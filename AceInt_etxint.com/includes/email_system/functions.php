<?
//$mod_name = "M2";
//if(!auth_level("VIEW")) {
//	print "ACCESS DENIED";
//	exit;
//}

$CONFIG['dir_thumbnails'] = "/home/etxint/admin.etxint.com/uploads/_thumbnails";

$icon_files = Array("ai","aif","aifc","aiff","asf","au","avi","cda","doc","eps","exe","gif","htm",
					"html","jpg","js","m1v","m3u","mdb","mid","midi","mov","mp2","mp2v","mp3","mpa",
					"mpe","mpeg","mpg","mpv2","msi","pdf","php","ppt","psd","rmi","snd","txt","wav",
					"wma","wmf","wmv","wvx","xls","zip");

function string_dot($strText,$strLen = 35) {

	if (strLen($strText) >= $strLen) {
		$text = substr($strText, 0, $strLen)."...";
		$text = str_replace("....","...",$text);
		return $text;

	} else {
		return $strText;
	}


}

function process_thumbnail($recordid,$file,$srcSize,$small = false) {
	global $max_thumb_width,$max_thumb_height,$max_thumb_width_sm,$max_thumb_height_sm,$CONFIG;
	
	$twidth = ($small) ? $max_thumb_width_sm : $max_thumb_width;
	$theight = ($small) ? $max_thumb_height_sm : $max_thumb_height;
	
	if($CONFIG['image_magick']) {
	
		$file_src = $file["dest_name"];
		$file_dest = ($small) ? $CONFIG['dir_path'] . $_REQUEST['path'] . "_thumbnails/".$recordid."_sm.jpg" : $CONFIG['dir_path'] . $_REQUEST['path'] . "_thumbnails/".$recordid.".jpg";
		
		if($file["dest_ext"] == "pdf" || $file["dest_ext"] == "epdf" || $file["dest_ext"] == "fax" || $file["dest_ext"] == "avi") {
			$file_src = $file_src . "[0]";
		}
		
		exec($CONFIG[image_magick]."convert -verbose -geometry \"".$twidth."x".$theight.">\" $file_src $file_dest",$result);
		$line = $result[count($result)-1];
		if(preg_match("/^(.*?) (.*?) (.*?)x(.*?)=>(.*?)$/", $line, $regs)) {
			$SQLQuery = "UPDATE tbl_file_uploads SET ImageWidth = '$regs[3]', ImageHeight= '$regs[4]' WHERE FieldID = '$recordid'";
			dbWrite($SQLQuery, "etxint_email_system");		
		}
	} else {
		$scale = min(($twidth/$srcSize[0]), ($theight/$srcSize[1]));
		$width = $srcSize[0];
		$height = $srcSize[1];
		
        switch ($file["dest_ext"]) {            
			case "jpg": $image = ImageCreateFromJpeg($file["dest_name"]); break; 
			case "gif": $image = ImageCreateFromGif($file["dest_name"]); break; 
			case "png": $image = ImageCreateFromPng($file["dest_name"]); break; 
			default: return false; break;
		}
		
		// CREATE THUMBNAIL
		if ($scale < 1) {
			$new_width = floor($scale*$width);
			$new_height = floor($scale*$height);
        	$image_thumb = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        // ALREADY SMALL, COPY IMAGE
        } else {
        	$image_thumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, $width, $width, $width, $height);
        }
        
        // SAVE THUMBNAIL
        ImageDestroy($image);
        ImageJPEG($image_thumb,$CONFIG['dir_thumbnails'] . "/$recordid.jpg");
        ImageDestroy($image_thumb);
        return true;
	}

}

/*

	$src[FileName];
	$src[FileExt];
	$src[Width];
	$src[Height];
	
	$dest[FileName];
	$dest[FileExt];
	$dest[Width];
	$dest[Height];
	
	1: Scale Keep Width;
	2: Scale Keep Height;
	3: Scale Max Width or Height
	4: Resize

*/

function process_resize_image($FieldID, $job, $destWidth, $destHeight) {
	global $CONFIG;
	
	$SQLQuery = "SELECT FileName, FilePath, FileNameDisplay, FileSize, FileExt, isDeleteProtect, ImageWidth, ImageHeight FROM tbl_file_uploads WHERE FieldID = '$FieldID'";
	$rs = dbRead($SQLQuery, "etxint_email_system");
	$row = dbFetchArray($rs, "etxint_email_system");
	$destFileName = process_randname() . ".jpg";
	
	$srcFile = $CONFIG['dir_path'] . $row[FilePath] . $row[FileName];
	$destFile = $CONFIG['dir_path'] . $row[FilePath] . $destFileName;
	
	
	if($CONFIG['image_magick']) {	
		if($row[FileExt] == "pdf" || $row[FileExt] == "epdf" || $row[FileExt] == "fax" || $row[FileExt] == "avi") {
			$srcFile = $srcFile . "[0]";
		}
		if($job == "1") { // KEEP WIDTH
			$destWidth = $destWidth;
			$destHeight = (($row[ImageHeight]/$destHeight)*$row[ImageHeight]);
			
			exec($CONFIG[image_magick]."convert -verbose -scale \"".$destWidth."x".$destHeight.">\" $srcFile $destFile",$result);
		} else if($job == "2") { // KEEP HEIGHT
			$destHeight = $dest[Height];
			$destWidth = (($row[ImageWidth]/$destWidth)*$row[ImageWidth]);
			
			exec($CONFIG[image_magick]."convert -verbose -scale \"".$destWidth."x".$destHeight.">\" $srcFile $destFile",$result);
		} else if($job == "3") { // SCALE
			$destHeight = $destHeight;
			$destWidth = $destWidth;
			
			exec($CONFIG[image_magick]."convert -verbose -geometry \"".$destWidth."x".$destHeight.">\" $srcFile $destFile",$result);
		} else if($job == "4") { // RESIZE CUSTOM
			$destHeight = $destHeight;
			$destWidth = $destWidth;
			
			exec($CONFIG[image_magick]."convert -verbose -scale \"".$destWidth."x".$destHeight.">\" $srcFile $destFile",$result);
		}
		$line = $result[count($result)-1];
		if(strstr($result[0],"Error")) {
			return false;
		}
		$size = filesize($destFile);
		
		$file["name"] = $destFileName;
		$file["org_name"] = $row[FileNameDisplay] . " (".$destHeight."x".$destWidth.")";
		$file["dest_ext"] = $row[FileExt];
		$file["dest_path"] = $CONFIG['dir_path'] . $row[FilePath];
		$file["dest_name"] = $file["dest_path"] . $file["name"];
		$file["size"] = $size;
		$_REQUEST[file_delete] = $row[isDeleteProtect];

		$recordid = process_file($file,$FieldID);
	}
}


function process_upload() {
	global $max_file_size,$upload_temp_dir,$CONFIG;
	$file = $_FILES["file"];
	$file["org_name"] = $file["name"];
	$file["dest_ext"] = strtolower(substr(strrchr($file["name"], "."), 1));
	$file["name"] = process_randname() . "." . $file["dest_ext"];
	$file["dest_path"] = "/home/etxint/admin.etxint.com/uploads/";
	$file["dest_name"] = $file["dest_path"] . $file["name"];
	
	if($file["size"] > $max_file_size) {
		$errormsg[] = "The uploaded file size has exceeded the 5MB limit.";
		$haltUpload = true;
	}
	
	if($file["type"] == "application/x-zip-compressed" && $_REQUEST["file_unzip"]) {
		include('pclzip.lib.php');
		// UnZIP and Process Items
		if($_REQUEST["file_unzip"]) {
			$random_name = process_randname();
			if (move_uploaded_file($file["tmp_name"], $upload_temp_dir . "/$random_name.zip")) {
				$zip_folder = $upload_temp_dir."/".process_randname()."/";
				mkdir($zip_folder);
				$zip = new PclZip($upload_temp_dir . "/$random_name.zip");
				$zipdir = $zip->extract($zip_folder, "");
				if ($zipdir == 0) {
	    			$errormsg[] = $zip->errorInfo();
	    			$haltUpload = true;
	  			} else {
	  				print "done";
	  			}
	  			
	  			process_array($zipdir);
	  			
	  			// DELETE TEMP DIR
	  			$del_dir = trim(substr($zip_folder, 0, strlen($zip_folder)-1));
	  			rmdir($del_dir);
	  			// DELETE ZIP ARCHIVE
	  			unlink($upload_temp_dir . "/$random_name.zip");
			}
		}
	} else {
		if (move_uploaded_file($file["tmp_name"], $file["dest_name"])) {
			$ParentID = process_file($file);
			
			if($_REQUEST[resize]) {
				$resize = explode(",", $_REQUEST[resize]);
				foreach($resize as $i) {
					$resize2 = explode("|",$i);
					process_resize_image($ParentID, 4, $resize2[0], $resize2[1]);
				}			
			}
			
			if($file["dest_ext"] == "pdf") {
				process_resize_image($ParentID, 1, 96, 136);
			}
			
		}
		if($_REQUEST["file_zip"] && $file["dest_ext"] != ".zip") {
			include('zip.lib.php');
			$random_name = process_randname();
			
			$file_contents = file_get_contents ($file["dest_name"]);		
			$zipfile = new zipfile();
			$zipfile -> add_file($file_contents, $file["org_name"]);
			$zip_buffer = $zipfile -> file();
			$fp = fopen ($file["dest_path"] . "$random_name.zip","w+");
			fwrite ($fp, $zip_buffer);
			fclose($fp);
			if($fp) {
				$file["dest_ext"] = "zip";
				$file["org_name"] = substr($file["org_name"], 0, strpos($file["org_name"],".")) . ".zip";
				$file["name"] = "$random_name.zip";
				$file["size"] = strlen($zip_buffer);
				$file["tmp_name"] = "";
				$file["dest_path"] = $file["dest_path"];
				$file["dest_name"] = $file["dest_path"] . $file["name"];
				$file["error"] = "";
				process_file($file,$ParentID);
			} else {
				$errormsg[] = "Error creating Zip archive from uploaded file";
			}
			
		
		}
	}
}

function process_randname() {

	$date =  uniqid(date("Ymdhis"));
	return $date;

}


function process_array($zip) {
	global $upload_cwd;
	foreach($zip as $i) {
		$row = $i;
		
		$path_ext = pathinfo($row[filename]);
		$file["dest_ext"] = $path_ext["extension"];
		$file["org_name"] = $row[stored_filename];
		$file["name"] = process_randname() . "." . $file["dest_ext"];
		$file["size"] = $row[size];
		$file["tmp_name"] = "";
		$file["dest_path"] = ($row["folder"]) ? $upload_cwd . "/" . $row["folder"] . "/" : $upload_cwd;
		$file["dest_name"] = $file["dest_path"] . $file["name"];
		$file["error"] = "";
		
		if($row["folder"]) {
			if(!file_exists($upload_cwd . "/" . $row["folder"])) {
				mkdir($upload_cwd . "/" . $row["folder"]);
			}
		}

		rename($row["filename"], $file["dest_name"]);
		$ParentID = process_file($file);
		if($_REQUEST[resize]) {
			$resize = explode(",", $_REQUEST[resize]);
			foreach($resize as $i) {
				$resize2 = explode("|",$i);
				process_resize_image($ParentID, 4, $resize2[0], $resize2[1]);
			}			
		}

	}


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

function process_md5_exist($md5,$size,$org_name) {
	global $errormsg;
	$SQLQuery = "SELECT FieldID,FileNameDisplay,FilePath FROM tbl_file_uploads WHERE md5Check = '$md5' AND FileSize = '$size'";
	$rs = dbRead($SQLQuery, "etxint_email_system");
	if(dbRecordTotal($rs) > 0) {
		$row = dbFetchArray($rs, "etxint_email_system");
		$errormsg[] = "$org_name was already found on the system and is currently known as $row[FileNameDisplay] [ID=$row[FieldID]] located in $row[FilePath]";
		return true;
	} else {
		return false;
	}

}

function process_file($file,$parentid = false) {
	global $CONFIG,$image_magic_filetypes;
	
	$path = pathinfo($file["dest_name"]);
	
	$file[dest_path] = str_replace($CONFIG[dir_path],"",$file[dest_path]);
	$isDeleteProtect = ($_REQUEST[file_delete]) ? 1 : 0;
	$name = (trim($_REQUEST[file_name])) ? trim($_REQUEST[file_name]) : $file["org_name"];
	
	$md5 = process_md5($file["dest_name"]);
	
	// FILE ALREADY FOUND ON SYSTEM
	if(process_md5_exist($md5,$file["size"],$file["org_name"])) {
		unlink($file["dest_name"]);
		return;
	}
	
	
	$sql = new dbCreateSQL();
	$sql->add_table("tbl_file_uploads");
	$sql->add_item("FileName",$file[name]);
	$sql->add_item("FilePath",$file[dest_path]);
	$sql->add_item("FileNameDisplay",$name);
	$sql->add_item("FileSize",$file[size]);
	$sql->add_item("FileExt",$path[extension]);
	$sql->add_item("isDeleteProtect",$isDeleteProtect);
	$sql->add_item("CreateDate",mktime());
	$sql->add_item("CreateBy", $_SESSION['UserID']);
	$sql->add_item("ImageCaption",$_REQUEST[file_caption]);
	$sql->add_item("ImageComments",$_REQUEST[file_comments]);
	$sql->add_item("md5Check",process_md5($file["dest_name"]));
	if($parentid) $sql->add_item("ParentID",$parentid);
	if(in_array($file[dest_ext],$image_magic_filetypes)) {
		$image_size = GetImageSize($file["dest_name"]);
		if($image_size) {
			$sql->add_item("ImageWidth",$image_size[0]);
			$sql->add_item("ImageHeight",$image_size[1]);
		}
		
		$RecordID = dbWrite($sql->get_sql_insert(), "etxint_email_system", true);
		// LARGE THUMBNAIL
		process_thumbnail($RecordID,$file,$image_size);
		// SMALL THUMBNAIL
		process_thumbnail($RecordID,$file,$image_size,1);
	} else {
		$RecordID = dbWrite($sql->get_sql_insert(), "etxint_email_system");
    }
    
    return $RecordID;
}
function get_files_sqlwhere($dir,$parentid) {
	$dir = trim($dir);
	$SQLQuery .= "(ParentID = '$parentid')";
	
	if($_REQUEST[search]) {
		$search = str_replace("*","%",trim($_REQUEST[search]));
		$search_array = explode(",",$search);
		
		foreach($search_array as $i) {
			$i = trim($i);
			if(!$i) continue;
			$search_sql .= " OR (FileNameDisplay LIKE '%".addslashes($i)."%' OR ImageCaption LIKE '%".addslashes($i)."%' OR ImageComments LIKE '%".addslashes($i)."%')";		
		}
		
		if($search_sql) {
			$search_sql = substr($search_sql,4);
			$SQLQuery .= " AND (FieldID = '".addslashes($i)."' OR $search_sql)";
		}
	}
	switch($_REQUEST[search_opt]) {
		case "1": break; // EVERYTHING
		case "2": $SQLQuery .= " AND (FileExt = 'jpg' OR FileExt = 'gif' OR FileExt = 'png')"; break; // IMAGES
		case "3": $SQLQuery .= " AND (FileExt = 'avi' OR FileExt = 'wav' OR FileExt = 'mp3' OR FileExt = 'mpg' OR FileExt = 'mpeg')"; break; // MULTIMEDIA
		case "4": $SQLQuery .= " AND (FileExt = 'pdf')"; break; // PDF DOCUMENTS
		case "5": $SQLQuery .= " AND (FileExt = 'zip')"; break; // ZIP ARCHIVES
	}
	
	return "($SQLQuery)";

}

function get_files($dir,$start,$limit,$parentid = 0) {
	global $icon_files;
	$SQLQuery = "SELECT FieldID,
						FileName,
						FileNameDisplay,
						FileSize,
						FileExt,
						ImageWidth,
						ImageHeight,
						CreateDate,
						CreateBy,
						ImageCaption,
						ImageComments,
						hit_counter
				   FROM tbl_file_uploads
				  WHERE " . get_files_sqlwhere($dir,$parentid);
	if($_REQUEST[OrderBy]) {
	
		$SQLQuery .= " ORDER BY $_REQUEST[OrderBy] $_REQUEST[OrderByDir]";
	}
	$rs = dbRead($SQLQuery, "etxint_email_system");
	if(dbRecordTotal($rs) < 1) {
		return Array();
	}

	while($row = dbFetchArray($rs, "etxint_email_system")) {
		if(in_array($row[FileExt],$icon_files)) {
			$row[icon] = $row[FileExt] .".gif";
		} else {
			$row[icon] = "unknown.gif";
		}
		$datarow[] = $row;
	}
	
	return $datarow;
}

function get_file($fieldid) {
	$SQLQuery = "SELECT * FROM tbl_file_uploads WHERE FieldID = '$fieldid'";
	$rs = dbRead($SQLQuery, "etxint_email_system");
	if(dbRecordTotal($rs) < 1) {
		return Array();
	}
	$row = dbFetchArray($rs);
	return $row;

}

 function form_radio($name,$value,$compare,$custom = false) {

  if(strtolower($value) == strtolower($compare)) {
	return "<input type=\"radio\" name=\"$name\" value=\"$value\" checked $custom>";
  } else {
	return "<input type=\"radio\" name=\"$name\" value=\"$value\" $custom>";
  }

 }
 function Get_User_Info($UserID){
 
  $SQL = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = ". $UserID);
  $Row = mysql_fetch_assoc($SQL);
 
  return $Row;
 
 }
?>