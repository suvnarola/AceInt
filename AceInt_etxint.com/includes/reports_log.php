<?

if(!checkmodule("LogReport")) {



?>



<table width="601" border="0" cellpadding="1" cellspacing="0">

 <tr>

  <td class="Border">

   <table width="100%" border="0" cellpadding="3" cellspacing="0">

    <tr>

     <td width="100%" align="center" class="Heading2">You arn't allowed to use this function.</td>

    </tr>

   </table>

  </td>

 </tr>

</table>



<?

die;

}

?>



<form method="POST" action="body.php?page=reports_log&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frmletters">



<?



// Some Setup.



$time_start = getmicrotime();

$tabarray = array('User Log','Web Log');



// Do Tabs if we need to.



 tabs($tabarray);



if($_GET[tab] == "User Log") {

 user();



} elseif($_GET[tab] == "Web Log") {

 web();



}



?>

</form>





<?



function user() {



if(!$_POST[next] && !$_REQUEST[details]) {



?>

<html>



<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">

<meta name="GENERATOR" content="Microsoft FrontPage 6.0">

<meta name="ProgId" content="FrontPage.Editor.Document">

</head>



<body>



<form method="POST" action="body.php?page=reports_log">



<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">

  <tr>

    <td width="100%" class="Border">

    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">

      <tr>

        <td class="Heading" align="center" colspan="2">Log Reports</td>

      </tr>

     <tr>

      <td align="right" valign="middle" class="Heading2"><b><?= get_word("25") ?>:</b></td>

      <td bgcolor="FFFFFF"><select size="1" name="area">

      <option value="all">All Staff</option>

       <?



		$query1 = dbRead("select FieldID, place from area where CID like '".$_SESSION['User']['CID']."' order by place ASC");

		while($row2 = mysql_fetch_assoc($query1)) {

		 ?>

		  <option value="<?= $row2['FieldID'] ?>"><?= $row2['place'] ?></option>

		 <?

		}



	   ?>

	   </select>

	  </td>

     </tr>

      <tr>

        <td class="Heading2" align="right">Month:</td>

        <td bgcolor="#FFFFFF"><select name="month">

         <?



			$startmonth = "10";

			$startyear = "2002";

			$foo = 0;



			while($current == false) {



			 $dis_date = date("Y-m", mktime(1,1,1,$startmonth+$foo,1,$startyear));

			 $dis_date2 = date("M, Y", mktime(1,1,1,$startmonth+$foo,1,$startyear));

			 $checkdate = date("Y-m");

			 if($dis_date == $checkdate) { $current = 1; }





			 ?>

			  <option value="<?= $dis_date ?>"<? if($current == true) { echo " selected"; } ?>><?= $dis_date2 ?></option>

			 <?



			 $foo++;

			}



         ?>

        </select></td>

      </tr>

      <tr>

        <td class="Heading2">&nbsp;</td>

        <td bgcolor="#FFFFFF" align="right"><input type="submit" name="Submit" value="Get Report"></td>

      </tr>

    </table>

    </td>

  </tr>

</table>



<input type="hidden" name="next" value="1">



</form>



</body>



</html>

<?



} elseif($_REQUEST['details']) {

?>

<form method="POST" action="<?= $PHP_SELF ?>">



<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" id="AutoNumber1" width="640">

  <tr>

    <td width="100%" class="Border">

    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2" width="100%">

      <tr>

        <td colspan="4" align="center" class="Heading">Log Report Detail - <?= $_GET[month] ?> [<?= $_GET[username] ?>]</td>

      </tr>

      <tr>

        <td width="65" valign="top" class="Heading2">Date:</td>

        <td width="90" valign="top" class="Heading2">IP Address:</td>

        <td width="130" valign="top" class="Heading2">Type:</td>

        <td valign="top" class="Heading2">Data:</td>

      </tr>

      <?



       $foo = 0;



       //$query = dbRead("select tbl_kpi.*, tbl_kpi_changes.*, tbl_kpi_login_history.*, tbl_kpi_type.*, tbl_kpi.Date from tbl_kpi, tbl_kpi_type left outer join tbl_kpi_changes on (tbl_kpi_changes.KpiID = tbl_kpi.FieldID) left outer join tbl_kpi_login_history on (tbl_kpi.LoginID = tbl_kpi_login_history.FieldID) where (tbl_kpi.Type = tbl_kpi_type.FieldID) and tbl_kpi.Date like '".$_REQUEST['month']."-%' and tbl_kpi.UserID = '".$_REQUEST['username']."' order by tbl_kpi.FieldID DESC","log");

       $query = dbRead("

		select tbl_kpi.*, tbl_kpi_changes.*, tbl_kpi_login_history.*, tbl_kpi_type.*, tbl_kpi.Date



		from tbl_kpi

			inner

				join

					tbl_kpi_type

					on tbl_kpi.Type = tbl_kpi_type.FieldID

			left outer join tbl_kpi_changes on (tbl_kpi_changes.KpiID = tbl_kpi.FieldID)

			left outer join tbl_kpi_login_history on (tbl_kpi.LoginID = tbl_kpi_login_history.FieldID)



		where tbl_kpi.Date like '".$_REQUEST['month']."-%' and tbl_kpi.UserID = '".$_REQUEST['username']."'



		order by tbl_kpi.FieldID DESC","etxint_log");



       while($row = mysql_fetch_assoc($query)) {



        $cfgbgcolorone="#CCCCCC";

        $cfgbgcolortwo="#EEEEEE";

        $bgcolor=$cfgbgcolorone;

        $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;



        ?>

      <tr bgcolor="<?= $bgcolor ?>">

        <td width="65" valign="top"><?= $row[Date] ?></td>

        <td width="90" valign="top"><?= long2ip($row['IPAddress']) ?></td>

        <td width="130" valign="top"><?= $row[Type] ?></td>

        <td valign="top"><? if($row['Memid']) print "<b>Memid:</b> ".$row['Memid']."<br><br>"; ?><?= display_log_array($row['Data']) ?></td>

      </tr>

        <?



        $foo++;



       }

      ?>

    </table>

    </td>

  </tr>

</table>



</form>



</body>



</html>

<?

} else {

?>



<html>



<head>

<meta http-equiv="Content-Language" content="en-au">

<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">

<meta name="GENERATOR" content="Microsoft FrontPage 5.0">

<meta name="ProgId" content="FrontPage.Editor.Document">

</head>



<body>



<form method="POST" action="<?= $PHP_SELF ?>">



<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" id="AutoNumber1">

  <tr>

    <td width="100%" class="Border">

    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2">

      <tr>

        <td colspan="38" align="center" class="Heading">Log Report - <?= $_POST[month] ?></td>

      </tr>

      <tr>

        <td valign="top" class="Heading3">Username:</td>

        <td valign="top" class="Heading3">Totals:</td>

        <td valign="top" class="Heading3">Member Searches:</td>

        <td valign="top" class="Heading3">View Member:</td>

        <td valign="top" class="Heading3">Edit Member 1:</td>

        <td valign="top" class="Heading3">Edit Member 2:</td>

        <td valign="top" class="Heading3">View Statement Current:</td>

        <td valign="top" class="Heading3">View Statement Past:</td>

        <td valign="top" class="Heading3">Add Notes:</td>

        <td valign="top" class="Heading3">View Notes:</td>

        <td valign="top" class="Heading3">Order Cheque Book:</td>

        <td valign="top" class="Heading3">Order Mem Card:</td>

        <td valign="top" class="Heading3">Change Facility:</td>

        <td valign="top" class="Heading3">Change Re Facility:</td>

        <td valign="top" class="Heading3">Conversion:</td>

        <td valign="top" class="Heading3">Fee Payment:</td>

        <td valign="top" class="Heading3">Fee Reversal:</td>

        <td valign="top" class="Heading3">Send Tax Invoice:</td>

        <td valign="top" class="Heading3">Deactive:</td>

        <td valign="top" class="Heading3">Send Message:</td>

        <td valign="top" class="Heading3">View message:</td>

        <td valign="top" class="Heading3">Funds Transfer:</td>

        <td valign="top" class="Heading3">Auth Check:</td>

        <td valign="top" class="Heading3">Auth Edit:</td>

        <td valign="top" class="Heading3">Upload Newsletter:</td>

        <td valign="top" class="Heading3">Directories:</td>

        <td valign="top" class="Heading3">Contacts:</td>

        <td valign="top" class="Heading3">Currency Conversion:</td>

        <td valign="top" class="Heading3">Summary:</td>

        <td valign="top" class="Heading3">Class Add:</td>

        <td valign="top" class="Heading3">Class Edit:</td>

        <td valign="top" class="Heading3">Class Search:</td>

        <td valign="top" class="Heading3">Class Pic Upload:</td>

        <td valign="top" class="Heading3">Stats Report:</td>

        <td valign="top" class="Heading3">Fees Owing:</td>

        <td valign="top" class="Heading3">Email List:</td>

        <td valign="top" class="Heading3">Fax List:</td>

        <td valign="top" class="Heading3">Labels:</td>

      </tr>

      <?



       $foo = 0;

       if($_REQUEST['area'] == 'all')  {

        $query = dbRead("select Name as username, FieldID, username as User from tbl_admin_users where Suspended != '1' and CID = '".$_SESSION[User][CID]."' order by Name");

       } else {

        $query = dbRead("select Name as username, FieldID, username as User from tbl_admin_users where Area = '".$_REQUEST['area']."' and Suspended != '1' order by Name");

       }



       while($row = mysql_fetch_assoc($query)) {



        $query2 = dbRead("select tbl_kpi_type.Type as Type, count(tbl_kpi.Type) as Count from tbl_kpi, tbl_kpi_type where (tbl_kpi.Type = tbl_kpi_type.FieldID) and UserID=".$row['FieldID']." and date like '".$_POST[month]."-%' group by tbl_kpi.Type","etxint_log");

        while($row2 = mysql_fetch_assoc($query2)) {



         $row3[$row2[Type]] = $row2[Count];



        }



        if(is_array($row3)) { $total = array_sum($row3); } else { $total = 0; }



        $cfgbgcolorone="#CCCCCC";

        $cfgbgcolortwo="#EEEEEE";

        $bgcolor=$cfgbgcolorone;

        $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;



        if(!$row[username]) {



         $row['username'] = "None [".$row['User']."]";



        }



        ?>

      <tr bgcolor="<?= $bgcolor ?>">

        <td valign="top"><a class="nav" href="body.php?page=reports_log&tab=User Log&details=1&username=<?= $row[FieldID] ?>&month=<?= $_POST[month] ?>"><?= $row[username] ?></a></td>

        <td valign="top" align="right"><b><?= $total ?></b></td>

        <td valign="top" align="right"><? if(!$row3["Member Search"]) { echo "0"; } else { echo $row3["Member Search"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["View Member"]) { echo "0"; } else { echo $row3["View Member"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Change Member 1"]) { echo "0"; } else { echo $row3["Change Member 1"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Change Member 2"]) { echo "0"; } else { echo $row3["Change Member 2"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["View Current Statement"]) { echo "0"; } else { echo $row3["View Current Statement"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["View Past Statement"]) { echo "0"; } else { echo $row3["View Past Statement"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Add Notes"]) { echo "0"; } else { echo $row3["Add Notes"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["View Notes"]) { echo "0"; } else { echo $row3["View Notes"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Order Cheque Book"]) { echo "0"; } else { echo $row3["Order Cheque Book"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Order Mem Card"]) { echo "0"; } else { echo $row3["Order Mem Card"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Change Facility"]) { echo "0"; } else { echo $row3["Change Facility"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Change Real Estate Facility"]) { echo "0"; } else { echo $row3["Change Real Estate Facility"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Conversion"]) { echo "0"; } else { echo $row3["Conversion"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Fee Payment"]) { echo "0"; } else { echo $row3["Fee Payment"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Fee Reversals"]) { echo "0"; } else { echo $row3["Fee Reversals"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Send Tax Invoice"]) { echo "0"; } else { echo $row3["Send Tax Invoice"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Deactive"]) { echo "0"; } else { echo $row3["Deactive"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Send Messages"]) { echo "0"; } else { echo $row3["Send Messages"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["View Messages"]) { echo "0"; } else { echo $row3["View Messages"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Funds Transfer"]) { echo "0"; } else { echo $row3["Funds Transfer"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Auth Check"]) { echo "0"; } else { echo $row3["Auth Check"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Auth Edit"]) { echo "0"; } else { echo $row3["Auth Edit"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Upload Newsletter"]) { echo "0"; } else { echo $row3["Upload Newsletter"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Directory Download"]) { echo "0"; } else { echo $row3["Directory Download"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Contacts"]) { echo "0"; } else { echo $row3["Contacts"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Currency Convert"]) { echo "0"; } else { echo $row3["Currency Convert"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Summary"]) { echo "0"; } else { echo $row3["Summary"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Classifieds Add"]) { echo "0"; } else { echo $row3["Classifieds Add"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Classifieds Edit"]) { echo "0"; } else { echo $row3["Classifieds Edit"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Classifieds Search"]) { echo "0"; } else { echo $row3["Classifieds Search"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Classifieds Picture Upload"]) { echo "0"; } else { echo $row3["Classifieds Picture Upload"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Stats Report"]) { echo "0"; } else { echo $row3["Stats Report"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Fees Owing"]) { echo "0"; } else { echo $row3["Fees Owing"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Email List"]) { echo "0"; } else { echo $row3["Email List"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Fax List"]) { echo "0"; } else { echo $row3["Fax List"]; } ?></td>

        <td valign="top" align="right"><? if(!$row3["Labels"]) { echo "0"; } else { echo $row3["Labels"]; } ?></td>

      </tr>

        <?

        $row3 = "";

        $foo++;



       }

      ?>

    </table>

    </td>

  </tr>

</table>



</form>



</body>



</html>

<?



}

}



function web() {



if($_REQUEST['next']) {



 if($_REQUEST['type'] == 1 || $_REQUEST['type'] == 2) {

  $tn = "Page";

  $query = dbRead("select tbl_corp_log.*, tbl_admin_users.Name as Name, tbl_corp_pages.page as page from tbl_corp_log, tbl_admin_users, tbl_corp_pages where (tbl_corp_log.UserID = tbl_admin_users.FieldID) and (tbl_corp_log.PageID = tbl_corp_pages.pageid) and Type = '".$_REQUEST['type']."' order by Date");

 } elseif($_REQUEST['type'] == 3) {

  $tn = "Licensee";

  $query = dbRead("select tbl_corp_log.*, tbl_admin_users.Name as Name, area.place as page from tbl_corp_log, tbl_admin_users, area where (tbl_corp_log.UserID = tbl_admin_users.FieldID) and (tbl_corp_log.PageID = area.FieldID) and Type = '".$_REQUEST['type']."' order by Date");

 } elseif($_REQUEST['type'] == 4 || $_REQUEST['type'] == 5) {

  $tn = "Country";

  $query = dbRead("select tbl_corp_log.*, tbl_admin_users.Name as Name, country.name as page from tbl_corp_log, tbl_admin_users, country where (tbl_corp_log.UserID = tbl_admin_users.FieldID) and (tbl_corp_log.CID = country.countryID) and Type = '".$_REQUEST['type']."' order by Date");

 } elseif($_REQUEST['type'] == 6) {

  $tn = "Letter";

  $query = dbRead("select tbl_corp_log.*, tbl_admin_users.Name as Name, tbl_corp_log.PageID as page from tbl_corp_log, tbl_admin_users where (tbl_corp_log.UserID = tbl_admin_users.FieldID) and Type = '".$_REQUEST['type']."' order by Date");

 } elseif($_REQUEST['type'] == 7) {

  $tn = "User";

  $query = dbRead("select tbl_corp_log.*, tbl_admin_users.Name as Name, tbl_admin_users2.name as page from tbl_corp_log, tbl_admin_users, tbl_admin_users as tbl_admin_users2 where (tbl_corp_log.UserID = tbl_admin_users.FieldID) and (tbl_corp_log.PageID = tbl_admin_users2.FieldID) and Type = '".$_REQUEST['type']."' order by Date");

 } elseif($_REQUEST['type'] == 8) {
  $tn = "members_log";
  $query = dbRead("select * from table_members_log");
 }

?>



<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" id="AutoNumber1">

  <tr>

    <td width="100%" class="Border">

    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2">

      <tr>

        <td colspan="38" align="center" class="Heading">Log Report - <?= $_POST[month] ?></td>

      </tr>
<?php if($_REQUEST['type'] == 8){ ?>
      <tr>

        <td valign="top" class="Heading3">Members Account Number:</td>

        <td valign="top" class="Heading3">Date:</td>
        
        <td valign="top" class="Heading3">Time:</td>

      </tr>

      <?
}else{ ?>
    <tr>

        <td valign="top" class="Heading3">Date:</td>

        <td valign="top" class="Heading3"><?= $tn ?>:</td>

        <td valign="top" class="Heading3">CID:</td>

        <td valign="top" class="Heading3">Language:</td>

        <td valign="top" class="Heading3">User:</td>

        <td valign="top" class="Heading3">Data:</td>

      </tr>
<?php } 


       $foo = 0;

       while($row = mysql_fetch_assoc($query)) {



        $cfgbgcolorone="#CCCCCC";

        $cfgbgcolortwo="#EEEEEE";

        $bgcolor=$cfgbgcolorone;

        $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;



 if($_REQUEST['type'] == 8){ 
     $date_did= $row['Date'];
     $date_did= explode(" ",$date_did);
     ?>
       <tr bgcolor="<?= $bgcolor ?>">

        <td valign="top" align="left"><?= $row['AccNo'] ?></td>

        <td valign="top" align="left"><?= $date_did[0] ?></td>
        
        <td valign="top" align="left"><?= $date_did[1] ?></td>

     </tr>
      <?php 
 }else{

        ?>

      <tr bgcolor="<?= $bgcolor ?>">

        <td valign="top" align="left"><?= $row['Date'] ?></td>

        <td valign="top" align="left"><?= $row['page'] ?></td>

        <td valign="top" align="left"><?= $row['CID'] ?></td>

        <td valign="top" align="left"><?= $row['Lang_Code'] ?></td>

        <td valign="top" align="left"><?= $row['Name'] ?></td>

        <td valign="top" align="left"><?= display_log_array($row['Data']) ?></td>

     </tr>

        <?
 }
        $row3 = "";

        $foo++;



       }

      ?>

    </table>

    </td>

  </tr>

</table>





<? } else {?>

<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">

  <tr>

    <td width="100%" class="Border">

    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">

      <tr>

        <td class="Heading" align="center" colspan="2">Web Update Log</td>

      </tr>

     <tr>

      <td align="right" valign="middle" class="Heading2"><b>Type:</b></td>

      <td bgcolor="FFFFFF"><select size="1" name="type">



		 <option value="1">Web Page Data</option>

		 <option value="2">Web Page Header/Links/Active</option>

		 <option value="3">Area Updates</option>

		 <option value="4">CountryData Updates</option>

		 <option value="5">Country Updates</option>

		 <option value="6">Letter Updates</option>

		 <option value="7">User Updates</option>
                 
         <option value="8">Members Log</option>

	   </select>

	  </td>

     </tr>

      <tr>

        <td class="Heading2">&nbsp;</td>

        <td bgcolor="#FFFFFF" align="right"><input type="submit" name="Submit" value="Get Report"></td>

      </tr>

    </table>

    </td>

  </tr>

</table>



<input type="hidden" name="next" value="1">





<?

 }

}



 /**

  * Functions.

  */



 function display_log_array($Data) {



  $NewData = unserialize($Data);

  if(is_array($NewData)) {



   foreach($NewData as $Key => $Value) {



    $PrintData .= "<b>" . $Key . "</b><br>&nbsp;&nbsp;" . $Value[0] . " <b>Changed to</b> " . $Value[1] . "<br>";



   }



   return $PrintData;



  }



 }



?>