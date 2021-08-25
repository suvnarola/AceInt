<? 
 include("global.php");
 $query = dbRead("select countrydata.*, country.* from countrydata, country where country.countryID = countrydata.CID and country.countryID = ".$_SESSION['User']['CID']."");
 //echo $textcontent;
 if(@mysql_num_rows($query) != 0) {
 
 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 pdf_open_file($pdf);
 pdf_set_info($pdf, "Author","E Banc Trade");
 pdf_set_info($pdf, "Title","Tax Invoice");
 pdf_set_info($pdf, "Creator", "E Banc Accounts");
 pdf_set_info($pdf, "Subject", "Tax Invoice");
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");

 //check to see if there is any data if not then return nothing.
 
 
 #loop around
 while($row = mysql_fetch_array($query)) {

  pdf_begin_page($pdf, 595, 842);
  
   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
   $font = pdf_findfont($pdf, "Tahoma", "host", 0);
   pdf_setfont($pdf, $font, 10);
    
   //put image up the top.
   $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/ebanc.jpg");  
   pdf_place_image($pdf, $pdfimage, 460, 775, 1);
  
   pdf_setcolor($pdf, "fill", "rgb", .55, .55, .55); 
   pdf_setfont($pdf, $font, 9); 
   pdf_show_boxed($pdf, "Trading alternatives for business for lifestyle for you", 60, 820, 300, 9, "left");    
 
   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0); 
   pdf_setfont($pdf, $font, 7); 

   pdf_show_boxed($pdf, "www.ebanctrade.com", 490, 759, 100, 7, "left");    
   pdf_show_boxed($pdf, "HEAD OFFICE", 490, 745, 100, 7, "left"); 
  
   $mess_len = pdf_stringwidth($pdf, $row[address1], $font, 7);
   $textheight = ((ceil($mess_len/100))*7);
   $pos = $textheight + 1;
   pdf_show_boxed($pdf, "$row[address1]", 490, 745-$pos, 100, $textheight, "left");
  
   $pos = $pos + 24;
   pdf_show_boxed($pdf, "POSTAL ADDRESS", 490, 745-$pos, 100, 8, "left"); 
  
   $mess_len = pdf_stringwidth($pdf, $row[address2], $font, 7);
   $textheight = ((ceil($mess_len/100))*7);
   $pos = $pos + $textheight + 1;   
   pdf_show_boxed($pdf, "$row[address2]", 490, 745-$pos, 100, $textheight, "left");
 
   $pos = $pos + 24; 
   pdf_show_boxed($pdf, "OFFICE CONTACTS", 490, 745-$pos, 100, 8, "left"); 
   pdf_show_boxed($pdf, "email: $row[email]", 490, 737-$pos, 100, 8, "left");       
   pdf_show_boxed($pdf, "tel: $row[phone]", 490, 729-$pos, 100, 8, "left");
   pdf_show_boxed($pdf, "fax: $row[fax]", 490, 721-$pos, 100, 8, "left");  
   pdf_show_boxed($pdf, "$row[abn]", 490, 713-$pos, 100, 8, "left");

    
  pdf_end_page($pdf);

 }

 //close it up
 pdf_close($pdf);
 $buffer = PDF_get_buffer($pdf);

 pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","LetterHead.pdf","attachment");
 //return $buffer;

} else {
 
 $buffer="none";
 return $buffer;

}

?>