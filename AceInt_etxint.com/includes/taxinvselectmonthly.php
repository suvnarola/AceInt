<?

include("taxinvoiceky.php");
include("class.html.mime.mail.inc");
$f = date("n")-1;
$g = date("Y");

if(!checkmodule("PrintTaxInv")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
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

if($_REQUEST[view] && $ff) {

 $invoice_date=date("Y-m", mktime(1,1,1,date("n")-1,1,date("Y")));

   taxinvoice();

die;
}

if($_REQUEST[send] && $rr)  {


 // Tax Invoice Run.
 $invoice_date=date("Y-m", mktime(1,1,1,date("n")-1,1,date("Y")));
 $display_date=date("F, Y", mktime(1,1,1,date("n")-1,1,date("Y")));


    // define the text.
    $text = get_html_template($_SESSION['User']['CID'], $_SESSION['User']['Name'], 'Attached is your Tax Invoices');

    // get the actual taxinvoice ready.
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
    $mail->send($_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], 'E Banc Accounts', 'dave@ebanctrade.com', 'Tax Invoice - '.$row2[companyname],'Bcc: dave@ebanctrade.com');

    echo "Tax Invoice has been email to ".$_SESSION['User']['EmailAddress']."";

die;
}
?>


<html>
<body>

<form method="post" action="../includes/taxinvoice.php">

<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_word("177") ?></td>
	</tr>
	<tr>
		<td width="155" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
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
		<td width="155" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
	<tr>
	    <td width="155" align="right" class="Heading2"><b><?= get_word("84") ?>:</b></td>
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
<input type="hidden" name="monthly" value="1">

</form>

</body>
</html>
