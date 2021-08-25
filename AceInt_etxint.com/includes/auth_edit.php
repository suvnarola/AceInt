<?

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

if(!checkmodule("AuthEdit")) {

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
 add_kpi("17","0");
}

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">

</head>

<body onload="javascript:setFocus('Auth','data');">

<form method="post" action="body.php?page=auth_edit" name="Auth">

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

if(!$_REQUEST['data']) {

 $counter = 0;

} else {

 $dbgetnoncheckedtrans = dbRead("select * from transactions WHERE authno like '%".addslashes($_REQUEST['data'])."' order by date");
 $counter = mysql_num_rows($dbgetnoncheckedtrans);

}
?>
<br>
<form name="edittrans" method="post" action="general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Records: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="8" align="center" class="Heading"><?= get_page_data("1") ?></td>
	</tr>
	<tr>
		<td colspan="8" align="center" class="Heading"><font color="#FF0000"><b><?= $_REQUEST['error'] ?></b></font></td>
	</tr>	
	<tr>
		<td class="Heading2"><b><?= get_word("41") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("117") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("118") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("43") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("44") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("106") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_page_data("5") ?>:</b></td>
		<td class="Heading2"><b><?= get_word("80") ?>:</b></td>
	</tr>

<?

if(@mysql_num_rows($dbgetnoncheckedtrans) == 0 || !$_REQUEST['data']) {
 $notrans = true;
?>
		<tr>
			<td colspan="8" bgcolor="#FFFFFF">No Transactions.</font></td>
		</tr>
<?

} else {

$foo = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
	
	$datecheck = date("Y-m-d", mktime(0,0,0,date("m"),1,date("Y")));
	
?>
		<input type="hidden" name="id[]" value="<?= $row['id'] ?>">
		<input type="hidden" name="authno[]" value="<?= $row['authno'] ?>">		
		<tr bgcolor="<?= $bgcolor ?>">
		   <? if(date("Y-m", strtotime($row['dis_date'])) == date("Y-m")) {?>
			<td><input size="10" type="text" name="dis_date[]" value="<?= $row['dis_date'] ?>" size="20" style="font-family: Verdana; font-size: 8pt"></td>
		   <?} else {?>
			<td><?= $row['dis_date'] ?></td>
		    <input type="hidden" name="dis_date[]" value="<?= $row['dis_date'] ?>">		
	       <?}?>
			<td><?= $row['memid'] ?></td>
			<td><?= $row['to_memid'] ?></td>
			<td><?= $row['buy'] ?></td>
			<td><?= $row['sell'] ?></td>
			<td><?= $row['dollarfees'] ?></td>
			<td><? if($row['checked'] == 0) { echo " C"; } ?>
			    <? if($row['checked'] == 1) { echo " U"; } ?>
			</td>
			<td><input size="22" type="text" name="details[]" value="<?= $row['details'] ?>" size="20" style="font-family: Verdana; font-size: 8pt"></td>
		</tr>
<?
if($row['dollarfees'] > 0) {?>
  <input type="hidden" value="<?= $row['buy'] ?>" name="buy[]">
<?
}

//if($row['dis_date'] < $datecheck || $row['dollarfees'] < 0)  {?>
  <input type="hidden" value="<?= $row['dollarfees'] ?>" name="dollarfees[]">
<?
//}
$totalamount += $amount;
$foo++;

}

}

?>
		<tr>
		    <td colspan="8" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
<?
 if(!$notrans) {
  ?>
	<tr>
		<td colspan="8" align="right" bgcolor="#FFFFFF"><input type="submit" value="Update Transactions." name="edittrans"></td>
	</tr>
  <?
 }
?>
</table>
</td>
</tr>
</table>

<? if($_REQUEST['redirectpage']) { echo '<input type="hidden" value="'.urlencode($_REQUEST['redirectpage']).'" name="redirectpage"> '; } ?>
<input type="hidden" value="1" name="edittrans">
<input type="hidden" value="<?= $_REQUEST['data'] ?>" name="data">
</form>

</body>
</html>

<?

}

?>