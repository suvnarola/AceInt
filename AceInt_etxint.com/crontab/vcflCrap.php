<?

	$NoSession = true;
 
	include("/home/etxint/admin.etxint.com/includes/global.php");
	include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
	include("/home/etxint/admin.etxint.com/includes/modules/functions.transaction.php");
	include("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");
	
	$today_date = date("Y-m-d");

	$vcflSQL = dbRead("select members.* from members, mem_categories where (mem_categories.memid = members.memid) and mem_categories.description like '%vcfl%' order by members.memid");
	while($vcflObj = mysql_fetch_object($vcflSQL)) {
		
		$Transfer = new FundsTransfer();
		
		$Transfer->AddFrom("25877");
		$Transfer->AddTo($vcflObj->memid);
		$Transfer->AddDate($today_date);
		$Transfer->AddAmount("2000.00", $Transfer->FromCountry['convert']);
		
		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";
		
		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho("180");
		$Transfer->AddDetails("Annual Installment");
		$Transfer->MultiCheck("4");
		
		if(!$Transfer->Errors) {
		
			/**
			 * No errors put transaction through.
			 */ 
		
			$Transfer->DOTransfer("1");
		
		}

	}

?>