<%@ include file="config.jsp" %>
<%@ page import="java.io.File" %>
<%
	String category = "";
	String title = "";
	String error = "";
	String section_path = "";
	if ((! root_path.equals("")) && (enable_upload.equals("yes")) && (request.getParameter("submit") != null)) {
		if ((! pages_path.equals("")) && (request.getParameter("section") != null) && (request.getParameter("section").equals("Pages"))) {
			section_path = pages_path;
		} else if ((! images_path.equals("")) && (request.getParameter("section") != null) && (request.getParameter("section").equals("Images"))) {
			section_path = images_path;
		} else if ((! files_path.equals("")) && (request.getParameter("section") != null) && (request.getParameter("section").equals("Files"))) {
			section_path = files_path;
		}
		if ((! section_path.equals("")) && (request.getParameter("action") != null))  {
			if ((request.getParameter("action").equals("Create")) && (request.getParameter("title") != null) && (! request.getParameter("title").equals(""))) {
				String new_category = "" + request.getParameter("category") + request.getParameter("title") + "/";
				File fh = new File(root_path + section_path + new_category);
				if (! fh.exists()) {
					fh.mkdirs();
				}
				category = "" + new_category;
				title = "" + request.getParameter("title");
			} else if ((request.getParameter("action").equals("Update")) && (request.getParameter("old_title") != null) && (! request.getParameter("old_title").equals("")) && (request.getParameter("title") != null) && (! request.getParameter("title").equals(""))) {
				String old_category = "" + request.getParameter("category");
				String new_category = "" + request.getParameter("category").replaceAll(request.getParameter("old_title") + "/$", request.getParameter("title") + "/");
				File fh = new File(root_path + section_path + old_category);
				File fh2 = new File(root_path + section_path + new_category);
				if ((fh.exists()) && (fh.isDirectory()) && (! fh2.exists())) {
					fh.renameTo(fh2);
				}
				category = "" + new_category;
				title = "" + request.getParameter("title");
			} else if ((request.getParameter("action").equals("Delete")) && (request.getParameter("old_title") != null) && (! request.getParameter("old_title").equals(""))) {
				String old_category = "" + request.getParameter("category");
				File fh = new File(root_path + section_path + old_category);
				if ((fh.exists()) && (fh.isDirectory()) && (fh.list().length == 0)) {
					fh.delete();
				}
				category = "" + old_category.replaceAll("/$", "");
				category = "" + category.replaceAll("[^/]*$", "");
				title = "" + category.replaceAll("/$", "");
				title = "" + title.replaceAll("^.*/", "");
			}
		}
	} else if (root_path.equals("")) {
		error = "DISABLED";
	}
%>
<%@ include file="hyperlinkcategory.jsp.html" %>
