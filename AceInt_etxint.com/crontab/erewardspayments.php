<?

 $date = date("dmy", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

  $text = "Dear Dave, \n\nAttached is your current E Rewards Payment File.";
  $buffer = erewards_payments();
  define('CRLF', "\r\n", TRUE);
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
  $mail->add_text($text);
  $mail->add_attachment($buffer, 'national.txt', 'text/plain');
  $mail->build_message();
  $mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts', 'accounts@ebanctrade.com', 'E Rewards Payments');

function erewards_payments() {

global $date, $date2, $pdf;

$ctotal=0;
$net=0;
$counter=0;

$blah="0                 01NAB       E Banc Trade P/L          224238E Rewards   $date                                        \r\n";

$query = dbRead("select * from erewards_bank, members where members.memid = erewards_bank.memid and type='0' and date='$date2'");

 #loop around
 while($row = mysql_fetch_assoc($query)) {
  
  $counter++;

  $bank=chunk_split("$row[reward_bsb]", 3, '-');
  $bank=rtrim($bank,"-");
  
  $banknumber=str_pad($row[reward_accno], 9, "0", STR_PAD_LEFT);  
   
  $total=$row[amount_cash];
  $total=$total*100;
  $totall=str_pad($total, 10, "0", STR_PAD_LEFT);  
 
  $ctotal += $total;
  $name=str_pad($row[reward_accname], 32);
  
  $memid=str_pad($row[memid], 5);
  $det="E Rewards - $memid";
   
  $blah .= "1$bank$banknumber 50$totall$name$det 084-571482174512E Banc Trade P L00000000\r\n";

 }
 
 $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);   
 $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);   
 $dtotal="0000000000";
 $net=str_pad($ctotal, 10, "0", STR_PAD_LEFT); 
  
   
 $blah .= "7999-999            $net$ctotall$dtotal                        $counter                                        ";

 return $blah;

}

?>