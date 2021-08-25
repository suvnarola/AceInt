<cfinclude template="config.cfm" >
	<cfset category = "">
	<cfset title = "">
	<cfset error = "">
	<cfset section_path="">
	<cfif ((root_path is not "") and (enable_upload is "yes") and (IsDefined("URL.submit")))>
		<cfif ((pages_path is not "") and (URL.section is "Pages"))>
			<cfset section_path = pages_path>
		<cfelseif ((images_path is not "") and (URL.section is "Images"))>
			<cfset section_path = images_path>
		<cfelseif ((files_path is not "") and (URL.section is "Files"))>
			<cfset section_path = files_path>
		</cfif>
		<cfif (section_path is not "")>
			<cfif ((URL.action is "Create") and (URL.title is not ""))>
				<cfset new_category = URL.category & URL.title & "/">
				<cfif (not DirectoryExists(root_path & section_path & new_category))>
					<cfdirectory action="create" directory="#root_path & section_path & new_category#">
				</cfif>
				<cfset category = new_category>
				<cfset title = URL.title>
			<cfelseif ((URL.action is "Update") and (URL.old_title is not "") and (URL.title is not ""))>
				<cfset old_category = URL.category>
				<cfset new_category = REReplaceNoCase(URL.category, URL.old_title & "/$", URL.title & "/", "ALL")>
				<cfif ((DirectoryExists(root_path & section_path & old_category)) and (not DirectoryExists(root_path & section_path & new_category)))>
					<cfdirectory action="rename" directory="#root_path & section_path & old_category#" newDirectory="#root_path & section_path & new_category#">
				</cfif>
				<cfset category = new_category>
				<cfset title = URL.title>
			<cfelseif ((URL.action is "Delete") and (URL.old_title is not ""))>
				<cfset old_category = URL.category>
				<cfif (DirectoryExists(root_path & section_path & old_category))>
					<cfdirectory action="list" directory="#root_path & section_path & old_category#" name="folder">
					<cfif (folder.size is "") or (folder.size is 0)>
						<cfdirectory action="delete" directory="#root_path & section_path & old_category#">
					</cfif>
				</cfif>
				<cfset category = REReplaceNoCase(old_category, "/$", "", "ALL")>
				<cfset category = REReplaceNoCase(category, "[^/]*$", "", "ALL")>
				<cfset title = REReplaceNoCase(category, "/$", "", "ALL")>
				<cfset title = REReplaceNoCase(title, "^.*/", "", "ALL")>
			</cfif>
		</cfif>
	<cfelseif (root_path is "")>
		<cfset error = "DISABLED">
	</cfif>
<cfinclude template="hyperlinkcategory.cfm.html" >
