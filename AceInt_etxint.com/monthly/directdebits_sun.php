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
 $date4 = date("dmy", mktime(0,0,0,date("m"),date("d"),date("Y")));

 // define the text.
  $text = "Dear Finance,\r\n\r\nAttached is this months Direct Debits to be Uplaod to Securepay.";

 // get the actual taxinvoice ready.
  $buffer = taxinvoice();

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'EBT0001.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  //$mail->send('Dave', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Direct Debit Report','Bcc: reports@ebanctrade.com');

    unset($attachArray);
    unset($addressArray);
	unset($bccArray);

	$finance = 'finance@empiretradeexchange.com.au' ;

   	$attachArray[] = array($buffer, 'dd.txt', 'base64', 'text/plain');
$addressArray[] = array(trim( $finance ), 'Finance');
//$addressArray[] = array( 'developer@jollytel.com.au' , 'Developer');
//	$bccArray[] = array(trim("dave@hq.etxint.com"), $row2['contactname']);

	sendEmail( $finance , ' E Banc Accounts', $finance , 'Direct Debit Report', $finance , 'E Banc Accounts', $text, $addressArray, $attachArray );


function taxinvoice() {

global $linkid, $db, $date1, $date2, $date4, $pdf;

	/*
	 *
	 * BATCHVERSION=2
P,063-503,10279940,21st Century Auction,1100,TransRef30524
P,633-000,107299992,A Growing Affair,1650,TransRef12730
P,064-119,10124842,M Giufre,1650,TransRef15388
P,124-001,10606876,AA&RJ STEWART,1650,TransRef14918

	 *
	 */

$ctotal=0;
$net=0;
$counter=0;

$datee=str_pad($date4, 46);

//	$blah="0                 01MET       EMPIRE TRADE AUSTRALIA    371972DEBTORS     $datee\r\n";
	$blah= array( "BATCHVERSION=2" ) ;

//$query = dbRead("select * from erewards_bank, members where members.memid=erewards_bank.memid and type='0' and date='$date2'");
$query = dbRead("select * from members, invoice, tbl_admin_payment_types where (invoice.memid=members.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and tbl_admin_payment_types.ddrun='1' and members.paymenttype = 26 and accountno > 0 and invoice.date='$date2' and (overduefees+currentpaid+currentfees)>'5' and members.CID = 1 order by accountname");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

	 $query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.dis_date like '".$date1."-%' and memid='".$row[memid]."' and transactions.dollarfees < '0' and to_memid != '16083' group by memid");
  $row1 = mysql_fetch_assoc($query1);

  $total=($row[overduefees]+$row[currentpaid]+$row[currentfees]+$row1[fees]);
  $totall=$total*100;

  if($totall > '500')  {

//	  echo "<pre>" . print_r( $totall , true ) . "</pre>" ; exit ;

	  $counter++;

	  $line = array( 'P' ) ;

		$bankaccount = explode(",", $row['accountno']);
	  	$bank=chunk_split("$bankaccount[0]", 3, '-');
	  	$bank=rtrim($bank,"-");

	  $line[] = $bank ;

//	  	$banknumber=str_pad($bankaccount[1], 9, " ", STR_PAD_LEFT);

	  $line[] = trim( $bankaccount[1] ) ;

	  $name=str_pad(str_replace( ',' , '' , $row['accountname'] ) , 32);

	  $line[] = trim( $name ) ;

	  $line[] = $totall ;

	  $line[] = 'TransRef' . trim( $row[memid] ) ;

	  $blah[] = implode( ',' , $line ) ;

	  //$total=$row[amount_cash];
	  //$total=$total*100;
//	  $totall2=str_pad($totall, 10, "0", STR_PAD_LEFT);
//
//	  $ctotal=$ctotal+$totall;
//
//	  $memid=str_pad($row[memid], 5);
//	  $det="FEES TRANS- $memid";
//
//	  //$blah .= "1$bank$banknumber 13$totall2$name$det 484-799003598705EMPIRE TRADE AUS00000000\r\n";
//	  $blah .= "1$bank$banknumber 13$totall2$name$det 484-799003598705EMPIRE TRADE EXC00000000\r\n";
//
 	  $feePay = new feePayment($row[memid]);
 	  $feePay->payFees($_SESSION['feePayment']['memberRow'], $total, 1, 5, '', "Cash Fees Payment");

  }
 }

// $net=$ctotal;
//
// $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);
// $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
// $dtotal="0000000000";
// $net=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
//
//
// $blah .= "7999-999            $net$dtotal$ctotall                        $counter                                        ";

//	echo "<pre>" . print_r( $blah , true ) . "</pre>" ; exit ;


	return implode( "\r\n" , $blah ) ;

//	return $blah;

}
