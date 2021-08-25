<?php
mysql_connect('db-04.server-logon.com','josiah','true10');
mysql_select_db('snyper');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>E Banc Trade Help Documents</title>
<link href="hm-i.screen.css" media="screen,*" rel="stylesheet" type="text/css" />
<link href="hm-i.print.css" media="print" rel="stylesheet" type="text/css" />
<style type="text/css">
.tab-i {
	margin: 0px;
	float: right;
	position: relative;
	text-align: center;
	vertical-align: middle;
	font-size: 10px;
	font-weight: bold;
	color: #003366;
	border: 1px solid #607F9F;
	background-color: #CCD6E0;
	background-image: url('admin/images/tab-i.gif');
	background-repeat: repeat-x; padding-left:10px; padding-right:10px; padding-top:4px; padding-bottom:4px; background-position-y:50%
}
.doc-search {
	margin: 15px 15px 15px 15px;
	padding: 5px 5px 5px 5px;
	border: 1px solid #8099B2;
	background-color: #CCD6E0;
}
	.doc-search label {
		font-size: 10px;
		font-weight: bold;
	}
	.doc-search label input {
		font-size: 10px;
		font-family: verdana;
		width: 510px;
	}

.doc-summary {
	margin: 10px 10px 10px 10px;
	padding: 5px 5px 5px 5px;
	border: 1px solid #BFCCD8;
	background-color: #E5EAEF;
    float: left;
	position: relative;
	width: 500px;
}
	.doc-icon {
	    float: left;
	    margin: 5px 5px 5px 0px;
	}

	.doc-info {
	    float: left;
	    margin: 5px 5px 5px 5px;
		width: 440px;
	}
	    .doc-info .title {
			font-weight: bold;
	    }
	    .doc-info .description {
			font-size: 10px;
	    }
.content {
	padding-bottom: 20px;
	float: left;
}
</style>
</head>

<body>
<div class="content">
	<h1 class="doc-title"><span>Document Index</span></h1>
	<form class="doc-search">
	    <label>Search Text:<br />
		<input type="text" value="<?= $_GET['search']; ?>" name="search" size="20" /></label>
		<a class="tab-i" href="javascript:document.forms[0].submit();">Search &raquo;</a>
	</form>
<?php

if(strlen($_GET['search'])){
	$where = 'WHERE Keywords LIKE \'%'.addslashes($_GET['search']).'%\' OR Description LIKE \'%'.addslashes($_GET['search']).'%\' OR DocumentTitle LIKE \'%'.addslashes($_GET['search']).'%\' ';
}else{
	$where = '';
}

$sql = "SELECT * FROM help_documents $where ORDER BY DocumentTitle";
$query = mysql_query($sql);
if($query){
    while($row = mysql_fetch_assoc($query)){
        if(file_exists($row['DocumentFilename'].'.htm')){
		?>
	<div class="doc-summary">
	    <a href="<?= $row['DocumentFilename']; ?>.htm" class="doc-icon"><img src="hm-doc-i.png" width="38" height="51" border="0" /></a>
	    <div class="doc-info">
	        <a href="<?= $row['DocumentFilename']; ?>.htm"><div class="title"><?= $row['DocumentTitle']; ?></div></a>
	        <div class="description"><?= $row['Description']; ?></div>
		</div>
	</div>
		<?php
		}
    }
}else{
}
?>
</div>
</body>
</html>