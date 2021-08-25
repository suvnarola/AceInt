<?

$NoSession = true;

// Send Christmas Card.
include("global.php");
include("class.html.mime.mail.inc");

$get_details = dbRead("select * from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and members.CID in (3,10) and tbl_members_email.type = 3 and members.status = 0");
while($row = mysql_fetch_assoc($get_details)) {

 $qq = dbRead("select * from country where countryID = ".$row['CID']."");
 $row2 = mysql_fetch_assoc($qq); 
 
 $from_name = "From the Team at E Banc Trade Head Office";

 if($row[email]) {
  
  if($row['CID'] == 3 || $row['CID'] == 10)  {

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
                <td><p align="center"><img src="http://www.ebanctrade.com/home/images/xmascard2.jpg" width="550" height="197"></p>
                  
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
                        Wij wensen u een fijne kerst en een fantastisch nieuw jaar, en we rekenen weer op een prettige samenwerking in 2006.</font></strong></font>
       					<p><font face="Arial, Helvetica, sans-serif"><strong><font color="#333333"><font color="#999999" size="4">Hartelijke kerstgroeten van het management en medewerkers van E Banc Trade</font></font></strong></font><br>
                        <br>
                    	<br>
                  		</p>
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
                <td><p align="center"><img src="http://www.ebanctrade.com/home/images/xmascard2.jpg" width="550" height="197"></p>
                  
                  <table width="100%" border="0" cellspacing="0" cellpadding="30">
                    <tr> 
                      <td>
						<p><font face="Arial, Helvetica, sans-serif">
                    	<strong><font color="#999999" size="4">Estimado '.$row[contactname].',</font></strong></font></p>
						<font face="Arial, Helvetica, sans-serif"><strong><font color="#999999" size="4">Como miembro de E banc trade, usted es part�cipe de todos nuestros logros. Gracias por su colaboraci�n y su confianza y por acompa�arnos hac�a el �xito.
						<br>
                        <br>
                        <em>Le deseamos paz y alegr�a para las navidades y un prospero a�o 2006.</em><br>
                        <br>Felices fiestas<br>De todo el equipo E Banc Trade </font></strong></font>
       					<p><font face="Arial, Helvetica, sans-serif"><strong><font color="#333333"><font color="#999999" size="4">Cordialmente<br>Pablo Salgado</font></font></strong></font><br>
                        <br>
                    	<br>
                  		</p>
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
                <td><p align="center"><img src="http://www.ebanctrade.com/home/images/xmascard2.jpg" width="550" height="197"></p>
                  
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
                        New Year, and we look forward to working together in 2006.</font></strong></font>
       					<p><font face="Arial, Helvetica, sans-serif"><strong><font color="#333333"><font color="#999999" size="4">'.$from_name.'</font></font></strong></font><br>
                        <br>
                    	<br>
                  		</p>
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
  
   $re = "hq@".$row2['countrycode'].".ebanctrade.com";
  
   define('CRLF', "\r\n", TRUE);
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
   $mail->add_html($send_html,'');
   $mail->build_message();
   $mail->send($row[companyname], $row[email], 'E Banc Trade', $re, 'Merry Christmas');

   $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
   $de = "Christmas Card Sent";
   dbWrite("insert into notes (date,memid,userid,type,note) values ('".$curdate."','".$row['memid']."','180','1','".addslashes($de)."')",'etradebanc');
   
 }

}

?>
