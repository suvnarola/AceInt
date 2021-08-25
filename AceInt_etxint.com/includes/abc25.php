<?
 include("/home/etxint/admin.etxint.com/includes/global.php");
include("taxinvoiceky.php");
include("letterscashfees.php");
 //$query = dbRead("select * from tbl_members_log where FieldID = 700315","log");
 //$row = mysql_fetch_assoc($query);

 //print long2ip(2147483647)."<br>";
 //print ip2long($row[IPAddress]);

 	$query = dbRead("select status.*, invoice_re.*, members.*, country.*, countrydata.*, members.abn as abn2, invoice_re.amount as currentfees

	from invoice_re

		inner
			join
				members
				on invoice_re.memid=members.memid
		inner
			join
				`status`
				on members.status = status.FieldID
		inner
			join
				country
				on members.CID=country.countryID
		inner
			join
				countrydata
				on members.CID = countrydata.CID

	where
		invoice_re.FieldID in (191,189,190,188,185,184,187,186)

	order by companyname");

    // define the text.
    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Rollover Letters.";

	  $MemArray[] = 16534;
	  $MemArray[] = 14148;
	  $MemArray[] = 17962;
	  $MemArray[] = 16416;
	  $MemArray[] = 13273;
	  $MemArray[] = 15590;
	  $MemArray[] = 17027;
	  $MemArray[] = 16762;
	  $MemArray[] = 14166;

    // get the actual taxinvoice ready.
    $buffer = feeletters($MemArray,'37',$_REQUEST[header]);
    $buffer2 = taxinvoice($query,true,'',true,true);

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
   	$attachArray[] = array($buffer2, 'invoices.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'RE Rollover Letters', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);




?>