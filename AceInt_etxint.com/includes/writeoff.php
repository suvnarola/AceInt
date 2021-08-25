<?
//include("global.php");

if(!checkmodule("EditMemberLevel2")) {

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

// Start of Write Off Script.
$trans_date = date("Y-m-d");

if($_REQUEST['next'] == 1) {

	dbWrite("update members set status = '6' where memid = '$_REQUEST[memid]'");

	if($_REQUEST[reminder]) {
		$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
		$date2 = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+14,date("Y")));
		$note = "Waiting for reply from member about outstanding balances";
		dbWrite("insert into notes (memid, date, userid, reminder, note, type) values ('$_REQUEST[memid]', '".$date."', '".$_SESSION['User']['FieldID']."', '".$date2."', '".$note."','1')");
	}

	echo get_page_data("8");
	die;

} elseif($_REQUEST['next'] == 2) {
	if($_REQUEST['WriteOff'])  {

		// Set Facility and Real Estate Facility to 0 then run the facility script.
		dbWrite("update members set overdraft='0', reoverdraft='0' where memid = '$_REQUEST[memid]'");
		$memid = $_POST[memid];
		require("includes/facility.php");
		require("includes/facility2.php");

		$query2 = dbRead("select * from transactions where (memid = '".$_POST[memid]."' and to_memid = '".$_SESSION['Country']['suspense']."') or (memid = '".$_SESSION['Country']['suspense']."' and to_memid = '".$_POST[memid]."')");
		while ($row2 = mysql_fetch_assoc($query2)) {
			dbWrite("update transactions set memid='".$_SESSION['Country']['test']."', to_memid='".$_SESSION['Country']['test']."' where authno = '$row2[authno]' and ((memid = '".$_POST[memid]."' and to_memid = '".$_SESSION['Country']['suspense']."') or (memid = '".$_SESSION['Country']['suspense']."' and to_memid = '".$_POST[memid]."'))");
			dbWrite("update transactions set memid='".$_SESSION['Country']['test']."', to_memid='".$_SESSION['Country']['test']."' where id = '$row2[id]'");
		}

		// Get balance of member after facilities have been zeroed.
		$query = dbRead("select sum(sell)-sum(buy) as Balance, sum(dollarfees) as CashFees from transactions where memid = '$_POST[memid]'");
		$row = mysql_fetch_assoc($query);

		if($row[Balance] != 0) {

			if($row[Balance] > 0) {

			 	write_off_debt($_REQUEST[memid],$_SESSION[Country][w_trust],$trans_date,abs($row[Balance]),'Closing Balance Transferred to Trust');

			} else {

			 	write_off_debt($_SESSION[Country][writeoff],$_REQUEST[memid],$trans_date,abs($row[Balance]),'Closing Balance Wrote Off');

			}
		}

		if($row[CashFees] > 0) {

			write_off_fees($_REQUEST[memid],$_SESSION[Country][reserveacc],$trans_date,$row[CashFees],'Reversal of Fees');

		} elseif($row[CashFees] < 0) {

			add_pre_fees($_REQUEST[memid],$_SESSION[Country][reserveacc],$trans_date,abs($row[CashFees]),'Balancing Fees');

		}

		dbWrite("update members set status = '1', fiftyclub = '0', letters = '0', fee_deductions = '0', over_payment = '0', datedeactivated = '".date("Y-m-d")."' where memid = '$_REQUEST[memid]'");
		$note = "Member has been deactivated.";
		dbWrite("insert into notes (memid, date, userid, note, type) values ('$_REQUEST[memid]', '".date("y-m-d")."', '".$_SESSION['User']['FieldID']."','".$note."','1')");
		add_kpi("57", $_REQUEST[memid]);
		delete_cats($_REQUEST[memid]);
		update_feesincurred($_REQUEST[memid]);

    $dbquery = dbRead("select * from members where memid = ".$_REQUEST[memid]."");
    $row = mysql_fetch_assoc($dbquery);
    $subject = "Member Deactivated";
    $text = "Member ".$row['companyname']." - ".$_REQUEST['memid']." has been Deactivated\r\n\r\nMembership Accounts.";
    $text = get_html_template($_SESSION['User']['CID'],'MS',$text);

    unset($addressArray);
	$addressArray[] = array(trim('jane.m@au.empirexchange.com'), 'Corrie');
	//$addressArray[] = array(trim('kym.s@au.empirexchange.com'), 'Kym');

	sendEmail("accounts@" . $_SESSION[Country][countrycode] ."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray);

		echo get_page_data("9");
		die;

	} else {
		if($_REQUEST['tradeowing'] == 0.00 || $_REQUEST['feesowing'] == 0.00) {

			// Set Facility and Real Estate Facility to 0 then run the facility script.
			dbWrite("update members set overdraft='0', reoverdraft='0' where memid = '$_REQUEST[memid]'");
			$memid = $_POST[memid];
			require("includes/facility.php");

			// Deactivate them.
			dbWrite("update members set status = '1', letters = '0', fee_deductions = '0', over_payment = '0', datedeactivated = '".date("Y-m-d")."' where memid = '$_REQUEST[memid]'");
			add_kpi("57", $_REQUEST[memid]);
			delete_cats($_REQUEST[memid]);
			update_feesincurred($_REQUEST[memid]);
			echo get_page_data("9");
			die;
		} else {
			echo get_page_data("10");
		}
	}
	die;
}

$query = dbRead("select * from members where memid='".$_REQUEST['memid']."'");
$row = mysql_fetch_assoc($query);
?>
<html>
<body onload="javascript:setFocus('writeoff','memid');">

<form method="POST" action="body.php?page=writeoff" name="writeoff">
<input type="hidden" name="letter_no" value="5">
<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b><?= get_word("50") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF">
		 <?
		  $feesowing = feesowing($_REQUEST['memid']);
		  $tradeowing = tradeowing($_REQUEST['memid']);

		  if($_REQUEST['memid']) {

		   ?><input type="hidden" name="ChangeMargin" value="1"><input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>"><?= $_REQUEST['memid'] ?><?

		  } else {

		   ?><input type="text" size="10" name="memid" onKeyPress="return number(event)"><?

		  }
		 ?>
		</td>
	</tr>
	<tr>
	    <td width="130" align="right" class="Heading2"><b><?= get_word("47") ?>:</b></td>
	    <td align="left" bgcolor="#FFFFFF"><input type="hidden" name="feesowing" value="<?= $feesowing ?>"><?= $feesowing ?></td>
	</tr>
	<tr>
	    <td width="130" align="right" class="Heading2"><b><?= get_word("55") ?>:</b></td>
	    <td align="left" bgcolor="#FFFFFF"><input type="hidden" name="tradeowing" value="<?= $feesowing ?>"><?= $tradeowing ?></td>
	</tr>
	<?if(($feesowing != '0.00' && $row[status] != 6) || ($tradeowing != '0.00' && $row[status] != 6)) {?>
	<tr>
	    <td width="130" align="right" class="Heading2"></td>
	    <td align="left" bgcolor="#FF0000"><?= get_page_data("5") ?></td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b><?= get_page_data("2") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF">
        <input type="checkbox" name="reminder" checked value="1"></td>
	</tr>
	<tr>
	    <td width="130" align="right" class="Heading2">
	    </td>
	    <td align="left" bgcolor="#FFFFFF" >
	    <a href="/includes/lettersend.php?Client=<?= $_REQUEST['memid'] ?>&letter=1&type=1&letter_no=5&amount=<?= $amountcheck ?>" class="nav"><b><?= get_page_data("4") ?></b></a>
	    <input type="hidden" name="next" value="1"></td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b></b></td>
		<td align="left" bgcolor="#FFFFFF"><?= get_page_data("6") ?></td>
	</tr>
	<?} else {
	   if($feesowing != '0.00' || $tradeowing != '0.00')  {?>
	<tr>
	    <td width="130" align="right" class="Heading2"></td>
	    <td align="left" bgcolor="#FF0000"><?= get_page_data("7") ?></td>
	</tr>
	<tr>
		<td width="130" align="right" class="Heading2"><b><?= get_page_data("3") ?>:</b></td>
		<td align="left" bgcolor="#FFFFFF">
        <input type="checkbox" name="WriteOff" value="1"><input type="hidden" name="next" value="2"></td>
	</tr>
	  <?} else {?>
	    <input type="hidden" name="next" value="2">
	    <input type="hidden" name="WriteOff" value="2">
	  <?}
	}?>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="Continue"></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>

</body>
</html>
<?

function update_feesincurred($memid) {

 global $db, $linkid;

 dbWrite("update feesincurred set fee_paid = fee_amount where memid = '$memid' and (fee_amount-fee_paid) != 0");

}

function delete_cats($memid) {

 global $db, $linkid;

 dbWrite("update mem_categories set category = '0' where memid = '$memid'");
 dbWrite("update members set t_unlist = 0, bdriven = 'N' where memid = '$memid'");

}

function write_off_debt($memid_from,$memid_to,$date,$amount,$details) {

 global $db, $linkid;

 $t = mktime();
 $t2 = $t-951500000;
 $t3 = mt_rand(1000,9000);
 $authno = $t2-$t3;

 $array1 = explode("-", $date);
 $day = $array1[2];
 $month = $array1[1];
 $year = $array1[0];
 $disdate = "$year-$month-$day";
 $epoch = mktime(0,0,1,$month,$day,$year);

 dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked) values ('$memid_from','$epoch','$memid_to','$amount','0','0','0.00','1','".encode_text2($details)."','$authno','$disdate','0')");
 dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked) values ('$memid_to','$epoch','$memid_from','0','$amount','0','0.00','2','".encode_text2($details)."','$authno','$disdate','0')");

}

function write_off_fees($memid_to,$memid_from,$date,$amount,$details) {

 global $db, $linkid;

 $t = mktime();
 $t2 = $t-951500000;
 $t3 = mt_rand(1000,9000);
 $authno = $t2-$t3;

 $array1 = explode("-", $date);
 $day = $array1[2];
 $month = $array1[1];
 $year = $array1[0];
 $disdate = "$year-$month-$day";
 $epoch = mktime(0,0,1,$month,$day,$year);

 //dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked) values ('$memid_to','$epoch','$memid_from','0','0','0','-$amount','5','".encode_text2($details)."','$authno','$disdate','0')");
 //dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked) values ('$memid_from','$epoch','$memid_to','0','0','0','$amount','5','".encode_text2($details)."','$authno','$disdate','0')");
 dbWrite("insert into transactions values('$memid_to','$t','$memid_from','0','0','0','-$amount','9','".addslashes(encode_text2($details))."','$authno','$date','','','0','','".$_SESSION['User']['FieldID']."')");

}

function add_pre_fees($memid_to,$memid_from,$date,$amount,$details) {

 global $db, $linkid;

 $t = mktime();
 $t2 = $t-951500000;
 $t3 = mt_rand(1000,9000);
 $authno = $t2-$t3;

 $array1 = explode("-", $date);
 $day = $array1[2];
 $month = $array1[1];
 $year = $array1[0];
 $disdate = "$year-$month-$day";
 $epoch = mktime(0,0,1,$month,$day,$year);

 //dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked) values ('$memid_to','$epoch','$memid_from','0','0','0','-$amount','5','".encode_text2($details)."','$authno','$disdate','0')");
 //dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked) values ('$memid_from','$epoch','$memid_to','0','0','0','$amount','5','".encode_text2($details)."','$authno','$disdate','0')");
 dbWrite("insert into transactions values('$memid_to','$t','$memid_from','0','0','0','$amount','3','".addslashes(encode_text2($details))."','$authno','$date','','','0','','".$_SESSION['User']['FieldID']."')");
 dbWrite("update members set fee_deductions = (fee_deductions - ".$amount.") where memid = '".$memid_to."'");

}
?>
