<?
if(!checkmodule("ClasPicture")) {

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

if($_FILES['picture']) {

 if(checkmodule("Log")) {
  add_kpi("44", "0");
 }

move_uploaded_file($_FILES['picture']['tmp_name'], "/home/etxint/public_html/clasimages/".$_FILES['picture']['name']);
$source = "/home/etxint/public_html/clasimages/".$_FILES['picture']['name'];
$dest = "/home/etxint/public_html/clasimages/thumb-".$_FILES['picture']['name'];
copy($source, $dest);
exec("convert -geometry 75 /home/etxint/public_html/clasimages/thumb-".$_FILES['picture']['name']." /home/etxint/public_html/clasimages/thumb-".$_FILES['picture']['name']."");

if($_REQUEST['clasid']) {
 dbWrite("update classifieds set image='".$_FILES['picture']['name']."' where id='".$_REQUEST['clasid']."'");
}

}

?>
<html>
<head>
<title>real upload</title>
</head>
<body onload="javascript:setFocus('CL','picture');">

<form ENCTYPE="multipart/form-data" method="POST" action="body.php?page=clas_picupload" name="CL">
<font face="Verdana" size="2" color="#000000">&nbsp;<?= get_page_data("1") ?>:<input size="25" type="file" name="picture" style="font-family: Verdana"></font><br>
<font face="Verdana" size="2" color="#000000">&nbsp;ID:<input type="text" name="clasid" size="25"></font><br>
<input type="submit" name="blah" value="<?= get_word("83") ?>">
</form>
</body>
</html>