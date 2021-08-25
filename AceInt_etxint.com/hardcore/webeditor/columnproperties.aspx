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
				allowed_files = "\\.(" + Regex.Replace(image_formats, ",", "|") + ")$";
			}
			if (section_path != "") {
				DirectoryInfo di = new DirectoryInfo(root_path + section_path + Request.QueryString.Get("category"));
				if (di.Exists) {
					links = di.GetFiles();
					links_path = section_path + Request.QueryString.Get("category");
				}
			}
		}
	}
</script>
<!-- #include file="columnproperties.aspx.html" -->
