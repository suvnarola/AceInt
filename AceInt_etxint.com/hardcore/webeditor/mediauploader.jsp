<%@ include file="config.jsp" %>
<%
	String valid_extensions = "";
	if (! images_path.equals("")) {
		valid_extensions = image_formats;
	}
%>
<%@ include file="mediauploader.jsp.html" %>
