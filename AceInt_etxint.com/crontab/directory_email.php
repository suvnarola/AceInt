<?

 /**
  * Tax Invoice Run.
  * Regional Area - 27
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");

 ini_set('max_execution_time','1500');
 include("class.html.mime.mail.inc");

 /**
  * Go into a loop to start the process off.
  */

 $Cquery = dbRead("select country.*, countrydata.* from country, countrydata where (country.countryID = countrydata.CID) and Display = 'Yes' and countryID = 1 order by countryID");
 while($Crow = mysql_fetch_array($Cquery)) {

   $query2 = dbRead("select members.*, tbl_members_email.*

	from members

		inner
			join
				tbl_members_email
				on members.memid = tbl_members_email.acc_no
		inner
			join
				tbl_area_physical
				on members.area = tbl_area_physical.FieldID
		inner
			join
				`status`
				on members.status = status.FieldID
	where

		tbl_members_email.type = 3 and tbl_members_email.email != '' and status.mem_lists = 1 and members.CID = ".$Crow['countryID']." and tbl_area_physical.RegionalID IN (27)");

  while($row2 = mysql_fetch_array($query2)) {

	unset($attachArray);
	unset($addressArray);
    unset($bccArray);

   // define the text.
   $tt = '<p>It is important for you
  to have up to date information on membership within the exchange - how
  else can you find the member who is offering what you want? Members have always
  been able to access the most up to date membership information online but the
  feedback we have been receiving indicates many of you do not have time to
  go online to search, and you need this information at your fingertips, or in
  your car. Quite often it is not the business owner doing the buying - rather
  it is your staff, or family members and often they do not have access to your
  online account.</p>
<p>To ensure you are always
  up to date with your fellow exchange members, we are emailing you a link to
  your local area directory. You can also nominate staff and family who assist
  you to spend your trade dollars to receive future Directory emails - just <a href="mailto:membersupport@au.empirexchange.com">send</a>
  us their email address. Updated directory links will be emailed every 3 months
  and it is simply a matter of clicking the <b>DOWNLOAD</b> icon and your directory
  will be saved to your computer. You can then print it out and take it with you,
  or access it from your computer until the next update arrives in your mailbox.
  Your local directory can be quite large - 80 to 150 pages, so you may not
  wish to print it in its entirety.</p>
<p></p>
<p align="center"><a href="http://www.ebanctrade.com/home/directory_download.php?disarea=27"target="_blank"><img src="http://media.ebanctrade.com/uploads/Image/download.jpg" width="150" height="67" border="0" alt="Download Directory" align="middle"></a></p>
<p align="left">
  While is it valuable to have your local area directory, remember we have many
  members who can trade nationally, either online or by using shipping options,
  so don"t forget the members outside your local area and use the online
  directory as well. </p>
<p>DON"T FORGET - the
  most up to date directory is still the one you can download from the <a href="https://secure.etxint.com/members/?CID=1&LID=en" target="_blank">member"s
  section</a> of the corporate website. This directory is updated immediately
  information is added or changed and you have many combination options for advanced
  searching.</p>
<p><b>Order your Hardcopy Directory
  from Head Office</b><br>
  You can still order printed directories to leave in your office or at home.
  Either reply to this email, phone head office or order online from the secure
  Member"s section. Your Directory will be mailed to you within three business
  days and your account will be charged as follows: </p>
<blockquote>
  <p>$2 : Directory of members
    in your local area <br>
    $5 : Directory of Queensland members<br>
    $5 : Combined Directory of Victoria, South Australia, Tasmania, Western Australia
    ACT and NSW members<br>
    $10 : Directory of all Australian members<br>
    <b><font size="1"> (Prices include postage)</font></b></p>
</blockquote>
<p>If ordering a hard copy
  directory please ensure your postal address, listed below, is current. <br>
  Street: '. $row2['postalno'] .' '. $row2['postalname'] .'  <br>
  Suburb: '. $row2['postalcity'] .'  <br>
  State: '. $row2['postalstate'] .'  <br>
  postcode: '. $row2['postalpostcode'] .'  </p>
<p><b>Who does your shopping?</b><br>
  Is there someone who does the purchasing in your business? To utilise trade
  to its fullest potential ensure your purchaser is aware of trade and has a current
  directory. Each cash business expense converted to a trade business expense
  is a cash saving.</p>
<p>Is there someone who does
  the purchasing in your home? Again make sure they are fully aware of trade and
  how to find what they are looking for.</p>
<p>Don&#146;t have time to
  source products and services! Empire Trade member support staff are available
  to take calls and source the businesses you require. Why not complete the attached
  <b>Business Expense Check List</b> and mail or fax it back to head office. Our
  sales consultants will try to source new members businesses who offer the services
  you require, and Member Support will send you information on existing member
  businesses you are interested in contacting.</p>
<p><b>Help us keep our Directory up to date</b>.<br>
  Keeping our directories up to date is a priority for us. Member support staff
  around the country are continually contacting members and updating details and
  you can assist us by informing our office if you come across any outdated listings.
  But remember to ask to speak to the contact person displayed in the directory
  when calling businesses. Sometimes staff members may be new; not aware their
  employer is an Empire Trader; or not fully aware of Empire trading practices.</p>
<p>For all <b>Member Support</b>
  queries, please contact the <b>NSW Regional South office</b> by phone: 02 6931
  8077, fax: 02 6931 8099, or email: <a href="mailto:nsw@au.empireXchange.com">nsw@au.empireXchange.com</a>.<br>
  </p>';

   $text = get_html_template($Crow['countryID'],$row2['contactname'],$tt);
   define("CRLF", "\r\n", TRUE);
   $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));
   $mail->add_html($text);

   $SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/cs/busexp-au.pdf");
   $attachArray[] = array($SBuffer, "busexp.pdf", 'base64', 'application/pdf');

   $mail->build_message();

   if($Crow['logo'] == 'ept') {

		if(strstr($row2['email'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
			   	$mail->send($row2['contactname'], trim($value), "E Banc Accounts", "accounts@".$Crow[countrycode].".ebanctrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>");
   			}
   		} else {
		   	$mail->send($row2['contactname'], $row2['email'], "E Planet Accounts", "accounts@".$Crow[countrycode].".eplanettrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".eplanettrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\nBCC: dave@ebanctrade.com");
   		}

   } elseif($Crow['logo'] == 'etx') {

		$bccArray[] = array("reports@ebanctrade.com", $row2['contactname']);

		if(strstr($row2['email'], ";")) {
			$emailArray = explode(";", $row2['email']);
			foreach($emailArray as $key => $value) {
		   		//$mail->send($row2['contactname'], trim($value), "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".empireXchange.com>\r\nErrors-To: TaxInvoiceEmailError <dave@empireXchange.com>\r\n");
				$addressArray[] = array(trim($value), $row2['contactname']);
  			}
			sendEmail("membersupport@".$Crow[countrycode].".empireXchange.com", "Empire Trade Member Support", "membersupport@".$Crow[countrycode].".empireXchange.com", "Empire Trade - Directory", "membersupport@".$Crow[countrycode].".empireXchange.com", "Empire Trade Member Support", $text, $addressArray, $attachArray, $bccArray);
    	} else {
		   	//$mail->send($row2['contactname'], $row2['email'], "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".empireXchange.com>\r\nErrors-To: TaxInvoiceEmailError <dave@empireXchange.com>\r\n");
			$addressArray[] = array(trim($row2['email']), $row2['contactname']);
			sendEmail("membersupport@".$Crow[countrycode].".empireXchange.com", "Empire Trade Member Support", "membersupport@".$Crow[countrycode].".empireXchange.com", "Empire Trade - Directory", "membersupport@".$Crow[countrycode].".empireXchange.com", "Empire Trade Member Support", $text, $addressArray, $attachArray, $bccArray);
			//print $row2['email'];
   		}

   } else {

		if(strstr($row2['email'], ";")) {
			$emailArray = explode(";", $row2['email']);
			foreach($emailArray as $key => $value) {
		   		$mail->send($row2['contactname'], trim($value), "E Banc Accounts", "accounts@".$Crow[countrycode].".ebanctrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\n");
   			}
   		} else {
		   	$mail->send($row2['contactname'], $row2['email'], "E Banc Accounts", "accounts@".$Crow[countrycode].".ebanctrade.com", $Crow['sname']." / ".$Crow['tname']." - ".$row2['companyname'],"Reply-To: TaxInvoiceQuery <accounts@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\n");
   		}

   }
   //usleep(500000);
  }

 }

?>
