<html>
<head>
<title>Report - Accounts</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
</head>
<body>
<?
if(!checkmodule("LynReports")) {
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

<form method="POST" action="body.php?page=reports_lyn&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();
$tabarray = array(get_page_data("1"),get_page_data("9"),get_page_data("7"),get_page_data("8"),'Direct Debits','Unallocated Funds','Licensee Fees','DD Check');

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  feespaid();

} elseif($_GET[tab] == "tab2") {

  mem();

} elseif($_GET[tab] == "tab3") {

  fees();

} elseif($_GET[tab] == "tab4") {

  refees();

} elseif($_GET[tab] == "tab5") {

  dd();

} elseif($_GET[tab] == "tab6") {

  unallocated();

} elseif($_GET[tab] == "tab8") {

  ddcheck();

}
?>

</form>

<?
function feespaid()  {

if($_REQUEST[year1])  {

 if(checkmodule("Log")) {
  add_kpi("46", "0");
 }

$startdate = mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']);
$enddate = mktime(0,0,0,$_REQUEST['currentmonth']+1,1,$_REQUEST['currentyear']);
$dis_month = date("F", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

$curdate = date("dS F Y");

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST[month1],$_REQUEST[day1],$_REQUEST[year1]));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST[month2],$_REQUEST[day2],$_REQUEST[year2]));


?>

<body bgcolor='#FFFFFF'>
<p><font face='Verdana, Arial, Helvetica, sans-serif'><b><font size='2'><?= get_page_data("4") ?> <?= $fromdate ?> - <?= $todate ?>.</font></b></font></p>
<table width='630' border='1' bordercolor='#CCCCCC' cellspacing='0' cellpadding='3'>
	<form method='post' action='payfees.php'>
  <tr bgcolor='#CCCCCC'>
    <td width='40'><font size='1' face='Verdana'><b><?= get_word("1") ?>:</b></font></td>
    <td width='200'><font size='1' face='Verdana'><b><?= get_word("3") ?>:</b></font></td>
    <td width='45'><font size='1' face='Verdana'><b><?= get_word("56") ?>:</b></font></td>
    <td width='45'><font size='1' face='Verdana'><b><?= get_word("86") ?>:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b><?= get_word("103") ?>:</b></font></td>
  </tr>
<?

//$dbtq = dbRead("select transactions.memid as memid, who, type, dollarfees as feespaid from transactions, members where (transactions.memid = members.memid) and dollarfees < 0 and members.CID = '".$_SESSION['User']['CID']."' and (dis_date BETWEEN {d '$year1-$month1-$day1'} AND {d '$year2-$month2-$day2'} ) order by type, companyname");
//$dbtq = dbRead("select transactions.memid as memid, userid, type, dollarfees as feespaid, name from transactions, members, tbl_admin_trans_type where (transactions.memid = members.memid) and (transactions.type = tbl_admin_trans_type.FieldID) and dollarfees < 0  and type not in (10) and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and members.CID = '".$_SESSION['User']['CID']."' and (dis_date BETWEEN {d '$_REQUEST[year1]-$_REQUEST[month1]-$_REQUEST[day1]'} AND {d '$_REQUEST[year2]-$_REQUEST[month2]-$_REQUEST[day2]'} ) order by type, companyname");
//$dbtq = dbRead("select transactions.memid as memid, userid, type, dollarfees as feespaid, name from transactions, members, tbl_admin_trans_type where (transactions.memid = members.memid) and (transactions.type = tbl_admin_trans_type.FieldID) and ((dollarfees < 0  and type not in (10)) or (dollarfees > 0  and type = 10)) and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and members.CID = '".$_SESSION['User']['CID']."' and to_memid != '".$_SESSION['Country']['rereserve']."' and (dis_date BETWEEN {d '$_REQUEST[year1]-$_REQUEST[month1]-$_REQUEST[day1]'} AND {d '$_REQUEST[year2]-$_REQUEST[month2]-$_REQUEST[day2]'} ) order by type, companyname");
$dbtq = dbRead("select transactions.memid as memid, userid, type, dollarfees as feespaid, name, dis_date from transactions, members, tbl_admin_trans_type where (transactions.memid = members.memid) and (transactions.type = tbl_admin_trans_type.FieldID) and ((dollarfees < 0  and type not in (10)) or (dollarfees > 0  and type = 10)) and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and members.CID = '".$_SESSION['User']['CID']."' and to_memid != '".$_SESSION['Country']['rereserve']."' and (dis_date BETWEEN {d '$_REQUEST[year1]-$_REQUEST[month1]-$_REQUEST[day1]'} AND {d '$_REQUEST[year2]-$_REQUEST[month2]-$_REQUEST[day2]'} ) order by type, dis_date");

	while($row = mysql_fetch_assoc($dbtq)) {

		$dbgetcomname = dbRead("select companyname from members where memid=".$row['memid']."");
		$row2 = mysql_fetch_assoc($dbgetcomname);

		$feespaid = number_format(abs($row['feespaid']),2);

		$abc = dbRead("select * from tbl_admin_users where FieldID = '$row[userid]'");
		$row3 = mysql_fetch_assoc($abc);

?>
 <tr>
   <input type='hidden' name='memberacc[]' value='<?= $row['memid'] ?>'>
   <td width='40'><font size='1' face='Verdana'><?= $row['memid'] ?></font>&nbsp;</td>
   <td width='200'><font size='1' face='Verdana'><?= $row['dis_date'] ?> <?= get_all_added_characters($row2['companyname']) ?></font>&nbsp;</td>
   <td width='45'><font size='1' face='Verdana'><?= get_all_added_characters($row3['Name']) ?></font>&nbsp;</td>
   <td width='45'><font size='1' face='Verdana'><?= get_all_added_characters($row['name']) ?></font>&nbsp;</td>
   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= $feespaid ?></font>&nbsp;</td>
 </tr>
<?

$total += abs($row['feespaid']);
$TOTAL[$row['type']] += abs($row['feespaid']);

}


	if(@mysql_num_rows($dbtq) > 0) {

	 foreach($TOTAL as $key => $value) {

	 $Type = get_fee_type($key);

	 ?>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="4" align="right"><b><?= $Type ?>:</b></td>
		<td align="left"><b><?= $_SESSION['Country']['currency'] ?><?= number_format($value,2) ?></b>&nbsp;</td>
	 </tr>
	 <?

	 }

	}

?>
  <tr>
    <td width='40'><font size='1' face='Verdana'><b></b></font></td>
    <td width='200' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td width='45' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td width='45' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($total,2) ?></b></font>&nbsp;</td>
  </tr>
  </form>
</table>
</body>
<?
die;
}
?>

<form name="dailytrans" method="POST" action="body.php?page=cashfeespaid">

<table width="600" border="0" cellspacing="0" cellpadding="1" align="left">
<tr>
<td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("1") ?>.</b></td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("101") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day1">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
      </select>
      <select name="month1">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year1',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("102") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day2">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 28) { echo "selected "; } ?>value="28">28</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 29) { echo "selected "; } ?>value="29">29</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 30) { echo "selected "; } ?>value="30">30</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 31) { echo "selected "; } ?>value="31">31</option>
      </select>
      <select name="month2">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year2',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr>
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" style="size: 8pt"></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
</body>
<?
}

function mem()  {

 global $Amount, $referlist;

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST[month1],$_REQUEST[day1],$_REQUEST[year1]));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST[month2],$_REQUEST[day2],$_REQUEST[year2]));

if($_REQUEST[search]) {

$time_start = getmicrotime();


 //$query  = "select members.*, area.*, salespeople.*, tbl_admin_payment_types.* from members, area left outer join salespeople on (salespeople.salesmanid = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.area=area.FieldID and members.memid = ".$_REQUEST['data']." and members.CID=".$_SESSION['User']['CID']."";
 //$query  = "select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.* from members, area left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.area=area.FieldID and members.memid = ".$_REQUEST['data']." and members.CID=".$_SESSION['User']['CID']."";
 $queryOLD  = "select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.* from members, area left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.licensee=area.FieldID and members.datejoined BETWEEN '".$_REQUEST[year1]."-".$_REQUEST[month1]."-".$_REQUEST[day1]."' AND '".$_REQUEST[year2]."-".$_REQUEST[month2]."-".$_REQUEST[day2]."' and members.CID=".$_SESSION['User']['CID']." and members.status != 1 order by datejoined, memid";
 $query  = "
	 select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.*

	 from members members

	 left
	 	join
	 		area
	 		on members.licensee=area.FieldID

	 left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid)
	 left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID)

	 WHERE

	 members.datejoined BETWEEN '".$_REQUEST[year1]."-".$_REQUEST[month1]."-".$_REQUEST[day1]."' AND '".$_REQUEST[year2]."-".$_REQUEST[month2]."-".$_REQUEST[day2]."' and
	 members.CID=".$_SESSION['User']['CID']." and
	 members.status != 1

	 order by datejoined, memid

 ";

$dbgetnoncheckedtrans = dbRead($query);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><b><?= get_page_data("9") ?> <?= $fromdate ?> - <?= $todate ?></b></td>
 </tr>
 <tr>
  <td bgcolor="#FFFFFF" align="center">No. Members: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="5" align="center" class="Heading"><?= get_page_data("9") ?>.</td>
	</tr>
	<tr>
		<td width="45" class="Heading2"><b><?= get_word("1") ?>:<br><?= get_word("90") ?>:</b></font></td>
		<td width="220" class="Heading2"><b><?= get_word("3") ?>:<br><?= get_page_data("5") ?>:</b></font></td>
		<td width="175" class="Heading2"><b><?= get_word("25") ?>:<br><?= get_word("34") ?>:</b></font></td>
		<td width="68" class="Heading2"><b><?= get_word("69") ?>:<br><?= get_word("74") ?>:</b></font></td>
		<td width="62" class="Heading2"><b><?= get_word("58") ?>:<br><?= get_word("61") ?>:</b></font></td>
	</tr>
<?

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {
?>
	<tr>
		<td colspan="6" bgcolor="#FFFFFF"><?= get_page_data("6") ?>.</font></td>
	</tr>
<?

} else {

$foo = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$dis_date = date("d/m/Y", $row[date]);

	if($row[erewards] > 0) {
	  $rewards = "YES";
	} else {
	  $rewards = "NO";
	}

	$Amount = 0;
	$referlist = "";

	if($row[referedby]) {
      //rewardscheck($row[referedby]);
    }

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="45"><?= $row[memid] ?><br><?= $rewards ?></td>
			<td width="220"><a href="body.php?page=viewmember&memid=<?= $row[memid] ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?><br><?= $referlist ?> <?= get_word("52") ?>:<?= number_format($Amount,2) ?></a></td>
			<td width="175"><?= $row[place] ?><br><?= get_all_added_characters($row[Name]) ?></td>
			<td width="68"><?= $row[datejoined] ?><br><?= get_all_added_characters($row[banked]) ?></td>
			<td width="62"><?= $row[Type] ?><br><?= number_format($row[membershipfeepaid],2) ?></td>
		</tr>
<?

$totalamount += $amount;
$foo++;

}

}

if(!$data) {
?>
		<tr bgcolor="#FFFFFF">
			<td width="45">&nbsp;</td>
			<td width="220">&nbsp;</td>
			<td width="175" align="right">&nbsp;</td>
			<td width="68"></td>
			<td width="62">&nbsp;</td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="5" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
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
</form>

<?

} else {
?>
<form name="dailytrans" method="POST" action="body.php?page=cashfeespaid">

<table width="610" border="0" cellspacing="0" cellpadding="1" align="left">
 <tr>
  <td class="border">
  <table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
   <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("9") ?>.</b></td>
   </tr>
   <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("101") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day1">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
      </select>
      <select name="month1">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year1',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("102") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day2">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 28) { echo "selected "; } ?>value="28">28</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 29) { echo "selected "; } ?>value="29">29</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 30) { echo "selected "; } ?>value="30">30</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 31) { echo "selected "; } ?>value="31">31</option>
      </select>
      <select name="month2">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year2',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr><input type="hidden" name="search" value="1">
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" style="size: 8pt"></td>
  </tr>
</table>
</td>
</tr>
</table>
</table>
<?


}
}



function fees()  {

$f = date("n")-1;
$g = date("Y");

if($_REQUEST['currentyear'])  {

 if(checkmodule("Log")) {
  add_kpi("47", "0");
 }

$db_date = date("Y-m-d", mktime(0,0,0,$_REQUEST['currentmonth']+1,1-1,$_REQUEST['currentyear']));
$dis_month = date("F", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

?>

<body bgcolor='#FFFFFF'>
<p><font face='Verdana, Arial, Helvetica, sans-serif'><b><font size='2'><?= get_page_data("5") ?> <?= $dis_month ?> <?= $_REQUEST['currentyear'] ?>.</font></b></font></p>
<table width='600' border='1' bordercolor='#CCCCCC' cellspacing='0' cellpadding='3'>
	<form method='post' action='payfees.php'>
  <tr bgcolor='#CCCCCC'>
    <td width='60'><font size='1' face='Verdana'><b>Invoice No:</b></font></td>
    <td width='60'><font size='1' face='Verdana'><b><?= get_word("1") ?>:</b></font></td>
    <td width='250'><font size='1' face='Verdana'><b><?= get_word("3") ?>:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b><?= get_word("42") ?>:</b></font></td>
  </tr>
<?
if($_SESSION['User']['CID'] == 15) {
	$dbtq = dbRead("select invoice.*, members.*, invoice_es.* from members, invoice, invoice_es where (invoice.memid = members.memid) and (invoice.FieldID = invoice_es.inv_link) and invoice.date = '$db_date' and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'");
} else {
	$dbtq = dbRead("select invoice.*, members.* from members, invoice where (invoice.memid = members.memid) and invoice.date = '$db_date' and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'");
}
#$dbtq = dbRead("select invoice.*, members.* from members, invoice where (invoice.memid = members.memid) and invoice.date = '$db_date' and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'");
//$dbtq = dbRead("select invoice.*, members.* from members, invoice where (invoice.memid = members.memid) and invoice.date = '$db_date' and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'");

	while($row = mysql_fetch_assoc($dbtq)) {

	$feespaid = number_format(abs($row['feespaid']),2);

	if($_SESSION['User']['CID'] == 15) {
		$no = $row['inv_no'];
	} else {
		$no = $row['FieldID'];
	}

?>
 <tr>
   <td width='60'><font size='1' face='Verdana'><?= $no ?></font>&nbsp;</td>
   <td width='60'><font size='1' face='Verdana'><?= $row['memid'] ?></font>&nbsp;</td>
   <td width='250'><font size='1' face='Verdana'><?= get_all_added_characters($row['companyname']) ?></font>&nbsp;</td>
   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($row['currentfees'],2) ?></font>&nbsp;</td>
 </tr>
<?

$total += $row['currentfees'];

}

?>
  <tr>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='250' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($total,2) ?></b></font>&nbsp;</td>
  </tr>
  </form>
</table>
</body>

<?
die;
}
?>

<form method="post" action="body.php?page=cashfeesinvoiced">

<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?>.</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
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
        <input type="submit" value="<?= get_word("83") ?>" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>

<?}

function refees()  {

if($_REQUEST[year1])  {

 if(checkmodule("Log")) {
  add_kpi("48", "0");
 }

$curdate = date("dS F Y");

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST[month1],$_REQUEST[day1],$_REQUEST[year1]));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST[month2],$_REQUEST[day2],$_REQUEST[year2]));

?>

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("3") ?></b></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("41") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $curdate ?></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("41") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $fromdate ?> - <?= $todate ?></td>
  </tr>
</table>
</td>
</tr>
</table>
<br><br>

<?

$dbquery = dbRead("SELECT feespaid.paymentdate as value1,members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.percent as value5,feespaid.percent as value9, feespaid.type as value10, place as place FROM members,feespaid, area WHERE ((feespaid.memid = members.memid) and (feespaid.area = area.FieldID) AND type in (2,3) and members.CID = '".$_SESSION['User']['CID']."' and (feespaid.paymentdate BETWEEN {d '$_REQUEST[year1]-$_REQUEST[month1]-$_REQUEST[day1]'} AND {d '$_REQUEST[year2]-$_REQUEST[month2]-$_REQUEST[day2]'} ) ) order by feespaid.id");

?>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("41") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("3") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("25") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_word("103") ?></b></td>
    <td align="right" class="Heading2"><b>%:</b></td>
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

	$dis_value4 = number_format($row[value4],2);
	$value7 = ($row[value4]/100)*$row[value5];
	$dis_value7 = number_format($value7,2);

?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value1] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= substr($row[value2], 0, 20) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[value3] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row['place'] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= $dis_value4 ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= $row[value5] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><b><?= $dis_value7 ?></b></td>
  </tr>
<?

$total += $value7;
if($row[value10] == 2)  {
  $total2 += $row[value4];
}
$foo++;

}

$dis_total = number_format($total,2);

?>
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= number_format($total2,2) ?></b></td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= $dis_total ?></b></td>
  </tr>
</table>
</td>
</tr>
</table>
<br><br>
<?
//} ?>

</body>
<?
die;
}
?>

<form name="dailytrans" method="POST" action="body.php?page=invoicedreal">

<table width="600" border="0" cellspacing="0" cellpadding="1" align="left">
<tr>
<td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("3") ?> </b></td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("101") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day1">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
      </select>
      <select name="month1">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year1',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("102") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day2">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 28) { echo "selected "; } ?>value="28">28</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 29) { echo "selected "; } ?>value="29">29</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 30) { echo "selected "; } ?>value="30">30</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 31) { echo "selected "; } ?>value="31">31</option>
      </select>
      <select name="month2">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year2',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr>
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" name="Submit" value="<?= get_word("83") ?>"></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
</body>

<?}

function dd()  {

$f = date("n")-1;
$g = date("Y");

if($_REQUEST['currentyear'])  {

 if(checkmodule("Log")) {
  //add_kpi("47", "0");
 }

$db_date = date("Y-m-d", mktime(0,0,0,$_REQUEST['currentmonth']+1,1-1,$_REQUEST['currentyear']));
$dis_month = date("F", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

 $date1 = date("Y-m-d", mktime(0,0,0,$_REQUEST['currentmonth']+1,1-1,$_REQUEST['currentyear']));
 $date2 = date("Y-m-d", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));
 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<body bgcolor='#FFFFFF'>
<p><font face='Verdana, Arial, Helvetica, sans-serif'><b><font size='2'><?= get_page_data("5") ?> <?= $dis_month ?> <?= $_REQUEST['currentyear'] ?>.</font></b></font></p>
<table width='600' border='1' bordercolor='#CCCCCC' cellspacing='0' cellpadding='3'>
	<form method='post' action='payfees.php'>
  <tr bgcolor='#CCCCCC'>
    <td width='60'><font size='1' face='Verdana'><b><?= get_word("1") ?>:</b></font></td>
    <td width='250'><font size='1' face='Verdana'><b><?= get_word("3") ?>:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b>Owing:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b>Paid:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b>Claimable:</b></font></td>
  </tr>
<?
 $dbtq = dbRead("select * from members, invoice, tbl_admin_payment_types where (invoice.memid=members.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and tbl_admin_payment_types.ddrun='1' and invoice.date = '$date1' and (overduefees+currentpaid+currentfees)>'5' and members.CID = ".$_SESSION['User']['CID']." order by accountname");

  while($row = mysql_fetch_assoc($dbtq)) {

    $query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.dis_date > '$date1' and memid='$row[memid]' and transactions.dollarfees < '0' and to_memid != '".$_SESSION['Country']['rereserve']."' group by memid");
    $row1 = mysql_fetch_assoc($query1);

    $total=($row[overduefees]+$row[currentpaid]+$row[currentfees]);
    $total2 = $total+$row1[fees];

?>
 <tr>
   <td width='60'><font size='1' face='Verdana'><?= $row['memid'] ?></font>&nbsp;</td>
   <td width='250'><font size='1' face='Verdana'><?= get_all_added_characters($row['companyname']) ?></font>&nbsp;</td>
   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($total,2) ?></font>&nbsp;</td>
   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($row1[fees],2) ?></font>&nbsp;</td>
   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($total2,2) ?></font>&nbsp;</td>
 </tr>
<?

$totalT += $total2;
$totalT2 += $total;
}

?>
  <tr>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='250' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($totalT2,2) ?></b></font>&nbsp;</td>
    <td align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($totalT,2) ?></b></font>&nbsp;</td>
  </tr>
  </form>
</table>
</body>

<?
die;
}
?>

<form method="post" action="body.php?page=cashfeesinvoiced">

<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?>.</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
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
        <input type="submit" value="<?= get_word("83") ?>" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>

<?}
function unallocated() {

if($_REQUEST['year1'])  {

 if(checkmodule("Log")) {
  add_kpi("48", "0");
 }

$curdate = date("dS F Y");

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month1'],$_REQUEST['day1'],$_REQUEST['year1']));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month2'],$_REQUEST['day2'],$_REQUEST['year2']));
$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month1'],$_REQUEST['day1'],$_REQUEST['year1']));
$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month2'],$_REQUEST['day2'],$_REQUEST['year2']));

?>
<html>
<head>
<title>Unallocated Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Unallocated Fees</b></td>
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

//$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9 FROM members,feespaid WHERE (((feespaid.area = '$adminuserarray[$i]' ) AND (feespaid.memid = members.memid ) ) AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2' ) )");
$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, feesincurred.memid as value10 FROM members,feespaid left outer join feesincurred on (feespaid.feesincurrid = feesincurred.fieldid) WHERE (((feespaid.memid = members.memid ) ) and feespaid.type = 6 and members.CID = ".$_SESSION['User']['CID']." AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2' ) )");

?>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("41") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("3") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b>Type</b></td>
    <td align="left" class="Heading2" width="10"><b>BuyID</b></td>
    <td align="right" class="Heading2"><b><?= get_word("103") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_word("106") ?>:</b></td>
    <td align="right" class="Heading2"><b><?= get_word("105") ?></b></td>
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

	$value6 = $row[value4]-($row[value5]);
	$dis_value6 = number_format($value6,2);
	$dis_value4 = number_format($row[value4],2);
	$value7 = ($value6/100)*$row[value9];
	//$dis_value7 = number_format(round($value7),2);
	$dis_value7 = number_format($value7,2);

    if($row['type'] == 1) {
     $t = "B";
    } else {
     $t = "S";
    }
?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value1]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= substr($row[value2], 0, 20) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value3]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($t) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>" width="10"><?= get_all_added_characters($row[value10]) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($dis_value4) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[value5],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($dis_value6) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>" width="10"><?= get_all_added_characters($row[value9]) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><b><?= get_all_added_characters($dis_value7) ?></b></td>
  </tr>
<?
$total += $value7;
if($row['type'] == 1) {
 $total2 += $row[value4];
}
$foo++;

}

if($_SESSION['User']['CID'] == 12) {
 $dis_total = number_format(round($total),2);
} else {
 $dis_total = number_format($total,2);
}
?>
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
    <td align="left" bgcolor="#FFFFFF"><b></b></td>
    <td align="left" bgcolor="#FFFFFF"><b></b></td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= number_format($total2,2) ?></b></td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= $dis_total ?></b></td>
  </tr>
</table>
</td>
</tr>
</table>
<br><br>
</body>
</html>
<?
die;
}
?>
<html>
<head>
<title>Unallocated Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="dailytrans" method="POST" action="body.php?page=dailyfees">

<table width="600" border="0" cellspacing="0" cellpadding="1" align="left">
<tr>
<td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="left">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Unallocated Fees.</b></td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("101") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day1">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>
      </select>
      <select name="month1">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year1',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("102") ?>:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day2">
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 28) { echo "selected "; } ?>value="28">28</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 29) { echo "selected "; } ?>value="29">29</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 30) { echo "selected "; } ?>value="30">30</option>
        <option <? if(date("d", mktime(1,1,1,date("m")+1,1-1,date("Y"))) == 31) { echo "selected "; } ?>value="31">31</option>
      </select>
      <select name="month2">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
      </select>
		<?

		$query = get_year_array();
	    form_select('year2',$query,'','',date("Y"));

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
<?
}

function ddcheck(){

 $lastmonth = date("Y-m", mktime(1,1,1,date("m")-1,1,date("Y")));
 $thismonth = date("Y-m", mktime(1,1,1,date("m"),1,date("Y")));
?>
<body bgcolor='#FFFFFF'>
<p><font face='Verdana, Arial, Helvetica, sans-serif'><b><font size='2'><?= get_page_data("5") ?> <?= $dis_month ?> <?= $_REQUEST['currentyear'] ?>.</font></b></font></p>
<table width='600' border='1' bordercolor='#CCCCCC' cellspacing='0' cellpadding='3'>
	<form method='post' action='payfees.php'>
  <tr bgcolor='#CCCCCC'>
    <td width='60'><font size='1' face='Verdana'><b><?= get_word("1") ?>:</b></font></td>
    <td width='250'><font size='1' face='Verdana'><b><?= get_word("3") ?>:</b></font></td>
    <td width='50'><font size='1' face='Verdana'><b>Type:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b><?= get_word("42") ?>:</b></font></td>
  </tr>
<?
 $total = 0;

 $lastmonth = date("Y-m", mktime(1,1,1,date("m")-1,1,date("Y")));
 $thismonth = date("Y-m", mktime(1,1,1,date("m"),1,date("Y")));

 $query = dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe, paymenttype from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and (tbl_admin_payment_types.ccrun='1' or tbl_admin_payment_types.ddrun='1') and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 group by invoice.memid");

 while($row = mysql_fetch_assoc($query)) {

  $query3 = dbRead("select sum(dollarfees) as feespaid from transactions where memid='$row[memid]' and dollarfees < 0 and dis_date like '$thismonth-%'");
  $row3 = mysql_fetch_assoc($query3);

  $ChargeAmount = $row[feesowe] + $row3[feespaid];

  $exdate_temp = explode("/", $row[expires]);

  $exdate1 = $exdate_temp[0];
  $exdate2 = $exdate_temp[1];
  $thisyear = date("y");
  $thismonth2 = date("m");

  //if(($exdate2 > $thisyear) or (($exdate1 >= $thismonth2) and ($exdate2 == $thisyear))) {

   if($ChargeAmount > 5) {

	?>
	 <tr>
	   <td width='60'><font size='1' face='Verdana'><?= $row['memid'] ?></font>&nbsp;</td>
	   <td width='250'><font size='1' face='Verdana'><?= get_all_added_characters($row['companyname']) ?></font>&nbsp;</td>
	   <td width='50'><font size='1' face='Verdana'><?= get_all_added_characters($row['paymenttype']) ?></font>&nbsp;</td>
	   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($ChargeAmount,2) ?></font>&nbsp;</td>
	 </tr>
	<?
	$total = $total+$ChargeAmount;
	}
   //}

 }
?>
  <tr>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='250' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($total,2) ?></b></font>&nbsp;</td>
  </tr>
  </form>
</table>
<?
}
?>