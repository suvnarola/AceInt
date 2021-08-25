<?

	/**
	 * Class to process fee payments and reports.
	 *
	 * @package E Banc Administration Site
	 * @author Antony Puckey
	 * @copyright Copyright 2005, RDI Host Pty Ltd
	 */

	class feePayment extends ebancSuite {

		var $feeAmount;
		var $feeDate;
		var $feesOwing;
		var $poNumber;
		var $ccResult = array();
		var $memberRow = array();
		var $testServer = false;

		function feePayment($memID = false, $testServer = false) {

			global $ebancAdmin;

			if($memID) {

				$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_MEMBERS . ".* from " . DB_TABLE_MEMBERS . " where " . DB_TABLE_MEMBERS . ".memid = " . $memID, DB_NAME);
				$this->memberRow = @mysql_fetch_assoc($sqlQuery);
				if($this->memberRow) {

					$_SESSION['feePayment']['memberRow'] = $this->memberRow;

					$feesQuery = $ebancAdmin->dbRead("select sum(" . DB_TABLE_TRANSACTIONS . ".dollarfees) as feesOwing from " . DB_TABLE_TRANSACTIONS . " where memid = " . $this->memberRow['memid'] . " group by memid", DB_NAME);
					$feesRow = mysql_fetch_assoc($feesQuery);

					$_SESSION['feePayment']['feesOwing'] = $feesRow['feesOwing'];

					if($_REQUEST['accountFrom']) {

						$extraFees = number_format((($_REQUEST['paymentAmount']/100)*$_SESSION['feePayment']['memberRow']['transfeecash']),2,'.','');
						$_SESSION['feePayment']['feesOwing'] += $extraFees;

					}

				}

			}

			if($testServer) {

				$this->testServer = "1";

			}

		}

		function transferFees($fromMemberID, $toMemberID) {

			/**
			 * Update fees incurred and set everything that isnt paid off to the new account.
			 *
			 * add an entry into transactions for the amount that is left to pay off with type of 9 from reserve account
			 *
			 * add another entry into transaction into the new account for the same amount type 3 from the reserve account.
			 *
			 * copy from over_payment, fee_deductions and add into new account
			 *
			 */

			global $ebancAdmin;

			$fromMemberSQL = $ebancAdmin->dbRead("select members.* from members where memid = " . $fromMemberID);
			$toMemberSQL = $ebancAdmin->dbRead("select members.* from members where memid = " . $toMemberID);

			$fromMemberRow = mysql_fetch_assoc($fromMemberSQL);
			$toMemberRow = mysql_fetch_assoc($toMemberSQL);

			$balanceFromSQL = $ebancAdmin->dbRead("select sum(dollarfees) as fromFees from transactions where memid = " . $fromMemberID);
			$balanceFromRow = mysql_fetch_assoc($balanceFromSQL);

			$ebancAdmin->dbWrite("update " . DB_TABLE_MEMBERS . " set over_payment = (over_payment + " . $fromMemberRow['over_payment'] . "), fee_deductions = (fee_deductions + " . $fromMemberRow['fee_deductions'] . ") where memid = " . $toMemberID);

			$ebancAdmin->authNo();

			if($balanceFromRow['fromFees'] > 0) {

				$ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,userid) values ('" . $fromMemberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','-" . $balanceFromRow['fromFees'] . "','9','Transfer of Fees','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,userid) values ('" . $toMemberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','" . $balanceFromRow['fromFees'] . "','3','Transfer of Fees','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME);

				$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESINCURRED . ".* from " . DB_TABLE_FEESINCURRED . " where " . DB_TABLE_FEESINCURRED . ".memid = " . $fromMemberRow['memid'] . " and " . DB_TABLE_FEESINCURRED . ".fee_amount != " . DB_TABLE_FEESINCURRED . ".fee_paid order by " . DB_TABLE_FEESINCURRED . ".fieldid ASC", DB_NAME);

				while($sqlRow = mysql_fetch_assoc($sqlQuery)) {

					dbWrite("update " . DB_TABLE_FEESINCURRED . " set memid = '" . $toMemberID . "' where fieldid = " . $sqlRow['fieldid']);

				}

			} else {

				$ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,userid) values('" . $toMemberRow['memid'] . "', '" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','0','0','" . $balanceFromRow['fromFees'] . "','10','Transfer of Fees','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "',".$_SESSION['User']['FieldID'].")", DB_NAME);

			}

		}

		function ccPayment($memID, $ccAmount, $ccNO, $ccExpiryM, $ccExpiryY, $ccCVV, $ccType, $poNumber, $testServer = false) {

			if($testServer) {

				$ccPay = new SecurePay();

				print $ccPay->serverPath;

			} else {

				$ccPay = new SecurePay();

			}

			$ccPay->auth(MERCHANT_ID, MERCHANT_PASSWORD);
			$ccPay->payment($ccAmount, $poNumber, $ccNO, $ccType, $ccCVV, $ccExpiryM, $ccExpiryY);
			$ccPay->process();

			$this->ccResult = $ccPay->result();

			return $this->ccResult;

		}

		function ccMonthly() {

			// TODO:

		}

		function getFeesOwing() {

			// TODO:

		}

		function addCCTrans($memID,$type=false) {

			global $ebancAdmin;

			$poNumber = $ebancAdmin->dbWrite("insert into " . DB_TABLE_CREDIT . " (memid,date,userid,type) values ('" . $memID . "',now(),'" . $_SESSION['User']['FieldID'] . "','" . $type . "')", DB_NAME, 1);

			return $poNumber;

		}

		function feeReversal($memberRow, $transType, $feeAmount, $unHonourArray, $transDetails = false) {

			/**
			 * Type:
			 *  1: Goods and Services
			 *  2: Real Estate
			 *  3: Unhonour.
			 *
			 * if this is a stationery fee reversal take the amount off fee deductions and insert a transaction to reverse.
			 *
			 * if not stationery fee reversal then go backwards through the fee_deductions and get rid of the payments.
			 *
			 */

			global $ebancAdmin;

			$transDetails = ($transDetails) ? $transDetails : "Fee Reversal";

			switch($transType) {

				case "1":

					$ebancAdmin->authNo();

					$ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,userid) values('" . $memberRow['memid'] . "', '" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','0','0','-" . $feeAmount . "','9','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','".$_SESSION['User']['FieldID']."')", DB_NAME);

					if($_REQUEST['fees'] == 1)  {

						if($memberRow['fee_deductions'] != "0") {

							$finalDeduction = $memberRow['fee_deductions'] - $feeAmount;

							if($finalDeduction <= 0) {

								$finalDeduction = 0;

							}

						}

						$ebancAdmin->dbWrite("update  " . DB_TABLE_MEMBERS . " set " . DB_TABLE_MEMBERS . ".fee_deductions = '" . $finalDeduction . "' where  " . DB_TABLE_MEMBERS . ".memid = '" . $memberRow['memid'] . "'", DB_NAME);

					} else {

						/**
						 * Loop around the fees incurred as if we are paying fees off.
						 *
						 */
						if($_REQUEST['fees'] == 2) {
							$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESINCURRED . ".* from " . DB_TABLE_FEESINCURRED . " where " . DB_TABLE_FEESINCURRED . ".memid = " . $memberRow['memid'] . " and " . DB_TABLE_FEESINCURRED . ".fee_amount != " . DB_TABLE_FEESINCURRED . ".fee_paid and fee_amount = 11 and percent = 50 order by " . DB_TABLE_FEESINCURRED . ".fieldid ASC", DB_NAME);
						} else {
							$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESINCURRED . ".* from " . DB_TABLE_FEESINCURRED . " where " . DB_TABLE_FEESINCURRED . ".memid = " . $memberRow['memid'] . " and " . DB_TABLE_FEESINCURRED . ".fee_amount != " . DB_TABLE_FEESINCURRED . ".fee_paid and fee_amount != 11 and percent != 50 order by " . DB_TABLE_FEESINCURRED . ".fieldid ASC", DB_NAME);
						}

					 	$payAmount = $feeAmount;

						while($sqlRow = mysql_fetch_assoc($sqlQuery)) {

							if($payAmount > 0) {

								if($payAmount >= ($sqlRow['fee_amount'] - $sqlRow['fee_paid'])) {

									/**
									 * amount larger than this transaction. make it equal and take amount off $payAmount;
									 */

									dbWrite("update feesincurred set fee_paid = fee_amount where fieldid = " . $sqlRow['fieldid']);

									$payAmount -= ($sqlRow['fee_amount'] - $sqlRow['fee_paid']);

								} else {

									dbWrite("update feesincurred set fee_paid = (fee_paid + " . $payAmount . ") where fieldid = " . $sqlRow['fieldid']);

									$payAmount = 0;

								}

							}

						}

						if($payAmount) {

							dbWrite("update members set over_payment = (over_payment + " . $payAmount . ") where memid = " . $memberRow['memid']);

						}

					}

					break;

				case "2":

					$ebancAdmin->authNo();

					$ebancAdmin->dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,userid) values('" . $memberRow['memid'] . "', '" . mktime() . "','" . $_SESSION['Country']['rereserve'] . "','0','0','-" . $feeAmount . "','3','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','".$_SESSION['User']['FieldID']."')", DB_NAME);

					break;

				case "3":

					/**
					 * Loop Around all the fees that where selected and refund them.
					 */

					foreach($unHonourArray as $key => $value) {

						//$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESINCURRED . ".* from " . DB_TABLE_FEESINCURRED . " where " . DB_TABLE_FEESINCURRED . ".memid = " . $memberRow['memid'] . " and " . DB_TABLE_FEESINCURRED . ".fee_paid != 0 order by " . DB_TABLE_FEESINCURRED . ".fieldid DESC", DB_NAME);

						$feePaidSQL = dbRead("select feespaid.* from feespaid where id = " . $value);
						$feesPaidRow = mysql_fetch_assoc($feePaidSQL);

						//$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESPAID . ".* from " . DB_TABLE_FEESPAID . " where " . DB_TABLE_FEESPAID . ".transID = " . $feesPaidRow['transID'] . " and type != 1 order by " . DB_TABLE_FEESPAID . ".id", DB_NAME);
						$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESPAID . ".* from " . DB_TABLE_FEESPAID . " where " . DB_TABLE_FEESPAID . ".transID = " . $feesPaidRow['transID'] . " and transID != 0 order by " . DB_TABLE_FEESPAID . ".id", DB_NAME);

						  //$areaSQL = dbRead("select area.* from area where FieldID = " . $memberRow['licensee']);
						  //$areaRow = mysql_fetch_assoc($areaSQL);

						  //$chargePercent = $feesPaidRow['percent'];

    					//$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $feesPaidRow['memid'] . "','" . date("Y-m-d") . "','-" . $feesPaidRow['amountpaid'] . "','-" . $feesPaidRow['deducted_fees'] . "','" . $chargePercent . "','" . $memberRow['licensee'] . "','1','" . $sqlRow['fieldid'] . "')", DB_NAME, true);

						  $payAmount = $feesPaidRow['amountpaid'] - $feesPaidRow['deducted_fees'];
						  //$total += $feesPaidRow['amountpaid'];
						  $total = 0;

						if($feesPaidRow['deducted_fees'] > 0) {

							dbWrite("update members set fee_deductions = (fee_deductions + " . $feesPaidRow['deducted_fees'] . ") where memid = " . $feesPaidRow['memid']);

						}

						dbWrite("update members set over_payment = 0 where memid = " . $memberRow['memid']);

						while($sqlRow = mysql_fetch_assoc($sqlQuery)) {

									if($sqlRow['type'] == 1) {
										$total += $sqlRow['amountpaid'];
									}

							//if($payAmount > 0) {

									//if($sqlRow['type'] == 9) {
									if($sqlRow['type'] != 4) {
										//$feesPaidIDArray[] = $this->interFee($sqlRow, "-".$sqlRow['amountpaid'], $sqlRow['fieldid']);
    								  	//$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $sqlRow['memid'] . "','" . date("Y-m-d") . "','-" . $sqlRow['amountpaid'] . "','0','" . $sqlRow['percent'] . "','" . $sqlRow['area'] . "','9','" . $sqlRow['feesincurrid'] . "')", DB_NAME, true);
    								  	$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $sqlRow['memid'] . "','" . date("Y-m-d") . "','-" . $sqlRow['amountpaid'] . "','0','" . $sqlRow['percent'] . "','" . $sqlRow['area'] . "','" . $sqlRow['type'] . "','" . $sqlRow['feesincurrid'] . "')", DB_NAME, true);
									} else {
    								  	$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $sqlRow['memid'] . "','" . date("Y-m-d") . "','-" . $sqlRow['amountpaid'] . "','0','" . $sqlRow['percent'] . "','" . $sqlRow['area'] . "','4','" . $sqlRow['feesincurrid'] . "')", DB_NAME, true);
    									dbWrite("update feesincurred set fee_paid = (fee_paid - " . $sqlRow['amountpaid'] . ") where fieldid = " . $sqlRow['feesincurrid']);
    									$payAmount -= $sqlRow['amountpaid'];
									}



		if($fff) {
								$chargePercentTo = $sqlRow['to_percent'];

    							if($sqlRow['fee_paid'] > $payAmount) {

    								/**
    								 * Take off payamount off this feesincurred and set $payAmount to 0;
    								 */
									if($sqlRow['type'] == 9) {
									  $feesPaidIDArray[] = $this->interFee($sqlRow, "-".$payAmount, $sqlRow['fieldid']);

									} else {
    								$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $sqlRow['to_memid'] . "','" . date("Y-m-d") . "','-" . $payAmount . "','0','" . $chargePercentTo . "','" . $sqlRow['to_licensee'] . "','4','" . $sqlRow['fieldid'] . "')", DB_NAME, true);

    								dbWrite("update feesincurred set fee_paid = (fee_paid - " . $payAmount . ") where fieldid = " . $sqlRow['fieldid']);

    								$payAmount = 0;
									}
    							} else {

									if($sqlRow['type'] == 9) {
									  $feesPaidIDArray[] = $this->interFee($sqlRow, "-".$sqlRow['fee_paid'], $sqlRow['fieldid']);

									} else {
    								$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $sqlRow['to_memid'] . "','" . date("Y-m-d") . "','-" . $sqlRow['fee_paid'] . "','0','" . $chargePercentTo . "','" . $sqlRow['to_licensee'] . "','4','" . $sqlRow['fieldid'] . "')", DB_NAME, true);

    								dbWrite("update feesincurred set fee_paid = 0 where fieldid = " . $sqlRow['fieldid']);
    								$payAmount -= $sqlRow['fee_paid'];
									}
    							}
			}
							}

						//}

					}


					/**
					 * Add a transaction in the transactions table with the total amount that was reversed.
					 */

					$ebancAdmin->authNo();
					$transID = $ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,userid) values('" . $memberRow['memid'] . "', '" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','0','0','" . abs($total) . "','10','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "',".$_SESSION['User']['FieldID'].")", DB_NAME, TRUE);

        			foreach($feesPaidIDArray as $key => $value) {

        				if($value) {

        					$ebancAdmin->dbWrite("update feespaid set transID = '" . $transID . "' where id = " . $value);

        				}

        			}

					break;

			}

		}

		function addFees($transID, $fromMemberRow, $toMemberRow, $feeAmount, $transDetails) {

			global $ebancAdmin;

			if($transID) {

				/**
				 * Adding fees onto a transaction as we have a transactions id.
				 *
				 * Update Transaction record.
				 * Add record into feesincurred.
				 *
				 * Check over_payment. if there is overpayment then take this fees off overpayment and add into fees paid.
				 *
				 */

				$areaRow = $ebancAdmin->dbRead("select area.* from area where FieldID = " . $fromMemberRow['licensee']);
				$areaObj = mysql_fetch_object($areaRow);

				$areaRowTo = $ebancAdmin->dbRead("select area.* from area where FieldID = " . $toMemberRow['licensee']);
				$areaObjTo = mysql_fetch_object($areaRowTo);

				$chargePercent = $areaObj->feepercent / 2;
				$chargePercentTo = $areaObjTo->feepercent / 2;

				$ebancAdmin->dbWrite("update " . DB_TABLE_TRANSACTIONS . " set " . DB_TABLE_TRANSACTIONS . ".dollarfees = '" . $feeAmount . "' where " . DB_TABLE_TRANSACTIONS . "id = '" . $transID . "'", DB_NAME);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESINCURRED . " (date,memid,licensee,to_memid,to_licensee,trans_id,fee_amount,percent,to_percent) values ('".date("Y-m-d")."','".$fromMemberRow['memid']."','".$fromMemberRow['licensee']."','".$toMemberRow['memid']."','".$toMemberRow['licensee']."','".$transID."','".$feeAmount."','" . $chargePercent . "','" . $chargePercentTo . "')", DB_NAME);



			} else {

				/**
				 * Adding fees onto an account that doesn't have a transaction id.
				 *
				 * Add transaction in to transaction table.
				 * Update members and add onto members.fees_deductions.
				 *
				 * Hungry stuff goes in here too.
				 *
				 */

				$ebancAdmin->authNo();

				$ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,userid) values ('" . $fromMemberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','" . $feeAmount . "','1','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME);
				$ebancAdmin->dbWrite("update " . DB_TABLE_MEMBERS . " set " . DB_TABLE_MEMBERS . ".fee_deductions = (" . DB_TABLE_MEMBERS . ".fees_deductions + " . $feeAmount . ")");

			}

		}

		function payFees($memberRow, $feeAmount, $transType, $methodType, $paidTrade = false, $otherMemberRow = false, $transDetails = false, $feesIncurrID = false, $rollover = false) {

			global $ebancAdmin;

			if(!$_SESSION['Country']) {

				$countrySQL = $ebancAdmin->dbRead("select country.* from country where countryID = " . $memberRow['CID']);
				$countryRow = mysql_fetch_assoc($countrySQL);

				$_SESSION['Country'] = $countryRow;

			}

			$transDetails = ($transDetails) ? addslashes($transDetails) : "Cash Fee Payment";

			$payAmount = $feeAmount;

			/**
			 * Before we do anything check to see if this payment needs to pay off any adminsitration fees.
			 */

			$areaQuery = $ebancAdmin->dbRead("select " . DB_TABLE_AREA . ".* from " . DB_TABLE_AREA . " where " . DB_TABLE_AREA . ".FieldID = " . $memberRow['licensee'], DB_NAME);
			$areaRow = mysql_fetch_assoc($areaQuery);

			$ebancAdmin->authNo();

			$chargePercent = $areaRow['feepercent'] / 2;
			/**
			 * If we have $feesIncurrID then this is an ITT and we need to pay that payment off only.
			 */

			if($feesIncurrID) {

				$ebancAdmin->authNo();

				$feesIncurredQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESINCURRED . ".* from " . DB_TABLE_FEESINCURRED . " where fieldid = " . $feesIncurrID);
				$feesIncurredRow = mysql_fetch_assoc($feesIncurredQuery);

				$chargePercent = $feesIncurredRow['percent'];
				$chargePercentOther = $feesIncurredRow['to_percent'];

				$transID = $ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,clear_date,userid) values ('" . $memberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','-" . $feeAmount . "','" . $methodType . "','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME, TRUE);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','0','" . $chargePercent . "','" . $feesIncurredRow['licensee'] . "','1','" . $feesIncurrID . "','" . $transID . "')", DB_NAME);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $otherMemberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','0','" . $chargePercentOther . "','" . $feesIncurredRow['to_licensee'] . "','4','" . $feesIncurrID . "','" . $transID . "')", DB_NAME);
				$ebancAdmin->dbWrite("update " . DB_TABLE_FEESINCURRED . " set fee_paid = " . $feeAmount . " where fieldid = " . $feesIncurrID);

				$this->interFee($feesIncurredRow, $payAmount, $feesIncurrID, $transID);

				return 1;

			}

			if($transType != 2) {

    			if($memberRow['fee_deductions'] > 0) {

    				/**
    				 * Member has fee deductions. Take the cash paid off this amount first.
    				 *
    				 */

    				if($payAmount > $memberRow['fee_deductions']) {

    					$ebancAdmin->dbWrite("update " . DB_TABLE_MEMBERS . " set " . DB_TABLE_MEMBERS . ".fee_deductions = 0 where " . DB_TABLE_MEMBERS . ".memid = " . $memberRow['memid'], DB_NAME);

    					$payAmount -= $memberRow['fee_deductions'];

    				} else {

    					/**
    					 * We dont need to do anything else after this as the fee payment only covered the fee_deductions.
    					 *
    					 * return.
    					 */

    					$ebancAdmin->authNo();

    					$ebancAdmin->dbWrite("update " . DB_TABLE_MEMBERS . " set " . DB_TABLE_MEMBERS . ".fee_deductions = (" . DB_TABLE_MEMBERS . ".fee_deductions - " . $payAmount . ") where " . DB_TABLE_MEMBERS . ".memid = " . $memberRow['memid'], DB_NAME);
    					$transID = $ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,clear_date,userid) values ('" . $memberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','-" . $feeAmount . "','" . $methodType . "','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME, TRUE);
    					$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','" . $payAmount . "','" . $chargePercent . "','" . $memberRow['licensee'] . "','1','0','" . $transID . "')", DB_NAME);

    					return 1;

    				}

    			}

			}

			/**
			 * insert record into feespaid for the buyer.
			 *
			 * total fees paid and the amount of fee deductions.
			 *
			 */

			$sqlQuery = $ebancAdmin->dbRead("select " . DB_TABLE_FEESINCURRED . ".* from " . DB_TABLE_FEESINCURRED . " where " . DB_TABLE_FEESINCURRED . ".memid = " . $memberRow['memid'] . " and " . DB_TABLE_FEESINCURRED . ".fee_amount != " . DB_TABLE_FEESINCURRED . ".fee_paid order by " . DB_TABLE_FEESINCURRED . ".fieldid ASC", DB_NAME);

			if(!mysql_num_rows($sqlQuery) && $transType != 2) {

				/**
				 * There is no transactions in the feesincurred.
				 *
				 * Update members over_payment field and return 1.
				 *
				 */

				$ebancAdmin->authNo();

				$feesDeducted = $feeAmount - $payAmount;

				$ebancAdmin->dbWrite("update members set " . DB_TABLE_MEMBERS . ".over_payment = (" . DB_TABLE_MEMBERS . ".over_payment + " . $payAmount . ") where " . DB_TABLE_MEMBERS . ".memid = " . $memberRow['memid'], DB_NAME);
				$transID = $ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,clear_date,userid) values ('" . $memberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','-" . $feeAmount . "','" . $methodType . "','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME, TRUE);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $feeAmount . "','" . $feesDeducted . "','" . $chargePercent . "','" . $memberRow['licensee'] . "','1','0','".$transID."')", DB_NAME);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $feeAmount . "','" . $feesDeducted . "','" . $chargePercent . "','" . $_SESSION['Country']['DefaultArea'] . "','6','0','".$transID."')", DB_NAME);

				return 1;

			}

			$afee = 0;

			while($sqlRow = mysql_fetch_assoc($sqlQuery)) {

				$toMemberSql = $ebancAdmin->dbRead("select " . DB_TABLE_MEMBERS . ".* from " . DB_TABLE_MEMBERS . " where " . DB_TABLE_MEMBERS . ".memid = " . $sqlRow['to_memid'], DB_NAME);
				$toMemberRow = mysql_fetch_assoc($toMemberSql);

				$chargePercentTo = $sqlRow['to_percent'];
				$chargePercent = $sqlRow['percent'];

				if($payAmount >= ($sqlRow['fee_amount'] - $sqlRow['fee_paid']) && abs($payAmount) != 0) {

					/**
					 * Fee payment amount is more than what this transaction was.
					 *
					 * Make it the same and take that off the $payAmount ready for the next transaction.
					 *
					 * Keep going until its 0.
					 *
					 * add an entry into feespaid for this transaction aswell.
					 *
					 */

					if($transType != 2) {

						$feesIncurredID = $ebancAdmin->dbWrite("update " . DB_TABLE_FEESINCURRED . " set " . DB_TABLE_FEESINCURRED . ".fee_paid = " . DB_TABLE_FEESINCURRED . ".fee_amount where " . DB_TABLE_FEESINCURRED . ".fieldid = " . $sqlRow['fieldid'], DB_NAME, true);

					}

					$newAmount = $sqlRow['fee_amount'] - $sqlRow['fee_paid'];

					switch($transType) {

						case "1":

							/**
							 * Goods and services fee payment.
							 *
							 * add 2 transactions into feespaid.
							 *
							 * Type:
							 *  4: Seller
							 */

							if(!$paidTrade) {

								if($newAmount > 0) {

									$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $toMemberRow['memid'] . "','" . date("Y-m-d") . "','" . $newAmount . "','0','" . $chargePercentTo . "','" . $sqlRow['to_licensee'] . "','4','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
									  //$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $newAmount . "','0','" . $chargePercent . "','" . $sqlRow['licensee'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
									  $feesPaidIDArray[] = $this->interFee($sqlRow, $newAmount, $sqlRow['fieldid']);
									  //if($sqlRow['to_memid'] == 29253 && $sqlRow['fee_amount'] == 11 && $sqlRow['percent'] == 50) {
									  //if($sqlRow['to_memid'] == $_SESSION['Country']['adminacc'] && $sqlRow['fee_amount'] == $_SESSION['Country']['admin_fee'] && $sqlRow['percent'] == 50) {
									  if($sqlRow['to_memid'] == $_SESSION['Country']['adminacc'] && $sqlRow['percent'] == 50) {
									    $afee += $newAmount;
									  }
								}

							}

							break;

						case "2":

							/**
							 * Realestate Payment.
							 *
							 * add 2 transactions into feespaid.
							 *
							 * Type:
							 *  3: Seller ( Only if different area )
							 */

							if(!$paidTrade) {

								//$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $toMemberRow['memid'] . "','" . date("Y-m-d") . "','" . $newAmount . "','0','" . $chargePercent . "','" . $toMemberRow['licensee'] . "','3','" . $sqlRow['fieldid'] . "')", DB_NAME, true);

							}

							break;

					}

					$payAmount -= ($sqlRow['fee_amount'] - $sqlRow['fee_paid']);
					$newAmount = 0;

				} else {

					/**
					 * the payment wont pay off the last fee that was incurred.
					 *
					 * Update record and add $payAmount onto the fee_paid for this transaction.
					 *
					 * add an entry into feespaid for this part transaction.
					 *
					 * we only need to do this ONCE.
					 *
					 */

					$newAmount = $sqlRow['fee_amount'] - $sqlRow['fee_paid'];

					if(!$noRepeat) {

						if($transType != 2) {

							$feesIncurredID = $ebancAdmin->dbWrite("update " . DB_TABLE_FEESINCURRED . " set " . DB_TABLE_FEESINCURRED . ".fee_paid = (" . DB_TABLE_FEESINCURRED . ".fee_paid + " . $payAmount . ") where " . DB_TABLE_FEESINCURRED . ".fieldid = " . $sqlRow['fieldid'], DB_NAME, true);

						}

						switch($transType) {

							case "1":

								/**
								 * Goods and services fee payment.
								 *
								 * Type:
								 *  4: Seller
								 */

								if(!$paidTrade) {

									if($newAmount > 0 && abs($payAmount) != 0) {

										$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $toMemberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','0','" . $chargePercentTo . "','" . $sqlRow['to_licensee'] . "','4','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
									      //$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','0','" . $chargePercent . "','" . $sqlRow['licensee'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
									  	  $feesPaidIDArray[] = $this->interFee($sqlRow, $payAmount, $sqlRow['fieldid']);
									  //if($sqlRow['to_memid'] == 29253 && $sqlRow['fee_amount'] == 11 && $sqlRow['percent'] == 50) {
									  //if($sqlRow['to_memid'] == $_SESSION['Country']['adminacc'] && $sqlRow['fee_amount'] == $_SESSION['Country']['admin_fee'] && $sqlRow['percent'] == 50) {
									  if($sqlRow['to_memid'] == $_SESSION['Country']['adminacc'] && $sqlRow['percent'] == 50) {
										$afee += $payAmount;
									  }
									}

								}

								break;

							case "2":

								/**
								 * Realestate Payment.
								 *
								 * Type:
								 *  3: Seller ( Only if different area )
								 */

								if(!$paidTrade) {

									//$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $toMemberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','0','" . $chargePercent . "','" . $toMemberRow['licensee'] . "','3','" . $sqlRow['fieldid'] . "')", DB_NAME, true);

								}

								break;

						}

						$payAmount = 0;
						$noRepeat = 1;

					}

				}


			}

			/**
			 * Insert Feespaid transaction in for the amount that was paid off in the fees_incurred table.
			 *
			 * Insert a transaction in the transactions table for the amount that was paid in total.
			 *
			 */

			$chargePercent = $areaRow['feepercent'] / 2;

			$feesPaidTotal = $feeAmount - $payAmount;

			if(!$paidTrade) {

				if($transType == 2) {

				  if($rollover) {

					$invSql = $ebancAdmin->dbRead("select FieldID, amount, payment, amount-payment as owing from invoice_re where invoice_re.memid = " . $memberRow['memid'] . " and amount-payment > 0 order by date", DB_NAME);

					$ffee = $feeAmount;

					while($invRow = mysql_fetch_assoc($invSql)) {

						if($ffee >= $invRow['owing'] && abs($ffee) != 0) {

							$ebancAdmin->dbWrite("update invoice_re set payment = '" . $invRow['amount'] . "' where FieldID = " . $invRow['FieldID'], DB_NAME);

							$ffee = $ffee-$invRow['owing'];

						} else {

							if(!$noRepeat) {
								$npayment = $invRow['payment']+$ffee;
								$ebancAdmin->dbWrite("update invoice_re set payment = '" . $npayment . "' where invoice_re.FieldID = " . $invRow['FieldID'], DB_NAME);
								$ffee = 0;
								$noRepeat = 1;
							}
						}

				    }

				  } else {

					$otherSql = $ebancAdmin->dbRead("select " . DB_TABLE_AREA . ".* from " . DB_TABLE_AREA . " where " . DB_TABLE_AREA . ".FieldID = " . $otherMemberRow['licensee'], DB_NAME);
					$otherRow = mysql_fetch_assoc($otherSql);

				    $chargePercentOther = $otherRow['feepercent']/2;

					$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $feeAmount . "','0','" . $chargePercent . "','" . $memberRow['licensee'] . "','2','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
					$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $otherMemberRow['memid'] . "','" . date("Y-m-d") . "','" . $feeAmount . "','0','" . $chargePercentOther . "','" . $otherMemberRow['licensee'] . "','3','" . $sqlRow['fieldid'] . "')", DB_NAME, true);

					   $rrow[memid] = $memberRow['memid'];
					   $rrow[licensee] = $memberRow['licensee'];
					   $rrow[to_memid] = $otherMemberRow['memid'];
					   $rrow[to_licensee] = $otherMemberRow['licensee'];

					   $feesPaidIDArray[] = $this->interFee($rrow, $feeAmount, $sqlRow['fieldid'], '', true);
				  }

				} else {
					if($sqlRow['percent']) {
						$chargePercent = $sqlRow['percent'];
					}

					if($afee) {
						$afees = $feeAmount-$afee;
						//if($memberRow['letters'] == 3 || $memberRow['letters'] == 4) {
						if(($memberRow['letters'] == 3 && $memberRow['status'] == 5) || $memberRow['letters'] == 4 || $memberRow['letters'] == 5 || $memberRow['letters'] == 6) {
							$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $afee . "','0','50','" . $_SESSION['Country']['DefaultArea'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
						} else {
							$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $afee . "','0','50','" . $memberRow['licensee'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
						}
						if($afees) {
							$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $afees . "','" . $memberRow['fee_deductions'] . "','" . $chargePercent . "','" . $memberRow['licensee'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
						}
					} else {
						$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $feeAmount . "','" . $memberRow['fee_deductions'] . "','" . $chargePercent . "','" . $memberRow['licensee'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
					}
					//if($memberRow['fee_deductions'] != 0) {
					  //$feesPaidIDArray[] = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $memberRow['fee_deductions'] . "','" . $memberRow['fee_deductions'] . "','" . $chargePercent . "','" . $memberRow['licensee'] . "','" . $transType . "','" . $sqlRow['fieldid'] . "')", DB_NAME, true);
					//}
				}

			}

			if($transType == 2) {

				$transID = $ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,clear_date,userid) values ('" . $memberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['rereserve'] . "','-" . $feeAmount . "','" . $methodType . "','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME, true);

			} else {

				$transID = $ebancAdmin->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,dollarfees,type,details,authno,dis_date,clear_date,userid) values ('" . $memberRow['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','-" . $feeAmount . "','" . $methodType . "','" . $transDetails . "','" . $ebancAdmin->authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "')", DB_NAME, true);

			}

			if($feesPaidIDArray) {

    			foreach($feesPaidIDArray as $key => $value) {

    				if($value) {

    					$ebancAdmin->dbWrite("update " . DB_TABLE_FEESPAID . " set transID = '" . $transID . "' where id = " . $value);

    				}

    			}

			}

			if($paidTrade) {

				/**
				 * If this transaction has been paid in trade then we need to transfer from the members account
				 * to the countries expense account.
				 */

				if($_REQUEST['gst']) {

					$feeAmount = ($feeAmount/(100+$_SESSION['Country']['tax']))*100;

				}

				$ebancAdmin->addTransaction($memberRow['memid'],'0',$_SESSION['Country']['expense'],'0',date("Y-m-d"),$feeAmount,$transDetails,'',1);

			}

			if($memberRow['goldcard']) {

					/**
					 * Gold card.
					 *
					 * If member has a gold card and this is a goods and services transaction. add a transaction for.
					 */

				//$ebancAdmin->addTransaction($_SESSION['Country']['expense'],'0',$memberRow['memid'],'0',date("Y-m-d"),'0',"Gold Card Rewards",'',1);

			}

			if($payAmount > 0.001 && $transType != 2) {

				/**
				 * We have left over money from over payment. Add the left over amount onto members.over_paymenet
				 */

				$ebancAdmin->dbWrite("update " . DB_TABLE_MEMBERS . " set " . DB_TABLE_MEMBERS . ".over_payment = (" . DB_TABLE_MEMBERS . ".over_payment + " . $payAmount . ") where " . DB_TABLE_MEMBERS . ".memid = " . $memberRow['memid'], DB_NAME);
				$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $payAmount . "','0','" . $chargePercent . "','" . $_SESSION['Country']['DefaultArea'] . "','6','0','".$transID."')", DB_NAME);

			}

			return 1;

		}


		function interFee($FIrow, $pay, $fIncurrID = false, $tID = false, $re = false) {

			global $ebancAdmin;

		    $fromAreaSQL = $ebancAdmin->dbRead("select " . DB_TABLE_AREA . ".* from " . DB_TABLE_AREA . " where FieldID = " . $FIrow['licensee']);
		    $toAreaSQL = $ebancAdmin->dbRead("select " . DB_TABLE_AREA . ".* from " . DB_TABLE_AREA . " where FieldID = " . $FIrow['to_licensee']);

		    $fromAreaRow = mysql_fetch_assoc($fromAreaSQL);
		    $toAreaRow = mysql_fetch_assoc($toAreaSQL);

			//if($fromAreaRow['CID'] == 1 || $fromAreaRow['CID'] == 12) {
			if($_SESSION['Country']['inter_per']) {
				$perTotal = 0;

				if($re) {
					if($fromAreaRow['inter'] == 'Y') {
						$perTotal += $fromAreaRow['feepercent']/2;
					}
					if($toAreaRow['inter'] == 'Y') {
						$perTotal += $toAreaRow['feepercent']/2;
					}
				} else {
					if($fromAreaRow['inter'] == 'Y') {
						$perTotal += $FIrow['percent'];
					}
					if($toAreaRow['inter'] == 'Y') {
						$perTotal += $FIrow['to_percent'];
					}
				}

				//if($FIrow['to_memid'] == 29253 && $FIrow['fee_amount'] == 11 && $FIrow['percent'] == 50) {
				//if($FIrow['to_memid'] == $_SESSION['Country']['reserveacc'] && $FIrow['fee_amount'] == $_SESSION['Country']['admin_fee'] && $FIrow['percent'] == 50 && $_SESSION['Country']['countryID'] == 1) {
				//if($FIrow['to_memid'] == $_SESSION['Country']['adminacc'] && $FIrow['fee_amount'] == $_SESSION['Country']['admin_fee'] && $FIrow['percent'] == 50) {
				if($FIrow['to_memid'] == $_SESSION['Country']['adminacc'] && $FIrow['percent'] == 50) {
				  $per = $_SESSION['Country']['inter_per'];
				  if($fromAreaRow['inter'] == 'Y') {
				  	$perTotal = 50;
				  } else {
				  	$perTotal = 0;
				  }
				  //$per = 20;
				  //$perTotal = 50;
				  //$amPer = ($pay/11)*100;
				  //$am = (3.30/100)*$amPer;
				  //$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $FIrow['memid'] . "','" . date("Y-m-d") . "','". $am ."','0','0','" . $FIrow['licensee'] . "','8','" . $fIncurrID . "','" . $tID . "')", DB_NAME, true);
				} elseif($_REQUEST['plp']) {
				  $per = 100;
				} else {
				  //$per = 20;
				  $per = $_SESSION['Country']['inter_per'];
				}

				$interFees = ($pay*((100-$perTotal)/100)*($per/100));
				$yy = $ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $FIrow['memid'] . "','" . date("Y-m-d") . "','" . $interFees . "','0','" . $perTotal . "','" . $FIrow['licensee'] . "','9','" . $fIncurrID . "','" . $tID . "')", DB_NAME, true);

				return $yy;
			}
		}

	}

?>