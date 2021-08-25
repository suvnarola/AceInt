<?

 /**
  * Graph for plotting User Usage for a one day period.
  *
  * version 0.01
  */

 include("global.php");
 include("includes-1.16/jpgraph.php");
 include("includes-1.16/jpgraph_gantt.php");

 $SessionSQL = dbRead("select tbl_kpi_login_history.*, sec_to_time((unix_timestamp(max(tbl_kpi.Date))+".$_SESSION['Country']['timezone'].")-(unix_timestamp(tbl_kpi_login_history.Date))+".$_SESSION['Country']['timezone'].") as Diff, (unix_timestamp(tbl_kpi_login_history.Date)+".$_SESSION['Country']['timezone'].") as Date from tbl_kpi_login_history, tbl_kpi where (tbl_kpi_login_history.FieldID = tbl_kpi.LoginID) and tbl_kpi_login_history.UserID = ".$_REQUEST['UserID']." and tbl_kpi_login_history.Date like '".$_REQUEST['Date']."%' group by tbl_kpi_login_history.FieldID Order By tbl_kpi_login_history.Date ASC","etxint_log");

 $UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = " . $_REQUEST['UserID']);
 $UserRow = mysql_fetch_assoc($UserSQL);

 $SessionCount = 0;

 while($SessionRow = mysql_fetch_assoc($SessionSQL)) {

  $SecondsTemp = explode(":", $SessionRow['Diff']);
  $Seconds = (3600*$SecondsTemp[0]) + (60*$SecondsTemp[1]) + $SecondsTemp[2];
  $DisplaySession = $SessionCount + 1;

  $GraphData[] = Array($SessionCount, "Session " . $DisplaySession, date("ymd H:i:s", $SessionRow['Date']), date("ymd H:i:s", $SessionRow['Date']+$Seconds));

  $SessionCount++;

 }

$data = $GraphData;

//var_dump($data);
//die;

// Basic graph parameters
$graph = new GanttGraph();
$graph->SetMarginColor('darkgreen@0.8');
$graph->SetColor('white');

// We want to display day, hour and minute scales
$graph->ShowHeaders(GANTT_HDAY | GANTT_HHOUR | GANTT_HMIN);

// Setup day format
$graph->scale->day->SetBackgroundColor('lightyellow:1.5');
$graph->scale->day->SetFont(FF_ARIAL);
$graph->scale->day->SetStyle(DAYSTYLE_SHORTDAYDATE1);

// Setup hour format
$graph->scale->hour->SetIntervall(1);
$graph->scale->hour->SetBackgroundColor('lightyellow:1.5');
$graph->scale->hour->SetFont(FF_FONT0);
$graph->scale->hour->SetStyle(HOURSTYLE_H24);
$graph->scale->hour->grid->SetColor('gray:0.8');

// Setup minute format
$graph->scale->minute->SetIntervall(30);
$graph->scale->minute->SetBackgroundColor('lightyellow:1.5');
$graph->scale->minute->SetFont(FF_FONT0);
$graph->scale->minute->SetStyle(MINUTESTYLE_MM);
$graph->scale->minute->grid->SetColor('lightgray');

$graph->scale->tableTitle->Set('Sessions ');
$graph->scale->tableTitle->SetFont(FF_ARIAL,FS_NORMAL,12);
$graph->scale->SetTableTitleBackground('darkgreen@0.6');
$graph->scale->tableTitle->Show(true);

$graph->title->Set("Sessions for ".$UserRow['Name']." for " . date("jS M Y", strtotime($_REQUEST['Date'])));
$graph->title->SetColor('darkgray');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);


for($i=0; $i<count($data); ++$i) {
    $bar = new GanttBar($data[$i][0],$data[$i][1],$data[$i][2],$data[$i][3]);
    if( count($data[$i])>4 )
	$bar->title->SetFont($data[$i][4],$data[$i][5],$data[$i][6]);
    $bar->SetPattern(BAND_RDIAG,"yellow");
    $bar->SetFillColor("gray");
    $graph->Add($bar);
}

$graph->Stroke();

?>


