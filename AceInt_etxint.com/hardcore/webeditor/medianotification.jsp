<%@ include file="config.jsp" %>
<%@ include file="Fileupload.jsp" %>
<%@ page import="java.io.File" %>
<%@ page import="java.util.HashMap" %>
<%
	// Set href to URL for image
	String href = "";

	String error = "";

	HashMap myfileupload = new HashMap();
	String section_path = "";
	String allowed_files = "";
	if ((! root_path.equals("")) && (! upload_path.equals("")) && (enable_upload.equals("yes"))) {
		myfileupload = getFileupload(request, upload_path);
		if (! images_path.equals("")) {
			section_path = images_path;
			allowed_files = "\\.(" + image_formats.replaceAll(",", "|") + ")$";
		}
		href = context + section_path + myfileupload.get("category") + myfileupload.get("title");
		Pattern p = Pattern.compile(allowed_files, Pattern.MULTILINE | Pattern.CASE_INSENSITIVE | Pattern.DOTALL);
		if (! section_path.equals("")) {
			if ((myfileupload.get("action").equals("Create")) && (myfileupload.get("title") != null) && (! myfileupload.get("title").equals(""))) {
				Matcher m = p.matcher("" + myfileupload.get("title"));
				File fh = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("title"));
				if ((m.find()) && (! myfileupload.get("file.filename").equals("")) && (! fh.exists())) {
					new File(upload_path + myfileupload.get("file.filename")).renameTo(fh);
				}
			} else if ((myfileupload.get("action").equals("Update")) && (myfileupload.get("old_title") != null) && (! myfileupload.get("old_title").equals("")) && (myfileupload.get("title") != null) && (! myfileupload.get("title").equals(""))) {
				Matcher m = p.matcher("" + myfileupload.get("title"));
				if (m.find()) {
					if ((myfileupload.get("file.filename") != null) && (! myfileupload.get("file.filename").equals(""))) {
						if (myfileupload.get("old_title").equals(myfileupload.get("title"))) {
							// REPLACE
							File fh = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("old_title"));
							if (fh.exists() && fh.isFile()) {
								fh.delete();
								new File(upload_path + myfileupload.get("file.filename")).renameTo(fh);
							}
						} else {
							// RENAME + REPLACE
							m = p.matcher("" + myfileupload.get("old_title"));
							File fh = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("old_title"));
							File fh2 = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("title"));
							if ((m.find()) && (fh.exists() && fh.isFile()) && (! fh2.exists())) {
								fh.delete();
								new File(upload_path + myfileupload.get("file.filename")).renameTo(fh2);
							}
						}
					} else if (! myfileupload.get("old_title").equals(myfileupload.get("title"))) {
						// RENAME
						m = p.matcher("" + myfileupload.get("old_title"));
						File fh = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("old_title"));
						File fh2 = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("title"));
						if ((m.find()) && (fh.exists() && fh.isFile()) && (! fh2.exists())) {
							fh.renameTo(fh2);
						}
					}
				}
			} else if ((myfileupload.get("action").equals("Delete")) && (myfileupload.get("old_title") != null) && (! myfileupload.get("old_title").equals(""))) {
				Matcher m = p.matcher("" + myfileupload.get("old_title"));
				File fh = new File(root_path + section_path + myfileupload.get("category") + myfileupload.get("old_title"));
				if ((m.find()) && (fh.exists() && fh.isFile())) {
					fh.delete();
				}
				href = "";
			}
		}
		if ((myfileupload.get("file.filename") != null) && (! myfileupload.get("file.filename").equals(""))) {
			File fh = new File(upload_path + myfileupload.get("file.filename"));
			if (fh.exists()) {
				fh.delete();
			}
		}
	} else {
		error = "DISABLED";
	}
%>
<%@ include file="medianotification.jsp.html" %>
