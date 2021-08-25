<?

$encoding="UTF-8"
$messageID = md5(microtime());
$messageTimestamp = date("YdmHis") + "000" + "+600";
$serverTimeout="60";
$apiVersion="xml-4.2"

$xml .= "<"."?xml version=\"1.0\" encoding=\"$encoding\"?".">\n<SecurePayMessage>\n";

$xml .= "<MessageInfo>
			<messageID>$messageID</messageID>
			<messageTimestamp>$messageTimestamp</messageTimestamp>
			<timeoutValue>$serverTimeout</timeoutValue>
			<apiVersion>$apiVersion</apiVersion>
		</MessageInfo>\n";

$xml .= "<RequestType>Payment</RequestType>\n";


$xml .= "<MerchantInfo>
			<merchantID>ebt0022</merchantID>
			<password>k87jkqdam</password>
		</MerchantInfo>\n";

$xml .= "<Payment>\n\t<TxnList count=1>";


	$xml .= "<Txn ID=1>";
	$xml .= "<txnType>0</txnType>
			<txnSource>23</txnSource>
			<amount>10</amount>
			<currency>AUD</currency>
			<purchaseOrderNo>222</purchaseOrderNo>";

	$xml .= "<CreditCardInfo>
				<cardNumber>4940521305989618</cardNumber>
				<cvv></cvv>
				<expiryDate>06/11</expiryDate>
			</CreditCardInfo>
		</Txn>";

$xml .= "</TxnList>\n</Payment>\n";

$xml .= "</SecurePayMessage>\n";

$request  = "POST /xmlapi/payment HTTP/1.1\r\n";
$request .= "Host: ssl://203.89.255.131\r\n";
$request .= "Content-Length: " . strlen($data) . "\r\n";
$request .= "Content-Type: text/xml\r\n";
$request .= "Connection: Close\r\n\r\n";
$request .= $xml . "\r\n\r\n";

$secureSocket = fsockopen("ssl://203.89.255.131", "443", $errNo, $errorStr, "60");



if(!$secureSocket) {

	print "A fatal error has occurred and i was unable to continue.\r\n\r\nErrorCode: ".$errorNo."\r\nErrorString: ".$this->errorStr."";
	exit;

} else {

		fputs($secureSocket, $request);

		// GET RESPONSE

		while(!feof($secureSocket)) {
			$line = trim(fgets($secureSocket, 1024));

			// HANDLE STATUS CODES

			if(substr($line,0,8) == "HTTP/1.1") {

				$line_array = explode(" ",$line);
				$code = $line_array[1];
				$code_text = $line_array[2];
				if($code == "200" || $code == "100") {

					$http_ok = true;

				} else {

					// ERROR STATUS CODE

					$error_display(511,"$code $code_text");

				}

			}

			if(strstr($line, "<"."?xml")) $http_found_xml = true;

			// ADD LINE

			if($http_ok && $http_found_xml) $xml_response .= $line . "\n";

		}

		// NO BUFFER RETURNED

		if(!$xml_response) $error_display(511);

		fclose($socket);

}

echo $xml_response;
