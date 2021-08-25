<?

 /**
  * E Banc New Member Docs
  *
  * member_edit.php
  * Version 0.01
  */

 if(!$_REQUEST[updatenewmembersemail] && !$_REQUEST[updatenewmembers]) {
  include("global.php");
 }

 if($_REQUEST['webDisplay'] == 1) {

 	$buffer = newMembersPDF("");

 	send_to_browser($buffer, "appliction/pdf", "newMembersPDF.pdf","InLine");

 }

 if($_REQUEST['last30']) {

 	$buffer = newMembersPDF("last30");

 	send_to_browser($buffer, "appliction/pdf", "newMembersPDF.pdf","InLine");

 }

 function newMembersPDF($type, $sendToBroswer = false) {

	global $pdf, $font, $fontitalic, $fontbold, $pos_baseref;

     $prevdate = date("Y-m-d", mktime(0,0,0,date("m")-1,date("d"),date("Y")));

     	switch($type) {

     		case "last30":
              //$query = dbRead("select members.memid, members.companyname, members.contactname, members.opt, members.streetno, members.streetname, members.suburb, members.city, members.state, members.postcode, members.postalno, members.postalname, members.postalsuburb, members.postalcity, members.postalstate, members.postalpostcode, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, area.place, area.tradeq, tbl_admin_users.Name as name from members, area left outer join tbl_admin_users on (members.salesmanid = tbl_admin_users.FieldID) left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3) where members.licensee = area.FieldID and (datejoined between '".date("Y-m-d", mktime()-2592000)."' and '".date("Y-m-d")."') and area.FieldID IN (".get_areas_allowed().") and members.CID='".$_SESSION['User']['CID']."' Order By place, companyname;");
              $query = dbRead("select members.memid, members.companyname, members.contactname, members.opt, members.streetno, members.streetname, members.suburb, members.city, members.state, members.postcode, members.postalno, members.postalname, members.postalsuburb, members.postalcity, members.postalstate, members.postalpostcode, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, area.place, area.tradeq, tbl_admin_users.Name as name

				from members
				inner
					join
						area
						on members.licensee = area.FieldID

				left outer join tbl_admin_users on (members.salesmanid = tbl_admin_users.FieldID)

				left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3)

				where
					(datejoined between '".date("Y-m-d", mktime()-2592000)."' and '".date("Y-m-d")."') and area.FieldID IN (".get_areas_allowed().") and members.CID='".$_SESSION['User']['CID']."'

				Order By place, companyname;");

     		  break;

     		case "newlic":
          	  //$query = dbRead("select members.memid, members.companyname, members.contactname, members.opt, members.streetno, members.streetname, members.suburb, members.city, members.state, members.postcode, members.postalno, members.postalname, members.postalsuburb, members.postalcity, members.postalstate, members.postalpostcode, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, area.place, area.tradeq, tbl_admin_users.Name as name from members, area left outer join tbl_admin_users on (members.salesmanid = tbl_admin_users.FieldID) left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3) where members.licensee = area.FieldID and (datepacksent Is Null or datepacksent = '0000-00-00') and members.CID='".$_SESSION['User']['CID']."' and licensee = '".$_REQUEST['newlic']."' Order By place, companyname;");
          	  $query = dbRead("select members.memid, members.companyname, members.contactname, members.opt, members.streetno, members.streetname, members.suburb, members.city, members.state, members.postcode, members.postalno, members.postalname, members.postalsuburb, members.postalcity, members.postalstate, members.postalpostcode, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, area.place, area.tradeq, tbl_admin_users.Name as name
				from members

				inner
					join
						area
						on members.licensee = area.FieldID

				left outer join tbl_admin_users on (members.salesmanid = tbl_admin_users.FieldID)
				left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3)

				where
					(datepacksent Is Null or datepacksent = '0000-00-00') and members.CID='".$_SESSION['User']['CID']."' and licensee = '".$_REQUEST['newlic']."'

				Order By place, companyname;");
          	  break;

          	default:
          	  //$query = dbRead("select members.memid, members.companyname, members.contactname, members.opt, members.streetno, members.streetname, members.suburb, members.city, members.state, members.postcode, members.postalno, members.postalname, members.postalsuburb, members.postalcity, members.postalstate, members.postalpostcode, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, area.place, area.tradeq, tbl_admin_users.Name as name from members, area left outer join tbl_admin_users on (members.salesmanid = tbl_admin_users.FieldID) left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3) where members.licensee = area.FieldID and (datepacksent Is Null or datepacksent = '0000-00-00') and members.CID='".$_SESSION['User']['CID']."' Order By place, companyname;");
          	  $query = dbRead("select members.memid, members.companyname, members.contactname, members.opt, members.streetno, members.streetname, members.suburb, members.city, members.state, members.postcode, members.postalno, members.postalname, members.postalsuburb, members.postalcity, members.postalstate, members.postalpostcode, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, area.place, area.tradeq, tbl_admin_users.Name as name

		 		from members

				inner
					join
						area
						on members.licensee = area.FieldID

				left outer join tbl_admin_users on (members.salesmanid = tbl_admin_users.FieldID)
				left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3)

				where
					(datepacksent Is Null or datepacksent = '0000-00-00') and members.CID='".$_SESSION['User']['CID']."'

				Order By place, companyname;");
          	  break;

     	}

     /**
      * Create PDF File
      */

     $pdf = pdf_new();
     //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 	 pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

     pdf_open_file($pdf, '');
     pdf_set_info($pdf, "Author","E Banc Trade");
     pdf_set_info($pdf, "Title","New Members");
     pdf_set_info($pdf, "Creator", "Dave Richardson");
     pdf_set_info($pdf, "Subject", "New Members");
     pdf_set_value($pdf, compress, 9);
     pdf_begin_page($pdf, 595, 842);
     pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
     pdf_set_parameter($pdf, "textformat", "utf8");
     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);

     $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
     $fontitalic = pdf_findfont($pdf, "VerdanaItalic", "winansi", 0);
     $fontbold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);

     pdf_setfont($pdf, $font, 16);
     pdf_set_text_pos($pdf, get_left_pos(get_word("185"), $pdf, "297.5", 16, $font), 830-$offset3);
     pdf_continue_text($pdf, get_word("185"));

     pdf_setlinewidth($pdf, 2);
     pdf_moveto($pdf, 200, 810);
     pdf_lineto($pdf, 400, 810);
     pdf_stroke($pdf);

     /**
      * Set some start variables.
      */

     $current_category = "";
     $current_area = "";
     $pos_baseref = 35;
     $pos = 790;
     $page = 1;
     $index = array();


     $offset3 = 0;
     $counter3 = 0;
     $page = 1;

     /**
      * Main Loop Section.
      */

     while($row = mysql_fetch_array($query)) {

      $offset3 = $counter3;

      $retrieved_area = $row['place'];

      if($retrieved_area != $current_area) {

       $current_area = $retrieved_area;
       $pos = check_for_next_page($row,$pos,'area');
       $pos = display_area_header($current_area,$row['tradeq'],$pos,$font);

      }

      $pos = check_for_next_page($row,$pos,'member');
      $pos = display_entry($row,$pos,$font);

     }

     //close it up
     pdf_end_page($pdf);
     pdf_close($pdf);
     $buffer = pdf_get_buffer($pdf);
     pdf_delete($pdf);

     if($sendToBroswer) {

     	send_to_browser($buffer,"application/pdf","NewMembers.pdf","inline");

     } else {

     	return $buffer;

     }

 }

 /**
  * Functions.
  */

 function display_area_header($area_name,$tradeq,$pos,$font) {

  global $pdf, $fontitalic, $font;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontitalic, 12);
  pdf_set_text_pos($pdf, 12, $pos+15);
  pdf_continue_text($pdf, $area_name." (".$tradeq.")");

  pdf_setlinewidth($pdf, 0.5);
  pdf_moveto($pdf, 10, $pos);
  pdf_lineto($pdf, 585, $pos);
  pdf_stroke($pdf);

  $pos = $pos - 13;

  return $pos;

 }

 function display_entry($row,$pos,$font2) {

  global $pdf, $font, $fontbold, $row1, $Country;

  // companyname.

   pdf_setlinewidth($pdf, 0.5);
   pdf_moveto($pdf, 20, $pos+10);
   pdf_lineto($pdf, 585, $pos+10);
   pdf_stroke($pdf);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 8);
   pdf_set_text_pos($pdf, 20, $pos+10);
   //pdf_continue_text($pdf, substr($row['companyname'], 0, 39));

   $NewCompanyname = explode("|", wordwrap($row['companyname'], 35, "|"));
   $counter = 0;
   foreach($NewCompanyname as $Line) {
     pdf_continue_text($pdf, $Line);
   }

  // contactname

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 210, $pos+10);
   //pdf_continue_text($pdf, $row['contactname']);

   $NewContactname = explode("|", wordwrap($row['contactname'], 25, "|"));
   foreach($NewContactname as $Line) {
     pdf_continue_text($pdf, $Line);
   }

  // address

   $addr = ''. $row['streetno'] .' '. $row['streetname'] .' '. $row['suburb'] .' '. $row['city'] .' '. $row['state'] .' '. $row['postcode'] .'';
   $addres = trim($addr);
   $Stringwidth = pdf_stringwidth($pdf, $addres, $font, 8);
   $textheight = ((ceil($Stringwidth/255))*8);

   if ($textheight == 8) {
    $text = 0;
    $noaddr = 7;
   } else  {
    if(!$textheight) {
     $text = 0;
     $noaddr = 0;
    } else {
     $text = 8;
     $noaddr = 7;
    }
   }

   $paddr = ''. $row['postalno'] .' '. $row['postalname'] .' '. $row['postalsuburb'] .' '. $row['postalcity'] .' '. $row['postalstate'] .' '. $row['postalpostcode'] .'';
   $paddres = trim($paddr);
   $pStringwidth = pdf_stringwidth($pdf, $paddres, $font, 8);
   $ptextheight = ((ceil($pStringwidth/255))*8);


   $blah = addresslayoutflat($_SESSION['Country']['countryID'],1);
   $blah2 = addresslayoutflat($_SESSION['Country']['countryID'],2);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 330, $pos+10);
   //pdf_continue_text($pdf, "SA: ".$addres);
   //pdf_continue_text($pdf, "PA: ".$paddres);

  foreach($blah as $key => $value) {
    $addline = "";

    foreach($value as $key2) {
     if($row[$key2]) {
      $addline .= $row[$key2] ." ";
     }
    }

    if(trim($addline))  {

     $NewCompanyname = explode("|", wordwrap($addline, 60, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
      $text = $text + 8;
     }
    }
  }

  foreach($blah2 as $key => $value) {
    $addline2 = "";

    foreach($value as $key2) {
     if($row[$key2]) {
      $addline2 .= $row[$key2] ." ";
     }
    }

    if(trim($addline2))  {

     $NewCompanyname = explode("|", wordwrap($addline2, 60, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
      $text = $text + 8;
     }
    }
  }

  // next line. take something from the pos

   $pos = $pos - $text;

  // message.
   //if($_SESSION['Country']['english'] == 'Y' && $row1['english'] == 'N')  {
   // $desc=$row['engdesc'];
   //} else {
   //  $desc=$row['description'];
   //}

   $pos = $pos - 2;

   $CatSQL = dbRead("select mem_categories.*, categories.category as CatName from mem_categories, categories where (mem_categories.category = categories.catid) and memid = '".$row['memid']."'");
   $CatCount = mysql_num_rows($CatSQL);
   $Counter = 1;
   while($CatRow = mysql_fetch_assoc($CatSQL)) {

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fontbold, 7);
    pdf_set_text_pos($pdf, 30, $pos);
    pdf_continue_text($pdf, $CatRow['CatName']);

    $pos = $pos - 8;


	$textheight = 0;

    $pos = check_for_next_page($CatRow,$pos,'description');

    $NewDesc = explode("|", wordwrap($CatRow['description'], 145, "|"));
    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 7);
    pdf_set_text_pos($pdf, 50, $pos);
    foreach($NewDesc as $Line) {
     pdf_continue_text($pdf, $Line);
     $textheight += 7;
    }

    if($Counter != $CatCount) { $pos = $pos - $textheight - 2; } else { $pos = $pos - 8; }

    if($CatRow['engdesc'])  {

     $pos = $pos - $textheight + 7;

     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
     pdf_setfont($pdf, $font2, 7);
     pdf_set_text_pos($pdf, 50, $pos);

     $pos = check_for_next_page($CatRow,$pos,'description');

     $NewDesc = explode("|", wordwrap($CatRow['engdesc'], 145, "|"));
     foreach($NewDesc as $Line) {
      pdf_setfont($pdf, $font2, 7);
      pdf_continue_text($pdf, $Line);
      $textheight += 7;
     }

     $pos = $pos - 8;

    }

    $Counter++;

   }

   $pos = $pos - 2;

  // next line. take something from the pos

   $pos = $pos - ($textheight-$noaddr) - 10;

  // fax/tel/email

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 8);
   pdf_set_text_pos($pdf, 20, $pos+10);
   pdf_continue_text($pdf, "Tel: ");

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 8);
   pdf_set_text_pos($pdf, 135, $pos+10);
   pdf_continue_text($pdf, "Fax: ");

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 8);
   pdf_set_text_pos($pdf, 255, $pos+10);
   pdf_continue_text($pdf, "Email: ");

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 40, $pos+10);
   pdf_continue_text($pdf, "" . $row['phonearea'] . " " . $row['phoneno'] . "");

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 160, $pos+10);
   pdf_continue_text($pdf, "" . $row['faxarea'] . " " . $row['faxno'] . "");

   if($_REQUEST['new']) {
     $email=$row['emailaddress'];
   } else {
    if($row['opt'] == 'Y')  {
     $email=$row['emailaddress'];
    } elseif($row['opt'] == 'N') {
     $email="none";
    }
   }

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 285, $pos+10);
   pdf_continue_text($pdf, $email);

   if($_REQUEST['new'])  {
    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 455, $pos+10);
    pdf_continue_text($pdf, "S/P: ".$row['name']);
   }
  // return

   $pos = ($pos - 12);

   return $pos;

 }

 function check_for_next_page($row,$pos,$type) {

  global $pdf, $page, $font, $pos_baseref, $fontitalic, $displaydate, $row1;

  //check some heights.

  if($type == "member") {

   $mess_len = pdf_stringwidth($pdf, $row['description'], $font, 6);
   $message_height = ((ceil($mess_len/535))*6);

   $total_height = $message_height + 20;

   $new_pos = $pos - $total_height;

  } elseif($type == "description") {

   $mess_len = pdf_stringwidth($pdf, $row['description'], $font, 6);
   $message_height = ((ceil($mess_len/535))*6);
   $total_height = $message_height + 20;
   $new_pos = $pos - 24 - $total_height;

  } elseif($type == "area") {

   $mess_len = pdf_stringwidth($pdf, $row['place'], $font, 12);
   $message_height = ((ceil($mess_len/535))*6);
   $total_height = $message_height + 20;
   $new_pos = $pos - $total_height - 12;

  }

  if($new_pos < $pos_baseref) {

   pdf_setlinewidth($pdf, 0.5);
   pdf_moveto($pdf, 10, 34);
   pdf_lineto($pdf, 585, 34);
   pdf_stroke($pdf);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 275, 20);
   pdf_continue_text($pdf, date("d-M-Y"));

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 550, 20);
   pdf_continue_text($pdf, "Page $page");

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 15, 20);
   pdf_continue_text($pdf, $displaydate);

   $page = $page + 1;

   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $pos = 810;

   return $pos;

  } else {

   return $pos;

  }

 }

?>