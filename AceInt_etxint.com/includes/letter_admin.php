<html>
<head>
<title>Report - Accounts</title>
<meta http-equiv='Content-Type' content='text/html; charset=<?= which_charset($_REQUEST['page']) ?>'>
</head>
<body>
<?
if(!checkmodule("SuperUser")) {
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

?>

<form method="POST" action="body.php?page=letter_admin&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>" name="frm">

<?

// Some Setup.

$time_start = getmicrotime();
$tabarray = array('Edit Letter','Add Letter');

// Do Tabs if we need to.

 tabs($tabarray);

if($_GET[tab] == "Edit Letter") {

 if($_REQUEST['Letter']) {

  update_letter($_REQUEST['Letter']);

 } else {

  edit_letter();

 }

} elseif($_GET[tab] == "Add Letter") {

  add_letter();

}

?>

</form>

<?
function edit_letter()  {

 if($_REQUEST['update'])  {

   $SQLQueryo = dbRead("select * from standard_letters where fieldid = '".$_REQUEST['fieldid']."'", "ebanc_letters");
   $rowo = mysql_fetch_assoc($SQLQueryo);

   dbWrite("update standard_letters set title = '".addslashes(encode_text2($_REQUEST['subject']))."', letter = '".addslashes(encode_text2($_REQUEST['letter_data']))."', CID = '".$_REQUEST['CID']."', letter_no = '".$_REQUEST['letter_no']."', l_display = '".$_REQUEST['ldis']."' where fieldid = '".$_REQUEST['fieldid']."'","ebanc_letters");

   if(encode_text2($_REQUEST['subject']) != $rowo['title'])  {
      $logdata[subject] = array($rowo['title'],encode_text2($_REQUEST['subject']));
   }

   if(encode_text2($_REQUEST['letter_data']) != $rowo['letter'])  {
      $logdata[letter] = array($rowo['letter'],encode_text2($_REQUEST['letter_data']));
   }

  add_kpi2(6,$_REQUEST['letter_no'],'0',$_REQUEST['CID'],$logdata);

 }

 if(!$_REQUEST[countryID])  {
   $_REQUEST[countryID] = $_SESSION[User][CID];
   $_REQUEST[search] = "search";
 }
  ?>
 <body>
 <form method="post" action="body.php?page=UserManagement">
 <table width="600" border="0" cellpadding="1" cellspacing="0">
  <tr>
   <td class="Border">
   <table width="600" border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td colspan="2" align="center" class="Heading">Country Select</td>
    </tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST['countryID'],'All Countries');
          ?>
      </td>
    </tr>
    <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="Search" name="search">&nbsp;</td>
    </tr>
   </table>
   </td>
  </tr>
 </table>

 <input type="hidden" name="search" value="1">

 </form>
 <?
 if($_REQUEST[search]) {

  if($_REQUEST[countryID])  {
    $searchCID = $_REQUEST[countryID];
  } else {
    $searchCID = "%";
  }

  $SQLQuery = dbRead("select * from standard_letters where CID = '".$_REQUEST[countryID]."' order by letter_no", "ebanc_letters");
  ?>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="600" id="AutoNumber1">
   <tr>
    <td width="100%" class="Border">
     <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
     <tr>
       <td colspan="5" class="Heading" align="center">Edit Letter</td>
      </tr>
      <tr>
       <td nowrap class="Heading2" width="60">Letter ID</td>
       <td class="Heading2" width="90%">Subject</td>
       <td class="Heading2" align="left">CID</td>
       <td class="Heading2" align="left">On/Off</td>
       <td class="Heading2" align="left">EDIT</td>
      </tr>
      <?
       $CID = "";
       while($row = mysql_fetch_assoc($SQLQuery)) {

         if($row['l_display'] == 1){
		   $st = "Hidden";
		 } else {
		   $st = "<b>"."Visable"."</b>";
		 }

        ?>
        <tr bgcolor="#FFFFFF">
         <td nowrap width="60"><?= $row['letter_no'] ?></td>
         <td width="90%"><?= $row['title'] ?></td>
         <td align="right"><?= $row['CID'] ?></td>
         <td align="right"><?= $st ?></td>
         <td align="left"><a href="body.php?page=letter_admin&EditUser=true&Letter=<?= $row['fieldid'] ?>&tab=Edit Letter" class="nav">EDIT</a></td>
        </tr>
        <?
       }
      ?>
     </table>
    </td>
   </tr>
  </table>
<?
 }
}

function update_letter()  {

$SQLQuery = dbRead("select * from standard_letters where fieldid = '".$_REQUEST[Letter]."'", "ebanc_letters");
$row = mysql_fetch_assoc($SQLQuery)
?>
 <body>
 <form method="post" action="body.php?page=UserManagement">
<table border="0" cellpadding="1" cellspacing="1" width="639">
 <tr>
  <td class="Border">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2">Edit Standard Letter</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600">
	     <table width="100%" border="0" cellpadding="3" cellspacing="0" >
		  <tr>
		   <td align="right" valign="middle" class="Heading2" width="30%">Letter No: </td>
		   <td><input type="text" name="letter_no" value="<?= $row[letter_no] ?>" size="30"></td>
		  </tr>
		  <tr>
       		<td bgcolor="#FFFFFF" align="left"><select name="ldis">
					<option selected value="0">Visable</option>
					<option value="1">Hidden</option>
					</select>
			</td>
		  </tr>
    	  <tr>
           <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
           <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from country order by name");
            form_select('CID',$sql_query,'name','countryID',$row['CID']);

           ?>
           </td>
          </tr>
          <tr>
		   <td align="right" valign="middle" class="Heading2" width="30%">Subject: </td>
		   <td><input type="text" name="subject" size="30" value="<?= $row['title'] ?>"></td>
		  </tr>
         </table>
        Text for Email:<br>
        &nbsp;<textarea rows="25" name="letter_data" cols="70"><?= $row['letter'] ?></textarea><p>
        <input type="submit" name="b1"  value="Continue"></td>
	</tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="fieldid" value="<?= $_REQUEST[Letter] ?>">
<input type="hidden" name="update" value="1">
</form>

<?

}

function add_letter()  {

 if($_REQUEST['update'])  {

   dbWrite("insert into standard_letters (title,letter,CID,letter_no) values ('".addslashes(encode_text2($_REQUEST['subject']))."','".addslashes(encode_text2($_REQUEST['letter_data']))."','".$_REQUEST['CID']."','".$_REQUEST['letter_no']."')","ebanc_letters",true);

 }

?>

<form method="POST" action="body.php?page=letter_admin" name="am">
<table border="0" cellpadding="1" cellspacing="1" width="639">
 <tr>
  <td class="Border">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2" colspan="2">Create Standard Letters</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF" width="600">
	     <table width="100%" border="0" cellpadding="3" cellspacing="0" >
		  <tr>
		   <td align="right" valign="middle" class="Heading2" width="30%">Letter No: </td>
		   <td><input type="text" name="letter_no" value="<?= $row[letter_no] ?>" size="30"></td>
		  </tr>
    	  <tr>
           <td align="right" valign="middle" class="Heading2" width="30%"><b>Country:</b></td>
           <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from country order by name");
            form_select('CID',$sql_query,'name','countryID',$row['CID']);

           ?>
           </td>
          </tr>
          <tr>
		   <td align="right" valign="middle" class="Heading2" width="30%">Subject: </td>
		   <td><input type="text" name="subject" size="30" value="<?= $row['title'] ?>"></td>
		  </tr>
         </table>
        Text for Email:<br>
        &nbsp;<textarea rows="25" name="letter_data" cols="70"><?= $row['letter'] ?></textarea><p>
        <input type="submit" name="b1"  value="Continue" ></td>
	</tr>
  </table>
  </td>
 </tr>
</table>
<input type="hidden" name="update" value="1">
</form>
<?}?>