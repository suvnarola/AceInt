<?



if(!checkmodule("StatsReports") && !checkmodule("LicReports") && !checkmodule("MemReports")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?></td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>

<form method="POST" action="body.php?page=reports_members&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

//echo "<pre>" . print_r( $_REQUEST , true ) . "</pre>" ; exit ;

 $time_start = getmicrotime();
 //$tabarray = array('Listed','Unlisted','Temp Unlisted','Uncontactable','Status','Non Contacted','Members with Equity','Members in Debt','No DD','Fees Owing','Returned from D/Collect','Activity Report','Facility','Stats Report','Clubs','Broker Driven');
 $tabarray = array('Listed','Unlisted','Temp Unlisted','Non Contacted','Uncontactable','Status','Members with Equity','Members in Debt','Overdue Fees','Returned from D/Collect','No DD','Facility','Activity Report');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Broker Driven") {

 broker();

} elseif($_GET[tab] == "Uncontactable") {

 uncon();

} elseif($_GET[tab] == "Temp Unlisted") {

 temp();

} elseif($_GET[tab] == "Members in Debt") {

  debt();

} elseif($_GET[tab] == "Stats Report") {

  stats();

} elseif($_GET[tab] == "Listed") {
  listed();

} elseif($_GET[tab] == "Unlisted") {

  unlist();

} elseif($_GET[tab] == "Status") {

  status();

} elseif($_GET[tab] == "Non Contacted") {

  con();

} elseif($_GET[tab] == "Facility") {

  fac();

} elseif($_GET[tab] == "Activity Report") {

  act();

} elseif($_GET[tab] == "Clubs") {

  club();

} elseif($_GET[tab] == "Members with Equity") {

  equity();

} elseif($_GET[tab] == "Overdue Fees") {

  owing();

} elseif($_GET[tab] == "Returned from D/Collect") {

  soli();

} elseif($_GET[tab] == "No DD") {

  dd();

}

function debt() {

if($_REQUEST['search'])  {

 //if($_REQUEST['lic'])  {
  //$li = " and members.licensee = ".$_REQUEST['lic'];
 //} else {
 // $li = "";
 //}

  if($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
	 $li = "";
    } else {
     $li = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $li = " and members.licensee = ".$_REQUEST['lic'];
  }

 if($_REQUEST['amount'])  {
  $amo = " -".$_REQUEST['amount'];
 } else {
  $amo = 0;
 }

 $dbquery = dbRead("select ((sum(sell)-sum(buy))-overdraft-reoverdraft) as net, sum(transactions.buy) as TradeAmount, sum(transactions.sell) as STradeAmount, members.*, RegionalID from transactions, members, tbl_area_physical where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID)$li and members.CID = ".$_SESSION['User']['CID']." and transactions.type IN (1,2) and status not in (1,3,6) group by transactions.memid having ((sum(sell)-sum(buy))-members.overdraft-reoverdraft) <= $amo order by members.memid");
?>

<table width="610" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="610" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td colspan="7" align="center" class="Heading2">Members in Debt - </td>
	</tr>
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("5") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("12")  ?></b></td>
    <td align="right" class="Heading2"><b></b></td>
    <td align="right" class="Heading2"><b><?= get_word("53") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_word("54") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_word("55") ?></b></td>
  </tr>
<?

$foo= 0;
$totalfac = 0;
$totalrefac = 0;
$totalnet =0;

while($row = mysql_fetch_assoc($dbquery))  {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

    $bal = $row[STradeAmount]-$row[TradeAmount];

?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[memid] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[companyname] ?></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[status] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($bal,2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[overdraft],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[reoverdraft],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[net],2) ?></td>
  </tr>
<?

 $totalfac += $row[overdraft];
 $totalrefac += $row[reoverdraft];
 $totalnet += $row[net];
 $foo++;

}

?>
 <tr>
  <td colspan="4" align="right" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
  <td align="right" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalfac,2) ?></td>
  <td align="right" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalrefac,2) ?></td>
  <td align="right" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalnet,2) ?></td>
 </tr>
</table>
</td>
</tr>
</table>

<?
die;
} else {
?>

<html>
<body>

<form method="get" action="/includes/reports_daily.php">
<input type="hidden" name="search" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2">Members in Debt</td>
	</tr>
	</tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	    <td width="150" align="right" class="Heading2"><b><?= get_word("78") ?>:</b></td>
        <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
       <tr>
	    <td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
        <td bgcolor="#FFFFFF" width="600">
          <select  name="lic" >
           <?

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
          ?>
		  <option value="">All Areas</option>
		  <?
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		  }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID from area where `drop` = 'Y' and area.CID=".$_SESSION['User']['CID']."$areas order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <tr>
	    <td width="150" align="right" class="Heading2"><b>In Debt Amount or More:</b></td>
        <td bgcolor="#FFFFFF" width="600">
        <input type="text" name="amount" size="20">
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

<?
}
}

?>
</form>

<?
function uncon() {

 global $time_start;

 $colspan = "9";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {

?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Uncontactable Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>L/Traded:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
   </tr>
<?

  $foo = 0;
  //$area2 = " and licensee in (".get_areas_allowed(true).")";

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
    if($_REQUEST['area'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and licensee = ".$_REQUEST['area']."";
    }
  }
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status not in (1,3,6) and bdriven = 'Y'$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status not in (1,3,6) and bdriven = 'Y'$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID from members, transactions, tbl_area_physical, area where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID) and (members.licensee = area.FieldID) and status not in (1,3) and uncon = 'Y'$area2 and members.CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.licensee, members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><?= $row[memid] ?></td>
      <td  nowrap width = "180"><?= $row[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
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
     <td width="100%" colspan="2" align="center" class="Heading">Uncontactable Members Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="center" class="Heading2"><?= get_word("78") ?>:</td>
	 	 <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="center" class="Heading2"><?= get_word("25") ?>:</td>
	 	 <td bgcolor="#FFFFFF" width="600">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" colspan="2" align = "right" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?

 }
}

function broker() {

 global $time_start;

 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {

?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Broker Driven Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
	 <td class="Heading2" align = "right"><b>% Date:</b></td>
	 <td class="Heading2" align = "right"><b>Pr/y:</b></td>
   </tr>
<?

  $foo = 0;
  //$area2 = " and licensee in (".get_areas_allowed(true).")";

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } elseif($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } else {
   $area2 = " and licensee = ".$_REQUEST['area']."";
  }
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status not in (1,3,6) and bdriven = 'Y'$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status not in (1,3,6) and bdriven = 'Y'$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID from members, transactions, tbl_area_physical, area where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID) and (members.licensee = area.FieldID) and status not in (1,3,6) and bdriven = 'Y'$area2 and members.CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.licensee, members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
     if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" colspan="2" align="center" class="Heading">Broker Driven Members Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td align="left" class="Heading2">	 <select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:
	  </td>
	 	 <td align="left" class="Heading2">
		  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:
	  </td>
	 	 <td align="left" class="Heading2">
		  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" colspan="2" align = "right" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?

 }
}

function temp() {

 global $time_start;

 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Temp Unlisted Members Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;
  //$area2 = " and licensee = ".$_REQUEST['area']."";


  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = ( $_REQUEST['area'] == 'all') ? '' : " and licensee = ".$_REQUEST['area']."";
  }

  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status not in (1,3,6) and t_unlist = 1$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status not in (1,3,6) and t_unlist = 1$area2 and CID = ".$_SESSION['User']['CID']." group by transactions.memid order by members.memid ASC");
  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID from members, transactions, tbl_area_physical, area where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID) and (members.licensee = area.FieldID) and status not in (1,3,6) and t_unlist = 1$area2 and members.CID = ".$_SESSION['User']['CID']." group by transactions.memid order by members.licensee, members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
	 if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" colspan="2" align="center" class="Heading">Temp Unlisted Members Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td align="left" class="Heading2">	 <select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:</td>
	 	 <td align="left" class="Heading2">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:</td>
	 	 <td align="left" class="Heading2">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" colspan="2" align = "right" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
 }

}


function stats()  {

$thismonth=date("n");
$thisyear=date("Y");
add_kpi("64", "0");

if($_REQUEST['pm'])  {

 st();
die;
}

?>
<html>
<body>
</form>
<form method="get" action="/includes/reports_stats.php">

<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
	</tr>
       <tr>
	    <td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
        <td bgcolor="#FFFFFF" width="600">
          <select  name="lic" >
           <?

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
          ?>
           <option value="">All Areas</option>
          <?
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		  }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID from area where `drop` = 'Y' and area.CID=".$_SESSION['User']['CID']."$areas order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
	 </td>
    </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("40") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="pm">
				<option selected value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
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
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="36">36</option>
				<option value="37">37</option>
				<option value="38">38</option>
				<option value="39">39</option>
				<option value="40">40</option>
			</select>
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
</html>
<?
}

function listed() {

	/*

Array
(
    [page] => reports_members
    [ID] =>
    [tab] => Listed
    [area] => 1122
    [SearchBu] => Search
    [search] => 1
    [PHPSESSID] => 5ec1smv4ieuqo49qmi7e3t38h4
)
*/


	global $time_start;

 if($_REQUEST['amount'])  {
  //$amo = " and (((sum(sell)-sum(buy))-members.overdraft) < -".$_REQUEST['amount'].")";
  $amo = " and ((((sum(sell)-sum(buy))-members.overdraft) < 5000) and (((sum(sell)-sum(buy))-members.overdraft) > 0))";
 } else {
  $amo = "";
 }

 if($_REQUEST['club']) {
   $cc = " and members.fiftyclub not in (1,2) ";
 }

 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Listed Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] == 'all') {
    //$area2 = "";
  //} else {
    //$area2 = " and licensee = ".$_REQUEST['area']."";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = ( $_REQUEST['area'] == 'all' ) ? '' : " and licensee = ".$_REQUEST['area']."";
  }

 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having (sum(mem_categories.category) = 0) order by members.memid ASC");
 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,4,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having ((sum(mem_categories.category) = 0)$amo) order by members.memid ASC");
	$sql = "select 
		members.*, 
		sum(transactions.buy) as buy, 
		sum(transactions.sell) as sell, 
		sum(transactions.dollarfees) as dollarfees, 
		sum(mem_categories.category) as cat, 
		RegionalID

   from members

   inner join tbl_area_physical on members.area = tbl_area_physical.FieldID
   inner join area on members.licensee = area.FieldID
   left outer join mem_categories on (members.memid = mem_categories.memid)
   left outer join transactions on members.memid = transactions.memid

   where 
   		status not in (1,3,6)
   		$area2$cc 
   		and 
   		t_unlist = 'N' 
   		and members.CID = '".$_SESSION['User']['CID']."' 
   		
   		group by 
   		members.memid 
   		
   		having 
   		((sum(mem_categories.category) > 0)$amo) 
   		
   		order by 
   		members.licensee, members.memid ASC"   ;

//die( $sql ) ;

//echo "<pre>" . print_r( $sql , true ) . "</pre>" ;

//echo $sql . "<br><br>\n\n" ;

$query = dbRead( $sql );

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
	 if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];
	 $ntotal+=$net;

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ntotal)?></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" align="center" class="Heading" colspan = "2">List Member Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Exclude Club Members:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="club" value="1"></td>
   </tr>
   <tr>
     <td bgcolor="#FFFFFF" align = "right" nowrap colspan = "2"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
 }
}

function unlist() {

 global $time_start;

 if($_REQUEST['amount'])  {
  //$amo = " and (((sum(sell)-sum(buy))-members.overdraft) < -".$_REQUEST['amount'].")";
  $amo = " and ((((sum(sell)-sum(buy))-members.overdraft) < 5000) and (((sum(sell)-sum(buy))-members.overdraft) > 0))";
 } else {
  $amo = "";
 }

 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Unlisted Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] == 'all') {
    //$area2 = "";
  //} else {
    //$area2 = " and licensee = ".$_REQUEST['area']."";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  //} elseif($_REQUEST['regional']) {
   //$area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = ( $_REQUEST['area'] == 'all' ) ? '' : " and licensee = ".$_REQUEST['area']."";
  }

 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having (sum(mem_categories.category) = 0) order by members.memid ASC");
 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,4,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having ((sum(mem_categories.category) = 0)$amo) order by members.memid ASC");
   $query = dbRead("
   select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat, RegionalID

   from members

   inner join tbl_area_physical on members.area = tbl_area_physical.FieldID
   inner join area on members.licensee = area.FieldID
   left outer join mem_categories on (members.memid = mem_categories.memid)
   left outer join transactions on members.memid = transactions.memid

   where status not in (1,3,6)$area2 and members.CID = '".$_SESSION['User']['CID']."' group by transactions.memid having ((sum(mem_categories.category) = 0)$amo) order by members.licensee, members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
     if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>

      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" align="center" class="Heading" colspan = "2">Unlist Member Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" align = "right" nowrap colspan = "2"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
 }
}

function status() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[search]) {

 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = "".$_REQUEST['year']."-".$_REQUEST['month']."-01";

 if ($_REQUEST[suspended] && $_REQUEST[suspended2])  {
  $st = "Suspended Members & Suspended & Locked Members ";
 } elseif ($_REQUEST[suspended])  {
  $title = "Suspended Members";
 } elseif($_REQUEST[suspended2])  {
  $title = "Suspended & Locked Members ";
 }

 if ($_REQUEST[suspended] && $_REQUEST[suspended2])  {
  $st = '5,6';
 } elseif ($_REQUEST[suspended])  {
  $st = '5';
 } elseif ($_REQUEST[suspended2])  {
  $st = '6';
 }

 if (!$_REQUEST[letter])  {
  $letter = " and members.letters = 0";
 } else {
  $letter = "";
 }

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading"><?= $title ?></td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" nowrap width = "50"><b>Acc No.:</b></td>
     <td class="Heading2" width = "180" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>L/Traded:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Balance:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Net:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Claimable:</b></td>
     <td align="right" class="Heading2"><b>DEL:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] != 'all') {
   //$area2 = " and licensee = ".$_REQUEST['area']."";
  //} else {
  // $area2 = "";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
    if($_REQUEST['area'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and licensee = ".$_REQUEST['area']."";
    }
  }


  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status = '$st'$letter and CID = '".$_SESSION['User']['CID']."'$area2 group by transactions.memid order by members.memid ASC");
  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID

  from members

   inner join tbl_area_physical on members.area = tbl_area_physical.FieldID
   inner join area on members.licensee = area.FieldID
   left outer join transactions on members.memid = transactions.memid


  where status in ('$st')$letter$area2 and uncon != 'Y' and members.CID = '".$_SESSION['User']['CID']."'$area2

  group by transactions.memid
  order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

     $counter = $counter + 1;

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
     if($net < 0)  {
       $claim = (abs($net)+$row[dollarfees]);
     } else {
       $claim = $row[dollarfees];
     }

     if($row[status] == 6 && $row[letters] == 3)  {
       $ready = "Y";
     } else {
       $ready = "N";
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td  nowrap width = "50"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180" ><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[fee_deductions] ?></td>
      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= number_format($claim) ?></td>
      <td  nowrap align = "right"><?= $ready ?></td>
    </tr>
<?
 	$foo++;
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td  nowrap width = "50"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td align="right"></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?}?>
      </td>
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
   <tr colspan="2">
     <td colspan="2" align="center" class="Heading">Members Status Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" width="150" class="Heading2"><b><?= get_word("78") ?>:</b></td>
	 <td align="left"  bgcolor="#FFFFFF">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" width="150" class="Heading2"><b><?= get_word("25") ?>:</b></td>
	 <td align="left"  bgcolor="#FFFFFF"><select name="area" id="area" onChange="ChangeCountry(this);">
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");

		   if($_SESSION['User']['Area'] == 1) {
		   ?>
            <option value="all">All Area's</option>
		   <?
		   }

           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Suspended:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="suspended" value="1"></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Suspended N/Log:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="suspended2" checked value="1"></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Include Letter Mem:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="letter" value="1"></td>
   </tr>
   <tr>
     <td width="150" class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}
}

function soli() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[search]) {

 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = "".$_REQUEST['year']."-".$_REQUEST['month']."-01";


?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading"><?= $title ?></td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" nowrap width = "50"><b>Acc No.:</b></td>
     <td class="Heading2" width = "180" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>L/Traded:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Balance:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Net:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Claimable:</b></td>
     <td align="right" class="Heading2"><b>DEL:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] != 'all') {
   //$area2 = " and licensee = ".$_REQUEST['area']."";
  //} else {
  // $area2 = "";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
    if($_REQUEST['area'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and licensee = ".$_REQUEST['area']."";
    }
  }


  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status = '$st'$letter and CID = '".$_SESSION['User']['CID']."'$area2 group by transactions.memid order by members.memid ASC");
  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID

  from members

   inner join tbl_area_physical on members.area = tbl_area_physical.FieldID
   inner join area on members.licensee = area.FieldID
   left outer join transactions on members.memid = transactions.memid


  where letters = 6 $area2 and status not in(1,3) uncon != 'Y' and members.CID = '".$_SESSION['User']['CID']."'$area2

  group by transactions.memid
  order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

     $counter = $counter + 1;

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
     if($net < 0)  {
       $claim = (abs($net)+$row[dollarfees]);
     } else {
       $claim = $row[dollarfees];
     }

     if($row[status] == 6 && $row[letters] == 3)  {
       $ready = "Y";
     } else {
       $ready = "N";
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td  nowrap width = "50"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180" ><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[fee_deductions] ?></td>
      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= number_format($claim) ?></td>
      <td  nowrap align = "right"><?= $ready ?></td>
    </tr>
<?
 	$foo++;
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td  nowrap width = "50"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td align="right"></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?}?>
      </td>
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
   <tr colspan="2">
     <td colspan="2" align="center" class="Heading">Members Status Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" width="150" class="Heading2"><b><?= get_word("78") ?>:</b></td>
	 <td align="left"  bgcolor="#FFFFFF">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" width="150" class="Heading2"><b><?= get_word("25") ?>:</b></td>
	 <td align="left"  bgcolor="#FFFFFF"><select name="area" id="area" onChange="ChangeCountry(this);">
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");

		   if($_SESSION['User']['Area'] == 1) {
		   ?>
            <option value="all">All Area's</option>
		   <?
		   }

           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td width="150" class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}
}
function con() {

 global $time_start;

 $colspan = "5";
 if($_REQUEST['area'])  {

?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Non Contacted Members Report - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>L/Traded:</b></td>
     <td class="Heading2" align = "right"><b>L/Contacted:</b></td>
   </tr>
<?

  $foo = 0;
  //$area2 = " and licensee in (".get_areas_allowed(true).")";
  //$area2 = " and licensee = ".$_REQUEST['area']."";
  if($_REQUEST['regional']) {
  	if($_REQUEST['regional'] == 'all') {
  	 $area2 = "";
  	} else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = ( $_REQUEST['area'] == 'all' ) ? '' : " and licensee = ".$_REQUEST['area']."";
  }

  //$query = dbRead("SELECT notes.memid, companyname, phoneno, status, max( notes.date )  AS LastOfdate, RegionalID FROM notes, members, tbl_area_physical Where (notes.memid = members.memid) and (members.area = tbl_area_physical.FieldID) and members.CID = ".$_SESSION['User']['CID']." and notes.type = 2 and notes.userid != 180 and Status not in (1,2,3,6)$area2 GROUP  BY notes.memid HAVING ( ( ( max( notes.date )  ) <  '".$_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day']."' ) )");
  $query = dbRead("SELECT memid, companyname, phoneno, status, date_per, RegionalID FROM members, tbl_area_physical Where (members.area = tbl_area_physical.FieldID) and members.CID = ".$_SESSION['User']['CID']." and Status not in (1,2,3,6)$area2 and date_per <  '".$_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day']."' ");
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status not in (1,3,6) and bdriven = 'Y'$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
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
     <td colspan="2" width="100%" align="center" class="Heading">Non Contacted Members Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td width="40%" align="right" class="Heading2"><b><?= get_word("78") ?>:</b></td>
	 <td align="left" bgcolor="#FFFFFF">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td width="40%" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
	 <td align="left" bgcolor="#FFFFFF">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
  <tr>
    <td width="40%" align="right" class="Heading2"><b>Not Contacted Since:</b></td>
    <td bgcolor="#FFFFFF">
      <select name="day">
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
      <select name="month">
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
	    form_select('year',$query,'','',date("Y"));

	   	?>
    </td>
  </tr>

   <tr>
     <td bgcolor="#FFFFFF" colspan="2" align = "right" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?

 }
}

function owing() {

 global $time_start;

 if($_REQUEST['amount'])  {
  $amo = $_REQUEST['amount'];
 } else {
  $amo = "";
 }

 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Unlisted Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] == 'all') {
    //$area2 = "";
  //} else {
    //$area2 = " and licensee = ".$_REQUEST['area']."";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  //} elseif($_REQUEST['regional']) {
   //$area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['state']) {
    if($_REQUEST['state'] == 'all') {
     $area2 = " ";
    } else {
 	 $area2 = " and tbl_area_regional.StateID = ".$_REQUEST['state']."";
 	}
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = " ";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = " and licensee = ".$_REQUEST['area']."";
  }


 $date32 = date("Y-m-d", mktime(0,0,1,date("m"),1-1,date("Y")));
 $query = dbRead("select sum(currentfees+currentpaid+overduefees) as minv, invoice.memid, companyname, status, overdraft, reoverdraft, date_per, priority  from invoice, members, area where invoice.memid = members.memid and members.licensee = area.FieldID and date = '".$date32."' and status not in (1,3,6)$area2 and members.CID = '".$_SESSION['User']['CID']."' group by invoice.memid ");


  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {
 $date31 = date("Y-m", mktime(0,0,1,date("m"),date("d"),date("Y")));
 $getpaidbal = dbRead("select sum(dollarfees) as nf from transactions where dis_date like '".$date31."-%' and dollarfees < 0 AND (to_memid NOT IN (".get_non_included_accounts($_SESSION['Country']['countryID'],true,false,false,true).")) and memid='".$row['memid']."' ");
 $paidrow = mysql_fetch_assoc($getpaidbal);

 if($_REQUEST['amount']) {
  $amount2 = $_REQUEST['amount'];
 } else {
  $amount2 = 20;
 }
 $nowing = $row['minv']+$paidrow['nf'];
 if($nowing > $amount2) {

	 $counter = $counter + 1;
     if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($nowing,2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
 	}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>

      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" align="center" class="Heading" colspan = "2">Unlist Member Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
      <td height="30" align="right" class="Heading2" width="150"><b>State:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="state"><option selected value="all">All States</option>
		<?
		$dbgetarea=dbRead("select * from tbl_area_states where CID = '".$_SESSION['Country']['countryID']."' and StateName != '' group by StateName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['FieldID']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['StateName'] ?></option>
			<?
		}
		?>
	  </select>&nbsp;</td>
   </tr>
    <tr>
	    <td width="150" align="right" class="Heading2"><b>With Fees Over $:</b></td>
        <td bgcolor="#FFFFFF" width="600">
        <input type="text" name="amount" size="20">
	    </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" align = "right" nowrap colspan = "2"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
 }
}

function fac() {

if($_REQUEST['search'])  {

 if($_REQUEST['amount'])  {
  if($_REQUEST['overdraft'] && $_REQUEST['reoverdraft']) {
    $amo = " and ((members.overdraft >= ".$_REQUEST['amount'].") or (members.reoverdraft >= ".$_REQUEST['amount']."))";
  } elseif($_REQUEST['overdraft']) {
    $amo = " and members.overdraft >= ".$_REQUEST['amount'];
  } elseif($_REQUEST['reoverdraft']) {
    $amo = " and members.reoverdraft >= ".$_REQUEST['amount'];
  } else {
    $amo = " and members.reoverdraft < 0";
  }
 } else {
    //$amo = "";
    $amo = " and members.reoverdraft < 0";
 }

// if($_REQUEST['lic'])  {
 // $li = " and members.licensee = ".$_REQUEST['lic'];
// } else {
 // $li = "";
 //}

  if($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
  	 $li = "";
  	} else {
     $li = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $li = " and members.licensee = ".$_REQUEST['lic']."";
  }

 $dbquery = dbRead("select ((sum(sell)-sum(buy))-overdraft) as net, sum(transactions.buy) as TradeAmount, sum(transactions.sell) as STradeAmount, members.*, RegionalID from transactions, members, tbl_area_physical where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID)$li and transactions.type IN (1,2) and members.CID = '".$_SESSION['Country']['countryID']."' and status not in (1,3,6)$amo group by transactions.memid order by members.memid");
?>

<table width="610" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="610" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td colspan="7" align="center" class="Heading2">Members with Facility of <?= $_REQUEST['amount'] ?> and over - </td>
	</tr>
  <tr>
    <td align="left" class="Heading2"><b><?= get_word("1") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("5") ?></b></td>
    <td align="left" class="Heading2"><b><?= get_word("12")  ?></b></td>
    <td align="right" class="Heading2"><b></b></td>
    <td align="right" class="Heading2"><b><?= get_word("53") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_word("54") ?></b></td>
    <td align="right" class="Heading2"><b><?= get_word("55") ?></b></td>
  </tr>
<?

$foo= 0;
$totalfac = 0;
$totalrefac = 0;
$totalnet =0;
$counter = 0;

while($row = mysql_fetch_assoc($dbquery))  {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

    $bal = $row[STradeAmount]-$row[TradeAmount];

?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $row[status] ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($bal,2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[overdraft],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[reoverdraft],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row[net],2) ?></td>
  </tr>
<?

 $totalfac += $row[overdraft];
 $totalrefac += $row[reoverdraft];
 $totalnet += $row[net];
 $foo++;
 $counter++;
}

?>
 <tr>
  <td colspan="2" align="right" bgcolor="#FFFFFF"><b>No of Members: <?= $counter ?></b></td>
  <td colspan="2" align="right" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:</b></td>
  <td align="right" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalfac,2) ?></td>
  <td align="right" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalrefac,2) ?></td>
  <td align="right" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency']?><?= number_format($totalnet,2) ?></td>
 </tr>
</table>
</td>
</tr>
</table>

<?
die;
} else {
?>

<html>
<body>

<form method="get" action="/includes/reports_daily.php">
<input type="hidden" name="search" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2">Facility Report</td>
	</tr>
	</tr>
	   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	    <td width="200" align="right" class="Heading2"><b><?= get_word("78") ?>:</b></td>
        <td bgcolor="#FFFFFF" width="550">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>

       <tr>
	    <td width="200" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
        <td bgcolor="#FFFFFF" width="550">
          <select  name="lic" >
          <?
          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
              ?>
		  	  <option value="">All Areas</option>
		      <?
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		  }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID from area where `drop` = 'Y' and area.CID=".$_SESSION['User']['CID']."$areas order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <tr>
	    <td width="200" align="right" class="Heading2"><b>With Facility Amount or More:</b></td>
        <td bgcolor="#FFFFFF" width="550">
        <input type="text" name="amount" size="20">
	    </td>
    </tr>
   <tr>
     <td align="right" width="200" class="Heading2"><b>include G/S Overdraft:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="overdraft" value="1" checked value="1"></td>
   </tr>
   <tr>
     <td align="right" width="200" class="Heading2"><b>include R/E Overdraft:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="reoverdraft" value="ON"></td>
   </tr>
	<tr>
		<td width="200" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="<?= get_word("83") ?>" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

<?
}
}

function club() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[search]) {

 //$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
 //$date2 = "".$_REQUEST['year']."-".$_REQUEST['month']."-01";

 if ($_REQUEST[fifty] && $_REQUEST[gold])  {
  $title = 'Club Members';
 } elseif ($_REQUEST[fifty])  {
  $title = "50% Club Members";
 } elseif($_REQUEST[gold])  {
  $title = "Gold Club Members";
 }

 if ($_REQUEST[fifty] && $_REQUEST[gold])  {
  $st = "1,2";
 } elseif ($_REQUEST[fifty])  {
  $st = '1';
 } elseif ($_REQUEST[gold])  {
  $st = '2';
 }

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading"><?= $title ?> - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" nowrap width = "50"><b>Acc No.:</b></td>
     <td class="Heading2" width = "180" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Balance:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Net:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Claimable:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] != 'all') {
  // $area2 = " and licensee = ".$_REQUEST['area']."";
  //} else {
  // $area2 = "";
  //}

  if($_REQUEST['user']) {
    $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
	 $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
	}
  } else {
   if($_REQUEST['area'] == 'all') {
    $area2 = " ";
   } else {
    $area2 = " and licensee = ".$_REQUEST['area']."";
   }
  }

  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status = '$st'$letter and CID = '".$_SESSION['User']['CID']."'$area2 group by transactions.memid order by members.memid ASC");
  if($_REQUEST['vcfl']) {
   $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID from members, transactions, tbl_area_physical, area, mem_categories where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID) and (members.licensee = area.FieldID) and (members.memid = mem_categories.memid) and mem_categories.description like '%vcfl%' and status != 1 and members.CID = '".$_SESSION['User']['CID']."'$area2 group by transactions.memid order by members.licensee, members.memid ASC");
  } else {
   $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, RegionalID from members, transactions, tbl_area_physical, area where (members.memid = transactions.memid) and (members.area = tbl_area_physical.FieldID) and (members.licensee = area.FieldID) and fiftyclub in ($st) and members.CID = '".$_SESSION['User']['CID']."'$area2 group by transactions.memid order by members.licensee, members.memid ASC");
  }

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   $c =0;
   while($row = mysql_fetch_array($query)) {

     $counter = $counter + 1;
     if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
     if($net < 0)  {
       $claim = (abs($net)+$row[dollarfees]);
     } else {
       $claim = $row[dollarfees];
     }

     if($row[status] == 6 && $row[letters] == 3)  {
       $ready = "Y";
     } else {
       $ready = "N";
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td  nowrap width = "50"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180" ><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[fee_deductions] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= number_format($claim) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?
 	$foo++;
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td  nowrap width = "50"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?}?>
      </td>
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
   <tr colspan="2">
     <td colspan="2" align="center" class="Heading">Club Members Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" width="150" class="Heading2"><b><?= get_word("78") ?>:</b></td>
	 <td align="left"  bgcolor="#FFFFFF"><select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" width="150" class="Heading2"><b><?= get_word("25") ?>:</b></td>
	 <td align="left"  bgcolor="#FFFFFF"><select name="area" id="area" onChange="ChangeCountry(this);">
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");

		   if($_SESSION['User']['Area'] == 1) {
		   ?>
            <option value="all">All Area's</option>
		   <?
		   }

           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Gold Club:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="gold" value="1"></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>50% Club:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="fifty" checked value="1"></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>VCFL:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="vcfl"></td>
   </tr>
   <tr>
     <td width="150" class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}
}

function act()  {

$thismonth=date("n");
$thisyear=date("Y");
add_kpi("64", "0");

if($_REQUEST['pm'])  {

 st();
die;
}

?>
<html>
<body>
</form>
<form method="get" action="/monthly/activity.php">
<input type="hidden" name="search" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
	<?if(checkmodule("SuperUser")) {?>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID'],'Select Country');
          ?>
      </td>
    </tr>
    <?}?>
	</tr>
       <tr>
	    <td width="150" align="right" class="Heading2"><b><?= get_word("25") ?>:</b></td>
        <td bgcolor="#FFFFFF" width="600">
          <select  name="lic" >
           <?

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
          ?>
           <option value="">All Areas</option>
          <?
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		  }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID from area where `drop` = 'Y' and area.CID=".$_SESSION['User']['CID']."$areas order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
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
</html>
<?
}
?>
</form>

<?

function equity() {

 global $time_start;

 if($_REQUEST['amount'])  {
  //$amo = " and (((sum(sell)-sum(buy))-members.overdraft-members.reoverdraft) > ".$_REQUEST['amount'].")";
  $amo = " having (((sum(sell)-sum(buy))-members.overdraft-members.reoverdraft) > ".$_REQUEST['amount'].") ";
 } else {
  $amo = "";
 }


 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Listed Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] == 'all') {
    //$area2 = "";
  //} else {
    //$area2 = " and licensee = ".$_REQUEST['area']."";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = " and licensee = ".$_REQUEST['area']."";
  }

 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having (sum(mem_categories.category) = 0) order by members.memid ASC");
 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,4,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having ((sum(mem_categories.category) = 0)$amo) order by members.memid ASC");
   $query = dbRead("
   select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat, RegionalID

   from members

   inner join tbl_area_physical on members.area = tbl_area_physical.FieldID
   inner join area on members.licensee = area.FieldID
   left outer join mem_categories on (members.memid = mem_categories.memid)
   left outer join transactions on members.memid = transactions.memid

   where status not in (1,3,6)$area2 and members.CID = '".$_SESSION['User']['CID']."' group by members.memid $amo order by members.licensee, members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
	 if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];
     if($net < 0) {
	 $ntotal+=$net;
	 }


     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[contactname] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= $row[phonearea] ?></td>
      <td  nowrap align = "right"><?= $row[phoneno] ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ntotal)?></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" align="center" class="Heading" colspan = "2">List Member Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <tr>
	    <td width="150" align="right" class="Heading2"><b>With Equity Over $:</b></td>
        <td bgcolor="#FFFFFF" width="600">
        <input type="text" name="amount" size="20">
	    </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" align = "right" nowrap colspan = "2"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
 }
}

function dd() {

 global $time_start;



 $colspan = "10";
 if($_REQUEST['area'] || $_REQUEST['regional'] || $_REQUEST['user'])  {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Listed Members - <?= get_area($_REQUEST['area'])?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>% Date:</b></td>
	 <td align="right" class="Heading2"><b>P/ty:</b></td>
   </tr>
<?

  $foo = 0;

  //if($_REQUEST['area'] == 'all') {
    //$area2 = "";
  //} else {
    //$area2 = " and licensee = ".$_REQUEST['area']."";
  //}

  if($_REQUEST['user']) {
   $area2 = " and user = ".$_REQUEST['user']."";
  } elseif($_REQUEST['regional']) {
    if($_REQUEST['regional'] == 'all') {
     $area2 = "";
    } else {
     $area2 = " and RegionalID = ".$_REQUEST['regional']."";
    }
  } else {
   $area2 = ( $_REQUEST['area'] == 'all' ) ? '' : " and licensee = ".$_REQUEST['area']."";
  }

 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having (sum(mem_categories.category) = 0) order by members.memid ASC");
 //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat from members, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and status not in (1,3,4,6)$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid having ((sum(mem_categories.category) = 0)$amo) order by members.memid ASC");
   $query = dbRead("
   select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, sum(mem_categories.category) as cat, RegionalID

   from members

   inner join tbl_area_physical on members.area = tbl_area_physical.FieldID
   inner join area on members.licensee = area.FieldID
   left outer join mem_categories on (members.memid = mem_categories.memid)
   left outer join transactions on members.memid = transactions.memid

   where status not in (1,3,6)$area2$cc and paymenttype = 0 and members.CID = '".$_SESSION['User']['CID']."' group by members.memid order by members.licensee, members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   $c = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
	 if($row[priority] == 0) {
	   $c++;
	 }

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];
	 $ntotal+=$net;

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row[status] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[date_per] ?></td>
	  <td  nowrap align = "right"><?= $row[priority] ?></td>
    </tr>
<?

 	$foo++;
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ntotal)?></td>
      <td align="right"></td>
	  <td  nowrap align = "right"><?= $c ?></td>
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
     <td width="100%" align="center" class="Heading" colspan = "2">List Member Report</td>
   </tr>
   <?if($_SESSION['User']['Area'] == 1) {?>
	<tr>
	 <td align="right" class="Heading2">User:</td>
	 	 <td bgcolor="#FFFFFF" width="600"><select name="user">
		  <option value="">Select User</option>
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
	 <td align="right" class="Heading2"><?= get_word("78") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="regional" id="area" onChange="ChangeCountry(this);">
            <option value="">Select Area</option>
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option value="all">All Area's</option>
          <?
          }
           $query2 = dbRead("select RegionalName, FieldID from tbl_area_regional where CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?}?>
	<tr>
	 <td align="right" class="Heading2"><?= get_word("25") ?>:</td>
	 <td bgcolor="#FFFFFF" width="600">
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if(checkmodule("EditMemberLevel2"))  {
          ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="all">All Area's</option>
          <?
          }
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

           $query2 = dbRead("select place,FieldID from area where `drop` = 'Y' and CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
   <tr>
     <td bgcolor="#FFFFFF" align = "right" nowrap colspan = "2"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
 }
}

function get_area($id) {

  if($id != 'all') {
     $query = dbRead("select * from area where FieldID = ".$id."");
	 $row = mysql_fetch_array($query);
  }

	 return $row['place'];
}
?>