<?

 /**
  * Cheque Print Class
  *
  * class.chequeprint.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  * : Requires Database functions, dbRead(), dbWrite() and their associated functions.
  */

 class ChequePrint {
 
  /*
   
   Class Description.
   
    Memid:				Membership Number of the Cheque book to be printed.
    CountryID:			Country where the cheque book is printing from.
    ChequeType:			Type of cheque to be printed.
    					1: Individual cheques.
    					2: New Members cheques.
    					3: Daily Cheque print that gets emailed.
   
   Function Reference.
   
    SetType()			See Above for Types. CountryID is needed and memid is optional but is required for
    					type 1.
    GenerateCheques()	generate the actual pdf for the cheques.
    CountrySpecific()	get country specific options for different located text.
    Complete()			Finish the pdf and send the appropriate file. either returned or sent to browser.
    
  */

  function ChequePrint($Arg1) {
  
    $this->Memid = "";
    $this->CountryID = "";
    $this->ChequeType = "";
    $this->PDF = pdf_new();
    $this->ChequeNo = 0;
    $this->TotalPages = "";
    $this->TotalMembers = "";
    $this->NumberPages = $Arg1;
    $this->Query = "";
    $this->RChequeNoOffset = "";
    $this->DisplayRightChequeNo = true;
    $this->DisplayLeftChequeNo = true;
    $this->AdditionalCheckNo = false;
    $this->ChequePositions = Array();
    
    pdf_set_parameter($this->PDF, "license", "L500102-010000-105258-97D9C0");
    pdf_open_file($this->PDF);
    pdf_set_info($this->PDF, "Author", "RDI Host Pty Ltd / INPrime Pty Ltd");
    pdf_set_info($this->PDF, "Title", "Cheque Class");
    pdf_set_info($this->PDF, "Creator", "Antony Puckey");
    pdf_set_info($this->PDF, "Subject", "Cheque Class");
    pdf_begin_page($this->PDF, 595, 842);
    pdf_set_parameter($this->PDF, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
    pdf_set_parameter($this->PDF, "textformat", "utf8");
    
    $this->PDFFont = pdf_findfont($this->PDF, "Verdana", "winansi", 0);
    $this->PDFImage = pdf_open_image_file($this->PDF, "jpeg", "/home/etxint/public_html/home/images/ebanc.jpg");
  
  }
  
  function CountrySpecific($CountryID) {
  
  	switch($CountryID) {
  		
  		case "8":
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'733'
  					),
  				'Name2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'510'
  					),
  				'Name3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'288'
  					),
  				'Name4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'64'
  					),
  				'Account1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'705'
  					),
  				'Account2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'483'
  					),
  				'Account3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'261'
  					),
  				'Account4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'38'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'705'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'485'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'265'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'45'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'763'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'542'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'321'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'100'
  					),
  			);
		    $this->RChequeNoOffset = "";
   			$this->DisplayRightChequeNo = false;
   			$this->DisplayLeftChequeNo = false;
		    $this->AdditionalCheckNo = false;
  			break;
  		
  		case "12":
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'710'
  					),
  				'Name2' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'501'
  					),
  				'Name3' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'293'
  					),
  				'Name4' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'85'
  					),
  				'Account1' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'684'
  					),
  				'Account2' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'476'
  					),
  				'Account3' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'267'
  					),
  				'Account4' => array(
  					'Left'		=>	'276',
  					'Bottom'	=>	'61'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'705'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'485'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'265'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'45'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'560',
  					'Bottom'	=>	'747'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'560',
  					'Bottom'	=>	'544'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'560',
  					'Bottom'	=>	'335'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'560',
  					'Bottom'	=>	'128'
  					),
  			);
		    $this->RChequeNoOffset = "75";
   			$this->DisplayRightChequeNo = true;
   			$this->DisplayLeftChequeNo = false;
		    $this->AdditionalCheckNo = true;
  			break;

  		case "3":
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'733'
  					),
  				'Name2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'510'
  					),
  				'Name3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'288'
  					),
  				'Name4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'64'
  					),
  				'Account1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'705'
  					),
  				'Account2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'483'
  					),
  				'Account3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'261'
  					),
  				'Account4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'38'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'705'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'485'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'265'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'45'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'763'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'542'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'321'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'100'
  					),
  			);
		    $this->RChequeNoOffset = "";
   			$this->DisplayRightChequeNo = true;
   			$this->DisplayLeftChequeNo = true;
		    $this->AdditionalCheckNo = false;
  			break;
  			
  		case "10":
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'733'
  					),
  				'Name2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'510'
  					),
  				'Name3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'288'
  					),
  				'Name4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'64'
  					),
  				'Account1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'705'
  					),
  				'Account2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'483'
  					),
  				'Account3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'261'
  					),
  				'Account4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'38'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'705'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'485'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'265'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'45'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'763'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'542'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'321'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'580',
  					'Bottom'	=>	'100'
  					),
  			);
		    $this->RChequeNoOffset = "";
   			$this->DisplayRightChequeNo = true;
   			$this->DisplayLeftChequeNo = true;
		    $this->AdditionalCheckNo = false;
  			break;
  			  		
  		case "16":
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'708'
  					),
  				'Name2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'505'
  					),
  				'Name3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'288'
  					),
  				'Name4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'75'
  					),
  				'Account1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'680'
  					),
  				'Account2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'473'
  					),
  				'Account3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'261'
  					),
  				'Account4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'53'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'680'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'480'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'265'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'55'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'575',
  					'Bottom'	=>	'738'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'575',
  					'Bottom'	=>	'537'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'575',
  					'Bottom'	=>	'321'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'575',
  					'Bottom'	=>	'110'
  					),
  			);
		    $this->RChequeNoOffset = "";
   			$this->DisplayRightChequeNo = true;
   			$this->DisplayLeftChequeNo = true;
		    $this->AdditionalCheckNo = false;
  			break;
  			
  		case "2":
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'731'
  					),
  				'Name2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'507'
  					),
  				'Name3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'280'
  					),
  				'Name4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'55'
  					),
  				'Account1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'703'
  					),
  				'Account2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'480'
  					),
  				'Account3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'253'
  					),
  				'Account4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'29'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'703'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'482'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'261'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'40'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'761'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'539'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'317'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'95'
  					),
  			);
		    $this->RChequeNoOffset = "";
   			$this->DisplayRightChequeNo = true;
   			$this->DisplayLeftChequeNo = true;
		    $this->AdditionalCheckNo = false;
  			break;
  			
  		default:
  			$this->ChequePositions = array(
  				'Name1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'733'
  					),
  				'Name2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'510'
  					),
  				'Name3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'288'
  					),
  				'Name4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'64'
  					),
  				'Account1' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'705'
  					),
  				'Account2' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'483'
  					),
  				'Account3' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'261'
  					),
  				'Account4' => array(
  					'Left'		=>	'306',
  					'Bottom'	=>	'38'
  					),
  				'LCheque1' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'705'
  					),
  				'LCheque2' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'485'
  					),
  				'LCheque3' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'265'
  					),
  				'LCheque4' => array(
  					'Left'		=>	'80',
  					'Bottom'	=>	'45'
  					),
  				'RCheque1' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'763'
  					),
  				'RCheque2' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'542'
  					),
  				'RCheque3' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'321'
  					),
  				'RCheque4' => array(
  					'Left'		=>	'590',
  					'Bottom'	=>	'100'
  					),
  			);
		    $this->RChequeNoOffset = "";
   			$this->DisplayRightChequeNo = true;
   			$this->DisplayLeftChequeNo = true;
		    $this->AdditionalCheckNo = false;
  			break;
  	}
  
  }
  
  function SetType($Type, $CountryID, $Memid = false) {
  	
  	$this->CountryID = $CountryID;
  	
    switch($Type) {
    
    	case "1":
    		
    		$this->Memid = $Memid;
    		$this->Query = dbRead("select members.* from members, status where (members.status = status.FieldID) and members.memid = ".$this->Memid." and status.mem_cheque = 1");
			break;
			
		case "2":
		
			$this->Query = dbRead("select members.* from members, status where (members.status = status.FieldID) and (members.datepacksent is NULL or members.datepacksent = '0000-00-00') and status.mem_cheque = 1 and members.CID = '".$this->CountryID."' order by members.memid");
			break;
			
		case "3":
		
			$this->Query = dbRead("select members.* from memcards, members where (memcards.memid=members.memid) and memcards.type='2' and memcards.done='N' and members.CID='".$this->CountryID."' order by memcards.memid");
			break;
			
    }
  
  }

  function GenerateCheques() {
    
    $this->CountrySpecific($this->CountryID);
    
  	$this->TotalPages = mysql_num_rows($this->Query)*$this->NumberPages;

	$PageCounter = 1;
	$this->ChequeNo = 0;

	while($Row = mysql_fetch_assoc($this->Query)) {

		$Counter = 0;
		$ChequeCounter = 0;
    	$this->ChequeNo = $Row['cheque_no'];

		while($Counter < $this->NumberPages) {
 
			#this need to be inside the while loop so the details change
			
			if($Row['displayname'] == "regname") {
			
				$topbox = substr($Row[regname], 0, 55);
			
			} else {

				$topbox = substr($Row[companyname], 0, 55);
			
			}
			$bottombox = "$Row[memid]";

			pdf_setfont($this->PDF, $this->PDFFont, 10);
 
			pdf_set_text_pos($this->PDF, get_left_pos($topbox, $this->PDF, $this->ChequePositions['Name1']['Left']), $this->ChequePositions['Name1']['Bottom']);
			pdf_continue_text($this->PDF, $topbox);
			pdf_set_text_pos($this->PDF, get_left_pos($topbox, $this->PDF, $this->ChequePositions['Name2']['Left']), $this->ChequePositions['Name2']['Bottom']);
			pdf_continue_text($this->PDF, $topbox);
			pdf_set_text_pos($this->PDF, get_left_pos($topbox, $this->PDF, $this->ChequePositions['Name3']['Left']), $this->ChequePositions['Name3']['Bottom']);
			pdf_continue_text($this->PDF, $topbox);
			pdf_set_text_pos($this->PDF, get_left_pos($topbox, $this->PDF, $this->ChequePositions['Name4']['Left']), $this->ChequePositions['Name4']['Bottom']);
			pdf_continue_text($this->PDF, $topbox);  
			
			$this->ChequeNo = $Row['cheque_no']+$ChequeCounter;
			$this->ChequeNo = $this->ChequeNo+1;
			
			if($this->DisplayLeftChequeNo) {
			
				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['LCheque1']['Left']), $this->ChequePositions['LCheque1']['Bottom']);
				pdf_continue_text($this->PDF, $no); 
				
			}
			
			if($this->DisplayRightChequeNo) {
			
				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['RCheque1']['Left']), $this->ChequePositions['RCheque1']['Bottom']+$this->RChequeNoOffset);
				pdf_continue_text($this->PDF, $no);
			
			}
			
			$ExtraNo = ($this->AdditionalCheckNo) ? " - " . str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT) : "";
			
			pdf_setfont($this->PDF, $this->PDFFont, 12);
			pdf_set_text_pos($this->PDF, get_left_pos($bottombox . $ExtraNo, $this->PDF, $this->ChequePositions['Account1']['Left']), $this->ChequePositions['Account1']['Bottom']);
			pdf_continue_text($this->PDF, $bottombox . $ExtraNo);
 			
			$this->ChequeNo = $this->ChequeNo+$this->NumberPages;
			
			if($this->DisplayLeftChequeNo) {
 			
				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['LCheque2']['Left']), $this->ChequePositions['LCheque2']['Bottom']);
				pdf_continue_text($this->PDF, $no);
				
			}
			
			if($this->DisplayRightChequeNo) {
			
				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['RCheque2']['Left']), $this->ChequePositions['RCheque2']['Bottom']+$this->RChequeNoOffset);
				pdf_continue_text($this->PDF, $no);
				
			}
			
			$ExtraNo = ($this->AdditionalCheckNo) ? " - " . str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT) : "";
			
			pdf_setfont($this->PDF, $this->PDFFont, 12);			
			pdf_set_text_pos($this->PDF, get_left_pos($bottombox . $ExtraNo, $this->PDF, $this->ChequePositions['Account2']['Left']), $this->ChequePositions['Account2']['Bottom']);
			pdf_continue_text($this->PDF, $bottombox . $ExtraNo);

			$this->ChequeNo = $this->ChequeNo+$this->NumberPages;
			
			if($this->DisplayLeftChequeNo) {

				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['LCheque3']['Left']), $this->ChequePositions['LCheque3']['Bottom']);
				pdf_continue_text($this->PDF, $no); 
				
			}
			
			if($this->DisplayRightChequeNo) {
			
				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['RCheque3']['Left']), $this->ChequePositions['RCheque3']['Bottom']+$this->RChequeNoOffset);
				pdf_continue_text($this->PDF, $no);
			
			}
			
			$ExtraNo = ($this->AdditionalCheckNo) ? " - " . str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT) : "";
			
			pdf_setfont($this->PDF, $this->PDFFont, 12);
			pdf_set_text_pos($this->PDF, get_left_pos($bottombox . $ExtraNo, $this->PDF, $this->ChequePositions['Account3']['Left']), $this->ChequePositions['Account3']['Bottom']);
			pdf_continue_text($this->PDF, $bottombox . $ExtraNo);

			$this->ChequeNo = $this->ChequeNo+$this->NumberPages;
			
			if($this->DisplayLeftChequeNo) {

				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['LCheque4']['Left']), $this->ChequePositions['LCheque4']['Bottom']);
				pdf_continue_text($this->PDF, $no);
				
			}
			
			if($this->DisplayRightChequeNo) {
			
				pdf_setfont($this->PDF, $this->PDFFont, 8);
				$no = str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT);
				pdf_set_text_pos($this->PDF, get_right_pos($no, $this->PDF, $this->ChequePositions['RCheque4']['Left']), $this->ChequePositions['RCheque4']['Bottom']+$this->RChequeNoOffset);
				pdf_continue_text($this->PDF, $no);
			
			}
			
			$ExtraNo = ($this->AdditionalCheckNo) ? " - " . str_pad($this->ChequeNo, 6, "0", STR_PAD_LEFT) : "";
			
			pdf_setfont($this->PDF, $this->PDFFont, 12);
			pdf_set_text_pos($this->PDF, get_left_pos($bottombox . $ExtraNo, $this->PDF, $this->ChequePositions['Account4']['Left']), $this->ChequePositions['Account4']['Bottom']);
			pdf_continue_text($this->PDF, $bottombox . $ExtraNo);  
	
			$Counter++;
			$ChequeCounter++;

			if($PageCounter != $this->TotalPages) {

				pdf_end_page($this->PDF);
				pdf_begin_page($this->PDF, 595, 842); 

			} 
			
			$PageCounter++;

		}
		
		if($this->DisplayRightChequeNo) {
			dbWrite("update members set cheque_no = ".$this->ChequeNo." where memid = " . $Row['memid']); 
		}
		
	}
  
  }

  function GetNumber($Name, $Type) {
  
  	return $this->ChequePositions[$Name][$Type];
  
  }

  function Complete($Type) {
  
	pdf_end_page($this->PDF);
	pdf_close($this->PDF);
	$Buffer = PDF_get_buffer($this->PDF);
	pdf_delete($this->PDF);
	
	switch($Type) {
	
		case "1":
			send_to_browser($Buffer,"application/pdf","PrintCheque.pdf","attachment");
	  		break;
	  		
		case "2":
			return $Buffer;
	  		break;
	  		
	}
	  	
  }

 }

?>