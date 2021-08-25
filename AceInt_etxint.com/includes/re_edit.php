<?

if(!checkmodule("REEdit")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
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

if($_REQUEST['reid']) {

#getstuffout
$dbgetdet=mysql_db_query($db, "select * from realestate where id='".$_REQUEST['reid']."'", $linkid);
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
     <td bgcolor="#FFFFFF"><input size="30" type="text" name="contactname" value="<?= get_all_added_characters($REDetails[contactname]) ?>"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
     <td bgcolor="#FFFFFF"><select name="catid5">
<?

$dbgetclascat=mysql_db_query($db, "select recatid as id8, recategory from recategories where recategory != '' order by recategory ASC", $linkid);
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
     <td bgcolor="#FFFFFF"><input size="20" type="text" name="phone" value="<?= get_all_added_characters($REDetails[phone]) ?>"></td>
	</tr>
<?

if($MemberDetails[langcode] != "nl") {

?>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("15") ?>:</b></font></td>
     <td bgcolor="#FFFFFF"><input size="25" type="text" name="suburb" value="<?= get_all_added_characters($REDetails[suburb]) ?>"></td>
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
     <td bgcolor="#FFFFFF"><input size="5" type="text" name="postcode" value="<?= get_all_added_characters($REDetails[postcode]) ?>" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
     <td width="150" align="right" class="Heading2"><b><?= get_word("9") ?>:</b></td>
     <td bgcolor="#FFFFFF"><input size="35" type="text" name="emailaddress" value="<?= get_all_added_characters($REDetails[emailaddress]) ?>"></td>
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
     <td bgcolor="#FFFFFF"><textarea name="detaildesc" cols="50" rows="4"><?= get_all_added_characters($REDetails[shortdesc]) ?></textarea></td>
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
$dbgetmemcats=mysql_db_query($db, "select id, price, pricetrade, contactname from realestate ".$aa." order by id DESC", $linkid);
while($row = @mysql_fetch_array($dbgetmemcats)) {


?>
 <tr>
  <td align="left" width="10%" bgcolor="#FFFFFF"><a class="nav" href="<?= $PHP_SELF ?>?page=re_edit&reid=<?= $row[id] ?>"><?= $row[id] ?></a></td>
  <td align="left" width="50%" bgcolor="#FFFFFF"><a class="nav" href="<?= $PHP_SELF ?>?page=re_edit&reid=<?= $row[id] ?>"><?= get_all_added_characters($row[contactname]) ?></a></td>
  <td align="right" width="20%" bgcolor="#FFFFFF"><?= $row[price] ?></td>
  <td align="right" width="20%" bgcolor="#FFFFFF"><?= get_all_added_characters($row[pricetrade]) ?></td>
 </tr>
<? } ?>
</table>
</td>
</tr>
</table>
<? } ?>