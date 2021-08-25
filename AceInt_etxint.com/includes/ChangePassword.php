<?

 $CONFIG2['db_name'] = "control2";
 //$CONFIG2['db_host'] = "66.228.219.105";
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

 /**
  * Password Change
  *
  * ChangePassword.php
  * version 0.01
  *
  * First Version of File.
  *
  *
  * : Requires Database functions, dbRead(), dbWrite() and their associated functions.
  */

 if($_REQUEST['Process']) {

  if(checkmodule("ChangeUserPass")) {

   if($_REQUEST['NewPassword'] == $_REQUEST['NewPassword2']) {

    dbWrite("update tbl_admin_users set passwordChange = '" . mktime() . "', md5password = '".md5(addslashes($_REQUEST['NewPassword']))."' where FieldID = " . $_REQUEST['UserID']);
    dbWrite("update tbl_admin_users set OldPassword = '.', Password = '.' where FieldID = " . $_REQUEST['UserID']);

	$SQLQuery = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = ". $_REQUEST['UserID'] ."");
	$SQLRow = mysql_fetch_assoc($SQLQuery);

    $array = explode("@", $SQLRow['EmailAddress']);
    $add1 = $array[0];
    $add2 = $array[1];
    //$querych  = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".addslashes($add1)."' and AliasDomain = '".addslashes($add2)."'");
    //$querych  = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".addslashes($add1)."' and AliasDomain = '".addslashes($add2)."'");
    //$rowch = @mysql_fetch_assoc($querych);

    //if($rowch['FieldID']) {

     //dbWrite2("update tbl_Mail set Password = '".addslashes(encode_text2($_REQUEST['NewPassword']))."', StatusID = '4' where FieldID = ". $rowch['MailID'] ."");

    //}

   } else {

    $ErrorMsg = true;

   }

  } else {

    if(md5(addslashes($_REQUEST['OldPassword'])) == $_SESSION['User']['md5']) {

     if($_REQUEST['NewPassword'] == $_REQUEST['NewPassword2']) {

      dbWrite("update tbl_admin_users set passwordChange = '" . mktime() . "', md5password = '".md5(addslashes($_REQUEST['NewPassword']))."' where FieldID = ".$_SESSION['User']['FieldID']);
      dbWrite("update tbl_admin_users set OldPassword = '.', Password = '.' where FieldID = ".$_SESSION['User']['FieldID']);


	    $array = explode("@", $_SESSION['User']['EmailAddress']);
	    $add1 = $array[0];
	    $add2 = $array[1];
	    //$querych  = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".addslashes($add1)."' and AliasDomain = '".addslashes($add2)."'");
	    //$rowch = @mysql_fetch_assoc($querych);

	    //if($rowch['FieldID']) {

	     //dbWrite2("update tbl_Mail set Password = '".addslashes(encode_text2($_REQUEST['NewPassword']))."', StatusID = '4' where FieldID = ". $rowch['MailID'] ."");

	    //}

		unset($_SESSION['LoggedIn']);
		unset($_SESSION['Username']);
		unset($_SESSION['LoginID']);

		session_destroy();

	 	?>
	 	<script>
	 		parent.main.location.href = '/body.php';
	 	</script>
	 	<?

     } else {

      $ErrorMsg = True;

     }

    }

  }

 }

?>

<form name="ChangePassword" method="post" action="body.php?page=ChangePassword">

<input type="hidden" value="1" name="Process">
<?

 if(!$_REQUEST['Process']) {

  ?>
   <table width="620" border="2" bordercolor="#00FF00" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
    <tr>
     <td bgcolor="#FFFFFF" align="center"><b>WARNING - If password successfully changed user will be logged out.</b>&nbsp;</td>
    </tr>
   </table><br>
  <?

 } else {

  if($ErrorMsg) {

   ?>
    <table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
     <tr>
      <td bgcolor="#FFFFFF" align="center"><b>There was an error updating the password. Please try again.</b>&nbsp;</td>
     </tr>
    </table><br>
   <?

  }

 }

?>
<table width="620" cellspacing="0" cellpadding="1" border="0">
	<tr>
		<td class="Border">
			<table border="0" cellspacing="0" cellpadding="3" width="620">
				<tr>
					<td colspan="2" class="Heading2" align="center">Change Password</td>
				</tr>
				<?

					if(!checkmodule("ChangeUserPass")) {

        				?>
        				<tr>
        					<td class="Heading2" align="right" width="120">Old Password</td>
        					<td bgcolor="#FFFFFF" align="left"><input type="password" size="25" name="OldPassword"></td>
        				</tr>
        				<?

					} else {

        				?>
        				<tr>
        					<td class="Heading2" align="right" width="120">Username</td>
        					<td bgcolor="#FFFFFF" align="left">
        						<select name="UserID">
        						<?

        							$SQLQuery = dbRead("select tbl_admin_users.* from tbl_admin_users where Suspended != 1 and CID = " . $_SESSION['User']['CID'] . " Order By Name");
        							while($SQLRow = mysql_fetch_assoc($SQLQuery)) {

        								$SQLRow['Name'] = ($SQLRow['Name']) ? $SQLRow['Name'] : "No Name";

        								?>
        								<option value="<?= $SQLRow['FieldID'] ?>"><?= $SQLRow['Name'] ?> [<?= $SQLRow['Username'] ?>]</option>
        								<?

        							}

        						?>
        						</select>
        					</td>
        				</tr>
        				<?

					}

				?>
				<tr>
					<td class="Heading2" align="right" width="120">New Password</td>
					<td bgcolor="#FFFFFF" align="left"><input type="password" size="25" name="NewPassword"></td>
				</tr>
				<tr>
					<td class="Heading2" align="right" width="120">Confirm Password</td>
					<td bgcolor="#FFFFFF" align="left"><input type="password" size="25" name="NewPassword2"></td>
				</tr>
				<tr>
					<td class="Heading2" align="right" width="120">&nbsp;</td>
					<td bgcolor="#FFFFFF" align="left"><input type="submit" value="Change Password"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</form>

<?

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
?>
