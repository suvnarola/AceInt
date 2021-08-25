<?

if(!checkmodule("AddMember")) {

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

?>
<html>
<script LANGUAGE="JavaScript">
<!--

function ConfirmUpdate() {
	bDelete = confirm("Are you sure you wish to Update New Members??");
	if (bDelete) {
		document.updatenewmembers.submit();
	} else {
	    return false;
	}
}
//-->
</script>
<body>

<form method="POST" action="/general.php?updatenewmembers=true" name="updatenewmembers">

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<? if($_REQUEST['sent']) {?>
	<tr>
		<td align="center" class="Heading2">Email has been sent to Reception</td>
	</tr>
	<?}?>	
	<tr>
		<td align="center" class="Heading2"><?= get_word("185") ?>.</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="/includes/membersdetailspack.php" class="nav"><?= get_page_data("3") ?></a></li>
	     <li><a href="/includes/membersdetailsfile.php" class="nav"><?= get_page_data("4") ?></a></li>
	     <li><a href="/includes/printcheque.php?new=1" class="nav"><?= get_page_data("5") ?></a></li>
	     <li><a href="/includes/newmemberPDF.php?new=1&webDisplay=1" class="nav"><?= get_word("185") ?></a></li>
	     <li><a href="/includes/labels.php" class="nav"><?= get_page_data("7") ?></a></li>
		 <?if($_SESSION['User']['CID'] == 1) {?> <li><a href="/general.php?updatenewmembersemail=1" class="nav"><?= get_page_data("8") ?></a></li><?}?>	     
	    </ul>
	    <button name="UpdateNewMembers" value="1" onclick="javascript:ConfirmUpdate();"><?= get_page_data("2") ?></button>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

</form>

</body>
</html>