<?
 include("/home/etxint/admin.etxint.com/includes/global.php");
 
 $amount = 0; 
 
 //$query = dbRead("select * from feesowing, members where (feesowing.memid = members.memid) and status != 1 and CID = 1 and numfeesowing > 0"); 
 //$query = dbRead("select * from feespaid, members, country where (feespaid.memid = members.memid) and (members.CID = country.countryID) and numfeesowed > 0 and deducted_fees = 0 "); 
 $query = dbRead("select * from realimages"); 

 while($row = mysql_fetch_assoc($query)) {
   //$amount = 0;

   //if($row['numfeesowing'] > 0)  {
    //if($row['numfeesowed'] > 0)  {

    $hh = explode(".", $row['imagename'], 2);
   
    //$amount = $row['numfeesowing']*5.50;
    //dbWrite("update members set fee_deductions = ".$amount." where memid=".$row['memid']."");   
    //$amount = $row['numfeesowed']*$row['feemonthly'];
    //dbWrite("update feespaid set deducted_fees = ".$amount." where id=".$row['id'].""); 
    //dbWrite("update realimages set agent_id = ".$hh[0]." where realid=".$row['realid'].""); 
       
   //}

 } 

$gg = get_non_included_accounts(1);

print $gg;

  $year = "2002";
  $month = "02";
  $day = date("d", mktime(0,0,0,$month+1,1-1,$year));
    
  $ym = $year."-".$month."-".$day;
  //print $ym
?>