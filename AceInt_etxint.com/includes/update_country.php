<form method="POST" action="body.php?page=update_country&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>">

<?
// Some Setup.
include("includes/modules/db.php");

$tabarray = array("Country Update",'Add Country');

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  country();

} elseif($_GET[tab] == "tab2") {

  countryadd();
}

?>

</form>

<?
function country() {

$yesno = array('Y' => 'Yes', 'N' => 'No');
$yesno2 = array('Yes' => 'Yes', 'No' => 'No');
$et = array('ebt' => 'ebt', 'ept' => 'ept', 'etx' => 'etx');
$no = array('0' => 'No', '1' => 'Yes');
$dec = array('1' => '.[dot]', '2' => ',[comm]');

if($_REQUEST['data'])  {

$dbquery1 = dbRead("select * from country where countryID = ".$_REQUEST['data']."");

$row = mysql_fetch_assoc($dbquery1);

?>

<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<title>Change Area</title>
</head>

<body onload="javascript:setFocus('countryadd','name');">

<form method="post" action="/general.php" name="changecountry">

<input type="hidden" name="countryID" value="<?= $row['countryID'] ?>">

<table border="0" width="639" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="Heading"><b>Country Edit</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country ID:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><?= $row['countryID'] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="name" size="30" value="<?= $row['name'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Nationality Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="nationality" size="30" value="<?= $row['nationality'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Lang Code:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="Langcode" size="30" value="<?= $row['Langcode'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Additional web Lang:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="web_langs" size="30" value="<?= $row['web_langs'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country Code:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="countrycode" size="30" value="<?= $row['countrycode'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Locale Code:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="locale" size="30" value="<?= $row['locale'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>English Speaking:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('english',$yesno,'','',$row['english']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('Display',$yesno2,'','',$row['Display']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Print Cards:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('printcards',$yesno,'','',$row['printcards']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Facility Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="facacc" size="10" value="<?= $row['facacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>R/E Facility Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="refacacc" size="10" value="<?= $row['refacacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Reserve Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="reserveacc" size="10" value="<?= $row['reserveacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>R/E Reserve Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="rereserve" size="10" value="<?= $row['rereserve'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Trust Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="trustacc" size="10" value="<?= $row['trustacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Expense Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="expense" size="10" value="<?= $row['expense'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>International Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="interacc" size="10" value="<?= $row['interacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>E Rewards Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="erewardsacc" size="10" value="<?= $row['erewardsacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Suspense Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="suspense" size="10" value="<?= $row['suspense'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Test Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="test" size="10" value="<?= $row['test'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Other Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="other" size="10" value="<?= $row['other'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Write Off Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="writeoff" size="10" value="<?= $row['writeoff'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Closed Account Trust:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="w_trust" size="10" value="<?= $row['w_trust'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Loan Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="loan" size="10" value="<?= $row['loan'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Repayment Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="repay" size="10" value="<?= $row['repay'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Company Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="company" size="30" value="<?= $row['company'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Residentional Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="address1" size="30" value="<?= $row['address1'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Postal Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="address2" size="30" value="<?= $row['address2'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Phone:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="phone" size="30" value="<?= $row['phone'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fax:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="fax" size="30" value="<?= $row['fax'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email" size="30" value="<?= $row['email'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>ABN:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="abn" size="30" value="<?= $row['abn'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Auth Phone
    No:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="authno" size="30" value="<?= $row['authno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax Name:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="GST" size="10" value="<?= $row['GST'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tax" size="10" value="<?= $row['tax'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Currency:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="currency" size="10" value="<?= $row['currency'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Currency Name:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="currencyname" size="10" value="<?= $row['currencyname'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Decimal Type:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('dec_type',$dec,'','',$row['dec_type']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Convert Code:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="convert" size="10" value="<?= $row['convert'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Time Zone:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="timezone" size="10" value="<?= $row['timezone'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Trans Fees %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="feepercent" size="10" value="<?= $row['feepercent'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>R/E Trans Fees %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="repercent" size="10" value="<?= $row['repercent'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Membership Fee Nett:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="membership" size="10" value="<?= $row['membership'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Membership Fee Gross:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="membershipgross" size="10" value="<?= $row['membershipgross'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Monthly Fee:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="feemonthly" size="15" value="<?= $row['feemonthly'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Admin Fee:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('a_fee',$yesno,'','',$row['a_fee']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Admin Fee Amount:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="admin_fee" size="15" value="<?= $row['admin_fee'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Facility Renewal:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="facility_renewal" size="15" value="<?= $row['facility_renewal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Auth Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="authlimit" size="15" value="<?= $row['authlimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Daily Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="dailylimit" size="15" value="<?= $row['dailylimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Weekly Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="weeklimit" size="15" value="<?= $row['weeklimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Per Member Daily Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="memdailylimit" size="15" value="<?= $row['memdailylimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Per Member Weekly Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="memweeklimit" size="15" value="<?= $row['memweeklimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Letter Amount:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="letteramount" size="15" value="<?= $row['letteramount'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Clas. Min. Amount:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="clasmin" size="15" value="<?= $row['clasmin'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>No of Cheque Sheets:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="cheque_pages" size="15" value="<?= $row['cheque_pages'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country Prefix:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="phoneprefix" size="15" value="<?= $row['phoneprefix'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Category Groups:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('catgroups',$no,'','',$row['catgroups']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Club:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('club',$no,'','',$row['club']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Gold:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('gold',$no,'','',$row['gold']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>50% Plus Club:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="fiftyName" size="15" value="<?= $row['fiftyName'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Gold Club:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="goldName" size="15" value="<?= $row['goldName'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Service Setup Fee:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="setup_fee" size="15" value="<?= $row['setup_fee'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display E Rewards:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('erewards',$yesno,'','',$row['erewards']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display All Trades:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('alltrades',$yesno,'','',$row['alltrades']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>EBT/EPT:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('logo',$et,'','',$row['logo']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Lic ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email_lic" size="10" value="<?= $row['email_lic'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Staff ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email_staff" size="10" value="<?= $row['email_staff'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Default Area:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="DefaultArea" size="10" value="<?= $row['DefaultArea'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Change Area" name="changearea" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="changecountry" value="1">

</form>

</body>
<?
die;
}

 if($_REQUEST[changecountry])  {

  $query = dbRead("select * from country where countryID = '".$_REQUEST['countryID']."'");
  $row = mysql_fetch_assoc($query);

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

  add_kpi2(5,'0','0',$row['countryID'],$logdata);

  $SQL = new dbCreateSQL();

  $SQL->add_table("country");

  $SQL->add_item("name", encode_text2($_REQUEST['name']));
  $SQL->add_item("nationality", encode_text2($_REQUEST['nationality']));
  $SQL->add_item("Langcode", encode_text2($_REQUEST['Langcode']));
  $SQL->add_item("web_langs", encode_text2($_REQUEST['web_langs']));
  $SQL->add_item("countrycode", encode_text2($_REQUEST['countrycode']));
  $SQL->add_item("locale", encode_text2($_REQUEST['locale']));
  $SQL->add_item("english", encode_text2($_REQUEST['english']));
  $SQL->add_item("Display", encode_text2($_REQUEST['Display']));
  $SQL->add_item("printcards", encode_text2($_REQUEST['printcards']));
  $SQL->add_item("facacc", encode_text2($_REQUEST['facacc']));
  $SQL->add_item("refacacc", encode_text2($_REQUEST['refacacc']));
  $SQL->add_item("reserveacc", $_REQUEST['reserveacc']);
  $SQL->add_item("rereserve", encode_text2($_REQUEST['rereserve']));
  $SQL->add_item("trustacc", encode_text2($_REQUEST['trustacc']));
  $SQL->add_item("expense", encode_text2($_REQUEST['expense']));
  $SQL->add_item("interacc", encode_text2($_REQUEST['interacc']));
  $SQL->add_item("erewardsacc", encode_text2($_REQUEST['erewardsacc']));
  $SQL->add_item("suspense", encode_text2($_REQUEST['suspense']));
  $SQL->add_item("test", encode_text2($_REQUEST['test']));
  $SQL->add_item("other", encode_text2($_REQUEST['other']));
  $SQL->add_item("writeoff", encode_text2($_REQUEST['writeoff']));
  $SQL->add_item("w_trust", encode_text2($_REQUEST['w_trust']));
  $SQL->add_item("loan", encode_text2($_REQUEST['loan']));
  $SQL->add_item("repay", encode_text2($_REQUEST['repay']));
  $SQL->add_item("company", encode_text2($_REQUEST['company']));
  $SQL->add_item("address1", encode_text2($_REQUEST['address1']));
  $SQL->add_item("address2", $_REQUEST['address2']);
  $SQL->add_item("phone", $_REQUEST['phone']);
  $SQL->add_item("fax", $_REQUEST['fax']);
  $SQL->add_item("email", encode_text2($_REQUEST['email']));
  $SQL->add_item("abn", encode_text2($_REQUEST['abn']));
  $SQL->add_item("authno", encode_text2($_REQUEST['authno']));
  $SQL->add_item("GST", $_REQUEST['GST']);
  $SQL->add_item("tax", $_REQUEST['tax']);
  $SQL->add_item("currency", $_REQUEST['currency']);
  $SQL->add_item("currencyname", $_REQUEST['currencyname']);
  $SQL->add_item("dec_type", $_REQUEST['dec_type']);
  $SQL->add_item("convert", encode_text2($_REQUEST['convert']));
  $SQL->add_item("timezone", encode_text2($_REQUEST['timezone']));
  $SQL->add_item("feepercent", encode_text2($_REQUEST['feepercent']));
  $SQL->add_item("repercent", encode_text2($_REQUEST['repercent']));
  $SQL->add_item("membership", encode_text2($_REQUEST['membership']));
  $SQL->add_item("membershipgross", encode_text2($_REQUEST['membershipgross']));
  $SQL->add_item("feemonthly", $_REQUEST['feemonthly']);
  $SQL->add_item("a_fee", $_REQUEST['a_fee']);
  $SQL->add_item("admin_fee", $_REQUEST['admin_fee']);
  $SQL->add_item("facility_renewal", $_REQUEST['facility_renewal']);
  $SQL->add_item("authlimit", $_REQUEST['authlimit']);
  $SQL->add_item("dailylimit", $_REQUEST['dailylimit']);
  $SQL->add_item("weeklimit", encode_text2($_REQUEST['weeklimit']));
  $SQL->add_item("memdailylimit", encode_text2($_REQUEST['memdailylimit']));
  $SQL->add_item("memweeklimit", encode_text2($_REQUEST['memweeklimit']));
  $SQL->add_item("letteramount", encode_text2($_REQUEST['letteramount']));
  $SQL->add_item("clasmin", $_REQUEST['clasmin']);
  $SQL->add_item("cheque_pages", $_REQUEST['cheque_pages']);
  $SQL->add_item("phoneprefix", $_REQUEST['phoneprefix']);
  $SQL->add_item("catgroups", $_REQUEST['catgroups']);
  $SQL->add_item("club", $_REQUEST['club']);
  $SQL->add_item("gold", $_REQUEST['gold']);
  $SQL->add_item("fiftyName", $_REQUEST['fiftyName']);
  $SQL->add_item("goldName", $_REQUEST['goldName']);
  $SQL->add_item("setup_fee", $_REQUEST['setup_fee']);
  $SQL->add_item("erewards", $_REQUEST['erewards']);
  $SQL->add_item("alltrades", $_REQUEST['alltrades']);
  $SQL->add_item("logo", encode_text2($_REQUEST['logo']));
  $SQL->add_item("email_lic", encode_text2($_REQUEST['email_lic']));
  $SQL->add_item("email_staff", encode_text2($_REQUEST['email_staff']));
  $SQL->add_item("DefaultArea", encode_text2($_REQUEST['DefaultArea']));

  $SQL->add_where("countryID = '".$_REQUEST['countryID']."'");
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
	<td width="200"><b><a href="body.php?page=update_country&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>&data=<?= $row['countryID'] ?>" class="nav"><b><?= $row['name'] ?> (<?= $row['countryID'] ?>)</b></a></b></td>
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
$et = array('ebt' => 'ebt', 'ept' => 'ept');
$no = array('0' => 'No', '1' => 'Yes');
$dec = array('1' => '.[dot]', '2' => ',[comm]');

 if($_REQUEST[countryadd])  {

  $SQL = new dbCreateSQL();

  $SQL->add_table("country");

  $SQL->add_item("name", encode_text2($_REQUEST['name']));
  $SQL->add_item("nationality", encode_text2($_REQUEST['nationality']));
  $SQL->add_item("Langcode", encode_text2($_REQUEST['Langcode']));
  $SQL->add_item("web_langs", encode_text2($_REQUEST['web_langs']));
  $SQL->add_item("countrycode", encode_text2($_REQUEST['countrycode']));
  $SQL->add_item("locale", encode_text2($_REQUEST['locale']));
  $SQL->add_item("english", encode_text2($_REQUEST['english']));
  $SQL->add_item("Display", encode_text2($_REQUEST['Display']));
  $SQL->add_item("printcards", encode_text2($_REQUEST['printcards']));
  $SQL->add_item("facacc", encode_text2($_REQUEST['facacc']));
  $SQL->add_item("refacacc", encode_text2($_REQUEST['refacacc']));
  $SQL->add_item("reserveacc", $_REQUEST['reserveacc']);
  $SQL->add_item("rereserve", encode_text2($_REQUEST['rereserve']));
  $SQL->add_item("trustacc", encode_text2($_REQUEST['trustacc']));
  $SQL->add_item("expense", encode_text2($_REQUEST['expense']));
  $SQL->add_item("interacc", encode_text2($_REQUEST['interacc']));
  $SQL->add_item("erewardsacc", encode_text2($_REQUEST['erewardsacc']));
  $SQL->add_item("suspense", encode_text2($_REQUEST['suspense']));
  $SQL->add_item("test", encode_text2($_REQUEST['test']));
  $SQL->add_item("other", encode_text2($_REQUEST['other']));
  $SQL->add_item("writeoff", encode_text2($_REQUEST['writeoff']));
  $SQL->add_item("w_trust", encode_text2($_REQUEST['w_trust']));
  $SQL->add_item("loan", encode_text2($_REQUEST['loan']));
  $SQL->add_item("repay", encode_text2($_REQUEST['repay']));
  $SQL->add_item("company", encode_text2($_REQUEST['company']));
  $SQL->add_item("address1", encode_text2($_REQUEST['address1']));
  $SQL->add_item("address2", $_REQUEST['address2']);
  $SQL->add_item("phone", $_REQUEST['phone']);
  $SQL->add_item("GST", $_REQUEST['GST']);
  $SQL->add_item("fax", $_REQUEST['fax']);
  $SQL->add_item("email", encode_text2($_REQUEST['email']));
  $SQL->add_item("abn", encode_text2($_REQUEST['abn']));
  $SQL->add_item("authno", encode_text2($_REQUEST['authno']));
  $SQL->add_item("tax", $_REQUEST['tax']);
  $SQL->add_item("currency", $_REQUEST['currency']);
  $SQL->add_item("currencyname", $_REQUEST['currencyname']);
  $SQL->add_item("dec_type", $_REQUEST['dec_type']);
  $SQL->add_item("convert", encode_text2($_REQUEST['convert']));
  $SQL->add_item("timezone", encode_text2($_REQUEST['timezone']));
  $SQL->add_item("feepercent", encode_text2($_REQUEST['feepercent']));
  $SQL->add_item("repercent", encode_text2($_REQUEST['repercent']));
  $SQL->add_item("membership", encode_text2($_REQUEST['membership']));
  $SQL->add_item("membershipgross", encode_text2($_REQUEST['membershipgross']));
  $SQL->add_item("feemonthly", $_REQUEST['feemonthly']);
  $SQL->add_item("a_fee", $_REQUEST['a_fee']);
  $SQL->add_item("admin_fee", $_REQUEST['admin_fee']);
  $SQL->add_item("facility_renewal", $_REQUEST['facility_renewal']);
  $SQL->add_item("authlimit", $_REQUEST['authlimit']);
  $SQL->add_item("dailylimit", $_REQUEST['dailylimit']);
  $SQL->add_item("weeklimit", encode_text2($_REQUEST['weeklimit']));
  $SQL->add_item("memdailylimit", encode_text2($_REQUEST['memdailylimit']));
  $SQL->add_item("memweeklimit", encode_text2($_REQUEST['memweeklimit']));
  $SQL->add_item("letteramount", encode_text2($_REQUEST['letteramount']));
  $SQL->add_item("clasmin", $_REQUEST['clasmin']);
  $SQL->add_item("cheque_pages", $_REQUEST['cheque_pages']);
  $SQL->add_item("phoneprefix", $_REQUEST['phoneprefix']);
  $SQL->add_item("catgroups", $_REQUEST['catgroups']);
  $SQL->add_item("club", $_REQUEST['club']);
  $SQL->add_item("gold", $_REQUEST['gold']);
  $SQL->add_item("fiftyName", $_REQUEST['fiftyName']);
  $SQL->add_item("goldName", $_REQUEST['goldName']);
  $SQL->add_item("setup_fee", $_REQUEST['setup_fee']);
  $SQL->add_item("erewards", $_REQUEST['erewards']);
  $SQL->add_item("alltrades", $_REQUEST['alltrades']);
  $SQL->add_item("logo", encode_text2($_REQUEST['logo']));
  $SQL->add_item("email_lic", encode_text2($_REQUEST['email_lic']));
  $SQL->add_item("email_staff", encode_text2($_REQUEST['email_staff']));
  $SQL->add_item("DefaultArea", encode_text2($_REQUEST['DefaultArea']));

  dbWrite($SQL->get_sql_insert());
 }

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
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
    <td colspan="2" align="center" class="Heading"><b>Country Add</b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="name" size="30" value="<?= $row['name'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Nationality Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="nationality" size="30" value="<?= $row['nationality'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Lang Code:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="Langcode" size="30" value="<?= $row['Langcode'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Additional web Lang:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="web_langs" size="30" value="<?= $row['web_langs'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country Code:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="countrycode" size="30" value="<?= $row['countrycode'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Locale Code:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="locale" size="30" value="<?= $row['locale'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>English Speaking:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('english',$yesno,'','',$row['english']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('Display',$yesno2,'','',$row['Display']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Print Cards:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('printcards',$yesno,'','',$row['printcards']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Facility Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="facacc" size="10" value="<?= $row['facacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>R/E
    Facility Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="refacacc" size="10" value="<?= $row['refacacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Reserve Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="reserveacc" size="10" value="<?= $row['reserveacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>R/E Reserve Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="rereserve" size="10" value="<?= $row['rereserve'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Trust Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="trustacc" size="10" value="<?= $row['trustacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Expense Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="expense" size="10" value="<?= $row['expense'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>International Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="interacc" size="10" value="<?= $row['interacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>E Rewards Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="erewardsacc" size="10" value="<?= $row['erewardsacc'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Suspense Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="suspense" size="10" value="<?= $row['suspense'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Test Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="test" size="10" value="<?= $row['test'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Other Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="other" size="10" value="<?= $row['other'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Write Off Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="writeoff" size="10" value="<?= $row['writeoff'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Closed Account Trust:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="w_trust" size="10" value="<?= $row['w_trust'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Loan Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="loan" size="10" value="<?= $row['loan'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Repayment Account:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="repay" size="10" value="<?= $row['repay'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Company Name:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="company" size="30" value="<?= $row['company'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Residentional Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="address1" size="30" value="<?= $row['address1'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Postal Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="address2" size="30" value="<?= $row['address2'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Phone:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="phone" size="30" value="<?= $row['phone'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Fax:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="fax" size="30" value="<?= $row['fax'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email" size="30" value="<?= $row['email'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>ABN:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="abn" size="30" value="<?= $row['abn'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Auth Phone
    No:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="authno" size="30" value="<?= $row['authno'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax Name:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="GST" size="10" value="<?= $row['GST'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Tax %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="tax" size="10" value="<?= $row['tax'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Currency:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="currency" size="10" value="<?= $row['currency'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Currency Name:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="currencyname" size="10" value="<?= $row['currencyname'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Decimal Type:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('dec_type',$dec,'','',$row['dec_type']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Convert Code:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="convert" size="10" value="<?= $row['convert'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Time Zone:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="timezone" size="10" value="<?= $row['timezone'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Trans Fees %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="feepercent" size="10" value="<?= $row['feepercent'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>R/E Trans Fees %:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="repercent" size="10" value="<?= $row['repercent'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Membership Fee Nett:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="membership" size="10" value="<?= $row['membership'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Membership Fee Gross:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="membershipgross" size="10" value="<?= $row['membershipgross'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Monthly Fee:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="feemonthly" size="15" value="<?= $row['feemonthly'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Admin Fee:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('a_fee',$yesno,'','',$row['a_fee']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Admin Fee Amount:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="admin_fee" size="15" value="<?= $row['admin_fee'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Facility Renewal:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="facility_renewal" size="15" value="<?= $row['facility_renewal'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Auth Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="authlimit" size="15" value="<?= $row['authlimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Daily Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="dailylimit" size="15" value="<?= $row['dailylimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Weekly Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="weeklimit" size="15" value="<?= $row['weeklimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Per Member Daily Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="memdailylimit" size="15" value="<?= $row['memdailylimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Per Member Weekly Limit:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="memweeklimit" size="15" value="<?= $row['memweeklimit'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Letter Amount:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="letteramount" size="15" value="<?= $row['letteramount'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Clas. Min. Amount:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="clasmin" size="15" value="<?= $row['clasmin'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>No of Cheque Sheets:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="cheque_pages" size="15" value="<?= $row['cheque_pages'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Country Prefix:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="phoneprefix" size="15" value="<?= $row['phoneprefix'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Category Groups:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('catgroups',$no,'','',$row['catgroups']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Club:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('club',$no,'','',$row['club']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Gold:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('gold',$no,'','',$row['gold']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>50% Plus Club:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="fiftyName" size="15" value="<?= $row['fiftyName'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Gold Club:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="goldName" size="15" value="<?= $row['goldName'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Service Setup Fee:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="setup_fee" size="15" value="<?= $row['setup_fee'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display E Rewards:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('erewards',$yesno,'','',$row['erewards']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Display All Trades:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('alltrades',$yesno,'','',$row['alltrades']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>EBT/EPT:</b></td>
    <td bgcolor="#FFFFFF" align="left"><?= form_select('logo',$et,'','',$row['logo']); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Lic ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email_lic" size="10" value="<?= $row['email_lic'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Email Staff ID:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="email_staff" size="10" value="<?= $row['email_staff'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Default Area:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><input type="text" name="DefaultArea" size="10" value="<?= $row['DefaultArea'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Emails:</b></td>
     <td bgcolor="#FFFFFF" width="70%">Dont forget about country emails LIC/STAFF</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Add Country" name="countryadd" style="font-family: Verdana; font-size: 8pt">
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
