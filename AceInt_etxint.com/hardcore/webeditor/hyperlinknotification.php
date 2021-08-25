<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php include "Fileupload.php"; ?>
<?php
	# Set href to URL for hyperlink
	$href = "";

	$error = "";

	$myfileupload = "";
	$section_path = "";
	$allowed_files = "";
	if (($root_path <> "") && ($upload_path <> "") && ($enable_upload == "yes")) {
		$myfileupload = getFileupload($HTTP_POST_VARS, $HTTP_POST_FILES, $upload_path);
		if (($pages_path <> "") && ($HTTP_POST_VARS["section"] == "Pages")) {
			$section_path = $pages_path;
			$allowed_files = "\.(" . str_replace(",", "|", $page_formats) . ")$";
		} else if (($images_path <> "") && ($HTTP_POST_VARS["section"] == "Images")) {
			$section_path = $images_path;
			$allowed_files = "\.(" . str_replace(",", "|", $image_formats) . ")$";
		} else if (($files_path <> "") && ($HTTP_POST_VARS["section"] == "Files")) {
			$section_path = $files_path;
			$allowed_files = "\.(" . str_replace(",", "|", $file_formats) . ")$";
		}
		$href = $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"];
		if ($section_path <> "") {
			if (($HTTP_POST_VARS["action"] == "Create") && ($HTTP_POST_VARS["title"] <> "")) {
				if ((eregi($allowed_files, $HTTP_POST_VARS["title"])) && ($myfileupload["filename"] <> "") && (! file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]))) {
					copy($upload_path . $myfileupload["filename"], $root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]);
					if (file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"])) {
						unlink($upload_path . $myfileupload["filename"]);
					}
				}
			} else if (($HTTP_POST_VARS["action"] == "Update") && ($HTTP_POST_VARS["old_title"] <> "") && ($HTTP_POST_VARS["title"] <> "")) {
				if (eregi($allowed_files, $HTTP_POST_VARS["title"])) {
					if ($myfileupload["filename"] <> "") {
						if ($HTTP_POST_VARS["old_title"] == $HTTP_POST_VARS["title"]) {
							# REPLACE
							if (file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"])) {
								unlink($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"]);
								copy($upload_path . $myfileupload["filename"], $root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]);
							}
						} else {
							# RENAME + REPLACE
							if ((eregi($allowed_files, $HTTP_POST_VARS["old_title"])) && (file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"])) && (! file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]))) {
								unlink($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"]);
								copy($upload_path . $myfileupload["filename"], $root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]);
							}
						}
					} else if ($HTTP_POST_VARS["old_title"] <> $HTTP_POST_VARS["title"]) {
						# RENAME
						if ((eregi($allowed_files, $HTTP_POST_VARS["old_title"])) && (file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"])) && (! file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]))) {
							rename($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"], $root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["title"]);
						}
					}
				}
			} else if (($HTTP_POST_VARS["action"] == "Delete") && ($HTTP_POST_VARS["old_title"] <> "")) {
				if ((eregi($allowed_files, $HTTP_POST_VARS["old_title"])) && (file_exists($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"]))) {
					unlink($root_path . $section_path . $HTTP_POST_VARS["category"] . $HTTP_POST_VARS["old_title"]);
				}
				$href = "";
			}
		}
		if ($myfileupload["filename"] <> "") {
			if (file_exists($upload_path . $myfileupload["filename"])) {
				unlink($upload_path . $myfileupload["filename"]);
			}
		}
	} else {
		$error = "DISABLED";
	}
?>
<?php include "hyperlinknotification.php.html"; ?>
