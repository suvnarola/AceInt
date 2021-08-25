<?

$CONFIG['db_name'] = "etradebanc";
$CONFIG['db_host'] = "104.192.31.75";
$CONFIG['db_user'] = "empireDB";
$CONFIG['db_pass'] = "1emPire82";
$CONFIG['db_linkid'] = @mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

$goldSQL = dbRead("select tbl_gold.* from tbl_gold where cashAmountPaid = 0 and tradeAmountPaid = 0");
while($goldObj = mysql_fetch_object($goldSQL)) {

	dbWrite("delete from tbl_gold where fieldID = " . $goldObj->fieldID);

}

function dbWrite($SQLQuery,$database = false,$ReturnID = False) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);
	if ($ReturnID == True) {
		$ReturnID = mysql_insert_id($CONFIG['db_linkid']);
	} else {
		$ReturnID = True;
	}

	return($ReturnID);
}


function dbRead($SQLQuery,$database = false) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);

	return($rsid);
}

?>
