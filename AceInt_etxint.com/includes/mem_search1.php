<?

if(!checkmodule("MemberSearch")) {

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

if($_REQUEST['countryid']) {
 $GET_CID = $_REQUEST['countryid'];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

$Cquery=dbRead("select * from country where countryID = '$GET_CID'");
$Crow = mysql_fetch_assoc($Cquery);

if($_REQUEST['memsearch']) {

 // log this if they have log on.
 
 if(checkmodule("Log")) {
  
  add_kpi("1","0");
  
}

 $time_start = getmicrotime();

 if($_REQUEST['f'] == "companyname") {
  $fff = "(companyname like '%".encode_text2(addslashes(trim($_REQUEST['data'])))."%' or contactname like '%".encode_text2(addslashes(trim($_REQUEST['data'])))."%' or regname like '%".encode_text2(addslashes(trim($_REQUEST['data'])))."%' or accholder like '%".encode_text2(addslashes(trim($_REQUEST['data'])))."%')";
 } elseif($_REQUEST['f'] == "emailaddress") {
  $fff = "tbl_members_email.email like '%".encode_text2(addslashes(trim($_REQUEST['data'])))."%'";
 } else {
  $fff = "".addslashes($_REQUEST['f'])." like '%".encode_text2(addslashes(trim($_REQUEST['data'])))."%'";
 }

 if($_REQUEST['deactive']) {
  $blahblah = "Deactive";
 } else {
  $blahblah = "23144123423";
 }
 
 if($_REQUEST['fifty']) {
  $fifty = " AND (members.fiftyclub = 1)";
 } else {
  $fifty = "";  
 } 
 
 if($_REQUEST['fifty'] && $_REQUEST['gold']) {
   $fifty = " and members.fiftyclub in (1,2)";
 } elseif ($_REQUEST['fifty']) {
   $fifty = " and members.fiftyclub in (1,2)";
 } elseif ($_REQUEST['gold']) {
   $fifty = " and members.fiftyclub = 2"; 
 } else {
   $fifty = "";
 } 

 if($_REQUEST['licareaid']) {
 
 
 }
 
 if($_REQUEST['areaid']) {
  //$count = 0;
  //$query = dbRead("select FieldID from area where disarea = '".addslashes($_REQUEST['areaid'])."'");
  //while($row = mysql_fetch_assoc($query)) {
   //if($count == 0) {
    //$andor = "";
   //} else {
    //$andor = "or";
   //}
   //$area_array .= " ".$andor." area='".$row['FieldID']."'";
   //$count++;
  //}
  $area_array = "tbl_area_physical.FieldID = ".$_REQUEST['areaid']."";
 }

 //if($_REQUEST['otherarea']) {
  //$searcharea = "licensee";
 //} else {
  //$searcharea = "area";
 //}
 
 $curm = date("n");
 $cury = date("Y");
 
 $query3 = dbRead("select memid from notes group by memid ASC");
 while($row3 = mysql_fetch_assoc($query3)) {
  $notesarray[$row3['memid']] = 1;
 }

 if($_REQUEST['disareaid']) {
  //$count=0;
  //$query=dbRead("select FieldID from area where disarea = '".addslashes($_REQUEST['disareaid'])."'");
  //while($row = mysql_fetch_assoc($query)) {
   //if($count == 0) {
    //$andor="";
   //} else {
    //$andor="or";
   //}
   //$area_array.=" ".$andor." area='".$row['FieldID']."'";
   //$count++;
  //}
  $area_array = "tbl_area_regional.FieldID = ".$_REQUEST['disareaid']."";
 }
 
 
 

 if($_REQUEST['stateid']) {
 
  //$State = " and area.state like '".$_REQUEST['state']."' ";
  //$StateLink = "(members.$searcharea = area.FieldID) and ";
  //$AreaTable = ", area";
  $State = " and tbl_area_states.FieldID = '".$_REQUEST['stateid']."' ";  

 }
 
 $StateLink = "(members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and ";
 $AreaTable = ", tbl_area_physical, tbl_area_regional, tbl_area_states"; 

 if($_REQUEST['catid'] && $_REQUEST['licareaid']) {
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, mem_categories.description as descrip, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and (mem_categories.category IN (".addslashes($_REQUEST['catid']).")) and members.licensee = '".addslashes($_REQUEST['licareaid'])."' and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } elseif($_REQUEST['catid'] && $_REQUEST['areaid']) {
 
	$querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_physical,tbl_area_regional,tbl_area_states WHERE (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_physical.FieldID IN (".$_REQUEST['areaid'].")) group by tbl_area_states.FieldID");
	$count = 0;
	$STID = "";
	while($rowstate = mysql_fetch_assoc($querystate)) { 
	 if($count == 0) {
	  $andor="";
	 } else {
	  $andor=",";
	 }       
	  $STID.= "".$andor."".$rowstate['FieldID'];
	  $count++;
	}
	  
	if(checkmodule("EditMemberLevel2")) {
	
		$nationalMem = "or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))";
	
	}
	  
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, mem_categories.description as descrip, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and (mem_categories.category IN (".addslashes($_REQUEST['catid']).")) and ((tbl_area_physical.FieldID = ".addslashes($_REQUEST['areaid']).") $nationalMem ) and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } elseif($_REQUEST['catid'] && $_REQUEST['disareaid']) {
 
  $querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_regional,tbl_area_states WHERE (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_regional.FieldID = '".$_REQUEST['disareaid']."') group by tbl_area_states.FieldID");
  $rowstate = mysql_fetch_assoc($querystate);

	if(checkmodule("EditMemberLevel2")) {
	
		$nationalMem = "or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$rowstate['FieldID']."))";
	
	}
  
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, mem_categories.description as descrip, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and (mem_categories.category IN (".addslashes($_REQUEST['catid']).")) and (($area_array) $nationalMem ) and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } elseif($_REQUEST['licareaid']) {
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and members.licensee = '".addslashes($_REQUEST['licareaid'])."' and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } elseif($_REQUEST['areaid']) {
 
	$querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_physical,tbl_area_regional,tbl_area_states WHERE (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_physical.FieldID IN (".$_REQUEST['areaid'].")) group by tbl_area_states.FieldID");
	$count = 0;
	$STID = "";
	while($rowstate = mysql_fetch_assoc($querystate)) { 
	 if($count == 0) {
	  $andor="";
	 } else {
	  $andor=",";
	 }       
	  $STID.= "".$andor."".$rowstate['FieldID'];
	  $count++;
	}
	  
	if(checkmodule("EditMemberLevel2")) {
	
		$nationalMem = "or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))";
	
	}
	 
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and ((tbl_area_physical.FieldID = ".addslashes($_REQUEST['areaid']).") $nationalMem ) and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } elseif($_REQUEST['disareaid']) {
 
  $querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_regional,tbl_area_states WHERE (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_regional.FieldID = '".$_REQUEST['disareaid']."') group by tbl_area_states.FieldID");
  $rowstate = mysql_fetch_assoc($querystate);
  
	if(checkmodule("EditMemberLevel2")) {
	
		$nationalMem = "or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$rowstate['FieldID']."))";
	
	}
  
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and (($area_array) $nationalMem ) and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } elseif($_REQUEST['catid']) {
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, mem_categories.description as descrip, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and (mem_categories.category IN (".addslashes($_REQUEST['catid']).")) and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 } else {
  $dbgetdataout = dbRead("select members.memid as memid, members.companyname as companyname, members.contactname as contactname, members.phoneno as phoneno, members.city as city, members.phonearea as phonearea, members.status as status, members.trade_per as trade_per, members.date_per as date_per, max(mem_categories.category) as cat1, members.letters as letters, members.bdriven as bdriven, members.t_unlist as t_unlist, members.CID as CID, members.fiftyclub, status.* from members, status$AreaTable left outer join mem_categories on (members.memid = mem_categories.memid) left outer join tbl_members_email on (tbl_members_email.acc_no = members.memid) where $StateLink(members.status = status.FieldID) and $fff and status.Type not like '$blahblah'$fifty $State and members.CID like '$GET_CID' group by members.memid order by companyname ASC");
 }

?>
<html>
<head>
<script>

function notes(URL) {
var exitwin = "toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=400";
selectedURL = URL;                
remotecontrol = window.open(selectedURL, "exit_console", exitwin);
remotecontrol.focus();
}

function ChangeCountry(list) {
 var url = 'body.php?page=mem_search&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body<? if(!$_REQUEST['memsearch']) { ?> onload="javascript:setFocus('frm','data');" <? } ?>>

<form method="get" action="body.php?page=mem_search" name="frm">
<input type="hidden" name="memsearch" value="1">
<input type="hidden" name="page" value="mem_search">
<input type="hidden" name="countryid" value="<?= $GET_CID ?>">
<table width="620" cellspacing="0" cellpadding="1" border="0">
<tr>
<td class="Border">
  <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse" bordercolor="#111111">
    <tr>
     <td colspan="5" align="center" class="Heading2"><?= get_page_data("1") ?></td>
    </tr>
  <tr> 
      <td align="right" class="Heading2" width="100"><b><?= get_word("85") ?>:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="data" id=data0 value="<?= encode_text2(trim($_REQUEST['data'])) ?>" size="35" tabindex="1"></td>
      <td align="right" class="Heading2" colspan="2"><b><?= get_word("86") ?>:</b></td>
      <td bgcolor="#FFFFFF"> 
        <select name="f">
          <option <? if ($_REQUEST['f'] == "phoneno") { echo "selected "; } ?>value="phoneno"><?= get_word("7") ?></option>
          <option <? if ($_REQUEST['f'] == "faxno") { echo "selected "; } ?>value="faxno"><?= get_word("8") ?></option>
          <option <? if ($_REQUEST['f'] == "members.memid") { echo "selected "; } ?>value="members.memid"><?= get_word("1") ?></option>
          <option <? if ($_REQUEST['f'] == "city") { echo "selected "; } ?>value="city"><?= get_word("16") ?></option>
          <option <? if ($_REQUEST['f'] == "status") { echo "selected "; } ?>value="status"><?= get_word("12") ?></option>
          <option <? if ($_REQUEST['f'] == "postcode") { echo "selected "; } ?>value="postcode"><?= get_word("18") ?></option>
          <option <? if ($_REQUEST['f'] == "companyname") { echo "selected "; } ?>value="companyname"><?= get_word("3") ?></option>
          <option <? if ($_REQUEST['f'] == "emailaddress") { echo "selected "; } ?>value="emailaddress"><?= get_word("9") ?></option>
          <option <? if ($_REQUEST['f'] == "mem_categories.description") { echo "selected "; } ?>value="mem_categories.description"><?= get_word("27") ?></option>
          <option <? if ($_REQUEST['f'] == "streetname") { echo "selected "; } ?>value="streetname"><?= get_word("14") ?></option>
          <option <? if ($_REQUEST['f'] == "oldcompanyname") { echo "selected "; } ?>value="oldcompanyname">Old Company Name</option>
        </select>
      </td>
  </tr>
  <tr> 
      <td width="100" height="30" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="catid"><option selected value=""><?= get_word("160") ?></option>
<?
	    if(($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') || ($_SESSION['User']['lang_code'] != $Crow['Langcode'] && $Crow['english'] == 'N')) {
	      $cat = " order by engcategory";
	      $sel = " engcategory as category, catid as catid ";
	    } else {
	      $cat = " order by category";
	      $sel = " category, catid ";       
	    }
	       	  
		//$dbgetcat = dbRead("select catid, category from categories where CID like '".addslashes($GET_CID)."' order by category ASC");
		$dbgetcat=dbRead("select $sel from categories where CID like '".addslashes($GET_CID)."'$cat ASC");
		while($row = mysql_fetch_assoc($dbgetcat)) {
			?>
			<option <? if ($row['catid'] == $_REQUEST['catid']) { echo "selected ";} ?>value="<?= $row['catid'] ?>"><?= $row['category'] ?></option>
			<?
		}
?>
	  </select> </td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left"><?= get_page_data("2") ?><input type="checkbox" name="deactive" <? if($_REQUEST['deactive']) { echo "checked "; } ?> value="ON"></td>
  </tr>
    <tr>
      <td height="30" align="right" class="Heading2" width="100"><b><?= get_page_data("12") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="licareaid"><option selected value=""><?= get_word("161") ?></option>
<?
	
		$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($GET_CID)."' order by place ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['licareaid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['place'] ?></option>
			<?
		}
		
?>
	  </select>&nbsp;</td>
<?if($_SESSION['Country']['club'] == 1) {?> 		  
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left">50% Club Members <input type="checkbox" name="fifty" <? if($_REQUEST['fifty']) { echo "checked "; } ?> value="OFF"></td>
<?} else {?>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left"></td>
<?}?>
    </tr>
    <tr>
      <td width="100" height="30" align="right" class="Heading2"><b><?= get_word("24") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="areaid"><option selected value=""><?= get_word("161") ?></option>
<?
		$dbgetarea=dbRead("select tbl_area_physical.FieldID, tbl_area_physical.AreaName from tbl_area_physical,tbl_area_regional,tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID like '".addslashes($GET_CID)."' order by AreaName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['areaid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['AreaName'] ?></option>
			<?
		
	  }
	  
?>
	  </select>&nbsp;</td>
<?if($_SESSION['Country']['club'] == 1) {?> 	  
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left">Gold Club Members <input type="checkbox" name="gold" <? if($_REQUEST['gold']) { echo "checked "; } ?> value="OFF"></td>
<?} else {?>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left"></td>
<?}?>      </tr> 
    <tr>
      <td height="30" align="right" class="Heading2" width="100"><b><?= get_word("78") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="disareaid"><option selected value=""><?= get_word("161") ?></option>
<?
		//$dbgetarea=dbRead("select disarea from area where CID like '".addslashes($GET_CID)."' group by disarea ASC");
		$dbgetarea=dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID = ".addslashes($GET_CID)." order by RegionalName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['disareaid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['RegionalName'] ?></option>
			<?
		}
		
?>
	  </select>&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="right">&nbsp;</td>
    </tr>
    <tr>
      <td height="30" align="right" class="Heading2" width="100"><b><?= get_word("17") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="stateid"><option selected value=""><?= get_word("162") ?></option>
<?
		//$dbgetarea=dbRead("select state from area where CID like '".addslashes($GET_CID)."' and state != '' group by state ASC");
		$dbgetarea=dbRead("select FieldID, StateName from tbl_area_states where CID like '".addslashes($GET_CID)."' Order by StateName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['stateid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['StateName'] ?></option>
			<?
		}
		
?>
	  </select>&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF" colspan="3">&nbsp;</td>
    </tr>
  <tr> 
      <td height="30" align="right" class="Heading2" width="100"><b><?= get_word("79") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
	
		$dbgetarea=dbRead("select * from country order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['countryID'] == $GET_CID) { echo "selected "; } ?>value="<?= $row['countryID'] ?>"><?= $row['name'] ?></option>
			<?
		}
		
		$counter = mysql_num_rows($dbgetdataout);
		
?>
	  </select>&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="right">&nbsp;</td>
  </tr>
  <tr> 
      <td width="100" height="30" class="Heading2"></td>
      <td width="450" height="30" colspan="4" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>">&nbsp;<input type="Submit" name="emsa" value="<?= get_page_data("5") ?>">&nbsp;<input type="Submit" name="vssa" value="<?= get_page_data("6") ?>">&nbsp;<? if(checkmodule("PrintCheque")) { ?><input type="Submit" name="pcheque" value="<?= get_page_data("7") ?>"><? } ?></td>
  </tr>
</table>
</td>
</tr>
</table>

<br>


<table width="620" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td align="center" bgcolor="#FFFFFF"><a href="javascript:print()" class="nav"><?= get_word("87") ?></a><br>Number of Results: <b><?= $counter ?></b></td>
	</tr>
</table>
<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="620" border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="10" align="center" class="Heading"><?= get_page_data("8") ?>.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("1") ?>:</b></td>
		<td width="176" class="Heading2"><b><?= get_word("3") ?>:</b></td>
		<td width="150" class="Heading2"><b><?= get_word("5") ?>:</b></td>
		<td width="92" class="Heading2"><b><?= get_word("16") ?>:</b></td>
		<td width="118" class="Heading2"><b><?= get_word("7") ?>:</b></td>
		<td width="12" class="Heading2">&nbsp;</td>
		<td width="13" class="Heading2">&nbsp;</td>
		<td width="13" class="Heading2">&nbsp;</td>
		<td width="13" class="Heading2">&nbsp;</td>
		<td width="13" class="Heading2">&nbsp;</td>
	</tr>

<?

$foo=0;

while($row = mysql_fetch_assoc($dbgetdataout)) {
 
 $count++;

 if($row['status'] == 1) {
  $let = "D";
 } elseif($row['status'] == 0) {
  $let = "A";
 } elseif($row['status'] == 2) {
  $let = "C";
 } elseif($row['status'] == 3) {
  $let = "S";
 } elseif($row['status'] == 4) {
  $let = "P";
 } elseif($row['status'] == 5) {
  $let = "U";
 } elseif($row['status'] == 6) {
  $let = "L";
 }

if($row['status'] != 6 && $row['status'] != 1) {
 if($row['cat1'] == 0) {
  $let2 = "N";
 } elseif($row['t_unlist'] == 1)  {
  $let2 = "T"; 
 } else {
  $let2 = "L";
 }
} else {
  $let2 = "N";
}

	 if($row['letters'] == 1) {
	  $bgcolor = "#33cc66";
	  $foo % 2  ? 0: $bgcolor = "#009933";
	 } elseif($row[letters] == 2) {
	  $bgcolor = "#0080ff";
	  $foo % 2  ? 0: $bgcolor = "#0050ff";
	 } elseif($row[letters] == 3) {
	  $bgcolor = "#cc00cc";
	  $foo % 2  ? 0: $bgcolor = "#ee00ee";
	 } elseif($row[letters] == 9) {
	  $bgcolor = "#FF4444";
	  $foo % 2  ? 0: $bgcolor = "#FF6666";
	 } elseif($row[letters] == 4) {
	  $bgcolor = "#fffc00";
	  $foo % 2  ? 0: $bgcolor = "#f0d3oc";
	 } else {
	  $bgcolor = "#CCCCCC";
	  $foo % 2  ? 0: $bgcolor = "#EEEEEE";
	 }

	 if($row['bdriven'] == Y) {
	  $frcolor = "#FB800D";
	 } elseif($row['t_unlist'] == 1 || $row['status'] == 6) {
	  $frcolor = "#FFFFFF";
	 } else {
	  $frcolor = "#000000";
	 }

   if($_SESSION['Country']['countryID'] != $row['CID']) {        
	 $areano = (substr($row[phonearea],0,1) != 0) ? $Crow['phoneprefix']." ".$row[phonearea] : $Crow['phoneprefix']." ".substr($row[phonearea],1);
   } else {
	 $areano = $row[phonearea];
   }
   
   if($row['letters'] == 9) {
	 $frcolor2 = "#FFFFFF";     
   } else {
	 $frcolor2 = "#FF0000";   
   } 
   
?>
		<tr id="<?= $row['memid'] ?>" bgcolor="<?= $bgcolor ?>" onmouseover="setPointer(this, <?= $row['memid'] ?>, 'over', '<?= $bgcolor ?>', '#BBBBBB', '#AAAAAA');" onmouseout="setPointer(this, <?= $row['memid'] ?>, 'out', '<?= $bgcolor ?>', '#BBBBBB', '#AAAAAA');" onmousedown="setPointer(this, <?= $row['memid'] ?>, 'click', '<?= $bgcolor ?>', '#BBBBBB', '#AAAAAA');">
			<td width="40"><? if(checkmodule("PrintCheque") && ($_SESSION['Country']['countryID'] == 3 || $_SESSION['Country']['countryID'] == 10)) { ?><a href="includes/printcheque.php?memid=<?= $row['memid'] ?>" class="nav"><? } ?><font color="<?= $frcolor ?>"><?= $row['memid'] ?></font><? if(checkmodule("PrintCheque")) { ?></a><? } ?>&nbsp;</td>
			<td width="176"><font color="<?= $frcolor ?>"><?= $row['companyname'] ?></font>&nbsp;</td>
			<td width="150"><font color="<?= $frcolor ?>"><?= $row['contactname'] ?></font>&nbsp;</td>
			<td width="92"><font color="<?= $frcolor ?>"><?= $row['city'] ?></font>&nbsp;</td>
			<td width="118"><font color="<?= $frcolor ?>"><?= $areano ?> <?= $row['phoneno'] ?></font>&nbsp;</td>
			<td width="12" align="right"><?if($row['fiftyclub'] == 1) {?><b><font color ="<?= $frcolor2 ?>">50%</font></b><?} elseif($row['fiftyclub'] == 2) {?><b><font color ="<?= $frcolor2 ?>">Gold</font></b><?}?></td>
			<td width="13" align="right"><a href="javascript:notes('body.php?page=notes&memid=<?= $row['memid'] ?>')"><img src="images/icon_n<? if($notesarray[$row['memid']] == 1) { echo "2"; } ?>.gif" border="0" width="16" height="15"></a></td>
			<td width="13" align="right"><a href="/includes/redirect.php?data=<?= $row['memid'] ?>&emsa=true"><img src="images/icon_e.gif" border="0" width="16" height="15"></a></td>
			<td width="13" align="left" valign="middle"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pageno=1&tab=tab7&currentmonth=<?= date("n") ?>&numbermonths=1&currentyear=<?= date("Y") ?>&DisplayStatement=1"><img src="images/icon_s.gif" border="0" width="16" height="15"></a></td>
			<td width="13" align="left" valign="middle"><b><?= $let ?><?= $let2 ?></b></td>
		</tr>
		<?if($_REQUEST['catid']) {?>
		 <?if($_SESSION['User']['emcus'] == 1) { ?>
		 <tr id="<?= $row['memid'] . "85478" ?>" bgcolor="<?= $bgcolor ?>" onmouseover="setPointer(this, <?= $row['memid'] . "85478" ?>, 'over', '<?= $bgcolor ?>', '#BBBBBB', '#AAAAAA');" onmouseout="setPointer(this, <?= $row['memid'] . "85478" ?>, 'out', '<?= $bgcolor ?>', '#BBBBBB', '#AAAAAA');" onmousedown="setPointer(this, <?= $row['memid'] . "85478" ?>, 'click', '<?= $bgcolor ?>', '#BBBBBB', '#AAAAAA');">
		  <td colspan="5"><font color="<?= $frcolor ?>"><?= $row['descrip'] ?></font>&nbsp;</td>
		  <td colspan="4"><?= $row['trade_per'] ?>% / <?= date("d-m-y", strtotime($row['date_per'])) ?>&nbsp;</td>
		 </tr>
		 <?}?>
		<?}?>
<?

$foo++;
}

?>
		<tr>
		    <td colspan="10" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end=getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>

</table>
</td>
</tr>
</table>

</body>

</html>

<?

} else {

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'body.php?page=mem_search&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body onload="javascript:setFocus('frm','data');">
<form method="get" action="body.php?page=mem_search" name="frm">
<input type="hidden" name="countryid" value="<?= $GET_CID ?>">
<input type="hidden" name="page" value="mem_search">
<input type="hidden" name="memsearch" value="1">
<? if($_REQUEST['Error']) { ?>
  <table width="620" border="1" bordercolor="#FF0000" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
   <tr>
    <td bgcolor="#FFFFFF" align="center"><b>You must use a valid Account number to click Edit or View member.</b></td>
   </tr>
  </table><br>
<? } ?>
<table width="620" cellspacing="0" cellpadding="1" border="0">
<tr>
<td class="Border">
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr>
     <td colspan="4" align="center" class="Heading2"><?= get_page_data("1") ?></td>
    </tr>
  <tr> 
      <td align="right" width="100" class="Heading2"><b><?= get_word("85") ?>:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="data" id=data size="35" tabindex="1" value="<?= encode_text2(trim($_REQUEST['data'])) ?>"></td>
      <td align="right" class="Heading2"><b><?= get_word("86") ?>:</b></td>
      <td bgcolor="#FFFFFF"> 
        <select name="f">
          <option value="phoneno"><?= get_word("7") ?></option>
          <option value="faxno"><?= get_word("8") ?></option>
          <option value="members.memid"><?= get_word("1") ?></option>
          <option value="city"><?= get_word("16") ?></option>
          <option value="status"><?= get_word("12") ?></option>
          <option value="postcode"><?= get_word("18") ?></option>
          <option selected value="companyname"><?= get_word("3") ?></option>
          <option value="emailaddress"><?= get_word("9") ?></option>
          <option value="mem_categories.description"><?= get_word("27") ?></option>
          <option value="streetname"><?= get_word("14") ?></option>
          <option value="oldcompanyname">Old Company Name</option>
        </select>
      </td>
  </tr>
  <tr> 
      <td width="100" height="30" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="catid"><option selected value=""><?= get_word("160") ?></option>
<?
	    //if($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') {
	    if(($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') || ($_SESSION['User']['lang_code'] != $Crow['Langcode'] && $Crow['english'] == 'N')) {
	       $cat = " order by engcategory";
	       $sel = " engcategory as category, catid as catid ";
	    } else {
	       $cat = " order by category";
	       $sel = " category, catid ";       
	    }
	       
		//$dbgetcat=dbRead("select catid, category from categories where CID like '".addslashes($GET_CID)."' order by category ASC");
		$dbgetcat=dbRead("select $sel from categories where CID like '".addslashes($GET_CID)."'$cat ASC");
		
		while($row = mysql_fetch_assoc($dbgetcat)) {
			?>
			<option value="<?= $row['catid'] ?>"><?= $row['category'] ?></option>
			<?
		}

?>
	  </select> </td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF" align="left"><?= get_page_data("2") ?><input type="checkbox" name="deactive" checked value="ON"></td>
  </tr>
    <tr>
      <td width="100" height="30" align="right" class="Heading2"><b><?= get_page_data("12") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="licareaid"><option selected value=""><?= get_word("161") ?></option>
<?
		$dbgetarea=dbRead("select FieldID, place from area where CID like '".addslashes($GET_CID)."' order by place ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option value="<?= $row['FieldID'] ?>"><?= $row['place'] ?></option>
			<?
		
	  }
	  
?>
	  </select>&nbsp;</td>
<?if($_SESSION['Country']['countryID'] == 1) {?>	  
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left">50% Club Members <input type="checkbox" name="fifty" <? if($_REQUEST['fifty']) { echo "checked "; } ?> value="OFF"></td>
<?} else {?>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left"></td>
<?}?>   
 </tr>
    <tr>
      <td width="100" height="30" align="right" class="Heading2"><b><?= get_word("24") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="areaid"><option selected value=""><?= get_word("161") ?></option>
<?
		$dbgetarea=dbRead("select tbl_area_physical.FieldID, tbl_area_physical.AreaName from tbl_area_physical,tbl_area_regional,tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID like '".addslashes($GET_CID)."' order by AreaName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option value="<?= $row['FieldID'] ?>"><?= $row['AreaName'] ?></option>
			<?
		
	  }
	  
?>
	  </select>&nbsp;</td>
<?if($_SESSION['Country']['club'] == 1) {?>   
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left">Gold Club Members 
      <input type="checkbox" name="gold" <? if($_REQUEST['gold']) { echo "checked "; } ?> value="ON"></td>
<?} else {?>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="left"></td>
<?}?>  
    </tr>
    <tr>
      <td width="100" height="30" align="right" class="Heading2"><b>&nbsp;<?= get_word("78") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="disareaid"><option selected value=""><?= get_word("161") ?></option>
<?
		//$dbgetarea=dbRead("select disarea from area where CID like '".addslashes($GET_CID)."' group by disarea ASC");
		$dbgetarea=dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID = ".addslashes($GET_CID)." order by RegionalName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option value="<?= $row['FieldID'] ?>"><?= $row['RegionalName'] ?></option>
			<?
		
	  }
	  
?>
	  </select>&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF" align="right">&nbsp;</td>
    </tr>
    <tr>
      <td height="30" align="right" class="Heading2" width="100"><b><?= get_word("17") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="stateid"><option selected value=""><?= get_word("162") ?></option>
<?
		//$dbgetarea=dbRead("select state from area where CID like '".addslashes($GET_CID)."' and state != '' group by state ASC");
		$dbgetarea=dbRead("select FieldID, StateName from tbl_area_states where CID like '".addslashes($GET_CID)."' Order by StateName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option value="<?= $row['FieldID'] ?>"><?= $row['StateName'] ?></option>
			<?
		}
		
?>
	  </select>&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF" colspan="2">&nbsp;</td>
    </tr>
  <tr>
      <td width="100" height="30" align="right" class="Heading2"><b>&nbsp;<?= get_word("79") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
		$dbgetarea=dbRead("select * from country order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if(!$_REQUEST[countryid] && $_SESSION['User']['CID'] == $row['countryID']) { echo "selected "; } elseif($GET_CID == $row['countryID']) { echo "selected "; } ?>value="<?= $row['countryID'] ?>"><?= $row['name'] ?></option>
			<?
		
	  }
	  
?>
	  </select>&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF" align="right">&nbsp;</td>
  </tr>
  <tr> 
      <td width="100" height="30" class="Heading2"></td>
      <td width="450" height="30" colspan="3" bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("48") ?>">&nbsp;<input type="Submit" name="emsa" value="<?= get_page_data("5") ?>">&nbsp;<input type="Submit" name="vssa" value="<?= get_page_data("6") ?>">&nbsp;<? if(checkmodule("PrintCheque")) { ?><input type="Submit" name="pcheque" value="<?= get_page_data("7") ?>"><? } ?></td>
  </tr>

</table>
</td>
</tr>
</table>
</form>

<?

include("/virtual/preview/htdocs/events/events-edit.php");

?>

</body>
</html>
<?

}

?>