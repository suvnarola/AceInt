<?

// Bar Graph

if(!$_REQUEST['refreshed']) {
 
 $Count = 0;

 foreach($_REQUEST as $key => $value) {
  
  if(!strstr($key, "phpbb")) {
  
   if($Count == 0) {
    $AO = "?";
   } else {
    $AO = "&";
   }
  
   $NEWREQ .= "$AO$key=$value";
 
   $Count++;
  
  }
  
 }
 
 $NEWREQ .= "&refreshed=true";
 ?>
 <html>
 <head>
 <body>
 <img src="<? print "".$_SERVER['SCRIPT_NAME']."$NEWREQ"; ?>">
 </body>
 </head>
 </html>
 <?
 die;
}

 include("global.php");

$query5 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='".$_SESSION['User']['lang_code']."' and page = 'graphs' order by position");

while($row = mysql_fetch_array($query5)) {
 
 $PageData2[$row[position]] = $row[data];

}

function get_page_data2($id)  {
  global $PageData2;
  return $PageData2[$id];
}
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_bar.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_log.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_line.php");

// set the variables for each graph.

if($_REQUEST['GraphID'] == 1) {

 $query = dbRead("select Cumulative as DB_Data, Month as date1 from monthly_members where CID = '".$_REQUEST['PassCID']."' order by Month DESC limit 12");

 $EGRAPH['title'] = get_page_data2(2);
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = get_page_data2(2);
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 19) {

 $query = dbRead("select GrandTotal as DB_Data, Month as date1 from monthly_members group by date1 DESC limit 12");

 $EGRAPH['title'] = get_page_data2(2);
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = get_page_data2(2);
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 20) {

 $query = dbRead("select sum(Amount) as DB_Data, Month as date1 from monthly_members group by Month DESC limit 12");

 $EGRAPH['title'] = get_page_data2(3);
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = get_page_data2(3);
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 17) {

$query = dbRead("select count(erewards.memid) as DB_Data, extract(year_month from erewards.date) as date1 from erewards where erewards.amount_cash = '40' and erewards.amount_trade = '10' group by date1 order by date1 desc limit 12");

 $EGRAPH['title'] = "".get_page_data(2)." - Sponsorship";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(2)." - Sponsorship";
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 18) {

$query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where status = '4' group by date1 order by date1 desc");

 $EGRAPH['title'] = "".get_page_data(2)." - E Rewards";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(2)." - E Rewards";
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 2) {

 $query = dbRead("select Amount as DB_Data, Month as date1 from monthly_members where CID = '".$_REQUEST['PassCID']."' order by Month DESC limit 12");

 $EGRAPH['title'] = get_page_data(6);
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = get_page_data(6);
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 3) {

 $query = dbRead("select Amount as DB_Data, Month as date1 from monthly_transactions where CID = '".$_REQUEST['PassCID']."' order by Month DESC limit 12");

 $EGRAPH['title'] = get_page_data(7);
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = get_page_data(7);
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 4) {

 $query = dbRead("SELECT sum(transactions.buy) AS DB_Data, extract(year_month FROM transactions.dis_date) AS date1 FROM transactions, members WHERE (transactions.memid = members.memid) and (members.CID = ".$_REQUEST['PassCID'].") and (transactions.memid NOT IN (".get_non_included_accounts($_REQUEST['PassCID'],'','',true).") and transactions.to_memid NOT IN (".get_non_included_accounts($_REQUEST['PassCID'],'',true,'').")) and transactions.details not like '%not proceed%' GROUP  BY date1 DESC limit 12");
 //$query = dbRead("SELECT sum(transactions.buy) AS DB_Data, extract(year_month FROM transactions.dis_date) AS date1 FROM transactions, members WHERE (transactions.memid = members.memid) and (members.CID = ".$_REQUEST['PassCID'].") and (transactions.memid NOT IN (".get_non_included_accounts($_REQUEST['PassCID'],'','',true).") and transactions.to_memid NOT IN (".get_non_included_accounts($_REQUEST['PassCID'],'',true,'').")) GROUP  BY date1 DESC limit 12");

 $EGRAPH['title'] = "Volume of Trade Dollars - Total";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - Total";
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 21) {

 $query = dbRead("SELECT sum(transactions.buy) AS DB_Data, extract(year_month FROM transactions.dis_date) AS date1 FROM transactions, members WHERE (members.memid = transactions.memid) and (members.CID = ".$_REQUEST['PassCID'].") and (transactions.memid IN (".get_non_included_accounts($_REQUEST['PassCID'], true,true).")) and transactions.details not like '%not proceed%' GROUP  BY date1 DESC limit 12");

 $EGRAPH['title'] = "Volume of Trade Dollars - RE";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - RE";
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 22) {

 $query = dbRead("SELECT sum(transactions.buy) AS DB_Data, extract(year_month FROM transactions.dis_date) AS date1 FROM transactions, members WHERE (transactions.memid = members.memid) and (members.CID = ".$_REQUEST['PassCID'].") and (transactions.memid NOT IN (".get_non_included_accounts($_REQUEST['PassCID']).") and transactions.to_memid NOT IN (".get_non_included_accounts($_REQUEST['PassCID']).")) GROUP  BY date1 DESC limit 12");

 $EGRAPH['title'] = "Volume of Trade Dollars - G/S";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - G/S";
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 5) {

$query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where licensee = '".addslashes($_REQUEST['area'])."' group by date1 order by date1 desc");

 // get area name.
 
 $row = mysql_fetch_assoc(dbRead("select place from area where FieldID = '".addslashes($_REQUEST['area'])."'"));

 $EGRAPH['title'] = "".get_page_data(2)." - ".$row['place'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(2)." - ".$row['place'];
 $EGRAPH['toptextcolor'] = "black";

}
 elseif($_REQUEST['GraphID'] == 6) {

 $query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where licensee = '".addslashes($_REQUEST['area'])."' group by date1 order by date1 desc limit 12");

 // get area name.
 
 $row = mysql_fetch_assoc(dbRead("select place from area where FieldID = '".addslashes($_REQUEST['area'])."'"));

 $EGRAPH['title'] = "".get_page_data(6)." - ".$row['place'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(6)." - ".$row['place'];
 $EGRAPH['toptextcolor'] = "black";

}
 elseif($_REQUEST['GraphID'] == 7) {

 $query = dbRead("select count(transactions.memid) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid and members.licensee = '".addslashes($_REQUEST['area'])."') and transactions.type = '1' group by date1 order by date1 desc limit 12");

 // get area name.
 
 $row = mysql_fetch_assoc(dbRead("select place from area where FieldID = '".addslashes($_REQUEST['area'])."'"));

 $EGRAPH['title'] = "".get_page_data(7)." - ".$row[place];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(7)." - ".$row['place'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 8) {
 
 $row = mysql_fetch_assoc(dbRead("select place, CID from area where FieldID = '".addslashes($_REQUEST['area'])."'"));
 $Extra = get_non_included_accounts($row['CID']);

 $query = dbRead("select sum(transactions.buy) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and members.licensee = '".addslashes($_REQUEST['area'])."' and transactions.type = '1' and (transactions.memid not in ($Extra)) and (transactions.to_memid not in ($Extra)) group by date1 order by date1 desc limit 12");

 // get area name.
 
 $EGRAPH['title'] = "Volume of G/S Trade Dollars - ".$row['place'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - ".$row['place'];
 $EGRAPH['toptextcolor'] = "black";
 
 } elseif($_REQUEST['GraphID'] == 81) {
 
 $row = mysql_fetch_assoc(dbRead("select place, CID from area where FieldID = '".addslashes($_REQUEST['area'])."'"));
 $Extra = get_non_included_accounts($row['CID'], true,true);

 $query = dbRead("select sum(transactions.buy) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.to_memid) and members.licensee = '".addslashes($_REQUEST['area'])."' and (transactions.memid IN ($Extra)) and transactions.details not like '%not proceed%' group by date1 order by date1 desc limit 12");

 // get area name.
 
 $EGRAPH['title'] = "Volume of R/E Trade Dollars - ".$row['place'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - ".$row['place'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 13) {

$count = 0;
//$query = dbRead("select FieldID from area where disarea='".addslashes($_REQUEST['area'])."'");
$query = dbRead("select tbl_area_physical.FieldID as FieldID from tbl_area_physical, tbl_area_regional where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and tbl_area_regional.FieldID='".addslashes($_REQUEST['area'])."'");
while($row = mysql_fetch_assoc($query)) {
 if($count == 0) {
  $andor = "";
 } else {
  $andor = "or";
 }
 $area_array .= " ".$andor." area='".$row['FieldID']."'";
 $count++;
}

$query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where ($area_array) group by date1 order by date1 desc");

 // get area name.
 
 $EGRAPH['title'] = "".get_page_data(2)." - ".$_REQUEST['area'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(2)." - ".$_REQUEST['area'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 14) {

$count = 0;
//$query = dbRead("select FieldID from area where disarea='".addslashes($_REQUEST['area'])."'");
$query = dbRead("select tbl_area_physical.FieldID as FieldID from tbl_area_physical, tbl_area_regional where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and tbl_area_regional.FieldID='".addslashes($_REQUEST['area'])."'");
while($row = mysql_fetch_assoc($query)) {
 if($count == 0) {
  $andor="";
 } else {
  $andor="or";
 }
 $area_array.=" ".$andor." area='".$row['FieldID']."'";
 $count++;
}

 $query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where ($area_array) group by date1 order by date1 desc limit 12");

 // get area name.
 
 $EGRAPH['title'] = "".get_page_data(3)." - ".$_REQUEST['area'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(3)." - ".$_REQUEST['area'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 15) {

$count=0;
//$query=dbRead("select FieldID from area where disarea='".addslashes($_REQUEST['area'])."'");
$query = dbRead("select tbl_area_physical.FieldID as FieldID from tbl_area_physical, tbl_area_regional where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and tbl_area_regional.FieldID='".addslashes($_REQUEST['area'])."'");
while($row = mysql_fetch_assoc($query)) {
 if($count == 0) {
  $andor="";
 } else {
  $andor="or";
 }
 $area_array.=" ".$andor." members.area='".$row['FieldID']."'";
 $count++;
}

 $query = dbRead("select count(transactions.memid) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and ($area_array) and transactions.type = '1' group by date1 order by date1 desc limit 12");

 // get area name.
 
 $row = mysql_fetch_assoc(dbRead("select place from area where FieldID = '".addslashes($_REQUEST['area'])."'"));

 $EGRAPH['title'] = "".get_page_data(7)." - ".$_REQUEST['area'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(7)." - ".$_REQUEST['area'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 16) {

$count=0;
//$query=dbRead("select FieldID, CID from area where disarea='".addslashes($_REQUEST['area'])."'");
$query = dbRead("select tbl_area_physical.FieldID as FieldID from tbl_area_physical, tbl_area_regional where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and tbl_area_regional.FieldID='".addslashes($_REQUEST['area'])."'");
while($row = mysql_fetch_assoc($query)) {
 if($count == 0) {
  $andor="";
 } else {
  $andor="or";
 }
 $area_array.=" ".$andor." members.area='".$row['FieldID']."'";
 $CID = $row['CID'];
 $count++;
}

 $Extra = get_non_included_accounts($CID);
 $query = dbRead("select sum(transactions.buy) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and ($area_array) and transactions.type = '1' and (transactions.memid not in($Extra)) and (transactions.to_memid not in($Extra)) group by date1 order by date1 desc limit 12");

 // get area name.
 
 $EGRAPH['title'] = "Volume of G/S Trade Dollars - ".$_REQUEST['area'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - ".$_REQUEST['area'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 161) {

$count=0;
//$query=dbRead("select FieldID, CID from area where disarea='".addslashes($_REQUEST['area'])."'");
$query = dbRead("select tbl_area_physical.FieldID as FieldID from tbl_area_physical, tbl_area_regional where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and tbl_area_regional.FieldID='".addslashes($_REQUEST['area'])."'");
while($row = mysql_fetch_assoc($query)) {
 if($count == 0) {
  $andor="";
 } else {
  $andor="or";
 }
 $area_array.=" ".$andor." members.area='".$row['FieldID']."'";
 $CID = $row['CID'];
 $count++;
}

 $Extra = get_non_included_accounts($CID, true,true);
 $query = dbRead("select sum(transactions.buy) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.to_memid) and ($area_array) and (transactions.memid IN ($Extra)) and transactions.details not like '%not proceed%' group by date1 order by date1 desc limit 12");
 // get area name.
 
 $EGRAPH['title'] = "Volume of R/E Trade Dollars - ".$_REQUEST['area'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - ".$_REQUEST['area'];
 $EGRAPH['toptextcolor'] = "black";

} elseif($_REQUEST['GraphID'] == 9) {

//$query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = '".addslashes($_REQUEST['state'])."') group by date1 order by date1 desc");
$query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.FieldID = '".addslashes($_REQUEST['state'])."' group by date1 order by date1 desc");

 $query2 = dbRead("select StateName from tbl_area_states where FieldID = ".$_REQUEST['state']."");
 $row2 = mysql_fetch_assoc($query2);
 
 $EGRAPH['title'] = "".get_page_data(2)." - ".$row2['StateName'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(2)." - ".$row2['StateName'];
 $EGRAPH['toptextcolor'] = "black";

} 
 elseif($_REQUEST['GraphID'] == 10) {

 //$query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = '".addslashes($_REQUEST['state'])."') group by date1 order by date1 desc limit 12");
 $query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.FieldID = '".addslashes($_REQUEST['state'])."' group by date1 order by date1 desc limit 12");

 $query2 = dbRead("select StateName from tbl_area_states where FieldID = ".$_REQUEST['state']."");
 $row2 = mysql_fetch_assoc($query2);
 
 $EGRAPH['title'] = "".get_page_data(7)." - ".$row2['StateName'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(7)." - ".$row2['StateName'];
 $EGRAPH['toptextcolor'] = "black";

}
 elseif($_REQUEST['GraphID'] == 11) {
 
 $otherquery = mysql_fetch_assoc(dbRead("select CID from tbl_area_states where FieldID = '".addslashes($_REQUEST['state'])."'"));
 $Extra = get_non_included_accounts($otherquery['CID']);

 $query = dbRead("select count(transactions.memid) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.FieldID = '".addslashes($_REQUEST['state'])."' and transactions.type = '1' and (transactions.memid not in($Extra)) and (transactions.to_memid not in($Extra)) group by date1 order by date1 desc limit 12");

 $query2 = dbRead("select StateName from tbl_area_states where FieldID = ".$_REQUEST['state']."");
 $row2 = mysql_fetch_assoc($query2);
 
 $EGRAPH['title'] = "".get_page_data(7)." - ".$row2['StateName'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "".get_page_data(7)." - ".$row2['StateName'];
 $EGRAPH['toptextcolor'] = "black";

}
 elseif($_REQUEST['GraphID'] == 12) {

 $otherquery = mysql_fetch_assoc(dbRead("select CID from tbl_area_states where FieldID = '".addslashes($_REQUEST['state'])."'"));

 $Extra = get_non_included_accounts($otherquery['CID']);
 $query = dbRead("select sum(transactions.buy) as DB_Data, extract(year_month from transactions.dis_date) as date1 from transactions, members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.memid = transactions.memid) and  (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.FieldID = '".addslashes($_REQUEST['state'])."' and transactions.type = '1' and (transactions.memid not in($Extra)) and (transactions.to_memid not in($Extra)) group by date1 order by date1 desc limit 12");

 $query2 = dbRead("select StateName from tbl_area_states where FieldID = ".$_REQUEST['state']."");
 $row2 = mysql_fetch_assoc($query2);
 
 $EGRAPH['title'] = "Volume of Trade Dollars - ".$row2['StateName'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Volume of Trade Dollars - ".$row2['StateName'];
 $EGRAPH['toptextcolor'] = "black";

}


// make the data array.

  $ydata = Array();

while($row = mysql_fetch_assoc($query)) {

  $ydata[] = $row['DB_Data'];

  $datax[] = date("M y", mktime(1,1,1,substr($row['date1'], 4),1,substr($row['date1'], 0, 4)));

  $Month = substr($row['date1'], 4);
  $Year = substr($row['date1'], 0, 4);

}
 
 $ydata = array_reverse($ydata);
 $datax = array_reverse($datax);

 // some graphs require us to add the values manually because of different queries.

 if($_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 18 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 13) {
  $new_ydata = $ydata;
  unset($ydata);
  $ydata = Array();
  foreach($new_ydata as $value) {
   $Cumul += $value;
   $ydata[] += $Cumul;
  } 
  $ydata2 = array_slice($ydata, -12, 12);
  $datax = array_slice($datax, -12, 12);
  $ydata = $ydata2;
 }

 if($_REQUEST['GraphID'] == 4 || $_REQUEST['GraphID'] == 21 || $_REQUEST['GraphID'] == 22 || $_REQUEST['GraphID'] == 8 || $_REQUEST['GraphID'] == 12 || $_REQUEST['GraphID'] == 16) {
  $height = 440;
  $bmargin = 110;
 } else {
  $height = 440;
  $bmargin = 70;
 }

// Create the graph. These two calls are always required
$graph = new Graph(595,$height,"auto");    
$graph->img->SetMargin(60,60,20,$bmargin);
$graph->SetScale("textlin");
$graph->SetShadow();

// Callback function for Y-scale
function yScaleCallback($file_size) {

 if(!$file_size) { $file_size = 0; }
 
 if ($file_size >= 1000000000) { 
	$file_size = round($file_size/1000000000, 2). " Bil"; 
 } elseif ($file_size >= 1000000) { 
	$file_size = round($file_size/1000000, 2). " Mil"; 
 } elseif ($file_size >= 1000) { 
	$file_size = round($file_size/1000, 2). " Tho"; 
 } else { 
	$file_size = round($file_size, 2). ""; 
 }

return $file_size;

}
if($_REQUEST['GraphID'] == 1 || $_REQUEST['GraphID'] == 19 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 13) {
 // Create the linear plot
 $lineplot = new LinePlot($ydata);
 $lineplot->SetColor($EGRAPH['lineplotcolor']);
 $lineplot->SetWeight(2);
 $lineplot->SetLegend($EGRAPH['lineplotlegend']);
}

// Create a bar pot 
$bplot = new BarPlot($ydata);

if($_REQUEST['GraphID'] == 1 || $_REQUEST['GraphID'] == 19 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 18 || $_REQUEST['GraphID'] == 2 || $_REQUEST['GraphID'] == 20 || $_REQUEST['GraphID'] == 3 || $_REQUEST['GraphID'] == 4 || $_REQUEST['GraphID'] == 21 || $_REQUEST['GraphID'] == 22 || $_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 6 || $_REQUEST['GraphID'] == 7 || $_REQUEST['GraphID'] == 8 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 10 || $_REQUEST['GraphID'] == 11 || $_REQUEST['GraphID'] == 12 || $_REQUEST['GraphID'] == 13 || $_REQUEST['GraphID'] == 14 || $_REQUEST['GraphID'] == 15 || $_REQUEST['GraphID'] == 16) {
 $bplot->SetYBase($ydata[0]);
}

$bplot->SetFillColor($EGRAPH['barfillcolor']); 
$bplot->SetWidth(.7); 

if($_REQUEST['GraphID'] == 1 || $_REQUEST['GraphID'] == 19 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 18 || $_REQUEST['GraphID'] == 20 || $_REQUEST['GraphID'] == 2 || $_REQUEST['GraphID'] == 3 || $_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 6 || $_REQUEST['GraphID'] == 7 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 10 || $_REQUEST['GraphID'] == 11 || $_REQUEST['GraphID'] == 13 || $_REQUEST['GraphID'] == 14 || $_REQUEST['GraphID'] == 15) {
 $bplot->value->Show();
 $bplot->value->SetFont(FF_ARIAL,FS_BOLD,8);
 $bplot->value->SetFormat('%d');
 $bplot->value->SetColor($EGRAPH['toptextcolor'],$EGRAPH['toptextcolor']);
}

$graph->title->Set($EGRAPH['title']);
$graph->xaxis->title->Set($EGRAPH['xtitle']);
$graph->yaxis->title->Set($EGRAPH['ytitle']);
$graph->yscale->SetGrace(10);
$graph->xaxis->SetTickLabels($datax);
$graph->yaxis->SetTitlemargin(50);

if($_REQUEST['GraphID'] == 4 || $_REQUEST['GraphID'] == 21 || $_REQUEST['GraphID'] == 22 || $_REQUEST['GraphID'] == 8 || $_REQUEST['GraphID'] == 12 || $_REQUEST['GraphID'] == 16) {
 $graph->yaxis->SetLabelFormatCallback('yScaleCallback');
}

if($_REQUEST['GraphID'] == 4 || $_REQUEST['GraphID'] == 21 || $_REQUEST['GraphID'] == 22 || $_REQUEST['GraphID'] == 8 || $_REQUEST['GraphID'] == 12 || $_REQUEST['GraphID'] == 16) {

 $Amount_Total = number_format(array_sum($ydata),2);

 // Create and add a new text
 $txt=new Text("Total of last 12 months\n\n$$Amount_Total");
 $txt->Pos(0.5,0.88,"center","center");
 $txt->SetFont(FF_FONT2,FS_BOLD);
 $txt->ParagraphAlign('cenetered');
 $txt->SetBox('lightblue','navy','gray');
 $txt->SetColor("black");
 $graph->AddText($txt);

 //function array_ereg($pattern, $haystack) {
 // for($i = 0; $i < count($haystack); $i++) {
 //  if (ereg($pattern, $haystack[$i]))
 //   return $i;
 //  }
 // return false;
 //}

 //$julysearch = array_ereg('Jul', $datax);
 
 //if(!$julysearch) { $julysearch = 0; }
 
 //for ($i = $julysearch; $i <= 13; $i++) {
 // $test += $ydata[$i];
 // $i++;
 //}

 
 //$Amount_Financial_Year = number_format($test,2);
 
 //// Create and add a new text
 //$txt2=new Text("Total From $datax[$julysearch] to $datax[11]\n\n$$Amount_Financial_Year");
 //$txt2->Pos(0.71,0.88,"center","center");
 //$txt2->SetFont(FF_FONT2,FS_BOLD);
 //$txt2->ParagraphAlign('cenetered');
 //$txt2->SetBox('lightblue','navy','gray');
 //$txt2->SetColor("black");
 //$graph->AddText($txt2);

}

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xgrid->Show(true,true);
$graph->ygrid->Show(true,true);



$graph->yaxis->SetColor($EGRAPH['yaxiscolor']);



$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.93,"center","center");

// Add the plot to the graph
if($_REQUEST['GraphID'] == 1 || $_REQUEST['GraphID'] == 19 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 13) {
 $graph->Add($lineplot);
}
$graph->Add($bplot);


// Display the graph
$graph->Stroke();

?>