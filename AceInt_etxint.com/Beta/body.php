<? header("cache-control: public"); ?>
<? include_once("includes/global.php"); ?>
<? $time_start = getmicrotime(); ?>
<? $IncludedRedirect = true; ?>
<? require("includes/redirect.php"); ?>

<?
 if($_REQUEST['page'] != "member_edit") {
 ?>
<html>
<head>
<title>E Banc Trade - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=<? print which_charset($_REQUEST['page']); ?>">
<LINK REL="STYLESHEET" type="text/css" href="includes/styles.css">
<script language="javascript" type="text/javascript" src="includes/default.js?cache=no">
</script>
</head>
<?
 }
 ?>
<? if(!$_REQUEST['ChangeMargin']) { ?>
<body bgcolor="#FFFFFF" topmargin="10" leftmargin="0">
<? } else { ?>
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0">
<? } ?>
<? if(!$_REQUEST['NoTable']) { ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"></td>
    <td valign="top"><? if(!$_REQUEST['ChangeMargin']) { ?><img border="0" src="images/layout_spacer.gif" width="10" height="10"> <? } ?></td>
    <td valign="top" width="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">
          </td>
        </tr>
        <tr>
          <td width="100%">
<? } ?>
<!-- START MAIN BODY -->

<?

if($_REQUEST['page']) {

 include("includes/".addslashes($_REQUEST['page']).".php");

} else {

 include("includes/mem_search.php");

}

?>

<!-- END MAIN BODY -->
<? if(!$_REQUEST['NoTable']) { ?>
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
<? } ?>

<!-- Start Debuging -->
<? //if(checkmodule("SuperUser")) { dump_session(); } ?>
<!-- End Debuging -->
</body>

</html>
<?

$time_end = getmicrotime();
$time = number_format($time_end - $time_start,3);
print "<!-- Render Time: $time seconds :: Total DB Queries: $DB_Count :: Session Size: ".get_session_size()." -->";
?>
