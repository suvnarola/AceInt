<?php

// Administration Fees.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

//$dbgetmemwithfees = dbRead("SELECT transactions.memid, transactions.date, transactions.to_memid, transactions.dollarfees, transactions.type, transactions.details, transactions.authno, transactions.dis_date, transactions.checked, transactions.id, transactions.userid, members.admin_exempt FROM transactions INNER JOIN members ON transactions.memid = members.memid WHERE (transactions.dollarfees=199 Or transactions.dollarfees=249) AND transactions.dis_date= '2012-06-28' AND members.admin_exempt=1 ORDER BY transactions.id");
$dbgetmemwithfees = dbRead("SELECT feesincurred.fieldid, feesincurred.date, feesincurred.memid, feesincurred.licensee, feesincurred.percent, feesincurred.to_memid, feesincurred.to_licensee, feesincurred.to_percent, feesincurred.trans_id, feesincurred.fee_amount, feesincurred.fee_paid, members.prepaid FROM feesincurred INNER JOIN members ON feesincurred.memid = members.memid WHERE feesincurred.date = '2012-06-28' AND feesincurred.fee_amount = 199 AND members.prepaid = 1");

$c =0;
while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

  //$dbgetmemwithfees1 = dbRead("SELECT transactions.memid, Sum(transactions.dollarfees) AS SumOfdollarfees FROM transactions WHERE transactions.dis_date < '2012-06-28' and memid = ".$row[memid]." GROUP BY transactions.memid");
  //$row1 = mysql_fetch_assoc($dbgetmemwithfees1);

  //if($row1[SumOfdollarfees] < 0){
   //dbWrite("delete from mem_categories where memid = '".$_REQUEST['Client']."' and FieldID = '".addslashes($DeleteID)."'");
   dbWrite("delete from feesincurred where fieldid = '".$row[fieldid]."'");
   //echo $row1[memid]."<br>";
   $c++;

  //}


}

echo $c;
?>