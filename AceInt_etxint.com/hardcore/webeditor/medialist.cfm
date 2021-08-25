<cfinclude template="config.cfm" >
<cfscript>
	links = "";
	links_path = "";
	section_path = "";
	allowed_files = "";
	if (root_path is not "") {
		if (images_path is not "") {
			section_path = images_path;
			allowed_files = "\.(" & Replace(image_formats, ",", "|", "ALL") & ")$";
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
<cfinclude template="medialist.cfm.html" >
