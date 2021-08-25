<?php

function getFileupload($HTTP_POST_VARS, $HTTP_POST_FILES, $filepathname) {

	$blocked_files = "\.(asp|jsp|php|php3|php4|phtml|phps|cgi|sh|pl)$";

	$pathname = $filepathname;

	$myfileupload = array();
	$myfileupload["title"] = "";
	$myfileupload["filename"] = "";
	$myfileupload["filenameextension"] = "";
	$myfileupload["upload_filename"] = "";
	$myfileupload["server_filename"] = "";

	if ($HTTP_POST_FILES["file"]["name"] <> "") {
		$myfileupload["title"] = $HTTP_POST_VARS["title"];
		$myfileupload["filename"] = $HTTP_POST_FILES["file"]["name"];

		$filenameparts = split("\.", $myfileupload["filename"]);
		$myfileupload["filenameextension"] = $filenameparts[sizeof($filenameparts)-1];
		$myfileupload["basefilename"] = $filenameparts[0];
		for ($i=1; $i<=(sizeof($filenameparts)-2); $i++) {
			$myfileupload["basefilename"] .= "." . $filenameparts[$i];
		}
		$myfileupload["upload_filename"] = $HTTP_POST_FILES["file"]["name"];
		$myfileupload["server_filename"] = $filepathname . $myfileupload["filename"];

		if ($pathname <> "") {
			$i = 1;
			while (file_exists($pathname . $myfileupload["filename"])) {
				$i++;
				$myfileupload["filename"] = $myfileupload["basefilename"] . "_" . $i . "." . $myfileupload["filenameextension"];
				$myfileupload["server_filename"] = $filepathname . $myfileupload["filename"];
			}
			if ( $myfileupload["filename"] <> "") {
				if (! eregi($blocked_files, $myfileupload["filename"], $matches)) {
					if (move_uploaded_file($HTTP_POST_FILES["file"]["tmp_name"], $pathname . $myfileupload["filename"])) {
						$fh = fopen($pathname . $myfileupload["filename"], "rb");
						$myfileupload["file"] = fread($fh, filesize($pathname . $myfileupload["filename"]));
						fclose($fh);
					}
				}
			}
		}

		if (($pathname == "") && (get_cfg_var("open_basedir") <> "")) {
			$pathname = get_cfg_var("open_basedir") . "/";
		}
		if ($pathname <> "") {
			$i = 1;
			$dummy_filename = "dummy.txt";
			while (file_exists($pathname . $dummy_filename)) {
				$i++;
				$dummy_filename = "dummy." . $i . ".txt";
			}
			if (move_uploaded_file($HTTP_POST_FILES["file"]["tmp_name"], $pathname . $dummy_filename)) {
				$fh = fopen($pathname . $dummy_filename, "rb");
				$myfileupload["file"] = fread($fh, filesize($pathname . $dummy_filename));
				fclose($fh);
				unlink($pathname . $dummy_filename);
			}
		} else if (get_cfg_var("open_basedir") == "") {
			$fh = fopen($HTTP_POST_FILES["file"]["tmp_name"], "rb");
			$myfileupload["file"] = fread($fh, filesize($HTTP_POST_FILES["file"]["tmp_name"]));
			fclose($fh);
		}

		if (file_exists($HTTP_POST_FILES["file"]["tmp_name"])) {
			unlink($HTTP_POST_FILES["file"]["tmp_name"]);
		}
	}
	return $myfileupload;
}

?>