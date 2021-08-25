<?

function fee_payment_cash() {

 ?>
 <table width="639" border="0" cellpadding="1" cellspacing="0">
 <tr>
 <td class="Border">
 <table width="100%" cellspacing="0" cellpadding="3">
  <tr> 
    <td width="80" class="Heading2"><font size="1" face="Verdana"><b>Account No.:</b></font></td>
    <td width="190" class="Heading2"><font size="1" face="Verdana"><b>Name:</b></font></td>
    <td width="120" class="Heading2"><font size="1" face="Verdana"><b>Details:</b></font></td>
    <td align="right" width="120" class="Heading2"><font size="1" face="Verdana"><b>Fees Due:</b></font></td>
    <td align="right" width="120" class="Heading2"><font size="1" face="Verdana"><b>Amt Paid:</b></font></td>
  </tr>
 <?

 $dbtq = dbRead("select memid, sum(dollarfees) as feesowe from transactions where memid='".$_REQUEST['Client']."' group by memid");
 while($row = mysql_fetch_assoc($dbtq)) {

 $dbgetcomname = dbRead("select companyname, licensee from members where memid = '".$row['memid']."'");
 list($companyname, $licensee) = mysql_fetch_row($dbgetcomname);

  ?>
	  <tr> 
		<input type="hidden" name="memberacc" value="<?= $row['memid'] ?>">
	    <td width="80" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= $row['memid'] ?></font>&nbsp;</td>
	    <td width="190" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= $companyname ?></font>&nbsp;</td>
	    <td width="120" bgcolor="#DDDDDD"><font size="1" face="Verdana">Cash Fees Payment</font></td>
	    <td align="right" width="120" bgcolor="#DDDDDD"><font size="1" face="Verdana"><?= $_SESSION['Country']['currency'] ?><?= number_format($row['feesowe'],2) ?></font></td>
		<td align="right" width="120" bgcolor="#DDDDDD"><font size="1" face="Verdana"><input size="12" type="text" name="amount" onKeyPress="return number(event)"></font></td>
		<input type="hidden" value="Cash Fees Payment" name="det">
		<input type="hidden" value="<?= $licensee ?>" name="licensee">		
	  </tr>
  <?

 }

 ?>
  <tr> 
    <td colspan="5" align="right" bgcolor="#FFFFFF"><?if(checkmodule("SuperUser")) {?><input type="checkbox" name="trade" value="1"> Paid in Trade <?}?><input type="submit" value="Pay Fees." name="fees"></td>
  </tr>

 </table>
 </td>
 </tr>
 </table>

 <input type="hidden" value="1" name="Next">
 <?

}

?>