<?

 /**
  * Facility Conversion System
  */

 $FacilityArray = array(
 		'16084',
 		'16204',
 		'16205',
 		'17067',
 		'17060',
 		'16203',
 		'17074',
 		'17053',
 		'17037',
 		'18649',
 		'18655',
 		'20796',
 		'22131',
 		'22846',
 		'25362'
 );
 
 foreach($FacilityArray as $key) {
 
 	$FacilitySQL = dbRead("select transactions.* from transactions where memid = ". $key ." or to_memid = " . $key);
 	while($FacilityRow = mysql_fetch_assoc($FacilitySQL)) {
 	
 		if($FacilityRow['type'] == 2) {
 		
 			if($FacilityRow['sell'] < 0) {
 			
 				// Update this record so that its a positve buy instead of a negative sell.
 				dbWrite("update transactions set sell = 0, buy = ".abs($FacilityRow['sell']).", type = 1 where id = " . $FacilityRow['id']);
 				
 			}
 		
 		}
 		
 		if($FacilityRow['type'] == 1) {
 		
 			if($FacilityRow['buy'] < 0) {
 			
 				// Update this record so that its a positive sell instead of a negative buy.
 				dbWrite("update transactions set sell = ".abs($FacilityRow['buy']).", buy = 0, type = 2 where id = " . $FacilityRow['id']);
 			
 			}
 		
 		}
 		
 		$Count++;
 		
 	}
 	
 }
 
 print $Count . " Rows Updated";
 
 print "</pre>";

?>