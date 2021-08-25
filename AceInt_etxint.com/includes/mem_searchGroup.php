<HTML>
<HEAD>
<TITLE></TITLE>
<META HTTP-EQUIV="Content-Language" CONTENT="en-us">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<script language="javascript" src="js/sleight.js"></script>
<script>
function ChangeRegional(list) {
 var url = 'https://admin.etxint.com/body.php?page=mem_search&memsearch=1&categoryid=1&catid=1&disareaid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}
</script>
</HEAD>
<BODY BGCOLOR=#FFFFFF text="#000000" link="#0000CC" vlink="#5A2D27" alink="#FF6600" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr valign="top">
    <td width="100%">
	 <TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
        <TR>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_01.jpg" ALT=""></TD>
          <TD background="images/divid_02.jpg"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
          Category_Groups</strong></font></TD>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_04.jpg" ALT=""></TD>
          <TD width="100%" background="images/divid_05.jpg"><IMG SRC="images/divid_05.jpg" WIDTH=10 HEIGHT=28 ALT=""></TD>
        </TR>
      </TABLE>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td width="6"></td>
          <td bgcolor="#CCCCCC"><? display_cat_main(); ?></td>
        </tr>
      </table>
    </td>
    <td><font size="2" face="Arial, Helvetica, sans-serif"><strong>
      </strong></font>
    </td>
  </tr>
</table>
<?if($_REQUEST['categoryid']) {?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr valign="top">
    <td width="100%">
	  <TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
        <TR>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_01.jpg" ALT=""></TD>
          <TD background="images/divid_02.jpg" nowrap><font size="2" face="Arial, Helvetica, sans-serif"><strong><?= cat_display($_REQUEST[categoryid],$_REQUEST['q']); ?></strong></font></TD>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_04.jpg" ALT=""></TD>
          <TD width="100%" background="images/divid_05.jpg"><IMG SRC="images/divid_05.jpg" WIDTH=10 HEIGHT=28 ALT=""></TD>
        </TR>
      </TABLE>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td width="6"></td>
          <td bgcolor="#CCCCCC"><? display_cat_view($_REQUEST[categoryid], $_REQUEST['q']); ?></td>
        </tr>
      </table>
    </td>
    <td><font size="2" face="Arial, Helvetica, sans-serif"><strong>
      </strong></font>
    </td>
  </tr>
</table>
<?}?>
</BODY>
</HTML>

<?

function display_cat_main() {

$timestamp_now = date("YmdHis");

$query = dbRead("SELECT * FROM tbl_cat_main where CID = ".$_SESSION['Country']['countryID']." order by tbl_cat_main.C_Name ");
while($row = mysql_fetch_assoc($query)) {

 $data_structure[] = $row['C_Name'];
 $cat_count[$row['C_Name']] = (isset($row['subCount'])) ? $row['subCount'] : 0;
 $data_structure_id[] = $row['FieldID'];

}

$Category_Count = sizeof($data_structure);
$Category_Count_Half = ceil($Category_Count/4);

?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
 <?
  $Counter = 0;
  for($i = 0;$i < $Category_Count_Half;$i++) {

   $cfg_bgcolor_one = "#DDDDDD";
   $cfg_bgcolor_two = "#EEEEEE";

   $bgcolor = $cfg_bgcolor_one;

   $Counter % 2 ? 0: $bgcolor = $cfg_bgcolor_two;

  ?>
  <tr valign="top" bgcolor="<?= $bgcolor ?>">
   <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?page=mem_search&categoryid=<?= $data_structure_id[$i] ?>&memsearch=1&top=1"><?= $data_structure[$i] ?>&nbsp;</a><br></font></td>
   <td><? if($data_structure[$i+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?page=mem_search&categoryid=<?= $data_structure_id[$i+$Category_Count_Half] ?>&memsearch=1&top=1"><?= $data_structure[$i+$Category_Count_Half] ?>&nbsp;</a></font><? } else { ?> &nbsp;<? } ?></td>
   <td><? if($data_structure[$i+($Category_Count_Half*2)]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?page=mem_search&categoryid=<?= $data_structure_id[$i+($Category_Count_Half*2)] ?>&memsearch=1&top=1"><?= $data_structure[$i+($Category_Count_Half*2)] ?>&nbsp;</a></font><? } else { ?> &nbsp;<? } ?></td>
   <td><? if($data_structure[$i+($Category_Count_Half*3)]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?page=mem_search&categoryid=<?= $data_structure_id[$i+($Category_Count_Half*3)] ?>&memsearch=1&top=1"><?= $data_structure[$i+($Category_Count_Half*3)] ?>&nbsp;</a></font><? } else { ?> &nbsp;<? } ?></td>
  </tr>
  <?
  $Counter++;
  }
 ?>
</table>
<?

}

function cat_display($categoryid,$quick = false) {

 global $REQUEST;

 if($quick == 2) {
  $display = '<a href="body.php?PageID=78">Categories Groups</a> > FASTsearch';
 } elseif($quick == 3) {
  $display = '<a href="body.php?PageID=78">Categories Groups</a> > AdvancedSearch';
 } elseif($quick) {
  $display = '<a href="body.php?PageID=78">Categories Groups</a> > QuickFind';
 } else {
  $query = dbRead("select tbl_cat_main.* from tbl_cat_main where tbl_cat_main.FieldID = '$categoryid'");
  $row = mysql_fetch_assoc($query);

  $display = $row[C_Name];
 }
 return $display;

}

function display_cat_view($categoryid,$quick = false) {

$timestamp_now = date("YmdHis");

//$query = dbRead("select tbl_auction_sub_categories.*, count(tbl_auction_auctions.id) as Auction_Count from tbl_auction_sub_categories left outer join tbl_auction_auctions on (tbl_auction_auctions.category = tbl_auction_sub_categories.cat_id and tbl_auction_auctions.ends > '$timestamp_now' and tbl_auction_auctions.Display = 'Y') where tbl_auction_sub_categories.parent_id = '$categoryid' group by tbl_auction_sub_categories.cat_id order by tbl_auction_sub_categories.cat_name asc");
 if($quick == 2) {
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE">
 <tr>
  <form method="POST" action="body.php?PageID=79">
  <td align="center">
  <input name="q" type="hidden" value="2">
  <input name="data" type="text" size="20">
  <input type="submit" name="Submit" value="GO">
  <br><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Search our Members fast!</font>
  </td>
 </form>
 </tr>
</table>
<?
} elseif($quick == 3) {

  display_adv_search2();

} elseif($quick) {

$queryCat = dbRead("SELECT * FROM mem_categories where category != 0 and memid = ".$_SESSION['Member']['memid']."");
$count = 1;
while($rowCat = mysql_fetch_array($queryCat)) {
  if($count > 1) {
   $listCat.= ",".$rowCat['category'];
  } else {
  $listCat.= $rowCat['category'];
  }
  $count++;
}
$query = dbRead("SELECT categories.*, count(mem_categories.memid) as mem_Count FROM categories, tbl_cat_providers, mem_categories where tbl_cat_providers.providerID = categories.catid and tbl_cat_providers.providerID = mem_categories.category and tbl_cat_providers.catID in (".$listCat.") group by tbl_cat_providers.providerID order by categories.category");
} else {
 $query = dbRead("select categories.*, count(mem_categories.memid) as mem_Count from tbl_cat_link, categories left outer join mem_categories on (mem_categories.category = categories.catid) where tbl_cat_link.Sub_ID = categories.catid and tbl_cat_link.Main_ID = ".$categoryid." group by tbl_cat_link.Sub_ID order by categories.category asc");
}
while($row = mysql_fetch_assoc($query)) {

 $data_structure[] = $row[category];
 $cat_count[$row[category]] = $row[mem_Count];
 $data_structure_id[] = $row[catid];

}

$Category_Count = sizeof($data_structure);
$Category_Count_Half = ceil($Category_Count/3);

?>
<form method="post" action="body.php?page=mem_search" name="frm">
<input type="hidden" name="categoryid" value="<?= $_REQUEST['categoryid'] ?>">
<input type="hidden" name="catid" value="<?= $_REQUEST['catid'] ?>">
<input type="hidden" name="memsearch" value="1">
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
 <?
  $Counter = 0;
  for($i = 0;$i < $Category_Count_Half;$i++) {

   $cfg_bgcolor_one = "#DDDDDD";
   $cfg_bgcolor_two = "#EEEEEE";

   $bgcolor = $cfg_bgcolor_one;

   $Counter % 2 ? 0: $bgcolor = $cfg_bgcolor_two;

  ?>
  <tr valign="top" bgcolor="<?= $bgcolor ?>">
   <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="checkbox" name="catid[]" value="<?= $data_structure_id[$i] ?>" <?if($_REQUEST['catid'] == $data_structure_id[$i]) {?>checked<?}?>>&nbsp;<?= $data_structure[$i] ?>&nbsp;<font color="#333333">(<?= number_format($cat_count[$data_structure[$i]]) ?>)</font><br></font></td>
   <td><? if($data_structure[$i+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="checkbox" name="catid[]" value="<?= $data_structure_id[$i+$Category_Count_Half] ?>" <?if($_REQUEST['catid'] == $data_structure_id[$i+$Category_Count_Half]) {?>checked<?}?>>&nbsp;<?= $data_structure[$i+$Category_Count_Half] ?>&nbsp;<font color="#333333">(<?= number_format($cat_count[$data_structure[$i+$Category_Count_Half]]) ?>)</font></font><? } else { ?> &nbsp;<? } ?></td>
   <td><? if($data_structure[$i+$Category_Count_Half+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="checkbox" name="catid[]" value="<?= $data_structure_id[$i+$Category_Count_Half+$Category_Count_Half] ?>" <?if($_REQUEST['catid'] == $data_structure_id[$i+$Category_Count_Half+$Category_Count_Half]) {?>checked<?}?>>&nbsp;<?= $data_structure[$i+$Category_Count_Half+$Category_Count_Half] ?>&nbsp;<font color="#333333">(<?= number_format($cat_count[$data_structure[$i+$Category_Count_Half+$Category_Count_Half]]) ?>)</font></font><? } else { ?> &nbsp;<? } ?></td>
  </tr>
  <?
  $Counter++;
  }
 ?>
</table>
<?
display_adv_search2();
}

function display_adv_search2() {

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} elseif($_POST[countryid]) {
 $GET_CID = $_POST[countryid];
} else {
 $GET_CID = $_SESSION['Country']['countryID'];
}
?>

<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE">
 <tr>
      <td align="center" width="45%"><b><?= get_word("17") ?>:<b/> <select name="stateid"><option selected value=""><?= get_word("162") ?></option>
<?
		$dbgetarea=dbRead("select FieldID, StateName from tbl_area_states where CID like '".addslashes($GET_CID)."' Order by StateName ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['FieldID'] == $_REQUEST['stateid']) { echo "selected "; } ?>value="<?= $row['FieldID'] ?>"><?= $row['StateName'] ?></option>
			<?
		}

?>
	  </select>&nbsp;</td>

   <td align="center" width="10%"><b> OR </b></td>
   <td align="center" width="45%"><b><?= get_word("78") ?>:</b> <select name="disareaid" id="disareaid"><option selected value=""><?= get_word("161") ?></option>
  <?
	$dbgetarea = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_physical, members, mem_categories where tbl_area_regional.FieldID = tbl_area_physical.RegionalID and tbl_area_physical.FieldID = members.area and members.memid = mem_categories.memid and tbl_area_regional.CID = '$GET_CID' GROUP BY tbl_area_regional.RegionalName HAVING Sum(mem_categories.category)>0 Order by RegionalName asc");
	while(list($FieldID, $RegionalName) = mysql_fetch_row($dbgetarea)) {
    ?>
	<option <? if ($FieldID == $_REQUEST['disareaid']) { echo "selected ";} ?>value="<?= $FieldID ?>"><?= $RegionalName ?></option>
    <?
	}

	$counter = mysql_num_rows($dbgetdataout);

  ?> </select>
   </td>
  </tr>

  <tr>
  <td align="center" colspan=3>
  <input type="submit" name="Submit" value="Search">
  </td>
  </tr>
 </form>
 </tr>
</table>

<?

}
?>
