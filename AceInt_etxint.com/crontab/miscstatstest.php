<?

 /**
  * Update Graph Data for Misc Stats.
  */

 $NoSession = true;

 include("../includes/global.php");
 include("../includes/modules/db.php");

 $num_months = period_diff(strtotime("1st Feb 2000"),mktime())+1;
 while($num_months >= 0) {
  
  $YearMonth = date("Ym", mktime(0,0,0,date("m")-$num_months,1,date("Y")));
  $YearArray[$YearMonth] = array();
  $num_months--;
  
 }

 $SQLQuery = dbRead("select * from area where FieldID = 1");
 while($ARow = mysql_fetch_assoc($SQLQuery)) {

  $Extra = get_non_included_accounts($ARow['CID']);
  $CountrySQL = dbRead("select * from country where countryID = ".$ARow['CID']);
  $Country = mysql_fetch_assoc($CountrySQL);
  
  
  $Query10 = dbRead("SELECT Sum(transactions.sell) as SumOfsell, Sum(transactions.buy)) AS SumOfbuy, extract(year_month from transactions.dis_date) as date1 FROM transactions,members,members members_1 WHERE (((transactions.memid)=".$_SESSION['Country']['faccacc'].")) AND ((transactions.type)="1") AND ((members.licensee)=".$ARow['FieldID'].") AND (transactions.to_memid  Not IN ($Extra))) OR (((transactions.memid)=".$_SESSION['Country']['faccacc'].") AND ((transactions.type)="2") AND (transactions.to_memid Not IN ($Extra)) AND ((members_1.licensee)=".$ARow['FieldID'].")) group by date1");

  while($Row10 = mysql_fetch_assoc($Query10)) {
   
   if($Row10['date1'] != 0) {
   
    $YearArray[$Row10[date1]][$ARow['FieldID']]['MemCountPaid'] = $Row10['SumOfbuy'];
    $YearArray[$Row10[date1]][$ARow['FieldID']]['MemCountPaid2'] = $Row10['SumOfsell'];
   
   }
   
  }

 }


 foreach($YearArray as $Key => $Value) {
 
  foreach($Value as $Key2 => $Value2) {
   
   //dbWrite("insert into tbl_miscstats (AreaID,Month,GS_NoTrans,GS_CashPaid,RE_CashPaid,GS_Buy,GS_Sell,GS_CashIncurred,RE_CashIncurred,GS_Facility,RE_Facility,MemCount,MemCountPaid) values ('".$Key2."','".$Key."','".$Value2['NoTrans']."','".$Value2['GS_CashPaid']."','".$Value2['RE_CashPaid']."','".$Value2['GS_Buy']."','".$Value2['GS_Sell']."','".$Value2['GS_CashIncurred']."','".$Value2['RE_CashIncurred']."','".$Value2['GS_Facility']."','".$Value2['RE_Facility']."','".$Value2['MemCount']."','".$Value2['MemCountPaid']."')");
   echo $Key2.", ".$Key.", ".$Value2['MemCountPaid'].", ".$Value2['MemCountPaid2'];   
  }
 
 }
?>
Done.