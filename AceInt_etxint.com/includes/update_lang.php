<form method="POST" action="body.php?page=update_lang&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

$tabarray = array('Lang Update','Add Lang');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Lang Update") {

  lang();

} elseif($_GET[tab] == "Add Lang") {

  langadd();
}

?>

</form>

<?
function lang() {

 if($_REQUEST['searchdata'])  {
?>
<body onload="javascript:setFocus('changearea','tradeq');">
<form method="post" action="body.php?page=update_admin" name="changearea">
<table border="0" width="620" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $sql_query = dbRead("select * from tbl_lang_keywords where langcode = '".$_REQUEST['langcode']."' order by wordid");
  while($row = mysql_fetch_assoc($sql_query)) {
  ?>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b><?= $row['wordid'] ?>:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="<?= $row['wordid'] ?>" size="30" maxlength="255" value="<?= $row['word'] ?>"></td>
   </tr>
 <?}?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update Data" name="updateadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
<input type="hidden" name="dataupdate" value="1">

</form>
</body>
 <?
 die;
 }

if($_REQUEST[dataupdate])  {

  $lang_query = dbRead("select wordid from tbl_lang_keywords group by wordid order by wordid");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_lang_keywords");

   $SQL->add_item("word", encode_text2($_REQUEST[$rowlang['wordid']]));

   $SQL->add_where("wordid = '".$rowlang['wordid']."'");
   $SQL->add_where("langcode = '".$_REQUEST['langcode']."'");

   dbWrite($SQL->get_sql_update());
  }
 }
//}

if(checkmodule("SuperUser")) {
 $SearchCID = "%";
} else {
 $SearchCID = $_SESSION['User']['CID'];
}

if(checkmodule("SuperUser")) {
 $SearchReports = "all";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
}

if($SearchReports == "all") {
 $query3 = dbRead("select FieldID from area where CID like '".$SearchCID."' order by place");
 //$query3 = dbRead("select FieldID from area where CID = 8 order by place");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= $row3['FieldID'].",";
 }
 $adminuserarray = explode(",", $at);
} else {
 $adminuserarray = explode(",", $SearchReports);
}?>

<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Lang:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$lang_query = dbRead("select Langcode from country group by Langcode order by Langcode");
            form_select('langcode',$lang_query,'Langcode','Langcode');
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

function langadd() {

 if($_REQUEST[adddata])  {

  $p_query = dbRead("select wordid as max from tbl_lang_keywords group by wordid order by wordid Desc");
  $p_row = mysql_fetch_assoc($p_query);
  //echo $p_row['max'];
  $pos = $p_row['max']+1;
  //echo $pos;
  $lang_query = dbRead("select Langcode from country group by Langcode order by Langcode");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_lang_keywords");

   $SQL->add_item("wordid", $pos);
   $SQL->add_item("langcode", encode_text2($rowlang['Langcode']));
   //$SQL->add_item("word", encode_text2($_REQUEST[$rowlang['Langcode']]));
   $SQL->add_item("word", encode_text2($_REQUEST[en]));

   dbWrite($SQL->get_sql_insert());
  }
 }

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<title>Change Area</title>
</head>

<body onload="javascript:setFocus('areaadd','tradeq');">
<?if($_REQUEST[adddata]) {?>
<table width="620" border="1" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
   <tr>
    <td bgcolor="#FFFFFF" align="center"><font color = #FF0000>The new data was added in Position Number: <?= $pos ?>&nbsp;</font></td>
   </tr>
</table><br>
<?}?>
<form method="post" action="body.php?page=update_admin" name="adminadd">

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Add New Data</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
<?
$lang_query = dbRead("select Langcode from country group by Langcode order by Langcode");

 while($rowlang = mysql_fetch_assoc($lang_query)) {?>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b><?= $rowlang['Langcode']?>:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="<?= $rowlang['Langcode'] ?>" size="30" maxlength="255" value="<?= $rowlang['Langcode'] ?>"></td>
  </tr>
<? }
?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="adddata" value="1">

</form>

</body>

<?
die;
}
