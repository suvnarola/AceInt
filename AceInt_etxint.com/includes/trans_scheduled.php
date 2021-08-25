<?

if(!checkmodule("Scheduled")) {

?>

<table width="620" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>

<form method="POST" action="body.php?page=trans_scheduled&ID=<?= $_REQUEST[ID] ?>&tab=<?= $_REQUEST[tab] ?>">

<?

// Some Setup.

 $tabarray = array(get_page_data("18"),get_page_data("11"));

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_REQUEST[tab] == "tab1") {

 if($_REQUEST[next]) {

  $id_array = $_REQUEST[sched_id];

  foreach($id_array as $key => $value) {

   dbWrite("delete from scheduled where ID='$value'");

  }

  view_scheduled();

 } else {

  if($_REQUEST[editsched]) {

   edit_scheduled($_REQUEST[ID]);

  } else {

   if($_REQUEST[editsched2]) {

    $newdate = date("Y-m-d", mktime(1,1,1,$_REQUEST[sd_month],$_REQUEST[sd_day],$_REQUEST[sd_year]));

    dbWrite("update scheduled set from_memid='$_REQUEST[from_memid]', to_memid='$_REQUEST[to_memid]', frequency='$_REQUEST[frequency]', startdate='$newdate', amount='$_REQUEST[amount]', timesleft='$_REQUEST[timesleft]', active='$_REQUEST[active]' where ID='$_REQUEST[ID]'");
    view_scheduled();

   } else {

    view_scheduled();

   }

  }

 }

} elseif($_REQUEST[tab] == "tab2") {

 if($_POST[next]) {

  $newdate = date("Y-m-d", mktime(1,1,1,$_REQUEST[sd_month],$_REQUEST[sd_day],$_REQUEST[sd_year]));

  dbWrite("insert into scheduled (from_memid,to_memid,frequency,amount,enteredby,timesleft,active,startdate) values ('$_REQUEST[from_memid]','$_REQUEST[to_memid]','$_REQUEST[frequency]','$_REQUEST[amount]','".$_SESSION['Username']."','$_REQUEST[timesleft]','$_REQUEST[active]','$newdate')");
  add_scheduled();

 } else {

  add_scheduled();

 }

}

?>

</form>

<?

function view_scheduled() {

 ?>

 <table border="0" cellspacing="0" width="620" cellpadding="1">
 <tr>
 <td class="Border">
  <table border="0" cellspacing="0" width="100%" cellpadding="3">
   <tr>
     <td width="100%" colspan="8" align="center" class="Heading"><?= get_page_data("1") ?></td>
   </tr>
   <tr>
     <td class="Heading2"><b><?= get_page_data("4") ?>:</b></td>
     <td class="Heading2"><b><?= get_page_data("5") ?>:</b></td>
     <td align="right" class="Heading2"><b><?= get_page_data("7") ?>:</b></td>
     <td align="right" class="Heading2"><b><?= get_word("61") ?>:</b></td>
     <td class="Heading2" align="right"><b><?= get_page_data("13") ?>:</b></td>
     <td align="right" class="Heading2"><b><?= get_page_data("9") ?>:</b></td>
     <td align="right" class="Heading2"><b><?= get_page_data("14") ?>:</b></td>
     <td align="right" class="Heading2"><b><?= get_word("125") ?>:</b></td>
   </tr>

 <?

 $time_start = getmicrotime();

 $query = dbRead("select scheduled.*, members_to.companyname as comp_to, members_from.companyname as comp_from from scheduled, members as members_to, members as members_from where (members_to.memid = scheduled.to_memid) and (members_from.memid = scheduled.from_memid) and members_from.CID = '".$_SESSION['User']['CID']."'");
 $counter = mysql_num_rows($query);

 ?>
   <tr>
     <td colspan="8" align="center" bgcolor="#FFFFFF">Total Scheduled Transfers: <?= $counter ?></td>
   </tr>
 <?

 $foo = 0;

 while($row = mysql_fetch_array($query)) {

  $cfgbgcolorone = "#CCCCCC";
  $cfgbgcolortwo = "#EEEEEE";
  $bgcolor = $cfgbgcolorone;
  $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

  ?>
    <tr>
      <td bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[comp_from]) ?></td>
      <td bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[comp_to]) ?></td>
      <td align="right" bgcolor="<?= $bgcolor ?>"><?= get_all_added_characters($row[frequency]) ?></td>
      <td align="right" bgcolor="<?= $bgcolor ?>"><?= $row[amount] ?></td>
      <td align="right" bgcolor="<?= $bgcolor ?>"><?= $row[timesleft] ?></td>
      <td align="right" bgcolor="<?= $bgcolor ?>"><?= $row[active] ?></td>
      <td align="right" bgcolor="<?= $bgcolor ?>"><a href="body.php?page=trans_scheduled&ID=<?= $row[ID] ?>&tab=<?= $_GET[tab] ?>&editsched=true" class="nav"><?= get_page_data("14") ?></a></td>
      <td align="right" bgcolor="<?= $bgcolor ?>"><input type="checkbox" value="<?= $row[ID] ?>" name="sched_id[]"></td>
    </tr>
  <?

  $foo++;

 }

 ?>

     <tr>
       <td bgcolor="#FFFFFF" colspan="8" align="right">
        <button name="B1" style="width: 124; height: 25" type="submit">
        <font face="Verdana"><?= get_word("125") ?></font></button></td>
     </tr>
     <tr>
       <td bgcolor="#FFFFFF" colspan="8" align="center">Page Generation Time: <?
		    $time_end = getmicrotime();
		    $time = $time_end - $time_start;
			$time = number_format($time,2);
			echo $time;
		    ?> seconds</td>
     </tr>
    </table>
   </td>
  </tr>
 </table>

 <input type="hidden" name="next" value="1">

 <?

}

function add_scheduled() {

?>
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="620" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td width="100%" colspan="2" align="center" class="Heading"><?= get_page_data("2") ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("4") ?>.:</td>
        <td bgcolor="#FFFFFF"><input type="text" name="from_memid" size="10"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("5") ?>.:</td>
        <td bgcolor="#FFFFFF"><input type="text" name="to_memid" size="10"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("6") ?>:</td>
        <td bgcolor="#FFFFFF">
        <select name="sd_day">
         <option <? if(date("j") == "1") { echo "selected "; } ?>value="1">1</option>
         <option <? if(date("j") == "2") { echo "selected "; } ?>value="2">2</option>
         <option <? if(date("j") == "3") { echo "selected "; } ?>value="3">3</option>
         <option <? if(date("j") == "4") { echo "selected "; } ?>value="4">4</option>
         <option <? if(date("j") == "5") { echo "selected "; } ?>value="5">5</option>
         <option <? if(date("j") == "6") { echo "selected "; } ?>value="6">6</option>
         <option <? if(date("j") == "7") { echo "selected "; } ?>value="7">7</option>
         <option <? if(date("j") == "8") { echo "selected "; } ?>value="8">8</option>
         <option <? if(date("j") == "9") { echo "selected "; } ?>value="9">9</option>
         <option <? if(date("j") == "10") { echo "selected "; } ?>value="10">10</option>
         <option <? if(date("j") == "11") { echo "selected "; } ?>value="11">11</option>
         <option <? if(date("j") == "12") { echo "selected "; } ?>value="12">12</option>
         <option <? if(date("j") == "13") { echo "selected "; } ?>value="13">13</option>
         <option <? if(date("j") == "14") { echo "selected "; } ?>value="14">14</option>
         <option <? if(date("j") == "15") { echo "selected "; } ?>value="15">15</option>
         <option <? if(date("j") == "16") { echo "selected "; } ?>value="16">16</option>
         <option <? if(date("j") == "17") { echo "selected "; } ?>value="17">17</option>
         <option <? if(date("j") == "18") { echo "selected "; } ?>value="18">18</option>
         <option <? if(date("j") == "19") { echo "selected "; } ?>value="19">19</option>
         <option <? if(date("j") == "20") { echo "selected "; } ?>value="20">20</option>
         <option <? if(date("j") == "21") { echo "selected "; } ?>value="21">21</option>
         <option <? if(date("j") == "22") { echo "selected "; } ?>value="22">22</option>
         <option <? if(date("j") == "23") { echo "selected "; } ?>value="23">23</option>
         <option <? if(date("j") == "24") { echo "selected "; } ?>value="24">24</option>
         <option <? if(date("j") == "25") { echo "selected "; } ?>value="25">25</option>
         <option <? if(date("j") == "26") { echo "selected "; } ?>value="26">26</option>
         <option <? if(date("j") == "27") { echo "selected "; } ?>value="27">27</option>
         <option <? if(date("j") == "28") { echo "selected "; } ?>value="28">28</option>
         <option <? if(date("j") == "29") { echo "selected "; } ?>value="29">29</option>
         <option <? if(date("j") == "30") { echo "selected "; } ?>value="30">30</option>
         <option <? if(date("j") == "31") { echo "selected "; } ?>value="31">31</option>
        </select>

        <select name="sd_month">
         <option <? if(date("n") == "1") { echo "selected "; } ?>value="1">Jan</option>
         <option <? if(date("n") == "2") { echo "selected "; } ?>value="2">Feb</option>
         <option <? if(date("n") == "3") { echo "selected "; } ?>value="3">Mar</option>
         <option <? if(date("n") == "4") { echo "selected "; } ?>value="4">Apr</option>
         <option <? if(date("n") == "5") { echo "selected "; } ?>value="5">May</option>
         <option <? if(date("n") == "6") { echo "selected "; } ?>value="6">Jun</option>
         <option <? if(date("n") == "7") { echo "selected "; } ?>value="7">Jul</option>
         <option <? if(date("n") == "8") { echo "selected "; } ?>value="8">Aug</option>
         <option <? if(date("n") == "9") { echo "selected "; } ?>value="9">Sep</option>
         <option <? if(date("n") == "10") { echo "selected "; } ?>value="10">Oct</option>
         <option <? if(date("n") == "11") { echo "selected "; } ?>value="11">Nov</option>
         <option <? if(date("n") == "12") { echo "selected "; } ?>value="12">Dec</option>
        </select>

        <select name="sd_year">
         <option <? if(date("Y") == "2002") { echo "selected "; } ?>value="2002">2002</option>
         <option <? if(date("Y") == "2003") { echo "selected "; } ?>value="2003">2003</option>
         <option <? if(date("Y") == "2004") { echo "selected "; } ?>value="2004">2004</option>
         <option <? if(date("Y") == "2005") { echo "selected "; } ?>value="2005">2005</option>
         <option <? if(date("Y") == "2006") { echo "selected "; } ?>value="2006">2006</option>
         <option <? if(date("Y") == "2007") { echo "selected "; } ?>value="2007">2007</option>
         <option <? if(date("Y") == "2008") { echo "selected "; } ?>value="2008">2008</option>
         <option <? if(date("Y") == "2009") { echo "selected "; } ?>value="2009">2009</option>
         <option <? if(date("Y") == "2010") { echo "selected "; } ?>value="2010">2010</option>
         <option <? if(date("Y") == "2011") { echo "selected "; } ?>value="2011">2011</option>
        </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("7") ?>:</td>
        <td bgcolor="#FFFFFF"><select size="1" name="frequency">
        <option selected value="weekly"><?= get_page_data("15") ?></option>
        <option value="fortnightly"><?= get_page_data("16") ?></option>
        <option value="monthly"><?= get_page_data("17") ?></option>
        <option value="yearly">Yearly</option>
        </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_word("61") ?>:</td>
        <td bgcolor="#FFFFFF"><input type="text" name="amount" size="15"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("8") ?>:</td>
        <td bgcolor="#FFFFFF" valign="middle"><input type="text" name="timesleft" size="15">&nbsp;&nbsp;[ <?= get_page_data("10") ?>. ]</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("9") ?>:</td>
        <td bgcolor="#FFFFFF">
        <select size="1" name="active">
        <option selected value="Yes">Yes</option>
        <option value="No">No</option>
        </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%">&nbsp;</td>
        <td bgcolor="#FFFFFF" align="right">
        <button name="B1" style="width: 114; height: 25" type="submit">
        <font face="Verdana"><?= get_page_data("11") ?></font></button></td>
      </tr>
      </table>
    </td>
  </tr>
</table>

<input type="hidden" value="1" name="next">

<?

}

function edit_scheduled($sched_id) {

$query = dbRead("select * from scheduled where ID = '$_GET[ID]'");
$row = mysql_fetch_assoc($query);

$datearray = explode("-", $row[startdate]);

?>
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="620" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td width="100%" colspan="2" align="center" class="Heading"><?= get_page_data("3") ?></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("4") ?>:</td>
        <td bgcolor="#FFFFFF"><input type="text" name="from_memid" size="10" value="<?= $row[from_memid] ?>"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("5") ?>:</td>
        <td bgcolor="#FFFFFF"><input type="text" name="to_memid" size="10" value="<?= $row[to_memid] ?>"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("6") ?>:</td>
        <td bgcolor="#FFFFFF">
        <select name="sd_day">
         <option <? if($datearray[2] == "1") { echo "selected "; } ?>value="1">1</option>
         <option <? if($datearray[2] == "2") { echo "selected "; } ?>value="2">2</option>
         <option <? if($datearray[2] == "3") { echo "selected "; } ?>value="3">3</option>
         <option <? if($datearray[2] == "4") { echo "selected "; } ?>value="4">4</option>
         <option <? if($datearray[2] == "5") { echo "selected "; } ?>value="5">5</option>
         <option <? if($datearray[2] == "6") { echo "selected "; } ?>value="6">6</option>
         <option <? if($datearray[2] == "7") { echo "selected "; } ?>value="7">7</option>
         <option <? if($datearray[2] == "8") { echo "selected "; } ?>value="8">8</option>
         <option <? if($datearray[2] == "9") { echo "selected "; } ?>value="9">9</option>
         <option <? if($datearray[2] == "10") { echo "selected "; } ?>value="10">10</option>
         <option <? if($datearray[2] == "11") { echo "selected "; } ?>value="11">11</option>
         <option <? if($datearray[2] == "12") { echo "selected "; } ?>value="12">12</option>
         <option <? if($datearray[2] == "13") { echo "selected "; } ?>value="13">13</option>
         <option <? if($datearray[2] == "14") { echo "selected "; } ?>value="14">14</option>
         <option <? if($datearray[2] == "15") { echo "selected "; } ?>value="15">15</option>
         <option <? if($datearray[2] == "16") { echo "selected "; } ?>value="16">16</option>
         <option <? if($datearray[2] == "17") { echo "selected "; } ?>value="17">17</option>
         <option <? if($datearray[2] == "18") { echo "selected "; } ?>value="18">18</option>
         <option <? if($datearray[2] == "19") { echo "selected "; } ?>value="19">19</option>
         <option <? if($datearray[2] == "20") { echo "selected "; } ?>value="20">20</option>
         <option <? if($datearray[2] == "21") { echo "selected "; } ?>value="21">21</option>
         <option <? if($datearray[2] == "22") { echo "selected "; } ?>value="22">22</option>
         <option <? if($datearray[2] == "23") { echo "selected "; } ?>value="23">23</option>
         <option <? if($datearray[2] == "24") { echo "selected "; } ?>value="24">24</option>
         <option <? if($datearray[2] == "25") { echo "selected "; } ?>value="25">25</option>
         <option <? if($datearray[2] == "26") { echo "selected "; } ?>value="26">26</option>
         <option <? if($datearray[2] == "27") { echo "selected "; } ?>value="27">27</option>
         <option <? if($datearray[2] == "28") { echo "selected "; } ?>value="28">28</option>
         <option <? if($datearray[2] == "29") { echo "selected "; } ?>value="29">29</option>
         <option <? if($datearray[2] == "30") { echo "selected "; } ?>value="30">30</option>
         <option <? if($datearray[2] == "31") { echo "selected "; } ?>value="31">31</option>
        </select>

        <select name="sd_month">
         <option <? if($datearray[1] == "1") { echo "selected "; } ?>value="1">Jan</option>
         <option <? if($datearray[1] == "2") { echo "selected "; } ?>value="2">Feb</option>
         <option <? if($datearray[1] == "3") { echo "selected "; } ?>value="3">Mar</option>
         <option <? if($datearray[1] == "4") { echo "selected "; } ?>value="4">Apr</option>
         <option <? if($datearray[1] == "5") { echo "selected "; } ?>value="5">May</option>
         <option <? if($datearray[1] == "6") { echo "selected "; } ?>value="6">Jun</option>
         <option <? if($datearray[1] == "7") { echo "selected "; } ?>value="7">Jul</option>
         <option <? if($datearray[1] == "8") { echo "selected "; } ?>value="8">Aug</option>
         <option <? if($datearray[1] == "9") { echo "selected "; } ?>value="9">Sep</option>
         <option <? if($datearray[1] == "10") { echo "selected "; } ?>value="10">Oct</option>
         <option <? if($datearray[1] == "11") { echo "selected "; } ?>value="11">Nov</option>
         <option <? if($datearray[1] == "12") { echo "selected "; } ?>value="12">Dec</option>
        </select>

        <select name="sd_year">
         <option <? if($datearray[0] == "2002") { echo "selected "; } ?>value="2002">2002</option>
         <option <? if($datearray[0] == "2003") { echo "selected "; } ?>value="2003">2003</option>
         <option <? if($datearray[0] == "2004") { echo "selected "; } ?>value="2004">2004</option>
         <option <? if($datearray[0] == "2005") { echo "selected "; } ?>value="2005">2005</option>
         <option <? if($datearray[0] == "2006") { echo "selected "; } ?>value="2006">2006</option>
         <option <? if($datearray[0] == "2007") { echo "selected "; } ?>value="2007">2007</option>
         <option <? if($datearray[0] == "2008") { echo "selected "; } ?>value="2008">2008</option>
         <option <? if($datearray[0] == "2009") { echo "selected "; } ?>value="2009">2009</option>
         <option <? if($datearray[0] == "2010") { echo "selected "; } ?>value="2010">2010</option>
         <option <? if($datearray[0] == "2011") { echo "selected "; } ?>value="2011">2011</option>
        </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("7") ?>:</td>
        <td bgcolor="#FFFFFF"><select size="1" name="frequency">
        <option <? if($row[frequency] == "weekly") { echo "selected "; } ?>value="weekly"><?= get_page_data("15") ?></option>
        <option <? if($row[frequency] == "fortnightly") { echo "selected "; } ?>value="fortnightly"><?= get_page_data("16") ?></option>
        <option <? if($row[frequency] == "monthly") { echo "selected "; } ?>value="monthly"><?= get_page_data("17") ?></option>
        <option <? if($row[frequency] == "yearly") { echo "selected "; } ?>value="yearly">Yearly</option>
        </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_word("61") ?>:</td>
        <td bgcolor="#FFFFFF"><input type="text" name="amount" size="15" value="<?= $row[amount] ?>"></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("8") ?>:</td>
        <td bgcolor="#FFFFFF" valign="middle"><input type="text" name="timesleft" size="15" value="<?= $row[timesleft] ?>">&nbsp;&nbsp;[ <?= get_page_data("10") ?>. ]</td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%"><?= get_page_data("9") ?>:</td>
        <td bgcolor="#FFFFFF">
        <select size="1" name="active">
        <option <? if($row[active] == "Yes") { echo "selected "; } ?>value="Yes">Yes</option>
        <option <? if($row[active] == "No") { echo "selected "; } ?>value="No">No</option>
        </select></td>
      </tr>
      <tr>
        <td class="Heading2" align="right" width="30%">&nbsp;</td>
        <td bgcolor="#FFFFFF" align="right">
        <button name="ChangeTransfer" style="width: 124; height: 25" type="submit">
        <font face="Verdana"><?= get_page_data("12") ?></font></button></td>
      </tr>
      </table>
    </td>
  </tr>
</table>

<input type="hidden" value="1" name="editsched2">

<?

}
