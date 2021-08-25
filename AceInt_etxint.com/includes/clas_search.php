<?

if(!checkmodule("ClasSearch")) {

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

if(!$_REQUEST[countryid])  {
   $searchcountry = $_SESSION[User][CID];
} else {
   $searchcountry = $_REQUEST[countryid];
}

if($_REQUEST[cs] || $cs == 1) {

 if(checkmodule("Log")) {
  add_kpi("43", "0");
 }

#pages numbers and stuff.
if($_REQUEST['next']) {
	$_REQUEST['pageno'] += 1;
} elseif($_REQUEST['back']) {
	$_REQUEST['pageno'] -= 1;
}

if($_REQUEST['pageno'] == 1) {
	$first = 0;
	$second = $_REQUEST['numpages'];
} else {
	$first = ($_REQUEST['pageno']-1)*$_REQUEST['numpages'];
	$second = $_REQUEST['numpages'];
}

if($_REQUEST['area']) {
 $count = 0;
 //$query = dbRead("select FieldID from area where disarea='$area' and CID='".$searchcountry."'");
 $query = dbRead("select tbl_area_physical.AreaName as AreaName from tbl_area_physical, tbl_area_regional where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and tbl_area_regional.FieldID='$area'");
 while($row = mysql_fetch_assoc($query)) {
  if($count == 0) {
   $andor = "";
  } else {
   $andor = "or";
  }
  $area_array .= " ".$andor." area='".$row[AreaName]."'";
  $count++;
 }
}

#search thing :)
if(substr($_REQUEST['areaid'],0,3) == 'CID' && $_REQUEST['category'])  {
    $C = substr($_REQUEST['areaid'],3);
	$csearch = "select id, cid_origin, suburb, postcode, date, name, shortdesc, price, phone, emailaddress, areaid, productname, tradeprice, memid, type, image from classifieds where category='".$_REQUEST['category']."' and cid_origin = '$C' and checked='1' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($productname))."%') and CID='".$_SESSION['User'][CID]."' order by date DESC limit $first, $second";
	$csearch2 = "select id from classifieds where category='$category' and cid_origin = '$C' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and checked='1' and CID='".$_SESSION['User'][CID]."'";
} elseif(substr($_REQUEST['areaid'],0,3) == 'CID')  {
    $C = substr($_REQUEST['areaid'],3);
    echo $C;
	$csearch = "select id, cid_origin, suburb, postcode, date, name, shortdesc, price, phone, emailaddress, areaid, productname, tradeprice, memid, type, image from classifieds where cid_origin = '$C' and checked='1' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and CID='".$_SESSION['User'][CID]."' order by type desc, date DESC limit $first, $second ";
	$csearch2 = "select id from classifieds where cid_origin = '$C' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and checked='1' and CID='".$_SESSION['User'][CID]."'";
} elseif($_REQUEST['category'] && $_REQUEST['areaid']) {
	$csearch="select id, cid_origin, suburb, postcode, date, classifieds.name as name, shortdesc, price, classifieds.phone as phone, emailaddress, areaid, productname, tradeprice, memid, type, image, country.currency from classifieds, country where (classifieds.cid_origin = country.countryID) and category='".$_REQUEST['category']."' and areaid = '$areaid' and checked='1' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and classifieds.CID='".$searchcountry."' order by type desc, date DESC limit $first, $second";
	$csearch2="select id from classifieds where category='".$_REQUEST['category']."' and areaid = '".$_REQUEST['areaid']."' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and checked='1' and CID='".$searchcountry."'";
} elseif($_REQUEST['category']) {
	$csearch="select id, cid_origin, suburb, postcode, date, classifieds.name as name, shortdesc, price, classifieds.phone as phone, emailaddress, areaid, productname, tradeprice, memid, type, image, country.currency from classifieds, country where (classifieds.cid_origin = country.countryID) and category='".$_REQUEST['category']."' and checked='1' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and classifieds.CID='".$searchcountry."' order by type desc, date DESC limit $first, $second";
	$csearch2="select id from classifieds where category='".$_REQUEST['category']."' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and checked='1' and CID='".$searchcountry."'";
} elseif($_REQUEST['areaid']) {
	$csearch="select id, cid_origin, suburb, postcode, date, classifieds.name as name, shortdesc, price, classifieds.phone as phone, emailaddress, areaid, productname, tradeprice, memid, type, image, country.currency from classifieds, country where (classifieds.cid_origin = country.countryID) and areaid = '".$_REQUEST['areaid']."' and checked='1' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and classifieds.CID='".$searchcountry."' order by type desc, date DESC limit $first, $second ";
	$csearch2="select id from classifieds where areaid = '".$_REQUEST['areaid']."' and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and checked='1' and CID='".$searchcountry."'";
} else {
	$csearch="select id, cid_origin, suburb, postcode, date, classifieds.name as name, shortdesc, price, classifieds.phone as phone, emailaddress, areaid, productname, tradeprice, memid, type, image, country.currency from classifieds, country where (classifieds.cid_origin = country.countryID) and (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and classifieds.CID='".$searchcountry."' and checked='1' order by type desc, date DESC limit $first, $second";
	$csearch2="select id from classifieds where (productname like '%".encode_text2(addslashes($_REQUEST['productname']))."%' or detaildesc like '%".encode_text2(addslashes($_REQUEST['productname']))."%') and checked='1' and CID='".$searchcountry."'";
}

$dbsearch = dbRead($csearch);
$dbsearch2 = dbRead($csearch2);

$searchcount = mysql_num_rows($dbsearch2);
$pagenumber = $_REQUEST['pageno'];
$totalpages2 = $searchcount/$_REQUEST['numpages'];
$totalpages = ceil($totalpages2);


?>
<html>
<head>
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=no,menubar=no,width=639,height=479');
}
</script>
</head>
<body>
<table width="620" border="0" cellpadding="0" cellspacing="1">
	<tr>
		<td align="left"><?= get_word("127") ?>: <b><?= $searchcount ?></b></td>
		<td align="right">Page <?= $pagenumber ?> of <?= $totalpages ?></td>
	</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="1">
	<tr height="15" width="100%">
		<td colspan="3" class="Heading"><b>&nbsp;<?= get_page_data("1") ?>.</b></td>
	</tr>
<?

		if(mysql_num_rows($dbsearch) == 0) {

		print'<p align="center"><br><b>No Search Results</b><br><br></p>';

		} else {

		while($row = mysql_fetch_assoc($dbsearch)) {

		$array1 = explode(" ", $row[date]);
		$array2 = explode("-", $array1[0]);
		$month = $array2[1];
		$day = $array2[2];
		$year = $array2[0];
		$newdate = date("l jS	F Y", mktime(0,0,0,$month,$day,$year));

?>
	<tr width="100%">
		<td valign="top" bgcolor="#FFFFFF">
		<table border="0" width="100%" cellspacing="1" cellpadding="3">
			<tr>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td width="300" colspan=3" align="left" valign="top" class="Heading"><b><?= get_all_added_characters($row[productname]) ?> - <?= $_SESSION['Country']['currency'] ?><?= number_format(($row[price]+$row[tradeprice]),2) ?></b></td>
				<td align="right" valign="top" class="Heading">ID: <?= $row['id'] ?></td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
			</tr>

			<tr>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td width="75" rowspan="12" valign="top">
			<?
			if($row[image]) {
			 if(file_exists("/home/etxint/public_html/clasimages/$row[image]")) {
			 ?>
				<a href="javascript:open_win('/clasimages/<?= $row[image] ?>');"><img src="/clasimages/thumb-<?= $row[image]?>" border="0"></a><br>
			 <?
			 }
			}
			?>
			<?
			if($row['cid_origin'] == $_SESSION['Country']['countryID']) {
				$dbgetareas = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_regional.FieldID = ".$row['areaid']." and tbl_area_states.CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
				while($row2 = mysql_fetch_assoc($dbgetareas)) {
				  $rname = $row2['RegionalName'];
				}
			} else {
				$rname = $row['cname'];
			}
			?>
				 <br><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF9900"><center><b><?= get_all_added_characters($rname) ?><br><?= get_all_added_characters($row[suburb]) ?>, <?= get_all_added_characters($row[postcode]) ?><br><a href="body.php?page=email&classid=<?= $row['id'] ?>">EMAIL</a></b></center></font>
				</td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td align="left" colspan="2" valign="top" ><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><a href="javascript:open_win('body.php?page=clas_detail&id=<?= $row[id] ?>');" class="nav"><?= get_all_added_characters($row['shortdesc']) ?></a></font></td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td align="left" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><br><?= get_word("122")?>: <?= $_SESSION['Country']['currency'] ?><?= number_format($row[tradeprice],2) ?><br><?= get_word("121")?>: <?= $_SESSION['Country']['currency'] ?><?= number_format($row[price],2) ?></font></td>
				<td align="left" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><br><?= get_all_added_characters($row[name]) ?><br><?= get_all_added_characters($row[phone]) ?><br><?= get_all_added_characters($row['emailaddress']) ?></font></td>

			</tr>

<?

				if($row[type] == 2) {

					print'For Sale';

				} elseif($row[type] == 1) {

					print'Wanted To Buy';

				}

?>

		</table>
	    </td>
	</tr>
<?

		}

		}

?>

</table>
</td>
</tr>
</table>
<br>
<form method="POST" action="<?= $PHP_SELF ?>?page=clas_search">
<input type="hidden" value="<?= $_REQUEST[category] ?>" name="category">
<input type="hidden" value="<?= $_REQUEST[area] ?>" name="area">
<input type="hidden" value="<?= $_REQUEST[pageno] ?>" name="pageno">
<input type="hidden" value="1" name="cs">
<input type="hidden" value="<?= $_REQUEST[numpages] ?>" name="numpages">
<input type="hidden" value="<?= $_REQUEST[pagenos] ?>" name="pagenos">
<input type="hidden" value="<?= $_REQUEST[productname] ?>" name="productname">
<input type="hidden" value="<?= $searchcountry ?>" name="countryid">
<table width="640" border="0" cellpadding="0" cellspacing="1">
	<tr>
		<td align="left">
<?

		if(!$_REQUEST[pagenos]) {
		  $_REQUEST[pagenos] = 1;
		}

		$toplimit = $_REQUEST[pagenos]*15;
		$startlimit = $toplimit-14;

		if($_REQUEST[pageno] != 1) {

		print'<input type="submit" name="back" value="<< Back">';

		} else {

		print'<img src="/images/spacer.gif" width="62" height="1">';

		}

		print'
		</td>
		<td align="center">';

		if($_REQUEST[pagenos] > 1) {
		$pn2 = $_REQUEST[pagenos]-1;
		print'<a href="'.$PHP_SELF.'?page=clas_search&category='.$row[category].'&area='.$row[area].'&pageno='.$_REQUEST[pageno].'&cs=1&numpages='.$_REQUEST[numpages].'&pagenos='.$pn2.'&productname='.$_REQUEST[productname].'&countryid='.$searchcountry.'" class="nav">Prev</a> ';

		}

		$pagecounter=$startlimit;
		if($toplimit >= $totalpages) {
		  $toplimit2 = $totalpages;
		} else {
		  $toplimit2 = $toplimit;
		}
		while($pagecounter <= $toplimit2) {

			if($pagecounter == $pageno) {
			print'<a href="'.$PHP_SELF.'?page=clas_search&category='.$sendcategory.'&area='.$sendarea.'&pageno='.$pagecounter.'&cs=1&numpages='.$_REQUEST[numpages].'&pagenos='.$_REQUEST[pagenos].'&productname='.$sendproductname.'&countryid='.$searchcountry.'" class="nav"><b>'.$pagecounter.'</b></a> ';
			$pagecounter += 1;
			} else {
			print'<a href="'.$PHP_SELF.'?page=clas_search&category='.$sendcategory.'&area='.$sendarea.'&pageno='.$pagecounter.'&cs=1&numpages='.$_REQUEST[numpages].'&pagenos='.$_REQUEST[pagenos].'&productname='.$sendproductname.'&countryid='.$searchcountry.'" class="nav">'.$pagecounter.'</a> ';
			$pagecounter += 1;
			}

		}

		if($totalpages > $toplimit) {

		$pn = $pagenos+1;
		print' <a href="'.$PHP_SELF.'?page=clas_search&category='.$sendcategory.'&area='.$sendarea.'&pageno='.$_REQUEST[pageno].'&cs=1&numpages='.$_REQUEST[numpages].'&pagenos='.$pn.'&productname='.$sendproductname.'&countryid='.$searchcountry.'" class="nav">More</a>';

		}

		print'</td>
		<td align="right">';

		if($_REQUEST[pageno] >= $totalpages) {

		print'<img src="/images/spacer.gif" width="62" height="1">';

		} else {

		print'<input type="submit" name="next" value="Next >>">';

		}

?>
		</td>
	</tr>

</table>
</form>
</body>
</html>
<?

} else {

$dbtotalclas = dbRead("select count(*) as tc from classifieds where checked='1' and CID='$searchcountry'");
$clas = mysql_fetch_assoc($dbtotalclas);

?>
<html>
<head>
<SCRIPT language=JavaScript>
function ChangeCountry(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=clas_search&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}
</script>
</head>
<body onload="javascript:setFocus('Clas','productname');">
<form name="classearch" method="POST" action="<?= $PHP_SELF ?>?page=clas_search" name="Clas">
<table width="620" cellpadding="1">
 <tr>
  <td class="Border">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td>
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
 <tr>
  <td height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $searchcountry) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}
?>
   </select>&nbsp;</td>
  </tr>
   </table>
  </td>
 </tr>
   <tr>
    <td>
    <table width="100%" border="0" cellpadding="3" cellspacing="0">
	 <tr height="15" width="100%">
	  <td colspan="3" width="100%" class="Heading" align="center"><b>&nbsp;<?= get_page_data("1") ?>.</b></font></td>
	 </tr>
    </table>
    </td>
   </tr>
   <tr width="100%">
		<td valign="top">
			<table width="100%" border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td align="right" class="Heading2"><?= get_word("119") ?>:</td>
					<td align="left" bgcolor="#FFFFFF"><input type="text" name="productname" size="20"></td>
					<td align="right" bgcolor="#FFFFFF"><?= get_word("127") ?>:</font></td>
					<td align="right" bgcolor="#FFFFFF"><b><?= $clas[tc] ?></b></td>
				</tr>
				<tr>
					<td align="right" class="Heading2"><?= get_word("26") ?>:</td>
					<td bgcolor="#FFFFFF"><select name="category">
					<option selected value=""><?= get_word("160") ?></option>
					<?

					$dbgetclascat = dbRead("select catid, category from categories where CID='".$searchcountry."' order by category ASC");
					while($row = mysql_fetch_assoc($dbgetclascat)) {

						print'<option value="'.$row[catid].'">'.$row[category].'</option>';

					}

					?>
					</select><input type="hidden" value="1" name="pageno"></td>
					<td bgcolor="#FFFFFF"></td>
					<td bgcolor="#FFFFFF"></td>
				</tr>
				<tr>
					<td align="right" class="Heading2"><?= get_word("78") ?>:</td>
					<td bgcolor="#FFFFFF"><select name="areaid">
					<option selected value=""><?= get_word("161") ?></option>
					<?

					$dbgetareas = dbRead("select name, countryID from country where Display = 'Yes' and countryID != '".$_SESSION['User']['CID']."' order by name");
					while($row = mysql_fetch_assoc($dbgetareas)) {

						print'<option value="CID'.$row['countryID'].'">
                    '.$row['name'].'</option>';

					}

					//$dbgetareas = dbRead("select disarea from area where CID='".$searchcountry."' group by disarea asc");
					$dbgetareas = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$searchcountry."' order by RegionalName asc");
					while($row = mysql_fetch_assoc($dbgetareas)) {

						print'<option value="'.$row[FieldID].'">
                    '.$row[RegionalName].'</option>






					';

					}

					?>
					</select></td>
					<td bgcolor="#FFFFFF"></td>
					<td bgcolor="#FFFFFF"></td>
				</tr>
				<tr>
					<td align="right" class="Heading2"><?= get_word("126") ?>:</td>
					<td bgcolor="#FFFFFF"><select name="numpages">
					<option selected value="6">6</option>
					<option value="12">12</option>
					<option value="18">18</option>
					<option value="36">36</option>
					<option value="72">72</option>
					<option value="10000000">All</option>
					</select></td>
					<td colspan="2" align="right" bgcolor="#FFFFFF"><input name="cs5" type="submit" value="<?= get_word("48") ?> >>"></td>
	  </tr>
	 </table>
	 </td>
	</tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="countryid" value="<?= $searchcountry ?>">
<input type="hidden" name="cs" value="1">

</form>
</body>
</html>
<? } ?>