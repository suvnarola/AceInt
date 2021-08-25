<?

include("global.php");

add_kpi("53", "0");

//function getlables()  {

// Type or Members to get out.

if($_REQUEST['select']) {

?>
<form method="post">
<LINK REL="STYLESHEET" type="text/css" href="styles.css">

<table width="610" callspacing="0" cellpadding="3">
 <tr>
  <td bgcolor="#FFFFFF" align="center"></td>
 </tr>
</table>
<table width="610" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6" align="center" class="Heading">Unselect Members Not to be included in Labels</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="38" class="Heading2"><b></b></font></td>
		<td width="42" class="Heading2"><b></b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>

	<?

	 if($_REQUEST[memberslist]) {

	  $MemArray_tmp = explode(",", $_REQUEST[memberslist]);

	  foreach($MemArray_tmp as $key => $value) {
	   if($count == 0) {
	    $andor="";
	   } else {
	    $andor=",";
	   }

	   $MemArray.="".$andor."".$value."";

	   $count++;

	  }

	 }

	 if($_REQUEST['month'] != 0 && $_REQUEST['year'] != 0) {
	  $DateQuery = " AND (members.datejoined like '".$_REQUEST['year']."-".$_REQUEST['month']."-%')";
	 } else {
	  $DateQuery = "";
	 }

	 if($_REQUEST[type] == 1) {

	  $TypeArray = " AND (members.monthlyfeecash > 0)";
	  if($_REQUEST['excludefax']) {

	   $FaxQuery = " AND (members.faxno = '' or members.faxno is NULL)";

	  }

	 } elseif($_REQUEST[type] == 2) {

	  $TypeArray = " AND (members.monthlyfeecash = 0)";

	 } elseif($_REQUEST[type] == 3) {

	  $TypeArray = "";

	 }

	// Categories.

	if($_REQUEST[category]) {

	 $count=0;
	 foreach($_REQUEST[category] as $key => $value) {
	  if($count == 0) {
	   $andor="";
	  } else {
	   $andor=",";
	  }

	  $CatArray_tmp.="".$andor."".$value."";

	  $count++;

	  if($CatArray_tmp) {

	   $CatArray = "(categories.catid IN ($CatArray_tmp)) AND ";

	  }

	 }

	}

	if($_REQUEST[area]) {

	 $count=0;
	 foreach($_REQUEST[area] as $key => $value) {
	  if($count == 0) {
	   $andor="";
	  } else {
	   $andor=",";
	  }

	  $AreaArray_tmp.="".$andor."".$value."";

	  $count++;

	 }

	}

	// Generate Query.

	if($_REQUEST[area] != "") {

	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,area.FieldID FROM status,members,area left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.licensee = area.FieldID)) AND (area.FieldID IN ($AreaArray_tmp))) group by members.companyname";
	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_physical.FieldID FROM status,members,tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.area = tbl_area_physical.FieldID)) AND (tbl_area_physical.FieldID IN ($AreaArray_tmp))) group by members.companyname";
	 $sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_physical.FieldID
		FROM members
			inner
				join
					status
					on members.status = status.FieldID
			inner
				join
					tbl_area_physical
					on members.area = tbl_area_physical.FieldID
		        left outer join mem_categories on (members.memid = mem_categories.memid)
			left outer join categories on (mem_categories.category = categories.catid)
		WHERE ((( $CatArray (((status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery AND (members.monthlyfeecash > 0)))) AND (tbl_area_physical.FieldID IN ($AreaArray_tmp)))
		group by members.companyname";

	} elseif($_REQUEST[disarea] != "") {

	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,area.FieldID FROM status,members,area left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.licensee = area.FieldID)) AND (area.disarea = '$_REQUEST[disarea]')) group by members.companyname";
	 $sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_regional.FieldID FROM status,members,tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID)) AND (tbl_area_regional.FieldID = '$_REQUEST[disarea]')) group by members.companyname";

	} elseif($_REQUEST[state] != "") {

	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,area.FieldID FROM status,members,area left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.licensee = area.FieldID)) AND (area.state = '$_REQUEST[state]')) group by members.companyname";
	 $sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_states.FieldID FROM status,members,tbl_area_physical, tbl_area_regional,tbl_area_states left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_states.FieldID = '$_REQUEST[state]')) group by members.companyname";

	} elseif($_REQUEST[memberslist]) {

	 $sqlquery = "SELECT members.* FROM members WHERE ((memid IN ($MemArray)) AND (CID = ".$_SESSION['User']['CID'].")) group by members.companyname";

	} else {

	 $sqlquery = "SELECT categories.catid,mem_categories.FieldID,members.* FROM status,members left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) group by members.companyname";

	}

	$sqlQuery = dbRead($sqlquery);

$foo = 0;
while($row = mysql_fetch_assoc($sqlQuery)) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row['memid'] ?></td>
			<td width="200"><?= $row['companyname'] ?></td>
			<td width="38"></td>
			<td width="42"><?= $row['category'] ?></td>
			<td width="30"><input type="checkbox" name="id2[]" value="<?= $row['memid'] ?>" checked></td>
		</tr>
<?
$foo++;
}
?>
	<tr>
		<td colspan="6" align="right" bgcolor="#FFFFFF"><input type="submit" value="Continue" name="checktransactions"></td>
	</tr>
   </table>
  </td>
 </tr>
</table>

</form>

<?
	die;

}

if(!$_REQUEST['id2']) {

	 if($_REQUEST[memberslist]) {

	  $MemArray_tmp = explode(",", $_REQUEST[memberslist]);

	  foreach($MemArray_tmp as $key => $value) {
	   if($count == 0) {
	    $andor="";
	   } else {
	    $andor=",";
	   }

	   $MemArray.="".$andor."".$value."";

	   $count++;

	  }

	 }

	 if($_REQUEST['month'] != 0 && $_REQUEST['year'] != 0) {
	  $DateQuery = " AND (members.datejoined like '".$_REQUEST['year']."-".$_REQUEST['month']."-%')";
	 } else {
	  $DateQuery = "";
	 }

	 if($_REQUEST['vcfl']) {
 		$vcfl = " and mem_categories.description like '%vcfl%'";
	 }

	 if($_REQUEST[type] == 1) {

	  $TypeArray = " AND (members.monthlyfeecash > 0)";
	  if($_REQUEST['excludefax']) {

	   $FaxQuery = " AND (members.faxno = '' or members.faxno is NULL)";

	  }

	 } elseif($_REQUEST[type] == 2) {

	  $TypeArray = " AND (members.monthlyfeecash = 0)";

	 } elseif($_REQUEST[type] == 3) {

	  $TypeArray = "";

	 }

	// Categories.

	if($_REQUEST[category]) {

	 $count=0;
	 foreach($_REQUEST[category] as $key => $value) {
	  if($count == 0) {
	   $andor="";
	  } else {
	   $andor=",";
	  }

	  $CatArray_tmp.="".$andor."".$value."";

	  $count++;

	  if($CatArray_tmp) {

	   $CatArray = "(categories.catid IN ($CatArray_tmp)) AND ";

	  }

	 }

	}

	if($_REQUEST[area]) {

	 $count=0;
	 foreach($_REQUEST[area] as $key => $value) {
	  if($count == 0) {
	   $andor="";
	  } else {
	   $andor=",";
	  }

	  $AreaArray_tmp.="".$andor."".$value."";

	  $count++;

	 }

	}

	// Generate Query.

	if($_REQUEST[area] != "") {

	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,area.FieldID FROM status,members,area left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.licensee = area.FieldID)) AND (area.FieldID IN ($AreaArray_tmp))) group by members.companyname";
	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_physical.FieldID FROM status,members,tbl_area_physical left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.area = tbl_area_physical.FieldID)) AND (tbl_area_physical.FieldID IN ($AreaArray_tmp))) group by members.companyname";
	 $sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_physical.FieldID
		FROM members
			inner
				join
					status
					on members.status = status.FieldID
			inner
				join
					tbl_area_physical
					on members.area = tbl_area_physical.FieldID
		        left outer join mem_categories on (members.memid = mem_categories.memid)
			left outer join categories on (mem_categories.category = categories.catid)
		WHERE $CatArray status.mem_lists = 1 AND members.CID = ".$_SESSION['User']['CID']." $TypeArray$DateQuery$vcfl$FaxQuery AND tbl_area_physical.FieldID IN ($AreaArray_tmp)
		group by members.companyname";

	} elseif($_REQUEST[disarea] != "") {

	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,area.FieldID FROM status,members,area left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.licensee = area.FieldID)) AND (area.disarea = '$_REQUEST[disarea]')) group by members.companyname";
	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_regional.FieldID FROM status,members,tbl_area_physical, tbl_area_regional left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID)) AND (tbl_area_regional.FieldID = '$_REQUEST[disarea]')) group by members.companyname";
	 $sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_regional.FieldID
	FROM members
		inner
			join
				status
				on members.status = status.FieldID
		inner
			join
				tbl_area_physical
				on members.area = tbl_area_physical.FieldID
		inner
			join
				tbl_area_regional
				on tbl_area_physical.RegionalID = tbl_area_regional.FieldID


		left outer join mem_categories on (members.memid = mem_categories.memid)
		left outer join categories on (mem_categories.category = categories.catid)

	WHERE ((($CatArray (((status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$vcfl$FaxQuery))) AND (tbl_area_regional.FieldID = '$_REQUEST[disarea]'))
	group by members.companyname";

	} elseif($_REQUEST[state] != "") {

	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,area.FieldID FROM status,members,area left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.licensee = area.FieldID)) AND (area.state = '$_REQUEST[state]')) group by members.companyname";
	 //$sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_states.FieldID FROM status,members,tbl_area_physical, tbl_area_regional,tbl_area_states left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ((($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_states.FieldID = '$_REQUEST[state]')) group by members.companyname";
	 $sqlquery = "SELECT members.*,mem_categories.FieldID,categories.catid,tbl_area_states.FieldID
	FROM members
		inner
			join
				status
				on members.status = status.FieldID
		inner
			join
				tbl_area_physical
				on members.area = tbl_area_physical.FieldID
		inner
			join
				tbl_area_regional
				on tbl_area_physical.RegionalID = tbl_area_regional.FieldID
		inner
			join
				tbl_area_states
				on tbl_area_regional.StateID = tbl_area_states.FieldID

	left outer join mem_categories on (members.memid = mem_categories.memid)
	left outer join categories on (mem_categories.category = categories.catid)

	WHERE ((($CatArray (((status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$vcfl$FaxQuery))) AND (tbl_area_states.FieldID = '$_REQUEST[state]'))
	group by members.companyname";

	} elseif($_REQUEST[memberslist]) {

	 $sqlquery = "SELECT members.* FROM members WHERE ((memid IN ($MemArray)) AND (CID = ".$_SESSION['User']['CID'].")) group by members.companyname";

	} else {

	 //$sqlquery = "SELECT categories.catid,mem_categories.FieldID,members.* FROM status,members left outer join mem_categories on (members.memid = mem_categories.memid) left outer join categories on (mem_categories.category = categories.catid) WHERE ($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$FaxQuery)) group by members.companyname";
	 $sqlquery = "SELECT categories.catid,mem_categories.FieldID,members.*
	FROM members
		inner
			join
				status
				on members.status = status.FieldID

		left outer join mem_categories on (members.memid = mem_categories.memid)
		left outer join categories on (mem_categories.category = categories.catid)
	WHERE ($CatArray (((members.status = status.FieldID) AND (status.mem_lists = 1) AND (members.CID = ".$_SESSION['User']['CID']."))$TypeArray$DateQuery$vcfl$FaxQuery))
	group by members.companyname";

	}

} else {

	$counter = 1;

	$memberArray = $_REQUEST['id2'];
	foreach($memberArray as $key => $value) {

		if($counter == 1) {

			$memList .= $value;

		} else {

			$memList .= ",".$value;

		}

		$counter++;

	}

	 $sqlquery = "SELECT members.* FROM members WHERE memid in (" . $memList . ") group by members.companyname";

}

$PDFLabel = generate_labels($sqlquery);
send_to_browser($PDFLabel,"application/pdf","Labels.pdf","attachment");

//}

function generate_labels($sqlquery) {

 $query = dbRead($sqlquery);

 // Create & Open PDF-Object this is before the loop

 $pdf = pdf_new();
 //pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
 pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

 pdf_open_file($pdf,'');
 pdf_set_info($pdf, "Author","E Banc Trade Pty Ltd");
 pdf_set_info($pdf, "Title","Mailing Labels");
 pdf_set_info($pdf, "Creator", "Antony Puckey");
 pdf_set_info($pdf, "Subject", "Mailing Labels");
 pdf_begin_page($pdf, 595, 842);
 pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
 pdf_set_parameter($pdf, "textformat", "utf8");

 $counter = 0;
 $x = 0;
 $y = 0;

 while($row = mysql_fetch_assoc($query)) {

  $blah = addresslayout($row['CID']);

  if($row[postalsuburb]) {
   $suburb=" $row[postalsuburb]";
  } else {
   unset($suburb);
  }

  if($row[postalno]) {
   $streetno="$row[postalno] ";
  } else {
   unset($streetno);
  }

  //$addressbox="$row[contactname]\r\n$row[companyname]\r\n$streetno$row[postalname]$suburb\r\n$row[postalcity]  $row[postalstate] $row[postalpostcode]";


  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0);
  $font = pdf_findfont($pdf, "Verdana", "winansi", 0);
  pdf_setfont($pdf, $font, 12);
  //pdf_show_boxed($pdf, $addressbox, 30+$x, 710-$y, 170, 96, "left");

  pdf_set_text_pos($pdf, 30+$x, 806-$y);

  foreach($blah as $key => $value) {
    $addline = "";

    foreach($value as $key2) {
     if($row[$key2]) {
      $addline .= $row[$key2] ." ";
     }
    }

    if(trim($addline))  {

     $NewCompanyname = explode("|", wordwrap($addline, 25, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
     }
    }
  }

  $counter=$counter+1;

  if ($counter <= 2) {
   $x=$x+197;
  } elseif($counter > 2) {
   $x=0;
   $y=$y+115;
   $counter=0;
  }

  if ($y >= 701) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $x=0;
   $y=0;
   $counter=0;
  }

 }

 //close it up
 pdf_end_page($pdf);
 pdf_close($pdf);
 $buffer = PDF_get_buffer($pdf);

 pdf_delete($pdf);

 return $buffer;

}
/**
 * Trish Query = "SELECT members.memid FROM members WHERE ((memid IN (9312,9124)) AND (CID = 1))";
 */


?>