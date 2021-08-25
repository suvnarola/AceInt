<?

 /**
  * Classifieds PDF.
  *
  * Version 0.02
  * classifieds.php
  */

 ini_set("max_execution_time", 60);
 //include("../includes/global.php");

 if($_REQUEST['list'])  {
  include("../includes/global.php");
  $buffer = classified();
  send_to_browser($buffer,"application/pdf","Classified.pdf","inline");
 }

 function classified() {

  global $pdf, $font, $fontbold, $row, $page, $index, $pos_baseref, $displaydate, $Country, $pos2;

  if($_REQUEST['jack']) {
 	$month = date("n");
	$day = date("j");
	$year = date("Y");
	$epochbefore = date("Y-m-d H:i:s", mktime(0,0,0,$month-1,$day,$year));
    $query = dbRead("SELECT categories.category, classifieds.type, classifieds.id, classifieds.productname, classifieds.price, classifieds.tradeprice, classifieds.shortdesc, classifieds.name, classifieds.phone, classifieds.emailaddress, classifieds.areaid, classifieds.image, tbl_area_regional.RegionalName, currency, classifieds.date FROM classifieds, categories, tbl_area_regional, country WHERE (classifieds.areaid = tbl_area_regional.FieldID) and (classifieds.category = categories.catid) and (classifieds.cid_origin = country.countryID) and categories.catid>0 and classifieds.CID='".$_SESSION['User']['CID']."' and checked = 1 and int_check = 1 and classifieds.type > 0 and classifieds.date > '".$epochbefore."' ORDER BY classifieds.type desc,categories.category,classifieds.id");
  } else {
    $query = dbRead("SELECT categories.category, classifieds.type, classifieds.id, classifieds.productname, classifieds.price, classifieds.tradeprice, classifieds.shortdesc, classifieds.name, classifieds.phone, classifieds.emailaddress, classifieds.areaid, classifieds.image, tbl_area_regional.RegionalName, currency FROM classifieds, categories, tbl_area_regional, country WHERE (classifieds.areaid = tbl_area_regional.FieldID) and (classifieds.category = categories.catid) and (classifieds.cid_origin = country.countryID) and categories.catid>0 and classifieds.CID='".$_SESSION['User']['CID']."' and checked = 1 and int_check = 1 and classifieds.type > 0 ORDER BY classifieds.type desc,categories.category,classifieds.id");
  }

 /**
  * Set some start variables.
  */

 $current_category = "";
 $current_area = "";
 $pos_baseref = 25;
 $pos = 810;
 $pos2 = 0;
 $page = 1;
 $index = array();

 /**
  * Main Working Section.
  */

  $pdf = pdf_new();
  //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
  pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

  pdf_open_file($pdf, '');
  pdf_set_info($pdf, "Author","ETX International");
  pdf_set_info($pdf, "Title","ETX International Classifieds Directory");
  pdf_set_info($pdf, "Creator", "Antony Puckey");
  pdf_set_info($pdf, "Subject", "Classifieds Directory");
  pdf_set_value($pdf, compress, 9);
  pdf_begin_page($pdf, 595, 842);
  pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
  pdf_set_parameter($pdf, "textformat", "utf8");

  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  $fontbold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 16);
  pdf_set_text_pos($pdf, get_left_pos(get_word("176"), $pdf, "297.5", 16, $font), 836);
  pdf_continue_text($pdf, "Classifieds");

  $displaydate = date('l, jS F Y');

  /**
   * Loop around the Main Query and generate the PDF.
   */

  while($row = mysql_fetch_assoc($query)) {

   $retrieved_category = $row['category'];
   $retrieved_area = which_type($row['type']);

   if($retrieved_area != $current_area) {

    $current_area = $retrieved_area;
    $pos = check_for_next_page($row,$pos,'area');
    $pos = display_area_header($current_area,$pos);
	$current_category = "";

   }

   if($retrieved_category != $current_category) {

    $current_category = $retrieved_category;
    $pos = check_for_next_page($row,$pos,'category');
    $pos = display_category_header($current_category,$pos);
    add_index($current_category,$page);

   }



  $pos = check_for_next_page($row,$pos,'member');
  $pos = display_entry($row,$pos);

  }

  // end page number.

  pdf_setlinewidth($pdf, 0.5);
  pdf_moveto($pdf, 10, 15);
  pdf_lineto($pdf, 585, 15);
  pdf_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 7);
  pdf_set_text_pos($pdf, 550, 12);
  pdf_continue_text($pdf, get_word("175")." $page");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 7);
  pdf_set_text_pos($pdf, 15, 12);
  pdf_continue_text($pdf, $displaydate);

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $pos = 810;

  // index page.

  display_index();

  // finish off the pdf.

  pdf_end_page($pdf);
  pdf_close($pdf);

  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);

  //send_to_browser($buffer,"application/pdf","Classifieds.pdf","inline");

  return $buffer;


 }

 /**
  * Functions.
  */

 function which_type($type) {

  switch($type) {
   case "1": return get_word("201"); break;
   case "2": return get_word("200"); break;
  }

 }

 function display_index() {

  global $index, $pdf, $font, $pos_baseref, $row1;

  $pos = 810;
  $offset2 = 10;
  $offset = 0;
  $count = 1;

  foreach($index as $content => $value) {

   $pos = $pos - $offset2;

   // category name

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 50+$offset, $pos+15);
   pdf_continue_text($pdf, $content);

   // page number

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 270+$offset, $pos+15);
   pdf_continue_text($pdf, $value);

   if($pos < $pos_baseref) {

    $offset = 280;
    $pos = 810;

    if($count == 2) {

     pdf_end_page($pdf);
     pdf_begin_page($pdf, 595, 842);

     $offset = 0;
     $count = 1;

    } else {

     $count = 2;

    }

   }

  }

 }

 function add_index($category,$page) {

  global $index, $row1;

  $index[$category] = $page;

 }

 function display_entry($row,$pos) {

   global $pdf, $font, $fontbold, $fontbold, $row1, $Country, $pos2;

   $pos = $pos - 5;

   pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
   pdf_setlinewidth($pdf, 1);
   pdf_moveto($pdf, 20+$pos2, $pos+10);
   pdf_lineto($pdf, 197+$pos2, $pos+10);
   pdf_stroke($pdf);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 8);
   pdf_set_text_pos($pdf, 20+$pos2, $pos+8);

   $Newarea = explode("|", wordwrap($row['productname']." (ID: ".$row['id'].")", 30, "|"));
   $counter = 0;
   foreach($Newarea as $Line) {
	pdf_continue_text($pdf, $Line);
	if($counter > 0) {
      $pos = $pos-8;
	}
	$counter++;
   }

   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $Liner);

   pdf_setfont($pdf, $font, 7);
   pdf_continue_text($pdf, $row['RegionalName']." Area");

   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $Liner);

   if($row['image'] && $row['image'] != 'noimg.gif') {
    //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/public_html/clasimages/thumb-".$row['image']);
    //pdf_fit_image($pdf, $pdfimage, 20+$pos2, $pos+20, "boxsize {179 179} fitmethod meet");
	//pdf_place_image($pdf, $pdfimage, 20+$pos2, $pos+20, 1);
   }

   pdf_setfont($pdf, $font, 6);
   $NewDesc = explode("|", wordwrap($row['shortdesc'], 55, "|"));
   foreach($NewDesc as $Line) {
    pdf_continue_text($pdf, $Line);
    $pos = $pos - 6;
   }

   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $Liner);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 7);
   pdf_continue_text($pdf, "Cash:  ".$row['currency']."".number_format($row['price'], 2));
   pdf_continue_text($pdf, "Trade: ".$row['currency']."".number_format($row['tradeprice'], 2)."");
   pdf_continue_text($pdf, "Total: ".$row['currency']."".number_format(($row['price']+$row['tradeprice']), 2)."");

   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $Liner);

   $pos = $pos - 75;

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font, 7);
   pdf_continue_text($pdf, $row['name']);
   pdf_continue_text($pdf, "Tel: ".$row['phone']);
   //pdf_continue_text($pdf, "Email: ".$row['emailaddress']);

   $counter = 0;
   if($row['emailaddress']) {
	   $NewContactname = explode("|", wordwrap("Email: ".$row['emailaddress'], 47, "|",true));
	   foreach($NewContactname as $Line) {
	     pdf_continue_text($pdf, $Line);
	     if($counter > 0) {
   	 	   $pos = $pos - 7;
   	 	 }
   	   }
   }

   return $pos;

 }

 function display_category_header($category_name,$pos) {

  global $pdf, $font, $pos2;

if($rr) {
  $pos = $pos - 5;

  pdf_setlinewidth($pdf, 2);
  pdf_moveto($pdf, 10+$pos2, $pos);
  pdf_lineto($pdf, 250+$pos2, $pos);
  pdf_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 12);
  pdf_set_text_pos($pdf, 10+$pos2, $pos+15);
  pdf_continue_text($pdf, $category_name);

  $pos = $pos - 15;
}

  $pos = $pos - 10;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 10);
  $Newarea = explode("|", wordwrap($category_name, 31, "|"));
  $counter = 0;
  $c = 0;
  foreach($Newarea as $Line) {
    if($counter > 0) {
      $c = $c+10;
	}
	$counter++;
  }

  pdf_setlinewidth($pdf, 0.5);
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_rect($pdf, 20+$pos2, $pos-$c, 177, 12+$c);
  pdf_fill_stroke($pdf);
  pdf_setcolor($pdf, "both", "rgb", 1, 1, 1, 1);

  $cc = 0;
  foreach($Newarea as $Line) {
	pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "108", 10, $font)+$pos2, $pos+12-$cc);
	pdf_continue_text($pdf, $Line);
	$cc = $cc+10;
  }

  $pos = $pos - 12 - $c;

  return $pos;

 }

 function display_area_header($area_name,$pos) {

  global $pdf, $font, $fontbold, $fontitalic, $row1, $pos2;

if($ff) {
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontitalic, 12);
  pdf_set_text_pos($pdf, 12+$pos2, $pos+15);
  pdf_continue_text($pdf, $area_name);

  $pos = $pos - 10;
}


  $pos = $pos - 20;

  pdf_setlinewidth($pdf, 1);
  pdf_moveto($pdf, 20+$pos2, $pos+18);
  pdf_lineto($pdf, 197+$pos2, $pos+18);
  pdf_stroke($pdf);

  pdf_setlinewidth($pdf, 2);
  pdf_moveto($pdf, 20+$pos2, $pos+22);
  pdf_lineto($pdf, 197+$pos2, $pos+22);
  pdf_stroke($pdf);

  $name = $area_name;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontbold, 12);
  $Newarea = explode("|", wordwrap($name, 15, "|"));
  $counter = 0;
  $c = 0;

  pdf_set_text_pos($pdf, 21+$pos2, $pos+17-$c);

  foreach($Newarea as $Line) {
	pdf_continue_text($pdf, $Line);
	if($counter > 0) {
      $c = $c+12;
	}
	$counter++;
  }

  pdf_setlinewidth($pdf, 1);
  pdf_moveto($pdf, 20+$pos2, $pos-$c);
  pdf_lineto($pdf, 197+$pos2, $pos-$c);
  pdf_stroke($pdf);;

  pdf_setlinewidth($pdf, 2);
  pdf_moveto($pdf, 20+$pos2, $pos-4 - $c);
  pdf_lineto($pdf, 197+$pos2, $pos-4 - $c);
  pdf_stroke($pdf);

  $pos = $pos - 17 - $c;


  return $pos;

 }

 function check_for_next_page($row,$pos,$type) {

  global $pdf, $page, $font, $pos_baseref, $fontitalic, $displaydate, $row1, $pos2;

  //check some heights.

  if($type == "member") {

   $mess_len = pdf_stringwidth($pdf, $row['shortdesc'], $font, 6);
   $message_height = ((ceil($mess_len/179))*6);
   $total_height = $message_height + 75;
   $new_pos = $pos - $total_height;

  } elseif($type == "category") {

   $mess_len = pdf_stringwidth($pdf, $row['shortdesc'], $font, 6);
   $message_height = ((ceil($mess_len/179))*6);
   $total_height = $message_height + 75;
   $new_pos = $pos - 24 - $total_height;

  } elseif($type == "area") {

   $mess_len = pdf_stringwidth($pdf, $row['shortdesc'], $font, 6);
   $message_height = ((ceil($mess_len/179))*6);
   $total_height = $message_height + 75;
   $new_pos = $pos - $total_height - 12;

  }

  if($new_pos < $pos_baseref) {

   if($pos2 < 366) {

	if($pos2 == 0) {
	  $pos2 = 188;
	} elseif($pos2 == 188) {
	  $pos2 = 376;
	} elseif($pos2 == 376) {
	  $pos2 = 0;
	}

	if($pos2) {
	 pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
	 pdf_setlinewidth($pdf, 0.5);
	 pdf_moveto($pdf, 15+$pos2, 815);
	 pdf_lineto($pdf, 15+$pos2, 25);
	 pdf_stroke($pdf);
	}

    $pos = 810;
    return $pos;

   } else {

	pdf_setlinewidth($pdf, 0.5);
	pdf_moveto($pdf, 10, 15);
	pdf_lineto($pdf, 585, 15);
	pdf_stroke($pdf);

	pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	pdf_setfont($pdf, $font, 7);
	pdf_set_text_pos($pdf, 550, 12);
	pdf_continue_text($pdf, get_word("175")." $page");

	pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	pdf_setfont($pdf, $font, 7);
	pdf_set_text_pos($pdf, 15, 12);
	pdf_continue_text($pdf, $displaydate);

	$page = $page + 1;

	pdf_end_page($pdf);
	pdf_begin_page($pdf, 595, 842);
	$pos = 810;
	$pos2 = 0;

	return $pos;

   }

  } else {

   return $pos;

  }

 }

?>