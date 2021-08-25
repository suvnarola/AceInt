<?

 /**
  * Statement Print
  */

function statement($query, $printStationery = false, $individual = false, $send = false, $senddate = false, $nomonths = false) {

 global $pdf, $font, $row;

 $date2 = ($senddate) ? $senddate : date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")));

 if($nomonths) {
  $newdate1 = explode("-", $senddate);
  $date1 = date("Y-m", mktime(0,0,0,$newdate1[1]-$nomonths,01,$newdate1[0]));
 } else {
  $date1 = $date2;
 }
 //$query = dbRead("select * from status, invoice, members, area, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.area=area.FieldID and (members.CID = countrydata.CID) and members.CID='".$_SESSION['User']['CID']."' and members.monthlyfeecash� > '0' and invoice.date like '".date("Y-m", mktime(0,0,0,date("m")-1,1-1,date("Y")))."-%' and members.datejoined < '#".date("Y-m", mktime(0,0,0,date("m"),1-1,date("Y")))."-01#' and (status.mem_send_inv = 1) order by companyname");

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

 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
 pdf_set_parameter($pdf, "textformat", "utf8");

 while($row = mysql_fetch_assoc($query)) {

 if($row['CID'] == 155) {


 } else {


  pdf_begin_page($pdf, 595, 842);

  $counter4 = 0;
  $offset3 = 0;
  $tradetotal = 0;
  $cashtotal = 0;
  $tbuy = 0;
  $tsell = 0;

  Stationery($printStationery);

  //$query3 = dbRead("select transactions.*, members.*, tbl_members_companyinfo.Companyname as TOCompanyname from transactions, members left outer join tbl_members_companyinfo on (transactions.to_memid = tbl_members_companyinfo.memid AND transactions.dis_date BETWEEN tbl_members_companyinfo.datefrom AND tbl_members_companyinfo.dateto) where transactions.to_memid = members.memid and transactions.memid = $row[memid] and dis_date between '#$date1-01#' and '#$date2-31#' order by dis_date");
  $query3 = dbRead("
	select transactions.*, members.*, tbl_members_companyinfo.Companyname as TOCompanyname
	from transactions

		inner
			join
				members
				on (transactions.to_memid = members.memid)

		left outer join tbl_members_companyinfo on (transactions.to_memid = tbl_members_companyinfo.memid AND transactions.dis_date BETWEEN tbl_members_companyinfo.datefrom AND tbl_members_companyinfo.dateto)

		where
			transactions.memid = ".$row[memid]." and dis_date between '".$date1."-01#' and '".$date2."-31'

		order by dis_date
	");


  //$query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees from transactions where memid = $row[memid] and dis_date < '#$date2-01#' group by memid");
  //$query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees from transactions where memid = $row[memid] and dis_date < '#$date1-01#' group by memid");
  $query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees from transactions where memid = ".$row[memid]." and dis_date < '$date1-01' group by memid");
  $row4 = mysql_fetch_assoc($query4);

  //$newdate = explode("-", $row[date]);
  $newdate = explode("-", $date1);
  $newdate3 = date("d/m/Y", mktime(0,0,0,$newdate[1],01,$newdate[0]));

  $box1 = $newdate3;
  $box2 = $row['sob'];
  $box3 = "0.00";
  $box4=number_format($row4[optrade],2);
  $tradetotal+=$row4[optrade];
  $box41=number_format($tradetotal,2);
  $box5="$row4[opfees]";
  $cashtotal=($cashtotal + $row4[opfees]);
  $box6=number_format($cashtotal,2);
  $tsell = $row4[optrade];

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 8);

  pdf_set_text_pos($pdf, 35, 550);
  pdf_continue_text($pdf, $box1);

  pdf_set_text_pos($pdf, 100, 550);
  pdf_continue_text($pdf, $box2);

  pdf_set_text_pos($pdf, get_right_pos($box3, $pdf, "342", 8, $font), 550);
  pdf_continue_text($pdf, $box3);

  pdf_set_text_pos($pdf, get_right_pos($box4, $pdf, "402", 8, $font), 550);
  pdf_continue_text($pdf, $box4);

  pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462", 8, $font), 550);
  pdf_continue_text($pdf, $box41);

  pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "512", 8, $font), 550);
  pdf_continue_text($pdf, $box5);

  pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560", 8, $font), 550);
  pdf_continue_text($pdf, $box6);

  //3 top lines in boxes
  pdf_setlinewidth($pdf, 1.5);
  pdf_moveto($pdf, 35, 535);
  pdf_lineto($pdf, 560, 535);
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

    Stationery($printStationery);
   }

   $newdate=explode("-", $row3[dis_date]);
   $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0]));

   $box1="$newdate2";
   $box2=$row3[$row3[displayname]];

   if($row3['TOCompanyname']) {

    $box2=$row3['TOCompanyname'];

   }

   if($row3['checked'] == 0)  {
    $box33 = "C";
   } else {
    $box33 = "U";
   }

   $box3=$row3[buy];

   $box4=$row3[sell];

   if($row3[buy] == "0") {
    $tradetotal+=$row3[sell];
   } else {
    $tradetotal-=$row3[buy];
   }

   $box41=number_format($tradetotal,2);
   $box5="$row3[dollarfees]";
   $cashtotal=($cashtotal + $row3[dollarfees]);
   $box6=number_format($cashtotal,2);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
   pdf_setfont($pdf, $font, 8);

   pdf_set_text_pos($pdf, 35, 550-$offset3);
   pdf_continue_text($pdf, $box1);

   pdf_set_text_pos($pdf, 100, 550-$offset3);
   pdf_continue_text($pdf, $box2);

   pdf_set_text_pos($pdf, get_right_pos($box33, $pdf, "286", 8, $font), 550-$offset3);
   pdf_continue_text($pdf, $box33);

   pdf_set_text_pos($pdf, get_right_pos(number_format($box3,2), $pdf, "342", 8, $font), 550-$offset3);
   pdf_continue_text($pdf, number_format($box3,2));

   pdf_set_text_pos($pdf, get_right_pos(number_format($box4,2), $pdf, "402", 8, $font), 550-$offset3);
   pdf_continue_text($pdf, number_format($box4,2));

   pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462", 8, $font), 550-$offset3);
   pdf_continue_text($pdf, $box41);

   pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "512", 8, $font), 550-$offset3);
   pdf_continue_text($pdf, $box5);

   pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560", 8, $font), 550-$offset3);
   pdf_continue_text($pdf, $box6);

   $offset3 = $offset3 + $texthieght;

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   $font = pdf_findfont($pdf, "Verdana", "winansi", 0);

   if($row3[chq_no]) {
     $dd = $row3[details]."Chq No: ".$row3[chq_no];
   } else {
     $dd = $row3[details];
   }
   pdf_setfont($pdf, $font, 6);
   pdf_set_text_pos($pdf, 100, 550-$offset3);
   $Newtrequ = explode("|", wordwrap($dd, 92, "|"));
   foreach($Newtrequ as $Line) {
    pdf_continue_text($pdf, $Line);
   }

   $tsell = $tsell + $box4;
   $tbuy = $tbuy + $box3;

   //3 top lines in boxes
   pdf_setlinewidth($pdf, 1.5);
   pdf_moveto($pdf, 35, 535-$offset3);
   pdf_lineto($pdf, 560, 535-$offset3);
   pdf_stroke($pdf);

   $offset3 = $offset3 + 20;

  }

  if($offset3 > 490) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $offset3=0;

   Stationery($printStationery);
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 8);

  pdf_set_text_pos($pdf, get_right_pos($row['sto'].":", $pdf, "290", 8, $font), 555-$offset3);
  pdf_continue_text($pdf, $row['sto'].":");

  pdf_set_text_pos($pdf, get_right_pos(number_format($tbuy,2), $pdf, "342", 8, $font), 555-$offset3);
  pdf_continue_text($pdf, number_format($tbuy,2));

  pdf_set_text_pos($pdf, get_right_pos(number_format($tsell,2), $pdf, "402", 8, $font), 555-$offset3);
  pdf_continue_text($pdf, number_format($tsell,2));

  pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462", 8, $font), 555-$offset3);
  pdf_continue_text($pdf, $box41);

  pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560", 8, $font), 555-$offset3);
  pdf_continue_text($pdf, $box6);

  if($row['CID'] == 12 || $row['CID'] == 15) {

	  $offset3 = $offset3 + 40;
	  $offset4 = $offset3 + 40;

  } else {

	  $offset3 = $offset3 + 40;

  }

  if($offset4 > 485) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $offset3=0;

   Stationery($printStationery);
  }

  $cash=$cashtotal;

  if($cash < 0) {
   $feesdetails = $row['spai'];
   $cash=($cash*-1);
  } else {
   $feesdetails = $row['sdue'];
   $cash=$cash;
  }

  $box7 = $row['sfac'].":";
  $box71= $row['srfac'].":";
  $box72= $row['snett'].":";
  $box73= $row['scas']." $feesdetails:";

  $nett=($tradetotal - $row[overdraft] - $row[reoverdraft]);

  $facility=number_format($row[overdraft],2);
  $refacility=number_format($row[reoverdraft],2);
  $nett=number_format($nett,2);
  $cash=number_format($cash,2);

  //$box8= $row['currency']."$facility";
  //$box9= $row['currency']."$refacility";
  //$box10= $row['currency']."$nett";
  //$box11= $row['currency']."$cash";

  $box8= "T".$row['currency']."".$facility;
  $box9= "T".$row['currency']."".$refacility;
  $box10= "T".$row['currency']."".$nett;
  $box11= $row['currency']."".$cash;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 12);

  pdf_set_text_pos($pdf, get_right_pos($box7, $pdf, "300", 12, $font), 568-$offset3);
  pdf_continue_text($pdf, $box7);
  pdf_set_text_pos($pdf, get_right_pos($box71, $pdf, "300", 12, $font), 546-$offset3);
  pdf_continue_text($pdf, $box71);
  pdf_set_text_pos($pdf, get_right_pos($box72, $pdf, "300", 12, $font), 524-$offset3);
  pdf_continue_text($pdf, $box72);
  pdf_set_text_pos($pdf, get_right_pos($box73, $pdf, "300", 12, $font), 502-$offset3);
  pdf_continue_text($pdf, $box73);

  pdf_set_text_pos($pdf, get_right_pos($box8, $pdf, "420", 12, $font), 568-$offset3);
  pdf_continue_text($pdf, $box8);
  pdf_set_text_pos($pdf, get_right_pos($box9, $pdf, "420", 12, $font), 546-$offset3);
  pdf_continue_text($pdf, $box9);
  pdf_set_text_pos($pdf, get_right_pos($box10, $pdf, "420", 12, $font), 524-$offset3);
  pdf_continue_text($pdf, $box10);
  pdf_set_text_pos($pdf, get_right_pos($box11, $pdf, "420", 12, $font), 502-$offset3);
  pdf_continue_text($pdf, $box11);

  if($row['CID'] == 12 || $row['CID'] == 15) {

   //$offset3 = $offset3 + 90;

   //if($offset3 > 485) {
    //pdf_end_page($pdf);
    //pdf_begin_page($pdf, 595, 842);
    //$offset3=0;

    //Stationery($printStationery);
   //}

   pdf_setfont($pdf, $font, 7);
   pdf_set_text_pos($pdf, 40, 480-$offset3);
   $NewDesc = explode("\r\n", wordwrap($row['thu'], '130', "\r\n"));

   $fontBold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);

   $pdfCount = 1;

   foreach($NewDesc as $Key => $Value) {

     ob_start();
     eval(" ?>".$Value."<? ");
     $output = ob_get_contents();
	 ob_end_clean();

   	 if($pdfCount == 1 || $pdfCount == 4){

		pdf_setfont($pdf, $fontBold, 7);

   	 } elseif($pdfCount == 8 || $pdfCount == 7){

		pdf_setfont($pdf, $font, 5);

   	 } else {

		pdf_setfont($pdf, $font, 7);

   	 }

     pdf_continue_text($pdf, $output);
     $textheight += 12;

     $pdfCount++;

   }
  }

  $offset3 = 0;
  $tradetotal = 0;
  $cashtotal = 0;
 pdf_end_page($pdf);
 }
}

 //close it up
 //pdf_end_page($pdf);
 pdf_close($pdf);
 $buffer = pdf_get_buffer($pdf);
 pdf_delete($pdf);

 if($send)  {
  return $buffer;
 } else {
  send_to_browser($buffer,"application/pdf","Statement.pdf","attachment");
 }

}


 function Stationery($printStationery = false) {

  global $pdf, $font, $row;

  $blah = addresslayout($_SESSION['CountryID']['countryID']);

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

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 14);

  pdf_set_text_pos($pdf, 60, 790);
  if($row['CID'] == 12) {
	pdf_continue_text($pdf, $row['regname']);
  } else {
	pdf_continue_text($pdf, $row['companyname']);
  }

  if($row['CID'] == 12 || $row['CID'] == 15) {
   $pos2 = 265;
  } else {
   $pos2 = 0;
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 75+$pos2, 695);

  foreach($blah as $key => $value) {
    $addline = "";

    foreach($value as $key2) {
     if($row[$key2]) {
     $addline .= $row[$key2] ." ";
     }
    }

    if(trim($addline))  {

     $NewCompanyname = explode("|", wordwrap($addline, 40, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
     }
    }
  }

	 if(!$_SESSION['Country']['logo'])  {
	   $logo = $row['logo'];
	 }  else  {
	   $logo = $_SESSION['Country']['logo'];
	 }

	 if($logo == "ept")  {
	   $web = "www.eplanettrade.com";
	   $say = "Let the Businesses and Trade Unite";
	 } elseif($logo == "etx")  {
	   $web = "www.empireXchange.com";
	   $say = "Let the Businesses and Trade Unite";
	 } else {
	   $web = "www.ebanctrade.com";
	   $say = "Trading alternatives for business for lifestyle for you";
	 }






if($hhhh)  {
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
}

 pdf_setfont($pdf, $font, 12);
 pdf_set_text_pos($pdf, 155, 755);
 pdf_continue_text($pdf, $row['memid']);
 pdf_continue_text($pdf, " ");
 pdf_continue_text($pdf, $newdate2);
 //pdf_continue_text($pdf, " ");
 //pdf_continue_text($pdf, $row[phone]);

   //pdf_set_text_pos($pdf, get_right_pos($row['locoff'].":", $pdf, "450"), 670);
   //pdf_continue_text($pdf, $row['locoff'].":");

  if($printStationery) {

	   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	   pdf_setfont($pdf, $font, 8);

	   pdf_set_text_pos($pdf, get_right_pos($row['company'], $pdf, "560", 8, $font), 715);
	   pdf_continue_text($pdf, $row['company']);

	   $theight = 0;
	   $text = explode(",",$row['address2'],2);
	   foreach($text as $Line) {
	     pdf_set_text_pos($pdf, get_right_pos(trim($Line), $pdf, "560", 8, $font), 705-$theight);
	     pdf_continue_text($pdf, trim($Line));
	     $theight += 9;
	   }

	   pdf_set_text_pos($pdf, get_right_pos("T: ".$row['phone'], $pdf, "560", 8, $font), 675);
	   pdf_continue_text($pdf, "T: ".$row['phone']);
	   pdf_set_text_pos($pdf, get_right_pos("F: ".$row['fax'], $pdf, "560", 8, $font), 665);
	   pdf_continue_text($pdf, "F: ".$row['fax']);
	   pdf_set_text_pos($pdf, get_right_pos("E: ".$row['email'], $pdf, "560", 8, $font), 655);
	   pdf_continue_text($pdf, "E: ".$row['email']);
	   pdf_set_text_pos($pdf, get_right_pos("W: ".$web."", $pdf, "560", 8, $font), 645);
	   pdf_continue_text($pdf, "W: ".$web."");

	   if($row['CID'] != 15) {
		   pdf_set_text_pos($pdf, get_right_pos($row['abn'], $pdf, "560", 8, $font), 635);
		   pdf_continue_text($pdf, $row['abn']);
	   }

   if($row['CID'] == 12 || $row['CID'] == 15) {
    $pos2 = 265;
   } else {
    $pos2 = 0;
   }

   //address box
   pdf_rect($pdf, 65+$pos2, 620, 200, 76);
   pdf_closepath_stroke($pdf);

   if($row['CID'] == 6) {
     $ima = "ept";
   } elseif($row['CID'] == 1) {
     $ima = "etx";
   } else {
     $ima = "ebt";
   }

   //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$ima."-bw.jpg");
   //pdf_place_image($pdf, $pdfimage, 445, 755, 1);

	 if(!$_SESSION['Country']['logo'])  {
	   $logo = $row['logo'];
	 }  else  {
	   $logo = $_SESSION['Country']['logo'];
	 }

	if($row['logo'] == 'etx') {
    	//$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/public_html/home/images/".$logo."2-bw.jpg");
   		//pdf_place_image($pdf, $pdfimage, 455, 740, .25);
    	$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/home/images/".$logo."2-bw.jpg", '');
   		pdf_fit_image($pdf, $pdfimage, 475, 740, "scale 0.25");
	} else {
    	//$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$logo."-bw.jpg");
   		//pdf_place_image($pdf, $pdfimage, 445, 780, 1);
    	$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$logo."-bw.jpg", '');
   		pdf_fit_image($pdf, $pdfimage, 445, 780, "scale 1");
	}

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1, 0);
   pdf_setfont($pdf, $font, 18);
   pdf_set_text_pos($pdf, 60, 815);
   pdf_continue_text($pdf, $row['sname']);

   pdf_setfont($pdf, $font, 12);
   pdf_set_text_pos($pdf, get_right_pos($row['acno'].":", $pdf, "150", 12, $font), 755);
   pdf_continue_text($pdf, $row['acno'].":");
   pdf_set_text_pos($pdf, get_right_pos($row['sdate'].":", $pdf, "150", 12, $font), 730);
   pdf_continue_text($pdf, $row['sdate'].":");
   //pdf_set_text_pos($pdf, get_right_pos($row['locoff'].":", $pdf, "450"), 673);
   //pdf_continue_text($pdf, $row['locoff'].":");

   pdf_setfont($pdf, $font, 10);
   pdf_set_text_pos($pdf, 35, 585);
   pdf_continue_text($pdf, $row['stdate']);

   //pdf_set_text_pos($pdf, 100, 600);
   //pdf_continue_text($pdf, "Account");

   pdf_set_text_pos($pdf, get_right_pos($row['sbuy'], $pdf, "343", 10, $font), 585);
   pdf_continue_text($pdf, $row['sbuy']);

   pdf_set_text_pos($pdf, get_right_pos($row['ssell'], $pdf, "402", 10, $font), 585);
   pdf_continue_text($pdf, $row['ssell']);

   pdf_setfont($pdf, $font, 8);

   $Data = explode(" ", $row['stbal'], 2);
   $DataCount = 0;
   foreach($Data as $key => $value) {
    pdf_set_text_pos($pdf, get_right_pos($value, $pdf, "462", 8, $font), 590-$DataCount);
    pdf_continue_text($pdf, $value);
    $DataCount += 10;
   }

   $Data = explode(" ", $row['scash'], 2);
   $DataCount = 0;
   foreach($Data as $key => $value) {
    pdf_set_text_pos($pdf, get_right_pos($value, $pdf, "512", 8, $font), 590-$DataCount);
    pdf_continue_text($pdf, $value);
    $DataCount += 10;
   }

   $Data = explode(" ", $row['scbal'], 2);
   $DataCount = 0;
   foreach($Data as $key => $value) {
    pdf_set_text_pos($pdf, get_right_pos($value, $pdf, "560", 8, $font), 590-$DataCount);
    pdf_continue_text($pdf, $value);
    $DataCount += 10;
   }

   pdf_setlinewidth($pdf, 1.5);
   pdf_moveto($pdf, 35, 565);
   pdf_lineto($pdf, 560, 565);
   pdf_stroke($pdf);

  }

 }
