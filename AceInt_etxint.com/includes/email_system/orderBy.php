<?

	define("ORDER_NUMBER", $_REQUEST['orderNumber']);
	define("SECTION_TYPE", $_REQUEST['sectionType']);
	define("SECTION_TEMPLATE_TYPE", $_REQUEST['sectionDataType']);
	define("DATAID", $_REQUEST['dataID']);
	define("ARROW", $_REQUEST['arrowType']);
	define("JOBID", $_REQUEST['jobID']);
	define("TEMP_ORDER_NUMBER", mt_rand(1000,9999));

	include("../global.php");

	$orderArray = explode(".", ORDER_NUMBER);
	
	if(SECTION_TYPE == "Header") {
	
		/**
		 * This is a Header. Move entire contents in the direction of the arrow.
		 */
	
		if($_REQUEST['arrowType'] == "Up") {
			
			/**
			 * Set the current order numbers to TEMP_ORDER_NUMBER.ordernumber
			 */
			
			$currentDataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where orderBy like '" . $orderArray[0] . ".%' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			while($currentDataRow = mysql_fetch_assoc($currentDataSQL)) {
			
				$currentOrderArray = explode(".", $currentDataRow['orderBy']);
				dbWrite("update tbl_jobs_data set orderBy = '" . TEMP_ORDER_NUMBER . "." . $currentOrderArray[1] . "' where FieldID = " . $currentDataRow['FieldID'], "etxint_email_system");
			
			}

			/**
			 * Set the one Above to the current one.
			 */
			
			$aboveNumber = $orderArray[0] - 1;
			
			$aboveDataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where orderBy like '" . $aboveNumber . ".%' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			while($aboveDataRow = mysql_fetch_assoc($aboveDataSQL)) {
			
				$aboveOrderArray = explode(".", $aboveDataRow['orderBy']);
				dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . "." . $aboveOrderArray[1] . "' where FieldID = " . $aboveDataRow['FieldID'], "etxint_email_system");
			
			}

			/**
			 * Set the TEMP_ORDER_NUMBER one to the one above.
			 */
		
			$newNumber = $orderArray[0] - 1;
			
			$newDataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where orderBy like '" . TEMP_ORDER_NUMBER . ".%' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			while($newDataRow = mysql_fetch_assoc($newDataSQL)) {
			
				$newOrderArray = explode(".", $newDataRow['orderBy']);
				dbWrite("update tbl_jobs_data set orderBy = '" . $newNumber . "." . $newOrderArray[1] . "' where FieldID = " . $newDataRow['FieldID'], "etxint_email_system");
			
			}
			
			/**
			 * Done
			 */
			
		} elseif($_REQUEST['arrowType'] == "Down") {
		
			/**
			 * Set the current order numbers to TEMP_ORDER_NUMBER.ordernumber
			 */
			
			$currentDataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where orderBy like '" . $orderArray[0] . ".%' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			while($currentDataRow = mysql_fetch_assoc($currentDataSQL)) {
			
				$currentOrderArray = explode(".", $currentDataRow['orderBy']);
				dbWrite("update tbl_jobs_data set orderBy = '" . TEMP_ORDER_NUMBER . "." . $currentOrderArray[1] . "' where FieldID = " . $currentDataRow['FieldID'], "etxint_email_system");
			
			}

			/**
			 * Set the one Below to the current one.
			 */
			
			$aboveNumber = $orderArray[0] + 1;
			
			$aboveDataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where orderBy like '" . $aboveNumber . ".%' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			while($aboveDataRow = mysql_fetch_assoc($aboveDataSQL)) {
			
				$aboveOrderArray = explode(".", $aboveDataRow['orderBy']);
				dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . "." . $aboveOrderArray[1] . "' where FieldID = " . $aboveDataRow['FieldID'], "etxint_email_system");
			
			}

			/**
			 * Set the TEMP_ORDER_NUMBER one to the one above.
			 */
		
			$newNumber = $orderArray[0] + 1;
			
			$newDataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where orderBy like '" . TEMP_ORDER_NUMBER . ".%' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			while($newDataRow = mysql_fetch_assoc($newDataSQL)) {
			
				$newOrderArray = explode(".", $newDataRow['orderBy']);
				dbWrite("update tbl_jobs_data set orderBy = '" . $newNumber . "." . $newOrderArray[1] . "' where FieldID = " . $newDataRow['FieldID'], "etxint_email_system");
			
			}
			
			/**
			 * Done
			 */		
			 
		}
	
	} elseif(SECTION_TYPE == "Data") {
	
		/**
		 * This is a Data within a Header. Move it in the direction of the arrow.
		 */
	
		if($_REQUEST['arrowType'] == "Up") {
		
			/**
			 * Set the current order numbers to $orderArray[0].99
			 */
			
			dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . ".99' where FieldID = " . DATAID, "etxint_email_system");
			

			/**
			 * Set the one Above to the current one.
			 */
			
			$aboveNumber = str_pad($orderArray[1] - 1, 2, "0", STR_PAD_LEFT);
			
			dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . "." . $orderArray[1] . "' where orderBy = '" . $orderArray[0] . "." . $aboveNumber . "' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			
			print $out1;
			
			/**
			 * Set the .99 one to the one above.
			 */
		
			dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . "." . $aboveNumber . "' where orderBy = '" . $orderArray[0] . ".99' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			
			print $out2;
			
			/**
			 * Done
			 */		
		
		} elseif($_REQUEST['arrowType'] == "Down") {
		
			/**
			 * Set the current order numbers to $orderArray[0].99
			 */
			
			dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . ".99' where FieldID = " . DATAID, "etxint_email_system");
			

			/**
			 * Set the one Below to the current one.
			 */
			
			$aboveNumber = str_pad($orderArray[1] + 1, 2, "0", STR_PAD_LEFT);
			
			dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . "." . $orderArray[1] . "' where orderBy = '" . $orderArray[0] . "." . $aboveNumber . "' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");

			/**
			 * Set the .99 one to the one above.
			 */
		
			dbWrite("update tbl_jobs_data set orderBy = '" . $orderArray[0] . "." . $aboveNumber . "' where orderBy = '" . $orderArray[0] . ".99' and templateType = '" . SECTION_TEMPLATE_TYPE . "' and jobID = " . JOBID, "etxint_email_system");
			
			/**
			 * Done
			 */		
		
		}

	}
	
	header("Location: /includes/email_system/templates/template.bulletin.php?jobID=" . JOBID . "&editCMS=1");
	
?>