<?

 /**
  * Funds Transfer Functions
  *
  * functions.transaction.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  */

 function check_facility_balance($memrow,$amount,$DontAdd = false) {

	global $Transfer;

	if(!$_REQUEST[transok] && !$_REQUEST['suspense']) {

		$Transfer->TransCheck = 1;
		//checks();

	}

	$BalanceSQL = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid = ".$memrow['memid']." and checked = 0");
	$BalanceROW = mysql_fetch_assoc($BalanceSQL);

	if($BalanceROW['cb'] < $amount) {

		/**
		 * See if we can modify the facility.
		 */

		if($memrow['overdraft'] < $memrow['maxfacility']) {

			$NewFacility = 500;

			while($Modify == false) {

				if(($memrow['overdraft']+$NewFacility) < $memrow['maxfacility']) {

					if(($BalanceROW['cb']+$NewFacility) > $amount) {

						/**
						 * Transaction will go through! Modify the facility and exit.
						 */

						$memid = $memrow['memid'];
						$DBFacility = $memrow['overdraft']+$NewFacility;

						if(!$DontAdd) {
							dbWrite("update members set overdraft = '".$DBFacility."' where memid = '".$memrow['memid']."'");
							$ClearTrans = true;
							require("includes/facility.php");
						}

						$Modify = true;
						return "0";

					} else {


						$NewFacility += 500;

					}

				} else {

					/**
					 * We can only add up to the max facility. add that to see if the transaction will go through.
					 */

					if((($BalanceROW['cb']-$memrow['overdraft'])+$memrow['maxfacility']) > $amount) {

						/**
						 * We can get the transaction through with the max facility change it and exit.
						 */

						$memid = $memrow['memid'];

						if(!$DontAdd) {
							dbWrite("update members set overdraft = '".$memrow['maxfacility']."' where memid = '".$memrow['memid']."'");
							$ClearTrans = true;
							require("includes/facility.php");
						}

						$Modify = true;
						return "0";

					} else {

						/**
						 * We cant do anything for this person. exit out
						 */

						$Modify = true;
						return "1";

					}

				}

			}

		} else {

			return "1";

		}

	}

	return "1";

 }

 function StartTransfer($SendMemid = false) {

  $_SESSION['Transaction']['TransDate'] = date("d F Y", mktime() + $_SESSION['Country']['timezone']);

  ?>
   <html>
   <head>
   <meta http-equiv="Content-Language" content="en-us">
   <meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
   </head>
   <body onload="javascript:setFocus('FundsTransfer','BuyerID');">
   <form method="GET" action="body.php" name="FundsTransfer">
   <input type="hidden" name="page" value="TransferNew">
   <table border="0" cellpadding="1" cellspacing="0" width="620">
    <tr>
     <td class="Border">
      <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
       <tr>
        <td width="100%" colspan="4" align="center" class="Heading"><?= get_page_data("1") ?></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right"><?= get_page_data("2") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="BuyerID" size="20" onKeyPress="return number(event)"></td>
        <td width="25%" class="Heading2" align="right"><?= get_page_data("3") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="SellerID" size="20" onKeyPress="return number(event)" value="<?= $SendMemid ?>"></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right" valign="top"><?= get_word("80") ?>:</td>
        <td width="75%" colspan="3" bgcolor="#FFFFFF"><textarea rows="4" name="TransDetails" cols="54"></textarea></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_word("41") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="TransDate" size="20" value="<?= $_SESSION['Transaction']['TransDate'] ?>"></td>
        <td width="25%" align="right" class="Heading2"><?= get_word("61") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="TransAmount" size="20" onKeyPress="return number(event)"></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2">Cheque Number:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="ChqNo" size="5" onKeyPress="return number(event)"></td>
        <td width="25%" align="right" class="Heading2"><?= get_word("139") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
  	   <?

          $sql_query = dbRead("select * from country order by name");
          form_select('ConvertCurrency',$sql_query,'name','convert',$_SESSION['Country']['convert']);

  	   ?>
        </td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="FeesBuyer" size="8" onKeyPress="return number(event)"></td>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="FeesSeller" size="8" onKeyPress="return number(event)"></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("5") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
         <select size="1" name="ChargeFeesBuyer">
          <option selected value="1">Yes</option>
          <option value="0">No</option>
         </select>
        </td>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("5") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
         <select size="1" name="ChargeFeesSeller">
          <option selected value="1">Yes</option>
          <option value="0">No</option>
         </select>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <table border="0" cellpadding="1" cellspacing="0" width="620">
    <tr>
     <td width="100%" align="center" colspan="4" bgcolor="#FFFFFF">
     <button name="Transfer2" style="width: 120; height: 22" value="1" type="submit"><b>
     <font face="Verdana" size="1"><?= get_page_data("10") ?></font></b></button></td>
    </tr>
   </table>
   <input type="hidden" name="Transfer" value="1">
   </form>
   </body>
   </html>
  <?

 }

 function ErrorMsg($check = false, $suspense = false) {

  global $ebancAdmin, $Transfer;

  ?>
   <html>
   <head>
   <meta http-equiv="Content-Language" content="en-us">
   <meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
   </head>
   <body onload="javascript:setFocus('FundsTransfer','BuyerID');">
   <form method="GET" action="body.php" name="FundsTransfer">
   <input type="hidden" name="page" value="TransferNew">
	<?
     DisplayWarnings();
    ?>
   <br>
   <table border="0" cellpadding="1" cellspacing="0" width="620">
    <tr>
     <td class="Border">
      <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
       <tr>
        <td width="100%" colspan="4" align="center" class="Heading"><?= get_page_data("1") ?></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF"><?= $Transfer->FromRow['companyname'] ?></td>
        <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF"><?= $Transfer->ToRow['companyname'] ?></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right"><?= get_page_data("2") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="BuyerID" size="20" value="<?= stripslashes($_REQUEST['BuyerID']) ?>" onKeyPress="return number(event)"></td>
        <td width="25%" class="Heading2" align="right"><?= get_page_data("3") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="SellerID" size="20" value="<?= stripslashes($_REQUEST['SellerID']) ?>" onKeyPress="return number(event)"></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right" valign="top"><?= get_word("80") ?>:</td>
        <td width="75%" colspan="3" bgcolor="#FFFFFF"><textarea rows="4" name="TransDetails" cols="54"><?= stripslashes($_REQUEST['TransDetails']) ?></textarea></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_word("41") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
		<input type="text" name="TransDate" size="20" value="<?= date("d F Y", strtotime($_REQUEST['TransDate']))?>"></td>
        <td width="25%" align="right" class="Heading2"><?= get_word("61") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="TransAmount" size="20" value="<?= stripslashes($_REQUEST['TransAmount']) ?>" onKeyPress="return letternumber(event)"></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2">Cheque No:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="ChqNo" size="5" value="<?= $_REQUEST['ChqNo'] ?>" onKeyPress="return number(event)"></td>
        <td width="25%" align="right" class="Heading2"><?= get_word("139") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
  	   <?

          $sql_query = dbRead("select * from country order by name");
          form_select('ConvertCurrency',$sql_query,'name','convert',$_REQUEST['ConvertCurrency']);

  	   ?>
        </td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="FeesBuyer" size="8" value="<?= stripslashes($_REQUEST['FeesBuyer']) ?>" onKeyPress="return number(event)"></td>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><input type="text" name="FeesSeller" size="8" value="<?= stripslashes($_REQUEST['FeesSeller']) ?>" onKeyPress="return number(event)"></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("5") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
         <select size="1" name="ChargeFeesBuyer">
         <option <? if ($_REQUEST['ChargeFeesBuyer'] == "1") { echo "selected "; } ?>value="1">Yes</option>
         <option <? if ($_REQUEST['ChargeFeesBuyer'] == "0") { echo "selected "; } ?>value="0">No</option>
         </select>
        </td>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("5") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF">
         <select size="1" name="ChargeFeesSeller">
         <option <? if ($_REQUEST['ChargeFeesSeller'] == "1") { echo "selected "; } ?>value="1">Yes</option>
         <option <? if ($_REQUEST['ChargeFeesSeller'] == "0") { echo "selected "; } ?>value="0">No</option>
         </select>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <table border="0" cellpadding="1" cellspacing="0" width="620">
    <?

    if(checkmodule("Contractor") && checkmodule("Staff") && $suspense && $Transfer->AmountFrom > $Transfer->BuyerBalance && $Transfer->AmountFrom < $Transfer->FromCountry['authlimit']) {
     if(checkmodule("Suspense"))  {?>
      <tr>
       <td colspan="2" align="center" width="100%"><?= get_page_data("16") ?>? <input type="checkbox" name="suspense" value="1">  <a href="body.php?page=lettercreate&Client=<?= $Transfer->From ?>&letter=8&type=1&letter_no=8&view=2&to=<?= $Transfer->FromRow['accholder'] ?>" class="nav"><b><?= get_page_data("17") ?></b></a></td>
      </tr>
    <?}
    } elseif($check) {
      $ldate = date(Y-m-d);
     ?>
    <tr>
     <td colspan="2" align="center" width="100%"><? if(checkmodule("Override")) { ?>Override? <input type="checkbox" name="transok" value="1"> <? } ?> <a href="body.php?page=lettercreate&Client=<?= $Transfer->To ?>&letter=7&type=1&letter_no=7&amount=<?= $Transfer->AmountTo ?>&date=<?= $ldate ?>&buyer=<?= $Transfer->From ?>&buyername=<?= $Transfer->FromRow['companyname'] ?>&view=2" class="nav"><b>Print Dishonour Letter</b></a></td>
    </tr>
     <?
    }
    ?>
    <tr>
     <td width="100%" align="center" colspan="2" bgcolor="#FFFFFF">
     <button name="Transfer" style="width: 120; height: 22" value="1" type="submit"><b>
     <font face="Verdana" size="1"><?= get_page_data("10") ?></font></b></button></td>
    </tr>
   </table>

   <input type="hidden" name="Transfer" value="1">
   </form>
   </body>
   </html>
  <?

 }

 function ConfirmTransfer() {

  global $Transfer, $ebancAdmin;

  ?>
   <html>
   <head>
   <meta http-equiv="Content-Language" content="en-us">
   <meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
   <script LANGUAGE="JavaScript">
	<!--

	function transferConfirm() {

		if(document.FundsTransfer.ccNumber.value == false & document.FundsTransfer.cashPayment.value == false) {

			if(!document.FundsTransfer.realITT.checked) {

				alert("Amount is over <?= $_SESSION['Country']['currency'] ?><?= number_format($_SESSION['Country']['memdailylimit'], 2) ?>, You must put an Amount in the Credit Card or Cash Payment window");

			} else {

				document.FundsTransfer.submit();

			}

		} else {

		  document.FundsTransfer.submit();

		}

	}

	//-->
   </script>

   </head>
   <body>
   <a href="javascript:history.back(1)" class="nav">&lt;&lt; <?= get_word("113") ?></a>
   <br>
   <form method="GET" action="body.php" name="FundsTransfer">
   <input type="hidden" name="page" value="TransferNew">
   <input type="hidden" name="Complete" value="1">
   <?

    DisplayWarnings();

   ?>
   <table border="0" cellpadding="1" cellspacing="0" width="620">
    <tr>
     <td class="Border">

      <table border="0" cellpadding="4" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
       <tr>
        <td width="100%" colspan="4" align="center" class="Heading"><?= get_page_data("1") ?></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->FromRow['companyname'] ?></b></td>
        <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->ToRow['companyname'] ?></b></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right"><?= get_page_data("2") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><a href="body.php?page=member_edit&Client=<?= $Transfer->From ?>&pagno=1&tab=tab5 " target="_blank" class="nav"><?= stripslashes($Transfer->From) ?></a></td>
        <td width="25%" class="Heading2" align="right"><?= get_page_data("3") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><a href="body.php?page=member_edit&Client=<?= $Transfer->To ?>&pagno=1&tab=tab5 " target="_blank" class="nav"><?= stripslashes($Transfer->To) ?></a></td>
       </tr>
       <tr>
        <td width="25%" class="Heading2" align="right" valign="top"><?= get_word("80") ?>:</td>
        <td width="75%" colspan="3" bgcolor="#FFFFFF"><?= stripslashes($Transfer->Details) ?> - Cheque No: <?= $Transfer->ChqNo ?></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_word("41") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><?= date("d F Y", $Transfer->ToDBDate) ?></td>
        <td width="25%" align="right" class="Heading2"><?= get_word("61") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->ToCountry['currency'] ?><?= number_format($Transfer->AmountTo,2) ?></b></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><?= number_format($Transfer->FromFeesPercent,2) ?>%</td>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><?= number_format($Transfer->ToFeesPercent,2) ?>%</td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("5") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><? if($Transfer->ChargeFromFees) { print "Yes"; } else { print "No"; } ?></td>
        <td width="25%" align="right" class="Heading2"><?= get_page_data("5") ?>:</td>
        <td width="25%" bgcolor="#FFFFFF"><? if($Transfer->ChargeToFees) { print "Yes"; } else { print "No"; } ?></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><span lang="en-au"><?= get_page_data("6") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->FromCountry['currency'] ?><?= number_format($Transfer->FromFees,2) ?></b></td>
        <td width="25%" align="right" class="Heading2"><span lang="en-au"><?= get_page_data("6") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->ToCountry['currency'] ?><?= number_format($Transfer->ToFees,2) ?></b></td>
       </tr>
       <tr>
        <td width="25%" align="right" class="Heading2"><span lang="en-au"><?= get_page_data("7") ?>:</span></td>
        <td width="25%" bgcolor="#FFFFFF">
        <?if($Transfer->ToRow['CID'] == $_SESSION['User']['CID'] ) {?><?if(($_SESSION['User']['CID'] == 2 && $Transfer->AmountTo < 10000) || $_SESSION['User']['CID'] != 2) {?><input type="checkbox" name="clearnow" value="ON"><?}}?></td>
        <td width="25%" align="right" class="Heading2">RealEstate</td>
        <td width="25%" bgcolor="#FFFFFF"><? if((checkmodule("SuperUser") || checkmodule("Contractor")) && $Transfer->AmountTo >= $_SESSION['Country']['memdailylimit']) { ?><input type="checkbox" name="realITT" value="1"><? }?></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <table border="0" cellpadding="1" cellspacing="0" width="620">
    <tr>
     <td width="100%" align="center" colspan="4" bgcolor="#FFFFFF">
     <button name="CompleteTransfer" style="width: 140; height: 22" value="1" <? if($Transfer->AmountTo >= $_SESSION['Country']['memdailylimit']) { print 'type="button" onclick="javascript:transferConfirm()"'; } else { print 'type="submit"'; } ?>><b>
     <font face="Verdana" size="1"><?= get_page_data("8") ?></font></b></button>&nbsp;
     <button name="Cancel" style="width: 120; height: 22" value="1" type="button" onclick="javascript:window.location.href('body.php?page=TransferNew');">
     <b><font face="Verdana" size="1"><?= get_page_data("9") ?></font></b>
     </button></td>
    </tr>
   </table>

   <br>
   <?
    if($Transfer->AmountTo >= $_SESSION['Country']['memdailylimit']) {

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

			'2007'	=>	'2007',
			'2008'	=>	'2008',
			'2009'	=>	'2009',
			'2010'	=>	'2010',
			'2011'	=>	'2011',
			'2012'	=>	'2012',
			'2013'	=>	'2013',
			'2014'	=>	'2014',
			'2015'	=>	'2015',
			'2016'	=>	'2016',
			'2017'	=>	'2017',
			'2018'	=>	'2018',
			'2019'	=>	'2019',
			'2020'	=>	'2020',

		);

    	$ccPayAmount = ($_REQUEST['ccPayment']) ? $_REQUEST['ccPayment'] : $Transfer->FromFees;

?>

<table width="620" cellspacing="0" cellpadding="0">
  <tr>
  	<td colspan="2" align="center"><b>Credit Card Payment OR Cash Payment</b><br><br></td>
  </tr>
  <tr>
    <td valign="top">
		<?= $ebancAdmin->formField(1, "NAME&nbsp;ON&nbsp;CARD", "optionalInfo", $_REQUEST['optionalInfo'], "20,0", $errorMsg['optionalInfo']); ?>
    	<?= $ebancAdmin->formField(9, "CHARGE&nbsp;AMOUNT", "ccPayment", $ccPayAmount, "20,0", $errorMsg['ccPayment']); ?>
		<?= $ebancAdmin->formField(1, "CC&nbsp;NUMBER", "ccNumber", $_REQUEST['ccNumber'], "20,0", $errorMsg['ccNumber']); ?>
		<?= $ebancAdmin->formField(8, "EXPIRY&nbsp;NUMBER", "", $ebancAdmin->formSelect("exDate1", $customExpiryDate1,'','',$_REQUEST['exDate1'],'',' class="inputBoxes"') . $ebancAdmin->formSelect("exDate2", $customExpiryDate2,'','',$_REQUEST['exDate2'],'',' class="inputBoxes"'), "20,0", $errorMsg['expiryDate']); ?>
    	<?= $ebancAdmin->formField(8, "CARD&nbsp;TYPE", "", $ebancAdmin->formSelect("ccType", $customCardType,'','',$_REQUEST['ccType'],'',' class="inputBoxes"'), "20,0", $errorMsg['ccType'], "*"); ?>


    </td>
    <td valign="top">
		<?= $ebancAdmin->formField(1, "CASH&nbsp;/&nbsp;CHEQUE&nbsp;AMOUNT", "cashPayment", $_REQUEST['cashPayment'], "20,0", $errorMsg['cashPayment']); ?>
    </td>
  </tr>
</table>

	<?
    }
   ?>
   </form>
   </body>
   </html>
  <?

 }

 function DOTransfer($ittTransfer = false) {

  global $Transfer;

   $Transfer->DOTransfer("", $ittTransfer);

   //add_kpi("15",0);

   ?>
   <form method="GET" action="body.php" name="FundsTransfer">
   <input type="hidden" name="page" value="TransferNew">
   <table width="620" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse">
    <tr>
     <td bgcolor="#FFFFFF" colspan="2"><p align="center"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a></td>
    </tr>
    <?//if($FF) {?>
	    <tr>
	     <td align="center" valign="middle" colspan="2" class="Heading2">
	   		<a href="/general.php?SendTranRec=true&memid=<?= $Transfer->To ?>&authno=<?= $Transfer->AuthNo ?>" class="nav"><b>Email Transaction Confirmation to both Parties</b></a>
		 </td>
	    </tr>
	 <?//}?>
    <tr>
     <td bgcolor="#FFFFFF">
     <img border="0" src="../images/<?= $_SESSION['Country']['logo'] ?>-bw.jpg"><br><?= $_SESSION['Country']['abn'] ?></td>
     <td bgcolor="#FFFFFF" align="right" valign="bottom"><b><?= get_page_data("14") ?><br><?= date("l jS F, Y"); ?></b></td>
    </tr>
   </table>
   <hr align="left" noshade color="#000000" width="639" size="1">
   <table border="0" cellpadding="4" cellspacing="0" style="border-collapse: collapse" width="639" id="AutoNumber1">
    <tr>
     <td width="100%" align="left" colspan="4"><b><?= get_page_data("12") ?>.<br><?= get_word("182") ?>: <font color="#FF0000"><?= $Transfer->AuthNo ?></font></b></td>
    </tr>
    <tr>
     <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
     <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->FromRow['companyname'] ?></b></td>
     <td width="25%" class="Heading2" align="right"><span lang="en-au"><?= get_word("3") ?>:</span></td>
     <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->ToRow['companyname'] ?></b></td>
    </tr>
    <tr>
     <td width="25%" class="Heading2" align="right"><?= get_page_data("2") ?>:</td>
     <td width="25%" bgcolor="#FFFFFF"><?= stripslashes($Transfer->From) ?></td>
     <td width="25%" class="Heading2" align="right"><?= get_page_data("3") ?>:</td>
     <td width="25%" bgcolor="#FFFFFF"><?= stripslashes($Transfer->To) ?></td>
    </tr>
    <tr>
     <td width="25%" class="Heading2" align="right" valign="top"><?= get_word("80") ?>:</td>
     <td width="75%" colspan="3" bgcolor="#FFFFFF"><?= stripslashes($Transfer->Details) ?></td>
    </tr>
    <tr>
     <td width="25%" align="right" class="Heading2"><?= get_word("41") ?>:</td>
     <td width="25%" bgcolor="#FFFFFF"><?= date("jS F, Y", $Transfer->ToDBDate) ?></td>
     <td width="25%" align="right" class="Heading2"><?= get_word("61") ?>:</td>
     <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->ToCountry['currency'] ?><?= number_format($Transfer->AmountTo,2) ?></b></td>
    </tr>
    <?

    	if($_REQUEST['realITT']) {

		    ?>
		    <tr>
		     <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
		     <td width="25%" bgcolor="#FFFFFF"><?= number_format($Transfer->FromFeesPercent,2) ?>%</td>
		     <td width="25%" align="right" class="Heading2"><?= get_page_data("4") ?>:</td>
		     <td width="25%" bgcolor="#FFFFFF"><?= number_format($Transfer->ToFeesPercent,2) ?>%</td>
		    </tr>
		    <tr>
		     <td width="25%" align="right" class="Heading2"><span lang="en-au"><?= get_page_data("6") ?>:</span></td>
		     <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->FromCountry['currency'] ?><?= number_format($Transfer->FromFees,2) ?></b></td>
		     <td width="25%" align="right" class="Heading2"><span lang="en-au"><?= get_page_data("6") ?>:</span></td>
		     <td width="25%" bgcolor="#FFFFFF"><b><?= $Transfer->ToCountry['currency'] ?><?= number_format($Transfer->ToFees,2) ?></b></td>
		    </tr>
		    <?

    	}

    ?>
   </table>
   <hr width="639" align="left" noshade color="#000000" size="1">
   <table width="639" cellpadding="3" cellspacing="0" border="0">
    <tr>
     <td width="33%" align="left"><?= $_SESSION['Country']['address1'] ?><?if($_SESSION['Country']['address1'] != $_SESSION['Country']['address2']) {?><br><?= $_SESSION['Country']['address2'] ?><?}?></td>
     <td width="33%" align="center"><?= $_SESSION['Country']['phone'] ?><br><?= $_SESSION['Country']['fax'] ?></span></td>
     <td width="33%" align="right"><?= $_SESSION['Country']['email'] ?></a><br>http://www.<?= getWho($_SESSION[Country][logo], 2) ?></td>
    </tr>
    <tr>
     <td width="100%" align="center" colspan="3" bgcolor="#FFFFFF"><button name="NextTransaction" style="width: 140; height: 21" value="1" type="submit"><font face="Verdana" size="1"><b><span lang="en-au"><?= get_page_data("11") ?></span></b></font></button></td>
    </tr>
   </table>
   <input type="hidden" name="SellerID" value="<?= $Transfer->To ?>">
   </form>
   <?

 }

 function DisplayWarnings() {

  global $Transfer;

  if($Transfer->Warnings || $Transfer->Errors) {

   ?>
    <table width="620" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
     <tr>
      <td bgcolor="#FFFFFF">
       <?

        if($Transfer->Warnings) {
         foreach($Transfer->Warnings as $Key => $Value) {
          print "<font style=\"color: green\">" . TransactionError($Value) . "</font><br>";
         }
        }

        if($Transfer->Errors) {
         foreach($Transfer->Errors as $Key => $Value) {
          print "<font style=\"color: red\">" . TransactionError($Value) . "</font><br>";
         }
        }

       ?> &nbsp;</td>
     </tr>
    </table><br>
   <?

  }

 }

 function check_suspense($memrow,$amount,$DontAdd = false) {

  global $Transfer;

   $BalanceSQL = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid = ".$memrow['memid']."");
   $BalanceROW = mysql_fetch_assoc($BalanceSQL);

   $BuyerCountrySQL = dbRead("select * from country where countryID = ".$memrow['CID']."");
   $BuyerCountryROW = mysql_fetch_assoc($BuyerCountrySQL);

   if(($BuyerCountryROW['authlimit'] >= $amount) && ($BalanceROW['cb'] < $amount)) {

    /**
     * We can modify the balance. need to figure out how many 50's we need in there.
     */

    $Difference =  number_format($amount, 2, '.', '') - number_format($BalanceROW['cb'], 2, '.', '');
    $NumFifty = ceil($Difference/50);

    if(!$DontAdd) {
     dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$BuyerCountryROW['suspense']."','".$Transfer->FromDBDate."','".$memrow['memid']."','".$Difference."','0','0','0','1','Transfer From Suspense','".$Transfer->AuthNo."','".$Transfer->FromDBDisDate."','0','','".$_SESSION['User']['FieldID']."')");
     dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$memrow['memid']."','".$Transfer->FromDBDate."','".$BuyerCountryROW['suspense']."','0','".$Difference."','0','0','2','Transfer From Suspense','".$Transfer->AuthNo."','".$Transfer->FromDBDisDate."','0','','".$_SESSION['User']['FieldID']."')");
    }
   }

   return $Difference;

 }

 function GetBalance() {

  global $Transfer;

   $query = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".addslashes($Transfer->From)."' and checked='0'");
   $query2 = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".addslashes($Transfer->From)."'");
   $query3 = dbRead("select sum(dollarfees) as cb from transactions where memid='".addslashes($Transfer->From)."'");

   $total = mysql_fetch_assoc($query2);
   $avail = mysql_fetch_assoc($query);
   $cashfees = mysql_fetch_assoc($query3);

   ?>
   <table width="620" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
    <tr>
     <td nowrap align="left"><?= get_page_data("18") ?> <?= $Transfer->FromRow['companyname'] ?>&nbsp;</td>
     <td align="left"><?= $Transfer->FromCountry['currency'] ?><?= number_format($total[cb],2) ?>&nbsp;</td>
    </tr>
    <tr>
     <td alith="right"><?= get_page_data("19") ?> <?= $Transfer->FromRow['companyname'] ?>&nbsp;</td>
     <td align="left"><?= $Transfer->FromCountry['currency'] ?><?= number_format($avail[cb],2) ?>&nbsp;</td>
    </tr>
    <tr>
     <td alith="right"><?= get_page_data("20") ?> <?= $Transfer->FromRow['companyname'] ?>&nbsp;</td>
     <td align="left"><?= $Transfer->FromCountry['currency'] ?><?= number_format($cashfees[cb],2) ?>&nbsp;</td>
    </tr>
   </table>
   <?

 }

 function LastTrans() {

   global $Transfer;

   // top table stuff.

   ?>
   <br><br>
   <font face="Verdana" size="1" color="#000000"><b><?= get_page_data("21") ?> <?= $Transfer->FromRow['companyname'] ?></b></font>
   <table width="620" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
    <tr>
      <td class="Heading2" valign="bottom" width="30" nowrap><b><?= get_word("41") ?></b>&nbsp;</td>
      <td class="Heading2" valign="bottom" width="384"><b><?= get_word("3") ?></b>&nbsp;</td>
      <td class="Heading2" align="right" width="90" valign="bottom"><b><?= get_word("43") ?></b>&nbsp;</td>
      <td class="Heading2" align="right" width="90" valign="bottom"><b><?= get_word("44") ?></b>&nbsp;</td>
      <td class="Heading2" align="right" width="45" valign="bottom"><b><?= get_word("46") ?></b>&nbsp;</td>
    </tr>
   <?

   // get the transactions out.

   $dbgettrans = dbRead("select * from transactions where memid='".addslashes($Transfer->From)."' order by id desc limit 0,5");

   $foo=0;

   while($row = mysql_fetch_assoc($dbgettrans)) {

    $dis_date=date("d/m/y", $row[date]);
    $dbgetotherid = dbRead("select companyname from members where memid='".addslashes($row[to_memid])."'");
    $otherrow = mysql_fetch_assoc($dbgetotherid);

    $cfgbgcolorone="#CCCCCC";
    $cfgbgcolortwo="#EEEEEE";
    $bgcolor=$cfgbgcolorone;
    $foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

    ?>

    <tr>
      <td valign="top" bgcolor="<?= $bgcolor ?>" height="19" width="30" nowrap><?= $dis_date ?>&nbsp;</td>
      <td valign="top" bgcolor="<?= $bgcolor ?>" height="19" width="384"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $id ?>');" class="nav"><?= $otherrow[companyname] ?></a><? if($details) { print'<br><font style="font-size: 7pt">'.$details.'</font>'; } ?>&nbsp;</td>
      <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[buy],2) ?></td>
      <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[sell],2) ?></td>
      <td width="45" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($row[dollarfees],2) ?></td>
    </tr>

    <?

    $foo++;

   }

   ?>
   </table>
   <?

 }

function displayReceipt($success,$secureReply,$errormsg = false) {

	global $Transfer;

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

		//add_kpi("23", $Transfer->From);

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