<?
//include("global.php");
$time_start=getmicrotime();

$dbgetnewmem=mysql_db_query($db, "select * from tbl_newmem where CID = ".$_SESSION['User']['CID']." order by id", $linkid);

$counter=mysql_num_rows($dbgetnewmem);

?>
<br>
<form name="deletenewmem" method="post" action="/general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("3") ?>: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading"><?= get_word("185") ?>.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b>ID:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("5") ?>:</b></font></td>
		<td width="88" class="Heading2"><b><?= get_word("16") ?>:</b></font></td>
		<td width="42" class="Heading2"><b><?= get_word("17") ?>:</b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>

<?

if(mysql_num_rows($dbgetnewmem) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF"><?= get_page_data("2") ?>.</font></td>
		</tr>
<?

} else {

$foo=0;

while($row = mysql_fetch_array($dbgetnewmem)) {

	$cfgbgcolorone="#CCCCCC";
	$cfgbgcolortwo="#EEEEEE";
	$bgcolor=$cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

if(!$row[companyname]) { $row[companyname]="(No Companyname)"; }

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row[id] ?></td>
			<td width="200"><a href="body.php?page=member_add&newmem=<?= $row[id] ?><? if ($row[erewards] == 3) { echo "&erewards_system=3"; }  else { echo "&erewards_system=5"; } ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?></a></td>
			<td width="200"><?= get_all_added_characters($row[contactname]) ?></td>
			<td width="88"><?= get_all_added_characters($row[city]) ?></td>
			<td width="42"><?= get_all_added_characters($row[state]) ?></td>
			<td width="30"><input type="checkbox" name="delnewmem[]" value="<?= $row[id] ?>"></td>
		</tr>
<?

$foo++;

}

}

?>
		<tr>
		    <td colspan="6" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end=getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="deletenewmem"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>