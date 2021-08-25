<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=update_proc&tab=Add Proc&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

function ChangeCountry2(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=update_proc&tab=Proc Update&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}
</script>
</head>
<form method="POST" action="body.php?page=update_proc&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

// Some Setup.
include("includes/modules/db.php");

$tabarray = array('Proc Update','Add Proc');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Proc Update") {

  data();

} elseif($_GET[tab] == "Add Proc") {

  addproc();
}

?>

</form>
<?
function data() {

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

 if($_REQUEST['searchpos'])  {

  $query = dbRead("select * from tbl_proc_data where fieldid = '".addslashes($_REQUEST['fieldid'])."'");
  $row = mysql_fetch_assoc($query);

 ?>

<input type="hidden" name="fieldid" value="<?= $row[procid] ?>">
<input type="hidden" name="posid" value="<?= $_REQUEST['fieldid'] ?>">
<input type="hidden" name="searchdata" value="1">
<input type="hidden" name="Proc Update" value="1">
<input type="hidden" name="updatepos" value="1">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Edit Procedure</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="position" size="55" maxlength="255" value="<?= $row['position'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position Title:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="pos_title" size="55" maxlength="255" value="<?= $row['pos_title'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position Data:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><textarea  name="pos_data" cols="45" rows="12"><?= $row['pos_data'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>


 <?
 die;
 }

 if($_REQUEST['searchdata'])  {

   if($_REQUEST[updatepos])  {

       $SQL = new dbCreateSQL();

       $SQL->add_table("tbl_proc_data");

       $SQL->add_item("position", encode_text2($_REQUEST['position']));
       $SQL->add_item("pos_title", encode_text2($_REQUEST['pos_title']));
       $SQL->add_item("pos_data", encode_text2($_REQUEST['pos_data']));

       $SQL->add_where("fieldid = '".$_REQUEST['posid']."'");

       dbWrite($SQL->get_sql_update());

   }

   if($_REQUEST[updateproc])  {

       $SQL = new dbCreateSQL();

       $SQL->add_table("tbl_procedure");

       $SQL->add_item("proc_name", encode_text2($_REQUEST['proc_name']));
       $SQL->add_item("proc_purpose", encode_text2($_REQUEST['proc_purpose']));
       $SQL->add_item("proc_ad", encode_text2($_REQUEST['proc_ad']));

       $SQL->add_where("fieldid = '".$_REQUEST['fieldid']."'");

       dbWrite($SQL->get_sql_update());

   }

   $query = dbRead("SELECT * from tbl_procedure where fieldid = '".$_REQUEST[fieldid]."'");
   $row = mysql_fetch_assoc($query);
?>
<body onload="javascript:setFocus('changearea','tradeq');">
<table border="0" cellspacing="0" cellpadding="1" width="610">
 <tr>
  <td class="Border">
<table border="0" width="610" cellpadding="1" cellspacing="0">

  <input type="hidden" name="fieldid" value="<?= $row[fieldid] ?>">
  <input type="hidden" name="searchdata" value="1">
  <input type="hidden" name="Proc Update" value="1">
  <input type="hidden" name="updateproc" value="1">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Edit Procedure</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Code:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_code" size="30" maxlength="255" value="<?= $row['proc_code'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Number:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_no" size="30" maxlength="255" value="<?= $row['proc_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Name:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_name" size="60" maxlength="255" value="<?= $row['proc_name'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Purpose:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_purpose" size="60" maxlength="255" value="<?= $row['proc_purpose'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Related Doc:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_ad" size="60" maxlength="255" value="<?= $row['proc_ad'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
    <br><br></td>
  </tr>
  </table>
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="left" valign="middle" class="Heading2" width="30%"><b>Position:</b></td>
    <td align="left" valign="middle" class="Heading2" width="30%"><b>Position Title:</b></td>
    <td align="left" valign="middle" class="Heading2" width="30%"><b>Edit:</b></td>
   </tr>
   <?
   $query = dbRead("SELECT * from tbl_proc_data where procid = '".$_REQUEST[fieldid]."' order by position");
   while($row = mysql_fetch_assoc($query)) {
   ?>
   <tr bgcolor="#FFFFFF">
    <td align="left" valign="top" width="30%"><?= $row[position]?></td>
    <td align="left" valign="top" width="30%"><?= $row[pos_title]?></td>
    <td align="left" valign="top" width="30%"><a href="body.php?page=update_proc&searchpos=true&fieldid=<?= $row['fieldid'] ?>&tab=Proc Update" class="nav">Edit</a></td>
   </tr>
   <?}?>
  </table>
  </td>
 </tr>
</table>
 <?
 die;
 }

$query = dbRead("SELECT * from tbl_procedure where CID = '".$GET_CID."'order by proc_code, proc_no");

?>
<table border="0" cellspacing="0" cellpadding="1" width="610">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
<?if($_SESSION['User']['Area'] == 1)  {?>
 <tr>
  <td colspan="3" height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry2(this);">
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
    <td align="left" valign="middle" class="Heading2" width="30%"><b>ProcCode:</b></td>
    <td align="left" valign="middle" class="Heading2" width="30%"><b>ProcNo:</b></td>
    <td align="left" valign="middle" class="Heading2" width="30%"><b>ProcName:</b></td>
   </tr>
   <?
   while($row = mysql_fetch_assoc($query)) {
   ?>
   <tr bgcolor="#FFFFFF">
    <td align="left" valign="top" width="30%"><?= $row[proc_code]?></td>
    <td align="left" valign="top" width="30%"><?= $row[proc_no]?></td>
    <td align="left" valign="top" width="30%"><a href="body.php?page=update_proc&searchdata=true&fieldid=<?= $row['fieldid'] ?>&tab=Proc Update" class="nav"><?= $row[proc_name]?></a></td>
   </tr>
   <?}?>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="fieldid" value="<?= $row[fieldid]?>">
<input type="hidden" name="searchdata" value="1">

<?
}

function addproc() {

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

if($_REQUEST[addpos]) {

  $SQLTest = dbRead("select count(*) as Test from tbl_proc_data where procid = '".addslashes($_REQUEST['procid'])."' and position = '".addslashes($_REQUEST['position'])."' and CID = '".addslashes($GET_CID)."'");
  @$SQLTestRow = mysql_fetch_assoc($SQLTest);
  if($SQLTestRow['Test'] > 0) {

    echo "Procudure Position Exists";

  } else {

    $SQL = new dbCreateSQL();

    $SQL->add_table("tbl_proc_data");

    $SQL->add_item("procid", encode_text2($_REQUEST['procid']));
    $SQL->add_item("position", encode_text2($_REQUEST['position']));
    $SQL->add_item("pos_title", encode_text2($_REQUEST['pos_title']));
    $SQL->add_item("pos_data", encode_text2($_REQUEST['pos_data']));
    $SQL->add_item("CID", encode_text2($GET_CID));

    $MailID = dbWrite($SQL->get_sql_insert(),'etradebanc',true);
?>
<input type="hidden" name="procid" value="<?= $_REQUEST['procid'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Add New Procedure</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="position" size="30" maxlength="255" value="<?= $_REQUEST['positionl'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position Title:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="pos_title" size="30" maxlength="255" value="<?= $_REQUEST['pos_titlel'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position Data:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><textarea  name="pos_data" cols="45" rows="12"><?= $row['pos_datal'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="countryid" value="<?= $GET_CID ?>">
<input type="hidden" name="addpos" value="1">
<?
die;
  }
}

if($_REQUEST[adddata]) {

  $SQLTest = dbRead("select count(*) as Test from tbl_procedure where proc_code = '".addslashes($_REQUEST['proc_code'])."' and proc_no = '".addslashes($_REQUEST['proc_no'])."' and CID = '".addslashes($GET_CID)."'");
  @$SQLTestRow = mysql_fetch_assoc($SQLTest);
  if($SQLTestRow['Test'] > 0) {

    echo "Procudure Exists";

  } else {

    $SQL = new dbCreateSQL();

    $SQL->add_table("tbl_procedure");

    $SQL->add_item("proc_code", encode_text2($_REQUEST['proc_code']));
    $SQL->add_item("proc_no", encode_text2($_REQUEST['proc_no']));
    $SQL->add_item("proc_name", encode_text2($_REQUEST['proc_name']));
    $SQL->add_item("proc_purpose", encode_text2($_REQUEST['proc_purpose']));
    $SQL->add_item("proc_ad", encode_text2($_REQUEST['proc_ad']));
    $SQL->add_item("CID", encode_text2($GET_CID));

    $MailID = dbWrite($SQL->get_sql_insert(),'etradebanc',true);
?>
<input type="hidden" name="procid" value="<?= $MailID ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Add New Procedure</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="position" size="30" maxlength="255" value="<?= $_REQUEST['position'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position Title:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="pos_title" size="30" maxlength="255" value="<?= $_REQUEST['pos_title'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Position Data:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><textarea  name="pos_data" cols="45" rows="12"><?= $row['pos_data'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="countryid" value="<?= $GET_CID?>">
<input type="hidden" name="addpos" value="1">
<?
  die;
  }
?>

<?
}
?>

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
<?if($_SESSION['User']['Area'] == 1)  {?>
 <tr>
  <td colspan="2" height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
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
    <td colspan="2" align="center" class="Heading"><b>Add New Procedure</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Code:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_code" size="30" maxlength="255" value="<?= $_REQUEST['proc_code'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Number:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_no" size="30" maxlength="255" value="<?= $_REQUEST['proc_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Name:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_name" size="30" maxlength="255" value="<?= $_REQUEST['proc_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Procedure Purpose:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_purpose" size="30" maxlength="255" value="<?= $_REQUEST['proc_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Related Doc:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="proc_ad" size="30" maxlength="255" value="<?= $_REQUEST['proc_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="countryid" value="<?= $GET_CID ?>">
<input type="hidden" name="adddata" value="1">

<?
}
