<?

 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");
 ini_set('max_execution_time','1500');
 
 $SQLQuery = dbRead("select registered_accounts_fuel.* from registered_accounts_fuel where cashFeePaid != 1 order by FieldID","ebanc_services");
 while($row = mysql_fetch_assoc($SQLQuery)) {

   $date = date("Y-m-d", mktime(0,0,0,date("m"),9-(($row['Terms']-$row['Payments_Left'])*7),date("Y")));
   $counter = 0;
   
   while($counter <= ($row['Terms']/2)) {

	$dateamounts[$date] += ($row['Plan_Amount']);  
    $date = date("Y-m-d", strtotime($date)+604800);
    $counter++;   
     
   }
 }
 ksort($dateamounts);
 //print_r($dateamounts);

?>
<table>
<?
foreach($dateamounts as $key => $value) { 
?>

<tr>
<td><?= $key ?></td>
<td><?= $dateamounts[$key] ?></td>
</tr>
<?}?> 
</table>