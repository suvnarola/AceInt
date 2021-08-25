<?

if(!checkmodule("MemOrder")) {

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

$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_REQUEST['addmember']) {

#check to see if the account number exists.

$memberq = mysql_db_query($db, "select * from members where memid='".$_REQUEST['memid']."' and CID = '".$_SESSION['User']['CID']."'", $linkid);
$memberrow = mysql_fetch_assoc($memberq);

if($_REQUEST['confirm']) {

$authno=mt_rand(1000000,99999999);
$t=mktime();
$d=date("Y-m-d");

dbWrite("insert into memcards (memid,date,type,userid) values ('".$_REQUEST[memid]."','$date','1','".$_SESSION['User']['FieldID']."')");
dbWrite("insert into transactions values('".$_REQUEST[memid]."','$t','9845','0','0','0','5.00','3','Member Card Charge','$authno','$d','','0','','".$_SESSION['User']['FieldID']."')");
dbWrite("update members set fee_deductions = fee_deductions+5 where memid = '".$_REQUEST[memid]."'"); 

if(checkmodule("Log")) {
 add_kpi("14",$_REQUEST['memid']);
}

?>
<html>
<body>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">

<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("1") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_REQUEST[memid] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[companyname]) ?></td>
	</tr>
</table>
</td>
</tr>
</table>

</body>
</html>

<?
die;
}

if(!$memberrow[memid]) {

 // member doesnt exist. bomb out with error.

 ?>
<html>
<body onload="javascript:setFocus('OrderMemCards','memid');">

<form method="POST" action="body.php?page=ordermemcards" name="OrderMemCards">
<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="40" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addmember"></td>
	</tr>
</table>
</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" bgcolor="#FFFFFF">
			<ul>
				<li><?= get_page_data("5") ?></li>
				<li><?= get_page_data("6") ?>.</li>
			</ul>
		</td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="addmember" value="1">

</form>

</body>
</html>
 <?
 
die;
} 
?>

<html>
<body>

<form method="POST" action="body.php?page=ordermemcards" name="OrderCheque">
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">

<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("80") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_REQUEST[memid] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[companyname]) ?></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("93") ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("13") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalno]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("14") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalname]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("15") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalsuburb]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("16") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalcity]) ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("17") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalstate]) ?></td>
	</tr>	
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("18") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[postalpostcode]) ?></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("89") ?>" name="confirm"></td>
	</tr>			
</table>
</td>
</tr>
</table>
<input type="hidden" name="confirm" value="1">
<input type="hidden" name="addmember" value="1">
<input type="hidden" name="memid" value="<?= $_REQUEST[memid] ?>">
</form>

</body>
</html>
<?
die;
}
?>


<html>
<body onload="javascript:setFocus('OrderMemCards','memid');">

<form method="POST" action="body.php?page=ordermemcards" name="OrderMemCards">

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="40" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addmember"></td>
	</tr>
</table>
</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" bgcolor="#FFFFFF">
			<ul>
				<li><?= get_page_data("5") ?>.</li>
				<li><?= get_page_data("6") ?>.</li>
			</ul>
		</td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="addmember" value="1">

</form>

</body>
</html>