<?

// Admin Auction Functions.

function display_item($auctionid) {

 $query3 = dbRead("select tbl_auction_auctions.*, tbl_auction_sub_categories.*, UNIX_TIMESTAMP(tbl_auction_auctions.ends) as unix_ends from tbl_auction_auctions, tbl_auction_sub_categories where (tbl_auction_auctions.category = tbl_auction_sub_categories.cat_id) and tbl_auction_auctions.id = '$auctionid'");
 $row3 = mysql_fetch_assoc($query3);

 $query2 = dbRead("SELECT tbl_auction_auctions.id, tbl_auction_categories.cat_name as CatName, tbl_auction_categories.cat_id as CatID, tbl_auction_sub_categories.cat_name as SubCat, tbl_auction_sub_categories.cat_id as SubID FROM tbl_auction_auctions,tbl_auction_sub_categories, tbl_auction_categories WHERE (((tbl_auction_auctions.id = $auctionid) AND (tbl_auction_auctions.category = tbl_auction_sub_categories.cat_id)) AND (tbl_auction_categories.cat_id = tbl_auction_sub_categories.parent_id))");
 $row2 = mysql_fetch_assoc($query2);

 $time_remain = get_time_remain($row3);

if(!$row3[title]) {
 $Title = "No Product Name";
} else {
 $Title = $row3[title];
}

?>
<TABLE WIDTH=640 BORDER=0 CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD width="26">
    <IMG SRC="http://www.ebanctrade.com/members/images/divid_01.jpg" ALT="" width="26" height="28"></TD>
    <TD background="http://www.ebanctrade.com/members/images/divid_02.jpg" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Categories&nbsp;&gt;&nbsp;<?= $row2[CatName] ?></strong></font><font size="2" face="Arial, Helvetica, sans-serif"><strong>&nbsp;&gt;&nbsp;<?= $row2[SubCat] ?></strong></font></p>
      </TD>
    <TD width="26">
    <IMG SRC="http://www.ebanctrade.com/members/images/divid_04.jpg" ALT="" width="26" height="28"></TD>
    <TD width="100%" valign="middle" background="http://www.ebanctrade.com/members/images/divid_05.jpg"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Auction ID: EBT-AU-<?= $row3[id] ?>&nbsp;&nbsp;</font></TD>
  </TR>
</TABLE>
<table width="640" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="999" colspan="5" valign="bottom"><div align="right">
        <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#3399FF">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="10">
                <tr>
                  <td width="600" valign="top" bgcolor="#DDDDDD"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?= $Title ?></strong> &nbsp;&nbsp;<br><? if($row3[auction_type] == 2) { print "Dutch Auction"; } else { print "Standard Auction"; } ?></font></td>
                  <td width="153" valign="top" bgcolor="#DDDDDD"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">$<?= number_format($row3[current_bid],2); ?><br><?= number_format($row3[trade_percent]) ?>% Trade</font></div></td>
                  <td width="170" valign="top" bgcolor="#DDDDDD"> <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $time_remain ?><br>Place a Bid</font></div></td>
                </tr>
                <tr bgcolor="#EFEFEF">
                  <td colspan="3" valign="top">
                    <div align="left">
                      <font size="2"><?= nl2br($row3[description]) ?></font><br><br>
						<div align="center">
						 <? show_images($auctionid) ?>
                         <br>
                         <? seller_details($auctionid); ?>
                         <br>
						 <? display_auction_close($auctionid); ?>
						 <br>
                         </div>
                    </div>
                   </td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
<?

}

function show_images($auctionid) {

if(is_file("/home/etxint/public_html/members/auctionimages/".$auctionid.".1.jpg")) {
 $image = "<a href=\"javascript:openwindow('includes/imageview.php?id=".$auctionid."')\"><img src=\"http://www.ebanctrade.com/members/auctionimages/".$auctionid.".1.jpg\" border=\"0\"></a>";
} else {
 $image = "<img src=\"http://www.ebanctrade.com/members/auctionimages/n-a.gif\" border=\"0\">";
}

?>
 <table width="302" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
   <td>
    <table width="301" border="0" cellpadding="5" cellspacing="0" bgcolor="#999999">
     <tr>
      <td width="100%" bgcolor="#666666" align="center"><?= $image ?></td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
<?

}

function seller_details($auctionid) {

$query = dbRead("select * from tbl_auction_auctions where id = '$auctionid'");
$row = mysql_fetch_assoc($query);

$query2 = dbRead("select avg(rate) as AVGRate from tbl_auction_rate where rated_user_id = '$row[user]'");
$row2 = mysql_fetch_array($query2);

$query3 = dbRead("select companyname from members where memid = '$row[user]'");
$row3 = mysql_fetch_assoc($query3);

$Average_Rate = number_format($row2[AVGRate],2);
$Average_Rate_Text = get_average_text($Average_Rate);

$DeliveryDetails = get_shipping($row);

$PaymentMethods = get_payment($row);

?>
<hr align="center" width="300" size="1" noshade>
<TABLE width=300 cellpadding=1 cellspacing=0 border=0>
 <TR bgcolor=#ffcc66>
  <TD align=center bgcolor="#6699CC"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Auction Seller and Shipping Details</strong></font> </TD>
 </TR>
</TABLE>
<table width="300" border="0" cellspacing="0" cellpadding="1">
 <tr>
  <td colspan="2" bgcolor="#6699CC"><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
 <tr>
  <td width="119" valign="top"> <div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Seller:</strong></font></div></td>
  <td width="253" valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $row3[companyname] ?></font></td>
 </tr>
 <tr>
  <td valign="top"> <div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Seller Rating:</strong></font></div></td>
  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $Average_Rate_Text ?> (<?= $Average_Rate ?>)</font></td>
 </tr>
 <tr>
  <td valign="top"> <div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Location:</strong></font></div></td>
  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $row[location] ?></font></td>
 </tr>
 <tr>
  <td valign="top"> <div align="left"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment:</strong></font></font></div></td>
  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $PaymentMethods ?></font></td>
 </tr>
 <tr>
  <td valign="top"> <div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Delivery:</strong></font></div></td>
  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $DeliveryDetails?></font></td>
 </tr>
</table>
</td>
</tr>
</table>
<hr align="center" width="300" size="1" noshade>

<?

}

function get_shipping($row) {

 $shippingid = $row[shipping];

 switch($shippingid) {
  case "1": return "Buyer Pays Shipping"; break;
  case "2": return "Seller Pays Shipping"; break;
 }

}

function get_payment_text($paymentno) {

 switch($paymentno) {
  case "1": return "Cheque"; break;
  case "2": return "Money Order"; break;
  case "3": return "Mastercard or Visa"; break;
  case "4": return "Wire Transfer"; break;
 }

}

function get_payment($row) {

 $payment_methods = explode(",", $row[payment]);

 $count = 1;
 foreach($payment_methods as $key => $value) {

  if($count != 1) {

   $returnval .= ", ";

  }

  $returnval .= get_payment_text($value);

  $count++;

 }

 return $returnval;

}

function get_average_text($rate) {

 if($rate == 0) { return "Not Yet Rated"; break; }

 $test = ceil($rate);

 switch($test) {
  case "1": return "Poor"; break;
  case "2": return "Satisfactory"; break;
  case "3": return "Average"; break;
  case "4": return "Good"; break;
  case "5": return "Excellent"; break;
 }

}

function display_auction_close($auctionid) {

 $timestamp_now = date("YmdHis");

 $query = dbread("select tbl_auction_auctions.*, UNIX_TIMESTAMP(ends) as EndTime from tbl_auction_auctions where id = '$auctionid'");
 $row = mysql_fetch_assoc($query);

 $query2 = dbRead("select count(id) as Seller_Auction_Count from tbl_auction_auctions where user = '$row[user]' and ends > '$timestamp_now'");
 $row2 = mysql_fetch_assoc($query2);

 $query3 = dbRead("select members.* from members where members.memid = '$row[user]'");
 $row3 = mysql_fetch_assoc($query3);

 $dis_date = date("g:ia, D jS M Y", $row[EndTime]);

?>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">This Auction closes: <?= $dis_date ?></font><br>
<br>
<?

}


?>
