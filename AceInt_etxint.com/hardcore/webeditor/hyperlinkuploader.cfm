<cfinclude template="config.cfm" >
<cfscript>
	valid_extensions="";
	if ((pages_path is not "") and (URL.section is "Pages")) {
		valid_extensions = page_formats;
	} else if ((images_path is not "") and (URL.section is "Images")) {
		valid_extensions = image_formats;
	} else if ((files_path is not "") and (URL.section is "Files")) {
		valid_extensions = file_formats;
	}
</cfscript>
<cfinclude template="hyperlinkuploader.cfm.html" >
