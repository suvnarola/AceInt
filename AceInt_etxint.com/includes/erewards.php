<?

$db_date = date("Y-m-d");

function add_referal($referer_memid,$member_memid) {

 global $db, $linkid, $db_date;

 // check to see if the referer has an entry in the agents table. if not then add one.
 $query = mysql_db_query($db, "select count(*) as AgentCount from `erewards_agents` where agent='$referer_memid'", $linkid);
 $row = mysql_fetch_array($query);
 if($row[AgentCount] == 0) {
  mysql_db_query($db, "insert into `erewards_agents` (agent) values ('$referer_memid')", $linkid);
 }

 // do the transfers.
 // first level.
 // easy cause we just put the money in the referers agent account.
 
 mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$referer_memid','$member_memid','1','0','40','10')", $linkid);
 mysql_db_query($db, "update `erewards_agents` set referals=referals+1 where agent='$referer_memid'", $linkid);

 // second level.
 // we need to check if the first referer was refered. if he was then we need to put an entry in his reward account.
 
 $query2 = mysql_db_query($db, "select memid, referedby from members where memid='$referer_memid'", $linkid);
 $row2 = mysql_fetch_array($query2);
 if($row2[referedby]) {
  
  // this means that the first referer had someone refer him.
  // we need to add an entry for him to get his second level fees.
  
  mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row2[referedby]','$member_memid','1','0','30','20')", $linkid);

  // third level.
  // now whilst in here we need to check to see if this referer had anyone refer him.
  
  $query3 = mysql_db_query($db, "select memid, referedby from members where memid='$row2[referedby]'", $linkid);
  $row3 = mysql_fetch_array($query3);
  if($row3[referedby]) {
  
   // this means that the second referer had someone refer him.
   // we need to add an entry for him aswell.
   
   mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row3[referedby]','$member_memid','1','0','20','30')", $linkid);
   
   // forth level.
   // now whilst in here we need to check to see if this referer had anyone refer him.
   
   $query4 = mysql_db_query($db, "select memid, referedby from members where memid='$row3[referedby]'", $linkid);
   $row4 = mysql_fetch_array($query4);
   if($row4[referedby]) {
   
    // this means the third referer had someone refer him.
    // we need to add an entry for him.
    
    mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row4[referedby]','$member_memid','1','0','10','40')", $linkid);
   
    // fifth and last level.
    // now whilst in here we need to check to see if this referer had anyone refer him.
    
    $query5 = mysql_db_query($db, "select memid, referedby from members where memid='$row4[referedby]'", $linkid);
    $row5 = mysql_fetch_array($query5);
    if($row5[referedby]) {
     
     // this means the fourth referer had someone refer him.
     // we need to add an entry for him.
     
     mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row5[referedby]','$member_memid','1','0','0','50')", $linkid);
     
    }
   
   }
   
  }
  
 }
 
 // if not then do nothing else.
 
}

function add_cash_fees($cash_memid,$cash_amount) {

 global $db, $linkid, $db_date;

 // we need to check to see if this persons referer has had more than 10 signups and if they even have a referer.
 
 $query = mysql_db_query($db, "select memid, referedby from members where memid='$cash_memid'", $linkid);
 $row = mysql_fetch_array($query);
 if($row[referedby]) {
 
  // this person has a referer. we need to check to see if they have more than 10 signups.
  // if not then we need to stop and not keep going through the loop.
  
  $query2 = mysql_db_query($db, "select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row[referedby]'", $linkid);
  $row2 = mysql_fetch_array($query2);
  if($row2[referals] >= 10) {
  
   // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
   // first level - 50% of the agents percentage.
   
   $reward_total_amount = ($cash_amount/100)*$row2[percent];
   $reward_net_amount = ($reward_total_amount/100)*50;
   $reward_actual_amount = $reward_net_amount/2;
   
   mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row2[agent]','$cash_memid','2','50','$reward_actual_amount','$reward_actual_amount')", $linkid);
  
   // now whilst we are in here we need to check to see if the current referer has a referer.
   // if there is then go on to check the referals.
   
   $query3 = mysql_db_query($db, "select memid, referedby from members where memid='$row[referedby]'", $linkid);
   $row3 = mysql_fetch_array($query3);
   if($row3[referedby]) {
  
    // this person has a referer. we need to check to see if they have more than 10 signups.
    // if not then we need to stop and not keep going through the loop.
  
    $query4 = mysql_db_query($db, "select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row3[referedby]'", $linkid);
    $row4 = mysql_fetch_array($query4);
    if($row4[referals] >= 10) {
  
     // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
     // second level - 30% of the agents percentage.
   
     $reward_total_amount = ($cash_amount/100)*$row4[percent];
     $reward_net_amount = ($reward_total_amount/100)*30;
     $reward_actual_amount = $reward_net_amount/2;
   
     mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row4[agent]','$cash_memid','2','30','$reward_actual_amount','$reward_actual_amount')", $linkid);
  
     // now whilst we are in here we need to check to see if the current referer has a referer.
     // if there is then go on to check the referals.
     
     $query5 = mysql_db_query($db, "select memid, referedby from members where memid='$row3[referedby]'", $linkid);
     $row5 = mysql_fetch_array($query5);
     if($row5[referedby]) {
  
      // this person has a referer. we need to check to see if they have more than 10 signups.
      // if not then we need to stop and not keep going through the loop.
  
      $query6 = mysql_db_query($db, "select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row5[referedby]'", $linkid);
      $row6 = mysql_fetch_array($query6);
      if($row6[referals] >= 10) {
  
       // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
       // first level - 50% of the agents percentage.
   
       $reward_total_amount = ($cash_amount/100)*$row6[percent];
       $reward_net_amount = ($reward_total_amount/100)*15;
       $reward_actual_amount = $reward_net_amount/2;
   
       mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row6[agent]','$cash_memid','2','15','$reward_actual_amount','$reward_actual_amount')", $linkid);
  
       // now whilst we are in here we need to check to see if the current referer has a referer.
       // if there is then go on to check the referals.
       
       $query7 = mysql_db_query($db, "select memid, referedby from members where memid='$row5[referedby]'", $linkid);
       $row7 = mysql_fetch_array($query7);
       if($row3[referedby]) {
  
        // this person has a referer. we need to check to see if they have more than 10 signups.
        // if not then we need to stop and not keep going through the loop.
  
        $query8 = mysql_db_query($db, "select sum(referals) as referals, count(*) as count, sum(percent) as percent, sum(agent) as agent from `erewards_agents` where agent='$row7[referedby]'", $linkid);
        $row8 = mysql_fetch_array($query8);
        if($row8[referals] >= 10) {
  
         // this referer has 10 referals. we need to insert a record in the table to reflect their percentage.
         // first level - 50% of the agents percentage.
   
         $reward_total_amount = ($cash_amount/100)*$row8[percent];
         $reward_net_amount = ($reward_total_amount/100)*5;
         $reward_actual_amount = $reward_net_amount/2;
   
         mysql_db_query($db, "insert into erewards (date,agent,memid,type,percent,amount_cash,amount_trade) values ('$db_date','$row8[agent]','$cash_memid','2','5','$reward_actual_amount','$reward_actual_amount')", $linkid);
  
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

?>