<?

if(!checkmodule("EditMemberLevel2") && !checkmodule("ClasCheck") && $_SESSION['User']['AreasAllowed'] != 'all' && $rr) {

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

?>
<?

if($_REQUEST[letter])  {

  $dbquery = dbRead("select * from standard_letters where letter_no = ".$_REQUEST[letter]." and CID = ".$_SESSION['User']['CID']."","ebanc_letters");
  $row = mysql_fetch_assoc($dbquery);

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
	if(document.am.view[1].checked) {
		bDelete = confirm("Are you sure you wish to continue and save this communication?");
		if (bDelete) {
			document.am.submit();
		} else {
		    return false;
		}
	} else {
		document.am.submit();
	}
}

</script>
</head>
<body>

<form method="POST" action="includes/lettersend.php" name="am">
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2"><?= get_page_data("1") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
        <p>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600">
	     <table width="100%" border="0" cellpadding="3" cellspacing="0" >
		  <tr>
		   <td width = "10" align = "right"><?= get_page_data("2") ?>: </td>
		   <td><input type="text" name="to" value="<?= get_all_added_characters($memrow[accholder_first]) ?>" size="30"></td>
		  </tr>
		  <tr>
		   <td align = "right"><?= get_word("41") ?>: </td>
		   <td><input type="text" name="date" value="<?= date("d F Y") ?>" size="30"></td>
		  </tr>
		  <tr>
		   <td align = "right"><?= get_page_data("3") ?>: </td>
		   <td><input type="text" name="subject" size="30" value="<?= get_all_added_characters($row['title']) ?>"></td>
		  </tr>
         </table>
        	Enter Text:<br>
        <?
        ob_start();
        eval(" ?>".$row['letter']."<? ");
        $output = ob_get_contents();
	    ob_end_clean();
        ?>

        <textarea rows="25" name="send_text" cols="74"><?= get_all_added_characters($output) ?></textarea><p>



		<b>Add Attachments</b>

	     <table width="100%" border="0" cellpadding="3" cellspacing="0" >
		  <tr>
		   <td><input type="checkbox" name="att[]" value="pdf/D026-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/D026-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Facility</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/D021-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/D021-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">ITT</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/D035-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/D035-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Direct Debit</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/D037-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/D037-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Additional Sign</a></td>
		  </tr>
		  <tr>
		   <td><input type="checkbox" name="att[]" value="pdf/D059-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/D059-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Joint Account Request</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/close_account-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/close_account-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Close of Account</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/sold_business-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/sold_business-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Sale of Business</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/change_account-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/change_account-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Change of Account</a></td>
		  </tr>
		  <tr>
		   <td><input type="checkbox" name="att[]" value="cs/busexp-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/busexp-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Business Expense Checklist</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/application_50-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/application_50-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">50% Plus Club Application</a></td>
		   <td><input type="checkbox" name="att[]" value="pdf/application_gold-<?= $_SESSION['Country']['countrycode']?>.pdf"> <a href="/downloads/pdf/application_gold-<?= $_SESSION['Country']['countrycode']?>.pdf" target="_blank">Gold Club Application</a></td>
		  </tr>

		  <tr>
		   <td><input type="checkbox" name="clas" value="1"> Classifieds</td>
		   <td><input type="checkbox" name="re" value="1"> Real Estate</td>
		  </tr>
         </table>

		<br>
		<hr>
        <input type="radio" name="type" value="1" checked> <?= get_page_data("4") ?>&nbsp;<input type="radio" name="type" value="2"> <?= get_page_data("5") ?><br>
	    <input type="radio" name="view" value="1" checked> <?= get_page_data("6") ?>&nbsp;<input type="radio" name="view" value="2"> <?= get_page_data("7") ?><br>
        <input type="Button" name="b1"  value="<?= get_word("83") ?>" onclick="ConfirmAdd();"> <?= get_word("84") ?> <input type="checkbox" name="header" value="1">
	 </td>
	</tr>
</table>
</td>
</tr>
</table>
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