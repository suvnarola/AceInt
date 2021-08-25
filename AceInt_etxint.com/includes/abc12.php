<?
 include("/home/etxint/admin.etxint.com/includes/global.php");

   //$Wlang_query = dbRead("select * from tbl_corp_log");
   //$Wrowlang = mysql_fetch_assoc($Wlang_query);
   
 // $UserModules[$Row[FieldID]] = unserialize($Wrowlang['Data']);
      
  //print_r($UserModules);

   //$Wlang_query = dbRead("select * from members left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no) having (tbl_members_email.acc_no is null)");
   $Wlang_query = dbRead("select * from tbl_members_feedback where AccountNumber > 1");
   $counter = 0;   
   while($Wrowlang = mysql_fetch_assoc($Wlang_query)) {
    $counter++;
    //$Memid = $Wrowlang['memid'];
    //dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','1','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
    //dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','2','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
    //dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','3','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
    //dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','4','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");     
    //print $Wrowlang['memid']." ".$Wrowlang['emailaddress']."<br>";
    
    
   }
 print $counter;
?>