<html>
<head>
<title>Report - Accounts</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>
<body>
<?
if(!checkmodule("LynReports") && !checkmodule("LicReports")) {
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

<form method="POST" action="body.php?page=reports_comm&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();

if(checkmodule("LynReports")) {
 $tabarray = array(get_word("173")." ".get_page_data("7"),get_word("34")." ".get_page_data("7"),get_page_data("9"),'Agent Sub',get_page_data("8"),"Members Membership");
} elseif(checkmodule("LicReports")) {
 $tabarray = array(get_word("173")." ".get_page_data("7"),get_word("34")." ".get_page_data("7"),get_page_data("9"),'Agent Sub');
}


// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  sales();

} elseif($_GET[tab] == "tab2") {

  salesperson();

} elseif($_GET[tab] == "tab3") {

  fees();

} elseif($_GET[tab] == "tab4") {

  lic();

} elseif($_GET[tab] == "tab5") {

  membership();

} elseif($_GET[tab] == "tab6") {

  membership_mem();

}

?>

</form>

<?
function fees()  {

if($_REQUEST['currentmonth']) {
 $m = $_REQUEST['currentmonth'];
} else {
 $m = date("m")-1;
}
?>

<form method="post" action="body.php?page=feessearch" name="FeesSearch">

<table width="600" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading" style="border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000;"><?= get_page_data("1") ?></td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2" style="border-left: 1px solid #000000;"><b><?= get_word("25") ?>:</b></td>
      <td width="450" bgcolor="#FFFFFF" style="border-right: 1px solid #000000;"><select size="1" name="area">
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

           $query1 = dbRead("select FieldID, place from area where CID=".$_SESSION['User']['CID']."$areas order by place");
           while($row1 = mysql_fetch_assoc($query1)) {
            ?>
            <option <? if ($_REQUEST['area'] == $row1[FieldID]) { echo "selected "; } ?>value="<?= $row1[FieldID] ?>"><?= $row1[place] ?></option>
            <?
           }
          ?>
          </select>
      </td>
   </tr>
	<tr>
		<td width="150" align="right" class="Heading2" style="border-left: 1px solid #000000;"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF" style="border-right: 1px solid #000000;">
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
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>				</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2" style="border-left: 1px solid #000000;"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF" style="border-right: 1px solid #000000;">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
   <tr>
      <td width="150" height="30" class="Heading2" style="border-bottom: 1px solid #000000; border-left: 1px solid #000000;">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF" style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;"><input type="Submit" value="<?= get_word("48") ?>" name="search">&nbsp;</td>
  </tr>

</table>

<input type="hidden" name="search" value="1">

</form>

<?

if($_REQUEST[search] || $_REQUEST[search]) {

 $time_start = getmicrotime();

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="600" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Licensee Fees Check: <?= $counter ?></td>
 </tr>
</table>

<table width="600" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
  <tr>
    <td style="padding: 2px; border-left: 1px solid #000000; border-top: 1px solid #000000" class="Heading2" width="60" valign="bottom"><b><?= get_word("41") ?></b>&nbsp;</td>
    <td style="padding: 2px; border-top: 1px solid #000000" class="Heading2" width="200" valign="bottom"><b><?= get_word("3") ?></b>&nbsp;</td>
    <td style="padding: 2px; border-top: 1px solid #000000" class="Heading2" align="right" width="200" valign="bottom"><b><?= get_word("80") ?>:</b>&nbsp;</td>
    <td style="padding: 2px; border-top: 1px solid #000000" class="Heading2" align="right" width="70" valign="bottom"><b><?= get_word("1") ?>:</b>&nbsp;</td>
    <td style="padding: 2px; border-right: 1px solid #000000; border-top: 1px solid #000000" class="Heading2" align="right" width="100" valign="bottom"><b><?= get_word("46") ?></b>&nbsp;</td>
  </tr>
<?

// Get the transactions out.

$foo = 0;

$get_date = "$_REQUEST[currentyear]-$_REQUEST[currentmonth]";

$dbgettrans = dbRead("select date, transactions.memid, companyname, buy, sell, details, authno, dollarfees, type, id, checked from transactions, members where (transactions.memid=members.memid) and members.licensee = $_REQUEST[area] and (transactions.type in (5,6,10)) and transactions.dis_date like '$get_date-%' order by transactions.dis_date, transactions.id");

while($transrow = mysql_fetch_assoc($dbgettrans)) {

$endTable = false;
$counter = false;

$dis_date = date("d/m/y", $transrow[date]);

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td style="padding: 2px; border-left: 1px solid #000000" valign="top" bgcolor="<?= $bgcolor ?>" height="19"><? if(checkmodule("AuthEdit")) { echo '<a href="body.php?page=authedit&data='.$transrow[authno].'&search=true&redirectpage=body.php%3Fpage%3Dstatement%26memid%3D'.$MemberDetails[memid].'" class="nav">'; } ?><?= $dis_date ?><? if(checkmodule("AuthEdit")) { echo '</a>'; } ?></td>
    <td style="padding: 2px;" valign="top"  bgcolor="<?= $bgcolor ?>" height="19"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $transrow[id] ?>');" class="nav"><?= get_all_added_characters($transrow[companyname]) ?></a>&nbsp;</td>
    <td style="padding: 2px;" width="200" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= get_all_added_characters($transrow[details]) ?></td>
    <td style="padding: 2px;" width="70" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= $transrow[memid] ?></td>
    <td style="padding: 2px; border-right: 1px solid #000000" width="100" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($transrow[dollarfees],2) ?></td>
  </tr>

  <?
  //$dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, place FROM members,feespaid, area WHERE feespaid.memid = members.memid and members.licensee = area.FieldID AND type not in (8,9) and transID = ".$transrow['id']."");
  $dbquery = dbRead("SELECT feespaid.paymentdate as value1, type as type, members.companyname as value2,feespaid.memid as value3,feespaid.amountpaid as value4,feespaid.deducted_fees as value5,feespaid.percent as value9, place FROM members,feespaid, area WHERE feespaid.memid = members.memid and feespaid.area = area.FieldID AND type not in (8,9) and transID = ".$transrow['id']."");

  $counter = 1;

  while($row3 = mysql_fetch_assoc($dbquery)) {

	if($counter == 1) {

    	?>
    	<tr>
    		<td colspan="5" style="padding: 0px;">
    			<table width="100%" cellspacing="0" cellpadding="0" border="0">

    	<?

	}

	$value6 = $row3[value4]-($row3[value5]);
	$dis_value6 = number_format($value6,2);
	$dis_value4 = number_format($row3[value4],2);
	$value7 = ($value6/100)*$row3[value9];
	//$dis_value7 = number_format(round($value7),2);
	$dis_value7 = number_format($value7,2);

    if($row3['type'] == 1) {
     $t = "B";
    } elseif($row3['type'] == 6) {
     $t = "UCS";
    } else {
     $t = "S";
    }

    if($row3['type'] == 6) {
     $nn = "Unallocated Credit Fees";
    } else {
     $nn = substr($row3[value2], 0, 20);
    }

      ?>
      <tr>
        <td style="border-left: 1px solid #000000" width="30" align="left" bgcolor="<?= $bgcolor ?>" style="padding: 2px;">&nbsp;&nbsp;&nbsp;</td>
        <td align="left" width="150" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= $nn ?>&nbsp;</td>
        <td align="left" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= get_all_added_characters($row3[value3]) ?>&nbsp;</td>
        <td align="left" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= get_all_added_characters($row3[place]) ?>&nbsp;</td>
        <td align="left" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= get_all_added_characters($t) ?>&nbsp;</td>
        <td align="right" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= get_all_added_characters($dis_value4) ?>&nbsp;</td>
        <td align="right" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= number_format($row3[value5],2) ?>&nbsp;</td>
        <td align="right" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= get_all_added_characters($dis_value6) ?>&nbsp;</td>
        <td align="right" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= number_format($row3[value9],0) ?>&nbsp;</td>
        <td align="right" bgcolor="<?= $bgcolor ?>" style="padding: 2px;"><?= get_all_added_characters($dis_value7) ?>&nbsp;</td>
        <td style="border-right: 1px solid #000000" width="30" align="right" bgcolor="<?= $bgcolor ?>" style="padding: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>

      <?

  	$endTable = 1;
  	$counter++;

  }

  if($endTable) {

  	?>
  	</table>
    </td>
    </tr>
  	<?
  }

  ?>


  <tr>
<?
$statement_fees += $transrow[dollarfees];
$foo++;
}
?>

    <td class="Heading2" align="right" valign="top" height="19" colspan="1">&nbsp;</td>
    <td class="Heading2" width="200" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="200" align="right" valign="top" height="19">&nbsp;</td>
    <td class="Heading2" width="70" align="right" valign="top" height="19"><b><?= get_word("52") ?>:</td>
    <td class="Heading2" width="100" align="right" valign="top" height="19">&nbsp;<?= number_format($statement_fees,2) ?></td>
  </tr>
</table>

</form>

</body>
</html>

<?
 }
}

function membership()  {

 global $Amount, $referlist;

?>

<body onload="javascript:setFocus('paidsearch','data');">

<form method="post" action="body.php?page=paidsearch" name="paidsearch">

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("2") ?></td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2"><b><?= get_word("50") ?>:</b></td>
      <td width="450" bgcolor="#FFFFFF"><input type="text" name="data" size="20"></td>
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

if($_REQUEST[data]) {

 //$query  = "select members.*, area.*, salespeople.*, tbl_admin_payment_types.* from members, area left outer join salespeople on (salespeople.salesmanid = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.area=area.FieldID and members.memid = ".$_REQUEST['data']." and members.CID=".$_SESSION['User']['CID']."";
 $query  = "

 select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.*

 from members

 inner join area on members.licensee=area.FieldID

 left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid)
 left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID)

 WHERE members.memid = ".$_REQUEST['data']." and members.CID=".$_SESSION['User']['CID']."";

} else {

 //$query  = "select members.*, area.*, salespeople.*, tbl_admin_payment_types.* from members, area left outer join salespeople on (salespeople.salesmanid = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.area=area.FieldID and members.CID=".$_SESSION['User']['CID']." AND members.paid = 'N' order by companyname";
 $query  = "
 select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.*

 from members

 inner join area on members.licensee=area.FieldID

 left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid)
 left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID)

 WHERE members.CID=".$_SESSION['User']['CID']." AND members.paid = 'N' order by companyname";

}

$dbgetnoncheckedtrans = dbRead($query);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Non Paid Members: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="7" align="center" class="Heading"><?= get_page_data("3") ?>.</td>
	</tr>
	<tr>
		<td width="45" class="Heading2"><b><?= get_word("1") ?>:<br><?= get_word("90") ?>:</b></font></td>
		<td width="220" class="Heading2"><b><?= get_word("3") ?>:<br><?= get_page_data("5") ?>:</b></font></td>
		<td width="175" class="Heading2"><b><?= get_word("25") ?>:<br><?= get_word("34") ?>:</b></font></td>
		<td width="68" class="Heading2"><b><?= get_word("69") ?>:<br><?= get_word("74") ?>:</b></font></td>
		<td width="62" class="Heading2"><b><?= get_word("58") ?>:<br><?= get_word("61") ?>:</b></font></td>
		<td width="62" class="Heading2"><b>Refer</b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
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
      rewardscheck($row[referedby]);
    }

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

	if($row['refer_account']) {
	 $rrr = "Mem Refer";
	} else {
	 $rrr = "";
	}

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="45"><?= $row[memid] ?><br><?= get_all_added_characters($rewards) ?></td>
			<td width="220"><a href="body.php?page=viewmember&memid=<?= $row[memid] ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?><br><?= $referlist ?> <?= get_word("52") ?>:<?= number_format($Amount,2) ?></a></td>
			<td width="175"><?= get_all_added_characters($row[place]) ?><br><?= get_all_added_characters($row[Name]) ?></td>
			<td width="68"><?= $row[datejoined] ?><br><?= get_all_added_characters($row[banked]) ?></td>
			<td width="62"><?= get_all_added_characters($row[Type]) ?><br><?= number_format($row[membershipfeepaid],2) ?></td>
			<td width="62"><br><?= $rrr ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row[memid] ?>"></td>
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
			<td width="30"></td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="6" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="checkmembers"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

</body>
</html>

<?

}
}

function sales()  {

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
  <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF"><select name="area">
		 <?if($_SESSION['User']['ReportsAllowed'] == 'all') {?>
		 <option selected value=""><?= get_word("161") ?></option>
         <?}?>
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

           $query2 = dbRead("select place,FieldID from area where CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
			<option value="<?= $row2['FieldID'] ?>" <?if($row2['FieldID'] == $_REQUEST['area']) { print selected; }?>><?= $row2['place'] ?></option>
            <?
           }
          ?>

		  <?

		  //$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($_SESSION['User']['CID'])."' order by place ASC");
		  //while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
		  <?
		  //}
		  ?>
	  	 </select>
		</td>
   </tr>
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

$query3 = dbRead("select * from area where CID = ".$_SESSION['User']['CID']."$area order by place");
while($row3 = mysql_fetch_assoc($query3)) {
$foo = 0;
$total = 0;
 $newdate = "'$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'";
 //echo $newdate;
 $query  = "select * from members, tbl_admin_users WHERE members.salesmanid=tbl_admin_users.FieldID and tbl_admin_users.Area = ".$row3[FieldID]." and members.CID=".$_SESSION['User']['CID']." AND datejoined like $newdate order by membershipfeepaid, memshipfeepaytype, Name";
 //$query  = "select * from members, salespeople WHERE members.salesmanid=salespeople.salesmanid and salespeople.areaid = ".$row3[FieldID]." and members.CID=".$_SESSION['User']['CID']." AND datejoined like $newdate order by membershipfeepaid, memshipfeepaytype, name";
 //$query  = "select * from members, salespeople WHERE members.salesmanid=salespeople.salesmanid and (members.area = ".$row3['FieldID']." or members.licensee = ".$row3['FieldID'].") and members.CID=".$_SESSION['User']['CID']." AND datejoined like $newdate order by name, membershipfeepaid";

$dbgetnoncheckedtrans = dbRead($query);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {


} else {
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
		<td colspan="8" align="center" class="Heading"><?= get_page_data("7") ?> - <?= $row3['place'] ?></td>
	</tr>
	<tr>
		<td width="30" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="180" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
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
	if($row3['CID'] == 1) {
		$commision = $row[membershipfeepaid]-110;
	} else {
		$commision = ($row[membershipfeepaid]/100)*$row3['feepercent'];
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
			<td width="180"><a href="body.php?page=member_edit&tab=tab1&Client=<?= $row[memid] ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?><br></a></td>
			<td width="80" align="right"><?= get_all_added_characters($row[banked]) ?></td>
			<td width="100" align="right"><?= get_all_added_characters($row[Name]) ?></td>
			<td width="60" align="right"><?= get_all_added_characters($row4[Type]) ?></td>
			<td width="70" align="right"><?= number_format($row[membershipfeepaid],2) ?></td>
			<td width="70" align="right"><?= number_format($commision,2) ?></td>
			<td width="20" align="right"><?= get_all_added_characters($row[paid]) ?></td>
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

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

<?
}
}
}
}

function salesperson()  {

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
  <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF"><select name="area">
 		 <?if($_SESSION['User']['ReportsAllowed'] == 'all') {?>
		 <option selected value=""><?= get_word("161") ?></option>
		 <?}?>
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
 			  $areas2 = " and (Area in ($cat_array))";

		   }

           $query2 = dbRead("select place,FieldID from area where CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            ?>
			<option value="<?= $row2['FieldID'] ?>" <?if($row2['FieldID'] == $_REQUEST['area']) { print selected; }?>><?= $row2['place'] ?></option>
            <?
           }
          ?>


		  <?

		  //$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($_SESSION['User']['CID'])."' order by place ASC");
		  //while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
		  <?
		  //}
		  ?>
	  	 </select>
		</td>
   </tr>
   <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("34") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		 <select name="salesperson"><option selected value=""><?= get_word("161") ?></option>
		  <?

		  $dbgetarea=dbRead("select FieldID, Name from tbl_admin_users where CID like '".addslashes($_SESSION['User']['CID'])."' and SalesPerson = 1$areas2 order by Name ASC");
		  while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
			<option <? if ($row['FieldID'] == $_REQUEST['salesperson']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?></option>
		  <?
		  }
		  ?>
	  	 </select>
		</td>
   </tr>

   <tr>
		<td width="150" align="right" class="Heading2"><b>Sort By:</b></td>
		<td width="450" bgcolor="#FFFFFF">
          <input type = "radio" name = "type" id = "2" value = "1" CHECKED><?= get_word("34") ?><br>
          <input type = "radio" name = "type" id = "1" value = "2"><?= get_word("25") ?><br>
        </td>
   </tr>
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
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>
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

if($_REQUEST[type] == 1) {
if($_REQUEST['salesperson']) {
 $salesperson = " and FieldID = ".$_REQUEST['salesperson'];
} else {
 $salesperson = "";
}

$query3 = dbRead("select FieldID, Name as title from tbl_admin_users where CID = ".$_SESSION['User']['CID']." and SalesPerson = 1$salesperson order by Name");

} elseif($_REQUEST[type] == 2) {
if($_REQUEST['area']) {
 $area = " and FieldID = ".$_REQUEST['area'];
} else {
 $area = "";
}

$query3 = dbRead("select FieldID, place as title from area where CID = ".$_SESSION['User']['CID']."$area order by place");
}

while($row3 = mysql_fetch_assoc($query3)) {
 $total = 0;
 $newdate = "'$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'";

 if($_REQUEST['type'] == 1) {
  $query  = "select * from members, tbl_admin_users WHERE members.salesmanid=tbl_admin_users.FieldID and members.salesmanid = ".$row3[FieldID]." and members.CID=".$_SESSION['User']['CID']." AND datejoined like $newdate order by membershipfeepaid, memshipfeepaytype";
 } elseif($_REQUEST['type'] == 2) {
  $query  = "select * from members, tbl_admin_users WHERE members.salesmanid=tbl_admin_users.FieldID and tbl_admin_users.Area = ".$row3[FieldID]." and members.CID=".$_SESSION['User']['CID']." AND datejoined like $newdate order by Name,membershipfeepaid, memshipfeepaytype";
 }

$dbgetnoncheckedtrans = dbRead($query);

$counter = mysql_num_rows($dbgetnoncheckedtrans);
if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

} else {
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
		<td colspan="8" align="center" class="Heading"><?= get_page_data("7") ?> - <?= $row3['title'] ?></td>
	</tr>
	<tr>
		<td width="30" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="180" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="80" class="Heading2" align="right"><b><?= get_word("69") ?>:</b></font></td>
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
$salesid = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$dis_date = date("d/m/Y", $row[date]);

	$Amount = 0;
	//$commision = ($row[membershipfeepaid]/100)*$row['salespercent'];
	if($row['CID'] == 1) {
		$commision = $row[membershipfeepaid]-110;
	} else {
		$commision = ($row[membershipfeepaid]/100)*$row['salespercent'];
	}

	$query4 = dbRead("select * from tbl_admin_payment_types where FieldID = '".$row[memshipfeepaytype]."'");
    $row4 = mysql_fetch_assoc($query4);

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

    if($salesid != $row['FieldID'] && $_REQUEST[type] == 2)  {
       if($salesid != 0 )  {?>
		<tr bgcolor="#FFFFFF">
			<td width="30">&nbsp;</td>
			<td width="180">&nbsp;</td>
			<td width="80"></td>
			<td width="100">&nbsp;</td>
			<td width="60">&nbsp;</td>
			<td width="70"></td>
			<td width="70" align="right"><?= number_format($total,2) ?></td>
			<td width="20"></td>
		</tr>
		<?}?>
		<tr>
			<td colspan="8" bgcolor="#FFFFFF" align = "center"><?= get_all_added_characters($row['Name']) ?></font></td>
		</tr>
   <?
        $total = 0;
    }

    $salesid = $row['FieldID'];
?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="30"><?= $row[memid] ?></td>
			<td width="180"><a href="body.php?page=member_edit&tab=tab1&Client=<?= $row[memid] ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?><br></a></td>
			<td width="80" align="right"><?= $row[datejoined] ?></td>
			<td width="100" align="right"><?= get_all_added_characters($row[Name]) ?></td>
			<td width="60" align="right"><?= get_all_added_characters($row4[Type]) ?></td>
			<td width="70" align="right"><?= number_format($row[membershipfeepaid],2) ?></td>
			<td width="70" align="right"><?= number_format($commision,2) ?></td>
			<td width="20" align="right"><?= $row['paid'] ?></td>
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
			<td width="100">&nbsp;</td>
			<td width="60">&nbsp;</td>
			<td width="70">&nbsp;</td>
			<td width="70" align="right"><?= number_format($total,2) ?></td>
			<td width="20">&nbsp;</td>
		</tr>
<?
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

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

<?
}
}
}

}
function rewardscheck($refer)  {

 global $Amount, $referlist;

 // get members details out and display a table row with $40 in it for them.

 $Amount = 40;
 $referlist = "$refer ";

 $query1 = dbRead("select * from members where memid = '$refer'");
 $row1 = mysql_fetch_assoc($query1);

 if($row1[referedby]) {

  // get this persons details out and display a row.

  $Amount += 5;
  $referlist .= "$row1[referedby] ";

  $query2 = dbRead("select * from members where memid = '$row1[referedby]'");
  $row2 = mysql_fetch_assoc($query2);


  if($row2[referedby]) {

   // get the next persons out.

   $Amount += 10;
   $referlist .= "$row2[referedby] ";

   $query3 = dbRead("select * from members where memid = '$row2[referedby]'");
   $row3 = mysql_fetch_assoc($query3);


   if($row3[referedby]) {

    // get next persons out.

    $Amount += 15;
    $referlist .= "$row3[referedby] ";

   $query4 = dbRead("select * from members where memid = '$row3[referedby]'");
   $row4 = mysql_fetch_assoc($query4);


    if($row4[referedby]) {

     $Amount += 20;
     $referlist .= "$row4[referedby] ";
    }

   }

  }

 }

}

function lic()  {

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
     <td colspan="2" align="center" class="Heading">Licensee Subsidy Search</td>
  </tr>
  <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF"><select name="area">
		 <?if($_SESSION['User']['ReportsAllowed'] == 'all') {?>
		 <option selected value=""><?= get_word("161") ?></option>
         <?}?>
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

           $query2 = dbRead("select place,FieldID from area where CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
			<option <? if ($_REQUEST['area'] == $row2['FieldID']) { echo "selected "; } ?>value="<?= $row2['FieldID'] ?>"><?= $row2['place'] ?></option>
            <?
           }
          ?>

		  <?

		  //$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($_SESSION['User']['CID'])."' order by place ASC");
		  //while($row = mysql_fetch_assoc($dbgetarea)) {
		  ?>
		  <?
		  //}
		  ?>
	  	 </select>
		</td>
   </tr>
   <tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($m == "03") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($m == "06") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($m == "09") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($m == "12") { echo "selected "; } ?>value="12">December</option>
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

$query3 = dbRead("select * from area where CID = ".$_SESSION['User']['CID']."$area order by place");
while($row3 = mysql_fetch_assoc($query3)) {

 $foo = 0;
 $total = 0;

 $date3 = date("Y-m-d", mktime(0,0,0,$_REQUEST[currentmonth]+1,1,$_REQUEST[currentyear]-2));
 $date4 = date("Y-m-d", mktime(0,0,0,$_REQUEST[currentmonth]+1,1-1,$_REQUEST[currentyear]));
 $date5 = date("Ym", mktime(0,0,0,$_REQUEST[currentmonth],1,$_REQUEST[currentyear]));

 //$query  = "select * from members WHERE licensee = ".$row3[FieldID]." and members.CID=".$_SESSION['User']['CID']." AND datejoined >= '$date3' and datejoined <= '$date4' and (status = 0 or status = 4) order by memid";
 //$query  = "select * from members WHERE licensee = ".$row3[FieldID]." and members.CID=".$_SESSION['User']['CID']." and (status = 0 or status = 4) and CID = 1 order by memid";
 $query  = "select * from tbl_subsidy WHERE licensee = ".$row3[FieldID]." and month = ".$date5." order by acc_no";

 $dbgetnoncheckedtrans = dbRead($query);

 $counter = mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="620" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Members available for subsidy: <?= $counter ?></td>
 </tr>
</table>

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="7" align="center" class="Heading">Licensee Subsidy - <?= $row3['place'] ?></td>
	</tr>
	<tr>
		<td width="30" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="180" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="80" class="Heading2" align="right"><b>Date Joined:</b></font></td>
		<td width="60" class="Heading2" align="right"><b>No Trans:</b></font></td>
		<td width="70" class="Heading2" align="right"><b>Subsidy:</b></font></td>
	</tr>
<?

if(mysql_num_rows($dbgetnoncheckedtrans) == 0) {

?>
		<tr>
			<td colspan="7" bgcolor="#FFFFFF"><?= get_page_data("6") ?>.</font></td>
		</tr>
<?

} else {

$foo = 0;
$total = 0;
$count = 0;

while($row = mysql_fetch_assoc($dbgetnoncheckedtrans)) {

	$dis_date = date("d/m/Y", $row[date]);
	$commision = 165;

    $date1 = date("Y-m-d", mktime(0,0,0,$_REQUEST[currentmonth]+1,1-1,$_REQUEST[currentyear]));
    $date2 = date("Y-m-d", mktime(0,0,0,$_REQUEST[currentmonth]-2,1,$_REQUEST[currentyear]));

    //$query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."','".$_SESSION['Country']['adminacc']."') and dis_date >= '$date2' and dis_date <= '$date1' order by dis_date DESC limit 1");
	//$row1 = mysql_fetch_array($query1);

	//if($row1['dis_date']) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="30"><?= $row[acc_no] ?></td>
			<td width="180"><a href="body.php?page=member_edit&tab=tab1&Client=<?= $row[acc_no] ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?><br></a></td>
			<td width="80" align="right"><?= $row[datejoined] ?></td>
			<td width="60" align="right"><?= get_all_added_characters($row[no_trans]) ?></td>
			<td width="70" align="right"><?= number_format($commision,2) ?></td>
		</tr>
<?
$total += $commision;
$count++ ;
$foo++;

	//}
}

}

if(!$data) {

$per = ($count/$counter)*100;
?>
		<tr bgcolor="#FFFFFF">
			<td width="30">&nbsp;</td>
			<td width="180"><b>No of Members meeting requirements: <?= $count ?> - <?= number_format($per,2) ?>%</b></td>
			<td width="60">&nbsp;</td>
			<td width="70" align=right><b>Total Subsidy:</b></td>
			<td width="70" align="right"><b><?= number_format($total,2) ?></b></td>
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
</table>
</td>
</tr>
</table>

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

<?

}
}
}

function membership_mem()  {

 global $Amount, $referlist;

?>

<body onload="javascript:setFocus('paidsearch','data');">

<form method="post" action="body.php?page=paidsearch" name="paidsearch">

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("2") ?></td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2"><b><?= get_word("50") ?>:</b></td>
      <td width="450" bgcolor="#FFFFFF"><input type="text" name="data" size="20"></td>
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

if($_REQUEST[data]) {

 //$query  = "select members.*, area.*, salespeople.*, tbl_admin_payment_types.* from members, area left outer join salespeople on (salespeople.salesmanid = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.area=area.FieldID and members.memid = ".$_REQUEST['data']." and members.CID=".$_SESSION['User']['CID']."";
 $query  = "
 select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.*

 from members

 inner join area on members.licensee=area.FieldID

 left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid)
 left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID)

 WHERE members.memid = ".$_REQUEST['data']." and members.CID=".$_SESSION['User']['CID']."";

} else {

 //$query  = "select members.*, area.*, salespeople.*, tbl_admin_payment_types.* from members, area left outer join salespeople on (salespeople.salesmanid = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.area=area.FieldID and members.CID=".$_SESSION['User']['CID']." AND members.paid = 'N' order by companyname";
 //$query  = "select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.* from members, area left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid) left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID) WHERE members.licensee=area.FieldID and members.CID=".$_SESSION['User']['CID']." AND members.paid = 'N' order by companyname";
 $query  = "
 select members.*, area.*, tbl_admin_users.*, tbl_admin_payment_types.*, members2.companyname as companyname2

 from members

 inner join area on members.licensee=area.FieldID

 inner join members members2 on members.referedby = members2.memid

 left outer join tbl_admin_users on (tbl_admin_users.FieldID = members.salesmanid)
 left outer join tbl_admin_payment_types on (members.memshipfeepaytype = tbl_admin_payment_types.FieldID)

 WHERE members.CID=".$_SESSION['User']['CID']." and members.refer_account > 0 and members.datejoined > '2007-09-01' AND members.paid_mem = 'N' and members.referedby > 0 order by companyname";

}

$dbgetnoncheckedtrans = dbRead($query);

$counter = mysql_num_rows($dbgetnoncheckedtrans);

?>
<br>
<form name="checktrans" method="post" action="/general.php">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center">Total Non Paid Members: <?= $counter ?></td>
 </tr>
</table>

<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading"><?= get_page_data("3") ?>.</td>
	</tr>
	<tr>
		<td width="45" class="Heading2"><b><?= get_word("1") ?>:<br>Ref <?= get_word("1") ?>:</b></font></td>
		<td width="220" class="Heading2"><b><?= get_word("3") ?>:<br>Ref <?= get_word("3") ?>:</b></font></td>
		<td width="175" class="Heading2"><b><?= get_word("25") ?>:<br><?= get_word("34") ?>:</b></font></td>
		<td width="68" class="Heading2"><b><?= get_word("69") ?>:<br><?= get_word("74") ?>:</b></font></td>
		<td width="62" class="Heading2"><b><?= get_word("58") ?>:<br><?= get_word("61") ?>:</b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
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

	$Amount = 0;
	$referlist = "";

	if($row[referedby]) {
      rewardscheck($row[referedby]);
    }

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="45"><?= $row[memid] ?><br><?= $row['referedby'] ?></td>
			<td width="220"><a href="body.php?page=viewmember&memid=<?= $row[memid] ?>" class="nav"><?= get_all_added_characters($row[companyname]) ?><br><?= $row[companyname2] ?></a></td>
			<td width="175"><?= $row[place] ?><br><?= get_all_added_characters($row[Name]) ?></td>
			<td width="68"><?= $row[datejoined] ?><br><?= get_all_added_characters($row[banked]) ?></td>
			<td width="62"><?= $row[Type] ?><br><?= number_format($row[membershipfeepaid],2) ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row[memid] ?>"></td>
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
			<td width="30"></td>
		</tr>
<?
}
?>
		<tr>
		    <td colspan="6" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("4") ?>" name="checkmembers_mem"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

</body>
</html>

<?

}
}
?>
