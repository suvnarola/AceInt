<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	$category = "";
	$title = "";
	$error = "";
	$section_path = "";
	if (($root_path <> "") && ($enable_upload == "yes") && ($HTTP_GET_VARS["submit"] <> "")) {
		if ($images_path <> "") {
			$section_path = $images_path;
		}
		if ($section_path <> "") {
			if (($HTTP_GET_VARS["action"] == "Create") && ($HTTP_GET_VARS["title"] <> "")) {
				$new_category = $HTTP_GET_VARS["category"] . $HTTP_GET_VARS["title"] . "/";
				if (! file_exists($root_path . $section_path . $new_category)) {
					mkdir($root_path . $section_path . $new_category);
				}
				$category = $new_category;
				$title = $HTTP_GET_VARS["title"];
			} else if (($HTTP_GET_VARS["action"] == "Update") && ($HTTP_GET_VARS["old_title"] <> "") && ($HTTP_GET_VARS["title"] <> "")) {
				$old_category = $HTTP_GET_VARS["category"];
				$new_category = eregi_replace($HTTP_GET_VARS["old_title"] . "/$", $HTTP_GET_VARS["title"] . "/", $HTTP_GET_VARS["category"]);
				if ((file_exists($root_path . $section_path . $old_category)) && (! file_exists($root_path . $section_path . $new_category))) {
					rename($root_path . $section_path . $old_category, $root_path . $section_path . $new_category);
				}
				$category = $new_category;
				$title = $HTTP_GET_VARS["title"];
			} else if (($HTTP_GET_VARS["action"] == "Delete") && ($HTTP_GET_VARS["old_title"] <> "")) {
				$old_category = $HTTP_GET_VARS["category"];
				if (file_exists($root_path . $section_path . $old_category)) {
					$fh = opendir($root_path . $section_path . $old_category);
					while (($entry = readdir($fh)) && (($entry == ".") || ($entry == ".."))) { ; }
					closedir($fh);
					if (! $entry) {
						rmdir($root_path . $section_path . $old_category);
					}
				}
				$category = eregi_replace("/$", "", $old_category);
				$category = eregi_replace("[^/]*$", "", $category);
				$title = eregi_replace("/$", "", $category);
				$title = eregi_replace("^.*/", "", $title);
			}
		}
	} else if ($root_path == "") {
		$error = "DISABLED";
	}
?>
<?php include "mediacategory.php.html"; ?>
