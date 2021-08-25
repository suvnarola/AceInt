<?
include_once("/home/etxint/admin.etxint.com/includes/global.php");    //  Needs this.
include("/home/etxint/admin.etxint.com/includes/modules/class.emailSystem.php");    // Needs this.
//include("/home/etxint/admin.etxint.com/includes/modules/class.emailSystem_members.php");    // Needs this.
include("/home/etxint/admin.etxint.com/includes/modules/class.paging.php");                   // Needs this.
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
</head>
<body>
<?
$emailAdmin = new emailSystem();
$emailAdmin->displayList();
?>
</body>
</html>
