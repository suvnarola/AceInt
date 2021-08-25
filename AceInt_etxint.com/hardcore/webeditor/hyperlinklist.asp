<!-- #include file="config.asp" -->
<%
	Dim fs, links, links_path, section_path
	Set fs = Server.CreateObject("Scripting.FileSystemObject")
	section_path = ""
	allowed_files = ""
	If (root_path <> "") Then
		If ((pages_path <> "") AND (Request.QueryString("section") = "Pages")) Then
			section_path = pages_path
			allowed_files = "\.(" & Replace(page_formats, ",", "|") & ")$"
		ElseIf ((images_path <> "") AND (Request.QueryString("section") = "Images")) Then
			section_path = images_path
			allowed_files = "\.(" & Replace(image_formats, ",", "|") & ")$"
		ElseIf ((files_path <> "") AND (Request.QueryString("section") = "Files")) Then
			section_path = files_path
			allowed_files = "\.(" & Replace(file_formats, ",", "|") & ")$"
		End If
		If (section_path <> "") Then
			If (fs.FolderExists(root_path & section_path & Request.QueryString("category"))) Then
				Set links = fs.GetFolder(root_path & section_path & Request.QueryString("category"))
				If (Request.QueryString("category") = "/") Then
					links_path = section_path
				Else
					links_path = section_path & Request.QueryString("category")
				End If
			End If
		End If
	End If
%>
<!-- #include file="hyperlinklist.asp.html" -->
