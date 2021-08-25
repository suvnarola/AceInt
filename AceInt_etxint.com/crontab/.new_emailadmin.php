<?

 /**
  * Tax Invoice Run.
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");

 ini_set('max_execution_time','1500');

 $invoice_date=date("Y-m", mktime(1,1,1,date("n")-1,1,date("Y")));
 $display_date=date("F, Y", mktime(1,1,1,date("n")-1,1,date("Y")));

 include("class.html.mime.mail.inc");
 include("/home/etxint/admin.etxint.com/includes/taxinvoiceky.php");
 include("/home/etxint/admin.etxint.com/monthly/statementky.php");

 /**
  * Go into a loop to start the process off.
  */

 $Cquery = dbRead("select country.*, countrydata.* from country, countrydata where (country.countryID = countrydata.CID) and Display = 'Yes' and countryID = 1 order by countryID");
 while($Crow = mysql_fetch_array($Cquery)) {

 //do emails for the individual countries first.
  //$query2 = dbRead("select members.memid as memid, members.companyname as companyname, members.emailaddress as emailaddress, members.contactname as contactname, invoice.* from invoice, members, status where invoice.memid=members.memid and (members.status = status.FieldID) and members.monthlyfeecash = '0' and invoice.date like '$invoice_date-%' and (status.mem_send_inv = 1) and members.emailaddress != '' and members.CID = '".$Crow['countryID']."' order by companyname");
  $query2 = dbRead("select members.memid as memid, members.companyname as companyname, tbl_members_email.email as emailaddress, members.contactname as contactname, invoice.* from invoice, members, status, tbl_members_email where invoice.memid=members.memid and (members.status = status.FieldID) and (members.memid = tbl_members_email.acc_no) and members.monthlyfeecash = '0' and members.prepaid = 1 and invoice.date like '$invoice_date-%' and (status.mem_send_inv = 1) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.CID = '".$Crow['countryID']."' order by companyname");
  while($row2 = mysql_fetch_array($query2)) {

	unset($attachArray);
	unset($addressArray);
    unset($bccArray);

   // define the text.
   //$text = get_html_template($Crow['countryID'],$row2['contactname'],$Crow['emtax'],$Crow,$Crow);
   $text = get_html_template($Crow['countryID'],$row2['contactname'],$Crow['emtax']);

   $newAmount = ($row2['currentfees'] + $row2['overduefees']) + $row2['currentpaid'];
   if($newAmount < 0) {
     $amo = "Amount Prepaid: $". abs(number_format($newAmount, 2));
   } else {
     $amo = "Amount Owing: $". number_format($newAmount, 2);
   }

   //$text = str_replace("{AMOUNT}", number_format($newAmount, 2), $text);
   $text = str_replace("{AMOUNT}", $amo, $text);

   define("CRLF", "\r\n", TRUE);
   $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));

   if($Crow['countryID'] == 4) {

    $newAmount = ($row2['currentfees'] + $row2['overduefees']) - $row2['currentpaid'];

	// ###AMOUNT### and ###MEMID### substitutes.
	$text = str_replace("{AMOUNT}", number_format($newAmount, 2), $text);
	$text = str_replace("{MEMID}", $row2['memid'], $text);

   }

   $mail->add_html($text);

   $Squery = dbRead("select * from invoice, members, area, countrydata, country where (invoice.memid=members.memid) and (members.licensee=area.FieldID) and (members.CID = country.countryID) and (members.CID = countrydata.CID) and members.memid='".$row2['memid']."' and invoice.date like '$invoice_date-%' order by companyname");
   $SBuffer = statement($Squery,true,'',true);
   $mail->add_attachment($SBuffer, "Statement.pdf", "application/pdf");
   $attachArray[] = array($SBuffer, "Statement.pdf", 'base64', 'application/pdf');

   if($row2['currentfees'] !=0 || $row2['overduefees'] != 0 || $row2['currentpaid'] !=0) {
    if($row2['CID'] != 15 || ($row2['CID'] == 15 && $row2['currentfees'] >= 20)) {
	 if($row2['CID'] == 15) {
      $query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn, invoice_es.* from countrydata, invoice, members, country, invoice_es where invoice.FieldID = invoice_es.inv_link and invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '$invoice_date-%' and members.memid = '".$row2['memid']."' order by companyname");
	 } else {
      $query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country where invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '$invoice_date-%' and members.memid = '".$row2['memid']."' order by companyname");
	 }

     $buffer = taxinvoice($query,true,'',true);
     $mail->add_attachment($buffer, "TaxInvoice.pdf", "application/pdf");
   	 $attachArray[] = array($buffer, "TaxInvoice.pdf", 'base64', 'application/pdf');
	}
   }

   $mail->build_message();

   //if($Crow['countryID'] == 3) {
   if($Crow['logo'] == 'ept') {

		if(strstr($row2['emailaddress'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
			   	$mail->send($row2['contactname'], trim($value), "E Banc Accounts", "accounts@".$Crow[countrycode].".ebanctrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>");
   			}
   		} else {
		   	$mail->send($row2['contactname'], $row2['emailaddress'], "E Planet Accounts", "accounts@".$Crow[countrycode].".eplanettrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".eplanettrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\nBCC: dave@ebanctrade.com");
   		}

   //} elseif($Crow['countryID'] == 1) {
   } elseif($Crow['logo'] == 'etx') {

		if(strstr($row2['emailaddress'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
		   	//$mail->send($row2['contactname'], trim($value), "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".empireXchange.com>\r\nErrors-To: TaxInvoiceEmailError <dave@empireXchange.com>\r\n");
				//$addressArray[] = array(trim($value), $row2['contactname']);
				//$addressArray[] = array('dave@au.etxint.com', $row2['contactname']);
				$addressArray[] = array('dave@au.etxint.com', $row2['contactname']);
   			}
   		} else {
		   	//$mail->send($row2['contactname'], $row2['emailaddress'], "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".empireXchange.com>\r\nErrors-To: TaxInvoiceEmailError <dave@empireXchange.com>\r\n");
			//$addressArray[] = array(trim($row2['emailaddress']), $row2['contactname']);
			$addressArray[] = array('dave@au.etxint.com', $row2['contactname']);
		//sendEmail("accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'], "accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", $text, $addressArray, $attachArray);
   		}

	    //$bccArray[] = array("dave.r@hq.etxint.com", $row[tradeq]);
		//sendEmail("accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", "Amended ".$Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'], "accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", $text, $addressArray, $attachArray, $bccArray);
    $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
    	dbWrite("insert into notes (memid,date,userid,type,note) values ('".$row2['memid']."','".$curdate."','180','1','Amended Tax Invoice/Statement Emailed')");

   } else {

		if(strstr($row2['emailaddress'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
		   		$mail->send($row2['contactname'], trim($value), "E Banc Accounts", "accounts@".$Crow[countrycode].".ebanctrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\n");
   			}
   		} else {
		   	$mail->send($row2['contactname'], $row2['emailaddress'], "E Banc Accounts", "accounts@".$Crow[countrycode].".ebanctrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\n");
   		}

   }
   usleep(500000);
  }

  $Mquery = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country, status where members.CID=country.countryID and (members.status = status.FieldID) and invoice.memid=members.memid and countrydata.CID=members.CID and invoice.date like '".$invoice_date."-%' and members.CID like '".$Crow['countryID']."' and members.monthlyfeecash != '0' and status.mem_send_inv = 1 and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) order by members.companyname");
  $Mrow = mysql_fetch_array($Mquery);
  if($Mrow['memid'])  {
    $Mquery2 = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country, status where members.CID=country.countryID and (members.status = status.FieldID) and invoice.memid=members.memid and countrydata.CID=members.CID and invoice.date like '".$invoice_date."-%' and members.CID like '".$Crow['countryID']."' and members.monthlyfeecash != '0' and status.mem_send_inv = 1 and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) order by members.companyname");
    $text = "Here are your Tax Invoices for the month of $display_date.\r\n\r\nPlease print and mail them out.\r\n\r\nYou may need Adobe Acrobat to read these Tax Invoices.\r\nTo get it go here http://www.adobe.com/\r\n\r\nE Banc Trade Accounts";
    $buffer = taxinvoice($Mquery2,true,'',true);
    define("CRLF", "\r\n", TRUE);
    $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));
    $mail->add_text($text);
    $mail->add_attachment($buffer, "TaxInvoice-Print.pdf", "application/pdf");
    $mail->build_message();
    $mail->send($Crow['name'], "accounts@".$Crow['countrycode']."ebanctrade.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - ".$Crow['name'], "Bcc: dave@ebanctrade.com");
  }
 }

?>
