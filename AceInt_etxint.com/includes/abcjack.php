<?

 include("/home/etxint/admin.etxint.com/includes/global.php");

 
 $query = dbRead("select * from members, tbl_area_physical, tbl_area_regional where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and StateID = '8'");
 $count = 0;
 #loop around
 while($row = mysql_fetch_assoc($query)) {
 
    $count++;
    $fee = $row['fee_deductions']+3;
    dbWrite("insert into transactions (memid,date,to_memid,dollarfees,type,details,authno,dis_date,checked,userid) values ('".$row['memid']."','1113487200','9845','3','3','Directory Charge','1625".$row[memid]."','2005-04-15','0','180')");
    dbWrite("update members set fee_deductions = ".$fee." where memid=".$row['memid']."");
   
 }
 
 print $count;
?>