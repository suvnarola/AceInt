<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form ENCTYPE="multipart/form-data" method="POST" action="main.php?page=auctions&auction=<?= $_GET[auction] ?>&tab=<?= $_GET[tab] ?>">

<?

// Some Setup.

 $tabarray = array('View Active Auctions','View Closed Auctions','Pending Auctions');

 $timestampnow = date("YmdHis");

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "View Active Auctions") {

 if($_POST[UpdateAuction]) {

  update_auction();
  display_auctions(1);

 } else {

  if($_GET[ViewAuction]) {

   display_item($_GET[auction]);

  } elseif($_GET[DelAuction]) {

   del_auction($_GET[auction]);
   edit_auction($_GET[auction]);

  } elseif($_GET[EditAuction]) {

   edit_auction($_GET[auction]);

  } else {

   display_auctions(1);

  }

 }

} elseif($_GET[tab] == "View Closed Auctions") {

 if($_POST[UpdateAuction]) {

  update_auction();
  edit_auction($_GET[auction]);

 } else {

  if($_GET[ViewAuction]) {

   display_item($_GET[auction]);

  } elseif($_GET[DelAuction]) {

   del_auction($_GET[auction]);
   display_auctions(2);

  } elseif($_GET[EditAuction]) {

   edit_auction($_GET[auction]);

  } else {

   display_auctions(2);

  }

 }

} elseif($_GET[tab] == "Pending Auctions") {

 display_auctions(3);

}

?>

</body>

</html>

<?

function update_auction() {

 $query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(starts) as unixstarts from tbl_auction_auctions where id = '$_REQUEST[auction]'");
 $row = mysql_fetch_assoc($query);

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

 foreach($_REQUEST as $i => $value) {
  $TRANSFER_VARS[$i]=addslashes($value);
 }

 if($row[duration] != $TRANSFER_VARS[duration]) {

  $extratime_tmp = (60*60*24)*$TRANSFER_VARS[duration];
  $extratime = $row[unixstarts]+$extratime_tmp;
  $new_ends = date("YmdHis", $extratime);

  dbWrite("update tbl_auction_auctions set ends = '$new_ends' where id='$TRANSFER_VARS[auction]'");

  if($new_ends > $row[starts]) {

   // set notified to N
   dbWrite("update tbl_auction_auctions set notified = 'N' where id='$TRANSFER_VARS[auction]'");

  }

 }

 if($row[minimum_bid] != $TRANSFER_VARS[minimum_bid]) {

  // See if there has been any bids.
  $query2 = dbRead("select count(id) as Test from tbl_auction_bids where auction = '$TRANSFER_VARS[auction]'");
  $row2 = mysql_fetch_assoc($query2);

  if($row2[Test] < 1) {

   dbWrite("update tbl_auction_auctions set minimum_bid = '$TRANSFER_VARS[minimum_bid]', current_bid = '$TRANSFER_VARS[minimum_bid]' where id='$TRANSFER_VARS[auction]'");

  }

 }

 if($TRANSFER_VARS[Display] == "Y" && $row[Display] == "N") {

  // we need to start the auction again. because we are just displaying it.

  $starttime = date("YmdHis");
  $endtime = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d")+$TRANSFER_VARS[duration],date("Y")));

  dbWrite("update tbl_auction_auctions set ends = '$endtime', starts = '$starttime' where id='$TRANSFER_VARS[auction]'");
  dbWrite("update tbl_auction_auctions set notified = 'N' where id='$TRANSFER_VARS[auction]'");

 }

 dbWrite("update tbl_auction_auctions set Display = '$TRANSFER_VARS[Display]' where id='$TRANSFER_VARS[auction]'");
 dbWrite("update tbl_auction_auctions set duration = '$TRANSFER_VARS[duration]', market_value = '$TRANSFER_VARS[market_value]', GST = '$TRANSFER_VARS[GST]', auction_type = '$TRANSFER_VARS[auction_type]', title = '$TRANSFER_VARS[title]', description = '$TRANSFER_VARS[description]', category = '$TRANSFER_VARS[category]', trade_percent = '$TRANSFER_VARS[trade_percent]', reserve_price = '$TRANSFER_VARS[reserve_price]', increment = '$TRANSFER_VARS[customincrement]', location = '$TRANSFER_VARS[location]', location_zip = '$TRANSFER_VARS[location_zip]', shipping = '$TRANSFER_VARS[shipping]', payment = '$Database_Payment_Details'  where id='$TRANSFER_VARS[auction]'");

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
        <td colspan="7" class="Heading" align="center"><?= $Top_Title ?></td>
        </tr>
      <tr>
        <td nowrap class="Heading2" class="Heading2" width="35" nowrap>ID</td>
        <td class="Heading2" class="Heading2" width="100%">PRODUCT</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>TIME LEFT</td>
        <td class="Heading2" class="Heading2" align="right" nowrap>PRICE</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>EDIT</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>VIEW</td>
        <td class="Heading2" class="Heading2" align="left" nowrap>DEL</td>
      </tr>
      <?

       if($greatless == 3) {

        $query = dbRead("select tbl_auction_auctions_pending.*, UNIX_TIMESTAMP(tbl_auction_auctions_pending.ends) as unix_ends FROM tbl_auction_auctions_pending order by tbl_auction_auctions_pending.id DESC");

       } else {

        $query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(tbl_auction_auctions.ends) as unix_ends FROM tbl_auction_auctions where tbl_auction_auctions.ends $BA $timestampnow order by tbl_auction_auctions.ends DESC");

       }

       while($row = mysql_fetch_assoc($query)) {

       	if($greatless == 1) {
       	 $time_left = get_time_remain($row);
       	 $tab = "View Active Auctions";
       	} else {
       	 $time_left = date("jS M Y", $row[unix_ends]);
       	 $tab = "View Closed Auctions";
       	}

		if(!$row[title]) {

		 $Title = "No Product Name";

		} else {

		 $Title = $row[title];

		}

      ?>
      <tr bgcolor="#FFFFFF">
        <td><?= $row[id] ?></td>
        <td><? if($row[Display] == "Y") { echo "<b>"; } ?><?= $Title ?> [<?= $row[Display] ?>]<? if($row[Display] == "Y") { echo "</b>"; } ?></td>
        <td align="right" nowrap><?= $time_left ?></td>
        <td align="right">$<?= number_format($row[current_bid],2) ?></td>
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

function del_auction($auctionid) {

 dbWrite("delete from tbl_auction_auctions where id = '$auctionid'");

}

function edit_auction($auctionid) {

$query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(starts) as starts, UNIX_TIMESTAMP(ends) as ends from tbl_auction_auctions where id = '$auctionid'");
$row = mysql_fetch_assoc($query);

$Dis_Starts = date("g:ia, D jS M Y", $row[starts]);
$Dis_Ends = date("g:ia, D jS M Y", $row[ends]);
$Dis_Days_tmp = mktime()-$row[starts];
$Dis_Days = get_days($Dis_Days_tmp);

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
        <td class="Heading2" align="right" width="140">Starts:</td>
        <td bgcolor="#FFFFFF"><?= $Dis_Starts ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Ends:</td>
        <td bgcolor="#FFFFFF"><?= $Dis_Ends ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Duration Days:</td>
        <td bgcolor="#FFFFFF"><input type="text" size="10" name="duration" value="<?= $row[duration] ?>">&nbsp;<?= $Dis_Days ?> From Start of Auction.</td>
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
                </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Reserve Price:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="reserve_price" SIZE=10 VALUE="<?= $row[reserve_price] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">GST:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=radio NAME=GST VALUE="Y" <? if($row[GST] == "Y") { echo "checked"; } ?>>GST Included<BR><INPUT TYPE=radio NAME=GST VALUE="N" <? if($row[shipping] == "N") { echo "checked"; } ?>>GST to be paid in Cash&nbsp;</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Market Value:</td>
        <td bgcolor="#FFFFFF"><INPUT TYPE=text NAME="market_value" SIZE=10 VALUE="<?= $row[market_value] ?>" onKeyPress="return number(event)"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Auction Type:</td>
        <td bgcolor="#FFFFFF"><SELECT NAME="auction_type">
	           <OPTION VALUE="1" <? if($row[auction_type] == 1) { echo "selected"; } ?>>Standard auction</OPTION>
	           <OPTION VALUE="2" <? if($row[auction_type] == 2) { echo "selected"; } ?>>Dutch auction</OPTION>
              </SELECT></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Bid Increment:</td>
        <td bgcolor="#FFFFFF"> <INPUT TYPE=text NAME="customincrement" SIZE=10 VALUE="<?= $row[increment] ?>" onKeyPress="return number(event)"></td>
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
        <td class="Heading2" align="right" width="140">Display:</td>
        <td bgcolor="#FFFFFF"><select name="Display">
               <option value="Y"<? if($row[Display] == "Y") { echo " selected"; } ?>>Yes</option>
               <option value="N"<? if($row[Display] == "N") { echo " selected"; } ?>>No</option>
              </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="140">Email Notified:</td>
        <td bgcolor="#FFFFFF"><select name="notified">
               <option value="Y"<? if($row[notified] == "Y") { echo " selected"; } ?>>Yes</option>
               <option value="N"<? if($row[notified] == "N") { echo " selected"; } ?>>No</option>
              </select></td>
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

                ?><a href="general.php?DeletePicture=true&auction=<?= $row[id] ?>&Image=<?= $value ?>&tab=<?= $_GET[tab] ?>"><img border="0" alt="Delete Image" src="http://www.ebanctrade.com/members/auctionimages/<?= $value ?>"></a>&nbsp;<?

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
