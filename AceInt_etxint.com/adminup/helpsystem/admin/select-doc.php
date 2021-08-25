<?php
// =============================================================================
// Select Document
// -----------------------------------------------------------------------------
// Author: 	Josiah
// Date: 	2005-02-01
// Version: 1.0
// -----------------------------------------------------------------------------
// This script will list all the help documents so that one can be chosen to
// edit. New documents can also be added from here
// =============================================================================

// Connect to the database
mysql_connect('db-04.server-logon.com','josiah','true10');
mysql_select_db('snyper');

// See if a new document has been requested
if($_GET['newDocument'] == 'true'){
	// Add the new document
	$sql = "INSERT INTO help_documents VALUES ('','new','New Document','','','pending')";
	$query = mysql_query($sql);
	if($query){
	    // Redirect the person to the document that was just inserted
		header('Location: edit-doc.php?documentId='.mysql_insert_id());
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Please Select a Help Document to edit</title>
<style type="text/css">
body {
	font-family: Verdana;
	font-size: 12px;
}
div.container {
	text-align: center;
}
.doc-list {
	width: 760px;
	text-align: left;
	list-style: none;
}
	.doc-list a {
	    float: left;
	    width: 40%;
	    margin: 3px 3px 3px 3px;
		padding: 2px 2px 2px 2px;
		border: 1px solid #CCD6E0;
		border-left: 18px solid #CCD6E0;
		background-color: #E5EAEF;
		color: #000000;
		text-decoration: none;
	}
	.doc-list a:hover {
		border: 1px solid #8099B2;
		border-left: 18px solid #8099B2;
		background-color: #E5EAEF;
	}
	.doc-list a.published {
	    float: left;
	    width: 40%;
	    margin: 3px 3px 3px 3px;
		padding: 2px 2px 2px 2px;
		border: 1px solid lightgreen;
		border-left: 18px solid lightgreen;
		background-color: #E5EAEF;
		color: #000000;
		text-decoration: none;
	}
	.doc-list a.published:hover {
		border: 1px solid green;
		border-left: 18px solid green;
		background-color: #E5EAEF;
	}
</style>
</head>

<body>
<div class="container">
	<ul class="doc-list">
	    <h3>Please select the document that you wish to edit</h3>
	    <?php
		// Fetch the query
		$sql = 'SELECT * FROM help_documents ORDER BY DocumentTitle';
		$query = mysql_query($sql);
		if($query){
		    while($row = mysql_fetch_assoc($query)){
				?>
				<a href="edit-doc.php?documentId=<?= $row['DocumentID']; ?>"<?php if(is_file('../'.$row['DocumentFilename'].'.htm')){ ?> class="published"<? } ?>><li><?= $row['DocumentTitle']; ?></li></a>
				<?php
		    }
		}else{
		}
		?>
		<a href="?newDocument=true"><li>New Document &raquo;</li></a>
	</ul>
</div>
</body>
</html>
