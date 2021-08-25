<cfinclude template="config.cfm" >
<cfscript>
	links = "";
	links_path = "";
	section_path = "";
	allowed_files = "";
	if ((root_path is not "") and (IsDefined("URL.section"))) {
		if ((pages_path is not "") and (URL.section is "Pages")) {
			section_path = pages_path;
			allowed_files = "\.(" & Replace(page_formats, ",", "|", "ALL") & ")$";
		} else if ((images_path is not "") and (URL.section is "Images")) {
			section_path = images_path;
			allowed_files = "\.(" & Replace(image_formats, ",", "|", "ALL") & ")$";
		} else if ((files_path is not "") and (URL.section is "Files")) {
			section_path = files_path;
			allowed_files = "\.(" & Replace(file_formats, ",", "|", "ALL") & ")$";
		}
		if (section_path is not "") {
			if (DirectoryExists(root_path & section_path & URL.category)) {
				links = root_path & section_path & URL.category;
				if (URL.category is "/") {
					links_path = section_path;
				} else {
					links_path = section_path & URL.category;
				}
			}
		}
	}
</cfscript>
<cfinclude template="hyperlinklist.cfm.html" >
