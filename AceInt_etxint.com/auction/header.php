<?
include("includes/global.php");
?>
<html>
<?

// Debug Stuff.

 if($CONFIG[DEBUG]) {
  display_info();
 }

?>
<head>
<meta http-equiv="Content-Language" content="en-au">
<title>Virtual Server Management</title>
<base target="main">
<style>
<!--
td           { font-family: Verdana; font-size: 8pt }
-->
</style>
</head>

<body bgcolor="#314D7B">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td>
    <img border="0" src="images/header_auction_ebanc.gif"></td>
    <td background="images/header_bg.gif" valign="top">
    <img border="0" src="images/header_left.gif" width="14" height="64"></td>
    <td width="100%" background="images/header_bg.gif">&nbsp;</td>
  </tr>
  <tr>
    <td width="100" colspan="3" bgcolor="#94A6BD" height="25">&nbsp;&nbsp;
    <?= $CONFIG[Username] ?></td>
  </tr>
</table>

</body>

</html>
