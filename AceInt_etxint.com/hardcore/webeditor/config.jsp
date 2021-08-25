<%

// Which spellcheck command and parameters to use
String spellcheckCommand = "";
String spellcheckParameters = "";
//spellcheckCommand = "/usr/local/bin/aspell";
//spellcheckCommand = "C:\\Progra~1\\Aspell\\bin\\aspell.exe";
spellcheckParameters = "-a -H";

// Which spellcheck command parameter to use for specifying which dictionary to use
String spellcheckDictionary = "";
spellcheckDictionary = "-d";
// Which dictionary options to make available to users
String spellcheckDictionaries = "";
spellcheckDictionaries = "<option value=\"\">- default -<option value=\"af\">Afrikaans<option value=\"br\">Breton<option value=\"bg\">Bulgarian<option value=\"ca\">Catalan<option value=\"hr\">Croatian<option value=\"cs\">Czech<option value=\"da\">Danish<option value=\"nl\">Dutch<option value=\"en\">English<option value=\"en_US\">English (American)<option value=\"en_GB\">English (British)<option value=\"en_CA\">English (Canadian)<option value=\"eo\">Esperanto<option value=\"fo\">Faroese<option value=\"fr\">French<option value=\"fr_FR\">French (French)<option value=\"fr_CH\">French (Swiss)<option value=\"gl\">Galician<option value=\"de\">German<option value=\"de_DE\">German (German)<option value=\"de_CH\">German (Swiss)<option value=\"el\">Greek<option value=\"is\">Icelandic<option value=\"id\">Indonesian<option value=\"ia\">Interlingua<option value=\"ga\">Irish<option value=\"it\">Italian<option value=\"mk\">Macedonian<option value=\"ms\">Malay<option value=\"mt\">Maltese<option value=\"gv\">Manx Gaelic<option value=\"mi\">Maori<option value=\"no\">Norwegian<option value=\"nb\">Norwegian Bokm&aring;l<option value=\"nn\">Norwegian Nynorsk<option value=\"pl\">Polish<option value=\"pt\">Portuguese<option value=\"pt_BR\">Portuguese (Brazilian)<option value=\"pt_PT\">Portuguese (Portuguese)<option value=\"ro\">Romanian<option value=\"ru\">Russian<option value=\"gd\">Scottish Gaelic<option value=\"tn\">Setswana<option value=\"sk\">Slovak<option value=\"sl\">Slovenian<option value=\"es\">Spanish<option value=\"sw\">Swahili<option value=\"sv\">Swedish<option value=\"tr\">Turkish<option value=\"uk\">Ukranian<option value=\"wa\">Walloon<option value=\"cy\">Welsh<option value=\"zu\">Zulu";


// Which (if any) context images and files folders are located under?
String context = "";
// context = "/myApplication";
// Please see "HardCore Web Content Editor - User & Developer Guide - 1.8 Relocation" for other required configuration for use under a context

// Set to website root folder if images and files folders are located under the website root folder
// Set to context folder if images and files folders are located under a context
String root_path = "";
root_path = getServletContext().getRealPath("/").replaceAll("[\\\\/]$", "");
//root_path = "D:\\Hardcore\\Web Content Editor";

// Which (if any) temporary folder to use for upload?
String enable_upload = "no";
//String enable_upload = "yes";
String upload_path = "";
upload_path = getServletContext().getRealPath("/hardcore/upload/");
//upload_path = "D:\\HardCore\\Web Content Editor\\upload\\";

// Which (if any) folders to hide from users?
String exclude_paths;
exclude_paths = "/hardcore,/WEB-INF";

// Which (if any) pages folder to use for Insert Hyperlink?
String pages_path = "";
pages_path = "/";

// Which (if any) images folder to use for Insert Hyperlink and Insert Media?
String images_path = "";
images_path = "/";

// Which (if any) files folder to use for Insert Hyperlink?
String files_path = "";
files_path = "/";

// Which (if any) page formats to allow for Insert Hyperlink?
String page_formats = "";
page_formats = "html,htm";

// Which (if any) file formats to allow for Insert Hyperlink?
String file_formats = "";
file_formats = "gif,jpg,jpeg,png,swf,class,txt,doc,pdf,zip";

// Which (if any) image formats to allow for Insert Hyperlink/Media?
String image_formats = "";
image_formats = "gif,jpg,jpeg,png,swf,class";

%>
