<?

 /**
  * Update Graph Data for Misc Stats.
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/db.php");

 //dbWrite("delete from tbl_miscstats");

 //$num_months = period_diff(strtotime("1st Feb 2000"),mktime())+1;
 //while($num_months >= 0) {

  //$YearMonth = date("Ym", mktime(0,0,0,date("m")-$num_months,1,date("Y")));
  //$YearArray[$YearMonth] = array();
  //$num_months--;
   //$SQLQuery = dbRead("select * from area  order by FieldID");
   //while($Rows = mysql_fetch_assoc($SQLQuery)) {
    //$YearArray[$YearMonth][$Rows['FieldID']]["blank"] = 1;
   //}
 //}

 $YearMonth2 = date("Y-m", mktime(0,0,0,date("m")-1,1,date("Y")));
 $YearMonth = date("Ym", mktime(0,0,0,date("m")-1,1,date("Y")));
 //$YearMonth2 = "2010-06";
 //$YearMonth = "201006";

 $YearArray[$YearMonth] = array();

 $SQLQuery = dbRead("select * from area  order by FieldID");
 while($Rows = mysql_fetch_assoc($SQLQuery)) {
   $YearArray[$YearMonth][$Rows['FieldID']]["blank"] = 1;
 }


 $SQLQuery = dbRead("select * from area  order by FieldID");
 while($ARow = mysql_fetch_assoc($SQLQuery)) {

  $Extra = get_non_included_accounts($ARow['CID']);
  $CountrySQL = dbRead("select * from country where countryID = ".$ARow['CID']);
  $Country = mysql_fetch_assoc($CountrySQL);

  //$Query = dbRead("select count(transactions.memid) as memid, sum(transactions.buy) as TradeAmount, sum(transactions.sell) as STradeAmount, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and transactions.type IN (1,2) and members.area = '".$ARow['FieldID']."' and ((transactions.memid not in ($Extra)) AND (transactions.to_memid not in ($Extra)))group by date1 ");
  $Query = dbRead("select count(transactions.memid) as memid, sum(transactions.buy) as TradeAmount, sum(transactions.sell) as STradeAmount from transactions, members where (members.memid = transactions.memid) and transactions.dis_date like '$YearMonth2-%' and transactions.type IN (1,2) and members.licensee = '".$ARow['FieldID']."' and ((transactions.memid not in ($Extra)) AND (transactions.to_memid not in ($Extra))) ");
  //while($row = mysql_fetch_assoc($Query)) {
  $row = mysql_fetch_assoc($Query);
   //if($row[date1] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['NoTrans'] = $row['memid'];
    $YearArray[$YearMonth][$ARow['FieldID']]['GS_Buy'] = $row['TradeAmount'];
    $YearArray[$YearMonth][$ARow['FieldID']]['GS_Sell'] = $row['STradeAmount'];

   //}

  //}

  //$Query3 = dbRead("select sum(amountpaid) as GSPaid, extract(year_month from feespaid.paymentdate) as date1 from feespaid, members where (members.memid = feespaid.memid) and members.licensee = '".$ARow['FieldID']."' and type = 1 group by date1");
  $Query3 = dbRead("select sum(amountpaid) as GSPaid from feespaid, members where (members.memid = feespaid.memid) and feespaid.paymentdate like '$YearMonth2-%' and members.licensee = '".$ARow['FieldID']."' and type = 1 ");
  //while($Row3 = @mysql_fetch_assoc($Query3)) {
  $Row3 = @mysql_fetch_assoc($Query3);
   //if($Row3['date1'] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['GS_CashPaid'] = $Row3['GSPaid'];

   //}

  //}

  $Query4 = dbRead("select sum(amountpaid) as REPaid from feespaid, members where (members.memid = feespaid.memid) and feespaid.paymentdate like '$YearMonth2-%' and members.licensee = '".$ARow['FieldID']."' and type = 2 ");
  //while($Row4 = @mysql_fetch_assoc($Query4)) {
  $Row4 = @mysql_fetch_assoc($Query4);
   //if($Row4['date1'] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['RE_CashPaid'] = $Row4['REPaid'];

   //}

  //}

  //$Query5 = dbRead("select sum(transactions.dollarfees) as CashFees, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and transactions.dollarfees > 0 and members.licensee = '".$ARow['FieldID']."' and ((transactions.memid not in ($Extra)) AND (transactions.to_memid not in ($Extra))) group by date1");
  $Query5 = dbRead("select sum(transactions.dollarfees) as CashFees from transactions, members where (members.memid = transactions.memid) and transactions.dis_date like '$YearMonth2-%' and transactions.dollarfees > 0 and transactions.type != 10 and members.licensee = '".$ARow['FieldID']."' and ((transactions.memid not in (".get_non_included_accounts($ARow['CID']).")) AND (transactions.to_memid not in (".get_non_included_accounts($ARow['CID'], true,false,false,true)."))) ");
  //while($Row5 = mysql_fetch_assoc($Query5)) {
  $Row5 = mysql_fetch_assoc($Query5);
   //if($Row5[date1] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['GS_CashIncurred'] = $Row5['CashFees'];

   //}

  //}

  $Query6 = dbRead("select sum(transactions.dollarfees) as CashFees from transactions, members where (members.memid = transactions.memid) and transactions.dis_date like '$YearMonth2-%' and transactions.dollarfees > 0 and members.licensee = '".$ARow['FieldID']."' and ((transactions.memid in (".get_non_included_accounts($ARow['CID'], true).")) AND (transactions.to_memid in (".get_non_included_accounts($ARow['CID'], true)."))) ");
  //while($Row6 = mysql_fetch_assoc($Query6)) {
  $Row6 = mysql_fetch_assoc($Query6);
   //if($Row6[date1] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['RE_CashIncurred'] = $Row6['CashFees'];

   //}

  //}

  //$Query7 = dbRead("select sum(transactions.sell) as sell, sum(transactions.buy) as buy, extract(year_month from transactions.dis_date) as date1 from transactions, members where (members.memid = transactions.memid) and members.licensee = '".$ARow['FieldID']."' and transactions.to_memid = ".$Country['facacc']." group by date1");
  $Query7 = dbRead("select sum(transactions.sell) as sell, sum(transactions.buy) as buy from transactions, members where (members.memid = transactions.memid) and transactions.dis_date like '$YearMonth2-%' and members.licensee = '".$ARow['FieldID']."' and transactions.to_memid = ".$Country['facacc']." and transactions.memid NOT IN (".get_non_included_accounts($ARow['CID']).") ");
  //while($Row7 = mysql_fetch_assoc($Query7)) {
  $Row7 = mysql_fetch_assoc($Query7);
   //if($Row7['date1'] != 0) {
    $Facility = $Row7['sell'] - $Row7['buy'];
    $YearArray[$YearMonth][$ARow['FieldID']]['GS_Facility'] = $Facility;

   //}

  //}

  $Query8 = dbRead("select sum(transactions.sell) as sell, sum(transactions.buy) as buy from transactions, members where (members.memid = transactions.memid) and transactions.dis_date like '$YearMonth2-%' and members.licensee = '".$ARow['FieldID']."' and transactions.to_memid = ".$Country['refacacc']." and transactions.memid NOT IN (".get_non_included_accounts($ARow['CID']).") ");
  //while($Row8 = mysql_fetch_assoc($Query8)) {
  $Row8 = mysql_fetch_assoc($Query8);
   //if($Row8['date1'] != 0) {
    $Facility = $Row8['sell'] - $Row8['buy'];
    $YearArray[$YearMonth][$ARow['FieldID']]['RE_Facility'] = $Facility;

   //}

  //}

  //$Query9 = dbRead("select count(memid) as MemCount, extract(year_month from members.datejoined) as date1 from members, salespeople where (members.licensee = salespeople.areaid) and salespeople.areaid = ".$ARow['FieldID']." group by date1");
  //$Query9 = dbRead("select count(memid) as MemCount, extract(year_month from members.datejoined) as date1 from members, tbl_admin_users where (members.licensee = tbl_admin_users.Area) and tbl_admin_users.Area = ".$ARow['FieldID']." group by date1");
  $Query9 = dbRead("select count(memid) as MemCount from members, tbl_admin_users where (members.salesmanid = tbl_admin_users.FieldID) and members.datejoined like '$YearMonth2-%' and tbl_admin_users.Area = ".$ARow['FieldID']." ");

  //while($Row9 = mysql_fetch_assoc($Query9)) {
  $Row9 = mysql_fetch_assoc($Query9);
   //if($Row9['date1'] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['MemCount'] = $Row9['MemCount'];

   //}

  //}

  //$Query10 = dbRead("select count(memid) as MemCount, extract(year_month from members.datejoined) as date1 from members, salespeople where (members.licensee = salespeople.areaid) and salespeople.areaid = ".$ARow['FieldID']." and members.membershipfeepaid > '0' group by date1");
  //$Query10 = dbRead("select count(memid) as MemCount, extract(year_month from members.datejoined) as date1 from members, tbl_admin_users where (members.licensee = tbl_admin_users.Area) and tbl_admin_users.Area = ".$ARow['FieldID']." and members.membershipfeepaid > '0' group by date1");
  $Query10 = dbRead("select count(memid) as MemCount from members, tbl_admin_users where (members.salesmanid = tbl_admin_users.FieldID) and members.datejoined like '$YearMonth2-%' and tbl_admin_users.Area = ".$ARow['FieldID']." and members.membershipfeepaid > 0 ");

  //while($Row10 = mysql_fetch_assoc($Query10)) {
  $Row10 = mysql_fetch_assoc($Query10);
   //if($Row10['date1'] != 0) {

    $YearArray[$YearMonth][$ARow['FieldID']]['MemCountPaid'] = $Row10['MemCount'];

   //}

  //}

 }

 foreach($YearArray as $Key => $Value) {

  foreach($Value as $Key2 => $Value2) {

  $year = substr($Key,0,4);
  $month = substr($Key,4,2);
  $day = date("d", mktime(0,0,0,$month+1,1-1,$year));

  $ym = $year."-".$month."-".$day;

   $QueryCID = dbRead("Select CID, facacc, refacacc from area, country where (area.CID = country.countryID) and FieldID = ".$Key2."");
   $RowCID = mysql_fetch_assoc($QueryCID);

   //$Query12 = dbRead("SELECT SUM(transactions.buy )  as SumBuy,SUM(transactions.sell ) as SumSell  FROM members,transactions,members members_1 WHERE (((transactions.to_memid = members.memid ) AND (transactions.memid = members_1.memid ) ) AND ((((((transactions.memid = ".$RowCID['facacc']." ) AND (transactions.type = '1' ) ) AND (members.licensee = ".$Key2." ) ) AND NOT((transactions.to_memid IN (".get_non_included_accounts($RowCID['CID']).") ) ) ) AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} ) ) OR (((((transactions.memid = ".$RowCID['facacc']." ) AND (transactions.type = '2' ) ) AND NOT((transactions.to_memid IN (".get_non_included_accounts($RowCID['CID']).") ) ) ) AND (members_1.licensee = ".$Key2.") ) AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} ) ) ) )");
   $Query12 = dbRead("SELECT SUM(transactions.buy )  as SumBuy, SUM(transactions.sell ) as SumSell FROM members,transactions  WHERE (transactions.memid = members.memid ) AND (transactions.to_memid = ".$RowCID['facacc']." ) AND NOT (transactions.memid IN (".get_non_included_accounts($RowCID['CID']).") ) AND (members.licensee = ".$Key2.") AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} )  ");
   $Row12 = mysql_fetch_assoc($Query12);
   $tt12 = $Row12['SumSell'] - $Row12['SumBuy'];

   //$Query13 = dbRead("SELECT SUM(transactions.buy )  as SumBuy,SUM(transactions.sell ) as SumSell  FROM members,transactions,members members_1 WHERE (((transactions.to_memid = members.memid ) AND (transactions.memid = members_1.memid ) ) AND ((((((transactions.memid = ".$RowCID['refacacc']." ) AND (transactions.type = '1' ) ) AND (members.licensee = ".$Key2." ) ) AND NOT((transactions.to_memid IN (".get_non_included_accounts($RowCID['CID']).") ) ) ) AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} ) ) OR (((((transactions.memid = ".$RowCID['refacacc']." ) AND (transactions.type = '2' ) ) AND NOT((transactions.to_memid IN (".get_non_included_accounts($RowCID['CID']).") ) ) ) AND (members_1.licensee = ".$Key2.") ) AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} ) ) ) )");
   $Query13 = dbRead("SELECT SUM(transactions.buy )  as SumBuy, SUM(transactions.sell ) as SumSell FROM members,transactions  WHERE (transactions.memid = members.memid ) AND (transactions.to_memid = ".$RowCID['refacacc']." ) AND NOT (transactions.memid IN (".get_non_included_accounts($RowCID['CID']).") ) AND (members.licensee = ".$Key2.") AND (transactions.dis_date BETWEEN {d '2000-01-01'} AND {d '".$ym."'} )  ");
   $Row13 = mysql_fetch_assoc($Query13);
   $tt13 = abs($Row13['SumSell']) - abs($Row13['SumBuy']);

   dbWrite("insert into tbl_miscstats (AreaID,Month,GS_NoTrans,GS_CashPaid,RE_CashPaid,GS_Buy,GS_Sell,GS_CashIncurred,RE_CashIncurred,GS_Facility,RE_Facility,MemCount,MemCountPaid,T_GS_Facility,T_RE_Facility) values ('".$Key2."','".$Key."','".$Value2['NoTrans']."','".$Value2['GS_CashPaid']."','".$Value2['RE_CashPaid']."','".$Value2['GS_Buy']."','".$Value2['GS_Sell']."','".$Value2['GS_CashIncurred']."','".$Value2['RE_CashIncurred']."','".$Value2['GS_Facility']."','".$Value2['RE_Facility']."','".$Value2['MemCount']."','".$Value2['MemCountPaid']."','".$tt12."','".$tt13."')");

  }

 }
?>
Done.