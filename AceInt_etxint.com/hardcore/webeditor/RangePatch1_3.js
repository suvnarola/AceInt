/*
	File:		RangePatch1_3.js
	Name:		Mozilla Range Implementation Patch
	Version:	1.3
	
	Author:		Jeffrey M. Yates
	Contact:	PBWiz@PBWizard.com
	Home:		http://www.pbwizard.com
	Date:		25 March 2001
	
	References:
		Visit http://pbwizard.com/Moz%20Patches/index.htm for directions on use.
			(Note:  should be posted by 26 March, 2001)
	
	======================================================================================
	This file is used for "patching" Mozilla's implementation of W3C's Range object.
	Please contact me if you run into any errors in my code or inproper implementation
	of the W3C DOM Level 2 Recomendation.  If you find a good use for this code I would 
	like to provide a link to the page on my site as a reference for those wishing to 
	learn how to implement it.
	
	======================================================================================
	Terms of use:
		Feel free to distribute this in it's entirity including the comments.  If you wish
		to modify the code please send me a copy via the contact information above.
		
	
	Version Update
	----------------------------------------------------------
	A.	This version finishes up the Range patch to cover the XML side of the range
		patch including namespaces.
		
	B.	Added the read-only property of Node.xml to all nodes.
	
	C.  Added the Range.xmlText property.
	
	D.  Combined all markup generating routines into one function call with the
		exception of the innerHTML (which has special conciderations).
	
	
	The following Mozilla properties/methods are fixed using this patch:
	----------------------------------------------------------
	Range.extractContents();
	Range.cloneContents();
	Range.insertNode( newNode );
	Range.surroundContents( newParentNode );
	Range.deleteContents();
	HTMLElement.innerHTML;

	The following methods and properties extend the Mozilla DOM.
	--------------------------------------------------------------------
	Range.htmlText;					//read-only property
	Range.xmlText;					//read-only property
	Range.text;
	HTMLElement.outerHTML;			
	HTMLElement.canHaveChildren;	//read-only property
	Range.startContainerBranch		//read-only property
	Range.endContainerBranch;		//read-only property
	Node.xml;						//read-only property
	
	Known unresolved issues
	--------------------------------------------------------------------
	None at this time
	

	Issues for Emulating IE's TextRange object
	--------------------------------------------------------------------
	Need a method of getting and setting the text only value of the Range and 
	Elements.  I do not see a great need for this, but IE has it and many 
	developers use it.
	
	Need a method on the Element interface that checks to see if an element 
	can have a specific node as it's child without actually inserting the node.
	This may need to be generalized to all nodes.
	

	Issues for using the Range object for a rich-text editor
	--------------------------------------------------------------------
	Create a separate method for Range.rangeExtraction that deals with "zipping" up
	the DOM structure.  This idea is important for dealing with HTML documents.
	This method corrisponds to the cut operation.  Also a corisponding method for 
	the paste operation is needed.

*/

/*
	Below are the error conditions that I cannot check at this time.
	
	deleteContents 
		DOMException
			NO_MODIFICATION_ALLOWED_ERR: Raised if any portion of the content of the Range 
				is read-only or any of the nodes that contain any of the content of the Range 
				are read-only.
				(cannot test at this time, requires a XHTML or XML document with a DocType
					in order to get a read-only sub-tree)
		
	extractContents 
		DOMException
			 NO_MODIFICATION_ALLOWED_ERR: Raised if any portion of the content of the Range 
				is read-only or any of the nodes which contain any of the content of the 
				Range are read-only.
				(cannot test at this time, requires a XHTML or XML document with a DocType
					in order to get a read-only sub-tree)

	insertNode 
		DOMException
			NO_MODIFICATION_ALLOWED_ERR: Raised if an ancestor container of the start of 
				the Range is read-only.
				(cannot test at this time, requires a XHTML or XML document with a DocType
					in order to get a read-only sub-tree)

	surroundContents 
		DOMException
			NO_MODIFICATION_ALLOWED_ERR: Raised if an ancestor container of either 
				boundary-point of the Range is read-only.
				(cannot test at this time, requires a XHTML or XML document with a DocType
					in order to get a read-only sub-tree)
*/

var __debug__ = true;

function ApplyRangePatch(initDoc){
		
	if(window.navigator.userAgent.indexOf("Gecko") > 1){
		//The following code is used to "Patch" Mozilla's implementation of the range object
		//so that it follows the W3C Recomendation, as well as fix Mozilla's extension to 
		//it as well.

		//These variables are used to test to make sure that the properties/methods
		//need to be patched.
		var console = "";
		var div = initDoc.createElement("div");
		div.appendChild(initDoc.createTextNode("test successful"));
		var range = initDoc.createRange();
		range.selectNodeContents(div);  //this initializes the range so it can be tested
		
		
		//test the Range interface
		if( !Range ){
			console = "exposed Range interface\n";
			Range = range.constructor;
		}
		
		
		if(Range.RangePatched){
			initDoc.RangePatched = true;
			return;
		}
		
		
	//below is the implementation of the patch
	//Note: all properties/methods not attached directly to the Range object
	//		are patches to other classes needed for calculations used in the
	//		range patch.

		if( initDoc + "" == "[object HTMLDocument]" ){
		//HTML only Properties

			//test the HTMLElement interface first so that it will return the proper value
			if( div.innerHTML != "test successful" ){
				HTMLElement.prototype.__defineSetter__("innerHTML", SetInnerHTML );
				HTMLElement.prototype.__defineGetter__("innerHTML", GetInnerHTML );
				console += "HTMLElement.innerHTML patched\n";
			}

			if( div.outerHTML != "<DIV>test successful</DIV>" ){
				HTMLElement.prototype.__defineSetter__("outerHTML", SetOuterHTML );
				HTMLElement.prototype.__defineGetter__("outerHTML", GetOuterHTML );
				console += "HTMLElement.outerHTML patched\n";
			}
	
			if( !div.canHaveChildren ){
				HTMLElement.prototype.__defineGetter__("canHaveChildren", CanElementHaveChildNodes );
				console += "HTMLElement.canHaveChildren patched\n";
			}

			if( range.htmlText + "" == "undefined" ){
				Range.prototype.__defineGetter__("htmlText", GetRangeHTML); //read-only
				console += "Range.htmlText patched\n";
			}
	
		}

		//XML Properties
		if( div.xml != "<DIV>test successful</DIV>" ){
			Node.prototype.__defineGetter__("xml", GetXML );
			console += "need Node.xml patched\n";
		}
		if( document.firstChild.document != document ){
			Node.prototype.__defineGetter__("document", GetNodesDocument );
			console += "Node.document patched\n";
		}
		if( range.xmlText + "" == "undefined" ){
			Range.prototype.__defineGetter__("xmlText", GetRangeXML); //read-only
			console += "Range.xmlText patched\n";
		}

		//Properties and Methods for DOM Range
		if( range.startContainerBranch + "" == "undefined" ){
			Range.prototype.__defineGetter__("startContainerBranch", GetStartContainerBranch )
			console += "Range.startContainerBranch patched\n";
		}
		if( range.endContainerBranch + "" == "undefined" ){
			Range.prototype.__defineGetter__("endContainerBranch", GetEndContainerBranch )
			console += "Range.endContainerBranch patched\n";
		}
		if( range.text + "" == "undefined" ){
			Range.prototype.__defineGetter__("text", Range.prototype.toString )  //read-only at this time
			console += "Range.text patched\n";
		}
		if( Range.START_TO_START + "" == "undefined" ){
			Range.START_TO_START = 0;
			Range.START_TO_END = 1;
			Range.END_TO_END = 2;
			Range.END_TO_START = 3;
			console += "Range comparison constants patched\n";
		}
		if( Range.NODE_BEFORE + "" == "undefined" ){
			Range.NODE_BEFORE = 0;
			Range.NODE_AFTER = 1;
			Range.NODE_BEFORE_AND_AFTER = 2;
			Range.NODE_INSIDE = 3;
			console += "nsRange node comparison results constants patched\n";
		}
		if( Range.POINT_EQUAL + "" == "undefined" ){
			Range.POINT_BEFORE = 1;
			Range.POINT_EQUAL = 0;		//for comparisons of nodes this is inside
			Range.POINT_AFTER = -1;
			console += "nsRange point comparison results constants patched\n";
		}

		//test for range methods
		extractFrag = range.createContextualFragment("<b>this is a fragment</b> use to test <b>the extraction method</b>");
try{
		range.setStart( extractFrag.firstChild.firstChild, 5 );
		range.setEnd( extractFrag.lastChild.lastChild, 15 );
}catch(e){}
		if( range.extractContents )	try{ range.extractContents(); } catch(e){}
		if( !extractFrag.firstChild.firstChild || extractFrag.firstChild.firstChild.data != "this " ){
			Range.prototype.extractContents = ExtractRangeContents;
			console += "Range.extractContents patched\n";
		}
	
		extractFrag = range.createContextualFragment("<b>this is a fragment</b> use to test <b>the extraction method</b>");
try{
		range.setStart( extractFrag.firstChild.firstChild, 5 );
		range.setEnd( extractFrag.lastChild.lastChild, 15 );
}catch(e){}
		if( range.deleteContents )	try{ range.deleteContents(); } catch(e){}
		if( !extractFrag.firstChild.firstChild || extractFrag.firstChild.firstChild.data != "this " ){
			Range.prototype.deleteContents = DeleteRangeContents;
			console += "Range.deleteContents patched\n";
		}
	
		range.selectNodeContents(initDoc.lastChild);  //this initializes the range so it can be tested
		extractFrag = range.createContextualFragment("<b>this is a fragment</b> use to test <b>the extraction method</b>");
		range.setStart( extractFrag.firstChild.firstChild, 5 );
		range.setEnd( extractFrag.lastChild.lastChild, 15 );
		var cloneFrag = extractFrag;
		if( range.cloneContents )	try{ cloneFrag = range.cloneContents(); } catch(e){}
		if( cloneFrag.firstChild.firstChild.data != "is a fragment" ) {
			Range.prototype.cloneContents = CloneRangeContents;
			console += "Range.cloneContents patched\n";
		}
	
		extractFrag = range.createContextualFragment("<b>this is a fragment</b> use to test <b>the extraction method</b>");
		range.selectNode(extractFrag.childNodes[1])
		var testEl = initDoc.createElement("i");
		if( range.surroundContents )	try{ range.surroundContents(testEl); } catch(e){}
		if( !extractFrag.childNodes[1] || extractFrag.childNodes[1].tagName != "I" ){
			Range.prototype.surroundContents = SurroundRangeContentsByNode;
			console += "Range.surroundContents patched\n";
		}
	
		extractFrag = range.createContextualFragment("<b>this is a fragment</b> use to test <b>the extraction method</b>");
		range.selectNode(extractFrag.childNodes[1])
		testEl = initDoc.createElement("i");
		if( range.insertNode )	try{ range.insertNode(testEl); } catch(e){}
		if( !extractFrag.childNodes[1] || extractFrag.childNodes[1].tagName != "I" ){
			Range.prototype.insertNode = InsertNodeAtStartOfRange;
			console += "Range.insertNode needs patched\n";
		}
		
		if( console == "" ) console = "no patches needed";
		if( __debug__ ) alert( console );

		initDoc.RangePatched = true;
		Range.RangePatched = true;
		return;
	}
	
	//this function is used for when adding properties, but have not implemented them yet.
	//Alerts the code writer that they are using something that is not implemented.
	function notImplementedYet(propertyName, objectName){
		alert( "The " + propertyName + " property or method of the " + objectName 
				+ " object is not implemented yet.");
	}
	
/*
	function NodeContainsNode( node ){
	//Node.prototype.contains = NodeContainsNode;
		var ptr = node;
		while( ptr.parentNode ){
			if( ptr.paretnNode == this ) return true;
			ptr = ptr.parentNode;	
		}
		return false;
	}
	
	function GetParentIndex(){
	//Node.prototype.__defineGetter__( "parentIndex", GetParentIndex );
		if( this.parentNode == null ) return null;
		var siblings = this.parentNode.childNodes;
		var len = siblings.length;
		for( var i=0; i<len; i++ ){
			if( siblings[i] == this ) return i;
		}
		throw new Error("DOM Structure Unstable");
	}
	function GetRangeRootContainer(){
	//Range.prototype.__defineGetter__( "rootContainer", GetRangeRootAncestor );
		return this.commonAncestorContainer.rootAncestor;
	}
	function GetRootAncestor(){
	//Node.prototype.__defineGetter__( "rootAncestor", GetRootAncestor );
		var ptr = this;
		while( ptr.parentNode )
			ptr = ptr.parentNode;	
		return ptr;
	}
	function CompareBoundaryPoints( how, srcRange ){
	//Range.prorotype.compareBoundaryPoints = CompareBoundaryPoints;
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
			var test_INVALID_STATE_ERR = srcRange.endContainer;
			var test_INVALID_STATE_ERR = srcRange.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		switch( how ){
		case Range.START_TO_START:
			return comparePositions(srcRange.startContainer, srcRange.startOffset, 
										this.startContainer, this.startOffset );
		case Range.END_TO_START:
			return comparePositions(srcRange.endContainer, srcRange.endOffset, 
										this.startContainer, this.startOffset );
		case Range.START_TO_END:
			return comparePositions(srcRange.startContainer, srcRange.startOffset, 
										this.endContainer, this.endOffset );
		case Range.END_TO_END:
			return comparePositions(srcRange.endContainer, srcRange.endOffset, 
										this.endContainer, this.endOffset );
		}
	}
	function IntersectsNode( node ){
	//Range.prorotype.intersectsNode = IntersectsNode;
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		//if the start of the node is before the range end and
		//   the end of the node is after the range start then
		//   the node intersects the range
		if( node == this.commonAncestorContainer ) return true;
		var startOfNodeToEndOfRange = comparePositions(
							node.parentNode, node.parentIndex, 
							this.endContainer, this.endOffset );
		var endOfNodeToStartOfRange = comparePositions(
							node.parentNode, node.parentIndex + 1, 
							this.startContainer, this.startOffset );
		if( startOfNodeToEndOfRange == -1 && endOfNodeToStartOfRange== 1 ) 
			return true;
		return false;
	}
	function ComparePoint( node, offset ){
	//Range.prorotype.comparePoint = ComparePoint;
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		//-1 before range, 0 in range, 1 after range
		
		//compare the start of the range to the point
		if( comparePositions(node, offset, this.startContainer, this.startOffset ) == -1 ) 
			return -1;
		
		//compare the end of the range to the point
		if( comparePositions( node, offset, this.endContainer, this.endOffset ) == 1 ) 
			return 1;
		
		//the point is not before or after the range so must be within the range.
		return 0;
	
	}
	function IsPointInRange( node, offset ){
	//Range.prorotype.isPointInRange = IsPointInRange;
		return ( this.ComparePoint( node, offset ) == 0 );
	}
	function CompareNode( node ){
	//Range.prorotype.compareNode = CompareNode;
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		var startOfNodeToStartOfRange = comparePositions(
							node.parentNode, node.parentIndex, 
							this.startContainer, this.startOffset );
		var endOfNodeToEndOfRange = comparePositions(
							node.parentNode, node.parentIndex + 1, 
							this.endContainer, this.endOffset );
		if( startOfNodeToStartOfRange == -1 && endOfNodeToEndOfRange == 1 )
			return 2;  //Range.NODE_BEFORE_AND_AFTER;
		if( startOfNodeToStartOfRange == -1 ) 
			return 0;  //Range.NODE_BEFORE;
		if( endOfNodeToEndOfRange == 1 )
			return 1;  //Range.NODE_AFTER;
		return 3; //Range.NODE_INSIDE;
	}
*/
	function GetNodesDocument(){
		//Node.prototype.__defineGetter__("document", GetNodesDocument );
		
		//This property follows the node chain to the top parent.  If the top parent is
		//a document node then return it, else return null.
		var ptr = this;
		while( ptr.parentNode ) ptr = ptr.parentNode;
		if( ptr.nodeType != Node.DOCUMENT_NODE ) return null;
		return ptr;
	}
	
	function GetXML(){
		//Node.prototype.__defineGetter__("xml", GetXML );
		return calculateMarkup(this, true);
	}
	
	function GetRangeXML(){
		//Range.prototype.__defineGetter__("xmlText", GetRangeXML); //read-only
		return GetRangeMarkup( this, true );
	}
	
	function GetStartContainerBranch(){
		//Range.prototype.__defineGetter__("startContainerBranch", GetStartContainerBranch )
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		return calculateContainerBranch(this.startContainer, this );
	}
	
	function GetEndContainerBranch(){
		//Range.prototype.__defineGetter__("endContainerBranch", GetEndContainerBranch )
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		return calculateContainerBranch(this.endContainer, this );
	}

	function GetInnerHTML( ){
		//HTMLElement.prototype.__defineGetter__("innerHTML", GetInnerHTML );
		//the calculation is recursive so make it an external function call.
		return calculateInnerHTML( this );
	}
	
	function SetInnerHTML( html ){
		//HTMLElement.prototype.__defineSetter__("innerHTML", SetInnerHTML );
		
		//all nodes generated by this function should have the same document
		//as it's generator.  W3C recomendation says that if a node is
		//inserted from one document into another document to throw an error.

		switch( this.tagName ){
			//read only if COL, COLGROUP, FRAMESET, HEAD, HTML, STYLE, TABLE, 
			//TBODY, TFOOT, THEAD, title, TR
			case "COL":		case "COLGROUP":	case "FRAMESET":	case "HEAD":
			case "HTML":	case "STYLE":		case "TABLE":		case "TBODY":
			case "TFOOT":	case "THEAD":		case "TITLE":		case "TR":
			return;		
		}

		//note: doc should be ownerDocument, but it has been improperly
		//applied by Mozilla
		var doc = this.document;
		var el = this;
		
		//test for orphaned node
		if( !doc ) doc = document;
		
		//this code depends upon the range patch being installed.
		//the range patch is in file RangePatch.js
		if( !doc.RangePatched ) ApplyRangePatch(doc);
		
		//create a range object so you can get access to the isValidFragment
		//method and the createContextualFrament method.
		var range = doc.createRange();
		
		//a range object MUST be initialized before it's methods can be used.
		range.selectNodeContents( el );
		
		//create a documentFragment based upon the html string suppled
		var frag = range.createContextualFragment(html);
		
		//remove all of the children of the node
		var ptr = this.lastChild;
		while( ptr ) {
			this.removeChild( ptr );
			ptr = this.lastChild;
		}
		
		//insert the new children into the node
		this.appendChild( frag );
		
		return html;
	}
	
	function GetOuterHTML(){
		//HTMLElement.prototype.__defineGetter__("outerHTML", GetOuterHTML );
		//the calculation is recursive so make it an external function call.
		return calculateMarkup( this, false );
	}
	
	function SetOuterHTML( html ){
		//HTMLElement.prototype.__defineSetter__("outerHTML", SetOuterHTML );
		
		//all nodes generated by this function should have the same document
		//as it's generator.  W3C recomendation says that if a node is
		//inserted from one document into another document to throw an error.
		
		switch( this.tagName ){
			//read only if CAPTION, COL, COLGROUP, FRAMESET, HEAD, HTML, 
			//TBODY, TD, TFOOT, TH, THEAD, or TR
			case "CAPTION":	case "COL":		case "COLGROUP":	case "FRAMESET":
			case "HTML":	case "TBODY":	case "TD":			case "TFOOT":
			case "TH":		case "THEAD":	case "TR":			case "HEAD":
			return;		
		}
		
		//note: doc should be ownerDocument, but it has been improperly
		//applied by Mozilla
		var doc = this.document;
		var el = this;
		
		//if the node does not have a parent all that happens is the children
		//of the node are deleted.  Skip the rest if it does not have a parent.
		if( this.parentNode ){
			//test for orphaned node - not attached to a document
			if( !doc ) doc = document;

			//this code depends upon the range patch being installed.
			//on this nodes document
			if( !doc.RangePatched ) ApplyRangePatch(doc);
		
			//create a range object so you can get access to the isValidFragment
			//method and the createContextualFrament method.
			var range = doc.createRange();
		
			//a range object MUST be initialized before it's methods can be used.
			range.setStartBefore(el);

			//create a documentFragment based upon the html string suppled
			var frag = range.createContextualFragment( html );
		
			//replace this element with the new node
			this.parentNode.replaceChild( frag, this );
		} 
		//when you change the outerHTML of a node all of it's children are
		//destroyed.
		var ptr = this.firstChild;
		while( ptr ){
			this.removeChild( ptr );
			ptr = this.firstChild;
		}
		return html;		
	}
	
	function CanElementHaveChildNodes(){
		//HTMLElement.prototype.__defineGetter__("canHaveChildren", CanElementHaveChildNodes );
		
		//This read-only property indicates whether an element can have
		//child nodes
		switch( this.tagName ){
		//These are the elements that cannot have child nodes
		//and are thus by definition empty elements
		case "AREA":	case "BASE":	case "BASEFONT":
		case "BR":		case "COL":		case "FRAME":
		case "HR":		case "IMG":		case "INPUT":
		case "ISINDEX":	case "LINK":	case "META":
		case "PARAM":
			return false;
		}
		return true;
	}

	function GetRangeHTML(){
		//Range.prototype.__defineGetter__("htmlText", GetRangeInnerHTML);
		return GetRangeMarkup( this, false );
	}

	function GetRangeMarkup(inRange, isXML){
		//called from getRangeHTML and GetRangeXML
		try{
			var test_INVALID_STATE_ERR = inRange.endContainer;
			var test_INVALID_STATE_ERR = inRange.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}
		
		var retVal = "";
		var ptr = inRange.commonAncestorContainer.firstChild;
		while( ptr ){
			retVal += calculateMarkup( ptr , false, inRange );
			ptr = ptr.nextSibling;
		}
		return retVal;
	}
	
	function CloneRangeContents(){
		//Range.prototype.cloneContents = CloneRangeContents;
		//Mozilla has not implemented this method yet.  This code will simulate the 
		//required functionality
		var common = this.commonAncestorContainer;
		var clone = initDoc.createDocumentFragment();
		if( this.commonAncestorContainer.nodeType == Node.CDATA_SECTION_NODE ||
			this.commonAncestorContainer.nodeType == Node.TEXT_NODE){
			//if the entire range is within a Text or CDATASection node then you do not
			//have to "walk" through the commonAncestorContainer's tree.
			copyContents( common, clone, this );
			return clone;
		}
		//Walk through the commonAncestorContainer's tree and copy the pertinant nodes, or
		//sections of nodes that fall within the range.
		var nextPtr, ptr = this.commonAncestorContainer.firstChild;
		while( ptr ){
			nextPtr = ptr.nextSibling;
			//copyContents does not modify the DOM so you could use ptr = ptr.nextSibling
			//after the copyContent function call, but I chose not to so the functionality 
			//looks the same as extractContents implementation.
			copyContents( ptr, clone, this);
			ptr = nextPtr;
		}
		return clone;
	}
	
	function DeleteRangeContents(){
		//Range.prototype.deleteContents = DeleteRangeContents;
		//This method is the same as extractContents but without returning the documentFragement.
		//Since the overhead is small using a DocumentFragment I am not worried about performance
		//here.
		this.extractContents();
	}
	
	function ExtractRangeContents(){
		//Range.prototype.extractContents = ExtractRangeContents;
		//Mozilla has not implemented this method yet.  This code will simulate the 
		//required functionality
		var common = this.commonAncestorContainer;
		var contents = initDoc.createDocumentFragment();
		if( this.commonAncestorContainer.nodeType == Node.CDATA_SECTION_NODE ||
			this.commonAncestorContainer.nodeType == Node.TEXT_NODE){
			//if the entire range is within a Text or CDATASection node then you do not
			//have to "walk" through the commonAncestorContainer's tree.
			extractContents( common, contents, this );
			return contents;
		}
		//Walk through the commonAncestorContainer's tree and remove the pertinant nodes, or
		//sections of nodes that fall within the range.
		var nextPtr, ptr = this.commonAncestorContainer.firstChild;
		while( ptr ){
			nextPtr = ptr.nextSibling;
			//you must find the next sibling before calling extractContents since it changes
			//the DOM structure.
			extractContents( ptr, contents, this);
			ptr = nextPtr;
		}
		return contents;
	}

	function InsertNodeAtStartOfRange( newNode ){
		//Range.prototype.insertNode = InsertNodeAtStartOfRange;
		//Mozilla has not implemented this method yet.  This code will simulate the 
		//required functionality

		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}

		var node=newNode;
		//check for invalid node type
		switch( node.nodeType ){
			case Node.DOCUMENT_NODE:
			case Node.ATTRIBUTE_NODE:
			case Node.ENTITY_NODE:
			case Node.NOTATION_NODE:
				throw new Error("INVALID_NODE_TYPE_ERR")
				break;
			case Node.DOCUMENT_FRAGMENT_NODE:
				var firstChild = node.firstChild;
		}
		if( this.startContainer.nodeType == Node.CDATA_SECTION_NODE ||
			this.startContainer.nodeType == Node.TEXT_NODE){
			//The start of the range is within a Text or CDATASection node.  The node
			//must be split so that the new node can be inserted.
			this.startContainer.parentNode.insertBefore(
				node, this.startContainer.splitText( this.startOffset));
		} else if( this.startOffset == this.startContainer.childNodes.length){
			//find the insertion point in the DOM and insert the node.
			
			//the insertion point is at the end of the startContainer node so just 
			//append the node to the startContainer node.
			this.startContainer.appendChild( node );
		} else {
			//the insertion point is NOT at the end of the startContainer node.  Insert
			//the node before the node referenced by the startOffset property
			this.startContainer.insertBefore( 
				node, this.startContainer.childNodes.item(this.startOffset) );
		}
		if( node.nodeType == Node.DOCUMENT_FRAGMENT_NODE) node = firstChild;
		try{
			this.setStart( node, 0 );
		}catch(err){}
	}
		
	function SurroundRangeContentsByNode( newParent ){
		//Range.prototype.surroundContents = SurroundRangeContentsByNode;
		//The only way to surround the range by an element is to extract the range from
		//the tree, insert the node, and append the extracted range to this node.
		
		var node = newParent;
		try{
			var test_INVALID_STATE_ERR = this.endContainer;
			var test_INVALID_STATE_ERR = this.startContainer;
		}catch(e){throw new Error("INVALID_STATE_ERR")}

		switch( node.nodeType ){
			case Node.DOCUMENT_NODE:
			case Node.ATTRIBUTE_NODE:
			case Node.ENTITY_NODE:
			case Node.NOTATION_NODE:
				throw new Error("INVALID_NODE_TYPE_ERR")
		}
		
		if( this.endContainer.nodeType == Node.TEXT_NODE){
			if( this.endOffset < this.endContainer.length )
				var newText = this.endContainer.splitText( this.endOffset );
			this.setEndAfter( this.endContainer );
		}
		if( this.startContainer.nodeType == Node.TEXT_NODE){
			if( this.endOffset > 0 ){
				var newText = this.startContainer.splitText( this.startOffset );
				this.setStartBefore( newText );
			} else 
			this.setStartBefore( this.startContainer );
		}
		var startContainer = this.startContainer;
		var endContainer = this.endContainer;
		
		//Since the text nodes have been split up into the parts included in the
		//range and parts not included in the range (and totally selected) then
		//if any node is not totally selected then there is an error.
		
		//index 0 of startContainerBranch and endContainerBranch 
		//is the commonAncestorContainer which by definition will most
		//likely be partially selected.  This is the only node involved in
		//the surroundContents method that can be so.  If any other Node
		//involved in the rage is partially selected then throw an error.
		var i, len, branchNode;
		var startBranch = this.startContainerBranch;
		var endBranch = this.endContainerBranch;
		for( i=0, len=startBranch.length; i<len-1; i++ )
			if( this.compareNode( startBranch[i] ) != Range.NODE_INSIDE ){
				throw new Error("BAD_BOUNDARYPOINTS_ERR");
			}
		for( i=1, len=endBranch.length; i<len; i++ )
			if( this.compareNode( endBranch[i] ) != Range.NODE_INSIDE ){
				throw new Error("BAD_BOUNDARYPOINTS_ERR");
			}
		
		//move the contents out of the range;
		frag = this.extractContents();
		
		//insert the "surroundContents" node.
		//Note:  insertNode method re-defines the range so it contains all of the content
		//		including the "surroundContents" node.
		try{
			this.insertNode(node);
			node.appendChild( frag );
		} catch(e){
			//error inserting node or its contents.  Reset back to the way it was.
			this.extractContents();
			this.insertNode(frag);
			throw e;
		}
		
	}

	function copyContents( fromNode, appendToNode, inRange ){
		//this method is used as a recursive copy function for 
		//GetRangeInnerHTML and CloneRangeContents functions.
			
	//	Arguments:
	//		fromNode - a reference to a node to be tested to see if it
	//			is located within the range.  If it is totally within the
	//			range the node and it's contents are cloned and the
	//			cloned node is appended to appendToNode.  If the node is
	//			partially selected then the node is cloned and appended and 
	//			this function is recursively called with each of it's child
	//			nodes (if it is an element), or it is split and the portion
	//			that is part of the range is cloned (if it is a Text or
	//			CDATASection node).
	//		appendToNode - a reference to a node to have the copy of the 
	//		    range appended to.  This reference changes as you walk through
	//		    the tree to point to the current insertion point.
	//			I require an appendToNode to be passed in instead of
	//			generating and returning a DocumentFragment due to the
	//			overhead that would add to the function.
	//		inRange - a reference to a range object that the contents are
	//			to be cloned from.
		
		//find out if the node is totally or partially located within the range
		if( !inRange.intersectsNode( fromNode ) ) return;
		var compare = inRange.compareNode( fromNode );
		var clonedNode;
		switch( compare ){
		case Range.NODE_INSIDE:
			//the node is totally selected by the range, clone it and all of
			//it's contents.
			appendToNode.appendChild( fromNode.cloneNode(true));
			return;
		case Range.NODE_BEFORE_AND_AFTER:
			//the node is partially selected by the range.  It extends before
			//and after the range.
			if( fromNode.nodeType == Node.CDATA_SECTION_NODE ||
				fromNode.nodeType == Node.TEXT_NODE){
				//split the Text/CDATASection node into the 3 sections (before,
				//inside, after) and clone the inside contents
				clonedNode = fromNode.cloneNode(false);
				clonedNode.splitText( inRange.endOffset );
				clonedNode = clonedNode.splitText( inRange.startOffset );
				appendToNode.appendChild( clonedNode );
				return;
			}
		case Range.NODE_BEFORE:
			//the node is partially selected by the range.  It extends before
			//the range.
			if( fromNode.nodeType == Node.CDATA_SECTION_NODE ||
				fromNode.nodeType == Node.TEXT_NODE){
				//split the Text/CDATASection node into the 2 sections (before,
				//inside) and clone the inside contents
				clonedNode = fromNode.cloneNode(false);
				clonedNode = clonedNode.splitText( inRange.startOffset );
				appendToNode.appendChild( clonedNode );
				return;
			}
		case Range.NODE_AFTER:
			//the node is partially selected by the range.  It extends
			//after the range.
			if( fromNode.nodeType == Node.CDATA_SECTION_NODE ||
				fromNode.nodeType == Node.TEXT_NODE){
				//split the Text/CDATASection node into the 2 sections 
				//(inside, after) and clone the inside contents
				clonedNode = fromNode.cloneNode(false);
				clonedNode.splitText( inRange.endOffset );
				appendToNode.appendChild( clonedNode );
				return;
			}
			//the node is partially selected and is not a Text/CDATASection
			//node.  Clone this node (but not it's contents) and iterate 
			//through each of it's children sending them recurivaly back
			//into this function.
			clonedNode = fromNode.cloneNode(false);
			appendToNode.appendChild( clonedNode );
			var ptr = fromNode.firstChild;
			while( ptr ){
				//pass in the clonedNode as the new appendToNode argument
				copyContents( ptr, clonedNode, inRange);
				ptr = ptr.nextSibling;
			}
			return;
		}
	}
		
	function extractContents( fromNode, appendToNode, inRange ){
		//this method is used as a recursive extract function for the
		//extractContents function.
			
	//	Arguments:
	//		fromNode - a reference to a node to be tested to see if it
	//			is located within the range.  If it is totally within the
	//			range the node and it's contents are extracted and appended
	//			to the appendToNode.  If the node is
	//			partially selected then the node is cloned (but not it's contents)
	//			and appended to the appendToNode and it's children are recursively 
	//			passed into a call to this function (if it is an element), 
	//			or it is split and the portion that is part of the range is 
	//			remove and appended to the appendToNode (if it is a Text or 
	//			CDATASection node).
	//		appendToNode - a reference to a node to have the range contents 
	//			appended to.  This reference changes as you walk through
	//		    the tree to point to the current insertion point.
	//			I require an appendToNode to be passed in instead of
	//			generating and returning a DocumentFragment due to the
	//			overhead that would add to the function.
	//		inRange - a reference to a range object that the contents are
	//			to be extracted from.

		//find out if the node is totally or partially located within the range
		if( !inRange.intersectsNode( fromNode ) ) return;
		var compare = inRange.compareNode( fromNode );
			
		var clonedNode;
		switch( compare ){
		case Range.NODE_INSIDE:
			//the node is totally selected by the range, extract it's entire
			//contents.
			appendToNode.appendChild( fromNode.parentNode.removeChild(fromNode));
			return;
		case Range.NODE_BEFORE_AND_AFTER:
			//the node is partially selected by the range.  It extends before
			//and after the range.
			if( fromNode.nodeType == Node.CDATA_SECTION_NODE ||
				fromNode.nodeType == Node.TEXT_NODE){
				//split the Text/CDATASection node into the 3 sections (before,
				//inside, after) and extract the inside contents
				var before = fromNode.cloneNode( true );
				var inside = fromNode.cloneNode( true );
				var after = fromNode;
				before.deleteData( inRange.startOffset, before.length );
				inside.deleteData( inRange.endOffset, inside.length );
				inside.deleteData( 0, inRange.startOffset );
				after.deleteData( 0, inRange.endOffset );
				after.parentNode.insertBefore( before, after );
				inRange.setStartBefore( after );
				inRange.collapse( true );
				appendToNode.appendChild( inside );
				return;
			}
		case Range.NODE_BEFORE:
			//the node is partially selected by the range.  It extends before
			//the range.
			if( fromNode.nodeType == Node.CDATA_SECTION_NODE ||
				fromNode.nodeType == Node.TEXT_NODE){
				//split the Text/CDATASection node into the 2 sections (before,
				//inside) and extract the inside contents
				var before = fromNode;
				var inside = fromNode.cloneNode( true );
				inside.deleteData( 0, inRange.startOffset );
				before.deleteData( inRange.startOffset, before.length );
				inRange.setStartAfter( before );
				appendToNode.appendChild( inside );
				return;
			}
		case Range.NODE_AFTER:
			//the node is partially selected by the range.  It extends
			//after the range.
			if( fromNode.nodeType == Node.CDATA_SECTION_NODE ||
				fromNode.nodeType == Node.TEXT_NODE){
				//split the Text/CDATASection node into the 2 sections 
				//(inside, after) and extract the inside contents
				var inside = fromNode.cloneNode( true );
				var after = fromNode;
				inside.deleteData( inRange.endOffset, inside.length );
				after.deleteData( 0, inRange.endOffset );
				inRange.setEndBefore( after );
				appendToNode.appendChild( inside );
				return;
			}
			//the node is partially selected and is not a Text/CDATASection
			//node.  Clone this node (but not it's contents) and iterate 
			//through each of it's children sending them recurivaly back
			//into this function.
			clonedNode = fromNode.cloneNode(false);
			appendToNode.appendChild( clonedNode );
			var nextPtr, ptr = fromNode.firstChild;
			while( ptr ){
				//since the DOM tree will be modified, get the next sibling
				//before it is modified.
				nextPtr = ptr.nextSibling;
				//pass in the clonedNode as the new appendToNode argument
				extractContents( ptr, clonedNode, inRange);
				ptr = nextPtr;
			}
			return;
		}
	}
	
	function calculateInnerHTML( node ){
		//this function manually calculates the innerHTML of the supplied node.
		switch(node.nodeType){
			case Node.ELEMENT_NODE:
			case Node.DOCUMENT_FRAGMENT_NODE:  //doc frag supported for range.htmlText
				var ptr = node.firstChild;
				var retVal = "";
				while( ptr ){
					retVal += calculateMarkup( ptr, false );
					ptr = ptr.nextSibling;
				}
				return retVal;
			
			//Only elements are processed by this function call.  All others should
			//generate an error.
			default:
				throw new Error("Only elements have innerHTML properties");
		}
	}
	
	function calculateMarkup( node, isXML, inRange ){  
		if( arguments.length < 2 ) isXML = (node.document + "" == "[object Document]");
		var calcRange = false;
		if( inRange ) calcRange = true;
		
		//calculate only those nodes that are within the range;
		if( calcRange && !inRange.intersectsNode(node) ) return "";
		
		//this function manually calculates the outerHTML of the supplied node.
		switch(node.nodeType){
			//the following node types are supported for the recursion nature
			//of this function.
			case Node.CDATA_SECTION_NODE: //CDATASections are not parsed
				var retVal = node.data;
				if(  calcRange && inRange.compareNode(node) != Range.NODE_INSIDE ){
					if( node != inRange.endContainer && node != inRange.startContainer)
						throw new Error("Range Processing CDATASection Node Markup");
					if( node == inRange.endContainer )
						retVal = retVal.substring( 0, inRange.endOffset );
					if( node == inRange.startContainer )
						retVal = retVal.substring( inRange.startOffset, retVal.length + 1 );
				}
				return "<![CDATA[" + retVal + "]]>";
			
			case Node.TEXT_NODE:	
				var retVal = node.data;
				if(  calcRange && inRange.compareNode(node) != Range.NODE_INSIDE ){
					if( node != inRange.endContainer && node != inRange.startContainer){
						alert(inRange.compareNode(node));
//						throw new Error("Range Processing Text Node Markup");
					}
					if( node == inRange.endContainer )
						retVal = retVal.substring( 0, inRange.endOffset );
					if( node == inRange.startContainer )
						retVal = retVal.substring( inRange.startOffset, retVal.length + 1 );
				}
				//when Mozilla Bug #15118 is fixed then will have to test for entities
				//and replace them.  At this time just replace the known XML entities.
				return retVal.replace(/\&/g, "&amp;").replace(/</g, 
						"&lt;").replace(/>/g, "&gt;");
			
			case Node.COMMENT_NODE:	//Comment nodes are not parsed
				var retVal = node.data;
				if(  calcRange && inRange.compareNode(node) != Range.NODE_INSIDE ){
					if( node != inRange.endContainer && node != inRange.startContainer)
						throw new Error("Range Processing Comment Node Markup");
					if( node == inRange.endContainer )
						retVal = retVal.substring( 0, inRange.endOffset );
					if( node == inRange.startContainer )
						retVal = retVal.substring( inRange.startOffset, retVal.length + 1 );
				}
				return "<!--" + retVal + "-->";
			
			//the following nodes outerHTML is it's innerHTML
			case Node.DOCUMENT_FRAGMENT_NODE:
				var retVal = "";
				var ptr = node.firstChild;
				while( ptr ){
					retVal += calculateMarkup( ptr , isXML, inRange);
					ptr = ptr.nextSibling;
				}
				return retVal;
				
			//These nodes corrispond to tags.  Calculate the tags value.
			case Node.ELEMENT_NODE:
				var name = node.nodeName;
				var empty = (node.childNodes.length == 0);
				var attr, attrs = node.attributes;
				var len = attrs.length;
				var retVal= "<" + name;
				
				//get each of the attributes
				for (var i = 0; i < len; i++) {
					attr = attrs.item(i);
					//if it has not been specified than it assumes it default
					//value and does not need to be included.
					if( attr.specified )
						retVal += " " + calculateMarkup( attr, isXML, null );
				}
				if( isXML && empty ) return retVal + "/>";
				retVal += ">";
				if( !isXML && !node.canHaveChildren ) return retVal;
				var ptr = node.firstChild;
				while( ptr ){
					retVal += calculateMarkup( ptr , isXML, inRange);
					ptr = ptr.nextSibling;
				}
				return retVal + "</" + name + ">";
			
			case Node.PROCESSING_INSTRUCTION_NODE:
				return "<?" + node.target + " " + node.data + "?>";
					
			case Node.DOCUMENT_TYPE_NODE:
				var retVal = "<!DOCTYPE " + node.nodeName;
				if( node.publicId ){
					retVal += ' PUBLIC "' + node.publicId + '"';
					if( node.systemId ) retVal += ' "' + node.systemId + '"';
				} else if( node.systemId ) retVal += ' SYSTEM "' + node.systemId + '"';
				if( node.internalSubset ) retVal += " " + node.internalSubset;
				return retVal + ">\n";

			case Node.ENTITY_NODE:		//cannot test this code due to Mozilla Bug #15118
				var retVal = "<!ENTITY " + node.nodeName;
				if( node.publicId ){
					retVal += ' PUBLIC "' + node.publicId + '"';
					if( node.systemId ) retVal += ' "' + node.systemId + '"';
					if( node.notationName ) retVal += " NDATA " + node.notationName;
				} else if( node.systemId ) {
					retVal += ' SYSTEM "' + node.systemId + '"';
					if( node.notationName ) retVal += " NDATA " + node.notationName;
				} else {
					retVal += '"';
					var ptr = node.firstChild;
					while( ptr ){
						retVal += calculateMarkup( ptr , isXML, inRange);
						ptr = ptr.nextSibling;
					}
					retVal += '"';
				}
				return retVal + ">";

			case Node.NOTATION_NODE:	//cannot test this code due to Mozilla Bug #15118
				var retVal = "<!NOTATION " + node.nodeName;
				if( node.publicId ){
					retVal += ' PUBLIC "' + node.publicId + '"';
					if( node.systemId ) retVal += ' "' + node.systemId + '"';
				} else if( node.systemId ) retVal += ' SYSTEM "' + node.systemId + '"';
				return retVal + ">";

			case Node.ENTITY_REFERENCE_NODE:
				return "&" + node.nodeName + ";";
			
			case Node.ATTRIBUTE_NODE:
				if( node.specified )
					return node.nodeName + '="' + node.value + '"';
				return "";

			case Node.DOCUMENT_NODE:
				//there is a bug in XML documents.  The doctype tag is NOT in the document
				//sub-tree, but is in an HTML document.  Detect it and surpress it.
				var retVal = "";
				var foundDocType = false;
				var ptr = node.firstChild;
				while( ptr ){
					if(ptr.nodeType != Node.DOCUMENT_TYPE_NODE )
						foundDocType = true;
					retVal += calculateMarkup( ptr, isXML, inRange );
					ptr = ptr.nextSibling;
				}
				if( !foundDocType && node.doctype ) retVal = calculateMarkup(node.doctype, isXML, inRange) + retVal;
				if(isXML && ( !calcRange || (node == inRange.startContainer && inRange.startOffset == 0)))
					retVal = '<?xml version="1.0" encoding="'+ node.characterSet + '"?>\n' + retVal;
				return retVal;
		}
	}

	function calculateContainerBranch( startCalculationsFrom, inRange ){
		var start = startCalculationsFrom;
		var range = inRange;
		var common = inRange.commonAncestorContainer;
	
		var retValue = new Array();
		var ptr = start;
		retValue.unshift(ptr);
		while( ptr != common ){
			ptr = ptr.parentNode;
			retValue.unshift(ptr);
		} 
		retValue.item = function (index){
			return this[index];
		}
		retValue.itemOffset = function (index){
			//no need to calculate the offset if the index is pointing to 
			//the start container.  This eliminates needing to figure out
			//the offset in a text node.
			if( this[index] == start )
				return range.startOffset;
			return this[index].parentIndex;
			}
		return retValue;
	}
/*
	function comparePositions( srcNode, srcOffset, toNode, toOffset ){
		if( srcNode.rootContainer != toNode.rootContainer )
			throw new Error( "WRONG_DOCUMENT_ERR" );
		//if both nodes are the same then just compare the offsets
		if( srcNode == toNode ){
			if( toOffset > srcOffset ) return Range.POINT_AFTER;
			if( toOffset < srcOffset ) return Range.POINT_BEFORE;
			return Range.POINT_EQUAL;
		}
		//if scrNode contains the toNode find recursivly call this function with toNodes prarent.
		if( srcNode.contains( toNode ))
			return comparePositions( srcNode, srcOffset, toNode.parentNode, toNode.parentIndex );
		
		//the same is true if toNode contains srcNode
		if( toNode.contains( srcNode ))
			return comparePositions( srcNode.parentNode, srcNode.parentIndex, toNode, toOffset  );
		
		//Since they do not contain one another then back up the tree until you find a
		//common ancestor that contains them both and do the comparison there.
		return comparePositions( srcNode.parentNode, srcNode.parentIndex, toNode.parentNode, toNode.parentIndex  );
	}
*/

};
//note:  because of proper standards, the patch must be run on each
//applicable document.  In the current versions of Mozilla it only needs
//run on one document and it will patch them all.

ApplyRangePatch(document);



