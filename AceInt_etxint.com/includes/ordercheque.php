<?

if(!checkmodule("MemOrder")) {

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

$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_REQUEST['addmember']) {

#check to see if the account number exists.

$memberq = mysql_db_query($db, "select * from members, status where (members.status = status.FieldID) and memid='".$_REQUEST[memid]."' and CID = '".$_SESSION['User']['CID']."'", $linkid);
$memberrow = mysql_fetch_assoc($memberq);

if($_REQUEST['confirm']) {

$memberrow[companyname] = addslashes($memberrow[companyname]);

dbWrite("insert into memcards (memid,date,type,userid) values ('".$_REQUEST[memid]."','$date','2','".$_SESSION['User']['FieldID']."')");
dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST[memid]."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','2','Cheque Book Ordered')");

add_kpi("5", $memberrow['memid']);

?>
<html>
<body>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">

<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_REQUEST[memid] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[companyname]) ?></td>
	</tr>
</table>
</td>
</tr>
</table>

</body>
</html>

<?
die;
}

if(!$memberrow[memid]) {
  // member doesnt exist. bomb out with error.
?>

<html>
<body onload="javascript:setFocus('OrderCheque','memid');">

<form method="POST" action="body.php?page=ordercheque" name="OrderCheque">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addmember"></td>
	</tr>
</table>
</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

<input type="hidden" name="addmember" value="1">

</form>

</body>
</html>
 <?

die;
}

//if($memberrow[Type] == 'Deactive' || $memberrow[Type] == 'Contractor' || $memberrow[Type] == 'Suspend') {
if($memberrow['mem_cheque'] != 1) {
?>
<html>
<body onload="javascript:setFocus('OrderCheque','memid');">

<form method="POST" action="body.php?page=ordercheque" name="OrderCheque">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= eval(" ?>".get_page_data("5")."<? ") ?></td>
</tr>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addmember"></td>
	</tr>
</table>
</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

<input type="hidden" name="addmember" value="1">

</form>

</body>
</html>
<?
die;
}

?>
<html>
<body>

<form method="POST" action="body.php?page=ordercheque" name="OrderCheque">
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">

<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("80") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_REQUEST[memid] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[companyname]) ?></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("93") ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("13") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalno]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("14") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalname]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("15") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalsuburb]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("16") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalcity]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("17") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalstate]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("18") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalpostcode]) ?></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("89") ?>" name="confirm"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="confirm" value="1">
<input type="hidden" name="addmember" value="1">
<input type="hidden" name="memid" value="<?= $_REQUEST[memid] ?>">
</form>

</body>
</html>
<?
die;
}
?>

<html>
<body onload="javascript:setFocus('OrderCheque','memid');">

<form method="POST" action="body.php?page=ordercheque" name="OrderCheque">

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
	<tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" width="40"></td>
		<td bgcolor="#FFFFFF" ><input type="submit" value="<?= get_word("83") ?>" name="addmember"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="addmember" value="1">

</form>

</body>
</html>