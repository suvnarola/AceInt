<?

 /**
  * Tax Invoice Run.
  * 12/02/08 - Agent Area 1
  * 24/02/08 - Regional Areas all QLD
  * 06/03/08 - Regional Areas all NSW
  * 10/03/08 - Regional Areas all WA
  * 13/03/08 - Regional Areas all SA
  * 25/05/08 - Regional Areas all TAS
  * 25/05/08 - Regional Areas all VIC
  * 25/05/08 - Regional Areas all ACT
  * 08/09/08 - Regional Areas all QLD
  * 15/09/08 - Regional Areas all NSW
  * 10/11/08 - Regional Areas all WA
  * 10/11/08 - Regional Areas all SA
  * 01/12/08 - Regional Areas all VIC
  * 08/12/08 - Regional Areas all ACT
  * 08/12/08 - Regional Areas all TAS
  * 29/07/09 - Regional Areas all WA
  * 29/07/09 - Regional Areas all TAS
  * 03/08/09 - Regional Areas all SA
  * 03/08/09 - Regional Areas all Lance
  * 03/08/09 - Regional Areas all Newcastle
  * 26/08/09 - Regional Areas all NSW
  * 26/08/09 - Regional Areas all VIC
  * 26/08/09 - Regional Areas all Rest of QLD
 **/

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 ini_set('max_execution_time','1500');

 $date11 = date("Y-m-d", mktime(1,1,1,date("m")-3,date("d"),date("Y")));

 /**
  * Go into a loop to start the process off.
  */

 $Cquery = dbRead("select country.*, countrydata.* from country, countrydata where (country.countryID = countrydata.CID) and Display = 'Yes' and countryID = 1 order by countryID");
 while($Crow = mysql_fetch_array($Cquery)) {

  //do emails for the individual countries first.
  $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress, tbl_area_physical.RegionalID from members, status, tbl_members_email, tbl_area_physical where (members.status = status.FieldID) and (members.memid = tbl_members_email.acc_no) and (status.mem_send_inv = 1) and members.area = tbl_area_physical.FieldID and tbl_members_email.email != '' and tbl_members_email.type = 3 and members.last_confirm < '".$date11."' and tbl_area_physical.RegionalID in (4,5,26,29,34) and members.CID = '".$Crow['countryID']."' order by companyname");
  while($row2 = mysql_fetch_array($query2)) {

	unset($attachArray);
	unset($addressArray);
	unset($bccArray);

    $query = dbRead("select mem_categories.*, categories.category as catname, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$row2['memid']."' order by mem_categories.FieldID");
    $Counter = 1;
    $Categories = "";
    while($catrow = mysql_fetch_assoc($query)) {

      if($catrow['category'] != 0) {
       $Categories .= "".get_word("26")." $Counter: <b>$catrow[catname]</b><br>".get_word("27")." $Counter: <b>$catrow[description]</b><br><br>";
       $Counter++;
      }

    }

	if($Categories) {
	 if(is_numeric($row2['trade_per']) && $row2['trade_per']) {
	  $Categories = $Categories."Last known Trade %: <b>".$row2['trade_per']."%</b><br>";
	 } else {
	  $Categories = $Categories."Last known Trade %: <b>Please indicate your accepted trade %.</b><br>";
	 }
	}

	if(!$Categories || $row2['t_unlist']) {
	 $Categories = $Categories." You are currently not listed in the directory at your request however, if you want to be relist in the directory please contact head office.<br><br>";
	} elseif($Categories) {
	 $Categories = $Categories."(".get_word("211").")<br><br>";
	}

   // define the text.
   $text1 = "".$Crow['partb']." ".$Crow['phone']." ".$Crow['partc']."<br><br>".get_word("50").": <b>$row2[memid]</b><br>".get_word("4").": <b>$row2[accholder]</b><br>".get_word("5").": <b>$row2[contactname]</b><br>".get_word("3").": <b>$row2[companyname]</b><br>".get_word("9").": <b>$row2[emailaddress]</b><br>".get_word("28").": <b>$row2[webpageurl]</b><br>".get_word("7").": <b>$row2[phonearea] $row2[phoneno]</b><br>".get_word("8").": <b>$row2[faxarea] $row2[faxno]</b><br>".get_word("11").": <b>$row2[homephonearea] $row2[homephone]</b> (".get_word("210").")<br>".get_word("10").": <b>$row2[mobile]</b> (".get_word("210").")<br><br>$Categories ".get_word("129").": <b>$row2[streetno] $row2[streetname] $row2[suburb] $row2[city] $row2[state] $row2[postcode]</b><br>".get_word("93").": <b>$row2[postalno] $row2[postalname] $row2[postalsuburb] $row2[postalcity] $row2[postalstate] $row2[postalpostcode]</b>";
   $text1 = $text1.'<br><p><b>Member Directory</b><br>To ensure you are always up to date with your fellow exchange members, we are emailing you a link to your local area directory. Simply click the <b>DOWNLOAD</b> icon and your directory will be saved to your computer. You can then print it out and take it with you, or access it from your computer. While is it valuable to have your local area directory, remember we have many members who can trade nationally.</p><p></p><p align="center"><a href="http://www.ebanctrade.com/home/directory_download.php?disarea='.$row2['RegionalID'].'"target="_blank"><img src="http://media.ebanctrade.com/uploads/Image/download.jpg" width="150" height="67" border="0" alt="Download Directory" align="middle"></a></p><p></p><b>Member Site</b><br>To access the full member directory along with the latest classifieds, real estate listings, product catalogue and your latest account information <a href="http://www.empireXchange.com/members">CLICK HERE</a> to log into the member section.<br><br><br>Regards<br>Membership Accounts Department';
   $text = get_html_template($Crow['countryID'],$row2['contactname'],$text1);
   define("CRLF", "\r\n", TRUE);
   $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));
   $mail->add_html($text);
   $mail->build_message();

	if(strstr($row2['emailaddress'], ";")) {
		$emailArray = explode(";", $row2['emailaddress']);
		foreach($emailArray as $key => $value) {
		  $addressArray[] = array(trim($value), $row2['contactname']);
		  //$addressArray[] = array(trim("dave@ebanctrade.com"), $row2['contactname']);
		}
	} else {
		$addressArray[] = array(trim($row2['emailaddress']), $row2['contactname']);
		//$addressArray[] = array(trim("dave@ebanctrade.com"), $row2['contactname']);
	}

	$bccArray[] = array(trim("dave@ebanctrade.com"), $row2['contactname']);

   //$addressArray[] = array(trim('dave.r@hq.etxint.com'), $row2['contactname']);

   sendEmail("accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", "Contact Details Confirmation - ".$row2['companyname'], "accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", $text, $addressArray, $attachArray, $bccArray);

   //usleep(500000);
  }

 }

?>