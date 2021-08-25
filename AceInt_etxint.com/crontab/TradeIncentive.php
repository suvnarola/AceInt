<?

// Trade Incentive - Monthly Script.

include("/home/etxint/admin.etxint.com/includes/global.php");

if($_GET[Debug]) {

 echo "<pre><br>Trade Incentive - Debug Mode\r\n\r\n";

}

$test = mktime(1,1,1,date("m")-1,1,date("Y"));
$numdays = date("t", $test);

if($_REQUEST[Debug]) {

 echo "Number of Days last month: $numdays\r\n";

}

$prev_month_date = date("Y-m-d", mktime(1,1,1,date("m"),1-1,date("Y")));
$prev_month_date2 = date("Y-m-d", mktime(1,1,1,date("m")-1,1-1,date("Y")));

if($_REQUEST[Debug]) {

 echo "Last day of previous month: $prev_month_date2\r\n";
 echo "Last day of last month: $prev_month_date\r\n\r\n";

}

$getmemids = dbRead("select members.*, (overdraft+reoverdraft) as Facility from members where alltrades='1'");
while($row = mysql_fetch_assoc($getmemids)) {

// Check to see if the member owes any fees.

if($_REQUEST[Debug]) {

 echo "Member: $row[companyname] ($row[memid])\r\n";

}

$query3 = dbRead("select sum(currentfees+currentpaid+overduefees) as Test1 from invoice where memid = '$row[memid]' and date = '$prev_month_date2'");
$row3 = @mysql_fetch_assoc($query3);

if($_REQUEST[Debug]) {

 echo "Current Owed Cashfees: $row3[Test1]\r\n";
 
}

if(!$row3[Test1]) {
 $row3[Test1] = "0";
}

$query2 = @mysql_db_query(etradebanc, "select currentpaid as Test2 from invoice where memid = '$row[memid]' and date = '$prev_month_date'", $linkid);
$row2 = @mysql_fetch_assoc($query2);

if($_REQUEST[Debug]) {

 echo "Current Paid Cashfees: $row2[Test2]\r\n";
 
}

if(!$row2[Test2]) {
 $row[Test2] = "0";
}

$FinalAmount = $row3[Test1] + $row2[Test2];

if($_REQUEST[Debug]) {

 echo "Final Amount: $FinalAmount\r\n";
 
}

if($FinalAmount <= 20) {

$totalbal = 0;
$foo = 1;
while($foo <= $numdays) {

 $test = mktime(1,1,1,date("m")-1,$foo,date("Y"));
 $foo3 = date("Y-m-d", $test);

 $getbal = dbRead("select (sum(sell)-sum(buy))-sum(tradefees) as cb from transactions where memid='$row[memid]' and dis_date <= '$foo3'");
 $row4 = mysql_fetch_assoc($getbal);
 
 if($Facility > 0) {
  $row4[cb] = $row4[cb]-$row[Facility];
 }
 $totalbal += $row4[cb];
 $foo++;

}

 $pre_avg = $totalbal/$numdays;
 $int = 10;
 $pre_intamount = (($pre_avg*.1)/12);

 #insert interest

 if($pre_intamount > 0) {
   $authno = mt_rand(1000000,99999999);
   $t = mktime();
   $d = date("Y-m-d");
   
   if($_REQUEST[Debug]) {
    
    echo "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,who) values('10655','$t','$row[memid]','$pre_intamount','0','0','0','1','Trade Incentive','$authno','$d','0','','system')\r\n";
    echo "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,who) values('$row[memid]','$t','10655','0','$pre_intamount','0','0','2','Trade Incentive','$authno','$d','0','','system')\r\n";
    
   } else {
   
    dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,who) values('10655','$t','$row[memid]','$pre_intamount','0','0','0','1','Trade Incentive','$authno','$d','0','','system')");
    dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,who) values('$row[memid]','$t','10655','0','$pre_intamount','0','0','2','Trade Incentive','$authno','$d','0','','system')");
    
   }

 }

}

echo "\r\n\r\n";

}
?>