<?

 /**
  * Directory Email Select Form.
  */

 if(!$_REQUEST['selected']) {
  trans_session();
 }

 include("includes/directory_function.php");
 include("includes/zip.lib.php");

 if($_REQUEST['temp_name'])  {

  $temp_cat = serialize($_SESSION['Directory']['category']);

  dbWrite("insert into tbl_directory_temp (CID,TempName,TempCats,UserID) values ('".$_SESSION['User']['CID']."','".encode_text2($_REQUEST['temp_name'])."','".$temp_cat."','".$_SESSION['User']['FieldID']."')");

 }

 if($_REQUEST['select'])  {
?>
<form name="checktrans" method="post" action="body.php">
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
		<td colspan="6" align="center" class="Heading">Unselect Members Not to be included in Directory</td>
	</tr>
	<tr>
		<td width="40" class="Heading2"><b><?= get_word("1") ?>:</b></font></td>
		<td width="200" class="Heading2"><b><?= get_word("3") ?>:</b></font></td>
		<td width="250" class="Heading2"><b></b></font></td>
		<td width="38" class="Heading2"><b></b></font></td>
		<td width="42" class="Heading2"><b></b></font></td>
		<td width="30" class="Heading2">&nbsp;</td>
	</tr>

<?

  if($_REQUEST['fifty'] && $_REQUEST['gold']) {
   $fifty = " AND (members.fiftyclub in (1,2))";
  } elseif($_REQUEST['fifty']) {
   $fifty = " AND (members.fiftyclub in (1,2))";
  } elseif($_REQUEST['gold']) {
   $fifty = " AND (members.fiftyclub = 2)";
  } else {
   $fifty = "";
  }

  if($_SESSION['Directory']['top']) {

   if($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') {
    $OrderBy = "Order By categories.engcategory, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName, members.companyname";
   } else {
    $OrderBy = "Order By categories.category, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName, members.companyname";
   }

  } elseif($_SESSION['Directory']['topright']) {

   $OrderBy = "Group By mem_categories.memid Order By tbl_area_regional.RegionalName,members.postcode,members.companyname";

  } elseif($_SESSION['Directory']['topright']) {

   $OrderBy = "Group By mem_categories.memid Order By members.postcode,tbl_area_regional.RegionalName,categories.category,members.companyname";

  }


  if($_SESSION['Directory']['top'] || $_SESSION['Directory']['topright']) {

   if($_SESSION['Directory']['category'] && $_SESSION['Directory']['area']) {

    $CatComma = comma_seperate($_SESSION['Directory']['category']);
    $AreaComma = comma_seperate($_SESSION['Directory']['area']);
    $querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_physical,tbl_area_regional,tbl_area_states WHERE (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_physical.FieldID IN (".$AreaComma.")) group by tbl_area_states.FieldID");
    $count = 0;
    $STID = "";
    while($rowstate = mysql_fetch_assoc($querystate)) {
     if($count == 0) {
      $andor="";
     } else {
      $andor=",";
     }
      $STID.= "".$andor."".$rowstate['FieldID'];
      $count++;
    }

    if($_SESSION['Directory']['nat']) {
     $nats = "";
    } else {
     $nats = " or (mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid'].") or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, members.emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status WHERE ((((((mem_categories.category = categories.catid) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid) AND (members.status = status.FieldID)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].") AND (members.bdriven = 'N') AND (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.")))) $OrderBy");
    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE ((((((mem_categories.category = categories.catid) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid) AND (members.status = status.FieldID)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 and (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.")))) $OrderBy");
    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE ((((((mem_categories.category = categories.catid) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid) AND (members.status = status.FieldID)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 and (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.")$nats)) $OrderBy");

	$query = dbRead("

	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per as trade_per, members.date_per as date_per

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		categories.catid IN ($CatComma)	AND
		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 and
	    members.t_unlist != 1 and
		$mem
	    status.mem_dir = 1 AND
		tbl_area_physical.FieldID IN (".$AreaComma.")$nats

	$OrderBy

	");


   } elseif($_SESSION['Directory']['area']) {

    $AreaComma = comma_seperate($_SESSION['Directory']['area']);
    $querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_physical,tbl_area_regional,tbl_area_states WHERE (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_physical.FieldID IN (".$AreaComma.")) group by tbl_area_states.FieldID");
    $count = 0;
    $STID = "";
    while($rowstate = mysql_fetch_assoc($querystate)) {
     if($count == 0) {
      $andor="";
     } else {
      $andor=",";
     }
      $STID.= "".$andor."".$rowstate['FieldID'];
      $count++;
    }

    if($_SESSION['Directory']['nat']) {
     $nats = "";
    } else {
     $nats = " or (mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid'].") or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))))) AND (mem_categories.category != 0) AND (mem_categories.category = categories.catid)) $OrderBy");
    $query = dbRead("

	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 and
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		$mem
		mem_categories.category != 0 and
		tbl_area_physical.FieldID IN (".$AreaComma.")$nats

	$OrderBy

	");

   } elseif($_SESSION['Directory']['category'] && $_SESSION['Directory']['disarea']) {

    $CatComma = comma_seperate($_SESSION['Directory']['category']);
    $querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_regional,tbl_area_states WHERE (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."') group by tbl_area_states.FieldID");
    $rowstate = mysql_fetch_assoc($querystate);

    if($_SESSION['Directory']['nat']) {
     $nats = "";
    } else {
     $nats = " or (mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid'].") or (mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID'].")";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE ((((((mem_categories.category = categories.catid) AND (members.status = status.FieldID) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."' or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID']."))) $OrderBy");
    $query = dbRead("

	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		categories.catid IN ($CatComma) AND
		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		$mem
		status.mem_dir = 1 AND
		tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."' $nats

	$OrderBy

	");

   } elseif($_SESSION['Directory']['disarea']) {

    $querystate = dbRead("SELECT tbl_area_states.FieldID as FieldID FROM tbl_area_regional,tbl_area_states WHERE (tbl_area_regional.StateID = tbl_area_states.FieldID) AND (tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."') group by tbl_area_states.FieldID");
    $rowstate = mysql_fetch_assoc($querystate);

    if($_SESSION['Directory']['nat']) {
     $nats = "";
	} else {
     $nats = " or (mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid'].") or (mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID']." and members.CID = ".$_SESSION['Directory']['countryid'].")";
    }


    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."' or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID'].")))) AND (mem_categories.category != 0) AND (mem_categories.category = categories.catid)) $OrderBy");
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
	 	categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		$mem
		mem_categories.category != 0 and
		tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."' $nats

		$OrderBy

	");

   } elseif($_SESSION['Directory']['state'] && $_SESSION['Directory']['category']) {

    $StateComma = comma_seperate($_SESSION['Directory']['state']);
    $CatComma = comma_seperate($_SESSION['Directory']['category']);

    if($_SESSION['Directory']['nat']) {
     $nats = "";
    } else {
     $nats = " or (mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid'].")";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (categories.catid IN ($CatComma)) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)))) AND (mem_categories.category = categories.catid) AND (mem_categories.category != 0) AND (tbl_area_states.FieldID IN (".$StateComma.") or mem_categories.dir_nation = 1)) $OrderBy");
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		categories.catid IN ($CatComma) AND
		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		$mem
		mem_categories.category != 0 AND
		tbl_area_states.FieldID IN (".$StateComma.") $nats

		$OrderBy

	");

   } elseif($_SESSION['Directory']['state']) {

    $StateComma = comma_seperate($_SESSION['Directory']['state']);

    if($_SESSION['Directory']['nat']) {
     $nats = "";
    } else {
     $nats = " or (mem_categories.dir_nation = 1 and members.cid = ".$_SESSION['Directory']['countryid'].")";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)))) AND (mem_categories.category = categories.catid) AND (mem_categories.category != 0) AND (tbl_area_states.FieldID IN (".$StateComma.") or mem_categories.dir_nation = 1)) $OrderBy");
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		$mem
		mem_categories.category != 0 AND
		tbl_area_states.FieldID IN (".$StateComma.") $nats

		$OrderBy

	");

   } elseif($_SESSION['Directory']['category']) {

    $CatComma = comma_seperate($_SESSION['Directory']['category']);
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		categories.catid IN ($CatComma) AND
		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		$mem
		status.mem_dir = 1

		$OrderBy

	");

   } else {

    if($_SESSION['Directory']['PostCodes']) $PostCodeArray = "members.postcode IN (".$_SESSION['Directory']['PostCodes'].")";
    $query = dbRead("

		SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid

		FROM members

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

			inner
				join
					status
					on members.status = status.FieldID

			inner
				join
					mem_categories
					on members.memid = mem_categories.memid

			inner
				join
					categories
					on mem_categories.category = categories.catid

			left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

		WHERE

			members.CID = ".$_SESSION['Directory']['countryid']." and
			$bro
			$fifty
			categories.display_direct = 1 AND
			members.t_unlist != 1 AND
			status.mem_dir = 1 AND
			$mem
			$PostCodeArray

			$OrderBy

		");

   }

  } elseif($_SESSION['Directory']['bottom'])  {

    if($_SESSION['Directory']['type'] == 1) {
      $t1 = "categories.cont = '1' and";
    } elseif($_SESSION['Directory']['type'] == 2) {
      $t1 = "categories.rest_acco = '1' and";
    } elseif($_SESSION['Directory']['type'] == 3) {
   	  $t1 = "categories.rest_supp = '1' and";
 	} elseif($_SESSION['Directory']['type'] == 4) {
  	  $t1 = "categories.tourist = '1' and";
 	} elseif($_SESSION['Directory']['type'] == 5) {
  	  $t1 = "categories.gene_busi = '1' and";
 	} elseif($_SESSION['Directory']['type'] == 6) {
  	  $t1 = "categories.wed = '1' and";
 	}

   if($_SESSION['Directory']['state2']) {
     $query = dbRead("

	 SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, categories.gene_busi, categories.cont, categories.rest_acco, categories.rest_supp, categories.tourist, tbl_area_regional.RegionalName as disarea, tbl_area_states.StateName as state, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl

	 FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

	 	left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	 WHERE

		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		(tbl_area_states.FieldID = '".$_SESSION['Directory']['state2']."' or mem_categories.dir_nation = 1) AND
		$t1

		ORDER BY categories.category, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName ,members.companyname

	");

   } else  {

     $query = dbRead("
	 SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, categories.gene_busi, categories.cont, categories.rest_acco, categories.rest_supp, categories.tourist, tbl_area_regional.RegionalName as disarea, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl

	 FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

	 	left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	 WHERE

		members.CID = ".$_SESSION['Directory']['countryid']." and
		$bro
		$fifty
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		(tbl_area_regional.FieldID LIKE '".$_SESSION['Directory']['disarea2']."' or mem_categories.dir_nation = 1) AND
		$t1

		ORDER BY categories.category, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName ,members.companyname

	");

   }

  }

  if($_REQUEST['list'])  {
   if(checkmodule("SuperUser")) {
     $SearchReports = "all";
   } else {
     $SearchReports = $_SESSION['User']['ReportsAllowed'];
   }

  $SearchCID =  $_SESSION['User']['CID'];

   if($SearchReports == "all") {

    $query3 = dbRead("select FieldID from tbl_area_physical where CID = '$SearchCID'");
    while($row3 = mysql_fetch_assoc($query3)) {
     $at .= "$row3[FieldID],";
    }
    $at = substr($at, 0, -1);
    $area_array = $at;
   } else {
     $area_array = $_SESSION['User']['ReportsAllowed'];
   }

    $OrderBy = "Order By categories.category, tbl_area_physical.AreaName ,members.companyname";
    $prevdate = date("Y-m", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
    $query = dbRead("
	SELECT categories.category, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt

	FROM members

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

		inner
			join
				status
				on members.status = status.FieldID

		inner
			join
				mem_categories
				on members.memid = mem_categories.memid

		inner
			join
				categories
				on mem_categories.category = categories.catid

		left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1)

	WHERE

		members.CID = ".$_SESSION['User']['CID']." AND
		$bro
		$fifty
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		members.licensee IN ($area_array) AND
		mem_categories.category != 0 and
		categories.display_direct = 1 and
		datejoined like '$prevdate-%'

		$OrderBy

	");

  }
$_SESSION['Directory']['selected'] = 1;

$foo = 0;
while($row = mysql_fetch_assoc($query)) {

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;
?>
		<tr bgcolor="<?= $bgcolor ?>">
			<td width="40"><?= $row['memid'] ?></td>
			<td width="200"><?= $row['companyname'] ?></td>
			<td width="250"><?= $row['description'] ?></td>
			<td width="38"></td>
			<td align="right" width="125"><?= $row['category'] ?><br><?= $row['trade_per'] ?>% <br> <?= date("d-m-y", strtotime($row['date_per'])) ?></td>
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

<input type="hidden" value="1" name="selected">
<input type="hidden" name="page" value="directory_pre_send">
<input type="hidden" name="countryid" value="<?= $_SESSION['Directory']['countryid'] ?>">
</form>
<?
 } else {

 $_SESSION['Directory']['id2'] = $_REQUEST['id2'];
 $BufferNormal = directory(0,$_REQUEST['fifty'],$_REQUEST['gold']);
 $ZipFile = new zipfile();
 $ZipFile -> addFile($BufferNormal, "Directory.pdf");
 $BufferZipped = $ZipFile -> file();

 display_form();

 }

 function display_form() {

  global $BufferNormal, $BufferZipped;

  ?>
   <form method="GET" action="includes/directory_send.php">
   <input type="hidden" name="SendInfo" value="Email">
   <table border="0" cellpadding="1" cellspacing="1" width="639">
    <tr>
     <td class="Border">
      <table width="100%" border="0" cellpadding="4" cellspacing="0" style="border-collapse: collapse">
       <tr>
        <td class="Heading" colspan="2"><?= get_page_data("3") ?>.</td>
       </tr>
       <tr>
        <td bgcolor="#FFFFFF"><img src="images/Directory.gif" border="0"></td>
        <td bgcolor="#FFFFFF" width="100%">
         <table border="0" cellpadding="3" cellspacing="0" align="left" width="100%">
          <tr>
           <td><img src="images/icon_pdf.gif" border="0"></td>
           <td width="100%"><a href="includes/directory_send.php?SendInfo=Normal" class="nav">PDF Document (<?= GetFileSize(strlen($BufferNormal)); ?>)</a></td>
          </tr>
          <tr>
           <td><img src="images/icon_zip.gif" border="0"></td>
           <td width="100%"><a href="includes/directory_send.php?SendInfo=Zipped" class="nav">ZIP Archive (<?= GetFileSize(strlen($BufferZipped)); ?>)</a></td>
          </tr>
          <tr>
           <td colspan='2'><?= eval(" ?>".get_page_data("1")."<? ") ?>.<br><?= get_page_data("2") ?></td>
          </tr>
          <tr>
           <td colspan="2"><input type="text" size="24" name="EmailAddress" style="font-size: 8pt" value="<?= $_SESSION['Admin']['email'] ?>">&nbsp;<input style="font-size: 8pt;" type="Submit" name="SendEmail" value="<?= get_page_data("4") ?>">&nbsp;<input type="checkbox" value="ON" name="EmailUncompressed">&nbsp;<?= get_page_data("5") ?>.</td>
          </tr>
          <tr>
           <td colspan="2"><textarea rows="4" size="50"  name="message" cols = "50"><?= get_page_data("6") ?></textarea></td>
          </tr>
          <tr>
           <td colspan="2"><?= get_word("140") ?>.<br><br><a href="http://www.adobe.com/products/acrobat/readstep2.html"><img src="images/get_adobe_reader.gif" border="0"></a></td>
          </tr>
        </table>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   </form>
  </textarea><?

 }

 function trans_session() {

  $_SESSION['Directory']['category']  = array();

  $_SESSION['Directory']['area']      = (!empty($_SESSION['Directory']['area']) && $_SESSION['Directory']['area'] == $_REQUEST['area'])                ? $_SESSION['Directory']['area']      : $_REQUEST['area'];
  $_SESSION['Directory']['disarea']   = (!empty($_SESSION['Directory']['disarea']) && $_SESSION['Directory']['disarea'] == $_REQUEST['disarea'])       ? $_SESSION['Directory']['disarea']   : addslashes($_REQUEST['disarea']);
  $_SESSION['Directory']['state']     = (!empty($_SESSION['Directory']['state']) && $_SESSION['Directory']['state'] == $_REQUEST['state'])             ? $_SESSION['Directory']['state']     : $_REQUEST['state'];
  $_SESSION['Directory']['category']  = (!empty($_SESSION['Directory']['category']) && $_SESSION['Directory']['category'] == $_REQUEST['category'])    ? $_SESSION['Directory']['category']  : $_REQUEST['category'];
  $_SESSION['Directory']['top']       = (!empty($_SESSION['Directory']['top']) && $_SESSION['Directory']['top'] == $_REQUEST['top'])                   ? $_SESSION['Directory']['top']       : addslashes($_REQUEST['top']);
  $_SESSION['Directory']['topright']  = (!empty($_SESSION['Directory']['topright']) && $_SESSION['Directory']['topright'] == $_REQUEST['topright'])    ? $_SESSION['Directory']['topright']  : addslashes($_REQUEST['topright']);
  $_SESSION['Directory']['disarea2']  = (!empty($_SESSION['Directory']['disarea2']) && $_SESSION['Directory']['disarea2'] == $_REQUEST['disarea2'])    ? $_SESSION['Directory']['disarea2']  : addslashes($_REQUEST['disarea2']);
  $_SESSION['Directory']['type']      = (!empty($_SESSION['Directory']['type']) && $_SESSION['Directory']['type'] == $_REQUEST['type'])                ? $_SESSION['Directory']['type']      : addslashes($_REQUEST['type']);
  $_SESSION['Directory']['bottom']    = (!empty($_SESSION['Directory']['bottom']) && $_SESSION['Directory']['bottom'] == $_REQUEST['bottom'])          ? $_SESSION['Directory']['bottom']    : addslashes($_REQUEST['bottom']);
  $_SESSION['Directory']['countryid'] = (!empty($_SESSION['Directory']['countryid']) && $_SESSION['Directory']['countryid'] == $_REQUEST['countryid']) ? $_SESSION['Directory']['countryid'] : addslashes($_REQUEST['countryid']);
  $_SESSION['Directory']['PostCodes'] = (!empty($_SESSION['Directory']['PostCodes']) && $_SESSION['Directory']['PostCodes'] == $_REQUEST['PostCodes']) ? $_SESSION['Directory']['PostCodes'] : addslashes($_REQUEST['PostCodes']);
  $_SESSION['Directory']['state2'] 	  = (!empty($_SESSION['Directory']['state2']) && $_SESSION['Directory']['state2'] == $_REQUEST['state2']) 		? $_SESSION['Directory']['state2']    : addslashes($_REQUEST['state2']);
  $_SESSION['Directory']['selected']  = (!empty($_SESSION['Directory']['selected']) && $_SESSION['Directory']['selected'] == $_REQUEST['selected']) 	? $_SESSION['Directory']['selected']  : addslashes($_REQUEST['selected']);
  $_SESSION['Directory']['fifty']  	  = (!empty($_SESSION['Directory']['fifty']) && $_SESSION['Directory']['fifty'] == $_REQUEST['fifty']) 			? $_SESSION['Directory']['fifty'] 	    : addslashes($_REQUEST['fifty']);
  $_SESSION['Directory']['gold']  	  = (!empty($_SESSION['Directory']['gold']) && $_SESSION['Directory']['gold'] == $_REQUEST['gold']) 			? $_SESSION['Directory']['gold']      : addslashes($_REQUEST['gold']);
  $_SESSION['Directory']['nat']  	  = (!empty($_SESSION['Directory']['nat']) && $_SESSION['Directory']['nat'] == $_REQUEST['nat']) 			? $_SESSION['Directory']['nat']      : addslashes($_REQUEST['nat']);

 }

?>