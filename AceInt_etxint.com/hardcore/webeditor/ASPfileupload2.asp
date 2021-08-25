<%
	Function ASPfileupload2(ByRef Request)
		Dim myfileupload
		Set myfileupload = CreateObject("Scripting.Dictionary")
		Set ASPfileupload2 = myfileupload
	
		contentBytes = Request.BinaryRead(Request.TotalBytes)
		If (LenB(contentBytes) = 0) Then Exit Function
		contentDelimiter = MidB(contentBytes, 1, InStrB(1, contentBytes, CRLF) - 1)
	
		Dim s
		Dim e
		Dim l
		
		s = InStrB(1, contentBytes, contentDelimiter & CRLF)
		While (s <> 0)
			Dim contentPart
			Dim contentDisposition
			Dim name
			Dim filename
			Dim contentType
			Dim stringValue
			Dim binaryValue

			e = InStrB(s + 1, contentBytes, contentDelimiter) - 2
			l = e - s
			contentPart = MidB(contentBytes, s, l)

			contentDisposition = getContentDisposition(contentPart)
			name = getContentDispositionName(contentDisposition)
			filename = getContentDispositionFilename(contentDisposition)
			contentType = getContentType(contentPart)
			If (contentType = "") Then
				stringValue = binary2string(getContent(contentPart))
				myfileupload.Add name, stringValue
			Else
				binaryValue = getContent(contentPart)
				myfileupload.Add name, binaryValue
			End If
	
			If (Len(filename) > 0) Then
				myfileupload.Item(name & ".fullpathname") = filename

				If (InStrRev(filename, "\")>1) Then
					myfileupload.Add name & ".filename", Mid(filename, InStrRev(filename, "\")+1)
				Else
					myfileupload.Add name & ".filename", filename
				End If

				If (InStrRev(myfileupload.Item(name & ".filename"), ".")>1) Then
					myfileupload.Add name & ".basefilename", Mid(myfileupload.Item(name & ".filename"), 1, InStrRev(myfileupload.Item(name & ".filename"), ".")-1)
					myfileupload.Add name & ".filenameextension", Mid(myfileupload.Item(name & ".filename"), InStrRev(myfileupload.Item(name & ".filename"), ".")+1)
				Else
					myfileupload.Add name & ".basefilename", filename
					myfileupload.Add name & ".filenameextension", ""
				End If

				myfileupload.Item(name & ".contenttype") = contentType
				If (LenB(binaryValue) = 0) Then
					myfileupload.Item(name & ".binarydata") = ChrB(0)
					myfileupload.Item(name & ".length") = Len(stringValue)
					myfileupload.Item(name & ".value") = stringValue
				Else
					myfileupload.Item(name & ".binarydata") = binaryValue
					myfileupload.Item(name & ".length") = LenB(binaryValue)
					myfileupload.Item(name & ".value") = ""
				End If
			End If
	
			s = InStrB(s + 1, contentBytes, contentDelimiter & CRLF)
		Wend

		Set ASPfileupload2 = myfileupload
	End Function

	Function getContent(ByRef binarydata)
		getContent = ""
		Dim s
		s = InStrB(1, binarydata, CRLF & CRLF)
		If (s <> 0) Then
			s = s + 4
			getContent = MidB(binarydata, s)
		End If
	End Function

	Function getContentType(ByRef binarydata)
		getContentType = ""
		Dim s
		Dim e
		Dim l
		s = InStrB(1, binarydata, CRLF & string2binary("Content-Type:"), vbTextCompare)
		If (s <> 0) Then
			s = s + 15
			e = InStrB(s, binarydata, CRLF)
			If (e <> 0) Then
				If (s < e) Then
					l = e - s
					getContentType = Trim(binary2string(MidB(binarydata, s, l)))
				End If
			End If
		End If
	End Function

	Function getContentDisposition(ByRef binarydata)
		getContentDisposition = ""
		Dim s
		Dim e
		Dim l
		s = InStrB(1, binarydata, CRLF & string2binary("Content-Disposition:"), vbTextCompare)
		If (s <> 0) Then
			s = s + 22
			e = InStrB(s, binarydata, CRLF)
			If (e <> 0) Then
				If (s < e ) Then
					l = e - s
					getContentDisposition = binary2string(MidB(binarydata, s, l))
				End If
			End If
		End If
	End Function

	Function getContentDispositionName(ByRef contentDisposition)
		getContentDispositionName = ""
		Dim s
		Dim e
		Dim l
		s = InStr(1, contentDisposition, "name=""", vbTextCompare)
		If (s <> 0) Then
			s = s + 6
			e = InStr(s, contentDisposition, """")
			If (e <> 0) Then
				If (s < e) Then
					l = e - s
					getContentDispositionName = Mid(contentDisposition, s, l)
				End If
			End If
		End If
	End Function

	Function getContentDispositionFilename(ByRef contentDisposition)
		getContentDispositionFilename = ""
		Dim s
		Dim e
		Dim l
		s = InStr(1, contentDisposition, "filename=""", vbTextCompare)
		If (s <> 0) Then
			s = s + 10
			e = InStr(s, contentDisposition, """")
			If (e <> 0) Then
				If (s < e) Then
					l = e - s
					getContentDispositionFilename = Mid(contentDisposition, s, l)
				End If
			End If
		End If
	End Function

	Function string2binary(ByRef data)
		string2binary = ""
		Dim i
		For i = 1 To Len(data)
			string2binary = string2binary & ChrB(Asc(Mid(data, i, 1)))
		Next
		
	End Function

'	Function binary2string(ByRef data)
'		binary2string = ""
'		Dim i
'		For i = 1 To LenB(data)
'			binary2string = binary2string & Chr(AscB(MidB(data, i, 1)))
'		Next
'	End Function
	Function binary2string(data)
		If (vartype(data) = 8) Then data = string2bytes(data)
		Const adLongVarChar = 201
		Dim rs
		Set rs = Server.CreateObject("ADODB.Recordset")
		If (LenB(data) > 0) Then
			rs.Fields.Append "data", adLongVarChar, LenB(data)
			rs.Open()
			rs.AddNew()
			rs.Fields("data").AppendChunk(data)
			rs.Update()
			binary2string = rs.Fields("data")
			rs.Close()
			Set rs = Nothing
		Else
			binary2string = ""
		End If
	End Function

	Function string2bytes(ByRef data)
		Const adLongVarBinary = 205
		Dim rs
		Set rs = Server.CreateObject("ADODB.Recordset")
		If (LenB(data) > 0) Then
			rs.Fields.Append "data", adLongVarBinary, LenB(data)
			rs.Open()
			rs.AddNew()
			rs.Fields("data").AppendChunk(data & ChrB(0))
			rs.Update()
			string2bytes = rs.Fields("data").GetChunk(LenB(data))
			rs.Close()
			Set rs = Nothing
		Else
			string2bytes = ""
		End If
	End Function

	Function binary2file(ByRef data, filename)
		Const adTypeBinary = 1
		Const adSaveCreateOverwrite = 2
		Const adModeReadWrite = 3
		Dim st
		Set st = Server.CreateObject("ADODB.Stream")
		st.Type = adTypeBinary
		Call st.Open()
		Call st.Write(string2bytes(data))
		Call st.SaveToFile(filename, adSaveCreateOverWrite)
		Call st.Close()
	End Function

	Function CRLF()
		Dim CR, LF
		CR = ChrB(Asc(vbCr))
		LF = ChrB(Asc(vbLf))
		CRLF = CR & LF
	End Function
%>
