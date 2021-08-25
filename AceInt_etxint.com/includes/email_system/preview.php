<?

 include("../global.php");

 $JobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . $_REQUEST['JobID'], "etxint_email_system");
 $JobRow = mysql_fetch_assoc($JobQuery);

 print $JobRow['JobData'];

?>