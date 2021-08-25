<? require("global.php"); ?>
<?
$CDataSQL = dbRead("select * from countrydata where CID = '".$_SESSION['Country']['countryID']."'");
$CDataRow = mysql_fetch_assoc($CDataSQL);

//$query=dbRead("select memid, companyname, contactname, memusername, mempassword, overdraft, tradeq, phone from members, area where members.licensee = area.FieldID and (members.datepacksent is NULL or members.datepacksent = '0000-00-00') and members.CID like '".$_SESSION['User']['CID']."' and status!='2' order by members.memid");
$query=dbRead("select memid, companyname, contactname, memusername, mempassword, overdraft, tradeq, phone from members, area where members.licensee = area.FieldID and (members.datepacksent is NULL or members.datepacksent = '0000-00-00') and members.CID like '".$_SESSION['User']['CID']."' order by members.memid");

$query5=dbRead("select * from country where countryID='".$_SESSION['User']['CID']."'");
$row5=mysql_fetch_assoc($query5);

$auth="".$CDataRow['n_auth']." $row5[authno]";
$secu=$CDataRow['n_secure'];

if($_SESSION['Country']['logo'] == "ept")  {
  $web = "www.eplanettrade.com";
} elseif($_SESSION['Country']['logo'] == "etx") {
  $web = "www.empiretrade.com.au";
} else {
  $web = "www.ebanctrade.com";
}

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","E Banc Trade");
pdf_set_info($pdf, "Title","New Member Doc Pack");
pdf_set_info($pdf, "Creator", "E Banc Trade");
pdf_set_info($pdf, "Subject", "New Member Docs");
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

$counter=1;

#loop around
while($row = mysql_fetch_assoc($query)) {

$remainder=$counter % 4;

if($remainder == 3) {
 $offset=420;
} elseif($remainder == 2) {
 $offset=210.5;
} elseif($remainder == 1) {
 $offset=0;
} elseif($remainder == 0) {
 $offset=631;
}

if(!$row[tradeq]) {
 $tno=" ";
} else {
 if($_SESSION['Country']['countryID'] == 8 && $row['mobile'])  {
   $tno = "$row[tradeq])   Phone: $row[mobile]";
 } else {
   $tno = "$row[tradeq])   Phone: $row[phone]";
 }
}

$over = number_format($row[overdraft],2);

//put image up the top.
//$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg");
//pdf_place_image($pdf, $pdfimage, 35, 795-$offset, .75);
$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg", '');
pdf_fit_image($pdf, $pdfimage, 35, 795-$offset, "scale 0.75");

//do some text up the top.
pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);
pdf_setfont($pdf, $font, 12);
pdf_set_text_pos($pdf, get_left_pos($CDataRow['n_company'], $pdf, "360", 12, $font), 837-$offset);
pdf_continue_text($pdf, $CDataRow['n_company']);

pdf_setfont($pdf, $font, 10);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_accno'], $pdf, "195", 10, $font), 785-$offset);
pdf_continue_text($pdf, $CDataRow['n_accno']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_facility'], $pdf, "195", 10, $font), 765-$offset);
pdf_continue_text($pdf, $CDataRow['n_facility']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_user'], $pdf, "460", 10, $font), 785-$offset);
pdf_continue_text($pdf, $CDataRow['n_user']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_pass'], $pdf, "460", 10, $font), 765-$offset);
pdf_continue_text($pdf, $CDataRow['n_pass']);
pdf_set_text_pos($pdf, get_right_pos($CDataRow['n_customer'], $pdf, "195", 10, $font), 745-$offset);
pdf_continue_text($pdf, $CDataRow['n_customer']);


$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 14);
pdf_set_text_pos($pdf, get_left_pos($row['companyname'], $pdf, "360", 14, $font), 820-$offset);
pdf_continue_text($pdf, $row['companyname']);

pdf_setfont($pdf, $font, 10);
pdf_set_text_pos($pdf, get_left_pos($auth, $pdf, "298", 10, $font), 720-$offset);
pdf_continue_text($pdf, $auth);

pdf_setfont($pdf, $font, 8);
pdf_set_text_pos($pdf, get_left_pos($secu, $pdf, "298", 8, $font), 708-$offset);
pdf_continue_text($pdf, $secu);
pdf_set_text_pos($pdf, 35, 673-$offset);

$text = explode(",", $row5['address2'],2);
foreach($text as $Line) {
  pdf_continue_text($pdf, trim($Line));
  $textheight += 8;
}

//pdf_continue_text($pdf, decode_text($row5['address1']));
//pdf_continue_text($pdf, decode_text($row5['address2']));
pdf_set_text_pos($pdf, get_left_pos($row5['abn'], $pdf, "297", 8, $font), 689-$offset);
pdf_continue_text($pdf, $row5['abn']);
pdf_set_text_pos($pdf, get_left_pos(" ", $pdf, "297", 8, $font), 681-$offset);
pdf_continue_text($pdf, " ");
pdf_set_text_pos($pdf, get_left_pos("Phone: ".$row5['phone'], $pdf, "297", 8, $font), 673-$offset);
pdf_continue_text($pdf, "Phone: ".$row5['phone']);
pdf_set_text_pos($pdf, get_left_pos("Fax: ".$row5['fax'], $pdf, "297", 8, $font), 665-$offset);
pdf_continue_text($pdf, "Fax: ".$row5['fax']);
pdf_set_text_pos($pdf, get_right_pos("Email: ".$row5['email'], $pdf, "560", 8, $font), 673-$offset);
pdf_continue_text($pdf, "Email: ".$row5['email']);
pdf_set_text_pos($pdf, get_right_pos("http://".$web."", $pdf, "560", 8, $font), 665-$offset);
pdf_continue_text($pdf, "http://".$web."");

pdf_setfont($pdf, $font, 10);
pdf_set_text_pos($pdf, 200, 785-$offset);
pdf_continue_text($pdf, $row[memid]);
pdf_set_text_pos($pdf, 200, 765-$offset);
pdf_continue_text($pdf, $over);
pdf_set_text_pos($pdf, 465, 785-$offset);
pdf_continue_text($pdf, $row[memusername]);
pdf_set_text_pos($pdf, 465, 765-$offset);
pdf_continue_text($pdf, $row[mempassword]);
pdf_set_text_pos($pdf, 200, 745-$offset);
if($_SESSION['Country']['countryID'] == "15") {
 pdf_continue_text($pdf, $row[tradeq]."  ".get_word("7").": ".$row[phone]."  Movil: +34 699980114");
} else {
 pdf_continue_text($pdf, $row[tradeq]."    ".get_word("7").": ".$row[phone]);
}

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

send_to_browser($buffer,"application/pdf","MemberDetailsPack.pdf","inline");

?>