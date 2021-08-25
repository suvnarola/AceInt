<?

/**
 * Credit Card Fee Payment.
 *
 * @package E Banc Administration Site
 * @author Antony Puckey
 * @copyright Copyright 2005, RDI Host Pty Ltd
 *
 */

/**
 *  DEFINE(MERCHANT_ID, "ebt0001");
 *  DEFINE(MERCHANT_PASSWORD, "jasu2ilk");
 */
//include("/home/etxint/admin.etxint.com/includes/ebancAdminSessions.php");
include( "/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php" );

	die ( 'disabled for the moment ') ;

$ebancAdmin = new ebancSuite();

DEFINE( MERCHANT_ID , "ebt0022" );
DEFINE( MERCHANT_PASSWORD , "k87jkqdam" );

include( "/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php" );
include( "/home/etxint/admin.etxint.com/includes/modules/class.xmlCreditCard.php" );

$lastmonth = date( "Y-m" , mktime( 1 , 1 , 1 , date( "m" ) - 1 , 1 , date( "Y" ) ) );
$thismonth = date( "Y-m" , mktime( 1 , 1 , 1 , date( "m" ) , 1 , date( "Y" ) ) );

$fieldList = 'invoice.memid as memid, ';
$fieldList .= 'members.licensee as licensee, ';
$fieldList .= 'members.accountno as accountno, ';
$fieldList .= 'members.companyname as companyname, ';
$fieldList .= 'members.expires as expires, ';
$fieldList .= 'sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe';
$tables = 'invoice, members, tbl_admin_payment_types';

// $sql = "select invoice.memid as memid, members.licensee as licensee, members.accountno as accountno, members.companyname as companyname, members.expires as expires, sum((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) as feesowe from invoice, members, tbl_admin_payment_types where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 group by invoice.memid" ;
$sql = "select $fieldList from $tables where (members.memid = invoice.memid) and (members.paymenttype = tbl_admin_payment_types.FieldID) and (members.accountno != '') and tbl_admin_payment_types.ccrun='1' and ((((invoice.currentfees + invoice.overduefees) + invoice.currentpaid) > 5) and (date like '$lastmonth-%')) and members.CID = 1 group by invoice.memid";

// die( $sql ) ;

$query = $ebancAdmin->dbRead( $sql );

$csvHeader = "memid,licensee,accountno,companyname,expires,feesowe,feespaid,ChargeAmount";

$csvArray[] = $csvHeader ;

while ($row = mysql_fetch_assoc( $query ))
{

	$tempRow = $row;

	$query3 = $ebancAdmin->dbRead( "select sum(dollarfees) as feespaid from transactions where memid='$row[memid]' and dollarfees < 0 and dis_date like '$thismonth-%'" );
	$row3 = mysql_fetch_assoc( $query3 );

	$tempRow[ 'feespaid' ] = $row3[ 'feespaid' ];

	$ChargeAmount = $row[ feesowe ] + $row3[ feespaid ];

	$tempRow[ 'ChargeAmount' ] = $ChargeAmount;

	$csvArray[] = '"' . implode( '","' , $tempRow ) . '"' ;

	continue ;

//	echo 'adfasdf' ;
//
//	exit ;

//	$exdate_temp = explode( "/" , $row[ expires ] );
//
//	$exdate1 = $exdate_temp[ 0 ];
//	$exdate2 = $exdate_temp[ 1 ];
//	$thisyear = date( "y" );
//	$thismonth2 = date( "m" );
//
//	if ( ( $exdate2 > $thisyear ) or ( ( $exdate1 >= $thismonth2 ) and ( $exdate2 == $thisyear ) ) )
//	{
//
//		if ( $ChargeAmount > 5 )
//		{
//
//			$amount = $ChargeAmount;
//			$ccamount = $ChargeAmount * 100;
//
//			$cc1 = substr( $row[ accountno ] , 0 , 1 );
//			if ( $cc1 == 4 )
//			{
//				$cc = "Visa";
//			}
//			else
//			{
//				$cc = "Mastercard";
//			}
//
//
//			$ccPayment = new feePayment( $row[ 'memid' ] );
//			$_SESSION[ 'feePayment' ][ 'poNumber' ] = $ccPayment->addCCTrans( $_SESSION[ 'feePayment' ][ 'memberRow' ][ 'memid' ] , 5 );
//			$_SESSION[ 'feePayment' ][ 'feesOwing' ] = $ChargeAmount;
//			$_SESSION[ 'feePayment' ][ 'chargeAmount' ] = $ChargeAmount;
//			$_SESSION[ 'feePayment' ][ 'optionalInfo' ] = $_REQUEST[ 'optionalInfo' ];
//			$_SESSION[ 'feePayment' ][ 'ccNumber' ] = $row[ 'accountno' ];
//			$_SESSION[ 'feePayment' ][ 'ccType' ] = $cc;
//			$_SESSION[ 'feePayment' ][ 'cvvNumber' ] = $_REQUEST[ 'cvvNumber' ];
//			$_SESSION[ 'feePayment' ][ 'expireMonth' ] = $exdate1;
//			$_SESSION[ 'feePayment' ][ 'expireYear' ] = $exdate2;
//
//			$secureResponse = Array();
//
//			$secureResponse = $ccPayment->ccPayment( $_SESSION[ 'feePayment' ][ 'memberRow' ][ 'memid' ] , $_SESSION[ 'feePayment' ][ 'chargeAmount' ] , $_SESSION[ 'feePayment' ][ 'ccNumber' ] , $_SESSION[ 'feePayment' ][ 'expireMonth' ] , $_SESSION[ 'feePayment' ][ 'expireYear' ] , $_SESSION[ 'feePayment' ][ 'cvvNumber' ] , $_SESSION[ 'feePayment' ][ 'ccType' ] , $_SESSION[ 'feePayment' ][ 'poNumber' ] );
//
//			if ( $secureResponse[ 'APPROVED' ] == "Yes" )
//			{
//				//echo $row['memid']."Approved<br>";
//				/**
//				 * Transaction has been successfull.
//				 */
//
//				$ebancAdmin->dbWrite( "update credit_transactions set success = 'Yes', amount = '" . $_SESSION[ 'feePayment' ][ 'chargeAmount' ] . "', response_code = '" . $secureResponse[ 'RESPONSECODE' ] . "', response_text = '" . $secureResponse[ 'RESPONSETEXT' ] . "', sp_trans_id = '" . $secureResponse[ 'TXNID' ] . "', card_type = '" . $secureResponse[ 'CARDDESCRIPTION' ] . "', card_name = '" . $_SESSION[ 'feePayment' ][ 'optionalInfo' ] . "' where FieldID = '" . $_SESSION[ 'feePayment' ][ 'poNumber' ] . "'" );
//
//				$ccPayment->payFees( $_SESSION[ 'feePayment' ][ 'memberRow' ] , $_SESSION[ 'feePayment' ][ 'chargeAmount' ] , 1 , 5 );
//
//
//			}
//			elseif ( $secureResponse[ 'APPROVED' ] == "No" )
//			{
//				//echo $row['memid']."Declined<br>";
//				/**
//				 * Transaction has been unsuccessfull.
//				 */
//
//				$ebancAdmin->dbWrite( "update credit_transactions set success = 'No', amount = '" . $_SESSION[ 'feePayment' ][ 'chargeAmount' ] . "', response_code = '" . $secureResponse[ 'RESPONSECODE' ] . "', response_text = '" . $secureResponse[ 'RESPONSETEXT' ] . "', sp_trans_id = '" . $secureResponse[ 'TXNID' ] . "', card_type = '" . $secureResponse[ 'CARDDESCRIPTION' ] . "', card_name='" . $_SESSION[ 'feePayment' ][ 'optionalInfo' ] . "' where FieldID = '" . $_SESSION[ 'feePayment' ][ 'poNumber' ] . "'" );
//
//
//			}
//
//			//displayReceipt();
//
//			unset( $_SESSION[ 'feePayment' ] );
//
//		}
//	}
//	sleep( 2 );

}

$csv = implode( "\n" , $csvArray ) ;

die( $csv) ;

echo "<pre>" . print_r( $csvArray , true ) . "</pre>";

?>
