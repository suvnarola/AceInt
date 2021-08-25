<cfinclude template="config.cfm" >
<cfscript>
	images = "";
	hidden_paths = "^$";
	if (exclude_paths is not "") {
		hidden_paths = "^(" & Replace(exclude_paths, ",", "|", "ALL") & ")(/.*)?$";
	}
	if (root_path is not "") {
		if (images_path is not "") {
			if (DirectoryExists(root_path & images_path)) {
				images = root_path & images_path;
			}
		}
	}
</cfscript>

<cffunction name="folderMenu">
	<cfargument name="base_path" type="string">
	<cfargument name="dh" type="string">
	<cfargument name="menu" type="string">
	<cfargument name="text" type="string">
	<cfargument name="section" type="string">
	<cfif dh is not "">
		<cfscript>
		WriteOutput(menu & " = menuitem;" & Chr(13) & Chr(10));
		WriteOutput("medialistfilterMenu.add(menuitem++,menuitem_images," & text & ",'javascript:openit(\'" & section & "\',\'' + " & text & " + '\')','','',true,'imgfolder.gif');" & Chr(13) & Chr(10));
		folderSubMenu(base_path, "", dh, menu, text, section);
		</cfscript>
	</cfif>
</cffunction>
<cffunction name="folderSubMenu">
	<cfargument name="base_path" type="string">
	<cfargument name="path" type="string">
	<cfargument name="dh" type="string">
	<cfargument name="menu" type="string">
	<cfargument name="text" type="string">
	<cfargument name="section" type="string">
	<cfscript>var i = 0;</cfscript>
	<cfscript>var entry = false;</cfscript>
	<cfif dh is not "">
		<cfset i = 0>
		<cfdirectory action="list" directory="#dh#" name="entry" sort="name ASC">
		<cfloop query="entry">
			<cfif REFindNoCase(hidden_paths, base_path & path & entry.Name) GT 0>
			<cfelseif entry.type is "Dir">
				<cfset i = i + 1>
				<cfscript>
				WriteOutput(menu & "_" & i & " = menuitem;" & Chr(13) & Chr(10));
				WriteOutput("medialistfilterMenu.add(menuitem++," & menu & ",'" & entry.Name & "','javascript:openit(\'" & path & entry.Name & "/" & "\',\'" & entry.Name & "\')','','','','imgfolder.gif');" & Chr(13) & Chr(10));
				subdh = root_path & base_path & path & entry.Name;
				folderSubMenu(base_path, path & entry.Name & "/", subdh, menu & "_" & i, "'" & entry.Name & "'", section);
				</cfscript>
			</cfif>
		</cfloop>
	</cfif>
</cffunction>
<cfinclude template="medialistfilter.cfm.html" >
