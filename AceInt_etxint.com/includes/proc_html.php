<?

$dquery = dbRead("SELECT * from tbl_proc_docs where Doc_Display = 1 ");
while($drow = mysql_fetch_assoc($dquery)) {

 $DocArray[] = $drow['Doc_No'];
 $DocLocation[] = "<a class=\"nav3\" href=\"#\" onclick=\"javascript:opener.location.href='https://admin.etxint.com" . str_replace("**",$_SESSION['Country']['countrycode'],$drow['Location']) . "'\">" . $drow['Doc_No'] . "</a>";

}

function doc_check($String) {

 global $DocArray, $DocLocation;

 return str_replace($DocArray, $DocLocation, $String);

}


$query = dbRead("SELECT * from tbl_procedure where fieldid = '".$_REQUEST['id']."'order by proc_no");
while($row = mysql_fetch_assoc($query)) {
?>
<table width="520" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td width="33%"><?= get_page_data("1") ?></td>
  <td align="center" width="34%"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a> || <a class="nav" href="#" onclick="javascript:opener.location.href='body.php?page=procs&tab=tab1';">Procedure List</a></td>
  <td align="right" width="33%">&nbsp;</td>
 </tr>
</table>
<hr size="1" width="520" align="left">

<table border="0" cellspacing="0" width="520" cellpadding="1">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="520" cellpadding="3">
  <tr>
    <td class="Heading2" align="right" width="75" valign="top"><span lang="en-us">OPERATING PROCEDURE:</span></td>
    <td align="left" width="450" bgcolor="#FFFFFF"><b><font size="2"><?= $row['proc_code'] ?>-<?= $row['proc_no'] ?>: <?= $row['proc_name'] ?></font></b></td>
  </tr>
  <tr>
    <td class="Heading2" align="right" width="75" valign="top"><span lang="en-us">PURPOSE:</span></td>
    <td align="left" width="450" bgcolor="#FFFFFF"><?= $row['proc_purpose'] ?></td>
  </tr>
  <tr>
    <td class="Heading2" align="right" width="75" valign="top"><span lang="en-us">ASSOCIATED DOCUMENTS:</span></td>
    <td align="left" width="450" bgcolor="#FFFFFF"><?= doc_check($row['proc_ad']) ?></td>
  </tr>
  <tr>
    <td class="Heading2" align="right" width="75" valign="top"><span lang="en-us">PARA.NO.</span></td>
    <td class="Heading2" align="left" width="450" valign="top"><span lang="en-us"> DETAILS.</span></td>
  </tr>
   <?
    $query2 = dbRead("SELECT * from tbl_proc_data where procid = '".$row['fieldid']."' order by position");
    while($row2 = mysql_fetch_assoc($query2)) {
   ?>
  <tr>
    <td class="Heading2" align="right" width="75" valign="top"><span lang="en-us"><?= $row['proc_code'] ?>-<?= $row['proc_no'] ?>.<?= $row2['position'] ?></span></td>
    <td align="left" width="450" bgcolor="#FFFFFF"><b><?= $row2['pos_title'] ?>:</b></td>
  </tr>
  <tr>
    <td class="Heading2" align="right" width="75" valign="top"><span lang="en-us"></span></td>
    <td align="left" width="450" bgcolor="#FFFFFF"><?= doc_check($row2['pos_data']) ?></td>
  </tr>
   <?}?>
  </table>
</td>
</tr>
</table>
<br>
<table style="border: 1px solid #000000" width="520">
 <tr>
  <td align="left">Issue Number: <?= $row['proc_issue'] ?></td>
  <td align="right"><?= $row['proc_date'] ?></td>
 </tr>
</table>
<hr size="1" width="520" align="left">
<table width="520" cellspacing="0" cellpadding="2" border="0">
 <tr>
  <td align="center"><a href="javascript:window.close();" class="nav"><?= get_word("111") ?></a></td>
 </tr>
</table>
<?}?>

 <tr>
  <td colspan="2" align="right" bgcolor="#FFFFFF">&nbsp;</td></tr></table></td></tr></table>