<?
 include("/home/etxint/admin.etxint.com/includes/global.php");
 //include("modules/class.ebancadmin.php");
 
 $amount = 0; 
 $query = dbRead("SELECT transactions.memid, fee_deductions, Max( dis_date ) as MaxDate FROM transactions, members WHERE ( transactions.memid = members.memid ) and dis_date <= '2005-06-30' AND CID =1 AND dollarfees <0 GROUP  BY transactions.memid ORDER  BY dis_date"); 
print "<table>";	 print "<tr><td>Memid</td><td>Sum Total fees at last payement</td><td>Date of payment</td><td>stationery fees after last payment</td><td>members fees deductions</td><td>sfees before</td><td>cfees paid before</td></tr>";
 while($row = mysql_fetch_assoc($query)) { 

   $query2 = dbRead("select Sum(dollarfees) as FeeSum from transactions where memid = ".$row[memid]." and dis_date <= '".$row['MaxDate']."'"); 
   $row2 = mysql_fetch_assoc($query2);

   if($row2['FeeSum'] != 0)  {
    $query3 = dbRead("select Sum(dollarfees) as FeeSum from transactions where memid = ".$row[memid]." and type = 3 and dis_date between '".$row['MaxDate']."' and '2005-06-30'"); 
    $row3 = mysql_fetch_assoc($query3);
    if($row['fee_deduction'] != $row3['FeeSum']) { 
          		 
    $kablah = dbRead("select sum(transactions.dollarfees) as sfees from transactions where transactions.memid = ".$row[memid]." and dis_date <= '".$row['MaxDate']."' and transactions.dollarfees = '5.50'");	 
    $kablahrow = mysql_fetch_assoc($kablah);	 	 
    $kablah2 = dbRead("select sum(transactions.dollarfees) as sfees from transactions where transactions.memid = ".$row[memid]." and dis_date < '".$row['MaxDate']."' and to_memid != 16083 and transactions.dollarfees < 0");	 
    $kablahrow2 = mysql_fetch_assoc($kablah2);
    
    print "<tr><td>".$row['memid']."</td><td>".$row2['FeeSum']."</td><td>".$row['MaxDate']."</td><td>".$row3['FeeSum']."</td><td>".$row['fee_deductions']."</td><td>".$kablahrow['sfees']."</td><td>".$kablahrow2['sfees']."</td></tr>";		 
     //print $row['memid'].", ".$row2['FeeSum'].", ".$row['MaxDate'].", ".$row3['FeeSum'].", ".$row['fee_deductions']."<br>";
     $amount++;
    }
   
   } 

   if($row2['FeeSum'] == 0)  {
    $query3 = dbRead("select Sum(dollarfees) as FeeSum from transactions where memid = ".$row[memid]." and type in (3) and dis_date between '".$row['MaxDate']."' and '2005-06-30'"); 
    $row3 = mysql_fetch_assoc($query3);
    if($row3['FeeSum'] != $row['fee_deductions']) {
     if($row['fee_deductions'] != 0 && $row3[FeeSum]) {
      print $row['memid'].", ".$row3['FeeSum'].", ".$row['fee_deductions'].", ".$row['MaxDate']."<br>";
      $amount++;
      dbWrite("update members set fee_deductions = '".$row3['FeeSum']."' where memid = '".$row['memid']."'");
     }
    } 
   }  
 }
  print "</table>";
 print $amount;  
?>