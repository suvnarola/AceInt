<?

 include("/virt/backup/home/etxint/admin.etxint.com/includes/global.php");

?>
	search_addresses  = [];
	display_addresses = [];

 <?
  
    $UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users Order by Username");
    while($UserRow = mysql_fetch_assoc($UserSQL)) {

     print "search_addresses[search_addresses.length]   = ['".$UserRow['Username']."', '', '']\r\n";
     print "display_addresses[display_addresses.length] = ['".addslashes($UserRow['Username'])." (".addslashes($UserRow['Name']).")', '".addslashes($UserRow['Username'])."']\r\n";
     
    }
	
	

 ?>
 
	function update_address_list(text) {
		var new_addresses = [];

		for(var i=0; i<search_addresses.length; i++){
			if(text == ''
			   || search_addresses[i][0].toLowerCase().indexOf(text.toLowerCase()) != -1
			   || search_addresses[i][1].toLowerCase().indexOf(text.toLowerCase()) != -1
			   || search_addresses[i][2].toLowerCase().indexOf(text.toLowerCase()) != -1){
				new_addresses[new_addresses.length] = display_addresses[i];
			}
		}
		
		addressesElement = xbApi_getFormElement('TrackUser', 'UserID');

		// Clear current addresses
		for(i=0; i<addressesElement.options.length; i++){
			addressesElement.options[i] = null;
			i--;
		}
		
		// Set new addresses
		for(i=0; i<new_addresses.length; i++){
			addressesElement[addressesElement.length] = new Option(new_addresses[i][0], new_addresses[i][1]);
		}
	}

	function add_address(field) {

		addressesElement = xbApi_getFormElement('TrackUser', 'UserID');
		addresses        = xbApi_getFormValue(addressesElement);

		// Lose any blanks
		var tmp = new Array();
		for (var i=0; i<addresses.length; i++) {
			if (addresses[i] != '') {
				tmp[tmp.length] = addresses[i];
			}
		}
		addresses = tmp;
		addresses = addresses.join(', ');

		if (addresses != '') {
			formElement      = xbApi_getFormElement('TrackUser', field);
			formElementValue = xbApi_getFormValue(formElement);
	
			xbApi_setFormValue(formElement, (formElementValue == '' ? addresses : formElementValue + ', ' + addresses));
		}
	}

	function append_signature(){
		sig = '';
		textElement = xbApi_getFormElement('TrackUser', 'text');
		xbApi_setFormValue(textElement, xbApi_getFormValue(textElement) + sig)
		return false;
	}
