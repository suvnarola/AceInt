<?php

include("/home/etxint/admin.etxint.com/includes/global.php");

 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

 #get transactions out for each jimsnet member.
 $query2 = dbRead("select sum(sell-buy) as sbalance, memid from transactions where memid='$row[memid]' and dis_date <= '$date2' and CID = 1");

 while($row2 = mysql_fetch_assoc($query2)) {

   dbWrite("update members set decbalance = '$row2[sbalance]' where memid = '$row2[memid]'");

 }

?>