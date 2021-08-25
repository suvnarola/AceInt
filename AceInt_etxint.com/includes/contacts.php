<form method="POST" action="body.php">
 <input type="hidden" name="page" value="contacts">
 <input type="hidden" name="ID" value="<?= $_REQUEST[ID] ?>">
 <input type="hidden" name="tab" value="<?= $_REQUEST[tab] ?>">
<?
// Some Setup.
include("includes/modules/db.php");

add_kpi("58", "0");
 
$tabarray = array(get_page_data("5"),get_page_data("6")); 

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_REQUEST[tab] == "tab1") {
 
  lic();
 
} elseif($_REQUEST[tab] == "tab2") {
  
  empl();
  
} 

?>

</form>


<?

function lic() {

 if($_REQUEST['suspendlic'])  {
  
  $id = $_REQUEST['id'];
    
  $count = sizeof($id);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {
    dbWrite("update area set display = 'N' where FieldID='".$id[$i]."'");
  }
 }

 if(!$_REQUEST[countryID] && !$_REQUEST[search])  { 
   $searchcountry = $_SESSION['User']['CID'];
   $_REQUEST['search'] = "search";   
 } else {
   $searchcountry = $_REQUEST['countryID'];
   $_REQUEST['search'] = "search";   
 }
?>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>

<body>
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("1") ?></td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2"><b><?= get_word("79") ?>:</b></td>  
      <td width="450" bgcolor="#FFFFFF" >
          <?
		  $sql = "select * from country where Display = 'Yes' order by name" ;
           $query1 = dbRead( $sql );
           form_select('countryID',$query1,'name','countryID',$searchcountry,'All Countries');           
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



<?

if($_REQUEST[search]) {

$time_start = getmicrotime();

//if($_REQUEST[countryID])  {
if($searchcountry)  {
  $searchCID = $searchcountry;
} else {
  $searchCID = "%";
}

$sql = "select * from country where Display = 'Yes' and countryID like '$searchCID'" ;
//echo "<pre>" . print_r( $sql , true ) . "</pre>" ;
$query3 = dbRead( $sql );
while($row3 = mysql_fetch_assoc($query3)) {
?>
 <form name="checktrans" method="post" action="/includes/contactslicensees.php">

 <table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="5" align="center" class="Heading"><?= get_all_added_characters($row3['name']) ?></td>
	</tr>
	<tr>
		<td colspan="5" align="center" bgcolor="#EEEEEE"><b>Head Office</b></td>
	</tr>
		<tr bgcolor="#CCCCCC">
			<td width="160"><b><?= $row3['company'] ?></b></td>
			<td width="420" colspan = "4"><?= get_all_added_characters($row3['address1']) ?><br></a></td>
		</tr>
		<tr bgcolor="#CCCCCC">
			<td width="160" colspan = "1">&nbsp;&nbsp;&nbsp;<?= $row3[tradeq] ?></td>
			<td width="420" colspan = "4"><?= get_all_added_characters($row3['address2']) ?><br></a></td>			
		</tr>
		<tr bgcolor="#CCCCCC">
		    <td width="133">&nbsp;&nbsp;&nbsp;<b>Tel:</b> <?= get_all_added_characters($row3['phone']) ?></td>
			<td width="133" style="padding: 0"><b>Fax:</b> <?= get_all_added_characters($row3['fax']) ?></td>
			<td width="133" style="padding: 0"><b>Mobile:</b> <?= get_all_added_characters($row3[mobile]) ?></td>
			<td width="220" colspan = "4" style="padding: 0"><b>Email:</b> <?= get_all_added_characters($row3['email']) ?></td>
		</tr>

<?

$foo = 0;

$sql = "select * from area where CID = ".$row3[countryID]." and display ='Y' order by state,place" ;
//echo "<pre>" . print_r( $sql , true ) . "</pre>" ;
 $query2  = dbRead( $sql );
 
 if(mysql_num_rows($query2) == 0) {
 
?>
	<tr>
		<td colspan="5" align="center" bgcolor="#EEEEEE"><b><?= get_page_data("2") ?></b></td>
	</tr>
<?
 
 }

 while($row2 = mysql_fetch_assoc($query2)) {
 
 if($LocationTemp != $row2['state']) {

?>
	<tr>
		<td colspan="5" align="left" class="Heading"><?= get_all_added_characters($row2['state']) ?></td>
	</tr>
<?
 
 }
 
 $LocationTemp = $row2['state'];
 $paddress = "";
 
	if($row2['p_address'] != $row2['r_address'])  {
	  $paddress = $row2['p_address'];		
	} 
	
	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;+
	
	$address = "$row2[street], $row2[suburb], $row2[state], $row2[postcode]"
	

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="160"><b><?= $row2[place] ?></b></td>
			<td width="420" colspan = "4"><?= get_all_added_characters($row2['r_address']) ?><br></a></td>
		</tr>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="160" colspan = "1">&nbsp;&nbsp;&nbsp;<?= get_all_added_characters($row2[tradeq]) ?></td>
			<td width="420" colspan = "4"><?= get_all_added_characters($paddress) ?><br></a></td>			
		</tr>
		<tr bgcolor="<?= $bgcolor ?>">
		    <td width="133">&nbsp;&nbsp;&nbsp;<b>Tel:</b> <?= get_all_added_characters($row2[phone]) ?></td>
			<td width="133" style="padding: 0"><b>Fax:</b> <?= get_all_added_characters($row2[fax]) ?></td>
			<td width="133" style="padding: 0"><b>Mobile:</b> <?= get_all_added_characters($row2[mobile]) ?></td>
			<td width="220" style="padding: 0"><b>Email:</b> <?= get_all_added_characters($row2[email]) ?></td>
			<td width="30"><?if(checkmodule("AuthCheck") && $_SESSION['User']['CID'] == $row2['CID'])  {?><input type="checkbox" name="id[]" value="<?= $row2['FieldID'] ?>"><?}?></td>			
		</tr>
<?

$foo++;

}
?>
</table>
</td>
</tr>	
	
<?
}
?>

<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	    <tr>
	        <td colspan="5" bgcolor="#FFFFFF" align="right"><input type="button" value="<?= get_page_data(11) ?>" onclick="location.href='includes/contactslicensees.php?countryID=<?= $searchCID ?>&contactpdf=true'"></td>
	    </tr>
  <?if(checkmodule("AuthCheck"))  {?>
  <tr>
    <td colspan="5" align="right" bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("10") ?>" name="suspendbu"></td>
  </tr>
  <?}?>	    
		<tr>
		    <td colspan="5" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
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
<input type="hidden" value="tab1" name="tab">
<input type="hidden" value="1" name="suspendlic">
<input type="hidden" value="1" name="contactpdf">
<input type="hidden" value="<?= $data ?>" name="<?= data ?>">
</form>

</body>
</html>
<?
}

}

function empl() {

 if($_REQUEST['suspend'])  {
  
  $id = $_REQUEST['id'];
  $id2 = $_REQUEST['id2'];

  $count2 = sizeof($id2);
  $i = 0;
  for ($i = 0; $i <= $count2; $i++) {
    dbWrite("update tbl_admin_users set SalesPerson = 0 where FieldID='".$id2[$i]."'");
  }
    
  $count = sizeof($id);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {
    dbWrite("update tbl_admin_users set Suspended = '1' where FieldID='".$id[$i]."'");
  }
 }

 if(!$_REQUEST[countryID] && !$_REQUEST[search])  { 
   $searchcountry = $_SESSION[User][CID];
   $_REQUEST[search2] = "search";   
 } else {
   $searchcountry = $_REQUEST[countryID];
   $_REQUEST[search2] = "search";   
 }
?>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=220,height=200');
}
</script>
</head>

<body>
<form method="post" action="body.php">
 <input type="hidden" name="page" value="contacts">
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
     <td colspan="2" align="center" class="Heading"><?= get_page_data("3") ?></td>
  </tr>
  <tr>
      <td align="right" width="150" class="Heading2"><b><?= get_word("79") ?>:</b></td>  
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country where Display = 'Yes' order by name");
           form_select('countryID',$query1,'name','countryID',$searchcountry,'All Countries');           
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

<input type="hidden" name="search2" value="1">

</form>


<?

if($_REQUEST[search2]) {

$time_start = getmicrotime();

if($searchcountry)  {
  $searchCID = $searchcountry;
} else {
  $searchCID = "%";
}


$query3 = dbRead("select * from country where Display = 'Yes' and countryID like '$searchCID'");
while($row3 = mysql_fetch_assoc($query3)) {
?>
 <form name="checktrans" method="get" action="body.php">
 <input type="hidden" name="page" value="contacts">
 <table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading"><?= get_all_added_characters($row3['name']) ?></td>
	</tr>
<?

$foo = 0;

 $query2  = dbRead("select tbl_admin_users.*, state, place from tbl_admin_users, area where (tbl_admin_users.Area = area.FieldID) and tbl_admin_users.CID = ".$row3[countryID]." and Name != '' and (Suspended !='1') order by state,area.place, Name");
 
 if(mysql_num_rows($query2) == 0) {
 
?>
	<tr>
		<td colspan="6" align="center" bgcolor="#EEEEEE"><b><?= get_page_data("4") ?></b></td>
	</tr>
<?
 
 }
 
 $LocationTemp = "";
 $AreaTemp = ""; 
 while($row2 = mysql_fetch_assoc($query2)) {
 
 if($LocationTemp != $row2['state']) {

?>
	<tr>
		<td colspan="6" align="left" class="Heading"><?= get_all_added_characters($row2['state']) ?></td>
	</tr>
<?
 
 }
 
 if($AreaTemp != $row2['place']) {

?>
	<tr>
 		    <?if(checkmodule("AuthCheck") && $_SESSION['User']['CID'] == $row2['CID'])  {?>
		<td colspan="4" align="left" bgcolor="#666666" style="color: #DDDDDD"><b><?= get_all_added_characters($row2['place']) ?></b></td>
			<td bgcolor="#666666" style="color: #DDDDDD"><b>Acc</b></td>
			<td bgcolor="#666666" style="color: #DDDDDD"><b>S/P</b></td>
			<?} else {?>
		<td colspan="6" align="left" bgcolor="#666666" style="color: #DDDDDD"><b><?= get_all_added_characters($row2['place']) ?></b></td>
			<?}?>
	</tr>
<?

 } 
 
 $LocationTemp = $row2['state'];
 $AreaTemp = $row2['place'];

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;+

	$address = "$row2[street], $row2[suburb], $row2[state], $row2[postcode]"	
?>
		<tr bgcolor="<?= $bgcolor ?>">
 		<?
 		if($AreaTemp != $row2[place]) {?>
			<td width="200" colspan="6"><b><?= get_all_added_characters($row2[place]) ?></b></td>			
        <?
        }?>		
		</tr>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="200"><?if (checkmodule("SuperUser")) {?><a href="body.php?page=UserManagement&EditUser=true&User=<?= $row2['FieldID'] ?>&tab=Users" class="nav"><?= get_all_added_characters($row2[Name]) ?></a><?} else {?>&nbsp;&nbsp;&nbsp;<?= get_all_added_characters($row2[Name]) ?><?}?></td>
			<td width="200"><?= $row2[Position2] ?></td>
			<td width="110"><?= get_all_added_characters($row2[Mobile]) ?></td>
			<td width="110"><?= get_all_added_characters($row2[EmailAddress]) ?></td>
			<td width="30"><?if(checkmodule("AuthCheck") && $_SESSION['User']['CID'] == $row2['CID'])  {?><input type="checkbox" name="id[]" value="<?= $row2['FieldID'] ?>"><?}?></td>			
			<td width="30"><?if(checkmodule("AuthCheck") && $_SESSION['User']['CID'] == $row2['CID'])  {?><?if($row2[SalesPerson] == 1)  {?><input type="checkbox" name="id2[]" value="<?= $row2['FieldID'] ?>"><?} }?></td>			
		</tr>
		<?if($row2['skype_id']) {?>
		<tr bgcolor="<?= $bgcolor ?>">
		 <td width="400" colspan=2><td>
		 <td width="110" align=left colspan=2>Skype ID: <?= $row2['skype_id'] ?><td>
		</td>
		</tr>
		<?}?>
<?
$AreaTemp = $row2[place];
$foo++;

}
?>
</table>
</td>
</tr>	
	
<?
}
?>

<tr>
<td>
</td>
</tr>
<tr>
  <td colspan="6" align="right" bgcolor="#FFFFFF"><?if(checkmodule("AuthCheck"))  {?><input type="submit" value="<?= get_page_data("8") ?>" name="suspendbt"><?}?></td>
</tr>
</table>
<input type="hidden" value="<?= $searchCID ?>" name="countryID">
<input type="hidden" value="tab2" name="tab">
<input type="hidden" value="1" name="suspend">
</form>

</body>
</html>
<?
}

}

function check_disabled() {

 if(!checkmodule("SuperUser")) {
 
  return " disabled";
 
 }

}
?>