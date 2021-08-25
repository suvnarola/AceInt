<?
 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/db.php");

  //$Query10 = dbRead("select count(memid) as MemCount, extract(year_month from members.datejoined) as date1 from members where area in (1,26,28) group by date1");
  //$Query10 = dbRead("select count(memid) as MemCount, extract(year_month from members.datedeactivated) as date1 from members where area in (1,26,28) and status = 1 group by date1");
  $Query10 = dbRead("select count(memid) as MemCount, AreaName from members, tbl_area_physical where (members.area = tbl_area_physical.FieldID) and status = 1 and members.CID = 1 group by area order by AreaName");

  while($Row10 = mysql_fetch_assoc($Query10)) {

   //if($Row10['date1'] != 0) {

    //$YearArray[$Row10[date1]][$ARow['FieldID']]['MemCountPaid'] = $Row10['MemCount'];
    //echo $Row10[date1]." - ".$Row10['MemCount'];
    echo $Row10['AreaName']." - ".$Row10['MemCount'];
    ?><br><?

   //}

  }
?>