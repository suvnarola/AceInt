<?

if($_REQUEST['DeleteSelected']) {
 $testarray = $_REQUEST['del_mess'];
 $count = sizeof($testarray);
 $i = 0;
 for ($i = 0; $i < $count; $i++) {
  mysql_db_query($db_mess, "update message_system set SendDelete='Y' where FieldID='".$testarray[$i]."'", $linkid);
 }
}

if($_REQUEST['DisplayMessage']) {

$query = dbRead("select * from message_system where Sender = '".$_SESSION['User']['FieldID']."' and FieldID = '".$_REQUEST['ID']."'", "etxint_ebanc_message");
$row = mysql_fetch_assoc($query);
echo $_REQUEST[ID];
?>
<table width="500" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td nowrap width="33%"><?= get_page_data("1") ?></td>
  <td align="center" width="34%"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a></td>
  <td align="right" width="33%"><a href="#" class="nav"><?= get_word("112") ?></a></td>
 </tr>
</table>
<hr size="1" width="500" align="left">

<table border="0" cellspacing="0" width="500" cellpadding="1">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
  <tr>
    <td class="Heading2" nowrap align="right" width="30%"><span lang="en-us"><?= get_word("110") ?>:</span></td>
    <td nowrap align="left" width="70%" bgcolor="#FFFFFF">
    <?= $row['Receiver'] ?></td>
    </tr>
  <tr>
    <td class="Heading2" nowrap align="right" width="30%"><span lang="en-us"><?= get_word("41") ?>:</span></td>
    <td nowrap align="left" width="70%" bgcolor="#FFFFFF">
    <?= $row['Date_Entered'] ?></td>
  </tr>
  <tr>
    <td class="Heading2" nowrap align="right" width="30%"><span lang="en-us"><?= get_word("108") ?>:</span></td>
    <td nowrap align="left" width="70%" bgcolor="#FFFFFF">
    <?= $row['Importance'] ?></td>
  </tr>
  <tr>
    <td class="Heading2" nowrap align="right" width="30%" valign="top"><span lang="en-us"><?= get_word("109") ?>:</span></td>
    <td align="left" width="70%" bgcolor="#FFFFFF">
    <?= nl2br($row['Message']) ?></td>
    </tr>
  </table>
</td>
</tr>
</table>
<table width="500" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td align="center"><a href="body.php?page=messages_sent" class="nav">
  <font size="1">&lt&lt <?= get_word("113") ?></font></a></td>
 </tr>
</table>
<hr size="1" width="500" align="left">
<table width="500" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td align="center"><a href="javascript:window.close();" class="nav"><?= get_word("111") ?></a></td>
 </tr>
</table>

<?

} else {

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form method="POST" action="body.php?page=messages_sent">

<input type="hidden" name="DeleteSelected" value="1">

<table width="500" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td nowrap><?= get_page_data("1") ?></td>
  <td align="right"><a href="#" class="nav"><?= get_word("112") ?></a></td>
 </tr>
</table>
<hr size="1" width="500" align="left">

<table border="0" cellspacing="0" width="500" cellpadding="1">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="2">
  <tr>
    <td width="80" class="Heading2" nowrap><?= get_word("41") ?>:</td>
    <td width="100" class="Heading2" nowrap><?= get_word("110") ?>:</td>
    <td class="Heading2" nowrap><?= get_word("109") ?>:</td>
    <td width="10" class="Heading2">&nbsp;</td>
    <td width="20" class="Heading2">Del</td>
  </tr>


<?

$query = dbRead("select message_system.*, Name from message_system, etradebanc.tbl_admin_users where (message_system.Receiver = tbl_admin_users.FieldID) and Sender = '".$_SESSION['User']['FieldID']."' and SendDelete='N' order by Date_Entered DESC, message_system.FieldID DESC", "etxint_ebanc_message");

$foo=0;
while($row = mysql_fetch_assoc($query)) {

	//$ReceiverRow = mysql_fetch_assoc(dbRead("select * from tbl_admin_users where FieldID = '".$row['Receiver']."'"));

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

	switch($row['Importance']) {
      case "1": $imp_color = "#669900"; break;
      case "2": $imp_color = "#99cc00"; break;
      case "3": $imp_color = "#ffff00"; break;
      case "4": $imp_color = "#ff9900"; break;
      case "5": $imp_color = "#ff3300"; break;
	}

?>
  <tr bgcolor="<?= $bgcolor ?>">
    <td width="80" valign="top" align="right"><?= date("jS M Y", strtotime($row['Date_Entered'])) ?><br><?= date("g:ia", strtotime($row['Date_Entered'])) ?></td>
    <td width="100" valign="top"><?= get_all_added_characters($row['Name']) ?></td>
    <td valign="top"><a href="body.php?page=messages_sent&ID=<?= $row['FieldID'] ?>&DisplayMessage=true" class="<? if($row['Date_Viewed'] == "0000-00-00") { echo "nav2"; } else { echo "nav"; } ?>"><?= get_all_added_characters(nl2br($row['Message'])) ?></a></td>
    <td width="20">
    <table cellspacing="0" cellpadding="3" width="100%" height="100%">
     <tr>
      <td bgcolor="<?= $imp_color ?>">
      <img src="/images/layout_spacer.gif" width="1" height="1">&nbsp;</td>
     </tr>
    </table>
    </td>
    <td width="20" class="Heading"><input type="checkbox" name="del_mess[]" value="<?= $row['FieldID'] ?>"></td>
  </tr>
<?

$foo++;

}


?>
 <tr>
  <td colspan="5" align="right" bgcolor="#FFFFFF">
  <button type="submit" name="SubmitDeleted" style="width: 110; height: 22"><b><span lang="en-us">
  <font size="1"><?= get_word("191") ?></font></span></b></button></td>
 </tr>
</table>
</td>
</tr>
</table>
<hr size="1" width="500" align="left">
<table width="500" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td align="center"><a href="javascript:window.close();" class="nav"><?= get_word("111") ?></a></td>
 </tr>
</table>

<?

}

?>