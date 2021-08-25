<?

if($_REQUEST[next] == 1) {
 
 if(!$_REQUEST[memid]) {
 
  $errormsg = "Membership number must be entered";
  first_form($errormsg);
  
 } else {
  
  $query2 = mysql_db_query($db, "select * from members where memid='$_REQUEST[memid]'", $linkid);
  $row2 = mysql_fetch_array($query2);
  
  if($row2[erewards] == 1) {
   $errormsg = "Member is awaiting Approval.";
  } elseif($row2[erewards] == 2) {
   $errormsg = "Member is awaiting Approval.";
  } elseif($row2[erewards] == 9) {
   $errormsg = "Member is already on E Rewards.";
  } elseif($row2[erewards] == 0) {
   second_form('',$_REQUEST[memid]);
   die;
  }
  
  first_form($errormsg);
  die;
  
 }
 
} elseif($_REQUEST[next] == 2) {

 //check to see if this member is awaiting approval or already signed up.
 
 $query = mysql_db_query($db, "select * from members where memid='$_REQUEST[memid]'", $linkid);
 $row = mysql_fetch_array($query);
 
 if($row[erewards] == 0) {
  
  // we need to check to make sure everything is filled out.
  
  if(!$_REQUEST[memid] || !$_REQUEST[emailaddress] || !$HTTP_POST_VARS[accountno] || !$_REQUEST[expires] || !$_REQUEST[accountname] || !$_REQUEST[referedby]) {
   
   $errormsg = "Some required fields were not completed. Please Check.";
  
  } else {
  
   // all required fields are there. need to do a credit card check.
   
   $creditcheck = credit_card_check($_REQUEST[accountno],$_REQUEST[expires]);
   
   if($creditcheck == 1) {
    
    $errormsg = "Credit Card Has Expired.";
    
   } elseif($creditcheck == 2) {
    
    $errormsg = "Credit Card Is Invalid.";
    
   } elseif($creditcheck == 0) {
     
    // credit check is ok. add this person to the approval.
    
    if($_REQUEST[opt100]) {
     $opt100 = "2";
    } else {
     $opt100 = "1";
    }
    
    if($_REQUEST[abn]) {
     $supply = "3";
    } else {
     $supply = "1";
    }
    
    mysql_db_query($db, "update members set gst='$gst', supply_statement='$supply', erewards='$opt100', emailaddress='$_REQUEST[emailaddress]', reward_bsb='$_REQUEST[reward_bsb]', reward_accno='$_REQUEST[reward_accno]', reward_accname='$_REQUEST[reward_accname]', accountno='$_REQUEST[accountno]', accountname='$_REQUEST[accountname]', expires='$_REQUEST[expires]', referedby='$_REQUEST[referedby]' where memid='$_REQUEST[memid]'", $linkid);
    
    // we need to add this person to the erewards_agent table.
    // if he is there already set his flag to Y so he is eligible for 3 months.
    
    $query3 = mysql_db_query($db, "select count(*) as AgentCount from erewards_agents where agent='$_REQUEST[memid]'", $linkid);
    $row3 = mysql_fetch_array($query3);
    if($row3[AgentCount] == 0) {
     mysql_db_query($db, "insert into erewards_agents (agent,flag) values ('$_REQUEST[memid]','Y')", $linkid);
    } else {
     mysql_db_query($db, "update erewards_agents set flag='Y' where agent='$_REQUEST[memid]'", $linkid);
    }
    
    first_form('');
    die;
   }
  
  }
  
 }

 second_form($errormsg,$_REQUEST[memid]);

} else {

 first_form('');

}

function second_form($errormsg,$memid) {

 global $db, $linkid, $HTTP_POST_VARS;

if(!$errormsg) {
 $query = mysql_db_query($db, "select * from members where memid='$memid'", $linkid);
 $row = mysql_fetch_array($query);
} else {
 $row = $_REQUEST;
}

?>
<form method="POST" action="body.php?page=erewardssignup">
<input type="hidden" name="next" value="2">
<?
 if($errormsg) {
  ?>
  <table width="639" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><?= $errormsg ?>&nbsp;</td>
  </tr>
  </table>
  <br>
  <?
 }
?>
<table border="0" cellspacing="1" cellpadding="1">
 <tr>
  <td class="Border">
   <table border="0" width="495" cellspacing="0" cellpadding="3">
    <tr>
     <td align="center" valign="middle" colspan="2" class="Heading2"><b>Contact Info</b></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b>Account 
     No.:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="hidden" name="memid" size="10" value="<?= $memid ?>"><?= $_REQUEST[memid] ?>&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b>$100 Option:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input <? if($row[opt100]) { echo "checked "; } ?>type="checkbox" name="opt100" value="ON" size="20">(Opt for $100 instead of erewards.)
     </td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>ABN:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="text" name="abn" size="30" value="<?= $row[abn] ?>" onKeyPress="return number(event)"></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="30%"><b>GST Registered:</b></td>
     <td bgcolor="#FFFFFF" width="70%"><select size="1" name="gst">
     <option <? if($row2[gst] == "N") { echo "selected "; }?>value="N">No</option>
     <option <? if($row2[gst] == "Y") { echo "selected "; }?>value="Y">Yes</option>
     </select></td>
    </tr>    
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Email Address:</b></td>
     <td <? if($errormsg && !$_REQUEST[emailaddress]) { echo 'bgcolor="#FF6666"'; } else { echo 'bgcolor="FFFFFF"'; } ?> valign="middle">
     <input type="text" name="emailaddress" size="30" value="<?= $row[emailaddress] ?>"> <font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Bank Account BSB:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="text" name="reward_bsb" size="10" value="<?= $row[reward_bsb] ?>"></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Bank Account Number:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="text" name="reward_accno" size="20" value="<?= $row[reward_accno] ?>"></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Bank Account Name:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="text" name="reward_accname" size="30" value="<?= $row[reward_accname] ?>"></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Credit Card No:</b></td>
     <td <? if($errormsg && !$_REQUEST[accountno]) { echo 'bgcolor="#FF6666"'; } else { echo 'bgcolor="FFFFFF"'; } ?> valign="middle">
     <input type="text" name="accountno" size="20" value="<?= $row[accountno] ?>" onKeyPress="return number(event)"> <font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Credit Card Expiry:</b></td>
     <td <? if($errormsg && !$_REQUEST[expires]) { echo 'bgcolor="#FF6666"'; } else { echo 'bgcolor="FFFFFF"'; } ?> valign="middle">
     <input type="text" name="expires" size="6" value="<?= $row[expires] ?>">&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Credit Card Name:</b></td>
     <td <? if($errormsg && !$_REQUEST[accountname]) { echo 'bgcolor="#FF6666"'; } else { echo 'bgcolor="FFFFFF"'; } ?> valign="middle">
     <input type="text" name="accountname" size="30" value="<?= $row[accountname] ?>"><font size="2" color="#FF0000"><b> *</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2"><b>Referer:</b></td>
     <td <? if($errormsg && !$_REQUEST[referedby]) { echo 'bgcolor="#FF6666"'; } else { echo 'bgcolor="FFFFFF"'; } ?> valign="middle">
     <input type="text" name="referedby" size="10" value="<?= $row[referedby] ?>"><font size="2" color="#FF0000"><b> *</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2">&nbsp;</td>
     <td bgcolor="FFFFFF" valign="middle">
     <button name="submit" type="submit">Submit Application</button></td>
    </tr>
    </table>
  </td>
 </tr>
</table>
</form>
<?
}

function first_form($errormsg) {

?>
<body onload="javascript:setFocus('erewards','memid');">
<form method="POST" action="body.php?page=erewardssignup" name="erewards">
<input type="hidden" name="next" value="1">
<?
 if($errormsg) {
  ?>
  <table width="639" border="2" bordercolor="#FF0000" cellpadding="3" cellspacing="0">
  <tr>
  <td bgcolor="#FFFFFF"><?= $errormsg ?>&nbsp;</td>
  </tr>
  </table>
  <br>
  <?
 }
?>
<table border="0" cellspacing="1" cellpadding="1">
 <tr>
  <td class="Border">
   <table border="0" width="495" cellspacing="0" cellpadding="3">
    <tr>
     <td align="center" valign="middle" colspan="2" class="Heading2"><b>Contact Info</b></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2" width="170"><b>Account 
     No.:</b></td>
     <td bgcolor="FFFFFF" valign="middle">
     <input type="text" name="memid" size="10" value="<?= $_REQUEST[memid] ?>">&nbsp;<font size="2" color="#FF0000"><b>*</b></font></td>
    </tr>
    <tr>
     <td align="right" valign="middle" class="Heading2">&nbsp;</td>
     <td bgcolor="FFFFFF" valign="middle">
     <button name="submit" type="submit">Next Step</button></td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</body>
<?

}

?>