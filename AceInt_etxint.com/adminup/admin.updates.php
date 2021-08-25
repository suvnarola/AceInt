<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Updates</title>
<link href="https://admin.ebanctrade.com/adminup/adminUpdates.screen.css" media="screen" rel="stylesheet" type="text/css" />
<link href="https://admin.ebanctrade.com/adminup/adminUpdates.print.css" media="print" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
class adminUpdates {
	var $year;
	var $month;
	var $menu;

	function adminUpdates(){
		$this->year = date('Y');
		$this->month = date('m');
		$this->menuYears = array();
		$this->menuMonths = array();
	}

	function menuYear(){
		$query = mysql_query('
						SELECT
							YEAR(addDate)
						FROM
							snyper.tbl_adminupdates
						GROUP BY
							YEAR(addDate)
						ORDER BY
							YEAR(addDate) DESC
							');

		$r = '';
		while($row = mysql_fetch_row($query)){
			$r.= sprintf("\n\t\t".'<a href="%2$s" class="menu-year%3$s">%1$s</a>',
							$row[0],
							$this->pageLocation()."&y={$row[0]}",
							(($row[0] == $this->year) ? '-sel' : '')
						);
			if($row[0] == $this->year){
				$r.= $this->menuMonth();
			}
		}
		return $r;
	}

	function menuMonth(){
		$query = mysql_query('
						SELECT
							MONTH(addDate),
							MONTHNAME(addDate)
						FROM
							snyper.tbl_adminupdates
						WHERE
							YEAR(addDate) = "'.$this->year.'"
						GROUP BY
							MONTHNAME(addDate)
						ORDER BY
							MONTH(addDate) DESC
							');

		$r = '';
		while($row = mysql_fetch_row($query)){
			$r.= sprintf("\n\t\t".'<a href="%2$s" class="menu-month%3$s">%1$s</a>',
							$row[1],
							$this->pageLocation()."&m={$row[0]}",
							(($row[0] == $this->month) ? '-sel' : '')
						);
		}
		return $r;
	}

	function pageLocation(){
		$get = $_GET;
		unset($get['m'],$get['y'],$get['mo'],$get['s']);
		foreach($get as $key => $value){
			$get[$key] = $key.'='.urlencode($value);
		}
		return $_SERVER['PHP_SELF'].'?'.implode('&',$get);
	}

	function output(){
		if($this->search){
			$query = mysql_query('
							SELECT
								update_id,
								content,
								DATE_FORMAT(addDate,"%d/%m/%Y") AS date,
								MONTHNAME(addDate) AS month
							FROM
								snyper.tbl_adminupdates
							WHERE
								addDate
							  AND
							  	content LIKE "%'.addslashes($this->searchTerm).'%"
							ORDER BY
								addDate DESC
								');
		}else{
			$query = mysql_query('
							SELECT
								*,
								DATE_FORMAT(addDate,"%d/%m/%Y") AS date,
								MONTHNAME(addDate) AS month
							FROM
								snyper.tbl_adminupdates
							WHERE
								MONTH(addDate) = "'.$this->month.'"
							  AND
								YEAR(addDate) = "'.$this->year.'"
							ORDER BY
								addDate DESC
								');
		}

		$row = mysql_fetch_assoc($query);

		if($this->search){
			$r = '<span class="noPrint"><form action="'.$this->pageLocation().'" name="adminUpdateSearch" method="get" enctype="application/x-www-form-urlencoded" class="adminUpdate-search">';
			$r.= '<input type="hidden" name="page" value="procs" />';
			$r.= '<input type="hidden" name="Client" value="" />';
			$r.= '<input type="hidden" name="pageno" value="" />';
			$r.= '<input type="hidden" name="tab" value="tab3" />';
			$r.= 'Search:<input type="text" name="s" value="'.htmlentities(stripslashes($this->searchTerm)).'" class="adminUpdate-searchBox" /><input type="submit" value="Search" />';
			$r.= '</from></span>';
		}else{
			$r = sprintf('<div class="adminUpdates-monthTitle">%s</div>',$row['month']);
		}

		if($this->mode == 'edit'){
			$fs = "\n\t\t".'
							<form action="'.$this->pageLocation().'" name="adminUpdate_%3$s" method="post" enctype="application/x-www-form-urlencoded" class="adminUpdate">
								<input type="hidden" name="update_id" value="%3$s" />
								<input type="text" name="date" class="adminUpdate-date" value="%1$s" onKeyUp="document.all.adminUpdate_%3$s.submit.style.display = \'inline\'" />
								<textarea name="content" class="adminUpdate-content" onKeyUp="document.all.adminUpdate_%3$s.submit.style.display = \'inline\'">%2$s</textarea>
								<div style="text-align: right"><input type="submit" name="submit" value="Submit Changes" style="display: none;" /></div>
							</form>';
		}else{
			$fs = "\n\t\t".'
							<div class="adminUpdate">
								<div class="adminUpdate-date">%s</div>
								<div class="adminUpdate-content">%s</div>
							</div>';
		}
		do{
			$c = $row['content'];
			if($this->mode != 'edit'){
				$c = preg_replace('/\s(http:\/\/|https:\/\/)([^\s]+)(\.\w\w\w)(\.[^\/\s,]*)?(\/)?\s/','<a href="$1$2$3$4$5">$0</a>',$c);
				$c = nl2br($c);
			}

			$r.= sprintf($fs,
						$row['date'],
						$c,
						$row['update_id']
					);
		} while($row = mysql_fetch_assoc($query));
		return $r;
	}
}










if(!($app = unserialize($_SESSION['adminUpdates']))){
	$app = new adminUpdates();
}

if(is_numeric($_GET['m'])){
	if($_GET['m'] <= 12){
		$app->month = (int) $_GET['m'];
		$app->search = false;
	}
}

if(is_numeric($_GET['y'])){
	if($_GET['y'] < 2050){
		$app->month = (int) date('m');
		$app->year = (int) $_GET['y'];
		$app->search = false;
	}
}elseif($_GET['y'] == 's'){
	$app->month = 0;
	$app->year = 0;
	$app->search = true;
}

if(strlen($_GET['s'])){
	$app->searchTerm = (string) $_GET['s'];
	$app->month = 0;
	$app->year = 0;
	$app->search = true;
}



if($_SESSION['Username'] == 'rory' || $_SESSION['Username'] == 'darryl' || $_SESSION['Username'] == 'dave'){
	if($_GET['mo'] == 'edit'){
		$app->mode = 'edit';
	}elseif($_GET['mo'] == 'view'){
		$app->mode = 'view';
	}elseif($_GET['mo'] == 'new'){
		$app->mode = 'edit';
		$app->month = 0;
		$app->year = 0;
	}elseif(is_numeric($_POST['update_id'])){
		if($_POST['content']){
			mysql_query('
				UPDATE snyper.tbl_adminupdates
				SET
					content = \''.addslashes($_POST['content']).'\'
					'.($_POST['sendUpdate'] ? ',addDate = NOW()' : '').'
				WHERE
					update_id = \''.addslashes($_POST['update_id']).'\'
			');
		}else{
			mysql_query('DELETE FROM snyper.tbl_adminupdates WHERE update_id = \''.addslashes($_POST['update_id']).'\'');
		}
	}elseif($_POST['update_id'] === ""){
		$d = explode('/',$_POST['date']);
		mysql_query('
			INSERT INTO snyper.tbl_adminupdates
			VALUES (
				"",
				"'.$d[2].'-'.$d[1].'-'.$d[0].'",
				"0",
				\''.addslashes($_POST['content']).'\',
				"'.$_SESSION['User']['FieldID'].'",
				"'.$_SESSION['User']['Name'].'"
			)');
	}
}

$_SESSION['adminUpdates'] = serialize($app);
?>

<div class="adminUpdates">
	<div class="adminUpdates-title">Admin Updates</div>
	<div class="adminUpdates-menu">
		<a href="<?= $app->pageLocation(); ?>&y=s" class="menu-year<?= $app->search ? '-sel' : ''; ?>">Search</a>
<?= $app->menuYear(); ?>
<?php
if($_SESSION['Username'] == 'rory' || $_SESSION['Username'] == 'darryl' || $_SESSION['Username'] == 'dave'){
	echo "\n\t\t".'<a href="'.$app->pageLocation().'&mo='.(($app->mode == 'edit') ? 'view' : 'edit').'" class="menu-year-sel" style="margin-top: 50px;">'.(($app->mode == 'edit') ? 'View Mode' : 'Edit Mode').'</a>';
	echo "\n\t\t".'<a href="'.$app->pageLocation().'&mo=new" class="menu-year-sel">New Update</a>';
}
?>
	</div>
	<div class="adminUpdates-content">
<?= $app->output(); ?>
	</div>
</div>
<!-- Version 1 Beta -->
</body>
</html>