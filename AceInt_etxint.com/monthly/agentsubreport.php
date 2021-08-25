<?

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $date3 = date("Y-m", mktime(0,0,0,date("m"),1-1,date("Y")));
 $query = dbRead("select * from area, country where area.CID = country.countryID and ((area.CID = 1 and `drop` = 'Y')) order by FieldID");

 #loop around
 while($row = mysql_fetch_assoc($query)) {

   $email = $row[reportemail];

   if(!$email) {
    $email = 'dave@ebanctrade.com';
   }

   // define the text.
   //$text = "Attached is your Subsidy Report for the quarter ending September 07.  In future, all Subsidy reports will be sent by email and are no longer available from the intranet, so please ensure you save your report once you receive it.  If you don't make an immediate claim, it is your responsibility to retain the report for future claim.<br><br>You have up to 12 months to claim your subsidy amount for each quarter.  You will not be able to claim a quarterly subsidy that is older than 12 months.<br><br>If you have downloaded your September quarter report, please discard it as it will not be accurate.  Ensure your claim is based on the attached report.<br><br>Regards<br>Empire Trade Accounts";
   $text = "Attached is your Subsidy Report for the quarter previous quarter.<br><br>Regards<br>Empire Trade Accounts";
   $text = get_html_template($row['countryID'],$row[tradeq],$text);
   $subject = "Subsidy Report - ".$row[place];

   // get the actual taxinvoice ready.
   $buffer = taxinvoice($row[FieldID]);

     unset($attachArray);
     unset($addressArray);
     unset($bccArray);

   	$attachArray[] = array($buffer, 'subsidy_'.$row[place].'_'.$date3.'.pdf', 'base64', 'application/pdf');

	if(strstr($email, ";")) {
		$emailArray = explode(";", $email);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row[tradeq]);
		}
	} else {
		$addressArray[] = array(trim($email), $row[tradeq]);
	}

	$bccArray[] = array("reports@ebanctrade.com", $row[tradeq]);

	sendEmail("accounts@au.empireXchange.com", 'Empire Accounts', "accounts@au.empireXchange.com", $subject, "accounts@au.empireXchange.com", 'Empire Accounts', $text, $addressArray, $attachArray, $bccArray);

}

function taxinvoice($run_fieldid) {

 global $linkid, $db, $date3, $row, $pdf;

 //Create & Open PDF-Object this is before the loop
 $pdf = pdf_new();
 //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
 pdf_open_file($pdf, '');
 pdf_set_info($pdf, "Author","RDI Host");
 pdf_set_info($pdf, "Title","Invoice 1");
 pdf_set_info($pdf, "Creator", "Antony Puckey");
 pdf_set_info($pdf, "Subject", "Hosting Invoice");
 pdf_set_value($pdf, compress, 9);
 pdf_begin_page($pdf, 595, 842);
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
 pdf_set_parameter($pdf, "textformat", "utf8");

 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
 $font = pdf_findfont($pdf, "Verdana", "winansi", 0);

 layout();

 $foo = 0;
 $total = 0;

 $date1 = date("Y-m-d", mktime(0,0,0, date("m"),1-1,date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m")-3,1,date("Y")));
 $date4 = date("Ym", mktime(0,0,0,date("m"),1-1,date("Y")));

 //$query3  = dbRead("select * from members WHERE licensee = ".$run_fieldid." and members.CID=".$_SESSION['User']['CID']." and (status = 0 or status = 4) order by memid");
 //$query3  = dbRead("select members.*, count(transactions.memid) as taccount from members, transactions WHERE members.memid = transactions.memid and licensee = ".$run_fieldid." and (status = 0 or status = 4) and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."','".$_SESSION['Country']['adminacc']."') and dis_date >= '$date2' and dis_date <= '$date1' group by transactions.memid order by memid");
 $query3  = dbRead("select members.*, count(transactions.memid) as taccount from members, transactions WHERE members.memid = transactions.memid and licensee = ".$run_fieldid." and (status = 0 or status = 4) and to_memid NOT IN ('".$row['reserveacc']."','".$row['rereserve']."','".$row['facacc']."','".$row['refacacc']."','".$row['adminacc']."') and dis_date >= '$date2' and dis_date <= '$date1' group by transactions.memid order by memid");

 $offset3 = 0;
 $commtotal = 0;
 $pageno = 1;
 $commision = 165;

 $foo = 0;
 $total = 0;
 $count = 0;

 #loop around
 while($row3 = mysql_fetch_assoc($query3)) {

  //3 top lines in boxes
  pdf_setlinewidth($pdf, 1.5);
  pdf_moveto($pdf, 35, 570);
  pdf_lineto($pdf, 560, 570);
  pdf_stroke($pdf);

  //$offset3+=20;

  if($offset3 > 650) {

   $offset3+=40;

   pdf_set_text_pos($pdf, get_right_pos("Page $pageno", $pdf, "570", 8, $font), 775-$offset3);
   pdf_continue_text($pdf, "Page $pageno");

   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $offset3=0;
   $pageno++;

   layout();

   //$offset3+=20;

  }

  if($row3['taccount'] > 0) {

		$offset3+=20;

		$cfgbgcolorone = "#CCCCCC";
		$cfgbgcolortwo = "#EEEEEE";
		$bgcolor = $cfgbgcolorone;
		$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

		pdf_setfont($pdf, $font, 8);

		pdf_set_text_pos($pdf, 25, 765-$offset3);
		pdf_continue_text($pdf, $row3[memid]);

		$name=substr($row3[companyname], 0, 35);
		pdf_set_text_pos($pdf, 90, 765-$offset3);
		pdf_continue_text($pdf, $name);

		pdf_set_text_pos($pdf, get_right_pos($row3[datejoined], $pdf, "293", 8, $font), 765-$offset3);
		pdf_continue_text($pdf, $row3[datejoined]);

		pdf_set_text_pos($pdf, get_right_pos($row3[taccount], $pdf, "395", 8, $font), 765-$offset3);
		pdf_continue_text($pdf, $row3[taccount]);

		pdf_set_text_pos($pdf, get_right_pos(number_format($commision,2), $pdf, "565", 8, $font), 765-$offset3);
		pdf_continue_text($pdf, number_format($commision,2));


		//3 top lines in boxes
		pdf_setlinewidth($pdf, 1.5);
		pdf_moveto($pdf, 25, 750-$offset3);
		pdf_lineto($pdf, 565, 750-$offset3);
		pdf_stroke($pdf);

		$total += $commision;
		$count++ ;
		$foo++;

        dbWrite("insert into tbl_subsidy (acc_no,licensee,month,no_trans) values ('".$row3[memid]."','".$row3[licensee]."','".$date4."','".$row3[taccount]."')");

  }
 }

 $offset3+=20;
 if($row['CID'] == 12) {
  $commtotal=number_format(round($total),2);
 } else {
  $commtotal=number_format($total,2);
 }

 pdf_setfont($pdf, $font, 8);

 pdf_set_text_pos($pdf, get_right_pos("TOTALS:", $pdf, "485", 8, $font), 765-$offset3);
 pdf_continue_text($pdf, "TOTALS:");
 pdf_set_text_pos($pdf, get_right_pos($commtotal, $pdf, "565", 8, $font), 765-$offset3);
 pdf_continue_text($pdf, $commtotal);

 pdf_set_text_pos($pdf, get_right_pos("Page $pageno", $pdf, "570", 8, $font), 775-700);
 pdf_continue_text($pdf, "Page $pageno");

 $offset3 = 0;
 $commtotal = 0;

 //close it up
 pdf_end_page($pdf);
 pdf_close($pdf);
 $buffer = pdf_get_buffer($pdf);

 pdf_delete($pdf);
 return $buffer;

}

function layout() {

 global $pdf, $font, $row, $date3, $font;

 pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
 $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
 pdf_setfont($pdf, $font, 16);

 pdf_set_text_pos($pdf, get_left_pos("Agent Subsidy Report for $row[place] upto $date3", $pdf, "297.5", 16, $font), 800);
 pdf_continue_text($pdf, "Agent Subsidy Report for $row[place] upto $date3");

 pdf_setfont($pdf, $font, 8);

 pdf_set_text_pos($pdf, 25, 770-$offset3);
 pdf_continue_text($pdf, "Memid");
 pdf_set_text_pos($pdf, 90, 770-$offset3);
 pdf_continue_text($pdf, "Member Name");
 pdf_set_text_pos($pdf, get_right_pos("Mem ID", $pdf, "293", 8, $font), 770-$offset3);
 pdf_continue_text($pdf, "Date Joined");
 pdf_set_text_pos($pdf, get_right_pos("No of Transactions", $pdf, "395", 8, $font), 770-$offset3);
 pdf_continue_text($pdf, "No of Transactions");
 pdf_set_text_pos($pdf, get_right_pos("Subsidy", $pdf, "565", 8, $font), 770-$offset3);
 pdf_continue_text($pdf, "Subsidy");

 //3 top lines in boxes
 pdf_setlinewidth($pdf, 1.5);
 pdf_moveto($pdf, 25, 755-$offset3);
 pdf_lineto($pdf, 565, 755-$offset3);
 pdf_stroke($pdf);

}
