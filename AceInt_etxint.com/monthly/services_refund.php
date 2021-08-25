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
  $mail->add_attachment($buffer, 'refund.txt', 'text/plain');
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

 //$blah="01refund.txt          001\r\n";
 //$blah .="02DFT0001\r\n";

 $disdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));

 //$query = dbRead("select registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and ServiceID = 1 and Status_ID in (2,3) and Payments_Left > 0 order by Acc_No","ebanc_services");
 $query = dbRead("select registered_accounts.* from registered_accounts where Cash_Refund >0 and Bank_BSB > 0 order by FieldID","ebanc_services");

 while($row = mysql_fetch_assoc($query)) {

  $counter++;

  //$bank=chunk_split("$row[Bank_BSB]", 3, '-');
  //$bank=rtrim($bank,"-");
  $bank=$row[Bank_BSB];

  $banknumber=str_pad($row[Bank_Number], 28);
  $bankname=str_pad($row[Bank_Name], 34);
  $ref=str_pad($row[FieldID], 15);

  $total=number_format($row[Plan_Amount], 2);
  $totall=str_pad($total, 15, "0", STR_PAD_LEFT);

  $blah .="03OO      30112006O0002\r\n";

  $blah .= "55CR$bank$banknumber$bankname ID-$ref                                                                                                                                                                                                                                                                                                                                             $totall\r\n";
  $blah .= "55DR084571793365703                   My Services Banc Com Ltd           Refund                                                                                                                                                                                                                                                                                                                                                         $totall\r\n";
  //$blah .= "55CR084571793365703                   My Services Banc Com Ltd           Refund                                                                                                                                                                                                                                                                                                                                                         $totall\r\n";
  //$blah .= "55DR084571793365703                   My Services Banc Com Ltd           Refund                                                                                                                                                                                                                                                                                                                                                         $totall\r\n";

  $blah .="79\r\n";

 }

 $count=str_pad($counter, 3, "0", STR_PAD_LEFT);

 $blah2="01refund.txt          001\r\n";
 $blah2 .="02DFT$count\r\n";
 $blah2 .= $blah;

 $blah2 .="89\r\n";
 $blah2 .="99refund.txt          30112006";

 return $blah2;

}
