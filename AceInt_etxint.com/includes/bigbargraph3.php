<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_pie.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_pie3d.php");

// Get The Data out.

$country1 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '3' group by date1 order by date1 desc limit 6");
$country2 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '6' group by date1 order by date1 desc limit 6");
$country3 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '1' group by date1 order by date1 desc limit 6");
$country4 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '8' group by date1 order by date1 desc limit 6");
$country5 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '9' group by date1 order by date1 desc limit 6");
$country6 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '10' group by date1 order by date1 desc limit 6");
$country7 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '12' group by date1 order by date1 desc limit 6");
$country8 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '15' group by date1 order by date1 desc limit 6");
$country9 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as date1 from members where CID = '2' group by date1 order by date1 desc limit 6");

while($row1 = mysql_fetch_assoc($country1)) {

 $data1[] = $row1['DB_Data'];

}

while($row2 = mysql_fetch_assoc($country2)) {

 $data2[] = $row2['DB_Data'];

}

while($row3 = mysql_fetch_assoc($country3)) {

 $data3[] = $row3['DB_Data'];

}

while($row4 = mysql_fetch_assoc($country4)) {

 $data4[] = $row4['DB_Data'];

}

while($row5 = mysql_fetch_assoc($country5)) {

 $data5[] = $row5['DB_Data'];

}

while($row6 = mysql_fetch_assoc($country6)) {

 $data6[] = $row6['DB_Data'];

}

while($row7 = mysql_fetch_assoc($country7)) {

 $data7[] = $row7['DB_Data'];

}

while($row8 = mysql_fetch_assoc($country8)) {

 $data8[] = $row8['DB_Data'];

}

while($row9 = mysql_fetch_assoc($country9)) {

 $data9[] = $row9['DB_Data'];

}

// Some data
$data = array(
	
	// Netherlands
    $data1,
    
    // Malaysia
    $data2,
    
    // Australia
    $data3,
    
    // Vietnam
    $data4,
    
    // United Kingdom
    $data5,
    
    // Belgium
    $data6,
    
    // Belgium
    $data7,
    
    // Spain
    $data8,
    
    // New Zealand
    $data9
    
    );

$piepos = array(0.55,0.2, 0.87,0.55, 0.63,0.84, 0.87,0.38, 0.13,0.2, 0.13,0.55, 0.38,0.84, 0.13,0.84, 0.865,0.84);
$titles = array('Netherlands','Malaysia','Australia','Vietnam','United Kingdom','Belgium','Hungary','Spain','New Zealand');

$n = count($piepos)/2;
 
// A new graph
$graph = new PieGraph(800,640,'auto');

// Specify margins since we put the image in the plot area
$graph->img->SetMargin(1,5,0,6);
$graph->SetShadow();

// Setup background
$graph->SetBackgroundImage('../images/worldx.jpg',BGIMG_FILLPLOT,'jpg');

// Setup title
$graph->title->Set("Member Growth by Country");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,18);
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