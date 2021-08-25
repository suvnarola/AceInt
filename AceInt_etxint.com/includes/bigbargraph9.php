<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_bar.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_log.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_line.php");

// set the variables for each graph.

 $query = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as DB_Data2 from members where licensee = '".$_POST['area']."' group by DB_Data2 DESC limit 1,12");
 $query3 = dbRead("select count(memid) as DB_Data, extract(year_month from members.datejoined) as DB_Data2 from members where licensee = '".$_POST['area']."' group by DB_Data2 DESC limit 13,100000000");
 $query2 = dbRead("select * from area where FieldID = '".$_POST['area']."'");
 $row2 = mysql_fetch_assoc($query2);

 $EGRAPH['title'] = "Member Growth Projection - ".$_POST['graphdata']." Month Average - ".$_POST['graphdata2']." Month Projection - ".$row2['place'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Member Growth";
 $EGRAPH['toptextcolor'] = "black";

// make the data array.

while($row3 = mysql_fetch_assoc($query3)) {
  
  $Cumulative = $Cumulative + $row3['DB_Data'];
  
}

while($row = mysql_fetch_assoc($query)) {
  
  $Cumulative = $Cumulative + $row['DB_Data'];
  
  $ydata[] = $Cumulative;
  $average_array[] = $row['DB_Data'];
  
}

 //$ydata = array_reverse($ydata);

 $count = 11 - $_POST['graphdata'];
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {

  array_pop($average_array);
 
 }
 
// get average members growth per month based on the previous months starting from last month.

 $average_membergrowth = number_format(array_sum($average_array)/$_POST['graphdata']);
 
 $count = $_POST['graphdata2']-1;
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {
  
  $average_membergrowth = number_format($average_membergrowth*1.066,0,'.','');

  $ydata[] = $ydata[11+$i]+$average_membergrowth;

 }
  
// make the month array.

 $count = 11 + $_POST['graphdata2'];
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {

  $datax[] = date("M y",mktime(1,1,1,(date("n")-$count-1+$_POST['graphdata2'])+$i,1,date("Y")));

 }

// some graphs require us to add the values manually because of different queries.

 $temp1 = sizeof($ydata);

 if($temp1 > 12) {
  $offset = (39*($temp1-12));
 } else {
  $offset = 0;
 }

 $width = 595 + $offset;

// Create the graph. These two calls are always required
$graph = new Graph($width,660,"auto");    
$graph->img->SetMargin(60,60,20,70);
$graph->SetScale("textlin");
$graph->SetShadow();

// Create the linear plot
$lineplot=new LinePlot($ydata);

// Create a bar pot 
$bplot = new BarPlot($ydata);

$bplot->SetYBase($ydata[0]);

$bplot->SetFillColor($EGRAPH['barfillcolor']); 
$bplot->SetWidth(.7); 

$bplot->value->Show();
$bplot->value->SetFont(FF_ARIAL,FS_BOLD,8);
$bplot->value->SetFormat('%d');
$bplot->value->SetColor($EGRAPH['toptextcolor'],$EGRAPH['toptextcolor']);

$graph->title->Set($EGRAPH['title']);
$graph->xaxis->title->Set($EGRAPH['xtitle']);
$graph->yaxis->title->Set($EGRAPH['ytitle']);
$graph->yscale->SetGrace(10);
$graph->xaxis->SetTickLabels($datax);
$graph->yaxis->SetTitlemargin(50);
$graph->xaxis->SetLabelAngle(90);

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xgrid->Show(true,true);
$graph->ygrid->Show(true,true);

$lineplot->SetColor($EGRAPH['lineplotcolor']);
$lineplot->SetWeight(2);

$graph->yaxis->SetColor($EGRAPH['yaxiscolor']);

$lineplot->SetLegend($EGRAPH['lineplotlegend']);

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.93,"center","center");

// Add the plot to the graph
$graph->Add($bplot);

// Display the graph
$graph->Stroke();
?>