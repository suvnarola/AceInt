<?

include("class.html.mime.mail.inc");
if(!checkmodule("ManReports")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
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
if($_SESSION['User']['CID'] == 1) {
if($_REQUEST['countryID']) {
 $GET_CID = $_REQUEST['countryID'];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}
} else {
 $GET_CID = $_SESSION['User']['CID'];
}
?>
<html>
<head>
<script>

function ChangeCountry(list) {
 var url = 'body.php?page=reports_man&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>&countryID=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<form method="POST" action="body.php?page=reports_man&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_POST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array('Activity by Month','Activity by Area','Listed by Area','Listed by Month','Staff Comm','Staff Comm2');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Activity by Month") {

 monthly();

} elseif($_GET[tab] == "Activity by Area") {

 area();

} elseif($_GET[tab] == "Listed by Area") {

 l_area();

} elseif($_GET[tab] == "Listed by Month") {

 l_month();

} elseif($_GET[tab] == "Staff Comm") {

 staff_comm();

} elseif($_GET[tab] == "Staff Comm2") {

 staff_comm2();

}


?>
</form>
</html>
<?

function monthly() {

 global $time_start;

 $colspan = "14";

if($_REQUEST[search]) {

 $date2 = "".$_REQUEST['currentyear']."".$_REQUEST['currentmonth']."";

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Monthly
		Agent
     Activity Report for <?= $date2 ?></td>
   </tr>
   <tr>
     <td class="Heading2" nowrap align = "right" colspan="1"><b></b></td>
     <td class="Heading2" nowrap align = "left" colspan="7"><b>GOOD & SERVICES</b></td>
     <td class="Heading2" nowrap align = "left" colspan="4"><b>REAL ESTATE</b></td>
     <td class="Heading2" nowrap align = "left" colspan="2"><b>MEMBERS</b></td>
   </tr>
   <tr>
     <td class="Heading2" width = "100"><b>Agent:</b></td>
     <td class="Heading2" nowrap align = "right"><b>No Trans:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Buys:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Sells:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T_Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b></b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T_Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>N/Members:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Paid N/Mem:</b></td>
   </tr>
<?

  $foo = 0;
  if($_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    $query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' order by state, place ASC");
  } elseif(!$_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    //$query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 group by CID order by place ASC");
    $query = dbRead("select name as place, sum(GS_NoTrans) as GS_NoTrans, sum(GS_CashPaid) as GS_CashPaid, sum(RE_CashPaid) as RE_CashPaid, sum(GS_Buy) as GS_Buy, sum(GS_Sell) as GS_Sell, sum(GS_CashIncurred) as GS_CashIncurred, sum(RE_CashIncurred) as RE_CashIncurred, sum(GS_Facility) as GS_Facility, sum(T_GS_Facility) as T_GS_Facility, sum(RE_Facility) as RE_Facility, sum(T_RE_Facility) as T_RE_Facility, sum(MemCount) as MemCount, sum(MemCountPaid) as MemCountPaid from tbl_miscstats, area, country where (tbl_miscstats.AreaID = area.FieldID) and (area.CID = country.countryID) and Month = '".$date2."' and country.Display = 'Yes' group by name order by name");
  } else {
    $query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_SESSION['User']['CID']."' order by place ASC");
  }

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Area Data.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "100"><?= $row[place] ?></td>
      <td  nowrap align = "right"><?= $row[GS_NoTrans] ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_CashIncurred],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_CashPaid],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_Buy],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_Sell],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_Facility],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[T_GS_Facility],2) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($row[RE_CashPaid],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[RE_Facility],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[T_RE_Facility],2) ?></td>
      <td  nowrap align = "right"><?= $row[MemCount] ?></td>
      <td  nowrap align = "right"><?= $row[MemCountPaid] ?></td>
    </tr>
<?
    $gsnt+=$row[GS_NoTrans];
    $gscit+=$row[GS_CashIncurred];
    $gscpt+=$row[GS_CashPaid];
    $gsbt+=$row[GS_Buy];
    $gsst+=$row[GS_Sell];
    $gsft+=$row[GS_Facility];
    $tgsft+=$row[T_GS_Facility];
    $recit+=$row[RE_CashIncurred];
    $recpt+=$row[RE_CashPaid];
    $reft+=$row[RE_Facility];
    $treft+=$row[T_RE_Facility];
    $mt+=$row[MemCount];
    $mpt+=$row[MemCountPaid];

 	$foo++;
 	}
   }
?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= $gsnt ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscpt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsbt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsst,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($tgsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recpt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($reft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($treft,2) ?></td>
      <td  nowrap align = "right"><B><?= $mt ?></td>
      <td  nowrap align = "right"><B><?= $mpt ?></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Activity Report by Month</td>
   </tr>
   <?
   if($_SESSION['User']['CID'] == 1)  {
   ?>
   <tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID'],'All Countries');
          ?>
      </td>
    </tr>
    <?}?>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ((date("m")-1) == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ((date("m")-1) == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ((date("m")-1) == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ((date("m")-1) == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ((date("m")-1) == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ((date("m")-1) == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ((date("m")-1) == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ((date("m")-1) == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ((date("m")-1) == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ((date("m")-1) == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ((date("m")-1) == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ((date("m")-1) == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
   <tr>
	<tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}

}


function area() {

 global $time_start, $GET_CID;

 $colspan = "14";
 $startdate = date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-$_REQUEST['pm'],1,$_REQUEST['currentyear']));
 $enddate = date("Ym", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

if($_REQUEST[search]) {

 if($_REQUEST['area'])  {
  $queryarea = dbRead("select * from area where FieldID = '".$_REQUEST['area']."'");
  $rowarea = mysql_fetch_array($queryarea);
  $placename = $rowarea[place];
 } elseif($_REQUEST['state']) {
  $placename = $_REQUEST['state'];
 } else {
  $placename = $GET_CID;
 }
?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Area
     Activity Report for <?= $placename ?> Between <?= $startdate ?> and <?= $enddate ?></td>
   </tr>
   <tr>
     <td class="Heading2" nowrap align = "right" colspan="1"><b></b></td>
     <td class="Heading2" nowrap align = "left" colspan="7"><b>GOOD & SERVICES</b></td>
     <td class="Heading2" nowrap align = "left" colspan="4"><b>REAL ESTATE</b></td>
     <td class="Heading2" nowrap align = "left" colspan="2"><b>MEMBERS</b></td>
   </tr>
   <tr>
     <td class="Heading2" width = "100"><b>Month:</b></td>
     <td class="Heading2" nowrap align = "right"><b>No Trans:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Buys:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Sells:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T/Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b></b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T/Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>N/Members:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Paid N/Mem:</b></td>
   </tr>
<?

  $foo = 0;

  if ($_REQUEST[area])  {
    $query = dbRead("select * from tbl_miscstats where AreaID = '".$_REQUEST[area]."' and Month >= $startdate and Month <= $enddate order by Month DESC");
  } elseif($_REQUEST[state]) {
    $query = dbRead("select Month, sum(GS_NoTrans) as GS_NoTrans, sum(GS_CashPaid) as GS_CashPaid, sum(RE_CashPaid) as RE_CashPaid, sum(GS_Buy) as GS_Buy, sum(GS_Sell) as GS_Sell, sum(GS_CashIncurred) as GS_CashIncurred, sum(RE_CashIncurred) as RE_CashIncurred, sum(GS_Facility) as GS_Facility, sum(T_GS_Facility) as T_GS_Facility, sum(RE_Facility) as RE_Facility, sum(T_RE_Facility) as T_RE_Facility, sum(MemCount) as MemCount, sum(MemCountPaid) as MemCountPaid from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and CID = '".$GET_CID."' and state = '".$_REQUEST[state]."' and Month >= $startdate and Month <= $enddate group by Month order by Month DESC");
  } else {
    $query = dbRead("select Month, sum(GS_NoTrans) as GS_NoTrans, sum(GS_CashPaid) as GS_CashPaid, sum(RE_CashPaid) as RE_CashPaid, sum(GS_Buy) as GS_Buy, sum(GS_Sell) as GS_Sell, sum(GS_CashIncurred) as GS_CashIncurred, sum(RE_CashIncurred) as RE_CashIncurred, sum(GS_Facility) as GS_Facility, sum(T_GS_Facility) as T_GS_Facility, sum(RE_Facility) as RE_Facility, sum(T_RE_Facility) as T_RE_Facility, sum(MemCount) as MemCount, sum(MemCountPaid) as MemCountPaid from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and CID = '".$GET_CID."' and Month >= $startdate and Month <= $enddate group by Month order by Month DESC");
  }

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Data.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {
    echo $row[month];
    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "100"><?= $row[Month] ?></td>
      <td  nowrap align = "right"><?= $row[GS_NoTrans] ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_CashIncurred],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_CashPaid],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_Buy],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_Sell],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_Facility],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[T_GS_Facility],2) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($row[RE_CashPaid],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[RE_Facility],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[T_RE_Facility],2) ?></td>
      <td  nowrap align = "right"><?= $row[MemCount] ?></td>
      <td  nowrap align = "right"><?= $row[MemCountPaid] ?></td>
    </tr>
<?
    $gsnt+=$row[GS_NoTrans];
    $gscit+=$row[GS_CashIncurred];
    $gscpt+=$row[GS_CashPaid];
    $gsbt+=$row[GS_Buy];
    $gsst+=$row[GS_Sell];
    $gsft+=$row[GS_Facility];
    $tgsft+=$row[T_GS_Facility];
    $recit+=$row[RE_CashIncurred];
    $recpt+=$row[RE_CashPaid];
    $reft+=$row[RE_Facility];
    $treft+=$row[T_RE_Facility];
    $mt+=$row[MemCount];
    $mpt+=$row[MemCountPaid];

 	$foo++;
 	}
   }
?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= $gsnt ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscpt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsbt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsst,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($tgsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recpt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($reft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($treft,2) ?></td>
      <td  nowrap align = "right"><B><?= $mt ?></td>
      <td  nowrap align = "right"><B><?= $mpt ?></b></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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

 $thismonth=date("n")-1;
 $thisyear=date("Y");
?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Activity Report by Area</td>
   </tr>
   <?
   if($_SESSION['User']['CID'] == 1)  {
   ?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$GET_CID,'','onChange="ChangeCountry(this);"');
          ?>
      </td>
    </tr>
    <?}?>
    <tr>
      <td height="30" align="right" class="Heading2" width="150"><b>Agent Area:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="area"><option selected value="">All Areas</option>
       <?
		$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($GET_CID)."' and `drop` = 'Y' order by place ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['areaid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['place'] ?></option>
			<?
		}
       ?>
	  </select>&nbsp;</td>
   </tr>
   <tr>
      <td height="30" align="right" class="Heading2" width="150"><b>State:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="state"><option selected value="">All States</option>
		<?
		$dbgetarea=dbRead("select state from area where CID like '".addslashes($GET_CID)."' and state != '' group by state ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['state'] == $_REQUEST['state']) { echo "selected "; } ?>value="<?= $row['state'] ?>"><?= $row['state'] ?></option>
			<?
		}
		?>
	  </select>&nbsp;</td>
   </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option selected value="<?= $thismonth ?>">Last Month</option>
				<option>---------</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Months Prior:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_month_array();
            form_select('pm',$query,'','','','None');

           ?>
		</td>
	</tr>
	<tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}

}

function l_area() {

 global $time_start, $GET_CID;

 $colspan = "14";
 $startdate = date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-$_REQUEST['pm'],1,$_REQUEST['currentyear']));
 $enddate = date("Ym", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

if($_REQUEST[search]) {

 if($_REQUEST['area'])  {
  $queryarea = dbRead("select * from area where FieldID = '".$_REQUEST['area']."'");
  $rowarea = mysql_fetch_array($queryarea);
  $placename = $rowarea[place];
 } else {
  $placename = $GET_CID;
 }
?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Monthly
     Member Distribution Report for <?= $placename ?> between <?= $startdate ?> and <?= $enddate ?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "100"><b>Month:</b></td>
     <td class="Heading2" align = "right"><b>Listed:</b></td>
     <td class="Heading2" align = "right"><b>Unlisted:</b></td>
     <td class="Heading2" align = "right"><b>% of Unlisted:</b></td>
     <td class="Heading2" align = "right"><b>Deactive:</b></td>
     <td class="Heading2" align = "right"><b>Temp Unlisted:</b></td>
     <td class="Heading2" align = "right"><b>Broker Driven:</b></td>
     <td class="Heading2" align = "right"><b>Sponsorship:</b></td>
     <td class="Heading2" align = "right"><b>Suspended</b></td>
     <td class="Heading2" align = "right"><b>Suspended/ Locked:</b></td>
   </tr>
<?

  $foo = 0;

  if ($_REQUEST[area])  {
    $query = dbRead("select * from tbl_stats_listed where AreaID = '".$_REQUEST[area]."' and Month >= $startdate and Month <= $enddate order by Month DESC");
  } else {
    $query = dbRead("select Month, sum(Listed) as Listed, sum(Unlisted) as Unlisted, sum(Deactive) as Deactive, sum(Temp_Unlisted) as Temp_Unlisted, sum(Broker_Driven) as Broker_Driven, sum(Sponsorship) as Sponsorship, sum(Suspended) as Suspended, sum(Suspended_Locked) as Suspended_Locked from tbl_stats_listed, area where (tbl_stats_listed.AreaID = area.FieldID) and CID = '".$GET_CID."' and Month >= $startdate and Month <= $enddate group by Month order by Month DESC");
  }

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Data.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {
    echo $row[month];
    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;
    $per = (($row[Unlisted]/($row[Listed]+$row[Unlisted]))*100);
    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "100"><?= $row[Month] ?></td>
      <td  nowrap align = "right"><?= $row[Listed] ?></td>
      <td  nowrap align = "right"><?= $row[Unlisted] ?></td>
      <td  nowrap align = "right"><?= number_format($per,2) ?></td>
      <td  nowrap align = "right"><?= $row[Deactive] ?></td>
      <td  nowrap align = "right"><?= $row[Temp_Unlisted] ?></td>
      <td  nowrap align = "right"><?= $row[Broker_Driven] ?></td>
      <td  nowrap align = "right"><?= $row[Sponsorship] ?></td>
      <td  nowrap align = "right"><?= $row[Suspended] ?></td>
      <td  nowrap align = "right"><?= $row[Suspended_Locked] ?></td>
    </tr>
<?
    $gsnt+=$row[Listed];
    $gscit+=$row[Unlisted];
    $gscpt+=number_format($per,2);
    $gsbt+=$row[Deactive];
    $gsst+=$row[Temp_Unlisted];
    $gsft+=$row[Broker_Driven];
    $tgsft+=$row[Sponsorship];
    $recit+=$row[Suspended];
    $recpt+=$row[Suspended_Locked];

    $reft+=$row[RE_Facility];
    $treft+=$row[T_RE_Facility];
    $mt+=$row[MemCount];
    $mpt+=$row[MemCountPaid];

 	$foo++;
 	}
   }
?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= $gsnt ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscpt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsbt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsst,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($tgsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recpt,2) ?></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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

 $thismonth=date("n")-1;
 $thisyear=date("Y");
?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Member Distribution Report By Area</td>
   </tr>
   <?
   if($_SESSION['User']['CID'] == 1)  {
   ?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$GET_CID,'','onChange="ChangeCountry(this);"');
          ?>
      </td>
    </tr>
    <?}?>
    <tr>
      <td height="30" align="right" class="Heading2" width="150"><b>Agent Area:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="area"><option selected value="">All Areas</option>
       <?
		$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($GET_CID)."' order by place ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['areaid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['place'] ?></option>
			<?
		}
       ?>
	  </select>&nbsp;</td>
   </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option selected value="<?= $thismonth ?>">Last Month</option>
				<option>---------</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Months Prior:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_month_array();
            form_select('pm',$query,'','','','None');

           ?>
		</td>
	</tr>
	<tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}

}

function l_month() {

 global $time_start;

 $colspan = "14";

if($_REQUEST[search]) {

 $date2 = "".$_REQUEST['currentyear']."".$_REQUEST['currentmonth']."";

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Area Member Distribution Report for  <?= $date2 ?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "100"><b>Licensee:</b></td>
     <td class="Heading2" align = "right"><b>Listed:</b></td>
     <td class="Heading2" align = "right"><b>Unlisted:</b></td>
     <td class="Heading2" align = "right"><b>% of Unlisted:</b></td>
     <td class="Heading2" align = "right"><b>Deactive:</b></td>
     <td class="Heading2" align = "right"><b>Temp Unlisted:</b></td>
     <td class="Heading2" align = "right"><b>Broker Driven:</b></td>
     <td class="Heading2" align = "right"><b>Sponsorship:</b></td>
     <td class="Heading2" align = "right"><b>Suspended</b></td>
     <td class="Heading2" align = "right"><b>Suspended/ Locked:</b></td>
   </tr>
<?

  $foo = 0;
  if($_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    $query = dbRead("select * from tbl_stats_listed, area where (tbl_stats_listed.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' order by place ASC");
  } elseif(!$_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    $query = dbRead("select * from tbl_stats_listed, area where (tbl_stats_listed.AreaID = area.FieldID) and Month = $date2 order by place ASC");
  } else {
    $query = dbRead("select * from tbl_stats_listed, area where (tbl_stats_listed.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_SESSION['User']['CID']."' order by place ASC");
  }

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Area Data.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;
    $per = (($row[Unlisted]/($row[Listed]+$row[Unlisted]))*100);
    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "100"><?= $row[place] ?></td>
      <td  nowrap align = "right"><?= $row[Listed] ?></td>
      <td  nowrap align = "right"><?= $row[Unlisted] ?></td>
      <td  nowrap align = "right"><?= number_format($per,2) ?></td>
      <td  nowrap align = "right"><?= $row[Deactive] ?></td>
      <td  nowrap align = "right"><?= $row[Temp_Unlisted] ?></td>
      <td  nowrap align = "right"><?= $row[Broker_Driven] ?></td>
      <td  nowrap align = "right"><?= $row[Sponsorship] ?></td>
      <td  nowrap align = "right"><?= $row[Suspended] ?></td>
      <td  nowrap align = "right"><?= $row[Suspended_Locked] ?></td>
    </tr>
<?
    $gsnt+=$row[Listed];
    $gscit+=$row[Unlisted];
    $gscpt+=number_format($per,2);
    $gsbt+=$row[Deactive];
    $gsst+=$row[Temp_Unlisted];
    $gsft+=$row[Broker_Driven] ;
    $tgsft+=$row[Sponsorship];
    $recit+=$row[Suspended];
    $recpt+=$row[Suspended_Locked];

    $reft+=$row[RE_Facility];
    $treft+=$row[T_RE_Facility];
    $mt+=$row[MemCount];
    $mpt+=$row[MemCountPaid];

 	$foo++;
 	}
   }
?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= $gsnt ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscpt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsbt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsst,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($tgsft,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($recpt,2) ?></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Member Distribution Report By Month</td>
   </tr>
   <?
   if($_SESSION['User']['CID'] == 1)  {
   ?>
   <tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID'],'All Countries');
          ?>
      </td>
    </tr>
    <?}?>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ((date("m")-1) == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ((date("m")-1) == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ((date("m")-1) == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ((date("m")-1) == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ((date("m")-1) == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ((date("m")-1) == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ((date("m")-1) == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ((date("m")-1) == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ((date("m")-1) == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ((date("m")-1) == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ((date("m")-1) == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ((date("m")-1) == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
   <tr>
	<tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}

}

function staff_comm() {

 global $time_start;

 $colspan = "8";

if($_REQUEST[search]) {

 $date2 = "".$_REQUEST['currentyear']."".$_REQUEST['currentmonth']."";

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Monthly
		Staff Commission Report for <?= $date2 ?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "100"><b>Agent:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Comm:</b></td>
   </tr>
<?

  $foo = 0;
  if($_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    //$query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' order by state, place ASC");
    $query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' and `drop` = 'Y' order by user, place ASC");

  } elseif(!$_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    //$query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 group by CID order by place ASC");
    $query = dbRead("select name as place, sum(GS_NoTrans) as GS_NoTrans, sum(GS_CashPaid) as GS_CashPaid, sum(RE_CashPaid) as RE_CashPaid, sum(GS_Buy) as GS_Buy, sum(GS_Sell) as GS_Sell, sum(GS_CashIncurred) as GS_CashIncurred, sum(RE_CashIncurred) as RE_CashIncurred, sum(GS_Facility) as GS_Facility, sum(T_GS_Facility) as T_GS_Facility, sum(RE_Facility) as RE_Facility, sum(T_RE_Facility) as T_RE_Facility, sum(MemCount) as MemCount, sum(MemCountPaid) as MemCountPaid from tbl_miscstats, area, country where (tbl_miscstats.AreaID = area.FieldID) and (area.CID = country.countryID) and Month = '".$date2."' and country.Display = 'Yes' group by name order by name");
  } else {
    $query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_SESSION['User']['CID']."' order by place ASC");
  }


  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Area Data.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   $aa = 0;

   while($row = mysql_fetch_array($query)) {

 	if($row[user] != $aa && !$aa = 0) {
 	?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= number_format($gsci2,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsp,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsc,2) ?></td>
    </tr>
	<?
	$gsci = 0;
	$gsci2 = 0;
	$gsp = 0;
	$gsc = 0;

	}

    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

    $date3 = date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-1,date("d"),$_REQUEST['currentyear']));
    $fromdate2 = date("Y-m-d", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));
    $todate2 = date("Y-m-d", mktime(0,0,0,$_REQUEST['currentmonth']+1,1-1,$_REQUEST['currentyear']));

    $query2 = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date3 and area.CID = '".$_REQUEST['countryID']."' and AreaID = ".$row['AreaID']." order by state, place ASC");
	$row2 = mysql_fetch_array($query2);

    //$dbquery = dbRead("SELECT sum((feespaid.amountpaid-feespaid.deducted_fees)/2) as value4, sum(feespaid.deducted_fees) as value5 FROM feespaid WHERE (feespaid.area = ".$row['AreaID'].") and feespaid.type not in (6,7,8,9) AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2') and percent not in (50) group by area");
    //$dbquery = dbRead("SELECT sum((feespaid.amountpaid-feespaid.deducted_fees)/2) as value4, sum(feespaid.deducted_fees) as value5 FROM feespaid WHERE feespaid.area = ".$row['AreaID']." and feespaid.type not in (6,7,8,9) and percent > 0 AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2') group by area");
    $dbquery = dbRead("SELECT sum((feespaid.amountpaid-feespaid.deducted_fees)/2) as value4, sum(feespaid.deducted_fees) as value5, members.licensee FROM feespaid, members WHERE (feespaid.memid = members.memid) and feespaid.area = ".$row['AreaID']." and feespaid.type in (1,4,5) and percent > 0 AND (feespaid.paymentdate BETWEEN '$fromdate2' AND '$todate2') and feespaid.area = members.licensee group by feespaid.area");

	$row3 = mysql_fetch_array($dbquery);

	$com = ($row3[value4]/100)*5;
    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "100"><?= $row[place] ?></td>
      <td  nowrap align = "right"><?= number_format($row2[GS_CashIncurred],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[GS_CashIncurred],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row3[value4],2) ?></td>
      <td  nowrap align = "right"><?= number_format($com,2) ?></td>
    </tr>
<?
    $gsci+=$row[GS_CashIncurred];
    $gsci2+=$row2[GS_CashIncurred];
    $gsp+=$row3[value4];
    $gsc+=$com;
    $gscit+=$row[GS_CashIncurred];
    $gscit2+=$row2[GS_CashIncurred];
    $gspt+=$row3[value4];
    $gsct+=$com;

    $gsbt+=$row[GS_Buy];
    $gsst+=$row[GS_Sell];
    $gsft+=$row[GS_Facility];
    $tgsft+=$row[T_GS_Facility];
    $recit+=$row[RE_CashIncurred];
    $recpt+=$row[RE_CashPaid];
    $reft+=$row[RE_Facility];
    $treft+=$row[T_RE_Facility];
    $mt+=$row[MemCount];
    $mpt+=$row[MemCountPaid];

 	$foo++;
	$aa = $row[user];
 	}
   }
?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= number_format($gsci2,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsp,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsc,2) ?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>GRAND TOTALS:</td>
      <td  nowrap align = "right"><B><?= number_format($gscit2,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gspt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsct,2) ?></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Activity Report by Month</td>
   </tr>
   <?
   if($_SESSION['User']['CID'] == 1)  {
   ?>
   <tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID'],'All Countries');
          ?>
      </td>
    </tr>
    <?}?>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ((date("m")-1) == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ((date("m")-1) == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ((date("m")-1) == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ((date("m")-1) == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ((date("m")-1) == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ((date("m")-1) == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ((date("m")-1) == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ((date("m")-1) == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ((date("m")-1) == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ((date("m")-1) == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ((date("m")-1) == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ((date("m")-1) == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
   <tr>
	<tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}

}

function staff_comm2() {

 global $time_start;

 $colspan = "10";

if($_REQUEST[search]) {

 $date3 = date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-4,date("d"),$_REQUEST['currentyear']));
 $date2 = "".$_REQUEST['currentyear']."".$_REQUEST['currentmonth']."";
 $date4 = date("Y-m", mktime(0,0,0,$_REQUEST['currentmonth'],date("d"),$_REQUEST['currentyear']));
?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Monthly
		Staff Commission Report for <?= $date2 ?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "100"><b>Agent:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/Fees Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Average:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Current Incurred:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Share:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Comm:</b></td>
   </tr>
<?

  $foo = 0;
  if($_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    //$query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' order by state, place ASC");
    //$query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' and `drop` = 'Y' order by user, place ASC");
    $query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month between $date3 and $date2 and area.CID = '".$_REQUEST['countryID']."' and `drop` = 'Y' order by user, place ASC");

  } elseif(!$_REQUEST['countryID'] && $_SESSION['User']['CID'] == 1)  {
    //$query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 group by CID order by place ASC");
    $query = dbRead("select name as place, sum(GS_NoTrans) as GS_NoTrans, sum(GS_CashPaid) as GS_CashPaid, sum(RE_CashPaid) as RE_CashPaid, sum(GS_Buy) as GS_Buy, sum(GS_Sell) as GS_Sell, sum(GS_CashIncurred) as GS_CashIncurred, sum(RE_CashIncurred) as RE_CashIncurred, sum(GS_Facility) as GS_Facility, sum(T_GS_Facility) as T_GS_Facility, sum(RE_Facility) as RE_Facility, sum(T_RE_Facility) as T_RE_Facility, sum(MemCount) as MemCount, sum(MemCountPaid) as MemCountPaid from tbl_miscstats, area, country where (tbl_miscstats.AreaID = area.FieldID) and (area.CID = country.countryID) and Month = '".$date2."' and country.Display = 'Yes' group by name order by name");
  } else {
    $query = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_SESSION['User']['CID']."' order by place ASC");
  }


  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Area Data.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $aa = 0;


   $YearMonth = $date2;
   $YearArray[$YearMonth] = array();

   $SQLQuery = dbRead("select * from tbl_miscstats, area where (tbl_miscstats.AreaID = area.FieldID) and Month = $date2 and area.CID = '".$_REQUEST['countryID']."' and `drop` = 'Y' order by user, place ASC");
   while($Rows = mysql_fetch_assoc($SQLQuery)) {
    $YearArray[$YearMonth][$Rows['place']]["blank"] = 1;
    $YearArray[$YearMonth][$Rows['place']]['area'] = $Rows['AreaID'];
    $YearArray[$YearMonth][$Rows['place']]['user'] = $Rows['user'];
   }

   while($row = mysql_fetch_array($query)) {

    if($row['Month'] == date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-1,date("d"),$_REQUEST['currentyear']))) {
    	$YearArray[$YearMonth][$row['place']]['4'] = $row['GS_CashIncurred'];
	} elseif($row['Month'] == date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-2,date("d"),$_REQUEST['currentyear']))) {
    	$YearArray[$YearMonth][$row['place']]['3'] = $row['GS_CashIncurred'];
	} elseif($row['Month'] == date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-3,date("d"),$_REQUEST['currentyear']))) {
    	$YearArray[$YearMonth][$row['place']]['2'] = $row['GS_CashIncurred'];
	} elseif($row['Month'] == date("Ym", mktime(0,0,0,$_REQUEST['currentmonth']-4,date("d"),$_REQUEST['currentyear']))) {
    	$YearArray[$YearMonth][$row['place']]['1'] = $row['GS_CashIncurred'];
	} else {
    	$YearArray[$YearMonth][$row['place']]['GS_CashIncurred'] = $row['GS_CashIncurred'];
    	$YearArray[$YearMonth][$row['place']]['GS_CashPaid'] = $row['GS_CashPaid'];
	}

   }
//print_r($YearArray);
 foreach($YearArray as $Key => $Value) {

  foreach($Value as $Key2 => $Value2) {


 	if($Value2['user'] != $aa && !$aa = 0) {
 	?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= number_format($gsci,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci2,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci3,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci4,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsp,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsc,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gcpai,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gpai,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gcom,2) ?></td>
    </tr>
	<?
	$gsci = 0;
	$gsci2 = 0;
	$gsci3 = 0;
	$gsci4 = 0;
	$gsp = 0;
	$gsc = 0;
	$gcpai = 0;
	$gpai = 0;
	$gcom = 0;

	}

    $dbquery = dbRead("SELECT sum((feespaid.amountpaid-feespaid.deducted_fees)/2) as value4, sum(feespaid.deducted_fees) as value5, members.licensee FROM feespaid, members WHERE (feespaid.memid = members.memid) and feespaid.area = ".$Value2['area']." and feespaid.type in (1,4,5) and percent > 0 AND feespaid.paymentdate like '".$date4."-%' and feespaid.area = members.licensee group by feespaid.area");
	$row3 = mysql_fetch_array($dbquery);

    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

	$avg = ($Value2[1]+$Value2[2]+$Value2[3]+$Value2[4])/4;
	$com = ($row3['value4']/100)*5;
//print $com;
    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "100"><?= $Key2 ?></td>
      <td  nowrap align = "right"><?= number_format($Value2[1],2) ?></td>
      <td  nowrap align = "right"><?= number_format($Value2[2],2) ?></td>
      <td  nowrap align = "right"><?= number_format($Value2[3],2) ?></td>
      <td  nowrap align = "right"><?= number_format($Value2[4],2) ?></td>
      <td  nowrap align = "right"><?= number_format($avg,2) ?></td>
      <td  nowrap align = "right"><?= number_format($Value2['GS_CashIncurred'],2) ?></td>
      <td  nowrap align = "right"><?= number_format($Value2['GS_CashPaid'],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row3['value4'],2) ?></td>
      <td  nowrap align = "right"><?= number_format($com,2) ?></td>
    </tr>
<?
    $gsci+=$Value2[1];
    $gsci2+=$Value2[2];
    $gsci3+=$Value2[3];
    $gsci4+=$Value2[4];
    $gsp+=$avg;
    $gsc+=$Value2['GS_CashIncurred'];
    $gcpai+=$Value2['GS_CashPaid'];
    $gpai+=$row3['value4'];
    $gcom+=$com;

    $gscit+=$Value2[1];
    $gscit2+=$Value2[2];
    $gscit3+=$Value2[3];
    $gscit4+=$Value2[4];
    $gspt+=$avg;
    $gsct+=$Value2['GS_CashIncurred'];
    $gcpait+=$Value2['GS_CashPaid'];
    $gpait+=$row3['value4'];
    $gcomt+=$com;

 	$foo++;
	$aa = $Value2['user'];
 	}
 	}
   }
?>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>TOTALS:</td>
      <td  nowrap align = "right"><B><?= number_format($gsci,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci2,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci3,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsci4,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsp,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsc,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gcpai,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gpai,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gcom,2) ?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width = "100"><B>GRAND TOTALS:</td>
      <td  nowrap align = "right"><B><?= number_format($gscit,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit2,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit3,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gscit4,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gspt,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gsct,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gcpait,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gpait,2) ?></td>
      <td  nowrap align = "right"><B><?= number_format($gcomt,2) ?></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Activity Report by Month</td>
   </tr>
   <?
   if($_SESSION['User']['CID'] == 1)  {
   ?>
   <tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID'],'All Countries');
          ?>
      </td>
    </tr>
    <?}?>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ((date("m")-1) == "01") { echo "selected "; } ?>value="01">January</option>
				<option <? if ((date("m")-1) == "02") { echo "selected "; } ?>value="02">February</option>
				<option <? if ((date("m")-1) == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ((date("m")-1) == "04") { echo "selected "; } ?>value="04">April</option>
				<option <? if ((date("m")-1) == "05") { echo "selected "; } ?>value="05">May</option>
				<option <? if ((date("m")-1) == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ((date("m")-1) == "07") { echo "selected "; } ?>value="07">July</option>
				<option <? if ((date("m")-1) == "08") { echo "selected "; } ?>value="08">August</option>
				<option <? if ((date("m")-1) == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ((date("m")-1) == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ((date("m")-1) == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ((date("m")-1) == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
   <tr>
	<tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}

}
?>