<? require("global.php"); ?>
<?
$CDataSQL = dbRead("select * from countrydata where CID = '".$_SESSION['Country']['countryID']."'");
$CDataRow = mysql_fetch_assoc($CDataSQL);

$query=dbRead("select * from members where (datepacksent is NULL or datepacksent = '0000-00-00') and CID='".$_SESSION['User']['CID']."' order by memid");

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","E Banc Trade");
pdf_set_info($pdf, "Title","New Member Docs File");
pdf_set_info($pdf, "Creator", "E Banc Trade");
pdf_set_info($pdf, "Subject", "New Member Docs");
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

$counter=1;
//$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg");
$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg", '');

#loop around
while($row = mysql_fetch_assoc($query)) {

$remainder=$counter % 3;

if($remainder == 2) {
 $offset=265;
} elseif($remainder == 1) {
 $offset=0;
} elseif($remainder == 0) {
 $offset=530;
}

if (!$row[membershipfeepaid]) {
 $row[membershipfeepaid]="0.00";
}

// top rectangle
pdf_setlinewidth($pdf, 2.5); //make the border of the rectangle a bit wider
pdf_rect($pdf, 50, 552-$offset, 496, 250); //draw the rectangle
pdf_stroke($pdf); //stroke the path with the current color(not yet :-))and line width

//3 top lines in boxes
pdf_setlinewidth($pdf, 2.5);
pdf_moveto($pdf, 50, 752-$offset);
pdf_lineto($pdf, 546, 752-$offset);
pdf_stroke($pdf);

//put image up the top.
pdf_fit_image($pdf, $pdfimage, 51.5, 753.5-$offset, "scale 0.75");

//do some text up the top.
pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);
pdf_setfont($pdf, $font, 12);
pdf_set_text_pos($pdf, get_left_pos($CDataRow['n_company'], $pdf, "360.5", 12, $font), 794-$offset);
pdf_continue_text($pdf, $CDataRow['n_company']);

pdf_setfont($pdf, $font, 14);
pdf_set_text_pos($pdf, get_left_pos($row[companyname], $pdf, "360.5", 14, $font), 775-$offset);
pdf_continue_text($pdf, $row[companyname]);

pdf_setfont($pdf, $font, 10);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_contact'], $pdf, "225", 10, $font), 740-$offset);
pdf_continue_text($pdf, $CDataRow['n_contact']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_accno'], $pdf, "225", 10, $font), 720-$offset);
pdf_continue_text($pdf, $CDataRow['n_accno']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_user'], $pdf, "225", 10, $font), 700-$offset);
pdf_continue_text($pdf, $CDataRow['n_user']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_pass'], $pdf, "225", 10, $font), 680-$offset);
pdf_continue_text($pdf, $CDataRow['n_pass']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_facility'], $pdf, "225", 10, $font), 660-$offset);
pdf_continue_text($pdf, $CDataRow['n_facility']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_enton'], $pdf, "225", 10, $font), 640-$offset);
pdf_continue_text($pdf, $CDataRow['n_enton']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_fee'], $pdf, "225", 10, $font), 620-$offset);
pdf_continue_text($pdf, $CDataRow['n_fee']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_paid'], $pdf, "225", 10, $font), 600-$offset);
pdf_continue_text($pdf, $CDataRow['n_paid']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_entby'], $pdf, "225", 10, $font), 580-$offset);
pdf_continue_text($pdf, $CDataRow['n_entby']);


$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 10);
pdf_set_text_pos($pdf, 235, 740-$offset);
pdf_continue_text($pdf, $row[contactname]);
pdf_set_text_pos($pdf, 235, 720-$offset);
pdf_continue_text($pdf, $row[memid]);
pdf_set_text_pos($pdf, 235, 700-$offset);
pdf_continue_text($pdf, $row[memusername]);
pdf_set_text_pos($pdf, 235, 680-$offset);
pdf_continue_text($pdf, $row[mempassword]);
pdf_set_text_pos($pdf, 235, 660-$offset);
pdf_continue_text($pdf, $row[overdraft]);
pdf_set_text_pos($pdf, 235, 640-$offset);
pdf_continue_text($pdf, $row[datejoined]);
pdf_set_text_pos($pdf, 235, 620-$offset);
pdf_continue_text($pdf, $row[transfeecash]);
pdf_set_text_pos($pdf, 235, 600-$offset);
pdf_continue_text($pdf, $row[membershipfeepaid]);
pdf_set_text_pos($pdf, 235, 580-$offset);
pdf_continue_text($pdf, $row[lastedit]);

if($remainder == 0) {
 pdf_end_page($pdf);
 pdf_begin_page($pdf, 595, 842);
}

$counter++;
}

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = PDF_get_buffer($pdf);
pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","MemberDetailsFile.pdf","inline");

?>