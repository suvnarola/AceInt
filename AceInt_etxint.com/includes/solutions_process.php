<?

 include("/home/etxint/admin.etxint.com/includes/modules/class.xmlCreditCard.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");
 include("/home/etxint/admin.etxint.com/includes/modules/db.php");
 include("modules/functions.transaction.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");
 include("class.html.mime.mail.inc");

if(!checkmodule("MyServices")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81")?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

$query5 = dbRead("select position, data from tbl_members_data, tbl_members_pages where (tbl_members_data.pageid = tbl_members_pages.FieldID) and langcode='".$_SESSION['Country']['Langcode']."' and pageid = 66 and CID = '" . $_SESSION['Country']['countryID'] . "' order by position");
while($row = mysql_fetch_array($query5)) {

   $this->PageData2[$row[position]] = $row[data];

}

$planSQL = dbRead("select plans.* from plans where FieldID = 1", "empire_solutions");
$planObj = mysql_fetch_object($planSQL);

if($_REQUEST['processPayment']) {

	    if($_REQUEST['add']) {

			$memAccSQL = dbRead("select * from members where memid = '" . addslashes($_REQUEST['memid']) . "'");
			$memAccObj = @mysql_fetch_object($memAccSQL);

		    $cashFee = (addslashes($_REQUEST['totalTrade']) / 100) * $memAccObj->transfeecash;

	        $regAccID = dbWrite("insert into registered_accounts (Acc_No,Bank_Number,Bank_BSB,Bank_Name,Plan_ID,Status_ID,Date_Joined,Date_Renewal,Terms,Plan_Amount,cashToPay,tradeToPay,Payments_Left,cashFeePaid) values ('" . $_REQUEST['memid'] . "','" . $_REQUEST['elecAccNo'] . "','" . $_REQUEST['elecBpayCode'] . "','" . $_REQUEST['elecComp'] . "','1','0','" . date("Y-m-d") . "','00-00-0000','" . $planObj->Plan_Terms . "','" . addslashes($_REQUEST['weekAmount']) . "','" . addslashes($_REQUEST['totalCash']) . "','" . addslashes($_REQUEST['totalTrade']) . "','" . $planObj->Plan_Terms . "','" . $cashFee . "')","empire_solutions", TRUE);

		}

 		if($_REQUEST['regID']) {

	 		$regAccSQL = dbRead("select registered_accounts.* from registered_accounts where FieldID = " . $_REQUEST['regID'],"empire_solutions");
	 		$regAccObj = @mysql_fetch_object($regAccSQL);

 		}

		$serviceSQL = dbRead("select plans.*, services.* from plans, services where (services.FieldID = plans.ServiceID) and plans.FieldID = '" . $regAccObj->Plan_ID . "'", "empire_solutions");
		$serviceObj = mysql_fetch_object($serviceSQL);


		if($_REQUEST['processCashFees']) {

		  if($ggg) {
			/**
			 * Process Credit Card.
			 */

		  	$ccCheckProc = new SecurePay();

		 	$checkArray = $ccCheckProc->validate('1', '1', addslashes($_REQUEST['ccCardNumber']), addslashes($_REQUEST['ccCardType']), '1', addslashes($_REQUEST['ccMonth']), addslashes($_REQUEST['ccYear']));

		 	if(!$ccCheckProc->validate_errors) {

				/**
				 * Process Credit Card Here.
				 */

				$ccPay = new SecurePay();

				$ccPay->auth("ebt0022", "k87jkqdam");
				$ccPay->payment($_REQUEST['totalCashFee'], "EBS" . $regAccObj->AccNo, addslashes($_REQUEST['ccCardNumber']), addslashes($_REQUEST['ccCardType']), "", addslashes($_REQUEST['ccMonth']), addslashes($_REQUEST['ccYear']));

				if(!$ccPay->validate_errors) {

					$ccPay->process();

					$ccResult = $ccPay->result();

					if($ccResult['APPROVED'] == "Yes") {

						$ebancAdmin = new ebancSuite();
						$feePayment = new feePayment($regAccObj->Acc_No);

						$otherSQL = dbRead("select members.* from members where memid = '27384'");
						$otherRow = mysql_fetch_assoc($otherSQL);

						dbWrite("insert into credit_transactions (memid, success, amount, response_code, response_text, sp_trans_id, date, card_type, card_name, userid, type) values ('" . $regAccObj->Acc_No . "','YES','" . $_REQUEST['totalCashFee'] . "','" . $ccResult['RESPONSECODE'] . "','" . $ccResult['RESPONSETEXT'] . "','" . $ccResult['TXNID'] . "','" . date("Y-m-d") . "','" . $_REQUEST['ccCardType'] . "','" . $_REQUEST['ccCardName'] . "','" . $regAccObj->Acc_No . "','10')");
						dbWrite("update registered_accounts set creditFeesReceiptNo = '" . $ccResult['TXNID'] . "', cashFeePaid = '" . $_REQUEST['totalCashFee'] . "' where FieldID = " . $regAccObj->FieldID,"empire_solutions");
						$feePayment->payFees($regAccObj->Acc_No, $_REQUEST['totalCashFee'], 1, 5, "", $otherRow, "Solution ITT Fees", $Transfer->FeesIncurrID);

						if($goldObj->tradeToPay == $goldObj->tradeAmountPaid) {


						}

					} else {

						dbWrite("insert into credit_transactions (memid, success, amount, response_code, response_text, sp_trans_id, date, card_type, card_name, userid, type) values ('" . $regAccObj->Acc_No . "','NO','" . $_REQUEST['totalCashFee'] . "','" . $ccResult['RESPONSECODE'] . "','" . $ccResult['RESPONSETEXT'] . "','" . $ccResult['TXNID'] . "','" . date("Y-m-d") . "','" . $_REQUEST['ccCardType'] . "','" . $_REQUEST['ccCardName'] . "','" . $regAccObj->Acc_No . "','10')");
						$errorArray[$ccResult[RESPONSECODE]] = $ccResult["RESPONSETEXT"];

					}

				} else {

					$errorArray = $ccPay->validate_errors;

				}

			} else {

				/**
				 * Display Errors.
				 */

				$errorArray = $ccCheckProc->validate_errors;

			}
		  } else {

		    dbWrite("update registered_accounts set creditFeesReceiptNo = '" . $_REQUEST['creceipt'] . "', cashFeePaid = 0 where FieldID = " . $regAccObj->FieldID,"empire_solutions");

		  }
		}

 		if($_REQUEST['processCash']) {

 			/**
 			 * Process Credit Card.
 			 */
 		  if($gg) {
		  	$ccCheckProc = new SecurePay();

		 	$checkArray = $ccCheckProc->validate('1', '1', addslashes($_REQUEST['ccCardNumber']), addslashes($_REQUEST['ccCardType']), '1', addslashes($_REQUEST['ccMonth']), addslashes($_REQUEST['ccYear']));

		 	if(!$ccCheckProc->validate_errors) {

				/**
				 * Process Credit Card Here.
				 */

				$ccPay = new SecurePay();

				$ccPay->auth(MERCHANT_ID, MERCHANT_PASSWORD);
				$ccPay->payment($_REQUEST['totalCash'], "EBS" . $regAccObj->FieldID, addslashes($_REQUEST['ccCardNumber']), addslashes($_REQUEST['ccCardType']), "", addslashes($_REQUEST['ccMonth']), addslashes($_REQUEST['ccYear']));

				if(!$ccPay->validate_errors) {

					$ccPay->process();

					$ccResult = $ccPay->result();

					if($ccResult['APPROVED'] == "Yes") {

						$tt = $_REQUEST['totalCash']-$serviceObj->Plan_Fee;
						dbWrite("insert into credit_transactions (memid, success, amount, response_code, response_text, sp_trans_id, date, card_type, card_name, userid, type) values ('" . $regAccObj->Acc_No . "','YES','" . $_REQUEST['totalCash'] . "','" . $ccResult['RESPONSECODE'] . "','" . $ccResult['RESPONSETEXT'] . "','" . $ccResult['TXNID'] . "','" . date("Y-m-d") . "','" . $_REQUEST['ccCardType'] . "','" . $_REQUEST['ccCardName'] . "','" . $regAccObj->Acc_No . "','12')");
 						dbWrite("update registered_accounts set cashToPay = 0, creditReceiptNo = '" . $ccResult['TXNID'] . "' where FieldID = " . $regAccObj->FieldID, "empire_solutions");
						dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date, dollarfees) values ('" . $regAccObj->FieldID . "','" . $tt . "','1','Cash Payment - " . $regAccObj->FieldID . "','" . $ccResult['TXNID'] . "','" . date("Y-m-d") . "','" . $serviceObj->Plan_Fee . "')", "empire_solutions");
						//dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date) values ('" . $regAccObj->FieldID . "','" . $tt . "','1','Cash Payment - " . $regAccObj->FieldID . "','" . $ccResult['TXNID'] . "','" . date("Y-m-d") . "')", "empire_solutions");
  if($regAccObj->tradeToPay == 0) {

    dbWrite("update registered_accounts set Status_ID = 1, Date_Paid = '".date("Y-m-d")."' where FieldID = " . $regAccObj->FieldID, "empire_solutions");

  }
						if($regAccObj->tradeReceiptNo) {

 						  dbWrite("update registered_accounts set Status_ID = 1, Date_Paid = '".date("Y-m-d")."' where FieldID = " . $regAccObj->FieldID, "empire_solutions");

						  //$queryde = dbRead("SELECT * from reg_acc_details WHERE  reg_acc_id = ".$regAccObj->FieldID,"empire_solutions");
						  $queryde = dbRead("SELECT * from reg_acc_details WHERE  Acc_No = ".$regAccObj->Acc_No,"empire_solutions");
						  $rowde = mysql_fetch_assoc($queryde);

				          //$text = "Thank you for joining the ".$serviceObj->Product." programme.  Your full payment has been received and you will be receiving a confirmation letter with 10 working days.<br><br>My Services Banc.";

			 			  ob_start();
			 			  eval(" ?>".$serviceClass->get_page_data2(34)."<? ");
			 			  $output = ob_get_contents();
			 			  ob_end_clean();

			 			  $text = $output;

					      $text = get_services_template($_SESSION['Country']['countryID'], $rowde['accholder'], $text);
					      $subject = "My Services Banc - ".$serviceObj->Product;

					      $this->Mail = new PHPMailer();

					      $this->Mail->Priority = 3;
					      $this->Mail->CharSet = "utf-8";
					      $this->Mail->From = "info@myservicesbanc.com";
					      $this->Mail->FromName = "My Services Banc";
					      $this->Mail->Sender = "info@myservicesbanc.com";
					      $this->Mail->Subject = $subject;
					      $this->Mail->AddReplyTo("info@myservicesbanc.com", "My Services Banc");
						  $this->Mail->IsSendmail(true);
					      $this->Mail->Body = $text;
					      $this->Mail->IsHTML(true);

					      $this->Mail->AddAddress($rowde['emailaddress'], $rowde['accholder']);

					      //$this->Mail->Send();

						}

					} else {

						dbWrite("insert into credit_transactions (memid, success, amount, response_code, response_text, sp_trans_id, date, card_type, card_name, userid, type) values ('" . $regAccObj->Acc_No . "','NO','" . $_REQUEST['totalCash'] . "','" . $ccResult['RESPONSECODE'] . "','" . $ccResult['RESPONSETEXT'] . "','" . $ccResult['TXNID'] . "','" . date("Y-m-d") . "','" . $_REQUEST['ccCardType'] . "','" . $_REQUEST['ccCardName'] . "','" . $regAccObj->Acc_No . "','12')");
						$errorArray[$ccResult[RESPONSECODE]] = $ccResult["RESPONSETEXT"];

					}

				} else {

					$errorArray = $ccPay->validate_errors;

				}

			} else {

				/**
				 * Display Errors.
				 */

				$errorArray = $ccCheckProc->validate_errors;

			}
		   } else {

			  if($_REQUEST['ccreceipt']) {

			    $cah = $_REQUEST['totalCash']-$serviceObj->Plan_Fee;
				dbWrite("update registered_accounts set cashToPay = 0, creditReceiptNo = '" . $_REQUEST['ccreceipt'] . "' where FieldID = " . $regAccObj->FieldID, "empire_solutions");
				dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date, dollarfees) values ('" . $regAccObj->FieldID . "','" . $cah . "','1','Cash Payment - " . $regAccObj->FieldID . "','" . $_REQUEST['ccreceipt'] . "','" . date("Y-m-d") . "','" . $serviceObj->Plan_Fee . "')", "empire_solutions");
				if($regAccObj->tradeToPay == 0) {

				dbWrite("update registered_accounts set Status_ID = 1, Date_Paid = '".date("Y-m-d")."' where FieldID = " . $regAccObj->FieldID, "empire_solutions");

				}
		   	 }
		   }
 		}

 		if($_REQUEST['processTrade']) {

		  if($ggg) {
 			/**
 			 * Process Trade Component.
 			 * Just transfer the amount as we have a direct debit in place now.
 			 */

			$Transfer = new FundsTransfer();

			$Transfer->AddFrom($regAccObj->Acc_No);
			$Transfer->AddTo("29118");
			$Transfer->AddDate(date("d F Y", mktime() + $_SESSION['Country']['timezone']));
			$Transfer->AddAmount($_REQUEST['totalTrade'], $_SESSION['Country']['convert']);
			$Transfer->AddFees("Buyer", "", "Yes");
			$Transfer->AddFees("Seller", "", "Yes");
			$Transfer->AddWho($regAccObj->Acc_No);
			$Transfer->AddDetails($serviceObj->Product . " Ref: " . $regAccObj->FieldID);

 			$Transfer->DOTransfer(1);

 			dbWrite("update registered_accounts set tradeToPay = 0, tradeReceiptNo = '" . $Transfer->AuthNo . "' where FieldID = " . $regAccObj->FieldID, "empire_solutions");
			dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date) values ('" . $regAccObj->FieldID . "','" . $_REQUEST['totalTrade'] . "','2','Trade Payment - " . $regAccObj->FieldID . "','" . $Transfer->AuthNo . "','" . date("Y-m-d") . "')", "empire_solutions");

			if($regAccObj->creditReceiptNo) {

 			  dbWrite("update registered_accounts set Status_ID = 1, Date_Paid = '".date("Y-m-d")."' where FieldID = " . $regAccObj->FieldID, "empire_solutions");

			  //$queryde = dbRead("SELECT * from reg_acc_details WHERE  reg_acc_id = ".$regAccObj->FieldID,"empire_solutions");
			  $queryde = dbRead("SELECT * from reg_acc_details WHERE  Acc_No = ".$regAccObj->Acc_No,"empire_solutions");
			  $rowde = mysql_fetch_assoc($queryde);

	          //$text = "Thank you for joining the ".$serviceObj->Product." programme.  Your full payment has been received and you will be receiving a confirmation letter with 10 working days.<br><br>My Services Banc.";

			  ob_start();
			  eval(" ?>".$serviceClass->get_page_data2(34)."<? ");
			  $output = ob_get_contents();
			  ob_end_clean();

			  $text = $output;

		      $text = get_services_template($_SESSION['Country']['countryID'], $rowde['accholder'], $text);
		      $subject = "My Services Banc - ".$serviceObj->Product;

		      $this->Mail = new PHPMailer();

		      $this->Mail->Priority = 3;
		      $this->Mail->CharSet = "utf-8";
		      $this->Mail->From = "info@myservicesbanc.com";
		      $this->Mail->FromName = "My Services Banc";
		      $this->Mail->Sender = "info@myservicesbanc.com";
		      $this->Mail->Subject = $subject;
		      $this->Mail->AddReplyTo("info@myservicesbanc.com", "My Services Banc");
			  $this->Mail->IsSendmail(true);
		      $this->Mail->Body = $text;
		      $this->Mail->IsHTML(true);

		      $this->Mail->AddAddress($rowde['emailaddress'], $rowde['accholder']);

		      //$this->Mail->Send();
			}

 			unset($Transfer);
		  } else {

 			dbWrite("update registered_accounts set tradeToPay = 0, tradeReceiptNo = '" . $_REQUEST['treceipt'] . "' where FieldID = " . $regAccObj->FieldID, "empire_solutions");
			dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date) values ('" . $regAccObj->FieldID . "','" . $_REQUEST['totalTrade'] . "','2','Trade Payment - " . $regAccObj->FieldID . "','" . $_REQUEST['treceipt'] . "','" . date("Y-m-d") . "')", "empire_solutions");

  			if($regAccObj->cashToPay == 0) {
    			dbWrite("update registered_accounts set Status_ID = 1, Date_Paid = '".date("Y-m-d")."' where FieldID = " . $regAccObj->FieldID, "empire_solutions");
  			}
		  }



 		}

	$newID = ($regAccID) ? $regAccID : $regAccObj->FieldID;


 	processPayment('',$newID,'');

} else {

 	displayPayment();
}


	function displayPayment() {

		$planSQL = dbRead("select plans.* from plans where FieldID = 1", "empire_solutions");
		$planObj = mysql_fetch_object($planSQL);

		?>
		<script language="JavaScript" src="includes/ebancServicesJavascript.php?antiCache=<?= mt_rand(1,100000) ?>&Client=<?= $_REQUEST['memid'] ?>"></script>

			<div class="serviceDataCredit" id="paymentDisplay">
				<form method="post" name="paymentForm" action="body.php?page=solutions_process">
			 	<input type="hidden" name="processPayment" value="1">
			 	<input type="hidden" name="add" value="1">
			 	<input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">
			 	<input type="hidden" name="addTotals" value="1">
			 		<div class="displayText"><span style="color: #0000FF;"><b><?= get_page_data2(15) ?>: <?= $planObj->Plan_Display_Terms ?> for <?= $planObj->Plan_Terms ?></b></span> </div>
			 		<div class="leftHeading"><span style="color: #FF0000;"><b>Enter Amount:</b></span></div><div class="dataEntry"><input class="serviceInput" type="text" size="10" name="weeklyAmount" onFocus="calcTotals();" onblur="stopTotals();" value=""> <b><?= $_SESSION['Country']['convert'] ?></b> <? if($_SESSION['Country']['countryID'] == 12) { ?><input class="serviceInput bgLightGrey" type="text" size="10" name="weeklyAmountAUD" readonly="true" value="0.00"> <b>AUD</b><? } ?></div>
			 		<div class="displayText"><span style="color: #FF0000;"><b>(<?= get_page_data2(20) ?> $<?= $planObj->Min_Amount  ?><? if($planObj->Max_Amount > 0) { ?> - <?= get_page_data2(21) ?> $<?= $planObj->Max_Amount  ?><?  } ?>)</b></span> </div>
			 		<div class="leftHeading"><b>Total Principal:</b></div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="10" name="totalPlan" readonly="true" value="0.00"></div>
			 		<div class="leftHeading">Trade Amount:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="10" name="totalTrade" readonly="true" value="0.00"></div>
			 		<div class="leftHeading">Cash Amount:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="10" name="totalCash" readonly="true" value="<?= $planObj->Plan_Fee ?>"> <b>(<?= get_page_data2(22) ?> $<?= $planObj->Plan_Fee ?>.)</b></div>
					<div class="leftHeading">Weekly Repayment Am:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="10" name="weekAmount" readonly="true" value="0.00"></div>

	 		<div class="leftHeading">Bank Account Name:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="elecComp"></div>
	 		<div class="leftHeading">Bank Account BSB:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="elecBpayCode"></div>
	 		<div class="leftHeading">Bank Account No:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="elecAccNo"></div>

			 		<div class="submitService"><input class="serviceButton" type="submit" name="serviceCreditUpdate" value ="  Next > >  "></div>
			 	</form>
			</div>
	<?
	}


	function processPayment($errorArray = false, $regAccID, $otherPayment = false) {

		if($regAccID) {

			$regAccSQL = dbRead("select registered_accounts.* from registered_accounts where registered_accounts.FieldID = '" . $regAccID . "'","empire_solutions");
			$regAccObj = @mysql_fetch_object($regAccSQL);

			$memAccSQL = dbRead("select * from members where memid = '" . $regAccObj->Acc_No . "'");
			$memAccObj = @mysql_fetch_object($memAccSQL);

		}

		if($errorArray) {

			/**
			 * Summarise errors then display options below
			 */

		 	foreach($errorArray as $errKey => $errValue) {

		 		$dispError .= $errKey . ": " . $errValue . "<br>";

		 	}

		 	?>
		 		<div class="serviceError"><?= $dispError ?></div><br>
		 	<?

		}


		?>
		<div class="serviceDataCredit" id="paymentDisplay">
			<?

				//if($regAccObj->tradeToPay >= $_SESSION['Country']['memdailylimit'] || $_SESSION['Member']['status'] == 3) {

					?>
				 	<form method="post" name="paymentForm" action="body.php?page=solutions_process">
				 	<input type="hidden" name="processPayment" value="1">
				 	<input type="hidden" name="serviceID" value="<?= $_REQUEST['serviceID'] ?>">
					<input type="hidden" name="selectedAcc" value="<?= $regAccID ?>">
				 	<input type="hidden" name="processCashFees" value="1">
		 			<input type="hidden" name="regID" value="<?= $regAccObj->FieldID ?>">
				 	<div style="float: left;"><img border="0" src="images/etx-bw.jpg" width="103" height="36"></b></div><br>
				 		<div class="displayText"><span style="color: #0000FF; font-size: 12px;"><b>Transaction Fee Payment</b></span> </div>
				 		<div class="displayText"><span style="color: #000000; font-weight: normal"><?= get_page_data2(27) ?></span> </div><br>

		 				<div class="leftHeading">Cash Fee Receipt No:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" name="creceipt" ></div>
				 		<div class="leftHeading">Total Cash:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" readonly="true" name="totalCashFee" value="<?= $regAccObj->cashFeePaid ?>"></div>

<?if($ff) {?>
				 		<div class="leftHeading"><?= get_word(135) ?>:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="ccCardName" value="<?= $_REQUEST['ccCardName'] ?>"></div>
				 		<div class="leftHeading"><?= get_word(134) ?>:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="ccCardNumber" value="<?= $_REQUEST['ccCardNumber'] ?>"></div>
				 		<div class="leftHeading">Credit Card Expiry:</div><div class="dataEntry"><input class="serviceInput" type="text" size="2" onfocus="if(this.value=='MM')this.value=''" onblur="if(this.value=='')this.value='MM'" maxlength="2" name="ccMonth" value="<?= $_REQUEST['ccMonth'] ?>"> / <input class="serviceInput" type="text" size="2" onfocus="if(this.value=='YY')this.value=''" onblur="if(this.value=='')this.value='YY'" maxlength="2" name="ccYear" value="<?= $_REQUEST['ccYear'] ?>"></div>
				 		<div class="leftHeading">Credit Card Type:</div><div class="dataEntry">
				 			<select class="serviceInput" name="ccCardType">
								<option <? if($_REQUEST['ccCardType'] == "Mastercard") { print "selected"; } ?> value="Mastercard">Mastercard</option>
								<option <? if($_REQUEST['ccCardType'] == "Visa") { print "selected"; } ?> value="Visa">Visa</option>
								<option <? if($_REQUEST['ccCardType'] == "Bankcard") { print "selected"; } ?> value="Bankcard">Bankcard</option>
							</select>
				 		</div>
				 		<div class="leftHeading">Total Cash:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" readonly="true" name="totalCashFee" value="<?= $regAccObj->cashFeePaid ?>"></div>
				 		<?}?>
				 		<? if($regAccObj->creditFeesReceiptNo) { ?><div class="displayText"><span style="color: #FF0000;"><b><?= get_word(136) ?>: <?= $regAccObj->creditFeesReceiptNo?></b></span> </div><? } ?>
				 		<div class="submitService"><input class="serviceButton" type="submit" name="processCashFee" value ="  Click Me "<? if($regAccObj->cashFeePaid == 0) { print " disabled"; } ?>></div>
				 		<div class="displayText"><hr size="1"></div>
				 	</form>

				 	<?

		 		//}

		 	?>
			<form method="post" name="paymentForm" action="body.php?page=solutions_process">
		 	<input type="hidden" name="processPayment" value="1">
		 	<input type="hidden" name="processCash" value="1">
			<input type="hidden" name="serviceID" value="<?= $_REQUEST['serviceID'] ?>">
			<input type="hidden" name="selectedAcc" value="<?= $regAccID ?>">
		 	<input type="hidden" name="regID" value="<?= $regAccObj->FieldID ?>">
			<div style="float: left;"><img border="0" src="images/myServicesBanc.jpg" width="105" height="31"></b></div><br>
				<div class="displayText"><span style="color: #0000FF; font-size: 12px;"><b>Solutions Payments</b></span> </div>
				<div class="displayText"><span style="color: #000000; font-weight: normal">&nbsp;</span></div>
		 		<div class="leftHeading">Cash Receipt No:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" name="ccreceipt" ></div>

				<?if($gg) {?>
	 			<div class="leftHeading"><?= get_word(135) ?>:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="ccCardName" value="<?= $_REQUEST['ccCardName'] ?>"></div>
		 		<div class="leftHeading"><?= get_word(134) ?>:</div><div class="dataEntry"><input class="serviceInput" type="text" size="20" name="ccCardNumber" value="<?= $_REQUEST['ccCardNumber'] ?>"></div>
		 		<div class="leftHeading">Credit Card Expiry:</div><div class="dataEntry"><input class="serviceInput" type="text" maxlength="2" size="2" onfocus="if(this.value=='MM')this.value=''" onblur="if(this.value=='')this.value='MM'" name="ccMonth" value="<?= $_REQUEST['ccMonth'] ?>"> / <input class="serviceInput" type="text" size="2" onfocus="if(this.value=='YY')this.value=''" onblur="if(this.value=='')this.value='YY'" maxlength="2" name="ccYear" value="<?= $_REQUEST['ccYear'] ?>"></div>
		 		<div class="leftHeading">Credit Card Type:</div><div class="dataEntry">
		 			<select class="serviceInput" name="ccCardType">
						<option <? if($_REQUEST['ccCardType'] == "Mastercard") { print "selected"; } ?> value="Mastercard">Mastercard</option>
						<option <? if($_REQUEST['ccCardType'] == "Visa") { print "selected"; } ?> value="Visa">Visa</option>
						<option <? if($_REQUEST['ccCardType'] == "Bankcard") { print "selected"; } ?> value="Bankcard">Bankcard</option>
					</select>
		 		</div>
		 		<?}?>
		 		<div class="leftHeading">Total Cash:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" readonly="true" name="totalCash" value="<?= $regAccObj->cashToPay ?>"></div>
		 		<? if($regAccObj->creditReceiptNo) { ?><div class="displayText"><span style="color: #FF0000;"><b><?= get_word(136) ?>: <?= $regAccObj->creditReceiptNo ?></b></span> </div><? } ?>
		 		<div class="submitService"><input class="serviceButton" type="submit" name="processCash" value ="  Click Me  "<? if($regAccObj->cashToPay == 0) { print " disabled"; } ?>></div>
				<div class="displayText"><span style="color: #000000; font-weight: normal"><?= get_page_data2(30) ?></span></div>

		 	</form>
			<form method="post" name="paymentForm" action="body.php?page=solutions_process">
		 	<input type="hidden" name="processPayment" value="1">
			<input type="hidden" name="serviceID" value="<?= $_REQUEST['serviceID'] ?>">
			<input type="hidden" name="selectedAcc" value="<?= $regAccID ?>">
		 	<input type="hidden" name="processTrade" value="1">
		 	<input type="hidden" name="regID" value="<?= $regAccObj->FieldID ?>">
		 		<div class="leftHeading">Trade Receipt No:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" name="treceipt" ></div>
		 		<div class="leftHeading">Total Trade:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" readonly="true" name="totalTrade" value="<?= $regAccObj->tradeToPay ?>"></div>

		 	<?if($hh) {?>
		 		<div class="leftHeading"><?= get_word(63) ?>:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" readonly="true" name="memberAcc" value="<?= $regAccObj->Acc_No ?>"></div>
		 		<div class="leftHeading">Total Trade:</div><div class="dataEntry"><input class="serviceInput bgLightGrey" type="text" size="20" readonly="true" name="totalTrade" value="<?= $regAccObj->tradeToPay ?>"></div>
			<?}?>
		 		<? if($regAccObj->tradeReceiptNo) { ?><div class="displayText"><span style="color: #FF0000;"><b><?= get_word(136) ?>: <?= $regAccObj->tradeReceiptNo ?></b></span> </div><? } ?>
		 		<div class="submitService"><input class="serviceButton" type="submit" name="processTrade" value ="  Click Me "<? if($regAccObj->tradeToPay == 0) { print " disabled"; } ?>></div>
		 	</form>
		</div>
		<?



	}


	function get_page_data2($id)  {

	   return $this->PageData2[$id];

	}
	
