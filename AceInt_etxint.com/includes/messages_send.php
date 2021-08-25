<?

if(checkmodule("Log")) {
 add_kpi("9","0");
}

if($_REQUEST['countryid']) {
 $GET_CID = $_REQUEST['countryid'];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<script>

function ChangeCountry(list) {
 var url = 'body.php?page=messages_send&mess=<?= $_REQUEST['mess'] ?>&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>

<body>
<form name="SendMessage" method="POST" action="/includes/message.php">

<input type="hidden" name="message_send" value="1">

<table width="500" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td nowrap><?= get_page_data("1") ?></td>
 </tr>
</table>
<hr size="1" width="500" align="left">

<table border="0" cellspacing="0" width="500" cellpadding="1">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
  <tr>
      <td height="30" align="right" class="Heading2" width="50%"><b><?= get_word("79") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?

		$dbgetarea = dbRead("select * from country order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['countryID'] == $GET_CID) { echo "selected "; } ?>value="<?= $row['countryID'] ?>"><?= $row['name'] ?></option>
			<?
		}
		$counter = mysql_num_rows($dbgetdataout);

?>
	  </select>&nbsp;</td>
  <tr>
    <td class="Heading2" nowrap align="right" width="50%"><span lang="en-us"><?= get_word("110") ?>:</span></td>
    <?if($_REQUEST['Sender']) {
     $query4 = dbRead("select * from tbl_admin_users where FieldID='".$_REQUEST['Sender']."'");
     $row4 = mysql_fetch_array($query4);?>
    <td nowrap align="left" width="50%" bgcolor="#FFFFFF"><input type="hidden" name="message_sendto" value="<?= $row4['FieldID'] ?>"><?= $row4['Name'] ?></td>
    <?} else {?>
    <td nowrap align="left" width="50%" <? if($_REQUEST['Error']) { print 'bgcolor="#FF0000"'; } else { print 'bgcolor="#FFFFFF"'; } ?>>
    <select size="1" name="message_sendto"><option value="">Select One</option>
    <?
     $query = dbRead("select Name, Position, FieldID from tbl_admin_users where Name != '' and Suspended !='1' and CID = $GET_CID order by Name");
     while($row = mysql_fetch_assoc($query)) {

      ?>
       <option value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?> (<?= $row['Position'] ?>)</option>
      <?

     }
    ?>
    </select></td><?}?>
    <?if((checkmodule("AddMember") && $GET_CID == $_SESSION['User']['CID']) || (checkmodule("ManReports") && $GET_CID == $_SESSION['User']['CID']))  {?>
    </tr>
      <td class="Heading2" nowrap align="right">OR</td>
      <td bgcolor="#FFFFFF">
       <table border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td bgcolor="#FFFFFF" width="180"><input type="checkbox" name="type1" value="1" <? if($_REQUEST['type1']) { print "checked"; } ?>> <?= get_word("173") ?> </td>
         <td bgcolor="#FFFFFF" width="180"><input type="checkbox" name="type2" value="1" <? if($_REQUEST['type2']) { print "checked"; } ?>> <?= get_word("170") ?> </td>
        </tr>
        <tr>
         <td bgcolor="#FFFFFF" width="180"><input type="checkbox" name="type3" value="1" <? if($_REQUEST['type3']) { print "checked"; } ?>> <?= get_word("172") ?> </td>
         <td bgcolor="#FFFFFF" width="180"><input type="checkbox" name="type4" value="1" <? if($_REQUEST['type4']) { print "checked"; } ?>> <?= get_word("174") ?> </td>
        </tr>
        <tr>
         <td bgcolor="#FFFFFF" width="180"><input type="checkbox" name="type5" value="1" <? if($_REQUEST['type5']) { print "checked"; } ?>> <?= get_word("169") ?> </td>
        </tr>
       </table>
      </td>
    <tr>
    <?}?>
    </tr>
  <tr>
    <td class="Heading2" nowrap align="right" width="50%"><span lang="en-us"><?= get_word("108") ?>:</span></td>
    <td nowrap align="left" width="50%" bgcolor="#FFFFFF">
    <select size="1" name="message_importance">
    <option value="1"<? if($_REQUEST['message_importance'] == 1) { print " selected"; } ?>>Very Low</option>
    <option value="2"<? if($_REQUEST['message_importance'] == 2) { print " selected"; } ?>>Low</option>
    <option value="3"<? if($_REQUEST['message_importance'] == 3) { print " selected"; } ?><? if(!$_REQUEST['message_importance']) { print " selected"; } ?>>Normal</option>
    <option value="4"<? if($_REQUEST['message_importance'] == 4) { print " selected"; } ?>>High</option>
    <option value="5"<? if($_REQUEST['message_importance'] == 5) { print " selected"; } ?>>Very High</option>
    </select></td>
  </tr>
  <?if($_REQUEST['noteid']) {?>
  <tr>
   <td class="Heading2" nowrap align="right" width="50%"><span lang="en-us">Don't Add Note:</span></td>
   <td nowrap align="left" width="50%" bgcolor="#FFFFFF"><input type="checkbox" name="note" value="1"></td>
  </tr>
  <?}?>
  <tr>
    <td class="Heading2" nowrap align="right" width="50%" valign="top"><span lang="en-us"><?= get_word("109") ?>:</span></td>
    <td nowrap align="left" width="50%" bgcolor="#FFFFFF">
    <?

     if($_REQUEST['mess']) {
	     $querymes = dbRead("select * from message_system where message_system.FieldID = '".$_REQUEST['mess']."'", "etxint_ebanc_message");
	     $rowmes = mysql_fetch_assoc($querymes);
     }
     if($_REQUEST['noteid']) {
     ?>
      <input type="hidden" name="noteid" value="<?= $_REQUEST['noteid'] ?>">
      <?
     }
    ?>
    <textarea rows="9" name="message_description" cols="45"><?= $rowmes[Message] ?><?= $_REQUEST['message_description'] ?></textarea></td>
    </tr>
  <tr>
    <td bgcolor="#FFFFFF" nowrap align="center" width="100%" colspan="2">
    <button name="B1" style="width: 131; height: 25" type="submit"><b>
    <font face="Tahoma"><span lang="en-us"><?= get_page_data("1") ?></span></font></b>
    </button></td>
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

</form>