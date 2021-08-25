<?

$CONFIG['db_name'] = "etradebanc";
$CONFIG['db_host'] = "localhost";
$CONFIG['db_user'] = "empireDB";
$CONFIG['db_pass'] = "1emPire82";
$CONFIG['db_linkid'] = mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

$CONFIG[DEBUG] = true;

$CONFIG[Username] = $_SERVER[REMOTE_USER];

include("auction_functions.php");
include("../includes/modules/class.empiremailer.php");
////////////////////////////// GLOBAL FUNCTIONS //////////////////////////////

function dbRead($SQLQuery,$database = false) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error()); }

	$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);
	if ($rsid == False) { dbReportError(mysql_errno(),mysql_error()); }

	return($rsid);
}


function dbWrite($SQLQuery,$database = false,$ReturnID = False) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error()); }

	$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);
	if ($rsid == False) { dbReportError(mysql_errno(),mysql_error()); }
	if ($ReturnID == True) {
		$ReturnID = mysql_insert_id($CONFIG['db_linkid']);
	} else {
		$ReturnID = True;
	}

	return($ReturnID);
}


function dbReportError($ErrorNumber,$ErrorMsg) {

	print "An error occured while connecting to the database<br>";
	print "<strong>$ErrorNumber</strong>";
	print $ErrorMsg;
	exit;
}

function getmicrotime() {
   	list($msec, $sec) = explode(" ",microtime());
   	return ((float)$sec + (float)$msec);
}

function display_info() {

?>
<!-- -----------------------------------------------------------------
DomainName  = <?= $_SERVER[SERVER_NAME] ?>
ScriptName  = <?= $_SERVER[SCRIPT_NAME] ?>
IPAddress   = <? if($_SERVER[HTTP_X_FORWARDED_FOR]) { echo $_SERVER[HTTP_X_FORWARDED_FOR]; } else { echo $_SERVER["REMOTE_ADDR"]; } ?>
UserAgent   = <?= $_SERVER[HTTP_USER_AGENT] ?>
----------------------------------------------------------------- --->
<?

}

function tabs($tabarray) {

 $count = sizeof($tabarray);

 if($_GET[tab]) {

  ?>
    <table border="0" cellpadding="0" cellspacing="0" width="640">
    <tr>
      <td><img border="0" src="images/layout_arrow_right.gif" width="6" height="11">&nbsp;</td>
      <td width="100%">
      <?

       $foo = 1;

       foreach($tabarray as $tabkey => $tabvalue) {

        if($tabvalue == $_GET[tab]) {
         ?>
         &nbsp;<a class="nav" href="main.php?page=<?= $_REQUEST['page']?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=<?= $tabvalue ?>"><b><?= $tabvalue ?>&nbsp;</b></a><? if($count != $foo) { ?> |<? } ?>
         <?
        } else {
         ?>
         &nbsp;<a class="nav" href="main.php?page=<?= $_REQUEST['page']?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=<?= $tabvalue ?>"><?= $tabvalue ?>&nbsp;</a><? if($count != $foo) { ?> |<? } ?>
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

function get_time_remain($row) {

	$diff = $row[unix_ends]-mktime();
	$years = ($diff - ($diff % 31536000)) / 31536000;
	$diff = $diff - ($years * 31536000);
	$days = ($diff - ($diff % 86400)) / 86400;
	$diff = $diff - ($days * 86400);
	$hours = ($diff - ($diff % 3600)) / 3600;
	$diff = $diff - ($hours * 3600);
	$minutes = ($diff - ($diff % 60)) / 60;
	if($years != 0) { $AgeDate .= $years."y "; }
	if($days > 1) { $AgeDate .= $days." days "; } elseif($days == 1) { $AgeDate .= $days." day "; }
	if($hours != 0) { $AgeDate .= $hours."h "; }
	if($minutes != 0 ) { $AgeDate .= $minutes."m"; }

	return $AgeDate;

}

function get_days($diff) {

	$years = ($diff - ($diff % 31536000)) / 31536000;
	$diff = $diff - ($years * 31536000);
	$days = ($diff - ($diff % 86400)) / 86400;
	$diff = $diff - ($days * 86400);
	$hours = ($diff - ($diff % 3600)) / 3600;
	$diff = $diff - ($hours * 3600);
	$minutes = ($diff - ($diff % 60)) / 60;
	if($days > 1) { $AgeDate .= $days." days "; } elseif($days == 1) { $AgeDate .= $days." day "; } elseif($days < 1) { $AgeDate .= "0 Days "; }

	return $AgeDate;

}

function getDirList($dirName) {

	$d = dir($dirName);

	while($entry = $d->read()) {
		if ($entry != "." && $entry != "..") {
			if (is_dir($dirName."/".$entry)) {
				getDirList($dirName."/".$entry);
			} else {
				$FilesArray[] = $entry;
			}
		}
	}

	$d->close();
	return $FilesArray;
}

function form_select($name,$query,$value,$key,$compare = false,$allowall = false,$custom = false,$size = 1) {

 $sql_query = $query;

 if($allowall) {

  $output .= "<option value=\"\">$allowall</option>\n";

 }

 if(is_array($query)) {

  foreach($query as $key2 => $value2) {

   if(strtolower($value2) == strtolower($compare)) {

    $output .= "<option selected value=\"$key2\">$value2</option>\n";

   } else {

    $output .= "<option value=\"$key2\">$value2</option>\n";

   }

  }

 } else {

  while($row = mysql_fetch_assoc($sql_query)) {

   if(strtolower($row[$key]) == strtolower($compare)) {

    $output .= "<option selected value=\"$row[$key]\">$row[$value]</option>\n";

   } else {

    $output .= "<option value=\"$row[$key]\">$row[$value]</option>\n";

   }

  }

 }

 print "<select size=\"$size\" name=\"$name\"$custom>\n$output</select>";

}

function form_radio_yesno($name,$yesno,$custom = false) {

 if($yesno == "Y" || $yesno == 1 || $yesno == "Buy") {

  $output .= "<input checked type=\"radio\" name=\"$name\" value=\"Y\"$custom> Y <input type=\"radio\" name=\"$name\" value=\"N\"$custom> N";

 } else {

  $output .= "<input type=\"radio\" name=\"$name\" value=\"Y\"$custom> Y <input checked type=\"radio\" name=\"$name\" value=\"N\"$custom> N";

 }

 print $output;

}

function form_addslashes() {

 foreach($_REQUEST as $key => $value) {

  $TV[$key] = addslashes($value);

 }

 return $TV;

}

function get_html_template($CID,$Name,$Content,$RorySend = false) {

 $CountryRow = mysql_fetch_assoc(dbRead("select * from country where countryID = '".addslashes($CID)."'"));
 $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '".addslashes($CID)."'"));
 $InterCountry = mysql_fetch_assoc(dbRead("select * from country where countryID = '1'"));

 $newadd = str_replace(", ", "<br>", $CountryRow['address1']);
 $newpostal = str_replace(", ", "<br>", $CountryRow['address2']);

 if($CountryRow['logo'] == "ept")  {
   $web = "www.eplanettrade.com";
   $say = "Let the Businesses and Trade Unite";
 }  elseif($CountryRow['logo'] == "etx")  {
   $web = "www.empireXchange.com";
   $say = "Xchanging the way you do business";
 } else {
   $web = "www.ebanctrade.com";
   $say = "Wealth creation through business growth";
 }

 if($RorySend) {
  $newdear = $Name;
 } else {
  $newdear = "".$CountryDataRow['dear']." ".$Name."";
 }

if($CID != 1) {
 if($CountryRow['logo'] == "etx") {
  $int = '
		  				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">'.$CountryDataRow['tpl_intheadoffice'].'</font></strong><br>
						<span style="color: #FFFFFF"></span> '.$InterCountry['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$InterCountry['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$InterCountry['fax'].'</font><br>
          				<p><img src="http://www.ebanctrade.com/email/theme3/etx-vict-4-template_11.jpg" width="168" height="16" alt=""><br></p>
  ';
 } else {
  $int = '
						<span style="font-size: 11px; font-family: Arial;"><b>'.$CountryDataRow['tpl_intheadoffice'].'</b></span><br>
						<span style="color: #FFFFFF">e</span> '.$InterCountry['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$InterCountry['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$InterCountry['fax'].'</span><br><br><br>
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

$ei = $CountryRow['logo'].".gif";

 if($CountryRow['logo'] == "etx") {
 $html_template = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Empire Trade</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" height="20" border="0" cellpadding="0" cellspacing="0" bgcolor="#887C66">
  <tr>
    <td bgcolor="#887C66"><img src="http://www.empirexchange.com/email/theme3/pbe-vic-5_01.jpg" width="630" height="20" alt=""></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#887C66">
  <tr>
    <td width="195" rowspan="2" valign="top" ><img src="http://www.empirexchange.com/email/theme3/pbe-vic-5_03.jpg" width="195" height="69" alt=""></td>
    <td height="40" valign="bottom"><div align="center"><strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">'. $say .'</font></strong></div></td>
    <td width="625" rowspan="2" valign="top" >&nbsp;</td>
  </tr>
  <tr>
    <td width="414" height="27" valign="bottom">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="195" valign="top" bgcolor="#887C66"><table width="195" border="0" cellpadding="0" cellspacing="0" bgcolor="#887C66">
      <tr>
        <td valign="top"><img src="http://www.empirexchange.com/email/theme3/etx-vict-4-brw_06.jpg" width="18" height="412" alt=""></td>
        <td valign="top" bgcolor="#887C66"><p><img src="http://www.empirexchange.com/email/theme3/etx-vict-4-brw_07.jpg" width="168" height="80" alt=""></p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">'.$CountryDataRow['tpl_headoffice'].'</font></strong><br>
            '.$newadd.'</font></p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="http://www.empirexchange.com/email/theme3/etx-vict-4-brw_11.jpg" width="168" height="16" alt=""><br>
              <br>
            <strong><font size="2">'.$CountryDataRow['tpl_postal'].'</font></strong><br>
            '.$newpostal.'</font></p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="http://www.empirexchange.com/email/theme3/etx-vict-4-brw_11.jpg" width="168" height="16" alt=""><br>
              <br>
              <strong><font size="2">'.$CountryDataRow['tpl_contacts'].'</font></strong><br>
            '.$CountryRow['email'].' <br>
            p '.$CountryRow['phone'].' <br>
            f '.$CountryRow['fax'].' </font></p>
          <p><img src="http://www.empirexchange.com/email/theme3/etx-vict-4-brw_11.jpg" width="168" height="16" alt=""><br>
	  <br>
          <p>'.$int.'</p>
          <p>&nbsp; </p>
            <img src="http://www.empirexchange.com/email/theme3/pbe-vic-5_13.jpg" width="168" height="75" alt=""></p>
          </td>
        <td valign="top"><img src="http://www.empirexchange.com/email/theme3/pbe-vic-5-wh_08.jpg" width="9" height="412" alt=""></td>
      </tr>
    </table></td>
    <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td bgcolor="#FFFFFF"><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br><br>'.$newdear.'</font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">'.$Content.'<br><br></font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Empire Trade</font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            '.$CountryDataRow['tpl_headoffice'].'<br>
            '.$CountryRow['email'].'<br>
            '.$web.'</font></p>
          <hr size="1" noshade>
          </p>
          <font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$CountryDataRow['tpl_disclaimer'].'</font></td>
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
				  <p>'.$newdear.'</p>
			  	  <br>'.$Content.'
			  	  <p>Empire Trade</p>
                  <p><span class="jobdesc">
				  '.$CountryDataRow['tpl_headoffice'].'<br>
				  '.$CountryRow['email'].'<br>
				  '.$web.'
				  </span></p>
                  <p><span style="font-size: 7pt">'.$CountryDataRow['tpl_disclaimer'].'</span></p>
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
                    <img src="http://www.ebanctrade.com/email/images/'. $ei .'" width="182" height="118"></td>
				</tr>
				<tr>
					<td style="width: 182px; height: 100%; vertical-align: top;">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
								<td width="18">&nbsp;</td>
								<td style="color: #000066; padding-left: 10px; font-size: 11px; line-height: 100%; font-family: Arial; background: #9ec03e;">'. $web .'<br><br>
						<span style="font-size: 11px; font-family: Arial;"><b>'.$CountryDataRow['tpl_headoffice'].'</b></span><br>
                  		<span style="font-style: italic;">'.$newadd.'</span><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b>'.$CountryDataRow['tpl_postal'].'</b></span><br>
                      	<span style="font-style: italic;">'.$newpostal.'</span><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b>'.$CountryDataRow['tpl_contacts'].'</b></span><br>
						<span style="font-style: italic;">
						<span style="color: #FFFFFF">e</span> '.$CountryRow['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$CountryRow['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$CountryRow['fax'].'</span><br><br><br>
						'.$int.'
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

function getWho($logo, $type) {

	switch($type) {

		case "1":

			/**
			 * Text.
			 */

			switch($logo) {

				case "etx":

					return "Empire Trade";
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

			switch($logo) {

				case "etx":

					return "empireXchange.com";
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

	if($addAttach) {

		foreach($addAttach as $key => $value) {

			$awesomeMail->AddStringAttachment($value[0] , $value[1], $value[2], $value[3]);

		}

	}

	if(is_array($bccArray)) {

		foreach($bccArray as $key => $value) {

	   		$awesomeMail->AddBCC($value[0], $value[1]);

		}

	}

	foreach($addAddress as $key => $value) {

   		$awesomeMail->AddAddress($value[0], $value[1]);

	}

    $awesomeMail->Send();

}
?>
