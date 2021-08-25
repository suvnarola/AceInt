<?

// Jims Net Fee Update.

include("/home/etxint/admin.etxint.com/includes/global.php");

#get jimsnet members out of the database.
$query = dbRead("select * from jimsnet where changed = 'N' and sorted = 'Y'");

while($row = mysql_fetch_assoc($query)) {

 #get transactions out for each jimsnet member.
 $query2 = dbRead("select sum(buy) as jimsbuy from transactions where memid='$row[memid]'");
 $row2 = mysql_fetch_assoc($query2);

 #check to see if they have spent more than their opening balance.
 if($row2[jimsbuy] >= $row[openbalance]) {

  #if they have, change their fees to 4.95%.
  dbWrite("update members set transfeecash='4.95' where memid='$row[memid]'");
  dbWrite("update jimsnet set changed='Y' where memid='$row[memid]'");

  #email me to tell me whats happened
  mail("dave@ebanctrade.com", "Jims Net Member Changed - $row[memid]", ".");

 }

}

?>