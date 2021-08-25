<%@ Page Language="C#" Debug="true" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
	// Set href to URL for hyperlink
	string href = "";

	string error = "";

	string section_path = "";
	string allowed_files = "";

	void Page_Load(Object Source, EventArgs E) {
		if ((root_path != "") && (upload_path != "") && (enable_upload == "yes")) {
			if ((Request.Files.Get("file") != null) && (Request.Files.Get("file").FileName != "")) {
				Request.Files.Get("file").SaveAs(upload_path + Request.Files.Get("file").FileName);
			}

			if ((pages_path != "") && (Request.Form.Get("section") == "Pages")) {
				section_path = pages_path;
				allowed_files = "\\.(" + Regex.Replace(page_formats, ",", "|") + ")$";
			} else if ((images_path != "") && (Request.Form.Get("section") == "Images")) {
				section_path = images_path;
				allowed_files = "\\.(" + Regex.Replace(image_formats, ",", "|") + ")$";
			} else if ((files_path != "") && (Request.Form.Get("section") == "Files")) {
				section_path = files_path;
				allowed_files = "\\.(" + Regex.Replace(file_formats, ",", "|") + ")$";
			}
			href = section_path + Request.Form.Get("category") + Request.Form.Get("title");
			if (section_path != "") {
				if ((Request.Form.Get("action") == "Create") && (Request.Form.Get("title") != "")) {
					if ((Regex.IsMatch(Request.Form.Get("title"), allowed_files, RegexOptions.IgnoreCase)) && (Request.Files.Get("file").FileName != "") && (! File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title")))) {
						File.Move(upload_path + Request.Files.Get("file").FileName, root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title"));
					}
				} else if ((Request.Form.Get("action") == "Update") && (Request.Form.Get("old_title") != "") && (Request.Form.Get("title") != "")) {
					if (Regex.IsMatch(Request.Form.Get("title"), allowed_files, RegexOptions.IgnoreCase)) {
						if (Request.Files.Get("file").FileName != "") {
							if (Request.Form.Get("old_title") == Request.Form.Get("title")) {
								// REPLACE
								if (File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"))) {
									File.Delete(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"));
									File.Move(upload_path + Request.Files.Get("file").FileName, root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title"));
								}
							} else {
								// RENAME + REPLACE
								if ((Regex.IsMatch(Request.Form.Get("old_title"), allowed_files, RegexOptions.IgnoreCase)) && (File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"))) && (! File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title")))) {
									File.Delete(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"));
									File.Move(upload_path + Request.Files.Get("file").FileName, root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title"));
								}
							}
						} else if (Request.Form.Get("old_title") != Request.Form.Get("title")) {
							// RENAME
							if ((Regex.IsMatch(Request.Form.Get("old_title"), allowed_files, RegexOptions.IgnoreCase)) && (File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"))) && (! File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title")))) {
								File.Move(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"), root_path + section_path + Request.Form.Get("category") + Request.Form.Get("title"));
							}
						}
					}
				} else if ((Request.Form.Get("action") == "Delete") && (Request.Form.Get("old_title") != "")) {
					if ((Regex.IsMatch(Request.Form.Get("old_title"), allowed_files, RegexOptions.IgnoreCase)) && (File.Exists(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title")))) {
						File.Delete(root_path + section_path + Request.Form.Get("category") + Request.Form.Get("old_title"));
					}
					href = "";
				}
			}
			if ((Request.Files.Get("file") != null) && (Request.Files.Get("file").FileName != "")) {
				if (File.Exists(upload_path + Request.Files.Get("file").FileName)) {
					File.Exists(upload_path + Request.Files.Get("file").FileName);
				}
			}
		} else {
			error = "DISABLED";
		}
	}
</script>
<!-- #include file="hyperlinknotification.aspx.html" -->
