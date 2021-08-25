<?

 /**
  * Feedback Email Script.
  *
  * feedback_email.hp
  * version 0.01
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");

 $Counter = 0;

 $SQLQuery = dbRead("select categories.* from categories where feedback_sent != 1 and CID = 111 order by category limit 2");
 while($Row = mysql_fetch_assoc($SQLQuery)) {

  $CQuery = dbRead("select country.* from country where countryID = " . $Row['CID']);
  $CRow = mysql_fetch_assoc($CQuery);

  //$MemQuery = dbRead("select members.* from members, mem_categories where (members.memid = mem_categories.memid) and mem_categories.category = ".$Row['catid']." and members.feedback_sent != 1 and members.emailaddress != '' and members.status = 0");
  $MemQuery = dbRead("select members.*

	from members
		inner
			join
				mem_categories
				on members.memid = mem_categories.memid
		inner
			join
				tbl_members_email
				on members.memid = tbl_members_email.acc_no

	where

		tbl_members_email.type = 3 and mem_categories.category = ".$Row['catid']." and members.feedback_sent != 1 and tbl_members_email.email != '' and members.status = 0");

  while($MemRow = mysql_fetch_assoc($MemQuery)) {

   define("CRLF", "\r\n", TRUE);
   $PreText = "Our standard of customer service is very important to us and we request your assistance to improve it further.<br><br>From time to time we will be contacting members selected randomly from our Australian database, with a request to complete our online Service Questionnaire.<br><br>Your business has been selected in this mail-out and we would appreciate your taking the time to visit the <a target=\"_blank\" href=\"http://www.etxint.com/home/x.php?SectID=3&PageID=70&CID=1&LID=en\">questionnaire</a> and submit your responses. It should only take a few minutes and will be of great assistance to us in planning future strategies to increase and improve member participation.<br><br>";
   $text = get_html_template($MemRow['CID'],$MemRow['contactname'],$PreText);
   $mail = new html_mime_mail(array("X-Mailer: Empire Trade"));
   $mail->add_html($text);
   $mail->build_message();
   $mail->send($MemRow['contactname'], $MemRow['emailaddress'], "Empire Trade", "feedback@".$CRow[countrycode].".ebanctrade.com", "Feedback - ".$MemRow['companyname'],"Reply-To: Feedback Inquiry <feedback@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: FeedbackError <dave@ebanctrade.com>\r\n");
   //$mail->send($MemRow['contactname'], "dave@ebanctrade.com", "E Banc Trade", "feedback@".$CRow[countrycode].".ebanctrade.com", "Feedback - ".$MemRow['companyname'],"Reply-To: Feedback Inquiry <feedback@".$Crow[countrycode].".ebanctrade.com>\r\nErrors-To: FeedbackError <dave@ebanctrade.com>\r\n");

   dbWrite("update members set feedback_sent = 1 where memid = " . $MemRow['memid']);

   $Counter++;

  }

  dbWrite("update categories set feedback_sent = 1 where catid = " . $Row['catid']);

 }

 //mail("dave@ebanctrade.com", "Feedback Emails Sent", "Sent out ".$Counter." feedback emails this run.");

?>
