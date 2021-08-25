<?

// Stationery Fees.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

$day = date("j");
$month_1 = date("n");
$year1 = date("Y");
$prevyear = date("Y", mktime(0,0,0,$month_1-1,1,$year1));
$prevmonth = date("n", mktime(0,0,0,$month_1-1,1,$year1));

$trandate = mktime(20,1,1,$month_1,1-1,$year1);
$transdis_date = date("Y-m-d", $trandate);

$pre_month = date("Y-m-d", mktime(0,0,0,$month_1-1,1,$year1));

$transDetails = "Directory Charge";

#insert stationery fees!
$dbgetmemwithfees = dbRead("select members.memid, members.monthlyfeecash, members.fee_deductions, country.reserveacc from members, country, mem_categories where (members.CID = country.countryID) and (members.memid = mem_categories.memid) and area in (1,26,28) and mem_categories.category not in (2783,2784,270) and members.status = 0 group by mem_categories.memid having (sum(mem_categories.category) > 0) ");
//$dbgetmemwithfees = dbRead("select members.memid, members.monthlyfeecash, country.reserveacc from members, country, status where (members.CID = country.countryID) and (members.status = status.FieldID) and (status.FieldID = 0) and members.datejoined < '$pre_month' and members.area in (632,68,70,69,76,370)");
while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

	#insert stationery
	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values  ('".$row['memid']."','".$trandate."','".$row['reserveacc']."','0','0','0','2.00','3','".$transDetails."','321','".$transdis_date."','0','180')");

	#update feesowing
	//dbWrite("update feesowing set numfeesowing=numfeesowing+1 where memid='".$row['memid']."'");
	$newfee = $row['fee_deductions']+$row['monthlyfeecash'];

	/**

	if($row['over_payment']) {
	
		if($row['over_payment'] > 2) {
		
			dbWrite("update members set over_payment = (over_payment - 2) where memid='".$row['memid']."'");
		
		} else {
		
			dbWrite("update members set over_payment = 0 where memid='".$row['memid']."'");
			dbWrite("update members set fee_deductions = (fee_deductions + (2 - " . $row['over_payment'] . ")) where memid='".$row['memid']."'");
			
		}
	
	} else {

	**/

		dbWrite("update members set fee_deductions = (fee_deductions + 2) where memid='".$row['memid']."'");

	/**

	}

	**/

}

?>