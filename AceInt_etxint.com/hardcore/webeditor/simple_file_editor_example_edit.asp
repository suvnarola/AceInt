<%
	' HardCore Web Content Editor example for editing a file on the web server

	' Set the name of the content file to be edited here
	filename = "/hardcore/webeditor/simple_file_editor_example_content.html"

	If (Request.Form("content") <> "") Then
		Set oFS = Server.CreateObject("Scripting.FileSystemObject")
		Set file = oFS.CreateTextFile(Server.MapPath(filename), True)
		file.Write Request.Form("content")
		file.Close
	End If

	content = ""
	Set oFS = Server.CreateObject("Scripting.FileSystemObject")
	If (oFS.FileExists(Server.MapPath(filename))) Then
		Set file = oFS.OpenTextFile(Server.MapPath(filename), 1, True)
		While Not file.AtEndOfStream
			line = file.ReadLine
			content = content & line
		Wend
		file.Close
	End If
	content_unencoded = Replace(Replace(Replace(Replace(content, "\", "\\"), "'", "\'"), vbcr, "\r"), vblf, "\n")
	content_encoded = Replace(Replace(Replace(Replace(Server.HTMLencode(content), "\", "\\"), "'", "\'"), vbcr, "\r"), vblf, "\n")
%>
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
	<script>content_editor = new HardCoreWebEditor('/hardcore/webeditor/', 'asp', 'content', '<%= Replace(content_unencoded, "script", "scr'+'ipt") %>', '<%= Replace(content_encoded, "script", "scr'+'ipt") %>', '', '', '', '', '', '', '', '', '', '', 'html', '');</script>
	</td></tr></table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>HardCoreWebEditorDOMInspector();</script>
	</td></tr></table>
</form>
</body>
</html>
