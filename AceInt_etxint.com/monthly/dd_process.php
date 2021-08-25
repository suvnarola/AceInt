<?

 /**
  * Direct Transfer Management.
  *
  * dd_upload.php
  * version 0.02
  */
$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
include("/home/etxint/admin.etxint.com/includes/modules/functions.transaction.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.phpmailer.php");

$dd = date("Y-m-d");

$d = dir("/home/etxint/public_html/upload/dd_files/");

while (false !== ($entry = $d->read())) {

	if($entry != "." && $entry != ".." && $entry != "processed_files") {
		$array1 = file("/home/etxint/public_html/upload/dd_files/".$entry);

		$toid = explode("_", $entry);
		$tomemid = $toid[0];

		foreach($array1 as $key => $value) {

		    $col = explode(",", $value);

			$Transfer = new FundsTransfer();

			$Transfer->AddFrom($col[0]);
			$Transfer->AddTo($tomemid);
			$Transfer->AddDate($dd);
			$Transfer->AddAmount($col[2], $Transfer->FromCountry['convert']);

			$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
			$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

			$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
			$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
			$Transfer->AddWho("180");
			$Transfer->AddDetails($col[3]);
			$Transfer->MultiCheck("4");

		 	if(!$Transfer->Errors) {

		 		/**
		 		 * No errors put transaction through.
		 		 */

		 		$Transfer->DOTransfer("1");

		 	} else {

		 		emailError($Transfer->FromRow['CID']);

		 	}

		}

		$source="/home/etxint/public_html/upload/dd_files/".$entry;
		$dest="/home/etxint/public_html/upload/dd_files/processed_files/".$entry;
		copy($source, $dest);
		unlink("/home/etxint/public_html/upload/dd_files/".$entry);
	}
}

$d->close();


function emailError($countryID) {

	global $Transfer;

	$bodyText = "

	RE: Dishonour.
	<br><br>
	The following scheduled Direct Transfer payment has been unsuccessfull.
	<br><br>
	Drawer Name: " . $Transfer->FromRow['accholder'] . "<br>
	Account Number: " . $Transfer->FromRow['memid'] . "<br>
	Amount of Payment: $" . number_format($Transfer->Amount,2) . "
	<br><br>
	Payee Name: " . $Transfer->ToRow['accholder'] . "<br>
	Payee Account Number: " . $Transfer->ToRow['memid'] . "
	<br><br>
	This amount has not been transferred.
	<br><br>
	Please contact the drawer to resolve this matter.
	<br><br>
	A copy of this email has been sent to the Account drawer.
	<br><br>
	Regards,
	<br><br>
	";

    $email = get_html_template($countryID, $Transfer->ToRow['contactname'], $bodyText);

	$countrySQL = dbRead("select country.* from country where countryID = " . addslashes($countryID));
	$countryRow = mysql_fetch_assoc($countrySQL);

	$emailFromSQL = dbRead("select tbl_members_email.* from tbl_members_email where acc_no = " . $Transfer->FromRow['memid'] . " and type = 3");
	$emailFromRow = @mysql_fetch_assoc($emailFromSQL);

	$emailToSQL = dbRead("select tbl_members_email.* from tbl_members_email where acc_no = " . $Transfer->ToRow['memid'] . " and type = 3");
	$emailToRow = @mysql_fetch_assoc($emailToSQL);

    $this->Mail = new PHPMailer();

    $this->Mail->Priority = 3;
    $this->Mail->CharSet = "utf-8";
    $this->Mail->From = "accounts@".$countryRow['countrycode'].".ebanctrade.com";
    $this->Mail->FromName = "E Banc Trade - Accounts";
    $this->Mail->Sender = "accounts@".$countryRow['countrycode'].".ebanctrade.com";
    $this->Mail->Subject = "Dishonoured Transaction.";
    $this->Mail->AddReplyTo("accounts@".$countryRow['countrycode'].".ebanctrade.com", "Scheduled Payment");
	$this->Mail->IsSendmail(true);
    $this->Mail->Body = $email;
    $this->Mail->IsHTML(true);

    $this->Mail->AddAddress($emailToRow['email'], $Transfer->ToRow['accholder']);
    $this->Mail->AddCC($emailFromRow['email'], $Transfer->FromRow['accholder']);

    $this->Mail->AddBCC("accounts@".$countryRow['countrycode'].".ebanctrade.com", "E Banc Accounts");

    $this->Mail->Send();

}
