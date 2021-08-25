<?


include("letterscashfees.php");
include("class.html.mime.mail.inc");

if(!checkmodule("Clubs")) {

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

<form method="POST" action="body.php?page=club_admin&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_REQUEST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array($_SESSION['Country']['fiftyName'],"Waiting Appoval of ".$_SESSION['Country']['fiftyName']);
 if($_SESSION['Country']['gold']) { $tabarray[] = $_SESSION['Country']['goldName']; }
 if($_SESSION['Country']['gold']) { $tabarray[] = "Waiting Appoval of ".$_SESSION['Country']['goldName']; }
// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

 update('1');
 letters('1');

} elseif($_GET[tab] == "tab2") {

 update('4');
 letters('4');

} elseif($_GET[tab] == "tab3") {

 update('2');
 letters('2');

} elseif($_GET[tab] == "tab4") {

 update('3');
 letters('3,5');

}

?>

</form>

<?

function letters($letterno) {

 global $time_start;

 $colspan = "11";

if($letterno == 1) {
 $yy = "50% Plus Club Members";
} elseif($letterno == 2) {
 $yy = "Gold Club Members";
} elseif($letterno == 3) {
 $yy = "Waiting Approval for Gold Club";
} elseif($letterno == 4) {
 $yy = "Waiting Approval for 50% Plus Club";
}
?>
 <table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Add Member</td>
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
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading"><?= $yy ?></td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1")?>:</b></td>
     <td class="Heading2" width = "250" ><b><?= get_word("3")?>:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>L/Traded:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Balance:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/Facility:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Net:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Claimable:</b></td>
     <td align="right" class="Heading2"><b>DEL:</b></td>
   </tr>
<?

   $foo = 0;
   $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members left outer join transactions on (members.memid = transactions.memid) where fiftyclub in (".$letterno.") and CID = '".$_SESSION['User']['CID']."' group by members.memid order by members.memid ASC");

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

    $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	$row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></td>
      <td  width = "250"><?= $row[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row[fee_deductions],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees]) ?></td>
      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= number_format($claim) ?></td>
      <td align="right"><input type="checkbox" name="del[]" value="<?= $row[memid] ?>"></td>
    </tr>
    <?

 	$foo++;
 	$counter++;
    }

   }

?>
       <tr bgcolor="#FFFFFF">
        <td width = "80"></td>
        <td  nowrap>No. of Members: <?= $counter ?></td>
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
      <tr>
       <td colspan="<?= $colspan ?>" bgcolor="#FFFFFF">
       <table border="0" cellspacing="0" width="100%" cellpadding="0">
        <tr>
         <td bgcolor="#FFFFFF" align="right"><?if(($_GET[tab] == "tab2") || ($_GET[tab] == "tab4")) {?><input type="submit" name="move" value="Approve"><?}?></td>
         <td bgcolor="#FFFFFF" align="right"><?if(($_GET[tab] == "tab2") || ($_GET[tab] == "tab4")) {?><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Decline" name="B1"><?}?></td>
         <td bgcolor="#FFFFFF" align="right"><?if(($_GET[tab] == "tab1") || ($_GET[tab] == "tab3")) {?><input type="submit" name="remove" value="Remove"><?}?></td>
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

   dbWrite("update members set fiftyclub = '$letterno' where memid = '$value'");
   if($letterno == 1) {
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','" . date("y-m-d H:i:s") . "','180','1','Member Accepted for 50% Plus Club')");
   } elseif($letterno == 2) {
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','" . date("y-m-d H:i:s") . "','180','1','Member Accepted for Gold Club')");
   }

  }

 } elseif($_REQUEST[B1]) {

  $DelArray = $_REQUEST[del];

  foreach($DelArray as $key => $value) {

   $query = dbRead("select members.memid as memid, members.companyname as companyname, tbl_members_email.email as emailaddress, members.contactname as contactname, members.fiftyclub as fiftyclub from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and memid = '$value'");
   $row = mysql_fetch_array($query);

   if($row['fiftyclub'] == 5) {

     //$text = "Thank you for your application to upgrade to the Gold Club.  Unfortunately you do not meet all the requirements for membership to the this Club.  If, in the future, your circumstances change and you feel you meet all requirements, you are invited to apply again for membership.<br><br>You are currently a member of the 50% Plus Club and have access through the member's section to participate in the essential products and services programmes which are offered to Empire Trade 50% Plus Club members when they elect to become a class member of the company, My Services Banc.com Ltd (myServicesBanc). Membership to myServicesBanc is available to you on 50% trade. Please be sure to read the Terms and Conditions carefully before becoming a member of myServicesBanc and participating in any of these programmes.<br><br>If you have any queries, please contact Member Support at Head Office on 07 5437 7220 or customersupport@ebanctrade.com.";
     $text = get_page_data(4);
     $text = get_html_template($_SESSION['Country']['countryID'],$row['accholder'],$text);
     define("CRLF", "\r\n", TRUE);

     unset($attachArray);
     unset($addressArray);

	 if(strstr($row['emailaddress'], ";")) {
		$emailArray = explode(";", $row['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row['contactname']);
		}
	 } else {
		$addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 }

	 //$addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), "Club Application - ".$row['companyname'], 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray);

     dbWrite("update members set fiftyclub = '1' where memid = ".$row['memid']."");
     dbWrite("insert into notes (memid,date,userid,type,note) values ('".$row['memid']."','" . date("y-m-d H:i:s") . "','180','1','Member Declined for Upgrade to Gold Club')");

   } elseif($row['fiftyclub'] == 4 || $row['fiftyclub'] == 3) {

     //$text = "Thank you for your application to join one of Empire Trade Clubs.  Unfortunately you do not meet all the requirements for membership at this time.  If, in the future, your circumstances change and you feel you meet all requirements, you are invited to apply again for membership.<br><br>Please note your are still able to participate in the essential products and services programmes which are offered to Empire Trade members when they elect to become a class member of the company, My Services Banc.com Ltd (myServicesBanc). Membership to myServicesBanc is available to you on 20% trade.  Please be sure to read the Terms and Conditions carefully before becoming a member of myServicesBanc and participating in any of these programmes.<br><br>If you have any queries, please contact Member Support at Head Office on 07 5437 7220 or customersupport@ebanctrade.com.";
     $text = get_page_data(3);
     $text = get_html_template($_SESSION['Country']['countryID'],$row['accholder'],$text);
     define("CRLF", "\r\n", TRUE);

     unset($attachArray);
     unset($addressArray);

	 if(strstr($row['emailaddress'], ";")) {
		$emailArray = explode(";", $row['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row['contactname']);
		}
	 } else {
		$addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 }

	 //$addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), "Club Application - ".$row['companyname'], 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray);

	 if($row['fiftyclub'] == 3) {
      dbWrite("insert into notes (memid,date,userid,type,note) values ('".$row['memid']."','" . date("y-m-d H:i:s") . "','180','1','Member Declined for the Gold Club')");
	 } else {
      dbWrite("insert into notes (memid,date,userid,type,note) values ('".$row['memid']."','" . date("y-m-d H:i:s") . "','180','1','Member Declined for the 50% Plus Club')");
	 }
     dbWrite("update members set fiftyclub = '0' where memid = ".$row['memid']."");

   }

  }

 } elseif($_REQUEST[move]) {

  $MemberArray = $_REQUEST[del];
  $letterno2=$letterno+1;

  if ($letterno == 3) {

    if(substr_count($MemberArray_temp, ",") > 0) {

     $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

     $query = dbRead("select members.memid as memid, members.companyname as companyname, members.accholder as accholder, tbl_members_email.email as emailaddress, members.contactname as contactname from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and memid = '$value'");
     $row = mysql_fetch_array($query);

     //$text = "Congratulations, you meet all the requirement for membership to the Gold Club.  Your application for membership has been successful. <br><br>Your membership of the Gold Club also gives you automatic membership to the 50% Plus Club <br><br>You can search our online directory for other members of the Gold Club and 50% Plus Club by ticking the relevent box in the member section Directory Search or Directory Downloads.  You have agreed to trade with other Gold Club members at 100% trade and with 50% Plus Club members at a minimum of 50% Trade.<br><br>You also have online access through the member's section to participate in the essential products and services programmes which are offered to Empire Trade Gold Club members when they elect to become a class member of the company, My Services Banc.com Ltd (myServicesBanc). Membership to myServicesBanc is available to you on 50% trade. Please be sure to read the Terms and Conditions carefully before becoming a member of myServicesBanc and participating in any of these programmes.<br><br>If you have any queries, please contact Member Support at Head Office on 07 5437 7220 or customersupport@ebanctrade.com.";
     $text = get_page_data(2);
     $text = get_html_template($_SESSION['Country']['countryID'],$row['accholder'],$text);
     define("CRLF", "\r\n", TRUE);

     unset($attachArray);
     unset($addressArray);

	 if(strstr($row['emailaddress'], ";")) {
		$emailArray = explode(";", $row['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row['accholder']);
		}
	 } else {
		$addressArray[] = array(trim($row['emailaddress']), $row['accholder']);
	 }

	 $SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/pdf/ClubQA.pdf");
	 $attachArray[] = array($SBuffer, 'Club-qa.pdf', 'base64', 'application/pdf');

	 //$addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), "Club Application - ".$row['companyname'], 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

     dbWrite("update members set fiftyclub = '2' where memid = '$value'");
	 dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','" . date("Y-m-d H:i:s") . "','180','1','Member Approved for Gold Club')");

    }

   }  elseif ($letterno == 4) {

    if(substr_count($MemberArray_temp, ",") > 0) {

     $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

     $query = dbRead("select members.memid as memid, members.companyname as companyname, members.accholder as accholder, tbl_members_email.email as emailaddress, members.contactname as contactname from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and memid = '$value'");
     $row = mysql_fetch_array($query);

     //$text = "Congratulations, you meet all the requirement for membership to the 50% Plus Club.  Your application for membership has been successful.  <br><br>You can search our online directory for other members of the 50% Plus Club by ticking the relevent box in the member section Directory Search or Directory Downloads.  You have agreed to trade with other 50% Plus Club members and Gold Club members at a minimum of 50% trade.<br><br>You also have online access through the member's section to participate in the essential products and services programmes which are offered to Empire Trade Gold Club members when they elect to become a class member of the company, My Services Banc.com Ltd (myServicesBanc). Membership to myServicesBanc is available to you on 50% trade. Please be sure to read the Terms and Conditions carefully before becoming a member of myServicesBanc and participating in any of these programmes.<br><br>If you have any queries, please contact Member Support at Head Office on 07 5437 7220 or customersupport@ebanctrade.com.";
     $text = get_page_data(1);
     $text = get_html_template($_SESSION['Country']['countryID'],$row['accholder'],$text);
     define("CRLF", "\r\n", TRUE);

     unset($attachArray);
     unset($addressArray);

	 if(strstr($row['emailaddress'], ";")) {
		$emailArray = explode(";", $row['emailaddress']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row['accholder']);
		}
	 } else {
		$addressArray[] = array(trim($row['emailaddress']), $row['accholder']);
	 }

	 $SBuffer = file_get_contents("http://www.ebanctrade.com/downloads/pdf/ClubQA.pdf");
	 $attachArray[] = array($SBuffer, 'Club-qa.pdf', 'base64', 'application/pdf');

	 //$addressArray[] = array(trim($row['emailaddress']), $row['contactname']);
	 sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), "Club Application - ".$row['companyname'], 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

     dbWrite("update members set fiftyclub = '1' where memid = '$value'");
	 dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','" . date("y-m-d H:i:s") . "','180','1','Member Approved for 50% Plus Club')");

    }

   }

  } elseif($_REQUEST[remove]) {

  $DelArray = $_REQUEST[del];

  foreach($DelArray as $key => $value) {

     $query = dbRead("select members.memid as memid, members.companyname as companyname, members.accholder as accholder, tbl_members_email.email as emailaddress, members.contactname as contactname, members.fiftyclub as fiftyclub from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and memid = '$value'");
     $row = mysql_fetch_array($query);

	 if($row['fiftyclub'] == 1) {
      dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','" . date("y-m-d H:i:s") . "','180','1','Member has been removed from 50% Plus Club')");
      dbWrite("update members set fiftyclub = '0' where memid='$value'");
	 } elseif($row['fiftyclub'] == 2) {
      dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','" . date("y-m-d H:i:s") . "','180','1','Member has been removed from Gold Club')");
      dbWrite("update members set fiftyclub = '0' where memid='$value'");
	 }

  }

 }

}

?>