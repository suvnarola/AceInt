<?
 include_once("global.php");

if(!checkmodule("TransReceipt")) {

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

$query=mysql_db_query($db, "select * from transactions where authno='".$_REQUEST['authno']."' and type='1'", $linkid);
$row=mysql_fetch_array($query);

#get buyer and seller information out.
$query1=mysql_db_query($db, "select * from members, country where (members.CID = country.countryID) and memid='$row[memid]'", $linkid);
$query2=mysql_db_query($db, "select * from members, country where (members.CID = country.countryID) and memid='$row[to_memid]'", $linkid);
$buyerrow=mysql_fetch_array($query1);
$sellerrow=mysql_fetch_array($query2);

$newdate=explode("-", $row[dis_date]);
$dis_date="$newdate[2]/$newdate[1]/$newdate[0]";

?>
<form method="GET" action="body.php" name="FundsTransfer">
<input type="hidden" name="page" value="TransferNew">
<table width="639" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse">
<tr>
<td bgcolor="#FFFFFF" colspan="2">
<p align="center"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a></td>
</tr>
<tr>
<td bgcolor="#FFFFFF">
<?if($_SESSION['Country']['logo'] == 'etx') {?>
    <img border="0" src="../images/<?= $_SESSION['Country']['logo'] ?>-bw.jpg" width="176" height="38"><br>
<?} else {?>
    <img border="0" src="../images/<?= $_SESSION['Country']['logo'] ?>-bw.jpg" width="158" height="76"><br>
<?}?>
    <?= $_SESSION['Country']['abn']?></td>
<td bgcolor="#FFFFFF" align="right" valign="bottom">
    <b><?= get_page_data("1") ?><br><?= $otherdate ?></b></td>
</tr>
</table>
<hr align="left" noshade color="#000000" width="639" size="1">
<table border="0" cellpadding="4" cellspacing="0" style="border-collapse: collapse" width="639" id="AutoNumber1">
  <tr>
    <td width="100%" align="left" colspan="4"><b><?= get_page_data("2") ?>.<br><?= get_word("182") ?>: <font color="#FF0000"><?= $row['authno'] ?></font></b></td>
    </tr>
  <tr>
    <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
    <td width="25%" bgcolor="#FFFFFF"><b><?= get_all_added_characters($buyerrow[companyname]) ?></b></td>
    <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
    <td width="25%" bgcolor="#FFFFFF"><b><?= get_all_added_characters($sellerrow[companyname]) ?></b></td>
  </tr>
  <tr>
    <td width="25%" class="Heading2" align="right"><?= get_page_data("4") ?>:</td>
    <td width="25%" bgcolor="#FFFFFF"><?= $row[memid] ?></td>
    <td width="25%" class="Heading2" align="right"><?= get_page_data("5") ?>:</td>
    <td width="25%" bgcolor="#FFFFFF"><?= $row[to_memid] ?></td>
  </tr>
  <tr>
    <td width="25%" class="Heading2" align="right" valign="top"><?= get_word("80") ?>:</td>
    <td width="75%" colspan="3" bgcolor="#FFFFFF"><?= $row[details] ?><? if($row[chq_no] > 0) { ?> - Cheque No: <?= $row[chq_no] ?><?}?></td>
  </tr>
  <tr>
    <td width="25%" align="right" class="Heading2"><?= get_word("41") ?>:</td>
    <td width="25%" bgcolor="#FFFFFF"><?= $dis_date ?></td>
    <td width="25%" align="right" class="Heading2"><?= get_word("61") ?>:</td>
    <td width="25%" bgcolor="#FFFFFF"><b><?= $buyerrow['currency']?><?= number_format($row[buy],2) ?></b></td>
  </tr>
  <tr>
    <td width="25%" align="right" class="Heading2"><?= get_word("106") ?>:</td>
    <td width="25%" bgcolor="#FFFFFF"><b><?= $buyerrow['currency']?><?= number_format($row[dollarfees],2) ?></b></td>
    <td width="25%" align="right" class="Heading2"><?= get_word("106") ?>:</td>
    <td width="25%" bgcolor="#FFFFFF"><b><?= $sellerrow['currency']?>0.00</b></td>
  </tr>
  </table>
<hr width="639" align="left" noshade color="#000000" size="1">
<table width="639" cellpadding="3" cellspacing="0" border="0">
  <tr>
    <td width="33%" align="left"><?= $_SESSION['Country']['address2']?></td>
    <td width="33%" align="center"><?= $_SESSION['Country']['phone']?><br><?= $_SESSION['Country']['fax']?></span></td>
    <td width="33%" align="right"><?= $_SESSION['Country']['email']?></a><br>http://www.etxint.com</td>
  </tr>
  <tr>
    <td width="100%" align="center" colspan="3" bgcolor="#FFFFFF"><button name="NextTransaction" style="width: 140; height: 21" value="1" type="submit"><font face="Verdana" size="1"><b><span lang="en-au">Next Transaction</span></b></font></button></td>
  </tr>
</table>
<input type="hidden" name="SellerID" value="<?= $sellerrow['memid'] ?>">
</form>
