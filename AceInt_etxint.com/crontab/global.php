<?

// Globals for System scripts.

$CONFIG['db_name'] = "etradebanc";
$CONFIG['db_host'] = "104.192.31.75";
$CONFIG['db_user'] = "empireDB";
$CONFIG['db_pass'] = "1emPire82";
$CONFIG['db_linkid'] = @mysql_connect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

$CONFIG['auth_enabled'] = false;
$CONFIG['auth_realm'] = "E Banc Administration.";
$CONFIG['auth_failed'] = "/virt/web-01/errormsg/auth_1.htm";
$CONFIG['auth_suspend'] = "/virt/web-01/errormsg/auth_2.htm";
$CONFIG['auth_user'] = "";
$CONFIG['auth_pass'] = "";
$CONFIG['DEBUG'] = true;

$db = $CONFIG['db_name'];
$linkid = $CONFIG['db_linkid'];

function dbRead($SQLQuery,$database = false) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error()); }

	mysql_select_db($database);

	$rsid = mysql_query($SQLQuery, $CONFIG['db_linkid']);
	if ($rsid == False) { dbReportError(mysql_errno(),mysql_error()); }

	return($rsid);
}

function dbWrite($SQLQuery,$database = false,$ReturnID = False) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error()); }

	mysql_select_db($database);

	$rsid = mysql_query($SQLQuery, $CONFIG['db_linkid']);
	if ($rsid == False) { dbReportError(mysql_errno(),mysql_error()); }
	if ($ReturnID == True) {
		$ReturnID = mysql_insert_id($CONFIG['db_linkid']);
	} else {
		$ReturnID = True;
	}

	return($ReturnID);
}


function dbReportError($ErrorNumber,$ErrorMsg) {

	print "An error occured while connecting to the database<br>";
	print "<strong>$ErrorNumber</strong>";
	print $ErrorMsg;
	exit;
}

 function get_non_included_accounts($CID) {

  $SQLQuery = dbRead("select * from country where countryID = '$CID'");
  $CRow = mysql_fetch_assoc($SQLQuery);

  $DATA[rereserve] = explode(",", $CRow[rereserve]);
  $DATA[trustacc] = explode(",", $CRow[trustacc]);

  $Count = 0;

  foreach($DATA as $DATA_Key => $DATA_Value) {


   foreach($DATA_Value as $key => $value) {

    if($Count == 0) {
     $AO = "";
    } else {
     $AO = ",";
    }

    $NI .= "$AO$value";

    $Count++;

   }

  }

  return $NI;

 }

?>
