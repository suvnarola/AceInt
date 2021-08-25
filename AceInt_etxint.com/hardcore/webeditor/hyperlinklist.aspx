<%@ Page Language="C#" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
	string section_path = "";
	string allowed_files = "";
	string links_path = "";
	FileInfo[] links = null;

	void Page_Load(Object Source, EventArgs E) {
		if (root_path != "") {
			if ((pages_path != "") && (Request.QueryString.Get("section") == "Pages")) {
				section_path = pages_path;
				allowed_files = "\\.(" + page_formats.Replace(",", "|") + ")$";
			} else if ((images_path != "") && (Request.QueryString.Get("section") == "Images")) {
				section_path = images_path;
				allowed_files = "\\.(" + image_formats.Replace(",", "|") + ")$";
			} else if ((files_path != "") && (Request.QueryString.Get("section") == "Files")) {
				section_path = files_path;
				allowed_files = "\\.(" + file_formats.Replace(",", "|") + ")$";
			}
			if (section_path != "") {
				DirectoryInfo di = new DirectoryInfo(root_path + section_path + Request.QueryString.Get("category"));
				if (di.Exists) {
					links = di.GetFiles();
					if (Request.QueryString.Get("category") == "/") {
						links_path = section_path;
					} else {
						links_path = section_path + Request.QueryString.Get("category");
					}
				}
			}
		}
	}
</script>
<!-- #include file="hyperlinklist.aspx.html" -->
