<?php
 ini_set("max_execution_time", 888888);

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/db.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/functions.transaction.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");

 $Odate = date("Y-m-d", mktime(0,0,0,date("m")-1,1-1,date("Y")));
 $Extra = get_non_included_accounts(1);

 echo $Odate;

 $Query9 = dbRead("SELECT transactions.memid as memid, transfeecash, paymenttype, interest, Sum(transactions.sell) - Sum(transactions.buy) as SumOfbalance FROM transactions, members WHERE transactions.memid = members.memid and CID = 1 and status in (0,5) and members.interest = 0 and members.prepaid != 1 and members.transfeecash > 0 and dis_date <= '$Odate' AND transactions.to_memid  Not IN (13698,16084) group by transactions.memid");

 while($Row9 = mysql_fetch_assoc($Query9)) {

	 $YearMonth2 = date("Y-m", mktime(0,0,0,date("m")-1,1,date("Y")));
	 $YearMonth = date("Ym", mktime(0,0,0,date("m")-1,1,date("Y")));
	 $YearMonths = date("d", mktime(0,0,0,date("m"),1-1,date("Y")));
	 $count = 1;
	 $YearArray[$YearMonth] = array();

	 while($count <= $YearMonths) {

	  $YearArray[$YearMonth][$count]["blank"] = 1;
	  $count++;

	 }

	 //$Query10 = dbRead("SELECT Sum(transactions.sell) - Sum(transactions.buy) as SumOfday, Sum(transactions.buy) AS SumOfbuy, extract(day from transactions.dis_date) as date1 FROM transactions WHERE memid = '$Row9[memid]' and dis_date like '2012-06-%' AND transactions.to_memid  Not IN (13698,16084) group by date1");
	 $Query10 = dbRead("SELECT Sum(transactions.sell) - Sum(transactions.buy) as SumOfday, Sum(transactions.buy) AS SumOfbuy, extract(day from transactions.dis_date) as date1 FROM transactions WHERE memid = '$Row9[memid]' and dis_date like '$YearMonth2-%' AND transactions.to_memid  Not IN (13698,16084) group by date1");

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

   //$balance = $balance + $Value2['movement'] + $Value2['facility'];
   $balance = $balance + $Value2['movement'];
   //echo "<br>".$balance;


   if($balance > 0) {

    $interest = $interest + ($balance/100 * 0.016438356164383561643835616438356);

   }
  }

 }
//insert interest transaction here;
if($interest > 0){

$details = "Monthly Interest";
//$today_date = date("2012-06-30");
$today_date = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom("30890");
		$Transfer->AddTo($Row9[memid]);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount($interest, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails($details);
		$Transfer->MultiCheck("1");

		$Transfer->DOTransfer("1");

echo "<br>".$Row9[memid].";".$interest.";".$today_date.";".$Row9['paymenttype'].";".$Row9['interest'];
$tt = $tt + $interest;
$C++;
}


 }
echo "<br> - ".$tt." - ".$C;
//print $Row10[date1]." - ".$Row10[SumOfbuy]."<br>";
//print_r($YearArray);

?>