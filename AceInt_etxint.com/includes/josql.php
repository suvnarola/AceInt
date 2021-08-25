<?

$jopos = dbRead("select Position2 from tbl_admin_users where (Position2 != null or Position2 != '0') group by Position2 order by Position2");

?>