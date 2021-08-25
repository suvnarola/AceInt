<?php

include_once("includes/global.php");
?>

<?

 if(is_loggedin()) {

  ?>

  <html>

  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <title><? print "A.C.E. International :: Admin Control"; ?></title>
  </head>

  <frameset framespacing="0" border="0" rows="79,24,*" frameborder="0">
   <frame name="top" scrolling="no" noresize target="contents" src="top2.php">
   <frame name="nav" scrolling="no" src="nav2.php?page=nav2&UserID=<?= $_SESSION['User']['FieldID'] ?>&md5=<?= $_SESSION['User']['md5'] ?>" noresize marginwidth="0" marginheight="0" target="main">
   <frameset cols="140,*" frameborder="NO" border="0" framespacing="0">
   <frame name="left_nav" scrolling="auto" noresize src="left_nav2.php?page=left_nav2" target="main">
   <frame name="main" scrolling="auto" src="body.php">
  </frameset>
   <body>

    <p>This page uses frames, but your browser doesn&#39;t support them.</p>

   </body>
  </noframes>
  </frameset>

  </html>

  <?

 } else {

 // Login.

?>
 <html>

 <head>
 <meta name="GENERATOR" content="Microsoft FrontPage 6.0">
 <meta name="ProgId" content="FrontPage.Editor.Document">
 
 <meta name="globalsign-domain-verification" content="1BAXa6w9doLpmFXoOXMPiXutJuDdsdZDLdpRLdhCyY" />
 <title>A.C.E. International - Administration Login</title>
 <style>
 <!--
 td           { font-family: Verdana; font-size: 8pt }

 	.inputButton {

		border:				1px solid #7F9DB9;
		padding:			3px 8px 3px 8px;
		color:				#444444;
		background-color:	#A4BACE;
		font-weight:		bold;
		font-size:			10px;
		font-family:		Verdana;

	}
 -->
 </style>
 </head>
 <script type="text/javascript">
  if (self != top){
   if (document.images) top.location.replace(document.location.href);
   else top.location.href = document.location.href;
  }
 </script>
 <script language="javascript" type="text/javascript" src="includes/default.js">
 </script>
 <body onload="javascript:setFocus('CMSLogin','user');">
 <table border="0" width="100%" height="100%">
  <tr>
    <td width="100%">
      <div align="center">
        <center>

			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td height="1" width="1"><img src="images/pixel.gif"></td>
					<td height="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-x;" colspan="2"><img src="images/pixel.gif" width="1" height="1"></td>
					<td height="1" width="1"><img src="images/pixel.gif"></td>
					<td height="1"></td>
				</tr>
				<tr>
					<td width="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-y;"></td>
					<td colspan="2" rowspan="2" style="background-image: url(images/greyGradient.gif); background-repeat: repeat-x;">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
								<td width="1" height="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
								<td><img src="images/pixel.gif"></td>
								<td width="1" height="1" style="background-image: url(images/greyBack2.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
							</tr>
							<tr>
								<td colspan="3" style="padding: 5px; font-family: Verdana; font-size: 12px;">

				                    <table border="0" width="100%" cellpadding="2">
				                      <tr>
				                        <td width="100%">&nbsp;&nbsp;</td>
				                      </tr>
				                      <tr>
				                        <td width="100%" style="font-family: Verdana; font-size: 12px; color: #444444;" align="center"><b>&nbsp;&nbsp;&nbsp;&nbsp;A.C.E. International Staff and Agency Intranet&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				                      </tr>
				                      <tr>
				                        <td width="100%" style="font-family: Verdana; font-size: 12px; color: #444444;" align="center">Welcome to your Administration System.</td>
				                      </tr>
				                      <tr>
				                        <td width="100%">&nbsp;&nbsp;</td>
				                      </tr>
				                      <tr>
				                        <td width="100%" style="font-family: Verdana; font-size: 12px; color: #444444; font-size: 10px" align="center">
<!--				                          Developed and Customised by<br>-->
<!--				                          RDI Host for A.C.E. International<br>-->
<!--				                          <br>-->
<!--				                          Copyright (c) --><?//= date("Y") ?>
<!--				                          <a target="_blank" style="color: #000000" href="http://www.rdihost.com/">-->
<!--											RDI Host Pty Ltd</a>-->
										</td>
				                      </tr>
				                     <form name="CMSLogin" method="POST" action="<?= $_SERVER[PHP_SELF]?>">
				                      <tr>
				                        <td width="100%" style="font-family: Verdana; font-size: 12px; color: #444444;" align="center"><br><B>INTRANET Login:</B><br><br>
										User: <input  value="<?= $_REQUEST['user'] ?>" name="user" type="text" size="25" style="font-size: 7pt; font-family: Tahoma"><br>
										Pass: <input name="pass" type="password" size="25" style="font-size: 7pt; font-family: Tahoma"><br><br>
				                        <input type="hidden" name="job" value="login"><input type="submit" value="Login" class="inputButton"></td>
				                      </tr>
				                     </form>
				                    </table>

								</td>
							</tr>
							<tr>
								<td width="1" height="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
								<td width="360"><img src="images/pixel.gif"></td>
								<td width="1" height="1" style="background-image: url(images/greyBack2.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
							</tr>
						</table>
					</td>
					<td width="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
					<td width="9" height="19" style="background-image: url(images/dropShadow3.gif); background-repeat: no-repeat;"><img src="images/pixel.gif"></td>
				</tr>
				<tr>
					<td width="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
					<td width="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
					<td width="9" height="253" style="background-image: url(images/dropShadow4.gif); background-repeat: repeat-y;"><img src="images/pixel.gif"></td>
				</tr>
				<tr>
					<td height="1" width="1"><img src="images/pixel.gif"></td>
					<td height="1" style="background-image: url(images/greyBack.gif); background-repeat: repeat-x;" colspan="2"><img src="images/pixel.gif"></td>
					<td height="1" width="1" style="background-image: url(images/bottomRightCorner.gif); background-repeat: repeat-x;"><img src="images/pixel.gif"></td>
					<td height="1" width="9" style="background-image: url(images/dropShadow6.gif); background-repeat: no-repeat;"><img src="images/pixel.gif"></td>
				</tr>
				<tr>
					<td width="1"><img src="images/pixel.gif"></td>
					<td width="24" height="10" style="background-image: url(images/dropShadow1.gif); background-repeat: no-repeat;"><img src="images/pixel.gif"></td>
					<td height="10" width="340" style="background-image: url(images/dropShadow2.gif); background-repeat: repeat-x;"><img src="images/pixel.gif"></td>
					<td width="1" style="background-image: url(images/dropShadow7.gif); background-repeat: repeat-x;"><img src="images/pixel.gif"></td>
					<td width="9" hright="10" style="background-image: url(images/dropShadow5.gif); background-repeat: no-repeat;"><img src="images/pixel.gif"></td>
				</tr>
			</table>
        </center>
      </div>
    </td>
  </tr>
 </table>
 </body>

 </html>
 <?

 }

?>
