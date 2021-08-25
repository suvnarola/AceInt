<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 if($_REQUEST['search']) {
 	if($_REQUEST['countryID']) {
		$cc = $_REQUEST['countryID'];
	} else {
		$cc = $_SESSION['Country']['countryID'];
	}
	$buffer = activity($_REQUEST['lic'],$cc);
 	send_to_browser($buffer, "appliction/pdf", "activity.pdf","InLine");
 } else {
 //$date2 = date("Y-m-d", mktime(0,0,0,date("m")-1,1-1,date("Y")));
 //$date3 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 $date4 = date("Y-m", mktime(0,0,0,date("m")-1,1,date("Y")));

 $query = dbRead("select * from area where `drop` = 'Y' order by place");

#loop around
while($row = mysql_fetch_assoc($query)) {

   $otheremail = "";

   if($row[locationID] > 0) {

     $query1 = dbRead("select email from area where FieldID=$row[locationID]");
     $row1 = mysql_fetch_assoc($query1);

     $otheremail .= ",".$row1[email];
   }

   if($row[locationID2] > 0) {

     $query2 = dbRead("select email from area where FieldID=$row[locationID2]");
     $row2 = mysql_fetch_assoc($query2);

     $otheremail .= ",".$row2[email];
   }

   $email = $row[email];

   if($row[email] != $row[reportemail])  {
     $otheremail .= ",".$row[reportemail];
   }

   if(!$row[email]) {
    $email = 'dave@ebanctrade.com';
   }

  // define the text.
   $text = "Dear $row[tradeq],\r\n\r\nAttached is your current Activity Report.";

  // get the actual taxinvoice ready.
   $buffer = activity($row[FieldID]);

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.
   $mail->add_attachment($buffer, 'activity-'.$date4.'.pdf', 'application/pdf');

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send($row[tradeq], $email, 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Activity Report - '.$row[place],'Bcc: reports@ebanctrade.com'.$otheremail);

}
}

function activity($run_fieldid = false,$country = false)  {

 global $linkid, $db, $date2, $date3, $date4, $row, $pdf;

 $date2 = date("Y-m-d", mktime(0,0,0,date("m")-1,1-1,date("Y")));
 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 $date4 = date("Y-m", mktime(0,0,0,date("m")-1,1,date("Y")));

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
pdf_begin_page($pdf, 842, 595);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");


pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);

$offset3 = 0;
$pageno = 1;
$tbuy = 0;
$tsell = 0;

templ();
//if($country) {
	//$query4 = dbRead("select invoice.memid, members.companyname, members.overdraft, members.reoverdraft, members.status, currentfees, currentpaid, overduefees from invoice, members, `status` where invoice.memid=members.memid and (members.status = status.FieldID) and date = '$date3' and CID='$country' and status.mem_send_inv = 1 order by companyname");
//} else {
	if($run_fieldid) {
		$rf = " and licensee=".$run_fieldid."";
	} else {
		$rf = " and CID=".$country."";
	}
	$query4 = dbRead("select invoice.memid, members.companyname, members.overdraft, members.reoverdraft, members.status, currentfees, currentpaid, overduefees from invoice, members, `status` where invoice.memid=members.memid and (members.status = status.FieldID) and date = '$date3'$rf and status.mem_send_inv = 1 order by companyname");
//}
//$query4 = dbRead("select invoice.memid, members.companyname, members.status, currentfees, currentpaid, overduefees from invoice, members, status where invoice.memid=members.memid and (members.status = status.FieldID) and date = '$date3' and licensee='$run_fieldid' and status.mem_send_inv = 1 order by companyname");
//$query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees, members.memid, members.companyname, status from transactions, members where (members.memid = transactions.memid) and members.licensee = '$run_fieldid' and dis_date <= '#$date2#' and status <>'1' group by transactions.memid");

#loop around
while($row4 = mysql_fetch_assoc($query4)) {

$query5 = dbRead("select (sum(sell)) as opsell, (sum(buy)) as opbuy from transactions where memid='$row4[memid]' and dis_date like '$date4-%'");
$row5=mysql_fetch_assoc($query5);

//$query6 = dbRead("select * from invoice where memid='$row4[memid]' and date = '#$date2#'");
$query6 = dbRead("select (sum(sell)-sum(buy)) as optrade from transactions where dis_date <= '$date2' and memid='$row4[memid]' group by transactions.memid");
$row6=mysql_fetch_assoc($query6);

//$newdate=explode("-", $row[date]);
//$newdate3=date("d/m/Y", mktime(0,0,0,$newdate[1],01,$newdate[0]));

if($offset3 > 480) {

 pdf_setfont($pdf, $font, 8);

 pdf_set_text_pos($pdf, get_right_pos("Page $pageno", $pdf, "834", 8, $font), 520-$offset3);
 pdf_continue_text($pdf, "Page $pageno");

 pdf_end_page($pdf);
 pdf_begin_page($pdf, 842, 595);
 $offset3 = 0;
 $pageno++;

 templ();

}

$tradetotal+=$row6[optrade];
$close=($row6[optrade] + $row5[opsell] - $row5[opbuy]);
$net = $close - $row4['overdraft'] - $row4['reoverdraft'];
$tnet += $net;

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, 15, 530-$offset3);
pdf_continue_text($pdf, $row4[memid]);
//$name=substr($row4[companyname], 0, 35);
pdf_set_text_pos($pdf, 60, 530-$offset3);
//pdf_continue_text($pdf, $name);
pdf_continue_text($pdf, $row4[companyname]);
pdf_set_text_pos($pdf, get_left_pos("Stat", $pdf, "222.5", 8, $font), 530-$offset3);
pdf_continue_text($pdf, $row4[status]);
pdf_set_text_pos($pdf, get_right_pos(number_format($row6[optrade],2), $pdf, "312", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($row6[optrade],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($row5[opbuy],2), $pdf, "399", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($row5[opbuy],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($row5[opsell],2), $pdf, "486", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($row5[opsell],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($close,2), $pdf, "573", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($close,2));
pdf_set_text_pos($pdf, get_right_pos(number_format($net,2), $pdf, "640", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($net,2));
pdf_set_text_pos($pdf, get_right_pos(number_format($row4[currentfees],2), $pdf, "690", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($row4[currentfees],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($row4[overduefees],2), $pdf, "763", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($row4[overduefees],2));
pdf_set_text_pos($pdf, get_right_pos(number_format($row4[currentpaid],2), $pdf, "834", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($row4[currentpaid],2));

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 15, 515-$offset3);
pdf_lineto($pdf, 834, 515-$offset3);
pdf_stroke($pdf);

$offset3+=20;

$tbuy=$tbuy+$row5[opbuy];
$tsell=$tsell+$row5[opsell];

}

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, get_right_pos(number_format($tnet,2), $pdf, "640", 8, $font), 530-$offset3);
pdf_continue_text($pdf, number_format($net,2));
//pdf_show_boxed($pdf, "", 240, 520-$offset3, 40, 10, "right");
//pdf_show_boxed($pdf, number_format($tbuy,2), 367, 520-$offset3, 57, 10, "right");
//pdf_show_boxed($pdf, number_format($tsell,2), 448, 520-$offset3, 57, 10, "right");


pdf_end_page($pdf);
pdf_begin_page($pdf, 842, 595);

$offset3 = 0;
$tradetotal = 0;
$cashtotal = 0;


//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = pdf_get_buffer($pdf);

return $buffer;
pdf_delete($pdf);

}

function templ() {

global $pdf, $font, $row, $date2, $date3;

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 16);

pdf_set_text_pos($pdf, get_left_pos("$row[place] Activity Report upto $date3", $pdf, "396", 16, $font), 580);
pdf_continue_text($pdf, "$row[place] Activity Report upto $date3");

pdf_setfont($pdf, $font, 10);

pdf_set_text_pos($pdf, get_left_pos("Trade Account", $pdf, "420.5", 10, $font), 560-$offset3);
pdf_continue_text($pdf, "Trade Account");
pdf_set_text_pos($pdf, get_left_pos("Cash Fees", $pdf, "710.5", 10, $font), 560-$offset3);
pdf_continue_text($pdf, "Cash Fees");

// lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 232, 545-$offset3);
pdf_lineto($pdf, 640, 545-$offset3);
pdf_stroke($pdf);

pdf_moveto($pdf, 645, 545-$offset3);
pdf_lineto($pdf, 834, 545-$offset3);
pdf_stroke($pdf);

pdf_setfont($pdf, $font, 10);

pdf_set_text_pos($pdf, 15, 545-$offset3);
pdf_continue_text($pdf, "Acc No");
pdf_set_text_pos($pdf, 60, 545-$offset3);
pdf_continue_text($pdf, "Company Name");
pdf_set_text_pos($pdf, get_left_pos("Stat", $pdf, "222.5", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Stat");
pdf_set_text_pos($pdf, get_right_pos("Opening", $pdf, "312", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Opening");
pdf_set_text_pos($pdf, get_right_pos("Buys", $pdf, "399", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Buys");
pdf_set_text_pos($pdf, get_right_pos("Sells", $pdf, "486", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Sells");
pdf_set_text_pos($pdf, get_right_pos("Closing", $pdf, "573", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Closing");
pdf_set_text_pos($pdf, get_right_pos("Net Pos", $pdf, "640", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Net Pos");
pdf_set_text_pos($pdf, get_right_pos("Current", $pdf, "690", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Current");
pdf_set_text_pos($pdf, get_right_pos("Overdue", $pdf, "763", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Overdue");
pdf_set_text_pos($pdf, get_right_pos("Paid Fees", $pdf, "834", 10, $font), 545-$offset3);
pdf_continue_text($pdf, "Paid Fees");


// lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 15, 532-$offset3);
pdf_lineto($pdf, 834, 532-$offset3);
pdf_stroke($pdf);

}
