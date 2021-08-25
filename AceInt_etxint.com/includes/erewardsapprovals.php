<?
$time_start = getmicrotime();

$dbgetnewmem = mysql_db_query($db, "select members.memid, members.companyname, members.accountno, members.expires, members.erewards, area.place as areaname from members, area where ((area.FieldID = members.area) and (members.erewards = '1' or members.erewards = '2')) order by members.companyname", $linkid);

$counter = mysql_num_rows($dbgetnewmem);

?>
<br>
<form name="approverewards" method="post" action="/general.php">

<?
 if($errormsg) {
  ?>
  <table width="610" border="2" bordercolor="#0000FF" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><?= $errormsg ?>&nbsp;</td>
  </tr>
  </table>
  <br>
  <?
 }
?>

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Members Awaiting Approval: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading">New E Rewards Members.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b>AccNo.:</b></font></td>
		<td width="200" class="Heading2"><b>Company Name:</b></font></td>
		<td width="200" class="Heading2"><b>Credit Info:</b></font></td>
		<td width="88" class="Heading2"><b>Area:</b></font></td>
		<td width="42" class="Heading2"><b>Opt:</b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>

<?

if(mysql_num_rows($dbgetnewmem) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF">No Members Awaiting Approval.</font></td>
		</tr>
<?

} else {

$foo=0;

while($row = mysql_fetch_array($dbgetnewmem)) {

	if($row[erewards] == 1) {
	 $opt = "In";
	} else {
	 $opt = "Out";
	}

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

if(!$row[companyname]) { $row[companyname] = "(No Companyname)"; }

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row[memid] ?></td>
			<td width="200"><?= $row[companyname] ?></td>
			<td width="200"><?= $row[accountno] ?> - <?= $row[expires] ?></td>
			<td width="88"><?= $row[areaname] ?></td>
			<td width="42"><?= $opt ?></td>
			<td width="30"><input type="checkbox" name="approvemem[]" value="<?= $row[memid] ?>"></td>
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
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="Approve Selected Members" name="approvenewmem"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>