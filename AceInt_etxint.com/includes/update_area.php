<form method="POST" action="body.php?page=update_area&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

$tabarray = array("Area Update");
if(checkmodule("SuperUser")) { $tabarray[] = "Add Area"; }

// Do Tabs if we need to.

 //tabs($tabarray);

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  area();

} elseif($_GET[tab] == "tab2") {

  areaadd();
}

?>

</form>

<?
function area() {

$yesno = array('Y' => 'Yes', 'N' => 'No');
$yesno2 = array('0' => 'No', '1' => 'Yes');

if($_REQUEST['data'])  {


$dbquery1 = dbRead("select * from area where FieldID = ".$_REQUEST['data']."");

$row = mysql_fetch_assoc($dbquery1);

?>


<form method="post" action="body.php?page=update_area" name="changearea">

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">
<input type="hidden" name="countryID" value="<?= $row['CID'] ?>">

<table border="0" width="620" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Area Edit</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Contact Info</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Area:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="place" size="30" value="<?= $row['place'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Contact:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tradeq" size="30" value="<?= $row['tradeq'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Residential Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="r_address" size="30" value="<?= $row['r_address'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Postal Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="p_address" size="30" value="<?= $row['p_address'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>State:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from tbl_area_states where CID = '".$row['CID']."' order by StateName");
            form_select('state',$sql_query,'StateName','StateName',$row['state']);

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Physical Area:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from tbl_area_physical where CID = '".$row['CID']."' order by AreaName");
            form_select('PhysicalID',$sql_query,'AreaName','FieldID',$row['PhysicalID']);

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Phone:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="phone" size="30" value="<?= $row['phone'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fax:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="fax" size="30" value="<?= $row['fax'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Mobile:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="mobile" size="30" value="<?= $row['mobile'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email" size="30" value="<?= $row['email'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Reports:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="reportemail" size="30" value="<?= $row['reportemail'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Publications:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="pubemail" size="30" value="<?= $row['pubemail'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Username Prefix:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="id" size="10" value="<?= $row['id'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fee %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="feepercent" size="10" value="<?= $row['feepercent'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Master Lic 1:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from area where CID = '".$_SESSION['User']['CID']."' order by place");
            form_select('locationID',$sql_query,'place','FieldID',$row['locationID'],'No Area');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Master Lic 2:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from area where CID = '".$_SESSION['User']['CID']."' order by place");
            form_select('LocationID2',$sql_query,'place','FieldID',$row['LocationID2'],'No Area');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>State Lists:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('statewide',$yesno2,'','',$row['statewide']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Drop Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('drop',$yesno,'','',$row['drop']); ?></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('display',$yesno,'','',$row['display']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Deduct before Inter Fees:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('inter',$yesno,'','',$row['inter']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from country where Display = 'Yes' order by name");
            form_select('CID',$sql_query,'name','countryID',$row['CID']);

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Change Area" name="changearea" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changemember" value="1">

</form>

</body>

<?
die;
}

if($_REQUEST[changemember])  {

  $query = dbRead("select * from area where FieldID = '".$_REQUEST['fieldid']."'");
  $row = mysql_fetch_assoc($query);

    if(!$_REQUEST['locationID']) {
     $_REQUEST['locationID'] = '0.00';
    }

    if(!$_REQUEST['LocationID2']) {
     $_REQUEST['LocationID2'] = '0.00';
    }

  foreach($_REQUEST as $key => $value) {

     $NIPageArray = array(
       'phpbb2mysql_data' => 'phpbb2mysql_data',
       'page' => 'page',
       'Client' => 'Client',
       'pageno' => 'pageno',
       'tab' => 'tab',
       'Update' => 'Update',
       'main' => 'main',
       'changemember' => 'changemember',
       'PHPSESSID' => 'PHPSESSID',
       'countryID' => 'countryID',
       'fieldid' => 'fieldid',
       'LocationID' => 'LocationID',
       'changearea' => 'changearea',
    );

    if(encode_text2($_REQUEST[$key]) != $row[$key]) {
     if($key != $NIPageArray[$key]) {
      $logdata[$key] = array($row[$key],encode_text2($value));
     }
    }
  }

  add_kpi2(3,$row['FieldID'],'0',$row['CID'],$logdata);

  $SQL = new dbCreateSQL();

  $SQL->add_table("area");

  $SQL->add_item("tradeq", encode_text2($_REQUEST['tradeq']));
  $SQL->add_item("r_address", encode_text2($_REQUEST['r_address']));
  $SQL->add_item("p_address", encode_text2($_REQUEST['p_address']));
  //$SQL->add_item("street", encode_text2($_REQUEST['street']));
  //$SQL->add_item("suburb", encode_text2($_REQUEST['suburb']));
  $SQL->add_item("state", encode_text2($_REQUEST['state']));
  //$SQL->add_item("postcode", encode_text2($_REQUEST['postcode']));
  $SQL->add_item("phone", encode_text2($_REQUEST['phone']));
  $SQL->add_item("fax", encode_text2($_REQUEST['fax']));
  $SQL->add_item("mobile", $_REQUEST['mobile']);
  //$SQL->add_item("disarea", encode_text2($_REQUEST['disarea']));
  $SQL->add_item("place", encode_text2($_REQUEST['place']));
  $SQL->add_item("email", encode_text2($_REQUEST['email']));
  $SQL->add_item("reportemail", encode_text2($_REQUEST['reportemail']));
  $SQL->add_item("pubemail", encode_text2($_REQUEST['pubemail']));
  $SQL->add_item("PhysicalID", encode_text2($_REQUEST['PhysicalID']));
  $SQL->add_item("id", encode_text2($_REQUEST['id']));
  $SQL->add_item("feepercent", $_REQUEST['feepercent']);
  $SQL->add_item("locationID", $_REQUEST['locationID']);
  $SQL->add_item("LocationID2", $_REQUEST['LocationID2']);
  $SQL->add_item("statewide", encode_text2($_REQUEST['statewide']));
  $SQL->add_item("drop", encode_text2($_REQUEST['drop']));
  $SQL->add_item("display", encode_text2($_REQUEST['display']));
  $SQL->add_item("inter", encode_text2($_REQUEST['inter']));
  //$SQL->add_item("CID", encode_text2($_REQUEST['CID']));

  $SQL->add_where("FieldID = '".$_REQUEST['fieldid']."'");
  dbWrite($SQL->get_sql_update());

}

 if(!$_REQUEST['countryID'] )  {
   $SearchCID = $_SESSION['User']['CID'];
 } else {
   $SearchCID = $_REQUEST['countryID'];
 }

//if(checkmodule("SuperUser")) {
 //$SearchCID = "%";
//} else {
 //$SearchCID = $_SESSION['User']['CID'];
//}

if(checkmodule("SuperUser")) {
 $SearchReports = "all";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
}

 $query3 = dbRead("select FieldID from area where CID like '".$SearchCID."' order by place");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= $row3['FieldID'].",";
 }
 $adminuserarray = explode(",", $at);

?>

<input type="hidden" name="data" value="<?= $row['FieldID'] ?>">

<table border="0" cellspacing="0" cellpadding="1" width="620">
<tr>
 <td class="Border">
   <table border="0" width="100%" cellspacing="0" cellpadding="3">
    <tr>
      <td colspan="2" align="center" class="Heading">Country Select</td>
    </tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$SearchCID);
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
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="4" align="center" class="Heading"><b>Area Details View</b></td>
  </tr>
 <?
 $count = sizeof($adminuserarray);
 $i = 0;
 $foo = 0;

 for ($i = 0; $i < $count; $i++) {

  $query = dbRead("select * from area where FieldID='$adminuserarray[$i]' order by place");
  $row = mysql_fetch_assoc($query);

  $paddress = "";

  if($row['p_address'] != $row['r_address'])  {
	$paddress = $row['p_address'];
  }

  $address = $row['street'];
  if($row['suburb']) {
   $address .= ", ".$row['suburb'];
  }
  $address .= ", ".$row['state'];
  $address .= ", ".$row['postcode'];

  $cfgbgcolorone = "#CCCCCC";
  $cfgbgcolortwo = "#EEEEEE";
  $bgcolor = $cfgbgcolorone;
  $foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
  ?>
  <tr bgcolor="<?= $bgcolor ?>">
	<td width="200"><b><a href="body.php?page=update_area&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>&data=<?= $row['FieldID'] ?>" class="nav"><b><?= $row['place'] ?> (<?= $row['FieldID'] ?>)</b></a></b></td>
	<td width="420" colspan = "3"><?= $row['r_address'] ?><br></a></td>
  </tr>
  <tr bgcolor="<?= $bgcolor ?>">
	<td width="200" colspan = "1">&nbsp;&nbsp;&nbsp;<?= $row[tradeq] ?></td>
	<td width="220" colspan = "2"><?= $paddress ?><br></a></td>
	<td width="200" colspan = "1"><?= $row['state'] ?> / <?= $row['disarea'] ?><br></a></td>
  </tr>
  <tr bgcolor="<?= $bgcolor ?>">
	<td width="133">&nbsp;&nbsp;&nbsp;<b>Tel:</b> <?= $row[phone] ?></td>
	<td width="133" style="padding: 0"><b>Fax:</b> <?= $row[fax] ?></td>
	<td width="133" style="padding: 0"><b>Mobile:</b> <?= $row[mobile] ?></td>
	<td width="220" style="padding: 0"><b>Email:</b> <?= $row[email] ?></td>
  </tr>
  <?$foo++;
  }?>
</table>
</td>
</tr>
</table>


<?
}

function check_disabled() {

 if(!checkmodule("SuperUser")) {

  return " disabled";

 }

}

function areaadd() {
$yesno = array('Y' => 'Yes', 'N' => 'No');

 if($_REQUEST[areaadd])  {

  $SQL = new dbCreateSQL();

  $SQL->add_table("area");

  $SQL->add_item("tradeq", encode_text2($_REQUEST['tradeq']));
  $SQL->add_item("r_address", encode_text2($_REQUEST['r_address']));
  $SQL->add_item("p_address", encode_text2($_REQUEST['p_address']));
  $SQL->add_item("state", encode_text2($_REQUEST['state']));
  $SQL->add_item("phone", encode_text2($_REQUEST['phone']));
  $SQL->add_item("fax", encode_text2($_REQUEST['fax']));
  $SQL->add_item("mobile", $_REQUEST['mobile']);
  $SQL->add_item("place", encode_text2($_REQUEST['place']));
  $SQL->add_item("email", encode_text2($_REQUEST['email']));
  $SQL->add_item("reportemail", encode_text2($_REQUEST['reportemail']));
  $SQL->add_item("pubemail", encode_text2($_REQUEST['pubemail']));
  $SQL->add_item("PhysicalID", encode_text2($_REQUEST['PhysicalID']));
  $SQL->add_item("id", encode_text2($_REQUEST['id']));
  $SQL->add_item("feepercent", $_REQUEST['feepercent']);
  $SQL->add_item("locationID", $_REQUEST['locationID']);
  $SQL->add_item("LocationID2", $_REQUEST['LocationID2']);
  $SQL->add_item("statewide", encode_text2($_REQUEST['statewide']));
  $SQL->add_item("drop", encode_text2($_REQUEST['drop']));
  $SQL->add_item("display", encode_text2($_REQUEST['display']));
  $SQL->add_item("inter", encode_text2($_REQUEST['inter']));
  $SQL->add_item("CID", encode_text2($_REQUEST['CID']));

  dbWrite($SQL->get_sql_insert());
 }

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<title>Change Area</title>
</head>

<body onload="javascript:setFocus('areaadd','tradeq');">

<form method="post" action="body.php?page=update_area" name="areaadd">

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Add Area</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Contact Info</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Area:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="place" size="30" value="<?= $row['place'] ?>" ></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Contact:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tradeq" size="30" value="<?= $row['tradeq'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Residential Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="r_address" size="30" value="<?= $row['r_address'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Postal Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="p_address" size="30" value="<?= $row['p_address'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>State:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
			if(!checkmodule("SuperUser")) {
			  $lim = " where CID = ". $_SESSION['User']['CID']."";
			} else {
			  $lim = "";
			}
            $sql_query = dbRead("select * from tbl_area_states$lim order by StateName");
            form_select('state',$sql_query,'StateName','StateName',$row['state'],'');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Physical Area:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
			if(!checkmodule("SuperUser")) {
			  $lim = " where CID = ". $_SESSION['User']['CID']."";
			} else {
			  $lim = "";
			}
            $sql_query = dbRead("select * from tbl_area_physical$lim order by AreaName");
            form_select('PhysicalID',$sql_query,'AreaName','FieldID',$row['PhysicalID'],'');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Phone:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="phone" size="30" value="<?= $row['phone'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fax:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="fax" size="30" value="<?= $row['fax'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Mobile:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="mobile" size="30" value="<?= $row['mobile'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email" size="30" value="<?= $row['email'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Reports:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="reportemail" size="30" value="<?= $row['reportemail'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Publications:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="pubemail" size="30" value="<?= $row['pubemail'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Username Prefix:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="id" size="10" value="<?= $row['id'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fee %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="feepercent" size="10" value="<?= $row['feepercent'] ?>" ></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Master Lic 1:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from area where CID = '".$_SESSION['User']['CID']."' order by place");
            form_select('locationID',$sql_query,'place','FieldID',$row['locationID'],'No Area');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Master Lic 2:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from area where CID = '".$_SESSION['User']['CID']."' order by place");
            form_select('LocationID2',$sql_query,'place','FieldID',$row['LocationID2'],'No Area');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>State Lists:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('statewide',$yesno,'','',$row['statewide'],''); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Drop Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('drop',$yesno,'','',$row['drop'],''); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('display',$yesno,'','',$row['display'],''); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('inter',$yesno,'','',$row['inter'],''); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
			if(!checkmodule("SuperUser")) {
			  $lim = " where CID = ". $_SESSION['User']['CID']."";
			} else {
			  $lim = "";
			}
            $sql_query = dbRead("select * from country where Display = 'Yes'$lim order by name");
            form_select('CID',$sql_query,'name','countryID',$row['CID'],'');

           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Area" name="areaadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="areaadd" value="1">

</form>

</body>

<?
die;
}
