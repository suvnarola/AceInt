<?
if(!checkmodule("RESearch")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
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

if($_REQUEST[cs] || $cs == 1) {

#pages numbers and stuff.

 if(checkmodule("Log")) {
  add_kpi("39", "0");
 }

if($_REQUEST['next']) {
  $_REQUEST['pageno']+=1;
} elseif($_REQUEST['back']) {
  $_REQUEST['pageno']-=1;
}

if($_REQUEST['pageno'] == 1) {
  $first=0;
  $second=$_REQUEST['numpages'];
} else {
  $first=($_REQUEST['pageno']-1)*$_REQUEST['numpages'];
  $second=$_REQUEST['numpages'];
}

#search thing :)
if($_REQUEST['id']) {

  $data = addslashes($_REQUEST['id']);
  $gg = is_numeric($data);
  //print "11";
  if($gg) {
    $csearch="select id, agent, date, contactname, emailaddress, realestate.phone, tbl_area_regional.RegionalName as area, price, shortdesc, pricetrade, memid, evalby, evalamount, income, totalprice, image, under, agents.name from realestate, tbl_area_regional, agents where (realestate.area = tbl_area_regional.FieldID) and (realestate.agent = agents.agentid) and id = ".$_REQUEST['id']." and checked='1' order by id DESC limit $first, $second";
    $csearch2="select id from realestate where id = ".$_REQUEST['id']." and checked='1'";
  //print "33";
  } else {
  //print "22";
    $csearch="select id, agent, date, contactname, emailaddress, realestate.phone, tbl_area_regional.RegionalName as area, price, shortdesc, pricetrade, memid, evalby, evalamount, income, totalprice, image, under, agents.name from realestate, tbl_area_regional, agents where (realestate.area = tbl_area_regional.FieldID) and (realestate.agent = agents.agentid) and shortdesc like '%".$_REQUEST['id']."%' and checked='1' order by id DESC limit $first, $second";
    $csearch2="select id from realestate where shortdesc like '%".$_REQUEST['id']."%' and checked='1'";
  }

} elseif($_REQUEST['category'] && $_REQUEST['areaid']) {
  $csearch="select id, agent, date, contactname, emailaddress, realestate.phone, tbl_area_regional.RegionalName as area, price, shortdesc, pricetrade, memid, evalby, evalamount, income, totalprice, image, under, agents.name from realestate, tbl_area_regional, agents where (realestate.area = tbl_area_regional.FieldID) and (realestate.agent = agents.agentid) and category='".$_REQUEST['category']."' and area = '%".$_REQUEST['areaid']."%' and checked='1' order by id DESC limit $first, $second";
  $csearch2="select id from realestate where category='".$_REQUEST['category']."' and area like '%".$_REQUEST['areaid']."%' and checked='1'";
} elseif($_REQUEST['category']) {
  $csearch="select id, agent, date, contactname, emailaddress, realestate.phone, tbl_area_regional.RegionalName as area, price, shortdesc, pricetrade, memid, evalby, evalamount, income, totalprice, image, under, agents.name from realestate, tbl_area_regional, agents where (realestate.area = tbl_area_regional.FieldID) and (realestate.agent = agents.agentid) and category='".$_REQUEST['category']."' and checked='1' order by id DESC limit $first, $second";
  $csearch2="select id from realestate where category='".$_REQUEST['category']."' and checked='1'";
} elseif($_REQUEST['areaid']) {
  $csearch="select id, agent, date, contactname, emailaddress, realestate.phone, tbl_area_regional.RegionalName as area, price, shortdesc, pricetrade, memid, evalby, evalamount, income, totalprice, image, under, agents.name from realestate, tbl_area_regional, agents where (realestate.area = tbl_area_regional.FieldID) and (realestate.agent = agents.agentid) and area = '%".$_REQUEST['areaid']."%' and checked='1' order by id DESC limit $first, $second";
  $csearch2="select id from realestate where area like '%".$_REQUEST['areaid']."%' and checked='1'";
} else {
  $csearch="select id, agent, date, contactname, emailaddress, realestate.phone, tbl_area_regional.RegionalName as area, price, shortdesc, pricetrade, memid, evalby, evalamount, income, totalprice, image, under, agents.name from realestate, tbl_area_regional, agents where (realestate.area = tbl_area_regional.FieldID) and (realestate.agent = agents.agentid) and checked='1' order by id DESC limit $first, $second";
  $csearch2="select id from realestate where checked='1'";
}

$dbsearch=mysql_db_query($db, $csearch, $linkid);
$dbsearch2=mysql_db_query($db, $csearch2, $linkid);
$searchcount=mysql_num_rows($dbsearch2);
$pagenumber=$_REQUEST['pageno'];
$totalpages2=$searchcount/$_REQUEST['numpages'];
$totalpages=ceil($totalpages2);


$dbtotalre=mysql_db_query($db, "select count(*) as tc from realestate", $linkid);
$row2=mysql_fetch_array($dbtotalre);

?>
<html>
<head>
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=yes,status=yes,resizable=no,menubar=no,width=639,height=479');
}
</script>
</head>
<body>

<form name="classearch" method="POST" action="body.php?page=re_search">
<table width="620" cellpadding="1" cellspacing="0" border="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td>
<table width="620" border="0" cellpadding="3" cellspacing="0">
		<tr height="15">
			<td colspan="3" class="Heading" align="center"><b>&nbsp;<?= get_word("48") ?>.</b></td>
		</tr>
</table>
  </td>
 </tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellpadding="3" cellspacing="0">
					<tr>
						<td align="right" class="Heading2">Data:</td>
						<td bgcolor="#FFFFFF"><input type="text" name="id" size="15" value=""></td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
					</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("26") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="category">
						<option selected value="">All Categories</option>
							<?

						$dbgetrecat=mysql_db_query($db, "select recatid, recategory from recategories order by recategory ASC", $linkid);
						while($row = mysql_fetch_array($dbgetrecat)) {

							?>
							<option value="<?= $row[recatid] ?>"><?= $row[recategory] ?></option>
							<?
						}
						?>
						</select></td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
						<td align="right" bgcolor="#FFFFFF"><?= get_word("52") ?>: <b><?= get_all_added_characters($row2[tc]) ?></b></td>
					</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("78") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="areaid">
						<option selected value="">All Areas</option>
                        <?

						//$dbgetareas=mysql_db_query($db, "select disarea from area where CID='".$_SESSION['User']['CID']."' group by disarea asc", $linkid);
					    $dbgetareas = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName asc");
						while($row = mysql_fetch_array($dbgetareas)) {

						 ?>
						 <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
						 <?

						}

						?>
						</select></td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
					</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("126") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="numpages">
						<option selected value="6">6</option>
						<option value="12">12</option>
						<option value="18">18</option>
						<option value="36">36</option>
						<option value="72">72</option>
						<option value="10000000">All</option>
						</select><input type="hidden" value="1" name="pageno"></td>
						<td colspan="2" align="right" bgcolor="#FFFFFF"><input name="cs" type="submit" value="<?= get_word("48") ?> >>"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
</tr>
</table>
</form>


<table width="620" border="0" cellpadding="0" cellspacing="1" >
	<tr>
		<td align="left"><?= get_word("127") ?>: <b><?= $searchcount ?></b></td>
		<td align="right">Page <?= $pagenumber ?> of <?= $totalpages ?></td>
	</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="1">
	<tr height="15" width="100%">
		<td colspan="3" class="Heading"><b>&nbsp;<?= get_word("48") ?>.</b></td>
	</tr>
<?

		if(mysql_num_rows($dbsearch) == 0) {

		?>
		<p align="center"><br><b>No Search Results</b><br><br></p>
		<?

		} else {

		while($row = mysql_fetch_array($dbsearch)) {

		$newdet=substr($row[shortdesc], 0, 400);
		$getagent=mysql_db_query($db, "select name from agents where agentid='$row[agent]'", $linkid);
		$agentrow=mysql_fetch_array($getagent);

		?>
		<tr width="100%">
		<td valign="top" bgcolor="#FFFFFF">
		<table border="0" width="100%" cellspacing="1" cellpadding="3">
				<tr>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
					<td width="300" colspan=3" align="left" valign="top" class="Heading"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><b><?= $row['area'] ?> - <?= $row['totalprice'] ?></b></font></td>
					<td align="right" valign="top" class="Heading"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000">ID: <?= $row['id'] ?></font></td>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
				</tr>

				<tr>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
					<td width="75" rowspan="12" valign="top">

					<?
					if($row['image'] != "noimg.gif") {
					$imagename = $row['image'];
					?>
					<a href="javascript:open_win('http://www.ebanctrade.com/realimages/<?= $imagename ?>');"><img src="http://www.ebanctrade.com/realimages/thumb-<?= $row['image'] ?>"></a><br>
					<?
					}
					?>
					<?

					if($row['under'] == 1) {
					 ?>
					 <br><font face="Verdana, Arial, Helvetica, sans-serif" size="3" color="#FF9900"><center><b>Under Contract</b></center></font>
					 <?
					} elseif($row['under'] == 2) {
					 ?>
					 <br><font face="Verdana, Arial, Helvetica, sans-serif" size="3" color="#FF9900"><center><b>SOLD</b></center></font>
					 <?
					}
					?>
					</td>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
					<td align="left" colspan="2" valign="top" ><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><a class="nav" href="javascript:open_win('body.php?page=re_detail&id=<?= $row[id] ?>&agent=<?= $row[agent] ?>');" class="menu4"><?= get_all_added_characters($row['shortdesc']) ?></a></font></td>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
					<td width="5">&nbsp;&nbsp;&nbsp;</td>
					<td align="left" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><br><?= get_word("122")?>: <?= $row['pricetrade'] ?><br><?= get_word("121")?>: <?= $row['price'] ?></font></td>
					<td align="left" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><br><?= get_all_added_characters($row['name']) ?><br><?= get_all_added_characters($row['contactname']) ?><br><?= get_all_added_characters($row['phone']) ?><br><?= get_all_added_characters($row['emailaddress']) ?></font></td>

				</tr>

		</table>
		</td>
	</tr>

		<?

		}

		}

		?>
</table>
</td>
</tr>
</table>
<br>
<table width="620" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left" width="62">
		<form method="POST" action="body.php?page=re_search">
		<input type="hidden" value="<?= $sendcategory ?>" name="category">
		<input type="hidden" value="<?= $sendarea ?>" name="area">
		<input type="hidden" value="<?= $_REQUEST['pageno'] ?>" name="pageno">
		<input type="hidden" value="1" name="cs">
		<input type="hidden" value="<?= $_REQUEST['numpages'] ?>" name="numpages">
		<input type="hidden" value="<?= $_REQUEST['pagenos'] ?>" name="pagenos">
		<?

		if(!$_REQUEST['pagenos']) {
		  $_REQUEST['pagenos']=1;
		}

		$toplimit=$_REQUEST['pagenos']*15;
		$startlimit=$toplimit-14;

		if($_REQUEST['pageno'] != 1) {

		?>
		<input type="submit" name="back" value="<< <?= get_word("113") ?>">
		<?

		} else {

		?>&nbsp;
		<?

		}

		?>
		</td>
		<td align="center"><font face="Verdana" size="1">

		<?
		if($_REQUEST['pagenos'] > 1) {
		$pn2=$_REQUEST['pagenos']-1;
		?></font>
		<a class="nav" href="body.php?page=re_search&category=<?= $sendcategory ?>&area=<?= $sendarea ?>&pageno=<?= $pageno ?>&cs=1&numpages=<?= $numpages ?>&pagenos=<?= $pn2 ?>" class="menu5"><?= get_word("113") ?> </a>
		<?

		}

		$pagecounter=$startlimit;
		if($toplimit >= $totalpages) {
		  $toplimit2=$totalpages;
		} else {
		  $toplimit2=$toplimit;
		}
		while($pagecounter <= $toplimit2) {

			if($pagecounter == $pageno) {
			?>
			<a class="nav" href="body.php?page=re_search&category=<?= $sendcategory ?>&area=<?= $sendarea ?>&pageno=<?= $pagecounter ?>&cs=1&numpages=<?= $_REQUEST['numpages'] ?>&pagenos=<?= $_REQUEST['pagenos'] ?>&productname=<?= $sendproductname ?>" class="menu5"><b><?= $pagecounter ?></b></a>
			<?
			$pagecounter+=1;
			} else {
			?>
			<a class="nav" href="body.php?page=re_search&category=<?= $sendcategory ?>&area=<?= $sendarea ?>&pageno=<?= $pagecounter ?>&cs=1&numpages=<?= $_REQUEST['numpages'] ?>&pagenos=<?= $_REQUEST['pagenos'] ?>&productname=<?= $sendproductname ?>" class="menu5"><?= $pagecounter ?></a>
			<?
			$pagecounter+=1;
			}

		}

		if($totalpages > $toplimit) {

		$pn=$_REQUEST['pagenos']+1;
		?> <a class="nav" href="body.php?page=re_search&category=<?= $sendcategory ?>&area=<?= $sendarea ?>&pageno=<?= $_REQUEST['pageno'] ?>&cs=1&numpages=<?= $_REQUEST['numpages'] ?>&pagenos=<?= $pn ?>" class="menu5"><?= get_word("130") ?></a>
		<?

		}

		?></td>
		<td align="right" width="62"><?

		if($_REQUEST['pageno'] != $totalpages) {

		?><input type="submit" name="next" value="<?= get_word("130") ?> >>"><?

		} else {

		}

		?>
		</form>
		</td>
	</tr>
</table>
<br>
</body>
</html>
<?

} else {

$dbtotalre=mysql_db_query($db, "select count(*) as tc from realestate", $linkid);
$row2=mysql_fetch_array($dbtotalre);

?>
<form name="classearch" method="POST" action="body.php?page=re_search">
<table width="620" cellpadding="1" cellspacing="0" border="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td>
<table width="620" border="0" cellpadding="3" cellspacing="0">
		<tr height="15">
			<td colspan="3" class="Heading" align="center"><b>&nbsp;<?= get_word("48") ?>.</b></td>
		</tr>
</table>
  </td>
 </tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellpadding="3" cellspacing="0">
					<tr>
						<td align="right" class="Heading2">Data:</td>
						<td bgcolor="#FFFFFF"><input type="text" name="id" size="15" value=""></td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
					</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("26") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="category">
						<option selected value="">All Categories</option>
							<?

						$dbgetrecat=mysql_db_query($db, "select recatid, recategory from recategories order by recategory ASC", $linkid);
						while($row = mysql_fetch_array($dbgetrecat)) {

							?>
							<option value="<?= $row[recatid] ?>"><?= $row[recategory] ?></option>
							<?
						}
						?>
						</select></td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
						<td align="right" bgcolor="#FFFFFF"><?= get_word("52") ?>: <b><?= $row2[tc] ?></b></td>
					</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("78") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="areaid">
						<option selected value="">All Areas</option>
                        <?

						//$dbgetareas=mysql_db_query($db, "select disarea from area where CID='".$_SESSION['User']['CID']."' group by disarea asc", $linkid);
					    $dbgetareas = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional, tbl_area_states where (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID='".$_SESSION['User']['CID']."' order by RegionalName asc");
						while($row = mysql_fetch_array($dbgetareas)) {

						 ?>
						 <option value="<?= $row[FieldID] ?>"><?= $row[RegionalName] ?></option>
						 <?

						}

						?>
						</select></td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
						<td bgcolor="#FFFFFF">&nbsp;</td>
					</tr>
					<tr>
						<td align="right" class="Heading2"><?= get_word("126") ?>:</td>
						<td bgcolor="#FFFFFF"><select name="numpages">
						<option selected value="6">6</option>
						<option value="12">12</option>
						<option value="18">18</option>
						<option value="36">36</option>
						<option value="72">72</option>
						<option value="10000000">All</option>
						</select><input type="hidden" value="1" name="pageno"></td>
						<td colspan="2" align="right" bgcolor="#FFFFFF"><input name="cs" type="submit" value="<?= get_word("48") ?> >>"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
</tr>
</table>
</form>

<?

}

?>