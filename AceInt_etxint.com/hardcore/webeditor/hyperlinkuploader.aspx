<%@ Page Language="C#" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
	string valid_extensions = "";

	void Page_Load(Object Source, EventArgs E) {
		if ((pages_path != "") && (Request.QueryString.Get("section") == "Pages")) {
			valid_extensions = page_formats;
		} else if ((images_path != "") && (Request.QueryString.Get("section") == "Images")) {
			valid_extensions = image_formats;
		} else if ((files_path != "") && (Request.QueryString.Get("section") == "Files")) {
			valid_extensions = file_formats;
		}
	}
</script>
<!-- #include file="hyperlinkuploader.aspx.html" -->
