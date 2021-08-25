<?

// Update Hits.

include("/home/etxint/admin.etxint.com/includes/global.php");

$fd = file("/virt/web-01/stats/www.ebanctrade.com/webalizer.hist");

foreach($fd as $key => $value) {

 $Temp = explode(" ", $value);
 
 $Hits += $Temp[2];
 
}

dbWrite("update tbl_hits set Hits = '$Hits'");