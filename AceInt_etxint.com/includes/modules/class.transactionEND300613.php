<?

 /**
  * Transaction Class
  *
  * class.transaction.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  * : Requires Database functions, dbRead(), dbWrite() and their associated functions.
  * : Requires fee payment class.
  */

 class FundsTransfer extends ebancSuite {

  /*

   Class Description.

    To:				Membership Number of the Seller.
    From:			Membership Number of the Seller.
    Amount:			Original Amount of the transaction.
    AmountCur:		Currency of the Amount entered.
    AmountTo:		Amount to go into Sellers Account.
    AmountToCUR:	Currency of Seller
    AmountFrom:		Amount to go into

   Function Reference.

    AddTo(value)				Add Variables (To, ToRow, ToCountry) has to be valid membership number
    AddFrom(value)				Add Variables (From, FromRow, FromCountry) has to be valid membership number
    AddAmount(value,currency)	Add Original Amount of transaction in. Also if international convert amount into
   								the nessecary different currencies for processing. Requires AddTo, AddFrom.
    AddDate(value)				Adds Date and Display Date in correct timezone. Date cannot be in the past and has
   								to be valid. If the date isn't valid then put todays date in based on the buyers
   								timezone.
    AddFees(type,value,choice)	Adds fees for the buyer and seller. Choice is 0 or 1. If 0 then no fees are charged.
    AddDetails(value)			Adds Standard Details and International Details if needed.
    AddWho(value)				Adds the person who did the transaction.
    MultiCheck(type)			Multicheck function that has multiple types of operatrion depending on if you want to
    							check alot of information or not check anything al all and just let the transaction
    							go through. 1: Administration 2: Members Section 10: SuperUser
    DoTransfer()				Do the Transfer.

  */


  function FundsTransfer() {

	$this->To = "";
	$this->From = "";
	$this->Amount = "";
	$this->AmountCUR = "";
	$this->AmountTo = "";
	$this->AmountToRates = Array();
	$this->AmountToCUR = "";
	$this->AmountFrom = "";
	$this->AmountFromRates = Array();
	$this->AmountFromCUR = "";
	$this->Checked = "";
	$this->ToDBDate = "";
	$this->ToDBDisDate = "";
	$this->FromDBDate = "";
	$this->FromDBDisDate = "";
	$this->ToFeesPercent = "";
	$this->FromFeesPercent = "";
	$this->ToFees = "";
	$this->FromFees = "";
	$this->ChargeToFees = "";
	$this->ChargeFromFees = "";
	$this->Details = "";
	$this->Who = "";
	$this->AuthNo = "";
	$this->International = "";
	$this->AlreadyDone = 0;
	$this->TransactionType = "";
	$this->ToCountry = Array();
	$this->FromCountry = Array();
	$this->ToRow = Array();
	$this->FromRow = Array();
	$this->SuspenseFacilityMultiplier = "";
	$this->Check = "";
	$this->Suspense = "";
	$this->TransCheck = "";
	$this->DoSuspense = $_REQUEST['suspense'];
	$this->BuyerBalance = "";
    $this->Errors = Array();
    $this->Warnings = Array();
    $this->FeesIncurrID = "";
    $this->ChqNo = "";

  }

	function getFees($memid)  {

		$query = dbRead("select sum(dollarfees) as fees from transactions where memid = '" . $memid . "'");
		$row=mysql_fetch_array($query);

		return number_format($row["fees"], 2);

	}

  function ChqNo($Data) {

   $this->ChqNo = $Data;

  }

  function AddTo($Data) {

   $this->To = $Data;

   $ToSQL = @dbRead("Select members.*, status.* from members, status where (members.status = status.FieldID) and memid = " . $this->To);
   $this->ToRow = @mysql_fetch_assoc($ToSQL);

   if($this->ToRow) {

	$typeSQL = dbRead("select tbl_email_type.FieldID, tbl_members_email.email from tbl_email_type, tbl_members_email where (tbl_members_email.type = tbl_email_type.FieldID) and (tbl_members_email.acc_no = ".$this->To.") ");
	while($typeRow = mysql_fetch_assoc($typeSQL)) {

		$this->ToRow["emailaddress_$typeRow[FieldID]"] = $typeRow['email'];

	}

    $ToCountrySQL = dbRead("select country.* from country where countryID = " . $this->ToRow['CID']);
    $this->ToCountry = @mysql_fetch_assoc($ToCountrySQL);

   } else {

    $this->Errors[3] = 3;

   }


  }

  function AddFrom($Data) {

   $this->From = $Data;

   $FromSQL = @dbRead("Select members.*, status.* from members, status where (members.status = status.FieldID) and memid = " . $this->From);
   $this->FromRow = @mysql_fetch_assoc($FromSQL);

   if($this->FromRow) {

    $FromCountrySQL = dbRead("select country.* from country where countryID = " . $this->FromRow['CID']);
    $this->FromCountry = @mysql_fetch_assoc($FromCountrySQL);

   } else {

    $this->Errors[2] = 2;

   }

  }

  function AddAmount($Data,$Cur) {

   if($this->FromRow && $this->ToRow) {

    if(strstr($Data, "$")) {
     $Data = str_replace("$", "", $Data);
    }

    $this->Amount = $Data;
    $this->AmountCUR = $Cur;

    $this->AmountToCUR = $this->ToCountry['convert'];
    $this->AmountFromCUR = $this->FromCountry['convert'];

    if($this->AmountFromCUR == $this->AmountCUR) {
     $this->AmountFrom = $this->Amount;
    } else {
     $this->AmountFromRates = get_rates($this->AmountCUR,$this->AmountFromCUR,$this->Amount);
     $this->AmountFrom = $this->AmountFromRates['Amount'];
     $this->International = 1;
    }

    if($this->AmountToCUR == $this->AmountCUR) {
     $this->AmountTo = $this->Amount;
    } else {
     //convert amount into sellers currency.
     $this->AmountToRates = get_rates($this->AmountCUR,$this->AmountToCUR,$this->Amount);
     $this->AmountTo = $this->AmountToRates['Amount'];
     $this->International = 1;
    }

    $this->AuthNo = auth_no();

   }

  }

  function AddDate($Data) {

   if(($Timestamp = strtotime($Data)) === -1) {
     if($this->FromRow && $this->ToRow) {
      $this->ToDBDate = mktime() + $this->ToCountry['timezone'];
      $this->FromDBDate = mktime() + $this->FromCountry['timezone'];
      $this->ToDBDisDate = date("Y-m-d", mktime() + $this->ToCountry['timezone']);
      $this->FromDBDisDate = date("Y-m-d", mktime() + $this->FromCountry['timezone']);
     } else {
      $this->ToDBDate = mktime();
      $this->FromDBDate = mktime();
      $this->ToDBDisDate = date("Y-m-d", $this->ToDBDate);
      $this->FromDBDisDate = date("Y-m-d", $this->ToDBDate);
     }
   } else {
     $this->ToDBDate = $Timestamp;
     $this->FromDBDate = $Timestamp;
     $this->ToDBDisDate = date("Y-m-d", $Timestamp);
     $this->FromDBDisDate = date("Y-m-d", $Timestamp);
   }

  }

  function AddFees($Who,$Percent,$Choice,$Multi = false) {

   if($this->FromRow && $this->ToRow) {

    if($Who == "Buyer") {
     $this->ChargeFromFees = $Choice;
     if($Choice) {
      $this->FromFeesPercent = (!$Percent && $this->FromRow['feescharge'] == "Buy") ? $this->FromRow['transfeecash'] : $Percent;
     } else {
      $this->FromFeesPercent = 0;
     }
     if($this->FromRow['decbalance'] > 0 && $Choice) {
      if($this->FromRow['paymenttype'] && $this->FromRow['transfeecash'] > 0 && $Multi != 1) {
       $this->FromFeesPercent = !$Percent ? $this->FromRow['transfeecash']-0.96 : $Percent-0.96;
	  } else {
       $this->FromFeesPercent = !$Percent ? $this->FromRow['transfeecash'] : $Percent;
	  }
	 }
    } elseif($Who == "Seller") {
     $this->ChargeToFees = $Choice;
     if($Choice) {
      //if($this->ToRow['paymenttype']){
      //$this->ToFeesPercent = (!$Percent && $this->ToRow['feescharge'] == "Sell") ? $this->ToRow['transfeecash']-0.96 : $Percent-0.96;
	  //} else {
      $this->ToFeesPercent = (!$Percent && $this->ToRow['feescharge'] == "Sell") ? $this->ToRow['transfeecash'] : $Percent;
      //}
     } else {
      $this->ToFeesPercent = 0;
     }
     if($this->ToRow['paymenttype'] && $this->ToRow['transfeecash'] > 0 && $Multi != 1) {
     //if($this->FromRow['paymenttype']) {
       $this->ToFeesPercent = !$Percent ? $this->ToRow['transfeecash']-0.96 : $Percent-0.96;
	 } else {
       $this->ToFeesPercent = !$Percent ? $this->ToRow['transfeecash'] : $Percent;
	 }
    }

    if($_SESSION['Country']['countryID'] == 12) {

      $this->ToFees = ($this->ToFeesPercent) ? round(($this->AmountTo/100)*$this->ToFeesPercent) : 0;
      $this->FromFees = ($this->FromFeesPercent) ? round(($this->AmountFrom/100)*$this->FromFeesPercent) : 0;

    } else {

      $this->ToFees = ($this->ToFeesPercent) ? number_format(($this->AmountTo/100)*$this->ToFeesPercent, 2, '.', '') : 0;
      $this->FromFees = ($this->FromFeesPercent) ? number_format(($this->AmountFrom/100)*$this->FromFeesPercent, 2, '.', '') : 0;

     if($this->FromRow['decbalance'] > 0 && $Who == "Buyer") {
      $nn = $this->FromRow['decbalance']-$this->AmountFrom;
      dbRead("update members set decbalance = ".$nn." where memid = ".$this->FromRow['memid']."");
	 }

    }

   }

  }

  function AddWho($Who) {
    $this->Who = $Who;
  }

  function AddDetails($Data) {

   $this->Details = $Data;

   if($this->Details && $this->International) $this->Details .= "\r\n\r\n";

   if($this->International) $this->Details .= "International Transaction from ".$this->FromRow['companyname']." - ".$this->From." to ".$this->ToRow['companyname']." - ".$this->To."\r\n".$this->ToCountry['currency']."".$this->AmountTo." @ ".$this->AmountToRates['Rate']." = ".$this->FromCountry['currency']."".$this->AmountFrom."";

  }

  function MultiCheck($Type) {

   if($Type == "1") {

    /**
     * Administration Checks.
     */

    $startDate = mktime(0,0,0,date("m"),1,date("Y"));

    if($this->ToDBDate < $startDate) {
     $this->Errors[19] = 19;
     $this->Check = true;
    }

    if($this->FromRow['Type'] == "Deactive") {
     $this->Errors[7] = 7;
     $this->Check = true;
    }

    if($this->ToRow['Type'] == "Deactive") {
     $this->Errors[8] = 8;
     $this->Check = true;
    }

    if($this->FromRow['Type'] == "Contractor") {
     $this->Errors[9] = 9;
     $this->Check = true;
    }

    if(!checkmodule("Contractor") && $this->FromRow['Type'] == "Contractor") {
     $this->Errors[10] = 10;
     $this->Check = true;
    }

    if(!checkmodule("Staff") && $this->FromRow['Type'] == "Staff") {
     $this->Errors[11] = 11;
     $this->Check = true;
    }

    if($this->ToRow['wagesacc'] > 0) {
     if($this->ToRow['wagesacc'] != $this->From) {
      $this->Errors[12] = 12;
      $this->Check = true;
     }
    }

    if($this->FromRow['memid'] == $this->FromCountry['facacc'] || $this->FromRow['memid'] == $this->FromCountry['refacacc']) {
     $this->Errors[9] = 9;
     $this->Check = true;
    }

    if($this->ToRow['memid'] == $this->ToCountry['facacc'] || $this->ToRow['memid'] == $this->ToCountry['refacacc']) {
     $this->Errors[20] = 20;
     $this->Check = true;
    }

    $TestDate = mktime()-2592000;

    $MTransSQL = dbRead("select count(*) as MultipleCount from transactions where memid = '".$this->From."' and buy = '".$this->AmountFrom."' and date > ".$TestDate."");
    $MTransRow = @mysql_fetch_assoc($MTransSQL);
    if($MTransRow['MultipleCount'] > 0) {
     $this->Warnings[1] = 1;
    }

    $Check1 = dbRead("select sum(buy) as ch1 from transactions where dis_date = '".date("Y-m-d")."' and memid = '".$this->From."' and to_memid = '".$this->To."' and checked = '0'");
    $Row1 = @mysql_fetch_assoc($Check1);

    $Check2 = dbRead("select sum(buy) as ch1 from transactions where memid = '".$this->From."' and dis_date > '".date("Y-m-d", mktime()-604800)."' and to_memid = '".$_SESSION['Transaction']['SellerID']."' and checked = '0'");
    $Row2 = @mysql_fetch_assoc($Check2);

    $Check3 = dbRead("select sum(buy) as ch1 from transactions where memid = '".$this->From."' and dis_date > '".date("Y-m-d", mktime()+604800)."' and to_memid = '".$_SESSION['Transaction']['SellerID']."' and checked = '0'");
    $Row3 = @mysql_fetch_assoc($Check3);

    if($Row1['ch1']+$this->AmountFrom > $this->FromCountry['memdailylimit']) {
     if($_SESSION['User']['CID'] == 1) {
      $this->Warnings[13] = 13;
     }
    } elseif($Row2['ch1']+$this->AmountFrom > $this->FromCountry['memweeklimit']) {
     $this->Warnings[14] = 14;
    } elseif($Row2['ch1']+$this->AmountFrom > $this->FromCountry['memweeklimit']) {
     $this->Warnings[14] = 14;
    }

    if($this->FromRow['Type'] == "Staff") {
     $this->Warnings[15] = 15;
    }

    if($this->ToRow['Type'] == "Suspend") {
      $this->Warnings[16] = 16;
    }

    if($this->FromRow['Type'] == "Contract") {
      $this->Warnings[17] = 17;
    }

    if(!$this->Errors) {
     $this->TransCheck = check_facility_balance($this->FromRow,$this->AmountFrom,true);
     if($this->DoSuspense) {
      $this->SuspenseFacilityMultiplier = check_suspense($this->FromRow,$this->AmountFrom,true);
     }
    }

    if(!$this->SuspenseFacilityMultiplier) {
     if($this->FromRow['Type'] == "Suspend" && $this->AmountFrom < $this->FromCountry['authlimit']) {
      $this->Warnings[5] = 5;
      $this->Check = true;
      $this->Suspense = true;
     } elseif($this->FromRow['Type'] == "Suspend" && $this->AmountFrom > $this->FromCountry['authlimit']) {
      $this->Errors[5] = 5;
      $this->Check = true;
     }
     if($this->FromRow['letters'] == 3 && $this->AmountFrom < $this->FromCountry['authlimit']) {
      $this->Warnings[6] = 6;
      $this->Check = true;
     } elseif($this->FromRow['letters'] == 3 && $this->AmountFrom > $this->FromCountry['authlimit']) {
      $this->Errors[6] = 6;
      $this->Check = true;
     }
    }

   } elseif($Type == "2") {

    /**
     * Members Section Checks.
     */

	if($this->FromRow['CID'] = 3 || $this->ToRow['CID'] == 3 || $this->FromRow['CID'] = 10 || $this->ToRow['CID'] == 10) {
		if($this->FromRow['CID'] != $this->ToRow['CID']) {
			$this->Errors[30] = 30;
			$this->Check = true;
		}
	}

	if(!$this->Amount) {
		$this->Errors[19] = 19;
		$this->Check = true;
	}

	if(!trim($this->Details)) {
		$this->Errors[18] = 18;
		$this->Check = true;
	}

    if($this->FromRow['Type'] == "Deactive") {
     $this->Errors[7] = 7;
     $this->Check = true;
    }

    if($this->ToRow['Type'] == "Deactive") {
     $this->Errors[20] = 20;
     $this->Check = true;
    }

    if($this->FromRow['Type'] == "Contractor") {
     $this->Errors[9] = 9;
     $this->Check = true;
    }

    if(!checkmodule("Contractor") && $this->FromRow['Type'] == "Contractor") {
     $this->Errors[10] = 10;
     $this->Check = true;
    }

    //if(!checkmodule("Staff") && $this->FromRow['Type'] == "Staff") {
    // $this->Errors[11] = 11;
    // $this->Check = true;
    //}

    if($this->ToRow['wagesacc'] > 0) {
     if($this->ToRow['wagesacc'] != $this->From) {
      $this->Errors[20] = 20;
      $this->Check = true;
     }
    }

    $TestDate = mktime()-2592000;

    $Check1 = dbRead("select sum(buy) as ch1 from transactions where dis_date = '".date("Y-m-d")."' and memid = '".$this->From."' and to_memid = '".$this->To."' and checked = '0'");
    $Row1 = @mysql_fetch_assoc($Check1);

    $Check2 = dbRead("select sum(buy) as ch1 from transactions where memid = '".$this->From."' and dis_date > '".date("Y-m-d", mktime()-604800)."' and to_memid = '".$this->To."' and checked = '0'");
    $Row2 = @mysql_fetch_assoc($Check2);

	if(!$this->FromRow['itt_exempt'] && $this->FromCountry['countryID'] != 1) {
	    if($Row1['ch1']+$this->AmountFrom >= $this->FromCountry['memdailylimit']) {
	     //if($_SESSION['User']['CID'] == 1) {
	      $this->Errors[13] = 13;
	     //}
	    } elseif($Row2['ch1']+$this->AmountFrom >= $this->FromCountry['memweeklimit']) {
	     $this->Errors[14] = 14;
	    }
	}

    $Check3 = dbRead("select sum(buy) as ch1 from transactions where memid = '".$this->From."' and dis_date > '".date("Y-m-d", mktime()-604800)."' and to_memid = '".$this->To."' and checked = '0'");
    $Row3 = @mysql_fetch_assoc($Check3);

	if($Row3['ch1']+$this->AmountFrom >= 1000 && $this->AmountFrom < 1000 && $this->FromCountry['countryID'] == 1 && !$this->FromRow['itt_exempt']) {
	    //$this->Errors[13] = 13;
	}

    //if($this->FromRow['Type'] == "Staff") {
    // $this->Errors[15] = 15;
    //}

    if($this->ToRow['status'] == "6") {
      $this->Errors[20] = 20;
    }

    if($this->FromRow['Type'] == "Contract") {
      $this->Errors[17] = 17;
    }

    if(!$this->Errors) {
     $this->TransCheck = check_facility_balance($this->FromRow,$this->AmountFrom,true);
    }

   } elseif($Type == "3") {

    /**
     * Merchant Services Checks.
     */

    if($this->FromRow['Type'] == "Deactive") {
     $this->Errors[8] = 7;
    }

    if($this->FromRow['Type'] == "Contractor") {
     $this->Errors[9] = 9;
    }

    if($this->FromRow['Type'] == "Suspend") {
     $this->Errors[16] = 16;
    }

    if($this->ToRow['Type'] == "Suspend") {
     $this->Errors[5] = 5;
    }

    if($this->ToRow['Type'] == "Deactive") {
     $this->Errors[8] = 8;
    }

    if($this->ToRow['Type'] == "Suspend") {
      $this->Errors[16] = 16;
    }

    $Check1 = dbRead("select sum(buy) as ch1 from transactions where dis_date = '".date("Y-m-d")."' and memid = '".$this->From."' and to_memid = '".$this->To."' and checked = '0'");
    $Row1 = @mysql_fetch_assoc($Check1);

    $Check2 = dbRead("select sum(buy) as ch1 from transactions where memid = '".$this->From."' and (((dis_date) Between '".date("Y-m-d", mktime(0,0,0,date("m"),date("d")-date("w"),date("Y")))."' And '".date("Y-m-d", mktime(0,0,0,date("m"),date("d")+(6-date("w")),date("Y")))."')) and to_memid = '".$_SESSION['Transaction']['SellerID']."' and checked = '0'");
    $Row2 = @mysql_fetch_assoc($Check2);

    $feeAmount = $this->getFees($this->From);

    if($feeAmount < 0) {

    	$newFeeAmount = abs($feeAmount);

    	if($newFeeAmount >= $this->FromFees) {

    		//Fee Amount will pay for it do nothing else :)

    	} else {

		    if($Row1['ch1']+$this->AmountFrom > $this->FromCountry['memdailylimit']) {

		     $this->Errors[13] = 13;

		    } elseif($Row2['ch1']+$this->AmountFrom > $this->FromCountry['memweeklimit']) {

		     $this->Errors[14] = 14;

		    }

    	}

    } else {

	    if($Row1['ch1']+$this->AmountFrom > $this->FromCountry['memdailylimit']) {

	     $this->Errors[13] = 13;

	    } elseif($Row2['ch1']+$this->AmountFrom > $this->FromCountry['memweeklimit']) {

	     $this->Errors[14] = 14;

	    }

    }

    if(!$this->Errors) {
     $this->TransCheck = check_facility_balance($this->FromRow,$this->AmountFrom,true);
    }

   } elseif($Type == "4") {

	/**
	 * Scheduled Transaction checks.
	 */

    if($this->FromRow['Type'] == "Deactive") {
     $this->Errors[8] = 7;
    }

    if($this->ToRow['Type'] == "Deactive") {
     $this->Errors[8] = 8;
    }

    if($this->FromRow['status'] == "6") {
     $this->Errors[8] = 7;
    }

    if($this->ToRow['status'] == "6") {
     $this->Errors[8] = 8;
    }

    if($this->ToRow['wagesacc'] > 0) {
     if($this->ToRow['wagesacc'] != $this->From) {
      $this->Errors[20] = 20;
      $this->Check = true;
     }
    }

    if(!$this->Errors) {
     $this->TransCheck = check_facility_balance($this->FromRow,$this->AmountFrom,true);
    }

   } elseif($Type == "10") {

    /**
     * Super User. Do no checks.
     */

   }

   if($this->TransCheck && !$this->SuspenseFacilityMultiplier) {

    $query3 = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".$this->From."' and checked='0'");
    $row = mysql_fetch_assoc($query3);
    $this->BuyerBalance = number_format($row[cb], 2, ".", "");
    if($this->BuyerBalance < $this->AmountFrom && !$_REQUEST[transok]) {
     $this->Errors[4] = 4;
     $this->Check = true;
     $this->Suspense = true;
    }

   }

  }

  function feesIncurred($memberID, $payAmount, $ittTransfer) {

  	if($ittTransfer) {

		$sqlQuery = dbRead("select feesincurred.* from feesincurred where feesincurred.memid = " . $memberID . " and fieldid = " . $this->FeesIncurrID . " order by feesincurred.fieldid ASC", "etradebanc");

	} else {

		$sqlQuery = dbRead("select feesincurred.* from feesincurred where feesincurred.memid = " . $memberID . " and feesincurred.fee_amount != feesincurred.fee_paid order by feesincurred.fieldid ASC", "etradebanc");

	}

	if(!mysql_num_rows($sqlQuery)) {

		// there is fees to be paid off. pay them off.

		while($sqlRow = mysql_fetch_assoc($sqlQuery)) {

			if($payAmount >= ($sqlRow['fee_amount'] - $sqlRow['fee_paid']) && abs($payAmount) != 0) {

				$ebancAdmin->dbWrite("update feesincurred set feesincurred.fee_paid = feesincurred.fee_amount where feesincurred.fieldid = " . $sqlRow['fieldid'], "etradebanc", true);

				$payAmount -= ($sqlRow['fee_amount'] - $sqlRow['fee_paid']);

			} else {

				$ebancAdmin->dbWrite("update feesincurred set feesincurred.fee_paid = (feesincurred.fee_paid + " . $payAmount . ") where feesincurred.fieldid = " . $sqlRow['fieldid'], "etradebanc", true);

				$payAmount = 0;

			}

		}

	 }

  }

  function DOTransfer($clearTrans = false, $ittTransfer = false) {

   $this->TransCheck = check_facility_balance($this->FromRow,$this->AmountFrom);
   if($this->DoSuspense) {
    $this->SuspenseFacilityMultiplier = check_suspense($this->FromRow,$this->AmountFrom);
   }

   if($this->AmountTo >= $this->ToCountry['authlimit']) {
    if($_REQUEST['clearnow'] || $clearTrans) {
     $this->Checked = "0";
    } else {
     $this->Checked = "1";
    }
   } else {
    $this->Checked = "0";
   }

   $ff = strstr(get_non_included_accounts($this->FromCountry['countryID']), $this->From);

   if(!$this->FromFees && !$ff) {
    	 mail("dave@ebanctrade.com", "FEE FREE Transaction - ".$this->From."", "Hi, \n\n".$this->From." Has Just Spent $".number_format($this->AmountFrom,2)." FEE FREE with ".$this->To.".", "From: E Banc Members Section <accounts@ebanctrade.com>\r\nBcc: dave@ebanctrade.com\r\n");
   }

   if($this->International) {

    $trans1 = "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$this->From."','".$this->ToDBDate."','".$this->FromCountry['interacc']."','".$this->AmountFrom."','0','0','".$this->FromFees."','1','".addslashes(encode_text2($this->Details))."','".$this->AuthNo."','".$this->FromDBDisDate."','0','','".$this->Who."')";
    $trans2 = "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$this->FromCountry['interacc']."','".$this->ToDBDate."','".$this->From."','0','".$this->AmountFrom."','0','0','2','".addslashes(encode_text2($this->Details))."','".$this->AuthNo."','".$this->FromDBDisDate."','".$this->Checked."','','".$this->Who."')";
    $trans3 = "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$this->ToCountry['interacc']."','".$this->FromDBDate."','".$this->To."','".$this->AmountTo."','0','0','0','1','".addslashes(encode_text2($this->Details))."','".$this->AuthNo."','".$this->ToDBDisDate."','0','','".$this->Who."')";
    $trans4 = "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$this->To."','".$this->FromDBDate."','".$this->ToCountry['interacc']."','0','".$this->AmountTo."','0','".$this->ToFees."','2','".addslashes(encode_text2($this->Details))."','".$this->AuthNo."','".$this->ToDBDisDate."','".$this->Checked."','','".$this->Who."')";

    $buyid = dbWrite($trans1,etradebanc,true);
    dbWrite($trans2);
    dbWrite($trans3);
    $sellid = dbWrite($trans4,etradebanc,true);

	$Details = "International Transaction Fee";

	if($this->FromFees > 0) {
		$nFromFees = ($this->FromFees/$this->FromFeesPercent)+((($this->FromFees/$this->FromFeesPercent)/100)*$this->FromCountry['tax']);
    	$nID = dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$this->From."','".$this->ToDBDate."','".$this->FromCountry['reserveacc']."','0','0','0','".$nFromFees."','3','".addslashes(encode_text2($Details))."','".$this->AuthNo."','".$this->FromDBDisDate."','0','','".$this->Who."')",etradebanc,true);
      	dbWrite("update members set fee_deductions = fee_deductions + " . $nFromFees . "  where memid = " . $this->From);
		dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $this->From . "','" . $this->FromDBDisDate . "','" . $nFromFees . "','0','". $this->FromCountry['tax'] ."','" . $this->FromRow['licensee'] . "','7','0','" . $nID . "')");
	}

	if($this->ToFees > 0) {
		$nToFees = ($this->ToFees/$this->ToFeesPercent)+((($this->ToFees/$this->ToFeesPercent)/100)*$this->ToCountry['tax']);
    	$nID = dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,id,userid) values ('".$this->To."','".$this->FromDBDate."','".$this->ToCountry['reserveacc']."','0','0','0','".$nToFees."','3','".addslashes(encode_text2($Details))."','".$this->AuthNo."','".$this->ToDBDisDate."','0','','".$this->Who."')",etradebanc,true);
      	dbWrite("update members set fee_deductions = fee_deductions + " . $nToFees . "  where memid = " . $this->To);
		dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $this->To . "','" . $this->FromDBDisDate . "','" . $nToFees . "','0','". $this->ToCountry['tax'] ."','" . $this->ToRow['licensee'] . "','7','0','" . $nID . "')");
	}

    $fromAreaSQL = dbRead("select area.* from area where FieldID = " . $this->FromRow['licensee']);
    $toAreaSQL = dbRead("select area.* from area where FieldID = " . $this->ToRow['licensee']);

    $fromAreaRow = mysql_fetch_assoc($fromAreaSQL);
    $toAreaRow = mysql_fetch_assoc($toAreaSQL);

   if(!strstr($_SESSION['Country']['trustacc'],$this->To)) {
     if($this->FromFees) {

      $chargePercentFrom = $fromAreaRow['feepercent'] / 2;

      $toCSQL = dbRead("select area.* from area where FieldID = " . $this->FromCountry['DefaultArea']);
      $toCRow = mysql_fetch_assoc($toCSQL);

      $chargePercentTo = $toCRow['feepercent'] / 2;

      if($this->FromRow['over_payment'] > 0) {

      	if($this->FromFees > $this->FromRow['over_payment']) {

      		dbWrite("update members set over_payment = 0 where memid = " . $this->From);
      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','".$this->FromDBDisDate."','".$this->FromCountry['interacc']."','".$this->FromCountry['DefaultArea']."','".$this->FromFees."','" . $this->FromRow['over_payment'] . "','".$buyid."')";
      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->FromDBDisDate."','".$this->FromCountry['interacc']."','".$this->FromCountry['DefaultArea']."','" . $chargePercentTo . "','".$this->FromFees."','" . $this->FromRow['over_payment'] . "','".$buyid."')";
          	$this->FeesIncurrID = dbWrite($trans12);

	      	$checkSQL = dbRead("select transactions.* from transactions where dollarfees < '0' and dis_date > '2005-06-30' and type in (5,6) and memid = " . $this->FromRow['memid'] . " order by dis_date DESC limit 1");

	      	if(mysql_num_rows($checkSQL)) {

				dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->To . "','" . $this->FromDBDisDate . "','" . $this->FromRow['over_payment'] . "','0','" . $chargePercentTo . "','" . $this->ToRow['licensee'] . "','5','0')", DB_NAME);

				   $rrow[memid] = $this->FromRow['memid'];
				   $rrow[licensee] = $this->FromRow['licensee'];
				   $rrow[to_memid] = $this->To;
				   $rrow[to_licensee] = $this->ToRow['licensee'];
				   $feesPaidIDArray[] = $this->interFee($rrow, $this->FromRow['over_payment'], $this->FeesIncurrID, '',true);

      		}

      		if(!strstr($_SESSION['Country']['trustacc'], $this->ToRow['memid'])) {

      			$this->feesIncurred($this->FromRow['memid'], $this->FromRow['over_payment'], $ittTransfer);

      		}

      	} else {

      		dbWrite("update members set over_payment = over_payment - " . $this->FromFees . "  where memid = " . $this->From);
      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','".$this->FromDBDisDate."','".$this->FromCountry['interacc']."','".$this->FromCountry['DefaultArea']."','".$this->FromFees."','" . $this->FromFees . "','".$buyid."')";
      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->FromDBDisDate."','".$this->FromCountry['interacc']."','".$this->FromCountry['DefaultArea']."','" . $chargePercentTo . "','".$this->FromFees."','" . $this->FromFees . "','".$buyid."')";
      		$this->FeesIncurrID = dbWrite($trans12);

			dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->To . "','" . $this->FromDBDisDate . "','" . $this->FromFees . "','0','" . $chargePercentTo . "','" . $this->ToRow['licensee'] . "','5','0')", DB_NAME);

			   $rrow[memid] = $this->FromRow['memid'];
			   $rrow[licensee] = $this->FromRow['licensee'];
			   $rrow[to_memid] = $this->To;
			   $rrow[to_licensee] = $this->ToRow['licensee'];
			   $feesPaidIDArray[] = $this->interFee($rrow, $this->FromFees, $this->FeesIncurrID, '',true);

      		if(!strstr($_SESSION['Country']['trustacc'], $this->ToRow['memid'])) {

      			$this->feesIncurred($this->FromRow['memid'], $this->FromFees, $ittTransfer);

      		}

      	}

      } else {

      	//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','".$this->FromFees."','".$buyid."')";
      	$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->FromDBDisDate."','".$this->FromCountry['interacc']."','".$this->FromCountry['DefaultArea']."','" . $chargePercentTo . "','".$this->FromFees."','".$buyid."')";
      	$this->FeesIncurrID = dbWrite($trans12, "etradebanc", true);

      }

     }
     if($this->ToFees) {

      $chargePercentTo = $toAreaRow['feepercent'] / 2;

      $toCSQL = dbRead("select area.* from area where FieldID = " . $this->ToCountry['DefaultArea']);
      $toCRow = mysql_fetch_assoc($toCSQL);

      $chargePercentFrom = $toCRow['feepercent'] / 2;

      if($this->ToRow['over_payment'] > 0) {

      	//$chargePercent = $toAreaRow['feepercent'] / 2;

      	if($this->ToFees > $this->ToRow['over_payment']) {

      		dbWrite("update members set over_payment = 0 where memid = " . $this->To);
      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->To."''".$this->ToRow['licensee']."','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','".$this->ToFees."','" . $this->ToRow['over_payment'] . "','".$sellid."')";
      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromDBDisDate."','".$this->ToCountry['interacc']."','".$this->ToCountry['DefaultArea']."','" . $chargePercentFrom . "','".$this->ToFees."','" . $this->ToRow['over_payment'] . "','".$sellid."')";
          	$this->FeesIncurrID = dbWrite($trans12);

	      	$checkSQL = dbRead("select transactions.* from transactions where dollarfees < '0' and dis_date > '2005-06-30' and type in (5,6) and memid = " . $this->ToRow['memid'] . " order by dis_date DESC limit 1");

	      	if(mysql_num_rows($checkSQL)) {

				dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->From . "','" . $this->FromDBDisDate . "','" . $this->ToRow['over_payment'] . "','0','" . $chargePercentFrom . "','" . $this->FromRow['licensee'] . "','5','0')", DB_NAME);

				   $rrow[memid] = $this->To;
				   $rrow[licensee] = $this->ToRow['licensee'];
				   $rrow[to_memid] = $this->FromRow['memid'];
				   $rrow[to_licensee] = $this->FromRow['licensee'];
				   $feesPaidIDArray[] = $this->interFee($rrow, $this->ToRow['over_payment'], $this->FeesIncurrID, '',true);

      		}

      		if(!strstr($_SESSION['Country']['trustacc'], $this->FromRow['memid'])) {

      			$this->feesIncurred($this->FromRow['memid'], $this->ToRow['over_payment'], $ittTransfer);

      		}

      	} else {

      		dbWrite("update members set over_payment = over_payment - " . $this->ToFees . "  where memid = " . $this->To);
      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->To."''".$this->ToRow['licensee']."','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','".$this->ToFees."','" . $this->ToFees . "','".$sellid."')";
      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromDBDisDate."','".$this->ToCountry['interacc']."','".$this->ToCountry['DefaultArea']."','" . $chargePercentFrom . "','".$this->ToFees."','" . $this->ToFees . "','".$sellid."')";
      		$this->FeesIncurrID = dbWrite($trans12);

			dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->From . "','" . $this->FromDBDisDate . "','" . $this->ToFees . "','0','" . $chargePercentFrom . "','" . $this->FromRow['licensee'] . "','5','0')", DB_NAME);

			   $rrow[memid] = $this->To;
			   $rrow[licensee] = $this->ToRow['licensee'];
			   $rrow[to_memid] = $this->FromRow['memid'];
			   $rrow[to_licensee] = $this->FromRow['licensee'];
			   $feesPaidIDArray[] = $this->interFee($rrow, $this->ToFees, $this->FeesIncurrID, '',true);

      		if(!strstr($_SESSION['Country']['trustacc'], $this->FromRow['memid'])) {

      			$this->feesIncurred($this->FromRow['memid'], $this->ToFees, $ittTransfer);

      		}


      	}

      } else {

      	//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','".$this->ToFees."','".$sellid."')";
      	$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromDBDisDate."','". $this->ToCountry['interacc'] ."','".$this->ToCountry['DefaultArea']."','" . $chargePercentFrom . "','".$this->ToFees."','".$sellid."')";
      	$this->FeesIncurrID = dbWrite($trans12, "etradebanc", true);

      }

     }
    }


   } else {

    $trans1 = "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,chq_no,checked,id,userid) values ('".$this->From."','".$this->ToDBDate."','".$this->To."','".$this->Amount."','0','0','".$this->FromFees."','1','".addslashes(encode_text2($this->Details))."','".$this->AuthNo."','".$this->FromDBDisDate."','".$this->ChqNo."','0','','".$this->Who."')";
    $trans2 = "insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,chq_no,checked,id,userid) values ('".$this->To."','".$this->ToDBDate."','".$this->From."','0','".$this->Amount."','0','".$this->ToFees."','2','".addslashes(encode_text2($this->Details))."','".$this->AuthNo."','".$this->ToDBDisDate."','".$this->ChqNo."','".$this->Checked."','','".$this->Who."')";

    $buyid = dbWrite($trans1,etradebanc,true);
    $sellid = dbWrite($trans2,etradebanc,true);

    //addFees($transID, $fromMemberRow, $toMemberRow, $feeAmount, $transDetails);

    $fromAreaSQL = dbRead("select area.* from area where FieldID = " . $this->FromRow['licensee']);
    $toAreaSQL = dbRead("select area.* from area where FieldID = " . $this->ToRow['licensee']);

    $fromAreaRow = mysql_fetch_assoc($fromAreaSQL);
    $toAreaRow = mysql_fetch_assoc($toAreaSQL);

   if(!strstr($_SESSION['Country']['trustacc'],$this->To)) {
     if($this->FromFees) {

      $chargePercent = $fromAreaRow['feepercent'] / 2;
      $chargePercentFrom = $fromAreaRow['feepercent'] / 2;
      $chargePercentTo = $toAreaRow['feepercent'] / 2;

      if($this->FromRow['over_payment'] > 0) {

      	//$chargePercent = $fromAreaRow['feepercent'] / 2;

	      	if($this->FromFees > $this->FromRow['over_payment']) {

	      		dbWrite("update members set over_payment = 0 where memid = " . $this->From);
	      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','".$this->FromFees."','" . $this->FromRow['over_payment'] . "','".$buyid."')";
	      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromFees."','" . $this->FromRow['over_payment'] . "','".$buyid."')";
	          	$this->FeesIncurrID = dbWrite($trans12, "etradebanc", true);

		      	$checkSQL = dbRead("select transactions.* from transactions where dollarfees < '0' and dis_date > '2005-06-30' and type in (5,6) and memid = " . $this->FromRow['memid'] . " order by dis_date DESC limit 1");

		      	if(mysql_num_rows($checkSQL)) {

					dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->To . "','" . $this->FromDBDisDate . "','" . $this->FromRow['over_payment'] . "','0','" . $chargePercentTo . "','" . $this->ToRow['licensee'] . "','5','0')", DB_NAME);
					   $rrow[memid] = $this->FromRow['memid'];
					   $rrow[licensee] = $this->FromRow['licensee'];
					   $rrow[to_memid] = $this->To;
					   $rrow[to_licensee] = $this->ToRow['licensee'];
					   $feesPaidIDArray[] = $this->interFee($rrow, $this->FromRow['over_payment'], $this->FeesIncurrID, '',true);

	      		}

	      		if(!strstr($_SESSION['Country']['trustacc'], $this->ToRow['memid'])) {

	      			$this->feesIncurred($this->FromRow['memid'], $this->FromRow['over_payment'], $ittTransfer);

	      		}

	      	} else {

	      		dbWrite("update members set over_payment = over_payment - " . $this->FromFees . "  where memid = " . $this->From);
	      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','".$this->FromFees."','" . $this->FromFees . "','".$buyid."')";
	      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromFees."','" . $this->FromFees . "','".$buyid."')";
	      		$this->FeesIncurrID = dbWrite($trans12);

				dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->To . "','" . $this->FromDBDisDate . "','" . $this->FromFees . "','0','" . $chargePercentTo . "','" . $this->ToRow['licensee'] . "','5','0')", DB_NAME);

				   $rrow[memid] = $this->FromRow['memid'];
				   $rrow[licensee] = $this->FromRow['licensee'];
				   $rrow[to_memid] = $this->To;
				   $rrow[to_licensee] = $this->ToRow['licensee'];
				   $feesPaidIDArray[] = $this->interFee($rrow, $this->FromFees, $this->FeesIncurrID, '',true);

	      		if(!strstr($_SESSION['Country']['trustacc'], $this->ToRow['memid'])) {

	      			$this->feesIncurred($this->FromRow['memid'], $this->FromFees, $ittTransfer);

	      		}

	      	}

      } else {

      	//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','".$this->FromFees."','".$buyid."')";
      	$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,trans_id) values ('".$this->From."','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->FromDBDisDate."','".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromFees."','".$buyid."')";
      	$this->FeesIncurrID = dbWrite($trans12, "etradebanc", true);

      }

     }
     if($this->ToFees) {

      $chargePercent = $toAreaRow['feepercent'] / 2;
      $chargePercentFrom = $fromAreaRow['feepercent'] / 2;
      $chargePercentTo = $toAreaRow['feepercent'] / 2;

      if($this->ToRow['over_payment'] > 0) {

      	$chargePercent = $toAreaRow['feepercent'] / 2;

      	if($this->ToFees > $this->ToRow['over_payment']) {

      		dbWrite("update members set over_payment = 0 where memid = " . $this->To);
      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->To."''".$this->ToRow['licensee']."','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','".$this->ToFees."','" . $this->ToRow['over_payment'] . "','".$sellid."')";
      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->ToFees."','" . $this->ToRow['over_payment'] . "','".$sellid."')";
          	$this->FeesIncurrID = dbWrite($trans12);

	      	$checkSQL = dbRead("select transactions.* from transactions where dollarfees < '0' and dis_date > '2005-06-30' and type in (5,6) and memid = " . $this->ToRow['memid'] . " order by dis_date DESC limit 1");

	      	if(mysql_num_rows($checkSQL)) {

				dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->From . "','" . $this->FromDBDisDate . "','" . $this->ToRow['over_payment'] . "','0','" . $chargePercentFrom . "','" . $this->FromRow['licensee'] . "','5','0')", DB_NAME);
				   $rrow[memid] = $this->To;
				   $rrow[licensee] = $this->ToRow['licensee'];
				   $rrow[to_memid] = $this->FromRow['memid'];
				   $rrow[to_licensee] = $this->FromRow['licensee'];
				   $feesPaidIDArray[] = $this->interFee($rrow, $this->ToRow['over_payment'], $this->FeesIncurrID, '',true);

      		}

      		if(!strstr($_SESSION['Country']['trustacc'], $this->FromRow['memid'])) {

      			$this->feesIncurred($this->FromRow['memid'], $this->ToRow['over_payment'], $ittTransfer);

      		}

      	} else {

      		dbWrite("update members set over_payment = over_payment - " . $this->ToFees . "  where memid = " . $this->To);
      		//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id) values ('".$this->To."''".$this->ToRow['licensee']."','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','".$this->ToFees."','" . $this->ToFees . "','".$sellid."')";
      		$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,fee_paid,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->ToFees."','" . $this->ToFees . "','".$sellid."')";
      		$this->FeesIncurrID = dbWrite($trans12);

			dbWrite("insert into feespaid (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid) values ('" . $this->From . "','" . $this->FromDBDisDate . "','" . $this->ToFees . "','0','" . $chargePercentFrom . "','" . $this->FromRow['licensee'] . "','5','0')", DB_NAME);

				   $rrow[memid] = $this->To;
				   $rrow[licensee] = $this->ToRow['licensee'];
				   $rrow[to_memid] = $this->FromRow['memid'];
				   $rrow[to_licensee] = $this->FromRow['licensee'];
				   $feesPaidIDArray[] = $this->interFee($rrow, $this->ToFees, $this->FeesIncurrID, '',true);

      		if(!strstr($_SESSION['Country']['trustacc'], $this->FromRow['memid'])) {

      			$this->feesIncurred($this->FromRow['memid'], $this->ToFees, $ittTransfer);

      		}


      	}

      } else {

      	//$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','".$this->ToFees."','".$sellid."')";
      	$trans12 = "insert into feesincurred (memid,licensee,percent,date,to_memid,to_licensee,to_percent,fee_amount,trans_id) values ('".$this->To."','".$this->ToRow['licensee']."','" . $chargePercentTo . "','".$this->FromDBDisDate."','$this->From','".$this->FromRow['licensee']."','" . $chargePercentFrom . "','".$this->ToFees."','".$sellid."')";
      	$this->FeesIncurrID = dbWrite($trans12, "etradebanc", true);

      }

     }

    }

   }

  }

		function interFee($FIrow, $pay, $fIncurrID = false, $tID = false, $re = false) {

			global $ebancAdmin;

		    $fromAreaSQL = dbRead("select " . DB_TABLE_AREA . ".* from " . DB_TABLE_AREA . " where FieldID = " . $FIrow['licensee']);
		    $toAreaSQL = dbRead("select " . DB_TABLE_AREA . ".* from " . DB_TABLE_AREA . " where FieldID = " . $FIrow['to_licensee']);

		    $fromAreaRow = mysql_fetch_assoc($fromAreaSQL);
		    $toAreaRow = mysql_fetch_assoc($toAreaSQL);

			//if($fromAreaRow['CID'] == 1 || $fromAreaRow['CID'] == 12) {
			if($this->FromCountry['inter_per']) {

				$perTotal = 0;

				if($re) {
					if($fromAreaRow['inter'] == 'Y') {
						$perTotal += $fromAreaRow['percent']/2;
					}
					if($toAreaRow['inter'] == 'Y') {
						$perTotal += $toAreaRow['percent']/2;
					}
				} else {
					if($fromAreaRow['inter'] == 'Y') {
						$perTotal += $FIrow['percent'];
					}
					if($toAreaRow['inter'] == 'Y') {
						$perTotal += $FIrow['to_percent'];
					}
				}

				//if($FIrow['to_memid'] == 29253 && $FIrow['fee_amount'] == 11 && $FIrow['percent'] == 50) {
				//if($FIrow['to_memid'] == $this->FromCountry['reserveacc'] && $FIrow['fee_amount'] == $this->FromCountry['admin_fee'] && $FIrow['percent'] == 50 && $this->FromCountry['countryID'] == 1) {
				//if($FIrow['to_memid'] == $_SESSION['Country']['adminacc'] && $FIrow['fee_amount'] == $_SESSION['Country']['admin_fee'] && $FIrow['percent'] == 50) {
				if($FIrow['to_memid'] == $_SESSION['Country']['adminacc'] && $FIrow['percent'] == 50) {
				  $per = $_SESSION['Country']['inter_per'];
				  if($fromAreaRow['inter'] == 'Y') {
				  	$perTotal = 50;
				  } else {
				  	$perTotal = 0;
				  }
				  //$per = 20;
				  //$perTotal = 50;
				  //$amPer = ($pay/11)*100;
				  //$am = (3.30/100)*$amPer;
				  //$ebancAdmin->dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $FIrow['memid'] . "','" . date("Y-m-d") . "','". $am ."','0','0','" . $FIrow['licensee'] . "','8','" . $fIncurrID . "','" . $tID . "')", DB_NAME, true);
				} elseif($REQUEST['plp']) {
				  $per = 100;
				} else {
				  //$per = 20;
				  $per = $this->FromCountry['inter_per'];
				}

				$interFees = ($pay*((100-$perTotal)/100)*($per/100));
				$yy = dbWrite("insert into " . DB_TABLE_FEESPAID . " (memid,paymentdate,amountpaid,deducted_fees,percent,area,type,feesincurrid,transID) values ('" . $FIrow['memid'] . "','" . date("Y-m-d") . "','" . $interFees . "','0','" . $perTotal . "','" . $FIrow['licensee'] . "','9','" . $fIncurrID . "','" . $tID . "')", DB_NAME, true);

				return $yy;
			}
		}

 }

?>