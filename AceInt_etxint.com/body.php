<?php header("cache-control: public"); ?>
<?php include_once("includes/global.php"); ?>
<?php $time_start = getmicrotime(); ?>
<?php $IncludedRedirect = true; ?>
<?php require("includes/redirect.php"); ?>

<?php
 if($_REQUEST['page'] != "member_edit") {
 ?>
<html>
<head>
<title>A.C.E. International - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print which_charset($_REQUEST['page']); ?>">
<LINK REL="STYLESHEET" type="text/css" href="includes/styles.css">
<script language="javascript" type="text/javascript" src="includes/default.js?cache=no">
</script>
</head>
<?php
 }
 ?>
<?php if(!$_REQUEST['ChangeMargin']) { ?>
<body bgcolor="#FFFFFF" topmargin="10" leftmargin="0">
<?php } else { ?>
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0">
<?php } ?>
<?php if(!$_REQUEST['NoTable']) { ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"></td>
    <td valign="top"><?php if(!$_REQUEST['ChangeMargin']) { ?><img border="0" src="images/layout_spacer.gif" width="10" height="10"> <?php } ?></td>
    <td valign="top" width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">
          </td>
        </tr>
        <tr>
          <td width="100%">
<?php } ?>
<!-- START MAIN BODY -->

<?

if($_REQUEST['page']) {

 include("includes/".addslashes($_REQUEST['page']).".php");

} else {

 include("includes/mem_search.php");

}

?>

<!-- END MAIN BODY -->
<?php if(!$_REQUEST['NoTable']) { ?>
	  </td>
        </tr>
        <tr>
          <td width="100%">
          </td>
        </tr>
      </table>
    </td>
    <td valign="top"><img border="0" src="images/layout_spacer.gif" width="10" height="10"></td>
  </tr>
</table>
<?php } ?>

<!-- Start Debuging -->
<!-- End Debuging -->
</body>

</html>
<?php

$time_end = getmicrotime();
$time = number_format($time_end - $time_start,3);
print "<!-- Render Time: $time seconds :: Total DB Queries: $DB_Count -->";
?>
