<?

include("class.html.mime.mail.inc");
include("htmlMimeMail.php");


if($_REQUEST['email'] && $_REQUEST['catid']) {

	$dbgetemail=dbRead("select * from tbl_promo where email = '".addslashes($_REQUEST['email'])."'");
	$rowemail = mysql_fetch_assoc($dbgetemail);

	if($rowemail) {
	  $error = "That business has already been emailed";
	} else {

    dbWrite("insert into tbl_promo (name,email,phone,user,catid) values ('".addslashes($_REQUEST['name'])."','".addslashes($_REQUEST['email'])."','".$_REQUEST['phone']."','".$_SESSION['User']['FieldID']."','".$_REQUEST['catid']."')");

	$dbgetcat=dbRead("select * from categories where catid = ".$_REQUEST['catid']);
	$row = mysql_fetch_assoc($dbgetcat);


$text = '<font face="TTE21ECC30t00" color="#4b3322" size="5">
<p align="center"><font color="#663300" size="6"><strong><font size="5"></font></strong></font></p>
<p align="center"><font face="TTE21ECC30t00" color="#808080"><font face="Arial" color="#000000" size="3"></font></font><font face="TTE21ECC30t00" color="#808080"></font></p>
<strong><font color="#ff6600"><font size="4"><font color="#cc6600">
<p align="left"><em><font face="Comic Sans MS" color="#000000" size="2">Empire Trade Exchange is seeking your business to join our network of existing businesses accepting Empire Trade as part payment for goods and services.</font></em></p>
<p align="center"><em><font size="6"><font face="Comic Sans MS"><font color="#000000">2010</font><font color="#808080"> </font><font color="#000000">Online </font><font color="#000000">Membersh</font><font color="#000000">ip Promotion<br />
</font></font></font></em><font face="TTE21ECC30t00" color="#808080" size="5"><strong><font color="#663300" size="6"><font size="1"><font face="Arial" color="#000000" size="3"></font></font></font></strong></font></p>
<p><font face="TTE21ECC30t00"><font face="Arial" color="#663300" size="3"><strong></strong></font></font></p>
<p><font face="TTE21ECC30t00"><font face="Arial" color="#663300" size="3"><strong></strong></font></font></p>
<p align="center"><font face="TTE21ECC30t00"><font face="Arial" color="#663300" size="4"><strong><img style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; MARGIN-RIGHT: 5px; PADDING-TOP: 5px" height="231" width="350" align="left" border="0" alt="" src="http://media.ebanctrade.com/uploads/Image/Networking/2010/Feb/99e.jpg" /></strong></font></font></p>
<p align="center"><font face="TTE21ECC30t00"><font face="Arial" color="#663300" size="4"><strong></strong></font></font></p>
<p align="center"><font face="TTE21ECC30t00"><font face="Arial" color="#663300" size="4"><strong>For a limited time&nbsp;you can join<br />
Empire Trade Exchange for a<br />
one-off membership fee of<br />
<font size="6">$99</font></strong><font size="2">Inc</font> <font color="#000000" size="2">normally $495</font></font></font></p>
<p align="center"><font color="#663300" size="4"></font></p>
<p align="center"><font color="#663300" size="4"></font></p>
<p align="center">&nbsp;</p>
<p>
<table cellspacing="1" cellpadding="1" width="450" align="center" summary="" border="0">
    <tbody>
        <tr>
            <td bgcolor="#ccccc6"><font color="#cc6600"><strong><font size="2">Our Services Include</font></strong><br />
            </font></td>
            <td bgcolor="#ccccc6"><font color="#000000" size="1">&bull; Online Internet transactions<br />
            and account information.</font></td>
        </tr>
        <tr>
            <td bgcolor="#ccccc6"><font color="#000000" size="1">&bull; Dedicated Head Office&nbsp;Member <br />
            Support&nbsp;for all members.</font></td>
            <td bgcolor="#ccccc6"><font color="#000000" size="1">&bull; Online Auctions, Classifieds<br />
            and Product Catalogue<br />
            available to members.<br />
            </font></td>
        </tr>
        <tr>
            <td bgcolor="#ccccc6"><font color="#000000" size="1">&bull; Interactive Website to<br />
            promote your business and<br />
            services.</font></td>
            <td bgcolor="#ccccc6"><font color="#000000" size="1">&bull; Membership Directory of<br />
            goods and services available<br />
            in hard copy and online.<br />
            </font></td>
        </tr>
        <tr>
            <td bgcolor="#ccccc6"><font color="#000000" size="1">&bull; An established network for<br />
            your goods and services.<br />
            </font></td>
            <td bgcolor="#ccccc6"><font size="1"><font color="#000000">&bull; Trade dollar facility<br />
            assistance for general<br />
            purchases.</font><br />
            </font></td>
        </tr>
    </tbody>
</table>
</p>
<p align="left"><font color="#cc6600">Want more Sales <font face="TTE21ECC30t00">for your bus</font><font face="TTE21ECC30t00">i</font></font><font face="TTE21ECC30t00" color="#808080" size="5"><font color="#663300" size="6"><font color="#cc6600" size="3"><font color="#cc6600">ness?</font>&nbsp;<img style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; MARGIN-LEFT: 5px; PADDING-TOP: 5px" height="132" alt="" width="200" align="right" border="0" src="http://media.ebanctrade.com/uploads/Image/Networking/2010/Feb/99e.jpg" /><br />
</font><font size="4"><font face="TTE21ECC30t00" color="#808080"><font color="#663300" size="3"><font color="#cc6600">Want more available cash?</font><br />
</font></font></font></font></font><font face="TTE21ECC30t00" color="#808080" size="5"><font color="#663300" size="6"><font size="1"><font size="2"><font color="#000000"><font face="Arial"><br />
When you join Empire Trade Exchange, you are joining a network of thousands of other businesses buying and selling from one another using a combination of Australian Dollars (cash) and Empire Trade credits (trade).</font></font></font></font></font></font> </p>
<p><font face="Arial" color="#000000" size="2">Unlike other trade exchanges Empire Trade allows members to accept cash to cover some of the product costs plus trade credits in the sale of goods and services.</font>&nbsp;</p>
<font face="TTE21ECC30t00" color="#808080" size="5"><font color="#663300" size="6"><font size="1"><font face="Arial" color="#000000" size="3">
<p><font size="4"><font color="#cc6600" size="3">What are the benefits of Joining?<br />
</font></font><br />
<font face="Arial" size="2">You will attract more buyers (sales) for your business as other members of the network actively seek you out when they are searching for a supplier of your type of business. </font></p>
<p><font face="Arial"><font size="2">When you buy from another member using your trade dollars you are conserving your cash.</font>&nbsp;</font>&nbsp;</p>
<p align="center"><strong><font size="4"><font color="#cc6600" size="3">Have you heard of barter before?</font><br />
</font><font color="#000000" size="2"><a href="http://media.ebanctrade.com/uploads/comparison.JPG"><font color="#cc6600">Click here</font></a> to compare our fees to that of other popular exchanges</font></strong>&nbsp;</p>
</font></font></font></font><font color="#cc6600"><font size="4">
<p align="center"><font size="3">To find out more phone&nbsp;Empire on&nbsp;07 5437 7220 or reply to this email with your contact details and we will call you shortly.<br />
<font color="#000000">&nbsp;</font></font></p>
<p align="center"><font color="#000000" size="3">To receive your membership at $99 log on to</font> <a href="http://www.etxint.com"><font color="#cc6000" size="3">www.etxint.com</font></a><font color="#000000"><font size="3"> click on <font style="BACKGROUND-COLOR: #cc6600"><font color="#ffffff">Join</font> Now</font> complete the online application and enter the promotional code&nbsp;<u><font style="BACKGROUND-COLOR: #cccccc">trade</font></u> </font></font><font color="#cc6600" size="1">*Limited time only</font><br />
</p>
</font></font>
<p align="center"><br />
<font color="#000000" size="2"><em>Xchanging the way you do business...</em></font></p>
<P><SPAN
            style="FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 9px">If you feel that
            your email was obtained by error, or you no longer wish to receive
            email from us, please <A
            href="mailto:hq@au.etxint.com?subject=Please remove me from your 2010 Membership Promotion email list">select
            here</A> and hit send.</SPAN></P>
<p><font color="#000000" face="Arial"><font size="2">Regards<br>'.$_SESSION['User']['Name'].'</font>&nbsp;</font>&nbsp;</p>
</font></font></font></strong></font>';

    if(!$_REQUEST['name']) {
	  $nn = "Business Owner";
	} else {
	  $nn = $_REQUEST['name'];
	}

    $text = get_html_template($_SESSION['User']['CID'], $nn, $text);
    // define carriage returns for macs and pc's
    define('CRLF', "\r\n", TRUE);
    unset($attachArray);
    unset($addressArray);

    //$buffer = taxinvoice($query,$_REQUEST[stationery],'',true);
   	//$attachArray[] = array($buffer, 'taxinvoice.pdf', 'base64', 'application/pdf');

	if(strstr($_REQUEST['email'], ";")) {
		$emailArray = explode(";", $_REQUEST['email']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), $row['category']);
		}
	} else {
		$addressArray[] = array(trim($_REQUEST['email']), $row['category']);
	}

	sendEmail( $_SESSION['User']['EmailAddress'], getWho($_SESSION[Country][logo], 1), $_SESSION['User']['EmailAddress'], 'Membership Promotion ', $_SESSION['User']['EmailAddress'], getWho($row2[logo], 1), $text, $addressArray, $attachArray);


    Print "<font color ='#0000FF'><b>Email Sent</b></font>";
   }

} elseif(!$_REQUEST['email']) {
	$error .= "You must enter a Email Address";
} elseif(!$_REQUEST['catid']) {
	$error .= "You must select a Category";
}

?>
<html>
<head>
</head>
<body<? if(!$_REQUEST['memsearch']) { ?> onload="javascript:setFocus('frm','name');" <? } ?>>

<form method="post" action="body.php?page=promo_email" name="frm">
<table width="620" cellspacing="0" cellpadding="1" border="0">
<tr>
<td class="Border">
  <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse" bordercolor="#111111">
    <tr>
     <td colspan="5" align="center" class="Heading2">Send Promo Email</td>
    </tr>
    <tr>
     <td colspan="5" align="center" class="Heading2"><font color ='#FF0000'><b><?= $error; ?></b></font></td>
    </tr>
  <tr>
  <tr>
      <td width="100" height="30" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
      <td height="30" bgcolor="#FFFFFF"><select name="catid"><option selected value=""><?= get_word("160") ?></option>
<?
	    if(($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') || ($_SESSION['User']['lang_code'] != $Crow['Langcode'] && $Crow['english'] == 'N')) {
	      $cat = " order by engcategory";
	      $sel = " engcategory as category, catid as catid ";
	    } else {
	      $cat = " order by category";
	      $sel = " category, catid ";
	    }

		$dbgetcat=dbRead("select $sel from categories where CID like '".addslashes($_SESSION['Country']['countryID'])."'$cat ASC");
		while($row = mysql_fetch_assoc($dbgetcat)) {
			?>
			<option <? if ($row['catid'] == $_REQUEST['catid']) { echo "selected ";} ?>value="<?= $row['catid'] ?>"><?= $row['category'] ?></option>
			<?
		}
?>
	  </select> </td>
  </tr>
  <tr>
      <td align="right" class="Heading2" width="100"><b>Name:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="name" <?if($error) {?>value="<?= encode_text2(trim($_REQUEST['name'])) ?>"<? }?> size="35" tabindex="1"></td>
  </tr>
  <tr>
      <td align="right" class="Heading2" width="100"><b>Email:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="email" <?if($error) {?>value="<?= encode_text2(trim($_REQUEST['email'])) ?>"<? }?> size="35" tabindex="2"></td>
  </tr>
  <tr>
      <td align="right" class="Heading2" width="100"><b>Phone:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="phone" <?if($error) {?>value="<?= encode_text2(trim($_REQUEST['phone'])) ?>"<? }?> size="35" tabindex="3"></td>
  </tr>
  <tr>
      <td width="100" height="30" class="Heading2"></td>
      <td width="450" height="30" colspan="4" bgcolor="#FFFFFF"><input type="Submit" value="Submit"></td>
  </tr>
  </table>
</td>
</tr>
</table>
</form>
