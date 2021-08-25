<form enctype="multipart/form-data" method="POST" action="body.php?page=update_member&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

if(checkmodule("SuperUser")) {
  $tabarray = array('Page Data Update','Page Data Add');
} else {
  $tabarray = array('Page Data Update');
}
// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  data();

} elseif($_GET[tab] == "tab2") {

  dataadd();

}

?>

</form>

<?

function data() {

 if($_REQUEST['searchdata'])  {
?>
<form method="post" action="body.php?page=update_member" name="changearea">
<table border="0" width="620" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
  <?
  $eng_query = dbRead("select * from tbl_members_data where CID = 1 and langcode = 'en' and pageid = ".$_REQUEST['FieldID']." order by position");
  $EngRow = mysql_fetch_assoc($eng_query);

  $sql_query = dbRead("select * from tbl_members_data where CID = '".$_REQUEST['CID']."' and langcode = '".$_REQUEST['langcode']."' and pageid = ".$_REQUEST['FieldID']." order by position");
  while($row = mysql_fetch_assoc($sql_query)) {

    if($_REQUEST['CID'] == 12 && $_REQUEST['langcode'] == 'ro') {
     $eng_query = dbRead("select * from tbl_members_data where CID = 12 and langcode = 'hu' and pageid = ".$_REQUEST['FieldID']." and position = ".$row['position']."");
     $EngRow = mysql_fetch_assoc($eng_query);
    } else {
     $eng_query = dbRead("select * from tbl_members_data where CID = 1 and langcode = 'en' and pageid = ".$_REQUEST['FieldID']." and position = ".$row['position']."");
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
<input type="hidden" name="FieldID" value="<?= $_REQUEST['FieldID'] ?>">
<input type="hidden" name="CID" value="<?= $_REQUEST['CID'] ?>">
<input type="hidden" name="langcode" value="<?= $_REQUEST['langcode'] ?>">
<input type="hidden" name="dataupdate" value="1">

</form>
</body>
 <?
 die;
 }

if($_REQUEST[dataupdate])  {

  $lang_query = dbRead("select position from tbl_members_data where pageid = '".$_REQUEST['FieldID']."' group by position order by position");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $Wlang_query = dbRead("select * from tbl_members_data where pageid = '".$_REQUEST['FieldID']."' and position = '".$rowlang['position']."' and langcode = '".$_REQUEST['langcode']."' and CID = '".$_REQUEST['CID']."'");
   $Wrowlang = mysql_fetch_assoc($Wlang_query);
   if(encode_text2($_REQUEST[$rowlang['position']]) != $Wrowlang['data']) {
     $logdata[$rowlang['position']] = array($Wrowlang['data'],encode_text2($_REQUEST[$rowlang['position']]));
   }

   $SQL = new dbCreateSQL();

   $SQL->add_table("tbl_members_data");

   //$SQL->add_item("position", encode_text2($rowlang['position']));
   //$SQL->add_item("pageid", encode_text2($_REQUEST['pageid']));
   //$SQL->add_item("langcode", encode_text2($_REQUEST['Langcode']));
   $SQL->add_item("data", encode_text2($_REQUEST[$rowlang['position']]));

   $SQL->add_where("pageid = '".$_REQUEST['FieldID']."'");
   $SQL->add_where("position = '".$rowlang['position']."'");
   $SQL->add_where("langcode = '".$_REQUEST['langcode']."'");
   $SQL->add_where("CID = '".$_REQUEST['CID']."'");

   dbWrite($SQL->get_sql_update());

  }
  add_kpi2(1,$_REQUEST['FieldID'],$_REQUEST['langcode'],$_REQUEST['CID'],$logdata);

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
            $sql_query = dbRead("select * from tbl_members_pages, tbl_members_data where tbl_members_pages.FieldID = tbl_members_data.pageid group by tbl_members_pages.PageName order by tbl_members_pages.PageName");
            form_select('FieldID',$sql_query,'PageName','FieldID','','','','','FieldID');
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

  $p_query = dbRead("select position as max from tbl_members_data where pageid = '".$_REQUEST['FieldID']."' group by position order by position Desc");
  $p_row = mysql_fetch_assoc($p_query);
  $pos = $p_row['max']+1;

  //$lang_query = dbRead("select Langcode as Langcode, countrycode, countryID from country where Display = 'Yes' group by countrycode order by countrycode");
  $lang_query = dbRead("select * from country where Display = 'Yes' group by countryID order by countryID");
  while($rowlang = mysql_fetch_assoc($lang_query)) {

   $lang2_query = dbRead("select * from tbl_members_data where CID = ".$rowlang['countryID']." group by langcode order by langcode");
   while($rowlang2 = mysql_fetch_assoc($lang2_query)) {

    $SQL = new dbCreateSQL();

    $SQL->add_table("tbl_members_data");

    $SQL->add_item("position", $pos);
    $SQL->add_item("pageid", encode_text2($_REQUEST['FieldID']));
    $SQL->add_item("langcode", encode_text2($rowlang2['langcode']));
    $SQL->add_item("CID", encode_text2($rowlang['countryID']));
    $SQL->add_item("data", encode_text2($_REQUEST[$rowlang['countrycode']]));
    //$SQL->add_item("data", encode_text2($_REQUEST[au]));

   dbWrite($SQL->get_sql_insert());

   }
  }
 }
   echo "hello";
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
<form method="post" action="body.php?page=update_member" name="adminadd">

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
            $sql_query = dbRead("select * from tbl_members_pages order by PageName");
            form_select('FieldID',$sql_query,'PageName','FieldID',$_REQUEST[FieldID],'','','','FieldID');
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
<?}
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
