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
  $text = "Dear Spain,\r\n\r\nAttached is this months Direct Debits to be Uplaod to Bank.";

 // get the actual taxinvoice ready.
  $buffer = taxinvoice();

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'BBVA19.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  //$mail->send('Dave', 'hq@es.ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Direct Debit Report','Bcc: reports@ebanctrade.com');

    unset($attachArray);
    unset($addressArray);

   	$attachArray[] = array($buffer, 'BBVA19.txt', 'base64', 'text/plain');
	$addressArray[] = array(trim('hq@es.ebanctrade.com'), 'Spain');
	$addressArray[] = array(trim('dave@hq.etxint.com'), 'Dave');

	sendEmail("accounts@au.ebanctrade.com", ' E Banc Accounts', 'accounts@au.ebanctrade.com', 'Direct Debit Report', 'accounts@au.ebanctrade.com', 'E Banc Accounts', $text, $addressArray, $attachArray);

function taxinvoice() {

global $linkid, $db, $date3, $date2, $date1, $row, $pdf, $offset3;

//$query = dbRead("select * from members, invoice where (invoice.memid=members.memid) and members.paymenttype='Direct Debit' and invoice.date='$date2' and (overduefees+currentpaid+currentfees)>'20' order by accountname");
$query = dbRead("select * from members, tbl_admin_payment_types, invoice where (invoice.memid=members.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and tbl_admin_payment_types.ddrun='1' and accountno > 0 and invoice.date='$date2' and (overduefees+currentpaid+currentfees)>'20' and members.CID = 15 order by accountname");

$date = date("dmy", mktime(0,0,0,date("m"),date("d"),date("Y")));
$datee = str_pad($date, 12);
$name = str_pad("EBANCTRADE", 40);
$namee = str_pad("EBANCTRADE", 39);
$blah = "5180B54012463000$datee$name                    01824813                                                                  \r\n";
$counter = 0;
$sum = 0;
$crow = 1;

 #loop around
 while($row = mysql_fetch_assoc($query)) {

  $query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.dis_date like '$date1-%' and memid='$row[memid]' and transactions.dollarfees < '0' and to_memid != '16083' group by memid");
  $row1 = mysql_fetch_assoc($query1);

  $total=($row[overduefees]+$row[currentpaid]+$row[currentfees]+$row1[fees]);
  $totall=$total*100;

  $mid = str_pad("430".$row['memid'], 12);
  $mname = $row[accountname]. str_pad("", 40-mb_strlen($row[accountname], "utf-8"));
  $address = $row[streetname]." ".$row[streetno] . str_pad("", 40-mb_strlen($row[streetname]." ".$row[streetno], "utf-8"));
  $city = $row[city]. str_pad("", 35-mb_strlen($row[city], "utf-8"));
  $post = str_pad($row[postcode], 19);

  $rec = str_pad("RECIBOS        ".$total, 48);

  if($totall > '2000')  {

	$inv = dbRead("select * from invoice_es where inv_link = '".$row['FieldID']."'");
	$invRow = mysql_fetch_assoc($inv);

	if($invRow['inv_no']) {
		$inv_no = $invRow['inv_no'];
	} else {
		$inv_no = 0;
	}

    $total2 = str_pad($totall, 10, "0", STR_PAD_LEFT);
	$id = str_pad($counter, 6, "0", STR_PAD_LEFT);
	$in = str_pad($inv_no, 10, "0", STR_PAD_LEFT);
	$in2 = str_pad($inv_no, 40, " ", STR_PAD_LEFT);
	//$acc = $row['accountno'];
	$acc = 	str_replace(" ", "", $row['accountno']);

  	$blah .= "5380B54012463000$date$date$namee 01824813660201533938        01                                                                \r\n";
  	$blah .= "5680B54012463000$mid$mname$acc$total2$id$in$rec\r\n";
  	$blah .= "5684B54012463000$mid                                        $in2                                                      \r\n";
  	$blah .= "5686B54012463000$mid$mname$address$city$post\r\n";
  	$blah .= "5880B54012463000                                                                        $total2      00000000010000000005                                      \r\n";

 	$feePay = new feePayment($row[memid]);
 	$feePay->payFees($_SESSION['feePayment']['memberRow'], $total, 1, 5, '', "Cash Fees Payment");

	$counter++;
	$crow = $crow+5;
	$sum = $sum + $totall;
  }
 }

 $count = str_pad($counter, 4, "0", STR_PAD_LEFT);
 $count2 = str_pad($counter, 10, "0", STR_PAD_LEFT);
 $tsum = str_pad($sum, 10, "0", STR_PAD_LEFT);
 $trow = str_pad($crow+1, 10, "0", STR_PAD_LEFT);

 $blah .= "5980B54012463000                                                    $count                $tsum      $count2$trow                                      \r\n";

 return $blah;

}
