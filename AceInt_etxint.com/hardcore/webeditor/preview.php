<?php include "php5.php"; ?>
<html>
<head>
<title>HardCore Web Content Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Generator" content="HardCore Web Content Editor">
<meta http-equiv="Copyright" content="(C) 2002-2004 - HardCore Internet Ltd. - www.hardcoreinternet.co.uk">
<?php if ($HTTP_POST_VARS["stylesheet"] <> "") { ?><link rel="stylesheet" type="text/css" href="<?php echo $HTTP_POST_VARS["stylesheet"] ?>" /><?php } ?>
</head>
<body style="margin: 0px;">
<?php echo stripslashes($HTTP_POST_VARS["content"]) ?>
</body>
</html>
