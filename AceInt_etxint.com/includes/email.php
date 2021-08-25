<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2008
 */

$query5 = dbRead("select position, data from tbl_corp_data, country where (tbl_corp_data.CID = country.countryID) and  countryID = '".$_SESSION['Country']['countryID']."' and tbl_corp_data.langcode = '".$_SESSION['Country']['Langcode']."' and pageid = 53 order by position");
while($row5 = mysql_fetch_array($query5)) {

 $PageData2[$row5[position]] = $row5[data];

}

function get_page_data2($id)  {
  global $PageData2;
  return $PageData2[$id];
}
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>


<?

if($_REQUEST[next]) {

include("includes/class.html.mime.mail.inc");
//include("includes/class.phpmailer.php");

 $query = dbRead("select * from classifieds where id = ".$_REQUEST['clasid']);
 $row = mysql_fetch_assoc($query);

	if($row[image]) {
	 if(file_exists("/home/etxint/public_html/clasimages/". $row[image] ."")) {

	   $im = "<img src='http://www.etxint.com/clasimages/thumb-". $row[image]."' border='0'><br>";

	 }
	}

	if($row['cid_origin'] == $_SESSION['Country']['countryID']) {
		$dbgetareas = dbRead("select tbl_area_regional.FieldID as FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_regional.FieldID = ".$row['areaid']." and tbl_area_states.CID='".$_SESSION['Country']['countryID']."' order by RegionalName");
		while($row2 = mysql_fetch_assoc($dbgetareas)) {
		  $rname = $row2['RegionalName'];
		}
	} else {
		$rname = $row['cname'];
	}

	if($row[type] == 2) {

		$tt = "For Sale";

	} elseif($row[type] == 1) {

		$tt = "Wanted To Buy";

	}

 $aa = '
     <table>
 	<tr width="100%">
		<td valign="top" bgcolor="#FFFFFF">
		<table border="0" width="100%" cellspacing="1" cellpadding="3">
			<tr>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td width="300" colspan=3" align="left" valign="top" class="Heading"><b>'. $row[productname] .' - '. $_SESSION['Country']['currency'] .''. number_format(($row[price]+$row[tradeprice]),2) .'</b></td>
				<td align="right" valign="top" class="Heading">ID: '. $row['id'] .'</td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
			</tr>

			<tr>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td width="75" rowspan="12" valign="top">
			'.$im.'
 			    <br><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF9900"><center><b>'. $rname .'<br>'. $row[suburb] .', '. $row[postcode] .'</b></center></font>
				</td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td align="left" colspan="2" valign="top" ><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000">'. $row['shortdesc'].'</font></td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td width="5">&nbsp;&nbsp;&nbsp;</td>
				<td align="left" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><br>'. get_word("122").': '. $_SESSION['Country']['currency'] .''. number_format($row[tradeprice],2) .'<br>'. get_word("121").': '. $_SESSION['Country']['currency'] .''. number_format($row[price],2) .'</font></td>
				<td align="left" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><br>'. $row[name] .'<br>'. $row[phone] .'<br>'. $row['emailaddress'] .'</font></td>

			</tr>
		'.$tt.'
		</table>
	    </td>
	</tr>
    </table>

 ';

    $text = "The following classified has been forwarded to you for your interest. To view the full list of classifieds available online <a href='https://secure.etxint.com/members'>CLICK HERE</a> and enter your username and password. <br><br> ".$aa. "<br><br><br><br>Comments:<br>".$_REQUEST[comments]."<br><br>Regards<br>".$_SESSION['User']['Name']."";
 	$text = get_html_template($_SESSION['Country']['countryID'], $_REQUEST[tofirstname], $text);

    unset($attachArray);
    unset($addressArray);

	if(strstr($_REQUEST[toemail], ";")) {
		$emailArray = explode(";", $_REQUEST[toemail]);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $_REQUEST[tofirstname]);
		}
	} else {
		$addressArray[] = array(trim($_REQUEST[toemail]), $_REQUEST[tofirstname]);
	}

	sendEmail("hq@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1), 'hq@' . $_SESSION[Country][countrycode] . '.'. getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1).' - Web Site', 'hq@' . $_SESSION[Country][countrycode] . '.'.getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1), $text, $addressArray, $attachArray);

?><head>
  <meta http-equiv="refresh" content="5;URL=<?= $_POST[referer] ?>">
 </head>

 <table width="100%" cellpadding="0" cellspacing="0" border="0" height="900">
  <tr>
    <td width="15">
    <td valign="top"><?= get_page_data("10") ?> <a href="<?= $_REQUEST[referer]?>">Email Sent</a></td>
  </tr>
 </table>
<?

} else {

?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="15"></td>
    <td><form method="post" action="body.php?page=email">
        <input type="hidden" name="SectID" value="1">
        <input type="hidden" name="PageID" value="53">
        <input type="hidden" name="next" value="1">
        <input type="hidden" name="CID" value="<?= $_SESSION['Country']['countryID']?>">
        <div align="center">
          <table width="400" border="0" cellspacing="0" cellpadding="10">
            <tr>
              <td width="56%"> <p><strong>
                  <input name="referer" type="hidden" id="referer" value="<?= $_SERVER[HTTP_REFERER] ?>">
                  <input name="clasid" type="hidden" id="clasid" value="<?= $_REQUEST['classid'] ?>">

                 <?= get_page_data2("1") ?></strong></p>
                  <br>
                  <?= get_page_data2("5") ?>:*<br>
                  <input type="text" name="senderemail" value="<?= $_SESSION['User']['EmailAddress'] ?>" tabindex="3" size="20">
                 </p></td>
              <td width="44%"> <p><strong><?= get_page_data2("2") ?></strong></p>
                <p><?= get_page_data2("3") ?>:<br>
                  <input type="text" name="tofirstname" tabindex="4" size="20">
                  <br>
                  <?= get_page_data2("5") ?>:*<br>
                  <input type="text" name="toemail" tabindex="6" size="20">
                 </p></td>
            </tr>
            <p></p>
            <tr>
              <td height="39" colspan="2"><p><strong><?= get_page_data2("6") ?>: *</strong></p>
                <p>
                  <textarea name="comments" cols="43" rows="4" tabindex="7"></textarea>
                </p>
                <p><input type="submit" name="Submit" value="<?= get_page_data2("9") ?>" tabindex="8">
                  </p>
                <p><em>*
                  <?= get_page_data2("8") ?></em></p></td>
            </tr>
          </table>
        </div>
      </form>

    </td>
  </tr>
</table>
<center>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>


<?

}

?>