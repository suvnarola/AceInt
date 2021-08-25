<!-- #include file="config.asp" -->
<%
	hidden_paths = "^$"
	If (exclude_paths <> "") Then
		hidden_paths = "^(" & Replace(exclude_paths, ",", "|") & ")(/.*)?$"
	End If
	Dim fs, pages, files, images
	Set fs = Server.CreateObject("Scripting.FileSystemObject")
	If (root_path <> "") Then
		If (pages_path <> "") Then
			If (fs.FolderExists(root_path & pages_path)) Then
				Set pages = fs.GetFolder(root_path & pages_path)
			End If
		End If
		If (images_path <> "") Then
			If (fs.FolderExists(root_path & images_path)) Then
				Set images = fs.GetFolder(root_path & images_path)
			End If
		End If
		If (files_path <> "") Then
			If (fs.FolderExists(root_path & files_path)) Then
				Set files = fs.GetFolder(root_path & files_path)
			End If
		End If
	End If
%>
<%

Function folderMenu(base_path, dh, menu, text, section)
	If (Typename(dh) <> "Empty") Then
		Response.Write menu & " = menuitem;" & vbcrlf
		Response.Write "hyperlinklistfilterMenu.add(menuitem++,menuitem_hyperlinks," & text & ",'javascript:openit(\'" & section & "\',\'\',\'' + " & text & " + '\')','','',true,'imgfolder.gif');" & vbcrlf
		dummy = folderSubMenu(base_path, "", dh, menu, text, section)
	End If
	folderMenu = True
End Function

Function folderSubMenu(base_path, path, dh, menu, text, section)
	If (Typename(dh) <> "Empty") Then
		Dim i
		i = 0
		Set RegEx = new RegExp
		RegEx.Global = True
		RegEx.IgnoreCase = True
		RegEx.Pattern = hidden_paths
		For Each folder In dh.SubFolders
			entry = folder.Name
			Set Matches = regEx.Execute(base_path & path & entry)
			If (Matches.Count > 0) Then
				' ignore
			ElseIf ((fs.FolderExists(root_path & base_path & path & entry)) AND (entry <> ".") AND (entry <> "..")) Then
				i = i + 1
				Response.Write menu & "_" & i & " = menuitem;" & vbcrlf
				Response.Write "hyperlinklistfilterMenu.add(menuitem++," & menu & ",'" & Replace(entry, "'", "\'") & "','javascript:openit(\'" & section & "\',\'" & path & entry & "/" & "\',\'" & entry & "\')','','','','imgfolder.gif');" & vbcrlf
				Dim subdh
				Set subdh = fs.GetFolder(root_path & base_path & path & entry)
				dummy = folderSubMenu(base_path, path & entry & "/", subdh, menu & "_" & i, "'" & entry & "'", section)
			End If
		Next
	End If
	folderSubMenu = True
End Function

%>
<!-- #include file="hyperlinklistfilter.asp.html" -->
