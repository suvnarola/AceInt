<?

 /**
  * E Banc Trade KPI Tracking.
  *
  * kpitrack.php
  * Version 0.01
  */

 include("includes/modules/class.kpi.php");
 include("includes/modules/db.php");

 add_kpi("65",0);

 /**
  * Main Working Secion.
  */

 $TabArray = array('Current Users','Track User','System Summary');

 displaytabs($TabArray);

 if($_REQUEST['tab'] == "tab1") {

  display_current_users();

 } elseif($_REQUEST['tab'] == "tab2") {

  if($_REQUEST['GetLogins']) {

   display_track_logins();

  } elseif($_REQUEST['TrackLogin']) {

   track_login();

  } else {

   display_initial_track();

  }

 } elseif($_REQUEST['tab'] == "tab3") {

  display_system_summary();

 }

 /**
  * Functions
  */

 function track_login() {

  $LoginSQL = dbRead("select tbl_admin_users.*, tbl_kpi_login_history.* from etxint_etradebanc.tbl_admin_users, tbl_kpi_login_history where (tbl_kpi_login_history.UserID = tbl_admin_users.FieldID) and tbl_kpi_login_history.FieldID = " . $_REQUEST['LoginID'], "etxint_log");
  $LoginRow = mysql_fetch_assoc($LoginSQL);

  $SessionLengthSQL = dbRead("select tbl_kpi_login_history.*, sec_to_time(unix_timestamp(max(tbl_kpi.Date))-unix_timestamp(tbl_kpi_login_history.Date)) as Diff from tbl_kpi_login_history, tbl_kpi where (tbl_kpi_login_history.FieldID = tbl_kpi.LoginID) and tbl_kpi_login_history.UserID = " . $LoginRow['UserID'] . " and tbl_kpi_login_history.FieldID = ".$LoginRow['FieldID']." group by tbl_kpi_login_history.FieldID Order By tbl_kpi_login_history.Date DESC","etxint_log");
  $SessionLengthRow = mysql_fetch_assoc($SessionLengthSQL);

  $SessionItemsSQL = dbRead("select tbl_kpi.FieldID, tbl_kpi.Date, tbl_kpi_type.Type, tbl_kpi.Memid from tbl_kpi, tbl_kpi_type where (tbl_kpi.Type = tbl_kpi_type.FieldID) and tbl_kpi.LoginID = " . $LoginRow['FieldID'] . " Order By tbl_kpi.Date Desc", "etxint_log");
  $SessionItems = mysql_num_rows($SessionItemsSQL);

  ?>
  <table width="620" cellspacing="0" cellpadding="3">
   <tr>
    <td colspan="2" align="center"><b>Viewing Session for <?= $LoginRow['Username'] ?> [<?= $LoginRow['FieldID'] ?>]</b></td>
   </tr>
   <tr>
    <td align="left" style="border-top: 1px solid #000000; border-left: 1px solid #000000"><b>Session Start Time</b></td>
    <td align="right" style="border-top: 1px solid #000000; border-right: 1px solid #000000"><?= $LoginRow['Date'] ?></td>
   </tr>
   <tr>
    <td align="left" style="border-left: 1px solid #000000; background: #DDDDDD"><b>Session Length</b></td>
    <td align="right" style="border-right: 1px solid #000000; background: #DDDDDD"><?= $SessionLengthRow['Diff'] ?></td>
   </tr>
   <tr>
    <td align="left" style="border-bottom: 1px solid #000000; border-left: 1px solid #000000"><b>Session Items</b></td>
    <td align="right" style="border-bottom: 1px solid #000000; border-right: 1px solid #000000"><?= $SessionItems ?></td>
   </tr>
  </table>
  <br>
  <table width="620" cellspacing="0" cellpadding="3">
   <tr>
    <td colspan="3" align="center"><b>Session Items :: </b> <a class="nav" href="includes/kpi_ganttgraph.php?UserID=<?= $LoginRow['UserID'] ?>&Date=<?= date("Y-m-d", strtotime($LoginRow['Date'])+$_SESSION['Country']['timezone']) ?>"><b>Graph for <?= date("Y-m-d", strtotime($LoginRow['Date'])+$_SESSION['Country']['timezone']) ?></b></a></td>
   </tr>
   <tr>
    <td align="left" style="border-top: 1px solid #000000; border-left: 1px solid #000000"><b>Date</b></td>
    <td align="left" style="border-top: 1px solid #000000"><b>Type</b></td>
    <td align="right" style="border-top: 1px solid #000000; border-right: 1px solid #000000"><b>Memid</b></td>
   </tr>
   <?

    $SessionCount = 0;

    while($SessionItemsRow = mysql_fetch_assoc($SessionItemsSQL)) {

     $SessionCount++;

     $BGColor = "#DDDDDD";
	 $SessionCount % 2  ? 0: $BGColor = "#FFFFFF";

     ?>
      <tr style="background: <?= $BGColor ?>">
       <td align="left" style="border-left: 1px solid #000000"><?= $SessionItemsRow['Date'] ?></td>
       <td align="left"><?= $SessionItemsRow['Type'] ?></td>
       <td align="right" style="border-right: 1px solid #000000"><?= $SessionItemsRow['Memid'] ?></td>
      </tr>
     <?

    }

   ?>
   <tr>
    <td style="border-top: 1px solid #000000">&nbsp;</td>
    <td style="border-top: 1px solid #000000">&nbsp;</td>
    <td style="border-top: 1px solid #000000">&nbsp;</td>
   </tr>
  </table>
  <?

 }

 function display_track_logins() {

  $GetUserIDSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where Username = '".$_REQUEST['UserToTrack']."'");
  $GetUserIDRow = mysql_fetch_assoc($GetUserIDSQL);

 ?>
  <table width="620" cellspacing="0" cellpadding="3">
   <tr>
    <td colspan="5" align="center"><b>Select Session to View (<?= $GetUserIDRow['Username'] ?>)</b></td>
   </tr>
   <tr>
    <td style="border-top: 1px solid #000000; border-left: 1px solid #000000" align="left"><b>Login Date</b></td>
    <td style="border-top: 1px solid #000000;" align="left"><b>Location</b></td>
    <td style="border-top: 1px solid #000000;" align="right"><b>Session Length</b></td>
    <td style="border-top: 1px solid #000000;" align="right"><b>VIEW</b></td>
    <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="right"><b>GRAPH</b></td>
   </tr>
   <?

   $UserCount = 0;

    //$LoginSQL = dbRead("select tbl_kpi_login_history.*, sec_to_time((unix_timestamp(max(tbl_kpi.Date))+".$_SESSION['Country']['timezone'].")-(unix_timestamp(tbl_kpi_login_history.Date)+".$_SESSION['Country']['timezone'].")) as Diff, (unix_timestamp(tbl_kpi_login_history.Date)+".$_SESSION['Country']['timezone'].") as Date from tbl_kpi_login_history, tbl_kpi where (tbl_kpi_login_history.FieldID = tbl_kpi.LoginID) and tbl_kpi_login_history.UserID = " . $GetUserIDRow['FieldID'] . " and tbl_kpi_login_history.Date like '2007-06-%' group by tbl_kpi_login_history.FieldID Order By tbl_kpi_login_history.Date DESC","log");
    $LoginSQL = dbRead("select tbl_kpi_login_history.*, sec_to_time((unix_timestamp(max(tbl_kpi.Date))+".$_SESSION['Country']['timezone'].")-(unix_timestamp(tbl_kpi_login_history.Date)+".$_SESSION['Country']['timezone'].")) as Diff, (unix_timestamp(tbl_kpi_login_history.Date)+".$_SESSION['Country']['timezone'].") as Date from tbl_kpi_login_history, tbl_kpi where (tbl_kpi_login_history.FieldID = tbl_kpi.LoginID) and tbl_kpi_login_history.UserID = " . $GetUserIDRow['FieldID'] . " group by tbl_kpi_login_history.FieldID Order By tbl_kpi_login_history.Date DESC","etxint_log");

    while($LoginRow = mysql_fetch_assoc($LoginSQL)) {

     $UserCount++;

	 $BGColor = "#DDDDDD";
   	 $UserCount % 2  ? 0: $BGColor = "#FFFFFF";

     $LocationSQL = dbRead("SELECT * FROM tbl_geoip WHERE IP_FROM <= inet_aton('".long2ip($LoginRow['IPAddress'])."') AND IP_TO >= inet_aton('".long2ip($LoginRow['IPAddress'])."')");
     $LocationRow = @mysql_fetch_assoc($LocationSQL);

     $LocationRow['Country_Name'] = ($LocationRow['Country_Name']) ? $LocationRow['Country_Name'] : "Unknown";

     if($CheckDate != date("Y-m-d", $LoginRow['Date']+$_SESSION['Country']['timezone'])) {

      $Link = "<a href=\"includes/kpi_ganttgraph.php?UserID=".$LoginRow['UserID']."&Date=".date("Y-m-d", $LoginRow['Date']+$_SESSION['Country']['timezone'])."\" class=\"nav\">VIEW</a>";

     } else {

      $Link = "&nbsp;";

     }

     $CheckDate = date("Y-m-d", $LoginRow['Date']+$_SESSION['Country']['timezone']);

     ?>
     <tr>
      <td style="border-top: 1px solid #000000; border-left: 1px solid #000000; background: <?= $BGColor ?>" align="left"><?= date("Y-m-d H:i:s", $LoginRow['Date']) ?></td>
      <td style="border-top: 1px solid #000000; background: <?= $BGColor ?>" align="left"><?= $LocationRow['Country_Name'] ?></td>
      <td style="border-top: 1px solid #000000; background: <?= $BGColor ?>" align="right"><?= $LoginRow['Diff'] ?></td>
      <td style="border-top: 1px solid #000000; background: <?= $BGColor ?>" align="right"><a href="body.php?page=kpitrack&tab=tab2&TrackLogin=true&LoginID=<?= $LoginRow['FieldID'] ?>" class="nav">VIEW</a></td>
      <td style="<? if($Link != "&nbsp;") { ?>border-top: 1px solid #000000; <? } ?>border-left: 1px solid #000000; border-right: 1px solid #000000; background: #FFFFFF" align="right"><?= $Link ?></td>
     </tr>
     <?

    }

   ?>
   <tr>
    <td colspan="5" align="center" style="border-top: 1px solid #000000"><b>Total of <?= $UserCount ?> Logins<b></td>
   </tr>
  </table>
 <?

 }

 function display_initial_track() {

  ?>
   <script language="javascript" type="text/javascript">
	<!--
		xbApi_onload = [];
		xbApi_path = 'javascript/xbApi/';
	// -->
   </script>
   <script language="javascript" src="javascript/xbApi/xbApi.loader.js" type="text/javascript"></script>
   <script type="text/javascript" language="javascript" src="includes/modules/javascript.usertrack.php" defer="defer"></script>
   <table width="620" cellspacing="0" cellpadding="2">
   <form name="TrackUser">
   <input type="hidden" name="GetLogins" value="1">
   <input type="hidden" name="page" value="kpitrack">
   <input type="hidden" name="tab" value="tab2">
    <tr>
     <td width="50%" valign="top">
      <table width="310" cellspacing="0" cellpadding="3" style="border: 1px solid #000000">
       <tr>
        <td colspan="1" align="Left" style="border-bottom: 1px solid #000000; background: #DDDDDD"><b>Select User to Track</b></td>
       </tr>
       <tr>
        <td><input type="text" name="UserToTrack" width="20" onkeyup="update_address_list(this.value)" value="<Search>" onclick="if(this.value == '<Search>') this.value = ''"></td>
       </tr>
       <tr>
        <td><input type="submit" name="Submit" value="Get User Logins >>" style="font-family: Tahoma; font-size: 8pt; font-weight: bold"></td>
       </tr>
      </table>
     </td>
     <td width="50%" valign="top">
      <table width="310" cellspacing="0" cellpadding="3" style="border: 1px solid #000000">
       <tr>
        <td colspan="1" align="Left" style="border-bottom: 1px solid #000000; background: #DDDDDD"><b>User List</b></td>
       </tr>
       <tr>
        <td colspan="2" align="left"><select size="10" name="UserID" onclick="document.TrackUser.UserToTrack.value = this.value">
         <?

          $UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users Order by Username");
          while($UserRow = mysql_fetch_assoc($UserSQL)) {

           ?><option value="<?= $UserRow['Username'] ?>"><?= $UserRow['Username'] ?> (<?= $UserRow['Name'] ?>)</option><?

          }

         ?>
        </select></td>
       </tr>
      </table>
     </td>
    </tr>
   </form>
   </table>
  <?

 }

 function display_system_summary() {

  $KpiTrack = new KPI();

  $KpiTrack->GetSystemSummary(date("Y-m"),$_REQUEST['OrderBy']);
  $KpiTrack->GetMonthlyUserSummary(date("Y-m"))

  ?>
   <table width="620" cellspacing="0" cellpadding="3">
    <tr>
     <td colspan="2" align="center"><b>Login Summary</b></td>
    </tr>
    <tr bgcolor="#DDDDDD">
     <td style="border-left: 1px solid #000000; border-top: 1px solid #000000;"><b>Total Logins</b></td>
     <td style="border-right: 1px solid #000000; border-top: 1px solid #000000;" align="right"><?= $KpiTrack->MonthlyUserSummary['TotalLogins'] ?></td>
    </tr>
    <tr>
     <td style="border-left: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Unique Users</b></td>
     <td style="border-right: 1px solid #000000; border-bottom: 1px solid #000000;" align="right"><?= $KpiTrack->MonthlyUserSummary['Unique'] ?></td>
    </tr>
   </table>
   <table width="620" cellspacing="0" cellpadding="3">
    <tr>
     <td colspan="2" align="center"><b>System Summary</b></td>
    </tr>
    <tr>
     <td style="border-left: 1px solid #000000; border-top: 1px solid #000000;"><a class="nav" href="body.php?page=kpitrack&tab=tab3&OrderBy=tbl_kpi_type.Type"><b>KPI</b></a></td>
     <td align="right" style="border-right: 1px solid #000000; border-top: 1px solid #000000;"><a class="nav" href="body.php?page=kpitrack&tab=tab3&OrderBy=Count"><b>Page Count</b></a></td>
    </tr>
  <?

   $UserCount=0;

   foreach($KpiTrack->SystemSummary as $Key => $Value) {

    $UserCount++;

	$BGColor = "#DDDDDD";
	$UserCount % 2  ? 0: $BGColor = "#FFFFFF";

    ?>
    <tr bgcolor="<?= $BGColor ?>">
     <td style="border-left: 1px solid #000000"><?= $Key ?></td>
     <td align="right" style="border-right: 1px solid #000000"><?= $Value ?></td>
    </tr>
    <?

   }

    ?>
    <tr>
     <td colspan="2" align="center" style="border-top: 1px solid #000000"><b>Total of <?= $UserCount ?> Pages<b></td>
    </tr>
   </table>
  <?

 }

 function display_current_users() {

  $KpiTrack = new KPI();

  $KpiTrack->GetCurrentUsers();

  ?>
   <table width="620" cellspacing="0" cellpadding="3">
    <tr>
     <td colspan="5" align="center"><b>Current Intranet Users</b></td>
    </tr>
    <tr>
     <td style="border-left: 1px solid #000000; border-top: 1px solid #000000;"><b>User</b></td>
     <td style="border-top: 1px solid #000000;"><b>Location</b></td>
     <td style="border-top: 1px solid #000000;"><b>Login Date</b></td>
     <td style="border-top: 1px solid #000000;"><b>Last Access Date</b></td>
     <td align="right" style="border-right: 1px solid #000000; border-top: 1px solid #000000;"><b>Last Access</b></td>
    </tr>
    <?

     $UserCount=0;

     foreach($KpiTrack->CurrentUsers as $Key => $Value) {

      $UserCount++;

      $UserSQL = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = " . $Value['UserID']);
      $UserRow = mysql_fetch_assoc($UserSQL);

      $KPILoginSQL = dbRead("select tbl_kpi_login_history.* from tbl_kpi_login_history where FieldID = " . $Value['LoginID'], "etxint_log");
      $KPILoginRow = @mysql_fetch_assoc($KPILoginSQL);

	  $BGColor = "#DDDDDD";
	  $UserCount % 2  ? 0: $BGColor = "#FFFFFF";

      $LocationSQL = dbRead("SELECT * FROM tbl_geoip WHERE IP_FROM <= inet_aton('".long2ip($KPILoginRow['IPAddress'])."') AND IP_TO >= inet_aton('".long2ip($KPILoginRow['IPAddress'])."')");
      $LocationRow = @mysql_fetch_assoc($LocationSQL);

      $LocationRow['Country_Name'] = ($LocationRow['Country_Name']) ? $LocationRow['Country_Name'] : "Unknown";
      $UserRow['Name'] = ($UserRow['Name']) ? $UserRow['Name'] : "Unknown";
      $UserRow['Username'] = ($UserRow['Username']) ? $UserRow['Username'] : "Unknown";

      ?>
      <tr bgcolor="<?= $BGColor ?>">
       <td style="border-left: 1px solid #000000"><a href="body.php?page=kpitrack&tab=tab2&TrackLogin=true&LoginID=<?= $Value['LoginID'] ?>" class="nav"><?= $UserRow['Name'] ?> (<?= $UserRow['Username'] ?>)</a></td>
       <td><?= $LocationRow['Country_Name'] ?></td>
       <td><?= $KPILoginRow['Date'] ?></td>
       <td><?= $Value['Date'] ?></td>
       <td align="right" style="border-right: 1px solid #000000"><?= $Value['Diff'] ?></td>
      </tr>
      <?

     }

    ?>
    <tr>
     <td colspan="5" align="center" style="border-top: 1px solid #000000"><b>Total of <?= $UserCount ?> Users</b></td>
    </tr>
   </table>
  <?

 }

?>
