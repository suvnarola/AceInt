<?
 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

   $energytotal = energymonthlytotal();
   $phonetotal = phonemonthlytotal();
   $ratestotal = ratesmonthlytotal();
   $text = "Please deposit ".number_format($energytotal,2)." to account My Services Banc BSB: 084 571 Acc: 793365703 as Class D Monthly Repayment from My Payments Pty Ltd \r\nPlease deposit ".number_format($phonetotal,2)." to account My Services Banc BSB: 084 571 Acc No: 793365703 as Class C Monthly Repayment from My Payments Pty Ltd \r\nPlease deposit ".number_format($ratestotal,2)." to account My Services Banc BSB: 084 571 Acc No: 793365703 as Class F Monthly Repayment from My Payment Pty Ltd";

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send('Julie', 'julie@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'myServicesBanc - Monthly Repayments');


function energymonthlytotal() {

 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

 $SQLQuery = dbRead("select sum(Plan_Amount) as total from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 3 and Status_ID in (2,3) and Payments_Left > 0 and Date_Paid < '".$date3."' order by Acc_No","ebanc_services");
 $row = mysql_fetch_assoc($SQLQuery);

 $total = $row['total'];

 return $total;

}

function phonemonthlytotal() {

 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

 //$SQLQuery = dbRead("select registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 2 and Status_ID in (2,3) and Payments_Left > 0 and Date_Paid < '".$date3."' order by Acc_No","ebanc_services");
 $SQLQuery = dbRead("select sum(Plan_Amount) as total from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 2 and Status_ID in (2,3) and Payments_Left > 0 and Date_Paid < '".$date3."' order by Acc_No","ebanc_services");
 $row = mysql_fetch_assoc($SQLQuery);

 $total = $row['total'];

 return $total;

}

function ratesmonthlytotal() {

 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

 //$SQLQuery = dbRead("select registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 7 and Status_ID in (2,3) and Payments_Left > 0 and Date_Paid < '".$date3."' order by Acc_No","ebanc_services");
 $SQLQuery = dbRead("select sum(Plan_Amount) as total from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 7 and Status_ID in (2,3) and Payments_Left > 0 and Date_Paid < '".$date3."' order by Acc_No","ebanc_services");
 $row = mysql_fetch_assoc($SQLQuery);

 $total = $row['total'];

 return $total;

}
