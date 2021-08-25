<?
$time_start = getmicrotime();

if($_REQUEST['checked']) {

	$genArray = $_REQUEST['id2'];

 $count = sizeof($genArray);
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {
	dbWrite("update classifieds set checked='1' where id='".$genArray[$i]."'");
 }

 if(checkmodule("Log")) {
  add_kpi("45", "0");
 }

}

if($_REQUEST['delete']) {

	$genArray = $_REQUEST['id2'];

 $count = sizeof($genArray);
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {
	dbWrite("delete from classifieds where id='".$genArray[$i]."'");
 }

 if(checkmodule("Log")) {
  add_kpi("45", "0");
 }

}

if($_REQUEST['approved']) {

 $genArray = $_REQUEST['id2'];

 $count = sizeof($genArray);
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {
	dbWrite("update classifieds set int_check='1' where id='".$genArray[$i]."'");

	$message = "New Classified has been added. [".$_REQUEST[clasid]."]\r\n\r\nName: $row[name]\r\nProductname:$row[productname]\r\nPhone Number:$row[phoneno]\r\nDescription:$row[shortdesc]\r\nPrice Cash: $row[price]\r\nPrice Trade: $row[tradeprice]\r\n\r\nMembers Section.";

    $query2 = dbRead("select classifieds.*, countrycode, country.name as name from classifieds, country where (classifieds.CID = country.countryID) and id='".$genArray[$i]."'");
    while ($row2 = mysql_fetch_assoc($query2)) {
	  $message = "New Classified has been added. [".$row2[clasid]."]\r\n\r\nName: $row2[name]\r\nProductname:$row2[productname]\r\nPhone Number:$row2[phoneno]\r\nDescription:$row2[shortdesc]\r\nPrice Cash: $row2[price]\r\nPrice Trade: $row2[tradeprice]\r\n\r\nMembers Section.";
	  mail("classified@".$row2['countrycode'].".".getWho($row2[logo], 2),"New International Classified",$message);
	  //mail("dave@ebanctrade.com","New Member Classified from ".$_SESSION['Country']['name'],$message);
   }

 }

 if(checkmodule("Log")) {
  add_kpi("45", "0");
 }

}
?>

<html>

<head>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
<meta name='GENERATOR' content='Microsoft FrontPage 5.0'>
<title>Classifieds Check</title>
</head>

<body>
<form method="post" action="body.php?page=clas_check" name="CL">
<table width="620" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
					<table width="100%" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="7" bgcolor="#000000" align="center" class="Heading"><?= get_page_data("1") ?></td>
						</tr>
						<tr>
							<td align="left" width="10%" class="Heading2"><b>ID:</b></td>
							<td align="left" width="40%" class="Heading2"><b><?= get_word("119") ?>:</b></td>
							<td align="left" width="20%" class="Heading2"><b><?= get_word("41") ?>:</b></td>
							<td align="left" width="15%" class="Heading2"><b><?= get_word("79") ?>:</b></td>
			                <td align="left" width="15%" class="Heading2"><b><?= get_word("121") ?>:</b></td>
							<td align="right" width="15%" class="Heading2"><b><?= get_word("122") ?>:</b></td>
							<td align="right" width="20%" class="Heading2"><b><?= get_page_data("2") ?>:</b></td>
						</tr>
						<?

						$dbgetmemcats = dbRead("select id, date, country.name as cname, price, tradeprice, productname, currency from classifieds, country where (classifieds.cid_origin = country.countryID) and CID=".$_SESSION['User']['CID']." and checked = 0 and int_check != 0 order by id");

						$foo = 0;

						while($row = mysql_fetch_assoc($dbgetmemcats)) {

						$cfgbgcolorone = "#CCCCCC";
						$cfgbgcolortwo = "#EEEEEE";
						$bgcolor = $cfgbgcolorone;
						$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

						$price2 = number_format($row['price'],2);
						$tradeprice2 = number_format($row['tradeprice'],2);

						?>
						<tr>
							<td align="left" width="10%" bgcolor="<?= $bgcolor ?>"><a class="nav" href="body.php?page=clas_edit&editclas2=true&clasid=<?= $row['id'] ?>&type1=true"><?= $row['id'] ?></a></td>
							<td align="left" width="25%" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row['productname']) ?></td>
							<td align="left" width="15%" bgcolor="<?= $bgcolor ?>"><?= $row['date'] ?></td>
							<td align="left" width="20%" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row['cname']) ?></td>
							<td align="right" width="15%" bgcolor="<?= $bgcolor ?>"><?= $row['currency'] ?><?= $price2 ?></td>
							<td align="right" width="15%" bgcolor="<?= $bgcolor ?>"><?= $row['currency'] ?><?= $tradeprice2 ?></td>
      						<td align="center" bgcolor="<?= $bgcolor ?>"><input type="checkbox" name="id2[]" value="<?= $row['id'] ?>"></td>
						</tr>
						<?
						$foo++;
						}
						?>
						</tr>
  <tr>
      <td width="150" colspan="5" height="30" class="Heading2">&nbsp;</td>
      <td width="450" align="right" colspan="2" height="30" class="Heading2"><input type="Submit" value="<?= get_page_data("2") ?>" name="checked">&nbsp;</td>
  </tr>
  <tr>
      <td width="150" colspan="5" height="30" class="Heading2">&nbsp;</td>
      <td width="450" align="right" colspan="2" height="30" class="Heading2"><input type="Submit" value="Delete" name="delete">&nbsp;</td>
  </tr>
  </table>
</td>
</tr>
</table>
</form>
<form method="post" action="body.php?page=clas_check" name="CL">
<table width="620" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
					<table width="100%" cellpadding="3" cellspacing="0">
						<tr>
							<td colspan="7" bgcolor="#000000" align="center" class="Heading">Classified for submission to other countries</td>
						</tr>
						<tr>
							<td align="left" width="10%" class="Heading2"><b>ID:</b></td>
							<td align="left" width="40%" class="Heading2"><b><?= get_word("119") ?>:</b></td>
							<td align="left" width="20%" class="Heading2"><b><?= get_word("41") ?>:</b></td>
							<td align="left" width="15%" class="Heading2"><b><?= get_word("79") ?>:</b></td>
					        <td align="right" width="15%" class="Heading2"><b><?= get_word("121") ?>:</b></td>
							<td align="right" width="15%" class="Heading2"><b><?= get_word("122") ?>:</b></td>
							<td align="right" width="20%" class="Heading2"><b><?= get_page_data("2") ?>:</b></td>
						</tr>
						<?

						$dbgetmemcats = dbRead("select id, date, country.name as cname, price, tradeprice, productname from classifieds, country where (classifieds.cid = country.countryID) and cid_origin = ".$_SESSION['User']['CID']." and CID != ".$_SESSION['User']['CID']." and checked = 0 and int_check = 0 order by id");

						$foo = 0;

						while($row = mysql_fetch_assoc($dbgetmemcats)) {

						$cfgbgcolorone = "#CCCCCC";
						$cfgbgcolortwo = "#EEEEEE";
						$bgcolor = $cfgbgcolorone;
						$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

						$price2 = number_format($row['price'],2);
						$tradeprice2 = number_format($row['tradeprice'],2);

						?>
						<tr>
							<td align="left" width="10%" bgcolor="<?= $bgcolor ?>"><a class="nav" href="body.php?page=clas_edit&editclas2=true&clasid=<?= $row['id'] ?>&type1=true"><?= $row['id'] ?></a></td>
							<td align="left" width="25%" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row['productname']) ?></td>
							<td align="left" width="15%" bgcolor="<?= $bgcolor ?>"><?= $row['date'] ?></td>
							<td align="left" width="20%" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row['cname']) ?></td>
							<td align="right" width="20%" bgcolor="<?= $bgcolor ?>"><?= $_SESSION['Country']['currency'] ?><?= $price2 ?></td>
							<td align="right" width="20%" bgcolor="<?= $bgcolor ?>"><?= $_SESSION['Country']['currency'] ?><?= $tradeprice2 ?></td>
      						<td align="center" bgcolor="<?= $bgcolor ?>"><input type="checkbox" name="id2[]" value="<?= $row['id'] ?>"></td>
						</tr>
						<?
						$foo++;
						}
						?>

  <tr>
      <td width="150" colspan="5" height="30" class="Heading2">&nbsp;</td>
      <td width="450" align="right" colspan="2" height="30" class="Heading2"><input type="Submit" value="<?= get_page_data("2") ?>" name="approved">&nbsp;</td>
  </tr>
  <tr>
      <td width="150" colspan="5" height="30" class="Heading2">&nbsp;</td>
      <td width="450" align="right" colspan="2" height="30" class="Heading2"><input type="Submit" value="Delete" name="delete">&nbsp;</td>
  </tr>

						<tr>
						    <td colspan="7" bgcolor="#FFFFFF" align="center">Page Generation Time: <?
						    $time_end = getmicrotime();
						    $time = $time_end - $time_start;
							$time = number_format($time,2);
							echo $time;
						    ?> seconds</td>
						</tr>
					</table>
</td>
</tr>
</table>
</form>

</body>
</html>