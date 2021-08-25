<?

 /**
  * E Banc Trade Invoice Script.
  *
  * invoice.php
  * Version 0.01
  */

 ini_set("max_execution_time", "120");

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");

 /**
  * Loop around the Countries.
  */

 //print "<pre>";

 $CSQL = dbRead("select country.* from country where countryID = 1 order by countryID");
 while($CRow = mysql_fetch_assoc($CSQL)) {

  $CMembers = dbRead("select memid, ac_fees from members where ((datejoined < '".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."' and status != '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1-1,date("Y")))."-%' and status = '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1,date("Y")))."-%' and status = '1')) and CID = '".$CRow['countryID']."'");
  //$CMembers = dbRead("select memid from members where ((datejoined < '".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."' and status != '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1-1,date("Y")))."-%' and status = '1')) and CID = '".$CRow['countryID']."'");
  //$CMembers = dbRead("select memid from members where datejoined < '".date("Y", mktime(0,0,0,date("n")-1,1,date("Y")))."-".date("n", mktime(0,0,0,date("n")-1,1,date("Y")))."-01' and status != '1' and CID = '".$CRow['countryID']."'");
  //$CMembers = dbRead("select memid from members where datejoined < '2004-08-01' and status != '1' and CID = '".$CRow['countryID']."'");
  while($MemRow = mysql_fetch_assoc($CMembers)) {
   $OverdueFees = 0;
   $OverdueFees1 = 0;
   $OverdueFees2 = 0;
   $CurrentFeesOwe = 0;

   $CurrentFeesOweSQL = dbRead("SELECT SUM(dollarfees) as CurrentFees FROM transactions WHERE ((((memid = ".$MemRow['memid'].") AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true)."))) AND (date < '2014-07-01' ) ))");
   $CurrentFeesOwe = mysql_fetch_assoc($CurrentFeesOweSQL);

   $CurrentPaidSQL = dbRead("SELECT SUM(dollarfees ) as CurrentPaid FROM transactions WHERE ((((memid = ".$MemRow['memid']." ) AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true).")) AND (date >= '2014-07-01' ) ) ) AND (dollarfees < 0 ) )");
   $CurrentPaid = mysql_fetch_assoc($CurrentPaidSQL);

   //$OverdueFeesSQL2 = dbRead("SELECT Sum(transactions.dollarfees) AS OverdueFees2 FROM transactions WHERE memid='".$MemRow['memid']."' AND (date >= ".mktime(0,0,0,date("n", mktime(0,0,0,date("n")-1,1,date("Y"))),1,date("Y", mktime(0,0,0,date("n")-1,1,date("Y"))))." ) AND (date < ".mktime(0,0,0,date("n"),1,date("Y"))." ) AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true).")) and type = 10");
   //$OverdueFees2 = mysql_fetch_assoc($OverdueFeesSQL2);

   if($OverdueFees2['OverdueFees2']) {
   		$CurrentPaid = $CurrentPaid['CurrentPaid'] + $OverdueFees2['OverdueFees2'];
   } else {
   		$CurrentPaid = $CurrentPaid['CurrentPaid'];
   }

	$CurrentFeesOwe = $CurrentFeesOwe['CurrentFees'];
   //$OverdueFeesSQL = dbRead("SELECT Sum(transactions.dollarfees) AS OverdueFees FROM transactions WHERE memid='".$MemRow['memid']."' AND date < ".mktime(0,0,0,date("n", mktime(0,0,0,date("n")-1,1,date("Y"))),1,date("Y", mktime(0,0,0,date("n")-1,1,date("Y"))))." AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true)."))");
   //$OverdueFees = mysql_fetch_assoc($OverdueFeesSQL);

   if($_REQUEST['Debug']) {
print "a".$CurrentFeesOwe;
		print "memid = " . $MemRow['memid'];
		print "<br>";
		print "OverDueFees1 = " . $CurrentFeesOwe['CurrentFees'];
		print "<br>";
		print "OverDueFees2 = " . $CurrentPaid;
		print "<br>";
		print "<br>";

   } else {

   ///$inv = dbWrite("insert into invoice (memid,currentfees,currentpaid,overduefees,date) values ('".$MemRow['memid']."','".$CurrentFeesOwe['CurrentFees']."','".$CurrentPaid."','".$OverdueFees['OverdueFees']."','".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."')","etradebanc",true);

   }
  }

 }

 print "</pre>";

?>