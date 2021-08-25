<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	$section_path = "";
	$allowed_files = "";
	if ($root_path <> "") {
		if ($images_path <> "") {
			$section_path = $images_path;
			$allowed_files = "\.(" . str_replace(",", "|", $image_formats) . ")$";
		}
		if ($section_path <> "") {
			if (is_dir($root_path . $section_path)) {
				$links = opendir($root_path . $section_path);
				$links_path = $section_path;
			}
		}
	}
?>
<?php include "table.php.html"; ?>
