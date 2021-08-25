<?php

// Administration Fees.

$NoSession = true;

//include("/home/etxint/admin.etxint.com/includes/global.php");
include("class.html.mime.mail.inc");
include("/home/etxint/admin.etxint.com/includes/taxinvoiceky.php");
include("/home/etxint/admin.etxint.com/monthly/statementky.php");

$dbgetmemwithfees = dbRead("SELECT transactions.memid, transactions.date, transactions.to_memid, transactions.dollarfees, transactions.type, transactions.details, transactions.authno, transactions.dis_date, transactions.checked, transactions.id, transactions.userid, members.admin_exempt, members.companyname FROM transactions INNER JOIN members ON transactions.memid = members.memid WHERE (transactions.dollarfees=158.40) AND transactions.dis_date= '2013-06-28' and transactions.memid = " . $_REQUEST['memid'] . " and status = 1 ORDER BY transactions.id limit 1");

$c =0;
while($row = mysql_fetch_assoc($dbgetmemwithfees)) {
$c++;
 $dbgetmemwithfeess = dbRead("SELECT transactions.memid, transactions.date, transactions.to_memid, transactions.dollarfees, transactions.type, transactions.details, transactions.authno, transactions.dis_date, transactions.checked, transactions.id, transactions.userid, members.admin_exempt, members.companyname FROM transactions INNER JOIN members ON transactions.memid = members.memid WHERE (transactions.dollarfees=-158.40) AND transactions.dis_date > '2013-06-28' and transactions.memid = " . $row['memid'] . " and status = 1 ORDER BY transactions.id limit 1");
 $row1 = mysql_fetch_assoc($dbgetmemwithfeess);

 if($row1[id] > 0) {

   dbWrite("delete from feesincurred where trans_id = '".$row[id]."'");
   dbWrite("delete from transactions where id = '".$row[id]."'");
   dbWrite("delete from transactions where id = '".$row1[id]."'");
   dbWrite("update invoice set currentfees = currentfees-'".$row[dollarfees]."' where date = '2013-06-30' and memid = " . $row['memid'] . "");

   echo $row1['memid'].",";

 }
}

echo $c;
