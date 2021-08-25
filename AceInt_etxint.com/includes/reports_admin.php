<?

include("class.html.mime.mail.inc");
include("letterscashfees.php");
if(!checkmodule("Letters")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">You are not allowed to use this function.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>

<form method="POST" action="body.php?page=reports_admin&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<?

// Some Setup.

$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_POST);
 echo "</pre>";

}

 $time_start = getmicrotime();
 $tabarray = array('Active','Deactive','Facility','Negative','Status','Licensee','Pat');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Active") {

 active();

} elseif($_GET[tab] == "Deactive") {

 deactive();

} elseif($_GET[tab] == "Facility") {

 facility();

} elseif($_GET[tab] == "Negative") {

 negative();

} elseif($_GET[tab] == "Status") {

 status();

} elseif($_GET[tab] == "Licensee") {

 licensee();

} elseif($_GET[tab] == "Pat") {

 re();

}


?>
</form>

<?

function active() {

 global $time_start;

 $colspan = "12";

 if($_REQUEST['move']) {

  $MemberArray = $_REQUEST['del'];


  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters($MemberArray,'15',$_REQUEST[header]);

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
   $mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Letters - '.$letterno2);


    if(substr_count($MemberArray_temp, ",") > 0) {

      $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {

	 $currentdate = date("Y-m-d");
	 $det = "Overdue Fee Letter 1 Sent";
     //dbWrite("update members set letters = '1' where memid = '$value'");
     //dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");

    }
   echo done;
   die;
  }


if($_REQUEST[search]) {

 //$date = "2001-03-31";
 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

 $date2 = "".$_REQUEST['year']."-".$_REQUEST['month']."-01";
 $date3 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));
 $date4 = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

 if ($_REQUEST[facility] && $_REQUEST[refacility])  {
  $title = "Facility over ".number_format($_REQUEST[facility])." or R/Facility over ".number_format($_REQUEST[refacility])."";
  $sql = "overdraft > ".$_REQUEST[facility]." or reoverdraft > ".$_REQUEST[refacility]."";
 } elseif($_REQUEST[facility])  {
  $title = "Facility over ".number_format($_REQUEST[facility])."";
  $sql = "overdraft > ".$_REQUEST[facility]."";
 } elseif ($_REQUEST[refacility])  {
  $title = "R/Facility over ".number_format($_REQUEST[refacility])."";
  $sql = "reoverdraft > ".$_REQUEST[refacility]."";
 } else {
  $sql = "";
 }

 $sql2 = "";
 if ($_REQUEST[fees])  {
  $title2 = " or O/D Fees over ".number_format($_REQUEST[fees])."";
  $sql2 = " or overduefees > ".$_REQUEST[fees]."";
 }

?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Active Member not Traded Since <?= $date2 ?>and have <?= $title ?><?= $title2 ?></td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" width = "80"><b>Acc No.:</b></td>
     <td class="Heading2" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
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

  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status != '1' and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and (members.memid = invoice.memid) and status != '1' and CID = '1' and invoice.date = '2004-01-31' and (reoverdraft > '5000' or overduefees > '200') group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and (members.memid = invoice.memid) and status != '1' and CID = '1' and invoice.date = '2004-01-31' and (overdraft > '".$_REQUEST[facility]."' or reoverdraft > '".$_REQUEST[refacility]."' or overduefees > '".$_REQUEST[fees]."') group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and (members.memid = invoice.memid) and status != '1' and CID = '1' and invoice.date = '2004-01-31' and ($sql$sql2) group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and (members.memid = invoice.memid) and status != '1' and CID = '1' and invoice.date = '".$date3."' and transactions.dis_date between '".$date2."' and '".$date."' and ($sql$sql2) group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice, transactions where (members.memid = transactions.memid) and (members.memid = invoice.memid) and status != '1' and CID = '1' and invoice.date = '".$date3."' and transactions.dis_date between '".$date2."' and '".$date."' group by transactions.memid order by members.memid ASC");
  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees from members, invoice left outer join transactions on (members.memid = transactions.memid) where (members.memid = invoice.memid) and status not in (1,4) and CID = '1' and invoice.date = '".$date3."' and transactions.dis_date between '".$date2."' and '".$date."' group by transactions.memid order by members.memid ASC");

  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees, overduefees, sum(mem_categories.category) as cat from members, invoice, transactions left outer join feesowing on (members.memid = feesowing.memid) left outer join mem_categories on (members.memid = mem_categories.memid) where (members.memid = transactions.memid) and (members.memid = invoice.memid) and status != '1' and CID = '1' and invoice.date = '2004-01-31' having (sum(mem_categories.category) = 0) group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell from members, transactions where (members.memid = transactions.memid) and members.datejoined  < 2002-03-31 and CID = '1' and transactions.dis_date between '2001-04-30' and '2002-03-31' group by transactions.memid having (sum(transactions.buy) > 0 or sum(transactions.sell) > 0) order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {

     //$query1 = dbRead("select transactions.id from transactions where memid = '".$row['memid']."' and (dis_date between '2003-09-01' and '2004-03-07') and to_memid NOT IN (9845,13698)");
     $query1 = dbRead("select transactions.id from transactions where memid = '".$row['memid']."' and (dis_date between '".$date2."' and '".$date4."') and to_memid NOT IN (16083,10528,10741,14416,16084,9845,13698,10655,14003,15778,12986,10568,9846,10618,13838,9312.26223,16083,26253,25978,26221,26225,26226,26227,26228,26229)");

	 if(mysql_num_rows($query1) == 0)  {

	     $counter = $counter + 1;

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

	     if($row[status] == 6 && $row[letters] == 3)  {
	       $ready = "Y";
	     } else {
	       $ready = "N";
	     }

	     $ctotal+=$claim;
	     $dtotal+=$row[dollarfees];
	     $btotal+=$bal;
	     $ftotal+=$row[overdraft];
	     $rtotal+=$row[reoverdraft];

	     //$query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN (16083,10528,10741,14416,16084,9845,13698,10655,14003,15778,12986,10568,9846,10618,13838,9312.26223,16083,26253,25978,26221,26225,26226,26227,26228,26229) order by dis_date DESC limit 1");
		 $row1 = mysql_fetch_array($query1);

	    ?>
	    <tr bgcolor="<?= $bgcolor ?>">
	      <td width = "80"><?= $row[memid] ?></td>
	      <td  nowrap><?= $row[companyname] ?></td>
	      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
	      <td  nowrap align = "right"><?= $row[numfeesowing] ?></td>
	      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
	      <td  nowrap align = "right"><?= number_format($bal) ?></td>
	      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
	      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
	      <td  nowrap align = "right"><?= number_format($net) ?></td>
	      <td  nowrap align = "right"><?= number_format($claim) ?></td>
	      <td  nowrap align = "right"><?= $ready ?></td>
	      <td align="right"><input type="checkbox" name="del[]" value="<?= $row[memid] ?>"></td>
	    </tr>
		<?

	 	$foo++;
 	}
 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?//if(!checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?//}?>
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
} else {
?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
    <?if ($letterno == 1) {?><tr>
    <td width="100%" colspan="8" align="center" bgcolor="#FFFFFF"></td></tr><?}?>
</table>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Active Member Report <?= $letterno ?></td>
   </tr>
   <tr>
     <td align="right" class="Heading2"><b>Facility Over:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="facility" size="20" onKeyPress="return number(event)"> (min of $1000)</td>
   </tr>
   <tr>
     <td align="right" class="Heading2"><b>Real Estate Facility Over:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="refacility" size="20" onKeyPress="return number(event)"> (min of $1000)</td>
   </tr>
   <tr>
     <td align="right" class="Heading2"><b>Fees Over:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="fees" size="20" onKeyPress="return number(event)"> (min of $50)</td>
   </tr>
	<tr>
		<td align="right" class="Heading2"><b>Not Traded Since:</b></td>
		<td bgcolor="#FFFFFF">
			<select name="month">
				<option <? if ($f == "1") { echo "selected "; } ?>value="01">January</option>
				<option <? if ($f == "2") { echo "selected "; } ?>value="02">February</option>
				<option <? if ($f == "3") { echo "selected "; } ?>value="03">March</option>
				<option <? if ($f == "4") { echo "selected "; } ?>value="04">April</option>
				<option <? if ($f == "5") { echo "selected "; } ?>value="05">May</option>
				<option <? if ($f == "6") { echo "selected "; } ?>value="06">June</option>
				<option <? if ($f == "7") { echo "selected "; } ?>value="07">July</option>
				<option <? if ($f == "8") { echo "selected "; } ?>value="08">August</option>
				<option <? if ($f == "9") { echo "selected "; } ?>value="09">September</option>
				<option <? if ($f == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($f == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($f == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		<?

		$query = get_year_array();
	    form_select('year',$query,'','',date("Y"));

	   	?>
   <tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}

}


function deactive() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[B1]) {

 $trans_date = date("Y-m-d");

  $DelArray = $_POST[del];

  foreach($DelArray as $key => $value) {

 // Set Facility and Real Estate Facility to 0 then run the facility script.
 dbWrite("update members set overdraft='0', reoverdraft='0' where memid = '$value'");
 $memid = $value;
 require("includes/facility.php");

 // Get balance of member after facilities have been zeroed.
 $query = dbRead("select sum(sell)-sum(buy) as Balance, sum(dollarfees) as CashFees from transactions where memid = '$value'");
 $row = mysql_fetch_assoc($query);

 if($row[Balance] != 0) {

  if($row[Balance] > 0) {

   write_off_debt($value,$_SESSION[Country][writeoff],$trans_date,$row[Balance],'Closing Balance Wrote Off');

  } else {

   write_off_debt($_SESSION[Country][writeoff],$value,$trans_date,abs($row[Balance]),'Closing Balance Wrote Off');

  }
 }

 if($row[CashFees] != 0) {

  if($row[CashFees] > 0) {

   write_off_fees($value,$_SESSION[Country][reserveacc],$trans_date,$row[CashFees],'Reversal of Fees');

  }
 }
 }
}

?>

<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Deactive Members</td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b>Acc No.:</b></td>
     <td class="Heading2" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
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

  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status = '1' and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 $counter = $counter + 1;
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
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><?= $row[memid] ?></td>
      <td  nowrap width = "180"><?= $row[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[numfeesowing] ?></td>
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
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td  nowrap align="right"></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Write Off" name="B1"></td>
       </tr>
      </table>
      <?}?>
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

function facility() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[move])  {

  $MemberArray = $_REQUEST['del'];

  // define the text.
   $text = "Dear ".$_SESSION['User']['Name'].",\r\n\r\nAttached is your current Letters.";

  // get the actual taxinvoice ready.
   $buffer = feeletters($MemberArray,'20',$_REQUEST[header]);

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
   $mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts - Head Office', 'accounts@ebanctrade.com', 'Letters - '.$letterno2);

    if(substr_count($MemberArray_temp, ",") > 0) {

     $MemberArray = explode(",", $MemberArray_temp);

    } else {

     $MemberArray[] = $MemberArray_temp;

    }

    foreach($MemberArray as $key => $value) {
     dbWrite("update members set overdraft = '0' where memid = '$value'");

     $memid = $value;
     require("includes/facility.php");
	 $currentdate = date("Y-m-d");
	 $det = "Facility Reduction Letter Sent";
     dbWrite("insert into notes (memid,date,userid,type,note) values ('$value','$currentdate','180','1','$det')");

    }

} elseif($_REQUEST[search]) {

?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Member with funds of <?= number_format($_REQUEST[facility]) ?> above there Facility</td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" width = "80"><b>Acc No.:</b></td>
     <td class="Heading2" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
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

  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status != '1' and CID = '1' and overdraft > '0' group by transactions.memid order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    if(($row[sell] - $row[buy] - $row[overdraft]) > $_REQUEST[facility])  {

     $counter = $counter + 1;

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
     if($net < 0)  {
       $claim = ($net+$row[dollarfees]);
     } else {
       $claim = $row[dollarfees];
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $ftotal+=$row[overdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><?= $row[memid] ?></td>
      <td  nowrap width = "180"><?= $row[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[numfeesowing] ?></td>
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

 	}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td align="right"></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF" align="right">No. of Members: <?= $counter ?> <input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
      <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
    </tr
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(!checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?}?>
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
} else {
?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
    <?if ($letterno == 1) {?><tr>
    <td width="100%" colspan="8" align="center" bgcolor="#FFFFFF"></td></tr><?}?>
</table>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Member with Facility Report <?= $letterno ?></td>
   </tr>
   <tr>
     <td align="right" class="Heading2"><b>Facility Over:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="facility" size="20" onKeyPress="return number(event)"> (min of $1000)</td>
   </tr>
   <tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}

}

function licensee() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[search]) {

?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Member with funds of <?= number_format($_REQUEST[facility]) ?> above there Facility</td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" width = "180"><b>Licensee:</b></td>
     <td class="Heading2" nowrap><b>G/S Fees Gen:</b></td>
     <td class="Heading2" nowrap align = "right"><b>G/S Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>G/S Buys:</b></td>
     <td class="Heading2" nowrap align = "right"><b>G/S Sells:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/E Fees Gen:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/E Fees Paid:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/E Buys:</b></td>
     <td class="Heading2" nowrap align = "right"><b>R/E Sells:</b></td>
     <td class="Heading2" nowrap align = "right"><b>No. of Trans:</b></td>
     <td align="right" class="Heading2"><b>DEL:</b></td>
   </tr>
<?

  $foo = 0;

  $query = dbRead("select * from monthly_licensee, area where (monthly_licensee.licensee = area.FieldID) and month = '200401' and CID = 1 order by area.place ASC");
  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    //if(($row[sell] - $row[buy] - $row[overdraft]) > $_REQUEST[facility])  {

     $counter = $counter + 1;

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
     if($net < 0)  {
       $claim = ($net+$row[dollarfees]);
     } else {
       $claim = $row[dollarfees];
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $ftotal+=$row[overdraft];

     //$query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 //$row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td  nowrap width = "180"><?= $row[place] ?></td>
      <td width = "80"><?= number_format($row[gs_fees],2) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($row[gs_buy],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[gs_sell],2) ?></td>
      <td  nowrap align = "right"><?= number_format($row[gs_trans]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= $row[trans] ?></td>
      <td align="right"><input type="checkbox" name="del[]" value="<?= $row[memid] ?>"></td>
    </tr>
<?

 	$foo++;

 	//}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td align="right"><input type="checkbox" name="del[]" value="<?= $row[memid] ?>"></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(!checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?}?>
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
} else {
?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
    <?if ($letterno == 1) {?><tr>
    <td width="100%" colspan="8" align="center" bgcolor="#FFFFFF"></td></tr><?}?>
</table>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Member with Facility Report <?= $letterno ?></td>
   </tr>
   <tr>
     <td align="right" class="Heading2"><b>Facility Over:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="facility" size="20" onKeyPress="return number(event)"> (min of $1000)</td>
   </tr>
   <tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}

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

 dbWrite("insert into transactions values('$memid_to','$t','$memid_from','0','0','0','-$amount','9','".addslashes(encode_text2($details))."','$authno','$date','0','','".$_SESSION['User']['FieldID']."')");

}

function negative() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[B1]) {

 $trans_date = date("Y-m-d");

  $DelArray = $_POST[del];

  foreach($DelArray as $key => $value) {

 // Set Facility and Real Estate Facility to 0 then run the facility script.
 dbWrite("update members set overdraft='0', reoverdraft='0' where memid = '$value'");
 $memid = $value;
 require("includes/facility.php");

 // Get balance of member after facilities have been zeroed.
 $query = dbRead("select sum(sell)-sum(buy) as Balance, sum(dollarfees) as CashFees from transactions where memid = '$value'");
 $row = mysql_fetch_assoc($query);

 if($row[Balance] != 0) {

  if($row[Balance] > 0) {

   write_off_debt($value,'".$_SESSION[Country][writeoff]."',$trans_date,$row[Balance],'Closing Balance Wrote Off');

  } else {

   write_off_debt('".$_SESSION[Country][writeoff]."',$value,$trans_date,abs($row[Balance]),'Closing Balance Wrote Off');

  }
 }

 if($row[CashFees] != 0) {

  if($row[CashFees] > 0) {

   write_off_fees($value,'".$_SESSION[Country][reserveacc]."',$trans_date,$row[CashFees],'Reversal of Fees');

  }
 }
 }
}

?>

<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Active Members with Negative Balance</td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b>Acc No.:</b></td>
     <td class="Heading2" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
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

  $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status != '1' and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    if(($row[sell]-$row[buy]) < 0)  {

	 $counter = $counter + 1;
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
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><?= $row[memid] ?></td>
      <td  nowrap width = "180"><?= $row[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[numfeesowing] ?></td>
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
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td  nowrap align="right"></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Write Off" name="B1"></td>
       </tr>
      </table>
      <?}?>
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

function status() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[search]) {

 $date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = "".$_REQUEST['year']."-".$_REQUEST['month']."-01";

 if ($_REQUEST[suspended])  {
  $title = "Suspended Members";
 } elseif($_REQUEST[suspended2])  {
  $title = "Suspended & Locked Members ";
 }

 if ($_REQUEST[suspended])  {
  $st = '5';
 } elseif ($_REQUEST[suspended2])  {
  $st = '6';
 }

 if (!$_REQUEST[letter])  {
  $letter = " and members.letters = 0";
 } else {
  $letter = "";
 }

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Active Member not Traded Since <?= $date2 ?>and have <?= $title ?><?= $title2 ?></td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" nowrap width = "50"><b>Acc No.:</b></td>
     <td class="Heading2" width = "180" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>S/Fees:</b></td>
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

  $query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status = '$st' and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

     $counter = $counter + 1;

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

     if($row[status] == 6 && $row[letters] == 3)  {
       $ready = "Y";
     } else {
       $ready = "N";
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td  nowrap width = "50"><?= $row[memid] ?></td>
      <td  nowrap width = "180" ><?= $row[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[fee_deductions] ?></td>
      <td  nowrap align = "right"><?= $row1[dis_date] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[overdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
      <td  nowrap align = "right"><?= number_format($claim) ?></td>
      <td  nowrap align = "right"><?= $ready ?></td>
    </tr>
<?
 	$foo++;
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td  nowrap width = "50"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td align="right"></td>
    </tr>
    <tr>
      <td colspan = "<?= $colspan ?>">
      <?if(!checkmodule("SuperUser")) {?>
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td bgcolor="#FFFFFF" align="right"><input type="checkbox" name="header" value="1"> Include Letter Head <input type="submit" name="move" value="Move to Next"></td>
        <td bgcolor="#FFFFFF" align="right"><a href="?checkall=1" class="nav" onclick="setCheckboxes('frmletters',true); return false;">Check All</a>&nbsp;<input type="submit" value="Delete Selected" name="B1"></td>
       </tr>
      </table>
      <?}?>
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
} else {
?>

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr colspan="2">
     <td colspan="2" align="center" class="Heading">Suspended Active Member Report <?= $letterno ?></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Suspended:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="suspended" value="1"></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Suspended N/Log:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="suspended2" checked value="1"></td>
   </tr>
   <tr>
     <td align="right" width="150" class="Heading2"><b>Include Letter Mem:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="letter" value="1"></td>
   </tr>
   <tr>
     <td width="150" class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF"><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}
}

function re() {

 global $time_start;

 $colspan = "11";

if($_REQUEST[search]) {

?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Member with funds of <?= number_format($_REQUEST[amount]) ?> or above</td>
   </tr>
   <tr>
     <input type="hidden" name="search" value="1">
     <td class="Heading2" width = "80"><b>Acc No.:</b></td>
     <td class="Heading2" nowrap><b>Account Name:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Dollar Fees:</b></td>
     <td class="Heading2" nowrap align = "right"><b>Status:</b></td>
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

  //$query = dbRead("select members.*, area.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, area, transactions where (members.memid = transactions.memid) and (members.licensee = area.FieldID)and status != '1' and members.CID = '1' and members.state = 'qld' group by transactions.memid having (((sell-buy)-(overdraft+reoverdraft)) >= ".$_REQUEST['amount'].") order by members.memid ASC");
  $query = dbRead("select members.*, area.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, area, transactions where (members.memid = transactions.memid) and (members.licensee = area.FieldID)and status != '1' and members.CID = '1' group by transactions.memid having (((sell-buy)-(overdraft+reoverdraft)) >= ".$_REQUEST['amount'].") order by members.memid ASC");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {
   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    if(($row[sell] - $row[buy] - $row[overdraft]) > $_REQUEST[facility])  {

     $counter = $counter + 1;

     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     $bal = ($row[sell]-$row[buy]);
     $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
     if($net < 0)  {
       $claim = ($net+$row[dollarfees]);
     } else {
       $claim = $row[dollarfees];
     }

     $ctotal+=$claim;
     $dtotal+=$row[dollarfees];
     $ftotal+=$row[overdraft];

     $query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."') order by dis_date DESC limit 1");
	 $row1 = mysql_fetch_array($query1);

    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><?= $row[memid] ?></td>
      <td  nowrap width = "180"><?= $row[companyname] ?> (<?= $row[place]?>)</td>
      <td  nowrap align = "right"><?= number_format($row[dollarfees],2) ?></td>
      <td  nowrap align = "right"><?= $row[status] ?></td>
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

 	}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap>No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($ctotal) ?></td>
      <td align="right"></td>
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
} else {
?>
<table border="0" cellspacing="0" width="639" cellpadding="1">
    <?if ($letterno == 1) {?><tr>
    <td width="100%" colspan="8" align="center" bgcolor="#FFFFFF"></td></tr><?}?>
</table>
<table border="0" cellspacing="0" width="639" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="2" align="center" class="Heading">Member with Balance Report <?= $letterno ?></td>
   </tr>
   <tr>
     <td align="right" class="Heading2"><b>Balance Over:</b></td>
     <td bgcolor="#FFFFFF" nowrap><input type="text" name="amount" size="20" onKeyPress="return number(event)"> (min of $1000)</td>
   </tr>
   <tr>
     <td class="Heading2">&nbsp;</td><input type="hidden" name="search" value="1">
     <td bgcolor="#FFFFFF" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<?
}

}

?>