<style>
<!--
.Border {
			border: 1px solid #004990 }

-->
</style>
<form method="POST" action="page.php?id=31">
   <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td bgcolor="#7B5EC1" colspan="3" height="23"  width="605" style="padding-left: 5"><b><font color="#FFFFFF">Search Calendar</font></b></td>
                  </tr>
                  <tr>
                    <td bgcolor="#004990" colspan="3" width="615" height="2">
                    <img border="0" src="images/layout_spacer.gif" width="2" height="2"><img border="0" src="images/layout_spacer.gif" width="1" height="2"></td>
                  </tr>
                  <tr>
                    <td colspan="3" width="615" height="2">
                    <img border="0" src="images/layout_spacer.gif" width="2" height="2"><img border="0" src="images/layout_spacer.gif" width="1" height="2"></td>
                  </tr>
                  <tr>
                    <td bgcolor="#E5ECF4">
                    <img border="0" src="images/layout_spacer.gif" width="10" height="2"></td>
                    <td>
                    <img border="0" src="images/layout_spacer.gif" width="10" height="2"></td>
                    <td width="100%">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="AutoNumber3">
                      <tr>
                        <td width="100%">
                        <img border="0" src="images/layout_spacer.gif" width="2" height="5"></td>
                      </tr>
                      <tr>
                        <td width="100%">
                        <table border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td>
                            <p align="left">Keywords</td>
                            <td>
                            <img border="0" src="images/layout_spacer.gif" width="10" height="5"></td>
                            <td>Category</td>
                            <td>
                            <img border="0" src="images/layout_spacer.gif" width="10" height="5"></td>
                            <td>
                            <img border="0" src="images/layout_spacer.gif" width="10" height="5"></td>
                          </tr>
                          <tr>
                            <td>
                            <input name="csearch" size="20" class="Border" style="float: left" value="<?= $_REQUEST[csearch] ?>"></td>
                            <td>
                            <img border="0" src="images/layout_spacer.gif" width="10" height="20"></td>
                            <td>
      <select size="1" name="cat">
      <option value="-">Any Category</option>
<?
$SQLQuery = "SELECT * FROM tbl_cal_cat ORDER BY Name";
$rs = dbRead($SQLQuery);

while($row = dbFetchArray($rs)) {
	if($_REQUEST[cat] == $row[FieldID]) {
		print "<option selected value=\"$row[FieldID]\">$row[Name]</option>";
	} else {
		print "<option value=\"$row[FieldID]\">$row[Name]</option>";
	}
}
?>      </select></td>
                            <td>
                            <img border="0" src="images/layout_spacer.gif" width="10" height="20"></td>
                            <td>
                            <input style="border:0" border="0" src="images/layout_submit.gif" name="I3" type="image" alt="Search" width="16" height="20"></td>
                          </tr>
                        </table>
                        </td>
                      </tr>
                      <tr>
                        <td width="100%">
                        <img border="0" src="images/layout_spacer.gif" width="2" height="5"></td>
                      </tr>
                    </table>
                    </td>
                    </tr>
                  <tr>
                    <td colspan="3" width="615" height="2">
                    <img border="0" src="images/layout_spacer.gif" width="2" height="2"><img border="0" src="images/layout_spacer.gif" width="1" height="4"></td>
                  </tr>
                  </table><?
# this serves three different forms of the calendar data:
# * a monthly view ($cm, $cy)
# * a daily view ($cm, $cd, $cy)
# * an individual item view ($cid)
# if we encounter an error display an event or a day, we display the current
# month (or the month of the requested day)

$begun = 0;
$cid = (int)$_REQUEST[cid];
$cy = (int)$_REQUEST[cy];
$cm = (int)$_REQUEST[cm];
$cd = (int)$_REQUEST[cd];

if (!$cm) $cm = date("m");
if (!$cy) $cy = date("Y");

$date = mktime(0,0,1,$cm,1,$cy);
if(!$events) $events = load_events($date,1);

// DISPLAY SINGLE EVENT ###############################################
if ($cid) { 
	foreach($events as $event) {
		if($event[FieldID] == $cid) {
			display_event_header($event[EventStartTime]);
			display_event($event, 0);
			display_event_footer();
			break;
		}
	}
// DISPLAY DAY EVENTS ################################################
} elseif ($cy && $cm && $cd) {
	if (checkdate($cm,$cd,$cy)) {
		$date = mktime(0,0,1,$cm,$cd,$cy);
			display_event_header($date);
			foreach ($events as $event) {
				if(date("j",$event[EventStartTime]) == $cd || date("j",$event[EventStartTime]) <= $cd && date("j",$event[EventFinishTime]) >= $cd) {
					display_event($event, 0);
				}
     		}
     		display_event_footer();
	} else {
		$errors[] = "The specified date (".htmlentities("$cy/$cm/$cd").") was not valid.";
		unset($cm); unset($cd); unset($cy);
	}
}

if ($cm && $cy && !checkdate($cm,1,$cy)) {
	$errors[] = "The specified year and month (".htmlentities("$cy, $cm").") are not valid.";
	unset($cm); unset($cy);
}


if ($errors) {
	foreach($errors as $i) {
	print "$i<br><br>";
	}
	exit(0);
}
    
# beginning and end of this month
$bom = mktime(0,0,1,$cm,  1,$cy);
$eom = mktime(0,0,1,$cm+1,0,$cy);

# last month and next month
$lm = mktime(0,0,1,$cm,0,$cy);
$nm = mktime(0,0,1,$cm+1,1,$cy);

$NextPrevURL = $PHP_SELF . "?id=$_REQUEST[id]&csearch=" . urlencode($_REQUEST[csearch]) . "&cat=$_REQUEST[cat]&cm=%m&amp;cy=%Y";

print '<table bgcolor="#d0d0d0" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#7B5EC1">
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td width="33%">'.strftime('<a style="color: #FFFFFF" href="'.$NextPrevURL.'">%B, %Y</a></td>', $lm).'
          <td width="33%" align="center" style="color: #FFFFFF"><b>'.strftime('%B, %Y', $bom).'</b></td>
          <td width="34%" align="right">'.strftime('<a style="color: #FFFFFF" href="'.$NextPrevURL.'">%B, %Y</a></td>', $nm).'</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td bgcolor="#004990" height="2"><img border="0" src="images/layout_spacer.gif" width="2" height="2"></td>
  </tr>
</table>';

# begin the calendar
echo '<table id="cal" bordercolor="#004990" width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse">',
     "\n",'<tr bgcolor="#B2C8DF">',"\n";
for ($i = 0; $i < 7; $i++) {
  echo '<th width="14%">', date("l",mktime(0,0,1,4,$i+1,2001)), "</th>\n";
}
echo "</tr>\n<tr>";

# generate the requisite number of blank days to get things started
for ($days = $i = date("w",$bom); $i > 0; $i--) {
  echo '<td bgcolor="#E5ECF4">&nbsp;</td>';
}

for ($i = 1; $i <= date("t",$bom); $i++) {
if($i == date("j") && $cm == date("m")) {
  echo '<td valign="top" bgcolor="#FFFFFF" height="100"><b><a class="day" style="color: #000000;" href="',
       $PHP_SELF, "?id=$_REQUEST[id]&csearch=" . urlencode($_REQUEST[csearch]) . "&cat=$_REQUEST[cat]&cm=$cm&amp;cd=$i&amp;cy=$cy",
       '">',$i,'</a></b>';
  echo '<div class="event" style="color: #000000;"><B>TODAY</B></div>';  
} else {
  echo '<td valign="top" bgcolor="#FFFFFF" height="100"><a class="day" href="',
       $PHP_SELF, "?id=$_REQUEST[id]&csearch=" . urlencode($_REQUEST[csearch]) . "&cat=$_REQUEST[cat]&cm=$cm&amp;cd=$i&amp;cy=$cy",
       '">',$i,'</a>';
}
  display_events_for_day(date("Y-m-",$bom).sprintf("%02d",$i),$events);
  echo '</td>';
  if (++$days % 7 == 0) echo "</tr>";
}

# generate the requisite number of blank days to wrap things up
for (; $days % 7; $days++) {
  echo '<td bgcolor="#E5ECF4">&nbsp;</td>';
}
echo "</table>\n";

# FUNCTIONS

/* display a <div> for each of the events that fall on a given day */
function display_events_for_day($day,$events) {
  global $PHP_SELF,$cm,$cy;
  foreach ($events as $event) {
  	$EventStartDay = date("Y-m-d",$event[EventStartTime]);
  	$EventFinishDay = date("Y-m-d",$event[EventFinishTime]);
    if (($EventStartDay <= $day && $EventFinishDay >= $day) || ($EventStartDay == $day)) {  	
    	if($event[BGColor]) {
      echo '<div class="event" style="background-color: '.$event[BGColor].';">',
           '<a href="',$PHP_SELF,"?id=$_REQUEST[id]&cid=$event[FieldID]&csearch=" . urlencode($_REQUEST[csearch]) . "&cat=$_REQUEST[cat]&amp;cm=$cm&amp;cy=$cy",'">',
           stripslashes(htmlentities($event['EventName'])),
           '</a></div>';
           $BGColor = "";
        } else {
      echo '<div class="event">',
           '<a href="',$PHP_SELF,"?id=$_REQUEST[id]&cid=$event[FieldID]&csearch=" . urlencode($_REQUEST[csearch]) . "&cat=$_REQUEST[cat]&amp;cm=$cm&amp;cy=$cy",'">',
           stripslashes(htmlentities($event['EventName'])),
           '</a></div>';  
        }
    }
  }
        echo '<div><img src="images/layout_spacer.gif" width="5" height="5"></div>';  
  
}

/* load a list of events, either for a particular day or a whole month */
function load_events($from, $whole_month=0) {
  /* we'll take advantage of the equality behavior of this date format */
  $from_date = date("Y-m-d", $from);
  $bom = mktime(0,0,1,date("m",$from),1,date("Y",$from));
  $eom = mktime(0,0,1,date("m",$from)+1,0,date("Y",$from));
  $to_date = date("Y-m-d", $whole_month ? $eom : $from);

  $events = $seen = array();

$SQLQuery = "SELECT tbl_cal_details.*, tbl_cal_cat.Name, tbl_cal_cat.BGColor AS BGColor FROM tbl_cal_details INNER JOIN tbl_cal_cat ON tbl_cal_details.EventCategoryID = tbl_cal_cat.FieldID WHERE EventStartTime > '$bom' AND EventStartTime < '$eom' ";

if($_REQUEST[csearch]) $SQLQuery .= "AND EventName LIKE '%$_REQUEST[csearch]%' "; 
if($_REQUEST[cat] && $_REQUEST[cat] != "-") $SQLQuery .= "AND EventCategoryID = '$_REQUEST[cat]' ";

$SQLQuery .= "ORDER BY EventStartTime,EventName";

$rs = dbRead($SQLQuery);
$RecordCount = dbRecordTotal($rs);

if($RecordCount < 1) return 0;
while($row = dbFetchArray($rs)) {
    if ($seen[$row[FieldID]]++) continue; # only want each event once!
    $events[] = $row;
}
  return $events;
}

function display_event_header($sday) {
print '<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#7B5EC1" colspan="3" height="23" style="padding: 5;"><img border="0" src="images/layout_spacer.gif" width="1" height="2"><font color="#FFFFFF"><b>'.date("l jS F Y", $sday).'</b></font></td>
  </tr>
  <tr>
    <td bgcolor="#004990" colspan="3" height="2"><img border="0" src="images/layout_spacer.gif" width="2" height="2"></td>
  </tr>
  <tr>
    <td colspan="3" height="2"><img border="0" src="images/layout_spacer.gif" width="2" height="2"></td>
  </tr>
  <tr>
    <td bgcolor="#E5ECF4"><img border="0" src="images/layout_spacer.gif" width="10" height="2"></td>
    <td><img border="0" src="images/layout_spacer.gif" width="10" height="2"></td>
    <td width="100%">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
  <td width="100%" colspan="2"><img border="0" src="images/layout_spacer.gif" width="2" height="10"></td>
</tr>';
}

function display_event_footer() {
print '</table>
  </td>
  </tr>
    <tr>
    <td colspan="3" height="2"><img border="0" src="images/layout_spacer.gif" width="4" height="4"></td>
  </tr>
</table>';
}

function display_event($event,$include_date=1) {

	$SQLQuery = "SELECT tbl_cal_venues.VenueName, tbl_cal_venues.VenueDetails, tbl_cal_venues_sub.LocationName, tbl_cal_venues_sub.LocationDetails FROM tbl_cal_venues INNER JOIN tbl_cal_venues_sub ON tbl_cal_venues_sub.VenueID = tbl_cal_venues.FieldID WHERE tbl_cal_venues_sub.FieldID = '$event[EventLocationID]'";
	$rs = dbRead($SQLQuery);
	$row = dbFetchArray($rs);

	$date_start = date("d-m-Y",$event[EventStartTime]);
	$date_end = date("d-m-Y",$event[EventFinishTime]);

	$venue = "<b>$row[LocationName]</b>";
	if($row[LocationDetails]) $venue .= nl2br(trim("<br>$row[LocationDetails]"));
	if($row[LocationName] != $row[VenueName]) $venue .= "<br>$row[VenueName]";
	if($row[VenueDetails]) $venue .= nl2br(trim("<br>$row[VenueDetails]"));
	
	if($event[EventLocationID] == "87" || $event[EventLocationID] == "59") {
	  $venue = "There are no location details available for this event";
	}
	
	if($event[EventFinishTime] < mktime()) $event[Comments] = "This event has already concluded.</b><br><br>" . $event[Comments];
		$comments .= "<b>Event Times</b>";
	if($event[EventAllDay] == "Y") {
	
		if($date_start != $date_end) { // MULTI ALL DAY EVENT
			$comments .= " (Multi-Day) [All Day Event]";
			$comments .= "<br>".date("l jS F Y",$event[EventStartTime])." to ".date("l jS F Y",$event[EventFinishTime]);			
		} else { // ALL DAY EVENT
			$comments .= "<br>".date("l jS F Y",$event[EventStartTime]);	
		}
	
	} else {
		if($date_start != $date_end) { // MULTI DAY EVENT
			$comments .= " (Multi-Day)";
			$comments .= "<br>".date("l jS F Y",$event[EventStartTime])." from " . date("g:i a",$event[EventStartTime]) . " to ".date("l jS F Y",$event[EventFinishTime]). " at " .date("g:i a",$event[EventFinishTime]);	
		} else { // SINGLE EVENT
			if($event[EventStartTime] == $event[EventFinishTime]) {
				$comments .= "<br>".date("l jS F Y",$event[EventStartTime])." from " . date("g:i a",$event[EventStartTime]);			
			} else {
				$comments .= "<br>".date("l jS F Y",$event[EventStartTime])." from " . date("g:i a",$event[EventStartTime]) . " to ".date("g:i a",$event[EventFinishTime]);			
			}
		}
	
	
	}
	
	if($event[Comments]) $comments .= "<br><br><b>Comments</b><br>$event[Comments]";
	
	//if($date_start != $date_end) $comments .= " (Multi-Day)";
	//if($event[EventAllDay] == "Y") $comments .= " [All Day Event]";
	
	//$comments .= "<br>".date("D jS M",$event[EventStartTime]). " from " .date("g:i a",$event[EventStartTime]);
	
	//$comments .= " END " . date("D jS M",$event[EventFinishTime]). " from " .date("g:i a",$event[EventFinishTime]);
  
  
  print '
<tr>
  <td width="100%" colspan="2"><b>'.$event[EventName].'</b> - ['.$event[Name].']</td>
</tr>
<tr>
  <td width="100%" colspan="2" bgcolor="#800080"><img border="0" src="images/layout_spacer.gif" width="2" height="1"></td>
</tr>
<tr>
  <td width="100%" colspan="2"><img border="0" src="images/layout_spacer.gif" width="2" height="10"></td>
</tr>
<tr>
  <td width="70%" style="padding-right: 5;" valign="top">'.$comments.'</td>
  <td width="30%" valign="top">';
  
  
  print'  <table border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#004990" width="100%">
      <tr>
        <td width="100%">'.$venue.'</td>
      </tr>
    </table>
    </td>
</tr>
  <tr>
    <td colspan="3" height="2"><img border="0" src="images/layout_spacer.gif" width="4" height="15"></td>
  </tr>';

}

?>
</form>
<table border="0" cellspacing="1">
  <tr>
    <td bgcolor="#D7CEEE"><img border="0" src="images/layout_spacer.gif" width="15" height="15"></td>
    <td><img border="0" src="images/layout_spacer.gif" width="5" height="5"></td>
    <td>Pastoral Care</td>
  </tr>
  <tr>
    <td bgcolor="#DAE5EF"><img border="0" src="images/layout_spacer.gif" width="15" height="15"></td>
    <td><img border="0" src="images/layout_spacer.gif" width="5" height="5"></td>
    <td>Teaching and Learning</td>
  </tr>
</table>