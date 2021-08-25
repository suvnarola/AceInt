<?

 include("/home/etxint/admin.etxint.com/includes/global.php");

 $CONFIG2['db_name'] = "control2";
 $CONFIG2['db_host'] = "localhost";
 $CONFIG2['db_user'] = "empireDB";
 $CONFIG2['db_pass'] = "1emPire82";
 $CONFIG2['db_linkid'] = @mysql_pconnect($CONFIG2['db_host'], $CONFIG2['db_user'], $CONFIG2['db_pass']);

 $CONFIG2['auth_enabled'] = false;
 $CONFIG2['auth_realm'] = "E Banc Administration.";
 $CONFIG2['auth_failed'] = "/virt/web-01/errormsg/auth_1.htm";
 $CONFIG2['auth_suspend'] = "/virt/web-01/errormsg/auth_2.htm";
 $CONFIG2['auth_user'] = "";
 $CONFIG2['auth_pass'] = "";
 $CONFIG2['DEBUG'] = true;
 $CONFIG2[graphver] = "1.8";


 //$date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 //$date = date("dmY", mktime(0,0,0,date("m"),date("d"),date("Y")));

 //$query = dbRead("select * from members where reward_bsb > '0'");
$query = dbRead("select * from tbl_admin_users where Suspended = 1");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

  $name = explode("@",$row[EmailAddress]);
  $query2 = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".$name[0]."' and AliasDomain = '".$name[1]."'");
  $row2 = mysql_fetch_assoc($query2);
  if($row2[FieldID]) {
   echo $row2[FieldID].", ".$row[EmailAddress]."<br>";
  }
 //$net=str_pad($row[reward_bsb], 6, "0", STR_PAD_LEFT);
 //$nett .="$net\n\r";

 //dbWrite("update members set reward_bsb = '$net' where memid = '$row[memid]'");

 }

function dbRead2($SQLQuery,$database = false) {
	global $CONFIG2, $DB_Count;
	if($database == false) { $database = $CONFIG2['db_name']; }

	if ($CONFIG2['db_linkid'] == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }

	mysql_select_db($database);

	$rsid = mysql_query($SQLQuery, $CONFIG2['db_linkid']);
	if ($rsid == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }

	$DB_Count++;

	return($rsid);
}

function dbReportError2($ErrorNumber,$ErrorMsg,$SQLQuery) {

	print "An error occured while connecting to the database<br>";
	print "<strong>$ErrorNumber</strong>";
	print $ErrorMsg;
	exit;
}
