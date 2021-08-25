<?
//include("includes/contactslicensees.php");
//include("/home/etxint/admin.etxint.com/includes/contactslicensees.php");
// start of directory.php

 ini_set("max_execution_time", 60);

 /**
  * Functions.
  */

 if($_REQUEST['list'])  {
  include("global.php");
  $_SESSION['Directory']['countryid'] = $_SESSION['User']['CID'];
  $buffer = directory();
  send_to_browser($buffer,"application/pdf","Directory.pdf","inline");
 }

 function directory($bd = false, $fif = false, $gold = false) {

  global $pdf, $font, $font2, $fontbold, $font2bold, $fontitalic, $displaydate, $current_category, $current_area, $pos_baseref, $pos, $pos2, $page, $index, $row1, $t1, $image, $imagef, $col, $col2, $col3;

  $Cquery=dbRead("select * from country where countryID = '".$_SESSION['Directory']['countryid']."'");
  $Crow = mysql_fetch_assoc($Cquery);

  if($bd) {
   $bro = "";
  } else {
   $bro = "members.bdriven = 'N' and";
  }

  if($fif && $gold) {
   $fifty = "members.fiftyclub in (1,2) and";
  } elseif($fif) {
   $fifty = "members.fiftyclub in (1,2) and";
  } elseif($gold) {
   $fifty = "members.fiftyclub = 2 and";
  } else {
   $fifty = "";
  }

  if($_SESSION['Directory']['selected'])  {
    $MemComma = comma_seperate($_SESSION['Directory']['id2']);
    //$mem = "members.memid IN (".$MemComma.") and";
    $mem = "mem_categories.FieldID IN (".$MemComma.") and";
  }

  if($_REQUEST['month']) {
    $d = date("Y-m-d", mktime(0,0,1,date("m")-$_REQUEST['month'],date("d"),date("Y")));
    $con = " date_per > '".$d."' and";
  } else {
    $con = "";
  }

  if($_SESSION['Directory']['top']) {

   if($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') {
    $OrderBy = "Order By categories.engcategory, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName, members.companyname";
   } else {
    $OrderBy = "Order By categories.category, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName, members.companyname";
   }

  } elseif($_SESSION['Directory']['topright']) {

   //$OrderBy = "Group By mem_categories.memid Order By tbl_area_regional.RegionalName,members.postcode,members.companyname";
   $OrderBy = "Group By mem_categories.memid Order By members.postcode,categories.category,members.companyname";

  } elseif($_SESSION['Directory']['topright']) {

   //$OrderBy = "Group By mem_categories.memid Order By members.postcode,tbl_area_regional.RegionalName,categories.category,members.companyname";
   $OrderBy = "Group By mem_categories.memid Order By members.postcode,categories.category,members.companyname";

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
     $nats = " or ($mem mem_categories.category IN ($CatComma) and mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1) or ($mem mem_categories.category IN ($CatComma) and mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.") and members.t_unlist != 1 and $con status.mem_dir = 1)";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, members.emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status WHERE ((((((mem_categories.category = categories.catid) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid) AND (members.status = status.FieldID)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].") AND (members.bdriven = 'N') AND (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.")))) $OrderBy");
    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE ((((((mem_categories.category = categories.catid) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid) AND (members.status = status.FieldID)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 and (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.")))) $OrderBy");
    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE ((((((mem_categories.category = categories.catid) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid) AND (members.status = status.FieldID)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 and (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.")$nats)) $OrderBy");

	$query = dbRead("

	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.date_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
    //$AreaComma = $_SESSION['Directory']['area'];
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
     $nats = " or ($mem mem_categories.category != 0 and mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1) or ($mem mem_categories.category != 0 and mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.") and members.t_unlist != 1 and $con status.mem_dir = 1)";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))))) AND (mem_categories.category != 0) AND (mem_categories.category = categories.catid)) $OrderBy");
    $query = dbRead("

	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
     $nats = " or ($mem mem_categories.category IN ($CatComma) and mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1) or ($mem mem_categories.category IN ($CatComma) and mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID']." and members.t_unlist != 1 and $con status.mem_dir = 1)";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE ((((((mem_categories.category = categories.catid) AND (members.status = status.FieldID) AND (categories.catid IN ($CatComma))) AND (members.memid = mem_categories.memid)) AND (((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)$mem) AND ((status.mem_dir = 1)))) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."' or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID']."))) $OrderBy");
    $query = dbRead("

	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
     $nats = " or ($mem mem_categories.category != 0 and mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1) or ($mem mem_categories.category != 0 and mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID']." and members.CID = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1)";
    }


    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_regional.FieldID = '".$_SESSION['Directory']['disarea']."' or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID = ".$rowstate['FieldID'].")))) AND (mem_categories.category != 0) AND (mem_categories.category = categories.catid)) $OrderBy");
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
     $nats = " or ($mem mem_categories.category IN ($CatComma) and mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1)";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (categories.catid IN ($CatComma)) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)))) AND (mem_categories.category = categories.catid) AND (mem_categories.category != 0) AND (tbl_area_states.FieldID IN (".$StateComma.") or mem_categories.dir_nation = 1)) $OrderBy");
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
     $nats = " or ($mem mem_categories.category != 0 and mem_categories.dir_nation = 1 and members.cid = ".$_SESSION['Directory']['countryid']." and members.t_unlist != 1 and $con status.mem_dir = 1)";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)))) AND (mem_categories.category = categories.catid) AND (mem_categories.category != 0) AND (tbl_area_states.FieldID IN (".$StateComma.") or mem_categories.dir_nation = 1)) $OrderBy");
    $query = dbRead("
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state,mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
	SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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

    if($_SESSION['Directory']['PostCodes']) $PostCodeArray = " members.postcode IN (".$_SESSION['Directory']['PostCodes'].") and ";

    $query = dbRead("

		SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid, members.trade_per, members.direct_ad, dir_ad, mem_categories.FieldID as fid

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
			$mem
			$PostCodeArray
			status.mem_dir = 1

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

   if($_SESSION['Directory']['area2']) {

    $AreaComma = comma_seperate($_SESSION['Directory']['area2']);
    //$AreaComma = $_SESSION['Directory']['area'];
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
     $nats = " or ($mem mem_categories.category != 0 and mem_categories.dir_nation = 1 and members.CID = ".$_SESSION['Directory']['countryid']." and ".$t1." status.mem_dir = 1) or ($mem mem_categories.category != 0 and mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID.") and ".$t1." status.mem_dir = 1)";
    }

    //$query = dbRead("SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, tbl_area_physical.AreaName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.memid FROM mem_categories,members,tbl_area_physical,tbl_area_regional,tbl_area_states,categories,status left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no AND tbl_members_email.type = 1) WHERE (((members.memid = mem_categories.memid) AND (members.status = status.FieldID) AND (((((members.CID = ".$_SESSION['Directory']['countryid'].")$bro$fifty and categories.display_direct = 1 AND (members.t_unlist != 1)) AND ((status.mem_dir = 1))$mem) AND (members.area = tbl_area_physical.FieldID) and (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID)) AND (tbl_area_physical.FieldID IN (".$AreaComma.") or mem_categories.dir_nation = 1 or (mem_categories.dir_state = 1 and tbl_area_states.FieldID IN (".$STID."))))) AND (mem_categories.category != 0) AND (mem_categories.category = categories.catid)) $OrderBy");
     $query = dbRead("
	 SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, categories.gene_busi, categories.cont, categories.rest_acco, categories.rest_supp, categories.tourist, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.trade_per
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
		$t1
		tbl_area_physical.FieldID IN (".$AreaComma.")$nats

		ORDER BY categories.category, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName ,members.companyname

	");

   } elseif($_SESSION['Directory']['state2']) {
     $query = dbRead("

	 SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, categories.gene_busi, categories.cont, categories.rest_acco, categories.rest_supp, categories.tourist, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.trade_per
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
		$mem
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		$t1
		(tbl_area_states.FieldID = '".$_SESSION['Directory']['state2']."' or mem_categories.dir_nation = 1)

		ORDER BY categories.category, mem_categories.dir_nation, tbl_area_states.StateName, mem_categories.dir_state, tbl_area_regional.RegionalName ,members.companyname

	");

   } elseif($_SESSION['Directory']['disarea2'])  {

     $query = dbRead("
	 SELECT categories.category, categories.engcategory, mem_categories.dir_nation, mem_categories.dir_state, tbl_area_states.StateName as State1, categories.gene_busi, categories.cont, categories.rest_acco, categories.rest_supp, categories.tourist, tbl_area_regional.RegionalName as disarea, members.fiftyclub, members.companyname, members.contactname, members.streetno, members.streetname, members.suburb, members.city, members.postcode, members.state, mem_categories.description, mem_categories.engdesc, members.phonearea, members.phoneno, members.faxarea, members.faxno, tbl_members_email.email as emailaddress, members.opt, webpageurl, members.trade_per

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
		$mem
		categories.display_direct = 1 AND
		members.t_unlist != 1 AND
		status.mem_dir = 1 AND
		$t1
		(tbl_area_regional.FieldID LIKE '".$_SESSION['Directory']['disarea2']."' or mem_categories.dir_nation = 1)

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

  $query1 = dbRead("SELECT * from country where countryID='".$_SESSION['Directory']['countryid']."'");
  $row1 = mysql_fetch_assoc($query1);

  /**
   * Set some start variables.
   */

  $current_category = "";
  $current_area = "";
  //$pos_baseref = 35;
  $pos_baseref = 25;
  $pos = 810;
  $pos2 = 0;
  $page = 1;
  $index = array();
  $accIndex = array();
  $image = array();
  $imagef = array();

  /**
   * Main Working Section.
   */

  $pdf = pdf_new();
  pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
  #pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
  pdf_open_file($pdf, '');
  #pdf_open_file($pdf);
  #pdf_begin_document($pdf, "directory.pdf", "compatibility 1.6");

  pdf_set_info($pdf, "Author","ETX International");
  pdf_set_info($pdf, "Title","ETX Directory");
  pdf_set_info($pdf, "Creator", "Antony Puckey");
  pdf_set_info($pdf, "Subject", "ETX Directory");
  pdf_set_value($pdf, compress, 9);
  pdf_begin_page($pdf, 595, 842);
  pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
  pdf_set_parameter($pdf, "textformat", "utf8");

  $font = pdf_findfont($pdf, "Tahoma", "winansi", 0);
  $font2 = pdf_findfont($pdf, "Arial", "winansi", 0);
  $font2bold = pdf_findfont($pdf, "ArialBold", "winansi", 0);
  $fontbold = pdf_findfont($pdf, "TahomaBold", "winansi", 0);
  $fontitalic = pdf_findfont($pdf, "VerdanaItalic", "winansi", 0);

  //$displaydate = date('l, jS F Y');
  $displaydate = date('jS F Y');

  /**
   * Loop around the Main Query and generate the PDF.
   */

  while($row = mysql_fetch_assoc($query)) {

   if(!$current_category) {
     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
     pdf_setfont($pdf, $font, 12);
	 pdf_set_text_pos($pdf, get_right_pos($row['category'], $pdf, "575", 12, $font), 840);
     //pdf_continue_text($pdf, $row['category']);
   }

   if($_SESSION['Directory']['top'] || $_SESSION['Directory']['bottom'])  {

     if($_SESSION['Country']['Langcode'] != $Crow['Langcode'] && $Crow['english'] == 'N') {
       $retrieved_category = $row['engcategory'];
     } else {
       $retrieved_category = $row['category'];
     }

     if($row['dir_nation'] == 9 && $row['dir_state'] == 9) {
      $retrieved_state = $row['State1'];
      $retrieved_area = $row['disarea'];
     } elseif($row['dir_nation'] == 9 && $row['dir_state'] == 1) {
      $retrieved_state = $row['State1'];
	 }


   } elseif($_SESSION['Directory']['topright'])  {

     $retrieved_category = $row['postcode'];
     $retrieved_area = $row['category'];

   } elseif($_REQUEST['list']) {

     $retrieved_category = $row['category'];
     $retrieved_area = $row['disarea'];

   }

   if($retrieved_category != $current_category) {

     $Check = true;

    $current_category = $retrieved_category;
    $pos = check_for_next_page($row,$pos,'category');
    $pos = display_category_header($current_category,$pos,$pos2,$font2);
    add_index($current_category,$page);

    //if($row['dir_nation'] == 1 && !$_SESSION['Directory']['nat']) {
    if($row['dir_nation'] == 1) {

      $pos = check_for_next_page($row,$pos,'area');
      $pos = display_area_header('National Members',$pos,$pos2,$font2);

    }
   }

   if($retrieved_state != $current_state || $Check && $row['dir_nation'] == 9) {

     $Check2 = true;
     $current_state = $retrieved_state;
     $pos = check_for_next_page($row,$pos,'state');
     $pos = display_state_header($current_state,$pos,$pos2,$font2);

    //if($row['dir_state'] == 1 && !$_SESSION['Directory']['nat']) {
    if($row['dir_state'] == 1) {
      $pos = check_for_next_page($row,$pos,'area');
      $pos = display_area_header($row['State1'].' - Statewide',$pos,$pos2,$font2);
	  $Check = false;
    }

   }

   if(($retrieved_area != $current_area) || ($Check && $row['dir_nation'] != 1) ||$Check2 && $row['dir_state'] != 1) {

    if($row['dir_state'] == 9 )  {
     $current_area = $retrieved_area;
     $pos = check_for_next_page($row,$pos,'area');
     $pos = display_area_header($current_area,$pos,$pos2,$font2);
	 $Check = false;
	 $Check2 = false;
	}
   }

   $pos = check_for_next_page($row,$pos,'member');
   $pos = display_entry($row,$pos,$pos2,$font2);

   //if($row['direct_ad'] == 1) {
   if($row['dir_ad'] == 1) {

	//$pdfimage = pdf_open_image_file($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$row['memid'].".jpg");
	//pdf_place_image($pdf, $pdfimage, 15+$pos2, $pos-175, 1);

    //$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$row['memid'].".jpg",'');
    $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$row['fid'].".jpg",'');
    pdf_fit_image($pdf, $pdfimage, 20+$pos2,  $pos-165, "scale 1");

	$pos = $pos-175;

   //} elseif($row['direct_ad'] == 2) {
   } elseif($row['dir_ad'] == 3) {

	$col = $col + 270;
	//$image[] = $row['memid'];
	$image[] = $row['fid'];

   //} elseif($row['direct_ad'] == 3) {
   } elseif($row['dir_ad'] == 4) {

	//$imagef[] = $row['memid'];
	$imagef[] = $row['fid'];

   } else {

	//$pos = check_for_next_page($row,$pos,'member');
	//$pos = display_entry($row,$pos,$pos2,$font2);

   }

   if($fif || $gold) {

	addAccNum($row['memid'],$row['companyname']);

   }

  }

  // end page number.

  pdf_setlinewidth($pdf, 0.5);
  pdf_moveto($pdf, 15, 15);
  pdf_lineto($pdf, 582, 15);
  pdf_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 8);
  //pdf_set_text_pos($pdf, 542, 12);
  pdf_set_text_pos($pdf, get_right_pos(get_word("175")." $page", $pdf, "574", 8, $font), 12);
  pdf_continue_text($pdf, get_word("175")." $page");

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 8);
  pdf_set_text_pos($pdf, 20, 12);
  pdf_continue_text($pdf, $displaydate);

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 5);
    $text = "Disclaimer: the trade percentages listed is indicative only and represents the most recent known trade component the member has accepted.  All members have the flexibility to change their trade percentages at any time, unless they are Club members trading with other Club members.";
    $NewText = explode("|", wordwrap($text, 145, "|"));

    foreach($NewText as $Line) {
	  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297", 5, $font), 12-$p);
	  pdf_continue_text($pdf, $Line);
	  $p = $p+5;
    }

  pdf_end_page($pdf);
  pdf_begin_page($pdf, 595, 842);
  $pos = 810;

  unset($pp);
  $pp = explode(".", $page/2);
  if($pp[1] > 0) {
   pdf_end_page($pdf);
   pdf_begin_page($pdf, 595, 842);
   $pos = 810;
  }

  // index page.
  cover();

  display_index($font2);

  if($fif || $gold) {

    pdf_end_page($pdf);
    pdf_begin_page($pdf, 595, 842);
	displayAccNumIndex();

  }

  // finish off the pdf.

  pdf_end_page($pdf);

  //if($_SESSION['Directory']['state']) {
   //pdf_begin_page($pdf, 595, 842);
   //cont('1');
   //pdf_end_page($pdf);
  //}

  pdf_close($pdf);

  $buffer = PDF_get_buffer($pdf);
  pdf_delete($pdf);

  return $buffer;

 }

 function displayAccNumIndex() {

  global $accIndex, $pdf, $font, $pos_baseref, $row1, $t1;

  $pos = 810;
  $offset2 = 10;
  $offset = 0;
  $count = 1;
  $Foo = 1;

  ksort($accIndex);
  reset($accIndex);

  foreach($accIndex as $content => $value) {

   $pos = $pos - $offset2;

   // category name

   $Foo % 2 ? $Colour = "0" : $Colour = "0";

   pdf_setcolor($pdf, "fill", "rgb", $Colour, $Colour, $Colour, $Colour);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 50+$offset, $pos+15);

   $textheight = 0;

   $NewDesc = explode("|", wordwrap($value, 50, "|"));
   foreach($NewDesc as $Line) {
    pdf_continue_text($pdf, $Line);
    $textheight += 8;
   }

   $pos = $pos - $textheight + 8;

   // page number

   pdf_setcolor($pdf, "fill", "rgb", $Colour, $Colour, $Colour, $Colour);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 270+$offset, $pos+15);
   pdf_continue_text($pdf, $content);

   $Foo++;

   if($pos < $pos_baseref) {

    $offset = 280;
    $pos = 810;

    if($count == 2) {

     pdf_end_page($pdf);
     pdf_begin_page($pdf, 595, 842);

     $offset = 0;
     $count = 1;

    } else {

     $count = 2;

    }

   }

  }

 }

 function display_index($font2) {

  global $index, $pdf, $font, $pos_baseref, $row1, $t1;

  $pos = 810;
  $offset2 = 10;
  $offset = 0;
  $count = 1;
  $Foo = 1;

  if(!$_SESSION['Directory']['category']) {
   if($_SESSION['Directory']['bottom'])  {
    //$type = " and ".$t1;
    $type = " ".$t1;
   }

   $OtherCategories = dbRead("select categories.* from categories where display_drop = 'N' and$type CID = " . $_SESSION['Directory']['countryid']);
   while($OtherCategoriesRow = mysql_fetch_assoc($OtherCategories)) {

    $index[$OtherCategoriesRow[category]] = "--";

   }
  }
  ksort($index);
  reset($index);

  foreach($index as $content => $value) {

   $pos = $pos - $offset2;

   // category name

   $Foo % 2 ? $Colour = "0" : $Colour = "0";

   pdf_setcolor($pdf, "fill", "rgb", $Colour, $Colour, $Colour, $Colour);
   pdf_setfont($pdf, $font2, 8);
   pdf_set_text_pos($pdf, 50+$offset, $pos+15);

   $textheight = 0;

   $NewDesc = explode("|", wordwrap($content, 50, "|"));
   foreach($NewDesc as $Line) {
    pdf_continue_text($pdf, $Line);
    $textheight += 8;
   }

   $pos = $pos - $textheight + 8;

   // page number

   pdf_setcolor($pdf, "fill", "rgb", $Colour, $Colour, $Colour, $Colour);
   pdf_setfont($pdf, $font, 8);
   pdf_set_text_pos($pdf, 270+$offset, $pos+15);
   pdf_continue_text($pdf, $value);

   $Foo++;

   if($pos < $pos_baseref) {

    $offset = 280;
    $pos = 810;

    if($count == 2) {

     pdf_end_page($pdf);
     pdf_begin_page($pdf, 595, 842);

     $offset = 0;
     $count = 1;

    } else {

     $count = 2;

    }

   }

  }

 }

 function add_index($category,$page) {

  global $index, $row1;

  $index[$category] = $page;

 }

 function addAccNum($accNo,$companyName) {

  global $accIndex;

  $accIndex[$accNo] = $companyName;

 }

 function display_category_header($category_name,$pos,$pos2,$font2) {

  global $pdf, $font, $fontbold, $BookMark;

  //$BookMark = pdf_add_bookmark($pdf, $category_name, '');
  //$BookMark = pdf_create_bookmark($pdf, $category_name);

  $pos = $pos - 20;

  pdf_setlinewidth($pdf, 1);
  pdf_moveto($pdf, 19+$pos2, $pos+18);
  pdf_lineto($pdf, 149+$pos2, $pos+18);
  pdf_stroke($pdf);

  pdf_setlinewidth($pdf, 2);
  pdf_moveto($pdf, 19+$pos2, $pos+22);
  pdf_lineto($pdf, 149+$pos2, $pos+22);
  pdf_stroke($pdf);

  if($_SESSION['Directory']['countryid'] == 1) {
   $name = strtoupper($category_name);
  } else {
   $name = $category_name;
  }

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontbold, 12);
  $Newarea = explode("|", wordwrap($name, 15, "|"));
  $counter = 0;
  $c = 0;

  pdf_set_text_pos($pdf, 20+$pos2, $pos+17-$c);

  foreach($Newarea as $Line) {
	pdf_continue_text($pdf, $Line);
	if($counter > 0) {
      $c = $c+12;
	}
	$counter++;
  }

  pdf_setlinewidth($pdf, 1);
  pdf_moveto($pdf, 19+$pos2, $pos-$c);
  pdf_lineto($pdf, 149+$pos2, $pos-$c);
  pdf_stroke($pdf);;

  pdf_setlinewidth($pdf, 2);
  pdf_moveto($pdf, 19+$pos2, $pos-4 - $c);
  pdf_lineto($pdf, 149+$pos2, $pos-4 - $c);
  pdf_stroke($pdf);

  $pos = $pos - 17 - $c;

  return $pos;

 }

 function display_state_header($category_name,$pos,$pos2,$font2) {

  global $pdf, $font, $fontbold, $BookMark;

  $pos = $pos - 6.5;

  pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
  pdf_setlinewidth($pdf, 0.5);
  pdf_moveto($pdf, 19+$pos2, $pos+16);
  pdf_lineto($pdf, 149+$pos2, $pos+16);
  pdf_stroke($pdf);

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontbold, 10);
  pdf_set_text_pos($pdf, 18+$pos2, $pos+15);

  pdf_set_text_pos($pdf, get_left_pos($category_name, $pdf, "84", 10, $fontbold)+$pos2, $pos+12);
  pdf_continue_text($pdf, $category_name);

  $pos = $pos - 6;

  return $pos;

 }

 function display_area_header($area_name,$pos,$pos2,$font2) {

  global $pdf, $font, $fontitalic, $fontbold, $row1, $BookMark;

  //pdf_add_bookmark($pdf, $area_name, $BookMark);
  //pdf_create_bookmark($pdf, $area_name, $BookMark);

  $pos = $pos - 10;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $fontbold, 10);
  $Newarea = explode("|", wordwrap($area_name, 20, "|"));
  $counter = 0;
  foreach($Newarea as $Line) {
    if($counter > 0) {
      $c = $c+10;
	}
	$counter++;
  }

  pdf_setlinewidth($pdf, 0.5);
  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_rect($pdf, 19+$pos2, $pos-$c, 130, 12+$c);
  pdf_fill_stroke($pdf);
  pdf_setcolor($pdf, "both", "rgb", 1, 1, 1, 1);

  $cc = 0;
  foreach($Newarea as $Line) {
	pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "84", 10, $fontbold)+$pos2, $pos+12-$cc);
	pdf_continue_text($pdf, $Line);
	$cc = $cc+10;
  }

  $pos = $pos - 12 - $c;

  return $pos;

 }

 function display_entry($row,$pos,$pos2,$font2) {

  global $pdf, $font, $fontbold, $font2bold, $row1, $Country;

  // companyname.

   pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
   pdf_setlinewidth($pdf, 0.5);
   pdf_moveto($pdf, 19+$pos2, $pos+10);
   pdf_lineto($pdf, 149+$pos2, $pos+10);
   pdf_stroke($pdf);

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 7);
   pdf_set_text_pos($pdf, 19+$pos2, $pos+8);

   $NewCompanyname = explode("|", wordwrap($row['companyname'], 30, "|"));
   $count = 0;
   foreach($NewCompanyname as $Line) {
     pdf_continue_text($pdf, $Line);
	 if($count > 0) {
		$pos = $pos-7;
	 }
     $count ++;
   }


  // address.
   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font2, 7);

   $blah =addresslayoutdirectory($row['countryID'],true);

   foreach($blah as $key => $value) {
    $addline = "";

    foreach($value as $key2) {
     if($row[$key2]) {
      $addline .= $row[$key2] ." ";
     }
    }

    if(trim($addline))  {

	 $count = 0;
     $NewCompanyname = explode("|", wordwrap($addline, 30, "|"));
     foreach($NewCompanyname as $Line) {
      pdf_continue_text($pdf, $Line);
 	  if($count > 0) {
		$pos = $pos-7;
	  }
      $count ++;
     }
    }
   }

   pdf_setfont($pdf, $font, 4);
   pdf_continue_text($pdf, $Liner);


   if($row[fiftyclub]) {

	  pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
	  pdf_setfont($pdf, $fontbold, 4);
	  pdf_continue_text($pdf, $tt);

	  pdf_setlinewidth($pdf, 0.5);
	  pdf_setcolor($pdf, "fill", "rgb", .75, .75, .75, .75);
	  pdf_rect($pdf, 45+$pos2, $pos-25, 78, 7);
	  pdf_fill_stroke($pdf);



	  pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
	  pdf_setfont($pdf, $fontbold, 6);
	  pdf_set_text_pos($pdf, 19+$pos2, $pos-18);
	  if($row[fiftyclub] == 2) {
	    $tt = "                     Gold Club Member";
	  } else {
	    $tt = "                 50% Plus Club Member";
	  }

	  pdf_continue_text($pdf, $tt);

	  pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
	  pdf_setfont($pdf, $fontbold, 4);
	  pdf_continue_text($pdf, $ttt);
	  pdf_set_text_pos($pdf, 19+$pos2, $pos-28);
	  $pos = $pos-15;

   }



  // message.
   if(($_SESSION['Country']['Langcode'] != $row1['Langcode']) && $row['engdesc'])  {
     $desc=$row['engdesc'];
   } else {
     $desc=$row['description'];
   }

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $font2, 7);

   $NewDesc = explode("|", wordwrap($desc, 38, "|"));

   foreach($NewDesc as $Line) {
    pdf_continue_text($pdf, $Line);
    $textheight += 7;
   }

   if(is_numeric($row['trade_per']) && $row['trade_per']) {
      pdf_setfont($pdf, $font2bold, 7);
	  $trade = "Last Trading at: ".$row['trade_per']."%";
      pdf_continue_text($pdf, $trade);
	  $textheight += 7;
   }

  // next line. take something from the pos

   //$pos = $pos - ($textheight-$noaddr) - 10;
   $pos = $pos - ($textheight-$noaddr) - 20;


  // ??

   //if ($textheight == 8) {
    //$text = 0;
    //$noaddr = 7;
   //} else  {
    //if(!$textheight) {
     //$text = 0;
     //$noaddr = 0;
    //} else {
     //$text = 8;
     //$noaddr = 7;
    //}
   //}

  // next line. take something from the pos
   if($counter > 3)  {
    $text = $text + 10;
   }
   $pos = $pos - $text;

   $pos = $pos - 2;






   pdf_setfont($pdf, $fontbold, 4);
   pdf_continue_text($pdf, $Liner);


  // contactname

   $count = 0;
   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 7);

   $NewContactname = explode("|", wordwrap($row['contactname'], 30, "|"));
   foreach($NewContactname as $Line) {
     pdf_continue_text($pdf, $Line);
	 if($count > 0) {
		$pos = $pos-7;
	 }
     $count ++;
   }


  // fax/tel/email

   if($_SESSION['Country']['countryID'] != $_SESSION['Directory']['countryid']) {
	 $areano = (substr($row[phonearea],0,1) != 0) ? $row1['phoneprefix']." ".$row[phonearea] : $row1['phoneprefix']." ".substr($row[phonearea],1);
   } else {
	 $areano = $row[phonearea];
   }

   if($_SESSION['Country']['countryID'] != $_SESSION['Directory']['countryid']) {
	 $areafax = (substr($row[faxarea],0,1) != 0) ? $row1['phoneprefix']." ".$row[faxarea] : $row1['phoneprefix']." ".substr($row[faxarea],1);
   } else {
	 $areafax = $row[faxarea];
   }

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 7);
   pdf_continue_text($pdf, "T: ". $areano . " " . $row['phoneno'] . "");

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 7);
   if($row['faxno']) {
   	 pdf_continue_text($pdf, "F: ". $areafax . " " . $row['faxno'] . "");
     $pos = $pos - 8;
   }

   pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
   pdf_setfont($pdf, $fontbold, 7);
   if($row['emailaddress']) {
	   $NewContactname = explode("|", wordwrap($row['emailaddress'], 30, "|",true));
	   foreach($NewContactname as $Line) {
	     pdf_continue_text($pdf, $Line);
   	 	 $pos = $pos - 8;
   	   }
   }

   if($row['webpageurl'])  {
	   $NewContactname = explode("|", wordwrap($row['webpageurl'], 30, "|",true));
	   foreach($NewContactname as $Line) {
	     pdf_continue_text($pdf, $Line);
   	 	 $pos = $pos - 8;
   	   }
   }

  // return

   $pos = ($pos - 25);

   return $pos;

 }

 function check_for_next_page($row,$pos,$type) {

  global $pdf, $page, $font, $fontbold, $pos_baseref, $fontitalic, $displaydate, $row1, $pos2, $current_category, $image, $imagef, $col, $col2, $col3;

  //check some heights.

  if(($_SESSION['Country']['Langcode'] != $row1['Langcode']) && $row['engdesc'])  {
     $desc=$row['engdesc'];
  } else {
     $desc=$row['description'];
  }

  $mess_len = pdf_stringwidth($pdf, $desc, $font, 8);
  $message_height = ((ceil($mess_len/126))*7);

   if(is_numeric($row['trade_per']) && $row['trade_per']) {
    $message_height += 7;
   }

  $total_height = $message_height + 45;

  if($row['fiftyclub']) {
     $total_height = $total_height + 15;
  }

  if($type == "member") {

   $new_pos = $pos - $total_height;

  } elseif($type == "category") {

   $new_pos = $pos - 24 - $total_height - 18 - 24;

  } elseif($type == "area") {

   $new_pos = $pos - $total_height - 18;

  } elseif($type == "state") {

   $new_pos = $pos - 24 - $total_height;

  }

  if($new_pos < ($pos_baseref+$col4)) {

	if($rr && $image && ($pos2 == 0 || $pos2 == 292)) {

		$count = 0;
		foreach($image as $key => $value) {
	      $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$value.".jpg",'');
	   	  pdf_fit_image($pdf, $pdfimage, 20+$pos2, 20+$count, "scale 1");

		  $pos = $pos-270;
		  $count = $count+270;

	    }

	    $image = array();
        $col2 = 1;
        $col3 = $col;

	}

   if($pos2 < 370) {
	if($pos2 == 0) {
	  $pos2 = 142;
	} elseif($pos2 == 142) {
	  $pos2 = 284;
	} elseif($pos2 == 284) {
	  $pos2 = 426;
	} elseif($pos2 == 426) {
	  $pos2 = 0;
	}

	if($pos2) {
	 pdf_setcolor($pdf, "both", "rgb", 0, 0, 0, 0);
	 pdf_setlinewidth($pdf, 0.5);
	 pdf_moveto($pdf, 13+$pos2, 815-$col2);
	 pdf_lineto($pdf, 13+$pos2, 25);
	 pdf_stroke($pdf);
	}

	if($col2) {
	 $pos = 810-$col2;
	 $col2=0;
	} else {
     $pos = 810;
    }


    if($pos2 == 284) {

		$count = 0;
		foreach($image as $key => $value) {
	      $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$value.".jpg",'');
	   	  pdf_fit_image($pdf, $pdfimage, 20+$pos2, 555-$count, "scale 1");



		  $gg = 810-($count+270);
		  if($gg < 25) {



    	pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setlinewidth($pdf, 0.5);
	    pdf_moveto($pdf, 19, 15);
	    pdf_lineto($pdf, 575, 15);
	    pdf_stroke($pdf);

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 8);
	    //pdf_set_text_pos($pdf, 542, 12);
	    pdf_set_text_pos($pdf, get_right_pos(get_word("175")." $page", $pdf, "574", 8, $font), 12);
	    pdf_continue_text($pdf, get_word("175")." $page");

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 5);
	    $text = "Disclaimer: the trade percentages listed is indicative only and represents the most recent known trade component the member has accepted.  All members have the flexibility to change their trade percentages at any time, unless they are Club members trading with other Club members.";
	    $NewText = explode("|", wordwrap($text, 145, "|"));
		$p = 0;

	    foreach($NewText as $Line) {
		  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297", 5, $font), 12-$p);
		  pdf_continue_text($pdf, $Line);
		  $p = $p+5;
	    }

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 8);
	    pdf_set_text_pos($pdf, 21, 12);
	    pdf_continue_text($pdf, $displaydate);

	    $page = $page + 1;

	    pdf_end_page($pdf);
	    pdf_begin_page($pdf, 595, 842);

	    $pos = 810;
	    $pos2 = 0;

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 12);

		$div = explode(".", $page/2);
		if($div[1] > 0) {
			pdf_set_text_pos($pdf, get_right_pos($current_category, $pdf, "574", 12, $font), 840);
		} else {
			pdf_set_text_pos($pdf, 19, 841);
		}
	    pdf_continue_text($pdf, $current_category);


		   $pos2 = 0;
	       $pos = 810;
		   $count = -270;
		  }






		  $pos = $pos-270;
		  $count = $count+270;

	    }

		$col2 = $count;
	    $image = array();

	}

    return $pos;

   } else {

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setlinewidth($pdf, 0.5);
    pdf_moveto($pdf, 19, 15);
    pdf_lineto($pdf, 575, 15);
    pdf_stroke($pdf);

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    //pdf_set_text_pos($pdf, 542, 12);
    pdf_set_text_pos($pdf, get_right_pos(get_word("175")." $page", $pdf, "574", 8, $font), 12);
    pdf_continue_text($pdf, get_word("175")." $page");

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 5);
    $text = "Disclaimer: the trade percentages listed is indicative only and represents the most recent known trade component the member has accepted.  All members have the flexibility to change their trade percentages at any time, unless they are Club members trading with other Club members.";
    $NewText = explode("|", wordwrap($text, 145, "|"));

    foreach($NewText as $Line) {
	  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297", 5, $font), 12-$p);
	  pdf_continue_text($pdf, $Line);
	  $p = $p+5;
    }

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 8);
    pdf_set_text_pos($pdf, 21, 12);
    pdf_continue_text($pdf, $displaydate);

    $page = $page + 1;

    pdf_end_page($pdf);
    pdf_begin_page($pdf, 595, 842);
    $pos = 810;
    $pos2 = 0;

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 12);

	$div = explode(".", $page/2);
	if($div[1] > 0) {
		pdf_set_text_pos($pdf, get_right_pos($current_category, $pdf, "574", 12, $font), 840);
	} else {
		pdf_set_text_pos($pdf, 19, 841);
	}
    pdf_continue_text($pdf, $current_category);


	if($imagef) {
	  foreach($imagef as $key => $value) {
	    $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$value.".jpg",'');
	   	pdf_fit_image($pdf, $pdfimage, 30, 30, "scale 2");
	   	//pdf_fit_image($pdf, $pdfimage, 23, 20, "boxsize {260 260} fitmethod meet");

    	pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setlinewidth($pdf, 0.5);
	    pdf_moveto($pdf, 19, 15);
	    pdf_lineto($pdf, 575, 15);
	    pdf_stroke($pdf);

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 8);
	    //pdf_set_text_pos($pdf, 542, 12);
	    pdf_set_text_pos($pdf, get_right_pos(get_word("175")." $page", $pdf, "574", 8, $font), 12);
	    pdf_continue_text($pdf, get_word("175")." $page");

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 5);
	    $text = "Disclaimer: the trade percentages listed is indicative only and represents the most recent known trade component the member has accepted.  All members have the flexibility to change their trade percentages at any time, unless they are Club members trading with other Club members.";
	    $NewText = explode("|", wordwrap($text, 145, "|"));
		$p = 0;

	    foreach($NewText as $Line) {
		  pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297", 5, $font), 12-$p);
		  pdf_continue_text($pdf, $Line);
		  $p = $p+5;
	    }

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 8);
	    pdf_set_text_pos($pdf, 21, 12);
	    pdf_continue_text($pdf, $displaydate);

	    $page = $page + 1;

	    pdf_end_page($pdf);
	    pdf_begin_page($pdf, 595, 842);

	    $pos = 810;
	    $pos2 = 0;

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 12);

		$div = explode(".", $page/2);
		if($div[1] > 0) {
			pdf_set_text_pos($pdf, get_right_pos($current_category, $pdf, "574", 12, $font), 840);
		} else {
			pdf_set_text_pos($pdf, 19, 841);
		}
	    pdf_continue_text($pdf, $current_category);

	  }
	  $imagef = array();
	}


	if($image) {

		$count = 0;
		foreach($image as $key => $value) {
	      $pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/logoimages/".$value.".jpg",'');
	   	  pdf_fit_image($pdf, $pdfimage, 20+$pos2, 555-$count, "scale 4");

	   	 // pdf_fit_image($pdf, $pdfimage, 20+$pos2, 555-$count, "boxsize {260 260} fitmethod meet");

		  $gg = 810-($count+270);
		  if($gg < 25) {
		   $pos2 = 284;
	       $pos = 810;
		   $count = -270;
		  }

		  $pos = $pos-270;
		  $count = $count+270;

	    }

		$col2 = $count;
	    $image = array();

    }

    return $pos;

   }

  } else {

   return $pos;

  }
 }

 function cover() {

  global $pdf, $page, $font, $fontbold, $pos_baseref, $fontitalic, $displaydate, $row1, $pos2, $current_category, $image, $col, $col2, $col3;

	pdf_rect($pdf, 25, 25, 545, 800);
	pdf_closepath_stroke($pdf);

	$pdfimage = pdf_load_image($pdf, "jpeg", "/home/etxint/public_html/home/images/etx2-bw.jpg",'');
	pdf_fit_image($pdf, $pdfimage, 225, 645, "scale 0.5");

    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
    pdf_setfont($pdf, $font, 30);
    if($_REQUEST['list']) {
		$Line = "New Members";
	} else {
		$Line = "Membership Directory";
	}

    pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297.5", 30, $font), 570);
    pdf_continue_text($pdf, $Line);

    if($_SESSION['Directory']['area']) {

		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
		pdf_setfont($pdf, $font, 20);
		$p = 0;

    	$AreaComma = comma_seperate($_SESSION['Directory']['area']);
	    $query = dbRead("SELECT * FROM tbl_area_physical WHERE FieldID in (".$AreaComma.") order by AreaName");
	    while($row = mysql_fetch_assoc($query)) {
		 $sname = $row['AreaName'];
		 pdf_set_text_pos($pdf, get_left_pos($sname, $pdf, "297.5", 20, $font), 500-$p);
		 pdf_continue_text($pdf, $sname);
	     $p=$p+21;
	    }

		if($_SESSION['Directory']['category']) {
			 pdf_set_text_pos($pdf, get_left_pos("Category Extract Only", $pdf, "297.5", 20, $font), 500-$p);
			 pdf_continue_text($pdf, "Category Extract Only");
		     $p=$p+21;
		}
		agents($query);

	} elseif($_SESSION['Directory']['disarea']) {

	    //$query = dbRead("SELECT area.state, area.place, area.r_address, area.p_address, area.state, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM area, tbl_physical_area, tbl_regional_area WHERE area.PhysicalID = tbl_physical_area.FieldID and RegionalID = ".$_SESSION['Directory']['disarea']." AND area.display = 'Y' and ORDER BY country.name, area.state, area.place");
	    $query = dbRead("SELECT * FROM tbl_area_regional WHERE  FieldID = ".$_SESSION['Directory']['disarea']."");
	    $row = mysql_fetch_assoc($query);

	    pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	    pdf_setfont($pdf, $font, 20);
		$Line = $row['RegionalName'];
	    pdf_set_text_pos($pdf, get_left_pos($Line, $pdf, "297.5", 20, $font), 500);
	    pdf_continue_text($pdf, $Line);

	    $query = dbRead("SELECT area.state, area.place, area.r_address, area.p_address, area.state, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM area, tbl_area_physical WHERE area.PhysicalID = tbl_area_physical.FieldID and RegionalID = ".$_SESSION['Directory']['disarea']." AND area.display = 'Y' ORDER BY area.state, area.place");
		if($_SESSION['Directory']['category']) {
			 pdf_set_text_pos($pdf, get_left_pos("Category Extract Only", $pdf, "297.5", 20, $font), 500-$p);
			 pdf_continue_text($pdf, "Category Extract Only");
		     $p=$p+21;
		}
		agents($query);

	} elseif($_SESSION['Directory']['state']) {

		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
		pdf_setfont($pdf, $font, 20);
		$p = 0;

	    $StateComma = comma_seperate($_SESSION['Directory']['state']);
	    $query = dbRead("SELECT * FROM tbl_area_states WHERE FieldID in (".$StateComma.") order by StateName");
	    while($row = mysql_fetch_assoc($query)) {
		 if($row['StateName'] == "Qld") {
		  $sname = "Queensland";
		 } elseif($row['StateName'] == "NSW") {
		  $sname = "New South Wales";
		 } elseif($row['StateName'] == "Vic") {
		  $sname = "Victoria";
		 } elseif($row['StateName'] == "WA") {
		  $sname = "Western Australia";
		 } elseif($row['StateName'] == "NT") {
		  $sname = "Northern Territory";
		 } elseif($row['StateName'] == "ACT") {
		  $sname = "Australia Capital Territory";
		 } elseif($row['StateName'] == "SA") {
		  $sname = "South Australia";
		 } elseif($row['StateName'] == "Tas") {
		  $sname = "Tasmania";
		 } else {
		  $sname = $row['StateName'];
		 }
		 pdf_set_text_pos($pdf, get_left_pos($sname, $pdf, "297.5", 20, $font), 500-$p);
		 pdf_continue_text($pdf, $sname);
	     $p=$p+21;
	    }

	    $query = dbRead("SELECT area.state as area, area.state, area.place, area.r_address, area.p_address, area.state, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM area, tbl_area_physical, tbl_area_regional WHERE area.PhysicalID = tbl_area_physical.FieldID and tbl_area_physical.RegionalID = tbl_area_regional.FieldID and StateID in (".$StateComma.") AND area.display = 'Y' ORDER BY area.state, area.place");
		if($_SESSION['Directory']['category']) {
			 pdf_set_text_pos($pdf, get_left_pos("Category Extract Only", $pdf, "297.5", 20, $font), 500-$p);
			 pdf_continue_text($pdf, "Category Extract Only");
		     $p=$p+21;
		}
		agents($query, $p);

	}

    pdf_end_page($pdf);
    pdf_begin_page($pdf, 595, 842);
 }

 function agents($query, $pp = false) {

  global $pdf, $page, $font, $fontbold, $pos_baseref, $fontitalic, $displaydate, $row1, $pos2, $current_category, $image, $col, $col2, $col3;


    pdf_end_page($pdf);
    pdf_begin_page($pdf, 595, 842);

	pdf_rect($pdf, 25, 25, 545, 800);
	pdf_closepath_stroke($pdf);

	$p2 = 0;
	$p3 = 0;
	$colcount = 0;
	$agentt = " ";

    while($row = mysql_fetch_assoc($query)) {

     $colcount++;

	 if($agentt != $row['area']) {
	 	if($colcount != 1) {
		 $p2=$p2+75;
		 }
		 $p2=$p2+25;
	   	 pdf_setfont($pdf, $fontbold, 12);
		 pdf_set_text_pos($pdf, get_left_pos("Agent Contacts in ".$row['area'], $pdf, "297.5", 12, $fontbold), 840-$p2);
		 pdf_continue_text($pdf, "Agent Contacts in ".$row['area']);

	     $agentt = $row['area'];
	     $colcount = 1;
	     $p3 = 0;
		 $p2=$p2;
	 }

  	 pdf_setfont($pdf, $fontbold, 10);
 	 pdf_set_text_pos($pdf, 35+$p3, 815-$p2);
 	 pdf_continue_text($pdf, $row['place']);

  	 pdf_setfont($pdf, $font, 10);
 	 pdf_set_text_pos($pdf, 35+$p3, 805-$p2);
 	 pdf_continue_text($pdf, $row['tradeq']);

	 pdf_setfont($pdf, $font, 8);
 	 pdf_set_text_pos($pdf, 35+$p3, 795-$p2);
 	 pdf_continue_text($pdf, "Tel: ".$row['phone']);
 	 pdf_continue_text($pdf, "Fax: ".$row['fax']);
 	 pdf_continue_text($pdf, "Mobile: ".$row['mobile']);
 	 pdf_continue_text($pdf, "Email: ".$row['email']);
  	 pdf_continue_text($pdf, $row['r_address']);

     if($crow['r_address'] != $crow['p_address']) {
 	  pdf_set_text_pos($pdf, 35+$p3, 755-$p2);
 	  pdf_continue_text($pdf, $row['p_address']);
 	 }

	 if($colcount == 1) {
	  $p3 = 180;
	 } elseif($colcount == 2) {
	  $p3 = 360;
	 } else {
	  $p3 = 0;
	  $p2=$p2+75;
	  $colcount = 0;
	 }

    }
 }

 function agentsold($query, $pp = false) {

  global $pdf, $page, $font, $fontbold, $pos_baseref, $fontitalic, $displaydate, $row1, $pos2, $current_category, $image, $col, $col2, $col3;

	$p2 = $pp;
	$p3 = 0;
	$colcount = 0;
	$agentt = " ";
	$rr = 1;
	//$searchcount = mysql_num_rows($query);

    while($row = mysql_fetch_assoc($query)) {

     $colcount++;
	 if(520-$p2-25 < 100) {
	  //$p3 = 180+$p3;
	  //$p2 = 75+$pp;
      pdf_end_page($pdf);
      pdf_begin_page($pdf, 595, 842);

	  pdf_rect($pdf, 25, 25, 545, 800);
	  pdf_closepath_stroke($pdf);

	  $p2 = -520;
	  $colcount = 1;
	  $p3 = 0;
	  $rr = 1;
	 }
	 if($agentt != $row['area']) {
	  if($rr != 1) {
	     $p2=$p2+75;
		 //
	  }
$rr = 0;
	   	 pdf_setfont($pdf, $fontbold, 12);
		 pdf_set_text_pos($pdf, get_left_pos("Agent Contacts in ".$row['area'], $pdf, "297.5", 12, $fontbold), 520-$p2);
		 pdf_continue_text($pdf, "Agent Contacts in ".$row['area']);
		 $p2=$p2+25;
	     $agentt = $row['area'];
	     $colcount = 1;
	     $p3 = 0;
	 }

	 //$p2=$p2+75;

	 //if($p2 > 475) {


  	 pdf_setfont($pdf, $fontbold, 10);
 	 pdf_set_text_pos($pdf, 35+$p3, 520-$p2);
 	 pdf_continue_text($pdf, $row['place']);

  	 pdf_setfont($pdf, $font, 10);
 	 pdf_set_text_pos($pdf, 35+$p3, 510-$p2);
 	 pdf_continue_text($pdf, $row['tradeq']);

	 pdf_setfont($pdf, $font, 8);
 	 pdf_set_text_pos($pdf, 35+$p3, 500-$p2);
 	 pdf_continue_text($pdf, "Tel: ".$row['phone']);
 	 pdf_continue_text($pdf, "Fax: ".$row['fax']);
 	 pdf_continue_text($pdf, "Mobile: ".$row['mobile']);
 	 pdf_continue_text($pdf, "Email: ".$row['email']);
  	 pdf_continue_text($pdf, $row['r_address']);

     if($crow['r_address'] != $crow['p_address']) {
 	  pdf_set_text_pos($pdf, 35+$p3, 460-$p2);
 	  pdf_continue_text($pdf, $row['p_address']);
 	 }

	 if($colcount == 1) {
	  $p3 = 180;
	 } elseif($colcount == 2) {
	  $p3 = 360;
	 } else {
	  $p3 = 0;
	  $p2=$p2+75;
	  $colcount = 0;
	 }

    }
 }
?>
