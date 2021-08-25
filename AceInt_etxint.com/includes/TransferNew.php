<?

 /**
  * Funds Transfer
  *
  * TransferNew.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  * : Requires Transaction Functions.
  * : Requires Transaction Class.
  */

 check_access_level("Transaction");

 include("includes/modules/db.php");
 include("includes/modules/class.ebancSuite.php");
 include("includes/modules/class.feepayments.php");

 $ebancAdmin = new ebancSuite();

	DEFINE(MERCHANT_ID, "ebt0022");
	DEFINE(MERCHANT_PASSWORD, "k87jkqdam");

 include("includes/modules/functions.transaction.php");
 include("includes/modules/class.transaction.php");
 include("includes/modules/class.xmlCreditCard.php");

 if($_REQUEST['Transfer']) {

  $Transfer = new FundsTransfer();

  $Transfer->AddFrom($_REQUEST['BuyerID']);
  $Transfer->AddTo($_REQUEST['SellerID']);
  $Transfer->AddDate($_REQUEST['TransDate']);
  $Transfer->AddAmount($_REQUEST['TransAmount'], $_REQUEST['ConvertCurrency']);
  $Transfer->AddFees("Buyer", $_REQUEST['FeesBuyer'], $_REQUEST['ChargeFeesBuyer'], 0);
  $Transfer->AddFees("Seller", $_REQUEST['FeesSeller'], $_REQUEST['ChargeFeesSeller'], 0);
  $Transfer->AddWho($_SESSION['User']['FieldID']);
  $Transfer->AddDetails($_REQUEST['TransDetails']);
  $Transfer->MultiCheck("1");
  $Transfer->ChqNo($_REQUEST['ChqNo']);

  if($Transfer->Errors && !$_REQUEST['transok']) {

   /**
    * Errors. Display Error/Warning Dialogs.
    */

   ErrorMsg($Transfer->Check, $Transfer->Suspense);
   if($Transfer->ToRow && $Transfer->FromRow) {
    GetBalance();
    LastTrans();
   }

  } else {

   /**
    * Display Confirm Box with any warnings.
    */

   ConfirmTransfer();
   $_SESSION['Transaction']['Object'] = serialize($Transfer);
   GetBalance();
   LastTrans();

  }

 } elseif($_REQUEST['Complete']) {

  $Transfer = unserialize($_SESSION['Transaction']['Object']);
  unset($_SESSION['Transaction']['Object']);

  /**
   * Check here if there is a credit card number or a cash payment. if there is this is an itt that the fees need to be paid off straight away.
   */

  if($_REQUEST['ccNumber'] || $_REQUEST['cashPayment']) {

   if($_REQUEST['ccNumber']) {

    $ccPayment = new feePayment($Transfer->From);

	$_SESSION['feePayment']['poNumber'] = ($_SESSION['feePayment']['poNumber']) ? $_SESSION['feePayment']['poNumber'] : $ccPayment->addCCTrans($Transfer->From);

    $secureResponse = $ccPayment->ccPayment($Transfer->FromRow['memid'], ereg_replace("[^0-9\.]", "", $_REQUEST['ccPayment']), $_REQUEST['ccNumber'], $_REQUEST['exDate1'], $_REQUEST['exDate2'], "", $_REQUEST['ccType'], $_SESSION['feePayment']['poNumber']);

	if($secureResponse['APPROVED'] == "Yes") {

		/**
		 * Transaction has been successfull.
		 */

    	//if($Transfer->From == 27583) {
      	 //mail("neil@ebanctrade.com", "Transaction - ".$Transfer->From."", "Hi, \n\nDorri Has Just Spent $".number_format($Transfer->Amount,2)." with ".$Transfer->ToRow['companyname'].".", "From: E Banc Members Section <accounts@ebanctrade.com>\r\nBcc: dave@ebanctrade.com\r\n");
    	//}

		DOTransfer("1");

		$ebancAdmin->dbWrite("update credit_transactions set success = 'Yes', amount = '" . $Transfer->FromFees . "', response_code = '" . $secureResponse['RESPONSECODE'] . "', response_text = '" . $secureResponse['RESPONSETEXT'] . "', sp_trans_id = '" . $secureResponse['TXNID'] . "', card_type = '" . $secureResponse['CARDDESCRIPTION'] . "', card_name = '" . $_SESSION['feePayment']['optionalInfo'] . "', type = 1 where FieldID = '" . $_SESSION['feePayment']['poNumber'] . "'");
		$ccPayment->payFees($_SESSION['feePayment']['memberRow'], $Transfer->FromFees, 1, 5, "", $Transfer->ToRow, "ITT Cash Fees", $Transfer->FeesIncurrID);
		displayReceipt('1',$secureResponse);

		unset($Transfer);
		unset($_SESSION['feePayment']);

	} elseif($secureResponse['APPROVED'] == "No") {

		/**
		 * Transaction has been unsuccessfull.
		 */

		$ebancAdmin->dbWrite("update credit_transactions set success = 'No', amount = '" . $_SESSION['feePayment']['chargeAmount'] . "', response_code = '" . $secureResponse['RESPONSECODE'] . "', response_text = '" . $secureResponse['RESPONSETEXT'] . "', sp_trans_id = '" . $secureResponse['TXNID'] . "', card_type = '" . $secureResponse['CARDDESCRIPTION'] . "', card_name='" . $_SESSION['feePayment']['optionalInfo'] . "', type = 1 where FieldID = '" . $_SESSION['feePayment']['poNumber'] . "'");
		displayReceipt('2',$secureResponse);

		unset($_SESSION['feePayment']);

	}


   } else {

    	if($Transfer->From == 27583) {
      	 mail("neil@ebanctrade.com", "Transaction - ".$Transfer->From."", "Hi, \n\nDorri Has Just Spent $".number_format($Transfer->Amount,2)." with ".$Transfer->ToRow['companyname'].".", "From: E Banc Members Section <accounts@ebanctrade.com>\r\nBcc: dave@ebanctrade.com\r\n");
    	}

		DOTransfer("1");

    	$ccPayment = new feePayment($Transfer->From);
		$ccPayment->payFees($_SESSION['feePayment']['memberRow'], ereg_replace("[^0-9\.]", "", $_REQUEST['cashPayment']), 1, 6, "", $Transfer->ToRow, "ITT Cash Fees", $Transfer->FeesIncurrID);

		displayReceipt('1',$secureResponse);

		unset($Transfer);
		unset($_SESSION['feePayment']);

   }


  } else {

    //if($Transfer->From == 27583) {
      	 //mail("neil@ebanctrade.com", "Transaction - ".$Transfer->From."", "Hi, \n\nDorri Has Just Spent $".number_format($Transfer->Amount,2)." with ".$Transfer->ToRow['companyname'].".", "From: E Banc Members Section <accounts@ebanctrade.com>\r\nBcc: dave@ebanctrade.com\r\n");
    //}

    DOTransfer();
    unset($Transfer);
	unset($_SESSION['feePayment']);

  }

 } elseif($_REQUEST['NextTransaction']) {

  StartTransfer($_REQUEST['SellerID']);

 } else {

  StartTransfer();

 }
