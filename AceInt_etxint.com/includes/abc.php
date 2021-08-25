<?

 include("/home/etxint/admin.etxint.com/includes/global.php");
 
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 $date = date("dmY", mktime(0,0,0,date("m"),date("d"),date("Y"))); 
 
 $query = dbRead("select * from transactions where memid = '12056' and details like '%transfer%'");

 #loop around
 while($row = mysql_fetch_assoc($query)) {
  //echo $row[to_memid]; 
  
  $query2 = dbRead("select sum(sell-buy) as balance from transactions where memid = '".$row[to_memid]."' group by memid");
  $row2=mysql_fetch_array($query2);
  
  if($row2[balance] > $row[buy])  { 
    echo $row[to_memid].", ".$row2[balance].", ".$row[buy].", ".$row[details].", ".$row[dis_date]."<br>";
  }
   
 }

?>