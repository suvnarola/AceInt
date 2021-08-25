<?php
$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
include("/home/etxint/admin.etxint.com/includes/modules/functions.transaction.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");
//include("/home/etxint/admin.etxint.com/includes/modules/class.phpmailer.php");

//include("/home/etxint/admin.etxint.com/includes/class.html.mime.mail.inc");
//include("/home/etxint/admin.etxint.com/includes/htmlMimeMail.php");

$details = "Christmas Bonus";
$today_date = date("Y-m-d");

 #get transactions out for each jimsnet member.
 $query = dbRead("select * from members where status in (0,4,5,6) and CID = 1 and memid > 10101 order by memid");

 while($row = mysql_fetch_assoc($query)) {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom("29258");
		$Transfer->AddTo($row[memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount("250", $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("1");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		//emailError($Transfer->FromRow['CID']);
     		echo $row[memid].",";

     	}

 }



?>