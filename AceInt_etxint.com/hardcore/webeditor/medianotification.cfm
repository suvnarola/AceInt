<cfinclude template="config.cfm" >
<cfif IsDefined("Form.file") and (Form.file is not "")>
<cffile action="upload" destination="#upload_path#" nameConflict="MakeUnique" fileField="Form.file">
<cfset filename="#cffile.serverFile#">
<cfelse>
<cfset filename="">
</cfif>
	<!--- Set href to URL for hyperlink --->
	<cfset href = "">

	<cfset error = "">

	<cfset section_path="">
	<cfset allowed_files="">
	<cfif ((root_path is not "") and (upload_path is not "") and (enable_upload is "yes"))>
		<cfif (images_path is not "")>
			<cfset section_path = images_path>
			<cfset allowed_files = "\.(" & Replace(image_formats, ",", "|", "All") & ")$">
		</cfif>
		<cfif IsDefined("Form.title")>
		<cfset href = section_path & Form.category & Form.title>
		<cfelse>
		<cfset href = section_path & Form.category>
		</cfif>
		<cfif (section_path is not "")>
			<cfif ((Form.action is "Create") and (Form.title is not ""))>
				<cfif ((REFindNoCase(allowed_files, Form.title) GT 0) and (filename is not "") and (not FileExists(root_path & section_path & Form.category & Form.title)))>
					<cffile action="move" source="#upload_path & filename#" destination="#root_path & section_path & Form.category & Form.title#">
				</cfif>
			<cfelseif ((Form.action is "Update") and (Form.old_title is not "") and (Form.title is not ""))>
				<cfif (REFindNoCase(allowed_files, Form.title) GT 0)>
					<cfif (filename is not "")>
						<cfif (Form.old_title is Form.title)>
							<!--- REPLACE --->
							<cfif (FileExists(root_path & section_path & Form.category & Form.old_title))>
								<cffile action="delete" file="#root_path & section_path & Form.category & Form.old_title#">
								<cffile action="move" source="#upload_path & filename#" destination="#root_path & section_path & Form.category & Form.title#">
							</cfif>
						<cfelse>
							<!--- RENAME + REPLACE --->
							<cfif ((REFindNoCase(allowed_files, Form.title) GT 0) and (FileExists(root_path & section_path & Form.category & Form.old_title)) and (not FileExists(root_path & section_path & Form.category & Form.title)))>
								<cffile action="delete" file="#root_path & section_path & Form.category & Form.old_title#">
								<cffile action="move" source="#upload_path & filename#" destination="#root_path & section_path & Form.category & Form.title#">
							</cfif>
						</cfif>
					<cfelseif (Form.old_title is not Form.title)>
						<!--- RENAME --->
						<cfif ((REFindNoCase(allowed_files, Form.old_title) GT 0) and (FileExists(root_path & section_path & Form.category & Form.old_title)) and (not FileExists(root_path & section_path & Form.category & Form.title)))>
							<cffile action="move" source="#root_path & section_path & Form.category & Form.old_title#" destination="#root_path & section_path & Form.category & Form.title#">
						</cfif>
					</cfif>
				</cfif>
			<cfelseif ((Form.action is "Delete") and (Form.old_title is not ""))>
				<cfif ((REFindNoCase(allowed_files, Form.old_title) GT 0) and (FileExists(root_path & section_path & Form.category & Form.old_title)))>
					<cffile action="delete" file="#root_path & section_path & Form.category & Form.old_title#">
				</cfif>
				<cfset href = "">
			</cfif>
		</cfif>
		<cfif filename is not "">
			<cfif FileExists(upload_path & filename)>
				<cffile action="delete" file="#upload_path & filename#">
			</cfif>
		</cfif>
	<cfelse>
		<cfset error = "DISABLED">
	</cfif>
<cfinclude template="medianotification.cfm.html" >
