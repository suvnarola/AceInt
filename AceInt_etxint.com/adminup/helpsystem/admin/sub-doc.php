<?php
mysql_connect('db-04.server-logon.com','josiah','true10');
mysql_select_db('snyper');

if($_GET['componentId'] == 'new' && is_numeric($_GET['documentId']) && is_numeric($_GET['parentId'])){
	$documentId = (int) $_GET['documentId'];
	$parentId = (int) $_GET['parentId'];
	
	$row = @ mysql_fetch_row(mysql_query('SELECT MAX(`Order`) FROM help_doc_components WHERE DocumentID = '.$documentId.' GROUP BY DocumentID'));
	$order = $row[0] + 1;
	
	$sql = 'INSERT INTO help_doc_components (ComponentID,ParentID,DocumentID,`Order`,ComponentTitle,ComponentContents,TopLink,DateModified) VALUES ("",'.$parentId.','.$documentId.','.$order.',"New Component","",0,NOW())';
	$query = mysql_query($sql);
	if($query){
		header('Location: '.$_SERVER['PHP_SELF'].'?componentId='.mysql_insert_id());
		exit();
	}else{
		//echo $sql;
		echo '<script language="javascript">window.close();</script>';
		exit();
	}
}

$componentId = (int) $_GET['componentId'];

$sql = "SELECT * FROM help_doc_components WHERE componentId = $componentId";
$query = mysql_query($sql);
if($query){
	$cRow = mysql_fetch_assoc($query);
	$documentId = $cRow['DocumentID'];
}else{
	exit('Invalid component');
}

if(count($_POST)){
	$set = array();
	
	if(isset($_POST['PositionID'])){
		if($_POST['PositionID'] == 'end'){
			$row = mysql_fetch_row(mysql_query('SELECT MAX(`Order`) FROM help_doc_components WHERE DocumentID = '.$documentId.' GROUP BY DocumentID'));
			$order = $row[0]+1;
		}else{
			$positionId = (int) $_POST['PositionID'];
			$row = mysql_fetch_row(mysql_query('SELECT `Order` FROM help_doc_components WHERE ComponentID = '.$positionId));
			$order = $row[0];
		}
		$sql = 'UPDATE help_doc_components SET `Order` = IF(ComponentID = '.$componentId.', '.$order.', IF(`Order` >= '.$order.', `Order`+1, `Order`)) WHERE DocumentID = '.$documentId;
		$query = mysql_query($sql);
	}
	
	if(is_numeric($_POST['ParentID'])){
		$set[] = 'ParentID = \''.((int) $_POST['ParentID']).'\'';
	}
	
	if($_POST['TopLink'] == 1){
		$set[] = 'TopLink = \'1\'';
	}else{
		$set[] = 'TopLink = \'0\'';
	}

	if($_POST['ComponentTitle']){
		$set[] = 'ComponentTitle = \''.(addslashes($_POST['ComponentTitle'])).'\'';
	}
	
	if($_POST['ComponentContents']){
		$set[] = 'ComponentContents = \''.(addslashes($_POST['ComponentContents'])).'\'';
	}
	
	$sql = 'UPDATE help_doc_components SET '.implode(', ',$set).' WHERE ComponentID = '.$componentId;
	$query = mysql_query($sql);
	echo '<script language="javascript">window.close();</script>';
	exit();
}


$components = array();
$sql = "SELECT ComponentID, ParentID, `Order`, ComponentTitle FROM help_doc_components WHERE DocumentID = $documentId AND ComponentID != $componentId ORDER BY `Order`";
$query = mysql_query($sql);
if($query){
	while($row = mysql_fetch_assoc($query)){
	    if(!is_array($components[$row['ParentID']])){
			$components[$row['ParentID']] = array();
	    }
	    $components[$row['ParentID']][$row['ComponentID']] = $row;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modifying Subdocument</title>
<script language="javascript">
var subcomponents = new Array();
<?php
foreach($components as $k => $arr){
    $a = array();
    foreach($arr as $key => $row){
        $a[] = "Array('$key','Before \'{$row['ComponentTitle']}\'')";
    }
	echo "subcomponents[$k] = Array(".implode(',',$a).");\r\n";
}
?>
function changeSubcomponents(id){
	var selectDst = document.forms[0].PositionID;
	for(var i=0; i < selectDst.length; i++) {
		selectDst.remove(i);
		i--;
	}
	if(subcomponents[id]){
		for(var i=0; i < subcomponents[id].length; i++){
			selectDst[selectDst.length] = new Option(subcomponents[id][i][1], subcomponents[id][i][0]);
		}
	}
	selectDst[selectDst.length] = new Option('at the End', 'end');
	selectDst.selectedIndex = selectDst.length-1;
}
</script>
<link href="/hardcore/webeditor/webeditor.css" rel="stylesheet" type="text/css" />
<script src="/hardcore/webeditor/webeditor.js"></script>
<style type="text/css">
body {
	font-family: verdana;
	font-size: 10px;
	margin: 5px 5px 5px 5px;
	background-color: #C0C0C0;
}
label {
	display: block;
}
select, input {
	font-family: verdana;
	font-size: 10px;
	width: 100%;
}
.topLink {
	float: left;
	margin-top: 10px;
	position: absolute;
	width: 200px;
	vertical-align: center;
}
.topLink label {
	margin-top: 3px;
	float: left;
}
.topLink input {
	float: left;
	width: auto;
}
.submit {
	text-align: right;
	margin-top: 10px;
}
.submit input {
	width: auto;
}
</style>
</head>

<body>
	<form class="contentEditor" name="componentEditor" id="componentEditor" action="<?= $_SERVER['PHP_SELF']; ?>?componentId=<?= $componentId; ?>" method="post">
		<label>Subclass of<br />
		<select onChange="changeSubcomponents(this.value);this.name='ParentID';">
		    <option value="0"<?= (0 == $cRow['ParentID'] ? ' selected' : ''); ?>>Main Document</option>
		<?php
		function sectionSelect($id,$depth){
		    global $components;
			global $cRow;
			
			if(is_array($components[$id])){
				foreach($components[$id] as $key => $row){
					?>
			    <option value="<?= $row['ComponentID']; ?>"<?= ($row['ComponentID'] == $cRow['ParentID'] ? ' selected' : ''); ?>><?= str_repeat('&nbsp;&nbsp;&nbsp;',$depth).'&nbsp;&raquo;'.$row['ComponentTitle']; ?></option>
					<?php
					sectionSelect($row['ComponentID'],$depth+1);
				}
			}
		}
		sectionSelect(0,0);
		?>
		</select></label>
		<label>Position<br />
		<select name="PositionID">
		    <option>No Change</option>
		</select></label>
		<label>Title<br />
		<input type="text" name="ComponentTitle" value="<?= $cRow['ComponentTitle']; ?>" /></label>
		<script>HardCoreWebEditorToolbar(null,'bold underline forecolor insertorderedlist insertunorderedlist createlink mailto anchor unlink insertmedia removeformat viewsource help');</script>
		<script>ComponentContents_editor = new HardCoreWebEditor('/hardcore/webeditor/','php','ComponentContents','<?= addslashes(str_replace("\r","",str_replace("\n","",str_replace("\\","\\\\",stripslashes($cRow['ComponentContents']))))); ?>','','http://172.16.40.237/helpSystem/admin/hardcoreEditor.css',true,'manager','','','','','','100%','200','xhtml','');</script>
		<div class="topLink"><input type="checkbox" name="TopLink" value="1"<?= (($cRow['TopLink'] == 1) ? ' checked' : ''); ?> /><label for="TopLink">Display "Back to top" link?</label></div>
		<div class="submit"><input type="submit" value="Save &raquo;" /></div>
	</form>
</body>
</html>
