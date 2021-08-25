<?

 /**
  * Auction Search.
  */

 $TypeArray = array(
 		"id" => "Auction ID",
 		"user" => "MemberShip Number",
 		"title" => "Title",
 		"description" => "Description",
 		"location_zip" => "Post Code");


if($_REQUEST[auctionsearch]) {

$time_start = getmicrotime();

if($_REQUEST[OrderBy]) {

 $OrderBy = "ORDER BY ". addslashes($_REQUEST[OrderBy]) ." DESC";
 
} else {

 $OrderBy = "ORDER BY title DESC";
 
}

if($_REQUEST[Category]) {

 $Category = "AND (tbl_auction_auctions.category IN (". addslashes($_REQUEST[Category]) ."))";

}

$GetData = dbRead("SELECT tbl_auction_auctions.*, members.companyname, members.phonearea, members.phoneno, members.emailaddress from tbl_auction_auctions, members where (tbl_auction_auctions.user = members.memid) and $_REQUEST[Type] like '%". addslashes($_REQUEST[Data]) ."%' $Category $OrderBy");

$counter = mysql_num_rows($GetData);

$query3 = dbRead("select memid from notes group by memid ASC");
while($row3 = mysql_fetch_assoc($query3)) {
  $notesarray[$row3[memid]] = 1;
}

function decide_class($Type) {
 
 if(!$_REQUEST[OrderBy]) {
  if($Type == "title") {
   return "class=\"Heading4\"";
  } else {
   return "class=\"Heading\"";
  }
 } else {
  if(strtolower($Type) == strtolower($_REQUEST[OrderBy])) {
   return "class=\"Heading4\"";
  } else {
   return "class=\"Heading\"";
  }
 }
 
}

function decide_link($AuctionRow) {

 $timestampnow = date("YmdHis");

 if($AuctionRow[ends] > $timestampnow) {
 
  /**
   * Active Auction
   */
  
  return "<a href=\"main.php?page=auctions&auction=".$AuctionRow['id']."&tab=View Active Auctions&EditAuction=True\">";
 
 } else {
 
  /**
   * Closed Auction
   */
  
  return "<a href=\"main.php?page=auctions&auction=".$AuctionRow['id']."&tab=View Closed Auctions&EditAuction=True\">";
 
 }

}

?>
<html>
<head>
<script>

function notes(URL) {
var exitwin ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=400";
selectedURL = URL;                
remotecontrol=window.open(selectedURL, "exit_console", exitwin);
remotecontrol.focus();
}

</script>
</head>
<body<? if(!$_REQUEST[auctionsearch]) { echo ' onload="javascript:putFocus(0,2);"'; } ?>>

<form method="post" action="main.php?page=auction_search" name="frm">
<input type="hidden" name="auctionsearch" value="1">
<table width="620" cellspacing="0" cellpadding="1" border="0">
<tr>
<td class="Border">
  <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse" bordercolor="#111111">
    <tr>
     <td colspan="5" align="center" class="Heading2">Auction Search</td>
    </tr>
  <tr> 
      <td align="right" class="Heading2" width="100"><b>Data:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="Data" id=data0 value="<?= $_REQUEST[Data] ?>" size="35" tabindex="1"></td>
      <td align="right" class="Heading2" colspan="2"><b>Type:</b></td>
      <td bgcolor="#FFFFFF"> 
		<?
		
		form_select('Type',$TypeArray,'','',$_REQUEST[Type]);
		
		?>
      </td>
  </tr>
  <tr> 
      <td width="100" height="30" align="right" class="Heading2"><b>Category:</b></td>
      <td height="30" bgcolor="#FFFFFF"><?
      
	   $dbgetcat = dbRead("select tbl_auction_categories.* from tbl_auction_categories order by tbl_auction_categories.cat_name ASC");
	   form_select('Category',$dbgetcat,'cat_name','cat_id',$_REQUEST[catid],'All Categories');
	       
      ?></td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" colspan="2" bgcolor="#FFFFFF" align="right">&nbsp;</td>
  </tr>
  <tr> 
      <td width="100" height="30" class="Heading2"></td>
      <td height="30" colspan="4" bgcolor="#FFFFFF"><input type="Submit" value="Search">&nbsp;&nbsp;&nbsp;<? if($Admin[pcheque] == "Y") { ?><? } ?></td>
  </tr>
</table>
</td>
</tr>
</table>

<br>


<table width="610" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td align="center" bgcolor="#FFFFFF"><a href="javascript:print()" class="nav">Print</a><br>Number of Results: <b><?= $counter ?></b></td>
	</tr>
</table>
<table width="610" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="610" border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="8" align="center" class="Heading">Auction Search Details.</td>
	</tr>
	<tr>
		<td width="40" <? print decide_class("id"); ?>><b><a class="ordernav" href="main.php?page=auction_search&auctionsearch=true&Data=<?= $_REQUEST[Data] ?>&Type=<?= $_REQUEST[Type] ?>&OrderBy=id">ID:</a></b></td>
		<td width="160" <? print decide_class("user"); ?>><b>USER:</b></td>
		<td width="165" <? print decide_class("title"); ?>><b><a class="ordernav" href="main.php?page=auction_search&auctionsearch=true&Data=<?= $_REQUEST[Data] ?>&Type=<?= $_REQUEST[Type] ?>&OrderBy=title">TITLE:</b></td>
		<td width="92" <? print decide_class("null"); ?>><b>PHONE:</b></td>
		<td width="65" <? print decide_class("trade_percent"); ?> align="right"><b>TRADE %:</b></td>
		<td width="47" <? print decide_class("reserve_price"); ?> align="right">RESERVE</td>
		<td width="16" <? print decide_class("null"); ?>>&nbsp;</td>
		<td width="16" <? print decide_class("null"); ?>>&nbsp;</td>
	</tr>



<?

$foo=0;

while($row = mysql_fetch_assoc($GetData)) {
 
 $count++;
 
 $bgcolor = "#CCCCCC";
 $foo % 2  ? 0: $bgcolor = "#EEEEEE";

?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40" nowrap><a href="main.php?page=logs&auction=<?= $row[id] ?>" class="nav"><font color="<?= $frcolor ?>"><?= $row[id] ?></font></a></td>
			<td width="160" nowrap><font color="<?= $frcolor ?>"><? if($row[emailaddress]) { ?><a class="nav" href="mailto:<?= $row[emailaddress] ?>"><? } ?><?= $row[companyname] ?><? if($row[emailaddress]) { ?></a><? } ?></font></td>
			<td width="165" nowrap><font color="<?= $frcolor ?>"><?= $row[title] ?></font></td>
			<td width="92" nowrap><font color="<?= $frcolor ?>"><?= $row[phonearea] ?> <?= $row[phoneno] ?></font></td>
			<td width="65" nowrap align="right"><font color="<?= $frcolor ?>"><?= $row[trade_percent] ?>%</font></td>
			<td width="47" align="right" nowrap>$<?= $row[reserve_price] ?></td>
			<td width="16" align="right" nowrap><? print decide_link($row); ?><img src="/images/icon_e.gif" border="0" width="16" height="15"></a></td>
			<td width="16" align="right" nowrap><a href="javascript:notes('/body.php?page=notes&memid=<?= $row[user] ?>')"><img src="/images/icon_n<? if($notesarray[$row[user]] == 1) { echo "2"; } ?>.gif" border="0" width="16" height="15"></a></td>
		</tr>
<?

$foo++;
}

?>
		<tr>
		    <td colspan="8" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
		    $time_end=getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
		</tr>

</table>
</td>
</tr>
</table>

</body>

</html>

<?

} else {

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</head>

<body onload="javascript:putFocus(0,2);">
<form method="post" action="main.php?page=auction_search" name="frm">
<input type="hidden" name="countryid" value="<?= $GET_CID?>">
<input type="hidden" name="auctionsearch" value="1">
<table width="620" cellspacing="0" cellpadding="1" border="0">
<tr>
<td class="Border">
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr>
     <td colspan="4" align="center" class="Heading2">Auction Search</td>
    </tr>
  <tr> 
      <td align="right" width="100" class="Heading2"><b>Data:</b></td>
      <td bgcolor="#FFFFFF">
      <input type="text" name="Data" id=data size="35" tabindex="1"></td>
      <td align="right" class="Heading2"><b>Type:</b></td>
      <td bgcolor="#FFFFFF"> 
		<?
		
		form_select('Type',$TypeArray,'','','Title');
		
		?>
      </td>
  </tr>
  <tr> 
      <td width="100" height="30" align="right" class="Heading2"><b>Category:</b></td>
      <td height="30" bgcolor="#FFFFFF"><?
      
	   $dbgetcat = dbRead("select tbl_auction_categories.* from tbl_auction_categories order by tbl_auction_categories.cat_name ASC");
	   form_select('Category',$dbgetcat,'cat_name','cat_id',$_REQUEST[catid],'All Categories');
	       
      ?></td>
      <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="30" bgcolor="#FFFFFF" align="right">&nbsp;</td>
  </tr>
  <tr> 
      <td width="100" height="30" class="Heading2"></td>
      <td width="450" height="30" colspan="3" bgcolor="#FFFFFF"><input type="Submit" value="Search">&nbsp;&nbsp;&nbsp;<? if($Admin[pcheque] == "Y") { ?><? } ?></td>
  </tr>

</table>
</td>
</tr>
</table>
</form>
</body>
</html>
<?

}

?>