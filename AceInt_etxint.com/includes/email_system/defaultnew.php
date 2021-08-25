<?

 include("includes/modules/class.emailSystem.php");
 include("includes/modules/class.paging.php");
 include("includes/email_system/htmlMimeMail.php");
 include("FCKeditor/fckeditor.php");

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
</head>

<body>

<?

 $TabArray = array('Email Summary','Add New','Manage Header Images','Upload Images');

 if(checkmodule("SuperUser")) {

 	//$TabArray[] = 'Manage Header Images';
 	//$TabArray[] = 'Upload Images';

 }

 displaytabs($TabArray);

 $emailAdmin = new emailSystem();

 if($_REQUEST['tab'] == "tab1") {

 	if($_REQUEST['Edit']) {

 		$emailAdmin->editJob($_REQUEST['JobID']);

 	} elseif($_REQUEST['Del']) {

 		$emailAdmin->deleteJob($_REQUEST['JobID']);
 		$emailAdmin->displayList();

 	} elseif($_REQUEST['Info']) {

 		$emailAdmin->displayDetail($_REQUEST['JobID']);

 	} elseif($_REQUEST['doSend']) {

 		flush();
 		$emailAdmin->doSend($_REQUEST['jobID']);

 	} elseif($_REQUEST['Copy']) {

 		$emailAdmin->copyJob($_REQUEST['jobID']);
 		$emailAdmin->displayList();

 	} else {

 		$emailAdmin->displayList();

 	}

 } elseif($_REQUEST['tab'] == "tab2") {

 	if($_REQUEST['Next'] == 1) {

 		$emailAdmin->dbAddJob();
 		$emailAdmin->editJob($emailAdmin->addID);

 	} else {

 		$emailAdmin->addNew();

 	}

 } elseif($_REQUEST['tab'] == "tab3") {

 	$emailAdmin->displayImages();

 } elseif($_REQUEST['tab'] == "tab4") {

 	uploadImages();

 }


function uploadImages(){


if($_REQUEST['filelist']) {

    $dirname = $_REQUEST['filelist'];
	$filename = ("/home/etxint/admin.etxint.com/uploads/Image/Networking/2012/" . "$dirname" . "/");
	if (file_exists($filename)) {
	 echo "The directory $dirname exists";
	} else {

	$fol = explode("/",$dirname);
	$sdir = "/";
	foreach($fol as $key => $value) {
	   $filename2 = ("/home/etxint/admin.etxint.com/uploads/Image/Networking/2012".$sdir."" . "$value" . "/");

	   if (file_exists($filename2)) {
	     echo "The directory $value exists";
	   } else {
	     mkdir("/home/etxint/admin.etxint.com/uploads/Image/Networking/2012".$sdir."" . "$value", 0777);
	     echo "The directory $value was successfully created.";
	   }
	   //$sdir =	$sdir."/".$value."/";
	   $sdir =	$sdir."".$value."/";
	}
	 //mkdir("/home/etxint/admin.etxint.com/uploads/Image/Networking/2012/" . "$dirname", 0777);
	 //echo "The directory $dirname was successfully created.";
?>
	 <p><a href="includes/email_system/swfobjectdemo/index.php?fold=<?= $dirname ?>" target="_blank">Upload Files to <?= $dirname ?></a></p>
<?
	}

} else {
?>

		<form method="POST" enctype="multipart/form-data" action="body.php">
		<input type="hidden" name="page" value="email_system/defaultnew">
		<input type="hidden" name="tab" value="tab4">
		 <tr>
			<td width="85%"><b> Diretory:</b> /Networking/2012/ <input type="text" name="filelist" size="40"> /</td>
		 </tr>
		 <tr>
		 	<td><input type="submit" value="Next Step >>"></td>
		 </tr>
		 </form>




<?
}
}
?>

</body>

</html>