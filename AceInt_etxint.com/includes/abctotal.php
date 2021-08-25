<?
 include("/home/etxint/admin.etxint.com/includes/global.php");


 //$query = dbRead("SELECT feespaid.memid as to_memid, feespaid.paymentdate, feespaid.amountpaid, feespaid.percent as to_percent, feespaid.area as to_licensee, area.inter as to_inter, feespaid.feesincurrid, feespaid.transID, feesincurred.memid, feesincurred.licensee, area_1.feepercent/2 as percent, area_1.inter FROM feespaid, area, feesincurred, area as area_1 where (feespaid.area = area.FieldID) and (feespaid.feesincurrid = feesincurred.fieldid) and (feesincurred.licensee = area_1.FieldID) and (feespaid.paymentdate Between '2007-03-16' And '2007-03-31') AND feespaid.type = 4 AND area_1.CID = 1");
 //$query = dbRead("SELECT feespaid.memid as to_memid, feespaid.paymentdate, feespaid.amountpaid, feespaid.percent as to_percent, feespaid.area as to_licensee, area.inter as to_inter, feespaid_1.memid as memid, feespaid_1.area as licensee, feespaid_1.percent as percent, area_1.inter as inter, fesspaid.transID FROM feespaid, area, feespaid as feespaid_1, area as area_1 where (feespaid.area = area.FieldID) and (feespaid.transID = feespaid_1.transID) and (feespaid_1.area = area_1.FieldID) and (feespaid.paymentdate Between '2007-03-16' And '2007-03-31') AND feespaid.type = 3 AND feespaid_1.type = 2 AND area_1.CID = 1");
 //$query = dbRead("select feespaid_2.memid, feespaid_2.paymentdate, feespaid_2.amountpaid, feespaid_2.numfeesowed, feespaid_2.deducted_fees, feespaid_2.percent, feespaid_2.area, feespaid_2.type, feespaid_2.feesincurrid, feespaid_2.id, feespaid_2.transID FROM feespaid, feespaid as feespaid_2 WHERE (feespaid.transID = feespaid_2.transID) and (feespaid.paymentdate Between '2007-04-01' And '2007-04-16') AND feespaid.amountpaid=11 AND feespaid.percent=0 AND feespaid.area=1 AND feespaid.type=4 and feespaid_2.type = 1 and feespaid_2.id < 86913");
 $dbtq = dbRead("select sum(currentfees+currentpaid+overduefees) as sumamount from members, invoice where (invoice.memid = members.memid) and invoice.date = '2007-10-31' and members.memid NOT IN (".get_non_included_accounts(1).",10568) and members.CID = 1 group by CID");
 //while($row = mysql_fetch_assoc($query)) {
 $row = mysql_fetch_assoc($dbtq);
 print $row[sumamount];

 //}
?>