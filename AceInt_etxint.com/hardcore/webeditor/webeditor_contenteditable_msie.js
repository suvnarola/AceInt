// HardCore Web Content Editor
// Copyright 2002-2004 HardCore Internet Ltd.
// www.hardcoreinternet.co.uk

function webeditor_select_init(node) {
       node.ondeactivate = webeditor_select_deactivate;
       node.onblur = webeditor_select_blur;
}

function webeditor_select_focus(evt) {
       contenteditable_focused_contentwindow().focus();
       return true;
}

function contenteditable_onload(handler) {
       window.attachEvent("onload", handler);
}

function contenteditable_onload_remove(handler) {
       window.detachEvent("onload", handler);
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
                               iframe.contentWindow.document.body.contentEditable = true;
                               iframe.contentWindow.document.execCommand("redo", false, null);
                               // Setting innerHTML may not work properly - changes href/src from relative to absolute - use write instead
                               //iframe.contentWindow.document.body.innerHTML = contenteditable_contents[iframe.id];
                               iframe.contentWindow.document.body.innerHTML = "&nbsp;";
                               // MSIE replace may be broken for escaped "\$1" and "\$2" dollar characters in replacement string
                               iframe.contentWindow.document.write(iframe.contentWindow.document.documentElement.outerHTML.replace(new RegExp("(&nbsp;)?(</body>)", "i"), contenteditable_contents[iframe.id].replace(/\$([_`'+&0-9]+)/g, "\\\$\\$1")+"$2").replace(/\\(\$)\\([_`'+&0-9]+)/g, "$1$2"));
                               if (! contenteditable_onfocus[i]) contenteditable_onfocus[i] = new Function('contenteditable_focus'+i, 'contenteditable_focused='+i+';webeditor_onfocus();webeditor_refreshToolbar(true);');
                               if (! contenteditable_onblur[i]) contenteditable_onblur[i] = new Function('contenteditable_blur'+i, 'webeditor_onblur();webeditor_refreshToolbar(true);');
                               iframe.contentWindow.attachEvent("onfocus", contenteditable_onfocus[i]);
                               iframe.contentWindow.attachEvent("onblur", contenteditable_onblur[i]);
                               iframe.contentWindow.document.body.attachEvent("onfocus", contenteditable_onfocus[i]);
                               iframe.contentWindow.document.body.attachEvent("onblur", contenteditable_onblur[i]);
                               iframe.contentWindow.document.attachEvent("onkeydown", contenteditable_event);
                               iframe.contentWindow.document.attachEvent("onkeyup", contenteditable_event);
                               iframe.contentWindow.document.attachEvent("onkeypress", contenteditable_event);
                               iframe.contentWindow.document.attachEvent("onmousedown", contenteditable_event);
                               iframe.contentWindow.document.attachEvent("onmouseup", contenteditable_event);
                               iframe.contentWindow.document.attachEvent("ondrag", contenteditable_event);
                               iframe.contentWindow.document.close();
                               var form = iframe;
                               while ((form.tagName != "FORM") && (form.tagName != "HTML")) {
                                       form = form.parentNode;
                               }
                               if (form.tagName != "HTML") {
                                       form.attachEvent("onsubmit", contenteditable_onSubmit);
                                       form[iframe.id].value = contenteditable_contents[iframe.id];
                               }
                       }  catch (e) {
                               alert(Text('webbrowser_unsupported_contenteditable'));
                       }
               }
       }
}

function contenteditable_enable() {
}

function contenteditable_event(event) {
       return webeditor_event(contenteditable_focused_contentwindow().event);
}

function contenteditable_event_stop(event) {
       event.cancelBubble = true;
       event.returnValue = false;
}

function contenteditable_event_ctrlkey(event) {
       if (event.type == "keypress") {
               return event.ctrlKey;
       } else {
               return event.ctrlKey;
       }
}

function contenteditable_event_key(event) {
       if ((event.type == "keydown") && ((event.keyCode < 16) || (event.keyCode > 18))) {
               return String.fromCharCode(event.keyCode).toLowerCase();
       } else {
               return false;
       }
}

function contenteditable_toolbar() {
       if (webeditor.toolbar && webeditor.toolbar.contentWindow && webeditor.toolbar.contentWindow.document && webeditor.toolbar.contentWindow.document.body) {
               return webeditor.toolbar.contentWindow.document.body;
       } else {
               return document;
       }
}

function contenteditable_selection(contentWindow) {
       if (! contentWindow) contentWindow = contenteditable_focused_contentwindow();
       return contentWindow.document.selection;
}

function contenteditable_selection_text(contentSelection) {
       if (! contentSelection) contentSelection = contenteditable_selection();
       return contenteditable_selection_range(contentSelection).htmlText;
}

function contenteditable_selection_range_control(contentSelection) {
       if (! contentSelection) contentSelection = contenteditable_selection();
       if (contentSelection) {
               return (contentSelection.type == 'Control');
       }
}

function contenteditable_selection_range(contentSelection) {
       if (! contentSelection) contentSelection = contenteditable_selection();
       if (contentSelection) {
               if (contentSelection.type == 'Control') {
                       var range = contenteditable_createrange();
                       try {
                               range.moveToElementText(contentSelection.createRange()(0));
                       } catch(e) {
                               range = contenteditable_createtextrange();
                               try {
                                       range.moveToElementText(contentSelection.createRange()(0));
                               } catch(e) {
                               }
                       }
                       return range;
               } else {
                       try {
                               return contentSelection.createRange();
                       } catch(e) {
                               return false;
                       }
               }
       }
}

function contenteditable_selection_range_parentNode(contentRange) {
       if (! contentRange) contentRange = contenteditable_selection_range();
       if (contentRange.parentElement) {
               try {
                       return contentRange.parentElement();
               } catch(e) {
                       return false;
               }
       } else {
               return false;
       }
}

function contenteditable_selection_cells(contentSelection) {
       var range = contenteditable_selection_range();
       var table = contenteditable_selection_range_parentNode();
       while ((table.tagName == "THEAD") || (table.tagName == "TBODY") || (table.tagName == "TFOOT") || (table.tagName == "TR") || (table.tagName == "TD")) {
               table = table.parentNode;
       }
       if (table.tagName == "TABLE") {
               // MSIE as of v6.0 does not handle cell selection across rows "correctly".
               // It is not possible to select a square of cells.
               // Trailing and leading cells across rows are included in the selection.
               // Simulate a square selection of cells by finding first column in first row and last column in last row.
               var firstColumn = -1;
               var firstRow = -1;
               var lastColumn = -1;
               var lastRow = -1;
               for (var i=0; i<table.rows.length; i++) {
                       for (var j=0; j<table.rows[i].cells.length; j++) {
                               // create new range to use for comparison of current selection range to each tag element range
                               var element = contenteditable_createrange();
                               // set element range for this tag
                               if (element.moveToElementText) {
                                       element.moveToElementText(table.rows[i].cells[j]);
                                       // test if current selection range is equal to or part of this tag element range
                                       if ((range.compareEndPoints("EndToStart",element) == 1) && (range.compareEndPoints("StartToEnd",element) == -1)) {
                                               if (firstColumn == -1) firstColumn = j;
                                               if (firstRow == -1) firstRow = i;
                                               lastColumn = j;
                                               lastRow = i;
                                       }
                               }
                       }
               }
               if ((firstRow > -1) && (lastRow > -1) && (firstColumn > -1) && (lastColumn > -1)) {
                       var cells = new Array();
                       for (var i=firstRow; i<=lastRow; i++) {
                               cells[i-firstRow] = new Array();
                               for (var j=firstColumn; j<=lastColumn; j++) {
                                       cells[i-firstRow].push(table.rows[i].cells[j]);
                               }
                       }
                       if (cells.length) return cells;
               }
       }
}

function contenteditable_createrange() {
//      return contenteditable_focused_contentwindow().document.selection.createRange();
       return document.selection.createRange();
}

function contenteditable_createtextrange() {
       return contenteditable_focused_contentwindow().document.body.createTextRange();
}

function contenteditable_createcontrolrange() {
       return contenteditable_focused_contentwindow().document.body.createControlRange();
}

function contenteditable_selection_container(tagName) {
       var range = contenteditable_selection_range();
       var container_exact = false;
       var container = false;
       if (contenteditable_selection_range_control()) {
               container = contenteditable_selection().createRange()(0);
       } else {
               container = contenteditable_selection_range_parentNode();
       }
       if ((tagName == "object") && container) {
               for (var node=container.firstChild; node; node=node.nextSibling) {
                       if (node.tagName == "OBJECT") {
                               container = node;
                       }
               }
       }
       if (! container.tagName) container = container.parentNode;
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

function contenteditable_selection_node(node) {
       webeditor.selection_node = node.nodeName;
       if ((node.nodeName == "TABLE") || (node.nodeName == "IMG") || (node.nodeName == "INPUT") || (node.nodeName == "SELECT") || (node.nodeName == "TEXTAREA")) {
               try {
                       var range = contenteditable_createcontrolrange();
                       range.addElement(node);
                       range.select();
               } catch(e) {
               }
       } else {
               var range = contenteditable_createrange();
               range.collapse();
               if (range.moveToElementText) {
                       try {
                               range.moveToElementText(node);
                               range.select();
                       } catch(e) {
                               try {
                                       range = contenteditable_createtextrange();
                                       range.moveToElementText(node);
                                       range.select();
                               } catch(e) {
                               }
                       }
               } else {
                       try {
                               range = contenteditable_createtextrange();
                               range.moveToElementText(node);
                               range.select();
                       } catch(e) {
                       }
               }
       }
}

function contenteditable_selection_bookmark(bookmark) {
       if (bookmark) {
               var range;
               if (range = contenteditable_selection_range()) {
                       range.moveToBookmark(bookmark);
                       range.select();
                       range.scrollIntoView();
               }
               contenteditable_focused_contentwindow().focus();
       } else {
               var range;
               if (range = contenteditable_selection_range()) {
                       bookmark = range.getBookmark();
               }
       }
       return bookmark;
}

function contenteditable_insertNodeAtSelection(contentWindow, insertNode, insertHTML) {
       var insertedNode = insertNode;
       if (contentWindow) contentWindow.focus();
       var selection = contenteditable_selection(contentWindow);
       try {
               var range = contenteditable_selection_range(selection);
               if (insertNode.outerHTML) {
                       insertedNode = contenteditable_insertNodeAtSelection_outerHTML(range, insertNode, insertHTML);
               } else {
                       range.pasteHTML(insertNode.nodeValue);
                       // MISSING: insertedNode = [inserted node in document DOM]
               }
       } catch(e) {
               try {
                       var range = contenteditable_selection().createRange()(0);
                       if (insertNode.outerHTML) {
                               insertedNode = contenteditable_insertNodeAtSelection_outerHTML(range, insertNode, insertHTML);
                       } else {
                               range.outerHTML = insertNode.nodeValue;
                               // MISSING: insertedNode = [inserted node in document DOM]
                       }
               } catch(e) {
               }
       }
       return insertedNode;
}

function contenteditable_insertNodeAtSelection_outerHTML(range, insertNode, insertHTML) {
       var insertedNode = insertNode;
       if ((! insertNode.childNodes) || (! insertNode.childNodes.length)) {
               if (insertNode.outerHTML) {
                       if (! insertNode.id) insertNode.id = "HardCoreWebContentEditorInsertNodeAtSelectionDummy";
                       range.pasteHTML(insertNode.outerHTML);
                       insertedNode = contenteditable_focused_document().getElementById('HardCoreWebContentEditorInsertNodeAtSelectionDummy');
                       if (insertedNode && (insertedNode.id == "HardCoreWebContentEditorInsertNodeAtSelectionDummy")) contenteditable_removeAttribute(insertedNode, "id");
               } else {
                       range.pasteHTML(insertNode.nodeValue);
                       // MISSING: insertedNode = [inserted node in document DOM]
               }
       } else {
               try {
                       var element = contenteditable_createrange();
                       element.moveToElementText(range.parentElement());
                       if ((range.compareEndPoints("StartToStart",element) == 0) && (range.compareEndPoints("EndToEnd",element) == 0)) {
                               if (range.parentElement().nodeName == "BODY") {
                                       range.parentElement().innerHTML = '<div id="HardCoreWebContentEditorInsertNodeAtSelectionDummy">&nbsp;</div>';
                               } else {
                                       range.parentElement().outerHTML = '<div id="HardCoreWebContentEditorInsertNodeAtSelectionDummy">&nbsp;</div>';
                               }
                       } else if ((range.text == range.htmlText) && (insertNode.nodeName != "DIV")) {
                               range.pasteHTML('<span id="HardCoreWebContentEditorInsertNodeAtSelectionDummy">&nbsp;</span>');
                       } else {
                               range.pasteHTML('<div id="HardCoreWebContentEditorInsertNodeAtSelectionDummy">&nbsp;</div>');
                       }
               } catch(e) {
                       range.pasteHTML('<div id="HardCoreWebContentEditorInsertNodeAtSelectionDummy">&nbsp;</div>');
               }
               var dummyNode = contenteditable_focused_document().getElementById('HardCoreWebContentEditorInsertNodeAtSelectionDummy');
               if (dummyNode) {
                       var previousSibling = dummyNode.previousSibling;
                       var parentNode = dummyNode.parentNode;
                       if (false && insertHTML) {
                               // range.pasteHTML may generate invalid HTML code with overlapping tags and broken DOM
                               contenteditable_selection_node(dummyNode);
                               var range = contenteditable_selection_range();
                               range.pasteHTML(insertHTML);
                       } else {
                               // MSIE may have broken/removed PARAM tags inside OBJECT tag when insertNode.innerHTML was set
                               dummyNode.outerHTML = insertNode.outerHTML;
                       }
                       if (previousSibling) {
                               insertedNode = previousSibling.nextSibling;
                       } else {
                               insertedNode = parentNode.firstChild;
                       }
               }
       }
       return insertedNode;
}

function contenteditable_BlockDirLTR(id) {
       contenteditable_execcommand(id);
}

function contenteditable_BlockDirRTL(id) {
       contenteditable_execcommand(id);
}

function contenteditable_formatblock_query() {
       return ('' + contenteditable_focused_document().queryCommandValue("formatblock")).toLowerCase();
}

function contenteditable_formatblock(command,value) {
       if (value == "<p>") value = "normal";
       return contenteditable_execcommand(command,value);
}

function contenteditable_formatclass(command,value) {
       var range = contenteditable_selection_range();
       var container = contenteditable_selection_range_parentNode();
       if ((range.text == range.htmlText) && (range.text != '')) {
               range.pasteHTML('<span class="'+value+'">' + range.text + '</span>');
       } else if ((container.childNodes.length == 1) && (container.childNodes[0].nodeType == 3)) { // TEXT NODE
               contenteditable_formatclass_attribute(container, value);
       } else {
               contenteditable_formatclass_range(command,value,container,range);
       }
}

function contenteditable_formatclass_range(command,value,container,range) {
       // create new range to use for comparison of current selection range to each tag element range
       var element;
       element = range.duplicate();
       // set element range for this tag
       if ((element.moveToElementText) && (container.nodeType != 3)) {
               element.moveToElementText(container);
               // test if current selection range is equal to or part of this tag element range
               if ((range.compareEndPoints("StartToStart",element) == 0) && (range.compareEndPoints("EndToEnd",element) == 0)) {
                       contenteditable_formatclass_attribute(container, value);
               } else {
                       for (var i=0; i<container.childNodes.length; i++) {
                               var node = container.childNodes[i];
                               if (node.nodeType == 3) { // TEXT NODE
                                       contenteditable_formatclass_attribute(node.parentNode, value);
                               } else {
                                       // create new range to use for comparison of current selection range to each tag element range
                                       var element;
                                       element = range.duplicate();
                                       // set element range for this tag
                                       if (element.moveToElementText) {
                                               element.moveToElementText(node);
                                               // test if current selection range is equal to or part of this tag element range
                                               if ((range.compareEndPoints("EndToStart",element) == 1) && (range.compareEndPoints("StartToEnd",element) == -1)) {
                                                       if ((range.compareEndPoints("StartToStart",element) == 1) && (range.compareEndPoints("EndToEnd",element) == 1)) {
                                                               // start of range includes element partially
                                                               contenteditable_formatclass_range(command,value,node,range);
                                                       } else if ((range.compareEndPoints("StartToStart",element) == -1) && (range.compareEndPoints("EndToEnd",element) == -1)) {
                                                               // end of range includes element partially
                                                               contenteditable_formatclass_range(command,value,node,range);
                                                       } else if ((range.compareEndPoints("StartToStart",element) != 1) && (range.compareEndPoints("EndToEnd",element) != -1)) {
                                                               // range includes element completely
                                                               contenteditable_formatclass_attribute(node, value);
                                                       }
                                               }
                                       }
                               }
                       }
               }
       }
}

function contenteditable_find(command) {
       if (contenteditable_execcommand(command)) {
               return true;
       } else {
               try {
                       contenteditable_focused_contentwindow().find();
                       return true;
               } catch (e) {
                       webeditor.find_window = window.open(webeditor.rootpath+"find.html?editor=webeditor&find=", "find_window", ",width=400,height=150,scrollbars=yes,resizable=yes,status=yes", true);
                       return true;
               }
       }
}

function contenteditable_print(command) {
       if (contenteditable_execcommand(command)) {
               return true;
       } else {
               try {
                       contenteditable_focused_contentwindow().print();
                       return true;
               } catch (e) {
                       return false;
               }
       }
}

function contenteditable_fontname(command,value) {
       var rc = contenteditable_execcommand(command,value);
       if (value == "") {
               // may have set face="" instead of removing attribute and eventually tag - clean up manually
               var htmlcode = "" + contenteditable_selection_text();
               htmlcode = htmlcode.replace(new RegExp('^(<font[^>]*) face=""(.*)(</font>)$', "i"), "$1$2$3");
               htmlcode = htmlcode.replace(new RegExp('^(<font>)(.*)(</font>)$', "i"), "$2");
               contenteditable_pasteContent(htmlcode);
       }
       return rc;
}

function contenteditable_fontsize(command,value) {
       var rc = contenteditable_execcommand(command,value);
       if (value == "") {
               // may have set size=1 instead of removing attribute and eventually tag - clean up manually
               var htmlcode = "" + contenteditable_selection_text();
               htmlcode = htmlcode.replace(new RegExp('^(<font[^>]*) size="?1"?(.*)(</font>)$', "i"), "$1$2$3");
               htmlcode = htmlcode.replace(new RegExp('^(<font>)(.*)(</font>)$', "i"), "$2");
               contenteditable_pasteContent(htmlcode);
       }
       return rc;
}

function contenteditable_document_stylesheets_cssrules(stylesheet) {
       return stylesheet.rules;
}

function contenteditable_viewsource_onfocus(textarea) {
       textarea.detachEvent("onfocus", contenteditable_onfocus[contenteditable_focused]);
       textarea.attachEvent("onfocus", contenteditable_onfocus[contenteditable_focused]);
       textarea.onfocus = contenteditable_onfocus[contenteditable_focused];
       textarea.detachEvent("onblur", contenteditable_onblur[contenteditable_focused]);
       textarea.attachEvent("onblur", contenteditable_onblur[contenteditable_focused]);
       textarea.onblur = contenteditable_onblur[contenteditable_focused];
}

function contenteditable_viewsource_textarea2html(textarea, iframe) {
       var content = textarea.value;
       try {
               if (webeditor_custom_encode) content = webeditor_custom_encode(content);
       } catch(e) {
       }
       content = contenteditable_encodeScriptTags(content);

       // Setting innerHTML may not work properly - changes href/src from relative to absolute - use write instead
       //iframe.contentWindow.document.body.innerHTML = content;
       iframe.contentWindow.document.body.innerHTML = "&nbsp;";
       // MSIE replace may be broken for escaped "\$1" and "\$2" dollar characters in replacement string
       content = iframe.contentWindow.document.documentElement.outerHTML.replace(new RegExp("(&nbsp;)?(</body>)", "i"), content.replace(/\$([_`'+&0-9]+)/g, "\\\$\\$1")+"$2").replace(/\\(\$)\\([_`'+&0-9]+)/g, "$1$2");
       iframe.contentWindow.document.write(content);

       iframe.contentWindow.detachEvent("onfocus", contenteditable_onfocus[contenteditable_focused]);
       iframe.contentWindow.attachEvent("onfocus", contenteditable_onfocus[contenteditable_focused]);
       iframe.contentWindow.document.body.detachEvent("onfocus", contenteditable_onfocus[contenteditable_focused]);
       iframe.contentWindow.document.body.attachEvent("onfocus", contenteditable_onfocus[contenteditable_focused]);
       iframe.contentWindow.detachEvent("onblur", contenteditable_onblur[contenteditable_focused]);
       iframe.contentWindow.attachEvent("onblur", contenteditable_onblur[contenteditable_focused]);
       iframe.contentWindow.document.body.detachEvent("onblur", contenteditable_onblur[contenteditable_focused]);
       iframe.contentWindow.document.body.attachEvent("onblur", contenteditable_onblur[contenteditable_focused]);
       iframe.contentWindow.document.detachEvent("onkeydown", contenteditable_event);
       iframe.contentWindow.document.attachEvent("onkeydown", contenteditable_event);
       iframe.contentWindow.document.detachEvent("onkeyup", contenteditable_event);
       iframe.contentWindow.document.attachEvent("onkeyup", contenteditable_event);
       iframe.contentWindow.document.detachEvent("onkeypress", contenteditable_event);
       iframe.contentWindow.document.attachEvent("onkeypress", contenteditable_event);
       iframe.contentWindow.document.detachEvent("onmousedown", contenteditable_event);
       iframe.contentWindow.document.attachEvent("onmousedown", contenteditable_event);
       iframe.contentWindow.document.detachEvent("onmouseup", contenteditable_event);
       iframe.contentWindow.document.attachEvent("onmouseup", contenteditable_event);
       iframe.contentWindow.document.detachEvent("ondrag", contenteditable_event);
       iframe.contentWindow.document.attachEvent("ondrag", contenteditable_event);
       iframe.contentWindow.document.close();
}

function contenteditable_viewsource_show() {
       // old viewsource functionality using text encoding
       var html = document.createTextNode(document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerHTML);
       document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerText = html.toString();
}

function contenteditable_viewsource_hide() {
       // old viewsource functionality using text encoding
       var html = document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerText;
       document.getElementsByTagName('iframe').item(contenteditable_focused).contentWindow.document.body.innerHTML = html.toString();
}

function contenteditable_specialcharacter(value) {
       if (value) {
               contenteditable_focused_document().execCommand('paste', false, value);
       }
}

function contenteditable_setAttribute(node, attribute, value) {
       contenteditable_removeAttribute(node, attribute);
       try {
               node.setAttribute(attribute, value);
       } catch(e) {
       }
       if ((node.nodeName == "INPUT") && (attribute == "type") && (contenteditable_getAttribute(node, attribute) != value)) {
               RegExp.global = true;
               RegExp.multiline = true;
               if (node.outerHTML.match(/^(<INPUT[^>]+type=)("[a-z]*")([^>]*>)$/gi)) {
                       node.outerHTML = node.outerHTML.replace(/^(<INPUT[^>]+type=)("[a-z]*")([^>]*>)$/gi, "$1"+value+"$3");
               } else if (node.outerHTML.match(/^(<INPUT[^>]+type=)([a-z]+)([^>]*>)$/gi)) {
                       node.outerHTML = node.outerHTML.replace(/^(<INPUT[^>]+type=)([a-z]+)([^>]*>)$/gi, "$1"+value+"$3");
               } else {
                       node.outerHTML = node.outerHTML.replace(/^(<INPUT[^>]+)([^>]*>)$/gi, "$1 type="+value+" $3");
               }
       }
       if ((node.nodeName == "A") && (attribute == "name") && (contenteditable_getAttribute(node, attribute) != value)) {
               node.removeAttribute("NAME");
               node.setAttribute("NAME", value);
       }
       if ((node.nodeName == "INPUT") && (attribute == "name") && (contenteditable_getAttribute(node, attribute) != value)) {
               node.removeAttribute("NAME");
               node.setAttribute("NAME", value);
       }
       if ((node.nodeName == "TEXTAREA") && (attribute == "name") && (contenteditable_getAttribute(node, attribute) != value)) {
               node.removeAttribute("NAME");
               node.setAttribute("NAME", value);
       }
       if ((node.nodeName == "SELECT") && (attribute == "name") && (contenteditable_getAttribute(node, attribute) != value)) {
               node.removeAttribute("NAME");
               node.setAttribute("NAME", value);
       }
}

function contenteditable_removeAttribute(node, attribute) {
       try {
               node.setAttribute(attribute, "");
               node.removeAttribute(attribute);
       } catch(e) {
       }
       if (attribute == "class") {
               node.removeAttribute("className");
       }
       if ((node.nodeName == "A") && (attribute == "name")) {
               node.removeAttribute("NAME");
       }
       if ((node.nodeName == "INPUT") && (attribute == "name")) {
               node.removeAttribute("NAME");
       }
       if ((node.nodeName == "TEXTAREA") && (attribute == "name")) {
               node.removeAttribute("NAME");
       }
       if ((node.nodeName == "SELECT") && (attribute == "name")) {
               node.removeAttribute("NAME");
       }
}

function contenteditable_getAttribute(node, attribute) {
// MSIE element.getAttribute(attribute) may not work properly - changes href/src from relative to absolute + returns unspecified default values
       var value = "";
       if (node) {
               if (node.outerHTML) {
                       RegExp.global = true;
                       RegExp.multiline = true;
                       var quotedValue = new RegExp('[ \t\r\n]'+attribute+'="([^"]*)"', 'gi');
                       var unquotedValue = new RegExp('[ \t\r\n]'+attribute+'=([^ >]*)', 'gi');
                       var flagAttribute = new RegExp('[ \t\r\n]('+attribute+')[ \t\r\n>]', 'gi');
                       var matches;
                       if (matches = quotedValue.exec(node.outerHTML)) {
                               value = matches[1] || "";
                       } else if (matches = unquotedValue.exec(node.outerHTML)) {
                               value = matches[1] || "";
                       } else if (matches = flagAttribute.exec(node.outerHTML)) {
                               value = matches[1] || "";
                       }
               } else if (node.getAttribute) {
                       value = node.getAttribute(attribute) || "";
               }
       }
       return value;
}

function contenteditable_insertimage_fix(src, border, alt, width, height, vspace, hspace, align, htmlclass, htmlid, onmouseover, onmouseout) {
// MSIE insertNodeAtSelection/pasteHTML may not work properly - changes src from relative to absolute + sets unspecified default values
       var img = null;
       var range = contenteditable_selection_range();
       range.moveStart("character",-1);
       var tags = contenteditable_focused_document().getElementsByTagName("img");
       for (var i=0; i<tags.length; i++) {
               try {
                       // create new range to use for comparison of current selection range to each tag element range
                       var element = contenteditable_createrange();
                       // set element range for this tag
                       element.moveToElementText(tags[i]);
                       // test if current selection range is equal to or part of this tag element range
                       if ((range.compareEndPoints("EndToStart",element) == 1) && (range.compareEndPoints("StartToEnd",element) == -1) && (range.compareEndPoints("StartToStart",element) != -1) && (range.compareEndPoints("EndToEnd",element) != 1)) {
                               img = tags[i];
                       }
               } catch(e) {
               }
       }
       if (img) {
               contenteditable_setAttribute(img, "src", src);
               if (border) {
                       contenteditable_setAttribute(img, "border", border);
               } else {
                       contenteditable_removeAttribute(img, "border");
               }
               if (alt) {
                       contenteditable_setAttribute(img, "alt", alt);
               } else {
                       contenteditable_removeAttribute(img, "alt");
               }
               if (width) {
                       contenteditable_setAttribute(img, "width", width);
               } else {
                       contenteditable_removeAttribute(img, "width");
               }
               if (height) {
                       contenteditable_setAttribute(img, "height", height);
               } else {
                       contenteditable_removeAttribute(img, "height");
               }
               if (vspace) {
                       contenteditable_setAttribute(img, "vspace", vspace);
               } else {
                       contenteditable_removeAttribute(img, "vspace");
               }
               if (hspace) {
                       contenteditable_setAttribute(img, "hspace", hspace);
               } else {
                       contenteditable_removeAttribute(img, "hspace");
               }
               if (align) {
                       contenteditable_setAttribute(img, "align", align);
               } else {
                       contenteditable_removeAttribute(img, "align");
               }
               if (onmouseover) {
                       contenteditable_setAttribute(img, "onMouseOver", onmouseover);
               } else {
                       contenteditable_removeAttribute(img, "onMouseOver");
               }
               if (onmouseout) {
                       contenteditable_setAttribute(img, "onMouseOut", onmouseout);
               } else {
                       contenteditable_removeAttribute(img, "onMouseOut");
               }
               if (htmlclass) {
                       contenteditable_setAttribute(img, "class", htmlclass);
               } else {
                       contenteditable_removeAttribute(img, "class");
               }
               if (htmlid) {
                       contenteditable_setAttribute(img, "id", htmlid);
               } else {
                       contenteditable_removeAttribute(img, "id");
               }
       }
}

function contenteditable_insertlink_fix(href, target, text, htmlclass, htmlid, onclick) {
// MSIE insertNodeAtSelection/pasteHTML may not work properly - changes src from relative to absolute + sets unspecified default values
       var a = null;
       var range = contenteditable_selection_range();
       range.moveStart("character",-1);
       var tags = contenteditable_focused_document().getElementsByTagName("a");
       for (var i=0; i<tags.length; i++) {
               try {
                       // create new range to use for comparison of current selection range to each tag element range
                       var element = contenteditable_createrange();
                       // set element range for this tag
                       element.moveToElementText(tags[i]);
                       // test if current selection range is equal to or part of this tag element range
                       if ((range.compareEndPoints("EndToStart",element) == 1) && (range.compareEndPoints("StartToEnd",element) == -1) && (range.compareEndPoints("StartToStart",element) != -1) && (range.compareEndPoints("EndToEnd",element) != 1)) {
                               a = tags[i];
                       }
               } catch(e) {
               }
       }
       if (a) {
               contenteditable_setAttribute(a, "href", href);
               if (target) {
                       contenteditable_setAttribute(a, "target", target);
               } else {
                       contenteditable_removeAttribute(a, "target");
               }
               if (htmlclass) {
                       contenteditable_setAttribute(a, "class", htmlclass);
               } else {
                       contenteditable_removeAttribute(a, "class");
               }
               if (htmlid) {
                       contenteditable_setAttribute(a, "id", htmlid);
               } else {
                       contenteditable_removeAttribute(a, "id");
               }
               if (onclick) {
                       contenteditable_setAttribute(a, "onclick", onclick);
               } else {
                       contenteditable_removeAttribute(a, "onclick");
               }
       }
}

function contenteditable_position(state) {
       var element = contenteditable_positionable();
       if (state) {
               contenteditable_execcommand('2D-Position', true);
       } else if (element) {
               if (element.style.position == "absolute") {
                       contenteditable_execcommand('2D-Position', false);
                       element.style.position = "";
                       element.style.top = "";
                       element.style.left = "";
                       element.style.zIndex = "";
               } else {
                       element.style.position = "absolute";
                       contenteditable_execcommand('2D-Position', true);
               }
               if (contenteditable_getAttribute(element, "style") == "") {
                       contenteditable_removeAttribute(element, "style");
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
               if (element.style && element.style.zIndex && (element.style.zIndex > -1000000)) {
                       element.style.zIndex = parseInt(element.style.zIndex) - 1;
               } else {
                       element.style.zIndex = -1;
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
               if (zIndex > -1000000) {
                       zIndex--;
               } else {
                       zIndex = -1000000;
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
       var element = contenteditable_positionable();
       if (element && element.style) {
               if (element.style.zIndex > 0) {
                       element.style.zIndex = -element.style.zIndex;
               } else if (element.style.zIndex == 0) {
                       element.style.zIndex = -1;
               }
       }
}

function contenteditable_event_delete(my_event) {
       // MSIE may be delete all content if deleting a table using Backspace
       if (my_event && my_event.keyCode && (my_event.keyCode == 8)) {
               if ((my_event.type == "keydown") || (my_event.type == "keypress")) {
                       var container = contenteditable_selection_container();
                       var parentnode = container.parentNode;
                       if ((container.nodeName == "TBODY") || (container.nodeName == "THEAD") || (container.nodeName == "TFOOT")) {
                               contenteditable_event_stop(my_event);
                               contenteditable_undo_save();
                               parentnode.removeChild(container);
                               if ((parentnode.nodeName == "TABLE") && (parentnode.childNodes && parentnode.childNodes.length == 0)) {
                                       parentnode.parentNode.removeChild(parentnode);
                               }
                       } else if (contenteditable_selection_range_control() && ((container.nodeName == "HR") || (container.nodeName == "IFRAME") || (container.nodeName == "DIV") || (container.nodeName == "IMG") || (container.nodeName == "TABLE") || (container.nodeName == "INPUT") || (container.nodeName == "TEXTAREA") || (container.nodeName == "SELECT"))) {
                               // MSIE may return wrong container/parentnode for SELECT tags so check container nodeName
                               contenteditable_event_stop(my_event);
                               contenteditable_undo_save();
                               parentnode.removeChild(container);
                       } else if (contenteditable_selection_range_control()) {
                               // MSIE may return wrong container/parentnode for SELECT tags so ignore delete
                               contenteditable_event_stop(my_event);
                       }
               }
       }
}

function contenteditable_event_enter_p() {
       var range = contenteditable_selection_range();
       if (range.text == range.htmlText) { // #text node
               var parentNode = contenteditable_selection_range_parentNode();
               var oldP = parentNode.firstChild;
               var preP = "&nbsp;";
               var postP = "&nbsp;";
               var preRange = contenteditable_createrange();
               var postRange = contenteditable_createrange();
               if (preRange.moveToElementText) {
                       preRange.moveToElementText(parentNode);
                       while (range.compareEndPoints("StartToEnd",preRange) == -1) {
                               preRange.moveEnd("character",-1);
                       }
                       postRange.moveToElementText(parentNode);
                       while (range.compareEndPoints("EndToStart",postRange) == 1) {
                               postRange.moveStart("character",1);
                       }
                       preP = preRange.text || "&nbsp;";
                       postP = postRange.text || "&nbsp;";
               }
               if (parentNode.nodeName == "P") {
                       var newP = contenteditable_focused_contentwindow().document.createElement("P");
                       newP.innerHTML = preP;
                       oldP.parentNode.parentNode.insertBefore(newP, oldP.parentNode);
                       oldP.nodeValue = postP;
                       range = contenteditable_selection_range();
                       range.moveToElementText(oldP.parentNode);
                       range.collapse(1);
                       range.select();
               } else {
                       var newP = contenteditable_focused_contentwindow().document.createElement("P");
                       var newP2 = contenteditable_focused_contentwindow().document.createElement("P");
                       newP.innerHTML = preP;
                       newP2.innerHTML = postP;
                       oldP.parentNode.insertBefore(newP, oldP);
                       oldP.parentNode.insertBefore(newP2, oldP.nextSibling);
                       oldP.parentNode.removeChild(oldP);
                       range = contenteditable_selection_range();
                       range.moveToElementText(newP2);
                       range.collapse(1);
                       range.select();
               }
       } else {
               contenteditable_pasteContent("<p>");
       }
}