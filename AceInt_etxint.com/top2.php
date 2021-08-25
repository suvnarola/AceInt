<?

 include("includes/global.php");

?>
<html>
<head>
<title>VSM Header</title>
<BASE TARGET="main">
<style>
td { font-family: Tahoma; font-size: 8pt; color: #777777 }
a:link, a:visited { text-decoration: none; font-family: Verdana; color: #333333 }
a:hover { text-decoration: underline; font-family: Verdana; color: #333333 }

</style>
</head>
<body topmargin = "0" leftmargin="0">

<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td width="458" background="images/admin_site_3_02.gif"><a href="body.php?page=mem_search" alt="E Banc Administration">
    <img src="images/admin_site_3_0111.gif" border="0" width="458" height="79"></a></td>
    <td width="100%" background="images/admin_site_3_02.gif" valign="top" style="padding-top: 18px" COLSPAN="2">
     <table cellspacing="5" cellpadding="0">
      <tr>
       <td valign="top">
<?
	  //$date =  date("Y-m-d", mktime()+ $_SESSION['Country']['timezone']);
	  $date =  date("Y-m-d");
	  $query = dbRead("select Count(members.memid) AS CountOfmemid, tbl_admin_users.Name from members, area, tbl_admin_users where members.licensee = area.FieldID and area.user = tbl_admin_users.FieldID and members.date_per = '".$date."' group by tbl_admin_users.Name");
	  $count =0;

	  if($_SESSION['User']['FieldID'] == 289) {

	  	while($row = mysql_fetch_assoc($query)) {
	  	  $count++;
	  	  if($count > 3) {
	  	  ?>
	  	    </td>
       		<td valign="top">
       	  <?
	  	  $count = 1;
	  	  }
	  	?>
	  	  <?= get_all_added_characters($row['Name']) ?> - <?= get_all_added_characters($row['CountOfmemid']) ?><br>
	  	<?
		}

	  } else {

?>
        <a href="body.php?page=mem_search">Home</a><br>
        <!-- <a href="/bb2">Agents Forum</a><br> -->
        <? if(checkmodule("SuperUsers")) { ?><a href="/temp/bbimport.php">Update PhpBB Users</a><? } ?><br>
        <? if(checkmodule("SuperUser")) { ?><a href="body.php?page=update_lang&tab=Lang Update">Lang Update</a><? } ?><br>
        <? if(checkmodule("SuperUser")) { ?><a href="body.php?page=update_warehouse&tab=Warehouse Update">Update Ware</a><? } ?><br>
       </td>
       <td valign="top">
        <? if(checkmodule("SuperUserw")) { ?><a href="includes/procedures.php?list=1">Procedures</a><br><? } ?>
        <? if(checkmodule("SuperUser")) { ?><a href="body.php?page=update_proc&tab=Add Proc">Proc Update</a><br><? } ?>
        <? if(checkmodule("SuperUser") || checkmodule("MemberUpdate")) { ?><a href="body.php?page=update_member&tab=tab1">Member Update</a><? } ?><br>
        <? if(checkmodule("SuperUser") || checkmodule("CorpUpdate")) { ?><a href="body.php?page=update_corp&tab=tab1">Corp Update</a><? } ?><br>
        <? if(checkmodule("SuperUser") || checkmodule("AdminUpdate")) { ?><a href="body.php?page=update_admin&tab=Data Update">Admin Update</a><? } ?>
        <? if(checkmodule("SuperUsers")) { ?><a href="body.php?page=update_aa&view=ppp Update">Ph Area</a><? } ?>
       </td>
       <td valign="top">
        <? if(checkmodule("SuperUser") || checkmodule("CorpUpdate")) { ?><a href="body.php?page=update_area&tab=tab1">Area Admin</a><? } ?><br>
        <? if(checkmodule("SuperUser")) { ?><a href="body.php?page=letter_admin&tab=Edit Letter">Letter Admin</a><? } ?><br>
        <? if(checkmodule("SuperUser")) { ?><a href="body.php?page=update_country&tab=tab1">Country Admin</a><? } ?><br>
        <? if(checkmodule("SuperUser")) { ?><a href="body.php?page=update_countrydata&tab=Country Update">Countrydata Admin</a><? } ?><br>
 	  <?}?>
       </td>
      </tr>
     </table>
    </td>
  </tr>
</table>

</body>

</html>
