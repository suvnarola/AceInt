<?

 /**
  * Realestate Facility Change Version 1.01. Runs after midnight on the first.
  */

 $NoSession = true;
if($_REQUEST[Debug]) {
$Debug = 1;
}
 include("/home/etxint/admin.etxint.com/includes/global.php");
 $RunDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
 //$Debug = "1";

 /**
  * Need to Loop around the country table first.
  * Then we need to get the members out in each country with a realestate facility.
  * If they have more money that would cover reducing their facility get it done.
  */

 $SQLQuery = dbRead("select * from country  where countryID = 12 order by countryID");
 while($CountryRow = mysql_fetch_assoc($SQLQuery)) {

  /**
   * Main Body of Script.
   */

  if($Debug) {
   echo "<pre>";
   $query2 = "";
  }

  $query = dbRead("select memid, overdraft, maxfacility from members where overdraft > maxfacility and status != 1 and overdraft != 0 and CID='$CountryRow[countryID]'");
  while($row = mysql_fetch_assoc($query)) {

   $row4 = get_balance("$row[memid]");

	if($row[overdraft] > $row[maxfacility]) {

		$balCredit = getDailyAmount($row['memid'], $CountryRow['countryID']);

		if($balCredit > 0) {

			if(($row['overdraft'] - $balCredit) >= $row['maxfacility']) {

				$Answer = "Yes";
				$query2 = "update members set overdraft = (overdraft - " . $balCredit . ") where memid='" . $row[memid] . "'";

			} else {

				$Answer = "Yes";
				$query2 = "update members set overdraft = '" . $row[maxfacility] . "' where memid='" . $row[memid] . "'";

			}

		}

	} else {

		$Answer = "No";

	}

	if($Debug == false) {

		if($Answer == "Yes") {

			dbWrite($query2);
			facility($row[memid],$CountryRow);

		}

	}

    if($Debug) {
     echo "Memid: $row[memid]\r\n RE: $row[reoverdraft]\r\n GOODS: $row[overdraft]\r\n TOTAL: $row4[Total]\r\n QUERY: $query2\r\n UPDATE: $Answer\r\n $balCredit \r\n\r\n";
     $query2 = "";
    }

   }

  }

 /**
  * Internal Functions.
  */

	function getDailyAmount($memID, $CID) {

 		global $RunDate;
		//$transSQL = dbRead("select sum(sell-buy) as dailySum from transactions where memid = '" . $memID . "' and to_memid not in (" . get_non_included_accounts($CID) . ") and checked = '0' and dis_date = '" . date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) . "'");
		//$transSQL = dbRead("select sum(sell-buy) as dailySum from transactions where memid = '" . $memID . "' and to_memid not in (" . get_non_included_accounts($CID) . ") and checked = '0'");
        $transSQL = dbRead("select (sum(sell)-sum(buy)) as dailySum from transactions where memid='$memID' and checked = '0' and dis_date <= '$RunDate'");
		$transRow = mysql_fetch_assoc($transSQL);

		if($transRow['dailySum'] > 1) {

			return $transRow['dailySum'];

		} else {

			return 0;

		}

	}


function facility($memid,$Country) {

 //get the members facility out.
 $query6 = dbRead("select overdraft from members where memid='$memid'");
 $row6 = mysql_fetch_array($query6);

 //check to see what facility they have now.
 $query7 = dbRead("select (sum(sell)-sum(buy)) as curfacility from transactions where memid='$memid' and (to_memid='$Country[facacc]')");
 $row7 = mysql_fetch_array($query7);

 $difference = $row6[overdraft]-$row7[curfacility];

  //insert facility
  $month = date("n");
  $year = date("Y");
  $date = date("d");
  $t = mktime(1,1,1,$month,$date-1,$year);
  $t2 = $t-951500000;
  $t3 = mt_rand(1000,9000);
  $authno = $t2-$t3;
  $disdate = date("Y-m-d",$t);

  $checked="1";

  if($difference > 0) {

  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$Country[facacc]','$t','$memid','$difference','0','0','1','Repayment of Facility','$authno','$disdate','0','180')");
  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','$Country[facacc]','0','$difference','0','2','Repayment of Facility','$authno','$disdate','0','180')");
 	dbWrite("insert into tbl_members_facility (acc_no,date,facility_type,facility_amount,user_id) values ('$memid','$disdate','1','".$difference."','".$_SESSION['User']['FieldID']."')");

  } else {

  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$Country[facacc]','$t','$memid','0','".abs($difference)."','0','2','Repayment of Facility','$authno','$disdate','0','180')");
  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','$Country[facacc]','".abs($difference)."','0','0','1','Repayment of Facility','$authno','$disdate','0','180')");

    $amount = abs($difference);
    $queryf = dbRead("select * from tbl_members_facility where acc_no = '$memid' and facility_type = 1 having (facility_amount - facility_repay) != 0 order by date");
    while($rowf = mysql_fetch_array($queryf)) {
     if(($rowf['facility_amount']-$rowf['facility_repay']) < $amount) {
       dbWrite("update tbl_members_facility set facility_repay = '".$rowf['facility_amount']."' where FieldID = ".$rowf['FieldID']."");
	   $amount = $amount-($rowf['facility_amount']-$rowf['facility_repay']);
     } else {
       $newamount = ($rowf['facility_repay']+$amount);
       dbWrite("update tbl_members_facility set facility_repay = '".$newamount."' where FieldID = ".$rowf['FieldID']."");
	   $amount = 0;
     }
    }
  }

}

function get_balance($Memid) {

 global $RunDate;

 $SQLQuery = dbRead("select (sum(sell)-sum(buy)) as Total from transactions where memid='$Memid' and checked = 0 and dis_date <= '$RunDate'");
 $Result = mysql_fetch_assoc($SQLQuery);

 return $Result;

}
