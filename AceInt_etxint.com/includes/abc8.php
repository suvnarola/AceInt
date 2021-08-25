<?
 include("/home/etxint/admin.etxint.com/includes/global.php");
?>
<HTML>
<HEAD>
<? if($_GET[lan] == "ml") {  ?><TITLE>E Planet Trade - Home</TITLE><?    
} else {	?><TITLE>E Banc Trade - Home</TITLE><? } ?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<SCRIPT LANGUAGE="javascript" SRC="/scripts/PopBox.js">
</SCRIPT>
<body>

<form method="POST" action="abc8.php" name="FAC">
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
		<td class="Heading2" width="100" align="right"><b><?= get_word("50") ?>:</b></td>
		<td bgcolor="#FFFFFF" align="left"><textarea name="memid" rows="10" cols="20"></textarea></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="changefacility"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changefacility" value="1">

</form>

</body>
</html>
<?


if($_REQUEST[memid])  { 
 //$query = dbRead("select area.disarea as disarea, tbl_area_states.FieldID as FieldID, area.CID as CID from area, tbl_area_states where (area.state = tbl_area_states.statename) and area.CID = 8 group by disarea order by area.cid,disarea");
 //$query = dbRead("select area.FieldID as FieldID, tbl_area_disarea.fieldid as regionalid from tbl_area_disarea, area where (area.disarea = tbl_area_disarea.dis_area)");
 //$query = dbRead("select area.FieldID as FieldID, area.place as place, RegionalID as RegionalID, CID as CID from area order by FieldID");
 //$query = dbRead("select area.disarea as place, tbl_area_regional.FieldID as RegionalID, tbl_area_regional.CID as CID from area, tbl_area_regional where (area.disarea = tbl_area_regional.RegionalName) and area.CID = 8 group by disarea order by disarea");
 //$query = dbRead("select area.FieldID as FieldID, tbl_area_physical.FieldID as phyarea from area, tbl_area_physical where (area.disarea = tbl_area_physical.AreaName) and area.CID = 8");
 //$query = dbRead("select * from  categories where CID = 1");
 //$query = dbRead("select * from  area where CID = 8"); 
 //$query = dbRead("select tbl_area_physical.FieldID as FieldID, tbl_area_physical.AreaName as place, CID as CID from tbl_area_physical where CID = 15");
 //$query = dbRead("select tbl_area_regional.FieldID as FieldID, tbl_area_regional.RegionalName as place, CID as CID from tbl_area_regional where CID = 16");

    //dbWrite("update tbl_area_states set StateName = '".addslashes(encode_text2($_REQUEST['memid']))."' where FieldID=172");
 dbWrite("update services set planRules = '".addslashes(encode_text2($_REQUEST['memid']))."' where ID=4","ebanc_services");


 $cc =0;
 #loop around
 //while($row = mysql_fetch_assoc($query)) {

   //dbWrite("insert into tbl_proc_code (Proc_Code,Proc_Title,CID) values ('Admin','".addslashes(encode_text2($_REQUEST['memid']))."','8')");

    //dbWrite("insert into area (place,PhysicalID,CID) values ('".addslashes(encode_text2($row[place]))."','".$row[FieldID]."','".$row[CID]."')");

    //dbWrite("insert into tbl_area_states (StateName,CID) values ('".addslashes(encode_text2($_REQUEST[memid]))."','15')");
    //dbWrite("insert into tbl_area_regional (RegionalName,StateID,CID) values ('".addslashes(encode_text2($row[disarea]))."','".$row[FieldID]."','".$row[CID]."')");
    //dbWrite("update area set regional_id = ".$row['regionalid']." where FieldID=".$row['FieldID']."");
    //dbWrite("insert into tbl_area_physical (AreaName,RegionalID,CID) values ('".addslashes(encode_text2($row[place]))."','".$row[FieldID]."','".$row[CID]."')");
    //dbWrite("insert into tbl_area_physical (AreaName,RegionalID,CID) values ('".addslashes(encode_text2($row[place]))."','".$row[RegionalID]."','".$row[CID]."')");
    //dbWrite("update area set PhysicalID = ".$row['phyarea']." where FieldID=".$row['FieldID']."");
    //dbWrite("insert into categories (category,display_drop,cont,rest_acco,rest_supp,tourist,gene_busi,wed,CID) values ('".addslashes(encode_text2($row[category]))."','".addslashes(encode_text2($row[display_drop]))."', '".addslashes(encode_text2($row[cont]))."','".addslashes(encode_text2($row[rest_acco]))."','".addslashes(encode_text2($row[rest_supp]))."','".addslashes(encode_text2($row[tourist]))."','".addslashes(encode_text2($row[gene_busi]))."','".addslashes(encode_text2($row[wed]))."','14')");
  //if($row['tradeq'])  {
    //dbWrite("update area set place = '".addslashes($row['tradeq'])."' where FieldID= ".$row['FieldID']."");
  // }
 //}
    //dbWrite("insert into tbl_area_physical (AreaName,RegionalID,CID) values ('".addslashes(encode_text2($_REQUEST[memid]))."','368','8')");
    //dbWrite("insert into tbl_area_regional (RegionalName,StateID,CID) values ('".addslashes(encode_text2($_REQUEST[memid]))."','194','15')");
    $ip = long2ip($_REQUEST[memid]);
    //echo $ip;
}
?>