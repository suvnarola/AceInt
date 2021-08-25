<?

function fn_progress_bar($intCurrentCount = 100, $intTotalCount = 100) {

   static $intNumberRuns = 0;
   static $intDisplayedCurrentPercent = 0;
   $strProgressBar = '';
   $dblPercentIncrease = (100 / $intTotalCount);
   $intCurrentPercent = intval($intCurrentCount * $dblPercentIncrease);
   $intNumberRuns++;
       
	if(1 == $intNumberRuns)   {
		$strProgressBar = "
		<table width='50%' id='progress_bar' summary='progress_bar' align='center'><tbody><tr>
		<td id='progress_bar_complete' width='0%' align='center' style='background:#CCFFCC;'>&nbsp;</td>
		<td style='background:#FFCCCC;'>&nbsp;</td>
		</tr></tbody></table>
		";
	
	} else if($intDisplayedCurrentPercent <> $intCurrentPercent) {
		$intDisplayedCurrentPercent = $intCurrentPercent;
		$strProgressBar = "
		<script type='text/javascript' language='javascript'>
		dhd_fn_progress_bar_update($intCurrentPercent);
		</script>
		";
	}
	
	if(100 <= $intCurrentPercent) {
		$intNumberRuns = $intDisplayedCurrentPercent = 0;
		$strProgressBar = "
		<script type='text/javascript' language='javascript'>
		document.getElementById('progress_bar').style.visibility='hidden';
		document.location.href='test.php';
		</script>
		";
	}

	echo $strProgressBar;
	forceFlush();

}

function dummyErrorHandler ($errno, $errstr, $errfile, $errline) {
}

function forceFlush() {    
   ob_start();
   ob_end_clean();
   flush();
   set_error_handler("dummyErrorHandler");
   ob_end_flush();
   restore_error_handler();
}

?>