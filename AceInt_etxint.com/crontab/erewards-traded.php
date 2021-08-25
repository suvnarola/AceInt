<?

 /**
  * Funds Transfer Functions
  *
  * functions.transaction.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  */
 
 ini_set("max_execution_time", "120");

 $NoSession = true;
 
 include("../includes/global.php");
 
 $MemberSQL = dbRead("select members.* from members where reward_check = 0 and CID = 1 and datejoined > '".date("Y-m-d", mktime(1,1,1,date("m")-6,1,date("Y")))."'");
 while($MemberRow = mysql_fetch_assoc($MemberSQL)) {
 
  if(CheckLastSixMonths($MemberRow['memid'])) {
  
   dbWrite("update members set reward_check = 1 where memid = " . $MemberRow['memid']);
  
  }
 
 }
 
 /**
  * Functions.
  */
  
 function CheckLastSixMonths($Memid) {
  
  $CheckSQL = dbRead("select (sum(sell)+sum(buy)) as Balance from transactions where memid='".$Memid."' and (transactions.memid NOT IN (".get_non_included_accounts('1').") and transactions.to_memid NOT IN (".get_non_included_accounts('1').")) and dis_date between '".date("Y-m-d", mktime(1,1,1,date("m")-6,date("d"),date("Y")))."' and '".date("Y-m-d")."'");
  $CheckRow = mysql_fetch_assoc($CheckSQL);
  
  if($CheckRow['Balance'] >= 500) {
  
   return 1;
  
  } 
  
 }

?>