<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	if ((sizeof($HTTP_POST_VARS) > 0) && ($spellcheckCommand <> "")) {
		if ($HTTP_POST_VARS["dictionary"] <> "") {
			$spellcheckParameters = $spellcheckParameters . " " . $spellcheckDictionary . " " . $HTTP_POST_VARS["dictionary"];
		}

		$content = stripslashes($HTTP_POST_VARS["content"]);

		$contentCurrentLine = 0;
		$contentNextLine = 0;

		$spellcheckContent = "";
		$mycontent = "";
		$myline = nextContentLine();
		while ($myline <> "") {
			$spellcheckContent = $spellcheckContent . "^" . $myline . "\r\n";
			$mycontent = $mycontent . $myline . "\r\n";
			$myline = nextContentLine();
		}
		$content = $mycontent;

		$jsMisspelled = "";

		$descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w"));
		$process = proc_open($spellcheckCommand . " " . $spellcheckParameters, $descriptorspec, $pipes);
		if (is_resource($process)) {
			fwrite($pipes[0], $spellcheckContent);
			fclose($pipes[0]);

			if (!feof($pipes[2])) {
				$error = str_replace("\r", "", str_replace("\n", "", fgets($pipes[2], 1024)));
				if ($error <> "") {
					echo "\r\n<p>ERROR: " . $spellcheckCommand . " " . $spellcheckParameters . "</p>\r\n";
					echo $error . "<br>\r\n";
					while (!feof($pipes[2])) {
						echo fgets($pipes[2], 1024) . "<br>\r\n";
					}
					echo "\r\n<p>\r\n";
					while (!feof($pipes[1])) {
						echo fgets($pipes[1], 1024) . "<br>\r\n";
					}
				}
			}

			$contentCurrentLine = 0;
			$contentNextLine = 0;

			$misspelledCount = 0;
			$misspelled = "";
			$suggestions = "";

			$myline = str_replace("\r", "", str_replace("\n", "", fgets($pipes[1], 1024)));
			nextContentLine();
			while (!feof($pipes[1])) {
				$myline = str_replace("\r", "", str_replace("\n", "", fgets($pipes[1], 1024)));
				$misspelledOffset = "";
				$misspelled = "";
				$suggestions = "";
				if (($myline == "") || ($myline == "\n") || ($myline == "\r\n")) {
					nextContentLine();
				} else if (substr($myline, 0, 1) == "&") {
					$i = strrpos(substr($myline, 0, strpos($myline, ":")), " ")+1;
					$misspelled = substr($myline, 2, strpos(substr($myline, 2), " "));
					$misspelledOffset = substr($myline, $i, strpos($myline, ":")-$i);
					$suggestions = substr($myline, strpos($myline, ":")+2);
				} else if (substr($myline, 0, 1) == "#") {
					$i = strpos(substr($myline, 2), " ")+3;
					$misspelled = substr($myline, 2, strpos(substr($myline, 2), " "));
					$misspelledOffset = substr($myline, $i);
					$suggestions = "";
				}
				if ((substr($myline, 0, 1) == "&") || (substr($myline, 0, 1) == "#")) {
					$misspelled = str_replace("\"", "\\\"", str_replace("'", "\\'", str_replace(", ", ",", $misspelled)));
					$suggestions = str_replace("\"", "\\\"", str_replace("'", "\\'", str_replace(", ", ",", $suggestions)));
					if ($misspelledOffset >= 0) {
						$misspelledOffset = $misspelledOffset + $contentCurrentLine - 1;
					} else {
						$misspelledOffset = $contentCurrentLine;
					}
					$jsMisspelled = $jsMisspelled . "misspelled[" . $misspelledCount . "] = new misspelledItem(" . $misspelledOffset . ",\"" . $misspelled . "\",\"" . $suggestions . "\");" . "\r\n";
					$misspelledCount = $misspelledCount + 1;
				}
			}
			fclose($pipes[1]);
			$return_value = proc_close($process);
		}
	}

	function nextContentLine() {
		global $content;
		global $contentCurrentLine;
		global $contentNextLine;

		$EOL = false;
		$myline = "";
		while (($myline == "") && (strlen($content)-$contentNextLine > 0)) {
			$EOL = strpos(substr($content, $contentNextLine), "\n");
			if ($EOL === false) {
				$EOL = strlen($content);
			} else {
				$EOL = $EOL + $contentNextLine;
			}
			if ($EOL-$contentNextLine > 0) {
				$myline = str_replace("\r", "", str_replace("\n", "", substr($content,$contentNextLine,$EOL-$contentNextLine)));
				$contentCurrentLine = $contentNextLine;
				$contentNextLine = $EOL + 1;
			}
		}
		return $myline;
	}
?>
<?php
	if (sizeof($HTTP_POST_VARS) > 0) {
		include "spellcheck.php.post.html";
	} else {
		include "spellcheck.php.get.html";
	}
?>
