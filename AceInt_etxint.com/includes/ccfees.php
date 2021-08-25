<?

// credit card process.

if(!checkmodule("CCFees")) {

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

//$Debug = true;
$MerchantID = "ebt0022";

// get the data out of the database if there is http_get_vars and ponum in there.

if($_REQUEST['next'] == 3) {

 $query2 = dbRead("select * from credit_transactions where FieldID='".$_REQUEST['ponum']."'");
 $row2 = mysql_fetch_assoc($query2);

}

// main body of script



if($_REQUEST['next'] == 1) {

 $memberq = mysql_db_query($db, "select * from members where memid='$_REQUEST[memid]'", $linkid);
 $memberrow = @mysql_fetch_assoc($memberq);

 if(!$_REQUEST['memid']) {

  $errormsg = "Membership number must be entered";
  first_form($errormsg);

 } elseif(!$memberrow) {

  $errormsg = "Invalded Account Number";
  first_form($errormsg);

 } else {

  //second form
  second_form('',$_REQUEST['memid']);

 }

} elseif($_REQUEST['next'] == 2) {

 // update type of transaction.
 dbWrite("update credit_transactions set type='1' where FieldID='".$_REQUEST['ponum']."'");

 // confirm.
 confirm_form('',$_REQUEST['memid']);

} elseif($_REQUEST['next'] == 3) {

 // We need to try and charge the card now.
// print "starting securepay transaction";
 $SecureResponse = Process_Credit_Card($MerchantID,$_REQUEST['amount'],$_REQUEST['ponum'],$_REQUEST['creditCard1'],$_REQUEST['creditCard2'],$_REQUEST['creditCard3'],$_REQUEST['creditCard4'],$_REQUEST['exdate1'],$_REQUEST['exdate2'],$_REQUEST['optional_info']);
// print "finished securepay transaction";

 // see if the credit card processed.

 if($Debug) {
  echo "<pre>";
  var_dump($SecureResponse);
  echo "</pre>";
 }

 if($SecureResponse['successfull'] == 1) {

  // successfull.

  $errormsg = get_error($SecureResponse['response_code']);

  display_receipt('1','',$_REQUEST['licensee']);

 } elseif($SecureResponse['successfull'] == 2) {

  // unsuccessfull.

  $errormsg = get_error($SecureResponse['response_code']);

  display_receipt('2',$errormsg,$_REQUEST['licensee']);

 }

} else {

 first_form('');

}



// functions.



function first_form($errormsg) {

?>
<body onload="javascript:setFocus('cc','creditCard1');">
<form method="POST" action="body.php?page=ccfees" name="cc">
<input type="hidden" name="next" value="1">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<?
 if($errormsg) {
  ?>
  <table width="639" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><?= $errormsg ?>&nbsp;</td>
  </tr>
  </table>
  <br>
  <?
 }
?>
<table border="0" cellspacing="1" cellpadding="1">
 <tr>
  <td class="Border">
   <table border="0" width="495" cellspacing="0" cellpadding="3">
    <tr>
     <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_page_data("1") ?></b></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("50") ?>:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="text" name="memid" size="10" value="<?= $_REQUEST['memid'] ?>">&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2">&nbsp;</td>
     <td bgcolor="FFFFFF" valign="middle">
     <button name="submit" type="submit"><?= get_word("130") ?></button></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
</body>
<?

}

function second_form ($errormsg,$memid) {

 $memrow = mysql_fetch_array(dbRead("select * from members where memid='$memid'"));

 if(!$errormsg) {

  $query = dbRead("select memid as memid2, sum(dollarfees) as feesowe from transactions where memid='$memid' group by memid");
  $row = mysql_fetch_assoc($query);

 }

 $ponum = add_temp_trans($_REQUEST['memid'],$row['feesowe']);

?>
<body onload="javascript:setFocus('cc','creditCard1');">
<form method="POST" action="body.php?page=ccfees" name="cc">
<input type="hidden" name="next" value="2">
<input type="hidden" name="ponum" value="<?= $ponum ?>">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>


<?
 if($errormsg) {
  ?>
  <table width="639" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><?= $errormsg ?>&nbsp;</td>
  </tr>
  </table>
  <br>
  <?
 }
?>

<table border="0" cellspacing="1" cellpadding="1">
 <tr>
  <td class="Border">
   <table border="0" width="495" cellspacing="0" cellpadding="3">
    <tr>
     <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_page_data("1") ?></b></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("50") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">
     <input type="hidden" name="memid" size="10" value="<?= $memid ?>"><?= $_REQUEST['memid'] ?>&nbsp;</td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("3") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= get_all_added_characters($memrow['companyname']) ?>&nbsp;</td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("61") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">
     <input type="text" name="amount" size="15" value="<?= get_all_added_characters($row['feesowe']) ?>">&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("133") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= get_all_added_characters($ponum) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("134") ?>:</b></td>
     <td width="325" bgcolor="#FFFFFF">
     <input type="text" name="creditCard1" size="8" value="" maxlength="4" onKeyPress="return number(event)"> <input type="text" name="creditCard2" size="8" value="" maxlength="4" onKeyPress="return number(event)"> <input type="text" name="creditCard3" size="8" value="" maxlength="4" onKeyPress="return number(event)"> <input type="text" name="creditCard4" size="8" value="" maxlength="4" onKeyPress="return number(event)">&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("64") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><select size="1" name="exdate1">
     <option selected value="01">01</option>
     <option value="02">02</option>
     <option value="03">03</option>
     <option value="04">04</option>
     <option value="05">05</option>
     <option value="06">06</option>
     <option value="07">07</option>
     <option value="08">08</option>
     <option value="09">09</option>
     <option value="10">10</option>
     <option value="11">11</option>
     <option value="12">12</option>
     </select> <select size="1" name="exdate2">
     <option value="06">2006</option>
     <option value="07">2007</option>
     <option value="08">2008</option>
     <option value="09">2009</option>
     <option value="10">2010</option>
     <option value="11">2011</option>
     <option value="12">2012</option>
     </select>&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("135") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">
     <input type="text" name="optional_info" size="20">&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2">&nbsp;</td>
     <td bgcolor="FFFFFF" valign="middle">
     <button name="submit" type="submit"><?= get_word("89") ?></button></td>
    </tr>
    </table>
  </td>
 </tr>
</table>
</form>
</body>
<?


}

function confirm_form ($errormsg,$memid) {

 $memrow = mysql_fetch_array(dbRead("select * from members where memid='$memid'"));

 $amount = $_REQUEST['amount']*100;

?>
<form method="POST" action="body.php?page=ccfees">
<input type="hidden" name="next" value="3">
<? if($_REQUEST['ChangeMargin']) { ?><input type="hidden" name="ChangeMargin" value="1"><? } ?>

<input type="hidden" name="merchantid" value="ebt0022">
<input type="hidden" name="amount" value="<?= $amount ?>">
<input type="hidden" name="ponum" value="<?= $_REQUEST['ponum'] ?>">
<input type="hidden" name="creditCard1" value="<?= $_REQUEST['creditCard1'] ?>">
<input type="hidden" name="creditCard2" value="<?= $_REQUEST['creditCard2'] ?>">
<input type="hidden" name="creditCard3" value="<?= $_REQUEST['creditCard3'] ?>">
<input type="hidden" name="creditCard4" value="<?= $_REQUEST['creditCard4'] ?>">
<input type="hidden" name="exdate1" value="<?= $_REQUEST['exdate1'] ?>">
<input type="hidden" name="exdate2" value="<?= $_REQUEST['exdate2'] ?>">
<input type="hidden" name="optional_info" value="<?= $_REQUEST['optional_info'] ?>">
<input type="hidden" name="success_page" value="http://admin.ebanctrade.com/body.php?page=ccfees&successfull=1&ponum=">
<input type="hidden" name="failure_page" value="http://admin.ebanctrade.com/body.php?page=ccfees&successfull=2&ponum=">
<input type="hidden" name="licensee" value="<?= $memrow['licensee'] ?>">

<?
 if($errormsg) {
  ?>
  <table width="639" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><?= $errormsg ?>&nbsp;</td>
  </tr>
  </table>
  <br>
  <?
 }
?>
<table border="0" cellspacing="1" cellpadding="1">
 <tr>
  <td class="Border">
   <table border="0" width="495" cellspacing="0" cellpadding="3">
    <tr>
     <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_page_data("1") ?></b></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("50") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_POST[memid] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("3") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= get_all_added_characters($memrow[companyname]) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("61") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_SESSION['Country']['currency'] ?><?= number_format($_POST[amount],2) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("133") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= get_all_added_characters($_POST[ponum]) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("134") ?>:</b></td>
     <td width="325" bgcolor="#FFFFFF"><?= $_POST[creditCard1] ?> <?= $_POST[creditCard2] ?> <?= $_POST[creditCard3] ?> <?= $_POST[creditCard4] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("64") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_POST[exdate1] ?> <?= $_POST[exdate2] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("135") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= get_all_added_characters($_POST[optional_info]) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2">&nbsp;</td>
     <td bgcolor="FFFFFF" valign="middle"><button name="submit" type="submit"><?= get_word("137") ?></button></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <table border="1" width="495" cellspacing="1" cellspacing="1" cellpadding="3">
    <tr>
     <td colspan = "2" bgcolor="FFFFFF" align="middle"><font size="2" color="#FF0000"><?= eval(" ?>".get_page_data("2")."<? ") ?></font></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
<?

}

?>
