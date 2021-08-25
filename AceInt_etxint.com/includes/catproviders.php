<?
$yesno = array('Y' => 'Yes', 'N' => 'No');

if(!checkmodule("CatDel")) {
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
echo "abc";
 //$query = dbRead("select * from categories where CID='".$_SESSION['User']['CID']."' order by category");
 $query = dbRead("select categories.*, tbl_cat_providers.providerID from categories LEFT OUTER JOIN tbl_cat_providers on (categories.catid = tbl_cat_providers.providerID and tbl_cat_providers.catID = ".$_REQUEST['categoryid'].") where CID = 1 order by category");

 while($row = mysql_fetch_assoc($query)) {

  if($row['providerID'] != $_REQUEST[$row['catid']]) {

    if(!$row['providerID'] && $_REQUEST[$row['catid']]) {
      dbWrite("insert into tbl_cat_providers (catID,providerID) values ('".$_REQUEST['categoryid']."','".$row['catid']."')");
	} elseif(!$_REQUEST[$row['catid']] && $row['providerID']) {
      dbWrite("delete from tbl_cat_providers where catID = " . $_REQUEST['categoryid'] . " and providerID = " . $row['catid']);
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

<form name="categoryedit" method="POST" action="body.php?page=catproviders">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category
    <span lang="en-us">Provider Edit</span></tr>
  <tr>
    <td width="100%" align="center" class="Heading2" colspan="2"><font color="#FF0000"><?= $error ?></font></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><span lang="en-us">Category</span>:<td width="70%" bgcolor="#FFFFFF">
    <select size="1" name="category">
	<?
	 $query2 = dbRead("select * from categories where (CID = '".$_SESSION['User']['CID']."') or catid = 0 order by category");
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

</body>

</html>
<?
if($_REQUEST['catedit']) {

 #get category data.
 //$query3 = dbRead("select categories.*, tbl_cat_providers.providerID from categories LEFT OUTER JOIN tbl_cat_providers on (categories.catid = tbl_cat_providers.providerID and tbl_cat_providers.catID = ".$_REQUEST['category'].") where CID = 1 order by category");
 //$query3 = dbRead("select categories.*, tbl_cat_providers.providerID from categories inner join tbl_cat_link on tbl_cat_link.Sub_ID = categories.catid LEFT OUTER JOIN tbl_cat_providers on (categories.catid = tbl_cat_providers.providerID and tbl_cat_providers.catID = ".$_REQUEST['category'].") where CID = 1 and display_drop = 'Y' order by tbl_cat_link.Main_ID, category");
 $query3 = dbRead("select categories.*, tbl_cat_providers.providerID, tbl_cat_main.C_Name from categories inner join tbl_cat_link on tbl_cat_link.Sub_ID = categories.catid inner join tbl_cat_main on tbl_cat_link.Main_ID = tbl_cat_main.FieldID LEFT OUTER JOIN tbl_cat_providers on (categories.catid = tbl_cat_providers.providerID and tbl_cat_providers.catID = ".$_REQUEST['category'].") where categories.CID = 1 and display_drop = 'Y' order by tbl_cat_main.C_Name, category");
?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form name="categoryedit" method="POST" action="body.php?page=catproviders">
<input type="hidden" name="categoryid" value="<?= $_REQUEST['category'] ?>">
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
 <td class="Border">
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="3" class="Heading" align="center">Category Provider Edit
  </tr>

<?
 $counter = 1;
 while($catrow = mysql_fetch_assoc($query3)) {

 if($main != $catrow[C_Name]) {
?>
<tr BGCOLOR=#FFFFFF align="center">
<td colspan=3>
<b><?= $catrow[C_Name] ?></b>
</td>
</tr>

<?
   $counter = 1;
 }
  $main = $catrow[C_Name];

  if($counter == 1) {
?>
  <tr>
    <td width="30%" align="right" class="Heading2"><?= $catrow['category'] ?>
	<input type="checkbox" name="<?= $catrow['catid'] ?>" value="1" <? if($catrow['providerID'] > 0){?>checked<?}?>>
	</td>
<?
  } elseif($counter == 2) {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['category'] ?>
	<input type="checkbox" name="<?= $catrow['catid'] ?>" value="1" <? if($catrow['providerID'] > 0){?>checked<?}?>>
	</td>
<?
  } else {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['category'] ?>
	<input type="checkbox" name="<?= $catrow['catid'] ?>" value="1" <? if($catrow['providerID'] > 0){?>checked<?}?>>
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
    <td width="30%" class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
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