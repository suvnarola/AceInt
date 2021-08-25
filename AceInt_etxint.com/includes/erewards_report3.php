<?
$time_start=getmicrotime();

$prev_month = date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")));

$dbgetnewmem=mysql_db_query($db, "select sum(erewards_bank.amount_cash) as AmountCash, erewards_bank.memid as Memid, members.companyname as CompanyName from erewards_bank, members where (erewards_bank.memid = members.memid) and erewards_bank.type = '3' and erewards_bank.date like '$prev_month%' group by erewards_bank.memid", $linkid);

?>
<br>
<form name="deletenewmem" method="post" action="/general.php">

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="5" align="center" class="Heading">Total $100 Commissions.</td>
	</tr>
	<tr>
		<td class="Heading2"><b>Acc No.:</b></font></td>
		<td class="Heading2"><b>Company Name:</b></font></td>
		<td class="Heading2" align="right"><b>Amount Cash:</b></font></td>
	</tr>

<?

if(@mysql_num_rows($dbgetnewmem) == 0) {

?>
		<tr>
			<td colspan="5" bgcolor="#FFFFFF">No $100 Commissions</td>
		</tr>
<?

} else {

$foo=0;

while($row = mysql_fetch_array($dbgetnewmem)) {

	$cfgbgcolorone="#CCCCCC";
	$cfgbgcolortwo="#EEEEEE";
	$bgcolor=$cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

if(!$row[CompanyName]) { $row[CompanyName]="(No Companyname)"; }

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td><?= $row[Memid] ?></td>
			<td><?= $row[CompanyName] ?></a></td>
			<td align="right"><?= $row[AmountCash] ?></td>
		</tr>
<?

$foo++;

}

}

?>
		<tr>
		    <td colspan="5" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end=getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	</table>
</td>
</tr>
</table>

</form>

</body>
</html>