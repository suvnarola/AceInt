<html>
<body onload="javascript:setFocus('OrderCheque','memid');">

<form method="POST" action="body.php?page=trustrecon" name="OrderCheque">

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2">trust Recon</td>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Details:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="20" name="details"></td>
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


$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_REQUEST['confirm']) {

$memberrow[companyname] = addslashes($memberrow[companyname]);

dbWrite("update transactions set details = '".$_REQUEST['details2']."' where id = ".$_REQUEST['id']."");

//die;
}

if($_REQUEST['addmember']) {

#check to see if the account number exists.

$query = dbRead("select * from transactions where transactions.details like '%".$_REQUEST['details']."%' and memid = 10528 and type = 1");
//14416;
?>
<html>
<body>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">

<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">

    <?
	while($row = mysql_fetch_array($query)) {
	?>
<form method="POST" action="body.php?page=trustrecon" name="OrderCheque">
	<tr>
		<td bgcolor="#FFFFFF" align="left" width="10%"><?= $row[memid] ?></td>
		<td bgcolor="#FFFFFF" align="left" width="15%"><?= $row[dis_date] ?></td>
		<td bgcolor="#FFFFFF" align="left" width="10%"><?= $row[to_memid] ?></td>
		<td bgcolor="#FFFFFF" align="left" width="15%"><?= $row[buy] ?></td>
		<td bgcolor="#FFFFFF" align="left" width="50%"><textarea cols="55" rows="3" name="details2"><?= $row[details] ?></textarea></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addmember"><?if($row[id] == $_REQUEST['id']) {?><Br><b>UPDATED</b><?}?></td>
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="confirm" value="1">
<input type="hidden" name="details" value="<?= $_REQUEST[details] ?>">
	</tr>
</form>
	<?}?>
</table>
</td>
</tr>
</table>

</body>
</html>

<?
}
