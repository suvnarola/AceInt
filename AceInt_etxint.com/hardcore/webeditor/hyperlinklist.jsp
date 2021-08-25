<%@ include file="config.jsp" %>
<%@ page import="java.io.File" %>
<%@ page import="java.util.regex.*" %>
<%
	String[] links = null;
	String links_path = "";
	String section_path = "";
	String allowed_files = "";
	if (! root_path.equals("")) {
		if ((! pages_path.equals("")) && (request.getParameter("section") != null) && (request.getParameter("section").equals("Pages"))) {
			section_path = pages_path;
			allowed_files = "\\.(" + page_formats.replaceAll(",", "|") + ")$";
		} else if ((! images_path.equals("")) && (request.getParameter("section") != null) && (request.getParameter("section").equals("Images"))) {
			section_path = images_path;
			allowed_files = "\\.(" + image_formats.replaceAll(",", "|") + ")$";
		} else if ((! files_path.equals("")) && (request.getParameter("section") != null) && (request.getParameter("section").equals("Files"))) {
			section_path = files_path;
			allowed_files = "\\.(" + file_formats.replaceAll(",", "|") + ")$";
		}
		if (! section_path.equals("")) {
			File fh = new File(root_path + section_path + request.getParameter("category"));
			if (fh.exists() && fh.isDirectory()) {
				links = fh.list();
				if (request.getParameter("category").equals("/")) {
					links_path = section_path;
				} else {
					links_path = section_path + request.getParameter("category");
				}
			}
		}
	}
%>
<%@ include file="hyperlinklist.jsp.html" %>
