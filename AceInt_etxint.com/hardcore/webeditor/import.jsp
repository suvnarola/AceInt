<%@ page import="java.util.HashMap" %>
<%@ include file="Fileupload.jsp" %>
<%
	String content = "";
	HashMap myfileupload = getFileupload(request, "");
	if ((myfileupload != null) && (myfileupload.get("file") != null)) content = "" + myfileupload.get("file");

	Pattern p = Pattern.compile("^.*<body[^>]*>(.*)</body>.*$", Pattern.MULTILINE | Pattern.CASE_INSENSITIVE | Pattern.DOTALL);
	Matcher m = p.matcher(content);
	if (m.find()) {
		content = m.group(1);
	}
%>
<%@ include file="import.jsp.html" %>
