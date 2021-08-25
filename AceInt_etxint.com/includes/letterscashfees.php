<?

if($_REQUEST['ltype'] == 1) {

  include("/home/etxint/admin.etxint.com/includes/global.php");
  include("class.html.mime.mail.inc");

  if($_REQUEST['header'])  {
    $head = 1;
  }

    // define the text.
   $text = "Dear Diane,\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters('','1','');
     unset($attachArray);
     unset($addressArray);
   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim('diane@au.empirexchange.com'), "Diane");

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Letters - '.$_REQUEST['ltype'], 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);


}

function feeletters($memberarray,$ltype,$header,$noem2 = false)  {

global $pdf, $pdfsig, $row, $pdfimage;

$date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

if($ltype == 1 ||$ltype == 2 ||$ltype == 3 ||$ltype == 15 || $ltype == 20 ||$ltype == 41 ||$ltype == 48 || $ltype == 37 || $ltype == 50 || $ltype == 46)  {
  $count=0;

  foreach($memberarray as $key => $value) {

    if($count == 0) {
     $andor="";
    } else {
     $andor="or";
    }
    $area_array.=" ".$andor." memid='".$value."'";
    $count++;

  }

 if($noem2) {
  $query = dbRead("select * from members, country, countrydata, tbl_members_email where (members.CID = country.countryID) and (members.CID = countrydata.CID) and (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and tbl_members_email.email = '' and ($area_array) order by companyname");
 } else {
  $query = dbRead("select * from members, country, countrydata where (members.CID = country.countryID) and (members.CID = countrydata.CID) and ($area_array) order by companyname");
 }
}
$rr = mysql_num_rows($query);
echo $rr;
print_r($area_array);

if($ltype == 22) {

  $count=0;
   foreach($memberarray as $key => $value) {

    if($count == 0) {
     $andor="";
    } else {
     $andor="or";
    }
    $area_array.=" ".$andor." registered_accounts.FieldID='".$value."'";
    $count++;

   }

 //$query = dbRead("select Acc_No as Acc_No, FieldID as id from registered_accounts where ($area_array)","ebanc_services");
 //$query = dbRead("select Acc_No as memid, registered_accounts.FieldID as id, plans.ServiceID as FieldID3 , reg_acc_details.*, plans.* from registered_accounts, reg_acc_details, plans where (registered_accounts.FieldID = reg_acc_details.reg_acc_id) and (registered_accounts.Plan_ID = plans.FieldID) and ($area_array)","ebanc_services");
 $query = dbRead("select registered_accounts.Acc_No as memid, registered_accounts.FieldID as id, plans.ServiceID as FieldID3 , reg_acc_details.*, plans.* from registered_accounts, reg_acc_details, plans where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and ($area_array)","ebanc_services");

 $nono = 1;
}

if($ltype == 29) {


 //$query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product, registered_accounts.FieldID as id from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.Status_ID < 4 and (Cash_Refund != 0 or Trade_Refund != 0) order by registered_accounts.FieldID","ebanc_services");
 $query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product, registered_accounts.FieldID as id from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and Cash_Refund > 0 group by registered_accounts.Acc_No order by reg_acc_details.companyname","ebanc_services");

 $nono = 1;

}

$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

pdf_open_file($pdf, '');

pdf_set_value($pdf, compress, 9);

pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
$font = pdf_findfont($pdf, "Tahoma", "winansi", 0);
$font_bold = pdf_findfont($pdf, "TahomaBold", "winansi", 0);
$pdfsig = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/monthly/neileast.jpg", '');
$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/disign.jpg", '');

pdf_set_info($pdf, "Title","E Banc Trade - Accounts");
pdf_set_info($pdf, "Author","Accounts Department");
pdf_set_info($pdf, "Creator", "E Banc Trade");
pdf_set_info($pdf, "Subject", "Cash Fees");
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

while($row = mysql_fetch_assoc($query)) {

	pdf_begin_page($pdf, 595, 842);

	pagelayout($nono);

	if($nono) {
	 datainput('30', $header, $row['id'], '', true);
	} else {
	 datainput($ltype, $header, $row[memid], $row['id']);
	}

	pdf_end_page($pdf);

    if($nono4) {
      pdf_begin_page($pdf, 595, 842);
	  datainput2($row['FieldID3'], $row['Club'], $row['CID']);
	  pdf_end_page($pdf);
    }
}

//close it up
pdf_close($pdf);
$buffer = pdf_get_buffer($pdf);
return $buffer;
pdf_delete($pdf);

}

function pagelayout($tt = false) {

 global $pdf, $font, $row, $pdfimage, $pdfsig;

 $date1 = date("d-m-Y", mktime(0,0,0,date("m"),date("d"),date("Y")));

 //put date in
 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
 $font = pdf_findfont($pdf, "Tahoma", "winansi", 0);
 $font_bold = pdf_findfont($pdf, "TahomaBold", "winansi", 0);

 pdf_setfont($pdf, $font, 11);
 pdf_set_text_pos($pdf, 65, 770);
 pdf_continue_text($pdf, $date1);

 pdf_set_text_pos($pdf, 65, 740);

 if(!$tt) {
  pdf_continue_text($pdf, $row['let_acc'].": ".$row['memid']."");
 }

 pdf_set_text_pos($pdf, 65, 710);

 $blah = addresslayout($_SESSION['Country']['countryID']);

   foreach($blah as $key => $value) {
    $addline = "";

    foreach($value as $key2) {
     if($row[$key2]) {
      $addline .= $row[$key2] ." ";
     }
    }

    if(trim($addline))  {

     $NewCompanyname = explode("|", wordwrap($addline, 42, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
     }
    }
   }

 pdf_set_text_pos($pdf, 65, 600);

 if($tt) {
  pdf_continue_text($pdf, "Dear $row[accholder_first]");
 } else {
  pdf_continue_text($pdf, $row['dear']." $row[accholder_first]");
 }
}

function datainput($int_type, $header, $memid, $id = false, $services = false) {

 global $pdf, $font, $pdfsig, $row, $pdfimage;
 $_REQUEST['Client'] = $memid;

 if($header) {
  if($services) {
    letterhead(true);
  } else {
    letterhead();
  }
 }

 $dbletter = dbRead("select * from standard_letters where letter_no = ".$int_type." and CID = ".$_SESSION['User']['CID']."","ebanc_letters");
 $rowletter = mysql_fetch_assoc($dbletter);

 if($int_type == 1) {

   if($_SESSION['User']['CID'] == 1) {
     //pdf_place_image($pdf, $pdfimage, 60, 315, .5);
     pdf_fit_image($pdf, $pdfimage, 60, 205, "scale 1.5");
   }

 } elseif($int_type == 2) {

   if($_SESSION['User']['CID'] == 1) {
     pdf_fit_image($pdf, $pdfimage, 60, 90, "scale 1.5");
   }

 } elseif($int_type == 3) {

   if($_SESSION['User']['CID'] == 1) {
     pdf_fit_image($pdf, $pdfimage, 60, 55, "scale 1.5");
   }

 } elseif($int_type == 15) {

   if($_SESSION['User']['CID'] == 1) {
    pdf_fit_image($pdf, $pdfimage, 60, 370, "scale 1.5");
   }

 } elseif($int_type == 20) {

   if($_SESSION['User']['CID'] == 1) {
    pdf_fit_image($pdf, $pdfimage, 60, 375, "scale 1.5");
   }

 } elseif($int_type == 40) {

   if($_SESSION['User']['CID'] == 1) {
    pdf_fit_image($pdf, $pdfimage, 60, 375, "scale 1.5");
   }

 } elseif($int_type == 41) {

   if($_SESSION['User']['CID'] == 1) {
    pdf_fit_image($pdf, $pdfimage, 60, 290, "scale 1.5");
   }

 } elseif($int_type == 48) {

   if($_SESSION['User']['CID'] == 1) {
    pdf_fit_image($pdf, $pdfimage, 60, 200, "scale 1.5");
   }

 }

 pdf_setfont($pdf, $font, 11);
 pdf_set_text_pos($pdf, 65, 570);

    ob_start();
    eval(" ?>".$rowletter['letter']."<? ");
    $output = ob_get_contents();
	ob_end_clean();

 if($services) {
  $rap = 90;
 } else {
  $rap = 72;
 }

 $text = explode("\r\n", wordwrap($output, $rap, "\r\n"));


 foreach($text as $Key => $Value) {

    pdf_continue_text($pdf, $Value);
    $textheight += 10;
 }

 pdf_setfont($pdf, $font, 10);
 pdf_set_value($pdf, "leading", 15);

}

function datainput2($serviceID = false, $club = false, $cid = false) {

 global $pdf, $font, $pdfsig, $pdfimage;

 //$dbletter = dbRead("select * from plans, services where (plans.ServiceID = services.FieldID) and plans.FieldID = ".$serviceID."","ebanc_services");
 //$rowletter = mysql_fetch_assoc($dbletter);
 $dbletter = dbRead("select * from services where FieldID = ".$serviceID." and CID = ".$cid."","ebanc_services");
 $rowletter = mysql_fetch_assoc($dbletter);

 pdf_setfont($pdf, $font, 7);
 pdf_set_text_pos($pdf, 50, 800);

    ob_start();
    eval(" ?>".$rowletter['planRules']."<? ");
    $output = ob_get_contents();
	ob_end_clean();

 $text = explode("\r\n", wordwrap($output, '150', "\r\n"));

 foreach($text as $Key => $Value) {

    pdf_continue_text($pdf, $Value);

 }

 pdf_setfont($pdf, $font, 10);
 pdf_set_value($pdf, "leading", 15);

}
?>