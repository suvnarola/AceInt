<!-- #include file="config.asp" -->
<%
	Dim category, title
	category = ""
	title = ""
	Dim error
	Dim fs, section_path
	Set fs = Server.CreateObject("Scripting.FileSystemObject")
	If ((root_path <> "") AND (enable_upload = "yes") AND (Request.QueryString("submit") <> "")) Then
		If (images_path <> "") Then
			section_path = images_path
		End If
		If (section_path <> "") Then
			Dim RegEx
			Set RegEx = new RegExp
			RegEx.Global = True
			RegEx.IgnoreCase = True
			If ((Request.QueryString("action") = "Create") AND (Request.QueryString("title") <> "")) Then
				new_category = Request.QueryString("category") & Request.QueryString("title")
				If (NOT fs.FolderExists(root_path & section_path & new_category)) Then
					fs.CreateFolder root_path & section_path & new_category
				End If
				category = new_category & "/"
				title = Request.QueryString("title")
			ElseIf ((Request.QueryString("action") = "Update") AND (Request.QueryString("old_title") <> "") AND (Request.QueryString("title") <> "")) Then
				RegEx.Pattern = "/$"
				old_category = RegEx.Replace(Request.QueryString("category"), "")
				RegEx.Pattern = Request.QueryString("old_title") & "/$"
				new_category = RegEx.Replace(Request.QueryString("category"), "" & Request.QueryString("title"))
				If ((fs.FolderExists(root_path & section_path & old_category)) AND (NOT fs.FolderExists(root_path & section_path & new_category))) Then
					fs.MoveFolder root_path & section_path & old_category, root_path & section_path & new_category
				End If
				category = new_category & "/"
				title = Request.QueryString("title")
			ElseIf ((Request.QueryString("action") = "Delete") AND (Request.QueryString("old_title") <> "")) Then
				RegEx.Pattern = "/$"
				old_category = RegEx.Replace(Request.QueryString("category"), "")
				If (fs.FolderExists(root_path & section_path & old_category)) Then
					Set folder = fs.GetFolder(root_path & section_path & old_category)
					If ((folder.Files.Count = 0) AND (folder.SubFolders.Count = 0)) Then
						fs.DeleteFolder root_path & section_path & old_category
					End If
				End If
				RegEx.Pattern = "[^/]*$"
				category = RegEx.Replace(old_category, "")
				RegEx.Pattern = "/$"
				title = RegEx.Replace(category, "")
				RegEx.Pattern = "^.*/"
				title = RegEx.Replace(title, "")
			End If
		End If
	ElseIf (root_path = "") Then
		error = "DISABLED"
	End If
%>
<!-- #include file="mediacategory.asp.html" -->
