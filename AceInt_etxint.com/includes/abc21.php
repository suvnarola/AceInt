<?
 include("/home/etxint/admin.etxint.com/includes/global.php");


 $query = dbRead("select * FROM transactions, members WHERE (transactions.memid = members.memid) and dis_date between ('2007-03-16' and '2007-05-31') and CID = 1");

 //while($row = mysql_fetch_assoc($query)) {



 //}

 //$dd = get_non_included_accounts(1,'','',true);
 //$dd = get_non_included_accounts(1);
 //$dd = get_non_included_accounts(1, true,false,false,true);
 $dd = get_non_included_accounts(1, true, false, false, true);
 print $dd;

?>