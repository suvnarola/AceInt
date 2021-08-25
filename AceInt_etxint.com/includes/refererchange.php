<?

 /**
  * Referer Change
  *
  * refererchange.php
  * Version 0.1
  */
  
 check_access_level("ErewardsChange");
 
 if($_REQUEST[next]) {
 
  //$Prev_Refer = @mysql_num_rows(@dbRead("select memid, referedby from members where memid='$_REQUEST[memid]'"));
  
  //if(!$Prev_Refer) { add_transaction($_SESSION['Country']['erewardsacc'],$_REQUEST[memid],date("Y-m-d"),"214.50",'Conversion'); }
  
  @dbWrite("delete from erewards where memid='$_REQUEST[memid]'");
  @dbWrite("update members set referedby = '$_REQUEST[referedby]' where memid = '$_REQUEST[memid]'");
  add_referal($_REQUEST[referedby],$_REQUEST[memid]);
  display_form(1);

 } else {
 
  display_form();
 
 }
 
 
 
function display_form($Completed = false) {

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form method="POST" action="body.php?page=refererchange">

<?
 if($Completed) {
?>
   <table width="601" border="0" cellpadding="1" cellspacing="0">
    <tr>
     <td class="Border">
      <table width="100%" border="0" cellpadding="3" cellspacing="0">
       <tr>
        <td width="100%" align="center" class="Heading2">The Referrer Has been Changed Successfully.</td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
<?
 }
?>

<table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="600" id="AutoNumber1">
  <tr>
    <td width="50%" align="right">Account No.:</td>
    <td width="50%"><input type="text" name="memid" size="20"></td>
  </tr>
  <tr>
    <td width="50%" align="right">Referrer:</td>
    <td width="50%"><input type="text" name="referedby" size="20"></td>
  </tr>
  <tr>
    <td width="50%">&nbsp;</td>
    <td width="50%"><input type="submit" value="Submit" name="blah"></td>
  </tr>
</table>

<input type="hidden" name="next" value="1">

</form>

</body>

</html>
<?

}