<?
 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");

 $date = date("Y-m", mktime(0,0,0,date("m")-1,1,date("Y")));

 $querycountry = dbRead("select * from country where Display = 'Yes'");
 while ($rowcountry = mysql_fetch_assoc($querycountry)) {

  $query2 = dbRead("select * from transactions where memid = '".$rowcountry['suspense']."' and buy > 0 and dis_date like '$date-%'");
  while ($row2 = mysql_fetch_assoc($query2)) {

   $query = dbRead("select sum(sell)-sum(buy) as Balance, sum(dollarfees) as CashFees from transactions where memid = '$row2[to_memid]'");
   $row = mysql_fetch_assoc($query);

   if($row[Balance] > $row2[buy])  {
    dbWrite("update transactions set memid='".$rowcountry['test']."', to_memid='".$rowcountry['test']."' where authno = '$row2[authno]' and ((memid = '$row2[to_memid]' and to_memid = '".$rowcountry['suspense']."') or (memid = '".$rowcountry['suspense']."' and to_memid = '$row2[to_memid]'))");

   }
  }
 }
