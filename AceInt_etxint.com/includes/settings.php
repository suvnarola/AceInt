<?

#header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");				// Date in the past
#header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");	// always modified
#header ("Cache-Control: no-cache, must-revalidate");			// HTTP/1.1
#header ("Pragma: no-cache");

$host="localhost";
$user="";
$pass="";
$db="etradebanc";

$linkid = mysql_pconnect($host ,$user ,$pass);

$font = "Verdana, Arial, Helvetica, sans-serif";

$fundspass="ebhyQs/7PwDqk";
$statepass="ebMUUvcDh.KKg";

?>
