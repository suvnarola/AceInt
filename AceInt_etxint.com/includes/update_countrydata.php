<html>
<head>
<title>E Banc Trade - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK REL="STYLESHEET" type="text/css" href="includes/styles.css">
<script language="javascript" type="text/javascript" src="includes/default.js?cache=no"></script>
<script LANGUAGE="JavaScript">
</script>
</head>
<form method="POST" action="body.php?page=update_countrydata&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

$tabarray = array('Country Update','Add Country');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Country Update") {

  country();

} elseif($_GET[tab] == "Add Country") {

  countryadd();
}

?>

</form>
</html>

<?
function country() {

$yesno = array('Y' => 'Yes', 'N' => 'No');
$yesno2 = array('Yes' => 'Yes', 'No' => 'No');

if($_REQUEST['data'])  {

$dbquery1 = dbRead("select * from countrydata where CID = ".$_REQUEST['data']."");

$row = mysql_fetch_assoc($dbquery1);

?>
<body onload="javascript:setFocus('countryadd','name');">
<font face="Arial">
<form method="post" action="/general.php" name="changecountry">

<input type="hidden" name="CID" value="<?= $row['CID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3" height="4089">
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Country Data Edit</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="40%" height="19"><b>Country ID:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="19"><?= $row['countryID'] ?></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Tax Invoice Data</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Tax Invoice:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><font face="Verdana"><input type="text" name="tname" size="30" value="<?= $row['tname'] ?>"></font></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Account Number:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="acno" size="30" value="<?= $row['acno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Tax Invoice Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tdate" size="30" value="<?= $row['tdate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Tax Invoice Number:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tno" size="30" value="<?= $row['tno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Cash Fees Owing for Current Month:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tdet" size="30" value="<?= $row['tdet'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="100"><b>A breakdown of fees is available on your statement. This is attached if you received this invoice by mail, or can be downloaded from the members section of the corporate website by online members.:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="100">
    <textarea name="tcom" size="30" rows="6" value="<?= $row['tcom'] ?>" cols="35"><?= $row['tcom'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Sub Total:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tsub" size="30" value="<?= $row['tsub'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Plus GST:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tgst" size="30" value="<?= $row['tgst'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>TOTAL</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="ttot" size="30" value="<?= $row['ttot'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>PLUS OUTSTANDING FEES:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tout" size="30" value="<?= $row['tout'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Less Fees Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tpaid" size="30" value="<?= $row['tpaid'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>TOTAL NOW OWING:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input class ="test" type="text" name="tnow" size="30" value="<?= $row['tnow'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>Payment is required within 7 days of receipt of this Tax Invoice, unless it is less than $20 or an ongoing debit arrangement is in place.:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="trequ" size="30" rows="5" value="<?= $row['trequ'] ?>" cols="35"><?= $row['trequ'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="38"><b>Nominated account will be debited on the 21st of the month:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="38"><input type="text" name="tnom" size="30" value="<?= $row['tnom'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="38"><b>A late payment fee may apply:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="38"><input type="text" name="tlate" size="30" value="<?= $row['tlate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="38"><b>Hungary:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <?
        ob_start();
        eval(" ?>".$row['thu']."<? ");
        $output = ob_get_contents();
	    ob_end_clean();
    ?>
    <textarea name="thu" size="30" rows="5" cols="35"><?= $output ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Company Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="comna" size="30" value="<?= $row['comna'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Amount Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="amow" size="30" value="<?= $row['amow'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Remittance:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="trem" size="30" value="<?= $row['trem'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Amount Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tampa" size="30" value="<?= $row['tampa'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Statement Data</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>STATEMENT:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sname" size="30" value="<?= $row['sname'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Statement Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sdate" size="30" value="<?= $row['sdate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="stdate" size="30" value="<?= $row['stdate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Local Office:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="locoff" size="30" value="<?= $row['locoff'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Buy:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sbuy" size="30" value="<?= $row['sbuy'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Sell:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="ssell" size="30" value="<?= $row['ssell'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Trade Balance:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="stbal" size="30" value="<?= $row['stbal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Cash Fees:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="scash" size="30" value="<?= $row['scash'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Cash Balance:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="scbal" size="30" value="<?= $row['scbal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Opening Balance:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sob" size="30" value="<?= $row['sob'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>TOTALS:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sto" size="30" value="<?= $row['sto'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Current Facility:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sfac" size="30" value="<?= $row['sfac'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Real Estate Facility:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="srfac" size="30" value="<?= $row['srfac'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Nett Position:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="snett" size="30" value="<?= $row['snett'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Cash Fees:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="scas" size="30" value="<?= $row['scas'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Now Due:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="sdue" size="30" value="<?= $row['sdue'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Prepaid:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="spai" size="30" value="<?= $row['spai'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>Authorisation is required for all transactions of $200 and over:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="auth" size="30" rows="5" value="<?= $row['auth'] ?>" cols="35"><?= $row['auth'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>24 HOURS NOTICE MUST BE GIVEN FOR ALL TRANSACTIONS OF $3000 AND OVER BY LODGEMENT OF AN INTENTION TO TRADE (ITT) FORM:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="itt" size="30"  rows="5" value="<?= $row['itt'] ?>" rows="1" cols="35"><?= $row['itt'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Receipt Data</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Receipt:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="receipt" size="30" value="<?= $row['receipt'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Receipt Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="re_date" size="30" value="<?= $row['re_date'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Receipt Number:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="re_no" size="30" value="<?= $row['re_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Received from:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="re_from" size="30" value="<?= $row['re_from'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>The sum of:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="re_sum" size="30" value="<?= $row['re_sum'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="38"><b>Being for conversion to E Banc Trade Dollars to the amount of:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="38"><input type="text" name="re_to" size="30" value="<?= $row['re_to'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>New Member Pack Details</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>MEMBERS PERSONAL TRADING ACCOUNT DETAILS:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_company" size="30" value="<?= $row['n_company'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Contact Name is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_contact" size="30" value="<?= $row['n_contact'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Account Number is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_accno" size="30" value="<?= $row['n_accno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Online User Name is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_user" size="30" value="<?= $row['n_user'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Online Password is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_pass" size="30" value="<?= $row['n_pass'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Facility is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_facility" size="30" value="<?= $row['n_facility'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Your Customer Support is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_customer" size="30" value="<?= $row['n_customer'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Authorisation Call-in Number is:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_auth" size="30" value="<?= $row['n_auth'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Keep Secure</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_secure" size="30" value="<?= $row['n_secure'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Entered on:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_enton" size="30" value="<?= $row['n_enton'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Entered by:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_entby" size="30" value="<?= $row['n_entby'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Fee %:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_fee" size="30" value="<?= $row['n_fee'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Joining Fee Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="n_paid" size="30" value="<?= $row['n_paid'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Email - New Member</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Dear:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="dear" size="30" value="<?= $row['dear'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>RE:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="re" size="30" value="<?= $row['re'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>You are receiving this email because either you have recently joined E Banc Trade, or, as an existing member, you have reopened your account in a different name.:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="parta" size="30"  rows="5" value="<?= $row['parta'] ?>" rows="1" cols="35"><?= $row['parta'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>Would you please check your account details below.  If there are any discrepancies, or you wish to change your directory message, please notify Head Office, Australia on:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="partb" size="30"  rows="5" value="<?= $row['partb'] ?>" rows="1" cols="35"><?= $row['partb'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>and ask for the Membership Accounts Department, or simply reply to this email.:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="partc" size="30"  rows="5" value="<?= $row['partc'] ?>" rows="1" cols="35"><?= $row['partc'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Email - Online Details</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Username:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="muser" size="30" value="<?= $row['muser'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Password:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="mpass" size="30" value="<?= $row['mpass'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="84"><b>At your request, we are forwarding you a reminder of your username and password on your E Banc Trade Account.:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <textarea name="upparta" size="30" value="<?= $row['upparta'] ?>" rows="5" cols="35"><?= $row['upparta'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="171"><b>Access this site by clicking on this link, <a href="https://secure.ebanctrade.com/members">http://secure.ebanctrade.com/members</a>, or pasting it into your browser window. Enter your username and password when requested, and you can then access the members section of the corporate website. This provides access to your account details, the Bid'n'Buy site and the classifieds. You can also update your membership information, pay fees online and make trade transfers.:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="171">
    <textarea name="uppartb" rows="10" cols="35"><?= $row['uppartb'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Email - Tax Invoice</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="684"><b>Attached is your current Tax Invoice.  You may need Acrobat Reader to view this Tax Invoice.  You can download it for free from: <a href="http://www.adobe.com/products/acrobat/readstep.html">http://www.adobe.com/products/acrobat/readstep.html</a>.<br><br>Current and Past Statements<br>You can view your current and past statements from the Member’s section of the E Banc Trade website, <a href="https://www.ebanctrade.com/members">https://www.ebanctrade.com/members</a> by entering your username and password.  A list of functions is available to you from the left side of the screen.<br><br>E Banc Trader – our monthly newsletter<br>The current newsletter is now available and can also be viewed by selecting the appropriate link from the left side of the home page.  If you would like to promote your goods and services in this newsletter, please email them to <a href="mailto:publications@ebanctrade.com">publications@ebanctrade.com</a> by the 20th of each month.<br><br>Membership Directory Updates<br>Remember to download the latest Membership Directory from the website for the most up to date information on goods and services provided by the E Banc Trade Exchange.  A listing of new members for the past month is available from the left hand selection once you log in to the Member’s section. A hardcopy of the directory is available from head office for a cash fee of $10.  Local directories are also available from Head Office.<br><br>Regards<br><br><br><br>E Banc Trade Membership Accounts:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="684">
    <textarea name="emtax" rows="30" cols="35"><?= $row['emtax'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Head Office:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tpl_headoffice" size="30" value="<?= $row['tpl_headoffice'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Int'l Head Office:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tpl_intheadoffice" size="30" value="<?= $row['tpl_intheadoffice'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>Office Contacts:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tpl_contacts" size="30" value="<?= $row['tpl_contacts'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>work the dream:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="tpl_workthedream" size="30" value="<?= $row['tpl_workthedream'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>INTER HEAD OFFICE:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="let_inter" size="30" value="<?= $row['let_inter'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>HEAD OFFICE:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="let_head" size="30" value="<?= $row['let_head'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>POSTAL ADDRESS:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="let_postal" size="30" value="<?= $row['let_postal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="22"><b>OFFICE CONTACT:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="22"><input type="text" name="let_office" size="30" value="<?= $row['let_office'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="20"><b>Let Business:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="20"><input type="text" name="let_top" size="30" value="<?= $row['let_top'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Account No:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_acc" size="30" value="<?= $row['let_acc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="164"><b>Confidentiality:
                      The information contained in this email and any attachments
                      is confidential and/or privileged.<br>
                      If the reader is not the intended recipient named above,
                      or a representative of the intended recipient, you are <br>
                      advised</font><font face="Arial, Helvetica, sans-serif" size="1">
                      that any review, dissemination or copying of this email
                      and any attachments is prohibited. If you have<br>
                      received this email in error, please notify the sender by
                      email, telephone or fax and return it to the sender.</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="164">
    <textarea name="tpl_disclaimer" rows="10" cols="35"><?= $row['tpl_disclaimer'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading" height="19"><b>Classified Rules</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="164"><b>Class Rules:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="164">
    <textarea name="clas_rules" rows="10" cols="35"><?= $row['clas_rules'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Email System</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>If you cannot view the below email,:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_top" size="30" value="<?= $row['em_top'] ?>" rows="5" cols="35"><?= $row['em_top'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>please select here:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_sel" size="30" value="<?= $row['em_sel'] ?>" rows="5" cols="35"><?= $row['em_sel'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Happy Trading from Empire Trade Management and Associates:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_bot" size="30" value="<?= $row['em_bot'] ?>" rows="5" cols="35"><?= $row['em_bot'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>This message is sent to online members of Empire Trade. If you feel that your email was obtained by error, or you no longer wish to receive membership promotions, please <a href="mailto:<?= $pubEmailObj->pubemail ?>?subject=Please%20remove%20me%20from%20your%20email%20list">select here</a> and hit send.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_rem" size="30" value="<?= $row['em_rem'] ?>" rows="5" cols="35"><?= $row['em_rem'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Agent Info Email:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_agent" size="30" value="<?= $row['em_agent'] ?>" rows="5" cols="35"><?= $row['em_agent'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%" height="40">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%" height="40"><input type="submit" value="Change Data" name="changearea" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changecountry" value="1">

</form>
</font>
</body>
<?
die;
}

 if($_REQUEST[changecountry])  {

  $query = dbRead("select * from countrydata where CID = '".$_REQUEST['CID']."'");
  $row = mysql_fetch_assoc($query);

  //if($_REQUEST['locationID'] < 1) {
    //$_REQUEST['locationID'] == '0';
  //}

  //if($_REQUEST['LocationID2'] < 1) {
    //$_REQUEST['LocationID2'] == '0';
  //}

  foreach($_REQUEST as $key => $value) {

     $NIPageArray = array(
       'phpbb2mysql_data' => 'phpbb2mysql_data',
       'page' => 'page',
       'Client' => 'Client',
       'pageno' => 'pageno',
       'tab' => 'tab',
       'Update' => 'Update',
       'main' => 'main',
       'changemember' => 'changemember',
       'PHPSESSID' => 'PHPSESSID',
       'countryID' => 'countryID',
       'fieldid' => 'fieldid',
       'LocationID' => 'LocationID',
       'changearea' => 'changearea',
       'changecountry' => 'changecountry'
    );

    if(encode_text2($_REQUEST[$key]) != $row[$key]) {
     if($key != $NIPageArray[$key]) {
      $logdata[$key] = array($row[$key],encode_text2($value));
     }
    }
  }

  add_kpi2(4,'0','0',$row['CID'],$logdata);

  $SQL = new dbCreateSQL();

  $SQL->add_table("countrydata");

  $SQL->add_item("n_company", encode_text2($_REQUEST['n_company']));
  $SQL->add_item("n_contact", encode_text2($_REQUEST['n_contact']));
  $SQL->add_item("n_accno", encode_text2($_REQUEST['n_accno']));
  $SQL->add_item("n_user", encode_text2($_REQUEST['n_user']));
  $SQL->add_item("n_pass", encode_text2($_REQUEST['n_pass']));
  $SQL->add_item("n_facility", encode_text2($_REQUEST['n_facility']));
  $SQL->add_item("n_customer", encode_text2($_REQUEST['n_customer']));
  $SQL->add_item("n_auth", encode_text2($_REQUEST['n_auth']));
  $SQL->add_item("n_secure", encode_text2($_REQUEST['n_secure']));
  $SQL->add_item("n_enton", encode_text2($_REQUEST['n_enton']));
  $SQL->add_item("n_entby", encode_text2($_REQUEST['n_entby']));
  $SQL->add_item("n_fee", encode_text2($_REQUEST['n_fee']));
  $SQL->add_item("n_paid", encode_text2($_REQUEST['n_paid']));
  $SQL->add_item("tname", encode_text2($_REQUEST['tname']));
  $SQL->add_item("sname", encode_text2($_REQUEST['sname']));
  $SQL->add_item("acno", encode_text2($_REQUEST['acno']));
  $SQL->add_item("tdate", encode_text2($_REQUEST['tdate']));
  $SQL->add_item("locoff", encode_text2($_REQUEST['locoff']));
  $SQL->add_item("tno", encode_text2($_REQUEST['tno']));
  $SQL->add_item("tdet", encode_text2($_REQUEST['tdet']));
  $SQL->add_item("tcom", encode_text2($_REQUEST['tcom']));
  $SQL->add_item("tsub", encode_text2($_REQUEST['tsub']));
  $SQL->add_item("tgst", encode_text2($_REQUEST['tgst']));
  $SQL->add_item("ttot", encode_text2($_REQUEST['ttot']));
  $SQL->add_item("tout", encode_text2($_REQUEST['tout']));
  $SQL->add_item("tpaid", encode_text2($_REQUEST['tpaid']));
  $SQL->add_item("tnow", encode_text2($_REQUEST['tnow']));
  $SQL->add_item("trequ", encode_text2($_REQUEST['trequ']));
  $SQL->add_item("tnom", encode_text2($_REQUEST['tnom']));
  $SQL->add_item("tlate", encode_text2($_REQUEST['tlate']));
  $SQL->add_item("thu", encode_text2($_REQUEST['thu']));
  $SQL->add_item("sdate", encode_text2($_REQUEST['sdate']));
  $SQL->add_item("stdate", encode_text2($_REQUEST['stdate']));
  $SQL->add_item("sbuy", encode_text2($_REQUEST['sbuy']));
  $SQL->add_item("ssell", encode_text2($_REQUEST['ssell']));
  $SQL->add_item("stbal", encode_text2($_REQUEST['stbal']));
  $SQL->add_item("sob", encode_text2($_REQUEST['sob']));
  $SQL->add_item("sto", encode_text2($_REQUEST['sto']));
  $SQL->add_item("sfac", encode_text2($_REQUEST['sfac']));
  $SQL->add_item("srfac", encode_text2($_REQUEST['srfac']));
  $SQL->add_item("snett", encode_text2($_REQUEST['snett']));
  $SQL->add_item("scash", encode_text2($_REQUEST['scash']));
  $SQL->add_item("scas", encode_text2($_REQUEST['scas']));
  $SQL->add_item("scbal", encode_text2($_REQUEST['scbal']));
  $SQL->add_item("sdue", encode_text2($_REQUEST['sdue']));
  $SQL->add_item("spai", encode_text2($_REQUEST['spai']));
  $SQL->add_item("auth", encode_text2($_REQUEST['auth']));
  $SQL->add_item("itt", encode_text2($_REQUEST['itt']));
  $SQL->add_item("comna", encode_text2($_REQUEST['comna']));
  $SQL->add_item("amow", encode_text2($_REQUEST['amow']));
  $SQL->add_item("trem", encode_text2($_REQUEST['trem']));
  $SQL->add_item("tampa", encode_text2($_REQUEST['tampa']));
  $SQL->add_item("receipt", encode_text2($_REQUEST['receipt']));
  $SQL->add_item("re_date", encode_text2($_REQUEST['re_date']));
  $SQL->add_item("re_no", encode_text2($_REQUEST['re_no']));
  $SQL->add_item("re_from", encode_text2($_REQUEST['re_from']));
  $SQL->add_item("re_sum", encode_text2($_REQUEST['re_sum']));
  $SQL->add_item("re_to", encode_text2($_REQUEST['re_to']));
  $SQL->add_item("dear", encode_text2($_REQUEST['dear']));
  $SQL->add_item("re", encode_text2($_REQUEST['re']));
  $SQL->add_item("parta", encode_text2($_REQUEST['parta']));
  $SQL->add_item("partb", encode_text2($_REQUEST['partb']));
  $SQL->add_item("partc", encode_text2($_REQUEST['partc']));
  $SQL->add_item("muser", encode_text2($_REQUEST['muser']));
  $SQL->add_item("mpass", encode_text2($_REQUEST['mpass']));
  $SQL->add_item("upparta", encode_text2($_REQUEST['upparta']));
  $SQL->add_item("uppartb", encode_text2($_REQUEST['uppartb']));
  $SQL->add_item("emtax", encode_text2($_REQUEST['emtax']));
  $SQL->add_item("tpl_headoffice", encode_text2($_REQUEST['tpl_headoffice']));
  $SQL->add_item("tpl_intheadoffice", encode_text2($_REQUEST['tpl_intheadoffice']));
  $SQL->add_item("tpl_contacts", encode_text2($_REQUEST['tpl_contacts']));
  $SQL->add_item("let_inter", encode_text2($_REQUEST['let_inter']));
  $SQL->add_item("let_head", encode_text2($_REQUEST['let_head']));
  $SQL->add_item("let_postal", encode_text2($_REQUEST['let_postal']));
  $SQL->add_item("let_office", encode_text2($_REQUEST['let_office']));
  $SQL->add_item("let_top", encode_text2($_REQUEST['let_top']));
  $SQL->add_item("let_acc", encode_text2($_REQUEST['let_acc']));
  $SQL->add_item("tpl_workthedream", encode_text2($_REQUEST['tpl_workthedream']));
  $SQL->add_item("tpl_disclaimer", encode_text2($_REQUEST['tpl_disclaimer']));
  $SQL->add_item("em_top", encode_text2($_REQUEST['em_top']));
  $SQL->add_item("em_sel", encode_text2($_REQUEST['em_sel']));
  $SQL->add_item("em_bot", encode_text2($_REQUEST['em_bot']));
  $SQL->add_item("em_rem", encode_text2($_REQUEST['em_rem']));
  $SQL->add_item("em_agent", encode_text2($_REQUEST['em_agent']));
  $SQL->add_item("clas_rules", encode_text2($_REQUEST['clas_rules']));

  $SQL->add_where("CID = '".$_REQUEST['CID']."'");
  dbWrite($SQL->get_sql_update());

 }

?>
</form>
<form method="post" action="body.php?page=update_country&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">
<input type="hidden" name="data" value="<?= $row['countryID'] ?>">

<table border="0" cellspacing="0" cellpadding="1" width="610">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="3" align="center" class="Heading"><b>Country Details View</b></td>
  </tr>
 <?
 $foo = 0;

 $query = dbRead("select * from country order by name");
 while($row = mysql_fetch_assoc($query))  {

  $cfgbgcolorone = "#CCCCCC";
  $cfgbgcolortwo = "#EEEEEE";
  $bgcolor = $cfgbgcolorone;
  $foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
  ?>
  <tr bgcolor="<?= $bgcolor ?>">
	<td width="200"><b><a href="body.php?page=update_countrydata&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>&data=<?= $row['countryID'] ?>" class="nav"><b><?= $row['name'] ?> (<?= $row['countryID'] ?>)</b></a></b></td>
	<td width="420" colspan = "2"><?= $row['address1'] ?><br></a></td>
  </tr>
  <tr bgcolor="<?= $bgcolor ?>">
	<td width="133">&nbsp;&nbsp;&nbsp;<b>Tel:</b> <?= $row[phone] ?></td>
	<td width="133" style="padding: 0"><b>Fax:</b> <?= $row[fax] ?></td>
	<td width="220" style="padding: 0"><b>Email:</b> <?= $row[email] ?></td>
  </tr>
 <?$foo++;
 }?>
</table>
</td>
</tr>
</table>

<?
}

function countryadd() {

$yesno = array('Y' => 'Yes', 'N' => 'No');
$yesno2 = array('Yes' => 'Yes', 'No' => 'No');

 if($_REQUEST[countryadd])  {

  $SQL = new dbCreateSQL();

  $SQL->add_table("countrydata");

  $SQL->add_item("n_company", encode_text2($_REQUEST['n_company']));
  $SQL->add_item("n_contact", encode_text2($_REQUEST['n_contact']));
  $SQL->add_item("n_accno", encode_text2($_REQUEST['n_accno']));
  $SQL->add_item("n_user", encode_text2($_REQUEST['n_user']));
  $SQL->add_item("n_pass", encode_text2($_REQUEST['n_pass']));
  $SQL->add_item("n_facility", encode_text2($_REQUEST['n_facility']));
  $SQL->add_item("n_customer", encode_text2($_REQUEST['n_customer']));
  $SQL->add_item("n_auth", encode_text2($_REQUEST['n_auth']));
  $SQL->add_item("n_secure", encode_text2($_REQUEST['n_secure']));
  $SQL->add_item("n_enton", encode_text2($_REQUEST['n_enton']));
  $SQL->add_item("n_entby", encode_text2($_REQUEST['n_entby']));
  $SQL->add_item("n_fee", encode_text2($_REQUEST['n_fee']));
  $SQL->add_item("n_paid", encode_text2($_REQUEST['n_paid']));
  $SQL->add_item("tname", encode_text2($_REQUEST['tname']));
  $SQL->add_item("sname", encode_text2($_REQUEST['sname']));
  $SQL->add_item("acno", encode_text2($_REQUEST['acno']));
  $SQL->add_item("tdate", encode_text2($_REQUEST['tdate']));
  $SQL->add_item("locoff", encode_text2($_REQUEST['locoff']));
  $SQL->add_item("tno", encode_text2($_REQUEST['tno']));
  $SQL->add_item("tdet", encode_text2($_REQUEST['tdet']));
  $SQL->add_item("tcom", encode_text2($_REQUEST['tcom']));
  $SQL->add_item("tsub", encode_text2($_REQUEST['tsub']));
  $SQL->add_item("tgst", encode_text2($_REQUEST['tgst']));
  $SQL->add_item("ttot", encode_text2($_REQUEST['ttot']));
  $SQL->add_item("tout", encode_text2($_REQUEST['tout']));
  $SQL->add_item("tpaid", encode_text2($_REQUEST['tpaid']));
  $SQL->add_item("tnow", encode_text2($_REQUEST['tnow']));
  $SQL->add_item("trequ", encode_text2($_REQUEST['trequ']));
  $SQL->add_item("tnom", encode_text2($_REQUEST['tnom']));
  $SQL->add_item("tlate", encode_text2($_REQUEST['tlate']));
  $SQL->add_item("thu", encode_text2($_REQUEST['thu']));
  $SQL->add_item("sdate", encode_text2($_REQUEST['sdate']));
  $SQL->add_item("stdate", encode_text2($_REQUEST['stdate']));
  $SQL->add_item("sbuy", encode_text2($_REQUEST['sbuy']));
  $SQL->add_item("ssell", encode_text2($_REQUEST['ssell']));
  $SQL->add_item("stbal", encode_text2($_REQUEST['stbal']));
  $SQL->add_item("sob", encode_text2($_REQUEST['sob']));
  $SQL->add_item("sto", encode_text2($_REQUEST['sto']));
  $SQL->add_item("sfac", encode_text2($_REQUEST['sfac']));
  $SQL->add_item("srfac", encode_text2($_REQUEST['srfac']));
  $SQL->add_item("snett", encode_text2($_REQUEST['snett']));
  $SQL->add_item("scash", encode_text2($_REQUEST['scash']));
  $SQL->add_item("scas", encode_text2($_REQUEST['scas']));
  $SQL->add_item("scbal", encode_text2($_REQUEST['scbal']));
  $SQL->add_item("sdue", encode_text2($_REQUEST['sdue']));
  $SQL->add_item("spai", encode_text2($_REQUEST['spai']));
  $SQL->add_item("auth", encode_text2($_REQUEST['auth']));
  $SQL->add_item("itt", encode_text2($_REQUEST['itt']));
  $SQL->add_item("comna", encode_text2($_REQUEST['comna']));
  $SQL->add_item("amow", encode_text2($_REQUEST['amow']));
  $SQL->add_item("trem", encode_text2($_REQUEST['trem']));
  $SQL->add_item("tampa", encode_text2($_REQUEST['tampa']));
  $SQL->add_item("receipt", encode_text2($_REQUEST['receipt']));
  $SQL->add_item("re_date", encode_text2($_REQUEST['re_date']));
  $SQL->add_item("re_no", encode_text2($_REQUEST['re_no']));
  $SQL->add_item("re_from", encode_text2($_REQUEST['re_from']));
  $SQL->add_item("re_sum", encode_text2($_REQUEST['re_sum']));
  $SQL->add_item("re_to", encode_text2($_REQUEST['re_to']));
  $SQL->add_item("dear", encode_text2($_REQUEST['dear']));
  $SQL->add_item("re", encode_text2($_REQUEST['re']));
  $SQL->add_item("parta", encode_text2($_REQUEST['parta']));
  $SQL->add_item("partb", encode_text2($_REQUEST['partb']));
  $SQL->add_item("partc", encode_text2($_REQUEST['partc']));
  $SQL->add_item("muser", encode_text2($_REQUEST['muser']));
  $SQL->add_item("mpass", encode_text2($_REQUEST['mpass']));
  $SQL->add_item("upparta", encode_text2($_REQUEST['upparta']));
  $SQL->add_item("uppartb", encode_text2($_REQUEST['uppartb']));
  $SQL->add_item("emtax", encode_text2($_REQUEST['emtax']));
  $SQL->add_item("tpl_headoffice", encode_text2($_REQUEST['tpl_headoffice']));
  $SQL->add_item("tpl_intheadoffice", encode_text2($_REQUEST['tpl_intheadoffice']));
  $SQL->add_item("tpl_contacts", encode_text2($_REQUEST['tpl_contacts']));
  $SQL->add_item("tpl_workthedream", encode_text2($_REQUEST['tpl_workthedream']));
  $SQL->add_item("let_inter", encode_text2($_REQUEST['let_inter']));
  $SQL->add_item("let_head", encode_text2($_REQUEST['let_head']));
  $SQL->add_item("let_postal", encode_text2($_REQUEST['let_postal']));
  $SQL->add_item("let_office", encode_text2($_REQUEST['let_office']));
  $SQL->add_item("let_top", encode_text2($_REQUEST['let_top']));
  $SQL->add_item("tpl_disclaimer", encode_text2($_REQUEST['tpl_disclaimer']));
  $SQL->add_item("let_acc", encode_text2($_REQUEST['let_acc']));
  $SQL->add_item("em_top", encode_text2($_REQUEST['em_top']));
  $SQL->add_item("em_sel", encode_text2($_REQUEST['em_sel']));
  $SQL->add_item("em_bot", encode_text2($_REQUEST['em_bot']));
  $SQL->add_item("em_rem", encode_text2($_REQUEST['em_rem']));
  $SQL->add_item("em_agent", encode_text2($_REQUEST['em_agent']));
  $SQL->add_item("clas_rules", encode_text2($_REQUEST['clas_rules']));

  dbWrite($SQL->get_sql_insert());
 }

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<title>Change Area</title>
</head>

<body onload="javascript:setFocus('countryadd','name');">

<form method="post" action="/general.php" name="countryadd">

<input type="hidden" name="fieldid" value="<?= $row['FieldID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Country Data Add</b></td>
  </tr>
  <tr>
      <td height="30" align="right" class="Heading2" width="100"><b><?= get_word("79") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?

		$dbgetarea=dbRead("select * from country order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row['countryID'] == $GET_CID) { echo "selected "; } ?>value="<?= $row['countryID'] ?>"><?= $row['name'] ?></option>
			<?
		}

		$counter = mysql_num_rows($dbgetdataout);

?>
	  </select>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Tax Invoice Data</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax Invoice:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tname" size="30" value="<?= $row['tname'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Account Number:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="acno" size="30" value="<?= $row['acno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax Invoice Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tdate" size="30" value="<?= $row['tdate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax Invoice Number:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tno" size="30" value="<?= $row['tno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Cash Fees Owing for Current Month:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tdet" size="30" value="<?= $row['tdet'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>A breakdown of fees is available on your statement. This is attached if you received this invoice by mail, or can be downloaded from the members' section of the corporate website by online members.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="tcom" size="30" rows="6" value="<?= $row['tcom'] ?>" cols="35"><?= $row['tcom'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Sub Total:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tsub" size="30" value="<?= $row['tsub'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Plus GST:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tgst" size="30" value="<?= $row['tgst'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>TOTAL</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="ttot" size="30" value="<?= $row['ttot'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>PLUS OUTSTANDING FEES:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tout" size="30" value="<?= $row['tout'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Less Fees Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tpaid" size="30" value="<?= $row['tpaid'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>TOTAL NOW OWING:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tnow" size="30" value="<?= $row['tnow'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Payment is required within 7 days of receipt of this Tax Invoice, unless it is less than $20 or an ongoing debit arrangement is in place.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="trequ" size="30" rows="5" value="<?= $row['trequ'] ?>" cols="35"><?= $row['trequ'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Nominated account will be debited on the 21st of the month:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tnom" size="30" value="<?= $row['tnom'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="38"><b>A late payment fee may apply:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="38"><input type="text" name="tlate" size="30" value="<?= $row['tlate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%" height="38"><b>Hungary:</b></td>
    <td bgcolor="#FFFFFF" width="70%" height="84">
    <?
        ob_start();
        eval(" ?>".$row['thu']."<? ");
        $output = ob_get_contents();
	    ob_end_clean();
    ?>
    <textarea name="thu" size="30" rows="5" cols="35"><?= $output ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Company Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="comna" size="30" value="<?= $row['comna'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Amount Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="amow" size="30" value="<?= $row['amow'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Remittance:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="trem" size="30" value="<?= $row['trem'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Amount Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tampa" size="30" value="<?= $row['tampa'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Statement Data</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>STATEMENT:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sname" size="30" value="<?= $row['sname'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Statement Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sdate" size="30" value="<?= $row['sdate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="stdate" size="30" value="<?= $row['stdate'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Local Office:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="locoff" size="30" value="<?= $row['locoff'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Buy:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sbuy" size="30" value="<?= $row['sbuy'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Sell:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="ssell" size="30" value="<?= $row['ssell'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Trade Balance:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="stbal" size="30" value="<?= $row['stbal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Cash Fees:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="scash" size="30" value="<?= $row['scash'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Cash Balance:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="scbal" size="30" value="<?= $row['scbal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Opening Balance:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sob" size="30" value="<?= $row['sob'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>TOTALS:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sto" size="30" value="<?= $row['sto'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Current Facility:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sfac" size="30" value="<?= $row['sfac'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Real Estate Facility:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="srfac" size="30" value="<?= $row['srfac'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Nett Position:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="snett" size="30" value="<?= $row['snett'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Cash Fees:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="scas" size="30" value="<?= $row['scas'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Now Due:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="sdue" size="30" value="<?= $row['sdue'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Prepaid:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="spai" size="30" value="<?= $row['spai'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Authorisation is required for all transactions of $200 and over:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="trequ" size="30" rows="5" value="<?= $row['auth'] ?>" cols="35"><?= $row['auth'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>24 HOURS NOTICE MUST BE GIVEN FOR ALL TRANSACTIONS OF $3000 AND OVER BY LODGEMENT OF AN INTENTION TO TRADE (ITT) FORM:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="itt" size="30"  rows="5" value="<?= $row['itt'] ?>" rows="1" cols="35"><?= $row['itt'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Receipt Data</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Receipt:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="receipt" size="30" value="<?= $row['receipt'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Receipt Date:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="re_date" size="30" value="<?= $row['re_date'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Receipt Number:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="re_no" size="30" value="<?= $row['re_no'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Received from:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="re_from" size="30" value="<?= $row['re_from'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>The sum of:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="re_sum" size="30" value="<?= $row['re_sum'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Being for conversion to E Banc Trade Dollars to the amount of:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="re_to" size="30" value="<?= $row['re_to'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>New Member Pack Details</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>MEMBERS PERSONAL TRADING ACCOUNT DETAILS:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_company" size="30" value="<?= $row['n_company'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Contact Name is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_contact" size="30" value="<?= $row['n_contact'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Account Number is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_accno" size="30" value="<?= $row['n_accno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Online User Name is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_user" size="30" value="<?= $row['n_user'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Online Password is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_pass" size="30" value="<?= $row['n_pass'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Facility is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_facility" size="30" value="<?= $row['n_facility'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Your Customer Support is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_customer" size="30" value="<?= $row['n_customer'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Authorisation Call-in Number is:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_auth" size="30" value="<?= $row['n_auth'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Keep Secure</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_secure" size="30" value="<?= $row['n_secure'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Entered on:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_enton" size="30" value="<?= $row['n_enton'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Entered by:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_entby" size="30" value="<?= $row['n_entby'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fee %:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_fee" size="30" value="<?= $row['n_fee'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Joining Fee Paid:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="n_paid" size="30" value="<?= $row['n_paid'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Email - New Memeber</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Dear:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="dear" size="30" value="<?= $row['dear'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>RE:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="re" size="30" value="<?= $row['re'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>You are receiving this email because either you have recently joined E Banc Trade, or, as an existing member, you have reopened your account in a different name.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="parta" size="30"  rows="5" value="<?= $row['parta'] ?>" rows="1" cols="35"><?= $row['parta'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Would you please check your account details below.  If there are any discrepancies, or you wish to change your directory message, please notify Head Office, Australia on:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="partb" size="30"  rows="5" value="<?= $row['partb'] ?>" rows="1" cols="35"><?= $row['partb'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>and ask for the Membership Accounts Department, or simply reply to this email.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="partc" size="30"  rows="5" value="<?= $row['partc'] ?>" rows="1" cols="35"><?= $row['partc'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Email - Online Details</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Username:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="muser" size="30" value="<?= $row['muser'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Password:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="mpass" size="30" value="<?= $row['mpass'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>At your request, we are forwarding you a reminder of your username and password on your E Banc Trade Account.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="upparta" size="30" value="<?= $row['upparta'] ?>" rows="5" cols="35"><?= $row['upparta'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Access this site by clicking on this link, <a href="https://secure.ebanctrade.com/members">http://secure.ebanctrade.com/members</a>, or pasting it into your browser window. Enter your username and password when requested, and you can then access the members section of the corporate website. This provides access to your account details, the Bid'n'Buy site and the classifieds. You can also update your membership information, pay fees online and make trade transfers.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="uppartb" rows="10" cols="35"><?= $row['uppartb'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Email - Tax Invoice</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Attached is your current Tax Invoice.  You may need Acrobat Reader to view this Tax Invoice.  You can download it for free from: <a href="http://www.adobe.com/products/acrobat/readstep.html">http://www.adobe.com/products/acrobat/readstep.html</a>.<br><br>Current and Past Statements<br>You can view your current and past statements from the Member’s section of the E Banc Trade website, <a href="https://www.ebanctrade.com/members">https://www.ebanctrade.com/members</a> by entering your username and password.  A list of functions is available to you from the left side of the screen.<br><br>E Banc Trader – our monthly newsletter<br>The current newsletter is now available and can also be viewed by selecting the appropriate link from the left side of the home page.  If you would like to promote your goods and services in this newsletter, please email them to <a href="mailto:publications@ebanctrade.com">publications@ebanctrade.com</a> by the 20th of each month.<br><br>Membership Directory Updates<br>Remember to download the latest Membership Directory from the website for the most up to date information on goods and services provided by the E Banc Trade Exchange.  A listing of new members for the past month is available from the left hand selection once you log in to the Member’s section. A hardcopy of the directory is available from head office for a cash fee of $10.  Local directories are also available from Head Office.<br><br>Regards<br><br><br><br>E Banc Trade Membership Accounts:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="emtax" rows="30" cols="35"><?= $row['emtax'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Head Office:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tpl_headoffice" size="30" value="<?= $row['tpl_headoffice'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Int'l Head Office:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tpl_intheadoffice" size="30" value="<?= $row['tpl_intheadoffice'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Office Contacts:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tpl_contacts" size="30" value="<?= $row['tpl_contacts'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>work the dream:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tpl_workthedream" size="30" value="<?= $row['tpl_workthedream'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>INTER HEAD OFFICE:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_inter" size="30" value="<?= $row['let_inter'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>HEAD OFFICE:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_head" size="30" value="<?= $row['let_head'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>POSTAL ADDRESS:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_postal" size="30" value="<?= $row['let_postal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>OFFICE CONTACT:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_office" size="30" value="<?= $row['let_office'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Let Business:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_top" size="30" value="<?= $row['let_top'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Account No:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="let_acc" size="30" value="<?= $row['let_acc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Confidentiality:
                      The information contained in this email and any attachments
                      is confidential and/or privileged.<br>
                      If the reader is not the intended recipient named above,
                      or a representative of the intended recipient, you are <br>
                      advised</font><font face="Arial, Helvetica, sans-serif" size="1">
                      that any review, dissemination or copying of this email
                      and any attachments is prohibited. If you have<br>
                      received this email in error, please notify the sender by
                      email, telephone or fax and return it to the sender.</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="tpl_disclaimer" rows="10" cols="35"><?= $row['tpl_disclaimer'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Classified Rules</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Class Rules:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="clas_rules" rows="10" cols="35"><?= $row['clas_rules'] ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Email System</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>If you cannot view the below email,:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_top" size="30" value="<?= $row['em_top'] ?>" rows="5" cols="35"><?= $row['em_top'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>please select here:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_sel" size="30" value="<?= $row['em_sel'] ?>" rows="5" cols="35"><?= $row['em_sel'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Happy Trading from Empire Trade Management and Associates:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_bot" size="30" value="<?= $row['em_bot'] ?>" rows="5" cols="35"><?= $row['em_bot'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>This message is sent to online members of Empire Trade. If you feel that your email was obtained by error, or you no longer wish to receive membership promotions, please <a href="mailto:<?= $pubEmailObj->pubemail ?>?subject=Please%20remove%20me%20from%20your%20email%20list">select here</a> and hit send.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_rem" size="30" value="<?= $row['em_rem'] ?>" rows="5" cols="35"><?= $row['em_rem'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>This message is sent to online members of Empire Trade. If you feel that your email was obtained by error, or you no longer wish to receive membership promotions, please <a href="mailto:<?= $pubEmailObj->pubemail ?>?subject=Please%20remove%20me%20from%20your%20email%20list">select here</a> and hit send.:</b></td>
    <td bgcolor="#FFFFFF" width="70%">
    <textarea name="em_agent" size="30" value="<?= $row['em_agent'] ?>" rows="5" cols="35"><?= $row['em_agent'] ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Data" name="countryadd" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="countryadd" value="1">

</form>

</body>

<?
die;
}
