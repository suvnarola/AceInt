// HardCore Web Content Editor
// Copyright 2002-2004 HardCore Internet Ltd.
// www.hardcoreinternet.co.uk

var webeditor;
webeditor.inited = 0;

webeditor_custom_encode = false;
webeditor_custom_decode = false;

///////////////////////////////////////////////////////////////////////////////////////////////////
// Main function/object called from web page at location of the web editor content
///////////////////////////////////////////////////////////////////////////////////////////////////

function HardCoreWebEditor(rootpath, language, name, value, value_htmlencoded, stylesheet, showhtml, manager, onEnter, onShiftEnter, onCtrlEnter, onAltEnter, toolbar, width, height, format, encoding, direction) {
	this.rootpath = rootpath;
	this.language = language;
	this.editor = name;
	this.manager = manager;
	if (! HardCoreWebEditor.instance) {
		HardCoreWebEditor.instance = new Array();
		this.instance = 1;
	} else {
		this.instance = HardCoreWebEditor.instance.length;
	}
	HardCoreWebEditor.instance[this.instance] = this;

	this.DHTMLSafe;
	this.HTML;
	this.BreakOnEnter = false;
	this.BaseURL = document.location.protocol + "//" + document.location.host;
	this.ContentItem;
	this.ShowBorders = true;
	this.ShowDetails = false;
	this.initialised = false;

	this.initialise = initialise;
	this.onLoad = onLoad;
	this.onSubmit = onSubmit;
	this.onKeyPress = onKeyPress;
	this.onButtonMouseOver = onButtonMouseOver;
	this.onButtonMouseOut = onButtonMouseOut;
	this.onButtonMouseDown = onButtonMouseDown;
	this.onButtonMouseUp = onButtonMouseUp;
	this.onButtonClick = onButtonClick;
	this.onButtonImage = onButtonImage;
	this.onButtonHyperlink = onButtonHyperlink;
	this.onButtonTable = onButtonTable;
	this.onButtonTableProperties = onButtonTableProperties;
	this.onButtonRowProperties = onButtonRowProperties;
	this.onButtonCellProperties = onButtonCellProperties;
	this.isTable = isTable;
	this.isRow = isRow;
	this.isCell = isCell;
	this.onButtonHTML = onButtonHTML;
	this.onButtonSpecialCharacter = onButtonSpecialCharacter;
	this.onButtonDetails = onButtonDetails;
	this.onButtonHelp = onButtonHelp;
	this.onFontFormat = onFontFormat;
	this.onFontSize = onFontSize;
	this.insertImage = insertImage;
	this.insertHyperlink = insertHyperlink;
	this.insertTable = insertTable;
	this.updateTable = updateTable;
	this.updateRow = updateRow;
	this.updateCell = updateCell;
	this.insertSpecialCharacter = insertSpecialCharacter;
	this.refreshToolbar = refreshToolbar;
	this.showContextMenu = showContextMenu;
	this.contextMenuAction = contextMenuAction;

	if (webeditor_custom_encode) value = webeditor_custom_encode(value);
	if (webeditor_custom_encode) value_htmlencoded = webeditor_custom_encode(value_htmlencoded);
	this.create = create;
	if ((value_htmlencoded == "") && (value != '')) {
		value_htmlencoded = value;
		value_htmlencoded = value_htmlencoded.replace(/&/g,"&amp;");
		value_htmlencoded = value_htmlencoded.replace(/</g,"&lt;");
		value_htmlencoded = value_htmlencoded.replace(/>/g,"&gt;");
	}
	this.create(value_htmlencoded);
	this.initialise();
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
	onSubmit();
}

function HardCoreWebEditorGetContent(id) {
	var content = "";
	if (id) {
		var editor;
		for (var i=1; i<=HardCoreWebEditor.instance.length; i++) {
			if (editor = HardCoreWebEditor.instance[i]) {
				if (editor.editor == id) {
					if (editor.HTML) {
						editor.onButtonHTML();
					}
					content = editor.DHTMLSafe.DOM.body.innerHTML;
					if (content.length) {
						content = editor.DHTMLSafe.FilterSourceCode(content);
					}
				}
			}
		}
	} else {
		if (editor = HardCoreWebEditor.instance[contenteditable_focused]) {
			if (editor.HTML) {
				editor.onButtonHTML();
			}
			content = editor.DHTMLSafe.DOM.body.innerHTML;
			if (content.length) {
				content = editor.DHTMLSafe.FilterSourceCode(content);
			}
		}
	}
	if (webeditor_custom_decode) content = webeditor_custom_decode(content);
	return content;
}

function HardCoreWebEditorGetContentSelection(id) {
	var content = "";
	if (id) {
		var editor;
		for (var i=1; i<=HardCoreWebEditor.instance.length; i++) {
			if (editor = HardCoreWebEditor.instance[i]) {
				if (editor.editor == id) {
					content = editor.DHTMLSafe.DOM.selection.createRange().htmlText || editor.DHTMLSafe.DOM.selection.createRange().text;
				}
			}
		}
	} else {
		if (editor = HardCoreWebEditor.instance[contenteditable_focused]) {
			content = editor.DHTMLSafe.DOM.selection.createRange().htmlText || editor.DHTMLSafe.DOM.selection.createRange().text;
		}
	}
	if (webeditor_custom_decode) content = webeditor_custom_decode(content);
	return content;
}

function HardCoreWebEditorSetContent(content, id) {
	if (webeditor_custom_encode) content = webeditor_custom_encode(content);
	if (id) {
		var editor;
		for (var i=1; i<=HardCoreWebEditor.instance.length; i++) {
			if (editor = HardCoreWebEditor.instance[i]) {
				if (editor.editor == id) {
					if (editor.HTML) {
						editor.onButtonHTML();
					}
					if (content.length) {
						content = editor.DHTMLSafe.FilterSourceCode(content);
					}
					editor.DHTMLSafe.DOM.body.innerHTML = content;
				}
			}
		}
	} else {
		if (editor = HardCoreWebEditor.instance[contenteditable_focused]) {
			if (editor.HTML) {
				editor.onButtonHTML();
			}
			if (content.length) {
				content = editor.DHTMLSafe.FilterSourceCode(content);
			}
			editor.DHTMLSafe.DOM.body.innerHTML = content;
		}
	}
}

function HardCoreWebEditorPasteContent(content, id) {
	if (webeditor_custom_encode) content = webeditor_custom_encode(content);
	if (id) {
		var editor;
		for (var i=1; i<=HardCoreWebEditor.instance.length; i++) {
			if (editor = HardCoreWebEditor.instance[i]) {
				if (editor.editor == id) {
					editor.DHTMLSafe.DOM.selection.createRange().pasteHTML(content);
				}
			}
		}
	} else {
		if (editor = HardCoreWebEditor.instance[contenteditable_focused]) {
			editor.DHTMLSafe.DOM.selection.createRange().pasteHTML(content);
		}
	}
}

function HardCoreWebEditorCleanContent(all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
}

function HardCoreWebEditorCleanContentString(content, all_html, all_xml, all_namespace, all_lang, all_class, all_style, empty_span, all_span, empty_font, all_font, all_del_ins, empty_p_div) {
	return content;
}



var contenteditable_focused = 0;
var contenteditable_onfocus = new Array();

function initialise() {
	this.ContentItem = eval("document.all."+this.editor+this.instance);
	this.Form = this.ContentItem;

	if (this.Form) {
		while ((this.Form.tagName != "FORM") && (this.Form.tagName != "HTML")) {
			this.Form = this.Form.parentElement;
		}
		
		if (this.Form.tagName != "HTML") {
			this.DHTMLSafe = eval('this.Form.DHTMLSafe_'+this.editor+this.instance);
			this.Form.onsubmit = this.onSubmit;
			if (! contenteditable_onfocus[this.instance]) contenteditable_onfocus[this.instance] = new Function('contenteditable_focus'+this.instance, 'contenteditable_focused='+this.instance+';');
			if (this.DHTMLSafe.attachEvent) {
				this.DHTMLSafe.attachEvent("onfocus", contenteditable_onfocus[this.instance]);
			} else {
				this.DHTMLSafe.onfocus = contenteditable_onfocus[this.instance];
			}
		}
	}

	if (this.DHTMLSafe) {
		this.DHTMLSafe.NewDocument();
		this.DHTMLSafe.BaseURL=this.BaseURL;
		this.DHTMLSafe.ShowBorders = this.ShowBorders;
		this.DHTMLSafe.ShowDetails = this.ShowDetails;
	 	this.refreshToolbar();
		this.onLoad();
		this.HTML = false;
	}
}



var onLoad_setTimeout;

function onLoad() {
	if (this.DHTMLSafe.Busy) {
		onLoad_setTimeout = this;
		setTimeout("onLoad_setTimeout.onLoad();", 100);
		return;
	}

	if (this.instance == 1) window.onUnload = this.onUnload;

	var instance;
	for (instance in HardCoreWebEditor.instance) {
		if (! HardCoreWebEditor.instance[instance].initialised) {
			HardCoreWebEditor.instance[instance].initialised = true;
		
			if (select = eval('document.all.'+HardCoreWebEditor.instance[instance].editor+'_fontstyle'+HardCoreWebEditor.instance[instance].instance)) { //Populate QuickFormat Box
				var obj = new ActiveXObject("DEGetBlockFmtNamesParam.DEGetBlockFmtNamesParam");
				HardCoreWebEditor.instance[instance].DHTMLSafe.ExecCommand(DECMD_GETBLOCKFMTNAMES,OLECMDEXECOPT_DODEFAULT,obj);
				var names = new VBArray(obj.Names);
				var names = names.toArray();
				
				for (var i=0; i<names.length; i++) {
					option = document.createElement("OPTION");
					option.text = names[i];
					option.name = names[i];
					select.options.add(option);
				}
			}
				
			if (HardCoreWebEditor.instance[instance].ContentItem.value.length) {
				HardCoreWebEditor.instance[instance].DHTMLSafe.DOM.body.innerHTML = HardCoreWebEditor.instance[instance].ContentItem.value;
			} else {
				HardCoreWebEditor.instance[instance].DHTMLSafe.DOM.body.innerHTML = " ";
			}

			HardCoreWebEditor.instance[instance].refreshToolbar();
		}
	}
}



function onSubmit() {
	var editor;
	for (var i=1; i<=HardCoreWebEditor.instance.length; i++) {
		if (editor = HardCoreWebEditor.instance[i]) {
			if (editor.HTML) {
				editor.onButtonHTML();
			}
			var value = editor.DHTMLSafe.DOM.body.innerHTML;
			if (webeditor_custom_decode) value = webeditor_custom_decode(value);
			editor.ContentItem.value = value;
			if (editor.ContentItem.value.length) {
				editor.ContentItem.value = editor.DHTMLSafe.FilterSourceCode(editor.ContentItem.value);
			}
		}
	}
}



function onKeyPress() {
	if (this.HTML || this.BreakOnEnter) {
		// Enter: <br> - CTRL+Enter: <p>
		if (this.DHTMLSafe.DOM.parentWindow.event.keyCode == 10) {
			this.DHTMLSafe.DOM.parentWindow.event.keyCode = 13;			
		} else if (this.DHTMLSafe.DOM.parentWindow.event.keyCode == 13) {
			if (this.DHTMLSafe.QueryStatus(DECMD_UNORDERLIST) != DECMDF_LATCHED) {
				this.DHTMLSafe.DOM.parentWindow.event.returnValue = false;		
				var selected = this.DHTMLSafe.DOM.selection.createRange();
				selected.pasteHTML("<BR>");
				selected.collapse(false);
				selected.select();
			}
		}
	}
}



function onButtonMouseOver(button) {
	if (button.state == 0) {
		return;
	} else if (button.state == 2) {
		return;
	} else if (button.type == "button") {
		button.className = "buttonMouseOver";
		return;
	}
}



function onButtonMouseOut(button) {
	if (button.state == 0) {
		return;
	} else if (button.state == 2) {
		button.className = "buttonDown";
		return;
	} else if (button.type == "button") {
		button.className = "buttonUp";
		return;
	}
}



function onButtonMouseDown(button) {
	if (button.state == 0) {
		return;
	} else if (button.type == "button") {
		button.className="buttonClick";
		return;
	}
}



function onButtonMouseUp(src) {
	if (src.state == 0) {
		return;
	} else if (src.state==2) {
		src.className="buttonDown";
		return;
	} else if (src.type == "button") {
		src.className="buttonMouseOver";
		return;
	}
}



function onButtonClick(decmd) {
	if (this.HTML) { return; }

	if (decmd == null) decmd = eval(window.event.srcElement.decmd);
	if (decmd == "tableproperties") {
		this.onButtonTableProperties();
	} else if (decmd == "rowproperties") {
		this.onButtonRowProperties();
	} else if (decmd == "cellproperties") {
		this.onButtonCellProperties();
	} else if (this.DHTMLSafe.QueryStatus(decmd) != DECMDF_DISABLED) {
		this.DHTMLSafe.ExecCommand(decmd, OLECMDEXECOPT_DODEFAULT);
	}
	this.DHTMLSafe.focus();
}



function onFontFormat() {
	if (this.HTML) { return; }

	var select = eval('document.all.'+this.editor+'_fontstyle'+this.instance);
	this.DHTMLSafe.ExecCommand(DECMD_SETBLOCKFMT, OLECMDEXECOPT_DODEFAULT, select.options[select.selectedIndex].name);
	this.DHTMLSafe.focus();
}



function onFontSize() {
	if (this.HTML) { return; }

	var select = eval('document.all.'+this.editor+'_fontsize' + this.instance);
	this.DHTMLSafe.ExecCommand(DECMD_SETFONTSIZE, OLECMDEXECOPT_DODEFAULT, select.options[select.selectedIndex].value);
	this.DHTMLSafe.focus();
}



function refreshToolbar() {
	if (! this.DHTMLSafe) return;
	if (this.DHTMLSafe.QueryStatus(5002) != this.DHTMLSafe.QueryStatus(5003)) return;

	var select;
	var toolbar = eval(this.editor + "_toolbar" + this.instance);

	for (var i=0; i<toolbar.all.length; i++) {
		var button = toolbar.all(i);
		if ( button.decmd == "details") {
			if (this.HTML) {
				button.className = "disabled";
				button.state = 0;
			} else if (this.DHTMLSafe.ShowDetails) {
				button.className = "buttonDown";
				button.state = 2;
			} else if (button.state != 1) {
				button.className = "buttonUp";
				button.state = 1;
			}
		} else if (button.decmd == "html") {
			if (this.HTML) {
				button.className = "buttonDown";
				button.state = 2;
			} else if (button.state != 1) {
				button.className = "buttonUp";
				button.state = 1;
			}
		} else if (button.decmd == "specialcharacter") {
			if (this.HTML) {
				button.className = "disabled";
				button.state = 0;
			} else if (button.state != 1) {
				button.className = "buttonUp";
				button.state = 1;
			}
		} else if (button.decmd == DECMD_HYPERLINK) {
	   		if (this.HTML) {
				button.state = 0;
				button.className = "disabled";
			} else {
				button.state = 1;
				button.className = "buttonUp";
			}
		} else if (button.decmd < 6000) {
			var is_ie4 = ((parseInt(navigator.appVersion)  == 4) && (navigator.userAgent.toLowerCase().indexOf("msie 5")==-1) && (navigator.userAgent.toLowerCase().indexOf("msie 6")==-1) );
			if (!is_ie4) button.style.visibility = "visible";
			var state = this.DHTMLSafe.QueryStatus(button.decmd)
	   		if (this.HTML) {
				button.state = 0;
				button.className = "disabled";
	   		} else if ((state == DECMDF_DISABLED) || (state == DECMDF_NOTSUPPORTED)) {
				if (button.state != 0) {
					button.state = 0;
					button.className = "disabled";
				}
			} else if ((state == DECMDF_ENABLED) || (state == DECMDF_NINCHED)) {
				if (button.state != 1) {
					button.state = 1;
					button.className = "buttonUp";
				}
			} else {
				if (button.state != 2) {
					button.state = 2;
					button.className = "buttonDown";
				}
			}
		}
	}

	if (select = eval('document.all.'+this.editor+'_fontsize'+this.instance)) {
		if ((this.DHTMLSafe && !this.DHTMLSafe.Busy && this.DHTMLSafe.QueryStatus(DECMD_SETFONTSIZE) != DECMDF_DISABLED) && this.DHTMLSafe.DOM && this.DHTMLSafe.DOM.selection && (this.DHTMLSafe.DOM.selection.type != "control")) {
			if (select.disabled) {
				select.disabled = false;
			}
			var fontsize = this.DHTMLSafe.ExecCommand(DECMD_GETFONTSIZE);
			if ((fontsize != null) && (fontsize != "")) {
				for (i=0; i<select.options.length; i++) {
					if ((select.options[i].value == fontsize) && (! select.options[i].selected)) {
						select.options[i].selected=true;
					}
				}
			} else {
				select.selectedIndex=-1;
			}
		} else {
			if (! select.disabled) {
				select.disabled=true;
			}
		}
	}	

	if (select = eval('document.all.'+this.editor+'_fontstyle'+this.instance)){
		if ((this.DHTMLSafe && !this.DHTMLSafe.Busy && this.DHTMLSafe.QueryStatus(DECMD_SETBLOCKFMT) != DECMDF_DISABLED) && this.DHTMLSafe.DOM && this.DHTMLSafe.DOM.selection && (this.DHTMLSafe.DOM.selection.type != "control")) {
			if (select.disabled) {
				select.disabled = false;
			}
			var fontstyle = this.DHTMLSafe.ExecCommand(DECMD_GETBLOCKFMT);
			if ((fontstyle != "") && (fontstyle != null)) {
				for (i=0; i<select.options.length; i++) {
					if ((select.options[i].name == fontstyle) && (! select.options[i].selected)) {
						select.options[i].selected = true;
					}
				}
			} else {
				select.selectedIndex = -1;
			}
		} else {
			if (! select.disabled) {
				select.disabled=true;
			}
		}
	}	
}



function onButtonHTML() {
	this.DHTMLSafe.DOM.selection.empty();
	if (this.HTML) {
		this.DHTMLSafe.DOM.body.style.fontFamily = "";	
		this.DHTMLSafe.DOM.body.style.fontSize = "";
		this.ContentItem.value=this.DHTMLSafe.DOM.body.createTextRange().text;
		if (webeditor_custom_encode) this.ContentItem.value = webeditor_custom_encode(this.ContentItem.value);
		this.DHTMLSafe.DOM.body.innerHTML = this.ContentItem.value;	
		this.HTML = false;
	} else {
		var re=/((<br>)+)/ig;
		this.DHTMLSafe.DOM.body.style.fontFamily = "Courier New";
		this.DHTMLSafe.DOM.body.style.fontSize = "10pt";
		this.ContentItem.value=this.DHTMLSafe.DOM.body.innerHTML;
		if (webeditor_custom_decode) this.ContentItem.value = webeditor_custom_decode(this.ContentItem.value);
		this.DHTMLSafe.DOM.body.innerHTML = "";
		this.DHTMLSafe.DOM.body.createTextRange().text = this.ContentItem.value.replace(re, "$1\n");
		this.HTML = true;
	}
	this.DHTMLSafe.focus();
}



function onButtonHyperlink() {
	if (this.HTML) { return; }
	this.DHTMLSafe.focus();
	if (this.DHTMLSafe.DOM.selection.type == "Control") {
		var element = this.DHTMLSafe.DOM.selection.createRange().commonParentElement();
		var select = this.DHTMLSafe.DOM.body.createTextRange();
		select.moveToElementText(element);
		select.select();
	}

	var href = '';
	var target = '';
	var htmlid = '';
	var htmlclass = '';
	var onclick = '';
	var text = this.DHTMLSafe.DOM.selection.createRange().text;
	var select = this.DHTMLSafe.DOM.selection.createRange();
	var tags = this.DHTMLSafe.DOM.all.tags("A");
	for (i=0; i<tags.length; i++) {
		var element = this.DHTMLSafe.DOM.body.createTextRange();
		element.moveToElementText(tags[i]);
		if ((select.compareEndPoints("EndToStart",element) == 1) && (select.compareEndPoints("StartToEnd",element) == -1)) {
			if (select.compareEndPoints("StartToStart",element) == 1) {
				select.setEndPoint("StartToStart",element);
			}
			if (select.compareEndPoints("EndToEnd",element) == -1) {
				select.setEndPoint("EndToEnd",element);
			}
			select.select();
			text = select.text;
			if (tags[i].href) {
				href = tags[i].href;
			}
			if (tags[i].target) {
				target = tags[i].target;
			}
			htmlid = htmlTagAttribute(tags[i], "id");
			htmlclass = htmlTagAttribute(tags[i], "class");
			onclick = htmlTagAttribute(tags[i], "onclick");
		}
	}

	if ((typeof(this.hyperlink_window) == "undefined") || this.hyperlink_window.closed) {
		if (this.manager && this.language) {
			this.hyperlink_window = window.open(this.rootpath+"hyperlink"+this.manager+"."+this.language+"?editor="+this.editor+"_editor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text) ,"hyperlink_window","scrollbars=yes,width=750,height=450,resizable=yes,status=yes",true);
		} else {
			this.hyperlink_window = window.open(this.rootpath+"hyperlink"+this.manager+".html?editor="+this.editor+"_editor&href="+escape(href)+"&target="+escape(target)+"&htmlid="+escape(htmlid)+"&htmlclass="+escape(htmlclass)+"&onclick="+escape(onclick)+"&text="+escape(text) ,"hyperlink_window","scrollbars=yes,width=750,height=450,resizable=yes,status=yes",true);
		}
	}
	//this.hyperlink_window.focus();
}



function onButtonImage() {
	if (this.HTML) { return; }
	this.DHTMLSafe.focus();
	if (this.DHTMLSafe.DOM.selection.type == "Control") {
		var element = this.DHTMLSafe.DOM.selection.createRange().commonParentElement();
		var select = this.DHTMLSafe.DOM.body.createTextRange();
		select.moveToElementText(element);
		select.select();
	}

	var url = '';
	var href = '';
	var border = '';
	var alt = '';
	var width = '';
	var height = '';
	var vspace = '';
	var hspace = '';
	var align = '';
	var htmlclass = '';
	var htmlid = '';
	var select = this.DHTMLSafe.DOM.selection.createRange();
	var tags = this.DHTMLSafe.DOM.all.tags("IMG");
	for(i=0; i<tags.length; i++) {
		var element = this.DHTMLSafe.DOM.body.createTextRange();
		element.moveToElementText(tags[i]);
		if ((select.compareEndPoints("EndToStart",element) == 1) && (select.compareEndPoints("StartToEnd",element) == -1)) {
			if (select.compareEndPoints("StartToStart",element) == 1) {
				select.setEndPoint("StartToStart",element);
			}
			if (select.compareEndPoints("EndToEnd",element) == -1) {
				select.setEndPoint("EndToEnd",element);
			}
			select.select();

			if (tags[i].src) {
				url = tags[i].src;
			}
			if (tags[i].border) {
				border = tags[i].border;
			}
			if (tags[i].alt) {
				alt = tags[i].alt;
			}
			href = htmlTagAttribute(tags[i], "src");
			width = htmlTagAttribute(tags[i], "width");
			height = htmlTagAttribute(tags[i], "height");
			vspace = htmlTagAttribute(tags[i], "vspace");
			hspace = htmlTagAttribute(tags[i], "hspace");
			align = htmlTagAttribute(tags[i], "align");
			htmlclass = htmlTagAttribute(tags[i], "class");
			htmlid = htmlTagAttribute(tags[i], "id");
		}
	}

	if ((typeof(this.image_window) == "undefined") || this.image_window.closed) {
		if (this.manager && this.language) {
			this.image_window = window.open(this.rootpath+"media"+this.manager+"."+this.language+"?editor="+this.editor+"_editor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid),"image_window","scrollbars=yes,width=750,height=450,resizable=yes,status=yes");
		} else {
			this.image_window = window.open(this.rootpath+"media"+this.manager+".html?editor="+this.editor+"_editor&href="+escape(href)+"&border="+escape(border)+"&alt="+escape(alt)+"&width="+escape(width)+"&height="+escape(height)+"&vspace="+escape(vspace)+"&hspace="+escape(hspace)+"&align="+escape(align)+"&htmlclass="+escape(htmlclass)+"&htmlid="+escape(htmlid),"image_window","scrollbars=yes,width=750,height=450,resizable=yes,status=yes");
		}
	}
	//this.image_window.focus();
}



function htmlTagAttribute(tag, name) {
	var value = '';
	if (tag['outerHTML'].toLowerCase().indexOf(" "+name+"=") >= 0) {
		value = tag['outerHTML'].substring(tag['outerHTML'].toLowerCase().indexOf(" "+name+"=")+name.length+2);
		if ((value.charAt(0) == '"') && value.indexOf('"', 1)) {
			value = value.substring(1, value.indexOf('"', 1));
		} else if (value.indexOf(' ') >= 0) {
			value = value.substring(0, value.indexOf(' '));
		} else if (value.indexOf('/')) {
			value = value.substring(0, value.indexOf('/'));
		} else if (value.indexOf('>')) {
			value = value.substring(0, value.indexOf('>'));
		}
	}
	return value;
}



function onButtonSpecialCharacter() {
	if (this.HTML) { return; }
	if ((typeof(this.specialcharacter_window) == "undefined") || this.specialcharacter_window.closed) {
		this.specialcharacter_window = window.open(this.rootpath+"specialcharacter.html?editor="+this.editor+"_editor", "specialcharacter_window", "scrollbars=yes,width=500,height=425,resizable=yes,status=yes,scrollbars=yes", true);
	}
	//this.specialcharacter_window.focus();
}



function onButtonTable() {
	if (this.HTML) { return; }
	if ((typeof(this.table_window) == "undefined") || this.table_window.closed) {
		this.table_window = window.open(this.rootpath+"table.html?editor="+this.editor+"_editor", "table_window", "scrollbars=yes,width=475,height=375,resizable=yes,status=yes", true);
	}
	//this.table_window.focus();
}



function isTable() {
	var table;
	if (table = this.isRow()) {
		while ((table.tagName != "TABLE") && (table.tagName != "HTML")) {
			table = table.parentElement;
		}
		if (table.tagName == "HTML") {
			return false;
		} else {
			return table;
		}
	}
}



function isRow() {
	var row;
	if (row = this.isCell()) {
		while ((row.tagName != "TR") && (row.tagName != "HTML")) {
			row = row.parentElement;
		}
		if (row.tagName == "HTML") {
			return false;
		} else {
			return row;
		}
	}
}



function isCell() {
	var selected = this.DHTMLSafe.DOM.selection.createRange();
	selected.collapse();
	selected.select();
	
	var cell = this.DHTMLSafe.DOM.selection.createRange().parentElement();
	while ((cell.tagName != "TD") && (cell.tagName != "HTML")) {
		cell = cell.parentElement;
	}

	if (cell.tagName == "HTML") {
		return false;
	} else {
		return cell;
	}
}



function onButtonTableProperties() {
	if (this.HTML) { return; }
	var table;
	if (table = this.isTable()) {
		if ((typeof(this.table_window) == "undefined") || this.table_window.closed) {
			this.table_window = window.open(this.rootpath+"tableproperties.html?editor="+this.editor+"_editor&border="+escape(table.getAttribute("BORDER"))+"&width="+escape(table.getAttribute("WIDTH"))+"&height="+escape(table.getAttribute("HEIGHT"))+"&cellpadding="+escape(table.getAttribute("CELLPADDING"))+"&cellspacing="+escape(table.getAttribute("CELLSPACING"))+"&bgcolor="+escape(table.getAttribute("BGCOLOR"))+"&bordercolor="+escape(table.getAttribute("BORDERCOLOR"))+"&background="+escape(table.getAttribute("BACKGROUND")), "table_window", "scrollbars=yes,width=475,height=375,resizable=yes,status=yes", true);
		}
		//this.table_window.focus();
	}
}



function onButtonRowProperties() {
	if (this.HTML) { return; }
	var row;
	if (row = this.isRow()) {
		if ((typeof(this.table_window) == "undefined") || this.table_window.closed) {
			this.table_window = window.open(this.rootpath+"rowproperties.html?editor="+this.editor+"_editor&align="+escape(row.getAttribute("ALIGN"))+"&valign="+escape(row.getAttribute("VALIGN"))+"&bgcolor="+escape(row.getAttribute("BGCOLOR"))+"&bordercolor="+escape(row.getAttribute("BORDERCOLOR"))+"&background="+escape(row.getAttribute("BACKGROUND")), "table_window", "scrollbars=yes,width=475,height=350,resizable=yes,status=yes", true);
		}
		//this.table_window.focus();
	}
}



function onButtonCellProperties() {
	if (this.HTML) { return; }
	var cell;
	if (cell = this.isCell()) {
		if ((typeof(this.table_window) == "undefined") || this.table_window.closed) {
			this.table_window = window.open(this.rootpath+"cellproperties.html?editor="+this.editor+"_editor&width="+escape(cell.getAttribute("WIDTH"))+"&height="+escape(cell.getAttribute("HEIGHT"))+"&colspan="+escape(cell.getAttribute("COLSPAN"))+"&rowspan="+escape(cell.getAttribute("ROWSPAN"))+"&align="+escape(cell.getAttribute("ALIGN"))+"&valign="+escape(cell.getAttribute("VALIGN"))+"&bgcolor="+escape(cell.getAttribute("BGCOLOR"))+"&bordercolor="+escape(cell.getAttribute("BORDERCOLOR"))+"&background="+escape(cell.getAttribute("BACKGROUND")), "table_window", "scrollbars=yes,width=475,height=450,resizable=yes,status=yes", true);
		}
		//this.table_window.focus();
	}
}



function onButtonDetails() {
	if (this.HTML) { return; }
	this.DHTMLSafe.ShowDetails = ! this.DHTMLSafe.ShowDetails;
}



function insertImage(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid) {
	if (this.HTML) { return; }
	//this.DHTMLSafe.ExecCommand(DECMD_IMAGE,OLECMDEXECOPT_DONTPROMPTUSER,u);
	var img = '<img src="' + src + '"';
	if (border) { img = img + ' border="' + border + '"'; }
	if (alt) { img = img + ' alt="' + alt + '"'; }
	if (width) { img = img + ' width="' + width + '"'; }
	if (height) { img = img + ' height="' + height + '"'; }
	if (vspace) { img = img + ' vspace="' + vspace + '"'; }
	if (hspace) { img = img + ' hspace="' + hspace + '"'; }
	if (align) { img = img + ' align="' + align + '"'; }
	if (htmlclass) { img = img + ' class="' + htmlclass + '"'; }
	if (htmlid) { img = img + ' id="' + htmlid + '"'; }
	img = img + '>';
	this.DHTMLSafe.DOM.selection.createRange().pasteHTML(img);
	this.DHTMLSafe.focus();
}



function onButtonHelp() {
	if ((typeof(this.help_window) == "undefined") || this.help_window.closed) {
		this.help_window = window.open(this.rootpath+"help.html?editor="+this.editor+"_editor", "help_window", "width=600,height=435,scrollbars=yes,resizable=yes,status=yes", true);
	}
}



function insertHyperlink(href, target, text, htmlclass, htmlid, onclick) {
	if (this.HTML) { return; }
	if (this.DHTMLSafe.DOM.selection.createRange().text == this.DHTMLSafe.DOM.selection.createRange().htmlText) {
		this.DHTMLSafe.DOM.selection.createRange().text = text;
	}
	if (href == "") {
		if (this.DHTMLSafe.QueryStatus(DECMD_UNLINK) == DECMDF_ENABLED) {
			this.DHTMLSafe.ExecCommand(DECMD_UNLINK);
		}
	} else {
		var selected = this.DHTMLSafe.DOM.selection.createRange();
		if (selected.compareEndPoints("StartToEnd",selected) == 0) { // new link
			if (text == "") {
				text = href;
			}
			var html = '<A href="' + href +'"';
			if (target.length) {
				html += ' target="' + target + '"';
			}
			if (htmlclass.length) {
				html += ' class="' + htmlclass + '"';
			}
			if (htmlid.length) {
				html += ' id="' + htmlid + '"';
			}
			if (onclick.length) {
				html += ' onclick="' + onclick + '"';
			}
			html += '>' + text + '</a>';
			selected.pasteHTML(html);
		} else { // update link
			var id = Math.random().toString();
			this.DHTMLSafe.ExecCommand(DECMD_HYPERLINK,OLECMDEXECOPT_DONTPROMPTUSER,id);
			var tags = this.DHTMLSafe.DOM.all.tags("A");
			for (i=0; i<tags.length; i++) {
				if (tags[i].href == id) {
					tags[i].href = href;
					if (target && target.length) {
						tags[i].target = target;
					} else {
						tags[i].removeAttribute("target",0);
					}
					if (htmlclass && htmlclass.length) {
						tags[i].className = htmlclass;
					} else {
						tags[i].removeAttribute("class",0);
					}
					if (htmlid && htmlid.length) {
						tags[i].id = htmlid;
					} else {
						tags[i].removeAttribute("id",0);
					}
					if (onclick && onclick.length) {
						tags[i].onclick = onclick;
					} else {
						tags[i].removeAttribute("onclick",0);
					}
				}
			}
		}
	}
	this.DHTMLSafe.focus();
}



function insertTable(rows, cols, border, width, height, cellpadding, cellspacing, background, bgcolor, bordercolor) {
	if (this.HTML) { return; }
	var attributes = "";
	if (border != "") attributes += ' border="'+border+'"';
	if (width != "") attributes += ' width="'+width+'"';
	if (height != "") attributes += ' height="'+height+'"';
	if (cellpadding != "") attributes += ' cellpadding="'+cellpadding+'"';
	if (cellspacing != "") attributes += ' cellspacing="'+cellspacing+'"';
	if (background != "") attributes += ' background="'+background+'"';
	if (bgcolor != "") attributes += ' bgcolor="'+bgcolor+'"';
	if (bordercolor != "") attributes += ' bordercolor="'+bordercolor+'"';
	var table = new ActiveXObject("DEInsertTableParam.DEInsertTableParam");
	table.NumRows = rows;
	table.NumCols = cols;
	table.Caption = "";
	table.TableAttrs = attributes;
	table.CellAttrs = "";
	this.DHTMLSafe.ExecCommand(DECMD_INSERTTABLE,OLECMDEXECOPT_DODEFAULT, table);
	this.DHTMLSafe.focus();
}



function updateTable(form) {
	if (this.HTML) { return; }
	var table;
	if (table = this.isTable()) {
		if (table.getAttribute("WIDTH") != form.width.value) {
			if (form.width.value == "") {
				table.removeAttribute("WIDTH", 0);
			} else {
				table.setAttribute("WIDTH", form.width.value, 0);
			}
		}
		if (table.getAttribute("HEIGHT") != form.height.value) {
			if (form.height.value == "") {
				table.removeAttribute("HEIGHT", 0);
			} else {
				table.setAttribute("HEIGHT", form.height.value, 0);
			}
		}
		if (table.getAttribute("CELLPADDING") != form.cellpadding.value) {
			if (form.cellpadding.value == "") {
				table.removeAttribute("CELLPADDING", 0);
			} else {
				table.setAttribute("CELLPADDING", form.cellpadding.value, 0);
			}
		}
		if (table.getAttribute("CELLSPACING") != form.cellspacing.value) {
			if (form.cellspacing.value == "") {
				table.removeAttribute("CELLSPACING", 0);
			} else {
				table.setAttribute("CELLSPACING", form.cellspacing.value, 0);
			}
		}
		if (table.getAttribute("BORDER") != form.border.value) {
			if (form.border.value == "") {
				table.removeAttribute("BORDER", 0);
			} else {
				table.setAttribute("BORDER", form.border.value, 0);
			}
		}
		if (table.getAttribute("BGCOLOR") != form.bgcolor.value) {
			if (form.bgcolor.value == "") {
				table.removeAttribute("BGCOLOR", 0);
			} else {
				table.setAttribute("BGCOLOR", form.bgcolor.value, 0);
			}
		}
		if (table.getAttribute("BORDERCOLOR") != form.bordercolor.value) {
			if (form.bordercolor.value == "") {
				table.removeAttribute("BORDERCOLOR", 0);
			} else {
				table.setAttribute("BORDERCOLOR", form.bordercolor.value, 0);
			}
		}
		if (table.getAttribute("BACKGROUND") != form.background.value) {
			if (form.background.value == "") {
				table.removeAttribute("BACKGROUND", 0);
			} else {
				table.setAttribute("BACKGROUND", form.background.value, 0);
			}
		}
	}
	this.DHTMLSafe.focus();
}



function updateRow(form) {
	if (this.HTML) { return; }
	var row;
	if (row = this.isRow()) {
		if (row.getAttribute("ALIGN") != form.align.value) {
			if (form.align.value == "") {
				row.removeAttribute("ALIGN", 0);
			} else {
				row.setAttribute("ALIGN", form.align.value, 0);
			}
		}
		if (row.getAttribute("VALIGN") != form.valign.value) {
			if (form.valign.value == "") {
				row.removeAttribute("VALIGN", 0);
			} else {
				row.setAttribute("VALIGN", form.valign.value, 0);
			}
		}
		if (row.getAttribute("BGCOLOR") != form.bgcolor.value) {
			if (form.bgcolor.value == "") {
				row.removeAttribute("BGCOLOR", 0);
			} else {
				row.setAttribute("BGCOLOR", form.bgcolor.value, 0);
			}
		}
		if (row.getAttribute("BORDERCOLOR") != form.bordercolor.value) {
			if (form.bordercolor.value == "") {
				row.removeAttribute("BORDERCOLOR", 0);
			} else {
				row.setAttribute("BORDERCOLOR", form.bordercolor.value, 0);
			}
		}
		if (row.getAttribute("BACKGROUND") != form.background.value) {
			if (form.background.value == "") {
				row.removeAttribute("BACKGROUND", 0);
			} else {
				row.setAttribute("BACKGROUND", form.background.value, 0);
			}
		}
	}
	// dummy assignment to trick DHTML component to refresh
	this.DHTMLSafe.DOM.body.innerHTML = "" + this.DHTMLSafe.DOM.body.innerHTML;	
	this.DHTMLSafe.focus();
}



function updateCell(form) {
	if (this.HTML) { return; }
	var cell;
	if (cell = this.isCell()) {
		if (cell.getAttribute("WIDTH") != form.width.value) {
			if (form.width.value == "") {
				cell.removeAttribute("WIDTH", 0);
			} else {
				cell.setAttribute("WIDTH", form.width.value, 0);
			}
		}
		if (cell.getAttribute("HEIGHT") != form.height.value) {
			if (form.height.value == "") {
				cell.removeAttribute("HEIGHT", 0);
			} else {
				cell.setAttribute("HEIGHT", form.height.value, 0);
			}
		}
		if (cell.getAttribute("COLSPAN") != form.colspan.value) {
			if (form.colspan.value == "") {
				cell.removeAttribute("COLSPAN", 0);
			} else {
				cell.setAttribute("COLSPAN", form.colspan.value, 0);
			}
		}
		if (cell.getAttribute("ROWSPAN") != form.rowspan.value) {
			if (form.rowspan.value == "") {
				cell.removeAttribute("ROWSPAN", 0);
			} else {
				cell.setAttribute("ROWSPAN", form.rowspan.value, 0);
			}
		}
		if (cell.getAttribute("ALIGN") != form.align.value) {
			if (form.align.value == "") {
				cell.removeAttribute("ALIGN", 0);
			} else {
				cell.setAttribute("ALIGN", form.align.value, 0);
			}
		}
		if (cell.getAttribute("VALIGN") != form.valign.value) {
			if (form.valign.value == "") {
				cell.removeAttribute("VALIGN", 0);
			} else {
				cell.setAttribute("VALIGN", form.valign.value, 0);
			}
		}
		if (cell.getAttribute("BGCOLOR") != form.bgcolor.value) {
			if (form.bgcolor.value == "") {
				cell.removeAttribute("BGCOLOR", 0);
			} else {
				cell.setAttribute("BGCOLOR", form.bgcolor.value, 0);
			}
		}
		if (cell.getAttribute("BORDERCOLOR") != form.bordercolor.value) {
			if (form.bordercolor.value == "") {
				cell.removeAttribute("BORDERCOLOR", 0);
			} else {
				cell.setAttribute("BORDERCOLOR", form.bordercolor.value, 0);
			}
		}
		if (cell.getAttribute("BACKGROUND") != form.background.value) {
			if (form.background.value == "") {
				cell.removeAttribute("BACKGROUND", 0);
			} else {
				cell.setAttribute("BACKGROUND", form.background.value, 0);
			}
		}
	}
	this.DHTMLSafe.focus();
}



function insertSpecialCharacter(htmlcode) {
	if (this.HTML) { return; }
	this.DHTMLSafe.DOM.selection.createRange().pasteHTML(htmlcode);
	this.DHTMLSafe.focus();
}



function ContextMenuItem(string, action) {
	this.string = string;
	this.action = action;
}



function showContextMenu() {
	var i = 0;
	this.ContextMenu = new Array();
	this.ContextMenu[i++] = new ContextMenuItem("About", "about");

	if (this.DHTMLSafe.QueryStatus(DECMD_INSERTROW) != DECMDF_DISABLED) {
		this.ContextMenu[i++] = new ContextMenuItem("", 0);
		this.ContextMenu[i++] = new ContextMenuItem("Insert Row", DECMD_INSERTROW);
		this.ContextMenu[i++] = new ContextMenuItem("Delete Row(s)", DECMD_DELETEROWS);
		this.ContextMenu[i++] = new ContextMenuItem("", 0);
		this.ContextMenu[i++] = new ContextMenuItem("Insert Column", DECMD_INSERTCOL);
		this.ContextMenu[i++] = new ContextMenuItem("Delete Column(s)", DECMD_DELETECOLS);
		this.ContextMenu[i++] = new ContextMenuItem("", 0);
		this.ContextMenu[i++] = new ContextMenuItem("Insert Cell", DECMD_INSERTCELL);
		this.ContextMenu[i++] = new ContextMenuItem("Delete Cell(s)", DECMD_DELETECELLS);
		this.ContextMenu[i++] = new ContextMenuItem("", 0);
		this.ContextMenu[i++] = new ContextMenuItem("Merge Cells", DECMD_MERGECELLS);
		this.ContextMenu[i++] = new ContextMenuItem("Split Cell", DECMD_SPLITCELL);
		this.ContextMenu[i++] = new ContextMenuItem("", 0);
		this.ContextMenu[i++] = new ContextMenuItem("Table Properties", "tableproperties");
		this.ContextMenu[i++] = new ContextMenuItem("Row Properties", "rowproperties");
		this.ContextMenu[i++] = new ContextMenuItem("Cell Properties", "cellproperties");
	}

	var state;
	var ContextMenuItems = new Array();
	var ContextMenuStates = new Array();
	for (i=0; i<this.ContextMenu.length; i++) {
		ContextMenuItems[i] = this.ContextMenu[i].string;

		if ((ContextMenuItems[i] != "") && (this.ContextMenu[i].action < 6000)) {
			state = this.DHTMLSafe.QueryStatus(this.ContextMenu[i].action);
		} else {
			state = DECMDF_ENABLED;
		}

		if ((state == DECMDF_DISABLED) || (state == DECMDF_NOTSUPPORTED)) {
			ContextMenuStates[i] = OLE_TRISTATE_GRAY;
		} else if ((state == DECMDF_ENABLED) || (state == DECMDF_NINCHED)) {
			ContextMenuStates[i] = OLE_TRISTATE_UNCHECKED;
		} else { // DECMDF_LATCHED
			ContextMenuStates[i] = OLE_TRISTATE_CHECKED;
		}
	}
	this.DHTMLSafe.SetContextMenu(ContextMenuItems, ContextMenuStates);
}



function contextMenuAction(action) {
	if (this.ContextMenu[action].action == "about") {
		alert("HardCore Web Content Editor"+"\n"+"(C) 2002-2004 - HardCore Internet Ltd."+"\n"+"www.hardcoreinternet.co.uk");
	} else {
		this.onButtonClick(this.ContextMenu[action].action);
	}
}



function create(value) {
	document.writeln('<style type="text/css">');
	document.writeln('.buttonUp	{width:23px;height:22px;vertical-align:middle;border:1px solid;border-color:buttonface;}');
	document.writeln('.buttonMouseOver{width:23px;height:22px;vertical-align:middle;border:1px solid;border-top-color:buttonhighlight;border-left-color:buttonhighlight;border-right-color:buttonshadow;border-bottom-color:buttonshadow;}');
	document.writeln('.buttonClick	{width:23px;height:22px;vertical-align:middle;border:1px;background-color:#eeeeee;}');
	document.writeln('.buttonDown	{width:25px;height:22px;vertical-align:middle;border:1px;background-color:#eeeeee;}');
	document.writeln('.disabled	{width:25px;height:22px;vertical-align:middle;border:1px;filter:mask() mask(color=buttonshadow) dropshadow(offX=1,offY=1,color=buttonhighlight,positive=1);}');
	document.writeln('.space		{margin:0;padding:0;vertical-align:middle;}');
	document.writeln('</style>');
	
// You are not allowed in any way to remove or hide the copyright notice unless you obtain a valid commercial license for the HardCore Web Content Editor from HardCore Internet Ltd. - www.hardcoreinternet.co.uk
// The copyright notice must remain visible and unmodified at all times unless you obtain a valid commercial license for the HardCore Web Content Editor from HardCore Internet Ltd. - www.hardcoreinternet.co.uk
//	document.writeln('<div align="left"><font size="-1">HardCore Web Content Editor - (C) 2002-2004 - HardCore Internet Ltd. - <a href="http://www.hardcoreinternet.co.uk" target="_blank">www.hardcoreinternet.co.uk</a></font></div>');
	document.write('<table bgcolor="buttonface" cellpadding="0" cellspacing="0" width="600" height="400" border="1"	ondragstart="window.event.returnValue=false;" onselectstart="window.event.returnValue=false;">');
	document.write(' <tr>');
	document.write('  <td valign="bottom">');
	document.write('   <table cellspacing=2 cellpadding=0 border=0>');
	document.write('    <tr valign="middle" align="left">');
	document.write('     <td width=10 background="'+this.rootpath+'toolbar.gif"><img src="'+this.rootpath+'spacer.gif" width=9 height=22 alt=""></td>');
	document.write('     <td>');
	document.write('      <span id="'+this.editor+'_toolbar'+this.instance+'"');
	document.write('	onmouseover="'+this.editor+'_editor.onButtonMouseOver(window.event.srcElement);"');
	document.write('	onmousedown="'+this.editor+'_editor.onButtonMouseDown(window.event.srcElement);"');
	document.write('	onmouseup="'+this.editor+'_editor.onButtonMouseUp(window.event.srcElement);"');
	document.write('	onmouseout="'+this.editor+'_editor.onButtonMouseOut(window.event.srcElement);"');
	document.write('	ondragstart="window.event.returnValue=false;"');
	document.write('	onselectstart="window.event.returnValue=false;"');
	document.write('      >');
	document.write('      <nobr>');
	document.write('      <img src="'+this.rootpath+'spacer.gif" width="1" style="vertical-align:middle;">');
	document.write('      <select size="1" id="'+this.editor+'_fontstyle'+this.instance+'" style="width:120px;font:8pt Verdana;vertical-align:middle;" onchange="'+this.editor+'_editor.onFontFormat();"></select>');
	document.write('      <img src="'+this.rootpath+'spacer.gif" width="1" style="vertical-align:middle;">');
	document.write('      <select size="1" id="'+this.editor+'_fontsize'+this.instance+'" style="width:40px;font:8pt Verdana;vertical-align:middle;" onchange="'+this.editor+'_editor.onFontSize();">');
	document.write('	<option value=1>8');
	document.write('	<option value=2>10');
	document.write('	<option value=3 selected>12');
	document.write('	<option value=4>14');
	document.write('	<option value=5>18');
	document.write('	<option value=6>24');
	document.write('	<option value=7>36');
	document.write('      </select>');
	document.write('      <img src="'+this.rootpath+'spacer.gif" width="1" height=22 alt="">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_bold')+'" onclick="'+this.editor+'_editor.onButtonClick(5000);" src="'+this.rootpath+'bold.gif" type="button" class="buttonUp" decmd="5000">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_italic')+'" onclick="'+this.editor+'_editor.onButtonClick(5023);" src="'+this.rootpath+'italic.gif" type="button" class="buttonUp" decmd="5023">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_underline')+'" onclick="'+this.editor+'_editor.onButtonClick(5048);" src="'+this.rootpath+'underline.gif" type="button" class="buttonUp" decmd="5048">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_bullets')+'" onclick="'+this.editor+'_editor.onButtonClick(5051);" src="'+this.rootpath+'list.gif" type="button" class="buttonUp" decmd="5051">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_decreaseindent')+'" onclick="'+this.editor+'_editor.onButtonClick(5031);" src="'+this.rootpath+'indent-.gif" type="button" class="buttonUp" decmd="5031">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_indent')+'" onclick="'+this.editor+'_editor.onButtonClick(5018);" src="'+this.rootpath+'indent+.gif" type="button" class="buttonUp" decmd="5018">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_justifyleft')+'" onclick="'+this.editor+'_editor.onButtonClick(5025);" src="'+this.rootpath+'left.gif" type="button" class="buttonUp" decmd="5025">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_justifycenter')+'" onclick="'+this.editor+'_editor.onButtonClick(5024);" src="'+this.rootpath+'center.gif" type="button" class="buttonUp" decmd="5024">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_justifyright')+'" onclick="'+this.editor+'_editor.onButtonClick(5026);" src="'+this.rootpath+'right.gif" type="button" class="buttonUp" decmd="5026">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_font')+'" onclick="'+this.editor+'_editor.onButtonClick(5009);" src="'+this.rootpath+'fontcolour.gif" type="button" class="buttonUp" decmd="5009">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_cut')+'" onclick="'+this.editor+'_editor.onButtonClick(5003);" src="'+this.rootpath+'cut.gif" type="button" class="buttonUp" decmd="5003">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_copy')+'" onclick="'+this.editor+'_editor.onButtonClick(5002);" src="'+this.rootpath+'copy.gif" type="button" class="buttonUp" decmd="5002">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_paste')+'" onclick="'+this.editor+'_editor.onButtonClick(5032);" src="'+this.rootpath+'paste.gif" type="button" class="buttonUp" decmd="5032">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_redo')+'" onclick="'+this.editor+'_editor.onButtonClick(5033);" src="'+this.rootpath+'redo.gif" type="button" class="buttonUp" decmd="5033">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_undo')+'" onclick="'+this.editor+'_editor.onButtonClick(5049);" src="'+this.rootpath+'undo.gif" type="button" class="buttonUp" decmd="5049">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_insertimage')+'" onclick="'+this.editor+'_editor.onButtonImage();" src="'+this.rootpath+'image.gif" type="button" class="buttonUp" decmd="5017">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_createlink')+'" onclick="'+this.editor+'_editor.onButtonHyperlink();" src="'+this.rootpath+'link.gif" type="button" class="buttonUp" decmd="5016">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_createtable')+'" onclick="'+this.editor+'_editor.onButtonTable();" src="'+this.rootpath+'table.gif" type="button" class="buttonUp" decmd="5022">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_specialcharacter')+'" onclick="'+this.editor+'_editor.onButtonSpecialCharacter();" src="'+this.rootpath+'specialcharacter.gif" type="button" class="buttonUp" decmd="specialcharacter">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_find')+'" onclick="'+this.editor+'_editor.onButtonClick(5008);" src="'+this.rootpath+'find.gif" type="button" class="buttonUp">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_help')+'" onclick="'+this.editor+'_editor.onButtonHelp();" src="'+this.rootpath+'help.gif" type="button" class="buttonUp">');
	document.write('      <img src="'+this.rootpath+'space.gif" class="space">');
	document.write('      <wbr>');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_showdetails')+'" onclick="'+this.editor+'_editor.onButtonDetails();" src="'+this.rootpath+'paragraph.gif" type="button" class="buttonUp" decmd="details">');
	document.write('      <img width="23" height="22" alt="'+Text('toolbar_viewsource')+'" onclick="'+this.editor+'_editor.onButtonHTML();" src="'+this.rootpath+'details.gif" type="button" class="buttonUp" decmd="html">');
	document.write('      </nobr>');
	document.write('      <br>');
	document.write('      </span>');
	document.write('     </td>');
	document.write('    </tr>');
	document.write('   </table>');
	document.write('  </td>');
	document.write(' </tr>');
	document.write(' <tr>');
	document.write('  <td width="100%" height="100%">');
	document.write('   <object style="z-index:1;" classid="clsid:2D360201-FFF5-11d1-8D03-00A0C959BC0A" width="100%" height="100%" id="DHTMLSafe_'+this.editor+this.instance+'" >');
	document.write('    <param name=ScrollbarAppearance value=0>');
	document.write('   </object>');
	document.write('   <script for="DHTMLSafe_'+this.editor+this.instance+'" event="onclick">return '+this.editor+'_editor.refreshToolbar();</script>');
	document.write('   <script for="DHTMLSafe_'+this.editor+this.instance+'" event="onkeypress">return '+this.editor+'_editor.onKeyPress();</script>');
	document.write('   <script for="DHTMLSafe_'+this.editor+this.instance+'" event="DisplayChanged">return '+this.editor+'_editor.refreshToolbar();</script>');
	document.write('   <script for="DHTMLSafe_'+this.editor+this.instance+'" event="ShowContextMenu">return '+this.editor+'_editor.showContextMenu();</script>');
	document.write('   <script for="DHTMLSafe_'+this.editor+this.instance+'" event="ContextMenuAction(action)">return '+this.editor+'_editor.contextMenuAction(action);</script>');
	document.write('  </td>');
	document.write(' </tr>');
	document.writeln('</table>');
	
	document.write('<textarea style="position:absolute; visibility:hidden;" id="'+this.editor+this.instance+'" name="'+this.editor+'">');
	document.write(value);
	document.writeln('</textarea>');
}



// DHTML Editing Component Constants for JavaScript
// Copyright 1998 Microsoft Corporation.  All rights reserved.
//

//
// Command IDs
//

DECMD_BOLD =                      5000;
DECMD_COPY =                      5002;
DECMD_CUT =                       5003;
DECMD_DELETE =                    5004;
DECMD_DELETECELLS =               5005;
DECMD_DELETECOLS =                5006;
DECMD_DELETEROWS =                5007;
DECMD_FINDTEXT =                  5008;
DECMD_FONT =                      5009;
DECMD_GETBACKCOLOR =              5010;
DECMD_GETBLOCKFMT =               5011;
DECMD_GETBLOCKFMTNAMES =          5012;
DECMD_GETFONTNAME =               5013;
DECMD_GETFONTSIZE =               5014;
DECMD_GETFORECOLOR =              5015;
DECMD_HYPERLINK =                 5016;
DECMD_IMAGE =                     5017;
DECMD_INDENT =                    5018;
DECMD_INSERTCELL =                5019;
DECMD_INSERTCOL =                 5020;
DECMD_INSERTROW =                 5021;
DECMD_INSERTTABLE =               5022;
DECMD_ITALIC =                    5023;
DECMD_JUSTIFYCENTER =             5024;
DECMD_JUSTIFYLEFT =               5025;
DECMD_JUSTIFYRIGHT =              5026;
DECMD_LOCK_ELEMENT =              5027;
DECMD_MAKE_ABSOLUTE =             5028;
DECMD_MERGECELLS =                5029;
DECMD_ORDERLIST =                 5030;
DECMD_OUTDENT =                   5031;
DECMD_PASTE =                     5032;
DECMD_REDO =                      5033;
DECMD_REMOVEFORMAT =              5034;
DECMD_SELECTALL =                 5035;
DECMD_SEND_BACKWARD =             5036;
DECMD_BRING_FORWARD =             5037;
DECMD_SEND_BELOW_TEXT =           5038;
DECMD_BRING_ABOVE_TEXT =          5039;
DECMD_SEND_TO_BACK =              5040;
DECMD_BRING_TO_FRONT =            5041;
DECMD_SETBACKCOLOR =              5042;
DECMD_SETBLOCKFMT =               5043;
DECMD_SETFONTNAME =               5044;
DECMD_SETFONTSIZE =               5045;
DECMD_SETFORECOLOR =              5046;
DECMD_SPLITCELL =                 5047;
DECMD_UNDERLINE =                 5048;
DECMD_UNDO =                      5049;
DECMD_UNLINK =                    5050;
DECMD_UNORDERLIST =               5051;
DECMD_PROPERTIES =                5052;

//
// Enums
//

// OLECMDEXECOPT  
OLECMDEXECOPT_DODEFAULT =         0;
OLECMDEXECOPT_PROMPTUSER =        1;
OLECMDEXECOPT_DONTPROMPTUSER =    2;

// DHTMLEDITCMDF
DECMDF_NOTSUPPORTED =             0;
DECMDF_DISABLED =                 1;
DECMDF_ENABLED =                  3;
DECMDF_LATCHED =                  7;
DECMDF_NINCHED =                  11;

// DHTMLEDITAPPEARANCE
DEAPPEARANCE_FLAT =               0;
DEAPPEARANCE_3D =                 1;

// OLE_TRISTATE
OLE_TRISTATE_UNCHECKED =          0;
OLE_TRISTATE_CHECKED =            1;
OLE_TRISTATE_GRAY =               2;
