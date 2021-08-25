<?
 include("/home/etxint/admin.etxint.com/includes/global.php");


 $query = dbRead("select * from members where CID = 1");

 while($row = mysql_fetch_assoc($query)) {

	 
	 //$bank=explode(" ",$row['accholder']);
  	 //$bank=array_reverse($bank); 
  
     //dbRead("update members set accholder_surname = '".addslashes(encode_text2($bank[0]))."' where memid = ".$row['memid']."");
     //print $row[accholder]." - ".$bank[0]?><br><?;

     $bank = str_replace($row['accholder_surname'], " ", $row['accholder_first']);
     $bank = trim($bank);

     dbRead("update members set accholder_first = '".addslashes(encode_text2($bank))."' where memid = ".$row['memid']."");
     //print $row[accholder]." - ".$bank?><br><?;

 }

 print $dd;
 print $counter;
?>