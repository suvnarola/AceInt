<?


include("letterscashfees.php");
include("class.html.mime.mail.inc");
if(!checkmodule("MyServices")) {

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

<form method="POST" action="body.php?page=solutions_admin&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_REQUEST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array("UnPaid","Paid","Pending","Issued","Active");

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

 update('0');
 letters('0');

} elseif($_GET[tab] == "tab2") {

 update('1');
 letters('1');

} elseif($_GET[tab] == "tab3") {

 update('1','1');
 letters('1','1');

} elseif($_GET[tab] == "tab4") {

 update('2');
 letters('2');

} elseif($_GET[tab] == "tab5") {

 update('3');
 letters('3');

}

?>
</form>
<?

function letters($letterno, $pend = false) {

 global $time_start;

 $colspan = "14";

 if($_REQUEST['payment']) {

   $query = dbRead("select * from registered_accounts where FieldID = ".$_REQUEST['FieldID']."","empire_solutions");
   $row = mysql_fetch_array($query);
 ?>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading2">Payment</td>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Reg Account ID:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><?= $row['FieldID'] ?></td>
		<input type="hidden" name="FieldID" value="<?= $row['FieldID'] ?>">
	</tr>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Cash Owing:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><?= $row['cashToPay'] ?></td>
	</tr>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Cash Paid:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="cash" onKeyPress="return number(event)"></td>
	</tr>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Payment Method:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="method"></td>
	</tr>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Trade Owing:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><?= $row['tradeToPay'] ?></td>
	</tr>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Trade Paid:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="trade" onKeyPress="return number(event)"></td>
	</tr>
	</tr>
		<td class="Heading2" width="100" align="right"><b>Auth No:</b></td>
		<td bgcolor="#FFFFFF" width="599" align="left"><input type="text" size="10" name="auth" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="Process Payment" name="process"></td>
	</tr>
</table>
</td>
</tr>
</table>

 <?

 } else {

   if($_REQUEST['process']) {

    $query = dbRead("select registered_accounts.*, plans.Plan_Fee as Plan_Fee from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and registered_accounts.FieldID = ". $_REQUEST['FieldID'] ."","empire_solutions");
    $row = mysql_fetch_array($query);

    $cc = $row['cashToPay'] - $_REQUEST['cash'];
    $tt = $row['tradeToPay'] - $_REQUEST['trade'];
    $aa = $_REQUEST['cash'] - $row['Plan_Fee'];

    if($_REQUEST['cash']) {
     dbWrite("update registered_accounts set cashToPay = ". $cc .", creditReceiptNo = '". $_REQUEST['method'] ."' where FieldID = ". $row['FieldID'] ."","empire_solutions");
   	 dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date, dollarfees) values ('" . $_REQUEST['FieldID'] . "','" . $aa . "','1','Cash Payment - " . $_REQUEST['FieldID'] . "','" . $_REQUEST['method'] . "','" . date("Y-m-d") . "','" . $row['Plan_Fee'] . "')", "empire_solutions");
    }

    if($_REQUEST['trade']) {
     dbWrite("update registered_accounts set tradeToPay = ". $tt .", tradeReceiptNo = '". $_REQUEST['auth'] ."' where FieldID = ". $row['FieldID'] ."","empire_solutions");
 	 dbWrite("insert into transactions (regAccID, sell, type_id, details, receipt, dis_date) values ('" . $_REQUEST['FieldID'] . "','" . $_REQUEST['trade'] . "','2','Trade Payment - " . $_REQUEST['FieldID'] . "','" . $_REQUEST['auth'] . "','" . date("Y-m-d") . "')", "empire_solutions");
    }

	if(($cc - $tt) == 0) {
      dbWrite("update registered_accounts set Status_ID = 1, Date_Paid = '". date("Y-m-d")."' where FieldID = ". $row['FieldID'] ."","empire_solutions");
	}
  }

?>
 <table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">List <?= $letterno ?></td>
   </tr>
   <tr>
     <td class="Heading2"><b>C/No:</b></td>
     <td class="Heading2" nowrap align = "right"><b><?= get_word("1")?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Service:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T %:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/us:</b></td>
     <td class="Heading2" nowrap align = "right"><b>D/Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Terms:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Remain:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Plan<br>Amt:</b></td>
     <td class="Heading2" nowrap align = "right"><b>C/O:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T/O:</b></td>
     <td class="Heading2" nowrap align = "right"><b>T/Amt:</b></td>
      <?if($letterno == 1 && $pend == 1) {?>
     <td class="Heading2" nowrap align = "right"><b>Card No:</b></td>
	  <?} else {?>
     <td class="Heading2" nowrap align = "right"><b></b></td>
	  <?}?>
     <td align="right" class="Heading2"><b>DEL:</b></td>
   </tr>
<?

   $foo = 0;
   $date2 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-6,date("Y")));
   //$date2 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

   if($letterno == 1 && $pend == 1) {
     $dd = " and Date_Paid < '$date2'";
   } else {
    $dd = "";
   }

   $query = dbRead("select *, registered_accounts.FieldID as FieldID2 from registered_accounts, plans, services where (registered_accounts.Plan_ID = plans.FieldID) and (plans.ServiceID = services.FieldID) and (plans.CID = services.CID) and Status_ID = '$letterno'$dd order by registered_accounts.Date_Paid, registered_accounts.Acc_No ASC",empire_solutions);

   if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

   } else {
    $counter = 0;
    $dtotal = 0;
    $ptotal = 0;
    while($row = mysql_fetch_array($query)) {

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $t = $row['Terms']*$row['Plan_Amount'];
     ?>
     <tr bgcolor="<?= $bgcolor ?>">
      <td><?= $row['FieldID2'] ?></td>
      <td nowrap align = "right"><?= $row['Acc_No'] ?></td>
      <td nowrap align = "right"><a href="body.php?page=services_statement&fieldid=<?= $row['FieldID2']?>" class="nav"><?= $row['Product'] ?></a></td>
        <td  nowrap align = "right"><?= number_format($row['Trade_Percent'],0) ?></td>
        <td  nowrap align = "right"><?= $row['Status_ID'] ?></td>
        <td  nowrap align = "right"><?= $row['Date_Paid'] ?></td>
        <td  nowrap align = "right"><?= $row['Terms'] ?></td>
        <td  nowrap align = "right"><?= $row['Payments_Left'] ?></td>
        <td  nowrap align = "right"><?= $row['Plan_Amount'] ?></td>
        <td  nowrap align = "right"><a href="body.php?page=services_admin&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>&payment=1&FieldID=<?= $row['FieldID2'] ?>" class="nav"><?= number_format($row['cashToPay']) ?></a></td>
        <td  nowrap align = "right"><?= number_format($row['tradeToPay']) ?></td>
        <td  nowrap align = "right"><?= number_format($t) ?></td>
      <?if($letterno == 1 && $pend == 1) {?>
        <td align="right"><input type="textbox" size="14" name="num[<?= $row['FieldID2'] ?>]" value="<?= $row['Card_No'] ?>" <?if($row['ServiceID'] != 1) {?>readonly<?}?>></td>
	  <?} else {?>
	    <td align="right"></td>
	  <?}?>
        <td align="right"><input type="checkbox" name="del[]" value="<?= $row['FieldID2'] ?>"></td>
    </tr>
    <?
    $dtotal = $dtotal+$t;
    $ptotal = $ptotal+$row['Plan_Amount'];
 	$foo++;
 	$counter++;
 	$TOTAL[$row['Product']] += abs($row['Plan_Amount']);
 	$TTOTAL[$row['Product']] += abs($t);
    }

   }

?>
       <tr bgcolor="#FFFFFF">
        <td></td>
        <td></td>
        <td  nowrap colspan="2" align="right">No. of Members: <?= $counter ?></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"><?= number_format($ptotal) ?></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"></td>
        <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
        <td  nowrap align = "right"></td>
        <td align="right"></td>
       </tr>
       <?
	 foreach($TOTAL as $key => $value) {

	 $Type = get_fee_type($key);

	 ?>
 	 <tr bgcolor="#FFFFFF">
 	    <td  nowrap colspan="5" align = "right"></td>
		<td colspan="3" align="left"><b><?= $key ?>:</b></td>
		<td align="right"><b><?= number_format($value,2) ?></b></td>
 	    <td  nowrap colspan="2" align = "right"></td>
		<td align="right"><b><?= number_format($TTOTAL[$key],2) ?></b></td>
 	    <td  nowrap colspan="2" align = "right"></td>
	 </tr>
	 <?

	 }
	 ?>
      <tr>
       <td colspan="<?= $colspan ?>" bgcolor="#FFFFFF">
       <table border="0" cellspacing="0" width="100%" cellpadding="0">
 	   <?if(checkmodule("SuperUser")) {?>
        <tr>
         <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
         <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
        </tr>
       <?}?>
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
}

function update($letterno,$pend = false) {

 global $minutesPDF;

 if($_REQUEST[B1]) {

  $DelArray = $_REQUEST[del];

  foreach($DelArray as $key => $value) {

   if($letterno == 2 || $letterno == 3 || $letterno == 4)  {

    //dbWrite("update members set status = '0' where memid='$value' and status='5'");
    //dbWrite("update members set status = '0' where memid='$value' and status='6'");

   }

   //dbWrite("update members set letters = '0' where memid='$value'");

  }

 } elseif($_REQUEST[move]) {

  $MemberArray = $_REQUEST[del];
  $CardArray = $_REQUEST[num];
  $letterno2=$letterno+1;

  print_r($CardArray);

   if($letterno == 0) {

  // define the text.
   //$text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   //$buffer = feeletters($MemberArray,'1',$_REQUEST[header]);

  // define carriage returns for macs and pc's
   //define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   //$mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   //$mail->add_text($text);

  // add the attachment on.
   //$mail->add_attachment($buffer, 'letters.pdf', 'application/pdf');

  // build the message.
   //$mail->build_message();

  // send the message.
   //$mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Letters - 1');


    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 //$currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 //$det = "Overdue Fee Letter 1 Sent";
     dbWrite("update registered_accounts set Statsus = '1' where memid = '$value'","empire_solutions");
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");

    }

   } elseif($letterno == 1) {

    if(substr_count($MemberArray, ",") > 0) {

      $MemberArray = explode(",", $MemberArray);

    }

    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "myServiceBanc - Letters";
     dbWrite("update registered_accounts set Status_ID = '2' where FieldID = '$value'","empire_solutions");
     if($pend && $CardArray[$value] > 0) {
      dbWrite("update registered_accounts set Card_No = '".$CardArray[$value]."' where FieldID = '$value'","empire_solutions");
     }

    }

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters($MemberArray,'22',$_REQUEST[header]);

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.

   include("minutesPDF.php");

   $mail->add_attachment($Buffer, 'minutes.pdf', 'application/pdf');
   $mail->add_attachment($buffer, 'letters.pdf', 'application/pdf');

  // build the message.
   $mail->build_message();

  // send the message.
   //$mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'myServicesBanc - Letters');

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
   	$attachArray[] = array($Buffer, 'minutes.pdf', 'base64', 'application/pdf');

	if(strstr($_SESSION['User']['EmailAddress'], ";")) {
		$emailArray = explode(";", $_SESSION['User']['EmailAddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $_SESSION['User']['Name']);
		}
	} else {
		$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);
	}

	sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $_SESSION[Country][countrycode] .'.' . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);


   } elseif ($letterno == 2) {

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters($MemberArray,'3',$_REQUEST[header]);

  // define carriage returns for macs and pc's
   define('CRLF', "\r\n", TRUE);

  // create a new mail instance
   $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

  // add the text in.
   $mail->add_text($text);

  // add the attachment on.
   $mail->add_attachment($buffer, 'letters.pdf', 'application/pdf');

  // build the message.
   $mail->build_message();

  // send the message.
   //$mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Letters - '.$letterno2);

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');

	if(strstr($_SESSION['User']['EmailAddress'], ";")) {
		$emailArray = explode(";", $_SESSION['User']['EmailAddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $_SESSION['User']['Name']);
		}
	} else {
		$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);
	}

	sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($row2[logo], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $_SESSION[Country][countrycode] .'.' . getWho($row2[logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray, $attachArray);


    if(substr_count($MemberArray_temp, ",") > 0) {

     $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {
     dbWrite("update registered_accounts set Status = '3' where memid = '$value'","empire_solutions");
     //dbWrite("update members set status = '6' where memid = '$value' and status = '0'");
     //dbWrite("update members set status = '6' where memid = '$value'");
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "Overdue Fee Letter 3 Sent";
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");

    }

   } elseif ($letterno == 3) {

    foreach($MemberArray as $key => $value) {
     //dbWrite("update members set letters = '4' where memid = '$value'");
     //dbWrite("update members set status = '6' where memid = '$value'");
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "Sent to Solicitor";
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
    }

   }

  }

}
