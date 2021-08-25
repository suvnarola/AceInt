<%@ Page Language="C#" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
	string hidden_paths = "^$";
	DirectoryInfo[] pages = null;
	DirectoryInfo[] images = null;
	DirectoryInfo[] files = null;

	void Page_Load(Object Source, EventArgs E) {
		if (exclude_paths != "") {
			hidden_paths = "^(" + exclude_paths.Replace(",", "|") + ")(/.*)?$";
		}
		if (root_path != "") {
			if (pages_path != "") {
				DirectoryInfo di = new DirectoryInfo(root_path + pages_path);
				if (di.Exists) {
					pages = di.GetDirectories();
				}
			}
			if (images_path != "") {
				DirectoryInfo di = new DirectoryInfo(root_path + images_path);
				if (di.Exists) {
					images = di.GetDirectories();
				}
			}
			if (files_path != "") {
				DirectoryInfo di = new DirectoryInfo(root_path + files_path);
				if (di.Exists) {
					files = di.GetDirectories();
				}
			}
		}
	}

	void folderMenu(string base_path, DirectoryInfo[] dh, string menu, string text, string section) {
		if (dh != null) {
			Response.Write(menu + " = menuitem;" + "\r\n");
			Response.Write("hyperlinklistfilterMenu.add(menuitem++,menuitem_hyperlinks," + text + ",'javascript:openit(\\'" + section + "\\',\\'\\',\\'' + " + text + " + '\\')','','',true,'imgfolder.gif');" + "\r\n");
			folderSubMenu(base_path, "", dh, menu, text, section);
		}
	}

	void folderSubMenu(string base_path, string path, DirectoryInfo[] dh, string menu, string text, string section) {
		if (dh != null) {
			int i = 0;
			foreach (DirectoryInfo folder in dh) {
				string entry = folder.Name;
				DirectoryInfo di = new DirectoryInfo(root_path + base_path + path + entry);
				if (Regex.IsMatch(base_path + path + entry, hidden_paths, RegexOptions.IgnoreCase)) {
					// ignore
				} else if ((di.Exists) && (entry != ".") && (entry != "..")) {
					i = i + 1;
					Response.Write(menu + "_" + i + " = menuitem;" + "\r\n");
					Response.Write("hyperlinklistfilterMenu.add(menuitem++," + menu + ",'" + entry.Replace("'", "\\'") + "','javascript:openit(\\'" + section + "\\',\\'" + path + entry + "/" + "\\',\\'" + entry + "\\')','','','','imgfolder.gif');" + "\r\n");
					DirectoryInfo[] subdh = di.GetDirectories();
					folderSubMenu(base_path, path + entry + "/", subdh, menu + "_" + i, "'" + entry + "'", section);
				}
			}
		}
	}
</script>
<!-- #include file="hyperlinklistfilter.aspx.html" -->
