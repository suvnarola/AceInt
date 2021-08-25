<?

if(!checkmodule("Downloads")) {

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
<html>
<body>

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("1") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="body.php?page=dir" class="nav"><?= get_page_data("2") ?></a></li>
	     <li><a href="includes/classifieds.php?list=1" class="nav"><?= get_page_data("3") ?></a></li>
	     <li><a href="includes/classifieds.php?list=1&jack=1" class="nav"><?= get_page_data("3") ?> - Jackie</a></li>
	     <li><a href="includes/realestate.php?list=1" class="nav"><?= get_page_data("4") ?></a></li>
	     <li><a href="includes/newmemberPDF.php?last30=1" class="nav"><?= get_page_data("5") ?></a></li>
	     <li><a href="includes/directory_function.php?list=1" class="nav"><?= get_page_data("6") ?></a></li>
		<br>
			     <li><a href="body.php?page=dir2" class="nav"><?= get_page_data("2") ?></a></li>
	     <li><a href="includes/classifieds2.php" class="nav"><?= get_page_data("3") ?></a></li>

	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

</body>
</html>