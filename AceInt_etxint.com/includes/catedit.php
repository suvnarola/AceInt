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


if($_REQUEST['catedit']) {

 #get category data.
 $query3 = dbRead("select * from categories where catid='".$_REQUEST['category']."'");
 $row4 = mysql_fetch_assoc($query3);

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form name="categoryedit" method="POST" action="body.php?page=catedit">
<input type="hidden" name="categoryid" value="<?= $_REQUEST['category'] ?>">
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
 <td class="Border">
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category Edit</tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><span lang="en-us">Old Category</span>:</td>
    <td width="70%" bgcolor="#FFFFFF">&nbsp;<?= $row4['category'] ?></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><span lang="en-us">New Category</span>:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="categoryname" size="30" value="<?= $row4['category'] ?>"></td>
  </tr>
  <?if($_SESSION['Country']['english'] == 'N') {?>
  <tr>
    <td width="30%" align="right" class="Heading2">EnglishCategory Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="engcatname" size="30" value="<?= $row4['engcategory'] ?>"></tr>
  </tr>
  <?}?>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display in Dropdowns:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('display_drop',$yesno,'','',$row4['display_drop']); ?></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Contractors:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="cont" value="1" <? if($row4['cont']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Accomm/Rest Supplies:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="rest_supp" value="1" <? if($row4['rest_supp']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Rest/Accomm:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="rest_acco" value="1" <? if($row4['rest_acco']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Tourist:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="tourist" value="1" <? if($row4['tourist']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>General Business:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="gene_busi" value="1" <? if($row4['gene_busi']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Wedding Services:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="wed" value="1" <? if($row4['wed']){?>checked<?}?>></td>
  </tr>

<?
if($_SESSION['Country']['countryID'] == 1){
 #get category data.
 $query3 = dbRead("select tbl_cat_main.*, tbl_cat_link.Sub_ID from tbl_cat_main LEFT OUTER JOIN tbl_cat_link on (tbl_cat_main.FieldID = tbl_cat_link.Main_ID and tbl_cat_link.Sub_ID = ".$_REQUEST['category'].") where CID = 1 order by C_Name");

?>
<tr>
<td colspan=2>
<table border="0" cellpadding="1" cellspacing="0" width="100%">
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
	<input type="checkbox" name="main[<?= $catrow['FieldID'] ?>]" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
<?
  } elseif($counter == 2) {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="main[<?= $catrow['FieldID'] ?>]" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
<?
  } else {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="main[<?= $catrow['FieldID'] ?>]" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
   </tr>
<?
   $counter = 0;
  }
  $counter++;
 }

?>
</table>
</td>
</tr>
<?}?>
  <tr>
    <input type="hidden" name="catedit2" value="1">
    <td width="30%" class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
    <input type="submit" value="Delete" name="deleteCat">
    <button name="sub2" type="submit">Edit Category</button></td>
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

if($_REQUEST['catedit2'] && $_REQUEST['categoryname']) {

 if($_REQUEST['deleteCat']) {
  $queryc = dbRead("select * from mem_categories where category = ".$_REQUEST['categoryid']." ");
  $rowc = mysql_fetch_assoc($queryc);
  if($rowc) {
   $error .= "You can not delete a category that have members listed";
  } else {
    dbWrite("delete from categories where catid = " . $_REQUEST['categoryid']);
	echo "Category Deleted";
  }
 }

 if(($_SESSION['Country']['english'] == 'N' && !$_REQUEST['engcatname'])) {
  $error .= "There must been a English Category Name";
 }

 if($_SESSION['Country']['countryID'] == 1 && !$_REQUEST['main'] && $_REQUEST['display_drop'] == "Y") {
    $error .= "A Main Group must be selected!!";
 }

 if(!$error) {
  #update category table.
  dbRead("update categories set category='".addslashes(encode_text2($_REQUEST['categoryname']))."', engcategory='".addslashes(encode_text2($_REQUEST['engcatname']))."', display_drop='".addslashes(encode_text2($_REQUEST['display_drop']))."', cont='".addslashes(encode_text2($_REQUEST['cont']))."', rest_supp='".addslashes(encode_text2($_REQUEST['rest_supp']))."', rest_acco='".addslashes(encode_text2($_REQUEST['rest_acco']))."', tourist='".addslashes(encode_text2($_REQUEST['tourist']))."', gene_busi='".addslashes(encode_text2($_REQUEST['gene_busi']))."', wed='".addslashes(encode_text2($_REQUEST['wed']))."' where CID='".$_SESSION['User']['CID']."' and catid='".$_REQUEST['categoryid']."'");

 $query = dbRead("select tbl_cat_main.*, tbl_cat_link.Sub_ID from tbl_cat_main LEFT OUTER JOIN tbl_cat_link on (tbl_cat_main.FieldID = tbl_cat_link.Main_ID and tbl_cat_link.Sub_ID = ".$_REQUEST['categoryid'].") where CID = 1 order by C_Name");

 while($row = mysql_fetch_assoc($query)) {

  $id = $row['FieldID'];
  if($row['FieldID'] != $_REQUEST[main][$id]) {

    if(!$row['Sub_ID'] && $_REQUEST[main][$id]) {
      dbWrite("insert into tbl_cat_link (Main_ID,Sub_ID) values ('".$row['FieldID']."','".$_REQUEST['categoryid']."')");
	} elseif(!$_REQUEST[main][$id] && $row['Sub_ID']) {
      dbWrite("delete from tbl_cat_link where Main_ID = " . $row['FieldID'] . " and Sub_ID = " . $_REQUEST['categoryid']);
	}

  }

 }

  if(checkmodule("Log")) {
   add_kpi("34", "0");
  }
  echo "Category Updated";
  //die;

 } else {

 //}
?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form name="categoryedit" method="POST" action="body.php?page=catedit">
<input type="hidden" name="categoryid" value="<?= $_REQUEST['categoryid'] ?>">
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
 <td class="Border">
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category Edit</tr>
  <tr>
    <td width="100%" align="center" class="Heading2" colspan="2"><font color="#FF0000"><?= $error ?></font></td>
  </tr
  <tr>
    <td width="30%" align="right" class="Heading2"><span lang="en-us">Old Category</span>:</td>
    <td width="70%" bgcolor="#FFFFFF">&nbsp;<?= $_REQUEST['categoryname'] ?></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><span lang="en-us">New Category</span>:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="categoryname" size="30" value="<?= $_REQUEST['categoryname'] ?>"></td>
  </tr>
  <?if($_SESSION['Country']['english'] == 'N') {?>
  <tr>
    <td width="30%" align="right" class="Heading2">EnglishCategory Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="engcatname" size="30" value="<?= $_REQUEST['engcatname'] ?>"></tr>
  </tr>
  <?}?>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display in Dropdowns:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('display_drop',$yesno,'','',$_REQUEST['display_drop']); ?></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Contractors:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="cont" value="1" <? if($_REQUEST['cont']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Accomm/Rest Supplies:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="rest_supp" value="1" <? if($_REQUEST['rest_supp']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Rest/Accomm:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="rest_acco" value="1" <? if($_REQUEST['rest_acco']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Tourist:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="tourist" value="1" <? if($_REQUEST['tourist']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>General Business:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="gene_busi" value="1" <? if($_REQUEST['gene_busi']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Wedding Services:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="wed" value="1" <? if($_REQUEST['wed']){?>checked<?}?>></td>
  </tr>
<?
if($_SESSION['Country']['countryID'] == 1){
 #get category data.
 $query3 = dbRead("select tbl_cat_main.*, tbl_cat_link.Sub_ID from tbl_cat_main LEFT OUTER JOIN tbl_cat_link on (tbl_cat_main.FieldID = tbl_cat_link.Main_ID and tbl_cat_link.Sub_ID = ".$_REQUEST['categoryid'].") where CID = 1 order by C_Name");

?>
<tr>
<td colspan=2>
<table border="0" cellpadding="1" cellspacing="0" width="100%">
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
	<input type="checkbox" name="main[<?= $catrow['FieldID'] ?>]" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
<?
  } elseif($counter == 2) {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="main[<?= $catrow['FieldID'] ?>]" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
<?
  } else {
?>
    <td width="30%" align="right" class="Heading2"><?= $catrow['C_Name'] ?>
	<input type="checkbox" name="main[<?= $catrow['FieldID'] ?>]" value="1" <? if($catrow['Sub_ID'] > 0){?>checked<?}?>>
	</td>
   </tr>
<?
   $counter = 0;
  }
  $counter++;
 }

?>
</table>
</td>
</tr>
<?}?>
  <tr>
    <input type="hidden" name="catedit2" value="1">
    <td width="30%" class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
    <button name="sub2" type="submit">Edit Category</button></td>
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

<form name="categoryedit" method="POST" action="body.php?page=catedit">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category
    <span lang="en-us">Edit</span></tr>
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
	 <option value="<?= $row2['catid'] ?>"><?= $row2['category'] ?></option>
	 <?
	 }
	?>
    </select></tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
    <input type="hidden" name="catedit" value="1">
	<button name="sub" type="submit">
    <span lang="en-us">Edit Category</span>
    </button></tr>
</table>
</td>
</tr>
</table>

</form>

</body>

</html>