<?

 /**
  * Message System
  *
  * Version 1.1
  * message.php
  */

 include("global.php");

 if(!$_REQUEST['message_sendto']) {

  header("Location: /body.php?page=messages_send&Error=true&countryid=".htmlspecialchars($_REQUEST['countryid'])."&type1=".htmlspecialchars($_REQUEST['type1'])."&type2=".htmlspecialchars($_REQUEST['type2'])."&type3=".htmlspecialchars($_REQUEST['type3'])."&type4=".htmlspecialchars($_REQUEST['type4'])."&type5=".htmlspecialchars($_REQUEST['type5'])."&message_importance=".htmlspecialchars($_REQUEST['message_importance'])."&message_description=".htmlspecialchars($_REQUEST['message_description'])."");
  die;

 }


 $DBDate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);

 if($_REQUEST['message_send']) {

  if($_REQUEST['type1'] || $_REQUEST['type2'] || $_REQUEST['type3'] || $_REQUEST['type4'] || $_REQUEST['type5'] )  {

 if($_REQUEST[type1]) {
  $t1 = " emlic = '1'";
 } else {
  $t1 = "";
 }

 if($_REQUEST[type2]) {
  if($t1)  {
   $t2 = " OR emadm = '1'";
  } else {
   $t2 = " emadm = '1'";
  }
 } else {
  $t2 = "";
 }

 if($_REQUEST[type3]) {
  if($t1 || $t2)  {
   $t3 = " OR emcus = '1'";
  } else {
   $t3 = " emcus = '1'";
  }
 } else {
  $t3 = "";
 }

 if($_REQUEST[type4]) {
  if($t1 || $t2 || $t3)  {
   $t4 = " OR emsal = '1'";
  } else {
   $t4 = " emsal = '1'";
  }
 } else {
  $t4 = "";
 }

 if($_REQUEST[type5]) {
  if($t1 || $t2 || $t3 || $t4)  {
   $t5 = " OR emrea = '1'";
  } else {
   $t5 = " emrea = '1'";
  }
 } else {
  $t5 = "";
 }

    $query = dbRead("select FieldID from tbl_admin_users where (Suspended != '1') and ($t1$t2$t3$t4$t5) and CID = '".$_SESSION['User']['CID']."' Order by FieldID");
    $users = array();
    while($row = mysql_fetch_assoc($query)) {
      $users[] = $row[FieldID];
    }
    addmessage($users,$_REQUEST['message_description'],$_SESSION['User']['FieldID']);

  } else {

	  $query = dbRead("select * from tbl_admin_users where FieldID='".$_REQUEST['message_sendto']."'");
	  $row = mysql_fetch_assoc($query);

	  dbWrite("insert into message_system (Date_Entered,Sender,Receiver,Importance,Message) values ('".$DBDate."','".$_SESSION['User']['FieldID']."','".$_REQUEST['message_sendto']."','".$_REQUEST['message_importance']."','".addslashes(encode_text2($_REQUEST['message_description']))."')", "etxint_ebanc_message");

	  if($_REQUEST['noteid'] && !$_REQUEST['note']) {

	    $query = dbRead("select * from notes where FieldID='".$_REQUEST['noteid']."'", "etradebanc");
	    $row = mysql_fetch_assoc($query);

	    $noteid2 = dbWrite("insert into notes (memid,date,userid,type,reminder,note) values ('".$row['memid']."','".$DBDate."','".$_SESSION['User']['FieldID']."','".$row['type']."','$reminder','".addslashes(encode_text2($_REQUEST['message_description']))."')","etradebanc", true);
	  	dbWrite("update notes set responseid = '".$noteid2."' where FieldID = '".$_REQUEST['noteid']."'", "etradebanc");

	  }
  }
  ?>
  <html>
  <head>
  <script>
   window.close();
  </script>
  </head>
  </html>
  <?

 }

?>