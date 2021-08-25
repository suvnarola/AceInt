<?

 /**
  * Email System Class
  *
  * class.emailSystem.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  * : Requires Database functions, dbRead(), dbWrite() and their associated functions.
  */
 
 class emailSystem {

  /*
   
   Class Description.
   
	addID:						
	addJobName:					
	addJobSubject:				
	addJobFromAddress:			
	addJobReturnPath:			
   
   Function Reference.
   
    DisplayList(value)				Add Variables (To, ToRow, ToCountry) has to be valid membership number
   
  */
	
	var $addID;
	var $addJobName;
	var $addJobSubject;
	var $addJobFromAddress;
	var $addJobReturnPath;
	var $addAttachments;
	var $addTemplateID;
	var $addTemplateArray;
 	var $addEmailList;
 	var $emailType;
 	
 	function emailSystem() {
 		
 		if(!is_array($_SESSION['emailAdminS'])) {
 			
 			$_SESSION['emailAdminS'] = array();
 		
 		}
 		
 		$_SESSION['emailAdminS']['JobName'] = (!empty($_SESSION['emailAdminS']['JobName'])) ? $_SESSION['emailAdminS']['JobName'] : $_REQUEST['JobName'];
 		$_SESSION['emailAdminS']['Subject'] = (!empty($_SESSION['emailAdminS']['Subject'])) ? $_SESSION['emailAdminS']['Subject'] : $_REQUEST['Subject'];
 		$_SESSION['emailAdminS']['templateID'] = (!empty($_SESSION['emailAdminS']['templateID'])) ? $_SESSION['emailAdminS']['templateID'] : $_REQUEST['templateID'];
  		$_SESSION['emailAdminS']['FromAddress'] = (!empty($_SESSION['emailAdminS']['FromAddress'])) ? $_SESSION['emailAdminS']['FromAddress'] : $_REQUEST['FromAddress'];
 		$_SESSION['emailAdminS']['ReturnPath'] = (!empty($_SESSION['emailAdminS']['ReturnPath'])) ? $_SESSION['emailAdminS']['ReturnPath'] : $_REQUEST['ReturnPath'];
 		$_SESSION['emailAdminS']['websiteView'] = (!empty($_SESSION['emailAdminS']['websiteView'])) ? $_SESSION['emailAdminS']['websiteView'] : $_REQUEST['websiteView'];
 		$_SESSION['emailAdminS']['membersMarket'] = (!empty($_SESSION['emailAdminS']['membersMarket'])) ? $_SESSION['emailAdminS']['membersMarket'] : $_REQUEST['membersMarket'];
 		
 		if($_REQUEST['Next'] == 2) {
 		
 			$_SESSION['emailAdminS']['emailList'] = (!empty($_SESSION['emailAdminS']['emailList'])) ? $_SESSION['emailAdminS']['emailList'] : $this->processEmails();
 		
 		}
 		
 	}
 	
 	function processEmails() {
 	
		if($_REQUEST['all']) {
			
			$this->emailType = "1";
			
		} else {
			
			$this->emailType = "2";
			
		} 

		return $this->getList($this->emailType);
		
	}
 	
	function getList($type) {
		
		$count = 0;

		if($_REQUEST[re]) {
			$op = " AND members.reopt = 'Y'";
		} else {
			$op = " AND members.opt = 'Y'";
		}

		if($_REQUEST[cat]) {

			$count=0;

			foreach($_REQUEST[cat] as $cat_val) {
				
				if($count == 0) {

					$andor="";
					
				} else {
					
					$andor=",";
					
				}

				$cat_list.="".$andor."".$cat_val."";

				$count++;
			}
			
			$cat2_array = " and (mem_categories.category IN($cat_list))";  
			  
		} 

		if($type == "1") {

			if($_REQUEST[disarea]) {

				$area_array = $_REQUEST[disarea];
			
				foreach($area_array as $key => $value) {
					
					$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and tbl_area_regional.FieldID ='$value' and (status.mem_lists = 1) group by members.emailaddress order by AreaName");

					while($row = mysql_fetch_assoc($query)) {
						
						$count++;
						
						if($count == 5) {
							
							$blah .= "steve@funstuff4you.net;";
							
						}
						
						if($_SESSION['User']['CID'] == 12) {
							
							$blah .= "$row[emailaddress],\r\n";
							
						} else { 
							
							$blah .= "$row[emailaddress];";
							
						}
						
					}
					
				}
			
				return $blah;

			} elseif($_REQUEST[area]) {

				$count=0;
				
				foreach($_REQUEST[area] as $cat_val) {
					
					if($count == 0) {
						
						$andor="";
						
					} else {
						
						$andor=",";
						
					}     
					    
					$a_list = explode(",", $cat_val,2);
					$cat_array.="".$andor."".$a_list[0]."";
					$cat_array3.="".$andor."".$a_list[1]."";
				
					$count++;
					
				}

				$query = dbRead("select members.*, status.*, mem_categories.* from members, status, tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) where (members.area = tbl_area_physical.FieldID) and (members.status = status.FieldID) and (tbl_area_physical.FieldID IN($cat_array) or members.licensee in ($cat_array3)) and members.emailaddress != ''$op$cat2_array and (status.mem_lists = 1) group by members.memid order by AreaName");

				while($row = mysql_fetch_assoc($query)) {
					
					$count++;
					
					if($count == 5) {
						
						$blah .= "steve@funstuff4you.net;";
						
					}	
					
					if($_SESSION['User']['CID'] == 12) {
						
						$blah .= "$row[emailaddress],\r\n";
						
					} else { 
						
						$blah .= "$row[emailaddress];";
					
					}
					
				}

				return $blah;

			} elseif($_REQUEST[lic]) {

				$count=0;
				
				foreach($_REQUEST[lic] as $cat_val) {
					
					if($count == 0) {
						
						$andor="";
					
					} else {
						
						$andor=",";
					
					}

					$cat_array.="".$andor."".$cat_val."";
					
					$count++;
					
				}

				$query = dbRead("select members.*, status.*, mem_categories.* from members, status, area left outer join mem_categories on (members.memid = mem_categories.memid) where (members.licensee = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array))$cat2_array and (status.mem_lists = 1) group by members.memid order by place");

				while($row = mysql_fetch_assoc($query)) {
					
				$count++;
					
					if($count == 5) {
						
						$blah .= "steve@funstuff4you.net;";
						
					}	
					
					if($_SESSION['User']['CID'] == 12) {	
						 
						$blah .= "$row[emailaddress],\r\n";
						
					} else { 
						
						$blah .= "$row[emailaddress];";
						
					}
					
				}

				return $blah;

			}

		} elseif($type == "2") {

			$area_array = $_POST[disarea];
			
			foreach($area_array as $key => $value) {

				$query = dbRead("select members.*, status.* from members, status, tbl_area_physical, tbl_area_regional where (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op$cat2_array and tbl_area_regional.FieldID='$value' and status.Name = 'Sponsorship'  order by RegionalName");

				while($row = mysql_fetch_assoc($query)) {
					
					$count++;
					
					if($count == 5) {
						
						$blah .= "steve@funstuff4you.net;";
						
					}	
					
					if($_SESSION['User']['CID'] == 12) {	
						 
						$blah .= "$row[emailaddress],\r\n";
						
					} else { 
						
						$blah .= "$row[emailaddress];";
						
					}
					
				}
				
			}
			
			return $blah;

		}
		
	}
	
	function dbAddJob() {
		
		$this->addID = dbWrite("insert into tbl_jobs (templateID,JobName,Subject,FromAddress,ReturnPath,websiteView,membersMarket,userID) values ('".$_SESSION['emailAdminS']['templateID']."','".addslashes($_SESSION['emailAdminS']['JobName'])."','".addslashes($_SESSION['emailAdminS']['Subject'])."','".$_SESSION['emailAdminS']['FromAddress']."','".$_SESSION['emailAdminS']['ReturnPath']."','1','".$_SESSION['emailAdminS']['membersMarket']."','" . $_SESSION['User']['Area'] . "')","etxint_email_system", true);
		
		if($_REQUEST['templateDataID']) {
		
			$templateSQL = dbRead("select tbl_templates_templates.* from tbl_templates_templates where fieldID = " . $_REQUEST['templateDataID'], "etxint_email_system");
			$templateRow = mysql_fetch_assoc($templateSQL);
			
			$arrayData = unserialize($templateRow['templateData']);
		
			foreach($arrayData as $key => $value) {
			
				dbWrite("insert into tbl_jobs_data (jobID,templateType,templateData) values ('" . $this->addID . "','" . $key . "','" . $value . "')", "etxint_email_system");
			
			}
		
		}

	}
	
	function dbAddressesAdd($addID) {
		
		$emailAddressArray = explode(";", $_SESSION['emailAdminS']['emailList']);
		foreach($emailAddressArray as $Key => $Value) {
			
			if($Value) {
			
				dbWrite("insert into tbl_jobs_emails (JobID,EmailAddress,mailSent) values ('" . $addID . "','".addslashes($Value)."','0')","etxint_email_system");
			
			}
		}
		
	}
	
 	function deleteJob($JobID) {
 		
 		dbWrite("delete from tbl_jobs where FieldID = " . $JobID, "etxint_email_system");
 		dbWrite("delete from tbl_jobs_emails where JobID = " . $JobID, "etxint_email_system");
 		return;
 		
 	}
 	
	function displayList() {
 
 
	?>
	<script LANGUAGE="JavaScript">
	<!--
	
		function confirmDel(JobID) {
			
			bDelete = confirm("Are you sure you wish to delete JobID " + JobID + "?");
			
			if (bDelete) {
				
				document.location.href = 'body.php?page=email_system/defaultnew&Del=true&tab=tab1&JobID=' + JobID;
				
			} else {
				
			    return;
			    
			}
			
		}
	
	//-->
	</script>
	
	 <table border="0" width="100%" cellspacing="0" cellpadding="0">
	   <tr>
	     <td width="100%">
	       <table cellspacing="0" cellpadding="0" width="100%">
	         <tr>
	           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
	           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Current Email Jobs.</td>
	           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
	         </tr>
	       </table>
	       <table cellpadding="0" cellspacing="0">
	         <tr>
	           <td><img src="images/spacer.gif" width="100%" height="1"></td>
	         </tr>
	       </table>
	       <table cellspacing="0" cellpadding="0" width="100%">
	         <tr>
	           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
	           <td width="100%" background="images/nav_02.gif" height="2"></td>
	           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
	         </tr>
	         <tr>
	           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
	           <td width="100%" background="images/nav_05.gif" style="padding: 3px">
	
	
					<table width="100%" cellspacing="0" border="0" cellpadding="3">
						<tr>
							<td style="border-bottom: 1px solid #C6C6C6;"><b>JobID</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;"><b>JobName</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right"><b>Email Size</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right"><b>Total Size Sent</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right"><b>Total Emails</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right"><b>Number Sent</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right">&nbsp;</td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right">&nbsp;</td>
						</tr>
						<?
						
						$Foo = 1;
						
						if(checkmodule("SuperUser")) {
						
							$EmailQuery = dbRead("select tbl_templates.*, tbl_jobs.* from tbl_jobs, tbl_templates where (tbl_jobs.templateID = tbl_templates.FieldID) order by tbl_jobs.FieldID DESC","etxint_email_system");
						
						} else {
						
							$EmailQuery = dbRead("select tbl_templates.*, tbl_jobs.* from tbl_jobs, tbl_templates where (tbl_jobs.templateID = tbl_templates.FieldID) and userID IN (" . get_areas_allowed($_SESSION['User']['CID']) . ") order by tbl_jobs.FieldID DESC","etxint_email_system");
						
						}
						
						while($EmailRow = mysql_fetch_assoc($EmailQuery)) {
						
							$EmailNumSQL = dbRead("select count(FieldID) as TotalNum, sum(mailSent) as TotalSent from tbl_jobs_emails where JobID = " . $EmailRow['FieldID'], "etxint_email_system");
							$EmailNumRow = @mysql_fetch_assoc($EmailNumSQL);
							
							$BGColor1 = "#F7F7F7";
							$BGColor2 = "#FCFCFC";

                    		ob_start();
                    		readfile("http://admin.ebanctrade.com/includes/email_system/templates/" . $EmailRow['templateFile'] . "?jobID=" . $EmailRow['FieldID']);
                    		$dataResult = ob_get_contents();
                    		ob_end_clean();
							
							$BGColor = $BGColor1;
							$Foo % 2 ? 0: $BGColor = $BGColor2;
							
							?>
							<tr bgcolor="<?= $BGColor ?>">
								<td><?= $EmailRow['FieldID'] ?></td>
								<td><a href="?page=email_system/defaultnew&JobID=<?= $EmailRow['FieldID'] ?>&tab=tab1&Info=true" class="nav"><?= $EmailRow['JobName'] ?></a></td>
								<td align="right"><?= GetFileSize(strlen($dataResult)) ?></td>
								<td align="right"><?= GetFileSize(strlen($dataResult)*$EmailNumRow['TotalSent']) ?></td>
								<td align="right"><?= number_format($EmailNumRow['TotalNum']) ?></td>
								<td align="right"><?= number_format($EmailNumRow['TotalSent']) ?></td>
								<td align="right"><a class="nav" href="body.php?page=email_system/defaultnew&tab=tab1&Edit=true&JobID=<?= $EmailRow['FieldID'] ?>"><img alt="Edit" src="/images/edit.png" border="0"></a></td>
								<td align="right"><a class="nav" href="javascript:confirmDel('<?= $EmailRow['FieldID'] ?>')"><img alt="Delete" src="/images/delete.png" border="0"></a></td>
							</tr>
							<?
						
							$Foo++;
							$TotalData += strlen($dataResult)*$EmailNumRow['TotalSent'];
							
						}
						
						?>
							<tr bgcolor="<?= $BGColor ?>">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="right"><b>Total:</b></td>
								<td align="right"><b><?= GetFileSize($TotalData) ?></b></td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>
	
						<tr>
							<td colspan="6" align="left"><img src="images/spacer.gif" width="1" height="1"></td>
						</tr>
					</table>
	
	
			   </td>
	           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
	         </tr>
	         <tr>
	           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
	           <td width="100%" background="images/nav_10.gif" height="2"></td>
	           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
	         </tr>
	       </table>
	     </td>
	   </tr>
	 </table>
	 <table cellpadding="0" cellspacing="0" width="100%">
	   <tr>
	     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
	   </tr>
	 </table>
	 
 	<?
 
 	}
 	
	function checkSpam($jobID) {
		
		include("includes/email_system/htmlMimeMail.php");
		
		$dataSQL = dbRead("select tbl_templates.*, tbl_jobs.* from tbl_jobs, tbl_templates where (tbl_jobs.templateID = tbl_templates.FieldID) and tbl_jobs.FieldID = " . $jobID, "etxint_email_system");
		$dataRow = @mysql_fetch_assoc($dataSQL);
		
		ob_start();
		readfile("http://admin.ebanctrade.com/includes/email_system/templates/" . $dataRow['templateFile'] . "?jobID=" . $jobID);
		$dataResult .= ob_get_contents();
		ob_end_clean();
		
		$result = $this->smtpSend($jobID,"Spam","Test",$dataResult,$_SESSION['User']['EmailAddress'],$dataRow['Subject'],$dataRow['FromAddress'],$dataRow['ReturnPath'],$dataRow['isBcc'],$jobID);
		
		print "Spam Email Sent";
		
	}
	 	
	function addNew() {
 		
		/**
		 * Clear $_SESSION['emailAdminS'] out to start a fresh
		 */
		
		unset($_SESSION['emailAdminS']);
		
		?>
		<form method="POST" enctype="multipart/form-data" action="body.php">
		
		<input type="hidden" name="Next" value="1">
		<input type="hidden" name="page" value="email_system/defaultnew">
		<input type="hidden" name="tab" value="tab2">
				
		 <table border="0" width="100%" cellspacing="0" cellpadding="0">
		   <tr>
		     <td width="100%">
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
		           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Add New Mailout Job.</td>
		           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
		         </tr>
		       </table>
		       <table cellpadding="0" cellspacing="0">
		         <tr>
		           <td><img src="images/spacer.gif" width="100%" height="1"></td>
		         </tr>
		       </table>
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_02.gif" height="2"></td>
		           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
		         </tr>
		         <tr>
		           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		           <td width="100%" background="images/nav_05.gif" style="padding: 3px">
		
						<table cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td align="right" width="200"><b>Job Name:</b></td>
								<td width="85%"><input type="text" name="JobName" size="35"></td>
							</tr>
							<tr>
								<td align="right" width="200"><b>Subject:</b></td>
								<td width="85%"><input type="text" name="Subject" size="35"></td>
							</tr>
							<tr>
								<td align="right" width="200"><b>Members Market:</b></td>
								<td width="85%"><input type="checkbox" value="1" name="membersMarket"></td>
							</tr>
							<tr>
								<td align="right" width="200"><b>Template:</b></td>
								<td width="85%"><select name="templateID">
								<?

									$SQLQuery = dbRead("select tbl_templates.* from tbl_templates","etxint_email_system");
									while($SQLRow = mysql_fetch_assoc($SQLQuery)) {

										?>

											<option value="<?= $SQLRow['FieldID'] ?>"><?= $SQLRow['templateName'] ?></option>

										<?
									}

								?>
								</select></td>
							</tr>
							<tr>
								<td align="right" width="200"><b>Template Data:</b></td>
								<td width="85%"><select name="templateDataID"><option></option>
								<?

									$SQLQuery = dbRead("select tbl_templates_templates.* from tbl_templates_templates where areaID IN (".get_areas_allowed($_SESSION['User']['CID']).")","etxint_email_system");
									while($SQLRow = mysql_fetch_assoc($SQLQuery)) {

										?>

											<option value="<?= $SQLRow['fieldID'] ?>"><?= $SQLRow['templateName'] ?></option>

										<?
									}

								?>
								</select></td>
							</tr>
							<tr>
								<td align="right"><b>From Address:</b></td>
								<td><select name="FromAddress">
									<? 

										if($_SESSION['User']['AreasAllowed'] == "all") {
									
    									?>
    									<option value="customersupport@ebanctrade.com"<? if($JobRow['FromAddress'] == "customersupport@ebanctrade.com") { print " selected"; } ?>>customersupport@ebanctrade.com</option>
    									<?
									
									}
									
									$SQLQuery = dbRead("select area.* from area where FieldID in (".get_areas_allowed($_SESSION['User']['CID']).") and pubemail != ''");
									while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
									
										?>
											
											<option value="<?= $SQLRow['pubemail'] ?>"><?= $SQLRow['pubemail'] ?></option>
										
										<?
										
									}
								
								?>
								</select></td>
							</tr>
							<tr>
								<td align="right"><b>Return Path Address:</b></td>
								<td><select name="ReturnPath">
									<? 

										if($_SESSION['User']['AreasAllowed'] == "all") {
									
    									?>
    									<option value="customersupport@ebanctrade.com"<? if($JobRow['FromAddress'] == "customersupport@ebanctrade.com") { print " selected"; } ?>>customersupport@ebanctrade.com</option>
    									<?
									
									}
									$SQLQuery = dbRead("select area.* from area where FieldID in (".get_areas_allowed($_SESSION['User']['CID']).") and pubemail != ''");
									while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
									
										?>
											
											<option value="<?= $SQLRow['pubemail'] ?>"><?= $SQLRow['pubemail'] ?></option>
										
										<?
										
									}
								
								?>
								</select></td>
							</tr>
							<tr>
								<td align="right">&nbsp;</td>
								<td><input type="submit" value="Next Step >>"></td>
							</tr>
						</table>
		
				   </td>
		           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		         </tr>
		         <tr>
		           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_10.gif" height="2"></td>
		           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
		         </tr>
		       </table>
		     </td>
		   </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" width="100%">
		   <tr>
		     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
		   </tr>
		 </table>
		
		
		</form>
		<?
 
	}
 	
	function addWho() {
 		
		$checkSQL = dbRead("select count(mailSent) as mailSent from tbl_jobs_emails where mailSent = 1 and jobID = " . $_REQUEST['jobID'], "etxint_email_system");
 		$checkRow = mysql_fetch_assoc($checkSQL);
 		
 		if($checkRow['mailSent']) {
 		
 			print "Can not add more emails after you have started to send the email.";
 			die;
 		
 		}
 		
		?>
		<form method="POST" enctype="multipart/form-data" action="includes/email_system/addItem.php">
		
		<input type="hidden" name="Next" value="2">
		<input type="hidden" name="dbAdd" value="1">
		<input type="hidden" name="jobID" value="<?= $_REQUEST['jobID'] ?>">
		<input type="hidden" name="page" value="email_system/defaultnew">
		<input type="hidden" name="tab" value="tab2">
		<input type="hidden" name="addItem" value="templateAddresses">
				
		 <table border="0" width="100%" cellspacing="0" cellpadding="0">
		   <tr>
		     <td width="100%">
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
		           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Add Address to Mailout Job.</td>
		           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
		         </tr>
		       </table>
		       <table cellpadding="0" cellspacing="0">
		         <tr>
		           <td><img src="images/spacer.gif" width="100%" height="1"></td>
		         </tr>
		       </table>
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_02.gif" height="2"></td>
		           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
		         </tr>
		         <tr>
		           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		           <td width="100%" background="images/nav_05.gif" style="padding: 3px">
		
					<script language="JavaScript" type="text/javascript">
					
					function ChangeCountry(list) {
					 var url = 'https://admin.ebanctrade.com/body.php?page=listshq&tab=Email List&countryid=' + list.options[list.selectedIndex].value;
					 if (url != "") {
					  location.href=url;
					 }
					}
					
					</script>

					<?
					
					if($_REQUEST['countryid']) {
						
						$GET_CID = $_REQUEST['countryid'];
						
					} else {
						
						$GET_CID = $_SESSION['User']['CID'];
						
					}
					
					?>

					<input type="hidden" name="countryid" value="<?= $GET_CID?>">
					<table border="0" cellpadding="1" cellspacing="1" width="639">

					<?
					
					if($_SESSION['User']['Area'] == 1)  {
						
						?>
						
						<tr>
							<td height="30" align="left"><b>Country:</b>
							<select name="countryid" id="countryid" onChange="ChangeCountry(this);">
							<?	
							
								$dbgetarea = dbRead("select * from country where Display = 'Yes' order by name ASC");
								while($row = mysql_fetch_assoc($dbgetarea)) {
									
									?><option <? if ($row['countryID'] == $GET_CID) { echo "selected "; } ?>value="<?= $row['countryID'] ?>"><?= $row['name'] ?></option><?
									
								}
									
							?>
							</select></td>
						</tr> 
						
						<?
					
					}
					
					?>  
					
						<tr>
							<td>
								<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
									<tr>
	    								<td width="600">Please select the ares to send the email.<br><br>
          									<b><?= get_word("78") ?>:</b><br>
											<?
											
											if($_SESSION['User']['AreasAllowed'] == "all")  {
												
												?>
												
												<select size="10" name="disarea[]" multiple>
												
												<?
									          	
									           		$query2 = dbRead("select RegionalName, tbl_area_regional.FieldID from tbl_area_regional where CID='".$GET_CID."' order by RegionalName");
									           		while($row2 = mysql_fetch_assoc($query2)) {
									           	
									            		?>
									            
									            		<option value="<?= $row2['FieldID'] ?>"><?= $row2['RegionalName'] ?></option>
									            
									            		<?
									           		}
									           		
									          	?>   
									          	      
											</select>
										</td>
									</tr>
									<tr> 
										<td width="600"><b><?= get_word("24") ?>:</b><br>         
										
											<?
											
											}
												
											?>
											
											<select size="10" name="area[]" multiple>
											
												<?
											
												$query5 = dbRead("select area.statewide, tbl_area_states.FieldID, tbl_area_states.StateName from area, tbl_area_physical, tbl_area_regional, tbl_area_states where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and area.FieldID = ".$_SESSION['User']['Area']."");
												$row5 = mysql_fetch_assoc($query5);
											
												if($_SESSION['User']['ReportsAllowed'] == 'all')  {
												
													$areas = "";        
												
												}  else  {            
												
													if($row5['statewide'] == 1)  {        
												
														$areas = " and tbl_area_regional.StateID = ".$row5['FieldID']."";         
												
													}  else  {
													
														$count=0;
														$newarray = explode(",", $_SESSION['User']['ReportsAllowed']);
													
														foreach($newarray as $cat_val) {
															
															if($count == 0) {
																
																$andor="";
																
															} else {
																
																$andor=",";
																
															}
													
															$cat_array.="".$andor."".$cat_val."";
													
															$count++;
													
														}
														
														$areas = " and (area.FieldID in ($cat_array))";   

													}
													
												}
												
												$query2 = dbRead("select AreaName, tbl_area_physical.FieldID as FieldID, area.FieldID as id from area, tbl_area_physical, tbl_area_regional where (area.PhysicalID = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and area.CID='$GET_CID'$areas order by AreaName");
												while($row2 = mysql_fetch_assoc($query2)) {
													
													?>
													
													<option value="<?= $row2[FieldID] ?>,<?= $row2[id] ?>"><?= $row2[AreaName] ?></option>
													
													<?
													
												}
												
												?>
												
											</select>
										</td>
									</tr>
									<?
									
									if($ff) {
										
										?>
										
										<tr>         
											<td width="600"><b><?= get_word("25") ?>:</b><br>          
												<select size="10" name="lic[]" multiple>
												<?
												
													if($_SESSION['User']['ReportsAllowed'] == 'all')  {
														
														$areas = "";      
														  
													}  else  {
														
														$areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";   
													
													}
										
													$query2 = dbRead("select place, area.FieldID as FieldID from area where area.CID='".$GET_CID."'$areas order by place");
													while($row2 = mysql_fetch_assoc($query2)) {
														
														?>
														
														<option value="<?= $row2['FieldID'] ?>"><?= $row2['place'] ?></option>
														
														<?
														
													}
													
													?>
													
												</select>          
											</td>
										</tr>
										
									<?
									
									}
									
									?>
											
										<tr>
											<td width="600">
												<select size="10" name="cat[]" multiple>
													<?
													
													$query2 = dbRead("select category,catid from categories where CID='$GET_CID' order by category");
													while($row2 = mysql_fetch_assoc($query2)) {
													
														?>
														
														<option value="<?= $row2[catid] ?>"><?= $row2[category] ?></option>
														
														<?
													
													}
													
													?>
												</select>	
											</td>      
										</tr>          
										<tr>
											<td>
												<br>Real estate only:<input type="checkbox" name="re" value="1"><br>       
												<br>Email List:&nbsp;<input type="radio" name="all" value="1" checked>
												<br>Sponsorships:&nbsp;<input type="radio" name="sponsor" value="1"><br>
												<input type="Submit" value=" Add ">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
		           		
				   </td>
		           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		         </tr>
		         <tr>
		           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_10.gif" height="2"></td>
		           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
		         </tr>
		       </table>
		     </td>
		   </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" width="100%">
		   <tr>
		     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
		   </tr>
		 </table>
		
		</form>
		<?
 
	}
	
 	function doSend($jobID) {
 		
 			flush();
 		
 		?>
 		
	 		<script language="JavaScript" type="text/javascript" src="/javascript/xp_progress.js"></script>
	 		<DIV ID="splashScreen" align="center" style="display: block">
				<p>Please wait. Sending Emails...</p>
				<script type="text/javascript">
					var splashScreenBar = createBar(300,15,'white',1,'#003399','blue',85,7,3,"");
				</script>
			</DIV>
			<DIV ID="pageContent" align="center" style="display: none">
				<p>Finished Sending Emails.</p>
			</DIV>
			<!-- Start IE Bug Fix -->
			  </td>
		        </tr>
		        <tr>
		          <td width="100%">
		          </td>
		        </tr>
		      </table>
		    </td>
		    <td valign="top"><img border="0" src="images/layout_spacer.gif" width="10" height="10"></td>
		  </tr>
		</table>
		<!-- End IE Bug Fix -->
		<!--
 		<?
 		
 		flush();
 		
		$dataSQL = dbRead("select tbl_templates.*, tbl_jobs.* from tbl_jobs, tbl_templates where (tbl_jobs.templateID = tbl_templates.FieldID) and tbl_jobs.FieldID = " . $jobID, "etxint_email_system");
		$dataRow = @mysql_fetch_assoc($dataSQL);
		
		ob_start();
		readfile("http://admin.ebanctrade.com/includes/email_system/templates/" . $dataRow['templateFile'] . "?jobID=" . $jobID);
		$dataResult .= ob_get_contents();
		ob_end_clean();
		
		$SQLQuery = dbRead("select tbl_jobs_emails.* from tbl_jobs_emails where tbl_jobs_emails.JobID = ".$jobID." and mailSent = 0 group by EmailAddress","etxint_email_system");
		while($Row = mysql_fetch_assoc($SQLQuery)) {
		
			$result = $this->smtpSend($jobID,$Row[FirstName],$Row[LastName],$dataResult,$Row[EmailAddress],$dataRow['Subject'],$dataRow['FromAddress'],$dataRow['ReturnPath'],$dataRow['isBcc'],$jobID);
			
			if($result) {
				//add_log("Sent: $Row[EmailAddress]");
				dbWrite("UPDATE tbl_jobs_emails SET mailSent = 1 WHERE FieldID = " . $Row[FieldID], "etxint_email_system");			
			} else {
				//add_log("<b>FAILED: $row[EmailAddress] </b>");
			}
		
			flush();
			
		
		}
	    
		//dbWrite("UPDATE tbl_jobs_emails SET mailSent = 1 where jobID = " . $jobID, "etxint_email_system");			
	    
		?>
		-->
			<script type="text/javascript">
				    document.getElementById("splashScreen").style.display = "none";
    				document.getElementById("pageContent").style.display = "block";
			</script>
			
		<?
	 		
	}
 	
	function editJob($JobID) {
		
		if($_REQUEST['updateDB']) {
			
			dbWrite("update tbl_jobs set JobName = '".addslashes($_REQUEST['JobName'])."', Subject = '".addslashes($_REQUEST['Subject'])."', FromAddress = '".addslashes($_REQUEST['FromAddress'])."', ReturnPath = '".addslashes($_REQUEST['ReturnPath'])."', websiteView = '".addslashes($_REQUEST['websiteView'])."', membersMarket = '".addslashes($_REQUEST['membersMarket'])."' where FieldID = " . $_REQUEST['jobID'], "etxint_email_system");
			
		}
		
		$JobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . $JobID, "etxint_email_system");
		$JobRow = mysql_fetch_assoc($JobQuery);
		
		$templateQuery = dbRead("select tbl_templates.* from tbl_templates where FieldID = " . $JobRow['templateID'] ,"etxint_email_system");
		$templateRow = mysql_fetch_assoc($templateQuery);
		
		?>
		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<form method="post">
		<input type="hidden" name="jobID" value="<?= $JobRow['FieldID'] ?>">
		<input type="hidden" name="tab" value="<?= $_REQUEST['tab'] ?>">
		<input type="hidden" name="updateDB" value="1">
		<input type="hidden" name="Edit" value="1">
		   <tr>
		     <td width="100%">
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
		           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Edit Job</td>
		           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
		         </tr>
		       </table>
		       <table cellpadding="0" cellspacing="0">
		         <tr>
		           <td><img src="images/spacer.gif" width="100%" height="1"></td>
		         </tr>
		       </table>
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_02.gif" height="2"></td>
		           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
		         </tr>
		         <tr>
		           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		           <td width="100%" background="images/nav_05.gif" style="padding: 3px">	
		
					<table width="100%" cellpadding="3" cellspacing="0" border="0">
						<tr>
							<td width="50%" valign="top">
								<table width="100%" cellpadding="3" cellspacing="0" border="0">
									<tr>
										<td width="100" align="right"><b>Job ID:</b></td>
										<td><?= $JobRow['FieldID'] ?></td>
									</tr>
									<tr>
										<td width="100" align="right"><b>Job Name:</b></td>
										<td><input type="text" name="JobName" value="<?= $JobRow['JobName'] ?>" size="30"></td>
									</tr>
									<tr>
										<td width="100" align="right"><b>Subject:</b></td>
										<td><input type="text" name="Subject" value="<?= $JobRow['Subject'] ?>" size="30"></td>
									</tr>
									<tr>
										<td align="right"><b>Template:</b></td>
										<td><?= $templateRow['templateName'] ?></td>
									</tr>
									<tr>
										<td align="right"><b>Members Market:</b></td>
										<td><input type="checkbox" value="1" name="membersMarket" <? if($JobRow['membersMarket']) { print "checked"; } ?>></td>
									</tr>
									<tr>
										<td align="right" valign="top"><b>From Address:</b></td>
										<td><select name="FromAddress">
            									<? 
            
            										if($_SESSION['User']['AreasAllowed'] == "all") {
            									
                									?>
                									<option value="customersupport@ebanctrade.com"<? if($JobRow['FromAddress'] == "customersupport@ebanctrade.com") { print " selected"; } ?>>customersupport@ebanctrade.com</option>
                									<?
            									
            									}
												$SQLQuery = dbRead("select area.* from area where FieldID in (".get_areas_allowed($_SESSION['User']['CID']).") and pubemail != ''");
												while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
												
													?>
														
														<option value="<?= $SQLRow['pubemail'] ?>"<? if($JobRow['FromAddress'] == $SQLRow['pubemail']) { print " selected"; } ?>><?= $SQLRow['pubemail'] ?></option>
													
													<?
													
												}
											
											?>
										</select></td>
									</tr>
									<tr>
										<td align="right" valign="top"><b>Return Path:</b></td>
										<td><select name="ReturnPath">
            									<? 
            
            										if($_SESSION['User']['AreasAllowed'] == "all") {
            									
                									?>
                									<option value="customersupport@ebanctrade.com"<? if($JobRow['FromAddress'] == "customersupport@ebanctrade.com") { print " selected"; } ?>>customersupport@ebanctrade.com</option>
                									<?
            									
            									}
												$SQLQuery = dbRead("select area.* from area where FieldID in (".get_areas_allowed($_SESSION['User']['CID']).") and pubemail != ''");
												while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
												
													?>
														
														<option value="<?= $SQLRow['pubemail'] ?>"<? if($JobRow['ReturnPath'] == $SQLRow['pubemail']) { print " selected"; } ?>><?= $SQLRow['pubemail'] ?></option>
													
													<?
													
												}
											
											?>
										</select></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td><input type="submit" value="Update Job"></td>
									</tr>
								</table>
							</td>
							<td width="50%" valign="top">
								<table width="100%" cellpadding="3" cellspacing="0" border="0">
									<tr>
										<td width="100" valign="top" align="right"><b>Add Item to Job:</b></td>
										<td>
											<?
												
												$templateFields = unserialize($templateRow['templateFields']);	
											
												foreach($templateFields as $key => $value) {
													
													if($value) {
													
														?><a target="addJobItem" href="body.php?page=email_system/addItem&jobID=<?= $JobRow['FieldID'] ?>&addItem=<?= $key ?>&tab=<?= $_REQUEST['tab'] ?>"><?= $value ?></a><br><?
															
													}
														
												}
											
											?>
											<br>
											<a target="addJobItem" href="body.php?page=email_system/addItem&jobID=<?= $JobRow['FieldID'] ?>&addItem=templateSpamCheck&tab=<?= $_REQUEST['tab'] ?>">Check for Spam</a><br>
											<a target="addJobItem" href="includes/email_system/templates/<?= $templateRow['templateFile'] ?>?jobID=<?= $JobRow['FieldID'] ?>&editCMS=1">Preview Email</a><br>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="border-top: 1px solid #999999">
								
								<?
								
									if($_REQUEST['addItem']) {
										
										?>
										
											<div align="center"><iframe name="addJobItem" src="body.php?page=email_system/addItem&jobID=<?= $JobRow['FieldID'] ?>&addItem=<?= $_REQUEST['addItem'] ?>" width="100%" height="700px" scrolling="auto" frameborder="0"></iframe></div>
										
										<?
										
									} else {
										
										?>
										
											<div align="center"><iframe name="addJobItem" src="includes/email_system/templates/<?= $templateRow['templateFile'] ?>?jobID=<?= $JobRow['FieldID'] ?>&editCMS=1" width="100%" height="700px" scrolling="auto" frameborder="0"></iframe></div>
											
										<?
											
									}
								
								?>
							
							</td>
						</tr>
					</table>
						
			       </td>
		           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		         </tr>
		         <tr>
		           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_10.gif" height="2"></td>
		           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
		         </tr>
		       </table>
		     </td>
		   </tr>
	   	  </form>
		 </table>
		 <table cellpadding="0" cellspacing="0" width="100%">
		   <tr>
		     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
		   </tr>
	
		 </table>

		 <?
		
	}
	
	function displayImages() {
		
		if($_REQUEST['editID']) {
			
			if($_REQUEST['deleteImage']) {
			
				dbWrite("delete from tbl_jobs_images where FieldID = " . $_REQUEST['editID'], "etxint_email_system");
			
			} else {
			
				if(is_file($_FILES['updateImage']['tmp_name'])) {
				
					$imageData = base64_encode(fread(fopen($_FILES['updateImage']['tmp_name'], "r"), $_FILES['updateImage']['size']));
					unlink($_FILES['updateImage']['tmp_name']);
					
					dbWrite("update tbl_jobs_images set imageMimeType = '".$_FILES['updateImage']['type']."', imageData = '".$imageData."' where fieldID = " . $_REQUEST['editID'],"etxint_email_system");
							
				}
				
				if($_REQUEST['updateData']) {
				
					dbWrite("update tbl_jobs_images set imageName = '".$_REQUEST['imageName']."', imageType = '".$_REQUEST['imageType']."' where fieldID = " . $_REQUEST['editID'],"etxint_email_system");
				
				}
						
				$imageSQL = dbRead("select * from tbl_jobs_images where fieldID = " . $_REQUEST['editID'],"etxint_email_system");
				$imageRow = mysql_fetch_assoc($imageSQL);
			
			}
		} elseif($_FILES['newImage']) {
			
			$imageData = base64_encode(fread(fopen($_FILES['newImage']['tmp_name'], "r"), $_FILES['newImage']['size']));
			unlink($_FILES['newImage']['tmp_name']);
			
			dbWrite("insert into tbl_jobs_images (imageName,imageType,imageMimeType,imageData) values ('".addslashes($_REQUEST['imageName'])."','".addslashes($_REQUEST['imageType'])."','".$_FILES['newImage']['type']."','".$imageData."')","etxint_email_system");
			
		}
		
		?>
		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<input type="hidden" name="tab" value="tab3">
		<?
		 
			if($_REQUEST['editID']) {
			
				?>
					<input type="hidden" name="updateData" value="1">
				<?
			
			}
		
		?>
		   <tr>
		     <td width="100%">
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
		           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Manage Images</td>
		           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
		         </tr>
		       </table>
		       <table cellpadding="0" cellspacing="0">
		         <tr>
		           <td><img src="images/spacer.gif" width="100%" height="1"></td>
		         </tr>
		       </table>
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_02.gif" height="2"></td>
		           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
		         </tr>
		         <tr>
		           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		           <td width="100%" background="images/nav_05.gif" style="padding: 3px">	
					<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="tab" value="tab3">
					<?
					 
						if($_REQUEST['editID']) {
						
							?>
								<input type="hidden" name="updateData" value="1">
							<?
						
						}
					
					?>
						<table>
							<tr>
								<td><b>Image Name:</b></td>
								<td><input type="text" name="imageName" size="20" value="<?= $imageRow['imageName'] ?>"></td>
							</tr>
							<tr>
								<td><b>Image Type:</b></td>
								<td><select name="imageType">
									<option value="Header" <? if($imageRow['imageType'] == "Header") { print "selected"; } ?>>Header</option>
									<option value="Logo" <? if($imageRow['imageType'] == "Logo") { print "selected"; } ?>>Logo</option>
									<option value="Other" <? if($imageRow['imageType'] == "Other") { print "selected"; } ?>>Other</option>
								</select></td>
							</tr>
							<tr>
								<td><b>Upload Image:</b></td>
								<td>
								<input type="file" name="<? if($_REQUEST['editID']) { print "updateImage"; } else { print "newImage"; } ?>"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type="submit" value="<? if($_REQUEST['editID']) { print " Update Image"; } else { print "Upload Image"; } ?>">&nbsp;<? if($_REQUEST['editID']) { print "<input type=\"submit\" name=\"deleteImage\" value=\" Delete Image \">"; } ?></td>
							</tr>
						</table>
					</form>
					<hr size="1" width="100%">
					<table>
						<?
						
							$imageSQL = dbRead("select tbl_jobs_images.* from tbl_jobs_images","etxint_email_system");
							if(mysql_num_rows($imageSQL)) {
								
								while($imageRow = mysql_fetch_assoc($imageSQL)) {
								
									?>
									
										<tr>
											<td valign="top"><b><?= $imageRow['imageName'] ?></b><br><?= $imageRow['imageMimeType'] ?></td>
											<td><a href="body.php?page=email_system/defaultnew&tab=tab3&editID=<?= $imageRow['fieldID'] ?>"><img border="0" src="includes/email_system/displayImage.php?imageID=<?= $imageRow['fieldID'] ?>" border="0"></a></td>
										</tr>
										
									<?		
								
								}
								
							} else {
								
								?>
								
									<tr>
										<td colspan="2">No images at this time.</td>
									</tr>
									
								<?	
																					
							}
						?>

					</table>
			       </td>
		           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		         </tr>
		         <tr>
		           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_10.gif" height="2"></td>
		           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
		         </tr>
		       </table>
		     </td>
		   </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" width="100%">
		   <tr>
		     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
		   </tr>
	
		 </table>
		
		<?
		
	}
	
	function getOrder($orderType, $jobID, $sectionName) {
	
		$orderSQL = dbRead("select max(orderBy) + 0.01 as maxOrder from tbl_jobs_data where templateSection = '" . $sectionName . "' and templateType = '" . $orderType . "' and jobID = " . $jobID, "etxint_email_system");
		$orderRow = @mysql_fetch_assoc($orderSQL);
		
		if($orderRow['maxOrder'] == "0.01") {
		
    		$orderSQL2 = dbRead("select max(orderBy) as maxOrder from tbl_jobs_data where templateType = '" . $orderType . "' and jobID = " . $jobID, "etxint_email_system");
    		$orderRow2 = @mysql_fetch_assoc($orderSQL2);
			
			if(!$orderRow2['maxOrder']) {
			
				return "1.01";
			
			} else {
			
				return (number_format($orderRow2['maxOrder']) + 1) . ".01";
			
			}
			
		} else {
		
			return $orderRow['maxOrder'];
		
		}
		
	}
	
	function addItem($itemID) {
		
		if($itemID == "templateAddresses") {
			
			if($_REQUEST['dbAdd']) {
			
				$this->dbAddressesAdd($_REQUEST['jobID']);
				print "Done adding addresses.";
				die;
			
			}
			
			$this->addWho();
		
		} elseif($itemID == "templateTitle") {
			
			if($_REQUEST['templateTitle']) {
			
				$testSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateTitle' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
				if(mysql_num_rows($testSQL)) {
					
					$testRow = mysql_fetch_assoc($testSQL);
					dbWrite("update tbl_jobs_data set templateData = '".$_REQUEST['templateTitle']."' where FieldID = " . $testRow['FieldID'], "etxint_email_system");
						
				} else {
					
					dbWrite("insert into tbl_jobs_data (jobID,templateType,templateData) values ('".addslashes($_REQUEST['jobID'])."','templateTitle','".addslashes($_REQUEST['templateTitle'])."')", "etxint_email_system");
					
				}
			
			}
			
			$bilineSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateTitle' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
			$bilineRow = mysql_fetch_assoc($bilineSQL);
			
			?>
				<form method="post">
				<input type="hidden" name="JobID" value="<?= $_REQUEST['JobID']?>">
				<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
				<input type="hidden" name="addItem" value="templateTitle">
				<table>
					<tr>
						<td colspan="2"><b>Change Template Title:</b></td>
					</tr>
					<tr>
						<td><b>Title:</b></td>
						<td><input type="text" name="templateTitle" size="40" value="<?= $bilineRow['templateData'] ?>"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Update Title"></td>
					</tr>

				</table>
				</form>
			<?

		} elseif($itemID == "templateSpamCheck") {
			
			$this->checkSpam($_REQUEST['jobID']);

		} elseif($itemID == "templateBackgroundColor") {

			if($_REQUEST['templateBackgroundColor']) {
			
				$testSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateBackgroundColor' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
				if(mysql_num_rows($testSQL)) {
					
					$testRow = mysql_fetch_assoc($testSQL);
					dbWrite("update tbl_jobs_data set templateData = '".$_REQUEST['templateBackgroundColor']."' where FieldID = " . $testRow['FieldID'], "etxint_email_system");
						
				} else {
					
					dbWrite("insert into tbl_jobs_data (jobID,templateType,templateData) values ('".addslashes($_REQUEST['jobID'])."','templateBackgroundColor','".addslashes($_REQUEST['templateBackgroundColor'])."')", "etxint_email_system");
					
				}
			
			}
			
			$bilineSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateBackgroundColor' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
			$bilineRow = mysql_fetch_assoc($bilineSQL);
			
			?>
				<form method="post">
				<input type="hidden" name="JobID" value="<?= $_REQUEST['JobID']?>">
				<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
				<input type="hidden" name="addItem" value="templateBackgroundColor">
				<table>
					<tr>
						<td colspan="2"><b>Change tmplate background colour:</b></td>
					</tr>
					<tr>
						<td><b>Colour:</b></td>
						<td><input type="text" name="templateBackgroundColor" size="40" value="<?= $bilineRow['templateData'] ?>"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Update Background Colour"></td>
					</tr>

				</table>
				</form>
			<?
			
		} elseif($itemID == "templateBiline") {
			
			if($_REQUEST['templateBiline']) {
			
				$testSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateBiline' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
				if(mysql_num_rows($testSQL)) {
					
					$testRow = mysql_fetch_assoc($testSQL);
					dbWrite("update tbl_jobs_data set templateData = '".addslashes($_REQUEST['templateBiline'])."' where FieldID = " . $testRow['FieldID'], "etxint_email_system");
						
				} else {
					
					dbWrite("insert into tbl_jobs_data (jobID,templateType,templateData) values ('".addslashes($_REQUEST['jobID'])."','templateBiline','".addslashes($_REQUEST['templateBiline'])."')", "etxint_email_system");
					
				}
			
			}
			
			$bilineSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateBiline' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
			$bilineRow = mysql_fetch_assoc($bilineSQL);
			
			?>
				<form method="post">
				<input type="hidden" name="JobID" value="<?= $_REQUEST['JobID']?>">
				<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
				<input type="hidden" name="addItem" value="templateBiline">
				<table>
					<tr>
						<td colspan="2"><b>Change Template Biline:</b></td>
					</tr>
					<tr>
						<td><b>BiLine:</b></td>
						<td><input type="text" name="templateBiline" size="40" value="<?= $bilineRow['templateData'] ?>"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Update Biline"></td>
					</tr>

				</table>
				</form>
			<?
		
		} elseif($itemID == "templateCommunity") {
			
			if($_REQUEST['addtoDB']) {
				
				$insertArray = array(
					'title' => $_REQUEST['sectionTitle'],
					'data' => $_REQUEST['sectionData'],
					'contact' => $_REQUEST['sectionContact']
					);
				
				$newOrder = $this->getOrder("Community", $_REQUEST['jobID'], addslashes($_REQUEST['sectionSection']));
				
				dbWrite("insert into tbl_jobs_data (jobID,templateType,templateSection,templateData,orderBy) values ('".addslashes($_REQUEST['jobID'])."','Community','".addslashes($_REQUEST['sectionSection'])."','".addslashes(serialize($insertArray))."','" . $newOrder . "')","etxint_email_system");
				
			}
			
			$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'Community' and JobID = " . addslashes($_REQUEST['jobID']) . " Order By templateSection, `orderBy`", "etxint_email_system");
			while($templateDataRow = mysql_fetch_assoc($templateDataQuery)) {
					
				$sectionArray[$templateDataRow['templateSection']][] = $templateDataRow['templateData'];
				
			}
			
			?>
			<form method="post">
			<input type="hidden" name="JobID" value="<?= $_REQUEST['JobID']?>">
			<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
			<input type="hidden" name="addItem" value="templateCommunity">
			<input type="hidden" name="addtoDB" value="1">
			<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
			<script src="/hardcore/webeditor/webeditor.js"></script>
			<table width="100%">
				<tr>
					<td colspan="2"><b>Template Sections TOP:</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>Add Section</b></td>
				</tr>
				<tr>
					<td align="right"><b>Section:</b></td>
					<td><input type="text" name="sectionSection" size="40"></td>
				</tr>
				<tr>
					<td align="right"><b>Title:</b></td>
					<td><input type="text" name="sectionTitle" size="40"></td>
				</tr>
				<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td>
						<script>HardCoreWebEditorToolbar(null,'formatclass fontname fontsize bold underline forecolor insertorderedlist insertunorderedlist createlink mailto anchor', 'unlink removeformat justifyleft justifycenter justifyright justifyfull superscript subscript strikethrough', 'createtable tableproperties insertcaption insertrowhead insertrowfoot rowproperties insertrowabove insertrowbelow deleterow splitcellrows columnproperties insertcolumnleft insertcolumnright deletecolumn splitcellcolumns cellproperties insertcellleft insertcellright deletecell splitcell mergecells', 'viewsource help insertmedia');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Data:</b></td>
					<td>
						<script>sectionData_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionData','','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','150','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Contact:</b></td>
					<td>
						<script>sectionContact_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionContact','','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','150','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Add Section"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			</form>
			
			<?
		
		} elseif($itemID == "templateImage") {
			
			if($_REQUEST['templateImage']) {
			
				$testSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateImage' and jobID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
				if(mysql_num_rows($testSQL)) {
					
					$testRow = mysql_fetch_assoc($testSQL);
					dbWrite("update tbl_jobs_data set templateData = '".$_REQUEST['templateImage']."' where FieldID = " . $testRow['FieldID'], "etxint_email_system");
						
				} else {
					
					dbWrite("insert into tbl_jobs_data (jobID,templateType,templateData) values ('".addslashes($_REQUEST['jobID'])."','templateImage','".addslashes($_REQUEST['templateImage'])."')", "etxint_email_system");
					
				}
				
			}
			
			?>
			
				<form method="post">
				<input type="hidden" name="JobID" value="<?= $_REQUEST['JobID']?>">
				<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
				<input type="hidden" name="addItem" value="templateImage">
				<table>
					<tr>
						<td colspan="2"><b>Change Header Image:</b></td>
					</tr>
					<tr>
						<td><b>Image:</b></td>
						<td><select name="templateImage">
							
							<?
							
								$imageSQL = dbRead("select tbl_jobs_images.* from tbl_jobs_images where imageType = 'Header' and areaID in (".get_areas_allowed($_SESSION['User']['CID']).")","etxint_email_system");
								while($imageRow = mysql_fetch_assoc($imageSQL)) {
								
									?><option value="<?= $imageRow['fieldID'] ?>"><?= $imageRow['imageName'] ?></option><?
										
								}
								
							?>
						
						</select></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Change Image"></td>
					</tr>
				</table>
				</form>
			
			<?

		} elseif($itemID == "templateSections") {
			
			if($_REQUEST['addtoDB']) {
				
				$insertArray = array(
					'title' => $_REQUEST['sectionTitle'],
					'data' => $_REQUEST['sectionData'],
					'contact' => $_REQUEST['sectionContact']
					);
				
				$newOrder = $this->getOrder("Section", $_REQUEST['jobID'], addslashes($_REQUEST['sectionSection']));
				
				dbWrite("insert into tbl_jobs_data (jobID,templateType,templateSection,templateData,orderBy) values ('".addslashes($_REQUEST['jobID'])."','Section','".addslashes($_REQUEST['sectionSection'])."','".addslashes(serialize($insertArray))."','" . $newOrder . "')","etxint_email_system");
				
			}
			
			$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'Section' and JobID = " . addslashes($_REQUEST['jobID']) . " Order By templateSection, `orderBy`", "etxint_email_system");
			while($templateDataRow = mysql_fetch_assoc($templateDataQuery)) {
					
				$sectionArray[$templateDataRow['templateSection']][] = $templateDataRow['templateData'];
				
			}
			
			?>
			<form method="post">
			<input type="hidden" name="JobID" value="<?= $_REQUEST['JobID']?>">
			<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
			<input type="hidden" name="addItem" value="templateSections">
			<input type="hidden" name="addtoDB" value="1">
			<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
			<script src="/hardcore/webeditor/webeditor.js"></script>
			<table width="100%">
				<tr>
					<td colspan="2"><b>Template Sections:</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>Add Section</b></td>
				</tr>
				<tr>
					<td align="right"><b>Section:</b></td>
					<td><input type="text" name="sectionSection" size="40"></td>
				</tr>
				<tr>
					<td align="right"><b>Title:</b></td>
					<td><input type="text" name="sectionTitle" size="40"></td>
				</tr>
				<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td>
						<script>HardCoreWebEditorToolbar(null,'formatclass fontname fontsize bold underline forecolor insertorderedlist insertunorderedlist createlink mailto anchor', 'unlink removeformat justifyleft justifycenter justifyright justifyfull superscript subscript strikethrough', 'createtable tableproperties insertcaption insertrowhead insertrowfoot rowproperties insertrowabove insertrowbelow deleterow splitcellrows columnproperties insertcolumnleft insertcolumnright deletecolumn splitcellcolumns cellproperties insertcellleft insertcellright deletecell splitcell mergecells', 'viewsource help insertmedia');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Data:</b></td>
					<td>
						<script>sectionData_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionData','','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','150','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Contact:</b></td>
					<td>
						<script>sectionContact_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionContact','','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','150','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Add Section"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			</form>
			
			<?

		} elseif($itemID == "templateArticleEdit") {
			
			if($_REQUEST['updateDB']) {
				
				$insertArray = array(
					'title' => $_REQUEST['sectionTitle'],
					'data' => $_REQUEST['sectionData'],
					'contact' => $_REQUEST['sectionContact']
					);
				
				dbWrite("update tbl_jobs_data set templateSection = '".addslashes($_REQUEST['sectionSection'])."', templateData = '".addslashes(serialize($insertArray))."' where FieldID = " . $_REQUEST['articleID'],"etxint_email_system");
				
			}
			
			$editSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where fieldID = " . $_REQUEST['articleID'], "etxint_email_system");
			$editRow = @mysql_fetch_assoc($editSQL);
			
			$editArray = unserialize($editRow['templateData']);
			
			?>
			<form method="post">
			<input type="hidden" name="articleID" value="<?= $_REQUEST['articleID'] ?>">
			<input type="hidden" name="tab" value="<?= $_REQUEST['tab'] ?>">
			<input type="hidden" name="addItem" value="templateArticleEdit">
			<input type="hidden" name="updateDB" value="1">
			<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
			<script src="/hardcore/webeditor/webeditor.js"></script>
			<table width="100%">
				<tr>
					<td colspan="2"><b>Template Sections:</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>Add Section</b></td>
				</tr>
				<tr>
					<td align="right"><b>Section:</b></td>
					<td><input type="text" name="sectionSection" size="40" value="<?= $editRow['templateSection'] ?>"></td>
				</tr>
				<tr>
					<td align="right"><b>Title:</b></td>
					<td><input type="text" name="sectionTitle" size="40" value="<?= $editArray['title']?>"></td>
				</tr>
				<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td>
						<script>HardCoreWebEditorToolbar(null,'formatclass fontname fontsize bold underline forecolor insertorderedlist insertunorderedlist createlink mailto anchor', 'unlink removeformat justifyleft justifycenter justifyright justifyfull superscript subscript strikethrough', 'createtable tableproperties insertcaption insertrowhead insertrowfoot rowproperties insertrowabove insertrowbelow deleterow splitcellrows columnproperties insertcolumnleft insertcolumnright deletecolumn splitcellcolumns cellproperties insertcellleft insertcellright deletecell splitcell mergecells', 'viewsource help insertmedia');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Data:</b></td>
					<td>
						<script>sectionData_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionData','<?= addslashes(str_replace("\r", "", str_replace("\n", "", str_replace("\\", "\\\\", stripslashes($editArray['data']))))) ?>','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','150','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Contact:</b></td>
					<td>
						<script>sectionContact_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionContact','<?= addslashes(str_replace("\r", "", str_replace("\n", "", str_replace("\\", "\\\\", stripslashes($editArray['contact']))))) ?>','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','150','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Update Section"></td>
				</tr>
			</table>
			</form>
			
			<?

		} elseif($itemID == "templateData") {
			
			if($_REQUEST['addtoDB']) {
				
				/**
				 * Check to see if its there already
				 * If it is update if not add.
				 */
				
				$checkSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateData' and jobID = " . $_REQUEST['jobID'], "etxint_email_system");
				if(mysql_num_rows($checkSQL)) {
				
					/**
					 * Update
					 */
						
					dbWrite("update tbl_jobs_data set templateData = '".addslashes($_REQUEST['sectionData'])."' where templateType = 'templateData' and jobID = " . $_REQUEST['jobID'], "etxint_email_system");
					
				} else {
				
					/**
					 * Add
					 */
					
					dbWrite("insert into tbl_jobs_data (jobID,templateType,templateData) values ('".$_REQUEST['jobID']."','templateData','".addslashes($_REQUEST['sectionData'])."')","etxint_email_system");		
								
				}

			}
			
			$dataSQL = dbRead("select tbl_jobs_data.* from tbl_jobs_data where templateType = 'templateData' and jobID = " . $_REQUEST['jobID'], "etxint_email_system");
			$dataRow = mysql_fetch_assoc($dataSQL);
			
			?>
			<form method="post">
			<input type="hidden" name="jobID" value="<?= $_REQUEST['jobID']?>">
			<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
			<input type="hidden" name="addItem" value="templateData">
			<input type="hidden" name="addtoDB" value="1">
			<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
			<script src="/hardcore/webeditor/webeditor.js"></script>
			<table width="100%">
				<tr>
					<td colspan="2"><b>Template Data:</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>Add Data</b></td>
				</tr>
				<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td>
						<script>HardCoreWebEditorToolbar(null,'formatclass fontname fontsize bold underline forecolor insertorderedlist insertunorderedlist createlink mailto anchor', 'unlink removeformat justifyleft justifycenter justifyright justifyfull superscript subscript strikethrough', 'createtable tableproperties insertcaption insertrowhead insertrowfoot rowproperties insertrowabove insertrowbelow deleterow splitcellrows columnproperties insertcolumnleft insertcolumnright deletecolumn splitcellcolumns cellproperties insertcellleft insertcellright deletecell splitcell mergecells', 'viewsource help insertmedia');</script>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Data:</b></td>
					<td>
						<script>sectionData_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','sectionData','<?= addslashes(str_replace("\r", "", str_replace("\n", "", str_replace("\\", "\\\\", stripslashes($dataRow['templateData']))))) ?>','','https://admin.ebanctrade.com/includes/email_system/editor.css',true,'manager','','','','','','100%','300','xhtml','');</script>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Update Data"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>

			</table>
			</form>
			
			<?

		} elseif($itemID == "templateAttachments") {
			
			if($_REQUEST['addtoDB']) {
			
				if(is_file($_FILES['fileAttachment']['tmp_name'])) {
					
					$fileExt = strtolower(substr(strrchr($_FILES['fileAttachment']['name'], "."), 1));
					
					$imageData = base64_encode(fread(fopen($_FILES['fileAttachment']['tmp_name'], "r"), $_FILES['fileAttachment']['size']));
					unlink($_FILES['fileAttachment']['tmp_name']);
					
					dbWrite("insert into tbl_jobs_attachments (JobID,Name,mimeType,Data,fileExt,fileSize) values ('" . $_REQUEST['jobID'] . "','" . $_REQUEST['fileAttachmentName'] . "','" . $_FILES['fileAttachment']['type'] . "','" . $imageData . "','" . $fileExt . "','" . $_FILES['fileAttachment']['size'] . "')","etxint_email_system");
							
				}
							
			}
			
			?>
			
			<form method="post"  enctype="multipart/form-data">
			<input type="hidden" name="jobID" value="<?= $_REQUEST['jobID']?>">
			<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
			<input type="hidden" name="addItem" value="templateAttachments">
			<input type="hidden" name="addtoDB" value="1">
			<table width="100%">
				<tr>
					<td colspan="2"><b>Add Attachment:</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>Add Attachment</b></td>
				</tr>
				<tr>
					<td><b>Name:</b></td>
					<td><input type="text" name="fileAttachmentName" size="30"></td>
				</tr>
				<tr>
					<td><b>File:</b></td>
					<td><input type="file" name="fileAttachment" size="30"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Add File"></td>
				</tr>
			</table>			
			</form>
			<?

		} elseif($itemID == "templateEmailaddresses") {
			
			if($_REQUEST['addtoDB']) {
			
				dbWrite("insert into tbl_jobs_emails (JobID,EmailAddress,mailSent,isBcc) values ('".$_REQUEST['jobID']."','".$_REQUEST['emailAddress']."','0','1')","etxint_email_system");
					
			}
			
			?>
			
			<form method="post">
			<input type="hidden" name="jobID" value="<?= $_REQUEST['jobID']?>">
			<input type="hidden" name="tab" value="<?= $_REQUEST['tab']?>">
			<input type="hidden" name="addItem" value="templateEmailaddresses">
			<input type="hidden" name="addtoDB" value="1">
			<table width="100%">
				<tr>
					<td colspan="2"><b>Add Email Address:</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><b>Add Email Address</b></td>
				</tr>
				<tr>
					<td><b>Email Address</b></td>
					<td><input type="text" name="emailAddress" size="30"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Add Emailaddress"></td>
				</tr>
			</table>
			</form>
			<?

			
		}
		
	}
	
	function displayDetail($JobID) {
	 
		$JobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . $JobID, "etxint_email_system");
		$JobRow = mysql_fetch_assoc($JobQuery);
		
		$EmailNumSQL = dbRead("select count(FieldID) as TotalNum, sum(mailSent) as TotalSent from tbl_jobs_emails where JobID = " . $JobRow['FieldID'], "etxint_email_system");
		$EmailNumRow = @mysql_fetch_assoc($EmailNumSQL);
	  
		?>
		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<form method="post" action="body.php?page=email_system/defaultnew">
		<input type="hidden" name="jobID" value="<?= $JobRow['FieldID'] ?>">
		<input type="hidden" name="doSend" value="1">
		<input type="hidden" name="tab" value="tab1">
		   <tr>
		     <td width="100%">
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
		           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">JobDetails</td>
		           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
		         </tr>
		       </table>
		       <table cellpadding="0" cellspacing="0">
		         <tr>
		           <td><img src="images/spacer.gif" width="100%" height="1"></td>
		         </tr>
		       </table>
		       <table cellspacing="0" cellpadding="0" width="100%">
		         <tr>
		           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_02.gif" height="2"></td>
		           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
		         </tr>
		         <tr>
		           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		           <td width="100%" background="images/nav_05.gif" style="padding: 3px">	
		
						<table cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td align="right" width="200"><b>Job Name</b></td>
								<td width="85%" ><?= $JobRow['JobName'] ?></td>
							</tr>
							<tr>
								<td align="right" width="200"><b>Subject</b></td>
								<td width="85%"><?= $JobRow['Subject'] ?></td>
							</tr>
							<tr>
								<td align="right"><b>Total Addresses</b></td>
								<td ><?= $EmailNumRow['TotalNum'] ?></td>
							</tr>
							<tr>
								<td align="right"><b>Total Sent</b></td>
								<td ><?= $EmailNumRow['TotalSent'] ?></td>
							</tr>
							<tr>
								<td align="right"><b>From Address</b></td>
								<td ><?= $JobRow['FromAddress'] ?></td>
							</tr>
							<tr>
								<td align="right"><b>Return Path</b></td>
								<td ><?= $JobRow['ReturnPath'] ?></td>
							</tr>
							<tr>
								<td align="right" ><b>Preview</b></td>
								<td ><a target="_new" href="http://media.ebanctrade.com/bulletin.php?jobID=<?= $JobRow['FieldID'] ?>" class="nav">
								Click to preview email</a></td>
							</tr>
							<tr>
								<td align="right">&nbsp;</td>
								<td ><input type="submit" value="Send Email Job" <? if($EmailNumRow['TotalNum'] == $EmailNumRow['TotalSent']) { print "disabled"; } ?>></td>
							</tr>
						</table>
						
			       </td>
		           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
		         </tr>
		         <tr>
		           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
		           <td width="100%" background="images/nav_10.gif" height="2"></td>
		           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
		         </tr>
		       </table>
		     </td>
		   </tr>
	   	  </form>
		 </table>
		 <table cellpadding="0" cellspacing="0" width="100%">
		   <tr>
		     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
		   </tr>
	
		 </table>
						
	
		<?
	 	
	}
	
	function smtpSend($jobID,$first_name,$last_name,$html,$to,$subject,$from,$return,$bcc = false) {
	
		$mail = new htmlMimeMail();
		
		$mail->setHtmlEncoding("7bit");
		$mail->setHtml($html);
		
			/**
			 * Check to see if there are any attachments to add.
			 */
			
			$attachSQL = dbRead("select tbl_jobs_attachments.* from tbl_jobs_attachments where JobID = " . $jobID, "etxint_email_system");
			while($attachRow = @mysql_fetch_assoc($attachSQL)) {
			
				$mail->addAttachment(base64_decode($attachRow['Data']), $attachRow['Name'] . "." . $attachRow['fileExt'], $attachRow['mimeType']);	
			
			}
					
		$mail->setFrom($from);
		//$mail->setReturnPath($return);
		$mail->setSubject($subject);
		
		if($bcc) {
			$mail->setHeader("Bcc", $to);
			$result = $mail->send(array('E Banc Traders <noreply@ebanctrade.com>'),'mail');
		} else {
			$result = $mail->send(array('E Banc Traders <'.$to.'>'),'mail');
		}
			
		return $result;
	
	}
		
 }

?>