<?

 /**
  * Update Graph Data for Misc Stats.
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/db.php");

 //dbWrite("delete from tbl_stats_listed");

 $YearMonth = date("Ym", mktime(0,0,0,date("m")-1,1,date("Y")));
 $YearArray[$YearMonth] = array();

 //$Query = dbRead("select count(members.memid) as memid, area from members where bdriven = 'Y' and status not in (1,4) group by area order by area");
 //$Query = dbRead("select count(members.memid) as memid, area from members where bdriven = 'Y' and status not in (1,4) group by licensee order by licensee");
 $Query = dbRead("select count(members.memid) as memid, licensee as area from members where bdriven = 'Y' and status not in (1,4) group by licensee order by licensee");

 while($row = mysql_fetch_assoc($Query)) {

    $YearArray[$YearMonth][$row['area']]['bdriven'] = $row['memid'];

 }


 //$Query2 = dbRead("select count(members.memid) as memid, area from members where t_unlist = 1 and status != 1 group by area order by area");
 $Query2 = dbRead("select count(members.memid) as memid, licensee as area from members where t_unlist = 1 and status != 1 group by licensee order by licensee");

 while($row2 = mysql_fetch_assoc($Query2)) {

    $YearArray[$YearMonth][$row2['area']]['t_unlisted'] = $row2['memid'];

 }

 //$Query4 = dbRead("select count(members.memid) as memid, area from members where status = 1 group by area order by area");
 $Query4 = dbRead("select count(members.memid) as memid, licensee as area from members where status = 1 group by licensee order by licensee");

 while($row4 = mysql_fetch_assoc($Query4)) {

    $YearArray[$YearMonth][$row4['area']]['deactive'] = $row4['memid'];

 }

 //$Query5 = dbRead("select count(members.memid) as memid, area from members where status = 4 group by area order by area");
 $Query5 = dbRead("select count(members.memid) as memid, licensee as area from members where status = 4 group by licensee order by licensee");

 while($row5 = mysql_fetch_assoc($Query5)) {

    $YearArray[$YearMonth][$row5['area']]['sponsorship'] = $row5['memid'];

 }

 //$Query6 = dbRead("select count(members.memid) as memid, area from members where status = 5 group by area order by area");
 $Query6 = dbRead("select count(members.memid) as memid, licensee as area from members where status = 5 group by licensee order by licensee");

 while($row6 = mysql_fetch_assoc($Query6)) {

    $YearArray[$YearMonth][$row6['area']]['suspended'] = $row6['memid'];

 }

 //$Query7 = dbRead("select count(members.memid) as memid, area from members where status = 6 group by area order by area");
 $Query7 = dbRead("select count(members.memid) as memid, licensee as area from members where status = 6 group by licensee order by licensee");

 while($row7 = mysql_fetch_assoc($Query7)) {

    $YearArray[$YearMonth][$row7['area']]['suspended_locked'] = $row7['memid'];

 }

 $SQLQueryy = dbRead("select * from area  order by FieldID");
 while($rowy = mysql_fetch_assoc($SQLQueryy)) {

  //$Query3 = dbRead("SELECT mem_categories.memid, Sum(mem_categories.category) AS SumOfcategory FROM members, mem_categories WHERE (members.memid = mem_categories.memid) and members.area= ".$rowy[FieldID]." and status != 1 GROUP BY mem_categories.memid HAVING ((Sum(mem_categories.category))=0)");
  $Query3 = dbRead("SELECT mem_categories.memid, Sum(mem_categories.category) AS SumOfcategory FROM members, mem_categories WHERE (members.memid = mem_categories.memid) and members.licensee= ".$rowy[FieldID]." and status != 1 GROUP BY mem_categories.memid HAVING ((Sum(mem_categories.category))=0)");
  $cc = mysql_num_rows($Query3);
  $YearArray[$YearMonth][$rowy['FieldID']]['unlisted'] = $cc;

  //$Query4 = dbRead("SELECT mem_categories.memid, Sum(mem_categories.category) AS SumOfcategory FROM members, mem_categories WHERE (members.memid = mem_categories.memid) and members.area= ".$rowy[FieldID]." and t_unlist = 0 and bdriven = 'N' GROUP BY mem_categories.memid HAVING ((Sum(mem_categories.category))>0)");
  //$Query4 = dbRead("SELECT mem_categories.memid, Sum(mem_categories.category) AS SumOfcategory FROM members, mem_categories WHERE (members.memid = mem_categories.memid) and members.area= ".$rowy[FieldID]." GROUP BY mem_categories.memid HAVING ((Sum(mem_categories.category))>0)");
  $Query4 = dbRead("SELECT mem_categories.memid, Sum(mem_categories.category) AS SumOfcategory FROM members, mem_categories WHERE (members.memid = mem_categories.memid) and members.licensee= ".$rowy[FieldID]." GROUP BY mem_categories.memid HAVING ((Sum(mem_categories.category))>0)");
  $cc4 = mysql_num_rows($Query4);
  $YearArray[$YearMonth][$rowy['FieldID']]['listed'] = $cc4;

 }


 foreach($YearArray as $Key => $Value) {


  foreach($Value as $Key2 => $Value2) {

   dbWrite("insert into tbl_stats_listed (AreaID,Month,Temp_Unlisted,Broker_Driven,Unlisted,Listed,Deactive,Sponsorship,Suspended,Suspended_Locked) values ('".$Key2."','".$Key."','".$Value2['t_unlisted']."','".$Value2['bdriven']."','".$Value2['unlisted']."','".$Value2['listed']."','".$Value2['deactive']."','".$Value2['sponsorship']."','".$Value2['suspended']."','".$Value2['suspended_locked']."')");
   //echo $Key2.", ".$Key.", ".$Value2['bdriven'].", ".$Value2['t_unlisted'].", ".$Value2['unlisted'].", ".$Value2['listed']."\r\n";

  }
 }

?>