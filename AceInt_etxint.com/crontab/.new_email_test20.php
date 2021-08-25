<?

ini_set("display_errors", "1");
error_reporting(E_ALL);
//awssendmail("admin.aceint.com.au - START Tax Invoice Run (new_email.php)");

//ini_set('memory_limit', '1024M'); // or you could use 1G
ini_set('memory_limit', '1000000000000M');

/**
 * Tax Invoice Run.
 */
$NoSession = true;

if (ini_get('date.timezone') == '') {
    date_default_timezone_set('UTC');
}

include("/home/etxint/admin.etxint.com/includes/global.php");
ini_set('max_execution_time', '0');

$invoice_date = date("Y-m", mktime(1, 1, 1, date("n") - 1, 1, date("Y")));
$display_date = date("F, Y", mktime(1, 1, 1, date("n") - 1, 1, date("Y")));

include("class.html.mime.mail.inc");
include("/home/etxint/admin.etxint.com/includes/taxinvoiceky.php");
include("/home/etxint/admin.etxint.com/includes/taxinvoiceky_new.php");
include("/home/etxint/admin.etxint.com/monthly/statementky_new.php");

define("CRLF", "\r\n", TRUE);
//dbWrite("insert into test_insert (name,class) values  ('john_1','AWs')");
/**
 * Go into a loop to start the process off.
 */
//$Cquery = dbRead("select country.*, countrydata.* from country, countrydata where (country.countryID = countrydata.CID) and Display = 'Yes' and countryID not in (12,10,3,15,8) order by countryID");
$Cquery = dbRead("select 
c.countryID, c.countrycode, cd.emtax, c.logo, cd.sname, cd.tname, c.name FROM  
country c LEFT JOIN countrydata cd ON c.countryID = cd.CID WHERE
c.countryID = cd.CID and c.Display = 'Yes' and c.countryID not in (12,10,3,15,8) order by c.countryID DESC");
echo "<br/>";
echo "select 
c.countryID, c.countrycode, cd.emtax, c.logo, cd.sname, cd.tname, c.name FROM  
country c LEFT JOIN countrydata cd ON c.countryID = cd.CID WHERE
c.countryID = cd.CID and c.Display = 'Yes' and c.countryID not in (12,10,3,15,8) order by c.countryID DESC";
echo "<br/>";

while ($Crow = mysql_fetch_array($Cquery)) {

    //do emails for the individual countries first.
    //$query2 = dbRead("select members.memid as memid, members.companyname as companyname, members.emailaddress as emailaddress, members.contactname as contactname, invoice.* from invoice, members, status where invoice.memid=members.memid and (members.status = status.FieldID) and members.monthlyfeecash = '0' and invoice.date like '$invoice_date-%' and (status.mem_send_inv = 1) and members.emailaddress != '' and members.CID = '".$Crow['countryID']."' order by companyname");
//  $query2 = dbRead("select members.memid as memid, members.companyname as companyname, tbl_members_email.email as emailaddress, members.contactname as contactname, invoice.* from invoice, members, status, tbl_members_email where invoice.memid=members.memid and (members.status = status.FieldID) and (members.memid = tbl_members_email.acc_no) and members.monthlyfeecash = '0' and invoice.date like '$invoice_date-%' and (status.mem_send_inv = 1) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.CID = '".$Crow['countryID']."' order by companyname");
   $query2 = dbRead("select members.CID, members.memid as memid, members.companyname as companyname, tbl_members_email.email as emailaddress, members.contactname as contactname, invoice.* FROM members LEFT JOIN invoice ON invoice.memid = 30950 LEFT JOIN status ON status.FieldID = members.status LEFT JOIN tbl_members_email ON tbl_members_email.acc_no = 30950 where invoice.memid=30950 and (members.status = status.FieldID) and (members.memid = 30950) and members.monthlyfeecash != 0 and invoice.date like '2020-12-%' and (status.mem_send_inv = 1) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.companyname = 'Cambray Place' order by members.companyname"
    );
echo "<br/>";
    echo "select members.CID, members.memid as memid, members.companyname as companyname, tbl_members_email.email as emailaddress, members.contactname as contactname, invoice.* FROM members LEFT JOIN invoice ON invoice.memid = 30950 LEFT JOIN status ON status.FieldID = members.status LEFT JOIN tbl_members_email ON tbl_members_email.acc_no = 30950 where invoice.memid=30950 and (members.status = status.FieldID) and (members.memid = 30950) and members.monthlyfeecash != 0 and invoice.date like '2020-12-%' and (status.mem_send_inv = 1) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.companyname = 'Cambray Place' order by members.companyname";
    

    $cnn = 1;
    while ($row2 = mysql_fetch_array($query2)) {

        $attachArray = array();
        $addressArray = array();
        $bccArray = array();

        unset($attachArray);
        unset($addressArray);
        unset($bccArray);

        // define the text.
        //$text = get_html_template($Crow['countryID'],$row2['contactname'],$Crow['emtax'],$Crow,$Crow);
        $text = get_html_template($Crow['countryID'], $row2['contactname'], $Crow['emtax']);
        
        $newAmount = ($row2['currentfees'] + $row2['overduefees']) + $row2['currentpaid'];
        if ($newAmount < 0) {
            $amo = "Amount Prepaid: $" . abs(number_format($newAmount, 2));
        } else {
            $amo = "Amount Owing: $" . number_format($newAmount, 2);
        }

        $text = str_replace("{AMOUNT}", $amo, $text);


        $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));

        if ($Crow['countryID'] == 4) {

            $newAmount = ($row2['currentfees'] + $row2['overduefees']) - $row2['currentpaid'];

            $text = str_replace("{AMOUNT}", number_format($newAmount, 2), $text);
            $text = str_replace("{MEMID}", $row2['memid'], $text);
        }
        $mail->add_html($text);
//        $Squery = dbRead("select * from invoice, members, area, countrydata, country where (invoice.memid=members.memid) and (members.licensee=area.FieldID) and (members.CID = country.countryID) and (members.CID = countrydata.CID) and members.memid='" . $row2['memid'] . "' and invoice.date like '$invoice_date-%' order by companyname");
        $Squery = dbRead("select * from members 
        LEFT JOIN invoice ON invoice.memid = members.memid
        LEFT JOIN area ON area.FieldID = members.licensee
        LEFT JOIN countrydata ON countrydata.CID = members.CID
        LEFT JOIN country ON country.countryID = members.CID
        where 
        (invoice.memid = members.memid) and 
        (members.licensee = area.FieldID) and 
        (members.CID = country.countryID) and 
        (members.CID = countrydata.CID) and 
        members.memid = '" . $row2['memid'] . "' and 
        invoice.date like '2020-12-%' order by members.companyname");
        echo "<br/>";
        echo "loop Query 1";
        echo "<br/>";
        echo "select * from members 
        LEFT JOIN invoice ON invoice.memid = members.memid
        LEFT JOIN area ON area.FieldID = members.licensee
        LEFT JOIN countrydata ON countrydata.CID = members.CID
        LEFT JOIN country ON country.countryID = members.CID
        where 
        (invoice.memid = members.memid) and 
        (members.licensee = area.FieldID) and 
        (members.CID = country.countryID) and 
        (members.CID = countrydata.CID) and 
        members.memid = '" . $row2['memid'] . "' and 
        invoice.date like '2020-12-%' order by members.companyname";
        $SBuffer = statement($Squery, true, '', true);
        $mail->add_attachment($SBuffer, "Statement.pdf", "application/pdf");
        $attachArray[] = array($SBuffer, "Statement.pdf", 'base64', 'application/pdf');
        
        if ($row2['currentfees'] != 0 || $row2['overduefees'] != 0 || $row2['currentpaid'] != 0) {

            if ($row2['CID'] != 15 || ($row2['CID'] == 15)) {
                if ($row2['CID'] == 15) {
                    echo "IFFFFFFFFFFF<br/>";
                    echo "select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn, invoice_es.* from members
                    LEFT JOIN countrydata ON countrydata.CID = members.CID
                    LEFT JOIN invoice ON invoice.memid = members.memid 
                    LEFT JOIN country ON country.countryID = members.CID
                    LEFT JOIN invoice_es ON invoice_es.inv_link = invoice.FieldID
                    where 
                    invoice.FieldID = invoice_es.inv_link and 
                    invoice.memid = members.memid and 
                    members.CID = country.countryID and 
                    countrydata.CID = members.CID and 
                    invoice.date like '2020-12-%' and members.memid = '" . $row2['memid'] . "' 
                    order by members.companyname";
                    echo "<br/>";
                    echo "loop Query 2";
                    echo "<br/>";
//                    $query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn, invoice_es.* from countrydata, invoice, members, country, invoice_es where invoice.FieldID = invoice_es.inv_link and invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '$invoice_date-%' and members.memid = '" . $row2['memid'] . "' order by companyname");
                    $query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn, invoice_es.* from members
                    LEFT JOIN countrydata ON countrydata.CID = members.CID
                    LEFT JOIN invoice ON invoice.memid = members.memid 
                    LEFT JOIN country ON country.countryID = members.CID
                    LEFT JOIN invoice_es ON invoice_es.inv_link = invoice.FieldID
                    where 
                    invoice.FieldID = invoice_es.inv_link and 
                    invoice.memid = members.memid and 
                    members.CID = country.countryID and 
                    countrydata.CID = members.CID and 
                    invoice.date like '2020-12-%' and members.memid = '" . $row2['memid'] . "' 
                    order by members.companyname");
                   
                } else {
                    echo "<br/><br/><br/><br/>ELSEEEEEEEEEE<br/>";
                    echo "loop Query 3";
                    echo "<br/>";
                    echo "select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from members
                    LEFT JOIN countrydata ON countrydata.CID = members.CID
                    LEFT JOIN invoice ON invoice.memid = members.memid
                    LEFT JOIN country ON country.countryID = members.CID
                    where invoice.memid = members.memid and 
                    members.CID = country.countryID and 
                    countrydata.CID = members.CID and 
                    invoice.date like '2020-12-%' and 
                    members.memid = '" . $row2['memid'] . "' 
                    order by members.companyname";
                    echo "<br/>";
//                    $query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country where invoice.memid=members.memid and members.CID=country.countryID and countrydata.CID=members.CID and invoice.date like '$invoice_date-%' and members.memid = '" . $row2['memid'] . "' order by companyname");
                    $query = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from members
                    LEFT JOIN countrydata ON countrydata.CID = members.CID
                    LEFT JOIN invoice ON invoice.memid = members.memid
                    LEFT JOIN country ON country.countryID = members.CID
                    where invoice.memid = members.memid and 
                    members.CID = country.countryID and 
                    countrydata.CID = members.CID and 
                    invoice.date like '2020-12-%' and 
                    members.memid = '" . $row2['memid'] . "' 
                    order by members.companyname");
                   
                }
                echo "Query==>".$query;
                //exit();
                $buffer = taxinvoice($query, true, '', true);
                $mail->add_attachment($buffer, "TaxInvoice.pdf", "application/pdf");
                $attachArray[] = array($buffer, "TaxInvoice.pdf", 'base64', 'application/pdf');
             
            }
        }
        
        $mail->build_message();
        echo "<pre>";
        print_r($mail);
        echo "</pre>";
        exit();
        if ($Crow['logo'] == 'ept') {
            if (strstr($row2['emailaddress'], ";")) {
                $emailArray = explode(";", $row2['emailaddress']);
                foreach ($emailArray as $key => $value) {
                   dbWrite("insert into test_insert (name,class) values  ('john_2','AWs')");
                  $mail->send('dev1@atomicwebstrategy.com', trim($value), "E Banc Accounts", "accounts@" . $Crow[countrycode] . ".ebanctrade.com", $Crow['sname'] . " / " . $Crow['tname'] . " - " . $row2['companyname'], "Reply-To: TaxInvoiceQuery <accounts@" . $Crow[countrycode] . ".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>");
                }
            } else {
                dbWrite("insert into test_insert (name,class) values  ('john_3','AWs')");
               $mail->send('dev1@atomicwebstrategy.com', "E Planet Accounts", "accounts@" . $Crow[countrycode] . ".eplanettrade.com", $Crow['sname'] . " / " . $Crow['tname'] . " - " . $row2['companyname'], "Reply-To: TaxInvoiceQuery <accounts@" . $Crow[countrycode] . ".eplanettrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\nBCC: dave@ebanctrade.com");
            }
        } elseif ($Crow['logo'] == 'etx') {
            echo "LOGO======>ETX";
            echo "<br/>";

            if (strstr($row2['emailaddress'], ";")) {
                $emailArray = explode(";", $row2['emailaddress']);
                foreach ($emailArray as $key => $value) {
                    //$addressArray[] = array(trim($value), $row2['contactname']);
                    $addressArray[] = array(trim('dev1@atomicwebstrategy.com'), $row2['contactname']);
                }
            } else {
                $addressArray[] = array(trim('dev1@atomicwebstrategy.com'), $row2['contactname']);
            }
//            print_r($addressArray);
//            echo '<br>';
//            $addressArray = array();
            $addressArray = array(array('dev1@atomicwebstrategy.com', 'Testing User'));
//            $addressArray = array(array('keknarola@gmail.com', 'Testing User'));
           dbWrite("insert into test_insert (name,class) values  ('john_4','AWs')");
           sendEmail('dev1@atomicwebstrategy.com', "Empire Trade Accounts", 'dev1@atomicwebstrategy.com', $Crow['sname'] . " / " . $Crow['tname'] . " - " . $row2['companyname'], "accounts@aceint.com.au", "Empire Trade Accounts", $text, $addressArray, $attachArray, false);
        } else {
            if (strstr($row2['emailaddress'], ";")) {
                $emailArray = explode(";", $row2['emailaddress']);
                foreach ($emailArray as $key => $value) {
                    dbWrite("insert into test_insert (name,class) values  ('john_5','AWs')");
                    $mail->send('dev1@atomicwebstrategy.com', "E Banc Accounts", "accounts@" . $Crow[countrycode] . ".ebanctrade.com", $Crow['sname'] . " / " . $Crow['tname'] . " - " . $row2['companyname'], "Reply-To: TaxInvoiceQuery <accounts@" . $Crow[countrycode] . ".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\n");
                }
            } else {
                dbWrite("insert into test_insert (name,class) values  ('john_6','AWs')");
               $mail->send('dev1@atomicwebstrategy.com', "E Banc Accounts", "accounts@" . $Crow[countrycode] . ".ebanctrade.com", $Crow['sname'] . " / " . $Crow['tname'] . " - " . $row2['companyname'], "Reply-To: TaxInvoiceQuery <accounts@" . $Crow[countrycode] . ".ebanctrade.com>\r\nErrors-To: TaxInvoiceEmailError <dave@ebanctrade.com>\r\n");
            }
        }
        die();
//        if($cnn == 5){
//        die();
//        }
//        $cnn++;
       // usleep(500000);
    }


//    $Mquery = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country, status where members.CID=country.countryID and (members.status = status.FieldID) and invoice.memid=members.memid and countrydata.CID=members.CID and invoice.date like '" . $invoice_date . "-%' and members.CID like '" . $Crow['countryID'] . "' and members.monthlyfeecash != '0' and status.mem_send_inv = 1 and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) order by members.companyname");
    $Mquery = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn 
    from members
    LEFT JOIN countrydata ON countrydata.CID = members.CID
    LEFT JOIN invoice ON invoice.memid = members.memid
    LEFT JOIN country ON country.countryID = members.CID
    LEFT JOIN status ON status.FieldID = members.status
    where 
    members.CID = country.countryID and 
    (members.status = status.FieldID) and 
    invoice.memid = members.memid and 
    countrydata.CID = members.CID and 
    invoice.date like '2020-12-%' and 
    members.CID like '" . $Crow['countryID'] . "' and 
    members.monthlyfeecash != '0' and 
    status.mem_send_inv = 1 and 
    (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) 
    order by members.companyname");

    $Mrow = mysql_fetch_array($Mquery);
    if ($Mrow['memid']) {
//        $Mquery2 = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn from countrydata, invoice, members, country, status where members.CID=country.countryID and (members.status = status.FieldID) and invoice.memid=members.memid and countrydata.CID=members.CID and invoice.date like '" . $invoice_date . "-%' and members.CID like '" . $Crow['countryID'] . "' and members.monthlyfeecash != '0' and status.mem_send_inv = 1 and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) order by members.companyname");
        $Mquery2 = dbRead("select countrydata.*, invoice.*, members.*, country.*, members.abn as abnn 
        from members
        LEFT JOIN countrydata ON countrydata.CID = members.CID
        LEFT JOIN invoice ON invoice.memid = members.memid
        LEFT JOIN country ON country.countryID = members.CID
        LEFT JOIN status ON status.FieldID = members.status
        where members.CID = country.countryID and 
        (members.status = status.FieldID) and 
        invoice.memid = members.memid and 
        countrydata.CID = members.CID and 
        invoice.date like '2020-12-%' and 
        members.CID like '" . $Crow['countryID'] . "' and 
        status.mem_send_inv = 1 and 
        (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0)         
        order by members.companyname");

        $data_response = array();
        while ($row = mysql_fetch_assoc($Mquery2)) {
            $data_response[] = $row;
        }
        $data_group = array();
        $data_group = count($data_response > 300) ? array_chunk($data_response, 300) : $data_response;

        $text = "Here are your Tax Invoices for the month of $display_date.\r\n\r\nPlease print and mail them out.\r\n\r\nYou may need Adobe Acrobat to read these Tax Invoices.\r\nTo get it go here http://www.adobe.com/\r\n\r\nE Banc Trade Accounts";
        if (isset($data_group) && sizeof($data_group) > 0) {
            foreach ($data_group as $data) {
                $buffer = taxinvoice_data_pdf($data, true, '', true);
                $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));
                $mail->add_text($text);
                $mail->add_attachment($buffer, "TaxInvoice-Print.pdf", "application/pdf");
                $mail->build_message();
                dbWrite("insert into test_insert (name,class) values  ('john_7','AWs')");
                $mail->send($Crow['name'], "dev1@atomicwebstrategy.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - " . $Crow['name'], "Bcc: dave@ebanctrade.com");
//////        $mail->send($Crow['name'], "dev1@atomicwebstrategy.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - " . $Crow['name'], "Bcc: dave@ebanctrade.com");
//                $mail->send($Crow['name'], "keknarola@gmail.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - " . $Crow['name'], "Bcc: dave@ebanctrade.com");
//                sleep(10);                
//                $ii++;
            }
        }

//        $text = "Here are your Tax Invoices for the month of $display_date.\r\n\r\nPlease print and mail them out.\r\n\r\nYou may need Adobe Acrobat to read these Tax Invoices.\r\nTo get it go here http://www.adobe.com/\r\n\r\nE Banc Trade Accounts";
//        $buffer = taxinvoice($Mquery2, true, '', true);
////        define("CRLF", "\r\n", TRUE);
//        $mail = new html_mime_mail(array("X-Mailer: E Banc Trade"));
//        $mail->add_text($text);
//        $mail->add_attachment($buffer, "TaxInvoice-Print.pdf", "application/pdf");
//        $mail->build_message();
 //       $mail->send($Crow['name'], "accounts@" . $Crow['countrycode'] . "ebanctrade.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - " . $Crow['name'], "Bcc: dave@ebanctrade.com");
////        $mail->send($Crow['name'], "dev1@atomicwebstrategy.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - " . $Crow['name'], "Bcc: dave@ebanctrade.com");
////        $mail->send($Crow['name'], "keknarola@gmail.com", "E Banc Accounts", "accounts@ebanctrade.com", "Tax Invoice - " . $Crow['name'], "Bcc: dave@ebanctrade.com");
    }
    $cn_rw++;
}

awssendmail("admin.aceint.com.au - END Tax Invoice Run (new_email.php)");

function awssendmail($m) {
//    $to = "info@atomicwebstrategy.com";
    $to = "dev1@atomicwebstrategy.com";
//    $to = "keknarola@gmail.com";
    $subject = "admin.aceint.com.au - Cron.";
    $message = $m;
    $from = "noreply@aceint.com.au";
    $headers = "From: $from";
   mail('dev1@atomicwebstrategy.com', $subject, $message, $headers);      // Send Mail.
}

?>
