<?

#get the members facility out.
$query=mysql_db_query($db, "select reoverdraft from members where memid='$memid'", $linkid);
$row=mysql_fetch_array($query);

$querybuy = dbRead("select * from country, members where (country.countryID = members.CID) and members.memid='$memid'");
$rowbuy = mysql_fetch_array($querybuy);

#check to see what facility they have now.
$query2=mysql_db_query($db, "select (sum(sell)-sum(buy)) as curfacility from transactions where memid='$memid' and (to_memid='".$_SESSION['Country']['refacacc']."')", $linkid);
$row2=mysql_fetch_array($query2);

$difference=$row[reoverdraft]-$row2[curfacility];

if($difference != 0) {

 #insert facility
 $t=mktime();
 $t2=$t-951500000;
 $t3=mt_rand(1000,9000);
 $authno=$t2-$t3;
 $disdate=date("Y-m-d",$t);
 
 #if total facility is more than the maxtransfer make the new facility uncleared.
 if($_SESSION['User']['MaxTransfer'] >= $row[reoverdraft]) {
  $checked="0";
 } else {
  $checked="1";
 }
 
 if(checkmodule("SuperUser")) {
  $checked="0";
 }
 
 if($difference > 0) {
 
 	mysql_db_query($db, "insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('".$rowbuy['refacacc']."','$t','$memid','$difference','0','0','1','Facility','$authno','$disdate','0','".$_SESSION['User']['FieldID']."')", $linkid);
 	mysql_db_query($db, "insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','".$rowbuy['refacacc']."','0','$difference','0','2','Facility','$authno','$disdate','$checked','".$_SESSION['User']['FieldID']."')", $linkid);
 	dbWrite("insert into tbl_members_facility (acc_no,date,facility_type,facility_amount,user_id) values ('$memid','$disdate','2','".$difference."','".$_SESSION['User']['FieldID']."')");

 } else {
 
 	mysql_db_query($db, "insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('".$rowbuy['refacacc']."','$t','$memid','0','".abs($difference)."','0','2','Facility','$authno','$disdate','0','".$_SESSION['User']['FieldID']."')", $linkid);
 	mysql_db_query($db, "insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','".$rowbuy['refacacc']."','".abs($difference)."','0','0','1','Facility','$authno','$disdate','$checked','".$_SESSION['User']['FieldID']."')", $linkid);
    $amount = abs($difference);
    $queryf = dbRead("select * from tbl_members_facility where acc_no = '$memid' and facility_type = 2 having (facility_amount - facility_repay) != 0 order by date");
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
 }

}

?>