<?
if(!checkmodule("CCReport")) {

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

$time_start = getmicrotime();

if($_REQUEST['next'] == 2) {

 second_form($_REQUEST['day1'],$_REQUEST['month1'],$_REQUEST['year1'],$_REQUEST['day2'],$_REQUEST['month2'],$_REQUEST['year2']);

} else {

 first_form();

}

function second_form($day,$month,$year,$day2,$month2,$year2) {

 global $time_start;

 if(checkmodule("Log")) {
  add_kpi("24", "0");
 }


?>
<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="7" align="center" class="Heading">Empire Solutions - Credit Card Report</td>
	</tr>
	<tr>
		<td class="Heading2"><b><?= get_word("41") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("135") ?>:</b></font></td>
		<td class="Heading2"><b>Approved:</b></font></td>
		<td class="Heading2"><b><?= get_word("61") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("186") ?>:</b></font></td>
		<td class="Heading2"><b><?= get_word("86") ?>:</b></font></td>
	</tr>
	<?

	$foo = 0;

	$query = dbRead("select credit_transactions.memid as memid, card_name as cname, credit_transactions.date as date, credit_transactions.success as success, credit_transactions.amount as amount, credit_transactions.response_code as response_code, credit_transactions.type as type, members.companyname as companyname from credit_transactions, members where (credit_transactions.memid = members.memid) and date >= '$year-$month-$day' and date <= '$year2-$month2-$day2' and success not like 'In_Progress' and type = '12' order by type");
	while($row = mysql_fetch_assoc($query)) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

	$Response = get_error($row['response_code']);
	$Type = get_type($row['type']);
	if($row['memid'] == 1) { $DisplayName = "General Charge"; } else { $DisplayName = $row['companyname']; }

	$pre_date = explode("-", $row['date']);
	$new_date = date("d/m/Y", mktime(1,1,1,$pre_date[1],$pre_date[2],$pre_date[0]));

	?>
	<tr bgcolor="<?= $bgcolor ?>">
		<td><?= $new_date ?></td>
		<td><?= $DisplayName ?></td>
		<td><?= $row['cname'] ?></a></td>
		<td><?= $row['success'] ?></a></td>
		<td><?= $_SESSION['Country']['currency'] ?><?= number_format($row['amount'],2) ?></td>
		<td><?= $Response ?>[<?= $row['response_code'] ?>]</td>
		<td><?= $Type ?></td>
	</tr>
	<?

	 if($row['success'] == "Yes") { $TOTAL[$row['type']] += $row['amount']; } else { $TOTAL[$row['type']] += 0; }
	 $foo++;

	}

	if(@mysql_num_rows($query) > 0) {

	 foreach($TOTAL as $key => $value) {

	 $Type = get_type($key);

     $totals += $value;

	 ?>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="6" align="right"><b><?= $Type ?>:</b></td>
		<td align="left"><b><?= $_SESSION['Country']['currency'] ?><?= number_format($value,2) ?></b></td>
	 </tr>
	 <?

	 }

	}

	?>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="6" align="right"><b>Total:</b></td>
		<td align="left" style="border-top: 1px solid #000000"><b><?= $_SESSION['Country']['currency'] ?><?= number_format($totals,2) ?></b></td>
	 </tr>
	<tr>
	    <td colspan="7" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
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

<?

}

function first_form() {

?>
<html>
<head>
<title>Daily Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="ccreport" method="POST" action="body.php?page=solutions_ccreport">

<input type="hidden" name="next" value="2">

<table width="400" border="0" cellspacing="1" cellpadding="1" align="center">
<tr>
<td class="Border">
<table width="400" border="0" cellspacing="0" cellpadding="3" align="center">
  <tr>
    <td colspan="2" class="Heading2" align="center"><b>Empire Solutions - Credit Card Report</b></td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("101") ?>: </b></td>
    <td bgcolor="#FFFFFF">
      <select name="day1">
        <option <? if(date("d") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("d") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("d") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("d") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("d") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("d") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("d") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("d") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("d") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("d") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("d") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("d") == "12") { echo "selected "; } ?>value="12">12</option>
        <option <? if(date("d") == "13") { echo "selected "; } ?>value="13">13</option>
        <option <? if(date("d") == "14") { echo "selected "; } ?>value="14">14</option>
        <option <? if(date("d") == "15") { echo "selected "; } ?>value="15">15</option>
        <option <? if(date("d") == "16") { echo "selected "; } ?>value="16">16</option>
        <option <? if(date("d") == "17") { echo "selected "; } ?>value="17">17</option>
        <option <? if(date("d") == "18") { echo "selected "; } ?>value="18">18</option>
        <option <? if(date("d") == "19") { echo "selected "; } ?>value="19">19</option>
        <option <? if(date("d") == "20") { echo "selected "; } ?>value="20">20</option>
        <option <? if(date("d") == "21") { echo "selected "; } ?>value="21">21</option>
        <option <? if(date("d") == "22") { echo "selected "; } ?>value="22">22</option>
        <option <? if(date("d") == "23") { echo "selected "; } ?>value="23">23</option>
        <option <? if(date("d") == "24") { echo "selected "; } ?>value="24">24</option>
        <option <? if(date("d") == "25") { echo "selected "; } ?>value="25">25</option>
        <option <? if(date("d") == "26") { echo "selected "; } ?>value="26">26</option>
        <option <? if(date("d") == "27") { echo "selected "; } ?>value="27">27</option>
        <option <? if(date("d") == "28") { echo "selected "; } ?>value="28">28</option>
        <option <? if(date("d") == "29") { echo "selected "; } ?>value="29">29</option>
        <option <? if(date("d") == "30") { echo "selected "; } ?>value="30">30</option>
        <option <? if(date("d") == "31") { echo "selected "; } ?>value="31">31</option>
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
      <select name="year1">
        <option <? if(date("Y") == "2000") { echo "selected "; } ?>value="2000">2000</option>
        <option <? if(date("Y") == "2001") { echo "selected "; } ?>value="2001">2001</option>
        <option <? if(date("Y") == "2002") { echo "selected "; } ?>value="2002">2002</option>
        <option <? if(date("Y") == "2003") { echo "selected "; } ?>value="2003">2003</option>
        <option <? if(date("Y") == "2004") { echo "selected "; } ?>value="2004">2004</option>
        <option <? if(date("Y") == "2005") { echo "selected "; } ?>value="2005">2005</option>
        <option <? if(date("Y") == "2006") { echo "selected "; } ?>value="2006">2006</option>
        <option <? if(date("Y") == "2007") { echo "selected "; } ?>value="2007">2007</option>
      </select>
    </td>
  </tr>
  <tr>
    <td width="100" align="right" class="Heading2"><b><?= get_word("102") ?>: </b></td>
    <td bgcolor="#FFFFFF">
      <select name="day2">
        <option <? if(date("d") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("d") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("d") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("d") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("d") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("d") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("d") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("d") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("d") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("d") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("d") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("d") == "12") { echo "selected "; } ?>value="12">12</option>
        <option <? if(date("d") == "13") { echo "selected "; } ?>value="13">13</option>
        <option <? if(date("d") == "14") { echo "selected "; } ?>value="14">14</option>
        <option <? if(date("d") == "15") { echo "selected "; } ?>value="15">15</option>
        <option <? if(date("d") == "16") { echo "selected "; } ?>value="16">16</option>
        <option <? if(date("d") == "17") { echo "selected "; } ?>value="17">17</option>
        <option <? if(date("d") == "18") { echo "selected "; } ?>value="18">18</option>
        <option <? if(date("d") == "19") { echo "selected "; } ?>value="19">19</option>
        <option <? if(date("d") == "20") { echo "selected "; } ?>value="20">20</option>
        <option <? if(date("d") == "21") { echo "selected "; } ?>value="21">21</option>
        <option <? if(date("d") == "22") { echo "selected "; } ?>value="22">22</option>
        <option <? if(date("d") == "23") { echo "selected "; } ?>value="23">23</option>
        <option <? if(date("d") == "24") { echo "selected "; } ?>value="24">24</option>
        <option <? if(date("d") == "25") { echo "selected "; } ?>value="25">25</option>
        <option <? if(date("d") == "26") { echo "selected "; } ?>value="26">26</option>
        <option <? if(date("d") == "27") { echo "selected "; } ?>value="27">27</option>
        <option <? if(date("d") == "28") { echo "selected "; } ?>value="28">28</option>
        <option <? if(date("d") == "29") { echo "selected "; } ?>value="29">29</option>
        <option <? if(date("d") == "30") { echo "selected "; } ?>value="30">30</option>
        <option <? if(date("d") == "31") { echo "selected "; } ?>value="31">31</option>
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
      <select name="year2">
        <option <? if(date("Y") == "2000") { echo "selected "; } ?>value="2000">2000</option>
        <option <? if(date("Y") == "2001") { echo "selected "; } ?>value="2001">2001</option>
        <option <? if(date("Y") == "2002") { echo "selected "; } ?>value="2002">2002</option>
        <option <? if(date("Y") == "2003") { echo "selected "; } ?>value="2003">2003</option>
        <option <? if(date("Y") == "2004") { echo "selected "; } ?>value="2004">2004</option>
        <option <? if(date("Y") == "2005") { echo "selected "; } ?>value="2005">2005</option>
        <option <? if(date("Y") == "2006") { echo "selected "; } ?>value="2006">2006</option>
        <option <? if(date("Y") == "2007") { echo "selected "; } ?>value="2007">2007</option>
      </select>
    </td>
  </tr>
  <tr>
    <td width="100" class="Heading2">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="submit" name="search" value="<?= get_word("48") ?>"></td>
  </tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?

}
