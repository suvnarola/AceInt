<?

set_time_limit(3600);

include("htmlMimeMail.php");
//include("progressbar.php");

 if($_REQUEST['DoSend']) {

	add_log("Starting Bulk Mail Out");

	$DataQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . $_REQUEST['SendJob'],"etxint_email_system");
	$DataRow = mysql_fetch_assoc($DataQuery);

	$SQLQuery = dbRead("select tbl_jobs_emails.* from tbl_jobs_emails where tbl_jobs_emails.JobID = ".$DataRow['FieldID']." and mailSent = 0","etxint_email_system");
	while($Row = mysql_fetch_assoc($SQLQuery)) {

		$result = SendEmail($Row[FirstName],$Row[LastName],$DataRow['JobData'],$Row[EmailAddress],$DataRow['Subject'],$DataRow['FromAddress'],$DataRow['ReturnPath']);

		if($result) {
			add_log("Sent: $Row[EmailAddress]");
			dbWrite("UPDATE tbl_jobs_emails SET mailSent = 1 WHERE FieldID = " . $Row[FieldID], "etxint_email_system");
		} else {
			add_log("<b>FAILED: $row[EmailAddress] </b>");
		}

	}

	add_log("Finished Sending Bulk Email.");

 }

if($_REQUEST['AttachFileUpload']) {

 //Add New Job to Database.
 $InsertID = dbWrite("insert into tbl_jobs (JobName,Subject,FromAddress,ReturnPath,JobData) values ('".addslashes($_REQUEST['JobName'])."','".addslashes($_REQUEST['Subject'])."','".addslashes($_REQUEST['FromAddress'])."','".addslashes($_REQUEST['ReturnPath'])."','".addslashes($_REQUEST['JobData'])."')","etxint_email_system", true);

 // Process File and add to database.
 $File = $_FILES['emailtext'];
 $SplitFile = file($File['tmp_name']);
 $EmailAddressArray = explode(";", $SplitFile[0]);
 foreach($EmailAddressArray as $Key => $Value) {

  dbWrite("insert into tbl_jobs_emails (JobID,EmailAddress,mailSent) values ('" . $InsertID . "','".addslashes($Value)."','0')","etxint_email_system");

 }

}

function add_log($msg) {
	print "$msg<br>";
	flush();
}

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
<script src="/hardcore/webeditor/webeditor.js"></script>
<title></title>
</head>

<body>

<?

 $TabArray = array('Email Summary','Add New');

 displaytabs($TabArray);

 if($_REQUEST['tab'] == "tab1") {

  if($_REQUEST['JobID']) {

   JobDetail();

  } else {

   DisplayList();

  }

 } elseif($_REQUEST['tab'] == "tab2") {

  if($_REQUEST['Next'] == 1) {

  	addJob();
  	AddNew_Who();

  } elseif($_REQUEST['Next'] == 2) {

  	AddNew_Attach();

  } elseif($_REQUEST['Next'] == 3) {

    AddNew_Data();

  } else {

  	AddNew();

  }


 }

 /*

	if(!$_REQUEST['DoSend']) {

		if($_REQUEST['JobID']) {

			JobDetail();

		} else {

			DisplayList();

		}

		AddNew();

	}

 */

?>

</body>

</html>

<?

 /**
  * Functions
  */

 function addJob() {

 	$_SESSION['emailSystem']['JobID'] = dbWrite("insert into tbl_jobs (JobName,Subject,FromAddress,ReturnPath) values ('".addslashes($_REQUEST['JobName'])."','".addslashes($_REQUEST['Subject'])."','".addslashes($_REQUEST['FromAddress'])."','".addslashes($_REQUEST['ReturnPath'])."')", "etxint_email_system");

 }

 function JobDetail() {

  $JobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . $_REQUEST['JobID'], "etxint_email_system");
  $JobRow = mysql_fetch_assoc($JobQuery);

  $EmailNumSQL = dbRead("select count(FieldID) as TotalNum, sum(mailSent) as TotalSent from tbl_jobs_emails where JobID = " . $JobRow['FieldID'], "etxint_email_system");
  $EmailNumRow = @mysql_fetch_assoc($EmailNumSQL);


  ?>

	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<form method="post">
	<input type="hidden" name="SendJob" value="<?= $JobRow['FieldID'] ?>">
	<input type="hidden" name="DoSend" value="1">
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
							<td ><a target="_new" href="includes/email_system/preview.php?JobID=<?= $JobRow['FieldID'] ?>" class="nav">
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

 function SendEmail($first_name,$last_name,$html,$to,$subject,$from,$return) {

	$mail = new htmlMimeMail();

	$mail->setHtmlEncoding("base64");
	$mail->setHtml($html);

	$mail->setFrom($from);
	$mail->setReturnPath($return);
	$mail->setSubject($subject);

	$result = $mail->send(array($to));

	return $result;

 }

 function DisplayList() {

  ?>

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
							<td style="border-bottom: 1px solid #C6C6C6;" align="right"><b>EDIT</b></td>
							<td style="border-bottom: 1px solid #C6C6C6;" align="right"><b>DEL</b></td>
						</tr>
						<?

						$Foo = 1;

						$EmailQuery = dbRead("select tbl_jobs.* from tbl_jobs order by FieldID DESC","etxint_email_system");
						while($EmailRow = mysql_fetch_assoc($EmailQuery)) {

							$EmailNumSQL = dbRead("select count(FieldID) as TotalNum, sum(mailSent) as TotalSent from tbl_jobs_emails where JobID = " . $EmailRow['FieldID'], "etxint_email_system");
							$EmailNumRow = @mysql_fetch_assoc($EmailNumSQL);

							$BGColor1 = "#F7F7F7";
							$BGColor2 = "#FCFCFC";

							$BGColor = $BGColor1;
							$Foo % 2 ? 0: $BGColor = $BGColor2;

							?>
							<tr bgcolor="<?= $BGColor ?>">
								<td><?= $EmailRow['FieldID'] ?></td>
								<td><a href="?page=email_system/default&JobID=<?= $EmailRow['FieldID'] ?>&tab=tab1" class="nav"><?= $EmailRow['JobName'] ?></a></td>
								<td align="right"><?= GetFileSize(strlen($EmailRow['JobData'])) ?></td>
								<td align="right"><?= GetFileSize(strlen($EmailRow['JobData'])*$EmailNumRow['TotalSent']) ?></td>
								<td align="right"><?= number_format($EmailNumRow['TotalNum']) ?></td>
								<td align="right"><?= number_format($EmailNumRow['TotalSent']) ?></td>
								<td align="right"><a href="#">EDIT</a></td>
								<td align="right"><a href="#">DEL</a></td>
							</tr>
							<?

							$Foo++;
							$TotalData += strlen($EmailRow['JobData'])*$EmailNumRow['TotalSent'];

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

 function AddNew() {

  ?>
	<form method="POST" enctype="multipart/form-data">

	<input type="hidden" name="Next" value="1">

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
							<td align="right"><b>From Address:</b></td>
							<td><select name="FromAddress">
							<?

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

	if($_REQUEST['area'] || $_REQUEST['disarea'] || $_REQUEST['lic'])  {
		add_kpi("51", "0");

		// define the text.
		$text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your updated email list.";

		// get the actual taxinvoice ready.
		$buffer = taxinvoice($type);

		// define carriage returns for macs and pc's
		define('CRLF', "\r\n", TRUE);

		// create a new mail instance
		$mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

		// add the text in.
		$mail->add_text($text);

		// add the attachment on.
		$mail->add_attachment($buffer, 'emaillist.txt', 'text/plain');
		// build the message.
		$mail->build_message();

		// send the message.
		$mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Updated Email List','Bcc: reports@ebanctrade.com');

		echo "Your Email List has been sent to your email address at ".$_SESSION['User']['EmailAddress']."";

	} else {

		echo "An Area Must Be Selected";

	}

 }

 function AddNew_Who() {

//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";

if($_REQUEST[all]) {
  $type="1";
} else {
  $type="2";
}

//echo $type;

?>

<html>
<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=listshq&tab=Email List&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body>

<?

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

?>

<form method="POST" action="body.php?page=listshq" name="am">
<input type="hidden" name="countryid" value="<?= $GET_CID?>">
<table border="0" cellpadding="1" cellspacing="1" width="639">

<?if($_SESSION['User']['Area'] == 1)  {?>
 <tr>
  <td height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}
?>
   </select>&nbsp;</td>
  </tr>
<?}?>
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2"><?= get_page_data("8") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
        <p>&nbsp;</td>
        <p>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600"><?= get_page_data("9") ?>.<br><br>
          <b><?= get_word("78") ?>:</b><br>
			<?if($_SESSION['User']['AreasAllowed'] == 'all')  {?>
          <select size="10" name="disarea[]" multiple>
          <?
           //$query2 = dbRead("select disarea from area where CID='$GET_CID' group by disarea order by disarea");
           $query2 = dbRead("select RegionalName, tbl_area_regional.FieldID from tbl_area_regional where CID='$GET_CID' order by RegionalName");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[RegionalName] ?></option>
            <?
           }
          ?>
          </select>
        </td>
       </tr>
       <tr>
	    <td bgcolor="#FFFFFF" width="600"><b><?= get_word("24") ?>:</b><br>
			<?}?>
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
              //$newarray = explode(",", get_physcial_area($_SESSION['User']['ReportsAllowed']));
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
 			  //$areas = " and (area.FieldID in ($cat_array))";
		   }
		   }
           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
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
       <?if($ff) {?>
       <tr>
	    <td bgcolor="#FFFFFF" width="600"><b><?= get_word("25") ?>:</b><br>
          <select size="10" name="lic[]" multiple>
           <?

          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
 			  $areas = " and (area.FieldID in (".$_SESSION['User']['ReportsAllowed']."))";
		   }

           //$query2 = dbRead("select place,FieldID from area where CID='$GET_CID'$areas group by place order by place");
           $query2 = dbRead("select place, area.FieldID as FieldID from area where area.CID='$GET_CID'$areas order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
	 </td>
    </tr>
    <?}?>
	<tr>
	 <td bgcolor="#FFFFFF" width="600">
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
     <td bgcolor="#FFFFFF">
          <br><br><?= get_page_data("10") ?><input type="checkbox" name="re" value="1"><br>
          <br>
          <input type="Submit" name="all"  value="<?= get_page_data("8") ?>"> <input type="Submit" name="sponsor"  value="<?= get_page_data("12") ?>">
	 </td>
	</tr>
</table>
</td>
</tr>
</table>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

</form>

</body>
</html>
<?

}

?>
<!-- <?= print_r($_REQUEST) ?> -->