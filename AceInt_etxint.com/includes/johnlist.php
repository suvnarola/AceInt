<?

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

     unset($attachArray);
     unset($addressArray);

   	 $attachArray[] = array($buffer, 'emaillist.txt', 'base64', 'text/plain');
	 $addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), "Updated Email List", 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray);

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
		<td align="center" class="Heading2" colspan="2"><?= get_page_data("8") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
        <p>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600"><?= get_page_data("9") ?>.<br><br>
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

          if($row5['statewide'] == 1)  {
           $areas = " and tbl_area_regional.StateID = ".$row5['FieldID']."";
          }  else  {
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
 			  $areas = " and (area.FieldID in ($cat_array))";
		   }
		   }
           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select AreaName, tbl_area_physical.FieldID as FieldID from area, tbl_area_physical, tbl_area_regional where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and area.CID='$GET_CID'$areas order by AreaName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[AreaName] ?></option>
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
 			  $areas = " and (area.FieldID in ($cat_array))";
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
          <input type="Submit" name="all"  value="<?= get_page_data("8") ?>"> <input type="Submit" name="sponsor"  value="<?= get_page_data("12") ?>">
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
    $query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and tbl_area_regional.FieldID ='$value' and (status.mem_lists = 1) group by members.emailaddress order by AreaName");
    #loop around
    while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode]\r\n";
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

    $cat_array.="".$andor."".$cat_val."";

    $count++;
   }

   //$query = dbRead("select members.*, status.*, area.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by area");
   $query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (tbl_area_physical.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by AreaName");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode]\r\n";
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
   $query = dbRead("select members.*, status.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.licensee = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by place");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode]\r\n";
   }

   return $blah;

  }

 } elseif($type == "2") {

  $area_array = $_POST[disarea];
  foreach($area_array as $key => $value) {
   //$query = dbRead("select members.*, status.* from members, status, area where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and area.disarea='$value' and status.Name = 'Sponsorship'  order by area");
   $query = dbRead("select members.*, status.* from members, status, tbl_area_physical, tbl_area_regional where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and tbl_area_regional.FieldID='$value' and status.Name = 'Sponsorship'  order by RegionalName");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
	 $count++;
     $blah .= "$row[memid],$row[companyname],$row[companyname],$row[contactname],$row[postalstreetno],$row[postalstreetname],$row[postalsuburb],$row[postalcity],$row[postalpostcode]\r\n";
   }
  }

  return $blah;

 }

}
