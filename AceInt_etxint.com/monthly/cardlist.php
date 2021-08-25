<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");
 include("class.html.mime.mail.inc");

 $ebancAdmin = new ebancSuite();

 $date1 = date("Y-m", mktime(0,0,0,date("m"),1,date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

 // define the text.
  $text = "Dear Dave,\r\n\r\nAttached is this months Direct Debits to be Uplaod to Securepay.";

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

global $linkid, $db, $date3, $date2, $date1, $row, $pdf, $offset3;

//$query = dbRead("select * from members, invoice where (invoice.memid=members.memid) and members.paymenttype='Direct Debit' and invoice.date='$date2' and (overduefees+currentpaid+currentfees)>'20' order by accountname");
$query = dbRead("select * from members where members.status in (0,3,4,5,6) and members.CID = 1 order by memid");
$ss = 0;

 #loop around
 while($row = mysql_fetch_assoc($query)) {
   $ss++;
   $memid=str_pad($row[memid], 5, "0", STR_PAD_LEFT);
   $blah .= "60094474132$memid,$row[companyname],$row[accholder] \r\n";
 }

 $blah .= "$ss";
 return $blah;

}
