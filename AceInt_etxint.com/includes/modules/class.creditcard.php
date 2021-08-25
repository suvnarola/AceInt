<?

/**
 * Class to process a credit card transaction.
 *
 * @package CreditCard
 * @author Antony Puckey
 * @copyright Copyright 2005, RDI Host Pty Ltd
 * @return Array containing Success/Failure Code and the response code for the transcation..
 *
 * Example:
 * <code>
 * $secureCard = new processCreditCard('1');
 *
 * $secureCard->merchantID = "abc0001";
 * $secureCard->amount = "1008;
 * $secureCard->ponum = "7458";
 * $secureCard->addCreditcard("4444 3333 2222 1111");
 * $secureCard->expiryMonth = "01";
 * $secureCard->expiryYear = "07";
 * $secureCard->optionalInfo = "Joe M Blogs";
 *
 * $secureCard->buildQuery();
 *
 * $secureReply = $secureCard->sendQuery();
 * </code>
 *
 * Example Response:
 * <code>
 * Array
 *	(
 *	    [successfull] => 1
 *	    [ponum] => 7458
 *	    [amount] => 108
 *	    [response_code] => 08
 *	    [txn_id] => 013997
 *	    [settle_date] => 20050505
 *	    [card_type] => Visa
 *	    [optional_info] => Joe M Blogs
 *	)
 *</code>
 */

class processCreditCard {

	/**
	 * Contains the Merchant ID to send to Securepay.
	 *
	 * Required Field.
	 */

	var $merchantID;

	/**
	 * Conains the Amount of the transaction in cents.
	 *
	 * Required Field.
	 */

	var $amount;

	/**
	 * Contains the Purchase Order Number.
	 * This field is used for searching Securepay's online transaction database.
	 *
	 * Required Field.
	 */

	var $ponum;

	/**
	 * Contains the Credit Card number. In 16 or 15 digit form.
	 *
	 * Required Field.
	 */

	var $creditcardNumber;

	/**
	 * Contains optional Creditcard Verification Number.
	 */

	var $cvvNumber = false;

	/**
	 * Contains Expiry Month.
	 *
	 * Required Field.
	 */

	var $expiryMonth;

	/**
	 * Contains the Expiry Year.
	 *
	 * Required Field.
	 */

	var $expiryYear;

	/**
	 * Contains Optional info to be sent back with the transaction response.
	 */

	var $optionalInfo;

	/**
	 * Tells the class to go into test mode and send queries to the Test Server.
	 */

	var $test = false;

	/**
	 * Contains the Server host to connect to.
	 *
	 * @example "ssl://www.securepay.com.au"
	 * @exanple "test.securepay.com.au"
	 */

	var $serverHost;

	/**
	 * Contains the port to talk to the server on.
	 *
	 * 443 for ssl enabled.
	 * 80 for normal web port.
	 */

	var $serverPort;

	/**
	 * Method to use to send data to the Host server.
	 */

	var $method = "POST";

	/**
	 * Protocol to use to send data to the Host server.
	 */

	var $methodhttp = "HTTP/1.1";

	/**
	 * Path of script to send data to the Host server.
	 */

	var $serverPath = "/securepay/payments/process2.asp";

	/**
	 * Sets the timeout whilst connecting to the Host server.
	 */

	var $timeout = 50;

	/**
	 * Contains the Data String to send to the Host server.
	 */

	var $data;

	/**
	 * Contains the Length of the Data String to send to the Host server.
	 */
	var $dataLength;

	/**
	 * Contains the complete request to send to the Host server.
	 */

	var $request;

	/**
	 * Contains the respose received back from the Host server.
	 */

	var $response = array();

	/**
	 * Contains the Error Number if there is an error connecting to the Host server.
	 */

	var $errorNo;

	/**
	 * Contains the Error String if there is an error connecting to the Host server.
	 */

	var $errorStr;

	/**
	 * Initial setup. Sets whether to connect to the test or live server.
	 *
	 * @param bool true or false
	 */

	function processCreditCard($testserver = false) {

		$this->serverHost = ($testserver) ? "test.securepay.com.au" : "ssl://www.securepay.com.au";
		$this->serverPort = ($testserver) ? "80" : "443";

	}

	function addCreditcard($cardNumber) {

		$this->creditcardNumber = ereg_replace("[^0-9]", "", $cardNumber);

	}

	function addExpiryMonth($expireMonth) {

		if(!is_numeric($expireMonth) || $expireMonth < 1 || $expireMonth > 12) {

			//this would be an error.

		} else {

			$this->expiryMonth = $expireMonth;

		}

	}

	function addExpiryYear($expireYear) {

		$currentYear = date('Y');
		$currentMonth = date('m');

		if($expireYear < 2000) { $expireYear = $expireYear + 2000; }

		if(!is_numeric($expireYear) || $expireYear < $currentYear || $expireYear > $currentYear + 10) {
			//$this->doError(5,"The Credit Card expiry year was invalid");
		} else {
			$this->expireYear = $expireYear;
		}

		if(($this->expiryMonth < $currentMonth && $expireYear == $currentYear) || $this->expiryMonth < $currentMonth && $expireYear < $currentYear) {
			//$this->doError(6,"The Credit Card has expired");
		}

	}

	/**
	 * Builds the Request to actually send to the server.
	 */

	function buildQuery() {

		$this->data = "merchantid=".$this->merchantID;
		$this->data .= "&amount=".$this->amount;
		$this->data .= "&ponum=".$this->ponum;
		$this->data .= "&creditCard1=".substr($this->creditcardNumber, 0, 4);
		$this->data .= "&creditCard2=".substr($this->creditcardNumber, 4, 4);
		$this->data .= "&creditCard3=".substr($this->creditcardNumber, 8, 4);
		$this->data .= "&creditCard4=".substr($this->creditcardNumber, 12, 4);
		$this->data .= "&exdate1=".$this->expiryMonth;
		$this->data .= "&exdate2=".$this->expiryYear;

		if($this->cvvNumber) {

			$this->data .= "&cvvno=".$this->cvvNumber;

		}

		$this->data .= "&optional_info=".htmlentities($this->optionalInfo);
		$this->data .= "&success_page=successfull%3D1%26ponum%3D&failure_page=successfull%3D2%26ponum%3D";
		$this->dataLength = strlen($this->data);

		$this->request  = $this->method . " ". $this->serverPath . " " . $this->methodhttp . "\r\n";
		$this->request .= "Host: " . $this->serverHost . "\r\n";
		$this->request .= "Content-Length: " . $this->dataLength . "\r\n";
		$this->request .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$this->request .= "Connection: Close\r\n\r\n";
		$this->request .= $this->data . "\r\n\r\n";

		//var_dump($this->request);

	}

	/**
	 * Connects to the Host server.
	 *
	 * Returns the Resposnse array.
	 */

	function sendQuery() {

		$secureSocket = fsockopen($this->serverHost, $this->serverPort, $this->errorNo, $this->errorStr, $this->timeout);

		if(!$secureSocket) {

			print "A fatal error has occurred and i was unable to continue.\r\n\r\nErrorCode: ".$this->errorNo."\r\nErrorString: ".$this->errorStr."";
			exit;

		} else {

			fputs ($secureSocket, $this->request);

			while(!feof($secureSocket)) {

				$Temp = explode(":", @fgets($secureSocket, 1024));
				$SecureResponseTemp[$Temp[0]] = $Temp[1];

			}

			$Temp2 = explode("&", trim($SecureResponseTemp[Location]));

			foreach($Temp2 as $value) {

				$Temp3 = explode("%3D", $value);

				$this->response[$Temp3[0]] = str_replace("+", " ", $Temp3[1]);

			}

			fclose($secureSocket);

		}

		return $this->response;

	}

	/**
	 * List of Error Response codes.
	 *
	 * Securepay Issued.
	 */

	function getError($code) {

		switch($code) {
			case "00": return "Transaction Approved."; break;
			case "01": return "See Card Issuer."; break;
			case "04": return "Call Auth Centre."; break;
			case "08": return "Approved. Sign Receipt."; break;
			case "11": return "Transaction Approved."; break;
			case "12": return "Card Not Accepted."; break;
			case "31": return "See Card Issuer."; break;
			case "39": return "No CREDIT Account."; break;
			case "50"; return "Test."; break;
			case "51": return "Insuffient Funds."; break;
			case "52": return "No CHEQUE Account."; break;
			case "53": return "No SAVINGS Account."; break;
			case "54": return "Card Expired."; break;
			case "55": return "Invalid PIN"; break;
			case "60": return "Call Bank Help Desk. Pick Up Card."; break;
			case "61": return "Over Card Limit."; break;
			case "75": return "Too Many Atempts at PIN"; break;
			case "76": return "Transaction Approved. Key Change Required."; break;
			case "80": return "Transaction Approved. Key Change Required."; break;
			case "91": return "Issuer Not Available."; break;
			case "93": return "Already Settled."; break;
			case "94": return "STAN Out Of Sync."; break;
			case "96": return "System Malfunction."; break;
			case "97": return "Transaction Approved. Reconciliation Totals Reset."; break;
			case "98": return "NAC Error."; break;
			case "100": return "Invalid Transaction Amount."; break;
			case "101": return "Invalid Card Number."; break;
			case "102": return "Invalid Expiry Date Format."; break;
			case "103": return "Invalid Purchase Order."; break;
			case "104": return "Invalid Merchant ID."; break;
			case "106": return "Card Type Unsupported."; break;
			case "109": return "Invalid Credit Card CVV Number Format."; break;
			case "110": return "Unable To Connect To Server."; break;
			case "111": return "Server Connection Aborted During Transaction."; break;
			case "112": return "Transaction Timed Out By Client."; break;
			case "113": return "General Database Error."; break;
			case "114": return "Error Loading Properties File."; break;
			case "115": return "Fatal Unknown Server Error."; break;
			case "116": return "Function Unavailable Through Bank."; break;
			case "117": return "Message Format Error."; break;
			case "118": return "Unable to Decrypt Message."; break;
			case "119": return "Unable To Encrypt Message."; break;
			case "123": return "Gateway Timed Out."; break;
			case "124": return "Gateway Connection Aborted During Transaction."; break;
			case "125": return "Unknown Error Code."; break;
			case "126": return "Unable To Connect To Gateway."; break;
			case "127": return "Invalid Phone Number."; break;
			case "128": return "Invalid Client ID."; break;
			case "129": return "Invalid Transaction Type."; break;
			case "130": return "Invalid Frequency Type."; break;
			case "131": return "Invalid Number Format."; break;
			case "132": return "Invaild Date Format."; break;
			case "133": return "Transaction For Refund Not In Database."; break;
			case "134": return "This Transaction Fully Or Partly Refunded."; break;
			case "135": return "Transaction For Reversal Not In Database."; break;
			case "136": return "This Transaction Already Reversed."; break;
			case "137": return "Pre-Auth Transaction Not Found In Database."; break;
			case "138": return "This Pre-Auth Already Completed."; break;
			case "139": return "No Authorisation Code Supplied."; break;
			case "140": return "Partially Refunded, Do Refund To Complete."; break;
			case "141": return "No Transaction ID Supplied."; break;
			case "142": return "Pre-Auth Was Done For Smaller Amount."; break;
			case "900": return "Invalid Transaction Amount."; break;
			case "901": return "Invalid Credit Card Number."; break;
			case "902": return "Invalid Expiry Date Format."; break;
			case "903": return "Invalid Transaction Number."; break;
			case "904": return "Invalid Merchant/Terminal ID."; break;
			case "906": return "Card Unsupported."; break;
			case "907": return "Card Expired."; break;
			case "908": return "Insufficient Funds."; break;
			case "909": return "Credit Card Details Unknown."; break;
			case "910": return "Unable To Connect To Bank."; break;
			case "913": return "Unable To Update Database."; break;
			case "914": return "Power Failure."; break;
			case "915": return "Fatal Unknown Gateway Error."; break;
			case "916": return "Invalid Transaction Type Requested."; break;
			case "917": return "Invalid Message Format."; break;
			case "922": return "Bank Is Overloaded."; break;
			case "923": return "Bank Timed Out."; break;
			case "924": return "Transport Error."; break;
			case "925": return "Unknown Bank Response Code."; break;
			case "926": return "Gateway Busy."; break;
			case "928": return "Invalid Customer ID."; break;
			case "933": return "Transaction Not Found."; break;
			case "936": return "Transaction Already Reversed."; break;
			case "938": return "Pre-Auth Already Completed."; break;
			case "941": return "Invalid Transaction ID Supplied."; break;
			case "960": return "Contact Card Issuer."; break;
			case "970": return "File Access Error."; break;
			case "971": return "Invalid Flag Set."; break;
			case "972": return "PIN-PAD Offline."; break;
			case "973": return "Invoice Unavailable."; break;
			case "975": return "No Action Taken."; break;
			default: return "Unknown Error Response"; break;
	 	}

	}

	/**
	 * Type of Transaction.
	 */

	function getType($code) {

		switch($code) {
			case "1": return "Fee Payment"; break;
			case "2": return "MemberShip"; break;
			case "3": return "E Rewards"; break;
			case "4": return "Real Estate"; break;
			case "5": return "Monthly Cash Fees"; break;
			case "6": return "Conversion"; break;
			case "7": return "Dave's Fun Stuff"; break;
			case "8": return "E Foundation"; break;
			default: return "NEW"; break;
		}

	}


}


?>