<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_bar.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_log.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_line.php");

// set the variables for each graph.

 $EGRAPH['title'] = "Membership signup history";
 $EGRAPH['barfillcolor'] = "orange";
 $EGRAPH['xtitle'] = "";
 $EGRAPH['ytitle'] = "";
 $EGRAPH['lineplotcolor'] = "deepskyblue";
 $EGRAPH['yaxiscolor'] = "blue";
 $EGRAPH['lineplotlegend'] = "Member Activity";
 $EGRAPH['toptextcolor'] = "black";

 $foo = 0;
 $query = dbRead("select licensee from members where datejoined like '".date("Y-m")."-%' group by licensee");
 while($row = mysql_fetch_assoc($query)) {
  
  if($foo == 0) {
 
   $data_query .= "area.FieldID != '".$row['licensee']."'";
  
  } else {
  
   $data_query .= " and area.FieldID != '".$row['licensee']."'";
   
  }
  
  $foo++;
 }
 
 // get the field ids that we need to build the array with

 $query2 = dbRead("select FieldID from area, members where $data_query group by FieldID");

// make the data array.

while($row = mysql_fetch_assoc($query2)) {

  $temprow1 = dbRead("select count(memid) as DB_Data from members where datejoined like '".date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")))."-%' and licensee = '".$row['FieldID']."'");
  $temprow2 = dbRead("select count(memid) as DB_Data from members where datejoined like '".date("Y-m", mktime(1,1,1,date("m")-2,date("d"),date("Y")))."-%' and licensee = '".$row['FieldID']."'");
  $temprow3 = dbRead("select count(memid) as DB_Data from members where datejoined like '".date("Y-m", mktime(1,1,1,date("m")-3,date("d"),date("Y")))."-%' and licensee = '".$row['FieldID']."'");

  $res_temprow1 = mysql_fetch_assoc($temprow1);
  $res_temprow2 = mysql_fetch_assoc($temprow2);
  $res_temprow3 = mysql_fetch_assoc($temprow3);

  $ydata1[] = $res_temprow1['DB_Data'];
  $ydata2[] = $res_temprow2['DB_Data'];
  $ydata3[] = $res_temprow3['DB_Data'];
  
  $query3 = dbRead("select place from area where FieldID='".$row['FieldID']."'");
  $row3 = mysql_fetch_assoc($query3);
  $datax[] = $row3['place'];
  
}

// do the width of the graph.

 if(sizeof($ydata1) > 12) {
  $offset = (78*(sizeof($ydata1)-12));
 } else {
  $offset = 0;
 }

 $width = 595 + $offset;

// Create the graph. These two calls are always required
$graph = new Graph($width,600,"auto");    
$graph->img->SetMargin(60,60,20,230);
$graph->SetScale("textlin");
$graph->SetShadow();

// Create 3 bar plots

$bplot = new BarPlot($ydata1);
$bplot->SetYBase($ydata[0]);
$bplot->SetFillColor($EGRAPH['barfillcolor']); 
$bplot->SetWidth(.7); 
$bplot->value->Show();
$bplot->value->SetFont(FF_ARIAL,FS_BOLD,8);
$bplot->value->SetFormat('%d');
$bplot->value->SetColor($EGRAPH['toptextcolor'],$EGRAPH['toptextcolor']);

$bplot2 = new BarPlot($ydata2);
$bplot2->SetYBase($ydata[0]);
$bplot2->SetFillColor($EGRAPH['barfillcolor']); 
$bplot2->SetWidth(.7); 
$bplot2->value->Show();
$bplot2->value->SetFont(FF_ARIAL,FS_BOLD,8);
$bplot2->value->SetFormat('%d');
$bplot2->value->SetColor($EGRAPH['toptextcolor'],$EGRAPH['toptextcolor']);

$bplot3 = new BarPlot($ydata3);
$bplot3->SetYBase($ydata[0]);
$bplot3->SetFillColor($EGRAPH['barfillcolor']); 
$bplot3->SetWidth(.7); 
$bplot3->value->Show();
$bplot3->value->SetFont(FF_ARIAL,FS_BOLD,8);
$bplot3->value->SetFormat('%d');
$bplot3->value->SetColor($EGRAPH['toptextcolor'],$EGRAPH['toptextcolor']);

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
$graph->yaxis->SetColor($EGRAPH['yaxiscolor']);
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.93,"center","center");

// Add the plot to the graph
$gbplot = new GroupBarPlot(array($bplot,$bplot2,$bplot3));
$graph->Add($gbplot);

// Display the graph
$graph->Stroke();
?>