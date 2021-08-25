<?

$f = date("n")-1;
$g = date("Y");

if($_REQUEST['currentyear'])  {

$time_start=getmicrotime();

$prev_month = date("Y-m", mktime(1,1,1,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

$dbgetnewmem=mysql_db_query($db, "select sum(erewards_bank.amount_cash) as AmountCash, sum(erewards_bank.amount_trade) as AmountTrade, erewards_bank.memid as Memid, members.companyname as CompanyName, members.gst as gst, members.abn as abn from erewards_bank, members where (erewards_bank.memid = members.memid) and (erewards_bank.type = '1') and erewards_bank.date like '$prev_month%' group by erewards_bank.memid", $linkid);

?>
<br>
<form name="deletenewmem" method="post" action="/general.php">

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading">Total Trade and Cash paid 
        to Members <?= $prev_month ?></td>
	</tr>
	<tr>
		<td class="Heading2"><b>Acc No.:</b></font></td>
		<td class="Heading2"><b>Company Name:</b></font></td>
		<td class="Heading2"><b>GST:</b></font></td>
		<td class="Heading2" align="right"><b>Amount Cash:</b></font></td>
		<td class="Heading2" align="right"><b>Amount Trade:</b></font></td>
		<td class="Heading2"><b>Amount GST:</b></font></td>
	</tr>

<?

if(@mysql_num_rows($dbgetnewmem) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF">No Cash Or Trade Paid to Members</td>
		</tr>
<?

} else {

$foo=0;
$tcash=0;
$ttrade=0;
$tgst=0;

while($row = mysql_fetch_array($dbgetnewmem)) {

	$cfgbgcolorone="#CCCCCC";
	$cfgbgcolortwo="#EEEEEE";
	$bgcolor=$cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;
	
	if($row[gst] == "Y" && $row[abn]) {

     $amounttotal = $row[AmountCash]+$row[AmountTrade];	
     $gstamount = number_format((($amounttotal/(100+$_SESSION['Country'][tax]))*$_SESSION['Country'][tax]), 2);
  
    } else {
    
     $gstamount = "0.00";
    
    }
    
    $tcash=$tcash+$row[AmountCash];
	$ttrade=$ttrade+$row[AmountTrade];
	$tgst=$tgst+$gstamount;
  
if(!$row[CompanyName]) { $row[CompanyName]="(No Companyname)"; }


?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td><?= $row[Memid] ?></td>
			<td><?= $row[CompanyName] ?></a></td>
			<td><?= $row[gst] ?></a></td>
			<td align="right"><?= $row[AmountCash] ?></td>
			<td align="right"><?= $row[AmountTrade] ?></td>
			<td align="right"><?= $gstamount ?></td>			
		</tr>
<?

$foo++;

}

}

    $tcash=number_format($tcash,2);
	$ttrade=number_format($ttrade,2);
	$tgst=number_format($tgst,2);

?>
		<tr class='Heading'>
		    <td colspan="3" align="right">TOTAL:</td>
			<td align="right"><?= $tcash ?></td>
			<td align="right"><?= $ttrade ?></td>
			<td align="right"><?= $tgst ?></td>
		</tr>
		<tr>
		    <td colspan="6" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end=getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
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
<body>

<form method="post" action="body.php?page=erewards_report1">

<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2">Total Trade and Cash paid to Members</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($f == "1") { echo "selected "; } ?>value="1">January</option>
				<option <? if ($f == "2") { echo "selected "; } ?>value="2">February</option>
				<option <? if ($f == "3") { echo "selected "; } ?>value="3">March</option>
				<option <? if ($f == "4") { echo "selected "; } ?>value="4">April</option>
				<option <? if ($f == "5") { echo "selected "; } ?>value="5">May</option>
				<option <? if ($f == "6") { echo "selected "; } ?>value="6">June</option>
				<option <? if ($f == "7") { echo "selected "; } ?>value="7">July</option>
				<option <? if ($f == "8") { echo "selected "; } ?>value="8">August</option>
				<option <? if ($f == "9") { echo "selected "; } ?>value="9">September</option>
				<option <? if ($f == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($f == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($f == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentyear">
				<option <? if ($g == "2000") { echo "selected "; } ?>value="2000">2000</option>
				<option <? if ($g == "2001") { echo "selected "; } ?>value="2001">2001</option>
				<option <? if ($g == "2002") { echo "selected "; } ?>value="2002">2002</option>
				<option <? if ($g == "2003") { echo "selected "; } ?>value="2003">2003</option>
				<option <? if ($g == "2004") { echo "selected "; } ?>value="2004">2004</option>
				<option <? if ($g == "2005") { echo "selected "; } ?>value="2005">2005</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="Get Report" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>