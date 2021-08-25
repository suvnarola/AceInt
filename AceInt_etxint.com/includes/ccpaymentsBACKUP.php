<?


// credit card process.

if(!checkmodule("CCPayments")) {

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

 $query2 = dbRead("select * from credit_transactions where FieldID = '".$_REQUEST['ponum']."'");
 $row2 = mysql_fetch_assoc($query2);

}

// main body of script

if($_REQUEST['next'] == 2) {

 // confirm stuff here.

 // update type of transaction.
 dbWrite("update credit_transactions set type='".$_REQUEST['PaymentType']."' where FieldID='".$_REQUEST['ponum']."'");

 confirm_form('',$_POST['memid']);

} elseif($_REQUEST['next'] == 3) {

 // We need to try and charge the card now.

 $SecureResponse = Process_Credit_Card($MerchantID,$_REQUEST['amount'],$_REQUEST['ponum'],$_REQUEST['creditCard1'],$_REQUEST['creditCard2'],$_REQUEST['creditCard3'],$_REQUEST['creditCard4'],$_REQUEST['exdate1'],$_REQUEST['exdate2'],$_REQUEST['optional_info']);

 // see if the credit card processed.

 if($Debug) {
  echo "<pre>";
  var_dump($SecureResponse);
  echo "</pre>";
 }

 if($SecureResponse['successfull'] == 1) {

  // successfull.

  $errormsg = get_error($SecureResponse['response_code']);

  display_receipt('1');

 } elseif($SecureResponse['successfull'] == 2) {

  // unsuccessfull.

  $errormsg = get_error($SecureResponse['response_code']);

  display_receipt('2',$errormsg);

 }

} else {

 first_form('');

}



// functions.

function display_receipt($success,$errormsg = false) {

 global $row2, $SecureResponse;

 $amount = $SecureResponse['amount']/100;
 $amount = number_format($amount,2,'.','');

 $date = date("Y-m-d");

 if($success == 1) {

  // successfull transaction. update credit_transactions and add a cash fee payment in.
  dbWrite("update credit_transactions set success='Yes', amount='$amount', response_code='".$SecureResponse['response_code']."', response_text='".$SecureResponse['response_text']."', sp_trans_id='".$SecureResponse['txn_id']."', card_type='".$SecureResponse['card_type']."', card_name='".$SecureResponse['optional_info']."' where FieldID='".$SecureResponse['ponum']."'");

 } elseif($success == 2) {

  // unsuccessfull transaction. update credit_transactions.
  dbWrite("update credit_transactions set success='No', amount='$amount', response_code='".$SecureResponse['response_code']."', response_text='".$SecureResponse['response_text']."', sp_trans_id='".$SecureResponse['txn_id']."', card_type='".$SecureResponse['card_type']."', card_name='".$SecureResponse['optional_info']."' where FieldID='".$SecureResponse['ponum']."'");

 }

 if(checkmodule("Log")) {
  add_kpi("23", $_REQUEST['memid']);
 }


 if($errormsg) {
  ?>
  <table width="495" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><b><?= $errormsg ?></b><br><?= get_word("131") ?>: <?= $SecureResponse['response_code'] ?></td>
  </tr>
  </table>
  <br>
  <?
 } else {
  ?>
  <table width="495" border="2" bordercolor="#00FF00" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><b><?= get_word("132") ?></b><br><?= get_word("131") ?>: <?= $SecureResponse['response_code'] ?></td>
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
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("61") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_SESSION['Country']['currency'] ?><?= number_format($amount,2) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("133") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $SecureResponse['ponum'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("135") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $SecureResponse['optional_info'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("136") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $SecureResponse['txn_id'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("50") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= date("m/d/Y") ?></td>
    </tr>
    </table>
  </td>
 </tr>
</table>
<?

}

function first_form ($errormsg) {

 $ponum = add_temp_trans(1,$row['feesowe']);

?>
<html>
<body onload="javascript:setFocus('CC','amount');">
<form method="POST" action="body.php?page=ccpayments" name="CC">
<input type="hidden" name="next" value="2">
<input type="hidden" name="ponum" value="<?= $ponum ?>">
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
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("61") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">
     <input type="text" name="amount" size="15"></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("133") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $ponum ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("134") ?>:</b></td>
     <td width="325" bgcolor="#FFFFFF">
     <input type="text" name="creditCard1" size="8" value="" maxlength="4"> <input type="text" name="creditCard2" size="8" value="" maxlength="4"> <input type="text" name="creditCard3" size="8" value="" maxlength="4"> <input type="text" name="creditCard4" size="8" value="" maxlength="4"></td>
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
     <option value="13">2013</option>
     <option value="14">2014</option>
     <option value="15">2015</option>
     <option value="16">2016</option>
     </select></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("135") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">
     <input type="text" name="optional_info" size="20"></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("58") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">
     <select size="1" name="PaymentType">
     <option selected value="2">MemberShip</option>
     <option value="4">RE Fee Payment</option>
     <option value="3">E Rewards</option>
     <option value="6">Conversion</option>
     <option value="7">Dave's Fun Stuff</option>
     <option value="8">E Foundation</option>
     <option value="12">Solutions</option>
     <option value="13">RE Rollover Fee</option>
     <option value="14">Warehouse</option>
     </select></td>
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
<?


}

function confirm_form ($errormsg) {

 $amount = $_REQUEST['amount']*100;

?>
<form method="POST" action="body.php?page=ccpayments">
<input type="hidden" name="next" value="3">
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
<input type="hidden" name="success_page" value="http://admin.ebanctrade.com/body.php?page=ccpayments&successfull=1&ponum=">
<input type="hidden" name="failure_page" value="http://admin.ebanctrade.com/body.php?page=ccpayments&successfull=2&ponum=">

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
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("61") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST['amount'],2) ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("133") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_REQUEST['ponum'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("134") ?>:</b></td>
     <td width="325" bgcolor="#FFFFFF"><?= $_REQUEST['creditCard1'] ?> <?= $_REQUEST['creditCard2'] ?> <?= $_REQUEST['creditCard3'] ?> <?= $_REQUEST['creditCard4'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("64") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_REQUEST['exdate1'] ?> <?= $_REQUEST['exdate2'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("135") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325"><?= $_REQUEST['optional_info'] ?></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("58") ?>:</b></td>
     <td bgcolor="FFFFFF" width="325">&nbsp;</td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2">&nbsp;</td>
     <td bgcolor="FFFFFF" valign="middle"><button name="submit" type="submit"><?= get_word("137") ?></button></td>
    </tr>
    </table>
  </td>
 </tr>
</table>
</form>
<?

}

?>