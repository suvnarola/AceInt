// HardCore Web Content Editor
// Copyright 2002-2004 HardCore Internet Ltd.
// www.hardcoreinternet.co.uk

var webeditor = new Object();
webeditor.rootpath = "/hardcore/webeditor/";
webeditor.buttonpath = webeditor.rootpath;

var navigator_vendor = navigator.vendor;
var navigator_vendorSub = navigator.vendorSub;
var navigator_userAgent = navigator.userAgent;
var navigator_appVersion = navigator.appVersion;

function browserIs(string) {
	if (string) {
		return navigator_userAgent.toLowerCase().indexOf(string.toLowerCase())+1;
	} else {
		return navigator_userAgent.toLowerCase();
	}
}

function browserNot(string) {
	return ! browserIs(string);
}

function versionIs(string) {
	if (string) {
		return navigator_appVersion.toLowerCase().indexOf(string.toLowerCase())+1;
	} else {
		return navigator_appVersion.toLowerCase()
	}
}

function versionNot(string) {
	return ! versionIs(string);
}

if (browserIs("Opera")) {
	webbrowser = "Opera";
	minorVersion = parseFloat(versionIs());
	majorVersion = parseInt(minorVersion);
	webeditorType = "textarea";
} else if (browserIs("Konqueror")) {
	webbrowser = "Konqueror";
	minorVersion = parseFloat(versionIs().substring(versionIs("Konqueror")+8, versionIs().indexOf(";", versionIs("Konqueror"))));
	majorVersion = parseInt(minorVersion);
	webeditorType = "textarea";
} else if (browserIs("Safari") && browserIs("Mac")) {
	webbrowser = "Safari";
	minorVersion = parseFloat(versionIs());
	majorVersion = parseInt(minorVersion);
	webeditorType = "textarea";
} else if (browserIs("MSIE") && browserIs("Win")) {
	webbrowser = "MSIE";
	if (versionIs("MSIE")) {
		minorVersion = parseFloat(versionIs().substring(versionIs("MSIE")+3, versionIs().indexOf(";", versionIs("MSIE"))));
		majorVersion = parseInt(minorVersion);
	} else {
		minorVersion = parseFloat(versionIs());
		majorVersion = parseInt(minorVersion);
	}
	if (minorVersion >= "5.5") {
		webeditorType = "msie";
	} else if (majorVersion >= 4) {
		webeditorType = "dhtml";
	} else {
		webeditorType = "textarea";
	}
} else if (browserIs("Gecko") && browserIs("Mozilla/5") && browserNot("spoofer") && browserNot("compatible") && browserNot("webtv") && browserNot("hotjava") && ((navigator_vendor.toLowerCase() == "netscape") || (navigator_vendor.toLowerCase() == "mozilla") || (navigator_vendor == ""))) {
	webbrowser = "Mozilla";
	if (navigator_vendorSub && (! isNaN(parseInt(navigator_vendorSub)))) {
		minorVersion = navigator_vendorSub;
		majorVersion = parseInt(minorVersion);
	} else if (browserIs("rv:")) {
		minorVersion = parseFloat(browserIs().substring(browserIs("rv:")+2, browserIs().indexOf(")", browserIs("rv:"))));
		majorVersion = parseInt(minorVersion);
	} else {
		minorVersion = parseFloat(versionIs());
		majorVersion = parseInt(minorVersion);
	}
	if (minorVersion >= "1.3") {
		webeditorType = "mozilla";
	} else {
		webeditorType = "textarea";
	}
} else if (browserIs("Gecko") && browserIs("Mozilla/5") && browserNot("spoofer") && browserNot("compatible") && browserNot("webtv") && browserNot("hotjava") && ((navigator_vendor.toLowerCase() == "firebird") || (navigator_vendor.toLowerCase() == "firefox"))) {
	webbrowser = "Mozilla";
	if (navigator_vendorSub && (! isNaN(parseInt(navigator_vendorSub)))) {
		minorVersion = navigator_vendorSub;
		majorVersion = parseInt(minorVersion);
	} else if (browserIs("rv:")) {
		minorVersion = parseFloat(browserIs().substring(browserIs("rv:")+2, browserIs().indexOf(")", browserIs("rv:"))));
		majorVersion = parseInt(minorVersion);
	} else {
		minorVersion = parseFloat(versionIs());
		majorVersion = parseInt(minorVersion);
	}
	minorVersionFraction = parseInt(minorVersion.substring(minorVersion.indexOf(".")+1));
	if ((majorVersion >= 1) || (minorVersionFraction >= 7)) {
		webeditorType = "mozilla";
	} else {
		webeditorType = "textarea";
	}
} else {
	webbrowser = "other";
	minorVersion = parseFloat(versionIs());
	majorVersion = parseInt(minorVersion);
	webeditorType = "textarea";
}

if (webeditorType == "textarea") {
	document.write('<script src="' + webeditor.rootpath + 'webeditor.properties.js"></script>');
	document.write('<script src="' + webeditor.rootpath + 'webeditor_textarea.js"></script>');
} else if ((webeditorType == "mozilla") && document.designMode) {
	document.write('<script src="' + webeditor.rootpath + 'webeditor.properties.js"></script>');
	document.write('<script src="' + webeditor.rootpath + 'webeditor_contenteditable.js"></script>');
	document.write('<script src="' + webeditor.rootpath + 'webeditor_contenteditable_mozilla.js"></script>');
} else if ((webeditorType == "msie") && document.designMode) {
	document.write('<script src="' + webeditor.rootpath + 'webeditor.properties.js"></script>');
	document.write('<script src="' + webeditor.rootpath + 'webeditor_contenteditable.js"></script>');
	document.write('<script src="' + webeditor.rootpath + 'webeditor_contenteditable_msie.js"></script>');
} else {
	document.write('<script src="' + webeditor.rootpath + 'webeditor.properties.js"></script>');
	document.write('<script src="' + webeditor.rootpath + 'webeditor_textarea.js"></script>');
}
