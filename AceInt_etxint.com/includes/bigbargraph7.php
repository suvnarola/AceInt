<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_bar.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_log.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_line.php");

// set the variables for each graph.

  $query_date = date("Y-m", mktime(1,1,1,date("m"),date("d"),date("Y")));
  $query_date2 = date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")));
  $query_date3 = date("Y-m", mktime(1,1,1,date("m")-2,date("d"),date("Y")));
  $query_date4 = date("Y-m", mktime(1,1,1,date("m")-3,date("d"),date("Y")));

 if($_POST['graphdata'] == "1") {
  
  $query_insert = "(datejoined like '$query_date-%' or datejoined like '$query_date2-%')"; 
  
 } elseif($_POST['graphdata'] == "2") {
 
  $query_insert = "(datejoined like '$query_date-%' or datejoined like '$query_date2-%' or datejoined like '$query_date3-%')"; 

 } elseif($_POST['graphdata'] == "3") {
 
  $query_insert = "(datejoined like '$query_date-%' or datejoined like '$query_date2-%' or datejoined like '$query_date3-%' or datejoined like '$query_date4-%')"; 

 }
 
 $query = dbRead("select categories.category as DB_Data1, count(categories.catid) as DB_Data2 from members, categories, mem_categories where (members.memid = mem_categories.memid) and (mem_categories.category = categories.catid) and $query_insert and members.CID = ".$_SESSION['User']['CID']." and categories.catid != 0 group by categories.catid order by ".$_POST['graphdata2']);
 
 $EGRAPH['title'] = "Monthly Members by Category - ".$_POST['graphdata']." Month(s)";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Monthly Members by Category - ".$_POST['graphdata']." Months";
 $EGRAPH['toptextcolor'] = "black";

// make the data array.

while($row = mysql_fetch_assoc($query)) {
 
  $ydata[] = $row['DB_Data2'];
  
  $datax[] = $row['DB_Data1'];
  
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
$graph = new Graph($width,560,"auto");    
$graph->img->SetMargin(60,60,20,260);
$graph->SetScale("textlin");
$graph->SetShadow();

// Create the linear plot
$lineplot=new LinePlot($ydata);

// Create a bar pot 
$bplot = new BarPlot($ydata);

$bplot->SetYBase(0);

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