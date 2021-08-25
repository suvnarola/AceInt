<?

//include("/home/etxint/admin.etxint.com/includes/global.php");
include_once("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
include_once("/home/etxint/admin.etxint.com/includes/modules/functions.transaction.php");
include_once("/home/etxint/admin.etxint.com/includes/modules/class.transaction.php");

#get the members facility out.
$query = dbRead("select overdraft from members where memid='$memid'");
$row = mysql_fetch_array($query);

$querybuy = dbRead("select * from country, members where (country.countryID = members.CID) and members.memid='$memid'");
$rowbuy = mysql_fetch_array($querybuy);

#check to see what facility they have now.
$query2= dbRead("select (sum(sell)-sum(buy)) as curfacility from transactions where memid='$memid' and (to_memid='".$_SESSION['Country']['facacc']."')");
$row2=mysql_fetch_array($query2);

//if($row['overdraft'] > $row2[curfacility]) {
if($_SESSION['Country']['countryID'] != 12) {
  dbWrite("update members set maxfacility = '".$row['overdraft']."' where memid = '$memid'");
}
//}

$difference=$row[overdraft]-$row2[curfacility];

if($difference != 0) {

 #insert facility
 $t=mktime();
 $t2=$t-951500000;
 $t3=mt_rand(1000,9000);
 $authno=$t2-$t3;
 $disdate=date("Y-m-d",$t);

 #if total facility is more than the maxtransfer make the new facility uncleared.
 if($_SESSION['User']['MaxTransfer'] >= $row[overdraft]) {

  $checked="0";

  if($_SESSION['User']['CID'] == 12) {

   $userArray = array('789' => 'john.k@ebanctrade.com','286' => 'john.k@ebanctrade.com','287' => ' 	joseph.t@ebanctrade.com','913' => 'maria.t@ebanctrade.com');

   switch($_SESSION['User']['FieldID']) {

   	default: break;

   }

  }

 } else {
  $checked="1";
 }

 if(checkmodule("SuperUser")) {
  $checked="0";
 }

 if($ClearTrans) {
  $checked="0";
 }

 if($difference > 0) {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($rowbuy['facacc']);
		$Transfer->AddTo($memid);
		$Transfer->AddDate(date("Y-m-d"));
		$Transfer->AddAmount($difference, $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddWho($_SESSION['User']['FieldID']);
		$Transfer->AddDetails("Facility");
		$Transfer->MultiCheck("0");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");
 			dbWrite("insert into tbl_members_facility (acc_no,date,facility_type,facility_amount,user_id) values ('$memid','$disdate','1','".$difference."','".$_SESSION['User']['FieldID']."')");


     	} else {

     		print_r($Transfer->Errors) .",";

     	}
 	//dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('".$rowbuy['facacc']."','$t','$memid','$difference','0','0','1','Facility','$authno','$disdate','0','".$_SESSION['User']['FieldID']."')");
 	//dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','".$rowbuy['facacc']."','0','$difference','0','2','Facility','$authno','$disdate','$checked','".$_SESSION['User']['FieldID']."')");
 	//dbWrite("insert into tbl_members_facility (acc_no,date,facility_type,facility_amount,user_id) values ('$memid','$disdate','1','".$difference."','".$_SESSION['User']['FieldID']."')");

 } else {

		$Transfer = new FundsTransfer();

		$Transfer->AddFrom($memid);
		$Transfer->AddTo($rowbuy['facacc']);
		$Transfer->AddDate(date("Y-m-d"));
		$Transfer->AddAmount(abs($difference), $Transfer->FromCountry['convert']);

		$chargeFeesBuyer = ($Transfer->FromRow['feescharge'] == "Buy") ? "1" : "0";
		$chargeFeesSeller = ($Transfer->ToRow['feescharge'] == "Sell") ? "1" : "0";

		//$Transfer->AddFees("Buyer", $Transfer->FromRow['transfeecash'], $chargeFeesBuyer);
		//$Transfer->AddFees("Seller", $Transfer->ToRow['transfeecash'], $chargeFeesSeller);
		$Transfer->AddFees("Buyer", 0, 0);
		$Transfer->AddFees("Seller", 0, 0);
		$Transfer->AddWho($_SESSION['User']['FieldID']);
		$Transfer->AddDetails("Facility");
		$Transfer->MultiCheck("0");

     	if(!$Transfer->Errors) {

     		/**
     		 * No errors put transaction through.
     		 */

     		$Transfer->DOTransfer("1");
    $amount = abs($difference);
    $queryf = dbRead("select * from tbl_members_facility where acc_no = '$memid' and facility_type = 1 having (facility_amount - facility_repay) != 0 order by date");
    while($rowf = mysql_fetch_array($queryf)) {
     if(($rowf['facility_amount']-$rowf['facility_repay']) < $amount) {
       dbWrite("update tbl_members_facility set facility_repay = '".$rowf['facility_amount']."' where FieldID = ".$rowf['FieldID']."");
	   $amount = $amount-($rowf['facility_amount']-$rowf['facility_repay']);
     } else {
       $newamount = ($rowf['facility_repay']+$amount);
       dbWrite("update tbl_members_facility set facility_repay = '".$newamount."' where FieldID = ".$rowf['FieldID']."");
	   $amount = 0;
     }
    }

     	} else {

     		print_r($Transfer->Errors) .",";

     	}

 	//dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('".$rowbuy['facacc']."','$t','$memid','0','".abs($difference)."','0','2','Facility','$authno','$disdate','0','".$_SESSION['User']['FieldID']."')");
 	//dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','".$rowbuy['facacc']."','".abs($difference)."','0','0','1','Facility','$authno','$disdate','$checked','".$_SESSION['User']['FieldID']."')");
    //$amount = abs($difference);
    //$queryf = dbRead("select * from tbl_members_facility where acc_no = '$memid' and facility_type = 1 having (facility_amount - facility_repay) != 0 order by date");
    //while($rowf = mysql_fetch_array($queryf)) {
     //if(($rowf['facility_amount']-$rowf['facility_repay']) < $amount) {
       //dbWrite("update tbl_members_facility set facility_repay = '".$rowf['facility_amount']."' where FieldID = ".$rowf['FieldID']."");
	   //$amount = $amount-($rowf['facility_amount']-$rowf['facility_repay']);
     //} else {
       //$newamount = ($rowf['facility_repay']+$amount);
       //dbWrite("update tbl_members_facility set facility_repay = '".$newamount."' where FieldID = ".$rowf['FieldID']."");
	   //$amount = 0;
     //}
    //}
 }

}

?>
