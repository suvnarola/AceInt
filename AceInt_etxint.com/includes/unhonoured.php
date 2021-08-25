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
<body onload="javascript:setFocus('mem','memid');">

<form method="POST" action="body.php?page=unhonoured" name="mem">
<input type="hidden" name="GO" value="1">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="620" height="57">
<tr>
<td class="Border" height="54">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="40" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" width="580" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
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
} else {

//$query1 = dbRead("select feespaid.* from feespaid where feespaid.type in (1) and feespaid.memid='".$_REQUEST['memid']."' and amountpaid > 0 order by paymentdate desc limit 10");
$query1 = dbRead("select sum(deducted_fees) as deducted_fees, sum(amountpaid) as amountpaid, max(paymentdate) as paymentdate, max(id) as id from feespaid where feespaid.type in (1) and feespaid.memid='".$_REQUEST['memid']."' and amountpaid > 0 group by transID order by paymentdate desc limit 10");

?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>
<body onload="javascript:setFocus('mem','memid');">

<form method="post" action="/general.php" name = "mem">

<input type="hidden" name="charge" value="1">
<input type="hidden" name="type" value="4">




<table width="620" cellspacing="0" cellpadding="3">
  <tr>
    <td width="80" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("41") ?>:</b></font></td>
    <td width="150" class="Heading2"><font size="1" face="Verdana"><b>Stationary Fees:</b></font></td>
    <td width="150" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("103") ?>:</b></font></td>
    <td class="Heading2" align="right"><font size="1" face="Verdana"><b>Reverse:</b></font></td>
  </tr>
  <?
   while($row1 = mysql_fetch_assoc($query1)) {
  ?>
  <tr>
	    <td width="80" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= get_all_added_characters($row1[paymentdate]) ?></font>&nbsp;</td>
	    <td width="120" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= get_all_added_characters($row1[deducted_fees]) ?></font>&nbsp;</td>
	    <td width="120" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= get_all_added_characters($row1[amountpaid]) ?></font>&nbsp;</td>
	    <td bgcolor="#DDDDDD" align="right"><font size="1" face="Verdana"><input type="checkbox" name="feesPaidID[]" value="<?= $row1[id] ?>"></font>&nbsp;</td>
  </tr>
  <?}?>
  <tr>
	    <td bgcolor="#DDDDDD" align="right" colspan="4"><input type="text" size="40" name="transferDetails" value="Dishonour Reversal of DD">&nbsp;</td>
  </tr>
</table>

	<input type="hidden" name="memberID" value="<?= $row['memid'] ?>">

<table width="620" border="0" cellpadding="1" cellspacing="0">

  <tr>
    <td colspan="5" align="right" bgcolor="#FFFFFF"><input type="submit" value="Go" name="unHonourReversal"></td>
  </tr>

</table>

</form>

</body>
</html>

<?}
} else {?>

<html>
<body onload="javascript:setFocus('mem','memid');">

<form method="POST" action="body.php?page=unhonoured" name="mem">


<table border="0" cellpadding="1" cellspacing="1" width="620">
<input type="hidden" name="GO" value="1">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="40" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" width="580" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
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
