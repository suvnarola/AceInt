<?

function first_form($errormsg = false) {

	$ponum = add_temp_trans(1,'0.00');

	?>
	
	<html>
	<body onload="javascript:setFocus('CC','amount');">
	<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" name="CC">
	<input type="hidden" name="next" value="2">
	<input type="hidden" name="tab" value="<?= $_REQUEST['tab'] ?>">
	<input type="hidden" name="page" value="credit_system/default">
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
	
	 <table border="0" width="100%" cellspacing="0" cellpadding="0">
	   <tr>
	     <td width="100%">
	       <table cellspacing="0" cellpadding="0" width="100%">
	         <tr>
	           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
	           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Process Credit Card.</td>
	           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
	         </tr>
	       </table>
	       <table cellpadding="0" cellspacing="0">
	         <tr>
	           <td><img src="images/spacer.gif" width="100%" height="1"></td>
	         </tr>
	       </table>
	       <table cellspacing="0" cellpadding="0" width="100%">
	         <tr>
	           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
	           <td width="100%" background="images/nav_02.gif" height="2"></td>
	           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
	         </tr>
	         <tr>
	           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
	           <td width="100%" background="images/nav_05.gif" style="padding: 3px">
	
				   <table border="0" width="495" cellspacing="0" cellpadding="3">
				    <tr>
				     <td align="right" valign="middle" width="120"><b><?= get_word("61") ?>:</b></td>
				     <td>
				     <input type="text" name="amount" size="15"></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="120"><b><?= get_word("133") ?>:</b></td>
				     <td><?= $ponum ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="120"><b><?= get_word("134") ?>:</b></td>
				     <td>
				     <input type="text" name="creditCard" size="24"></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="120"><b><?= get_word("64") ?>:</b></td>
				     <td><select size="1" name="exDate1">
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
				     </select> <select size="1" name="exDate2">
				     <option value="02" selected>2005</option>
				     <option value="06">2006</option>
				     <option value="07">2007</option>
				     <option value="08">2008</option>
				     <option value="09">2009</option>
				     <option value="10">2010</option>
				     <option value="11">2011</option>
				     <option value="12">2012</option>
				     </select></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="120"><b><?= get_word("135") ?>:</b></td>
				     <td>
				     <input type="text" name="optionalInfo" size="20"></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="120"><b><?= get_word("58") ?>:</b></td>
				     <td>
				     <select size="1" name="paymentType">
				     	
				     	<?
				     	
				     	 $paymentTypesSQL = dbRead("select tbl_admin_credit_types.* from tbl_admin_credit_types order by FieldID");
				     	 while($paymentTypesRow = mysql_fetch_assoc($paymentTypesSQL)) {
				     	 	
				     	 	?>
				     	 		<option value="<?= $paymentTypesRow['FieldID'] ?>"><?= $paymentTypesRow['Type'] ?></option>
				     	 	<?
				     	 	
				     	 }
					     

						?>
				     </select></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle">&nbsp;</td>
				     <td valign="middle">
				     <button name="submit" type="submit"><?= get_word("89") ?></button></td>
				    </tr>
				    </table>
				
			   </td>
	           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
	         </tr>
	         <tr>
	           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
	           <td width="100%" background="images/nav_10.gif" height="2"></td>
	           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
	         </tr>
	       </table>
	     </td>
	   </tr>
	 </table>
	 <table cellpadding="0" cellspacing="0" width="100%">
	   <tr>
	     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
	   </tr>
	 </table>
				
	</form>
	
	<?

}

function confirm_form ($errormsg) {

	$amountCents = $_REQUEST['amount']*100;

	?>
	
	<form method="POST" action="body.php?page=ccpayments">
	<input type="hidden" name="page" value="credit_system/default">
 	<input type="hidden" name="next" value="3">
 	<input type="hidden" name="tab" value="<?= $_REQUEST['tab'] ?>">
	<input type="hidden" name="amount" value="<?= $amountCents ?>">
	<input type="hidden" name="ponum" value="<?= $_REQUEST['ponum'] ?>">
	<input type="hidden" name="creditCard" value="<?= $_REQUEST['creditCard'] ?>">
	<input type="hidden" name="exDate1" value="<?= $_REQUEST['exDate1'] ?>">
	<input type="hidden" name="exDate2" value="<?= $_REQUEST['exDate2'] ?>">
	<input type="hidden" name="optionalInfo" value="<?= $_REQUEST['optionalInfo'] ?>">
	<input type="hidden" name="successPage" value="http://admin.ebanctrade.com/body.php?page=ccpayments&successfull=1&ponum=">
	<input type="hidden" name="failurePage" value="http://admin.ebanctrade.com/body.php?page=ccpayments&successfull=2&ponum=">
	
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

	 <table border="0" width="100%" cellspacing="0" cellpadding="0">
	   <tr>
	     <td width="100%">
	       <table cellspacing="0" cellpadding="0" width="100%">
	         <tr>
	           <td width="20"><img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
	           <td background="images/admin_site_3_10.gif" class="Heading2" width="100%">Process Credit Card.</td>
	           <td width="14"><img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
	         </tr>
	       </table>
	       <table cellpadding="0" cellspacing="0">
	         <tr>
	           <td><img src="images/spacer.gif" width="100%" height="1"></td>
	         </tr>
	       </table>
	       <table cellspacing="0" cellpadding="0" width="100%">
	         <tr>
	           <td width="2"><img src="images/nav_01.gif" border="0" width="2" height="2"></td>
	           <td width="100%" background="images/nav_02.gif" height="2"></td>
	           <td width="4"><img src="images/nav_03.gif" border="0" width="2" height="2"></td>
	         </tr>
	         <tr>
	           <td width="2" background="images/nav_04.gif"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
	           <td width="100%" background="images/nav_05.gif" style="padding: 3px">

				   <table border="0" width="495" cellspacing="0" cellpadding="3">
				    <tr>
				     <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_page_data("1") ?></b></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="170"><b><?= get_word("61") ?>:</b></td>
				     <td><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST['amount'],2) ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="170"><b><?= get_word("133") ?>:</b></td>
				     <td><?= $_REQUEST['ponum'] ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="170"><b><?= get_word("134") ?>:</b></td>
				     <td><?= $_REQUEST['creditCard'] ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="170"><b><?= get_word("64") ?>:</b></td>
				     <td><?= $_REQUEST['exDate1'] ?> <?= $_REQUEST['exDate2'] ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="170"><b><?= get_word("135") ?>:</b></td>
				     <td><?= $_REQUEST['optionalInfo'] ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle" width="170"><b><?= get_word("58") ?>:</b></td>
				     <td><?= get_type($_REQUEST['paymentType']) ?></td>
				    </tr>
				    <tr>
				     <td align="right" valign="middle">&nbsp;</td>
				     <td valign="middle"><button name="submit" type="submit"><?= get_word("137") ?></button></td>
				    </tr>
				    </table>
				
			   </td>
	           <td width="4" background="images/nav_07.gif" border="0"><img src="images/spacer.gif" border="0" width="1" height="1"></td>
	         </tr>
	         <tr>
	           <td width="2"><img src="images/nav_09.gif" border="0" width="2" height="2"></td>
	           <td width="100%" background="images/nav_10.gif" height="2"></td>
	           <td width="4"><img src="images/nav_11.gif" border="0" width="2" height="2"></td>
	         </tr>
	       </table>
	     </td>
	   </tr>
	 </table>
	 <table cellpadding="0" cellspacing="0" width="100%">
	   <tr>
	     <td width="120"><img src="images/spacer.gif" width="120" height="1"></td>
	   </tr>
	 </table>
				
	</form>
	
<?

}

function display_receipt($success,$errormsg = false) {

	global $secureReply;

	$amount = $secureReply['amount']/100;
	$amount = number_format($amount,2,'.','');

	$date = date("Y-m-d");

	if($success == 1) {
 
		// successfull transaction. update credit_transactions and add a cash fee payment in.
		dbWrite("update credit_transactions set success='Yes', amount='$amount', response_code='".$secureReply['response_code']."', response_text='".$secureReply['response_text']."', sp_trans_id='".$secureReply['txn_id']."', card_type='".$secureReply['card_type']."', card_name='".$secureReply['optional_info']."' where FieldID='".$secureReply['ponum']."'");
 
	} elseif($success == 2) {
 
		// unsuccessfull transaction. update credit_transactions.
		dbWrite("update credit_transactions set success='No', amount='$amount', response_code='".$secureReply['response_code']."', response_text='".$secureReply['response_text']."', sp_trans_id='".$secureReply['txn_id']."', card_type='".$secureReply['card_type']."', card_name='".$secureReply['optional_info']."' where FieldID='".$secureReply['ponum']."'");
  
	}

	if(checkmodule("Log")) {
		
		add_kpi("23", $_REQUEST['memid']);
		
	}


	if($errormsg) {
		
		?>
		
			<table width="495" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
				<tr>
					<td bgcolor="#FFFFFF"><b><?= $errormsg ?></b><br><?= get_word("131") ?>: <?= $secureReply['response_code'] ?></td>
				</tr>
			</table>
			<br>
			
		<?
		
	} else {
		
		?>
		
			<table width="495" border="2" bordercolor="#00FF00" cellpadding="3" cellspacing="0">
				<tr>
					<td bgcolor="#FFFFFF"><b><?= get_word("132") ?></b><br><?= get_word("131") ?>: <?= $secureReply['response_code'] ?></td>
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
	     <td bgcolor="FFFFFF" width="325"><?= $secureReply['ponum'] ?></td>
	    </tr>
	    <tr>
	     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("135") ?>:</b></td>
	     <td bgcolor="FFFFFF" width="325"><?= $secureReply['optional_info'] ?></td>
	    </tr>
	    <tr>
	     <td align="right" valign="middle" class="Heading2" width="170"><b><?= get_word("136") ?>:</b></td>
	     <td bgcolor="FFFFFF" width="325"><?= $secureReply['txn_id'] ?></td>
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

?>