<?

if(!checkmodule("FeePayment")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}
if($_SESSION['User']['FieldID'] == 0) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">Page down for maintenance. If you need this urgently please email Antony on <a href="mailto:antony@ebanctrade.com">antony@ebanctrade.com</a></td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}
if($_REQUEST['GO']) {

$query = dbRead("select * from members where memid='".$_REQUEST['memid']."' and CID = '".$_SESSION['User']['CID']."'");
$row = mysql_fetch_assoc($query);

if(!$row[memid]) {?>

<html>
<body onload="javascript:setFocus('SFee','memid');">

<form method="POST" action="body.php?page=stationeryfeeadd" name="SFee">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<input type="hidden" name="GO" value="1">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="40" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="GO"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>

<?
} else {?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form method="post" action="/general.php" name="SFee">

<input type="hidden" name="directorycharge" value="1">
<input type="hidden" name="directoryfee" value="1">
<input type="hidden" name="type" value="1">
<input type="hidden" name="d_fee[]" value="<?= get_all_added_characters($row[fee_deductions]) ?>">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="80" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("1") ?>:</b></font></td>
    <td width="200" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("3") ?>:</b></font></td>
    <td width="120" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("80") ?>:</b></font></td>
    <td align="right" width="120" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("61") ?>:</b></font></td>
  </tr>

	  <tr>
		<input type="hidden" name="memberacc[]" value="<?= $row[memid] ?>">
	    <td width="80" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= $row[memid] ?></font>&nbsp;</td>
	    <td width="200" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= get_all_added_characters($row[companyname]) ?></font>&nbsp;</td>
	    <td width="120" bgcolor="#DDDDDD"><input size="32" type="text" name="det[]"></td>
		<td align="right" width="120" bgcolor="#DDDDDD"><font size="1" face="Verdana"><input size="14" type="text" name="amount[]" onKeyPress="return number(event)"></font></td>
	  </tr>

	  <tr>
		<td colspan="4" align="right" bgcolor="#FFFFFF"><?= get_page_data("4") ?>: <input type="radio" name="deduct" value="1" checked>&nbsp;<?= get_page_data("5") ?>: <input type="radio" name="deduct"></td>
	  </tr>

  <tr>
    <td colspan="5" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="directorycharge"></td>
  </tr>

</table>
</td>
</tr>
</table>

</form>

</body>
</html>

<?
}

} else {
?>

<html>
<body onload="javascript:setFocus('SFee','memid');">

<form method="POST" action="body.php?page=stationeryfeeadd" name="SFee">

<table border="0" cellpadding="1" cellspacing="1" width="620">
<input type="hidden" name="GO" value="1">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="40" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="GO"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>
<?}
