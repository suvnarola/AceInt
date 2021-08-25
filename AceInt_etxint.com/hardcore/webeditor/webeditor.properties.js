
var webeditor_languages = "|ar|da|de|en|es|fr|it|ja|zh|";

var hardcore = new Object();

function Text(name) {
//	if (! hardcore[name]) alert("ERROR:"+name);
	return hardcore[name] || name || "";
}

function getCookie(name) {
	if (document.cookie && (document.cookie.indexOf(name+'=') != -1)) {
		cookiestart = document.cookie.indexOf(name+'=') + name.length + 1;
		cookieend = document.cookie.indexOf(';', cookiestart);
		if (cookieend == -1) cookieend = document.cookie.length;
		return unescape(document.cookie.substring(cookiestart, cookieend));
	}
}

function browserLanguage() {
	var language = "";
	if (getCookie('AcceptLanguage')) {
		languages = getCookie('AcceptLanguage');
		while (languages) {
			language = getCookie('AcceptLanguage').substring(0,2).toLowerCase();
			if (webeditor_languages.indexOf("|"+language+"|") >= 0) {
				return language;
			} else if (languages.indexOf(',') != -1) {
				languages = languages.substring(languages.indexOf(',')+1);
			} else {
				break;
			}
		}
	}
	if (navigator.userLanguage) {
		language = navigator.userLanguage.substring(0,2).toLowerCase();
	} else if (navigator.language) {
		language = navigator.language.substring(0,2).toLowerCase();
	}
	return language;
}

function browserCountry() {
	var country = "";
	if (getCookie('AcceptLanguage')) {
		languages = getCookie('AcceptLanguage');
		while (languages) {
			language = getCookie('AcceptLanguage').substring(0,2).toLowerCase();
			country = getCookie('AcceptLanguage').substring(3,5).toUpperCase();
			if (webeditor_languages.indexOf("|"+language+"_"+country+"|") >= 0) {
				return country;
			} else if (languages.indexOf(',') != -1) {
				languages = languages.substring(languages.indexOf(',')+1);
			} else {
				break;
			}
		}
	}
	if (navigator.userLanguage) {
		country = navigator.userLanguage.substring(3,5).toUpperCase();
	} else if (navigator.language) {
		country = navigator.language.substring(3,5).toUpperCase();
	}
	return country;
}

if (webeditor_languages.indexOf("|"+browserLanguage()+"_"+browserCountry()+"|") >= 0) {
	webeditor_properties = "_"+browserLanguage()+"_"+browserCountry();
} else if (webeditor_languages.indexOf("|"+browserLanguage()+"|") >= 0) {
	webeditor_properties = "_"+browserLanguage();
} else {
	webeditor_properties = "";
}

if (typeof(webeditor) == "undefined") {
	document.write('<script src="properties' + webeditor_properties + '.js"></script>');
} else {
	document.write('<script src="' + webeditor.rootpath + 'properties' + webeditor_properties + '.js"></script>');
}
