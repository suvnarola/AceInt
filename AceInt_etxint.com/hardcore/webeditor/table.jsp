<%@ include file="config.jsp" %>
<%@ page import="java.io.File" %>
<%@ page import="java.net.URLEncoder" %>
<%@ page import="java.util.regex.*" %>
<%
	String[] links = null;
	String links_path = "";
	String section_path = "";
	String allowed_files = "";
	Pattern p = null;
	if (! root_path.equals("")) {
		if (! images_path.equals("")) {
			section_path = images_path;
			allowed_files = "\\.(" + image_formats.replaceAll(",", "|") + ")$";
		}
		p = Pattern.compile(allowed_files, Pattern.MULTILINE | Pattern.CASE_INSENSITIVE | Pattern.DOTALL);
		if (! section_path.equals("")) {
			File fh = new File(root_path + section_path);
			if (fh.exists() && fh.isDirectory()) {
				links = fh.list();
				links_path = section_path;
			}
		}
	}
%>
<%@ include file="table.jsp.html" %>
