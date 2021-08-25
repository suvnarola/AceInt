<?

 /** 
  * Encode Database to UTF8
  *
  * encode.php
  * Version 0.02
  */

 include("global.php");
 include("modules/db.php");

 ini_set("max_execution_time","60");

 $SQL = dbRead("select * from tbl_procedure");
 //$FieldList = dbtList("country");

 while($SQLRow = mysql_fetch_assoc($SQL)) {
 
  $dbSQL = new dbCreateSQL();
  $dbSQL->add_table("tbl_procedure");
 	
  $dbSQL->add_item("proc_name", decode_text($SQLRow['proc_name']));
  //$dbSQL->add_item("Position", decode_text($SQLRow['Position']));
  //$dbSQL->add_item("Position2", decode_text($SQLRow['Position2']));
 
  //$dbSQL->add_where("CID = '".$SQLRow['FieldID']."'");
  //$dbSQL->add_where("CID = 8");
  
  //print $dbSQL->get_sql_update()."\r\n";
  dbWrite($dbSQL->get_sql_update());
 
 }

?>