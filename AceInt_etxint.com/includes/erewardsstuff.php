<?

if($_POST[next]) {

 $query = dbRead("select * from members where memid='$_POST[memid]'");
 $row = mysql_fetch_assoc($query);
 
 add_referal($row[referedby],$_POST[memid]);

}

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<form method="POST" action="body.php?page=erewardsstuff">

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="600" id="AutoNumber1">
  <tr>
    <td width="50%">Account Number:</td>
    <td width="50%"><input type="text" name="memid" size="20"></td>
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