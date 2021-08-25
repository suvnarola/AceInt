<?

 //include("global.php");

if($_REQUEST['countryid']) {
 $GET_CID = $_REQUEST['countryid'];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

if($_REQUEST['deal_save']) {

	dbWrite("insert into deals (NoteID,MemID,UserID,Details,AuthNo,Amount) values ('".$_REQUEST['noteid']."','".$_REQUEST['memid']."','".$_SESSION['User']['FieldID']."','".addslashes(encode_text2($_REQUEST['details']))."','".$_REQUEST['authno']."','".$_REQUEST['amount']."')","etradebanc", true);

}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>
<form name="SendMessage" method="POST" action="/includes/deal.php">

<input type="hidden" name="deal_save" value="1">

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
    <td class="<?= bg($row) ?>" nowrap align="right" width="50%" valign="top"><span lang="en-us">Details:</span></td>
    <td nowrap align="left" width="50%" bgcolor="#FFFFFF">
    <input type="hidden" name="noteid" value="<?= $_REQUEST['noteid'] ?>">
    <input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">
    <textarea rows="3" name="details" cols="30"></textarea></td>
    </tr>
     <tr>
      <td class="<?= bg($row) ?>" align="right" nowrap>Authno:</td>
      <td bgcolor="#FFFFFF" align="left"><input type="text" name="authno" size="10"  maxlength="10" value=""></td>
     </tr>
     <tr>
      <td class="<?= bg($row) ?>" align="right" nowrap>Trade Amount:</td>
      <td bgcolor="#FFFFFF" align="left"><input type="text" name="amount" size="10"  maxlength="10" value=""></td>
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