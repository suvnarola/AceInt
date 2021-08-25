<?

include("globals.php");

#find out how many images there are for this car.

 $id = $_GET[id];

 $Pictures = getDirList("/home/etxint/public_html/members/auctionimages");

 foreach($Pictures as $key => $value) {
    
  $Test_tmp = $value;   

  $Test = substr(strrev(strrchr(strrev($Test_tmp), ".")), 0, -1);
         	   
  if($Test == $id) {

   $Picture_Array[] = $value;
	
  }

 } 

$ImageCount = count($Picture_Array);

if(!$_GET[imageno]) {

 $imageid = $Picture_Array[0];
 
 // next/previous stuff.
 
 if(isset($Picture_Array[1])) {
  $nextpage = 1;
 } else {
  $nextpage = "0";
 }
 if(isset($Picture_Array[$ImageCount])) {
  $previouspage = $ImageCount;
 } else {
  $previouspage = "0";
 }
 
} else {

 $imageid = $Picture_Array[$_GET[imageno]];

 // next/previous stuff.
 
 if(isset($Picture_Array[$_GET[imageno]+1])) {
  $nextpage = $_GET[imageno]+1;
 } else {
  $nextpage = "0";
 }
 if(isset($Picture_Array[$_GET[imageno]-1])) {
  $nextpage = 1;
 } else {
  $nextpage = "0";
 }

}

?>
<html>

<head>
<style>
<!--
a.nav:link {
	font-family: Verdana;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	text-decoration: none; }
a.nav:visited {
	font-family: Verdana;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	text-decoration: none; }
a.nav:hover {
	font-family: Verdana;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	text-decoration: underline; }
td { font-family: Verdana; font-size: 8pt; color: #000000; }
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<TITLE>E Banc Auctions - Auction ID: EBT-AU-<?= $id ?></TITLE>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="3" align="center"><img src="/members/auctionimages/<?= $imageid ?>"></td>
  </tr>
  <tr>
    <td align="left" width="33%"><b><a href="imageview.php?id=<?= $id ?>&imageno=<?= $previouspage ?>" class="nav">Previous</a></b></td>
    <td align="center"><b><a class="nav" href="javascript:this.close();">Close</a></b></td>
    <td align="right" width="33%"><b><a href="imageview.php?id=<?= $id ?>&imageno=<?= $nextpage ?>" class="nav">Next</a></b></td>
  </tr>
</table>
</body>

</html>