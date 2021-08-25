<?

if(!checkmodule("Notes")) {

?>

	<table width="601" border="0" cellpadding="1" cellspacing="0">
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

$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_SESSION['User']['NoteType'] == 2) {
  $abc = "notes.type='1' or notes.type='2' ";
} else {
  $abc = "notes.type='1' ";
}

if($_REQUEST['all']) {
 $abc = "notes.type='1' or notes.type='2' ";
} elseif($_REQUEST['all2']) {
 $abc = "notes.type='1' ";
}

if($_SESSION['User']['Area'] == 1 && !$_REQUEST['all3']) {
 $abc .= "or notes.type='4' ";
}

if($_REQUEST['all3']) {
 $abc = "notes.type='3' ";
}

$query3 = dbRead("select companyname from members where memid = '".$_REQUEST['memid']."'");
$row3 = mysql_fetch_assoc($query3);

if($_REQUEST['addnote']) {

$curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);

$newnote = addslashes($_REQUEST['note']);

$reminder = $_REQUEST['year1']."-".$_REQUEST['month1']."-".$_REQUEST['day1'];
if($reminder < $date)  {
  $reminder = "0000-00-00";
}

dbWrite("insert into notes (memid,date,userid,type,reminder,note) values ('".$_REQUEST['memid']."','$curdate','".$_SESSION['User']['FieldID']."','".$_REQUEST['type']."','$reminder','".addslashes(encode_text2($newnote))."')");

 if($_REQUEST['anote']) {
  dbWrite("insert into notes (memid,date,userid,type,reminder,note) values ('".$_REQUEST['anote']."','".$curdate."','".$_SESSION['User']['FieldID']."','".$_REQUEST['type']."','".$reminder."','".addslashes(encode_text2($newnote))."')");
 }

 if($_REQUEST['message_sendto']) {
  $note = $newnote." for Acc No. ".$_REQUEST['memid'];
  $DBDate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
  dbWrite("insert into message_system (Date_Entered,Sender,Receiver,Importance,Message) values ('".$DBDate."','".$_SESSION['User']['FieldID']."','".$_REQUEST['message_sendto']."','1','".addslashes(encode_text2($note))."')", "etxint_ebanc_message");
 }

if(checkmodule("Log")) {
 add_kpi("11",$_REQUEST['memid']);
}

} else {

if(checkmodule("Log")) {
 add_kpi("10",$_REQUEST['memid']);
}


}

if($_REQUEST['updatenote']) {

$newnote1 = addslashes($_REQUEST['note']);
$reminder = $_REQUEST['year1']."-".$_REQUEST['month1']."-".$_REQUEST['day1'];
if($reminder < $date)  {
  $reminder = "0000-00-00";
}

dbWrite("update notes set note='".addslashes(encode_text2($newnote1))."', type='".$_REQUEST['type']."', reminder='$reminder' where FieldID='".$_REQUEST['id']."'");

}

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<table border="0" cellspacing="0" width="500" cellpadding="1">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
  <tr>
    <td width="100%" class="Heading" align="center" colspan="5"><?= $row3[companyname] ?></td>
  </tr>
  <tr>
    <td width="80" class="Heading"><?= get_word("41") ?>:</td>
    <td width="80" class="Heading"><?= get_word("56") ?>:</td>
    <td width="240" class="Heading"><?= get_word("57") ?>:</td>

<?
  $query6 = dbRead("select * from notes where memid='".$_REQUEST['memid']."' and deleted='0' and type='3' order by date DESC");
  $row6 = mysql_fetch_assoc($query6)?>
    <?if($row6) {?><td width="100" class="Heading" align="right"><a href="body.php?page=notes&memid=<?= $_REQUEST['memid'] ?>&all3=true"><?= get_word("169") ?></a></td><?} else {?><td width="100" class="Heading" align="right"> </td><?}?>
    <?if($all) {?><td width="100" class="Heading" align="right"><a href="body.php?page=notes&memid=<?= $_REQUEST['memid'] ?>&all2=true"><?= get_word("170") ?></a></td><?} elseif($_SESSION['User']['NoteType'] == 1 || $_SESSION['User']['NoteType'] ==  3) {?><td width="100" class="Heading" align="right"><a href="body.php?page=notes&memid=<?= $_REQUEST['memid'] ?>&all=true"><?= get_word("171") ?></a></td><?} else {?><td width="100" class="Heading" align="right"></td><?}?>
  </tr>
  </tr>

<?
//$query = mysql_db_query($db, "select * from notes where memid='".$_REQUEST['memid']."' and deleted='0' and ($abc) order by date DESC", $linkid);
//$query = mysql_db_query($db, "select notes.*, Name from notes, tbl_admin_users where (notes.userid = tbl_admin_users.FieldID) and memid='".$_REQUEST['memid']."' and deleted='0' and ($abc) order by date DESC", $linkid);
$query = mysql_db_query($db, "select notes.*, Name from notes, tbl_admin_users where (notes.userid = tbl_admin_users.FieldID) and memid='".$_REQUEST['memid']."' and ($abc) order by date DESC", $linkid);

$foo=0;
while($row = mysql_fetch_assoc($query)) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>
  <tr bgcolor="<?= $bgcolor ?>">
    <td width="80"><?= $row['date'] ?></td>
    <td width="80"><?= $row['Name'] ?></td>
    <?if ($_SESSION['User']['Name'] == $row['Name'] && date("Y-m-d", strtotime($row['date'])) == $date) {?><td colspan="3"><a href="body.php?page=notes&memid=<?= $row['memid'] ?>&ID=<?= $row['FieldID'] ?>"><?= $row['note'] ?></a></td><?} else {?><td colspan="3"><?= $row['note'] ?></td><?}?>
  </tr>
<?

$foo++;

}


?>
</table>
</td>
</tr>
</table>
<br>
<?if($_REQUEST['ID']) {
$query1=dbRead("select * from notes where FieldID = '".$_REQUEST['ID']."' order by date DESC");

$row1 = mysql_fetch_assoc($query1);
$newdate = explode("-", $row1['reminder']);

?>

<form action="body.php" method="GET">
<input type="hidden" name="page" value="notes">
<input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">
<input type="hidden" value="<?= $row1['FieldID'] ?>" name="id">
<table border="0" cellpadding="1" cellspacing="0" width="500">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="3" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td width="100%" colspan="2" class="Heading"><?= get_page_data("1") ?></td>
  <tr>
    <td width="15%" class="Heading2">&nbsp;<td bgcolor="#FFFFFF"><textarea rows="6" cols="50" name="note"><?= $row1['note'] ?></textarea></td>
  <tr>
    <td width="15%" class="Heading2">&nbsp;</td>
    <td class="Heading2">
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td class="Heading2" nowrap align="right"><span lang="en-us"><?= get_word("86") ?>:</span></td>
        <td class="Heading2" nowrap align="left">
        <select size="1" name="type">
        <option <? if($row1['type'] == "1") { echo "selected "; } ?> value="1"><?= get_word("170") ?></option>
        <option <? if($row1['type'] == "2") { echo "selected "; } ?> value="2"><?= get_word("172") ?></option>
        <option <? if($row1['type'] == "3") { echo "selected "; } ?> value="3"><?= get_word("169") ?></option>
		<?if($_SESSION['User']['Area'] == 1) {?>
	      <option <? if($row1['type'] == "4") { echo "selected "; } ?> value="4">HQ Note</option>
		<?}?>
        </select>
        </td>
        <td align="right" class="Heading2"><?= get_word("178") ?>:</td>
        <td class="Heading2">
       <select name="day1">
        <option <? if($newdate[2] == "1") { echo "selected "; } ?>value="01">1</option>
        <option <? if($newdate[2] == "2") { echo "selected "; } ?>value="02">2</option>
        <option <? if($newdate[2] == "3") { echo "selected "; } ?>value="03">3</option>
        <option <? if($newdate[2] == "4") { echo "selected "; } ?>value="04">4</option>
        <option <? if($newdate[2] == "5") { echo "selected "; } ?>value="05">5</option>
        <option <? if($newdate[2] == "6") { echo "selected "; } ?>value="06">6</option>
        <option <? if($newdate[2] == "7") { echo "selected "; } ?>value="07">7</option>
        <option <? if($newdate[2] == "8") { echo "selected "; } ?>value="08">8</option>
        <option <? if($newdate[2] == "9") { echo "selected "; } ?>value="09">9</option>
        <option <? if($newdate[2] == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if($newdate[2] == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if($newdate[2] == "12") { echo "selected "; } ?>value="12">12</option>
        <option <? if($newdate[2] == "13") { echo "selected "; } ?>value="13">13</option>
        <option <? if($newdate[2] == "14") { echo "selected "; } ?>value="14">14</option>
        <option <? if($newdate[2] == "15") { echo "selected "; } ?>value="15">15</option>
        <option <? if($newdate[2] == "16") { echo "selected "; } ?>value="16">16</option>
        <option <? if($newdate[2] == "17") { echo "selected "; } ?>value="17">17</option>
        <option <? if($newdate[2] == "18") { echo "selected "; } ?>value="18">18</option>
        <option <? if($newdate[2] == "19") { echo "selected "; } ?>value="19">19</option>
        <option <? if($newdate[2] == "20") { echo "selected "; } ?>value="20">20</option>
        <option <? if($newdate[2] == "21") { echo "selected "; } ?>value="21">21</option>
        <option <? if($newdate[2] == "22") { echo "selected "; } ?>value="22">22</option>
        <option <? if($newdate[2] == "23") { echo "selected "; } ?>value="23">23</option>
        <option <? if($newdate[2] == "24") { echo "selected "; } ?>value="24">24</option>
        <option <? if($newdate[2] == "25") { echo "selected "; } ?>value="25">25</option>
        <option <? if($newdate[2] == "26") { echo "selected "; } ?>value="26">26</option>
        <option <? if($newdate[2] == "27") { echo "selected "; } ?>value="27">27</option>
        <option <? if($newdate[2] == "28") { echo "selected "; } ?>value="28">28</option>
        <option <? if($newdate[2] == "29") { echo "selected "; } ?>value="29">29</option>
        <option <? if($newdate[2] == "30") { echo "selected "; } ?>value="30">30</option>
        <option <? if($newdate[2] == "31") { echo "selected "; } ?>value="31">31</option>
       </select>
       <select name="month1">
        <option <? if($newdate[1] == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if($newdate[1] == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if($newdate[1] == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if($newdate[1] == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if($newdate[1] == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if($newdate[1] == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if($newdate[1] == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if($newdate[1] == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if($newdate[1] == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if($newdate[1] == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if($newdate[1] == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if($newdate[1] == "12") { echo "selected "; } ?>value="12">12</option>
       </select>

		<?

		$query = get_year_array(1);
	    form_select('year1',$query,'','',$newdate[0]);

	   	?>&nbsp;&nbsp;
        </td>
       </tr>
       <tr>
        <td  class="Heading2" colspan="4" align="right"><button name="updatenote" style="width: 81; height: 20" type="submit">
    <b><font size="1" face="Verdana"><?= get_page_data("4") ?></font></b>
    </button></td>
       </tr>
      </table></td>
  </tr>
<input type="hidden" name="updatenote" value="1">
</table>
</td>
</tr>
</table>

</form>


<?} else {?>
<form action="body.php" method="GET">
<input type="hidden" name="page" value="notes">
<input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">

<table border="0" cellpadding="1" cellspacing="0" width="500">
 <tr>
  <td class="Border">
    <table border="0" cellspacing="0" width="100%" cellpadding="3" style="border-collapse: collapse" bordercolor="#111111">
     <tr>
      <td width="100%" colspan="2" class="Heading"><?= get_page_data("2") ?></td>
     </tr>
     <tr>
      <td width="15%" class="Heading2">&nbsp;</td><td bgcolor="#FFFFFF"><textarea rows="6" cols="50" name="note"></textarea></td>
     </tr>
     <tr>
      <td width="15%" class="Heading2">&nbsp;</td>
      <td class="Heading2">
      <table border="0" cellspacing="0" width="100%" cellpadding="3">
       <tr>
        <td class="Heading2" nowrap align="right"><span lang="en-us"><?= get_word("86") ?>:</span></td>
        <td class="Heading2" nowrap align="left">
        <select size="1" name="type">
        <option <? if($_SESSION['User']['NoteType'] == 1) { echo "selected "; } ?>value="1"><?= get_word("170") ?></option>
        <option <? if($_SESSION['User']['notetype'] == 2) { echo "selected "; } ?>value="2"><?= get_word("172") ?></option>
        <option <? if($_SESSION['User'][NoteType] == "3") { echo "selected "; } ?> value="3"><?= get_word("169") ?></option>
		<?if($_SESSION['User']['Area'] == 1) {?>
	      <option <? if($_SESSION['User'][NoteType] == "4") { echo "selected "; } ?> value="4">HQ Note</option>
		<?}?>
        </select></td>
        <td align="right" class="Heading2"><?= get_word("178") ?>:</td>
        <td class="Heading2">
       <select name="day1">
        <option <? if((date("d")-1) == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if((date("d")-1) == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if((date("d")-1) == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if((date("d")-1) == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if((date("d")-1) == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if((date("d")-1) == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if((date("d")-1) == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if((date("d")-1) == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if((date("d")-1) == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if((date("d")-1) == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if((date("d")-1) == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if((date("d")-1) == "12") { echo "selected "; } ?>value="12">12</option>
        <option <? if((date("d")-1) == "13") { echo "selected "; } ?>value="13">13</option>
        <option <? if((date("d")-1) == "14") { echo "selected "; } ?>value="14">14</option>
        <option <? if((date("d")-1) == "15") { echo "selected "; } ?>value="15">15</option>
        <option <? if((date("d")-1) == "16") { echo "selected "; } ?>value="16">16</option>
        <option <? if((date("d")-1) == "17") { echo "selected "; } ?>value="17">17</option>
        <option <? if((date("d")-1) == "18") { echo "selected "; } ?>value="18">18</option>
        <option <? if((date("d")-1) == "19") { echo "selected "; } ?>value="19">19</option>
        <option <? if((date("d")-1) == "20") { echo "selected "; } ?>value="20">20</option>
        <option <? if((date("d")-1) == "21") { echo "selected "; } ?>value="21">21</option>
        <option <? if((date("d")-1) == "22") { echo "selected "; } ?>value="22">22</option>
        <option <? if((date("d")-1) == "23") { echo "selected "; } ?>value="23">23</option>
        <option <? if((date("d")-1) == "24") { echo "selected "; } ?>value="24">24</option>
        <option <? if((date("d")-1) == "25") { echo "selected "; } ?>value="25">25</option>
        <option <? if((date("d")-1) == "26") { echo "selected "; } ?>value="26">26</option>
        <option <? if((date("d")-1) == "27") { echo "selected "; } ?>value="27">27</option>
        <option <? if((date("d")-1) == "28") { echo "selected "; } ?>value="28">28</option>
        <option <? if((date("d")-1) == "29") { echo "selected "; } ?>value="29">29</option>
        <option <? if((date("d")-1) == "30") { echo "selected "; } ?>value="30">30</option>
        <option <? if((date("d")-1) == "31") { echo "selected "; } ?>value="31">31</option>
       </select>
       <select name="month1">
        <option <? if(date("m") == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m") == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m") == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m") == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m") == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m") == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m") == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m") == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m") == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m") == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m") == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m") == "12") { echo "selected "; } ?>value="12">12</option>
       </select>
		<?

		$query = get_year_array(1);
	    form_select('year1',$query,'','',$newdate[0]);

	   	?>&nbsp;&nbsp;
        </td>
       </tr>
       <tr>
        <td  class="Heading2" colspan="4" align="right"><button name="AddNote" style="width: 81; height: 20" type="submit">
        <b><font size="1" face="Verdana"><?= get_page_data("2") ?></font></b></button></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
  <?
  if(!$_REQUEST['ID']) {
  ?>
  <tr>
   <td bgcolor="#FFFFFF"><b>Send Note To: </b><select size="1" name="message_sendto"><option value="">Forward To</option>
    <?
     $query = dbRead("select Name, Position, FieldID from tbl_admin_users where Name != '' and Suspended !='1' and CID = ".$_SESSION['Country']['countryID']." order by Name");
     while($row = mysql_fetch_assoc($query)) {

      ?>
       <option value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?> (<?= $row['Position'] ?>)</option>
      <?

     }
    ?>
    </select></td>
  </tr>
  <?}?>
  <tr>
   <td bgcolor="#FFFFFF"><b>Add Note to Additional Account: </b><input type="text" name="anote" size="10" maxlength="6"></td>
  </tr>
</table>

<input type="hidden" name="addnote" value="1">


<?}?>



</form>

</body>

</html>