<%@ Page Language="C#" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
	string category = "";
	string title = "";
	string error = "";
	string section_path = "";

	void Page_Load(Object Source, EventArgs E) {
		if ((root_path != "") && (enable_upload == "yes") && (Request.QueryString.Get("submit") != "") && (Request.QueryString.Get("submit") != null)) {
			if (images_path != "") {
				section_path = images_path;
			}
			if (section_path != "") {
				if ((Request.QueryString.Get("action") == "Create") && (Request.QueryString.Get("title") != "")) {
					string new_category = Request.QueryString.Get("category") + Request.QueryString.Get("title");
					DirectoryInfo di = new DirectoryInfo(root_path + section_path + new_category);
					if (! di.Exists) {
						System.IO.Directory.CreateDirectory(root_path + section_path + new_category);
					}
					category = new_category + "/";
					title = Request.QueryString.Get("title");
				} else if ((Request.QueryString.Get("action") == "Update") && (Request.QueryString.Get("old_title") != "") && (Request.QueryString.Get("title") != "")) {
					string old_category = Regex.Replace(Request.QueryString.Get("category"), "/$", "");
					string new_category = Regex.Replace(Request.QueryString.Get("category"), "" + Request.QueryString.Get("old_title") + "/$", "" + Request.QueryString.Get("title"));
					DirectoryInfo old_di = new DirectoryInfo(root_path + section_path + old_category);
					DirectoryInfo di = new DirectoryInfo(root_path + section_path + new_category);
					if ((old_di.Exists) && (! di.Exists)) {
						old_di.MoveTo(root_path + section_path + new_category);
					}
					category = new_category + "/";
					title = Request.QueryString.Get("title");
				} else if ((Request.QueryString.Get("action") == "Delete") && (Request.QueryString.Get("old_title") != "")) {
					string old_category = Regex.Replace(Request.QueryString.Get("category"), "/$", "");
					DirectoryInfo di = new DirectoryInfo(root_path + section_path + old_category);
					if (di.Exists) {
						DirectoryInfo[] subfolders = di.GetDirectories();
						FileInfo[] files = di.GetFiles();
						if ((files.Length == 0) && (subfolders.Length == 0)) {
							di.Delete();
						}
					}
					category = Regex.Replace(old_category, "[^/]*$", "");
					title = Regex.Replace(category, "/$", "");
					title = Regex.Replace(title, "^.*/", "");
				}
			}
		} else if (root_path == "") {
			error = "DISABLED";
		}
	}
</script>
<!-- #include file="mediacategory.aspx.html" -->
