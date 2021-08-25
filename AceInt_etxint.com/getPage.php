<?php

	/**
	 * PAge to get other pages out of the system.
	 *
	 * @package E Banc Administration Site
	 * @author Antony Puckey
	 * @copyright Copyright 2005, RDI Host Pty Ltd
	 *
	 */

	header("cache-control: public");

	include("includes/ebancAdminSessions.php");
	include("includes/modules/class.ebancSuite.php");
	//require("includes/redirect.php");

	$ebancAdmin = new ebancSuite();

	$timeStart = $ebancAdmin->getMicrotime();


 if($_REQUEST['page'] != "member_edit") {
 ?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>E Banc Trade - Administration</title>

<LINK REL="STYLESHEET" type="text/css" href="includes/styles.css">
<script language="javascript" type="text/javascript" src="includes/default.js?cache=no">
</script>
</head>
<?php
 }
 ?>
<?php if(!$_REQUEST['ChangeMargin']) { ?>
<body class="greyBackground" topmargin="10" leftmargin="0">
<?php } else { ?>
<body class="greyBackground" topmargin="0" leftmargin="0">
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

			<?php

			if($_REQUEST['page']) {

			 include("includes/pages/".addslashes($_REQUEST['page']).".php");

			} else {

			 include("includes/pages/mem_search.php");

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

</body>

</html>
<?php

	$timeEnd = $ebancAdmin->getMicrotime();

	$timeFull = number_format($timeEnd - $timeStart,3);
	print "<!-- Render Time: " . $timeFull . " seconds :: Total DB Queries: " . $ebancAdmin->dbCount . " :: Session Size: " . $ebancAdmin->getSessionSize() . " -->";

?>
