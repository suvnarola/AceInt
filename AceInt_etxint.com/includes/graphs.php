<?

if(!checkmodule("Graphs")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">You arn't allowed to use this function.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

if(checkmodule("Log")) {
 add_kpi("13","0");
}

$Get_CID = ($_REQUEST['ChangeCID']) ? $_REQUEST['ChangeCID'] : $_SESSION['Country']['countryID'];

$CountrySQL = dbRead("select country.* from country where countryID = " . $Get_CID);
$CountryRow = mysql_fetch_assoc($CountrySQL);

?>
<html>
<head>
<script>

function ChangeCountry(list) {
 var url = 'body.php?page=graphs&ChangeCID=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data(27) ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <hr>
	    <P>
	    <b><?= get_page_data(1) ?></b><br>
	    </P>
	    <ul>
	     <li><a href="includes/bargraph.php?GraphID=19" class="nav"><?= get_page_data(2) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=20" class="nav"><?= get_page_data(3) ?></a></li>
	    </ul>
	    <hr>
	    <?if($_SESSION['User']['AreasAllowed'] == 'all' && ($_SESSION['User']['CID'] != 3 || $_SESSION['User']['CID'] != 10)) {?>
	    <center>
	    <select name="ChangeCID" id="ChangeCID" onChange="ChangeCountry(this);">
<?
	
		$dbgetarea=dbRead("select * from country  order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['countryID'] == $Get_CID) { echo "selected "; } ?>value="<?= $row['countryID'] ?>"><?= $row['name'] ?></option>
			<?
		}
		
		$counter = mysql_num_rows($dbgetdataout);
		
?>
	  </select>
	  </center>
	  <?}?>
	    <P>
	    <B><?= get_page_data(4) ?> - <?= $CountryRow[name] ?></B><br>
	    </P>
	    <ul>
	     <li><a href="includes/bargraph.php?GraphID=1&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(5) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=2&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(6) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=3&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(7) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=4&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(8) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=21&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(9) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=22&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(10) ?></a></li>
	     <li><a href="includes/bargraph.php?GraphID=17&PassCID=<?= $CountryRow['countryID'] ?>" class="nav"><?= get_page_data(11) ?></a></li>
	     <?if($ff) {?><li><a href="includes/bargraph.php?GraphID=18&PassCID=<?= $CountryRow['countryID'] ?>" class="nav">Monthly E Rewards</a></li><?}?>
	    </ul>
	    <hr>
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
		   ?>	    
	    <b><?= get_page_data(12) ?></b><br>
		<table>
		<form action="includes/bargraph.php?GraphID=5" method="POST">
		 <tr>
		  <td><?= get_page_data(5) ?></td>
		  <td><select name="area">
		  <?
		   $query = dbRead("select FieldID, place from area where CID = '".$Get_CID."'$areas order by place ASC");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>
		<form action="includes/bargraph.php?GraphID=6" method="POST">
		 <tr>
		  <td><?= get_page_data(6) ?></td>
		  <td><select name="area">
		  <?
		   $query = dbRead("select FieldID, place from area where CID = '".$Get_CID."'$areas order by place ASC");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=7" method="POST">
		 <tr>
		  <td><?= get_page_data(7) ?></td>
		  <td><select name="area">
		  <?
		   $query = dbRead("select FieldID, place from area where CID = '".$Get_CID."'$areas order by place ASC");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=8" method="POST">
		 <tr>
		  <td><?= get_page_data(10) ?></td>
		  <td><select name="area">
		  <?
		   $query = dbRead("select FieldID, place from area where CID = '".$Get_CID."'$areas order by place ASC");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=81" method="POST">
		 <tr>
		  <td><?= get_page_data(9) ?></td>
		  <td><select name="area">
		  <?
		   $query = dbRead("select FieldID, place from area where CID = '".$Get_CID."'$areas order by place ASC");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form> 		
		</table>
	    <hr>
	    <b><?= get_page_data(13) ?></b><br>
		<table>
		<form action="includes/bargraph.php?GraphID=13" method="POST">
		 <tr>
		  <td><?= get_page_data(5) ?></td>
		  <td><select name="area">
		  <?
		   //$query = dbRead("select disarea from area group by disarea ASC");
		   //$query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");
           if($_SESSION['User']['Area'] == 1) {
		     $query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");           
		   } else { 
             $query = dbRead("select tbl_area_regional.FieldID, tbl_area_regional.RegionalName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID in ( ".get_areas_allowed(true)." ) group by tbl_area_regional.FieldID order by tbl_area_regional.RegionalName");
		   }
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>
		<form action="includes/bargraph.php?GraphID=14" method="POST">
		 <tr>
		  <td><?= get_page_data(6) ?></td>
		  <td><select name="area">
		  <?
		   //$query = dbRead("select disarea from area group by disarea ASC");
		   //$query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");
           if($_SESSION['User']['Area'] == 1) {
		     $query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");           
		   } else { 
             $query = dbRead("select tbl_area_regional.FieldID, tbl_area_regional.RegionalName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID in ( ".get_areas_allowed(true)." ) group by tbl_area_regional.FieldID order by tbl_area_regional.RegionalName");
		   }	   	   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=15" method="POST">
		 <tr>
		  <td><?= get_page_data(7) ?></td>
		  <td><select name="area">
		  <?
		   //$query = dbRead("select disarea from area group by disarea ASC");
		   //$query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");
           if($_SESSION['User']['Area'] == 1) {
		     $query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");           
		   } else { 
             $query = dbRead("select tbl_area_regional.FieldID, tbl_area_regional.RegionalName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID in ( ".get_areas_allowed(true)." ) group by tbl_area_regional.FieldID order by tbl_area_regional.RegionalName");
		   }		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=16" method="POST">
		 <tr>
		  <td><?= get_page_data(10) ?></td>
		  <td><select name="area">
		  <?
		   //$query = dbRead("select disarea from area group by disarea ASC");
		   //$query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");
           if($_SESSION['User']['Area'] == 1) {
		     $query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");           
		   } else { 
             $query = dbRead("select tbl_area_regional.FieldID, tbl_area_regional.RegionalName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID in ( ".get_areas_allowed(true)." ) group by tbl_area_regional.FieldID order by tbl_area_regional.RegionalName");
		   }		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=161" method="POST">
		 <tr>
		  <td><?= get_page_data(9) ?></td>
		  <td><select name="area">
		  <?
		   //$query = dbRead("select disarea from area group by disarea ASC");
		   //$query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");
           if($_SESSION['User']['Area'] == 1) {
		     $query = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional where CID = '".$Get_CID."' order by RegionalName ASC");           
		   } else { 
             $query = dbRead("select tbl_area_regional.FieldID, tbl_area_regional.RegionalName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID in ( ".get_areas_allowed(true)." ) group by tbl_area_regional.FieldID order by tbl_area_regional.RegionalName");
		   }		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form> 		
		</table>
		<hr>
	    <b><?= get_page_data(14) ?></b><br>
		<table>
		<form action="includes/bargraph.php?GraphID=9" method="POST">
		 <tr>
		  <td><?= get_page_data(5) ?></td>
		  <td><select name="state">
		  <?
		   //$query = dbRead("select state from area where CID='1' group by state");
		   $query = dbRead("select StateName, FieldID from tbl_area_states where CID = '".$Get_CID."' Order by StateName");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[StateName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>
		<form action="includes/bargraph.php?GraphID=10" method="POST">
		 <tr>
		  <td><?= get_page_data(6) ?></td>
		  <td><select name="state">
		  <?
		   $query = dbRead("select StateName, FieldID from tbl_area_states where CID = '".$Get_CID."' Order by StateName");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[StateName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=11" method="POST">
		 <tr>
		  <td><?= get_page_data(7) ?></td>
		  <td><select name="state">
		  <?
		   $query = dbRead("select StateName, FieldID from tbl_area_states where CID = '".$Get_CID."' Order by StateName");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[StateName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		<form action="includes/bargraph.php?GraphID=12" method="POST">
		 <tr>
		  <td><?= get_page_data(8) ?></td>
		  <td><select name="state">
		  <?
		   $query = dbRead("select StateName, FieldID from tbl_area_states where CID = '".$Get_CID."' Order by StateName");
		   while($row = mysql_fetch_assoc($query)) {
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[StateName] ?></option>
		    <?
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
 		</form>
		</table>
		<hr>
        <B><?= get_page_data(15) ?></B><br>
		<table>
		<form action="includes/bargraphre.php?GraphID=1" method="POST">
		 <tr>
		  <td><?= get_word(39) ?>:</td>
		  <td><select name="graphdata">
		  <?

			$startmonth = "02";
			$startyear = "2000";
			$foo = 0;
			$current = false;
			
			while($current == false) {
			 
			 $dis_date = date("Y", mktime(1,1,1,1,1,$startyear+$foo));
			 $dis_date2 = date("Y", mktime(1,1,1,1,1,$startyear+$foo));
			 $checkdate = date("Y");
			 if($dis_date == $checkdate) { $current = 1; }

 
			 ?>
			  <option value="<?= $dis_date ?>"<? if($current == true) { echo " selected"; } ?>><?= $dis_date2 ?></option>
			 <?
			 
			 $foo++;
			}

		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>

	   </table>
		<hr>
        <B><?= get_page_data(16) ?></B><br>
		<BR>
		<ul>
		 <li><a href="includes/bigbargraph6RE.php" class="nav"><?= get_page_data(17) ?></a></li>
		</ul>
		<hr>
        <b><?= get_page_data(18) ?></b><br>
		<table>
		<form action="includes/bigbargraph.php" method="POST">
		 <tr>
		  <td><?= get_word(38) ?>:</td>
		  <td><select name="graphdata">
		  <?

			$startmonth = "02";
			$startyear = "2000";
			$foo = 0;
			$current = false;
			
			while($current == false) {
			 
			 $dis_date = date("Y-m", mktime(1,1,1,$startmonth+$foo,1,$startyear));
			 $dis_date2 = date("M, Y", mktime(1,1,1,$startmonth+$foo,1,$startyear));
			 $checkdate = date("Y-m");
			 if($dis_date == $checkdate) { $current = 1; }

 
			 ?>
			  <option value="<?= $dis_date ?>"<? if($current == true) { echo " selected"; } ?>><?= $dis_date2 ?></option>
			 <?
			 
			 $foo++;
			}

		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>

	   </table>
		<hr>
        <b><?= get_page_data(19) ?></b><br>
		<table>
		<form action="includes/bigbargraph5.php" method="POST">
		 <tr>
		  <td><?= get_word(38) ?>:</td>
		  <td><select name="graphdata">
		  <?

			$startmonth = "02";
			$startyear = "2000";
			$foo = 0;
			$current = false;
			
			while($current == false) {
			 
			 $dis_date = date("Y-m", mktime(1,1,1,$startmonth+$foo,1,$startyear));
			 $dis_date2 = date("M, Y", mktime(1,1,1,$startmonth+$foo,1,$startyear));
			 $checkdate = date("Y-m");
			 if($dis_date == $checkdate) { $current = 1; }

 
			 ?>
			  <option value="<?= $dis_date ?>"<? if($current == true) { echo " selected"; } ?>><?= $dis_date2 ?></option>
			 <?
			 
			 $foo++;
			}

		  ?>
		  </select>
		  <input type="hidden" name="Country" value="<?= $Get_CID ?>">
		  </td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>

	   </table>
		<hr>
        <b><?= get_page_data(20) ?></b><br>
		<table>
		<form action="includes/bigbargraph7.php" method="POST">
		 <tr>
		  <td><?= get_word(40) ?></td>
		  <td><select name="graphdata">
		   <option value="1">1 Month</option>
		   <option value="2">2 Months</option>
		   <option value="3">3 Months</option>
		  </select>&nbsp;<?= get_page_data(30) ?>:&nbsp;<select name="graphdata2">
		   <option value="DB_Data2 DESC">Member Amount</option>
		   <option value="DB_Data1 ASC">Category Name</option>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>

	   </table>
	   <!--
		<hr>
        <b>New Members by Classification:</b><br>
		<table>
		<form action="includes/bigbargraph8.php" method="POST">
		 <tr>
		  <td>Months Prior:</td>
		  <td><select name="graphdata">
		   <option value="1">1 Month</option>
		   <option value="2">2 Months</option>
		   <option value="3">3 Months</option>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="Go"></td>
		 </tr>
		</form>

	   </table>
	   -->
		<hr>
        <b><?= get_page_data(21) ?></b><br>
		<table>
		<form action="includes/bigbargraph6.php" method="GET">
		 <tr>
		  <td><?= get_page_data(28) ?>:</td>
		  <td><select name="graphdata">
		  <option selected value="3">3 Months</option>
          <option value="6">6 Months</option>
          <option value="12">12 Months</option>
		  </select>&nbsp;<?= get_page_data(29) ?>:<select name="graphdata2">
		  <option selected value="12">1</option>
          <option value="24">2</option>
          <option value="36">3</option>
          <option value="60">5</option>
          <option value="120">10</option>
		  </select>&nbsp;<input type="hidden" name="Country" value="<?= $Get_CID ?>"></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>

	   </table>
		<hr>
        <b><?= get_page_data(22) ?></b><br>
		<table>
		<form action="includes/bigbargraph9.php" method="POST">
		 <tr>
		  <td><?= get_page_data(28) ?>:</td>
		  <td><select name="graphdata">
		  <option selected value="3">3 Months</option>
          <option value="6">6 Months</option>
          <option value="12">12 Months</option>
		  </select>&nbsp;<?= get_page_data(29) ?>:<select name="graphdata2">
		  <option selected value="12">1</option>
          <option value="24">2</option>
          <option value="36">3</option>
          <option value="60">5</option>
          <option value="120">10</option>
		  </select>&nbsp;<select name="area">
		  <?
		   $query = dbRead("select * from area where CID = '".$Get_CID."'$areas order by place");
		   while($row = mysql_fetch_assoc($query)) {
		   
		    ?>
		     <option value="<?= $row[FieldID] ?>"><?= $row[place] ?></option>
		    <?
		   
		   }
		  ?>
		  </select></td>
		  <td><input type="Submit" name="Graphs" value="<?= get_word(83) ?>"></td>
		 </tr>
		</form>

	   </table>
	    <hr>
        <b><?= get_page_data(23) ?></b><br>
	    </p>
	   <ul>
	    <li>
        <a class="nav" href="includes/bigbargraph2.php"><?= get_page_data(25) ?></a></li>
	   </ul>
	   <hr>
        <b><?= get_page_data(24) ?></b><br>
	    </p>
	   <ul>
	    <li>
        <a class="nav" href="includes/bigbargraph3.php">
        <?= get_page_data(26) ?></a></li>
	   </ul>	   
	   <hr>
        <b>Member Growth by State (Australia)</b><br>
	    </p>
	   <ul>
	    <li>
        <a class="nav" href="includes/bigbargraph4.php">
        Member growth by State</a></li>
	   </ul>	   
	   </td>

 	 </tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    &nbsp;</td>

 	 </tr>
</table>
</td>
</tr>
</table>

</body>
</html>