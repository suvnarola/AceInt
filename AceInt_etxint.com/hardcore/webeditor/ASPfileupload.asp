<%
	Function ASPfileupload(ByRef Request)
		Dim ASPfileupload_filepost
		Set ASPfileupload_filepost = CreateObject("Scripting.Dictionary")
	
		Dim iPos
		Dim vData, vSeparator
		vData = Request.BinaryRead(Request.TotalBytes)
		Call fnParsePostContents(vSeparator, vData)

		'Get Each INPUT Contents
		Do While LenB(vData) > 0
			iPos = InStrB(1, vData, ChrB(13) & ChrB(10) & vSeparator & ChrB(13) & ChrB(10))
			Call fnParseInputContents(MidB(vData, 1, iPos - 1), ASPfileupload_filepost)
			vData = MidB(vData, iPos + 2 + LenB(vSeparator) + 2)
		Loop
		Set ASPfileupload = ASPfileupload_filepost
	End Function
	
	Sub fnParsePostContents(ByRef p_vSeparator, ByRef p_vData)
		Dim iPos
		
		iPos = InStrB(1, p_vData, ChrB(13) & ChrB(10))
		If (iPos > 0) Then
			p_vSeparator = MidB(p_vData, 1, iPos - 1)
			p_vData = MidB(p_vData, iPos + 2, LenB(p_vData) - LenB(p_vSeparator) - 2 - 4) & ChrB(13) & ChrB(10)
		End If
	End Sub

	Sub fnParseInputContents(ByRef p_vInput, ByRef filepost)
		Dim bFileUploaded
		Dim sContentDisposition
		Dim sContentType
		Dim vContent
		Dim iPosS, iPosF
		
		iPosS = 1
		iPosF = InStrB(1, p_vInput, ChrB(13) & ChrB(10))
		sContentDisposition = fnConvertArrayToString(MidB(p_vInput, iPosS, iPosF - iPosS))
		
		iPosS = iPosF + 2
		If Chr(AscB(MidB(p_vInput, iPosS, 1))) = "C" Then
			iPosF = InStrB(iPosS, p_vInput, ChrB(13) & ChrB(10) & ChrB(13) & ChrB(10))
			sContentType = fnConvertArrayToString(MidB(p_vInput, iPosS, iPosF - iPosS))
			vContent = MidB(p_vInput, iPosF + 4)
			bFileUploaded = True
		Else
			sContentType = ""
			vContent = fnConvertArrayToString(MidB(p_vInput, iPosF + 4))
			bFileUploaded = False
		End If

		Dim name, filename
		Dim i1, i2
		i1 = InStr(1, sContentDisposition, " name=""")
		If (i1 > 0) Then
			i2 = InStr(i1+7, sContentDisposition, """")
			name = Mid(sContentDisposition, i1+7, (i2-i1)-7)
		Else
			name=""
		End If
		i1 = InStr(1, sContentDisposition, " filename=""")
		If (i1 > 0) Then
			i2 = InStr(i1+11, sContentDisposition, """")
			filename = Mid(sContentDisposition, i1+11, (i2-i1)-11)
		Else
			filename = ""
		End If
			
		If (Len(name) > 0) Then
			filepost.Add name, vContent
		End If
		If (Len(filename) > 0) Then
			filepost.Add name & ".fullpathname", filename

			If (InStrRev(filename, "\")>1) Then
				filepost.Add name & ".filename", Mid(filename, InStrRev(filename, "\")+1)
			Else
				filepost.Add name & ".filename", filename
			End If

			If (InStrRev(filepost.Item(name & ".filename"), ".")>1) Then
				filepost.Add name & ".filenameextension", Mid(filepost.Item(name & ".filename"), InStrRev(filepost.Item(name & ".filename"), ".")+1)
				filepost.Add name & ".basefilename", Mid(filepost.Item(name & ".filename"), 1, InStrRev(filepost.Item(name & ".filename"), ".")-1)
			Else
				filepost.Add name & ".basefilename", filename
				filepost.Add name & ".filenameextension", ""
			End If
		End If
	End Sub
	
	Function fnConvertArrayToString(vArray)
		Dim sString
		Dim iPos
		
		iPos = 1
		sString = ""
		Do While iPos <= LenB(vArray)
			sString = sString & Chr(AscB(MidB(vArray, iPos, 1)))
			iPos = iPos + 1
		Loop
		
		fnConvertArrayToString = sString
	End Function
%>
