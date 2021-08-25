<?
 include("/home/etxint/admin.etxint.com/includes/global.php");

 $query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(starts) as starts, UNIX_TIMESTAMP(ends) as ends from tbl_auction_auctions where id = '2078'");
 //$row = mysql_fetch_assoc($query);

print $row[starts];

  $starts = date("YmdHis", $row[starts]);


  print "-".$starts."-";

  $y = substr($extratime, 0, 4);
  $m = substr($extratime, 4, 2);
  $d = substr($extratime, 6, 2);
  $h = substr($extratime, 8, 2);
  $min = substr($extratime, 10, 2);
  $s = substr($extratime, 12, 2);

  $new_ends = date("YmdHis", mktime($h,$min,$s+300,$m,$d,$y));
$month_1 = date("n");
print $month_1;
  //print $new_ends;
?>