<?

// Scheduled Transfer Script.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
include("/home/etxint/admin.etxint.com/includes/modules/functions.transaction.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.phpmailer.php");
$details = "Automatic Transfer";

// Get the ones we need to process out.

$today_date = date("Y-m-d");
//$today_date = "2004-11-11";

$query = dbRead("select * from scheduled where startdate <= '$today_date' and active = 'Yes'");
while($row = mysql_fetch_array($query)) {

 // these transfers have a previous date or todays date.
 // need to get the frequency and check to see if today is the day to transfer the money.
 // if the startdate is today then we need to do the transfer straight away.
 // in all cases if the times left needs to be decreased we need to do that at the end.

 if($row[startdate] == $today_date) {

  // do the transfer now because this is the day of the first transfer.
  if($row[amount] < 0) {

    $dbgetbalu = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".$row[from_memid]."' and checked = 0");
    $traderowu = mysql_fetch_assoc($dbgetbalu);7

    $newamount = $traderowu[cb];

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($newamount, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

    //add_transaction($row[from_memid],$row[to_memid],$today_date,$newamount,$details);

  } else {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($row[amount], $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

    //add_transaction($row[from_memid],$row[to_memid],$today_date,$row[amount],$details);
  }

 } else {

  // if we get here that means that its already run once or the startdate is in the past.
  // we need to work out how many days or months have passed from the startdate and work
  // out from that if we need to do the transfer.

  $timestamp_prev_temp = explode("-", $row[startdate]);
  $timestamp_now_temp = explode("-", $today_date);

  $timestamp_prev = mktime(0,0,0,$timestamp_prev_temp[1],$timestamp_prev_temp[2],$timestamp_prev_temp[0]);
  $timestamp_now = mktime(0,0,0,$timestamp_now_temp[1],$timestamp_now_temp[2],$timestamp_now_temp[0]);

  $timestamp_diff = $timestamp_now - $timestamp_prev;

  $day_diff = $timestamp_diff/86400;

  if($row[frequency] == "weekly") {

   if($day_diff % 7 == 0) {

    if($row[amount] < 0) {

     $dbgetbalu = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='$row[from_memid]' and checked = 0");
     $traderowu = mysql_fetch_assoc($dbgetbalu);

     $newamount = $traderowu[cb];

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($newamount, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$newamount,$details);

    } else {

     // do the transactions because its a multiple of 7.

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($row[amount], $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$row[amount],$details);

    }

   }

  }

  if($row[frequency] == "fortnightly") {

   if($day_diff % 14 == 0) {

    // do the transactions because its a multiple of 7.
    if($row[amount] < 0) {

     $dbgetbalu = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='$row[from_memid]' and checked = 0");
     $traderowu = mysql_fetch_assoc($dbgetbalu);

     $newamount = $traderowu[cb];

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($newamount, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$newamount,$details);

    } else {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($row[amount], $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$row[amount],$details);
    }
   }
  }

  if($row[frequency] == "monthly") {

   if($timestamp_prev_temp[2] > date("d", mktime(0,0,0,date("m")+1,1-1,date("Y")))) {

    $runday = date("d", mktime(0,0,0,date("m")+1,1-1,date("Y")));

   } else {

    $runday = $timestamp_prev_temp[2];

   }

   if(date("d") == $runday) {

    // do the transaction.
    if($row[amount] < 0) {

     $dbgetbalu = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='$row[from_memid]' and checked = 0");
     $traderowu = mysql_fetch_assoc($dbgetbalu);

     $newamount = $traderowu[cb];

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($newamount, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$newamount,$details);

    } else {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($row[amount], $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$row[amount],$details);
	}
   }

  }

  if($row[frequency] == "yearly") {

   if($day_diff % 365 == 0) {

    // do the transactions because its a multiple of 365.
    if($row[amount] < 0) {

     $dbgetbalu = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='$row[from_memid]' and checked = 0");
     $traderowu = mysql_fetch_assoc($dbgetbalu);

     $newamount = $traderowu[cb];

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($newamount, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$newamount,$details);

    } else {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($row[from_memid]);
		$Transfer->AddTo($row[to_memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($row[amount], $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("4");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");

     	} else {

     		emailError($Transfer->FromRow['CID']);

     	}

     //add_transaction($row[from_memid],$row[to_memid],$today_date,$row[amount],$details);
    }
   }
  }

 }

 if($row[timesleft] == 1) {

  // if its got 1 here then change it to not active
  dbWrite("update scheduled set active = 'No' where ID = '$row[ID]'");

 } elseif($row[timesleft] > 1) {

  // take a number off the times left.
  dbWrite("update scheduled set timesleft = timesleft-1 where ID = '".$row[ID]."'");

 }

}

function emailError($countryID) {

	global $Transfer;

	$bodyText = "

	RE: Dishonour.
	<br><br>
	The following scheduled payment has been unsuccessfull.
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

    $this->Mail->AddBCC("accounts@".$countryRow['countrycode'].".etxint.com", "E Banc Accounts");

    $this->Mail->Send();

}
