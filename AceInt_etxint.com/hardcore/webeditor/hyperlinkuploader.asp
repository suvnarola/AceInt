<!-- #include file="config.asp" -->
<%
	Dim valid_extensions
	If ((pages_path <> "") AND (Request.QueryString("section") = "Pages")) Then
		valid_extensions = page_formats
	ElseIf ((images_path <> "") AND (Request.QueryString("section") = "Images")) Then
		valid_extensions = image_formats
	ElseIf ((files_path <> "") AND (Request.QueryString("section") = "Files")) Then
		valid_extensions = file_formats
	End If
%>
<!-- #include file="hyperlinkuploader.asp.html" -->
