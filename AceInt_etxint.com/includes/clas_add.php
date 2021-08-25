<HTML>
<HEAD>
<TITLE></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<script language="javascript" src="js/sleight.js"></script>
</HEAD>

<?

$CDataSQL = dbRead("select * from countrydata where CID = '".$Country['countryID']."'");
$CDataRow = mysql_fetch_assoc($CDataSQL);

if($_REQUEST['Accept'])  {
$query = dbRead("select * from members left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3) where memid='$_REQUEST[memid]'");
$row = mysql_fetch_assoc($query);

if($_REQUEST['error'] ==1) {
 echo "A Email Address must be Provided";
} elseif($_REQUEST['error'] ==2) {
 echo "The Trade Amount has to be at least 30% of the total Price";
}
?>
<body onload="javascript:setFocus('CL','productname');">
<table border="0" cellspacing="0" cellpadding="0" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<form ENCTYPE="multipart/form-data" method="POST" action="/general.php" name="CL">
				<tr width="100%">
					<td colspan="2" class="Heading" align="center"><?= get_word("181") ?>.</td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("119") ?>:</b></td>
					<td bgcolor="#FFFFFF"><input size="30" type="text" name="productname" value="<?= get_all_added_characters($_REQUEST[productname]) ?>"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
					<td bgcolor="#FFFFFF"><select name="category">
					<?

					$dbgetclascat = dbRead("select catid, category from categories where CID='".$_SESSION['User']['CID']."' order by category ASC");
					while($row3 = mysql_fetch_assoc($dbgetclascat)) {
					?>
					<option value="<?= $row3['catid'] ?>"><?= $row3['category'] ?></option>
					<?
					}
					?>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("121") ?>:</b></td>
					<td bgcolor="#FFFFFF"><input size="10" type="text" name="price" value="<?= $_REQUEST[price] ?>" onKeyPress="return number(event)"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("122") ?>:</b></td>
					<td bgcolor="#FFFFFF"><input size="10" type="text" name="tradeprice" value="<?= $_REQUEST[tradeprice] ?>" onKeyPress="return number(event)"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("86") ?>:</b></td>
					<td bgcolor="#FFFFFF"><select name="type">
					<option selected value="2">For Sale</option>
					<option value="1">Wanted To Buy</option>
					</select>
					</td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("120") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="name" <? if($_REQUEST[name]) {?>value="<?= $_REQUEST[name] ?>"<?} else {?>value="<?= get_all_added_characters($row['companyname']) ?>"<?}?>></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("7") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="phoneno" <? if($_REQUEST[phoneno]) {?>value="<?= $_REQUEST[phoneno] ?>"<?} else {?>value="<?= get_all_added_characters($row['phonearea']) ?> <?= get_all_added_characters($row['phoneno']) ?>"<?}?>></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("15") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="suburb" <? if($_REQUEST[suburb]) {?>value="<?= $_REQUEST[suburb] ?>"<?} else {?>value="<?= get_all_added_characters($row['city']) ?>"<?}?>></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("78") ?>:</b></td>
					<td bgcolor="#FFFFFF"><select name="areaid">
					<?if($_REQUEST[area]) {?>
					  <option selected value="<?= $_REQUEST[area] ?>"><?= $REQUEST[area] ?></option>
					<?} else {?>
					  <option selected value="<?= $row[area] ?>"><?= $row['area'] ?></option>
					<?}

					//$dbgetareas = dbRead("select place from area where CID='".$_SESSION['User']['CID']."' order by place");
					$dbgetareas = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName");
					while($row1 = mysql_fetch_assoc($dbgetareas)) {
					?>
					<option value="<?= $row1['FieldID'] ?>"><?= $row1['RegionalName'] ?></option>
					<?

					}

					?>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("18") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="postcode" <? if($_REQUEST[postcode]) {?>value="<?= $_REQUEST[postcode] ?>"<?} else {?>value="<?= get_all_added_characters($row['postcode']) ?>"<?}?> onKeyPress="return number(event)"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("9") ?>:</b></td>
					<td <?if($err == 1) {?>bgcolor="#FF0000"<?} else {?>bgcolor="#FFFFFF"<?}?>>
                    <input size="25" type="text" name="emailaddress" value="<?= get_all_added_characters($row['email']) ?>"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("124") ?>:</b></td>
					<td bgcolor="#FFFFFF"><input size="25" type="file" name="picture" value="<?= $_REQUEST[picture] ?>"><br><?= get_page_data("4")?></td>
				</tr>
				<tr>
					<td width="150" align="right" valign="top" class="Heading2"><b><?= get_word("27") ?>:</b></td>
					<td bgcolor="#FFFFFF"><textarea cols="50" rows="6" name="shortdesc" value="<?= $_REQUEST[shortdesc] ?>"></textarea></td>
				</tr>
				<tr width="100%">
					<td colspan="2" class="Heading" align="center">Select other country to submit classified to if required</td>
				</tr>
				 <?
                   $query2 = dbRead("select * from country where Display = 'Yes' and countryID != ".$_SESSION['User']['CID']." order by name");
                    while ($row2 = mysql_fetch_assoc($query2)) {
				 ?>
				<tr>
					<td width="150" align="right" valign="top" class="Heading2"><b><?= get_all_added_characters($row2['name'])  ?>:</b></td>
					<td bgcolor="#FFFFFF"><input type="checkbox" name="<?= $row2['countryID'] ?>" value="1"></td>
				</tr>
				<?}?>
				<tr>
					<td width="150" class="Heading2">&nbsp;</td>
					<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("181") ?>" name="ac"></td>
				</tr>
				<input type="hidden" name="addclassified" value="1">
			</form>
			</table>
		</td>
	</tr>
</table>
</td>
</tr>
</table>
</body>

<?
die;
}

$CDataSQL = dbRead("select * from countrydata where CID = '".$_SESSION['Country']['countryID']."'");
$CDataRow = mysql_fetch_assoc($CDataSQL);
?>

<BODY BGCOLOR=#FFFFFF text="#000000" link="#0000CC" vlink="#0000CC" alink="#FF6600" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" STYLE="border-collapse: collapse" WIDTH="100%" ID="AutoNumber1">
  <form method="POST" action="body.php?page=clas_add">
  <TR>
    <TD WIDTH="90%"><?= eval(" ?>".$CDataRow['clas_rules']."<? ") ?></td>
  </TR>
  <tr>
    <td>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td align ="center"><input type="submit" value="<?= get_page_data("3") ?>" name="add"></td>
  </tr>
  <input type="hidden" name="Accept" value="1">
  <input type="hidden" name="memid" value="<?= $_REQUEST[memid] ?>">
 </form>
</TABLE>
<p>&nbsp;</p>
</BODY>
</html>