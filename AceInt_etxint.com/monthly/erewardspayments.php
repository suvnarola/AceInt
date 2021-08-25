<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date = date("dmy", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

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
  $mail->add_attachment($buffer, 'NAT.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  $mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'E Rewards Payment Upload','Bcc: reports@ebanctrade.com');

function taxinvoice() {

global $linkid, $db, $date, $date2, $pdf;

$ctotal=0;
$net=0;
$counter=0;

$datee=str_pad($date], 46);

$blah="0                 01NAB       E Banc Trade P/L          224238E Rewards   $datee\r\n";

$query = dbRead("select * from erewards_bank, members where members.memid=erewards_bank.memid and type='0' and date='$date2'");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

  $counter++;

  $bank=chunk_split("$row[reward_bsb]", 3, '-');
  $bank=rtrim($bank,"-");

  $banknumber=str_pad($row[reward_accno], 9, "0", STR_PAD_LEFT);

  $total=$row[amount_cash];
  $total=$total*100;
  $totall=str_pad($total, 10, "0", STR_PAD_LEFT);

  $ctotal=$ctotal+$total;
  $name=str_pad($row[reward_accname], 32);

  $memid=str_pad($row[memid], 5);
  $det="E Rewards - $memid";

  $blah .= "1$bank$banknumber 50$totall$name$det 084-571482174512E Banc Trade P L00000000\r\n";

 }

 $net=$ctotal;

 $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);
 $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
 $dtotal="0000000000";
 $net=str_pad($ctotal, 10, "0", STR_PAD_LEFT);


 $blah .= "7999-999            $net$ctotall$dtotal                        $counter                                        ";

 return $blah;

}
