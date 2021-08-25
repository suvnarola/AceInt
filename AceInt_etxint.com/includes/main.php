<? check_access(); ?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META HTTP-EQUIV="Content-Language" CONTENT="en-us">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<script language="javascript" src="js/sleight.js"></script>
</HEAD>
<BODY BGCOLOR=#FFFFFF text="#000000" link="#0000CC" vlink="#5A2D27" alink="#FF6600" LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<? display_header(); ?>
<table width="100%" border="1" bordercolor="#5C2D23" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
 <tr>
  <td bgcolor="#FFFFFF" align="center"><FONT SIZE="1">Welcome to the new Empire Trade online Bid &#39;n&#39; Buy. Please read the Rules and Guidelines before entering items for sale, or bidding on items. By using this Bid &#39;n&#39; Buy; service, you are agreeing to the Rules and Guidelines and entering a binding contract.<br>
This site is in currently in the trial stage and as such is still being developed, so all feature may not yet be available. Your feedback is encouraged. If you require any further information or assistance with this Bid &#39;n&#39; Buy; service, please contact the Auction Administrator at Empire Trade head office on  on 07 5437 7220, or email <A HREF="mailto:auctions@au.empireXchange.com">auctions@au.empireXchange.com</A>.
</FONT><br></td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr valign="top">
    <td width="100%">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
        <TR>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_01.jpg" ALT=""></TD>
          <TD background="images/divid_02.jpg"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
          Categories</strong></font></TD>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_04.jpg" ALT=""></TD>
          <TD width="100%" background="images/divid_05.jpg"><IMG SRC="images/divid_05.jpg" WIDTH=10 HEIGHT=28 ALT=""></TD>
        </TR>
      </TABLE>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td width="6"></td>
          <td bgcolor="#CCCCCC"><? display_categories(); ?></td>
        </tr>
      </table>
    </td>
    <td><font size="2" face="Arial, Helvetica, sans-serif"><strong>
      </strong></font>
    </td>
  </tr>
</table>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
        <TR>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_01.jpg" ALT=""></TD>
          <TD background="images/divid_02.jpg"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
          Categories</strong></font></TD>
          <TD width="26" background="images/divid_05.jpg">
          <IMG SRC="images/divid_04.jpg" ALT=""></TD>
          <TD width="100%" background="images/divid_05.jpg"><IMG SRC="images/divid_05.jpg" WIDTH=10 HEIGHT=28 ALT=""></TD>
        </TR>
      </TABLE>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td width="6"></td>
          <td bgcolor="#CCCCCC"><? display_categories(); ?></td>
        </tr>
      </table>
    </td>
    <td><font size="2" face="Arial, Helvetica, sans-serif"><strong>
      </strong></font>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="100%">
   <TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
     <TR>
       <TD width="26" background="images/divid_05.jpg">
       <IMG SRC="images/divid_01.jpg" ALT=""></TD>
       <TD background="images/divid_02.jpg"><font size="2" face="Arial, Helvetica, sans-serif"><strong>
       FASTsearch</strong></font></TD>
       <TD width="26" background="images/divid_05.jpg">
       <IMG SRC="images/divid_04.jpg" ALT=""></TD>
       <TD width="100%" background="images/divid_05.jpg"><IMG SRC="images/divid_05.jpg" WIDTH=10 HEIGHT=28 ALT=""></TD>
    </TR>
   </TABLE>
   <table width="100%" border="0" cellpadding="1" cellspacing="0">
     <tr>
       <td width="5">&nbsp;</td>
       <td width="100%" bgcolor="#CCCCCC"><? display_search(); ?></td>
     </tr>
   </table>
    </td>
    <td><font size="2" face="Arial, Helvetica, sans-serif"><strong>
      </strong></font>
    </td>
  </tr>
</table>
<? display_hot_auctions('5'); ?>
<? display_featured_auctions('5'); ?>
<? display_closing_soon('5'); ?>
<p>&nbsp;</p>
</BODY>
</HTML>


<?
function display_categories() {

$timestamp_now = date("YmdHis");

$query = dbRead("SELECT tbl_auction_sub_categories.parent_id, tbl_auction_categories.cat_name as cat_name ,count(tbl_auction_auctions.id) as Auction_Count FROM tbl_auction_categories, {oj tbl_auction_sub_categories LEFT OUTER JOIN tbl_auction_auctions ON (tbl_auction_sub_categories.cat_id = tbl_auction_auctions.category and tbl_auction_auctions.ends > '$timestamp_now' and tbl_auction_auctions.Display = 'Y') } where (tbl_auction_categories.cat_id = tbl_auction_sub_categories.parent_id) Group BY tbl_auction_sub_categories.parent_id order by tbl_auction_categories.cat_name");
while($row = mysql_fetch_assoc($query)) {

 $data_structure[] = $row[cat_name];
 $cat_count[$row[cat_name]] = $row[Auction_Count];
 $data_structure_id[] = $row[parent_id];

}

$Category_Count = sizeof($data_structure);
$Counter = 0;
$Test = ceil($Category_Count/4);
$Plus2 += 1;
//echo $Test;

echo "<pre>";
//var_dump($data_structure);
echo "</pre>";

?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
 <tr valign="top" bgcolor="#EEEEEE">
  <?
  for($i = 1;$i < $Category_Count+1;$i++) {

   if(!$Plus) {

    $INum = ceil($i/4)-1;

    echo '<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?PageID=32&categoryid='.$data_structure_id[$INum].'">'.$data_structure[$INum].'&nbsp;<font color="#333333">('.number_format($cat_count[$data_structure[$INum]]).')</font></a><br></font></td>';

    $Plus += 1;

   } else {

    $NewI = (($Test * ($Plus - 1)) + $Plus2)-1;

    echo '<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?PageID=32&categoryid='.$data_structure_id[$NewI].'">'.$data_structure[$NewI].'&nbsp;<font color="#333333">('.number_format($cat_count[$data_structure[$NewI]]).')</font></a><br></font></td>';

   }

   $Plus += 1;

   if($i % 4 == 0) {

    $cfg_bgcolor_one = "#EEEEEE";
    $cfg_bgcolor_two = "#DDDDDD";

    $bgcolor = $cfg_bgcolor_one;

    $Counter % 2 ? 0: $bgcolor = $cfg_bgcolor_two;

    echo '</tr><tr valign="top" bgcolor="'.$bgcolor.'">';

    unset($Plus);
    $Plus2 += 1;

    $Counter++;
   }

  }

 ?>
</table>
<?

}

function display_cat_related() {

$timestamp_now = date("YmdHis");

$query = dbRead("SELECT tbl_auction_sub_categories.parent_id, tbl_auction_categories.cat_name as cat_name ,count(tbl_auction_auctions.id) as Auction_Count FROM tbl_auction_categories, {oj tbl_auction_sub_categories LEFT OUTER JOIN tbl_auction_auctions ON (tbl_auction_sub_categories.cat_id = tbl_auction_auctions.category and tbl_auction_auctions.ends > '$timestamp_now' and tbl_auction_auctions.Display = 'Y') } where (tbl_auction_categories.cat_id = tbl_auction_sub_categories.parent_id) Group BY tbl_auction_sub_categories.parent_id order by tbl_auction_categories.cat_name");
while($row = mysql_fetch_assoc($query)) {

 $data_structure[] = $row[cat_name];
 $cat_count[$row[cat_name]] = $row[Auction_Count];
 $data_structure_id[] = $row[parent_id];

}

$Category_Count = sizeof($data_structure);
$Counter = 0;
$Test = ceil($Category_Count/4);
$Plus2 += 1;
//echo $Test;

echo "<pre>";
//var_dump($data_structure);
echo "</pre>";

?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
 <tr valign="top" bgcolor="#EEEEEE">
  <?
  for($i = 1;$i < $Category_Count+1;$i++) {

   if(!$Plus) {

    $INum = ceil($i/4)-1;

    echo '<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?PageID=32&categoryid='.$data_structure_id[$INum].'">'.$data_structure[$INum].'&nbsp;<font color="#333333">('.number_format($cat_count[$data_structure[$INum]]).')</font></a><br></font></td>';

    $Plus += 1;

   } else {

    $NewI = (($Test * ($Plus - 1)) + $Plus2)-1;

    echo '<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a class="catnav" href="body.php?PageID=32&categoryid='.$data_structure_id[$NewI].'">'.$data_structure[$NewI].'&nbsp;<font color="#333333">('.number_format($cat_count[$data_structure[$NewI]]).')</font></a><br></font></td>';

   }

   $Plus += 1;

   if($i % 4 == 0) {

    $cfg_bgcolor_one = "#EEEEEE";
    $cfg_bgcolor_two = "#DDDDDD";

    $bgcolor = $cfg_bgcolor_one;

    $Counter % 2 ? 0: $bgcolor = $cfg_bgcolor_two;

    echo '</tr><tr valign="top" bgcolor="'.$bgcolor.'">';

    unset($Plus);
    $Plus2 += 1;

    $Counter++;
   }

  }

 ?>
</table>
<?

}

function display_search() {

?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE">
 <tr>
  <form method="POST" action="body.php?PageID=29">
  <td align="center">
  <input name="searchstring" type="text" size="20">
  <input type="submit" name="Submit" value="GO">
  <br><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Search our Auction Listings fast!</font>
  </td>
 </form>
 </tr>
</table>

<?

}
?>
