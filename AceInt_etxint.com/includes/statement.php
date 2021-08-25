<?
include("global.php");
include("../monthly/statementky.php");
include("class.html.mime.mail.inc");

$query5 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='".$_SESSION['User']['lang_code']."' and page = 'statement' order by position");

while($row = mysql_fetch_array($query5)) {

 $PageData[$row[position]] = $row[data];

}


if($_REQUEST[individual])  {

 $query = dbRead("select * from invoice, members, country, countrydata where (country.countryID = countrydata.CID) and invoice.memid=members.memid and members.CID=country.countryID and invoice.memid = '$_REQUEST[memid]' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'");

if($_REQUEST[view]) {

 if (@mysql_num_rows($query) > 0) {

   statement($query,$_REQUEST[stationery],true,'',$_REQUEST['currentyear']."-".$_REQUEST['currentmonth'],$_REQUEST['numbermonths']);
  // dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST[memid]."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Statement Printed -".$_REQUEST['currentyear']."-".$_REQUEST['currentmonth']."')");

 } else {

   echo get_page_data("1");
 }

die;
}

if($_REQUEST[send])  {

 //$query2 = dbRead("select * from members, country, countrydata where (members.CID = country.countryID) and (members.CID = countrydata.CID) and memid = '$_REQUEST[memid]'");
 $query2 = dbRead("select * from members, country, countrydata, tbl_members_email where (members.CID = country.countryID) and (members.memid = tbl_members_email.acc_no) and (members.CID = countrydata.CID) and memid = '$_REQUEST[memid]' and tbl_members_email.type = 2");

 while($row2 = mysql_fetch_assoc($query2)) {

  if ($row2['email']) {
   if (@mysql_num_rows($query) > 0) {

    $subject = get_word("209");
    // define the text.
    $text = get_html_template($row2['CID'],$row2['contactname'],$row2['emtax']);

    // get the actual taxinvoice ready.
    $buffer = statement($query,true,true,true,$_REQUEST['currentyear']."-".$_REQUEST['currentmonth'],$_REQUEST['numbermonths']);

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'statement.pdf', 'base64', 'application/pdf');

	if(strstr($row2[email], ";")) {
		$emailArray = explode(";", $row2[email]);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row2[contactname]);
		}
	} else {
		$addressArray[] = array(trim($row2[email]), $row2[contactname]);
	}

	//sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $_SESSION[Country][countrycode] .'.' . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);
	sendEmail('accounts@aceint.com.au', getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $_SESSION[Country][countrycode] .'.' . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    echo get_page_data("3").": $row2[email]";
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST[memid]."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Statement Emailed - ".$_REQUEST['currentyear']."-".$_REQUEST['currentmonth']."')");

   } else {

    echo get_page_data("1");

   }

  } else {

   echo get_page_data("2");

  }
 }
 }
die;
}

if($_REQUEST[monthly]) {

 //$query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash� > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-01' and (status.mem_send_inv = 1) and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
$date2=date("Y-m-d", mktime(1,1,1,$_REQUEST[currentmonth]+1,1,$_REQUEST[currentyear]));

if($_SESSION['Country']['countryID'] == 12) {
 //$query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash� > '0' and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-01' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
 $query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash > '0' and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
 //$query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash� > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
} elseif($_SESSION['Country']['countryID'] == 15) {
 //$query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash� > '0' and (invoice.currentfees > 0) and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
 $query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
} else {
 //$query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash� > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-01' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
 $query = dbRead("select * from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
}
 if($_REQUEST[view]) {
   if (@mysql_num_rows($query) > 0) {

     statement($query,$_REQUEST[stationery],'','',$_REQUEST['currentyear']."-".$_REQUEST['currentmonth'],0);

   } else {

     echo get_page_data("1");

   }
 die;
 }

 if($_REQUEST[send])  {

  if($_SESSION['User']['EmailAddress'])  {
   if (@mysql_num_rows($query) > 0) {

    // define the text.
    $text = get_html_template($_SESSION['User']['CID'], $_SESSION['User']['Name'], 'Attached is your Tax Invoices');

    // get the actual taxinvoice ready.
    $buffer = statement($query,$_REQUEST[stationery],'',true);

    unset($attachArray);
    unset($addressArray);

   	$attachArray[] = array($buffer, 'taxinvoice.pdf', 'base64', 'application/pdf');

	if(strstr($_SESSION['User']['EmailAddress'], ";")) {
		$emailArray = explode(";", $_SESSION['User']['EmailAddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $_SESSION['User']['Name']);
		}
	} else {
		$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);
	}

	//sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.'. getWho($row2[logo], 2), 'Monthly Tax Invoice ', 'accounts@' . $_SESSION[Country][countrycode] . '.'.getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);
	sendEmail('accounts@aceint.com.au', getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $_SESSION[Country][countrycode] .'.' . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    echo "Statement has been email to ".$_SESSION['User']['EmailAddress']."";

   } else {

     echo get_page_data("1");

   }

  }  else  {

    echo get_page_data("2");

  }
 }
die;
}
