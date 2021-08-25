
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<?

// Send Christmas Card.

include("class.html.mime.mail.inc");

if($_POST[next] == "1") {

 $member_array = explode(",", $_POST[memid]);

 foreach($member_array as $key => $value) {

  $get_details = dbRead("select * from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and type = 3 and memid = '$value'");
  $row = mysql_fetch_assoc($get_details);

  if($_SESSION['User']['Name']) {

   //$from_name = "From ".$_SESSION['User']['Name']." and the Team at Empire Trade";
   $from_name = "From ".$_SESSION['User']['Name']." and the Team at Access Commercial Exchange International";
   $re = $_SESSION['User']['EmailAddress'];

  } else {

   //$from_name = "From the Team at Empire Trade";
   $from_name = "From the Team at Access Commercial Exchange International";
   $re = "hq@".$_SESSION['Country']['countrycode'].".empireXchange.com";

  }
	
	$re = 'hq@accesscommercial.exchange';
	
  if($row[email]) {	  

  if($row['CID'] == 3 || $row['CID'] == 10)  {

   $send_html = '
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <title>Merry Christmas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>

    <body bgcolor="#CCCCCC" text="#333333" link="#333333" vlink="#333333" alink="#333333">
    <div align="center">
      <p>&nbsp;</p>


 <table width="762" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
              <tr>
                <td><p align="center"><img src="https://secure.etxint.net/members/images/ACE-Logo.jpg" width="350" height="250"></p>

                  <table width="100%" border="0" cellspacing="0" cellpadding="30">
                    <tr>
                      <td>
						<p><font face="Arial, Helvetica, sans-serif">
                    	<strong><font color="#999999" size="4">Beste '.$row[contactname].',</font></strong></font></p>
						<font face="Arial, Helvetica, sans-serif"><strong><font color="#999999" size="4">Onze relatie met u heeft een belangrijke bijdrage geleverd aan ons succes in het afgelopen jaar.
<br>
                        <br>
                        <em> Wij willen u graag bedanken voor het vertrouwen in ons</em><br>
                        <br>
                        Wij wensen u een fijne kerst en een fantastisch nieuw jaar, en we rekenen weer op een prettige samenwerking in 2019.</font></strong></font>
       					<p><font face="Arial, Helvetica, sans-serif"><strong><font color="#333333"><font color="#999999" size="4">Hartelijke kerstgroeten van het management en medewerkers van E Banc Trade</font></font></strong></font><br>
                        <br>
                    	<br>
                  		</p>
						<div align="center"> 
							<img src="https://secure.etxint.net/members/images/IMG_5205.JPG">
						</div>
					</td>
				</tr>
       		</table>
				</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
  </body>
</html>

   ';

 } elseif($row['CID'] == 15)  {


   $send_html = '
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <title>Merry Christmas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    </head>

    <body bgcolor="#CCCCCC" text="#333333" link="#333333" vlink="#333333" alink="#333333">
    <div align="center">
      <p>&nbsp;</p>


 <table width="762" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
              <tr>
                <td><p align="center"><img src="https://secure.etxint.net/members/images/ACE-Logo.jpg" width="350" height="250"></p>

                  <table width="100%" border="0" cellspacing="0" cellpadding="30">
                    <tr>
                      <td>
						<p><font face="Arial, Helvetica, sans-serif">
                    	<strong><font color="#999999" size="4">Estimado '.$row[contactname].',</font></strong></font></p>
						<font face="Arial, Helvetica, sans-serif"><strong><font color="#999999" size="4">Como miembro de E banc trade, usted es partícipe de todos nuestros logros. Gracias por su colaboración y su confianza y por acompañarnos hacía el éxito.
						<br>
                        <br>
                        <em>Le deseamos paz y alegría para las navidades y un prospero año 2019.</em><br>
                        <br>Felices fiestas<br>De todo el equipo E Banc Trade </font></strong></font>
       					<p><font face="Arial, Helvetica, sans-serif"><strong><font color="#333333"><font color="#999999" size="4">Cordialmente<br>Pablo Salgado</font></font></strong></font><br>
                        <br>
                    	<br>
                  		</p>
						<div align="center"> 
							<img src="https://secure.etxint.net/members/images/IMG_5205.JPG">
						</div>
					</td>
				</tr>
       		</table>
				</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
  </body>
</html>

   ';


 } else {
	//http://www.ebanctrade.com/home/images/xmascard2.jpg
   $send_html = '
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <title>Merry Christmas</title>
    <meta http-equiv="Content-Type" content="text/html; charset='.which_charset($_REQUEST['page']).'">
    </head>

    <body bgcolor="#CCCCCC" text="#333333" link="#333333" vlink="#333333" alink="#333333">
    <div align="center">
      <p>&nbsp;</p>

 <table width="762" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
              <tr>
                <td><p align="center"><img src="https://secure.etxint.net/members/images/ACE-Logo.jpg" width="350" height="250"></p>

                  <table width="100%" border="0" cellspacing="0" cellpadding="30">
                    <tr>
                      <td>
						<p><font face="Arial, Helvetica, sans-serif">
                    	<strong><font color="#999999" size="4">Dear '.$row[contactname].',</font></strong></font></p>
						<font face="Arial, Helvetica, sans-serif"><strong><font color="#999999" size="4">Our
                        relationship with you has been important to our success this
                        year.<br>
                        <br>
                        <em> Thank you for your support</em><br>
                        <br>
                        We wish you a very merry Christmas and a happy and prosperous
                        New Year, and we look forward to working together in 2019.</font></strong></font>
       					<p><font face="Arial, Helvetica, sans-serif"><strong><font color="#333333"><font color="#999999" size="4">'.$from_name.'</font></font></strong></font><br>
                        <br>
                    	<br>
                  		</p>
						<div align="center"> 
							<img src="https://secure.etxint.net/members/images/IMG_5205.JPG">
						</div>
					</td>
				</tr>
       		</table>
				</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
  </body>
</html>

   ';
  }
	
    define('CRLF', "\r\n", TRUE);
 	//$mail = new html_mime_mail(array('X-Mailer: Empire Trade'));
	$mail = new html_mime_mail(array('X-Mailer: Access Commercial Exchange International'));
 	$mail->add_html($send_html,'');
 	$mail->build_message();
 	//$mail->send($row[companyname], $row[email], 'Empire Trade', $re, 'Merry Christmas');
 	$mail->send($row[companyname], $row[email], 'Access Commercial Exchange International', $re, 'Merry Christmas');

   $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
   $de = "Christmas Card Sent";
   dbWrite("insert into notes (date,memid,userid,type,note) values ('".$curdate."','".$row['memid']."','".$_SESSION['User']['FieldID']."','1','".addslashes($de)."')",'etradebanc');

  }

 }

}


?>

<html>
<body onload="javascript:setFocus('sendxmas','memid');">

<form method="POST" action="body.php?page=sendxmas" name="sendxmas">

<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2">Send Christmas E Card.</td>
	</tr>
	<tr>
		<td width="100" align="right" class="Heading2"><b>Member ID:</b></td>
		<td align="left" bgcolor="#FFFFFF"><input type="text" size="25" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="GO" name="sendxmas"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="next" value="1">

</form>

</body>
</html>