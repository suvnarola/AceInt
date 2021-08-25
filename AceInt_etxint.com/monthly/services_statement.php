<?

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/letterscashfees.php");
 include("class.html.mime.mail.inc");

 //$query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.Status_ID < 4 and (Cash_Refund != 0 or Trade_Refund != 0) order by registered_accounts.FieldID","ebanc_services");
 $query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.Status_ID = 0 and Cash_Refund = 0 and Trade_Refund = 0 order by registered_accounts.FieldID","ebanc_services");
 //$query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.Status_ID = 8 order by registered_accounts.FieldID","ebanc_services");

 //while($row = mysql_fetch_assoc($query)) {

    //$query1 = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.FieldID = ". $row['FieldID'] ." order by registered_accounts.FieldID","ebanc_services");

    // define the text.
    $text = get_html_template($_SESSION['User']['CID'], $_SESSION['User']['Name'], 'Attached is your Tax Invoices');

    // get the actual taxinvoice ready.
    //$buffer = statement($query,true,'',true);
	$buffer2 =feeletters($memberarray,'29',true);
    // define carriage returns for macs and pc's
    define('CRLF', "\r\n", TRUE);

    // create a new mail instance
    $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

    // add the text in.
    $mail->add_html($text);

    // add the attachment on.
    $mail->add_attachment($buffer, 'statement.pdf', 'application/pdf');
    $mail->add_attachment($buffer2, 'letter.pdf', 'application/pdf');

    // build the message.
    $mail->build_message();

    // send the message.
    $mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts', 'dave@ebanctrade.com', 'Tax Invoice - '.$row2[companyname],'Bcc: dave@ebanctrade.com');
    //$mail->send($row['accholder'], $row['emailaddress'], 'myServicesBanc', 'info@myservicesbanc.com', 'myServiceBanc - ASIC Requirements','Bcc: dave@ebanctrade.com');

    echo "Statement has been email to ".$_SESSION['User']['EmailAddress']."";
    //echo "Statement has been email to ".$row['emailaddress']." - Reg Acc ID: ".$row['FieldID']."";

   print $coun;
 //}
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
 pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");

 pdf_open_file($pdf);
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

  $query3 = dbRead("select transactions.*, registered_accounts.* from transactions, registered_accounts where (transactions.regAccID = registered_accounts.FieldID) and registered_accounts.FieldID = ".$row['FieldID']." order by dis_date","ebanc_services");
  //$query3 = dbRead("select transactions.*, members.*, tbl_members_companyinfo.Companyname as TOCompanyname from transactions, members left outer join tbl_members_companyinfo on (transactions.to_memid = tbl_members_companyinfo.memid AND transactions.dis_date BETWEEN tbl_members_companyinfo.datefrom AND tbl_members_companyinfo.dateto) where transactions.to_memid = members.memid and transactions.memid = $row[memid] and dis_date between '#$date1-01#' and '#$date2-31#' order by dis_date");
  //$query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees from transactions where memid = $row[memid] and dis_date < '#$date2-01#' group by memid");

if($ff) {
  $query4 = dbRead("select (sum(sell)-sum(buy)) as optrade, sum(dollarfees) as opfees from transactions where memid = $row[memid] and dis_date < '#$date1-01#' group by memid");
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

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 8);

  pdf_set_text_pos($pdf, 35, 570);
  pdf_continue_text($pdf, $box1);

  pdf_set_text_pos($pdf, 100, 570);
  pdf_continue_text($pdf, $box2);

  pdf_set_text_pos($pdf, get_right_pos($box3, $pdf, "342"), 570);
  pdf_continue_text($pdf, $box3);

  pdf_set_text_pos($pdf, get_right_pos($box4, $pdf, "402"), 570);
  pdf_continue_text($pdf, $box4);

  pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "462"), 570);
  pdf_continue_text($pdf, $box41);

  pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "512"), 570);
  pdf_continue_text($pdf, $box5);

  pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560"), 570);
  pdf_continue_text($pdf, $box6);

  //3 top lines in boxes
  pdf_setlinewidth($pdf, 1.5);
  pdf_moveto($pdf, 35, 555);
  pdf_lineto($pdf, 560, 555);
  pdf_stroke($pdf);

  $offset3+=20;
}
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
   $box2=$row3[details];

   //if($row3['TOCompanyname']) {

    //$box2=$row3['TOCompanyname'];

   //}

   if($row3['type_id'] == 1)  {
    $box33 = "Cash";
   } else {
    $box33 = "Trade";
   }

   $box3=number_format($row3['buy'],2);

   $box4=number_format($row3['sell'],2);

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
   $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
   pdf_setfont($pdf, $font, 8);

   pdf_set_text_pos($pdf, 35, 580-$offset3);
   pdf_continue_text($pdf, $box1);

   pdf_set_text_pos($pdf, 100, 580-$offset3);
   pdf_continue_text($pdf, $box2);

   pdf_set_text_pos($pdf, get_right_pos($box33, $pdf, "325"), 580-$offset3);
   pdf_continue_text($pdf, $box33);

   pdf_set_text_pos($pdf, get_right_pos($box3, $pdf, "378"), 580-$offset3);
   pdf_continue_text($pdf, $box3);

   pdf_set_text_pos($pdf, get_right_pos($box4, $pdf, "437"), 580-$offset3);
   pdf_continue_text($pdf, $box4);

   pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "510"), 580-$offset3);
   pdf_continue_text($pdf, $box41);

if($dd) {
   pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "512"), 575-$offset3);
   pdf_continue_text($pdf, $box5);

   pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560"), 575-$offset3);
   pdf_continue_text($pdf, $box6);
}
   $offset3 = $offset3 + $texthieght;

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   $font = pdf_findfont($pdf, "Verdana", "winansi", 0);

   //pdf_setfont($pdf, $font, 6);
   //pdf_set_text_pos($pdf, 100, 570-$offset3);
   //$Newtrequ = explode("|", wordwrap($row3[details], 92, "|"));
   //foreach($Newtrequ as $Line) {
    //pdf_continue_text($pdf, $Line);
   //}

   $tsell = $tsell + $row3['sell'];
   $tbuy = $tbuy + $row3['buy'];

   //3 top lines in boxes
   pdf_setlinewidth($pdf, 1.5);
   pdf_moveto($pdf, 35, 575-$offset3);
   pdf_lineto($pdf, 560, 575-$offset3);
   pdf_stroke($pdf);

   $offset3 = $offset3 + 10;

  }

  if($offset3 > 490) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $offset3=0;

   Stationery($printStationery);
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 8);

  pdf_set_text_pos($pdf, get_right_pos("Totals:", $pdf, "330"), 570-$offset3);
  pdf_continue_text($pdf, "Totals:");

  pdf_set_text_pos($pdf, get_right_pos(number_format($tbuy,2), $pdf, "378"), 570-$offset3);
  pdf_continue_text($pdf, number_format($tbuy,2));

  pdf_set_text_pos($pdf, get_right_pos(number_format($tsell,2), $pdf, "437"), 570-$offset3);
  pdf_continue_text($pdf, number_format($tsell,2));

  pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "510"), 570-$offset3);
  pdf_continue_text($pdf, $box41);

  //pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "560"), 570-$offset3);
  //pdf_continue_text($pdf, $box6);

  if($row['CID'] == 12) {

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

  $box7 = "";
  $box71= "Cash Refund Owing:";
  $box72= "Trade Refund Owing:";
  //$box73= $row['scas']." $feesdetails:";

  $nett=($tradetotal - $row[overdraft] - $row[reoverdraft]);

  $facility=number_format($row[overdraft],2);
  $refacility=number_format($row[reoverdraft],2);
  $nett=number_format($nett,2);
  $cash=number_format($cash,2);

  //$box8= $row['currency']."$facility";
  //$box9= $row['currency']."$refacility";
  //$box10= $row['currency']."$nett";
  //$box11= $row['currency']."$cash";

  $query4 = dbRead("select sum(sell) as sell, sum(buy) as buy from transactions where regAccID = $row[FieldID] and type_id = 1 group by regAccID","ebanc_services");
  $row4 = mysql_fetch_assoc($query4);

  $query5 = dbRead("select sum(sell) as sell, sum(buy) as buy from transactions where regAccID = $row[FieldID] group by regAccID","ebanc_services");
  $row5 = mysql_fetch_assoc($query5);

  //$box8= $facility;
  $box9= $row4['sell'] - $row4['buy'];
  if($box9 < 0) {
   $box9 = 0;
  }

  $box10= $row5['sell'] - ($row5['buy'] + ($box9));
  if($box10 < 0) {
   $box10 = 0;
  }

  //dbWrite("update registered_accounts set Benefit_Rec = '". $row5['buy'] ."' where FieldID = ".$row['FieldID']."","ebanc_services");
  //dbWrite("update registered_accounts set Cash_Refund = ".$box9." where FieldID = ".$row['FieldID']."","ebanc_services");
  //dbWrite("update registered_accounts set Trade_Refund = ".$box10." where FieldID = ".$row['FieldID']."","ebanc_services");

  $box9=number_format($box9,2);
  $box10=number_format($box10,2);

  //$box11= $cash;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 12);

  pdf_set_text_pos($pdf, get_right_pos($box7, $pdf, "300"), 583-$offset3);
  pdf_continue_text($pdf, $box7);
  pdf_set_text_pos($pdf, get_right_pos($box71, $pdf, "300"), 561-$offset3);
  pdf_continue_text($pdf, $box71);
  pdf_set_text_pos($pdf, get_right_pos($box72, $pdf, "300"), 539-$offset3);
  pdf_continue_text($pdf, $box72);
  pdf_set_text_pos($pdf, get_right_pos($box73, $pdf, "300"), 517-$offset3);
  pdf_continue_text($pdf, $box73);

  pdf_set_text_pos($pdf, get_right_pos($box8, $pdf, "420"), 583-$offset3);
  pdf_continue_text($pdf, $box8);
  pdf_set_text_pos($pdf, get_right_pos($box9, $pdf, "420"), 561-$offset3);
  pdf_continue_text($pdf, $box9);
  pdf_set_text_pos($pdf, get_right_pos($box10, $pdf, "420"), 539-$offset3);
  pdf_continue_text($pdf, $box10);
  pdf_set_text_pos($pdf, get_right_pos($box11, $pdf, "420"), 517-$offset3);
  pdf_continue_text($pdf, $box11);

  if($row['CID'] == 12) {

   //$offset3 = $offset3 + 90;

   //if($offset3 > 485) {
    //pdf_end_page($pdf);
    //pdf_begin_page($pdf, 595, 842);
    //$offset3=0;

    //Stationery($printStationery);
   //}

   pdf_setfont($pdf, $font, 7);
   pdf_set_text_pos($pdf, 40, 490-$offset3);
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

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 14);

 pdf_set_text_pos($pdf, 60, 795);
 pdf_continue_text($pdf, $row[companyname]);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 75, 700);

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
 pdf_set_text_pos($pdf, 155, 760);
 pdf_continue_text($pdf, $row['FieldID']);
 pdf_continue_text($pdf, " ");
 pdf_continue_text($pdf, $row['Product']);
 //pdf_continue_text($pdf, " ");
 //pdf_continue_text($pdf, $row[phone]);

   //pdf_set_text_pos($pdf, get_right_pos($row['locoff'].":", $pdf, "450"), 670);
   //pdf_continue_text($pdf, $row['locoff'].":");

  if($printStationery) {

   //address box
   pdf_rect($pdf, 65, 630, 200, 76);
   pdf_closepath_stroke($pdf);

   $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/myServices.jpg");
   pdf_place_image($pdf, $pdfimage, 445, 755, 1);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
   pdf_setfont($pdf, $font, 18);
   pdf_set_text_pos($pdf, 60, 815);
   pdf_continue_text($pdf, "Statement");

   pdf_setfont($pdf, $font, 12);
   pdf_set_text_pos($pdf, get_right_pos("Reg Acc ID:", $pdf, "150"), 760);
   pdf_continue_text($pdf, "Reg Acc ID:");
   pdf_set_text_pos($pdf, get_right_pos("Service:", $pdf, "150"), 735);
   pdf_continue_text($pdf, "Service:");
   //pdf_set_text_pos($pdf, get_right_pos($row['locoff'].":", $pdf, "450"), 668);
   //pdf_continue_text($pdf, $row['locoff'].":");

   pdf_setfont($pdf, $font, 10);
   pdf_set_text_pos($pdf, 35, 605);
   pdf_continue_text($pdf, "Date");

   pdf_set_text_pos($pdf, 100, 605);
   pdf_continue_text($pdf, "Details");

   pdf_set_text_pos($pdf, get_right_pos("C/T", $pdf, "325"), 605);
   pdf_continue_text($pdf, "C/T");

   pdf_set_text_pos($pdf, get_right_pos("Benefit", $pdf, "378"), 605);
   pdf_continue_text($pdf, "Benefit");

   pdf_set_text_pos($pdf, get_right_pos("M/ship", $pdf, "437"), 605);
   pdf_continue_text($pdf, "M/ship");

   pdf_setfont($pdf, $font, 10);

   $Data = explode(" ", "Balance", 2);
   $DataCount = 0;
   foreach($Data as $key => $value) {
    pdf_set_text_pos($pdf, get_right_pos($value, $pdf, "510"), 605-$DataCount);
    pdf_continue_text($pdf, $value);
    $DataCount += 10;
   }

if($gg) {
   $Data = explode(" ", $row['scash'], 2);
   $DataCount = 0;
   foreach($Data as $key => $value) {
    pdf_set_text_pos($pdf, get_right_pos($value, $pdf, "512"), 605-$DataCount);
    pdf_continue_text($pdf, $value);
    $DataCount += 10;
   }

   $Data = explode(" ", $row['scbal'], 2);
   $DataCount = 0;
   foreach($Data as $key => $value) {
    pdf_set_text_pos($pdf, get_right_pos($value, $pdf, "560"), 605-$DataCount);
    pdf_continue_text($pdf, $value);
    $DataCount += 10;
   }
}
   pdf_setlinewidth($pdf, 1.5);
   pdf_moveto($pdf, 35, 585);
   pdf_lineto($pdf, 560, 585);
   pdf_stroke($pdf);

  }

 }
