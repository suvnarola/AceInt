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
			if (images_path != "") {
				section_path = images_path;
				allowed_files = "\\.(" + image_formats.Replace(",", "|") + ")$";
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
<!-- #include file="medialist.aspx.html" -->
