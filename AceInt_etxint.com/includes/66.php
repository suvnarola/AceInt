<?

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");


	//$cashFeesSQL = dbRead("select * from members where CID = 1 and sms = 5 and memid != 22311");
	$cashFeesSQL = dbRead("select * from members where CID = 1 and memid in (11797)");
	while($typeRow = mysql_fetch_assoc($cashFeesSQL)) {

	//if($_REQUEST[feereverse]) {

 		$amount = 66;
 		$memberacc = $typeRow['memid'];

		if($amount) {

			$authno=mt_rand(1000000,99999999);
			$t=mktime();
			$d=date("Y-m-d");
			#insert transaction

				// normal reversal.

			 	$ebancAdmin = new ebancSuite();

			 	$feePay = new feePayment($memberacc);

				$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $amount, '', 'Reversal Re Prom');

		}

	}

?>