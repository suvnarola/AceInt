// HardCore Web Content Editor
// Copyright 2002-2004 HardCore Internet Ltd.
// www.hardcoreinternet.co.uk

var webeditor;
webeditor.inited = 0;

///////////////////////////////////////////////////////////////////////////////////////////////////
// Main function/object called from web page at location of the web editor content
///////////////////////////////////////////////////////////////////////////////////////////////////

function HardCoreWebEditor(rootpath, language, name, value, value_htmlencoded, stylesheet, showhtml, manager, onEnter, onShiftEnter, onCtrlEnter, onAltEnter, toolbar, width, height, format, encoding, direction) {
	document.write(Text('webbrowser_unsupported_textarea'));
	document.write('<textarea name="'+name+'" cols="80" rows="25">'+unescape(value)+'</textarea>');
	webeditor.inited += 1;
}

function HardCoreWebEditorFocus() {
}

function HardCoreWebEditorDOMInspector(name) {
}

function HardCoreWebEditorToolbar(options) {
}

function HardCoreWebEditorStylesheet(stylesheet) {
}

function HardCoreWebEditorSubmit() {
}

function HardCoreWebEditorGetContent(id) {
}

function HardCoreWebEditorGetContentSelection(id) {
}

function HardCoreWebEditorSetContent(content, id) {
}

function HardCoreWebEditorPasteContent(content, id) {
}

function HardCoreWebEditorCleanContent(all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
}

function HardCoreWebEditorCleanContentString(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
	return content;
}

