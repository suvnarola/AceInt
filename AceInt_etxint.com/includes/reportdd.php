
<body bgcolor='#FFFFFF'>
<p><font face='Verdana, Arial, Helvetica, sans-serif'><b><font size='2'><?= get_page_data("5") ?> <?= $dis_month ?> <?= $_REQUEST['currentyear'] ?>.</font></b></font></p>
<table width='600' border='1' bordercolor='#CCCCCC' cellspacing='0' cellpadding='3'>
	<form method='post' action='payfees.php'>
  <tr bgcolor='#CCCCCC'>
    <td width='60'><font size='1' face='Verdana'><b>Invoice No:</b></font></td>
    <td width='60'><font size='1' face='Verdana'><b><?= get_word("1") ?>:</b></font></td>
    <td width='250'><font size='1' face='Verdana'><b><?= get_word("3") ?>:</b></font></td>
    <td align='right' width='100'><font size='1' face='Verdana'><b><?= get_word("42") ?>:</b></font></td>
  </tr>
<?
 $total = 0;

 $lastmonth = date("Y-m", mktime(1,1,1,date("m")-1,1,date("Y")));
 $thismonth = date("Y-m", mktime(1,1,1,date("m"),1,date("Y")));

 $query = $ebancAdmin->dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 group by invoice.memid");

 while($row = mysql_fetch_assoc($query)) {

  $query3 = $ebancAdmin->dbRead("select sum(dollarfees) as feespaid from transactions where memid='$row[memid]' and dollarfees < 0 and dis_date like '$thismonth-%'");
  $row3 = mysql_fetch_assoc($query3);

  $ChargeAmount = $row[feesowe] + $row3[feespaid];

  $exdate_temp = explode("/", $row[expires]);

  $exdate1 = $exdate_temp[0];
  $exdate2 = $exdate_temp[1];
  $thisyear = date("y");
  $thismonth2 = date("m");

  if(($exdate2 > $thisyear) or (($exdate1 >= $thismonth2) and ($exdate2 == $thisyear))) {

   if($ChargeAmount > 5) {

	?>
	 <tr>
	   <td width='60'><font size='1' face='Verdana'><?= $row['memid'] ?></font>&nbsp;</td>
	   <td width='250'><font size='1' face='Verdana'><?= $row['companyname'] ?></font>&nbsp;</td>
	   <td align='right' width='100'><font size='1' face='Verdana'><?= $_SESSION['Country']['currency'] ?><?= number_format($ChargeAmount,2) ?></font>&nbsp;</td>
	 </tr>
	<?
	$total = $total+$ChargeAmount;
	}
   }

 }
?>
  <tr>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='60'><font size='1' face='Verdana'><b></b></font></td>
    <td width='250' align='right'><font size='1' face='Verdana'><b></b></font></td>
    <td align='right' width='100' bgcolor='#CCCCCC'><font size='1' face='Verdana'><b><?= $_SESSION['Country']['currency'] ?><?= number_format($total,2) ?></b></font>&nbsp;</td>
  </tr>
  </form>
</table>