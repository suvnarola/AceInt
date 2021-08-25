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

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("1") ?>.</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="downloads/publications/get_publication.php?file=advantage1&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("2") ?></a></li>
	     <li><a href="downloads/publications/get_publication.php?file=advantage2&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("3") ?></a></li>
	     <li>&nbsp;</li>
	     <li><a href="downloads/publications/get_publication.php?file=trade1&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("4") ?></a></li>
	     <li><a href="downloads/publications/get_publication.php?file=trade2&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("5") ?></a></li>
	     <li>&nbsp;</li>
	     <li><a href="downloads/publications/get_publication.php?file=QA1&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("6") ?></a></li>
	     <li><a href="downloads/publications/get_publication.php?file=QA2&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("7") ?></a></li>
	     <?if($_SESSION['Country']['countryID'] == 3 || $_SESSION['Country']['countryID'] == 10) {?>
	     <li>&nbsp;</li>
	     <li><a href="downloads/publications/dutch1.pdf" class="nav">Q&As Dutch Page 1</a></li>
	     <li><a href="downloads/publications/dutch2.pdf" class="nav">Q&As Dutch Page 2</a></li>
	     <?}?>
	     <li>&nbsp;</li>
	     <li><a href="downloads/publications/get_publication.php?file=cover1&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("8") ?></a></li>
	     <li><a href="downloads/publications/get_publication.php?file=cover2&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("9") ?></a></li>            
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

</body>
</html>