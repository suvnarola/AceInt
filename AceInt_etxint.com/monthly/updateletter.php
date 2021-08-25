<?

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

$Debug = false;

$date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

$query = dbRead("select members.memid, invoice.currentpaid, invoice.overduefees, invoice.currentfees, members.status, members.CID, country.letteramount from members, invoice, country where members.memid=invoice.memid and members.CID = country.countryID and members.letters !=0 and invoice.date = '$date2'");
while($row = mysql_fetch_assoc($query)) {

 $query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.memid= $row[memid] and to_memid !=16083 and dollarfees <'0' and dis_date > '$date2'");
 $row1 = mysql_fetch_assoc($query1);

 if($row['CID'] == 12) {
  $out = $row[overduefees]+$row[currentpaid]+$row[currentfees]+$row1[fees];
 } else {
  $out = $row[overduefees]+$row[currentpaid]+$row1[fees];
 }

 if($out < $row[letteramount]) {

  if($Debug) {

   echo "<pre>";
   echo "$row[memid] $out";
   echo "</pre>";

  } else {

   dbWrite("update members set status = '0' where memid = '$row[memid]' and status = '5'");
   dbWrite("update members set status = '0' where memid = '$row[memid]' and status = '6'");

   dbWrite("update members set letters = '0' where memid = '$row[memid]'");

  }

 }

}
