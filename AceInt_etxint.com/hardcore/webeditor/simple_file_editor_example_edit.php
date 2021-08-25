<?php include "php5.php"; ?>
<?php
	# HardCore Web Content Editor example for editing a file on the web server

	# Set the name of the content file to be edited here
	$filename = "/hardcore/webeditor/simple_file_editor_example_content.html";

	if ($HTTP_POST_VARS["content"] <> "") {
		$file = fopen($_SERVER["DOCUMENT_ROOT"] . $filename, "w");
		fputs($file, stripslashes($HTTP_POST_VARS["content"]));
		fclose($file);
	}

	$content = "";
	if (file_exists($_SERVER["DOCUMENT_ROOT"] . $filename)) {
		$file = fopen($_SERVER["DOCUMENT_ROOT"] . $filename, "r");
		if ($file) {
			while (! feof($file)) {
				$line = fgets($file, 4096);
				$content = $content . $line;
			}
		}
		fclose($file);
	}
	$content_unencoded = str_replace("\n", "\\n", str_replace("\r", "\\r", str_replace("'", "\'", str_replace("\\", "\\\\", $content))));
	$content_encoded = str_replace("\n", "\\n", str_replace("\r", "\\r", str_replace("'", "\'", str_replace("\\", "\\\\", htmlspecialchars($content)))));
?>
<html>
<head>
<title>HardCore Web Content Editor Example</title>
<link rel="stylesheet" type="text/css" href="/hardcore/webeditor/webeditor.css" /> 
<script src="/hardcore/webeditor/webeditor.js"></script>
</head>
<body>
<form method="post">
	<p><input type="submit" value="Save"></p>
	<table cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>HardCoreWebEditorToolbar();</script>
	</td></tr></table>
	<table width="100%" height="450" cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>content_editor = new HardCoreWebEditor('/hardcore/webeditor/', 'php', 'content', '<?php echo str_replace("script", "scr'+'ipt", $content_unencoded) ?>', '<?php echo str_replace("script", "scr'+'ipt", $content_encoded) ?>', '', '', '', '', '', '', '', '', '', '', 'html', '');</script>
	</td></tr></table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>HardCoreWebEditorDOMInspector();</script>
	</td></tr></table>
</form>
</body>
</html>
