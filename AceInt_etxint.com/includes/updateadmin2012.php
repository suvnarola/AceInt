<?php

// Administration Fees.

$NoSession = true;

//include("/home/etxint/admin.etxint.com/includes/global.php");
include("class.html.mime.mail.inc");
include("/home/etxint/admin.etxint.com/includes/taxinvoiceky.php");
include("/home/etxint/admin.etxint.com/monthly/statementky.php");

$dbgetmemwithfees = dbRead("SELECT transactions.memid, transactions.date, transactions.to_memid, transactions.dollarfees, transactions.type, transactions.details, transactions.authno, transactions.dis_date, transactions.checked, transactions.id, transactions.userid, members.admin_exempt, members.companyname FROM transactions INNER JOIN members ON transactions.memid = members.memid WHERE (transactions.dollarfees=199 Or transactions.dollarfees=249) AND transactions.dis_date= '2012-06-28' and transactions.memid = " . $_REQUEST['memid'] . " ORDER BY transactions.id limit 1");

$c =0;
while($row = mysql_fetch_assoc($dbgetmemwithfees)) {

   dbWrite("delete from feesincurred where trans_id = '".$row[id]."'");
   dbWrite("delete from transactions where id = '".$row[id]."'");
   dbWrite("update invoice set currentfees = currentfees-'".$row[dollarfees]."' where date = '2012-06-30' and memid = " . $row['memid'] . "");

 	$invoice_date=date("Y-m", mktime(1,1,1,date("n")-1,1,date("Y")));
 	$display_date=date("F, Y", mktime(1,1,1,date("n")-1,1,date("Y")));

	unset($attachArray);
	unset($addressArray);
    unset($bccArray);


   $Cquery = dbRead("select country.*, countrydata.* from country, countrydata where (country.countryID = countrydata.CID) and Display = 'Yes' and countryID = 1 order by countryID");
   $Crow = mysql_fetch_array($Cquery);

   $query2 = dbRead("select * from invoice where date = '2012-06-30' and memid = " . $row['memid'] . "");
   $row2 = mysql_fetch_array($query2);

   $query3 = dbRead("select tbl_members_email.email as emailaddress from tbl_members_email where tbl_members_email.type and acc_no = " . $row['memid'] . "");
   $row3 = mysql_fetch_array($query3);

   $text = get_html_template($Crow['countryID'],$row['contactname'],$Crow['emtax']);

   $newAmount = ($row2['currentfees'] + $row2['overduefees']) + $row2['currentpaid'];
   if($newAmount < 0) {
     $amo = "Amount Prepaid: $". abs(number_format($newAmount, 2));
   } else {
     $amo = "Amount Owing: $". number_format($newAmount, 2);
   }

   $text = str_replace("{AMOUNT}", $amo, $text);

   define("CRLF", "\r\n", TRUE);
   $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));
echo $row['memid'];
   $Squery = dbRead("select * from invoice, members, area, countrydata, country where (invoice.memid=members.memid) and (members.licensee=area.FieldID) and (members.CID = country.countryID) and (members.CID = countrydata.CID) and members.memid='".$row['memid']."' and invoice.date like '2012-06-%' order by companyname");
   $SBuffer = statement($Squery,true,'',true);
   //$mail->add_attachment($SBuffer, "Statement.pdf", "application/pdf");
   $attachArray[] = array($SBuffer, "Statement.pdf", 'base64', 'application/pdf');

   //if($row2['currentfees'] !=0 || $row2['overduefees'] != 0 || $row2['currentpaid'] !=0) {
    if($row2['CID'] != 15 || ($row2['CID'] == 15 && $row2['currentfees'] >= 20)) {
     //$query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country where invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '2012-06-%' and members.memid = '".$row['memid']."' order by companyname");
echo $row2['memid'];
 	$query9 = dbRead("select invoice.*, members.*, country.*, countrydata.*, members.abn as abn2 from invoice, members, country, countrydata where (country.countryID = countrydata.CID) and invoice.memid=members.memid and members.CID=country.countryID and invoice.date = '2012-06-30' and invoice.memid ='".$row2['memid']."' ");
 $rowCat = mysql_num_rows($query9);
 if($rowCat > 0) {
echo "hello";
     //$buffer = taxinvoice($query,true,'',true);
     $buffer = taxinvoice($query9,true,true,true);
 }

     $mail->add_attachment($buffer, "TaxInvoice.pdf", "application/pdf");
   	 $attachArray[] = array($buffer, "TaxInvoice.pdf", 'base64', 'application/pdf');
	}
   //}

   $mail->build_message();

	if(strstr($row3['emailaddress'], ";")) {
		$emailArray = explode(";", $row3['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row2['contactname']);
		}
	} else {
		$addressArray[] = array(trim($row3['emailaddress']), $row2['contactname']);
	}

	//sendEmail("accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", "accounts@".$Crow[countrycode].".empireXchange.com", "Amended ".$Crow['sname']." / ".$Crow['tname']." - ".$row['companyname'], "accounts@".$Crow[countrycode].".empireXchange.com", "Empire Trade Accounts", $text, $addressArray, $attachArray, $bccArray);
	echo "Emailed";
   $c++;
}

echo $c;
