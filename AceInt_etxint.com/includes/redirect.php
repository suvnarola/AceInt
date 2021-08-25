<?

if(!$IncludedRedirect) {
 
 include("global.php");

}

if(isset($_REQUEST['emsa'])) {

 if($_SESSION['User']['PrintView'] == 1)  {
	$tab = "tab5";
 }  else  {
	$tab = "tab1";
 }
 
 $CheckSQL = dbRead("select count(memid) as `Check` from members where memid = '" . addslashes($_REQUEST['data']) . "'");
 $CheckRow = @mysql_fetch_assoc($CheckSQL);
 
 if($CheckRow['Check'] == 1) {
 
  add_kpi("2",$_REQUEST['data']);
  Header("Location: /body.php?page=member_edit&Client=".$_REQUEST['data']."&pageno=1&tab=$tab");
  die;
 } else {
 
  Header("Location: /body.php?page=mem_search&data=".addslashes($_REQUEST['data'])."&Error=true");
  die;
 }
 
}

if(isset($_REQUEST['pcheque'])) {
 Header("Location: /includes/printcheque.php?memid=".$_REQUEST['data']."");
 die;
}

if($_REQUEST['vssa']) {

 $CheckSQL = dbRead("select count(memid) as `Check` from members where memid = '" . addslashes($_REQUEST['data']) . "'");
 $CheckRow = @mysql_fetch_assoc($CheckSQL);
 
 if($CheckRow['Check'] == 1) {
 	
  add_kpi("2",$_REQUEST['data']);
  Header("Location: /body.php?page=member_edit&Client=".$_REQUEST['data']."&pageno=1&tab=tab7&currentmonth=".date("n")."&numbermonths=1&currentyear=".date("Y")."&DisplayStatement=1");
  die;
 
 } else {
 
  Header("Location: /body.php?page=mem_search&data=".addslashes($_REQUEST['data'])."&Error=true");
  die;
 }
 
}

if($_REQUEST['edit']) {
 Header("Location: /body.php?page=changemember&edit2=true&memid=".$_REQUEST['data']."");
 die;
}

if($_REQUEST['addnew']) {
 Header("Location: /body.php?page=addmember&edit2=true&memid=".$_REQUEST['data']."");
 die;
}

if($_REQUEST['redirtransfer']) {
 Header("Location: /body.php?page=transfer");
 die;
}

?>
