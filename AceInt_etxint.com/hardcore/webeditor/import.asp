<!-- #include file="Fileupload.asp" -->
<%
	content = ""
	Set myfileupload = getFileupload(Request, "")
	content = "" & myfileupload.Item("file")

	Set RegEx = new RegExp
	RegEx.Global = True
	RegEx.IgnoreCase = True
	RegEx.Pattern = "^(.|\r\n)*<body[^>]*>((.|\r|\n)*)</body>"
	Set Matches = regEx.Execute(content)
	If (Matches.Count > 0) Then
		content = Matches.Item(0).SubMatches(1)
	End If
%> 
<!-- #include file="import.asp.html" -->
