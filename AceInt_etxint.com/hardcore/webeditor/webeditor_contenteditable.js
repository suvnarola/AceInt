///////////////////////////////////////////////////////////////////////////////////////////////////
// HardCore Web Content Editor
// Copyright 2002-2004 HardCore Internet Ltd.
// www.hardcoreinternet.co.uk
///////////////////////////////////////////////////////////////////////////////////////////////////

var webeditor;
webeditor.inited = 0;

var webeditor_keyCode_undo = 90;
var webeditor_keyCode_redo = 89;

///////////////////////////////////////////////////////////////////////////////////////////////////
// Main function/object called from web page at location of the web editor content
///////////////////////////////////////////////////////////////////////////////////////////////////

function HardCoreWebEditor(rootpath, language, name, value, value_htmlencoded, stylesheet, showhtml, manager, onEnter, onShiftEnter, onCtrlEnter, onAltEnter, toolbar, width, height, format, encoding, direction) {
	webeditor.language = language || "html";
	webeditor.stylesheet = stylesheet || "";
	webeditor.manager = manager || "";
	webeditor.onEnter = onEnter || "";
	webeditor.onShiftEnter = onShiftEnter || "";
	webeditor.onCtrlEnter = onCtrlEnter || "";
	webeditor.onAltEnter = onAltEnter || "";
	webeditor.toolbar = toolbar;
	webeditor.width = width || "100%";
	webeditor.height = height || "100%";
	webeditor.format = format || "";
	webeditor.encoding = encoding || "UTF-8";
	webeditor.direction = direction || 'ltr';
	if (! webeditor.select_focused) webeditor.select_focused = new Array();

	try {
		if (webeditor_custom_encode) value = webeditor_custom_encode(value);
	} catch(e) {
	}
	value = contenteditable_encodeScriptTags(value);

	contenteditable(name, unescape(value), stylesheet);
	contenteditable_onload(webeditor_init);
}

function HardCoreWebEditorFocus() {
	contenteditable_enable();
}

function HardCoreWebEditorDOMInspector(name) {
	document.writeln('<table class="webeditor_DOM_inspector" cellpadding="0" cellspacing="0" bgcolor="#C0C0C0" border="1" width="' + webeditor.width + '">');
	if (name) {
		document.writeln('<tr><td id="webeditor_DOM_inspector_'+name+'">');
	} else {
		document.writeln('<tr><td id="webeditor_DOM_inspector">');
	}
	document.writeln('&nbsp;');
	document.writeln('</td></tr>');
	document.writeln('</table>');
}

function HardCoreWebEditorToolbar(options) {
// You are not allowed in any way to remove or hide the copyright notice unless you obtain a valid commercial license for the HardCore Web Content Editor from HardCore Internet Ltd. - www.hardcoreinternet.co.uk
// The copyright notice must remain visible and unmodified at all times unless you obtain a valid commercial license for the HardCore Web Content Editor from HardCore Internet Ltd. - www.hardcoreinternet.co.uk
//	document.writeln('<div align="left"><font size="-1">HardCore Web Content Editor - (C) 2002-2004 - HardCore Internet Ltd. - <a href="http://www.hardcoreinternet.co.uk" target="_blank">www.hardcoreinternet.co.uk</a></font></div>');
	var rows = new Array(0);
	if (arguments.length > 1) {
		for (var i=1; i<arguments.length; i++) if (arguments[i]) rows[rows.length] = arguments[i];
	}
	if (! rows.length) {
		rows[0] = "formatclass formatblock fontname fontsize bold italic underline forecolor backcolor superscript subscript strikethrough help";
		rows[1] = "cut copy paste clean removeformat delete selectall undo redo specialcharacter insertmedia iframe createlink mailto anchor unlink inserthorizontalrule insertorderedlist insertunorderedlist outdent indent justifyleft justifycenter justifyright justifyfull nobr";
		rows[2] = "createtable tableproperties insertcaption insertrowhead insertrowfoot rowproperties insertrowabove insertrowbelow deleterow splitcellrows columnproperties insertcolumnleft insertcolumnright deletecolumn splitcellcolumns cellproperties insertcellleft insertcellright deletecell splitcell mergecells import find printbreak print preview";
		rows[3] = "form submitbutton resetbutton backbutton imagebutton file button text password hidden textarea checkbox radio select position forwards backwards front back abovetext belowtext box spellcheck viewdetails viewsource save";
	}
	if (! options) options = new Array();
	if (! options['formatblock']) {
		var i=0;
		options['formatblock'] = new Array();
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_normal');
		options['formatblock'][i++].value='<p>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_paragraph');
		options['formatblock'][i++].value='<p>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_formatted');
		options['formatblock'][i++].value='<pre>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_heading1');
		options['formatblock'][i++].value='<h1>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_heading2');
		options['formatblock'][i++].value='<h2>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_heading3');
		options['formatblock'][i++].value='<h3>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_heading4');
		options['formatblock'][i++].value='<h4>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_heading5');
		options['formatblock'][i++].value='<h5>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_heading6');
		options['formatblock'][i++].value='<h6>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_numberedlist');
		options['formatblock'][i++].value='<ol>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_bulletedlist');
		options['formatblock'][i++].value='<ul>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_directorylist');
		options['formatblock'][i++].value='<dir>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_menulist');
		options['formatblock'][i++].value='<menu>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_definitionterm');
		options['formatblock'][i++].value='<dt>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_definition');
		options['formatblock'][i++].value='<dd>';
		options['formatblock'][i] = new Object();
		options['formatblock'][i].name=Text('formatblock_address');
		options['formatblock'][i++].value='<address>';
	}
	if (! options['fontname']) {
		var i=0;
		options['fontname'] = new Array();
		options['fontname'][i] = new Object();
		options['fontname'][i].name='Times New Roman';
		options['fontname'][i++].value='Times New Roman';
		options['fontname'][i] = new Object();
		options['fontname'][i].name='Helvetica,Arial';
		options['fontname'][i++].value='Helvetica,Arial';
		options['fontname'][i] = new Object();
		options['fontname'][i].name='Helvetica';
		options['fontname'][i++].value='Helvetica';
		options['fontname'][i] = new Object();
		options['fontname'][i].name='Arial';
		options['fontname'][i++].value='Arial';
		options['fontname'][i] = new Object();
		options['fontname'][i].name='Courier';
		options['fontname'][i++].value='Courier';
	}
	if (! options['fontsize']) {
		var i=0;
		options['fontsize'] = new Array();
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='8';
		options['fontsize'][i++].value='1';
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='10';
		options['fontsize'][i++].value='2';
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='12';
		options['fontsize'][i++].value='3';
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='14';
		options['fontsize'][i++].value='4';
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='18';
		options['fontsize'][i++].value='5';
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='24';
		options['fontsize'][i++].value='6';
		options['fontsize'][i] = new Object();
		options['fontsize'][i].name='36';
		options['fontsize'][i++].value='7';
	}
	document.writeln('<table class="webeditor_toolbar" cellpadding="0" cellspacing="0" bgcolor="#C0C0C0" border="0">');
	for (var i=0; i<rows.length; i++) {
		document.writeln('<tr>');
		var elements = rows[i].split(" ");
		for (var j=0; j<elements.length; j++) {
			switch(elements[j]) {
			case "formatclass":
				document.writeln('<td colspan="5">');
				document.writeln('<select unselectable="on" class="webeditor_select" id="formatclass" title="'+Text('toolbar_formatclass')+'" style="width: 125px;">');
				document.writeln('  <option value="">&nbsp;</option>');
				document.writeln('</select>');
				document.writeln('</td>');
				break;
			case "formatblock":
				document.writeln('<td colspan="4">');
				document.writeln('<select unselectable="on" class="webeditor_select" id="formatblock" title="'+Text('toolbar_formatblock')+'" style="width: 100px;">');
				for (var k=0; k<options['formatblock'].length; k++) {
					document.writeln('  <option value="'+options['formatblock'][k].value+'">'+options['formatblock'][k].name+'</option>');
				}
				document.writeln('</select>');
				document.writeln('</td>');
				break;
			case "fontname":
				document.writeln('<td colspan="6">');
				document.writeln('<select unselectable="on" class="webeditor_select" id="fontname" title="'+Text('toolbar_fontname')+'" style="width: 150px;">');
				document.writeln('  <option value=""></option>');
				for (var k=0; k<options['fontname'].length; k++) {
					document.writeln('  <option value="'+options['fontname'][k].value+'">'+options['fontname'][k].name+'</option>');
				}
				document.writeln('</select>');
				document.writeln('</td>');
				break;
			case "fontsize":
				document.writeln('<td colspan="2">');
				document.writeln('<select unselectable="on" class="webeditor_select" id="fontsize" title="'+Text('toolbar_fontsize')+'" style="width: 50px;">');
				document.writeln('  <option value=""></option>');
				for (var k=0; k<options['fontsize'].length; k++) {
					document.writeln('  <option value="'+options['fontsize'][k].value+'">'+options['fontsize'][k].name+'</option>');
				}
				document.writeln('</select>');
				document.writeln('</td>');
				break;
			case "bold":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="bold" src="' + webeditor.buttonpath + 'bold.gif" alt="'+Text('toolbar_bold')+'" title="'+Text('toolbar_bold')+'"></td>');
				break;
			case "italic":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="italic" src="' + webeditor.buttonpath + 'italic.gif" alt="'+Text('toolbar_italic')+'" title="'+Text('toolbar_italic')+'"></td>');
				break;
			case "underline":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="underline" src="' + webeditor.buttonpath + 'underline.gif" alt="'+Text('toolbar_underline')+'" title="'+Text('toolbar_underline')+'"></td>');
				break;
			case "forecolor":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="forecolor" src="' + webeditor.buttonpath + 'forecolor.gif" alt="'+Text('toolbar_forecolor')+'" title="'+Text('toolbar_forecolor')+'"></td>');
				break;
			case "backcolor":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="backcolor" src="' + webeditor.buttonpath + 'backcolor.gif" alt="'+Text('toolbar_backcolor')+'" title="'+Text('toolbar_backcolor')+'"></td>');
				break;
			case "removeformat":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="removeformat" src="' + webeditor.buttonpath + 'removeformat.gif" alt="'+Text('toolbar_removeformat')+'" title="'+Text('toolbar_removeformat')+'"></td>');
				break;
			case "cut":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="cut" src="' + webeditor.buttonpath + 'cut.gif" alt="'+Text('toolbar_cut')+'" title="'+Text('toolbar_cut')+'"></td>');
				break;
			case "copy":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="copy" src="' + webeditor.buttonpath + 'copy.gif" alt="'+Text('toolbar_copy')+'" title="'+Text('toolbar_copy')+'"></td>');
				break;
			case "paste":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="paste" src="' + webeditor.buttonpath + 'paste.gif" alt="'+Text('toolbar_paste')+'" title="'+Text('toolbar_paste')+'"></td>');
				break;
			case "delete":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="delete" src="' + webeditor.buttonpath + 'delete.gif" alt="'+Text('toolbar_delete')+'" title="'+Text('toolbar_delete')+'"></td>');
				break;
			case "selectall":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="selectall" src="' + webeditor.buttonpath + 'selectall.gif" alt="'+Text('toolbar_selectall')+'" title="'+Text('toolbar_selectall')+'"></td>');
				break;
			case "undo":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="undo" src="' + webeditor.buttonpath + 'undo.gif" alt="'+Text('toolbar_undo')+'" title="'+Text('toolbar_undo')+'"></td>');
				break;
			case "redo":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="redo" src="' + webeditor.buttonpath + 'redo.gif" alt="'+Text('toolbar_redo')+'" title="'+Text('toolbar_redo')+'"></td>');
				break;
			case "specialcharacter":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="specialcharacter" src="' + webeditor.buttonpath + 'specialcharacter.gif" alt="'+Text('toolbar_specialcharacter')+'" title="'+Text('toolbar_specialcharacter')+'"></td>');
				break;
			case "insertmedia":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="insertmedia" src="' + webeditor.buttonpath + 'media.gif" alt="'+Text('toolbar_insertmedia')+'" title="'+Text('toolbar_insertmedia')+'"></td>');
				break;
			case "insertimage":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="insertimage" src="' + webeditor.buttonpath + 'image.gif" alt="'+Text('toolbar_insertimage')+'" title="'+Text('toolbar_insertimage')+'"></td>');
				break;
			case "insertflash":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="insertflash" src="' + webeditor.buttonpath + 'flash.gif" alt="'+Text('toolbar_insertflash')+'" title="'+Text('toolbar_insertflash')+'"></td>');
				break;
			case "insertapplet":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="insertapplet" src="' + webeditor.buttonpath + 'applet.gif" alt="'+Text('toolbar_insertapplet')+'" title="'+Text('toolbar_insertapplet')+'"></td>');
				break;
			case "createlink":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="createlink" src="' + webeditor.buttonpath + 'link.gif" alt="'+Text('toolbar_createlink')+'" title="'+Text('toolbar_createlink')+'"></td>');
				break;
			case "mailto":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="mailto" src="' + webeditor.buttonpath + 'mailto.gif" alt="'+Text('toolbar_mailto')+'" title="'+Text('toolbar_mailto')+'"></td>');
				break;
			case "unlink":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="unlink" src="' + webeditor.buttonpath + 'unlink.gif" alt="'+Text('toolbar_unlink')+'" title="'+Text('toolbar_unlink')+'"></td>');
				break;
			case "inserthorizontalrule":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="inserthorizontalrule" src="' + webeditor.buttonpath + 'hr.gif" alt="'+Text('toolbar_inserthorizontalrule')+'" title="'+Text('toolbar_inserthorizontalrule')+'"></td>');
				break;
			case "insertorderedlist":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="insertorderedlist" src="' + webeditor.buttonpath + 'ol.gif" alt="'+Text('toolbar_insertorderedlist')+'" title="'+Text('toolbar_insertorderedlist')+'"></td>');
				break;
			case "insertunorderedlist":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="insertunorderedlist" src="' + webeditor.buttonpath + 'ul.gif" alt="'+Text('toolbar_insertunorderedlist')+'" title="'+Text('toolbar_insertunorderedlist')+'"></td>');
				break;
			case "outdent":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="outdent" src="' + webeditor.buttonpath + 'outdent.gif" alt="'+Text('toolbar_outdent')+'" title="'+Text('toolbar_outdent')+'"></td>');
				break;
			case "indent":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="indent" src="' + webeditor.buttonpath + 'indent.gif" alt="'+Text('toolbar_indent')+'" title="'+Text('toolbar_indent')+'"></td>');
				break;
			case "justifyleft":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="justifyleft" src="' + webeditor.buttonpath + 'justifyleft.gif" alt="'+Text('toolbar_justifyleft')+'" title="'+Text('toolbar_justifyleft')+'"></td>');
				break;
			case "justifycenter":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="justifycenter" src="' + webeditor.buttonpath + 'justifycenter.gif" alt="'+Text('toolbar_justifycenter')+'" title="'+Text('toolbar_justifycenter')+'"></td>');
				break;
			case "justifyright":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="justifyright" src="' + webeditor.buttonpath + 'justifyright.gif" alt="'+Text('toolbar_justifyright')+'" title="'+Text('toolbar_justifyright')+'"></td>');
				break;
			case "justifyfull":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="justifyfull" src="' + webeditor.buttonpath + 'justifyfull.gif" alt="'+Text('toolbar_justifyfull')+'" title="'+Text('toolbar_justifyfull')+'"></td>');
				break;
			case "strikethrough":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="strikethrough" src="' + webeditor.buttonpath + 'strikethrough.gif" alt="'+Text('toolbar_strikethrough')+'" title="'+Text('toolbar_strikethrough')+'"></td>');
				break;
			case "superscript":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="superscript" src="' + webeditor.buttonpath + 'superscript.gif" alt="'+Text('toolbar_superscript')+'" title="'+Text('toolbar_superscript')+'"></td>');
				break;
			case "subscript":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="subscript" src="' + webeditor.buttonpath + 'subscript.gif" alt="'+Text('toolbar_subscript')+'" title="'+Text('toolbar_subscript')+'"></td>');
				break;
			case "createtable":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="createtable" src="' + webeditor.buttonpath + 'table.gif" alt="'+Text('toolbar_createtable')+'" title="'+Text('toolbar_createtable')+'"></td>');
				break;
			case "tableproperties":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="tableproperties" src="' + webeditor.buttonpath + 'tableproperties.gif" alt="'+Text('toolbar_tableproperties')+'" title="'+Text('toolbar_tableproperties')+'"></td>');
				break;
			case "rowproperties":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="rowproperties" src="' + webeditor.buttonpath + 'rowproperties.gif" alt="'+Text('toolbar_rowproperties')+'" title="'+Text('toolbar_rowproperties')+'"></td>');
				break;
			case "insertcaption":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertcaption" src="' + webeditor.buttonpath + 'insertcaption.gif" alt="'+Text('toolbar_insertcaption')+'" title="'+Text('toolbar_insertcaption')+'"></td>');
				break;
			case "insertrowhead":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertrowhead" src="' + webeditor.buttonpath + 'insertrowhead.gif" alt="'+Text('toolbar_insertrowhead')+'" title="'+Text('toolbar_insertrowhead')+'"></td>');
				break;
			case "insertrowfoot":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertrowfoot" src="' + webeditor.buttonpath + 'insertrowfoot.gif" alt="'+Text('toolbar_insertrowfoot')+'" title="'+Text('toolbar_insertrowfoot')+'"></td>');
				break;
			case "insertrowabove":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertrowabove" src="' + webeditor.buttonpath + 'insertrowabove.gif" alt="'+Text('toolbar_insertrowabove')+'" title="'+Text('toolbar_insertrowabove')+'"></td>');
				break;
			case "insertrowbelow":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertrowbelow" src="' + webeditor.buttonpath + 'insertrowbelow.gif" alt="'+Text('toolbar_insertrowbelow')+'" title="'+Text('toolbar_insertrowbelow')+'"></td>');
				break;
			case "deleterow":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="deleterow" src="' + webeditor.buttonpath + 'deleterow.gif" alt="'+Text('toolbar_deleterow')+'" title="'+Text('toolbar_deleterow')+'"></td>');
				break;
			case "splitcellrows":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="splitcellrows" src="' + webeditor.buttonpath + 'splitcellrows.gif" alt="'+Text('toolbar_splitcellrows')+'" title="'+Text('toolbar_splitcellrows')+'"></td>');
				break;
			case "columnproperties":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="columnproperties" src="' + webeditor.buttonpath + 'columnproperties.gif" alt="'+Text('toolbar_columnproperties')+'" title="'+Text('toolbar_columnproperties')+'"></td>');
				break;
			case "insertcolumnleft":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertcolumnleft" src="' + webeditor.buttonpath + 'insertcolumnleft.gif" alt="'+Text('toolbar_insertcolumnleft')+'" title="'+Text('toolbar_insertcolumnleft')+'"></td>');
				break;
			case "insertcolumnright":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertcolumnright" src="' + webeditor.buttonpath + 'insertcolumnright.gif" alt="'+Text('toolbar_insertcolumnright')+'" title="'+Text('toolbar_insertcolumnright')+'"></td>');
				break;
			case "deletecolumn":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="deletecolumn" src="' + webeditor.buttonpath + 'deletecolumn.gif" alt="'+Text('toolbar_deletecolumn')+'" title="'+Text('toolbar_deletecolumn')+'"></td>');
				break;
			case "splitcellcolumns":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="splitcellcolumns" src="' + webeditor.buttonpath + 'splitcellcolumns.gif" alt="'+Text('toolbar_splitcellcolumns')+'" title="'+Text('toolbar_splitcellcolumns')+'"></td>');
				break;
			case "cellproperties":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="cellproperties" src="' + webeditor.buttonpath + 'cellproperties.gif" alt="'+Text('toolbar_cellproperties')+'" title="'+Text('toolbar_cellproperties')+'"></td>');
				break;
			case "insertcellleft":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertcellleft" src="' + webeditor.buttonpath + 'insertcellleft.gif" alt="'+Text('toolbar_insertcellleft')+'" title="'+Text('toolbar_insertcellleft')+'"></td>');
				break;
			case "insertcellright":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="insertcellright" src="' + webeditor.buttonpath + 'insertcellright.gif" alt="'+Text('toolbar_insertcellright')+'" title="'+Text('toolbar_insertcellright')+'"></td>');
				break;
			case "deletecell":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="deletecell" src="' + webeditor.buttonpath + 'deletecell.gif" alt="'+Text('toolbar_deletecell')+'" title="'+Text('toolbar_deletecell')+'"></td>');
				break;
			case "splitcell":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="splitcell" src="' + webeditor.buttonpath + 'splitcell.gif" alt="'+Text('toolbar_splitcell')+'" title="'+Text('toolbar_splitcell')+'"></td>');
				break;
			case "mergecells":
				document.writeln('<td><img unselectable="on" class="webeditor_icon_disabled" id="mergecells" src="' + webeditor.buttonpath + 'mergecells.gif" alt="'+Text('toolbar_mergecells')+'" title="'+Text('toolbar_mergecells')+'"></td>');
				break;
			case "find":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="find" src="' + webeditor.buttonpath + 'find.gif" alt="'+Text('toolbar_find')+'" title="'+Text('toolbar_find')+'"></td>');
				break;
			case "print":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="print" src="' + webeditor.buttonpath + 'print.gif" alt="'+Text('toolbar_print')+'" title="'+Text('toolbar_print')+'"></td>');
				break;
			case "printbreak":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="printbreak" src="' + webeditor.buttonpath + 'printbreak.gif" alt="'+Text('toolbar_printbreak')+'" title="'+Text('toolbar_printbreak')+'"></td>');
				break;
			case "viewdetails":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="viewdetails" src="' + webeditor.buttonpath + 'viewdetails.gif" alt="'+Text('toolbar_viewdetails')+'" title="'+Text('toolbar_viewdetails')+'"></td>');
				break;
			case "viewsource":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="viewsource" src="' + webeditor.buttonpath + 'viewsource.gif" alt="'+Text('toolbar_viewsource')+'" title="'+Text('toolbar_viewsource')+'"></td>');
				break;
			case "help":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="help" src="' + webeditor.buttonpath + 'help.gif" alt="'+Text('toolbar_help')+'" title="'+Text('toolbar_help')+'"></td>');
				break;
			case "form":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="form" src="' + webeditor.buttonpath + 'form.gif" alt="'+Text('toolbar_form')+'" title="'+Text('toolbar_form')+'"></td>');
				break;
			case "submitbutton":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="submitbutton" src="' + webeditor.buttonpath + 'submitbutton.gif" alt="'+Text('toolbar_submitbutton')+'" title="'+Text('toolbar_submitbutton')+'"></td>');
				break;
			case "button":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="button" src="' + webeditor.buttonpath + 'button.gif" alt="'+Text('toolbar_button')+'" title="'+Text('toolbar_button')+'"></td>');
				break;
			case "resetbutton":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="resetbutton" src="' + webeditor.buttonpath + 'resetbutton.gif" alt="'+Text('toolbar_resetbutton')+'" title="'+Text('toolbar_resetbutton')+'"></td>');
				break;
			case "backbutton":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="backbutton" src="' + webeditor.buttonpath + 'backbutton.gif" alt="'+Text('toolbar_backbutton')+'" title="'+Text('toolbar_backbutton')+'"></td>');
				break;
			case "imagebutton":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="imagebutton" src="' + webeditor.buttonpath + 'imagebutton.gif" alt="'+Text('toolbar_imagebutton')+'" title="'+Text('toolbar_imagebutton')+'"></td>');
				break;
			case "text":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="text" src="' + webeditor.buttonpath + 'text.gif" alt="'+Text('toolbar_text')+'" title="'+Text('toolbar_text')+'"></td>');
				break;
			case "hidden":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="hidden" src="' + webeditor.buttonpath + 'hidden.gif" alt="'+Text('toolbar_hidden')+'" title="'+Text('toolbar_hidden')+'"></td>');
				break;
			case "textarea":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="textarea" src="' + webeditor.buttonpath + 'textarea.gif" alt="'+Text('toolbar_textarea')+'" title="'+Text('toolbar_textarea')+'"></td>');
				break;
			case "password":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="password" src="' + webeditor.buttonpath + 'password.gif" alt="'+Text('toolbar_password')+'" title="'+Text('toolbar_password')+'"></td>');
				break;
			case "radio":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="radio" src="' + webeditor.buttonpath + 'radio.gif" alt="'+Text('toolbar_radio')+'" title="'+Text('toolbar_radio')+'"></td>');
				break;
			case "checkbox":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="checkbox" src="' + webeditor.buttonpath + 'checkbox.gif" alt="'+Text('toolbar_checkbox')+'" title="'+Text('toolbar_checkbox')+'"></td>');
				break;
			case "select":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="select" src="' + webeditor.buttonpath + 'select.gif" alt="'+Text('toolbar_select')+'" title="'+Text('toolbar_select')+'"></td>');
				break;
			case "file":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="file" src="' + webeditor.buttonpath + 'file.gif" alt="'+Text('toolbar_file')+'" title="'+Text('toolbar_file')+'"></td>');
				break;
			case "clean":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="clean" src="' + webeditor.buttonpath + 'clean.gif" alt="'+Text('toolbar_clean')+'" title="'+Text('toolbar_clean')+'"></td>');
				break;
			case "position":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="position" src="' + webeditor.buttonpath + 'position.gif" alt="'+Text('toolbar_position')+'" title="'+Text('toolbar_position')+'"></td>');
				break;
			case "forwards":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="forwards" src="' + webeditor.buttonpath + 'forwards.gif" alt="'+Text('toolbar_forwards')+'" title="'+Text('toolbar_forwards')+'"></td>');
				break;
			case "backwards":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="backwards" src="' + webeditor.buttonpath + 'backwards.gif" alt="'+Text('toolbar_backwards')+'" title="'+Text('toolbar_backwards')+'"></td>');
				break;
			case "front":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="front" src="' + webeditor.buttonpath + 'front.gif" alt="'+Text('toolbar_front')+'" title="'+Text('toolbar_front')+'"></td>');
				break;
			case "back":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="back" src="' + webeditor.buttonpath + 'back.gif" alt="'+Text('toolbar_back')+'" title="'+Text('toolbar_back')+'"></td>');
				break;
			case "abovetext":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="abovetext" src="' + webeditor.buttonpath + 'abovetext.gif" alt="'+Text('toolbar_abovetext')+'" title="'+Text('toolbar_abovetext')+'"></td>');
				break;
			case "belowtext":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="belowtext" src="' + webeditor.buttonpath + 'belowtext.gif" alt="'+Text('toolbar_belowtext')+'" title="'+Text('toolbar_belowtext')+'"></td>');
				break;
			case "box":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="box" src="' + webeditor.buttonpath + 'box.gif" alt="'+Text('toolbar_box')+'" title="'+Text('toolbar_box')+'"></td>');
				break;
			case "iframe":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="iframe" src="' + webeditor.buttonpath + 'iframe.gif" alt="'+Text('toolbar_iframe')+'" title="'+Text('toolbar_iframe')+'"></td>');
				break;
			case "preview":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="preview" src="' + webeditor.buttonpath + 'preview.gif" alt="'+Text('toolbar_preview')+'" title="'+Text('toolbar_preview')+'"></td>');
				break;
			case "anchor":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="anchor" src="' + webeditor.buttonpath + 'anchor.gif" alt="'+Text('toolbar_anchor')+'" title="'+Text('toolbar_anchor')+'"></td>');
				break;
			case "nobr":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="nobr" src="' + webeditor.buttonpath + 'nobr.gif" alt="'+Text('toolbar_nobr')+'" title="'+Text('toolbar_nobr')+'"></td>');
				break;
			case "import":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="import" src="' + webeditor.buttonpath + 'import.gif" alt="'+Text('toolbar_import')+'" title="'+Text('toolbar_import')+'"></td>');
				break;
			case "save":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="save" src="' + webeditor.buttonpath + 'save.gif" alt="'+Text('toolbar_save')+'" title="'+Text('toolbar_save')+'"></td>');
				break;
		// experimental / deprecated
			case "increasefontsize":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="increasefontsize" src="' + webeditor.buttonpath + 'increasefontsize.gif" alt="'+Text('toolbar_increasefontsize')+'" title="'+Text('toolbar_increasefontsize')+'"></td>');
				break;
			case "decreasefontsize":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="decreasefontsize" src="' + webeditor.buttonpath + 'decreasefontsize.gif" alt="'+Text('toolbar_decreasefontsize')+'" title="'+Text('toolbar_decreasefontsize')+'"></td>');
				break;
			case "usecss":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="usecss" src="' + webeditor.buttonpath + 'usecss.gif" alt="'+Text('toolbar_usecss')+'" title="'+Text('toolbar_usecss')+'"></td>');
				break;
			case "readonly":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="readonly" src="' + webeditor.buttonpath + 'readonly.gif" alt="'+Text('toolbar_readonly')+'" title="'+Text('toolbar_readonly')+'"></td>');
				break;
			case "spellcheck":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="spellcheck" src="' + webeditor.buttonpath + 'spellcheck.gif" alt="'+Text('toolbar_spellcheck')+'" title="'+Text('toolbar_spellcheck')+'"></td>');
				break;
			case "saveas":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="saveas" src="' + webeditor.buttonpath + 'saveas.gif" alt="'+Text('toolbar_saveas')+'" title="'+Text('toolbar_saveas')+'"></td>');
				break;
			case "stylesheet_toggle":
				document.writeln('<td><nobr>&nbsp; <input class="webeditor_input" id="stylesheet_toggle" type="checkbox" name="dummy" value="dummy" checked onClick="contenteditable_stylesheet_toggle(this.checked);">'+Text('toolbar_stylesheet_toggle')+'</nobr></td>');
				break;
			case "BlockDirLTR":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="BlockDirLTR" src="' + webeditor.buttonpath + 'ltr.gif" alt="'+Text('toolbar_ltr')+'" title="'+Text('toolbar_ltr')+'"></td>');
				break;
			case "BlockDirRTL":
				document.writeln('<td><img unselectable="on" class="webeditor_icon" id="BlockDirRTL" src="' + webeditor.buttonpath + 'rtl.gif" alt="'+Text('toolbar_rtl')+'" title="'+Text('toolbar_rtl')+'"></td>');
				break;
			default:
				var custom_function;
				try {
					custom_function = eval('webeditor_custom_toolbar_'+elements[j]);
				} catch (e) {
					custom_function = null;
				}
				if (custom_function) {
					try {
						eval('webeditor_custom_toolbar_'+elements[j]+'()');
					} catch(e) {
					}
				} else {
					if (Text('toolbar_'+elements[j]) != 'toolbar_'+elements[j]) {
						document.writeln('<td><img unselectable="on" class="webeditor_icon" id="'+elements[j]+'" src="' + webeditor.buttonpath + ''+elements[j]+'.gif" alt="'+Text('toolbar_'+elements[j])+'" title="'+Text('toolbar_'+elements[j])+'"></td>');
					} else {
						document.writeln('<td><img unselectable="on" class="webeditor_icon" id="'+elements[j]+'" src="' + webeditor.buttonpath + ''+elements[j]+'.gif" alt="'+elements[j]+'" title="'+elements[j]+'"></td>');
					}
				}
				break;
			}
		}
		document.writeln('</tr>');
	}
	document.writeln('</table>');
}

function HardCoreWebEditorStylesheet(stylesheet) {
	contenteditable_stylesheet_link(stylesheet);
}

function HardCoreWebEditorSubmit() {
	contenteditable_onSubmit();
}

function HardCoreWebEditorGetContent(id) {
	var content = contenteditable_formatContent(contenteditable_getContentBodyNode(id));
	content = contenteditable_decodeScriptTags(content);
	try {
		if (webeditor_custom_decode) content = webeditor_custom_decode(content);
	} catch(e) {
	}
	return content;
}

function HardCoreWebEditorGetContentSelection(id) {
	var content = contenteditable_getContentSelection(id);
//	var content = contenteditable_formatContent(.....);
	content = contenteditable_decodeScriptTags(content);
	try {
		if (webeditor_custom_decode) content = webeditor_custom_decode(content);
	} catch(e) {
	}
	return content;
}

function HardCoreWebEditorSetContent(content, id) {
	try {
		if (webeditor_custom_encode) content = webeditor_custom_encode(content);
	} catch(e) {
	}
	content = contenteditable_encodeScriptTags(content);
	contenteditable_setContent(content, id);
}

function HardCoreWebEditorPasteContent(content, id) {
	try {
		if (webeditor_custom_encode) content = webeditor_custom_encode(content);
	} catch(e) {
	}
	content = contenteditable_encodeScriptTags(content);
	contenteditable_pasteContent(content, id);
}

function HardCoreWebEditorCleanContent(all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
	cleanContent(all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div);
}

function HardCoreWebEditorCleanContentString(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
	return cleanContentSub(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div);
}



///////////////////////////////////////////////////////////////////////////////////////////////////
// Helper object used for global settings and callback from web editor dialog windows
///////////////////////////////////////////////////////////////////////////////////////////////////

webeditor.insertSpecialCharacter = insertSpecialCharacter;
webeditor.insertHyperlink = insertHyperlink;
webeditor.insertImage = insertImage;
webeditor.insertFlash = insertFlash;
webeditor.insertApplet = insertApplet;
webeditor.insertTable = insertTable;
webeditor.updateTable = updateTable;
webeditor.updateRow = updateRow;
webeditor.updateColumn = updateColumn;
webeditor.updateCell = updateCell;
webeditor.backColor = backColor;
webeditor.foreColor = foreColor;
webeditor.insertForm = insertForm;
webeditor.insertButton = insertButton;
webeditor.insertText = insertText;
webeditor.insertHidden = insertHidden;
webeditor.insertTextarea = insertTextarea;
webeditor.insertCheckbox = insertCheckbox;
webeditor.insertFile = insertFile;
webeditor.insertSelect = insertSelect;
webeditor.cleanContent = cleanContent;
webeditor.insertBox = insertBox;
webeditor.insertIframe = insertIframe;
webeditor.insertAnchor = insertAnchor;
webeditor.insertMailto = insertMailto;
webeditor.importFile = importFile;



///////////////////////////////////////////////////////////////////////////////////////////////////
// Init web editor toolbar event handlers
///////////////////////////////////////////////////////////////////////////////////////////////////

function webeditor_init() {
	contenteditable_init();
	var toolbar = contenteditable_toolbar();
	children = toolbar.getElementsByTagName('DIV');
	for (var i=0; i < children.length; i++) {
		if ((children[i].className == "webeditor_button") || (children[i].className == "webeditor_button_disabled")) {
			children[i].onmouseover = webeditor_button_mouseover;
			children[i].onmouseout = webeditor_button_mouseout;
			children[i].onmousedown = webeditor_button_mousedown;
			children[i].onmouseup = webeditor_button_mouseup;
			children[i].onclick = webeditor_click;
		}
	}
	children = toolbar.getElementsByTagName('IMG');
	for (var i=0; i < children.length; i++) {
		if ((children[i].className == "webeditor_icon") || (children[i].className == "webeditor_icon_disabled")) {
			children[i].onmouseover = webeditor_icon_mouseover;
			children[i].onmouseout = webeditor_icon_mouseout;
			children[i].onmousedown = webeditor_icon_mousedown;
			children[i].onmouseup = webeditor_icon_mouseup;
			children[i].onclick = webeditor_click;
		}
	}
	children = toolbar.getElementsByTagName('SELECT');
	for (var i=0; i < children.length; i++) {
		if ((children[i].className == "webeditor_select") || (children[i].className == "webeditor_select_disabled")) {
			if (! children[i].onchange) children[i].onchange = webeditor_select;
			webeditor_select_init(children[i]);
		}
	}
	webeditor.inited += 1;
}

function webeditor_onfocus() {
	if (webeditor.handling_event) return;
	try {
		if (webeditor_custom_onfocus) webeditor_custom_onfocus(contenteditable_focused_iframe().id);
	} catch(e) {
	}
}

function webeditor_onblur() {
	if (webeditor.handling_event) return;
	try {
		if (webeditor_custom_onblur) webeditor_custom_onblur(contenteditable_focused_iframe().id);
	} catch(e) {
	}
}

function webeditor_refreshToolbar(force) {
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = null;
	var start_time = (new Date()).getTime();
	if ((force == true) || (contenteditable_selection_range_parentNode() != webeditor.contenteditable_selection_range_parentNode)) {
		webeditor.contenteditable_selection_range_parentNode = contenteditable_selection_range_parentNode();
		var toolbar = contenteditable_toolbar();
		var positionable = contenteditable_positionable();
		var isTable = contenteditable_isTable();
		var isTableCaption = contenteditable_isTableCaption();
		var isRow = contenteditable_isRow();
		var isCell = contenteditable_isCell();

		var children = toolbar.getElementsByTagName('IMG');
		for (var i=0; i < children.length; i++) {
			try {
				if ((children[i].className == "webeditor_icon") || (children[i].className == "webeditor_icon_selected") || (children[i].className == "webeditor_icon_disabled")) {
					if (contenteditable_viewsource_status[contenteditable_focused]) {
						switch(children[i].id) {
						case "viewsource":
							if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
							break;
						case "help":
						case "preview":
						case "print":
						case "save":
							if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							break;
						default:
							if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							break;
						}
					} else {
						switch(children[i].id) {
						case "viewsource":
							if (contenteditable_viewsource_status[contenteditable_focused]) {
								if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "bold":
						case "italic":
						case "underline":
						case "strikethrough":
						case "superscript":
						case "subscript":
						case "justifyleft":
						case "justifyright":
						case "justifycenter":
						case "justifyfull":
						case "BlockDirLTR":
						case "BlockDirRTL":
							try {
								var selection_value = contenteditable_focused_document().queryCommandState(children[i].id);
								if (selection_value) {
									if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
								} else {
									if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
								}
							} catch(e) {
							}
							break;
						case "insertunorderedlist":
							var selection_value = contenteditable_formatblock_query().toLowerCase();
							if ((selection_value == "ul") || (selection_value == "bulleted list")) {
								if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "insertorderedlist":
							var selection_value = contenteditable_formatblock_query().toLowerCase();
							if ((selection_value == "ol") || (selection_value == "numbered list")) {
								if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "tableproperties":
						case "insertcaption":
							if (! isTable) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "deleterow":
							if ((! isRow) && (! isTableCaption)) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "rowproperties":
						case "insertrowhead":
						case "insertrowfoot":
						case "insertrowabove":
						case "insertrowbelow":
							if (! isRow) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "splitcellrows":
						case "columnproperties":
						case "insertcolumnleft":
						case "insertcolumnright":
						case "deletecolumn":
						case "splitcellcolumns":
						case "cellproperties":
						case "insertcellleft":
						case "insertcellright":
						case "deletecell":
						case "splitcell":
							if (! isCell) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "mergecells":
							if (! contenteditable_selection_container('table')) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "nobr":
							if (contenteditable_selection_container('nobr')) {
								if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "viewdetails":
							if (contenteditable_viewdetails_status[contenteditable_focused]) {
								if (children[i].className != "webeditor_icon_selected") children[i].className = "webeditor_icon_selected";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "position":
						case "forwards":
						case "backwards":
						case "front":
						case "back":
						case "abovetext":
						case "belowtext":
							if (! positionable) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "box":
							// MSIE may crash if inserting box inside text formatting tags
							if (contenteditable_selection_container('span') || contenteditable_selection_container('font') || contenteditable_selection_container('strong') || contenteditable_selection_container('em') || contenteditable_selection_container('u')) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "import":
							if ((webeditor.language == "") || (webeditor.language == "html")) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						case "spellcheck":
							if ((webeditor.language == "") || (webeditor.language == "html")) {
								if (children[i].className != "webeditor_icon_disabled") children[i].className = "webeditor_icon_disabled";
							} else {
								if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							}
							break;
						default:
							if (children[i].className != "webeditor_icon") children[i].className = "webeditor_icon";
							break;
						}
					}
				}
			} catch (e) {
			}
		}

		var children = toolbar.getElementsByTagName('SELECT');
		for (var i=0; i < children.length; i++) {
			if ((children[i].className == "webeditor_select") || (children[i].className == "webeditor_select_disabled")) {
				if (contenteditable_viewsource_status[contenteditable_focused]) {
					if (children[i].className != "webeditor_select_disabled") children[i].className = "webeditor_select_disabled";
					if (children[i].disabled != true) children[i].disabled = true;
				} else {
					if (children[i].className != "webeditor_select") children[i].className = "webeditor_select";
					if (children[i].disabled != false) children[i].disabled = false;
					switch(children[i].id) {
					case "fontname":
					case "fontsize":
						try {
							var selection_index = 0;
							var selection_value = ('' + contenteditable_focused_document().queryCommandValue(children[i].id)).toLowerCase();
							for (var j=0; j < children[i].options.length; j++) {
								if (selection_value.toLowerCase() == children[i].options[j].value.toLowerCase()) {
									selection_index = j;
									break;
								}
							}
							if ((children[i].selectedIndex != selection_index) && (! webeditor.select_focused[children[i].id])) children[i].selectedIndex = selection_index;
						} catch(e) {
						}
						break;
					case "formatblock":
						try {
							var selection_index = 0;
							var selection_name = contenteditable_formatblock_query();
							var selection_value = '<' + selection_name + '>';
							for (var j=0; j < children[i].options.length; j++) {
								if (selection_value.toLowerCase() == children[i].options[j].value.toLowerCase()) {
									selection_index = j;
									break;
								}
								if (selection_name.toLowerCase() == children[i].options[j].text.toLowerCase()) {
									selection_index = j;
									break;
								}
							}
							if ((children[i].selectedIndex != selection_index) && (! webeditor.select_focused[children[i].id])) children[i].selectedIndex = selection_index;
						} catch(e) {
						}
						break;
					case "formatclass":
						try {
							if ((! webeditor.stylesheetclassnames) || (! webeditor.stylesheetclassnames.length)) {
								webeditor.stylesheetclassnames = new Array();
								webeditor.stylesheetclassvalues = new Array();
								var mydocument = contenteditable_focused_document();
								for (var stylesheet=0; stylesheet<mydocument.styleSheets.length; stylesheet++) {
									var cssrules = contenteditable_document_stylesheets_cssrules(mydocument.styleSheets[stylesheet]);
									for (var rule=0; rule<cssrules.length; rule++) {
										var selectors = cssrules[rule].selectorText.split(",");
										var selectorText = cssrules[rule].selectorText;
										for (selector in selectors) {
											selectorText = selectors[selector];
											if (selectorText.match(/^.*\.([-a-zA-Z0-9_]*).*$/)) {
												var myclassname = selectorText.replace(/^.*\.([a-zA-Z0-9_]*).*$/, "$1");
												var myclassvalue = myclassname;
												try {
													if (webeditor_custom_formatclass_option) myclassname = webeditor_custom_formatclass_option(myclassname);
												} catch(e) {
												}
												if (myclassname && myclassvalue && (! webeditor.stylesheetclassvalues[myclassname])) {
													webeditor.stylesheetclassnames[webeditor.stylesheetclassnames.length] = myclassname;
													webeditor.stylesheetclassvalues[myclassname] = myclassvalue;
												}
											}
										}
									}
								}
							}
							webeditor.stylesheetclassnames = webeditor.stylesheetclassnames.sort();
							var selection_index = 0;
							var selection_option = 1;
							var selection_name = contenteditable_formatclass_query();
							var selection_value = '<' + selection_name + '>';
							for (j in webeditor.stylesheetclassnames) {
								var myclassname = webeditor.stylesheetclassnames[j];
								var myclassvalue = webeditor.stylesheetclassvalues[myclassname];
								if (myclassname) {
									if ((! children[i].options[selection_option]) || (children[i].options[selection_option].value != myclassvalue)) {
										var myoption = new Option(myclassname, myclassvalue, false, false);
										children[i].options[selection_option] = myoption;
									}
									if (selection_value.toLowerCase() == children[i].options[selection_option].value.toLowerCase()) {
										selection_index = selection_option;
									}
									if (selection_name.toLowerCase() == children[i].options[selection_option].value.toLowerCase()) {
										selection_index = selection_option;
									}
									if (selection_name.toLowerCase() == children[i].options[selection_option].text.toLowerCase()) {
										selection_index = selection_option;
									}
									selection_option++;
								}
							}
							for (j=children[i].options.length; j>selection_option; j--) {
								children[i].options[j-1] = null;
							}
							if ((children[i].selectedIndex != selection_index) && (! webeditor.select_focused[children[i].id])) children[i].selectedIndex = selection_index;
						} catch(e) {
						}
						break;
					}
				}
			}
		}

		var children = toolbar.getElementsByTagName('INPUT');
		for (var i=0; i < children.length; i++) {
			if ((children[i].className == "webeditor_input") || (children[i].className == "webeditor_input_disabled")) {
				if (contenteditable_viewsource_status[contenteditable_focused]) {
					if (children[i].className != "webeditor_input_disabled") children[i].className = "webeditor_input_disabled";
					if (children[i].disabled != true) children[i].disabled = true;
				} else {
					if (children[i].className != "webeditor_input") children[i].className = "webeditor_input";
					if (children[i].disabled != false) children[i].disabled = false;
				}
			}
		}

		var iframe;
		if (iframe = contenteditable_focused_iframe()) {
			if (DOM_inspector = document.getElementById('webeditor_DOM_inspector_'+iframe.id) || document.getElementById('webeditor_DOM_inspector')) {
				if ((parentnode = contenteditable_selection_container()) && (! contenteditable_viewsource_status[contenteditable_focused])) {
					var attributes = "";
					if (parentnode && parentnode.attributes && (parentnode.nodeName != "BODY")) {
						attributes = contenteditable_node_attributes(parentnode);
					}
					var level = 0;
					var hierarchy = "";
					for (var node=parentnode; (node && (node.nodeType == 1)); node=node.parentNode) {
						if ((node.nodeName == "BODY") && (node.className != "HardCoreWebEditor")) {
							hierarchy = "";
							attributes = "";
							break;
						}
						if ((node.nodeName != "HTML") && (node.nodeName != "BODY") && (node.nodeName != "HEAD") && (node.nodeName != "BASE")) {
							if (hierarchy) hierarchy = " > " + hierarchy;
							hierarchy = '</a>' + hierarchy;
							hierarchy = node.nodeName + hierarchy;
							hierarchy = '<a href="javascript:webeditor_select_parentnode(' + level + ', \'' + node.nodeName + '\', \'' + iframe.id + '\');">' + hierarchy;
						} else if ((node.nodeName != "HEAD") && (node.nodeName != "BASE")) {
							if (hierarchy) hierarchy = " > " + hierarchy;
							hierarchy = node.nodeName + hierarchy;
						}
						level++;
					}
					if (hierarchy) {
						switch (parentnode.nodeName) {
						case "HTML":
						case "BODY":
						case "TABLE":
						case "THEAD":
						case "TBODY":
						case "TFOOT":
						case "TR":
						case "TD":
						case "OL":
						case "UL":
						case "LI":
							remove = '';
							break;
						default:
							remove = '<a style="color: red;" href="javascript:webeditor_remove_parentnode(\'' + iframe.id + '\');">' + Text('dominspector_remove') + '</a>';
							break;
						}
						var html = hierarchy;
						if (attributes) html += ' ' + attributes;
						if (remove) html += ' &lt;&lt;&lt; ' + remove;
						if (DOM_inspector.HardCoreDOMInspectorInnerHTML != html) {
							DOM_inspector.HardCoreDOMInspectorInnerHTML = html;
							DOM_inspector.innerHTML = html;
						}
					}
				} else {
					if (DOM_inspector.HardCoreDOMInspectorInnerHTML != "&nbsp;") {
						DOM_inspector.HardCoreDOMInspectorInnerHTML = "&nbsp;";
						DOM_inspector.innerHTML = "&nbsp;";
					}
				}
			}
		}
	}
	var end_time = (new Date()).getTime();
	var timeout = (end_time-start_time)*10;
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = setTimeout(webeditor_refreshToolbar, timeout);
}

function webeditor_select_parentnode(select_level, select_node, id) {
	if ((iframe = contenteditable_focused_iframe()) && ((iframe.id == id) || (! id)))  {
		var parentnode = contenteditable_selection_container();
		var level = 0;
		for (var node=parentnode; (node && (node.nodeType == 1)); node=node.parentNode) {
			if (level == select_level) {
				contenteditable_selection_node(node);
				contenteditable_focused_contentwindow().focus();
			}
			level++;
		}
		webeditor_refreshToolbar(true);
	}
}

function webeditor_remove_parentnode(id) {
	if ((iframe = contenteditable_focused_iframe()) && ((iframe.id == id) || (! id)))  {
		var node = contenteditable_selection_container();
		switch (node.nodeName) {
		case "HTML":
		case "BODY":
		case "TABLE":
		case "THEAD":
		case "TBODY":
		case "TFOOT":
		case "TR":
		case "TD":
		case "OL":
		case "UL":
		case "LI":
			break;
		default:
			contenteditable_remove_parentnode(node);
			webeditor_refreshToolbar(true);
			contenteditable_focused_contentwindow().focus();
			break;
		}
	}
}

// Web editor toolbar event handlers

function webeditor_event(evt) {
	var my_event = (evt) ? evt : ((event) ? event : null);
	webeditor.selection_node = "";
	contenteditable_undo_init();
	contenteditable_event_paste(my_event);
	contenteditable_event_enter(my_event);
	contenteditable_event_delete(my_event);
//	if (contenteditable_event_ctrlkey(event) && contenteditable_event_key(event)) {
//		// check for and handle CTRL+key events here
//		contenteditable_event_stop(event);
//	}
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = setTimeout(webeditor_refreshToolbar, 100);
}

function webeditor_button_mousedown() {
	if (this.className != "webeditor_button_disabled") this.className = "webeditor_button_mousedown";
}

function webeditor_button_mouseup() {
	if (this.className != "webeditor_button_disabled") this.className = "webeditor_button_mouseup";
}

function webeditor_button_mouseout() {
	webeditor.handling_event = false;
	if (this.className != "webeditor_button_disabled") this.className = "webeditor_button";
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = setTimeout(webeditor_refreshToolbar, 100);
}

function webeditor_button_mouseover() {
	if (this.className != "webeditor_button_disabled") this.className = "webeditor_button_mouseover";
	webeditor.handling_event = true;
}

function webeditor_icon_mousedown() {
	if (this.className != "webeditor_icon_disabled") this.className = "webeditor_icon_mousedown";
}

function webeditor_icon_mouseup() {
	if (this.className != "webeditor_icon_disabled") this.className = "webeditor_icon_mouseup";
}

function webeditor_icon_mouseout() {
	webeditor.handling_event = false;
	if (this.className != "webeditor_icon_disabled") this.className = "webeditor_icon";
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = setTimeout(webeditor_refreshToolbar, 100);
}

function webeditor_icon_mouseover() {
	if (this.className != "webeditor_icon_disabled") this.className = "webeditor_icon_mouseover";
	webeditor.handling_event = true;
}

function webeditor_click() {
	contenteditable_focused_contentwindow().focus();
	if (this.className == "webeditor_icon_disabled") return;
	var custom_function;
	try {
		custom_function = eval('webeditor_custom_'+this.id);
	} catch (e) {
		custom_function = null;
	}
	if (custom_function) {
		try {
			contenteditable_undo_save();
			eval('webeditor_custom_'+this.id+'()');
			contenteditable_undo_save();
		} catch(e) {
		}
	} else if (this.id == "help") {
		webeditor_help();
	} else if (this.id == "preview") {
		contenteditable_preview();
	} else if (this.id == "print") {
		contenteditable_focused_contentwindow().focus();
		if (! contenteditable_print(this.id)) {
			alert(Text('print_alert'));
		}
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "save") {
		contenteditable_save();
	} else if (this.id == "viewsource") {
		contenteditable_undo_save();
		contenteditable_viewsource(this.checked);
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (contenteditable_viewsource_status[contenteditable_focused]) {
		// ignore
	} else if (this.id == "specialcharacter") {
		webeditor_specialcharacter();
	} else if (this.id == "createtable") {
		webeditor_createtable();
	} else if (this.id == "tableproperties") {
		webeditor_tableproperties();
	} else if (this.id == "rowproperties") {
		webeditor_rowproperties();
	} else if (this.id == "columnproperties") {
		webeditor_columnproperties();
	} else if (this.id == "cellproperties") {
		webeditor_cellproperties();
	} else if (this.id == "insertcaption") {
		contenteditable_undo_save();
		contenteditable_insertcaption();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertrowhead") {
		contenteditable_undo_save();
		contenteditable_insertrowhead();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertrowfoot") {
		contenteditable_undo_save();
		contenteditable_insertrowfoot();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertrowabove") {
		contenteditable_undo_save();
		contenteditable_insertrowabove();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertrowbelow") {
		contenteditable_undo_save();
		contenteditable_insertrowbelow();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "deleterow") {
		contenteditable_undo_save();
		contenteditable_deleterow();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertcolumnleft") {
		contenteditable_undo_save();
		contenteditable_insertcolumnleft();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertcolumnright") {
		contenteditable_undo_save();
		contenteditable_insertcolumnright();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "deletecolumn") {
		contenteditable_undo_save();
		contenteditable_deletecolumn();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertcellleft") {
		contenteditable_undo_save();
		contenteditable_insertcellleft();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertcellright") {
		contenteditable_undo_save();
		contenteditable_insertcellright();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "deletecell") {
		contenteditable_undo_save();
		contenteditable_deletecell();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "mergecells") {
		contenteditable_undo_save();
		contenteditable_mergecells();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "splitcell") {
		contenteditable_undo_save();
		contenteditable_splitcell();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "splitcellrows") {
		contenteditable_undo_save();
		contenteditable_splitcellrows();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "splitcellcolumns") {
		contenteditable_undo_save();
		contenteditable_splitcellcolumns();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "insertmedia") {
		webeditor_insertmedia();
	} else if (this.id == "insertimage") {
		webeditor_insertmedia();
	} else if (this.id == "insertflash") {
		webeditor_insertmedia();
	} else if (this.id == "insertapplet") {
		webeditor_insertmedia();
	} else if (this.id == "createlink") {
		webeditor_createlink();
	} else if (this.id == "mailto") {
		webeditor_mailto();
	} else if (this.id == "backcolor") {
		webeditor_backcolor();
	} else if (this.id == "forecolor") {
		webeditor_forecolor();
	} else if (this.id == "form") {
		webeditor_form();
	} else if (this.id == "button") {
		webeditor_button();
	} else if (this.id == "submitbutton") {
		webeditor_submitbutton();
	} else if (this.id == "resetbutton") {
		webeditor_resetbutton();
	} else if (this.id == "backbutton") {
		webeditor_backbutton();
	} else if (this.id == "imagebutton") {
		webeditor_imagebutton();
	} else if (this.id == "text") {
		webeditor_text();
	} else if (this.id == "password") {
		webeditor_password();
	} else if (this.id == "textarea") {
		webeditor_textarea();
	} else if (this.id == "checkbox") {
		webeditor_checkbox();
	} else if (this.id == "radio") {
		webeditor_radio();
	} else if (this.id == "select") {
		webeditor_selectlist();
	} else if (this.id == "hidden") {
		webeditor_hidden();
	} else if (this.id == "file") {
		webeditor_file();
	} else if (this.id == "clean") {
		webeditor_clean();
	} else if (this.id == "position") {
		contenteditable_undo_save();
		contenteditable_position();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "forwards") {
		contenteditable_undo_save();
		contenteditable_forwards();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "backwards") {
		contenteditable_undo_save();
		contenteditable_backwards();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "front") {
		contenteditable_undo_save();
		contenteditable_front();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "back") {
		contenteditable_undo_save();
		contenteditable_back();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "abovetext") {
		contenteditable_undo_save();
		contenteditable_abovetext();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "belowtext") {
		contenteditable_undo_save();
		contenteditable_belowtext();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "box") {
		webeditor_box();
	} else if (this.id == "iframe") {
		webeditor_iframe();
	} else if (this.id == "import") {
		webeditor_import();
	} else if (this.id == "spellcheck") {
		contenteditable_spellcheck();
	} else if (this.id == "nobr") {
		contenteditable_undo_save();
		contenteditable_nobr();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "anchor") {
		webeditor_anchor();
	} else if (this.id == "printbreak") {
		contenteditable_undo_save();
		contenteditable_printbreak();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "undo") {
		contenteditable_undo();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "redo") {
		contenteditable_redo();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "viewdetails") {
		contenteditable_undo_save();
		contenteditable_viewdetails(this.checked);
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "find") {
		contenteditable_focused_contentwindow().focus();
		if (! contenteditable_find(this.id)) {
			alert(Text('find_alert'));
		}
	} else if (this.id == "cut") {
		contenteditable_undo_save();
		if (! contenteditable_execcommand(this.id)) {
			alert(Text('cut_alert'));
		}
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "copy") {
		if (! contenteditable_execcommand(this.id)) {
			alert(Text('copy_alert'));
		}
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "paste") {
		if (! contenteditable_execcommand(this.id)) {
			alert(Text('paste_alert'));
		}
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "saveas") {
		if (! contenteditable_execcommand(this.id)) {
			alert(Text('saveas_alert'));
		}
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "removeformat") {
		contenteditable_undo_save();
		contenteditable_execcommand(this.id);
		contenteditable_removeformat();
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "selectall") {
		contenteditable_selection_node(contenteditable_focused_document().body);
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "BlockDirLTR") {
		contenteditable_BlockDirLTR(this.id);
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "BlockDirRTL") {
		contenteditable_BlockDirRTL(this.id);
		contenteditable_focused_contentwindow().focus();
	} else {
		contenteditable_undo_save();
		contenteditable_execcommand(this.id);
		contenteditable_undo_save();
		contenteditable_focused_contentwindow().focus();
	}
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = setTimeout(webeditor_refreshToolbar, 100);
}

function webeditor_select() {
	if ((this.id != "undo") && (this.id != "redo")) contenteditable_undo_save();
	if (this.id == "formatclass") {
		webeditor_select_focus();
		contenteditable_formatclass(this.id,this.value);
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "formatblock") {
		webeditor_select_focus();
		contenteditable_formatblock(this.id,this.value);
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "fontname") {
		webeditor_select_focus();
		contenteditable_fontname(this.id,this.value);
		contenteditable_focused_contentwindow().focus();
	} else if (this.id == "fontsize") {
		webeditor_select_focus();
		contenteditable_fontsize(this.id,this.value);
		contenteditable_focused_contentwindow().focus();
	} else {
		webeditor_select_focus();
		var custom_function;
		try {
			custom_function = eval('webeditor_custom_'+this.id);
		} catch (e) {
			custom_function = null;
		}
		if (custom_function) {
			try {
				eval('webeditor_custom_'+this.id+'(\''+this.value+'\')');
			} catch(e) {
			}
		} else {
			contenteditable_execcommand(this.id,this.value);
		}
		contenteditable_focused_contentwindow().focus();
	}
	if ((this.id != "undo") && (this.id != "redo")) contenteditable_undo_save();
	if (webeditor.refreshToolbarTimeout) clearTimeout(webeditor.refreshToolbarTimeout);
	webeditor.refreshToolbarTimeout = setTimeout(webeditor_refreshToolbar, 100);
}

function webeditor_select_deactivate(evt) {
	if (evt && evt.target && evt.target.id) webeditor.select_focused[evt.target.id] = false;
	contenteditable_focused_contentwindow().focus();
	return true;
}

function webeditor_select_blur(evt) {
	if (evt && evt.target && evt.target.id) webeditor.select_focused[evt.target.id] = false;
	return true;
}

function webeditor_help() {
	if ((typeof(webeditor.help_window) == "undefined") || webeditor.help_window.closed) {
		webeditor.help_window = window.open(webeditor.rootpath+"help2.html?editor=webeditor", "help_window", "scrollbars=yes,width=600,height=435,resizable=yes,status=yes", true);
	}
	webeditor.help_window.focus();
}

function webeditor_specialcharacter() {
	if ((typeof(webeditor.specialcharacter_window) == "undefined") || webeditor.specialcharacter_window.closed) {
		webeditor.specialcharacter_window = window.open(webeditor.rootpath+"specialcharacter.html?editor=webeditor", "specialcharacter_window", "scrollbars=yes,width=500,height=425,resizable=yes,status=yes", true);
	}
	webeditor.specialcharacter_window.focus();
}

function webeditor_import() {
	if ((typeof(webeditor.import_window) == "undefined") || webeditor.import_window.closed) {
		if (webeditor.language) {
			webeditor.import_window = window.open(webeditor.rootpath+"import."+webeditor.language+"?editor=webeditor", "import_window", "scrollbars=yes,width=350,height=200,resizable=yes,status=yes", true);
			webeditor.import_window.focus();
		} else {
//			webeditor.import_window = window.open(webeditor.rootpath+"import.html?editor=webeditor", "import_window", "scrollbars=yes,width=350,height=200,resizable=yes,status=yes", true);
//			webeditor.import_window.focus();
		}
	}
}

function webeditor_createtable() {
	if ((typeof(webeditor.table_window) == "undefined") || webeditor.table_window.closed) {
		if (webeditor.language) {
			webeditor.table_window = window.open(webeditor.rootpath+"table."+webeditor.language+"?editor=webeditor", "table_window", "scrollbars=yes,width=475,height=375,resizable=yes,status=yes", true);
		} else {
			webeditor.table_window = window.open(webeditor.rootpath+"table.html?editor=webeditor", "table_window", "scrollbars=yes,width=475,height=375,resizable=yes,status=yes", true);
		}
	}
	webeditor.table_window.focus();
}

function webeditor_tableproperties() {
	var table;
	if (table = contenteditable_isTable()) {
		if ((typeof(webeditor.tableproperties_window) == "undefined") || webeditor.tableproperties_window.closed) {
			var border = contenteditable_getAttribute(table, "border") || "";
			var width = contenteditable_getAttribute(table, "width") || "";
			var height = contenteditable_getAttribute(table, "height") || "";
			var cellpadding = contenteditable_getAttribute(table, "cellPadding") || "";
			var cellspacing = contenteditable_getAttribute(table, "cellSpacing") || "";
			var bgcolor = contenteditable_getAttribute(table, "bgColor") || "";
			var bordercolor = contenteditable_getAttribute(table, "borderColor") || "";
			var background = contenteditable_getAttribute(table, "background") || "";
			var htmlclass = contenteditable_getAttribute(table, "class") || "";
			var htmlid = contenteditable_getAttribute(table, "id") || "";
			if (webeditor.language) {
				webeditor.tableproperties_window = window.open(webeditor.rootpath+"tableproperties."+webeditor.language+"?editor=webeditor&border="+escape(border)+"&width="+escape(width)+"&height="+escape(height)+"&cellpadding="+escape(cellpadding)+"&cellspacing="+escape(cellspacing)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid), "tableproperties_window", "scrollbars=yes,width=475,height=375,resizable=yes,status=yes", true);
			} else {
				webeditor.tableproperties_window = window.open(webeditor.rootpath+"tableproperties.html?editor=webeditor&border="+escape(border)+"&width="+escape(width)+"&height="+escape(height)+"&cellpadding="+escape(cellpadding)+"&cellspacing="+escape(cellspacing)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid), "tableproperties_window", "scrollbars=yes,width=475,height=375,resizable=yes,status=yes", true);
			}
		}
		webeditor.tableproperties_window.focus();
	}
}

function webeditor_rowproperties() {
	var row;
	if (row = contenteditable_isRow()) {
		if ((typeof(webeditor.rowproperties_window) == "undefined") || webeditor.rowproperties_window.closed) {
			var align = contenteditable_getAttribute(row, "align") || "";
			var valign = contenteditable_getAttribute(row, "vAlign") || "";
			var bgcolor = contenteditable_getAttribute(row, "bgColor") || "";
			var bordercolor = contenteditable_getAttribute(row, "borderColor") || "";
			var background = contenteditable_getAttribute(row, "background") || "";
			var htmlclass = contenteditable_getAttribute(row, "class") || "";
			var htmlid = contenteditable_getAttribute(row, "id") || "";
			if (webeditor.language) {
				webeditor.rowproperties_window = window.open(webeditor.rootpath+"rowproperties."+webeditor.language+"?editor=webeditor&align="+escape(align)+"&valign="+escape(valign)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid), "rowproperties_window", "scrollbars=yes,width=475,height=350,resizable=yes,status=yes", true);
			} else {
				webeditor.rowproperties_window = window.open(webeditor.rootpath+"rowproperties.html?editor=webeditor&align="+escape(align)+"&valign="+escape(valign)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid), "rowproperties_window", "scrollbars=yes,width=475,height=350,resizable=yes,status=yes", true);
			}
		}
		webeditor.rowproperties_window.focus();
	}
}

function webeditor_columnproperties() {
	var cell;
	if (cell = contenteditable_isCell()) {
		if ((typeof(webeditor.columnproperties_window) == "undefined") || webeditor.columnproperties_window.closed) {
			var width = contenteditable_getAttribute(cell, "width") || "";
			var height = contenteditable_getAttribute(cell, "height") || "";
			var align = contenteditable_getAttribute(cell, "align") || "";
			var valign = contenteditable_getAttribute(cell, "vAlign") || "";
			var bgcolor = contenteditable_getAttribute(cell, "bgColor") || "";
			var bordercolor = contenteditable_getAttribute(cell, "borderColor") || "";
			var background = contenteditable_getAttribute(cell, "background") || "";
			var htmlclass = contenteditable_getAttribute(cell, "class") || "";
			if (webeditor.language) {
				webeditor.columnproperties_window = window.open(webeditor.rootpath+"columnproperties."+webeditor.language+"?editor=webeditor&width="+escape(width)+"&height="+escape(height)+"&colspan="+"&align="+escape(align)+"&valign="+escape(valign)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass), "columnproperties_window", "scrollbars=yes,width=475,height=450,resizable=yes,status=yes", true);
			} else {
				webeditor.columnproperties_window = window.open(webeditor.rootpath+"columnproperties.html?editor=webeditor&width="+escape(width)+"&height="+escape(height)+"&colspan="+"&align="+escape(align)+"&valign="+escape(valign)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass), "columnproperties_window", "scrollbars=yes,width=475,height=450,resizable=yes,status=yes", true);
			}
		}
		webeditor.columnproperties_window.focus();
	}
}

function webeditor_cellproperties() {
	var cell;
	if (cell = contenteditable_isCell()) {
		if ((typeof(webeditor.cellproperties_window) == "undefined") || webeditor.cellproperties_window.closed) {
			var width = contenteditable_getAttribute(cell, "width") || "";
			var height = contenteditable_getAttribute(cell, "height") || "";
			var colspan = contenteditable_getAttribute(cell, "colSpan") || "";
			var rowspan = contenteditable_getAttribute(cell, "rowSpan") || "";
			var align = contenteditable_getAttribute(cell, "align") || "";
			var valign = contenteditable_getAttribute(cell, "vAlign") || "";
			var bgcolor = contenteditable_getAttribute(cell, "bgColor") || "";
			var bordercolor = contenteditable_getAttribute(cell, "borderColor") || "";
			var background = contenteditable_getAttribute(cell, "background") || "";
			var htmlclass = contenteditable_getAttribute(cell, "class") || "";
			var htmlid = contenteditable_getAttribute(cell, "id") || "";
			if (webeditor.language) {
				webeditor.cellproperties_window = window.open(webeditor.rootpath+"cellproperties."+webeditor.language+"?editor=webeditor&width="+escape(width)+"&height="+escape(height)+"&colspan="+escape(colspan)+"&rowspan="+escape(rowspan)+"&align="+escape(align)+"&valign="+escape(valign)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid), "cellproperties_window", "scrollbars=yes,width=475,height=450,resizable=yes,status=yes", true);
			} else {
				webeditor.cellproperties_window = window.open(webeditor.rootpath+"cellproperties.html?editor=webeditor&width="+escape(width)+"&height="+escape(height)+"&colspan="+escape(colspan)+"&rowspan="+escape(rowspan)+"&align="+escape(align)+"&valign="+escape(valign)+"&bgcolor="+escape(bgcolor)+"&bordercolor="+escape(bordercolor)+"&background="+escape(background)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid), "cellproperties_window", "scrollbars=yes,width=475,height=450,resizable=yes,status=yes", true);
			}
		}
		webeditor.cellproperties_window.focus();
	}
}

function webeditor_insertmedia() {
	var href = '';
	var border = '';
	var alt = '';
	var width = '';
	var height = '';
	var vspace = '';
	var hspace = '';
	var align = '';
	var onmouseover = '';
	var onmouseout = '';
	var htmlid = '';
	var htmlclass = '';
	var mediaclass = '';
	var text = contenteditable_selection_text();
	var element = contenteditable_selection_container('img');
	if (element) {
		contenteditable_selection_node(element);
		href = contenteditable_getAttribute(element, "src") || '';
		border = contenteditable_getAttribute(element, "border") || '';
		alt = contenteditable_getAttribute(element, "alt") || '';
		width = contenteditable_getAttribute(element, "width") || '';
		height = contenteditable_getAttribute(element, "height") || '';
		vspace = contenteditable_getAttribute(element, "vspace") || '';
		hspace = contenteditable_getAttribute(element, "hspace") || '';
		align = contenteditable_getAttribute(element, "align") || '';
		onmouseover = contenteditable_getAttribute(element, "onMouseOver") || '';
		onmouseout = contenteditable_getAttribute(element, "onMouseOut") || '';
		htmlclass = contenteditable_getAttribute(element, "class") || "";
		htmlid = contenteditable_getAttribute(element, "id") || "";
		mediaclass = "image";
	} else {
		element = contenteditable_selection_container('object');
		if (contenteditable_getAttribute(element, 'classid') == "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000") {
			href = "";
			for (var node=element.firstChild; node; node=node.nextSibling) {
				if ((node.nodeName == "PARAM") && (contenteditable_getAttribute(node, 'name').toLowerCase() == "movie")) {
					href = contenteditable_getAttribute(node, 'value') || '';
				}
			}
			width = contenteditable_getAttribute(element, "width") || '';
			height = contenteditable_getAttribute(element, "height") || '';
			htmlclass = contenteditable_getAttribute(element, "class") || "";
			htmlid = contenteditable_getAttribute(element, "id") || "";
			mediaclass = "flash";
			contenteditable_selection_node(element);
		} else if (contenteditable_getAttribute(element, 'classid') == "clsid:CAFEEFAC-0014-0002-0000-ABCDEFFEDCBA") {
			href = "";
			for (var node=element.firstChild; node; node=node.nextSibling) {
				if ((node.nodeName == "PARAM") && (contenteditable_getAttribute(node, 'name') == "codebase")) {
					href = contenteditable_getAttribute(node, 'value') + href;
				} else if ((node.nodeName == "PARAM") && (contenteditable_getAttribute(node, 'name') == "code")) {
					href = href + contenteditable_getAttribute(node, 'value') || '';
				}
			}
			width = contenteditable_getAttribute(element, "width") || '';
			height = contenteditable_getAttribute(element, "height") || '';
			htmlclass = contenteditable_getAttribute(element, "class") || "";
			htmlid = contenteditable_getAttribute(element, "id") || "";
			mediaclass = "applet";
			contenteditable_selection_node(element);
		}
	}

	if ((typeof(webeditor.image_window) == "undefined") || webeditor.image_window.closed) {
		if ((webeditorType == "dhtml") && (majorVersion == 4)) {
			webeditor.image_window = window.open(webeditor.rootpath+"media.html?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass),"image_window","scrollbars=yes,width=550,height=600,resizable=yes,status=yes");
		} else if (webeditor.manager && webeditor.language) {
			webeditor.image_window = window.open(webeditor.rootpath+"media"+webeditor.manager+"."+webeditor.language+"?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass),"image_window","scrollbars=yes,width=750,height=500,resizable=yes,status=yes");
		} else if (webeditor.manager) {
			webeditor.image_window = window.open(webeditor.rootpath+"media"+webeditor.manager+".html?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass),"image_window","scrollbars=yes,width=750,height=500,resizable=yes,status=yes");
		} else {
			webeditor.image_window = window.open(webeditor.rootpath+"media.html?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass),"image_window","scrollbars=yes,width=550,height=600,resizable=yes,status=yes");
		}
	} else {
		if ((webeditorType == "dhtml") && (majorVersion == 4)) {
			webeditor.image_window.document.location = webeditor.rootpath+"media.html?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass);
		} else if (webeditor.manager && webeditor.language) {
			webeditor.image_window.document.location = webeditor.rootpath+"media"+webeditor.manager+"."+webeditor.language+"?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass);
		} else if (webeditor.manager) {
			webeditor.image_window.document.location = webeditor.rootpath+"media"+webeditor.manager+".html?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass);
		} else {
			webeditor.image_window.document.location = webeditor.rootpath+"media.html?editor=webeditor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&onmouseover="+escape(onmouseover)+"&onmouseout="+escape(onmouseout)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid)+"&mediaclass="+escape(mediaclass);
		}
	}
	webeditor.image_window.focus();
}

function webeditor_createlink() {
	var text = '';
	var href = '';
	var target = '';
	var htmlid = '';
	var htmlclass = '';
	var onclick = '';

	var text = contenteditable_selection_text();
	var element = contenteditable_selection_container('a');
	if (element) {
		contenteditable_selection_node(element);
		text = element.innerHTML;
		href = contenteditable_getAttribute(element, "href") || '';
		href = href.replace(/&amp;/gi, "&");
		target = contenteditable_getAttribute(element, "target") || '';
		htmlclass = contenteditable_getAttribute(element, "class") || "";
		htmlid = contenteditable_getAttribute(element, "id") || "";
		onclick = contenteditable_getAttribute(onclick, "onclick") || "";
	}

	if ((typeof(webeditor.hyperlink_window) == "undefined") || webeditor.hyperlink_window.closed) {
		if (webeditor.manager && webeditor.language) {
			webeditor.hyperlink_window = window.open(webeditor.rootpath+"hyperlink"+webeditor.manager+"."+webeditor.language+"?editor=webeditor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text) ,"hyperlink_window","scrollbars=yes,width=750,height=450,resizable=yes,status=yes",true);
		} else if (webeditor.manager) {
			webeditor.hyperlink_window = window.open(webeditor.rootpath+"hyperlink"+webeditor.manager+".html?editor=webeditor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text) ,"hyperlink_window","scrollbars=yes,width=750,height=450,resizable=yes,status=yes",true);
		} else {
			webeditor.hyperlink_window = window.open(webeditor.rootpath+"hyperlink.html?editor=webeditor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text) ,"hyperlink_window","scrollbars=yes,width=475,height=400,resizable=yes,status=yes",true);
		}
	} else {
		if (webeditor.manager && webeditor.language) {
			webeditor.hyperlink_window.document.location = webeditor.rootpath+"hyperlink"+webeditor.manager+"."+webeditor.language+"?editor=webeditor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text);
		} else if (webeditor.manager) {
			webeditor.hyperlink_window.document.location = webeditor.rootpath+"hyperlink"+webeditor.manager+".html?editor=webeditor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text);
		} else {
			webeditor.hyperlink_window.document.location = webeditor.rootpath+"hyperlink.html?editor=webeditor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text);
		}
	}
	webeditor.hyperlink_window.focus();
}

function webeditor_mailto() {
	var text = '';
	var email = '';
	var subject = '';
	var htmlid = '';
	var htmlclass = '';

	var text = contenteditable_selection_text();
	var element = contenteditable_selection_container('a');
	if (element) {
		contenteditable_selection_node(element);
		text = element.innerHTML;
		var href = contenteditable_getAttribute(element, "href") || '';
		if (href.match(/^mailto:([^?]*)(.*)$/gi)) email = href.replace(/^mailto:([^?]*)(.*)$/gi, "$1") || '';
		if (href.match(/^(.*)\?subject=(.*)/gi)) subject = href.replace(/^(.*)\?subject=(.*)/gi, "$2") || '';
		htmlclass = contenteditable_getAttribute(element, "class") || "";
		htmlid = contenteditable_getAttribute(element, "id") || "";
	}

	if ((typeof(webeditor.mailto_window) == "undefined") || webeditor.mailto_window.closed) {
		webeditor.mailto_window = window.open(webeditor.rootpath+"mailto.html?editor=webeditor&email="+escape(email)+"&subject="+escape(subject)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass), "mailto_window", "width=500,height=325,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.mailto_window.focus();
}

function webeditor_backcolor() {
	if ((typeof(webeditor.colour_window) == "undefined") || webeditor.colour_window.closed) {
		webeditor.colour_window = window.open(webeditor.rootpath+"colour.html?editor=webeditor&attribute=backColor", "colour_window", "width=475,height=325,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.colour_window.focus();
}

function webeditor_forecolor() {
	if ((typeof(webeditor.colour_window) == "undefined") || webeditor.colour_window.closed) {
		webeditor.colour_window = window.open(webeditor.rootpath+"colour.html?editor=webeditor&attribute=foreColor", "colour_window", "width=475,height=325,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.colour_window.focus();
}

function webeditor_form() {
	var action = '';
	var enctype = '';
	var method = '';
	var target = '';
	var onsubmit = '';
	var onreset = '';
	var htmlid = '';
	var htmlclass = '';

	var form = contenteditable_selection_container('form');
	if (form) {
		contenteditable_selection_node(form);
		action = contenteditable_getAttribute(form, "action") || '';
		enctype = contenteditable_getAttribute(form, "enctype") || '';
		method = contenteditable_getAttribute(form, "method") || '';
		target = contenteditable_getAttribute(form, "target") || '';
		onsubmit = contenteditable_getAttribute(form, "onsubmit") || '';
		onreset = contenteditable_getAttribute(form, "onreset") || '';
		htmlclass = contenteditable_getAttribute(form, "class") || "";
		htmlid = contenteditable_getAttribute(form, "id") || "";
	}
	if ((typeof(webeditor.form_window) == "undefined") || webeditor.form_window.closed) {
		webeditor.form_window = window.open(webeditor.rootpath+"form.html?editor=webeditor&action="+escape(action)+"&enctype="+escape(enctype)+"&method="+escape(method)+"&target="+escape(target)+"&onsubmit="+escape(onsubmit)+"&onreset="+escape(onreset)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "form_window", "width=475,height=350,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.form_window.focus();
}

function webeditor_button(type, onclick) {
	if (! type) type = 'button';
	if (! onclick) onclick = '';
	var name = '';
	var value = '';
	var src = '';
	var align = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var button = contenteditable_selection_container('input');
	if (button) {
		contenteditable_selection_node(button);
		name = contenteditable_getAttribute(button, "name") || '';
		value = contenteditable_getAttribute(button, "value") || '';
		src = contenteditable_getAttribute(button, "src") || '';
		align = contenteditable_getAttribute(button, "align") || '';
		if (! onclick) onclick = contenteditable_getAttribute(button, "onclick") || '';
		onfocus = contenteditable_getAttribute(button, "onfocus") || '';
		onblur = contenteditable_getAttribute(button, "onblur") || '';
		htmlclass = contenteditable_getAttribute(button, "class") || "";
		htmlid = contenteditable_getAttribute(button, "id") || "";
	}
	if ((typeof(webeditor.button_window) == "undefined") || webeditor.button_window.closed) {
		webeditor.button_window = window.open(webeditor.rootpath+"button.html?editor=webeditor&type="+escape(type)+"&name="+escape(name)+"&value="+escape(value)+"&src="+escape(src)+"&align="+escape(align)+"&onclick="+escape(onclick)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "button_window", "width=475,height=400,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.button_window.focus();
}

function webeditor_submitbutton() {
	webeditor_button("submit");
}

function webeditor_resetbutton() {
	webeditor_button("reset");
}

function webeditor_imagebutton() {
	webeditor_button("image");
}

function webeditor_backbutton() {
	webeditor_button("button", "history.back(-1);");
}

function webeditor_text() {
	var name = '';
	var value = '';
	var size = '';
	var maxlength = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var input = contenteditable_selection_container('input');
	if (input) {
		contenteditable_selection_node(input);
		name = contenteditable_getAttribute(input, "name") || '';
		value = contenteditable_getAttribute(input, "value") || '';
		size = contenteditable_getAttribute(input, "size") || '';
		maxlength = contenteditable_getAttribute(input, "maxLength") || '';
		onclick = contenteditable_getAttribute(input, "onclick") || '';
		onchange = contenteditable_getAttribute(input, "onchange") || '';
		onfocus = contenteditable_getAttribute(input, "onfocus") || '';
		onblur = contenteditable_getAttribute(input, "onblur") || '';
		htmlclass = contenteditable_getAttribute(input, "class") || "";
		htmlid = contenteditable_getAttribute(input, "id") || "";
	}
	if ((typeof(webeditor.text_window) == "undefined") || webeditor.text_window.closed) {
		webeditor.text_window = window.open(webeditor.rootpath+"text.html?editor=webeditor&name="+escape(name)+"&value="+escape(value)+"&size="+escape(size)+"&maxlength="+escape(maxlength)+"&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "text_window", "width=475,height=450,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.text_window.focus();
}

function webeditor_password() {
	var name = '';
	var value = '';
	var size = '';
	var maxlength = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var input = contenteditable_selection_container('input');
	if (input) {
		contenteditable_selection_node(input);
		name = contenteditable_getAttribute(input, "name") || '';
		value = contenteditable_getAttribute(input, "value") || '';
		size = contenteditable_getAttribute(input, "size") || '';
		maxlength = contenteditable_getAttribute(input, "maxLength") || '';
		onclick = contenteditable_getAttribute(input, "onclick") || '';
		onchange = contenteditable_getAttribute(input, "onchange") || '';
		onfocus = contenteditable_getAttribute(input, "onfocus") || '';
		onblur = contenteditable_getAttribute(input, "onblur") || '';
		htmlclass = contenteditable_getAttribute(input, "class") || "";
		htmlid = contenteditable_getAttribute(input, "id") || "";
	}
	if ((typeof(webeditor.password_window) == "undefined") || webeditor.password_window.closed) {
		webeditor.password_window = window.open(webeditor.rootpath+"password.html?editor=webeditor&name="+escape(name)+"&value="+escape(value)+"&size="+escape(size)+"&maxlength="+escape(maxlength)+"&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "password_window", "width=475,height=450,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.password_window.focus();
}

function webeditor_hidden() {
	var type = 'hidden';
	var name = '';
	var value = '';
	var htmlid = '';
	var htmlclass = '';

	var input = contenteditable_selection_container('input');
	if (input) {
		contenteditable_selection_node(input);
		name = contenteditable_getAttribute(input, "name") || '';
		value = contenteditable_getAttribute(input, "value") || '';
		htmlclass = contenteditable_getAttribute(input, "class") || "";
		htmlid = contenteditable_getAttribute(input, "id") || "";
	}
	if ((typeof(webeditor.hidden_window) == "undefined") || webeditor.hidden_window.closed) {
		webeditor.hidden_window = window.open(webeditor.rootpath+"hidden.html?editor=webeditor&type="+escape(type)+"&name="+escape(name)+"&value="+escape(value)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "hidden_window", "width=475,height=250,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.hidden_window.focus();
}

function webeditor_textarea() {
	var name = '';
	var cols = '';
	var rows = '';
	var wrap = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var textarea = contenteditable_selection_container('textarea');
	if (textarea) {
		contenteditable_selection_node(textarea);
		name = contenteditable_getAttribute(textarea, "name") || '';
		cols = contenteditable_getAttribute(textarea, "cols") || '';
		rows = contenteditable_getAttribute(textarea, "rows") || '';
		wrap = contenteditable_getAttribute(textarea, "wrap") || '';
		onclick = contenteditable_getAttribute(textarea, "onclick") || '';
		onchange = contenteditable_getAttribute(textarea, "onchange") || '';
		onfocus = contenteditable_getAttribute(textarea, "onfocus") || '';
		onblur = contenteditable_getAttribute(textarea, "onblur") || '';
		htmlclass = contenteditable_getAttribute(textarea, "class") || "";
		htmlid = contenteditable_getAttribute(textarea, "id") || "";
	}
	if ((typeof(webeditor.textarea_window) == "undefined") || webeditor.textarea_window.closed) {
		webeditor.textarea_window = window.open(webeditor.rootpath+"textarea.html?editor=webeditor&name="+escape(name)+"&cols="+escape(cols)+"&rows="+escape(rows)+"&wrap="+escape(wrap)+"&&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "textarea_window", "width=475,height=425,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.textarea_window.focus();
}

function webeditor_checkbox() {
	var name = '';
	var value = '';
	var checked = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var input = contenteditable_selection_container('input');
	if (input) {
		contenteditable_selection_node(input);
		name = contenteditable_getAttribute(input, "name") || '';
		value = contenteditable_getAttribute(input, "value") || '';
		checked = contenteditable_getAttribute(input, "checked") ? 'checked' : '';
		onclick = contenteditable_getAttribute(input, "onclick") || '';
		onchange = contenteditable_getAttribute(input, "onchange") || '';
		onfocus = contenteditable_getAttribute(input, "onfocus") || '';
		onblur = contenteditable_getAttribute(input, "onblur") || '';
		htmlclass = contenteditable_getAttribute(input, "class") || "";
		htmlid = contenteditable_getAttribute(input, "id") || "";
	}
	if ((typeof(webeditor.checkbox_window) == "undefined") || webeditor.checkbox_window.closed) {
		webeditor.checkbox_window = window.open(webeditor.rootpath+"checkbox.html?editor=webeditor&name="+escape(name)+"&value="+escape(value)+"&checked="+escape(checked)+"&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "checkbox_window", "width=475,height=375,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.checkbox_window.focus();
}

function webeditor_radio() {
	var name = '';
	var value = '';
	var checked = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var input = contenteditable_selection_container('input');
	if (input) {
		contenteditable_selection_node(input);
		name = contenteditable_getAttribute(input, "name") || '';
		value = contenteditable_getAttribute(input, "value") || '';
		checked = contenteditable_getAttribute(input, "checked") ? 'checked' : '';
		onclick = contenteditable_getAttribute(input, "onclick") || '';
		onchange = contenteditable_getAttribute(input, "onchange") || '';
		onfocus = contenteditable_getAttribute(input, "onfocus") || '';
		onblur = contenteditable_getAttribute(input, "onblur") || '';
		htmlclass = contenteditable_getAttribute(input, "class") || "";
		htmlid = contenteditable_getAttribute(input, "id") || "";
	}
	if ((typeof(webeditor.radiobutton_window) == "undefined") || webeditor.radiobutton_window.closed) {
		webeditor.radiobutton_window = window.open(webeditor.rootpath+"radiobutton.html?editor=webeditor&name="+escape(name)+"&value="+escape(value)+"&checked="+escape(checked)+"&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "radiobutton_window", "width=475,height=375,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.radiobutton_window.focus();
}

function webeditor_file() {
	var type = 'file';
	var name = '';
	var value = '';
	var accept = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';

	var input = contenteditable_selection_container('input');
	if (input) {
		contenteditable_selection_node(input);
		name = contenteditable_getAttribute(input, "name") || '';
		value = contenteditable_getAttribute(input, "value") || '';
		accept = contenteditable_getAttribute(input, "accept") || '';
		onclick = contenteditable_getAttribute(input, "onclick") || '';
		onchange = contenteditable_getAttribute(input, "onchange") || '';
		onfocus = contenteditable_getAttribute(input, "onfocus") || '';
		onblur = contenteditable_getAttribute(input, "onblur") || '';
		htmlclass = contenteditable_getAttribute(input, "class") || "";
		htmlid = contenteditable_getAttribute(input, "id") || "";
	}
	if ((typeof(webeditor.file_window) == "undefined") || webeditor.file_window.closed) {
		webeditor.file_window = window.open(webeditor.rootpath+"file.html?editor=webeditor&type="+escape(type)+"&name="+escape(name)+"&value="+escape(value)+"&accept="+escape(accept)+"&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"", "file_window", "width=475,height=350,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.file_window.focus();
}

function webeditor_selectlist() {
	var name = '';
	var size = '';
	var multiple = '';
	var onclick = '';
	var onchange = '';
	var onfocus = '';
	var onblur = '';
	var htmlid = '';
	var htmlclass = '';
	webeditor.selectlist_options = false;
	webeditor.selectlist_node = false;

	var selectbox = contenteditable_selection_container('select');
	if (selectbox) {
		contenteditable_selection_node(selectbox);
		name = contenteditable_getAttribute(selectbox, "name") || '';
		size = contenteditable_getAttribute(selectbox, "size") || '';
		if (size < 1) size = '';
		multiple = contenteditable_getAttribute(selectbox, "multiple") ? 'multiple' : '';
		onclick = contenteditable_getAttribute(selectbox, "onclick") || '';
		onchange = contenteditable_getAttribute(selectbox, "onchange") || '';
		onfocus = contenteditable_getAttribute(selectbox, "onfocus") || '';
		onblur = contenteditable_getAttribute(selectbox, "onblur") || '';
		htmlclass = contenteditable_getAttribute(selectbox, "class") || "";
		htmlid = contenteditable_getAttribute(selectbox, "id") || "";
		webeditor.selectlist_node = selectbox;
		webeditor.selectlist_options = selectbox.options;
	}
	if ((typeof(webeditor.select_window) == "undefined") || webeditor.select_window.closed) {
		webeditor.select_window = window.open(webeditor.rootpath+"select.html?editor=webeditor&name="+escape(name)+"&size="+escape(size)+"&multiple="+escape(multiple)+"&onclick="+escape(onclick)+"&onchange="+escape(onchange)+"&onfocus="+escape(onfocus)+"&onblur="+escape(onblur)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass), "select_window", "scrollbars=yes,width=875,height=375,resizable=yes,status=yes", true);
	}
	webeditor.select_window.focus();
}

function webeditor_selectlist_options() {
	// MSIE contenteditable_selection_container() called from dialog window may not work correctly i.e. if select list is only editable content
	if (webeditor.selectlist_options) return webeditor.selectlist_options;
	var selectbox = contenteditable_selection_container('select');
	if (selectbox) {
		return selectbox.options;
	}
}

function webeditor_clean() {
	if ((typeof(webeditor.clean_window) == "undefined") || webeditor.clean_window.closed) {
		webeditor.clean_window = window.open(webeditor.rootpath+"clean.html?editor=webeditor", "clean_window", "width=475,height=275,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.clean_window.focus();
}

function webeditor_box() {
	var width = '100';
	var height = '100';
	var borderwidth = '1';
	var borderstyle = 'solid';
	var bordercolor = '';
	var backgroundcolor = '';
	var htmlid = '';
	var htmlclass = '';

	var box = contenteditable_selection_container('div');
	if (box && box.style && (box.style.position == "absolute")) {
		if (box && box.style) {
			contenteditable_selection_node(box);
			width = box.style.width || '';
			height = box.style.height || '';
			borderwidth = box.style.borderTopWidth || '';
			borderstyle = box.style.borderTopStyle || '';
			bordercolor = box.style.borderTopColor || '';
			backgroundcolor = box.style.backgroundColor || '';
			htmlclass = contenteditable_getAttribute(box, "class") || "";
			htmlid = contenteditable_getAttribute(box, "id") || "";
		}
	}
	if ((typeof(webeditor.box_window) == "undefined") || webeditor.box_window.closed) {
		webeditor.box_window = window.open(webeditor.rootpath+"box.html?editor=webeditor&width="+escape(width)+"&height="+escape(height)+"&borderwidth="+escape(borderwidth)+"&borderstyle="+escape(borderstyle)+"&bordercolor="+escape(bordercolor)+"&backgroundcolor="+escape(backgroundcolor)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass), "box_window", "width=475,height=350,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.box_window.focus();
}

function webeditor_iframe() {
	var width = '';
	var height = '';
	var src = '';
	var htmlid = '';
	var htmlclass = '';

	var iframe = contenteditable_selection_container('iframe');
	if (iframe) {
		contenteditable_selection_node(iframe);
		width = contenteditable_getAttribute(iframe, "width") || '';
		height = contenteditable_getAttribute(iframe, "height") || '';
		src = contenteditable_getAttribute(iframe, "src") || '';
		htmlclass = contenteditable_getAttribute(iframe, "class") || "";
		htmlid = contenteditable_getAttribute(iframe, "id") || "";
	}
	if ((typeof(webeditor.iframe_window) == "undefined") || webeditor.iframe_window.closed) {
		webeditor.iframe_window = window.open(webeditor.rootpath+"iframe.html?editor=webeditor&width="+escape(width)+"&height="+escape(height)+"&src="+escape(src)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass), "iframe_window", "width=525,height=350,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.iframe_window.focus();
}

function webeditor_anchor() {
	var name = '';
	var htmlid = '';
	var htmlclass = '';

	var anchor = contenteditable_selection_container('a');
	if (anchor) {
		contenteditable_selection_node(anchor);
		name = contenteditable_getAttribute(anchor, 'name') || '';
		htmlclass = contenteditable_getAttribute(anchor, "class") || "";
		htmlid = contenteditable_getAttribute(anchor, "id") || "";
	}
	if ((typeof(webeditor.anchor_window) == "undefined") || webeditor.anchor_window.closed) {
		webeditor.anchor_window = window.open(webeditor.rootpath+"anchor.html?editor=webeditor&name="+escape(name)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass), "anchor_window", "width=475,height=275,scrollbars=yes,resizable=yes,status=yes", true);
	}
	webeditor.anchor_window.focus();
}



///////////////////////////////////////////////////////////////////////////////////////////////////
// Callback functions called from web editor dialog windows
///////////////////////////////////////////////////////////////////////////////////////////////////

function cleanContent(all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var content = contenteditable_getContentSelection();
	if (content) {
		content = cleanContentSub(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div);
		contenteditable_event_paste_do_pre();
		contenteditable_pasteContent(content);
		contenteditable_event_paste_do_post();
		contenteditable_event_paste_fix();
	} else {
		content = contenteditable_getContent();
		content = cleanContentSub(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div);
		contenteditable_event_paste_do_pre();
		contenteditable_setContent(content);
		contenteditable_event_paste_do_post();
		contenteditable_event_paste_fix();
	}
	contenteditable_undo_save();
}

function cleanContentSub(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div, all_format_tags) {
	RegExp.global = true;
	RegExp.multiline = true;
	if (all_html) {
		// all html tags
		content = content.replace(/<\/?[^>]*>/gi, "");
	}
	if (all_xml) {
		// xml tags
		content = content.replace(/<\?xml[^>]*>/gi, "");
		content = content.replace(/<xml[^>]*>/gi, "");
		content = content.replace(/<\?[^>]*\?>/gi, "")
	}
	if (all_namespace) {
		// all namespace tags
		content = content.replace(/<\/?[a-z]+:[^>]*>/gi, "");
	}
	if (all_lang) {
		// all lang attributes
		content = content.replace(/(<[^>]+)[ \t\r\n]+lang=[^ |>]*([^>]*>)/gi, "$1 $2");
	}
	if (all_del_ins) {
		// all del and ins tags
		content = content.replace(/<del[^>]*>.*<\/del>/gi, "");
		content = content.replace(/<ins[^>]*>(.*)<\/ins>/gi, "$1");
	}
	if (all_class) {
		// all class attributes
		content = content.replace(/(<[^>]+)[ \t\r\n]+class=[^ |>]*([^>]*>)/gi, "$1 $2");
	}
	if (all_style) {
		// all style attributes
		content = content.replace(/(<[^>]+)[ \t\r\n]+style="[^"]*"([^>]*>)/gi, "$1$2");
	}
	if (empty_span) {
		// double and empty span tags and span tags without attributes
		content = content.replace(/<span *><span *>([^<]*)<\/span><\/span>/gi, "<span>$1</span>");
		content = content.replace(/<span[^>]*><\/span>/gi, "");
		content = content.replace(/<span *>([^<]*)<\/span>/gi, "$1");
	}
	if (all_span) {
		// all span tags
		content = content.replace(/<\/?span[^>]*>/gi, "");
	}
	if (empty_font) {
		// double and empty font tags and font tags without attributes
		content = content.replace(/<font><font>([^<]*)<\/font><\/font>/gi, "<font>$1</font>");
		content = content.replace(/<font[^>]*><\/font>/gi, "");
		content = content.replace(/<font>([^<]*)<\/font>/gi, "$1");
	}
	if (all_font) {
		// all font tags
		content = content.replace(/<\/?font[^>]*>/gi, "");
	}
	if (empty_p_div) {
		// empty p and div tags
		content = content.replace(/<p[^>]+>&nbsp;<\/p>/gi, "")
		content = content.replace(/<p[^>]+><\/p>/gi, "")
		content = content.replace(/<div[^>]+>&nbsp;<\/div>/gi, "")
		content = content.replace(/<div[^>]+><\/div>/gi, "")
	}
	if (all_format_tags) {
		// all text formatting tags
		content = content.replace(/<(\/?)address[^>]*>/gi, "<$1p>");
		content = content.replace(/<\/?b>/gi, "");
		content = content.replace(/<\/?big>/gi, "");
		content = content.replace(/<\/?blink[^>]*>/gi, "");
		content = content.replace(/<(\/?)blockquote>/gi, "<$1p>");
		content = content.replace(/<(\/?)center>/gi, "<$1p>");
		content = content.replace(/<\/?cite>/gi, "");
		content = content.replace(/<\/?code>/gi, "");
		content = content.replace(/<\/?em>/gi, "");
		content = content.replace(/<(\/?)h1>/gi, "<$1p>");
		content = content.replace(/<(\/?)h2>/gi, "<$1p>");
		content = content.replace(/<(\/?)h3>/gi, "<$1p>");
		content = content.replace(/<(\/?)h4>/gi, "<$1p>");
		content = content.replace(/<(\/?)h5>/gi, "<$1p>");
		content = content.replace(/<(\/?)h6>/gi, "<$1p>");
		content = content.replace(/<\/?i>/gi, "");
		content = content.replace(/<\/?kbd>/gi, "");
		content = content.replace(/<(\/?)pre>/gi, "<$1p>");
		content = content.replace(/<\/?q>/gi, "");
		content = content.replace(/<\/?s>/gi, "");
		content = content.replace(/<\/?samp>/gi, "");
		content = content.replace(/<\/?small>/gi, "");
		content = content.replace(/<\/?strike>/gi, "");
		content = content.replace(/<\/?strong>/gi, "");
		content = content.replace(/<\/?sub>/gi, "");
		content = content.replace(/<\/?sup>/gi, "");
		content = content.replace(/<\/?u>/gi, "");
		content = content.replace(/<\/?var>/gi, "");
	}
	// Crossed P and SPAN tags
	content = content.replace(/<p([ \t\r\n]+[^<]*)?><span([ \t\r\n]+[^<]*)?>([^<]*)<\/p><\/span>/gi, "<p $1><span $2>$3</span></p>");
	content = content.replace(/<span([ \t\r\n]+[^<]*)?><p([ \t\r\n]+[^<]*)?>([^<]*)<\/span><\/p>/gi, "<p $1><span $2>$3</span></p>");
	return content;
}

function insertMailto(email, subject, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var href = "mailto:" + email;
	if (subject) href += "?subject=" + subject;
	var anchor = contenteditable_selection_container('a');
	if (anchor) {
		text = anchor.innerHTML;
		if (contenteditable_getAttribute(anchor, "href") != href) {
			if (href == "") {
				contenteditable_removeAttribute(anchor, "href");
			} else {
				contenteditable_setAttribute(anchor, "href", href);
			}
		}
		if (contenteditable_getAttribute(anchor, "class") != htmlclass) {
			if (htmlclass == "") {
				contenteditable_removeAttribute(anchor, "class");
			} else {
				contenteditable_setAttribute(anchor, "class", htmlclass);
			}
		}
		if (contenteditable_getAttribute(anchor, "id") != htmlid) {
			if (htmlid == "") {
				contenteditable_removeAttribute(anchor, "id");
			} else {
				contenteditable_setAttribute(anchor, "id", htmlid);
			}
		}
		anchor.innerHTML = text;
	} else {
		var text = contenteditable_selection_text() || subject || email || '&nbsp;';
		var attributes = '';
		if (href) attributes += ' href="' + href + '"';
		if (htmlid) attributes += ' id="' + htmlid + '"';
		if (htmlclass) attributes += ' class="' + htmlclass + '"';
		anchor = contenteditable_pasteContent('<a' + attributes + '>' + text + '</a>');
		if (anchor) contenteditable_selection_node(anchor);
		if (anchor = contenteditable_selection_container("a")) {
			insertMailto(email, subject, htmlclass, htmlid);
		}
	}
	contenteditable_undo_save();
	contenteditable_focused_contentwindow().focus();
}

function insertAnchor(name, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var anchor = contenteditable_selection_container('a');
	if (anchor) {
		if (contenteditable_getAttribute(anchor, "name") != name) {
			if (name == "") {
				contenteditable_removeAttribute(anchor, "name");
			} else {
				contenteditable_setAttribute(anchor, "name", name);
			}
		}
		if (contenteditable_getAttribute(anchor, "class") != htmlclass) {
			if (htmlclass == "") {
				contenteditable_removeAttribute(anchor, "class");
			} else {
				contenteditable_setAttribute(anchor, "class", htmlclass);
			}
		}
		if (contenteditable_getAttribute(anchor, "id") != htmlid) {
			if (htmlid == "") {
				contenteditable_removeAttribute(anchor, "id");
			} else {
				contenteditable_setAttribute(anchor, "id", htmlid);
			}
		}
	} else {
		var text = contenteditable_selection_text() || '&nbsp;';
		var attributes = '';
		if (name) attributes += ' name="' + name + '"';
		if (htmlid) attributes += ' id="' + htmlid + '"';
		if (htmlclass) attributes += ' class="' + htmlclass + '"';
		anchor = contenteditable_pasteContent('<a' + attributes + '>' + text + '</a>');
		if (anchor) contenteditable_selection_node(anchor);
		if (anchor = contenteditable_selection_container("a")) {
			insertAnchor(name, htmlclass, htmlid);
		}
	}
	contenteditable_undo_save();
	contenteditable_focused_contentwindow().focus();
}

function insertBox(width, height, borderwidth, borderstyle, bordercolor, backgroundcolor, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var box = contenteditable_selection_container('div');
	if (box && box.style && (box.style.position == "absolute")) {
		width = width.replace(/^([0-9]+)$/gi, "$1px");
		height = height.replace(/^([0-9]+)$/gi, "$1px");
		borderwidth = borderwidth.replace(/^([0-9]+)$/gi, "$1px");
		try { if (width) box.style.width = width; } catch (e) { }
		try { if (height) box.style.height = height; } catch (e) { }
		try { if (borderwidth) box.style.borderWidth = borderwidth; } catch (e) { }
		try { if (borderstyle) box.style.borderStyle = borderstyle; } catch (e) { }
		try { if (bordercolor) box.style.borderColor = bordercolor; } catch (e) { }
		try { if (backgroundcolor) box.style.backgroundColor = backgroundcolor; } catch (e) { }
		if (contenteditable_getAttribute(box, "class") != htmlclass) {
			if (htmlclass == "") {
				contenteditable_removeAttribute(box, "class");
			} else {
				contenteditable_setAttribute(box, "class", htmlclass);
			}
		}
		if (contenteditable_getAttribute(box, "id") != htmlid) {
			if (htmlid == "") {
				contenteditable_removeAttribute(box, "id");
			} else {
				contenteditable_setAttribute(box, "id", htmlid);
			}
		}
	} else {
		var text = contenteditable_selection_text() || '&nbsp;';
		var attributes = '';
		attributes += ' style="position:absolute;';
		width = width.replace(/^([0-9]+)$/gi, "$1px");
		height = height.replace(/^([0-9]+)$/gi, "$1px");
		borderwidth = borderwidth.replace(/^([0-9]+)$/gi, "$1px");
		if (width) attributes += ' width: ' + width + ';';
		if (height) attributes += ' height: ' + height + ';';
		if (borderwidth) attributes += ' border-width: ' + borderwidth + ';';
		if (borderstyle) attributes += ' border-style: ' + borderstyle + ';';
		if (bordercolor) attributes += ' border-color: ' + bordercolor + ';';
		if (backgroundcolor) attributes += ' background-color: ' + backgroundcolor + ';';
		attributes += '"';
		if (htmlid) attributes += ' id="' + htmlid + '"';
		if (htmlclass) attributes += ' class="' + htmlclass + '"';
		box = contenteditable_pasteContent('<div' + attributes + '>' + text + '</div>');
		if (box) contenteditable_selection_node(box);
		if (box = contenteditable_selection_container("div")) {
			insertBox(width, height, borderwidth, borderstyle, bordercolor, backgroundcolor, htmlclass, htmlid);
		}
		contenteditable_position(true);
	}
	contenteditable_undo_save();
	contenteditable_focused_contentwindow().focus();
}

function insertIframe(width, height, src, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var iframe = contenteditable_selection_container('iframe');
	if (iframe) {
		if (contenteditable_getAttribute(iframe, "width") != width) {
			if (width == "") {
				contenteditable_removeAttribute(iframe, "width");
			} else {
				contenteditable_setAttribute(iframe, "width", width);
			}
		}
		if (contenteditable_getAttribute(iframe, "height") != height) {
			if (height == "") {
				contenteditable_removeAttribute(iframe, "height");
			} else {
				contenteditable_setAttribute(iframe, "height", height);
			}
		}
		if (contenteditable_getAttribute(iframe, "src") != src) {
			if (src == "") {
				contenteditable_removeAttribute(iframe, "src");
			} else {
				contenteditable_setAttribute(iframe, "src", src);
			}
		}
		if (contenteditable_getAttribute(iframe, "class") != htmlclass) {
			if (htmlclass == "") {
				contenteditable_removeAttribute(iframe, "class");
			} else {
				contenteditable_setAttribute(iframe, "class", htmlclass);
			}
		}
		if (contenteditable_getAttribute(iframe, "id") != htmlid) {
			if (htmlid == "") {
				contenteditable_removeAttribute(iframe, "id");
			} else {
				contenteditable_setAttribute(iframe, "id", htmlid);
			}
		}
	} else {
		var attributes = '';
		if (width) attributes += ' width="' + width + '"';
		if (height) attributes += ' height="' + height + '"';
		if (src) attributes += ' src="' + src + '"';
		if (htmlid) attributes += ' id="' + htmlid + '"';
		if (htmlclass) attributes += ' class="' + htmlclass + '"';
		iframe = contenteditable_pasteContent('<iframe' + attributes + '>&nbsp;</iframe>');
		if (iframe) contenteditable_selection_node(iframe);
		if (iframe = contenteditable_selection_container("iframe")) {
			insertIframe(width, height, src, htmlclass, htmlid);
		}
	}
	contenteditable_undo_save();
	contenteditable_focused_contentwindow().focus();
}

function insertForm(action, method, enctype, target, onsubmit, onreset, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var form = contenteditable_selection_container('form');
	if (form) {
		if (contenteditable_getAttribute(form, "action") != action) {
			if (action == "") {
				contenteditable_removeAttribute(form, "action");
			} else {
				contenteditable_setAttribute(form, "action", action);
			}
		}
		if (contenteditable_getAttribute(form, "method") != method) {
			if (method == "") {
				contenteditable_removeAttribute(form, "method");
			} else {
				contenteditable_setAttribute(form, "method", method);
			}
		}
		if (contenteditable_getAttribute(form, "enctype") != enctype) {
			if (enctype == "") {
				contenteditable_removeAttribute(form, "enctype");
			} else {
				contenteditable_setAttribute(form, "enctype", enctype);
			}
		}
		if (contenteditable_getAttribute(form, "target") != target) {
			if (target == "") {
				contenteditable_removeAttribute(form, "target");
			} else {
				contenteditable_setAttribute(form, "target", target);
			}
		}
		if (contenteditable_getAttribute(form, "onsubmit") != onsubmit) {
			if (onsubmit == "") {
				contenteditable_removeAttribute(form, "onsubmit");
			} else {
				contenteditable_setAttribute(form, "onsubmit", onsubmit);
			}
		}
		if (contenteditable_getAttribute(form, "onreset") != onreset) {
			if (onreset == "") {
				contenteditable_removeAttribute(form, "onreset");
			} else {
				contenteditable_setAttribute(form, "onreset", onreset);
			}
		}
		if (contenteditable_getAttribute(form, "class") != htmlclass) {
			if (htmlclass == "") {
				contenteditable_removeAttribute(form, "class");
			} else {
				contenteditable_setAttribute(form, "class", htmlclass);
			}
		}
		if (contenteditable_getAttribute(form, "id") != htmlid) {
			if (htmlid == "") {
				contenteditable_removeAttribute(form, "id");
			} else {
				contenteditable_setAttribute(form, "id", htmlid);
			}
		}
	} else {
		var attributes = '';
		if (action) attributes += ' action="' + action + '"';
		if (method) attributes += ' method="' + method + '"';
		if (enctype) attributes += ' enctype="' + enctype + '"';
		if (target) attributes += ' target="' + target + '"';
		if (onsubmit) attributes += ' onsubmit="' + onsubmit + '"';
		if (onreset) attributes += ' onreset="' + onreset + '"';
		if (htmlid) attributes += ' id="' + htmlid + '"';
		if (htmlclass) attributes += ' class="' + htmlclass + '"';
		form = contenteditable_pasteContent('<form' + attributes + '>&nbsp;</form>');
		if (form) contenteditable_selection_node(form);
		if (form = contenteditable_selection_container("form")) {
			insertForm(action, method, enctype, target, onsubmit, onreset, htmlclass, htmlid);
		}
	}
	contenteditable_undo_save();
	contenteditable_focused_contentwindow().focus();
}

function insertButton(type, name, value, src, align, onclick, onfocus, onblur, htmlclass, htmlid) {
	insertInput('input', type, name, value, '', '', '', '', '', '', '', '', '', src, align, onclick, '', onfocus, onblur, htmlclass, htmlid);
}

function insertText(type, name, value, size, maxlength, onclick, onchange, onfocus, onblur, htmlclass, htmlid) {
	insertInput('input', type, name, value, '', '', '', size, maxlength, '', '', '', '', '', '', onclick, onchange, onfocus, onblur, htmlclass, htmlid);
}

function insertHidden(type, name, value, htmlclass, htmlid) {
	insertInput('input', type, name, value, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', htmlclass, htmlid);
}

function insertTextarea(name, cols, rows, wrap, onclick, onchange, onfocus, onblur, htmlclass, htmlid) {
	insertInput('textarea', '', name, '', '', '', '', '', '', cols, rows, wrap, '', '', '', onclick, onchange, onfocus, onblur, htmlclass, htmlid);
}

function insertCheckbox(type, name, value, checked, onclick, onchange, onfocus, onblur, htmlclass, htmlid) {
	insertInput('input', type, name, value, checked, '', '', '', '', '', '', '', '', '', '', onclick, onchange, onfocus, onblur, htmlclass, htmlid);
}

function insertFile(type, name, value, accept, onclick, onchange, onfocus, onblur, htmlclass, htmlid) {
	insertInput('input', type, name, value, '', '', '', '', '', '', '', '', accept, '', '', onclick, onchange, onfocus, onblur, htmlclass, htmlid);
}

function insertSelect(name, size, multiple, options, onclick, onchange, onfocus, onblur, htmlclass, htmlid) {
	// MSIE contenteditable_selection_container() called after dialog window may not work correctly i.e. if select list is only editable content
	if (webeditor.selectlist_node) contenteditable_selection_node(webeditor.selectlist_node);
	insertInput('select', '', name, '', '', multiple, options, size, '', '', '', '', '', '', '', onclick, onchange, onfocus, onblur, htmlclass, htmlid);
}

function insertInput(tag, type, name, value, checked, multiple, options, size, maxlength, cols, rows, wrap, accept, src, align, onclick, onchange, onfocus, onblur, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var input = contenteditable_selection_container(tag);
	// MSIE contenteditable_selection_container() called after dialog window may not work correctly i.e. if select list is only editable content
	if ((! input) && (tag == "select") && (webeditor.selectlist_node)) input = webeditor.selectlist_node;
	if (input) {
		contenteditable_selection_node(input);
		if (contenteditable_getAttribute(input, "name") != name) {
			if (name == "") {
				contenteditable_removeAttribute(input, "name");
			} else {
				contenteditable_setAttribute(input, "name", name);
			}
		}
		if (contenteditable_getAttribute(input, "value") != value) {
			if (value == "") {
				contenteditable_removeAttribute(input, "value");
			} else {
				contenteditable_setAttribute(input, "value", value);
			}
		}
		if (! checked) {
			contenteditable_removeAttribute(input, "checked");
		} else {
			contenteditable_setAttribute(input, "checked", true);
		}
		if (! multiple) {
			contenteditable_removeAttribute(input, "multiple");
		} else {
			contenteditable_setAttribute(input, "multiple", true);
		}
		if (contenteditable_getAttribute(input, "size") != size) {
			if (size == "") {
				contenteditable_removeAttribute(input, "size");
			} else {
				contenteditable_setAttribute(input, "size", size);
			}
		}
		if (contenteditable_getAttribute(input, "maxLength") != maxlength) {
			if (maxlength == "") {
				contenteditable_removeAttribute(input, "maxLength");
			} else {
				contenteditable_setAttribute(input, "maxLength", maxlength);
			}
		}
		if (contenteditable_getAttribute(input, "cols") != cols) {
			if (cols == "") {
				contenteditable_removeAttribute(input, "cols");
			} else {
				contenteditable_setAttribute(input, "cols", cols);
			}
		}
		if (contenteditable_getAttribute(input, "rows") != rows) {
			if (rows == "") {
				contenteditable_removeAttribute(input, "rows");
			} else {
				contenteditable_setAttribute(input, "rows", rows);
			}
		}
		if (contenteditable_getAttribute(input, "wrap") != wrap) {
			if (wrap == "") {
				contenteditable_removeAttribute(input, "wrap");
			} else {
				contenteditable_setAttribute(input, "wrap", wrap);
			}
		}
		if (contenteditable_getAttribute(input, "accept") != accept) {
			if (accept == "") {
				contenteditable_removeAttribute(input, "accept");
			} else {
				contenteditable_setAttribute(input, "accept", accept);
			}
		}
		if (contenteditable_getAttribute(input, "src") != src) {
			if (src == "") {
				contenteditable_removeAttribute(input, "src");
			} else {
				contenteditable_setAttribute(input, "src", src);
			}
		}
		if (contenteditable_getAttribute(input, "align") != align) {
			if (align == "") {
				contenteditable_removeAttribute(input, "align");
			} else {
				contenteditable_setAttribute(input, "align", align);
			}
		}
		if (contenteditable_getAttribute(input, "onclick") != onclick) {
			if (onclick == "") {
				contenteditable_removeAttribute(input, "onclick");
			} else {
				contenteditable_setAttribute(input, "onclick", onclick);
			}
		}
		if (contenteditable_getAttribute(input, "onchange") != onchange) {
			if (onchange == "") {
				contenteditable_removeAttribute(input, "onchange");
			} else {
				contenteditable_setAttribute(input, "onchange", onchange);
			}
		}
		if (contenteditable_getAttribute(input, "onfocus") != onfocus) {
			if (onfocus == "") {
				contenteditable_removeAttribute(input, "onfocus");
			} else {
				contenteditable_setAttribute(input, "onfocus", onfocus);
			}
		}
		if (contenteditable_getAttribute(input, "onblur") != onblur) {
			if (onblur == "") {
				contenteditable_removeAttribute(input, "onblur");
			} else {
				contenteditable_setAttribute(input, "onblur", onblur);
			}
		}
		if (contenteditable_getAttribute(input, "class") != htmlclass) {
			if (htmlclass == "") {
				contenteditable_removeAttribute(input, "class");
			} else {
				contenteditable_setAttribute(input, "class", htmlclass);
			}
		}
		if (contenteditable_getAttribute(input, "id") != htmlid) {
			if (htmlid == "") {
				contenteditable_removeAttribute(input, "id");
			} else {
				contenteditable_setAttribute(input, "id", htmlid);
			}
		}
		// setting options must be done last due to MSIE workaround
		if (options) {
			input.selectedIndex = 0;
			for (var i=0; i<options.length; i++) {
				var option = new Option();
				option.text = options[i].text;
				option.value = options[i].value;
				option.selected = options[i].defaultselected;
				option.defaultSelected = options[i].defaultselected;
				input.options[i] = option;
			}
			input.length = options.length;
		}
		// setting type must be done last due to MSIE workaround
		if (contenteditable_getAttribute(input, "type") != type) {
			if (type == "") {
				contenteditable_removeAttribute(input, "type");
			} else {
				contenteditable_setAttribute(input, "type", type);
			}
		}
	} else {
		var attributes = '';
		var select_options = '';
		if (options) {
			for (var i=0; i<options.length; i++) {
				select_options += '<option value="';
				select_options += options[i].value;
				select_options += '"';
				select_options += options[i].defaultselected ? ' selected' : '';
				select_options += '>';
				select_options += options[i].text;
			}
		}
		if (type) attributes += ' type="' + type + '"';
		if (name) attributes += ' name="' + name + '"';
		if (value) attributes += ' value="' + value + '"';
		if (checked) attributes += ' checked';
		if (multiple) attributes += ' multiple';
		if (size) attributes += ' size="' + size + '"';
		if (maxlength) attributes += ' maxLength="' + maxlength + '"';
		if (cols) attributes += ' cols="' + cols + '"';
		if (rows) attributes += ' rows="' + rows + '"';
		if (wrap) attributes += ' wrap="' + wrap + '"';
		if (src) attributes += ' src="' + src + '"';
		if (onclick) attributes += ' onclick="' + onclick + '"';
		if (onchange) attributes += ' onchange="' + onchange + '"';
		if (onfocus) attributes += ' onfocus="' + onfocus + '"';
		if (onblur) attributes += ' onblur="' + onblur + '"';
		if (htmlid) attributes += ' id="' + htmlid + '"';
		if (htmlclass) attributes += ' class="' + htmlclass + '"';
		if (tag == "textarea") {
			input = contenteditable_pasteContent('<textarea' + attributes + '></textarea>');
			if (input) contenteditable_selection_node(input);
			if (input = contenteditable_selection_container(tag)) {
				insertInput(tag, type, name, value, checked, multiple, options, size, maxlength, cols, rows, wrap, accept, src, align, onclick, onchange, onfocus, onblur, htmlclass, htmlid);
			}
		} else if (tag == "select") {
			input = contenteditable_pasteContent('<select' + attributes + '>' + select_options + '</select>');
			if (input) contenteditable_selection_node(input);
			if (input = contenteditable_selection_container(tag)) {
				insertInput(tag, type, name, value, checked, multiple, options, size, maxlength, cols, rows, wrap, accept, src, align, onclick, onchange, onfocus, onblur, htmlclass, htmlid);
			}
		} else {
			input = contenteditable_pasteContent('<input' + attributes + '>');
			if (input) contenteditable_selection_node(input);
			if (input = contenteditable_selection_container(tag)) {
				insertInput(tag, type, name, value, checked, multiple, options, size, maxlength, cols, rows, wrap, accept, src, align, onclick, onchange, onfocus, onblur, htmlclass, htmlid);
			}
		}
	}
	contenteditable_undo_save();
	contenteditable_focused_contentwindow().focus();
}

function insertTable(rows, cols, border, width, height, cellpadding, cellspacing, background, bgcolor, bordercolor, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	if (! border) { border = '1'; }
	contenteditable_createtable(rows, cols, border, width, height, cellpadding, cellspacing, background, bgcolor, bordercolor, htmlclass, htmlid);
	contenteditable_undo_save();
}

function insertHyperlink(href, target, text, htmlclass, htmlid, onclick) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	contenteditable_createlink(href, target, text, htmlclass, htmlid, onclick);
	contenteditable_undo_save();
}

function insertImage(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	contenteditable_insertimage(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout);
	contenteditable_undo_save();
}

function insertFlash(src, alt, width, height, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	contenteditable_insertflash(src, alt, width, height, htmlclass, htmlid);
	contenteditable_undo_save();
}

function insertApplet(src, alt, width, height, htmlclass, htmlid) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	contenteditable_insertapplet(src, alt, width, height, htmlclass, htmlid);
	contenteditable_undo_save();
}

function insertSpecialCharacter(value) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	contenteditable_specialcharacter(value);
	contenteditable_undo_save();
}

function backColor(value) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	if (value) contenteditable_execcommand("backcolor",value);
	contenteditable_undo_save();
}

function foreColor(value) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	if (value) contenteditable_execcommand("forecolor",value);
	contenteditable_undo_save();
}

function updateTable(form) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var table;
	if (table = contenteditable_isTable()) {
		if (contenteditable_getAttribute(table, "width") != form.width.value) {
			if (form.width.value == "") {
				contenteditable_removeAttribute(table, "width");
			} else {
				contenteditable_setAttribute(table, "width", form.width.value);
			}
		}
		if (contenteditable_getAttribute(table, "height") != form.height.value) {
			if (form.height.value == "") {
				contenteditable_removeAttribute(table, "height");
			} else {
				contenteditable_setAttribute(table, "height", form.height.value);
			}
		}
		if (contenteditable_getAttribute(table, "cellPadding") != form.cellpadding.value) {
			if (form.cellpadding.value == "") {
				contenteditable_removeAttribute(table, "cellPadding");
			} else {
				contenteditable_setAttribute(table, "cellPadding", form.cellpadding.value);
			}
		}
		if (contenteditable_getAttribute(table, "cellSpacing") != form.cellspacing.value) {
			if (form.cellspacing.value == "") {
				contenteditable_removeAttribute(table, "cellSpacing");
			} else {
				contenteditable_setAttribute(table, "cellSpacing", form.cellspacing.value);
			}
		}
		if (contenteditable_getAttribute(table, "border") != form.border.value) {
			if (form.border.value == "") {
				contenteditable_removeAttribute(table, "border");
			} else {
				contenteditable_setAttribute(table, "border", form.border.value);
			}
		}
		if (contenteditable_getAttribute(table, "bgColor") != form.bgcolor.value) {
			if (form.bgcolor.value == "") {
				contenteditable_removeAttribute(table, "bgColor");
			} else {
				contenteditable_setAttribute(table, "bgColor", form.bgcolor.value);
			}
		}
		if (contenteditable_getAttribute(table, "borderColor") != form.bordercolor.value) {
			if (form.bordercolor.value == "") {
				contenteditable_removeAttribute(table, "borderColor");
			} else {
				contenteditable_setAttribute(table, "borderColor", form.bordercolor.value);
			}
		}
		if (contenteditable_getAttribute(table, "background") != form.background.value) {
			if (form.background.value == "") {
				contenteditable_removeAttribute(table, "background");
			} else {
				contenteditable_setAttribute(table, "background", form.background.value);
			}
		}
		if (contenteditable_getAttribute(table, "class") != form.htmlclass.value) {
			if (form.htmlclass.value == "") {
				contenteditable_removeAttribute(table, "class");
			} else {
				contenteditable_setAttribute(table, "class", form.htmlclass.value);
			}
		}
		if (contenteditable_getAttribute(table, "id") != form.htmlid.value) {
			if (form.htmlid.value == "") {
				contenteditable_removeAttribute(table, "id");
			} else {
				contenteditable_setAttribute(table, "id", form.htmlid.value);
			}
		}
	}
	contenteditable_undo_save();
}

function updateRow(form) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var row;
	if (row = contenteditable_isRow()) {
		if (contenteditable_getAttribute(row, "align") != form.align.value) {
			if (form.align.value == "") {
				contenteditable_removeAttribute(row, "align");
			} else {
				contenteditable_setAttribute(row, "align", form.align.value);
			}
		}
		if (contenteditable_getAttribute(row, "vAlign") != form.valign.value) {
			if (form.valign.value == "") {
				contenteditable_removeAttribute(row, "vAlign");
			} else {
				contenteditable_setAttribute(row, "vAlign", form.valign.value);
			}
		}
		if (contenteditable_getAttribute(row, "bgColor") != form.bgcolor.value) {
			if (form.bgcolor.value == "") {
				contenteditable_removeAttribute(row, "bgColor");
			} else {
				contenteditable_setAttribute(row, "bgColor", form.bgcolor.value);
			}
		}
		if (contenteditable_getAttribute(row, "borderColor") != form.bordercolor.value) {
			if (form.bordercolor.value == "") {
				contenteditable_removeAttribute(row, "borderColor");
			} else {
				contenteditable_setAttribute(row, "borderColor", form.bordercolor.value);
			}
		}
		if (contenteditable_getAttribute(row, "background") != form.background.value) {
			if (form.background.value == "") {
				contenteditable_removeAttribute(row, "background");
			} else {
				contenteditable_setAttribute(row, "background", form.background.value);
			}
		}
		if (contenteditable_getAttribute(row, "class") != form.htmlclass.value) {
			if (form.htmlclass.value == "") {
				contenteditable_removeAttribute(row, "class");
			} else {
				contenteditable_setAttribute(row, "class", form.htmlclass.value);
			}
		}
		if (contenteditable_getAttribute(row, "id") != form.htmlid.value) {
			if (form.htmlid.value == "") {
				contenteditable_removeAttribute(row, "id");
			} else {
				contenteditable_setAttribute(row, "id", form.htmlid.value);
			}
		}
	}
	contenteditable_undo_save();
}

function updateColumn(form) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		var cellcolumns = contenteditable_adjustedCellColumns(table);
		var column = cellcolumns[contenteditable_rowIndex(row)][cell.cellIndex];
		var skiprows = 0;
		for (var i=0; i<table.rows.length; i++) {
			if (! skiprows) {
				var rowcolumn = 0;
				for (var j=0; ((j<table.rows[i].cells.length) && (rowcolumn<=column)); j++) {
					rowcolumn = cellcolumns[i][j];
					if ((rowcolumn == column) || ((rowcolumn + table.rows[i].cells[j].colSpan) > column)) {
						var thiscell = table.rows[i].cells[j];
						if (contenteditable_getAttribute(thiscell, "width") != form.width.value) {
							if (form.width.value == "") {
								contenteditable_removeAttribute(thiscell, "width");
							} else {
								contenteditable_setAttribute(thiscell, "width", form.width.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "height") != form.height.value) {
							if (form.height.value == "") {
								contenteditable_removeAttribute(thiscell, "height");
							} else {
								contenteditable_setAttribute(thiscell, "height", form.height.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "align") != form.align.value) {
							if (form.align.value == "") {
								contenteditable_removeAttribute(thiscell, "align");
							} else {
								contenteditable_setAttribute(thiscell, "align", form.align.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "vAlign") != form.valign.value) {
							if (form.valign.value == "") {
								contenteditable_removeAttribute(thiscell, "vAlign");
							} else {
								contenteditable_setAttribute(thiscell, "vAlign", form.valign.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "bgColor") != form.bgcolor.value) {
							if (form.bgcolor.value == "") {
								contenteditable_removeAttribute(thiscell, "bgColor");
							} else {
								contenteditable_setAttribute(thiscell, "bgColor", form.bgcolor.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "borderColor") != form.bordercolor.value) {
							if (form.bordercolor.value == "") {
								contenteditable_removeAttribute(thiscell, "borderColor");
							} else {
								contenteditable_setAttribute(thiscell, "borderColor", form.bordercolor.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "background") != form.background.value) {
							if (form.background.value == "") {
								contenteditable_removeAttribute(thiscell, "background");
							} else {
								contenteditable_setAttribute(thiscell, "background", form.background.value);
							}
						}
						if (contenteditable_getAttribute(thiscell, "class") != form.htmlclass.value) {
							if (form.htmlclass.value == "") {
								contenteditable_removeAttribute(thiscell, "class");
							} else {
								contenteditable_setAttribute(thiscell, "class", form.htmlclass.value);
							}
						}
						skiprows = table.rows[i].cells[j].rowSpan - 1;
					} else if ((rowcolumn + table.rows[i].cells[j].colSpan) > column) {
						skiprows = table.rows[i].cells[j].rowSpan - 1;
					}
					rowcolumn += table.rows[i].cells[j].colSpan;
				}
			} else {
				skiprows--;
			}
		}
	}
	contenteditable_undo_save();
}

function updateCell(form) {
	contenteditable_focused_contentwindow().focus();
	contenteditable_undo_save();
	var cell;
	if (cell = contenteditable_isCell()) {
		if (contenteditable_getAttribute(cell, "width") != form.width.value) {
			if (form.width.value == "") {
				contenteditable_removeAttribute(cell, "width");
			} else {
				contenteditable_setAttribute(cell, "width", form.width.value);
			}
		}
		if (contenteditable_getAttribute(cell, "height") != form.height.value) {
			if (form.height.value == "") {
				contenteditable_removeAttribute(cell, "height");
			} else {
				contenteditable_setAttribute(cell, "height", form.height.value);
			}
		}
		if (contenteditable_getAttribute(cell, "colSpan") != form.colspan.value) {
			if (form.colspan.value == "") {
				contenteditable_removeAttribute(cell, "colSpan");
			} else {
				contenteditable_setAttribute(cell, "colSpan", form.colspan.value);
			}
		}
		if (contenteditable_getAttribute(cell, "rowSpan") != form.rowspan.value) {
			if (form.rowspan.value == "") {
				contenteditable_removeAttribute(cell, "rowSpan");
			} else {
				contenteditable_setAttribute(cell, "rowSpan", form.rowspan.value);
			}
		}
		if (contenteditable_getAttribute(cell, "align") != form.align.value) {
			if (form.align.value == "") {
				contenteditable_removeAttribute(cell, "align");
			} else {
				contenteditable_setAttribute(cell, "align", form.align.value);
			}
		}
		if (contenteditable_getAttribute(cell, "vAlign") != form.valign.value) {
			if (form.valign.value == "") {
				contenteditable_removeAttribute(cell, "vAlign");
			} else {
				contenteditable_setAttribute(cell, "vAlign", form.valign.value);
			}
		}
		if (contenteditable_getAttribute(cell, "bgColor") != form.bgcolor.value) {
			if (form.bgcolor.value == "") {
				contenteditable_removeAttribute(cell, "bgColor");
			} else {
				contenteditable_setAttribute(cell, "bgColor", form.bgcolor.value);
			}
		}
		if (contenteditable_getAttribute(cell, "borderColor") != form.bordercolor.value) {
			if (form.bordercolor.value == "") {
				contenteditable_removeAttribute(cell, "borderColor");
			} else {
				contenteditable_setAttribute(cell, "borderColor", form.bordercolor.value);
			}
		}
		if (contenteditable_getAttribute(cell, "background") != form.background.value) {
			if (form.background.value == "") {
				contenteditable_removeAttribute(cell, "background");
			} else {
				contenteditable_setAttribute(cell, "background", form.background.value);
			}
		}
		if (contenteditable_getAttribute(cell, "class") != form.htmlclass.value) {
			if (form.htmlclass.value == "") {
				contenteditable_removeAttribute(cell, "class");
			} else {
				contenteditable_setAttribute(cell, "class", form.htmlclass.value);
			}
		}
		if (contenteditable_getAttribute(cell, "id") != form.htmlid.value) {
			if (form.htmlid.value == "") {
				contenteditable_removeAttribute(cell, "id");
			} else {
				contenteditable_setAttribute(cell, "id", form.htmlid.value);
			}
		}
	}
	contenteditable_undo_save();
}

function importFile(content) {
	try {
		if (webeditor_custom_encode) content = webeditor_custom_encode(content);
	} catch(e) {
	}
	content = contenteditable_encodeScriptTags(content);
	contenteditable_setContent(content);
}



///////////////////////////////////////////////////////////////////////////////////////////////////
// Common contenteditable variables for the actual document manipulation
///////////////////////////////////////////////////////////////////////////////////////////////////

var contenteditable_contents = new Array();	// contenteditable blocks' initial content
var contenteditable_stylesheet = new Array();	// contenteditable blocks' initial stylesheet
var contenteditable_viewsource_status = new Array();	// WYSIWYG vs. SOURCE flag
var contenteditable_viewdetails_status = new Array();	// WYSIWYG vs. DETAILS flag
var contenteditable_onfocus = new Array();	// onfocus handler functions
var contenteditable_onblur = new Array();	// onblur handler functions
var contenteditable_focused = 0;	// which contenteditable block is focused (if any)
var contenteditable_event_paste_pre = "";	// content DOM before paste
var contenteditable_event_paste_post = "";	// content DOM after paste
var contenteditable_event_pasted_pre = "";	// content DOM before paste flag
var contenteditable_event_pasted_post = "";	// content DOM after paste flag



///////////////////////////////////////////////////////////////////////////////////////////////////
// Common contenteditable functions for the actual document manipulation
///////////////////////////////////////////////////////////////////////////////////////////////////

function contenteditable(id, content, stylesheet) {
	contenteditable_contents[id] = content;
	if (stylesheet) {
		var stylesheet_toggle = document.getElementById('stylesheet_toggle');
		if (stylesheet_toggle) stylesheet_toggle.checked = true;
		contenteditable_stylesheet[id] = stylesheet;
	} else {
		var stylesheet_toggle = document.getElementById('stylesheet_toggle');
		if (stylesheet_toggle) stylesheet_toggle.checked = true;
		contenteditable_stylesheet[id] = webeditor.rootpath + 'default.css';
	}
	document.write('<textarea id="'+id+'_textarea" name="'+id+'" cols="1" rows="1" style="display:none"></textarea>');
	document.write('<iframe src="' + webeditor.rootpath + 'empty.html" id="'+id+'" class="HardCore_contenteditable" width="' + webeditor.width + '" height="' + webeditor.height + '">');
	document.write('</iframe>');
}

function contenteditable_onSubmit() {
	for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
		var iframe = document.getElementsByTagName('iframe').item(i);
		if (iframe.className == 'HardCore_contenteditable') {
			if (contenteditable_viewsource_status[i]) {
				contenteditable_focused = i;
				contenteditable_viewsource(false);
			}
			var form = iframe;
			while ((form.tagName != "FORM") && (form.tagName != "HTML")) {
				form = form.parentNode;
			}
			var textarea;
			if (form.tagName != "HTML") {
				textarea = form[iframe.id];
			} else {
				textarea = document.getElementById(iframe.id+'_textarea');
			}
			if (textarea) {
				var value = contenteditable_formatContent(iframe.contentWindow.document.body);
				value = contenteditable_decodeScriptTags(value);
				try {
					if (webeditor_custom_decode) value = webeditor_custom_decode(value);
				} catch(e) {
				}
				textarea.value = value;
			}
		}
	}
}

function contenteditable_getContent(id) {
	var body = contenteditable_getContentBodyNode(id);
	if (body) {
		return body.innerHTML;
	}
}

function contenteditable_getContentBodyNode(id) {
	if (! id) {
		if (contenteditable_viewsource_status[contenteditable_focused]) {
			contenteditable_viewsource(false);
		}
		var iframe = document.getElementsByTagName('iframe').item(contenteditable_focused);
		if (iframe) {
			return iframe.contentWindow.document.body;
		}
	} else {
		for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
			var iframe = document.getElementsByTagName('iframe').item(i);
			if (iframe.className == 'HardCore_contenteditable') {
				if (id == iframe.id) {
					if (contenteditable_viewsource_status[i]) {
						contenteditable_focused = i;
						contenteditable_viewsource(false);
					}
					return iframe.contentWindow.document.body;
				}
			}
		}
	}
}

function contenteditable_getContentSelection(id) {
	if (! id) {
		if (contenteditable_viewsource_status[contenteditable_focused]) {
			contenteditable_viewsource(false);
		}
		var contentWindow = contenteditable_focused_contentwindow();
		var contentSelection = contenteditable_selection(contentWindow);
		return contenteditable_selection_text(contentSelection);
	} else {
		for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
			var iframe = document.getElementsByTagName('iframe').item(i);
			if (iframe.className == 'HardCore_contenteditable') {
				if (id == iframe.id) {
					if (contenteditable_viewsource_status[i]) {
						contenteditable_focused = i;
						contenteditable_viewsource(false);
					}
					var contentWindow = iframe.contentWindow;
					var contentSelection = contenteditable_selection(contentWindow);
					return contenteditable_selection_text(contentSelection);
				}
			}
		}
	}
}

function contenteditable_setContent(content, id) {
	if (! id) {
		if (contenteditable_viewsource_status[contenteditable_focused]) {
			contenteditable_viewsource(false);
		}
		var iframe = document.getElementsByTagName('iframe').item(contenteditable_focused);
		if (iframe) {
			if (true) { // QQQ
				contenteditable_selection_node(iframe.contentWindow.document.body);
				contenteditable_pasteContent(content, id);
			} else {
				iframe.contentWindow.document.body.innerHTML = content;
			}
		}
	} else {
		for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
			var iframe = document.getElementsByTagName('iframe').item(i);
			if (iframe.className == 'HardCore_contenteditable') {
				if (id == iframe.id) {
					if (contenteditable_viewsource_status[i]) {
						contenteditable_focused = i;
						contenteditable_viewsource(false);
					}
					if (true) { // QQQ
						contenteditable_selection_node(iframe.contentWindow.document.body);
						contenteditable_pasteContent(content, id);
					} else {
						iframe.contentWindow.document.body.innerHTML = content;
					}
				}
			}
		}
	}
}

function contenteditable_pasteContent(content, id) {
	var firstChild;
	var node = document.createElement("div");
	// MSIE may break/remove PARAM tags inside OBJECT tag when node.innerHTML is set
	// MSIE may crash or create empty node if node.outerHTML is set
	// QQQ no known workaround
	node.innerHTML = content;
	if (! id) {
		if (contenteditable_viewsource_status[contenteditable_focused]) {
			contenteditable_viewsource(false);
		}
		if (node.childNodes.length == 1) {
			node = contenteditable_insertNodeAtSelection(contenteditable_focused_contentwindow(), node.firstChild, content);
			firstChild = node;
		} else {
			node = contenteditable_insertNodeAtSelection(contenteditable_focused_contentwindow(), node, content);
			firstChild = node.firstChild;
			contenteditable_remove_parentnode(node);
		}
	} else {
		for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
			var iframe = document.getElementsByTagName('iframe').item(i);
			if (iframe.className == 'HardCore_contenteditable') {
				if (id == iframe.id) {
					if (contenteditable_viewsource_status[i]) {
						contenteditable_focused = i;
						contenteditable_viewsource(false);
					}
					if (node.childNodes.length == 1) {
						node = contenteditable_insertNodeAtSelection(contenteditable_focused_contentwindow(), node.firstChild, content);
						firstChild = node;
					} else {
						node = contenteditable_insertNodeAtSelection(contenteditable_focused_contentwindow(), node, content);
						firstChild = node.firstChild;
						contenteditable_remove_parentnode(node);
					}
				}
			}
		}
	}
	return firstChild;
}

function contenteditable_focused_contentwindow() {
	if (document.getElementsByTagName('iframe').item(contenteditable_focused)) {
		return document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow;
	} else {
//		return document.window;
	}
}

function contenteditable_focused_document() {
	return document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document;
}

function contenteditable_focused_iframe() {
	return document.getElementsByTagName('iframe').item(contenteditable_focused);
}

function contenteditable_focused_form() {
	var form = contenteditable_focused_iframe();
	while ((form.tagName != "FORM") && (form.tagName != "HTML")) {
		form = form.parentNode;
	}
	if (form.tagName != "HTML") {
		return form;
	}
}

function contenteditable_focused_textarea() {
	var iframe = contenteditable_focused_iframe();
	var form = contenteditable_focused_form();
	if (form) {
		return form[iframe.id];
	} else {
		return document.getElementById(iframe.id+'_textarea');
	}
}

function contenteditable_iframe(id) {
	var iframes = document.getElementsByTagName('iframe');
	for (var i=0; i<iframes.length; i++) {
		if (iframes.item(i).id == id) return iframes.item(i);
	}
}

function contenteditable_execcommand(command, value) {
	try {
		contenteditable_focused_document().execCommand(command, false, value);
	} catch(e) {
		return false;
	}
	return true;
}

function contenteditable_createtable(rows, cols, border, width, height, cellpadding, cellspacing, background, bgcolor, bordercolor, htmlclass, htmlid) {
	var contentWindow = contenteditable_focused_contentwindow();
	if ((rows > 0) && (cols > 0)) {
		var table = contentWindow.document.createElement("table");
		if (border) { contenteditable_setAttribute(table, "border", border); }
		if (width) { contenteditable_setAttribute(table, "width", width); }
		if (height) { contenteditable_setAttribute(table, "height", height); }
		if (cellpadding) { contenteditable_setAttribute(table, "cellPadding", cellpadding); }
		if (cellspacing) { contenteditable_setAttribute(table, "cellSpacing", cellspacing); }
		if (background) { contenteditable_setAttribute(table, "background", background); }
		if (bgcolor) { contenteditable_setAttribute(table, "bgColor", bgcolor); }
		if (bordercolor) { contenteditable_setAttribute(table, "borderColor", bordercolor); }
		if (htmlclass) { contenteditable_setAttribute(table, "class", htmlclass); }
		if (htmlid) { contenteditable_setAttribute(table, "id", htmlid); }
		var tbody = contentWindow.document.createElement("tbody");
		for (var i=0; i<rows; i++) {
			var tr = contentWindow.document.createElement("tr");
			for (var j=0; j<cols; j++) {
				var td = contentWindow.document.createElement("td");
				td.innerHTML = "&nbsp;";
				tr.appendChild(td);
			}
			tbody.appendChild(tr);
		}
		table.appendChild(tbody);			
		contenteditable_insertNodeAtSelection(contentWindow, table);
	}
}

function contenteditable_isTable() {
	var table;
	if ((table = contenteditable_isRow()) || (table = contenteditable_isTableCaption())) {
		while ((table.tagName != "TABLE") && (table.tagName != "HTML")) {
			table = table.parentNode;
		}
		if (table.tagName == "HTML") {
			return false;
		} else {
			return table;
		}
	}
}

function contenteditable_isTableCaption() {
	var caption = contenteditable_selection_container();
	while ((caption) && (caption.tagName != "CAPTION") && (caption.tagName != "TABLE") && (caption.tagName != "HTML")) {
		caption = caption.parentNode;
	}

	if ((! caption) || (caption.tagName != "CAPTION")) {
		return false;
	} else {
		return caption;
	}
}

function contenteditable_isTableHead() {
	var table;
	if (table = contenteditable_isRow()) {
		while ((table.tagName != "THEAD") && (table.tagName != "TABLE") && (table.tagName != "HTML")) {
			table = table.parentNode;
		}
		if (table.tagName == "THEAD") {
			return table;
		} else {
			return false;
		}
	}
}

function contenteditable_isTableBody() {
	var table;
	if (table = contenteditable_isRow()) {
		while ((table.tagName != "TBODY") && (table.tagName != "TABLE") && (table.tagName != "HTML")) {
			table = table.parentNode;
		}
		if (table.tagName == "TBODY") {
			return table;
		} else {
			return false;
		}
	}
}

function contenteditable_isTableFoot() {
	var table;
	if (table = contenteditable_isRow()) {
		while ((table.tagName != "TFOOT") && (table.tagName != "TABLE") && (table.tagName != "HTML")) {
			table = table.parentNode;
		}
		if (table.tagName == "TFOOT") {
			return table;
		} else {
			return false;
		}
	}
}

function contenteditable_isRow() {
	var row;
	if (row = contenteditable_isCell()) {
		while ((row.tagName != "TR") && (row.tagName != "HTML")) {
			row = row.parentNode;
		}
		if (row.tagName == "HTML") {
			return false;
		} else {
			return row;
		}
	}
}

function contenteditable_isCell() {
	var cell = contenteditable_selection_container();
	while ((cell) && (cell.tagName != "TD") && (cell.tagName != "HTML")) {
		cell = cell.parentNode;
	}

	if ((! cell) || (cell.tagName == "HTML")) {
		return false;
	} else {
		return cell;
	}
}

function contenteditable_rowIndex(row) {
	var rowIndex = 0;
	var nodeName = row.nodeName;
	var parentNode = row.parentNode;
	if (parentNode.nodeName != "TABLE") {
		while (parentNode = parentNode.previousSibling) {
			if ((parentNode.nodeName == "THEAD") || (parentNode.nodeName == "TBODY")) {
				rowIndex += parentNode.rows.length;
			}
		}
	}
	while (row = row.previousSibling) {
		if (nodeName == row.nodeName) rowIndex++;
	}
	return rowIndex;
}

function contenteditable_adjustedCellColumns(table) {
	// find cell columns adjusted for table rowspans
	var cellColumn = new Array();
	var rowspan_adjustment = new Array();
	for (var i=0; i<table.rows.length; i++) {
		if (! rowspan_adjustment[i]) rowspan_adjustment[i] = new Array();
		if (! cellColumn[i]) cellColumn[i] = new Array();
		var column = 0;
		for (var j=0; j<table.rows[i].cells.length; j++) {
			if (! rowspan_adjustment[i][column]) rowspan_adjustment[i][column] = 0;
			while (rowspan_adjustment[i][column]) column += rowspan_adjustment[i][column];
			if (table.rows[i].cells[j].rowSpan > 1) {
				var rowsspanned = parseInt(table.rows[i].cells[j].rowSpan);
				while (rowsspanned > 1) {
					rowsspanned--;
					if (! rowspan_adjustment[i+rowsspanned]) rowspan_adjustment[i+rowsspanned] = new Array();
					rowspan_adjustment[i+rowsspanned][column] = table.rows[i].cells[j].colSpan;
				}
			}
			cellColumn[i][j] = column;
			column += table.rows[i].cells[j].colSpan;
		}
	}
	return cellColumn;
}

function contenteditable_adjustedSelectionColumns(rows) {
	// find cell columns adjusted for table rowspans
	var cellColumn = new Array();
	var rowspan_adjustment = new Array();
	for (var i=0; i<rows.length; i++) {
		if (! rowspan_adjustment[i]) rowspan_adjustment[i] = new Array();
		if (! cellColumn[i]) cellColumn[i] = new Array();
		var column = 0;
		for (var j=0; j<rows[i].length; j++) {
			if (! rowspan_adjustment[i][column]) rowspan_adjustment[i][column] = 0;
			while (rowspan_adjustment[i][column]) column += rowspan_adjustment[i][column];
			if (rows[i][j].rowSpan > 1) {
				var rowsspanned = parseInt(rows[i][j].rowSpan);
				while (rowsspanned > 1) {
					rowsspanned--;
					if (! rowspan_adjustment[i+rowsspanned]) rowspan_adjustment[i+rowsspanned] = new Array();
					rowspan_adjustment[i+rowsspanned][column] = rows[i][j].colSpan;
				}
			}
			cellColumn[i][j] = column;
			column += rows[i][j].colSpan;
		}
	}
	return cellColumn;
}

function contenteditable_insertrow_rowspans(new_row, rowspan) {
	if (new_row) {
		for (row=new_row.previousSibling; row; row=row.previousSibling) {
			rowspan++;
			for (var cell=row.firstChild; cell; cell=cell.nextSibling) {
				if (cell.tagName == "TD") {
					if ((cell.rowSpan > rowspan) && (cell.rowSpan > 1)) {
						cell.rowSpan++;
					}
				}
			}
		}
	}
}

function contenteditable_insertcaption() {
	var table;
	if (table = contenteditable_isTable()) {
		var caption;
		for (var node=table.firstChild; node; node=node.nextSibling) {
			if (node.tagName == "CAPTION") caption = node;
		}
		if (! caption) {
			caption = contenteditable_focused_contentwindow().document.createElement("caption");
			caption.innerHTML = "caption";
			if (table.firstChild) {
				table.insertBefore(caption, table.firstChild);
			} else {
				table.appendChild(caption);
			}
		}
	}
}

function contenteditable_insertrowhead() {
	var table;
	var row;
	if ((table = contenteditable_isTable()) && (row = contenteditable_isRow())) {
		var caption;
		var thead;
		for (var node=table.firstChild; node; node=node.nextSibling) {
			if (node.tagName == "THEAD") thead = node;
			if (node.tagName == "CAPTION") caption = node;
		}

		if (thead) {
			//
		} else if (caption) {
			thead = contenteditable_focused_contentwindow().document.createElement("thead");
			if (caption.nextSibling) {
				table.insertBefore(thead, caption.nextSibling);
			} else {
				table.appendChild(thead);
			}
		} else {
			thead = contenteditable_focused_contentwindow().document.createElement("thead");
			if (table.firstChild) {
				table.insertBefore(thead, table.firstChild);
			} else {
				table.appendChild(thead);
			}
		}

		var new_row = row.cloneNode(true);
		for (var cell=new_row.firstChild; cell; cell=cell.nextSibling) {
			if (cell.tagName == "TD") {
				contenteditable_removeAttribute(cell, "rowSpan");
				cell.innerHTML = "&nbsp;";
			}
		}
		if (thead.firstChild) {
			thead.insertBefore(new_row, thead.firstChild);
		} else {
			thead.appendChild(new_row);
		}
	}
}

function contenteditable_insertrowfoot() {
	var table;
	var row;
	if ((table = contenteditable_isTable()) && (row = contenteditable_isRow())) {
		var tfoot;
		for (var node=table.firstChild; node; node=node.nextSibling) {
			if (node.tagName == "TFOOT") tfoot = node;
		}

		if (! tfoot) {
			tfoot = contenteditable_focused_contentwindow().document.createElement("tfoot");
			table.appendChild(tfoot);
		}

		var new_row = row.cloneNode(true);
		for (var cell=new_row.firstChild; cell; cell=cell.nextSibling) {
			if (cell.tagName == "TD") {
				contenteditable_removeAttribute(cell, "rowSpan");
				cell.innerHTML = "&nbsp;";
			}
		}
		tfoot.appendChild(new_row);
	}
}

function contenteditable_insertrowabove() {
	var row;
	if (row = contenteditable_isRow()) {
		var new_row = row.cloneNode(true);
		for (var cell=new_row.firstChild; cell; cell=cell.nextSibling) {
			if (cell.tagName == "TD") {
				contenteditable_removeAttribute(cell, "rowSpan");
				cell.innerHTML = "&nbsp;";
			}
		}
		row.parentNode.insertBefore(new_row, row);
	}
	contenteditable_insertrow_rowspans(new_row,0);
}

function contenteditable_insertrowbelow() {
	var row;
	if (row = contenteditable_isRow()) {
		var new_row = row.cloneNode(true);
		for (var cell=new_row.firstChild; cell; cell=cell.nextSibling) {
			if (cell.tagName == "TD") {
				if (cell.rowSpan > 1) {
					cell.parentNode.removeChild(cell);
				}
				cell.innerHTML = "&nbsp;";
			}
		}
		row.parentNode.insertBefore(new_row, row.nextSibling);
	}
	contenteditable_insertrow_rowspans(new_row,-1);
}

function contenteditable_deleterow() {
	var table = contenteditable_isTable();
	var row;
	if (tablecaption = contenteditable_isTableCaption()) {
		table = tablecaption.parentNode;
		table.removeChild(tablecaption);
	} else if ((row = contenteditable_isRow()) && (tablehead = contenteditable_isTableHead())) {
		if (tablehead.rows.length > 1) {
			row.parentNode.removeChild(row);
		} else {
			tablehead.parentNode.removeChild(tablehead);
		}
	} else if ((row = contenteditable_isRow()) && (tablebody = contenteditable_isTableBody())) {
		if (tablebody.rows.length > 1) {
			row.parentNode.removeChild(row);
		} else {
			tablebody.parentNode.removeChild(tablebody);
		}
	} else if ((row = contenteditable_isRow()) && (tablefoot = contenteditable_isTableFoot())) {
		if (tablefoot.rows.length > 1) {
			row.parentNode.removeChild(row);
		} else {
			tablefoot.parentNode.removeChild(tablefoot);
		}
	} else if ((row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		if (table.rows.length > 1) {
			row.parentNode.removeChild(row);
		} else {
			table.parentNode.removeChild(table);
		}
	}
	if (table && (table.rows.length == 0)) {
		table.parentNode.removeChild(table);
	}
}

function contenteditable_insertcolumnleft() {
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		var cellcolumns = contenteditable_adjustedCellColumns(table);
		var column = cellcolumns[contenteditable_rowIndex(row)][cell.cellIndex];
		var skiprows = 0;
		for (var i=0; i<table.rows.length; i++) {
			if (! skiprows) {
				var rowcolumn = 0;
				for (var j=0; ((j<table.rows[i].cells.length) && (rowcolumn<=column)); j++) {
					rowcolumn = cellcolumns[i][j];
					if (rowcolumn == column) {
						var new_cell = contenteditable_focused_contentwindow().document.createElement("td");
						new_cell.innerHTML = "&nbsp;";
						new_cell.rowSpan = table.rows[i].cells[j].rowSpan;
						skiprows = table.rows[i].cells[j].rowSpan - 1;
						table.rows[i].insertBefore(new_cell, table.rows[i].cells[j]);
					} else if ((rowcolumn + table.rows[i].cells[j].colSpan) > column) {
						table.rows[i].cells[j].colSpan += 1;
						skiprows = table.rows[i].cells[j].rowSpan - 1;
					}
					rowcolumn += table.rows[i].cells[j].colSpan;
				}
			} else {
				skiprows--;
			}
		}
	}
}

function contenteditable_insertcolumnright() {
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		var cellcolumns = contenteditable_adjustedCellColumns(table);
		var column = cellcolumns[contenteditable_rowIndex(row)][cell.cellIndex] + cell.colSpan;
		var skiprows = 0;
		for (var i=0; i<table.rows.length; i++) {
			if (! skiprows) {
				var rowcolumn = 0;
				for (var j=0; ((j<table.rows[i].cells.length) && (rowcolumn<column)); j++) {
					rowcolumn = cellcolumns[i][j];
					rowcolumn += table.rows[i].cells[j].colSpan;
					if (rowcolumn == column) {
						var new_cell = contenteditable_focused_contentwindow().document.createElement("td");
						new_cell.innerHTML = "&nbsp;";
						new_cell.rowSpan = table.rows[i].cells[j].rowSpan;
						skiprows = table.rows[i].cells[j].rowSpan - 1;
						table.rows[i].insertBefore(new_cell, table.rows[i].cells[j].nextSibling);
					} else if (rowcolumn > column) {
						table.rows[i].cells[j].colSpan += 1;
						skiprows = table.rows[i].cells[j].rowSpan - 1;
					}
				}
			} else {
				skiprows--;
			}
		}
	}
}

function contenteditable_deletecolumn() {
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		if (row.cells.length > 1) {
			var cellcolumns = contenteditable_adjustedCellColumns(table);
			var column = cellcolumns[contenteditable_rowIndex(row)][cell.cellIndex];
			var skiprows = 0;
			for (var i=0; i<table.rows.length; i++) {
				if (! skiprows) {
					var rowcolumn = 0;
					for (var j=0; ((j<table.rows[i].cells.length) && (rowcolumn<=column)); j++) {
						rowcolumn = cellcolumns[i][j];
						if ((rowcolumn == column) && (table.rows[i].cells[j].colSpan == 1)) {
							skiprows = table.rows[i].cells[j].rowSpan - 1;
							table.rows[i].removeChild(table.rows[i].cells[j]);
							rowcolumn++;
							rowcolumn = column + 1;
						} else if ((rowcolumn + table.rows[i].cells[j].colSpan) > column) {
							table.rows[i].cells[j].colSpan -= 1;
							skiprows = table.rows[i].cells[j].rowSpan - 1;
							rowcolumn += table.rows[i].cells[j].colSpan;
							rowcolumn = column + 1;
						}
					}
				} else {
					skiprows--;
				}
			}
		} else {
			table.parentNode.removeChild(table);
		}
	}
}

function contenteditable_insertcellleft() {
	var cell;
	if (cell = contenteditable_isCell()) {
		var new_cell = contenteditable_focused_contentwindow().document.createElement("td");
		new_cell.innerHTML = "&nbsp;";
		cell.parentNode.insertBefore(new_cell, cell);
	}
}

function contenteditable_insertcellright() {
	var cell;
	if (cell = contenteditable_isCell()) {
		var new_cell = contenteditable_focused_contentwindow().document.createElement("td");
		new_cell.innerHTML = "&nbsp;";
		cell.parentNode.insertBefore(new_cell, cell.nextSibling);
	}
}

function contenteditable_deletecell() {
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		if (row.cells.length > 1) {
			row.removeChild(cell);
		} else {
			contenteditable_deleterow();
		}
	}
}

function contenteditable_mergecells() {
	if (cells = contenteditable_selection_cells()) {
		var cellcolumns = contenteditable_adjustedSelectionColumns(cells);
		var html = '';
		var colspan = 0;
		var rowspan = 0;
		for (var row=0; row<cells.length; row++) {
			if (html != '') html += '<br>\r\n';
			var rowcolumn = 0;
			for (var column=0; column<cells[row].length; column++) {
				rowcolumn = cellcolumns[row][column];
				if (row == 0) colspan += cells[row][column].colSpan;
				if (colspan == rowcolumn) {
					if (column == 0) rowspan += cells[row][column].rowSpan;
				} else {
					if (rowcolumn == 0) rowspan += cells[row][column].rowSpan;
				}
				if (cells[row][column].innerHTML != '&nbsp;') {
					html += cells[row][column].innerHTML;
				}
				if (row || column) {
					cells[row][column].parentNode.removeChild(cells[row][column]);
				}
			}
		}
		if (html == '') html = '&nbsp;';
		cells[0][0].innerHTML = html;
		cells[0][0].rowSpan = rowspan;
		cells[0][0].colSpan = colspan;
	}
}

function contenteditable_splitcell() {
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow()) && (table = contenteditable_isTable())) {
		var column = cell.cellIndex;
		var colSpan = cell.colSpan
		var rowSpan = cell.rowSpan
		contenteditable_removeAttribute(cell, "colSpan");
		contenteditable_removeAttribute(cell, "rowSpan");
		for (var i=0; i<rowSpan; i++) {
			for (var j=0; j<colSpan; j++) {
				var new_cell = cell.cloneNode(true);
				contenteditable_removeAttribute(new_cell, "colSpan");
				contenteditable_removeAttribute(new_cell, "rowSpan");
				new_cell.innerHTML = "&nbsp;";
				if (i) {
					row.insertBefore(new_cell, row.cells[column-1].nextSibling);
				} else if (j) {
					row.insertBefore(new_cell, row.cells[column].nextSibling);
				}
			}
			row = row.nextSibling;
		}
	}
}

function contenteditable_splitcellcolumns() {
	var table;
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow())) {
		var column = cell.cellIndex;
		var colSpan = cell.colSpan
		contenteditable_removeAttribute(cell, "colSpan");
		for (var i=1; i<colSpan; i++) {
			var new_cell = cell.cloneNode(true);
			contenteditable_removeAttribute(new_cell, "colSpan");
			new_cell.innerHTML = "&nbsp;";
			row.insertBefore(new_cell, cell.nextSibling);
		}
	}
}

function contenteditable_splitcellrows() {
	var row;
	var cell;
	if ((cell = contenteditable_isCell()) && (row = contenteditable_isRow())) {
		var column = cell.cellIndex;
		var rowSpan = cell.rowSpan
		contenteditable_removeAttribute(cell, "rowSpan");
		for (var i=1; i<rowSpan; i++) {
			row = row.nextSibling;
			var new_cell = cell.cloneNode(true);
			contenteditable_removeAttribute(new_cell, "rowSpan");
			new_cell.innerHTML = "&nbsp;";
			row.insertBefore(new_cell, row.cells[column-1].nextSibling);
		}
	}
}

function contenteditable_createlink(href, target, text, htmlclass, htmlid, onclick) {
	var contentWindow = contenteditable_focused_contentwindow();
	if (href) {
		if (! text) text = href;
		text = text.replace(/<a\s+[^>]+>/gi, "");
		text = text.replace(/<\/a>/gi, "");
		var element = contenteditable_selection_container('a');
		if (element) {
			contenteditable_selection_node(element);
			contenteditable_setAttribute(element, "href", href);
			if (target) {
				contenteditable_setAttribute(element, "target", target);
			} else {
				contenteditable_removeAttribute(element, "target");
			}
			if (htmlclass) {
				contenteditable_setAttribute(element, "class", htmlclass);
			} else {
				contenteditable_removeAttribute(element, "class");
			}
			if (htmlid) {
				contenteditable_setAttribute(element, "id", htmlid);
			} else {
				contenteditable_removeAttribute(element, "id");
			}
			if (onclick) {
				contenteditable_setAttribute(element, "onclick", onclick);
			} else {
				contenteditable_removeAttribute(element, "onclick");
			}
		} else {
			var a = contentWindow.document.createElement("a");
			contenteditable_setAttribute(a, "href", href);
			if (target) { contenteditable_setAttribute(a, "target", target); }
			if (htmlclass) { contenteditable_setAttribute(a, "class", htmlclass); }
			if (htmlid) { contenteditable_setAttribute(a, "id", htmlid); }
			if (onclick) { contenteditable_setAttribute(a, "onclick", onclick); }
			a.innerHTML = text;
			contenteditable_insertNodeAtSelection(contentWindow, a);
			// MSIE insertNodeAtSelection/pasteHTML may not work properly - changes src from relative to absolute + sets unspecified default values
			contenteditable_insertlink_fix(href, target, text, htmlclass, htmlid, onclick);
		}
	}
}

function contenteditable_insertimage(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout) {
	var contentWindow = contenteditable_focused_contentwindow();
	if (src) {
		var element = contenteditable_selection_container('object');
		if ((contenteditable_getAttribute(element, 'classid') == "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000") || (contenteditable_getAttribute(element, 'classid') == "clsid:CAFEEFAC-0014-0002-0000-ABCDEFFEDCBA")) {
			element.parentNode.removeChild(element);
		}
		var img = contentWindow.document.createElement("img");
		contenteditable_setAttribute(img, "src", src);
		if (border) { contenteditable_setAttribute(img, "border", border); }
		if (alt) { contenteditable_setAttribute(img, "alt", alt); }
		if (width) { contenteditable_setAttribute(img, "width", width); }
		if (height) { contenteditable_setAttribute(img, "height", height); }
		if (vspace) { contenteditable_setAttribute(img, "vspace", vspace); }
		if (hspace) { contenteditable_setAttribute(img, "hspace", hspace); }
		if (align) { contenteditable_setAttribute(img, "align", align); }
		if (onmouseover) { contenteditable_setAttribute(img, "onMouseOver", onmouseover); }
		if (onmouseout) { contenteditable_setAttribute(img, "onMouseOut", onmouseout); }
		if (htmlclass) { contenteditable_setAttribute(img, "class", htmlclass); }
		if (htmlid) { contenteditable_setAttribute(img, "id", htmlid); }
		contenteditable_insertNodeAtSelection(contentWindow, img);
		// MSIE insertNodeAtSelection/pasteHTML may not work properly - changes src from relative to absolute + sets unspecified default values
		contenteditable_insertimage_fix(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout);
	}
}

function contenteditable_insertflash(href, alt, width, height, htmlclass, htmlid) {
	var contentWindow = contenteditable_focused_contentwindow();
	if (href) {
		var element = contenteditable_selection_container('object');
		if ((contenteditable_getAttribute(element, 'classid') == "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000") || (contenteditable_getAttribute(element, 'classid') == "clsid:CAFEEFAC-0014-0002-0000-ABCDEFFEDCBA")) {
			element.parentNode.removeChild(element);
		}
		var html = '<object';
		html += ' codeBase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab"';
		html += ' classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"';
		if (width) { html += ' width="'+width+'"'; }
		if (height) { html += ' height="'+height+'"'; }
		if (htmlclass) { html += ' class="'+htmlclass+'"'; }
		if (htmlid) { html += ' id="'+htmlid+'"'; }
		html += '>';
		html += '<param name="movie" value="'+href+'">';
		html += '<param name="quality" value="high">';
		html += '<embed';
		html += ' src="'+href+'"';
		html += ' pluginspage="http://www.macromedia.com/go/getflashplayer"';
		html += ' type="application/x-shockwave-flash"';
		html += ' quality="high"';
		if (width) { html += ' width="'+width+'"'; }
		if (height) { html += ' height="'+height+'"'; }
		html += '>';
		html += '<noembed>';
		html += alt;
		html += '</noembed>';
		html += '</embed>';
		html += '</object>';
		contenteditable_pasteContent(html);
	}
}

function contenteditable_insertapplet(href, alt, width, height, htmlclass, htmlid) {
	var contentWindow = contenteditable_focused_contentwindow();
	if (href) {
		var element = contenteditable_selection_container('object');
		if ((contenteditable_getAttribute(element, 'classid') == "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000") || (contenteditable_getAttribute(element, 'classid') == "clsid:CAFEEFAC-0014-0002-0000-ABCDEFFEDCBA")) {
			element.parentNode.removeChild(element);
		}
		var code;
		var codebase;
		if (href.lastIndexOf("/")>=0) {
			code = href.substring(href.lastIndexOf("/")+1);
			codebase = href.substring(0, href.lastIndexOf("/")+1);
		} else {
			code = href;
			codebase = "";
		}
		var html = '';
		html += '<object';
		html += ' codeBase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_2-windows-i586.cab"';
		html += ' classid="clsid:CAFEEFAC-0014-0002-0000-ABCDEFFEDCBA"';
		if (width) { html += ' width="'+width+'"'; }
		if (height) { html += ' height="'+height+'"'; }
		if (htmlclass) { html += ' class="'+htmlclass+'"'; }
		if (htmlid) { html += ' id="'+htmlid+'"'; }
		html += '>';
		html += '<param name="codebase" value="'+codebase+'">';
		html += '<param name="code" value="'+code+'">';
		html += '<param name="type" value="application/x-java-applet">';
		html += '<comment>';
		html += '<embed';
		html += ' pluginspage="http://java.sun.com/products/plugin/index.html#download"';
		html += ' codebase="'+codebase+'"';
		html += ' code="'+code+'"';
		html += ' type="application/x-java-applet"';
		if (width) { html += ' width="'+width+'"'; }
		if (height) { html += ' height="'+height+'"'; }
		html += '>';
		html += '<noembed>';
		html += '<applet';
		html += ' codebase="'+codebase+'"';
		html += ' code="'+code+'"';
		if (width) { html += ' width="'+width+'"'; }
		if (height) { html += ' height="'+height+'"'; }
		html += '>';
		html += alt;
		html += '</applet>';
		html += '</noembed>';
		html += '</embed>';
		html += '</comment>';
		html += '</object>';
		contenteditable_pasteContent(html);
	}
}

function contenteditable_stylesheet_link(stylesheet) {
	if (! stylesheet) {
		stylesheet = webeditor.rootpath + 'default.css';
		var stylesheet_toggle = document.getElementById('stylesheet_toggle');
		if (stylesheet_toggle) stylesheet_toggle.checked = true;
	} else {
		var stylesheet_toggle = document.getElementById('stylesheet_toggle');
		if (stylesheet_toggle) stylesheet_toggle.checked = true;
	}
	for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
		var iframe = document.getElementsByTagName('iframe').item(i);
		if (iframe.className == 'HardCore_contenteditable') {
			try {
				// MSIE may crash if the style sheet imports other style sheets using @import
				//iframe.contentWindow.document.getElementById('stylesheet').href = stylesheet;
				var oldNode = iframe.contentWindow.document.getElementById('stylesheet');
				var newNode = iframe.contentWindow.document.createElement("link");
				newNode.id = "stylesheet";
				newNode.rel = "stylesheet";
				newNode.type = "text/css";
				newNode.href = stylesheet;
				oldNode.parentNode.replaceChild(newNode, oldNode);
				oldNode = false;
				contenteditable_stylesheet[iframe.id] = stylesheet;
				webeditor.stylesheetclassnames = new Array();
				webeditor.stylesheetclassvalues = new Array();
				webeditor_refreshToolbar(true);
			}  catch (e) {
			}
		}
	}
}

function contenteditable_stylesheet_toggle(use_stylesheet) {
	editor_stylesheet = webeditor.rootpath + 'editor.css';
	for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
		var iframe = document.getElementsByTagName('iframe').item(i);
		if (iframe.className == 'HardCore_contenteditable') {
			try {
				if (use_stylesheet) {
					iframe.contentWindow.document.getElementById('stylesheet').href = contenteditable_stylesheet[iframe.id];
					var stylesheet_toggle = document.getElementById('stylesheet_toggle');
					if (stylesheet_toggle) stylesheet_toggle.checked = true;
				} else {
					iframe.contentWindow.document.getElementById('stylesheet').href = editor_stylesheet;
					var stylesheet_toggle = document.getElementById('stylesheet_toggle');
					if (stylesheet_toggle) stylesheet_toggle.checked = false;
				}
			}  catch (e) {
			}
		}
	}
}

function contenteditable_formatclass_query() {
	var selection_class = "";
	for (var node = contenteditable_selection_container(); ((node != null) && (node.nodeName != "HTML")); node = node.parentNode) {
		if (node.className != "") {
			selection_class = node.className;
			break;
		}
	}
	return selection_class;
}

function contenteditable_formatclass_attribute(node, value) {
	if (value) {
		node.className = value;
	} else {
		contenteditable_removeAttribute(node, "class");
		if ((node.nodeName == "SPAN") && (node.childNodes) && (node.childNodes.length == 1)) {
			var empty = true;
			for (i in node.attributes) {
				if (node.attributes[i] && (node.attributes[i].specified == true)) {
					empty = false;
					break;
				}
			}
			if (empty) node.parentNode.replaceChild(node.childNodes[0], node);
		} else if ((node.nodeName == "SPAN") && (! node.childNodes) || (node.childNodes.length == 0)) {
			var empty = true;
			for (i in node.attributes) {
				if (node.attributes[i] && (node.attributes[i].specified == true)) {
					empty = false;
					break;
				}
			}
			if (empty && node.parentNode) node.parentNode.removeChild(node);
		}
	}
}

function contenteditable_viewdetails(details) {
	if (details == null) details = ! contenteditable_viewdetails_status[contenteditable_focused];
	if (details) {
		contenteditable_viewdetails_status[contenteditable_focused] = true;
		contenteditable_stylesheet_toggle(false);
	} else {
		contenteditable_viewdetails_status[contenteditable_focused] = false;
		contenteditable_stylesheet_toggle(true);
	}
}

function contenteditable_viewsource(source) {
	if (true) {
		// new viewsource functionality using textarea
		var iframe = contenteditable_focused_iframe();
		var form = contenteditable_focused_form();
		if (form.tagName != "HTML") {
			var textarea = contenteditable_focused_textarea();
			if (! contenteditable_viewsource_status[contenteditable_focused]) {
				contenteditable_viewsource_status[contenteditable_focused] = true;
				var viewdetails_status = contenteditable_viewdetails_status[contenteditable_focused] || false;
				contenteditable_viewdetails(false);
				contenteditable_viewdetails_status[contenteditable_focused] = viewdetails_status;
				var content = iframe.contentWindow.document.body.innerHTML;
				content = contenteditable_decodeScriptTags(content);
				try {
					if (webeditor_custom_decode) content = webeditor_custom_decode(content);
				} catch(e) {
				}
				textarea.value = content;
				var iframe_offsetWidth = iframe.offsetWidth;
				var iframe_offsetHeight = iframe.offsetHeight;
				iframe.style.display = "none";
				textarea.style.display = "block";
				while (textarea.offsetWidth < iframe_offsetWidth) { textarea.cols += 1; }
				while (textarea.offsetHeight < iframe_offsetHeight) { textarea.rows += 1; }
				contenteditable_viewsource_onfocus(textarea);
			} else {
				contenteditable_viewsource_status[contenteditable_focused] = false;
				contenteditable_viewdetails(contenteditable_viewdetails_status[contenteditable_focused]);
				textarea.style.display = "none";
				iframe.style.display = "block";
				contenteditable_viewsource_textarea2html(textarea, iframe);
				contenteditable_enable();
				contenteditable_position(true);
			}
		}
		return;
	} else {
		// old viewsource functionality using text encoding
		if (! contenteditable_viewsource_status[contenteditable_focused]) {
			contenteditable_viewsource_status[contenteditable_focused] = true;
			contenteditable_stylesheet_toggle(false);
			contenteditable_viewsource_show();
		} else {
			contenteditable_viewsource_status[contenteditable_focused] = false;
			contenteditable_stylesheet_toggle(true);
			contenteditable_viewsource_hide();
		}
	}
}

function contenteditable_node_attributes(node) {
	var attributes = "";
	if (node && node.attributes) {
		for (var attribute=0; attribute<node.attributes.length; attribute++) {
			if ((node.attributes[attribute].specified) && (node.attributes[attribute].name) && (node.attributes[attribute].name.substr(0,5) != "_moz_")) {
				attributes += node.attributes[attribute].name + '="' + contenteditable_getAttribute(node, node.attributes[attribute].name) + '" ';
			} else if ((node.attributes[attribute].specified) && (node.attributes[attribute].nodeName) && (node.attributes[attribute].nodeName.substr(0,5) != "_moz_")) {
				attributes += node.attributes[attribute].nodeName + '="' + contenteditable_getAttribute(node, node.attributes[attribute].nodeName) + '" ';
			}
		}
	}
	return attributes;
}

function contenteditable_nextnode(rootnode, node, skipChildren) {
	var current_node = node;
	if (node && node.firstChild && (! skipChildren)) {
		return node.firstChild;
	} else {
		while (node && (node != rootnode) && (! node.nextSibling)) node = node.parentNode;
		if (node && (node != rootnode)) {
			// MSIE DOM may be broken with loops caused by overlapping tags
			if (node.nextSibling == current_node) return false;
			return node.nextSibling;
		} else {
			return false;
		}
	}
}

function contenteditable_previousnode(rootnode, node) {
	if (rootnode == node) {
		while (node && node.lastChild) node = node.lastChild;
	} else if (node) {
		if (node.previousSibling) {
			node = node.previousSibling;
			while (node && node.lastChild) node = node.lastChild;
		} else {
			node = node.parentNode;
		}
	}
	if (node && (node != rootnode)) {
		return node;
	} else {
		return false;
	}
}

function contenteditable_event_paste_down(my_event) {
	if (my_event && (my_event.type == "keydown") && my_event.ctrlKey && (my_event.keyCode == 86)) {
		return true;
	} else {
		return false;
	}
}

function contenteditable_event_paste_up(my_event) {
	if (my_event && (my_event.type == "keyup") && my_event.ctrlKey && (my_event.keyCode == 86)) {
		return true;
	} else {
		return false;
	}
}

function contenteditable_event_paste_keypress(my_event) {
	if (my_event && (my_event.type == "keypress") && my_event.ctrlKey && (my_event.keyCode == 86)) {
		return true;
	} else {
		return false;
	}
}

function contenteditable_event_paste(evt) {
	// MSIE and Mozilla pastes absolute URLs instead of relative URLs as originally copied/cut
	if (! contenteditable_event_pasted_post) {
		if (contenteditable_event_paste_down(evt) && (! contenteditable_event_pasted_pre)) {
			contenteditable_undo_save();
			contenteditable_event_paste_do_pre();
		} else if (contenteditable_event_paste_up(evt) || contenteditable_event_pasted_pre) {
			contenteditable_event_paste_do_post();
			setTimeout(contenteditable_event_paste_do_fix, 100);
		}
	}
}

function contenteditable_event_paste_do_pre() {
	contenteditable_event_pasted_pre = true;
	contenteditable_event_paste_pre = contenteditable_focused_document().body.cloneNode(true);
}

function contenteditable_event_paste_do_post() {
	contenteditable_event_pasted_post = true;
}

function contenteditable_event_paste_do_fix() {
	contenteditable_event_paste_fix();
	contenteditable_undo_save();
}

function contenteditable_event_paste_fix() {
	contenteditable_event_paste_fix_sub("A", "href");
	contenteditable_event_paste_fix_sub("IMG", "src");
	contenteditable_event_paste_fix_sub("INPUT", "src");
	contenteditable_event_paste_fix_sub("TABLE", "background");
	contenteditable_event_paste_fix_sub("TR", "background");
	contenteditable_event_paste_fix_sub("TD", "background");
	contenteditable_event_paste_fix_sub("IFRAME", "src");
}

function contenteditable_event_paste_fix_sub(tagName, attributeName) {
	var url = '';
	if (document.location.protocol) url += document.location.protocol + '//';
	if (document.location.hostname) url += document.location.hostname;
	if (document.location.port) url += ':' + document.location.port;
	var tags = contenteditable_focused_document().getElementsByTagName(tagName);
	for (var i=0; i<tags.length; i++) {
		var node = tags[i];
		if ((node.nodeName == tagName) && ((""+contenteditable_getAttribute(node, attributeName)).match(new RegExp('^'+url+'.+')))) {
			contenteditable_setAttribute(node, attributeName, contenteditable_getAttribute(node, attributeName).replace(new RegExp('^'+url), ""));
		}
	}
}

function contenteditable_event_enter(my_event) {
	if (my_event && my_event.keyCode && (my_event.keyCode == 13)) {
		if ((my_event.type == "keydown") || (my_event.type == "keypress")) {
			contenteditable_undo_save();
		}
		if (webeditor.onCtrlEnter && my_event.ctrlKey) {
			contenteditable_event_stop(my_event);
			if (my_event.type == "keydown") {
				if (webeditor.onCtrlEnter.toLowerCase() == "<p>") {
					contenteditable_event_enter_p();
				} else {
					contenteditable_pasteContent(webeditor.onCtrlEnter);
				}
			}
		} else if (webeditor.onShiftEnter && my_event.shiftKey) {
			contenteditable_event_stop(my_event);
			if (my_event.type == "keydown") {
				if (webeditor.onShiftEnter.toLowerCase() == "<p>") {
					contenteditable_event_enter_p();
				} else {
					contenteditable_pasteContent(webeditor.onShiftEnter);
				}
			}
		} else if (webeditor.onAltEnter && my_event.altKey) {
			contenteditable_event_stop(my_event);
			if (my_event.type == "keydown") {
				if (webeditor.onAltEnter.toLowerCase() == "<p>") {
					contenteditable_event_enter_p();
				} else {
					contenteditable_pasteContent(webeditor.onAltEnter);
				}
			}
		} else if (webeditor.onEnter && (contenteditable_selection_container().tagName != "LI")) {
			contenteditable_event_stop(my_event);
			if (my_event.type == "keydown") {
				if (webeditor.onEnter.toLowerCase() == "<p>") {
					contenteditable_event_enter_p();
				} else {
					contenteditable_pasteContent(webeditor.onEnter);
				}
			}
		}
	} else if (my_event && my_event.ctrlKey && (my_event.type == "keydown") && (my_event.keyCode == webeditor_keyCode_undo)) {
		contenteditable_event_stop(my_event);
		contenteditable_undo();
	} else if (my_event && my_event.ctrlKey && (my_event.type == "keydown") && (my_event.keyCode == webeditor_keyCode_redo)) {
		contenteditable_event_stop(my_event);
		contenteditable_redo();
	} else if (my_event && (my_event.type == "keydown")) {
		if (! webeditor.undoSaveBeforeUndo) {
			contenteditable_undo_save();
			webeditor.undoSaveBeforeUndo = true;
		}
	} else if (my_event && (my_event.type == "mouseup")) {
		// QQQ detect mouse drag & drop
		//if (! webeditor.undoSaveBeforeUndo) {
		//	contenteditable_undo_save();
		//	webeditor.undoSaveBeforeUndo = true;
		//}
	} else {
		//if (my_event && my_event.ctrlKey && (my_event.type == "keydown") && (my_event.keyCode != 17)) alert(my_event.keyCode);
	}
}

function contenteditable_positionable() {
	var element = contenteditable_selection_container();
	while (element && (element.tagName != "IMG") && (element.tagName != "TABLE") && (element.tagName != "P") && (element.tagName != "DIV") && (element.tagName != "INPUT") && (element.tagName != "SELECT") && (element.tagName != "TEXTAREA") && (element.tagName != "IFRAME") && (element.tagName != "MARQUEE") && (element.tagName != "HR") && (element.tagName != "OBJECT")) {
		element = element.parentNode;
	}
	return element;
}

function contenteditable_positionable_front_max() {
	var content = contenteditable_focused_document().body;
	var tag = content;
	var zIndex = 0;
	while (tag = contenteditable_nextnode(content, tag)) {
		if (tag.style && (tag.style.position || tag.style.zIndex)) {
			var this_zIndex = parseInt(tag.style.zIndex) || parseInt("0" + tag.style.zIndex);
			if (this_zIndex > zIndex) zIndex = this_zIndex;
		}
	}
	return zIndex;
}

function contenteditable_positionable_back_min() {
	var content = contenteditable_focused_document().body;
	var tag = content;
	var zIndex = contenteditable_positionable_front_max();
	while (tag = contenteditable_nextnode(content, tag)) {
		if (tag.style && (tag.style.position || tag.style.zIndex)) {
			var this_zIndex = parseInt(tag.style.zIndex) || parseInt("0" + tag.style.zIndex);
			if (this_zIndex < zIndex) zIndex = this_zIndex;
		}
	}
	return zIndex;
}

function contenteditable_removeformat() {
	var content = contenteditable_getContentSelection();
	//content = cleanContentSub(content, '', '', '', '', '', all_style, empty_span, '', '', all_font, '', empty_p_div, all_format_tags);
	content = cleanContentSub(content, '', '', '', '', '', true, true, '', '', true, '', true, true);
	contenteditable_event_paste_do_pre();
	contenteditable_pasteContent(content);
	contenteditable_event_paste_do_post();
	contenteditable_event_paste_fix();
}

function contenteditable_preview() {
	var preview_window = window.open("", "preview_window", "width=800,height=600,resizable=yes,status=yes,titlebar=yes,scrollbars=yes,menubar=no,location=no,toolbar=no", true);
	preview_window.focus();
	var content = contenteditable_formatContent(contenteditable_getContentBodyNode());
	content = contenteditable_decodeScriptTags(content);
	try {
		if (webeditor_custom_decode) content = webeditor_custom_decode(content);
	} catch(e) {
	}

	if (webeditor.format == "xhtml") {
		preview_window.document.writeln('<?xml version="1.0" encoding="' + webeditor.encoding + '" ?>');
		preview_window.document.writeln('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
		preview_window.document.writeln('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');
	} else {
		preview_window.document.writeln('<html>');
	}
	preview_window.document.writeln('<head>');
	preview_window.document.writeln('<title>'+Text('preview_title')+'</title>');
	preview_window.document.writeln('<link rel="stylesheet" type="text/css" href="' + webeditor.stylesheet + '" />');
	preview_window.document.writeln('</head>');
	preview_window.document.writeln('<body style="margin: 0px;">');
	preview_window.document.writeln(content);
	preview_window.document.writeln('</body>');
	preview_window.document.writeln('</html>');
	preview_window.document.close();
}

function contenteditable_spellcheck() {
	var spellcheck_window;
	if ((typeof(spellcheck_window) == "undefined") || spellcheck_window.closed) {
		var id = contenteditable_focused_iframe().id;
		if (webeditor.language) {
			spellcheck_window = window.open(webeditor.rootpath+"spellcheck."+webeditor.language+"?editor=webeditor&id="+id, "spellcheck_window", "scrollbars=yes,width=350,height=350,resizable=yes,status=yes", true);
			spellcheck_window.focus();
		} else {
//			spellcheck_window = window.open(webeditor.rootpath+"spellcheck.html?editor=webeditor&id="+id, "spellcheck_window", "scrollbars=yes,width=350,height=350,resizable=yes,status=yes", true);
//			spellcheck_window.focus();
		}
	}
}

function contenteditable_save() {
	contenteditable_onSubmit();
	var form = contenteditable_focused_iframe();
	while ((form = form.parentNode) && (form.nodeName != "FORM"));
	if (form) form.submit();
}

function contenteditable_remove_parentnode(node) {
	// MSIE DOM may be broken with lost childNodes
	var nodeCount = 0;
	for (var count_node=node.firstChild; count_node; count_node=count_node.nextSibling) {
		nodeCount++;
	}
	if (nodeCount == node.childNodes.length) {
		var parentnode = node.parentNode;
		var nextsibling =  node.nextSibling;
		if (parentnode) {
			parentnode.removeChild(node);
			for (var childnode=node.lastChild; childnode; childnode=childnode.previousSibling) {
				var new_node = childnode.cloneNode(true);
				if (new_node && nextsibling && nextsibling.insertBefore) {
					parentnode.insertBefore(new_node, nextsibling);
					nextsibling = new_node;
				} else {
					parentnode.appendChild(new_node);
					nextsibling = new_node;
				}
			}
		}
	}
}

function contenteditable_nobr() {
	var nobr = contenteditable_selection_container('nobr');
	if (nobr) {
		contenteditable_remove_parentnode(nobr);
	} else {
		var text = contenteditable_selection_text() || '&nbsp;';
		contenteditable_event_paste_do_pre();
		contenteditable_pasteContent('<nobr>' + text + '</nobr>');
		contenteditable_event_paste_do_post();
		contenteditable_event_paste_fix();
	}
}

function contenteditable_printbreak() {
//	var nobr = contenteditable_selection_container('nobr');
//	if (nobr) {
//		contenteditable_remove_parentnode(nobr);
//	} else {
		var text = contenteditable_selection_text() || '&nbsp;';
		contenteditable_pasteContent('<div title="Print Page Break" style="page-break-before: always; background-color: #C0C0C0; vertical-align: middle; height: 1px; font-size: 1px;">' + text + '</div>');
//	}
}

function contenteditable_position_offsetTop(node) {
	//if (node.nodeName == "#text") node = node.parentNode;
	var top = node.offsetTop;
	while (node = node.offsetParent) {
		top += parseInt(node.offsetTop);
	}
	return top;
}

function contenteditable_position_offsetLeft(node) {
	var left = node.offsetLeft;
	while (node = node.offsetParent) {
		left += parseInt(node.offsetLeft);
	}
	return left;
}

function contenteditable_focused_getContent() {
	var iframe = document.getElementsByTagName('iframe').item(contenteditable_focused);
	if (iframe) {
		return iframe.contentWindow.document.body.innerHTML;
	}
}

function contenteditable_focused_setContent(content) {
	var iframe = document.getElementsByTagName('iframe').item(contenteditable_focused);
	if (iframe) {
		iframe.contentWindow.document.body.innerHTML = content;
	}
}

function contenteditable_undo_init() {
	if (! webeditor.undo) contenteditable_undo_save();
}

function contenteditable_undo_save() {
	var content = contenteditable_focused_getContent();
	if (! webeditor.undo) {
		webeditor.undo = new Array();
		webeditor.undo.MAX = 25;
	}
	if (! webeditor.undo[contenteditable_focused]) {
		webeditor.undo[contenteditable_focused] = new Array();
		webeditor.undo[contenteditable_focused][0] = new Object();
		webeditor.undo[contenteditable_focused][0].content = content;
		webeditor.undo[contenteditable_focused][0].bookmark = contenteditable_selection_bookmark();
		webeditor.undo[contenteditable_focused].current = 0;
		webeditor.undo[contenteditable_focused].latest = webeditor.undo[contenteditable_focused].current;
	}
	if (content != webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current].content) {
		if (! webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current+1]) {
			webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current+1] = new Object();
		}
		webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current+1].content = content;
		webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current+1].bookmark = contenteditable_selection_bookmark();
		webeditor.undo[contenteditable_focused].current++;
		webeditor.undo[contenteditable_focused].latest = webeditor.undo[contenteditable_focused].current;
	}
	if (webeditor.undo[contenteditable_focused].current == webeditor.undo.MAX) {
		for (var i=1; i<=webeditor.undo.MAX; i++) {
			webeditor.undo[contenteditable_focused][i-1].content = webeditor.undo[contenteditable_focused][i].content;
			webeditor.undo[contenteditable_focused][i-1].bookmark = webeditor.undo[contenteditable_focused][i].bookmark;
		}
		webeditor.undo[contenteditable_focused].current = webeditor.undo.MAX-1;
		webeditor.undo[contenteditable_focused].latest = webeditor.undo[contenteditable_focused].current;
	}
}

function contenteditable_undo() {
	if (webeditor.undoSaveBeforeUndo) {
		contenteditable_undo_save();
		webeditor.undoSaveBeforeUndo = false;
	}
	if (webeditor.undo && webeditor.undo[contenteditable_focused]) {
		if (webeditor.undo[contenteditable_focused].current) webeditor.undo[contenteditable_focused].current--;
		contenteditable_event_paste_do_pre();
		contenteditable_focused_setContent(webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current].content);
		contenteditable_event_paste_do_post();
		contenteditable_event_paste_fix();
		contenteditable_selection_bookmark(webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current].bookmark);
	}
}

function contenteditable_redo() {
	if (webeditor.undoSaveBeforeUndo) {
		contenteditable_undo_save();
		webeditor.undoSaveBeforeUndo = false;
	}
	if (webeditor.undo && webeditor.undo[contenteditable_focused]) {
		if (webeditor.undo[contenteditable_focused].current < webeditor.undo[contenteditable_focused].latest) webeditor.undo[contenteditable_focused].current++;
		contenteditable_event_paste_do_pre();
		contenteditable_focused_setContent(webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current].content);
		contenteditable_event_paste_do_post();
		contenteditable_event_paste_fix();
		contenteditable_selection_bookmark(webeditor.undo[contenteditable_focused][webeditor.undo[contenteditable_focused].current].bookmark);
	}
}

function contenteditable_formatContent(rootnode) {
	var content = '';
	if (webeditor.format == "html") {
		content = '';
		for (var childnode = rootnode.firstChild; childnode; childnode=childnode.nextSibling) {
			content += contenteditable_formatContentNodeHTML(childnode, childnode);
		}
	} else if (webeditor.format) { // webeditor.format == "xhtml"
		content = '';
		for (var childnode = rootnode.firstChild; childnode; childnode=childnode.nextSibling) {
			content += contenteditable_formatContentNodeXHTML(childnode, childnode);
		}
	} else if (rootnode) {
		content = rootnode.innerHTML;
	}
	return content;
}

function contenteditable_formatContentNodeHTML(node) {
	var content = '';
	if (node.tagName) {
		if (node.firstChild) {
			content += '<' + node.tagName.toLowerCase() + contenteditable_formatContentNodeAttributes(node) + '>';
			if (node.tagName.match(/^(TABLE|THEAD|TBODY|TFOOT|TR)$/)) {
				content += '\r\n';
			}
			var childnode = node;
			for (var childnode = node.firstChild; childnode; childnode=childnode.nextSibling) {
				content += contenteditable_formatContentNodeHTML(childnode, childnode);
			}
			content += '</' + node.tagName.toLowerCase() + '>';
			if (node.tagName.match(/^(TABLE|THEAD|TBODY|TFOOT|TR|TD|P|DIV)$/)) {
				content += '\r\n';
			}
		} else {
			content += '<' + node.tagName.toLowerCase() + contenteditable_formatContentNodeAttributes(node) + '>';
			if (node.tagName.match(/^(P|BR|HR)$/)) {
				content += '\r\n';
			}
		}
	} else if (node.nodeValue) {
		var value = node.nodeValue;
		while (value.match(/([^-a-zA-Z0-9\t\n\r !#$%&'()*+,.\/:;=?@\[\\\]^_`{|}~])/)) {
			value = value.replace(RegExp.$1, contenteditable_formatContentEscape(RegExp.$1));
		}
		value = value.replace(/(&(?![#a-zA-Z0-9]+;))/, "&amp;");
		content += value;
	}
	return content;
}

function contenteditable_formatContentNodeXHTML(node) {
	var content = '';
	if (node.tagName) {
		if (node.firstChild) {
			content += '<' + node.tagName.toLowerCase() + contenteditable_formatContentNodeAttributes(node) + '>';
			if (node.tagName.match(/^(TABLE|THEAD|TBODY|TFOOT|TR)$/)) {
				content += '\r\n';
			}
			var childnode = node;
			for (var childnode = node.firstChild; childnode; childnode=childnode.nextSibling) {
				content += contenteditable_formatContentNodeXHTML(childnode, childnode);
			}
			content += '</' + node.tagName.toLowerCase() + '>';
			if (node.tagName.match(/^(TABLE|THEAD|TBODY|TFOOT|TR|TD|P|DIV)$/)) {
				content += '\r\n';
			}
		} else {
			content += '<' + node.tagName.toLowerCase() + contenteditable_formatContentNodeAttributes(node) + ' />';
			if (node.tagName.match(/^(P|BR|HR)$/)) {
				content += '\r\n';
			}
		}
	} else if (node.nodeValue) {
		var value = node.nodeValue;
		while (value.match(/([^-a-zA-Z0-9\t\n\r !#$%&'()*+,.\/:;=?@\[\\\]^_`{|}~])/)) {
			value = value.replace(RegExp.$1, contenteditable_formatContentEscape(RegExp.$1));
		}
		value = value.replace(/(&(?![#a-zA-Z0-9]+;))/, "&amp;");
		content += value;
	}
	return content;
}

function contenteditable_formatContentNodeAttributes(node) {
	var attributes = "";
	if (node && node.attributes) {
		for (var attribute=0; attribute<node.attributes.length; attribute++) {
			if ((node.attributes[attribute].specified) && (node.attributes[attribute].name) && (node.attributes[attribute].name.substr(0,5) != "_moz_") && (node.attributes[attribute].name.substr(0,5) != "_moz-")) {
				if (contenteditable_getAttribute(node, node.attributes[attribute].name)) {
					attributes += ' ' + node.attributes[attribute].name + '="' + contenteditable_getAttribute(node, node.attributes[attribute].name) + '"';
				} else {
					attributes += ' ' + node.attributes[attribute].name + '="' + node.attributes[attribute].name + '"';
				}
			} else if ((node.attributes[attribute].specified) && (node.attributes[attribute].nodeName) && (node.attributes[attribute].nodeName.substr(0,5) != "_moz_") && (node.attributes[attribute].nodeName.substr(0,5) != "_moz-")) {
				if (contenteditable_getAttribute(node, node.attributes[attribute].nodeName)) {
					attributes += ' ' + node.attributes[attribute].nodeName + '="' + contenteditable_getAttribute(node, node.attributes[attribute].nodeName) + '"';
				} else {
					attributes += ' ' + node.attributes[attribute].nodeName + '="' + node.attributes[attribute].nodeName + '"';
				}
			}
		}
	}
	return attributes;
}

function contenteditable_formatContentEscape(value) {
	switch(value.charCodeAt(0)) {

	case 34:	return '&quot;';
	case 38:	return '&amp;';
	case 39:	return '&apos;';
	case 60:	return '&lt;';
	case 62:	return '&gt;';

	case 160:	return '&nbsp;';
	case 161:	return '&iexcl;';
	case 162:	return '&cent;';
	case 163:	return '&pound;';
	case 164:	return '&curren;';
	case 165:	return '&yen;';
	case 166:	return '&brvbar;';
	case 167:	return '&sect;';
	case 168:	return '&uml;';
	case 169:	return '&copy;';
	case 170:	return '&ordf;';
	case 171:	return '&laquo;';
	case 172:	return '&not;';
	case 173:	return '&shy;';
	case 174:	return '&reg;';
	case 175:	return '&macr;';
	case 176:	return '&deg;';
	case 177:	return '&plusmn;';
	case 178:	return '&sup2;';
	case 179:	return '&sup3;';
	case 180:	return '&acute;';
	case 181:	return '&micro;';
	case 182:	return '&para;';
	case 183:	return '&middot;';
	case 184:	return '&cedil;';
	case 185:	return '&supl;';
	case 186:	return '&ordm;';
	case 187:	return '&raquo;';
	case 188:	return '&frac14;';
	case 189:	return '&frac12;';
	case 190:	return '&frac34;';
	case 191:	return '&iquest;';
	case 192:	return '&Agrave;';
	case 193:	return '&Aacute;';
	case 194:	return '&Acirc;';
	case 195:	return '&Atilde;';
	case 196:	return '&Auml;';
	case 197:	return '&Aring;';
	case 198:	return '&AElig;';
	case 199:	return '&Ccedil;';
	case 200:	return '&Egrave;';
	case 201:	return '&Eacute;';
	case 202:	return '&Ecirc;';
	case 203:	return '&Euml;';
	case 204:	return '&Igrave;';
	case 205:	return '&Iacute;';
	case 206:	return '&Icirc;';
	case 207:	return '&Iuml;';
	case 208:	return '&ETH;';
	case 209:	return '&Ntilde;';
	case 210:	return '&Ograve;';
	case 211:	return '&Oacute;';
	case 212:	return '&Ocirc;';
	case 213:	return '&Otilde;';
	case 214:	return '&Ouml;';
	case 215:	return '&times;';
	case 216:	return '&Oslash;';
	case 217:	return '&Ugrave;';
	case 218:	return '&Uacute;';
	case 219:	return '&Ucirc;';
	case 220:	return '&Uuml;';
	case 221:	return '&Yacute;';
	case 222:	return '&THORN;';
	case 223:	return '&szlig;';
	case 224:	return '&agrave;';
	case 225:	return '&aacute;';
	case 226:	return '&acirc;';
	case 227:	return '&atilde;';
	case 228:	return '&auml;';
	case 229:	return '&aring;';
	case 230:	return '&aelig;';
	case 231:	return '&ccedil;';
	case 232:	return '&egrave;';
	case 233:	return '&eacute;';
	case 234:	return '&ecirc;';
	case 235:	return '&euml;';
	case 236:	return '&igrave;';
	case 237:	return '&iacute;';
	case 238:	return '&icirc;';
	case 239:	return '&iuml;';
	case 240:	return '&eth;';
	case 241:	return '&ntilde;';
	case 242:	return '&ograve;';
	case 243:	return '&oacute;';
	case 244:	return '&ocirc;';
	case 245:	return '&otilde;';
	case 246:	return '&ouml;';
	case 247:	return '&divide;';
	case 248:	return '&oslash;';
	case 249:	return '&ugrave;';
	case 250:	return '&uacute;';
	case 251:	return '&ucirc;';
	case 252:	return '&uuml;';
	case 253:	return '&yacute;';
	case 254:	return '&thorn;';
	case 255:	return '&yuml;';

	case 338:	return '&OElig;';
	case 339:	return '&oelig;';
	case 352:	return '&Scaron;';
	case 353:	return '&scaron;';
	case 376:	return '&Yuml;';

	case 710:	return '&circ;';
	case 732:	return '&tilde;';

	case 8194:	return '&ensp;';
	case 8195:	return '&emsp;';
	case 8201:	return '&thinsp;';
	case 8204:	return '&zwnj;';
	case 8205:	return '&zwj;';
	case 8206:	return '&lrm;';
	case 8207:	return '&rlm;';
	case 8211:	return '&ndash;';
	case 8212:	return '&mdash;';
	case 8216:	return '&lsquo;';
	case 8217:	return '&rsquo;';
	case 8218:	return '&sbquo;';
	case 8220:	return '&ldquo;';
	case 8221:	return '&rdquo;';
	case 8222:	return '&bdquo;';
	case 8224:	return '&dagger;';
	case 8225:	return '&Dagger;';
	case 8240:	return '&permil;';
	case 8249:	return '&lsaquo;';
	case 8250:	return '&rsaquo;';
	case 8364:	return '&euro;';

	case 402:	return '&fnof;';

	case 913:	return '&Alpha;';
	case 914:	return '&Beta;';
	case 915:	return '&Gamma;';
	case 916:	return '&Delta;';
	case 917:	return '&Epsilon;';
	case 918:	return '&Zeta;';
	case 919:	return '&Eta;';
	case 920:	return '&Theta;';
	case 921:	return '&Iota;';
	case 922:	return '&Kappa;';
	case 923:	return '&Lambda;';
	case 924:	return '&Mu;';
	case 925:	return '&Nu;';
	case 926:	return '&Xi;';
	case 927:	return '&Omicron;';
	case 928:	return '&Pi;';
	case 929:	return '&Rho;';
	case 931:	return '&Sigma;';
	case 932:	return '&Tau;';
	case 933:	return '&Upsilon;';
	case 934:	return '&Phi;';
	case 935:	return '&Chi;';
	case 936:	return '&Psi;';
	case 937:	return '&Omega;';
	case 945:	return '&alpha;';
	case 946:	return '&beta;';
	case 947:	return '&gamma;';
	case 948:	return '&delta;';
	case 949:	return '&epsilon;';
	case 950:	return '&zeta;';
	case 951:	return '&eta;';
	case 952:	return '&theta;';
	case 953:	return '&iota;';
	case 954:	return '&kappa;';
	case 955:	return '&lambda;';
	case 956:	return '&mu;';
	case 957:	return '&nu;';
	case 958:	return '&xi;';
	case 959:	return '&omicron;';
	case 960:	return '&pi;';
	case 961:	return '&rho;';
	case 962:	return '&sigmaf;';
	case 963:	return '&sigma;';
	case 964:	return '&tau;';
	case 965:	return '&upsilon;';
	case 966:	return '&phi;';
	case 967:	return '&chi;';
	case 968:	return '&psi;';
	case 969:	return '&omega;';
	case 977:	return '&thetasym;';
	case 978:	return '&upsih;';
	case 982:	return '&piv;';

	case 8226:	return '&bull;';
	case 8230:	return '&hellip;';
	case 8242:	return '&prime;';
	case 8243:	return '&Prime;';
	case 8254:	return '&oline;';
	case 8260:	return '&frasl;';

	case 8472:	return '&weierp;';
	case 8465:	return '&image;';
	case 8476:	return '&real;';
	case 8482:	return '&trade;';
	case 8501:	return '&alefsym;';

	case 8592:	return '&larr;';
	case 8593:	return '&uarr;';
	case 8594:	return '&rarr;';
	case 8595:	return '&darr;';
	case 8596:	return '&harr;';
	case 8629:	return '&crarr;';
	case 8656:	return '&lArr;';
	case 8657:	return '&uArr;';
	case 8658:	return '&rArr;';
	case 8659:	return '&dArr;';
	case 8660:	return '&hArr;';

	case 8704:	return '&forall;';
	case 8706:	return '&part;';
	case 8707:	return '&exist;';
	case 8709:	return '&empty;';
	case 8711:	return '&nabla;';
	case 8712:	return '&isin;';
	case 8713:	return '&notin;';
	case 8715:	return '&ni;';
	case 8719:	return '&prod;';
	case 8721:	return '&sum;';
	case 8722:	return '&minus;';
	case 8727:	return '&lowast;';
	case 8730:	return '&radic;';
	case 8733:	return '&prop;';
	case 8734:	return '&infin;';
	case 8736:	return '&ang;';
	case 8743:	return '&and;';
	case 8744:	return '&or;';
	case 8745:	return '&cap;';
	case 8746:	return '&cup;';
	case 8747:	return '&int;';
	case 8756:	return '&there4;';
	case 8764:	return '&sim;';
	case 8773:	return '&cong;';
	case 8776:	return '&asymp;';
	case 8800:	return '&ne;';
	case 8801:	return '&equiv;';
	case 8804:	return '&le;';
	case 8805:	return '&ge;';
	case 8834:	return '&sub;';
	case 8835:	return '&sup;';
	case 8836:	return '&nsub;';
	case 8838:	return '&sube;';
	case 8839:	return '&supe;';
	case 8853:	return '&oplus;';
	case 8855:	return '&otimes;';
	case 8869:	return '&perp;';
	case 8901:	return '&sdot;';

	case 8968:	return '&lceil;';
	case 8969:	return '&rceil;';
	case 8970:	return '&lfloor;';
	case 8971:	return '&rfloor;';
	case 9001:	return '&lang;';
	case 9002:	return '&rang;';

	case 9674:	return '&loz;';

	case 9824:	return '&spades;';
	case 9827:	return '&clubs;';
	case 9829:	return '&hearts;';
	case 9830:	return '&diams;';

	default:	return '&#' + value.charCodeAt(0) + ';';
	}
}

function contenteditable_encodeScriptTags(content) {
	RegExp.global = true;
	RegExp.multiline = true;

	content = '' + content;

	content = content.replace(/<script([^>]*)>/gi, "&lt;script$1&gt;");
	content = content.replace(/<\/script>/gi, "&lt;/script&gt;");

	content = content.replace(/<!--([^>]*)-->/gi, "&lt;!--$1--&gt;");

	return content;
}

function contenteditable_decodeScriptTags(content) {
	RegExp.global = true;
	RegExp.multiline = true;

	content = '' + content;

	content = content.replace(/&lt;!--([^&]*)--&gt;/gi, "<!--$1-->");
	content = content.replace(/&lt;!--([^>]*)--&gt;/gi, "<!--$1-->");

	content = content.replace(/&lt;script([^&]*)&gt;/gi, "<script$1>");
	content = content.replace(/&lt;\/script&gt;/gi, "</script>");
//	while (content.match(/<script>((.|\r|\n)*)<br>([\r\n](.|\r|\n)*)<\/script>/gi)) {
//		content = content.replace(/<script>((.|\r|\n)*)<br>([\r\n](.|\r|\n)*)<\/script>/gi, "<script>$1$3</script>");
//	}

	return content;
}
