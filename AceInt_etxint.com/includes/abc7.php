<?

 /**
  * Update Graph Data for Misc Stats.
  */

 $NoSession = true;

 include("../includes/global.php");
 include("../includes/modules/db.php");

 $num_months = period_diff(strtotime("1st Feb 2000"),mktime())+1;
 $YearArray["200201"] = array();
 //while($num_months >= 0) {
  
  //$YearMonth = date("Ym", mktime(0,0,0,date("m")-$num_months,1,date("Y")));
  //$YearArray[$YearMonth] = array();
  //$num_months--;
  
 //}

 $SQLQuery = dbRead("select * from area where CID = 1 order by FieldID");
 while($ARow = mysql_fetch_assoc($SQLQuery)) {
 
  //$Query7 = dbRead("select sum(transactions.sell) as sell, sum(transactions.buy) as buy, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and members.licensee = '".$ARow['FieldID']."' and transactions.to_memid = ".$Country['facacc']." group by date1");
  $Query7 = dbRead("select sum(transactions.sell) as sell, sum(transactions.buy) as buy, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and members.licensee = '".$ARow['FieldID']."' and transactions.to_memid = ".$Country['facacc']." and transactions.memid NOT IN (".get_non_included_accounts($ARow['CID']).") group by date1");
  while($Row7 = mysql_fetch_assoc($Query7)) {
   
   if($Row7['date1'] != 0) {
    $Facility = $Row7['sell'] - $Row7['buy'];
    $YearArray[$Row7[date1]][$ARow['FieldID']]['GS_Facility'] = $Facility;
   
   }
   
  } 
 
 }
 
 foreach($YearArray as $Key => $Value) {
 
  foreach($Value as $Key2 => $Value2) {
    
  $year = substr($Key,0,4);
  $month = substr($Key,4,2);
  $day = date("d", mktime(0,0,0,$month+1,1-1,$year));
    
  $ym = $year."-".$month."-".$day;
 
   $QueryCID = dbRead("Select CID, facacc, refacacc from area, country where (area.CID = country.countryID) and FieldID = ".$Key2."");  
   $RowCID = mysql_fetch_assoc($QueryCID);
  
   $Query12 = dbRead("SELECT SUM(transactions.buy )  as SumBuy,SUM(transactions.sell ) as SumSell  FROM members,transactions,members members_1 WHERE (((transactions.to_memid = members.memid ) AND (transactions.memid = members_1.memid ) ) AND ((((((transactions.memid = ".$RowCID['facacc']." ) AND (transactions.type = '1' ) ) AND (members.licensee = ".$Key2." ) ) AND NOT((transactions.to_memid IN (".get_non_included_accounts($RowCID['CID']).") ) ) ) AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} ) ) OR (((((transactions.memid = ".$RowCID['facacc']." ) AND (transactions.type = '2' ) ) AND NOT((transactions.to_memid IN (".get_non_included_accounts($RowCID['CID']).") ) ) ) AND (members_1.licensee = ".$Key2.") ) AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} ) ) ) )");  
   $Row12 = mysql_fetch_assoc($Query12);
   $tt12 = $Row12['SumBuy'] - $Row12['SumSell'];
           
   //dbWrite("insert into tbl_miscstats (AreaID,Month,GS_NoTrans,GS_CashPaid,RE_CashPaid,GS_Buy,GS_Sell,GS_CashIncurred,RE_CashIncurred,GS_Facility,RE_Facility,MemCount,MemCountPaid,T_GS_Facility,T_RE_Facility) values ('".$Key2."','".$Key."','".$Value2['NoTrans']."','".$Value2['GS_CashPaid']."','".$Value2['RE_CashPaid']."','".$Value2['GS_Buy']."','".$Value2['GS_Sell']."','".$Value2['GS_CashIncurred']."','".$Value2['RE_CashIncurred']."','".$Value2['GS_Facility']."','".$Value2['RE_Facility']."','".$Value2['MemCount']."','".$Value2['MemCountPaid']."','".$tt12."','".$tt13."')");
   Print $Key2.", ".$Key."', ".$tt12."\r\n";
  }
 
 }
 
 
?>