<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/public_html/members/includes/modules/class.phpmailer.php");
 include("class.html.mime.mail.inc");

 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

  // get the actual taxinvoice ready.
   $foodtotal = foodweeklytotal();
   $traveltotal = travelweeklytotal();
   $buffer = foodweekly();
   $buffer2 = travelweekly();

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.
  $mail->add_attachment($buffer, $date.' Payroll.txt', 'text/plain');
  $mail->add_attachment($buffer2, $date.' Travel.txt', 'text/plain');

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send('dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'myServicesBanc - Weekly Uploads');


   $text = get_services_template('1', 'Sharlene', 'Please find attacted account allocation for this week. Fund will be credited into your account overnight.');

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_html($text);

  // add the attachment on.
   $mail->add_attachment($buffer2, $date.' Travel.txt', 'text/plain');

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send('Corporate Travel', 'sharlene_howell@travelctm.com', 'E Banc Accounts - Head Office', 'dave@ebanctrade.com', 'myServicesBanc - Weekly Allocation', 'Bcc: dave@ebanctrade.com');
   //$mail->send('Corporate Travel', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'dave@ebanctrade.com', 'myServicesBanc - Weekly Allocation');



  // get the actual taxinvoice ready.
   $text = "Please deposit ".number_format($foodtotal,2)." to account BSB: 704 118 Acc: 959132 as Class B Weekly Repayment from My Payments Pty Ltd \r\nPlease deposit ".number_format($traveltotal,2)." to account My Services Banc BSB: 084 571 Acc No: 793365703 as Class E Weekly Repayment from My Payment Pty Ltd \r\nPlease deposit ".number_format($traveltotal,2)." to Corporate Travel Class E Weekly Benefits from My Services Banc.com Ltd";

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send('Julie', 'julie@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'myServicesBanc - Weekly Repayments');


function foodweekly() {

 global $linkid, $db;

 //$disdate = date("Y-m-d");
 $disdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));

 $SQLQuery = dbRead("select registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 1 and Status_ID in (2,3) and Payments_Left > 0 order by Acc_No","ebanc_services");
 while($row = mysql_fetch_assoc($SQLQuery)) {

  $card = $row['Card_No'];
  $payment = $row['Plan_Amount'];
  $details = "Weekly Benefit to ".$row['Card_No'];
  $details2 = "Weekly Benefit";

  $blah .= "$card,$payment,$details,$details2\r\n";

  if($row['Status_ID'] == 2) {
    $date2 = servicedates($row['FieldID'],2);
    dbWrite("update registered_accounts set Status_ID = 3, Date_Renewal = '".date("Y-m-d", strtotime($date2))."' where FieldID = ".$row['FieldID']."","ebanc_services");
  }

  dbWrite("insert into transactions (regAccID,buy,sell,type_id,details,dis_date) values ('".$row['FieldID']."','".$row['Plan_Amount']."','0','1','$details','$disdate')","ebanc_services");
  dbWrite("update registered_accounts set Payments_Left = (Payments_Left - 1) where FieldID = ".$row['FieldID']."","ebanc_services");

  if($row['Payments_Left'] == 1) {

    //renew($row['FieldID']);

  }

 }

 return $blah;

}

function travelweekly() {

 global $linkid, $db;

 $disdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));

 $SQLQuery = dbRead("select registered_accounts.*, reg_acc_details.* from registered_accounts, plans, reg_acc_details where (registered_accounts.Plan_ID = plans.FieldID) and (registered_accounts.Acc_No = reg_acc_details.Acc_No) and ServiceID = 6 and Status_ID in (2,3) and Payments_Left > 0 order by registered_accounts.Acc_No","ebanc_services");
 while($row = mysql_fetch_assoc($SQLQuery)) {

  $card = $row['FieldID'];
  $accno = $row['Acc_No'];
  $companyname = $row['companyname'];
  $accholder = $row['accholder'];
  $email = $row['emailaddress'];
  $phone = $row['phonearea']." ".$row['phoneno'];
  $payment = $row['Plan_Amount'];
  $details = "Weekly Benefit to ".$row['FieldID'];
  $details2 = "Weekly Benefit";
  $total = ($row['Terms']-$row['Payments_Left']+1)*$row['Plan_Amount'];

  $blah .= "$accno,$companyname,$accholder,$phone,$email,$payment,$total\r\n";

  if($row['Status_ID'] == 2) {
    $date2 = servicedates($row['FieldID'],2);
    dbWrite("update registered_accounts set Status_ID = 3, Date_Renewal = '".date("Y-m-d", strtotime($date2))."' where FieldID = ".$row['FieldID']."","ebanc_services");
  }

  dbWrite("insert into transactions (regAccID,buy,sell,type_id,details,dis_date) values ('".$row['FieldID']."','".$row['Plan_Amount']."','0','1','$details','$disdate')","ebanc_services");
  dbWrite("update registered_accounts set Payments_Left = (Payments_Left - 1) where FieldID = ".$row['FieldID']."","ebanc_services");

  if($row['Payments_Left'] == 1) {

    //renew($row['FieldID']);

  }

 }

 return $blah;

}

function foodweeklytotal() {

 $SQLQuery = dbRead("select sum(Plan_Amount) as total from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 1 and Status_ID in (2,3) and Payments_Left > 0 ","ebanc_services");
 $row = mysql_fetch_assoc($SQLQuery);

 $total = $row['total'];

 return $total;

}

function travelweeklytotal() {

 $SQLQuery = dbRead("select sum(Plan_Amount) as total from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 6 and Status_ID in (2,3) and Payments_Left > 0 order by Acc_No","ebanc_services");
 $row = mysql_fetch_assoc($SQLQuery);

 $total = $row['total'];

 return $total;

}

function renew($id) {

     $Query = dbRead("select * from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and (registered_accounts.Acc_No = reg_acc_details.Acc_No) and registered_accounts.FieldID = ". $id ."","ebanc_services");
 	 $row = mysql_fetch_assoc($Query);

     $Query2 = dbRead("select position, data from tbl_members_data, tbl_members_pages, country where (tbl_members_data.pageid = tbl_members_pages.FieldID) and (tbl_members_data.CID = country.countryID) and (tbl_members_data.langcode = country.Langcode) and position = 35 and pageid = 66 and country.countryID = ".$row['CID']." order by position");
 	 $row2 = mysql_fetch_assoc($Query2);

	 ob_start();
	 eval(" ?>".$row2['data']."<? ");
	 $output = ob_get_contents();
	 ob_end_clean();

	 $text = $output;

	 $text = get_services_template($row['CID'], $row['accholder'], $text);
	 $subject = "My Services Banc - ".$row['Product'];

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

	 $this->Mail->AddAddress($row['emailaddress'], $row['accholder']);
	 $this->Mail->AddAddress("dave@ebanctrade.com", $row['accholder']);

	 $this->Mail->Send();

	 return $text;

}
