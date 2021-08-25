<?php
 ini_set("max_execution_time", 888888);

 $NoSession = true;
 include("../includes/global.php");
 include("../includes/modules/db.php");

 $Odate = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 $Extra = get_non_included_accounts(1);

 $Query9 = dbRead("SELECT transactions.memid as memid, Sum(transactions.sell) - Sum(transactions.buy) as SumOfbalance FROM transactions, members WHERE transactions.memid = members.memid and CID = 1 and status = 0 and dis_date <= '$Odate' AND transactions.to_memid  Not IN (13698) group by transactions.memid");
$tt =0;
 while($Row9 = mysql_fetch_assoc($Query9)) {

	 $YearMonth = date("Ym", mktime(0,0,0,date("m")-1,1,date("Y")));
	 $YearMonths = date("d", mktime(0,0,0,date("m"),1-1,date("Y")));
	 $count = 1;
	 $YearArray[$YearMonth] = array();

	 while($count <= $YearMonths) {

	  $YearArray[$YearMonth][$count]["blank"] = 1;
	  $count++;

	 }

	 $Query10 = dbRead("SELECT Sum(transactions.sell) - Sum(transactions.buy) as SumOfday, Sum(transactions.buy) AS SumOfbuy, extract(day from transactions.dis_date) as date1 FROM transactions WHERE memid = '$Row9[memid]' and dis_date like '2012-02-%' AND transactions.to_memid  Not IN ($Extra) group by date1");

	  while($Row10 = mysql_fetch_assoc($Query10)) {

	   if($Row10['date1'] != 0) {

	    $YearArray[$YearMonth][$Row10[date1]]['movement'] = $Row10['SumOfday'];

	   }

	 }

	 //$Query10 = dbRead("SELECT Sum(transactions.sell) - Sum(transactions.buy) as SumOfday, Sum(transactions.buy) AS SumOfbuy, extract(day from transactions.dis_date) as date1 FROM transactions WHERE memid = '$Row9[memid]' and dis_date like '2012-02-%' AND transactions.to_memid  IN ($Extra) group by date1");

	  //while($Row10 = mysql_fetch_assoc($Query10)) {

	   //if($Row10['date1'] != 0) {

	    //$YearArray[$YearMonth][$Row10[date1]]['facility'] = $Row10['SumOfday'];

	   //}

	 //}
//echo $Row9[SumOfbalance]." - ";
$interest = 0;
$balance = $Row9[SumOfbalance];
 foreach($YearArray as $Key => $Value) {

  foreach($Value as $Key2 => $Value2) {
//print_r($Value2);
   //$balance = $balance + $Value2['movement'] + $Value2['facility'];
   $balance = $balance + $Value2['movement'];
  //echo "<br>".$balance;


   if($balance < 0) {

    $interest = $interest + (abs($balance)/100 * 0.016438356164383561643835616438356);

   }
  }

 }
//insert interest transaction here;
if($interest > 1){
echo "<br>".$Row9[memid]." Interest - ".number_format($interest, 2)."<br>";
$tt = $tt + $interest;
$C++;
}


 }
echo "<br> - ".$tt." - ".$C;
//print $Row10[date1]." - ".$Row10[SumOfbuy]."<br>";
//print_r($YearArray);

?>