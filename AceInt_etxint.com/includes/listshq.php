<?

include("class.html.mime.mail.inc");
include("zip.lib.php");

if(!checkmodule("HQEmail") && !checkmodule("LicEmail") && !checkmodule("PrintLabels")) {
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

<form method="POST" action="body.php?page=listshq&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?

// Some Setup.

 //$tabarray = array('Email List','Labels','Fax Members','Internal Email');

 $tabarray = array();
 $tabarray[] = get_page_data("7");
 if(checkmodule("HQEmail") || checkmodule("LicEmail")) { $tabarray[] = get_page_data("8"); }
 if(checkmodule("HQEmail") || checkmodule("LicEmail")) { $tabarray[] = get_page_data("18"); }
 if(checkmodule("HQEmail")) { $tabarray[] = get_page_data("22"); }
 if(checkmodule("SuperUser") || (checkmodule("HQEmail") && $_SESSION['User']['CID'] == 12)) { $tabarray[] = "HU List"; }

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab2") {

  email();

} elseif($_GET[tab] == "tab1") {

  labels();

} elseif($_GET[tab] == "tab3") {

  fax();

} elseif($_GET[tab] == "tab4") {

  inter();

} elseif($_GET[tab] == "tab5") {

  john();

}

?>

</form>

<?

function email() {

//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";

if($_REQUEST[all]) {
  $type="1";
} else {
  $type="2";
}

//echo $type;

if($_REQUEST[all] || $_REQUEST[sponsor])  {

 if($_REQUEST['area'] || $_REQUEST['disarea'] || $_REQUEST['lic'])  {
 add_kpi("51", "0");

 // define the text.
  $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your updated email list.";

 // get the actual taxinvoice ready.
  $bufferTemp = taxinvoice($type);

 $ZipFile = new zipfile();
 $ZipFile -> addFile($bufferTemp, "emaillist.txt");
 $buffer = $ZipFile -> file();


   	$attachArray[] = array($buffer, 'emaillist.zip', 'base64', 'application/x-zip');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), 'Updated Email List', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);


 echo "Your Email List has been sent to your email address at ".$_SESSION['User']['EmailAddress']."";
 }  else  {
   echo "An Area Must Be Selected";
 }
} else  {
?>

<html>
<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=listshq&tab=tab2&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body>

<?

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

?>

<form method="POST" action="body.php?page=listshq" name="am">
<input type="hidden" name="countryid" value="<?= $GET_CID?>">
<table border="0" cellpadding="1" cellspacing="1" width="639">

<?if($_SESSION['User']['Area'] == 1)  {?>
 <tr>
  <td height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}
?>
   </select>&nbsp;</td>
  </tr>
<?}?>
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2"><?= get_page_data("8") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
        <p>&nbsp;</td>
	</tr>
<?if($_SESSION['User']['AreasAllowed'] == 'all')  {?>
	<tr>
	    <td bgcolor="#FFFFFF" width="600"><?= get_page_data("9") ?>.<br><br>
          <b><?= get_word("78") ?>:</b><br>

          <select size="10" name="disarea[]" multiple>
          <?
           //$query2 = dbRead("select disarea from area where CID='$GET_CID' group by disarea order by disarea");
           $query2 = dbRead("select RegionalName, tbl_area_regional.FieldID from tbl_area_regional where CID='$GET_CID' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
       </tr>
<?//}?>
       <tr>
	    <td bgcolor="#FFFFFF" width="600"><b><?= get_word("24") ?>:</b><br>
          <select size="10" name="area[]" multiple>
           <?
           $query5 = dbRead("select area.statewide, tbl_area_states.FieldID, tbl_area_states.StateName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID = ".$_SESSION['User']['Area']."");
           $row5 = mysql_fetch_assoc($query5);

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
          if($row5['statewide'] == 1)  {
           $areas = " and tbl_area_regional.StateID = ".$row5['FieldID']."";
          }  else  {

              $count=0;
              $newarray = explode(",", $_SESSION['User']['ReportsAllowed']);
              //$newarray = explode(",", get_physcial_area($_SESSION['User']['ReportsAllowed']));
   			  foreach($newarray as $cat_val) {
    			if($count == 0) {
    			 $andor="";
 				} else {
 				 $andor=",";
				}

 				$cat_array.="".$andor."".$cat_val."";

 				$count++;

 			  }
 			  $areas = " and (area.FieldID in ($cat_array))";

		   }
		   }
           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, AreaName, tbl_area_physical.FieldID as FieldID, area.FieldID as id from area, tbl_area_physical, tbl_area_regional where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and area.CID='$GET_CID'$areas group by AreaName order by AreaName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>,<?= $row2[id] ?>"><?= $row2[AreaName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
       </tr>
<?}?>
<?//if($_SESSION['User']['AreasAllowed'] == 'all') {?>
       <tr>
	    <td bgcolor="#FFFFFF" width="600"><b><?= get_word("25") ?>:</b><br>
          <select size="10" name="lic[]" multiple>
           <?

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		   }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID, PhysicalID as PhysicalID from area where area.CID='$GET_CID'$areas order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2['FieldID'] ?>,<?= $row2['PhysicalID'] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
          <input type="checkbox" name="aall" value="1"> Include all members in Physical Area<br>
	 </td>
    </tr>
<?//}?>
	<tr>
	 <td bgcolor="#FFFFFF" width="600">
          <select size="10" name="cat[]" multiple>
          <?
           $query2 = dbRead("select category,catid from categories where CID='$GET_CID' order by category");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[catid] ?>"><?= $row2[category] ?></option>
            <?
           }
          ?>
          </select>
     </td>
	</tr>
    <tr>
     <td bgcolor="#FFFFFF">
		  <br><input type="checkbox" name="clubs" value="1">&nbsp;Club Members Only<br>
		  <input type="checkbox" name="spon" value="1">&nbsp;Sponsorship Only<br>
          <input type="checkbox" name="re" value="1"> <?= get_page_data("10") ?><br>
		  <input type="checkbox" name="external" value="1">&nbsp;External Emails Only<br>
		  <?if(checkmodule("SuperUser") || $_SESSION['User']['FieldID'] == 54) {?>
		  <input type="checkbox" name="vcfl" value="1">&nbsp;VCFL Only<br>
		  <?}?>
          <?if(checkmodule("SuperUser")) {?>
           <input type="checkbox" name="rory" value="1"> Include all Members<br>
          <?}?>
          <br>
          <input type="Submit" name="all"  value="<?= get_page_data("8") ?>">
	 </td>
	</tr>
</table>
</td>
</tr>
</table>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

</form>

</body>
</html>
<?

}

}

function labels() {

?>
</form>
<form method="POST" action="includes/listsmailinglabels.php">
<table border="0" cellpadding="1" cellspacing="1" width="640">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading" colspan="3"><?= get_page_data("1") ?></td>
	</tr>
	<tr>
		<td align="center" class="Heading" colspan="3"><?= get_word("138") ?></td>
	</tr>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("25") ?>:</td>
	    <td bgcolor="#FFFFFF">
	     <select size="10" name="area[]" multiple>
          <?

	       if($_SESSION['User']['AreasAllowed'] != "all") {
	        $ExtraQ = " AND (area.FieldID IN (".$_SESSION['User']['AreasAllowed']."))";
		   }

           //$query = dbRead("select FieldID, place from area where CID='".$_SESSION['User']['CID']."'$ExtraQ order by place");
           $query = dbRead("select tbl_area_physical.FieldID as FieldID, AreaName from area, tbl_area_physical where (area.PhysicalID = tbl_area_physical.FieldID) and area.CID='".$_SESSION['User']['CID']."'$ExtraQ order by AreaName");
           while($row = mysql_fetch_assoc($query)) {
            ?>
            <option value="<?= $row[FieldID] ?>"><?= $row[AreaName] ?></option>
            <?
           }
          ?>
          </select>
	    <select size="10" name="category[]" multiple>
	     <?
           $query3 = dbRead("select * from categories where CID='".$_SESSION['User']['CID']."' order by category");
           while($row3 = mysql_fetch_assoc($query3)) {
            ?>
            <option value="<?= $row3[catid] ?>"><?= $row3[category] ?></option>
            <?
           }
	     ?>
	    </select></td>
	</tr>
<?if($_SESSION['User']['AreasAllowed'] == 'all')  {?>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("78") ?>:</td>
	    <td bgcolor="#FFFFFF">
	     <select size="1" name="disarea"><option value=""><?= get_word("161") ?></option>
          <?
           //$query2 = dbRead("select disarea from area where CID='".$_SESSION['User']['CID']."' group by disarea order by disarea");
           $query2 = dbRead("select RegionalName, tbl_area_regional.FieldID as FieldID from tbl_area_regional, tbl_area_states where (tbl_area_regional.stateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
      </td>
	</tr>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("17") ?>:</td>
	    <td bgcolor="#FFFFFF">
	     <select size="1" name="state"><option value=""><?= get_word("161") ?></option>
          <?
           //$query4 = dbRead("select state from area where CID='".$_SESSION['User']['CID']."' group by state order by state");
           $query4 = dbRead("select StateName, FieldID from tbl_area_states where CID='".$_SESSION['User']['CID']."' order by StateName");
           while($row4 = mysql_fetch_assoc($query4)) {
            ?>
            <option value="<?= $row4[FieldID] ?>"><?= $row4[StateName] ?></option>
            <?
           }
          ?>
          </select></td>
	</tr>
<?}?>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_page_data("2") ?>:</td>
	    <td bgcolor="#FFFFFF"><INPUT TYPE="text" NAME="memberslist" SIZE="27"></td>
	</tr>
	<?if(checkmodule("SuperUser") || $_SESSION['User']['FieldID'] == 54) {?>
	<tr>
	    <td class="Heading2" width="100" align="right">&nbsp;</td>
	    <td bgcolor="#FFFFFF"><input type="checkbox" name="vcfl" value="1">&nbsp;VCFL Only</td>
	</tr>
	<?}?>
	<tr>
	    <td class="Heading2" width="100" align="right">&nbsp;</td>
	    <td bgcolor="#FFFFFF"><input type="checkbox" name="select" value="1">&nbsp;Limit Selection</td>
	</tr>
	<TR>
	    <td class="Heading2" width="100" align="right"><?= get_word("86") ?>:</td>
	    <td bgcolor="#FFFFFF"><input type="radio" name="type" value="1" checked><?= get_page_data("3") ?> <input type="checkbox" name="excludefax" value="1"><?= get_page_data("6") ?><br><input type="radio" name="type" value="2"><?= get_page_data("4") ?><br><input type="radio" name="type" value="3"><?= get_page_data("5") ?></td>
	</TR>
	<tr>
		<td width="100" align="right" class="Heading2"><?= get_word("38") ?>:</td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="month">
			    <option selected>Select One</option>
				<option <? if ($f == "1") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($f == "2") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($f == "3") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($f == "4") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($f == "5") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($f == "6") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($f == "7") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($f == "8") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($f == "9") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($f == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($f == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($f == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="Heading2"><?= get_word("39") ?>:</td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('year',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
	<tr>
	    <td class="Heading2" width="100" align="right">&nbsp;</td>
	    <td bgcolor="#FFFFFF"><input type="Submit" value="<?= get_page_data("7") ?>"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

<?

}

function fax() {

 if($_REQUEST[fax]) {

  add_kpi("52", "0");

  if($_REQUEST[category]) {

  $count=0;
  foreach($_REQUEST[category] as $key => $value) {
   if($count == 0) {
    $andor="";
   } else {
    $andor=",";
   }

   $CatArray_tmp.="".$andor."".$value."";

   $count++;

   if($CatArray_tmp) {

    $CatArray = " and (mem_categories.category IN ($CatArray_tmp))";

   }

  }

 } else {
    $CatArray = "";
 }

 if($_REQUEST['excludefax']) {

  $FaxQuery = " AND (members.faxno != members.phoneno)";

 } else {

  $FaxQuery = "";

 }

 if($_REQUEST[type] == 1) {

  $TypeArray = " AND (members.monthlyfeecash > 0)";

 } elseif($_REQUEST[type] == 2) {

  $TypeArray = "";

 }

 if($_REQUEST[area] != "") {

   $count=0;
   foreach($_REQUEST[area] as $cat_val) {
    if($count == 0) {
     $andor="";
    } else {
     $andor=",";
    }

    $area_array.="".$andor."".$cat_val."";

    $count++;
   }

  $query = dbRead("select * from status, members left outer join mem_categories on (members.memid = mem_categories.memid) where (members.status = status.FieldID) and members.CID = '".$_SESSION['User']['CID']."' and status.mem_lists = '1' and faxno !='' AND (members.area IN($area_array)$CatArray$FaxQuery$TypeArray) group by members.memid, faxno order by companyname");

 } elseif($_REQUEST[disarea] != "") {

  $query = dbRead("select * from status, members, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) where (members.status = status.FieldID) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) AND (tbl_area_regional.FieldID = '$_REQUEST[disarea]') and members.CID = '".$_SESSION['User']['CID']."' and status.mem_lists = '1' and faxno !=''$CatArray$FaxQuery$TypeArray group by members.memid, faxno order by companyname");

 } else {

  $query = dbRead("select * from status, members left outer join mem_categories on (members.memid = mem_categories.memid) where (members.status = status.FieldID) AND members.CID = '".$_SESSION['User']['CID']."'  and status.mem_lists = '1' and faxno !=''$CatArray$FaxQuery$TypeArray group by members.memid, faxno order by companyname");

 }

if($_REQUEST[format] == 1 || $_REQUEST[format] == 3)  {

 // define the text.
  $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your updated email list.";

 // get the actual taxinvoice ready.
  $buffer = faxlist($query,$_REQUEST[format]);

   	$attachArray[] = array($buffer, 'emaillist.txt', 'base64', 'text/plain');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), 'Updated Email List', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

 echo "Your Email List has been sent to your email address at ".$_SESSION['User']['EmailAddress']."";

} else {


 $time_start = getmicrotime();

 $counter = mysql_num_rows($query);
?>

</form>
<form method="POST" action="/includes/listsfaxmembers.php">
</form>
<table width="610" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td align="center" bgcolor="#FFFFFF"><a href="javascript:print()" class="nav"><?= get_word("87") ?></a><br>Number of Results: <b><?= $counter ?></b></td>
	</tr>
</table>
<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="610" border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="9" align="center" class="Heading"><?= get_page_data("12") ?>.</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("1") ?>:</b></td>
		<td width="176" class="Heading2"><b><?= get_word("3") ?>:</b></td>
		<td width="150" class="Heading2"><b><?= get_word("5") ?>:</b></td>
		<td width="92" class="Heading2"><b><?= get_word("8") ?>:</b></td>
	</tr>

<?

$foo=0;

while($row = mysql_fetch_assoc($query)) {

 $count++;

	  $bgcolor = "#CCCCCC";
	  $foo % 2  ? 0: $bgcolor = "#EEEEEE";

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><font color="<?= $frcolor ?>"><?= $row['memid'] ?></font>&nbsp;</td>
			<td width="176"><font color="<?= $frcolor ?>"><?= get_all_added_characters($row['companyname']) ?></font>&nbsp;</td>
			<td width="150"><font color="<?= $frcolor ?>"><?= get_all_added_characters($row['contactname']) ?></font>&nbsp;</td>
			<td width="92"><font color="<?= $frcolor ?>"><?= get_all_added_characters($row['faxarea']) ?> <?= get_all_added_characters($row['faxno']) ?></font>&nbsp;</td>
		</tr>
<?

$foo++;

}

?>
	<tr>
	    <td colspan="9" bgcolor="#FFFFFF" align="right"><input type="Submit" value="Get PDF Version"></td>
	</tr>
		<tr>
		    <td colspan="9" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
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

<?
}
} else {
?>

</form>
<form method="POST" action="body.php?page=listshq&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">
<table border="0" cellpadding="1" cellspacing="1" width="640">
<input type="hidden" name="fax" value="1">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading" colspan="3">Fax Stream List</td>
	</tr>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("25") ?>:</td>
	    <td bgcolor="#FFFFFF">
	     <select size="10" name="area[]" multiple>
          <?

	       if($_SESSION['User']['AreasAllowed'] != "all") {
	        $ExtraQ = " AND (area.FieldID IN (".$_SESSION['User']['AreasAllowed']."))";
		   }

           //$query = dbRead("select FieldID, place from area where CID='".$_SESSION['User']['CID']."'$ExtraQ order by place");
           $query = dbRead("select tbl_area_physical.FieldID as FieldID, AreaName from area, tbl_area_physical where (area.PhysicalID = tbl_area_physical.FieldID) and area.CID='".$_SESSION['User']['CID']."'$ExtraQ order by AreaName");
           while($row = mysql_fetch_assoc($query)) {
            ?>
            <option value="<?= $row[FieldID] ?>"><?= $row[AreaName] ?></option>
            <?
           }
          ?>
          </select>
	    <select size="10" name="category[]" multiple>
	     <?


           $query3 = dbRead("select * from categories where CID='".$_SESSION['User']['CID']."' order by category");
           while($row3 = mysql_fetch_assoc($query3)) {
            ?>
            <option value="<?= $row3[catid] ?>"><?= $row3[category] ?></option>
            <?
           }
	     ?>
	    </select></td>
	</tr>
<?if($_SESSION['User']['AreasAllowed'] == 'all')  {?>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("78") ?>:</td>
	    <td bgcolor="#FFFFFF">
	     <select size="1" name="disarea"><option value=""><?= get_word("161") ?></option>
          <?
           //$query2 = dbRead("select disarea from area where CID='".$_SESSION['User']['CID']."' group by disarea order by disarea");
           $query2 = dbRead("select RegionalName, tbl_area_regional.FieldID as FieldID from tbl_area_regional, tbl_area_states where (tbl_area_regional.stateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName");
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
	<TR>
	    <td class="Heading2" width="100" align="right"><?= get_word("86") ?>:</td>
	    <td bgcolor="#FFFFFF"><input type="radio" name="type" value="1" checked> <?= get_page_data("13") ?> <br><input type="radio" name="type" value="2"> <?= get_page_data("15") ?><br><br><input type="checkbox" name="excludefax" value="1"><?= get_page_data("16") ?></td>
	</TR>
	<TR>
	    <td class="Heading2" width="100" align="right"><?= get_page_data("17") ?>:</td>
	    <td bgcolor="#FFFFFF"><input type="radio" name="format" value="1" checked> <?= get_page_data("18") ?> <br><input type="radio" name="format" value="3" checked> <?= get_page_data("18") ?> <?= get_page_data("19") ?><br><input type="radio" name="format" value="2"> <?= get_page_data("20") ?></td>
	</TR>
	<tr>
	    <td class="Heading2" width="100" align="right">&nbsp;</td>
	    <td bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("83") ?>"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

<?
}
}

function inter() {


if($_REQUEST[search])  {

 // define the text.
  $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your updated email list.";

 // get the actual taxinvoice ready.
  $buffer = interlist();

   	$attachArray[] = array($buffer, 'emaillist.txt', 'base64', 'text/plain');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), 'Updated Email List', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);


 echo "Your Email List has been sent to your email address at ".$_SESSION['User']['EmailAddress']."";

} else  {

?>
<input type="hidden" name="search" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="640">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading" colspan="3"><?= get_page_data("22") ?></td>
	</tr>
	<tr>
		<td align="center" class="Heading" colspan="3"></td>
	</tr>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("79") ?>:</td>
        <td height="30" align="left" bgcolor="#FFFFFF">
  			<select name="countryid" id="countryid" onChange="ChangeCountry(this);"><option value=""><?= get_word("163") ?></option>
			<?

		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}

			?>
   			</select>&nbsp;</td>

	</tr>
	<tr>
	    <td class="Heading2" width="100" align="right"><?= get_word("25") ?>:</td>
	    <td bgcolor="#FFFFFF">
	     <select size="1" name="area"><option value=""><?= get_word("161") ?></option>
          <?
           $query = dbRead("select FieldID, place from area where CID='".$_SESSION['User']['CID']."' order by place");
           while($row = mysql_fetch_assoc($query)) {
            ?>
            <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
            <?
           }
          ?>
          </select></td>
    </tr>
	<TR>
	    <td class="Heading2" width="100" align="right"><?= get_word("86") ?>:</td>
	    <td bgcolor="#FFFFFF"><input type="checkbox" name="type1" value="1" checked><?= get_word("173") ?><br><input type="checkbox" name="type2" value="2"><?= get_word("170") ?><br><input type="checkbox" name="type3" value="3"><?= get_word("172") ?><br><input type="checkbox" name="type4" value="4"><?= get_word("174") ?><br><input type="checkbox" name="type5" value="5"><?= get_word("169") ?></td>
	</TR>
	<tr>
	    <td class="Heading2" width="100" align="right">&nbsp;</td>
	    <td bgcolor="#FFFFFF"><input type="Submit" value="<?= get_word("83") ?>"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

<?
}

}


function john()  {

//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";

if($_REQUEST[all]) {
  $type="1";
} else {
  $type="2";
}

//echo $type;

if($_REQUEST[all] || $_REQUEST[sponsor])  {

 if($_REQUEST['area'] || $_REQUEST['disarea'] || $_REQUEST['lic'])  {
 add_kpi("51", "0");

 // define the text.
  $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your updated email list.";

 // get the actual taxinvoice ready.
  $buffer = johnlist($type);

   	$attachArray[] = array($buffer, 'emaillist.txt', 'base64', 'text/plain');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), 'Updated Email List', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

 echo "Your Email List has been sent to your email address at ".$_SESSION['User']['EmailAddress']."";
 }  else  {
   echo "An Area Must Be Selected";
 }
} else  {
?>

<html>
<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=listshq&tab=Email List&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body>

<?

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

?>

<form method="POST" action="body.php?page=listshq" name="am">
<input type="hidden" name="countryid" value="<?= $GET_CID?>">
<table border="0" cellpadding="1" cellspacing="1" width="639">

<?if($_SESSION['User']['Area'] == 1)  {?>
 <tr>
  <td height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}
?>
   </select>&nbsp;</td>
  </tr>
<?}?>
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2">Mail Merge List</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
        <p>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600">Please select the areas and Category you want a mail merge for.<br><br>
          <b><?= get_word("78") ?>:</b><br>
<?if($_SESSION['User']['AreasAllowed'] == 'all')  {?>
          <select size="10" name="disarea[]" multiple>
          <?
           //$query2 = dbRead("select disarea from area where CID='$GET_CID' group by disarea order by disarea");
           $query2 = dbRead("select RegionalName, tbl_area_regional.FieldID from tbl_area_regional where CID='$GET_CID' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
       </tr>
       <tr>
	    <td bgcolor="#FFFFFF" width="600"><b><?= get_word("24") ?>:</b><br>
<?}?>
          <select size="10" name="area[]" multiple>
           <?
           $query5 = dbRead("select area.statewide, tbl_area_states.FieldID, tbl_area_states.StateName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID = ".$_SESSION['User']['Area']."");
           $row5 = mysql_fetch_assoc($query5);


          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
           if($row5['statewide'] == 1)  {
            $areas = " and tbl_area_regional.StateID = ".$row5['FieldID']."";
           }  else  {
              $count=0;
              $newarray = explode(",", $_SESSION['User']['ReportsAllowed']);
              //$newarray = explode(",", get_physcial_area($_SESSION['User']['ReportsAllowed']));
   			  foreach($newarray as $cat_val) {
    			if($count == 0) {
    			 $andor="";
 				} else {
 				 $andor=",";
				}

 				$cat_array.="".$andor."".$cat_val."";

 				$count++;

 			  }
 			  $areas = " and (area.FieldID in ($cat_array))";
 			  //$areas = " and (area.FieldID in ($cat_array))";
		    }
		   }
           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select AreaName, tbl_area_physical.FieldID as FieldID, area.FieldID as id from area, tbl_area_physical, tbl_area_regional where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and area.CID='$GET_CID'$areas group by AreaName order by AreaName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>,<?= $row2[id] ?>"><?= $row2[AreaName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
       </tr>
       <tr>
	    <td bgcolor="#FFFFFF" width="600"><b><?= get_word("25") ?>:</b><br>
          <select size="10" name="lic[]" multiple>
           <?

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		   }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID from area where area.CID='$GET_CID'$areas order by place");
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
	 <td bgcolor="#FFFFFF" width="600">
          <select size="10" name="cat[]" multiple>
          <?
           $query2 = dbRead("select category,catid from categories where CID='$GET_CID' order by category");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[catid] ?>"><?= $row2[category] ?></option>
            <?
           }
          ?>
          </select>
     </td>
	</tr>
    <tr>
     <td bgcolor="#FFFFFF">
          <br><br><?= get_page_data("10") ?><input type="checkbox" name="re" value="1"><br>
          <br>
          <input type="Submit" name="all"  value="Get Mail Merge List"> <input type="Submit" name="sponsor"  value="<?= get_page_data("12") ?>">
	 </td>
	</tr>
</table>
</td>
</tr>
</table>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

</form>

</body>
</html>

<?

}

}


function taxinvoice($type) {

 $count = 0;

 if($_REQUEST[re]) {
  $op = " AND members.reopt = 'Y'";
 } else {
  if($_REQUEST['rory']) {
   $op = "";
  } else {
   $op = " AND members.opt = 'Y'";
  }
 }

 if($_REQUEST['clubs']) {

 	$club = " AND members.fiftyclub in (1,2)";

 }

 if($_REQUEST['spon']) {

 	$spon = " and status.Name = 'Sponsorship'";

 }

 if($_REQUEST['vcfl']) {

 	$vcfl = " and mem_categories.description like '%vcfl%'";

 }

 if($_REQUEST['rory']) {
  //$ty = "email_accounts";
  $et = 3;
 } else {
  //$ty = "emailaddress";
  $et = 4;
 }

 if($_REQUEST[cat]) {

  $count=0;

  foreach($_REQUEST[cat] as $cat_val) {
   if($count == 0) {
     $andor="";
   } else {
     $andor=",";
   }

   $cat_list.="".$andor."".$cat_val."";

   $count++;
  }
  $cat2_array = " and (mem_categories.category IN($cat_list))";
 }

 if($_REQUEST['external']) {
   $op = " and exopt = 'Y' ";
 }

 if($type == "1") {

  if($_REQUEST[disarea]) {

   $area_array = $_REQUEST[disarea];
   $newArray = comma_seperate($area_array);

    //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and area.disarea='$value' and (status.mem_lists = 1) group by members.memid order by area");
    //$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and tbl_area_regional.FieldID IN ($newArray) and (status.mem_lists = 1) group by members.emailaddress order by AreaName");
    //$query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.* from members, status, tbl_area_physical, tbl_area_regional, tbl_members_email left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != ''$op$cat2_array and tbl_area_regional.FieldID IN ($newArray) and (status.mem_lists = 1) and (tbl_members_email.type = $et) group by email order by AreaName");    #loop around
    $query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.*

	from members

		inner
			join
				tbl_area_physical
				on members.area = tbl_area_physical.FieldID
		inner
			join
				tbl_area_regional
				on tbl_area_physical.RegionalID = tbl_area_regional.FieldID
		inner
			join
				status
				on members.status = status.FieldID
		inner
			join
				tbl_members_email
				on members.memid = tbl_members_email.acc_no

		left outer join mem_categories on (members.memid = mem_categories.memid)

	where
		tbl_members_email.email != ''$op$club$spon$vcfl$cat2_array and tbl_area_regional.FieldID IN ($newArray) and (status.mem_lists = 1) and (tbl_members_email.type = $et)

	group by email
	order by AreaName
	");

    while($row = mysql_fetch_assoc($query)) {
	 $count++;
   	 if($_SESSION['User']['CID'] == 12) {
      //$blah .= "$row[$ty],\r\n";
      $blah .= $row['email'].",\r\n";
     } else {
      //$blah .= "$row[emailaddress],";
      //$blah .= "$row[$ty],";
      $blah .= $row['email'].",";
     }
    }

   return $blah;

  } elseif($_REQUEST[area]) {

   $count=0;
   foreach($_REQUEST[area] as $cat_val) {
    if($count == 0) {
     $andor="";
    } else {
     $andor=",";
    }
    $a_list = explode(",", $cat_val,2);
    $cat_array.="".$andor."".$a_list[0]."";
    $cat_array3.="".$andor."".$a_list[1]."";
    //$cat_array.="".$andor."".$cat_val."";
    //echo $a_list[0];
    //echo $a_list[1];

    $count++;
   }

   //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by area");
   //$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (tbl_area_physical.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by AreaName");
   //$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and (tbl_area_physical.FieldID IN($cat_array) or members.licensee in ($cat_array3)) and members.emailaddress != ''$op$cat2_array and (status.mem_lists = 1) group by members.emailaddress order by AreaName");
   //$query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.* from members, status, tbl_area_physical, tbl_members_email left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = tbl_members_email.acc_no) and (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and (members.memid = tbl_members_email.acc_no) and (tbl_area_physical.FieldID IN($cat_array) or members.licensee in ($cat_array3)) and tbl_members_email.email != ''$op$cat2_array and (status.mem_lists = 1) and (tbl_members_email.type = $et) group by email order by AreaName");
   $query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.*
	from members

		inner
			join
				tbl_area_physical
				on members.area = tbl_area_physical.FieldID
		inner
			join
				status
				on members.status = status.FieldID
		inner
			join
				tbl_members_email
				on members.memid = tbl_members_email.acc_no

		left outer join mem_categories on (members.memid = mem_categories.memid)

	where
		(tbl_area_physical.FieldID IN($cat_array) or members.licensee in ($cat_array3)) and tbl_members_email.email != ''$op$club$spon$vcfl$cat2_array and (status.mem_lists = 1) and (tbl_members_email.type = $et)

	group by email
	order by AreaName
	");

   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
   	 if($_SESSION['User']['CID'] == 12) {
      //$blah .= "$row[$ty],\r\n";
      $blah .= $row['email'].",\r\n";
     } else {
      //$blah .= "$row[$ty],";
      $blah .= $row['email'].",";
     }
   }

   return $blah;

  } elseif($_REQUEST[lic]) {

   $count=0;
   foreach($_REQUEST[lic] as $cat_val) {
    if($count == 0) {
     $andor="";
    } else {
     $andor=",";
    }

    $a_list = explode(",", $cat_val,2);
    $cat_array.="".$andor."".$a_list[0]."";
    $cat_array3.="".$andor."".$a_list[1]."";

    $count++;
   }

	if($_REQUEST['aall']) {
		$ex = " or members.area IN($cat_array3)";
	} else {
		$ex = "";
	}

   //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by area");
   //$query = dbRead("select members.*, status.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.licensee = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.emailaddress order by place");
   //$query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.* from members, status, area, tbl_members_email left outer join mem_categories on (members.memid = mem_categories.memid) where (members.licensee = area.FieldID) and (members.status = status.FieldID) and (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) and (tbl_members_email.type = $et) group by tbl_members_email.email order by place");
   $query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.*
	from members

	inner
		join
			status
			on members.status = status.FieldID
	inner
		join
			area
			on members.licensee = area.FieldID
	inner
		join
			tbl_members_email
			on members.memid = tbl_members_email.acc_no


	left outer join mem_categories on (members.memid = mem_categories.memid)

	where
		(members.licensee in ($cat_array)$ex) and tbl_members_email.email != ''$op$club$spon$vcfl$cat2_array and (status.mem_lists = 1) and (tbl_members_email.type = $et)

	group by tbl_members_email.email
	order by place"
	);


   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
   	 if($_SESSION['User']['CID'] == 12) {
      //$blah .= "$row[$ty],\r\n";
      $blah .= $row['email'].",\r\n";
     } else {
      //$blah .= "$row[$ty],";
      $blah .= $row['email'].",";
     }
   }

   $blah .= $count;

   return $blah;

  }

 } elseif($type == "2") {

  $area_array = $_POST[disarea];
  $newArray = comma_seperate($area_array);

   //$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid)where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and tbl_area_regional.FieldID IN ($newArray) and status.Name = 'Sponsorship' group by members.emailaddress order by RegionalName");
   $query = dbRead("select members.*, status.*, mem_categories.*, tbl_members_email.email

	from members

	inner
		join
			status
			on members.status = status.FieldID
	inner
		join
			tbl_area_physical
			on members.area = tbl_area_physical.FieldID
	inner
		join
			tbl_area_regional
			on tbl_area_physical.RegionalID = tbl_area_regional.FieldID
	inner
		join
			tbl_members_email
			on members.memid = tbl_members_email.acc_no
	left outer join mem_categories on (members.memid = mem_categories.memid)

	where
		tbl_members_email.email != ''$op$cat2_array and tbl_area_regional.FieldID IN ($newArray) and (tbl_members_email.type = $et) and status.Name = 'Sponsorship'

	group by tbl_members_email.email
	order by RegionalName
	");

   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
   	 if($_SESSION['User']['CID'] == 12) {
      $blah .= "$row[email],\r\n";
     } else {
      $blah .= "$row[email],";
     }
   }


  return $blah;

 }

}

function interlist() {

 if($_REQUEST[countryid]) {
  $op = " AND CID = $_REQUEST[countryid]";
 } else {
  $op = "";
 }

 if($_REQUEST[area]) {
  $opp = " AND Area = $_REQUEST[area]";
 } else {
  $opp = "";
 }

 if($_REQUEST[type1]) {
  $t1 = " emlic = '1'";
 } else {
  $t1 = "";
 }

 if($_REQUEST[type2]) {
  if($t1)  {
   $t2 = " OR emadm = '1'";
  } else {
   $t2 = " emadm = '1'";
  }
 } else {
  $t2 = "";
 }

 if($_REQUEST[type3]) {
  if($t1 || $t2)  {
   $t3 = " OR emcus = '1'";
  } else {
   $t3 = " emcus = '1'";
  }
 } else {
  $t3 = "";
 }

 if($_REQUEST[type4]) {
  if($t1 || $t2 || $t3)  {
   $t4 = " OR emsal = '1'";
  } else {
   $t4 = " emsal = '1'";
  }
 } else {
  $t4 = "";
 }

 if($_REQUEST[type5]) {
  if($t1 || $t2 || $t3 || $t4)  {
   $t5 = " OR emrea = '1'";
  } else {
   $t5 = " emrea = '1'";
  }
 } else {
  $t5 = "";
 }

 //foreach($area_array as $key => $value) {
   $query = dbRead("select EmailAddress from tbl_admin_users where (Suspended != '1') and ($t1$t2$t3$t4$t5)$op$opp Order by EmailAddress");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
    if($row[EmailAddress] !=" ") {
     $blah .= "$row[EmailAddress];";
    }
   }
 //}

  return $blah;

}

function faxlist($query,$type) {

 if($type == 3)  {

  while($row = mysql_fetch_assoc($query)) {

     $blah .= "$row[companyname],$row[faxarea]$row[faxno]\r\n";

  }

 } else {

  while($row = mysql_fetch_assoc($query)) {

     //$no = ($row[faxarea]) ? explode("0", $row[faxarea],2) : explode("0", $row[faxno], 2);
     //$no2 = ($row[faxarea]) ? "001144$no[1]$row[faxno]" : "001144$no[1]";
     //$blah .= "$no2;";

     //$blah .= "0$no[1]$row[faxno];";

     $blah .= "$row[faxarea]$row[faxno];";
  }

 }

  return $blah;

}

function johnlist($type) {

 $count = 0;

 if($_REQUEST[re]) {
  $op = " AND members.reopt = 'Y'";
 } else {
  $op = " AND members.opt = 'Y'";
 }

 if($_REQUEST[cat]) {

  $count=0;

  foreach($_REQUEST[cat] as $cat_val) {
   if($count == 0) {
     $andor="";
   } else {
     $andor=",";
   }

   $cat_list.="".$andor."".$cat_val."";

   $count++;
  }
  $cat2_array = " and (mem_categories.category IN($cat_list))";
 }

 if($type == "1") {

  if($_REQUEST[disarea]) {

   $area_array = $_REQUEST[disarea];
   foreach($area_array as $key => $value) {
    //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and area.disarea='$value' and (status.mem_lists = 1) group by members.memid order by area");
    //$query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.* from members, tbl_members_email, status, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = tbl_members_email.acc_no) and (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and tbl_members_email.type = 3 and tbl_members_email.email != ''$op$cat2_array and tbl_area_regional.FieldID ='$value' and (status.mem_lists = 1) group by tbl_members_email.email order by AreaName");

	$query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.*

	from members
	inner
		join
			tbl_members_email
			on members.memid = tbl_members_email.acc_no
	inner
		join
			status
			members.status = status.FieldID
	inner
		join
			tbl_area_physical
			on members.area = tbl_area_physical.FieldID
	inner
		join
			tbl_area_regional
			on tbl_area_physical.RegionalID = tbl_area_regional.FieldID


 	left outer join mem_categories on (members.memid = mem_categories.memid)

	where
	tbl_members_email.type = 3 and tbl_members_email.email != ''$op$cat2_array and tbl_area_regional.FieldID ='$value' and (status.mem_lists = 1) group by tbl_members_email.email
	order by AreaName");

    #loop around
    while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode],$row[emailaddress]\r\n";
    }
   }

   return $blah;

  } elseif($_REQUEST[area]) {

   $count=0;
   foreach($_REQUEST[area] as $cat_val) {
    if($count == 0) {
     $andor="";
    } else {
     $andor=",";
    }
    $a_list = explode(",", $cat_val,2);
    $cat_array.="".$andor."".$a_list[0]."";
    $cat_array3.="".$andor."".$a_list[1]."";
    //$cat_array.="".$andor."".$cat_val."";
    //echo $a_list[0];
    //echo $a_list[1];

    $count++;
   }

   //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by area");
   //$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (tbl_area_physical.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by AreaName");
   //$query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.* from members, tbl_members_email, status, tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = tbl_members_email.acc_no) and (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and (tbl_area_physical.FieldID IN($cat_array) or members.licensee in ($cat_array3)) and tbl_members_email.type = 3 and tbl_members_email.email != ''$op$cat2_array and (status.mem_lists = 1) group by members.memid order by AreaName");
   $query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.*
	from members
	inner
		join
			tbl_members_email
			on members.memid = tbl_members_email.acc_no
	inner
		join
			status
			members.status = status.FieldID
	inner
		join
			tbl_area_physical
			on members.area = tbl_area_physical.FieldID

	left outer join mem_categories on (members.memid = mem_categories.memid)

	where
	(tbl_area_physical.FieldID IN($cat_array) or members.licensee in ($cat_array3)) and tbl_members_email.type = 3 and tbl_members_email.email != ''$op$cat2_array and (status.mem_lists = 1)
	group by members.memid
	order by AreaName");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode],$row[emailaddress]\r\n";
   }

   return $blah;

  } elseif($_REQUEST[lic]) {

   $count=0;
   foreach($_REQUEST[lic] as $cat_val) {
    if($count == 0) {
     $andor="";
    } else {
     $andor=",";
    }

    $cat_array.="".$andor."".$cat_val."";

    $count++;
   }

   //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by area");
   //$query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.* from members, tbl_members_email, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = tbl_members_email.acc_no) and (members.licensee = area.FieldID) and (members.status = status.FieldID) and tbl_members_email.type = 3 and tbl_members_email.email != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by place");
   $query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.*
	from members
	inner
		join
			tbl_members_email
			on members.memid = tbl_members_email.acc_no
	inner
		join
			status
			members.status = status.FieldID
	inner
		join
			area
			on members.licensee = area.FieldID

	left outer join mem_categories on (members.memid = mem_categories.memid)

	where
	tbl_members_email.type = 3 and tbl_members_email.email != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1)

	group by members.memid
	order by place");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode],$row[emailaddress]\r\n";
   }

   return $blah;

  }

 } elseif($type == "2") {

  $area_array = $_REQUEST[disarea];
  foreach($area_array as $key => $value) {
   //$query = dbRead("select members.*, status.* from members, status, area where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and area.disarea='$value' and status.Name = 'Sponsorship'  order by area");
   $query = dbRead("select members.*, tbl_members_email.email as emailaddress, status.*, mem_categories.* from members, tbl_members_email, status, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = tbl_members_email.acc_no) and (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and tbl_members_email.type = 3 and tbl_members_email.email != ''$op$cat2_array and tbl_area_regional.FieldID='$value' and status.Name = 'Sponsorship'  order by RegionalName");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode],$row[emailaddress]\r\n";
   }
  }

  return $blah;

 }
}
?>
