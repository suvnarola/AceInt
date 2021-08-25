<html>
<head>
<title>Real Rstate</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
</head>
<body>
<?
if(!checkmodule("REAdd")) {
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

?>

<form method="POST" action="body.php?page=re&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" ENCTYPE="multipart/form-data" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();
$tabarray = array('Add','Edit','Picture Upload');
if(checkmodule("RECatAdd")) { $tabarray[] = "Cat Add"; }
if(checkmodule("RECatAdd")) { $tabarray[] = "Agent Add"; }

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  addRE();

} elseif($_GET[tab] == "tab2") {

  editRE();

} elseif($_GET[tab] == "tab3") {

  pUpload();

} elseif($_GET[tab] == "tab4") {

  aCat();

} elseif($_GET[tab] == "tab5") {

  aAdd();

}
?>

</form>
<?

function addRE() {
if($ff) {
?>
<body onload="javascript:setFocus('add','contactname');">
<form ENCTYPE="multipart/form-data" name="add" method="post" action="/general.php?re=true" >
<?}


if($_REQUEST[addretodb]) {

  if(checkmodule("Log")) {
   add_kpi("36", "0");
  }

  $date=date("Y-m-d",mktime());
  $totalprice=$_REQUEST[totalprice];

  $desc = addslashes($_REQUEST[detaildesc]);

  $sendid = dbWrite("insert into realestate (agent,date,contactname,emailaddress,phone,area,price,pricetrade,shortdesc,totalprice,checked,CID,category,suburb,postcode,under) values ('".$_REQUEST[agent]."','$date','".encode_text2($_REQUEST[contactname])."','$_REQUEST[emailaddress]','".$_REQUEST[phone]."','".encode_text2($_REQUEST[area])."','".$_REQUEST[price]."','".$_REQUEST[pricetrade]."','$desc','".$_REQUEST[totalprice]."','1','".$_SESSION['User']['CID']."','".$_REQUEST[category]."','".encode_text2($_REQUEST[suburb])."','".encode_text2($_REQUEST[postcode])."','".$_REQUEST[under]."')",'etradebanc','1');

  details($sendid);

  die;
}
?>
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td class="Heading" colspan="2" align="center"><b>&nbsp;<?= get_page_data("1") ?>.</b></td>
	</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("128") ?>:</b></td>
					<td bgcolor="#FFFFFF"><select name="agent">
                    <?
					 if($_SESSION['User']['Area'] == 1) {
					  $aa = " Display = 'Y' and CID = ".$_SESSION['User']['CID']." order by name ";
					 } else {
					  $aa = " agentid = ".$_SESSION['User']['AgentID']."";
					 }
					 $query = dbRead("select * from agents where ".$aa."");
					while($row = mysql_fetch_assoc($query)) {

					?>
					<option value="<?= $row['agentid'] ?>"><?= $row['name'] ?></option>
					<?

					}

					?>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("5") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="30" type="text" name="contactname"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <select name="category">
                    <?

					$dbgetrecat = dbRead("select recatid, recategory from recategories where recategory != ' ' order by recategory");
					while($row = mysql_fetch_assoc($dbgetrecat)) {

					?>
					<option value="<?= $row['recatid'] ?>"><?= $row['recategory'] ?></option>
					<?

					}

					?>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("121") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="price"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("122") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="pricetrade"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("123") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="totalprice"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("129") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="address"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("7") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="phone"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("15") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="suburb"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("18") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="postcode"></td>
				</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("78") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="area">
                        <?
					    $dbgetareas = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName asc");
						while($row = mysql_fetch_array($dbgetareas)) {
   					    ?>
						 <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
						<?
						}
						?>
						</select></td>
					</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("9") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="emailaddress"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b>Status:</b></td>
					<td bgcolor="#FFFFFF"><select name="under">
  					<option value="0">For Sale</option>
  					<option value="1">Under Contract</option>
  					<option value="2">Sold</option>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("27") ?>:</b></td>
					<td bgcolor="#FFFFFF"><textarea rows="4" size="50"  name="detaildesc" cols = "50"></textarea></td>
          		</tr>
				<tr>
					<td width="150" valign="top" align="right" class="Heading2"></td>
					<td align="left" bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addretodb"></td>
				</tr>
			</table>

</td>
</tr>
</table>
<input type="hidden" name="addretodb" value="1">
</form>


<?
}

function editRE() {

if($_REQUEST['reno']) {

 if($_REQUEST['changereclas']) {

  if(checkmodule("Log")) {
   add_kpi("37", $_REQUEST['memid']);
  }

  dbWrite("update realestate set contactname='".encode_text2(addslashes($_REQUEST['contactname']))."', emailaddress='".addslashes($_REQUEST['emailaddress'])."', area='".addslashes($_REQUEST['area'])."', price='".addslashes($_REQUEST['price'])."', category='".addslashes($_REQUEST['catid5'])."', pricetrade='".addslashes($_REQUEST['tradeprice'])."', shortdesc='".encode_text2(addslashes($_REQUEST['detaildesc']))."', suburb='".encode_text2(addslashes($_REQUEST['suburb']))."', postcode='".addslashes($_REQUEST['postcode'])."', phone='".addslashes($_REQUEST['phone'])."', totalprice='".addslashes($_REQUEST['totalprice'])."', under='".addslashes($_REQUEST['under'])."' where id='".addslashes($_REQUEST['reno'])."' ");

  details($_REQUEST['reno']);

  die;

  } elseif($_REQUEST['deletereclas']) {

  dbWrite("delete from realestate where id = ".addslashes($_REQUEST['reno'])."");

  }

}

if($_REQUEST['reid']) {

#getstuffout
//$dbgetdet=mysql_db_query($db, "select * from realestate where id='".$_REQUEST['reid']."'", $linkid);
$dbgetdet=dbRead("select * from realestate where id='".$_REQUEST['reid']."'");

if(@mysql_num_rows($dbgetdet) == 0) {
	print"Thats not your Real Estate Listing! Norti Norti :)";
} else {

$REDetails=mysql_fetch_array($dbgetdet);
if($ClasDetails[type] == 1) {
	$distype="Buy";
} else {
	$distype="Sell";
}

?>
<html>
<body onload="javascript:setFocus('reedit','contactname');">
<table border="0" cellpadding="0" cellspacing="0" width="620">
<tr>
<td class="Border">
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="1" align="center">
 <tr>
  <td>
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td class="Heading" colspan="2" align="center"><b><?= get_page_data("1") ?></b></td>
    </tr>
    <tr>
	<form method="post" action="/general.php" name="reedit">
	<input type="hidden" name="reno" value="<?= $REDetails[id] ?>">

     <td width="150" align="right" class="Heading2"><b><?= get_word("5") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="30" type="text" name="contactname" value="<?= $REDetails[contactname] ?>"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
     <td bgcolor="#FFFFFF"><select name="catid5">
<?

//$dbgetclascat=mysql_db_query($db, "select recatid as id8, recategory from recategories where recategory != '' order by recategory ASC", $linkid);
$dbgetclascat=dbRead("select recatid as id8, recategory from recategories where recategory != '' order by recategory ASC");

while($row = mysql_fetch_array($dbgetclascat)) {

	?>
	<option <? if($REDetails[category] == $row[id8]) { echo "selected "; } ?>value="<?= $row[id8] ?>"><?= $row[recategory] ?></option>
	<?

}

?>
     </select></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("121") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="10" type="text" name="price" value="<?= $REDetails[price] ?>"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("122") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="10" type="text" name="tradeprice" value="<?= $REDetails[pricetrade] ?>"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("123") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="10" type="text" name="totalprice" value="<?= $REDetails[totalprice] ?>"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("7") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="20" type="text" name="phone" value="<?= $REDetails[phone] ?>"></td>
	</tr>
<?

if($MemberDetails[langcode] != "nl") {

?>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("15") ?>:</b></font></td>
     <td bgcolor="#FFFFFF"><input size="25" type="text" name="suburb" value="<?= $REDetails[suburb] ?>"></td>
	</tr>
<?

}

?>
					<tr>
						<td align="right" class="Heading2"><?= get_word("78") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="area">
                        <?
					    $dbgetareas = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName asc");
						while($row = mysql_fetch_array($dbgetareas)) {
   					    ?>
						 <option value="<?= $row[FieldID] ?>" <? if($REDetails['area'] == $row['FieldID']) { echo " selected"; } ?>><?= $row[RegionalName] ?></option>
						<?
						}
						?>
						</select></td>
					</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("18") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="5" type="text" name="postcode" value="<?= $REDetails[postcode] ?>" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("9") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="35" type="text" name="emailaddress" value="<?= $REDetails[emailaddress] ?>"></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Status:</b></td>
		<td bgcolor="#FFFFFF"><select name="under">
		<option value="0" <? if($REDetails[under] == 0) { echo " selected"; } ?>>For Sale</option>
		<option value="1" <? if($REDetails[under] == 1) { echo " selected"; } ?>>Under Contract</option>
		<option value="2" <? if($REDetails[under] == 2) { echo " selected"; } ?>>Sold</option>
		</select></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("27") ?>:</b></td>
     <td bgcolor="#FFFFFF"><textarea name="detaildesc" cols="50" rows="4"><?= $REDetails[shortdesc] ?></textarea></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"></td>
     <td align="left" bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="changereclas">&nbsp;<input type="submit" value="<?= get_word("125") ?>" name="deletereclas"></td>
	</tr>

	<input type="hidden" name="reno" value="<?= $REDetails[id] ?>">
   </table>
   </form>
  </tr>
 </td>
</table>
</tr>
</td>
</table>
<?

 }

} else {

?>
<table border="0" cellspacing="1" cellpadding="0" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="1">
 <tr>
  <td colspan="4" align="center" class="Heading"><?= get_page_data("1") ?></td>
 </tr>
 <tr>
  <td align="left" width="10%" class="Heading2"><b>ID:</b></td>
  <td align="left" width="50%" class="Heading2"><b><?= get_word("119") ?>:</b></td>
  <td align="right" width="20%" class="Heading2"><b><?= get_word("121") ?>:</b></td>
  <td align="right" width="20%" class="Heading2"><b><?= get_word("122") ?>:</b></td>
 </tr>
<?
if($_SESSION['User']['Area'] == 1) {
  $aa = " where CID = ".$_SESSION['User']['CID']." ";
} else {
  $aa = " where agent= ".$_SESSION['User']['AgentID']." ";
}

//$dbgetmemcats=mysql_db_query($db, "select id, price, pricetrade, contactname from realestate ".$aa." order by id DESC", $linkid);
$dbgetmemcats= dbRead("select id, price, pricetrade, contactname from realestate ".$aa." order by id DESC");

while($row = @mysql_fetch_array($dbgetmemcats)) {


?>
 <tr>
  <td align="left" width="10%" bgcolor="#FFFFFF"><a class="nav" href="<?= $PHP_SELF ?>?page=re&tab=tab2&reid=<?= $row[id] ?>"><?= $row[id] ?></a></td>
  <td align="left" width="50%" bgcolor="#FFFFFF"><a class="nav" href="<?= $PHP_SELF ?>?page=re&tab=tab2&reid=<?= $row[id] ?>"><?= $row[contactname] ?></a></td>
  <td align="right" width="20%" bgcolor="#FFFFFF"><?= $row[price] ?></td>
  <td align="right" width="20%" bgcolor="#FFFFFF"><?= $row[pricetrade] ?></td>
 </tr>
<? } ?>
</table>
</td>
</tr>
</table>
<? }

}

function pUpload() {

//if($_FILES['picture']) {
if($_FILES['picture']['tmp_name']) {

 if(checkmodule("Log")) {
  add_kpi("40", "0");
 }

$query = dbRead("select * from realestate where id='".$_REQUEST[realid]."'");
$row = mysql_fetch_assoc($query);
$agentid = $row['agent'];
 //if($_REQUEST['agent'])  {
   //$agentid = $_REQUEST['agent'];
 //} else {
   //$agentid = $_SESSION['User']['AgentID'];
 //}

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




  $maxwidth = "550";
  $imagehw = GetImageSize("/home/etxint/public_html/realimages/".$agentid.".".$picture_name_new.".jpg");
  $imagewidth = $imagehw[0];
  $imageheight = $imagehw[1];
  $imgorig = $imagewidth;

  if ($imagewidth > $maxwidth) {
    $imageprop=($maxwidth/$imagewidth);
    $imagevsize= ($imageheight*$imageprop);
    //$imageprop=($maxwidth*100)/$imagewidth;
    //$imagevsize= ($imageheight*$imageprop)/100 ;
    $imagewidth=$maxwidth;
    $imageheight=ceil($imagevsize);
    exec("convert -geometry ".$imagewidth."x".$imageheight." /home/etxint/public_html/realimages/".$agentid.".".$picture_name_new.".jpg /home/etxint/public_html/realimages/".$agentid.".".$picture_name_new.".jpg");

  }





	if($_REQUEST['default']) {

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

<font face="Verdana" size="2" color="#000000">&nbsp;Picture:<input size="25" type="file" name="picture" style="font-family: Verdana"></font><br>
<font face="Verdana" size="2" color="#000000">&nbsp;Real ID:<input type="text" name="realid" size="25" onKeyPress="return number(event)"></font><br>
<?if($_SESSION['User']['Area'] == 1111111)  {?>
<font face="Verdana" size="2" color="#000000"><b>Display: </b><?= form_select('agent',$yesno,'','',$row['Display']); ?></font><br>
<?}?>
<font face="Verdana" size="2" color="#000000">&nbsp;Default Image:<input type="checkbox" name="default" value="default"></font><br>
<input type="submit" name="blah" value="Upload File">


<?
}

function aCat() {

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
<?
}

function aAdd() {

if($_REQUEST[addagtodb]) {

  dbWrite("insert into agent (name,contact,lic,address,phone,fax,CID) values ('".encode_text2($_REQUEST[name])."','".encode_text2($_REQUEST[lic])."','".encode_text2($_REQUEST[address])."','".$_REQUEST[phone]."','".$_REQUEST[fax]."','".$_SESSION['User']['CID']."')");

  die;
}
?>
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td class="Heading" colspan="2" align="center"><b>&nbsp;<?= get_page_data("1") ?>.</b></td>
	</tr>
		<tr>
			<td width="150" align="right" class="Heading2"><b>Agent Name:</b></td>
			<td bgcolor="#FFFFFF">
            <input size="25" type="text" name="name"></td>
		</tr>
		<tr>
			<td width="150" align="right" class="Heading2"><b>Contact:</b></td>
			<td bgcolor="#FFFFFF">
            <input size="25" type="text" name="contact"></td>
		</tr>
		<tr>
			<td width="150" align="right" class="Heading2"><b>Licensee No:</b></td>
			<td bgcolor="#FFFFFF">
            <input size="25" type="text" name="lic"></td>
		</tr>
		<tr>
			<td width="150" align="right" class="Heading2"><b>Address:</b></td>
			<td bgcolor="#FFFFFF">
            <input size="25" type="text" name="address"></td>
		</tr>
		<tr>
			<td width="150" align="right" class="Heading2"><b>Phone:</b></td>
			<td bgcolor="#FFFFFF">
            <input size="25" type="text" name="phone"></td>
		</tr>
		<tr>
			<td width="150" align="right" class="Heading2"><b>Fax:</b></td>
			<td bgcolor="#FFFFFF">
            <input size="25" type="text" name="fax"></td>
		</tr>
		</tr>
			<tr>
			<td width="150" valign="top" align="right" class="Heading2"></td>
			<td align="left" bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addagtodb"></td>
		</tr>
</table>
</td>
</tr>
</table>
<?
}

function details($id) {


$dbgetdetails = dbRead("select * from realestate, tbl_area_regional where (realestate.area = tbl_area_regional.FieldID) and id='$id' and checked='1'");
$row = mysql_fetch_array($dbgetdetails);

$price2 = number_format($row[price],2);
$tradeprice2 = number_format($row[tradeprice],2);
$totalprice = $row[price]+$row[tradeprice];
$totalprice2 = number_format($totalprice,2);

$array1 = explode(" ", $row[date]);
$array2 = explode("-", $array1[0]);
$month = $array2[1];
$day = $array2[2];
$year = $array2[0];

$newdate = date("l jS F, Y", mktime(0,0,1,$month,$day,$year));

?>
<html>
<head>
<title>Real Estate Detail - ID: <?= $_GET[id] ?></title>
</head>
<body>
<table width="620" cellpadding="3" cellspacing="0" border="0">
 <tr>
  <td align="center"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a></td>
 </tr>
</table>
<table cellpadding="1" border="0" cellspacing="0" width="620">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td class="Heading"><b><?= $row[contactname] ?></b></td>
    </tr>
   </table>
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("41") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $newdate ?></td>
	 <td width="248" rowspan="12" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;
		<?

		$getimages="select imagename from realimages where id='$_GET[id]' and agent_id='$_GET[agent]'";
		$dbgetimages = dbRead($getimages);
		while(list($imagename)=mysql_fetch_row($dbgetimages)) {

		print'<img src="http://www.ebanctrade.com/realimages/thumb-'.$imagename.'" border="0">&nbsp;';

		}

		?>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("120") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[contactname] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("7") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[phone] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("9") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><a href="mailto:<?= $row[emailaddress] ?>" class="nav"><b><?= $row[emailaddress] ?></b></a></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("78") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[RegionalName] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("15") ?>, <?= get_word("18") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[suburb] ?>, <?= $row[postcode] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("121") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[price] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("122") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[pricetrade] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("123") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[totalprice] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" valign="top" class="Heading2" height="1"><?= get_word("27") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[shortdesc] ?></td>
    </tr>
	<tr>
	 <td width="124" align="right" valign="top" class="Heading2" height="<?= $cellh ?>">&nbsp;</td>
	 <td width="248" align="left" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<?
}
?>


