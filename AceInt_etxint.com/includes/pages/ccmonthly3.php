<?

	/**
	 * Credit Card Fee Payment.
	 *
	 * @package E Banc Administration Site
	 * @author Antony Puckey
	 * @copyright Copyright 2005, RDI Host Pty Ltd
	 *
	 */

	/**
	 *  DEFINE(MERCHANT_ID, "ebt0001");
	 *  DEFINE(MERCHANT_PASSWORD, "jasu2ilk");
	 */

	DEFINE(MERCHANT_ID, "ebt0022");
	DEFINE(MERCHANT_PASSWORD, "k87jkqdam");

	include("includes/modules/class.feepayments.php");
	include("includes/modules/class.xmlCreditCard.php");


 //$query = dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 group by invoice.memid");
 $query = dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 and members.memid in (9995,10070) group by invoice.memid");

 while($row = mysql_fetch_assoc($query)) {

  $query3 = dbRead("select sum(dollarfees) as feespaid from transactions where memid='$row[memid]' and dollarfees < 0 and dis_date like '$thismonth-%'");
  $row3 = mysql_fetch_assoc($query3);

  $ChargeAmount = $row[feesowe] + $row3[feespaid];

  $exdate_temp = explode("/", $row[expires]);

  $exdate1 = $exdate_temp[0];
  $exdate2 = $exdate_temp[1];
  $thisyear = date("y");
  $thismonth2 = date("m");

  if(($exdate2 > $thisyear) or (($exdate1 >= $thismonth2) and ($exdate2 == $thisyear))) {

   if($ChargeAmount > 5) {

    $amount = $ChargeAmount;
    $ccamount = $ChargeAmount*100;

    $ponum = add_temp_trans($row[memid],$row[feesowe]);




			$ccPayment = new feePayment($row['memid']);


			$secureResponse = Array();

			$secureResponse = $ccPayment->ccPayment($row['memid'], $ChargeAmount, $row['accountno'], $exdate1, $exdate2, '', 'Visa', $poNumber);

			if($secureResponse['APPROVED'] == "Yes") {

				/**
				 * Transaction has been successfull.
				 */

				$ebancAdmin->dbWrite("update credit_transactions set success = 'Yes', amount = '" . $ChargeAmount . "', response_code = '" . $secureResponse['RESPONSECODE'] . "', response_text = '" . $secureResponse['RESPONSETEXT'] . "', sp_trans_id = '" . $secureResponse['TXNID'] . "', card_type = '" . $secureResponse['CARDDESCRIPTION'] . "', card_name = '" . $_SESSION['feePayment']['optionalInfo'] . "' where FieldID = '" . $_SESSION['feePayment']['poNumber'] . "'");

				$ccPayment->payFees($_SESSION['feePayment']['memberRow'], $ChargeAmount, 1, 5);

				displayReceipt('1',$secureResponse);

			} elseif($secureResponse['APPROVED'] == "No") {

				/**
				 * Transaction has been unsuccessfull.
				 */

				$ebancAdmin->dbWrite("update credit_transactions set success = 'No', amount = '" . $ChargeAmount . "', response_code = '" . $secureResponse['RESPONSECODE'] . "', response_text = '" . $secureResponse['RESPONSETEXT'] . "', sp_trans_id = '" . $secureResponse['TXNID'] . "', card_type = '" . $secureResponse['CARDDESCRIPTION'] . "', card_name='" . $_SESSION['feePayment']['optionalInfo'] . "' where FieldID = '" . $_SESSION['feePayment']['poNumber'] . "'");


			}

			//displayReceipt();

			unset($_SESSION['feePayment']);

	}
   }
   sleep(2);
 }
?>
