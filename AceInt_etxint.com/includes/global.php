<?php
require_once 'modules/class.logger.php';

ini_set("error_reporting", E_ALL & ~E_DEPRECATED & ~E_NOTICE);

$CONFIG['db_name'] = "etxint_etradebanc";
$CONFIG['db_host'] = "localhost";
$CONFIG['db_user'] = "etxint_admin";
$CONFIG['db_pass'] = "Ohc6icho6eimaid3";
$CONFIG['db_linkid'] = @mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

$CONFIG['auth_enabled'] = false;
$CONFIG['auth_realm'] = "E Banc Administration.";
$CONFIG['auth_failed'] = "";
$CONFIG['auth_suspend'] = "";
$CONFIG['auth_user'] = "";
$CONFIG['auth_pass'] = "";
$CONFIG['DEBUG'] = true;
$CONFIG[graphver] = "1.16";

$db = $CONFIG['db_name'];
$db_mess = "etxint_ebanc_message";
$linkid = $CONFIG['db_linkid'];


/**
 * Sessions.
 */
if ($_REQUEST['page'] == "email_system/defaultnew") {
    ini_set("zlib.output_compression", 0);
}
if ($_REQUEST['page'] != "nav2") {

    include_once("sessions.php");
}

include("josql.php");
//include("modules/class.empiremailer.php");
include("/home/etxint/admin.etxint.com/includes/modules/class.empiremailer.php");

/**
 * Login Redirect.
 */
if (!$NoSession) {

    if (!is_loggedin()) {

        $pageTemp = explode("/", $_SERVER['SCRIPT_NAME']);
        $pageTemp = array_reverse($pageTemp);

        if ($pageTemp[0] != "index.php") {

            header("Location: /");
            die;
        }
    }
}


/**
 * SSL Redirect
 */
if (!$NoSession) {

    if ($_SERVER['SERVER_PORT'] != 443) {


//  	die( $_SERVER['HTTP_HOST'] ) ;

        header("Location: https://" . $_SERVER['HTTP_HOST']);
    }
}

//die( 'past ssl redirect') ;


$DB_Count = 0;

if (!$_SESSION['Country']) {

    $query2 = dbRead("select * from country where countryID='" . $_SESSION['User']['CID'] . "'");
    $_SESSION['Country'] = @mysql_fetch_assoc($query2);
}

if (!$_SESSION['CountryPref_Members']) {

    $query4 = dbRead("select * from countrypref_members where CID='" . $_SESSION['User']['CID'] . "'");
    $CountryPref_Members = @mysql_fetch_assoc($query4);
    $_SESSION['CountryPref_Members'] = $CountryPref_Members;
}

$ReqPage = ($_REQUEST['page']) ? $_REQUEST['page'] : "mem_search";

if ($_REQUEST['page'] == "TransferNew")
    $ReqPage = "transfer";

if ($_REQUEST['page'] == "nav2") {

    $UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = " . $_REQUEST['UserID'] . " and md5Password = '" . $_REQUEST['md5'] . "'");
    $UserRow = mysql_fetch_assoc($UserSQL);

    $query5 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='" . $UserRow['lang_code'] . "' and page = '" . $ReqPage . "' order by position");
    $query6 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='en' and page = '" . $ReqPage . "' order by position");
} else {
    $query5 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='" . $_SESSION['User']['lang_code'] . "' and page = '" . $ReqPage . "' order by position");
    $query6 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='en' and page = '" . $ReqPage . "' order by position");
}
while ($row = mysql_fetch_array($query5)) {

    $PageData[$row['position']] = $row['data'];
}

if ($_SESSION['User']['lang_code'] != 'en') {
    while ($row = mysql_fetch_array($query6)) {

        $PageDataEN[$row['position']] = $row['data'];
    }
}

function get_page_data($id, $help = false) {
    global $PageData;
    global $PageDataEN;

    if ($_SESSION['User']['lang_code'] != 'en' && !$help) {
        return $PageData[$id] . " <img src='images/qu.jpg' alt='" . $PageDataEN[$id] . "' style='border-width:0px;' />";
    } else {
        return $PageData[$id];
    }
}

if (!$_SESSION['WordData']) {

    $query6 = dbRead("select wordid, word from tbl_lang_keywords where langcode='" . $_SESSION['User']['lang_code'] . "' order by wordid");
    while ($row = mysql_fetch_array($query6)) {

        $_SESSION['WordData'][$row[wordid]] = $row[word];
    }
}

if (!$_SESSION['WordDataEN'] && $_SESSION['User']['lang_code'] != 'en') {

    $query7 = dbRead("select wordid, word from tbl_lang_keywords where langcode='en' order by wordid");
    while ($row = mysql_fetch_array($query7)) {

        $_SESSION['WordDataEN'][$row['wordid']] = $row['word'];
    }
}

function get_word($id, $help = false) {
    if ($_SESSION['User']['lang_code'] != 'en' && !$help) {
        return $_SESSION['WordData'][$id] . " <img src='images/qu.jpg' alt='" . $_SESSION['WordDataEN'][$id] . "' style='border-width:0px;' />";
    } else {
        return $_SESSION['WordData'][$id];
    }
}

function getWho($logo, $type) {

    switch ($type) {

        case "1":

            /**
             * Text.
             */
            switch ($logo) {

                case "etx":

                    return "A.C.E. International";
                    break;

                case "ept":

                    return "E Planet Trade";
                    break;

                default:

                    return "E Banc Trade";
                    break;
            }

            break;

        case "2":

            /**
             * Domain.
             */
            switch ($logo) {

                case "etx":

                    //return "empireXchange.com";
                    //return "empiretrade.com.au";
                    return "accesscommercial.exchange";
                    break;

                case "ept":

                    return "eplanettrade.com";
                    break;

                default:

                    return "ebanctrade.com";
                    break;
            }

            break;
    }
}

function sendEmail($from, $fromName, $sender, $subject, $replyTo, $replyToName, $body, $addAddress = array(), $addAttach = false, $bccArray = false) {

    if (isset($_SESSION['Username']) && !empty($_SESSION['Username'])) {
        ETXLogger::getLogger()->info("SendEmail starting with subject: " . $subject, array('username' => $_SESSION['Username']));
        ETXLogger::getLogger()->info(" -> from: " . $from, array('username' => $_SESSION['Username']));
    } else {
        ETXLogger::getLogger()->info("SendEmail starting with subject: " . $subject, array('username' => ''));
        ETXLogger::getLogger()->info(" -> from: " . $from, array('username' => ''));
    }
    $awesomeMail = new EmpireMailer();

    $awesomeMail->Priority = 3;
    $awesomeMail->IsHTML(true);
    $awesomeMail->CharSet = "utf-8";
    $awesomeMail->IsSendmail(true);

    $awesomeMail->From = $from;
    $awesomeMail->FromName = $fromName;
    $awesomeMail->Sender = $sender;
    $awesomeMail->Subject = $subject;
    $awesomeMail->AddReplyTo($replyTo, $replyToName);
    $awesomeMail->Body = $body;

    if ($addAttach) {
        foreach ($addAttach as $key => $value) {
            $awesomeMail->AddStringAttachment($value[0], $value[1], $value[2], $value[3]);
        }
    }

    if (is_array($bccArray)) {
        foreach ($bccArray as $key => $value) {
            $awesomeMail->AddBCC($value[0], $value[1]);
        }
    }

    foreach ($addAddress as $key => $value) {
        if (isset($_SESSION['Username']) && !empty($_SESSION['Username']))
            ETXLogger::getLogger()->info(" -> adding address: " . $value[0], array('username' => $_SESSION['Username']));
        else
            ETXLogger::getLogger()->info(" -> adding address: " . $value[0], array('username' => ''));
        $awesomeMail->AddAddress($value[0], $value[1]);
    }
    $res = $awesomeMail->Send();    
    if ($res) {
        if (isset($_SESSION['Username']) && !empty($_SESSION['Username']))
            ETXLogger::getLogger()->info(" -> send successful", array('username' => $_SESSION['Username']));
        else
            ETXLogger::getLogger()->info(" -> send successful", array('username' => ''));
    } else {
        if (isset($_SESSION['Username']) && !empty($_SESSION['Username']))
            ETXLogger::getLogger()->info(" -> send error: " . $awesomeMail->ErrorInfo, array('username' => $_SESSION['Username']));
        else
            ETXLogger::getLogger()->info(" -> send error: " . $awesomeMail->ErrorInfo, array('username' => ''));
    }
}

function dbtList($Table, $database = false) {
    global $CONFIG, $DB_Count;
    if ($database == false) {
        $database = $CONFIG['db_name'];
    }

    if ($CONFIG['db_linkid'] == False) {
        dbReportError(mysql_errno(), mysql_error(), $Table);
    }

    $rsid = mysql_list_fields($database, $Table, $CONFIG['db_linkid']);
    if ($rsid == False) {
        dbReportError(mysql_errno(), mysql_error(), $Table);
    }

    $Columns = mysql_num_fields($rsid);

    for ($i = 0; $i < $Columns; $i++) {
        $ReturnArray[] = mysql_field_name($rsid, $i);
    }

    $DB_Count++;

    return($ReturnArray);
}

function dbRead($SQLQuery, $database = false) {
    global $CONFIG, $DB_Count;
    if ($database == false) {
        $database = $CONFIG['db_name'];
    }

    if ($CONFIG['db_linkid'] == False) {
        dbReportError(mysql_errno(), mysql_error(), $SQLQuery);
    }

    mysql_select_db($database);

    $rsid = mysql_query($SQLQuery, $CONFIG['db_linkid']);
    if ($rsid == False) {
        dbReportError(mysql_errno(), mysql_error(), $SQLQuery);
    }

    $DB_Count++;

    return($rsid);
}

function dbWrite($SQLQuery, $database = false, $DBReturnID = False) {
    global $CONFIG, $DB_Count;
    if ($database == false) {
        $database = $CONFIG['db_name'];
    }

    if ($CONFIG['db_linkid'] == False) {
        dbReportError(mysql_errno(), mysql_error(), $SQLQuery);
    }

    mysql_select_db($database);

    $rsid = mysql_query($SQLQuery, $CONFIG['db_linkid']);
    if ($rsid == False) {
        dbReportError(mysql_errno(), mysql_error(), $SQLQuery);
    }
    if ($DBReturnID == True) {
        $DBReturnID = mysql_insert_id($CONFIG['db_linkid']);
    } else {
        $DBReturnID = True;
    }

    $DB_Count++;

    return($DBReturnID);
}

function dbReportError($ErrorNumber, $ErrorMsg, $SQLQuery) {

    error_log("database error: " . $ErrorNumber . "|" . $ErrorMsg);
    echo "database error: " . $ErrorNumber . "|" . $ErrorMsg . "<br><br>\n\n";
#	print "An error occured while connecting to the database<br>";
#	print "<strong>$ErrorNumber</strong>";
#	print $ErrorMsg;
    exit;
}

function displayLastMembers() {

    $memberArray = Array();

    $memSQL = dbRead("select tbl_kpi.* from tbl_kpi where UserID = '" . $_SESSION['User']['FieldID'] . "' and Type = '2' order by FieldID DESC limit 20", "etxint_log");
    while ($memObj = mysql_fetch_object($memSQL)) {

        $memberSQL = dbRead("select members.companyname from members where memid  = '" . $memObj->Memid . "'");
        $memberObj = mysql_fetch_object($memberSQL);

        if (!deep_in_array($memObj->Memid, $memberArray) && (sizeof($memberArray) < 5)) {

            $memberArray[] = array('memid' => $memObj->Memid, 'companyname' => $memberObj->companyname, 'date' => $memObj->Date);
        }
    }

    return $memberArray;
}

function deep_in_array($value, $array) {
    foreach ($array as $item) {
        if (!is_array($item)) {
            if ($item == $value)
                return true;
            else
                continue;
        }

        if (in_array($value, $item))
            return true;
        else if (deep_in_array($value, $item))
            return true;
    }
    return false;
}

function dbRecordTotal($rs) {
    return mysql_num_rows($rs);
}

function dbRecordAffected($rs) {
    return mysql_affected_rows($CONFIG[db_linkid]);
}

function dbFetchArray($rs) {
    return mysql_fetch_assoc($rs);
}

function dbString($string) {
    $string = addslashes(trim($string));
    return $string;
}

function getmicrotime() {
    list($msec, $sec) = explode(" ", microtime());
    return ((float) $sec + (float) $msec);
}

function check_access_level($page) {

    if (!checkmodule($page)) {
        ?>
        <table width="601" border="0" cellpadding="1" cellspacing="0">
            <tr>
                <td class="Border">
                    <table width="100%" border="0" cellpadding="3" cellspacing="0">
                        <tr>
                            <td width="100%" align="center" class="Heading2">You are not allowed to use this function.</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?
        die;
    }
}

function get_html_template($CID, $Name, $Content, $RorySend = false) {

    $CountryRow = mysql_fetch_assoc(dbRead("select * from country where countryID = '" . addslashes($CID) . "'"));
    $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '" . addslashes($CID) . "'"));
    $InterCountry = mysql_fetch_assoc(dbRead("select * from country where countryID = '1'"));

    $newadd = str_replace(", ", "<br>", $CountryRow['address1']);
    $newpostal = str_replace(", ", "<br>", $CountryRow['address2']);
    $newpostal = str_replace("1176","1198",$newpostal);
    if ($CountryRow['logo'] == "ept") {
        $web = "www.eplanettrade.com";
        $say = "Let the Businesses and Trade Unite";
    } elseif ($CountryRow['logo'] == "etx") {
        //$web = "www.empireXchange.com";
//   $web = "www.empiretrade.com.au";
        $web = "www.accesscommercial.exchange";
        $say = "Xchanging the way you do business";
    } else {
        $web = "www.ebanctrade.com";
        $say = "Wealth creation through business growth";
    }

    if ($RorySend) {
        $newdear = $Name;
    } else {
        $newdear = "" . $CountryDataRow['dear'] . " " . $Name . "";
    }

    if ($CID != 1) {
        if ($CountryRow['logo'] == "etx") {
            $int = '
		  				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">' . $CountryDataRow['tpl_intheadoffice'] . '</font></strong><br>
						<span style="color: #FFFFFF"></span> ' . $InterCountry['email'] . '<br>
						<span style="color: #FFFFFF">p</span> ' . $InterCountry['phone'] . '<br>
						<span style="color: #FFFFFF">f</span> ' . $InterCountry['fax'] . '</font><br>
          				<p><img src="https://admin.aceint.com.au/images/etx-vict-4-brw_11.png" width="168" height="16" alt=""><br></p>
  ';
        } else {
            $int = '
						<span style="font-size: 11px; font-family: Arial;"><b>' . $CountryDataRow['tpl_intheadoffice'] . '</b></span><br>
						<span style="color: #FFFFFF">e</span> ' . $InterCountry['email'] . '<br>
						<span style="color: #FFFFFF">p</span> ' . $InterCountry['phone'] . '<br>
						<span style="color: #FFFFFF">f</span> ' . $InterCountry['fax'] . '</span><br><br><br>
 ';
        }
    } else {
        $int = '';
    }

//if($CountryRow['countryID'] == 1) {
    //$ei = "logoTop2.gif";
//} else {
    //$ei = "logoTop.gif";
//}

    $ei = $CountryRow['logo'] . ".gif";

    if ($CountryRow['logo'] == "etx") {
        $html_template = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Empire Trade</title>

</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" height="20" border="0" cellpadding="0" cellspacing="0" bgcolor="#887C66">
  <tr>
    <td bgcolor="#f6f7f6"><img src="https://admin.aceint.com.au/images/pbe-vic-5_01.jpg" width="630" height="20" alt=""></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f6f7f6">
  <tr>
    <td width="195" rowspan="2" valign="top"><img src="https://admin.aceint.com.au/images/custom-logo.png" width="195" height="69" alt=""></td>
    <td height="40" valign="bottom"><div align="center"><strong><font color="#333" size="2" face="Verdana, Arial, Helvetica, sans-serif">' . $say . '</font></strong></div></td>
    <td width="625" rowspan="2" valign="top" >&nbsp;</td>
  </tr>
  <tr>
    <td width="414" height="27" valign="bottom">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="195" valign="top" bgcolor="#f6f7f6"><table width="195" border="0" cellpadding="0" cellspacing="0" bgcolor="#f6f7f6">
      <tr>
        <td valign="top"><img src="https://admin.aceint.com.au/images/etx-vict-4-brw_06.jpg" width="18" height="412" alt=""></td>
        <td valign="top" bgcolor="#f6f7f6">
          <p style="padding-top: 15px;"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">' . $CountryDataRow['tpl_headoffice'] . '</font></strong><br>
            ' . $newadd . '</font></p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="https://admin.aceint.com.au/images/etx-vict-4-brw_11.png" width="168" height="16" alt=""><br>
              <br>
            <strong><font size="2">' . $CountryDataRow['tpl_postal'] . '</font></strong><br>
            ' . $newpostal . '</font></p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="https://admin.aceint.com.au/images/etx-vict-4-brw_11.png" width="168" height="16" alt=""><br>
              <br>
              <strong><font size="2">' . $CountryDataRow['tpl_contacts'] . '</font></strong><br>
            ' . $CountryRow['email'] . ' <br>
            p ' . $CountryRow['phone'] . ' <br>
             </font></p>
          <p><img src="https://admin.aceint.com.au/images/etx-vict-4-brw_11.png" height="16" alt=""><br>
	  <br>
          <p>' . $int . '</p>
          <p>&nbsp; </p>
            
            </p>
          </td>
        <td valign="top"><img src="https://admin.aceint.com.au/images/pbe-vic-5-wh_08.jpg" width="9" height="412" alt=""></td>
      </tr>
    </table></td>
    <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td bgcolor="#FFFFFF"><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br><br>' . $newdear . '</font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">' . $Content . '<br><br></font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Empire Trade</font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            ' . $CountryDataRow['tpl_headoffice'] . '<br>
            Email : ' . $CountryRow['email'] . '<br>
            WEB : ' . $web . '</font></p>
          <hr size="1" noshade>
          </p>
          <font size="1" face="Verdana, Arial, Helvetica, sans-serif">' . $CountryDataRow['tpl_disclaimer'] . '</font></td>
      </tr>
    </table>      <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br>
    </font> </p>
    </td>
  </tr>
</table>

<p><!-- End ImageReady Slices -->
</p>
</body>
</html>
 ';
    } else {
        $html_template = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title>Empire Trade</title>
</head>
<LINK REL="stylesheet" type="text/css" href="http://www.ebanctrade.com/email/newEmail.css">
<style type="text/css">

.jobdesc { font-weight: normal; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 7pt;}

</style>

<body leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td style="vertical-align: top;" height="100%">
		<table cellpadding="0" cellspacing="0">
		  <tr>
			<td style="width: 100%; padding: 20px;" bgcolor="#FFFFFF"><img src="http://www.ebanctrade.com/email/images/CatchCry.png" width="125" height="36" border="0"></td>
		  </tr>
          <tr>
		   <td style="padding: 20px; font-family: Arial; color: #000000; font-size: 10pt">
           <table width="100%" border="0" cellspacing="0" cellpadding="10" hieght="100%">
             <tr>
              <td bgcolor="#FFFFFF" wrap style="padding: 20px; font-family: Arial; color: #000000; font-size: 10pt">
				  <p>' . $newdear . '</p>
			  	  <br>' . $Content . '
			  	  <p>Empire Trade</p>
                  <p><span class="jobdesc">
				  ' . $CountryDataRow['tpl_headoffice'] . '<br>
				  Email : ' . $CountryRow['email'] . '<br>
				  WEB : ' . $web . '
				  </span></p>
                  <p><span style="font-size: 7pt">' . $CountryDataRow['tpl_disclaimer'] . '</span></p>
                </td>
              </tr>
            </table>
			</td>
          </tr>
		</table>
		</td>
		<td class="bgImage">
			<table cellpadding="0" cellspacing="0" height="100%">
				<tr>
					<td style="width: 182px; height: 118px; vertical-align: top; background: #9ec03e;">
                    <img src="http://www.ebanctrade.com/email/images/' . $ei . '" width="182" height="118"></td>
				</tr>
				<tr>
					<td style="width: 182px; height: 100%; vertical-align: top;">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
								<td width="18">&nbsp;</td>
								<td style="color: #000066; padding-left: 10px; font-size: 11px; line-height: 100%; font-family: Arial; background: #9ec03e;">' . $web . '<br><br>
						<span style="font-size: 11px; font-family: Arial;"><b>' . $CountryDataRow['tpl_headoffice'] . '</b></span><br>
                  		<span style="font-style: italic;">' . $newadd . '</span><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b>' . $CountryDataRow['tpl_postal'] . '</b></span><br>
                      	<span style="font-style: italic;">' . $newpostal . '</span><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b>' . $CountryDataRow['tpl_contacts'] . '</b></span><br>
						<span style="font-style: italic;">
						<span style="color: #FFFFFF">e</span> ' . $CountryRow['email'] . '<br>
						<span style="color: #FFFFFF">p</span> ' . $CountryRow['phone'] . '<br>
						<br><br>
						' . $int . '
							</tr>
							<tr>
								<td height="100%">&nbsp;</td>
								<td style="background: #9ec03e; height: 100%;"><br><br></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style="background: #003E80; width: 20px; height: 100%; vertical-align: top;">&nbsp;</td>
	</tr>
</table>

</body>
</html>
';
    }

    return $html_template;
}

function credit_card_check($ccno, $ccdate) {

    if ($ccno == "4321432143214321") {
        return 0;
    }

    $expirearray = explode("/", $ccdate);
    $month = $expirearray[0];
    $year = $expirearray[1];

    // Get this year and this month and put them in variables

    $thisyear = date("y");
    $thismonth = date("m");

    // if the month of exp is passed,this year, card is expired
    // Remember that the card will never be a year in the past as the year list
    // has been created starting from $thisyear (see next)

    if ($month < $thismonth and $year <= $thisyear) {
        return 1;
    }

    $cclen = strlen($ccno);

    // Check card number lenght: must be 15 or 13 or 16

    if ($cclen != 13 and $cclen != 15 and $cclen != 16) {
        return 2;
    }

    // Check if the card has a pair or odd number of digits
    // the % give the rest of a division, like: 5 divided 2 -> give 1

    if ($cclen % 2 == 0) {

        $p = 0; // PAIR
    } else {

        $p = 2; // ODD
    }

    // Get each number of the card and put it in an array

    for ($a = 0; $a != $cclen; $a++) {

        $X[$a] = substr($ccno, $a, 1);
    }

    // Algorithm Mod10 modificated (by me :-) */
    for ($nume = ($p / 2); $nume != $cclen - $p; $nume = $nume + 2) {
        $X[$nume] = $X[$nume] * 2;
        if ($X[$nume] >= 10) {
            $X1 = substr($X[$nume], 0, 1);
            $X2 = substr($X[$nume], 1, 1);
            $X[$nume] = substr($X[$nume], 0, 1) + substr($X[$nume], 1, 1);
        }
    }

    // Sums each value of the modificated array
    // Check the result of the algorithm

    for ($i = 0; $i != $cclen; $i++) {

        $val = $val + $X[$i];
    }

    // Please note: if the second number of the result of algorithm is not 0
    // then the card is NOT valid

    if ($val == 0) {
        return 2;
    }
    if (substr($val, 1, 1) != 0) {
        return 2;
    }

    return 0;
}

$db_date = date("Y-m-d");

function add_referal($referer_memid, $member_memid) {

    global $db, $linkid, $db_date;

    // check to see if the referer has an entry in the agents table. if not then add one.
    $query = dbRead("select count(*) as AgentCount from `erewards_agents` where agent='$referer_memid'");
    $row = mysql_fetch_assoc($query);
    if ($row[AgentCount] == 0) {
        dbWrite("insert into `erewards_agents` (agent) values ('$referer_memid')");
    }

    // do the transfers.
    // first level.
    // easy cause we just put the money in the referers agent account.

    dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$referer_memid','$member_memid','1','0','40','10')");
    dbWrite("update `erewards_agents` set referals=referals+1 where agent='$referer_memid'");

    // second level.
    // we need to check if the first referer was refered. if he was then we need to put an entry in his reward account.

    $query2 = dbRead("select memid, referedby from members where memid='$referer_memid'");
    $row2 = mysql_fetch_assoc($query2);
    if ($row2[referedby]) {

        // this means that the first referer had someone refer him.
        // we need to add an entry for him to get his second level fees.

        dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row2[referedby]','$member_memid','1','0','5','5')");

        // third level.
        // now whilst in here we need to check to see if this referer had anyone refer him.

        $query3 = dbRead("select memid, referedby from members where memid='$row2[referedby]'");
        $row3 = mysql_fetch_assoc($query3);
        if ($row3[referedby]) {

            // this means that the second referer had someone refer him.
            // we need to add an entry for him aswell.

            dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row3[referedby]','$member_memid','1','0','10','10')");

            // forth level.
            // now whilst in here we need to check to see if this referer had anyone refer him.

            $query4 = dbRead("select memid, referedby from members where memid='$row3[referedby]'");
            $row4 = mysql_fetch_assoc($query4);
            if ($row4[referedby]) {

                // this means the third referer had someone refer him.
                // we need to add an entry for him.

                dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row4[referedby]','$member_memid','1','0','15','15')");

                // fifth and last level.
                // now whilst in here we need to check to see if this referer had anyone refer him.

                $query5 = dbRead("select memid, referedby from members where memid='$row4[referedby]'");
                $row5 = mysql_fetch_assoc($query5);
                if ($row5[referedby]) {

                    // this means the fourth referer had someone refer him.
                    // we need to add an entry for him.

                    dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row5[referedby]','$member_memid','1','0','20','20')");
                }
            }
        }
    }

    // if not then do nothing else.
}

function add_cash_fees($cash_memid, $cash_amount) {

    global $db, $linkid, $db_date;

    // we need to check to see if this persons referer has had more than 10 signups and if they even have a referer.

    $query = dbRead("select memid, referedby from members where memid='$cash_memid'");
    $row = mysql_fetch_assoc($query);
    if ($row[referedby]) {

        // this person has a referer. we need to check to see if they have more than 10 signups.
        // if not then we need to stop and not keep going through the loop.

        $query2 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row[referedby]'");
        $row2 = mysql_fetch_assoc($query2);
        if ($row2[referals] >= 10) {

            // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
            // first level - 50% of the agents percentage.

            $reward_total_amount = ($cash_amount / 100) * $row2[percent];
            $reward_net_amount = ($reward_total_amount / 100) * 50;
            $reward_actual_amount = $reward_net_amount / 2;

            dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row2[agent]','$cash_memid','2','50','$reward_actual_amount','$reward_actual_amount')");

            // now whilst we are in here we need to check to see if the current referer has a referer.
            // if there is then go on to check the referals.

            $query3 = dbRead("select memid, referedby from members where memid='$row[referedby]'");
            $row3 = mysql_fetch_assoc($query3);
            if ($row3[referedby]) {

                // this person has a referer. we need to check to see if they have more than 10 signups.
                // if not then we need to stop and not keep going through the loop.

                $query4 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row3[referedby]'");
                $row4 = mysql_fetch_assoc($query4);
                if ($row4[referals] >= 10) {

                    // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
                    // second level - 30% of the agents percentage.

                    $reward_total_amount = ($cash_amount / 100) * $row4[percent];
                    $reward_net_amount = ($reward_total_amount / 100) * 30;
                    $reward_actual_amount = $reward_net_amount / 2;

                    dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row4[agent]','$cash_memid','2','30','$reward_actual_amount','$reward_actual_amount')");

                    // now whilst we are in here we need to check to see if the current referer has a referer.
                    // if there is then go on to check the referals.

                    $query5 = dbRead("select memid, referedby from members where memid='$row3[referedby]'");
                    $row5 = mysql_fetch_assoc($query5);
                    if ($row5[referedby]) {

                        // this person has a referer. we need to check to see if they have more than 10 signups.
                        // if not then we need to stop and not keep going through the loop.

                        $query6 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row5[referedby]'");
                        $row6 = mysql_fetch_assoc($query6);
                        if ($row6[referals] >= 10) {

                            // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
                            // first level - 50% of the agents percentage.

                            $reward_total_amount = ($cash_amount / 100) * $row6[percent];
                            $reward_net_amount = ($reward_total_amount / 100) * 15;
                            $reward_actual_amount = $reward_net_amount / 2;

                            dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row6[agent]','$cash_memid','2','15','$reward_actual_amount','$reward_actual_amount')");

                            // now whilst we are in here we need to check to see if the current referer has a referer.
                            // if there is then go on to check the referals.

                            $query7 = dbRead("select memid, referedby from members where memid='$row5[referedby]'");
                            $row7 = mysql_fetch_assoc($query7);
                            if ($row3[referedby]) {

                                // this person has a referer. we need to check to see if they have more than 10 signups.
                                // if not then we need to stop and not keep going through the loop.

                                $query8 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row7[referedby]'");
                                $row8 = mysql_fetch_assoc($query8);
                                if ($row8[referals] >= 10) {

                                    // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
                                    // first level - 50% of the agents percentage.

                                    $reward_total_amount = ($cash_amount / 100) * $row8[percent];
                                    $reward_net_amount = ($reward_total_amount / 100) * 5;
                                    $reward_actual_amount = $reward_net_amount / 2;

                                    dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row8[agent]','$cash_memid','2','5','$reward_actual_amount','$reward_actual_amount')");

                                    // we are at the end of the run.
                                    // close it up.
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // if not do nothing else.
}

function add_transaction($memid_from, $memid_to, $date, $amount, $details) {

    global $db, $linkid;

    $t = mktime();
    $t2 = $t - 951500000;
    $t3 = mt_rand(1000, 9000);
    $authno = $t2 - $t3;

    $array1 = explode("-", $date);
    $day = $array1[2];
    $month = $array1[1];
    $year = $array1[0];
    $disdate = "$year-$month-$day";
    $epoch = mktime(0, 0, 1, $month, $day, $year);

    dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid_from','$epoch','$memid_to','$amount','0','0','0.00','1','" . encode_text2($details) . "','$authno','$disdate','0','180')");
    dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid_to','$epoch','$memid_from','0','$amount','0','0.00','2','" . encode_text2($details) . "','$authno','$disdate','0','180')");
}

function add_fees($memid_to, $date, $amount, $details) {

    global $db, $linkid;

    $t = mktime();
    $t2 = $t - 951500000;
    $t3 = mt_rand(1000, 9000);
    $authno = $t2 - $t3;

    $array1 = explode("-", $date);
    $day = $array1[2];
    $month = $array1[1];
    $year = $array1[0];
    $disdate = "$year-$month-$day";
    $epoch = mktime(0, 0, 1, $month, $day, $year);

    $userID = ($_SESSION['User']['FieldID']) ? $_SESSION['User']['FieldID'] : "180";

    dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid_to','$epoch','9845','0','0','0','-$amount','5','" . encode_text2($details) . "','$authno','$disdate','0','" . $userID . "')");
}

function Process_Credit_Card($merchantid, $amount, $ponum, $cc1, $cc2, $cc3, $cc4, $ex1, $ex2, $optional, $test = false) {

    $optional = str_replace(" ", "%20", $optional);
    $optional = str_replace("&", "and", $optional);

    if ($test) {
        $SecureServer[domain] = "test.securepay.com.au";
    } else {
        $SecureServer[domain] = "www.securepay.com.au";
    }
    $SecureServer[path] = "/securepay/payments/process2.asp";
    $SecureServer[port] = "443";
    $SecureServer[method] = "POST";
    $SecureServer[httpmethod] = "HTTP/1.1";

    $SecureServer[data] = "merchantid=$merchantid&amount=$amount&ponum=$ponum&creditCard1=$cc1&creditCard2=$cc2&creditCard3=$cc3&creditCard4=$cc4&exdate1=$ex1&exdate2=$ex2&optional_info=$optional&success_page=successfull%3D1%26ponum%3D&failure_page=successfull%3D2%26ponum%3D";
    $SecureServer[datalength] = strlen($SecureServer[data]);

    $SecureServer[request] = "$SecureServer[method] $SecureServer[path] $SecureServer[httpmethod]\r\n";
    $SecureServer[request] .= "Host: $SecureServer[domain]\r\n";
    $SecureServer[request] .= "Content-Length: $SecureServer[datalength]\r\n";
    $SecureServer[request] .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $SecureServer[request] .= "Connection: Close\r\n\r\n";
    $SecureServer[request] .= "$SecureServer[data]\r\n\r\n";

    //var_dump($SecureServer);
    // Establish the connection.

    $SecureSocket = fsockopen("ssl://" . $SecureServer[domain], $SecureServer[port], $errno, $errstr, 50);

    // Check to see if the connection is established. Else display error.

    if (!$SecureSocket) {

        echo "$errstr [$errno]";
    } else {

        // Main Body of Script.

        fputs($SecureSocket, $SecureServer[request]);

        while (!feof($SecureSocket)) {

            $Temp = explode(":", @fgets($SecureSocket, 1024));
            $SecureResponseTemp[$Temp[0]] = $Temp[1];
        }

        //var_dump($SecureResponseTemp);

        $Temp2 = explode("&", trim($SecureResponseTemp[Location]));

        foreach ($Temp2 as $value) {

            if ($test) {
                $Temp3 = explode("%3D", $value);
            } else {
                $Temp3 = explode("%3D", $value);
            }
            $SecureResponse[$Temp3[0]] = str_replace("+", " ", $Temp3[1]);
        }

        fclose($SecureSocket);
    }

    return $SecureResponse;
}

function add_temp_trans($memid, $amount) {

    $ponum = dbWrite("insert into credit_transactions (memid,amount,date,userid) values ('$memid','$amount',now(),'" . $_SESSION['User']['FieldID'] . "')", "etradebanc", 1);

    return $ponum;
}

function get_error($code) {

    // national and securepay error codes.

    switch ($code) {
        case "00": return "Transaction Approved.";
            break;
        case "01": return "Refer to Card Issuer.";
            break;
        case "04": return "Call Auth Centre.";
            break;
        case "08": return "Approved. Sign Receipt.";
            break;
        case "11": return "Transaction Approved.";
            break;
        case "12": return "Card Not Accepted.";
            break;
        case "31": return "See Card Issuer.";
            break;
        case "39": return "No CREDIT Account.";
            break;
        case "43": return "Stolen Card.";
            break;
        case "50";
            return "Test.";
            break;
        case "51": return "Insuffient Funds.";
            break;
        case "52": return "No CHEQUE Account.";
            break;
        case "53": return "No SAVINGS Account.";
            break;
        case "54": return "Card Expired.";
            break;
        case "55": return "Invalid PIN";
            break;
        case "60": return "Call Bank Help Desk. Pick Up Card.";
            break;
        case "61": return "Over Card Limit.";
            break;
        case "75": return "Too Many Atempts at PIN";
            break;
        case "76": return "Transaction Approved. Key Change Required.";
            break;
        case "77": return "Transaction Approved.";
            break;
        case "80": return "Transaction Approved. Key Change Required.";
            break;
        case "91": return "Issuer Not Available.";
            break;
        case "93": return "Already Settled.";
            break;
        case "94": return "STAN Out Of Sync.";
            break;
        case "96": return "System Malfunction.";
            break;
        case "97": return "Transaction Approved. Reconciliation Totals Reset.";
            break;
        case "98": return "NAC Error.";
            break;
        case "100": return "Invalid Transaction Amount.";
            break;
        case "101": return "Invalid Card Number.";
            break;
        case "102": return "Invalid Expiry Date Format.";
            break;
        case "103": return "Invalid Purchase Order.";
            break;
        case "104": return "Invalid Merchant ID.";
            break;
        case "106": return "Card Type Unsupported.";
            break;
        case "109": return "Invalid Credit Card CVV Number Format.";
            break;
        case "110": return "Unable To Connect To Server.";
            break;
        case "111": return "Server Connection Aborted During Transaction.";
            break;
        case "112": return "Transaction Timed Out By Client.";
            break;
        case "113": return "General Database Error.";
            break;
        case "114": return "Error Loading Properties File.";
            break;
        case "115": return "Fatal Unknown Server Error.";
            break;
        case "116": return "Function Unavailable Through Bank.";
            break;
        case "117": return "Message Format Error.";
            break;
        case "118": return "Unable to Decrypt Message.";
            break;
        case "119": return "Unable To Encrypt Message.";
            break;
        case "123": return "Gateway Timed Out.";
            break;
        case "124": return "Gateway Connection Aborted During Transaction.";
            break;
        case "125": return "Unknown Error Code.";
            break;
        case "126": return "Unable To Connect To Gateway.";
            break;
        case "127": return "Invalid Phone Number.";
            break;
        case "128": return "Invalid Client ID.";
            break;
        case "129": return "Invalid Transaction Type.";
            break;
        case "130": return "Invalid Frequency Type.";
            break;
        case "131": return "Invalid Number Format.";
            break;
        case "132": return "Invaild Date Format.";
            break;
        case "133": return "Transaction For Refund Not In Database.";
            break;
        case "134": return "This Transaction Fully Or Partly Refunded.";
            break;
        case "135": return "Transaction For Reversal Not In Database.";
            break;
        case "136": return "This Transaction Already Reversed.";
            break;
        case "137": return "Pre-Auth Transaction Not Found In Database.";
            break;
        case "138": return "This Pre-Auth Already Completed.";
            break;
        case "139": return "No Authorisation Code Supplied.";
            break;
        case "140": return "Partially Refunded, Do Refund To Complete.";
            break;
        case "141": return "No Transaction ID Supplied.";
            break;
        case "142": return "Pre-Auth Was Done For Smaller Amount.";
            break;
        case "900": return "Invalid Transaction Amount.";
            break;
        case "901": return "Invalid Credit Card Number.";
            break;
        case "902": return "Invalid Expiry Date Format.";
            break;
        case "903": return "Invalid Transaction Number.";
            break;
        case "904": return "Invalid Merchant/Terminal ID.";
            break;
        case "906": return "Card Unsupported.";
            break;
        case "907": return "Card Expired.";
            break;
        case "908": return "Insufficient Funds.";
            break;
        case "909": return "Credit Card Details Unknown.";
            break;
        case "910": return "Unable To Connect To Bank.";
            break;
        case "913": return "Unable To Update Database.";
            break;
        case "914": return "Power Failure.";
            break;
        case "915": return "Fatal Unknown Gateway Error.";
            break;
        case "916": return "Invalid Transaction Type Requested.";
            break;
        case "917": return "Invalid Message Format.";
            break;
        case "922": return "Bank Is Overloaded.";
            break;
        case "923": return "Bank Timed Out.";
            break;
        case "924": return "Transport Error.";
            break;
        case "925": return "Unknown Bank Response Code.";
            break;
        case "926": return "Gateway Busy.";
            break;
        case "928": return "Invalid Customer ID.";
            break;
        case "933": return "Transaction Not Found.";
            break;
        case "936": return "Transaction Already Reversed.";
            break;
        case "938": return "Pre-Auth Already Completed.";
            break;
        case "941": return "Invalid Transaction ID Supplied.";
            break;
        case "960": return "Contact Card Issuer.";
            break;
        case "970": return "File Access Error.";
            break;
        case "971": return "Invalid Flag Set.";
            break;
        case "972": return "PIN-PAD Offline.";
            break;
        case "973": return "Invoice Unavailable.";
            break;
        case "975": return "No Action Taken.";
            break;
        default: return "Unknown Error Response";
            break;
    }
}

function get_type($code) {

    switch ($code) {
        case "1";
            return "Fee Payment";
            break;
        case "2";
            return "MemberShip";
            break;
        case "3";
            return "E Rewards";
            break;
        case "4";
            return "Real Estate";
            break;
        case "5";
            return "Monthly Cash Fees";
            break;
        case "6";
            return "Conversion";
            break;
        case "7";
            return "Dave's Fun Stuff";
            break;
        case "8";
            return "E Foundation";
            break;
        case "9";
            return "myServices";
            break;
        case "12";
            return "Solutions";
            break;
        case "13";
            return "RE Rollover Fee";
            break;
        case "14";
            return "Warehouse";
            break;
        default;
            return "NEW";
            break;
    }
}

function TransactionError($Code) {

    switch ($Code) {
        case "1";
            return "A transaction already exists for that amount on this account in the last 30 days.<br>This could be a double up transaction";
            break;
        case "2";
            return "That Buyer Doesn't Exist";
            break;
        case "3";
            return "That Seller Doesn't Exist.";
            break;
        case "4";
            return "That Buyer doesn't have enough funds.";
            break;
        case "5";
            return "That Buyer is Suspended.";
            break;
        case "6";
            return "Unable to transfer funds as Buyers cash fees are outstanding for more 60 days.";
            break;
        case "7";
            return "That Buyer is Deactive.";
            break;
        case "8";
            return "That Seller is Deactive.";
            break;
        case "9";
            return "That Buyer is Contractor.";
            break;
        case "10";
            return "You are not allowed to do a Contract Only Transfer.";
            break;
        case "11";
            return "You are not allowed to do a Staff Transfer.";
            break;
        case "12";
            return "This Seller is not allowed to receive money from that Buyer.";
            break;
        case "13";
            return "Buyers daily $3000 per member limit has been exceeded.";
            break;
        case "14";
            return "Buyers weekly $5000 per member limit has been exceeded.";
            break;
        case "15";
            return "This is a Staff Account! Please Check.";
            break;
        case "16";
            return "That Seller is Suspended.";
            break;
        case "17";
            return "This is a Contract Only Account! Please Check.";
            break;
        case "18";
            return "This transaction has been declined.";
            break;
        case "19";
            return "Unable to Backdate Transactions.";
            break;
        case "20";
            return "Manual transaction with Facility Account NOT allowed.";
            break;
        case "30";
            return "International Transactions Not Allowed";
            break;
        default: return "Unknown Error";
            break;
    }
}

function get_fee_type($code) {

    $SQLRow = mysql_fetch_assoc(dbRead("select * from tbl_admin_trans_type where FieldID = '$code'"));
    return $SQLRow['name'];
}

function tbl_feespaid($amount, $memberacc, $licensee) {

    $authno = mt_rand(1000000, 99999999);
    $t = mktime();
    $d = date("Y-m-d");

    // erewards.
    add_cash_fees($memberacc, $amount);

    // if gold card insert trade the same as the value of the cash fees paid
    $dbcheckmember = dbRead("select goldcard from members where memid='$memberacc'");
    list($goldcard) = mysql_fetch_row($dbcheckmember);

    if ($goldcard == "1") {
        dbWrite("insert into transactions values('10655','$t','$memberacc','$amount','0','0','0','4','Goldcard Rewards','$authno','$d','0','','','" . $_SESSION['User']['FieldID'] . "')");
        dbWrite("insert into transactions values('$memberacc','$t','10655','0','$amount','0','0','4','Goldcard Rewards','$authno','$d','0','','','" . $_SESSION['User']['FieldID'] . "')");
    }

    // check to see if there are any stationery fees owed
    $dbcheckstationery = dbRead("select fee_deductions from members where memid='$memberacc'");
    $StationeryRow = @mysql_fetch_assoc($dbcheckstationery);

    $query4 = dbRead("select * from area where FieldID='$licensee'");
    $row4 = mysql_fetch_array($query4);


    #if they do take the number off the feesowing table to
    if ($StationeryRow['fee_deductions'] > 0) {

        if ($amount < $StationeryRow['fee_deductions']) {

            //take $amount off the fee_deductions and update members account because they didnt pay enouhg to take it to 0.
            $UpdateAmount = $StationeryRow['fee_deductions'] - $amount;
            dbWrite("update members set fee_deductions = " . $UpdateAmount . " where memid = " . $memberacc);
            $FeeAmount = $amount;
        } else {

            // make fee_deductions 0 for that member.
            dbWrite("update members set fee_deductions = 0 where memid = " . $memberacc);
            $FeeAmount = $StationeryRow['fee_deductions'];
        }

        if (!$trade) {
            dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type) values ('$memberacc','$d','$amount','$FeeAmount','" . $row4['feepercent'] . "','$licensee','1')");

            if ($dave) {
                $Fee_Amount = $amount - $StationeryRow['fee_deductions'];
                $queryinc = dbRead("select * from feesincurred where memid=" . $memberacc . " and fee_amount != fee_paid order by fieldid");
                while ($row5 = mysql_fetch_array($queryinc)) {

                    if ($Fee_Amount > 0) {
                        echo "aaa";
                        if ($Fee_Amount > ($row5['fee_amount'] - $row5['fee_paid'])) {
                            $Fee_Amount = $Fee_Amount - ($row5['fee_amount'] - $row5['fee_paid']);
                            dbWrite("update feesincurred set fee_paid = '" . $row5['fee_amount'] . "' where fieldid = '" . $row5['fieldid'] . "'");

                            $query9 = dbRead("select * from members where memid='" . $row5['to_memid'] . "'");
                            $row9 = mysql_fetch_array($query9);

                            dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type) values ('" . $row5['to_memid'] . "','$d','" . $row5['fee_amount'] . "','0','" . $row4['feepercent'] . "','" . $row9['FieldID'] . "','1')");
                            echo $row5['to_memid'] . $row5['fee_amount'] . $row4['feepercent'] . $row9['FieldID'];
                            echo "b";
                        } else {
                            $NewAmount = $row5['fee_paid'] - $Fee_Amount;
                            dbWrite("update feesincurred set fee_paid = '$NewAmount' where fieldid = '" . $row5['fieldid'] . "'");

                            $query9 = dbRead("select * from members where memid='" . $row5['to_memid'] . "'");
                            $row9 = mysql_fetch_array($query9);

                            dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type) values ('" . $row5['to_memid'] . "','$d','$NewAmount','0','" . $row4['feepercent'] . "','" . $row9['FieldID'] . "','1')");


                            $Fee_Amount = 0;
                            echo "c";
                        }
                    }
                }
            }
        }
    } else {

        if (!$trade) {
            dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type) values ('$memberacc','$d','$amount','0','" . $row4['feepercent'] . "','$licensee','1')");
        }
    }
}

function get_rates($convfrom, $convto, $amount) {

    global $currency_array;

    $CurrencyServer[domain] = "www.xe.net";

    $CurrencyServer[path] = "/ucc/convert/";
    $CurrencyServer[port] = "80";
    $CurrencyServer[method] = "POST";
    $CurrencyServer[httpmethod] = "HTTP/1.0";

    $CurrencyServer[data] = "Amount=$amount&From=$convfrom&To=$convto";
    $CurrencyServer[datalength] = strlen($CurrencyServer[data]);

    $CurrencyServer[request] = "$CurrencyServer[method] $CurrencyServer[path] $CurrencyServer[httpmethod]\r\n";
    $CurrencyServer[request] .= "Host: $CurrencyServer[domain]\r\n";
    $CurrencyServer[request] .= "Content-Length: $CurrencyServer[datalength]\r\n";
    $CurrencyServer[request] .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $CurrencyServer[request] .= "Connection: Close\r\n\r\n";
    $CurrencyServer[request] .= "$CurrencyServer[data]\r\n\r\n";

    // Establish the connection.

    $CurrencySocket = fsockopen($CurrencyServer[domain], $CurrencyServer[port], $errno, $errstr, 5);

    if (!$CurrencySocket) {

        echo "$errstr [$errno]";
    } else {

        // Main Body of Script.

        fputs($CurrencySocket, $CurrencyServer[request]);

        while (!feof($CurrencySocket)) {

            $Temp[] = fgets($CurrencySocket, 1024);
        }

        foreach ($Temp as $key => $value) {

            //if(substr_count($value, $convfrom) > 0 && substr_count($value, $convto) > 0 && substr_count($value, "XEsmall") > 0 && substr_count($value, "right") > 0) {
            if (substr_count($value, $convfrom) > 0 && substr_count($value, $convto) > 0 && substr_count($value, "right") > 0) {

                $value = trim(strip_tags($value));

                if (substr_count($value, "1&nbsp;" . $convfrom . "&nbsp;=") > 0) {

                    $Temp2 = explode("=", $value);
                    $Temp3 = explode(" ", str_replace(',', '', trim($Temp2[1])));
                    $Temp4 = explode("&nbsp;", $Temp3[0]);
                    $Converted_Amount['Amount'] = $amount * $Temp4[1];
                    $Converted_Amount['Rate'] = $Temp4[1];
                }
            }
        }

        fclose($CurrencySocket);
    }

    return $Converted_Amount;
}

function tabs($tabarray) {

    $count = sizeof($tabarray);

    if ($_GET[tab]) {
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="620">
            <tr>
                <td><img border="0" src="images/layout_arrow_right.gif" width="6" height="11">&nbsp;</td>
                <td width="100%">
                    <?
                    $foo = 1;

                    foreach ($tabarray as $tabkey => $tabvalue) {

                        if ($tabvalue == $_GET[tab]) {
                            ?>
                            &nbsp;<a class="nav" href="body.php?page=<?= $_REQUEST['page'] ?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=<?= $tabvalue ?>"><b><?= $tabvalue ?>&nbsp;</b></a><? if ($count != $foo) { ?> |<? } ?>
                            <?
                        } else {
                            ?>
                            &nbsp;<a class="nav" href="body.php?page=<?= $_REQUEST['page'] ?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=<?= $tabvalue ?>"><?= $tabvalue ?>&nbsp;</a><? if ($count != $foo) { ?> |<? } ?>
                            <?
                        }

                        $foo++;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2"><img border="0" src="images/layout_line.gif" width="100%" height="13"><br>&nbsp;</td>
            </tr>
        </table>
        <?
    }
}

function displaytabs($tabarray) {

    $count = sizeof($tabarray);

    if ($_REQUEST[tab]) {
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="620">
            <tr>
                <td><img border="0" src="images/layout_arrow_right.gif" width="6" height="11">&nbsp;</td>
                <td width="100%">
                    <?
                    $foo = 1;

                    foreach ($tabarray as $tabkey => $tabvalue) {

                        if ($_REQUEST[tab] == "tab" . $foo) {
                            ?>
                            &nbsp;<a class="nav" href="body.php?page=<?= $_REQUEST['page'] ?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab<?= $foo ?>"><b><?= $tabvalue ?>&nbsp;</b></a><? if ($count != $foo) { ?> |<? } ?>
                            <?
                        } else {
                            ?>
                            &nbsp;<a class="nav" href="body.php?page=<?= $_REQUEST['page'] ?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab<?= $foo ?>"><?= $tabvalue ?>&nbsp;</a><? if ($count != $foo) { ?> |<? } ?>
                            <?
                        }

                        $foo++;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2"><img border="0" src="images/layout_line.gif" width="100%" height="13"><br>&nbsp;</td>
            </tr>
        </table>
        <?
    }
}

function form_select($name, $query, $value, $key, $compare = false, $allowall = false, $custom = false, $size = 1, $bracket = false, $max = false) {

    $sql_query = $query;

    if ($allowall) {

        $output .= "<option value=\"\">$allowall</option>\n";
    }

    if (is_array($query)) {

        if ($max) {

            $ss = sizeof($query);
            $output .= "<option value=\"$ss\">$max</option>\n";
        }

        foreach ($query as $key2 => $value2) {

            $ibracket = ($bracket) ? " ($key2)" : "";

            if (strtolower($key2) == strtolower($compare)) {

                $output .= "<option selected value=\"$key2\">$value2$ibracket</option>\n";
            } else {

                $output .= "<option value=\"$key2\">$value2$ibracket</option>\n";
            }
        }
    } else {

        while ($row = mysql_fetch_assoc($sql_query)) {

            $ibracket = ($bracket) ? " ($row[$key])" : "";

            if (strtolower($row[$key]) == strtolower($compare)) {

                $output .= "<option selected value=\"$row[$key]\">$row[$value]$ibracket</option>\n";
            } else {

                $output .= "<option value=\"$row[$key]\">$row[$value]$ibracket</option>\n";
            }
        }
    }

    print "<select size=\"$size\" name=\"$name\"$custom>\n$output</select>";
}

function get_non_included_accounts($CID, $REOnly = false, $WithoutREFac = false, $WithoutRE = false, $WithoutInter = false) {

    $SQLQuery = dbRead("select * from country where countryID = '$CID'");
    $CRow = mysql_fetch_assoc($SQLQuery);

    if (!$WithoutRE) {

        $DATA[rereserve] = @explode(",", $CRow[rereserve]);
        $DATA[trustacc] = @explode(",", $CRow[trustacc]);
    }

    if (!$WithoutREFac) {

        $DATA[refacacc] = @explode(",", $CRow[refacacc]);
    }
    if (!$WithoutInter) {

        $DATA[interacc] = @explode(",", $CRow[interacc]);
    }

    if (!$REOnly) {

        $DATA[reserveacc] = @explode(",", $CRow[reserveacc]);
        $DATA[facacc] = @explode(",", $CRow[facacc]);
        $DATA[expense] = @explode(",", $CRow[expense]);
        $DATA[erewardsacc] = @explode(",", $CRow[erewardsacc]);
        $DATA[writeoff] = @explode(",", $CRow[writeoff]);
        $DATA[w_trust] = @explode(",", $CRow[w_trust]);
        $DATA[suspense] = @explode(",", $CRow[suspense]);
        $DATA[test] = @explode(",", $CRow[test]);
        $DATA[other] = @explode(",", $CRow[other]);
        $DATA[loan] = @explode(",", $CRow[loan]);
        $DATA[repay] = @explode(",", $CRow[repay]);
    }

    $Count = 0;

    foreach ($DATA as $DATA_Key => $DATA_Value) {

        foreach ($DATA_Value as $key => $value) {

            if ($Count == 0) {
                $AO = "";
            } else {
                $AO = ",";
            }

            $NI .= "$AO$value";

            $Count++;
        }
    }

    return $NI;
}

function auth_no() {

    $Check = true;

    while ($Check) {

        $t = mktime();
        $t2 = $t - 951500000;
        $t3 = mt_rand(1000, 9000);
        $authno = $t2 - $t3;

        $AuthSQL = dbRead("select count(id) as Count from transactions where authno = '" . $authno . "'");
        $AuthRow = @mysql_fetch_assoc($AuthSQL);

        if ($AuthRow['Count'] == 0) {

            $Check = false;
        }
    }

    return $authno;
}

function comma_seperate($Data) {

    $Count = 0;

    foreach ($Data as $Data_Value) {
        if ($Count == 0) {
            $AndOr = "";
        } else {
            $AndOr = ",";
        }

        if (is_numeric($Data_Value)) {
            $CommaValues .= "" . $AndOr . "" . $Data_Value . "";
        } else {
            $CommaValues .= "" . $AndOr . "'" . $Data_Value . "'";
        }
        $Count++;
    }

    return $CommaValues;
}

function GetFileSize($file_size) {

    if (!$file_size) {
        $file_size = 0;
    }
    if ($file_size >= 1099511627776) {
        $file_size = round($file_size / 1099511627776, 2) . "TB";
    } elseif ($file_size >= 1073741824) {
        $file_size = round($file_size / 1073741824, 2) . "GB";
    } elseif ($file_size >= 1048576) {
        $file_size = round($file_size / 1048576, 2) . "MB";
    } elseif ($file_size >= 1024) {
        $file_size = round($file_size / 1024, 2) . "KB";
    } else {
        $file_size = $file_size . " bytes";
    }

    return $file_size;
}

function send_to_browser($Data, $MimeType, $FileName, $Type) {

    $now = gmdate("D, d M Y H:i:s") . " GMT";

    header("Content-type: $MimeType");
    header("Content-Length: " . strlen($Data) . "");
    header("Expires: " . $now);
    header("Content-Disposition: attachment; filename=$FileName");
    header("Cache-Control: must-revalidate. post-check=0, pre-check=0");
    header("Pragma: public");

    print $Data;
}

/**
 * Functions.
 */
function display_area_header2($area_name, $tradeq, $pos, $font, $pdf) {

    global $fontitalic, $font;

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fontitalic, 12);
    pdf_set_text_pos($pdf, 12, $pos + 15);
    pdf_continue_text($pdf, $area_name . " (" . $tradeq . ")");

    pdf_setlinewidth($pdf, 0.5);
    pdf_moveto($pdf, 10, $pos);
    pdf_lineto($pdf, 585, $pos);
    pdf_stroke($pdf);

    $pos = $pos - 13;

    return $pos;
}

function display_entry2($row, $pos, $font2) {

    global $pdf, $font, $fontbold, $row1, $Country;

    // companyname.

    pdf_setlinewidth($pdf, 0.5);
    pdf_moveto($pdf, 20, $pos + 10);
    pdf_lineto($pdf, 585, $pos + 10);
    pdf_stroke($pdf);

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fontbold, 8);
    pdf_set_text_pos($pdf, 20, $pos + 10);
    pdf_continue_text($pdf, substr($row['companyname'], 0, 39));

    // contactname

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 210, $pos + 10);
    pdf_continue_text($pdf, $row['contactname']);

    // address

    $addr = '' . $row['streetno'] . ' ' . $row['streetname'] . ' ' . $row['suburb'] . ' ' . $row['city'] . ' ' . $row['state'] . ' ' . $row['postcode'] . '';
    $addres = trim($addr);
    $Stringwidth = pdf_stringwidth($pdf, $addres, $font, 8);
    $textheight = ((ceil($Stringwidth / 255)) * 8);

    if ($textheight == 8) {
        $text = 0;
        $noaddr = 7;
    } else {
        if (!$textheight) {
            $text = 0;
            $noaddr = 0;
        } else {
            $text = 8;
            $noaddr = 7;
        }
    }

    $paddr = '' . $row['postalno'] . ' ' . $row['postalname'] . ' ' . $row['postalsuburb'] . ' ' . $row['postalcity'] . ' ' . $row['postalstate'] . ' ' . $row['postalpostcode'] . '';
    $paddres = trim($paddr);
    $pStringwidth = pdf_stringwidth($pdf, $paddres, $font, 8);
    $ptextheight = ((ceil($pStringwidth / 255)) * 8);

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 330, $pos + 10);
    pdf_continue_text($pdf, "SA: " . $addres);
    pdf_continue_text($pdf, "PA: " . $paddres);

    // next line. take something from the pos

    $pos = $pos - $text;

    // message.
    //if($Country['english'] == 'Y' && $row1['english'] == 'N')  {
    // $desc=$row['engdesc'];
    //} else {
    //  $desc=$row['description'];
    //}

    $pos = $pos - 2;

    $CatSQL = dbRead("select mem_categories.*, categories.category as CatName from mem_categories, categories where (mem_categories.category = categories.catid) and memid = '" . $row['memid'] . "'");
    $CatCount = mysql_num_rows($CatSQL);
    $Counter = 1;
    while ($CatRow = mysql_fetch_assoc($CatSQL)) {

        pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
        pdf_setfont($pdf, $fontbold, 7);
        pdf_set_text_pos($pdf, 30, $pos);
        pdf_continue_text($pdf, $CatRow['CatName']);

        $pos = $pos - 8;


        $textheight = 0;

        $pos = check_for_next_page2($CatRow, $pos, 'description', $pdf, $font);

        $NewDesc = explode("|", wordwrap($CatRow['description'], 145, "|"));
        pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
        pdf_setfont($pdf, $font, 7);
        pdf_set_text_pos($pdf, 50, $pos);
        foreach ($NewDesc as $Line) {
            pdf_continue_text($pdf, $Line);
            $textheight += 7;
        }

        if ($Counter != $CatCount) {
            $pos = $pos - $textheight - 2;
        } else {
            $pos = $pos - 8;
        }

        if ($CatRow['engdesc']) {

            $pos = $pos - $textheight + 7;

            pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
            pdf_setfont($pdf, $font2, 7);
            pdf_set_text_pos($pdf, 50, $pos);

            $pos = check_for_next_page($CatRow, $pos, 'description');

            $NewDesc = explode("|", wordwrap($CatRow['engdesc'] . "T", 145, "|"));
            foreach ($NewDesc as $Line) {
                pdf_continue_text($pdf, $Line);
                $textheight += 7;
            }
        }

        $Counter++;
    }

    $pos = $pos - 2;

    // next line. take something from the pos

    $pos = $pos - ($textheight - $noaddr) - 10;

    // fax/tel/email

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fontbold, 8);
    pdf_set_text_pos($pdf, 95, $pos + 10);
    pdf_continue_text($pdf, "Tel: ");

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fontbold, 8);
    pdf_set_text_pos($pdf, 210, $pos + 10);
    pdf_continue_text($pdf, "Fax: ");

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fontbold, 8);
    pdf_set_text_pos($pdf, 330, $pos + 10);
    pdf_continue_text($pdf, "Email: ");

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 115, $pos + 10);
    pdf_continue_text($pdf, "" . $row['phonearea'] . " " . $row['phoneno'] . "");

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 235, $pos + 10);
    pdf_continue_text($pdf, "" . $row['faxarea'] . " " . $row['faxno'] . "");

    //if($row[opt] == 'Y')  {
    $email = $row['emailaddress'];
    //} elseif($row['opt'] == 'N') {
    //  $email="";
    //}

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 360, $pos + 10);
    pdf_continue_text($pdf, $email);

    // return

    $pos = ($pos - 12);

    return $pos;
}

function check_for_next_page2($row, $pos, $type, $pdf, $font) {

    global $page, $pos_baseref, $fontitalic, $displaydate, $row1;

    //check some heights.

    if ($type == "member") {

        $mess_len = pdf_stringwidth($pdf, $row['description'], $font, 6);
        $message_height = ((ceil($mess_len / 535)) * 6);

        $total_height = $message_height + 20;

        $new_pos = $pos - $total_height;
    } elseif ($type == "description") {

        $mess_len = pdf_stringwidth($pdf, $row['description'], $font, 6);
        $message_height = ((ceil($mess_len / 535)) * 6);
        $total_height = $message_height + 20;
        $new_pos = $pos - 24 - $total_height;
    } elseif ($type == "area") {

        $mess_len = pdf_stringwidth($pdf, $row['place'], $font, 12);
        $message_height = ((ceil($mess_len / 535)) * 6);
        $total_height = $message_height + 20;
        $new_pos = $pos - $total_height - 12;
    }

    if ($new_pos < $pos_baseref) {

        pdf_setlinewidth($pdf, 0.5);
        pdf_moveto($pdf, 10, 34);
        pdf_lineto($pdf, 585, 34);
        pdf_stroke($pdf);

        pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
        pdf_setfont($pdf, $font, 8);
        pdf_set_text_pos($pdf, 550, 20);
        pdf_continue_text($pdf, "Page $page");

        pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
        pdf_setfont($pdf, $font, 8);
        pdf_set_text_pos($pdf, 15, 20);
        pdf_continue_text($pdf, $displaydate);

        $page = $page + 1;

        pdf_end_page($pdf);
        pdf_begin_page($pdf, 595, 842);
        $pos = 810;

        return $pos;
    } else {

        return $pos;
    }
}

function form_radio_yesno($name, $yesno, $custom = false) {

    if ($yesno == "Y" || $yesno == 1 || $yesno == "Buy") {

        $output .= "<input checked type=\"radio\" name=\"$name\" value=\"Y\"$custom size=\"20\"> Y
<input type=\"radio\" name=\"$name\" value=\"N\"$custom size=\"20\"> N";
    } else {

        $output .= "<input type=\"radio\" name=\"$name\" value=\"Y\"$custom size=\"20\"> Y
<input checked type=\"radio\" name=\"$name\" value=\"N\"$custom size=\"20\"> N";
    }

    print $output;
}

function erewards_text($row) {

    switch ($row[erewards]) {

        case "1": return "Pending";
            break;
        case "2": return "Pending";
            break;
        case "9": return "Yes";
            break;
        default: return "No";
            break;
    }
}

function get_year_array($late = false) {

    $start_year = 2000;

    if ($late) {
        $date = date("Y") + 1;
    } else {
        $date = date("Y");
    }

    while ($date >= $start_year) {

        $YearArray[$start_year] = $start_year;

        $start_year++;
    }

    return $YearArray;
}

function get_month_array() {

    $num_months = period_diff(strtotime("1st Feb 2000"), mktime()) + 1;

    $foo = 1;

    while ($foo != $num_months) {

        $MonthArray[$foo] = $foo;

        $foo++;
    }

    return $MonthArray;
}

function period_diff($in_dateLow, $in_dateHigh) {
    // swap dates if they are backwards
    if ($in_dateLow > $in_dateHigh) {
        $tmp = $in_dateLow;
        $in_dateLow = $in_dateHigh;
        $in_dateHigh = $tmp;
    }

    $dateLow = $in_dateLow;
    $dateHigh = strftime('%m/%Y', $in_dateHigh);

    $periodDiff = 0;
    while (strftime('%m/%Y', $dateLow) != $dateHigh) {
        $periodDiff++;
        $dateLow = strtotime('+1 month', $dateLow);
    }

    return $periodDiff;
}

function encode_text($str) {

    return unicode_to_entities(utf8_to_unicode($str));
}

function encode_text2($str) {

    return unicode_to_utf8(utf8_to_unicode($str));
}

function decode_text($str) {

    return unicode_to_utf8(entities_to_unicode($str));
    //return entities_to_unicode($str);
    //return html_entity_decode($str);
}

function unicode_to_entities($unicode) {

    $entities = '';
    foreach ($unicode as $value) {

        $entities .= ( $value > 127 ) ? '&#' . $value . ';' : chr($value);
    } //foreach
    return $entities;
}

// unicode_to_entities

function unicode_to_utf8($str) {
    $utf8 = '';
    foreach ($str as $unicode) {
        if ($unicode < 128) {
            $utf8 .= chr($unicode);
        } elseif ($unicode < 2048) {
            $utf8 .= chr(192 + ( ( $unicode - ( $unicode % 64 ) ) / 64 ));
            $utf8 .= chr(128 + ( $unicode % 64 ));
        } else {
            $utf8 .= chr(224 + ( ( $unicode - ( $unicode % 4096 ) ) / 4096 ));
            $utf8 .= chr(128 + ( ( ( $unicode % 4096 ) - ( $unicode % 64 ) ) / 64 ));
            $utf8 .= chr(128 + ( $unicode % 64 ));
        } // if
    } // foreach
    return $utf8;
}

// unicode_to_utf8

function utf8_to_unicode($str) {

    $unicode = array();
    $values = array();
    $lookingFor = 1;

    for ($i = 0; $i < strlen($str); $i++) {
        $thisValue = ord($str[$i]);
        if ($thisValue < 128)
            $unicode[] = $thisValue;
        else {
            if (count($values) == 0)
                $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
            $values[] = $thisValue;
            if (count($values) == $lookingFor) {
                $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
                        ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );
                $unicode[] = $number;
                $values = array();
                $lookingFor = 1;
            } // if
        } // if
    } // for
    return $unicode;
}

// utf8_to_unicode

function entities_to_unicode($str) {

    $unicode = array();
    $inEntity = FALSE;
    $entity = '';

    for ($i = 0; $i < strlen($str); $i++) {
        if ($inEntity) {
            if ($str[$i] == ';') {
                $unicode[] = (int) $entity;
                $entity = '';
                $inEntity = FALSE;
            } elseif ($str[$i] != '#') {
                $entity .= $str[$i];
            } // if
        } else {
            if (( $str[$i] == '&' ) && ( $str[$i + 1] == '#' ))
                $inEntity = TRUE;
            else
                $unicode[] = ord($str[$i]);
        } // if
    } // for

    return $unicode;
}

// entitiexs_to_unicode

function get_left_pos($text, $pdf, $centerpos, $size = false, $font = false) {

    $StartPos = $centerpos - (pdf_stringwidth($pdf, $text, $font, $size) / 2);

    return $StartPos;
}

function get_right_pos($text, $pdf, $rightpos, $size = false, $font = false) {

    $StartPos = $rightpos - pdf_stringwidth($pdf, $text, $font, $size);

    return $StartPos;
}

function which_charset($Page) {

    switch ($Page) {

        case "contacts": return "utf-8";
            break;
        case "member_edit": return "utf-8";
            break;
        case "mem_search": return "utf-8";
            break;
        default: return "utf-8";
            break;
    }
}

function UpdatePastCompanyname($Memid, $Companyname, $OLDCompanyname = false) {

    $CheckOldCompany = dbRead("select tbl_members_companyinfo.* from tbl_members_companyinfo where memid = " . $Memid . " order by DateFrom Desc limit 1");
    $CheckOldCompanyRow = @mysql_fetch_assoc($CheckOldCompany);

    if ($CheckOldCompanyRow) {

        /**
         * Update Last CompanyName Record DateTO to todays date.
         * Insert an additional record DateFrom Todays Date with the new company name
         */
        if ($CheckOldCompanyRow['DateFrom'] == date("Y-m-d")) {

            dbWrite("update tbl_members_companyinfo set Companyname = '" . $Companyname . "' where FieldID = " . $CheckOldCompanyRow['FieldID']);
        } else {

            dbWrite("insert into tbl_members_companyinfo (memid,Companyname,DateFrom) values ('" . $Memid . "','" . $Companyname . "','" . date("Y-m-d") . "')");
            dbWrite("update tbl_members_companyinfo set DateTo = '" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))) . "' where FieldID = " . $CheckOldCompanyRow['FieldID']);
        }
    } else {

        /**
         * Insert new Record with Old Company Name with Todays Date in Date From.
         */
        dbWrite("insert into tbl_members_companyinfo (memid,Companyname,DateFrom,DateTo) values ('" . $Memid . "','" . $OLDCompanyname . "','2000-01-01','" . date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))) . "')");
        dbWrite("insert into tbl_members_companyinfo (memid,Companyname,DateFrom) values ('" . $Memid . "','" . $Companyname . "','" . date("Y-m-d") . "')");
    }
}

function get_areas_allowed($Report = false) {

    $Areas = ($Report) ? $_SESSION['User']['ReportsAllowed'] : $_SESSION['User']['AreasAllowed'];

    if ($Areas == "all") {

        $SQLQuery = dbRead("select * from area where CID = '" . $_SESSION['User']['CID'] . "'");
        while ($Row = mysql_fetch_assoc($SQLQuery)) {

            $TempArea .= $Row['FieldID'] . ",";
        }

        return substr($TempArea, 0, strlen($TempArea) - 1);
    } else {

        return trim($Areas);
    }
}

function get_physical_area($area) {


    $SQLQuery = dbRead("select * from area where FieldID in ('" . $area . "')");
    while ($Row = mysql_fetch_assoc($SQLQuery)) {

        $TempArea .= $Row['PhysicalID'] . ",";
    }

    return substr($TempArea, 0, strlen($TempArea) - 1);
}

function validate_email($email) {
    $email = trim($email); # removes whitespace
    if (!empty($email)):
        //  validate email address syntax
//    if( preg_match('/^[a-z0-9_\.]+@[a-z0-9\\-]+\.[a-z]+\.?[a-z]{2,4}$/i', $email, $match) ):
        if (preg_match('/[a-z0-9_\.-]+@[a-z0-9_-]+\.[a-z0-9_-]+/i', $email, $match)):
            return strtolower($match[0]);
        endif;
    endif;
    return false;
}

function check_area_access($memrow) {

    $SQLQuery = dbRead("select * from status where FieldID = " . $memrow['status']);
    $Row = mysql_fetch_assoc($SQLQuery);

    if (!checkmodule("SuperUser")) {

        $allowed = false;

        if ($memrow['status'] != 1 || $memrow['status'] != 5 || $memrow['status'] != 6) {
            if (checkmodule("EditMemberLevel2")) {
                if ($_SESSION['User']['CID'] == $memrow['CID']) {

                    $allowed = true;
                }
            }
        } else {
            $allowed = false;
        }

        $adminuserarray = explode(",", $_SESSION['User']['AreasAllowed']);

        $count = sizeof($adminuserarray);
        $i = 0;
        for ($i = 0; $i <= $count; $i++) {

            if (($adminuserarray[$i] == $memrow['licensee']) || (($adminuserarray[$i] == "all") && ($_SESSION['User']['CID'] == $memrow['CID']))) {
                if ($memrow['status'] != 1 || $memrow['status'] != 5 || $memrow['status'] != 6) {
                    $allowed = true;
                    break;
                }
            }
        }

        if ($_REQUEST['tab'] == "tab7" && !checkmodule("Contractor")) {
            if ($memrow['status'] == 1 || $memrow['status'] == 6) {
                return false;
            }
        }

        if ($_REQUEST['tab'] == "tab7" && !checkmodule("Staff")) {
            if ($memrow['status'] == 3) {
                return false;
            }
        }

        if ($_REQUEST['tab'] == "tab7" && !checkmodule("Contractor")) {
            if ($memrow['status'] == 2) {
                return false;
            }
        }

        if ($_REQUEST['tab'] == "tab10" && !checkmodule("EditMemberLevel2")) {
            if ($memrow['status'] == 1 || $memrow['status'] == 6) {
                return false;
            }
        }

        if ($_REQUEST['tab'] == "tab8" && !checkmodule("EditMemberLevel2")) {
            if ($memrow['status'] == 6 && $memrow['letters'] < 1) {
                return false;
            }
        }

        if ($_REQUEST['tab'] == "tab8" && $memrow['status'] == 1) {
            if (checkmodule("Deactivated")) {
                return true;
            } else {
                return false;
            }
            //if(!checkmodule("Deactivated")) {
            //return false;
            //}
        }

        if ($_REQUEST['tab'] == "tab11" && !checkmodule("EditMemberLevel2")) {
            if ($memrow['status'] == 6 && $memrow['letters'] < 1) {
                return false;
            }
        }

        if ($_REQUEST['tab'] == "tab11" && $memrow['status'] == 1) {
            if (!checkmodule("Deactivated")) {
                return false;
            }
        }

        //if(checkmodule("Deactivated")) {
        //$allowed = true;
        //}

        if ($allowed == false) {

            return false;
        } else {

            return true;
        }
    } else {

        return true;
    }
}

function do_date($Name, $Check = false, $DOB = false, $L2Check = false) {

    if ($Check) {
        $CheckDate = explode("-", $Check);
    }
    ?>

    <select name="<?= $Name ?>_Day"<?
    if ($L2Check) {
        print check_disabled();
    }
    ?>
            ><?= generate_options("1", "31", $CheckDate['2']); ?>
    </select>&nbsp;&nbsp;
    <select name="<?= $Name ?>_Month"<?
    if ($L2Check) {
        print check_disabled();
    }
    ?>

            ><?= generate_options("1", "12", $CheckDate['1']); ?>
    </select>&nbsp;&nbsp;
    <select name="<?= $Name ?>_Year"<?
    if ($L2Check) {
        print check_disabled();
    }
    ?>

            ><?
                if ($DOB) {
                    print generate_options("1900", date("Y"), $CheckDate['0']);
                } else {
                    print generate_options("2000", date("Y") + 1, $CheckDate['0']);
                }
                ?>
    </select>&nbsp;&nbsp;

    <?
}

function generate_options($From, $To, $Check) {

    $Result .= '<option value="0">0</option>';

    for ($i = $From; $i <= $To; $i++) {

        $Checked = ($i == $Check) ? " selected" : "";

        $Result .= '<option value="' . $i . '"' . $Checked . '>' . $i . '</option>';
    }

    return $Result;
}

function addmessage($users, $message, $sender) {

    if (!$sender) {
        $sender = "180";
    }

    foreach ($users as $key => $value) {

        dbWrite("insert into message_system (Date_Entered,Sender,Receiver,Importance,Message) values (now(),'" . $sender . "','" . $value . "','1','" . addslashes(encode_text2($message)) . "')", "etxint_ebanc_message");
    }
}

function addresslayoutflat($CID, $type) {

    if ($type == 1) {

        switch ($CID) {

            case "12":
                $layout[] = array('postcode', 'city', 'suburb', 'streetname', 'streetno');
                break;

            case "15":
                $layout[] = array('streetname', 'streetno', 'suburb', 'city', 'state', 'postcode');
                break;

            default:
                $layout[] = array('streetno', 'streetname', 'suburb', 'city', 'state', 'postcode');
                break;
        }
    } else {

        switch ($CID) {

            case "12":
                $layout[] = array('postalcity', 'postalsuburb', 'postalname', 'postalno', 'postalpostcode');
                break;

            case "15":
                $layout[] = array('postalname', 'postalno', 'postalsuburb', 'postalcity', 'postalstate', 'postalpostcode');
                break;

            default:
                $layout[] = array('postalno', 'postalname', 'postalsuburb', 'postalcity', 'postalstate', 'postalpostcode');
                break;
        }
    }
    return $layout;
}

function addresslayout($CID) {

    switch ($CID) {

        case "12":
            $layout[] = array('accholder');
            $layout[] = array('regname');
            $layout[] = array('postalcity');
            $layout[] = array('postalname', 'postalno');
            $layout[] = array('postalpostcode');

            break;

        case "15":
            $layout[] = array('regname');
            $layout[] = array('companyname');
            $layout[] = array('postalname', 'postalno');
            $layout[] = array('postalsuburb');
            $layout[] = array('postalcity', 'postalstate', 'postalpostcode');

            break;

        default:
            $layout[] = array('accholder');
            $layout[] = array('companyname');
            $layout[] = array('postalno', 'postalname');
            $layout[] = array('postalsuburb');
            $layout[] = array('postalcity', 'postalstate', 'postalpostcode');
            break;
    }

    return $layout;
}

function addresslayoutdirectory($CID) {

    switch ($CID) {

        case "12":
            $layout[] = array('city');
            $layout[] = array('streetname', 'streetno', 'postcode');

            break;

        case "15":
            $layout[] = array('streetname', 'streetno', 'suburb');
            $layout[] = array('city', 'state', 'postcode');

            break;

        default:
            $layout[] = array('streetno', 'streetname', 'suburb');
            $layout[] = array('city', 'state', 'postcode');
            break;
    }

    return $layout;
}

function letterhead($services = false) {

    //global $pdf, $font, $row, $pdfimage, $pdfsig;
    global $pdf, $font;

    $dbquery = dbRead("select * from countrydata where CID = '" . $_SESSION['User']['CID'] . "'");
    $rowcountry = mysql_fetch_assoc($dbquery);

    $fonti = pdf_findfont($pdf, "ArialItalic", "winansi", 0);
    $fontib = pdf_findfont($pdf, "ArialBoldItalic", "winansi", 0);

    if ($_SESSION['Country']['logo'] == "ept") {
        $web = "www.eplanettrade.com";
        //$say = "Let the Businesses and Trade Unite";
        $say = $rowcountry['let_top'];
    } elseif ($services) {
        $web = "";
        $say = "";
    } elseif ($_SESSION['Country']['logo'] == "etx") {
        //$web = "www.empireXchange.com";
        $web = "www.empiretrade.com.au";
        $say = "";
    } else {
        $web = "www.ebanctrade.com";
        //$say = "Wealth creation through business growth";
        $say = $rowcountry['let_top'];
    }

    //put image up the top.
    if ($services) {
        //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/myServices.jpg");
        //pdf_place_image($pdf, $pdfimage, 460, 775, 1);
        $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/myServices.jpg", '');
        pdf_fit_image($pdf, $pdfimage, 460, 775, "scale 1");
    } elseif ($_SESSION['Country']['logo'] == "etx") {
        //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."2-bw.jpg");
        //pdf_place_image($pdf, $pdfimage, 480, 775, .2);
        $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/" . $_SESSION['Country']['logo'] . "2-bw.jpg", '');
        pdf_fit_image($pdf, $pdfimage, 480, 775, "scale 0.2");
    } else {
        //$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/".$_SESSION['Country']['logo']."-bw.jpg");
        //pdf_place_image($pdf, $pdfimage, 460, 775, 1);
        $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/" . $_SESSION['Country']['logo'] . "-bw.jpg", '');
        pdf_fit_image($pdf, $pdfimage, 460, 775, "scale 1");
    }

    //pdf_place_image($pdf, $pdfimage, 460, 775, 1);

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fonti, 9);
    pdf_set_text_pos($pdf, 65, 829);
    pdf_continue_text($pdf, $say);

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $fonti, 7);

    pdf_set_text_pos($pdf, 480, 766);
    pdf_continue_text($pdf, $web);

    $query = dbRead("select * from area where FieldID = " . $_SESSION['User']['Area'] . "");
    $row = mysql_fetch_assoc($query);

    if ($services) {
        $head = "My Services Banc";
    } else {
        if ($_SESSION['User']['Area'] == 1) {
            //$head = "INTL HEAD OFFICE";
            $head = $rowcountry['let_inter'];
        } elseif ($_SESSION['User']['Area'] == $_SESSION['Country']['DefaultArea']) {
            //$head = "HEAD OFFICE";
            $head = $rowcountry['let_head'];
        } else {
            $head = strtoupper($row['place']);
        }
    }
    pdf_setfont($pdf, $fontib, 7);
    pdf_set_text_pos($pdf, 480, 752);
    pdf_continue_text($pdf, $head);

    pdf_setfont($pdf, $fonti, 7);
    pdf_set_text_pos($pdf, 480, 745);
    $text = explode(",", $row['r_address']);

    foreach ($text as $Line) {
        pdf_continue_text($pdf, trim($Line));
        $textheight += 7;
    }

    if ($row['p_address'] != $row['r_address']) {
        pdf_setfont($pdf, $fontib, 7);
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        //pdf_continue_text($pdf, "POSTAL ADDRESS");
        pdf_continue_text($pdf, $rowcountry['let_postal']);

        pdf_setfont($pdf, $fonti, 7);
        $text = explode(",", $row['p_address']);
        foreach ($text as $Line) {
            pdf_continue_text($pdf, trim($Line));
            $textheight += 7;
        }
    }

    if ($services) {
        $ee = "info@myservicesbanc.com";
        $ab = "119 388 662";
    } else {
        //$ee = $_SESSION['Country']['email'];
        $ee = $row['email'];
        $ab = $_SESSION['Country']['abn'];
    }

    pdf_setfont($pdf, $fontib, 7);
    pdf_continue_text($pdf, "");
    pdf_continue_text($pdf, "");
    //pdf_continue_text($pdf, "OFFICE CONTACTS");
    pdf_continue_text($pdf, $rowcountry['let_office']);
    pdf_setfont($pdf, $fonti, 7);
    pdf_continue_text($pdf, "email: " . $ee);
    //pdf_continue_text($pdf, "tel: ".$_SESSION['Country']['phone']);
    //pdf_continue_text($pdf, "fax: ".$_SESSION['Country']['fax']);
    pdf_continue_text($pdf, "tel: " . $row['phone']);
    pdf_continue_text($pdf, "fax: " . $row['fax']);

    if ($_SESSION['User']['Area'] == $_SESSION['Country']['DefaultArea']) {
        $gno = explode(",", $ab, 2);

        pdf_continue_text($pdf, $gno[0]);
        pdf_continue_text($pdf, trim($gno[1]));
    }

    if ($_SESSION['User']['Area'] != $_SESSION['Country']['DefaultArea']) {

        pdf_setcolor($pdf, "fill", "rgb", 0.2, 0.2, 0.2, 0.2);
        pdf_setfont($pdf, $fontib, 7);
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        //pdf_continue_text($pdf, "HEAD OFFICE");
        pdf_continue_text($pdf, $rowcountry['let_head']);

        pdf_setfont($pdf, $fonti, 7);
        $text = explode(",", $_SESSION['Country']['address2']);
        foreach ($text as $Line) {
            pdf_continue_text($pdf, trim($Line));
            $textheight += 7;
        }

        pdf_setfont($pdf, $fontib, 7);
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        //pdf_continue_text($pdf, "OFFICE CONTACTS");
        pdf_continue_text($pdf, $rowcountry['let_office']);
        pdf_setfont($pdf, $fonti, 7);
        pdf_continue_text($pdf, "email: " . $_SESSION['Country']['email']);
        pdf_continue_text($pdf, "tel: " . $_SESSION['Country']['phone']);
        pdf_continue_text($pdf, "fax: " . $_SESSION['Country']['fax']);

        $gno = explode(",", $ab, 2);

        pdf_continue_text($pdf, $gno[0]);
        pdf_continue_text($pdf, trim($gno[1]));
    }

    if ($_SESSION['User']['CID'] != 1) {

        $sqlquery = dbRead("select * from country where countryID = 1");
        $sqlrow = mysql_Fetch_assoc($sqlquery);

        pdf_setcolor($pdf, "fill", "rgb", 0.4, 0.4, 0.4, 0.4);
        pdf_setfont($pdf, $fontib, 7);
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        //pdf_continue_text($pdf, "INTL HEAD OFFICE");
        pdf_continue_text($pdf, $head = $rowcountry['let_inter']);
        pdf_setfont($pdf, $fonti, 7);
        $text = explode(",", $sqlrow['address2']);
        foreach ($text as $Line) {
            pdf_continue_text($pdf, trim($Line));
            $textheight += 7;
        }
        pdf_continue_text($pdf, "Australia");

        pdf_setfont($pdf, $fontib, 7);
        pdf_continue_text($pdf, "");
        pdf_continue_text($pdf, "");
        //pdf_continue_text($pdf, "OFFICE CONTACTS");
        pdf_continue_text($pdf, $rowcountry['let_office']);
        pdf_setfont($pdf, $fonti, 7);
        pdf_continue_text($pdf, "email: " . $sqlrow['email']);
        pdf_continue_text($pdf, "tel: " . $sqlrow['phone']);
        pdf_continue_text($pdf, "fax: " . $sqlrow['fax']);
    }
}

function tradeowing($memid) {

    $query = dbRead("select sum(sell-buy) as balance, overdraft, reoverdraft from members, transactions where (members.memid = transactions.memid) and members.memid = '$memid' group by transactions.memid");
    $row = mysql_fetch_array($query);

    $balance = ($row[balance] - $row[overdraft] - $row[reoverdraft]);
    if ($balance < 0) {
        $balance2 = abs($balance);
        return "" . $_SESSION['Country']['currency'] . "" . number_format($balance2, 2) . "";
    } else {
        return "0.00";
    }
}

function tfacility($memid) {

    $query = dbRead("select * from members where memid = '$memid'");
    $row = mysql_fetch_array($query);

    if (($row[overdraft] + $row[reoverdraft]) > 0) {
        $fees2 = $row[overdraft] + $row[reoverdraft];
        return "" . $_SESSION['Country']['currency'] . "" . number_format($fees2, 2) . "";
    } else {
        return "0.00";
    }
}

function feesowing($memid) {

    $query = dbRead("select sum(dollarfees) as fees from transactions where memid = '$memid' and to_memid not in (" . get_non_included_accounts($_SESSION['Country']['countryID'], true, false, false, true) . ")");
    $row = mysql_fetch_array($query);

    if ($row[fees] > 0) {
        $fees2 = abs($row[fees]);
        return "" . $_SESSION['Country']['currency'] . "" . number_format($fees2, 2) . "";
    } else {
        return "0.00";
    }
}

function totalowing($memid) {

    $query = dbRead("select sum(sell-buy) as balance, overdraft, reoverdraft, sum(dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and members.memid = '$memid' group by transactions.memid");
    $row = mysql_fetch_array($query);

    $balance = ($row[balance] - $row[overdraft] - $row[reoverdraft]);
    if ($balance < 0) {
        $balance2 = abs($balance);
    }

    if ($row[dollarfees] > 0) {
        $fees2 = abs($row[dollarfees]);
    }

    $total = ($fees2 + $balance2);
    return "" . $_SESSION['Country']['currency'] . "" . number_format($total, 2) . "";
}

function nett($memid) {

    $query = dbRead("select sum(sell-buy) as balance, overdraft, reoverdraft, sum(dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and members.memid = '$memid' group by transactions.memid");
    $row = mysql_fetch_array($query);

    $balance = ($row[balance] - $row[overdraft] - $row[reoverdraft]);

    return "" . $_SESSION['Country']['currency'] . "" . number_format($balance, 2) . "";
}

function facamt($memid) {

    $query = dbRead("select overdraft from members where members.memid = '$memid'");
    $row = mysql_fetch_array($query);

    return $row[overdraft];
}

function facRemove($memid) {

    $todate = date("Y-m-d", mktime(0, 0, 1, date("m"), 1, date("Y") - $_SESSION['Country']['facility_renewal']));
    $query = dbRead("SELECT tbl_members_facility.acc_no as memid,

		Sum(facility_amount-facility_repay) AS Expr1

		FROM tbl_members_facility WHERE
		tbl_members_facility.facility_type = 1
		and facility_amount-facility_repay > 0
		AND tbl_members_facility.date < '" . $todate . "'
		and tbl_members_facility.acc_no = '$memid'

		GROUP BY tbl_members_facility.acc_no
		order by tbl_members_facility.acc_no ASC");

    $row = mysql_fetch_array($query);

    return $row['Expr1'];
}

function fbal($memid) {

    $query = dbRead("select sum(sell-buy) as balance from transactions where transactions.memid = '$memid' group by transactions.memid");
    $row = mysql_fetch_array($query);

    $fre = facRemove($memid);
    $bal = ($row['balance'] - $fre);

    return $bal;
}

function fee_prepayment($memid) {

    $query = dbRead("select sum(sell-buy) as balance, overdraft, reoverdraft, sum(dollarfees) as dollarfees, transfeecash from members, transactions where (members.memid = transactions.memid) and members.memid = '$memid' group by transactions.memid");
    $row = mysql_fetch_array($query);

    $balance = ($row[balance] - $row[overdraft] - $row[reoverdraft]);
    if ($balance < 0) {
        $balance = $row['dollarfees'] + abs($balance);
    } else {
        $balance = $row['dollarfees'] + (($balance / 100) * $row['transfeecash']);
    }
    return "" . $_SESSION['Country']['currency'] . "" . number_format($balance, 2) . "";
}

function servicedates($id, $type) {

    $query = dbRead("select * from registered_accounts, plans, services where (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and registered_accounts.FieldID = " . $id . "", "ebanc_services");
    $row = mysql_fetch_array($query);

    if ($row['Plan_Display_Terms'] == "Weekly") {

        //$firstpay = date("d-m-Y", mktime(0,0,0,date("m"),date("d")+8,date("Y")));

        $startDate = $row['Date_Paid'];

        $startDateArray = explode("-", $startDate);

        $dayOfWeek = date("w", strtotime($startDate));

        if ($dayOfWeek == 6 || $dayOfWeek == 0) {

            $extraWeek = ($dayOfWeek) ? 7 : 6;
        } else {

            $numDays = 6 - $dayOfWeek;
        }

        $finalExtra = ($row['Terms'] - 1) * 7;

        $firstpay = date("jS M Y", mktime(0, 0, 0, $startDateArray[1], $startDateArray[2] + 14 + $numDays + $extraWeek, $startDateArray[0]));
        $lastpay = date("jS M Y", mktime(0, 0, 0, $startDateArray[1], $startDateArray[2] + 14 + $numDays + $extraWeek + $finalExtra, $startDateArray[0]));
    } elseif ($row['Plan_Display_Terms'] == "Monthly") {

        $bank = explode("-", $row['Date_Paid']);
        $firstpay = date("jS M Y", mktime(0, 0, 0, $bank[1] + 1, 21, $bank[0]));
        $lastpay = date("jS M Y", mktime(0, 0, 0, $bank[1] + ($row['Terms']), 21, $bank[0]));
    }

    if ($type == 1) {
        return $firstpay;
    } elseif ($type == 2) {
        return $lastpay;
    } elseif ($type == 3) {
        return $row['Card_No'];
    } elseif ($type == 4) {
        return $row['Plan_Amount'];
    } elseif ($type == 5) {
        return $row['Terms'];
    } elseif ($type == 6) {
        return $row['Bill_Code'];
    } elseif ($type == 7) {
        return $row['Acc_No'];
    }
}

function get_session_size() {

    global $WordData, $PageData, $PHPSESSID, $Country, $CountryData, $CountryPref_Members;

    $Size += @filesize("/tmp/sess_" . $PHPSESSID);
    $Size += @strlen(serialize($Country));
    $Size += @strlen(serialize($CountryData));
    $Size += @strlen(serialize($CountryPref_Members));
    $Size += @strlen(serialize($PageData));
    $Size += @strlen(serialize($WordData));

    return GetFileSize($Size);
}

function GetTimeRemain($to, $from = false, $full = false) {
    if (!$from) {
        $from = mktime();
        $to = $to + $from;
    }

    $diff = $to - $from;

    $years = ($diff - ($diff % 31557600)) / 31557600;
    $diff = $diff - ($years * 31557600);
    $months = ($diff - ($diff % 2629800)) / 2629800;
    $diff = $diff - ($months * 2629800);
    $days = ($diff - ($diff % 86400)) / 86400;
    $diff = $diff - ($days * 86400);
    $hours = ($diff - ($diff % 3600)) / 3600;
    $diff = $diff - ($hours * 3600);
    $minutes = ($diff - ($diff % 60)) / 60;
    $diff = $diff - ($minutes * 60);
    $seconds = $diff;

    if ($full) {
        if ($years) {
            $howlong .= "$years year";
            if ($years > 1)
                $howlong .= "s";
        }
        if ($months) {
            $howlong .= "$months month";
            if ($months > 1)
                $howlong .= "s";
        }
        if ($days) {
            $howlong .= "$days day";
            if ($days > 1)
                $howlong .= "s";
        }
        if ($hours) {
            $howlong .= "$hours hour";
            if ($hours > 1)
                $howlong .= "s";
        }
        if ($minutes) {
            $howlong .= "$minutes minute";
            if ($minutes > 1)
                $howlong .= "s";
        }
        if ($seconds) {
            if ($minutes) {
                $howlong .= " and ";
            }
            $howlong .= "$seconds second";
            if ($seconds > 1)
                $howlong .= "s";
        }
    } else {
        if ($years)
            $howlong .= $years . "y ";
        if ($months)
            $howlong .= $months . "m ";
        if ($days)
            $howlong .= $days . "d ";
        if ($hours)
            $howlong .= $hours . "h ";
        if ($minutes) {
            $howlong .= $minutes . "m ";
        } else {
            $howlong .= "0m ";
        }
        if ($seconds) {
            $howlong .= $seconds . "s";
        } else {
            $howlong .= "0s ";
        }
    }
    return trim($howlong);
}

function which_complaint($ComplaintID) {

    switch ($ComplaintID) {

        case "1":
            return "Can't Spend";
            break;
        case "2":
            return "Not enough business";
            break;
        case "3":
            return "Inflated pricing";
            break;
        case "4":
            return "Other";
            break;
    }
}

function GetUser($UserID) {

    if ($UserID) {

        $Query = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = " . $UserID);
        $Result = @mysql_fetch_assoc($Query);

        return $Result['Name'] . "[" . $Result['Username'] . "]";
    } else {

        return "None";
    }
}

function GetEmail($accno, $type) {

    //$Query = dbRead("select * from tbl_members_email where acc_no = " . $accno ." and type in (1,".$type.") order by type desc limit 1");
    //$Result = mysql_fetch_assoc($Query);

    $Query = dbRead("select * from tbl_members_email where acc_no = " . $accno . " and type = " . $type . "");

    if (@mysql_num_rows($Query)) {

        $counter = 0;

        while ($Row = mysql_fetch_assoc($Query)) {

            if ($counter != 0) {
                $Result .= "," . $Row['email'];
            } else {
                $Result = $Row['email'];
            }
            $counter++;
        }
    } else {

        $Query = dbRead("select * from tbl_members_email where acc_no = " . $accno . " and type = 1");
        $Row = mysql_fetch_assoc($Query);

        $Result = $Row['email'];
    }

    return $Result;
}

function service_refund2($id, $item) {

    global $coun;

    $ff = "";

    $query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product, registered_accounts.FieldID as id from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.Acc_No = " . $id . " and Cash_Refund > 0 order by registered_accounts.FieldID", "ebanc_services");
    while ($row = mysql_fetch_assoc($query)) {

        $ff .= $row['FieldID'] . " - " . $row['Product'] . " - " . $row['Refund'] . "\r\n";
        $coun += $row['Refund'];
    }

    return $ff;
}

function service_refund($id, $item) {

    $query = dbRead("select registered_accounts.*, reg_acc_details.*, services.Product as Product, registered_accounts.FieldID as id from registered_accounts, reg_acc_details, plans, services where registered_accounts.Acc_No=reg_acc_details.Acc_No and (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and registered_accounts.FieldID = " . $id . " order by registered_accounts.FieldID", "ebanc_services");
    $row = mysql_fetch_assoc($query);

    if ($item == 1) {
        $am = $row['Cash_Refund'];
    } elseif ($item == 2) {
        $am = $row['Trade_Refund'];
    } elseif ($item == 3) {
        $am = $row['Product'];
    }

    return $am;
}

function service_rules($serviceID, $item, $club = false, $cid = false) {

    if ($cid) {
        $countrySQL = dbRead("select * from country where countryID = " . $cid . "");
    } else {
        $countrySQL = dbRead("select * from country where countryID = 1");
    }
    $CCrow = mysql_fetch_assoc($countrySQL);

    if ($item == 1) {

        $plan = "";
        $plan1 = "";
        $plan2 = "";
        $ww = "";
        $counter = 0;
        $planSQL = dbRead("select plans.* from plans where ServiceID = " . $serviceID . " and Club <= " . $club . " and CID = " . $cid . " and Display_Plan != 1", "ebanc_services");
        while ($row = mysql_fetch_array($planSQL)) {

            $trade = number_format($row['Trade_Percent']);
            $counter++;
            if ($counter == 1) {
                $plan1 = substr($row['Plan_Name'], 0, -1);
            } else {
                $plan1 .= " or " . substr($row['Plan_Name'], 0, -1);
            }

            $tt = $row['Min_Amount'] * $row['Plan_Terms'];

            $cashp = 100 - $row['Trade_Percent'];
            $plan2 .= "" . substr($row['Plan_Name'], 0, -1) . " Plan - You elect a " . $row['Plan_Display_Terms'] . " amount (minimum $" . $row['Min_Amount'] . " / week = $" . number_format($tt, 0) . ") " . number_format($cashp, 0) . "% Cash and " . number_format($row['Trade_Percent'], 0) . "% Trade. You will receive the membership benefit " . $row['Plan_Display_Terms'] . " for " . $row['Plan_Name'] . ".\r\n\r\n";

            $ww = substr($row['Plan_Display_Terms'], 0, -2);
        }

        //$plan = "Number of ".$ww."s over which membership benefits will be provided (".$plan1.") - ".$trade."% of membership fee can be paid in trade dollars:\r\n";
        $plan .= $plan2;
    } elseif ($item == 2) {
        if ($club == 1) {
            //$plan = "be a member of the 50% Plus Club and actively trading at a minimum of 50%";
            $plan = "be a member of the " . $CCrow['fiftyName'] . " and actively trading at a minimum of 50%";
        } elseif ($club == 2) {
            //$plan = "be a member of the Gold Club and actively trading at a minimum of 100% ";
            $plan = "be a member of the " . $CCrow['goldName'] . " and actively trading at 100% ";
        } else {
            $plan = "be a member of E Banc Trade and actively trading ";
        }
    } elseif ($item == 3) {
        if ($club == 1) {
            //$plan = " and the 50% Plus Club";
            $plan = " and the " . $CCrow['fiftyName'] . "";
        } elseif ($club == 2) {
            //$plan = " and the Gold Club";
            $plan = " and the " . $CCrow['goldName'] . "";
        } else {
            $plan = "";
        }
    } elseif ($item == 4) {
        if ($club == 1) {
            $plan = "A member refuses to trade at 50% with another member of the " . $CCrow['fiftyName'] . "";
        } elseif ($club == 2) {
            $plan = "A member refuses to trade at 100% with another member of the " . $CCrow['goldName'] . "";
        } else {
            $plan = "A member refuses to trade with another member";
        }
    } elseif ($item == 5) {
        if ($club == 1) {
            $plan = " at 50% but ";
        } elseif ($club == 2) {
            $plan = " at 100% but ";
        } else {
            $plan = " but ";
        }
    }

    return $plan;
}

function reverseTransaction($authNo) {

    global $ebancAdmin;

    $ebancAdmin = new ebancSuite();

    $transSQL = dbRead("select transactions.* from transactions where authno = '" . addslashes($authNo) . "'");
    while ($transObj = mysql_fetch_object($transSQL)) {

        $transArray[] = array(
            'memid' => $transObj->memid,
            'to_memid' => $transObj->to_memid,
            'buy' => $transObj->buy,
            'sell' => $transObj->sell,
            'dollarfees' => $transObj->dollarfees,
            'type' => $transObj->type,
            'id' => $transObj->id,
        );
    }

    foreach ($transArray as $key => $value) {

        if ($value["dollarfees"] > 0) {

            /**
             * there are dollarfees to reverse! do that now.
             */
            $cashFeesSQL = dbRead("select * from feesincurred where memid = " . $value["memid"] . " and trans_id = " . $value["id"]);
            $cashFeesRow = mysql_fetch_assoc($cashFeesSQL);

            if ($cashFeesRow['fee_paid'] != 0) {
                $feePay = new feePayment($value["memid"]);
                $feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $value["dollarfees"], '', "Reversal of Cash Fees");
            } else {
                dbWrite("update feesincurred set fee_paid = fee_amount where trans_id = " . $value["id"]);
                dbWrite("insert into transactions (memid, date, to_memid, buy, sell, dollarfees, type, details, authno, dis_date, clear_date, checked, userid) values ('" . $value['memid'] . "','" . mktime() . "','" . $_SESSION['Country']['reserveacc'] . "','0','0','-" . $value['dollarfees'] . "','9','Reversal of Transaction - " . $authNo . "','" . $authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','0','" . $_SESSION['User']['FieldID'] . "')");
            }
            //$feePay = new feePayment($value["memid"]);
            //$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $value["dollarfees"], '', "Reversal of Cash Fees");
        }

        dbWrite("insert into transactions (memid, date, to_memid, buy, sell, dollarfees, type, details, authno, dis_date, clear_date, checked, userid) values ('" . $value['to_memid'] . "','" . mktime() . "','" . $value['memid'] . "','" . $value['buy'] . "','" . $value['sell'] . "','0','" . $value['type'] . "','Reversal of Transaction - " . $authNo . "','" . $authNo . "','" . date("Y-m-d") . "','" . date("Y-m-d") . "','0','" . $_SESSION['User']['FieldID'] . "')");
    }
}

function get_services_template($CID, $Name, $Content, $RorySend = false) {

    $CountryRow = mysql_fetch_assoc(dbRead("select * from country where countryID = '" . addslashes($CID) . "'"));
    $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '" . addslashes($CID) . "'"));
    $InterCountry = mysql_fetch_assoc(dbRead("select * from country where countryID = '1'"));

    $interadd = str_replace(", ", "<br>", $InterCountry['address1']);
    $newpostal = str_replace(", ", "<br>", $CountryRow['address2']);

    if ($CountryRow['logo'] == "ept") {
        $web = "www.eplanettrade.com";
        $say = "Let the Businesses and Trade Unite";
    } else {
        $web = "www.ebanctrade.com";
        $say = "Trading alternatives for business for lifestyle for you";
    }

    if ($RorySend) {
        $newdear = $Name;
    } else {
        $newdear = "" . $CountryDataRow['dear'] . " " . $Name . "";
    }

    if ($CountryRow['countryID'] == 1) {
        $NewPostalAddress = "Postal Address";
    } else {
        $NewPostalAddress = "" . $CountryRow['name'] . " " . $CountryDataRow['tpl_headoffice'] . "";
    }

    $html_template = '
<HTML>
<HEAD>
<TITLE></TITLE>

<STYLE type="text/css">
.jobdesc { font-weight: normal; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 7pt;}
.title { font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; color: #003399}
.workthe { font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-style: italic; color: #003399;}
.confid { font-weight: normal; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 7pt;}
.areas{ font-weight: normal; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 7pt; color: #003399}
td { font-weight: normal; font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 10pt;}
a { color: #0000FF; }
</STYLE>

</HEAD>
<BODY text="#000000" link="#FF9900" vlink="#FF9900" alink="#FF9900" LEFTMARGIN=0 TOPMARGIN=0 rightmargin="0" MARGINWIDTH=0 MARGINHEIGHT=0>
<table width="100%" border="0" cellpadding="0" cellspacing="0" background="http://www.ebanctrade.com/email/theme1/eb-et1_06.jpg">
  <tr>
    <td valign="top" bgcolor="#FF6600"><div align="center">
        <table width="618" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td bgcolor="#FFFFFF"><a href="http://' . $web . '"><img src="http://www.ebanctrade.com/email/theme2/myServices.png" width="618" height="69" border="0"></a></td>
          </tr>
          <tr>
            <td>
              <table width="618" border="0" cellspacing="0" cellpadding="10"><tr><td bgcolor="#FFFFFF">
				  <p>' . $newdear . '</p>
			  	  <br>' . $Content . '
			  	  <p>myServicesBanc</p>
                  <p><span class="jobdesc">
				  Head Office<br>
				  info@myservicesbanc.com<br>
				  </span></p>
                  <p><span class="confid">' . $CountryDataRow['tpl_disclaimer'] . '</span></p></td>
              </tr>
            </table>
			</td>
          </tr>
          <tr>
            <td bgcolor="#FFCFB5"><table width="95%" align="center" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFCFB5">
              <tr valign="top">
                <td width="33%">
						<span class="title">Business Address</span><br>
                  		<span class="areas">' . $interadd . '<br>
						Sunshine Coast Australia</span>
				</td>
                <td width="32%" height="61">
						<span class="title">' . $NewPostalAddress . '</span><br>
                      	<span class="areas">' . $newpostal . '</span>
				</td>
                <td width="33%" height="61">
						<span class="title">' . $CountryDataRow['tpl_contacts'] . '</span><br>
                        <span class="areas">email info@myservicesbanc.com<br>tel ' . $CountryRow['phone'] . '
                        <br> fax ' . $CountryRow['fax'] . '</span>
				</td>
              </tr>
            </table></td>
          </tr>
        </table>
    <blockquote>&nbsp;</blockquote></div>
	</td>
  </tr>
</table>
</BODY>
</HTML>
 ';

    return $html_template;
}

function image_type_or_mime_type_to_extension($image_type, $include_dot) {
    define("INVALID_IMAGETYPE", '');

    $extension = INVALID_IMAGETYPE;            /// Default return value for invalid input

    $image_type_identifiers = array(### These values correspond to the IMAGETYPE constants
        array(IMAGETYPE_GIF => 'gif', "mime_type" => 'image/gif'), ###  1 = GIF
        array(IMAGETYPE_JPEG => 'jpg', "mime_type" => 'image/jpeg'), ###  2 = JPG
        array(IMAGETYPE_PNG => 'png', "mime_type" => 'image/png'), ###  3 = PNG
        array(IMAGETYPE_SWF => 'swf', "mime_type" => 'application/x-shockwave-flash'), ###  4 = SWF  // A. Duplicated MIME type
        array(IMAGETYPE_PSD => 'psd', "mime_type" => 'image/psd'), ###  5 = PSD
        array(IMAGETYPE_BMP => 'bmp', "mime_type" => 'image/bmp'), ###  6 = BMP
        array(IMAGETYPE_TIFF_II => 'tiff', "mime_type" => 'image/tiff'), ###  7 = TIFF (intel byte order)
        array(IMAGETYPE_TIFF_MM => 'tiff', "mime_type" => 'image/tiff'), ###  8 = TIFF (motorola byte order)
        array(IMAGETYPE_JPC => 'jpc', "mime_type" => 'application/octet-stream'), ###  9 = JPC  // B. Duplicated MIME type
        array(IMAGETYPE_JP2 => 'jp2', "mime_type" => 'image/jp2'), ### 10 = JP2
        array(IMAGETYPE_JPX => 'jpf', "mime_type" => 'application/octet-stream'), ### 11 = JPX  // B. Duplicated MIME type
        array(IMAGETYPE_JB2 => 'jb2', "mime_type" => 'application/octet-stream'), ### 12 = JB2  // B. Duplicated MIME type
        array(IMAGETYPE_SWC => 'swc', "mime_type" => 'application/x-shockwave-flash'), ### 13 = SWC  // A. Duplicated MIME type
        array(IMAGETYPE_IFF => 'aiff', "mime_type" => 'image/iff'), ### 14 = IFF
        array(IMAGETYPE_WBMP => 'wbmp', "mime_type" => 'image/vnd.wap.wbmp'), ### 15 = WBMP
        array(IMAGETYPE_XBM => 'xbm', "mime_type" => 'image/xbm')                            ### 16 = XBM
    );

    if ((is_int($image_type)) AND ( IMAGETYPE_GIF <= $image_type) AND ( IMAGETYPE_XBM >= $image_type)) {
        $extension = $image_type_identifiers[$image_type - 1]; // -1 because $image_type_identifiers array starts at [0]
        $extension = $extension[$image_type];
    } elseif (is_string($image_type) AND ( ($image_type != 'application/x-shockwave-flash') OR ( $image_type != 'application/octet-stream'))) {

        $extension = match_mime_type_to_extension($image_type, $image_type_identifiers);
    } else {
        $extension = INVALID_IMAGETYPE;
    }

    if (is_bool($include_dot)) {

        if ((false != $include_dot) AND ( INVALID_IMAGETYPE != $extension)) {
            $extension = '.' . $extension;
        }
    } else {
        $extension = INVALID_IMAGETYPE;
    }

    return $extension;
}

function match_mime_type_to_extension($image_type, $image_type_identifiers) {
    // Return from loop on a match
    foreach ($image_type_identifiers as $_key_outer_loop => $_val_outer_loop) {
        foreach ($_val_outer_loop as $_key => $_val) {
            if (is_int($_key)) {            // Keep record of extension for mime check
                $extension = $_val;
            }
            if ($_key == 'mime_type') {
                if ($_val === $image_type) {    // Found match no need to continue looping
                    return $extension;        ### Return
                }
            }
        }
    }
    // Compared all values without match
    return $extension = INVALID_IMAGETYPE;
}

function bpay_code($memid) {

    $accno = ($memid < 10000) ? str_pad($memid, 5, "0", STR_PAD_LEFT) : $memid;

    $accArray = array_reverse(preg_split('//', $accno, -1, PREG_SPLIT_NO_EMPTY));

    $accArray[] = 4;
    $accArray[] = 7;

    $sumtotalT = 0;
    $mult = 2;
    $aa = 0;
    unset($div);

    foreach ($accArray as $key => $value) {

        $sumtotal = ($value * $mult);
        $sumtotalT += ($sumtotal > 9) ? array_sum(preg_split('//', $sumtotal, -1, PREG_SPLIT_NO_EMPTY)) : $sumtotal;

        if ($mult == 2) {
            $mult = 1;
        } else {
            $mult = 2;
        }
    }

    $div = explode(".", $sumtotalT / 10);

    if ($div[1] > 0) {
        $aa = (10 - $div[1]);
    } else {
        $aa = 0;
    }

    //return "74".$memid." ".$aa;
    return "74" . $accno . "" . $aa;
}

function decimal_format($amount, $curr = false, $trade = false, $dec = false, $CID = false) {

    if ($CID) {
        $Country = mysql_fetch_assoc(dbRead("select * from country where countryID='" . $CID . "'"));
        $CountryRow = $Country;
    } else {
        $CountryRow = $_SESSION['Country'];
    }

    if (!$dec) {
        $dec = 2;
    }

    //if($_SESSION['Country']['dec_type'] == 2) {
    if ($CountryRow['dec_type'] == 2) {
        $result_num = number_format($amount, $dec, ',', '.');
    } else {
        $result_num = number_format($amount, $dec, '.', ',');
    }

    if ($curr) {
        //if($_SESSION['Country']['curr_pos'] == 2) {
        if ($CountryRow['curr_pos'] == 2) {
            if ($trade) {
                //$result = $result_num." T".$_SESSION['Country']['currency'];
                $result = $result_num . " T" . $CountryRow['currency'];
            } else {
                //$result = $result_num." ".$_SESSION['Country']['currency'];
                $result = $result_num . " " . $CountryRow['currency'];
            }
        } else {
            if ($trade) {
                //$result = "T".$_SESSION['Country']['currency'].$result_num;
                $result = "T" . $CountryRow['currency'] . $result_num;
            } else {
                //$result = $_SESSION['Country']['currency'].$result_num;
                $result = $CountryRow['currency'] . $result_num;
            }
        }
    } else {
        $result = $result_num;
    }

    return $result;
}

function re_rollover($memid) {

    $todate = date("Y-m-d", mktime(0, 0, 1, date("m"), 1, date("Y") - 5));

    $query = dbRead("
		SELECT tbl_members_facility.acc_no as memid,

		Sum(facility_amount-facility_repay) AS Expr1, tbl_members_facility.date, tbl_members_facility.FieldID, members.overdraft

		FROM tbl_members_facility INNER JOIN members ON

		tbl_members_facility.acc_no = members.memid

		LEFT OUTER JOIN invoice_re on (tbl_members_facility.FieldID = invoice_re.facid)

		WHERE (

		tbl_members_facility.facility_type = 2
		and facility_amount-facility_repay > 0
		AND tbl_members_facility.date < '" . $todate . "'
		and invoice_re.FieldID is null
		AND members.status Not In (1,3)
		AND members.memid = '" . $memid . "'
		AND members.CID = '" . $_SESSION['User']['CID'] . "')

		GROUP BY tbl_members_facility.acc_no
		order by Expr1, members.memid ASC
		");

    $row = mysql_fetch_array($query);

    return "" . $_SESSION['Country']['currency'] . "" . number_format($row['Expr1'], 2) . "";
}

function re_rollover_fee($memid, $dol = false, $invid = false) {

    $todate = date("Y-m-d", mktime(0, 0, 1, date("m"), 1, date("Y") - 5));

    if ($invid) {
        $ss = " AND tbl_members_facility.FieldID = '" . $memid . "'";
    } else {
        $ss = " AND members.memid = '" . $memid . "'";
    }

    $query = dbRead("
		SELECT tbl_members_facility.acc_no as memid,

		Sum(facility_amount-facility_repay) AS Expr1, tbl_members_facility.date, tbl_members_facility.FieldID, members.overdraft

		FROM tbl_members_facility INNER JOIN members ON

		tbl_members_facility.acc_no = members.memid

		LEFT OUTER JOIN invoice_re on (tbl_members_facility.FieldID = invoice_re.facid)

		WHERE (

		tbl_members_facility.facility_type = 2
		and facility_amount-facility_repay > 0
		AND tbl_members_facility.date < '" . $todate . "'
		and invoice_re.FieldID is null
		AND members.status Not In (1,3)
		$ss
		AND members.CID = '" . $_SESSION['User']['CID'] . "')

		GROUP BY tbl_members_facility.acc_no
		order by Expr1, members.memid ASC
		");

    $row = mysql_fetch_array($query);
    $fee = ($row['Expr1'] / 100) * $_SESSION['Country']['repercent'];

    if ($dol) {
        return $fee;
    } else {
        return "" . $_SESSION['Country']['currency'] . "" . number_format($fee, 2) . "";
    }
}

function re_rollover2($memid) {

    $todate = date("Y-m-d", mktime(0, 0, 1, date("m"), 1, date("Y")));

    $query = dbRead("
		SELECT *
		FROM invoice_re

		WHERE
		invoice_re.memid = '" . $memid . "'
		AND invoice_re.date = '" . $todate . "'

		");

    $row = mysql_fetch_array($query);
    $am = $row['amount'] / $_SESSION['Country']['repercent'] * 100;

    return "" . $_SESSION['Country']['currency'] . "" . number_format($am, 2) . "";
}

function checktrust($acc, $CID) {

    $trustacc = get_non_included_accounts($CID, True);
    $labels = explode(",", $trustacc);

    foreach ($labels as $value) {
        if ($acc == $value) {
            $yes = 1;
        }
    }

    if ($yes) {
        return true;
    } else {
        return false;
    }
}

function adfe($memid) {

    $Query = dbRead("select members.datejoined, CID, admin_fee from members, country where members.CID = country.countryID and memid = " . $memid);
    $Result2 = @mysql_fetch_assoc($Query);

    if ($Result2['datejoined'] < '2007-09-10') {
        $refee = " reduce your Admin Fee from $" . number_format($Result2['admin_fee'] / 3 * 2, 2) . " to $" . number_format(($Result2['admin_fee'] / 2), 2);
    } else {
        $refee = " reduce your Admin Fee from $" . number_format($Result2['admin_fee'], 2) . " to $" . number_format(($Result2['admin_fee'] / 3) * 2, 2);
    }

    return $refee;
}

function bg($memrow) {

    if ($memrow[bdriven] == "Y") {
        $cc = "Heading22";
    } elseif ($memrow[respenddown] == 1) {
        $cc = "Heading23";
    } elseif ($memrow[status] == 4) {
        if ($memrow[sponcat] == 1) {
            $cc = "Heading24";
        } else {
            $cc = "Heading25";
        }
    } else {
        $cc = "Heading2";
    }

    return $cc;
}

function bg1($memrow) {

    if ($memrow[bdriven] == "Y") {
        $cc = "Heading12";
    } elseif ($memrow[respenddown] == 1) {
        $cc = "Heading13";
    } elseif ($memrow[status] == 4) {
        if ($memrow[sponcat] == 1) {
            $cc = "Heading14";
        } else {
            $cc = "Heading15";
        }
    } else {
        $cc = "Heading";
    }

    return $cc;
}

function get_all_added_characters($field) {
    return preg_replace('/\\\\/', '', htmlspecialchars($field, ENT_QUOTES, "UTF-8"));
}
?>
