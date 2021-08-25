<html>
<head>
<title>Report - Accounts</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
</head>
<body>
<?
if(!checkmodule("LicReports")) {
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

?>

<form method="POST" action="body.php?page=reports_inter&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();
$tabarray = array("Transaction Fees","Admin Fees","Memberships","Inter Trans");

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  fees();

} elseif($_GET[tab] == "tab2") {

  trans();

} elseif($_GET[tab] == "tab3") {

  sales();

} elseif($_GET[tab] == "tab4") {

  inter();

}

?>

</form>

<?
function fees()  {

if($_REQUEST['countryID']) {
	$CID = $_REQUEST['countryID'];
} else {
	$CID = $_SESSION['Country']['countryID'];
}

if($_REQUEST['year'])  {

 if(checkmodule("Log")) {
  add_kpi("48", "0");
 }

$curdate = date("dS F Y");

//$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month']-1,16,$_REQUEST['year']));
//$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month'],15,$_REQUEST['year']));
//$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month']-1,16,$_REQUEST['year']));
//$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month'],15,$_REQUEST['year']));

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month'],1,$_REQUEST['year']));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month']+1,1-1,$_REQUEST['year']));
$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month'],1,$_REQUEST['year']));
$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month']+1,1-1,$_REQUEST['year']));

?>
<html>
<head>
<title>International Transactions Commission</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>International Transaction Commission</b></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("41") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $curdate ?></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("101") ?> - <?= get_word("102") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $fromdate ?> - <?= $todate ?></td>
  </tr>
</table>
</td>
</tr>
</table>
<br><br>

<?
$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, feesincurrid, transID FROM members,feespaid WHERE (feespaid.memid = members.memid) and feespaid.type in (9) AND CID = ".$CID." and (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2')");
?>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("41") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("3") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b>Fees Incurred ID</b></td>
    <td align="left" class="Heading2"><b>Trans ID</b></td>
    <td align="right" class="Heading2" width="10"><b>%</b></td>
    <td align="right" class="Heading2"><b><?= get_word("104") ?></b></td>
  </tr>
<?
$total=0;
$total2=0;
$foo= 0;
while($row = mysql_fetch_assoc($dbquery)) {

	$array = explode("-", $row[value1]);
	$month = $array[1];
	$day = $array[2];
	$year = $array[0];
	$row[value1] = date("dMY", mktime(0,0,1,$month,$day,$year));

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value1] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= substr($row[value2], 0, 20) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value3] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[feesincurrid] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[transID] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>" width="10"><?= $row[value9] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= $row[value4] ?></td>
  </tr>
<?
$total += $row[value4];
$foo++;

}

?>
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
    <td align="left" bgcolor="#FFFFFF"><b></b></td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= number_format($total,2) ?></b></td>
  </tr>
</table>
</td>
</tr>
</table>
<br>
</body>
</html>
<?
die;
}

if($_REQUEST['currentmonth']) {
 $m = $_REQUEST['currentmonth'];
} else {
 $m = date("m")-1;
}
?>
<html>
<head>
<title>Daily Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="dailytrans" method="POST" action="body.php?page=dailyfees">

<table width="600" border="0" cellspacing="0" cellpadding="1" align="left">
<tr>
<td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("1") ?>.</b></td>
  </tr>
  <?if($_SESSION['Country']['countryID'] == 1) {?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID']);
          ?>
      </td>
    </tr>
    <?}?>
  	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="month">
				<option <? if ($m == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($m == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($m == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($m == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($m == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($m == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($m == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($m == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($m == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($m == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($m == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('year',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
  <tr>
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>
<?}

function trans()  {

if($_REQUEST['countryID']) {
	$CID = $_REQUEST['countryID'];
} else {
	$CID = $_SESSION['Country']['countryID'];
}

if($_REQUEST['year'])  {

 if(checkmodule("Log")) {
  add_kpi("48", "0");
 }

$curdate = date("dS F Y");

//$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month']-1,16,$_REQUEST['year']));
//$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month'],15,$_REQUEST['year']));
//$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month']-1,16,$_REQUEST['year']));
//$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month'],15,$_REQUEST['year']));

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month'],1,$_REQUEST['year']));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month']+1,1-1,$_REQUEST['year']));
$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month'],1,$_REQUEST['year']));
$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month']+1,1-1,$_REQUEST['year']));

?>
<html>
<head>
<title>International Transactions Commission</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>International Admin Commission</b></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("41") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $curdate ?></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("101") ?> - <?= get_word("102") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $fromdate ?> - <?= $todate ?></td>
  </tr>
</table>
</td>
</tr>
</table>
<br><br>

<?
$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, feesincurrid, transID FROM members,feespaid WHERE (feespaid.memid = members.memid) and feespaid.type in (8) AND CID = ".$CID." AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2')");
?>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("41") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("3") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b>Fees Incurred ID</b></td>
    <td align="left" class="Heading2"><b>Trans ID</b></td>
    <td align="right" class="Heading2" width="10"><b>%</b></td>
    <td align="right" class="Heading2"><b><?= get_word("104") ?></b></td>
  </tr>
<?
$total=0;
$total2=0;
$foo= 0;
while($row = mysql_fetch_assoc($dbquery)) {

	$array = explode("-", $row[value1]);
	$month = $array[1];
	$day = $array[2];
	$year = $array[0];
	$row[value1] = date("dMY", mktime(0,0,1,$month,$day,$year));

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value1] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= substr($row[value2], 0, 20) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value3] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[feesincurrid] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[transID] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>" width="10"><?= $row[value9] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= $row[value4] ?></td>
  </tr>
<?
$total += $row[value4];
$foo++;

}

?>
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
    <td align="left" bgcolor="#FFFFFF"><b></b></td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= number_format($total,2) ?></b></td>
  </tr>
</table>
</td>
</tr>
</table>
<br>
</body>
</html>
<?
die;
}
if($_REQUEST['currentmonth']) {
 $m = $_REQUEST['currentmonth'];
} else {
 $m = date("m")-1;
}
?>
<html>
<head>
<title>Daily Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="dailytrans" method="POST" action="body.php?page=dailyfees">

<table width="600" border="0" cellspacing="0" cellpadding="1" align="left">
<tr>
<td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("1") ?>.</b></td>
  </tr>
  <?if($_SESSION['Country']['countryID'] == 1) {?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID']);
          ?>
      </td>
    </tr>
    <?}?>
  <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="month">
				<option <? if ($m == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($m == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($m == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($m == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($m == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($m == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($m == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($m == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($m == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($m == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($m == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('year',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
  <tr>
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>
<?}

function sales()  {

if($_REQUEST['countryID']) {
	$CID = $_REQUEST['countryID'];
} else {
	$CID = $_SESSION['Country']['countryID'];
}

if($_REQUEST['currentmonth']) {
 $m = $_REQUEST['currentmonth'];
} else {
 $m = date("m")-1;
}
?>

<form method="post" action="body.php?page=salescheck" name="paidsearch1">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("7") ?></td>
  </tr>
  <?if($_SESSION['Country']['countryID'] == 1) {?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID']);
          ?>
      </td>
    </tr>
    <?}?>
   <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($m == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($m == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($m == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($m == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($m == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($m == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($m == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($m == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($m == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($m == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($m == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>			</select>
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
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="Search" name="search">&nbsp;</td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="search" value="1">

</form>


<?

if($_REQUEST[search]) {

$time_start = getmicrotime();

if($_REQUEST['area']) {
 $area = " and FieldID = ".$_REQUEST['area'];
} else {
 $area = "";
}

$foo = 0;
$total = 0;
 //$mounth = $_REQUEST[currentmonth]-1;
 //$newdate = "'$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'";
 //$newdatefrom = "$_REQUEST[currentyear]-$mounth-16";
 //$newdateto = "$_REQUEST[currentyear]-$_REQUEST[currentmonth]-15";

 $mounth = $_REQUEST[currentmonth];
 $newdate = "'$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'";
 $newdatefrom = "$_REQUEST[currentyear]-$mounth-1";
 $newdateto = "$_REQUEST[currentyear]-$_REQUEST[currentmonth]-31";

 $query  = "select * from members, tbl_admin_users WHERE members.salesmanid=tbl_admin_users.FieldID AND members.CID = ".$CID." AND (datejoined between '".$newdatefrom."' and '".$newdateto."') and membershipfeepaid > 0 order by membershipfeepaid, memshipfeepaytype, Name";

$dbgetnoncheckedtrans = dbRead($query);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Membership Signups: <?= $counter ?></td>
 </tr>
</table>

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="9" align="center" class="Heading">International Membership Commission</td>
	</tr>
	<tr>
		<td width="30" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="180" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="80" class="Heading2" align="right"><b>D/Joined:</b></font></td>
		<td width="80" class="Heading2" align="right"><b><?= get_word("74") ?>:</b></font></td>
		<td width="100" class="Heading2" align="right"><b><?= get_word("34") ?>:</b></font></td>
		<td width="60" class="Heading2" align="right"><b><?= get_word("58") ?>:</b></font></td>
		<td width="70" class="Heading2" align="right"><b><?= get_word("60") ?>:</b></font></td>
		<td width="70" class="Heading2" align="right"><b><?= get_word("104") ?>:</b></font></td>
		<td width="20" class="Heading2" align="right"><b>Comm Paid:</b></font></td>
	</tr>
<?

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="8" bgcolor="#FFFFFF"><?= get_page_data("6") ?>.</font></td>
		</tr>
<?

} else {

$foo = 0;
$total = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$dis_date = date("d/m/Y", $row[date]);

	$Amount = 0;

	if($CID == 1) {
		if($row[membershipfeepaid] >= 110) {
			$commision = (110/100)*20;
		} else {
			$commision = 0;
		}
	} else {
	  	$commision = ((($row[membershipfeepaid]/100)*(100-$row['salespercent']))/100)*10;
	}

	$query4 = dbRead("select * from tbl_admin_payment_types where FieldID = '".$row[memshipfeepaytype]."'");
    $row4 = mysql_fetch_assoc($query4);

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="30"><?= $row[memid] ?></td>
			<td width="180"><a href="body.php?page=member_edit&tab=tab1&Client=<?= $row[memid] ?>" class="nav"><?= $row[companyname] ?><br></a></td>
			<td width="80" align="right"><?= $row[datejoined] ?></td>
			<td width="80" align="right"><?= $row[banked] ?></td>
			<td width="100" align="right"><?= $row[Name] ?></td>
			<td width="60" align="right"><?= $row4[Type] ?></td>
			<td width="70" align="right"><?= number_format($row[membershipfeepaid],2) ?></td>
			<td width="70" align="right"><?= number_format($commision,2) ?></td>
			<td width="20" align="right"><?= $row[paid] ?></td>
		</tr>
<?
$total += $commision;
$totalamount += $amount;
$foo++;

}

}

if(!$data) {
?>
		<tr bgcolor="#FFFFFF">
			<td width="30">&nbsp;</td>
			<td width="180">&nbsp;</td>
			<td width="80"></td>
			<td width="80"></td>
			<td width="100">&nbsp;</td>
			<td width="60">&nbsp;</td>
			<td width="70"></td>
			<td width="70" align="right"><?= number_format($total,2) ?></td>
			<td width="20"></td>
		</tr>
<?
}

?>
		<tr>
		    <td colspan="9" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

<?


}
}

function inter()  {

if($_REQUEST['countryID']) {
	$CID = $_REQUEST['countryID'];
} else {
	$CID = $_SESSION['Country']['countryID'];
}

if($_REQUEST['year'])  {

 if(checkmodule("Log")) {
  add_kpi("48", "0");
 }

$curdate = date("dS F Y");

//$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month']-1,16,$_REQUEST['year']));
//$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month'],15,$_REQUEST['year']));
//$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month']-1,16,$_REQUEST['year']));
//$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month'],15,$_REQUEST['year']));

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month'],1,$_REQUEST['year']));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month']+1,1-1,$_REQUEST['year']));
$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month'],1,$_REQUEST['year']));
$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month']+1,1-1,$_REQUEST['year']));

?>
<html>
<head>
<title>International Transactions Commission</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>International Admin Commission</b></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("41") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $curdate ?></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("101") ?> - <?= get_word("102") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $fromdate ?> - <?= $todate ?></td>
  </tr>
</table>
</td>
</tr>
</table>
<br><br>

<?
$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, feesincurrid, transID FROM members,feespaid WHERE (feespaid.memid = members.memid) and feespaid.type in (7) AND CID = ".$CID." AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2')");
?>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("41") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("3") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b>Trans ID</b></td>
    <td align="left" class="Heading2"><b>Amount</b></td>
    <td align="right" class="Heading2" width="10"><b>%</b></td>
    <td align="right" class="Heading2"><b><?= get_word("104") ?></b></td>
  </tr>
<?
$total=0;
$total2=0;
$foo= 0;
while($row = mysql_fetch_assoc($dbquery)) {

	$array = explode("-", $row[value1]);
	$month = $array[1];
	$day = $array[2];
	$year = $array[0];
	$row[value1] = date("dMY", mktime(0,0,1,$month,$day,$year));
	//$com = $row[value4]-(($row[value4]/(100+$row[value9]))*$row[value9]);
	$com = $row[value4];

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value1] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= substr($row[value2], 0, 20) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value3] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[transID] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value4] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>" width="10"><?= $row[value9] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($com, 2) ?></td>
  </tr>
<?
$total += $com;
$foo++;

}

?>
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
    <td align="left" bgcolor="#FFFFFF"><b></b></td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= number_format($total,2) ?></b></td>
  </tr>
</table>
</td>
</tr>
</table>
<br>
</body>
</html>
<?
die;
}
if($_REQUEST['currentmonth']) {
 $m = $_REQUEST['currentmonth'];
} else {
 $m = date("m")-1;
}
?>
<html>
<head>
<title>Daily Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="dailytrans" method="POST" action="body.php?page=dailyfees">

<table width="600" border="0" cellspacing="0" cellpadding="1" align="left">
<tr>
<td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("1") ?>.</b></td>
  </tr>
  <?if($_SESSION['Country']['countryID'] == 1) {?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID']);
          ?>
      </td>
    </tr>
    <?}?>
  <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="month">
				<option <? if ($m == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($m == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($m == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($m == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($m == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($m == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($m == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($m == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($m == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($m == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($m == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('year',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
  <tr>
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>
<?}
?>

