<?

 /**
  * Update Graph Data for Neils Graphs.
  */

 $NoSession = true;

 include("global.php");

 dbWrite("delete from monthly_transactions");
 dbWrite("delete from monthly_members");

 $SQLQuery = dbRead("select * from country where facacc != 0");
 while($CRow = mysql_fetch_assoc($SQLQuery)) {

  /**
   * Update Monthly Transaction Amounts.
   */

  $Extra = get_non_included_accounts($CRow[countryID]);

  $Query = dbRead("select count(transactions.memid) as memid, sum(transactions.buy) as TradeAmount, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and members.CID = '$CRow[countryID]' and transactions.type='1' and ((transactions.memid not in ($Extra)) and (transactions.to_memid not in ($Extra))) group by date1");
  while($row = mysql_fetch_assoc($Query)) {

   $C_Total2 += $row[TradeAmount];
   if($row[date1] != 0) {
    dbWrite("insert into monthly_transactions (Month,Amount,TradeAmount,Cumulative,CID) values ('$row[date1]','$row[memid]','$row[TradeAmount]','$C_Total2','$CRow[countryID]')");
   }

  }

  $C_Total2 = 0;

  /**
   * Update Monthly Transaction Amounts.
   */

  $Query = dbRead("select count(memid) as memid, extract(year_month from members.datejoined) as date1 from members where CID = '$CRow[countryID]' group by date1");
  while($row2 = mysql_fetch_assoc($Query)) {

   $C_Total += $row2[memid];
   dbWrite("insert into monthly_members (Month,Amount,Cumulative,CID) values ('$row2[date1]','$row2[memid]','$C_Total','$CRow[countryID]')");

  }

  $C_Total = 0;

 }

 /**
  * Bloody Global graph.
  */

 $SQLQuery = dbRead("select sum(Amount) as GT, Month from monthly_members group by Month");
 while($GTRow = mysql_fetch_assoc($SQLQuery)) {

  $GrandTotal += $GTRow[GT];
  dbWrite("update monthly_members set GrandTotal = '$GrandTotal' where Month = '$GTRow[Month]'");

 }
