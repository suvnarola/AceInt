<?
 include("/home/etxint/admin.etxint.com/includes/global.php");

//$sdate=date('c',strtotime(date('Y')."W".date('W')."0"));
//$sdate=date('c',strtotime(date('Y')."W".date('W')."0"));
//$sdate=date('Y-m-d',strtotime(date('Y')."W".date('W')."0"));
//echo $sdate;



if ($handle = opendir('/home/etxint/admin.etxint.com/uploads/Image/Networking/2011/August/NV_Phones/23_8/')) {
    echo "Directory handle: $handle\n";
    echo "Files:\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {

        //echo "$file";

    }

    closedir($handle);
}




		//if($_REQUEST['templateAuction']) {

			$act = "<table cellspacing=\"1\" cellpadding=\"1\" width=\"680\" align=\"center\" summary=\"\" border=\"1\"><tbody>";
			$timestamp_now = date("YmdHis");
			$timestamp_now2 = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d")+7,date("Y")));
			//$sql_query = dbRead("select tbl_auction_auctions.*, UNIX_TIMESTAMP(tbl_auction_auctions.ends) as unix_ends, count(tbl_auction_bids.id) as Auction_Count from tbl_auction_auctions left outer join tbl_auction_bids on tbl_auction_auctions.id = tbl_auction_bids.auction where ends > '$timestamp_now' and ends < '$timestamp_now2' and Display = 'Y' group by tbl_auction_auctions.id ORDER BY tbl_auction_auctions.ends ASC","etradebanc");
			//while($Arow = mysql_fetch_assoc($sql_query)) {

			  //if($Arow[photo_uploaded] == 'Y') {

if ($handle = opendir('/home/etxint/admin.etxint.com/uploads/Image/Networking/2011/August/NV_Phones/23_8/')) {
    //echo "Directory handle: $handle\n";
    //echo "Files:\n";

$files1 = scandir('/home/etxint/admin.etxint.com/uploads/Image/Networking/2011/August/NV_Phones/23_8/');
//print_r($files1);
    /* This is the correct way to loop over the directory. */
    //while (false !== ($file = readdir($handle))) {
      foreach($files1 as $file) {

			$ff = "http://media.ebanctrade.com/uploads/Image/Networking/2011/August/NV_Phones/23_8/".$file;

 				  $maxwidth = "150";
				  $imagehw = GetImageSize("/home/etxint/admin.etxint.com/uploads/Image/Networking/2011/August/NV_Phones/23_8/".$file."");
				  $imagewidth = $imagehw[0];
				  $imageheight = $imagehw[1];
				  $imgorig = $imagewidth;

				  if ($imagewidth > $maxwidth) {
				    $imageprop=($maxwidth/$imagewidth);
				    $imagevsize= ($imageheight*$imageprop);
				    $imageheight=ceil($imagevsize);
				  } else {
				    $imageheight = $imageheight;
				  }

			  //}

			  $act .= "<tr>
			            <td><font face=\"Tahoma\" size=\"2\"><img height=\"".$imageheight."\" alt=\"\" width=\"150\" border=\"0\" src=\"$ff\" /></font></td>
			            <td><font face=\"Tahoma\" size=\"2\">".$ff." - ".number_format($Arow[trade_percent], 0)."% Trade</font></td>
			            <td>
			            <p align=\"center\"><font face=\"Tahoma\" size=\"2\"><a href=\"https://secure.etxint.com/members/index.php?AutoPage=37&amp;auctionid=".$Arow[id]."\"><img height=\"25\" alt=\"\" width=\"101\" src=\"http://media.ebanctrade.com/uploads/Image/Auction/Placeabid.JPG\" /></a></font></p>
			            </td>
			           </tr>";

    }

    closedir($handle);
}

			  $act .= "<tr>
			            <td><font face=\"Tahoma\" size=\"2\"><img height=\"".$imageheight."\" alt=\"\" width=\"150\" border=\"0\" src=\"https://secure.etxint.com/members/auctionimages/".$Arow[id].".1.jpg\" /></font></td>
			            <td><font face=\"Tahoma\" size=\"2\">".$Arow[title]." - ".number_format($Arow[trade_percent], 0)."% Trade</font></td>
			            <td>
			            <p align=\"center\"><font face=\"Tahoma\" size=\"2\"><a href=\"https://secure.etxint.com/members/index.php?AutoPage=37&amp;auctionid=".$Arow[id]."\"><img height=\"25\" alt=\"\" width=\"101\" src=\"http://media.ebanctrade.com/uploads/Image/Auction/Placeabid.JPG\" /></a></font></p>
			            </td>
			           </tr>";

			//}

			$act .= "</tbody></table>";
			$insertArray = array(
				'title' => $_REQUEST['sectionTitle'],
				'data' => $act,
				'contact' => $_REQUEST['sectionContact']
				);

			//dbWrite("insert into tbl_jobs_data (jobID,templateType,templateSection,orderBy,templateData) values ('" . $this->addID . "','Section','ITEMS OF INTEREST','1.01','" . addslashes(serialize($insertArray)) . "')", "etxint_email_system");

		//}





echo $act;








?>


 ?>