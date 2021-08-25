<?

 require("global.php");

$days=$_GET[pm];
$date5 = date("Y-m-d", mktime(0,0,0,date("m")-$days,1,date("Y")));

if(checkmodule("SuperUser")) {
 $SearchReports = "all";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
}

if($SearchReports == "all") {
 if(checkmodule("SuperUser")) {
  $SearchCID = "%";
 } else {
  $SearchCID = $_SESSION['User']['CID'];
 }

 $query3 = dbRead("select FieldID from area where CID like '$SearchCID'");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= "$row3[FieldID],";
 }
 $at = substr($at, 0, -1);
 $adminuserarray = explode(",", $at);
} else {
 $adminuserarray = explode(",", $SearchReports);
}

 $count = sizeof($adminuserarray);
 $i = 0;
 for ($i = 0; $i < $count; $i++) {

  if($i == 0) {
   $andor = "";
  } else {
   $andor = "or";
  }
  $area_array .= " ".$andor." members.licensee='".$adminuserarray[$i]."'";

 }

 if($_REQUEST['lic']) {
   $area_array = " and (members.licensee='".$_REQUEST['lic']."')";
 } else {
   $area_array = "";
 }

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();

pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","RDI Host");
pdf_set_info($pdf, "Title","Invoice 1");
pdf_set_info($pdf, "Creator", "Antony Puckey");
pdf_set_info($pdf, "Subject", "Hosting Invoice");
pdf_set_value($pdf, compress, 9);
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");

$offset3 = 0;
$pageno = 1;
$report = 1;

templ();

//$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status, sum(sell) as ssell, sum(buy) as sbuy from members, transactions, status where transactions.memid=members.memid and (members.status = status.FieldID) and ($area_array) and dis_date > '#$date5#' and (status.mem_stats_report = 1) and (to_memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).")) group by transactions.memid having (sum(sell) = '0') order by ssell, companyname");
$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status, sum(sell) as ssell, sum(buy) as sbuy from members, transactions, status where transactions.memid=members.memid and (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and dis_date > '#$date5#' and (status.mem_stats_report = 1) and (to_memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).")) group by transactions.memid having (sum(sell) = '0') order by ssell, companyname");

#loop around
while($row9 = mysql_fetch_assoc($query9)) {


 if($offset3 > 650) {

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $offset3 = 0;
  templ();

 }

 $offset3 = memb();

}

  $report = 2;

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  templ();
  $offset3 = 0;

$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status, sum(sell) as ssell, sum(buy) as sbuy from members, transactions, status where transactions.memid=members.memid and (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and dis_date > '#$date5#' and (status.mem_stats_report = 1) and (to_memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).")) group by transactions.memid having (sum(buy) = '0') order by sbuy, companyname ");

#loop around
while($row9 = mysql_fetch_assoc($query9)) {

 if($offset3 > 650) {

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $offset3 = 0;
  templ();

 }

 $offset3 = memb();

}

pdf_end_page($pdf);
pdf_begin_page($pdf, 595, 842);

$offset3 = 0;
$report = 3;

templ();

$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status from members, status where (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and (status.mem_stats_report = 1) order by companyname");
//$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status, sum(sell) as ssell, sum(buy) as sbuy from members, transactions, status where transactions.memid=members.memid and (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and dis_date > '#$date5#' and (status.mem_stats_report = 1) and (to_memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).")) group by transactions.memid having (sum(buy) < 1 and sum(sell) < 1) order by sbuy, companyname ");
//$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status, (sum(sell)+sum(buy)) as tot, sum(sell) as ssell, sum(buy) as sbuy from members, transactions, status where transactions.memid=members.memid and (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and dis_date > '#$date5#' and (status.mem_stats_report = 1) and (to_memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).")) group by transactions.memid having (sum(buy) < 1 and sum(sell) < 1) order by tot, companyname ");

while($row9 = mysql_fetch_assoc($query9)) {

 if($offset3 > 650) {

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $offset3 = 0;

  templ();

 }

 $query4 = dbRead("select sum(sell) as ssell, sum(buy) as sbuy from transactions where memid='$row9[memid]' and to_memid not in ('".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['facacc']."') and dis_date > '#$date5#' group by transactions.memid");
 $row4 = mysql_fetch_assoc($query4);

 if (($row4[sbuy] == 0&&$row4[ssell] == 0))  {

  memb();

 }

}

pdf_end_page($pdf);
pdf_begin_page($pdf, 595, 842);

$offset3 = 0;
$report = 4;

templ();

//$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status from members, status where (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and members.CID = ".$_SESSION['User']['CID']." and (status.mem_stats_report = 1) order by companyname");
$query9 = dbRead("select members.companyname, members.memid, members.datejoined, status, (sum(sell)+sum(buy)) as tot, sum(sell) as ssell, sum(buy) as sbuy from members, transactions, status where transactions.memid=members.memid and (members.status = status.FieldID) $area_array and members.CID = ".$_SESSION['User']['CID']." and dis_date > '#$date5#' and (status.mem_stats_report = 1) and (to_memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).")) group by transactions.memid having (sum(buy) > 0 and sum(sell) > 0) order by tot, companyname ");

while($row9 = mysql_fetch_assoc($query9)) {

 if($offset3 > 650) {

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $offset3 = 0;

  templ();

 }

 //$query4 = dbRead("select sum(sell) as ssell, sum(buy) as sbuy from transactions where memid='$row9[memid]' and to_memid not in ('".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['facacc']."') and dis_date > '#$date5#' group by transactions.memid");
 //$row4 = mysql_fetch_assoc($query4);

 //if (($row4[sbuy] > 0 && $row4[ssell] > 0))  {

  memb();

 //}

}

//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = pdf_get_buffer($pdf);

pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","Report.pdf","inline");

function templ() {

global $pdf, $font, $row, $row9, $date2, $report, $date3, $date4;

if ($report == 1)  {
  $title = "Members who have Purchased but not Sold";
} elseif ($report == 2)  {
  $title = "Members who have Sold but not Purchased";
} elseif ($report == 3)  {
  $title = "Members who have not Traded";
} elseif ($report == 4)  {
  $title = "Members who have Purchased and Sold";
} elseif ($report == 5)  {
  $title = "Top 20% by Buys";
}


pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 12);
pdf_set_text_pos($pdf, get_left_pos("$row[place] $title", $pdf, "297.5"), 800);
pdf_continue_text($pdf, "$row[place] $title");

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 10);
pdf_set_text_pos($pdf, 35, 770-$offset3);
pdf_continue_text($pdf, "Mem ID");
pdf_set_text_pos($pdf, 80, 770-$offset3);
pdf_continue_text($pdf, "Company Name");
pdf_set_text_pos($pdf, get_left_pos("Stat", $pdf, "282.5"), 770-$offset3);
pdf_continue_text($pdf, "Stat");
pdf_set_text_pos($pdf, get_right_pos("Buys", $pdf, "357"), 770-$offset3);
pdf_continue_text($pdf, "Buys");
pdf_set_text_pos($pdf, get_right_pos("Sells", $pdf, "424"), 770-$offset3);
pdf_continue_text($pdf, "Sells");
pdf_set_text_pos($pdf, get_right_pos("Cash Fees", $pdf, "505"), 770-$offset3);
pdf_continue_text($pdf, "Cash Fees");
pdf_set_text_pos($pdf, get_right_pos("Joined", $pdf, "575"), 770-$offset3);
pdf_continue_text($pdf, "Joined");

// lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 35, 755-$offset3);
pdf_lineto($pdf, 575, 755-$offset3);
pdf_stroke($pdf);

}

function memb() {

global $pdf, $font, $row, $date2, $offset3, $report, $date3, $row9, $row4;

$box1=$row9[memid];

$box2=$row9[companyname];

$box3=$row9[status];

//if($report == 3 || $report == 4)  {
 //$box4=number_format($row4[sbuy],2);
 //$box41=number_format($row4[ssell],2);
//} else {
 $box4=number_format($row9[sbuy],2);
 $box41=number_format($row9[ssell],2);
//}

$query5 = dbRead("select memid, Sum((currentfees+overduefees)-currentpaid) as fees from invoice where memid = '$row9[memid]' and date = '#$date3#' group by memid");
$row5 = mysql_fetch_assoc($query5);

$box5=number_format($row5[fees],2);

$box6=$row9[datejoined];

pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
$font = pdf_findfont($pdf, "Verdana", "host", 0);
pdf_setfont($pdf, $font, 8);
pdf_set_text_pos($pdf, 35, 755-$offset3);
pdf_continue_text($pdf, $box1);
pdf_set_text_pos($pdf, 80, 755-$offset3);
pdf_continue_text($pdf, $box2);
pdf_set_text_pos($pdf, get_left_pos($box3, $pdf, "284.5"), 755-$offset3);
pdf_continue_text($pdf, $box3);
pdf_set_text_pos($pdf, get_right_pos($box4, $pdf, "357"), 755-$offset3);
pdf_continue_text($pdf, $box4);
pdf_set_text_pos($pdf, get_right_pos($box41, $pdf, "424"), 755-$offset3);
pdf_continue_text($pdf, $box41);
pdf_set_text_pos($pdf, get_right_pos($box5, $pdf, "505"), 755-$offset3);
pdf_continue_text($pdf, $box5);
pdf_set_text_pos($pdf, get_right_pos($box6, $pdf, "572"), 755-$offset3);
pdf_continue_text($pdf, $box6);

//3 top lines in boxes
pdf_setlinewidth($pdf, 1.5);
pdf_moveto($pdf, 35, 740-$offset3);
pdf_lineto($pdf, 575, 740-$offset3);
pdf_stroke($pdf);

$offset3+=20;

return $offset3;

}

?>