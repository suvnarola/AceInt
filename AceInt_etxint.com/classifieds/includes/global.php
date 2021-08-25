<?

$CONFIG['db_name'] = "etradebanc";
$CONFIG['db_host'] = "localhost";
$CONFIG['db_user'] = "ebanc";
$CONFIG['db_pass'] = "9yhxv626";
$CONFIG['db_linkid'] = mysql_pconnect($CONFIG['db_host'], $CONFIG['db_user'], $CONFIG['db_pass']);

$CONFIG[DEBUG] = true;

$CONFIG[Username] = $_SERVER[REMOTE_USER];

include("auction_functions.php");

////////////////////////////// GLOBAL FUNCTIONS //////////////////////////////

function dbRead($SQLQuery,$database = false) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error()); }
	
	$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);
	if ($rsid == False) { dbReportError(mysql_errno(),mysql_error()); }

	return($rsid);
}


function dbWrite($SQLQuery,$database = false,$ReturnID = False) {
	global $CONFIG;
	if($database == false) { $database = $CONFIG['db_name']; }

	if ($CONFIG['db_linkid'] == False) { dbReportError(mysql_errno(),mysql_error()); }

	$rsid = mysql_db_query($database, $SQLQuery, $CONFIG['db_linkid']);
	if ($rsid == False) { dbReportError(mysql_errno(),mysql_error()); }
	if ($ReturnID == True) {
		$ReturnID = mysql_insert_id($CONFIG['db_linkid']);
	} else {
		$ReturnID = True;
	}

	return($ReturnID);
}


function dbReportError($ErrorNumber,$ErrorMsg) {

	print "An error occured while connecting to the database<br>";
	print "<strong>$ErrorNumber</strong>";
	print $ErrorMsg;
	exit;
}

function getmicrotime() { 
   	list($msec, $sec) = explode(" ",microtime()); 
   	return ((float)$sec + (float)$msec); 
} 

function display_info() {

?>
<!-- -----------------------------------------------------------------
Designed By = RDI Host Pty Ltd
            = 1 Explorer Street, Sippy Downs, QLD 4556, AUSTRALIA
            = T: 0412 8000 44
            = F: 07 5437 7230
            = E: info@rdihost.com
            = U: http://www.rdihost.com
DomainName  = <?= $_SERVER[SERVER_NAME] ?> 
ScriptName  = <?= $_SERVER[SCRIPT_NAME] ?> 
IPAddress   = <? if($_SERVER[HTTP_X_FORWARDED_FOR]) { echo $_SERVER[HTTP_X_FORWARDED_FOR]; } else { echo $_SERVER["REMOTE_ADDR"]; } ?> 
UserAgent   = <?= $_SERVER[HTTP_USER_AGENT] ?> 
----------------------------------------------------------------- --->
<?

}

function tabs($tabarray) {

 $count = sizeof($tabarray);

 if($_GET[tab]) {
 
  ?>
    <table border="0" cellpadding="0" cellspacing="0" width="640">
    <tr>
      <td><img border="0" src="images/layout_arrow_right.gif" width="6" height="11">&nbsp;</td>
      <td width="100%">
      <?
      
       $foo = 1;
       
       foreach($tabarray as $tabkey => $tabvalue) {
        
        if($tabvalue == $_GET[tab]) {
         ?>
         &nbsp;<b><?= $tabvalue ?></b><? if($count != $foo) { ?> |<? } ?>
         <?
        } else {
         ?>
         &nbsp;<a class="nav" href="main.php?page=<?= $_GET[page]?>&Client=<?= $_GET[Client] ?>&tab=<?= $tabvalue ?>"><?= $tabvalue ?></a><? if($count != $foo) { ?> |<? } ?>     
         <?
        }
	    
	    $foo++;
	    
	   }
   	  ?>
      </td>
    </tr>
    <tr>
      <td width="100%" colspan="2"><img border="0" src="images/layout_line.gif" width="100%" height="13"><br>&nbsp;</td>
    </tr>
  </table>
  <?
 
 }

}

function get_time_remain($row) {

	$diff = $row[unix_ends]-mktime(); 
	$years = ($diff - ($diff % 31536000)) / 31536000;
	$diff = $diff - ($years * 31536000); 
	$days = ($diff - ($diff % 86400)) / 86400; 
	$diff = $diff - ($days * 86400); 
	$hours = ($diff - ($diff % 3600)) / 3600; 
	$diff = $diff - ($hours * 3600); 
	$minutes = ($diff - ($diff % 60)) / 60; 
	if($years != 0) { $AgeDate .= $years."y "; }
	if($days > 1) { $AgeDate .= $days." days "; } elseif($days == 1) { $AgeDate .= $days." day "; }
	if($hours != 0) { $AgeDate .= $hours."h "; }
	if($minutes != 0 ) { $AgeDate .= $minutes."m"; }
	
	return $AgeDate;

}

function get_days($diff) {

	$years = ($diff - ($diff % 31536000)) / 31536000;
	$diff = $diff - ($years * 31536000); 
	$days = ($diff - ($diff % 86400)) / 86400; 
	$diff = $diff - ($days * 86400); 
	$hours = ($diff - ($diff % 3600)) / 3600; 
	$diff = $diff - ($hours * 3600); 
	$minutes = ($diff - ($diff % 60)) / 60; 
	if($days > 1) { $AgeDate .= $days." days "; } elseif($days == 1) { $AgeDate .= $days." day "; } elseif($days < 1) { $AgeDate .= "0 Days "; }
	
	return $AgeDate;

}

function getDirList($dirName) { 
	
	$d = dir($dirName); 
	
	while($entry = $d->read()) {
		if ($entry != "." && $entry != "..") { 
			if (is_dir($dirName."/".$entry)) { 
				getDirList($dirName."/".$entry); 
			} else { 
				$FilesArray[] = $entry; 
			} 
		} 
	} 
	
	$d->close();
	return $FilesArray;
}

function form_select($name,$query,$value,$key,$compare = false,$allowall = false,$custom = false,$size = 1) {
 
 $sql_query = $query;
 
 if($allowall) {
  
  $output .= "<option value=\"\">$allowall</option>\n";
  
 }
  
 while($row = mysql_fetch_assoc($sql_query)) {
  
  if(strtolower($row[$key]) == strtolower($compare)) {
  
   $output .= "<option selected value=\"$row[$key]\">$row[$value]</option>\n";
  
  } else {
  
   $output .= "<option value=\"$row[$key]\">$row[$value]</option>\n";
  
  }
  
 }
 
 print "<select size=\"$size\" name=\"$name\"$custom>\n$output</select>";

}

function form_addslashes() {

 foreach($_REQUEST as $key => $value) {
  
  $TV[$key] = addslashes($value);

 }
 
 return $TV;

}

?>