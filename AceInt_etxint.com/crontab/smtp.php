<?
// Here we completely assume that message is a reasonable size.
// The caller should verify that its size is reasonable.

// This function returns an array of three elements, labelled "headers",
// "headersref", and "body".  The "headers" element is an array
// containing all of the message header fields in order.  Each header
// field is an array of two elements: the "name", and the "body".  The
// field body is neither parsed nor unfolded.  The "headersref" element
// is an array containing the field bodies, indexed by name.  Note that
// only the last occurrence of each header is contained in this array.
// Also note that the keys in the "headersref" array are always in
// lowercase.
//
// On failure, this function returns false.

function smtp_parse_message( &$message)
{
	$headers = array();
	$headersref = array();

	$i = 0;
	while( 1) {
		$pos = strpos( $message, "\n", $i);
		if( $pos == false) return false;
		if( $pos == $i) {
			// We're done parsing the headers
			$body = substr( $message, $i + 1);
			break;
		}

		$line = substr( $message, $i, $pos + 1 - $i);
		$i = $pos + 1;

		$c = substr( $line, 0, 1);
		if( $c == " " || $c == "\t") {
			// This line should be appended to the previous header
			#if( sizeof($headers) == 0) return false;
			$headers[sizeof($headers)-1]["body"] .= $line;
			$headersref[strtolower($fieldname)] .= $line;
			continue;
		}

		$fieldname = "";
		$w = 0;
		while( $w < strlen( $line)) {
			$c = substr( $line, $w, 1);
			if( $c == ':' || ord($c) < 33 || ord($c) > 126) break;
			$fieldname .= $c;
			$w++;
		}

		$w = strpos( $line, ":", $w);
		#if( $w === false) return false;
		$w++;

		$fieldbody = substr( $line, $w);

		$headersref[strtolower($fieldname)] = $fieldbody;
		$headers[] = array("name"=>$fieldname,"body"=>$fieldbody);
	}
	
	#return array( "headers"=>$headers, "headersref"=>$headersref, "body"=>$body);
	$headers=array($headers);
	$headersref=array($headersref);
	$body=array($body);
}
?>

