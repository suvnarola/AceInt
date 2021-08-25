<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php include "Fileupload.php"; ?>
<?php

	$content = "";
	$myfileupload = getFileupload($HTTP_POST_VARS, $HTTP_POST_FILES, $upload_path);
	if (($myfileupload["filename"] <> "") && (file_exists($upload_path . $myfileupload["filename"]))) {
		unlink($upload_path . $myfileupload["filename"]);
	}
	$content = "" . $myfileupload["file"];

	# eregi with three .* may may very slow
	#if (eregi("^.*<body[^>]*>(.*)</body>.*$", $content, $matches)) {
	if (eregi("<body[^>]*>(.*)</body>", $content, $matches)) {
		$content = $matches[1];
	}
?>
<?php include "import.php.html"; ?>
