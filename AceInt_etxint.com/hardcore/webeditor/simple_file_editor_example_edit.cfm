	<!--- HardCore Web Content Editor example for editing a file on the web server --->

	<!--- Set the name of the content file to be edited here --->
	<cfset filename = "simple_file_editor_example_content.html">

	<cfif IsDefined("Form.content")>
		<cffile action="write" output="#Form.content#" file="#GetDirectoryFromPath(GetCurrentTemplatePath()) & filename#" addNewLine="No">
	</cfif>

	<cfset content = "">
	<cffile action="read" file="#GetDirectoryFromPath(GetCurrentTemplatePath()) & filename#" variable="content">
	<cfset content_unencoded = Replace(Replace(Replace(Replace(content, "\", "\\", "All"), "'", "\'", "All"), Chr(13), "\r", "All"), Chr(10), "\n", "All")>
	<cfset content_encoded = Replace(Replace(Replace(Replace(HTMLEditFormat(content), "\", "\\", "All"), "'", "\'", "All"), Chr(13), "\r", "All"), Chr(10), "\n", "All")>
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
	<script>content_editor = new HardCoreWebEditor('/hardcore/webeditor/', 'cfm', 'content', '<cfoutput>#Replace(content_unencoded, "script", "scr'+'ipt", "All")#</cfoutput>', '<cfoutput>#Replace(content_encoded, "script", "scr'+'ipt", "All")#</cfoutput>', '', '', '', '', '', '', '', '', '', '', 'html', '');</script>
	</td></tr></table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>HardCoreWebEditorDOMInspector();</script>
	</td></tr></table>
</form>
</body>
</html>
