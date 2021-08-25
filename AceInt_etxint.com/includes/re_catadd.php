<?

if(!checkmodule("RECatAdd")) {

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

if($_POST[recatadd]) {

#check to see if the category already exists
$query = dbRead("select * from recategories where recategory='$_POST[catname]' and CID='".$_SESSION['User']['CID']."'");
if(@mysql_num_rows($query) == 0) {
 #category doesn't exist. add it
 dbWrite("insert into recategories (recategory,CID) values ('".encode_text2($_POST['recatname'])."','".$_SESSION['User']['CID']."')");
 
 if(checkmodule("Log")) {
  add_kpi("38", "0");
 }

} else {
 #category exists display error.
 ?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body onload="javascript:setFocus('recategoryadd','recatname');">

<form name="recategoryadd" method="POST" action="body.php?page=recatadd">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center"><?= get_page_data("1") ?></td>
  </tr>
  <tr>
    <td width="100%" align="center" class="Heading2" colspan="2"><font color="#FF0000"><?= get_page_data("2") ?></font></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><?= get_word("26") ?>:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="recatname" size="30" value="<?= $_POST[recatname] ?>"></td>
  </tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF">
    <button name="recatadd" type="submit"><?= get_word("83") ?></button></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="recatadd" value="1">
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

<body onload="javascript:setFocus('recategoryadd','recatname');">

<form name="recategoryadd" method="POST" action="body.php?page=recatadd">

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center"><?= get_page_data("1") ?></tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><?= get_word("26") ?>:<td width="70%" bgcolor="#FFFFFF">
    <input type="text" name="recatname" size="30"></tr>
  </tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;<td width="70%" bgcolor="FFFFFF">
    <button name="recatadd" type="submit"><?= get_word("83") ?></button></tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="recatadd" value="1">

</form>

</body>

</html>