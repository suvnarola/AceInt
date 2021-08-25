<?

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date1 = date("Y-m", mktime(0,0,0,date("m"),1,date("Y")));
 $date2 = date("Y-m", mktime(0,0,0,date("m"),1-1,date("Y")));

 // define the text.
  $text = "Dear Robert,\r\n\r\nAttached is this months $date2 New Members for the printing of Priviledge Cards.";

 // get the actual taxinvoice ready.
  $buffer = taxinvoice();

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'PrivCardList-'.$date2.'.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  $mail->send('Robert', 'robert@privileges.com.au', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'PrivCard List -'.$date2,'Bcc: dave@ebanctrade.com');


function taxinvoice() {

global $linkid, $db, $date2, $row, $pdf, $offset3;

$query1 = dbRead("select * from members, area, status where members.area=area.FieldID and (members.status = status.FieldID) and datejoined like '$date2-%' and members.CID='1' and status.mem_cards = 1 order by place");

 #loop around
 while($row1 = mysql_fetch_assoc($query1)) {

   $name = substr($row1[accholder], 0, 27);
   $blah .= "$name,$row1[postalno],$row1[postalname],$row1[postalsuburb],$row1[postalcity],$row1[postalstate],$row1[postalpostcode],$row1[place]\r\n";

 }

 return $blah;

}
