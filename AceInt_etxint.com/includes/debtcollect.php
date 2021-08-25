<?
function debtcollect($memberarray,$ltype,$header)  {

//global $pdf, $pdfsig, $row, $pdfimage;

  foreach($memberarray as $key => $value) {

    if($count == 0) {
     $andor="";
    } else {
     $andor="or";
    }
    $area_array.=" ".$andor." members.memid='".$value."'";
    $count++;

  }

 $query = dbRead("select members.*, sum(transactions.buy) as buy, sum(transactions.sell) as sell, sum(transactions.dollarfees) as dollarfees from members, transactions where (members.memid = transactions.memid) and letters = '3' and ($area_array) group by transactions.memid order by members.memid ASC");
 $blah = "Account No,Company Name,Account Holder,ABN,Business Address,Suburb/State/Postcode,Postal Address,Postal Suburb/State/Postcode,Phone No,Fax No],Mobile,Home No,Cash Fees Owing,Facility Owing,Total,60 Days++\r\n";

 #loop around
 while($row = mysql_fetch_assoc($query)) {

    $bal = ($row[sell]-$row[buy]);
    $net = (($row[sell]-$row[buy])-($row[overdraft]+$row[reoverdraft]));
    if($net < 0)  {
      $claim = (abs($net)+$row[dollarfees]);
      $net = abs($net);
    } else {
      $claim = $row[dollarfees];
      $net = "";
    }

  $query1 = dbRead("select sum(dollarfees) as fees from transactions where transactions.dis_date like '$date1-%' and memid='$row[memid]' and transactions.dollarfees < '0' and to_memid != '16083' group by memid");
  $row1 = mysql_fetch_assoc($query1);

  $query2 = dbRead("select * from members where memid='$row[memid]'");
  $row2 = mysql_fetch_assoc($query2);
  $reg = str_replace(",", " ", $row2[regname]);
  $hol = str_replace(",", " ", $row2[accholder]);

  $blah .= "$row2[memid],$reg,$hol,$row2[abn],$row2[streetno] $row2[streetname] $row2[suburb],$row2[city] $row2[state] $row2[postcode],$row2[postalno] $row2[postalname] $row2[postalsuburb],$row2[postalcity] $row2[postalstate] $row2[postalpostcode],$row2[phonearea] $row2[phoneno],$row2[faxarea] $row2[faxno],$row2[mobile],$row2[homephone],$row[dollarfees],$net,$claim,60 Days++\r\n";

 }

 return $blah;



}

?>