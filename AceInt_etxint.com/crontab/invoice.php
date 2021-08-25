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

 $CSQL = dbRead("select country.* from country order by countryID");
 while($CRow = mysql_fetch_assoc($CSQL)) {

  $CMembers = dbRead("select memid, ac_fees from members where ((datejoined < '".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."' and status != '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1-1,date("Y")))."-%' and status = '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1,date("Y")))."-%' and status = '1')) and CID = '".$CRow['countryID']."'");
  //$CMembers = dbRead("select memid from members where ((datejoined < '".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."' and status != '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1-1,date("Y")))."-%' and status = '1')) and CID = '".$CRow['countryID']."'");
  //$CMembers = dbRead("select memid from members where datejoined < '".date("Y", mktime(0,0,0,date("n")-1,1,date("Y")))."-".date("n", mktime(0,0,0,date("n")-1,1,date("Y")))."-01' and status != '1' and CID = '".$CRow['countryID']."'");
  //$CMembers = dbRead("select memid from members where datejoined < '2004-08-01' and status != '1' and CID = '".$CRow['countryID']."'");
  while($MemRow = mysql_fetch_assoc($CMembers)) {
   $OverdueFees = 0;
   $OverdueFees1 = 0;
   $OverdueFees2 = 0;

   $CurrentFeesOweSQL = dbRead("SELECT SUM(dollarfees) as CurrentFees FROM transactions WHERE ((((memid = ".$MemRow['memid'].") AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true).")) AND (date >= ".mktime(0,0,0,date("n", mktime(0,0,0,date("n")-1,1,date("Y"))),1,date("Y", mktime(0,0,0,date("n")-1,1,date("Y"))))." )) AND (date < ".mktime(0,0,0,date("n"),1,date("Y"))." ) ) AND (dollarfees > 0 ))and type != 10");
   //$CurrentFeesOweSQL = dbRead("SELECT SUM(dollarfees) as CurrentFees FROM transactions WHERE ((((memid = ".$MemRow['memid'].") AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true).")) AND (date >= ".mktime(0,0,0,8,1,2004)." )) AND (date < ".mktime(0,0,0,9,1,2004)." ) ) AND (dollarfees > 0 ) )");
   $CurrentFeesOwe = mysql_fetch_assoc($CurrentFeesOweSQL);

   $CurrentPaidSQL = dbRead("SELECT SUM(dollarfees ) as CurrentPaid FROM transactions WHERE ((((memid = ".$MemRow['memid']." ) AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true).")) AND (date >= ".mktime(0,0,0,date("n", mktime(0,0,0,date("n")-1,1,date("Y"))),1,date("Y", mktime(0,0,0,date("n")-1,1,date("Y"))))." ) ) AND (date < ".mktime(0,0,0,date("n"),1,date("Y"))." ) ) AND (dollarfees < 0 ) )");
   //$CurrentPaidSQL = dbRead("SELECT SUM(dollarfees ) as CurentPaid FROM transactions WHERE ((((memid = ".$MemRow['memid']." ) AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true).")) AND (date >= ".mktime(0,0,0,8,1,2004)." ) ) AND (date < ".mktime(0,0,0,9,1,2004)." ) ) AND (dollarfees < 0 ) )");
   $CurrentPaid = mysql_fetch_assoc($CurrentPaidSQL);

   $OverdueFeesSQL2 = dbRead("SELECT Sum(transactions.dollarfees) AS OverdueFees2 FROM transactions WHERE memid='".$MemRow['memid']."' AND (date >= ".mktime(0,0,0,date("n", mktime(0,0,0,date("n")-1,1,date("Y"))),1,date("Y", mktime(0,0,0,date("n")-1,1,date("Y"))))." ) AND (date < ".mktime(0,0,0,date("n"),1,date("Y"))." ) AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true).")) and type = 10");
   $OverdueFees2 = mysql_fetch_assoc($OverdueFeesSQL2);

   if($OverdueFees2['OverdueFees2']) {
   		$CurrentPaid = $CurrentPaid['CurrentPaid'] + $OverdueFees2['OverdueFees2'];
   } else {
   		$CurrentPaid = $CurrentPaid['CurrentPaid'];
   }

   $OverdueFeesSQL = dbRead("SELECT Sum(transactions.dollarfees) AS OverdueFees FROM transactions WHERE memid='".$MemRow['memid']."' AND date < ".mktime(0,0,0,date("n", mktime(0,0,0,date("n")-1,1,date("Y"))),1,date("Y", mktime(0,0,0,date("n")-1,1,date("Y"))))." AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true)."))");
   //$OverdueFeesSQL = dbRead("SELECT Sum(transactions.dollarfees) AS OverdueFees1 FROM transactions WHERE memid='".$MemRow['memid']."' AND date < ".mktime(0,0,0,8,1,2004)." AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true)."))");
   $OverdueFees = mysql_fetch_assoc($OverdueFeesSQL);

   if($_REQUEST['Debug']) {

		print "memid = " . $MemRow['memid'];
		print "<br>";
		print "OverDueFees1 = " . $OverdueFees1['OverdueFees1'];
		print "<br>";
		print "OverDueFees2 = " . $OverdueFees2['OverdueFees2'];		print "<br>";		print "CurrentPaid = " . $CurrentPaid['CurrentPaid'];		print "<br>";		print "OverDueFees = " . $OverdueFees['OverdueFees'];
		print "<br>";
		print "<br>";

   } else {

   $inv = dbWrite("insert into invoice (memid,currentfees,currentpaid,overduefees,date) values ('".$MemRow['memid']."','".$CurrentFeesOwe['CurrentFees']."','".$CurrentPaid."','".$OverdueFees['OverdueFees']."','".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."')","etradebanc",true);

   //if($CRow['countryID'] == 15 && $CurrentFeesOwe['CurrentFees'] > 0) {
   if($CRow['countryID'] == 15) {
   	   $newFees = $MemRow['ac_fees']+$CurrentFeesOwe['CurrentFees'];
	   if($newFees >= 20) {
	     //$inv = dbWrite("insert into invoice_es (inv_type,inv_memid,inv_link,inv_desc,inv_amount,inv_tax,inv_date) values ('1','".$MemRow['memid']."','".$inv."','Transaction Fees','".$CurrentFeesOwe['CurrentFees']."','".$CRow['tax']."','".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."')","etradebanc",true);
	     $inv = dbWrite("insert into invoice_es (inv_type,inv_memid,inv_link,inv_desc,inv_amount,inv_tax,inv_date) values ('1','".$MemRow['memid']."','".$inv."','Transaction Fees','".$newFees."','".$CRow['tax']."','".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."')","etradebanc",true);
	     dbWrite("update members set ac_fees = 0 where  memid = '".$MemRow['memid']."'");
	   } else {
	     dbWrite("update members set ac_fees = '".$newFees."' where  memid = '".$MemRow['memid']."'");
	   }
   }
   //dbWrite("insert into invoice (memid,currentfees,currentpaid,overduefees,date) values ('".$MemRow['memid']."','".$CurrentFeesOwe['CurrentFees']."','".$CurrentPaid['CurentPaid']."','".$OverdueFees."','2004-08-31')");
   //print "insert into invoice (memid,currentfees,currentpaid,overduefees,date) values ('".$MemRow['memid']."','".$CurrentFeesOwe['CurrentFees']."','".$CurrentPaid['CurentPaid']."','".$OverdueFees['OverdueFees']."','2004-07-31')<br>";
   }
  }

 }

 print "</pre>";

?>