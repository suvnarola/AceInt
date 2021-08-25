<?


if($_REQUEST['previous']) {

	$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where memid = " . addslashes($_REQUEST['memid']) . " and jobID < " . addslashes($_REQUEST['jobID']) . " Order By jobID desc limit 1", "etxint_email_system");
	$templateDataRow = mysql_fetch_assoc($templateDataQuery);

} elseif($_REQUEST['next']) {

	$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where memid = " . addslashes($_REQUEST['memid']) . " and jobID > " . addslashes($_REQUEST['jobID']) . " Order By jobID asc limit 1", "etxint_email_system");
	$templateDataRow = mysql_fetch_assoc($templateDataQuery);

} else {

	$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where FieldID = " . addslashes($_REQUEST['id']) . " Order By `orderBy` asc", "etxint_email_system");
	$templateDataRow = mysql_fetch_assoc($templateDataQuery);
}
	$DataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where memid = " . addslashes($templateDataRow['memid']) . " Order By FieldID desc", "etxint_email_system");
	$DataRow = mysql_fetch_assoc($DataQuery);

	$sectionArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];
	$orderArray[$templateDataRow['FieldID']] = $templateDataRow['orderBy'];

	$arraySize = sizeof($templateArray);
	$counter = 1;

       if($templateDataRow['orderBy'] == 0) {
       ?>
		<table border="3" cellpadding="6" cellspacing="0" style="border-collapse: collapse" width="750">
		  <tr>
		    <td width="100%"><?= $templateDataRow['templateData'] ?>


 		  </td>
		 </tr>
		 </table>
       <?
	   } elseif(is_array($sectionArray)) {

		?>
		<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
		 <tr>
          <td class="article_header"><?= decideUpDown($templateDataRow['jobID'], $templateDataRow['memid'], "Data", "Section", $sectionKey) ?><?= $sectionData['title'] ?></td>
         </tr>
		</table>
		<br>
		<table border="3" cellpadding="6" cellspacing="0" style="border-collapse: collapse" width="750">
		  <tr>
		    <td width="100%">
		 <?

			foreach($sectionArray as $key => $value) {


				foreach($value as $sectionKey => $sectionValue) {

					$sectionData = unserialize($sectionValue);

					?>

				      <table width="750" border="0" align="center" cellpadding="0" cellspacing="0">

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
		  </td>
		 </tr>
		 </table>

<?
	function decideUpDown($jobID, $memid, $sectionType, $sectionWhere, $dataID = false) {


		/**
		 * Check to see if this is the last section if it is cant go down.
		 *
		 */
		$lastSectionSQL = dbRead("select tbl_jobs_data.jobID as firstOrder from tbl_jobs_data where memid = " . $memid . " order by jobID limit 1","etxint_email_system");
		$lastSectionRow = mysql_fetch_assoc($lastSectionSQL);

		if($lastSectionRow['firstOrder'] >= $jobID) {

			print "<img align=\"left\" src=\"http://media.ebanctrade.com/images/arrow_ASC_0.gif\">";

		} else {

			print "<a href=\"body.php?page=advert&memid=".$memid."&jobID=". $jobID ."&previous=1\"><img border=\"0\" align=\"left\" src=\"http://media.ebanctrade.com/images/arrow_ASC.gif\"></a>";

		}

		$lastSectionSQL = dbRead("select max(tbl_jobs_data.jobID) as lastOrder from tbl_jobs_data where memid = " . $memid . " ","etxint_email_system");
		$lastSectionRow = mysql_fetch_assoc($lastSectionSQL);


		if($lastSectionRow['lastOrder'] > $jobID) {

			print "<a href=\"body.php?page=advert&memid=".$memid."&jobID=". $jobID ."&next=1\"><img border=\"0\" align=\"left\" src=\"http://media.ebanctrade.com/images/arrow_DESC.gif\"></a>";

		} else {

			print "<img align=\"left\" src=\"http://media.ebanctrade.com/images/arrow_DESC_0.gif\">";

		}

	}
?>