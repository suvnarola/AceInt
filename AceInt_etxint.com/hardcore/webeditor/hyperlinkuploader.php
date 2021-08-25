<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	$valid_extensions = "";
	if (($pages_path <> "") && ($HTTP_GET_VARS["section"] == "Pages")) {
		$valid_extensions = $page_formats;
	}
	if (($images_path <> "") && ($HTTP_GET_VARS["section"] == "Images")) {
		$valid_extensions = $image_formats;
	}
	if (($files_path <> "") && ($HTTP_GET_VARS["section"] == "Files")) {
		$valid_extensions = $file_formats;
	}
?>
<?php include "hyperlinkuploader.php.html"; ?>
