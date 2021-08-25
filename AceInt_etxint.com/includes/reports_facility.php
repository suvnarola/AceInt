<?
$colspan = "9";
if($_REQUEST['month1'])  {
?>

<form method="POST" action="body.php?page=reports_facility&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">

<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="<?= $colspan ?>" align="center" class="Heading">Temp Unlisted Members Members</td>
   </tr>
   <tr>
     <td class="Heading2" width = "80"><b><?= get_word("1") ?>:</b></td>
     <td class="Heading2"><b><?= get_word("62") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("106") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("12") ?>:</b></td>
     <td class="Heading2" align = "right"><b>L/Traded:</b></td>
     <td class="Heading2" align = "right"><b>Balance:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("53") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("54") ?>:</b></td>
     <td class="Heading2" align = "right"><b><?= get_word("105") ?>:</b></td>
   </tr>
<?

  $foo = 0;
  $area2 = " and licensee = ".$_REQUEST['area']."";

  //$todate = date("Y-m", mktime(0,0,1,date(m)-2,1,date(Y)));
  //$todate = date("Y-m", mktime(0,0,1,$_REQUEST['month1'],1,$_REQUEST['year1']-$_SESSION['Country']['facility_renewal']));
//$todate = date("Y-m-d", mktime(0,0,1,$_REQUEST['month1']+1,1-1,$_REQUEST['year1']-$_SESSION['Country']['facility_renewal']));
  $todate = date("Y-m-d", mktime(0,0,1,date("m"),1,date("Y")-$_SESSION['Country']['facility_renewal']));

  //$query = dbRead("select members.*, feesowing.numfeesowing, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions left outer join feesowing on (members.memid = feesowing.memid) where (members.memid = transactions.memid) and status not in (1,3,6) and t_unlist = 1$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and status not in (1,3,6) and t_unlist = 1$area2 and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");
  //$query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from tbl_members_facility, members, transactions where (tbl_members_facility.acc_no = members.memid) and (members.memid = transactions.memid) and (facility_amount-facility_repay) > 0 and tbl_members_facility.date like '".$todate."-%' and status not in (1,3,6) and CID = '".$_SESSION['User']['CID']."' group by transactions.memid order by members.memid ASC");


$query = dbRead("
SELECT tbl_members_facility.acc_no as memid,

Sum(facility_amount-facility_repay) AS Expr1

FROM tbl_members_facility INNER JOIN members ON

tbl_members_facility.acc_no = members.memid

WHERE (

tbl_members_facility.facility_type = 1
and facility_amount-facility_repay > 0
AND tbl_members_facility.date < '".$todate."'
AND tbl_members_facility.date > '2005-09-22'
AND members.status Not In (1,3,6)
AND members.CID = '".$_SESSION['User']['CID']."')

GROUP BY tbl_members_facility.acc_no
order by members.memid ASC
");

  if(mysql_num_rows($query) == 0) {

    ?>
    <tr bgcolor="#FFFFFF">
      <td colspan="<?= $colspan ?>" align="center"><br>No Members in this category.<br><br></td>
    </tr>
    <?

  } else {

   $counter = 0;
   while($row = mysql_fetch_array($query)) {

    //if($row[overdraft] > 0 || $row[reoverdraft] >0 || $row[dollarfees] > 0)  {
    //if($row[overdraft] > 0 || $row[reoverdraft] > 0 || $row[dollarfees] > 0 || ($row[sell] - $row[buy]) != 0)  {

	 //$counter = $counter + 1;
     $cfgbgcolorone="#CCCCCC";
     $cfgbgcolortwo="#EEEEEE";

     $bgcolor=$cfgbgcolorone;
     $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

     //$bal = ($row[sell]-$row[buy]);
     //$net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));

     //$dtotal+=$row[dollarfees];
     //$btotal+=$bal;
     //$ftotal+=$row[overdraft];
     //$rtotal+=$row[reoverdraft];

     //$query1 = dbRead("select * from transactions where memid = '".$row['memid']."' and to_memid NOT IN ('".$_SESSION['Country']['reserveacc']."','".$_SESSION['Country']['rereserve']."','".$_SESSION['Country']['facacc']."','".$_SESSION['Country']['refacacc']."','".$_SESSION['Country']['adminacc']."') order by dis_date DESC limit 1");
	 //$row1 = mysql_fetch_array($query1);
     $query1 = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees
	 from members, transactions
	 where (members.memid = transactions.memid) and members.memid = ".$row['memid']."
	 group by transactions.memid
	 ");
	 $row1 = mysql_fetch_array($query1);

     $bal = ($row1[sell]-$row1[buy]);
     $net = (($row1[sell]-$row1[buy])-($row[Expr1]));


     $dtotal+=$row[dollarfees];
     $btotal+=$bal;
     $ftotal+=$row[overdraft];
     $rtotal+=$row[reoverdraft];

	//if($net < 0 || $net > 500) {
	if($net > 500) {
	 $counter = $counter + 1;
    ?>
    <tr bgcolor="<?= $bgcolor ?>">
      <td width = "80"><a href="body.php?page=member_edit&Client=<?= $row['memid'] ?>&pagno=1&tab=tab5 " class="nav" target="_blank"><?= $row[memid] ?></a></td>
      <td  nowrap width = "180"><?= $row1[companyname] ?></td>
      <td  nowrap align = "right"><?= number_format($row1[dollarfees],2) ?></td>
      <td  nowrap align = "center"><?= $row1[status] ?></td>
      <td  nowrap align = "right"><?= $row[dis_date] ?></td>
      <td  nowrap align = "right"><?= number_format($bal) ?></td>
      <td  nowrap align = "right"><?= number_format($row[Expr1]) ?></td>
      <td  nowrap align = "right"><?= number_format($row[reoverdraft]) ?></td>
      <td  nowrap align = "right"><?= number_format($net) ?></td>
    </tr>
<?

 	$foo++;
 	}
   }
  }

?>

    <tr bgcolor="#FFFFFF">
      <td width = "80"></td>
      <td  nowrap width = "180">No of Members = <?= $counter ?></td>
      <td  nowrap align = "right"><?= number_format($dtotal) ?></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"></td>
      <td  nowrap align = "right"><?= number_format($btotal) ?></td>
      <td  nowrap align = "right"><?= number_format($ftotal) ?></td>
      <td  nowrap align = "right"><?= number_format($rtotal) ?></td>
      <td  nowrap align = "right"></td>
    </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="<?= $colspan ?>" align="center">Page Generation Time: <?
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
<?

} else {

$f = date("n")+1;
?>
<form method="POST" action="body.php?page=reports_facility&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">
<table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan = "2" align="center" class="Heading">Temp Unlisted Members Report</td>
   </tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b>Facilities due for renewal in <?= get_word("38") ?>:</b></td>
			<td align="left" width="450" bgcolor="#FFFFFF">
			<select name="month1">
				<option <? if ($f == "1") { echo "selected "; } ?>value="1">January</option>
				<option <? if ($f == "2") { echo "selected "; } ?>value="2">February</option>
				<option <? if ($f == "3") { echo "selected "; } ?>value="3">March</option>
				<option <? if ($f == "4") { echo "selected "; } ?>value="4">April</option>
				<option <? if ($f == "5") { echo "selected "; } ?>value="5">May</option>
				<option <? if ($f == "6") { echo "selected "; } ?>value="6">June</option>
				<option <? if ($f == "7") { echo "selected "; } ?>value="7">July</option>
				<option <? if ($f == "8") { echo "selected "; } ?>value="8">August</option>
				<option <? if ($f == "9") { echo "selected "; } ?>value="9">September</option>
				<option <? if ($f == "10") { echo "selected "; } ?>value="10">October</option>
				<option <? if ($f == "11") { echo "selected "; } ?>value="11">November</option>
				<option <? if ($f == "12") { echo "selected "; } ?>value="12">December</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="Heading2"><b><?= get_word("39") ?>:</b></td>
		<td align="left" width="450" bgcolor="#FFFFFF">
		<?

		$query = get_year_array();
	    form_select('year1',$query,'','',date("Y"));

	   	?>
		</td>
	</tr>
   <tr>
     <td bgcolor="#FFFFFF" colspan="2" align = "right" nowrap><input type="submit" name="SearchBu" value="Search"></td>
   </tr>
  </table>
 </td>
 </tr>
</table>
<input type="hidden" name="search" value="1">

<?
}
?>