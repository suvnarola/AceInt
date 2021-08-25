<!-- #include file="config.asp" -->
<!-- #include file="Fileupload.asp" -->
<%
	' Set href to URL for hyperlink
	href = ""

	error = ""

	Dim myfileupload
	Dim section_path
	Dim allowed_files
	Dim fs
	Set fs = Server.CreateObject("Scripting.FileSystemObject")
	If ((root_path <> "") AND (upload_path <> "") AND (enable_upload = "yes")) Then
		Set myfileupload = getFileupload(Request, upload_path)
		If ((pages_path <> "") AND (myfileupload.Item("section") = "Pages")) Then
			section_path = pages_path
			allowed_files = "\.(" & Replace(page_formats, ",", "|") & ")$"
		ElseIf ((images_path <> "") AND (myfileupload.Item("section") = "Images")) Then
			section_path = images_path
			allowed_files = "\.(" & Replace(image_formats, ",", "|") & ")$"
		ElseIf ((files_path <> "") AND (myfileupload.Item("section") = "Files")) Then
			section_path = files_path
			allowed_files = "\.(" & Replace(file_formats, ",", "|") & ")$"
		End if		
		href = section_path & myfileupload.Item("category") & myfileupload.Item("title")
		Set RegEx = new RegExp
		RegEx.Global = True
		RegEx.IgnoreCase = True
		RegEx.Pattern = allowed_files
		If (section_path <> "") Then
			If ((myfileupload.Item("action") = "Create") AND (myfileupload.Item("title") <> "")) Then
				Set Matches = regEx.Execute(myfileupload.Item("title"))
				If ((Matches.Count > 0) AND (myfileupload.Item("filename") <> "") AND (NOT fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")))) Then
					fs.MoveFile upload_path & myfileupload.Item("filename"), root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")
				End If
			ElseIf ((myfileupload.Item("action") = "Update") AND (myfileupload.Item("old_title") <> "") AND (myfileupload.Item("title") <> "")) Then
				Set Matches = regEx.Execute(myfileupload.Item("title"))
				If (Matches.Count > 0) Then
					If (myfileupload.Item("filename") <> "") Then
						If (myfileupload.Item("old_title") = myfileupload.Item("title")) Then
							' REPLACE
							If (fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title"))) Then
								fs.DeleteFile root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title")
								fs.MoveFile upload_path & myfileupload.Item("filename"), root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")
							End If
						Else
							' RENAME + REPLACE
							Set Matches = regEx.Execute(myfileupload.Item("old_title"))
							If ((Matches.Count > 0) AND (fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title"))) AND (NOT fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")))) Then
								fs.DeleteFile root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title")
								fs.MoveFile upload_path & myfileupload.Item("filename"), root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")
							End If
						End If
					ElseIf (myfileupload.Item("old_title") <> myfileupload.Item("title")) Then
						' RENAME
						Set Matches = regEx.Execute(myfileupload.Item("old_title"))
						If ((Matches.Count > 0) AND (fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title"))) AND (NOT fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")))) Then
							fs.MoveFile root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title"), root_path & section_path & myfileupload.Item("category") & myfileupload.Item("title")
						End If
					End If
				End If
			ElseIf ((myfileupload.Item("action") = "Delete") AND (myfileupload.Item("old_title") <> "")) Then
				Set Matches = regEx.Execute(myfileupload.Item("old_title"))
				If ((Matches.Count > 0) AND (fs.FileExists(root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title")))) Then
					fs.DeleteFile root_path & section_path & myfileupload.Item("category") & myfileupload.Item("old_title")
				End If
				href = ""
			End If
		End If
		If (myfileupload.Item("filename") <> "") Then
			If (fs.FileExists(upload_path & myfileupload.Item("filename"))) Then
				fs.DeleteFile upload_path & myfileupload.Item("filename")
			End If
		End If
	Else
		error = "DISABLED"
	End If
%>
<!-- #include file="hyperlinknotification.asp.html" -->
