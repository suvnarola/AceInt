<?

// Administration Fees.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

//$day = date("j");
//$month_1 = date("n");
//$year1 = date("Y");

$day = date("j");
$month_1 = date("m", mktime(0,0,0,date("m")-1,1,date("Y")));
$year1 = date("Y", mktime(0,0,0,date("m"),1,date("Y")));

$prevyear = date("Y", mktime(0,0,0,$month_1-1,1,$year1));
$prevmonth = date("n", mktime(0,0,0,$month_1-1,1,$year1));
$usdate = date("Y-m-d", mktime(0,0,0,$month_1,1-1,$year1));
$ussdate = date("Y-m-d", mktime(0,0,0,$month_1-1,1,$year1));

$trandate = mktime(20,1,1,$month_1,28,$year1);
$transdis_date = date("Y-m-d", $trandate);

$pre_month = date("Y-m-d", mktime(0,0,0,$month_1,1,$year1));

$transDetails = "Late Payment Fee";

#insert stationery fees!
//$dbgetmemwithfees = dbRead("select members.memid, members.datejoined, members.CID, members.licensee, country.admin_fee, members.fee_deductions, country.reserveacc, country.DefaultArea, country.reserveacc from members, country, status where (members.CID = country.countryID) and (members.status = status.FieldID) and members.admin_exempt = 0 and status.mem_admin_fee = 1 and country.a_fee = 'Y' and country.admin_fee > 0 and (wagesacc = 0 or wagesacc is null) and members.datejoined < '$pre_month'");
//$dbgetmemwithfees = dbRead("select invoice.*,  members.companyname,  members.status, members.fee_deductions, country.*, status.* from invoice, members, country, status where invoice.memid = members.memid and (members.CID = country.countryID) and (members.status = status.FieldID) and invoice.date = '".$usdate."' and members.CID = 1 and members.status in (0,5) and members.memid not in (11342,10705,28443,26420,12362,13945,30093,29054,14453,13393,18996,11575,28405,30828,29864) and members.datejoined < '$pre_month'");
$dbgetmemwithfees = dbRead("select invoice.*,  members.companyname,  members.status, members.fee_deductions, country.*, status.* from invoice, members, country, status where invoice.memid = members.memid and (members.CID = country.countryID) and (members.status = status.FieldID) and invoice.date = '".$usdate."' and members.CID = 0 and members.status in (0,5) and members.memid not in (11342,10705,28443,26420,12362,13945,30093,29054,14453,13393,18996,11575,28405,30828,29864) and members.datejoined < '$pre_month'");

while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

    //$dbgetbal = dbRead("select sum(sell) as sell, sum(buy) as buy from transactions where memid='$row[memid]' and transactions.dis_date >= '$ussdate' and transactions.dis_date <= '$usdate' and to_memid not in (".get_non_included_accounts($row['countryID']).")");
    //$traderow = mysql_fetch_assoc($dbgetbal);

	$query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.memid= $row[memid] and to_memid !=16083 and dollarfees <'0' and dis_date > '$usdate'");
	$row1 = mysql_fetch_assoc($query1);

	//if(($row[overduefees]+$row[currentpaid]+$row[currentfees]+$row1[fees]) < 20 && ($row[overduefees]+$row[currentpaid]+$row[currentfees]+$row1[fees]) > 5) {
	if(($row[overduefees]+$row[currentpaid]+$row[currentfees]+$row1[fees]) > 20) {



    //if($traderow[sell] > 0 || $traderow[buy] > 0 || $row[overduefees]+$row[currentpaid]+$row1[fees] > 5) {
    //if($traderow[sell] > 0 || $traderow[buy] > 0) {


	 //$hs = 0;
	 //$hs = ($row[overduefees]+$row[currentpaid]+$row[currentfees]);
	 //$hs = ($hs-abs($row1['fees']));
	 //$hhh = $row[currentfees]+$row1[fees];

//echo $row[memid]."<br>";
//echo $row[memid]."-".abs($row1['fees'])."<BR>";


		#insert stationery
		$aa = auth_no();
		$transID = dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values  ('".$row['memid']."','".$trandate."','".$row['reserveacc']."','0','0','0','19.00','3','".$transDetails."','".$aa."','".$transdis_date."','0','180')",'etradebanc',true);

	    $UpdateAmount = $row['fee_deductions']+19;
	    dbWrite("update members set fee_deductions = " . $UpdateAmount . " where memid = " . $row['memid']);

echo $row[memid]."-".$row[companyname]."-".number_format($row['fee_deductions'], 2)."-".$UpdateAmount."<br>";
		$s++;

	 //}
	}

}
	print $s;

?>