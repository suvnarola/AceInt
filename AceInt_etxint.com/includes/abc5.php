<?
 include("/home/etxint/admin.etxint.com/includes/global.php");

 
  //$query = dbRead("select * from tbl_area_regional where StateID = 33 order by RegionalName");
  //$query = dbRead("select * from area where state = '".addslashes("H&#7891; Ch� Minh")."' group by disarea order by disarea");
  //$query = dbRead("select * from tbl_area_states where CID = 8 order by StateName");
  //$query = dbRead("select * from tbl_corp_headers where CID = 1 order by pageid");
  //$query = dbRead("select * from tbl_corp_pages where pageid = 66 ");
  //$query = dbRead("select * from tbl_members_companyinfo where DateTo > '2000-01-01' ");
  //$query = dbRead("select sum(invoice.currentfees) as CashFees, extract(year_month from invoice.date) as date1 from invoice, members where (members.memid = invoice.memid) and members.CID = 1 and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) group by date1");
  //$query = dbRead("select sum(invoice.overduefees) as Over, sum(invoice.currentpaid) as PaidFees from invoice, members where (members.memid = invoice.memid) and members.CID = 1 and date = '2003-01-31'");
  //$query = dbRead("select place, sum(invoice.overduefees) as Over, sum(invoice.currentpaid) as PaidFees from invoice, members, area where (members.memid = invoice.memid) and (members.licensee = area.FieldID) and members.CID = 1 and invoice.date = '2003-01-31' group by members.licensee order by members.licensee ");
  //$query = dbRead("select area.state as place, sum(invoice.overduefees) as Over, sum(invoice.currentpaid) as PaidFees from invoice, members, area where (members.memid = invoice.memid) and (members.licensee = area.FieldID) and members.CID = 1 and invoice.date = '2003-01-31' group by area.state order by area.state ");
  //$query = dbRead("select sum(invoice.overduefees) as Over, sum(invoice.currentpaid) as PaidFees, sum(invoice.currentfees) as CurrentFees, extract(year_month from invoice.date) as date1 from invoice, members where (members.memid = invoice.memid) and members.CID = 1 and members.memid NOT IN (".get_non_included_accounts($_SESSION['User']['CID']).",10568) group by date1");
  $query = dbRead("select * from tbl_area_regional where CID = 9 and FieldID not in (209,210) order by RegionalName");
 
 
 #loop around
 while($row = mysql_fetch_assoc($query)) {

    //dbWrite("update members set salesmanid = '".$row[FieldID]."' where memid = '".$row[memid]."'");
    //print "update members set salesmanid = '".$row[FieldID]."' where memid = '".$row[memid]."'\r\n\r\n";
   //print $row[RegionalName].", ".$row[FieldID];
     //print $row[disarea].", ".$row[FieldID];
     //print $row[StateName];
    //$d = explode("-",$row[DateTo]);
    //$date2 = date("Y-m-d", mktime(0,0,0,$d[1],$d[2]-1,$d[0]));
    //print $row[date1]." - ".$row[CashFees]."<br>";
    //$CashFees = $row[Over]+$row[PaidFees];
    //print $row[date1]." - ".$row[Over]." - ".$row[PaidFees]." - ".$row[CurrentFees]."<br>";
    //print $row[place]." - ".$CashFees."<br>";
    //$TO = $TO+$CashFees;
    //dbWrite("update tbl_members_companyinfo set DateTo = '".$date2."' where FieldID = '".$row[FieldID]."'");
    //print  $row[DateTo]." / ".$date2."<br>";
   //$query1 = dbRead("select * from country where Display = 'Yes' order by countryID");
    //while($row1 = mysql_fetch_assoc($query1)) { 
    
      //dbWrite("insert into tbl_corp_headers (pageid,page_header,page_link,page_active,CID) values ('66','Home','Home','1','".$row1['countryID']."')");
    //}
    dbWrite("insert into tbl_area_physical (AreaName,RegionalID,CID) values ('".addslashes(encode_text2($row[RegionalName]))."','".$row[FieldID]."','".$row[CID]."')");
    
    //print "<br>"; 
 }
//print $TO;

?>