<?

$query5=mysql_db_query($db, "select * from country where countryID='".$_SESSION['User']['CID']."'", $linkid);
$row5=mysql_fetch_array($query5);

if(!checkmodule("Transaction")) {

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

$gettrans="select * from transactions where id='".$_REQUEST['id']."'";
$dbgettrans=mysql_db_query($db, $gettrans, $linkid);
list($t_memid,$t_date,$t_to_memid,$t_buy,$t_sell,$t_tradefees,$t_dollarfees,$t_type,$t_details,$t_authno,$t_dis_date,$t_clear_date,$t_chq_no,$t_checked,$t_id,$t_userid)=mysql_fetch_row($dbgettrans);

if($t_type == 1) {
 $new_type="Buy";
 $amount=$t_buy;
 $account=$t_to_memid;
} elseif($t_type == 2) {
 $new_type="Sell";
 $amount=$t_sell;
 $account=$t_to_memid;
} elseif($t_type == 3) {
 $new_type="Cash Fees";
 $amount=$t_dollarfees;
 $account=$t_memid;
}

$getcompanyname="select companyname from members where memid='$account'";
$dbgetcompanyname=mysql_db_query($db, $getcompanyname, $linkid);
list($account)=mysql_fetch_row($dbgetcompanyname);
$amount=number_format($amount,2);

$query3 = dbRead("select * from tbl_admin_users where FieldID = '$t_userid'");
$row3=mysql_fetch_assoc($query3);

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Transaction Details</title>
</head>

<body leftmargin="1" topmargin="1">
<table border="0" cellpadding="2" cellspacing="0" width="200">
  <tr>
    <td width="100%" colspan="2" align="center" valign="top" bgcolor="#FFFFFF"><a href="javascript:print()" class="nav"><img border="0" src="images/icon_printable.gif"></a></td>
  </tr>
</table>
<table cellpadding="1" cellspacing="1" width="200">
<tr>
<td class="Border">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" align="center" valign="top" class="Heading2"><?= get_word("80") ?></td>
  </tr>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("41") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $t_dis_date ?>&nbsp;</td>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("41") ?> Cleared:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $t_clear_date ?>&nbsp;</td>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("86") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $new_type ?>&nbsp;</td>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("1") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $t_to_memid ?>&nbsp;</td>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("42") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $account ?>&nbsp;</td>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("61") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $row5[currency]?><?= $amount ?>&nbsp;</td>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("107") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><? if(checkmodule("TransReceipt")) { ?><a class="nav" target="_blank" href="body.php?page=trans_receipt&authno=<?= $t_authno ?>"><? } ?><?= $t_authno ?><? if(checkmodule("TransReceipt")) { ?></a><? } ?>&nbsp;</td>
  </tr>
  <tr>
    <td width="80" class="Heading2" align="right"><?= get_word("77") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $row3['Name'] ?>&nbsp;</td>
  </tr>
  <tr>
    <td width="80" class="Heading2" align="right">Chq No:</td>
    <td width="67%" bgcolor="#FFFFFF"><?= $t_chq_no ?>&nbsp;</td>
  </tr>
  <tr>
    <td width="80" class="Heading2" align="right" valign="top"><?= get_word("80") ?>:</td>
    <td width="67%" bgcolor="#FFFFFF" valign="top" align="left"><?= $t_details ?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" bgcolor="#FFFFFF" class="Heading2"><a href="javascript:window.close();" class="nav"><?= get_word("111")?></a></td>
  </tr>
 </table>
</td>
</tr>
</table>

</body>

</html>
