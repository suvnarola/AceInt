<?

// Main Start Page.

 include("includes/global.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<style>
<!--
.Border {
	background-color: #304C78; }
.Heading     {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #97A5BB; }
.Heading4     {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #97A5BB;
	border: 1px solid #304C78; }
.Heading2     {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	background-color: #97A5BB; }
.Heading3     {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	background-color: #97A5BB;
	border: 1px solid #304C78; }
.Cell1     {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	background-color: #97A5BB; }
a.nav:link {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	text-decoration: none; }
a.nav:visited {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	text-decoration: none; }
a.nav:hover {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	text-decoration: underline; }
a.nav2:link {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	text-decoration: none; }
a.nav2:visited {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	text-decoration: none; }
a.nav2:hover {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	text-decoration: underline; }
a.nav3:link {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FF0000;
	text-decoration: none; }
a.nav3:visited {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FF0000;
	text-decoration: none; }
a.nav3:hover {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FF0000;
	text-decoration: underline; }
a.ordernav:link {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none; }
a.ordernav:visited {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none; }
a.ordernav:hover {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: underline; }
td {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000; }
-->
</style>
<script LANGUAGE="JavaScript">
<!--

function ConfirmDelete(url,name) {
	bDelete = confirm("Are you sure you wish to delete auction " + name + "?\n\n");
	if (bDelete) {
		location.href = url;
	}
}

function openwindow(URL) {
 var exitwin ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=460,height=340";
 selectedURL = URL;
 remotecontrol=window.open(selectedURL, "exit_console", exitwin);
 remotecontrol.focus();
}

//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="10" leftmargin="0">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">

<!-- START NAVIGATION -->


<!-- END NAVIGATION -->

    </td>
    <td valign="top"><img border="0" src="images/layout_spacer.gif" width="10" height="10"></td>
    <td valign="top" width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">

<!-- START PRE HTML -->


<!-- END PRE HTML -->

          </td>
        </tr>
        <tr>
          <td width="100%">

<!-- START MAIN BODY -->

<?

if($_GET[page]) {

include("includes/$_GET[page].php");

} else {

include("includes/auctions.php");

}

?>

<!-- END MAIN BODY -->

	  </td>
        </tr>
        <tr>
          <td width="100%">

<!-- START PRE HTML -->


<!-- END PRE HTML -->

          </td>
        </tr>
      </table>
    </td>
    <td valign="top"><img border="0" src="images/layout_spacer.gif" width="10" height="10"></td>
  </tr>
</table>

</body>

</html>