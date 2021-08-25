<%@ page import="java.io.*" %>
<%
	// HardCore Web Content Editor example for editing a file on the web server

	// Set the name of the content file to be edited here
	String filename = "/hardcore/webeditor/simple_file_editor_example_content.html";

	if (request.getParameter("content") != null) {
		FileWriter output = new FileWriter(new java.io.File(getServletContext().getRealPath(filename)));
		output.write(request.getParameter("content"));
		output.close();
	}

	String content = "";
	File fh = new File(getServletContext().getRealPath(filename));
	if (fh.exists()) {
		BufferedReader file = null;
		try {
			file = new BufferedReader(new FileReader(getServletContext().getRealPath(filename)));
			String line;
			while ((line = file.readLine()) != null) {
				content = "" + content + line + "\r\n";
			}
			file.close();
		} catch (FileNotFoundException e) {
			if (file != null) try { file.close(); } catch (IOException ee) { ; }
		} catch (IOException e) {
			if (file != null) try { file.close(); } catch (IOException ee) { ; }
		}
	}
	String content_unencoded = content.replaceAll("\\\\", "\\\\\\\\").replaceAll("'", "\\\\'").replaceAll("\r", "\\\\r").replaceAll("\n", "\\\\n");
	String content_encoded = content.replaceAll("\\\\", "\\\\\\\\").replaceAll("\\&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;").replaceAll("\"", "&quot;").replaceAll("'", "\\\\'").replaceAll("\r", "\\\\r").replaceAll("\n", "\\\\n");
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
	<script>content_editor = new HardCoreWebEditor('/hardcore/webeditor/', 'jsp', 'content', '<%= content_unencoded.replaceAll("script", "scr'+'ipt") %>', '<%= content_encoded.replaceAll("script", "scr'+'ipt") %>', '', '', '', '', '', '', '', '', '', '', 'html', '');</script>
	</td></tr></table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
	<script>HardCoreWebEditorDOMInspector();</script>
	</td></tr></table>
</form>
</body>
</html>
