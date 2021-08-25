<html>
<head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Generator" content="HardCore Web Content Editor">
<meta http-equiv="Copyright" content="(C) 2002-2004 - HardCore Internet Ltd. - www.hardcoreinternet.co.uk">
<script src="webeditor.properties.js"></script>
<title>HardCore Web Content Editor</title>
<link rel="stylesheet" type="text/css" href="webeditor.css" />
<script>

document.title = Text('hyperlink_title');

function requestParameter(name) {
	var value = "";
	if (start = request.indexOf("?"+name+"=")+1) {
		value = request.substring(start+name.length+1);
	} else if (start = request.indexOf("&"+name+"=")+1) {
		value = request.substring(start+name.length+1);
	}
	if (value.indexOf("&")+1) {
		value = value.substring(0, value.indexOf("&"));
	}
	value = unescape(value);
	return value;
}

window.focus();
var request = "" + window.location;
editor = requestParameter("editor");

function initform(link) {
	var target = "";
	var text = "";
	var htmlclass = "";
	var htmlid = "";
	if (link == null) {
		link = requestParameter("href");
		target = requestParameter("target");
		text = requestParameter("text");
		htmlclass = requestParameter("htmlclass");
		htmlid = requestParameter("htmlid");
	} else {
		if (document.linkform.target) target = document.linkform.target.options[document.linkform.target.selectedIndex].value;
		if (document.linkform.text) text = document.linkform.text.value;
		if (document.linkform.htmlclass) htmlclass = document.linkform.htmlclass.value;
		if (document.linkform.htmlid) htmlid = document.linkform.htmlid.value;
	}

	if (('http://'+document.location.hostname == link.substring(0,7+document.location.hostname.length)) && (link.length > 7+document.location.hostname.length)) {
		link = link.substring(7+document.location.hostname.length);
	} else {
		for (j=0; j<=document.linkform.type.length; j++) {
			if (document.linkform.type.options[j] && (document.linkform.type.options[j].value == link.substring(0,document.linkform.type.options[j].value.length))) {
				document.linkform.type.selectedIndex = j;
			}
		}
		link = link.substring(document.linkform.type.options[document.linkform.type.selectedIndex].value.length);
	}

	if (link.indexOf('#')>=0) {
		document.linkform.bookmark.value=link.substring(link.indexOf('#')+1);
		link = link.substring(0,link.indexOf('#'));
	} else {
		document.linkform.bookmark.value='';
	}

	if (target) {
		for (j=0; j<=document.linkform.target.length; j++) {
			if (document.linkform.target.options[j] && (document.linkform.target.options[j].value == target)) {
				document.linkform.target.selectedIndex = j;
			}
		}
	}

	document.linkform.link.value=link;
	if (link) {
		for (j=0; j<=document.linkform.quicklink.length; j++) {
			if (document.linkform.quicklink.options[j] && (document.linkform.quicklink.options[j].value == link)) {
				document.linkform.quicklink.selectedIndex = j;
			} else if (document.linkform.quicklink.options[j] && (document.linkform.quicklink.options[j].value == document.linkform.type.options[document.linkform.type.selectedIndex].value + link)) {
				document.linkform.quicklink.selectedIndex = j;
			}
		}
	}

	if (document.linkform.text) document.linkform.text.value = text.replace("\t", " ").replace("\r", " ").replace("\n", " ");
	if (document.linkform.htmlclass) document.linkform.htmlclass.value = htmlclass.replace("\t", " ").replace("\r", " ").replace("\n", " ");
	if (document.linkform.htmlid) document.linkform.htmlid.value = htmlid.replace("\t", " ").replace("\r", " ").replace("\n", " ");
}

function linkit() {
	var url;
	url = document.linkform.type.value+document.linkform.link.value;
	if ((document.linkform.type.value == "") && (url.substring(0,4) == "www.")) {
		url = 'http://'+url;
	}
	if (document.linkform.bookmark.value != '') {
		url += '#'+document.linkform.bookmark.value;
	}
	if (document.linkform.target) { target = document.linkform.target.value; } else { target = ""; }
	if (document.linkform.text) { text = document.linkform.text.value; } else { text = ""; }
	if (document.linkform.htmlclass) { htmlclass = document.linkform.htmlclass.value; } else { htmlclass = ""; }
	if (document.linkform.htmlid) { htmlid = document.linkform.htmlid.value; } else { htmlid = ""; }
	top.opener[editor].insertHyperlink(url, target, text, htmlclass, htmlid);
	top.close();
}

</script>
</head>
<body onload="initform()">
<form name="linkform" onSubmit="linkit(); return false;">
<input type="hidden" name="text" value="">
<!--
	<p><font size="-1">
	Modify this file ("hyperlink.html") to integrate with your own web application and database.
	You may simply want to add your own hyperlinks to the "Quicklinks" list below,
	or you may want to make a complete hyperlink manager like the one used in the HardCore Web Content Management system
	(<a href="hyperlinkmanager.jpg" target="_blank">view example</a>).
	</font></p>
-->
<table width="100%" border="0" class="dtree">
	<tr align="left" valign="top"> 
		<td colspan="4" class="webeditor_window_title"><script>document.write(Text('hyperlink_title'));</script></td>
	</tr>
	<tr align="left" valign="top"> 
		<td colspan="4">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('hyperlink_url'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('hyperlink_url_quicklinks'));</script></td>
						<td colspan="2" class="webeditor_window_value"> 
							<select name="quicklink" style="width: 100%;" onChange="javascript:initform(this.options[this.selectedIndex].value)">
								<option value="" selected>&nbsp;
<%
Response.Write "<option value=""http://www.hardcoreinternet.co.uk/"">www.hardcoreinternet.co.uk" & vbcrlf
%>
							</select>
						</td>
					</tr>
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('hyperlink_url_type'));</script></td>
						<td class="webeditor_window_attribute"><script>document.write(Text('hyperlink_url_address'));</script></td>
						<td class="webeditor_window_attribute"><script>document.write(Text('hyperlink_url_bookmark'));</script></td>
					</tr>
					<tr>
						<td class="webeditor_window_value"> 
							<select name="type">
								<option value="" selected>&nbsp;
								<option value="http://">http://
								<option value="https://">https://
								<option value="ftp://">ftp://
								<option value="mailto:">mailto:
							</select>
						</td>
						<td class="webeditor_window_value" width="100%"> 
							<input type="text" name="link" size="35" style="width: 100%;">
						</td>
						<td class="webeditor_window_value"> 
							<nobr>#<input type="text" name="bookmark" size="10"></nobr>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr align="left" valign="top"> 
		<td colspan="4">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('hyperlink_target'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('hyperlink_target_target'));</script></td>
						<td class="webeditor_window_value"> 
							<select name="target">
								<option value="" selected>&nbsp;
								<option value="_self"><script>document.write(Text('hyperlink_target_target_self'));</script>
								<option value="_parent"><script>document.write(Text('hyperlink_target_target_parent'));</script>
								<option value="_top"><script>document.write(Text('hyperlink_target_target_top'));</script>
								<option value="_blank"><script>document.write(Text('hyperlink_target_target_blank'));</script>
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr align="left" valign="top"> 
		<td colspan="4">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('class_id'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('htmlclass'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="htmlclass" size="20" value="">
						</td>
						<td></td>
						<td class="webeditor_window_attribute"><script>document.write(Text('htmlid'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="htmlid" size="20" value="">
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
<br>
<div align="center">
<script>document.write('<input type="submit" value="' + Text('ok') + '">');</script>
<script>document.write('<input type="button" value="' + Text('cancel') + '" onClick="window.close()">');</script>
</div>
</form>
</body>
</html>
