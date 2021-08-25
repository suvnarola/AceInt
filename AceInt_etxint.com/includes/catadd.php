<?

$yesno = array('Y' => 'Yes', 'N' => 'No');

if(!checkmodule("CatAdd")) {

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

if($_REQUEST['catadd'] && $_REQUEST['catname']) {

 #check to see if the category already exists
 $query = dbRead("select * from categories where category='".addslashes(encode_text2($_REQUEST['catname']))."' and CID='".$_SESSION['User']['CID']."'");
 if(@mysql_num_rows($query) > 0 || ($_SESSION['Country']['english'] == 'N' && !$_REQUEST['engcatname'])) {

   #category exists display error.
   if($_SESSION['Country']['english'] == 'N' && !$_REQUEST['engcatname'])  {
    $error = "Must have a English Category";
   } else {
    $error = "That Category Already Exists!!";
   }

 }

 if($_SESSION['Country']['countryID'] == 1 && !$_REQUEST['main']) {
    $error .= "A Main Group must be selected!!";
 }


 if(!$error) {

   #category doesn't exist. add it
   $cid = dbWrite("insert into categories (category,display_drop,cont,rest_supp,rest_acco,tourist,gene_busi,wed,CID) values ('".addslashes(encode_text2($_REQUEST['catname']))."','".addslashes(encode_text2($_REQUEST['display_drop']))."','".$_REQUEST['cont']."','".$_REQUEST['rest_supp']."','".$_REQUEST['rest_acco']."','".$_REQUEST['tourist']."','".$_REQUEST['gene_busi']."','".addslashes(encode_text2($_REQUEST['wed']))."','".$_SESSION['User']['CID']."')","etradebanc",true);

   if($_REQUEST['main']) {

    $mainArray = $_REQUEST['main'];

    foreach($mainArray as $key => $value) {
   	  dbWrite("insert into tbl_cat_link (Main_ID,Sub_ID) values ('".$key."','".$cid."')");
    }

   }

   if(checkmodule("Log")) {
    add_kpi("33", "0");
   }

 } else {

 ?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body onload="javascript:setFocus('categoryadd','catname');">

<form name="categoryadd" method="POST" action="body.php?page=catadd">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
 <td class="Border">
 <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category Add</td>
  </tr>
  <tr>
    <td width="100%" align="center" class="Heading2" colspan="2"><font color="#FF0000"><?= $error ?></font></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2">Category Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="catname" size="30" value="<?= $_REQUEST['catname'] ?>"></td>
  </tr>
  <?if($_SESSION['Country']['english'] == 'N') {?>
  <tr>
    <td width="30%" align="right" class="Heading2">EnglishCategory Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="engcatname" size="30"></tr>
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
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="wed" value="1" <? if($_REQUEST['web']){?>checked<?}?>></td>
  </tr>
<?
if($_SESSION['Country']['countryID'] == 1) {
#get category data.
 $query3 = dbRead("select tbl_cat_main.* from tbl_cat_main where tbl_cat_main.CID = ".$_SESSION['Country']['countryID']." order by C_Name");
?>

<tr>
<td colspan=2>
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="30%" colspan=3 align="center" class="Heading2">Main Group
	</td>
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
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF"><button name="catadd" type="submit">Add Category</button></td>
  </tr>
 </table>
 </td>
</tr>
</table>
<input type="hidden" name="catadd" value="1">
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

<body onload="javascript:setFocus('categoryadd','catname');">

<form name="categoryadd" method="POST" action="body.php?page=catadd">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
 <td class="Border">
 <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">Category Add</td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2">Category Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="catname" size="30"></tr>
  </tr>
  <?if($_SESSION['Country']['english'] == 'N') {?>
  <tr>
    <td width="30%" align="right" class="Heading2">EnglishCategory Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="engcatname" size="30"></tr>
  </tr>
  <?}?>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display in Dropdowns:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('display_drop',$yesno,'','',$row['display_drop']); ?></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Contractors:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="cont" value="1" <? if($UserRow['emlic']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Accomm/Rest Supplies:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="rest_supp" value="1" <? if($UserRow['emadm']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Rest/Accomm:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="rest_acco" value="1" <? if($UserRow['emcus']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Tourist:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="tourist" value="1" <? if($UserRow['emsal']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>General Business:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="gene_busi" value="1" <? if($UserRow['emsal']){?>checked<?}?>></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Wedding Services:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="checkbox" name="wed" value="1" <? if($UserRow['web']){?>checked<?}?>></td>
  </tr>

<?
if($_SESSION['Country']['countryID'] == 1) {
#get category data.
 $query3 = dbRead("select tbl_cat_main.* from tbl_cat_main where tbl_cat_main.CID = ".$_SESSION['Country']['countryID']." order by C_Name");
?>

<tr>
<td colspan=2>
  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="30%" colspan=3 align="center" class="Heading2">Main Group
	</td>
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
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF"><button name="catadd" type="submit">Add Category</button></td>
  </tr>
 </table>
 </td>
</tr>
</table>

<input type="hidden" name="catadd" value="1">

</form>

</body>

</html>