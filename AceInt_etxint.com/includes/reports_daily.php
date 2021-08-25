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

<form method="POST" action="body.php?page=reports_daily&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();
$tabarray = array(get_page_data("9"),get_page_data("2"),get_word("47"));

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  fees();

} elseif($_GET[tab] == "tab2") {

  trans();

} elseif($_GET[tab] == "tab3") {

  owing();

}

?>

</form>

<?
function fees()  {

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
<title>Daily Transactions</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("1") ?></b></td>
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

if(checkmodule("SuperUser")) {
 $SearchReports = "all";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
}

if($SearchReports == "all") {
 if(checkmodule("SuperUser")) {
  //$SearchCID = "%";
  $SearchCID = $_REQUEST['countryID'];
 } else {
  $SearchCID = $_SESSION['User']['CID'];
 }

 if($_REQUEST['areaID']) {
	$ar = " and FieldID = ".$_REQUEST['areaID'];
 } else {
	$ar = "";
 }

 $query3 = dbRead("select FieldID from area where CID like '$SearchCID' $ar");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= "$row3[FieldID],";
 }
 $adminuserarray = explode(",", $at);
} else {
 $adminuserarray = explode(",", $SearchReports);
}

 $count = sizeof($adminuserarray);
 $i = 0;
 for ($i = 0; $i < $count; $i++) {

if($_REQUEST['countryID']) {
	$cc = " and members.CID = ".$_REQUEST['countryID']."";
} else {
	$cc = "";
}

#get areaname
$query4 = dbRead("select place, feepercent from area where FieldID='$adminuserarray[$i]'");
$row4 = mysql_fetch_assoc($query4);

if($row4['feepercent'] < 26) {
	$hh = " and feespaid.percent <> 50 ";
} else {
	$hh = "";
}

?>
<b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><?= $row4[place] ?>(<?= $adminuserarray[$i] ?>)</font></b><br>
<?

//$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9 FROM members,feespaid WHERE (((feespaid.area = '$adminuserarray[$i]' ) AND (feespaid.memid = members.memid ) ) AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2' ) )");
$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, feesincurred.memid as value10 FROM members,feespaid left outer join feesincurred on (feespaid.feesincurrid = feesincurred.fieldid) WHERE (((feespaid.area = '$adminuserarray[$i]' ) AND (feespaid.memid = members.memid )$cc $hh ) and feespaid.type not in (6,7,8,9) AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2' ) ) order by feespaid.paymentdate");

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
    } elseif($row['type'] == 5)  {
     $t = "ACS";
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
<? } ?>

</body>
</html>
<?
die;
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
  <?if(checkmodule("SuperUser")) {?>
    <tr>
    <td width="100" align="right" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID']);
          ?>
      </td>
    </tr>
    <?}?>
    <tr>
    <td width="100" align="right" class="Heading2"><b>Agent Area:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
         if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
              $count=0;
              $newarray = explode(",", $_SESSION['User']['ReportsAllowed']);
   			  foreach($newarray as $cat_val) {
    			if($count == 0) {
    			 $andor="";
 				} else {
 				 $andor=",";
				}

 				$cat_array.="".$andor."".$cat_val."";

 				$count++;

 			  }
 			  $areas = " and (FieldID in ($cat_array))";
		   }

           //$query2 = dbRead("select place,FieldID from area where CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");

           $query2 = dbRead("select * from area where CID='".$_SESSION['Country']['countryID']."' order by place");
           form_select('areaID',$query2,'place','FieldID',$_REQUEST['areaID']);
          ?>
      </td>
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
<?}

function trans()  {

if($_REQUEST['year1'])  {

 if(checkmodule("Log")) {
  add_kpi("49", "0");
 }

$curdate = date("dS F Y");

$fromdate = date("dS F Y", mktime(0,0,1,$_REQUEST['month1'],$_REQUEST['day1'],$_REQUEST['year1']));
$todate = date("dS F Y", mktime(0,0,1,$_REQUEST['month2'],$_REQUEST['day2'],$_REQUEST['year2']));
$fromdate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month1'],$_REQUEST['day1'],$_REQUEST['year1']));
$todate2 = date("Y-m-d", mktime(0,0,1,$_REQUEST['month2'],$_REQUEST['day2'],$_REQUEST['year2']));

?>
<html>
<head>
<title>Daily Transactions</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b><?= get_page_data("2") ?></b></td>
  </tr>
  <tr>
    <td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>: </b></td>
    <td bgcolor="#FFFFFF"><?= $_SESSION['Username'] ?></td>
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
<br>

<?

if(checkmodule("SuperUser")) {
 $SearchReports = "all";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
}

if($SearchReports == "all") {
 if(checkmodule("SuperUser")) {
  //$SearchCID = "%";
  $SearchCID = $_REQUEST['countryID'];
 } else {
  $SearchCID = $_SESSION['User']['CID'];
 }
 $query3 = dbRead("select FieldID from area where CID like '$SearchCID'");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= "$row3[FieldID],";
 }
 $adminuserarray = explode(",", $at);
} else {
 $adminuserarray = explode(",", $SearchReports);
}

 $count = sizeof($adminuserarray);
 $i = 0;
 for ($i = 0; $i < $count; $i++) {

#get areaname
$query4 = dbRead("select place from area where FieldID='$adminuserarray[$i]'");
$row4 = mysql_fetch_assoc($query4);

if($_REQUEST['countryID']) {
	$cc = " and members.CID = ".$_REQUEST['countryID']."";
	$cc1 = " and members_1.CID = ".$_REQUEST['countryID']."";
} else {
	$cc = "";
	$cc1 = "";
}
?>
<b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><?= $row4[place] ?></font></b><br>
<?

//$dbquery = dbRead("SELECT transactions.dis_date as value1,members.memid as value2,members.companyname as value3,members_1.memid as value4,members_1.companyname as value5,transactions.buy as value6,members.licensee as value7 FROM transactions,members,members members_1 WHERE (((members.memid = transactions.memid) AND (transactions.to_memid = members_1.memid)) AND (((((transactions.type = '1') AND (transactions.buy > 0)) AND (transactions.dis_date BETWEEN '$fromdate2' AND '$todate2' )) AND (members.licensee = '$adminuserarray[$i]')) OR ((((transactions.type = '1') AND (transactions.buy > 0)) AND (transactions.dis_date BETWEEN '$fromdate2' AND '$todate2' )) AND (members_1.licensee = '$adminuserarray[$i]' )))) order by value7,value1");
//$dbquery = dbRead("SELECT transactions.dis_date as value1,members.memid as value2,members.companyname as value3,members_1.memid as value4,members_1.companyname as value5,transactions.buy as value6,members.licensee as value7,members_1.licensee as value77 FROM transactions,members,members members_1 WHERE (((members.memid = transactions.memid) AND (transactions.to_memid = members_1.memid) and (transactions.memid Not IN (".get_non_included_accounts($_SESSION['User']['CID']).")) and (transactions.to_memid Not IN (".get_non_included_accounts($_SESSION['User']['CID'])."))) AND (((((transactions.type = '1') AND (transactions.buy > 0)) AND (transactions.dis_date BETWEEN '$fromdate2' AND '$todate2' )) AND (members.licensee = '$adminuserarray[$i]')) OR ((((transactions.type = '1') AND (transactions.buy > 0)) AND (transactions.dis_date BETWEEN '$fromdate2' AND '$todate2' )) AND (members_1.licensee = '$adminuserarray[$i]' )))) order by value7,value1");
$dbquery = dbRead("SELECT transactions.dis_date as value1,members.memid as value2,members.companyname as value3,members_1.memid as value4,members_1.companyname as value5,transactions.buy as value6,members.licensee as value7,members_1.licensee as value77, transactions.dollarfees as value8 FROM transactions,members,members members_1 WHERE (((members.memid = transactions.memid) AND (transactions.to_memid = members_1.memid) and (transactions.memid Not IN (".get_non_included_accounts($_SESSION['User']['CID'],'','','',true)."))$cc and (transactions.to_memid Not IN (".get_non_included_accounts($_SESSION['User']['CID'],'','','',true).")))$cc1 AND (((((transactions.type = '1') AND (transactions.buy > 0)) AND (transactions.dis_date BETWEEN '$fromdate2' AND '$todate2' )) AND (members.licensee = '$adminuserarray[$i]')) OR ((((transactions.type = '1') AND (transactions.buy > 0)) AND (transactions.dis_date BETWEEN '$fromdate2' AND '$todate2' )) AND (members_1.licensee = '$adminuserarray[$i]' )))) order by value1");

?>
<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("41") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("43") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_page_data("4") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("44") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_page_data("5") ?></b></td>
    <td align="right" class="Heading2"><b>Fees</b></td>
    <td align="right" class="Heading2"><b><?= get_word("61") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_page_data("6") ?></b></td>
    <td align="right" class="Heading2"></td>
  </tr>
<?

$foo= 0;
$totalbuy = 0;
$totalsell = 0;
$totalfee = 0;
while($row = mysql_fetch_assoc($dbquery)) {

	$row[value3] = substr($row[value3], 0, 20);
	$row[value5] = substr($row[value5], 0, 20);

	$array = explode("-", $row[value1]);
	$month = $array[1];
	$day = $array[2];
	$year = $array[0];
	$row[value1] = date("dMY", mktime(0,0,1,$month,$day,$year));

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

    $ty = "";

    if($row[value7] != $adminuserarray[$i]) {
     $other = $row[value7];
     $ty = "B";
     $row[value8] = "0.00";
    } else {
     $other = $row[value77];
     if($row[value77] != $adminuserarray[$i]) {
      $ty = "S";
     }
    }

	//$query2 = dbRead("select place from area where FieldID='$row[value7]'");
	$query2 = dbRead("select place from area where FieldID='$other'");
	$row2 = mysql_fetch_assoc($query2);

?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value1]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value2]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value3]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value4]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value5]) ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value8]) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[value6]) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= substr($row2[place],0,20) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($ty) ?></td>
  </tr>
<?

//if($row[value7] == $adminuserarray[$i]) {
 //$totalbuy += $row[value6];
//} else {
 //$totalsell += $row[value6];
//}

if($row[value7] == $adminuserarray[$i]) {
 $totalbuy += $row[value6];
}
if($row[value77] == $adminuserarray[$i]) {
 $totalsell += $row[value6];
}

$totalfee += $row[value8];
$foo++;

}

?>
 <tr>
  <td colspan="6" align="right" bgcolor="#FFFFFF"><b>Total Fees:</b></td>
  <td colspan="2" align="left" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalfee,2) ?></td>
 </tr>
 <tr>
  <td colspan="6" align="right" bgcolor="#FFFFFF"><b><?= get_page_data("7") ?>:</b></td>
  <td colspan="2" align="left" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalbuy,2) ?></td>
 </tr>
 <tr>
  <td colspan="6" align="right" bgcolor="#FFFFFF"><b><?= get_page_data("8") ?>:</b></td>
  <td colspan="2" align="left" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalsell,2) ?></td>
 </tr>
</table>
</td>
</tr>
</table>
<br><br>
<? } ?>

</body>
</html>

<?
die;
}
?>

<html>
<head>
<title>Daily Transactions</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="dailytrans" method="POST" action="body.php?page=dailytrans">

<table width="600" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" class="Heading" align="center"><b><?= get_page_data("2") ?>.</b></td>
  </tr>
  <?if(checkmodule("SuperUser")) {?>
    <tr>
      <td width="100" align="right" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID']);
          ?>
      </td>
    </tr>
    <?}?>
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

function owing() {
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<table width="620" cellspacing="0" cellpadding="1">
<tr>
<td class="Border">
<table width="100%" cellspacing="0" cellpadding="3">
 <tr>
  <td width="100%" align="center" class="Heading2"><b><?= get_word("47") ?>.</b></td>
 </tr>
</table>
<table width="620" cellspacing="0" cellpadding="3">


<?
if(checkmodule("SuperUser")) {
 $SearchReports = "all";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
}

if($SearchReports == "all") {
 //if(checkmodule("SuperUser")) {
  //$SearchCID = "%";
 //} else {
  $SearchCID = $_SESSION['User']['CID'];
 //}
 $query3 = dbRead("select FieldID from area where CID like '$SearchCID'");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= "".$row3['FieldID'].",";
 }
 $adminuserarray = explode(",", $at);
} else {
 $adminuserarray = explode(",", trim($SearchReports));
}

  $count=0;
  $trust = explode(",", $_SESSION['Country']['trustacc']);
  foreach($trust as $key => $value) {
   if($count == 0) {
    $andor="";
   } else {
    $andor="and";
   }
   $trust_array.=" ".$andor." transactions.to_memid <> '".$value."'";
   $count++;
  }

 $trust_array .= " and transactions.to_memid <> '".$_SESSION['Country']['rereserve']."'";

 add_kpi("50",0);

 $count = sizeof($adminuserarray);
 $i = 0;
 for ($i = 0; $i < $count; $i++) {

#get areaname
$query4 = dbRead("select place from area where FieldID='$adminuserarray[$i]'");
$row4 = mysql_fetch_assoc($query4);

$total=0;
$total2=0;

?>
  <tr>
    <td colspan="4" align="center" class="Heading2"><b><?= get_all_added_characters($row4['place']) ?></b>&nbsp;</td>
  </tr>
  <tr>
    <td width="80" class="Heading2"><b><?= get_word("1") ?>:</b></td>
    <td class="Heading2"><b><?= get_word("3") ?>:</b></td>
    <td class="Heading2"><b><?= get_word("7") ?>:</b></td>
    <td class="Heading2" align="right" width="70"><b><?= get_word("88") ?>:</b></td>
  </tr>
<?
if($SearchCID == 9) {
$query = dbRead("SELECT SUM(transactions.dollarfees) as feesowe,transactions.memid as memid, members.companyname as companyname, members.phonearea as phonearea, members.phoneno as phoneno FROM members,transactions WHERE ((members.licensee = '$adminuserarray[$i]') and (transactions.memid = members.memid) and ($trust_array)) and transactions.memid <> '16755' GROUP BY transactions.memid ,members.licensee HAVING (SUM(transactions.dollarfees ) > 0 )");
} else {
$query = dbRead("SELECT SUM(transactions.dollarfees) as feesowe,transactions.memid as memid, members.companyname as companyname, members.phonearea as phonearea, members.phoneno as phoneno FROM members,transactions WHERE ((members.licensee = '$adminuserarray[$i]') and (transactions.memid = members.memid) and ($trust_array)) and transactions.memid <> '16755' GROUP BY transactions.memid ,members.licensee  HAVING (SUM(transactions.dollarfees ) > 19.99 )");
}
//

	$foo=0;

	while($row = mysql_fetch_assoc($query)) {

	 $feesowe2 = number_format($row['feesowe'],2);


	 $cfgbgcolorone = "#CCCCCC";
	 $cfgbgcolortwo = "#EEEEEE";
	 $bgcolor = $cfgbgcolorone;
	 $foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
?>
	  <tr bgcolor="<?= $bgcolor ?>">
	    <td width="80"><?= $row['memid'] ?>&nbsp;</td>
	    <td><?= get_all_added_characters($row['companyname']) ?>&nbsp;</td>
	    <td><?= get_all_added_characters($row['phonearea']) ?>&nbsp;<?= get_all_added_characters($row['phoneno']) ?></td>
	    <td align="right" width="70"><?= $_SESSION['Country']['currency']?><?= $feesowe2 ?></td>
	  </tr>
<?

		$total += $row['feesowe'];

	$foo++;
}

$total2 = number_format($total,2);

?>
  <tr>
    <td width="80" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" width="70" bgcolor="#FFFFFF"><b><?= $_SESSION['Country']['currency']?><?= $total2 ?></b></td>
  </tr>
  <tr>
   <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
<?

}

?>
</form>
</table>
</td>
</tr>
</table>
</body>
</html>

<?
}

