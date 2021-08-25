<?

 /**
  * Session Script for Admin Server.
  *
  * sessions.php
  * Version 0.1
  */

 ini_set("session.use_only_cookies", "1");

 session_start();

 // Password Change.
 if($_REQUEST['changePass']) {

 	// Check old password.
    $SQLQuery = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_SESSION['Username']))."' and Password = password('".addslashes(trim($_REQUEST['oldpass']))."') and Password != '' and Suspended != '1'");
    if(@mysql_num_rows($SQLQuery) > 0) {

	  $oldPassword = true;

	  if($_SERVER['HTTP_X_FORWARDED_FOR']) {
	   $IPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
	   $IPAddress = $_SERVER['REMOTE_ADDR'];
	  }

	   $LoginRow = @mysql_fetch_assoc($SQLQuery);

	   $_SESSION['LoggedIn'] = true;

	   if((mktime() - $LoginRow['passwordChange']) < 7776000) {
	     $_SESSION['passwordOK'] = true;
	   } else {
	     $_SESSION['passwordOK'] = false;
	   }

	   $_SESSION['Username'] = $LoginRow['Username'];
	   $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$LoginRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);

	   load_session_vars();

	}

	$SQLQuery2 = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_SESSION['Username']))."' and OldPassword != '' and Suspended != '1'");
    $SQLRow = @mysql_fetch_assoc($SQLQuery2);
    if($SQLRow['OldPassword'] == crypt(addslashes(trim($_REQUEST['oldpass'])), $SQLRow['OldPassword'])) {

	  $oldPassword = true;

	  if($_SERVER['HTTP_X_FORWARDED_FOR']) {
	   $IPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
	   $IPAddress = $_SERVER['REMOTE_ADDR'];
	  }

	   $LoginRow = $SQLRow;

	   $_SESSION['LoggedIn'] = true;

	   if((mktime() - $LoginRow['passwordChange']) < 7776000) {
	     $_SESSION['passwordOK'] = true;
	   } else {
	     $_SESSION['passwordOK'] = false;
	   }

	   $_SESSION['Username'] = $LoginRow['Username'];
	   $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$LoginRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);

	   load_session_vars();

 	}

	$loginSQL = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_SESSION['Username']))."' and md5Password = '".md5(addslashes(trim($_REQUEST['oldpass'])))."' and md5Password != '' and Suspended != '1'");
	if(mysql_num_rows($loginSQL) > 0) {

		$oldPassword = true;

	   $LoginRow = @mysql_fetch_assoc($loginSQL);

	   $_SESSION['LoggedIn'] = true;

	   if((mktime() - $LoginRow['passwordChange']) < 7776000) {
	     $_SESSION['passwordOK'] = true;
	   } else {
	     $_SESSION['passwordOK'] = false;
	   }

	   $_SESSION['Username'] = $LoginRow['Username'];
	   $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$LoginRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);

	   load_session_vars();

	}

 	if($oldPassword) {

 		// check the 2 passwords submitted and update md5 password to new password.

 		if($_REQUEST['pass'] == $_REQUEST['pass2']) {

 			if($_REQUEST['pass'] != $_REQUEST['oldpass']) {

	 			dbWrite("update tbl_admin_users set passwordChange = '" . mktime() . "', Password = '.', OldPassword = '.', md5Password = '" . md5(addslashes($_REQUEST['pass'])) . "' where Username = '" . addslashes($_SESSION['Username']) . "'");

	 			$_SESSION['User']['md5'] = md5(addslashes($_REQUEST['pass']));
	 			$_SESSION['passwordOK'] = true;

 			}

 		}

 	}

 }

 if($_REQUEST['job'] == "login") {

  ebanc_login();

 } elseif($_REQUEST['job'] == "logout") {

  ebanc_logout();

 }

 /**
  * Functions.
  */

 function ebanc_login() {

  if($_SERVER['HTTP_X_FORWARDED_FOR']) {
   $IPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
   $IPAddress = $_SERVER['REMOTE_ADDR'];
  }

  $loginSQL = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_REQUEST['user']))."' and md5Password = '".md5(addslashes(trim($_REQUEST['pass'])))."' and md5Password != '' and Suspended != '1'");
  if(mysql_num_rows($loginSQL) > 0) {

  	// login
	   $LoginRow = @mysql_fetch_assoc($loginSQL);

	   $_SESSION['LoggedIn'] = true;

	   if((mktime() - $LoginRow['passwordChange']) < 7776000) {
	     $_SESSION['passwordOK'] = true;
	   } else {
	     $_SESSION['passwordOK'] = false;
	   }

	   $_SESSION['Username'] = $LoginRow['Username'];
	   $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$LoginRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);

	   load_session_vars();

  } else {

	  $SQLQuery = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_REQUEST['user']))."' and Password = password('".addslashes(trim($_REQUEST['pass']))."') and Password != '' and Suspended != '1'");
	  if(@mysql_num_rows($SQLQuery) > 0) {

	   $LoginRow = @mysql_fetch_assoc($SQLQuery);

	   $_SESSION['LoggedIn'] = true;

	   if((mktime() - $LoginRow['passwordChange']) < 7776000) {
	     $_SESSION['passwordOK'] = true;
	   } else {
	     $_SESSION['passwordOK'] = false;
	   }

	   $_SESSION['Username'] = $LoginRow['Username'];
	   $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$LoginRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);

	   load_session_vars();
	   updatemd5($LoginRow['FieldID']);

	  } else {

	   /**
	    * Try to login off the old Username/Password
	    */

	   $SQLQuery2 = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_REQUEST['user']))."' and OldPassword != '' and Suspended != '1'");
	   $SQLRow = @mysql_fetch_assoc($SQLQuery2);
	   if($SQLRow['OldPassword'] == crypt(addslashes(trim($_REQUEST['pass'])), $SQLRow['OldPassword'])) {

	    $_SESSION['LoggedIn'] = true;

	    if((mktime() - $LoginRow['passwordChange']) < 7776000) {
	      $_SESSION['passwordOK'] = true;
	    } else {
	      $_SESSION['passwordOK'] = false;
	    }

	    $_SESSION['Username'] = $SQLRow['Username'];
	    $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$SQLRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);

	    load_session_vars();
	    updatemd5($SQLRow['FieldID']);

	   }

	  }

	}

 }

 function updatemd5($UserID) {

  dbWrite("update tbl_admin_users set md5password = '".md5(addslashes($_REQUEST['pass']))."' where FieldID = ".$UserID);

 }

 function ebanc_logout() {

  unset($_SESSION['LoggedIn']);
  unset($_SESSION['Username']);
  unset($_SESSION['LoginID']);

  session_destroy();

  header("Location: index.php");

 }

 function is_loggedin() {

  if($_SESSION['LoggedIn']) {
   return true;
  } else {
   return false;
  }

 }

 function checkmodule($module) {

  $Modules = $_SESSION['Modules'];
  $labels = explode(",", $module);

  foreach($labels as $value) {
   if($Modules[$value]) {
     $yes = 1;
   }
  }

  if($yes) {
   return true;
  } else {
   return false;
  }

 }

 function add_kpi($Type,$Memid,$LogChange = false) {

  if(checkmodule("Log")) {

   $KpiID = dbWrite("insert into tbl_kpi (UserID,LoginID,Date,Type,Memid) values ('".$_SESSION['User']['FieldID']."','".$_SESSION['LoginID']."',now(),'$Type','$Memid')","etxint_log",true);

   if($LogChange) {

    dbWrite("insert into tbl_kpi_changes (KpiID,Date,Data) values ('$KpiID',now(),'".addslashes(serialize($LogChange))."')","etxint_log");

   }
  }
 }

 function add_kpi2($Type,$PageID,$Lang,$CID,$LogChange = false) {

  if($LogChange) {

    dbWrite("insert into tbl_corp_log (UserID,Date,Type,Lang_Code,CID,PageID,Data) values ('".$_SESSION['User']['FieldID']."',now(),'".$Type."','".$Lang."','".$CID."','".$PageID."','".addslashes(serialize($LogChange))."')");

  }

 }

 function dump_session() {

  print "<pre>";
  var_dump($_SESSION);
  print "</pre>";

 }

 function load_session_vars() {

  $SQLQuery = dbRead("select * from tbl_admin_users where Username = '".$_SESSION['Username']."'");
  $SQLRow = @mysql_fetch_assoc($SQLQuery);

  $_SESSION['User'] = $SQLRow;
  $_SESSION['User']['md5'] = $SQLRow['md5Password'];
  $_SESSION['User']['LangCode'] = $SQLRow['Langcode'];

  $_SESSION['Modules'] = unserialize($SQLRow['Modules']);

 }
