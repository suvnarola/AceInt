<%@ Page Language="C#" %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
	string valid_extensions = "";

	void Page_Load(Object Source, EventArgs E) {
		if (images_path != "") {
			valid_extensions = image_formats;
		}
	}
</script>
<!-- #include file="mediauploader.aspx.html" -->
