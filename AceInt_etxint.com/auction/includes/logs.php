<?

// Auction Logs.

if($_REQUEST[auction]) {

$auctionquery = dbRead("select tbl_auction_auctions.*, members.companyname from tbl_auction_auctions, members where (tbl_auction_auctions.user = members.memid) and id = '$_REQUEST[auction]'");
$auctionrow = mysql_fetch_assoc($auctionquery);

?>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
 <tr>
  <td width="100%" class="Border">
   <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
    <tr>
     <td colspan="4" class="Heading" align="center">AUCTION LOG - <?= $_REQUEST[auction] ?> - <?= $auctionrow[companyname] ?></td>
    </tr>
    <tr>
     <td nowrap class="Heading2">DATE</td>
     <td class="Heading2" nowrap>TYPE</td>
     <td class="Heading2" nowrap width="100%">COMPANYNAME</td>
     <td class="Heading2" nowrap>EMAILADDRESS</td>
    </tr>
    <?

     $query = dbread("select tbl_auction_log_type.Type, members.companyname, tbl_auction_log.DateTime, tbl_auction_log.EmailAddress, tbl_auction_log.memid from tbl_auction_log, tbl_auction_log_type, members where (tbl_auction_log.memid = members.memid) and (tbl_auction_log.Type = tbl_auction_log_type.FieldID) and tbl_auction_log.AuctionID like '$_REQUEST[auction]' order by tbl_auction_log.DateTime");

     if(mysql_num_rows($query) > 0) {

      while($row = mysql_fetch_assoc($query)) {

       $DisplayDate = date("jS M y H:i:s", strtotime($row[DateTime]));

    ?>
    <tr>
     <td nowrap bgcolor="#FFFFFF"><?= $DisplayDate ?></td>
     <td bgcolor="#FFFFFF" nowrap><?= $row[Type] ?></td>
     <td bgcolor="#FFFFFF" nowrap width="100%"><?if($row[Type] == "Bid.") {?><a href="https://admin.etxint.com/body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[companyname] ?></a><?} else {?><?= $row[companyname] ?><?}?></td>
     <td bgcolor="#FFFFFF" nowrap><?= $row[EmailAddress] ?></td>
    </tr>
	<?

	  }

	} else {

    ?>
    <tr>
     <td nowrap bgcolor="#FFFFFF" colspan="4" align="center"><br>No Auction Log for this auction.<br><br></td>
    </tr>
	<?

	}

	?>
   </table>
  </td>
 </tr>
</table>
<?

} else {

?>
<form method="POST" action="main.php?page=logs">

<table border="0" cellpadding="1" cellspacing="1" width="639">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td colspan="2" align="center" class="Heading">VIEW AUCTION LOGS</td>
	</tr>
		<td class="Heading2" width="40" align="right"><b>Auction&nbsp;ID:</b></td>
		<td bgcolor="#FFFFFF" align="left"><input type="text" size="10" name="auction"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="View Auction Log" name="viewlogs"></td>
	</tr>
</table>
</td>
</tr>
</table>

</form>
<?

}

?>