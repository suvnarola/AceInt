<?

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");
include("class.html.mime.mail.inc");

$date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
$date3 = date("Y-m", mktime(0,0,0,date("m"),1,date("Y")));

 $Cquery = dbRead("select * from country where countryID = 12");
 $Crow = mysql_fetch_assoc($Cquery);

 $query = dbRead("select * from members, invoice where (members.memid = invoice.memid) and members.CID = 12 and invoice.date='$date2' and members.letters ='0' and status !='1' and (overduefees+currentpaid+currentfees > 0)");
 while($row = mysql_fetch_assoc($query)) {

  $query2 = dbRead("select sum(dollarfees) as newfees from transactions where memid = '".$row['memid']."' and dollarfees < 0 and dis_date like '".$date3."-%'");
  $row2 = mysql_fetch_assoc($query2);

  $fees = ($row['overduefees'] + $row['currentpaid'] + $row['currentfees'])+$row2['newfees'];
  if($fees > $Crow['letteramount']) {
   dbWrite("update members set letters = '9' where memid = '$row[memid]'");
   //echo $row['memid']."<br>";
  }
 }

$query1 = dbRead("select * from area, country where (area.CID = country.countryID) and CID = 12");
while($row1 = mysql_fetch_assoc($query1)) {

 if(!$row1[email]) {

  $emailaddress =  "dave@ebanctrade.com";

 } else {
  if($row1['display'] == 'Y')  {
   $emailaddress = $row1[email];
  } else {
   $emailaddress =  "dave@ebanctrade.com";
  }
 }

 // define the text.
  $text = "Dear $row1[tradeq],\r\n\r\nAttached is a list of members in your area who have over $row1[currency]$row1[letteramount] owing in fees and are now 30 days overdue.\r\n\r\nThese members will be sent their first letter of request for payment on the 15th of this month.\r\nIf you do not wish any of these members to receive this letter you must contact Accounts at your Head Office before this date.\r\n\r\nRegards\r\n\r\nAccount Department.";

 // get the actual taxinvoice ready.
  $buffer = letter($row1[FieldID]);

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'Overdue - '.$row1[place].'.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  $mail->send($row1[tradeq], $emailaddress, 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Over Due Members - '.$row1[place],'Bcc: reports@ebanctrade.com'.$otheremail);

}

function letter($fieldid) {

$query2 = dbRead("select * from members where letters='9' and licensee='$fieldid' order by companyname");
if(mysql_num_rows($query2) == 0) {

$blah = "No overdue member in your area";

} else {

while($row2 = mysql_fetch_assoc($query2)) {

 $blah .= "$row2[memid] - ,$row2[companyname]\r\n";

}

}
 return $blah;
}
