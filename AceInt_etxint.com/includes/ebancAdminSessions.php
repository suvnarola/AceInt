<?

 /**
  * Session Script for Admin Server.
  *
  * sessions.php
  * Version 0.1
  */

 ini_set("session.use_only_cookies", "1");
 
 session_start();

 if($_REQUEST['job'] == "login") {
 
  ebanc_login();
 
 } elseif($_REQUEST['job'] == "logout") {
 
  ebanc_logout();
 
 }

 /**
  * SSL Redirect
  * 
  * First things first. If this is not secure.
  * 
  */
 
 if(!$NoSession) {
 
  if($_SERVER['SERVER_PORT'] != 443) {
 
   header("Location: https://" . $_SERVER['HTTP_HOST']);
   die;
  
  }

 }

 /**
  * Login Redirect.
  */
 
 if(!$NoSession) {
 
  if(!is_loggedin()) {
  
   $pageTemp = explode("/", $_SERVER['SCRIPT_NAME']);
   $pageTemp = array_reverse($pageTemp);
  
   if($pageTemp[0] != "index.php") {
 
    header("Location: /");
    die;
    
   }
 
  }

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
 
  $SQLQuery = dbRead("select * from tbl_admin_users where Username = '".addslashes(trim($_REQUEST['user']))."' and Password = password('".addslashes(trim($_REQUEST['pass']))."') and Password != '' and Suspended != '1'");
  if(@mysql_num_rows($SQLQuery) > 0) {
   
   $LoginRow = @mysql_fetch_assoc($SQLQuery);
  
   $_SESSION['LoggedIn'] = true;
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
    $_SESSION['Username'] = $SQLRow['Username'];
    $_SESSION['LoginID'] = dbWrite("insert into tbl_kpi_login_history (UserID,IPAddress,Date) values ('".$SQLRow['FieldID']."','".ip2long($IPAddress)."',now())","etxint_log",true);
    
    load_session_vars();
    updatemd5($SQLRow['FieldID']);
    
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
 
  $KpiID = dbWrite("insert into tbl_kpi (UserID,LoginID,Date,Type,Memid) values ('".$_SESSION['User']['FieldID']."','".$_SESSION['LoginID']."',now(),'$Type','$Memid')","etxint_log",true);

  if($LogChange) {
  
   dbWrite("insert into tbl_kpi_changes (KpiID,Date,Data) values ('$KpiID',now(),'".addslashes(serialize($LogChange))."')","etxint_log");
  
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
 
  $_SESSION['User']['FieldID'] = $SQLRow['FieldID'];
  $_SESSION['User']['Name'] = $SQLRow['Name'];
  $_SESSION['User']['md5'] = $SQLRow['md5Password'];
  $_SESSION['User']['EmailAddress'] = $SQLRow['EmailAddress'];
  $_SESSION['User']['Position'] = $SQLRow['Position'];
  $_SESSION['User']['Position2'] = $SQLRow['Position2'];  
  $_SESSION['User']['LangCode'] = $SQLRow['Langcode'];
  $_SESSION['User']['Locale'] = $SQLRow['Locale'];
  $_SESSION['User']['AgentID'] = $SQLRow['AgentID'];
  $_SESSION['User']['MaxTransfer'] = $SQLRow['MaxTransfer'];
  $_SESSION['User']['AreasAllowed'] = $SQLRow['AreasAllowed'];
  $_SESSION['User']['ReportsAllowed'] = $SQLRow['ReportsAllowed'];
  $_SESSION['User']['SalesPerson'] = $SQLRow['SalesPerson'];
  $_SESSION['User']['Area'] = $SQLRow['Area'];
  $_SESSION['User']['NoteType'] = $SQLRow['NoteType'];
  $_SESSION['User']['CID'] = $SQLRow['CID'];
  $_SESSION['User']['lang_code'] = $SQLRow['lang_code'];
  $_SESSION['User']['PrintView'] = $SQLRow['PrintView'];
  $_SESSION['Modules'] = unserialize($SQLRow['Modules']);
 
 }

?>