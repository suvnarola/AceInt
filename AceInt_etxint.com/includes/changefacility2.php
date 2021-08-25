<?

if(!checkmodule("REFacility")) {

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

if($_REQUEST['changefacility2']) {

$memberq = dbRead("select contactname, companyname, reoverdraft from members where memid='".$_REQUEST['memid']."'");
$memberrow = mysql_fetch_assoc($memberq);

// check facility funds.

$query = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".$_SESSION['Country']['refacacc']."'");
$row = mysql_fetch_assoc($query);

$extraamount = $_REQUEST['newfacility'] - $memberrow['reoverdraft'];

if($row[cb] < $extraamount) {

?>
<html>
<body>

<form method="POST" action="body.php?page=changefacility2">
<input type="hidden" name="prevfacility" value="<?= get_all_added_characters($row2['curfacility']) ?>">
<input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_page_data("6") ?>.</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_REQUEST['memid'] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow['companyname']) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow['contactname']) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("188") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country'][currency] ?><?= number_format($row2['curfacility'],2) ?></td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%">
        <input type="text" name="newfacility" size="20" onKeyPress="return number(event)" value="<?= get_all_added_characters($_REQUEST['newfacility']) ?>"></td>
	</tr>
	<tr>
		<td class="Heading2" align="right" width="20%"></td>
		<td bgcolor="#FFFFFF" width="80%"><input type="submit" value="<?= get_word("83") ?>" name="changefacility2"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changefacility2" value="1">

</form>

</body>
</html>
<?


die;
}

#update members details and run facility include and return an information page.
dbWrite("update members set reoverdraft = '".$_REQUEST['newfacility']."' where memid = '".$_REQUEST['memid']."'");
$memid = $_REQUEST['memid'];
require("includes/facility2.php");

if(checkmodule("Log")) {
 add_kpi("20",$_REQUEST['memid']);
}

if($_SESSION['User']['MaxTransfer'] >= $_REQUEST['newfacility']) {
 $clear = "Cleared";
} else {
 $clear = "Not Cleared";
}

?>
<html>
<body>

<form method="POST" action="body.php?page=changefacility2">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("190") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_REQUEST['memid'] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow['companyname']) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow['contactname']) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("189") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST['prevfacility'],2) ?></td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST['newfacility'],2) ?>&nbsp;(<font color="#FF0000"></font>)</td>
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
				<li><?= eval(" ?>".get_page_data("7")."<? ") ?>.</li>
				<li><?= get_page_data("8") ?>.</li>
				<li><font color="#FF0000"><?= get_page_data("9") ?>.</font></li>
				<li><?= get_page_data("10") ?>.</li>
				<li><?= get_page_data("11") ?>.</li>
			</ul>
		</td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>
<?

die;
}

if($_REQUEST['changefacility']) {

#check to see if the account number exists.

#check to see what current facility the members has.
$query2 = mysql_db_query($db, "select (sum(sell)-sum(buy)) as curfacility from transactions where memid='".$_REQUEST['memid']."' and (to_memid='".$_SESSION['Country']['refacacc']."')", $linkid);
$row2 = mysql_fetch_assoc($query2);

$memberq = mysql_db_query($db, "select contactname, companyname, memid from members where memid='".$_REQUEST['memid']."'  and CID = '".$_SESSION['User']['CID']."'", $linkid);
$memberrow = mysql_fetch_assoc($memberq);

if(!$memberrow['memid']) {

 // member doesnt exist. bomb out with error.
 
 ?>
<html>
<body onload="javascript:setFocus('FAC','memid');">

<form method="POST" action="body.php?page=changefacility2" name="FAC">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_word("82") ?>.</td>
</tr>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="100" align="left"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" width="40"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="changefacility"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changefacility" value="1">

</form>

</body>
</html>
 <?
 
die;
}

?>
<html>
<body onload="javascript:setFocus('FAC','newfacility');">

<form method="POST" action="body.php?page=changefacility2" name="FAC">
<input type="hidden" name="prevfacility" value="<?= $row2['curfacility'] ?>">
<input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="30%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="70%"><?= $_REQUEST['memid'] ?></td>
	</tr>
    <tr>
		<td class="Heading2" width="30%" align="right"><b><?= get_word("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="70%"><?= get_all_added_characters($memberrow['companyname']) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="30%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="70%"><?= get_all_added_characters($memberrow['contactname']) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="30%" align="right"><b><?= get_word("188") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="70%"><?= $_SESSION['Country']['currency'] ?><?= number_format($row2['curfacility'],2) ?></td>
	</tr>
		<td class="Heading2" width="30%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="70%">
        <input type="text" name="newfacility" size="20" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" align="right" width="30%"></td>
		<td bgcolor="#FFFFFF" width="70%"><input type="submit" value="<?= get_word("83") ?>" name="changefacility2"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changefacility2" value="1">

</form>

</body>
</html>
<?

die;
}

?>

<html>
<body onload="javascript:setFocus('FAC','memid');">

<form method="POST" action="body.php?page=changefacility2" name="FAC">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


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
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="changefacility"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changefacility" value="1">

</form>

</body>
</html>