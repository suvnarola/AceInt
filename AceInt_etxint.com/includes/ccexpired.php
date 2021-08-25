<?

include("global.php");

 if(checkmodule("Log")) {
  add_kpi("26", $_REQUEST['memid']);
 }

$date2 = date("Y", mktime(0,0,0,date("m"),1-1,date("Y")));

//$query = dbRead("select * from members where CID = '".$_SESSION['User']['CID']."' and (paymenttype = 'Visa' or paymenttype = 'Mastercard' or paymenttype = 'Bankcard' or paymenttype = 'Amex') order by expires");
$query = dbRead("select * from members, tbl_admin_payment_types where (members.paymenttype = tbl_admin_payment_types.FieldID) and members.CID = '".$_SESSION['User']['CID']."' and status not in (1) and ccrun = '1' order by expires");

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","E Banc Trade");
pdf_set_info($pdf, "Title","New Members");
pdf_set_info($pdf, "Creator", "Dave Richardson");
pdf_set_info($pdf, "Subject", "New Members");
pdf_set_value($pdf, compress, 9);
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 16);

pdf_set_text_pos($pdf, get_left_pos("Out of Date Credit Cards", $pdf, "297.5", 16, $font), 830);
pdf_continue_text($pdf, "Out of Date Credit Cards");

//lines between
pdf_setlinewidth($pdf, 2);
pdf_moveto($pdf, 200, 810);
pdf_lineto($pdf, 400, 810);
pdf_stroke($pdf);

$offset3 = 0;
$counter3 = 0;
$page = 1;

while($row = mysql_fetch_assoc($query)) {

$exdate_temp = explode("/", $row[expires]);

$exdate1 = $exdate_temp[0];
$exdate2 = $exdate_temp[1];
$thisyear = date("y");
$thismonth = date("m");

if(($exdate2 < $thisyear) or (($exdate1 <= $thismonth) and ($exdate2 == $thisyear))) {

$offset3 = $counter3;

$box3 = "$row[phonearea] $row[phoneno]";


pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 8);

pdf_set_text_pos($pdf, 15, 805-$offset3);
pdf_continue_text($pdf, $row[memid]);
pdf_set_text_pos($pdf, 75, 805-$offset3);
pdf_continue_text($pdf, $row[companyname]);
pdf_set_text_pos($pdf, 250, 805-$offset3);
pdf_continue_text($pdf, $row[contactname]);
pdf_set_text_pos($pdf, 375, 805-$offset3);
pdf_continue_text($pdf, $box3);
pdf_set_text_pos($pdf, 475, 805-$offset3);
pdf_continue_text($pdf, $row[Type]);
pdf_set_text_pos($pdf, 530, 805-$offset3);
pdf_continue_text($pdf, $row[expires]);

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 8);


//lines between
pdf_setlinewidth($pdf, 1);
pdf_moveto($pdf, 10, 795-$offset3);
pdf_lineto($pdf, 585, 795-$offset3);
pdf_stroke($pdf);

$counter3 = $counter3 + 15;

	if($counter3 > 750) {

	 //lines
	 pdf_setlinewidth($pdf, 0.5);
	 pdf_moveto($pdf, 10, 34);
	 pdf_lineto($pdf, 585, 34);
	 pdf_stroke($pdf);

     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	 $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
	 pdf_setfont($pdf, $font, 8);
	 pdf_set_text_pos($pdf, 550, 30);
	 pdf_continue_text($pdf, "Page $page");

 	 $page = $page + 1;

	 pdf_end_page($pdf);
	 pdf_begin_page($pdf, 595, 842);
	 $counter3 = 0;
	 $offset3 =0;
	}

}

}

//lines
pdf_setlinewidth($pdf, 0.5);
pdf_moveto($pdf, 10, 34);
pdf_lineto($pdf, 585, 34);
pdf_stroke($pdf);

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 8);
pdf_set_text_pos($pdf, 550, 30);
pdf_continue_text($pdf, "Page $page");

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = PDF_get_buffer($pdf);
pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","CreditCards.pdf","inline");


?>