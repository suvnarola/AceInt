<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	$links = "";
	$links_path = "";
	$section_path = "";
	$allowed_files = "";
	if ($root_path <> "") {
		if ($images_path <> "") {
			$section_path = $images_path;
			$allowed_files = "\.(" . str_replace(",", "|", $image_formats) . ")$";
		}
		if ($section_path <> "") {
			if (is_dir($root_path . $section_path . $HTTP_GET_VARS["category"])) {
				$links = opendir($root_path . $section_path . $HTTP_GET_VARS["category"]);
				if ($HTTP_GET_VARS["category"] == "/") {
					$links_path = $section_path;
				} else {
					$links_path = $section_path . $HTTP_GET_VARS["category"];
				}
			}
		}
	}
?>
<?php include "medialist.php.html"; ?>
<?php
	if ($links) closedir($links);
?>
