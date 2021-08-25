<?

// Bar Graph

 include("global.php");
 
 include("includes-".$CONFIG['graphver']."/jpgraph.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_pie.php");
 include("includes-".$CONFIG['graphver']."/jpgraph_pie3d.php");

// Get The Data out.

 if($_GET['graphdata']) {
  $graph_data = $_GET['graphdata'];
 } else {
  $graph_data = $_POST['graphdata'];
 }

 $query_date = date("Y-m", mktime(1,1,1,date("m"),date("d"),date("Y")));
 $query_date2 = date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")));
 $query_date3 = date("Y-m", mktime(1,1,1,date("m")-2,date("d"),date("Y")));
 $query_date4 = date("Y-m", mktime(1,1,1,date("m")-3,date("d"),date("Y")));

 if($graph_data == "1") {
  
  $query_insert = "(datejoined like '$query_date-%' or datejoined like '$query_date2-%')"; 

 } elseif($graph_data == "2") {
 
  $query_insert = "(datejoined like '$query_date-%' or datejoined like '$query_date2-%' or datejoined like '$query_date3-%')"; 

 } elseif($graph_data == "3") {
 
  $query_insert = "(datejoined like '$query_date-%' or datejoined like '$query_date2-%' or datejoined like '$query_date3-%' or datejoined like '$query_date4-%')"; 

 }
 
 $query = dbRead("select categories.classification, count(categories.catid) as DB_Data from members, categories, mem_categories where (members.memid = mem_categories.memid) and (mem_categories.category = categories.catid) and categories.classification != 0 and members.CID = 1 and $query_insert and categories.catid != 0 group by categories.classification order by classification");
 
while($row1 = mysql_fetch_assoc($query)) {

 $data1[] = $row1['DB_Data'];
 $datax[] = get_classification($row1['classification']);

}

// Some data
$data = array(
	
    $data1

);

$piepos = array(0.5,0.68);
$titles = array('Classification');

// A new graph
$graph = new PieGraph(640,480,'auto');

// Specify margins since we put the image in the plot area
$graph->img->SetMargin(1,5,0,6);
$graph->SetShadow();

// Setup title
$graph->title->Set("Member Growth by Category Classification");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,18);
$graph->title->SetColor('black');
$graph->title->SetMargin(5);


// Create the plots
    $d = "data1";
    $p = new PiePlot3D($data1);

// Position the four pies
    $p->SetCenter($piepos[0],$piepos[1]);


// Set the titles
    $p->title->Set($titles[0]);
    $p->title->SetColor('black');
    $p->title->SetFont(FF_VERDANA,FS_BOLD,14);

// Label font and color setup
    $p->value->SetFont(FF_VERDANA,FS_NORMAL,10);
    $p->value->SetColor('black');

// Show the percetages for each slice

	$p->SetLabelType(PIE_VALUE_ABS);
	$p->value->SetFormat("%d");
    $p->value->HideZero();
    $p->value->Show();


// Size of pie in fraction of the width of the graph

    $p->SetSize(0.4);

// Format the border around each slice


    $p->ShowBorder();
    $p->ExplodeSlice(0);

// Use one legend for the whole graph

$p->SetLegends($datax);
$graph->legend->Pos(0.05,0.1);

    $graph->Add($p);

$graph->Stroke();

function get_classification($number) {

 switch($number) {
  case "1": return "Wholesale Goods"; break;
  case "2": return "Personal Services"; break;
  case "3": return "Manufacturing"; break;
  case "4": return "Finance, Property Services"; break;
  case "5": return "Building / Construction Services"; break;
  case "6": return "Community Services"; break;
  case "7": return "Tourism"; break;
  case "8": return "Services"; break;
  case "9": return "Retail Goods"; break;
 }
 
}

?>