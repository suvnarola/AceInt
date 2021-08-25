<%@ Page Language="C#" %>
<html>
<head>
<title>HardCore Web Content Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Generator" content="HardCore Web Content Editor">
<meta http-equiv="Copyright" content="(C) 2002-2004 - HardCore Internet Ltd. - www.hardcoreinternet.co.uk">
<% if (Request.Form("stylesheet") != "") { %><link rel="stylesheet" type="text/css" href="<%= Request.Form("stylesheet") %>" /><% } %>
</head>
<body style="margin: 0px;">
<%= Request.Form("content") %>
</body>
</html>
