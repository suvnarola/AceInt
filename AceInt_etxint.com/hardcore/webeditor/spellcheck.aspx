<%@ Page Language="C#" validateRequest=false %>
<!-- #include file="config.aspx" -->
<%@ Import Namespace="System" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Diagnostics" %>
<script runat="server">
	string content = "";
	string jsMisspelled = "";
	int contentCurrentLine = 0;
	int contentNextLine = 0;
	int misspelledCount = 0;

	void Page_Load(Object Source, EventArgs E) {
		if ((Request.Form.Count > 0) && (spellcheckCommand != "")) {
			if (Request.Form.Get("dictionary") != "") {
				spellcheckParameters = spellcheckParameters + " " + spellcheckDictionary + " " + Request.Form.Get("dictionary");
			}

			content = Request.Form.Get("content");

			string spellcheckContent = "";
			string mycontent = "";
			string myline = nextContentLine();
			while (myline != "") {
				spellcheckContent = spellcheckContent + "^" + myline + "\r\n";
				mycontent = mycontent + myline + "\r\n";
				myline = nextContentLine();
			}
			content = mycontent;

			Random rand = new Random();
			string filename = Environment.GetEnvironmentVariable("Temp") + "\\HardCore_spellcheck_" + rand.Next();

			// write content in windows codepage 1252 format which is approximately like the web content's iso-8859-1 format
//			byte[] spellcheckContentBytes = Encoding.GetEncoding(1252).GetBytes(spellcheckContent);
//			FileStream fh = File.Create(filename);
//			fh.Write(spellcheckContentBytes, 0, spellcheckContentBytes.Length);
//			fh.Close();

			Stream fh = new FileStream(filename, FileMode.Create);
			foreach (char c in spellcheckContent.ToCharArray()) {
				fh.WriteByte((byte)c);
			}
			fh.Close();

			Process proc = System.Diagnostics.Process.Start("cmd.exe", " /c " + spellcheckCommand + " " + spellcheckParameters + " <" + filename + " >" + filename + ".spellcheck.txt");
			proc.WaitForExit(10*1000);
			proc.Close();

			jsMisspelled = "";

			contentCurrentLine = 0;
			contentNextLine = 0;

			misspelledCount = 0;
			string misspelled = "";
			string misspelledOffset = "";
			string suggestions = "";
			StreamReader fh2 = File.OpenText(filename + ".spellcheck.txt");
			myline = fh2.ReadLine();
			nextContentLine();
			while ((myline = fh2.ReadLine()) != null) {
				misspelled = "";
				misspelledOffset = "";
				suggestions = "";
				if (myline == "") {
					nextContentLine();
				} else if (myline.StartsWith("&")) {
					int i = myline.LastIndexOf(" ", myline.IndexOf(":"));
					misspelled = myline.Substring(2, myline.IndexOf(" ", 2)-2);
					misspelledOffset = myline.Substring(i, myline.IndexOf(":")-i);
					suggestions = myline.Substring(myline.IndexOf(":")+2);
				} else if (myline.StartsWith("#")) {
					int i = myline.IndexOf(" ", 2)+1;
					misspelled = myline.Substring(2, myline.IndexOf(" ", 2)-2);
					misspelledOffset = myline.Substring(i);
				}
				if ((myline.StartsWith("&")) || (myline.StartsWith("#"))) {
					misspelled = Server.HtmlEncode(misspelled.Replace("'", "\\'").Replace("\"", "\\\"").Replace(", ", ","));
					suggestions = Server.HtmlEncode(suggestions.Replace("'", "\\'").Replace("\"", "\\\"").Replace(", ", ","));
					if (misspelledOffset != "") {
						misspelledOffset = "" + (Convert.ToInt32(misspelledOffset) + contentCurrentLine - 1);
					} else {
						misspelledOffset = "" + contentCurrentLine;
					}
					jsMisspelled = jsMisspelled + "misspelled[" + misspelledCount + "] = new misspelledItem(" + misspelledOffset + ",\"" + misspelled + "\",\"" + suggestions + "\");" + "\r\n";
					misspelledCount = misspelledCount + 1;
				}
			}
			fh2.Close();

			if (File.Exists(filename)) File.Delete(filename);
			if (File.Exists(filename + ".spellcheck.txt")) File.Delete(filename + ".spellcheck.txt");
		}
	}

	string nextContentLine() {
		int EOL = -1;
		string myline = "";
		while ((myline == "") && (content.Length-contentNextLine > 0)) {
			EOL = content.IndexOf("\n", contentNextLine);
			if (EOL < 1) EOL = content.Length;
			if (EOL-contentNextLine > 0) {
				myline = content.Substring(contentNextLine,EOL-contentNextLine);
				if (myline.StartsWith("\r")) myline = myline.Substring(1);
				if (myline.EndsWith("\r")) myline = myline.Substring(0, myline.Length-1);
				contentCurrentLine = contentNextLine;
				contentNextLine = EOL + 1;
			}
		}
		return myline;
	}
</script>
<%
	if (Request.Form.Count > 0) {
%>
<!-- #include file="spellcheck.aspx.post.html" -->
<%
	} else {
%>
<!-- #include file="spellcheck.aspx.get.html" -->
<%
	}
%>
