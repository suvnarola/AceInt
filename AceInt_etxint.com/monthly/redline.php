<?

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");
include("class.html.mime.mail.inc");

$date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

$query2 = dbRead("select * from country where countryID != 12");
while($row2 = mysql_fetch_assoc($query2)) {

 $query = dbRead("select * from members, invoice where (members.memid = invoice.memid) and members.CID = '".$row2['countryID']."' and invoice.date='$date2' and members.letters ='0' and status not in (1,3,6) and rob != 1 and (overduefees+currentpaid > ".$row2['letteramount'].")");

 while($row = mysql_fetch_assoc($query)) {

  dbWrite("update members set letters = '9' where memid = '$row[memid]'");
  dbWrite("insert into notes (memid,date,userid,type,note) values ('$row[memid]','".date("Y-m-d H:i:s")."','180','1','Licensee Emailed with reference to overdue fees')");

 }
}

$query1 = dbRead("select * from country, area  where (area.CID = country.countryID) and inter = 'Y' and countryID != 12");
while($row1 = mysql_fetch_assoc($query1)) {

 if($row1[state] == 'QLD')  {
    //$otheremail = ",gayle@ebanctrade.com";
 } else {
    //$otheremail = "";
 }

 if(!$row1['reportemail']) {

  $emailaddress =  "dave@ebanctrade.com";

 } else {
  //if($row1['display'] == 'Y')  {
   //$emailaddress = $row1[email];
   $emailaddress = $row1['reportemail'];
  //} else {
  // $emailaddress =  "dave@ebanctrade.com";
  //}
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

$query1 = dbRead("select user, EmailAddress, Name from members, area, tbl_admin_users where members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and letters = 9 group by user");
while($row1 = mysql_fetch_assoc($query1)) {

 if($row1['EmailAddress']) {

 // define the text.
  $text = "Dear $row1[tradeq],\r\n\r\nAttached is a list of members in your area who have over $row1[currency]$row1[letteramount] owing in fees and are now 30 days overdue.\r\n\r\nThese members will be sent their first letter of request for payment on the 15th of this month.\r\nIf you do not wish any of these members to receive this letter you must contact Accounts at your Head Office before this date.\r\n\r\nRegards\r\n\r\nAccount Department.";
 // get the actual taxinvoice ready.
  $buffer = letter2($row1[user]);
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
  $mail->send($row1['Name'], $row1['EmailAddress'], 'Empire Accounts - Head Office', 'accounts@ebanctrade.com', '30 Days Over Due Members','Bcc: reports@ebanctrade.com');

 }
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

function letter2($fieldid) {

$query2 = dbRead("select * from members, area where members.licensee =  area.FieldID and letters='9' and user = '$fieldid' order by companyname");
if(mysql_num_rows($query2) == 0) {

$blah = "No overdue member in your area";

} else {

while($row2 = mysql_fetch_assoc($query2)) {

 $blah .= "$row2[memid] - ,$row2[companyname]\r\n";

}

}
 return $blah;
}
