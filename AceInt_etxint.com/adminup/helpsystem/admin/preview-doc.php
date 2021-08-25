<?php
/*
	Help Document Renderer
	============================================================================
	This is an editer for an online help system

	----------------------------------------------------------------------------
	Author: 	Josiah Truasheim
	Date:   	22.11.2004
	Version:    Development
	============================================================================
*/
mysql_connect('db-04.server-logon.com','josiah','true10');
mysql_select_db('snyper');

$tblContent = 'help_doc_components';
$tblDocument = 'help_documents';

$documentId = (int) $_GET['documentId'];
$isPreview = ($_GET['isPreview'] ? true : false);

$documentId = (int) $_GET['documentId'];
if($documentId !== 0){
	$docQuerySql = "SELECT * FROM $tblDocument WHERE DocumentID = $documentId";
	$docQuery = mysql_query($docQuerySql);
	if(@mysql_num_rows($docQuery)){
	    $docRow = mysql_fetch_assoc($docQuery);
	}else{
		exit("No Document");
	}
}else{
	exit('No Document');
}

function menu($id){
	global $documentId;
	global $tblContent;
	$c = '';

	$sql = "SELECT * FROM $tblContent WHERE DocumentID = $documentId AND ParentID = $id";
	$query = mysql_query($sql);
	if(@ mysql_num_rows($query)){
		$c .= '<ol class="menu">';
			while($row = mysql_fetch_assoc($query)){
				$c .= '<a href="#'.$row['ComponentID'].'"><li>'.$row['ComponentTitle'].'</li></a>';
				$c .= menu($row['ComponentID']);
			}
		$c .= '</ol>';
		return $c;
	}else{
	    return '';
	}
}

function subComponents($id){
	global $documentId;
	global $tblContent;
	$c = '';

	$sql = "SELECT * FROM $tblContent WHERE DocumentID = $documentId AND ParentID = $id";
	$query = mysql_query($sql);
	if(@ mysql_num_rows($query)){
		$c .= '<div class="subComponents">';
			while($row = mysql_fetch_assoc($query)){
			    $c .= '<a name="'.$row['ComponentID'].'"></a>';
				$c .= '<h3>'.$row['ComponentTitle'].'</h3>';
				if(strlen(trim($row['ComponentContents']))){
					$c .= '<p>'.stripslashes($row['ComponentContents']).'</p>';
				}
				if($row['TopLink']){
					$c .= '<a href="#" class="back-to-top">Back to the top</a>';
				}
				$c .= subComponents($row['ComponentID']);
			}
		$c .= '</div>';
		return $c;
	}else{
	    return '';
	}
}

$file = fopen(($isPreview ? 'previewDocuments/' : '../').$docRow['DocumentFilename'].'.htm','w');
foreach(@file('templates/default.htm') as $l){
	if(strpos($l,'[tpl:title]') !== false){
		$l = str_replace('[tpl:title]',$docRow['DocumentTitle'],$l);
	}elseif(strpos($l,'[tpl:menu]') !== false){
		$l = str_replace('[tpl:menu]',menu(0),$l);
	}elseif(strpos($l,'[tpl:date]') !== false){
		$l = str_replace('[tpl:date]','Last Updated: '.date('d-m-Y'),$l);
	}elseif(strpos($l,'[tpl:content]') !== false){
		$l = str_replace('[tpl:content]',subComponents(0),$l);
	}
	fwrite($file,$l);
}
fclose($file);

header('Location: http://admin.ebanctrade.com/adminup/helpsystem/'.($isPreview ? 'admin/previewDocuments/' : '').$docRow['DocumentFilename'].'.htm');
?>
