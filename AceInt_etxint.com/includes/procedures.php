<?

 ini_set("max_execution_time", 60);

 /**
  * Functions.
  */

 if($_REQUEST['list'])  {
  include("global.php");
  $buffer = directory();
  send_to_browser($buffer,"application/pdf","Directory.pdf","inline");
 }

 function directory() {

  global $pdf, $font, $fontbold, $fontitalic, $displaydate, $current_category, $current_area, $pos_baseref, $pos, $row, $page, $index;

  /**
   * Set some start variables.
   */

  //$current_category = "";
  $current_proc_code = "";
  $current_area = "";
  $pos_baseref = 80;
  $pos = 810;
  $page = 1;
  $index = array();

  /**
   * Main Working Section.
   */

  $pdf = pdf_new();
  pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
  pdf_open_file($pdf);
  pdf_set_info($pdf, "Author","E Banc Trade Pty Ltd");
  pdf_set_info($pdf, "Title","E Banc Trade Directory");
  pdf_set_info($pdf, "Creator", "Antony Puckey");
  pdf_set_info($pdf, "Subject", "E Banc Trade Directory");
  pdf_set_value($pdf, compress, 9);
  pdf_begin_page($pdf, 595, 842);
  pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
  pdf_set_parameter($pdf, "textformat", "utf8");

  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  $font2 = pdf_findfont($pdf, "Verdana", "winansi", 0);
  $fontbold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);
  $fontitalic = pdf_findfont($pdf, "VerdanaItalic", "winansi", 0);

  $displaydate = date('l, jS F Y');

  /**
   * Loop around the Main Query and generate the PDF.
   */

  $query = dbRead("SELECT * from tbl_procedure where CID = '".$_SESSION['Country']['countryID']."' order by proc_code, proc_no");
  while($row = mysql_fetch_assoc($query)) {

     if ($current_proc_code != $row[proc_code])  {
       $BookMark = pdf_add_bookmark($pdf, $row[proc_code]);
	   $current_proc_code = $row[proc_code];
     }

     pdf_add_bookmark($pdf, $row[proc_name], $BookMark);

     add_index($row[proc_code]." - ".$row[proc_name],$page);

     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
     pdf_setfont($pdf, $fontbold, 11);
     pdf_set_text_pos($pdf, 60, $pos);
     pdf_continue_text($pdf, "OPERATING");
     pdf_continue_text($pdf, "PROCEDURE");
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "");

     pdf_setfont($pdf, $font, 11);
     pdf_continue_text($pdf, "PURPOSE");

     pdf_setfont($pdf, $fontbold, 11);
     pdf_set_text_pos($pdf, 160, $pos);
     pdf_continue_text($pdf, $row[proc_code]."-".$row[proc_no].": ".$row[proc_name]);
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "");

     $pos = $pos - 44;

      pdf_setfont($pdf, $font, 11);
      $NewDesc = explode("|", wordwrap($row['proc_purpose'], 65, "|"));
      foreach($NewDesc as $Line) {
        pdf_continue_text($pdf, $Line);
        $pos = $pos - 11;
      }

     $pos = $pos - 22;

     pdf_set_text_pos($pdf, 60, $pos);
     pdf_continue_text($pdf, "ASSOCIATED");
     pdf_continue_text($pdf, "DOCUMENTS");

     pdf_set_text_pos($pdf, 160, $pos);

      $NewDesc = explode("|", wordwrap($row['proc_ad'], 65, "|"));
      foreach($NewDesc as $Line) {
        pdf_continue_text($pdf, $Line);
        $pos = $pos - 11;
      }

     $pos = $pos - 33;

     pdf_setfont($pdf, $fontbold, 11);
     pdf_set_text_pos($pdf, 60, $pos);
     pdf_continue_text($pdf, "PARA. NO.");

     pdf_set_text_pos($pdf, 160, $pos);
     pdf_continue_text($pdf, "DETAILS");

     $pos = $pos - 33;

     $query2 = dbRead("SELECT * from tbl_proc_data where procid = '".$row[fieldid]."' and CID = '".$_SESSION['Country']['countryID']."' order by position");
     while($row2 = mysql_fetch_assoc($query2)) {

      $pos = check_for_next_page($row2,$pos);

      pdf_setfont($pdf, $font, 11);
      pdf_set_text_pos($pdf, 60, $pos);
      pdf_continue_text($pdf, $row['proc_code']."-".$row['proc_no'].".".$row2['position']);

      pdf_setfont($pdf, $fontbold, 11);
      pdf_set_text_pos($pdf, 160, $pos);
      pdf_continue_text($pdf, $row2[pos_title].":");

      pdf_setfont($pdf, $font, 11);
      //$NewDesc = explode("|", wordwrap($row2[pos_data], 65, "|"));
      $NewDesc = explode("\r\n", wordwrap($row2[pos_data], 65, "\r\n"));
      foreach($NewDesc as $Line) {
        pdf_continue_text($pdf, $Line);
        $textheight += 11;
        $pos = $pos - 11;
      }
     $pos = $pos - 33;

	 }

     pdf_setlinewidth($pdf, 0.5);
     pdf_moveto($pdf, 10, 34);
     pdf_lineto($pdf, 585, 34);
     pdf_stroke($pdf);

     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
     pdf_setfont($pdf, $font, 8);
     pdf_set_text_pos($pdf, 550, 20);
     pdf_continue_text($pdf, "Page $page");

     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
     pdf_setfont($pdf, $font, 8);
     pdf_set_text_pos($pdf, 15, 20);
     pdf_continue_text($pdf, $displaydate);

     pdf_end_page($pdf);
     pdf_begin_page($pdf, 595, 842);
     $pos = 810;
     $page = $page+1;

  }

  // end page number.

  //pdf_setlinewidth($pdf, 0.5);
  //pdf_moveto($pdf, 10, 34);
  //pdf_lineto($pdf, 585, 34);
  //pdf_stroke($pdf);

  //pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  //pdf_setfont($pdf, $font, 8);
  //pdf_set_text_pos($pdf, 550, 20);
  //pdf_continue_text($pdf, "Page $page");

  //pdf_end_page($pdf);
  //pdf_begin_page($pdf, 595, 842);
  //$pos = 810;

  // index page.

  display_index($font2);

  // finish off the pdf.
  pdf_end_page($pdf);
  pdf_close($pdf);

  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);

  return $buffer;

 }

 function add_index($category,$page) {

  global $index, $row1;

  $index[$category] = $page;

 }

 function display_index($font2) {

  global $index, $pdf, $font, $fontbold, $pos_baseref, $row1;

  $pos = 810;
  $offset2 = 10;
  $offset = 0;
  $count = 1;

  foreach($index as $content => $value) {

   $pos = $pos - $offset2;

   // category name

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   pdf_setfont($pdf, $font2, 8);
   pdf_set_text_pos($pdf, 50+$offset, $pos+15);
   pdf_continue_text($pdf, $content);

   // page number

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 295+$offset, $pos+15);
   pdf_continue_text($pdf, $value);

   if($pos < $pos_baseref) {

    $offset = 305;
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

 function check_for_next_page($row2,$pos) {

  global $pdf, $page, $font, $fontbold, $pos_baseref, $fontitalic, $displaydate, $row, $row2;

   $mess_len = pdf_stringwidth($pdf, $row2['pos_data'], $font, 11);
   $message_height = ((ceil($mess_len/65))*11);

   $total_height = $message_height + 22;

   $new_pos = $pos - $total_height;

  if($new_pos < $pos_baseref) {

   pdf_setlinewidth($pdf, 0.5);
   pdf_moveto($pdf, 10, 34);
   pdf_lineto($pdf, 585, 34);
   pdf_stroke($pdf);

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

     pdf_setfont($pdf, $fontbold, 11);
     pdf_set_text_pos($pdf, 60, $pos);
     pdf_continue_text($pdf, "OPERATING");
     pdf_continue_text($pdf, "PROCEDURE");
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "PARA. NO.");

     pdf_set_text_pos($pdf, 160, $pos);
     pdf_continue_text($pdf, $row[proc_code]."-".$row[proc_no].": ".$row[proc_name]);
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "");
     pdf_continue_text($pdf, "DETAILS");

   $pos = $pos - 77;

   return $pos;

  } else {

   return $pos;

  }

 }
?>