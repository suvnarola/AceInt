<?

if(!checkmodule("AuthCheck")) {

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

if(checkmodule("Log")) {
 add_kpi("16","0");
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

<body onload="javascript:setFocus('Auth','data');">

<form method="post" action="body.php?page=auth_search" name="Auth">

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2"><b><?= get_word("107") ?>:</b></td>
      <td width="450" bgcolor="#FFFFFF"><input type="text" name="data" value="<?= $_REQUEST['data'] ?>" size="20"></td>
  </tr>
  <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("83") ?>" name="search">&nbsp;</td>
  </tr>

</table>
</td>
</tr>
</table>

<input type="hidden" name="search" value="1">

</form>

<?

if($_REQUEST['search']) {

$time_start = getmicrotime();

if($_SESSION['User']['CID'] == 2222) {
	$dbgetnoncheckedtrans = dbRead("select transactions.id, transactions.memid, transactions.date, transactions.to_memid, transactions.buy, transactions.sell, transactions.type, transactions.authno, members.memid as memmemid from transactions, members WHERE ((((((`members`.`CID` = ".$_SESSION['User']['CID'].") AND (`transactions`.`memid` = `members`.`memid`)) AND (`transactions`.`checked` = 1)) AND (transactions.authno like '%".addslashes($_REQUEST['data'])."%') and transactions.sell < 50000) AND (transactions.to_memid not like '" . $_SESSION['Country']['facacc'] . "')) and (transactions.to_memid not like '" . $_SESSION['Country']['reserveacc'] . "')) order by transactions.date");
} else {
	$dbgetnoncheckedtrans = dbRead("select transactions.id, transactions.memid, transactions.date, transactions.to_memid, transactions.buy, transactions.sell, transactions.type, transactions.authno, members.memid as memmemid from transactions, members WHERE ((((((`members`.`CID` = ".$_SESSION['User']['CID'].") AND (`transactions`.`memid` = `members`.`memid`)) AND (`transactions`.`checked` = 1)) AND (transactions.authno like '%".addslashes($_REQUEST['data'])."%')) AND (transactions.to_memid not like '" . $_SESSION['Country']['facacc'] . "')) and (transactions.to_memid not like '" . $_SESSION['Country']['reserveacc'] . "')) order by transactions.date");
}
$counter = @mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("2") ?>: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("41") ?>:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("117") ?>:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("118") ?>:</b></font></td>
		<td width="88" class="Heading2"><b><?= get_word("61") ?>:</b></font></td>
		<td width="42" class="Heading2"><b><?= get_word("107") ?>:</b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>

<?

if(@mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF"><?= get_word("184") ?>.</font></td>
		</tr>
<?

} else {

$foo = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$dis_date = date("d/m/Y", $row['date']);

	$dbgetname = dbRead("select companyname as buyername from members where memid='".$row['memid']."'");
	$buyername = mysql_fetch_assoc($dbgetname);

	$dbgetname2 = dbRead("select companyname as sellername from members where memid='".$row['to_memid']."'");
	$sellername = mysql_fetch_assoc($dbgetname2);

	if($row['type'] == 2) {

		$amount = $row['sell'];

	} elseif($row['type'] == 1) {

		$amount = $row['buy'];

	}

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $dis_date ?></td>
			<td width="200"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $row['id'] ?>');" class="nav"><?= get_all_added_characters($sellername['sellername']) ?></a></td>
			<td width="200"><?= get_all_added_characters($buyername['buyername']) ?></td>
			<td width="88"><?= number_format($amount,2) ?></td>
			<td width="42"><?= get_all_added_characters($row['authno']) ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row['id'] ?>"></td>
		</tr>
<?

$totalamount += $amount;
$foo++;

}

}

if(!$_REQUEST['data']) {
?>
		<tr bgcolor="#FFFFFF">
			<td width="40">&nbsp;</td>
			<td width="200">&nbsp;</td>
			<td width="200" align="right">&nbsp;</td>
			<td width="88"><?= number_format($totalamount,2) ?></td>
			<td width="42">&nbsp;</td>
			<td width="30"></td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="6" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="checktransactions"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" value="<?= $_REQUEST['data'] ?>" name="data">
</form>

</body>
</html>

<?

}

?>