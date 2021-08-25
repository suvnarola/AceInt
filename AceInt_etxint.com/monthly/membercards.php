<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date1 = date("Y-m", mktime(0,0,0,date("m"),1,date("Y")));
 $date2 = date("Y-m", mktime(0,0,0,date("m"),1-1,date("Y")));

 $query = dbRead("select * from country where printcards ='Y'");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

 // define the text.
  $text = "Dear Dave,\r\n\r\nAttached is this months $date2 New Members Card list to be printed.";

 // get the actual taxinvoice ready.
  $buffer = taxinvoice();

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'Card List-'.$row[name].'-'.$date2.'.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  //$mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Member Card List -'.$row[name],'Bcc: apccs@apccs.com.au');
  $mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Member Card List -'.$row[name]);

 }

function taxinvoice() {

global $linkid, $db, $date2, $row, $pdf, $offset3;

$query1 = dbRead("select * from members, status where (members.status = status.FieldID) and datejoined like '$date2-%' and CID='$row[countryID]' and status.mem_cards = 1 order by companyname");

 #loop around
 while($row1 = mysql_fetch_assoc($query1)) {

   $name = substr($row1[companyname], 0, 27);
   $blah .= "$name,$row1[memid]\r\n";

 }

 $query2 = dbRead("select memcards.memid, members.companyname, members.CID from memcards, members where (memcards.memid=members.memid) and type='1' and done='N' and members.CID='$row[countryID]'");

 while($row2 = mysql_fetch_assoc($query2)) {

   $name = substr($row2[companyname], 0, 27);
   $blah .= "$name,$row2[memid]\r\n";

 }

 dbWrite("update memcards set done='Y' where type='1'");
 return $blah;

}
