<?

 
 include("progressbar.php");
 
 $Start = 1;
 $Finish = 100;
 
 while($Start != $Finish) {
 
	$Start++;
	
	fn_progress_bar($Start, $Finish);
	
	sleep(1); 
 
 }

?>