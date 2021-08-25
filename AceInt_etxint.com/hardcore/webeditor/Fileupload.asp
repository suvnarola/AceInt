<!-- #include file="ASPfileupload.asp" -->
<!-- #include file="ASPfileupload2.asp" -->
<%

Function getFileupload(ByRef Request, filepathname)
	Dim fs, fh, i

	Dim blocked_files
	blocked_files = "\.(asp|jsp|php|php3|php4|phtml|phps|cgi|sh|pl)$"

	pathname = filepathname

	Dim myfileupload
	Set myfileupload = CreateObject("Scripting.Dictionary")
	myfileupload.Item("title") = ""
	myfileupload.Item("filename") = ""
	myfileupload.Item("filenameextension") = ""
	myfileupload.Item("upload_filename") = ""
	myfileupload.Item("server_filename") = ""

	' PURE ASP UPLOAD
	Dim Connection
	Dim AdodbVersion
	AdodbVersion = 0
	On Error Resume Next
	Set Connection = CreateObject("ADODB.Connection")
	AdodbVersion = CDbl(Connection.Version)
	Set Connection = Nothing
	On Error Goto 0
	If (AdodbVersion < 2.5) Then
		Set myfileupload = ASPfileupload(Request)
	Else
		Set myfileupload = ASPfileupload2(Request)
	End If

	If ((filepathname <> "") AND (myfileupload.Item("file.filename") <> "")) Then
		myfileupload.Item("filename") = myfileupload.Item("file.filename")
		myfileupload.Item("basefilename") = myfileupload.Item("file.basefilename")
		myfileupload.Item("filenameextension") = myfileupload.Item("file.filenameextension")
		If (myfileupload.Item("filename") <> "") Then
			myfileupload.Item("upload_filename") = myfileupload.Item("file.fullpathname")
			myfileupload.Item("server_filename") = filepathname & myfileupload.Item("filename")
		End If

		Set fs = Server.CreateObject("Scripting.FileSystemObject")
		i = 1
		While fs.FileExists(pathname & myfileupload.Item("filename"))
			i = i+1
			myfileupload.Item("filename") = myfileupload.Item("basefilename") & "_" & i & "." & myfileupload.Item("filenameextension")
			myfileupload.Item("server_filename") = filepathname & myfileupload.Item("filename")
		Wend

		Set RegEx = new RegExp
		RegEx.Global = True
		RegEx.Pattern = blocked_files
		Set Matches = regEx.Execute(myfileupload.Item("filename"))
		If (Matches.Count = 0) Then
			If (myfileupload.Exists("file.binarydata")) Then
				binary2file myfileupload.Item("file.binarydata"), pathname & myfileupload.Item("filename")
			Else
				Set fh = fs.CreateTextFile(pathname & myfileupload.Item("filename"))
				For i = 1 to LenB(myfileupload.Item("file"))
					fh.Write chr(AscB(MidB(myfileupload.Item("file"), i, 1)))
				Next
				fh.Close
			End If
		End If
	End If

	If (myfileupload.Exists("file.binarydata")) Then
		myfileupload.Item("file") = binary2string(myfileupload.Item("file.binarydata"))
	Else
		myfileupload.Item("file") = myfileupload.Item("file")
	End If

	Set getFileupload = myfileupload
End Function

%>