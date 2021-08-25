<?php

include("global.php");

include("taxinvoiceky.php");

include("class.html.mime.mail.inc");

include("htmlMimeMail.php");



$query5 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='" . $_SESSION['User']['lang_code'] . "' and page = 'taxinvoice' order by position");



while ($row = mysql_fetch_array($query5)) {



    $PageData[$row['position']] = $row['data'];
}



//function get_page_data($id)  {
//global $PageData;
//return $PageData[$id];
//}



if ($_REQUEST['individual']) {



    $startdate = date("Y-m", mktime(0, 0, 0, $_REQUEST['currentmonth'] - $_REQUEST['numbermonths'], 1, $_REQUEST['currentyear']));

    //$query = dbRead("select invoice.*, members.*, country.*, countrydata.*, members.abn as abn2 from invoice, members, country, countrydata where (country.countryID = countrydata.CID) and invoice.memid=members.memid and members.CID=country.countryID and invoice.memid = '$_REQUEST[memid]' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'");

    if ($_SESSION['Country']['countryID'] == 15) {

        //$query = dbRead("select status.*, invoice.*, members.*, country.*, countrydata.*, members.abn as abn2 from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash? > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and (invoice.currentfees > 0) and members.CID='".$_SESSION['User']['CID']."' order by companyname");

        $query = dbRead("select invoice.*, members.*, country.*, countrydata.*, members.abn as abn2, invoice_es.* from invoice, members, country, countrydata, invoice_es where invoice.FieldID = invoice_es.inv_link and (country.countryID = countrydata.CID) and invoice.memid=members.memid and members.CID=country.countryID and invoice.memid = '$_REQUEST[memid]' and invoice.date between '$startdate-1' and '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-31'");
    } else {

        $query = dbRead("select invoice.*, members.*, country.*, countrydata.*, members.abn as abn2 from invoice, members, country, countrydata where (country.countryID = countrydata.CID) and invoice.memid=members.memid and members.CID=country.countryID and invoice.memid = '$_REQUEST[memid]' and invoice.date between '$startdate-1' and '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-31'");
    }



    if ($_REQUEST['view']) {



        if (@mysql_num_rows($query) > 0) {



            taxinvoice($query, @$_REQUEST['stationery'], true, '');

            dbWrite("insert into notes (memid,date,userid,type,note) values ('" . $_REQUEST[memid] . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "','1','Tax Invoice Printed -" . $_REQUEST['currentyear'] . "-" . $_REQUEST['currentmonth'] . "')");
        } else {



            echo get_page_data("1");
        }



        die;
    }



    if ($_REQUEST[send]) {



        //$query2 = dbRead("select members.*, countrydata.*, members.abn as abn2 from members, countrydata where (members.CID = countrydata.CID) and memid = '$_REQUEST[memid]'");

        $query2 = dbRead("select country.*, members.*, countrydata.*, members.abn as abn2, tbl_members_email.email as emailaddress from country, members, countrydata, tbl_members_email where (members.CID = country.countryID) and (members.memid = tbl_members_email.acc_no) and (members.CID = countrydata.CID) and tbl_members_email.acc_no = '$_REQUEST[memid]' and tbl_members_email.type = 2");



        while ($row2 = mysql_fetch_assoc($query2)) {



            if ($row2['emailaddress']) {

                if (@mysql_num_rows($query) > 0) {



                    $subject = get_word("177");

                    $text = get_html_template($row2['CID'], $row2['contactname'], $row2['emtax']);

                    $buffer = taxinvoice($query, true, true, true);



                    unset($attachArray);

                    unset($addressArray);



                    $attachArray[] = array($buffer, 'taxinvoice.pdf', 'base64', 'application/pdf');



                    if (strstr($row2[emailaddress], ";")) {

                        $emailArray = explode(";", $row2[emailaddress]);

                        foreach ($emailArray as $key => $value) {

                            $addressArray[] = array(trim($value), $row2[contactname]);
                        }
                    } else {

                        $addressArray[] = array(trim($row2[emailaddress]), $row2[contactname]);
                    }



//	sendEmail("accounts@" . $row2[countrycode] .".". getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $row2[countrycode] .'.'. getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $row2[countrycode] .'.' . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);
                    sendEmail('accounts@aceint.com.au', getWho($row2[logo], 1) . ' Accounts', 'accounts@' . $row2[countrycode] . '.' . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $row2[countrycode] . '.' . getWho($row2[logo], 2), getWho($row2[logo], 1) . ' Accounts', $text, $addressArray, $attachArray);


                    print get_page_data("3") . " " . $row2['emailaddress'];

                    dbWrite("insert into notes (memid,date,userid,type,note) values ('" . $_REQUEST[memid] . "','" . date("Y-m-d") . "','" . $_SESSION['User']['FieldID'] . "','1','Tax Invoice Emailed -" . $_REQUEST['currentyear'] . "-" . $_REQUEST['currentmonth'] . "')");
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



if ($_REQUEST[monthly]) {



    $date2 = date("Y-m-d", mktime(1, 1, 1, $_REQUEST[currentmonth] + 1, 1, $_REQUEST[currentyear]));

    if ($_SESSION['Country']['countryID'] == 15) {

        //$query = dbRead("select status.*, invoice.*, members.*, country.*, countrydata.*, members.abn as abn2 from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash? > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and (invoice.currentfees > 0) and members.CID='".$_SESSION['User']['CID']."' order by companyname");
        //$query = dbRead("select status.*, invoice.*, members.*, country.*, countrydata.*, members.abn as abn2, invoice_es.* from status, invoice, members, country, countrydata, invoice_es where invoice.FieldID = invoice_es.inv_link and invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash? > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and members.CID='".$_SESSION['User']['CID']."' order by companyname");

        $query = dbRead("select status.*, invoice.*, members.*, country.*, countrydata.*, members.abn as abn2, invoice_es.* from status, invoice, members, country, countrydata, invoice_es where invoice.FieldID = invoice_es.inv_link and invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '" . $date2 . "' and (status.mem_send_inv = 1) and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and members.CID='" . $_SESSION['User']['CID'] . "' order by companyname");
    } else {

        //$query = dbRead("select status.*, invoice.*, members.*, country.*, countrydata.*, members.abn as abn2 from status, invoice, members, country, countrydata where invoice.memid=members.memid and (members.status = status.FieldID) and members.CID=country.countryID and members.CID = countrydata.CID and members.monthlyfeecash? > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '".$date2."' and (status.mem_send_inv = 1) and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and members.CID='".$_SESSION['User']['CID']."' order by companyname");

        $query = dbRead("select status.*, invoice.*, members.*, country.*, countrydata.*, members.abn as abn2



	from invoice



		inner

			join

				members

				on invoice.memid=members.memid

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

		members.monthlyfeecash? > '0' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.datejoined < '" . $date2 . "' and (status.mem_send_inv = 1) and (invoice.currentpaid != 0 or invoice.currentfees != 0 or invoice.overduefees > 0) and members.CID='" . $_SESSION['User']['CID'] . "'



	order by companyname");
    }



    if ($_REQUEST[view]) {

        if (@mysql_num_rows($query) > 0) {



            taxinvoice($query, $_REQUEST[stationery], '', '');
        } else {



            echo get_page_data("1");
        }

        die;
    }



    if ($_REQUEST[send]) {



        if ($_SESSION['User']['EmailAddress']) {

            if (@mysql_num_rows($query) > 0) {



                // define the text.

                $text = get_html_template($_SESSION['User']['CID'], $_SESSION['User']['Name'], 'Attached is your Tax Invoices');



                // get the actual taxinvoice ready.

                $buffer = taxinvoice($query, $_REQUEST[stationery], '', true);



                // define carriage returns for macs and pc's

                define('CRLF', "\r\n", TRUE);



                // create a new mail instance
                //$mail = new html_mime_mail();
                // add the text in.
                //$mail->add_html($text);
                // add the attachment on.
                //$mail->add_attachment($buffer, 'taxinvoice.pdf', 'application/pdf');
                // build the message.
                //$mail->build_message();
                // send the message.
                //$mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts', 'dave@ebanctrade.com', 'Tax Invoice - '.$row2[companyname],'Bcc: dave@ebanctrade.com');
                //$mail->smtp_send($testArray);



                unset($attachArray);

                unset($addressArray);



                $attachArray[] = array($buffer, 'taxinvoice.pdf', 'base64', 'application/pdf');



                if (strstr($_SESSION['User']['EmailAddress'], ";")) {

                    $emailArray = explode(";", $_SESSION['User']['EmailAddress']);

                    foreach ($emailArray as $key => $value) {

                        $addressArray[] = array(trim($value), $_SESSION['User']['Name']);
                    }
                } else {

                    $addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);
                }



//	sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.'. getWho($_SESSION[Country][logo], 2), 'Monthly Tax Invoice ', 'accounts@' . $_SESSION[Country][countrycode] . '.'.getWho($_SESSION[Country][logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);
                sendEmail('accounts@aceint.com.au', getWho($row2[logo], 1) . ' Accounts', 'accounts@' . $row2[countrycode] . '.' . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $row2[countrycode] . '.' . getWho($row2[logo], 2), getWho($row2[logo], 1) . ' Accounts', $text, $addressArray, $attachArray);



                echo "Tax Invoice has been email to " . $_SESSION['User']['EmailAddress'] . "";
            } else {



                echo get_page_data("1");
            }
        } else {



            echo get_page_data("2");
        }
    }

    die;
}



if ($_REQUEST[invoice]) {



    die('here we are ');



    if ($_REQUEST['tax']) {

        $tax = $_SESSION['Country']['tax'];
    } else {

        $tax = 0;
    }





    $dd = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

    if (!$_REQUEST['cc']) {

        $ino = dbWrite("insert into invoice_es (inv_date,inv_memid,inv_type,inv_desc,inv_amount,inv_tax) values ('" . $dd . "','" . $_REQUEST['memid'] . "','" . $_REQUEST['type'] . "','" . encode_text2($_REQUEST['desc']) . "','" . $_REQUEST['amount'] . "','" . $tax . "')", "etradebanc", true);
    } else {

        $ino = $_REQUEST['no'];
    }

    $query = dbRead("select invoice_es.inv_date as date, invoice_es.inv_memid as memid, invoice_es.inv_amount as currentfees, invoice_es.inv_no as FieldID, invoice_es.inv_tax as taxes, invoice_es.inv_desc as det, invoice_es.inv_type as inv_type, members.*, country.*, countrydata.*, members.abn as abn2 from invoice_es, members, country, countrydata where (country.countryID = countrydata.CID) and invoice_es.inv_memid=members.memid and members.CID=country.countryID and invoice_es.inv_no = " . $ino);



    //if($_REQUEST[view]) {
    //if (@mysql_num_rows($query) > 0) {



    taxinvoice($query, 1, true, '');

    //dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST[memid]."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Tax Invoice Printed -".$_REQUEST['currentyear']."-".$_REQUEST['currentmonth']."')");
    //} else {
    //echo get_page_data("1");
    //}
    //}



    die;
}

