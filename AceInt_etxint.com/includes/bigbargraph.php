<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_bar.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_log.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_line.php");

// set the variables for each graph.

 $query = dbRead("select count(memid) as DB_Data, licensee from members where datejoined like '".addslashes($_REQUEST[graphdata])."-%' group by licensee");

 $EGRAPH['title'] = "Monthly Members - ".$_REQUEST['graphdata'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Monthly Members - ".$_POST['graphdata'];
 $EGRAPH['toptextcolor'] = "black";


// make the data array.

while($row = mysql_fetch_assoc($query)) {

  $ydata[] = $row['DB_Data'];
  
  $query2 = dbRead("select place from area where FieldID = '".$row['licensee']."'");
  $row2 = mysql_fetch_assoc($query2);
  $datax[] = $row2['place'];
  
}

// some graphs require us to add the values manually because of different queries.

 if(sizeof($ydata) > 12) {
  $offset = (39*(sizeof($ydata)-12));
 } else {
  $offset = 0;
 }

 $width = 595 + $offset;

// Create the graph. These two calls are always required
$graph = new Graph($width,560,"auto");    
$graph->img->SetMargin(60,60,30,200);
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
$bplot->value->SetFont(FF_VERDANA,FS_BOLD,8);
$bplot->value->SetFormat('%d');
$bplot->value->SetColor($EGRAPH['toptextcolor'],$EGRAPH['toptextcolor']);

$graph->title->Set($EGRAPH['title']);
$graph->xaxis->title->Set($EGRAPH['xtitle']);
$graph->yaxis->title->Set($EGRAPH['ytitle']);
$graph->yscale->SetGrace(10);
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL, 8);
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelMargin(10);
$graph->yaxis->SetTitlemargin(50);
$graph->xaxis->SetLabelAngle(90);

$graph->title->SetFont(FF_VERDANA,FS_BOLD);
$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD);
$graph->xaxis->title->SetFont(FF_VERDANA,FS_BOLD);
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