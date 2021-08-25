<?

	$NoSession = true;
	
	//include("/home/etxint/admin.etxint.com/includes/global.php");
	//include("/home/etxint/public_html/members/includes/modules/class.phpmailer.php");
	//include("class.html.mime.mail.inc");

	//$currentDate = "18th august 2006";
	$currentDate = date('l dS \of F Y');
	$date2 = date("Y-m-d", mktime(0,0,0,date("m", strtotime($currentDate)),date("d", strtotime($currentDate))-6,date("Y", strtotime($currentDate))));
	$date3 = date("Y-m-d", mktime(0,0,0,date("m", strtotime($currentDate)),date("d", strtotime($currentDate))-13,date("Y", strtotime($currentDate))));

	$dd = " and (Date_Paid >= '$date3')"; 
	//$dd = " and (Date_Paid >= '$date3' and Date_Paid < '$date2')"; 
	
	//$regSQL1 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	//$regSQL2 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '2'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	//$regSQL3 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '3'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	//$regSQL6 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '6'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	//$regSQL7 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '7'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	$regSQL1 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '1' and Status_ID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	$regSQL2 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '2' and Status_ID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	$regSQL3 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '3' and Status_ID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	$regSQL6 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '6' and Status_ID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	$regSQL7 = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '7' and Status_ID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
	
	$total1 = @mysql_num_rows($regSQL1);
	$total2 = @mysql_num_rows($regSQL2);
	$total3 = @mysql_num_rows($regSQL3);
	$total6 = @mysql_num_rows($regSQL6);
	$total7 = @mysql_num_rows($regSQL7);

	$minutesPDF = pdf_new();
    pdf_set_parameter($minutesPDF, "license", "L500102-010000-105258-97D9C0");
    
    pdf_open_file($minutesPDF);
    
    pdf_set_info($minutesPDF, "Author", "RDI Host Pty Ltd / INPrime Pty Ltd");
    pdf_set_info($minutesPDF, "Title", "Meeting Minutes");
    pdf_set_info($minutesPDF, "Creator", "Antony Puckey");
    pdf_set_info($minutesPDF, "Subject", "Cheque Class");
    
    pdf_begin_page($minutesPDF, 595, 842);
    pdf_set_parameter($minutesPDF, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
    pdf_set_parameter($minutesPDF, "textformat", "utf8");
    
    $font = pdf_findfont($minutesPDF, "Verdana", "winansi", 0);
    $fontBold = pdf_findfont($minutesPDF, "VerdanaBold", "winansi", 0);
    
	//get_left_pos($text,$pdf,$centerpos)
	
	pdf_setfont($minutesPDF, $fontBold, 10);
	
	pdf_set_text_pos($minutesPDF, get_left_pos("Minutes of a Meeting of the Board of Directors",$minutesPDF,"297.5"), "780");
	pdf_continue_text($minutesPDF, "Minutes of a Meeting of the Board of Directors");
	
	pdf_setfont($minutesPDF, $font, 10);
	
	pdf_set_text_pos($minutesPDF, get_left_pos("myServicesBanc.com Limited",$minutesPDF,"297.5"), "770");
	pdf_continue_text($minutesPDF, "My Services Banc.com Limited");
	pdf_set_text_pos($minutesPDF, get_left_pos("ACN 119 388 662",$minutesPDF,"297.5"), "760");
	pdf_continue_text($minutesPDF, "ACN 119 388 662");

	pdf_setfont($minutesPDF, $fontBold, 10);

	pdf_set_text_pos($minutesPDF, "50", "720");
	pdf_continue_text($minutesPDF, "Held At:");
	pdf_set_text_pos($minutesPDF, "50", "690");
	pdf_continue_text($minutesPDF, "On:");
	pdf_set_text_pos($minutesPDF, "50", "670");
	pdf_continue_text($minutesPDF, "Present:");
	pdf_set_text_pos($minutesPDF, "50", "650");
	pdf_continue_text($minutesPDF, "Previous Minutes:");
	pdf_set_text_pos($minutesPDF, "50", "620");
	pdf_continue_text($minutesPDF, "Class Memberships:");
	pdf_set_text_pos($minutesPDF, "50", "520");
	pdf_continue_text($minutesPDF, "Closure:");

	pdf_setfont($minutesPDF, $font, 10);
	
	pdf_set_text_pos($minutesPDF, "170", "720");
	pdf_continue_text($minutesPDF, "2 Production Ave");
	pdf_continue_text($minutesPDF, "Warana, Queensland, 4575");
	pdf_set_text_pos($minutesPDF, "170", "690");
	pdf_continue_text($minutesPDF, date('l dS \of F Y', strtotime($currentDate)));
	pdf_set_text_pos($minutesPDF, "170", "670");
	pdf_continue_text($minutesPDF, "Neil East");
	pdf_set_text_pos($minutesPDF, "170", "650");
	pdf_continue_text($minutesPDF, "The chairperson reported that the minutes of the previous meeting have");
	pdf_continue_text($minutesPDF, "been signed as a true record.");
	pdf_set_text_pos($minutesPDF, "170", "620");
	pdf_continue_text($minutesPDF, "The below number of Applications and Application moneys being received for");
	pdf_continue_text($minutesPDF, "class memberships were approved and registered in the members register");
	pdf_continue_text($minutesPDF, "dated: " . date('l dS \of F Y', strtotime($currentDate)));
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "Class G: " . number_format($total1));
	pdf_continue_text($minutesPDF, "Class H: " . number_format($total2));
	pdf_continue_text($minutesPDF, "Class I: " . number_format($total3));
	pdf_continue_text($minutesPDF, "Class J: " . number_format($total6));
	pdf_continue_text($minutesPDF, "Class K: " . number_format($total7));
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "There being no other business the meeting was closed");
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "Signed as a true Record");
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "");
	pdf_continue_text($minutesPDF, "_______________________________");
	pdf_continue_text($minutesPDF, "ChairPerson");
	
	pdf_end_page($minutesPDF);
	
	if($total1) { displayMembers("G"); }
	if($total2) { displayMembers("H"); }
	if($total3) { displayMembers("I"); }
	if($total6) { displayMembers("J"); }
	if($total7) { displayMembers("K"); }
	
	
	pdf_close($minutesPDF);
	$Buffer = PDF_get_buffer($minutesPDF);
	pdf_delete($minutesPDF);

	//send_to_browser($Buffer,"application/pdf","myServicesMinutes.pdf","attachment");
	
	function displayMembers($classLetter) {
		
		global $minutesPDF, $font, $fontBold, $dd, $currentDate;
		
		switch($classLetter) {
		
			case "G":
				$serviceID = "1";
				break;
			case "H":
				$serviceID = "2";
				break;
			case "I":
				$serviceID = "3";
				break;
			case "J":
				$serviceID = "6";
				break;
			case "K":
				$serviceID = "7";
				break;				
		
		}
		
	    pdf_begin_page($minutesPDF, 595, 842);
	    
		pdf_setfont($minutesPDF, $font, 10);

		pdf_set_text_pos($minutesPDF, get_left_pos("Register of Class " . $classLetter . " Members", $minutesPDF, "297.5"), 800);
		pdf_continue_text($minutesPDF, "Register of Class " . $classLetter . " Members");
		
		pdf_setfont($minutesPDF, $font, 8);
		
		pdf_set_text_pos($minutesPDF, get_left_pos(date('l dS \of F Y', strtotime($currentDate)), $minutesPDF, "297.5"), 790);
		pdf_continue_text($minutesPDF, date('l dS \of F Y', strtotime($currentDate)));
		
		pdf_setfont($minutesPDF, $font, 8);
		
		pdf_set_text_pos($minutesPDF, 25, 770);
		pdf_continue_text($minutesPDF, "Reg ID");
		pdf_set_text_pos($minutesPDF, 70, 770);
		pdf_continue_text($minutesPDF, "Member Name / Address");
		pdf_set_text_pos($minutesPDF, "283", 770);
		pdf_continue_text($minutesPDF, "Contact Name");
		pdf_set_text_pos($minutesPDF, "453", 770);
		pdf_continue_text($minutesPDF, "Start Date");
		pdf_set_text_pos($minutesPDF, "525", 770);
		pdf_continue_text($minutesPDF, "Expiry Date");
		
		$pos = 760;
		
		$regSQL = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '". $serviceID ."' $dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
		//$regSQL = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services, reg_acc_details where (registered_accounts.Acc_No = reg_acc_details.Acc_No) and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and services.FieldID = '". $serviceID ."' and Status_ID = '1'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",ebanc_services);
		while($regObj = mysql_fetch_object($regSQL)) {
		
			$pos = $pos - 20;
		
			pdf_set_text_pos($minutesPDF, 25, $pos);
			pdf_continue_text($minutesPDF, $regObj->FieldID2);
			pdf_set_text_pos($minutesPDF, 70, $pos);
			pdf_continue_text($minutesPDF, $regObj->companyname);
			pdf_continue_text($minutesPDF, $regObj->streetno . " " . $regObj->streetname . " " . $regObj->city . " " . $regObj->state . " " . $regObj->postcode);
			pdf_set_text_pos($minutesPDF, "283", $pos);
			pdf_continue_text($minutesPDF, $regObj->accholder);
			pdf_set_text_pos($minutesPDF, "453", $pos);
			pdf_continue_text($minutesPDF, servicedates($regObj->FieldID2,1));
			pdf_set_text_pos($minutesPDF, "525", $pos);
			pdf_continue_text($minutesPDF, servicedates($regObj->FieldID2,2));
		
		}
		
		//3 top lines in boxes
		pdf_setlinewidth($minutesPDF, 1.5);
		pdf_moveto($minutesPDF, 25, 755);
		pdf_lineto($minutesPDF, 585, 755);
		pdf_stroke($minutesPDF);
	
		pdf_end_page($minutesPDF);
		
	}
	
?>