<?

if(!checkmodule("Downloads")) {

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
<html>
<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'body.php?page=dir&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

function catSelect(numberString,selectBox) {

	if(numberString != "none") {

		var catArray = numberString.split(",");
		var i;
		var x;

		boxObj = document.getElementById('catList');

		for( i = 0 ; i<boxObj.options.length; i++ ) {

			boxObj.options[i].selected = false;

			for ( x = 0 ; x<catArray.length; x++ ) {

				if( boxObj.options[i].value == catArray[x] ) {

					boxObj.options[i].selected = true;

				}

			}

		}

	} else {

		var i;

		boxObj = document.getElementById('catList');

		for( i = 0 ; i<boxObj.options.length; i++ ) {

			boxObj.options[i].selected = false;

		}

	}

}

function testing() {

 boxObj = document.getElementById('catList');

 boxObj.value[2].selected = true;

}
</script>
<script type="text/javascript"><!--
function dsp(loc){
   if(document.getElementById){
      var foc=loc.firstChild;
      foc=loc.firstChild.innerHTML?
         loc.firstChild:
         loc.firstChild.nextSibling;
      foc.innerHTML=foc.innerHTML=='+'?'-':'+';
      foc=loc.parentNode.nextSibling.style?
         loc.parentNode.nextSibling:
         loc.parentNode.nextSibling.nextSibling;
      foc.style.display=foc.style.display=='block'?'none':'block';}}

if(!document.getElementById)
   document.write('<style type="text/css"><!--\n'+
      '.dspcont{display:block;}\n'+
      '//--></style>');
//--></script>
<style type="text/css"><!--
.save{
   behavior:url(#default#savehistory);}
a.dsphead{
   text-decoration:none;
   margin-left:1.5em;}
a.dsphead span.dspchar{
   font-family:monospace;
   font-weight:normal;
   margin-top:0;
   margin-bottom:0;}
.dspcont{
   display:none;
   padding-left: 4em;
   }
.dspcont2{
   display:none;
   padding-left:4em;
   padding-top:10;
   padding-bottom:20;}

//--></style>
</head>
<BODY BGCOLOR=#FFFFFF text="#000000" link="#0000CC" vlink="#5A2D27" alink="#FF6600" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

<?

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

$Cquery=dbRead("select * from country where countryID = '$GET_CID'");
$Crow = mysql_fetch_assoc($Cquery);
$_SESSION['Directory']['selected'] = 0;
?>

<form method="GET" action="body.php" name="am">
<input type="hidden" name="page" value="directory_pre_send">
<input type="hidden" name="countryid" value="<?= $GET_CID ?>">
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
 <tr>
  <td height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?

		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}

?>
   </select>&nbsp;</td>
  </tr>
 </table>
</td>
</tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">

        <p>&nbsp;</td>
	    <td bgcolor="#FFFFFF">
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr>
<tr>
<td bgcolor="#FFFFFF" colspan="2">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td width="100%">
	 <TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
        <TR>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_01.jpg" ALT=""></TD>
          <TD background="images/divid_02.jpg"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
          QuickFind</strong></font></TD>
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
</td>
</tr>
	<tr>
	    <td bgcolor="#FFFFFF"><?= get_word("140") ?>.<br>
        <br><?= get_page_data("2") ?><br>
          <b><?= get_word("24") ?>:</b><br>
          <select size="6" name="area[]" multiple>
          <?
           //$query = dbRead("select FieldID, place from area where CID='$GET_CID' order by place");
           $query = dbRead("select tbl_area_physical.FieldID, AreaName from tbl_area_physical, tbl_area_regional, tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='$GET_CID' order by AreaName");
           while($row = mysql_fetch_assoc($query)) {
            ?>
            <option value="<?= $row[FieldID] ?>"><?= $row[AreaName] ?></option>
            <?
           }
          ?>
          </select><br><br>
          <b><?= get_word("78") ?>:</b><br>
          <select size="1" name="disarea"><option value=""><?= get_word("161") ?></option>
          <?
           //$query2 = dbRead("select disarea from area where CID='$GET_CID' group by disarea order by place");
           $query2 = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='$GET_CID' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select><br><? if($_SESSION['User']['AreasAllowed'] == "all") { ?><b>PostCodes:</b><? } ?><br><? if($_SESSION['User']['AreasAllowed'] == "all") { ?><input type="text" name="PostCodes" size="20"><? } ?>
		  <br>
		  <b>Exclude members not contacted in last:</b>
		  <br>
	      <select name="month">
	        <option <? if($d == "01") { echo "selected "; } ?>value="">Select</option>
	        <option <? if($d == "01") { echo "selected "; } ?>value="1">1</option>
	        <option <? if($d == "02") { echo "selected "; } ?>value="2">2</option>
	        <option <? if($d == "03") { echo "selected "; } ?>value="3">3</option>
	        <option <? if($d == "04") { echo "selected "; } ?>value="4">4</option>
	        <option <? if($d == "05") { echo "selected "; } ?>value="5">5</option>
	        <option <? if($d == "06") { echo "selected "; } ?>value="6">6</option>
	        <option <? if($d == "07") { echo "selected "; } ?>value="7">7</option>
	        <option <? if($d == "08") { echo "selected "; } ?>value="8">8</option>
	        <option <? if($d == "09") { echo "selected "; } ?>value="9">9</option>
	        <option <? if($d == "10") { echo "selected "; } ?>value="0">10</option>
	        <option <? if($d == "11") { echo "selected "; } ?>value="1">11</option>
	        <option <? if($d == "12") { echo "selected "; } ?>value="2">12</option>
	      </select> months
		  <br>
		  <?if($_SESSION['Country']['club'] == 1) {?>
		  Limit Selection: <input type="checkbox" name="select" value="1">
		  <br>
		  Exclude state/nat: <input type="checkbox" name="nat" value="1">
		  <br>
		  50% Club Members: <input type="checkbox" name="fifty" value="1">
		  <br>
		  Gold Club Members: <input type="checkbox" name="gold" value="1">
		  <br>
		  <?}?>
          <input type="submit" name="top"  value="<?= get_page_data("3") ?>"> <input type="submit" name="topright" value="<?= get_page_data("4") ?>"></td>

	    <td bgcolor="#FFFFFF">
	    <select name="state[]" size="10" multiple>
          <?
           //$query4 = dbRead("select state from area where CID='$GET_CID' group by state order by state");
           $query4 = dbRead("select FieldID, StateName from tbl_area_states where CID='$GET_CID' order by StateName");
           while($row4 = mysql_fetch_assoc($query4)) {
            ?>
            <option value="<?= $row4[FieldID] ?>"><?= $row4[StateName] ?></option>
            <?
           }
          ?>
	    </select><select size="10" id="catList" name="category[]" multiple>

	     <?
	       if($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') {
	          $cat = " order by engcategory";
	          $sel = " engcategory as category, catid as catid ";
	       } else {
	          $cat = " order by category";
	    	  $sel = " category, catid ";
	       }

           $query3 = dbRead("select $sel from categories where CID='$GET_CID'$cat");
           while($row3 = mysql_fetch_assoc($query3)) {
            ?>
            <option value="<?= $row3[catid] ?>"><?= $row3[category] ?></option>
            <?
           }
	     ?>
	    </select>
          <br>
          <?if($_SESSION['User']['emcus'] == 1) {?>Save Categories Selected as <input type="text" name="temp_name" size="20">
          <br><?}?>
	    <br>Saved Directory Templates:
	    <select size="1" id="TempList" name="TempList" onchange="javascript:catSelect(document.am.TempList.value, this);"><option value="none">Deselect All</option>
          <?
           $query2 = dbRead("select * from tbl_directory_temp where CID='$GET_CID' order by TempName");
           while($row2 = mysql_fetch_assoc($query2)) {

            $dataValue = comma_seperate(unserialize($row2['TempCats']));

            ?>
            <option value="<?= $dataValue ?>"><?= $row2[TempName] ?></option>
            <?
           }
          ?>
          </select>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2"><?= get_page_data("5") ?>.</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">

        <p>&nbsp;</td>
	    <td bgcolor="#FFFFFF">
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF"><?= get_word("140") ?>.<br>
        <br><br>
          <b><?= get_word("24") ?>:</b><br>
          <select size="6" name="area2[]" multiple>
          <?
           //$query = dbRead("select FieldID, place from area where CID='$GET_CID' order by place");
           $query = dbRead("select tbl_area_physical.FieldID, AreaName from tbl_area_physical, tbl_area_regional, tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='$GET_CID' order by AreaName");
           while($row = mysql_fetch_assoc($query)) {
            ?>
            <option value="<?= $row[FieldID] ?>"><?= $row[AreaName] ?></option>
            <?
           }
          ?>
          </select><br><br>
          <b><?= get_word("78") ?>:</b><br>
          <select size="1" name="disarea2"><option value="%"><?= get_word("161") ?></option>
          <?
           //$query2 = dbRead("select disarea from area where CID='$GET_CID' group by disarea order by place");
           $query2 = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='$GET_CID' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select><br><b><?= get_word("17") ?>:</b><br>
          <select name="state2"><option value=""><?= get_word("161") ?></option>
          <?
           //$query4 = dbRead("select state from area where CID='$GET_CID' group by state order by state");
           $query4 = dbRead("select FieldID, StateName from tbl_area_states where CID='$GET_CID' order by StateName");
           while($row4 = mysql_fetch_assoc($query4)) {
            ?>
            <option value="<?= $row4[FieldID] ?>"><?= $row4[StateName] ?></option>
            <?
           }
          ?>
	      </select>
          <br>
          <input type = "radio" name = "type" id = "1" value = "1" CHECKED><?= get_word("164") ?><br>
          <input type = "radio" name = "type" id = "2" value = "2"><?= get_word("165") ?><br>
          <input type = "radio" name = "type" id = "3" value = "3"><?= get_word("166") ?><br>
          <input type = "radio" name = "type" id = "4" value = "4"><?= get_word("167") ?><br>
          <input type = "radio" name = "type" id = "5" value = "5"><?= get_word("168") ?><br>
          <input type = "radio" name = "type" id = "6" value = "6"><?= get_word("204") ?><br><br>
		  <input type="Submit" name="bottom"  value="<?= get_page_data("6") ?>">
<br><br>
</td>
	    <td bgcolor="#FFFFFF">
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>

<?
function display_cat_main() {

global $bgcolor;
$timestamp_now = date("YmdHis");

$query = dbRead("SELECT * FROM tbl_cat_main where CID = ".$_SESSION['Country']['countryID']." order by tbl_cat_main.C_Name");
while($row = mysql_fetch_assoc($query)) {

 $data_structure[] = $row[C_Name];
 $cat_count[$row[C_Name]] = $row[subCount];
 $data_structure_id[] = $row[FieldID];

}


$Category_Count = sizeof($data_structure);
$Category_Count_Half = ceil($Category_Count/2);

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
   <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
<div class="save">
<div><a href="javascript:void('<?= $data_structure_id[$i]; ?>')" class="dsphead" onclick="dsp(this)"><span class="dspchar">+</span><b> <?= $data_structure[$i] ?> </b></a></div>
   <div class="dspcont">
      <div class="dsphead2">
		<?display_cat_view($data_structure_id[$i]);?>
	  </div>
</div>
</div>
   </td>
   <td><? if($data_structure[$i+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
<div class="save">
<div><a href="javascript:void('<?= $data_structure_id[$i+$Category_Count_Half]; ?>')" class="dsphead" onclick="dsp(this)"><span class="dspchar">+</span><b> <?= $data_structure[$i+$Category_Count_Half] ?> </b></a></div>
   <div class="dspcont">
      <div class="dsphead2">
		<?display_cat_view($data_structure_id[$i+$Category_Count_Half]);?>
	  </div>
</div>
</div>
   <? } ?>
   </td>
  </tr>
  <?
  $Counter++;
  }
 ?>
</table>
<?


}

function display_cat_view($categoryid,$quick = false) {

global $bgcolor;

$timestamp_now = date("YmdHis");

 $query = dbRead("select categories.*, count(mem_categories.memid) as mem_Count from tbl_cat_link, categories, mem_categories, members where mem_categories.category = categories.catid and mem_categories.memid = members.memid and tbl_cat_link.Sub_ID = categories.catid and tbl_cat_link.Main_ID = ".$categoryid." and bdriven='N' and t_unlist = 0 and status != 6 group by tbl_cat_link.Sub_ID order by categories.category asc");
 //$query = dbRead("select categories.* from tbl_cat_link, categories where tbl_cat_link.Sub_ID = categories.catid and tbl_cat_link.Main_ID = ".$categoryid." order by categories.category asc");
 //$query = dbRead("select categories.*, count(mem_categories.memid) as mem_Count from tbl_cat_link, categories left outer join mem_categories on (mem_categories.category = categories.catid) where tbl_cat_link.Sub_ID = categories.catid and tbl_cat_link.Main_ID = ".$categoryid." group by tbl_cat_link.Sub_ID order by categories.category asc");

while($row = mysql_fetch_assoc($query)) {

 $data_structure[] = $row[category];
 $cat_count[$row[category]] = $row[mem_Count];
 $data_structure_id[] = $row[catid];

}

$Category_Count = sizeof($data_structure);
$Category_Count_Half = ceil($Category_Count/3);

?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
 <?
  $Counter = 0;
  for($i = 0;$i < $Category_Count_Half;$i++) {
  ?>
  <tr valign="top" bgcolor="<?= $bgcolor ?>">
   <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="checkbox" name="category[]" value="<?= $data_structure_id[$i] ?>">&nbsp;<?= $data_structure[$i] ?>&nbsp;<font size="1" color="#333333">(<?= number_format($cat_count[$data_structure[$i]]) ?>)</font></a><br></font></td>
   <td><? if($data_structure[$i+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="checkbox" name="category[]" value="<?= $data_structure_id[$i+$Category_Count_Half] ?>">&nbsp;<?= $data_structure[$i+$Category_Count_Half] ?>&nbsp;<font size="1" color="#333333">(<?= number_format($cat_count[$data_structure[$i+$Category_Count_Half]]) ?>)</font></a></font><? } else { ?> &nbsp;<? } ?></td>
   <td><? if($data_structure[$i+$Category_Count_Half+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="checkbox" name="category[]" value="<?= $data_structure_id[$i+$Category_Count_Half+$Category_Count_Half] ?>">&nbsp;<?= $data_structure[$i+$Category_Count_Half+$Category_Count_Half] ?>&nbsp;<font size="1" color="#333333">(<?= number_format($cat_count[$data_structure[$i+$Category_Count_Half+$Category_Count_Half]]) ?>)</font></a></font><? } else { ?> &nbsp;<? } ?></td>
  </tr>
  <?
  $Counter++;
  }
 ?>
</table>
<?
}
?>