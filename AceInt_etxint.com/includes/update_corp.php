<form enctype="multipart/form-data" method="POST" action="body.php?page=update_corp&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

$tabarray = array('Page Data Update','Headers/Link Update','Spotlight Update','Add Spotlight','FAQ Update');
if(checkmodule("SuperUser")) { $tabarray[] = 'Add FAQ'; }
if(checkmodule("SuperUser")) { $tabarray[] = 'Page Data Add'; }

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  data();

} elseif($_REQUEST['tab'] == "tab2") {

	if($_REQUEST['dbUpdate']) {

		headersUpdate();
		display_edit();

	} else {

		display_edit();

	}

} elseif($_GET[tab] == "tab3") {

  spot();

} elseif($_GET[tab] == "tab4") {

  spotadd();

} elseif($_GET[tab] == "tab5") {

  FAQ();

} elseif($_GET[tab] == "tab6") {

  FAQadd();

} elseif($_GET[tab] == "tab7") {

  dataadd();

}

?>

</form>

<?

function headersUpdate() {

	$headersArray = $_REQUEST['headers'];
	$linkArray = $_REQUEST['link'];
	$activeArray = $_REQUEST['active'];

	foreach($headersArray as $key => $value) {

        $query6 = dbRead("select * from tbl_corp_headers where fieldid = " . $key);
        $row6 =	mysql_fetch_assoc($query6);

		dbWrite("update tbl_corp_headers set page_header = '". addslashes(encode_text2($value)) ."', page_link = '". addslashes(encode_text2($linkArray[$key])) ."', page_active = '". $activeArray[$key] ."' where fieldid = " . $key);

        if(encode_text2($value) != $row6['page_header']) {
          $logdata['Header'] = array($row6['page_header'],$value);
        }

        if(encode_text2($linkArray[$key]) != $row6['page_link']) {
          $logdata['Link'] = array($row6['page_link'],encode_text2($linkArray[$key]));
        }

        if(encode_text2($activeArray[$key]) != $row6['page_active']) {
          $logdata['Active'] = array($row6['page_active'],encode_text2($activeArray[$key]));
        }

       add_kpi2(2,$row6['pageid'],$row6['langcode'],$row6['CID'],$logdata);
	   unset($logdata);
	}
}

function display_edit() {

	if(!$_REQUEST['editCountry']) {

	 if(checkmodule("SuperUser")) {
	  $lim = "";
	 } else {
	  $lim = " and countryID = ".$_SESSION['User']['CID']."";
     }
		?>

		<table border="0" width="639" cellpadding="1" cellspacing="0">
			<tr>
				<td class="Border">
					<table border="0" width="100%" cellspacing="0" cellpadding="3">
						<tr>
							<td colspan="2" align="center" class="Heading"><b>Page Headers Edit</b></td>
						</tr>
						<tr>
							<td align="center" valign="middle" colspan="2" class="Heading2"><b>Select Country</b></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
							<td bgcolor="#FFFFFF" align="left">
								<?

									$sql_query = dbRead("select * from country where Display = 'Yes'$lim order by name");
									form_select('editCountry',$sql_query,'name','countryID','','','','','countryID');

								?>
							</td>
						</tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Language:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$lang_query = dbRead("select Langcode, web_langs from country where Display = 'Yes'$lim group by Langcode order by Langcode");
            while($langRow = mysql_fetch_assoc($lang_query)) {

              if($langRow['web_langs']) {

              	if(strstr($langRow['web_langs'], ",")) {

              		$explodeArray = explode(",", $langRow['web_langs']);
              		foreach($explodeArray as $key => $value) {

              			$displayArray[$value] = $value;

              		}

              	} else {

              		$displayArray[$langRow[web_langs]] = $langRow['web_langs'];

              	}

              }

               $displayArray[$langRow[Langcode]] = $langRow['Langcode'];

			   $langCode = $langRow['Langcode'];

            }

            form_select('langcode',$displayArray,'','',$langCode);
           ?>
    </td>
   </tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="30%">&nbsp;</td>
							<td bgcolor="#FFFFFF" align="left"><input type="submit" name="submitForm" value="Edit Headers"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<?

	} else {

		?>
		<input type="hidden" name="dbUpdate" value="1">
		<input type="hidden" name="editCountry" value="<?= $_REQUEST['editCountry']?>">
		<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode']?>">
		<table border="0" width="610" cellpadding="1" cellspacing="0">
			<tr>
				<td class="Border">
					<table border="0" width="100%" cellspacing="0" cellpadding="3">
						<tr>
							<td colspan="4" align="center" class="Heading"><b>Page Headers/Link Edit</b></td>
						</tr>
						<tr>
							<td align="center" valign="middle" class="Heading2"></td>
							<td align="center" valign="middle" class="Heading2"><b>Page Header</b></td>
							<td align="center" valign="middle" class="Heading2"><b>Link</b></td>
							<td align="right" class=" Heading2" ><b>Active</b></td>
						</tr>

						<?

							$sqlQuery = dbRead("select  tbl_corp_pages.page, tbl_corp_headers.* from tbl_corp_headers, tbl_corp_pages where (tbl_corp_pages.pageid = tbl_corp_headers.pageid) and CID = " . $_REQUEST['editCountry'] ." and langcode = '".$_REQUEST['langcode']."' order by page");
							while($sqlRow = mysql_fetch_assoc($sqlQuery)) {

								?>

								<tr>
									<td align="right" valign="middle" class="Heading2" ><b><?= $sqlRow['page'] ?>:</b></td>
									<td bgcolor="#FFFFFF" align="left"><input type="text" name="headers[<?= $sqlRow['fieldid'] ?>]" value="<?= $sqlRow['page_header'] ?>" size="37"></td>
									<td bgcolor="#FFFFFF" align="left"><input type="text" name="link[<?= $sqlRow['fieldid'] ?>]" value="<?= $sqlRow['page_link'] ?>" size="25"></td>
									<td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="active[<?= $sqlRow['fieldid'] ?>]" value="1" <? if($sqlRow['page_active']) { print "checked"; } ?>></td>
								</tr>

								<?

							}

						?>
						<tr>
							<td align="center" valign="middle" class="Heading2">&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right" colspan="3"><input type="submit" name="dbUpdate" value="Update Headers"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<?

	}

}

function data() {

 if($_REQUEST['searchdata'])  {
?>
<form method="post" action="body.php?page=update_admin" name="changearea">
<table border="0" width="620" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $eng_query = dbRead("select * from tbl_corp_data where CID = 1 and langcode = 'en' and pageid = ".$_REQUEST['pageid']." order by position");
  $EngRow = mysql_fetch_assoc($eng_query);

  $sql_query = dbRead("select * from tbl_corp_data where CID = '".$_REQUEST['CID']."' and langcode = '".$_REQUEST['langcode']."' and pageid = ".$_REQUEST['pageid']." order by position");
  while($row = mysql_fetch_assoc($sql_query)) {

    if($_REQUEST['CID'] == 12 && $_REQUEST['langcode'] == 'ro') {
     $eng_query = dbRead("select * from tbl_corp_data where CID = 12 and langcode = 'hu' and pageid = ".$_REQUEST['pageid']." and position = ".$row['position']."");
     $EngRow = mysql_fetch_assoc($eng_query);
    } else {
     $eng_query = dbRead("select * from tbl_corp_data where CID = 1 and langcode = 'en' and pageid = ".$_REQUEST['pageid']." and position = ".$row['position']."");
     $EngRow = mysql_fetch_assoc($eng_query);
    }

    $length = strlen($row['data']);
    $length = ($length/42)+1;
  ?>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="40%"><b><?= $EngRow['data'] ?> (<?= $row['position'] ?>):</b></td>
    <td bgcolor="#FFFFFF" width="60%"><textarea name="<?= $row['position'] ?>" cols="45" rows="<?= $length ?>"><?= htmlspecialchars($row['data']) ?></textarea></td>
   </tr>
 <?}?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update Data" name="updateadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="pageid" value="<?= $_REQUEST['pageid'] ?>">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
<input type="hidden" name="dataupdate" value="1">

</form>
</body>
 <?
 die;
 }

if($_REQUEST[dataupdate])  {

  $lang_query = dbRead("select position from tbl_corp_data where pageid = '".$_REQUEST['pageid']."' group by position order by position");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $Wlang_query = dbRead("select * from tbl_corp_data where pageid = '".$_REQUEST['pageid']."' and position = '".$rowlang['position']."' and langcode = '".$_REQUEST['langcode']."' and CID = '".$_REQUEST['CID']."'");
   $Wrowlang = mysql_fetch_assoc($Wlang_query);
   if(encode_text2($_REQUEST[$rowlang['position']]) != $Wrowlang['data']) {
     $logdata[$rowlang['position']] = array($Wrowlang['data'],encode_text2($_REQUEST[$rowlang['position']]));
   }

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_corp_data");

   //$SQL->add_item("position", encode_text2($rowlang['position']));
   //$SQL->add_item("pageid", encode_text2($_REQUEST['pageid']));
   //$SQL->add_item("langcode", encode_text2($_REQUEST['Langcode']));
   $SQL->add_item("data", encode_text2($_REQUEST[$rowlang['position']]));

   $SQL->add_where("pageid = '".$_REQUEST['pageid']."'");
   $SQL->add_where("position = '".$rowlang['position']."'");
   $SQL->add_where("langcode = '".$_REQUEST['langcode']."'");
   $SQL->add_where("CID = '".$_REQUEST['CID']."'");

   dbWrite($SQL->get_sql_update());

  }
  add_kpi2(1,$_REQUEST['pageid'],$_REQUEST['langcode'],$_REQUEST['CID'],$logdata);

 }
//}

if(checkmodule("SuperUser")) {
 $SearchCID = "%";
} else {
 $SearchCID = $_SESSION['User']['CID'];
}

if(checkmodule("SuperUser")) {
 $SearchReports = "all";
 $lim = "";
} else {
 $SearchReports = $_SESSION['User']['ReportsAllowed'];
 $lim = " and countryID = ".$_SESSION['User']['CID']."";
}

if($SearchReports == "all") {
 $query3 = dbRead("select FieldID from area where CID like '".$SearchCID."' order by place");
 //$query3 = dbRead("select FieldID from area where CID = 8 order by place");
 while($row3 = mysql_fetch_assoc($query3)) {
  $at .= $row3['FieldID'].",";
 }
 $adminuserarray = explode(",", $at);
} else {
 $adminuserarray = explode(",", $SearchReports);
}?>

<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$lang_query = dbRead("select name, countryID from country where Display = 'Yes'$lim order by name");
            //form_select('langcode',$lang_query,'name','Langcode');
            form_select('CID',$lang_query,'name','countryID');
           ?>
    </td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Language:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$lang_query = dbRead("select Langcode, web_langs from country where Display = 'Yes'$lim group by Langcode order by Langcode");
            while($langRow = mysql_fetch_assoc($lang_query)) {

              if($langRow['web_langs']) {

              	if(strstr($langRow['web_langs'], ",")) {

              		$explodeArray = explode(",", $langRow['web_langs']);
              		foreach($explodeArray as $key => $value) {

              			$displayArray[$value] = $value;

              		}

              	} else {

              		$displayArray[$langRow[web_langs]] = $langRow['web_langs'];

              	}

              }

               $displayArray[$langRow[Langcode]] = $langRow['Langcode'];

			   $langCode = $langRow['Langcode'];

            }

            form_select('langcode',$displayArray,'','',$langCode);
           ?>
    </td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Page:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
            $sql_query = dbRead("select * from tbl_corp_pages, tbl_corp_headers, tbl_corp_data where (tbl_corp_pages.pageid = tbl_corp_headers.pageid) and (tbl_corp_pages.pageid = tbl_corp_data.pageid) group by tbl_corp_pages.page order by tbl_corp_pages.page");
            form_select('pageid',$sql_query,'page','pageid','','','','','fieldid');
           ?>
    </td>
   </tr>
   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="search Data" name="datasearch" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="searchdata" value="1">

<?
}

function check_disabled() {

 if(!checkmodule("SuperUser")) {

  return " disabled";

 }

}

function dataadd() {

 if($_REQUEST[adddata])  {

  $p_query = dbRead("select position as max from tbl_corp_data where pageid = '".$_REQUEST['pageid']."' group by position order by position Desc");
  $p_row = mysql_fetch_assoc($p_query);
  $pos = $p_row['max']+1;

  //$lang_query = dbRead("select Langcode as Langcode, countrycode, countryID from country where Display = 'Yes' group by countrycode order by countrycode");
  $lang_query = dbRead("select * from country where Display = 'Yes' group by countryID order by countryID");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $lang2_query = dbRead("select * from tbl_corp_data where CID = ".$rowlang['countryID']." group by langcode order by langcode");
   while($rowlang2 = mysql_fetch_assoc($lang2_query)) {

    $SQL = new dbCreateSQL();

    $SQL->add_table("tbl_corp_data");

    $SQL->add_item("position", $pos);
    $SQL->add_item("pageid", encode_text2($_REQUEST['pageid']));
    $SQL->add_item("langcode", encode_text2($rowlang2['langcode']));
    $SQL->add_item("CID", encode_text2($rowlang['countryID']));
    $SQL->add_item("data", encode_text2($_REQUEST[$rowlang['countrycode']]));
    //$SQL->add_item("data", encode_text2($_REQUEST[au]));

   dbWrite($SQL->get_sql_insert());
   }
  }
 }

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<title>Change Area</title>
</head>

<?if($_REQUEST[adddata]) {?>
<table width="620" border="1" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
   <tr>
    <td bgcolor="#FFFFFF" align="center"><font color = #FF0000>The new data was added in Position Number: <?= $pos ?>&nbsp;</font></td>
   </tr>
</table><br>
<?}?>
<form method="post" action="body.php?page=update_admin" name="adminadd">

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Add New Data</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Content</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Page:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
            $sql_query = dbRead("select * from tbl_corp_pages order by page");
            form_select('pageid',$sql_query,'page','pageid',$_REQUEST[pageid],'','','','fieldid');
           ?>
     </td>
  </tr>
<?
$lang_query = dbRead("select countrycode as Langcode, name from country where Display = 'Yes' group by countrycode order by countrycode");

 while($rowlang = mysql_fetch_assoc($lang_query)) {?>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b><?= $rowlang['name']?>:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><textarea name="<?= $rowlang['Langcode'] ?>" cols="45" rows="6" size="30" value="<?= $rowlang['Langcode'] ?>"><?= $rowlang['Langcode'] ?></textarea></td>
  </tr>
<? }
?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="adddata" value="1">

</form>

</body>

<?
die;
}

function spot() {

if($_REQUEST['searchdata'])  {

if($_REQUEST[dataupdate])  {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_corp_spot");

   $SQL->add_item("spot_header", encode_text2($_REQUEST['spot_header']));
   $SQL->add_item("spot_text", encode_text2($_REQUEST['spot_text']));
   $SQL->add_item("spot_link", encode_text2($_REQUEST['spot_link']));
   $SQL->add_item("spot_address", encode_text2($_REQUEST['spot_address']));

   $SQL->add_where("fieldid = '".$_REQUEST['fieldid']."'");

   dbWrite($SQL->get_sql_update());

}

if($_REQUEST[delete])  {

  dbWrite("delete from tbl_corp_spot where fieldid = '".$_REQUEST['fieldid']."'");

}
?>
<table border="0" width="620" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $sql_query = dbRead("select * from tbl_corp_spot where CID = ".$_REQUEST['CID']." order by spot_no, langcode");
  while($row = mysql_fetch_assoc($sql_query)) {
  ?>

   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b><?= $row['spot_no'] ?>:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><a href="body.php?page=update_corp&tab=tab3&edit2=1&fieldid=<?= $row['fieldid'] ?>&CID=<?= $_REQUEST['CID'] ?>" class="nav"><?= $row['spot_header'] ?>(<?= $row['langcode'] ?>)</a></td>
   </tr>
 <?}?>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
</form>
</body>
 <?
 die;
 }

if($_REQUEST['edit2'])  {
?>
<table border="0" width="550" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $sql_query = dbRead("select * from tbl_corp_spot where fieldid = '".$_REQUEST['fieldid']."'");
  $row = mysql_fetch_assoc($sql_query);
  ?>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Header:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="spot_header" size="50" value="<?= $row['spot_header'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Text:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><textarea name="spot_text" size="40" rows="6" value="<?= $row['spot_text'] ?>" cols="40"><?= $row['spot_text'] ?></textarea></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Link Text:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="spot_link" size="50" value="<?= $row['spot_link'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Link Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="spot_address" size="50" value="<?= $row['spot_address'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Delete Spotlight" name="delete" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update Spotlight" name="dataupdate" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>

<input type="hidden" name="fieldid" value="<?= $_REQUEST['fieldid'] ?>">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
<input type="hidden" name="searchdata" value="1">

 <?
 die;
 }

if(checkmodule("SuperUser")) {
 $lim = "";
} else {
 $lim = " where countryID = ".$_SESSION['User']['CID']."";
}
?>

<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$country_query = dbRead("select * from country $lim order by name");
            form_select('CID',$country_query,'name','countryID');
           ?>
    </td>
   </tr>

   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="search Data" name="datasearch" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="searchdata" value="1">

<?
}

function spotadd() {

 if($_REQUEST[adddata])  {

  $p_query = dbRead("select spot_no as max from tbl_corp_spot where CID = ".$_REQUEST['CID']." group by spot_no order by spot_no Desc");
  $p_row = mysql_fetch_assoc($p_query);
  $pos = $p_row['max']+1;

  $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_corp_spot");

   $SQL->add_item("CID", encode_text2($rowlang['CID']));
   $SQL->add_item("langcode", encode_text2($rowlang['langcode']));
   $SQL->add_item("spot_no", $pos);
   $SQL->add_item("spot_header", encode_text2($_REQUEST[$rowlang['langcode']."_header"]));
   $SQL->add_item("spot_text", encode_text2($_REQUEST[$rowlang['langcode']."_text"]));
   $SQL->add_item("spot_link", encode_text2($_REQUEST[$rowlang['langcode']."_link"]));
   $SQL->add_item("spot_address", encode_text2($_REQUEST[$rowlang['langcode']."_address"]));

   $clid = dbWrite($SQL->get_sql_insert(),etradebanc,true);

			if($_FILES[$rowlang['langcode'].'_image']['tmp_name']) {

			 $ext = strstr ($_FILES[$rowlang['langcode'].'_image']['name'], '.');

			 move_uploaded_file($_FILES[$rowlang['langcode'].'_image']['tmp_name'], "/home/etxint/public_html/home/images/spot/".$clid."".$ext."");
			 $source = "/home/etxint/public_html/home/images/spot/$clid$ext";
			 $dest = "/home/etxint/public_html/home/images/spot/thumb-$clid$ext";
			 //$dest2 = "/home/etxint/public_html/home/images/spot/thumb2-$clid$ext";
			 copy($source, $dest);
			 //copy($source, $dest2);
			 exec("convert -geometry 150 /home/etxint/public_html/home/images/spot/thumb-$clid$ext /home/etxint/public_html/home/images/spot/thumb-$clid$ext");
			 //exec("convert -geometry 150 /home/etxint/public_html/home/images/spot/thumb2-$clid$ext /home/etxint/public_html/home/images/spot/thumb2-$clid$ext");

             dbWrite("update tbl_corp_spot set spot_picture = 'thumb-".$clid.$ext."' where fieldid = ".$clid."");

			} else {

			 $clid="";

			}

  }
 }


if($_REQUEST['searchdata2'])  {

?>
<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="550" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="3" align="center" class="Heading"><b>Add New Data</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="Heading2"><b></b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
    <td align="center" valign="middle" class="Heading2"><b><?= $rowlang[langcode]?></b></td>
    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Header:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_header" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Text:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><textarea name="<?= $rowlang['langcode'] ?>_text" size="35" rows="8" cols="35"></textarea></td>
    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Link Text:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_link" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Link Address:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['langcode'] ?>_address" size="40" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>

    <?}?>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b>Image Upload:</b></td>
    <?
    $counter = 0;
    $lang_query = dbRead("select * from tbl_corp_data where CID = ".$_REQUEST['CID']." group by langcode order by langcode");
    while($rowlang = mysql_fetch_assoc($lang_query)) {
     $counter++;
     ?>
     <td bgcolor="#FFFFFF" width="35%"><input type="file" name="<?= $rowlang['langcode'] ?>_image" size="20"></td>
    <?}?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="15%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="85%" colspan="2"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="adddata" value="1">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
</form>

</body>

<?
die;
}

if(checkmodule("SuperUser")) {
 $lim = "";
} else {
 $lim = " where countryID = ".$_SESSION['User']['CID']."";
}
?>

<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$country_query = dbRead("select * from country $lim order by name");
            form_select('CID',$country_query,'name','countryID');
           ?>
    </td>
   </tr>

   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="search Data" name="datasearch" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="searchdata2" value="1">
<?
}

function FAQ() {

if($_REQUEST['searchdata'])  {

if($_REQUEST[dataupdate])  {

  $lang_query = dbRead("select faq_lan as langcode from tbl_faq where CID = ".$_REQUEST['CID']." group by faq_lan order by faq_lan","snyper");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_faq");

   $SQL->add_item("faq_question", encode_text2($_REQUEST[$rowlang['langcode']."_faq_question"]));
   $SQL->add_item("faq_answer", encode_text2($_REQUEST[$rowlang['langcode']."_faq_answer"]));

   $SQL->add_where("faq_no = '".$_REQUEST['faq_no']."'");
   $SQL->add_where("CID = '".$_REQUEST['CID']."'");
   $SQL->add_where("faq_lan = '".$rowlang['langcode']."'");

   dbWrite($SQL->get_sql_update(),"snyper");

  }
 }
?>
<table border="0" width="620" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  //$sql_query = dbRead("select * from tbl_lang_keywords where langcode = '".$_REQUEST['langcode']."' order by wordid");
  $sql_query = dbRead("select * from tbl_faq where CID = '".$_REQUEST['CID']."' order by faq_id","snyper");

  while($row = mysql_fetch_assoc($sql_query)) {
  ?>

   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b><?= $row['faq_no'] ?>:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><a href="body.php?page=update_corp&tab=tab5&edit2=1&faq_no=<?= $row['faq_no'] ?>&CID=<?= $_REQUEST['CID'] ?>" class="nav"><?= $row['faq_question'] ?></a></td>
   </tr>
 <?}?>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
</form>
</body>
 <?
 die;
 }

if($_REQUEST['edit2'])  {
?>
<table border="0" width="550" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $sql_query = dbRead("select * from tbl_faq where CID = ".$_REQUEST['CID']." and faq_no = '".$_REQUEST['faq_no']."'","snyper");
  while($row = mysql_fetch_array($sql_query)) {
  ?>
   <tr>
    <td align="center" valign="middle" class="Heading2" width="30%" colspan = "2"><b><?= $row['faq_lan'] ?></b></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Question:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="<?= $row['faq_lan'] ?>_faq_question" size="50" value="<?= $row['faq_question'] ?>"></td>
   </tr>
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Answer:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><textarea name="<?= $row['faq_lan'] ?>_faq_answer" size="40" rows="6" value="<?= $row['faq_answer'] ?>" cols="40"><?= $row['faq_answer'] ?></textarea></td>
   </tr>
  <?}?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Update FAQ" name="updateadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>

<input type="hidden" name="faq_no" value="<?= $_REQUEST['faq_no'] ?>">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
<input type="hidden" name="searchdata" value="1">
<input type="hidden" name="dataupdate" value="1">

 <?
 die;
 }

if($_REQUEST[dataupdate44])  {

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_faq");

   $SQL->add_item("faq_question", encode_text2($_REQUEST['faq_question']));
   $SQL->add_item("faq_answer", encode_text2($_REQUEST['faq_answer']));

   $SQL->add_where("faq_id = '".$_REQUEST['faq_id']."'");

   dbWrite($SQL->get_sql_update(),"snyper");

 }
//}

if(checkmodule("SuperUser")) {
 $lim = "";
} else {
 $lim = " where countryID = ".$_SESSION['User']['CID']."";
}
?>
<table border="0" cellspacing="0" cellpadding="1" width="620">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
   <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?
  			$country_query = dbRead("select * from country $lim order by name");
            form_select('CID',$country_query,'name','countryID');
           ?>
    </td>
   </tr>

   <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="search Data" name="datasearch" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
   </tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="searchdata" value="1">

<?
}

function FAQadd() {

 if($_REQUEST[adddata])  {

  if(checkmodule("SuperUser")) {
   $lim = "";
  } else {
   $lim = " and countryID = ".$_SESSION['User']['CID']."";
  }

  $p_query = dbRead("select faq_no as max from tbl_faq group by faq_no order by faq_no Desc","snyper");
  $p_row = mysql_fetch_assoc($p_query);
  $pos = $p_row['max']+1;

  $lang_query = dbRead("select * from country where Display = 'Yes' $lim group by countryID order by countryID");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $lang2_query = dbRead("select * from tbl_faq where CID = ".$rowlang['countryID']." group by faq_lan order by faq_lan","snyper");
   while($rowlang2 = mysql_fetch_assoc($lang2_query)) {

    $SQL = new dbCreateSQL();

    $SQL->add_table("tbl_faq");

    $SQL->add_item("faq_no", $pos);
    $SQL->add_item("CID", encode_text2($rowlang['countryID']));
    $SQL->add_item("faq_lan", encode_text2($rowlang2['faq_lan']));
    $SQL->add_item("faq_question", encode_text2($_REQUEST[$rowlang['countrycode']."q"]));
    $SQL->add_item("faq_answer", encode_text2($_REQUEST[$rowlang['countrycode']."a"]));

    dbWrite($SQL->get_sql_insert(),"snyper");
   }
  }
 }

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<title>Change Area</title>
</head>

<body onload="javascript:setFocus('areaadd','tradeq');">
<?if($_REQUEST[adddata]) {?>
<table width="550" border="1" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
   <tr>
    <td bgcolor="#FFFFFF" align="center"><font color = #FF0000>Please remember to go in and update the english version.</font></td>
   </tr>
</table><br>
<?}?>
<form method="post" action="body.php?page=update_admin" name="adminadd">

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="550" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="3" align="center" class="Heading"><b>Add New Data</b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="Heading2"><b></b></td>
    <td align="center" valign="middle" class="Heading2"><b>Question</b></td>
    <td align="center" valign="middle" class="Heading2"><b>Answer</b></td>
  </tr>
<?
if(checkmodule("SuperUser")) {
 $lim = "";
} else {
 $lim = " and countryID = ".$_SESSION['User']['CID']."";
}
$lang_query = dbRead("select * from country where Display = 'Yes' $lim group by countrycode order by name");

 while($rowlang = mysql_fetch_assoc($lang_query)) {?>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="15%"><b><?= $rowlang['name']?>:</b></td>
     <td bgcolor="#FFFFFF" width="35%"><input type="text" name="<?= $rowlang['countrycode'] ?>q" size="30" maxlength="255" value="<?= $rowlang['countryID'] ?>"></td>
     <td bgcolor="#FFFFFF" width="50%"><input type="text" name="<?= $rowlang['countrycode'] ?>a" size="50" value="<?= $rowlang['countryID'] ?>"></td>
  </tr>
<? }
?>
  <tr>
    <td align="right" valign="top" class="Heading2" width="15%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="85%" colspan="2"><input type="submit" value="Add Data" name="dataadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="adddata" value="1">

</form>

</body>

<?
die;
}

function log_changes($row,$request,$type) {

 $NIPageArray = array(
       'phpbb2mysql_data' => 'phpbb2mysql_data',
       'page' => 'page',
       'Client' => 'Client',
       'pageno' => 'pageno',
       'tab' => 'tab',
       'Update' => 'Update',
       'main' => 'main',
       'changemember' => 'changemember',
       'PHPSESSID' => 'PHPSESSID'
 );

  if($request != $row) {
   //if($key != $NIPageArray[$key]) {

     $logdata[$key] = array($row[$key],$value);

   //}
  }

 add_kpi($type,$row['memid'],$logdata);

}
