<?

 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");
 ini_set('max_execution_time','1500');
 
 $SQLQuery = dbRead("select plans.*, registered_accounts.* from registered_accounts, plans where (registered_accounts.Plan_ID = plans.FieldID) and Status_ID = 3 and Date_Paid > '2006-01-01' order by Acc_No","ebanc_services");
 while($row = mysql_fetch_assoc($SQLQuery)) {

   $date = date("Y-m-d", strtotime(servicedates($row['FieldID'],1)));
   $counter = 0;
   
   //while($counter < (($row['Terms']/100)*(100-$row['Trade_Percent']))) {
    while($counter < ($row['Terms'])) {   
    
    
    //if($date > date("Y-m-d")) {
    //if($date > '2006-10-23') { 
    //if(($date <= '2006-10-22' && ($row[ServiceID] == 1 || $row[ServiceID] == 6)) || ($date > '2006-10-22' && ($row[ServiceID] == 2 || $row[ServiceID] == 3 || $row[ServiceID] == 7))) { 
       
      //if(((($row['Terms']/100)*(100-$row['Trade_Percent'])) - $counter > 0) && ((($row['Terms']/100)*(100-$row['Trade_Percent'])) - $counter < 1)) {
		  //$dateamounts[$date] += ($row['Plan_Amount'] * ((($row['Terms']/100)*(100-$row['Trade_Percent'])) - $counter));
		  //$total += ($row['Plan_Amount'] * ((($row['Terms']/100)*(100-$row['Trade_Percent'])) - $counter));

		  //$dateamounts[$row['FieldID']] += ($row['Plan_Amount'] * ((($row['Terms']/100)*(100-$row['Trade_Percent'])) - $counter));
		  //$total += ($row['Plan_Amount'] * ((($row['Terms']/100)*(100-$row['Trade_Percent'])) - $counter));
      //} else {
		  //$dateamounts[$date] += $row['Plan_Amount'];
		  //$total += $row['Plan_Amount'];
		  
		  $dateamounts[$row['FieldID']] += $row['Plan_Amount'];
		  $total += $row['Plan_Amount'];		  
      //}
      
    //}
    
    if($row['Plan_Display_Terms'] == 'Weekly') {
     $date = date("Y-m-d", strtotime($date)+604800);
    } else {
	 $startDateArray = explode("-", $date);    
     $date = date("Y-m-d", mktime(0,0,0,$startDateArray[1]+1,21,$startDateArray[0]));     
    }
    $counter++;    
   }
 }
 ksort($dateamounts);
 //print_r($dateamounts);

?>
<table>
<?
foreach($dateamounts as $key => $value) { 
  //dbWrite("update registered_accounts set Cash_Refund2 = ".$dateamounts[$key]." where FieldID = ".$key."","ebanc_services");        
?>

<tr>
<td><?= $key ?></td>
<td><?= $dateamounts[$key] ?></td>
</tr>
<?}?> 
<tr>
<td>TOTAL:</td>
<td><?= $total ?></td>
</tr>
</table>