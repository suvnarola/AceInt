<!-- #include file="config.asp" -->
<%
	Dim fs, links, links_path, section_path, allowed_files
	Set fs = Server.CreateObject("Scripting.FileSystemObject")
	section_path = ""
	allowed_files = ""
	If (root_path <> "") Then
		If (images_path <> "") Then
			section_path = images_path
			allowed_files = "\.(" & Replace(image_formats, ",", "|") & ")$"
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
<!-- #include file="medialist.asp.html" -->
