<? require("global.php"); ?>
<?

$query = dbRead("select * from members where (datepacksent is NULL or datepacksent = '0000-00-00') and CID='".$_SESSION['User']['CID']."' order by members.memid");
//$query = dbRead("select * from members where letters = 3 and CID='".$_SESSION['User']['CID']."' and status = 6 order by members.memid");

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","RDI Host");
pdf_set_info($pdf, "Title","Invoice 1");
pdf_set_info($pdf, "Creator", "Antony Puckey");
pdf_set_info($pdf, "Subject", "Hosting Invoice");
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

$counter = 0;
$x = 0;
$y = 0;

//$blah = addresslayout($_SESSION['Country']['countryID']);
$blah = addresslayout(12);


#loop around
while($row = mysql_fetch_assoc($query)) {

 $blah = addresslayout($row['CID']);

 if($row[postalno]) {
  $streetno="$row[postalno] ";
 } else {
  unset($streetno);
 }

 if($row[postalsuburb]) {
  $suburb=" $row[postalsuburb]";
 } else {
  unset($suburb);
 }

$addressbox="$row[contactname]\r\n$row[companyname]\r\n$streetno$row[postalname]$suburb\r\n$row[postalcity]  $row[postalstate] $row[postalpostcode]";

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
pdf_setfont($pdf, $font, 12);

pdf_set_text_pos($pdf, 25+$x, 812-$y);

  foreach($blah as $key => $value) {
    $addline = "";

    foreach($value as $key2) {
     if($row[$key2]) {
      $addline .= $row[$key2] ." ";
     }
    }

    if(trim($addline))  {

     $NewCompanyname = explode("|", wordwrap($addline, 25, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
     }
    }
  }

if($rrr)  {
$pos = 0;
if($row[accholder] != $row[companyname]) {
pdf_set_text_pos($pdf, 25+$x, 812-($y+$pos));
$NewDesc = explode("|", wordwrap($row[accholder], 25, "|"));
foreach($NewDesc as $Line) {
 pdf_continue_text($pdf, $Line);
 $pos += 12;
}
}

pdf_set_text_pos($pdf, 25+$x, 812-($y+$pos));
$NewDesc = explode("|", wordwrap($row[companyname], 25, "|"));
foreach($NewDesc as $Line) {
 pdf_continue_text($pdf, $Line);
 $pos += 12;
}


pdf_set_text_pos($pdf, 25+$x, 812-($y+$pos));
$NewDesc = explode("|", wordwrap("$streetno$row[postalname]$suburb", 25, "|"));
foreach($NewDesc as $Line) {
 pdf_continue_text($pdf, $Line);
 $pos += 12;
}


pdf_set_text_pos($pdf, 25+$x, 812-($y+$pos));
$NewDesc = explode("|", wordwrap("$row[postalcity]  $row[postalstate] $row[postalpostcode]", 25, "|"));
foreach($NewDesc as $Line) {
 pdf_continue_text($pdf, $Line);
 $pos += 12;
}
}

$counter=$counter+1;

 if ($counter <= 2) {
  $x=$x+197;
  $ttexthieght=0;
 } elseif($counter > 2) {
  $x=0;
  $y=$y+115;
  $ttexthieght=0;
  $counter=0;
 }

 if ($y >= 701) {
  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $x=0;
  $y=0;
  $ttexthieght=0;
  $counter=0;

 }

}

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = PDF_get_buffer($pdf);
pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","Labels.pdf","inline");

?>