<?
include_once( "/virtual/ebanc/database/smtp.php");

// Given the headers and body of an SMTP message, this function performs
// mime parsing and decoding.  It returns an associative array
// containing at least these two elements:
//
// "type"     -The first part of the mime type  (Eg, "text")
// "subtype"  -The second part of the mime type (Eg, "plain")
// "mimetype" -For convenience - type/subtype
//
// If the type is "multipart", then there will be one additional
// element, called "parts", which is an array of all parts of the
// message.  Each part has the same form as the entire return value of
// this function.  If the type is anything other than "multipart", then
// the following additional elements will exist:
//
// "charset"  -The character set of the body content
// "disp"     -The content disposition (usually "inline" or "attachment")
// "filename" -The suggested filename
// "body"     -The unencoded message body
//
// On failure, this function returns false.

function parse_mime( &$headersref, &$body)
{
	$content_type = $headersref["content-type"];
	if( !isset( $content_type)) {
		$content_type = "text/plain ;charset=us-ascii";
	}

	$disp = $headersref["content-disposition"];
	if( !isset( $disp)) {
		$disp = "inline";
	}

	$transfer_encoding = $headersref["content-transfer-encoding"];
	if( !isset( $transfer_encoding)) {
		$transfer_encoding = "7bit";
	}

	list($data,$typeparms) = parse_mime_header( $content_type);

	$tokens = explode( "/", $data);
	if( sizeof( $tokens) < 2) {
		$type = "text";
		$subtype = "plain";
	} else {
		$type = strtolower(trim( $tokens[0]));
		$subtype = strtolower(trim( $tokens[1]));
	}
	$mimetype = "$type/$subtype";

	if( $type == 'multipart') {
		$boundary = $typeparms["boundary"];
		if( !isset( $boundary)) return false;

		$b1 = "--$boundary";
		$b1len = strlen( $b1);

		// Split up the message
		$parts = array();
		$pos = $start = 0;
		$skipped_first = 0;
		$bodylen = strlen( $body);
		while( 1) {
			if( $pos >= $bodylen) return false;
			$pos = strpos( $body, $b1, $pos);
			if( $pos === false) return false;
			$end = $pos - 1;

			// Make sure that it's preceded by a newline
			if( $pos > 0 && substr($body,$pos-1,1) != "\n") {
				$pos += $b1len;
				continue;
			}

			$pos += $b1len;

			$ending = 0;
			if( $pos >= $bodylen) return false;
			if( substr( $body, $pos, 2) == '--') {
				$ending = 1;
				$pos += 2;
			}

			// Skip whitespace at the end of the line
			while( $pos < $bodylen &&
				ereg( "[ \t]", substr( $body, $pos, 1))) $pos++;

			// Is this really the boundary?
			if( $pos >= $bodylen) return false;
			if( substr( $body, $pos, 1) != "\n") continue;

			if( $skipped_first) {
				$smtp = smtp_parse_message(substr($body,$start,$end-$start));
				if( $smtp === false) return false;

				$parse = parse_mime( $smtp["headersref"], $smtp["body"]);
				if( $parse === false) return false;

				$parts[] = $parse;
			} else $skipped_first = 1;

			if( $ending) break;

			$pos ++;
			$start = $pos;
		}

		return array( "type"=>$type, "subtype"=>$subtype,
			"mimetype"=>$mimetype, "parts"=>$parts);
	}

	$charset = $typeparms["charset"];
	if( !isset( $charset)) $charset = "us-ascii";
	$charset = strtolower( $charset);

	list($data,$dispparms) = parse_mime_header( $disp);
	$disp = strtolower( $data);

	list($data,$encparms) = parse_mime_header($transfer_encoding);
	$enc = strtolower( $data);
	if( $enc == 'quoted-printable') {
		$convbody = quoted_printable_decode( $body);
	} else if( $enc == 'base64') {
		$convbody = base64_decode( $body);
	} else {
		$convbody = $body;
	}

	$filename = $dispparms["filename"];

	return array( "type"=>$type, "subtype"=>$subtype,
		"mimetype"=>$mimetype, "charset"=>$charset,
		"disp"=>$disp, "filename"=>$filename, "body"=>$convbody);
}

// Same as the above function, but takes an unparsed SMTP message
// as a parameter.  Note that the SMTP headers are thrown out.

function smtp_parse_mime( &$message)
{
	$smtp = smtp_parse_message( $message);
	if( $smtp === false) return false;

	return parse_mime( $smtp["headersref"], $smtp["body"]);
}

function parse_mime_header( &$field)
{
	$tokens = explode( ";", $field);
	$numtokens = sizeof( $tokens);

	$parameters = array();
	for( $i=1; $i < $numtokens; $i++)
	{
		$tok = $tokens[$i];
		$pos = strpos( $tok, "=");
		if( $pos === false) continue;

		$parmname = strtolower(trim( substr( $tok, 0, $pos)));
		$parmbody = trim( substr( $tok, $pos + 1));
		if( substr($parmbody,0,1) == '"') {
			// Strip quotes
			$parmbody = substr( $parmbody, 1, -1);
			// Strip escapes
			$parmbody = ereg_replace( "\\(.)", "\1", $parmbody);
		}

		$parameters[$parmname] = $parmbody;
	}

	return array(trim($tokens[0]),$parameters);
}
?>

