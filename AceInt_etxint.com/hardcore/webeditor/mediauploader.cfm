<cfinclude template="config.cfm" >
<cfscript>
	valid_extensions="";
	if (images_path is not "") {
		valid_extensions = image_formats;
	}
</cfscript>
<cfinclude template="mediauploader.cfm.html" >
