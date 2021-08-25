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
 //$query = dbRead("select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 and members.memid = 9124 group by invoice.memid");
 $query = dbRead("select monthlycc.*, credit_transactions.memid, credit_transactions.amount as amount2 from monthlycc, credit_transactions where monthlycc.refer = credit_transactions.FieldID");

// loop around them.

 while($row = mysql_fetch_assoc($query)) {

  // cash fees
$ff++;


     if($row[result] == "APPROVED") {

      // successfull.

      $date = date("Y-m-d");
      dbWrite("update credit_transactions set success='Yes', response_code='$row[response_code]', response_text='$row[response_text]', card_type='$row[card_type]' where FieldID='$row[refer]'");

 	  $feePay = new feePayment($row[memid]);
 	  $feePay->payFees($_SESSION['feePayment']['memberRow'], $row['amount2'], 1, 5, '', "Cash Fees Payment");
//echo $row[memid]."-".$row['amount2']."-".$row[refer]."- Approved<br>";
     } elseif($row[result] == "DECLINED") {

      // unsuccessfull.

      dbWrite("update credit_transactions set success='No', response_code='$row[response_code]', response_text='$row[response_text]', card_type='$row[card_type]' where FieldID='$row[refer]'");
      dbWrite("insert into memcards (memid,date,type,userid) values ('$row[memid]','$date','3','180')");
//echo $row[memid]."-".$row['amount2']."-".$row[refer]."- Declined<br>";
     }

 }
 echo $ff;
?>
