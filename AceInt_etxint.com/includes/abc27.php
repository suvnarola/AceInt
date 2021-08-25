<?
 include("/home/etxint/admin.etxint.com/includes/global.php");

 //$query = dbRead("select * from tbl_members_log where FieldID = 700315","log");
 //$row = mysql_fetch_assoc($query);

 //print long2ip(2147483647)."<br>";
 //print ip2long($row[IPAddress]);
$t=0;
$b=0;
$s=0;
 $query = dbRead("select * from transactions where memid = 28895 and sell >0");
 while($row = mysql_fetch_assoc($query)) {

	$query2 = dbRead("select * from transactions where memid = 28895 and buy > 0 and details like '%".addslashes($row['id'])."%'");
 	$row2 = mysql_fetch_assoc($query2);

	if(!$row2['memid']) {

//$b = $b+$row2[buy];
$s = $s+$row[sell];
		$cc++;
		$t=$t+$row[sell];
		print $row[dis_date]."-".$row[to_memid]."-".$row[id]."-".$row[details]."-".$row[sell]."-".$row2[buy]."-".$row2[memid]."<br>";

	$query3 = dbRead("select * from transactions where memid = 28895 and buy > 0 and details like '%".addslashes($row['id'])."%'");
 	while($row3 = mysql_fetch_assoc($query3)) {

		print " - ".$row3[memid]."-".$row3[dis_date]."-".$row3[to_memid]."-".$row3[id]."-".$row3[details]."-".$row3[buy]."<br>";
$b = $b+$row3[buy];

	}


	}
 }
//print $t;
print $cc;
print "<br>";
//print get_non_included_accounts(1, true,false,false,true);
print "<br>". $s;
print "<br>".$b;

$bb =0;
 $query3 = dbRead("select * from transactions where memid = 28895 and buy > 0 and details like '%]'");
 while($row3 = mysql_fetch_assoc($query3)) {

 $bb = $bb+$row3[buy];

 }
 print "- <br>". $bb;
?>