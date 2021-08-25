<?
$yesno = array('Y' => 'Yes', 'N' => 'No');

if(!checkmodule("CatEdit")) {
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


if($_REQUEST['catupdate']) {

 //$query = dbRead("select categories.*, tbl_cat_providers.providerID from categories LEFT OUTER JOIN tbl_cat_providers on (categories.catid = tbl_cat_providers.providerID and tbl_cat_providers.CatID = ".$_REQUEST['categoryid'].") where CID = 1 order by category");
 $query = dbRead("select tbl_cat_main.*, tbl_cat_link.Sub_ID from tbl_cat_main LEFT OUTER JOIN tbl_cat_link on (tbl_cat_main.FieldID = tbl_cat_link.Main_ID and tbl_cat_link.Sub_ID = ".$_REQUEST['categoryid'].") where CID = 1 order by C_Name");

 while($row = mysql_fetch_assoc($query)) {

  if($row['Sub_ID'] != $_REQUEST[$row['FieldID']]) {

    if(!$row['Sub_ID'] && $_REQUEST[$row['FieldID']]) {
      dbWrite("insert into tbl_cat_link (Main_ID,Sub_ID) values ('".$row['FieldID']."','".$_REQUEST['categoryid']."')");
	} elseif(!$_REQUEST[$row['FieldID']] && $row['Sub_ID']) {
      dbWrite("delete from tbl_cat_link where Main_ID = " . $row['FieldID'] . " and Sub_ID = " . $_REQUEST['categoryid']);
	}

  }

 }

}

if($_REQUEST['categoryid']) {
  $cc = $_REQUEST['categoryid'];
} elseif($_REQUEST['category']) {
  $cc = $_REQUEST['category'];
} else {
  $cc = "";
}
?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form name="categoryedit" method="POST" action="body.php?page=catlinks">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category
    <span lang="en-us">Links Edit</span></tr>
  <tr>
    <td width="100%" align="center" class="Heading2" colspan="2"><font color="#FF0000"><?= $error ?></font></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><span lang="en-us">Category</span>:<td width="70%" bgcolor="#FFFFFF">
    <select size="1" name="category">
	<?
	 $query2 = dbRead("select * from categories where CID='".$_SESSION['User']['CID']."' order by category");
	 while($row2 = mysql_fetch_assoc($query2)) {
	 ?>
	 <option <?if($cc == $row2['catid']) {?>selected<?}?> value="<?= $row2['catid'] ?>"><?= $row2['category'] ?></option>
	 <?
	 }
	?>
    </select></tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
    <input type="hidden" name="catedit" value="1">
    <button name="sub" type="submit">
    <span lang="en-us">Select Category</span>
    </button></tr>
</table>
</td>
</tr>
</table>

</form>

<?
if($_REQUEST['catedit']) {

 #get category data.
 $query3 = dbRead("select tbl_cat_main.*, tbl_cat_link.Sub_ID from tbl_cat_main LEFT OUTER JOIN tbl_cat_link on (tbl_cat_main.FieldID = tbl_cat_link.Main_ID and tbl_cat_link.Sub_ID = ".$_REQUEST['category'].") where CID = 1 order by C_Name");

?>

<form name="categoryupdate" method="POST" action="body.php?page=catlinks">
<input type="hidden" name="categoryid" value="<?= $_REQUEST['category'] ?>">
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
 <td class="Border">
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="3" class="Heading" align="center">Category Links Edit
  </tr>

<?
 $counter = 1;
 while($catrow = mysql_fetch_assoc($query3)) {

  if($counter == 1) {
?>
  <tr>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="<?= $catrow['FieldID'] ?>" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
<?
  } elseif($counter == 2) {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="<?= $catrow['FieldID'] ?>" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
<?
  } else {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="<?= $catrow['FieldID'] ?>" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
   </tr>
<?
   $counter = 0;
  }
  $counter++;
 }

?>
  <tr>
    <input type="hidden" name="catupdate" value="1">
    <td width="30%" colspan=2 class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
    <button name="sub2" type="submit">Update</button></td>
  </tr>
 </table>
 </td>
</tr>
</table>

</form>

</body>

</html>

<?
die;
}
?>