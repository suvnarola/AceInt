<?
include("global.php");
include("class.html.mime.mail.inc");
include('class.smtp.inc');
include('classifieds.php');
include('realestate.php');

if($_REQUEST['letter_no'])  {
  $dbquery = dbRead("select * from standard_letters where letter_no = ".$_REQUEST[letter_no]." and CID = ".$_SESSION['User']['CID']."","ebanc_letters");
  $letterrow = mysql_fetch_assoc($dbquery);
  $send_text = $letterrow['letter'];
  $title = $letterrow['title'];
  $_REQUEST['date'] = date("d F Y");
}  elseif($_REQUEST['id'])  {
  $dbquery = dbRead("select * from letters where letterid = ".$_REQUEST[id]."","ebanc_letters");
  $letterrow = mysql_fetch_assoc($dbquery);
  $send_text = $letterrow['data'];
  $title = $letterrow['subject'];
  //$_REQUEST['type'] = $letterrow['type'];
  $_REQUEST['type'] = 1;
  $_REQUEST['Client'] = $letterrow['memid'];
  $_REQUEST['date'] = date("d-m-Y", strtotime($letterrow['date']));
  $_REQUEST['to'] = $letterrow['dear'];
}  else {
  $send_text = $_REQUEST['send_text'];
  $title = $_REQUEST['subject'];
  $dear = $_REQUEST['to'];

    unset($attachArray);
	if($_REQUEST[att]) {
		$attlist = "\r\n\r\nAttached Files \r\n";
		$attArray = $_REQUEST[att];
		foreach($attArray as $key => $value) {

			$ex = explode("/", $value);
			$exx = $ex[1];

			$ftype = explode(".", $$exx);
			$ftypee = $fname[1];

   			$SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/".$value);
   			$attachArray[] = array($SBuffer, $exx, 'base64', 'application/'.$ftypee);
   			$attlist = $attlist."- ".$exx."\r\n";
		}
	}

	if($_REQUEST[clas]) {
      $buffer = classified();
   	  $attachArray[] = array($buffer, 'Classified.pdf', 'base64', 'application/pdf');
   	  $attlist = $attlist."- Classified.pdf\r\n";
	}

	if($_REQUEST[re]) {
      $buffer = realestate();
   	  $attachArray[] = array($buffer, 'Realestate.pdf', 'base64', 'application/pdf');
   	  $attlist = $attlist."- Realestate.pdf\r\n";
	}
    $send_text = $send_text." ".$attlist;
}

if($_REQUEST['type'])  {
  if($_REQUEST['view'] == 2) {

   add_kpi("55", $_REQUEST['Client'],$title);
   $newdate3=date("Y/m/d", strtotime($_REQUEST['date']));
   $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
   $letid = dbWrite("insert into letters (date,memid,userid,type,subject,dear,data) values ('".$newdate3."','".$_REQUEST['Client']."','".$_SESSION['User']['FieldID']."','".$_REQUEST['type']."','".addslashes(encode_text2($title))."','".addslashes(encode_text2($_REQUEST['to']))."','".addslashes(encode_text2($send_text))."')",'ebanc_letters',true);
   $details = "<a href=\"includes/lettersend.php?Action=true&id=". $letid ."&ChangeMargin=1\" class=\"nav\">Communication Sent</a>";
   dbWrite("insert into notes (date,memid,userid,type,note) values ('".$curdate."','".$_REQUEST['Client']."','".$_SESSION['User']['FieldID']."','1','".addslashes($details)."')",'etradebanc');

  }
 if($_REQUEST['type'] == 1)  {

  $buffer = taxinvoice($_REQUEST['Client'],$title,$_REQUEST['to'],$send_text,$_REQUEST['header'],$_REQUEST['date'],$_REQUEST['buyname']);
  send_to_browser($buffer,"application/pdf","Letter.pdf","attachment");

 } elseif($_REQUEST['type'] == 2) {
   //if($_REQUEST['view'] == 1)  {

  $DelArray = $_REQUEST[del];
   //} else {
    // define the text.
    $text = get_html_template($_SESSION['User']['CID'],$_REQUEST['to'],'RE Account: '.$_REQUEST['Client'].'<br><br>'.nl2br($send_text));
     //unset($attachArray);
     unset($addressArray);
   	if(strstr($_REQUEST['email'], ";")) {
		$emailArray = explode(";", $_REQUEST['email']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $_REQUEST['to']);
		}
	} else {
 		$addressArray[] = array(trim($_REQUEST['email']), $_REQUEST['to']);
	}

	$addressArray[] = array($_SESSION['User']['EmailAddress'], $_REQUEST['to']);
if($ff) {
	if($_REQUEST[att]) {
		$attArray = $_REQUEST[att];
		foreach($attArray as $key => $value) {

			$ex = explode("/", $value);
			$exx = $ex[1];

			$ftype = explode(".", $$exx);
			$ftypee = $fname[1];

   			$SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/".$value);
   			$attachArray[] = array($SBuffer, $exx, 'base64', 'application/'.$ftypee);
   			$attlist = $attlist."<$value>/r/n";

		}
	}

	if($_REQUEST[clas]) {
      $buffer = classified();
   	  $attachArray[] = array($buffer, 'Classified.pdf', 'base64', 'application/pdf');
   	  $attlist = $attlist."<Classified.pdf>/r/n";
	}

	if($_REQUEST[re]) {
      $buffer = realestate();
   	  $attachArray[] = array($buffer, 'Realestate.pdf', 'base64', 'application/pdf');
   	  $attlist = $attlist."<Realestate.pdf>/r/n";
	}
}
	sendEmail($_SESSION['User']['EmailAddress'], getWho($_SESSION['Country'][logo], 1) .' Accounts', $_SESSION['User']['EmailAddress'], $_REQUEST['subject'], $_SESSION['User']['EmailAddress'], getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);


   echo "Your email has been sent to ".$_REQUEST['email']."";
   //}
 }

}

function taxinvoice($client,$subject,$name,$textcontent,$header,$date) {

 global $pdf, $font;

 $query = dbRead("select countrydata.*, members.*, country.*, members.abn as abnn from countrydata, members, country where members.CID=country.countryID and countrydata.CID=members.CID and members.memid = '$client' order by companyname");
 if(@mysql_num_rows($query) != 0) {

  //Create & Open PDF-Object this is before the loop
  $pdf = pdf_new();
  //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
  pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

  pdf_open_file($pdf, '');
  pdf_set_info($pdf, "Author","E Banc Trade");
  pdf_set_info($pdf, "Title","Letter");
  pdf_set_info($pdf, "Creator", "E Banc Accounts");
  pdf_set_info($pdf, "Subject", "Letter");
  pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
  pdf_set_parameter($pdf, "textformat", "utf8");
  $font = pdf_findfont($pdf, "Arial", "winansi", 0);
  $fontbd = pdf_findfont($pdf, "ArialBold", "winansi", 0);

  //$blah = addresslayout($_SESSION['Country']['countryID']);


  #loop around
  while($row = mysql_fetch_array($query)) {

   $blah = addresslayout($row['CID']);

   pdf_begin_page($pdf, 595, 842);

   if($header) {
     letterhead();
   }

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

   $last="".$row['postalcity']."  ".$row['postalstate']."  ".$row['postalpostcode']."";
   $city=strtoupper($last);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 10);

   pdf_set_text_pos($pdf, 90, 770);
   pdf_continue_text($pdf, $date);

   pdf_set_text_pos($pdf, 90, 740);
   pdf_continue_text($pdf, $row['let_acc'].": ".$row['memid']."");

   pdf_setfont($pdf, $font, 11);
   pdf_set_text_pos($pdf, 90, 710);
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

if($ghgh)  {

   if($row['accholder'] != $row['companyname']) {
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
   pdf_setfont($pdf, $font, 10);
   pdf_set_text_pos($pdf, 90, 620);
   pdf_continue_text($pdf, $row['dear']." $name");

   pdf_setfont($pdf, $fontbd, 10);
   pdf_set_text_pos($pdf, 90, 590);
   pdf_continue_text($pdf, $row['re'].": $subject");

   pdf_setfont($pdf, $font, 10);
   pdf_set_text_pos($pdf, 90, 560);

   $textheight = 210;

   $NewDesc = explode("\r\n", wordwrap($textcontent, '80', "\r\n"));

   foreach($NewDesc as $Key => $Value) {

	 if($textheight > 740){
   		pdf_end_page($pdf);
        pdf_begin_page($pdf, 595, 842);

		if($header) {
		 letterhead();
		}

		$textheight = 0;
		pdf_setfont($pdf, $font, 10);
   		pdf_set_text_pos($pdf, 90, 770);
	 }

     ob_start();
     eval(" ?>".$Value."<? ");
     $output = ob_get_contents();
	 ob_end_clean();

     pdf_continue_text($pdf, $output);
     $textheight += 10;
   }

   pdf_end_page($pdf);
  }

  if($_REQUEST[att]) {
	$attArray = $_REQUEST[att];
	foreach($attArray as $key => $value) {

        pdf_begin_page($pdf, 595, 842);

		$pdi = pdf_open_pdi($pdf, "/home/etxint/public_html/downloads/".$value, "", 0);
		$page= pdf_open_pdi_page($pdf, $pdi, 1, "");
		pdf_place_pdi_page($pdf, $page, 0.0, 0.0, 1.0, 1.0);

   		pdf_end_page($pdf);

	}
  }

  //close it up
  pdf_close($pdf);
  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);

  return $buffer;

 } else {

  $buffer="none";
  return $buffer;

 }
die;
}?>