<!-- #include file="config.asp" -->
<%
	Dim valid_extensions
	If (images_path <> "") Then
		valid_extensions = image_formats
	End If
%>
<!-- #include file="mediauploader.asp.html" -->
