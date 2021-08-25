<?

 if($_REQUEST['dbAdd']) {

 	include("../global.php");
 	include("../modules/class.emailSystem.php");
 	include("../../FCKeditor/fckeditor.php");

 } else {

 	include("includes/modules/class.emailSystem.php");
 	include("FCKeditor/fckeditor.php");

 }

 $emailAdmin = new emailSystem();

 ?>

 <html>
 <head>
 </head>
 <body background="images/nav_05.gif">
 	<?

 		$emailAdmin->addItem($_REQUEST['addItem']);

 	?>
 </body>
 </html>