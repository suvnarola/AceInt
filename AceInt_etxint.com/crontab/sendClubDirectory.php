<?

	$NoSession = true;

	include("/home/etxint/admin.etxint.com/includes/global.php");
	include("/home/etxint/admin.etxint.com/includes/directory_function.php");
	include("/home/etxint/admin.etxint.com/includes/zip.lib.php");
	include("/home/etxint/admin.etxint.com/includes/modules/class.phpmailer.php");

	$_SESSION['Directory']['top']       = 1;
	$_SESSION['Directory']['countryid'] = 1;

	$BufferFiftyNormal = directory(0,TRUE,FALSE);
	$BufferGoldNormal = directory(0,FALSE,TRUE);

	$fiftySQL = dbRead("select members.*, tbl_members_email.email as emailAddress, country.countrycode, logo from tbl_members_email, members, country where (tbl_members_email.acc_no = members.memid) and (members.CID = country.countryID) and tbl_members_email.type = '3' and members.fiftyclub = '1' and members.status not in (1,5,6)");
	$goldSQL = dbRead("select members.*, tbl_members_email.email as emailAddress, country.countrycode, logo from tbl_members_email, members, country where (tbl_members_email.acc_no = members.memid) and (members.CID = country.countryID) and tbl_members_email.type = '3' and members.fiftyclub = '2' and members.status not in (1,5,6)");

	$fiftyText = "50% Plus Club Directory<br><br>Please find attached your monthly Club Directory displaying all traders who are current members of the 50% Plus Club.<br><br>We have also included a list of Club members by account number as this may provide a faster way of identifying if a trader has club membership when they are purchasing from you.<br><br>Please notify head office if you are aware of any club members who are not trading according to Club rules.<br><br>Regards";
	$goldText = "Gold Club Directory<br><br>Please find attached your monthly Club Directory displaying all traders who are current members of the Gold Club and the 50% Plus Club.  Your membership of the Gold Club gives you automatic membership to the 50% Plus Club.<br><br>We have also included a list of Club members by account number as this may provide a faster way of identifying if a trader has club membership when they are purchasing from you.<br><br>Please notify head office if you are aware of any club members who are not trading according to Club rules.<br><br>Regards";

	while($fiftyObj = mysql_fetch_object($fiftySQL)) {

		if($fiftyObj->logo == 'etx') {
		  $nn = "Empire";
		  $ee = "empireXchange";
		} elseif($fiftyObj->logo == 'ept') {
		  $nn = "E Planet";
		  $ee = "eplanettrade";
		} else {
		  $nn = "E Banc";
		  $ee = "ebanctrade";
		}

		$clubMail = new PHPMailer();

		$clubMail->Priority = 3;
		$clubMail->CharSet = "utf-8";
		$clubMail->From = "hq@".$fiftyObj->countrycode.".".$ee.".com";
		$clubMail->FromName = $nn." Trade - Web Site";
		$clubMail->Sender = "hq@".$fiftyObj->countrycode.".".$ee.".com";
		$clubMail->Subject = "Club Directory";
		$clubMail->AddReplyTo("hq@".$fiftyObj->countrycode.".".$ee.".com", $nn." Trade Club");
		$clubMail->IsSendmail(true);
		$clubMail->Body = get_html_template($fiftyObj->CID, 'Club Member', $fiftyText);
		$clubMail->IsHTML(true);

		$clubMail->AddStringAttachment($BufferFiftyNormal , "clubDirectoryFifty.pdf", "base64","application/pdf");

	    $clubMail->AddAddress($fiftyObj->emailAddress, $nn." Trade Club Member");
	    //$clubMail->AddAddress("dave@ebanctrade.com", $nn." Trade Club Member");

	    $clubMail->Send();

	}

	while($goldObj = mysql_fetch_object($goldSQL)) {

		if($goldObj->logo == 'etx') {
		  $nn = "Empire";
		  $ee = "empireXchange";
		} elseif($goldObj->logo == 'ept') {
		  $nn = "E Planet";
		  $ee = "eplanettrade";
		} else {
		  $nn = "E Banc";
		  $ee = "ebanctrade";
		}

		$clubMail = new PHPMailer();

		$clubMail->Priority = 3;
		$clubMail->CharSet = "utf-8";
		$clubMail->From = "hq@".$goldObj->countrycode.".".$ee.".com";
		$clubMail->FromName = $nn." Trade - Web Site";
		$clubMail->Sender = "hq@".$goldObj->countrycode.".".$ee.".com";
		$clubMail->Subject = "Club Directory";
		$clubMail->AddReplyTo("hq@".$goldObj->countrycode.".".$ee.".com", $nn." Trade Club");
		$clubMail->IsSendmail(true);
		$clubMail->Body = get_html_template($goldObj->CID, 'Club Member', $goldText);
		$clubMail->IsHTML(true);

		$clubMail->AddStringAttachment($BufferGoldNormal , "clubDirectoryGold.pdf", "base64","application/pdf");

	    $clubMail->AddAddress($goldObj->emailAddress, $nn." Trade Club Member");
	    //$clubMail->AddAddress("dave@ebanctrade.com", $nn." Trade Club Member");

	    $clubMail->Send();

	}



?>