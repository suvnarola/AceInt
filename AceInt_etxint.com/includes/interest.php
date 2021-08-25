<?php
 ini_set("max_execution_time", 888888);

 $NoSession = true;
 include("../includes/global.php");
 include("../includes/modules/db.php");

 $YearMonth = date("Ym", mktime(0,0,0,date("m")-1,1,date("Y")));
 $YearMonths = date("Ym", mktime(0,0,0,date("m"),1-1,date("Y")));
 $count = 0;
 $YearArray[$YearMonth] = array();

  while($count <= $YearMonths) {

  $count++;

    $YearArray[$YearMonth][$count]["blank"] = 1;

 }

print_r($YearArray);

?>