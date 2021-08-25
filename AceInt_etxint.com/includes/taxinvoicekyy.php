<?


function taxinvoice($query, $stationery = false, $individual = false, $send = false) {

 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
 pdf_open_file($pdf);
 pdf_set_info($pdf, "Author","E Banc Trade");
 pdf_set_info($pdf, "Title","Tax Invoice");
 pdf_set_info($pdf, "Creator", "E Banc Accounts");
 pdf_set_info($pdf, "Subject", "Tax Invoice");
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
 pdf_set_parameter($pdf, "textformat", "utf8");

 $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
 $fontbold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);
 //$font = pdf_findfont($pdf, "Verdana", "host", 0);

 #loop around

 while($row = mysql_fetch_assoc($query)) {

   if($row['CID'] == 151){

     $inv = dbRead("SELECT * FROM invoice_es WHERE inv_link = '".$row['FieldID']."'");
     $invRow = mysql_fetch_assoc($inv);

     if($invRow['inv_no']) {
	 	$inv_no = $invRow['inv_no'];
	 } else {
	 	$inv_no = $row['FieldID'];
	 }

     if(!$_SESSION['Country']['logo'])  {
       $logo = $row['logo'];
     } else {
       $logo = $_SESSION['Country']['logo'];
     }

     if($logo == "ept")  {
       $web = "www.eplanettrade.com";
       $say = "Let the Businesses and Trade Unite";
     } elseif($logo == "etx") {
       $web = "www.empireXchange.com";
       $say = "Trading alternatives for business for lifestyle for you";
     } else {
       $web = "www.ebanctrade.com";
       $say = "Trading alternatives for business for lifestyle for you";
     }

     if($row['CID'] == 12) {
       $pos2 = 265;
     } else {
       $pos2 = 0;
     }

     pdf_begin_page($pdf, 595, 842);

     if($stationery || ($individual && $send))  {

	    $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$logo."-bw.jpg");


		pdf_place_image($pdf, $pdfimage, 445, 755, 1);

		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
		pdf_setfont($pdf, $font, 18);
		//pdf_set_text_pos($pdf, get_right_pos($row[tname], $pdf, "530"), 800);
		//pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "70"), 800);
		pdf_set_text_pos($pdf, 70, 800);
		pdf_continue_text($pdf, $row[tname]);

		pdf_setfont($pdf, $font, 12);

		pdf_set_text_pos($pdf, get_right_pos("$row[acno]:", $pdf, "435"), 722);
		pdf_continue_text($pdf, "$row[acno]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[tdate]:", $pdf, "435"), 700);
		pdf_continue_text($pdf, "$row[tdate]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[tno]:", $pdf, "435"), 677);
		pdf_continue_text($pdf, "$row[tno]:");

		if($row['CID'] == 12) {
		  $pos2 = 265;
		} else {
		  $pos2 = 0;
		}

		//address box
		pdf_rect($pdf, 65+$pos2, 650, 180, 81);
		pdf_closepath_stroke($pdf);

		//top and bottom thin lines
		pdf_moveto($pdf, 30, 630-$offset);
		pdf_lineto($pdf, 565, 630-$offset);
		pdf_stroke($pdf);

		pdf_moveto($pdf, 30, 210-$offset);
		pdf_lineto($pdf, 565, 210-$offset);
		pdf_stroke($pdf);

		//lines and boxes
		pdf_rect($pdf, 65, 486, 463, 128);
		pdf_closepath_stroke($pdf);

		pdf_rect($pdf, 280, 486, 248, 27);
		pdf_closepath_stroke($pdf);

		pdf_rect($pdf, 280, 461, 248, 25);
		pdf_closepath_stroke($pdf);

		pdf_rect($pdf, 280, 436, 248, 25);
		pdf_closepath_stroke($pdf);

		pdf_moveto($pdf, 100, 300-$offset);
		pdf_lineto($pdf, 505, 300-$offset);
		pdf_stroke($pdf);

		pdf_set_text_pos($pdf, get_right_pos("$row[tsub]:", $pdf, "410"), 507);
		pdf_continue_text($pdf, "$row[tsub]:");

		if($row[tax] != 0) {
		  pdf_set_text_pos($pdf, get_right_pos("$row[tgst]:", $pdf, "410"), 482);
		  pdf_continue_text($pdf, "$row[tgst]:");
		}

		pdf_set_text_pos($pdf, get_right_pos("$row[ttot]:", $pdf, "410"), 457);
		pdf_continue_text($pdf, "$row[ttot]:");

		pdf_setfont($pdf, $font, 10);
		pdf_set_text_pos($pdf, get_right_pos("$row[tout]:", $pdf, "410"), 428);
		pdf_continue_text($pdf, "$row[tout]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[tpaid]:", $pdf, "410"), 398);
		pdf_continue_text($pdf, "$row[tpaid]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[tnow]:", $pdf, "410"), 368);
		pdf_continue_text($pdf, "$row[tnow]:");

		$pos = 295;
		$Newtrequ = explode("|", wordwrap($row[trequ], 92, "|"));
		foreach($Newtrequ as $Line) {
		  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
		  pdf_continue_text($pdf, $Line);
		  $pos = $pos - 10;
		}

		$pos = $pos - 10;

		pdf_setfont($pdf, $font, 9);

		$Newtnom = explode("|", wordwrap($row[tnom], 92, "|"));
		foreach($Newtnom as $Line) {
		  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
		  pdf_continue_text($pdf, $Line);
		  $pos = $pos - 9;
		}

		$pos = 205;

		$Newtnom = explode("|", wordwrap($row[auth], 92, "|"));
		foreach($Newtnom as $Line) {
		  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
		  pdf_continue_text($pdf, $Line);
		  $pos = $pos - 9;
		}

		$pos = $pos - 10;

		pdf_setcolor($pdf, "fill", "rgb", 1, 0.502, 0);
		pdf_setfont($pdf, $font, 10);

		$Newitt = explode("|", wordwrap($row[itt], 67, "|"));
		foreach($Newitt as $Line) {
		  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
		  pdf_continue_text($pdf, $Line);
		  $pos = $pos - 12;
		}

		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
		pdf_setfont($pdf, $font, 9);
		pdf_set_text_pos($pdf, get_left_pos($row['abn'], $pdf, "297.5"), 150);
		pdf_continue_text($pdf, $row['abn']);

		pdf_set_text_pos($pdf, 40, 133);

		$text = explode(",",$row['address2'],2);
		foreach($text as $Line) {
		  pdf_continue_text($pdf, trim($Line));
		  $textheight += 9;
		}

		pdf_set_text_pos($pdf, get_left_pos("Tel: ".$row['phone'], $pdf, "297.5"), 133);
		pdf_continue_text($pdf, "Tel: ".$row['phone']);
		pdf_set_text_pos($pdf, get_left_pos("Fax: ".$row['fax'], $pdf, "297.5"), 124);
		pdf_continue_text($pdf, "Fax: ".$row['fax']);
		pdf_set_text_pos($pdf, get_right_pos("Email: ".$row['email'], $pdf, "553"), 133);
		pdf_continue_text($pdf, "Email: ".$row['email']);
		pdf_set_text_pos($pdf, get_right_pos("http://".$web."", $pdf, "553"), 124);
		pdf_continue_text($pdf, "http://".$web."");

		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
		pdf_moveto($pdf, 30, 105-$offset);
		pdf_lineto($pdf, 565, 105-$offset);
		pdf_stroke($pdf);

		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
		pdf_moveto($pdf, 30, 105-$offset);
		pdf_lineto($pdf, 565, 105-$offset);
		pdf_stroke($pdf);

		pdf_setfont($pdf, $font, 12);
		pdf_set_text_pos($pdf, get_left_pos($row[trem], $pdf, "297.5"), 100);
		pdf_continue_text($pdf, $row[trem]);

		pdf_setfont($pdf, $font, 10);
		pdf_set_text_pos($pdf, get_right_pos("$row[comna]:", $pdf, "145"), 78);
		pdf_continue_text($pdf, "$row[comna]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[acno]:", $pdf, "145"), 38);
		pdf_continue_text($pdf, "$row[acno]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[tnow]:", $pdf, "470"), 78);
		pdf_continue_text($pdf, "$row[tnow]:");
		pdf_set_text_pos($pdf, get_right_pos("$row[tampa]:", $pdf, "470"), 38);
		pdf_continue_text($pdf, "$row[tampa]:");

 	 } else {

		pdf_setfont($pdf, $font, 9);
		pdf_set_text_pos($pdf, get_left_pos("        XXXXXXXXXXXX", $pdf, "297.5"), 163);
		pdf_continue_text($pdf, "        XXXXXXXXXXXX");

		pdf_setfont($pdf, $font, 9);
		pdf_set_text_pos($pdf, get_left_pos("13 110 102 648", $pdf, "395"), 163);
		pdf_continue_text($pdf, "13 110 102 648");

 	 }


     if($row[postalsuburb]) {
  		$suburb="$row[postalsuburb]\n";
 	 } else {
 		unset($suburb);
 	 }

	 if($row[postalno]) {
	   $streetno="$row[postalno] ";
	 } else {
	   unset($streetno);
	 }

	 if($row['CID'] == 1) {
	 	$cur = 1;
	 }

 	 $last="$row[postalcity]  $row[postalstate]  $row[postalpostcode]";
 	 $city=strtoupper($last);

 	 $addressbox="$row[contactname]\n$row[companyname]\n$streetno$row[postalname]\n$suburb$city";

 	 $newdate=explode("-", $row[date]);
 	 $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0]));

 	 $accountdetails="$row[memid]\n\r$newdate2\n\r$row[FieldID]";

 	 $gst=number_format((($row[currentfees]/(100+$row[tax]))*$row[tax]),2);
 	 $nett=number_format($row[currentfees]-$gst,2);

 	 $currentpaid = number_format(($row[currentpaid]*(-1)),2);

 	 //$total=number_format(($row[currentfees]+$row[overduefees])+$row[currentpaid],2);
 	 $total=number_format(($row[currentfees]+$row[overduefees]+$row[currentpaid]),2);

 	 $row[overduefees]=number_format($row[overduefees],2);

 	 //$fees="$row[overduefees]\n\n\n$row[currentpaid]\n\n\n$total";
 	 $fees="$row[overduefees]\n\n\n$currentpaid\n\n\n$total";

 	 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
 	 pdf_setfont($pdf, $fontbold, 14);
 	 pdf_set_text_pos($pdf, 70, 775);
 	 if($row['CID'] == 12) {
 	   pdf_continue_text($pdf, $row['regname']);
 	 } else {
 	   pdf_continue_text($pdf, $row['companyname']);
	 }

 	 if($row[CID] == 10) {
   	   pdf_setfont($pdf, $font, 10);
   	   pdf_continue_text($pdf, $row[abn2]);
 	 }

	 if($row['CID'] == 12) {
	   $pos2 = 265;
	 } else {
	   $pos2 = 0;
	 }

	 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	 pdf_setfont($pdf, $font, 10);
	 pdf_set_text_pos($pdf, 75+$pos2, 726);

	 if($hhh)  {
	   if($row['accholder'] != $row['companyname']) {
	     pdf_continue_text($pdf, $row['accholder']);
	   }

	   pdf_continue_text($pdf, $row['companyname']);
	   pdf_continue_text($pdf, "".$streetno."".$row['postalname']);
	   if($suburb)  {
	     pdf_continue_text($pdf, $suburb);
	   }
	   $add = "".$row['postalcity']."  ".$row['postalstate']."  ".$row['postalpostcode']."";
	   pdf_continue_text($pdf, $add);
	 }

     $blah = addresslayout($row['countryID']);

	 foreach($blah as $key => $value) {
	   $addline = "";

	   foreach($value as $key2) {
	     if($row[$key2]) {
	      $addline .= $row[$key2] ." ";
	     }
	   }

	   if(trim($addline))  {

	     $NewCompanyname = explode("|", wordwrap($addline, 40, "|"));
	     foreach($NewCompanyname as $Line) {
	       pdf_continue_text($pdf, $Line);
	     }
	   }
	 }

	 pdf_setfont($pdf, $font, 12);
	 pdf_set_text_pos($pdf, 455, 722);
	 pdf_continue_text($pdf, $row['memid']);
	 pdf_continue_text($pdf, " ");
	 pdf_continue_text($pdf, $newdate2);
	 pdf_continue_text($pdf, " ");
	 pdf_continue_text($pdf, $inv_no);

	 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	 pdf_setfont($pdf, $font, 10);

	 $pos = 581;

	 $Newdet = explode("|", wordwrap($row[tdet], 70, "|"));
	 foreach($Newdet as $Line) {
	   pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "225"), $pos);
	   pdf_continue_text($pdf, $Line);
	   $pos = $pos - 10;
	 }

     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
 	 pdf_setfont($pdf, $font, 7);

 	 $pos = $pos - 6;

 	 $Newtcom = explode("|", wordwrap("($row[tcom])", 70, "|"));
	 foreach($Newtcom as $Line) {
	   pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "225"), $pos);
	   pdf_continue_text($pdf, $Line);
	   $pos = $pos - 7;
	 }

	 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	 pdf_setfont($pdf, $font, 10);

	 if($row[tax] != 0) {
	   pdf_set_text_pos($pdf, get_right_pos($gst, $pdf, "520"), 480);
	   pdf_continue_text($pdf, $gst);
	 }

	 pdf_set_text_pos($pdf, get_right_pos($nett, $pdf, "520"), 581);
	 pdf_continue_text($pdf, $nett);
	 pdf_set_text_pos($pdf, get_right_pos($nett, $pdf, "520"), 505);
	 pdf_continue_text($pdf, $nett);
	 pdf_set_text_pos($pdf, get_right_pos($row[currentfees], $pdf, "520"), 458);
	 pdf_continue_text($pdf, $row[currentfees]);

	 pdf_set_text_pos($pdf, get_right_pos($row[overduefees], $pdf, "520"), 425);
	 pdf_continue_text($pdf, $row[overduefees]);
	 pdf_set_text_pos($pdf, get_right_pos($currentpaid, $pdf, "520"), 401.5);
	 pdf_continue_text($pdf, $currentpaid);
	 pdf_set_text_pos($pdf, get_right_pos($total, $pdf, "520"), 368);
	 pdf_continue_text($pdf, $total);


	 if($row['CID'] == 1) {
	   pdf_rect($pdf, 65, 339, 200, 130);
	   pdf_closepath_stroke($pdf);

	   pdf_moveto($pdf, 65, 452-$offset);
	   pdf_lineto($pdf, 265, 452-$offset);
	   pdf_stroke($pdf);

	   pdf_setfont($pdf, $font, 10);
	   pdf_set_text_pos($pdf, get_left_pos("PAYMENT METHODS", $pdf, "165"), 465);
	   pdf_continue_text($pdf, "PAYMENT METHODS");

	   pdf_setfont($pdf, $font, 8);
	   pdf_set_text_pos($pdf, 70, 447);
	   pdf_continue_text($pdf, "Credit Card");

	   pdf_set_text_pos($pdf, 70, 410);
	   pdf_continue_text($pdf, "Cheque");

	   pdf_set_text_pos($pdf, 70, 395);
	   pdf_continue_text($pdf, "Cash");

	   pdf_set_text_pos($pdf, 70, 375);
	   pdf_continue_text($pdf, "Direct Deposit");

	   pdf_set_text_pos($pdf, 130, 447);
	   pdf_continue_text($pdf, "- Call 1800 675 092");
	   pdf_continue_text($pdf, "  (businsess hours only)");
	   pdf_continue_text($pdf, "- online at www.ebanctrade.com");

	   pdf_setfont($pdf, $font, 7);
	   pdf_continue_text($pdf, "  (log into secure member section)");

	   pdf_setfont($pdf, $font, 8);
	   pdf_set_text_pos($pdf, 130, 410);
	   pdf_continue_text($pdf, "- mail to address below");

	   pdf_set_text_pos($pdf, 130, 395);
	   pdf_continue_text($pdf, "- in person");
	   pdf_continue_text($pdf, "  (Sunshine Coast office only)");

	   pdf_set_text_pos($pdf, 130, 375);
	   pdf_continue_text($pdf, "- Name: E Banc Trade");
	   pdf_continue_text($pdf, "  BSB: 084 571");
	   pdf_continue_text($pdf, "  Acc No: 584 652 378");
	   pdf_continue_text($pdf, "  Ref: ".$row[memid]);
	 }

	 pdf_setfont($pdf, $font, 12);
	 pdf_set_text_pos($pdf, 160, 78);
	 //pdf_continue_text($pdf, $row[companyname]);
	 pdf_continue_text($pdf, $row[regname]);
	 pdf_set_text_pos($pdf, 160, 38);
	 pdf_continue_text($pdf, $row[memid]);
	 pdf_set_text_pos($pdf, 480, 78);
	 pdf_continue_text($pdf, $total);

	 pdf_set_text_pos($pdf, get_left_pos($row[tlate], $pdf, "302.5"), 227);
	 pdf_continue_text($pdf, $row[tlate]);

	 pdf_end_page($pdf);


   } else {
   //do this if not 15;


	 if(!$_SESSION['Country']['logo'])  {
	   $logo = $row['logo'];
	 }  else  {
	   $logo = $_SESSION['Country']['logo'];
	 }

	 if($logo == "ept")  {
	   $web = "www.eplanettrade.com";
	   $say = "Let the Businesses and Trade Unite";
	 } elseif($logo == "etx")  {
	   $web = "www.empireXchange.com";
	   $say = "Let the Businesses and Trade Unite";
	 } else {
	   $web = "www.ebanctrade.com";
	   $say = "Trading alternatives for business for lifestyle for you";
	 }

     pdf_begin_page($pdf, 595, 842);

     if($stationery || ($individual && $send))  {


		if($row['logo'] == 'etx') {
	    	$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/public_html/home/images/".$logo."2-bw.jpg");
	   		pdf_place_image($pdf, $pdfimage, 455, 740, .25);
		} else {
	    	$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$logo."-bw.jpg");
	   		pdf_place_image($pdf, $pdfimage, 445, 780, 1);
		}

	   //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$logo."-bw.jpg");
	   //pdf_place_image($pdf, $pdfimage, 445, 780, .25);

	   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
	   pdf_setfont($pdf, $font, 18);
	   pdf_set_text_pos($pdf, 65, 815);

	   if($row['CID'] == 15) {
	    pdf_set_text_pos($pdf, 65, 730);
	    if($row['currentfees'] < 0) {
	      pdf_continue_text($pdf, "Credit Note");
	    } else {
	      pdf_continue_text($pdf, $row[tname]);
		}
	   } else {
	    pdf_set_text_pos($pdf, 65, 815);
	    pdf_continue_text($pdf, $row[tname]);
	   }

	   pdf_setfont($pdf, $font, 12);

	   if($row['CID'] == 12 || $row['CID'] == 15) {
	     $pos2 = 265;
	     if($row['CID'] == 15) {
	     	   pdf_set_text_pos($pdf, 50, 800);
			   pdf_continue_text($pdf, "$row[company]");
			   pdf_continue_text($pdf, "$row[abn]");
			   $text = explode(",",$row['address1'],2);
			   foreach($text as $Line) {
			     pdf_continue_text($pdf, trim($Line));
			     $textheight += 9;
			   }
		 	$pos3 = 75;
		 }
	   } else {
	     $pos2 = 0;
	   }

	   pdf_set_text_pos($pdf, get_right_pos("$row[acno]:", $pdf, "170"), 773-$pos3);
	   pdf_continue_text($pdf, "$row[acno]:");
	   pdf_set_text_pos($pdf, get_right_pos("$row[tdate]:", $pdf, "170"), 750-$pos3);
	   pdf_continue_text($pdf, "$row[tdate]:");
	   pdf_set_text_pos($pdf, get_right_pos("$row[tno]:", $pdf, "170"), 727-$pos3);
	   pdf_continue_text($pdf, "$row[tno]:");


	   //address box
	   pdf_rect($pdf, 65+$pos2, 630, 200, 76);
	   pdf_closepath_stroke($pdf);

	   //top and bottom thin lines
	   pdf_moveto($pdf, 30, 625-$offset);
	   pdf_lineto($pdf, 565, 625-$offset);
	   pdf_stroke($pdf);

	   pdf_moveto($pdf, 30, 210-$offset);
	   pdf_lineto($pdf, 565, 210-$offset);
	   pdf_stroke($pdf);

	   //lines and boxes
	   pdf_rect($pdf, 65, 486, 463, 128);
	   pdf_closepath_stroke($pdf);

	   pdf_rect($pdf, 280, 486, 248, 27);
	   pdf_closepath_stroke($pdf);

	   pdf_rect($pdf, 280, 461, 248, 25);
	   pdf_closepath_stroke($pdf);

	   pdf_rect($pdf, 280, 436, 248, 25);
	   pdf_closepath_stroke($pdf);

	   pdf_moveto($pdf, 65, 300-$offset);
	   pdf_lineto($pdf, 530, 300-$offset);
	   pdf_stroke($pdf);

	   pdf_set_text_pos($pdf, get_right_pos("$row[tsub]:", $pdf, "410"), 507);
	   pdf_continue_text($pdf, "$row[tsub]:");

	   if($row[tax] != 0) {
	     pdf_set_text_pos($pdf, get_right_pos("$row[tgst]:", $pdf, "410"), 482);
	     pdf_continue_text($pdf, "$row[tgst]:");
	   }

	   pdf_set_text_pos($pdf, get_right_pos("$row[ttot]:", $pdf, "410"), 457);
	   pdf_continue_text($pdf, "$row[ttot]:");

	   //if(!$_REQUEST['invoice'] || $row['inv_type'] == 1) {
	   if($row['CID'] != 15) {
		   pdf_setfont($pdf, $font, 10);
		   pdf_set_text_pos($pdf, get_right_pos("$row[tout]:", $pdf, "410"), 428);
		   pdf_continue_text($pdf, "$row[tout]:");
		   pdf_set_text_pos($pdf, get_right_pos("$row[tpaid]:", $pdf, "410"), 398);
		   pdf_continue_text($pdf, "$row[tpaid]:");
		   pdf_set_text_pos($pdf, get_right_pos("$row[tnow]:", $pdf, "410"), 368);
		   pdf_continue_text($pdf, "$row[tnow]:");
	   }

	   $pos = 295;
	   $Newtrequ = explode("|", wordwrap($row[trequ], 92, "|"));
	   foreach($Newtrequ as $Line) {
	     pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
	     pdf_continue_text($pdf, $Line);
	     $pos = $pos - 10;
	   }

	   $pos = $pos - 10;

	   pdf_setfont($pdf, $font, 9);

	   $Newtnom = explode("|", wordwrap($row[tnom], 92, "|"));
	   foreach($Newtnom as $Line) {
	     pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
	     pdf_continue_text($pdf, $Line);
	     $pos = $pos - 9;
	   }

	   $pos = 205;

	   $Newtnom = explode("|", wordwrap($row[auth], 92, "|"));
	   foreach($Newtnom as $Line) {
	     pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
	     pdf_continue_text($pdf, $Line);
	     $pos = $pos - 9;
	   }

	   $pos = $pos - 10;

	   pdf_setcolor($pdf, "fill", "rgb", 1, 0.502, 0);
	   pdf_setfont($pdf, $font, 10);

	   $Newitt = explode("|", wordwrap($row[itt], 67, "|"));
	   foreach($Newitt as $Line) {
	     pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "302.5"), $pos);
	     pdf_continue_text($pdf, $Line);
	     $pos = $pos - 12;
	   }

	   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	   pdf_setfont($pdf, $font, 9);
	   if($row['CID'] != 15) {
		   pdf_set_text_pos($pdf, get_left_pos($row['abn'], $pdf, "297.5"), 150);
		   pdf_continue_text($pdf, $row['abn']);
	   }
	   pdf_set_text_pos($pdf, 40, 133);

	   $text = explode(",",$row['address2'],2);
	   foreach($text as $Line) {
	     pdf_continue_text($pdf, trim($Line));
	     $textheight += 9;
	   }

	   pdf_set_text_pos($pdf, get_left_pos("Tel: ".$row['phone'], $pdf, "297.5"), 133);
	   pdf_continue_text($pdf, "Tel: ".$row['phone']);
	   pdf_set_text_pos($pdf, get_left_pos("Fax: ".$row['fax'], $pdf, "297.5"), 124);
	   pdf_continue_text($pdf, "Fax: ".$row['fax']);
	   pdf_set_text_pos($pdf, get_right_pos("Email: ".$row['email'], $pdf, "553"), 133);
	   pdf_continue_text($pdf, "Email: ".$row['email']);
	   pdf_set_text_pos($pdf, get_right_pos("http://".$web."", $pdf, "553"), 124);
	   pdf_continue_text($pdf, "http://".$web."");

	   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
	   pdf_moveto($pdf, 30, 105-$offset);
	   pdf_lineto($pdf, 565, 105-$offset);
	   pdf_stroke($pdf);

	   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
	   pdf_moveto($pdf, 30, 105-$offset);
	   pdf_lineto($pdf, 565, 105-$offset);
	   pdf_stroke($pdf);

	   if(!$_REQUEST['invoice'] || $row['inv_type'] == 1) {
		   pdf_setfont($pdf, $font, 14);
	       pdf_set_text_pos($pdf, 40, 105);
		   //pdf_set_text_pos($pdf, get_left_pos($row[trem], $pdf, "60"), 105);
		   pdf_continue_text($pdf, $row[trem]);


		   pdf_setfont($pdf, $font, 10);
		   pdf_set_text_pos($pdf, get_right_pos("$row[comna]:", $pdf, "355"), 90);
		   pdf_continue_text($pdf, "$row[comna]:");
		   pdf_set_text_pos($pdf, get_right_pos("$row[acno]:", $pdf, "355"), 70);
		   pdf_continue_text($pdf, "$row[acno]:");
		   pdf_set_text_pos($pdf, get_right_pos("$row[tnow]:", $pdf, "355"), 50);
		   pdf_continue_text($pdf, "$row[tnow]:");
		   pdf_set_text_pos($pdf, get_right_pos("$row[tampa]:", $pdf, "355"), 30);
		   pdf_continue_text($pdf, "$row[tampa]:");
	   }
 	 }

	 if($row[postalsuburb]) {
	   $suburb="$row[postalsuburb]\n";
	 } else {
	   unset($suburb);
	 }

	 if($row[postalno]) {
	   $streetno="$row[postalno] ";
	 } else {
	   unset($streetno);
	 }

	 $last="$row[postalcity]  $row[postalstate]  $row[postalpostcode]";
	 $city=strtoupper($last);

	 $addressbox="$row[contactname]\n$row[companyname]\n$streetno$row[postalname]\n$suburb$city";

	 $newdate=explode("-", $row[date]);
	 $newdate2=date("d/m/Y", mktime(0,0,0,$newdate[1],$newdate[2],$newdate[0]));

	 $accountdetails="$row[memid]\n\r$newdate2\n\r$row[FieldID]";

	 if($row['CID'] == 15 || $_REQUEST['invoice']) {
	 	$row[currentfees] = $row[inv_amount];
	 }

	 if($_REQUEST['invoice']) {
	 	if($row['taxes']) {
	 		$gst=number_format((($row[currentfees]/(100+$row[tax]))*$row[tax]),2);
	 		//$gst = decimal_format((($row[currentfees]/(100+$row[tax]))*$row[tax]), 1, '', '', $row['CID']);
		} else {
	 		$gst="0.00";
		}
	 } else {
	 	$gst=number_format((($row[currentfees]/(100+$row[tax]))*$row[tax]),2);
	 	//$gst = decimal_format((($row[currentfees]/(100+$row[tax]))*$row[tax]), 1, '', '', $row['CID']);
	 }

	 if($row['CID'] == 1) {
	 	$cur = 15;
	 }

	 //$nett=number_format($row[currentfees]-$gst,2);
 	 $nett = decimal_format($row[currentfees]-$gst, 1, '', '', $row['CID']);


	 //$currentpaid = number_format(($row[currentpaid]*(-1)),2);
	 $currentpaid = decimal_format(($row[currentpaid]*(-1)), 1, '', '', $row['CID']);

	 //$total=number_format((+$row[overduefees])+$row[currentpaid],2);
	 //$total=number_format(($row[currentfees]+$row[overduefees]+$row[currentpaid]),2);
	 $total = decimal_format(($row[currentfees]+$row[overduefees]+$row[currentpaid]), 1, '', '', $row['CID']);

	 //$row[overduefees]=number_format($row[overduefees],2);
	 $row[overduefees] = decimal_format($row[overduefees], 1, '', '', $row['CID']);

	 //$fees="$row[overduefees]\n\n\n$row[currentpaid]\n\n\n$total";
	 $fees="$row[overduefees]\n\n\n$currentpaid\n\n\n$total";

	 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	 pdf_setfont($pdf, $fontbold, 14);
	 pdf_set_text_pos($pdf, 60, 790);
	 if($row['CID'] != 15) {
		 if($row['CID'] == 12) {
	 	   pdf_continue_text($pdf, $row['regname']);
	 	 } else {
	 	   pdf_continue_text($pdf, $row['companyname']);
		 }
	 }

	 if($row[CID] == 10) {
	   pdf_setfont($pdf, $font, 10);
	   pdf_continue_text($pdf, $row[abn2]);
	 }

	 if($row['CID'] == 12 || $row['CID'] == 15) {
	   $pos2 = 265;
	 } else {
	   $pos2 = 0;
	 }

	 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	 pdf_setfont($pdf, $font, 10);
	 pdf_set_text_pos($pdf, 75+$pos2, 698);

	 if($row[CID] == 15) {
	    pdf_continue_text($pdf, $row[abn2]);
	 }

     $blah = addresslayout($row['countryID']);

	 foreach($blah as $key => $value) {
	    $addline = "";

	    foreach($value as $key2) {
	      if($row[$key2]) {
	        $addline .= $row[$key2] ." ";
	      }
	    }

	    if(trim($addline))  {

	      $NewCompanyname = explode("|", wordwrap($addline, 40, "|"));
	      foreach($NewCompanyname as $Line) {
	        pdf_continue_text($pdf, $Line);
	      }
	    }
	  }

	  if($row[CID] == 15) {
	      $inv = dbRead("SELECT * FROM invoice_es WHERE inv_link = '".$row['FieldID']."'");
	      $invRow = mysql_fetch_assoc($inv);
	  }

      if($invRow['inv_no']) {
	 	$inv_no = $invRow['inv_no'];
	  } else {
	 	$inv_no = $row['FieldID'];
	  }

     if($row['CID'] == 15) {
	 	$pos3 = 75;
	 }

	  pdf_setfont($pdf, $font, 12);
	  pdf_set_text_pos($pdf, 175, 773-$pos3);
	  pdf_continue_text($pdf, $row['memid']);
	  //pdf_continue_text($pdf, " ");
	  pdf_set_text_pos($pdf, 175, 750-$pos3);
	  pdf_continue_text($pdf, $newdate2);
	  //pdf_continue_text($pdf, " ");
	  pdf_set_text_pos($pdf, 175, 727-$pos3);
	  //pdf_continue_text($pdf, $row['FieldID']);
	  pdf_continue_text($pdf, $inv_no);

	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	  pdf_setfont($pdf, $font, 10);

	  $pos = 581;

	  if(!$_REQUEST['invoice']) {
		  $Newdet = explode("|", wordwrap($row[tdet], 70, "|"));
		  foreach($Newdet as $Line) {
		    pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "225"), $pos);
		    pdf_continue_text($pdf, $Line);
		    $pos = $pos - 10;
		  }
	  } else {
		  //$Newdet = explode("|", wordwrap($_REQUEST['desc'], 70, "|"));
		  $Newdet = explode("|", wordwrap($row['det'], 70, "|"));
		  foreach($Newdet as $Line) {
		    pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "225"), $pos);
		    pdf_continue_text($pdf, $Line);
		    $pos = $pos - 10;
		  }
	  }

	  if(!$_REQUEST['invoice']) {
		  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
		  pdf_setfont($pdf, $font, 7);

		  $pos = $pos - 6;

		  $Newtcom = explode("|", wordwrap("($row[tcom])", 70, "|"));
		  foreach($Newtcom as $Line) {
		    pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "225"), $pos);
		    pdf_continue_text($pdf, $Line);
		    $pos = $pos - 7;
		  }
	  }

	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	  pdf_setfont($pdf, $font, 10);

	  if($row[tax] != 0) {
		pdf_set_text_pos($pdf, get_right_pos(decimal_format($gst, 1, '', '', $row['CID']), $pdf, "520"), 480);
		pdf_continue_text($pdf, decimal_format($gst, 1, '', '', $row['CID']));
	  }

	  pdf_set_text_pos($pdf, get_right_pos($nett, $pdf, "520"), 581);
	  pdf_continue_text($pdf, $nett);
	  pdf_set_text_pos($pdf, get_right_pos($nett, $pdf, "520"), 505);
	  pdf_continue_text($pdf, $nett);
	  pdf_set_text_pos($pdf, get_right_pos(decimal_format($row[currentfees], 1, '', '', $row['CID']), $pdf, "520"), 455);
	  pdf_continue_text($pdf, decimal_format($row[currentfees], 1, '', '', $row['CID']));

	  //if(!$_REQUEST['invoice'] || $row['inv_type'] == 1) {
	  if($row['CID'] != 15) {
		  pdf_set_text_pos($pdf, get_right_pos($row[overduefees], $pdf, "520"), 425);
		  pdf_continue_text($pdf, $row[overduefees]);
		  pdf_set_text_pos($pdf, get_right_pos($currentpaid, $pdf, "520"), 396.5);
		  pdf_continue_text($pdf, $currentpaid);
		  pdf_set_text_pos($pdf, get_right_pos($total, $pdf, "520"), 368);
		  pdf_continue_text($pdf, $total);
	  }

	  if($row['paymenttype'] && $row['accountno']) {
	   pdf_set_text_pos($pdf, get_right_pos("Direct Debit in place - NO PAYMENT NEEDED", $pdf, "520"), 345);
	   pdf_continue_text($pdf, "Direct Debit in place - NO PAYMENT NEEDED");
	  }

	  if($row['CID'] == 2) {

	  	pdf_rect($pdf, 65, 339, 200, 130);
		pdf_closepath_stroke($pdf);

		pdf_moveto($pdf, 65, 452-$offset);
		pdf_lineto($pdf, 265, 452-$offset);
		pdf_stroke($pdf);

		pdf_setfont($pdf, $font, 10);
		pdf_set_text_pos($pdf, get_left_pos("PAYMENT METHODS", $pdf, "165"), 465);
		pdf_continue_text($pdf, "PAYMENT METHODS");

		pdf_setfont($pdf, $font, 8);
		//pdf_set_text_pos($pdf, 70, 447);
		//pdf_continue_text($pdf, "Credit Card");

		pdf_set_text_pos($pdf, 70, 447);
		pdf_continue_text($pdf, "Cheque");

		pdf_set_text_pos($pdf, 70, 432);
		pdf_continue_text($pdf, "Cash");

		pdf_set_text_pos($pdf, 70, 412);
		pdf_continue_text($pdf, "Direct Deposit");

		//pdf_set_text_pos($pdf, 130, 447);
		//pdf_continue_text($pdf, "- Call 1800 675 092");
		//pdf_continue_text($pdf, "  (businsess hours only)");
		//pdf_continue_text($pdf, "- online at www.ebanctrade.com");

		//pdf_setfont($pdf, $font, 7);
		//pdf_continue_text($pdf, "  (log into secure member section)");

		pdf_setfont($pdf, $font, 8);
		pdf_set_text_pos($pdf, 130, 447);
		pdf_continue_text($pdf, "- mail to address below");

		pdf_set_text_pos($pdf, 130, 432);
		pdf_continue_text($pdf, "- in person");
		pdf_continue_text($pdf, "  (Auckland office only)");

		pdf_set_text_pos($pdf, 130, 412);
		pdf_continue_text($pdf, "- Name: Empire Trade");
		//pdf_continue_text($pdf, "  Bank: ASB");
		//pdf_continue_text($pdf, "  Branch: 123216");
		//pdf_continue_text($pdf, "  Acc No: 010485700");
		pdf_continue_text($pdf, "  Ref: ".$row[memid]);

	  }

	  if($row['CID'] == 15) {

	  	pdf_rect($pdf, 65, 339, 200, 60);
		pdf_closepath_stroke($pdf);

		pdf_setfont($pdf, $font, 9);
		pdf_set_text_pos($pdf, 65, 412);
		pdf_continue_text($pdf, "Datos Bancarios para realizar la transferencia:");

		pdf_set_text_pos($pdf, 70, 390);
		pdf_continue_text($pdf, "  Banco Bilbao Vizcaya Argentaria");
		pdf_continue_text($pdf, " ");
		pdf_continue_text($pdf, "  Cuenta no: 0182 4813 66 0201533938");
		pdf_continue_text($pdf, "  Ref: ".$row[memid]);

		pdf_setfont($pdf, $font, 6);
	    pdf_set_text_pos($pdf, get_left_pos("Inscrito Registro Mercantil Tomo 2964 folio 95 Hoja A-94034 El 23.09.2005", $pdf, "302.5"), 220);
	    pdf_continue_text($pdf, "Inscrito Registro Mercantil Tomo 2964 folio 95 Hoja A-94034 El 23.09.2005");

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
		pdf_setfont($pdf, $font, 8);
		pdf_set_text_pos($pdf, 93, 100);
		//pdf_continue_text($pdf, "Adjunte este recibo en caso de realizar el pago mediante cheque y envielo por correo a nuestras oficinas");
		if($row['inv_type'] == 1) {
		 pdf_continue_text($pdf, "Por favor recorte y adjunte este recibo en caso de realizar el pago mediante cheque y envielo por correo a nuestras oficinas");
		}
	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
	  }

	  if($row['CID'] == 1 && $stationery) {

		//pdf_rect($pdf, 65, 339, 200, 130);
		pdf_rect($pdf, 65, 309, 200, 160);
		pdf_closepath_stroke($pdf);

		pdf_moveto($pdf, 65, 452-$offset);
		pdf_lineto($pdf, 265, 452-$offset);
		pdf_stroke($pdf);

		pdf_setfont($pdf, $font, 10);
		pdf_set_text_pos($pdf, get_left_pos("PAYMENT METHODS", $pdf, "165"), 465);
		pdf_continue_text($pdf, "PAYMENT METHODS");

		pdf_setfont($pdf, $font, 8);
		//pdf_set_text_pos($pdf, 70, 447);
		//pdf_continue_text($pdf, "Credit Card");

		//pdf_set_text_pos($pdf, 70, 447);
		pdf_set_text_pos($pdf, 70, 427);
		pdf_continue_text($pdf, "Cheque");

		//pdf_set_text_pos($pdf, 70, 432);
		pdf_set_text_pos($pdf, 70, 402);
		pdf_continue_text($pdf, "Cash");

		//pdf_set_text_pos($pdf, 70, 412);
		//pdf_continue_text($pdf, "Direct Deposit");

		//pdf_set_text_pos($pdf, 130, 447);
		//pdf_continue_text($pdf, "- Call 1800 675 092");
		//pdf_continue_text($pdf, "  (businsess hours only)");
		//pdf_continue_text($pdf, "- online at www.ebanctrade.com");

		//pdf_setfont($pdf, $font, 7);
		//pdf_continue_text($pdf, "  (log into secure member section)");

		pdf_setfont($pdf, $font, 8);
		pdf_set_text_pos($pdf, 130, 427);
		pdf_continue_text($pdf, "- mail to address below");

		pdf_set_text_pos($pdf, 130, 402);
		pdf_continue_text($pdf, "- in person");

		//pdf_set_text_pos($pdf, 130, 412);
		//pdf_continue_text($pdf, "- Name: Empire Trade");
		//pdf_continue_text($pdf, "  Bank: Suncorp");
		//pdf_continue_text($pdf, "  BSB: 484799");
		//pdf_continue_text($pdf, "  Acc No: 027318880");
		//pdf_continue_text($pdf, "  Ref: ".$row[memid]);

	  }

 	  if($row['CID'] == 1) {
		if(!$stationery) {

		    $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/public_html/home/images/BPAY.jpg");
			pdf_place_image($pdf, $pdfimage, 300, 188, .3);

		  	pdf_rect($pdf, 340, 194, 126, 39);
			pdf_closepath_stroke($pdf);

			pdf_setfont($pdf, $fontbold, 10);
			pdf_set_text_pos($pdf, 348, 228);
			pdf_continue_text($pdf, "Biller Code: 374215");

			$ref = "Ref: ".bpay_code($row[memid]);
			pdf_set_text_pos($pdf, 348, 213);
			pdf_continue_text($pdf, $ref);

			pdf_setfont($pdf, $fontbold, 8);
			pdf_set_text_pos($pdf, 120, 201);
			//pdf_continue_text($pdf, "XXXXXXX  484 799 Suncorp");
			pdf_continue_text($pdf, "XXXXXXX  ");

			pdf_set_text_pos($pdf, 138, 193);
			//pdf_continue_text($pdf, "XXXXXXXXXX   027318880");
			pdf_continue_text($pdf, "XXXXXXXXXX  ");

			pdf_set_text_pos($pdf, 485, 692);
			pdf_continue_text($pdf, "        XXXXXXXXXXXXX");
			pdf_continue_text($pdf, "   ABN 92 123 948 489");

		} else {

		    $pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/public_html/home/images/BPAY.jpg");
			//pdf_place_image($pdf, $pdfimage, 300, 303, .3);
			pdf_place_image($pdf, $pdfimage, 80, 313, .3);

		  	//pdf_rect($pdf, 340, 309, 126, 39);
		  	pdf_rect($pdf, 120, 319, 126, 39);
			pdf_closepath_stroke($pdf);

			pdf_setfont($pdf, $fontbold, 10);
			//pdf_set_text_pos($pdf, 348, 343);
			pdf_set_text_pos($pdf, 128, 353);
			pdf_continue_text($pdf, "Biller Code: 374215");

			$ref = "Ref: ".bpay_code($row[memid]);
			//pdf_set_text_pos($pdf, 348, 328);
			pdf_set_text_pos($pdf, 128, 338);
			pdf_continue_text($pdf, $ref);
		}
 	  }

	  if(!$_REQUEST['invoice'] || $row['inv_type'] == 1) {
		  pdf_setfont($pdf, $font, 12);
		  pdf_set_text_pos($pdf, 360, 90);

		  if($row['CID'] == 12) {
		    pdf_continue_text($pdf, $row['regname']);
		  } else {
		    pdf_continue_text($pdf, $row['companyname']);
		  }
		  pdf_set_text_pos($pdf, 360, 70);
		  pdf_continue_text($pdf, $row[memid]);
		  pdf_set_text_pos($pdf, 360, 50);
		  if($row['CID'] == 15) {
		   pdf_continue_text($pdf, $row[currentfees]);
		  } else {
		   pdf_continue_text($pdf, $total);
		  }
	  }

      pdf_end_page($pdf);

   }
  }

  //close it up
  pdf_close($pdf);
  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);


  if($send)  {
    return $buffer;
  } else {
    send_to_browser($buffer,"application/pdf","TaxInvoice.pdf","attachment");
  }

}
