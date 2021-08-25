<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */

$f = date("n")-1;
$g = date("Y");

if($_REQUEST['currentyear'])  {

 if(checkmodule("Log")) {
  add_kpi("47", "0");
 }

$db_date = date("Y-m", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));
$dis_month = date("F", mktime(0,0,0,$_REQUEST['currentmonth'],1,$_REQUEST['currentyear']));

?>

<body bgcolor='#FFFFFF'>
<p><font face='Verdana, Arial, Helvetica, sans-serif'><b><font size='2'><?= get_page_data("5") ?> <?= $dis_month ?> <?= $_REQUEST['currentyear'] ?>.</font></b></font></p>
<table width='600' border='1' bordercolor='#CCCCCC' cellspacing='0' cellpadding='3'>
	<form method='post' action='payfees.php'>
  <tr bgcolor='#CCCCCC'>
    <td width='60'><font size='1' face='Verdana'><b>Invoice No:</b></font></td>
    <td width='80'><font size='1' face='Verdana'><b>Date:</b></font></td>
    <td width='60'><font size='1' face='Verdana'><b><?= get_word("1") ?>:</b></font></td>
    <td width='250'><font size='1' face='Verdana'><b><?= get_word("3") ?>:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b><?= get_word("42") ?>:</b></font></td>
  </tr>
<?
if($_SESSION['User']['CID'] == 15) {
	$sql = "select members.*, invoice_es.* from members, invoice_es where (invoice_es.inv_memid = members.memid) and invoice_es.inv_date like '$db_date-%' and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and members.CID = '".$_SESSION['User']['CID']."'" ;
} else {
	$sql = "select invoice.*, members.* from members, invoice where (invoice.memid = members.memid) and invoice.date like '$db_date-%' and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'" ;
}

//echo "<pre>" . print_r( $sql , true ) . "</pre>" ;

$dbtq = dbRead( $sql );

#$dbtq = dbRead("select invoice.*, members.* from members, invoice where (invoice.memid = members.memid) and invoice.date = '$db_date' and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'");
//$dbtq = dbRead("select invoice.*, members.* from members, invoice where (invoice.memid = members.memid) and invoice.date = '$db_date' and currentfees > '0' and members.CID = '".$_SESSION['User']['CID']."'");


	while($row = mysql_fetch_assoc($dbtq)) {

		/*
		 *
		 * Array
(
    [FieldID] => 2280094
    [date] => 2016-09-30
    [memid] => 12948
    [currentfees] => 16.50
    [currentpaid] => 0.00
    [overduefees] => 424.90
    [sent] => 0
    [memusername] => gcchageo
    [mempassword] => Abcd123
    [Card_No] => 0
    [Card_Exp] =>
    [Terminal_No] =>
    [status] => 5
    [companyname] => Chalk Art By Huppy
    [regname] => Chalk Art By Huppy
    [accholder] => Geoffrey Hupfeld
    [accholder_first] => Geoffrey
    [accholder_surname] => Hupfeld
    [DOBholder] => 0000-00-00
    [contactname] => Geoffrey Hupfeld
    [DOBcontact] => 0000-00-00
    [signatories] =>
    [displayname] => companyname
    [abn] => 0
    [gst] => N
    [streetno] => 85
    [streetname] => Tallai Road
    [state] => Qld
    [postcode] => 4213
    [homestreetno] =>
    [homestreetname] =>
    [homestate] =>
    [homepostcode] =>
    [homecity] =>
    [homesuburb] =>
    [postalno] => 85
    [postalname] => Tallai Road
    [postalstate] => Qld
    [postalpostcode] => 4213
    [phonearea] => 07
    [phoneno] => 5530 5569
    [faxarea] =>
    [faxno] =>
    [mobile] => 0412 306 991
    [emailaddressold] => barter@chalkart.com.au
    [email_accounts] =>
    [webpageurl] => www.chalkart.com.au
    [sms] =>
    [bdriven] => N
    [t_unlist] => 0
    [sponcat] => 0
    [paymenttype] => 0
    [accountname] =>
    [accountno] =>
    [expires] =>
    [erewards] => 0
    [reward_bsb] =>
    [reward_accno] => 0
    [reward_accname] =>
    [reward_sponsorship] => 0
    [reward_datejoined] => 2001-08-30
    [reward_check] => 0
    [transfeetrade] => 0.00
    [transfeecash] => 5.00
    [monthlyfeetrade] => 0.00
    [monthlyfeecash] => 0.00
    [referedby] =>
    [refer_name] =>
    [refer_account] =>
    [city] => Tallai
    [postalcity] => Tallai
    [area] => 2
    [salesmanid] => 49
    [salesmanidOLD] => 29
    [membershipfeepaid] => 214.00
    [trade_membership] => 0.00
    [datejoined] => 2001-08-30
    [datepacksent] => 2001-08-30
    [datedeactivated] => 0000-00-00
    [salesmanpaid] =>
    [memshipfeepaytype] => 7
    [banked] => 0000-00-00
    [overdraft] => 0.00
    [maxfacility] => 0.00
    [reoverdraft] => 0.00
    [homephone] => 5530 6995
    [homephonearea] => 07
    [suburb] =>
    [postalsuburb] =>
    [goldcard] => 0
    [alltrades] => 0
    [fiftyclub] => 0
    [respenddown] => 0
    [pin] => 8585
    [cheque_no] => 60
    [fee_deductions] => 114.00
    [over_payment] => 116.92
    [CID] => 1
    [opt] => Y
    [reopt] => Y
    [exopt] => Y
    [direct] => Y
    [feescharge] => Buy
    [licensee] => 2
    [lastedit] => 1317
    [wagesacc] =>
    [locked] => N
    [supply_statement] => 0
    [lastlog] => 1446814163
    [letters] => 3
    [oldcompanyname] =>
    [paid] => Y
    [paid_mem] => N
    [AuctionSuspend] => N
    [feedback_sent] => 1
    [trade_per] => 50
    [date_per] => 2016-02-18
    [accept] => 2
    [last_confirm] => 2015-07-31
    [admin_exempt] => 0
    [itt_exempt] => 0
    [interest] => 0
    [gift] => 3
    [gift_rec] => 1
    [gift_type] => 10302294214093
    [star] => 3
    [prepaid] => 0
    [priority] => 8
    [ac_fees] => 0.00
    [direct_ad] => 0
    [uncon] => N
    [rob] => 0
    [decbalance] => 2235.77
    [paymenttypeBACK] => 0
    [accountnoBACK] =>
    [expiresBACK] =>
    [giftstreetno] => 85
    [giftstreetname] => Tallai Rd
    [giftstate] => Qld
    [giftpostcode] => 4213
    [giftcity] => Tallai
    [giftsuburb] =>
)

		 *
		 */

	$feespaid = number_format(abs($row['feespaid']),2);

	if($_SESSION['User']['CID'] == 15) {
		$no = $row['inv_no'];
	} else {
		$no = $row['FieldID'];
	}

?>
 <tr>
   <td width='60'><font size='1' face='Verdana'><? echo '<a href="includes/taxinvoice.php?invoice=1&cc=1&no='. $no .'&Client='.$transrow[to_memid].'&pagno=1&tab=tab5 " class="nav">'; ?><?= $no ?></a></font>&nbsp;</td>
   <td width='80'><font size='1' face='Verdana'><?= $row['date'] ?></font>&nbsp;</td>
   <td width='60'><font size='1' face='Verdana'><?= $row['memid'] ?></font>&nbsp;</td>
   <td width='250'><font size='1' face='Verdana'><?= $row['companyname'] ?></font>&nbsp;</td>
   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($row['currentfees'],2) ?></font>&nbsp;</td>
 </tr>
<?

$total += $row['inv_amount'];

}

?>
  <tr>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
   <td width='80'><font size='1' face='Verdana'></font>&nbsp;</td>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='250' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($total,2) ?></b></font>&nbsp;</td>
  </tr>
  </form>
</table>
</body>

<?
die;
}
?>

<form method="post" action="body.php?page=invoicereports">

<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?>.</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option <? if ($f == "1") { echo "selected "; } ?>value="1">January</option>
				<option <? if ($f == "2") { echo "selected "; } ?>value="2">February</option>
				<option <? if ($f == "3") { echo "selected "; } ?>value="3">March</option>
				<option <? if ($f == "4") { echo "selected "; } ?>value="4">April</option>
				<option <? if ($f == "5") { echo "selected "; } ?>value="5">May</option>
				<option <? if ($f == "6") { echo "selected "; } ?>value="6">June</option>
				<option <? if ($f == "7") { echo "selected "; } ?>value="7">July</option>
				<option <? if ($f == "8") { echo "selected "; } ?>value="8">August</option>
				<option <? if ($f == "9") { echo "selected "; } ?>value="9">September</option>
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
	    form_select('currentyear',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
	<tr>
		<td width="150" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="<?= get_word("83") ?>" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
