<?

if(!checkmodule("REFeePayment")) {

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

if($_REQUEST[next])  {

$dbgetcomname=dbRead("select memid from members where memid='$_REQUEST[memid]' and CID = '".$_SESSION['User']['CID']."'");
$memberrow = mysql_fetch_assoc($dbgetcomname);

if(!$memberrow[memid]) {
?>

<html>
<body onload="javascript:setFocus('Fee','memid');">

<form method="POST" action="body.php?page=feepayment3" name="Fee">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td width="100" align="right" class="Heading2"><b><?= get_word("50") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF" width="599"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" align="right" width="40"></td>
		<td bgcolor="#FFFFFF" width="599"><input type="submit" value="<?= get_word("83") ?>"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="next" value="1">
</form>

</body>
</html>

<?
die;
}?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body onload="javascript:setFocus('Fee3','othermemid');">

<form method="post" action="/general.php" name="Fee3">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="40" class="Heading2"><b><?= get_word("50") ?>:</b></td>
    <td width="150" class="Heading2"><b><?= get_word("3") ?>:</b></td>
    <td align="right" width="60" class="Heading2"><?= get_page_data("2") ?></td>
    <td align="right" width="120" class="Heading2"><b><?= get_word("88") ?>:</b></td>
    <td align="right" width="120" class="Heading2"><b><?= get_word("61") ?>:</b></td>
  </tr>
<?

//$dbtq=dbRead("select memid, sum(dollarfees) as feesowe from transactions where memid='$memberrow[memid]' and to_memid in(".get_non_included_accounts($_SESSION['Country']['countryID'],true,true).") group by memid");
$dbtq=dbRead("select memid, sum(amount-payment) as feesowe from invoice_re where memid='$memberrow[memid]' and amount-payment > 0 group by memid");

while(list($memid2, $feesowe)=mysql_fetch_row($dbtq)) {

if(!$memid2) {
 $memid2=$memberrow[memid];
}

if(!$feesowe) {
 $feesowe="0";
}

$dbgetcomname=dbRead("select companyname, licensee from members where memid='$memid2'");
list($companyname, $licensee)=mysql_fetch_row($dbgetcomname);

$feesowe2=number_format($feesowe,2);

?>
	  <tr>
		<input type="hidden" name="memberacc" value="<?= $memid2 ?>">
	    <td width="40" bgcolor="#DDDDDD"><?= $memid2 ?>&nbsp;</td>
	    <td width="150" bgcolor="#DDDDDD"><?= $companyname ?>&nbsp;</td>
	    <td align="right" width="120" bgcolor="#DDDDDD"><?= $_SESSION['Country']['currency'] ?><?= $feesowe2 ?>&nbsp;</td>
		<td align="right" width="120" bgcolor="#DDDDDD"><input size="12" type="text" name="amount" onKeyPress="return number(event)"></td>
	  </tr>
<?

}

?>
  <tr>
    <td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="fees"></td>
  </tr>

</table>
</td>
</tr>
</table>

<input type="hidden" name="rolloverpayment" value="1">

</form>

</body>
</html>

<?
die;
}?>

<html>
<body onload="javascript:setFocus('Fee','memid');">

<form method="POST" action="body.php?page=feepayment3" name="Fee">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td width="100" align="right" class="Heading2"><b><?= get_word("50") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF" width="599"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" align="right" width="40"></td>
		<td bgcolor="#FFFFFF" width="599"><input type="submit" value="<?= get_word("83") ?>"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="next" value="1">
</form>

</body>
</html>
