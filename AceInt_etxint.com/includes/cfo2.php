<?
if(!checkmodule("REReversals")) {

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

if($_REQUEST[done])  {
 echo "Reversal Done";
 die;
}

if($_REQUEST[cfo])  {

 if(checkmodule("Log")) {
  add_kpi("30", $_REQUEST['memid']);
 }
 
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body bgcolor="#FFFFFF" onload="javascript:setFocus('cfo','memid');">
<form method="post" action="/general.php" name="Rev">
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="2"><?= get_page_data("1") ?></font></b></font></p>
<table width="620" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="3">

  <tr bgcolor="#CCCCCC"> 
    <td width="60"><font size="1" face="Verdana"><b><?= get_word("50") ?>:</b></font></td>
    <td width="180"><font size="1" face="Verdana"><b><?= get_word("3") ?>:</b></font></td>
    <td width="120"><font size="1" face="Verdana"><b><?= get_word("80") ?>:</b></font></td>
    <td align="right" width="120"><font size="1" face="Verdana"><b><?= get_word("88") ?>:</b></font></td>
    <td align="right" width="120"><font size="1" face="Verdana"><b><?= get_word("61") ?>:</b></font></td>
  </tr>

<?

$dbtq = dbRead("select memid as memid2, sum(dollarfees) as feesowe from transactions where memid='".$_REQUEST['memid']."' group by memid");

while($row = mysql_fetch_assoc($dbtq)) {

if(!$row['memid2']) {

 $row['memid2'] = $_REQUEST['memid'];

}

if(!$row['feesowe']) {

 $row['feesowe'] = "0.00";

}

$dbgetcomname = dbRead("select companyname from members where memid='".$row['memid2']."'");
$row2 = mysql_fetch_assoc($dbgetcomname);

?>
	  <tr> 
		<input type="hidden" name="type" value="2">	  
		<input type="hidden" name="memberacc" value="<?= $row['memid2'] ?>">
	    <td width="60"><font size="1" face="Verdana"><?= $row['memid2'] ?></font>&nbsp;</td>
	    <td width="180"><font size="1" face="Verdana"><?= get_all_added_characters($row2['companyname']) ?></font>&nbsp;</td>
	    <td width="120"><textarea cols="15" rows="1" name="det"></textarea></td>
	    <td align="right" width="120"><font size="1" face="Verdana"><?= $_SESSION['Country']['currency'] ?><?= number_format($row['feesowe'],2) ?></font>&nbsp;</td>
		<td align="right" width="120"><font size="1" face="Verdana"><input size="14" type="text" name="amount" onKeyPress="return number(event)"></font></td>
	  </tr>
<?

}

?>
  <tr> 
    <td colspan="5" align="right"><input type="submit" value="<?= get_word("83") ?>" name="feereverse"></td>
  </tr>

</table>
<input type="hidden" name="feereverse" value="1">
<input type="hidden" name="ChangeMargin" value="1">
  </form>
  </body>
</html>

<?
die;
}
?>

<html>
<body onload="javascript:setFocus('cfo','memid');">

<form method="POST" action="body.php?page=cfo2" name="cfo">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="Heading2"><b><?= get_word("50") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="cfo"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>