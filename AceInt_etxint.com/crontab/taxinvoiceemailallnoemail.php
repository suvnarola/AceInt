<?

function taxinvoiceallnoemail($run_date,$CID) {
 
 $query = dbRead("select country.*, countrydata.*, members.*, sum(amount_cash)+sum(amount_trade) as InvoiceAmount from country, countrydata, members, erewards_bank where (members.CID = '$CID') and (country.countryID = countrydata.CID) and (members.CID = country.CountryID) and (members.memid = erewards_bank.memid) and erewards_bank.date like '$run_date-%' and type = '1' and members.emailaddress = '' group by erewards_bank.memid");
 
 if(@mysql_num_rows($query) != 0) {
 
 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 pdf_open_file($pdf);
 pdf_begin_page($pdf, 595, 842);
 pdf_set_info($pdf, "Author","E Banc Trade");
 pdf_set_info($pdf, "Title","Tax Invoice");
 pdf_set_info($pdf, "Creator", "E Banc Accounts");
 pdf_set_info($pdf, "Subject", "Tax Invoice");
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");

 //check to see if there is any data if not then return nothing.
 
 #loop around
 while($row = mysql_fetch_assoc($query)) {

  $invno = $run_date.$row[memid];
 
  $addressbox="\nE Banc Trade Pty Ltd\n2 Production Ave\nWarana\nQueensland    4575";
  
  $newdate=explode("-", $run_date);
  $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0])); 

  $accountdetails="$newdate2\n\n$invno";
  
  if ($row[abn] != 0 && $row[gst] = Y) {
  $gst=number_format((($row[InvoiceAmount]/(100+$row[tax]))*$row[tax]),2);
  $nett=number_format($row[InvoiceAmount]-$gst,2);
  $total=number_format($row[InvoiceAmount],2);
  } else {
   $gst='0.00';
   $nett=number_format($row[InvoiceAmount],2);
   $total=number_format($row[InvoiceAmount],2);
  }
    
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 16);
  pdf_show_boxed($pdf, "E Banc Trade Pty Ltd", 212, 760, 368, 20, "center");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 10);
  pdf_show_boxed($pdf, $addressbox, 85, 655, 170, 71, "left"); 
  pdf_setfont($pdf, $font, 12);
  pdf_show_boxed($pdf, $accountdetails, 440, 667, 105, 60, "left");
 
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 7);
  pdf_show_boxed($pdf, "(Please Refer to Statement for Breakdown of E Rewards)", 120, 551, 250, 20, "center");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "host", 0);
  pdf_setfont($pdf, $font, 10);
  pdf_show_boxed($pdf, "E Rewards Owing for Current Month", 120, 561, 250, 20, "center");
  pdf_show_boxed($pdf, $nett, 445, 561, 55, 20, "right");
  pdf_show_boxed($pdf, $nett, 445, 500, 55, 10, "right");
  
  if($row[tax] != 0) {
   pdf_show_boxed($pdf, $gst, 445, 475, 55, 10, "right");
  }
  
  pdf_show_boxed($pdf, $total, 445, 450, 55, 10, "right");
  pdf_show_boxed($pdf, "E Banc Trade Pty Ltd", 150, 65, 200, 10, "left");
  pdf_show_boxed($pdf, $total, 475, 65, 85, 10, "left");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
  pdf_setfont($pdf, $font, 18); 
  pdf_show_boxed($pdf, $row[tname], 212, 780, 368, 20, "center"); 
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  pdf_show_boxed($pdf, "$row[companyname]", 85, 800, 368, 20, "left");
 
  $accountdetails1="$row[tdate]:\n\n$row[tno]:";
 
  pdf_setfont($pdf, $font, 12);  
  pdf_show_boxed($pdf, $accountdetails1, 285, 667, 150, 60, "right");
 
  //address box
  pdf_rect($pdf, 80, 650, 180, 81);
  pdf_closepath_stroke($pdf); 
 
  //top and bottom thin lines
  pdf_moveto($pdf, 30, 630-$offset);
  pdf_lineto($pdf, 565, 630-$offset);
  pdf_stroke($pdf);  
  
  pdf_moveto($pdf, 30, 210-$offset);
  pdf_lineto($pdf, 565, 210-$offset);
  pdf_stroke($pdf); 
 
  //lines and boxes
  pdf_rect($pdf, 65, 518, 448, 100);
  pdf_closepath_stroke($pdf);
 
  pdf_rect($pdf, 280, 491, 233, 27);
  pdf_closepath_stroke($pdf); 
 
  pdf_rect($pdf, 280, 466, 233, 25);
  pdf_closepath_stroke($pdf);  
 
  pdf_rect($pdf, 280, 441, 233, 25);
  pdf_closepath_stroke($pdf); 
 
  pdf_moveto($pdf, 100, 300-$offset);
  pdf_lineto($pdf, 505, 300-$offset);
  pdf_stroke($pdf);
 
  pdf_show_boxed($pdf, "$row[tsub]:", 285, 500, 155, 12, "right");
  
  if($row[tax] != 0) {
   pdf_show_boxed($pdf, "$row[tgst]:", 285, 475, 155, 12, "right");
  }
  
  pdf_show_boxed($pdf, "$row[ttot]:", 285, 450, 155, 12, "right");
  pdf_setfont($pdf, $font, 10);   

  pdf_show_boxed($pdf, "Please refer to your statement and bank accounts for payment of this Tax Invoice", 75, 275, 455, 20, "center");
 
  if ($row[abn] !=0) {
   $aa="ABN: $row[abn]";
  } else {
   $$aa="";
  }
  
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0); 
  pdf_setfont($pdf, $font, 9); 
  pdf_show_boxed($pdf, "$aa", 40, 140, 515, 10, "center");
  pdf_show_boxed($pdf, "$row[streetno] $row[streetname], $row[city], $row[postalno] $row[postalname], $row[postalcity]", 40, 124, 523, 9, "center");
  pdf_show_boxed($pdf, "Tel: $row[phonearea] $row[phoneno], Fax: $row[faxarea] $row[faxno]", 40, 115, 523, 9, "center");
 
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1); 
  pdf_moveto($pdf, 30, 105-$offset);
  pdf_lineto($pdf, 565, 105-$offset);
  pdf_stroke($pdf); 
 
  pdf_setfont($pdf, $font, 12); 
  pdf_show_boxed($pdf, $row[trem], 40, 85, 515, 15, "center");
  pdf_setfont($pdf, $font, 10); 
  pdf_show_boxed($pdf, "$row[comna]:", 25, 65, 120, 10, "right");
  pdf_show_boxed($pdf, "$row[tnow]:", 360, 65, 110, 10, "right"); 
  pdf_show_boxed($pdf, "$row[tampa]:", 360, 25, 110, 10, "right"); 
 
  pdf_end_page($pdf);
  
  pdf_begin_page($pdf, 595, 842);

 }

 pdf_end_page($pdf);

 //close it up
 pdf_close($pdf);
 $buffer = PDF_get_buffer($pdf);

 return $buffer;

} else {
 
 $buffer="none";
 return $buffer;

}

}


?>