<?

// Bar Graph

if(!$_REQUEST['refreshed']) {
 
 $Count = 0;

 foreach($_REQUEST as $key => $value) {
  
  if($Count == 0) {
   $AO = "?";
  } else {
   $AO = "&";
  }
  if($key != "phpbb2mysql_data"){
   $NEWREQ .= "$AO$key=$value";
  }
  $Count++;
  
 }
 
 $NEWREQ .= "&refreshed=true";
 ?>
 <html>
 <head>
 <img src="<? print "".$_SERVER['SCRIPT_NAME']."$NEWREQ"; ?>">
 </head>
 </html>
 <?
 die;
}

 include("global.php");
 
 include("includes-$CONFIG[graphver]/jpgraph.php");
 include("includes-$CONFIG[graphver]/jpgraph_bar.php");
 include("includes-$CONFIG[graphver]/jpgraph_log.php");
 include("includes-$CONFIG[graphver]/jpgraph_line.php");

// set the variables for each graph.

if($_REQUEST['GraphID'] == 1) {

 $query = dbRead("SELECT sum(buy) AS DB_Data, extract(year_month FROM dis_date) AS date1 FROM transactions WHERE memid in(10528,10741,14416) AND dis_date like '".addslashes($_REQUEST['graphdata'])."-%' and details NOT LIKE  '%not proceed%' GROUP  BY date1 DESC limit 12");

 $EGRAPH['title'] = "Real Estate Amount for ".$_REQUEST['graphdata'];
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Real Estate Amount for ".$_REQUEST['graphdata'];
 $EGRAPH['toptextcolor'] = "black";

}

// make the data array.

  $ydata = Array();

while($row = mysql_fetch_assoc($query)) {

  $ydata[] = $row['DB_Data'];
  $Cumul += $row['DB_Data'];
  $datax[] = date("M y", mktime(1,1,1,substr($row['date1'], 4),1,substr($row['date1'], 0, 4)));

  $Month = substr($row['date1'], 4);
  $Year = substr($row['date1'], 0, 4);

}
 
 $ydata = array_reverse($ydata);
 $datax = array_reverse($datax);

 // some graphs require us to add the values manually because of different queries.

 if($_REQUEST['GraphID'] == 1345345345) {
  $new_ydata = $ydata;
  unset($ydata);
  $ydata = Array();
  foreach($new_ydata as $value) {
   $Cumul += $value;
   $ydata[] += $Cumul;
  } 
  $ydata2 = array_slice($ydata, -12, 12);
  $ydata = $ydata2;
 }

 if($_REQUEST['GraphID'] == 1) {
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
if($_REQUEST['GraphID'] == 1 || $_REQUEST['GraphID'] == 19 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 13) {
 // Create the linear plot
 $lineplot = new LinePlot($ydata);
 $lineplot->SetColor($EGRAPH['lineplotcolor']);
 $lineplot->SetWeight(2);
 $lineplot->SetLegend($EGRAPH['lineplotlegend']);
}

// Create a bar pot 
$bplot = new BarPlot($ydata);

if($_REQUEST['GraphID'] == 1 || $_REQUEST['GraphID'] == 19 || $_REQUEST['GraphID'] == 17 || $_REQUEST['GraphID'] == 18 || $_REQUEST['GraphID'] == 2 || $_REQUEST['GraphID'] == 20 || $_REQUEST['GraphID'] == 3 || $_REQUEST['GraphID'] == 4 || $_REQUEST['GraphID'] == 5 || $_REQUEST['GraphID'] == 6 || $_REQUEST['GraphID'] == 7 || $_REQUEST['GraphID'] == 8 || $_REQUEST['GraphID'] == 9 || $_REQUEST['GraphID'] == 10 || $_REQUEST['GraphID'] == 11 || $_REQUEST['GraphID'] == 12 || $_REQUEST['GraphID'] == 13 || $_REQUEST['GraphID'] == 14 || $_REQUEST['GraphID'] == 15 || $_REQUEST['GraphID'] == 16) {
 $bplot->SetYBase($ydata[0]);
}

$bplot->SetFillColor($EGRAPH['barfillcolor']); 
$bplot->SetWidth(.7); 

if($_REQUEST['GraphID'] == 14433344) {
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

if($_REQUEST['GraphID'] == 1) {
 $graph->yaxis->SetLabelFormatCallback('yScaleCallback');
}

if($_REQUEST['GraphID'] == 1) {

 $Amount_Total = number_format(array_sum($ydata),2);
 //$Amount_Total = number_format($Cumul,2);

 // Create and add a new text
 $txt=new Text("Total\n\n$$Amount_Total");
 $txt->Pos(0.5,0.88,"center","center");
 $txt->SetFont(FF_FONT2,FS_BOLD);
 $txt->ParagraphAlign('cenetered');
 $txt->SetBox('lightblue','navy','gray');
 $txt->SetColor("black");
 $graph->AddText($txt);

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
if($_REQUEST['GraphID'] == 15454545) {
 $graph->Add($lineplot);
}
$graph->Add($bplot);


// Display the graph
$graph->Stroke();

?>