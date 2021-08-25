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

$datee=str_pad($date, 46);

$blah="0                 01NAB       My Services Banc          224238Benefits    $datee\r\n";

 $disdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));

 //$query = dbRead("select registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 1 and Status_ID in (2,3) and Payments_Left > 0 order by Acc_No","ebanc_services");
 $query = dbRead("select registered_accounts.* from transactions, registered_accounts, plans where (transactions.regAccID = registered_accounts.FieldID) and (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 1 and Status_ID in (2,3) and dis_date = '2006-10-20' order by Acc_No","ebanc_services");

 while($row = mysql_fetch_assoc($query)) {

  //if($counter == 100 & $tt) {
  //$tt = $ctotal+((($row[Plan_Amount]*100)*2)+50);
  $tt = $ctotal+((($row[Plan_Amount]*100))+50);
  if($tt > 1500000 || $counter > 99) {
	 $net=$ctotal;
	 $counter = $counter+1;

	 $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);
	 $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
	 $net="0000000000";
	 $dtotal=str_pad($ctotal, 10, "0", STR_PAD_LEFT);

	 $blah .= "1084-571790615500 13$ctotall$name$det 084-571790615500Member Benefit  00000000\r\n";
	 $blah .= "7999-999            $net$ctotall$dtotal                        $counter                                        ";

	 $counter = 0;
	 $ctotal = 0;

	 $blah .= "NEW\r\n";
	 $blah .="0                 01NAB       My Services Banc          224238Benefits    $datee\r\n";

  }

  $counter++;

  $bank=chunk_split("$row[reward_bsb]", 3, '-');
  $bank=rtrim($bank,"-");

  $banknumber=str_pad($row[Bank_No], 9, "0", STR_PAD_LEFT);

  $total=$row[Plan_Amount];
  $total=$total*100;
  //$total=($total*2)+50;
  $total=($total)+50;
  $totall=str_pad($total, 10, "0", STR_PAD_LEFT);

  $ctotal=$ctotal+$total;
  $name=str_pad("My Services Banc", 32);

  $memid=str_pad($row[memid], 5);
  $det="Weekly Benefit   ";

  $blah .= "1704-118$banknumber 50$totall$name$det 084-571790615500My Service Banc 00000000\r\n";

 }

 $net=$ctotal;
 $counter = $counter+1;

 $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);
 $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
 $net="0000000000";
 $dtotal=str_pad($ctotal, 10, "0", STR_PAD_LEFT);

 $blah .= "1084-571790615500 13$ctotall$name$det 084-571790615500Member Benefit  00000000\r\n";
 $blah .= "7999-999            $net$ctotall$dtotal                        $counter                                        ";

 return $blah;

}
