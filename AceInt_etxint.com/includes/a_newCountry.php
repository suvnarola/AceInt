<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */

 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");
 ini_set('max_execution_time','600');

$cid = 17;
$lid = 'en';

$queryc = dbRead("select * from tbl_corp_data where CID = 1 and langcode = 'en' order by pageid, position");
while($rowc = mysql_fetch_assoc($queryc)) {

  dbWrite("insert into tbl_corp_data (langcode,pageid,position,data,CID) values ('".$lid."','".$rowc['pageid']."','".$rowc['position']."','".addslashes(encode_text2($rowc['data']))."','".$cid."')");

}


$queryc2 = dbRead("select * from  tbl_corp_headers where langcode = 'en' and CID = 1 order by pageid");
while($rowc2 = mysql_fetch_assoc($queryc2)) {

  dbWrite("insert into tbl_corp_headers (pageid,page_header,page_link,page_active,page_sect,langcode,CID) values ('".$rowc2['pageid']."','".addslashes(encode_text2($rowc2['page_header']))."','".addslashes(encode_text2($rowc2['page_link']))."','".$rowc2['page_active']."','".$rowc2['page_sect']."','".$lid."','".$cid."')");

}


$querym = dbRead("select * from  tbl_members_data where langcode = 'en' and CID = 1 order by pageid, position");
while($rowm = mysql_fetch_assoc($querym)) {

  dbWrite("insert into tbl_members_data (langcode,pageid,position,data,CID) values ('".$lid."','".$rowm['pageid']."','".$rowm['position']."','".addslashes(encode_text2($rowm['data']))."','".$cid."')");

}


$queryf = dbRead("select * from  tbl_faq where faq_lan = 'en' and CID = 1 order by faq_no",snyper);
while($rowf = mysql_fetch_assoc($queryf)) {

  dbWrite("insert into tbl_faq (faq_no,faq_question,faq_answer,faq_lan,CID) values ('".$rowf['faq_no']."','".addslashes(encode_text2($rowf['faq_question']))."','".addslashes(encode_text2($rowf['faq_answer']))."','".$lid."','".$cid."')",snyper);

}



if($lid != 'en') {

 $querya = dbRead("select * from tbl_admin_data where langcode = 'en' order by pageid, position");
 while($rowa = mysql_fetch_assoc($querya)) {

  dbWrite("insert into tbl_admin_data (langcode,pageid,position,data) values ('".$lid."','".$rowa['pageid']."','".$rowa['position']."','".addslashes(encode_text2($rowa['data']))."')");

 }

 $queryk = dbRead("select * from tbl_lang_keywords where langcode = 'en' order by wordid");
 while($rowk = mysql_fetch_assoc($queryk)) {

  dbWrite("insert into from tbl_lang_keywords (wordid,langcode,word) values ('".$rowk['wordid']."','".$lid."''".addslashes(encode_text2($rowk['word']))."')");

 }
// keywords
// admin data
}

?>