<?

// Administration Fees.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

$day = date("j");
$month_1 = date("n");
$year1 = date("Y");
$prevyear = date("Y", mktime(0,0,0,$month_1-1,1,$year1));
$prevmonth = date("n", mktime(0,0,0,$month_1-1,1,$year1));
$usdate = date("Y-m-d", mktime(0,0,0,$month_1-3,1,$year1));

$trandate = mktime(20,1,1,$month_1,28,$year1);
$transdis_date = date("Y-m-d", $trandate);

$pre_month = date("Y-m-d", mktime(0,0,0,$month_1,1,$year1));

$transDetails = "Administration Fee";

#insert stationery fees!
//$dbgetmemwithfees = dbRead("select members.memid, members.datejoined, members.CID, members.licensee, country.admin_fee, members.fee_deductions, country.reserveacc, country.DefaultArea, country.reserveacc from members, country, status where (members.CID = country.countryID) and (members.status = status.FieldID) and members.admin_exempt = 0 and status.mem_admin_fee = 1 and country.a_fee = 'Y' and country.admin_fee > 0 and (wagesacc = 0 or wagesacc is null) and members.datejoined < '$pre_month'");
$dbgetmemwithfees = dbRead("select members.memid, members.datejoined, members.CID, members.fiftyclub, members.paymenttype, members.licensee, members.admin_exempt, country.admin_fee, members.fee_deductions, country.reserveacc, country.adminacc, country.DefaultArea from members, country, status where (members.CID = country.countryID) and (members.status = status.FieldID) and (members.admin_exempt = 2 or members.admin_exempt = 0) and status.mem_admin_fee = 1 and country.a_fee = 'Y' and country.admin_fee > 0 and fiftyclub = 2 and (wagesacc = 0 or wagesacc is null) and members.datejoined < '$pre_month'");
//$dbgetmemwithfees = dbRead("select members.memid, members.datejoined, members.CID, members.fiftyclub, members.paymenttype, members.licensee, country.admin_fee, members.fee_deductions, country.reserveacc, country.adminacc, country.DefaultArea from members, country, status where (members.CID = country.countryID) and (members.status = status.FieldID) and members.admin_exempt = 0 and members.CID = 1 and status.mem_admin_fee = 1 and country.a_fee = 'Y' and country.admin_fee > 0 and fiftyclub = 2 and (wagesacc = 0 or wagesacc is null) and members.datejoined < '$pre_month'");

while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

	$adfee = 0;

	if($row['CID'] == 1) {

		if($row['fiftyclub'] == 1 && $row['paymenttype'] != 0 && $row['datejoined'] < '2007-09-10') {
			//$adfee = $row['admin_fee']/3;
		} elseif($row['fiftyclub'] == 1 && $row['paymenttype'] != 0 && $row['datejoined'] > '2007-09-10') {
			//$adfee = $row['admin_fee']/2;
		} elseif($row['paymenttype'] != 0 && $row['datejoined'] > '2007-09-10') {
			//$adfee = ($row['admin_fee']/3)*2;
		} elseif($row['paymenttype'] != 0 && $row['datejoined'] < '2007-09-10') {
			//$adfee = ($row['admin_fee']/2);
		} elseif($row['datejoined'] < '2007-09-10') {
			//$adfee = ($row['admin_fee']/3)*2;
		} else {
			//$adfee = $row['admin_fee'];
		}

      if($row['admin_exempt'] == 0) {
       if($month_1 == 6) {
        if($row['fiftyclub'] == 2) {
         $adfee = 105.60;
		} else {
         $adfee = 158.40;
		}

		if($row['paymenttype'] = 0) {
			//$adfee = 249;
		} else {
			//$adfee = 199;
		}
	   }

	  } elseif($row['admin_exempt'] == 2) {
		//if($row['fiftyclub'] == 2 && $row['paymenttype'] != 0) {
		if($row['fiftyclub'] == 2) {
		 $adfee = ($row['admin_fee']/3)*2;
		} else {
		 $adfee = $row['admin_fee'];
		}
	  }

    	//print $row[memid]." - ".$row[fiftyclub]." - ".$row[paymenttype]." - ".$row[datejoined]." - ".$adfee."<br>";
	} elseif($row['CID'] == 4) {

		if($row['datejoined'] < $usdate) {
			$adfee = $row['admin_fee'];
		}

	} else {

		//$adfee = $row['admin_fee'];

	}

	#insert stationery
	$aa = auth_no();
	//$transID = dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values  ('".$row['memid']."','".$trandate."','".$row['adminacc']."','0','0','0','".$row['admin_fee']."','3','".$transDetails."','".$aa."','".$transdis_date."','0','180')",'etradebanc',true);
    //dbWrite("insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id,percent,to_percent) values ('".$row['memid']."','".$row['licensee']."','".$transdis_date."','".$row['adminacc']."','".$row['DefaultArea']."','".$row['admin_fee']."','".$transID."','50','0')");
	if($adfee > 0) {
	  $transID = dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values  ('".$row['memid']."','".$trandate."','".$row['adminacc']."','0','0','0','".$adfee."','3','".$transDetails."','".$aa."','".$transdis_date."','0','180')",'etradebanc',true);
      dbWrite("insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id,percent,to_percent) values ('".$row['memid']."','".$row['licensee']."','".$transdis_date."','".$row['adminacc']."','".$row['DefaultArea']."','".$adfee."','".$transID."','50','0')");
	}

}

?>