<?php

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("/home/etxint/admin.etxint.com/includes/class.html.mime.mail.inc");
 include("/home/etxint/admin.etxint.com/includes/modules/class.phpmailer.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");


$dbtq = dbRead("select memid from wine");

while($row = mysql_fetch_assoc($dbtq)) {

$amount = 66;
$memberacc = $row['memid'];
$details = "Reversal Re Prom";

 if($memberacc) {

  $ebancAdmin = new ebancSuite();

  $feePay = new feePayment($memberacc);

  $feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $amount, '', $details);

 }

}
