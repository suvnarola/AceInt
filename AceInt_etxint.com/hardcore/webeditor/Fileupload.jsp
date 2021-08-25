<%@ page import="java.io.*" %>
<%@ page import="java.util.Enumeration" %>
<%@ page import="java.util.HashMap" %>
<%@ page import="java.util.Locale" %>
<%@ page import="java.util.regex.*" %>
<%@ page import="javax.servlet.*" %>
<%@ page import="javax.servlet.http.*" %>
<%!
	private HashMap getFileupload(HttpServletRequest request, String filepathname) {
		String blocked_files = "\\.(asp|jsp|php|php3|php4|cgi|sh|pl)$";
		try {
			HashMap myfileupload = new HashMap();
			
			String myforminputname = "";
			String myforminputvalue = "";
			
			ServletInputStream in = request.getInputStream();
			int contentLength = request.getContentLength();
			FileOutputStream file = null;
		
			String expected = "";
			String boundary = "";
		        byte[] bytes  = new byte[1024];
			int totalBytesRead = 0;
			int lastBytesRead = 0;
			while ((totalBytesRead < contentLength) && (lastBytesRead > -1)) {
				try {
					lastBytesRead = in.readLine(bytes, 0, bytes.length);
				} catch (IOException e) {
				}
				String data = "";
				try {
					data = new String(bytes, "ISO-8859-1").substring(0,lastBytesRead);
				} catch (UnsupportedEncodingException e) {
				}
				totalBytesRead += lastBytesRead;
		
				if (expected.equals("")) {
					boundary = "" + data.replaceAll("[\\r\\n]", "");
					expected = "Content-Disposition";
				} else if (expected.equals("Content-Disposition")) {
					HashMap myforminput = new HashMap();
					if (Pattern.compile("Content-Disposition: form-data;").matcher(data).find()) {
						Pattern p = Pattern.compile("([^ =]+)=\"([^\"]+)\"");
						Matcher m = p.matcher(data);
						while (m.find()) {
							String name = m.group(1);
							String value = m.group(2);
							myforminput.put(name, value);
						}
					}
					myforminputname = "" + myforminput.get("name");
					myforminputvalue = "";
					if (myforminput.get("filename") != null) {
						myfileupload.put(myforminputname, "");
						myfileupload.put("name", myforminputname);
						String filename = "" + myforminput.get("filename");
						myfileupload.put(myforminputname + ".fullpathname", filename);
						filename = filename.substring(filename.lastIndexOf("\\")+1);
						myfileupload.put(myforminputname + ".filename", filename);
						if (filename.lastIndexOf(".") == -1) {
							myfileupload.put(myforminputname + ".basefilename", filename);
							myfileupload.put(myforminputname + ".filenameextension", "");
						} else {
							myfileupload.put(myforminputname + ".basefilename", filename.substring(0, filename.lastIndexOf(".")));
							myfileupload.put(myforminputname + ".filenameextension", filename.substring(filename.lastIndexOf(".")+1));
						}
		
						myfileupload.put(myforminputname + ".upload_filename", "" + myforminput.get("filename"));
						myfileupload.put(myforminputname + ".server_filename", "" + filepathname + myfileupload.get(myforminputname + ".filename"));
	
						if (! filepathname.equals("")) {
							java.io.File f = new java.io.File(filepathname + myfileupload.get(myforminputname + ".filename"));
							int i = 1;
							while (f.exists()) {
								i = i + 1;
								myfileupload.put(myforminputname + ".filename", "" + myfileupload.get(myforminputname + ".basefilename") + "_" + i + "." + myfileupload.get(myforminputname + ".filenameextension"));
								myfileupload.put(myforminputname + ".server_filename", "" + filepathname + myfileupload.get(myforminputname + ".filename"));
								f = new java.io.File(filepathname + myfileupload.get(myforminputname + ".filename"));
							}
							filename = "" + myfileupload.get(myforminputname + ".filename");
			
							if (! Pattern.compile(blocked_files).matcher(filename).find()) {
								try {
									file = new FileOutputStream(filepathname + filename);
								} catch (FileNotFoundException e) {
									file = null;
								}
							} else {
								file = null;
							}
						}
					}
					expected = "blank";
				} else if (expected.equals("blank")) {
					if (! Pattern.compile("^[\\r\\n]*$").matcher(data).find()) {
						Pattern p = Pattern.compile("^([^:]+): (.*?)$");
						Matcher m = p.matcher(data);
						if (m.find()) {
							String name = m.group(1);
							String value = m.group(2);
							myfileupload.put(myforminputname + "." + name, value);
						}
					} else {
						expected = "Content";
					}
				} else if (expected.equals("Content")) {
					if (! Pattern.compile("^" + boundary + "(--)?$").matcher(data).find()) {
						if (file != null) {
							if (totalBytesRead >= contentLength - boundary.length() - 4) {
								if (lastBytesRead > 2) {
									try {
										file.write(bytes, 0, lastBytesRead-2);
									} catch (IOException e) {
									}
								}
							} else {
								if (lastBytesRead > 0) {
									try {
										file.write(bytes, 0, lastBytesRead);
									} catch (IOException e) {
									}
								}
							}
							myforminputvalue += data;
						} else {
							myforminputvalue += data;
						}
					} else {
						if (file != null) {
							try {
								file.close();
							} catch (IOException e) {
							}
							file = null;
						}
						Pattern p = Pattern.compile("^(?s)(.*?)$");
						Matcher m = p.matcher(myforminputvalue);
						if (m.find()) {
							myfileupload.put(myforminputname, m.group(1));
						}
						myforminputname = "";
						myforminputvalue = "";
						expected = "Content-Disposition";
					}
				}
			}
			if (myfileupload.get("file") != null) {
				return myfileupload;
			} else {
				return new HashMap();
			}
		} catch(IOException e) {
			return new HashMap();
		}
	}
%>