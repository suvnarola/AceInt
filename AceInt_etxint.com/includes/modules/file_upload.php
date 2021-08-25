<?

 /**
  * File Upload Functions
  *
  * file_upload.php
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
 
 function get_orderby($field,$text,$default = false) {
	if($field == $_REQUEST[OrderBy]) {
		$order = ($_REQUEST[OrderByDir] == "ASC") ? "DESC" : "ASC";
		$link = "<a class=\"nav\" href=\"javascript:OrderBy('$field','$order');\">$text <img src=\"../../images/arrow_$order.gif\" border=\"0\" align=\"absmiddle\" width=\"11\" height=\"6\"></a>";
	} else {
		$link = "<a class=\"nav\" href=\"javascript:OrderBy('$field', 'ASC');\">$text</a>";
	}
	
	return $link;
 }

 function get_files_sqlwhere($dir,$parentid) {
	$dir = trim($dir);
	$SQLQuery .= "(tbl_file_uploads.FilePath = '$dir' AND tbl_file_uploads.ParentID = '$parentid')";
	
	if($_REQUEST[search]) {
		$search = str_replace("*","%",trim($_REQUEST[search]));
		$search_array = explode(",",$search);
		
		foreach($search_array as $i) {
			$i = trim($i);
			if(!$i) continue;
			$search_sql .= " OR (tbl_file_uploads.FileNameDisplay LIKE '%".addslashes($i)."%' OR tbl_file_uploads.ImageCaption LIKE '%".addslashes($i)."%' OR tbl_file_uploads.ImageComments LIKE '%".addslashes($i)."%')";		
		}
		
		if($search_sql) {
			$search_sql = substr($search_sql,4);
			$SQLQuery .= " AND ($search_sql)";
		}
	}
	switch($_REQUEST['search_opt']) {
		case "1": break; // EVERYTHING
		case "2": $SQLQuery .= " AND (tbl_file_uploads.FileExt = 'jpg' OR tbl_file_uploads.FileExt = 'gif' OR tbl_file_uploads.FileExt = 'png')"; break; // IMAGES
		case "3": $SQLQuery .= " AND (tbl_file_uploads.FileExt = 'avi' OR tbl_file_uploads.FileExt = 'wav' OR tbl_file_uploads.FileExt = 'mp3' OR tbl_file_uploads.FileExt = 'mpg' OR tbl_file_uploads.FileExt = 'mpeg')"; break; // MULTIMEDIA
		case "4": $SQLQuery .= " AND (tbl_file_uploads.FileExt = 'pdf')"; break; // PDF DOCUMENTS
		case "5": $SQLQuery .= " AND (tbl_file_uploads.FileExt = 'zip')"; break; // ZIP ARCHIVES
	}
	
	return "($SQLQuery)";

 }

 function get_files($dir,$start,$limit,$parentid = 0) {
	global $icon_files;
	
	$AL = get_areas_allowed();
	
	$SQLQuery = "SELECT tbl_file_uploads.FieldID,
						tbl_file_uploads.FieldOrder,
						tbl_file_uploads.FileName,
						tbl_file_uploads.FileNameDisplay,
						tbl_file_uploads.FileSize,
						tbl_file_uploads.FileExt,
						tbl_file_uploads.ImageWidth,
						tbl_file_uploads.ImageHeight,
						tbl_file_uploads.CreateDate,
						tbl_file_uploads.CreateBy,
						tbl_file_uploads.ImageCaption,
						tbl_file_uploads.ImageComments,
						tbl_file_uploads.hit_counter,
						tbl_file_uploads_area.AreaID
				   FROM tbl_file_uploads, tbl_file_uploads_area
				  WHERE (tbl_file_uploads.FieldID = tbl_file_uploads_area.NewsletterID) AND (tbl_file_uploads_area.AreaID IN (".$AL.")) AND" . get_files_sqlwhere($dir,$parentid);
	if($_REQUEST[OrderBy]) {
	
		$SQLQuery .= " ORDER BY $_REQUEST[OrderBy] $_REQUEST[OrderByDir]";
	} else {
		$SQLQuery .= " ORDER BY FileDate ASC";
	}
	$rs = dbRead($SQLQuery);
	if(dbRecordTotal($rs) < 1) {
		return Array();
	}

	while($row = dbFetchArray($rs)) {
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
	$rs = dbRead($SQLQuery);
	if(dbRecordTotal($rs) < 1) {
		return Array();
	}
	$row = dbFetchArray($rs);
	return $row;

 }

 function process_thumbnail($recordid,$file,$srcSize,$small = false) {
	global $max_thumb_width,$max_thumb_height,$max_thumb_width_sm,$max_thumb_height_sm,$CONFIG;
	
	$twidth = ($small) ? $max_thumb_width_sm : $max_thumb_width;
	$theight = ($small) ? $max_thumb_height_sm : $max_thumb_height;
	
	if($CONFIG['image_magick']) {
	
		$file_src = $file["dest_name"];
		$file_dest = ($small) ? $CONFIG['dir_thumbnails'] . "/".$recordid."_sm.jpg" : $CONFIG['dir_thumbnails'] . "/".$recordid.".jpg";
		
		if($file["dest_ext"] == "pdf" || $file["dest_ext"] == "epdf" || $file["dest_ext"] == "fax" || $file["dest_ext"] == "avi") {
			$file_src = "pdf:" . $file_src . "[0]";
		}
		exec($CONFIG[image_magick]."convert -verbose -geometry \"".$twidth."x".$theight."\" $file_src $file_dest",$result);

		$line = $result[count($result)-1];
		if(preg_match("/^(.*?) (.*?) (.*?)x(.*?)=>(.*?)$/", $line, $regs)) {
			$SQLQuery = "UPDATE tbl_file_uploads SET ImageWidth = '$regs[3]', ImageHeight= '$regs[4]' WHERE FieldID = '$recordid'";
			dbWrite($SQLQuery);		
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
	
	$SQLQuery = "SELECT FileName, FilePath, FileNameDisplay, FileSize, FileExt, isDeleteProtect, ImageWidth, ImageHeight FROM tbl_file_uploads WHERE FieldID = '".$FieldID."'";
	$rs = dbRead($SQLQuery);
	$row = dbFetchArray($rs);
	$destFileName = process_randname() . ".jpg";
	
	$srcFile = $CONFIG['dir_path'] . $row['FilePath'] . $row['FileName'];
	$destFile = $CONFIG['dir_path'] . $row['FilePath'] . $destFileName;
	
	
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
		$size = @filesize($destFile);
		
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
 
	global $max_file_size, $upload_temp_dir, $CONFIG;
	
	$file = $_FILES['file'];
	$file['org_name'] = $file['name'];
	$file['dest_ext'] = strtolower(substr(strrchr($file['name'], "."), 1));
	$file['name'] = process_randname() . "." . $file['dest_ext'];
	$file['dest_path'] = $CONFIG['dir_path'] . $_SESSION['FileUpload']['path'];
	$file['dest_name'] = $file['dest_path'] . $file['name'];
	
	if($file['size'] > $max_file_size) {
		$errormsg[] = "The uploaded file size has exceeded the 1.5MB limit.";
		$haltUpload = true;
	}
	
	if($file['type'] == "application/x-zip-compressed" && $_REQUEST['file_unzip']) {
		include("modules/pclzip.lib.php");
		// UnZIP and Process Items
		if($_REQUEST['file_unzip']) {
			$random_name = process_randname();
			if (move_uploaded_file($file['tmp_name'], $CONFIG['dir_temp'] . "/$random_name.zip")) {
				$zip_folder = $CONFIG['dir_temp']."/".process_randname()."/";
				mkdir($zip_folder);
				$zip = new PclZip($CONFIG['dir_temp'] . "/$random_name.zip");
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
	  			unlink($CONFIG['dir_temp'] . "/$random_name.zip");
			}
		}
	} else {
		if (move_uploaded_file($file['tmp_name'], $file['dest_name'])) {
			$ParentID = process_file($file);
			
			if(!$ParentID) { return; }
			
			if($_REQUEST['resize']) {
				$resize = explode(",", $_REQUEST['resize']);
				foreach($resize as $i) {
					$resize2 = explode("|",$i);
					process_resize_image($ParentID, 4, $resize2[0], $resize2[1]);
				}			
			}
			
			if($file['dest_ext'] == "pdf") {
				process_resize_image($ParentID, 1, 96, 136);
			}
			
		}
		if($_REQUEST['file_zip'] && $file['dest_ext'] != ".zip") {
			include("includes/modules/zip.lib.php");
			
			$random_name = process_randname();
			
			$file_contents = @file_get_contents ($file['dest_name']);		
			$zipfile = new zipfile();
			$zipfile -> add_file($file_contents, $file['org_name']);
			$zip_buffer = $zipfile -> file();
			$fp = fopen ($file['dest_path'] . "$random_name.zip","w+");
			fwrite ($fp, $zip_buffer);
			fclose($fp);
			if($fp) {
				$file['dest_ext'] = "zip";
				$file['org_name'] = substr($file['org_name'], 0, strpos($file['org_name'],".")) . ".zip";
				$file['name'] = "$random_name.zip";
				$file['size'] = strlen($zip_buffer);
				$file['tmp_name'] = "";
				$file['dest_path'] = $file['dest_path'];
				$file['dest_name'] = $file['dest_path'] . $file['name'];
				$file['error'] = "";
				process_file($file,$ParentID);
			} else {
				$errormsg[] = "Error creating Zip archive from uploaded file";
			}
			
		
		}
	}
 }

 function process_randname() {

	$date = uniqid(date("Ymdhis"));
	return $date;

 }


 function process_array($zip) {
	global $upload_cwd;
	foreach($zip as $i) {
		$row = $i;
		
		$path_ext = pathinfo($row[filename]);
		$file['dest_ext'] = $path_ext['extension'];
		$file['org_name'] = $row[stored_filename];
		$file['name'] = process_randname() . "." . $file['dest_ext'];
		$file['size'] = $row['size'];
		$file['tmp_name'] = "";
		$file['dest_path'] = ($row['folder']) ? $upload_cwd . "/" . $row['folder'] . "/" : $upload_cwd;
		$file['dest_name'] = $file['dest_path'] . $file['name'];
		$file['error'] = "";
		
		if($row['folder']) {
			if(!file_exists($upload_cwd . "/" . $row['folder'])) {
				mkdir($upload_cwd . "/" . $row['folder']);
			}
		}

		rename($row['filename'], $file['dest_name']);
		$ParentID = process_file($file);
		
		if(!$ParentID) { return; }
		
		if($_REQUEST['resize']) {
			$resize = explode(",", $_REQUEST['resize']);
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
	$rs = dbRead($SQLQuery);
	if(dbRecordTotal($rs) > 0) {
		$row = dbFetchArray($rs);
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
	$isDeleteProtect = ($_REQUEST['file_delete']) ? 1 : 0;
	$name = (trim($_REQUEST[file_name])) ? trim($_REQUEST[file_name]) : $file["org_name"];
	
	$md5 = process_md5($file["dest_name"]);
	
	// FILE ALREADY FOUND ON SYSTEM
	if(process_md5_exist($md5,$file["size"],$file["org_name"])) {
		@unlink($file["dest_name"]);
		//$Errormsg['Messages'] .= "File is already on the System.";
		return;
	}
	
	
	$sql = new dbCreateSQL();
	$sql->add_table("tbl_file_uploads");
	$sql->add_item("FileName",$file[name]);
	$sql->add_item("FilePath",$_SESSION['FileUpload']['path']);
	$sql->add_item("FileNameDisplay",$name);
	$sql->add_item("FileSize",$file[size]);
	$sql->add_item("FileExt",$path[extension]);
	$sql->add_item("isDeleteProtect",$isDeleteProtect);
	$sql->add_item("CreateDate",mktime());
	$sql->add_item("CreateBy",$_SESSION['User']['LoginID']);
	$sql->add_item("ImageCaption",$_REQUEST[file_caption]);
	$sql->add_item("ImageComments",$_REQUEST[file_comments]);
	$sql->add_item("md5Check",process_md5($file["dest_name"]));
	$sql->add_item("FileType","1");
	$sql->add_item("FileDate","". $_REQUEST['Year'] . "-". $_REQUEST['Month'] . "-". $_REQUEST['Day'] . "");
	if($parentid) $sql->add_item("ParentID",$parentid);
	if(in_array($file['dest_ext'],$image_magic_filetypes)) {
		$image_size = GetImageSize($file['dest_name']);
		if($image_size) {
			$sql->add_item("ImageWidth",$image_size[0]);
			$sql->add_item("ImageHeight",$image_size[1]);
		}
		
		$RecordID = dbWrite($sql->get_sql_insert(), $CONFIG['db_name'], true);
		if(!$parentid) process_area($_REQUEST['Area'], $RecordID);
		if(!$parentid) process_fileorder($RecordID);
		 // LARGE THUMBNAIL
		process_thumbnail($RecordID,$file,$image_size);
		// SMALL THUMBNAIL
		process_thumbnail($RecordID,$file,$image_size,1);
	} else {
		$RecordID = dbWrite($sql->get_sql_insert());
    }
    
    return $RecordID;
 }

 function process_fileorder($RecordID) {
  
  $Max = mysql_fetch_assoc(dbRead("select max(FieldOrder)+1 as MaxFieldOrder from tbl_file_uploads"));
  
  dbWrite("update tbl_file_uploads set FieldOrder = " . $Max['MaxFieldOrder'] . " where FieldID = " . $RecordID);
 
 }
 
 function process_delete() {
 
  global $CONFIG;
 
  $FileList = explode(",", substr($_REQUEST['SelectedFiles'], 0, strlen($_REQUEST['SelectedFiles'])-1));
 
  foreach($FileList as $ParentID) {
  
   $SQLQuery = dbRead("select tbl_file_uploads.* from tbl_file_uploads where (FieldID = ".$ParentID.") OR (ParentID = ".$ParentID.")");
   
   while($SQLRow = mysql_fetch_assoc($SQLQuery)) {
   
    @unlink($CONFIG['dir_path'] . $SQLRow['FilePath'] . $SQLRow['FileName']);
    @unlink($CONFIG['dir_path'] . $SQLRow['FilePath'] . "_thumbnails/" . $SQLRow['FieldID'] . ".jpg");
    @unlink($CONFIG['dir_path'] . $SQLRow['FilePath'] . "_thumbnails/" . $SQLRow['FieldID'] . "_sm.jpg");
   
   }
   
   dbWrite("delete from tbl_file_uploads where (FieldID = ".$ParentID.") OR (ParentID = ".$ParentID.")");
   dbWrite("delete from tbl_file_uploads_area where NewsletterID = ".$ParentID."");
   
  }
 
 }

 function process_area($Areas, $RecordID) {
 
  if(!$Areas) {
   return;
  }
  
  if(is_array($Areas)) {
  
   foreach($Areas as $AreaID) {
  
    dbWrite("insert into tbl_file_uploads_area (NewsletterID, AreaID) values ('".$RecordID."','".$AreaID."')");	 
  
   }

  } else {
  
   dbWrite("insert into tbl_file_uploads_area (NewsletterID, AreaID) values ('".$RecordID."','".$Areas."')");	 
  
  }
 
 }

 function process_update() {
 
  dbWrite("update tbl_file_uploads set FileDate = '". $_REQUEST['Year'] . "-". $_REQUEST['Month'] . "-". $_REQUEST['Day'] . "', FileNameDisplay = '".$_REQUEST['FileNameDisplay']."', ImageCaption = '".$_REQUEST['ImageCaption']."', ImageComments = '".$_REQUEST['ImageComments']."' where (FieldID = '".$_REQUEST['RecordID']."' or ParentID = '".$_REQUEST['RecordID']."')");
 
 }

 function makeMoveArrow($count,$mid,$mpos,$total_count) {

  if($count > 1 && $total_count > 1) {
	$arrow = "<a href=\"body.php?page=manage_newsletters&mid=$mid&mpos=$mpos&mdir=U\" target=\"_self\" onmouseover=\"over('up',$count)\" onmouseout=\"out('up',$count)\"><img name=\"img_up_$count\" border=\"0\" src=\"/images/arrow_up_0.gif\" alt=\"Move Up\" width=\"9\" height=\"9\"></a>";
  } else {
	$arrow = "<img border=\"0\" src=\"/images/arrow_up_disabled.gif\" width=\"9\" height=\"9\">";
  }

	$arrow .= "<img border=\"0\" src=\"/images/layout_spacer.gif\" width=\"5\" height=\"9\">";
	
  if($count != $total_count && $total_count > 1) {
	$arrow .= "<a href=\"body.php?page=manage_newsletters&mid=$mid&mpos=$mpos&mdir=D\" target=\"_self\" onmouseover=\"over('down',$count)\" onmouseout=\"out('down',$count)\"><img name=\"img_down_$count\" border=\"0\" src=\"/images/arrow_down_0.gif\" alt=\"Move Down\" width=\"9\" height=\"9\"></a>";
  } else {
	$arrow .= "<img border=\"0\" src=\"/images/arrow_down_disabled.gif\" width=\"9\" height=\"9\">";
  }

  return $arrow;
 
 }


?>