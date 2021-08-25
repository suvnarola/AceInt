<?
set_time_limit (600);
include("includes/modules/db.php");
include("functions.php");

$max_file_size = "5242880"; // 5 MB
$max_thumb_width = 290;
$max_thumb_height = 200;

$max_thumb_width_sm = 70;
$max_thumb_height_sm = 70;

$upload_temp_dir = "/tmp";
$upload_cwd = "/virtualadmin.ebanctrade.com/htdocs/uploads";

$_REQUEST['FilePath'] = "/home/etxint/admin.etxint.com/uploads/";

$image_magic_filetypes = Array("avi","bmp","epdf","epi","eps","eps2","eps3","epsf","epsi","ept","fax","gif","jpg","pdf","pict","png","ps","ps2","ps3","psd","tga");

if($_REQUEST[view]) {
	$job = "VIEW";
} else {
	$job = "DEFAULT";
}

if($_REQUEST[job] == "Upload") {
		process_upload();
}

if($_REQUEST[JobType] == "DELETE" && $_REQUEST[SelectedFiles]) {
	$files = explode(",",$_REQUEST[SelectedFiles]);
	foreach($files as $row) {
		if($row) {
			dbWrite("DELETE FROM tbl_file_uploads WHERE FieldID = '$row'", "etxint_email_system");
			dbWrite("DELETE FROM tbl_file_uploads WHERE ParentID = '$row'", "etxint_email_system");
		}
	
	}
}

if($_REQUEST[job] == "Update" && $_REQUEST[view]) { // SAVE CHANGES
	$sql = new dbCreateSQL();
	$sql->add_table("tbl_file_uploads");
	$sql->add_item("FileNameDisplay",$_REQUEST[FileNameDisplay]);
	$sql->add_item("ImageCaption",$_REQUEST[ImageCaption]);
	$sql->add_item("ImageComments",$_REQUEST[ImageComments]);
	
	if($_REQUEST[hit_counter]) {
		$sql->add_item("hit_counter",0);
	}

	$sql->add_where("FieldID = '$_REQUEST[view]'");
	
	dbWrite($sql->get_sql_update(), "etxint_email_system");
	//logmsg("images",$_REQUEST[view],"Image Updated");


}


$_REQUEST[OrderBy] = ($_REQUEST[OrderBy]) ? $_REQUEST[OrderBy] : "FileNameDisplay";
$_REQUEST[OrderByDir] = ($_REQUEST[OrderByDir]) ? $_REQUEST[OrderByDir] : "ASC";

function get_orderby($field,$text,$default = false) {
	if($field == $_REQUEST[OrderBy]) {
		$order = ($_REQUEST[OrderByDir] == "ASC") ? "DESC" : "ASC";
		$link = "<a class=\"white\" href=\"javascript:OrderBy('$field','$order');\">$text <img src=\"../../images/arrow_$order.gif\" border=\"0\" align=\"absmiddle\" width=\"11\" height=\"6\"></a>";
	} else {
		$link = "<a class=\"white\" href=\"javascript:OrderBy('$field', 'ASC');\">$text</a>";
	}
	
	return $link;
}

$_REQUEST[search_opt] = ($_REQUEST[search_opt]) ? $_REQUEST[search_opt] : 1;
?>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Content Management System</title>
<meta http-equiv="imagetoolbar" content="no">
<LINK REL="stylesheet" type="text/css" href="../../stylesheet.css">
<script src="file.js"></script>
<script language="javascript">
<!--

function DeleteUser(id) {
	result = vbconfirmbox("Are you sure you wish to delete the selected files?",32 + 4 + 256);
	if (result==6){
		GetSelected();
		document.admin.JobType.value = "DELETE";
		document.admin.submit();
	}
}
//-->
</script>
<script language="VBScript">
function vbconfirmbox(thismsg,thisstyle)
    vbconfirmbox = MsgBox(thismsg,thisstyle)
End function
</script>
</head>
<body>
<form name="admin" enctype="multipart/form-data" method="POST" onsubmit="return GetSelected()">
<? if($job == "DEFAULT") { ?>
<table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
  <tr>
    <td width="100%" colspan="2" class="heading"><?= $_REQUEST[path] ?>&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF" valign="top">
    <div align="center">
      <center>
      <table border="0" style="border-collapse: collapse" bordercolor="#111111" cellspacing="1">
        <tr>
          <td colspan="2"><b>File Upload<br></b><input type="hidden" name="MAX_FILE_SIZE" value="<?= $max_file_size ?>"><input type="file" name="file" size="20" style="width:265"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Name</b> (Optional)<b><br>
          </b><input type="text" name="file_name" size="32" style="width:265"></td>
        </tr>
        <tr>
          <td colspan="2"><b>Image Caption</b> (Optional)<b><br>
          </b>
          <textarea rows="2" name="file_caption" cols="27" style="width:265"></textarea></td>
        </tr>
        <tr>
          <td colspan="2"><b>Comments</b> (Optional)<b><br>
          </b>
          <textarea rows="2" name="file_comments" cols="27" style="width:265"></textarea></td>
        </tr>
        <tr>
          <td colspan="2"><b>Image Formatter<br>
          <select size="1" name="resize" style="width:265">
          <option value="0">None</option>
          <option value="75|50,96|64,120|80">Highlight (420w x 280h)</option>
          <option value="75|50,96|64,120|80">Property (420w x 280h)</option>
          <option value="96|136">Publication (420w x 595h)</option>
          <option value="90|110,96|117,123|150">Team Profile (250w x 305h)</option>
          </select></b></td>
        </tr>
        <tr>
          <td>
          <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber12">
            <tr>
              <td><input type="checkbox" name="file_zip" value="1"></td>
              <td>Create Zip archive on upload</td>
            </tr>
            <tr>
              <td><input type="checkbox" name="file_unzip" value="1"></td>
              <td nowrap>Unzip archive and process each file</td>
            </tr>
          </table>
          </td>
          <td align="right">
          <input type="submit" value="Upload" name="job"></td>
        </tr>
      </table>
      </center>
    </div>
    </td>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF" valign="top">
    <div align="center">
      <center>
      <table border="0" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber13">
        <tr>
          <td nowrap><b>Search Folder</b> (* wildcard)<b><br>
          </b>
          <input type="text" name="search" size="32" style="width:200" value="<?= $_REQUEST[search] ?>"></td>
          <td nowrap><br>
          <input type="submit" value="Search" name="job" style="width:60"></td>
          </tr>
        <tr>
          <td nowrap colspan="2">
          <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber14">
            <tr>
              <td><?= form_radio("search_opt","1",$_REQUEST[search_opt]," id=\"search_opt_1\" style=\"cursor:hand\"") ?></td>
              <td><label for="search_opt_1" style="cursor:hand">Everything</label></td>
            </tr>
            <tr>
              <td><?= form_radio("search_opt","2",$_REQUEST[search_opt]," id=\"search_opt_2\" style=\"cursor:hand\"") ?></td>
              <td><label for="search_opt_2" style="cursor:hand">Images</label></td>
            </tr>
            <tr>
              <td><?= form_radio("search_opt","3",$_REQUEST[search_opt]," id=\"search_opt_3\" style=\"cursor:hand\"") ?></td>
              <td><label for="search_opt_3" style="cursor:hand">Multimedia</label></td>
            </tr>
            <tr>
              <td><?= form_radio("search_opt","4",$_REQUEST[search_opt]," id=\"search_opt_4\" style=\"cursor:hand\"") ?></td>
              <td><label for="search_opt_4" style="cursor:hand">PDF Documents</label></td>
            </tr>
            <tr>
              <td><?= form_radio("search_opt","5",$_REQUEST[search_opt]," id=\"search_opt_5\" style=\"cursor:hand\"") ?></td>
              <td><label for="search_opt_5" style="cursor:hand">ZIP Archives</label></td>
            </tr>
          </table>
          <br>
&nbsp;</td>
          </tr>
      </table>
      </center>
    </div>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
</table>
<? }
if($job == "VIEW") { 
$file = get_file($_REQUEST[view]);
$download = str_replace("//","/",$CONFIG['leadingdir'] . $file["FilePath"] . $file["FileName"]);
$user = Get_User_Info($file[CreateBy]);

?>
<table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
  <tr>
    <td width="100%" colspan="2" class="heading">File Viewer</td>
  </tr>
  <tr>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
    <div align="center">
      <center>
      <table border="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber6" cellspacing="1">
        <tr>
          <td><b>Name</b> (Optional)<b><br>
          </b>
          <input type="text" name="FileNameDisplay" size="32" style="width:265" value="<?= $file[FileNameDisplay] ?>"></td>
        </tr>
        <tr>
          <td><b>Image Caption</b> (Optional)<b><br>
          </b>
          <textarea rows="2" name="ImageCaption" cols="27" style="width:265"><?= $file[ImageCaption] ?></textarea></td>
        </tr>
        <tr>
          <td><b>Comments</b> (Optional)<b><br>
          </b>
          <textarea rows="2" name="ImageComments" cols="27" style="width:265"><?= $file[ImageComments] ?></textarea></td>
        </tr>
        <tr>
          <td>
          <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="table2">
            <tr>
              <td><input type="checkbox" name="hit_counter" value="1" id="hit_counter"></td>
              <td><label for="hit_counter" style="cursor:hand">Reset Hit Counter</label></td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td>
          <? //if(auth_level("EDIT")) { ?><input type="submit" value="Update" name="job"><? //} ?></td>
        </tr>
      </table>
      </center>
    </div>
    </td>
    <td width="290" bordercolor="#0E1B2A" bgcolor="#FFFFFF" align="center" height="200">
    <? if(file_exists("/home/etxint/admin.etxint.com/uploads/_thumbnails/" . $file["FieldID"] . ".jpg")) { ?>
    <a target="_blank" href="/uploads/<?= $file['FileName'] ?>"><img border="0" src="<?= "uploads/_thumbnails/" . $file["FieldID"] . ".jpg" ?>" alt="Click to open file"></a>
    <? } else { ?>
    No Preview Found
    <? } ?></td>
  </tr>
  <tr>
    <td bordercolor="#0E1B2A" bgcolor="#FFFFFF" colspan="2" align="center">
    <table border="0" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111" width="95%" id="AutoNumber16">
      <tr>
        <td align="right" nowrap><b>Folder:</b></td>
        <td width="33%"><?= $file["FilePath"] ?></td>
        <td align="right" nowrap><b>Created:</b></td>
        <td width="33%"><?= date("jS F Y \a\\t h:i:s A",$file["CreateDate"]) ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><b>Name:</b></td>
        <td width="33%"><?= $file["FileName"] ?></td>
        <td align="right" nowrap><b>Created By:</b></td>
        <td width="33%"><?= $user['FullName'] ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><b>Size:</b></td>
        <td width="33%"><?= GetFileSize($file["FileSize"]) ?></td>
        <td align="right" nowrap>&nbsp;</td>
        <td align="right" nowrap width="33%">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap><b>Full Location:</b></td>
        <td align="left" nowrap colspan="3"><? print $CONFIG['leadingdir'] . $file['FilePath'] . $file['FileName']; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap>&nbsp;</td>
        <td width="33%">&nbsp;</td>
        <td align="right" nowrap><b>Width:</b></td>
        <td width="33%"><?= $file["ImageWidth"] ?> pixels</td>
      </tr>
      <tr>
        <td align="right" nowrap><b>Hit Counter:</b></td>
        <td width="33%"><?= $file["hit_counter"] ?></td>
        <td align="right" nowrap><b>Height:</b></td>
        <td width="33%"><?= $file["ImageHeight"] ?> pixels</td>
      </tr>
    </table>
    </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
</table>
    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="600" id="AutoNumber5">
      <tr>
        <td width="100%">
<table border="0" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td width="100%" class="heading" style="padding: 0" colspan="2">
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="table1">
      <tr>
        <td>&nbsp;</td>
        <td class="heading" style="padding: 4"><?= get_orderby("FileNameDisplay","Name (Children)",1) ?>&nbsp;</td>
      </tr>
    </table>
    </td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("ImageWidth","Width") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("ImageHeight","Height") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("FileSize","Size") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("FileExt","Type") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("CreateDate","Created") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center"><?= get_orderby("hit_counter","Hits") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center"><?= get_orderby("FieldID","ID") ?></td>
    </tr>
  <? $files = get_files($_REQUEST[path],0,30,$_REQUEST[view]);
  
 
  $count = count($files);
  for ($i = 0; $i < $count; $i++) { 
  	$row = $files[$i];
  	$name = ($row[FileNameDisplay]) ? $row[FileNameDisplay] : $row[FileName];
  ?>
  <tr style="cursor:hand" id="<?= $row[FieldID] ?>" onmouseover="setPointer(this, <?= $row[FieldID] ?>, 'over', '#FFFFFF', '#E6E8E9', '#C0C0C0');" onmouseout="setPointer(this, <?= $row[FieldID] ?>, 'out', '#FFFFFF', '#E6E8E9', '#C0C0C0');" onmousedown="setPointer(this, <?= $row[FieldID] ?>, 'click', '#FFFFFF', '#E6E8E9', '#C0C0C0');">
    <td style="padding-left:4; padding-top:4; padding-bottom:4"><a href="<?= $SCRIPT_NAME ?>?page=email_system/defaultnew&tab=tab4&f=<?= $_REQUEST[f] ?>&path=<?= $_REQUEST[path] ?>&view=<?= $row[FieldID] ?>"><img border="0" src="../../images/icons/<?= $row[icon] ?>" width="16" height="16"></a></td>
    <td style="padding-right:4; padding-top:4; padding-bottom:4" width="100%"><a href="<?= $SCRIPT_NAME ?>?page=email_system/defaultnew&tab=tab4&f=<?= $_REQUEST[f] ?>&path=<?= $_REQUEST[path] ?>&view=<?= $row[FieldID] ?>"><?= $name ?></a></td>
    <td nowrap style="padding: 4;" align="right"><?= $row[ImageWidth] ?></td>
    <td nowrap style="padding: 4;" align="right"><?= $row[ImageHeight] ?></td>
    <td nowrap style="padding: 4;" align="right"><?= GetFileSize($row[FileSize]) ?></td>
    <td nowrap style="padding: 4;" align="right"><?= $row[FileExt] ?></td>
    <td nowrap style="padding: 4;"><?= date("d/m/y h:i A",$row[CreateDate]) ?></td>
    <td nowrap style="padding: 4;" align="center"><?= $row[hit_counter] ?></td>
    <td nowrap style="padding: 4;" align="center"><?= $row[FieldID] ?></td>
  </tr>
  <? } ?>
</table>

        </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
</table>
<? } 
if($job == "DEFAULT") { ?>

    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="600" id="AutoNumber5">
      <tr>
        <td width="100%">
<table border="0" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" id="FileList">
  <tr>
    <td width="100%" class="heading" style="padding: 0" colspan="2">
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber11">
      <tr>
        <td><input type="checkbox" name="allbox" value="ON" onclick="SelectAllBoxes()"></td>
        <td class="heading" style="padding: 4"><?= get_orderby("FileNameDisplay","Name",1) ?>&nbsp;</td>
      </tr>
    </table>
    </td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("ImageWidth","Width") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("ImageHeight","Height") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("FileSize","Size") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("FileExt","Type") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6"><?= get_orderby("CreateDate","Created") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center"><?= get_orderby("hit_counter","Hits") ?></td>
    <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center"><?= get_orderby("FieldID","ID") ?></td>
    </tr>
  <? $files = get_files($_REQUEST[path],0,30);
  
  //print get_files_sqlwhere($_REQUEST[path]);
  
  $count = count($files);
  for ($i = 0; $i < $count; $i++) { 
  	$row = $files[$i];
  	$name = ($row[FileNameDisplay]) ? $row[FileNameDisplay] : $row[FileName];
  ?>
  <tr style="cursor:hand" id="<?= $row[FieldID] ?>" onmouseover="setPointer(this, <?= $row[FieldID] ?>, 'over', '#FFFFFF', '#E6E8E9', '#C0C0C0');" onmouseout="setPointer(this, <?= $row[FieldID] ?>, 'out', '#FFFFFF', '#E6E8E9', '#C0C0C0');" onmousedown="setPointer(this, <?= $row[FieldID] ?>, 'click', '#FFFFFF', '#E6E8E9', '#C0C0C0');">
    <td style="padding-left:4; padding-top:4; padding-bottom:4"><a href="<?= $SCRIPT_NAME ?>?page=email_system/defaultnew&tab=tab4&f=<?= $_REQUEST[f] ?>&path=<?= $_REQUEST[path] ?>&view=<?= $row[FieldID] ?>"><img border="0" src="../../images/icons/<?= $row[icon] ?>" width="16" height="16"></a></td>
    <td style="padding-right:4; padding-top:4; padding-bottom:4" width="100%"><a href="<?= $SCRIPT_NAME ?>?page=email_system/defaultnew&tab=tab4&f=<?= $_REQUEST[f] ?>&path=<?= $_REQUEST[path] ?>&view=<?= $row[FieldID] ?>"><?= $name ?></a></td>
    <td nowrap style="padding: 4;" align="right"><?= $row[ImageWidth] ?></td>
    <td nowrap style="padding: 4;" align="right"><?= $row[ImageHeight] ?></td>
    <td nowrap style="padding: 4;" align="right"><?= GetFileSize($row[FileSize]) ?></td>
    <td nowrap style="padding: 4;" align="right"><?= $row[FileExt] ?></td>
    <td nowrap style="padding: 4;"><?= date("d/m/y h:i A",$row[CreateDate]) ?></td>
    <td nowrap style="padding: 4;" align="center"><?= $row[hit_counter] ?></td>
    <td nowrap style="padding: 4;" align="center"><?= $row[FieldID] ?></td>
  </tr>
  <? } ?>
  </table>
    </td>
  </tr>
</table>

    </td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <td width="100%">
    <br>
    <? //if(auth_level("DELETE")) { ?><input type="submit" value="Delete" name="job" onclick="DeleteUser()"><? //} ?></td>
  </tr>
</table>
<? } ?>
<input type="hidden" name="SelectedFiles" value="<?= $_REQUEST[SelectedFiles] ?>">
<input type="hidden" name="OrderBy" value="<?= $_REQUEST[OrderBy] ?>">
<input type="hidden" name="OrderByDir" value="<?= $_REQUEST[OrderByDir] ?>">
<input type="hidden" name="view" value="<?= $_REQUEST[view] ?>">
<input type="hidden" name="JobType" value="">
</form>
<script language="javascript">
<!--
<?
if($doRefresh) {
	print "parent.doRefresh();";

}

?>
//-->
