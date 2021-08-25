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
<?if($_REQUEST['currentmonth']) {
 $dd = $_REQUEST['currentmonth'];
} else {
 $dd = date("m");
}

if($_REQUEST['currentyear']) {
 $yy = $_REQUEST['currentyear'];
} else {
 $yy = date("Y");
}
?>
<body>

<form method="post" action="body.php?page=ccdeclined">

<table width="600" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("1") ?></td>
  </tr>
  <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($dd == "1") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($dd == "2") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($dd == "3") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($dd == "4") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($dd == "5") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($dd == "6") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($dd == "7") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($dd == "8") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($dd == "9") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($dd == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($dd == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($dd == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',$yy);

	   	?>
		</td>
	</tr>
   <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>" name="search">&nbsp;</td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="search" value="1">

</form>

<?

if($_REQUEST['search']) {

 if(checkmodule("Log")) {
  add_kpi("25", $_REQUEST['memid']);
 }

$time_start = getmicrotime();

$date = $_REQUEST['currentyear']."-".$_REQUEST['currentmonth'];
$query  = "select * from credit_transactions, members WHERE credit_transactions.memid=members.memid and type='5' and success='NO' and date like '$date-%' and members.CID = ".$_SESSION['User']['CID']." order by companyname";

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
		<td width="180" class="Heading2"><b><?= get_word("186") ?>:</b></font></td>
		<td width="25" class="Heading2"><b><?= get_word("86") ?>:</b></font></td>
		<td width="25" class="Heading2"><b>Club:</b></font></td>
		<td width="62" class="Heading2"><b><?= get_word("61") ?>:</b></font></td>
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

	$Response = get_error($row['response_code']);
?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row['memid'] ?></td>
			<td width="220"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= get_all_added_characters($row['companyname']) ?></a></td>
			<td width="20" align="center"><?= get_all_added_characters($row['status']) ?></td>
			<td width="180"><?= $Response ?>[<?= $row['response_code'] ?>]</td>
			<td width="25"><?= get_all_added_characters($row['paymenttype']) ?></td>
			<td width="25"><?= get_all_added_characters($row['fiftyclub']) ?></td>
			<td width="62"><?= number_format($row['amount'],2) ?></td>
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
			<td width="180" align="right">&nbsp;</td>
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

<?

}

?>