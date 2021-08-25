<?

 /**
  * Direct Transfer Management.
  *
  * dd_upload.php
  * version 0.02
  */

 $CONFIG['image_magick'] = "/virt/phpexec/";
 $CONFIG['dir_upload'] = "upload/newsletters";
 $CONFIG['dir_root'] = "";
 $CONFIG['dir_path'] = "/home/etxint/public_html/";
 $CONFIG['dir_temp'] = $CONFIG['dir_path'] . "/" . $CONFIG['dir_upload'] . "/_temp";
 $CONFIG['dir_thumbnails'] = $CONFIG['dir_path'] . "/" . $CONFIG['dir_upload'] . "/_thumbnails";
 $CONFIG['MaxFileSize'] = 1500000;
 $CONFIG['upload_temp_dir'] = "/home/etxint/public_html/upload/temp";

 $max_thumb_width = 290;
 $max_thumb_height = 200;

 $max_thumb_width_sm = 70;
 $max_thumb_height_sm = 70;

 //include("modules/file_upload.php");
 include("modules/db.php");

 $_SESSION['FileUpload']['path'] = "upload/newsletters/";

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

  if($_REQUEST['job'] == "Upload") {
	$Errormsg = check_errors();
  	if(!$Errormsg) {
		$Errormsg = process_upload();
   		if(!$Errormsg) {
    		//add_kpi("63", "0");
    		//mail("publications@ebanctrade.com","Newsletter Management","A New Newsletter has been added by ".$_SESSION['User']['Name']." in CountryID: ".$_SESSION['User']['CID']);
   		}
  	}
  }

  upload_form($Errormsg['Messages'],$Errormsg['Highlight']);

  if($_REQUEST['job'] == "Upload" && !$Errormsg) {

	 $array1 = file("/home/etxint/public_html/upload/newsletters/9124_2007-04-24.txt");

	 $foo = 0;
	 $total = 0;
	 ?>
	 <table border="0" cellspacing="0" width="620" cellpadding="1">
	 <tr>
	 <td class="Border">
	  <table border="0" cellspacing="0" width="100%" cellpadding="3">
	   <tr>
	     <td width="100%" colspan="8" align="center" class="Heading">Transfers</td>
	   </tr>
	   <tr>
	     <td class="Heading2"><b>From Account:</b></td>
	     <td class="Heading2"><b>From Name:</b></td>
	     <td align="right" class="Heading2"><b>Description:</b></td>
	     <td align="right" class="Heading2"><b>Amount:</b></td>
	   </tr>
	   <?
		foreach($array1 as $key => $value) {

		  $col = explode(",", $value);

		  $cfgbgcolorone = "#CCCCCC";
		  $cfgbgcolortwo = "#EEEEEE";
		  $bgcolor = $cfgbgcolorone;
		  $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

		  ?>
		    <tr>
		      <td bgcolor="<?= $bgcolor ?>"><?= $col[0] ?></td>
		      <td bgcolor="<?= $bgcolor ?>"><?= $col[2] ?></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"><?= $col[4] ?></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"><?= $col[3] ?></td>
		    </tr>
		  <?

		  $foo++;
		  $total += $col[3];

		}

	 ?>
		 <tr>
		 	<tr>
		      <td bgcolor="<?= $bgcolor ?>"></td>
		      <td bgcolor="<?= $bgcolor ?>"></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"><?= $total ?></td>
		    </tr>
		 </tr>
	     <tr>
	       <td bgcolor="#FFFFFF" colspan="8" align="center">Page Generation Time: <?
			    $time_end = getmicrotime();
			    $time = $time_end - $time_start;
				$time = number_format($time,2);
				echo $time;
			    ?> seconds</td>
	     </tr>
	    </table>
	   </td>
	  </tr>
	 </table>
	</body>
	</html>
<?
  }


 function upload_form($Errormsg = false, $ErrorArray = false) {

  print_error($Errormsg);

  ?>
  <table width="620" cellspacing="0" cellpadding="1" border="0">
   <tr>
    <td class="Border">
     <table width="100%" cellpadding="3" cellspacing="0" border="0">
      <tr>
       <td class="Heading2">Upload DD File.</td>
      </tr>
      <tr>
       <td bgcolor="#FFFFFF"><a href="javascript:new_window('body.php?page=newshowto')" class="nav"><font color="#FF0000"><b>Please click here for Newsletter Upload Instructions.</b></font></a></td>
      </tr>
      <tr>
       <td bgcolor="#FFFFFF">
      <table border="0" style="border-collapse: collapse" bordercolor="#111111" cellspacing="1" id="table1" width="614">
        <tr>
          <td colspan="2">Account No:<br><input type="text" name="memid" size="20"></td>
        </tr>
        <tr>
          <td colspan="2"><b>File Upload ( .txt ONLY ) ( MAX 2MB )<br></b><input type="file" name="file" value="<?= $_FILES['file']['name'] ?>" size="20" style="width:498"></td>
        </tr>
        <tr>
          <td align="right">
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

function check_errors() {

 $query3 = dbRead("select * from members where memid = ".$_REQUEST['memid']." and status != 1");
 $row3 = mysql_fetch_assoc($query3);

 if(!$row3['memid']) {
  $Errormsg['Messages'] .= "Invalid account number.<br>";
  $Errormsg['Highlight']['memid'] = true;
 }

 $file = $_FILES['file'];
 $file['dest_ext'] = strtolower(substr(strrchr($file['name'], "."), 1));

 if($file['dest_ext'] != 'txt') {
  $Errormsg['Messages'] .= "The file type need to a txt file.<br>";
  $Errormsg['Highlight']['file'] = true;
 }

 $lines = file($file['tmp_name']);

 echo $hh;

 foreach ($lines as $line_num => $line) {
	//echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
	$split = explode(",", $line);

	foreach($split as $i => $data) {

		//echo "Line #<b>{$i}</b> : " . htmlspecialchars($data) . "<br />\n";
		if($i == 0) {
		  $query = dbRead("select * from members where memid = ".$data." and status != 1");
		  $row = mysql_fetch_assoc($query);
		  if(!$row['memid']) {
		    $Errormsg['Messages'] .= "Invalid account number on line ".$line_num.".<br>";
		  }
		} elseif($i == 1) {

		} elseif($i == 2) {

		} elseif($i == 3) {
		  if(!preg_match ("/^([0-9.]+)$/", $data))  {
		    $Errormsg['Messages'] .= "Invalid amount on line ".$line_num.".<br>";
		  }
		} elseif($i == 4) {

		}
	}

 }

 if(!$_FILES['file']) {
  $Errormsg['Messages'] .= "You Must Select a File to upload.<br>";
  $Errormsg['Highlight']['file'] = true;
 }

 return $Errormsg;

}

 function process_upload() {

	global $max_file_size, $upload_temp_dir, $CONFIG;

	$dd = date("Y-m-d");
	$file = $_FILES['file'];
	$file['org_name'] = $file['name'];
	$file['dest_ext'] = strtolower(substr(strrchr($file['name'], "."), 1));
	$file['name'] = $_REQUEST['memid'] . "_".$dd."." . $file['dest_ext'];
	$file['dest_path'] = $CONFIG['dir_path'] . $_SESSION['FileUpload']['path'];
	$file['dest_name'] = $file['dest_path'] . $file['name'];

	if($file['size'] > $max_file_size) {
		$errormsg[] = "The uploaded file size has exceeded the 1.5MB limit.";
		$haltUpload = true;
	}

	if (move_uploaded_file($file['tmp_name'], $file['dest_name'])) {
		$ParentID = process_file($file);

		if(!$ParentID) { return; }

	}

 }

 function process_file($file,$parentid = false) {

	global $CONFIG,$image_magic_filetypes;

	$path = pathinfo($file["dest_name"]);

	$file[dest_path] = str_replace($CONFIG[dir_path],"",$file[dest_path]);
	$isDeleteProtect = ($_REQUEST['file_delete']) ? 1 : 0;
	$name = (trim($_REQUEST[file_name])) ? trim($_REQUEST[file_name]) : $file["org_name"];

	$md5 = process_md5($file["dest_name"]);

    return 1;
 }

  function process_md5($file) {
    if(!file_exists($file)) {
        return false;
    }
    else {
        $filecontent = implode("", file($file));
        return md5($filecontent);
    }
 }
