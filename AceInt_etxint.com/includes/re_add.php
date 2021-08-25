<html>
<head>
<title>Real Rstate</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
</head>
<body>
<?
if(!checkmodule("LynReports")) {
?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>

<form method="POST" action="body.php?page=re_add&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();
$tabarray = array('Add','Edit','Picture Upload','Cat Add','Agent Add');

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  addRE();

} elseif($_GET[tab] == "tab2") {

  mem();

} elseif($_GET[tab] == "tab3") {

  fees();

} elseif($_GET[tab] == "tab4") {

  refees();

} elseif($_GET[tab] == "tab5") {

  dd();

} elseif($_GET[tab] == "tab6") {

  unallocated();

}
?>

</form>

<?
function addRE() {
?>
<body onload="javascript:setFocus('add','contactname');">
<form name="add" method="post" action="/general.php" >
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" align="center" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td class="Heading" colspan="2" align="center"><b>&nbsp;<?= get_page_data("1") ?>.</b></td>
	</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("128") ?>:</b></td>
					<td bgcolor="#FFFFFF"><select name="agent">
                    <?
					 if($_SESSION['User']['Area'] == 1) {
					  $aa = " Display = 'Y' and CID = ".$_SESSION['User']['CID']." order by name ";
					 } else {
					  $aa = " agentid = ".$_SESSION['User']['AgentID']."";
					 }
					 $query = dbRead("select * from agents where ".$aa."");
					while($row = mysql_fetch_assoc($query)) {

					?>
					<option value="<?= $row['agentid'] ?>"><?= $row['name'] ?></option>
					<?

					}

					?>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("5") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="30" type="text" name="contactname"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("26") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <select name="category">
                    <?

					$dbgetrecat = dbRead("select recatid, recategory from recategories where recategory != ' ' order by recategory");
					while($row = mysql_fetch_assoc($dbgetrecat)) {

					?>
					<option value="<?= $row['recatid'] ?>"><?= $row['recategory'] ?></option>
					<?

					}

					?>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("121") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="price"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("122") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="pricetrade"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("123") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="totalprice"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("129") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="address"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("7") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="phone"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("15") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="suburb"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("18") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="10" type="text" name="postcode"></td>
				</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("78") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="area">
                        <?
					    $dbgetareas = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName asc");
						while($row = mysql_fetch_array($dbgetareas)) {
   					    ?>
						 <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
						<?
						}
						?>
						</select></td>
					</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("9") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input size="25" type="text" name="emailaddress"></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b>Status:</b></td>
					<td bgcolor="#FFFFFF"><select name="under">
  					<option value="0">For Sale</option>
  					<option value="1">Under Contract</option>
  					<option value="2">Sold</option>
					</select></td>
				</tr>
				<tr>
					<td width="150" align="right" class="Heading2"><b><?= get_word("27") ?>:</b></td>
					<td bgcolor="#FFFFFF">
                    <input type="text" name="detaildesc" size="20"></td>
				</tr>
					<tr>
					<td width="150" valign="top" align="right" class="Heading2"></td>
					<td align="left" bgcolor="#FFFFFF"><input type="submit" value="<?= get_word("83") ?>" name="addretodb"></td>
				</tr>
			</table>

</td>
</tr>
</table>
<input type="hidden" name="addretodb" value="1">
</form>
<?
}
?>