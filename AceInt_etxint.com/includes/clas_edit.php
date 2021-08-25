<?
$time_start = getmicrotime();

if($_REQUEST[editclas2])  {

print "<!-- 1 -->";

$dbgetdata = dbRead("select category, currency from classifieds, country where (classifieds.cid_origin = country.countryID) and id='".$_REQUEST[clasid]."'");

list($category, $currency) = mysql_fetch_row($dbgetdata);

$dbgetcat = dbRead("select category as c from categories where catid='".$category."'");
list($c) = mysql_fetch_row($dbgetcat);

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
</head>

<body onload="javascript:setFocus('Clas','productname');">
<?

								#getstuffout
								$dbgetdet = dbRead("select * from classifieds where id='$_REQUEST[clasid]'");

								list($clasid, $date, $name, $shortdesc, $detaildesc, $phone, $price, $catid4, $emailaddress, $suburb, $postcode, $area, $type, $checked, $int_check, $productname, $image, $memid, $tradeprice, $CID, $cid_origin)=mysql_fetch_row($dbgetdet);
								if($type == 1) {

									$distype="Buy";

								} else {

									$distype="Sell";

								}

?>
<table width="620" border="0" cellpadding="1" cellspacing="1">
<tr>
<td class="Border">
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<form method="post" action="/general.php" name="Clas">
	<input type="hidden" name="clasno" value="<?= $clasid ?>">
	  <tr>
		<td class="Heading" colspan="2"><?= get_page_data("1") ?></td>
	  </tr>
	  <tr>
	    <td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("119") ?>:</b></td>
		<td bgcolor="#FFFFFF"><input size="30" type="text" name="productname" value="<?= get_all_added_characters($productname) ?>" <?= checkDisable($code) ?>></td>
 	  </tr>
	  <tr>
		<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
		<td bgcolor="#FFFFFF">
        <select name="catid5" <?= checkDisable($code) ?>>
         <?
	 	  $dbgetclascat2 = dbRead("select catid as cid1, category as cid2 from categories where catid='$catid4'");
		  list($cid1, $cid2) = mysql_fetch_row($dbgetclascat2);
 		 ?>
		  <option selected value="<?= $cid1 ?>"><?= $cid2 ?></option>
         <?
   		  $dbgetclascat = dbRead("select catid as id8, category from categories where CID='$CID' order by category");
		  while(list($id8, $category) = mysql_fetch_row($dbgetclascat)) {
 		   ?>
		    <option value="<?= $id8 ?>"><?= $category ?></option>
         <?
  		  }
	  	 ?>
																</select></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("121") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="10" type="text" name="price" value="<?= $price ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("122") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="10" type="text" name="tradeprice" value="<?= $tradeprice ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("86") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <select name="type" <?= checkDisable($code) ?>>
																<option selected value="<?= $type ?>"><?= $distype ?></option>
																<option value="2">Sell</option>
																<option value="1">Buy</option>
																</select>
																</td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("120") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="25" type="text" name="name" value="<?= $name ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("7") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="10" type="text" name="phone" value="<?= $phone ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("15") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="25" type="text" name="suburb" value="<?= $suburb ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("78") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <select name="areaid" <?= checkDisable($code) ?>>
                    <?

					$dbgetareas = dbRead("select tbl_area_regional.FieldID, tbl_area_regional.RegionalName as RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName");
					while($row1 = mysql_fetch_assoc($dbgetareas)) {
					?>
					<option value="<?= $row1['FieldID'] ?>" <?if($area == $row1['FieldID']) { echo " selected";}?>><?= $row1['RegionalName'] ?></option>
					<?

					}

					?>
																</select></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("18") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="10" type="text" name="postcode" value="<?= get_all_added_characters($postcode) ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_word("9") ?>:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input size="25" type="text" name="emailaddress" value="<?= get_all_added_characters($emailaddress) ?>" <?= checkDisable($code) ?>></td>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b>Update All:</b></td>
																<td bgcolor="#FFFFFF">
                                                                <input type="checkbox" name="all" value="1" <?= checkDisable($code) ?>></td>
															</tr>
															<?if($image != "noimg.gif") {?>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b>Image:</b></td>
																<td bgcolor="#FFFFFF"><a href="http://www.ebanctrade.com/clasimages/<?= $image ?>" target="_new"><img src="http://www.ebanctrade.com/clasimages/thumb-<?= $image ?>" border="0"></a></tr>
															</tr>
															<?}?>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"><b><?= get_page_data("2") ?>:</b></td>
																<td bgcolor="#FFFFFF">
																<textarea cols="50" rows="6" name="shortdesc" <?= checkDisable($code) ?>><?= get_all_added_characters($shortdesc) ?></textarea>
															</tr>
															<tr>
																<td width="150" valign="top" align="right" class="Heading2"></td>
																<td align="left" bgcolor="#FFFFFF">
                                                                <input type="submit" value="<?= get_page_data("1") ?>" name="changeclas">&nbsp;<input type="submit" value="<?= get_word("125") ?>" name="deleteclas"></td>
															</tr>
															<input type="hidden" value="<?= $_REQUEST['type1'] ?>" name="check">
														</form>
														</table>
											</td>
											</tr>
											</table>

</body>
</html>

<?
die;
}
?>

<html>

<head>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
<meta name='GENERATOR' content='Microsoft FrontPage 5.0'>
<title>Edit Classifieds</title>
</head>

<body>
<table width="620" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
					<table width="100%" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="5" bgcolor="#000000" align="center" class="Heading"><?= get_page_data("1") ?></td>
						</tr>
						<tr>
							<td align="left" width="10%" class="Heading2"><b>ID:</b></td>
							<td align="left" width="30%" class="Heading2"><b><?= get_word("119") ?>:</b></td>
							<td align="right" width="20%" class="Heading2"><b>Date Added:</b></td>
							<td align="right" width="20%" class="Heading2"><b><?= get_word("121") ?>:</b></td>
							<td align="right" width="20%" class="Heading2"><b><?= get_word("122") ?>:</b></td>
						</tr>
						<?

						$dbgetmemcats = dbRead("select id, date, price, tradeprice, productname, currency from classifieds, country where (classifieds.cid_origin = country.countryID) and CID = ".$_SESSION['User']['CID']." and int_check = 1 order by id");

						$foo = 0;

						while($row = mysql_fetch_assoc($dbgetmemcats)) {

						$cfgbgcolorone = "#CCCCCC";
						$cfgbgcolortwo = "#EEEEEE";
						$bgcolor = $cfgbgcolorone;
						$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

						$price2 = number_format($row['price'],2);
						$tradeprice2 = number_format($row['tradeprice'],2);

						?>
						<tr>
							<td align="left" width="10%" bgcolor="<?= $bgcolor ?>"><a class="nav" href="body.php?page=clas_edit&editclas2=true&clasid=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
							<td align="left" width="30%" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row['productname']) ?></td>
							<td align="right" width="20%" bgcolor="<?= $bgcolor ?>"><?= $row['date'] ?></td>
							<td align="right" width="20%" bgcolor="<?= $bgcolor ?>"><?= $row['currency'] ?><?= $price2 ?></td>
							<td align="right" width="20%" bgcolor="<?= $bgcolor ?>"><?= $row['currency'] ?><?= $tradeprice2 ?></td>
						</tr>
						<?
						$foo++;
						}
						?>
						<tr>
						    <td colspan="5" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
						    $time_end = getmicrotime();
						    $time = $time_end - $time_start;
							$time = number_format($time,2);
							echo $time;
						    ?> seconds</td>
						</tr>
					</table>
</td>
</tr>
</table>

</body>
</html>

<?

	function checkDisable($code) {

		if($code == 1) {

			print "disabled";

		}

	}

?>