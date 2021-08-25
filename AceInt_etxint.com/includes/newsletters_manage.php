<?

 /**
  * Newsletter Management.
  *
  * manage_newsletters.php
  * version 0.02
  */

 //check_access_level("Newsletters" );

 include("modules/file_upload.php");
 include("modules/db.php");

 $_SESSION['FileUpload']['path'] = "upload/newsletters/";

 $icon_files = Array("ai","aif","aifc","aiff","asf","au","avi","cda","doc","eps","exe","gif","htm",
					"html","jpg","js","m1v","m3u","mdb","mid","midi","mov","mp2","mp2v","mp3","mpa",
					"mpe","mpeg","mpg","mpv2","msi","pdf","php","ppt","psd","rmi","snd","txt","wav",
					"wma","wmf","wmv","wvx","xls","zip");

 $image_magic_filetypes = Array("avi","bmp","epdf","epi","eps","eps2","eps3","epsf","epsi","ept","fax",
 								"gif","jpg","pdf","pict","png","ps","ps2","ps3","psd","tga");

 ?><script language="javascript" src="includes/modules/file.js?cache=no"></script><?
 ?><script language="javascript">
  function new_window(URL) {
  var viewmsg ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=480";
  selectedURL = URL;
  remotecontrol=window.open(selectedURL, "NewsHowto", viewmsg);
  remotecontrol.focus();
 }
 </script><?
 ?><form name="NewsLetterManagement" enctype="multipart/form-data" method="POST" onsubmit="return GetSelected()"><?
 ?><input type="hidden" name="SelectedFiles" value=""><?

 ?><!-- <?= var_dump($_REQUEST) ?> --><?

 if($_REQUEST['job'] == "Upload") {
  $Errormsg = check_errors();
  if(!$Errormsg) {
   $Errormsg = process_upload();
   if(!$Errormsg) {
    add_kpi("63", "0");
    mail("publications@ebanctrade.com","Newsletter Management","A New Newsletter has been added by ".$_SESSION['User']['Name']." in CountryID: ".$_SESSION['User']['CID']);
   }
  }
 } elseif($_REQUEST['job'] == "Delete") {
  if($_REQUEST['opt'] == 2) {
   process_delete();
  }
 }

 if($_REQUEST['job'] == "Update") {
  process_update();
 }

 if($_REQUEST[mid]) {
	if($_REQUEST[mdir] == "U") {
		$SQLQuery = "UPDATE tbl_file_uploads SET FieldOrder = $_REQUEST[mpos] WHERE FieldOrder = $_REQUEST[mpos]-1";
		dbWrite($SQLQuery);
		$SQLQuery = "UPDATE tbl_file_uploads SET FieldOrder = $_REQUEST[mpos]-1 WHERE FieldID = $_REQUEST[mid]";
		dbWrite($SQLQuery);
	} else {
		$SQLQuery = "UPDATE tbl_file_uploads SET FieldOrder = $_REQUEST[mpos] WHERE FieldOrder = $_REQUEST[mpos]+1";
		dbWrite($SQLQuery);
		$SQLQuery = "UPDATE tbl_file_uploads SET FieldOrder = $_REQUEST[mpos]+1 WHERE FieldID = $_REQUEST[mid]";
		dbWrite($SQLQuery);
	}
 }

 if($_REQUEST['view']) {
  view_file();
 } else {
  upload_form($Errormsg['Messages'],$Errormsg['Highlight']);
 }

 display_files();

 ?></form><?

 /**
  * Functions.
  */

 function upload_form($Errormsg = false, $ErrorArray = false) {

  print_error($Errormsg);

  ?>
  <table width="620" cellspacing="0" cellpadding="1" border="0">
   <tr>
    <td class="Border">
     <table width="100%" cellpadding="3" cellspacing="0" border="0">
      <tr>
       <td class="Heading2">Upload New Newsletter.</td>
      </tr>
      <tr>
       <td bgcolor="#FFFFFF"><a href="javascript:new_window('body.php?page=newshowto')" class="nav"><font color="#FF0000"><b>Please click here for Newsletter Upload Instructions.</b></font></a></td>
      </tr>
      <tr>
       <td bgcolor="#FFFFFF">
      <table border="0" style="border-collapse: collapse" bordercolor="#111111" cellspacing="1" id="table1" width="614">
        <tr>
          <td colspan="2" bgcolor="<?= change_colour("file", $ErrorArray) ?>"><b>File Upload ( PDF ONLY ) ( MAX 2MB )<br></b><input type="file" name="file" value="<?= $_FILES['file']['name'] ?>" size="20" style="width:498"></td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?= change_colour("file_name", $ErrorArray) ?>"><b>Name</b> (Optional)<b><br>
          </b>
			<input type="text" name="file_name" size="32" style="width:498;" value="<?= $_REQUEST['file_name'] ?>"></td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?= change_colour("postalcity", $ErrorArray) ?>"><b>Order Date (Please enter date of the newsletter issue.)</b><br>
           <select name="Day">
            <?

             $Day = ($_REQUEST['Day']) ? $_REQUEST['Day'] : date("j");

             for($i = 0; $i <= 31; $i++) {

              ?><option value="<?= $i ?>"<? if($Day == $i) { print " selected"; } ?>><?= $i ?></option><?

             }

            ?>
           </select>
           <select name="Month">
            <?

             $Month = ($_REQUEST['Month']) ? $_REQUEST['Month'] : date("n");

             for($i = 0; $i <= 12; $i++) {

              ?><option value="<?= $i ?>"<? if($Month == $i) { print " selected"; } ?>><?= $i ?></option><?

             }

            ?>
           </select>
           <select name="Year">
            <?

             $Year = ($_REQUEST['Year']) ? $_REQUEST['Year'] : date("Y");

             for($i = 2000; $i <= 2011; $i++) {

              ?><option value="<?= $i ?>"<? if($Year == $i) { print " selected"; } ?>><?= $i ?></option><?

             }

            ?>
           </select>
          </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?= change_colour("Area", $ErrorArray) ?>"><b>Area (You Must Select an Area)</b><br>
          <select size="5" multiple name="Area[]">
          <?

           $AL = get_areas_allowed();

           $AreaQuery = "SELECT area.* from area where FieldID IN (".$AL.")";

           $AreaQuery .= "ORDER by place ASC";
           $DBAreaQuery = dbRead($AreaQuery);

           while($AreaRow = mysql_fetch_assoc($DBAreaQuery)) {

            ?><option value="<?= $AreaRow['FieldID'] ?>"<? if($_SESSION['User']['Area'] == $AreaRow['FieldID']) { print " selected"; } ?>><?= $AreaRow['place'] ?></option><?

           }

          ?>
          </select>
          </td>
        </tr>
        <tr>
          <td>
          <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="table2">
            <tr>
              <td><input type="checkbox" name="none" value="1" checked disabled></td>
              <td>Image is Delete Protected</td>
            </tr>
            <tr>
              <td><input type="checkbox" name="none" value="1" checked disabled></td>
              <td>Create Zip archive on upload</td>
            </tr>
            <tr>
              <td><input type="checkbox" name="none" value="1" disabled></td>
              <td nowrap>Unzip archive and process each file</td>
            </tr>
          </table>
          </td>
          <td align="right">
          <input type="hidden" value="1" name="file_delete">
          <input type="hidden" value="1" name="file_zip">
          <input type="submit" value="Upload" name="job"></td>
        </tr>
      </table>
      	</td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
  <?

 }

 function display_files() {

  ?><br>
  <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="620" id="AutoNumber5">
  <tr>
    <td width="100%">
    <table border="0" cellpadding="2" width="620" style="border-collapse: collapse" bordercolor="#111111" id="FileList">
      <tr>
        <td width="100%" class="heading" style="padding: 0" colspan="2">
        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber11">
          <tr>
            <td><input type="checkbox" name="allbox" value="ON" onclick="SelectAllBoxes()"></td>
            <td class="heading" style="padding: 4">Name&nbsp;</td>
          </tr>
        </table>
        </td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6">Width</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6">Height</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6">Size</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6">Type</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6">Created</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center">Hits</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center">ID</td>
        <td class="heading" nowrap style="padding-left: 6; padding-right: 6" align="center">AreaID</td>
        </tr>
      <? $files = get_files($_SESSION['FileUpload']['path'],0,30);

      //print get_files_sqlwhere($path);

      $counter = 0;
      $count = count($files);
      for ($i = 0; $i < $count; $i++) {
        $counter++;
      	$row = $files[$i];
      	$name = ($row['FileNameDisplay']) ? $row['FileNameDisplay'] : $row['FileName'];
      ?>
      <tr style="cursor:hand" id="<?= $row[FieldID] ?>" onmouseover="setPointer(this, <?= $row[FieldID] ?>, 'over', '#FFFFFF', '#E6E8E9', '#C0C0C0');" onmouseout="setPointer(this, <?= $row[FieldID] ?>, 'out', '#FFFFFF', '#E6E8E9', '#C0C0C0');" onmousedown="setPointer(this, <?= $row[FieldID] ?>, 'click', '#FFFFFF', '#E6E8E9', '#C0C0C0');">
        <td style="padding-left:4; padding-top:4; padding-bottom:4"><a href="<?= $SCRIPT_NAME ?>?page=newsletters_manage&f=<?= $_REQUEST[f] ?>&path=<?= $_SESSION['FileUpload']['Path'] ?>&view=<?= $row[FieldID] ?>"><img border="0" src="../../images/icons/<?= $row[icon] ?>" width="16" height="16"></a></td>
        <td style="padding-right:4; padding-top:4; padding-bottom:4" width="100%"><a class="nav" href="<?= $SCRIPT_NAME ?>?page=newsletters_manage&f=<?= $_REQUEST[f] ?>&path=<?= $_SESSION['FileUpload']['Path'] ?>&view=<?= $row[FieldID] ?>"><?= $name ?></a></td>
        <td nowrap style="padding: 4;" align="right"><?= $row[ImageWidth] ?></td>
        <td nowrap style="padding: 4;" align="right"><?= $row[ImageHeight] ?></td>
        <td nowrap style="padding: 4;" align="right"><?= GetFileSize($row[FileSize]) ?></td>
        <td nowrap style="padding: 4;" align="right"><?= $row[FileExt] ?></td>
        <td nowrap style="padding: 4;"><?= $row[CreateDate] ?></td>
        <td nowrap style="padding: 4;" align="center"><?= $row[hit_counter] ?></td>
        <td nowrap style="padding: 4;" align="center"><?= $row[FieldID] ?></td>
        <td nowrap style="padding: 4;" align="center"><?= $row[AreaID] ?></td>
      </tr>
      <? } ?>
      </table>
      </td>
    </tr>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" width="620">
    <tr>
      <td width="50%">
        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber10">
          <tr>
            <td colspan="2">&nbsp; </td>
          </tr>
          <tr>
            <td><input type="radio" value="2" name="opt"></td>
            <td>Delete selected files</td>
          </tr>
        </table>
      </td>
      <td width="50%" align="right"><br>
      <input type="submit" value="Delete" name="job"></td>
    </tr>
  </table>
  <?

 }

 function view_file() {

 $file = get_file($_REQUEST['view']);
 $download = str_replace("//","/",$CONFIG['dir_root'] . $file['FilePath'] . $file['FileName']);

 $DeleteProtect = $file['isDeleteProtect'] ? "checked" : "";

  ?>
  <input type="hidden" value="<?= $file['FieldID'] ?>" name="RecordID">
  <table border="1" cellpadding="2" width="620" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
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
          <td><b>Date</b><br>
           <select name="Day">
            <?

             for($i = 1; $i <= 31; $i++) {

              ?><option value="<?= $i ?>"<? if(date("j", strtotime($file['FileDate'])) == $i) { print " selected"; } ?>><?= $i ?></option><?

             }

            ?>
           </select>
           <select name="Month">
            <?

             for($i = 1; $i <= 12; $i++) {

              ?><option value="<?= $i ?>"<? if(date("n", strtotime($file['FileDate'])) == $i) { print " selected"; } ?>><?= $i ?></option><?

             }

            ?>
           </select>
           <select name="Year">
            <?

             for($i = 2000; $i <= 2011; $i++) {

              ?><option value="<?= $i ?>"<? if(date("Y", strtotime($file['FileDate'])) == $i) { print " selected"; } ?>><?= $i ?></option><?

             }

            ?>
           </select>
          </td>
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
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber12">
              <tr>
                <td><input type="checkbox" name="DeleteProtect" value="1" <?= $DeleteProtect ?>></td>
                <td>Image is Delete Protected</td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
        </center>
      </div>
      </td>
      <td width="290" bordercolor="#0E1B2A" bgcolor="#FFFFFF" rowspan="2" align="center" height="200">
            <a target="_blank" href="https://secure.ebanctrade.com/upload/newsletters/<?= $file['FileName'] ?>">
            <img border="0" src="https://secure.ebanctrade.com/upload/newsletters/_thumbnails/<?= $file["FieldID"] ?>.jpg" alt="Click to open file"></a></td>
    </tr>
    <tr>
      <td bordercolor="#0E1B2A" bgcolor="#E6E8E9">
      <div align="center">
        <center>
        <table border="0" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber6" cellpadding="2">
          <tr>
            <td height="35"><b>File ID<br>
            </b>
            <input type="text" name="blank_2" size="4" value="<?= $file[FieldID] ?>" style="width:40" disabled></td>
            <td height="35"><b>Dynamic Link</b><br>
            <input type="text" name="blank_3" size="32" value="[image:<?= $file[FieldID] ?>]Click Here[/image]" style="width:221" disabled></td>
          </tr>
        </table>
        </center>
      </div>
      </td>
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
          <td width="33%"><?= $file["CreateBy"] ?></td>
        </tr>
        <tr>
          <td align="right" nowrap><b>Size:</b></td>
          <td width="33%"><?= GetFileSize($file["FileSize"]) ?></td>
          <td align="right" nowrap>&nbsp;</td>
          <td align="right" nowrap width="33%">&nbsp;</td>
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
        <tr>
          <td colspan="4" align="center"><input type="submit" value="Update" name="job"></td>
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

<?

 }

function print_error($Errormsg = false) {

 if($Errormsg) {

  ?>
  <table width="620" border="1" bordercolor="#FF0000" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
   <tr>
    <td bgcolor="#FFFFFF" align="center"><?= $Errormsg ?>&nbsp;</td>
   </tr>
  </table><br>
  <?

 }

}

function change_colour($field,$ErrorArray) {

 if($ErrorArray[$field]) {
  return "#FF0000";
 } else {
  return "#FFFFFF";
 }

}

function check_errors() {

 if(!$_REQUEST['Area']) {
  $Errormsg['Messages'] .= "You Must Select an Area.<br>";
  $Errormsg['Highlight']['Area'] = true;
 }

 if(!$_FILES['file']) {
  $Errormsg['Messages'] .= "You Must Select a File to upload.<br>";
  $Errormsg['Highlight']['file'] = true;
 }

 return $Errormsg;

}

?>