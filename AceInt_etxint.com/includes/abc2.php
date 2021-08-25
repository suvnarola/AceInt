<?

 include("/home/etxint/admin.etxint.com/includes/global.php");

 
 //$query = dbRead("select * from tbl_admin_users, members where (tbl_admin_users.SalesmanID = members.salesmanidOLD) and members.salesmanid < 1 group by members.memid");
 //$query = dbRead("select * from classifieds");
 //$query = dbRead("select * from tbl_corp_pages where page_title != ''");
 //$query = dbRead("select * from tbl_corp_data where pageid = 50 and position < 9 order by position, langcode");
 //$query = dbRead("select * from tbl_corp_data where CID = 1 and langcode = 'en' and pageid = 52 order by position, langcode");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

    //dbWrite("update members set salesmanid = '".$row[FieldID]."' where memid = '".$row[memid]."'");
    //dbWrite("update classifieds set int_check = 1");
    //print "update members set salesmanid = '".$row[FieldID]."' where memid = '".$row[memid]."'\r\n\r\n";
    //echo $row[page_title]?><br><?;
    //dbWrite("insert into tbl_corp_headers (pageid,page_header,page_active,CID) values ('".$row[pageid]."','".$row[page_title]."','1','15')");
    //dbWrite("insert into tbl_corp_data (langcode,pageid,position,data) values ('".$row['langcode']."','62','".$row['position']."','".addslashes(encode_text2($row['data']))."')");
    //dbWrite("update tbl_corp_data set data = '".addslashes(encode_text2($row['data']))."' where CID = 3 and langcode = 'du' and pageid = ".$row['pageid']." and position = ".$row['position']."");
   
 }

?>