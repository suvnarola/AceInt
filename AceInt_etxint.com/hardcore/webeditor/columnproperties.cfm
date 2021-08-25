<cfinclude template="config.cfm" >
<cfscript>
	links="";
	links_path="";
	section_path="";
	allowed_files="";
	if (root_path is not "") {
		if (images_path is not "") {
			section_path = images_path;
			allowed_files = "\.(" & Replace(image_formats, ",", "|", "All") & ")$";
		}
		if (section_path is not "") {
			if (DirectoryExists(root_path & section_path)) {
				links = root_path & section_path;
				links_path = section_path;
			}
		}
	}
</cfscript>
<cfinclude template="columnproperties.cfm.html" >
