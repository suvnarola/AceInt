<%@ include file="config.jsp" %>
<%
	String valid_extensions = "";
	if ((! pages_path.equals("")) && (request.getParameter("section").equals("Pages"))) {
		valid_extensions = page_formats;
	} else if ((! images_path.equals("")) && (request.getParameter("section").equals("Images"))) {
		valid_extensions = image_formats;
	} else if ((! files_path.equals("")) && (request.getParameter("section").equals("Files"))) {
		valid_extensions = file_formats;
	}
%>
<%@ include file="hyperlinkuploader.jsp.html" %>
