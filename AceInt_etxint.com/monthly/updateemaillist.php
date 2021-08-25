<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");

 $CONFIG2['db_name'] = "control2";
 $CONFIG2['db_host'] = "localhost";
 $CONFIG2['db_user'] = "stealth";
 $CONFIG2['db_pass'] = "";
 $CONFIG2['db_linkid'] = @mysql_pconnect($CONFIG2['db_host'], $CONFIG2['db_user'], $CONFIG2['db_pass']);

 $CONFIG2['auth_enabled'] = false;
 $CONFIG2['auth_realm'] = "E Banc Administration.";
 $CONFIG2['auth_failed'] = "/virt/web-01/errormsg/auth_1.htm";
 $CONFIG2['auth_suspend'] = "/virt/web-01/errormsg/auth_2.htm";
 $CONFIG2['auth_user'] = "";
 $CONFIG2['auth_pass'] = "";
 $CONFIG2['DEBUG'] = true;
 $CONFIG2[graphver] = "1.8";

 //Inter Staff;
 $elistint = "";

 $query = dbRead("select EmailAddress from tbl_admin_users where inter_staff = 1 and Suspended != '1' group by EmailAddress order by EmailAddress");
 while($row = mysql_fetch_assoc($query))  {
  if($row[EmailAddress])  {
    $elistint .= $row[EmailAddress].",";
  }
 }
 //echo substr($elist, 0, strlen($elist)-1);
 $elistint2 = substr($elistint, 0, strlen($elistint)-1);


 //HQ Staff AU;
 $elist = "";

 $query = dbRead("select EmailAddress from tbl_admin_users where CID=1 and Area=1 and Suspended != '1' and inter_staff != 1 group by EmailAddress order by EmailAddress");
 while($row = mysql_fetch_assoc($query))  {
  if($row[EmailAddress])  {
    $elist .= $row[EmailAddress].",";
  }
 }
 //echo substr($elist, 0, strlen($elist)-1);
 $elist2 = substr($elist, 0, strlen($elist)-1);

 //HQ cust/support AU;
 $elistcs = "";

 $query = dbRead("select EmailAddress from tbl_admin_users where CID=1 and Area=1 and Suspended != '1' and emcus = '1' group by EmailAddress order by EmailAddress");
 while($row = mysql_fetch_assoc($query))  {
  if($row[EmailAddress])  {
    $elistcs .= $row[EmailAddress].",";
  }
 }
 //echo substr($elist, 0, strlen($elist)-1);
 $elistcs2 = substr($elistcs, 0, strlen($elistcs)-1);

 //Lic AU;
 $elistau = "";

 $query = dbRead("select reportemail from area where CID=1 and display = 'Y' group by reportemail order by reportemail");
 while($row = mysql_fetch_assoc($query))  {
  if($row[reportemail])  {
    $elistau .= $row[reportemail].",";
  }
 }
 //echo substr($elistau, 0, strlen($elistau)-1);
 $elistau2 = substr($elistau, 0, strlen($elistau)-1);


 //Lic World;
 $elistw = "";

 //$query = dbRead("select reportemail from area where display = 'Y' group by reportemail order by reportemail");
 $query = dbRead("select reportemail from area, country where (area.FieldID = country.DefaultArea) and area.display = 'Y' group by reportemail order by reportemail","etradebanc");
 while($row = mysql_fetch_assoc($query))  {
  if($row[reportemail])  {
    $elistw .= $row[reportemail].",";
  }
 }
 //echo substr($elistw, 0, strlen($elistw)-1);
 $elistw2 = substr($elistw, 0, strlen($elistw)-1);


 //Lic Country;
 $elistc = "";

 $queryc = dbRead("select * from country where Display = 'Yes' and email_lic != '' order by name");
 while($rowc = mysql_fetch_assoc($queryc))  {
  $elistc = "";
  $query = dbRead("select reportemail from area where CID = '".$rowc[countryID]."' and display = 'Y' group by reportemail order by reportemail");
  while($row = mysql_fetch_assoc($query))  {
    if($row[reportemail])  {
     $elistc .= $row[reportemail].",";
    }
  }
  $elistc2 = substr($elistc, 0, strlen($elistc)-1);
  //dbWrite2("update tbl_Mail set ForwardEmail = '$elistc2', Forward = 'Y', StatusID = '2' where FieldID = '".$rowc['email_lic']."'");
  $LicCountry[] = "update tbl_Mail set ForwardEmail = '$elistc2', Forward = 'Y', StatusID = '2' where FieldID = '".$rowc['email_lic']."'";
 }


 //Staff Country;
 $elists = "";

 $queryc = dbRead("select * from country where Display = 'Yes' and email_staff != '' order by name");
 while($rowc = mysql_fetch_assoc($queryc))  {
  $elists = "";
  $query = dbRead("select EmailAddress from tbl_admin_users where CID='".$rowc[countryID]."' and Suspended != '1' and inter_staff != 1 group by EmailAddress order by EmailAddress");
  while($row = mysql_fetch_assoc($query))  {
    if($row[EmailAddress])  {
     $elists .= $row[EmailAddress].",";
    }
  }
  $elists2 = substr($elists, 0, strlen($elists)-1);
  //dbWrite2("update tbl_Mail set ForwardEmail = '$elists2', Forward = 'Y', StatusID = '2' where FieldID = '".$rowc['email_staff']."'");
  $StaffCountry[] = "update tbl_Mail set ForwardEmail = '$elists2', Forward = 'Y', StatusID = '2' where FieldID = '".$rowc['email_staff']."'";
 }

 //State Lists;
 $slists = "";

 $querys = dbRead("select * from tbl_area_states where CID = 1 order by StateName");
 while($rows = mysql_fetch_assoc($querys))  {
  $slists = "";
  $query = dbRead("select reportemail from area where state ='".$rows[StateName]."' and display = 'Y' group by reportemail order by reportemail");
  while($row = mysql_fetch_assoc($query))  {
    if($row[reportemail])  {
     $slists .= $row[reportemail].",";
    }
  }
  $slists2 = substr($slists, 0, strlen($slists)-1);
  //dbWrite2("update tbl_Mail set ForwardEmail = '$slists2', Forward = 'Y', StatusID = '2' where FieldID = '".$rows['EmailID']."'");
  $StateLists[] = "update tbl_Mail set ForwardEmail = '$slists2', Forward = 'Y', StatusID = '2' where FieldID = '".$rows['EmailID']."'";
 }

 mysql_close($CONFIG['db_linkid']);
 unset($CONFIG);

 $CONFIG2['db_name'] = "control2";
 $CONFIG2['db_host'] = "localhost";
 $CONFIG2['db_user'] = "stealth";
 $CONFIG2['db_pass'] = "";
 $CONFIG2['db_linkid'] = mysql_pconnect($CONFIG2['db_host'], $CONFIG2['db_user'], $CONFIG2['db_pass']);

 $CONFIG2['auth_enabled'] = false;
 $CONFIG2['auth_realm'] = "E Banc Administration.";
 $CONFIG2['auth_failed'] = "/virt/web-01/errormsg/auth_1.htm";
 $CONFIG2['auth_suspend'] = "/virt/web-01/errormsg/auth_2.htm";
 $CONFIG2['auth_user'] = "";
 $CONFIG2['auth_pass'] = "";
 $CONFIG2['DEBUG'] = true;
 $CONFIG2[graphver] = "1.8";

 //dbWrite2("update tbl_Mail set ForwardEmail = '$elistint2', Forward = 'Y', StatusID = '2' where FieldID = 2138");
 //dbWrite2("update tbl_Mail set ForwardEmail = '$elistw2', Forward = 'Y', StatusID = '2' where FieldID = 440");
 //dbWrite2("update tbl_Mail set ForwardEmail = '$elistau2', Forward = 'Y', StatusID = '2' where FieldID = 660");
 //dbWrite2("update tbl_Mail set ForwardEmail = '$elist2', Forward = 'Y', StatusID = '2' where FieldID = 453");
 //dbWrite2("update tbl_Mail set ForwardEmail = '$elistcs2', Forward = 'Y', StatusID = '2' where FieldID = 490");

 //print_r($elistint2);
 ?>
 <br><br>
 <?
 //print_r($elistw2);
 ?>
 <br><br>
 <?
 //print_r($elistau2);
 ?>
 <br><br>
 <?
 //print_r($elist2);
 ?>
 <br><br>
 <?
 //print_r($elistcs2);
 ?>
 <br><br>
 <?
 print_r($elists2);


 foreach($LicCountry as $Key => $Value) {
  //dbWrite2($Value);
 }

 foreach($StaffCountry as $Key => $Value) {
  //dbWrite2($Value);
 }

 foreach($StateLists as $Key => $Value) {
  //dbWrite2($Value);
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

function dbWrite2($SQLQuery,$database = false,$DBReturnID = False) {
	global $CONFIG2, $DB_Count;
	if($database == false) { $database = $CONFIG2['db_name']; }

	if ($CONFIG2['db_linkid'] == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }

	mysql_select_db($database);

	$rsid = mysql_query($SQLQuery, $CONFIG2['db_linkid']);
	if ($rsid == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }
	if ($DBReturnID == True) {
		$DBReturnID = mysql_insert_id($CONFIG2['db_linkid']);
	} else {
		$DBReturnID = True;
	}

	$DB_Count++;

	return($DBReturnID);
}


function dbReportError2($ErrorNumber,$ErrorMsg,$SQLQuery) {

	print "An error occured while connecting to the database<br>";
	print "<strong>$ErrorNumber</strong>";
	print $ErrorMsg;
	exit;
}
