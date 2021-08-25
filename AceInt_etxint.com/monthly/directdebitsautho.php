<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date1 = date("Y-m", mktime(0,0,0,date("m"),1,date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

 // define the text.
  $text = "Dear Dave,\r\n\r\nAttached is Direct Debits Authorities to be uplaoded to Securepay.";

 // get the actual taxinvoice ready.
  $buffer = taxinvoice();

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'EBT0001.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  //$mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Direct Debit Report','Bcc: reports@ebanctrade.com');

    unset($attachArray);
    unset($addressArray);

   	$attachArray[] = array($buffer, 'EBT00.txt', 'base64', 'text/plain');

	$addressArray[] = array(trim('dave@hq.etxint.com'), 'Dave');

	sendEmail("accounts@au.ebanctrade.com", ' E Banc Accounts', 'accounts@au.ebanctrade.com', 'Direct Debit Report', 'accounts@au.ebanctrade.com', 'E Banc Accounts', $text, $addressArray, $attachArray);


function taxinvoice() {

global $linkid, $db, $date2, $row, $pdf, $offset3;

//$query = dbRead("select * from members, invoice where (invoice.memid=members.memid) and members.paymenttype='Direct Debit' and invoice.date='$date2'");
$query = dbRead("select * from members, invoice, tbl_admin_payment_types where (invoice.memid=members.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and tbl_admin_payment_types.ddrun='1' and members.paymenttype = 20 and accountno > 0 and invoice.date='$date2' and members.CID = 1");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

   $bank = explode(",", $row[accountno]);
   $blah .= "EBT00|$bank[0]|$bank[1]|$row[accountname]|0|20201231|-1|-1|0|0|\r\n";

 }

 return $blah;

}
