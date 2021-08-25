<?

/*
 * @example
 * $cc = new SecurePay("test");
 * $cc->auth("ABC0001","abc123");
 * $cc->payment("10", "PURCHASE ORDER NUMBER", "4111-1111-1111-1111", "VISA", "123", "09","2005");
 * $cc->process();
 * if($result[STATUSCODE] == "1000") {
 *   $errors = $cc->validate_errors();
 *   print_r($errors);
 * }
 * 
 * $result = $cc->result();
 * print_r($result);
 */
 
error_reporting(5);
 
class SecurePay {
	
	var $xml;
	var $xml_header;
	var $xml_response;
	var $validate_errors = array();
	var $merchantID;
	var $password;
	var $txn = Array();
	
	var $error_code;
	var $error_msg;
	
	var $currency = "AUD";
	var $test;
	
	var $serverHost;
	var $serverPath;
	
	var $serverPort;
	var $serverTimeout = "60";
	
	var $result = false;
	var $RequestType = "Payment";
	
	function SecurePay($test) {
		
		// SET SERVER DETAILS
		// TEST TRANSACTION
		
		if($test) {
			
			$this->serverHost = "ssl://www.securepay.com.au";
			$this->serverPath = "/test/payment";
			$this->serverPort = "443";
			$this->test = 1;
		
		// LIVE TRANSACTION
		
		} else {
			
			$this->serverHost = "ssl://www.securepay.com.au";
			$this->serverPath = "/xmlapi/payment";
			$this->serverPort = "443";
			$this->test = 0;
			
		}
		
	}
	
		
	function process() {
		
		// CHECK FOR VALIDATION ERRORS
		
		if($this->error_code) {
			$this->result[APPROVED] ="No";
			$this->result[STATUSCODE] = $this->error_code;
			$this->result[STATUSDESC] = $this->error_desc;
			return 0;
		}
		
		// PROCESS TRANSACTION REQUEST
		
		$this->xml_header();
		$this->xml_MessageInfo();
		$this->xml_MerchantInfo();
		$this->xml_RequestType();
		$this->xml_Payment();
		$this->xml_footer();
		$this->http();
	
		// PROCESS SERVER RESPONSE
		
		if($this->xml_response) {
			
			$p = xml_parser_create();
			xml_parse_into_struct($p, $this->xml_response, $vals);
			xml_parser_free($p);
		
			for($i = 0; $i < count($vals); $i++) {
				
				if($vals[$i][value]) $array[$vals[$i][tag]] = trim($vals[$i][value]);
				if($vals[$i][tag] == "AMOUNT") {
					
					$array[AMOUNT_DEC] = number_format(($vals[$i][value]/100), 2,"","");
					
				}
			
			}
			
	
			$this->result = $array;
			
			// RETURN RESULT
			
			if($array[APPROVED] == "Yes") {
				
				return 1;
				
			} else {
				
				return 0;
				
			}
			
		} else {
			
			// NO SERVER RESPONSE, THEREFORE ERROR
			return 0;
			
		}

	}
	

	function auth($merchantID,$password) {
		
		// DO NOT SEND REAL DETAILS TO TEST SERVER
		$this->merchantID = $merchantID;
		$this->password = $password;
		return;
		
	}
	
	function status() {
		
		$this->RequestType = "Echo";
		return;	
			
	}
	
	function result() {
		
		return $this->result;
		
	}
	
	function validate_errors() {
		
		return $this->validate_errors;
		
	}
	
	

	function xml_header($version = "1.0",$encoding="UTF-8") {
		
		$this->xml .= "<"."?xml version=\"$version\" encoding=\"$encoding\"?".">\n<SecurePayMessage>\n";
		
	}
	
	function xml_footer() {
		
		$this->xml .= "</SecurePayMessage>\n";	
		
	}
	
	function xml_MessageInfo($apiVersion="xml-4.2") {
		
		/* 
		 * Identifies the message
		 * 
		 * @param string Version of the product used. Always "xml-4.2"
		 * 
		 * messageTimestamp
		 * YYYYDDMMHHNNSSKKK000sOOO
		 * YYYY is a 4-digit year
		 * DD is a 2-digit zero-padded day of month
		 * MM is a 2-digit zero-padded month of year (January = 01)
		 * HH is a 2-digit zero-padded hour of day in 24-hour clock format (midnight = 00)
		 * NN is a 2-digit zero-padded minute of hour
		 * SS is a 2-digit zero-padded second of minute
		 * KKK is a 3-digit zero-padded millisecond of second
		 * 000 is a Static 0 characters, as SecurePay does not store nanoseconds
		 * sOOO is a Time zone offset, where s is + or -, and OOO = minutes, from GMT.
		 */
		 
		$messageID = md5(microtime());
		$messageTimestamp = date("YdmHis") + "000" + "+600";
		
		$this->xml .= "<MessageInfo>
			<messageID>$messageID</messageID>
			<messageTimestamp>$messageTimestamp</messageTimestamp>
			<timeoutValue>$this->serverTimeout</timeoutValue>
			<apiVersion>$apiVersion</apiVersion>
		</MessageInfo>\n";
		
	}
	
	function xml_MerchantInfo() {
		
		/* 
		 * Identifies the merchant
		 * 
		 * @param string Merchant ID
		 * @param string Payment password
		 */		
		
		// CHECK AUTH
		
		if(!$this->merchantID || !$this->password) $this->error_display(504,"You must use auth() function prior to process()");
		$this->xml .= "<MerchantInfo>
			<merchantID>$this->merchantID</merchantID>
			<password>$this->password</password>
		</MerchantInfo>\n";
		
	}
	
	function xml_RequestType() {
		
		/* 
		 * Defines the type of the request being processed.
		 * 
		 * @param string "Payment" or "Echo"
		 */		
		 
		$this->xml .= "<RequestType>$this->RequestType</RequestType>\n";
		
	}
	
	function xml_Payment() {
		
		/*
		 * txnType Options
		 *  0: Standard Payment
		 *  4: Refund
		 *  6: Client Reversal (Void)
		 * 10: Preauthorise
		 * 11: Preauth Complete (Advice)
		 * 
		 * txnSource Options
		 * 23: XML
		 */
		 
		$TxnList = count($this->txn);

		$this->xml .= "<Payment>\n\t<TxnList count=\"$TxnList\">";
		
		for($i = 0; $i < $TxnList; $i++) {
			
			$row = $this->txn[$i];
			
			$this->xml .= "<Txn ID=\"".($i+1)."\">";
			$this->xml .= "<txnType>".$row[txnType]."</txnType>
					<txnSource>23</txnSource>
					<amount>".$row[amount]."</amount>
					<currency>".$row[currency]."</currency>
					<purchaseOrderNo>".$row[purchaseOrderNo]."</purchaseOrderNo>";
			if($row[txnID]) $this->xml .= "<txnID>".$row[txnID]."</txnID>\n";
			if($row[txnID]) $this->xml .= "<preauthID>".$row[preauthID]."</preauthID>\n";
			$this->xml .= "<CreditCardInfo>
						<cardNumber>".$row[cardNumber]."</cardNumber>
						<cvv>".$row[cvv]."</cvv>
						<expiryDate>".$row[expiryDate]."</expiryDate>
					</CreditCardInfo>
				</Txn>";
					
		}
		
		$this->xml .= "</TxnList>\n</Payment>\n";
		
	}
	
	
	function payment($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear) {
		
		$row = $this->validate($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear);
				
		return $this->_add_traction(0,$row[amount], $this->currency, $row[purchaseOrderNo], $row[cardNumber], $row[cardType], $row[cvv], $row[expiryMonth], $row[expiryYear]);

	}
	
	function refund($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear, $txnID) {

		$row = $this->validate($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear, $txnID);
		return $this->_add_traction(4, $row[amount], $this->currency, $row[purchaseOrderNo], $row[cardNumber], $row[cardType], $row[cvv], $row[expiryMonth], $row[expiryYear], $row[txnID]);

	}
	
	function reversal($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear, $txnID) {

		$row = $this->validate($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear, $txnID);
		return $this->_add_traction(6, $row[amount], $this->currency, $row[purchaseOrderNo], $row[cardNumber], $row[cardType], $row[cvv], $row[expiryMonth], $row[expiryYear], $row[txnID]);

	}
	
	
	function _add_traction($txnType, $amount, $currency, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear, $txnID = false, $preauthID = false) {

		$this->txn[] = Array("txnType" => $txnType,
								"amount" => $amount,
								"currency" => $currency,
								"purchaseOrderNo" => $purchaseOrderNo,
								"cardNumber" => $cardNumber,
								"cardType" => $cardType,
								"cvv" => $cvv,
								"expiryDate" => $expiryMonth . "/". substr($expiryYear,-2),
								"txnID" => $txnID,
								"preauthID" => $preauthID);

		return;

	}
	
	
	function http() {
	
		$http_ok = false;
		$http_found_xml = false;
		
		$this->xml_header = "POST " .  $this->serverPath . " HTTP/1.1\r\n";
		$this->xml_header .= "Host: " . $this->serverHost . "\r\n";
		$this->xml_header .= "Content-Type: text/xml\r\n";
		$this->xml_header .= "Content-Length: " . strlen($this->xml) . "\r\n";
		$this->xml_header .= "Connection: Close\r\n\r\n";
		$this->xml_header .= $this->xml . "\r\n\r\n";

		$socket = @fsockopen($this->serverHost, $this->serverPort, $errno, $errstr, $this->serverTimeout) or $this->error_display(510,"Unable to connect to $this->serverHost on port $this->serverPort");

		fputs($socket, $this->xml_header);
		
		// GET RESPONSE
		
		while(!feof($socket)) {
			$line = trim(fgets($socket, 1024));
			
			// HANDLE STATUS CODES
			
			if(substr($line,0,8) == "HTTP/1.1") {
				
				$line_array = explode(" ",$line);
				$code = $line_array[1];
				$code_text = $line_array[2];
				if($code == "200" || $code == "100") {
					
					$http_ok = true;
					
				} else {
					
					// ERROR STATUS CODE
					
					$this->error_display(511,"$code $code_text");
					
				}
				
			}
			
			if(strstr($line, "<"."?xml")) $http_found_xml = true;
			
			// ADD LINE
			
			if($http_ok && $http_found_xml) $this->xml_response .= $line . "\n";
			
		}
		
		// NO BUFFER RETURNED
		
		if(!$this->xml_response) $this->error_display(511);
	
		fclose($socket);

	}
	
	function error_display($code,$msg = false) {
		
		$error = $this->error_get_message($code);
		$this->error_code = $code;
		$this->error_desc = $error[0];
		if($error[1]) $this->error_desc = " " . $error[1];
		if($msg) $this->error_desc = " " . $msg;
		return;
		
	}
	
	function error_get_message($code,$noArray = false) {
		
		switch($code) {
		
			// BANK RESPONSE CODES
			case "00": $text = "Transaction Approved."; break;
			case "01": $text = "Refer to Card Issuer"; break;
			case "02": $text = "Refer to Issuer's Special Conditions"; break;
			case "03": $text = "Invalid Merchant"; break;
			case "04": $text = "Pick Up Card"; break;
			case "05": $text = "Do Not Honour"; break;
			case "06": $text = "Error"; break;
			case "07": $text = "Pick Up Card, Special Conditions"; break;
			case "08": $text = "Approved. Sign Receipt."; break;
			case "09": $text = "Request in Progress"; break;
			case "10": $text = "Partial Amount Approved"; break;
			case "11": $text = "Approved VIP"; break;
			case "12": $text = "Invalid Transaction"; break;
			case "13": $text = "Invalid Amount"; break;
			case "14": $text = "Invalid Card Number"; break;
			case "15": $text = "No Such Issuer"; break;
			case "16": $text = "Approved, Update Track 3"; break;
			case "17": $text = "Customer Cancellation"; break;
			case "18": $text = "Customer Dispute"; break;
			case "19": $text = "Re-enter Transaction"; break;
			case "20": $text = "Invalid Response"; break;
			case "21": $text = "No Action Taken"; break;
			case "22": $text = "Suspected Malfunction"; break;
			case "23": $text = "Unacceptable Transaction Fee"; break;
			case "24": $text = "File Update not Supported by Receiver"; break;
			case "25": $text = "Unable to Locate Record on File"; break;
			case "26": $text = "Duplicate File Update Record"; break;
			case "27": $text = "File Update Field Edit Error"; break;
			case "28": $text = "File Update File Locked Out"; break;
			case "29": $text = "File Update not Successful"; break;
			case "30": $text = "Format Error"; break;			
			case "31": $text = "Bank not Supported by Switch"; break;
			case "32": $text = "Completed Partially"; break;
			case "33": $text = "Expired Card - Pick Up"; break;
			case "34": $text = "Suspected Fraud - Pick Up"; break;
			case "35": $text = "Contact Acquirer - Pick Up"; break;
			case "36": $text = "Restricted Card - Pick Up"; break;
			case "37": $text = "Call Acquirer Security - Pick Up"; break;
			case "38": $text = "Allowable PIN Tries Exceeded"; break;
			case "39": $text = "No CREDIT Account"; break;
			case "40": $text = "Requested Function not Supported"; break;
			case "41": $text = "Lost Card - Pick Up"; break;
			case "42": $text = "No Universal Amount"; break;
			case "43": $text = "Stolen Card - Pick Up"; break;
			case "44": $text = "No Investment Account"; break;
			case "51": $text = "Insufficient Funds"; break;
			case "52": $text = "No Cheque Account"; break;
			case "53": $text = "No Savings Account"; break;
			case "54": $text = "Expired Card"; break;
			case "55": $text = "Incorrect PIN"; break;
			case "56": $text = "No Card Record"; break;
			case "57": $text = "Transaction not Permitted to Cardholder"; break;
			case "58": $text = "Transaction not Permitted to Terminal"; break;
			case "59": $text = "Suspected Fraud"; break;
			case "60": $text = "Call Bank Help Desk. Pick Up Card."; break;
			case "61": $text = "Over Card Limit"; break;
			case "62": $text = "Restricted Card"; break;
			case "63": $text = "Security Violation"; break;
			case "64": $text = "Original Amount Incorrect"; break;
			case "65": $text = "Exceeds Withdrawal Frequency Limit"; break;
			case "66": $text = "Card Acceptor Call Acquirer Security"; break;
			case "67": $text = "Hard Capture - Pick Up Card at ATM"; break;
			case "68": $text = "Response Received Too Late"; break;
			case "75": $text = "Too Many Atempts at PIN"; break;
			case "76": $text = "Transaction Approved. Key Change Required."; break;
			case "77": $text = "Transaction Approved."; break;
			case "86": $text = "ATM Malfunction"; break;
			case "87": $text = "No Envelope Inserted"; break;
			case "88": $text = "Unable to Dispense"; break;
			case "89": $text = "Administration Error"; break;
			case "90": $text = "Cut-off in Progress"; break;
			case "91": $text = "Issuer Not Available"; break;
			case "92": $text = "Financial Institution not Found"; break;
			case "93": $text = "Already Settled"; break;
			case "94": $text = "Duplicate Transmission"; break;
			case "95": $text = "Reconcile Error"; break;
			case "96": $text = "System Malfunction"; break;
			case "97": $text = "Transaction Approved. Reconciliation Totals Reset"; break;
			case "98": $text = "MAC Error."; break;
			case "99": $text = "Reserved for National Use"; break;
			
			
			// SECUREPAY PAYMENT SERVER RESPONSE CODES
			case "000": $text = "Normal"; $desc = "Message processed correctly (check transaction response for details)."; break;
			case "100": $text = "Invalid Transaction Amount"; $desc = "If payment transaction amount is non-integer, negative, or zero"; break;
			case "101": $text = "Invalid Card Number"; $desc = "If credit card number contains characters other digits, or bank does not recognize this number as a valid credit card number"; break;
			case "102": $text = "Invalid Expiry Date Format"; $desc = "If expiry date does not follow the format MM/YY or contains an invalid date"; break;
			case "103": $text = "Invalid Purchase Order"; $desc = "If purchase order is an empty string"; break;
			case "104": $text = "Invalid Merchant ID"; $desc = "If Merchant ID does not follow the format XXXDDDD, where X is a letter and D is a digit, or Merchant ID is not found in SecurePays database."; break;
			case "106": $text = "Card Type Unsupported"; $desc = "Merchant is not configured to accept payment from this particular Credit Card type"; break;
			case "109": $text = "Invalid Credit Card CVV Number Format"; $desc = "CVV Number contains character other than digits or contains more than 6 characters"; break;
			case "110": $text = "Unable To Connect To Server"; $desc = "Produced by SecurePay Client API when unable to establish connection to SecurePay Payment Gateway"; break;
			case "111": $text = "Server Connection Aborted During Transaction"; $desc = "Produced by SecurePay Client API when connection to SecurePay Payment Gateway is lost after the payment transaction has been sent"; break;
			case "112": $text = "Transaction Timed Out By Client"; $desc = "Produced by SecurePay Client API when no response to payment transaction has been received from SecurePay Payment Gateway within predefined time period (default 80 seconds)"; break;
			case "113": $text = "General Database Error"; $desc = "Payment Gateway was unable to read or write information to the database while processing the transaction"; break;
			case "114": $text = "Error Loading Properties File"; $desc = "Payment Gateway encountered an error while loading configuration information for this transaction"; break;
			case "115": $text = "Fatal Unknown Server Error"; $desc = "Transaction could not be processed by the Payment Gateway due to unknown reasons"; break;
			case "116": $text = "Function Unavailable Through Bank"; $desc = "The bank doesnt support the requested transaction type"; break;
			case "117": $text = "Message Format Error"; $desc = "SecurePay Payment Gateway couldnt correctly interpret the transaction message sent"; break;
			case "118": $text = "Unable to Decrypt Message"; $desc = "SecurePays security methods were unable to decrypt the message"; break;
			case "119": $text = "Unable To Encrypt Message"; $desc = "SecurePays security methods were unable to encrypt the message"; break;
			case "123": $text = "Gateway Timed Out"; $desc = "Produced by SecurePay Payment Gateway when no response to the transaction has been received from bank gateway within predefined time period"; break;
			case "124": $text = "Gateway Connection Aborted During Transaction"; $desc = "Produced by SecurePay Payment Gateway when connection to bank gateway is lost after the payment transaction has been sent"; break;
			case "125": $text = "Unknown Error Code"; $desc = "Produced by the bank gateway, textual description of the actual problem is stored in the database"; break;
			case "126": $text = "Unable To Connect To Gateway"; $desc = "SecurePay Payment Gateway couldnt establish a connection to Bank Gateway"; break;
			case "127": $text = "Invalid Phone Number"; $desc = ""; break;
			case "128": $text = "Invalid Client ID"; $desc = ""; break;
			case "129": $text = "Invalid Transaction Type"; $desc = ""; break;
			case "130": $text = "Invalid Frequency Type"; $desc = ""; break;
			case "131": $text = "Invalid Number Format"; $desc = "A sting entered cannot be parsed as an integer. I.e. string must contain only digits, or preceding - sign"; break;
			case "132": $text = "Invaild Date Format"; $desc = "Date entered does not follow the format DD/MM/YYYY, where DD is the 2-digit day of the month, MM is the 2-digit month number, and YYYY is the 4-digit year number; also if month is < 1 or > 12, or date is < 0 or > maximum days in that month"; break;
			case "133": $text = "Transaction For Refund Not In Database"; $desc = "Refund operation requested, and the original approved transaction is not found in the database"; break;
			case "134": $text = "This Transaction Fully Or Partly Refunded"; $desc = "Refund operation is requested, and the given transaction has already been fully or partially refunded"; break;
			case "135": $text = "Transaction For Reversal Not In Database"; $desc = "Reversal operation requested, and the original approved transaction is not found in the database"; break;
			case "136": $text = "This Transaction Already Reversed"; $desc = "Reversal operation requested, and the given transaction has already been reversed"; break;
			case "137": $text = "Pre-Auth Transaction Not Found In Database"; $desc = "Complete operation requested, and the matching approved pre-auth transaction is not found in the database"; break;
			case "138": $text = "This Pre-Auth Already Completed"; $desc = "Complete operation requested, and the given pre-auth has already been completed"; break;
			case "139": $text = "No Authorisation Code Supplied"; $desc = "Client performing Complete transaction did not provide Preauth Code from original pre-auth transaction"; break;
			case "140": $text = "Partially Refunded, Do Refund To Complete"; $desc = "Reversal operation is requested, and the given transaction has already been partially refunded"; break;
			case "141": $text = "No Transaction ID Supplied"; $desc = "Client performing Refund/Reversal transaction did not provide original payments Bank Transaction ID"; break;
			case "142": $text = "Pre-Auth Was Done For Smaller Amount"; $desc = "Complete operation was requested but the amount specified is greater the pre-authorised amount"; break;
			case "143": $text = "Payment amount smaller than minimum"; $desc = "The payment amount was smaller than the minimum accepted by the merchant"; break;
			case "144": $text = "Payment amount greater than maximum"; $desc = "The payment amount was greater than the maximum accepted by the merchant"; break;
			case "145": $text = "System maintenance in progress"; $desc = "The system maintenance is in progress and the system is currently unable to process transactions"; break;
			case "146": $text = "Duplicate Payment Found"; $desc = "The system located a transaction that seems to be a duplicate of the current attempt. Transaction is not passed to bank, and customer should contact their merchant before making payment."; break;
			case "147": $text = "No Valid MCC Found"; $desc = "The merchant does not have a valid MCC (Merchant Category Code) set up to complete this transaction (Refers to Recurring transactions only at present)."; break;
			case "148": $text = "Invalid Track 2 Data"; $desc = "If track 2 data is invalid length."; break;
			case "149": $text = "Track 2 Data Not Supplied"; $desc = "Track 2 data was not supplied and the transaction cannot be completed (Refers to Card Present transactions only at present)."; break;
			case "151": $text = "Invalid Currency Code"; $desc = "The currency code supplied does not match the format required by SecurePay. Check the list of accepted currency codes."; break;
			case "152": $text = "Multi-currency not supported by bank"; $desc = "The financial institution used for this payment only accepts payments in Australian dollars (AUD)."; break;
			case "153": $text = "External Database Error"; $desc = "A database error has occurred outside the SecurePay Payment Server (e.g. DEFT, etc)"; break;
			case "175": $text = "No Action Taken"; $desc = "The payment was held in the processing queue too long and was rejected without processing. Usually a symptom of slow bank responses. Additional terminal IDs may help solve this problem if it occurs frequently."; break;
			case "190": $text = "Merchant Gateway Not Configured"; $desc = "The gateway for the merchant has been reserved, but not yet configured to be live by SecurePay staff."; break;
			case "195": $text = "Merchant Gateway Disabled"; $desc = "SecurePay has disabled the merchant gateway."; break;
			case "199": $text = "Merchant Gateway Discontinued"; $desc = "SecurePay has discontinued the merchant gateway."; break;

			
			// SECURE PAY STATUS CODES
			case "504": $text = "Invalid Merchant ID"; $desc = "If Merchant ID does not follow the format XXXDDDD, where X is a letter and D is a digit, or Merchant ID is not found in SecurePays database."; break;
			case "505": $text = "Invalid URL"; $desc = "The URL passed to either Echo, Query, or Payment object is invalid."; break;
			case "510": $text = "Unable To Connect To Server"; $desc = "Produced by SecurePay Client API when unable to establish connection to SecurePay Payment Gateway."; break;
			case "511": $text = "Server Connection Aborted During Transaction"; $desc = "Produced by SecurePay Client API when connection to SecurePay Payment Gateway is lost after the payment transaction has been sent."; break;
			case "512": $text = "Transaction timed out By Client"; $desc = "Produced by SecurePay Client API when no response to payment transaction has been received from SecurePay Payment Gateway within predefined time period (default 80 seconds)."; break;
			case "513": $text = "General Database Error"; $desc = "Unable to read information from the database."; break;
			case "514": $text = "Error loading properties file"; $desc = "Payment Gateway encountered an error while loading configuration information for this transaction."; break;
			case "515": $text = "Fatal Unknown Error"; $desc = "Transaction could not be processed by the Payment Gateway due to unknown reasons."; break;
			case "516": $text = "Request type unavailable"; $desc = "SecurePay system doesnt support the requested transaction type."; break;
			case "517": $text = "Message Format Error"; $desc = "SecurePay Payment Gateway couldnt correctly interpret the transaction message sent."; break;
			case "524": $text = "Response not received"; $desc = "The client could not receive a response from the server."; break;
			case "545": $text = "System maintenance in progress"; $desc = "The system maintenance is in progress and the system is currently unable to process transactions."; break;
			case "550": $text = "Invalid password"; $desc = "The merchant has attempted to process a request with an invalid password."; break;
			case "575": $text = "Not implemented"; $desc = "This functionality has not yet been implemented."; break;
			case "577": $text = "Too Many Records for Processing"; $desc = "The maximum number of allowed events in a single message has been exceeded."; break;
			case "580": $text = "Process method has not been called"; $desc = "The process() method on either Echo, Payment or Query object has not been called."; break;
			case "595": $text = "Merchant Disabled"; $desc = "SecurePay has disabled the merchant and the requests from this merchant will not be processed."; break;
			
			// GATEWAY RESPONSE CODES
			case "900": $text = "Invalid Transaction Amount"; break;
			case "901": $text = "Invalid Credit Card Number"; break;
			case "902": $text = "Invalid Expiry Date Format"; break;
			case "903": $text = "Invalid Transaction Number"; break;
			case "904": $text = "Invalid Merchant/Terminal ID"; break;
			case "905": $text = "Invalid E-Mail Address"; break;
			case "906": $text = "Card Unsupported"; break;
			case "907": $text = "Card Expired"; break;
			case "908": $text = "Insufficient Funds"; break;
			case "909": $text = "Credit Card Details Unknown"; break;
			case "910": $text = "Unable To Connect To Bank"; break;
			case "913": $text = "Unable To Update Database"; break;
			case "914": $text = "Power Failure"; break;
			case "915": $text = "Fatal Unknown Gateway Error"; break;
			case "916": $text = "Invalid Transaction Type Requested"; break;
			case "917": $text = "Invalid Message Format"; break;
			case "918": $text = "Encryption Error"; break;
			case "919": $text = "Decryption Error"; break;
			case "922": $text = "Bank Is Overloaded"; break;
			case "923": $text = "Bank Timed Out"; break;
			case "924": $text = "Transport Error"; break;
			case "925": $text = "Unknown Bank Response Code"; break;
			case "926": $text = "Gateway Busy"; break;
			case "928": $text = "Invalid Customer ID"; break;
			case "932": $text = "Invalid Transaction Date"; break;
			case "933": $text = "Transaction Not Found"; break;
			case "936": $text = "Transaction Already Reversed"; break;
			case "938": $text = "Pre-Auth Already Completed"; break;
			case "941": $text = "Invalid Transaction ID Supplied"; break;
			case "960": $text = "Contact Card Issuer"; break;
			case "970": $text = "File Access Error"; break;
			case "971": $text = "Invalid Flag Set"; break;
			case "972": $text = "PIN-PAD Offline"; break;
			case "973": $text = "Invoice Unavailable"; break;
			case "973": $text = "Invoice Unavailable"; break;
			case "974": $text = "Gateway Configuration Error"; break;
			case "975": $text = "No Action Taken"; break;
			case "976": $text = "Unknown Currency Code"; break;
			case "977": $text = "Too Many Records for Processing"; break;
			case "978": $text = "Merchant Blocked"; break;
			
			// CUSTOM ERROR CODE
			case "1000": $text = "Validation Error"; break;
			
		}
		
		if($noArray) {
			
			return $text;
			
		} else {
			
			return Array($text,$desc);
			
		}
		
	}
	
	function validate($amount, $purchaseOrderNo, $cardNumber, $cardType, $cvv, $expiryMonth, $expiryYear, $txnID = false, $preauthID = false) {
		
		// FORMAT TO CURRENCY FORMAT
		$amount = number_format($amount, 2,"","");
		
		$amount = str_replace(".","",$amount);
		// CAN NOT PROCESS ZERO
		if($amount < 0) $this->validate_errors[] = $this->error_get_message(100,1);
		
		// REMOVE SPACES & SINGLE QUOTES
		$purchaseOrderNo = str_replace(Array(" ","'"),"",$purchaseOrderNo);
		// TRUNCATE TO 60 CHARACTERS
		$purchaseOrderNo = substr($purchaseOrderNo,0, 60);

		// CHECK CARD TYPE (INT)
		$cardType = $this->get_card_type($cardType);
		
		// CARD NOT SUPPORTED
		if(!$cardType) $this->validate_errors[] = $this->error_get_message(906,1);
		
		// REMOVE ALL NON DIGITS
		$cardNumber = ereg_replace("[^0-9]", "", $cardNumber);

		// CHECK CARD NUMBER IS NOT EMPTY
		if(!$cardNumber) $this->validate_errors[] = $this->error_get_message(901,1);

		// VALIDATE CARD NUMBER
		if(!$this->validate_cardnumber($cardNumber,$cardType)) $this->validate_errors[] = $this->error_get_message(901,1);

		// VALIDATE EXPIRY MONTH
		if(!is_numeric($expiryMonth) || $expiryMonth < 1 || $expiryMonth > 12) $this->validate_errors[] = $this->error_get_message(902,1);

		// VALIDATE EXPIRY YEAR && EXPIRY DATE
		$currentYear = date('Y'); 
		$currentMonth = date('m'); 
		settype($currentYear, 'integer'); 
		
		// HANDLE 2 DIGIT YEAR AND COVERT TO 4
		if($expiryYear < 2000) { $expiryYear = $expiryYear + 2000; }
		
		// VALIDATE EXPIRY YEAR
		if(!is_numeric($expiryYear) || $expiryYear < $currentYear || $expiryYear > $currentYear + 10) $this->validate_errors[] = $this->error_get_message(907,1);

		// HAS CARD EXPIRED
		if(($expiryMonth < $currentMonth && $expiryYear == $currentYear) || $expiryMonth < $currentMonth && $expiryYear < $currentYear) $this->validate_errors[] = $this->error_get_message(907,1);
		
		// REMOVE ALL NON DIGITS
		$cvv = ereg_replace("[^0-9]", "", $cvv);

		// REMOVE DUPLICATE ERROR MESSAGES
		array_unique($this->validate_errors);
		if(count($this->validate_errors)) {
				$this->error_display(1000,"See validate_errors() to display a full list of validation errors");
		}
		
		return Array("amount" => $amount,
					 "purchaseOrderNo" => $purchaseOrderNo,
					 "cardNumber" => $cardNumber,
					 "cardType" => $cardType,
					 "cvv" => $cvv,
					 "expiryMonth" => $expiryMonth,
					 "expiryYear" => $expiryYear,
					 "txnID" => $txnID,
					 "preauthID" => $preauthID);
	}
	
	function validate_cardnumber($cardNumber,$cardType) {
		switch($cardType) {
			case "1": // JCB
				$validate_1 = ereg("^(3[0-9]{4}|2131|1800)[0-9]{11}$", $cardNumber); break; 
			case "2": // AMEX
				$validate_1 = ereg("^3[47][0-9]{13}$", $cardNumber); break; 
			case "3": // DINERS CLUB 
				$validate_1 = ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $cardNumber); break;
			case "4": // BANKCARD 
				$validate_1 = ereg("^5610[0-9]{12}$", $cardNumber); break; 
				
			case "5": // MASTERCARD
				$validate_1 = ereg("^5[1-5][0-9]{14}$", $cardNumber); break; 
				
			case "6": // VISA
				$validate_1 = ereg("^4[0-9]{12}([0-9]{3})?$", $cardNumber); break; 

			default: $validate_1 = 0;
		}
		
		$cardNumber = strrev($cardNumber); 
		$numSum = 0; 

		for($i = 0; $i < strlen($cardNumber); $i++) { 
			$currentNum = substr($cardNumber, $i, 1);

			// Double every second digit
			if($i % 2 == 1) { 
				$currentNum *= 2;
			} 

			// Add digits of 2-digit numbers together
			if($currentNum > 9) { 
				$firstNum = $currentNum % 10; 
				$secondNum = ($currentNum - $firstNum) / 10; 
				$currentNum = $firstNum + $secondNum; 
			}

			$numSum += $currentNum; 
		}

		// If the total has no remainder it's OK 
		$validate_2 = ($numSum % 10 == 0);

		if($validate_1 && $validate_2) {
			return true;
		} else { 
			return false;
		}

		
	}
	
	function get_card_type($type) {
		
		switch(strtolower($type)) { 
			
			case "jc": 
			case "jcb": 
			case "1": 
				return 1; 
				break; 
				
			case "ax": 
			case "american express": 
			case "a":
			case "amex":
			case "2":
				return 2; 
				break; 
				
			case "dc": 
			case "diners club": 
			case "3": 
				return 3; 
				break; 
				
			case "bc": 
			case "bankcard": 
			case "4": 
				return 4; 
				break; 
				
			case "mc": 
			case "mastercard": 
			case "m": 
			case "5": 
				return 5;
				break; 
			
			case "vs": 
			case "visa": 
			case "v": 
			case "6": 
				return 6; 
				break; 

			default: return 0;
			
		}
		
	}
	
	function get_card_name($type) {
		
		switch(strtolower($type)) { 
			
			case "1": return "JCB"; break; 
			case "2": return "American Express"; break; 
			case "3": return "Diners Club"; break; 
			case "4": return "Bankcard"; break; 
			case "5": return "MasterCard"; break; 
			case "6": return "Visa"; break;
			default: return "Unknown";
			
		}
		
	}

}

?>
