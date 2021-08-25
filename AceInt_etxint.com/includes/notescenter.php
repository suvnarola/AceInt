<?

if(!checkmodule("Notes")) {

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

<form method="POST" action="body.php?page=notescenter&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?

// Some Setup.

 $tabarray = array(get_page_data("6"),"Search/".get_page_data("7"),"Track");
 if(checkmodule("LogReport")) { $tabarray[] = "Users Note"; }
 if(checkmodule("LogReport")) { $tabarray[] = "Contact Required"; }
 if(checkmodule("LogReport")) { $tabarray[] = "Target"; }
// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  todo();

} elseif($_GET[tab] == "tab2") {

  view();

} elseif($_GET[tab] == "tab3") {

  track();

} elseif($_GET[tab] == "tab4") {

  view2();

} elseif($_GET[tab] == "tab5") {

  view3();

} elseif($_GET[tab] == "tab6") {

  target();

}

?>

</form>

<?

function todo() {

if($_REQUEST[checkmembers]) {

$abc = $_REQUEST[id2];

$count = sizeof($abc);
$i = 0;
for ($i = 0; $i <= $count; $i++) {

	dbWrite("update notes set deleted=1 where FieldID='$abc[$i]'");

}

}

?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
</script>
</head>

<body>

<?

$time_start = getmicrotime();
$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

//$query6  = "select * from notes, members where (notes.memid=members.memid) and enteredby='".$_SESSION['Username']."' and deleted = '0' and reminder <= '$date' and reminder !='' order by date";
//$query6  = "select * from notes, members where (notes.memid=members.memid) and userid='".$_SESSION['User']['FieldID']."' and deleted = '0' and reminder <= '$date' and reminder > '2000-01-01' and reminder !='' order by date";
$query6  = "select *

	from notes
		inner
			join
				members
				on notes.memid=members.memid

	where
		userid='".$_SESSION['User']['FieldID']."' and deleted = '0' and reminder <= '$date' and reminder > '2000-01-01' and reminder !=''
	order by date";

$dbgetnoncheckedtrans = dbRead($query6);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="body.php?page=notescenter">

<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("2") ?>: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="5" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
		<td width="60" class="Heading2"><b><?= get_word("41") ?>:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="50" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="270" class="Heading2"><b><?= get_word("57") ?>:</b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>
<?

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF"><?= get_page_data("3") ?></font></td>
		</tr>
<?

} else {

$foo = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$dis_date = date("d/m/Y", $row[date]);

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="60"><?= $row[date] ?></td>
			<td width="200"><a href="body.php?page=member_edit&Client=<?= $row[memid] ?>&pagno=1&tab=tab5 " class="nav"><?= get_all_added_characters($row[companyname]) ?></a></td>
			<td width="50"><?= $row[memid] ?></td>
			<td width="270"><?= get_all_added_characters($row[note]) ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row[FieldID] ?>"></td>
		</tr>
<?

$foo++;

}

}

if(!$data) {
?>
		<tr bgcolor="#FFFFFF">
			<td width="60">&nbsp;</td>
			<td width="200">&nbsp;</td>
			<td width="50">&nbsp;</td>
			<td width="270" align="right">&nbsp;</td>
			<td width="30"></td>
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
	<tr>
		<td colspan="5" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="checkmembers"></td>
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

function view() {

	if($_REQUEST['currentmonth']) {
	  $dd = $_REQUEST['currentmonth'];
	} else {
	  $dd = date("m");
	}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>

<body>

<form method="post" action="body.php?page=notescenter&tab=View Past Notes" name="12345">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_word("57") ?></td>
  </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Data:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<input type="text" name="data" value="<?= $_REQUEST['data'] ?>" >
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($dd == "1") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($dd == "2") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($dd == "3") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($dd == "4") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($dd == "5") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($dd == "6") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($dd == "7") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($dd == "8") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($dd == "9") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($dd == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($dd == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($dd == "12") { echo "selected "; } ?>value="12">December</option>
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
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>" name="search">&nbsp;</td>
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

?>
<br>
<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("5") ?>: <?= $counter ?></td>
 </tr>
</table>

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="620" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" width="65" valign="bottom"><b><?= get_word("41") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="200" valign="bottom"><b><?= get_word("3") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="50" valign="bottom"><b><?= get_word("1") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="285" valign="bottom"><b><?= get_word("57") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="65" valign="bottom"><b>Reminder:</b>&nbsp;</td>
  </tr>
<?

// Get the transactions out.

$foo = 0;

$date1 = $_REQUEST[currentyear]."-".$_REQUEST[currentmonth];

if($date1) {
  $dd = " and date like '$date1-%'";
} else {
  $dd = "";
}

//$dbgettrans = dbRead("select notes.*, members.companyname as companyname from notes, members where (notes.memid=members.memid) and enteredby='".$_SESSION['Username']."' and date like '$date1-%' order by companyname, date");
$dbgettrans = dbRead("select notes.*, members.companyname as companyname from notes, members where (notes.memid=members.memid) and userid='".$_SESSION['User']['FieldID']."' and note like '%".$_REQUEST['data']."%' $dd order by date");

while($transrow = mysql_fetch_assoc($dbgettrans)) {

$dis_date = date("d/m/y", $transrow[date]);

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td width="65" valign="top" bgcolor="<?= $bgcolor ?>" height="19"><?= $transrow[date] ?></a>&nbsp;</td>
    <td width="200" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<a href="body.php?page=member_edit&Client=<?= $transrow[memid]?>&pagno=1&tab=tab5 " class="nav"><?= get_all_added_characters($transrow[companyname]) ?></a></td>
    <td width="50" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow[memid] ?></td>
    <td width="285" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= get_all_added_characters($transrow[note]) ?></td>
    <td width="65" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= get_all_added_characters($transrow['reminder']) ?></td>
  </tr>

<?
$statement_fees += $transrow[dollarfees];
$foo++;
}
?>

  <tr>
    <td class="Heading2" width="65" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="200" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="50" align="right" valign="top" height="19"><b>&nbsp;</td>
    <td class="Heading2" width="285" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="65" align="right" valign="top" height="19">&nbsp;</td>
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

function track() {

	if($_REQUEST[checkmembers]) {

		$abc = $_REQUEST[id2];

		$count = sizeof($abc);
		$i = 0;
		for ($i = 0; $i <= $count; $i++) {

			dbWrite("update notes set deleted=1 where FieldID='$abc[$i]'");

		}

	}

	?>

	<html>
	<head>
	<title>Untitled Document</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
	<SCRIPT language=JavaScript>
	</script>
	</head>

	<body>

	<?

	$time_start = getmicrotime();
	$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

	$query6  = "select * from notes, members where (notes.memid=members.memid) and userid='".$_SESSION['User']['FieldID']."' and deleted = '0' and responseid > 0 order by date";
	$dbgetnoncheckedtrans = dbRead($query6);

	$counter = mysql_num_rows($dbgetnoncheckedtrans);

	?>
	<br>
	<form name="checktrans" method="post" action="body.php?page=notescenter">

	<table width="620" callspacing="0" cellpadding="3">
	 <tr>
	  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("2") ?>: <?= $counter ?></td>
	 </tr>
	</table>

	<table width="610" border="0" cellpadding="1" cellspacing="0">
	<tr>
	<td class="Border">
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td colspan="5" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
		</tr>
		<tr>
			<td width="60" class="Heading2"><b><?= get_word("41") ?>:</b></font></td>
			<td width="50" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
			<td width="120" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
			<td width="300" class="Heading2"><b><?= get_word("57") ?>:</b></font></td>
			<td width="30" class="Heading2">&nbsp;</td>
		</tr>
	<?

	if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

	?>
			<tr>
				<td colspan="6" bgcolor="#FFFFFF"><?= get_page_data("3") ?></font></td>
			</tr>
	<?

	} else {

	$foo = 0;

	while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

		$dis_date = date("d/m/Y", $row[date]);

		$cfgbgcolorone = "#CCCCCC";
		$cfgbgcolortwo = "#EEEEEE";
		$bgcolor = $cfgbgcolorone;
		$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

	?>
			<tr bgcolor="<?= $bgcolor ?>">
				<td width="60"><?= $row[date] ?></td>
				<td width="50"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
				<td width="120"><a href="body.php?page=member_edit&Client=<?= $row[memid] ?>&pagno=1&tab=tab5 " class="nav"><?= get_all_added_characters($row[companyname]) ?></a></td>
				<td width="270"><?= get_all_added_characters($row[note]) ?></td>
				<td width="30"><input type="checkbox" name="id2[]" value="<?= $row[FieldID] ?>"></td>
			</tr>
	<?
		if($row['responseid'] > 1) {

			$query8  = dbRead("select * from notes where FieldID = ".$row['responseid']." and deleted = '0' order by date");
			$row8 = mysql_fetch_assoc($query8);
		?>
			<tr bgcolor="<?= $bgcolor ?>">
				<td width="60"><?= $row8[date] ?></td>
				<td width="50"></td>
				<td width="120"></td>
				<td width="270"><?= $row8[note] ?></td>
				<td width="30"><input type="checkbox" name="id2[]" value="<?= $row8[FieldID] ?>"></td>
			</tr>
		<?
		} else {
		?>
			<tr bgcolor="<?= $bgcolor ?>">
				<td width="60"></td>
				<td width="50"></td>
				<td width="120"></td>
				<td width="270"><b>NO AGENT RESPONSE / ACTION </b></td>
				<td width="30"></td>
			</tr>
		<?
		}

	$foo++;

	}

	}

	if(!$data) {
	?>
			<tr bgcolor="#FFFFFF">
				<td width="60">&nbsp;</td>
				<td width="50">&nbsp;</td>
				<td width="120">&nbsp;</td>
				<td width="270" align="right">&nbsp;</td>
				<td width="30"></td>
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
		<tr>
			<td colspan="5" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="checkmembers"></td>
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

function view2() {

	if($_REQUEST['currentmonth']) {
	  $dd = $_REQUEST['currentmonth'];
	} else {
	  $dd = date("m");
	}

	if($_REQUEST['currentday']) {
	  $dd2 = $_REQUEST['currentday'];
	} else {
	  $dd2 = date("d");
	}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>

<body>

<form method="post" action="body.php?page=notescenter&tab=User Notes" name="12345">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_word("57") ?></td>
  </tr>
   <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("34") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		 <select name="user">
		  <?

		  $dbgetarea=dbRead("select tbl_admin_users.FieldID, Name from tbl_admin_users, members, area where tbl_admin_users.FieldID = area.user and area.FieldID = members.licensee and tbl_admin_users.CID = ".$_SESSION['User']['CID']." and emcus = '1' and Suspended = 0 and Name not like '' group by Name order by Name ASC");
		  while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
			<option <? if ($row['FieldID'] == $_REQUEST['user']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?></option>
		  <?
		  }
		  ?>
			<option value="1086">Mick</option>
			<option value="289">Corrie</option>
	  	 </select>
		</td>
   </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Day:</b></td>
		<td width="450" bgcolor="#FFFFFF">
	      <select name="currentday">
	        <option <? if ($dd2 == "01") { echo "selected "; } ?>value="01">1</option>
	        <option <? if ($dd2 == "02") { echo "selected "; } ?>value="02">2</option>
	        <option <? if ($dd2 == "03") { echo "selected "; } ?>value="03">3</option>
	        <option <? if ($dd2 == "04") { echo "selected "; } ?>value="04">4</option>
	        <option <? if ($dd2 == "05") { echo "selected "; } ?>value="05">5</option>
	        <option <? if ($dd2 == "06") { echo "selected "; } ?>value="06">6</option>
	        <option <? if ($dd2 == "07") { echo "selected "; } ?>value="07">7</option>
	        <option <? if ($dd2 == "08") { echo "selected "; } ?>value="08">8</option>
	        <option <? if ($dd2 == "09") { echo "selected "; } ?>value="09">9</option>
	        <option <? if ($dd2 == "10") { echo "selected "; } ?>value="10">10</option>
	        <option <? if ($dd2 == "11") { echo "selected "; } ?>value="11">11</option>
	        <option <? if ($dd2 == "12") { echo "selected "; } ?>value="12">12</option>
	        <option <? if ($dd2 == "13") { echo "selected "; } ?>value="13">13</option>
	        <option <? if ($dd2 == "14") { echo "selected "; } ?>value="14">14</option>
	        <option <? if ($dd2 == "15") { echo "selected "; } ?>value="15">15</option>
	        <option <? if ($dd2 == "16") { echo "selected "; } ?>value="16">16</option>
	        <option <? if ($dd2 == "17") { echo "selected "; } ?>value="17">17</option>
	        <option <? if ($dd2 == "18") { echo "selected "; } ?>value="18">18</option>
	        <option <? if ($dd2 == "19") { echo "selected "; } ?>value="19">19</option>
	        <option <? if ($dd2 == "20") { echo "selected "; } ?>value="20">20</option>
	        <option <? if ($dd2 == "21") { echo "selected "; } ?>value="21">21</option>
	        <option <? if ($dd2 == "22") { echo "selected "; } ?>value="22">22</option>
	        <option <? if ($dd2 == "23") { echo "selected "; } ?>value="23">23</option>
	        <option <? if ($dd2 == "24") { echo "selected "; } ?>value="24">24</option>
	        <option <? if ($dd2 == "25") { echo "selected "; } ?>value="25">25</option>
	        <option <? if ($dd2 == "26") { echo "selected "; } ?>value="26">26</option>
	        <option <? if ($dd2 == "27") { echo "selected "; } ?>value="27">27</option>
	        <option <? if ($dd2 == "28") { echo "selected "; } ?>value="28">28</option>
	        <option <? if ($dd2 == "29") { echo "selected "; } ?>value="29">29</option>
	        <option <? if ($dd2 == "30") { echo "selected "; } ?>value="30">30</option>
	        <option <? if ($dd2 == "31") { echo "selected "; } ?>value="31">31</option>
	      </select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($dd == "1") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($dd == "2") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($dd == "3") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($dd == "4") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($dd == "5") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($dd == "6") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($dd == "7") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($dd == "8") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($dd == "9") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($dd == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($dd == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($dd == "12") { echo "selected "; } ?>value="12">December</option>
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
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>" name="search">&nbsp;</td>
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

$foo = 0;

$date1 = $_REQUEST[currentyear]."-".$_REQUEST[currentmonth]."-".$_REQUEST[currentday];

if($date1) {
  $dd = " and date like '$date1%'";
} else {
  $dd = "";
}

 //$dbgettrans = dbRead("select notes.*, members.companyname as companyname from notes, members where (notes.memid=members.memid) and enteredby='".$_SESSION['Username']."' and date like '$date1-%' order by companyname, date");
 $dbgettrans = dbRead("select notes.*, members.companyname as companyname, members.priority as priority, members.date_per as date_per from notes, members where (notes.memid=members.memid) and userid='".$_REQUEST['user']."' $dd order by date");

 $dbgettrans2 = dbRead("select count(notes.memid) as note from notes, members where (notes.memid=members.memid) and userid='".$_REQUEST['user']."' $dd group by notes.memid");
 $counter = mysql_num_rows($dbgettrans2);
?>
<br>
<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Number of Members Notes Entered: <?= $counter ?></td>
 </tr>
</table>

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="620" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" width="65" valign="bottom"><b><?= get_word("41") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="150" valign="bottom"><b><?= get_word("3") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="50" valign="bottom"><b><?= get_word("1") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="285" valign="bottom"><b><?= get_word("57") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="70" valign="bottom"><b>% Date:</b>&nbsp;</td>
    <td class="Heading2" width="10" valign="bottom"><b>Pr:</b>&nbsp;</td>
  </tr>
<?

// Get the transactions out.
$dealT=0;
$dealC=0;

while($transrow = mysql_fetch_assoc($dbgettrans)) {

$dis_date = date("d/m/y", $transrow[date]);

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td width="65" valign="top" bgcolor="<?= $bgcolor ?>" height="19"><?= $transrow[date] ?></a>&nbsp;</td>
    <td width="150" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<a href="body.php?page=member_edit&Client=<?= $transrow[memid]?>&pagno=1&tab=tab5" class="nav" target="_blank"><?= get_all_added_characters($transrow[companyname]) ?></a></td>
    <td width="50" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow[memid] ?></td>
    <td width="285" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= get_all_added_characters($transrow[note]) ?></td>
    <td width="70" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= get_all_added_characters($transrow['date_per']) ?></td>
    <td width="10" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow['priority'] ?></td>
  </tr>

<?
$statement_fees += $transrow[dollarfees];
$foo++;

 $Dealquery = dbRead("select * from deals where NoteID = ".$transrow['FieldID']."");
 $Dealrow = mysql_fetch_assoc($Dealquery);

 if($Dealrow) {
?>

  <tr <?= $bb ?> bgcolor="<?= $bgcolor ?>">
   <td colspan="2" ></td>
   <td width="50"><?= $Dealrow['AuthNo'] ?></td>
   <td width="285"><font color="#2400FF"><b>DEAL:</b></font> <?= $Dealrow['Details'] ?></td>
   <td width="70" colspan="2" align="right"><?= number_format($Dealrow['Amount'], 2) ?></td>
  </tr>
<?
$dealT+= $Dealrow['Amount'];
$dealC++;
 }
}
?>

  <tr>
    <td class="Heading2" width="65" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="150" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="50" align="right" valign="top" height="19"><b>&nbsp;</td>
    <td class="Heading2" width="285" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="70" align="right" valign="top" height="19">T$<?= number_format($dealT, 2)?></td>
    <td class="Heading2" width="10" align="right" valign="top" height="19"><?= get_all_added_characters($dealC) ?></td>
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

function view3() {

	if($_REQUEST['currentmonth']) {
	  $dd = $_REQUEST['currentmonth'];
	} else {
	  $dd = date("m");
	}

	if($_REQUEST['currentday']) {
	  $dd2 = $_REQUEST['currentday'];
	} else {
	  $dd2 = date("d");
	}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>

<body>

<form method="post" action="body.php?page=notescenter&tab=User Notes" name="12345">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_word("57") ?></td>
  </tr>
   <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("34") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		 <select name="user">
		  <?

		  //$dbgetarea=dbRead("select FieldID, Name from tbl_admin_users where CID = ".$_SESSION['User']['CID']." and Area = 1 and emcus = '1' and Suspended = 0 and Name not like '' order by Name ASC");
		  $dbgetarea=dbRead("select tbl_admin_users.FieldID, Name from tbl_admin_users, members, area where tbl_admin_users.FieldID = area.user and area.FieldID = members.licensee and tbl_admin_users.CID = ".$_SESSION['User']['CID']." and emcus = '1' and Suspended = 0 and Name not like '' group by Name order by Name ASC");
		  while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
			<option <? if ($row['FieldID'] == $_REQUEST['user']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?></option>
		  <?
		  }
		  ?>
	  	 </select>
		</td>
   </tr>
   <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>" name="search">&nbsp;</td>
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

$foo = 0;

$date1 = $_REQUEST[currentyear]."-".$_REQUEST[currentmonth]."-".$_REQUEST[currentday];

if($date1) {
  $dd = " and date like '$date1%'";
} else {
  $dd = "";
}

 //$dbgettrans = dbRead("select notes.*, members.companyname as companyname from notes, members where (notes.memid=members.memid) and enteredby='".$_SESSION['Username']."' and date like '$date1-%' order by companyname, date");
 //$dbgettrans = dbRead("select notes.*, members.companyname as companyname, members.priority as priority, members.date_per as date_per from notes, members where (notes.memid=members.memid) and userid='".$_REQUEST['user']."' $dd order by date");
  $dbgettrans = dbRead("select *
  from members
	inner
		join
			area on area.FieldID=members.licensee
  where
	members.priority > 0 and area.user ='".$_REQUEST['user']."' and status in (0,4,5) and uncon = 'N'
  order by fiftyclub, companyname");

?>
<br>
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="620" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" width="65" valign="bottom"><b><?= get_word("1") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="150" valign="bottom"><b><?= get_word("3") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="50" valign="bottom"><b><?= get_word("41") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="285" valign="bottom"><b><?= get_word("57") ?>:</b>&nbsp;</td>
    <td class="Heading2" width="70" valign="bottom"><b>% Date:</b>&nbsp;</td>
    <td class="Heading2" width="10" valign="bottom"><b>Pr:</b>&nbsp;</td>
  </tr>
<?

// Get the transactions out.
$counter = 0;

while($transrow = mysql_fetch_assoc($dbgettrans)) {

  $date4 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
  $days = $transrow['priority']*7;
  $newdate = explode("-", $transrow['date_per']);
  $date3 = date("Y-m-d", mktime(0,0,0,$newdate[1],$newdate[2]+$days,$newdate[0]));

if($date4 > $date3) {

$counter++;

$dis_date = date("d/m/y", $transrow[date]);

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td width="65" valign="top" bgcolor="<?= $bgcolor ?>" height="19"><?= $transrow[memid] ?></a>&nbsp;</td>
    <td width="150" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<a href="body.php?page=member_edit&Client=<?= $transrow[memid]?>&pagno=1&tab=tab5" class="nav" target="_blank"><?= get_all_added_characters($transrow[companyname]) ?></a></td>
    <td width="50" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow[date] ?></td>
    <td width="285" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= get_all_added_characters($transrow[note]) ?></td>
    <td width="70" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow['date_per'] ?></td>
    <td width="10" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow['priority'] ?></td>
  </tr>

<?
$statement_fees += $transrow[dollarfees];
$foo++;
}
}
?>

  <tr>
    <td class="Heading2" width="65" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="150" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="50" align="right" valign="top" height="19"><b>&nbsp;</td>
    <td class="Heading2" width="285" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="70" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="10" align="right" valign="top" height="19">&nbsp;</td>
  </tr>
</table>

</td>
</tr>
</table>
<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><?= get_page_data("5") ?>: <?= $counter ?></td>
 </tr>
</table>
</body>
</html>

<?
}

}

function target () {

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>

<body>

<form method="post" action="body.php?page=notescenter&tab=User Notes" name="12345">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading">Targets</td>
  </tr>
   <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("34") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		 <select name="user">
		   <option <? if ($row['FieldID'] == $_REQUEST['user']) { echo "selected "; } ?>value="">Select</option>

		  <?

		  $dbgetarea=dbRead("select tbl_admin_users.FieldID, Name from tbl_admin_users, members, area where tbl_admin_users.FieldID = area.user and area.FieldID = members.licensee and tbl_admin_users.CID = ".$_SESSION['User']['CID']." and emcus = '1' and Suspended = 0 and Name not like '' group by Name order by Name ASC");
		  while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
			<option <? if ($row['FieldID'] == $_REQUEST['user']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?></option>
		  <?
		  }
		  ?>
	  	 </select>
		</td>
   </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b></b></td>
		<td width="450" bgcolor="#FFFFFF"><b>OR</b></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="type">
				<option <? if ($_REQUEST['type'] == "01") { echo "selected "; } ?>value="01">This Week</option>
				<option <? if ($_REQUEST['type'] == "02") { echo "selected "; } ?>value="02">Last Week</option>
				<option <? if ($_REQUEST['type'] == "03") { echo "selected "; } ?>value="03">This Month</option>
				<option <? if ($_REQUEST['type'] == "04") { echo "selected "; } ?>value="04">Last Month</option>
				<option <? if ($_REQUEST['type'] == "05") { echo "selected "; } ?>value="05">Year To Date</option>
			</select>
		</td>
	</tr>
   <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>" name="search">&nbsp;</td>
  </tr>

</table>
</td>
</tr>
</table>

<input type="hidden" name="search" value="1">

</form>

<?
if($_REQUEST[search]) {

 $date1 = $_REQUEST[currentyear]."-".$_REQUEST[currentmonth]."-".$_REQUEST[currentday];
 $sdate=date('Y-m-d',strtotime(date('Y')."W".date('W')."0"));


 if($_REQUEST['user']) {
  $dd = " and area.user = ".$_REQUEST['user']." ";
  $ssdate = date("Y-m", mktime(0,0,0,1,1,date('Y')));
  $dd2 = " and dis_date >= '$ssdate' ";

  $dbgettrans = dbRead("select sum(buy) as sbuy, sum(dollarfees) as sfees, extract(year_month from transactions.dis_date) as date1 from transactions, members, area, tbl_admin_users where transactions.memid = members.memid and members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and buy > 0 and dollarfees > 0 and transactions.memid not in (".get_non_included_accounts($_SESSION['Country']['countryID']).") and to_memid not in (".get_non_included_accounts($_SESSION['Country']['countryID']).") $dd $dd2 group by date1");

 } else {

  if($_REQUEST['type'] == 1) {
   $dd = " and dis_date >= '$sdate' ";
  } elseif($_REQUEST['type'] == 2) {
   $bank = explode("-", $sdate);
   $ssdate = date("Y-m-d", mktime(0,0,0,$bank[1],$bank[2]-7,$bank[0]));
   $edate = date("Y-m-d", mktime(0,0,0,$bank[1],$bank[2]-1,$bank[0]));
   $dd = " and dis_date between '$ssdate' and '$edate' ";
  } elseif($_REQUEST['type'] == 3) {
   $ssdate = date("Y-m", mktime(0,0,0,date('m'),date('d'),date('Y')));
   $dd = " and dis_date like '$ssdate%' ";
  } elseif($_REQUEST['type'] == 4) {
   $ssdate = date("Y-m", mktime(0,0,0,date('m')-1,date('d'),date('Y')));
   $dd = " and dis_date like '$ssdate%' ";
  } elseif($_REQUEST['type'] == 5) {
   $ssdate = date("Y-m", mktime(0,0,0,1,1,date('Y')));
   $dd = " and dis_date >= '$ssdate' ";
  }

  $dbgettrans = dbRead("select sum(buy) as sbuy, sum(dollarfees) as sfees, username as date1 from transactions, members, area, tbl_admin_users where transactions.memid = members.memid and members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and buy > 0 and dollarfees > 0 and transactions.memid not in (".get_non_included_accounts($_SESSION['Country']['countryID']).") and to_memid not in (".get_non_included_accounts($_SESSION['Country']['countryID']).") $dd group by tbl_admin_users.username");
 }
?>

<br>
<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"></td>
 </tr>
</table>

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="620" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="Heading2" width="65" valign="bottom"><b>User:</b>&nbsp;</td>
    <td class="Heading2" width="150" valign="bottom"><b>Trade:</b>&nbsp;</td>
    <td class="Heading2" width="50" valign="bottom"><b>Dollar Fees:</b>&nbsp;</td>
  </tr>
<?

// Get the transactions out.

while($transrow = mysql_fetch_assoc($dbgettrans)) {

$dis_date = date("d/m/y", $transrow[date]);

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td width="65" valign="top" bgcolor="<?= $bgcolor ?>" height="19"><?= $transrow[date1] ?></a>&nbsp;</td>
    <td width="50" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow[sbuy] ?></td>
    <td width="285" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow[sfees] ?></td>
 </tr>

<?
$statement_fees += $transrow[dollarfees];
$foo++;
}
?>

  <tr>
    <td class="Heading2" width="65" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="150" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="50" align="right" valign="top" height="19"><b>&nbsp;</td>
  </tr>
</table>

</td>
</tr>
</table>
<?
}

}
?>