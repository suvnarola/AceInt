<?

// Update Weather Database.

include("/home/etxint/admin.etxint.com/includes/global.php");

// Energex Mooloolaba

$fd = fopen("http://www.bom.gov.au/products/IDQ65119/IDQ65119.94569.shtml", "r") or die("Cannot get Web Page");

while(!feof($fd)) {
 
 $buffer = fgets($fd, 4096);
 
 if(strstr($buffer, "<td align=center>")) {
  if(!$Done) {
   
   $buffer = str_replace("</td><td align=center>",",",$buffer);
   $buffer = str_replace("</td></tr>","",$buffer);
   $buffer = str_replace("<tr><td align=center>","",$buffer);
   
   $Weather_tmp = explode(",", $buffer);
   
   $Weather[Temperature] = $Weather_tmp[1];
   $Weather[DewPoint] = $Weather_tmp[2];
   $Weather[RelativeHumidity] = $Weather_tmp[3];
   $Weather[WindDirection] = $Weather_tmp[4];
   $Weather[WindSpeed] = $Weather_tmp[5];
   $Weather[WindGust] = $Weather_tmp[7];
   $Weather[Barometer] = $Weather_tmp[9];
   $Weather[RainSince9am] = $Weather_tmp[10];

   $Done = 1;
  }
 }

}

dbWrite("update tbl_weather set Temp = '$Weather[Temperature]', DewPoint = '$Weather[DewPoint]', WindSpeed = '$Weather[WindSpeed]', Bar = '$Weather[Barometer]', Humidity = '$Weather[RelativeHumidity]', WindDirection = '$Weather[WindDirection]', WindGust = '$Weather[WindGust]', RainSince9 = '$Weather[RainSince9am]'");

fclose($fd);

?>