<?

 /** 
  * E Banc Trade E Rewards - Monthly Script
  *
  * ERewardsMonthly.php
  * Version 0.01
  */

 $NoSession = true;

 include("/home/etxint/admin.etxint.com/includes/global.php");
 include("class.html.mime.mail.inc");
 
 include("taxinvoiceemail.php");
 include("taxinvoiceemailall.php");
 include("taxinvoiceemailallnoemail.php");

 $Debug = $_REQUEST['Debug'];
 
 if($_GET[Debug]) {
  echo "<pre><br>E Rewards Script - Debug Mode\r\n\r\n";
 }
  
 $CountrySQL = @dbRead("select * from country where countryID='1'");
 while($Country = @mysql_fetch_assoc($CountrySQL)) {

  $lastmonthdate = date("Y-m", mktime(1,1,1,date("m")-1,date("d"),date("Y")));
  $lastmonthdate2 = date("Y-m", mktime(1,1,1,date("m")-2,date("d"),date("Y")));
  $lastmonthdate3 = date("Y-m", mktime(1,1,1,date("m")-3,date("d"),date("Y")));
  $lastmonthdatetrans = date("Y-m-d", mktime(1,1,1,date("m"),1-1,date("Y")));
  $lastmonthdatetrans2 = date("Y-m-d", mktime(1,1,1,date("m")-1,1-1,date("Y")));

  $query = dbRead("SELECT members.licensee as licensee, members.reward_bsb, erewards_agents.flag as flag, erewards_agents.agent as agent, erewards_agents.referals as referals ,members.emailaddress as emailaddress, members.companyname as companyname, members.contactname as contactname, members.memid as members_memid, members.reward_accno as reward_accno, members.letters as letters, erewards.agent as Nothing FROM members,erewards_agents, erewards WHERE ((erewards_agents.agent = erewards.agent) and (erewards.date like '$lastmonthdate-%') and (members.letters = 0) and (members.erewards = '9') AND (members.memid = erewards_agents.agent) AND (members.supply_statement != '1') AND (erewards_agents.referals > 0)) GROUP BY erewards.agent ORDER BY erewards.agent ASC");
  while($row = mysql_fetch_assoc($query)) {
   
   if($Debug) {
    echo "Member: $row[agent] [".$row[companyname]."]<br>";
   }

   $MemberCheck = CheckFees($row);
   
   if($Debug) {
    echo "MemberCheck: $MemberCheck\r\n";
   }
  
   if($MemberCheck == "Yes") { 
  
    $query2 = dbRead("SELECT erewards.memid ,sum(amount_cash) as SumCash ,sum(amount_trade) as SumTrade FROM erewards, members WHERE (members.memid = erewards.memid) and members.reward_check = 1 and erewards.Paid = 0 and members.reward_datejoined > '".date("Y-m-d", mktime(1,1,1,date("m")-6,1,date("Y")))."' and date > '".date("Y-m-d", mktime(1,1,1,date("m")-6,1,date("Y")))."' and agent = $row[agent] group by agent");
    $row2 = mysql_fetch_assoc($query2);
  
    if(($row2[SumTrade]+$row2[SumCash]) > 0) {
     
     // Update E Rewards Table for this agent and set his transactions to Paid.
     if(!$Debug) {
      UpdatePaid($row[agent]);
     }
     
     if($Debug) {
      echo "Sum Trade: $row2[SumTrade]\r\n";
     }
  
     if($Debug) {
      echo "&nbsp;Insert into erewards_bank Type: 1, Date: $lastmonthdatetrans, Cash: $row2[SumCash], Trade: $row2[SumTrade]<br>";
     } else {
      //dbWrite("insert into erewards_bank (type,memid,date,amount_cash,amount_trade) values ('1','$row[agent]','$lastmonthdatetrans','$row2[SumCash]','$row2[SumTrade]')");
     }
  
     $query3 = dbRead("select memid, sum(dollarfees) as feesowe from transactions where memid='$row[agent]' group by memid");
     $row3 = mysql_fetch_assoc($query3);
  
     // add a trade transaction.

     $trans_details = "E Rewards";
  
     if($Debug) {
      echo "&nbsp;Adding Transaction to Member Account for Trade: $row2[SumTrade]<br>";
     } else {
      //add_transaction2($Country[erewardsacc],$row[agent],$lastmonthdatetrans,$row2[SumTrade],$trans_details);
     }
  
     if(($row3[feesowe] > $row2[SumCash]) || !($row[reward_bsb] || $row[reward_accno])) {

      if($Debug) {
       echo "&nbsp;Adding Fees Transaction for Cash: $row2[SumCash]<br>";
      } else {
       //tbl_feespaid2($row2[SumCash],$row[agent],$row[licensee]);
       //add_fees2($row[agent],$lastmonthdatetrans,$row2[SumCash],$trans_details); 
      }

      if($Debug) {
       echo "&nbsp;Insert into erewards_bank Type: 2, Date: $lastmonthdatetrans, Cash: $row2[SumCash]<br>";
      } else {
       //dbWrite("insert into erewards_bank (type,memid,date,amount_cash) values ('2','$row[agent]','$lastmonthdatetrans','$row2[SumCash]')");
      }
    
     } else {

      if($row3[feesowe] <= 0) {
    
       if($Debug) {
        echo "&nbsp;Insert into erewards_bank Type: 0, Date: $lastmonthdatetrans, Cash: $row2[SumCash]<br>";
       } else {
        //dbWrite("insert into erewards_bank (type,memid,date,amount_cash) values ('0','$row[agent]','$lastmonthdatetrans','$row2[SumCash]')");
       }

      } else {
    
       // add a cash fee payment for the amount of cash fees.
       // add an entry to lyns table type 0 of the difference of cash and fees
       // add an entry to lyns table type 2 of the cash fees we paid to the member.
    
       if($Debug) { 

        $otheramount = $row2[SumCash]-$row3[feesowe];
        echo "&nbsp;Insert into erewards_bank Type: 0 (2), Date: $lastmonthdatetrans, Cash: $otheramount<br>";
        echo "&nbsp;Insert into erewards_bank Type: 2 (2), Date: $lastmonthdatetrans, Cash: $row3[feesowe]<br>";
        echo "&nbsp;Adding Fees Transaction for Cash (2): $row3[feesowe]<br>";

       } else {
    
        $otheramount = $row2[SumCash]-$row3[feesowe];
        //tbl_feespaid2($row3[feesowe],$row[agent],$row[licensee]);
        //add_fees2($row[agent],$lastmonthdatetrans,$row3[feesowe],$trans_details);
        //dbWrite("insert into erewards_bank (type,memid,date,amount_cash) values ('0','$row[agent]','$lastmonthdatetrans','$otheramount')");
        //dbWrite("insert into erewards_bank (type,memid,date,amount_cash) values ('2','$row[agent]','$lastmonthdatetrans','$row3[feesowe]')");
   
       }
   
      }
   
     }
    
     $email_lyn = true;
     $amount = $row2[SumCash]+$row2[SumTrade];
  
     $i = $lastmonthdatetrans.$row[agent];
     $buffer = taxinvoice($row[agent],$lastmonthdatetrans,$amount,$i);
     $i++;
 
     if($row[emailaddress]) {
      if(!$Debug) {
  
       $text = "Dear $row[contactname],\n\nAttached is your current E Rewards Tax Invoive. This needs to be entered into your accounts. The Trade rewards have been entered into your account and cash reward has been deducted from your fees, If you have signed up 10 or more members the remainder if any will be deposited into your nominated bank account on the 15th.\n\nRegards\n\nE Banc Trade Rewards\n\nYou may need Adobe Acrobat to read this Tax Invoice.\nTo get it go here http://www.adobe.com/\n\nRegards\n\nE Banc Trade Accounts";
       define('CRLF', "\r\n", TRUE);
       $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
       $mail->add_text($text);
       $mail->add_attachment($buffer, 'TaxInvoice.pdf', 'application/pdf');
       $mail->build_message();
       $mail->send($row[Companyname], $row[emailaddress], 'E Banc Accounts', 'accounts@ebanctrade.com', 'Tax Invoice - '.$row[companyname]);  
  
      }
     
     }
    
    } else {
    
      // No money to get. Dont do anything.
     
      echo "&nbsp;There isn't any money to give :)";
   
    }
   
   } else {
   
    if($Debug) {
     echo "&nbsp;E Rewards will not make fees come under $200.<br><br>";
    }
   
   }
  
   print "\r\n\r\n";
  
  }

 }

 if($email_lyn == true) {
  if(!$Debug) {
   mail("lyn@ebanctrade.com", "New E Rewards Data", "Hi Lyn, There is new E Rewards Data in the admin site.", "From: E Rewards System <erewards@ebanctrade.com>\r\n");
  } else {
   echo "&nbsp;Emailed Lyn to tell her there is Data there.";
  }
 }

 // Do the countries tax invoices.

 if(!$Debug) {
  $cquery = dbRead("select * from country where Display = 'Yes' and countryID = 1");
  while($crow = mysql_fetch_assoc($cquery)) {
   $buffer = taxinvoiceall($lastmonthdate,$crow[countryID]);
    if(strlen($buffer) > 100) {
     $text = "Dear $crow[name],\n\nAttached is your current E Rewards Tax Invoives.";
     define('CRLF', "\r\n", TRUE);
     $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
     $mail->add_text($text);
     $mail->add_attachment($buffer, 'TaxInvoice.pdf', 'application/pdf');
     $mail->build_message();
     $mail->send('Lyn', 'lyn@ebanctrade.com', 'E Banc Accounts', 'accounts@ebanctrade.com', 'E Rewards Tax Invoices');  
    }
  }
 }
 
 // people with no email address get sent to jackie

 $cquery = dbRead("select * from country where Display = 'Yes' and countryID = 1");
 while($crow = mysql_fetch_assoc($cquery)) {
  $buffer = taxinvoiceallnoemail($lastmonthdate,$crow[countryID]);
   if(strlen($buffer) > 100) {
    $text = "Dear $crow[name],\n\nAttached is your current E Rewards Tax Invoives.";
    define('CRLF', "\r\n", TRUE);
    $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));
    $mail->add_text($text);
    $mail->add_attachment($buffer, 'TaxInvoice.pdf', 'application/pdf');
    $mail->build_message();
    $mail->send('Jackie', 'jackie@ebanctrade.com', 'E Banc Accounts', 'accounts@ebanctrade.com', 'E Rewards Tax Invoices');  
   }
 }

 // daves payment stuff

 include("erewardspayments.php");

 /**
  * Functions
  */
 
 function UpdatePaid($Agent) {
  
  $SQL = dbRead("select erewards.* from erewards, members where (members.memid = erewards.memid) and erewards.agent = ".$Agent." and members.reward_check = 1");
  while($Row = mysql_fetch_assoc($SQL)) {
   
   dbWrite("update erewards set Paid = 1 where FieldID = " . $Row['FieldID']);
   
  }
 
 }
 
 function CheckFees($row) {
  
  global $lastmonthdatetrans2, $lastmonthdate, $other;
    
  $checkquery1 = dbRead("select sum(currentpaid+overduefees) as Check1 from invoice where memid = '$row[agent]' and date = '$lastmonthdatetrans2'");
  $checkrow1 = @mysql_fetch_assoc($checkquery1);
 
  $checkquery2 = dbRead("SELECT memid ,sum(amount_cash) as SumCash ,sum(amount_trade) as SumTrade FROM erewards WHERE ((agent = $row[agent]) AND (date like '$lastmonthdate-%')) group by agent");
  $checkrow2 = mysql_fetch_assoc($checkquery2);
  
  $FeesCheck = $checkrow1[Check1] - $checkrow2[SumCash];
   
  if($FeesCheck < 200) {
   return "Yes";
  } else {
   return "No";
  }
 }
 
 function add_cash_fees2($cash_memid,$cash_amount) {

  global $db_date;

  // we need to check to see if this persons referer has had more than 10 signups and if they even have a referer.
 
  $query = dbRead("select memid, referedby from members where memid='$cash_memid'");
  $row = mysql_fetch_assoc($query);
  if($row[referedby]) {
 
   // this person has a referer. we need to check to see if they have more than 10 signups.
   // if not then we need to stop and not keep going through the loop.
  
   $query2 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row[referedby]'");
   $row2 = mysql_fetch_assoc($query2);
   if($row2[referals] >= 10) {
  
    // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
    // first level - 50% of the agents percentage.
   
    $reward_total_amount = ($cash_amount/100)*$row2[percent];
    $reward_net_amount = ($reward_total_amount/100)*50;
    $reward_actual_amount = $reward_net_amount/2;
   
    dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row2[agent]','$cash_memid','2','50','$reward_actual_amount','$reward_actual_amount')");
  
    // now whilst we are in here we need to check to see if the current referer has a referer.
    // if there is then go on to check the referals.
   
    $query3 = dbRead("select memid, referedby from members where memid='$row[referedby]'");
    $row3 = mysql_fetch_assoc($query3);
    if($row3[referedby]) {
  
     // this person has a referer. we need to check to see if they have more than 10 signups.
     // if not then we need to stop and not keep going through the loop.
  
     $query4 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row3[referedby]'");
     $row4 = mysql_fetch_assoc($query4);
     if($row4[referals] >= 10) {
  
      // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
      // second level - 30% of the agents percentage.
   
      $reward_total_amount = ($cash_amount/100)*$row4[percent];
      $reward_net_amount = ($reward_total_amount/100)*30;
      $reward_actual_amount = $reward_net_amount/2;
   
      dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row4[agent]','$cash_memid','2','30','$reward_actual_amount','$reward_actual_amount')");
  
      // now whilst we are in here we need to check to see if the current referer has a referer.
      // if there is then go on to check the referals.
     
      $query5 = dbRead("select memid, referedby from members where memid='$row3[referedby]'");
      $row5 = mysql_fetch_assoc($query5);
      if($row5[referedby]) {
  
       // this person has a referer. we need to check to see if they have more than 10 signups.
       // if not then we need to stop and not keep going through the loop.
  
       $query6 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row5[referedby]'");
       $row6 = mysql_fetch_assoc($query6);
       if($row6[referals] >= 10) {
  
        // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
        // first level - 50% of the agents percentage.
   
        $reward_total_amount = ($cash_amount/100)*$row6[percent];
        $reward_net_amount = ($reward_total_amount/100)*15;
        $reward_actual_amount = $reward_net_amount/2;
   
        dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row6[agent]','$cash_memid','2','15','$reward_actual_amount','$reward_actual_amount')");
  
        // now whilst we are in here we need to check to see if the current referer has a referer.
        // if there is then go on to check the referals.
       
        $query7 = dbRead("select memid, referedby from members where memid='$row5[referedby]'");
        $row7 = mysql_fetch_assoc($query7);
        if($row3[referedby]) {
  
         // this person has a referer. we need to check to see if they have more than 10 signups.
         // if not then we need to stop and not keep going through the loop.
  
         $query8 = dbRead("select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row7[referedby]'");
         $row8 = mysql_fetch_assoc($query8);
         if($row8[referals] >= 10) {
  
          // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
          // first level - 50% of the agents percentage.
   
          $reward_total_amount = ($cash_amount/100)*$row8[percent];
          $reward_net_amount = ($reward_total_amount/100)*5;
          $reward_actual_amount = $reward_net_amount/2;
   
          dbWrite("insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row8[agent]','$cash_memid','2','5','$reward_actual_amount','$reward_actual_amount')");
  
          // we are at the end of the run.
          // close it up.
         
         }
       
        }
      
       }
     
      }
  
     }
  
    }
  
   }
 
  }

  // if not do nothing else.

 }
 
 function add_transaction2($memid_from,$memid_to,$date,$amount,$details) {

  $t=mktime();
  $t2=$t-951500000;
  $t3=mt_rand(1000,9000);
  $authno=$t2-$t3;
 
  $disdate = date("Y-m-d");
  $epoch = mktime();

  dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid_from','$epoch','$memid_to','$amount','0','0','0.00','1','".addslashes(encode_text2($details))."','$authno','$disdate','0','180')");
  dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid) values ('$memid_to','$epoch','$memid_from','0','$amount','0','0.00','2','".addslashes(encode_text2($details))."','$authno','$disdate','0','180')");

 }

 function add_fees2($memid_to,$date,$amount,$details) {

  $t=mktime();
  $t2=$t-951500000;
  $t3=mt_rand(1000,9000);
  $authno=$t2-$t3;
 
  $disdate = date("Y-m-d");
  $epoch = mktime();

  dbWrite("insert into transactions (memid,date,to_memid,buy,sell,tradefees,dollarfees,type,details,authno,dis_date,checked,userid)values('$memid_to','$epoch','9845','0','0','0','-$amount','8','".addslashes(encode_text2($details))."','$authno','$disdate','0','180')");

 }

 function tbl_feespaid2($amount,$memberacc,$licensee) {

  global $REMOTE_USER;

  $authno = mt_rand(1000000,99999999);
  $t = mktime();
  $d = date("Y-m-d");

  // erewards.
  add_cash_fees2($memberacc,$amount);

  // if gold card insert trade the same as the value of the cash fees paid
  $dbcheckmember = dbRead("select goldcard from members where memid='$memberacc'");
  list($goldcard) = mysql_fetch_row($dbcheckmember);
 
  if($goldcard == "1") {
   dbWrite("insert into transactions values('10655','$t','$memberacc','$amount','0','0','0','4','Goldcard Rewards','$authno','$d','0','','180')");
   dbWrite("insert into transactions values('$memberacc','$t','10655','0','$amount','0','0','4','Goldcard Rewards','$authno','$d','0','','180')");
  }

  // check to see if there are any stationery fees owed
  $dbcheckstationery = dbRead("select numfeesowing from feesowing where memid='$memberacc'");
  list($numfeesowing) = mysql_fetch_row($dbcheckstationery);

  // if they do take the number off the feesowing table to 
  if($numfeesowing != "0") {

   $pretest1 = $amount/5.5;
   $test1 = floor($pretest1);
   $final = $numfeesowing-$test1;

   if($final <= 0) {
    $final = 0;
   }

   dbWrite("update feesowing set numfeesowing='$final' where memid='$memberacc'");
   $numfeespaid = $numfeesowing-$final;

   if($numfeespaid <= 0) { 
     $numfeespaid = 0; 
   }

   dbWrite("insert into feespaid values ('$memberacc','$d','$amount','$numfeespaid','70','$licensee','1','')");

  } else {

   dbWrite("insert into feespaid values ('$memberacc','$d','$amount','0','70','$licensee','1','')");

  }

 } 
 
?>