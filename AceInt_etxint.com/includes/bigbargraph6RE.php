<?

// Bar Graph

 include("global.php");
 
 include("includes-$CONFIG[graphver]/jpgraph.php");
 include("includes-$CONFIG[graphver]/jpgraph_bar.php");
 include("includes-$CONFIG[graphver]/jpgraph_log.php");
 include("includes-$CONFIG[graphver]/jpgraph_line.php");

// set the variables for each graph.

 $query = dbRead("SELECT sum(buy) AS DB_Data, extract(year_month FROM dis_date) AS date1 FROM transactions WHERE (memid IN (10528,14416,10741)) AND details NOT LIKE  '%not proceed%' GROUP  BY date1 DESC");

 $EGRAPH['title'] = "Real Estate Amount Total";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Member Growth";
 $EGRAPH['toptextcolor'] = "black";

// make the data array.

while($row = mysql_fetch_assoc($query)) {
  
  $ydata[] = $row['DB_Data'];
  $datax[] = date("M Y", mktime(1,1,1,substr($row['date1'], 4),1,substr($row['date1'], 0, 4)));

}

 $ydata = array_reverse($ydata);
 $datax = array_reverse($datax);

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
$graph->img->SetMargin(60,60,20,170);
$graph->SetScale("textlin");
$graph->SetShadow();

// Callback function for Y-scale
function yScaleCallback($file_size) {

 if(!$file_size) { $file_size = 0; }
 
 if ($file_size >= 1073741824) { 
	$file_size = round($file_size/1000000000, 2). " Bil"; 
 } elseif ($file_size >= 1048576) { 
	$file_size = round($file_size/1000000, 2). " Mil"; 
 } elseif ($file_size >= 1024) { 
	$file_size = round($file_size/1000, 2). " Tho"; 
 } else { 
	$file_size = round($file_size, 2). ""; 
 }

return $file_size;

}

// Create the linear plot
$lineplot=new LinePlot($ydata);

// Create a bar pot 
$bplot = new BarPlot($ydata);

$bplot->SetYBase($ydata[0]);

$bplot->SetFillColor($EGRAPH[barfillcolor]); 
$bplot->SetWidth(.7); 

//$bplot->value->Show();
//$bplot->value->SetFont(FF_ARIAL,FS_BOLD,8);
//$bplot->value->SetFormat('%d');
//$bplot->value->SetColor($EGRAPH[toptextcolor],$EGRAPH[toptextcolor]);

 $Amount_Total = number_format(array_sum($ydata),2);

 // Create and add a new text
 $txt=new Text("Total\n\n$$Amount_Total");
 $txt->Pos(0.5,0.9,"center","center");
 $txt->SetFont(FF_FONT2,FS_BOLD);
 $txt->ParagraphAlign('cenetered');
 $txt->SetBox('lightblue','navy','gray');
 $txt->SetColor("black");
 $graph->AddText($txt);

$graph->title->Set($EGRAPH[title]);
$graph->xaxis->title->Set($EGRAPH[xtitle]);
$graph->yaxis->title->Set($EGRAPH[ytitle]);
$graph->yscale->SetGrace(10);
$graph->xaxis->SetTickLabels($datax);
$graph->yaxis->SetTitlemargin(50);
$graph->xaxis->SetLabelAngle(90);

$graph->yaxis->SetLabelFormatCallback('yScaleCallback');

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xgrid->Show(true,true);
$graph->ygrid->Show(true,true);

$lineplot->SetColor($EGRAPH[lineplotcolor]);
$lineplot->SetWeight(2);

$graph->yaxis->SetColor($EGRAPH[yaxiscolor]);

$lineplot->SetLegend($EGRAPH[lineplotlegend]);

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.93,"center","center");

// Add the plot to the graph
$graph->Add($bplot);

// Display the graph
$graph->Stroke();
?>