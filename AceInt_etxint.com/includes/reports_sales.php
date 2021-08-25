<?

if(!checkmodule("SalesPerson")) {

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

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form method="POST" action="body.php?page=reports_sales">

<?

// Main Script here..

if(!$_POST[next]) {

 first_form();
 
} else {

 second_form();

}

?>

</body>

</html>

<?

function first_form() {

?>
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td colspan="2" class="Heading" align="center"><?= get_page_data("1") ?></td>
        </tr>
      <tr>
        <td nowrap class="Heading2" width="75" align="right"><?= get_word("38") ?>:</td>
        <td bgcolor="#FFFFFF"><select name="month">
		  <?

			$startmonth = "02";
			$startyear = "2000";
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
        <td class="Heading2"><?= $row[FieldID] ?></td>
        <td bgcolor="#FFFFFF"><button name="B1" type="submit"><?= get_word("48") ?></button></td>
      </tr>
      </table>
    </td>
  </tr>
</table>

<input type="hidden" value="1" name="next">

<?

}

function second_form() {

?>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="640" id="AutoNumber1">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
      <tr>
        <td colspan="3" class="Heading" align="center"><?= get_page_data("1") ?> - <?= $_POST[month]?></td>
        </tr>
      <tr>
        <td nowrap class="Heading2"><?= get_word("1") ?></td>
        <td class="Heading2" width="100%"><?= get_word("3") ?></td>
        <td class="Heading2"><?= get_word("69") ?></td>
      </tr>
      <?
      
       if($_SESSION['User']['SalesPerson'] == 0) {
       
       ?>
       <tr>
         <td bgcolor="#FFFFFF" colspan="3" align="center"><br><?= get_page_data("2") ?> [<a href="mailto:hq@ebanctrade.com" class="nav">hq@ebanctrade.com]</a>.<br><br></td>
       </tr>
       <?

       } else {
       
        $query = dbRead("select memid, companyname, datejoined from members where salesmanid='".$_SESSION['User']['FieldID']."' and datejoined like '$_POST[month]-%' order by datejoined");
       
        if(@mysql_num_rows($query) == 0) {
       
       ?>
       <tr>
         <td bgcolor="#FFFFFF" colspan="3" align="center"><br><?= get_page_data("3") ?>.<br><br></td>
       </tr>
       <?
       
        }
       
        while($row = @mysql_fetch_assoc($query)) {
       
        $newdate_temp = explode("-", $row[datejoined]);
        $newdate = date("j M Y", mktime(1,1,1,$newdate_temp[1],$newdate_temp[2],$newdate_temp[0]));
       
       ?>
       <tr>
         <td bgcolor="#FFFFFF"><?= $row[memid] ?></td>
         <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[companyname]) ?></td>
         <td bgcolor="#FFFFFF" align="right"><?= $newdate ?></td>  
       </tr>
       <?
      
        }
       
       }
       
      ?>
      </table>
    </td>
  </tr>
</table>

<input type="hidden" value="1" name="next">

<?

}

?>