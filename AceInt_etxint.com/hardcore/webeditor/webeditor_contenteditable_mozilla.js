// HardCore Web Content Editor
// Copyright 2002-2004 HardCore Internet Ltd.
// www.hardcoreinternet.co.uk

function webeditor_select_init(node) {
	node.ondeactivate = webeditor_select_deactivate;
	node.onblur = webeditor_select_blur;
	node.onfocus = webeditor_select_focus;
}

function webeditor_select_focus(evt) {
	if (evt && evt.target && evt.target.id) webeditor.select_focused[evt.target.id] = true;
	return true;
}

function contenteditable_onload(handler) {
	window.addEventListener("load", handler, true);
}

function contenteditable_onload_remove(handler) {
	window.removeEventListener("load", handler, true);
}

function contenteditable_init() {
	for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
		var iframe = document.getElementsByTagName('iframe').item(i);
		if (iframe.className == 'HardCore_contenteditable') {
			try {
				if (contenteditable_stylesheet[iframe.id]) {
					iframe.contentWindow.document.getElementById('stylesheet').href = contenteditable_stylesheet[iframe.id];
				}
				iframe.contentWindow.document.dir = webeditor.direction;
				iframe.contentWindow.document.designMode = "on";
				iframe.contentWindow.document.execCommand("redo", false, null);
				iframe.contentWindow.document.body.innerHTML = contenteditable_contents[iframe.id];
				if (! contenteditable_onfocus[i]) contenteditable_onfocus[i] = new Function('contenteditable_onfocus'+i, 'contenteditable_focused='+i+';webeditor_onfocus();webeditor_refreshToolbar(true);');
				if (! contenteditable_onblur[i]) contenteditable_onblur[i] = new Function('contenteditable_onblur'+i, 'webeditor_onblur();webeditor_refreshToolbar(true);');
				iframe.contentWindow.document.addEventListener("focus", contenteditable_onfocus[i], true);
				iframe.contentWindow.document.addEventListener("blur", contenteditable_onblur[i], true);
				iframe.contentWindow.document.addEventListener("keydown", contenteditable_event, true);
				iframe.contentWindow.document.addEventListener("keyup", contenteditable_event, true);
				iframe.contentWindow.document.addEventListener("keypress", contenteditable_event, true);
				iframe.contentWindow.document.addEventListener("mousedown", contenteditable_event, true);
				iframe.contentWindow.document.addEventListener("mouseup", contenteditable_event, true);
				iframe.contentWindow.document.addEventListener("drag", contenteditable_event, true);
				var form = iframe;
				while ((form.tagName != "FORM") && (form.tagName != "HTML")) {
					form = form.parentNode;
				}
				if (form.tagName != "HTML") {
					form.addEventListener("submit", contenteditable_onSubmit, true);
					form[iframe.id].value = contenteditable_contents[iframe.id];
				}
			}  catch (e) {
				alert(Text('webbrowser_unsupported_contenteditable'));
			}
		}
	}
}

function contenteditable_enable() {
	for (i=0; i<document.getElementsByTagName('iframe').length; i++) {
		var iframe = document.getElementsByTagName('iframe').item(i);
		if (iframe.className == 'HardCore_contenteditable') {
			try {
				iframe.contentWindow.document.designMode = "on";
			}  catch (e) {
			}
		}
	}
}

function contenteditable_event(event) {
	return webeditor_event(event);
}

function contenteditable_event_stop(event) {
	event.preventDefault();
	event.stopPropagation();
}

function contenteditable_event_ctrlkey(event) {
	if (event.ctrlKey && (event.type == "keypress")) {
		return true;
	} else {
		return false;
	}
}

function contenteditable_event_key(event) {
	if (event.type == "keypress") {
		return String.fromCharCode(event.charCode).toLowerCase();
	} else {
		return false;
	}
}

function contenteditable_toolbar() {
	if (webeditor.toolbar && webeditor.toolbar.contentDocument && webeditor.toolbar.contentDocument.body) {
		return webeditor.toolbar.contentDocument.body;
	} else {
		return document;
	}
}

function contenteditable_selection(contentWindow) {
	if (! contentWindow) contentWindow = contenteditable_focused_contentwindow();
	return contentWindow.getSelection();
}

function contenteditable_selection_text(contentSelection) {
	if (! contentSelection) contentSelection = contenteditable_selection();
	return getRangeHTML(contenteditable_selection_range(contentSelection),false);
}

function contenteditable_selection_range_control(contentSelection) {
}

function contenteditable_selection_range(contentSelection) {
	if (! contentSelection) contentSelection = contenteditable_selection();
	if (contentSelection) try { return contentSelection.getRangeAt(0); } catch(e) { }
}

function contenteditable_selection_range_parentNode(contentRange) {
	if (! contentRange) contentRange = contenteditable_selection_range();
	if (contentRange) {
		if (contentRange.commonAncestorContainer.nodeName == '#text') {
			return contentRange.commonAncestorContainer.parentNode;
		} else {
			return contentRange.commonAncestorContainer;
		}
	}
}

function contenteditable_selection_cells(contentSelection) {
	if (! contentSelection) contentSelection = contenteditable_selection();
	var cells = new Array();
	var rows = new Array();
	var row;
	for (var i=0; i<contentSelection.rangeCount; i++) {
		var range = contentSelection.getRangeAt(i);
		var node = range.startContainer.childNodes[range.startOffset];
		if (node && (node.tagName == "TD")) {
			var rowIndex = rows.length;
			for (var j=0; j<rows.length; j++) {
				if (rows[j] == node.parentNode) rowIndex = j;
			}
			rows[rowIndex] = node.parentNode;
			if (! cells[rowIndex]) cells[rowIndex] = new Array();
			cells[rowIndex].push(node);
		}
	}
	if (cells.length) return cells;
}

function contenteditable_createrange() {
	return contenteditable_focused_contentwindow().document.createRange();
//	return document.createRange();
}

function contenteditable_selection_container(tagName) {
	const START_TO_START = 0;
	const START_TO_END = 1;
	const END_TO_END = 2;
	const END_TO_START = 3;

	var range = contenteditable_selection_range();
	var container;
	var container_exact = false;
	if (range && (range.startContainer == range.endContainer) && (! range.startContainer.tagName)) {
		// range is within single text node
		container = contenteditable_selection_range_parentNode();
		if (container && (! container.tagName)) container = container.parentNode;
	} else if (range && (range.startContainer == range.endContainer) && (range.startContainer.tagName == "BODY") && (range.startOffset == 0) && (range.endOffset == range.startContainer.childNodes.length)) {
		// range is entire body
		container = contenteditable_selection_range_parentNode();
		if (container && (! container.tagName)) container = container.parentNode;
		if (tagName && container.firstChild && (container.firstChild.tagName == tagName.toUpperCase()) && (container.firstChild == container.lastChild)) container = container.firstChild;
	} else {
		var content;
		var startContainer = false;
		var endContainer = false;
		var tag;
		if (false && range && (range.startContainer == range.endContainer) && (range.startOffset == 0) && (range.endOffset == range.startContainer.childNodes.length)) {
			// range is all child nodes
		} else if (true && range && (range.startContainer == range.endContainer) && (range.startOffset == range.endOffset-1)) {
			// range is one child node
			content = range.startContainer.childNodes[range.startOffset];
			tag = content;
		} else if (true && range && (range.startContainer == range.endContainer) && (range.startOffset == range.endOffset)) {
			// range is nothing
			content = range.startContainer.childNodes[range.startOffset];
			tag = content;
		} else if (true && range && range.startContainer.tagName && range.endContainer.tagName) {
			// range is node range
			content = contenteditable_selection_range_parentNode();
			startContainer = range.startContainer.childNodes[range.startOffset];
			endContainer = range.endContainer.childNodes[range.endOffset];
			tag = startContainer;
		} else if (true && range && (range.startContainer.tagName || range.endContainer.tagName)) {
			// range is partial node range
			content = contenteditable_selection_range_parentNode();
			tag = content;
		} else {
			// range is text range
			content = contenteditable_selection_range_parentNode();
			tag = content;
			if (content && content.lastChild) tag = content.lastChild;
		}
		container_exact = false;
		container = content;
		if (container && (! container.tagName)) container = container.parentNode;
		var skipChildren = false;
		var containerCount = 0;
		while (tag = contenteditable_nextnode(content, tag, skipChildren)) {
			var element = contenteditable_createrange();
			if (element.selectNode) {
				element.selectNode(tag);
				if (tag.tagName && range && (range.compareBoundaryPoints(START_TO_START,element) == 0) && (range.compareBoundaryPoints(END_TO_END,element) == 0)) {
					container_exact = tag;
					if (container_exact.nodeName == webeditor.selection_node) break;
					if (tag.tagName == "TABLE") {
						skipChildren = true;
					} else {
						skipChildren = false;
					}
				} else if (tag.tagName && range && (range.compareBoundaryPoints(END_TO_START,element) <= 0) && (range.compareBoundaryPoints(START_TO_END,element) >= 0)) {
					if (container == tag.parentNode) {
						containerCount = 1;
					} else {
						containerCount++;
					}
					container = tag;
					if (tag.tagName == "TABLE") {
						skipChildren = true;
					} else {
						skipChildren = false;
					}
				} else {
					skipChildren = true;
				}
			}
			if (tag == endContainer) break;
		}
		if (containerCount > 1) container = content;
	}
	if (webeditor.selection_node && container) {
		var selection_node = container;
		while (selection_node && (webeditor.selection_node != selection_node.nodeName)) {
			selection_node = selection_node.parentNode;
		}
		if (selection_node) container = selection_node;
	}
	if (tagName) {
		var selection_node = container_exact || container || false;
		while (selection_node && (tagName.toUpperCase() != selection_node.tagName)) {
			selection_node = selection_node.parentNode;
		}
		return selection_node;
	} else {
		return container_exact || container || contenteditable_focused_document().body;
	}
}

function contenteditable_selection_node(startNode, endNode, startOffset, endOffset) {
	webeditor.selection_node = startNode.nodeName;
	var range = contenteditable_selection_range() || contenteditable_createrange();
	if (startNode.nodeName == "BODY") {
		range.selectNodeContents(startNode);
	} else {
		range.selectNode(startNode);
	}
	if (endNode) {
		try {
			range.setStart(startNode, startOffset);
			range.setEnd(endNode, endOffset);
		} catch(e) {
		}
	}
	var selection = contenteditable_selection();
	selection.removeAllRanges();
	selection.addRange(range);
}

function contenteditable_insertNodeAtSelection(contentWindow, insertNode, insertHTML) {
	var insertedNode = insertNode;

	// get current selection
	var selection = contenteditable_selection(contentWindow);

	// get the first range of the selection
	// (there's almost always only one range)
	var range;
	try {
		range = selection.getRangeAt(0);
	} catch(e) {
		range = contenteditable_createrange();
		range.selectNodeContents(contenteditable_focused_document().body);
		range.collapse(1);
	}

	// deselect everything
	selection.removeAllRanges();

	// remove content of current selection from document
	range.deleteContents();

	// get location of current selection
	var container = range.startContainer;
	var pos = range.startOffset;

	// make a new range for the new selection
	range = document.createRange();

	if ((container.nodeType == 3) && (insertNode.nodeType == 3)) {
		// if we insert text in a textnode, do optimized insertion
		container.insertData(pos, insertNode.nodeValue);

		// put cursor after inserted text
		range.setEnd(container, pos+insertNode.length);
		range.setStart(container, pos+insertNode.length);

		insertedNode = container;
	} else {
		var afterNode;
		if (container.nodeType==3) {
			// when inserting into a textnode
			// we create 2 new textnodes
			// and put the insertNode in between

			var textNode = container;
			container = textNode.parentNode;
			var text = textNode.nodeValue;

			// text before the split
			var textBefore = text.substr(0,pos);
			// text after the split
			var textAfter = text.substr(pos);

			var beforeNode = document.createTextNode(textBefore);
			var afterNode = document.createTextNode(textAfter);

			// insert the 3 new nodes before the old one
			container.insertBefore(afterNode, textNode);
			container.insertBefore(insertNode, afterNode);
			container.insertBefore(beforeNode, insertNode);

			// remove the old node
			container.removeChild(textNode);

			insertedNode = insertNode;
		} else {
			// else simply insert the node
			afterNode = container.childNodes[pos];
			if (afterNode) {
				container.insertBefore(insertNode, afterNode);
				insertedNode = insertNode;
			} else {
				try {
					container.appendChild(insertNode);
					insertedNode = insertNode;
				} catch(e) {
					var parentNode = container.parentNode;
					parentNode.replaceChild(insertNode, container);
					insertedNode = insertNode;
				}
			}
		}
		if (range && range.setEnd && afterNode) {
			range.setEnd(afterNode, 0);
		}
		if (range && range.setStart && afterNode) {
			range.setStart(afterNode, 0);
		}
	}
	try {
		selection.addRange(range);
	}  catch (e) {
	}
	return insertedNode;
}

function contenteditable_selection_container_list() {
	var list;
	if (list = contenteditable_selection_container('ul')) {
	} else if (list = contenteditable_selection_container('ol')) {
	} else if (list = contenteditable_selection_container('dir')) {
	} else if (list = contenteditable_selection_container('menu')) {
	} else if (list = contenteditable_selection_container('dl')) {
	}
	return list;
}

function contenteditable_selection_container_listitem() {
	var listitem;
	if (listitem = contenteditable_selection_container('li')) {
	} else if (listitem = contenteditable_selection_container('dt')) {
	} else if (listitem = contenteditable_selection_container('dd')) {
	}
	return listitem;;
}

function contenteditable_formatblock_list(value) {
	var list = contenteditable_selection_container_list();
	if (! list) {
		contenteditable_execcommand("insertunorderedlist");
		list = contenteditable_selection_container('ul');
	}
	if (list && value) {
		var new_list = contenteditable_focused_contentwindow().document.createElement(value);
		for (var element=list.firstChild; element; element=element.nextSibling) {
			var new_element = element.cloneNode(true);
			new_list.appendChild(new_element);
		}
		list.parentNode.replaceChild(new_list,list);
		return new_list;
	} else if (list) {
		// remove list
		var new_list = contenteditable_focused_contentwindow().document.createElement("p");
		for (var element=list.firstChild; element; element=element.nextSibling) {
			for (var subelement=element.firstChild; subelement; subelement=subelement.nextSibling) {
				var new_subelement = subelement.cloneNode(true);
				new_list.appendChild(new_subelement);
			}
			new_list.appendChild(contenteditable_focused_contentwindow().document.createElement("br"));
			new_list.appendChild(contenteditable_focused_contentwindow().document.createTextNode("\r\n"));
		}
		list.parentNode.replaceChild(new_list,list);
		return new_list;
	}
}

function contenteditable_formatblock_listitem(value) {
	var listitem = contenteditable_selection_container_listitem();
	if (listitem) {
		var new_listitem = contenteditable_focused_contentwindow().document.createElement(value);
		for (var element=listitem.firstChild; element; element=element.nextSibling) {
			var new_element = element.cloneNode(true);
			new_listitem.appendChild(new_element);
		}
		listitem.parentNode.replaceChild(new_listitem,listitem);
		return new_listitem;
	}
}

function contenteditable_formatblock_listitems(list,value) {
	if (list) {
		for (var listitem=list.firstChild; listitem; listitem=listitem.nextSibling) {
			if ((listitem.nodeName == "LI") || (listitem.nodeName == "DT") || (listitem.nodeName == "DD")) {
				var new_listitem = contenteditable_focused_contentwindow().document.createElement(value);
				for (var element=listitem.firstChild; element; element=element.nextSibling) {
					var new_element = element.cloneNode(true);
					new_listitem.appendChild(new_element);
				}
				list.replaceChild(new_listitem,listitem);
				listitem = new_listitem;
			}
		}
	}
}

function contenteditable_BlockDirLTR(id) {
	contenteditable_focused_contentwindow().document.dir = 'ltr';
}

function contenteditable_BlockDirRTL(id) {
	contenteditable_focused_contentwindow().document.dir = 'rtl';
}

function contenteditable_formatblock_query() {
	var formatblock;
	var list;
	var listitem;
	if ((formatblock = ('' + contenteditable_focused_document().queryCommandValue("formatblock")).toLowerCase()) && (formatblock != "p")) {
		return formatblock;
	} else if ((listitem = contenteditable_selection_container_listitem()) && (listitem.nodeName != "LI"))  {
		return listitem.nodeName.toLowerCase();
	} else if (list = contenteditable_selection_container_list()) {
		return list.nodeName.toLowerCase();
	}
	return "p";
}

function contenteditable_formatblock(command,value) {
	if (value == "<ol>") {
		var list = contenteditable_formatblock_list("ol");
		contenteditable_formatblock_listitems(list,"li");
	} else if (value == "<ul>") {
		var list = contenteditable_formatblock_list("ul");
		contenteditable_formatblock_listitems(list,"li");
	} else if (value == "<dir>") {
		var list = contenteditable_formatblock_list("dir");
		contenteditable_formatblock_listitems(list,"li");
	} else if (value == "<menu>") {
		var list = contenteditable_formatblock_list("menu");
		contenteditable_formatblock_listitems(list,"li");
	} else if (value == "<dt>") {
		var list = contenteditable_selection_container_list();
		if (list.nodeName == "dl") {
			contenteditable_formatblock_listitem("dt");
		} else {
			list = contenteditable_formatblock_list("dl");
			contenteditable_formatblock_listitems(list,"dt");
		}
	} else if (value == "<dd>") {
		var list = contenteditable_selection_container_list();
		if (list.nodeName == "dl") {
			contenteditable_formatblock_listitem("dd");
		} else {
			list = contenteditable_formatblock_list("dl");
			contenteditable_formatblock_listitems(list,"dd");
		}
	} else if (value == "<p>") {
		// applying normal/p to lists and list items does not work
		// approximate it by getting rid of the list completely
		// not ideal - but better than lists that are stuck
		var list = contenteditable_selection_container_list();
		if (list) {
			list = contenteditable_formatblock_list("");
		} else {
			contenteditable_execcommand(command,value);
		}
	} else {
		contenteditable_execcommand(command,value);
	}
}

function contenteditable_formatclass(command,value) {
	var contentSelection = contenteditable_selection();
	for (var i=0; i<contentSelection.rangeCount; i++) {
		var range = contentSelection.getRangeAt(i);
		if ((range.startContainer == range.endContainer) && (range.startOffset != range.endOffset) && (range.startContainer.nodeType == 3)) { // TEXT NODE
			if ((range.startOffset == 0) && (range.endOffset == range.startContainer.nodeValue.length) && (range.startContainer.parentNode.nodeName == "SPAN") && (range.startContainer.parentNode.childNodes.length == 1)) {
				contenteditable_formatclass_attribute(range.startContainer.parentNode, value);
			} else {
				var node = document.createElement("span");
				contenteditable_formatclass_attribute(node, value);
				node.appendChild(document.createTextNode(range.startContainer.nodeValue.substring(range.startOffset, range.endOffset)));
				contenteditable_insertNodeAtSelection(contenteditable_focused_contentwindow(), node);
			}
		} else if (range.startContainer != range.endContainer) {
			var node = range.startContainer;
			var startcontainer = range.startContainer;
			if (startcontainer.nodeType == 3) { // TEXT NODE
				if (range.startOffset < startcontainer.nodeValue.length) {
					startcontainer = startcontainer.parentNode;
				} else {
					startcontainer = startcontainer.nextSibling;
				}
			}
			var endcontainer = range.endContainer;
			if (endcontainer.nodeType == 3) { // TEXT NODE
				if (range.endOffset > 0) {
					endcontainer = endcontainer.parentNode;
				} else {
					endcontainer = endcontainer.previousSibling;
				}
			}
			for (var node = startcontainer; node != null; node = node.nextSibling) {
				contenteditable_formatclass_attribute(node, value);
				if (node == endcontainer) break;
			}
		} else if (range.startOffset != range.endOffset) {
			for (j=range.startOffset; j<range.endOffset; j++) {
				var node = range.startContainer.childNodes[range.startOffset];
				if (node.nodeType == 3) node = node.parentNode; // TEXT NODE
				contenteditable_formatclass_attribute(node, value);
			}
		} else {
			var node = range.commonAncestorContainer;
			while ((node != null) && (node.nodeType == 3)) { node = node.parentNode; } // TEXT NODE
			contenteditable_formatclass_attribute(node, value);
		}
	}
}

function contenteditable_find(command) {
	// Find does not work properly in Mozilla 1.3-1.5 - nsFindInstData may be undefined
	if (! contenteditable_focused_contentwindow().nsFindInstData) contenteditable_focused_contentwindow().nsFindInstData = new Function();
	if (contenteditable_viewsource_status[contenteditable_focused]) {
		contenteditable_focused_textarea().find();
	} else {
		contenteditable_focused_contentwindow().find();
	}
	return true;
}

function contenteditable_print(command) {
	contenteditable_focused_contentwindow().print();
	return true;
}

function contenteditable_fontname(command,value) {
	return contenteditable_execcommand(command,value);
}

function contenteditable_fontsize(command,value) {
	return contenteditable_execcommand(command,value);
}

function contenteditable_document_stylesheets_cssrules(stylesheet) {
	return stylesheet.cssRules;
}

function contenteditable_viewsource_onfocus(textarea) {
	textarea.removeEventListener("focus", contenteditable_onfocus[contenteditable_focused], true);
	textarea.addEventListener("focus", contenteditable_onfocus[contenteditable_focused], true);
	textarea.removeEventListener("blur", contenteditable_onblur[contenteditable_focused], true);
	textarea.addEventListener("blur", contenteditable_onblur[contenteditable_focused], true);
}

function contenteditable_viewsource_textarea2html(textarea, iframe) {
	var content = textarea.value;
	try {
		if (webeditor_custom_encode) content = webeditor_custom_encode(content);
	} catch(e) {
	}
	content = contenteditable_encodeScriptTags(content);
	iframe.contentWindow.document.body.innerHTML = content;
	iframe.contentWindow.document.removeEventListener("focus", contenteditable_onfocus[contenteditable_focused], true);
	iframe.contentWindow.document.addEventListener("focus", contenteditable_onfocus[contenteditable_focused], true);
	iframe.contentWindow.document.removeEventListener("blur", contenteditable_onblur[contenteditable_focused], true);
	iframe.contentWindow.document.addEventListener("blur", contenteditable_onblur[contenteditable_focused], true);
}

function contenteditable_viewsource_show() {
	// old viewsource functionality using text encoding
	var html = document.createTextNode(document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerHTML);
	document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerHTML = "";
	document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.appendChild(html);
}

function contenteditable_viewsource_hide() {
	// old viewsource functionality using text encoding
	var html = document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.ownerDocument.createRange();
	html.selectNodeContents(document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body);
	document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerHTML = html.toString();
}

function contenteditable_specialcharacter(value) {
	var contentWindow = contenteditable_focused_contentwindow();
	if (value) {
		var a = contentWindow.document.createTextNode(value);
		var selection = contenteditable_selection();
		var range = contenteditable_selection_range(selection);
		contenteditable_insertNodeAtSelection(contentWindow, a);
	}
}

function contenteditable_setAttribute(node, attribute, value) {
	node.setAttribute(attribute, value);
}

function contenteditable_getAttribute(node, attribute) {
	if (node) {
		return node.getAttribute(attribute) || "";
	} else {
		return "";
	}
}

function contenteditable_removeAttribute(node, attribute) {
	node.removeAttribute(attribute);
}

function contenteditable_insertimage_fix(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout) {
}

function contenteditable_insertlink_fix(href, target, text, htmlclass, htmlid, onclick) {
}

function contenteditable_position(state) {
	var element = contenteditable_positionable();
	if (state) {
		// OK
	} else if (element) {
		if (element.style.position == "absolute") {
			element.style.position = "";
			element.style.top = "";
			element.style.left = "";
			element.style.zIndex = "";
		} else {
			var top = contenteditable_position_offsetTop(element);
			var left = contenteditable_position_offsetLeft(element);
			element.style.position = "absolute";
			if (top) element.style.top = top + "px";
			if (left) element.style.left = left + "px";
			element.style.zIndex = 1;
		}
		if (element.getAttribute("STYLE") == "") {
			element.removeAttribute("STYLE", 0);
		}
	}
}

function contenteditable_forwards() {
	var element = contenteditable_positionable();
	if (element) {
		if (element.style && element.style.zIndex && (element.style.zIndex < 1000000)) {
			element.style.zIndex = parseInt(element.style.zIndex) + 1;
		} else {
			element.style.zIndex = 1;
		}
	}
}

function contenteditable_backwards() {
	var element = contenteditable_positionable();
	if (element) {
		if (element.style && element.style.zIndex && (element.style.zIndex > 0)) {
			element.style.zIndex = parseInt(element.style.zIndex) - 1;
		} else {
			element.style.zIndex = 0;
		}
	}
}

function contenteditable_front() {
	var element = contenteditable_positionable();
	if (element) {
		zIndex = contenteditable_positionable_front_max();
		if (zIndex < 1000000) {
			zIndex++;
		} else {
			zIndex = 1000000;
		}
		element.style.zIndex = zIndex;
	}
}

function contenteditable_back() {
	var element = contenteditable_positionable();
	if (element) {
		zIndex = contenteditable_positionable_back_min();
		if (zIndex > 0) {
			zIndex--;
		} else {
			zIndex = 0;
		}
		element.style.zIndex = zIndex;
	}
}

function contenteditable_abovetext() {
	var element = contenteditable_positionable();
	if (element && element.style) {
		if (element.style.zIndex < 0) {
			element.style.zIndex = -element.style.zIndex;
		} else if (element.style.zIndex == 0) {
			element.style.zIndex = 1;
		}
	}
}

function contenteditable_belowtext() {
}

function contenteditable_event_delete(my_event) {
}

function contenteditable_event_enter_p() {
	var range = contenteditable_selection_range();
	if ((range.startContainer == range.endContainer) && (range.startContainer.nodeName == "#text")) {
		var preP = range.startContainer.nodeValue.substring(0, range.startOffset) || "&nbsp;";
		var postP = range.startContainer.nodeValue.substring(range.endOffset) || "&nbsp;";
		var oldP = range.startContainer;
		if (oldP.parentNode.nodeName == "P") {
			var newP = contenteditable_focused_contentwindow().document.createElement("P");
			newP.innerHTML = preP;
			oldP.parentNode.parentNode.insertBefore(newP, oldP.parentNode);
			oldP.nodeValue = postP;
		} else {
			var newP = contenteditable_focused_contentwindow().document.createElement("P");
			var newP2 = contenteditable_focused_contentwindow().document.createElement("P");
			newP.innerHTML = preP;
			newP2.innerHTML = postP;
			oldP.parentNode.insertBefore(newP, oldP);
			oldP.parentNode.insertBefore(newP2, oldP.nextSibling);
			oldP.parentNode.removeChild(oldP);
		}
	} else {
		contenteditable_pasteContent("<p>");
	}
}

function contenteditable_selection_bookmark(bookmark) {
	if (bookmark) {
		var rootnode = contenteditable_focused_document().body;
		var startNodeNumber = bookmark.startNode;
		var startOffset = bookmark.startOffset;
		var endNodeNumber = bookmark.endNode;
		var endOffset = bookmark.endOffset;
		var startNode = contenteditable_selection_bookmark_path_node(rootnode, bookmark.startNodePath)
		var endNode = contenteditable_selection_bookmark_path_node(rootnode, bookmark.endNodePath)
		contenteditable_selection_node(startNode, endNode, startOffset, endOffset);
		if (contenteditable_selection_container().scrollIntoView) contenteditable_selection_container().scrollIntoView();
		contenteditable_focused_contentwindow().focus();
	} else {
		var range;
		if (range = contenteditable_selection_range()) {
			bookmark = new Object();
			bookmark.startNode = 0;
			bookmark.startOffset = range.startOffset;
			bookmark.endNode = 0;
			bookmark.endOffset = range.endOffset;
	
			bookmark.startNodePath = contenteditable_selection_bookmark_path(range.startContainer);
			bookmark.endNodePath = contenteditable_selection_bookmark_path(range.endContainer);
		}
	}
	return bookmark;
}

function contenteditable_selection_bookmark_path(node) {
	var nodePath = "";
	var nodeNumber = 1;
	var nodeName = node.nodeName;
	var tmp_node = node;
	while (tmp_node = tmp_node.previousSibling) {
		if (tmp_node.nodeName == nodeName) nodeNumber++;
	}
	nodePath = nodeNumber + "." + nodeName;
	while ((node = node.parentNode) && (node.nodeName != "BODY") && (node.nodeName != "HTML") && (node.nodeName != "#document")) {
		var nodeNumber = 1;
		var nodeName = node.nodeName;
		var tmp_node = node;
		while (tmp_node = tmp_node.previousSibling) {
			if (tmp_node.nodeName == nodeName) nodeNumber++;
		}
		nodePath = nodeNumber + "." + nodeName + " " + nodePath;
	}
	return nodePath;
}

function contenteditable_selection_bookmark_path_node(node, nodePath) {
	var nodePathSteps = nodePath.split(" ");
	for (var i=0; i<nodePathSteps.length; i++) {
		var parts = nodePathSteps[i].split(".");
		var nodeNumber = parseInt(parts[0]);
		var nodeName = parts[1];
		var childNode = node.firstChild;
		if (childNode.nodeName == nodeName) nodeNumber--;
		while (nodeNumber && (childNode = childNode.nextSibling)) {
			if (childNode.nodeName == nodeName) nodeNumber--;
		}
		if (childNode) {
			node = childNode;
		} else {
		}
	}
	return node;
}




//	getRangeHTML and getNodeHTML based on:

//	File:		RangePatch1_3.js
//	Name:		Mozilla Range Implementation Patch
//	Version:	1.3
//	Author:		Jeffrey M. Yates
//	Contact:	PBWiz@PBWizard.com
//	Home:		http://www.pbwizard.com
//	Date:		25 March 2001

function getRangeHTML(range) {
		var rangeHTML = "";
		var node;
		if ((range.commonAncestorContainer.nodeName == "BODY") && (! range.commonAncestorContainer.firstChild)) {
			// empty
		} else if (node = range.commonAncestorContainer.firstChild) {
			while (node) {
				rangeHTML += getNodeHTML(node, range);
				node = node.nextSibling;
			}
		} else {
			rangeHTML += getNodeHTML(range.startContainer, range);
		}
		return rangeHTML;
}

function getNodeHTML(node, range) {
	if (range && (! nodeInRange(node, range))) return '';
	switch(node.nodeType) {
	case 1: // ELEMENT_NODE
		var nodeHTML = '';
		if ((node != range.startContainer) || (range.startOffset == 0)) {
			nodeHTML += '<' + node.nodeName;
			for (var i = 0; i < node.attributes.length; i++) {
				if (node.attributes.item(i).specified)
					nodeHTML += ' ' + getNodeHTML(node.attributes.item(i), null);
			}
			nodeHTML += '>';
		}
		if (node.hasChildNodes()) {
			var childnode = node.firstChild;
			while (childnode) {
				nodeHTML += getNodeHTML(childnode, range);
				childnode = childnode.nextSibling;
			}
			if ((node != range.startContainer) || (range.startOffset == 0)) {
				nodeHTML += '</' + node.nodeName + '>';
			}
		}
		return nodeHTML;

	case 2: // ATTRIBUTE_NODE
		var nodeHTML = '';
		if (node.specified && (node.nodeName != '_moz_dirty')) {
			nodeHTML += node.nodeName + '="' + node.nodeValue + '"';
		}
		return nodeHTML;

	case 3: // TEXT_NODE
		var nodeHTML = node.data;
		if (range) {
			if (node == range.endContainer) {
				nodeHTML = nodeHTML.substring(0, range.endOffset);
			}
			if (node == range.startContainer) {
				nodeHTML = nodeHTML.substring(range.startOffset, nodeHTML.length+1);
			}
		}
		return nodeHTML;

	case 4: // CDATA_SECTION_NODE
		var nodeHTML = node.data;
		if (range) {
			if (node == range.endContainer) {
				nodeHTML = nodeHTML.substring(0, range.endOffset);
			}
			if (node == range.startContainer) {
				nodeHTML = nodeHTML.substring(range.startOffset, nodeHTML.length+1);
			}
		}
		return '<![CDATA[' + nodeHTML + ']]>';
	
	case 5: // ENTITY_REFERENCE_NODE
		return '&' + node.nodeName + ';';
	
	case 6: // ENTITY_NODE
		var nodeHTML = '<!ENTITY ' + node.nodeName;
		if( node.publicId ){
			nodeHTML += ' PUBLIC "' + node.publicId + '"';
			if (node.systemId) {
				nodeHTML += ' "' + node.systemId + '"';
			}
			if (node.notationName) {
				nodeHTML += ' NDATA ' + node.notationName;
			}
		} else if( node.systemId ) {
			nodeHTML += ' SYSTEM "' + node.systemId + '"';
			if( node.notationName ) {
				nodeHTML += ' NDATA ' + node.notationName;
			}
		} else {
			nodeHTML += '"';
			var childnode = node.firstChild;
			while (childnode) {
				nodeHTML += getNodeHTML(childnode, range);
				childnode = childnode.nextSibling;
			}
			nodeHTML += '"';
		}
		return nodeHTML + '>';

	case 7: // PROCESSING_INSTRUCTION_NODE
		var nodeHTML = '<?' + node.target + ' ' + node.data + '?>';
		return nodeHTML;
			
	case 8: // COMMENT_NODE
		var nodeHTML = node.data;
		if (range) {
			if (node == range.endContainer) {
				nodeHTML = nodeHTML.substring(0, range.endOffset);
			}
			if (node == range.startContainer) {
				nodeHTML = nodeHTML.substring(range.startOffset, nodeHTML.length+1);
			}
		}
		nodeHTML = '<!--' + nodeHTML + '-->';
		return nodeHTML;
	
	case 9: // DOCUMENT_NODE
		var nodeHTML = '';
		var foundDocType = false;
		var childnode = node.firstChild;
		while (childnode) {
			if (childnode.nodeType != 10 ) foundDocType = true;
			nodeHTML += getNodeHTML(childnode, range);
			childnode = childnode.nextSibling;
		}
		if (! foundDocType && node.doctype) {
			nodeHTML = getNodeHTML(node.doctype, range) + nodeHTML;
		}
		return nodeHTML;

	case 10: // DOCUMENT_TYPE_NODE
		var nodeHTML = '<!DOCTYPE ' + node.nodeName;
		if (node.publicId) {
			nodeHTML += ' PUBLIC "' + node.publicId + '"';
			if (node.systemId) nodeHTML += ' "' + node.systemId + '"';
		} else if (node.systemId) {
			nodeHTML += ' SYSTEM "' + node.systemId + '"';
		}
		if (node.internalSubset) {
			nodeHTML += ' ' + node.internalSubset;
		}
		nodeHTML += '>\n';
		return nodeHTML;

	case 11: // DOCUMENT_FRAGMENT_NODE
		var nodeHTML = '';
		var childnode = node.firstChild;
		while (childnode) {
			nodeHTML += getNodeHTML(childnode, range);
			childnode = childnode.nextSibling;
		}
		return nodeHTML;

	case 12: // NOTATION_NODE
		var nodeHTML = '<!NOTATION ' + node.nodeName;
		if (node.publicId) {
			nodeHTML += ' PUBLIC "' + node.publicId + '"';
			if (node.systemId) nodeHTML += ' "' + node.systemId + '"';
		} else if (node.systemId) {
			nodeHTML += ' SYSTEM "' + node.systemId + '"';
		}
		nodeHTML += '>';
		return nodeHTML;
	}
}

function nodeInRange(node, range) {
	const START_TO_START = 0;
	const START_TO_END = 1;
	const END_TO_END = 2;
	const END_TO_START = 3;

	try {
		var nodeRange = document.createRange();
		nodeRange.selectNode(node);
		if ((range.compareBoundaryPoints(END_TO_START,nodeRange) == -1) && (range.compareBoundaryPoints(START_TO_END,nodeRange) == 1)) {
			return true;
		}
	} catch(e) {
	}
	return false;
}
