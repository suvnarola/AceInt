<%@ Page Language="C#" validateRequest=false %>
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%
	// HardCore Web Content Editor example for editing a file on the web server

	// Set the name of the content file to be edited here
	string filename = "/hardcore/webeditor/simple_file_editor_example_content.html";

	if ((Request.Form.Get("content") != null) && (Request.Form.Get("content") != "")) {
		Stream file = new FileStream(Server.MapPath(filename), FileMode.Create);
		foreach (char c in Request.Form.Get("content").ToCharArray()) {
			file.WriteByte((byte)c);
		}
		file.Close();
	}

	string content = "";
	if (File.Exists(Server.MapPath(filename))) {
		StreamReader file = File.OpenText(Server.MapPath(filename));
		content = "" + file.ReadToEnd();
		file.Close();
	}
	string content_unencoded = content.Replace("\\", "\\\\").Replace("'", "\\'").Replace("\r", "\\r").Replace("\n", "\\n");
	string content_encoded = Server.HtmlEncode(content).Replace("\\", "\\\\").Replace("'", "\\'").Replace("\r", "\\r").Replace("\n", "\\n");
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
	<script>content_editor = new HardCoreWebEditor('/hardcore/webeditor/', 'aspx', 'content', '<%= content_unencoded.Replace("script", "scr'+'ipt") %>', '<%= content_encoded.Replace("script", "scr'+'ipt") %>', '', '', '', '', '', '', '', '', '', '', 'html', '');</script>
	</td></tr></table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>HardCoreWebEditorDOMInspector();</script>
	</td></tr></table>
</form>
</body>
</html>
