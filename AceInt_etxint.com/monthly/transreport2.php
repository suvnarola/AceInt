<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date2 = date("Y-m", mktime(0,0,0,date("m")-1,1-1,date("Y")));

 $query = dbRead("select * from area where CID = 1 and Display = 'Y' order by place");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

   $otheremail = "";

   if($row[locationID] > 0) {

     $query1 = dbRead("select reportemail from area where FieldID=$row[locationID]");
     $row1 = mysql_fetch_assoc($query1);
     if($row1['reportemail']) {
       $otheremail .= ";".$row1['reportemail'];
     }
   }

   if($row[LocationID2] > 0) {

     $query2 = dbRead("select reportemail from area where FieldID=$row[LocationID2]");
     $row2 = mysql_fetch_assoc($query2);
     if($row2['reportemail']) {
       $otheremail .= ";".$row2[reportemail];
     }
   }

   //if($row['display'] == 'Y')  {
    $email = $row[reportemail];
   //} else {
    //$email = 'dave@ebanctrade.com';
   //}

   if(!$email) {
    $email = 'dave@ebanctrade.com';
   }

  // define the text.
   $text = "Dear $row[tradeq],\r\n\r\nAttached is an amended December Transaction Fee Report as the previously send report contained errors. Please amend your December invoice and resend.";

  // get the actual taxinvoice ready.
   $buffer = taxinvoice($row[FieldID]);

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.
   $mail->add_attachment($buffer, 'transaction-'.$date4.'.pdf', 'application/pdf');

  // build the message.
   $mail->build_message();

  // send the message.
   //$mail->send($row[tradeq], $email, 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Transaction Report - '.$row[place],'Bcc: reports@ebanctrade.com'.$otheremail);
   //$mail->send($row[tradeq], 'dave@ebanctarde.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Transaction Report - '.$row[place],'Bcc: reports@ebanctrade.com'.$otheremail);
	$subject = "Amended December Transaction Report - ".$row[place];

     unset($attachArray);
     unset($addressArray);
     unset($bccArray);

   	$attachArray[] = array($buffer, 'transreport.pdf', 'base64', 'application/pdf');

	if(strstr($email, ";")) {
		$emailArray = explode(";", $email);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row[tradeq]);
			//$addressArray[] = array(trim("dave.r@hq.etxint.com"), $row[tradeq]);
		}
	} else {
		$addressArray[] = array(trim($email), $row[tradeq]);
		//$addressArray[] = array(trim("dave.r@hq.etxint.com"), $row[tradeq]);
	}

	$bccArray[] = array("reports@ebanctrade.com", $row[tradeq]);

	sendEmail("accounts@au.empireXchange.com", 'Empire Accounts', "accounts@au.empireXchange.com", $subject, "accounts@au.empireXchange.com", 'Empire Accounts', $text, $addressArray, $attachArray, $bccArray);

}

function taxinvoice($run_fieldid) {

global $linkid, $db, $date2, $row, $pdf;

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","RDI Host");
pdf_set_info($pdf, "Title","Invoice 1");
pdf_set_info($pdf, "Creator", "Antony Puckey");
pdf_set_info($pdf, "Subject", "Hosting Invoice");
pdf_set_value($pdf, compress, 9);
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);

layout();

//$query3 = dbRead("select * from feespaid, members where (feespaid.memid=members.memid) and members.licensee='$run_fieldid' and paymentdate like '$date2-%' order by paymentdate");
$query3 = dbRead("select * from feespaid, members where (feespaid.memid=members.memid) and feespaid.area='$run_fieldid' and paymentdate like '$date2-%' and feespaid.type not in (6,7,8,9) order by paymentdate");

$offset3 = 0;
$commtotal = 0;
$pageno = 1;

#loop around
while($row3 = mysql_fetch_assoc($query3)) {

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 35, 570);
pdf_lineto($pdf, 560, 570);
pdf_stroke($pdf);

$offset3+=20;

if($offset3 > 650) {

 $offset3+=20;

 pdf_set_text_pos($pdf, get_right_pos("Page $pageno", $pdf, "570", 8, $font), 775-$offset3);
 pdf_continue_text($pdf, "Page $pageno");

 pdf_end_page($pdf);
 pdf_begin_page($pdf, 595, 842);
 $offset3=0;
 $pageno++;

 layout();

 $offset3+=20;

}

$nett=($row3[amountpaid]-$row3[deducted_fees]);

$comm=($nett/100)*$row3[percent];

if($row3['type'] == 4 || $row3['type'] == 3) {
 $ty = "S";
} elseif($row3['type'] == 1  || $row3['type'] == 2) {
 $ty = "B";
} elseif($row['type'] == 5) {
 $ty = "ACS";
} elseif($row['type'] == 6) {
 $ty = "UCS";
}

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, 25, 765-$offset3);
pdf_continue_text($pdf, $row3[paymentdate]);
$name=substr($row3[companyname], 0, 35);
pdf_set_text_pos($pdf, 90, 765-$offset3);
pdf_continue_text($pdf, $row3[companyname]);
pdf_set_text_pos($pdf, get_right_pos($row3[memid], $pdf, "293", 8, $font), 765-$offset3);
pdf_continue_text($pdf, $row3[memid]);
pdf_set_text_pos($pdf, get_right_pos($ty, $pdf, "323", 8, $font), 765-$offset3);
pdf_continue_text($pdf, $ty);
pdf_set_text_pos($pdf, get_right_pos(number_format($row3[amountpaid],2), $pdf, "395", 8, $font), 765-$offset3);
pdf_continue_text($pdf, number_format($row3[amountpaid],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($row3[deducted_fees],2), $pdf, "440", 8, $font), 765-$offset3);
pdf_continue_text($pdf, number_format($row3[deducted_fees],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($nett,2), $pdf, "505", 8, $font), 765-$offset3);
pdf_continue_text($pdf, number_format($nett,2));
pdf_set_text_pos($pdf, get_right_pos(number_format($comm,2), $pdf, "575", 8, $font), 765-$offset3);
pdf_continue_text($pdf, number_format($comm,2));

$commtotal+=$comm;

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 25, 750-$offset3);
pdf_lineto($pdf, 585, 750-$offset3);
pdf_stroke($pdf);

}

$offset3+=20;
if($row['CID'] == 12) {
 $commtotal=number_format(round($commtotal),2);
} else {
 $commtotal=number_format($commtotal,2);
}

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, get_right_pos("TOTALS:", $pdf, "485", 8, $font), 765-$offset3);
pdf_continue_text($pdf, "TOTALS:");
pdf_set_text_pos($pdf, get_right_pos($commtotal, $pdf, "575", 8, $font), 765-$offset3);
pdf_continue_text($pdf, $commtotal);

$offset3 = 0;
$commtotal = 0;

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = pdf_get_buffer($pdf);

pdf_delete($pdf);
return $buffer;

}

function layout() {

 global $pdf, $font, $row, $date2, $font;

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 16);

pdf_set_text_pos($pdf, get_left_pos("Transaction Fee Report for $row[place] upto $date2", $pdf, "297.5", 16, $font), 800);
pdf_continue_text($pdf, "Transaction Fee Report for $row[place] upto $date2");

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, 25, 770-$offset3);
pdf_continue_text($pdf, "Date");
pdf_set_text_pos($pdf, 90, 770-$offset3);
pdf_continue_text($pdf, "Member Name");
pdf_set_text_pos($pdf, get_right_pos("Mem ID", $pdf, "293", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Mem ID");
pdf_set_text_pos($pdf, get_right_pos("B/S", $pdf, "323", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "B/S");
pdf_set_text_pos($pdf, get_right_pos("Fees Paid", $pdf, "395", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Fees Paid");
pdf_set_text_pos($pdf, get_right_pos("Fees", $pdf, "440", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Fees");
pdf_set_text_pos($pdf, get_right_pos("Nett", $pdf, "505", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Nett");
pdf_set_text_pos($pdf, get_right_pos("Comm", $pdf, "585", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Comm");

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 25, 755-$offset3);
pdf_lineto($pdf, 585, 755-$offset3);
pdf_stroke($pdf);

}
