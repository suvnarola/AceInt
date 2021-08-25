<?
 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");
 ini_set('max_execution_time','600');

//$queryc = dbRead("select * from  tbl_members_data where langcode = 'en' and CID = 1 order by pageid, position");
//$queryc = dbRead("select * from  tbl_admin_data where langcode = 'en' order by pageid, position");
$queryc = dbRead("select * from  tbl_corp_data where pageid = 52 and CID = 1 and langcode = 'en' order by pageid, position");
//$queryc = dbRead("select * from  tbl_corp_headers where langcode = 'en' and CID = 1 order by pageid");
//$queryc = dbRead("select * from  tbl_faq where faq_lan = 'en' and CID = 1 order by faq_no",snyper);
//$queryc = dbRead("select * from  tbl_lang_keywords where langcode = 'en' order by wordid");
//$queryc = dbRead("select * from  tbl_corp_log where FieldID = 256");
//$queryc = dbRead("select * from  xmlService where FieldID = 1");

while($rowc = mysql_fetch_assoc($queryc)) {
//$ee = unserialize($rowc[Data]);
//$ee = unserialize($rowc[xmlIpAddressArrray]);
//print_r($ee);
  //dbWrite("insert into tbl_members_data (langcode,pageid,position,data,CID) values ('".$rowc['langcode']."','8','".$rowc['position']."','".addslashes(encode_text2($rowc['data']))."','".$rowc['CID']."')");
  //dbWrite("insert into tbl_admin_data (langcode,pageid,position,data) values ('cn','".$rowc['pageid']."','".$rowc['position']."','".addslashes(encode_text2($rowc['data']))."')");
  //dbWrite("insert into tbl_corp_data (langcode,pageid,position,data,CID) values ('en','".$rowc['pageid']."','".$rowc['position']."','".addslashes(encode_text2($rowc['data']))."','5')");
  //dbWrite("insert into tbl_corp_headers (pageid,page_header,page_link,page_active,langcode,CID) values ('".$rowc['pageid']."','".addslashes(encode_text2($rowc['page_header']))."','".addslashes(encode_text2($rowc['page_link']))."','".$rowc['page_active']."','en','5')");
  //dbWrite("insert into tbl_faq (faq_no,faq_question,faq_answer,faq_lan,CID) values ('".$rowc['faq_no']."','".addslashes(encode_text2($rowc['faq_question']))."','".addslashes(encode_text2($rowc['faq_answer']))."','cn','5')",snyper);
  //dbWrite("insert into tbl_lang_keywords (wordid,langcode,word) values ('".$rowc['wordid']."','cn','".addslashes(encode_text2($rowc['word']))."')");

  //dbWrite("update tbl_members_data set data = '".addslashes(encode_text2($rowc['data']))."' where langcode = 'en' and CID = 15 and pageid = '".$rowc['pageid']."' and  position = '".$rowc['position']."'");
  dbWrite("update tbl_corp_data set data = '".addslashes(encode_text2($rowc['data']))."' where langcode = 'en' and CID = 6 and pageid = '".$rowc['pageid']."' and  position = '".$rowc['position']."'");
  //dbWrite("update tbl_corp_headers set page_header = '".addslashes(encode_text2($rowc['page_header']))."', page_link = '".addslashes(encode_text2($rowc['page_link']))."', page_active = '".addslashes(encode_text2($rowc['page_active']))."' where langcode = 'ua' and pageid = '".$rowc['pageid']."' and CID = 12");
  //dbWrite("update tbl_faq set faq_question = '".addslashes(encode_text2($rowc['faq_question']))."', faq_answer = '".addslashes(encode_text2($rowc['faq_answer']))."' where faq_lan = 'ua' and faq_no = '".$rowc['faq_no']."' and CID = 12",snyper);

}

//$ff[] = "66.98.250.10";
//$ff[] = "66.98.251.248";
//$ff[] = "66.98.251.249";
//$ff[] = "66.98.251.25";
//$ff[] = "66.98.250.122";
//$ff[] = "66.98.250.127";
//$ff[] = "66.98.250.230";
//$ff[] = "66.98.250.231";
$ff[] = "216.218.210.224";
$ff[] = "216.218.210.241";
$ff[] = "216.218.196.178";
$ff[] = "216.218.196.181";
//print_r($ff);
//dbWrite("update xmlService set xmlIpAddressArrray = '".addslashes(serialize($ff))."' where fieldID = 4");

?>