<?

include("taxinvoiceky.php");
include("class.html.mime.mail.inc");
$f = date("n")-1;
$g = date("Y");

if($f == 0) {
 $f = 12;
 $g = date("Y")-1;
}

add_kpi("62", $_REQUEST['memid']);

if($_REQUEST[view] && $gg) {

 $invoice_date=date("Y-m", mktime(1,1,1,date("n")-1,1,date("Y")));

 $query = dbRead("select * from invoice, members, country where invoice.memid=members.memid and members.CID=country.countryID and invoice.memid = '$_REQUEST[memid]' and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%'");

 if (@mysql_num_rows($query) > 0) {

   dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST[memid]."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Tax Invoice Printed')");

   taxinvoice();

 } else {

   echo "No Tax Invoice Available";
 }

die;
}

if($_REQUEST[send] && $ff)  {


 // Tax Invoice Run.
 $invoice_date=date("Y-m", mktime(1,1,1,date("n")-1,1,date("Y")));
 $display_date=date("F, Y", mktime(1,1,1,date("n")-1,1,date("Y")));

 //do emails for the individual countries first.
 //$query2 = dbRead("select members.memid as memid, members.companyname as companyname, members.email_accounts as emailaddress, members.contactname as contactname, members.CID as CID from invoice, members where invoice.memid=members.memid and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.memid = '$_REQUEST[memid]'");
 $query2 = dbRead("select members.memid as memid, members.companyname as companyname, tbl_members_email.email as emailaddress, members.contactname as contactname, members.CID as CID from invoice, members, tbl_members_email where invoice.memid=members.memid and (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and invoice.date like '$_REQUEST[currentyear]-$_REQUEST[currentmonth]-%' and members.memid = '$_REQUEST[memid]'");

 while($row2 = mysql_fetch_assoc($query2)) {

  if (!empty($row2['emailaddress'])) {

    $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '".$row2['CID']."'"));

    // define the text.
    $text = get_html_template($row2['CID'],$row2['contactname'],$CountryDataRow['emtax']);

    // get the actual taxinvoice ready.
    //$buffer = taxinvoice($row2[memid],$invoice_date,'');
    $buffer = taxinvoice();

    // define carriage returns for macs and pc's
    define('CRLF', "\r\n", TRUE);

    // create a new mail instance
    $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

    // add the text in.
    $mail->add_html($text);

    // add the attachment on.
    $mail->add_attachment($buffer, 'taxinvoice.pdf', 'application/pdf');

    // build the message.
    $mail->build_message();

    // send the message.
    $mail->send($row2[contactname], $row2[emailaddress], 'E Banc Accounts', 'accounts@ebanctrade.com', 'Tax Invoice - '.$row2[companyname],'Bcc: dave@ebanctrade.com');

    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST[memid]."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Tax Invoice Emailed -".$_REQUEST['currentyear']."-".$_REQUEST['currentmonth']."')");

    echo "Tax Invoice has been email to $row2[emailaddress]";
   } else {

   echo "No email available";

   }
 }
die;
}
?>

<html>
<body onload="javascript:setFocus('TSel','memid');">

<form method="post" action="../includes/taxinvoice.php" name="TSel">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("177") ?></td>
	</tr>
	</tr>
		<td class="Heading2" width="150" align="right">
			<b><?= get_word("50") ?>:</b>
			<br>
			<small>(<?= get_word("241") ?>)</small>
		
		</td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="memid" value="<?= $_REQUEST['memid'] ?>"></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
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
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',$g);

	   	?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("40") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_month_array();
            form_select('numbermonths',$query,'','',$g,'None');

           ?>
		</td>
	</tr>
	<tr>
	    <td width="150" align="right" class="Heading2"><b><?= get_word("84") ?>:</b></td>
	    <td align="Left" bgcolor="#FFFFFF"><input type="checkbox" name="stationery" value="1"></td>
	</tr>
	<tr>
		<td width="150" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="<?= get_word("183") ?>" name="view" style="size: 8pt">
        <input type="submit" value="<?= get_word("180") ?>" name="send" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="individual" value="1">
</form>

</body>
</html>
