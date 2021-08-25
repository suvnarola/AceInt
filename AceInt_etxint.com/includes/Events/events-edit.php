<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<script language="JavaScript" src="includes/Events/pupdate.js">
/*
Popup calendar script by Sev Kotchnev (webmaster@personal-connections.com)
For full source code and installation instructions to this script
Visit http://www.dynamicdrive.com
*/
</script>
<style type="text/css">
<!--
.month {
	background:#FFFFFF url('events_month_div.jpg') no-repeat left top; 
}
-->
</style>
</head>

<body>
<?
include("inc.php");
function menu_selector(){
	?>        <select name="order" id="order">
                <option<? if (!isset($_POST['order'])){
		$_POST['order'] = "default";
		echo " selected";
	}

	?>>Select Event Sorting...</option>
                <option>Default</option>
                <option value="all"<? 
	if (isset($_POST['order']) &$_POST['order'] == "all"){
		echo " selected";
	}

	?>>All</option>
                <option value="coming"<? 
	if (isset($_POST['order']) &$_POST['order'] == "coming"){
		echo " selected";
	}

	?>>Yet to Finish</option>
                <option value="gone"<? 
	if (isset($_POST['order']) &$_POST['order'] == "gone"){
		echo " selected";
	}

	?>>That Have Finished</option>
                <option value="nxten"<? 
	if (isset($_POST['order']) &$_POST['order'] == "nxten"){
		echo " selected";
	}

	?>>Next Ten</option>
                <option value="year"<? 
	if (isset($_POST['order']) &$_POST['order'] == "year"){
		echo " selected";
	}

	?>>Yearly</option>
              </select><?
}
function select_country(){ 
	// Note - Need to add the default country selector here
	$default = "all";
	if (isset($_POST['event_country_sel']) === false){
		$_POST['event_country_sel'] = $default;
	}

	?>			<select name="event_country_sel" id="event_country_sel">
	                <option value="all"<? if ($_POST['event_country_sel'] == "all"){
		echo " selected";
	}

	?>>All</option><?
	$sel_box_qu = mysql_query("SELECT DISTINCT event_country FROM event WHERE 1 GROUP BY event_country");
	while ($sel_box_res = mysql_fetch_assoc($sel_box_qu)){
		?>
	                <option value="<? echo $sel_box_res['event_country']; ?>"<? if ($_POST['event_country_sel'] == $sel_box_res['event_country']){
			echo " selected";
		}
		?>><? echo ucfirst($sel_box_res['event_country']);
		?></option>
	<? }

	?>		</select><?
}
function select_state(){ 
	// Note - Need to add the default country selector here
	$default = "all";
	if (isset($_POST['event_country_sel']) === false){
		$_POST['event_country_sel'] = $default;
	}
	if ($_POST['event_country_sel'] != "all"){
		if (isset($_POST['event_state_sel']) === false){
			$_POST['event_state_sel'] = "all";
		} ?>			State:
					<select name="event_state_sel" id="event_state_sel">
	                <option value="all"<? if ($_POST['event_state_sel'] == "all"){
			echo " selected";
		} ?>>All</option><?
		$sel_box_qu = mysql_query("SELECT DISTINCT event_state FROM event WHERE event_country = '".$_POST['event_country_sel']."' GROUP BY event_state");
		while ($sel_box_res = mysql_fetch_assoc($sel_box_qu)){

			?>
		                <option value="<? echo $sel_box_res['event_state'];

			?>"<? if ($_POST['event_state_sel'] == $sel_box_res['event_state']){
				echo " selected";
			}

			?>><? echo ucfirst($sel_box_res['event_state']);

			?></option>
		<? } ?>		</select><?
	}
}
function select_city(){ 
	// Note - Need to add the default country selector here
	$default = "all";
	if (isset($_POST['event_country_sel']) === false){
		$_POST['event_country_sel'] = $default;
	}
	if ($_POST['event_country_sel'] != "all"){
		if (isset($_POST['event_state_sel']) === false){
			$_POST['event_state_sel'] = "all";
		}
		if ($_POST['event_state_sel'] != "all"){
			if (isset($_POST['event_city_sel']) === false){
				$_POST['event_city_sel'] = "all";
			}

			?>			City:
						<select name="event_city_sel" id="event_city_sel">
		                <option value="all"<? if ($_POST['event_city_sel'] == "all"){
				echo " selected";
			}

			?>>All</option><?
			$sel_box_qu = mysql_query("SELECT DISTINCT event_city FROM event WHERE event_country = '".$_POST['event_country_sel']."' AND event_state = '".$_POST['event_state_sel']."' GROUP BY event_city");
			while ($sel_box_res = mysql_fetch_assoc($sel_box_qu)){

				?>
			                <option value="<? echo $sel_box_res['event_city'];

				?>"<? if ($_POST['event_city_sel'] == $sel_box_res['event_city']){
					echo " selected";
				}

				?>><? echo ucfirst($sel_box_res['event_city']);

				?></option>
			<? }

			?>		</select>
<?
		}
	}
}
function advanced_search(){
			?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#CACED9">
  <tr>
    <td><div align="center"> 
          <strong>Search for text: 
          
        <input name="search_text" type="text" id="search_text"<? if(isset($_POST[search_text])){print " value=\"".htmlentities(stripslashes($_POST[search_text]))."\""; } ?> SIZE="20">
          in 
          <select name="field" id="field">
            <option value="all"<? if(isset($_POST['field'])===false or $_POST['field']=="all"){?> selected<? } ?>>Any</option>
            <option value="name"<? if(isset($_POST['field'])===true & $_POST['field']=="name"){ echo " selected"; }?>>Event Name</option>
            <option value="description"<? if(isset($_POST['field'])===true & $_POST['field']=="description"){ echo " selected"; }?>>Event Description</option>
          </select>
          <br>
          <font color="#FFFFFF"> </font>
          <select name="time1" id="time1">
            <option value="on"<? if(isset($_POST['time1'])===false or $_POST['time1']=="on"){?> selected<? } ?>>Taking Place</option>
            <option value="start"<? if(isset($_POST['time1'])===false or $_POST['time1']=="start"){ echo " selected"; }?>>Starting</option>
            <option value="end"<? if(isset($_POST['time1'])===false or $_POST['time1']=="end"){ echo " selected"; }?>>Ending</option>
          </select>
          <font color="#FFFFFF"><font color="#000000">After </font> 
          <input name="date1" type="text" id="date1" size="20" maxlength="30" onFocus="getCalendarFor(this)"<? if(isset($_POST['date1'])===true){ echo $_POST['date1']; }?>>
        </font>and/or Before<font color="#FFFFFF"> 
        <input name="date2" type="text" id="date2" size="20" maxlength="30" onFocus="getCalendarFor(this)"<? if(isset($_POST['date2'])===true){ echo $_POST['date2']; }?>>
          </font></strong><font color="#FFFFFF"><strong> </strong></font> 
      </div></td>
  </tr>
</table><br />
<? 	
}
function error_msg(){

	?>
<table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
  <tr> 
    <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr> 
          <td valign="top"> <div align="center"> 
              <p><strong>Coming Events</strong></p>
            </div></td>
        </tr>
        <tr> 
          <td valign="top"> <form name="sort_sel" method="post" action="<?= $_SERVER[PHP_SELF] ?>">
              <div align="right">
              <? menu_selector();

	?>
                <input name="submit" type="submit" id="submit" value="Submit">
              </div>
            </form></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td> <div align="center"><strong>There are currently no specified events.</strong></div></td>
        </tr>
        <tr> 
          <td><div align="center"><a href="<?= $_SERVER[PHP_SELF] ?>?action=edit"><strong>Create 
              a new Event...</strong></a></div></td>
        </tr>
      </table></td>
  </tr>
</table><?
}
function read_info(){
	if (isset($_GET['ev_id']) AND is_numeric($_GET['ev_id'])){
		$single_qu = mysql_query("SELECT event_name,event_start_date,event_end_date,event_venue,event_street_no,event_street,event_city,event_state,event_country,event_admin,event_description,event_contact,event_time,event_info FROM event WHERE event_id = '".$_GET['ev_id']."'");
		if ($single_qu === false){
			return false;
		}else{
			if (mysql_num_rows($single_qu) < 1){
				return false;
			}else{
				$events_row = mysql_fetch_assoc($single_qu);
			}
		}
	}
	?>
	<table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
	  <tr> 
		<td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr> 
            <td colspan="2"><div align="center"> 
                <p><font color="#FFFFFF"><strong><?	echo "Event Details For: ".stripslashes($events_row['event_name']); ?></strong></font></p>
              </div></td>
          </tr>
          <tr> 
            <td width="100" background="includes/Events/events_left_top.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td width="506" background="includes/Events/events_top_bg.jpg" bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            
          <td width="100" valign="top" background="includes/Events/events_left_bg.jpg">
<div align="right"><strong>Venue 
                :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><strong> 
              <? echo stripslashes($events_row['event_venue']);	?>
              <br>
              </strong></tr>
          <tr valign="middle"> 
            
          <td valign="top" background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            
          <td width="100" valign="top" background="includes/Events/events_left_bg.jpg"> 
            <div align="right"><strong>Address 
                :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><strong><font color="#000000">
<? 
echo stripslashes($events_row['event_street_no'])." ";
echo stripslashes($events_row['event_street'])."\n<br>";
echo stripslashes($events_row['event_city'])." , ";
echo stripslashes($events_row['event_state'])."\n<br>";
echo stripslashes($events_row['event_country']);
     ?>
	 		</font></strong>
	 		</td>
          </tr>
          <tr valign="middle"> 
            
          <td valign="top" background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            
          <td width="100" valign="top" background="includes/Events/events_left_bg.jpg">
<div align="right"><strong>Time:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#000000"><strong><? echo stripslashes($events_row['event_time']); ?></strong></font></td>
          </tr>
          <tr valign="middle"> 
            
          <td valign="top" background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            
          <td valign="top" background="includes/Events/events_left_bg.jpg"> 
            <div align="right"><strong>Dates:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#000000"><strong>
            <?
		if ($events_row['event_start_date'] == $events_row['event_end_date']){
			echo date("j\<\s\u\p>S\<\/\s\u\p> M, Y", $events_row['event_start_date']);
		}else{
			echo date("j\<\s\u\p>S\<\/\s\u\p>", $events_row['event_start_date']);
			if (date("M", $events_row['event_start_date']) != date("M", $events_row['event_end_date'])){
				echo date(" M", $events_row['event_start_date']);
			}
			if (date("Y", $events_row['event_start_date']) != date("Y", $events_row['event_end_date'])){
				echo date(", Y", $events_row['event_start_date']);
			}
			echo date(" - j\<\s\u\p>S\<\/\s\u\p> M, Y", $events_row['event_end_date']);
		} ?></strong></font>
			</td>
		  </tr>
          <tr valign="middle"> 
            
          <td valign="top" background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
<!--          <tr valign="middle"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"></div></td>
            <td bgcolor="#FFFFFF"><font color="#000000"><strong> 
              <input type="checkbox" name="checkbox" value="checkbox">
              Administration only</strong></font> </tr>
          <tr valign="top"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr> -->
          <tr valign="top"> 
            
          <td valign="top" background="includes/Events/events_left_bg.jpg"> 
            <div align="right"><strong>Description:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#000000"><strong> 
              <? 
			if($description=str_replace("\r\n", "<br />", stripslashes($events_row['event_description']))){
			  	echo $description;
			}elseif($description=str_replace("\r", "<br />", stripslashes($events_row['event_description']))){
				echo $description;
			}elseif($description=str_replace("\n", "<br />", stripslashes($events_row['event_description']))){
				echo $description;
			}else{
				echo stripslashes($events_row['event_description']);
			}
				?>
              </strong></font> </tr>
          <tr valign="middle"> 
            
          <td valign="top" background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="top"> 
            
          <td valign="top" background="includes/Events/events_left_bg.jpg"> 
            <div align="right"><strong>Contact:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#000000"><strong><? 
			if($contact=str_replace("\r\n", "<br />", stripslashes($events_row['event_contact']))){
			  	echo $contact;
			}elseif($contact=str_replace("\r", "<br />", stripslashes($events_row['event_contact']))){
				echo $contact;
			}elseif($contact=str_replace("\n", "<br />", stripslashes($events_row['event_contact']))){
				echo $contact;
			}else{
				echo stripslashes($events_row['event_contact']);
			}
	?></strong></font>
			</td>
		  </tr>
        </table></td>
	  </tr>
	</table>
	<?
}
function form_make($error_fields, $type){

	global $Admin;

	if($Admin['events_edit']=="N"){
		return false;
	}
	$new = false;
	if ($type != "upload"){
		if (!isset($_GET['ev_id']) OR !is_numeric($_GET['ev_id'])){
			$new = true;
		}
		if ($new === false){
			$single_qu = mysql_query("SELECT event_id,event_name,event_start_date,event_end_date,event_venue,event_street_no,event_street,event_city,event_state,event_country,event_admin,event_description,event_contact,event_time,event_info FROM event WHERE event_id = '".$_GET['ev_id']."'");
			if ($single_qu === false){
				$new = true;
			}else{
				if (mysql_num_rows($single_qu) < 1){
					$new = true;
				}else{
					$events_row = mysql_fetch_assoc($single_qu);
				}
			}
		}
	}

	?>
	<a href="<?= $_SERVER[HTTP_REFERER]?>" class="nav">&lt;&lt; Back</a>
<form action="<?= $_SERVER[PHP_SELF] ?>?action=upload" method="post" name="event">
	<table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
	  <tr> 
		<td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
          <tr> 
            <td colspan="2"><div align="center"> 
                <p><font color="#FFFFFF"><strong><? if ($type == "upload"){
		echo $_POST['event_name'];
	}elseif ($new === false){
		echo "Edit Event: ".stripslashes($events_row['event_name']);
	}else{
		echo "New Event:";
	}

	?></strong></font></p>
              </div></td>
          </tr>
          <tr> 
            <td width="100" background="includes/Events/events_left_top.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td background="includes/Events/events_top_bg.jpg" bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Name:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="event_name" type="text" value="<? if ($type == "upload"){
		echo $_POST['event_name'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_name']);
	}

	?>" size="20" maxlength="30">
              <? if (is_array($error_fields) AND isset($error_fields['event_name'])){ ?>
              <br>
              </strong></font> 
              <font color="#990000"><strong> *<? echo $error_fields['event_name']; ?></strong></font> 
              <? }

	?>
            </td>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            <td width="100" background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Venue 
                :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="event_venue" type="text" id="event_venue" value="<? if ($type == "upload"){
		echo $_POST['event_venue'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_venue']);
	} 

	?>" size="20" maxlength="60">
              <br>
              </strong></font> 
              <? if (is_array($error_fields) AND isset($error_fields['event_venue'])){ ?>
              <font color="#990000"><strong> *<? echo $error_fields['event_venue']; ?></strong></font> 
              <? }

	?>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            <td width="100" background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Street Address 
                :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong><font color="#000000">Number: 
              </font><font color="#FFFFFF"><strong><font color="#000000">
              <input name="event_street_no" type="text" id="event_street_no3" value="<? if ($type == "upload"){
		echo $_POST['event_street_no'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_street_no']);
	}

	?>" size="10" maxlength="10">
              </font></strong></font><font color="#000000">Street Name:</font> 
              <input name="event_street" type="text" id="event_street" value="<? if ($type == "upload"){
		echo $_POST['event_street'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_street']);
	} 

	?>" size="20" maxlength="20">
              <? if (is_array($error_fields) AND isset($error_fields['event_street_no'])){ ?>
              <br>
              </strong></font> <font color="#990000"><strong> *<? echo $error_fields['event_street_no']; ?></strong></font> 
              <? }

	?>
              <? if (is_array($error_fields) AND isset($error_fields['event_street'])){ ?>
              <br>
              </strong></font> 
              <font color="#990000"><strong> *<? echo $error_fields['event_street']; ?></strong></font> 
              <? }

	?>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
            <td width="100" background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>City :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="event_city" type="text" id="event_city" value="<? if ($type == "upload"){
		echo $_POST['event_city'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_city']);
	}

	?>" size="20" maxlength="30">
              <? if (is_array($error_fields) AND isset($error_fields['event_city'])){ ?>
              <br>
              </strong></font> 
              <font color="#990000"><strong> *<? echo $error_fields['event_city']; ?></strong></font> 
              <? }

	?>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
            <td width="100" background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>State :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="event_state" type="text" id="event_state" value="<? if ($type == "upload"){
		echo $_POST['event_state'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_state']);
	}

	?>" size="20" maxlength="30">
              <? if (is_array($error_fields) AND isset($error_fields['event_state'])){ ?>
              <br>
              </strong></font> <font color="#990000"><strong> *<? echo $error_fields['event_state']; ?></strong></font> 
              <? }

	?>
            </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
            <td width="100" background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Country :&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="country_sel" type="radio" value="selected"<? if ($type != "upload"){
		echo " checked";
	}elseif ($_POST['country_sel'] == "entered" OR $new === true){
		echo " checked";
	}

	?>/>
              <select name="event_country_sel" id="event_country_sel">
<?
	$sel_box_qu = mysql_query("SELECT DISTINCT event_country FROM event WHERE 1 GROUP BY event_country");
	while ($sel_box_res = mysql_fetch_assoc($sel_box_qu)){ ?>
                <option value="<? echo $sel_box_res['event_country']; ?>"<? if ($type == "upload"){
			if ($_POST['country_sel'] == "selected" &$_POST['event_country_sel'] == $sel_box_res['event_country']){
				echo " selected";
			}
		}elseif($new===false & $events_row['event_state']==$sel_box_res['event_country']){
			echo " selected";
		}
		?>><? echo ucfirst($sel_box_res['event_country']); ?></option>
<? }

	?>
              </select>
              <input type="radio" name="country_sel" value="entered"<? if ($type == "upload"){
		if ($_POST['country_sel'] == "entered"){
			echo " checked";
		}
	}

	?> />
              <input name="event_country" type="text" id="event_country" value="<? if ($type == "upload" &$_POST['country_sel'] == "entered"){
		echo $_POST['event_country'];
	} 

	?>" size="20" maxlength="30" />
              <? if (is_array($error_fields) AND isset($error_fields['event_country'])){ ?>
              <br>
              </strong></font> <font color="#990000"><strong> *<? echo $error_fields['event_country']; ?></strong></font> 
              <? }

	?>
            </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            <td width="100" background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Time:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="event_time" type="text" id="event_time" value="<? if ($type == "upload"){
		echo $_POST['event_time'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_time']);
	} 

	?>" size="20" maxlength="30">
              <? if (is_array($error_fields) AND isset($error_fields['event_time'])){ ?>
              </strong></font><br>
              <font color="#990000"><strong> *<? echo $error_fields['event_time']; ?></strong></font>
              <? }

	?>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Start 
                Date:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <input name="event_start_date" type="text" id="event_start_date" size="50" value="<? if ($type == "upload"){
              		echo $_POST['event_start_date'];
	}elseif ($new === false){
		echo date("d-m-Y", $events_row['event_start_date']);
	}

	?>" size="50">
              <input type="button" value="Select Date" onClick="getCalendarFor(document.event.event_start_date)">
              <? if (is_array($error_fields) AND isset($error_fields['event_start_date'])){ ?>
              <br>
              <font color="#990000"><strong> *<? echo $error_fields['event_start_date']; ?></strong></font> 
              <? }

	?>
              </strong></font></tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>End 
                Date:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"> <p><font color="#FFFFFF"><strong> 
                <input name="event_end_date" type="text" id="event_end_date" value="<? if ($type == "upload"){
		echo $_POST['event_end_date'];
	}elseif ($new === false){
		echo date("d-m-Y", $events_row['event_end_date']);
	}

	?>" size="20" maxlength="30">
                <input type="button" value="Select Date" onClick="getCalendarFor(document.event.event_end_date)">
                <? if (is_array($error_fields) AND isset($error_fields['event_end_date'])){ ?>
                <br>
                <font color="#990000"><strong> *<? echo $error_fields['event_end_date']; ?></strong></font> 
                <? }

	?>
                </strong></font></p></tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
<!--          <tr valign="middle"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"></div></td>
            <td bgcolor="#FFFFFF"><font color="#000000"><strong> 
              <input type="checkbox" name="checkbox" value="checkbox">
              Administration only</strong></font> </tr>
          <tr valign="top"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr> -->
          <tr valign="top"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Description:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <textarea name="event_description" cols="60" rows="10" id="event_description"><? if ($type == "upload"){
		echo $_POST['event_description'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_description']);
	}

	?></textarea>
              </strong></font> </tr>
          <tr valign="middle"> 
            <td background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
            <td bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
          </tr>
          <tr valign="top"> 
            <td background="includes/Events/events_left_bg.jpg"> <div align="right"><strong>Contact:&nbsp;</strong></div></td>
            <td bgcolor="#FFFFFF"><font color="#FFFFFF"><strong> 
              <textarea name="event_contact" cols="60" rows="5" id="event_contact"><? if ($type == "upload"){
		echo $_POST['event_contact'];
	}elseif ($new === false){
		echo stripslashes($events_row['event_contact']);
	}

	?></textarea>
              </strong></font> </tr>
          <tr valign="middle" bgcolor="#97A5BB"> 
            <td colspan="2"><div align="right">
			<? if ($new === false AND $_POST['new'] != "true"){ ?>
				<input name="event_id" type="hidden" value="<? if ($type == "upload"){
			echo $_POST['event_id'];
		}else{
			echo $_GET['ev_id'];
		} ?>">
			<? }

	?>
				<input name="new" type="hidden" value="<? if ($new === true OR $_POST['new'] == "true"){
		echo "true";
	}else{
		echo "false";
	}

	?>">
                <input type="submit" name="Submit" value="Submit">
              </div></td>
          </tr>
        </table></td>
	  </tr>
	</table>
</form>
	<?
}
function default_html(){

	global $Admin;

	$order['min_rows'] = 1;
	if (isset($_POST['order']) & $_POST['order'] == "all"){
		$order['alternate'] = false;
		$order['where'] = "1";
	}elseif (isset($_POST['order']) & $_POST['order'] == "coming"){
		$order['alternate'] = false;
		$order['where'] = "event_start_date >= UNIX_TIMESTAMP()";
	}elseif (isset($_POST['order']) & $_POST['order'] == "gone"){
		$order['alternate'] = false;
		$order['where'] = "event_end_date <= UNIX_TIMESTAMP()";
		$order['limit'] = false;
	}elseif (isset($_POST['order']) & $_POST['order'] == "current"){
		$order['alternate'] = false;
		$order['where'] = "event_start_date <= UNIX_TIMESTAMP() AND event_end_date >= UNIX_TIMESTAMP()";
		$order['order'] = "event_end_date DESC";
	}elseif (isset($_POST['order']) & $_POST['order'] == "year"){
		$order['alternate'] = false;
		$order['where'] = "event_end_date >= UNIX_TIMESTAMP(NOW() - INTERVAL 6 MONTH) AND event_start_date <= UNIX_TIMESTAMP(NOW() + INTERVAL 6 MONTH)";
	}elseif (isset($_POST['order']) & $_POST['order'] == "month" &isset($_POST['month']) &isset($_POST['year'])){
		$order['alternate'] = false;
		$order['where'] = "((DATE_FORMAT(FROM_UNIXTIME(event_end_date),'%M') = '".$_POST['month']."' AND DATE_FORMAT(FROM_UNIXTIME(event_end_date),'%Y') = '".$_POST['year']."') OR (DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%M') = '".$_POST['month']."' AND DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%Y') = '".$_POST['year']."'))";
	}elseif ($_POST['advanced_search'] == "true"){
		if (isset($_POST['search_text']) & $_POST['search_text'] != ""){
			$string = trim($_POST['search_text']);
			$n = 1;
			while ($quo = strstr($string, "\"")){
				$quo = substr($quo,1,strlen($quo));
				$charlen = strlen($string) - (strlen($quo) + 2);
				$quo = str_replace(" ", "<nup>", $quo);
				$string = substr(str_replace(" ", "%", $string),0,$charlen)."%".$quo;
			}
			$string = "%".addslashes(str_replace("<nup>", " ", $string))."%";
			if ($_POST['field'] == "name"){
				$order['where'] = "(event_name LIKE '".$string."')";
			}elseif($_POST['field'] == "description"){
				$order['where'] = "(event_description LIKE '".$string."')";
			} else {
				$order['where'] = "((event_name LIKE '".$string."') OR (event_description LIKE '".$string."'))";
			}
		}
		if (isset($_POST['search_text'])===true & $_POST['search_text']!= "" & isset($_POST['date1'])===true & $_POST['date1'] != ""){
			$order['where'] .= " AND ";
		}
		if (isset($_POST['date1']) & $_POST['date1'] != "") {
			$time = split("[/.-]", $_POST['date1']);
			$timestr = $time[1]."/".$time[0]."/".$time[2];
			$time=strtotime($timestr);
			if($time!=-1){
				if($_POST['time1']=="start"){
					$order['where'] .= "(event_start_date >= '".$time."')";
				} elseif($_POST['time1']=="end") {
					$order['where'] .= "(event_end_date >= '".$time."')";
				} else {
					$order['where'] .= "(event_end_date >= '".$time."' AND event_start_date >= '".$time."')";
				}
			} else {
				$order['where'] .= "1";
			}
		}
		if (isset($_POST['date1'])===true & isset($_POST['date2'])===true & $_POST['date1']!="" & $_POST['date2']!=""){
			$order['where'] .= " AND ";
		}
		if (isset($_POST['date2']) & $_POST['date2'] != "") {
			$time = split("[/.-]", $_POST['date2']);
			$timestr = $time[1]."/".$time[0]."/".$time[2];
			$time=strtotime($timestr);
			if($time!=-1){
				if($_POST['time1']=="start"){
					$order['where'] .= "(event_start_date <= '".$time."')";
				} elseif($_POST['time1']=="end") {
					$order['where'] .= "(event_end_date <= '".$time."')";
				} else {
					$order['where'] .= "(event_end_date <= '".$time."' AND event_start_date <= '".$time."')";
				}
			} else {
				$order['where'] .= "1";
			}
		}
	}else{
		$order['alternate'] = true;
		$order['min_rows'] = 1;
		$order['where'] = "event_end_date >= UNIX_TIMESTAMP() AND event_start_date <= UNIX_TIMESTAMP(NOW() + INTERVAL 7 DAY)";
		$order['alt_where'] = "event_end_date >= UNIX_TIMESTAMP()";
		$order['limit'] = "0,10";
		$order['alt_limit'] = "0,5";
	}
	if (isset($_POST['event_country_sel']) === false){
		$_POST['event_country_sel'] = "all";
	}else{
		if (isset($_POST['event_state_sel']) === false){
			$_POST['event_state_sel'] = "all";
		}else{
			if (isset($_POST['event_city_sel']) === false){
				$_POST['event_city_sel'] = "all";
			}else{
				if ($_POST['event_city_sel'] != "all" and $_POST['event_city_sel'] != "all"){
					$location['city'] = true;
				}
			}
			if ($_POST['event_state_sel'] != "all" and $_POST['event_state_sel'] != "all"){
				$location['state'] = true;
			}
		}
		if ($_POST['event_country_sel'] != "all" and $_POST['event_country_sel'] != "all"){
			$location['country'] = true;
		}
	}
	$query = "SELECT event_id,event_name,event_start_date,event_end_date,event_description,event_admin,event_info FROM event WHERE ";
	if (isset($order['where'])){
		$query .= $order['where'];
		if ($location['country'] == true){
			$query .= " AND event_country = '".$_POST['event_country_sel']."'";
			if ($location['state'] == true){
				$query .= " AND event_state = '".$_POST['event_state_sel']."'";
				if ($location['city'] == true){
					$query .= " AND event_city = '".$_POST['event_city_sel']."'";
				}
			}
		}
	}else{
		if ($location['country'] == true){
			$query .= "event_country = '".$_POST['event_country_sel']."'";
			if ($location['state'] == true){
				$query .= " AND event_state = '".$_POST['event_state_sel']."'";
				if ($location['city'] == true){
					$query .= " AND event_city = '".$_POST['event_city_sel']."'";
				}
			}
		}else{
			return false;
		}
	}
	$query .= " ORDER BY ";
	if (isset($order['order']) &$order['order'] !== false){
		$query .= $order['order'];
	}else{
		$query .= "event_start_date";
	}
	if (isset($order['limit']) &$order['limit'] !== false){
		$query .= " LIMIT ";
		$query .= $order['limit'];
	}
	$events_qu = mysql_query($query);
	if ($events_qu === false OR mysql_num_rows($events_qu) < $order['min_rows']){
		if ($order['alternate'] == true){
			$query = "SELECT event_id,event_name,event_start_date,event_end_date,event_description,event_admin,event_info FROM event WHERE ";
			if (isset($order['alt_where'])){
				$query .= $order['alt_where'];
			}else{
				return false;
			}
			$query .= " ORDER BY ";
			if (isset($order['alt_order']) &$order['alt_order'] !== false){
				$query .= $order['alt_order'];
			}else{
				$query .= "event_start_date";
			}
			if (isset($order['alt_limit']) &$order['alt_limit'] !== false){
				$query .= " LIMIT ";
				$query .= $order['alt_limit'];
			}
			$events_qu = mysql_query($query);
			if ($events_qu === false){
				return false;
			}
		}else{
			return false;
		}
	}

	?>
<table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
  <tr> 
    <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr> 
          <td colspan="4" valign="top"> <div align="center"> 
              <strong>Coming Events<br>
                <font size="3"> 
                <? if (isset($_POST['month']) &isset($_POST['year'])){
		echo $_POST['month']." - ".$_POST['year'];
	}
	?>
                </font></strong>
            </div></td>
        </tr>
        <form name="sort_sel" method="post" action="<?= $_SERVER[PHP_SELF] ?>">
        <tr> 
          <td colspan="4" valign="top">
              <div align="right"><?
	if ($_POST['advanced_search'] == "true" or $_POST['advanced_search_start'] == "true"){
		advanced_search();
	}else{
		echo "Presets: ";
		menu_selector();
	}	?>			Country: 
<? 	select_country();
	select_state();
	select_city();	?>
                <br />
                <input name="advanced_search<? if(isset($_POST['advanced_search_start'])===false & isset($_POST['advanced_search'])===false){ echo "_start"; } ?>" type="checkbox" id="advanced_search<? if(isset($_POST['advanced_search_start'])===false){ echo "_start"; } ?>" value="true"<? if(isset($_POST['advanced_search'])===true or isset($_POST['advanced_search_start'])===true){ echo " checked"; } ?>>
                Advanced Search <input name="submit" type="submit" id="submit" value="Submit">
              </div>

          </td>
        </tr>
        </form>
        <tr> 
          <td width="100" background="includes/Events/events_left_top.jpg"><img src="spacer.gif" width="100" height="1"></td>
          <td colspan="3" background="includes/Events/events_top_bg.jpg" bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
        </tr>
        <?
	$n = 1;
	$lastmonth = "none";
	$changemonth = false;
	while ($events_row = mysql_fetch_assoc($events_qu)){
		if (isset($_POST['order']) &$_POST['order'] == "year"){
			$thismonth = getdate($events_row['event_start_date']);
			if (($thismonth['month'] != $lastmonth['month']) OR (isset($thismonth['month']) === false)){
				$lastmonth = getdate($events_row['event_start_date']);

				?>
        <tr> 
          <td width="100" background="includes/Events/events_month_top.jpg"><img src="spacer.gif" width="100" height="1"></td>
          <td colspan="3" bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td width="100" background="includes/Events/events_month_bg.jpg"></td>
          <td width="520" bgcolor="#FFFFFF" colspan="3"><strong> 
            <form action="<?= $_SERVER[PHP_SELF] ?>" method="post" name="month">
              <div align="center"> <font size="4" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input name="month" type="hidden" value="<? echo $thismonth['month'];

				?>">
                <input name="year" type="hidden" value="<? echo $thismonth['year'];

				?>">
                <input name="order" type="hidden" value="month">
                <a href="#" onClick="submit()"><? echo $thismonth['month'];

				?> 
                - <? echo $thismonth['year'];

				?></a> </font></div>
            </form>
            </strong> </td>
        </tr>
        <tr> 
          <td width="100" background="includes/Events/events_month_bot.jpg"><img src="spacer.gif" width="100" height="1"></td>
          <td colspan="3" bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
        </tr>
        <? $changemonth = true;
			}else{
				$changemonth = false;
			}
		}
		if ($n > 1 &$changemonth === false){

			?>
        <tr> 
          <td width="100" background="includes/Events/events_left_div.jpg"><img src="spacer.gif" width="100" height="1"></td>
          <td colspan="3" bgcolor="#FFFFFF"><img src="spacer.gif" width="1" height="1"></td>
        </tr>
        <?
		}else{
			$n++;
		} ?>
        <tr> 
          <td width="100" background="includes/Events/events_left_bg.jpg"><strong> 
            <?
		if ($events_row['event_start_date'] == $events_row['event_end_date']){
			echo date("j\<\s\u\p>S\<\/\s\u\p> M, Y", $events_row['event_start_date']);
		}else{
			echo date("j\<\s\u\p>S\<\/\s\u\p>", $events_row['event_start_date']);
			if (date("M", $events_row['event_start_date']) != date("M", $events_row['event_end_date'])){
				echo date(" M", $events_row['event_start_date']);
			}
			if (date("Y", $events_row['event_start_date']) != date("Y", $events_row['event_end_date'])){
				echo date(", Y", $events_row['event_start_date']);
			}
			echo date(" - j\<\s\u\p>S\<\/\s\u\p> M, Y", $events_row['event_end_date']);
		} ?>
            </strong></td>
          <td width="520" bgcolor="#FFFFFF"> 
            <?
		echo "<a href=\"". $_SERVER[PHP_SELF] ."?action=view&ev_id=".$events_row['event_id']."\">";
		$max_length = 60;
		if ($events_row['event_info'] == 1){
			if (strlen($events_row['event_name']." - ".$events_row['event_description']) <= $max_length){
				echo $events_row['event_name']." - ".$events_row['event_description'];
			}else{
				$num = $max_length - strlen($events_row['event_name']." - ");
				$short_description = substr($events_row['event_description'], 0, $num)."...";
				echo $events_row['event_name']." - ".$short_description;
			}
		}else{
			echo $events_row['event_name'];
		}
		echo "</a>\n"; ?>
          </td>
          <td bgcolor="#FFFFFF" width="20"><? if($Admin[edit_events] == "Y") { ?><a href="<?= $_SERVER[PHP_SELF] ?>?action=edit&ev_id=<? echo $events_row['event_id'];		?>">Edit...</a><? } else { ?>&nbsp;<? } ?></td>
          <td bgcolor="#FFFFFF" width="20"><? if($Admin[edit_events] == "Y") { ?><a href="<?= $_SERVER[PHP_SELF] ?>?action=delete&ev_id=<? echo $events_row['event_id'];		?>">Delete...</a><? } else { ?>&nbsp;<? } ?></td>
        </tr>
        <?
	}

	?>
        <tr> 
          <td colspan="4" align="center"><? if($Admin[edit_events] == "Y") { ?><a href="<?= $_SERVER[PHP_SELF] ?>?action=edit"><strong>Create a new Event...</strong></a><? } else { ?>&nbsp;<? } ?></td>
        </tr>
      </table></td>
  </tr>
</table>
<?

}
if (isset($_GET['action']) AND $_GET['action'] == "delete"){
	global $Admin;

	if($Admin['events_edit']=="N"){
		return false;
	}
	if (isset($_GET['ev_id']) AND is_numeric($_GET['ev_id'])){
		if (isset($_POST['accept']) AND $_POST['accept'] == "yes"){
			$delete_qu = mysql_query("DELETE FROM event WHERE event_id='".$_GET['ev_id']."'");
			if ($delete_qu === false){
				$result = "<b>The query failed</b><br />\n";
			}else{
				$result = "Event Deleted.";
			}

			?>
<table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
  <tr> 
    <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr> 
          <td><div align="center"> 
              <p><strong><? echo $result;

			?></strong></p>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
<br /><?
			$e = default_html();
			if ($e === false){
				error_msg();
			}
		}else{
			$event_qu = mysql_query("SELECT event_name FROM event WHERE event_id='".$_GET['ev_id']."'");
			$event_row = mysql_fetch_assoc($event_qu);

			?><table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
  <tr> 
    <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr align="right" valign="middle"> 
          <td width="88%"> 
            <div align="center"> 
              <p align="left"><strong>Are you sure that you want to delete the 
                event named:</strong> <? echo $event_row['event_name'];

			?></p>
            </div></td>
			  <form name="accept" method="post" action="<?= $_SERVER[PHP_SELF] ?>?action=delete&ev_id=<? echo $_GET['ev_id'];	?>">
          <td width="2%"> 
            <div align="right"> 
			  <br>
				  <input name="accept" type="hidden" id="accept" value="yes">
				  <input name="ok" type="submit" id="ok" value="Ok">
              </div>
          </td>
              </form>
			  <form name="accept" method="post" action="<?= $_SERVER[PHP_SELF] ?>">
          <td width="10%"> 
            <div align="right">
			  <br>
				  <input name="no" type="submit" id="no" value="Cancel">
              </div>
          </td>
			  </form>
        </tr>
      </table></td>
  </tr>
</table><?
		}
	}else{
		$e = default_html();
		if ($e === false){
			error_msg();
		}
	}
}elseif (isset($_GET['action']) AND $_GET['action'] == "view"){
	$e = read_info();
	if ($e === false){
		$e = default_html();
		if ($e === false){
			error_msg();
		}
	}
}elseif (isset($_GET['action']) AND $_GET['action'] == "edit"){
	$e = form_make(false, "edit");
	if ($e === false){
		$e = default_html();
		if ($e === false){
			error_msg();
		}
	}
}elseif (isset($_GET['action']) AND $_GET['action'] == "upload"){
	$error = false;
	if (!isset($_POST['event_name']) OR strlen($_POST['event_name']) < 8){
		$error_fields['event_name'] = "You must enter an event name and it must be greater than 8 Characters.";
		$error = true;
	}
	if (!isset($_POST['event_time']) OR strlen($_POST['event_time']) < 8){
		$error_fields['event_time'] = "You must enter an event time and it must be greater than 8 Characters.";
		$error = true;
	}
	if (!isset($_POST['event_venue']) OR strlen($_POST['event_venue']) < 6){
		$error_fields['event_venue'] = "You must enter an event venue and it must be greater than 6 Characters.";
		$error = true;
	}
	if (!isset($_POST['event_street_no'])){
		$error_fields['event_venue'] = "You must enter an event street number.";
		$error = true;
	}
	if (!isset($_POST['event_street']) OR strlen($_POST['event_street']) < 5){
		$error_fields['event_street'] = "You must enter an event street and it must be greater than 5 Characters.";
		$error = true;
	}
	if (!isset($_POST['event_city']) OR strlen($_POST['event_city']) < 2){
		$error_fields['event_city'] = "You must enter an event city and it must be greater than 2 Characters.";
		$error = true;
	}
	if (!isset($_POST['event_state']) OR strlen($_POST['event_state']) < 2){
		$error_fields['event_state'] = "You must enter an event state and it must be greater than 2 Characters.";
		$error = true;
	}
	if ($_POST['country_sel'] == "entered"){
		if (!isset($_POST['event_country']) OR strlen($_POST['event_country']) < 2){
			$error_fields['event_country'] = "You must enter an event country and it must be greater than 3 Characters.";
			$error = true;
		}
		$country = $_POST['event_country'];
	}else{
		if (!isset($_POST['event_country_sel']) OR strlen($_POST['event_country_sel']) < 2){
			$error_fields['event_country_sel'] = "You must enter an event country and it must be greater than 3 Characters.";
			$error = true;
		}
		$country = $_POST['event_country_sel'];
	}
	if (!isset($_POST['event_contact']) OR strlen($_POST['event_contact']) < 2){
		$error_fields['event_contact'] = "You must enter an event state and it must be greater than 3 Characters.";
		$error = true;
	}
	if (!isset($_POST['event_start_date'])){
		$error_fields['event_start_date'] = "You must enter an event start date.";
		$error = true;
	}else{
		$start_date = split("[/.-]", $_POST['event_start_date']);
		$start_date_string = $start_date[1]."/".$start_date[0]."/".$start_date[2];
		if (strtotime($start_date_string) === -1){
			$error_fields['event_start_date'] = "You must enter a valid event start date.";
			$error = true;
		}
	}
	if (!isset($_POST['event_end_date'])){
		$error_fields['event_end_date'] = "You must enter an event end date.";
		$error = true;
	}else{
		$end_date = split("[/.-]", $_POST['event_end_date']);
		$end_date_string = $end_date[1]."/".$end_date[0]."/".$end_date[2];
		if (strtotime($end_date_string) === -1){
			$error_fields['event_end_date'] = "You must enter a valid event end date.";
			$error = true;
		}
	}
	if (strtotime($start_date_string) > strtotime($end_date_string)){
		$error_fields['event_end_date'] .= " The end date must be later than the start date.";
		$error = true;
	}
	if (!isset($_POST['event_discription']) OR strlen($_POST['event_discription']) < 8){
		$event_info = 0;
	}else{
		$event_info = 1;
	}
	if (!isset($_POST['event_id']) AND $_POST['new'] != "true"){ ?><table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
  <tr> 
    <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr> 
          <td><div align="center"> 
              <p><strong>Fatal Error...</strong></p>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table><?
		$e = default_html();
		if ($e === false){
			error_msg();
		}
	}elseif ($error === true){
		$e = form_make($error_fields, "upload");
		if ($e === false){
			$e = default_html();
			if ($e === false){
				error_msg();
			}
		}
	}else{
		if ($_POST['new'] == "true"){ 
			// Below is the debug lines
			// echo "<b>This is a new addition</b><br />";
			$query = "INSERT INTO event (event_id,event_name,event_start_date,event_end_date,event_venue,event_street_no,event_street,event_city,event_state,event_country,event_admin,event_description,event_contact,event_time,event_info,event_icon) VALUES ('','".addslashes($_POST['event_name'])."', UNIX_TIMESTAMP('".$start_date[2]."-".$start_date[1]."-".$start_date[0]."'), UNIX_TIMESTAMP('".$end_date[2]."-".$end_date[1]."-".$end_date[0]."'), '".addslashes($_POST['event_venue'])."', '".addslashes($_POST['event_street_no'])."', '".addslashes($_POST['event_street'])."', '".addslashes(strtolower($_POST['event_city']))."', '".addslashes(strtolower($_POST['event_state']))."', '".addslashes(strtolower($country))."', '0', '".addslashes($_POST['event_description'])."', '".addslashes($_POST['event_contact'])."', '".addslashes($_POST['event_time'])."', '".$event_info."', '0')";
			$insert_qu = mysql_query($query);
			if ($insert_qu === false){
				$result = "<b>The query failed</b><br />\n"; 
				// echo "<b>Query:</b><br />\n";
				// echo $query."<br /><br />\n";
				// echo "<b>Mysql Said:</b><br />\n";
				// echo "ERROR ".mysql_errno($insert_qu).": ".mysql_error($insert_qu)."<br /><br />\n";
			}else{
				$result = "Upload Complete";
			}
		}else{ 
			// echo "<b>This is an updated addition</b>";
			$query = "UPDATE event SET event_name = '".addslashes($_POST['event_name'])."', event_start_date = UNIX_TIMESTAMP('".$start_date[2]."-".$start_date[1]."-".$start_date[0]."'), event_end_date = UNIX_TIMESTAMP('".$end_date[2]."-".$end_date[1]."-".$end_date[0]."'), event_venue = '".addslashes($_POST['event_venue'])."', event_street_no = '".addslashes($_POST['event_street_no'])."', event_street = '".addslashes($_POST['event_street'])."', event_city = '".addslashes(strtolower($_POST['event_city']))."', event_state = '".addslashes(strtolower($_POST['event_state']))."', event_country = '".addslashes(strtolower($country))."', event_admin = '0', event_description = '".addslashes($_POST['event_description'])."', event_contact = '".addslashes($_POST['event_contact'])."', event_time = '".addslashes($_POST['event_time'])."', event_info = '".$event_info."', event_icon = '0' WHERE event_id = '".$_POST['event_id']."'";
			$update_qu = mysql_query($query);
			if ($update_qu === false){
				$result = "<b>The query failed</b><br />\n"; 
				// echo "<b>Query:</b><br />\n";
				// echo $query."<br /><br />\n";
				// echo "<b>Mysql Said:</b><br />\n";
				// echo "ERROR ".mysql_errno().": ".mysql_error()."<br /><br />\n";
			}else{
				$result = "Upload Complete";
			}
		} ?><table width="620" border="0" cellpadding="1" cellspacing="0" class="Border">
  <tr> 
    <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="Heading2">
        <tr> 
          <td><div align="center"> 
              <p><strong><? echo $result; ?></strong></p>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
<br /><?
		$e = default_html();
		if ($e === false){
			error_msg();
		}
	}
}else{
	$e = default_html();
	if ($e === false){
		error_msg();
	}
}

?>
<script language="JavaScript">
if (document.all) {
 document.writeln("<div id=\"PopUpCalendar\" style=\"position:absolute; left:0px; top:0px; z-index:7; width:200px; height:77px; overflow: visible; visibility: hidden; background-color: #97A5BB; border: 0px none #000000\" onMouseOver=\"if(ppcTI){clearTimeout(ppcTI);ppcTI=false;}\" onMouseOut=\"ppcTI=setTimeout(\'hideCalendar()\',500)\">");
 document.writeln("<div id=\"monthSelector\" style=\"position:absolute; left:0px; top:0px; z-index:9; width:181px; height:27px; overflow: visible; visibility:inherit\">");}
else if (document.layers) {
 document.writeln("<layer id=\"PopUpCalendar\" pagex=\"0\" pagey=\"0\" width=\"200\" height=\"200\" z-index=\"100\" visibility=\"hide\" bgcolor=\"#97A5BB\" onMouseOver=\"if(ppcTI){clearTimeout(ppcTI);ppcTI=false;}\" onMouseOut=\"ppcTI=setTimeout('hideCalendar()',500)\">");
 document.writeln("<layer id=\"monthSelector\" left=\"0\" top=\"0\" width=\"181\" height=\"27\" z-index=\"9\" visibility=\"inherit\">");}
else {
 document.writeln("<p><font color=\"#FF0000\"><b>Error ! The current browser is either too old or too modern (usind DOM document structure).</b></font></p>");}
</script>
<noscript>
<p><font color="#FF0000"><b>JavaScript is not activated !</b></font></p></noscript>
<table width="200" border="0" cellpadding="4" cellspacing="0" vspace="0" hspace="0">
  <form name="ppcMonthList"><tr>
      <td align="center" bordercolor="#97A5BB" bgcolor="#97A5BB"><a href="javascript:moveMonth('Back')" onMouseOver="window.status=' ';return true;"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"><b>< 
        </b></font></a><font face="MS Sans Serif, sans-serif" size="1"> 
        <select name="sItem" onMouseOut="if(ppcIE){window.event.cancelBubble = true;}" onChange="switchMonth(this.options[this.selectedIndex].value)" style="font-family: 'MS Sans Serif', sans-serif; font-size: 9pt"><option value="0" selected>2000 &#8226; January</option><option value="1">2000 &#8226; February</option><option value="2">2000 &#8226; March</option><option value="3">2000 &#8226; April</option><option value="4">2000 &#8226; May</option><option value="5">2000 &#8226; June</option><option value="6">2000 &#8226; July</option><option value="7">2000 &#8226; August</option><option value="8">2000 &#8226; September</option><option value="9">2000 &#8226; October</option><option value="10">2000 &#8226; November</option><option value="11">2000 &#8226; December</option><option value="0">2001 &#8226; January</option></select>
        </font><a href="javascript:moveMonth('Forward')" onMouseOver="window.status=' ';return true;"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"><b> 
        ></b></font></a></td>
    </tr></form></table>
<table width="200" border="0" cellpadding="4" cellspacing="0" bordercolor="#304C78"  bgcolor="#97A5BB" vspace="0" hspace="0">
  <tr align="center" bordercolor="#97A5BB" bgcolor="#CFD3DE"> 
    <td width="20" bordercolor="#CFD3DE"><b><font face="MS Sans Serif, sans-serif" size="1">Su</font></b></td>
    <td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Mo</font></b></td>
    <td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Tu</font></b></td>
    <td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">We</font></b></td>
    <td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Th</font></b></td>
    <td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Fr</font></b></td>
    <td width="20" bordercolor="#CFD3DE"><b><font face="MS Sans Serif, sans-serif" size="1">Sa</font></b></td>
  </tr></table>
<script language="JavaScript">
if (document.all) {
 document.writeln("</div>");
 document.writeln("<div id=\"monthDays\" style=\"position:absolute; left:0px; top:52px; z-index:8; width:200px; height:17px; overflow: visible; visibility:inherit; background-color: #97A5BB; border: 0px none #000000\"> </div></div>");}
else if (document.layers) {
 document.writeln("</layer>");
 document.writeln("<layer id=\"monthDays\" left=\"0\" top=\"52\" width=\"200\" height=\"17\" z-index=\"8\" bgcolor=\"##97A5BB\" visibility=\"inherit\"> </layer></layer>");}
else {/*NOP*/}
</script>
</body>
</html>