<?

	/**
	 * Config
	 */

	$NoSession = true;

	include("../../global.php");

	session_start();

	$jobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
	$jobRow = @mysql_fetch_assoc($jobQuery);

	$userSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where fieldID = " . $jobRow['loginID'], "etxint_etradebanc");
	$userRow = mysql_fetch_assoc($userSQL);

	$countrySQL = dbRead("select country.* from country where countryID = " . $userRow['CID'], "etxint_etradebanc");
	$countryRow = mysql_fetch_assoc($countrySQL);

	$_SESSION['User2'] = $userRow;
	$_SESSION['Country2'] = $countryRow;

	$CDataSQL = dbRead("select * from countrydata where CID = '" . $userRow['CID'] . "'", "etxint_etradebanc");
	$CDataRow = mysql_fetch_assoc($CDataSQL);

	$templateDataQuery = dbRead("select tbl_jobs_data.* from tbl_jobs_data where JobID = " . addslashes($_REQUEST['jobID']) . " Order By `orderBy`", "etxint_email_system");
	while($templateDataRow = mysql_fetch_assoc($templateDataQuery)) {

		if($templateDataRow['templateType'] == "Section") {

			$sectionArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];

		} elseif($templateDataRow['templateType'] == "Community") {

			$communityArray[$templateDataRow['templateSection']][$templateDataRow['FieldID']] = $templateDataRow['templateData'];

		} else {

			$templateArray[$templateDataRow['templateType']] = $templateDataRow['templateData'];

		}

	}

if($_REQUEST['staff'])  {
 $query2 = dbRead("select Name, Position2, EmailAddress, Mobile from tbl_admin_users where FieldID = ".$_REQUEST['staff'], "etxint_etradebanc");
 $row2 = mysql_fetch_assoc($query2);

 $name = $row2['Name'];
 $position = $row2['Position2'];
 $email = $row2['EmailAddress'];
 $mob = $row2['Mobile'];
} else {
 $name = $_SESSION['User2']['Name'];
 $position = $_SESSION['User2']['Position2'];
 $email = $_SESSION['User2']['EmailAddress'];
 $mob = $_SESSION['User2']['Mobile'];
}

if($_REQUEST['area'])  {
 $aarea = $_REQUEST['area'];
} else {
 $aarea = $_SESSION['User2']['Area'];
}

 if($_SESSION['Country2']['logo'] == "ept")  {
   $web = "www.eplanettrade.com";
   $say = "Let the Businesses and Trade Unite";
   $Company = "E Planet Trade";
   $im = "ept.gif";
 }  elseif($_SESSION['Country2']['logo'] == "etx")  {
   $web = "www.etxint.com";
   $say = "Xchanging the way you do business";
   $Company = "Empire Trade";
   $im = "etx.gif";
 }  else  {
   $web = "www.ebanctrade.com";
   $say = "Trading alternatives for business for lifestyle for you";
   $Company = "E Banc Trade";
   $im = "ebt.gif";
 }

 $query = dbRead("select * from area where FieldID = $aarea", "etxint_etradebanc");
 $row = mysql_fetch_assoc($query);

 if($_SESSION['User2']['Area'] == $_SESSION['Country2']['DefaultArea']) {
  $title = $CDataRow['tpl_headoffice'];
  $newadd = str_replace(", ", "<br>", $_SESSION['Country2']['address1'] ."");
  $newpostal = str_replace(", ", "<br>", $_SESSION['Country2']['address2'] ."");
  $place = "National Head Office";
 } else {
  $title = $row['place'];
  $newadd = str_replace(", ", "<br>", $row['r_address'] ."");
  $newpostal = str_replace(", ", "<br>", $row['p_address'] ."");
  $place = $row[place];
 }

if($aarea != $_SESSION['Country2']['DefaultArea']) {
 if($_SESSION['Country']['logo'] == "etx") {
  $nat = '
  						<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">'.$CDataRow['tpl_headoffice'].'</font></strong><br>
						<span style="color: #FFFFFF">e</span> '.$_SESSION['Country2']['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$_SESSION['Country2']['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$_SESSION['Country2']['fax'].'</font><br>
          				<p><img src="http://www.ebanctrade.com/email/theme3/etx-vict-4-template_11.jpg" width="168" height="16" alt=""><br></p>
 ';
 } else {
  $nat = '
						<span style="font-size: 11px; font-family: Arial;"><b>'.$CDataRow['tpl_headoffice'].'</b></span><br>
						<span style="color: #FFFFFF">e</span> '.$_SESSION['Country2']['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$_SESSION['Country2']['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$_SESSION['Country2']['fax'].'</span><br><br><br>
 ';
 }
} else {
 $nat = '';
}

if($_SESSION['User2']['CID'] != 1) {
  $query2 = dbRead("select * from country where countryID = '1'", "etxint_etradebanc");
  $row2 = mysql_fetch_assoc($query2);
 if($_SESSION['Country']['logo'] == "etx") {
  $int = '
		  				<strong><font size="2">'.$CDataRow['tpl_intheadoffice'].'</font></strong><br>
						<span style="color: #FFFFFF"></span> '.$row2['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$row2['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$row2['fax'].'<br>
          				<p><img src="http://www.ebanctrade.com/email/theme3/etx-vict-4-template_11.jpg" width="168" height="16" alt=""><br></p>
 ';
 } else {
  $int = '
						<span style="font-size: 11px; font-family: Arial;"><b>'.$CDataRow['tpl_intheadoffice'].'</b></span><br>
						<span style="color: #FFFFFF">e</span> '.$row2['email'].'<br>
						<span style="color: #FFFFFF">p</span> '.$row2['phone'].'<br>
						<span style="color: #FFFFFF">f</span> '.$row2['fax'].'</span><br><br><br>
 ';
 }
} else {
 $int = '';
}

if($mob) {
 $mobi = $mob.'<br>';
} else {
 $mobi = '';
}

if($_SESSION['Country2']['logo'] == 'etx') {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled-1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<style type="text/css" title="currentStyle">
	.clickhere{	padding: 0px 0px 3px 0px; font: 10px Arial, Verdana, Geneva, Helvetica, sans-serif;	text-align: center;	color: #000000;}
	</style>
</head>
<body bgcolor="#FFFFFF" link="#0000CC" vlink="#0000CC" alink="#FF6600" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="clickhere">If you cannot view the below email,<?= $countryDataRow['em_top'] ?> <a href="http://media.ebanctrade.com/bulletin.php?jobID=<?= $jobRow['FieldID'] ?>"class="style1">
please click here</a></div>
<table width="100%" height="20" border="0" cellpadding="0" cellspacing="0" bgcolor="#887C66">
  <tr>
    <td bgcolor="#887C66"><img src="http://www.etxint.com/email/theme3/pbe-vic-5_01.jpg" width="630" height="20" alt=""></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#887C66">
  <tr>
    <td width="195" rowspan="2" valign="top" ><img src="http://www.etxint.com/email/theme3/pbe-vic-5_03.jpg" width="195" height="69" alt=""></td>
    <td height="40" valign="bottom"><div align="center"><span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#FFFFFF"><?= $say ?></span></div></td>
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
        <td valign="top"><img src="http://www.etxint.com/email/theme3/etx-vict-4-brw_06.jpg" width="18" height="412" alt=""></td>
        <td valign="top" bgcolor="#887C66"><p><img src="http://www.etxint.com/email/theme3/etx-vict-4-brw_07.jpg" width="168" height="80" alt=""></p>
		          <p><span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:12px; font-weight:bold;"><?= $title ?></span><br>
				  <span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size: 9px;">
				  	<?= $newadd ?></span></p>
          <p><img src="http://www.etxint.com/email/theme3/etx-vict-4-brw_11.jpg" width="168" height="16" alt=""><br>
              <br>
            <span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:12px; font-weight:bold;"><?= $CDataRow['tpl_postal'] ?></span><br>
			<span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size: 9px;">
            <?= $newpostal ?></span></p>
          <p><img src="http://www.etxint.com/email/theme3/etx-vict-4-brw_11.jpg" width="168" height="16" alt=""><br>
              <br>
              <span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:12px; font-weight:bold;"><?= $CDataRow['tpl_contacts'] ?></span><br>
			  <span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size: 9px;">
			  <?= $row['email'] ?><br>
			  p <?= $row['phone'] ?><br>
			  f <?= $row['fax'] ?></span></p>
          <p><img src="http://www.etxint.com/email/theme3/etx-vict-4-brw_11.jpg" width="168" height="16" alt=""><br>
	      <br>
          <p><?= $nat ?></p>
          <p><?= $int ?></p>
          <p>&nbsp; </p></font>
            <img src="http://www.etxint.com/email/theme3/pbe-vic-5_13.jpg" width="168" height="75" alt=""></p>
          </td>
        <td valign="top"><img src="http://www.etxint.com/email/theme3/pbe-vic-5-wh_08.jpg" width="9" height="412" alt=""></td>
      </tr>
    </table></td>
    <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td bgcolor="#FFFFFF"><span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:12px;"><p><br><br><br></p><br>
		  <?= $templateArray['templateData'] ?>
          <hr size="1" noshade>
			<p><span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:9px;"><?= $CDataRow['em_rem'] ?></span></p>
			<span style="font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:9px;"><?= $CDataRow['tpl_disclaimer'] ?></span></span></td>
      </tr>
    </table>      <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br>
    </font> </p>
    </td>
  </tr>
</table>

<p>
</p>
</body>
</html>

<?
} else {
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>E Banc Trade</title>
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
			  	  <br><?= $templateArray['templateData'] ?>
                  <p><span class="jobdesc">
				  </span></p>
				  <p><span style="font-size: 7pt"><?= $CDataRow['em_rem'] ?></span></p>
                  <p><span style="font-size: 7pt"><?= $CDataRow['tpl_disclaimer'] ?></span></p>
                </td>
              </tr>
            </table>
			</td>
          </tr>
		</table>
		</td>
		<td width="18">&nbsp;</td>
		<td class="bgImage">
			<table cellpadding="0" cellspacing="0" height="100%">
				<tr>
					<td style="width: 182px; height: 118px; vertical-align: top; background: #9ec03e;">
					<img src="http://www.ebanctrade.com/email/images/<?= $im ?>"></td>
				</tr>
				<tr>
					<td style="width: 182px; height: 100%; vertical-align: top;">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
								<td width="18">&nbsp;</td>
								<td style="color: #000066; padding-left: 10px; font-size: 11px; line-height: 100%; font-family: Arial; background: #9ec03e;">
								<?= $web ?><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b><?= $title ?></b></span><br>
                  		<span style="font-style: italic;"><?= $newadd ?></span><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b><?= $CDataRow['tpl_postal'] ?></b></span><br>
                      	<span style="font-style: italic;"><?= $newpostal ?></span><br><br>
						<span style="font-size: 11px; font-family: Arial;"><b><?= $CDataRow['tpl_contacts'] ?></b></span><br>
						<span style="font-style: italic;">
						<span style="color: #FFFFFF">e</span> <?= $row['email'] ?><br>
						<span style="color: #FFFFFF">p</span> <?= $row['phone'] ?><br>
						<span style="color: #FFFFFF">f</span> <?= $row['fax'] ?></span><br><br><br>
						<?= $nat ?>
						<?= $int ?>
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
		<td style="background: #003E80; width: 20px; vertical-align: top;">&nbsp;</td>
	</tr>
</table>

</body>
</html>
<?}?>