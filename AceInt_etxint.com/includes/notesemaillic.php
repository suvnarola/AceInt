<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");
 include("../includes/modules/class.phpmailer.php");

$date1 = date("d-m-Y", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")));

//$query = dbRead("select enteredby, Name, email  from notes, adminuser where (notes.enteredby=adminuser.username) and reminder='$date' group by enteredby");
//$query = dbRead("select userid, Name, EmailAddress  from notes, tbl_admin_users where (notes.userid=tbl_admin_users.FieldID) and reminder = '$date' group by userid");
//$query = dbRead("select licensee, place, reportemail from notes, members, area where (notes.memid = members.memid) and (members.licensee = area.FieldID) and date like '$date %' group by licensee");
//$query = dbRead("select licensee, tradeq, place, reportemail, area.CID as CID, logo, countrycode from notes, members, area, country where (notes.memid = members.memid) and (members.licensee = area.FieldID) and (area.CID = country.countryID) and date like '$date %' group by licensee");
$query = dbRead("select licensee, tradeq, place, reportemail, area.CID as CID, logo, countrycode from notes, members, area, country where (notes.memid = members.memid) and (members.licensee = area.FieldID) and (area.CID = country.countryID) and inter = 'Y' and date like '$date %' group by licensee");

while($row = mysql_fetch_array($query)) {

 if($row['reportemail']) {

	if($row['logo'] == 'etx') {
	  //$nn = "Empire";
	  //$ee = "empireXchange";
	} elseif($row['logo'] == 'ept') {
	  //$nn = "E Planet";
	  //$ee = "eplanettrade";
	} else {
	  //$nn = "E Banc";
	  //$ee = "ebanctrade";
	}

    // define the text.
    $text = "For your information, attached is a list of notes that have been added today to the member accounts within your agent region.";

	if($ff) {
	$clubMail = new PHPMailer();

	$clubMail->Priority = 3;
	$clubMail->CharSet = "utf-8";
	$clubMail->From = 'hq@'.$row['countrycode'].'.'.getWho($row['logo'], 2);
	$clubMail->FromName = getWho($_SESSION['Country'][logo], 1)." Trade - Web Site";
	$clubMail->Sender = 'hq@'.$row['countrycode'].'.'.getWho($row['logo'], 2);
	$clubMail->Subject = 'Todays Notes Entered in '.$row['place'].'';
	$clubMail->AddReplyTo('hq@'.$row['countrycode'].'.'.getWho($row['logo'], 2), getWho($row['logo'], 1));
	$clubMail->IsSendmail(true);
	$clubMail->Body = get_html_template($row['CID'], $row['place'], $text);
	$clubMail->IsHTML(true);

    $buffer = messages($row['licensee'],$row['place']);
	$clubMail->AddStringAttachment($buffer , "Notes-".$date.".pdf", "base64","application/pdf");

    //$clubMail->AddAddress($row['reportemail'], $nn." Trade");
    $clubMail->AddAddress("dave.r@hq.etxint.com", $nn." Trade");

    if($row['CID'] == 12) {
      $clubMail->AddBCC("zsolt@ebanctrade.com", getWho($row['logo'], 1));
      $clubMail->AddBCC("simandik@ebanctrade.com", getWho($row['logo'], 1));
    }

    //$clubMail->Send();

	}


    $subject = "Todays Notes Entered in ".$row['place']."";
    $text = get_html_template($row['CID'],$row2['tradeq'],$text);
    $buffer = messages($row['licensee'],$row['place']);

    unset($attachArray);
    unset($addressArray);

   	$attachArray[] = array($buffer, "Notes-".$date.".pdf", "base64","application/pdf");

	if(strstr($row['reportemail'], ";")) {
		$emailArray = explode(";", $row['reportemail']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row2['tradeq']);
		}
	} else {
		$addressArray[] = array(trim($row['reportemail']), $row2['tradeq']);
	}

	sendEmail("hq@" . $row['countrycode'] .".". getWho($row[logo], 2), getWho($row[logo], 1) .' Accounts', 'hq@' . $row['countrycode'] . getWho($row[logo], 2), $subject, 'hq@' . $row['countrycode'] .'.' . getWho($row[logo], 2), getWho($row[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

 }
}

function messages($enteredby,$name) {

 global $linkid, $db, $date, $date1;

 //$query1 = dbRead("select *  from notes, members where (notes.memid=members.memid) and reminder='$date' and enteredby='$enteredby' order by companyname");
 //$query1 = dbRead("select * from notes, members left outer join tbl_admin_users on (notes.userid = tbl_admin_users.FieldID) where (notes.memid=members.memid) and date like '$date %' and licensee='$enteredby' and type <= 3 order by companyname");
	$query1 = dbRead("select *

	from notes

		inner
			join
				members	on notes.memid=members.memid

	left outer join tbl_admin_users on (notes.userid = tbl_admin_users.FieldID)

	where
		date like '$date %' and licensee='$enteredby' and type <= 3

	order by companyname");

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
 pdf_begin_page($pdf, 595, 842);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 20);
  pdf_set_text_pos($pdf, get_left_pos($name, $pdf, "297.5", 20, $font), 800);
  pdf_continue_text($pdf, $name);
  pdf_set_text_pos($pdf, get_left_pos($date1, $pdf, "297.5", 20, $font), 755);
  pdf_continue_text($pdf, $date1);
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, get_left_pos("notes for", $pdf, "297.5", 20, $font), 772);
  pdf_continue_text($pdf, "notes for");

  //top and bottom thin lines
  pdf_moveto($pdf, 30, 715);
  pdf_lineto($pdf, 565, 715);
  pdf_stroke($pdf);

  pdf_moveto($pdf, 30, 695);
  pdf_lineto($pdf, 565, 695);
  pdf_stroke($pdf);


  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, 30, 712);
  pdf_continue_text($pdf, "MemID");
  pdf_set_text_pos($pdf, 85, 712);
  pdf_continue_text($pdf, "Company Name");
  pdf_set_text_pos($pdf, 255, 712);
  pdf_continue_text($pdf, "User");
  pdf_set_text_pos($pdf, 325, 712);
  pdf_continue_text($pdf, "Note");

 #loop around
 while($row1 = mysql_fetch_array($query1)) {

  if($offset > 580) {

   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);

   $offset = 0;

  //top and bottom thin lines
  pdf_moveto($pdf, 30, 715);
  pdf_lineto($pdf, 565, 715);
  pdf_stroke($pdf);

  pdf_moveto($pdf, 30, 695);
  pdf_lineto($pdf, 565, 695);
  pdf_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, 30, 712);
  pdf_continue_text($pdf, "MemID");
  pdf_set_text_pos($pdf, 85, 712);
  pdf_continue_text($pdf, "Company Name");
  pdf_set_text_pos($pdf, 255, 712);
  pdf_continue_text($pdf, "User");
  pdf_set_text_pos($pdf, 325, 712);
  pdf_continue_text($pdf, "Note");
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 30, 692-$offset);
  pdf_continue_text($pdf, $row1['memid']);

  $newcom = substr($row1['companyname'], 0, 31);
  pdf_set_text_pos($pdf, 85, 692-$offset);
  pdf_continue_text($pdf, $newcom);

  $Newname = substr($row1['Name'], 0, 12);

  pdf_set_text_pos($pdf, 255, 692-$offset);
  pdf_continue_text($pdf, $Newname);
  $mess = trim(strip_tags($row1['note']));
  $Stringwidth = pdf_stringwidth($pdf, $mess, $font, 10);
  $texthieght = ((ceil($Stringwidth/240))*10);

  $offset = $offset + $texthieght - 12;

  pdf_set_text_pos($pdf, 325, 680-$offset+$texthieght);

  $text = strip_tags($row1['note']);
  $NewNote = explode("|", wordwrap($text, 47, "|"));
  foreach($NewNote as $Line) {

   pdf_continue_text($pdf, $Line);

  }

  pdf_moveto($pdf, 30, 675-$offset);
  pdf_lineto($pdf, 565, 675-$offset);
  pdf_stroke($pdf);

  $offset = $offset + 20;

 }

 pdf_end_page($pdf);

 //close it up
 pdf_close($pdf);
 $buffer = PDF_get_buffer($pdf);

 pdf_delete($pdf);

 return $buffer;

}

?>