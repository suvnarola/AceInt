<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2008
 */

?>

<form method="POST" enctype="multipart/form-data" action="body.php?page=cat_add&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>&Client=<? $_REQUEST['Client'] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();

$tabarray = array("Add Product","Edit Products");

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  add($_REQUEST['Client']);

} elseif($_GET[tab] == "tab2") {

  edit($_REQUEST['Client']);

}
?>

</form>

<?
function add($Client = false) {

global $Client;

if($_REQUEST['products_name']) {

	$dbgetmemcats = dbRead("select * from manufacturers where manufacturers.acc_no = '".$_REQUEST['memid']."'","empireShop");
	$row = mysql_fetch_assoc($dbgetmemcats);

	if($row['manufacturers_id']) {
		$mid = $row['manufacturers_id'];
	} else {
		$dbgetmem = dbRead("select companyname from members where memid = '".$_REQUEST['memid']."'","etradebanc");
		$memrow = mysql_fetch_assoc($dbgetmem);
		$manid = dbWrite("insert into manufacturers (manufacturers_name,date_added,acc_no) values ('" . $memrow['companyname'] . "','" . date("y-m-d") . "','" . $_REQUEST['memid'] . "')","empireShop","true");
		$mid = $manid;
	}

	$gg = dbWrite("insert into products (products_price,products_trade,products_tax_class_id,products_date_added,products_status,manufacturers_id,products_image) values ('" . $_REQUEST['products_price'] . "','" . $_REQUEST['products_trade'] . "','" . $_REQUEST['products_tax_class_id'] . "','" . date("y-m-d") . "','0','" . $mid . "','" . $_FILES['picture']['name'] . "')","empireShop","true");
	dbWrite("insert into products_description (products_id,language_id,products_name,products_description,products_url) values ('" . $gg . "','1','" . $_REQUEST['products_name'] . "','" . $_REQUEST['products_description'] . "','" . $_REQUEST['products_url'] . "')","empireShop");
	dbWrite("insert into products_to_categories (products_id,categories_id) values ('" . $gg . "','0')","empireShop");

	if($_FILES['picture']['tmp_name']) {

		$picture_name_new = $_FILES['picture']['name'];

		move_uploaded_file($_FILES['picture']['tmp_name'], "/home/etxint/public_html/memberSite/shop/catalog/images/".$picture_name_new);

		$source="/home/etxint/public_html/memberSite/shop/catalog/images/".$picture_name_new;
		$dest="/home/etxint/public_html/memberSite/shop/catalog/images/thumb-".$picture_name_new;
		copy($source, $dest);
		exec('convert -geometry 75 /home/etxint/public_html/memberSite/shop/catalog/images/thumb-' . $picture_name_new . ' /home/etxint/public_html/memberSite/shop/catalog/images/thumb-' . $picture_name_new . '');

	}

	$subject = "Catelogue Item Added";
	$text = "Member has updated details. [".$_SESSION['Member']['memid']."]<br><br>". $ggg ."<br><br>Members Section.";
	$text = get_html_template($_SESSION['Member']['CID'],'Accounts',$text);

	unset($attachArray);
	unset($addressArray);

	$addressArray[] = array(trim("store@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION[Country][logo], 2)), getWho($_SESSION[Country][logo], 1). "Accounts");

	sendEmail("store@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1), 'store@' . $_SESSION[Country][countrycode] . '.'. getWho($_SESSION[Country][logo], 2), 'Catelogue Item Added ', 'store@' . $_SESSION[Country][countrycode] . '.'.getWho($_SESSION[Country][logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray);
?>

<body>
<table cellpadding="1" border="0" cellspacing="0" width="620">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center" class="Heading"><b>Product Add</b></td>
    </tr>
   </table>
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <input type="hidden" value="<?= $_REQUEST['Client'] ?>" name="Client">
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1"></td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"></td>
	 <td width="40%" rowspan="12" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;
		<?

		if($_FILES['picture']) {
		 if(file_exists("/home/etxint/public_html/memberSite/shop/catalog/images/".$picture_name_new)) {
		  print'<img src="/memberSite/shop/catalog/images/'.$picture_name_new.'" border="0"><br>';
		 }
		}

		?>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">Mem ID:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $Client ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">Product Name:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $_REQUEST['products_name'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">Products Description:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $_REQUEST['products_description'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">Products URL:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $_REQUEST['products_url'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">Products Price:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency'] ?><?= number_format($_REQUEST['products_price'], 2) ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">Trade %:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"> <?= $_REQUEST['products_trade'] ?></td>
	</tr>
	<tr>
	 <td width="20%" align="right" class="Heading2" height="1">GST:</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><?= $_REQUEST['products_tax_class_id'] ?></td>
	</tr>
    <tr>
	 <td width="20%" align="right" valign="top" class="Heading2" height="<?= $cellh ?>">&nbsp;</td>
	 <td width="40%" align="left" bgcolor="#FFFFFF"><input type="submit" value="Add Another Product" name="add"></td>
	</tr>
   </table>
  </td>
 </tr>
</table>

<?
} else {

product($Client = false);

}
}

function edit($Client = false) {

 global $row;

 if($_REQUEST['deleteprod']) {
	dbWrite("delete from products where products_id='".$_REQUEST['pID']."'","empireShop");
	dbWrite("delete from products_description where products_id='".$_REQUEST['pID']."'","empireShop");
	dbWrite("delete from products_to_categories where products_id='".$_REQUEST['pID']."'","empireShop");
 }

 if($_REQUEST['editprod']) {
	dbWrite("update products set products_price='".addslashes(encode_text2($_REQUEST['products_price']))."', products_trade ='".addslashes(encode_text2($_REQUEST['products_trade']))."', products_tax_class_id='".$_REQUEST['products_tax_class_id']."' where products_id = '".$_REQUEST['products_id']."'","empireShop");
	dbWrite("update products_description set products_name='".addslashes(encode_text2($_REQUEST['products_name']))."', products_description  ='".addslashes(encode_text2($_REQUEST['products_description']))."', products_url = '".$_REQUEST['products_url']."' where products_id = '".$_REQUEST['products_id']."'","empireShop");
 }

 if(!$_REQUEST['deleteprod'] && $_REQUEST['pID']) {

	$dbgetmemcats = dbRead("select products_description.*, products.* from products_description, products, manufacturers where products_description.products_id = products.products_id and products.manufacturers_id = manufacturers.manufacturers_id and products.products_id = '".$_REQUEST['pID']."'","empireShop");
	$row = mysql_fetch_assoc($dbgetmemcats);
 	product();
 	die;
 }
?>

<table border="0" cellspacing="1" cellpadding="0" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="1">
 <tr>
  <td align="left" width="10%" class="Heading2"><b>ID:</b></td>
  <td align="left" width="45%" class="Heading2"><b><?= get_word("119") ?>:</b></td>
  <td align="right" width="15%" class="Heading2"><b>Times View:</b></td>
  <td align="right" width="15%" class="Heading2"><b>Trade %:</b></td>
  <td align="right" width="15%" class="Heading2"><b>Price:</b></td>
  <td align="right" width="10%" class="Heading2">&nbsp;</td>
 </tr>
<?

$dbgetmemcats = dbRead("select products_description.*, products.products_price, products.products_trade from products_description, products, manufacturers where products_description.products_id = products.products_id and products.manufacturers_id = manufacturers.manufacturers_id and manufacturers.acc_no = '".$_REQUEST['Client']."'","empireShop");
while($row = mysql_fetch_assoc($dbgetmemcats)) {

	if($row['products_status'] == 0) {

		?>
		 <tr>
		  <td align="left" width="10%" bgcolor="#FFFFFF"><a class="nav" href="<?= $PHP_SELF ?>?page=cat_add&tab=tab2&pID=<?= $row['products_id'] ?>&Client=<?= $_REQUEST['Client'] ?>"><?= $row['products_id'] ?></a></td>
		  <td align="left" width="45%" bgcolor="#FFFFFF"><a class="nav" href="<?= $PHP_SELF ?>?page=cat_add&tab=tab2&pID=<?= $row['products_id'] ?>&Client=<?= $_REQUEST['Client'] ?>"><?= $row['products_name'] ?></a></td>
		  <td align="right" width="15%" bgcolor="#FFFFFF"><?= $row['products_viewed'] ?></td>
		  <td align="right" width="15%" bgcolor="#FFFFFF"><?= number_format($row['products_trade'], 0) ?></td>
		  <td align="right" width="15%" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency'] ?><?= number_format($row['products_price'], 2) ?></td>
		  <td align="right" width="10%" bgcolor="#FFFFFF"><a class="nav" href="body.php?deleteprod=1&page=cat_add&tab=tab2&pID=<?= $row['products_id'] ?>&Client=<?= $_REQUEST['Client'] ?>">DELETE</a></td>
		  </form>
		 </tr>
		<?

	} else {

		?>
		 <tr>
		  <td align="left" width="10%" bgcolor="#FFFFFF"><?= $row['products_id'] ?></td>
		  <td align="left" width="45%" bgcolor="#FFFFFF"><?= $row['products_name'] ?></td>
		  <td align="right" width="15%" bgcolor="#FFFFFF"><?= $row['products_viewed'] ?></td>
		  <td align="right" width="15%" bgcolor="#FFFFFF"><?= number_format($row['products_trade'], 0) ?></td>
		  <td align="right" width="15%" bgcolor="#FFFFFF"><?= $_SESSION['Country']['currency'] ?><?= number_format($row['products_price'], 2) ?></td>
		  <td align="right" width="10%" bgcolor="#FFFFFF"><a class="nav" href="body.php?deleteprod=1&page=cat_add&tab=tab2&pID=<?= $row['products_id'] ?>&Client=<?= $_REQUEST['Client'] ?>">DELETE</a></td>
		  </form>
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

function product($Client = false) {

global $row;

if($_REQUEST['pID']) {
	$tt = "Update Catalogue Listing";
} else {
	$tt = "Add Catalogue Listing";
}

if($row['memid']) {
 $mm = $row['memid'];
} else {
 $mm = $_REQUEST['Client'];
}
?>

    <form name="new_product" action="body.php?page=cat_add&tab=tab1" method="post" enctype="multipart/form-data">
    <table border="0" width="620" cellspacing="0" cellpadding="2">
    <input type="hidden" value="<?= $row['products_id'] ?>" name="products_id">
    <input type="hidden" value="<?= $_REQUEST['Client'] ?>" name="Client">
    <input type="hidden" value="1" name="editprod">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" pageHeading"><font color="#5A2E23" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b><br><?= $tt ?></b></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="1">
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
          <tr>
            <td class="main">Mem ID:</td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="10">&nbsp;<input type="text" value="<?= $mm ?>" name="memid" size="40"></td>
          </tr>
          <tr>
            <td class="main">Product Name:</td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="24" height="10">&nbsp;<input type="text" value="<?= $row['products_name'] ?>" name="products_name" size="40"></td>
          </tr>
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
          <tr bgcolor="#ECE6CC">
            <td class="main">GST:</td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="24" height="15">&nbsp;<select value="<?= $row['products_tax_class_id'] ?>" name="products_tax_class_id"><option value="0">--none--</option><option <?if($row['products_tax_class_id'] == 1) {?>selected<?}?> value="1">Taxable Goods</option></select></td>
          </tr>
          <tr bgcolor="#ECE6CC">
            <td class="main">Product Price (Nett):</td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="24" height="15">&nbsp;<input type="text" value="<?= $row['products_price'] ?>" name="products_price"></td>
          </tr>
          <tr bgcolor="#ECE6CC">
            <td class="main">Trade %:</td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="24" height="15">&nbsp;<input value="<?= $row['products_trade'] ?>" type="text" name="products_trade"></td>
          </tr>
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
          <tr>
            <td class="main" valign="top">Product Description:</td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top">&nbsp;</td>
                <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="24" height="10"><textarea name="products_description" wrap="soft" cols="60" rows="10"><?= $row['products_description'] ?></textarea></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
          <?if(!$_REQUEST['pID']) {?>
          <tr>
            <td class="main">Product Image:</td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="24" height="15">&nbsp;<input size="25" type="file" name="picture" style="font-family: Verdana"> (max 200kb)</td>
          </tr>
          <?}?>
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
          <tr>
            <td class="main">Product URL:<br><small>(without http://)</small></td>
            <td class="main"><img src="images/pixel_trans.gif" border="0" alt="" width="26" height="10"><input size="26" value="<?= $row['products_url'] ?>" type="text" name="products_url"></td>
          </tr>
          <tr>
            <td colspan="2"><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
      </tr>
      <tr>
		<td class="main" align="right"><input type="submit" value="Submit" style="font-family: Verdana; "></td>
      </tr>
    </table></form>

<?
}
?>