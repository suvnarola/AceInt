<?

if(!checkmodule("ErewardsCheck")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2">You are not allowed to use this function.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

// Start of E Rewards Check Script.

if($_POST[next]) {


 // get referer out.
 $firstquery = dbRead("select referedby from members where memid='$_POST[memid]'");
 $firstrow = mysql_fetch_assoc($firstquery);
 
 $firstone = $firstrow[referedby];
 

 ?>
<table width="639" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="1">
   	<tr>
	 <td colspan="3" align="center" class="Heading2">E Rewards Check.</td>
	</tr>
   	<tr>
	 <td align="left" class="Heading2">Acc No.</td>
	 <td align="left" class="Heading2">COMPANY NAME</td>
	 <td align="right" class="Heading2">AMOUNT</td>
	</tr>
 <?

 // get members details out and display a table row with $40 in it for them.
 
 $Amount = 40;
 
 $query = dbRead("select * from members where memid = '$firstone'");
 $row = mysql_fetch_assoc($query);
 
 ?>
  <tr>
   <td align="left" bgcolor="#FFFFFF"><?= $row[memid] ?></td>
   <td align="left" bgcolor="#FFFFFF"><?= $row[companyname] ?></td>
   <td align="right" bgcolor="#FFFFFF">$40.00</td>
  </tr>
 <?
 
 if($row[referedby]) {
 
  // get this persons details out and display a row.
  
  $Amount += 5;
  
  $query2 = dbRead("select * from members where memid = '$row[referedby]'");
  $row2 = mysql_fetch_assoc($query2);
 
  ?>
   <tr>
    <td align="left" bgcolor="#FFFFFF"><?= $row2[memid] ?></td>
    <td align="left" bgcolor="#FFFFFF"><?= $row2[companyname] ?></td>
    <td align="right" bgcolor="#FFFFFF">$5.00</td>
   </tr>
  <?
  
  if($row2[referedby]) {
  
   // get the next persons out.
   
   $Amount += 10;
   
   $query3 = dbRead("select * from members where memid = '$row2[referedby]'");
   $row3 = mysql_fetch_assoc($query3);
 
   ?>
    <tr>
     <td align="left" bgcolor="#FFFFFF"><?= $row3[memid] ?></td>
     <td align="left" bgcolor="#FFFFFF"><?= $row3[companyname] ?></td>
     <td align="right" bgcolor="#FFFFFF">$10.00</td>
    </tr>
   <?   
   
   if($row3[referedby]) {
    
    // get next persons out.
    
    $Amount += 15;
    
    $query4 = dbRead("select * from members where memid = '$row3[referedby]'");
    $row4 = mysql_fetch_assoc($query4);
 
    ?>
     <tr>
      <td align="left" bgcolor="#FFFFFF"><?= $row4[memid] ?></td>
      <td align="left" bgcolor="#FFFFFF"><?= $row4[companyname] ?></td>
      <td align="right" bgcolor="#FFFFFF">$15.00</td>
     </tr>
    <?

    if($row4[referedby]) {
    
     // get next persons out.
    
     $Amount += 20;
    
     $query5 = dbRead("select * from members where memid = '$row4[referedby]'");
     $row5 = mysql_fetch_assoc($query5);
  
     ?>
      <tr>
       <td align="left" bgcolor="#FFFFFF"><?= $row5[memid] ?></td>
       <td align="left" bgcolor="#FFFFFF"><?= $row5[companyname] ?></td>
       <td align="right" bgcolor="#FFFFFF">$20.00</td>
      </tr>
     <?
       
    }
   
   }
  
  }
  
 }
 ?>
     <tr>
      <td align="right" colspan="3" bgcolor="#FFFFFF">$<?= number_format($Amount,2) ?></td>
     </tr>
   </table>
  </td>
 </tr>
</table>
 <?
die;
}

?>
<html>
<body onload="javascript:setFocus('erewards_check','memid');">

<form method="POST" action="body.php?page=erewards_check" name="erewards_check">

<table width="639" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="Heading2">E Rewards Check.</td>
	</tr>
	<tr>
		<td width="100" align="right" class="Heading2"><b>Account No.:</b></td>
		<td align="left" bgcolor="#FFFFFF"><input type="text" size="10" name="memid" onKeyPress="return number(event)"></td>
	</tr>
	<tr>
		<td class="Heading2"></td>
		<td bgcolor="#FFFFFF"><input type="submit" value="GO" name="erewards_check"></td>
	</tr>
</table>
</td>
</tr>
</table>

<input type="hidden" name="next" value="1">

</form>

</body>
</html>