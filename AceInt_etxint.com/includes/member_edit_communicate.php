<?
  $dbquery = dbRead("select * from standard_letters where CID = ".$_SESSION['User']['CID']." and l_display = 0 order by title","etxint_ebanc_letters");
?>

<table width="620" border="1" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
 <tr>
  <td bgcolor="#FFFFFF" align="left"><br>
   <ul>
     <li><a href="body.php?page=lettercreate&letter=&Action=true&Client=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("1") ?></a></li>
    <?
     if($_SESSION['User']['AreasAllowed'] == 'all')  {
    ?>
 	 <li><a href="body.php?page=lettercreate&letter=11&Action=true&Client=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Uncontactable Member</a></li>
 	 <li><a href="body.php?page=lettercreate&letter=49&Action=true&Client=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">How can we help you</a></li>
    <?
     }
     if(checkmodule("EditMemberLevel2"))  {
       while($row = mysql_fetch_assoc($dbquery)) {?>
 	  <li><a href="body.php?page=lettercreate&letter=<?= $row['letter_no']?>&Action=true&Client=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= $row['title']?></a></li>
    <? }
     }?>
   </ul>
  </td>
 </tr>
</table>