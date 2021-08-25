<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date2 = date("Y-m", mktime(0,0,0,date("m"),1-1,date("Y")));

 $SQLQuery = dbRead("select * from country where countryID = '1' order by countryID");
 while($CountryRow = mysql_fetch_assoc($SQLQuery)) {

  // define the text.
   $text = "Dear Finance,\r\n\r\nAttached is your current Transaction Fee Report.";

  // get the actual taxinvoice ready.
   $buffer = taxinvoice($CountryRow[countryID]);

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.
   $mail->add_attachment($buffer, 'LicFees.pdf', 'application/pdf');

  // build the message.
   $mail->build_message();

  // send the message.
   $mail->send('Finance', 'finance@'.$CountryRow[countrycode].'.ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Licensee Fees Owing','Bcc: reports@ebanctrade.com');
   //$mail->send('Finance', 'dave@ebanctrade.com', 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Licensee Fees Owing');

 }

function taxinvoice($CID = false) {

global $linkid, $db, $date2, $row, $pdf, $offset3;

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
pdf_open_file($pdf,'');
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

$pageno = 1;
$offset3 = 0;

layout();

$query  = dbRead("select * from members, area WHERE (members.memid = area.accno) and members.CID=1 order by tradeq");
while($row = mysql_fetch_assoc($query)) {

 $mm = $_REQUEST[currentmonth]+1;
 $newdate = date("Y-m", mktime(0,0,0,date("m")-1,1,date("Y")));
 $date1 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

 $query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.dis_date > '$date1' and memid='$row[memid]' and transactions.dollarfees < '0' and to_memid != '16083' group by memid");
 $row1 = mysql_fetch_assoc($query1);

 $query3  = dbRead("select * from invoice WHERE memid = ".$row['memid']." AND date like '$newdate-%'");
 $row3 = mysql_fetch_assoc($query3);

 $total=($row3[overduefees]+$row3[currentpaid]+$row3[currentfees]+$row1[fees]);

 $offset3+=20;

 if($offset3 > 650) {

  $offset3+=20;
  pdf_setfont($pdf, $font, 8);
  pdf_set_text_pos($pdf, get_right_pos("Page $pageno", $pdf, "570", 8, $font), 775-$offset3);
  pdf_continue_text($pdf, "Page $pageno");

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $offset3=0;
  $pageno++;

  layout();

  $offset3+=20;

 }

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, 25, 765-$offset3);
pdf_continue_text($pdf, $row[memid]);
$name=substr($row[companyname], 0, 35);
pdf_set_text_pos($pdf, 90, 765-$offset3);
pdf_continue_text($pdf, $name);
pdf_set_text_pos($pdf, get_right_pos($row3[place], $pdf, "293", 8, $font), 765-$offset3);
pdf_continue_text($pdf, $row[place]);
pdf_set_text_pos($pdf, get_right_pos(number_format($total,2), $pdf, "510", 8, $font), 765-$offset3);
pdf_continue_text($pdf, number_format($total,2));

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 25, 750-$offset3);
pdf_lineto($pdf, 585, 750-$offset3);
pdf_stroke($pdf);

}

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = pdf_get_buffer($pdf);

pdf_delete($pdf);
return $buffer;

}


function layout() {

 global $pdf, $font, $row, $date2, $font, $offset3;

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 16);

pdf_set_text_pos($pdf, get_left_pos("Transaction Fee Report for $row[place] upto $date2", $pdf, "297.5", 16, $font), 800-$offset3);
pdf_continue_text($pdf, "Licensee Fees Owning Report for $row[place] upto $date2");

pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, 25, 770-$offset3);
pdf_continue_text($pdf, "Mem ID");
pdf_set_text_pos($pdf, 90, 770-$offset3);
pdf_continue_text($pdf, "Member Name");
pdf_set_text_pos($pdf, get_right_pos("Agent", $pdf, "293", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Agent");
pdf_set_text_pos($pdf, get_right_pos("Fees", $pdf, "510", 8, $font), 770-$offset3);
pdf_continue_text($pdf, "Fees");

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 25, 755-$offset3);
pdf_lineto($pdf, 585, 755-$offset3);
pdf_stroke($pdf);

}
