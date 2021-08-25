<%@ page import="java.util.*" %>
<%@ page import="java.io.*" %>
<%@ include file="config.jsp" %>
<%!
	class ExecCommandOutputHandler extends Thread {
		InputStream is;
		String type;
		StringBuffer content;
		int nextLineStart;

		ExecCommandOutputHandler(InputStream is, String type) {
			this.is = is;
			this.type = type;
			this.content = new StringBuffer();
			this.nextLineStart = 0;
		}

		public void run() {
			try {
				int ch;
				while ((ch = is.read()) != -1) {
					content.append((char)ch);
				}
			} catch (IOException ioe) {
				ioe.printStackTrace();  
			}
		}

		public String readLine() {
			if (nextLineStart >= content.length()) {
				return null;
			} else {
				int thisLineStart = nextLineStart;
				int thisLineLength = content.substring(thisLineStart).indexOf("\n");
				if (thisLineLength >= 0) {
					nextLineStart = thisLineStart + thisLineLength + 1;
				} else {
					nextLineStart = content.length();
				}
				return content.substring(thisLineStart, nextLineStart);
			}
		}
	}

	class ExecCommandInputHandler extends Thread {
		OutputStream os;
		String type;
		String content;

		ExecCommandInputHandler(OutputStream os, String type, String content) {
			this.os = os;
			this.type = type;
			this.content = content;
		}

		public void run() {
			try {
				if (content.length() > 0) {
					os.write(content.getBytes());
				}
				os.close();
			} catch(IOException ioe) {
				ioe.printStackTrace();  
			}
		}
	}

	class ExecCommand {
		Runtime rt;
		Process proc;
		ExecCommandInputHandler cmdInput;
		ExecCommandOutputHandler cmdOutput;
		ExecCommandOutputHandler cmdError;

		public ExecCommand(String command, String input) {
			try {
				rt = Runtime.getRuntime();
				proc = rt.exec(command);
				cmdError = new ExecCommandOutputHandler(proc.getErrorStream(), "ERROR");            
				cmdOutput = new ExecCommandOutputHandler(proc.getInputStream(), "OUTPUT");
				cmdInput = new ExecCommandInputHandler(proc.getOutputStream(), "INPUT", input);
				cmdError.start();
				cmdOutput.start();
				cmdInput.start();
				int exitVal = proc.waitFor();
			} catch (Throwable t) {
				t.printStackTrace();
			}
		}

		public String readLine() {
			if (cmdOutput != null) {
				return cmdOutput.readLine();
			} else {
				return null;
			}
		}
	}
%>
<%!
	String content;
	String jsMisspelled;
	int contentCurrentLine;
	int contentNextLine;
%>
<%
	if ((request.getMethod().equals("POST")) && (! spellcheckCommand.equals(""))) {
		if ((request.getParameter("dictionary") != null) && (! request.getParameter("dictionary").equals(""))) {
			spellcheckParameters = spellcheckParameters + " " + spellcheckDictionary + " " + request.getParameter("dictionary");
		}

		content = "" + request.getParameter("content");

		contentCurrentLine = 0;
		contentNextLine = 0;

		String spellcheckContent = "";
		String mycontent = "";
		String myline = nextContentLine();
		while (! myline.equals("")) {
			spellcheckContent = "" + spellcheckContent + "^" + myline + "\r\n";
			mycontent = "" + mycontent + myline + "\r\n";
			myline = nextContentLine();
		}
		content = mycontent;

		jsMisspelled = "";
		
		try {
			ExecCommand execCommand = new ExecCommand(spellcheckCommand + " " + spellcheckParameters, spellcheckContent);

			contentCurrentLine = 0;
			contentNextLine = 0;

			int misspelledCount = 0;
			String misspelledOffset = "";
			String misspelled = "";
			String suggestions = "";

			myline = execCommand.readLine();
			nextContentLine();
			while ((myline = execCommand.readLine()) != null) {
				myline = myline.trim();
				misspelledOffset = "";
				misspelled = "";
				suggestions = "";
				if (myline.equals("")) {
					nextContentLine();
				} else if (myline.substring(0,1).equals("&")) {
					int i = myline.substring(0, myline.indexOf(":")).lastIndexOf(" ") + 1;
					misspelled = myline.substring(2, myline.substring(2).indexOf(" ")+2);
					misspelledOffset = myline.substring(i, myline.indexOf(":"));
					suggestions = myline.substring(myline.indexOf(":")+2);
				} else if (myline.substring(0,1).equals("#")) {
					int i = myline.substring(2).indexOf(" ")+3;
					misspelled = myline.substring(2, myline.substring(2).indexOf(" ")+2);
					misspelledOffset = myline.substring(i);
					suggestions = "";
				}
				if ((! myline.equals("")) && ((myline.substring(0,1).equals("&")) || (myline.substring(0,1).equals("#")))) {
					misspelled = misspelled.replaceAll("\"", "\\\\\"").replaceAll("'", "\\\\'").replaceAll(", ", ",");
					suggestions = suggestions.replaceAll("\"", "\\\\\"").replaceAll("'", "\\\\'").replaceAll(", ", ",");
					if (! misspelledOffset.equals("")) {
						misspelledOffset = "" + (Integer.parseInt(misspelledOffset) + contentCurrentLine - 1);
					} else {
						misspelledOffset = "" + contentCurrentLine;
					}
					jsMisspelled = "" + jsMisspelled + "misspelled[" + misspelledCount + "] = new misspelledItem(" + misspelledOffset + ",\"" + misspelled + "\",\"" + suggestions + "\");" + "\r\n";
					misspelledCount = misspelledCount + 1;
				}
			}

		} catch (Throwable t) {
			t.printStackTrace();
		}
	}
%>
<%!
	public String nextContentLine() {
		int EOL = -1;
		String myline = "";
		while ((myline.equals("")) && (content.length()-contentNextLine > 0)) {
			EOL = content.substring(contentNextLine).indexOf("\n");
			if (EOL == -1) {
				EOL = content.length();
			} else {
				EOL = EOL + contentNextLine;
			}
			if (EOL-contentNextLine > 0) {
				myline = content.substring(contentNextLine,EOL).replaceAll("\r","").replaceAll("\n","");
				contentCurrentLine = 0 + contentNextLine;
				contentNextLine = EOL + 1;
			}
		}
		return myline;
	}
%>
<%
	if (request.getMethod().equals("POST")) {
%>
<%@ include file="spellcheck.jsp.post.html" %>
<%
	} else {
%>
<%@ include file="spellcheck.jsp.get.html" %>
<%
	}
%>
