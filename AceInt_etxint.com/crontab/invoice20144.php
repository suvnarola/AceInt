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
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");
 /**
  * Loop around the Countries.
  */

 //print "<pre>";

 $CSQL = dbRead("select country.* from country where countryID = 1 order by countryID");
 while($CRow = mysql_fetch_assoc($CSQL)) {
$OverdueFees = 0;
$fff=0;
  $CMembers = dbRead("select memid, ac_fees from members where ((datejoined < '".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."' and status != '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1-1,date("Y")))."-%' and status = '1') or (datedeactivated like '".date("Y-m", mktime(0,0,0,date("n"),1,date("Y")))."-%' and status = '1')) and CID = '".$CRow['countryID']."' and memid not in(10705,10526,16477,18754,11446,18996,12311)");
  while($MemRow = mysql_fetch_assoc($CMembers)) {
   $ff = 0;
   $ffff = 0;

   $CurrentPaidSQL = dbRead("SELECT SUM(dollarfees ) as CurrentPaid FROM transactions WHERE ((((memid = ".$MemRow['memid']." ) AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true).")) AND (date >= ".mktime(0,0,0,7,1,2014)." ) AND (date < ".mktime(0,0,0,7,1,2015)." ) ) ) AND (dollarfees < 0 ) )");
   $CurrentPaid = mysql_fetch_assoc($CurrentPaidSQL);

   $OverdueFeesSQL = dbRead("SELECT Sum(transactions.dollarfees) AS OverdueFees FROM transactions WHERE memid='".$MemRow['memid']."' AND date < ".mktime(0,0,0,7,1,2014)." AND (to_memid NOT IN (".get_non_included_accounts($CRow['countryID'],true,false,false,true)."))");
   $OverdueFees = mysql_fetch_assoc($OverdueFeesSQL);

   $ff = $OverdueFees['OverdueFees'] + $CurrentPaid['CurrentPaid'];

   if($OverdueFees['OverdueFees'] > 0 && $ff > 0) {
   $fff = $fff + $ff;

   if($_REQUEST['Debug']) {

		print "<br>";
		print $ff." - ".$MemRow['memid'];
		print "<br>";

   } else {

    // Insert reversal
 	$amount = $ff;
 	$memberacc = $MemRow['memid'];

	$authno=mt_rand(1000000,99999999);
	$t=mktime();
	$d=date("Y-m-d");

	$ebancAdmin = new ebancSuite();

	$feePay = new feePayment($memberacc);


	$cashFeesSQL = dbRead("select sum(fee_amount-fee_paid) as cashFees from feesincurred where memid = " . $memberacc ." and to_memid = ".$_SESSION['Country']['adminacc']." and percent = 50");
	$cashFeesRow = mysql_fetch_assoc($cashFeesSQL);

    //$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $amount, '', 'Reversal of Fees');
	$feePay->payFees($_SESSION['feePayment']['memberRow'], $amount, 1, 6, false, 'Reversal of Fees');

    // update invoice
    $eesql = dbRead("select currentpaid as currentpaid from invoice where date = '2015-06-30' and memid = ".$MemRow['memid']."");
	$eerow = mysql_fetch_assoc($eesql);
    $ffff = $eerow['currentpaid'] - $amount;
    dbWrite("update invoice set currentpaid = ".$ffff." where date = '2015-06-30' and memid = ".$MemRow['memid']."");


    //$inv = dbWrite("insert into invoice (memid,currentfees,currentpaid,overduefees,date) values ('".$MemRow['memid']."','".$CurrentFeesOwe['CurrentFees']."','".$CurrentPaid."','".$OverdueFees['OverdueFees']."','".date("Y-m-d", mktime(0,0,0,date("n"),1-1,date("Y")))."')","etradebanc",true);

   }
   }
  }
	print "TOTAL = " . $fff;
 }

 print "</pre>";

?>