<?

// Update Fake Id's For The Front WebSite.

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

dbWrite("truncate table memids");

dbWrite("insert into memids (memid) select memid from members order by memid");

?>