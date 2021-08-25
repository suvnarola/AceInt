<?

	$CONFIG['db_name'] = "etxint_email_system";
	$CONFIG['db_host'] = "localhost";
	$CONFIG['db_user'] = "etxint_admin";
	$CONFIG['db_pass'] = "Ohc6icho6eimaid3";
	$CONFIG['db_linkid'] = @mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

	$imageSQL = dbRead("select tbl_jobs_images.* from tbl_jobs_images where fieldID = " . $_REQUEST['imageID']);
    $imageRow = mysql_fetch_assoc($imageSQL);

    $now = gmdate("D, d M Y H:i:s") ." GMT";

    $fileExtensionTemp = explode("/", $imageRow['imageMimeType']);
    $fileExtension = $fileExtensionTemp[1];

    header("Content-type: " . $imageRow['imageMimeType']);
    header("Content-Length: " . strlen(base64_decode($imageRow['imageData'])));
    header("Expires: ". $now);
    header("Content-Disposition: attachment; filename=" . $imageRow['fieldID'] . "." . $fileExtension);
    header("Cache-Control: must-revalidate. post-check=0, pre-check=0");
    header("Pragma: public");

    print base64_decode($imageRow['imageData']);


	function dbtList($Table, $database = false) {
		global $CONFIG, $DB_Count;
		if($database == false) { $database = $CONFIG['db_name']; }

		if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error(),$Table); }

		$rsid = mysql_list_fields($database, $Table, $CONFIG['db_linkid']);
		if ($rsid == False) { dbReportError(mysql_errno(),mysql_error(),$Table); }

		$Columns = mysql_num_fields($rsid);

	    for ($i = 0; $i < $Columns; $i++) {
	    	$ReturnArray[] = mysql_field_name($rsid, $i);
		}

		$DB_Count++;

		return($ReturnArray);
	}

	function dbRead($SQLQuery,$database = false) {
		global $CONFIG, $DB_Count;
		if($database == false) { $database = $CONFIG['db_name']; }

		if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error(),$SQLQuery); }

		mysql_select_db($database);

		$rsid = mysql_query($SQLQuery, $CONFIG['db_linkid']);
		if ($rsid == False) { dbReportError(mysql_errno(),mysql_error(),$SQLQuery); }

		$DB_Count++;

		return($rsid);
	}

	function dbWrite($SQLQuery,$database = false,$DBReturnID = False) {
		global $CONFIG, $DB_Count;
		if($database == false) { $database = $CONFIG['db_name']; }

		if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error(),$SQLQuery); }

		mysql_select_db($database);

		$rsid = mysql_query($SQLQuery, $CONFIG['db_linkid']);
		if ($rsid == False) { dbReportError(mysql_errno(),mysql_error(),$SQLQuery); }
		if ($DBReturnID == True) {
			$DBReturnID = mysql_insert_id($CONFIG['db_linkid']);
		} else {
			$DBReturnID = True;
		}

		$DB_Count++;

		return($DBReturnID);
	}


	function dbReportError($ErrorNumber,$ErrorMsg,$SQLQuery) {

		print "An error occured while connecting to the database<br>";
		print "<strong>$ErrorNumber</strong>";
		print $ErrorMsg;
		exit;
	}
