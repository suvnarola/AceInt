<?

// monthly credit card charge.

 $NoSession = true;
 //$Debug = 1;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");

 $ebancAdmin = new ebancSuite();

 $MerchantID = "ebt0022";
 //$Debug = 1;
 $lastmonth = date("Y-m", mktime(1,1,1,date("m")-1,1,date("Y")));
 $thismonth = date("Y-m", mktime(1,1,1,date("m"),1,date("Y")));

 if($Debug) {
  echo "<pre>";
 }

// get the members out that we want to charge.

 //$query = dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members where (members.memid = invoice.memid) and (members.accountno != '') and (members.paymenttype = 'Visa' or members.paymenttype = 'Bankcard' or members.paymenttype = 'Mastercard' or members.paymenttype = 'Amex') and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 20) and (date like '$lastmonth-%')) group by invoice.memid");
 $query = dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 and members.memid = 9124 group by invoice.memid");

// loop around them.

 while($row = mysql_fetch_assoc($query)) {

  // cash fees

  $query3 = dbRead("select sum(dollarfees) as feespaid from transactions where memid='$row[memid]' and dollarfees < 0 and dis_date like '$thismonth-%'");
  $row3 = mysql_fetch_assoc($query3);

  $ChargeAmount = $row[feesowe] + $row3[feespaid];

  $exdate_temp = explode("/", $row[expires]);

  $exdate1 = $exdate_temp[0];
  $exdate2 = $exdate_temp[1];
  $thisyear = date("y");
  $thismonth2 = date("m");

  if(($exdate2 > $thisyear) or (($exdate1 >= $thismonth2) and ($exdate2 == $thisyear))) {

   if($ChargeAmount > 5) {

    $amount = $ChargeAmount;
    $ccamount = $ChargeAmount*100;

    // purchase order number, creditcard and expiry date

	if(!$Debug) {
     $ponum = add_temp_trans($row[memid],$row[feesowe]);
    }

    $cc1 = substr($row[accountno], 0, 4);
    $cc2 = substr($row[accountno], 4, 4);
    $cc3 = substr($row[accountno], 8, 4);
    $cc4 = substr($row[accountno], 12, 4);

	if($Debug) {
	 echo "$row[companyname] [$row[memid]]\r\n $$amount\r\n $row3[feespaid]\r\n\r\n";
	} else {
     $SecureResponse = Process_Credit_Card($MerchantID,$ccamount,$ponum,$cc1,$cc2,$cc3,$cc4,$exdate1,$exdate2,$row[companyname]);
	}

print_r($SecureResponse) ;
    // see if the credit card processed.

    // update type of transaction.
    if(!$Debug) {
     dbWrite("update credit_transactions set type='5' where FieldID='$ponum'");
	}

	if(!$Debug) {

     if($SecureResponse[successfull] == 1) {

      // successfull.

      $date = date("Y-m-d");
      dbWrite("update credit_transactions set success='Yes', amount='$amount', response_code='$SecureResponse[response_code]', response_text='$SecureResponse[response_text]', sp_trans_id='$SecureResponse[txn_id]', card_type='$SecureResponse[card_type]', card_name='$SecureResponse[optional_info]' where FieldID='$SecureResponse[ponum]'");

 	  $feePay = new feePayment($row[memid]);
 	  $feePay->payFees($_SESSION['feePayment']['memberRow'], $amount, 1, 5, '', "Cash Fees Payment");

     } elseif($SecureResponse[successfull] == 2) {

      // unsuccessfull.

      dbWrite("update credit_transactions set success='No', amount='$amount', response_code='$SecureResponse[response_code]', response_text='$SecureResponse[response_text]', sp_trans_id='$SecureResponse[txn_id]', card_type='$SecureResponse[card_type]', card_name='$SecureResponse[optional_info]' where FieldID='$SecureResponse[ponum]'");
      dbWrite("insert into memcards (memid,date,type,userid) values ('$row[memid]','$date','3','180')");

     }

	}

   }

  }

  if(!$Debug) {
   sleep(2);
  }

 }

 if($Debug) {
  echo "</pre>";
 }

?>
