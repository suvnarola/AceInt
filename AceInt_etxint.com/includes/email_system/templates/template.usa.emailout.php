<?php



	/**
	 * Config
	 */

	$NoSession = true;

	include("../../global.php");

	session_start();

	$jobQuery = dbRead("select tbl_jobs.* from tbl_jobs where FieldID = " . addslashes($_REQUEST['jobID']), "etxint_email_system");
	$jobRow = @mysql_fetch_assoc($jobQuery);

	$userSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where fieldID = " . $jobRow['loginID']);
	$userRow = mysql_fetch_assoc($userSQL);

	$countrySQL = dbRead("select country.* from country where countryID = " . $userRow['CID']);
	$countryRow = mysql_fetch_assoc($countrySQL);

	$_SESSION['User2'] = $userRow;
	$_SESSION['Country2'] = $countryRow;

	$CDataSQL = dbRead("select * from countrydata where CID = '" . $userRow['CID'] . "'", "etradebanc");
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


?><html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #ffeccf;
}
body, td, th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
}
.style1 {
	font-size: 9px
}
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
.style3 {color: #FFFFFF}
.style5 {font-size: 12px}
.style7 {font-size: 11px}
-->
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#090000">
        <tr>
          <td><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_01.jpg" width="423" height="207" alt="Empire Trade Exchange logo"></td>
          <td><div align="right"><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_03.jpg" width="167" height="207" alt="Exhanging the way you do business"></div></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="158" valign="top" bgcolor="#090000"><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_04.jpg" width="158" height="322" alt="Side bar"><br>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><p><span class="style3"><img src="http://www.empirexchange.com/email/usa/images/etx-div-usa.jpg" alt="ETX USA" width="152" height="39"><br>
                      Suite 102<br>
                    500 S Palm Canyon Drive<br>
Palm Springs, CA 92262<br>

                </span></p>
                  <p><span class="style3"><span class="style2"><img src="http://www.empirexchange.com/email/usa/images/etx-div-cont.jpg" alt="ETX Office Contacts" width="152" height="39"></span><br>
                      <span class="style1"> <span class="style7">hq@us.empirexchange.com</span><br>
                      <span class="style5">p +1 760 327 0807<br>
                  f +1 760 327 0876</span></span></span></p>

<!--                  <p><span class="style3"><span class="style2"><img src="http://www.empirexchange.com/email/usa/images/etx-div-ie.jpg" alt="ETX Office Contacts" width="152" height="39"></span><br>
                      <span class="style1"> <span class="style7"><span class="style5">Tim Snyder</span><br>
                                            ie@us.empirexchange.com</span><br>
                      <span class="style5">p +1 909 238 2185<br>
f +1 760 327 0876</span></span></span><br>
</p>
                  <p><span class="style3"><span class="style2"><img src="http://www.empirexchange.com/email/usa/images/etx-div-oc.jpg" alt="ETX Office Contacts" width="152" height="39"></span><br>
                      <span class="style1"> <span class="style7"><span class="style5">Ryan Johnson<br>
                      </span>oc@us.empirexchange.com</span><br>
                      <span class="style5">p +1 951 345 4006<br>
f +1 951 461 0504</span></span></span><br>
</p>
                  <p><span class="style3"><span class="style2"><img src="http://www.empirexchange.com/email/usa/images/etx-div-te.jpg" alt="ETX Office Contacts" width="152" height="39"></span><br>
                        <span class="style1"> <span class="style7"><span class="style5">Sheena Brewer<br>
                        </span>ta@us.empirexchange.com</span><br>
                        <span class="style5">p +1 951 345 4006<br>
                    f +1 951 461 0504</span></span></span><br> -->
                  </p>
                  <p><br>
                    <br>
                    </p></td>
              </tr>
            </table>
            <p>&nbsp;</p>
            <p>
               <br>
                <br>
            </p>
          <br></td>
          <td width="10">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="20">
              <tr>
                <td>
                	<?= $templateArray['templateData'] ?>
                	<br>
                	<p>This message is sent to online members of Empire Trade. If you feel that your email was obtained by error, or you no longer wish to receive membership promotions, please <a href='mailto:publications@us.empireXchange.com?subject=Please remove me from your email list'>select here</a> and hit send.</p>
				</td>
              </tr>
            </table></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="158" rowspan="2" bgcolor="#090000"><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_07.jpg" width="158" height="201" alt="divider"></td>
          <td height="177" valign="bottom"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#d7c1a7">
              <tr>
                <td width="7" height="134"><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_09.jpg" width="7" height="163" alt="table border"></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="20">
                    <tr>
                      <td>Confidentiality: The information contained in this email and               any attachments is confidential and/or privileged. If the reader is               not the intended recipient named above, or a representative of the               intended recipient, you are advised that any review, dissemination               or copying of this email and any attachments is prohibited. If you               have received this email in error, please notify the sender by               email, telephone or fax and return it to the           sender.</td>
                    </tr>
                  </table></td>
                <td width="5"><div align="right"><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_11.jpg" width="5" height="164" alt="table border"></div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="24" valign="bottom" bgcolor="#090000"><img src="http://www.empirexchange.com/email/usa/images/etx-eml-tpl-2.00_13.jpg" width="302" height="24" alt="bottom divider"></td>
        </tr>
        <tr>
          <td height="50" bgcolor="#090000">&nbsp;</td>
          <td height="24" valign="bottom" bgcolor="#090000">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div align="center">
        <p class="style1">EMPIRE TRADE EXCHANGE - XCHANGING THE WAY YOU DO BUSINESS</p>
      </div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
