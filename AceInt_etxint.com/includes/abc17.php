<?
 include("/home/etxint/admin.etxint.com/includes/global.php");


 //$query = dbRead("select * from transactions where memid = 27384 and dis_date = '2006-11-21'");
 $query = dbRead("select * from registered_accounts where Refund > 0 ","ebanc_services");

 while($row = mysql_fetch_assoc($query)) {

	 $t = mktime();
	 $t2 = $t-951500000;
	 $t3 = mt_rand(1000,9000);
	 $authno = $t2-$t3;
	 
	 $bank=explode("-",$row['details'], 2);
  	 $bank=trim($bank[1]); 
  
     //dbWrite("insert into transactions (regAccID,sell,type_id,details,receipt,dis_date) values ('".$bank."','-".$row['buy']."','1','Trade Refund - ".$bank."','".$authno."','2006-11-21')","ebanc_services");
     dbWrite("insert into transactions (regAccID,sell,type_id,details,receipt,dis_date) values ('".$row['FieldID']."','-".$row['Refund']."','1','Part Cash Refund - ".$row['FieldID']."','".$authno."','2006-11-30')","ebanc_services");


 }

 print $dd;
 print $counter;
?>