<script Language="C#" runat="server">

// Which spellcheck command and parameters to use
string spellcheckCommand = "";
//string spellcheckParameters = "";
//string spellcheckCommand = "/usr/local/bin/aspell";
//string spellcheckCommand = "C:\\Progra~1\\Aspell\\bin\\aspell.exe";
string spellcheckParameters = "-a -H";

// Which spellcheck command parameter to use for specifying which dictionary to use
string spellcheckDictionary = "-d";
// Which dictionary options to make available to users
string spellcheckDictionaries = "<option value=\"\">- default -<option value=\"af\">Afrikaans<option value=\"br\">Breton<option value=\"bg\">Bulgarian<option value=\"ca\">Catalan<option value=\"hr\">Croatian<option value=\"cs\">Czech<option value=\"da\">Danish<option value=\"nl\">Dutch<option value=\"en\">English<option value=\"en_US\">English (American)<option value=\"en_GB\">English (British)<option value=\"en_CA\">English (Canadian)<option value=\"eo\">Esperanto<option value=\"fo\">Faroese<option value=\"fr\">French<option value=\"fr_FR\">French (French)<option value=\"fr_CH\">French (Swiss)<option value=\"gl\">Galician<option value=\"de\">German<option value=\"de_DE\">German (German)<option value=\"de_CH\">German (Swiss)<option value=\"el\">Greek<option value=\"is\">Icelandic<option value=\"id\">Indonesian<option value=\"ia\">Interlingua<option value=\"ga\">Irish<option value=\"it\">Italian<option value=\"mk\">Macedonian<option value=\"ms\">Malay<option value=\"mt\">Maltese<option value=\"gv\">Manx Gaelic<option value=\"mi\">Maori<option value=\"no\">Norwegian<option value=\"nb\">Norwegian Bokm&aring;l<option value=\"nn\">Norwegian Nynorsk<option value=\"pl\">Polish<option value=\"pt\">Portuguese<option value=\"pt_BR\">Portuguese (Brazilian)<option value=\"pt_PT\">Portuguese (Portuguese)<option value=\"ro\">Romanian<option value=\"ru\">Russian<option value=\"gd\">Scottish Gaelic<option value=\"tn\">Setswana<option value=\"sk\">Slovak<option value=\"sl\">Slovenian<option value=\"es\">Spanish<option value=\"sw\">Swahili<option value=\"sv\">Swedish<option value=\"tr\">Turkish<option value=\"uk\">Ukranian<option value=\"wa\">Walloon<option value=\"cy\">Welsh<option value=\"zu\">Zulu";



//string root_path = "";
string root_path = System.Web.HttpContext.Current.Server.MapPath("/");
//string root_path = "D:\\HardCore\\Web Content Editor";

// Which (if any) temporary folder to use for upload?
string enable_upload = "no";
//string enable_upload = "yes";
//string upload_path = "";
string upload_path = System.Web.HttpContext.Current.Server.MapPath("/hardcore/upload/");
//string upload_path = "D:\\HardCore\\Web Content Editor\\upload\\";

// Which (if any) folders to hide from users?
//string exclude_paths = "";
string exclude_paths = "/hardcore,/WEB-INF";

// Which (if any) pages folder to use for Insert Hyperlink?
//string pages_path = "";
string pages_path = "/";

// Which (if any) images folder to use for Insert Hyperlink and Insert Media?
//string images_path = "";
string images_path = "/";

// Which (if any) files folder to use for Insert Hyperlink?
//string files_path = "";
string files_path = "/";

// Which (if any) page formats to allow for Insert Hyperlink?
//string page_formats = "";
string page_formats = "html,htm";

// Which (if any) file formats to allow for Insert Hyperlink?
//string file_formats = "";
string file_formats = "gif,jpg,jpeg,png,swf,class,txt,doc,pdf,zip";

// Which (if any) image formats to allow for Insert Hyperlink/Media?
//string image_formats = "";
string image_formats = "gif,jpg,jpeg,png,swf,class";

</script>