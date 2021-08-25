<?

 /** 
  * E Banc Trade Feedback
  *
  * feedback.php
  * Version 0.02
  */

 include("includes/modules/class.paging.php");
?>
<form method="POST" action="body.php?page=feedback&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?
 $tabarray = array("Feedbacks", "Summary", "Complaints and Comments");

 displaytabs($tabarray);

 if($_REQUEST[tab] == "tab1") {
  
  if($_REQUEST['DisplayForm']) {
  
   display_form();
  
  } else {
  
   display_feedbacks();

  } 

 } elseif($_REQUEST['tab'] == "tab2") {
 
   sum();
 
 } elseif($_REQUEST['tab'] == "tab3") {
  
  if($_REQUEST['DisplayForm']) {
  
   display_cnc_form();
  
  } else {
  
   display_cnc();

  } 
  
 }
?>
</form>
<?
 function display_form() {
 
 	$SQLQuery = dbRead("select tbl_members_feedback.* from tbl_members_feedback where FieldID = " . $_REQUEST['FeedbackID']);
	$SQLRow = mysql_fetch_assoc($SQLQuery);
  	
  	$Answers = unserialize($SQLRow['FormData']);
  	
	?>
	<table cellspacing="0" cellpadding="3" width="620">
		<tr>
			<td style="font-weight: bold;" align="center" colspan="2">Feedback - <?= $SQLRow['AccountName'] ?> [<?= $SQLRow['FieldID'] ?>]</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">1. What do you consider to be your level of understanding of the concept of trade, and the services available through E Banc Trade?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question1'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            3. I visit an E Banc Trade Office</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question3'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            4. Would you attend a training session on maximising the benefits of your E Banc Trade membership?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question4'] ?><br>&nbsp;&nbsp;<?= $Answers['Question4b'] ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            5. Would you prefer to receive emails/faxes promoting member services, specials and exchange events</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question5'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            6. Do you consider regular personal contact from your customer support team</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question6'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            7. My preferred form of communication is</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question7'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            8. Which benefits are you gaining from your membership?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
			<?
			
				if($Answers['Question8']) {
				
				?>
				
				<table>
				
					<?
					
					foreach($Answers['Question8'] as $Key => $Value) {
					
					?>
				
    					<tr>
    						<td width="120"><?= $Key ?></td>
    						<td><?= $Value ?></td>
    					</tr>
					
					<?
					
					}
					
					?>
					
				</table>
				
				<?
				
				}
				
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            9. Would you refer business acquaintances to E Banc Trade?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question9'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            10. Are you a member of more than one trade exchange</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question10'] ?>&nbsp;<br>&nbsp;&nbsp;<?= $Answers['Question10b'] ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">11. How would you generally rate our customer service?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question11a'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">11b. When talking to our office staff, do you find them</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
					<tr>
						<td width="120">Helpful</td>
						<td><?= $Answers['Question11b']['Helpful'] ?></td>
					</tr>			
					<tr>
						<td>Friendly</td>
						<td><?= $Answers['Question11b']['Friendly'] ?></td>
					</tr>			
					<tr>
						<td>Polite</td>
						<td><?= $Answers['Question11b']['Polite'] ?></td>
					</tr>			
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">11c. How can we improve our customer service</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
					<tr>
						<td width="120">Part 1</td>
						<td><?= $Answers['Question11c']['Part 1'] ?></td>
					</tr>			
					<tr>
						<td>Part 2</td>
						<td><?= $Answers['Question11c']['Part 2'] ?></td>
					</tr>			
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12a. How often would you visit our Website</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question12a'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12b. Which services do you use and how often</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
			<?
			
				if($Answers['Question12b']) {
				
				?>
				
				<table>
				
					<?
					
					foreach($Answers['Question12b'] as $Key => $Value) {
					
					?>
				
    					<tr>
    						<td width="120"><?= $Key ?></td>
    						<td><?= $Value ?></td>
    					</tr>
					
					<?
					
					}
					
					?>
					
				</table>
				
				<?
				
				}
				
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12c. How would you generally rate our online services?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question12c'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12d. How would you rate specific online services?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
			<?
			
				if($Answers['Question12d']) {
				
				?>
				
				<table>
				
					<?
					
					foreach($Answers['Question12d'] as $Key => $Value) {
					
					?>
				
    					<tr>
    						<td width="120"><?= $Key ?></td>
    						<td><?= $Value ?></td>
    					</tr>
					
					<?
					
					}
					
					?>
					
				</table>
				
				<?
				
				}
				
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12e. How can we improve our service?</td>
		</tr>
		<tr>
			<td width="50">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $Answers['Question12e'] ?>&nbsp;</td>
		</tr>
	</table>
	<?
 
 }

 function display_feedbacks() {
 
 	?>
 	<table cellpadding="3" cellspacing="0" width="620">
 		<tr>
 			<td style="font-weight: bold;" align="center" colspan="5">Feedback List</td>
 		</tr>
 		<tr>
 			<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; font-weight: bold;">Date</td>
 			<td style="border-top: 1px solid #000000; font-weight: bold;">Account Name</td>
 			<td style="border-top: 1px solid #000000; font-weight: bold;">AccNo</td>
 			<td style="border-top: 1px solid #000000; font-weight: bold;">Postcode</td>
 			<td style="border-top: 1px solid #000000; border-right: 1px solid #000000; font-weight: bold;">Area</td>
 		</tr>
 	<?
 
 	$Counter = 0;
 
	$SQLQuery = dbRead("select tbl_members_feedback.* from tbl_members_feedback order by Date");
 	while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
 		
 		$SQLRow['AccountName'] = ($SQLRow['AccountName']) ? $SQLRow['AccountName'] : "Name Not Entered";
 		
 		$BGColor = ($Counter % 2) ? "#FFFFFF" : "DDDDDD";
 	
 		?>
 		<tr style="background: <?= $BGColor ?>">
 			<td style="border-left: 1px solid #000000"><?= $SQLRow['Date'] ?></td>
 			<td><a class="nav" href="body.php?page=feedback&tab=tab1&DisplayForm=true&FeedbackID=<?= $SQLRow['FieldID'] ?>"><?= $SQLRow['AccountName'] ?> [<?= $SQLRow['FieldID'] ?>]</a></td>
 			<td><?= $SQLRow['AccountNumber'] ?></td>
 			<td><?= $SQLRow['PostCode'] ?></td>
 			<td style="border-right: 1px solid #000000"><?= $SQLRow['LicenseeArea'] ?>&nbsp;</td>
 		</tr>
 		<?
 		
 		$Counter++;
 	
 	}
 
	?>
 		<tr>
 			<td style="border-top: 1px solid #000000; font-weight: bold;" colspan="5" align="center">Total: <?= $Counter ?></td>
 		</tr>
	</table>
	<? 

 }

 function display_cnc_form() {
 
 	$SQLQuery = dbRead("select tbl_complaints.* from tbl_complaints where FieldID = " . $_REQUEST['ComplaintID']);
	$SQLRow = mysql_fetch_assoc($SQLQuery);
  	
	$UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = " . $SQLRow['EmployID']);
	$UserRow = mysql_fetch_assoc($UserSQL);
  	
	?>
	<table width="400" cellpadding="3" cellspacing="0" border="0">
		<tr>
			<td align="center"><a href="javascript:print();" class="nav">Print</a></td>
		</tr>
	</table>
	<table cellpadding="1" border="0" cellspacing="0" width="400">
		<tr>
			<td class="Border">
				<table width="100%" border="0" cellpadding="3" cellspacing="0">
					<tr>
						<td align="center" class="Heading"><b>Complaints Form</b></td>
					</tr>
				</table>
				<table width="100%" border="0" cellpadding="3" cellspacing="0">
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Employee:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= $UserRow['Name'] ?></td>
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Complaint Made By:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= $SQLRow['MadeBy'] ?></td>
					</tr>
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Reason for Complaint:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= which_complaint($SQLRow['Reason']) ?></td>
					</tr>
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Complaint:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= $SQLRow['Complaint'] ?></td>
					</tr>
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Corrective action taken:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= $SQLRow['Corrective'] ?></td>
					</tr>
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Follow up required:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= $SQLRow['Follow'] ?></td>
					</tr>
					<tr>
						<td width="140" align="right" class="Heading2" height="1">Comments:</td>
						<td width="225" align="left" bgcolor="#FFFFFF"><?= $SQLRow['Comments'] ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?
 
 }

 function display_cnc() {
 
 	?>
 	<table cellpadding="3" cellspacing="0" width="620">
 		<tr>
 			<td style="font-weight: bold;" align="center" colspan="5">Comments and Complaints List</td>
 		</tr>
 		<tr>
 			<td style="border-top: 1px solid #000000; border-left: 1px solid #000000; font-weight: bold;">Date</td>
 			<td style="border-top: 1px solid #000000; font-weight: bold;">Account Name</td>
 			<td style="border-top: 1px solid #000000; font-weight: bold;">Employee</td>
 			<td style="border-top: 1px solid #000000; border-right: 1px solid #000000; font-weight: bold;">Reason</td>
 		</tr>
 	<?
 
 	$Counter = 0;
 
	$SQLQuery = dbRead("select tbl_complaints.* from tbl_complaints order by Date");
 	while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
 		
 		$SQLRow['MadeBy'] = ($SQLRow['MadeBy']) ? $SQLRow['MadeBy'] : "Name Not Entered";
 		
 		$UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = " . $SQLRow['EmployID']);
 		$UserRow = mysql_fetch_assoc($UserSQL);
 		
 		$BGColor = ($Counter % 2) ? "#FFFFFF" : "DDDDDD";
 	
 		?>
 		<tr style="background: <?= $BGColor ?>">
 			<td style="border-left: 1px solid #000000"><?= $SQLRow['Date'] ?></td>
 			<td><a class="nav" href="body.php?page=feedback&tab=tab3&DisplayForm=true&ComplaintID=<?= $SQLRow['FieldID'] ?>"><?= $SQLRow['MadeBy'] ?> [<?= $SQLRow['AccNo'] ?>]</a></td>
 			<td><?= $UserRow['Name'] ?></td>
 			<td style="border-right: 1px solid #000000"><?= which_complaint($SQLRow['Reason']) ?>&nbsp;</td>
 		</tr>
 		<?
 		
 		$Counter++;
 	
 	}
 
	?>
 		<tr>
 			<td style="border-top: 1px solid #000000; font-weight: bold;" colspan="5" align="center">Total: <?= $Counter ?></td>
 		</tr>
	</table>
	<? 

 }

function sum() {

 if($_REQUEST['search']) {

 $startdate = date("Y-m-d",mktime(0,0,0,$_REQUEST['currentmonth']-$_REQUEST['numbermonths'],1,$_REQUEST['currentyear']));
 $enddate = date("Y-m-d",mktime(0,0,0,$_REQUEST['currentmonth']+1,1,$_REQUEST['currentyear']));
 $enddate2 = date("Y-m-d",mktime(0,0,0,$_REQUEST['currentmonth']+1,1-1,$_REQUEST['currentyear']));

 $SQLQuery = dbRead("select tbl_members_feedback.* from tbl_members_feedback where Date >= '$startdate' and Date < '$enddate'");
 while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
	
  $Answers = unserialize($SQLRow['FormData']);
 //print_r($Answers); 	
  foreach($Answers as $Key => $Value) {
  	
     switch($Key) {
  		
  	  case "Question1":
        switch($Value) {
  		
  	     case "Good":
  	      $result["Question1"]["Good"]++;
          break;

  	     case "Average":
  	      $result["Question1"]["Average"]++;      
          break;

  	     case "Poor":
  	      $result["Question1"]["Poor"]++;      
          break;
          
        }   	  
        break;

  	  case "Question3":
        switch($Value) {
  		
  	     case "Frequently":
  	      $result["Question3"]["Frequently"]++;
          break;

  	     case "Seldom":
  	      $result["Question3"]["Seldom"]++;      
          break;

  	     case "Never":
  	      $result["Question3"]["Never"]++;      
          break;
          
        }       
        break;
       
  	  case "Question4":
        switch($Value) {
  		
  	     case "Yes":
  	      $result["Question4"]["Yes"]++;
          break;

  	     case "No":
  	      $result["Question4"]["No"]++;      
          break;
          
        }       
        break; 

  	  case "Question4b":
        switch($Value) {
  		
  	     case "Daytime":
  	      $result["Question4b"]["Daytime"]++;
          break;

  	     case "Evening":
  	      $result["Question4b"]["Evening"]++;      
          break;
          
        }       
        break; 
                
  	  case "Question5":
        switch($Value) {
  		
  	     case "Weekly":
  	      $result["Question5"]["Weekly"]++;
          break;

  	     case "Fortnightly":
  	      $result["Question5"]["Fortnightly"]++;      
          break;

  	     case "Monthly":
  	      $result["Question5"]["Monthly"]++;      
          break;

  	     case "Never":
  	      $result["Question5"]["Never"]++;      
          break;  
                  
        }       
        break;

  	  case "Question6":
        switch($Value) {
  		
  	     case "Valuable":
  	      $result["Question6"]["Valuable"]++;
          break;

  	     case "Unnecessary":
  	      $result["Question6"]["Unnecessary"]++;      
          break;

  	     case "No Opinion":
  	      $result["Question6"]["No Opinion"]++;      
          break;
          
        }       
        break; 

  	  case "Question7":
        switch($Value) {
  		
  	     case "Fax":
  	      $result["Question7"]["Fax"]++;
          break;

  	     case "Email":
  	      $result["Question7"]["Email"]++;      
          break;

  	     case "Australia Post":
  	      $result["Question7"]["Australia Post"]++;      
          break;
          
        }       
        break; 
                
  	  case "Question8":
        switch($Value["Increased Market Share"]) {
  		
  	     case "Yes":
  	      $result["Question8"]["Increased Market Share"]++;
          break;
                                       
        }       

        switch($Value["Cash conservation"]) {
  		
  	     case "Yes":
  	      $result["Question8"]["Cash conservation"]++;
          break;
                                       
        }       
        
        switch($Value["Improved profit levels"]) {
  		
  	     case "Yes":
  	      $result["Question8"]["Improved profit levels"]++;
          break;
                                       
        }       
         
        
        switch($Value["Improved lifestyle"]) {
  		
  	     case "Yes":
  	      $result["Question8"]["Improved lifestyle"]++;
          break;
                                       
        }       
                 
        switch($Value["Promotion of goods or services"]) {
  		
  	     case "Yes":
  	      $result["Question8"]["Promotion of goods or services"]++;
          break;
                                       
        }       
        
        switch($Value["No benefits"]) {
  		
  	     case "Yes":
  	      $result["Question8"]["No benefits"]++;
          break;
                                       
        }       
        break; 
       
  	  case "Question9":
        switch($Value) {
  		
  	     case "Yes1":
  	      $result["Question9"]["Yes"]++;
          break;

  	     case "No1":
  	      $result["Question9"]["No"]++;      
          break;
          
        }       
        break;
        
  	  case "Question10":
        switch($Value) {
  		
  	     case "Yes":
  	      $result["Question10"]["Yes"]++;
          break;

  	     case "No":
  	      $result["Question10"]["No"]++;      
          break;
          
        }       
        break; 
        
  	  case "Question10b":
        switch($Value) {
  		
  	     case "Excellent Value":
  	      $result["Question10b"]["Excellent Value"]++;
          break;

  	     case "Similar":
  	      $result["Question10b"]["Similar"]++;      
          break;

  	     case "Expensive":
  	      $result["Question10b"]["Expensive"]++;      
          break;
                    
        }       
        break; 

  	  case "Question11a":
        switch($Value) {
  		
  	     case "Excellent":
  	      $result["Question11a"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question11a"]["Good"]++;      
          break;

  	     case "Average":
  	      $result["Question11a"]["Average"]++;      
          break;

  	     case "Poor":
  	      $result["Question11a"]["Poor"]++;      
          break;
                    
        }       
        break;


  	  case "Question11b":
        switch($Value["Helpful"]) {
  		
  	     case "Yes":
  	      $result["Question11b"]["Helpful"]["Yes"]++;
          break;

  	     case "No":
  	      $result["Question11b"]["Helpful"]["No"]++;
          break;
                                                 
        }       
        
        switch($Value["Friendly"]) {
  		
  	     case "Yes":
  	      $result["Question11b"]["Friendly"]["Yes"]++;
          break;

  	     case "No":
  	      $result["Question11b"]["Friendly"]["No"]++;
          break;
                                       
        }       
                
        switch($Value["Polite"]) {
  		
  	     case "Yes":
  	      $result["Question11b"]["Polite"]["Yes"]++;
          break;
          
  	     case "No":
  	      $result["Question11b"]["Polite"]["No"]++;
          break;
                                       
        }       
        break;         

  	  case "Question12a":
        switch($Value) {
  		
  	     case "Weekly":
  	      $result["Question12a"]["Weekly"]++;
          break;

  	     case "Fortnightly":
  	      $result["Question12a"]["Fortnightly"]++;      
          break;

  	     case "Monthly":
  	      $result["Question12a"]["Monthly"]++;      
          break;

  	     case "Never":
  	      $result["Question12a"]["Never"]++;      
          break;
                    
        }       
        break;

  	  case "Question12b":
        switch($Value["Directory_Search"]) {
  		
  	     case "Regulary":
  	      $result["Question12b"]["Directory Search"]["Regulary"]++;
          break;

  	     case "Seldom":
  	      $result["Question12b"]["Directory Search"]["Seldom"]++;
          break;

  	     case "Never":
  	      $result["Question12b"]["Directory Search"]["Never"]++;
          break;
          
  	     case "Unaware":
  	      $result["Question12b"]["Directory Search"]["Unaware"]++;
          break;
                                                                    
        }
        
        switch($Value["Online_trasnactions"]) {        
  	     case "Regulary":
  	      $result["Question12b"]["Online transactions"]["Regulary"]++;
          break;

  	     case "Seldom":
  	      $result["Question12b"]["Online transactions"]["Seldom"]++;
          break;

  	     case "Never":
  	      $result["Question12b"]["Online transactions"]["Never"]++;
          break ;
          
  	     case "Unaware":
  	      $result["Question12b"]["Online transactions"]["Unaware"]++;
          break;
                                                                    
        } 

        switch($Value["Viewing_transaction_Information"]) {
  		
  	     case "Regulary":
  	      $result["Question12b"]["Viewing transaction Information"]["Regulary"]++;
          break;

  	     case "Seldom":
  	      $result["Question12b"]["Viewing transaction Information"]["Seldom"]++;
          break;

  	     case "Never":
  	      $result["Question12b"]["Viewing transaction Information"]["Never"]++;
          break;
          
  	     case "Unaware":
  	      $result["Question12b"]["Viewing transaction Information"]["Unaware"]++;
          break;
                                                                    
        }  
  
        switch($Value["Bid_'n'_Buy_Auction_Site"]) {
  		
  	     case "Regulary":
  	      $result["Question12b"]["Bid 'n' Buy Auction Site"]["Regulary"]++;
          break;

  	     case "Seldom":
  	      $result["Question12b"]["Bid 'n' Buy Auction Site"]["Seldom"]++;
          break;

  	     case "Never":
  	      $result["Question12b"]["Bid 'n' Buy Auction Site"]["Never"]++;
          break;
          
  	     case "Unaware":
  	      $result["Question12b"]["Bid 'n' Buy Auction Site"]["Unaware"]++;
          break;
                                                                    
        } 

        switch($Value["The_Classifieds_Listing"]) {
  		
  	     case "Regulary":
  	      $result["Question12b"]["The Classifieds Listing"]["Regulary"]++;
          break;

  	     case "Seldom":
  	      $result["Question12b"]["The Classifieds Listing"]["Seldom"]++;
          break;

  	     case "Never":
  	      $result["Question12b"]["The Classifieds Listing"]["Never"]++;
          break;
          
  	     case "Unaware":
  	      $result["Question12b"]["The Classifieds Listing"]["Unaware"]++;
          break;
                                                                    
        }        

        switch($Value["Time_To_Trade"]) {
  		
  	     case "Regulary":
  	      $result["Question12b"]["Time_To_Trade"]["Regulary"]++;
          break;

  	     case "Seldom":
  	      $result["Question12b"]["Time_To_Trade"]["Seldom"]++;
          break;

  	     case "Never":
  	      $result["Question12b"]["Time_To_Trade"]["Never"]++;
          break;
          
  	     case "Unaware":
  	      $result["Question12b"]["Time_To_Trade"]["Unaware"]++;
          break;
                                                                    
        }  
        
  	  case "Question12c":
        switch($Value) {
  		
  	     case "Excellent":
  	      $result["Question12c"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12c"]["Good"]++;      
          break;

  	     case "Average":
  	      $result["Question12c"]["Average"]++;      
          break;

  	     case "Poor":
  	      $result["Question12c"]["Poor"]++;      
          break;
          
        }   	  
        break;

  	  case "Question12d":
        switch($Value["Directory Search"]) {
  		
  	     case "Excellent":
  	      $result["Question12d"]["Directory Search"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12d"]["Directory Search"]["Good"]++;
          break;

  	     case "Fair":
  	      $result["Question12d"]["Directory Search"]["Fair"]++;
          break;
          
  	     case "Not Effective":
  	      $result["Question12d"]["Directory Search"]["Not Effective"]++;
          break;
                                                                    
        }

        switch($Value["Online trasnactions"]) {
        
  	     case "Excellent":
  	      $result["Question12d"]["Online transactions"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12d"]["Online transactions"]["Good"]++;
          break;

  	     case "Fair":
  	      $result["Question12d"]["Online transactions"]["Fair"]++;
          break;
          
  	     case "Not Effective":
  	      $result["Question12d"]["Online transactions"]["Not Effective"]++;
          break;
                                                                    
        } 

        switch($Value["Viewing transaction Information"]) {
  		
  	     case "Excellent":
  	      $result["Question12d"]["Viewing transaction Information"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12d"]["Viewing transaction Information"]["Good"]++;
          break;

  	     case "Fair":
  	      $result["Question12d"]["Viewing transaction Information"]["Fair"]++;
          break;
          
  	     case "Not Effective":
  	      $result["Question12d"]["Viewing transaction Information"]["Not Effective"]++;
          break;
                                                                    
        }  
  
        switch($Value["Bid 'n' Buy Auction Site"]) {
  		
  	     case "Excellent":
  	      $result["Question12d"]["Bid 'n' Buy Auction Site"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12d"]["Bid 'n' Buy Auction Site"]["Good"]++;
          break;

  	     case "Fair":
  	      $result["Question12d"]["Bid 'n' Buy Auction Site"]["Fair"]++;
          break;
          
  	     case "Not Effective":
  	      $result["Question12d"]["Bid 'n' Buy Auction Site"]["Not Effective"]++;
          break;
                                                                    
        } 

        switch($Value["The Classifieds Listing"]) {
  		
  	     case "Excellent":
  	      $result["Question12d"]["The Classifieds Listing"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12d"]["The Classifieds Listing"]["Good"]++;
          break;

  	     case "Fair":
  	      $result["Question12d"]["The Classifieds Listing"]["Fair"]++;
          break;
          
  	     case "Not Effective":
  	      $result["Question12d"]["The Classifieds Listing"]["Not Effective"]++;
          break;
                                                                    
        }        

        switch($Value["Time To Trade"]) {
  		
  	     case "Excellent":
  	      $result["Question12d"]["Time To Trade"]["Excellent"]++;
          break;

  	     case "Good":
  	      $result["Question12d"]["Time To Trade"]["Good"]++;
          break;

  	     case "Fair":
  	      $result["Question12d"]["Time To Trade"]["Fair"]++;
          break;
          
  	     case "Not Effective":
  	      $result["Question12d"]["Time To Trade"]["Not Effective"]++;
          break;
                                                                    
        } 
            
     } 	
  	
  	}  	
  }  	
  //print_r($result);
?>

	<table cellspacing="0" cellpadding="3" width="620">
		<tr>
			<td style="font-weight: bold;" align="center" colspan="2">Feedback - <?= $startdate ?> to <?= $enddate2 ?></td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">1. What do you consider to be your level of understanding of the concept of trade, and the services available through E Banc Trade?</td>
		</tr>
		<?
		$q1 = $result['Question1']['Good']+$result['Question1']['Average']+$result['Question1']['Poor'];
		$q1a = number_format(($result['Question1']['Good']/$q1)*100,2);
		$q1b = number_format(($result['Question1']['Average']/$q1)*100,2);
		$q1c = number_format(($result['Question1']['Poor']/$q1)*100,2);		
		?>
		<tr>
			<td width="75" height="29">Good:</td>
			<td width="570" style="border-left: 1px solid #000000" height="29"> <?= $result['Question1']['Good'] ?> / <?= $q1a ?>%&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Average:</td>
			<td width="570" style="border-left: 1px solid #000000"> <?= $result['Question1']['Average'] ?> / <?= $q1b ?>%&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Poor:</td>
			<td width="570" style="border-left: 1px solid #000000"> <?= $result['Question1']['Poor'] ?> / <?= $q1c ?>%&nbsp;</td>
		</tr>		
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            3. I visit an E Banc Trade Office</td>
		</tr>
		<?
		$q3 = $result['Question3']['Frequently']+$result['Question3']['Seldom']+$result['Question3']['Never'];
		$q3a = number_format(($result['Question3']['Frequently']/$q3)*100,2);
		$q3b = number_format(($result['Question3']['Seldom']/$q3)*100,2);
		$q3c = number_format(($result['Question3']['Never']/$q3)*100,2);		
		?>		
		<tr>
			<td width="75">Frequently</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question3']['Frequently'] ?> / <?= $q3a ?>%&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Seldom</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question3']['Seldom'] ?> / <?= $q3b ?>%&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Never</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question3']['Never'] ?> / <?= $q3c ?>%&nbsp;</td>
		</tr>		
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            4. Would you attend a training session on maximising the benefits of your E Banc Trade membership?</td>
		</tr>
		<tr>
			<td width="75">No</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question4']['No'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Yes</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question4']['Yes'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">&nbsp;&nbsp;&nbsp;Daytime</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question4b']['Daytime'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">&nbsp;&nbsp;&nbsp;Evening</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question4b']['Evening'] ?>&nbsp;</td>
		</tr>		
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            5. Would you prefer to receive emails/faxes promoting member services, specials and exchange events</td>
		</tr>
		<tr>
			<td width="75">Weekly</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question5']['Weekly'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Fortnightly</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question5']['Fortnightly'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Monthly</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question5']['Monthly'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Never</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question5']['Never'] ?>&nbsp;</td>
		</tr>				
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            6. Do you consider regular personal contact from your customer support team</td>
		</tr>
		<tr>
			<td width="75">Valuable</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question6']['Valuable'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Unnecessary</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question6']['Unnecessary'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">No Opinion</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question6']['No Opinion'] ?>&nbsp;</td>
		</tr>				
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            7. My preferred form of communication is</td>
		</tr>
		<tr>
			<td width="75">Fax</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question7']['Fax'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Email</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question7']['Email'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Australia Post</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question7']['Australia Post'] ?>&nbsp;</td>
		</tr>				
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            8. Which benefits are you gaining from your membership?</td>
		</tr>
		<tr>
			<td width="75">Increased market share</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question8']['Increased Market Share'] ?>&nbsp;</td>
		</tr>	
		<tr>
			<td width="75">Cash Conservation</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question8']['Cash conservation'] ?>&nbsp;</td>
		</tr>	
		<tr>
			<td width="75">Improved profit levels</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question8']['Improved profit levels'] ?>&nbsp;</td>
		</tr>	
		<tr>
			<td width="75">Improved lifestyle</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question8']['Improved lifestyle'] ?>&nbsp;</td>
		</tr>	
		<tr>
			<td width="75">Promotion of goods or services</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question8']['Promotion of goods or services'] ?>&nbsp;</td>
		</tr>									
		<tr>
			<td width="75">No benefits</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question8']['No benefits'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            9. Would you refer business acquaintances to E Banc Trade?</td>
		</tr>
		<tr>
			<td width="75">No</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question9']['No'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Yes</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question9']['Yes'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">
            10. Are you a member of more than one trade exchange</td>
		</tr>
		<tr>
			<td width="75">No</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question10']['No'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Yes</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question10']['Yes'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">&nbsp;&nbsp;&nbsp;Excellent Value</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question10b']['Excellent Value'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">&nbsp;&nbsp;&nbsp;Similar</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question10b']['Similar'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">&nbsp;&nbsp;&nbsp;Expensive</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question10b']['Expensive'] ?>&nbsp;</td>
		</tr>				
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">11. How would you generally rate our customer service?</td>
		</tr>
		<tr>
			<td width="75">Excellent</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question11a']['Excellent'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Good</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question11a']['Good'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Average</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question11a']['Average'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Poor</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question11a']['Poor'] ?>&nbsp;</td>
		</tr>						
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">11b. When talking to our office staff, do you find them</td>
		</tr>
		<tr>
			<td width="75">Helpful</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
					<tr>
						<td width="50">Yes</td>
						<td><?= $result['Question11b']['Helpful']['Yes'] ?></td>
					</tr>			
					<tr>
						<td>No</td>
						<td><?= $result['Question11b']['Helpful']['No'] ?></td>
					</tr>						
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Friendly</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
					<tr>
						<td width="50">Yes</td>
						<td><?= $result['Question11b']['Friendly']['Yes'] ?></td>
					</tr>			
					<tr>
						<td>No</td>
						<td><?= $result['Question11b']['Friendly']['No'] ?></td>
					</tr>						
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Polite</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
					<tr>
						<td width="50">Yes</td>
						<td><?= $result['Question11b']['Polite']['Yes'] ?></td>
					</tr>			
					<tr>
						<td>No</td>
						<td><?= $result['Question11b']['Polite']['No'] ?></td>
					</tr>						
				</table>
			</td>
		</tr>				
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">11c. How can we improve our customer service</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12a. How often would you visit our Website</td>
		</tr>
		<tr>
			<td width="75">Weekly</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12a']['Weekly'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Fortnightly</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12a']['Fortnightly'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Monthly</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12a']['Monthly'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Never</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12a']['Never'] ?>&nbsp;</td>
		</tr>				
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12b. Which services do you use and how often</td>
		</tr>
		<tr>
			<td width="75">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40">Regulary</td>
    				<td width="40">Seldom</td>
    				<td width="40">Never</td>
    				<td width="40">Unware</td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Directory Search</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12b']['Directory Search']['Regulary'] ?></td>
    				<td width="40"><?= $result['Question12b']['Directory Search']['Seldom'] ?></td>
    				<td width="40"><?= $result['Question12b']['Directory Search']['Never'] ?></td>
    				<td width="40"><?= $result['Question12b']['Directory Search']['Unware'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Online transactions</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12b']['Online transactions']['Regulary'] ?></td>
    				<td width="40"><?= $result['Question12b']['Online transactions']['Seldom'] ?></td>
    				<td width="40"><?= $result['Question12b']['Online transactions']['Never'] ?></td>
    				<td width="40"><?= $result['Question12b']['Online transactions']['Unware'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Viewing transaction Information</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12b']['Viewing transaction Information']['Regulary'] ?></td>
    				<td width="40"><?= $result['Question12b']['Viewing transaction Information']['Seldom'] ?></td>
    				<td width="40"><?= $result['Question12b']['Viewing transaction Information']['Never'] ?></td>
    				<td width="40"><?= $result['Question12b']['Viewing transaction Information']['Unware'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Bid 'n' Buy Auction Site</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12b']["Bid 'n' Buy Auction Site"]['Regulary'] ?></td>
    				<td width="40"><?= $result['Question12b']["Bid 'n' Buy Auction Site"]['Seldom'] ?></td>
    				<td width="40"><?= $result['Question12b']["Bid 'n' Buy Auction Site"]['Never'] ?></td>
    				<td width="40"><?= $result['Question12b']["Bid 'n' Buy Auction Site"]['Unware'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">The Classified Listing</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12b']['The Classifieds Listing']['Regulary'] ?></td>
    				<td width="40"><?= $result['Question12b']['The Classifieds Listing']['Seldom'] ?></td>
    				<td width="40"><?= $result['Question12b']['The Classifieds Listing']['Never'] ?></td>
    				<td width="40"><?= $result['Question12b']['The Classifieds Listing']['Unware'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>								
		<tr>
			<td width="75">Time To Trade</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12b']['Time_To_Trade']['Regulary'] ?></td>
    				<td width="40"><?= $result['Question12b']['Time_To_Trade']['Seldom'] ?></td>
    				<td width="40"><?= $result['Question12b']['Time_To_Trade']['Never'] ?></td>
    				<td width="40"><?= $result['Question12b']['Time_To_Trade']['Unware'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12c. How would you generally rate our online services?</td>
		</tr>
		<tr>
			<td width="75">Excellent</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12c']['Excellent'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Good</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12c']['Good'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Average</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12c']['Average'] ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="75">Poor</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12c']['Poor'] ?>&nbsp;</td>
		</tr>		
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12d. How would you rate specific online services?</td>
		</tr>
		<tr>
			<td width="75">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40">Excellent</td>
    				<td width="40">Good</td>
    				<td width="40">Fair</td>
    				<td width="100">Not Effective</td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Directory Search</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12d']['Directory Search']['Excellent'] ?></td>
    				<td width="40"><?= $result['Question12d']['Directory Search']['Good'] ?></td>
    				<td width="40"><?= $result['Question12d']['Directory Search']['Fair'] ?></td>
    				<td width="40"><?= $result['Question12d']['Directory Search']['Not Effective'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Online transactions</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12d']['Online transactions']['Excellent'] ?></td>
    				<td width="40"><?= $result['Question12d']['Online transactions']['Good'] ?></td>
    				<td width="40"><?= $result['Question12d']['Online transactions']['Fair'] ?></td>
    				<td width="40"><?= $result['Question12d']['Online transactions']['Not Effective'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Viewing transaction Information</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12d']['Viewing transaction Information']['Excellent'] ?></td>
    				<td width="40"><?= $result['Question12d']['Viewing transaction Information']['Good'] ?></td>
    				<td width="40"><?= $result['Question12d']['Viewing transaction Information']['Fair'] ?></td>
    				<td width="40"><?= $result['Question12d']['Viewing transaction Information']['Not Effective'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">Bid 'n' Buy Auction Site</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12d']["Bid 'n' Buy Auction Site"]['Excellent'] ?></td>
    				<td width="40"><?= $result['Question12d']["Bid 'n' Buy Auction Site"]['Good'] ?></td>
    				<td width="40"><?= $result['Question12d']["Bid 'n' Buy Auction Site"]['Fair'] ?></td>
    				<td width="40"><?= $result['Question12d']["Bid 'n' Buy Auction Site"]['Not Effective'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="75">The Classified Listing</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12d']['The Classifieds Listing']['Excellent'] ?></td>
    				<td width="40"><?= $result['Question12d']['The Classifieds Listing']['Good'] ?></td>
    				<td width="40"><?= $result['Question12d']['The Classifieds Listing']['Fair'] ?></td>
    				<td width="40"><?= $result['Question12d']['The Classifieds Listing']['Not Effective'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>								
		<tr>
			<td width="75">Time To Trade</td>
			<td width="570" style="border-left: 1px solid #000000">
				<table>
		  		 <tr>
    				<td width="40"><?= $result['Question12d']['Time To Trade']['Excellent'] ?></td>
    				<td width="40"><?= $result['Question12d']['Time To Trade']['Good'] ?></td>
    				<td width="40"><?= $result['Question12d']['Time To Trade']['Fair'] ?></td>
    				<td width="40"><?= $result['Question12d']['Time To Trade']['Not Effective'] ?></td>
    			 </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; background: #CCCCCC; font-weight: bold;">12e. How can we improve our service?</td>
		</tr>
		<tr>
			<td width="75">&nbsp;</td>
			<td width="570" style="border-left: 1px solid #000000"><?= $result['Question12e'] ?>&nbsp;</td>
		</tr>
	</table>



<?  	
 } else {
?> 
<input type="hidden" name="DisplayStatement" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] - <?= get_page_data("8") ?></td>
	</tr>
	<tr>
		<td width="175" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="475" bgcolor="#FFFFFF">
           <?
            
            $query = dbRead("select tbl_admin_months.* from tbl_admin_months");
            form_select('currentmonth',$query,'Month','FieldID',date("m"));
                        
           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("40") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?
            
			$query = get_month_array();
            form_select('numbermonths',$query,'','','','None');
                        
           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?
            
			$query = get_year_array();
            form_select('currentyear',$query,'','',date("Y"));
                        
           ?>
		</td>
	</tr>
	<tr>
		<td width="150" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="Search" style="size: 8pt"></td>
        <input type="hidden" name="search" value="1">
	</tr>
</table>
</td>
</tr>
</table> 
<? 
 }
}
?>