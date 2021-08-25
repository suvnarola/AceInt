<?
 include("/home/etxint/admin.etxint.com/includes/global.php");

 //$query = dbRead("select * from tbl_members_log where FieldID = 700315","log");
 //$row = mysql_fetch_assoc($query);

 //print long2ip(2147483647)."<br>";
 //print ip2long($row[IPAddress]);

 $query = dbRead("select * from members, area where members.licensee = area.FieldID and priority > 0 and user = 1276 and CID = 1 order by priority");
 while($row = mysql_fetch_assoc($query)) {

  $d = explode("-", $row[date_per]);
  $todate = date("Y-m-d", mktime(0,0,1,$d[1],$d[3]+($row[priority]*7),$d[0]));


 }

?>