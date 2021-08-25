<cfinclude template="config.cfm" >
	<cfif (IsDefined("Form.dictionary") and (spellcheckCommand is not ""))>
		<cfif (Form.dictionary is not "")>
			<cfset spellcheckParameters = spellcheckParameters & " " & spellcheckDictionary & " " & Form.dictionary>
		</cfif>

		<cfset content = Form.content>

		<cfset contentCurrentLine = 0>
		<cfset contentNextLine = 1>

		<cfscript>
		spellcheckContent = "";
		mycontent = "";
		myline = nextContentLine();
		while (myline is not "") {
			spellcheckContent = spellcheckContent & "^" & myline & Chr(13) & Chr(10);
			mycontent = mycontent & myline & Chr(13) & Chr(10);
			myline = nextContentLine();
		}
		content = mycontent;
		</cfscript>

		<cfset filename = "#GetTempDirectory()#HardCore_spellcheck_" & RandRange(0,999999999)>
		<cffile action="write" file="#filename#" output="#spellcheckContent#" addNewLine="No">

		<cfif shellCommand is "">
		<cfexecute name="#spellcheckCommand#" arguments="#spellcheckParameters# <#filename# >#filename#.spellcheck.txt" timeout="30"></cfexecute>
		<cfelse>
		<cfexecute name="#shellCommand#" arguments="#shellParameters# #spellcheckCommand# #spellcheckParameters# <#filename# >#filename#.spellcheck.txt" timeout="30"></cfexecute>
		</cfif>

		<cfset jsMisspelled = "">

		<cfset contentCurrentLine = 0>
		<cfset contentNextLine = 1>

		<cfset misspelledCount = 0>
		<cfset misspelled = "">
		<cfset suggestions = "">
		<cfset firstline = true>
		<cffile action="read"  file="#filename#.spellcheck.txt" variable="spellcheckoutput">
		<cfscript>
		startOfLine = Find(Chr(13), spellcheckoutput, 1)+1;
		nextContentLine();
		while (startOfLine LT Len(spellcheckoutput)) {
			if (Find(Chr(13), spellcheckoutput, startOfLine) GT 0) {
				myline = Mid(spellcheckoutput, startOfLine, Find(Chr(13), spellcheckoutput, startOfLine)-startOfLine);
				startOfLine = Find(Chr(13), spellcheckoutput, startOfLine)+1;
			} else {
				myline = Mid(spellcheckoutput, startOfLine, Len(spellcheckoutput)-startOfLine);
				startOfLine = Len(spellcheckoutput)+1;
			}
			while ((Len(myline) GT 0) and ((Left(myline,1) is Chr(13)) or (Left(myline,1) is Chr(10)))) {
				if (Len(myline) GT 1) {
					myline = Right(myline,Len(myline)-1);
				} else {
					myline = "";
				}
			}
			while ((Len(myline) GT 0) and ((Right(myline,1) is Chr(13)) or (Right(myline,1) is Chr(10)))) {
				if (Len(myline) GT 1) {
					myline = Left(myline,Len(myline)-1);
				} else {
					myline = "";
				}
			}
			misspelled = "";
			misspelledOffset = "";
			suggestions = "";
			if (myline is "") {
				nextContentLine();
			} else if (Left(myline,1) is "&") {
				i = Find(" ", myline, Find(" ",myline,3)+1)+1;
				misspelled = Mid(myline,3,Find(" ",myline,3)-3);
				misspelledOffset = Mid(myline,i,Find(":",myline,1)-i);
				suggestions = Right(myline, Len(myline)-Find(":", myline, 1)-1);
			} else if (Left(myline,1) is Chr(35)) {
				i = Find(" ",myline,3)+1;
				misspelled = Mid(myline,3,Find(" ",myline,3)-3);
				misspelledOffset = Mid(myline,i,Len(myline)-i+1);
				suggestions = "";
			}
			if ((Left(myline,1) is "&") or (Left(myline,1) is Chr(35))) {
				misspelled = HTMLEditFormat(Replace(Replace(Replace(misspelled,"'","\'","All"),"""","\""","All"),", ",",","All"));
				suggestions = HTMLEditFormat(Replace(Replace(Replace(suggestions,"'","\'","All"),"""","\""","All"),", ",",","All"));
				if (misspelledOffset is not "") {
					misspelledOffset = misspelledOffset + contentCurrentLine - 2;
				} else {
					misspelledOffset = contentCurrentLine;
				}
					jsMisspelled = jsMisspelled & "misspelled[" & misspelledCount & "] = new misspelledItem(" & misspelledOffset & ",""" & misspelled & """,""" & suggestions & """);" & Chr(13) & Chr(10);
					misspelledCount = misspelledCount + 1;
			}
		}
		</cfscript>

		<cffile action="delete" file="#filename#">
		<cffile action="delete" file="#filename#.spellcheck.txt">
	</cfif>

	<cffunction name="nextContentLine">
		<cfscript>
		EOL = 0;
		myline = "";
		while ((myline is "") and (Len(content)+1-contentNextLine GT 0)) {
			EOL = Find(Chr(10), content, contentNextLine);
			if (EOL LT 1) { EOL = Len(content); }
			if (EOL-contentNextLine GT 0) {
				myline = Mid(content,contentNextLine,EOL-contentNextLine);
				if (Left(myline,1) is Chr(13)) { if (Len(myline) GT 1) { myline = Right(myline,Len(myline)-1); } else { myline = ""; } }
				if (Right(myline,1) is Chr(13)) { if (Len(myline) GT 1) { myline = Left(myline,Len(myline)-1); } else { myline = ""; } }
				contentCurrentLine = contentNextLine;
				contentNextLine = EOL + 1;
			}
		}
		return myline;
		</cfscript>
	</cffunction>
<cfif IsDefined("Form.dictionary")>
<cfinclude template="spellcheck.cfm.post.html" >
<cfelse>
<cfinclude template="spellcheck.cfm.get.html" >
</cfif>
