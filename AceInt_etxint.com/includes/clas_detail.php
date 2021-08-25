<?
if(!checkmodule("ClasDetail")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

$dbgetdetails = dbRead("select classifieds.*, country.currency as currency from classifieds, country where (classifieds.cid_origin = country.countryID) and id='".$_REQUEST['id']."'");
$row = mysql_fetch_assoc($dbgetdetails);

$price2 = number_format($row['price'],2);
$tradeprice2 = number_format($row['tradeprice'],2);
$totalprice = $row['price']+$row['tradeprice'];
$totalprice2 = number_format($totalprice,2);

$array1 = explode(" ", $row['date']);
$array2 = explode("-", $array1[0]);
$month = $array2[1];
$day = $array2[2];
$year = $array2[0];

$newdate = date("l jS F, Y", mktime(0,0,1,$month,$day,$year));

$cellh = 0;
if($row['image'] != "noimg.gif") {
 if(is_file("/home/etxint/public_html/clasimages/thumb2-".$row['image'])) {
  $height = getimagesize("/home/etxint/public_html/clasimages/thumb2-".$row['image']);
 }
}
if($height[1] > 176) {
 $cellh = $height[1] - 176;
}

?>
<html>
<head>
<title>Classified Detail - ID: <?= $row['id'] ?></title>
</head>
<body>
<table width="620" cellpadding="3" cellspacing="0" border="0">
 <tr>
  <td align="center"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a></td>
 </tr>
</table>
<table cellpadding="1" border="0" cellspacing="0" width="620">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td class="Heading"><b><?= $row['productname'] ?></b></td>
    </tr>
   </table>
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("41") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $newdate ?></td>
	 <td width="40%" rowspan="12" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;
		<?
				
		if($row['image'] != "noimg.gif") {
		 if(file_exists("/home/etxint/public_html/clasimages/".$row['image'])) {
		  print"<img src=\"/clasimages/thumb2-".$row['image']."\" border=\"0\"><br>";
		 }
		}

		?>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("120") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['name'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("7") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['phone'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("9") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><a href="mailto:<?= $row['emailaddress'] ?>" class="nav"><b><?= $row['emailaddress'] ?></b></a></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("15") ?>, <?= get_word("18") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['suburb'] ?>, <?= $row['postcode'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("121") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['currency'] ?><?= $price2 ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("122") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['currency'] ?><?= $tradeprice2 ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"><?= get_word("123") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['currency'] ?><?= $totalprice2 ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" valign="top" class="Heading2" height="1"><?= get_word("27") ?>:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $row['shortdesc'] ?></td>
    </tr>
	<tr>
	 <td width="20%" align="right" valign="top" class="Heading2" height="<?= $cellh ?>">&nbsp;</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
</table>