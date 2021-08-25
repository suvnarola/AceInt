<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date = date("dmy", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

$query = dbRead("select sum(Cash_Refund)as Cash_Refund, sum(Refund) as Refund, sum(Refund_2) as Refund_2, Acc_No, Bank_BSB, Bank_Number, Bank_Name from registered_accounts where Refund >0 group by Acc_No having (sum(Cash_Refund)-sum(Refund)-(5*sum(Refund_2)) >= 293) and (sum(Cash_Refund)-sum(Refund)-(5*sum(Refund_2)) < 293666) order by Acc_No","ebanc_services");
while($row = mysql_fetch_assoc($query)) {
  $ddd = $row[Cash_Refund]-$row[Refund]-(5*$row[Refund_2]);

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
  $mail->add_attachment($buffer, 'refund_$'.$ddd.'.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  $mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'E Rewards Payment Upload','Bcc: reports@ebanctrade.com');

}

function taxinvoice() {

global $linkid, $db, $date, $date2, $pdf, $row;

$ctotal=0;
$net=0;
$counter=0;

$datee=str_pad($date, 46);

 //$blah="01refund.txt          001\r\n";
 //$blah .="02DFT0001\r\n";
$blah="0                 01MET       EMPIRE TRADE AUSTRALIA    336668creditors   $datee\r\n";

 $disdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));

 //$query = dbRead("select registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 1 and Status_ID in (2,3) and Payments_Left > 0 order by Acc_No","ebanc_services");
 //$query = dbRead("select registered_accounts.* from registered_accounts where Cash_Refund >0 and Bank_BSB > 0 order by FieldID","ebanc_services");
 //$query = dbRead("select sum(Cash_Refund)as Cash_Refund, sum(Refund) as Refund, sum(Refund_2) as Refund_2, Acc_No, Bank_BSB, Bank_Number, Bank_Name from registered_accounts where Refund >0 and Refund_2 < 6 group by Acc_No order by Acc_No","ebanc_services");
 //$query = dbRead("select sum(Cash_Refund)as Cash_Refund, sum(Refund) as Refund, sum(Refund_2) as Refund_2, Acc_No, Bank_BSB, Bank_Number, Bank_Name from registered_accounts where Refund >0 group by Acc_No having (sum(Cash_Refund)-sum(Refund)-(5*sum(Refund_2)) >= 293) and (sum(Cash_Refund)-sum(Refund)-(5*sum(Refund_2)) < 293) order by Acc_No","ebanc_services");

 //while($row = mysql_fetch_assoc($query)) {

  $counter++;

  $dd = $row[Cash_Refund]-$row[Refund]-(5*$row[Refund_2]);

  $bank=chunk_split("$row[Bank_BSB]", 3, '-');
  $bank=rtrim($bank,"-");

  $banknumber=str_pad($row[Bank_Number], 9, "0", STR_PAD_LEFT);

  $total=$dd;
  $total=$total*100;
  $totall=str_pad($total, 10, "0", STR_PAD_LEFT);

  $ctotal=$ctotal+$total;
  $name=substr($row[Bank_Name], 0, 32);
  $name=str_pad($name, 32);

  $memid=str_pad($row[Acc_No], 5);
  $det="myServi Final Pmt";

  $blah .= "1$bank$banknumber 50$totall$name$det 484-799027318880Empire Trade Aus00000000\r\n";

 //}

 $net=$ctotal;

 $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);
 $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
 $dtotal="0000000000";
 $net=str_pad($ctotal, 10, "0", STR_PAD_LEFT);


 $blah .= "7999-999            $net$ctotall$dtotal                        $counter                                        ";

 return $blah;

}
