<?
//include("global.php");

if(!checkmodule("EditMemberLevel2")) {

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

// Start of Write Off Script.
$trans_date = date("Y-m-d");

if($_REQUEST['next'] == 1) {

 die;

} elseif($_REQUEST['next'] == 2) {

  $memid = $_REQUEST[memid];
  $query2 = dbRead("select * from transactions where memid = '".$_REQUEST['memid']."' and to_memid in (".$_SESSION['Country']['writeoff'].",".$_SESSION['Country']['w_trust'].")");
  while ($row2 = mysql_fetch_assoc($query2)) {
	  // Delete transactions
	  dbWrite("delete from transactions where id = ".$row2['id']."");
  }

  dbWrite("update members set status = 0 where memid = ".$_REQUEST[memid]."");
  $note = "Member has been reactivated.";
  dbWrite("insert into notes (memid, date, userid, note, type) values ('".$_REQUEST[memid]."', '".date("y-m-d")."', '".$_SESSION['User']['FieldID']."','".$note."','1')");
  add_kpi("57", $_REQUEST[memid]);
  echo get_page_data("9");
  die;

  print($query2);
}

$query = dbRead("select * from members where memid='".$_REQUEST['memid']."'");
$row = mysql_fetch_assoc($query);
?>
<html>
<body onload="javascript:setFocus('writeoff','memid');">

<form method="POST" action="body.php?page=wreactive" name="writeoff">
<input type="hidden" name="letter_no" value="5">
<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b><?= get_word("50") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF">
		 <?
		  $feesowing = feesowing($_REQUEST['memid']);
		  $tradeowing = tradeowing($_REQUEST['memid']);

		  if($_REQUEST['memid']) {

		   ?><input type="hidden" name="ChangeMargin" value="1"><input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>"><?= $_REQUEST['memid'] ?><?

		  } else {

		   ?><input type="text" size="10" name="memid" onKeyPress="return number(event)"><?

		  }
		 ?>
		</td>
	</tr>
	<tr>
	    <td width="130" align="right" class="Heading2"><b><?= get_word("47") ?>:</b></td>
	    <td align="left" bgcolor="#FFFFFF"><input type="hidden" name="feesowing" value="<?= $feesowing ?>"><?= $feesowing ?></td>
	</tr>
	<tr>
	    <td width="130" align="right" class="Heading2"><b><?= get_word("55") ?>:</b></td>
	    <td align="left" bgcolor="#FFFFFF"><input type="hidden" name="tradeowing" value="<?= $feesowing ?>"><?= $tradeowing ?></td>
	</tr>
	<?if(($feesowing != '0.00' && $row[status] != 6) || ($tradeowing != '0.00' && $row[status] != 6)) {?>
	<tr>
	    <td width="130" align="right" class="Heading2"></td>
	    <td align="left" bgcolor="#FF0000"><?= get_page_data("5") ?></td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b><?= get_page_data("2") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF">
        <input type="checkbox" name="reminder" checked value="1"></td>
	</tr>
	<tr>
	    <td width="130" align="right" class="Heading2">
	    </td>
	    <td align="left" bgcolor="#FFFFFF" >
	    <a href="/includes/lettersend.php?Client=<?= $_REQUEST['memid'] ?>&letter=1&type=1&letter_no=5&amount=<?= $amountcheck ?>" class="nav"><b><?= get_page_data("4") ?></b></a>
	    <input type="hidden" name="next" value="2"></td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b></b></td>
		<td align="left" bgcolor="#FFFFFF"><?= get_page_data("6") ?></td>
	</tr>
	<?} else {
	   if($feesowing != '0.00' || $tradeowing != '0.00')  {?>
	<tr>
	    <td width="130" align="right" class="Heading2"></td>
	    <td align="left" bgcolor="#FF0000"><?= get_page_data("7") ?></td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b><?= get_page_data("3") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF">
        <input type="checkbox" name="WriteOff" value="1"><input type="hidden" name="next" value="2"></td>
	</tr>
	  <?} else {?>
	    <input type="hidden" name="next" value="2">
	    <input type="hidden" name="WriteOff" value="2">
	  <?}
	}?>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="Continue"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

</body>
</html>
