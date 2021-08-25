<?

if(!checkmodule("MyServices")) {

?>
<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81")?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>

<form method="POST" action="body.php?page=solutions_report&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_REQUEST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array("Memberships","Benefits");

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

 report('1');

} elseif($_GET[tab] == "tab2") {

 report('2');

}

?>
</form>
<?

function report($type = false) {

$time_start = getmicrotime();

if($_REQUEST['next'] == 2) {

    if($type == 1) {
	  $query = dbRead("select transactions.*, services.Product as Product, registered_accounts.Acc_No as Acc_No from transactions left outer join registered_accounts on (transactions.regAccID = registered_accounts.FieldID) left outer join plans on (registered_accounts.Plan_ID = plans.FieldID) left outer join services on (plans.ServiceID = services.FieldID and plans.CID = services.CID) where dis_date >= '".$_REQUEST['year1']."-".$_REQUEST['month1']."-".$_REQUEST['day1']."' and dis_date <= '".$_REQUEST['year2']."-".$_REQUEST['month2']."-".$_REQUEST['day2']."' and sell != 0 order by dis_date","empire_solutions");
	  $gg = "Memberships Payment";
	} else {
	  $query = dbRead("select transactions.*, services.Product as Product, registered_accounts.Acc_No as Acc_No from transactions left outer join registered_accounts on (transactions.regAccID = registered_accounts.FieldID) left outer join plans on (registered_accounts.Plan_ID = plans.FieldID) left outer join services on (plans.ServiceID = services.FieldID and plans.CID = services.CID ) where dis_date >= '".$_REQUEST['year1']."-".$_REQUEST['month1']."-".$_REQUEST['day1']."' and dis_date <= '".$_REQUEST['year2']."-".$_REQUEST['month2']."-".$_REQUEST['day2']."' and buy != 0 order by dis_date","empire_solutions");
	  $gg = "Memberships Benefits";
	}
?>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="8" align="center" class="Heading">Empire Solutions - <?= $gg ?></td>
	</tr>
	<tr>
		<td class="Heading2"><b><?= get_word("41") ?>:</b></font></td>
		<td class="Heading2"><b>Acc No:</b></font></td>
		<td class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td class="Heading2"><b>Method:</b></font></td>
		<td class="Heading2"><b>Fees:</b></font></td>
		<td class="Heading2"><b>Details:</b></font></td>
		<td align=right class="Heading2"><b><?= get_word("61") ?>:</b></font></td>
		<td align=right class="Heading2"><b>Total:</b></font></td>
	</tr>
	<?

	$foo = 0;

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

    if($type == 1) {
     $amount = $row['sell'];
    } else {
     $amount = $row['buy'];
    }
	?>
	<tr bgcolor="<?= $bgcolor ?>">
		<td><?= $row['dis_date'] ?></td>
		<td><?= $row['regAccID'] ?></td>
		<td><?= $row['Acc_No'] ?></td>
		<td><?= $row['receipt'] ?></a></td>
		<td align=center><?= number_format($row['dollarfees'],2) ?></td>
		<td><?= $row['details'] ?></td>
		<td align=right><?= number_format($amount,2) ?></td>
		<td align=right><?= number_format($amount+$row['dollarfees'],2) ?></td>
	</tr>
	<?

     $ttotal += $amount + $row['dollarfees'];
     $total += $row['dollarfees'];
     if($row['type_id'] == 1) {
      $TOTAL[$row['Product']] += $amount;
     } else {
      $TOTALt[$row['Product']] += $amount;
	 }
	 $foo++;

	}
	?>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="4" align="right"><b>Total:</b></td>
		<td align="right"><b><?= number_format($total,2) ?></b></td>
		<td colspan="2" align="right"><b>Total:</b></td>
		<td align="right" style="border-top: 1px solid #000000"><b><?= number_format($ttotal,2) ?></b></td>
	 </tr>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="6" align="right"><b></b></td>
		<td align="right"><b>&nbsp;</b></td>
		<td align="right"><b></b></td>
	 </tr>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="6" align="right"><b></b></td>
		<td align="right"><b>Trade</b></td>
		<td align="right"><b>Cash</b></td>
	 </tr>
	 <?
	if(@mysql_num_rows($query) > 0) {

	 foreach($TOTAL as $key => $value) {

	 $Type = get_type($key);

     $totals += $value;

	 ?>
 	 <tr bgcolor="#FFFFFF">
		<td colspan="6" align="right"><b><?= $key ?>:</b></td>
		<td align="right"><b><?= number_format($TOTALt[$key],2) ?></b></td>
		<td align="right"><b><?= number_format($value,2) ?></b></td>
	 </tr>
	 <?

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
</table>
</td>
</tr>
</table>

<?
} else {

?>
<html>
<head>
<title>Daily Fees</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body>

<form name="ccreport" method="POST" action="body.php?page=services_report">

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
}
