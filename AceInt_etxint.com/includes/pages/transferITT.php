<?

	/**
	 * Credit Card Fee Payment.
	 * 
	 * @package E Banc Administration Site
	 * @author Antony Puckey
	 * @copyright Copyright 2005, RDI Host Pty Ltd
	 *
	 */
	 
	if(!checkmodule("CCFees")) {
		
		$ebancAdmin->permError("81");
		die;
		
	}

	/**
	   DEFINE(MERCHANT_ID, "ebt0022");
	   DEFINE(MERCHANT_PASSWORD, "jasu2ilk");
	 */

	/**
	 *	DEFINE(MERCHANT_ID, "ebt0001");
	 *	DEFINE(MERCHANT_PASSWORD, "k87jkqdam");
	 *
	 */

	DEFINE(MERCHANT_ID, "ebt0022");
	DEFINE(MERCHANT_PASSWORD, "k87jkqdam");

	include("includes/modules/class.feepayments.php");
	include("includes/modules/class.xmlCreditCard.php");

	$ccPayment = new feePayment($_REQUEST['memid']);
	
	switch($_REQUEST['nextPage']) {
	
		case "1":

			if(!$_REQUEST['memid']) {
			
				firstForm(array("memid" => "Membership number must be entered"));
			  
			} elseif(!$_SESSION['feePayment']['memberRow']['memid']) {
			  
				firstForm(array("memid" => "Invalid Account Number"));
			  	
			} else {
				
				if($_REQUEST['transferAmount'] > 0 && is_numeric($_REQUEST['transferAmount'])) {
				
					$_SESSION['transferITT']['memid'] = $_REQUEST['memid'];
					$_SESSION['transferITT']['tomemid'] = $_REQUEST['tomemid'];
					$_SESSION['transferITT']['transferAmount'] = $_REQUEST['transferAmount'];
					secondForm();
				
				} else {
				
					firstForm(array("transferAmount" => "Invalid Transfer Amount"));
				
				}

			}
			
			break;

		case "2":
		
			/**
			 * Check which was selected. 
			 */
			 
			 if($_REQUEST['paymentType'] == "1") {
			 
			 	/**
			 	 * Credit Card
			 	 */
			 
			 	ccForm();
			 	break;
			 
			 } elseif($_REQUEST['paymentType'] == "2") {
			 
			 	/**
			 	 * Cash / Cheque
			 	 */
			 
			 	cashForm();
			 	break;
			 
			 }
			 
		case "3":
		
			/**
			 * Process credit card but dont put the payment through yet. we need to do that after the transaction has taken place.
			 */ 
	
			$secureResponse = $ccPayment->ccPayment($_SESSION['feePayment']['memberRow']['memid'], $_SESSION['feePayment']['chargeAmount'], $_SESSION['feePayment']['ccNumber'], $_SESSION['feePayment']['expireMonth'], $_SESSION['feePayment']['expireYear'], $_SESSION['feePayment']['cvvNumber'], $_SESSION['feePayment']['ccType'], $_SESSION['feePayment']['poNumber'], 1);
	
			if($secureResponse['APPROVED'] == "Yes") {
	 
				/**
				 * Transaction has been successfull.
				 */
				 
				$ebancAdmin->dbWrite("update credit_transactions set success = 'Yes', amount = '" . $_SESSION['feePayment']['chargeAmount'] . "', response_code = '" . $secureResponse['RESPONSECODE'] . "', response_text = '" . $secureResponse['RESPONSETEXT'] . "', sp_trans_id = '" . $secureResponse['TXNID'] . "', card_type = '" . $secureResponse['CARDDESCRIPTION'] . "', card_name = '" . $_SESSION['feePayment']['optionalInfo'] . "' where FieldID = '" . $_SESSION['feePayment']['poNumber'] . "'");
				
				$_SESSION['transferITT']['paymentReceived'] = 1;
				
				//$ccPayment->payFees($_SESSION['feePayment']['memberRow'], $_SESSION['feePayment']['chargeAmount'], 1, 5);
				//displayReceipt('1',$secureResponse);  
	  
			} elseif($secureResponse['APPROVED'] == "No") {
	  
				/**
				 * Transaction has been unsuccessfull.
				 */
				 
				$ebancAdmin->dbWrite("update credit_transactions set success = 'No', amount = '" . $_SESSION['feePayment']['chargeAmount'] . "', response_code = '" . $secureResponse['RESPONSECODE'] . "', response_text = '" . $secureResponse['RESPONSETEXT'] . "', sp_trans_id = '" . $secureResponse['TXNID'] . "', card_type = '" . $secureResponse['CARDDESCRIPTION'] . "', card_name='" . $_SESSION['feePayment']['optionalInfo'] . "' where FieldID = '" . $_SESSION['feePayment']['poNumber'] . "'");
				
				displayReceipt('2',$secureResponse);  
	  
			}
			
			//displayReceipt();
			
			
				
			break;
	
		default:
		
			firstForm();
			break;		
		
	}

	/**
	 * Functions.
	 * 
	 */

function checkData() {

	global $ccPayment, $ebancAdmin;
	
	$ccTest = new SecurePay("test");
		
	if($_REQUEST['feeAmount'] <= 0) {
		
		$errorArray['feeAmount'] = "You must specify an amount greater than zero.";
		
	}
	
	$currentYear = date('Y'); 
	$currentMonth = date('m'); 
	settype($currentYear, 'integer'); 
	
	// VALIDATE EXPIRY YEAR
	if(!is_numeric($_REQUEST['exDate2']) || $_REQUEST['exDate2'] < $currentYear || $_REQUEST['exDate2'] > $currentYear + 10) {
		
		$errorArray['expiryDate'] = "Card has expired.";
		
	}

	// HAS CARD EXPIRED
	if(($_REQUEST['exDate1'] < $currentMonth && $_REQUEST['exDate2'] == $currentYear) || $_REQUEST['exDate1'] < $currentMonth && $_REQUEST['exDate2'] < $currentYear) {
		
		$errorArray['expiryDate'] = "Card has expired.";
	
	}
	
	//Check Credit Card Number.
	if(!$ccTest->validate_cardnumber($_REQUEST['ccNumber'], $ccTest->get_card_type($_REQUEST['ccType']))) {
	
		$errorArray['ccNumber'] = "Invalid Card number supplied.";
		
	}
	
	return $errorArray;
	
}

function displayReceipt($success,$secureResponse) {

	global $ebancAdmin, $ccPayment;

	if($success == 2) {
		
		?>
		
			<table width="495" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
				<tr>
					<td bgcolor="#FFFFFF"><b>[<?= $secureResponse['RESPONSECODE'] ?>]: <?= $secureResponse['RESPONSETEXT'] ?></b><br><?= $ebancAdmin->getWord("131") ?>: <?= $secureResponse['RESPONSECODE'] ?></td>
				</tr>
			</table>
			<br>
		
		<?
	
	} else {
		
		?>
		
			<table width="495" border="2" bordercolor="#00FF00" cellpadding="3" cellspacing="0">
				<tr>
					<td bgcolor="#FFFFFF"><b><?= $ebancAdmin->getWord("132") ?></b><br><?= $ebancAdmin->getWord("131") ?>: <?= $secureResponse['RESPONSECODE'] ?></td>
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
							<td align="center" valign="middle" colspan="2" class="Heading2"><b><?= $ebancAdmin->getPageData("1") ?></b></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b><?= $ebancAdmin->getWord("50") ?>:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= $_SESSION['feePayment']['memberRow']['memid'] ?></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b><?= $ebancAdmin->getWord("61") ?>:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= $_SESSION['Country']['currency'] ?><?= number_format($_SESSION['feePayment']['chargeAmount'], 2) ?></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b><?= $ebancAdmin->getWord("133") ?>:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= $_SESSION['feePayment']['poNumber'] ?></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b><?= $ebancAdmin->getWord("135") ?>:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= $_SESSION['feePayment']['optionalInfo'] ?></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b><?= $ebancAdmin->getWord("136") ?>:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= $secureResponse['TXNID'] ?></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b><?= $ebancAdmin->getWord("41") ?>:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= date("m/d/Y") ?></td>
						</tr>
						<tr>
							<td align="right" valign="middle" class="Heading2" width="170"><b>Approved:</b></td>
							<td bgcolor="FFFFFF" width="325"><?= $secureResponse['APPROVED'] ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	
	<?

}

function firstForm($errorMsg = false) {

	global $ccPayment, $ebancAdmin;

	?>

		<body onload="javascript:setFocus('ITT','memid');">
		<form method="POST" action="getPage.php?page=transferITT" name="ITT">
		<input type="hidden" name="nextPage" value="1">
	
		<?
		
			if($_REQUEST['ChangeMargin']) {
				
				?>
				
					<input type="hidden" name="ChangeMargin" value="1">
					
				<?
			}
		
			if($errorMsg) {
		 	
				?>
				
					<table>
					  	<tr>
					  		<td class="errorMsg"><?= $errorMsg['memid'] ?><?= $errorMsg['transferAmount'] ?>&nbsp;</td>
					  	</tr>
					</table>
					<br>
					
				<?
			}
			
		?>

			<table border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td class="formHeading">ITT Transfer</td>
				</tr>
			</table>
			
			<?
			
				$ccPayment->formField(1, strtoupper("from account no"), "memid", $_REQUEST['memid'], "20,0", $errorMsg['memid']);
				$ccPayment->formField(1, strtoupper("to account no"), "tomemid", $_REQUEST['tomemid'], "20,0", $errorMsg['tomemid']);
				$ccPayment->formField(1, strtoupper("transfer amount"), "transferAmount", $_REQUEST['transferAmount'], "20,0", $errorMsg['transferAmount']);
				$ccPayment->formSubmit($ebancAdmin->getWord("130"), "ITT");
			
			?>

		</form>
		</body>
		
		<?

	}

function secondForm($errorMsg = false) {

	global $ccPayment, $ebancAdmin;

		if($_REQUEST['ChangeMargin']) {
			
			?>
			
				<input type="hidden" name="ChangeMargin" value="1">
				
			<?
		}
	
		if($errorMsg) {
	 	
			?>
			
				<table>
				  	<tr>
				  		<td class="errorMsg"><?= $errorMsg ?>&nbsp;</td>
				  	</tr>
				</table>
				<br>
				
			<?
		}
	 
	?>

	<form method="POST" action="getPage.php?page=transferITT" name="ITT">
	<input type="hidden" name="nextPage" value="2">

		<table border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td class="formHeading">Choose Payment Type</td>
			</tr>
		</table>
	
	<?
	
		$paymentType = array(
		
			'1'	=>	'Credit Card',
			'2'	=>	'Cheque / Cash'
			
		);
			
		$ccPayment->formField(8, strtoupper("payment type"), "", $ebancAdmin->formSelect("paymentType", $paymentType,'','',$_REQUEST['paymentType'],'',' class="inputBoxes"'), "20,0", $errorMsg['paymentType']);
		$ccPayment->formSubmit($ebancAdmin->getWord("89"), "ITT");
	

}

function ccForm($errorMsg = false) {

	global $ccPayment, $ebancAdmin;
	
	$_SESSION['feePayment']['poNumber'] = ($_SESSION['feePayment']['poNumber']) ? $_SESSION['feePayment']['poNumber'] : $ccPayment->addCCTrans($_SESSION['feePayment']['memberRow']['memid']);

	?>
	
	<body onload="javascript:setFocus('ITT','optionalInfo');">
	<form method="POST" action="getPage.php?page=ccfees" name="ITT">
	<input type="hidden" name="nextPage" value="3">
	
		<? 
		
			if($_REQUEST['ChangeMargin']) {
				
				?>
				
					<input type="hidden" name="ChangeMargin" value="1">
					
				<?
			}
		
			if($errorMsg) {
		 		
		 		foreach($errorMsg as $errorKey => $errorValue) {
		 			
		 			$errorMsgDisplay .= ($counterBR) ? "<br>" : ""; 
		 			$errorMsgDisplay .= "[" . $errorKey . "]: " . $errorValue;
		 			
		 			$counterBR = 1;
		 			
		 		}
		 		
				?>
				
					<table>
					  	<tr>
					  		<td class="errorMsg"><?= $errorMsgDisplay ?>&nbsp;</td>
					  	</tr>
					</table>
					<br>
					
				<?
			}
		 
		?>

			<table border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td class="formHeading"><?= $ebancAdmin->getPageData("1") ?></td>
				</tr>
			</table>
			
		<?
			
			$amountDisplay = ($_REQUEST['feeAmount']) ? $_REQUEST['feeAmount'] : number_format(round(($_SESSION['transferITT']['transferAmount']/100*$_SESSION['feePayment']['memberRow']['transfeecash']), 2), 2);
			
			$_SESSION['feePayment']['feesOwing'] = $amountDisplay;
			
			$customCardType = array(
			
				'Visa'	=>	'Visa',
				'Mastercard'	=>	'Mastercard',
				'American Express'	=>	'American Express',
				'Diners Club'	=>	'Diners Club',
				'Bankcard'	=>	'Bankcard',
				'JCB'	=>	'JCB'
				
			);
			
			$customExpiryDate1 = array(
			
				'01'	=>	'01',
				'02'	=>	'02',
				'03'	=>	'03',
				'04'	=>	'04',
				'05'	=>	'05',
				'06'	=>	'06',
				'07'	=>	'07',
				'08'	=>	'08',
				'09'	=>	'09',
				'10'	=>	'10',
				'11'	=>	'11',
				'12'	=>	'12',
			
			);
			
			$customExpiryDate2 = array(
			
				'2005'	=>	'2005',
				'2006'	=>	'2006',
				'2007'	=>	'2007',
				'2008'	=>	'2008',
				'2009'	=>	'2009',
				'2010'	=>	'2010',
			
			);
			
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("50")), "", $_SESSION['feePayment']['memberRow']['memid'], "20,0");
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("3")), "", $_SESSION['feePayment']['memberRow']['companyname'], "20,0");
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("133")), "", $_SESSION['feePayment']['poNumber'], "20,0");
			$ccPayment->formField(1, strtoupper($ebancAdmin->getWord("61")), "feeAmount", $amountDisplay, "20,0", $errorMsg['feeAmount'], "*");
			$ccPayment->formField(1, strtoupper($ebancAdmin->getWord("135")), "optionalInfo", $_REQUEST['optionalInfo'], "20,0", $errorMsg['optionalInfo']);
			$ccPayment->formField(1, strtoupper($ebancAdmin->getWord("134")), "ccNumber", $_REQUEST['ccNumber'], "20,0", $errorMsg['ccNumber'], "*");
			$ccPayment->formField(8, strtoupper($ebancAdmin->getWord("64")), "", $ebancAdmin->formSelect("exDate1", $customExpiryDate1,'','',$_REQUEST['exDate1'],'',' class="inputBoxes"') . $ebancAdmin->formSelect("exDate2", $customExpiryDate2,'','',$_REQUEST['exDate2'],'',' class="inputBoxes"'), "20,0", $errorMsg['expiryDate'], "*");
			$ccPayment->formField(1, strtoupper("cvv number"), "cvvNumber", $_REQUEST['cvvNumber'], "20,0");
			$ccPayment->formField(8, strtoupper("card type"), "", $ebancAdmin->formSelect("ccType", $customCardType,'','',$_REQUEST['ccType'],'',' class="inputBoxes"'), "20,0", $errorMsg['ccType'], "*");
			$ccPayment->formSubmit($ebancAdmin->getWord("89"), "cc");
		
		?>

	</form>
	</body>

	<?

}

function confirmForm () {

	global $ccPayment, $ebancAdmin;

	$_SESSION['feePayment']['chargeAmount'] = $_REQUEST['feeAmount'];
	$_SESSION['feePayment']['optionalInfo'] = $_REQUEST['optionalInfo'];
	$_SESSION['feePayment']['ccNumber'] = $_REQUEST['ccNumber'];
	$_SESSION['feePayment']['ccType'] = $_REQUEST['ccType'];
	$_SESSION['feePayment']['cvvNumber'] = $_REQUEST['cvvNumber'];
	$_SESSION['feePayment']['expireMonth'] = $_REQUEST['exDate1'];
	$_SESSION['feePayment']['expireYear'] = $_REQUEST['exDate2'];

	?>
		<form method="POST" action="getPage.php?page=ccfees" name="cc">
		<input type="hidden" name="nextPage" value="3">

			<table border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td class="formHeading"><?= $ebancAdmin->getPageData("1") ?></td>
				</tr>
			</table>
			
		<?

			if($_REQUEST['ChangeMargin']) {
				
				?>
				
					<input type="hidden" name="ChangeMargin" value="1">
					
				<?
				
			}

			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("50")), "", $_SESSION['feePayment']['memberRow']['memid'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("3")), "", $_SESSION['feePayment']['memberRow']['companyname'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("133")), "", $_SESSION['feePayment']['poNumber'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("61")), "", number_format($_SESSION['feePayment']['chargeAmount'], 2), "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("135")), "", $_SESSION['feePayment']['optionalInfo'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("134")), "", $_SESSION['feePayment']['ccNumber'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper($ebancAdmin->getWord("64")), "", $_SESSION['feePayment']['expireMonth'] . "/" . $_SESSION['feePayment']['expireYear'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper("cvv number"), "", $_SESSION['feePayment']['cvvNumber'], "20,0", $errorMsg);
			$ccPayment->formField(9, strtoupper("card type"), "", $_SESSION['feePayment']['ccType'], "20,0", $errorMsg);
			$ccPayment->formSubmit($ebancAdmin->getWord("137"), "cc");
		 	
		?>
				
			<table>
			  	<tr>
			  		<td class="errorMsg"><?= eval(" ?>".$ebancAdmin->getPageData("2")."<? ") ?>&nbsp;</td>
			  	</tr>
			</table>
			<br>

</form>
<?

}

?>
