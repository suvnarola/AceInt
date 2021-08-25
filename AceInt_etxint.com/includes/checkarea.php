<?

if(!checkmodule("SuperUser")) {

#check details using $memid.
$query = dbRead("select licensee, CID from members where memid='$memid'");
$row99 = mysql_fetch_assoc($query);

#set somthing to false.
$allowed = false;

$adminuserarray = explode(",", $_SESSION['User']['AreasAllowed']);

 $count = sizeof($adminuserarray);
 $i = 0;
 for ($i = 0; $i <= $count; $i++) {
  
  if(($adminuserarray[$i] == $row99['licensee']) || (($adminuserarray[$i] == "all") && ($_SESSION['User']['CID'] == $row99['CID']))) { 
   $allowed = true;
   break;
  }

 }
  
 if($row['status'] != 1 || $row['status'] != 5) { 
  if(checkmodule("EditMemberLevel2")) {

   $allowed = true;

  }
 }

if($allowed == false) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?></td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}


}

?>