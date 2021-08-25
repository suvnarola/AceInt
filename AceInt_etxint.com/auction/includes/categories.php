<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form ENCTYPE="multipart/form-data" method="POST" action="main.php?page=categories&catid=<?= $_GET[catid] ?>&tab=<?= $_GET[tab] ?>">

<?

// Some Setup.

 $tabarray = array('Add Category','Edit Main Categories','Edit Sub Categories');
 
 $timestampnow = date("YmdHis");

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Add Category") {
 
 if($_REQUEST[CatAdd]) {
 
  $AddCat = add_category_to_database();
  if($AddCat) {
  
   add_category('1');
  
  } else {
  
   add_category();
  
  }
 
 } else {
 
  add_category();

 }

} elseif($_GET[tab] == "Edit Main Categories") {
 
 if($_REQUEST[CatEdit]) {
 
  if($_REQUEST[CatSave]) {
  
   save_main_category();
   edit_main_category();

  } else {
  
   edit_main_category2();
  
  }
 
 } else {
 
  edit_main_category();

 }

} elseif($_GET[tab] == "Edit Sub Categories") {

 if($_REQUEST[CatEdit]) {
 
  if($_REQUEST[CatSave]) {
  
   save_sub_category();
   edit_sub_category();

  } else {
  
   edit_sub_category2();
  
  }
 
 } else {
 
  edit_sub_category();

 }

}


?>

</body>

</html>

<?

function save_sub_category() {

 $TV = form_addslashes();

 dbWrite("update tbl_auction_sub_categories set cat_name = '$TV[SubCat]' where cat_id = '$_REQUEST[SubCatID]'");

}

function edit_sub_category2() {

 $query = dbRead("select * from tbl_auction_sub_categories where cat_id = '$_REQUEST[SubCat]'");
 $row = mysql_fetch_assoc($query);

?>
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">SUB CATEGORY EDIT</td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2">Sub Category:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="SubCat" value="<?= $row[cat_name] ?>"></td>
  </tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF">
    <button name="catadd" type="submit">
    Save Category.
    </button></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="SubCatID" value="<?= $row[cat_id] ?>">
<input type="hidden" name="CatSave" value="1">
<input type="hidden" name="CatEdit" value="1">
<?

}

function edit_sub_category() {

?>
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">SUB CATEGORY EDIT</td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2">Sub Category:</td>
    <td width="70%" bgcolor="#FFFFFF"><SELECT NAME="SubCat">
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
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF">
    <button name="catadd" type="submit">
    Edit Category.
    </button></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="CatEdit" value="1">
<?

}

function save_main_category() {

 $TV = form_addslashes();

 dbWrite("update tbl_auction_categories set cat_name = '$TV[MainCat]' where cat_id = '$_REQUEST[MainCatID]'");

}

function edit_main_category2() {

 $query = dbRead("select * from tbl_auction_categories where cat_id = '$_REQUEST[MainCat]'");
 $row = mysql_fetch_assoc($query);

?>
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">MAIN CATEGORY EDIT</td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2">Main Category:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="MainCat" value="<?= $row[cat_name] ?>"></td>
  </tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF">
    <button name="catadd" type="submit">
    Save Category.
    </button></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="MainCatID" value="<?= $row[cat_id] ?>">
<input type="hidden" name="CatSave" value="1">
<input type="hidden" name="CatEdit" value="1">
<?

}

function edit_main_category() {

?>
<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">MAIN CATEGORY EDIT</td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2">Main Category:</td>
    <td width="70%" bgcolor="#FFFFFF">
    <?
    
	 $dbgetcat = dbRead("select tbl_auction_categories.* from tbl_auction_categories order by tbl_auction_categories.cat_name ASC");
	 form_select('MainCat',$dbgetcat,'cat_name','cat_id',$_REQUEST[MainCat]);
    
    ?>
    </td>
  </tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF">
    <button name="catadd" type="submit">
    Edit Category.
    </button></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="CatEdit" value="1">
<?

}

function add_category_to_database() {

 // Check to see if the category exists. if so return a value.
 
 $TV = form_addslashes();
 
 $query = dbread("select * from tbl_auction_sub_categories where cat_name = '$TV[CategoryName]' and parent_id = '$TV[MainCat]'");

 if(mysql_num_rows($query) > 0) {
 
  // More than 0 rows means the category exists. return something so that the error is displayed.
  return "1";
 
 } else {
 
  // Category isn't there add it and return nothing.

  $TV = form_addslashes();
  
  dbWrite("insert into tbl_auction_sub_categories (parent_id,cat_name,deleted) values ('$TV[MainCat]','$TV[CategoryName]','0')");
 
 }

}

function add_category($errormsg = false) {

?>

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" class="Heading" align="center">CATEGORY ADD</td>
  </tr>
  <?
   if($errormsg) {
   ?>
   <tr>
     <td width="100%" align="center" class="Heading2" colspan="2"><font color="#FF0000">That Category Already Exists!!</font></td>
   </tr>
   <?
  }
  ?>
  <tr>
    <td width="30%" align="right" class="Heading2">Category Name:</td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="CategoryName" size="30" value="<?= $_REQUEST[CategoryName] ?>"></td>
  </tr>  
  <tr>
    <td width="30%" align="right" class="Heading2">Main Category:</td>
    <td width="70%" bgcolor="#FFFFFF">
    <?
    
	 $dbgetcat = dbRead("select tbl_auction_categories.* from tbl_auction_categories order by tbl_auction_categories.cat_name ASC");
	 form_select('MainCat',$dbgetcat,'cat_name','cat_id',$_REQUEST[MainCat]);
    
    ?>
    </td>
  </tr>
  <tr>
    <td width="30%" class="Heading2">&nbsp;</td>
    <td width="70%" bgcolor="FFFFFF">
    <button name="catadd" type="submit">
    Add Category
    </button></td>
  </tr>
</table>
</td>
</tr>
</table>
<input type="hidden" name="CatAdd" value="1">
<?

}

?>