<%@ Page Language="C#" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Text.RegularExpressions" %>
<script runat="server">
	string content = "";

	void Page_Load(Object Source, EventArgs E) {
		if (Request.Files.Get("file") != null) {
			byte[] input = new byte[Request.Files.Get("file").ContentLength];
			System.IO.Stream stream = Request.Files.Get("file").InputStream;
			stream.Read(input, 0, Request.Files.Get("file").ContentLength);
			for (int i = 0; i < Request.Files.Get("file").ContentLength; i++) {
				content = content + (char)input[i];
			}
		}
		if (Regex.IsMatch(content, "^(.|\r\n)*<body[^>]*>((.|\r|\n)*)</body>", RegexOptions.IgnoreCase)) {
			content = Regex.Replace(content, "^(.|\r\n)*<body[^>]*>((.|\r|\n)*)</body>", "${2}", RegexOptions.IgnoreCase);
		}
	}
</script>
<!-- #include file="import.aspx.html" -->
