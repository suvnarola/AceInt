<cfinclude template="config.cfm" >
<cfset content="">
<cfif IsDefined("Form.file")>
<cffile action="upload" destination="#upload_path#" nameConflict="MakeUnique" fileField="Form.file">
<cfset filename="#cffile.serverFile#">
<cffile action="read" file="#upload_path#/#filename#" variable="content">
</cfif>
<cfscript>
	matches = REFindNoCase("<body[^>]*>((.|\r|\n)*)</body>", content, 1, True);
	if ((ArrayLen(matches.pos) GT 0) and (matches.pos[1] GT 0) and (matches.len[1] GT 0)) {
		content = Mid(content, matches.pos[1], matches.len[1]);
	}
</cfscript>
<cfinclude template="import.cfm.html" >
