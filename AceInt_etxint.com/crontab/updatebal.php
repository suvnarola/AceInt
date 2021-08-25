<?php

include("/home/etxint/admin.etxint.com/includes/global.php");

 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1,date("Y")));

 #get transactions out for each jimsnet member.
 $query2 = dbRead("select sum(sell-buy) as sbalance, transactions.memid from transactions,members where transactions.memid = members.memid and dis_date < '$date2' and CID = 1 group by transactions.memid");

 while($row2 = mysql_fetch_assoc($query2)) {

  if($row2[sbalance] > 0) {
   $query3 = dbRead("select sum(buy) as bbalance, transactions.memid from transactions where memid = '$row2[memid]'and dis_date >= '$date2' group by transactions.memid");
   $row3 = mysql_fetch_assoc($query3);
   if($row3['bbalance']) {
    $bb=$row2[sbalance]-$row3['bbalance'];
   } else {
    $bb=$row2[sbalance];
   }
   dbWrite("update members set decbalance = '$bb' where memid = '$row2[memid]'");
  }
 }

?>