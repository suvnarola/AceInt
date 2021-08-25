<?

//include("global.php");
include("modules/db.php");

$dbgetclascat = dbRead("select * from members where memid='".$_REQUEST['memid']."'");
$row = mysql_fetch_assoc($dbgetclascat);

$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_REQUEST['add']) {

  $SQL = new dbCreateSQL();
  
  $SQL->add_table("tbl_complaints");
  
  $SQL->add_item("Date", $date);
  $SQL->add_item("EmployID", encode_text2($_REQUEST['employid']));
  $SQL->add_item("AccNo", encode_text2($_REQUEST['accno'])); 
  $SQL->add_item("Madeby", encode_text2($_REQUEST['madeby']));  
  $SQL->add_item("Reason", encode_text2($_REQUEST['reason']));
  $SQL->add_item("Complaint", encode_text2($_REQUEST['complaint']));
  $SQL->add_item("Corrective", encode_text2($_REQUEST['corrective'])); 
  $SQL->add_item("Follow", encode_text2($_REQUEST['follow']));
  $SQL->add_item("Comments", encode_text2($_REQUEST['comments']));   

  $det = "Complaint Made";  
  $id = dbWrite($SQL->get_sql_insert(),"etradebanc",true);
  $details = " <a href=\"javascript:open_win2('body.php?page=complaints&memid=". $_REQUEST['Client'] ."&id=". $id ."');\">Complaint Made</a>"; 
  dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['accno']."','$date','".$_REQUEST['employid']."','1','".addslashes($details)."')");
  
  echo "Your complaint has been Save";
  
}  else  {

 if($_REQUEST['id']) {
  $dbnote = dbRead("select * from tbl_complaints where FieldID=".$_REQUEST['id']."");
  $rownote = mysql_fetch_assoc($dbnote);
 }

 if($_REQUEST['id']) {
   $made = $rownote['Madeby'];
 } else {
   $made = $row['contactname'];
 } 
?>
<html>
<head>
<title>Complaits</title>
</head>
<body>
<form method="POST" action="body.php?page=complaints">
<input type="hidden" name="employid" value="<?= $_SESSION['User']['FieldID'] ?>">
<input type="hidden" name="accno" value="<?= $row['memid'] ?>">
<table width="400" cellpadding="3" cellspacing="0" border="0">
 <tr>
  <td align="center"><a href="javascript:print();" class="nav"><?= get_word("87") ?></a></td>
 </tr>
</table>
<table cellpadding="1" border="0" cellspacing="0" width="400">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td align="center" class="Heading"><b>Complaints Form</b></td>
    </tr>
   </table>
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
	 <td width="75" align="right" class="Heading2" height="1">Employee:</td>
	 <td width="225" align="left" bgcolor="#FFFFFF"><?= $_SESSION['User']['Name'] ?></td>
	<tr>
	 <td width="75" align="right" class="Heading2" height="1">Complaint Made By:</td>
	 <td width="225" align="left" bgcolor="#FFFFFF"><input size="25" type="text" name="madeby" value="<?= $made ?>"></td>
	</tr>
	<tr>
	 <td width="75" align="right" class="Heading2" height="1">Reason for Complaint:</td>
     <td width="225" align="left" bgcolor="#FFFFFF">
          <input type = "radio" name = "reason" id = "1" value = "1" <? if($rownote['Reason'] == 1) { print "checked"; }?>>Can't Spend<br>
          <input type = "radio" name = "reason" id = "2" value = "2" <? if($rownote['Reason'] == 2) { print "checked"; }?>>Not enough business<br>          
          <input type = "radio" name = "reason" id = "3" value = "3" <? if($rownote['Reason'] == 3) { print "checked"; }?>>Inflated pricing<br>	 
          <input type = "radio" name = "reason" id = "4" value = "4" <? if($rownote['Reason'] == 4) { print "checked"; }?>>Other<br>	 
	      <textarea cols="40" rows="5" name="complaint" ><?= $rownote['Complaint'] ?></textarea></td>
	</tr>
	<tr>
	 <td width="75" align="right" class="Heading2" height="1">Corrective action taken:</td>
	 <td width="225" align="left" bgcolor="#FFFFFF"><textarea cols="40" rows="5" name="corrective" ><?= $rownote['Corrective'] ?></textarea></td>
	</tr>
	<tr>
	 <td width="75" align="right" class="Heading2" height="1">Follow up required:</td>
	 <td width="225" align="left" bgcolor="#FFFFFF"><textarea cols="40" rows="5" name="follow" ><?= $rownote['Follow'] ?></textarea></td>
	</tr>
	<tr>
	 <td width="75" align="right" class="Heading2" height="1">Comments:</td>
	 <td width="225" align="left" bgcolor="#FFFFFF"><textarea cols="40" rows="5" name="comments" ><?= $rownote['Comments'] ?></textarea></td>
	</tr>
	<? if(!$_REQUEST['id']) {?>
	<tr>
	 <td width="75" align="right" valign="top" class="Heading2" height="<?= $cellh ?>">&nbsp;</td>
	 <td width="225" align="left" bgcolor="#FFFFFF">&nbsp;<input type="submit" value="Save" name="add"></td>
    </tr>
    <?}?>
   </table>
  </td>
 </tr>
</table>
</forms>
</form>

<?}?>