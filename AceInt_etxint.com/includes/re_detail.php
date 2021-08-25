<?

$dbgetdetails = dbRead("select * from realestate, tbl_area_regional where (realestate.area = tbl_area_regional.FieldID) and id='$_GET[id]' and checked='1'");
$row = mysql_fetch_array($dbgetdetails);

$price2 = number_format($row[price],2);
$tradeprice2 = number_format($row[tradeprice],2);
$totalprice = $row[price]+$row[tradeprice];
$totalprice2 = number_format($totalprice,2);

$array1 = explode(" ", $row[date]);
$array2 = explode("-", $array1[0]);
$month = $array2[1];
$day = $array2[2];
$year = $array2[0];

$newdate = date("l jS F, Y", mktime(0,0,1,$month,$day,$year));

?>
<html>
<head>
<title>Real Estate Detail - ID: <?= $_GET[id] ?></title>
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
     <td class="Heading"><b><?= $row[contactname] ?></b></td>
    </tr>
   </table>
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("41") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $newdate ?></td>
	 <td width="248" rowspan="12" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;
		<?
				
		$getimages="select imagename from realimages where id='$_GET[id]' and agent_id='$_GET[agent]'";
		$dbgetimages = dbRead($getimages);
		while(list($imagename)=mysql_fetch_row($dbgetimages)) {

		print'<img src="http://www.ebanctrade.com/realimages/thumb-'.$imagename.'" border="0">&nbsp;';
		
		}

		?>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("120") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[contactname] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("7") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[phone] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("9") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><a href="mailto:<?= $row[emailaddress] ?>" class="nav"><b><?= $row[emailaddress] ?></b></a></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("78") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[RegionalName] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("15") ?>, <?= get_word("18") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[suburb] ?>, <?= $row[postcode] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("121") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[price] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("122") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[pricetrade] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" class="Heading2" height="1"><?= get_word("123") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[totalprice] ?></td>
	</tr>
	<tr>
	 <td width="124" align="right" valign="top" class="Heading2" height="1"><?= get_word("27") ?>:</td>
	 <td width="248" align="left" bgcolor="#FFFFFF"><?= $row[shortdesc] ?></td>
    </tr>
	<tr>
	 <td width="124" align="right" valign="top" class="Heading2" height="<?= $cellh ?>">&nbsp;</td>
	 <td width="248" align="left" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
</table>