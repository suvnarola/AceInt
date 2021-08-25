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

document.title = Text('media_title');

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
	var border = "";
	var text = "";
	var width = "";
	var height = "";
	var vspace = "";
	var hspace = "";
	var align = "";
	var onmouseover = "";
	var onmouseout = "";
	var htmlclass = "";
	var htmlid = "";
	if (link == null) {
		link = requestParameter("href");
		border = requestParameter("border");
		text = requestParameter("alt");
		width = requestParameter("width");
		height = requestParameter("height");
		vspace = requestParameter("vspace");
		hspace = requestParameter("hspace");
		align = requestParameter("align");
		onmouseover = requestParameter("onmouseover");
		onmouseout = requestParameter("onmouseout");
		htmlclass = requestParameter("htmlclass");
		htmlid = requestParameter("htmlid");
	} else {
		if (document.linkform.border) border = document.linkform.border.value;
		if (document.linkform.text) text = document.linkform.text.value;
		if (document.linkform.width) width = document.linkform.width.value;
		if (document.linkform.height) height = document.linkform.height.value;
		if (document.linkform.vspace) vspace = document.linkform.vspace.value;
		if (document.linkform.hspace) hspace = document.linkform.hspace.value;
		if (document.linkform.align) align = document.linkform.align.value;
		if (document.linkform.htmlonmouseover) onmouseover = document.linkform.htmlonmouseover.value;
		if (document.linkform.htmlonmouseout) onmouseout = document.linkform.htmlonmouseout.value;
		if (document.linkform.htmlclass) htmlclass = document.linkform.htmlclass.value;
		if (document.linkform.htmlid) htmlid = document.linkform.htmlid.value;
	}

	if (document.linkform.border) document.linkform.border.value = border;
	if (document.linkform.text) document.linkform.text.value = text;
	if (document.linkform.width) document.linkform.width.value = width;
	if (document.linkform.height) document.linkform.height.value = height;
	if (document.linkform.vspace) document.linkform.vspace.value = vspace;
	if (document.linkform.hspace) document.linkform.hspace.value = hspace;
	if (document.linkform.align) document.linkform.align.value = align;
	if (document.linkform.htmlonmouseover) document.linkform.htmlonmouseover.value = onmouseover;
	if (document.linkform.htmlonmouseout) document.linkform.htmlonmouseout.value = onmouseout;
	if (document.linkform.htmlclass) document.linkform.htmlclass.value = htmlclass;
	if (document.linkform.htmlid) document.linkform.htmlid.value = htmlid;

	var type = '';
	if ('http://'+document.location.hostname == link.substring(0,7+document.location.hostname.length)) {
		link = link.substring(7+document.location.hostname.length);
	} else {
		for (j=0; j<=document.linkform.type.length; j++) {
			if (document.linkform.type.options[j] && (document.linkform.type.options[j].value == link.substring(0,document.linkform.type.options[j].value.length))) {
				document.linkform.type.selectedIndex = j;
				type = document.linkform.type.options[j].value
			}
		}
		link = link.substring(document.linkform.type.options[document.linkform.type.selectedIndex].value.length);
	}

	document.linkform.link.value=link;
	if (link) {
		for (j=0; j<=document.linkform.quicklink.length; j++) {
			if (document.linkform.quicklink.options[j] && (document.linkform.quicklink.options[j].value == type+link)) {
				document.linkform.quicklink.selectedIndex = j;
			}
		}
	}
}

function linkit() {
	var url;
	url = document.linkform.type.value+document.linkform.link.value;
	if ((document.linkform.type.value == "") && (url.substring(0,4) == "www.")) {
		url = 'http://'+url;
	}
	if (document.linkform.border) { border = document.linkform.border.value; } else { border = ""; }
	if (document.linkform.text) { text = document.linkform.text.value; } else { text = ""; }
	if (document.linkform.width) { width = document.linkform.width.value; } else { width = ""; }
	if (document.linkform.height) { height = document.linkform.height.value; } else { height = ""; }
	if (document.linkform.vspace) { vspace = document.linkform.vspace.value; } else { vspace = ""; }
	if (document.linkform.hspace) { hspace = document.linkform.hspace.value; } else { hspace = ""; }
	if (document.linkform.align) { align = document.linkform.align.value; } else { align = ""; }
	if (document.linkform.htmlonmouseover) { onmouseover = document.linkform.htmlonmouseover.value; } else { onmouseover = ""; }
	if (document.linkform.htmlonmouseout) { onmouseout = document.linkform.htmlonmouseout.value; } else { onmouseout = ""; }
	if (document.linkform.htmlclass) { htmlclass = document.linkform.htmlclass.value; } else { htmlclass = ""; }
	if (document.linkform.htmlid) { htmlid = document.linkform.htmlid.value; } else { htmlid = ""; }
	if (url.substring(url.length-4) == ".swf") {
		mediaclass = "flash";
	} else if (url.substring(url.length-6) == ".class") {
		mediaclass = "applet";
	} else {
		mediaclass = "image";
	}
	if (mediaclass == "flash") {
		top.opener[editor].insertFlash(url, text, width, height, htmlclass, htmlid);
	} else if (mediaclass == "applet") {
		top.opener[editor].insertApplet(url, text, width, height, htmlclass, htmlid);
	} else {
		top.opener[editor].insertImage(url, border, text, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout);
	}
	top.close();
}

</script>
</head>
<body onload="initform()">
<form name="linkform" onSubmit="linkit(); return false;">
<table width="100%" border="0" class="dtree">
	<tr align="left" valign="top"> 
		<td colspan="4" class="webeditor_window_title"><script>document.write(Text('media_title'));</script></td>
	</tr>
	<tr align="left" valign="top"> 
		<td colspan="4">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('media_url'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_url_quicklinks'));</script></td>
						<td class="webeditor_window_value"> 
							<select name="quicklink" style="width: 100%;" onChange="javascript:initform(this.options[this.selectedIndex].value)">
								<option value="" selected>&nbsp;
<%= "<option value=""http://www.hardcoreinternet.co.uk/hardcore/logo.gif"">HardCore Internet logo" %>
							</select>
						</td>
					</tr>
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_url_type'));</script></td>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_url_address'));</script></td>
					</tr>
					<tr>
						<td class="webeditor_window_value"> 
							<select name="type">
								<option value="" selected>&nbsp;
								<option value="http://">http://
								<option value="https://">https://
								<option value="ftp://">ftp://
							</select>
						</td>
						<td class="webeditor_window_value"> 
							<input type="text" name="link" size="53">
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr align="left" valign="top"> 
		<td colspan="2" width="50%">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('media_size'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_size_width'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="width" size="5" value="">
						</td>
					</tr>
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_size_height'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="height" size="5" value="">
						</td>
					</tr>
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_size_border'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="border" size="5" value="">
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td colspan="2" width="50%">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('media_spacing_alignment'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_spacing_vertical'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="vspace" size="5" value="">
						</td>
					</tr>
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_spacing_horizontal'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="hspace" size="5" value="">
						</td>
					</tr>
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_alignment'));</script></td>
						<td class="webeditor_window_value"> 
							<select name="align">
								<option value=""><script>document.write(Text('media_alignment_default'));</script>
								<option value="left"><script>document.write(Text('media_alignment_left'));</script>
								<option value="right"><script>document.write(Text('media_alignment_right'));</script>
								<option value="top"><script>document.write(Text('media_alignment_top'));</script>
								<option value="bottom"><script>document.write(Text('media_alignment_bottom'));</script>
								<option value="middle"><script>document.write(Text('media_alignment_middle'));</script>
								<option value="absmiddle"><script>document.write(Text('media_alignment_absmiddle'));</script>
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
			<legend class="webeditor_window_heading"><script>document.write(Text('media_alt'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_alt_text'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="text" size="55" value="">
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr align="left" valign="top"> 
		<td colspan="4">
			<fieldset>
			<legend class="webeditor_window_heading"><script>document.write(Text('media_events'));</script></legend>
				<table width="100%">
					<tr>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_events_onmouseover'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="htmlonmouseover" size="20" value="">
						</td>
						<td></td>
						<td class="webeditor_window_attribute"><script>document.write(Text('media_events_onmouseout'));</script></td>
						<td class="webeditor_window_value"> 
							<input type="text" name="htmlonmouseout" size="20" value="">
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
