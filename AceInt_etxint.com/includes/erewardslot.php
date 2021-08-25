<?

$pm = "40";

#get memberdetails
$query=mysql_db_query($db, "select members.*, status.* from members, status where (members.status = status.FieldID) and memid='$_POST[memid]'", $linkid);
$MemberDetails=mysql_fetch_assoc($query);

if(!checkmodule("ViewStatement")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">You are not allowed to use this function.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

if(!checkmodule("Staff")) {

 if($MemberDetails[Type] == "Staff") {
 
?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">This is a Staff or Contract Account. You are not allowed to view This Account</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}
}

require("./includes/checkarea.php");

if(!checkmodule("Contractor")) {

 if($MemberDetails[Type] == "Contractor") {
 
?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">This is a Staff or Contract Account. You are not allowed to view This Account</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}
}

 // check what area the member is in for admin.

 require("./includes/checkarea.php");

// get current month dates in unix timestamp format.

 $referer_query = dbRead("select referals from erewards_agents where agent='$MemberDetails[memid]'");
 $referer_row = mysql_fetch_assoc($referer_query);

 $startdate = date("Y-m-d", mktime(0,0,0,$currentmonth-$pm,1,$currentyear));
 $enddate = date("Y-m-d", mktime(0,0,0,$currentmonth+1,1-1,$currentyear));

?>

<html>

<head>
<script>
function notes(URL) {
  var exitwin="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=400";
  remotecontrol=window.open(URL, "notes", exitwin);
  remotecontrol.focus();
}
</script>
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>

</head>

<body>

<p><b><a href="javascript:print()" class="nav">Print</a></b> </p>
<table width="639" border="0" cellspacing="0" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td width="150" align="right">&nbsp;</td>
    <td width="150" align="left">&nbsp;</td>
    <td align="right"><?= $referer_row[referals] ?> Referals</td>
  </tr>
  <tr>
    <td width="150" align="right">Account No:</td>
    <td width="150" align="left"><b><?= $MemberDetails[memid] ?></b></td>
    <td align="right"><?= $MemberDetails[companyname] ?></td>
  </tr>
  <tr>
    <td width="150" align="right">Contact Name:</td>
    <td width="150" align="left"><b><?= $MemberDetails[contactname] ?></b></td>
    <td align="right">&nbsp;<?=$MemberDetails[streetno] ?> <?= $MemberDetails[streetname] ?></td>
  </tr>
  <tr>
    <td width="150" align="right">Month/Year:</td>
    <td width="150" align="left"><b><?= $currentmonth ?>/<?= $currentyear ?></b> - Months Prior: <b><?= $pm ?></b></td>
    <td align="right"><?= $MemberDetails[city] ?>, <?= $MemberDetails[state] ?>, <?= $MemberDetails[postcode] ?></td>
  </tr>
</table>
<table width="639" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" valign="bottom"><b>Date</b>&nbsp;</td>
    <td class="Heading2" valign="bottom"><b>Member</b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b>Cash</b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b>Trade</b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b>Total</b>&nbsp;</td>
    <td class="Heading2" align="right" width="45" valign="bottom"><b>Percent</b>&nbsp;</td>
    <td class="Heading2" align="right" width="50" valign="bottom"><b>Type</b>&nbsp;</td>
  </tr>

<?

// get the rewards out
$dbgettrans = dbRead("select * from erewards where agent='$MemberDetails[memid]' order by date");

$foo=0;

while($row = mysql_fetch_assoc($dbgettrans)) {

 // get other member details out.

 $dbgetotherid = mysql_db_query($db, "select * from members where memid='$row[memid]'", $linkid);
 $row2 = mysql_fetch_assoc($dbgetotherid);

 // set the type;
 
 if($row[type] == 1) {
  $dis_type = "SignUp";
 } else {
  $dis_type = "Fees";
 }

 // set background colours.

 $cfgbgcolorone = "#CCCCCC";
 $cfgbgcolortwo = "#EEEEEE";
 $bgcolor = $cfgbgcolorone;
 $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

 // set the date up.
 
 $date_array = explode("-", $row[date]);
 $dis_date = date("d/m/y", mktime(1,1,1,$date_array[1],$date_array[2],$date_array[0]));

 ?>

  <tr>
    <td valign="top" bgcolor="<?= $bgcolor ?>" height="19"><?= $dis_date ?>&nbsp;</td>
    <td valign="top" bgcolor="<?= $bgcolor ?>" height="19"><?= $row2[companyname] ?>&nbsp;</td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[amount_cash],2) ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[amount_trade],2) ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format(($row[amount_cash]+$row[amount_trade]),2) ?></td>
    <td width="45" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[percent],2) ?></td>
    <td width="50" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $dis_type ?></td>
  </tr>
  
 <?

$total_cash += $row[amount_cash];
$total_trade += $row[amount_trade];

}

 ?>

  <tr>
    <td height="19" valign="top">&nbsp;</td>
    <td align="right" valign="top" height="19"><b>Totals:</b></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($total_cash,2) ?></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($total_trade,2) ?></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format(($total_cash+$total_trade),2) ?></td>
    <td width="45" align="right" valign="top" height="19">&nbsp;</td>
    <td width="50" align="right" valign="top" height="19">&nbsp;</td>
  </tr>
 </table>
<table width="639" border="0" cellspacing="1" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td>&nbsp;</td>
    <td align="right"><b>Total Rewards:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC"><?= $_SESSION['Country']['currency'] ?><?= number_format(($total_cash+$total_trade),2) ?></td>
  </tr>
</table>
<br><br>
<font face="Verdana" size="1" color="#000000"><b>Table Fees Paid</b></font>
<table width="639" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" valign="bottom"><b>Date</b>&nbsp;</td>
    <td class="Heading2" valign="bottom" align="right"><b>Amount Paid</b>&nbsp;</td>
    <td class="Heading2" align="right" valign="bottom"><b>Fees Owed</b>&nbsp;</td>
    <td class="Heading2" align="right" valign="bottom"><b>Percent</b>&nbsp;</td>
  </tr>

<?

$query = dbRead("select * from feespaid where memid = '$memid' order by paymentdate");
if(mysql_num_rows($query) == 0) {

?>
  <tr>
    <td bgcolor="#FFFFFF" colspan="4" align="center"><br>Nothing to display<br><br></td>
  </tr>
<?

} else {

while($row = mysql_fetch_assoc($query)) {

?>
  <tr>
    <td bgcolor="#FFFFFF" valign="bottom"><?= $row[paymentdate] ?>&nbsp;</td>
    <td bgcolor="#FFFFFF" valign="bottom" align="right">$<?= number_format($row[amountpaid],2) ?>&nbsp;</td>
    <td bgcolor="#FFFFFF" align="right" valign="bottom"><?= $row[feesowed] ?>&nbsp;</td>
    <td bgcolor="#FFFFFF" align="right" valign="bottom"><?= $row[percent] ?>&nbsp;</td>
  </tr>
<?

}

}
?>
</table>
<br><br>
<font face="Verdana" size="1" color="#000000"><b>erewards_bank type 2 (cash fees paid)</b></font>
<table width="639" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" valign="bottom"><b>Date</b>&nbsp;</td>
    <td class="Heading2" valign="bottom" align="right"><b>Amount Paid</b>&nbsp;</td>
    <td class="Heading2" align="right" valign="bottom"><b>Type</b>&nbsp;</td>
  </tr>

<?

$query = dbRead("select * from erewards_bank where memid = '$memid' and type = '2' order by date");
if(mysql_num_rows($query) == 0) {

?>
  <tr>
    <td bgcolor="#FFFFFF" colspan="4" align="center"><br>Nothing to display<br><br></td>
  </tr>
<?

} else {

while($row = mysql_fetch_assoc($query)) {

?>
  <tr>
    <td bgcolor="#FFFFFF" valign="bottom"><?= $row[date] ?>&nbsp;</td>
    <td bgcolor="#FFFFFF" valign="bottom" align="right">$<?= number_format($row[amount_cash],2) ?>&nbsp;</td>
    <td bgcolor="#FFFFFF" align="right" valign="bottom"><?= $row[type] ?>&nbsp;</td>
  </tr>
<?

}

}
?>
</table>

<?

last_trans($memid);

?>

</body>

</html>
<?

function last_trans($memid) {

 global $MemberDetails;
 
 // top table stuff.
 
 ?>
 <br><br>
 <font face="Verdana" size="1" color="#000000"><b>Cash fees payments for <?= $MemberDetails[companyname] ?></b></font>
 <table width="639" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" valign="bottom" width="30" nowrap><b>Date</b>&nbsp;</td>
    <td class="Heading2" valign="bottom" width="384"><b>Account</b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b>Buy</b>&nbsp;</td>
    <td class="Heading2" align="right" width="90" valign="bottom"><b>Sell</b>&nbsp;</td>
    <td class="Heading2" align="right" width="45" valign="bottom"><b>Cash Fees</b>&nbsp;</td>
  </tr>
 <?
 
 // get the transactions out.

 $dbgettrans = dbRead("select * from transactions where memid='$memid' and dollarfees < '0' order by id desc");

 $foo=0;
 if(mysql_num_rows($dbgettrans) == 0) {
 
  ?>

  <tr>
    <td bgcolor="#FFFFFF" nowrap colspan="5" align="center"><br>Nothing To Display<br><br></td>
  </tr>
  
  <?
 
 } else {
 while($row = mysql_fetch_assoc($dbgettrans)) {

  $dis_date=date("d/m/y", $row[date]);
  $dbgetotherid = dbRead("select companyname from members where memid='$row[to_memid]'");
  $otherrow = mysql_fetch_assoc($dbgetotherid);

  $cfgbgcolorone="#CCCCCC";
  $cfgbgcolortwo="#EEEEEE";
  $bgcolor=$cfgbgcolorone;
  $foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

  ?>

  <tr>
    <td valign="top" bgcolor="<?= $bgcolor ?>" height="19" width="30" nowrap><?= $dis_date ?>&nbsp;</td>
    <td valign="top" bgcolor="<?= $bgcolor ?>" height="19" width="384"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $id ?>');" class="nav"><?= $otherrow[companyname] ?></a><? if($row[details]) { print'<br><font style="font-size: 7pt">'.$row[details].'</font>'; } ?>&nbsp;</td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[buy],2) ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[sell],2) ?></td>
    <td width="45" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[dollarfees],2) ?></td>
  </tr>
  
  <?
  
  $foo++;
 
 }
 }
 ?>
 </table>
 <?

}


?>