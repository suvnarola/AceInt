<?

include("global.php");

$buffer = conversiondocs($_GET[memid],$_GET[amount],$_GET[tradeorg],$_GET[conversionfee],$_GET[receivedfrom],$_REQUEST['otherDate']);

send_to_browser($buffer,"application/pdf","ConversionDocs.pdf","inline");

function conversiondocs($run_memid,$amount,$type,$conversionfee,$receivedfrom,$otherDate = false) {

 global $pdf, $font, $row, $date2, $linkid, $db;

 if($otherDate) {

	$newdate2 = $otherDate;

 } else {

	$newdate2 = date("d-m-Y", mktime(0,0,0,date("m"),date("d"),date("Y")));

 }


 $newdate1 = date("my", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $newamount = number_format($amount,2);

 $query = dbRead("select * from countrydata, members, country where members.CID=country.countryID and countrydata.CID=members.CID and members.memid = '$run_memid' order by companyname");

 if(@mysql_num_rows($query) != 0) {

 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
 pdf_open_file($pdf, '');
 pdf_set_info($pdf, "Author","E Banc Trade");
 pdf_set_info($pdf, "Title","Tax Invoice");
 pdf_set_info($pdf, "Creator", "E Banc Accounts");
 pdf_set_info($pdf, "Subject", "Tax Invoice");
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
 pdf_set_parameter($pdf, "textformat", "utf8");

 //check to see if there is any data if not then return nothing.
 //loop around
 while($row = mysql_fetch_array($query)) {

  $invno = $newdate1.$row[memid];
  pdf_begin_page($pdf, 595, 842);

  //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg");
  $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg", '');

  //put image up the top.
  //pdf_place_image($pdf, $pdfimage, 51.5, 753.5, 1);
  pdf_fit_image($pdf, $pdfimage, 51.5, 753.5, "scale 1");
  address();
  footer();

  //top and bottom thin lines
  pdf_moveto($pdf, 30, 600-$offset);
  pdf_lineto($pdf, 565, 600-$offset);
  pdf_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1, 1);
  pdf_setfont($pdf, $font, 18);
  pdf_set_text_pos($pdf, get_left_pos($row[receipt], $pdf, "297.5", 18, $font), 627);
  pdf_continue_text($pdf, $row[receipt]);

  $accountdetails1="$row[acno]:\n\n$row[tdate]:\n\nReceipt Number:";
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, get_right_pos($row[acno].":", $pdf, "435", 12, $font), 727);
  pdf_continue_text($pdf, $row[acno].":");
  pdf_set_text_pos($pdf, get_right_pos($row[re_date].":", $pdf, "435", 12, $font), 703);
  pdf_continue_text($pdf, $row[re_date].":");
  pdf_set_text_pos($pdf, get_right_pos($row[re_no].":", $pdf, "435", 12, $font), 679);
  pdf_continue_text($pdf, $row[re_no].":");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 12);
  $accountdetails="$row[memid]\n\n$newdate2\n\n$invno";

  pdf_set_text_pos($pdf, 440, 727);
  pdf_continue_text($pdf, $row[memid]);
  pdf_continue_text($pdf, " ");
  pdf_continue_text($pdf, $newdate2);
  pdf_continue_text($pdf, " ");
  pdf_continue_text($pdf, $invno);
  pdf_continue_text($pdf, " ");

  pdf_set_text_pos($pdf, 120, 520);
  pdf_continue_text($pdf, $row[re_from].": $receivedfrom");
  pdf_set_text_pos($pdf, 120, 490);
  pdf_continue_text($pdf, $row[re_sum].": $row[currency]$newamount $type");
  pdf_set_text_pos($pdf, 120, 460);

  $Newitt = explode("|", wordwrap($row[re_to]." ".$row[currency]."".$newamount, 50, "|"));
  foreach($Newitt as $Line) {
    pdf_continue_text($pdf, $Line);
    $pos = $pos - 12;
  }

  //pdf_continue_text($pdf, $row[re_to]." row[currency]$newamount");

  //pdf_set_text_pos($pdf, 120, 430);
  //pdf_continue_text($pdf, "By way of Trade");

  pdf_end_page($pdf);

 if($conversionfee > 0) {

  pdf_begin_page($pdf, 595, 842);

  address();
  $fee = $conversionfee;

  //put image up the top.
  //pdf_place_image($pdf, $pdfimage, 51.5, 753.5, 1);
  pdf_fit_image($pdf, $pdfimage, 51.5, 753.5, "scale 1");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1, 1);
  pdf_setfont($pdf, $font, 18);
  pdf_set_text_pos($pdf, get_left_pos($row[tname], $pdf, "396", 18, $font), 800);
  pdf_continue_text($pdf, $row[tname]);

  $accountdetails1="$row[acno]:\n\n$row[tdate]:\n\n$row[tno]:";
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, 285, 727);
  pdf_continue_text($pdf, $row[acno].":");
  pdf_continue_text($pdf, " ");
  pdf_continue_text($pdf, $row[tdate].":");
  pdf_continue_text($pdf, " ");
  pdf_continue_text($pdf, $row[tno].":");

  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, get_right_pos($row[tsub].":", $pdf, "440", 10, $font), 480);
  pdf_continue_text($pdf, "$row[tsub]:");

  if($row[tax] != 0) {
   pdf_set_text_pos($pdf, get_right_pos($row[tgst].":", $pdf, "440", 10, $font), 455);
   pdf_continue_text($pdf, "$row[tgst]:");
  }

  pdf_set_text_pos($pdf, get_right_pos("$row[ttot]:", $pdf, "440", 10, $font), 430);
  pdf_continue_text($pdf, "$row[ttot]:");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 16);
  pdf_set_text_pos($pdf, get_left_pos($row[companyname], $pdf, "396", 16, $font), 780);
  pdf_continue_text($pdf, $row[companyname]);
  pdf_setfont($pdf, $font, 12);
  $accountdetails="$row[memid]\n\n$newdate2\n\n$invno";
  pdf_set_text_pos($pdf, 440, 727);
  pdf_continue_text($pdf, $row[memid]);
  pdf_continue_text($pdf, " ");
  pdf_continue_text($pdf, $newdate2);
  pdf_continue_text($pdf, " ");
  pdf_continue_text($pdf, $invno);

  $gst=(($fee/(100+$row[tax]))*$row[tax]);
  $nett=$fee-$gst;
  $total=number_format($fee,2);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 120, 551);
  pdf_continue_text($pdf, "Conversion");
  pdf_set_text_pos($pdf, get_right_pos(number_format($nett,2), $pdf, "500", 10, $font), 551);
  pdf_continue_text($pdf, number_format($nett,2));
  pdf_set_text_pos($pdf, get_right_pos(number_format($nett,2), $pdf, "500", 10, $font), 480);
  pdf_continue_text($pdf, number_format($nett,2));

  if($row[tax] != 0) {
   pdf_set_text_pos($pdf, get_right_pos(number_format($gst,2), $pdf, "500", 10, $font), 455);
   pdf_continue_text($pdf, number_format($gst,2));
  }

  pdf_set_text_pos($pdf, get_right_pos($total, $pdf, "500", 10, $font), 430);
  pdf_continue_text($pdf, $total);

  //lines and boxes
  pdf_rect($pdf, 65, 488, 448, 100);
  pdf_closepath_stroke($pdf);

  pdf_rect($pdf, 280, 461, 233, 27);
  pdf_closepath_stroke($pdf);

  pdf_rect($pdf, 280, 436, 233, 25);
  pdf_closepath_stroke($pdf);

  pdf_rect($pdf, 280, 411, 233, 25);
  pdf_closepath_stroke($pdf);

  footer();

  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, get_left_pos($row[trem], $pdf,"555", 12, $font), 100);
  pdf_continue_text($pdf, $row[trem]);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, get_right_pos("$row[comna]:", $pdf,"145", 10, $font), 75);
  pdf_continue_text($pdf, "$row[comna]:");
  pdf_set_text_pos($pdf, get_right_pos("$row[acno]:", $pdf,"145", 10, $font), 35);
  pdf_continue_text($pdf, "$row[acno]:");
  pdf_set_text_pos($pdf, get_right_pos("$row[tnow]:", $pdf,"470", 10, $font), 75);
  pdf_continue_text($pdf, "$row[tnow]:");
  pdf_set_text_pos($pdf, get_right_pos("$row[tampa]:", $pdf,"470", 10, $font), 35);
  pdf_continue_text($pdf, "$row[tampa]:");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 150, 75);
  pdf_continue_text($pdf, $row[companyname]);
  pdf_set_text_pos($pdf, 150, 35);
  pdf_continue_text($pdf, $row[memid]);
  pdf_set_text_pos($pdf, 475, 75);
  pdf_continue_text($pdf, $total);

  pdf_end_page($pdf);

  //close it up
  pdf_close($pdf);
  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);

 } else {

 //close it up
 pdf_close($pdf);
 $buffer = PDF_get_buffer($pdf);
 pdf_delete($pdf);

 }
 }
 return $buffer;

} else {

 $buffer="none";
 return $buffer;

}

}

function footer()  {

  global $pdf, $font, $row;

  pdf_moveto($pdf, 30, 210-$offset);
  pdf_lineto($pdf, 565, 210-$offset);
  pdf_stroke($pdf);

  pdf_setfont($pdf, $font, 9);
  pdf_set_text_pos($pdf, get_left_pos($row['auth'], $pdf, "297.5", 9, $font), 205);
  pdf_continue_text($pdf, $row['auth']);

  pdf_setcolor($pdf, "fill", "rgb", 1, 0.502, 0, 0);
  pdf_setfont($pdf, $font, 12);

  $pos = 185;

  $NewITT = explode("|", wordwrap($row['itt'], 60, "|"));
  foreach($NewITT as $Line) {
   pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297.5", 12, $font), $pos);
   pdf_continue_text($pdf, $Line);
   $pos = $pos - 12;
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 9);
  pdf_set_text_pos($pdf, get_left_pos($row['abn'], $pdf, "297.5", 9, $font), 150);
  pdf_continue_text($pdf, $row['abn']);

  pdf_set_text_pos($pdf, 40, 133);

  $text = explode(",",$row['address2'],2);
  foreach($text as $Line) {
    pdf_continue_text($pdf, trim($Line));
    $textheight += 9;
  }

  pdf_set_text_pos($pdf, get_left_pos("Tel: ".$row['phone'], $pdf, "297.5", 9, $font), 133);
  pdf_continue_text($pdf, "Tel: ".$row['phone']);
  pdf_set_text_pos($pdf, get_left_pos("Fax: ".$row['fax'], $pdf, "297.5", 9, $font), 124);
  pdf_continue_text($pdf, "Fax: ".$row['fax']);
  pdf_set_text_pos($pdf, get_right_pos("Email: ".$row['email'], $pdf, "553", 9, $font), 133);
  pdf_continue_text($pdf, "Email: ".$row['email']);

  if($row['CID'] == 1) {
    $ww = "http://www.empireXchange.com";
  } else {
    $ww = "http://www.ebanctrade.com";
  }

  pdf_set_text_pos($pdf, get_right_pos($ww, $pdf, "553", 9, $font), 124);
  pdf_continue_text($pdf, $ww);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1, 1);
  pdf_moveto($pdf, 30, 105-$offset);
  pdf_lineto($pdf, 565, 105-$offset);
  pdf_stroke($pdf);
}

function address()  {

  global $pdf, $font, $row;

  if($row[postalsuburb]) {
   $suburb="$row[postalsuburb] ";
  }else{
  unset($suburb);
  }

  if($row[postalno]) {
   $streetno="$row[postalno] ";
  }else{
  unset($streetno);
  }

  $addressbox="$row[contactname]\n$row[companyname]\n$streetno$row[postalname]\n$suburb$row[postalcity]\n$row[postalstate]    $row[postalpostcode]";

  //address box
  pdf_rect($pdf, 65, 650, 180, 81);
  pdf_closepath_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 75, 726);

  pdf_continue_text($pdf, $row['contactname']);
  pdf_continue_text($pdf, $row['companyname']);
  pdf_continue_text($pdf, "".$streetno."".$row['postalname']);
  pdf_continue_text($pdf, "".$suburb."".$row['postalcity']);
  pdf_continue_text($pdf, $row['postalstate']."    ".$row['postalpostcode']);

  //top and bottom thin lines
  pdf_moveto($pdf, 30, 630-$offset);
  pdf_lineto($pdf, 565, 630-$offset);
  pdf_stroke($pdf);
}

?>