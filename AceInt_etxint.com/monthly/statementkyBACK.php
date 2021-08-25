<?

 /**
  * Statement Print
  */

 include("../includes/global.php");
 $date2 = date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")));

 $query = dbRead("select * from status, invoice, members, area, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.area=area.FieldID and (members.CID = countrydata.CID) and members.CID='".$_SESSION['User']['CID']."' and members.monthlyfeecash� > '0' and invoice.date like '".date("Y-m", mktime(0,0,0,date("m")-1,1-1,date("Y")))."-%' and members.datejoined < '#".date("Y-m", mktime(0,0,0,date("m"),1-1,date("Y")))."-01#' and (status.mem_send_inv = 1) order by companyname");

 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 pdf_open_file($pdf);
 pdf_set_info($pdf, "Author","RDI Host");
 pdf_set_info($pdf, "Title","Invoice 1");
 pdf_set_info($pdf, "Creator", "Antony Puckey");
 pdf_set_info($pdf, "Subject", "Hosting Invoice");
 pdf_set_value($pdf, compress, 9);
 pdf_begin_page($pdf, 595, 842);
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");

 while($row = mysql_fetch_assoc($query)) {

  $counter4 = 0;
  $offset3 = 0;
  $tradetotal = 0;
  $cashtotal = 0;
  $tbuy = 0;
  $tsell = 0;

  templ();

  $query3 = dbRead("select transactions.*, members.*, tbl_members_companyinfo.Companyname as TOCompanyname from transactions, members left outer join tbl_members_companyinfo on (transactions.to_memid = tbl_members_companyinfo.memid AND transactions.dis_date BETWEEN tbl_members_companyinfo.datefrom AND tbl_members_companyinfo.dateto) where transactions.to_memid = members.memid and transactions.memid = $row[memid] and dis_date between '#$date2-01#' and '#$date2-31#' order by dis_date");
  $query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees from transactions where memid = $row[memid] and dis_date < '#$date2-01#' group by memid");
  $row4 = mysql_fetch_assoc($query4);

  $newdate = explode("-", $row[date]);
  $newdate3 = date("d/m/Y", mktime(0,0,0,$newdate[1],01,$newdate[0]));

  $box1=$newdate3;

  $box2="Opening Balance";

  $box3="0.00";

  $box4=number_format($row4[optrade],2);

  $tradetotal+=$row4[optrade];

  $box41=number_format($tradetotal,2);

  $box5="$row4[opfees]";

  $cashtotal=($cashtotal + $row4[opfees]);

  $box6=number_format($cashtotal,2);

  $tsell = $row4[optrade];

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 8);

  pdf_set_text_pos($pdf, 30, 595);
  pdf_continue_text($pdf, $box1);
  //pdf_show_boxed($pdf, $box1, 30, 585, 60, 10, "left");

  pdf_set_text_pos($pdf, 100, 595);
  pdf_continue_text($pdf, $box2);
  //pdf_show_boxed($pdf, $box2, 100, 585, 185, 10, "left");

  pdf_set_text_pos($pdf, get_right_pos($box3, $pdf, "342"), 595);
  pdf_continue_text($pdf, $box3);
  //pdf_show_boxed($pdf, $box3, 285, 585, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($box4, $pdf, "402"), 595);
  pdf_continue_text($pdf, $box4);
  //pdf_show_boxed($pdf, $box4, 345, 585, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462"), 595);
  pdf_continue_text($pdf, $box41);
  //pdf_show_boxed($pdf, $box41, 405, 585, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "512"), 595);
  pdf_continue_text($pdf, $box5);
  //pdf_show_boxed($pdf, $box5, 469, 585, 43, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560"), 595);
  pdf_continue_text($pdf, $box6);
  //pdf_show_boxed($pdf, $box6, 515, 585, 45, 10, "right");

  //3 top lines in boxes
  pdf_setlinewidth($pdf, 1.5);
  pdf_moveto($pdf, 30, 580);
  pdf_lineto($pdf, 560, 580);
  pdf_stroke($pdf);

  $offset3+=20;

  #loop around
  while($row3 = mysql_fetch_array($query3)) {

   $Stringwidth = pdf_stringwidth($pdf, $row3[details], $font, 6);

   $texthieght = (((ceil($Stringwidth/220))*6)+6);
   $counter4 = $offset3 + $texthieght;

   if($counter4 > 490) {
    pdf_end_page($pdf);
    pdf_begin_page($pdf, 595, 842);
    $offset3 = 0;

    templ();
   }

   $newdate=explode("-", $row3[dis_date]);
   $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0]));

   $box1="$newdate2";

   $box2=$row3[$row3[displayname]];

   if($row3['TOCompanyname']) {

    $box2=$row3['TOCompanyname'];

   }

   $box3=number_format($row3[buy],2);

   $box4=number_format($row3[sell],2);

   if($row3[buy] == "0") {
    $tradetotal+=$row3[sell];
   } else {
    $tradetotal-=$row3[buy];
   }

   $box41=number_format($tradetotal,2);

   $box5="$row3[dollarfees]";

   $cashtotal=($cashtotal + $row3[dollarfees]);

   $box6=number_format($cashtotal,2);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   $font = pdf_findfont($pdf, "Verdana", "host", 0);
   pdf_setfont($pdf, $font, 8);

   pdf_set_text_pos($pdf, 30, 595-$offset3);
   pdf_continue_text($pdf, $box1);
   //pdf_show_boxed($pdf, $box1, 30, 585-$offset3, 60, 10, "left");

   pdf_set_text_pos($pdf, 100, 595-$offset3);
   pdf_continue_text($pdf, $box2);
   //pdf_show_boxed($pdf, $box2, 100, 585-$offset3, 185, 10, "left");

   pdf_set_text_pos($pdf, get_right_pos($box3, $pdf, "342"), 595-$offset3);
   pdf_continue_text($pdf, $box3);
   //pdf_show_boxed($pdf, $box3, 285, 585-$offset3, 57, 10, "right");

   pdf_set_text_pos($pdf, get_right_pos($box4, $pdf, "402"), 595-$offset3);
   pdf_continue_text($pdf, $box4);
   //pdf_show_boxed($pdf, $box4, 345, 585-$offset3, 57, 10, "right");

   pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462"), 595-$offset3);
   pdf_continue_text($pdf, $box41);
   //pdf_show_boxed($pdf, $box41, 405, 585-$offset3, 57, 10, "right");

   pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "512"), 595-$offset3);
   pdf_continue_text($pdf, $box5);
   //pdf_show_boxed($pdf, $box5, 469, 585-$offset3, 43, 10, "right");

   pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560"), 595-$offset3);
   pdf_continue_text($pdf, $box6);
   //pdf_show_boxed($pdf, $box6, 515, 585-$offset3, 45, 10, "right");

   $offset3 = $offset3 + $texthieght;

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   $font = pdf_findfont($pdf, "Verdana", "host", 0);
   pdf_setfont($pdf, $font, 6);
   //pdf_show_boxed($pdf, $row3[details], 100, 585-$offset3, 220, $texthieght, "left");

   pdf_set_text_pos($pdf, 100, 595-$offset3);
   $Newtrequ = explode("|", wordwrap($row3[details], 92, "|"));
   foreach($Newtrequ as $Line) {
    pdf_continue_text($pdf, $Line);
   }

   $tsell = $tsell + $box4;
   $tbuy = $tbuy + $box3;

   //3 top lines in boxes
   pdf_setlinewidth($pdf, 1.5);
   pdf_moveto($pdf, 30, 580-$offset3);
   pdf_lineto($pdf, 560, 580-$offset3);
   pdf_stroke($pdf);

   $offset3 = $offset3 + 20;

  }

  if($offset3 > 490) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $offset3=0;

   templ();
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 8);

  pdf_set_text_pos($pdf, get_right_pos("TOTALS:", $pdf, "290"), 595-$offset3);
  pdf_continue_text($pdf, "TOTALS:");
  //pdf_show_boxed($pdf, "TOTALS:", 250, 585-$offset3, 40, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos(number_format($tbuy,2), $pdf, "342"), 595-$offset3);
  pdf_continue_text($pdf, number_format($tbuy,2));
  //pdf_show_boxed($pdf, number_format($tbuy,2), 285, 585-$offset3, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos(number_format($tsell,2), $pdf, "402"), 595-$offset3);
  pdf_continue_text($pdf, number_format($tsell,2));
  //pdf_show_boxed($pdf, number_format($tsell,2), 345, 585-$offset3, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462"), 595-$offset3);
  pdf_continue_text($pdf, $box41);
  //pdf_show_boxed($pdf, $box41, 405, 585-$offset3, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560"), 595-$offset3);
  pdf_continue_text($pdf, $box6);
  //pdf_show_boxed($pdf, $box6, 515, 585-$offset3, 45, 10, "right");

  $offset3 = $offset3 + 90;

  if($offset3 > 485) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $offset3=0;

   templ();
  }


  $cash=$cashtotal;

  if($cash < 0) {
   $feesdetails = "Prepaid";
   $cash=($cash*-1);
  } else {
   $feesdetails = "Now Due";
   $cash=$cash;
  }

  //$box7="Current Facility:\n\nReal Estate Facility:\n\nNett Position:\n\nCash Fees $feesdetails:";

  //$box7="Current Facility:";
  $box7 = get_word("53").":";
  $box71= get_word("54").":";
  $box72= get_word("55").":";
  $box73= get_word("46")." $feesdetails:";

  $nett=($tradetotal - $row[overdraft] - $row[reoverdraft]);

  $facility=number_format($row[overdraft],2);
  $refacility=number_format($row[reoverdraft],2);
  $nett=number_format($nett,2);
  $cash=number_format($cash,2);

  //$box8="\$$facility\n\n$$refacility\n\n\$$nett\n\n\$$cash";
  $box8="\$$facility";
  $box9="\$$refacility";
  $box10="\$$nett";
  $box11="\$$cash";

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 12);

  pdf_set_text_pos($pdf, get_right_pos($box7, $pdf, "300"), 553-$offset3);
  pdf_continue_text($pdf, $box7);
  pdf_set_text_pos($pdf, get_right_pos($box71, $pdf, "300"), 531-$offset3);
  pdf_continue_text($pdf, $box71);
  pdf_set_text_pos($pdf, get_right_pos($box72, $pdf, "300"), 509-$offset3);
  pdf_continue_text($pdf, $box72);
  pdf_set_text_pos($pdf, get_right_pos($box73, $pdf, "300"), 487-$offset3);
  pdf_continue_text($pdf, $box73);
  //pdf_show_boxed($pdf, $box7, 150, 475-$offset3, 150, 85, "right");


  pdf_set_text_pos($pdf, get_right_pos($box8, $pdf, "400"), 553-$offset3);
  pdf_continue_text($pdf, $box8);
  pdf_set_text_pos($pdf, get_right_pos($box9, $pdf, "400"), 531-$offset3);
  pdf_continue_text($pdf, $box9);
  pdf_set_text_pos($pdf, get_right_pos($box10, $pdf, "400"), 509-$offset3);
  pdf_continue_text($pdf, $box10);
  pdf_set_text_pos($pdf, get_right_pos($box11, $pdf, "400"), 487-$offset3);
  pdf_continue_text($pdf, $box11);
  //pdf_show_boxed($pdf, $box8, 305, 475-$offset3, 95, 85, "right");

  pdf_show_boxed($pdf, $opening, 305, 455-$offset3, 65, 20, "right");

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);

  $offset3 = 0;
  $tradetotal = 0;
  $cashtotal = 0;

 }

 //close it up
 pdf_end_page($pdf);
 pdf_close($pdf);
 $buffer = pdf_get_buffer($pdf);
 pdf_delete($pdf);

 send_to_browser($buffer,"application/pdf","StatementRun.pdf","attachment");

 function templ() {

  global $pdf, $font, $row;

  if($row[postalsuburb]) {
   $suburb="$row[postalsuburb]\n";
  } else {
   unset($suburb);
  }

  if($row[postalno]) {
   $streetno="$row[postalno] ";
  } else {
   unset($streetno);
  }

  $last="$row[postalcity]  $row[postalstate]  $row[postalpostcode]";
  $city=strtoupper($last);

  $addressbox="$row[contactname]\n$row[companyname]\n$streetno$row[postalname]\n$suburb$city";

  $newdate=explode("-", $row[date]);
  $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0]));

 //$accountdetails="$row[memid]\n\n$newdate2";

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 14);
  //pdf_show_boxed($pdf, $row[companyname], 212, 765, 368, 20, "center");
   //pdf_set_text_pos($pdf, get_right_pos($row[companyname], $pdf, "530"), 775);
   //pdf_continue_text($pdf, get_left_pos($row[companyname], $pdf, "70"), 775);
   //pdf_set_text_pos($pdf, 70, 775);
   //pdf_continue_text($pdf, $row[companyname]);

  //pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  //$font = pdf_findfont($pdf, "Verdana", "host", 0);
  //pdf_setfont($pdf, $font, 10);
  //pdf_show_boxed($pdf, $addressbox, 90, 650, 175, 71, "left");




 pdf_set_text_pos($pdf, 70, 775);
 pdf_continue_text($pdf, $row[companyname]);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 75, 726);

 if(($row['accholder'] != $row['companyname']) && $row['accholder']) {
  pdf_continue_text($pdf, $row['accholder']);
 }
  pdf_continue_text($pdf, $row['companyname']);
  pdf_continue_text($pdf, "".$streetno."".$row['postalname']);
  if($suburb)  {
    pdf_continue_text($pdf, $suburb);
  }
  $add = "".$row['postalcity']."  ".$row['postalstate']."  ".$row['postalpostcode']."";
  pdf_continue_text($pdf, $add);

 pdf_setfont($pdf, $font, 12);
 pdf_set_text_pos($pdf, 450, 720);
 pdf_continue_text($pdf, $row['memid']);
 pdf_continue_text($pdf, " ");
 pdf_continue_text($pdf, $newdate2);
 pdf_continue_text($pdf, " ");
 pdf_continue_text($pdf, $row[phone]);


  //pdf_setfont($pdf, $font, 12);
  //pdf_show_boxed($pdf, $accountdetails, 460, 675, 85, 40, "left");
  //pdf_show_boxed($pdf, "Your Local Office: $row[phone]", 300, 650, 250, 15, "right");

  //if($abc) {

   $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/ebt-bw.jpg");
   pdf_place_image($pdf, $pdfimage, 445, 755, 1);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
   pdf_setfont($pdf, $font, 18);
   pdf_set_text_pos($pdf, 70, 800);
   pdf_continue_text($pdf, "Statement");


   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
   pdf_setfont($pdf, $font, 18);
   pdf_set_text_pos($pdf, get_right_pos($row['sname'], $pdf, "530"), 800);
   //pdf_continue_text($pdf, $row['acno']);
   pdf_setfont($pdf, $font, 12);
   pdf_set_text_pos($pdf, get_right_pos($row['acno'].":", $pdf, "430"), 718);
   pdf_continue_text($pdf, $row['acno'].":");
   pdf_set_text_pos($pdf, get_right_pos($row['sdate'].":", $pdf, "430"), 692);
   pdf_continue_text($pdf, $row['sdate'].":");
   pdf_set_text_pos($pdf, get_right_pos($row['locoff'].":", $pdf, "430"), 668);
   pdf_continue_text($pdf, $row['locoff'].":");

  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 30, 620);
  pdf_continue_text($pdf, $row['stdate']);
  //pdf_show_boxed($pdf, $row['locoff'], 30, 610, 60, 10, "left");

  pdf_set_text_pos($pdf, 100, 620);
  pdf_continue_text($pdf, "Account");
  //pdf_show_boxed($pdf, "Account", 100, 610, 185, 10, "left");

  pdf_set_text_pos($pdf, get_right_pos($row['sbuy'], $pdf, "343"), 620);
  pdf_continue_text($pdf, $row['sbuy']);
  //pdf_show_boxed($pdf, "buy", 286, 610, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos($row['ssell'], $pdf, "402"), 620);
  pdf_continue_text($pdf, $row['ssell']);
  //pdf_show_boxed($pdf, "sell", 345, 610, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos("balance", $pdf, "462"), 620);
  pdf_continue_text($pdf, "balance");
  //pdf_show_boxed($pdf, "balance", 405, 610, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos("fees", $pdf, "512"), 620);
  pdf_continue_text($pdf, "fees");
  //pdf_show_boxed($pdf, "fees", 469, 610, 43, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos("balance", $pdf, "560"), 620);
  pdf_continue_text($pdf, "balance");
  //pdf_show_boxed($pdf, "balance", 515, 610, 45, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos("trade", $pdf, "462"), 630);
  pdf_continue_text($pdf, "trade");
  //pdf_show_boxed($pdf, "trade", 405, 620, 57, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos("cash", $pdf, "512"), 630);
  pdf_continue_text($pdf, "cash");
  //pdf_show_boxed($pdf, "cash", 469, 620, 43, 10, "right");

  pdf_set_text_pos($pdf, get_right_pos("cash", $pdf, "560"), 630);
  pdf_continue_text($pdf, "cash");
  //pdf_show_boxed($pdf, "cash", 515, 620, 45, 10, "right");

  pdf_setlinewidth($pdf, 1.5);
  pdf_moveto($pdf, 30, 600);
  pdf_lineto($pdf, 560, 600);
  pdf_stroke($pdf);
  //}

 }
