<%@ include file="config.jsp" %>
<%@ page import="java.io.File" %>
<%@ page import="java.util.regex.*" %>
<%
	String hidden_paths = "^$";
	String[] images = null;
	if (! exclude_paths.equals("")) {
		hidden_paths = "^(" + exclude_paths.replaceAll(",", "|") + ")(/.*)?$";
	}
	if (! root_path.equals("")) {
		if (! images_path.equals("")) {
			File fh = new File(root_path + images_path);
			if (fh.exists() && fh.isDirectory()) {
				images = fh.list();
			}
		}
	}
%><%!

	public String folderMenu(String root_path, String hidden_paths, String base_path, String[] dh, String menu, String text, String section) {
		String output = "";
		if (dh != null) {
			output = output + menu + " = menuitem;" + "\r\n";
			output = output + "medialistfilterMenu.add(menuitem++,menuitem_images," + text + ",'javascript:openit(\\'" + section + "\\',\\'' + " + text + " + '\\')','','',true,'imgfolder.gif');" + "\r\n";
			output = output + folderSubMenu(root_path, hidden_paths, base_path, "", dh, menu, text, section);
		}
		return output;
	}

	public String folderSubMenu(String root_path, String hidden_paths, String base_path, String path, String[] dh, String menu, String text, String section) {
		String output = "";
		if (dh != null) {
			int i = 0;
			for (int folder=0; folder<dh.length; folder++) {
				String entry = dh[folder];
				File di = new File(root_path + base_path + path + entry);
				if (Pattern.compile(hidden_paths, Pattern.CASE_INSENSITIVE).matcher("" + base_path + path + entry).find()) {
					// ignore
				} else if ((di.isDirectory()) && (! entry.equals(".")) && (! entry.equals(".."))) {
					i = i + 1;
					output = output + menu + "_" + i + " = menuitem;" + "\r\n";
					output = output + "medialistfilterMenu.add(menuitem++," + menu + ",'" + entry.replaceAll("'", "\\\\'") + "','javascript:openit(\\'" + path + entry + "/" + "\\',\\'" + entry + "\\')','','','','imgfolder.gif');" + "\r\n";
					String[] subdh = di.list();
					output = output + folderSubMenu(root_path, hidden_paths, base_path, path + entry + "/", subdh, menu + "_" + i, "'" + entry + "'", section);
				}
			}
		}
		return output;
	}
%>
<%@ include file="medialistfilter.jsp.html" %>
