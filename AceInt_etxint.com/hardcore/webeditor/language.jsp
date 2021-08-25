<%@ page import="java.net.URLEncoder" %><%
Cookie mycookie = new Cookie("AcceptLanguage", "" + URLEncoder.encode(request.getHeader("Accept-Language")));
response.addCookie(mycookie);
%>