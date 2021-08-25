<?

if(!checkmodule("SalesAdd")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">You ar<span lang="en-us">e
     </span>n<span lang="en-us">o</span>t allowed to use this function.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

if($_REQUEST['name']) {

#check to see if the person is already in there.
$query=mysql_db_query($db, "select count(*) as test from tbl_admin_users where Name ='".addslashes($_REQUEST['name'])."'", $linkid);
$row=mysql_fetch_array($query);

if($row[test] == 0) {
 #good no name like that. insert it in and display form again.
 dbWrite("insert into tbl_admin_users (Name,Position2,PhoneNo,Mobile,EmailAddress,Address,Area,CID,Suspended,SalesPerson,emsal,lang_code,salespercent) values ('".addslashes(encode_text2($_REQUEST['name']))."','Sales Consultant','".addslashes($_REQUEST['phoneno'])."','".addslashes($_REQUEST['mobile'])."','".addslashes($_REQUEST['email'])."','".addslashes($_REQUEST['address'])."','".addslashes($_REQUEST['area'])."','".addslashes($_SESSION['User']['CID'])."','1','1','1','".addslashes($_SESSION['User']['LangCode'])."','".addslashes($_REQUEST['salespercent'])."')");

 if(checkmodule("Log")) {
  add_kpi("35", "0");
 }
 
} else {
#error. display page again with values and error msg.

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body onload="javascript:setFocus('salespersonadd','name');">

<form name="salespersonadd" action="body.php?page=salesadd" method="POST">

<table border="2" width="620" bordercolor="#FF0000" style="border-collapse: collapse" cellpadding="3" cellspacing="0">
<tr>
<td>Error: That Sales Person is already on.</td>
</tr>
</table>
<br><br>
<table width="620" cellpadding="1" cellspacing="0" border="0">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" align="center" class="Heading">Sales Person Add</tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Name:</b><td width="70%" bgcolor="#FFFFFF">
    <input type="text" name="name" size="20" value="<?= $_REQUEST[name] ?>"></tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Phone Number:</b><td width="70%" bgcolor="#FFFFFF">
    <input type="text" name="phoneno" size="20" value="<?= $_REQUEST[phoneno] ?>"></tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Mobile No:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="mobile" size="20" value="<?= $_REQUEST[mobile] ?>"></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Email Address:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="email" size="20" value="<?= $_REQUEST[email] ?>"></td>
  </tr>  
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Address:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="address" size="30" value="<?= $_REQUEST[address] ?>"></td>
  </tr>  
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Area:</b><td width="70%" bgcolor="#FFFFFF"><select name="area">
<?
		$getarea=dbRead("select place, FieldID from area where CID like '".$_SESSION['User']['CID']."' order by place ASC");

		while($rowarea=mysql_fetch_assoc($getarea)) {
			?>
			<option value="<?= $rowarea[FieldID] ?>"><?= $rowarea[place] ?> <?if($rowarea[FieldID] == $_REQUEST['area']) {?>Selected<?}?></option>
			<?
		}
		$counter=mysql_num_rows($dbgetdataout);
		
?>
	  </select></tr>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Membership Commission %:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="salespercent" size="10"></td>
  </tr>  
  <tr>
    <td width="30%" align="right" class="Heading2">&nbsp;<td width="70%" bgcolor="#FFFFFF">
    <button name="B1" style="width: 120; height: 22" type="submit">
    <b><font face="Verdana" size="1">Add Sales Person</font></b>
    </button></tr>
</table>
</td>
</tr>
</table>

</form>

</body>

</html>
<?

die;
}

}

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body onload="javascript:setFocus('salespersonadd','name');">

<form name="salespersonadd" action="body.php?page=salesadd" method="POST">

<table width="620" cellpadding="1" cellspacing="0" border="0">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="100%" colspan="2" align="center" class="Heading">Sales Person Add</tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Name:</b><td width="70%" bgcolor="#FFFFFF">
    <input type="text" name="name" size="20"></tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Phone Number:</b><td width="70%" bgcolor="#FFFFFF">
    <input type="text" name="phoneno" size="20"></tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Mobile No:</b><td width="70%" bgcolor="#FFFFFF">
    <input type="text" name="mobile" size="20"></tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Email Address:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="email" size="20"></td>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Address:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="address" size="30"></td>
  </tr>     
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Area:</b><td width="70%" bgcolor="#FFFFFF"><select name="area">
<?
 $query = dbRead("select * from tbl_newmem where id='".addslashes($_GET['newmem'])."'");
 $row2 = mysql_fetch_assoc($query);

		$getarea=dbRead("select place, FieldID from area where CID like '".$_SESSION['User']['CID']."' order by place ASC");
		while($rowarea=mysql_fetch_assoc($getarea)) {
			?>
			<option value="<?= $rowarea[FieldID] ?>"><?= $rowarea[place] ?></option>
			<?
		}
		$counter=mysql_num_rows($dbgetdataout);
		
?>
	  </select></tr>
  </tr>
  <tr>
    <td width="30%" align="right" class="Heading2"><b>Membership Commission %:</b></td>
    <td width="70%" bgcolor="#FFFFFF"><input type="text" name="salespercent" size="10"></td>
  </tr>  
  <tr>
    <td width="30%" align="right" class="Heading2">&nbsp;<td width="70%" bgcolor="#FFFFFF">
    <button name="B1" style="width: 120; height: 22" type="submit">
    <b><font face="Verdana" size="1">Add Sales Person</font></b>
    </button></tr>
</table>
</td>
</tr>
</table>

</form>

</body>

</html>