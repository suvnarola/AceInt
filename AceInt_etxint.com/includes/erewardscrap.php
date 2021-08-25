<?

if(!checkmodule("ViewStatement")) {

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

$thismonth=date("n");
$thisyear=date("Y");

?>
<html>
<body onload="javascript:setFocus('ER','memid');">

<form method="post" action="body.php?page=erewardslot" name="ER">

<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="Heading2">Monthly E Reward Statements.</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Account No.:</b></td>
		<td width="450" bgcolor="#FFFFFF"><input type="text" name="memid" size="10" value="<?= $bb ?>" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Month:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentmonth">
				<option selected value="<?= $thismonth ?>">This Month</option>
				<option>---------</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Year:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="currentyear">
				<option selected value="<?= $thisyear ?>">This Year</option>
				<option>---------</option>
				<option value="2000">2000</option>
				<option value="2001">2001</option>
				<option value="2002">2002</option>
				<option value="2003">2003</option>
				<option value="2004">2004</option>
				<option value="2005">2005</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" class="Heading2">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="Get Statement" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>