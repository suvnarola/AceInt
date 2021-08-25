<!-- #include file="config.asp" -->
<%
	If ((Len(Request.Form) > 0) AND (spellcheckCommand <> "")) Then
		If (Request.Form("dictionary") <> "") Then
			spellcheckParameters = spellcheckParameters & " " & spellcheckDictionary & " " & Request.Form("dictionary")
		End If

		Dim content
		content = Request.Form("content")

		Dim contentCurrentLine, contentNextLine
		contentCurrentLine = 0
		contentNextLine = 1

		Dim spellcheckContent, mycontent, myline
		spellcheckContent = ""
		mycontent = ""
		myline = nextContentLine()
		While (myline <> "")
			spellcheckContent = spellcheckContent & "^" & myline & vbcrlf
			mycontent = mycontent & myline & vbcrlf
			myline = nextContentLine()
		Wend
		content = mycontent

		Dim fs, fh, filename
		Set fs = Server.CreateObject("Scripting.FileSystemObject")
		filename = fs.GetSpecialFolder(2) & "\" & fs.GetTempName
		Set fh = fs.OpenTextFile(filename, 2, True, 0)
		fh.Write spellcheckContent
		fh.Close

		Dim shell
		Set shell = Server.CreateObject("Wscript.Shell")
		shell.Run "cmd.exe /c " & spellcheckCommand & " " & spellcheckParameters & " <" & filename & " >" & filename & ".spellcheck.txt", 0, True
		Set shell = nothing

		Dim jsMisspelled
		jsMisspelled = ""

		contentCurrentLine = 0
		contentNextLine = 1

		Dim misspelledIndex, misspelledCount, misspelledOffset, misspelled, suggestions
		misspelledCount = 0
		misspelled = ""
		suggestions = ""
		Set fh = fs.OpenTextFile(filename & ".spellcheck.txt", 1, False, 0)
		If (Not fh.AtEndOfStream) Then myline = fh.ReadLine
		nextContentLine()
		While (Not fh.AtEndOfStream)
			myline = fh.ReadLine
			misspelled = ""
			suggestions = ""
			If (myline = "") Then 
				nextContentLine()
			ElseIf (Left(myline,1) = "&") Then
				misspelledIndex = InStrRev(myline, " ", InStr(1, myline, ":"))+1
				misspelled = Mid(myline,3,InStr(3,myline," ")-3)
				misspelledOffset = Mid(myline,misspelledIndex,InStr(1,myline,":")-misspelledIndex)
				suggestions = Right(myline, Len(myline)-InStr(1, myline, ":")-1)
			ElseIf (Left(myline,1) = "#") Then
				misspelledIndex = InStr(3,myline," ")+1
				misspelled = Mid(myline,3,InStr(3,myline," ")-3)
				misspelledOffset = Mid(myline,misspelledIndex,len(myline)-misspelledIndex+1)
				suggestions = ""
			End If
			If ((Left(myline,1) = "&") OR (Left(myline,1) = "#")) Then
				misspelled = Server.HTMLEncode(Replace(Replace(Replace(misspelled,"'","\'"),"""","\"""),", ",","))
				suggestions = Server.HTMLEncode(Replace(Replace(Replace(suggestions,"'","\'"),"""","\"""),", ",","))
				If (IsNumeric(misspelledOffset)) Then
					misspelledOffset = CLng(misspelledOffset) + contentCurrentLine - 2
				Else
					misspelledOffset = contentCurrentLine
				End If
				jsMisspelled = jsMisspelled & "misspelled[" & misspelledCount & "] = new misspelledItem(" & misspelledOffset & ",""" & misspelled & """,""" & suggestions & """);" & vbCRLF
				misspelledCount = misspelledCount + 1
			End If
		Wend
		fh.Close

		On Error Resume Next
		fs.DeleteFile filename, True
		fs.DeleteFile filename & ".spellcheck.txt", True
		On Error Goto 0
	End If

	Function nextContentLine
		Dim myline, EOL
		EOL = False
		myline = ""
		While ((myline = "") AND (Len(content)+1-contentNextLine > 0))
			EOL = InStr(contentNextLine,content,vbLF)
			If (EOL < 1) Then EOL = Len(content)+1
			If (EOL-contentNextLine > 0) Then
				myline = Mid(content,contentNextLine,EOL-contentNextLine)
				If (Left(myline,1) = vbCR) Then myline = Right(myline,Len(myline)-1)
				If (Right(myline,1) = vbCR) Then myline = Left(myline,Len(myline)-1)
				contentCurrentLine = contentNextLine
				contentNextLine = EOL + 1
			End If
		Wend
		nextContentLine = myline
	End Function
%>
<%
	If (Len(Request.Form) > 0) Then
%>
<!-- #include file="spellcheck.asp.post.html" -->
<%
	Else
%>
<!-- #include file="spellcheck.asp.get.html" -->
<%
	End If
%>
