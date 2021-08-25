<?php

// Administration Fees.

$NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");

$dbgetmemwithfees = dbRead("SELECT feesincurred.*, transactions.authno FROM feesincurred INNER JOIN transactions ON feesincurred.trans_id = transactions.id WHERE (feesincurred.fee_amount = 199 Or feesincurred.fee_amount = 249) and feesincurred.date = '2012-06-28' AND feesincurred.fee_paid = 0");

$c =0;
while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

   $dbgetmemwithfees2 = dbRead("SELECT transactions.memid, Sum(transactions.dollarfees) AS SumOfdollarfees FROM transactions where memid = '".$row[memid]."' GROUP BY transactions.memid");
   $row2 = mysql_fetch_assoc($dbgetmemwithfees2);

   if($row2[SumOfdollarfees] > 218) {


		reverseTransaction($row['authno']);
		//add fees
		$aa = auth_no();
		$transDetails = "Monthly Admin Fees Jun - Nov";
		$day = date("j");
		$month_1 = date("n");
		$year1 = date("Y");
		$trandate = mktime(20,1,1,$month_1,16,$year1);
		$transdis_date = date("Y-m-d", $trandate);

		$transID = dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values  ('".$row['memid']."','".$trandate."','29679','0','0','0','99','3','".$transDetails."','".$aa."','".$transdis_date."','0','180')",'etradebanc',true);
	    dbWrite("insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id,percent,to_percent) values ('".$row['memid']."','".$row['licensee']."','".$transdis_date."','29679','1','99','".$transID."','50','0')");


   		$c++;

   }

}

echo $c;
