<?

 /**
  * Real Estate PDF.
  *
  * Version 0.02
  * realestate.php
  */

 ini_set("max_execution_time", 60);
 //include("../includes/global.php");

 if($_REQUEST['list'])  {
  include("../includes/global.php");
  $buffer = realestate();
  send_to_browser($buffer,"application/pdf","Realestate.pdf","inline");
 }

 function realestate() {

  global $pdf, $font, $fontbold, $row, $page, $index, $pos_baseref, $displaydate, $Country, $pos2;

 $query = dbRead("SELECT recategories.recategory, RegionalName as area, realestate.id, realestate.suburb, realestate.income, realestate.evalamount, realestate.totalprice, realestate.price, realestate.pricetrade, realestate.shortdesc, realestate.contactname, realestate.phone, agents.name, realestate.image FROM recategories, realestate, tbl_area_regional, agents WHERE recategories.recatid = realestate.category and realestate.agent = agents.agentid and (realestate.area = tbl_area_regional.FieldID) and realestate.CID='".$_SESSION['User']['CID']."' ORDER BY recategories.recategory,realestate.area");

 /**
  * Set some start variables.
  */

 $current_category = "";
 $current_area = "";
 $pos_baseref = 25;
 $pos = 800;
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
  pdf_set_info($pdf, "Title","ETX International Real Estate Directory");
  pdf_set_info($pdf, "Creator", "Antony Puckey");
  pdf_set_info($pdf, "Subject", "Real Estate Directory");
  pdf_set_value($pdf, compress, 9);
  pdf_begin_page($pdf, 595, 842);
  pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
  pdf_set_parameter($pdf, "textformat", "utf8");

  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  $font2 = pdf_findfont($pdf, "Verdana", "winansi", 0);
  $fontbold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);
  $fontitalic = pdf_findfont($pdf, "VerdanaItalic", "winansi", 0);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 16);
  pdf_set_text_pos($pdf, get_left_pos("Realestate", $pdf, "297.5", 16, $font), 836);
  pdf_continue_text($pdf, "Realestate");

  $displaydate = date('l, jS F Y');

  /**
   * Loop around the Main Query and generate the PDF.
   */

  while($row = mysql_fetch_assoc($query)) {

   $retrieved_category = $row['recategory'];
   $retrieved_area = $row['area'];

   if($retrieved_category != $current_category) {

    $current_category = $retrieved_category;
    $pos = check_for_next_page_re($row,$pos,'category');
    $pos = display_category_header_re($current_category,$pos,$font2);
    add_index_re($current_category,$page);
    $current_area = false;
    $NewCategory = true;

   }

   if($retrieved_area != $current_area) {

    $current_area = $retrieved_area;
    //$pos = check_for_next_page($row,$pos,'area');
    //$pos = display_area_header($current_area,$pos,$font2);
    $NewCategory = false;

   } elseif($NewCategory) {

    //$pos = check_for_next_page($row,$pos,'area');
    //$pos = display_area_header("N/A",$pos,$font2);
    $NewCategory = false;

   }

  $pos = check_for_next_page_re($row,$pos,'member');
  $pos = display_entry_re($row,$pos,$font2);

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

  display_index_re($font2);

  // finish off the pdf.

  pdf_end_page($pdf);
  pdf_close($pdf);

  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);

  //send_to_browser($buffer,"application/pdf","Realestate.pdf","inline");
  return $buffer;

 }

 /**
  * Functions.
  */

 function display_index_re($font2) {

  global $index, $pdf, $font, $pos_baseref, $row1;

  $pos = 810;
  $offset2 = 10;
  $offset = 0;
  $count = 1;

  foreach($index as $content => $value) {

   $pos = $pos - $offset2;

   // category name

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font2, 8);
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

 function add_index_re($category,$page) {

  global $index, $row1;

  $index[$category] = $page;

 }

 function display_entry_re($row,$pos,$font2) {

   global $pdf, $font, $fontbold, $fontbold, $row1, $pos2;

   $pos = $pos - 5;

   pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
   pdf_setlinewidth($pdf, 1);
   pdf_moveto($pdf, 20+$pos2, $pos+10);
   pdf_lineto($pdf, 285+$pos2, $pos+10);
   pdf_stroke($pdf);

   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 245+$pos2, $pos);
   pdf_continue_text($pdf, "ID: ".$row['id']);
   pdf_setfont($pdf, $fontbold, 8);
   pdf_set_text_pos($pdf, 20+$pos2, $pos);
   pdf_continue_text($pdf, $row['area']);
   pdf_continue_text($pdf, "Price: ".$row['totalprice']);


   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $ff);

   if($row['image'] != "noimg.gif") {
	//$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/realimages/".$logo."",'');
    //pdf_fit_image($pdf, $pdfimage, 20+$pos2, $pos+20, "boxsize {179 179} fitmethod meet");
	//pdf_fit_image($pdf, $pdfimage, 455, 740, "scale 0.25");
   }


   pdf_setfont($pdf, $font, 7);
   $NewDesc = explode("|", wordwrap($row['shortdesc'], 72, "|"));
   foreach($NewDesc as $Line) {
    pdf_continue_text($pdf, $Line);
    $pos = $pos - 7;
   }

   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $ff);

   pdf_setfont($pdf, $font, 8);
   //pdf_continue_text($pdf, "Total Price: ".$row['totalprice']."");
   pdf_continue_text($pdf, "Cash Amount: ".$row['price']."");
   pdf_continue_text($pdf, "Trade Amount: ".$row['pricetrade']."");

   pdf_setfont($pdf, $font, 2);
   pdf_continue_text($pdf, $ff);

   pdf_setfont($pdf, $font, 8);
   pdf_continue_text($pdf, $row['name']);
   if($row['name'] != $row['contactname']) {
   pdf_continue_text($pdf, $row['contactname']);
   }
   pdf_continue_text($pdf, $row['phone']);


   //$pos = $pos - 45;
   $pos = $pos - 77;

   return $pos;

 }

 function display_category_header_re($category_name,$pos,$font2) {

  global $pdf, $font, $fontbold, $pos2;

  $pos = $pos - 15;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontbold, 10);
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
  pdf_rect($pdf, 20+$pos2, $pos-$c, 265, 12+$c);
  pdf_fill_stroke($pdf);
  pdf_setcolor($pdf, "both", "rgb", 1, 1, 1, 1);

  $cc = 0;
  foreach($Newarea as $Line) {
	pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "152", 10, $font)+$pos2, $pos+12-$cc);
	pdf_continue_text($pdf, $Line);
	$cc = $cc+10;
  }

  $pos = $pos - 12 - $c;

  return $pos;

 }

 function display_area_header_re($area_name,$pos,$font2) {

  global $pdf, $font, $fontbold, $fontitalic, $row1, $pos2;

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

 function check_for_next_page_re($row,$pos,$type) {

  global $pdf, $page, $font, $pos_baseref, $fontitalic, $displaydate, $row1, $pos2;

  //check some heights.
   pdf_setfont($pdf, $font, 7);
   $NewDesc = explode("|", wordwrap($row['shortdesc'], 72, "|"));
   foreach($NewDesc as $Line) {
    $message_height = $message_height + 7;
   }

   $total_height = $message_height + 69;

  if($type == "member") {

   $new_pos = $pos - $total_height;

  } elseif($type == "category") {

   $new_pos = $pos - 27 - $total_height;

  } elseif($type == "area") {

   $new_pos = $pos - $total_height - 12;

  }

  if($new_pos < $pos_baseref) {

   if($pos2 < 275) {

	if($pos2 == 0) {
	  $pos2 = 285;
	} elseif($pos2 == 285) {
	  $pos2 = 0;
	}

	if($pos2) {
	 pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
	 pdf_setlinewidth($pdf, 0.5);
	 pdf_moveto($pdf, 10+$pos2, 815);
	 pdf_lineto($pdf, 10+$pos2, 25);
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