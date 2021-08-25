<?

include("letterscashfees.php");
include("debtcollect.php");
include("class.html.mime.mail.inc");

if(!checkmodule("Letters")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81")?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>

<form method="POST" action="body.php?page=letters&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_REQUEST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array(get_page_data("1"),get_page_data("2"),get_page_data("3"),get_page_data("4"),get_page_data("5"), "Admin", "Direct Debit");

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

 update('9');
 letters('9');

} elseif($_GET[tab] == "tab2") {

 update('1');
 letters('1');

} elseif($_GET[tab] == "tab3") {

 update('2');
 letters('2');

} elseif($_GET[tab] == "tab4") {

 update('3');
 letters('3');

} elseif($_GET[tab] == "tab5") {

 update('4');
 letters('4');

} elseif($_GET[tab] == "tab6") {

 update('6');
 letters('6');

} elseif($_GET[tab] == "tab7") {

 update('7');
 letters('7');

}

?>

</form>


<?

function letters($letterno) {

 global $time_start;

   //if($letterno == '3' || $letterno == '4') {
     $colspan = "11";
   //} else {
     //$colspan = "4";
   //}

?>
 <table border="0" cellspacing="0" width="620" cellpadding="1">
    <?if ($letterno == 1) {?><tr>
    <td width="100%" colspan="8" align="center" bgcolor="#FFFFFF"></td>
  </tr><?}?>
 </table>
 <table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading"><?= get_page_data("6") ?> <?= $letterno ?></td>
   </tr>
   <tr>
     <td class="Heading2"><b><?= get_word("1")?>:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="add" size="35"></td>
   </tr>
   <tr>
     <td class="Heading2">&nbsp;</td>
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="AddMembers" value="Add Members"></td>
   </tr>
  </table>
 </td>
 </tr>
 </table>
 <br><br>
 <table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Letter <?= $letterno ?> List</td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1")?>:</b></td>
     <td class="Heading2" nowrap><b><?= get_word("3")?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
     <?//if($letterno == '3' || $letterno == '4')  {?>
       <td class="Heading2" nowrap align = "right"><b>D/Fees:</b></td>
       <td class="Heading2" nowrap align = "right"><b>L/Traded:</b></td>
       <td class="Heading2" nowrap align = "right"><b>Balance:</b></td>
       <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
       <td class="Heading2" nowrap align = "right"><b>R/Facility:</b></td>
       <td class="Heading2" nowrap align = "right"><b>Net:</b></td>
       <td class="Heading2" nowrap align = "right"><b>Claimable:</b></td>
     <?//}?>
     <td align="right" class="Heading2"><b>DEL:</b></td>
   </tr>
<?

   $foo = 0;

   if($letterno == '7')  {
    $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status != 1 and letters = 7 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
    //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status != 1 and paymenttype > 0 and accountno > 0 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
   } else {
    $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and letters = '$letterno' and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
   }
     //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, area.* from members, transactions, area where members.licensee = area.FieldID and (members.memid = transactions.memid) and letters = '$letterno' and members.CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by place, members.memid ASC");


   //} else {
     //$query = dbRead("select members.*, feesowing.numfeesowing from members left outer join feesowing on (members.memid = feesowing.memid) where letters = '$letterno' and CID = '".$_SESSION['User']['CID']."' order by memid ASC");
     //$query = dbRead("select members.* from members where letters = '$letterno' and CID = '".$_SESSION['User']['CID']."' order by memid ASC");
   //}

   if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

   } else {
    $counter = 0;
    while($row = mysql_fetch_array($query)) {

    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";

    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

    $bal = ($row[sell]-$row[buy]);
    $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
    if($net < 0)  {
      $claim = (abs($net)+$row[dollarfees]);
    } else {
      $claim = $row[dollarfees];
    }

    $ctotal+=$claim;
    $dtotal+=$row[dollarfees];

   //if($letterno == '3' || $letterno == '4')  {
     //$query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and (to_memid != '".$_SESSION['Country']['reserveacc']."' or to_memid != '".$_SESSION['Country']['facacc']."' or to_memid != '".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."','".$_SESSION['Country']['adminacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);
   //}
    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td width = "150" nowrap><?= get_all_added_characters($row[companyname]) ?> <font size = '2' color = #FF0000><b><?if($row['rob'] == 1) { print"(_??_)"; }?></font></td>
      <td  nowrap align = "right"><?= number_format($row[fee_deductions],2) ?></td>
        <?//if($letterno == '3' || $letterno == '4') {?>
        <td  nowrap align = "right"><?= number_format($row[dollarfees]) ?></td>
        <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
        <td  nowrap align = "right"><?= number_format($bal) ?></td>
        <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
        <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
        <td  nowrap align = "right"><?= number_format($net) ?></td>
        <td  nowrap align = "right"><?= number_format($claim) ?></td>
      <?//}?>
      <td align="right"><input type="checkbox" name="del[]" value="<?= $row[memid] ?>"></td>
    </tr>
    <?

 	$foo++;
 	$counter++;
    }

   }

?>

	  <?//if($letterno == '3' || $letterno == '4') {?>
       <tr bgcolor="#FFFFFF">
        <td width = "80"></td>
        <td  nowrap><?= get_page_data("7") ?>: <?= $counter ?></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
        <td align="right"></td>
       </tr>
      <?//}?>
      <tr>
       <td colspan="<?= $colspan ?>" bgcolor="#FFFFFF">
       <table border="0" cellspacing="0" width="100%" cellpadding="0">
        <tr>
         <td bgcolor="#FFFFFF" align="right"><?if($letterno != 3) {?>No Email: <input type="checkbox" name="noem" value="1"> --- <?}?> <?if($letterno == 3) {?>Print Final Letter: <input type="checkbox" name="final" value="1"> --- <?}?> Include Letter Head: <input type="checkbox" name="header" value="1">  <input type="submit" name="move" value="Move to Next"></td>
         <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
        </tr>
       </table>
       </td>
     </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
     </tr>
    </table>
   </td>
  </tr>
 </table>

<?

}

function update($letterno) {

 if($_REQUEST[AddMembers]) {

  $MemberArray_temp = $_REQUEST[add];
  $MemberArray_temp = trim($MemberArray_temp);

  if(substr_count($MemberArray_temp, ",") > 0) {

   $MemberArray = explode(",", $MemberArray_temp);

  } else {

   $MemberArray[] = $MemberArray_temp;

  }

  foreach($MemberArray as $key => $value) {

   if($letterno == 3 || $letterno == 4) {

    dbWrite("update members set status = '6' where memid = '$value' and status = '0'");

   } elseif($letterno == 2) {

    dbWrite("update members set status = '5' where memid = '$value' and status = '0'");

   }

   dbWrite("update members set letters = '$letterno' where memid = '$value'");

  }

 } elseif($_REQUEST[B1]) {

  $DelArray = $_REQUEST[del];

  foreach($DelArray as $key => $value) {

   if($letterno == 2 || $letterno == 3 || $letterno == 4)  {

    dbWrite("update members set status = '0' where memid='$value' and status='5'");
    dbWrite("update members set status = '0' where memid='$value' and status='6'");

   }

   dbWrite("update members set letters = '0' where memid='$value'");

  }

 } elseif($_REQUEST[move]) {

  $MemberArray = $_REQUEST[del];
  $letterno2=$letterno+1;


   if($letterno == 9) {

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";
     $buffer = feeletters($MemberArray,'1',$_REQUEST[header]);
     unset($attachArray);
     unset($addressArray);
   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Letters - 1', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 //$currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	   //$det = "Overdue Fee Letter 1 Sent";
	 //$det = "Overdue Fee Letter 1 Email";
     //dbWrite("update members set letters = '1' where memid = '$value'");
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");


     $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.memid = '".$value."' order by companyname");
     $row2 = mysql_fetch_array($query2);

	 if($row2['emailaddress'] || $_REQUEST['noem']) {

		 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
		 //$det = "Overdue Fee Letter 1 Sent";
		 if($_REQUEST['noem']) {
		  $det = "Overdue Fee Letter 1 Sent";
		 } else {
		  $det = "Overdue Fee Letter 1 Email";
		 }

	     dbWrite("update members set letters = '1' where memid = '$value'");
	     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");

	 }

     //$text = "<b>Our records indicate that the following fee amount is now overdue:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>As the outstanding balance is now more than <b>30 days overdue</b>. Would you please settle this account within 14 days.<br><br>If you have  recently  paid  this amount or put a Direct Debit in place,  thank you and please disregard the remainder of this message.  If not, details of how to make payment are included below.<br><br>As a convenience to our members, fees & charges can be paid online by credit card.  Simply log into the member's section of the Empire Trade website, then select the Fee Payment option on the left of the screen.  This is a secure site. Alternatively you can bank transfer into our Suncorp bank account BSB: 484 799 Account No: 003598705, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>I have included a direct debit form.  Are you aware that if you have a direct debit authority in place, your monthly Administration Fee will reduced?<br><br>Direct debits are processed on the 21st of each month from your nominated bank account or credit card and fees are not debited until they exceed $5.  So why not take advantage of this reduced monthly fee and do away with monthly paperwork and manual payments.<br><br><br>Regards<br><br>Diane Phelps";
     $text = "<b>We wish to inform you that your Empire Trade Account is overdue by 30 days.<br><br>Your outstanding balance is ". feesowing($value)." and will need to be paid immediately.</b><br><br>As a convenience to our members, fees & charges can be paid online by credit card.  Simply log into the Member's section of the Empire Trade website, and then select the Fee Payment option on the left of the screen.  This is a secure site.  Alternatively you can bank transfer into our Suncorp Bank Account - BSB: 484 799 Account No: 003598705, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>Please note that late fees of AUD $19 per month, are payable on all accounts whilst in arrears.<br><br>Why not save more money and convenience in future, and complete the direct debit form enclosed!<br><br>Your business is important to us, so if you are suffering financial hardship and are unable to pay the full amount, contact our Accounts Department today, and we may be able to assist you and offer a payment plan as a short term option, to bring your account up to date.<br><br>Please accept our thanks and disregard this letter should you of paid this account already, or recently placed a direct debit in place with our office.<br><br><br>Regards<br><br>Diane Phelps";
     $text = get_html_template($row2['CID'],$row2['accholder'],$text);
     unset($attachArray);
     unset($addressArray);

	 $SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/pdf/D035-au.pdf");
	 $attachArray[] = array($SBuffer, 'D035.pdf', 'base64', 'application/pdf');

	 if(strstr($row2['emailaddress'], ";")) {
		$emailArray = explode(";", $row2['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row2['accholder']);
		}
	 } else {
		$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
	 }

	 //$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
	 sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Overdue Fees & Charges - 30 Days', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    }

   } elseif($letterno == 1) {


	$query1 = dbRead("select * from country, area where (area.CID = country.countryID) and inter = 'Y' and countryID = ".$_SESSION['Country']['countryID']."");
	while($row1 = mysql_fetch_assoc($query1)) {

	 if($row1[state] == 'QLD')  {
	    $otheremail = ",corrie.p@au.empirexchange.com";
	 } else {
	    $otheremail = "";
	 }

	 //if(!$row1[email]) {
	 if(!$row1[reportemail]) {
	  $emailaddress =  "dave@ebanctrade.com";

	 } else {
	  //if($row1['display'] == 'Y')  {
	   //$emailaddress = $row1[email];
	   $emailaddress = $row1[reportemail];
	  //} else {
	   //$emailaddress =  "dave@ebanctrade.com";
	  //}
	 }

	 if($_SESSION['User']['CID'] != 12 && $rr) {
	 // define the text.
	  $text = "Dear $row1[tradeq],\r\n\r\nAttached is a list of members in your area who have over $row1[currency]$row1[letteramount] owing in fees and are now over 45 days overdue.\r\n\r\nThey have received two reminder letter and will be terminated in 15 days if no contact is made.\r\n\r\n\r\nIf you do not wish any of these members to be terminted must contact Accounts at your Head Office before this date.\r\n\r\nRegards\r\n\r\nAccount Department.";

	 // get the actual taxinvoice ready.
	  $buffer = letter($row1[FieldID]);
	      unset($attachArray);
	     unset($addressArray);
	   	$attachArray[] = array($buffer, 'Overdue - '.$row1[place].'.txt', 'base64', 'text/plain');
		$addressArray[] = array(trim($emailaddress), $row1[tradeq]);

		sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Over Due Members - '.$row1[place], 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);


$query1 = dbRead("select user, EmailAddress, Name from members, area, tbl_admin_users where members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and letters = 1 group by user");
while($row1 = mysql_fetch_assoc($query1)) {

 if($row1['EmailAddress']) {

 // define the text.
  $text = "Dear $row1[tradeq],\r\n\r\nAttached is a list of members in your area who have over $row1[currency]$row1[letteramount] owing in fees and are now over 45 days overdue.\r\n\r\nThey have received two reminder letter and will be terminated in 15 days if no contact is made.\r\n\r\n\r\nIf you do not wish any of these members to be terminted must contact Accounts at your Head Office before this date.\r\n\r\nRegards\r\n\r\nAccount Department.";
 // get the actual taxinvoice ready.
  $buffer = letter2($row1[user],$letterno);
 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);
 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
 // add the text in.
  $mail->add_text($text);
 // add the attachment on.
  $mail->add_attachment($buffer, 'Overdue - '.$row1[place].'.txt', 'text/plain');
 // build the message.
  $mail->build_message();
 // send the message.
  $mail->send($row1['Name'], $row1['EmailAddress'], 'Empire Accounts - Head Office', 'accounts@ebanctrade.com', '45 Days Over Due Members','Bcc: reports@ebanctrade.com');

 }
}

	 }
	}

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters($MemberArray,'2',$_REQUEST[header]);
     unset($attachArray);
     unset($addressArray);
   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Letters - '.$letterno2, 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 //$currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 //$det = "Overdue Fee Letter 2 Email";
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','Licensee Emailed with reference to overdue fees')");
     //dbWrite("update members set letters = '2' where memid = '$value'");
     //dbWrite("update members set status = '5' where memid = '$value'");

     $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.memid = '".$value."' order by companyname");
     $row2 = mysql_fetch_array($query2);

	if($row2['emailaddress'] || $_REQUEST['noem']){
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 if($_REQUEST['noem']) {
	  $det = "Overdue Fee Letter 2 Sent";
	 } else {
	  $det = "Overdue Fee Letter 2 Email";
	 }
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','Licensee Emailed with reference to overdue fees')");
     dbWrite("update members set letters = '2' where memid = '$value'");
     dbWrite("update members set status = '5' where memid = '$value'");
	}

     //$text = "<b>Our records indicate that the following amount is still outstanding:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>We recently wrote to you to request payment of your outstanding balance of ". feesowing($value).", which is now overdue 45 days or more.<br><br>As this amount is still outstanding and we have had no communication from you, we regret to advise that pursuant to our Trading Rules, your membership account has now been placed into 'Suspended' status and no further purchases are permitted from this account.<br><br>Unless your payment is received within 14 days of this letter your membership account may be terminated. Any outstanding facility will become payable in Australian Dollars under the terms and conditions of the Facility Agreement.<br><br>Please ignore this notice if you have paid the overdue amount in the last few days.<br><br>Should you pay the outstanding balance within this 14 day period, your account will remain operational.  You can pay online using a credit card by logging on to the member's section of the Empire Trade website, and selecting the 'Fee Payment' option on the left of the screen.  This is a secure site.  Alternatively you can bank transfer into our Suncorp Bank Account - BSB: 484 799, Account No: 003598705 using your Empire Trade Account Number as the Reference, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>Should you be experiencing difficulty in paying this account, please contact our Accounts Department to discuss payment options.<br><br><br>Regards<br><br>Diane Phelps";
	 $text = "<b>We wish to inform you that your Empire Trade Account is over due by 45 days.</b><br><br>Your outstanding balance is ". feesowing($value) ." and will need to be paid immediately. <br><br>As this amount is still outstanding and we have had no communication from you, we regret to advise that pursuant to our Trading Rules, your membership account has now been placed into restricted status and no further purchases are permitted from this account.<br><br>If you have overlooked this account, we urge you to make payment immediately to avoid further fees and charges being accrued.<br><br>As a convenience to our members, fees & charges can be paid online by credit card.  Simply log into the member's section of the Empire Trade website, then select the Fee Payment option on the left of the screen.  This is a secure site. Alternatively you can bank transfer into our Suncorp Bank account - BSB: 484 799 Account No: 003598705, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>Should you wish to pay this account over the phone quickly and conveniently, you can do so by calling us on 1800 Empire.<br><br>Please note that late fees of AUD $19 per month, are payable on all accounts whilst in arrears.<br><br>Why not save more money and convenience in future, and complete the direct debit form enclosed! <br><br>Your business is important to us, so if you are suffering financial hardship and are unable to pay the full amount, contact our Accounts Department today and we may be able to assist you by offering a payment plan as a short term option to bring your account up to date.<br><br>Please accept our thanks and disregard this letter should you of paid this account already, or recently submitted a direct debit with our office.<br><br>Regards<br><br>Diane Phelps";
     $text = get_html_template($row2['CID'],$row2['accholder'],$text);

     unset($attachArray);
     unset($addressArray);

	 if(strstr($row2['emailaddress'], ";")) {
		$emailArray = explode(";", $row2['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row2['accholder']);
		}
	 } else {
		$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
	 }

	 sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Overdue Fees & Charges - 45 Days', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    }


   } elseif ($letterno == 2) {

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters($MemberArray,'3',$_REQUEST[header]);
   //$buffer = feeletters($MemberArray,'41',$_REQUEST[header]);

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Letters - '.$letterno2, 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    if(substr_count($MemberArray_temp, ",") > 0) {

     $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }


$query1 = dbRead("select user, EmailAddress, Name from members, area, tbl_admin_users where members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and letters = '$letterno' group by user");
while($row1 = mysql_fetch_assoc($query1)) {

 if($row1['EmailAddress']) {

 // define the text.
  $text = "Dear $row1[tradeq],\r\n\r\nAttached is a list of members in your area who have over $row1[currency]$row1[letteramount] owing in fees and are now over 60 days overdue.\r\n\r\nThey have received three reminder letters and will be sent in 7 days to the debt collector.\r\n\r\n\r\n\r\n\r\nRegards\r\n\r\nAccount Department.";
 // get the actual taxinvoice ready.
  $buffer = letter2($row1[user],$letterno);
 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);
 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
 // add the text in.
  $mail->add_text($text);
 // add the attachment on.
  $mail->add_attachment($buffer, 'Overdue - '.$row1[place].'.txt', 'text/plain');
 // build the message.
  $mail->build_message();
 // send the message.
  $mail->send($row1['Name'], $row1['EmailAddress'], 'Empire Accounts - Head Office', 'accounts@ebanctrade.com', '60 Days Over Due Members','Bcc: reports@ebanctrade.com');

 }
}

    foreach($MemberArray as $key => $value) {

     $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.memid = '".$value."' order by companyname");
     $row2 = mysql_fetch_array($query2);

     if($row2['emailaddress'] || $_REQUEST['noem']) {
     dbWrite("update members set letters = '3' where memid = '$value'");
     dbWrite("update members set status = '5' where memid = '$value' and status = '0'");
     //dbWrite("update members set status = '6' where memid = '$value'");
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 //$det = "Overdue Fee Letter 3 Sent";
	 if($_REQUEST['noem']) {
	  $det = "Overdue Fee Letter 3 Sent";
	 } else {
	  $det = "Overdue Fee Letter 3 Email";
	 }
	 //$det = "Overdue Fee Letter Final Notice Email/Sent";
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
	 }

     //$text = "<b>Our records indicate that the following amount is still outstanding:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>We recently wrote to you to request payment of your outstanding balance of ". feesowing($_REQUEST['Client']).", which is now overdue 45 days or more.<br><br>As this amount is still outstanding and we have had no communication from you, we regret to advise that pursuant to our Trading Rules, your membership account has now been placed into 'Suspended' status and no further purchases are permitted from this account.<br><br>Unless your payment is received within 14 days of this letter your membership account may be terminated. Any outstanding facility will become payable in Australian Dollars under the terms and conditions of the Facility Agreement.<br><br>Please ignore this notice if you have paid the overdue amount in the last few days.<br><br>Should you pay the outstanding balance within this 14 day period, your account will remain operational.  You can pay online using a credit card by logging on to the member's section of the Empire Trade website, and selecting the 'Fee Payment' option on the left of the screen.  This is a secure site.  Alternatively you can bank transfer into our Suncorp Bank Account - BSB: 484 799, Account No: 003598705 using your Empire Trade Account Number as the Reference, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>Should you be experiencing difficulty in paying this account, please contact our Accounts Department to discuss payment options.<br><br><br>Regards<br><br>Diane Phelps";
  //$text = "<b>Our records indicate that the following amount is still outstanding:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>We refer to our previous correspondence that was emailed to you, and advise that due to unpaid fees and charges, your membership account may be terminated within 10 days.<br><br>Upon termination of an account, the debt will be referred to a Debt Collection Agency for recovery which may involve legal action, incurring additional costs to you.  Any outstanding facility is also recoverable in Australian Dollars.  Any outstanding trade credit balance will be transferred to a trust account, pending resolution of this issue.  We may also provide details of your payment default to a credit reporting agency.  Details of your payment default will become part of your credit history file for 5 years and will be available to authorised parties, such as credit providers.  Default listings will impact your credit rating and may affect your ability to obtain future credit.<br><br>Should you pay the current balance outstanding of ". feesowing($value) .", within this 10 day period, your account will remain operational.  You can pay your current outstanding balance by completing the payment section below and attaching a cheque, or by phone with a credit card. Alternatively you can bank transfer to our Suncorp Bank Account - BSB: 484799, Account No: 003598705, or <b>BPAY</b> - Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br><br>Regards<br><br>Diane Phelps";
  //$text = "<b>Our records indicate that the following amount is still outstanding:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>This is our third letter regarding your account, and unpaid fees and charges.  Unfortunately, your membership and account may be terminated within 14 days if the outstanding balance is not paid.<br><br>Upon termination of an account, the debt will be referred for Legal Recovery action, incurring additional costs to you.  Any outstanding facility is recoverable in Australian Dollars.  Any outstanding trade credit balance will be transferred to a trust account, pending resolution of this matter.  Details of your payment default may also become known by credit reporting agencies and form part of your credit history file for the next 5 years and available to authorised credit providers.  Default listings impact your credit rating and may affect your ability to obtain future credit.<br><br>Should you pay the current outstanding balance of ". feesowing($value) .", before ". date("d-m-Y", mktime(1,1,1,date("m"),date("d")+10,date("Y"))) .", your account will remain operational.  You can pay your outstanding balance by forwarding a cheque; or by phone with a credit card. Alternatively you can bank transfer to our Suncorp Bank Account - BSB: 484799, Account No: 003598705, or BPAY - Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>We look forward to hearing from you.<br><br><br><br><br>Regards<br><br>Diane Phelps";
  $text = "<b>We wish to inform you that your Empire Trade account is overdue by 60 days. </b> This is the third attempt we have made contacting you seeking payment for your overdue Empire Trade Account.  Currently your outstanding balance is ". feesowing($value).".<br><br>In order to give our members the best assistance and service we can, we rely on fees being paid in full and on time.  Should you of overlooked this matter, please contact our office on 1800 Empire to correct your account immediately.<br><br>As a convenience to our members, fees & charges can be paid online by credit card.  Simply log into the member's section of the Empire Trade website, then select the Fee Payment option on the left of the screen.  This is a secure site. Alternatively you can bank transfer into our Suncorp Bank account  -BSB: 484 799 Account No: 003598705, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".  <br><br>We again act in good faith and give you the opportunity to bring your account up to date by paying the outstanding balance in full.  Upon payment being cleared all of your account access will be restored and we look forward in doing business with you again.<br><br>Please note that late fees are payable on all accounts of AU$19 per month whilst in arrears .<br><br>Why not save more money and convenience in future, and complete the direct debit form enclosed! <br><br>Your business is important to us, so if you are suffering financial hardship and are unable to pay the full amount, contact our Accounts Department today and we may be able to assist you and offer a payment plan as a short term option to bring your account up to date. This will be done on a Direct Debit basis only shall it be offered by us.<br><br>Should you again ignore this third OVERDUE account notice, the account will be forward to Specialised Collections and / or our Legal Department which will incur further fees and charges to you.  Any outstanding Empire Trade balance will also be recoverable in Australian Dollars. <br><br>Please accept our thanks and disregard this letter should you of paid this account already, or recently placed a direct debit in place with our office.<br><br>Regards<br><br>Diane Phelps";
     //$text = "<b>Our records indicate that the following amount is still outstanding:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>You have received 3 emails over recent weeks regarding your outstanding fees.<br><br>In some instances, the debt may be an accumulation of monthly administration fees, which were introduced by the previous exchange Manager of Empire Trade in 2006, as per the International Rules of the exchange. E Banc Trade became Empire Trade in November 2006, and your trading account transferred to Empire Trade as of that date.<br><br>While it is not our desire to take legal action to recover debts, we find it necessary in some cases to employ a Debt Collector to recover what is owed to us. This is a necessary action to ensure that we have sufficient income to provide services to our members.<br><br>If payment is not received within 7 working days, your account will be referred to our Debt Collection Agency and may incur additional costs. Your account is currently suspended and any outstanding facility will be due and repayable in Australian Dollars.  Payments can be bank transfered to our Suncorp Bank Account - BSB: 484799, Account No: 003598705, or <b>BPAY</b> - Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>Once this goes to the Collection Agency, your credit history may reflect the unpaid debt, which will influence your ability to obtain finance over the next 5 years.<br><br>Please contact our Accounts Department immediately to resolve this matter.<br><br>If you have paid the overdue amount in the last few days, or made arrangements for payment, please ignore this notice.<br><br><br>Regards<br><br>Diane Phelps";
     $text = get_html_template($row2['CID'],$row2['accholder'],$text);

     unset($attachArray);
     unset($addressArray);

	 if(strstr($row2['emailaddress'], ";")) {
		$emailArray = explode(";", $row2['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row2['accholder']);
		}
	 } else {
		$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
	 }

	 sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Overdue Fees & Charges - 60 Days', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);
	 //sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Overdue Fees & Charges - Final Notice', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    }

   } elseif ($letterno == 3) {


	if($_REQUEST['final']) {

	    // define the text.
	    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

	    // get the actual taxinvoice ready.
	    $buffer = feeletters($MemberArray,'41',$_REQUEST[header]);
	    //$buffer2 = feeletters($MemberArray,'41',$_REQUEST[header],'1');
	    //$buffer = feeletters($MemberArray,'48',$_REQUEST[header]);

	    unset($attachArray);
	    unset($addressArray);

	   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	   	//$attachArray[] = array($buffer2, 'lettersNoEmail.pdf', 'base64', 'application/pdf');
		$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

		sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Letters - Final', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

	    if(substr_count($MemberArray_temp, ",") > 0) {

	     $MemberArray = explode(",", $MemberArray_temp);

	    } else {

	     $MemberArray[] = $MemberArray_temp;

	    }

$query1 = dbRead("select user, EmailAddress, Name from members, area, tbl_admin_users where members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and letters = '$letterno' group by user");
while($row1 = mysql_fetch_assoc($query1)) {

 if($row1['EmailAddress']) {

 // define the text.
  $text = "Dear $row1[tradeq],\r\n\r\nAttached is a list of members in your area who have over $row1[currency]$row1[letteramount] owing in fees and are now over 60 days overdue.\r\n\r\nThey have received three reminder letters as well as a final demand and will be sent in 7 days to the debt collector.\r\n\r\n\r\n\r\n\r\nRegards\r\n\r\nAccount Department.";
 // get the actual taxinvoice ready.
  $buffer = letter2($row1[user],$letterno);
 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);
 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
 // add the text in.
  $mail->add_text($text);
 // add the attachment on.
  $mail->add_attachment($buffer, 'Overdue - '.$row1[place].'.txt', 'text/plain');
 // build the message.
  $mail->build_message();
 // send the message.
  $mail->send($row1['Name'], $row1['EmailAddress'], 'Empire Accounts - Head Office', 'accounts@ebanctrade.com', '60 Days Over Due Members','Bcc: reports@ebanctrade.com');

 }
}

    	foreach($MemberArray as $key => $value) {

		 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
   	     $det = "Overdue Fee Letter Final Notice Email/Sent";
		 //$det = "3 Months free Admin Fees Offer Letter Emailed";
	     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
         dbWrite("update members set status = '5' where memid = '$value'");

	     $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.memid = '".$value."' order by companyname");
	     $row2 = mysql_fetch_array($query2);

	     //$text = "<b>Our records indicate that the following amount is still outstanding:<br><br>Amount: ". feesowing($value)."<br><br>Account No: ". $value ."</b><br><br>You have received 3 emails over recent weeks regarding your outstanding fees.<br><br>In some instances, the debt may be an accumulation of monthly administration fees, which were introduced by the previous exchange Manager of Empire Trade in 2006, as per the International Rules of the exchange. E Banc Trade became Empire Trade in November 2006, and your trading account transferred to Empire Trade as of that date.<br><br>While it is not our desire to take legal action to recover debts, we find it necessary in some cases to employ a Debt Collector to recover what is owed to us. This is a necessary action to ensure that we have sufficient income to provide services to our members.<br><br>If payment is not received within 7 working days, your account will be referred to our Debt Collection Agency and may incur additional costs. Your account is currently suspended and any outstanding facility will be due and repayable in Australian Dollars.  Payments can be bank transfered to our Suncorp Bank Account - BSB: 484799, Account No: 003598705, or <b>BPAY</b> - Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>Once this goes to the Collection Agency, your credit history may reflect the unpaid debt, which will influence your ability to obtain finance over the next 5 years.<br><br>Please contact our Accounts Department immediately to resolve this matter.<br><br>If you have paid the overdue amount in the last few days, or made arrangements for payment, please ignore this notice.<br><br><br>Regards<br><br>Diane Phelps";
	     //$text = "<b>This is our final letter regarding outstanding fees of ". feesowing($value) .".<br><br>Account No: ". $value ."</b><br><br>The debt may be accumulating monthly administration fees, introduced by the previous Exchange Manager of Empire Trade in 2006, as per the International Rules of the exchange. E Banc Trade became Empire Trade in November 2006, and your trading account was transferred to us then.<br><br>It is not our desire to take legal recovery action; but we find it necessary in some instances to ensure that monies are correctly paid to us.  It is necessary to ensure that we have sufficient income to meet the requirements of members who pay for our services.  Unfortunately, this means that additional costs and unpleasantness may be visited upon members who do not pay and refuse to enter into satisfactory arrangements with us when immediate payment is legitimately not possible.<br><br>Please ensure that we receive payment of the sum of ". feesowing($value) ." no later than ". date("d-m-Y", mktime(1,1,1,date("m"),date("d")+7,date("Y"))) .".  If not, your account will be referred for recovery action, additional costs will be incurred by you and your credit history may reflect this unpaid debt and your ability to obtain finance will be adversely influenced during the next 5 years.<br><br>Please contact our Accounts Department immediately to resolve this matter.  Your accout is suspended and any outstanding facility is also due and payable in Australia Dollars.  Payments can be made by returning a cheque; or by phone with a credit card.  Alternatively you can bank transfer to our Suncorp Bank Account - BSB: 484799, Account No: 003598705, or BPAY - Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>If you have paid the overdue amount in the last few days, it has not yet shown in our accounts, so please provide us with details so that the matter can be rectified.<br><br>We look forward to hearing from you.<br><br><br>Regards<br><br>Diane Phelps";
	     $text = "We regret to advise you that due to the conduct of your account, you leave us no alternative other than to consider cancellation of your Empire Trade membership.<br> <br><b>This is our Final letter regarding outstanding fees of ".feesowing($value) .".</b><br><br>If you fail to finalise your account within 7 days, Empire Trade may lodge a default for the amount owing with Veda Advantage, Australia�s largest Credit Reporting Agency.  This may affect your borrowing capacity and credit rating for all future business and personal requirements for a period of up to 5 years.<br><br>As your membership is important to us, we would prefer to discuss other options with you to ensure your continued membership and trading.  <br><br>Your business is important to us, so if you are suffering financial hardship and are unable to pay the full amount, contact our Accounts Department today, and we may be able to assist you by offering a payment plan as a short term option to bring your account up to date.<br><br>Regards<br><br>Diane Phelps";
	     //$text = "With reference to our previous correspondance regarding your outstanding fees, we appreciate that the Global Economic Crisis has had an effect on many businesses.  In these difficult economic times, we would like to make the following offer prior to sending your account to Debt Collection:<br><br>Empire Trade will waive the Monthly Administration fee for 3 months simply by paying the outstanding amount of ". feesowing($value) ."  and completing the attached direct debit form, for ongoing fees and charges, and returning to this office within 7 days of the date of this letter. <br><br>By having this direct debit in place your monthly Administration Fee with then be automatically reduced after your 3 month free period.<br><br>You can pay online using a credit card by logging on to the member's section of the Empire Trade website, and selecting the 'Fee Payment' option on the left of the screen.  This is a secure site.  Alternatively you can bank transfer into our Suncorp Bank Account - BSB: 484 799, Account No: 003598705 using your Empire Trade Account Number as the Reference, or you can BPAY, Biller Code: 374215, Reference: ". bpay_code($value) .".<br><br>The Direct Debit can either be mailed to us at PO Box 151 Buddina  Qld  4575, or faxed to (07) 5437 7230.  Please put '3 Month Free Offer' across the top of your direct Debit.<br><br>We hope this will be of some assistance to you and avoid the additional cost of debt collection.<br><br><br>Regards<br>Diane Phelps<br>Membership Accounts";
	     $text = get_html_template($row2['CID'],$row2['accholder'],$text);

	     unset($attachArray);
	     unset($addressArray);

	 	 $attachArray[] = array($SBuffer, 'D035.pdf', 'base64', 'application/pdf');

		 if(strstr($row2['emailaddress'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
				$addressArray[] = array(trim($value), $row2['accholder']);
			}
		 } else {
			$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
		 }

		 sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Overdue Fees & Charges - Final Notice', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

		}

	} else {

    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

    // get the actual taxinvoice ready.
    $buffer = debtcollect($MemberArray,'3',$_REQUEST[header]);

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'baddebt.txt', 'base64', 'text/plain');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Letters - '.$letterno2, 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);




    foreach($MemberArray as $key => $value) {
     dbWrite("update members set letters = '4' where memid = '$value'");
     dbWrite("update members set status = '5' where memid = '$value'");
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "Pending Further Action";
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
    }
    }

   } elseif ($letterno == 4) {


    foreach($MemberArray as $key => $value) {
     dbWrite("update members set letters = '6' where memid = '$value'");
     dbWrite("update members set status = '5' where memid = '$value'");
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "<b>Debt collection action stopped<br>Contact needed to resolve and get trading</b>";
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
    }

   } elseif($letterno == 5) {


    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 //$det = "Overdue Fee Letter 1 Sent";
	 $det = "Fee Reduction Letter Emailed";
     //dbWrite("update members set `letters` = 6 where memid = '$value'");
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");


	}

   } elseif($letterno == 6) {

   // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";
     $buffer = feeletters($MemberArray,'50',$_REQUEST[header]);
     unset($attachArray);
     unset($addressArray);
   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Letters - 1', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 //$det = "Overdue Fee Letter 1 Sent";
	 $det = "Fee Reduction Letter Emailed";
     //dbWrite("update members set letters = '1' where memid = '$value'");
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");


     $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.memid = '".$value."' order by companyname");
     $row2 = mysql_fetch_array($query2);

     //$text = "As you are probably aware, Empire Trade is now under new management. While the ownership of the Exchange has changed, all trading arrangements with Empire Trade Exchange remain intact including all debts owed. <br><br>The new owner Mr Peter Barnes is prepared to offer a moratorium on outstanding fees as a means of establishing a new trading relationship. Mr Barnes understands that there has been a number of economic factors that have affected bartering over recent years. In light of these factors Mr Barnes is generously offering to waive half of your outstanding fees on the following provisos.<br><br> - Half of the outstanding fees are paid by close of business Friday 15th April 2011.<br><br> - A Direct Debit (Attached) is set up on your account. This also reduces future Administration Fees.<br><br>Peter and the Exchange would like to see you actively trading with confidence again, offering your products and services on a percentage of Empire Trade to attract new business. <br><br>Peter is currently overseas negotiating with international suppliers and setting up a supply chain of suitable products to be offered to members. This will provide an opportunity for you to access a new range of products at realistic prices and with a generous trade component.<br><br>Total overdue fees outstanding ". feesowing($value)." <br>Less 50% reduction if received by the 15th April 2011.<br><br>Phone Empire Trade on  07 5437 7220 with your credit card details to make payment, BPAY your fees online or Bank Tranfer as follows.<br><br>BSB: 484799<br>Account: 003598705 <br><br>BPAY Biller Code: 374215<br>BPAY Refrence Number: ". bpay_code($value) ." <br><br>If you are interested in imported product, please contact Head Office on 07 5437 7220.<br><br>Best wishes and thank you for your past and continued support.<br><br>The Team at Empire Trade Exchange.";
     $text = "As you are probably aware, Empire Trade is now under new management. While the ownership of the Exchange has changed, all trading arrangements with Empire Trade Exchange remain intact including all debts owed. <br><br>The new owner Mr Peter Barnes and the Exchange would like to see you actively trading with confidence again offering your products and services on a percentage of Empire Trade to attract new business and to utilise your trade in the purchase of goods and services. He is currently overseas negotiating with international suppliers and setting up a supply chain of suitable products to be offered to members. This will provide an opportunity for you to access a new range of products at realistic prices and with a generous trade component.<br><br>Peter understands that there has been a number of economic factors that have affected business over recent years and in light of these factors he is prepared to offer a moratorium on outstanding fees as a means of establishing a new trading relationship and is generously offering to waive half of your outstanding fees on the following provisos:<br><br> - Half of the outstanding fees are paid by close of business Friday 15th April 2011.<br><br> - A Direct Debit (Attached) is set up on your account. This also reduces future Administration Fees.<br><br><b>Total overdue fees outstanding ". feesowing($value)." </b> <br> Less 50% reduction if received by the 15th April 2011.<br><br>Phone Empire Trade on  07 5437 7220 with your credit card details to make payment, BPAY your fees online or Bank Tranfer as follows.<br><br>BSB: 484799<br>Account: 003598705 <br><br>BPAY Biller Code: 374215<br>BPAY Refrence Number: ". bpay_code($value) ." <br><br>If you are interested in imported product, please contact Head Office on 07 5437 7220.<br><br>Best wishes and thank you for your past and continued support.<br><br>The Team at Empire Trade Exchange.";
	 //$text = "The Empire Trade International Barter Exchange is now under new management. Robert Howell who previously owned and operated the Trade Exchange is no longer involved in the ownership or the operation of Empire Trade Exchange International or Empire Trade Exchange Australia. The management and staff of Empire Trade wish Robert all the best. <br><br>The current owner, Mr Peter Barnes, has been involved in the International Exchange since Nov 2006 when he financed the acquisition from the founder of the Exchange. At that time, Peter directed his focus onto the international arena and established business relationships internationally with the intention of providing a product supply stream to enable members to access a range of quality goods at fair prices and with a reasonable trade portion. Peter is very conscious of the fact that this has been spoken of before, but wishes to advise that he is currently overseas negotiating with international suppliers of suitable products. <br><br>As the sole owner of the International Exchange and of Empire Trade Australia, Peter is focused on establishing trading opportunities that will cause Empire Trade to excel in the Barter Industry. The International Exchange now known as Empire Trade was originally established in Maroochydore in the early part of the year 2000. Since then, in only 11 years, we have seen the Exchange grow from virtual insignificance, to become one of the three largest in Australia. With membership extending over a number of countries, Peter believes that Empire Trade will cement its place in the Barter Industry as an industry leader focused on providing member support services and shoring up product supply to fill in the gaps for goods that are not regularly available to bartering members.  <br><br>If you are interested in imported product, please call and talk to one of the friendly staff at head office on 07 5437 7220.<br><br>Best wishes and thank you for your past and continued support.<br><br>The Team at Empire Trade Exchange";
     $text = get_html_template($row2['CID'],$row2['accholder'],$text);


	     unset($attachArray);
	     unset($addressArray);

	 	 $SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/pdf/D035-au.pdf");
	 	 $attachArray[] = array($SBuffer, 'D035.pdf', 'base64', 'application/pdf');

		 if(strstr($row2['emailaddress'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
				$addressArray[] = array(trim($value), $row2['accholder']);
			}
		 } else {
			$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
		 }

		 sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Empire Trade fee reduction offer', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

		}

	} elseif($letterno == 7) {

    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";
     $buffer = feeletters($MemberArray,'46',$_REQUEST[header]);
     unset($attachArray);
     unset($addressArray);
   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Dishonoured DD', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);


    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 //$det = "Overdue Fee Letter 1 Sent";
	 //$det = "Direct Debit Change Notification";
	 $det = "Dishonoured Direct Debit";
     //dbWrite("update members set letters = '1' where memid = '$value'");
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");


     $query2 = dbRead("select members.*, tbl_members_email.email as emailaddress from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.email != '' and tbl_members_email.type = 2 and members.memid = '".$value."' order by companyname");
     $row2 = mysql_fetch_array($query2);

    //$text = "<b>Account No: ". $value ."</b><br><br>It has come to our attention that you previously had a direct debt in place on your account which is no longer functional.<br><br>Having a Direct Debit in place or setting up a Credit Card payment method will <b>". adfe($value) ."</b>.<br><br>If you would like to take advantage of reduced fees, please fill in the attached Direct Debit form and fax it back to 07 5437 7230 or mail to the National Head Office.<br><br>Thank you for your ongoing support of the Empire Trade system and we look forward to hearing from you soon.<br><br>The Team at Empire Trade Exchange";
    $text = "<b>Account No: ". $value ."</b><br><br>This is to advise that the Direct Debit we hold for you will change from March 2012. The date that Direct Debits are drawn will change from the 21st to 15th of the month.<br><br>Thank you for your ongoing support of the Empire Trade system.<br><br>The Team at Empire Trade Exchange";
	$text = get_html_template($row2['CID'],$row2['accholder'],$text);

	     unset($attachArray);
	     unset($addressArray);

	 	 //$SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/pdf/D035-au.pdf");
	 	 //$attachArray[] = array($SBuffer, 'D035.pdf', 'base64', 'application/pdf');

		 if(strstr($row2['emailaddress'], ";")) {
			$emailArray = explode(";", $row2['emailaddress']);
			foreach($emailArray as $key => $value) {
				$addressArray[] = array(trim($value), $row2['accholder']);
			}
		 } else {
			$addressArray[] = array(trim($row2['emailaddress']), $row2['accholder']);
		 }

		 //$addressArray[] = array(trim("dave@hq.etxint.com"), $row2['accholder']);

		 //sendEmail("accounts@". $_SESSION[Country][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), 'Dishonoured Direct Debit', 'accounts@' . $_SESSION[Country][countrycode] .'.'. getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

		}
	}
}
 return $blah;
}

function letter2($userid,$letterno) {

	 $blah = "";

     $query2 = dbRead("select * from members, area where members.licensee = area.FieldID and area.user = '".$userid."' and members.CID = '".$_SESSION['User']['CID']."' and members.letters = '".$letterno."' order by members.memid");
     while($row2 = mysql_fetch_array($query2)) {

		$memid = $row2['memid'];
		$companyname = $row2['companyname'];
	 	$blah .= "$memid - $companyname\r\n";

	 }

	return $blah;
}

?>