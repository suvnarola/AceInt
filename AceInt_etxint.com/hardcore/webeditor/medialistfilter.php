<?php include "php5.php"; ?>
<?php include "config.php"; ?>
<?php
	$hidden_paths = "^$";
	if ($exclude_paths <> "") {
		$hidden_paths = "^(" . str_replace(",", "|", $exclude_paths) . ")(/.*)?$";
	}
	if ($root_path <> "") {
		if ($images_path <> "") {
			if (is_dir($root_path . $images_path)) {
				$images = opendir($root_path . $images_path);
			}
		}
	}
?>
<?php

function folderMenu($base_path, $dh, $menu, $text, $section) {
	global $root_path, $hidden_paths;
	if ($dh) {
		echo $menu . " = menuitem;" . "\r\n";
		echo "medialistfilterMenu.add(menuitem++,menuitem_images," . $text . ",'javascript:openit(\'" . $section . "\',\'' + " . $text . " + '\')','','',true,'imgfolder.gif');" . "\r\n";
		folderSubMenu($base_path, "", $dh, $menu, $text, $section);
	}
}

function folderSubMenu($base_path, $path, $dh, $menu, $text, $section) {
	global $root_path, $hidden_paths;
	if ($dh) {
		$i = 0;
		while ($entry = readdir($dh)) {
			if (eregi($hidden_paths, $base_path . $path . $entry)) {
				# ignore
			} else if ((is_dir($root_path . $base_path . $path . $entry)) && ($entry <> ".") && ($entry <> "..")) {
				$i++;
				echo $menu . "_" . $i . " = menuitem;" . "\r\n";
				echo "medialistfilterMenu.add(menuitem++," . $menu . ",'" . $entry . "','javascript:openit(\'" . $path . $entry . "/" . "\',\'" . $entry . "\')','','','','imgfolder.gif');" . "\r\n";
				$subdh = opendir($root_path . $base_path . $path . $entry);
				folderSubMenu($base_path, $path . $entry . "/", $subdh, $menu . "_" . $i, "'" . $entry . "'", $section);
				closedir($subdh);
			}
		}
	}
}

?>
<?php include "medialistfilter.php.html"; ?>
<?php
	if ($images) closedir($images);
?>
