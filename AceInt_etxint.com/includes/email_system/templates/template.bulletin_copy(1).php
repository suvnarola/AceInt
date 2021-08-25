<?

	/**
	 * Config
	 */

	$CONFIG['db_name'] = "etxint_email_system";
	$CONFIG['db_host'] = "localhost";
	$CONFIG['db_user'] = "empireDB";
	$CONFIG['db_pass'] = "1emPire82";
	$CONFIG['db_linkid'] = @mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

	$jobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
	$jobRow = @mysql_fetch_assoc($jobQuery);

	$pubEmailSQL = dbRead("select area.pubemail from area where area.FieldID = '" . $jobRow['userID'] . "';","etradebanc");
	$pubEmailObj = mysql_fetch_object($pubEmailSQL);

	$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where JobID = " . addslashes($_REQUEST['jobID']) . " Order By `orderBy` asc", "etxint_email_system");
	while($templateDataRow = mysql_fetch_assoc($templateDataQuery)) {

		if($templateDataRow['templateType'] == "Section") {

			$sectionArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];
			$orderArray[$templateDataRow['FieldID']] = $templateDataRow['orderBy'];

		} elseif($templateDataRow['templateType'] == "Community") {

			$communityArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];
			$orderArray[$templateDataRow['FieldID']] = $templateDataRow['orderBy'];

		} else {

			$templateArray[$templateDataRow['templateType']] = $templateDataRow['templateData'];
			$orderArray[$templateDataRow['FieldID']] = $templateDataRow['orderBy'];

		}

	}

	$arraySize = sizeof($templateArray);
	$counter = 1;

?>
<html>
<head>
<title><?= $templateArray['templateTitle'] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css" title="currentStyle">
	body{ background-color: <?= $templateArray['templateBackgroundColor'] ?>;  }
	a:link { color: Black; text-decoration: underline; }
	a:visited { color: Black; text-decoration: underline; }
	a:active { color: Black; text-decoration: underline; }
	a:hover { background-color: #CCCCCC; color: Black; text-decoration: underline;}
	.bold {font-size: 16px; font-weight: bold;}
	.clickhere{	padding: 0px 0px 3px 0px; font: 10px Arial, Verdana, Geneva, Helvetica, sans-serif;	text-align: center;	color: #000000;}
	.position{ vertical-align: top;  padding: 15px;  text-align: center;}
	td { font-family: Arial; font-size: 10px; }
	td.head_header_left{	background-color: #FFFFFF;	color: #595959;	font-family: Arial;	font-size: 14px;	font-weight: bold;	vertical-align: bottom;	padding: 1px 1px 5px 5px; 	text-align:left; border-top: 1px solid #1E222F; border-left: 1px solid #1E222F; border-bottom: 1px solid #1E222F;}
	td.head_header_right img { vertical-align: middle; float: right;}
	td.head_header_right{	background-color: #FFFFFF;	padding: 1px 1px 1px 1px;  border-top: 1px solid #1E222F; border-right: 1px solid #1E222F; border-bottom: 1px solid #1E222F;}
	td.head_title{	background-color: #FFFFFF; border-right: 1px solid #1E222F; border-left: 1px solid #1E222F; border-bottom: 1px solid #1E222F;}
	td.head_footer{	background: #FFFFFF;  font: 10px Arial;	padding: 5px 2px 5px 2px;	text-align: center; border-bottom: 1px solid #1E222F; border-left: 1px solid #1E222F; border-right: 1px solid #1E222F; color: #000000; text-align: center;}
	td.main_main{	background-color: #FFFFFF;	padding: 15px 0px 40px 0px; border-left: 1px solid #1E222F;	border-right: 1px solid #1E222F;	border-top: 2px solid #1E222F;	border-bottom: 1px solid #1E222F; }
	td.news_top{	color: #000066; font: normal normal bold 12px/normal Arial;  padding: 2px 1px 2px 3px; border-top: 1px solid #1E222F; border-left: 1px solid #1E222F; border-right: 1px solid #1E222F;  border-bottom: 1px solid #1E222F;}
	td.news_picture{	padding: 10px 1px 1px 1px;	margin-top: 1px;	text-align: left;	vertical-align: top; border-left: 1px solid #1E222F; border-bottom: 1px solid #1E222F;}
	td.news_main{ background-color: #FFFFFF; font: normal normal normal 11px/normal Arial;  padding: 3px 3px 6px 3px; text-align: left; border-right: 1px solid #1E222F;  border-bottom: 1px solid #1E222F; }
	td.news_footer{	font: normal normal normal 10px/normal Arial;  padding: 3px 3px 3px 3px; text-align: right; border-right: 1px solid #1E222F; border-bottom: 1px solid #1E222F; border-left: 1px solid #1E222F; }
	td.brake_main{	background-color: #FFFFFF; font: normal normal normal 11px/normal Arial;  padding: 3px 3px 6px 3px; text-align: left; border-left: 1px solid #1E222F; border-right: 1px solid #1E222F;  border-bottom: 1px solid #1E222F; }
	td.article_header{	color: #660000; font: normal normal bold 12px/normal Arial;  padding: 2px 1px 2px 3px; border-top: 1px solid #1E222F; border-left: 1px solid #1E222F; border-right: 1px solid #1E222F; }
	td.article_main{	background-color: #FFFFFF; font: normal normal normal 11px/normal Arial;  padding: 3px 3px 6px 3px; text-align: left; border-left: 1px solid #1E222F; border-right: 1px solid #1E222F;  border-bottom: 1px dashed #1E222F; border-top: 1px dashed #1E222F; }
	td.article_main2{	background-color: #FFFFFF; font: normal normal normal 11px/normal Arial;  padding: 3px 3px 6px 3px; text-align: left; border-left: 1px solid #1E222F; border-right: 1px solid #1E222F;  border-bottom: 1px solid #1E222F; border-top: 1px solid #1E222F; }
	td.article_footer{	 font: normal normal normal 10px/normal Arial;  padding: 3px 3px 8px 3px; text-align: left;  border-left: 1px solid #1E222F; border-right: 1px solid #1E222F; border-bottom: 1px solid #1E222F; }
	.style1 {font-weight: bold}	</style>

	<?

		if($_REQUEST['editCMS']) {

			?>

			  	<script LANGUAGE="JavaScript">
				<!--

					function confirmDel(itemName,itemID,jobID) {

						bDelete = confirm("Are you sure you wish to delete item: " + itemName + "?");

						if (bDelete) {

							document.location.href = '/body.php?tab=1&page=email_system/addItem&addItem=delItem&delID=' + itemID + '&jobID=' + jobID;

						} else {

						    return;

						}

					}

				//-->
				</script>

			<?

		}

	?>

</head>

<body>
<div class="clickhere"> If you cannot view the below email, please <a href="http://media.ebanctrade.com/bulletin.php?jobID=<?= $jobRow['FieldID'] ?>"class="style1">
  select here</a></div>
<table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="368" height="0" class="head_header_left"><?= $templateArray['templateTitle'] ?>&nbsp;</td>
    <td width="180" class="head_header_right"><a href="http://www.ebanctrade.com"><img
src="http://media.ebanctrade.com/displayImage.php?imageID=42" width="143" height="40"
border="0"></a></td>
  </tr>
  <tr>
    <td height="100" colspan="2" class="head_title"><img src="http://media.ebanctrade.com/displayImage.php?imageID=<?= $templateArray['templateImage'] ?>"></td>
  </tr>
  <?

  	/**
  	 * Get Image ID out.
  	 */

  	$imageSQLtemp = dbRead("select tbl_jobs_data.* from tbl_jobs_data where jobID = " . $jobRow['FieldID'] . " and templateType = 'templateImage'");
    $imageRowtemp = @mysql_fetch_assoc($imageSQLtemp);

  	$imageSQL = dbRead("select tbl_jobs_images.* from tbl_jobs_images where fieldID = " . $imageRowtemp['templateData']);
  	$imageRow = @mysql_fetch_assoc($imageSQL);

  	//if($imageRow['areaID'] != 1) {

  		$areaSQL = dbRead("select area.* from area where FieldID = " . $imageRow['areaID'], "etradebanc");
  		$areaRow = @mysql_fetch_assoc($areaSQL);

  		$countrySQL = dbRead("select country.* from country where countryID = " . $areaRow['CID'], "etradebanc");
  		$countryRow = @mysql_fetch_assoc($countrySQL);

  		$countryDataSQL = dbRead("select countrydata.* from countrydata where CID = " . $areaRow['CID'], "etradebanc");
  		$countryDataRow = @mysql_fetch_assoc($countryDataSQL);

  		if($countryRow['DefaultArea'] == $areaRow['FieldID']) {

	  		?>
			  <tr>
			    <td colspan="2" class="head_footer"><?= getWho($areaRow['CID'],1); ?> <?= $countryRow['name'] ?> - Email: <a href="mailto:<?= $areaRow['email'] ?>"><?= $areaRow['email'] ?></a> - Tel: <?= $areaRow['phone'] ?> - Fax: <?= $areaRow['fax'] ?></td>
			  </tr>
	  		<?

  		} else {

	  		?>
			  <tr>
			    <td colspan="2" class="head_footer"><?= getWho($areaRow['CID'],1); ?> <?= $areaRow['place'] ?> - Email: <a href="mailto:<?= $areaRow['email'] ?>"><?= $areaRow['email'] ?></a> - Tel: <?= $areaRow['phone'] ?> - Fax: <?= $areaRow['fax'] ?></td>
			  </tr>
	  		<?


  		}


  	//}

  ?>
  <tr valign="top">
    <td colspan="2" class="main_main">
<!-- Begin BiLine -->
	<?

		if($templateArray['templateBiline']) {
			?>
			<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td height="25" class="news_top">
						<div align="center"><?= $templateArray['templateBiline'] ?></div>
					</td>
				</tr>
			</table>
			<br>
      		<?
		}

	?>
<!-- End BiLine -->

<!-- Begin Community -->
	<?

		if(is_array($communityArray)) {

			foreach($communityArray as $key => $value) {

				if($comunityName != $key) {

					if($_REQUEST['editCMS']) {

						$arrayKeys = array_keys($value);

    					?>

    				      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
    				        <tr>
    				          <td width="956" height="25" colspan="3" class="news_top"><?= decideUpDown($jobRow['FieldID'], $orderArray[$arrayKeys[0]], "Header", "Community") ?><font size="3"><?= $key ?></font></td>
    				        </tr>
    				      </table>
    				      <br>

    			      	<?

			      	} else {

    					?>

    				      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
    				        <tr>
    				          <td width="956" height="25" colspan="3" class="news_top"><font size="3"><?= $key ?></font></td>
    				        </tr>
    				      </table>
    				      <br>

    			      	<?

			      	}

				}

				foreach($value as $communityKey => $communityValue) {

					$communityData = unserialize($communityValue);

					?>

				      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">

				      <?

				      	if($_REQUEST['editCMS']) {

							//$arrayKeys = array_keys($communityValue);

				      		?>

						      	<tr>
						          <td class="article_header"><?= decideUpDown($jobRow['FieldID'], $orderArray[$communityKey], "Data", "Community", $communityKey) ?><a href="/body.php?page=email_system/addItem&articleID=<?= $communityKey ?>&addItem=templateArticleEdit&tab=<?= $_REQUEST['tab'] ?>"><img align="right" src="http://media.ebanctrade.com/images/edit.png" border="0"></a><?= $communityData['title'] ?><a href="javascript:confirmDel('<?= $communityData['title'] ?>',<?= $communityKey ?>,<?= $jobRow['FieldID'] ?>)"><img src="/images/delete.png" align="right" border="0"></a></td>
						        </tr>

				      		<?


				      	} else {

				      		?>

						      	<tr>
						          <td class="article_header"><?= $communityData['title'] ?></td>
						        </tr>

				      		<?

				      	}

				      ?>
				        <tr>
				          <td class="<? if(!$communityData['contact'] && !$communityData['title']) { print "article_main2"; } else { print "article_main"; } ?>" height="24"><?= $communityData['data'] ?></td>
				        </tr>

				        <?

				        if($communityData['contact']) {

				        	?>

						        <tr>
						          <td height="51" class="article_footer"><?= $communityData['contact'] ?></td>
						        </tr>

				        	<?

				        }

				        ?>

				      </table>
				      <br>

					<?

				}

		      	$communityName = $key;
		      	$counter++;

			}

		}

	?>
<!-- End Community -->

<?

	if($jobRow['membersMarket']) {

		?>

		<!-- Begin Members Market -->
		      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td height="25" class="news_top"><div align="center"><font size="3">
		            MEMBERS&#39; MARKET</font></div>
		          </td>
		        </tr>
		        <tr>
		          <td class="brake_main"><br>
		          If you wish to advertise your business to all Empire Trade members you
		          can submit your article online at <a href="http://www.empirexchange.com/news">
		          www.empirexchange.com/news</a> by clicking on the &#39;Submit your Article&#39;
		          link or by emailing directly to <a href="mailto:<?= $pubEmailObj->pubemail ?>">
		          <?= $pubEmailObj->pubemail ?></a><span style="font-size: 10px;">&nbsp;&nbsp;Please Note: While we attempt to include all member submissions, we cannot guarantee your submission will be in the current publication. Due to member demand, and to ensure fresh and interesting content, we do not offer members the option of advertising in consecutive issues.</span></td>
		        </tr>
		      </table>
		      <br>
		<!-- End Members Market -->

		<?

	}

?>

<!-- Begin Sections -->

	<?

		if(is_array($sectionArray)) {

			foreach($sectionArray as $key => $value) {

				if($sectionName != $key) {

					$arrayKeys = array_keys($value);

					if($_REQUEST['editCMS']) {

					?>

				      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
				        <tr>
				          <td width="956" height="25" colspan="3" class="news_top"><?= decideUpDown($jobRow['FieldID'], $orderArray[$arrayKeys[0]], "Header", "Section") ?><font size="3"><?= $key ?></font></td>
				        </tr>
				      </table>
				      <br>

			      	<?

			      	} else {

					?>

				      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
				        <tr>
				          <td width="956" height="25" colspan="3" class="news_top"><font size="3"><?= $key ?></font></td>
				        </tr>
				      </table>
				      <br>

			      	<?

			      	}

				}

				foreach($value as $sectionKey => $sectionValue) {

					$sectionData = unserialize($sectionValue);

					?>

				      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">

				      <?

				      	if($_REQUEST['editCMS']) {


				      		?>

						      	<tr>
						          <td class="article_header"><?= decideUpDown($jobRow['FieldID'], $orderArray[$sectionKey], "Data", "Section", $sectionKey) ?><a href="/body.php?page=email_system/addItem&articleID=<?= $sectionKey ?>&addItem=templateArticleEdit&tab=<?= $_REQUEST['tab'] ?>"><img align="right" src="http://media.ebanctrade.com/images/edit.png" border="0"></a><a href="javascript:confirmDel('<?= $sectionData['title'] ?>',<?= $sectionKey ?>,<?= $jobRow['FieldID'] ?>)"><img src="/images/delete.png" align="right" border="0"></a><?= $sectionData['title'] ?></td>
						        </tr>

				      		<?


				      	} else {

				      		?>

						      	<tr>
						          <td class="article_header"><?= $sectionData['title'] ?></td>
						        </tr>

				      		<?

				      	}

				      ?>

				        <tr>
				          <td class="<? if(!$sectionData['contact'] && !$sectionData['title']) { print "article_main2"; } else { print "article_main"; } ?>" height="24"><?= $sectionData['data'] ?></td>
				        </tr>


				        <?

				        if($sectionData['contact']) {

				        	?>

						        <tr>
						          <td height="51" class="article_footer"><?= $sectionData['contact'] ?></td>
						        </tr>

				        	<?

				        }

				        ?>
				      </table>
				      <br>

					<?

				}

		      	$sectionName = $key;

			}

		}

	?>
<!-- End Sections -->

      </td>
  </tr>

<!-- Begin Footer -->

  <tr>
    <td colspan="2" class="head_footer">
      <p>Happy Trading from Empire Trade Management and Associates</p>
      <p>This message is sent to online members of Empire Trade. If you feel
      that your email was obtained by error, or you no longer wish to receive
      membership promotions, please <a href="mailto:<?= $pubEmailObj->pubemail ?>?subject=Please%20remove%20me%20from%20your%20email%20list">
      select here</a> and hit send.</p>
      <p align="justify"><?= $countryDataRow['tpl_disclaimer'] ?></p>
    </td>
  </tr>

<!-- End Footer -->

</table>
</body>
</html>
<?

	/**
	 * Database Functions
	 */

	function dbWrite($SQLQuery,$database = false,$ReturnID = False) {
		global $CONFIG;
		if($database == false) { $database = $CONFIG['db_name']; }

		$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);
		if ($ReturnID == True) {
			$ReturnID = mysql_insert_id($CONFIG['db_linkid']);
		} else {
			$ReturnID = True;
		}

		return($ReturnID);
	}


	function dbRead($SQLQuery,$database = false) {
		global $CONFIG;
		if($database == false) { $database = $CONFIG['db_name']; }

		$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);

		return($rsid);
	}

	function decideUpDown($jobID, $orderNumber, $sectionType, $sectionWhere, $dataID = false) {

		//print "OrderNumber: " . $orderNumber;

		$orderNumberArray = explode(".", $orderNumber);

		if($sectionType == "Header") {

			if($orderNumberArray[0] == 1) {

				print "<img align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_ASC_0.gif\">";

			} else {

				print "<a href=\"/includes/email_system/orderBy.php?jobID=" . $jobID . "&orderNumber=" . $orderNumber . "&arrowType=Up&sectionDataType=" . $sectionWhere . "&sectionType=" . $sectionType . "\"><img border=\"0\" align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_ASC.gif\"></a>";

			}

			/**
			 * Check to see if this is the last section if it is cant go down.
			 *
			 */

			$lastSectionSQL = dbRead("select max(tbl_jobs_data.orderBy) as lastOrder from tbl_jobs_data where templateType = '" . $sectionWhere . "' and jobID = " . $jobID);
			$lastSectionRow = mysql_fetch_assoc($lastSectionSQL);

			$lastOrderNumber = explode(".", $lastSectionRow['lastOrder']);

			if($lastOrderNumber[0] > $orderNumberArray[0]) {

				print "<a href=\"/includes/email_system/orderBy.php?jobID=" . $jobID . "&orderNumber=" . $orderNumber . "&arrowType=Down&sectionDataType=" . $sectionWhere . "&sectionType=" . $sectionType . "\"><img border=\"0\" align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_DESC.gif\"></a>";

			} else {

				print "<img align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_DESC_0.gif\">";

			}

		} else {

			if($orderNumberArray[1] == "01") {

				print "<img align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_ASC_0.gif\">";

			} else {

				print "<a href=\"/includes/email_system/orderBy.php?jobID=" . $jobID . "&orderNumber=" . $orderNumber . "&arrowType=Up&sectionDataType=" . $sectionWhere . "&sectionType=" . $sectionType . "&dataID=" . $dataID . "\"><img border=\"0\" align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_ASC.gif\"></a>";

			}

			/**
			 * Check to see if this is the last section if it is cant go down.
			 *
			 */

			$lastSectionSQL = dbRead("select max(tbl_jobs_data.orderBy) as lastOrder from tbl_jobs_data where orderBy like '".$orderNumberArray[0].".%' and templateType = '" . $sectionWhere . "' and jobID = " . $jobID);
			$lastSectionRow = mysql_fetch_assoc($lastSectionSQL);

			$lastOrderNumber = explode(".", $lastSectionRow['lastOrder']);

			if($lastOrderNumber[1] > $orderNumberArray[1]) {

				print "<a href=\"/includes/email_system/orderBy.php?jobID=" . $jobID . "&orderNumber=" . $orderNumber . "&arrowType=Down&sectionDataType=" . $sectionWhere . "&sectionType=" . $sectionType . "&dataID=" . $dataID . "\"><img border=\"0\" align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_DESC.gif\"></a>";

			} else {

				print "<img align=\"right\" src=\"http://media.ebanctrade.com/images/arrow_DESC_0.gif\">";

			}

		}

	}

	function getWho($logo, $type) {

		switch($type) {

			case "1":

				/**
				 * Text.
				 */

				switch($logo) {

					case "etx":

						return "Empire Trade";
						break;

					case "ept":

						return "E Planet Trade";
						break;

					default:

						return "E Banc Trade";
						break;

				}

				break;

			case "2":

				/**
				 * Domain.
				 */

				switch($logo) {

					case "etx":

						return "empireXchange.com";
						break;

					case "ept":

						return "eplanettrade.com";
						break;

					default:

						return "ebanctrade.com";
						break;

				}

				break;

		}

	}

?>