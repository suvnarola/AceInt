<?php

// Administration Fees.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

//$dbgetmemwithfees = dbRead("SELECT transactions.memid, transactions.date, transactions.to_memid, transactions.dollarfees, transactions.type, transactions.details, transactions.authno, transactions.dis_date, transactions.checked, transactions.id, transactions.userid, members.admin_exempt, members.companyname FROM transactions INNER JOIN members ON transactions.memid = members.memid WHERE (transactions.dollarfees=199 Or transactions.dollarfees=249) AND transactions.dis_date= '2012-06-28' and transactions.memid = " . $_REQUEST['memid'] . " ORDER BY transactions.id limit 1");
$dbgetmemwithfees = dbRead("SELECT * FROM transactions INNER JOIN members ON transactions.memid = members.memid WHERE transactions.details = 'annual admin fee' AND members.status=1 and members.memid not in (20961,29375,19169,25519,30097,27718,24402,20961,25519,30097)");

$c =0;
while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

   $dbgetmemwithfees2 = dbRead("SELECT transactions.* FROM transactions WHERE transactions.dis_date > '2012-06-30' and transactions.memid = '".$row[memid]."' and transactions.details = 'Reversal of Fees' ");
   $row2 = mysql_fetch_assoc($dbgetmemwithfees2);

   if(abs($row2[dollarfees]) > $row[dollarfees]) {


 $array1 = explode("-", $row2[dis_date]);
 $day = $array1[2];
 $month = $array1[1];
 $year = $array1[0];
   $dd = date("Y-m", mktime(0,0,0,$month,$day,$year));
   //echo $dd;
   echo $row[memid]."<br>";

   dbWrite("delete from feesincurred where trans_id = '".$row[id]."'");
   dbWrite("delete from transactions where id = '".$row[id]."'");
   dbWrite("update transactions set dollarfees = dollarfees+'".$row[dollarfees]."' where id = " . $row2['id'] . "");
   dbWrite("update invoice set currentpaid = currentpaid+'".$row[dollarfees]."' where date like '".$dd."-%' and memid = " . $row['memid'] . "");

   dbWrite("update invoice set currentfees = currentfees-'".$row[dollarfees]."' where date = '2012-06-30' and memid = " . $row['memid'] . "");
   dbWrite("update invoice set overduefees = overduefees-'".$row[dollarfees]."' where date > '2012-06-30' and memid = " . $row['memid'] . "");


   $c++;

   }

}

echo $c;
?>