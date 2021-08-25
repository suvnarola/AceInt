<?

 /**
  * Directory Send.
  */

if($_REQUEST['refresh']) {

 include("directory_pre_send.php");

} else {

 include("global.php");
 include("directory_function.php");
 //include("includes/zip.lib.php");
 include("zip.lib.php");
 include("modules/class.phpmailer.php");

//$query5 = dbRead("select position, data from tbl_admin_data, tbl_admin_pages where (tbl_admin_data.pageid = tbl_admin_pages.pageid) and langcode='".$_SESSION['User']['lang_code']."' and page = 'directory_pre_send' order by position");

//while($row = mysql_fetch_array($query5)) {

// $PageData[$row[position]] = $row[data];

//}

//function get_page_data($id)  {
//  global $PageData;
//  return $PageData[$id];
//}

 $now = gmdate("D, d M Y H:i:s") ." GMT";

 add_kpi("54", "0");

 /**
  * Main Working Section.
  */

  $BufferNormal = directory(0,$_SESSION['Directory']['fifty'],$_SESSION['Directory']['gold']);

 if($_REQUEST['SendInfo'] == "Normal") {

  send_to_browser($BufferNormal,"application/pdf","Directory.pdf","attachment");

 } elseif($_REQUEST['SendInfo'] == "Zipped") {

  $ZipFile = new zipfile();
  $ZipFile -> addFile($BufferNormal, "Directory.pdf");
  $BufferZipped = $ZipFile -> file();

  send_to_browser($BufferZipped,"application/x-zip","Directory.zip","attachment");

 } elseif($_REQUEST['SendInfo'] == "Email") {

     unset($attachArray);
     unset($addressArray);
     unset($addressBCCArray);

    $text = get_html_template($_SESSION['User']['CID'], 'Member', $_REQUEST['message']);
    $subject = get_word("194");

    $newthis = new stdClass();
    $newthis->Mail = new PHPMailer();

    $newthis->Mail->Priority = 3;
    $newthis->Mail->CharSet = "utf-8";
    $newthis->Mail->From = $_SESSION['User']['EmailAddress'];
    $newthis->Mail->FromName = getWho($_SESSION[Country][logo], 1)." - ".$_SESSION['User']['Name'];
    $newthis->Mail->Sender = $_SESSION['User']['EmailAddress'];
    $newthis->Mail->Subject = $subject;
    $newthis->Mail->AddReplyTo($_SESSION['User']['EmailAddress'], $_SESSION['User']['Name']);
    $newthis->Mail->IsSendmail(true);
    $newthis->Mail->Body = $text;
    $newthis->Mail->IsHTML(true);

    if($_REQUEST[EmailUncompressed]) {
     $newthis->Mail->AddStringAttachment($BufferNormal, "Directory.pdf", "base64","application/pdf");
   	 $attachArray[] = array($BufferNormal, 'Directory.pdf', 'base64', 'application/pdf');
	} else {
     $ZipFile = new zipfile();
     $ZipFile -> addFile($BufferNormal, "Directory.pdf");
     $BufferZipped = $ZipFile -> file();
     $newthis->Mail->AddStringAttachment($BufferZipped, "Directory.zip", "base64", "application/x-zip");
   	 $attachArray[] = array($BufferZipped , 'Directory.zip', 'base64', 'application/x-zip');
	}

	if(strstr($_REQUEST['EmailAddress'], ";")) {
		$emailArray = explode(";", $_REQUEST['EmailAddress']);
		foreach($emailArray as $key => $value) {
    		$newthis->Mail->AddAddress(trim($value), getWho($_SESSION[Country][logo], 1)." Member");
			$addressArray[] = array(trim($value), getWho($_SESSION[Country][logo], 1)." Member");
		}
	} else {
    	$newthis->Mail->AddAddress($_REQUEST['EmailAddress'], getWho($_SESSION[Country][logo], 1)." Member");
		$addressArray[] = array(trim($_REQUEST['EmailAddress']), getWho($_SESSION[Country][logo], 1)." Member");
	}

	if($_REQUEST['bcc']) {
	  $addressBCCArray[] = array(trim($_SESSION['User']['EmailAddress']), getWho($_SESSION[Country][logo], 1)." Member");
	}

    //$this->Mail->AddAddress($_REQUEST['EmailAddress'], "E Banc Trade Member");

    //$this->Mail->Send();
	sendEmail($_SESSION['User']['EmailAddress'], getWho($_SESSION[Country][logo], 1)." - ".$_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], $subject, $_SESSION['User']['EmailAddress'], getWho($_SESSION[Country][logo], 1)." - ".$_SESSION['User']['Name'], $text, $addressArray, $attachArray, $addressBCCArray);

  /**
   * Reload Back to the Directory Page.
   */

  header("Location: /body.php?page=dir");

 }
}
?>
