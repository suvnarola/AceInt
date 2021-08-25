<?
 include("/home/etxint/admin.etxint.com/includes/modules/class.paging.php");

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form ENCTYPE="multipart/form-data" method="POST" action="main.php?page=auctions&auction=<?= $_GET[auction] ?>&tab=<?= $_GET[tab] ?>">

<?

// Some Setup.

 $tabarray = array('Summary','View Active Auctions','View Closed Auctions','Pending Auctions');

 $timestampnow = date("YmdHis");

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Summary") {

 display_initial();

} elseif($_GET[tab] == "View Active Auctions") {

 if($_REQUEST[UpdateAuction]) {

  update_other_auction($_REQUEST[auction]);
  display_auctions(1);

 } else {

  if($_REQUEST[DelAuction]) {

   dbWrite("delete from tbl_auction_auctions where id = '$_REQUEST[auction]'");
   display_auctions(1);

  } elseif($_REQUEST[EditAuction]) {

   edit_auction($_REQUEST[auction]);

  } else {

   display_auctions(1);

  }

 }

} elseif($_GET[tab] == "View Closed Auctions") {

 if($_REQUEST[EditAuction]) {

  edit_auction($_REQUEST[auction]);

 } else {

  display_auctions(2);

 }

} elseif($_GET[tab] == "Pending Auctions") {

 if($_REQUEST[DelAuction]) {

  dbWrite("delete from tbl_auction_auctions_pending where id = '$_REQUEST[auction]'");
  display_auctions(3);

 } else {

  if($_REQUEST[UpdateAuction]) {

   if($_REQUEST[MoveAuction]) {

    update_pending_auction($_REQUEST[auction]);
    activate_auction($_REQUEST[auction]);
    display_auctions(3);

   } else {

    update_pending_auction($_REQUEST[auction]);
    display_auctions(3);

   }

  } else {

   if($_REQUEST[EditAuction]) {

    edit_pending_auction($_REQUEST[auction]);

   } else {

    display_auctions(3);

   }

  }

 }

}

?>

</body>

</html>

<?

function activate_auction($auction) {

 global $timestampnow;

 $query = dbRead("select tbl_auction_auctions_pending.*, tbl_members_email.email as email, members.CID as CID from tbl_auction_auctions_pending, tbl_members_email, members where (tbl_auction_auctions_pending.user = members.memid) and (tbl_auction_auctions_pending.user = tbl_members_email.acc_no) and type = 3 and id = '$auction'");
 $row = mysql_fetch_assoc($query);

 foreach($row as $key => $value) {

  $row2[$key] = addslashes($value);

 }

 $extratime_tmp = (60*60*24)*$row[duration];
 $extratime = mktime()+$extratime_tmp;
 $timestampends = date("YmdHis", $extratime);

 dbWrite("insert into tbl_auction_auctions (id,user,title,starts,description,category,minimum_bid,trade_percent,reserve_price,buynow_price,duration,increment,location,location_zip,shipping,ship_amount,payment,ends,current_bid,photo_uploaded,Display,notified,GST,auction_type) values ('$row2[id]','$row2[user]','$row2[title]','$timestampnow','$row2[description]','$row2[category]','$row2[minimum_bid]','$row2[trade_percent]','$row2[reserve_price]','$row2[buynow_price]','$row2[duration]','$row2[increment]','$row2[location]','$row2[location_zip]','$row2[shipping]','$row2[ship_amount]','$row2[payment]','$timestampends','$row2[current_bid]','$row2[photo_uploaded]','Y','N','$row2[GST]','1')");
 dbWrite("delete from tbl_auction_auctions_pending where id = '$auction'");
 dbWrite("insert into tbl_auction_log (Type,Memid,EmailAddress,DateTime,AuctionID) values ('8','17404','auctions@ebanctrade.com',now(),'$auction')");

 $text = "This email is to advise you that your listing on the Bid n Buy site, Item ID: $row2[id], ($row2[title]) has been approved, and bidding is able to commence.\r\n\r\nWe wish you a very successful auction.\r\n\r\nThe Auction Administrator";
 $text = get_html_template(1,"BID'n'BUY Seller",$text);

 if(strstr($row[email], ";")) {
	$emailArray = explode(";", $row[email]);
	foreach($emailArray as $key => $value) {
		$addressArray[] = array(trim($value), getWho($_SESSION[Country][logo], 1). " Auction");
	}
 } else {
	$addressArray[] = array($row[email], getWho($_SESSION[Country][logo], 1). " Auction");
 }

 $addressArray[] = array($row[email], getWho($_SESSION[Country][logo], 1). "Auction");
 sendEmail("auctions@au.empireXchange.com", "Empire Trade Auction", "auctions@au.empireXchange.com", "Auction ID: ".$row2[id]." (".$row2[title].") - Approved", "auctions@au.empireXchange.com", "Empire Trade Auctions", $text, $addressArray);

}

function display_initial() {

 global $timestampnow;

 $query1 = dbRead("select count(*) as PendingCount from tbl_auction_auctions_pending");
 $row1 = mysql_fetch_assoc($query1);

 $query2 = dbRead("select count(*) as ActiveCount from tbl_auction_auctions where tbl_auction_auctions.ends > $timestampnow");
 $row2 = mysql_fetch_assoc($query2);

 $query3 = dbRead("select count(*) as ClosedCount from tbl_auction_auctions where tbl_auction_auctions.ends < $timestampnow");
 $row3 = mysql_fetch_assoc($query3);

 $TotalCount = $row1[PendingCount]+$row2[ActiveCount]+$row3[ClosedCount];

 ?>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td colspan="2" class="Heading" align="center">SUMMARY</td>
      </tr>
      <tr>
        <td nowrap class="Heading2" width="100%">ITEM</td>
        <td class="Heading2" width="35" nowrap>AMOUNT</td>
      </tr>
      <tr>
        <td nowrap bgcolor="#FFFFFF"><a href="main.php?page=auctions&auction=<?= $_GET[auction] ?>&tab=Pending Auctions" class="nav">Pending Auctions.</a></td>
        <td bgcolor="#FFFFFF"><?= $row1[PendingCount] ?></td>
      </tr>
      <tr>
        <td nowrap bgcolor="#FFFFFF"><a href="main.php?page=auctions&auction=<?= $_GET[auction] ?>&tab=View Active Auctions" class="nav">Active Auctions.</td>
        <td bgcolor="#FFFFFF"><?= $row2[ActiveCount] ?></td>
      </tr>
      <tr>
        <td nowrap bgcolor="#FFFFFF"><a href="main.php?page=auctions&auction=<?= $_GET[auction] ?>&tab=View Closed Auctions" class="nav">Closed Auctions.</td>
        <td bgcolor="#FFFFFF"><?= $row3[ClosedCount] ?></td>
      </tr>
      <tr>
        <td nowrap bgcolor="#FFFFFF">Total Auctions.</td>
        <td bgcolor="#FFFFFF"><?= $TotalCount ?></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
 <?

}

function display_auctions($greatless) {

 global $timestampnow;

 if($greatless == 1) {

  $BA = ">";
  $Top_Title = "ACTIVE AUCTIONS";

 } elseif($greatless == 2) {

  $BA = "<";
  $Top_Title = "CLOSED AUCTIONS";

 } elseif($greatless == 3) {

  $Top_Title = "PENDING AUCTIONS";

 }

?>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td colspan="9" class="Heading" align="center"><?= $Top_Title ?></td>
      </tr>
      <tr>
        <td nowrap class="Heading2" class="Heading2" width="30" nowrap>ID</td>
        <td class="Heading2" class="Heading2">PRODUCT</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>HITS</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>TIME LEFT</td>
        <td class="Heading2" class="Heading2" align="right" nowrap>START</td>
        <td class="Heading2" class="Heading2" align="right" nowrap>PRICE</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>EDIT</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>VIEW</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>DEL</td>
      </tr>
      <?

       if($greatless == 3) {

        //$SQL = dbRead("select tbl_auction_auctions_pending.*, UNIX_TIMESTAMP(tbl_auction_auctions_pending.ends) as unix_ends FROM tbl_auction_auctions_pending order by tbl_auction_auctions_pending.id DESC");
        $SQL = "select tbl_auction_auctions_pending.*, UNIX_TIMESTAMP(tbl_auction_auctions_pending.ends) as unix_ends FROM tbl_auction_auctions_pending order by tbl_auction_auctions_pending.id DESC";

       } else {

        if($greatless == 2) {
		 $sort = "DESC";
		}

        //$SQL = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(tbl_auction_auctions.ends) as unix_ends FROM tbl_auction_auctions where tbl_auction_auctions.ends $BA $timestampnow order by tbl_auction_auctions.ends DESC");
        $SQL = "select tbl_auction_auctions.*, UNIX_TIMESTAMP(tbl_auction_auctions.ends) as unix_ends FROM tbl_auction_auctions where tbl_auction_auctions.ends $BA $timestampnow order by tbl_auction_auctions.ends $sort";

       }

		 $p = new Pager;
		 $limit = 50;

		 $start = $p->findStart($limit);
		 $rs = dbRead($SQL,"etradebanc");
		 $count = mysql_num_rows($rs);

		 /* Find the number of pages based on $count and $limit */
		 $pagenos = $p->findPages($count, $limit);

		 /* Now we use the LIMIT clause to grab a range of rows */
		 $rs = dbRead("$SQL LIMIT ".$start.", ".$limit."","etradebanc");

		 /* Now get the page list and echo it */
		 $pagelist = $p->pageList($_REQUEST['pageno'], $pagenos);

		 $SQLQuery = $rs;

		?>
		<tr bgcolor="#FFFFFF">
		<td colspan="9" align="center">
			<table>
			<tr>
			<td><?= $pagelist ?></td>
			</tr>
			</table>
		</td>
		</tr>

		<?

       while($row = mysql_fetch_assoc($SQLQuery)) {

       	if($greatless == 1) {
       	 $time_left = get_time_remain($row);
       	 $tab = "View Active Auctions";
       	} elseif($greatless == 2) {
       	 $time_left = date("jS M Y", $row[unix_ends]);
       	 $tab = "View Closed Auctions";
       	} elseif($greatless == 3) {
       	 $time_left = "Pending";
       	 $tab = "Pending Auctions";
       	}

		if(!$row[title]) {

		 $Title = "No Product Name";

		} else {

		 $Title = $row[title];

		}

		if($row[current_bid] > $row[minimum_bid]) {
	 	 $frcolor = "#FF0000";
   		} else {
	 	 $frcolor = "#000000";
   		}

      ?>
      <tr bgcolor="#FFFFFF">
        <td><a href="main.php?page=logs&auction=<?= $row[id] ?>" class="nav"><?= $row[id] ?></a></td>
        <td><? if($row[Display] == "Y") { echo "<b>"; } ?><?= $Title ?> [<?= $row[Display] ?>]<? if($row[Display] == "Y") { echo "</b>"; } ?></td>
        <td align="center"><?= $row[hits] ?></td>
        <td align="right" nowrap><?= $time_left ?></td>
        <td align="right">$<?= number_format($row[minimum_bid],2) ?></td>
        <td align="right"><font color="<?= $frcolor ?>">$<?= number_format($row[current_bid],2) ?></font></td>
        <td align="right"><a href="main.php?page=auctions&auction=<?= $row[id] ?>&tab=<?= $tab ?>&EditAuction=True" class="nav">EDIT</a></td>
        <td align="right"><a href="main.php?page=auctions&auction=<?= $row[id] ?>&tab=<?= $tab ?>&ViewAuction=True" class="nav">VIEW</a></td>
        <td align="right"><a href="#" class="nav" onclick="ConfirmDelete('main.php?page=auctions&auction=<?= $row[id] ?>&tab=<?= $tab ?>&DelAuction=True','<?= $row[id] ?>')">DEL</a></td>
      </tr>
      <?

       }
      ?>
      </table>
    </td>
  </tr>
</table>
</form>

<?

}

function edit_pending_auction($auctionid) {

$query = dbRead("select tbl_auction_auctions_pending.*, UNIX_TIMESTAMP(starts) as starts, UNIX_TIMESTAMP(ends) as ends from tbl_auction_auctions_pending where id = '$auctionid'");
$row = mysql_fetch_assoc($query);

?>
<input type="hidden" name="UpdateAuction" value="1">
<input type="hidden" name="auction" value="<?= $row[id] ?>">
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td class="Heading" align="center" colspan="2">AUCTION EDIT</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">ID:</td>
        <td bgcolor="#FFFFFF"><?= $row[id] ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Member:</td>
        <td bgcolor="#FFFFFF"><?

         $query2 = dbRead("select * from members where memid = '$row[user]'");
         $row2 = mysql_fetch_assoc($query2);

         echo $row2[companyname];

        ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Title:</td>
        <td bgcolor="#FFFFFF"><input type="text" size="30" name="title" value="<?= $row[title] ?>"></td>
      </tr>

      <tr>
        <td class="Heading2" align="right" width="140">Description:</td>
        <td bgcolor="#FFFFFF"><textarea cols="57" rows="5" name="description"><?= $row[description] ?></textarea></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Category:</td>
        <td bgcolor="#FFFFFF"><SELECT NAME="category">
        <?

         $query2 = dbRead("SELECT tbl_auction_categories.cat_name as Main_Category, tbl_auction_sub_categories.cat_name as Sub_Category, tbl_auction_sub_categories.cat_id FROM tbl_auction_sub_categories,tbl_auction_categories WHERE (tbl_auction_sub_categories.parent_id = tbl_auction_categories.cat_id ) order by tbl_auction_categories.cat_name");
         while($row2 = mysql_fetch_assoc($query2)) {

          if($Main_Cat == $row2[Main_Category]) {

           $Spaces = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;$row2[Sub_Category]";
           ?>
            <option value="<?= $row2[cat_id] ?>"<? if($row[category] == $row2[cat_id]) { echo " selected"; } ?>><?= $Spaces ?></option>
           <?

          } else {

           $Spaces = "$row2[Main_Category]";
           $Spaces2 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;$row2[Sub_Category]";

           ?>
            <option value="<?= $row2[cat_id] ?>"><?= $Spaces ?></option>
            <option value="<?= $row2[cat_id] ?>"<? if($row[category] == $row2[cat_id]) { echo " selected"; } ?>><?= $Spaces2 ?></option>
           <?

          }

          $Main_Cat = $row2[Main_Category];

         }

        ?>
	   </SELECT></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Starting Price:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text SIZE = 10 NAME=minimum_bid VALUE="<?= $row[minimum_bid] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Duration Days:</td>
        <td bgcolor="#FFFFFF"><input type="text" size="10" name="duration" <?= $Disabled ?> value="<?= $row[duration] ?>">&nbsp;<?= $Dis_Days ?> From Start of Auction.</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Trade Percent:</td>
        <td bgcolor="#FFFFFF">
                <select NAME=trade_percent>
                  <option value="50"<? if($row[trade_percent] == "50") { echo " selected"; } ?>>50%</option>
                  <option value="55"<? if($row[trade_percent] == "55") { echo " selected"; } ?>>55%</option>
                  <option value="60"<? if($row[trade_percent] == "60") { echo " selected"; } ?>>60%</option>
                  <option value="65"<? if($row[trade_percent] == "65") { echo " selected"; } ?>>65%</option>
                  <option value="70"<? if($row[trade_percent] == "70") { echo " selected"; } ?>>70%</option>
                  <option value="75"<? if($row[trade_percent] == "75") { echo " selected"; } ?>>75%</option>
                  <option value="80"<? if($row[trade_percent] == "80") { echo " selected"; } ?>>80%</option>
                  <option value="85"<? if($row[trade_percent] == "85") { echo " selected"; } ?>>85%</option>
                  <option value="90"<? if($row[trade_percent] == "90") { echo " selected"; } ?>>90%</option>
                  <option value="95"<? if($row[trade_percent] == "95") { echo " selected"; } ?>>95%</option>
                  <option value="100"<? if($row[trade_percent] == "100") { echo " selected"; } ?>>100%</option>
               </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Reserve Price:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="reserve_price" SIZE=10 VALUE="<?= $row[reserve_price] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Buy Now Price:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="buynow_price" SIZE=10 VALUE="<?= $row[buynow_price] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">GST:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=radio NAME=GST VALUE="Y" <? if($row[GST] == "Y") { echo "checked"; } ?>>GST Included<BR><INPUT TYPE=radio NAME=GST VALUE="N" <? if($row[GST] == "N") { echo "checked"; } ?>>GST to be paid in Cash&nbsp;</td>
      </tr>
      <!--
      <tr>
        <td class="Heading2" align="right" width="140">Market Value:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text NAME="market_value" SIZE=10 VALUE="<?= $row[market_value] ?>" onKeyPress="return number(event)"></td>
      </tr>
      -->
      <tr>
        <td class="Heading2" align="right" width="140">Bid Increment:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="increment" SIZE=10 VALUE="<?= $row[increment] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140"><span lang="en-au">
        Country:</span></td>
        <td bgcolor="#FFFFFF">
                <SELECT NAME="country">
                 <?

                  $query2 = dbRead("select * from country where Display='Yes' order by name asc");
                  while($row2 = mysql_fetch_assoc($query2)) {

                   ?>
                    <option value="<?= $row2[countryID] ?>" <? if($row[country] == $row2[countryID]) { echo "selected"; } ?>><?= $row2[name] ?></option>
                   <?

                  }

                 ?>
                </SELECT></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Location:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text NAME="location" SIZE=25 VALUE="<?= $row[location] ?>"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Post Code:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text SIZE=8 NAME=location_zip VALUE="<?= $row[location_zip] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Shipping:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=radio NAME=shipping VALUE="1" <? if($row[shipping] == 1) { echo "checked"; } ?>>Buyer pays shipping expenses<BR><INPUT TYPE=radio NAME=shipping VALUE="2" <? if($row[shipping] == 2) { echo "checked"; } ?>>Seller pays shipping expenses&nbsp;</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140" nowrap>Payment Details:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="1" <? if(strstr($row[payment], "1")) { echo "checked"; } ?>>  Cheque</FONT><BR><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="2" <? if(strstr($row[payment], "2")) { echo "checked"; } ?>>  Money Order</FONT><BR><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="3" <? if(strstr($row[payment], "3")) { echo "checked"; } ?>>  MasterCard or Visa</FONT><BR><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="4"<? if(strstr($row[payment], "4")) { echo "checked"; } ?>>  Wire Transfer</FONT></td>
      </tr>
      <tr>
       <TD class="Heading2" WIDTH="260" ALIGN="right"><b>Add Picture</b></TD>
       <TD WIDTH="486" bgcolor="#FFFFFF"><INPUT TYPE=file SIZE=20 NAME=AddPicture></TD>
      </tr>
      <tr>
       <td class="Heading2" WIDTH="260" ALIGN="right"><b>Images:</b></td>
       <td bgcolor="#FFFFFF">
             <?

             $Pictures = getDirList("/home/etxint/public_html/members/auctionimages");

              foreach($Pictures as $key => $value) {

          	   $Test = substr(strrev(strrchr(strrev($value), ".")), 0, -1);

          	   ?><!-- Test Number: <?= $Test ?> --><?

               if($Test == $row[id]) {

                ?><img border="0" alt="Delete Image" src="http://www.ebanctrade.com/members/auctionimages/<?= $value ?>"><a href="general.php?DeletePicture=true&auction=<?= $row[id] ?>&Image=<?= $value ?>&tab=<?= $_GET[tab] ?>">&nbsp;&nbsp;Delete</a>&nbsp;<?

               }

              }

        ?></td>
         </tr>
         <tr>
        <td class="Heading2" align="right" width="140">&nbsp;</td>
        <td bgcolor="#FFFFFF" align="right"><input type="Submit" value="Move To Active Auctions" name="MoveAuction"><input type="Submit" value="Update Auction" name="Submit">&nbsp;<input type="Reset" name="Reset" value="Reset"</td>
      </tr>
     </table>
    </td>
  </tr>
</table>
<?

}

function update_pending_auction($auction) {

 // Payment Details.

 $paymentdetails = $_POST[payment];
 if($paymentdetails) {
  $count = 1;
  foreach($paymentdetails as $key => $value) {
   if($count != 1) {
    $Database_Payment_Details .= ",";
   }
   $Database_Payment_Details .= $value;
   $count++;
  }
 }

 // Stupid Characters

 foreach($_REQUEST as $i => $value) {
  $TRANSFER_VARS[$i]=addslashes($value);
 }

 $query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(starts) as starts, UNIX_TIMESTAMP(ends) as ends from tbl_auction_auctions where id = '$TRANSFER_VARS[auction]'");
 $row = mysql_fetch_assoc($query);

  // Ending Time

  $extratime_tmp = (60*60*24)*$TRANSFER_VARS[duration];
  $extratime = $row[starts]+$extratime_tmp;
  $new_ends = date("Y-m-d H:i:s", $extratime);


 // Update Auction.

 //dbWrite("update tbl_auction_auctions_pending set title = '$TRANSFER_VARS[title]', duration = '$TRANSFER_VARS[duration]', description = '$TRANSFER_VARS[description]', category = '$TRANSFER_VARS[category]', minimum_bid = '$TRANSFER_VARS[minimum_bid]', current_bid = '$TRANSFER_VARS[minimum_bid]', trade_percent = '$TRANSFER_VARS[trade_percent]', reserve_price = '$TRANSFER_VARS[reserve_price]', buynow_price = '$TRANSFER_VARS[buynow_price]', GST = '$TRANSFER_VARS[GST]', increment = '$TRANSFER_VARS[increment]', location = '$TRANSFER_VARS[location]', location_zip = '$TRANSFER_VARS[location_zip]', shipping = '$TRANSFER_VARS[shipping]', payment = '$Database_Payment_Details' where id = '$TRANSFER_VARS[auction]'");
 dbWrite("update tbl_auction_auctions_pending set duration = '$TRANSFER_VARS[duration]', title = '$TRANSFER_VARS[title]', description = '$TRANSFER_VARS[description]', category = '$TRANSFER_VARS[category]', minimum_bid = '$TRANSFER_VARS[minimum_bid]', current_bid = '$TRANSFER_VARS[minimum_bid]', trade_percent = '$TRANSFER_VARS[trade_percent]', reserve_price = '$TRANSFER_VARS[reserve_price]', buynow_price = '$TRANSFER_VARS[buynow_price]', GST = '$TRANSFER_VARS[GST]', increment = '$TRANSFER_VARS[increment]', location = '$TRANSFER_VARS[location]', location_zip = '$TRANSFER_VARS[location_zip]', shipping = '$TRANSFER_VARS[shipping]', payment = '$Database_Payment_Details' where id = '$TRANSFER_VARS[auction]'");

 if($_FILES[AddPicture]) {

  $Pictures = getDirList("/home/etxint/public_html/members/auctionimages");

  foreach($Pictures as $key => $value) {

   $Test_tmp = $value;

   $Test = substr(strrev(strrchr(strrev($Test_tmp), ".")), 0, -1);

   if($Test == $_REQUEST[auction]) {

    $Picture_Array_tmp = explode(".", $value);
	$Picture_Array[] = $Picture_Array_tmp[1];

   }

  }

  if(is_array($Picture_Array)) {

   natsort($Picture_Array);

   $Next_Picture_Number_tmp = count($Picture_Array)-1;
   $Next_Picture_Number = $Picture_Array[$Next_Picture_Number_tmp]+1;

  } else {

   $Next_Picture_Number = 1;

  }

  $picture_name_new = "$_REQUEST[auction].$Next_Picture_Number.jpg";

  move_uploaded_file($_FILES[AddPicture][tmp_name], "/home/etxint/public_html/members/auctionimages/".$picture_name_new);
  exec("convert -geometry 300 /home/etxint/public_html/members/auctionimages/".$picture_name_new." /home/etxint/public_html/members/auctionimages/".$picture_name_new);

 }

}

function update_other_auction($auction) {

 // Check to see if there has been any bids.
 // If there has been bids only update certain things.

 $TV = form_addslashes();

 $query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(starts) as starts, UNIX_TIMESTAMP(ends) as ends from tbl_auction_auctions where id = '$auction'");
 $row = mysql_fetch_assoc($query);

 $query3 = dbRead("select id, bid, UNIX_TIMESTAMP(bidwhen) as bidwhen, bidder, quantity, members.companyname as companyname from tbl_auction_bids, members where (members.memid = tbl_auction_bids.bidder) and tbl_auction_bids.auction = '$auction' order by bidwhen desc");
 if(mysql_num_rows($query3) > 0) {

  // Bids have been made. only update duration, and payment details.

  // Ending Time

  $extratime_tmp = (60*60*24)*$TV[duration];
  $extratime = $row[starts]+$extratime_tmp;
  $new_ends = date("YmdHis", $extratime);

  //$starts = date("YmdHis", $row[starts]);

  // Payment Details.

  $paymentdetails = $_REQUEST[payment];
  if($paymentdetails) {
   $count = 1;
   foreach($paymentdetails as $key => $value) {
    if($count != 1) {
     $Database_Payment_Details .= ",";
    }
    $Database_Payment_Details .= $value;
    $count++;
   }
  }

  dbWrite("update tbl_auction_auctions set duration = '$TV[duration]', starts = '$starts', ends = '$new_ends', payment = '$Database_Payment_Details' where id = '$TV[auction]'");

 } else {

  // Ending Time

  $extratime_tmp = (60*60*24)*$TV[duration];
  $extratime = $row[starts]+$extratime_tmp;
  $new_ends = date("YmdHis", $extratime);

  // Payment Details.

  $paymentdetails = $_REQUEST[payment];

  if($paymentdetails) {
   $count = 1;
   foreach($paymentdetails as $key => $value) {
    if($count != 1) {
     $Database_Payment_Details .= ",";
    }
    $Database_Payment_Details .= $value;
    $count++;
   }
  }

  // Update Auction.

  $starts = date("YmdHis", $row[starts]);

  dbWrite("update tbl_auction_auctions set starts = '$starts', ends = '$new_ends', title = '$TV[title]', duration = '$TV[duration]', description = '$TV[description]', category = '$TV[category]', minimum_bid = '$TV[minimum_bid]', current_bid = '$TV[minimum_bid]', trade_percent = '$TV[trade_percent]', reserve_price = '$TV[reserve_price]', buynow_price = '$TV[buynow_price]', GST = '$TV[GST]', increment = '$TV[customincrement]', location = '$TV[location]', location_zip = '$TV[location_zip]', shipping = '$TV[shipping]', payment = '$Database_Payment_Details' where id = '$TV[auction]'");

 }

 // Can Add Pictures no matter what.

 if($_FILES[AddPicture]) {

  $Pictures = getDirList("/home/etxint/public_html/members/auctionimages");

  foreach($Pictures as $key => $value) {

   $Test_tmp = $value;

   $Test = substr(strrev(strrchr(strrev($Test_tmp), ".")), 0, -1);

   if($Test == $TV[auction]) {

    $Picture_Array_tmp = explode(".", $value);
	$Picture_Array[] = $Picture_Array_tmp[1];

   }

  }

  if(is_array($Picture_Array)) {

   natsort($Picture_Array);

   $Next_Picture_Number_tmp = count($Picture_Array)-1;
   $Next_Picture_Number = $Picture_Array[$Next_Picture_Number_tmp]+1;

  } else {

   $Next_Picture_Number = 1;

  }

  $picture_name_new = "$TV[auction].$Next_Picture_Number.jpg";

  move_uploaded_file($_FILES[AddPicture][tmp_name], "/home/etxint/public_html/members/auctionimages/".$picture_name_new);
  exec("convert -geometry 300 /home/etxint/public_html/members/auctionimages/".$picture_name_new." /home/etxint/public_html/members/auctionimages/".$picture_name_new);

  if ($Next_Picture_Number == 1) {

  $picture_name_new = "$TV[auction].1.jpg";
  $picture_name_new2 = "$TV[auction].jpg";

  $imagehw = GetImageSize("/home/etxint/public_html/members/auctionimages/".$picture_name_new);
  $imagewidth = $imagehw[0];
  $imageheight = $imagehw[1];
  $imgorig = $imagewidth;

  $maxheight = "60";
  $imageprop2 =($maxheight/$imageheight);
  $imagevsize2= ($imagewidth*$imageprop2);
  $imageheight2 = $maxheight;
  $imagewidth2 = ceil($imagevsize2);

  $source="/home/etxint/public_html/members/auctionimages/".$picture_name_new."";
  $dest="/home/etxint/public_html/members/auctionimages/thumb-".$picture_name_new2."";
  copy($source, $dest);

  exec("convert -geometry ".$imagewidth2."x".$imageheight2." /home/etxint/public_html/members/auctionimages/thumb-".$picture_name_new2." /home/etxint/public_html/members/auctionimages/thumb-".$picture_name_new2);

  }

 }

}

function edit_auction($auctionid) {

$query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(starts) as starts, UNIX_TIMESTAMP(ends) as ends from tbl_auction_auctions where id = '$auctionid'");
$row = mysql_fetch_assoc($query);

$query3 = dbRead("select id, bid, UNIX_TIMESTAMP(bidwhen) as bidwhen, bidder, quantity, members.companyname as companyname from tbl_auction_bids, members where (members.memid = tbl_auction_bids.bidder) and tbl_auction_bids.auction = '$auctionid' order by bidwhen desc");

$numberbids = mysql_num_rows($query3);

$Dis_Starts = date("g:ia, D jS M Y", $row[starts]);
$Dis_Ends = date("g:ia, D jS M Y", $row[ends]);
$Dis_Days_tmp = mktime()-$row[starts];
$Dis_Days = get_days($Dis_Days_tmp);

if($numberbids > 0) {

 $Disabled = " disabled";

}

?>
<input type="hidden" name="UpdateAuction" value="1">
<input type="hidden" name="auction" value="<?= $row[id] ?>">
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td class="Heading" align="center" colspan="2">AUCTION EDIT</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">ID:</td>
        <td bgcolor="#FFFFFF"><?= $row[id] ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Member:</td>
        <td bgcolor="#FFFFFF"><?

         $query2 = dbRead("select * from members where memid = '$row[user]'");
         $row2 = mysql_fetch_assoc($query2);

         echo $row2[companyname];

        ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Title:</td>
        <td bgcolor="#FFFFFF"><input type="text" size="30" name="title" value="<?= $row[title] ?>"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Started:</td>
        <td bgcolor="#FFFFFF"><?= $Dis_Starts ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Ends:</td>
        <td bgcolor="#FFFFFF"><?= $Dis_Ends ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Duration Days:</td>
        <td bgcolor="#FFFFFF"><input type="text" size="10" name="duration" <?= $Disabled ?> value="<?= $row[duration] ?>">&nbsp;<?= $Dis_Days ?> From Start of Auction.</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Description:</td>
        <td bgcolor="#FFFFFF"><textarea cols="57" rows="5" name="description"<?= $Disabled ?>><?= $row[description] ?></textarea></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Category:</td>
        <td bgcolor="#FFFFFF"><SELECT NAME="category"<?= $Disabled ?>>
        <?

         $query2 = dbRead("SELECT tbl_auction_categories.cat_name as Main_Category, tbl_auction_sub_categories.cat_name as Sub_Category, tbl_auction_sub_categories.cat_id FROM tbl_auction_sub_categories,tbl_auction_categories WHERE (tbl_auction_sub_categories.parent_id = tbl_auction_categories.cat_id ) order by tbl_auction_categories.cat_name");
         while($row2 = mysql_fetch_assoc($query2)) {

          if($Main_Cat == $row2[Main_Category]) {

           $Spaces = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;$row2[Sub_Category]";
           ?>
            <option value="<?= $row2[cat_id] ?>"<? if($row[category] == $row2[cat_id]) { echo " selected"; } ?>><?= $Spaces ?></option>
           <?

          } else {

           $Spaces = "$row2[Main_Category]";
           $Spaces2 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;$row2[Sub_Category]";

           ?>
            <option value="<?= $row2[cat_id] ?>"><?= $Spaces ?></option>
            <option value="<?= $row2[cat_id] ?>"<? if($row[category] == $row2[cat_id]) { echo " selected"; } ?>><?= $Spaces2 ?></option>
           <?

          }

          $Main_Cat = $row2[Main_Category];

         }

        ?>
	   </SELECT></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Starting Price:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text SIZE = 10 NAME=minimum_bid VALUE="<?= $row[minimum_bid] ?>" onKeyPress="return number(event)"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Trade Percent:</td>
        <td bgcolor="#FFFFFF">
                <select NAME=trade_percent<?= $Disabled ?>>
                  <option value="50"<? if($row[trade_percent] == "50") { echo " selected"; } ?>>50%</option>
                  <option value="55"<? if($row[trade_percent] == "55") { echo " selected"; } ?>>55%</option>
                  <option value="60"<? if($row[trade_percent] == "60") { echo " selected"; } ?>>60%</option>
                  <option value="65"<? if($row[trade_percent] == "65") { echo " selected"; } ?>>65%</option>
                  <option value="70"<? if($row[trade_percent] == "70") { echo " selected"; } ?>>70%</option>
                  <option value="75"<? if($row[trade_percent] == "75") { echo " selected"; } ?>>75%</option>
                  <option value="80"<? if($row[trade_percent] == "80") { echo " selected"; } ?>>80%</option>
                  <option value="85"<? if($row[trade_percent] == "85") { echo " selected"; } ?>>85%</option>
                  <option value="90"<? if($row[trade_percent] == "90") { echo " selected"; } ?>>90%</option>
                  <option value="95"<? if($row[trade_percent] == "95") { echo " selected"; } ?>>95%</option>
                  <option value="100"<? if($row[trade_percent] == "100") { echo " selected"; } ?>>100%</option>
                </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Buy Now Price:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="buynow_price" SIZE=10 VALUE="<?= $row[buynow_price] ?>" onKeyPress="return number(event)"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Reserve Price:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="reserve_price" SIZE=10 VALUE="<?= $row[reserve_price] ?>" onKeyPress="return number(event)"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">GST:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=radio NAME=GST VALUE="Y" <? if($row[GST] == "Y") { echo "checked"; } ?><?= $Disabled ?>>GST Included<BR><INPUT TYPE=radio NAME=GST VALUE="N" <? if($row[GST] == "N") { echo "checked"; } ?><?= $Disabled ?>>GST to be paid in Cash&nbsp;</td>
      </tr>
      <!--
      <tr>
        <td class="Heading2" align="right" width="140">Market Value:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text NAME="market_value" SIZE=10 VALUE="<?= $row[market_value] ?>" onKeyPress="return number(event)"<?= $Disabled ?>></td>
      </tr>
      -->
      <tr>
        <td class="Heading2" align="right" width="140">Bid Increment:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="customincrement" SIZE=10 VALUE="<?= $row[increment] ?>" onKeyPress="return number(event)"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140"><span lang="en-au">
        Country:</span></td>
        <td bgcolor="#FFFFFF">
                <SELECT NAME="country"<?= $Disabled ?>>
                 <?

                  $query2 = dbRead("select * from country where Display='Yes' order by name asc");
                  while($row2 = mysql_fetch_assoc($query2)) {

                   ?>
                    <option value="<?= $row2[countryID] ?>" <? if($row[country] == $row2[countryID]) { echo "selected"; } ?>><?= $row2[name] ?></option>
                   <?

                  }

                 ?>
                </SELECT></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Location:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text NAME="location" SIZE=25 VALUE="<?= $row[location] ?>"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Post Code:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text SIZE=8 NAME=location_zip VALUE="<?= $row[location_zip] ?>" onKeyPress="return number(event)"<?= $Disabled ?>></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Shipping:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=radio NAME=shipping VALUE="1" <? if($row[shipping] == 1) { echo "checked"; } ?><?= $Disabled ?>>Buyer pays shipping expenses<BR><INPUT TYPE=radio NAME=shipping VALUE="2" <? if($row[shipping] == 2) { echo "checked"; } ?><?= $Disabled ?>>Seller pays shipping expenses&nbsp;</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140" nowrap>Payment Details:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="1" <? if(strstr($row[payment], "1")) { echo "checked"; } ?>>  Cheque</FONT><BR><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="2" <? if(strstr($row[payment], "2")) { echo "checked"; } ?>>  Money Order</FONT><BR><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="3" <? if(strstr($row[payment], "3")) { echo "checked"; } ?>>  MasterCard or Visa</FONT><BR><INPUT TYPE=CHECKBOX NAME="payment[]" VALUE="4"<? if(strstr($row[payment], "4")) { echo "checked"; } ?>>  Wire Transfer</FONT></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Email Notified:</td>
        <td bgcolor="#FFFFFF"><?= $row[notified] ?></td>
      </tr>
      <tr>
       <TD class="Heading2" WIDTH="140" ALIGN="right"><b>Add Picture</b></TD>
       <TD WIDTH="486" bgcolor="#FFFFFF"><INPUT TYPE=file SIZE=20 NAME=AddPicture></TD>
      </tr>
      <tr>
       <td class="Heading2" WIDTH="140" ALIGN="right"><b>Images:</b></td>
       <td bgcolor="#FFFFFF">
             <?

             $Pictures = getDirList("/home/etxint/public_html/members/auctionimages");

              foreach($Pictures as $key => $value) {

          	   $Test = substr(strrev(strrchr(strrev($value), ".")), 0, -1);

          	   ?><!-- Test Number: <?= $Test ?> --><?

               if($Test == $row[id]) {

                ?><img border="0" alt="Delete Image" src="http://www.ebanctrade.com/members/auctionimages/<?= $value ?>"><a href="general.php?DeletePicture=true&auction=<?= $row[id] ?>&Image=<?= $value ?>&tab=<?= $_GET[tab] ?>">&nbsp;&nbsp;DELETE</a>&nbsp;<?

               }

              }

        ?></td>
         </tr>
         <tr>
        <td class="Heading2" align="right" width="140">&nbsp;</td>
        <td bgcolor="#FFFFFF" align="right"><input type="Submit" value="Update Auction" name="Submit">&nbsp;<input type="Reset" name="Reset" value="Reset"</td>
      </tr>
     </table>
    </td>
  </tr>
</table>
<br>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td class="Heading" align="left" colspan="5">AUCTION BIDS</td>
      </tr>
      <tr>
        <td class="Heading2" align="left">ID</td>
        <td class="Heading2" align="left">BIDDER</td>
        <td class="Heading2" align="left">DATE</td>
        <td class="Heading2" align="left">TIME</td>
        <td class="Heading2" align="left">AMOUNT</td>
      </tr>
      <?

       $query3 = dbRead("select id, bid, UNIX_TIMESTAMP(bidwhen) as bidwhen, bidder, quantity, members.companyname as companyname from tbl_auction_bids, members where (members.memid = tbl_auction_bids.bidder) and tbl_auction_bids.auction = '$auctionid' order by bidwhen desc");

	   if(mysql_num_rows($query3) == 0) {

	    ?>
	    <tr>
	     <td bgcolor="#FFFFFF" colspan="5" align="center"><br>No Bids<br><br></td>
	    </tr>
	    <?

	   } else {

	    while($row3 = mysql_fetch_assoc($query3)) {

         $biddate = date("j M Y", $row3[bidwhen]);
         $bidtime = date("H:i", $row3[bidwhen]);

	     ?>
	     <tr>
	      <td bgcolor="#FFFFFF"><?= $row3[id]?></td>
	      <td bgcolor="#FFFFFF"><?= $row3[companyname] ?></td>
	      <td bgcolor="#FFFFFF"><?= $biddate ?></td>
	      <td bgcolor="#FFFFFF"><?= $bidtime ?></td>
	      <td bgcolor="#FFFFFF">$<?= number_format($row3[bid],2); ?></td>
	     </tr>
	     <?

	    }

	   }

      ?>
    </table>
    </td>
  </tr>
</table>

<?

}

?>
