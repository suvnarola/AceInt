<?

if(!checkmodule("REPicture")) {

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
  add_kpi("40", "0");
 }

 if($_REQUEST['agent'])  {
   $agentid = $_REQUEST['agent'];
 } else {
   $agentid = $_SESSION['User']['AgentID'];
 }

$picture_name=$_REQUEST[realid];

if(is_file("/home/etxint/public_html/realimages/".$agentid.".$_REQUEST[realid].jpg")) {
 //check to see how many files there are with this name.
 $start = true;
 $filecount=0;
 while($start == true) {

  $filecounttemp=$filecount+1;

  if(is_file("/home/etxint/public_html/realimages/$agentid.$_REQUEST[realid]-$filecounttemp.jpg")) {
   $filecount+=1;
  } else {
   $start = false;
  }

 }

 $nextpic=$filecounttemp;
 $picture_name_new=$picture_name . "-$nextpic";

} else {

 $picture_name_new=$picture_name;

}

move_uploaded_file($_FILES['picture']['tmp_name'], "/home/etxint/public_html/realimages/$agentid.$picture_name_new.jpg");

$source="/home/etxint/public_html/realimages/".$agentid.".".$picture_name_new.".jpg";
$dest="/home/etxint/public_html/realimages/thumb-".$agentid.".".$picture_name_new.".jpg";
copy($source, $dest);
exec('convert -geometry 75 /home/etxint/public_html/realimages/thumb-' .$agentid. '.' . $picture_name_new . '.jpg /home/etxint/public_html/realimages/thumb-' . $agentid . '.' . $picture_name_new . '.jpg');

	if($default) {

		$updatereal="update realestate set image='$agentid.$picture_name_new.jpg' where id='".$_REQUEST['realid']."'";
		$updaterealim="insert into realimages values ('','".$_REQUEST['realid']."','$agentid','$agentid.$picture_name_new.jpg')";
		dbWrite($updatereal);
		dbWrite($updaterealim);

	} else {

		$updaterealim="insert into realimages values ('','".$_REQUEST['realid']."','$agentid','$agentid.$picture_name_new.jpg')";
		dbWrite($updaterealim);

	}



}

$yesno = array('1' => 'Outlook', '15' => 'Other');
?>
<html>
<head>
<title>real upload</title>
</head>
<body onload="javascript:setFocus('UpReal','picture');">

<form ENCTYPE="multipart/form-data" method="POST" action="body.php?page=re_upload" name="UpReal">
<font face="Verdana" size="2" color="#000000">&nbsp;Picture:<input size="25" type="file" name="picture" style="font-family: Verdana"></font><br>
<font face="Verdana" size="2" color="#000000">&nbsp;Real ID:<input type="text" name="realid" size="25" onKeyPress="return number(event)"></font><br>
<?if($_SESSION['User']['Area'] == 1)  {?>
<font face="Verdana" size="2" color="#000000"><b>Display: </b><?= form_select('agent',$yesno,'','',$row['Display']); ?></font><br>
<?}?>
<font face="Verdana" size="2" color="#000000">&nbsp;Default Image:<input type="checkbox" name="default" value="default"></font><br>
<input type="submit" name="blah" value="Upload File">
</form>
</body>
</html>