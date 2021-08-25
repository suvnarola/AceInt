<?php
// Help Document Editor
// =============================================================================
// This is an editer for an online help system
//
// -----------------------------------------------------------------------------
// Author: 	Josiah Truasheim
// Date:   	16.11.2004
// Version:    Beta
// =============================================================================


// Connect to the MySQL server and database
mysql_connect('db-04.server-logon.com','josiah','true10');
mysql_select_db('snyper');

// The name of the contents table
$tblContent = 'help_doc_components';
// The name of the document information table
//		This is where all the document information is stored. Things like the
//		description and the keywords are stored here
$tblDocument = 'help_documents';

// This array holds any information that needs to be reported
$report = array();

// Put any status descriptions in here
$statusDsc['pending'] = 'You are viewing a Draft Copy';

// Set the document to an accessable variable
$documentId = (int) $_GET['documentId'];

if($documentId !== 0){
	// Get the document information from the database
	$docQuerySql = "SELECT * FROM $tblDocument WHERE DocumentID = $documentId";
	$docQuery = mysql_query($docQuerySql);
	if(@mysql_num_rows($docQuery)){
	    $docRow = mysql_fetch_assoc($docQuery);
		// Run all the $_POST associated functions
	    runPost();
	}else{
	    // Run the quit function
		quit('Not Found');
	}
}else{
    // Run the quit function
	quit('No Document');
}

// Reports any status changes
if($docRow['Status']!=''){
	$report[] = $statusDsc[$docRow['Status']];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Editing Document: '<?= $docRow['DocumentTitle']; ?>'</title>
<style type="text/css">
body {
	font-family: Verdana;
	font-size: 12px;
	margin: 10px 10px 10px 10px;
}

.container {
	width: 100%;
	min-width: 760px;
	text-align: center;
	position: relative;
	float: left;
}
.subContainer {
	width: 760px;
	text-align: left;
	position: relative;
}

.header {
	background-color: #CCD6E0;
	padding: 5px 5px 5px 5px;
	position: relative;
	float: left;
	width: 99%;
}

	.header input {
	    width: 99%;
	    border: 1px solid #8099B2;
	    margin: 1px 1px 1px 1px;
	    font-size: 12px;
	}
	
	.header textarea {
	    width: 99%;
	    height: 50px;
	    font-family: Arial;
	    border: 1px solid #8099B2;
	    margin: 1px 1px 1px 1px;
	    font-size: 12px;
	}

.contentsContainer {
	border: 1px solid #CCD6E0;
	background-color: #E5EAEF;
	margin-top: 30px;
	padding: 4px 4px 4px 4px;
	font-size: 10px;
	font-weight: 10px;
	float: left;
	width: 99%;
}
	.contentsContainer img {
	    margin-left: 3px;
	}
	
	.contentsContainer li {
	    padding-top: 3px;
	    padding-bottom: 3px;
	    margin-top: 5px;
	    margin-bottom: 15px;
	    border-top: 1px dotted #8099B2;
	    border-bottom: 1px dotted #8099B2;
	}
		.contentsContainer li li {
			padding-top: 3px;
			padding-bottom: 0px;
			margin-top: 3px;
			margin-bottom: 0px;
			border-top: 1px dotted #8099B2;
			border-bottom: 0px none;
		}

.fullWidth {
	font-size: 10px;
	font-weight: bold;
	padding: 1px 1px 1px 1px;
	width: 99%;
	float: left;
}

.halfWidth {
	font-size: 10px;
	font-weight: bold;
	padding: 1px 1px 1px 1px;
	width: 49%;
	float: left;
}
	
.small {
	margin: 1px 1px 1px 1px;
	padding: 0px 0px 0px 0px;
	font-size: 10px;
	font-weight: normal;
}

.pageReport {
	padding: 5px 5px 5px 5px;
	font-size: 10px;
	border: 1px solid #CCCCCC;
	background: #EEEEEE;
}

.header-tabs {
	float: left;
	position: relative;
}

.noShow {
	display: none;
}

.contentForm {
	display: block;
	position: absolute;
	float: right;
	width: 400px;
	right: 3px;
	top: auto;
	padding: 2px 2px 2px 2px;
	border: 1px solid #8099B2;
	background-color: #CCD6E0;
}
	.contentForm .submitBtn {
	    text-align: right;
	}
		.contentForm .submitBtn input {
		    width: 120px;
		}

	.contentForm .closeBtn {
	    font-size: 10px;
	    font-weight: bold;
		color: maroon;
		float: right;
		text-decoration: none;
		margin: 2px 2px 2px 2px;
	}
	
	.contentForm input, .contentForm textarea {
		width: 396px;
	    font-size: 10px;
	    font-weight: normal;
	    font-family: Arial;
		text-decoration: none;
		margin: 2px 0px 2px 0px;
	}

	.contentForm textarea {
	    height: 100px;
	}

.tab-i {
	padding: 4px 10px 4px 10px;
	margin: 0px 0px 0px 5px;
	float: right;
	position: relative;
	text-align: center;
	vertical-align: middle;
	font-size: 10px;
	font-weight: bold;
	color: #003366;
	border: 1px solid #607F9F;
	background-color: #CCD6E0;
	background-image: url(images/tab-i.gif);
	background-position: bottom;
	background-repeat: repeat-x;
}
</style>
<script language="javascript" type="text/javascript">
function showContent(id,add){
	if(add == 'add'){
		var cForm = document.getElementById('addEditor'+id);
	}else{
		var cForm = document.getElementById('contentEditor'+id);
	}
	if(cForm.className == 'noShow'){
	    cForm.className = 'contentForm';
	}else{
	    cForm.className = 'noShow';
	}
}

function contentWindow(id){
	var win = window.open('sub-doc.php?componentId='+id,'EditorWindow','width=450,height=362');
	win.window.focus();
	window.onFocus = function(){
		win.window.focus();
	}
}
</script>
<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
<script src="/hardcore/webeditor/webeditor.js"></script>
</head>

<body>
<div class="container"><div class="subContainer">
	    <?php
	    // If there is stuff to report... Report it
	    if(count($report) > 0){
	        ?>
			<p class="pageReport">
			    <?php foreach($report as $r){ ?>
			    <?= $r; ?><br />
			    <?php } ?>
			</p>
	        <?php
	    }
		?>
	<form class="headerContainer" action="<?= $_SERVER['PHP_SELF']; ?>?documentId=<?= $documentId; ?>" method="POST">
	    <div class="header">
	        <label class="halfWidth">Document Title<input onFocus="this.name='DocumentTitle'" id="DocumentTitle" type="text" maxlangth="60" value="<?= $docRow['DocumentTitle']; ?>" \></label>
	        <label class="halfWidth">Filename<input onFocus="this.name='DocumentFilename'" id="DocumentFilename" type="text" maxlength="20" value="<?= $docRow['DocumentFilename']; ?>" \></label>
	        <div class="halfWidth">
				<label for="Keywords">Document Keywords</label><br />
				<p class="small">
					These are the keywords that will indicate the subjects that
					the document relates to.
				</p>
				<textarea onFocus="this.name='Keywords'" id="Keywords"><?= $docRow['Keywords']; ?></textarea>
			</div>
	        <div class="halfWidth">
				<label for="Description">Document Description</label><br />
				<p class="small">
					This is a small description of what material the document
					covers. This will also be able to be searched.
				</p>
				<textarea onFocus="this.name='Description'" id="Description"><?= $docRow['Description']; ?></textarea>
			</div>
	    </div>
	    <div class="header-tabs">
			<a class="tab-i" href="javascript:document.forms[0].submit()">Update &raquo;</a>
			<a class="tab-i" href="preview-doc.php?documentId=<?= $documentId; ?>&isPreview=1" target="_blank">Preview &raquo;</a>
			<a class="tab-i" href="preview-doc.php?documentId=<?= $documentId; ?>" target="_blank">Publish &raquo;</a>
			<?php if(is_file('../'.$docRow['DocumentFilename'].'.htm')){ ?><a class="tab-i" href="edit-doc.php?documentId=<?= $documentId; ?>&unPublish=true">Un-Publish [x]</a><?php } ?>
			<a class="tab-i" href="javascript:if(window.confirm('Are you sure that you want to delete this document?')){window.location='?documentId=<?= $documentId; ?>&delete=true';}">Delete [X]</a>
			<a class="tab-i" href="select-doc.php">&laquo; Change document</a>
		</div>
	</form>
	<div class="contentsContainer">
		<?php
		$t = subComponents(0);
		if(!$t){
			echo 'There are currently no subcomponents in this document';
		}
		?>
		<div><a href="javascript:contentWindow('new&documentId=<?= $documentId; ?>&parentId=0');"><img src="images/btn-addSubsection.gif" width="79" height="12" align="right" border="0" /></a></div>
	</div>
</div></div>
</body>
</html>
<?php

// FUNCTION: subComponents
// =============================================================================
// Author:  Josiah Truasheim
// Version: 1.0
// -----------------------------------------------------------------------------
// This function will fetch all the components that have the specified id as the
// parent of the section. They are displayed for selection for editing.
// -----------------------------------------------------------------------------
// WARNING: This function references itself. If there is a circular reference in
//          id, it will become an infinate loop.
// -----------------------------------------------------------------------------
// Inputs:
//      $id - The id of the component that you would like the subcomponents of
// -----------------------------------------------------------------------------
// Outputs:
//      bool if the component was returned sucessfully
// =============================================================================
function subComponents($id){
	// Get the document ID and the table name from the global variables
	global $documentId;
	global $tblContent;

	// Get the subdocument rows
	$sql = "SELECT * FROM $tblContent WHERE DocumentID = $documentId AND ParentID = $id ORDER BY `Order` ASC";
	$query = mysql_query($sql);
	// Test to see if rows were returned and the query was sucessfull
	if(@ mysql_num_rows($query)){
		?>
		<ol>
			<?php while($row = mysql_fetch_assoc($query)){ ?>
				<li>
				<a href="javascript:if(window.confirm('Are you sure that you want to delete this content?')){window.location='?documentId=<?= $documentId ?>&deleteId=<?= $row['ComponentID']; ?>';}"><img src="images/btn-delete.gif" width="79" height="12" align="right" border="0" /></a>
				<a href="javascript:contentWindow(<?= $row['ComponentID']; ?>)"><img src="images/btn-edit.gif" width="79" height="12" align="right" border="0" /></a>
				<a href="javascript:contentWindow('new&documentId=<?= $documentId; ?>&parentId=<?= $row['ComponentID']; ?>');"><img src="images/btn-addSubsection.gif" width="79" height="12" align="right" border="0" /></a>
				<?= $row['ComponentTitle']; ?>
<?php
// Loop the function for sub-components
subComponents($row['ComponentID']);
?></li>
			<?php } ?>
		</ol>
		<?php
		return true;
	}else{
	    // return that there is nothing here
	    return false;
	}
}



// FUNCTION: runPost
// =============================================================================
// Author:  Josiah Truasheim
// Version: 1.0
// -----------------------------------------------------------------------------
// This function will check for data and instructions that are sent in POST and
// GET.
// =============================================================================
function runPost(){
	// Get the variables that are needed from GLOBAL scope
	global $tblDocument;
	global $tblContent;
	global $documentId;
	global $report;
	global $docRow;
	
	// See if the document is to be deleted
	if($_GET['delete'] == 'true'){
	    // DELETE the document information
		$sql = "DELETE FROM help_documents WHERE DocumentID = $documentId";
		$ok = mysql_query($sql);
		// DELETE the document contents
		$sql = "DELETE FROM help_doc_components WHERE DocumentID = $documentId";
		$ok = (mysql_query($sql) && $ok);
		// DELETE the preview file of the document
		@ unlink('previewDocuments/'.$docRow['DocumentFilename'].'.htm');
		// DELETE the published file of the document
		@ unlink('../'.$docRow['DocumentFilename'].'.htm');
		if($ok){
		    // if the document was deleted sucessfully, return the user to the
		    // Document selection screen
		    header('Location: select-doc.php');
		}else{
		    // if the document was not deleted sucessfully, tell the user that
		    // the document could not be deleted
			$report[] = 'Could not delete document';
		}
	}elseif($_GET['unPublish']=='true'){
	    // Delete the published files
        $del = @unlink('../'.$docRow['DocumentFilename'].'.htm');
		if($del){
			$report[] = "Document has been Un-Published";
		}else{
			$report[] = "<span style=\"color:red;\">Could not Un-Publish</span>";
		}
	}elseif(is_numeric($_GET['deleteId'])){
	    // Delete a singular component
		$sql = "DELETE FROM $tblContent WHERE ComponentID = {$_GET['deleteId']}";
		$query = mysql_query($sql);
		if($query){
			$report[] = "Draft has been updated";
		}else{
			$report[] = "<span style=\"color:red;\">Could not update</span>";
		}
	}else{
	    // Update the documents description, filename and keyword information
		$uploadFields = array('DocumentTitle','DocumentFilename','Keywords','Description');
		$fields = array();

		foreach($_POST as $key => $value){
		    if(in_array($key,$uploadFields)){
		        $fields[] = "$key = '$value'";
			}
		}

		if(count($fields)){
		    // Upload the information that has been requested
			$sql = "UPDATE $tblDocument SET ".implode(', ',$fields)." WHERE DocumentID = $documentId";
			$query = mysql_query($sql);
			if($query){
				$report[] = "Draft has been updated";
				foreach($_POST as $key => $value){
				    if(in_array($key,$uploadFields)){
				        $docRow[$key] = $value;
					}
				}
			}else{
				$report[] = "<span style=\"color:red;\">Could not update</span>";
			}
		}
	}
}



// FUNCTION: quit
// =============================================================================
// Author:  Josiah Truasheim
// Version: 1.0
// -----------------------------------------------------------------------------
// This function terminates the script that you want to terminate. It will
// display a reason and information if it has been entered.
// -----------------------------------------------------------------------------
// Inputs:
//      $reason - The reason, must be one of a set of reasons outlined in the
//                function.
//      $info - Information about the termination, displayed in a "<pre>" tag.
// -----------------------------------------------------------------------------
// Outputs:
//      Terminates the script by use of an exit(); function.
// =============================================================================
function quit($reason = false,$info = false){
	if($reason != false){
	    // All the reasons for a termination should be listed as case statements
	    // here
	    switch($reason){
	        case 'No Document':
	            echo 'You have not selected a help document';
	            break;
			case 'Not Found':
			    echo 'The document that you selected could not be found';
	    }
	}
	if($info != false){
	    // Output any info that was sent
	    echo "<pre>$info</pre>";
	}
	// Terminate the script
	exit("<!-- Exception Termination -->\r\n");
}

?>
