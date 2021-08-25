<?
if($_SESSION['User']['FieldID'] == 0) {?><table width="620" border="0" cellpadding="1" cellspacing="0"> <tr>  <td class="Border">   <table width="100%" border="0" cellpadding="3" cellspacing="0">    <tr>     <td width="100%" align="center" class="Heading2">Page down for maintenance. If you need this urgently please email Antony on <a href="mailto:antony@ebanctrade.com">antony@ebanctrade.com</a></td>    </tr>   </table>  </td> </tr></table><?die;}
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

if($_REQUEST['next'])  {

$dbgetcomname = dbRead("select memid from members where memid='$_REQUEST[memid]' and CID = '".$_SESSION['User']['CID']."'");
$memberrow = mysql_fetch_assoc($dbgetcomname);

if(!$memberrow[memid]) {
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body onload="javascript:setFocus('Fee','memid');">

<form method="POST" action="body.php?page=feepayment" name="Fee">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="GO"></td>
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

<body onload="javascript:setFocus('Fee','amount');">

<form method="post" action="/general.php" name="Fee">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="80" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("50") ?>:</b></font></td>
    <td width="180" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("3") ?>:</b></font></td>
    <td width="120" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("80") ?>:</b></font></td>
    <td align="right" width="120" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("88") ?>:</b></font></td>
    <td align="right" width="120" class="Heading2"><font size="1" face="Verdana"><b><?= get_word("61") ?>:</b></font></td>
  </tr>
<?

$dbtq=mysql_db_query($db, "select memid, sum(dollarfees) as feesowe from transactions where memid='$memberrow[memid]' and to_memid not in(".get_non_included_accounts($_SESSION['Country']['countryID'],true,false,false,true).") group by memid", $linkid);
while(list($memid2, $feesowe, $letters)=mysql_fetch_row($dbtq)) {

    $dbgetdataout = dbRead("select * from members where memid = '$memid2'");
	$row2 = mysql_fetch_assoc($dbgetdataout);

	if($row2['letters'] == 1) {
	$bgcolor = "#33cc66";
	$foo % 2  ? 0: $bgcolor = "#009933";
	} elseif($row2['letters'] == 2) {
	$bgcolor = "#0080ff";
	$foo % 2  ? 0: $bgcolor = "#0050ff";
	} elseif($row2['letters'] == 3) {
	$bgcolor = "#cc00cc";
	$foo % 2  ? 0: $bgcolor = "#ee00ee";
	} elseif($row2['letters'] == 9) {
	$bgcolor = "#FF4444";
	$foo % 2  ? 0: $bgcolor = "#FF6666";
	} elseif($row2['letters'] == 4) {
	$bgcolor = "#fffc00";
	$foo % 2  ? 0: $bgcolor = "#f0d3oc";
	} else {
	$bgcolor = "#CCCCCC";
	$foo % 2  ? 0: $bgcolor = "#EEEEEE";
	}

if(!$memid2) {
 $memid2=$memberrow[memid];
}

if(!$feesowe) {
 $feesowe="0";
}

$dbgetcomname=mysql_db_query($db, "select companyname, licensee from members where memid='$memid2'", $linkid);
list($companyname, $licensee)=mysql_fetch_row($dbgetcomname);

$feesowe2=number_format($feesowe,2);

?>
	  <tr>
		<input type="hidden" name="memberacc" value="<?= $memid2 ?>">
	    <td width="80" bgcolor="<?= $bgcolor ?>"><font size="1" face="Verdana"><?= $memid2 ?></font>&nbsp;</td>
	    <td width="180" bgcolor="<?= $bgcolor ?>"><font size="1" face="Verdana"><?= get_all_added_characters($companyname) ?></font>&nbsp;</td>
	    <td width="120" bgcolor="<?= $bgcolor ?>"><font size="1" face="Verdana"><?= get_page_data("1") ?></font></td>
	    <td align="right" width="120" bgcolor="<?= $bgcolor ?>"><font size="1" face="Verdana"><?= $row5[currency]?><?= $feesowe2 ?></font></td>
		<td align="right" width="120" bgcolor="<?= $bgcolor ?>"><font size="1" face="Verdana"><input size="12" type="text" name="amount" onKeyPress="return number(event)"></font></td>
		<input type="hidden" value="Cash Fees Payment" name="det">
		<input type="hidden" value="<?= $licensee ?>" name="licensee">
	  </tr>
<?

}

?>
  <tr>
    <td colspan="5" align="right" bgcolor="#FFFFFF"><?if(checkmodule("ChargeFees")) {?><input type="checkbox" name="gst" value="1"> GST in Cash <input type="checkbox" name="trade" value="1"> <?= get_page_data("2") ?> <?}?><input type="submit" value="<?= get_word("83") ?>" name="fees"></td>
  </tr>

</table>
</td>
</tr>
</table>

<input type="hidden" value="1" name="feepayment1">
<input type="hidden" value="1" name="type">

</form>

</body>
</html>
<?

die;
}
?>


<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body onload="javascript:setFocus('Fee','memid');">

<form method="POST" action="body.php?page=feepayment" name="Fee">

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="GO"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="next" value="1">
</form>

</body>
</html>
