<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

   $buffer3 = ratesmonthly();

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.
  $mail->add_attachment($buffer3, $date.' Rates.txt', 'text/plain');

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send('Rory', 'rory.c@hq.etxint.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Direct Debit Hard Copy Members');



function ratesmonthly() {

 global $linkid, $db;
 $blah = "";

 //$SQLQuery = dbRead("select registered_accounts_fuel.* from registered_accounts_fuel where tradeToPay = 1 and cashFeePaid != 1 group by creditReceiptNo","ebanc_services");
 //$SQLQuery = dbRead("select registered_accounts_fuel.* from registered_accounts_fuel where tradeToPay = 1 and cashFeePaid != 1 group by creditReceiptNo","ebanc_services");
 //$SQLQuery = dbRead("select * from registered_accounts, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and Status_ID > 1 group by registered_accounts.Acc_No","ebanc_services");
 //$SQLQuery = dbRead("select * from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and CID = 1 and status in (0,2,4,5,6) and paymenttype > 0 and accountno != ' ' AND type = 3");
 $SQLQuery = dbRead("select * from members where CID = 1 and status in (0,2,4,5) and monthlyfeecash > 0 and paymenttype > 0 and accountno != ' '");

 while($row = mysql_fetch_assoc($SQLQuery)) {

  //$email = $row['creditReceiptNo'];

  //if($row['emailaddress']) {
    //$blah .= $row['emailaddress'].",";
  //}

  //if($row['email']) {
    //$blah .= $row['email'].",";
  //}
  $blah .= $row['contactname'].",".$row['companyname'].",".$row['postalno'].",".$row['postalname'].",".$row['postalsuburb'].",".$row['postalcity'].",".$row['postalstate'].",".$row['postalpostcode']."\r\n";
  //if($row['creditReceiptNo']) {
    //$blah .= "$email,";
  //}
 }

 return $blah;

}
