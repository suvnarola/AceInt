<?

include("global.php");
include("modules/class.phpmailer.php");

if(!checkmodule("HQSend")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>
<?

if($_REQUEST['Type'] == "all") {
  $type="1";
} else {
  $type="2";
} 
 
//echo $type;
 
if($_REQUEST['Type'])  {

	$Counter = 0;

    $text = get_html_template($_SESSION['User']['CID'],$_REQUEST['send_dear'],$_REQUEST['send_text'],true);

    $this->Mail = new PHPMailer();

    $this->Mail->Priority = 3;
    $this->Mail->CharSet = "iso-8859-1";
    $this->Mail->From = $_SESSION['User']['EmailAddress'];
    $this->Mail->FromName = "E Banc Trade - Web Site";
    $this->Mail->Sender = $_SESSION['User']['EmailAddress'];
    $this->Mail->Subject = $_REQUEST['send_subject'];
    $this->Mail->AddReplyTo($_SESSION['User']['EmailAddress'], $_SESSION['User']['Name']);
	$this->Mail->IsSendmail(true);
    $this->Mail->Body = $text;
    $this->Mail->IsHTML(true);
       
    $this->Mail->AddAddress("hq@ebanctrade.com", "E Banc Trade Member");


  if($_REQUEST['send_cc']) {
  
   $Sendcc = explode(";", $_REQUEST['send_cc']);
  
   foreach($Sendcc as $key => $value) {
    $buffer_cc[] = $value;
   }
   
   foreach($buffer_cc as $key => $value) {
    $this->Mail->AddCC($value, "Antony");
   }        
  
  }  
    
  $buffer = taxinvoice($type);
  
  if($_REQUEST['send_bcc']) {
  
   $SendBcc = explode(";", $_REQUEST['send_bcc']);
  
   foreach($SendBcc as $key => $value) {
  
    $buffer[] = $value;
  
   }  
  
  }
  
  foreach($buffer as $key => $value) {
    $Counter++;
    $this->Mail->AddBCC($value, "E Banc Trade - Member");
    if($Counter % 500 == 0) {
     if(!$this->Mail->Send()) {
       
       print "there was an error sending the 500 loop: ". $Counter;
     
     }
     $this->Mail->ClearBCCs();
     $this->Mail->ClearCCs();
    }
  }
    
     if(!$this->Mail->Send()) {
       
       print "there was an error sending the last one:". $Counter;
     
     }

  dbWrite("insert into tbl_log (NumSent,Size,Date) values ('".count($buffer)."','".strlen($text)."',now())","rdihost");

  echo "Your Email has been sent.";

} else  {
?>

<html>
<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'http://admin.ebanctrade.com/body.php?page=emailsellect&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

function ConfirmAdd() {
	bDelete = confirm("Are you sure you wish to send this email?");
	if (bDelete) {
		document.am.submit();
	} else {
	    return false;
	}
}
</script>
</head>
<body>

<?

if($_REQUEST[countryid]) {
 $GET_CID = $_REQUEST[countryid];
} else {
 $GET_CID = $_SESSION['User']['CID'];
}

?>

<form method="POST" action="body.php?page=emailsellect_send" name="am">
<input type="hidden" name="countryid" value="<?= $GET_CID?>">
<table border="0" cellpadding="1" cellspacing="1" width="639">
 <tr>
  <td height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
  <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
	
		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}
		
?>
   </select>&nbsp;</td>
  </tr> 
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2">Email List</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
        <p>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600">
        <br>
        Subject:<br>
        <input type="text" name="send_subject" size="40"><br>
        Dear:<br>
        <input type="text" name="send_dear" size="40"><br>
        CC:<br>
        <input type="text" name="send_cc" size="40"><br>
        BCC:<br>
        <input type="text" name="send_bcc" size="40"><br>
        Text for Email:<br>
        <textarea rows="12" name="send_text" cols="70"></textarea><p>Please select the area's you want a email list for.<br><br>
          <b><?= get_word("78") ?>:</b><br>
          <select size="10" name="disarea[]" multiple>
          <?
           $query2 = dbRead("select disarea from area where CID='$GET_CID' group by disarea order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[disarea] ?>"><?= $row2[disarea] ?></option>
            <?
           }
          ?>
          </select><select size="10" name="area[]" multiple>
          <?
           $query2 = dbRead("select place,FieldID from area where CID='$GET_CID' group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select><br><br>Real Estate Only
		  <input type="checkbox" name="re" value="1"><br>       
          <br>
          <select name="Type">
           <option value="all">All</option>
           <option value="sponsor">Sponsor</option>
          </select>
          <br><br>
          <input type="Button" name="all"  value="Send Email" onclick="ConfirmAdd();">
	 </td>
	</tr>
</table>
</td>
</tr>
</table>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

</form>

</body>
</html>
<?

}

function taxinvoice($type) {

 if($_REQUEST[re]) {
  $op = " AND members.reopt = 'Y'";
 } else {
  $op = " AND members.opt = 'Y'";
 }

 if($type == "1") {

 if($_REQUEST[disarea]) {

  $area_array = $_REQUEST[disarea];
  foreach($area_array as $key => $value) {
   $query = dbRead("select members.*, status.*, area.* from members, status, area where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and area.disarea='$value' and status.Type = 'Normal' order by area");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
    $blah[] = addslashes($row[emailaddress]);
   }
  }

  return $blah;

 } elseif($_REQUEST[area]) {

   $count=0;
   foreach($_REQUEST[area] as $cat_val) {
    if($count == 0) {
     $andor="";
    } else {
     $andor=",";
    }
    
    $cat_array.="".$andor."".$cat_val."";
    
    $count++;
   }

   $query = dbRead("select members.*, status.*, area.* from members, status, area where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and (area.FieldID IN($cat_array)) and status.Type = 'Normal' order by area");
   #loop around
   while($row = mysql_fetch_assoc($query)) {
    $blah[] = addslashes($row[emailaddress]);
   }
    
    
  return $blah;
 }

} elseif($type == "2") {

 $area_array = $_REQUEST[disarea];
 foreach($area_array as $key => $value) {
  $query = dbRead("select members.*, status.*, area.* from members, status, area where (members.area = area.FieldID) and (members.status = status.FieldID) and members.emailaddress != ''$op and area.disarea='$value' and status.Name = 'Sponsorship'  order by area");
  #loop around
  while($row = mysql_fetch_assoc($query)) {
   $blah[] = addslashes($row[emailaddress]);
  }
 }

return $blah;

}

}

?>