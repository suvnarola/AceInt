<?

 /**
  * Realestate Facility Change Version 1.01. Runs after midnight on the first.
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 $RunDate = date("Y-m-d",mktime(0,0,0,date("m"),1-1,date("Y")));
 //$Debug = "1";

 /**
  * Need to Loop around the country table first.
  * Then we need to get the members out in each country with a realestate facility.
  * If they have more money that would cover reducing their facility get it done.
  */

 $SQLQuery = dbRead("select * from country order by countryID");
 while($CountryRow = mysql_fetch_assoc($SQLQuery)) {

  /**
   * Main Body of Script.
   */

  if($Debug) {
   echo "<pre>";
   $query2 = "";
  }

  $query = dbRead("select memid, overdraft, reoverdraft from members where status != 1 and reoverdraft != 0 and CID='$CountryRow[countryID]'");
  while($row = mysql_fetch_assoc($query)) {

   $row4 = get_balance("$row[memid]");

   if($row4[Total] > $row[overdraft]) {

    $difference_temp = $row4[Total] - $row[overdraft];

    if($difference_temp > 0) {

     if($difference_temp > $row[reoverdraft]) {
      $difference = 0;
     } else {
      $difference = $row[reoverdraft] - $difference_temp;
     }

     $Answer = "Yes";
     $query2 = "update members set reoverdraft='$difference' where memid='$row[memid]'";

     if($Debug == false) {
      dbWrite($query2);
      facility($row[memid],$CountryRow);
     }

    } else {

     $Answer = "No";

    }

    if($Debug) {
     echo "Memid: $row[memid]\r\n RE: $row[reoverdraft]\r\n GOODS: $row[overdraft]\r\n TOTAL: $row4[Total]\r\n QUERY: $query2\r\n UPDATE: $Answer\r\n\r\n";
     $query2 = "";
    }

   }

  }

 }

 /**
  * Internal Functions.
  */

function facility($memid,$Country) {

 //get the members facility out.
 $query6 = dbRead("select reoverdraft from members where memid='$memid'");
 $row6 = mysql_fetch_array($query6);

 //check to see what facility they have now.
 $query7 = dbRead("select (sum(sell)-sum(buy)) as curfacility from transactions where memid='$memid' and (to_memid='$Country[refacacc]')");
 $row7 = mysql_fetch_array($query7);

 $difference = $row6[reoverdraft]-$row7[curfacility];

  //insert facility
  $month = date("n");
  $year = date("Y");
  $t = mktime(1,1,1,$month,1-1,$year);
  $t2 = $t-951500000;
  $t3 = mt_rand(1000,9000);
  $authno = $t2-$t3;
  $disdate = date("Y-m-d",$t);

  $checked="1";

  if($difference > 0) {

  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$Country[refacacc]','$t','$memid','$difference','0','0','1','Repayment of Facility','$authno','$disdate','0','180')");
  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','$Country[refacacc]','0','$difference','0','2','Repayment of Facility','$authno','$disdate','0','180')");
 	dbWrite("insert into tbl_members_facility (acc_no,date,facility_type,facility_amount,user_id) values ('$memid','$disdate','2','".$difference."','".$_SESSION['User']['FieldID']."')");

  } else {

  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$Country[refacacc]','$t','$memid','0','".abs($difference)."','0','2','Repayment of Facility','$authno','$disdate','0','180')");
  	dbWrite("insert into transactions (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid','$t','$Country[refacacc]','".abs($difference)."','0','0','1','Repayment of Facility','$authno','$disdate','0','180')");

    $amount = abs($difference);
    $queryf = dbRead("select * from tbl_members_facility where acc_no = '$memid' and facility_type = 2 having (facility_amount - facility_repay) != 0 order by date");
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

 $SQLQuery = dbRead("select (sum(sell)-sum(buy)) as Total from transactions where memid='$Memid' and dis_date <= '$RunDate'");
 $Result = mysql_fetch_assoc($SQLQuery);

 return $Result;

}
