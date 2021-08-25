// Welcome to Tipster! Before you start, make sure you've read and agree to the
// "Conditions of Use" in the HTML document below.


// This script is object orientated.
// It works by creating "tip objects", each of which corresponds to a DIV in the page below.
// Each object contains a 'template' used for formatting tips, and settings for that object.
// Here are some examples to get you started:


// First, create a new tip object, and pass it its own name so it can reference itself.
var docTips = new TipObj('docTips');
with (docTips)
{
 // Next, we set the appearance and style of the tips displayed by this tip object.
 // Each tip object must have a string called 'template' that contains some specially-formatted
 // HTML to write to its DIV. This example has two nested tables, a border and a background.
 // The special bits are the %2%, %3% and so on halfway through. These correspond to values we
 // set in the tips.tipName arrays later: %0% is the X value, %1% is Y, and %2% onwards are
 // whatever other info we have in there (width, text etc...). This example sets the width %2%
 // of the table, and inserts some content which is the text %3%.
 // You might want to put extra information in the tip arrays, and use %4%, %5% onwards in the
 // template for tip headers, footers, customisable colours etc... see the next tip object for
 // another example.

 template = '<table bgcolor="#003366" cellpadding="1" cellspacing="0" width="%2%" border="0">' +
  '<tr><td><table bgcolor="#6699CC" cellpadding="3" cellspacing="0" width="100%" border="0">' +
  '<tr><td class="tipClass">%3%</td></tr></table></td></tr></table>';

 // Next, you can list one or more named tips to call later on in your page. This is useful if
 // you want to show the same tip several times in the page, or on several pages.
 //
 // We organise tips in arrays like so: tips.tipName = new Array(X, Y, width, text, ....);
 // The first two parameters, X and Y, are the distances of the tip from the mouse cursor position
 // if they're set as numbers. If they're strings ('in quotes'), the script calculates them as
 // expressions and ignores the mouse position. They are the only compulsory parameters.
 // You can also use the 'page' object included with this script for fancy positioning
 // effects. Functions include 'page.winW()' and 'page.winH()' to get the window area dimensions,
 // and 'page.scrollX()' and 'page.scrollY()' for the current scroll position, so you can align
 // your tips however you want... see the examples below.
 //
 // Alternatively, you can also create tips inline later in the page like so:
 // <tag onmouseover="tipObjectName.newTip('tipName', X, Y, ....and so on....)">
 // This automatically creates and shows a new tip (just give them random names).
 // Make sure you don't use HTML formatting inside HTML tag event handlers for your tip text.
 //
 // And if you don't want to do *that*, see below for an optional function that can convert
 // TITLE="..." attributes into tips automatically.


 // This 'mysite' tip will show 75px left of the cursor and 15px below. As you can see %2% is
 // a width of 150px, and %3% is a text string, according to our template above.
 tips.mysite = new Array(-75, 15, 150, 'Visit this for updates, help and more info');
 tips.welcome = new Array(5, 5, 100, 'Hope you like it...');
 tips.useful = new Array(5, 5, 150, 'This can add important context information to any link...');
 // This next tip uses a formula to position the tip 110 pixels from the right edge of the screen.
 tips.formulae = new Array('page.scrollX() + page.winW() - 110', -20, 100,
  'This tip is always on the right edge...');
 tips.format = new Array(5, 5, 150, 'That means <i>italics</i>...<br /><hr />...etc');


 // Finally, you can set some optional properties to customise the behavious of this object.
 //
 // How much of a delay do you want between pointing and action? Defaults are:
 showDelay = 0;
 hideDelay = 0;
 //
 // False will hide tips instantaneously. Fading only works under IE/Win and NS6+.
 //doFades = false;
 // You can change the minimum and maximum opacity percentages, defaults:
 //minAlpha = 0;
 //maxAlpha = 100;
 //
 // How fast the transparency changes (between 1 and 100), higher means faster fades.
 fadeInSpeed = 100;
 fadeOutSpeed = 100;
 //
 // Tip stickiness, from 0 to 1, defines how readily the tip follows the cursor. 1 means it
 // follows it perfectly (the default), 0 is a static tip, and decimals are 'floating' tips.
 tipStick = 0;
}

// Later in the document, use this syntax to show tips from links or other HTML tags:
// <a href="file.html" onmouseover="tipObjName.show('tipName')" onmouseout="tipObjName.hide()">




// Here's a second demo tip object. Feel free to delete it if you're not using it!
// I've included a tip header here in this template, %3% is the header text and %4% is
// now the main text. As you can see you can basically format your tips any way you want.
// This tip also includes mouse event handlers to show a second-level tip, just like in
// the body of the page below, so you can nest tips within tips, and a 'tipStick' of 0 so
// it never follows the mouse.
var staticTip = new TipObj('staticTip');
with (staticTip)
{
 template = '<table bgcolor="#000000" cellpadding="0" cellspacing="0" width="%2%" border="0">' +
  '<tr><td><table cellpadding="3" cellspacing="1" width="100%" border="0">' +
  '<tr><td bgcolor="#336666" align="center" height="18" class="tipClass">%3%</td></tr>' +
  '<tr><td bgcolor="#009999" align="center" height="*" class="tipClass">%4%</td></tr>' +
  '</table></td></tr></table>';

 // HIERARCHIAL TIPS: To call one tip object from within another tip object, make sure you
 // pass the a reference to the current object as the second parameter to the show() function.
 tips.links = new Array(5, 5, 100, 'Extra Links',
  '- <a href="javascript:alert(\'Useful indeed...\')">Section 1</a> -<br />' +
  '- <a href="#" name="nest1trig" onmouseover="nestTip.show(\'nest1\', staticTip)" ' +
   'onmouseout="nestTip.hide()">NESTED TIP 1 &gt;</a> -<br />' +
  '- <a href="#" name="nest2trig" onmouseover="nestTip.show(\'nest2\', staticTip)" ' +
   'onmouseout="nestTip.hide()">NESTED TIP 2 &gt;</a> -<br />');

 tipStick = 0;
}

// Here's the other tip object called by the one above, for hierarchial tips.
var nestTip = new TipObj('nestTip');
with (nestTip)
{
 template = '<table bgcolor="#000000" cellpadding="1" cellspacing="0" width="%2%" border="0">' +
  '<tr><td><table bgcolor="#009999" cellpadding="3" cellspacing="0" width="100%" border="0">' +
  '<tr><td class="tipClass">%3%</td></tr></table></td></tr></table>';

 tips.nest1 = new Array(10, 0, 90,
  '<a href="javascript:alert(\'A regular popup menu...\')">Relative Position</a>');

 // This tip is positioned via formulae based on its parent tip's position...
 tips.nest2 = new Array('staticTip.xPos + 95', 'staticTip.yPos + 50', 120,
  '<a href="javascript:alert(\'Nested tip 2\')">Absolutely positioned static tip...</a>');

 tipStick = 0;
}


// Here's one illustrating a decimal tipStick value so it floats along behind the cursor.
var stickyTip = new TipObj('stickyTip');
with (stickyTip)
{
 template = '<table bgcolor="#000000" cellpadding="1" cellspacing="0" width="%2%" border="0">' +
  '<tr><td><table bgcolor="#339966" cellpadding="4" cellspacing="0" width="100%" border="0">' +
  '<tr><td align="center" class="tipClass">%3%</td></tr></table></td></tr></table>';

 tips.floating = new Array(5, 5, 100, 'Floating tips can have extra effect!');

 tipStick = 0.2;
}



// Finally, here's an optional function that will convert document TITLE="..." attributes into
// tips, in v5+ browsers. This is provided as a base to get you started, so uncomment and enjoy.
// Otherwise this can be deleted, it is entirely optional and just takes up space.

/*

function titlesToTips()
{
 var tags = isDOM ? document.getElementsByTagName('*') []));
 for (var i = 0; i < tags.length; i++)
 {
  if (tags[i].title)
  {
   // You may wish to do some string processing here, for instance split the TITLE into two
   // strings based on the | character or similar, and use one for a tip heading in a template.
   tags[i].onmouseover = new Function('docTips.newTip(5, 5, 100, "' + tags[i].title + '")');
   tags[i].onmouseout = new Function('docTips.hide()');
   tags[i].title = '';
  }
 }
};
var tttOL = window.onload;
window.onload = function()
{
 if (tttOL) tttOL();
 titlesToTips();
}

*/
