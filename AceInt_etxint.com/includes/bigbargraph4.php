<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_pie.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_pie3d.php");

// Get The Data out.

//$state1 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'QLD') group by date1 order by date1 desc limit 6");
//$state2 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'WA') group by date1 order by date1 desc limit 6");
//$state3 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'SA') group by date1 order by date1 desc limit 6");
//$state4 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'VIC') group by date1 order by date1 desc limit 6");
//$state5 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'ACT') group by date1 order by date1 desc limit 6");
//$state6 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'NSW') group by date1 order by date1 desc limit 6");
//$state7 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, area where (members.licensee = area.FieldID and area.state = 'TAS') group by date1 order by date1 desc limit 6");

$state1 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'QLD' group by date1 order by date1 desc limit 6");
$state2 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'WA' group by date1 order by date1 desc limit 6");
$state3 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'SA' group by date1 order by date1 desc limit 6");
$state4 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'VIC' group by date1 order by date1 desc limit 6");
$state5 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'ACT' group by date1 order by date1 desc limit 6");
$state6 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'NSW' group by date1 order by date1 desc limit 6");
$state7 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members, tbl_area_physical, tbl_area_regional, tbl_area_states where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.StateName = 'TAS' group by date1 order by date1 desc limit 6");

$data1 = Array();
$data3 = Array();
$data4 = Array();
$data5 = Array();
$data6 = Array();
$data7 = Array();

while($row1 = mysql_fetch_assoc($state1)) {

 $data1[] = $row1['DB_Data'];
 $OD1[] = $row1['date1'];

}

while($row2 = mysql_fetch_assoc($state2)) {

 $data2[] = $row2['DB_Data'];
 $OD2[] = $row2['date1'];

}

while($row3 = mysql_fetch_assoc($state3)) {

 $data3[] = $row3['DB_Data'];
 $OD3[] = $row3['date1'];

}

while($row4 = mysql_fetch_assoc($state4)) {

 $data4[] = $row4['DB_Data'];
 $OD4[] = $row4['date1'];

}

while($row5 = mysql_fetch_assoc($state5)) {

 $data5[] = $row5['DB_Data'];
 $OD5[] = $row5['date1'];

}

while($row6 = mysql_fetch_assoc($state6)) {

 $data6[] = $row6['DB_Data'];
 $OD6[] = $row6['date1'];

}

while($row7 = mysql_fetch_assoc($state7)) {

 $data7[] = $row7['DB_Data'];
 $OD7[] = $row7['date1'];

}

// Stupid months with 0 members.

$this_month = date("Ym");

if($OD1[0] != $this_month) {
 array_pop($data1);
 array_unshift($data1, "0");
}

if($OD2[0] != $this_month) {
 array_pop($data2);
 array_unshift($data2, "0");
}

if($OD3[0] != $this_month) {
 array_pop($data3);
 array_unshift($data3, "0");
}

if($OD4[0] != $this_month) {
 array_pop($data4);
 array_unshift($data4, "0");
}

if($OD5[0] != $this_month) {
 array_pop($data5);
 array_unshift($data5, "0");
}

if($OD6[0] != $this_month) {
 array_pop($data6);
 array_unshift($data6, "0");
}

if($OD7[0] != $this_month) {
 array_pop($data7);
 array_unshift($data7, "0");
}


// Some data
$data = array(
	
	// Queensland
    $data1,
    
    // Western Australia
    $data2,
    
    // South Australia
    $data3,
    
    // Victoria
    $data4,
    
    // Australia Capital Territory
    $data5,
    
    // New South Wales
    $data6,
    
    // Tasmania
    $data7,
    
    );

$piepos = array(0.75,0.25, 0.15,0.25, 0.40,0.76, 0.80,0.80, 0.85,0.60, 0.85,0.44, 0.57,0.88);
$titles = array('Queensland','Western Australia','South Australia','Victoria','ACT','New South Wales','Tasmania');

$n = count($piepos)/2;
 
// A new graph
$graph = new PieGraph(800,640,'auto');

// Specify margins since we put the image in the plot area
$graph->img->SetMargin(1,5,0,6);
$graph->SetShadow();

// Setup background
$graph->SetBackgroundImage('../images/australia.jpg',BGIMG_FILLPLOT,'jpg');

// Setup title
$graph->title->Set("Member Growth by State - Australia");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,16);
$graph->title->SetColor('black');
$graph->title->SetMargin(5);


$p = array();
// Create the plots
for( $i=0; $i < $n; ++$i ) {
    $d = "data$i";
    $p[] = new PiePlot3D($data[$i]);
}

// Position the four pies
for( $i=0; $i < $n; ++$i ) {
    $p[$i]->SetCenter($piepos[2*$i],$piepos[2*$i+1]);
}

// Set the titles
for( $i=0; $i < $n; ++$i ) {
    $p[$i]->title->Set($titles[$i]);
    $p[$i]->title->SetColor('black');
    $p[$i]->title->SetFont(FF_VERDANA,FS_BOLD,10);
}

// Label font and color setup
for( $i=0; $i < $n; ++$i ) {
    $p[$i]->value->SetFont(FF_VERDANA,FS_NORMAL,7);
    $p[$i]->value->SetColor('black');
    $p[$i]->SetTheme('sand');
}

// Show the percetages for each slice
for( $i=0; $i < $n; ++$i ) {
	$p[$i]->SetLabelType(PIE_VALUE_ABS);
	$p[$i]->value->SetFormat("%d");
    $p[$i]->value->HideZero();
    $p[$i]->value->Show();
}

// Label format
//for( $i=0; $i < $n; ++$i ) {
//    $p[$i]->value->SetFormat("%01.1f%%");
//}

// Size of pie in fraction of the width of the graph
for( $i=0; $i < $n; ++$i ) {
    $p[$i]->SetSize(0.1);
}

// Format the border around each slice


for( $i=0; $i < $n; ++$i ) {
    $p[$i]->ShowBorder();
    $p[$i]->ExplodeSlice(0);
}

// Use one legend for the whole graph

$months = array(date("M", mktime(1,1,1,date("n")-0,1,date("Y"))),date("M", mktime(1,1,1,date("n")-1,1,date("Y"))),date("M", mktime(1,1,1,date("n")-2,1,date("Y"))),date("M", mktime(1,1,1,date("n")-3,1,date("Y"))),date("M", mktime(1,1,1,date("n")-4,1,date("Y"))),date("M", mktime(1,1,1,date("n")-5,1,date("Y"))));

array_reverse($months);

$p[0]->SetLegends($months);
$graph->legend->Pos(0.05,0.1);

for( $i=0; $i < $n; ++$i ) {
    $graph->Add($p[$i]);
}

$graph->Stroke();
?>