<?
 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");
 ini_set('max_execution_time','600');
 
//$queryc = dbRead("select * from  standard_letters where CID = 1 order by letter_no",ebanc_letters); 
$queryc = dbRead("select * from  categories where CID = 15 order by category"); 
?>

<HTML>
<HEAD>
<? if($_GET[lan] == "ml") {  ?><TITLE>E Planet Trade - Home</TITLE><?    
} else {	?><TITLE>E Banc Trade - Home</TITLE><? } ?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<SCRIPT LANGUAGE="javascript" SRC="/scripts/PopBox.js">
</SCRIPT>
<body>

<form method="POST" action="abc3.php" name="FAC">
<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">

<?
while($rowc = mysql_fetch_assoc($queryc)) {

   //dbWrite("insert into standard_letters (letter_no,CID,title,letter) values ('".$rowc['letter_no']."','2','".$rowc['title']."','".addslashes($rowc['letter'])."')",ebanc_letters); 
   //dbWrite("insert into categories (category,display_drop,cont,rest_acco,rest_supp,tourist,gene_busi,wed,CID) values ('".addslashes($rowc['category'])."','".addslashes($rowc['display_drop'])."','".addslashes($rowc['cont'])."','".addslashes($rowc['rest_acco'])."','".addslashes($rowc['rest_supp'])."','".addslashes($rowc['tourist'])."','".addslashes($rowc['gene_busi'])."','".addslashes($rowc['wed'])."','2')"); 
	?>
	<tr>
		<td class="Heading2" width="100" align="right"><?= $rowc['catid'] ?></td>
		<td bgcolor="#FFFFFF" align="left"><?= $rowc['category'] ?></td>
	</tr>
	<?
}
?>

</table>
</td>
</tr>
</table>

<input type="hidden" name="changefacility" value="1">

</form>

</body>
</html>