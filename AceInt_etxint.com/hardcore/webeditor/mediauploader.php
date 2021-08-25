<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	$valid_extensions = "";
	if ($images_path <> "") {
		$valid_extensions = $image_formats;
	}
?>
<?php include "mediauploader.php.html"; ?>
