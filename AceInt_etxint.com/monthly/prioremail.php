<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");
 include("/home/etxint/admin.etxint.com/includes/modules/class.phpmailer.php");

 $date1 = date("d-m-Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

 $query = dbRead("select FieldID as userid, tbl_admin_users.Name, EmailAddress, logo, countrycode, countryID from tbl_admin_users, country where (tbl_admin_users.CID = country.countryID) and Suspended = 0");

 while($row = mysql_fetch_array($query)) {

  $query1 = dbRead("select *
  from members
	inner
		join
			area on area.FieldID=members.licensee
  where
	members.priority > 0 and area.user ='".$row['userid']."'
  order by fiftyclub, companyname");


 if(mysql_num_rows($query1) != 0) {

 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

 pdf_open_file($pdf,'');
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
  pdf_set_text_pos($pdf, get_left_pos($row['Name'], $pdf, "297.5", 20, $font), 800);
  pdf_continue_text($pdf, $row['Name']);
  pdf_set_text_pos($pdf, get_left_pos($date1, $pdf, "297.5", 20, $font), 755);
  pdf_continue_text($pdf, $date1);
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, get_left_pos("Contacts for", $pdf, "297.5", 12, $font), 772);
  pdf_continue_text($pdf, "Contacts for");

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
  pdf_set_text_pos($pdf, 305, 712);
  pdf_continue_text($pdf, "L/Contacted");
  pdf_set_text_pos($pdf, 395, 712);
  pdf_continue_text($pdf, "Frequency");
  pdf_set_text_pos($pdf, 475, 712);
  pdf_continue_text($pdf, "Club");

  $offset = 0;

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
  pdf_set_text_pos($pdf, 305, 712);
  pdf_continue_text($pdf, "L/Contacted");
  pdf_set_text_pos($pdf, 395, 712);
  pdf_continue_text($pdf, "Frequency");
  pdf_set_text_pos($pdf, 475, 712);
  pdf_continue_text($pdf, "Club");

  }

  $date4 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
  $days = $row1['priority']*7;
  $newdate = explode("-", $row1['date_per']);
  $date3 = date("Y-m-d", mktime(0,0,0,$newdate[1],$newdate[2]+$days,$newdate[0]));

  if($row1['fiftyclub'] == 2) {
   $clubb = "Gold Club";
  } elseif($row1['fiftyclub'] == 1) {
   $clubb = "50% Club";
  } else {
   $clubb = "";
  }

 if($date4 > $date3) {

  $newdate = date("d-m-Y", strtotime($row1['date']));
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 10);
  pdf_set_text_pos($pdf, 30, 692-$offset);
  pdf_continue_text($pdf, $row1['memid']);
  pdf_set_text_pos($pdf, 85, 692-$offset);
  pdf_continue_text($pdf, $row1['companyname']);
  pdf_set_text_pos($pdf,305, 692-$offset);
  pdf_continue_text($pdf, $row1['date_per']);
  pdf_set_text_pos($pdf,395, 692-$offset);
  pdf_continue_text($pdf, $row1['priority']);
  pdf_set_text_pos($pdf,475, 692-$offset);
  pdf_continue_text($pdf, $clubb);

  pdf_moveto($pdf, 30, 675-$offset);
  pdf_lineto($pdf, 565, 675-$offset);
  pdf_stroke($pdf);

  $offset = $offset + 20;
 }
 }


 pdf_end_page($pdf);
 //close it up
 pdf_close($pdf);
 $buffer = PDF_get_buffer($pdf);

 pdf_delete($pdf);


	if($row['logo'] == 'etx') {
	  $nn = "Empire";
	  $ee = "empireXchange";
	} elseif($row['logo'] == 'ept') {
	  $nn = "E Planet";
	  $ee = "eplanettrade";
	} else {
	  $nn = "E Banc";
	  $ee = "ebanctrade";
	}

    // define the text.
    $text = "Attached is your current days contacts.";

	$clubMail = new PHPMailer();

	$clubMail->Priority = 3;
	$clubMail->CharSet = "utf-8";
	$clubMail->From = 'hq@'.$row['countrycode'].'.'.$ee.'.com';
	$clubMail->FromName = $nn." Trade - Web Site";
	$clubMail->Sender = 'hq@'.$row['countrycode'].'.'.$ee.'.com';
	$clubMail->Subject = 'Todays Contact Required for '.$row['Name'].'';
	$clubMail->AddReplyTo('hq@'.$row['countrycode'].'.'.$ee.'.com', $nn." Trade");
	$clubMail->IsSendmail(true);
	$clubMail->Body = get_html_template($row['countryID'], $row['Name'], $text);
	$clubMail->IsHTML(true);

	//$buffer = messages($row['userid'], $row['Name']);
	$clubMail->AddStringAttachment($buffer , "Contacts".$date1.".pdf", "base64","application/pdf");

    $clubMail->AddAddress($row['EmailAddress'], $nn." Trade");
    //$clubMail->AddAddress("dave.r@hq.etxint.com", $nn." Trade");

    $clubMail->Send();


 }

 }
