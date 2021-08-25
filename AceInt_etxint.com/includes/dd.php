<?

if(!checkmodule("CCExpired")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
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
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>
<body>


<?

 if(checkmodule("Log")) {
  //add_kpi("25", $_REQUEST['memid']);
 }

$time_start = getmicrotime();

$query  = "select * from members WHERE paymenttype = 0 and accountno > 0 and status != 1 and members.CID = ".$_SESSION['User']['CID']." order by status, memid";

$dbgetnoncheckedtrans = dbRead($query);
$counter = @mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Non Paid Members: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="7" align="center" class="Heading"><?= get_page_data("2") ?> <?= $date ?></td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="220" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="20" class="Heading2"><b><?= get_word("12") ?>:</b></font></td>
		<td width="25" class="Heading2"><b>Payment Type:</b></font></td>
		<td width="25" class="Heading2"><b>Account no:</b></font></td>
		<td width="25" class="Heading2"><b>Expires:</b></font></td>
		<td width="62" class="Heading2"><b>Club:</b></font></td>
	</tr>
<?

if(@mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="7" bgcolor="#FFFFFF"><?= get_page_data("3") ?>.</font></td>
		</tr>
<?

} else {

$foo = 0;
$totalamount = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

$query1  = "select tbl_kpi.Date from tbl_kpi, tbl_kpi_changes WHERE tbl_kpi.FieldID = tbl_kpi_changes.KpiID and Memid = ".$row['memid']." and Data like '%paymenttype%' order by tbl_kpi.Date";

$row2 = mysql_fetch_assoc(dbRead($query1,"etxint_log"));

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row['memid'] ?></td>
			<td width="220"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row['companyname'] ?></a></td>
			<td width="20" align="center"><?= $row['status'] ?></td>
			<td width="25"><?= $row['paymenttype'] ?></td>
			<td width="25"><?= $row['accountno'] ?></td>
			<td width="25"><?= $row['expires'] ?></td>
			<td width="162"><?= $row['fiftyclub'] ?> - <?= date("jS M y", strtotime($row2['Date'])); ?></td>
		</tr>
<?

$totalamount += $row[amount];
$foo++;

}

}

if(!$data) {
?>
		<tr class="Heading">
			<td width="40">&nbsp;</td>
			<td width="220">&nbsp;</td>
			<td width="20">&nbsp;</td>
			<td width="25" align="right">&nbsp;</td>
			<td width="25" align="right">&nbsp;</td>
			<td width="25" align="right"><?= get_word("52") ?>:</td>
			<td width="62"><?= number_format($totalamount,2) ?>&nbsp;</td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="7" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"></td>
	</tr>
</table>
</td>
</tr>
</table>

</body>
</html>

