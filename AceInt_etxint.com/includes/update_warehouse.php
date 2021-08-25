<form enctype="multipart/form-data" method="POST" action="body.php?page=update_warehouse&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

$tabarray = array('Warehouse Update','Add Warehouse');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Warehouse Update") {

  spot();

} elseif($_GET[tab] == "Add Warehouse") {

  spotadd();
}

?>

</form>

<?
function spot() {

if($_REQUEST['searchdata'])  {

if($_REQUEST[dataupdate])  {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_warehouse");

   $SQL->add_item("ware_header", encode_text2($_REQUEST['ware_header']));
   $SQL->add_item("ware_text", encode_text2($_REQUEST['ware_text']));
   $SQL->add_item("ware_cat", encode_text2($_REQUEST['ware_cat']));
   $SQL->add_item("ware_retail", encode_text2($_REQUEST['ware_retail']));
   $SQL->add_item("ware_eb", encode_text2($_REQUEST['ware_eb']));
   $SQL->add_item("ware_percent", encode_text2($_REQUEST['ware_percent']));

   $SQL->add_where("fieldid = '".$_REQUEST['fieldid']."'");

   dbWrite($SQL->get_sql_update());

}

if($_REQUEST[delete])  {

  dbWrite("delete from tbl_warehouse where fieldid = '".$_REQUEST['fieldid']."'");

}
?>
<table border="0" width="620" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $sql_query = dbRead("select * from tbl_warehouse where CID = 1 order by ware_no, langcode");
  while($row = mysql_fetch_assoc($sql_query)) {
  ?>

   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b><?= $row['ware_no'] ?>:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><a href="body.php?page=update_warehouse&tab=Warehouse Update&edit2=1&fieldid=<?= $row['fieldid'] ?>&CID=<?= $_REQUEST['CID'] ?>" class="nav"><?= $row['ware_header'] ?>(<?= $row['langcode'] ?>)</a></td>
   </tr>
 <?}?>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
</form>
</body>
 <?
 die;
 }

if($_REQUEST['edit2'])  {
?>
<table border="0" width="550" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $sql_query = dbRead("select * from tbl_warehouse where fieldid = '".$_REQUEST['fieldid']."'");
  $row = mysql_fetch_assoc($sql_query);
  ?>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Header:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="ware_header" size="50" value="<?= $row['ware_header'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Text:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><textarea name="ware_text" size="40" rows="6" value="<?= $row['ware_text'] ?>" cols="40"><?= $row['ware_text'] ?></textarea></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Category:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
       <select size="1" name="ware_cat">
       <option <? if($row['ware_cat'] == 1) { echo "selected "; }?>value="1">Italian Lounges</option>
       <option <? if($row['ware_cat'] == 2) { echo "selected "; }?>value="2">Australian Lounges</option>
       <option <? if($row['ware_cat'] == 3) { echo "selected "; }?>value="3">Specials</option>
       </select></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Retail Price:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="ware_retail" size="50" value="<?= $row['ware_retail'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>EB Price:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="ware_eb" size="50" value="<?= $row['ware_eb'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>EB Percent:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="ware_percent" size="50" value="<?= $row['ware_percent'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update Spotlight" name="dataupdate" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Delete Spotlight" name="delete" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>

<input type="hidden" name="fieldid" value="<?= $_REQUEST['fieldid'] ?>">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
<input type="hidden" name="searchdata" value="1">

 <?
 die;
 }

if(checkmodule("SuperUser")) {
 $lim = "";
} else {
 $lim = " where countryID = ".$_SESSION['User']['CID']."";
}
?>

<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$country_query = dbRead("select * from country $lim order by name");
            form_select('CID',$country_query,'name','countryID');
           ?>
    </td>
   </tr>

   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="search Data" name="datasearch" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="searchdata" value="1">

<?
}

function check_disabled() {

 if(!checkmodule("SuperUser")) {

  return " disabled";

 }

}

function spotadd() {

 if($_REQUEST[adddata])  {

  $p_query = dbRead("select ware_no as max from tbl_warehouse where CID = ".$_REQUEST['CID']." group by ware_no order by ware_no Desc");
  $p_row = mysql_fetch_assoc($p_query);
  $pos = $p_row['max']+1;

  $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_warehouse");

   $SQL->add_item("CID", encode_text2($rowlang['CID']));
   $SQL->add_item("langcode", encode_text2($rowlang['langcode']));
   $SQL->add_item("ware_no", $pos);
   $SQL->add_item("ware_header", encode_text2($_REQUEST[$rowlang['langcode']."_header"]));
   $SQL->add_item("ware_text", encode_text2($_REQUEST[$rowlang['langcode']."_text"]));
   $SQL->add_item("ware_cat", encode_text2($_REQUEST[$rowlang['langcode']."_cat"]));
   $SQL->add_item("ware_retail", encode_text2($_REQUEST[$rowlang['langcode']."_retail"]));
   $SQL->add_item("ware_eb", encode_text2($_REQUEST[$rowlang['langcode']."_eb"]));
   $SQL->add_item("ware_percent", encode_text2($_REQUEST[$rowlang['langcode']."_percent"]));

   $clid = dbWrite($SQL->get_sql_insert(),etradebanc,true);

			if($_FILES[$rowlang['langcode'].'_image']['tmp_name']) {

			 $ext = strstr ($_FILES[$rowlang['langcode'].'_image']['name'], '.');
echo $_FILES[$rowlang['langcode'].'_image']['tmp_name'];
			 move_uploaded_file($_FILES[$rowlang['langcode'].'_image']['tmp_name'], "/home/etxint/public_html/home/images/warehouse/".$clid."".$ext."");
			 $source = "/home/etxint/public_html/home/images/warehouse/$clid$ext";
			 $dest = "/home/etxint/public_html/home/images/warehouse/thumb-$clid$ext";
			 //$dest2 = "/home/etxint/public_html/home/images/warehouse/thumb2-$clid$ext";
			 copy($source, $dest);
			 //copy($source, $dest2);
			 exec("convert -geometry 200 /home/etxint/public_html/home/images/warehouse/thumb-$clid$ext /home/etxint/public_html/home/images/warehouse/thumb-$clid$ext");
			 //exec("convert -geometry 150 /home/etxint/public_html/home/images/warehouse/thumb2-$clid$ext /home/etxint/public_html/home/warehouse/ware/thumb2-$clid$ext");

             dbWrite("update tbl_warehouse set ware_picture = '".$clid.$ext."' where fieldid = ".$clid."");

			} else {

			 $clid="";

			}

  }
 }


if($_REQUEST['searchdata2'])  {

?>
<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="550" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="3" align="center" class="Heading"><b>Add New Data</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="Heading2"><b></b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
    <td align="center" valign="middle" class="Heading2"><b><?= $rowlang[langcode]?></b></td>
    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Header:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_header" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Text:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><textarea name="<?= $rowlang['langcode'] ?>_text" size="35" rows="8" cols="35"></textarea></td>
    <?}?>
  </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Category:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
    <td bgcolor="#FFFFFF" width="70%">
       <select size="1" name="<?= $rowlang['langcode'] ?>_cat">
       <option value="1">Italian Lounges</option>
       <option value="2">Australian Lounges</option>
       <option value="3">Specials</option>
       </select></td>
    <?}?>
   </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Retail Price:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_retail" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>EB Price:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_eb" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>EB Percent:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_percent" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Image Upload:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="file" name="<?= $rowlang['langcode'] ?>_image" size="20"></td>
    <?}?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="15%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="85%" colspan="2"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="adddata" value="1">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
</form>

</body>

<?
die;
}

if(checkmodule("SuperUser")) {
 $lim = "";
} else {
 $lim = " where countryID = ".$_SESSION['User']['CID']."";
}
?>

<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$country_query = dbRead("select * from country $lim order by name");
            form_select('CID',$country_query,'name','countryID');
           ?>
    </td>
   </tr>

   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="search Data" name="datasearch" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="searchdata2" value="1">
<?
}
