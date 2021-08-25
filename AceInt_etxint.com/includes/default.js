function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=no,status=yes,resizable=yes,menubar=no,width=220,height=250');
}
 function setFocus(formName, elementName) {
  if (document.forms.length > 0) {
  	focusElement = eval("document."+formName+"."+elementName);
	focusElement.focus();
  }
 }
 function putFocus(formInst, elementInst) {
  if (document.forms.length > 0) {
   document.forms[formInst].elements[elementInst].focus();
  }
 }
 function letternumber(e)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();

// control keys
if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return true;

// alphas and numbers
else if ((("abcdefghijklmnopqrstuvwxyz0123456789_.").indexOf(keychar) > -1))
   return true;
else
   return false;
}

function number(e)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();

// control keys
if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return true;

// alphas and numbers
else if ((("0123456789. ").indexOf(keychar) > -1))
   return true;
else
   return false;
}

function number2(e)
{
 var key;
 var keychar;

 if (window.event)
   key = window.event.keyCode;
 else if (e)
   key = e.which;
 else
   return true;
 keychar = String.fromCharCode(key);
 keychar = keychar.toLowerCase();

 // control keys
 if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return true;

 // alphas and numbers
 else if ((("0123456789,-/").indexOf(keychar) > -1))
   return true;
 else
   return false;
}

function phonenumber(e)
{
 var key;
 var keychar;

 if (window.event)
   key = window.event.keyCode;
 else if (e)
   key = e.which;
 else
   return true;
 keychar = String.fromCharCode(key);
 keychar = keychar.toLowerCase();

 // control keys
 if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return true;

 // alphas and numbers
 else if (((" 0123456789+").indexOf(keychar) > -1))
   return true;
 else
   return false;
}

var marked_row = new Array;

function setCheckboxes(the_form, do_check)
{
    var elts      = (typeof(document.forms[the_form].elements['del[]']) != 'undefined')
                  ? document.forms[the_form].elements['del[]']
                  : document.forms[the_form].elements['del[]'];
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) {
        for (var i = 0; i < elts_cnt; i++) {
            elts[i].checked = do_check;
        } // end for
    } else {
        elts.checked        = do_check;
    } // end if... else

    return true;
} // end of the 'setCheckboxes()' function

function setPointer(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '') || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined' && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    } // end 3

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        } else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase() && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor              = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '') ? thePointerColor : theDefaultColor;
            marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum]) ? true : null;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } // end 5

    return true;
} // end of the 'setPointer()' function

<!--

// A small function that refreshes NS4 on horizontal resize.
//	browserName = navigator.appName;
//	browserVer = parseInt(navigator.appVersion);
//	var msie4 = (browserName == "Microsoft Internet Explorer" && browserVer >= 4);
//	if ((browserName == "Netscape" && browserVer >= 3) || msie4 || browserName=="Konqueror") {
//		version = "n3";
//	} else {
//		version = "n2";
//	}
//
//	function over(dir,name)	{
//		if (version == "n3" && document["img_"+dir+"_"+name]) {
//			document["img_"+dir+"_"+name].src = eval("img_"+dir+"_1.src");
//		}
//	}
//
//	function out(dir,name)	{
//		if (version == "n3" && document["img_"+dir+"_"+name]) {
//			document["img_"+dir+"_"+name].src = eval("img_"+dir+"_0.src");
//		}
//	}
//
// 	focusElement = eval("document."+formName+"."+elementName);
//	focusElement.focus();
function go1(theRow){
 var theCells = theRow.cells;
 var rowCellsCnt  = theCells.length;
 var domDetect    = null;
 var currentColor = null;

  if (typeof(window.opera) == 'undefined' && typeof(theCells[0].getAttribute) != 'undefined') {
      currentColor = theCells[0].getAttribute('bgcolor');
      domDetect    = true;
  }
  // 3.2 ... with other browsers
  else {
      currentColor = theCells[0].style.backgroundColor;
      domDetect    = false;
  } // end 3

  if (domDetect) {
      for (c = 0; c < rowCellsCnt; c++) {
          theCells[c].setAttribute('bgcolor', 'yellow', 0);
      } // end for
  }
  // 5.2 ... with other browsers
  else {
      for (c = 0; c < rowCellsCnt; c++) {
          theCells[c].style.backgroundColor = 'yellow';
      }
  }
 setTimeout("go1('" + eval(theRow) + "');", 500);
}

function dhd_fn_progress_bar_update(intCurrentPercent) {

	document.getElementById('progress_bar_complete').style.width = intCurrentPercent+'%';
	document.getElementById('progress_bar_complete').innerHTML = intCurrentPercent+'%';

}



//var PageSavedWarning = false;
//var arrow_1 = new Image(); arrow_1.src = "/images/field_1.gif";
//var arrow_error = new Image(); arrow_error.src = "/images/field_error.gif";
//var img_up_0 = new Image(); img_up_0.src = "/images/arrow_up_0.gif";
//var img_up_1 = new Image(); img_up_1.src = "images/arrow_up_1.gif";
//var img_down_0 = new Image(); img_down_0.src = "images/arrow_down_0.gif";
//var img_down_1 = new Image(); img_down_1.src = "images/arrow_down_1.gif";
//-->
