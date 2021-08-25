
<form method="POST" action="body.php?page=solutions_statement&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<input type="hidden" name="DisplayStatement" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] - <?= get_page_data("8") ?></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Customer No:</b></td>
		<td width="450" bgcolor="#FFFFFF"><input type="text" name="fieldid" size="20" maxlength="10" value="<?= $_REQUEST['fieldid']?>"></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>E Banc AccNo:</b></td>
		<td width="450" bgcolor="#FFFFFF"><input type="text" name="accno" size="20" maxlength="10" value="<?= $_REQUEST['accno']?>"></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

            $query = dbRead("select tbl_admin_months.* from tbl_admin_months");
            form_select('currentmonth',$query,'Month','FieldID',date("m"));

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("40") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_month_array();
            form_select('numbermonths',$query,'','','','None');

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_year_array();
            form_select('currentyear',$query,'','',date("Y"));

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="Search" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

<?
if($_REQUEST['DisplayStatement'] || $_REQUEST['fieldid'] || $_REQUEST['accno']) {
?>

<table width="610" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" width="50" valign="bottom"><b><?= get_word("41") ?></b>&nbsp;</td>
    <td class="Heading2" width="150" valign="bottom"><b><?= get_word("80") ?></b>&nbsp;</td>
    <td class="Heading2" width="85" valign="bottom"><b>Receipt No</b>&nbsp;</td>
    <td class="Heading2" align="right" width="30" valign="bottom"><b>Type&nbsp;</b></td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b><?= get_word("43") ?></b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b><?= get_word("44") ?></b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b>Balance</b>&nbsp;</td>
  </tr>
<?

// Get the transactions out.
if($_REQUEST['accno']) {
 $yy =" and registered_accounts.Acc_No = ". $_REQUEST['accno']."";
} else {
 $yy =" and registered_accounts.FieldID = ". $_REQUEST['fieldid']."";
}

$dbgettrans = dbRead("select * from  registered_accounts, transactions where (registered_accounts.FieldID = transactions.regAccID)$yy order by transactions.dis_date, transactions.id ASC",empire_solutions);

$foo = 0;

while($transrow = mysql_fetch_assoc($dbgettrans)) {

$dis_date = date("d/m/y", strtotime($transrow['dis_date']));
$dollarfees_total += $transrow[dollarfees];

if($transrow[buy] == "0") {
 $tradebal += $transrow[sell];
 if(!$transrow[checked])  {
  $tradebalu += $transrow[sell];
 }
} else {
 $tradebal -= $transrow[buy];
 if(!$transrow[checked])  {
  $tradebalu -= $transrow[buy];
 }
}

if($transrow['type_id'] == 1) {
 $type = "Cash";
} else {
 $type = "Trade";
}

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td valign="top" bgcolor="<?= $bgcolor ?>" height="19"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $transrow[id] ?>');" class="nav"><?= $dis_date ?></a></td>
    <td valign="top"  bgcolor="<?= $bgcolor ?>" height="19"><?= $transrow[details] ?></td>
    <td valign="top"  bgcolor="<?= $bgcolor ?>" height="19"><?= $transrow['receipt'] ?></td>
    <td valign="top"  align="right" bgcolor="<?= $bgcolor ?>" height="19"><?= $type ?>&nbsp;</td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">
    &nbsp;<?= number_format($transrow[buy],2) ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">
    &nbsp;<?= number_format($transrow[sell],2) ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">
    &nbsp;<?= number_format($tradebal,2) ?></td>
  </tr>

<?
$statement_fees += $transrow[dollarfees];
$total_buy += $transrow[buy];
$total_sell += $transrow[sell];
$foo++;
}

if($tradebalu < 0) {
 $tradebalu = 0;
}
?>

  <tr>
    <td height="19" valign="top">&nbsp;</td>
    <td height="19" valign="top">&nbsp;</td>
    <td align="right" valign="top" height="19"><b><?= get_word("52") ?>:</b></td>
    <td width="35" align="right" valign="top" height="19">&nbsp;</td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($total_buy,2) ?></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($total_sell,2) ?></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($tradebal,2) ?></td>
  </tr>
</table>
<table width="610" border="0" cellspacing="1" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111" height="96">
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("218") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19"><?= $_SESSION['Country']['currency'] ?><?= number_format($tradebal,2) ?></td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("76") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19"><?= $_SESSION['Country']['currency'] ?><?= number_format($tradebalu,2) ?></td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19">&nbsp;</td>
    <td width="252" align="right" height="19">
    <button style="width: 120; height: 25; font-family: Verdana; font-weight: bold; font-size: 10px" type="submit">
    <?= get_page_data("22") ?>
    </button>
    </td>
  </tr>
</table>
<?
}
?>
