<?

if(!checkmodule("Conversion")) {

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

if($_REQUEST[memid]) {

 $memberq = dbRead("select contactname, companyname, memid from members where memid='$_REQUEST[memid]' and CID = '".$_SESSION['User']['CID']."'");
 $memberrow = mysql_fetch_assoc($memberq);

 if(!$memberrow[memid]) {
  
  // member does not exist. bomb out with error.
  ?>
<html>
<body onload="javascript:setFocus('conversion','memid');">

<form method="POST" action="body.php?page=conversion" name="conversion">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

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
		<td colspan="2" align="center" class="Heading2"><?= get_word("187") ?>.</td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" align="right" width="20%"></td>
		<td bgcolor="#FFFFFF" width="80%"><input type="submit" value="<?= get_word("83") ?>" name="conv"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="conversion" value="1">

</form>

</body>
</html>
  <?
 die;
 }

}

if($_REQUEST[conversion]) {

?>
<html>
<body onload="javascript:setFocus('conversion','amount');">

<form method="POST" action="body.php?page=conversion" name="conversion">
<input type="hidden" value="<?= $_REQUEST[memid] ?>" name="memid">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("187") ?>.</td>
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
		<td class="Heading2" width="20%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[contactname]) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("61") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="text" size="14" name="amount" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("3") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="text" size="10" name="percent" onKeyPress="return number(event)"></td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%">
        <select size="1" name="tradeorg">
        <?
         
         $toquery = dbRead("select * from trade_exchange where CID='".$_SESSION['User']['CID']."' order by Name");
         while($torow = mysql_fetch_assoc($toquery)) {
          ?>
           <option value="<?= $torow[FieldID] ?>"><?= $torow[Name] ?> [<?= number_format($torow[ChargeFeesPercent],1) ?>%]</option>
		  <?
		 }
        ?>
        </select></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%"></td>
		<td bgcolor="#FFFFFF" width="80%"><input type="submit" value="<?= get_word("83") ?>" name="conv"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="conversion2" value="1">

</form>

</body>
</html>
<?

die;
}

if($_REQUEST[conversion2]) {
 
 if($_REQUEST[percent]) {
 
  $conversionfee = ($_REQUEST[amount]/100)*$_REQUEST[percent];
 
 } else {
 
  $query = dbRead("select * from trade_exchange where FieldID = '$_REQUEST[tradeorg]'");
  $row = mysql_fetch_assoc($query);
  
  if($row[ChargeFees] == "Y") {
  
   $conversionfee = ($_REQUEST[amount]/100)*$row[ChargeFeesPercent];
  
  } else {
  
   $conversionfee = "0.00";
  
  }
 
 }
 
 $otherquery = dbRead("select * from trade_exchange where FieldID = '$_REQUEST[tradeorg]'");
 $otherrow = mysql_fetch_assoc($otherquery);
 
?>
<html>
<body onload="javascript:setFocus('conversion','receivedfrom');">

<form method="POST" action="body.php?page=conversion" name="conversion">
<input type="hidden" value="<?= $_REQUEST[memid] ?>" name="memid">
<input type="hidden" value="<?= $_REQUEST[amount] ?>" name="amount">
<input type="hidden" value="<?= $_REQUEST[tradeorg] ?>" name="tradeorg">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("187") ?>.</td>
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
		<td class="Heading2" width="20%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[contactname]) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("61") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country'][currency]?><?= number_format($_REQUEST[amount],2) ?></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="text" size="30" name="receivedfrom" value="<?= get_all_added_characters($memberrow[companyname]) ?>"></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("6") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="text" size="10" name="conversionfee" value="<?= number_format($conversionfee,2,'',''); ?>" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($otherrow[Name]) ?></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%"></td>
		<td bgcolor="#FFFFFF" width="80%"><input type="submit" value="<?= get_word("89") ?>" name="conv"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="conversion3" value="1">

</form>

</body>
</html>
<?

die;
}

if($_REQUEST[conversion3]) {

 $query2 = dbRead("select * from trade_exchange where FieldID='$_REQUEST[tradeorg]' and CID='".$_SESSION['User']['CID']."'");
 $row2 = mysql_fetch_assoc($query2);

 #get balance of reserve account out.
 $query = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".$_SESSION['Country']['reserveacc']."'");
 $row = mysql_fetch_assoc($query);

 if($row[cb] < $_REQUEST[amount]) {
  #reserve account doesnt have enough funds. display error.
  ?>
<html>
<body>

<form method="POST" action="body.php?page=conversion" name="conversion">
<input type="hidden" value="<?= $_REQUEST[memid] ?>" name="memid">
<input type="hidden" value="<?= $_REQUEST[amount] ?>" name="amount">
<input type="hidden" value="<?= $_REQUEST[tradeorg] ?>" name="tradeorg">
<input type="hidden" value="<?= $conversionfee ?>" name="conversionfee">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


<table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
<tr>
<td bgcolor="#FFFFFF"><?= get_page_data("8") ?>.</td>
</tr>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("187") ?>.</td>
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
		<td class="Heading2" width="20%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[contactname]) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("61") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST[amount],2) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("6") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country']['currency'] ?><?= number_format($conversionfee,2); ?></td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($row2[Name]) ?></td>
	</tr>
	<tr>
		<td class="Heading2" width="20%"></td>
		<td bgcolor="#FFFFFF" width="80%"><input type="submit" value="<?= get_word("83") ?>" name="conv"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="conversion3" value="1">

</form>

</body>
</html>
<?

die;
}

 $query2 = dbRead("select * from trade_exchange where FieldID='$_REQUEST[tradeorg]' and CID='".$_SESSION['User']['CID']."'");
 $row2 = mysql_fetch_assoc($query2);

 #insert the transaction.
 $t = mktime();
 $t2 = $t-951500000;
 $t3 = mt_rand(1000,9000);
 $authno = $t2-$t3;
 $disdate = date("Y-m-d",$t);
 
 #if total facility is more than the maxtransfer make the new facility uncleared.
 if($_SESSION['User']['MaxTransfer'] >= $_REQUEST[amount]) {
  $checked = "0";
  $discheck = "Cleared";
 } else {
  $checked = "1";
  $discheck = "Not Cleared";
 }
 
 if(checkmodule("SuperUser")) {
  $checked = "0";
 }
 
 dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('".$_SESSION['Country']['reserveacc']."','$t','$_REQUEST[memid]','$_REQUEST[amount]','0','$_REQUEST[conversionfee]','1','Conversion','$authno','$disdate','0','".$_SESSION['User']['FieldID']."')");
 dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$_REQUEST[memid]','$t','".$_SESSION['Country']['reserveacc']."','0','$_REQUEST[amount]','0','2','Conversion','$authno','$disdate','$checked','".$_SESSION['User']['FieldID']."')");

 if(checkmodule("Log")) {
  add_kpi("21", $_REQUEST['memid']);
 }

?>
<html>
<body>
<form method="GET" action="includes/condocs.php" name="conversion">
<input type="hidden" value="<?= $_REQUEST[memid] ?>" name="memid">
<input type="hidden" value="<?= $_REQUEST[amount] ?>" name="amount">
<input type="hidden" value="<?= $row2[Name] ?>" name="tradeorg">
<input type="hidden" value="<?= $conversionfee ?>" name="conversionfee">
<input type="hidden" value="<?= $_REQUEST[receivedfrom] ?>" name="receivedfrom">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?>.</td>
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
		<td class="Heading2" width="20%" align="right"><b><?= get_word("5") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($memberrow[contactname]) ?></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("61") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST[amount],2) ?>&nbsp;(<font color="#FF0000"><?= $discheck ?>)</font></td>
	</tr>
		<tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("6") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= $_SESSION['Country']['currency'] ?><?= number_format($conversionfee,2,'',''); ?></td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_page_data("4") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><?= get_all_added_characters($row2[Name]) ?></td>
	</tr>
	</tr>
		<td class="Heading2" width="20%" align="right">&nbsp;</td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="submit" name="Submit" value="<?= get_page_data("7") ?>"></td>
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
				<li><?= eval(" ?>".get_page_data("9")."<? ") ?>.</li>
				<li><?= get_page_data("10") ?>.</li>
				<li><?= get_page_data("11") ?>.</li>
				<li><font color="#FF0000"><?= get_page_data("12") ?>.</font></li>
				<li><?= get_page_data("13") ?>.</li>
				<li><?= get_page_data("14") ?>.</li>
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

?>

<html>
<body onload="javascript:setFocus('conversion','memid');">

<form method="POST" action="body.php?page=conversion" name="conversion">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("187") ?>.</td>
	</tr>
		<td class="Heading2" width="20%" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left" width="80%"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" align="right" width="20%"></td>
		<td bgcolor="#FFFFFF" width="80%"><input type="submit" value="<?= get_word("83") ?>" name="conv"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="conversion" value="1">

</form>

</body>
</html>