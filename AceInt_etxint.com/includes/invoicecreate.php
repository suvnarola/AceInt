<html>
<head>
<title>Report - Accounts</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
</head>
<body>
<?
if(!checkmodule("LynReports")) {
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

if($_REQUEST[Client])  {

  $memdbquery = dbRead("select * from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and memid = ".$_REQUEST[Client]." and tbl_members_email.type = 3");
  $memrow = mysql_fetch_assoc($memdbquery);

}
?>

<html>
<head>
<script language="JavaScript" type="text/javascript">


function ConfirmAdd() {
	//if(document.am.view[1].checked) {
		bDelete = confirm("Are you sure you wish to continue and create invoice?");
		if (bDelete) {
			document.am.b1.disabled=true;
			document.am.submit();
		} else {
		    return false;
		}
	//} else {
		document.am.submit();
	//}
}

</script>
</head>
<body>

<form method="POST" action="includes/taxinvoice.php" name="am">
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2">Create Tax Invoice</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600">
	     <table width="100%" border="0" cellpadding="3" cellspacing="0" >
		  <tr>
		   <td width="150" align="right" class="Heading2">Acc No: </td>
		   <td><input type="text" name="memid" value="<?= $memrow[memid] ?>" size="30"></td>
		  </tr>
		  <tr>
		   <td width="150" align="right" class="Heading2">Company Name: </td>
		   <td><?= $memrow[regname] ?></td>
		  </tr>
		  <tr>
		<td width="150" align="right" class="Heading2"><b>Type:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="type">
				<option <? if ($m == "01") { echo "selected "; } ?>value="2">Membership Fee</option>
				<option <? if ($m == "02") { echo "selected "; } ?>value="3">Conversion</option>
				<option <? if ($m == "03") { echo "selected "; } ?>value="4">Other</option>
			</select>
		</td>
		  <tr>
		   <td width="150" align="right" class="Heading2">Description: </td>
		   <td><input type="text" name="desc" size="30" value="<?= $row['desc'] ?>"></td>
		  </tr>
		  <tr>
		   <td width="150" align="right" class="Heading2">Amount: </td>
		   <td><input type="text" name="amount" size="30" value="<?= $row['amount'] ?>"></td>
		  </tr>
		<td width="150" align="right" class="Heading2"><b>Tax:</b></td>
		<td width="450" bgcolor="#FFFFFF">
			<select name="tax">
				<option <? if ($m == "01") { echo "selected "; } ?>value="1">Yes</option>
				<option <? if ($m == "02") { echo "selected "; } ?>value="0">No</option>
			</select>
		</td>
         </table>
        <input id="bb" type="Button" name="b1"  value="Submit" onclick="ConfirmAdd();">
	 </td>
	</tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="invoice" value="1">
<input type="hidden" name="Client" value="<?= $memrow[memid] ?>">
<input type="hidden" name="email" value="<?= $memrow[email] ?>">
<input type="hidden" name="ChangeMargin" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
</td>
</tr>
</table>

</form>

</body>
</html>
