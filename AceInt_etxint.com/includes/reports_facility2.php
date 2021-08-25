<?

include("letterscashfees.php");
include("taxinvoiceky.php");
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

<form method="POST" action="body.php?page=reports_facility2&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_REQUEST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array("Unused Facilities","Facilities Owing","RE Facility Owing","RE Rollover Invoice","RE Facility Birthday",get_page_data("5"));

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

 update('9');
 unused('1','1');
 //letters('9');

} elseif($_GET[tab] == "tab2") {

 update('1');
 unused('1','2');

} elseif($_GET[tab] == "tab3") {

 update('2');
 unused('2','2');

} elseif($_GET[tab] == "tab4") {

 //update('3');
 re_inv();

} elseif($_GET[tab] == "tab5") {

 //update('4');
 unused('5','2');

} elseif($_GET[tab] == "tab6") {

 update('4');
 letters('4');

}

?>

</form>
<?
function re_inv() {

	$colspan = "10";
	?>

	<form method="POST" action="body.php?page=reports_facility2&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

	<table border="0" cellspacing="0" width="620" cellpadding="1">
	 <tr>
	  <td class="Border">
	  <table border="0" cellspacing="0" width="100%" cellpadding="3">
	   <tr>
	    <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Real Estate Rollover Invoices Owing</td>
	   </tr>
	   <tr>
		<td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
		<td class="Heading2"><b><?= get_word("62") ?>:</b></td>
		<td class="Heading2" align = "right"><b>:</b></td>
		<td class="Heading2" align = "right"><b>:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
		<td class="Heading2" align = "right"><b></b></td>
	   </tr>
	<?

		$foo = 0;

		$query = dbRead("
		SELECT *

		FROM invoice_re INNER JOIN members ON

		invoice_re.memid = members.memid

		WHERE (

		amount-payment > 0
		AND members.status Not In (1,3)
		AND members.CID = '".$_SESSION['User']['CID']."')

		order by date
		");

		//AND tbl_members_facility.date > '2005-09-22';
		if(mysql_num_rows($query) == 0) {

			?>
			<tr bgcolor="#FFFFFF">
			  <td colspan="<?= $colspan ?>" align="center"><br>No Invoices Owing.<br><br></td>
			</tr>
			<?

		} else {

			$counter = 0;
			$ftotal = 0;
			while($row = mysql_fetch_array($query)) {

				$cfgbgcolorone="#CCCCCC";
				$cfgbgcolortwo="#EEEEEE";

				$bgcolor=$cfgbgcolorone;
				$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

					?>
					<tr bgcolor="<?= $bgcolor ?>">
					 <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
					 <td  nowrap width = "180"><?= $row[companyname] ?></td>
					 <td  nowrap align = "right"><?= $row[date] ?></td>
					 <td  nowrap align = "center"><?= $row[status] ?></td>
					 <td  nowrap align = "right"><?= number_format($bal) ?></td>
					 <td  nowrap align = "right"><?= number_format($row[Expr1]) ?></td>
					 <td  nowrap align = "right"><?= number_format($row['amount']) ?></td>
					 <td  nowrap align = "right"><?= number_format($row['payment']) ?></td>
      				 <td align="right"><input type="checkbox" name="del[]" value="<?= $row[memid] ?>"></td>
					</tr>
					<?
					$dtotal+=$row[dollarfees];
					$btotal+=$row['amount'];
					$ftotal+=$row[Expr1];
					$rtotal+=$row[reoverdraft];
					$foo++;

			}
		}
		?>

	   <tr bgcolor="#FFFFFF">
		<td width = "80"></td>
		<td  nowrap width = "180">No of Members = <?= $counter ?></td>
		<td  nowrap align = "right"><?= number_format($dtotal) ?></td>
		<td  nowrap align = "right"></td>
		<td  nowrap align = "right"><?= number_format($btotal) ?></td>
		<td  nowrap align = "right"></td>
		<td  nowrap align = "right"><?= number_format($btotal) ?></td>
		<td  nowrap align = "right"><?= number_format($rtotal) ?></td>
		<td  nowrap align = "right"></td>
		<td  nowrap align = "right"></td>
	  </tr>
	  <tr>
	   <td colspan="<?= $colspan ?>">
	   <table border="0" cellspacing="0" width="100%" cellpadding="0">
        <tr>
         <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
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


function unused($type = false,$pos = false) {

	$colspan = "10";
	?>

	<form method="POST" action="body.php?page=reports_facility2&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

	<table border="0" cellspacing="0" width="620" cellpadding="1">
	 <tr>
	  <td class="Border">
	  <table border="0" cellspacing="0" width="100%" cellpadding="3">
	   <tr>
	    <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Unused GS Facility</td>
	   </tr>
	   <tr>
		<td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
		<td class="Heading2"><b><?= get_word("62") ?>:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
		<td class="Heading2" align = "right"><b>L/Traded:</b></td>
		<td class="Heading2" align = "right"><b>Balance:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
		<td class="Heading2" align = "right"><b>Facility Year:</b></td>
		<td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
		<td class="Heading2" align = "right"><b></b></td>
	   </tr>
	<?

		$foo = 0;

		$area2 = " and licensee = ".$_REQUEST['area']."";


		if($type == 2) {
			$pp = " tbl_members_facility.facility_type = 2";
			//$todate = date("Y-m-d", mktime(0,0,1,date("m"),1,date("Y")-$_SESSION['Country']['facility_renewal']));
			$todate = date("Y-m-d", mktime(0,0,1,date("m"),1,date("Y")-5));
		} elseif($type == 5) {
			$pp = " tbl_members_facility.facility_type = 2 and tbl_members_facility.date like '%-".date("m")."-%'";
			$todate = date("Y-m-d")."' and tbl_members_facility.date > '".date("Y-m-d", mktime(0,0,0,date("m")+1,1,date("Y")-5));
		} else {
			$pp = " tbl_members_facility.facility_type = 1";
			$todate = date("Y-m-d", mktime(0,0,1,date("m"),1,date("Y")-$_SESSION['Country']['facility_renewal']));
		}

		if($type == 2) {

			$query = dbRead("
			SELECT tbl_members_facility.acc_no as memid,

			facility_amount-facility_repay AS Expr1, tbl_members_facility.date, tbl_members_facility.FieldID, members.overdraft

			FROM tbl_members_facility INNER JOIN members ON

			tbl_members_facility.acc_no = members.memid

			LEFT OUTER JOIN invoice_re on (tbl_members_facility.FieldID = invoice_re.facid)

			WHERE (

			$pp
			and facility_amount-facility_repay > 0
			AND tbl_members_facility.date < '".$todate."'
			and invoice_re.FieldID is null
			AND members.status Not In (1,3)
			AND members.CID = '".$_SESSION['User']['CID']."')

			order by tbl_members_facility.date DESC, members.memid ASC
			");

		} else {

			$query = dbRead("
			SELECT tbl_members_facility.acc_no as memid,

			facility_amount-facility_repay AS Expr1, tbl_members_facility.date, tbl_members_facility.FieldID, members.overdraft, tbl_members_facility.acc_no as FieldID

			FROM tbl_members_facility INNER JOIN members ON

			tbl_members_facility.acc_no = members.memid

			LEFT OUTER JOIN invoice_re on (tbl_members_facility.FieldID = invoice_re.facid)

			WHERE (

			$pp
			and facility_amount-facility_repay > 0
			AND tbl_members_facility.date < '".$todate."'
			and invoice_re.FieldID is null
			AND members.status Not In (1,3)
			AND members.CID = '".$_SESSION['User']['CID']."')

			order by tbl_members_facility.date DESC, members.memid ASC
			");

		}

		//AND tbl_members_facility.date > '2005-09-22';
		if(mysql_num_rows($query) == 0) {

			?>
			<tr bgcolor="#FFFFFF">
			  <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
			</tr>
			<?

		} else {

			$counter = 0;
			$ftotal = 0;
			while($row = mysql_fetch_array($query)) {

				$cfgbgcolorone="#CCCCCC";
				$cfgbgcolortwo="#EEEEEE";

				$bgcolor=$cfgbgcolorone;
				$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

				$query1 = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees
				from members, transactions
				where (members.memid = transactions.memid) and members.memid = ".$row['memid']."
				group by transactions.memid
				");

				$row1 = mysql_fetch_array($query1);

				$bal = ($row1[sell]-$row1[buy]);
				$net = (($row1[sell]-$row1[buy])-($row[Expr1])-$row[overdraft]);
				$year = explode("-", $row[date]);

				//if($net < 0 || $net > 500) {
				if($pos == 2 && $net < 0 || $pos == 1 && $net > 500) {
					$counter = $counter + 1;
					?>
					<tr bgcolor="<?= $bgcolor ?>">
					 <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
					 <td  nowrap width = "180"><?= $row1[companyname] ?></td>
					 <td  nowrap align = "right"><?= number_format($row1[dollarfees],2) ?></td>
					 <td  nowrap align = "center"><?= $row1[status] ?></td>
					 <td  nowrap align = "right"><?= $row[date] ?></td>
					 <td  nowrap align = "right"><?= number_format($bal) ?></td>
					 <td  nowrap align = "right"><?= number_format($row[Expr1]) ?></td>
					 <td  nowrap align = "right"><?= $year[0] ?></td>
					 <td  nowrap align = "right"><?= number_format($net) ?></td>
      				 <td align="right"><input type="checkbox" name="del[]" value="<?= $row[FieldID] ?>"></td>
					</tr>
					<?
					$dtotal+=$row[dollarfees];
					$btotal+=$bal;
					$ftotal+=$row[Expr1];
					$rtotal+=$row[reoverdraft];
					$foo++;
				}
			}
		}
		?>

	   <tr bgcolor="#FFFFFF">
		<td width = "80"></td>
		<td  nowrap width = "180">No of Members = <?= $counter ?></td>
		<td  nowrap align = "right"><?= number_format($dtotal) ?></td>
		<td  nowrap align = "right"></td>
		<td  nowrap align = "right"></td>
		<td  nowrap align = "right"><?= number_format($btotal) ?></td>
		<td  nowrap align = "right"><?= number_format($ftotal) ?></td>
		<td  nowrap align = "right"><?= number_format($rtotal) ?></td>
		<td  nowrap align = "right"></td>
		<td  nowrap align = "right"></td>
	  </tr>
	  <tr>
	   <td colspan="<?= $colspan ?>">
	   <table border="0" cellspacing="0" width="100%" cellpadding="0">
        <tr>
         <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
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
  $letterno2=$letterno+1;


   if($letterno == 9) {

  // define the text.
    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current unused facility Letters.";
    $buffer = feeletters($MemberArray,'20',$_REQUEST[header]);
    unset($attachArray);
    unset($addressArray);
   	$attachArray[] = array($buffer, 'facilityletters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Unused Facility Letters', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "Unused Facility Removed as Expired";
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");

	 $am = facRemove($value);
	 $memid = $value;

	 dbWrite("update members set overdraft = overdraft-".$am." where memid = ".$value."");
	 require("includes/facility.php");
	 //require("includes/facility2.php");

//print "".$value.", ".$row3['Expr1'].", ". $am;

    }

   } elseif($letterno == 1) {


    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Expired facility Letters.";
    $buffer = feeletters($MemberArray,'40',$_REQUEST[header]);
    unset($attachArray);
    unset($addressArray);
   	$attachArray[] = array($buffer, 'facilityletters.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'Unused Facility Letters', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
     $rem = date("Y-m-d", mktime(1,1,1,date("m"),date("d")+14,date("Y")));
	 $det = "Facility Expired Letter Send";
     dbWrite("insert into notes (memid,date,userid,type,reminder,note,reminder) values ('$value','$currentdate','".$_SESSION['User']['FieldID']."','1','$rem','$det')");

    }

   } elseif ($letterno == 2) {


	if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    $count = 0;
    foreach($MemberArray as $key => $value) {

	 $memberSQL = dbRead("select * from tbl_members_facility where FieldID = '$value'");
	 $memberRow = mysql_fetch_assoc($memberSQL);

	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $remin = date("d-m-Y", mktime(1,1,1,date("m")+1,date("d"),date("Y")));
	 $det = "Real Estate Rollover Letter and Invoice Sent";
	 $det2 = "Real Estate Rollover Fee";
	 $fe = re_rollover_fee($value,1,1);

	 $authno=mt_rand(1000000,99999999);
	 $t=mktime();
 	 $d=date("Y-m-d");

	 if($value > 0) {

	  $buyid = dbWrite("insert into transactions values('".$memberRow['acc_no']."','$t','".$_SESSION['Country'][rereserve]."','0','0','0','".$fe."','3','".addslashes(encode_text2($det2))."','$authno','$d','','','0','','".$_SESSION['User']['FieldID']."')", "etradebanc", true);
      $invid = dbWrite("insert into invoice_re (memid,date,amount,transid,facid) values ('".$memberRow['acc_no']."','$currentdate','$fe','$buyid','$value')","etradebanc",true);
      dbWrite("insert into notes (memid,date,userid,type,note,reminder) values ('".$memberRow['acc_no']."','$currentdate','180','1','$det','$remin')");

	  if($count == 0) {
	   $andor = "";
	  } else {
	   $andor = ",";
	  }
	  $listinv .= $andor."".$invid;
	  $MemArray[] = $memberRow['acc_no'];
	  $count++;

	 }

    }

 	$query = dbRead("select status.*, invoice_re.*, members.*, country.*, countrydata.*, members.abn as abn2, invoice_re.amount as currentfees

	from invoice_re

		inner
			join
				members
				on invoice_re.memid=members.memid
		inner
			join
				`status`
				on members.status = status.FieldID
		inner
			join
				country
				on members.CID=country.countryID
		inner
			join
				countrydata
				on members.CID = countrydata.CID

	where
		invoice_re.FieldID in ($listinv)

	order by companyname");

    // define the text.
    $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Rollover Letters.";

    // get the actual taxinvoice ready.
    $buffer = feeletters($MemArray,'37',$_REQUEST[header]);
    $buffer2 = taxinvoice($query,true,'',true,true);

     unset($attachArray);
     unset($addressArray);

   	$attachArray[] = array($buffer, 'letters.pdf', 'base64', 'application/pdf');
   	$attachArray[] = array($buffer2, 'invoices.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim($_SESSION['User']['EmailAddress']), $_SESSION['User']['Name']);

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), 'RE Rollover Letters', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);


   } elseif ($letterno == 3) {

    foreach($MemberArray as $key => $value) {
     dbWrite("update members set letters = '4' where memid = '$value'");
     dbWrite("update members set status = '6' where memid = '$value'");
	 $currentdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
	 $det = "Sent to Solicitor";
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");
    }

   }

  }

}


?>