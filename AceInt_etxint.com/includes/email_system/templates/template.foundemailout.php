<?

	/**
	 * Config
	 */

	$CONFIG['db_name'] = "etxint_email_system";
	$CONFIG['db_host'] = "localhost";
	$CONFIG['db_user'] = "empireDB";
	$CONFIG['db_pass'] = "1emPire82";
	$CONFIG['db_linkid'] = @mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

	$jobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
	$jobRow = @mysql_fetch_assoc($jobQuery);

	$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where JobID = " . addslashes($_REQUEST['jobID']) . " Order By `orderBy`", "etxint_email_system");
	while($templateDataRow = mysql_fetch_assoc($templateDataQuery)) {

		if($templateDataRow['templateType'] == "Section") {

			$sectionArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];

		} elseif($templateDataRow['templateType'] == "Community") {

			$communityArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];

		} else {

			$templateArray[$templateDataRow['templateType']] = $templateDataRow['templateData'];

		}

	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>E Foundation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style3 {
	font-size: 12px;
	font-style: normal;
	line-height: normal;
	font-variant: normal;
	text-transform: none;
	color: #333333;
	font-family: Arial;
	font-weight: normal;
}
.style4 {color: #FFFFFF}
.style5 {font-size: 16px}
td { font-weight: normal; font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 10pt;}
th { font-weight: normal; font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 10pt;}
a { color: #0000FF; }

-->
</style>
</head>

<body>
<div align="center">
  <table width="630" border="0" cellspacing="0" cellpadding="0">
    <tr>

      <th scope="col"><img src="http://www.efoundation.cc/img/header.gif" width="630" height="94"></th>
    </tr>
  </table>
  <table width="630" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th width="20" height="10" bgcolor="4281B1" scope="col">&nbsp;</th>
      <th width="20" scope="col">&nbsp;</th>
      <th scope="col"><p align="left" class="style3">&nbsp;</p></th>
      <th width="20" scope="col">&nbsp;</th>

      <th width="20" bgcolor="4281B1" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th width="20" height="19" bgcolor="4281B1" scope="col">&nbsp;</th>
      <th width="20" scope="col">&nbsp;</th>
      <th scope="col"><div align="left">
        <?= $templateArray['templateData'] ?>
      </div><br><br></th>
      <th width="20" scope="col">&nbsp;</th>
      <th width="20" bgcolor="4281B1" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th height="10" bgcolor="4281B1" scope="col">&nbsp;</th>
      <th height="10" scope="col">&nbsp;</th>
      <th height="10" scope="col">&nbsp;</th>
      <th height="10" scope="col">&nbsp;</th>
      <th height="10" bgcolor="4281B1" scope="col">&nbsp;</th>
    </tr>
  </table>

  <table width="630" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th width="50" height="5" bgcolor="4281B1" scope="col"> <div align="left"></div></th>
      <th width="140" bgcolor="4281B1" class="style3" scope="col"><div align="left" class="style4"><strong>Postal Address </strong><br>
PO Box 151 <br>
Buddina Qld 4575 <br>
Australia </div></th>
      <th bgcolor="4281B1" class="style3" scope="col"> <span class="style4"><strong><span class="style5">www.efoundation.cc</span><br>

      </strong><em>for you, the community </em></span></th>
      <th width="140" bgcolor="4281B1" class="style3" scope="col"><div align="left"><span class="style4"><strong>Contacts </strong><br>
      info@efoundation.cc<br>
      tel [07] 5437 7227<br>
      fax [07] 5437 7231</span></div></th>
      <th width="50" height="5" bgcolor="4281B1" scope="col">&nbsp;</th>

    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>
<?

	/**
	 * Database Functions
	 */

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