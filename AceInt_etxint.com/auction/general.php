<?

include("includes/global.php");

if($_REQUEST[DeletePicture]) {

 unlink("/home/etxint/public_html/members/auctionimages/".$_REQUEST[Image]);
 header("Location: main.php?page=auctions&auction=$_GET[auction]&tab=$_GET[tab]&EditAuction=True");

}
