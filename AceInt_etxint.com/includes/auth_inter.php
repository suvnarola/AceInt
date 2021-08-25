<?

if(!checkmodule("IntAuthCheck")) {

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
 add_kpi("18","0");
}

if(!$HTTP_POST_VARS[data]) {
 $HTTP_POST_VARS[data]=$HTTP_GET_VARS[data];
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

<body onload="javascript:setFocus('IntAuthSearch','data');">

<form method="post" action="body.php?page=auth_inter" name="IntAuthSearch">
<input type="hidden" name="search" value="1">
<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2"><b><?= get_word("107") ?>:</b></td>
      <td width="450" bgcolor="#FFFFFF"><input type="text" name="data" value="<?= $HTTP_POST_VARS[data] ?>" size="20" onKeyPress="return number(event)"></td>
  </tr>
  <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>" name="search2">&nbsp;</td>
  </tr>

</table>
</td>
</tr>
</table>

</form>

<?

if($HTTP_POST_VARS[search] || $HTTP_GET_VARS[search]) {

$time_start=getmicrotime();

$cquery=dbRead("select * from country order by name");
while($crow = mysql_fetch_assoc($cquery)) {

$dbgetnoncheckedtrans=dbRead("SELECT transactions.id, transactions.memid, transactions.date, transactions.to_memid, transactions.buy, transactions.sell, transactions.type, transactions.authno,members.memid FROM members,transactions WHERE ((transactions.to_memid = members.memid) AND ((((transactions.to_memid in ($crow[facacc],$crow[refacacc])) AND (transactions.checked = 1)) AND (members.CID = $crow[countryID])) OR (((transactions.to_memid = $crow[reserveacc]) AND (transactions.checked = 1)) AND (members.CID = $crow[countryID]))))");

$counter=mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("3") ?> <b><?= get_all_added_characters($crow[name]) ?>:</b> <?= $counter ?></td>
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
$totalamount=0;
if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF"><?= get_word("184") ?>.</font></td>
		</tr>
<?

} else {

$foo=0;



while(list($id, $memid, $date, $to_memid, $buy, $sell, $type, $authno)=mysql_fetch_row($dbgetnoncheckedtrans)) {

	$dis_date=date("d/m/Y", $date);
	$dbgetname=dbRead("select companyname as buyername from members where memid='$memid'");
	list($buyername)=mysql_fetch_row($dbgetname);

	$dbgetname2=dbRead("select companyname as sellername from members where memid='$to_memid'");
	list($sellername)=mysql_fetch_row($dbgetname2);

	if($type == 2) {

		$amount1=$sell;
		$amount=number_format($amount1,2);

	} elseif($type == 1) {

		$amount1=$buy;
		$amount=number_format($amount1,2);

	}

	$cfgbgcolorone="#CCCCCC";
	$cfgbgcolortwo="#EEEEEE";
	$bgcolor=$cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $dis_date ?></td>
			<td width="200"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $id ?>');" class="nav"><?= get_all_added_characters($sellername) ?></a></td>
			<td width="200"><?= get_all_added_characters($buyername) ?></td>
			<td width="88"><?= $amount ?></td>
			<td width="42"><?= get_all_added_characters($authno) ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $id ?>"></td>
		</tr>
<?

$totalamount+=$amount1;
$foo++;

}

}

if(!$data) {
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
		    $time_end=getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="checktransactions2"></td>
	</tr>
</table>
</td>
</tr>
</table>

<?

#end country loop
}

?>

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

</body>
</html>

<?

}

?>