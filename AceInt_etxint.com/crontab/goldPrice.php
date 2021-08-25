<?

$NoSession = true;

// Update Gold Price.

include("/home/etxint/admin.etxint.com/includes/global.php");

$fd = fopen("http://www.goldcentral.com/htmlsnippets/pricechart.inc", "r") or die("Cannot get Web Page");

while(!feof($fd)) {
 
 $buffer = fgets($fd, 4096);
 
 if(strstr($buffer, "<TD VALIGN=CENTER>")) {
  if(!$Done) {
   
   $buffer = str_replace("document.write('<TD VALIGN=CENTER><FONT FACE=ARIAL,HELVETICA SIZE=-2>","",$buffer);
   $buffer = str_replace("</FONT></TD>');","",$buffer);
   
   $goldPrice = $buffer;

   $Done = 1;
   
  }
 }

}

dbWrite("update tbl_goldPrice set goldPrice = '" . addslashes($goldPrice) . "' where fieldID = 1");

fclose($fd);

?>