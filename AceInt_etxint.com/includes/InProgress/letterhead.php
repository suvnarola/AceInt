<?
include("global.php");
//function taxinvoice($run_memid,$run_date,$run_cid) {
 
 //global $linkid, $db;
 
 //$query = mysql_db_query($db, "select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country where invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '$_POST[currentyear]-$_POST[currentmonth]-%' and members.memid = '$_POST[memid]' order by companyname", $linkid);
 //$query = mysql_db_query($db, "select * from countrydata, invoice, members, country where invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '2003-03-%' and members.memid = '9312' order by companyname", $linkid);
 $query = dbRead("select * from members, countrydata, country where members.CID=country.countryID and members.CID=countrydata.CID and members.memid = '9124' order by companyname");

 //if(@mysql_num_rows($query) != 0) {
 
 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 pdf_open_file($pdf);
 pdf_set_info($pdf, "Author","E Banc Trade");
 pdf_set_info($pdf, "Title","Tax Invoice");
 pdf_set_info($pdf, "Creator", "E Banc Accounts");
 pdf_set_info($pdf, "Subject", "Tax Invoice");
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
 
 
 #loop around
 while($row = mysql_fetch_array($query)) {

  pdf_begin_page($pdf, 595, 842);
 
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

  $addressbox="$row[contactname]\n$row[companyname]\n$streetno$row[postalname]\n$suburb$row[postalcity]\n$row[postalstate]    $row[postalpostcode]";
 
  $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0])); 

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Tahoma", "host", 0);
  pdf_setfont($pdf, $font, 10);
  pdf_show_boxed($pdf, $addressbox, 85, 655, 170, 71, "left"); 
  
  
  
  
  //$countrydata = dbRead("select * from country where CID ='1'");
  //$row4 = mysql_fetch_assoc($countrydata);   

  //put image up the top.
  $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/ebanc.jpg");  
  pdf_place_image($pdf, $pdfimage, 460, 775, 1);
  
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0); 
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
  pdf_show_boxed($pdf, "POASTAL ADDRESS", 490, 745-$pos, 100, 8, "left"); 
  
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

// return $buffer;
send_to_browser($buffer,"application/pdf","FaxMembers.pdf","attachment");

//} else {
 
 //$buffer="none";
 //return $buffer;

//}

//}


?>