<?
if(!checkmodule("PrintCheque")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
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

include("modules/class.chequeprint.php");
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
</script>
</head>
<body>
<form method="POST" action="body.php?page=printchequeview&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?

// Some Setup.

 $tabarray = array(get_page_data("1"),get_page_data("8"),'Directory Orders');

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  orders();

} elseif($_GET[tab] == "tab2") {

  inter();

} elseif($_GET[tab] == "tab3") {

  direct();

}
?>

</form>
</body>
</html>

<?
function orders() {


if($_REQUEST[checkprint]) {

// include some files.
include("class.html.mime.mail.inc");

add_kpi("61", "0");
$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

//do emails for the individual countries first.
//$query = dbRead("select *  from memcards, members, country where (memcards.memid=members.memid) and (members.CID = country.countryID) and type='2' and done='N' and CID='".$_SESSION['User']['CID']."' order by memcards.memid");
$query = dbRead("
	select *

	from memcards

		inner
	  		join
	  			members
	  			on (memcards.memid=members.memid)
		inner
	  		join
	  			country
	  			on (members.CID = country.countryID)

	where
		memcards.type='2' and memcards.done='N' and members.CID='".$_SESSION['User']['CID']."'

	order by memcards.memid

	");

if(@mysql_num_rows($query) != 0) {

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\n\r\n\rAttached are todays cheque books to be sent out.\n\rYou may need to download Acrobat Reader to view this document.  You can download it for free from:\n\rhttp://www.adobe.com/products/acrobat/readstep.html\n\r\n\rRegards\n\r\n\rETX International";

  // get the actual taxinvoice ready.

   $Cheque = new ChequePrint($_SESSION['Country']['cheque_pages']);

   $Cheque->SetType("3", $_SESSION['Country']['countryID']);
   $Cheque->GenerateCheques();

   $buffer = $Cheque->Complete("2");

   	$attachArray[] = array($buffer, 'chequebooks-'.$date.'.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), 'Cheque Books - '.$date, 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

   echo "Cheque PDF has been email to ".$_SESSION['User']['EmailAddress']."";

   while($row = mysql_fetch_array($query)) {
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$row['memid']."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','2','Cheque Book Sent')");
    dbWrite("update memcards set done='Y' where type='2' and memid = '$row[memid]'");
   }

} else {

   echo "No email available";

}

die;
}

if($_REQUEST[checkmembers]) {
$idd = $_REQUEST['id2'];
$count = sizeof($idd);
$i = 0;
for ($i = 0; $i <= $count; $i++) {

	dbWrite("delete from memcards where id='".$idd[$i]."'");

}

}

$time_start = getmicrotime();

//$query6  = "select tbl_admin_users.*, memcards.memid as memid, members.companyname as companyname, memcards.userid as userid, area.place as place, memcards.date as date, memcards.id as id  from memcards, members, area left outer join tbl_admin_users on (memcards.userid = tbl_admin_users.FieldID) WHERE memcards.memid=members.memid and members.licensee=area.FieldID and members.CID = '".$_SESSION['User']['CID']."' AND memcards.type = '2' and done = 'N' order by members.companyname";
$query6  = "select tbl_admin_users.*, memcards.memid as memid, members.companyname as companyname, memcards.userid as userid, area.place as place, memcards.date as date, memcards.id as id

	from memcards

		inner
	  		join
	  			members
	  			on (memcards.memid=members.memid)

		inner
	  		join
	  			area
	  			on (members.licensee=area.FieldID)

		left outer join tbl_admin_users on (memcards.userid = tbl_admin_users.FieldID)

	WHERE
		members.CID = '".$_SESSION['User']['CID']."' AND memcards.type = '2' and memcards.done = 'N'

	order by members.companyname
	";

$dbgetnoncheckedtrans = dbRead($query6);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

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
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("50") ?>:</b></font></td>
		<td width="220" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="110" class="Heading2"><b><?= get_page_data("2") ?>:</b></font></td>
		<td width="170" class="Heading2"><b><?= get_word("25") ?>:</b></font></td>
		<td width="60" class="Heading2"><b><?= get_page_data("3") ?></b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>
<?

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF"><?= get_page_data("4") ?>.</font></td>
		</tr>
<?

} else {

$foo = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

  if($row[userid] > 10000)  {
    $adder = $row[userid];
  }  else  {

    $adder = $row['Name'];
  }

	$dis_date = date("d/m/Y", $row[date]);

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row[memid] ?></td>
			<td width="220"><?= get_all_added_characters($row[companyname]) ?></td>
			<td width="110"><?= get_all_added_characters($adder) ?></td>
			<td width="170"><?= get_all_added_characters($row[place]) ?></td>
			<td width="60"><?= $row[date] ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row[id] ?>"></td>
		</tr>
<?

$totalamount += $amount;
$foo++;

}

}

if(!$data) {
?>
		<tr bgcolor="#FFFFFF">
			<td width="40">&nbsp;</td>
			<td width="220">&nbsp;</td>
			<td width="110">&nbsp;</td>
			<td width="170" align="right">&nbsp;</td>
			<td width="60"></td>
			<td width="30"></td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="7" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("6") ?>" name="checkmembers"><input type="submit" value="<?= get_page_data("7") ?>" name="checkprint"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="tab" value="<?= $_GET[tab] ?>">
<?
}


function inter()  {
?>
</form>
<form method="POST" action="includes/printcheque.php" name="PrintCheque">

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("7") ?></td>
	</tr>
	<tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2" width="40"></td>
		<td bgcolor="#FFFFFF" ><input type="button" value="<?= get_word("83") ?>" name="print" onclick="javascript:document.PrintCheque.submit()"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="inter" value="1">
</form>
<?
}

function direct() {


if($_REQUEST[checkprint]) {

// include some files.
include("class.html.mime.mail.inc");

add_kpi("61", "0");
$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

//do emails for the individual countries first.
//$query = dbRead("select *  from memcards, members where (memcards.memid=members.memid) and type='2' and done='N' and CID='".$_SESSION['User']['CID']."' order by memcards.memid");
$query = dbRead("

	select *
	from members

	inner join memdir on memdir.memid=members.memid

	where done='N' and CID='".$_SESSION['User']['CID']."'

	order by memdir.memid");

if(@mysql_num_rows($query) != 0) {

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\n\r\n\rAttached is todays cheque books to be send out.\n\rYou may need to download Acrobat Reader to view this document.  You can download it for free from:\n\rhttp://www.adobe.com/products/acrobat/readstep.html.\n\r\n\rRegards\n\r\n\rE Banc Trade Head Office";

  // get the actual taxinvoice ready.

   $Cheque = new ChequePrint($_SESSION['Country']['cheque_pages']);

   $Cheque->SetType("3", $_SESSION['Country']['countryID']);
   $Cheque->GenerateCheques();

   $buffer = $Cheque->Complete("2");

   	$attachArray[] = array($buffer, 'chequebooks-'.$date.'.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), 'Cheque Books - '.$date, 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

   echo "Cheque PDF has been email to ".$_SESSION['User']['EmailAddress']."";

   while($row = mysql_fetch_array($query)) {
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$row['memid']."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Cheque Book Sent')");
    dbWrite("update memcards set done='Y' where type='2' and memid = '$row[memid]'");
   }

} else {

   echo "No email available";

}

die;
}

if($_REQUEST[checkmembers1]) {
$idd = $_REQUEST['id2'];
$count = sizeof($idd);
$i = 0;
for ($i = 0; $i <= $count; $i++) {

	dbWrite("delete from memdir where fieldid='".$idd[$i]."'");

}

}

$time_start = getmicrotime();

//$query6  = "select tbl_admin_users.*, memcards.memid as memid, members.companyname as companyname, memcards.userid as userid, area.place as place, memcards.date as date, memcards.id as id  from memcards, members, area left outer join tbl_admin_users on (memcards.userid = tbl_admin_users.FieldID) WHERE memcards.memid=members.memid and members.area=area.FieldID and members.CID = '".$_SESSION['User']['CID']."' AND memcards.type = '2' and done = 'N' order by members.companyname";
//$query6  = "select tbl_admin_users.*, memdir.memid as memid, memdir.type as type, members.companyname as companyname, memdir.userid as userid, area.place as place, memdir.date as date, memdir.fieldid as id, tbl_area_states.StateName as sname from memdir, members, area left outer join tbl_admin_users on (memdir.userid = tbl_admin_users.FieldID) left outer join tbl_area_states on (memdir.stateid = tbl_area_states.FieldID) WHERE memdir.memid=members.memid and members.area=area.FieldID and members.CID = '".$_SESSION['User']['CID']."' and done = 'N' order by members.companyname";
$query6  = "select tbl_admin_users.*, memdir.memid as memid, memdir.type as type, members.companyname as companyname, memdir.userid as userid, area.place as place, memdir.date as date, memdir.fieldid as id, tbl_area_states.StateName as sname

	from memdir

		inner
			join
				members
				on memdir.memid=members.memid

		inner
			join
				area
				on members.area=area.FieldID

		left outer join tbl_admin_users on (memdir.userid = tbl_admin_users.FieldID) left outer join tbl_area_states on (memdir.stateid = tbl_area_states.FieldID)

	WHERE

		members.CID = '".$_SESSION['User']['CID']."' and done = 'N'

	order by members.companyname
";

$dbgetnoncheckedtrans = dbRead($query6);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

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
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="7" align="center" class="Heading"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("50") ?>:</b></font></td>
		<td width="220" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="50" class="Heading2"><b>Direct:</b></font></td>
		<td width="100" class="Heading2"><b><?= get_page_data("2") ?>:</b></font></td>
		<td width="170" class="Heading2"><b><?= get_word("25") ?>:</b></font></td>
		<td width="60" class="Heading2"><b><?= get_page_data("3") ?></b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>
<?

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="6" bgcolor="#FFFFFF"><?= get_page_data("4") ?>.</font></td>
		</tr>
<?

} else {

$foo = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

  if($row[userid] > 10000)  {
    $adder = $row[userid];
  }  else  {

    $adder = $row['Name'];
  }

	$dis_date = date("d/m/Y", $row[date]);

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

    if($row['type'] == 1)  {
      $dir = "National";
    } else {
      $dir = $row['sname'];
    }

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row[memid] ?></td>
			<td width="220"><?= get_all_added_characters($row[companyname]) ?></td>
			<td width="50"><?= get_all_added_characters($dir) ?></td>
			<td width="100"><?= get_all_added_characters($adder) ?></td>
			<td width="170"><?= get_all_added_characters($row[place]) ?></td>
			<td width="60"><?= $row[date] ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row[id] ?>"></td>
		</tr>
<?

$totalamount += $amount;
$foo++;

}

}

if(!$data) {
?>
		<tr bgcolor="#FFFFFF">
			<td width="40">&nbsp;</td>
			<td width="220">&nbsp;</td>
			<td width="50">&nbsp;</td>
			<td width="100" align="right">&nbsp;</td>
			<td width="60"></td>
			<td width="30"></td>
			<td width="30"></td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="7" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="7" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("6") ?>" name="checkmembers1"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="tab" value="<?= $_GET[tab] ?>">
<?
}

?>