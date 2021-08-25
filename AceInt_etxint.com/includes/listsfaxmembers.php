<?

include("global.php");


$date2 = date("Y", mktime(0,0,0,date("m"),1-1,date("Y")));

if($_SESSION['User']['ReportsAllowed'] == 'all')  {

  $cat_array = "";

} else {

              $newarray = explode(",", $_SESSION['User']['ReportsAllowed']);
              $cat_array = " and (area in (";              
   			  foreach($newarray as $cat_val) {
    			if($count == 0) {
    			 $andor="))";
 				} else {
 				 $andor=",";
				}
    
 				$cat_array.="".$andor."".$cat_val."";
    
 				$count++;
 				
 			   }
}

$query = dbRead("select * from members where CID = '".$_SESSION['User']['CID']."' and monthlyfeecash >0 and faxno !='' and emailaddress = '' $cat_array order by companyname");

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
pdf_open_file($pdf);
pdf_set_info($pdf, "Author","E Banc Trade");
pdf_set_info($pdf, "Title","New Members");
pdf_set_info($pdf, "Creator", "Dave Richardson");
pdf_set_info($pdf, "Subject", "Fax Members");
pdf_set_value($pdf, compress, 9);
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 16);

pdf_show_boxed($pdf, "Hard Copy Members on Fax", 10, 810, 575, 20, "center");    

//lines between
pdf_setlinewidth($pdf, 2);
pdf_moveto($pdf, 200, 810);
pdf_lineto($pdf, 400, 810);
pdf_stroke($pdf);

$offset3 = 0;
$counter3 = 0;
$page = 1;

while($row = mysql_fetch_assoc($query)) {

//$exdate_temp = explode("/", $row[expires]);
   
//$exdate1 = $exdate_temp[0];
//$exdate2 = $exdate_temp[1];
//$thisyear = date("y");
//$thismonth = date("m");
  
//if(($exdate2 < $thisyear) or (($exdate1 <= $thismonth) and ($exdate2 == $thisyear))) { 

$offset3 = $counter3;

$box3 = "$row[faxarea] $row[faxno]";


pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 8);

pdf_show_boxed($pdf, $row[memid], 12, 795-$offset3, 50, 10, "left");  
pdf_show_boxed($pdf, $row[companyname], 75, 795-$offset3, 170, 10, "left");    
pdf_show_boxed($pdf, $row[contactname], 250, 795-$offset3, 100, 10, "left");  
pdf_show_boxed($pdf, $box3, 400, 795-$offset3, 100, 10, "left"); 
   

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 8);


//lines between
pdf_setlinewidth($pdf, 1);
pdf_moveto($pdf, 10, 790-$offset3);
pdf_lineto($pdf, 585, 790-$offset3);
pdf_stroke($pdf);

$counter3 = $counter3 + 20;

	if($counter3 > 710) {
	
	 //lines 
	 pdf_setlinewidth($pdf, 0.5);
	 pdf_moveto($pdf, 10, 34);
	 pdf_lineto($pdf, 585, 34);
	 pdf_stroke($pdf);  
	
     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	 $font = pdf_findfont($pdf, "Verdana", "host", 0);
	 pdf_setfont($pdf, $font, 8);
	 pdf_show_boxed($pdf, "Page $page", 550, 20, 40, 10, "left");
			    
 	 $page = $page + 1;
	
	 pdf_end_page($pdf);
	 pdf_begin_page($pdf, 595, 842);
	 $counter3 = 0;
	 $offset3 =0;
	}

//}

}

//lines 
pdf_setlinewidth($pdf, 0.5);
pdf_moveto($pdf, 10, 34);
pdf_lineto($pdf, 585, 34);
pdf_stroke($pdf);  
	
pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 8);
pdf_show_boxed($pdf, "Page $page", 550, 20, 40, 10, "left");

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = PDF_get_buffer($pdf);
pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","FaxMembers.pdf","attachment");

?>