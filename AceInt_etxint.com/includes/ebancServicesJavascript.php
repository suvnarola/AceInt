<?

	include("includes/global.php");

	$ratesData = get_rates("HUF","AUD",1);

 	$planSQL = dbRead("select plans.* from plans where FieldID = 1", "empire_solutions");
 	$planObj = mysql_fetch_object($planSQL);

	
	//$tradeTotalSQL = dbRead("select (sum(sell)-sum(buy)) as totalTrade from transactions where memid = '". $regAccObj->Acc_No ."' and checked = 0");
	$tradeTotalSQL = dbRead("select (sum(sell)-sum(buy)) as totalTrade from transactions where memid = '". $_REQUEST['Client'] ."' and checked = 0");
	$tradeTotalObj = mysql_fetch_object($tradeTotalSQL);

?>
function calcTotals() {

	interval = setInterval("calcTotals2()",1);

}

function calcTotals2() {
	
	var inputTotal;
	var totalAmount;
	var totalCash;
	var totalTrade;
	var multiplier;
	var alerted;
	var weekAmount;

	<?
	
		if($_REQUEST['topUp']) {
			
			?>
	
				multiplier = 1;
	
			<?
	
		} else {
		
			?>
			
				multiplier = 1;
			
			<?
		
		}
	
	?>
			
	if(document.paymentForm.weeklyAmount.value > 0) {
				
		totalAmount = document.paymentForm.weeklyAmount.value * multiplier;
		
		<?
		
			if($_REQUEST['topUp']) {
				
				?>
		
				totalCash = ((totalAmount / 100) * (100 - <?= $planObj->Trade_Percent ?>));
		
				<?
		
			} else {
				
				?>
		
				totalCash = ((totalAmount / 100) * (100 - <?= $planObj->Trade_Percent ?>)) + <?= $planObj->Plan_Fee ?>;
		
				<?
		
			}
		
		?>
		
		totalTrade = ((totalAmount / 100) * <?= $planObj->Trade_Percent ?>);
		weekAmount = (totalAmount/ <?= $planObj->Plan_Terms ?>);
		
		document.paymentForm.totalPlan.value = Math.round(totalAmount).toFixed(2);
		document.paymentForm.totalCash.value = Math.round(totalCash).toFixed(2);
		document.paymentForm.totalTrade.value = Math.round(totalTrade).toFixed(2);
		document.paymentForm.weekAmount.value = Math.round(weekAmount*100)/100;

		<? 
		
			if($_SESSION['Country']['countryID'] == 12) {
		
				?>
				document.paymentForm.weeklyAmountAUD.value = Math.round(document.paymentForm.weeklyAmount.value*<?= $ratesData['Rate'] ?>).toFixed(2);
				
				<?
		
			}
		
		?>
		
		if((document.paymentForm.totalTrade.value > <?= $tradeTotalObj->totalTrade ?>)) {
		
			document.paymentForm.weeklyAmount.value = "";
			document.paymentForm.totalPlan.value = "0.00";
			
			<?
			
				if($_REQUEST['topUp']) {
					
					?>
			
						document.paymentForm.totalCash.value = "0.00";
			
					<?
			
				} else {
					
					?>
			
						document.paymentForm.totalCash.value = "<?= $planObj->Plan_Fee ?>";
			
					<?
			
				}
			
			?>
		
			document.paymentForm.totalTrade.value = "0.00";
			
			alert("Not enough trade in your account.");

		}
		
	} else {
	
		document.paymentForm.totalPlan.value = "0.00";
			<?
			
				if($_REQUEST['topUp']) {
					
					?>
			
						document.paymentForm.totalCash.value = "0.00";
			
					<?
			
				} else {
					
					?>
			
						document.paymentForm.totalCash.value = "<?= $planObj->Plan_Fee ?>";
			
					<?
			
				}
			
			?>
		document.paymentForm.totalTrade.value = "0.00";
		
		alerted = 2;
		
	}

}
function stopTotals(){

	<?
	
		if(!$_REQUEST['topUp']) {
			
			?>
			
				if((document.paymentForm.totalTrade.value < <?= $tradeTotalObj->totalTrade ?>)) {
			
					if(document.paymentForm.weeklyAmount.value < <?= $planObj->Min_Amount ?>) document.paymentForm.weeklyAmount.value = "<?= $planObj->Min_Amount ?>";
					<? 
					
						if($planObj->Max_Amount > 0) {
						
							?>
								if(document.paymentForm.weeklyAmount.value > <?= $planObj->Max_Amount ?>) document.paymentForm.weeklyAmount.value = "<?= $planObj->Max_Amount ?>";
							<?
							
						}
						
					?>
					document.paymentForm.weeklyAmount.value = Math.round(document.paymentForm.weeklyAmount.value).toFixed(2);
			
				}
			
			<?

		}
		
	?>

	clearInterval(interval);
	calcTotals2();
  
}
