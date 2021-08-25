<?

 /**
  * Navigation Bar Version 3.00
  */

 $NoSession = true;
 
 include('includes/global.php');

 /**
  * Get how many are unread/read out.
  */
 
 $query1 = dbRead("select count(*) as V_Count from message_system where Date_Viewed > '0000-00-00' and Receiver = '".$UserRow['FieldID']."' and Deleted = 'N'", "etxint_ebanc_message");
 $query2 = dbRead("select count(*) as U_Count from message_system where Date_Viewed = '0000-00-00' and Receiver = '".$UserRow['FieldID']."' and Deleted = 'N'", "etxint_ebanc_message");
 $row1 = mysql_fetch_assoc($query1);
 $row2 = mysql_fetch_assoc($query2);

 $WeatherSQL = dbRead("select * from tbl_weather");
 $Weather = mysql_fetch_assoc($WeatherSQL);

 /**
  * Expire Headers.
  */
  
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // Date in the past
 header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
 header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache");                                    // HTTP/1.0

 function checkmodule($module) {
  
  global $UserRow;
 
  $Modules = unserialize($UserRow['Modules']);
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

?>
<html>
<head>
<title>VSM Header</title>
<BASE TARGET="main">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="Refresh" CONTENT="300; URL=nav2.php?page=nav2&UserID=<?= $UserRow['FieldID'] ?>&md5=<?= $UserRow['md5Password'] ?>">
	<style type="text/css" media="screen" title="currentStyle">
		@import "/styles/styles.css";
	</style>
	<script language="javascript" type="text/javascript" src="/javascript/defaultAdmin.js?cache=no"></script>
</head>
<body topmargin = "0" leftmargin="0" <?
 
 if($row2[U_Count] > 0) {
  $query3 = dbRead("select count(*) as AlertCount from message_system where Alerted = 'N' and Receiver = '".$UserRow['FieldID']."'", "etxint_ebanc_message");
  $row3 = mysql_fetch_assoc($query3);
  if($row3[AlertCount] > 0) {
   print 'onload="javascript:alert_me();"';
   dbWrite("update message_system set Alerted='Y' where Receiver = '".$UserRow['FieldID']."'", "etxint_ebanc_message");
  }
 }

?>>

<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <TD COLSPAN="2" WIDTH="100%">
      <TABLE CELLSPACING="0" CELLPADDING="0" WIDTH="100%" BORDER="0" HEIGHT="24">
        <TR>
          <td width="31"><img src="images/admin_site_3_04.gif" border="0" WIDTH="31" HEIGHT="24"></td>
          <td background="images/admin_site_3_05.gif" valign="middle" style="padding-top: 2px" NOWRAP>
          <?= get_page_data("1") ?>, <?= $UserRow['Name'] ?>.</td>
          <td background="images/admin_site_3_05.gif" valign="middle" style="padding-top: 2px" NOWRAP WIDTH="100%" ALIGN="right"><? if(checkmodule("Weather")) { print "Temp: ".$Weather['Temp']."&deg; :: "; } ?><? if(checkmodule("SuperUser")) { ?><a href="/antony/reverseTrans.php">T</a>&nbsp;<a href="body.php?page=mem_searchnew">T</a>&nbsp;<a href="body.php?page=kpitrack&tab=tab1">Misc</a>&nbsp;<?}?><? if(checkmodule("AddAdminUser")) { ?><a href="body.php?page=UserManagement&tab=Users" class="nav" target="main">Users</a> 
          :: <? } ?><? if($UserRow['Name']) { ?><b><?= $row1['V_Count'] ?></b> 
          <?= get_page_data("2") ?> : <b><?= $row2['U_Count'] ?></b> <?= get_page_data("3") ?> :: <a href="javascript:window.location.replace('nav2.php?page=nav2&UserID=<?= $UserRow['FieldID'] ?>&md5=<?= $UserRow['md5Password'] ?>');new_window('body.php?page=messages_view');" class="nav" target="nav">
          <?= get_page_data("4") ?></a> :: <a href="javascript:window.location.replace('nav2.php?page=nav2&UserID=<?= $UserRow['FieldID'] ?>&md5=<?= $UserRow['md5Password'] ?>');new_window('body.php?page=messages_sent');" class="nav" target="nav">
          <?= get_page_data("5") ?></a> : <a href="javascript:window.location.replace('nav2.php?page=nav2&UserID=<?= $UserRow['FieldID'] ?>&md5=<?= $UserRow['md5Password'] ?>');new_window2('body.php?page=messages_send');" class="nav" target="nav">
          <?= get_page_data("6") ?></a> ::<?}?> <a target="main" class="nav" href="body.php?page=mem_search">
          <?= get_page_data("7") ?></a> :: <a target="_parent" href="index.php?job=logout" class="nav">
          <?= get_page_data("8") ?></a> &nbsp;</td>
        </TR>
      </TABLE>
    </TD>
  </tr>
</table>
</body>
</html>