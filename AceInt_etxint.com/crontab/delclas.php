<?

// Classified Delete After 2 Months

$NoSession = true;

include("/home/etxint/admin.etxint.com/includes/global.php");

$month = date("n");
$day = date("j");
$year = date("Y");
$epochbefore = date("Y-m-d H:i:s", mktime(0,0,0,$month-2,$day,$year));

dbWrite("delete from classifieds where date < '$epochbefore' and CID != '15'");

 $realSQL = dbRead("SELECT realimages.realid, `realimages`.`imagename` as imageName FROM {oj `realimages` LEFT OUTER JOIN `realestate` ON ((`realimages`.`id` = `realestate`.`id` ) AND (`realimages`.`agent_id` = `realestate`.`agent` ) ) } WHERE ((`realestate`.`agent` IS NULL ) )");
 while($realROW = mysql_fetch_assoc($realSQL)) {

  unlink("/home/etxint/public_html/realimages/".$realROW['imageName']);
  dbWrite("delete from realimages where realid = " . $realROW['realid']);

 }

 $Directory = dir("/home/etxint/public_html/clasimages");

 while(false !== ($DirEntry = $Directory->read())) {

  $DirectoryArray[] = $DirEntry;

 }

 $ClasSQL = dbRead("select * from classifieds order by id");
 while($ClasROW = mysql_fetch_assoc($ClasSQL)) {

  $ClasArray[] = "thumb-".$ClasROW[image];
  $ClasArray[] = "thumb2-".$ClasROW[image];
  $ClasArray[] = $ClasROW[image];

 }

 $result = array_diff($DirectoryArray, $ClasArray);

 foreach($result as $key => $value) {

  unlink("/home/etxint/public_html/clasimages/$value");

 }


?>