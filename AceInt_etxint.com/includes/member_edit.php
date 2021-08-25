<?

 /**
  * E Banc Trade Member Edit
  *
  * member_edit.php
  * Version 0.02
  */

 include("includes/modules/class.paging.php");
 include("includes/modules/actions.php");
 include("includes/modules/db.php");
 include("includes/class.html.mime.mail.inc");
 include("includes/modules/class.phpmailer.php");
?>
<html>
<head>
<title>A.C.E. - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= which_charset($_REQUEST['page']) ?>">
<LINK REL="STYLESHEET" type="text/css" href="includes/styles.css">
<script language="javascript" type="text/javascript" src="includes/default.js?cache=no"></script>
<script LANGUAGE="JavaScript">
<!--

function ConfirmDel(catid,category) {
	bDelete = confirm("Are you sure you want to delete Category " + category + "?");
	if (bDelete) {
		var url = "body.php?page=member_edit&Action=<?= $_REQUEST['Action'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&Client=<?= $_REQUEST['Client']?>&tab=tab3&Delete=" + catid;
		window.location.href = url;
	}
}

function open_win2(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=yes,status=yes,resizable=no,menubar=no,width=450,height=450');
}
//-->
</script>
<script LANGUAGE="JavaScript">
<!--

function ConfirmStationery() {
	bDelete = confirm("Would this member now like to receive their Tax Invoice via email and not pay the monthly stationery fee?");
	if (bDelete) {
		var url = "../general.php?pageno=<?= $_REQUEST['pageno'] ?>&Client=<?= $_REQUEST['Client']?>&tab=tab1&updatestationery=1";
		window.location.href = url;
	} else {
	    return false;
	}
}


function advert(URL) {
var exitwin = "toolbar=0,location=0,directories=0,menubar=0,status=2,scrollbars=1,target=_blank,width=810,height=400";
selectedURL = URL;
remotecontrol = window.open(selectedURL, "exit_console", exitwin);
remotecontrol.focus();
}

 //function new_window6(URL) {
  //var sendmsg ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=300";
  //selectedURL = URL;
  //remotecontrol=window.open(selectedURL, "deal", sendmsg);
  //remotecontrol.focus();
 //}

//-->
</script>

</head>

<form ENCTYPE="multipart/form-data" method="POST" action="body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&Action=<?= $_REQUEST['Action'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=<?= $_REQUEST['tab'] ?>" name="member_edit">

<?

// Some Setup.
 if($row[bdriven] == "Y") {
  $cc = "FF00FF";
 } elseif($row[respenddown] == 1) {
  $cc = "00FFFF";
 } elseif($row[status] == 4) {
  if($row[sponcat] == 1) {
   $cc = "FFFF00";
  } else {
   $cc = "FF0000";
  }
 } else {
  $cc = "FFFFFF";
 }


$Debug = false;

if($Debug) {

 echo "<pre>";
 var_dump($_POST);
 echo "</pre>";

}

 $tabarray = array(get_page_data("1"),get_page_data("2"),get_page_data("3"),get_page_data("5"),get_page_data("6"),get_page_data("7"),get_page_data("8"),get_page_data("42"),get_page_data("43"),"Adverts",get_page_data("46"),get_page_data("44"));
 //if(checkmodule("EditMemberLevel2")) { $tabarray[] = get_page_data("44"); }
 if(checkmodule("LogReport,SuperUser")) { $tabarray[] = get_page_data("47"); }

// Get Member Details Out.

$memquery = dbRead("select * from members where memid = '$_REQUEST[Client]'");
$memrow = mysql_fetch_assoc($memquery);

$typeSQL = dbRead("select tbl_email_type.FieldID, tbl_members_email.email from tbl_email_type, tbl_members_email where (tbl_members_email.type = tbl_email_type.FieldID) and (tbl_members_email.acc_no = ".$_REQUEST[Client].") ");
while($typeRow = mysql_fetch_assoc($typeSQL)) {

	$memrow["emailAddress_$typeRow[FieldID]"] = $typeRow['email'];

}

//if(!check_area_access($memrow) && $_REQUEST['tab']) {
// header("Location: body.php?page=member_edit&Client=".$_REQUEST['Client']."&Action=".$_REQUEST['Action']."&pageno=".$_REQUEST['pageno']."&tab=tab5");
//}

// Do Tabs if we need to.

 displaytabs($tabarray);


if($_REQUEST[tab] == "tab1") {

 if(check_area_access($memrow)) {

  if($_REQUEST['Update']) {

   update_main_info($memrow);

  } else {

   display_quick_notes($memrow);
   display_main_info($memrow);

  }

 } else {

  print get_page_data("26");

 }

} elseif($_REQUEST[tab] == "tab2") {

 if(check_area_access($memrow)) {

  if($_REQUEST['Update']) {

   update_address_info($memrow);

  } else {

   display_quick_notes($memrow);
   display_address_info($memrow);

  }

 } else {

  print get_page_data("26");

 }

} elseif($_REQUEST[tab] == "tab3") {

  if(checkmodule("EditMemberLevel1") || checkmodule("EditMemberLevel1")) {

	 if(check_area_access($memrow)) {

	  if($_REQUEST['Update']) {

	   add_category_info($memrow);

	  } elseif($_REQUEST['CatID']) {

	   edit_category_info($memrow,'','',$_REQUEST['CatID']);

	  } elseif($_REQUEST['UpdateCat']) {

	   update_category_info($memrow,'','',$_REQUEST['CatID']);

	  } elseif($_REQUEST['Delete']) {

	   delete_category_info($_REQUEST['Delete'],$memrow);

	  } else {

   	   display_quick_notes($memrow);
	   display_category_info($memrow);

	  }

	 } else {

	  print get_page_data("26");

	 }

  } else {

	   display_category_info($memrow);

  }

} elseif($_REQUEST[tab] == "tab4") {

 if(check_area_access($memrow)) {

  if($_REQUEST['Update']) {

   update_misc_info($memrow);

  } else {

   display_quick_notes($memrow);
   display_misc_info($memrow);

  }

 } else {

  print get_page_data("26");

 }

} elseif($_REQUEST[tab] == "tab6") {

 if(check_area_access($memrow)) {

  if($_REQUEST['Update']) {

   update_payment_info($memrow);

  } else {

   display_quick_notes($memrow);
   display_payment_info($memrow);

  }

 } else {

  print get_page_data("26");

 }

} elseif($_REQUEST[tab] == "tab7") {

 if(checkmodule("ViewStatement")) {

	 if(check_area_access($memrow)) {

	  if($_REQUEST['DisplayStatement']) {

	   if($memrow['status'] == 3) {

	    if(checkmodule("Staff") || checkmodule("ViewStatement")) {

   		 display_quick_notes($memrow);
	     display_statements($memrow,$_REQUEST['numbermonths']);

	    } else {

	     print "[1]: " . get_page_data("27");

	    }

	   } else {

   		display_quick_notes($memrow);
	    display_statements($memrow,$_REQUEST['numbermonths']);

	   }

	  } else {

	   if($memrow['status'] == 3) {

	    if(checkmodule("Staff")) {

   		 display_quick_notes($memrow);
	     select_statements($memrow);

	    } else {

	     print "[2]: " . get_page_data("27");

	    }

	   } else {

   		display_quick_notes($memrow);
	    select_statements($memrow);

	   }

	  }

	 } else {

	  print get_page_data("27");

	 }

 } else {

   print "[3]: " . get_page_data("27");

 }

} elseif($_REQUEST[tab] == "tab8") {

 if(check_area_access($memrow)) {

  display_quick_notes($memrow);
  display_actions($memrow);

 } else {

  print get_page_data("27");

 }

} elseif($_REQUEST[tab] == "tab9") {

 //if(check_area_access($memrow)) {
 //if($memrow[CID] == $_SESSION['User']['CID']) {

  if($_REQUEST['AddNote']) {

   add_note($memrow);
   display_quick_notes($memrow);
   display_notes($memrow);

  } elseif($_REQUEST['EditNote']) {

   edit_note();

  } elseif($_REQUEST['SaveNote']) {

   save_note();
   display_quick_notes($memrow);
   display_notes($memrow);

  } else {

   display_quick_notes($memrow);
   display_notes($memrow);

  }

 //} else {

  //print get_page_data("27");

 //}

} elseif($_REQUEST[tab] == "tab13") {

 if(check_area_access($memrow)) {

  display_log($memrow);

 } else {

  print get_page_data("26");

 }

} elseif($_REQUEST[tab] == "tab5") {

 display_quick_notes($memrow);
 display_printview($memrow);

} elseif($_REQUEST[tab] == "tab12") {

 if(check_area_access($memrow)) {

    display_quick_notes($memrow);
 	communicate($memrow);

 } else {

     print get_page_data("26");

 }


} elseif($_REQUEST[tab] == "tab10") {

 display_adverts($memrow);

} elseif($_REQUEST[tab] == "tab11") {

 if(check_area_access($memrow)) {
  if(checkmodule("ViewStatement")) {
   if($memrow['status'] == 3 || $memrow['status'] == 2) {

    if((checkmodule("Staff") && checkmodule("ViewStatement")) || (checkmodule("Contractor") && checkmodule("ViewStatement")) ) {

       history($memrow);

    } else {

     print get_page_data("27");

    }

   } else {

     history($memrow);

   }
  } else {
    print get_page_data("27");
  }
 } else {

  print get_page_data("27");

 }

}

?>

<table border="0" cellpadding="0" cellspacing="0" width="620">
 <tr>
   <td width="100%"><br><img border="0" src="images/layout_line.gif" width="100%" height="13"></td>
 </tr>
 <tr>
   <td width="100%" align="center"><?= get_word("66") ?>: <?= $memrow[companyname] ?> [<?= $memrow[memid] ?>]</td>
 </tr>
</table>

</form>

</html>

<?

function display_actions($row, $Errormsg = false, $ErrorArray = false) {

?>
<iframe src="body.php?page=member_edit_actions&ChangeMargin=1&Client=<?= $_REQUEST['Client'] ?>" frameborder=no width="620" height="820" marginwidth=0 marginheight=0 scrolling=no></iframe>
<?

}

function check_errors($Module, $row = false) {

 if(!checkmodule("EditMemberLevel1") && !checkmodule("EditMemberLevel2"))  {
  $Errormsg['Messages'] .= "<font color=\"#FF0000\"><b>".get_page_data("45")."</b></font>";
  return $Errormsg;
 }

 if($row['status'] == 1 && !checkmodule("SuperUser")) {
  $Errormsg['Messages'] .= "<font color=\"#FF0000\"><b>".get_page_data("28")."</b></font>";
  return $Errormsg;
 }

 if($row['status'] == 6 && !checkmodule("EditMemberLevel2") && $row['uncon'] != 'Y') {
   $Errormsg['Messages'] .= "<font color=\"#FF0000\"><b>".get_page_data("29")."</b></font>";
   return $Errormsg;
 }

 if($Module == "Main") {

  if(strpos(strtolower(" ".$_REQUEST['webpageurl']), "http://")) { $Errormsg['Messages'] .= get_word("145")."<br>"; $Errormsg['Highlight']['webpageurl'] = true; }
  //if($_REQUEST['emailAddress'] && !validate_email($_REQUEST['emailAddress'])) { $Errormsg['Messages'] .= get_word("144")."<br>"; $Errormsg['Highlight']['emailAddress'] = true; };
  //if($_REQUEST['email_accounts'] && !validate_email($_REQUEST['email_accounts'])) { $Errormsg['Messages'] .= get_word("144")."<br>"; $Errormsg['Highlight']['email_accounts'] = true; };
  if(!abs($row['monthlyfeecash']) && !$_REQUEST['emailAddress_2']) { $Errormsg['Messages'] .= get_word("146")."<br>"; $Errormsg['Highlight']['emailAddress_2'] = true; }
  //if(!abs($row['monthlyfeecash']) && !$_REQUEST['emailAddress']) { $Errormsg['Messages'] .= get_word("146")."<br>"; $Errormsg['Highlight']['emailAddress'] = true; }

   $typeSQL = dbRead("select tbl_email_type.* from tbl_email_type");
   while($typeRow = mysql_fetch_assoc($typeSQL)) {

	 if($_REQUEST["emailAddress_$typeRow[FieldID]"]) {

	   if(!validate_email($_REQUEST["emailAddress_$typeRow[FieldID]"])) {

	   	$Errormsg['Messages'] .= get_word("144")."<br>"; $Errormsg['Highlight']["emailAddress_$typeRow[FieldID]"] = true;

	   }

	 }

   }

  if(checkmodule("EditMemberLevel2")) {

   if(!$_REQUEST['regname']) { $Errormsg['Messages'] .= get_word("142")."<br>"; $Errormsg['Highlight']['regname'] = true;}
   if(!$_REQUEST['accholder']) { $Errormsg['Messages'] .= get_word("143")."<br>"; $Errormsg['Highlight']['accholder'] = true;}
   if(!$_REQUEST['accholder_first']) { $Errormsg['Messages'] .= get_word("143")."<br>"; $Errormsg['Highlight']['accholder_first'] = true;}
   if(!$_REQUEST['accholder_surname']) { $Errormsg['Messages'] .= get_word("143")."<br>"; $Errormsg['Highlight']['accholder_surname'] = true;}

   if($_REQUEST['status'] == 4) {
    if($_REQUEST['sponcat'] == 0) {
     $Errormsg['Messages'] .= get_word("203")."<br>";
     $Errormsg['Highlight']['sponcat'] = true;
    }
   }
  }

  return $Errormsg;

 }

 if($Module == "Address") {

  if(!$_REQUEST['city']) { $Errormsg['Messages'] .= get_word("141")."<br>"; $Errormsg['Highlight']['city'] = true; }
  if(!$_REQUEST['postalcity']) { $Errormsg['Messages'] .= get_word("147")."<br>"; $Errormsg['Highlight']['postalcity'] = true; }

  if($_SESSION['CountryPref_Members']['state_required'] == 1)  {
    if(!$_REQUEST['state']) { $Errormsg['Messages'] .= get_word("195")."<br>"; $Errormsg['Highlight']['state'] = true; }
    if(!$_REQUEST['postalstate']) { $Errormsg['Messages'] .= get_word("196")."<br>"; $Errormsg['Highlight']['postalstate'] = true; }
  }

  if($_SESSION['CountryPref_Members']['post_required'] == 1)  {
    if(!$_REQUEST['postcode']) { $Errormsg['Messages'] .= get_word("197")."<br>"; $Errormsg['Highlight']['postcode'] = true; }
    if(!$_REQUEST['postalpostcode']) { $Errormsg['Messages'] .= get_word("198")."<br>"; $Errormsg['Highlight']['postalpostcode'] = true; }
  }

  return $Errormsg;

 }

 if($Module == "Categories") {

  $CatSQL = dbRead("select count(*) as CatCount from mem_categories where memid = " . $row['memid']);
  $CatRow = @mysql_fetch_assoc($CatSQL);

  if($CatRow['CatCount'] == 1) {
   if(!checkmodule("EditMemberLevel2") && $_REQUEST['add_catid'] == 0) {
    if($_REQUEST['UpdateCat']) {
     $Errormsg['Messages'] .= "Not Allowed to Unlist Member<br>"; $Errormsg['Highlight']['description'] = true; $Errormsg['Highlight']['add_catid'] = true;
    }
   }
  }

  if(!$_REQUEST['description']) { $Errormsg['Messages'] .= get_word("179")."<br>"; $Errormsg['Highlight']['description'] = true; }
  if($_REQUEST['description'] && $_SESSION['Country']['english'] == 'N' && !$_REQUEST['engdescription']) { $Errormsg['Messages'] .= get_word("179")."<br>"; $Errormsg['Highlight']['engdescription'] = true; }

  return $Errormsg;

 }

 if($Module == "Misc") {

  if(checkmodule("EditMemberLevel2")) {
   if(!abs($_REQUEST['monthlyfeecash']) && !$row['emailAddress_2']) { $Errormsg['Messages'] .= get_word("148")."<br>"; $Errormsg['Highlight']['transfeecash'] = true; }
  }

  if($_REQUEST['wagesacc']) {
   $WageSQL = dbRead("select count(*) as Test from members where memid = '".$_REQUEST['wagesacc']."'");
   $WageRow = mysql_fetch_assoc($WageSQL);
   if($WageRow['Test'] < 1) {
    $Errormsg['Messages'] .= get_word("149")."<br>";
    $Errormsg['Highlight']['wagesacc'] = true;
   }
  }

  if($_REQUEST['datejoined_Day'] || $_REQUEST['datejoined_Month'] || $_REQUEST['datejoined_Year']) {
   $DateJoined = mktime(0,0,0,$_REQUEST['datejoined_Month'],$_REQUEST['datejoined_Day'],$_REQUEST['datejoined_Year']);
   if($DateJoined > (mktime()+$_SESSION['Country']['timezone'])) {
    $Errormsg['Messages'] .= get_word("150")."<br>";
    $Errormsg['Highlight']['datejoined'] = true;
   }
  }

  return $Errormsg;

 }

 if($Module == "Payment Details") {

  if($_REQUEST['paymenttype'] == 1 || $_REQUEST['paymenttype'] == 4 || $_REQUEST['paymenttype'] == 6 || $_REQUEST['paymenttype'] == 7) {

   $ExpireArray = explode("/", $_REQUEST['expires']);

   if($ExpireArray['0'] < date("m") and $ExpireArray['1'] <= date("y")) {
    $Errormsg['Messages'] .= get_word("151")."<br>";
    $Errormsg['Highlight']['expires'] = true;
   }

   if(!$_REQUEST['accountname']) {
    $Errormsg['Messages'] .= get_word("152")."<br>";
    $Errormsg['Highlight']['accountname'] = true;
   }

   if(!$_REQUEST['accountno']) {
    $Errormsg['Messages'] .= get_word("153")."<br>";
    $Errormsg['Highlight']['accountname'] = true;
   }

  }

  if($_REQUEST['paymenttype'] == 20) {

   if(!$_REQUEST['accountname']) {
    $Errormsg['Messages'] .= get_word("154")."<br>";
    $Errormsg['Highlight']['accountname'] = true;
   }

   if(!$_REQUEST['accountno']) {
    $Errormsg['Messages'] .= get_word("153")."<br>";
    $Errormsg['Highlight']['accountno'] = true;
   }

   if($_SESSION['Country']['countryID'] == 1) {

	   if(!strstr($_REQUEST['accountno'], ",")) {
	    $Errormsg['Messages'] .= get_word("155")."<br>";
	    $Errormsg['Highlight']['accountno'] = true;
	   }

	   $BSBArray = @explode(",", $_REQUEST['accountno']);

	   if(strlen($BSBArray[0]) != 6) {
	    $Errormsg['Messages'] .= get_word("156")."<br>";
	    $Errormsg['Highlight']['accountno'] = true;
	   }

   } elseif($_SESSION['Country']['countryID'] == 15) {

	   if(strlen($_REQUEST['accountno']) != 20) {
	    $Errormsg['Messages'] .= get_word("156")."<br>";
	    $Errormsg['Highlight']['accountno'] = true;
	   }

   }

  }
 }

  //if($_REQUEST['reward_no'] || $_REQUEST['reward_bsb'] || $_REQUEST['reward_name']) {

   //if(!$_REQUEST['reward_accname']) {
    //$Errormsg['Messages'] .= get_word("157")."<br>";
    //$Errormsg['Highlight']['reward_name'] = true;
   //}

   //if(!$_REQUEST['reward_accno']) {
    //$Errormsg['Messages'] .= get_word("153")."<br>";
    //$Errormsg['Highlight']['reward_no'] = true;
   //}

   //if(strlen($_REQUEST['reward_bsb']) != 6) {
    //$Errormsg['Messages'] .= get_word("156")."<br>";
    //$Errormsg['Highlight']['reward_no'] = true;
   //}

  //}

 return $Errormsg;

}

function update_main_info($row) {

 $Errormsg = check_errors("Main",$row);

 if($Errormsg) {
  display_main_info($row, $Errormsg['Messages'], $Errormsg['Highlight']);
 } else {	 
  log_changes($row,"4");
  $DOBContact = $_REQUEST['DOBcontact_Year'] . "-" . $_REQUEST['DOBcontact_Month'] . "-" . $_REQUEST['DOBcontact_Day'];
  $DOBHolder = $_REQUEST['DOBholder_Year'] . "-" . $_REQUEST['DOBholder_Month'] . "-" . $_REQUEST['DOBholder_Day'];

   $accountsSQL = dbRead("select tbl_members_email.* from tbl_members_email where acc_no = '".$_REQUEST['Client']."' and type = 2");
   $accountsRow = @mysql_fetch_assoc($accountsSQL);

  $SQL = new dbCreateSQL();

  $SQL->add_table("members");  
  $SQL->add_item("contactname", encode_text2($_REQUEST['contactname']));
  $SQL->add_item("DOBcontact", $DOBContact);
  $SQL->add_item("phonearea", encode_text2($_REQUEST['phonearea']));
  $SQL->add_item("phoneno", encode_text2($_REQUEST['phoneno']));
  $SQL->add_item("faxarea", encode_text2($_REQUEST['faxarea']));
  $SQL->add_item("faxno", encode_text2($_REQUEST['faxno']));
  $SQL->add_item("mobile", encode_text2($_REQUEST['mobile']));
  $SQL->add_item("email_accounts", encode_text2($_REQUEST['emailAddress']));
  $SQL->add_item("t_unlist", $_REQUEST['t_unlist']);
  $SQL->add_item("opt", $_REQUEST['opt']);
  $SQL->add_item("reopt", $_REQUEST['reopt']);
  $SQL->add_item("webpageurl", encode_text2($_REQUEST['webpageurl']));
  $SQL->add_item("pin", encode_text2($_REQUEST['pin']));

   $typeSQL = dbRead("select tbl_email_type.* from tbl_email_type");
   while($typeRow = mysql_fetch_assoc($typeSQL)) {

	dbWrite("update tbl_members_email set email = '" . $_REQUEST["emailAddress_$typeRow[FieldID]"] . "' where acc_no = " . $_REQUEST['Client'] . " and type = " . $typeRow['FieldID']);

   }

  if(checkmodule("EditMemberLevel2")) {

   $SQL->add_item("regname", encode_text2($_REQUEST['regname']));
   $SQL->add_item("accholder", encode_text2($_REQUEST['accholder']));
   $SQL->add_item("accholder_first", encode_text2($_REQUEST['accholder_first']));
   $SQL->add_item("accholder_surname", encode_text2($_REQUEST['accholder_surname']));
   $SQL->add_item("DOBholder", $DOBHolder);
   $SQL->add_item("signatories", encode_text2($_REQUEST['signatories']));
   $SQL->add_item("bdriven", $_REQUEST['bdriven']);
   $SQL->add_item("sponcat", $_REQUEST['sponcat']);
   $SQL->add_item("exopt", $_REQUEST['exopt']);
   $SQL->add_item("direct", $_REQUEST['direct']);

   if(!$_REQUEST['companyname']) {
    $SQL->add_item("companyname", encode_text2($_REQUEST['regname']));
    if($row['displayname'] == "companyname" && $row['companyname'] != $_REQUEST['regname']) {
     UpdatePastCompanyname($row['memid'],addslashes($_REQUEST['regname']),addslashes($row['regname']));
    }
   } else {
    $SQL->add_item("companyname", encode_text2($_REQUEST['companyname']));
    if($row['displayname'] == "companyname" && $row['companyname'] != $_REQUEST['companyname']) {
     UpdatePastCompanyname($row['memid'],addslashes($_REQUEST['companyname']),addslashes($row['companyname']));
    }
   }

   if($row['displayname'] == "regname" && $row['regname'] != $_REQUEST['regname']) {
    UpdatePastCompanyname($row['memid'],addslashes($_REQUEST['regname']),addslashes($row['regname']));
   }

   if($_REQUEST['companyname'] != $row['companyname']) {
    $SQL->add_item("oldcompanyname", encode_text2($row['companyname']));
   }

   if($row['status'] != 1) {
    $SQL->add_item("status", $_REQUEST['status']);
   }

   $SQL->add_item("area", $_REQUEST['area']);
   $SQL->add_item("licensee", $_REQUEST['licensee']);

   if($row['licensee'] != $_REQUEST['licensee']) {

    $FromLicensee = mysql_fetch_assoc(dbRead("select * from area where FieldID = '".$row['licensee']."'"));
    $ToLicensee = mysql_fetch_assoc(dbRead("select * from area where FieldID = '".$_REQUEST['licensee']."'"));

    //$EmailMessage = "Dear Licensee,\r\n\r\n Member ".$_REQUEST['companyname']."(".$_REQUEST['Client'].") has been changed from Licensee Area: ".$FromLicensee['place']." to Licensee Area: ".$ToLicensee['place'].".\r\n\r\n Both Licensees have been notified of this change.\r\n\r\nE Banc Trade Membership Accounts.";
    //mail("accounts@".$_SESSION['Country']['countrycode'].".ebanctrade.com", "Membership Licensee Area Change", $EmailMessage, "Bcc: ".$FromLicensee['email'].",".$ToLicensee['email']."");

    $subject = "Membership Agency Area Change";
    $text = "Member ".$_REQUEST['companyname']."(".$_REQUEST['Client'].") has been changed from Agent Area: ".$FromLicensee['place']." to Agent Area: ".$ToLicensee['place'].".\r\n\r\n Both Agency have been notified of this change.\r\n\r\nMembership Accounts.";

    $text = get_html_template($_SESSION['User']['CID'],'Licensee',$text);

    unset($attachArray);
    unset($addressArray);
    unset($bccArray);
	$addressArray[] = array(trim('accounts@'.$_SESSION['Country']['countrycode'].'.'.getWho($_SESSION['Country']['logo'], 2)), 'Agent');
	$addressArray[] = array(trim($FromLicensee['email']), 'Agent');
	$addressArray[] = array(trim($ToLicensee['email']), 'Agent');
	//$bccArray[] = array(trim($FromLicensee['email']), 'Agent');
	//$bccArray[] = array(trim($ToLicensee['email']), 'Agent');

	sendEmail("accounts@" . $_SESSION[Country][countrycode] ."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray, $attachArray);

   }

   if($row['opt'] != $_REQUEST['opt'] || $row['reopt'] != $_REQUEST['reopt']) {

     if(($row['opt'] != $_REQUEST['opt']) && $_REQUEST['opt'] == 'N') {

         $EmailMessage = "I am confirming that at your request, I have removed your email address from our promotional email list.  If you receive further emails of a promotional nature from any Empire Trade office, please forward them to me so I can rectify the situation.<br><br>If at any time you wish to recommence receiving emails promoting specials, events, or property opportunities, please contact me.<br><br>".$_SESSION['User']['Name']."";
	     $text = get_html_template($_SESSION['Country']['countryID'], $_REQUEST['accholder'], $EmailMessage);
	     unset($addressArray);

		 if(strstr($_REQUEST['emailAddress_3'], ";")) {
			$emailArray = explode(";", $_REQUEST['emailAddress_3']);
			foreach($emailArray as $key => $value) {
				$addressArray[] = array(trim($value), $_REQUEST['accholder']);
			}
		 } else {
			$addressArray[] = array(trim($_REQUEST['emailAddress_3']), $_REQUEST['accholder']);
		 }

		 if($_REQUEST['send_opt_em']) {
		 	sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.'. getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1).' - Mail Outs', 'accounts@' . $_SESSION[Country][countrycode] . '.'.getWho($_SESSION[Country][logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray);
			$tt = "Opted out of Promotional Publications and Email Sent";
		 } else {
			$tt = "Opted out of Promotional Publications";
		 }

  		 dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','".$tt."')");

     }  elseif(($row['reopt'] != $_REQUEST['reopt']) && $_REQUEST['reopt'] == 'N') {

         $EmailMessage = "I am confirming that at your request, I have removed your email address from our real estate bulletin email list.  If you receive further emails of a promotional nature from any Empire Trade office, please forward them to me so I can rectify the situation.<br><br>If at any time you wish to recommence receiving emails promoting specials, events, or property opportunities, please contact me.<br><br>".$_SESSION['User']['Name']."";
	     $text = get_html_template($_SESSION['Country']['countryID'], $_REQUEST['accholder'], $EmailMessage);
	     unset($addressArray);

		 if(strstr($_REQUEST['emailAddress_3'], ";")) {
			$emailArray = explode(";", $_REQUEST['emailAddress_3']);
			foreach($emailArray as $key => $value) {
				$addressArray[] = array(trim($value), $_REQUEST['accholder']);
			}
		 } else {
			$addressArray[] = array(trim($_REQUEST['emailAddress_3']), $_REQUEST['accholder']);
		 }

		 if($_REQUEST['send_reopt_em']) {
		 	sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.'. getWho($_SESSION[Country][logo], 2), getWho($_SESSION[Country][logo], 1).' - Mail Outs', 'accounts@' . $_SESSION[Country][countrycode] . '.'.getWho($_SESSION[Country][logo], 2), getWho($row2[logo], 1) .' Accounts', $text, $addressArray);
			$tt = "Opted out of Real Estate Publications and Email Sent";
		 } else {
			$tt = "Opted out of Real Estate Publications";
		 }

   		 dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Opted out of Real Estate Publications and Email Sent')");

     }
   }

   if($row['t_unlist'] != $_REQUEST['t_unlist']) {

     if($_REQUEST['t_unlist'] == 1) {
       $EmailMessage = "Dear CS,\r\n\r\n Member ".$_REQUEST['companyname']."(".$_REQUEST['Client'].") has been temp unlisted by:".$_SESSION['User']['Name'].".\r\n\r\nMembership Accounts.";
     }  else {
       $EmailMessage = "Dear CS,\r\n\r\n Member ".$_REQUEST['companyname']."(".$_REQUEST['Client'].") has been relisted by:".$_SESSION['User']['Name'].".\r\n\r\nMembership Accounts.";
     }

     mail("accounts@".$_SESSION['Country']['countrycode'].".". getWho($_SESSION[Country][logo], 2), "Membership Temp Unlist", $EmailMessage, "Bcc: membersupport@".$_SESSION['Country']['countrycode'].".". getWho($_SESSION[Country][logo], 2));

   }

   if(encode_text2($row['companyname']) != encode_text2($_REQUEST['companyname'])) {

    //mail("customersupport@".$_SESSION['Country']['countrycode'].".ebanctrade.com","Membership Name Change - $row[memid]","Members account number $row[memid] name has changed\r\n\r\nFrom: ".encode_text2(addslashes($row['companyname']))."\r\n\r\nTo: ".encode_text2(addslashes($_REQUEST['companyname']))."","From: E Banc Admin Site <antony@ebanctrade.com>");
    //dbwrite("update members set oldcompanyname = '".addslashes(encode_text2($_REQUEST['companyname']))."' where memid = '".$row['memid']."'");

    $subject = "Membership Name Change - ".$row[memid];
    $text = "Members account number ".$row[memid]." name has changed\r\n\r\nFrom: ".encode_text2(addslashes($row['companyname']))."\r\n\r\nTo: ".encode_text2(addslashes($_REQUEST['companyname']))."";

    $text = get_html_template($_SESSION['User']['CID'],'Customer Support',$text);

    unset($attachArray);
    unset($addressArray);
    unset($bccArray);
	$addressArray[] = array(trim('customersupport@'.$_SESSION['Country']['countrycode'].'.'.getWho($_SESSION[Country][logo], 2)), 'customersupport');
	//$bccArray[] = array(trim($FromLicensee['email']), 'Agent');
	//$bccArray[] = array(trim($ToLicensee['email']), 'Agent');

	sendEmail("accounts@" . $_SESSION[Country][countrycode] ."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray, $attachArray);

   }

   if($row['status'] != $_REQUEST['status'] && $_REQUEST['status'] == 1) {

    $Lic = dbRead("select * from area where FieldID = '".$_REQUEST['licensee']."'");
    $RowLic = mysql_fetch_assoc($Lic);

    //mail($rowlic['reportemail'],"Membership Deactived - " . $row['memid'],"Members account number ".$row['memid']." has been Deactivated","From: ETX Admin Site <accounts@".getWho($_SESSION[Country][logo], 2).">");
    mail($RowLic['email'],"Membership Deactived - " . $row['memid'],"Members account number ".$row['memid']." has been Deactivated","From: ETX Admin Site <accounts@".getWho($_SESSION[Country][logo], 2).">");

    dbWrite("update members set datedeactivated = '".date("Y-m-d")."' where memid = '".$_REQUEST['Client']."'");

   }

  }

  $SQL->add_where("memid = '".$_REQUEST['Client']."'");

  if(checkmodule("EditMemberLevel2") || checkmodule("EditMemberLevel1")) {

   dbWrite($SQL->get_sql_update());

  }

  if($_SESSION['User']['CID'] != 15) {

	if(!$accountsRow['email'] and $_REQUEST['emailAddress_2']) {

		if($row['monthlyfeecash'] > 0)  {

			   //if((!$accountsRow['email'] && $_REQUEST['emailAddress_2']) || ($_REQUEST['emailAddress_2']  && ($accountsRow['email'] != $_REQUEST['emailAddress_2']) && $row['monthlyfeecash'] > 0)) {
			   //if(!$accountsRow['email'] && $_REQUEST['emailAddress_2'] || $_REQUEST['emailAddress_2']  && $accountsRow['email'] != $_REQUEST['emailAddress_2'] && $row['monthlyfeecash'] > 0) {
			   ?><body onload="ConfirmStationery()"></body><?

		}

	}


  }

  UpdateLastEdit();

  $row = mysql_fetch_assoc(dbRead("select members.* from members where memid = '".$_REQUEST['Client']."'"));
  display_quick_notes($row);
  display_main_info($row);

 }

}

function update_address_info($row) {

 $Errormsg = check_errors("Address");

 if($Errormsg) {
  display_address_info($row, $Errormsg['Messages'], $Errormsg['Highlight']);
 } else {

  log_changes($row,"4");

  $SQL = new dbCreateSQL();

  $SQL->add_table("members");

  $SQL->add_item("streetno", encode_text2($_REQUEST['streetno']));
  $SQL->add_item("streetname", encode_text2($_REQUEST['streetname']));
  $SQL->add_item("suburb", encode_text2($_REQUEST['suburb']));
  $SQL->add_item("city", encode_text2($_REQUEST['city']));
  $SQL->add_item("state", encode_text2($_REQUEST['state']));
  $SQL->add_item("postcode", encode_text2($_REQUEST['postcode']));
  $SQL->add_item("postalno", encode_text2($_REQUEST['postalno']));
  $SQL->add_item("postalname", encode_text2($_REQUEST['postalname']));
  $SQL->add_item("postalstate", encode_text2($_REQUEST['postalstate']));
  $SQL->add_item("postalsuburb", encode_text2($_REQUEST['postalsuburb']));
  $SQL->add_item("postalcity", encode_text2($_REQUEST['postalcity']));
  $SQL->add_item("postalpostcode", encode_text2($_REQUEST['postalpostcode']));
  $SQL->add_item("homestreetno", encode_text2($_REQUEST['homestreetno']));
  $SQL->add_item("homestreetname", encode_text2($_REQUEST['homestreetname']));
  $SQL->add_item("homestate", encode_text2($_REQUEST['homestate']));
  $SQL->add_item("homesuburb", encode_text2($_REQUEST['homesuburb']));
  $SQL->add_item("homecity", encode_text2($_REQUEST['homecity']));
  $SQL->add_item("homepostcode", encode_text2($_REQUEST['homepostcode']));
  $SQL->add_item("giftstreetno", encode_text2($_REQUEST['giftstreetno']));
  $SQL->add_item("giftstreetname", encode_text2($_REQUEST['giftstreetname']));
  $SQL->add_item("giftstate", encode_text2($_REQUEST['giftstate']));
  $SQL->add_item("giftsuburb", encode_text2($_REQUEST['giftsuburb']));
  $SQL->add_item("giftcity", encode_text2($_REQUEST['giftcity']));
  $SQL->add_item("giftpostcode", encode_text2($_REQUEST['giftpostcode']));

  if(checkmodule("EditMemberLevel2")) {
   $SQL->add_item("area", $_REQUEST['area']);
  }

  $SQL->add_where("memid = '".$_REQUEST['Client']."'");

  UpdateLastEdit();

  if(checkmodule("EditMemberLevel2") || checkmodule("EditMemberLevel1")) {

   dbWrite($SQL->get_sql_update());

  }
  $row = mysql_fetch_assoc(dbRead("select members.* from members where memid = '".$_REQUEST['Client']."'"));
  display_address_info($row);

 }

}

function add_category_info($row) {

 $Errormsg = check_errors("Categories",$row);

 UpdateLastEdit();

 if($Errormsg) {
  display_category_info($row, $Errormsg['Messages'], $Errormsg['Highlight']);
 } else {

  if($_REQUEST['dir_pos'] == 1)   {
   $dir_nation = 1;
   $dir_state = 9;
  } elseif($_REQUEST['dir_pos'] == 2) {
   $dir_nation = 9;
   $dir_state = 1;
  } elseif($_REQUEST['dir_pos'] == 3) {
   $dir_nation = 9;
   $dir_state = 9;
  }

  if(checkmodule("EditMemberLevel2"))  {
   dbWrite("insert into mem_categories (memid,category,description,engdesc,dir_nation,dir_state) values ('".addslashes($_REQUEST['Client'])."','".addslashes($_REQUEST['add_catid'])."','".addslashes(encode_text2($_REQUEST['description']))."','".addslashes(encode_text2($_REQUEST['engdescription']))."','".$dir_nation."','".$dir_state."')");
  } else {
   dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('".addslashes($_REQUEST['Client'])."','".addslashes($_REQUEST['add_catid'])."','".addslashes(encode_text2($_REQUEST['description']))."','".addslashes(encode_text2($_REQUEST['engdescription']))."')");
  }

 $CatSQL = dbRead("select * from categories where catid = ".addslashes($_REQUEST['add_catid']));
 $CatRow = mysql_fetch_assoc($CatSQL);

 $logdata['category'] = array(0,addslashes($_REQUEST['add_catid']));
 $logdata['description'] = array(0,addslashes(encode_text2($_REQUEST['description'])));

 add_kpi(33,addslashes($_REQUEST['Client']),$logdata);

 display_category_info($row);
 }
}

function update_category_info($row) {

 $Errormsg = check_errors("Categories",$row);

 UpdateLastEdit();

 if($Errormsg) {
  edit_category_info($row, $Errormsg['Messages'],$Errormsg['Highlight'],$_REQUEST['UpdateCatID']);
 } else {

 //log_changes($row,"4");

  $currentSQL = dbRead("select mem_categories.* from mem_categories where FieldID = " . addslashes($_REQUEST['UpdateCatID']));
  $currentRow = mysql_fetch_assoc($currentSQL);

  if($_REQUEST['dir_pos'] == 1)   {
   $dir_nation = 1;
   $dir_state = 9;
  } elseif($_REQUEST['dir_pos'] == 2) {
   $dir_nation = 9;
   $dir_state = 1;
  } elseif($_REQUEST['dir_pos'] == 3) {
   $dir_nation = 9;
   $dir_state = 9;
  }

  if(checkmodule("EditMemberLevel2"))  {

  	if($dir_nation != $currentRow['dir_nation']) {

  	 // log change
  	 $logdata['dir_nation'] = array($currentRow['dir_nation'],$dir_nation);

  	}

  	if($dir_state != $currentRow['dir_state']) {

  	 // log change
  	 $logdata['dir_state'] = array($currentRow['dir_state'],$dir_state);

  	}

  	//if($_FILES['picture']) {
  	if($_REQUEST['picture']) {

		$picture_name_new = $_REQUEST['UpdateCatID'];

		move_uploaded_file($_FILES['picture']['tmp_name'], "/home/etxint/public_html/logoimages/$picture_name_new.jpg");

		$source="/home/etxint/public_html/logoimages/".$picture_name_new.".jpg";
		$dest="/home/etxint/public_html/logoimages/thumb-".$picture_name_new.".jpg";
		copy($source, $dest);
		exec('convert -geometry 75 /home/etxint/public_html/logoimages/thumb-' . $picture_name_new . '.jpg /home/etxint/public_html/logoimages/thumb-' . $picture_name_new . '.jpg');

		$imagehw = GetImageSize("/home/etxint/public_html/logoimages/".$picture_name_new.".jpg");
		$imagewidth = $imagehw[0];

		if($imagewidth == 140) {
		 $t = 1;
		} elseif($imagewidth == 260) {
		 $t = 3;
		} elseif($imagewidth == 1100) {
		 $t = 4;
		}

	    dbWrite("update mem_categories set dir_ad = '".$t."' where FieldID = '".addslashes($_REQUEST['UpdateCatID'])."'");

  	}

    dbWrite("update mem_categories set category = '".addslashes($_REQUEST['add_catid'])."', description = '".addslashes(encode_text2($_REQUEST['description']))."', engdesc = '".addslashes(encode_text2($_REQUEST['engdescription']))."', dir_nation = '".$dir_nation."', dir_state = '".$dir_state."' where FieldID = '".addslashes($_REQUEST['UpdateCatID'])."'");

  } else {

    dbWrite("update mem_categories set category = '".addslashes($_REQUEST['add_catid'])."', description = '".addslashes(encode_text2($_REQUEST['description']))."', engdesc = '".addslashes(encode_text2($_REQUEST['engdescription']))."' where FieldID = '".addslashes($_REQUEST['UpdateCatID'])."'");

  }

  if($_REQUEST['olddescription'] != $_REQUEST['description']) {

   $logdata['description'] = array($_REQUEST['olddescription'], $_REQUEST['description']);

  }

 $TESTQuery = dbRead("select count(*) as CatCount from mem_categories where memid = '".$_REQUEST['Client']."' and category != 0");
 $CatCount = mysql_fetch_assoc($TESTQuery);
 //if($CatCount['CatCount'] < 1) {
 if(!$CatCount['CatCount'] && $currentRow['category'] > 0) {
  dbWrite("update members set t_unlist = 0, bdriven = 'N' where memid = '".$_REQUEST['Client']."'");
  $newnote = "Member has been unlisted";
  $date1 = date("Y-m-d");
  dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".$date1."','".$_SESSION['User']['FieldID']."','1','".addslashes(encode_text2($newnote))."')");
  mail("customersupport@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2),"Membership Unlisted - ".$row['memid'],"Members account number ".$row['memid']." (".$row['companyname'].") has been Unlisted  by ".$_SESSION['User']['Name']."","From: ETX Admin Site <hq@".getWho($_SESSION['Country'][logo], 2).">","Bcc: accounts@".$_SESSION['Country']['countrycode'].".".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2));
 }

 $CatSQL = dbRead("select * from categories where catid = ".addslashes($_REQUEST['add_catid']));
 $CatRow = mysql_fetch_assoc($CatSQL);

 $CatSQL2 = dbRead("select * from categories where catid = ".addslashes($_REQUEST['OldCatID']));
 $CatRow2 = mysql_fetch_assoc($CatSQL2);

 if($_REQUEST['OldCatID'] != $_REQUEST['add_catid']) {

  $logdata['category']  = array($_REQUEST['OldCatID'], $_REQUEST['add_catid']);

 }

 add_kpi(34,addslashes($_REQUEST['Client']),$logdata);

 display_category_info($row);
 }
}

function delete_category_info($DeleteID,$row) {

 UpdateLastEdit();

 $TESTQuery = dbRead("select count(*) as CatCount from mem_categories where memid = '".$_REQUEST['Client']."' and category != 0");
 $CatCount = mysql_fetch_assoc($TESTQuery);

 //$NoCatCheckSQL = dbRead("select mem_categories.* from mem_categories where FieldID = " . $_REQUEST['Delete']);
 $NoCatCheckSQL = dbRead("select mem_categories.* from mem_categories where FieldID = " . $DeleteID ." and category != 0");
 $NoCatCheckRow = @mysql_fetch_assoc($NoCatCheckSQL);

 //if($CatCount['CatCount'] > 1 || ($CatCount['CatCount'] > 1 && !$NoCatCheckRow['category'])) {
 if($CatCount['CatCount'] > 1 || ($CatCount['CatCount'] == 1 && !$NoCatCheckRow['category'])) {

  $date1 = date("Y-m-d");
  $newnote = "Category had been Deleted";
  dbWrite("delete from mem_categories where memid = '".$_REQUEST['Client']."' and FieldID = '".addslashes($DeleteID)."'");
  dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".$date1."','".$_SESSION['User']['FieldID']."','1','".addslashes(encode_text2($newnote))."')");
  if($CatCount['CatCount'] == 1 && $ff) {
   dbWrite("update members set t_unlist = 0, bdriven = 'N' where memid = '".$_REQUEST['Client']."'");
   $newnote = "Member has been unlisted";
   dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".$date1."','".$_SESSION['User']['FieldID']."','1','".addslashes(encode_text2($newnote))."')");
   mail("customersupport@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2),"Membership Unlisted - ".$row['memid'],"Members account number ".$row['memid']." (".$row['companyname'].") has been Unlisted  by ".$_SESSION['User']['Name']."","From: E Banc Admin Site <hq@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2).">","Bcc: accounts@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2));
  }

  $logdata['category']  = array($NoCatCheckRow['category'],0);
  add_kpi(66,addslashes($_REQUEST['Client']),$logdata);

 } else {

  if(checkmodule("EditMemberLevel2")) {

   $TotalCountSQL = dbRead("select count(*) as CatCount from mem_categories where memid = '".$_REQUEST['Client']."'");
   $CatCountRow = mysql_fetch_assoc($TotalCountSQL);

   if($CatCountRow['CatCount'] > 1) {

    dbWrite("delete from mem_categories where memid = '".$_REQUEST['Client']."' and FieldID = '".addslashes($DeleteID)."'");
    if($CatCount['CatCount'] == 1) {
     dbWrite("update members set t_unlist = 0, bdriven = 'N' where memid = '".$_REQUEST['Client']."'");
     $newnote = "Member has been unlisted";
     $date1 = date("Y-m-d");
     dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".$date1."','".$_SESSION['User']['FieldID']."','1','".addslashes(encode_text2($newnote))."')");
     mail("customersupport@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2),"Membership Unlisted - ".$row['memid'],"Members account number ".$row['memid']." (".$row['companyname'].") has been Unlisted by ".$_SESSION['User']['Name']."","From: E Banc Admin Site <hq@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2).">","Bcc: accounts@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2));
    }

    $logdata['category']  = array($NoCatCheckRow['category'],0);
    add_kpi(66,addslashes($_REQUEST['Client']),$logdata);

   }

  }

 }

 display_category_info($row);

}

function update_misc_info($row) {

 $Errormsg = check_errors("Misc",$row);

 UpdateLastEdit();

 if($Errormsg) {
  display_misc_info($row, $Errormsg['Messages'], $Errormsg['Highlight']);
 } else {

  log_changes($row,"4");

  $Banked = $_REQUEST['banked_Year'] . "-" . $_REQUEST['banked_Month'] . "-" . $_REQUEST['banked_Day'];
  $DateJoined = $_REQUEST['datejoined_Year'] . "-" . $_REQUEST['datejoined_Month'] . "-" . $_REQUEST['datejoined_Day'];
  $DatePackSent = $_REQUEST['datepacksent_Year'] . "-" . $_REQUEST['datepacksent_Month'] . "-" . $_REQUEST['datepacksent_Day'];

  if($_REQUEST['sent'] == 1)  {
   $acc = 1;
  }

  if($_REQUEST['received'] == 1) {
   $acc = 2;
  }

  if($row['priority'] == 0 && $_REQUEST['priority'] > 0) {
   $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
   dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".$curdate."','".$_SESSION['User']['FieldID']."','2','".addslashes(encode_text2("Priority Activated"))."')");
  }

  $SQL = new dbCreateSQL();

  $SQL->add_table("members");

  if($_REQUEST['trade_per'] != $row['trade_per']) {
   $date_per = date("Y-m-d");
   $SQL->add_item("date_per", $date_per);
  }
  $SQL->add_item("trade_per", encode_text2($_REQUEST['trade_per']));
  $SQL->add_item("sms", encode_text2($_REQUEST['sms']));
  $SQL->add_item("homephonearea", encode_text2($_REQUEST['homephonearea']));
  $SQL->add_item("homephone", encode_text2($_REQUEST['homephone']));
  $SQL->add_item("star", encode_text2($_REQUEST['star']));
  $SQL->add_item("priority", encode_text2($_REQUEST['priority']));
  $SQL->add_item("uncon", encode_text2($_REQUEST['uncon']));
  $SQL->add_item("gift", encode_text2($_REQUEST['gift']));
   if($_REQUEST['uncon'] != $row['uncon']) {
    if($_REQUEST['uncon'] == 'Y') {
     $SQL->add_item("status", 6);
    } elseif($_REQUEST['uncon'] == 'N') {
     $SQL->add_item("status", 0);
	}
   }

  if(checkmodule("EditMemberLevel2")) {

   $SQL->add_item("abn", encode_text2($_REQUEST['abn']));
   if($_REQUEST['abn']) {
    $SQL->add_item("supply_statement", 3);
   }
   $SQL->add_item("gst", $_REQUEST['gst']);

   if(!$_REQUEST['wagesacc']) {
    $SQL->add_item("wagesacc", "NULL");
   } else {
    $SQL->add_item("wagesacc", encode_text2($_REQUEST['wagesacc']));
   }
   $SQL->add_item("accept", $acc);
   $SQL->add_item("memusername", encode_text2($_REQUEST['memusername']));
   $SQL->add_item("mempassword", encode_text2($_REQUEST['mempassword']));
   $SQL->add_item("Card_No", encode_text2($_REQUEST['Card_No']));
   $SQL->add_item("Card_Exp", encode_text2($_REQUEST['Card_Exp']));
   $SQL->add_item("Terminal_No", encode_text2($_REQUEST['Terminal_No']));
   $SQL->add_item("salesmanid", $_REQUEST['salesmanid']);
   $SQL->add_item("membershipfeepaid", encode_text2($_REQUEST['membershipfeepaid']));
   $SQL->add_item("trade_membership", encode_text2($_REQUEST['trade_membership']));
   $SQL->add_item("memshipfeepaytype", encode_text2($_REQUEST['memshipfeepaytype']));
   $SQL->add_item("banked", $Banked);
   $SQL->add_item("datejoined", $DateJoined);
   $SQL->add_item("datepacksent", $DatePackSent);
   $SQL->add_item("transfeecash", encode_text2($_REQUEST['transfeecash']));
   $SQL->add_item("monthlyfeecash", encode_text2($_REQUEST['monthlyfeecash']));
   $SQL->add_item("goldcard", $_REQUEST['goldcard']);
   $SQL->add_item("feescharge", $_REQUEST['feescharge']);
   $SQL->add_item("cheque_no", $_REQUEST['cheque_no']);
   $SQL->add_item("displayname", $_REQUEST['displayname']);
   $SQL->add_item("respenddown", $_REQUEST['respenddown']);
   $SQL->add_item("admin_exempt", $_REQUEST['admin_exempt']);
   $SQL->add_item("itt_exempt", $_REQUEST['itt_exempt']);
   $SQL->add_item("interest", $_REQUEST['interest']);
   $SQL->add_item("rob", $_REQUEST['rob']);
   $SQL->add_item("accept", $acc);
   $SQL->add_item("accept", $acc);
   $SQL->add_item("gift_rec", $_REQUEST['gift_rec']);
   $SQL->add_item("gift_type", $_REQUEST['gift_type']);

   //dbWrite("update PHPAUCTIONXL_users set password = '" . md5("lostit" . addslashes(encode_text2($_REQUEST['mempassword']))) . "' where id = '" . addslashes($_REQUEST['Client']) . "'", "empireAuction");

  }

  $SQL->add_where("memid = '".$_REQUEST['Client']."'");

  if(checkmodule("EditMemberLevel2") || checkmodule("EditMemberLevel1")) {

   dbWrite($SQL->get_sql_update());

  }

  $row = mysql_fetch_assoc(dbRead("select members.* from members where memid = '".$_REQUEST['Client']."'"));
  display_misc_info($row);

 }

}

function update_payment_info($row) {

 $Errormsg = check_errors("Payment Details");

 UpdateLastEdit();

 if($Errormsg) {
  display_payment_info($row, $Errormsg['Messages'], $Errormsg['Highlight']);
 } else {

  log_changes($row,"4");

  if(checkmodule("EditMemberLevel2")) {

   $SQL = new dbCreateSQL();

   $SQL->add_table("members");

   $SQL->add_item("paymenttype", encode_text2($_REQUEST['paymenttype']));
   $SQL->add_item("accountname", encode_text2($_REQUEST['accountname']));
   $SQL->add_item("accountno", encode_text2($_REQUEST['accountno']));
   $SQL->add_item("expires", encode_text2($_REQUEST['expires']));
   $SQL->add_item("supply_statement", encode_text2($_REQUEST['supply_statement']));
   $SQL->add_item("reward_bsb", encode_text2($_REQUEST['reward_bsb']));
   $SQL->add_item("reward_accno", encode_text2($_REQUEST['reward_accno']));
   $SQL->add_item("reward_accname", encode_text2($_REQUEST['reward_accname']));
   $SQL->add_item("reward_sponsorship", encode_text2($_REQUEST['reward_sponsorship']));

   $SQL->add_where("memid = '".$_REQUEST['Client']."'");

  }

  if(checkmodule("EditMemberLevel2") || checkmodule("EditMemberLevel1")) {

   dbWrite($SQL->get_sql_update());

  }
  $row = mysql_fetch_assoc(dbRead("select members.* from members where memid = '".$_REQUEST['Client']."'"));
  display_payment_info($row);

 }

}

function add_note($row) {

 $newnote = encode_text2(addslashes($_REQUEST['note']));
 if($_REQUEST['message_sendto']) {
    $dbgetuser = dbRead("select * from tbl_admin_users where FieldID = ".$_REQUEST['message_sendto']);
	$rowuser = mysql_fetch_assoc($dbgetuser);
 	$newnote = $newnote." - (".$rowuser['Name'].")";
 }

 UpdateLastEdit();

 $reminder = $_REQUEST['year1']."-".$_REQUEST['month1']."-".$_REQUEST['day1'];
 if($reminder < date("Y-m-d"))  {
  $reminder = "0000-00-00";
 } elseif($reminder > date("Y-m-d")) {
  $newnote = "Reminder Set (".$reminder.") - ".$newnote;
 }

 if($_REQUEST['reply']) {
   	$res = 1;
 } else {
 	$res = 0;
 }

 $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
 $noteid = dbWrite("insert into notes (memid,date,userid,type,reminder,note,responseid) values ('".$_REQUEST['Client']."','".$curdate."','".$_SESSION['User']['FieldID']."','".$_REQUEST['type']."','".$reminder."','".addslashes(encode_text2($newnote))."','".$res."')", "etradebanc", true);

 if($_REQUEST['anote']) {
  dbWrite("insert into notes (memid,date,userid,type,reminder,note) values ('".$_REQUEST['anote']."','".$curdate."','".$_SESSION['User']['FieldID']."','".$_REQUEST['type']."','".$reminder."','".addslashes(encode_text2($newnote))."')");
 }

 if($_REQUEST['reply']) {
 	$noteid = $noteid;
 } else {
 	$noteid = 0;
 }

 if($_REQUEST['message_sendto']) {
  $note = $newnote." for Acc No ".$_REQUEST['Client'];
  $DBDate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
  dbWrite("insert into message_system (Date_Entered,Sender,Receiver,Importance,Message,noteid) values ('".$DBDate."','".$_SESSION['User']['FieldID']."','".$_REQUEST['message_sendto']."','1','".addslashes(encode_text2($note))."','".$noteid."')", "etxint_ebanc_message");
 }

 if(checkmodule("Log")) {
  add_kpi("11",$_REQUEST['memid']);
 }

}

function which_yn($input) {

 switch($input) {
  case "Y": return "1";
  default: return "0";
 }

}

function display_main_info($row, $Errormsg = false, $ErrorArray = false) {

 global $PageData;
 print_error($Errormsg);

 $EmailOut = array('Y' => 'Yes', 'N' => 'No');
 $REEmailOut = array('Y' => 'Yes', 'N' => 'No');
 $BDriven = array('Y' => 'Yes', 'N' => 'No');
 $T_Unlist = array('1' => 'Yes', '0' => 'No');

 ?>
  <script language="javascript">
   function sameEmail() {

    if(document.member_edit.sameEmailAddress.checked == true) {

     <?

	 	$typeSQL = dbRead("select tbl_email_type.* from tbl_email_type order by FieldID");
	 	while($typeRow = mysql_fetch_assoc($typeSQL)) {

	 	 	?>
     		document.member_edit.emailAddress_<?= $typeRow['FieldID'] ?>.value = document.member_edit.emailAddress_3.value;
     		<?

     	}

     ?>

    } else {

     <?

	 	$typeSQL = dbRead("select tbl_email_type.* from tbl_email_type where updateAll = 1 order by FieldID");
	 	while($typeRow = mysql_fetch_assoc($typeSQL)) {

		 	$emailSQL = dbRead("select tbl_members_email.* from tbl_members_email where acc_no = " . $row['memid'] . "  and type = " . $typeRow['FieldID']);
		 	$emailRow = mysql_fetch_assoc($emailSQL);

	 	 	?>
     		document.member_edit.emailAddress_<?= $typeRow['FieldID'] ?>.value = '<?= $emailRow['email'] ?>';
     		<?

     	}

     ?>

    }

   }
  </script>
  <input type="hidden" name="Update" value="1">

  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>" align="center" colspan="2"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] - <?= get_page_data("1") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("49") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="pin" size="30" maxlength="80" value="<?= which_data($row,"pin",$Errormsg) ?>"<?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("2") ?>:</td>
          <td bgcolor="<?= change_colour("regname", $ErrorArray) ?>" align="left"><input type="text" name="regname" size="30" maxlength="80" value="<?= which_data($row,"regname",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap ><?= get_word("3") ?>:</td>
          <td bgcolor="<?= change_colour("companyname", $ErrorArray) ?>" align="left"><input type="text" name="companyname" size="30" maxlength="80" value="<?= htmlspecialchars(which_data($row,"companyname",$Errormsg)) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("4") ?>:</td>
          <td bgcolor="<?= change_colour("accholder", $ErrorArray) ?>" align="left"><input type="text" name="accholder" size="30" maxlength="80" value="<?= which_data($row,"accholder",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("4") ?> Firstname:</td>
          <td bgcolor="<?= change_colour("accholder_first", $ErrorArray) ?>" align="left"><input type="text" name="accholder_first" size="30" maxlength="80" value="<?= which_data($row,"accholder_first",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("4") ?> Surname:</td>
          <td bgcolor="<?= change_colour("accholder_surname", $ErrorArray) ?>" align="left"><input type="text" name="accholder_surname" size="30" maxlength="80" value="<?= which_data($row,"accholder_surname",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("4") ?> DOB:</td>
          <td bgcolor="<?= change_colour("DOBholder", $ErrorArray) ?>" align="left"><?= do_date("DOBholder",which_data($row,"DOBholder",$ErrorMsg),true,check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("5") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="contactname" size="30" maxlength="80" value="<?= which_data($row,"contactname",$Errormsg) ?>"<?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("5") ?> DOB:</td>
          <td bgcolor="<?= change_colour("DOBcontact", $ErrorArray) ?>" align="left"><?= do_date("DOBcontact",which_data($row,"DOBcontact",$ErrorMsg),true,check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("6") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="signatories" size="30" maxlength="80" value="<?= which_data($row,"signatories",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("7") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="phonearea" size="5" maxlength="15" value="<?= which_data($row,"phonearea",$Errormsg) ?>" onKeyPress="return number(event)" <?= check_disabled("1"); ?>>&nbsp;<input type="text" name="phoneno" size="20" maxlength="15" value="<?= which_data($row,"phoneno",$Errormsg) ?>" onKeyPress="return phonenumber(event)" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("8") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="faxarea" size="5" maxlength="15" value="<?= which_data($row,"faxarea",$Errormsg) ?>" onKeyPress="return number(event)" <?= check_disabled("1"); ?>>&nbsp;<input type="text" name="faxno" size="20" maxlength="15" value="<?= which_data($row,"faxno",$Errormsg) ?>" onKeyPress="return phonenumber(event)" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("10") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="mobile" size="10" maxlength="15" value="<?= which_data($row,"mobile",$Errormsg) ?>" onKeyPress="return phonenumber(event)" <?= check_disabled("1"); ?>></td>
         </tr>
		 <?

		 	$typeSQL = dbRead("select tbl_email_type.* from tbl_email_type order by orderBy");
		 	while($typeRow = mysql_fetch_assoc($typeSQL)) {

		 	 $emailSQL = dbRead("select tbl_members_email.* from tbl_members_email where acc_no = " . $row['memid'] . "  and type = " . $typeRow['FieldID']);
		 	 $emailRow = mysql_fetch_assoc($emailSQL);

			 ?>

	         <tr>
	          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word($typeRow['word_id']) ?>:</td>
	          <td bgcolor="<?= change_colour("emailAddress_" . $typeRow['FieldID'], $ErrorArray) ?>" align="left"><input type="text" name="emailAddress_<?= $typeRow['FieldID'] ?>" size="30" maxlength="80" value="<? if(!$_REQUEST["emailAddress_$typeRow[FieldID]"]) { print $emailRow['email']; } else { print $_REQUEST["emailAddress_$typeRow[FieldID]"]; } ?>" <?= check_disabled("1"); ?>><? if($typeRow['orderBy'] == 1) { ?><input type="checkbox" name="sameEmailAddress" onclick="javascript:sameEmail();" value="ON" <?= check_disabled("1"); ?>>&nbsp;<?= get_page_data("48") ?><? } ?></td>
	         </tr>

	         <?

	         $counter++;

         	}

         ?>

         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("21") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('opt',$EmailOut,'','',which_data($row,"opt",$Errormsg),'',check_disabled("1")); ?> <input type="checkbox" name="send_opt_em" value="1"> Send Email Confirmation</td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("22") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('reopt',$REEmailOut,'','',which_data($row,"reopt",$Errormsg),'',check_disabled("1")); ?> <input type="checkbox" name="send_reopt_em" value="1"> Send Email Confirmation</td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>External Email Outs:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('exopt',$REEmailOut,'','',which_data($row,"exopt",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("23") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('bdriven',$BDriven,'','',which_data($row,"bdriven",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("199") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('t_unlist',$T_Unlist,'','',which_data($row,"t_unlist",$Errormsg),'',check_disabled("1")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("239") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('direct',$REEmailOut,'','',which_data($row,"direct",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("28") ?>:</td>
          <td bgcolor="<?= change_colour("webpageurl", $ErrorArray) ?>" align="left"><input type="text" name="webpageurl" size="30" maxlength="80" value="<?= which_data($row,"webpageurl",$Errormsg) ?>" <?= check_disabled("1"); ?>> <a class="nav" href="http://<?= $row[webpageurl] ?>" target="_blank"> Click to site</a></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("12") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            if($row['status'] == 1) {

             ?><font color="#FF0000"><b>DEACTIVATED</b></font><?

            } else {

             $sql_query = dbRead("select * from status where FieldID != 1 order by FieldID");
             form_select('status',$sql_query,'Name','FieldID',which_data($row,"status",$Errormsg),'',check_disabled("2"));

            }

           ?>
          </td>
         </tr>
         <?if($row['status'] == 4 || $_REQUEST['status'] == 4)  {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("203") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            $query = dbRead("select * from spon_cats order by category");
            form_select('sponcat',$query,'category','fieldid', which_data($row,"sponcat",$Errormsg),'',check_disabled("2"));

           ?>
		  </td>
         </tr>
         <?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("24") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            //$sql_query = dbRead("select * from area where CID = '".$row['CID']."' order by place");
            $sql_query = dbRead("select tbl_area_physical.FieldID, AreaName from tbl_area_physical, tbl_area_regional, tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID = '".$row['CID']."' order by AreaName");
            form_select('area',$sql_query,'AreaName','FieldID',which_data($row,"area",$Errormsg),'',check_disabled("2"));

           ?>
          </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("25") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            $sql_query = dbRead("select * from area where CID = '".$row['CID']."' and `drop` = 'Y' order by place");
            form_select('licensee',$sql_query,'place','FieldID',which_data($row,"licensee",$Errormsg),'',check_disabled("2"));

			$sql_query2 = dbRead("select * from area where FieldID = '".$row['licensee']."' order by place");
        	$rowAgent = mysql_fetch_assoc($sql_query2);
        	if($rowAgent['display'] == 'Y') {
           ?>
          &nbsp;&nbsp;(<? if($_REQUEST[emailed]) { ?>&nbsp;&nbsp;Email Sent!<? } else { ?><a href="/general.php?SendAgent=true&memid=<?= $row[memid] ?>" class="nav">Send Agent Info</a><?}?>)
		  </td>
         </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Agent:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['tradeq']) ?></td>
		 </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Address:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['r_address']) ?></td>
		 </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Phone:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['phone']) ?></td>
		 </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Mobile:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['mobile']) ?></td>
		 </tr>
		 <?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>&nbsp;</td>
          <td bgcolor="#FFFFFF" align="right"><input type="submit" name="main" value="<?= get_page_data("12") ?>"></td>
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

function display_address_info($row, $Errormsg = false, $ErrorArray = false) {

 print_error($Errormsg);

 ?>
  <script language="javascript">
   function samepostal() {

    if(document.member_edit.SamePostal.checked == true) {

     document.member_edit.postalno.value = document.member_edit.streetno.value;
     document.member_edit.postalname.value = document.member_edit.streetname.value;
     document.member_edit.postalsuburb.value = document.member_edit.suburb.value;
     document.member_edit.postalcity.value = document.member_edit.city.value;
     document.member_edit.postalstate.value = document.member_edit.state.value;
     document.member_edit.postalpostcode.value = document.member_edit.postcode.value;

    } else {

     document.member_edit.postalno.value = '<?= addslashes($row['postalno']) ?>';
     document.member_edit.postalname.value = '<?= addslashes($row['postalname']) ?>';
     document.member_edit.postalsuburb.value = '<?= addslashes($row['postalsuburb']) ?>';
     document.member_edit.postalcity.value = '<?= addslashes($row['postalcity']) ?>';
     document.member_edit.postalstate.value = '<?= addslashes($row['postalstate']) ?>';
     document.member_edit.postalpostcode.value = '<?= addslashes($row['postalpostcode']) ?>';

    }

   }
  </script>
  <input type="hidden" name="Update" value="1">
  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="2"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] - <?= get_page_data("2") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("24") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            //$sql_query = dbRead("select * from area where CID = '".$row['CID']."' order by place");
            $sql_query = dbRead("select tbl_area_physical.FieldID, AreaName from tbl_area_physical, tbl_area_regional, tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID = '".$row['CID']."' order by AreaName");
            form_select('area',$sql_query,'AreaName','FieldID',which_data($row,"area",$Errormsg),'',check_disabled("2"));

           ?>
          </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("13") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="streetno" size="20" maxlength="40" value="<?= which_data($row,"streetno",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("14") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="streetname" size="30" maxlength="40" value="<?= which_data($row,"streetname",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("15") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="suburb" size="30" maxlength="40" value="<?= which_data($row,"suburb",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("16") ?>:</td>
          <td bgcolor="<?= change_colour("city", $ErrorArray) ?>" align="left"><input type="text" name="city" size="30" maxlength="40" value="<?= which_data($row,"city",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("17") ?>:</td>
          <td bgcolor="<?= change_colour("state", $ErrorArray) ?>" align="left"><input type="text" name="state" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>"  value="<?= which_data($row,"state",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("18") ?>:</td>
          <td bgcolor="<?= change_colour("postcode", $ErrorArray) ?>" align="left"><input type="text" name="postcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>"  maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row,"postcode",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("20") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
          <input type="checkbox" name="SamePostal" onclick="javascript:samepostal();" value="ON" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("19") ?> <?= get_word("13") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="postalno" size="20" maxlength="40" value="<?= which_data($row,"postalno",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("238") ?> / <?= get_word("19") ?> <?= get_word("14") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="postalname" size="30" maxlength="40" value="<?= which_data($row,"postalname",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("19") ?> <?= get_word("15") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="postalsuburb" size="30" maxlength="40" value="<?= which_data($row,"postalsuburb",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("19") ?> <?= get_word("16") ?>:</td>
          <td bgcolor="<?= change_colour("postalcity", $ErrorArray) ?>" align="left"><input type="text" name="postalcity" size="30" maxlength="40" value="<?= which_data($row,"postalcity",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("19") ?> <?= get_word("17") ?>:</td>
          <td bgcolor="<?= change_colour("postalstate", $ErrorArray) ?>" align="left"><input type="text" name="postalstate" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>"  value="<?= which_data($row,"postalstate",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("19") ?> <?= get_word("18") ?>:</td>
          <td bgcolor="<?= change_colour("postalpostcode", $ErrorArray) ?>" align="left"><input type="text" name="postalpostcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>"  maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row,"postalpostcode",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
  		<?//if(checkmodule("EditMemberLevel2")) { ?>
         <tr>
          <td class="<?= bg1($row) ?>"" colspan="2" align="center" nowrap><?= get_word("235") ?> <?= get_word("129") ?><br><font color ="#0000FF">NOT TO BE GIVEN TO MEMBERS</font></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("235") ?> <?= get_word("13") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="homestreetno" size="20" maxlength="40" value="<?= which_data($row,"homestreetno",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("235") ?> <?= get_word("14") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="homestreetname" size="30" maxlength="40" value="<?= which_data($row,"homestreetname",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("235") ?> <?= get_word("15") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="homesuburb" size="30" maxlength="40" value="<?= which_data($row,"homesuburb",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("235") ?> <?= get_word("16") ?>:</td>
          <td bgcolor="<?= change_colour("homecity", $ErrorArray) ?>" align="left"><input type="text" name="homecity" size="30" maxlength="40" value="<?= which_data($row,"homecity",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("235") ?> <?= get_word("17") ?>:</td>
          <td bgcolor="<?= change_colour("homestate", $ErrorArray) ?>" align="left"><input type="text" name="homestate" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>"  value="<?= which_data($row,"homestate",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("235") ?> <?= get_word("18") ?>:</td>
          <td bgcolor="<?= change_colour("homepostcode", $ErrorArray) ?>" align="left"><input type="text" name="homepostcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>"  maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row,"homepostcode",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
        <?//}?>
         <tr>
          <td class="<?= bg1($row) ?>"" colspan="2" align="center" nowrap><font color ="#0000FF">GIFT ADDRESS</font></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift <?= get_word("13") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="giftstreetno" size="20" maxlength="40" value="<?= which_data($row,"giftstreetno",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift <?= get_word("14") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="giftstreetname" size="30" maxlength="40" value="<?= which_data($row,"giftstreetname",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift <?= get_word("15") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="giftsuburb" size="30" maxlength="40" value="<?= which_data($row,"giftsuburb",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift <?= get_word("16") ?>:</td>
          <td bgcolor="<?= change_colour("giftcity", $ErrorArray) ?>" align="left"><input type="text" name="giftcity" size="30" maxlength="40" value="<?= which_data($row,"giftcity",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift <?= get_word("17") ?>:</td>
          <td bgcolor="<?= change_colour("giftstate", $ErrorArray) ?>" align="left"><input type="text" name="giftstate" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>"  value="<?= which_data($row,"giftstate",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift <?= get_word("18") ?>:</td>
          <td bgcolor="<?= change_colour("giftpostcode", $ErrorArray) ?>" align="left"><input type="text" name="giftpostcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>"  maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row,"giftpostcode",$Errormsg) ?>" <?= check_disabled("1"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>&nbsp;</td>
          <td bgcolor="#FFFFFF" align="right"><input type="submit" name="main" value="<?= get_page_data("12") ?>"></td>
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

function display_category_info($row, $Errormsg = false, $ErrorArray = false) {

 print_error($Errormsg);

 ?>
  <input type="hidden" name="Update" value="1">
  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="1" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="3"><?= get_word("66") ?>: <?= get_all_added_characters($row[companyname]) ?> [<?= $row[memid] ?>] - <?= get_page_data("3") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="left" nowrap rowspan="2" valign="top"><?= get_word("26") ?>:</td>
          <td class="<?= bg($row) ?>" align="left" nowrap><?= get_word("27") ?>:</td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="left" nowrap>English <?= get_word("27") ?>:</td>
         </tr>

         <?

          $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$_REQUEST['Client']."' order by mem_categories.FieldID");
          while($catrow = mysql_Fetch_assoc($query)) {

           ?>
            <tr>
             <td bgcolor="#FFFFFF" align="left" rowspan="2" valign="top"><a href="body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&tab=<?= $_REQUEST['tab'] ?>&CatID=<?= $catrow['FieldID'] ?>" class="nav"><?= $catrow[category] ?></a>&nbsp;&nbsp;(&nbsp;<a href="javascript:ConfirmDel('<?= $catrow['FieldID'] ?>','<?= addslashes($catrow[category]) ?>')" class="nav"><?= get_word("125") ?></a>&nbsp;)<?if($catrow['dir_state'] == 1) {?><br><br><b>State Member</b><?}?><?if($catrow['dir_nation'] == 1) {?><br><br><b>National Member</b><?}?></td>
             <td bgcolor="#FFFFFF" align="left"><?= $catrow[description] ?></td>
            </tr>
            <tr>
             <td bgcolor="#FFFFFF" align="left"><? if($_SESSION['Country']['english'] == "Y") { print "English Speaking Country."; } else { print $catrow[engdesc]; } ?></td>
            </tr>
           <?

          }

         ?>

        </table>
       </td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
  <br>

  <?

  if(checkmodule("EditMemberLevel1") || checkmodule("EditMemberLevel2")) {

  ?>

  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="1" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="2"><?= get_page_data("4") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150"><?= get_word("26") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">

           <?

            if($Errormsg) {
             $CheckCat = $_REQUEST['add_catid'];
            }

            $query = dbRead("select categories.* from categories where display_drop = 'Y' and categories.CID = '".$_SESSION['User']['CID']."' OR CID = '0' order by categories.category");
            form_select('add_catid',$query,'category','catid',$CheckCat);

           ?>

          </td>
         </tr>
         <? if (checkmodule("EditMemberLevel2")) {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">National:</td>
          <td bgcolor="#FFFFFF" align="left">
           <input type="radio" name="dir_pos" value="1" <? if($catrow['dir_nation'] == 1) {?>checked<?}?>>
           </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">Statewide:</td>
          <td bgcolor="#FFFFFF" align="left">
	    <input type="radio" name="dir_pos" value="2" <? if($catrow['dir_state'] == 1) {?>checked<?}?>>
          </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">Unselected:</td>
          <td bgcolor="#FFFFFF" align="left">
	    <input type="radio" name="dir_pos" value="3" checked>
          </td>
         </tr>
         <?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150"><?= get_word("27") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
          <textarea name="description" cols="57" rows="5"><?= which_data($row,"description",$Errormsg) ?></textarea></td>
         </tr>
         <?

         if($_SESSION['Country']['english'] == "N") {

         ?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">English <?= get_word("27") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
          <textarea name="engdescription" cols="57" rows="5"><?= which_data($row,"engdescription",$Errormsg) ?></textarea></td>
         </tr>
         <?

         }

         ?>
         <tr>
          <td bgcolor="#FFFFFF" align="right" colspan="2"><input type="submit" name="main" value="<?= ucwords(strtolower(get_page_data("4"))) ?>"></td>
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

}

function edit_category_info($row, $Errormsg = false, $ErrorArray = false,$CatID) {

 $SQL = dbRead("select * from mem_categories where FieldID = '".$CatID."'");
 $SQLRow = mysql_fetch_assoc($SQL);

 print_error($Errormsg);

 ?>
  <input type="hidden" name="UpdateCat" value="1">
  <input type="hidden" name="UpdateCatID" value="<?= $_REQUEST['CatID'] ?>">
  <input type="hidden" name="OldCatID" value="<?= $SQLRow['category'] ?>">
  <input type="hidden" name="olddescription" value="<?= $SQLRow['description'] ?>">
  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="1" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="3"><?= get_word("66") ?>: <?= get_all_added_characters($row[companyname]) ?> [<?= $row[memid] ?>] - <?= get_page_data("3") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="left" nowrap rowspan="2" valign="top"><?= get_word("26") ?>:</td>
          <td class="<?= bg($row) ?>" align="left" nowrap><?= get_word("27") ?>:</td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="left" nowrap>English <?= get_word("27") ?>:</td>
         </tr>

         <?

          $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$_REQUEST['Client']."' order by mem_categories.FieldID");
          while($catrow = mysql_Fetch_assoc($query)) {

           ?>
            <tr>
             <td bgcolor="#FFFFFF" align="left" rowspan="2" valign="top"><a href="body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&tab=<?= $_REQUEST['tab'] ?>&CatID=<?= $catrow['FieldID'] ?>" class="nav"><?= $catrow[category] ?></a>&nbsp;&nbsp;(&nbsp;<a href="javascript:ConfirmDel('<?= $catrow['FieldID'] ?>','<?= addslashes($catrow[category]) ?>')" class="nav"><?= get_word("125") ?></a>&nbsp;)</td>
             <td bgcolor="#FFFFFF" align="left"><?= $catrow[description] ?></td>
            </tr>
            <tr>
             <td bgcolor="#FFFFFF" align="left"><? if($_SESSION['Country']['english'] == "Y") { print "English Speaking Country."; } else { print $catrow[engdesc]; } ?></td>
            </tr>
           <?

          }

         ?>

        </table>
       </td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
  <br>
  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="1" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="2"><?= get_page_data("4") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150"><?= get_word("26") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">

           <?

            $query = dbRead("select categories.* from categories where (categories.CID = '".$_SESSION['User']['CID']."' and display_drop = 'Y') OR CID = '0' order by categories.category");
            form_select('add_catid',$query,'category','catid',$SQLRow['category']);

           ?>

          </td>
         </tr>
         <? if (checkmodule("EditMemberLevel2")) {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">National:</td>
          <td bgcolor="#FFFFFF" align="left">
           <input type="radio" name="dir_pos" value="1" <? if($SQLRow['dir_nation'] == 1) {?>checked<?}?>>
           </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">Statewide:</td>
          <td bgcolor="#FFFFFF" align="left">
	    <input type="radio" name="dir_pos" value="2" <? if($SQLRow['dir_state'] == 1) {?>checked<?}?>>
          </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">Unselected:</td>
          <td bgcolor="#FFFFFF" align="left">
	    <input type="radio" name="dir_pos" value="3" <? if($SQLRow['dir_state'] == 9 && $SQLRow['dir_nation'] == 9) {?>checked<?}?>>
          </td>
         </tr>
         <?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150"><?= get_word("27") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
          <textarea name="description" cols="57" rows="5"><?= get_all_added_characters($SQLRow['description']) ?></textarea></td>
         </tr>
         <?

         if($_SESSION['Country']['english'] == "N") {

         ?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">English <?= get_word("27") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
          <textarea name="engdescription" cols="57" rows="5"><?= get_all_added_characters($SQLRow['engdesc']) ?></textarea></td>
         </tr>
         <?

         }

		if(checkmodule("EditMemberLevel2")) {
         ?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" width="150">Ad Upload:</td>
          <td bgcolor="#FFFFFF" align="left">
          <font face="Verdana" size="2" color="#000000">&nbsp;Select file:<input size="25" type="file" name="picture" style="font-family: Verdana"> (max 2mb)</font></td>
         </tr>

       <?}?>
         <tr>
          <td bgcolor="#FFFFFF" align="right" colspan="2"><input type="submit" name="main" value="<?= get_page_data("19") ?>"></td>
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

function display_misc_info($row, $Errormsg = false, $ErrorArray = false) {

 global $Admin, $Country;

 $DisplayArray = array('companyname' => 'Trading As', 'regname' => 'Reg Name');
 $DisplayArray2 = array(0 => 'No', 1 => '50%', 2 => '75%');
 $DisplayArray3 = array(0 => 'None', 1 => 'Red', 2 => 'White', 3 => 'Tablet', 4 => 'Mixed');
 //$DisplayArray3 = array(0 => 'None', 4 => 'Mixed');
 $FeeschargeArray = array('Buy' => 'Buy', 'Sell' => 'Sell');
 $GoldCard = array('1' => 'Yes', '0' => 'No');
 $GoldCard2 = array('1' => 'Yes', '0' => 'No', '2' => 'Monthly');
 $Stars = array('0' => 'No Stars','1' => '1 Star', '2' => '2 Stars', '3' => '3 Stars', '4' => '4 Stars', '5' => '5 Stars');
 $Priority = array('0' => 'Select', '2' => 'Fortnightly', '4' => 'Monthly', '8' => '2 Monthly', '12' => 'Quarterly', '26' => 'Half Yearly');
 $cont = array('Y' => 'Yes', 'N' => 'No');

 print_error($Errormsg);

 ?>
  <input type="hidden" name="Update" value="1">
  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="2"><?= get_word("66") ?>: <?= get_all_added_characters($row[companyname]) ?> [<?= $row[memid] ?>] - <?= get_page_data("5") ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Priority:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('priority',$Priority,'','', which_data($row,"priority",$Errormsg)); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Star Rating:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('star',$Stars,'','', which_data($row,"star",$Errormsg)); ?></td>
         </tr>
<?if($_SESSION['User']['CID'] == 1) {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Acceptance Letter Sent:</td>
          <td bgcolor="#FFFFFF" align="left">
          <input type="checkbox" name="sent" value="1" <?if($row['accept'] == 1 || $row['accept'] == 2) {?>Checked<?}?> <?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Acceptance Letter Received:</td>
          <td bgcolor="#FFFFFF" align="left">
          <input type="checkbox" name="received" value="1" <?if($row['accept'] == 2) {?>Checked<?}?> <?= check_disabled("2"); ?>></td>
         </tr>
<?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Latest Trade %:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="trade_per" size="6"  maxlength="6" value="<?= which_data($row,"trade_per",$Errormsg) ?>"> as a <?= $row['date_per'] ?> </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("29") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="abn" size="<?= $_SESSION['CountryPref_Members']['abn_limit']?>"  maxlength="<?= $_SESSION['CountryPref_Members']['abn_limit']?>" value="<?= which_data($row,"abn",$Errormsg) ?>" <? if ($_SESSION['CountryPref_Members']['abn_no']) {?><?= check_disabled("2"); ?><?}?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("30") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_radio_yesno('gst', which_data($row,"gst",$Errormsg),check_disabled("2")) ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("31") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="wagesacc" size="10" value="<?= which_data($row,"wagesacc",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("32") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="memusername" size="10" maxlength="20" maxlength="20" value="<?= which_data($row,"memusername",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("33") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><? if(($row['status'] == 3) && (!checkmodule("Staff")) || !checkmodule("EditMemberLevel2")) {?>*********<?} else {?><input type="text" name="mempassword" size="10" maxlength="20" maxlength="20" value="<?= which_data($row,"mempassword",$Errormsg) ?>"<?= check_disabled("2"); ?>><?}?><? if($row['emailAddress_3']) { ?></a><? if($_REQUEST[emailed]) { ?>&nbsp;&nbsp;Email
          Sent!<? } else { ?><? if(!(($row[status] == 3) && (!checkmodule("SuperUser")) && ($_REQUEST[emailed] == true))) { ?>&nbsp;&nbsp;(<a href="/general.php?SendLoginInfo=true&memid=<?= $row[memid] ?>" class="nav">Send Username & Password</a>)<? } ?><? } ?><? } ?></td>
         </tr>
         <?if($_SESSION[Country][countryID] == 12)  {?>
         <tr>
           <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("208") ?>:</b></td>
           <td bgcolor="FFFFFF"><input type="text" name="sms" size="20" maxlength="50" value="<?= which_data($row, "sms", $ErrorMsg) ?>" <?= check_disabled("1"); ?> ></td>
         </tr>
         <?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("11") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="homephonearea" size="5" maxlength="15" value="<?= which_data($row,"homephonearea",$Errormsg) ?>" onKeyPress="return number(event)" <?= check_disabled("1"); ?>>&nbsp;<input type="text" name="homephone" size="20" maxlength="15" value="<?= which_data($row,"homephone",$Errormsg) ?>" onKeyPress="return phonenumber(event)" <?= check_disabled("1"); ?>></td>
         </tr>
         <?if($_SESSION[Country][countryID] == 2) {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Membership Card No:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="Card_No" size="16" maxlength="16" value="<?= which_data($row,"Card_No",$Errormsg) ?>" onKeyPress="return number(event)" <?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Membership Card Expire:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="Card_Exp" size="5" maxlength="5" value="<?= which_data($row,"Card_Exp",$Errormsg) ?>" onKeyPress="return number2(event)" <?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Terminal ID:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="Terminal_No" size="10" maxlength="16" value="<?= which_data($row,"Terminal_No",$Errormsg) ?>" onKeyPress="return number(event)" <?= check_disabled("2"); ?>></td>
         </tr>
         <?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("34") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

		    $query = dbRead("select FieldID, Name from tbl_admin_users where CID like '".$_SESSION['User']['CID']."' and SalesPerson = '1' order by Name ASC");
            //$query = dbRead("select salespeople.* from salespeople where salespeople.CID = '".$_SESSION['User']['CID']."' order by salespeople.name");
            form_select('salesmanid',$query,'Name','FieldID', which_data($row,"salesmanid",$Errormsg),'No Sales Person',check_disabled("2"));

           ?>
		  </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("59") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
          <?

            $query = dbRead("select * from tbl_admin_payment_types where startup = '1' order by Type");
            form_select('memshipfeepaytype',$query,'Type','FieldID',which_data($row,"memshipfeepaytype",$Errormsg),'Select One',check_disabled("2"));

          ?>
          </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("60") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="membershipfeepaid" size="10" maxlength="6" value="<?= which_data($row,"membershipfeepaid",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Trade % in above <?= get_word("60") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="trade_membership" size="10" maxlength="6" value="<?= which_data($row,"trade_membership",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Agent <?= get_word("73") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= $row['paid'] ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Member <?= get_word("73") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= $row['paid_mem'] ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("74") ?>:</td>
          <td bgcolor="<?= change_colour("banked", $ErrorArray) ?>" align="left"><?= do_date("banked",which_data($row,"banked",$ErrorMsg),'',check_disabled("2")); ?></td>

         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("69") ?>:</td>
          <td bgcolor="<?= change_colour("datejoined", $ErrorArray) ?>" align="left"><?= do_date("datejoined",which_data($row,"datejoined",$ErrorMsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("70") ?>:</td>
          <td bgcolor="<?= change_colour("datepacksent", $ErrorArray) ?>" align="left"><?= do_date("datepacksent",which_data($row,"datepacksent",$ErrorMsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("71") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= GetUser(which_data($row,"lastedit",$Errormsg)) ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("72") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><? if($row['lastlog']) { print date("jS F Y", $row['lastlog']); } else { print "Never"; } ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("67") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('displayname',$DisplayArray,'','', which_data($row,"displayname",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("68") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('feescharge',$FeeschargeArray,'','', which_data($row,"feescharge",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("35") ?>:</td>
          <td bgcolor="<?= change_colour("transfeecash", $ErrorArray) ?>" align="left"><input type="text" name="transfeecash" size="10" maxlength="6" value="<?= which_data($row,"transfeecash",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("36") ?>:</td>
          <td bgcolor="<?= change_colour("monthlyfeecash", $ErrorArray) ?>" align="left"><input type="text" name="monthlyfeecash" size="10" maxlength="9" value="<?= which_data($row,"monthlyfeecash",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Cheque Number:</td>
          <td bgcolor="<?= change_colour("cheque_no", $ErrorArray) ?>" align="left"><input type="text" name="cheque_no" size="10" maxlength="9" value="<?= which_data($row,"cheque_no",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
<?if($_SESSION['User']['CID'] == 1) {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("37") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('goldcard',$GoldCard,'','', which_data($row,"goldcard",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
<?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>RE Spenddown:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('respenddown',$DisplayArray2,'','', which_data($row,"respenddown",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Admin Fee Exemption:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('admin_exempt',$GoldCard2,'','', which_data($row,"admin_exempt",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>ITT Exempt:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('itt_exempt',$GoldCard,'','', which_data($row,"itt_exempt",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Interest Exempt:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('interest',$GoldCard,'','', which_data($row,"interest",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Uncontactable:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('uncon',$cont,'','', which_data($row,"uncon",$Errormsg),'',check_disabled("1")); ?></td>
         </tr>
<?if($_SESSION['User']['CID'] == 1) {?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Robert Deal:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('rob',$GoldCard,'','', which_data($row,"rob",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('gift',$DisplayArray3,'','', which_data($row,"gift",$Errormsg),'',check_disabled("1")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift Sent:</td>
          <td bgcolor="#FFFFFF" align="left"><?= form_select('gift_rec',$GoldCard,'','', which_data($row,"gift_rec",$Errormsg),'',check_disabled("2")); ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>Gift Method:</td>
          <td bgcolor="<?= change_colour("gift_type", $ErrorArray) ?>" align="left"><input type="text" name="gift_type" size="20" maxlength="20" value="<?= which_data($row,"gift_type",$Errormsg) ?>"<?= check_disabled("2"); ?> ></td>
         </tr>
<?}?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>&nbsp;</td>
          <td bgcolor="#FFFFFF" align="right"><input type="submit" name="main" value="<?= get_page_data("12") ?>"></td>
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

function display_printview($row) {


 $query3 = dbRead("select memid from notes where memid = ".$row['memid']." group by memid ASC");
 while($row3 = mysql_fetch_assoc($query3)) {
  $notesarray[$row3[memid]] = 1;
 }

 if($notesarray[$memid]) {

  $notestext = "Notes Have Been Entered&nbsp;&nbsp;&nbsp;";

 } else {

  $notestext = "Notes Not Entered&nbsp;&nbsp;&nbsp;";

 }

//$dbquery2 = dbRead("select place from area where FieldID='".$row['area']."' and CID like '".$_SESSION['User']['CID']."'");
$dbquery2 = dbRead("select AreaName as place from tbl_area_physical where FieldID='".$row['area']."' and CID like '".$_SESSION['User']['CID']."'");
$row2 = mysql_fetch_assoc($dbquery2);

$dbquery2 = dbRead("select place from area where FieldID='".$row['licensee']."' and CID like '".$_SESSION['User']['CID']."'");
$row3 = mysql_fetch_assoc($dbquery2);

$query4 = dbRead("select * from tbl_admin_payment_types where FieldID = '$row[memshipfeepaytype]'");
$row4 = mysql_fetch_array($query4);

$query5 = dbRead("select * from tbl_admin_payment_types where FieldID = '$row[paymenttype]'");
$row5 = mysql_fetch_array($query5);

$querys = dbRead("select * from status where FieldID = '$row[status]'");
$rows = mysql_fetch_array($querys);

if($row[opt] == "Y") {
 $row[opt] = "Yes";
} else {
 $row[opt] = "No";
}

if($row[reopt] == "Y") {
 $row[reopt] = "Yes";
} else {
 $row[reopt] = "No";
}

if($row[bdriven] == "Y") {
 $row[bdriven] = "Yes";
} else {
 $row[bdriven] = "No";
}

if($row[gst] == "Y") {
 $row[gst] = "Yes";
} else {
 $row[gst] = "No";
}

if($row[priority] == 1) {
  $prior = 'Weekly';
} elseif($row[priority] == 2) {
  $prior = 'Fortnightly';
} elseif($row[priority] == 4) {
  $prior = 'Monthly';
} elseif($row[priority] == 8) {
  $prior = '2 Monthly';
} elseif($row[priority] == 12) {
  $prior = 'Quarterly';
} elseif($row[priority] == 26) {
  $prior = 'Half Yearly';
}

if($_REQUEST['ViewLoginInfo']) {
    $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['Client']."','".$curdate."','".$_SESSION['User']['FieldID']."','1','Password Retrieved')");
}

?>
<html>

<head>
<script>
function notes(URL) {
  var exitwin="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=400";
  remotecontrol=window.open(URL, "notes", exitwin);
  remotecontrol.focus();
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="GENERATOR" content="Microsoft FrontPage 6.0">
<title>Change Member</title>

<style type="text/css">
	   a { color: #000000; text-decoration: none; }
	   a:hover { color: #0000FF; text-decoration: none; }
	   #nobg { background: none; }
</style>

</head>
<a href="javascript:print()" class="nav"><img border="0" src="images/icon_printable.gif"></a>
<table border="0" cellspacing="0" cellpadding="1" width="610">
<tr>
<td class="Border">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2" align="center" class="<?= bg1($row) ?>""><b><?= get_word("66") ?>: <?= get_all_added_characters($row[companyname]) ?> [<?= $row[memid] ?>] - <?= get_page_data("6") ?></b></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("91") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>Priority:</b></td>
    <td bgcolor="#FFFFFF"><?= $prior ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>Star Rating:</b></td>
    <td bgcolor="#FFFFFF">
    <?
    $count = 0;
    while($count < $row['star']) {
    ?>
    <img border="0" src="images/paw.jpg">
    <?

    $count++;
    }

    ?>
  </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("49") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[pin] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" width="30%" class="<?= bg($row) ?>"><b><?= get_word("50") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[memid] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("2") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[regname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("3") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[companyname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("4") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[accholder]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("4") ?> Firstname:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[accholder_first]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("4") ?> Surname:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[accholder_surname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("5") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[contactname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("6") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[signatories]) ?></td>
  </tr>

  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("7") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[phonearea]) ?> <?= get_all_added_characters($row[phoneno]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("8") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[faxarea]) ?> <?= get_all_added_characters($row[faxno]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("10") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[mobile]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("224") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if($row['emailAddress_3']) { ?><a href="mailto:<?= $row['emailAddress_3'] ?>" class="nav"><? } ?><?= $row['emailAddress_3'] ?><? if($row['emailAddress_3']) { ?></a><? } ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("222") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if($row['emailAddress_1']) { ?><a href="mailto:<?= $row['emailAddress_1'] ?>" class="nav"><? } ?><?= $row['emailAddress_1'] ?><? if($row['emailAddress_1']) { ?></a><? } ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("223") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if($row['emailAddress_2']) { ?><a href="mailto:<?= $row['emailAddress_2'] ?>" class="nav"><? } ?><?= $row['emailAddress_2'] ?><? if($row['emailAddress_2']) { ?></a><? } ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("225") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if($row['emailAddress_4']) { ?><a href="mailto:<?= $row['emailAddress_4'] ?>" class="nav"><? } ?><?= $row['emailAddress_4'] ?><? if($row['emailAddress_4']) { ?></a><? } ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("21") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[opt]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("22") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[reopt]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("28") ?>:</b></td>
    <td bgcolor="#FFFFFF"><a class="nav" href="http://<?= $row[webpageurl] ?>" target="_blank"><?= $row[webpageurl] ?></a></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("12") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($rows[Name]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b></b></td>
    <td bgcolor="#FFFFFF"><? if($_REQUEST[emailed2]) { ?>&nbsp;&nbsp;Email Sent!<? } else { ?><a href="/general.php?SendConfirm=true&memid=<?= $row[memid] ?>" class="nav"><b>Send Confirm Details</b></a><?}?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("92") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("13") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[streetno]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("14") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[streetname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("15") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[suburb]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("16") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[city]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("17") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[state]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("18") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postcode]) ?></td>
  </tr>
  <tr>
    <td align="center" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("93") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("19") ?> <?= get_word("13") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postalno]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("19") ?> <?= get_word("14") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postalname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("19") ?> <?= get_word("15") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postalsuburb]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("19") ?> <?= get_word("16") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postalcity]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("19") ?> <?= get_word("17") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postalstate]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("19") ?> <?= get_word("18") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[postalpostcode]) ?></td>
  </tr>
  <tr>
    <td align="center" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("94") ?></b></td>
  </tr>
  <?if(check_area_access($row)) {
		#get last edit by
		$dbgetsp = dbRead("select FieldID, Name from tbl_admin_users where FieldID='".$row['lastedit']."'");

		$nam = mysql_fetch_assoc($dbgetsp);
?>
  <tr>
    <td align="right" valign="middle" width="30%" class="<?= bg($row) ?>"><b><?= get_word("71") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($nam['Name']) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("29") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[abn]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("30") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[gst]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_page_data("13") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[oldcompanyname]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("11") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[homephonearea]) ?> <?= get_all_added_characters($row[homephone]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("69") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= date("l dS \of F Y", strtotime($row[datejoined])); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("70") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= date("l dS \of F Y", strtotime($row[datepacksent])); ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("32") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[memusername]) ?></td>
  </tr>
	 <tr>
	  <td class="<?= bg($row) ?>" align="right" nowrap><?= get_word("33") ?>:</td>
	  <td bgcolor="#FFFFFF" align="left"><? if(($row['status'] == 3 && !checkmodule("Staff")) || !checkmodule("EditMemberLevel2")) {?><?if($_SESSION['User']['Area'] == 1 && $row['status'] != 3 && $row['status'] != 1) {?><?if($_REQUEST['ViewLoginInfo']) {?> <?= $row['mempassword']?> <?} else {?>&nbsp;&nbsp;<a href="/body.php?page=member_edit&ViewLoginInfo=true&Client=<?= $row[memid] ?>&tab=tab5" class="nav"><b>Click for Password</b></a><?}?> <?} else {?>*********<?}?><?} else {?><?= $row['mempassword']?><?}?><? if($row['emailAddress_3']) { ?></a><? if($_REQUEST[emailed]) { ?>&nbsp;&nbsp;Email
	  Sent!<? } else { ?><? if(!(($row[status] == 3) && (!checkmodule("SuperUser")) && ($_REQUEST[emailed] == true))) { ?><?if($row[status] != 1) {?>&nbsp;&nbsp;(<a href="/general.php?SendLoginInfo=true&memid=<?= $row[memid] ?>" class="nav"><b>Send Username & Password</b></a>)<? } ?><? } ?><? } ?><? } ?></td>
	 </tr>
  <?}?>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("24") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row2[place] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("25") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row3[place] ?>
		  <?
		  $sql_query2 = dbRead("select * from area where FieldID = '".$row['licensee']."' order by place");
          $rowAgent = mysql_fetch_assoc($sql_query2);
          if($rowAgent['display'] == 'Y') {
          ?>
		  &nbsp;&nbsp;(<? if($_REQUEST[emailed]) { ?>&nbsp;&nbsp;Email Sent!<? } else { ?><a href="/general.php?SendAgent=true&memid=<?= $row[memid] ?>" class="nav"><b>Send Agent Info</b></a><?}?>)
		  </td>
         </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Agent:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['tradeq']) ?></td>
		 </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Address:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['r_address']) ?></td>
		 </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Phone:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['phone']) ?></td>
		 </tr>
         <tr>
		  <td class="<?= bg($row) ?>" align="right" nowrap>Mobile:</td>
		  <td bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($rowAgent['mobile']) ?></td>
		 </tr>
		 <?}?>
    </td>
  </tr>

  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("26") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>Last Trade %:</b></td>
    <td bgcolor="#FFFFFF"><?= $row['trade_per'] ?> as at <?= $row['date_per'] ?></td>
  </tr>
  <?
          $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$_REQUEST['Client']."' order by mem_categories.FieldID");
          while($catrow = mysql_Fetch_assoc($query)) {

           ?>
            <tr>
             <td class="<?= bg($row) ?>" align="right" rowspan="2" valign="top"><?= $catrow[category] ?></td>
             <td bgcolor="#FFFFFF" align="left"><?= $catrow[description] ?><?if($catrow['dir_state'] == 1) {?><b> - State Member</b><?}?><?if($catrow['dir_nation'] == 1) {?><b> - National Member</b><?}?></td>
            </tr>
            <tr>
             <td bgcolor="#FFFFFF" align="left"><? print $catrow[engdesc]; ?><br></td>
            </tr>
           <?

          }
  ?>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("23") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[bdriven] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("199") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($row[t_unlist]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>
  <?
          $querys = dbRead("select sum(cont) as cont, sum(rest_acco) as rest_acco, sum(rest_supp) as rest_supp, sum(tourist) as tourist, sum(gene_busi) as gene_busi, Sum(wed) as wed from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$_REQUEST['Client']."' group by mem_categories.memid");
          $catrows = mysql_Fetch_assoc($querys);
   ?>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("164") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($catrows[cont]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("165") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($catrows[rest_acco]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("166") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($catrows[rest_supp]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("167") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($catrows[tourist]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("168") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($catrows[gene_busi]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("204") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if ($catrows[wed]) {?>Yes <?} else { ?>No<?}?></td>
  </tr>

  <?if(check_area_access($row)) {?>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("95") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("34") ?>:</b></td>
    <td bgcolor="#FFFFFF">
<?

		#get current sales person
		$dbgetsp = dbRead("select FieldID, Name from tbl_admin_users where FieldID='".$row['salesmanid']."'");

		while(list($FieldID, $Name)=@mysql_fetch_row($dbgetsp)) {
			print "$Name";
		}


?>
	    </td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("60") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[membershipfeepaid] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>Trade % in above <?= get_word("60") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[membershipfeepaid] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("59") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row4[Type]) ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("96") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("58") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row5[Type]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("53") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[overdraft]) ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("54") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[reoverdraft]) ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("97") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("43") ?>/<?= get_word("44") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[feescharge] ?></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>%:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[transfeecash] ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("98") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("61") ?>:</b></td>
    <td bgcolor="#FFFFFF"><?= $row[monthlyfeecash] ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_word("65") ?></b></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("50") ?>:</b></td>
    <td bgcolor="#FFFFFF"><? if($row[referedby]) { ?><a href="/body.php?page=viewmember&memid=<?= $row[referedby] ?>" class="nav"><? } ?><?= $row[referedby] ?><? if($row[referedby]) { ?></a><? } ?></td>
  </tr>
  <?if($row[referedby]) {?>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>Referrer Paid:</b></td>
    <td bgcolor="#FFFFFF"><?= get_all_added_characters($row[paid_mem]) ?></td>
  </tr>
  <?}?>
  <?}?>
<?if($_SESSION['Country']['erewards'] == "Y" || $_SESSION['Country']['alltrades'] == "Y")  {?>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b><?= get_page_data("20") ?></b></td>
  </tr>
<?}?>
<?if($_SESSION['Country']['alltrades'] == "Y")  {?>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("37") ?>?</b></td>
    <td bgcolor="#FFFFFF">
<?

	if($row[goldcard] == "0") {
		print"No";
	} else {
		print"Yes";
	}

?>
	</td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b>Handy Categories / Previous Searches</b></td>
  </tr>
<tr>
<td colspan="2">
<?
$queryCat = dbRead("SELECT * FROM mem_categories where category != 0 and memid = ".$_REQUEST['Client']."");
$count = 1;
while($rowCat = mysql_fetch_array($queryCat)) {
  if($count > 1) {
   $listCat.= ",".$rowCat['category'];
  } else {
  $listCat.= $rowCat['category'];
  }
  $count++;
}

if(!$listCat) {
 $listCat = 0;
}

 $queryM = dbRead("SELECT tbl_area_physical.* FROM members, tbl_area_physical where members.area = tbl_area_physical.FieldID and members.memid = ".$row['memid']."");
 $rowM = mysql_fetch_assoc($queryM);
//if($listCat) {
 //$queryC = dbRead("SELECT categories.*, count(mem_categories.memid) as mCount FROM categories, tbl_cat_providers, mem_categories where tbl_cat_providers.providerID = categories.catid and tbl_cat_providers.providerID = mem_categories.category and tbl_cat_providers.catID in (".$listCat.") group by tbl_cat_providers.providerID order by categories.category");
 $queryC = dbRead("SELECT categories.*, count(mem_categories.memid) as mCount FROM categories, tbl_cat_providers, mem_categories, members, tbl_area_physical where tbl_cat_providers.providerID = categories.catid and tbl_cat_providers.providerID = mem_categories.category and mem_categories.memid = members.memid and members.area = tbl_area_physical.FieldID and tbl_area_physical.RegionalID = ".$rowM['RegionalID']." and tbl_cat_providers.catID in (".$listCat.") and bdriven='N' and t_unlist = 0 and status != 6 group by tbl_cat_providers.providerID order by categories.category");
 $rowCount = mysql_num_rows($queryC);

 while($rowC = mysql_fetch_assoc($queryC)) {

  $data_structure[] = $rowC[category];
  $cat_count[$rowC[category]] = $rowC[mCount];
  $data_structure_id[] = $rowC[catid];

 }
//}

 $querySearch = dbRead("SELECT categories.* FROM tbl_members_searches, categories where (tbl_members_searches.category = categories.catid) and memid = ".$_REQUEST['Client']." and tbl_members_searches.category != 0 order by date desc limit 5");
 $rowSearch = mysql_num_rows($querySearch);

 while($rowS = mysql_fetch_assoc($querySearch)) {

  $data_structure[] = $rowS[category];
  $cat_count[$rowS[category]] = "Mem";
  $data_structure_id[] = $rowS[catid];

 }

$Category_Count = sizeof($data_structure);
$Category_Count_Half = ceil($Category_Count/4);

?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#EEEEEE">
 <?
  $Counter = 0;
  for($i = 0;$i < $Category_Count_Half;$i++) {

   $cfg_bgcolor_one = "#DDDDDD";
   $cfg_bgcolor_two = "#EEEEEE";

   $bgcolor = $cfg_bgcolor_one;

   $Counter % 2 ? 0: $bgcolor = $cfg_bgcolor_two;

  ?>
  <tr valign="top" bgcolor="<?= $bgcolor ?>">
   <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a catnav" target="_blank" href="body.php?page=mem_search&memsearch=1&catid=<?= $data_structure_id[$i] ?>&disareaid=<?= $rowM['RegionalID'] ?>"><?= $data_structure[$i] ?>&nbsp;<font color="#333333">(<?= $cat_count[$data_structure[$i]] ?>)</font></a><br></font></td>
   <td><? if($data_structure[$i+$Category_Count_Half]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a catnav" target="_blank" href="body.php?page=mem_search&memsearch=1&catid=<?= $data_structure_id[$i+$Category_Count_Half] ?>&disareaid=<?= $rowM['RegionalID'] ?>"><?= $data_structure[$i+$Category_Count_Half] ?>&nbsp;<font color="#333333">(<?= $cat_count[$data_structure[$i+($Category_Count_Half)]] ?>)</font></a></font><? } else { ?> &nbsp;<? } ?></td>
   <td><? if($data_structure[$i+($Category_Count_Half*2)]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a catnav" target="_blank" href="body.php?page=mem_search&memsearch=1&catid=<?= $data_structure_id[$i+($Category_Count_Half*2)] ?>&disareaid=<?= $rowM['RegionalID'] ?>"><?= $data_structure[$i+($Category_Count_Half*2)] ?>&nbsp;<font color="#333333">(<?= $cat_count[$data_structure[$i+($Category_Count_Half*2)]] ?>)</font></a></font><? } else { ?> &nbsp;<? } ?></td>
   <td><? if($data_structure[$i+($Category_Count_Half*3)]) { ?><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a catnav" target="_blank" href="body.php?page=mem_search&memsearch=1&catid=<?= $data_structure_id[$i+($Category_Count_Half*3)] ?>&disareaid=<?= $rowM['RegionalID'] ?>"><?= $data_structure[$i+($Category_Count_Half*3)] ?>&nbsp;<font color="#333333">(<?= $cat_count[$data_structure[$i+($Category_Count_Half*3)]] ?>)</font></a></font><? } else { ?> &nbsp;<? } ?></td>
  </tr>
  <?
  $Counter++;
  }
 ?>
</table>
</td>
</tr>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b>Previous Adverts</b></td>
  </tr>
  <?
	  $advquery = dbRead("select tbl_jobs_data.*, subject from tbl_jobs_data, tbl_jobs where (tbl_jobs_data.jobID = tbl_jobs.FieldID) and memid = ".$row['memid']." order by FieldID Desc","etxint_email_system");
	  while($advrow = mysql_Fetch_assoc($advquery)) {
  ?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF" align="left"><a target="_blank" href="body.php?page=advert&id=<?= $advrow['FieldID'] ?>"><?= $advrow['subject'] ?></a>
	</td>
  </tr>
  <?
      }
  ?>
  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b>Advert Details</b></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><b><?= get_all_added_characters($row[companyname]) ?></b><br></td>
  </tr>
  <?
          $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$_REQUEST['Client']."' and mem_categories.category > 0 order by mem_categories.FieldID");
          while($catrow = mysql_Fetch_assoc($query)) {

           ?>
            <tr>
             <td colspan="2" bgcolor="#FFFFFF" align="left"><?= get_all_added_characters($catrow[description]) ?><?if($catrow['dir_state'] == 1) {?><b> - State Member</b><?}?><?if($catrow['dir_nation'] == 1) {?><b> - National Member</b><?}?></td>
            </tr>
           <?

          }
  ?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><br>Currently Accepting: <?= $row[trade_per] ?>% Empire Trade</td>
  </tr>
  <?
  if($row[fiftyclub] == 2) {
 //$cc = "100% Trade for Gold Club Members<br>50% Trade for 50% Club Members and ".$row[trade_per]."% non Club Members";
   $cc = "100% Trade for Gold Club Members<br>50% Trade for non Club Members";
  } elseif($row[fiftyclub] == 1) {
   $cc = "50% Trade for all Club Members and ".$row[trade_per]."% non Club Members";
  } else {
   $cc = "";
  }

  if($cc) {?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><?= $cc ?></td>
  </tr>
  <?}?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><br>Contact: <?= get_all_added_characters($row[contactname]) ?> on <?= get_all_added_characters($row[phonearea]) ?> <?= get_all_added_characters($row[phoneno]) ?></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">Address: <?= get_all_added_characters($row[streetno]) ?> <?= get_all_added_characters($row[streetname]) ?>, <?= get_all_added_characters($row[suburb]) ?> <?= get_all_added_characters($row[city]) ?> <?= get_all_added_characters($row[state]) ?> <?= get_all_added_characters($row[postcode]) ?></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">Email: <? if($row['emailAddress_1']) { ?><a href="mailto:<?= $row['emailAddress_1'] ?>" class="nav"><? } ?><?= $row['emailAddress_1'] ?><? if($row['emailAddress_1']) { ?></a><? } ?></td>
  </tr>
  <?if($row[webpageurl]) {?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">Website: <?= $row[webpageurl] ?></td>
  </tr>
  <?}?>

<?
if($ff) {
?>

  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b>All Trades?</b></td>
    <td bgcolor="#FFFFFF">
<?

	if($row[alltrades] == "0") {
		print"No";
	} else {
		print"Yes";
	}

?>
	</td>
  </tr>
<?}}?>

<?if($_SESSION['Country']['erewards'] == "Y")  {?>
  <tr>
    <td align="right" valign="middle" class="<?= bg($row) ?>"><b><?= get_word("90") ?>?</b></td>
    <td bgcolor="#FFFFFF">
<?

if($row[erewards] == 0) {
 print "No";
} elseif($row[erewards] == 1) {
 print "Pending";
} elseif($row[erewards] == 2) {
 print "Pending";
} elseif($row[erewards] == 9) {
 print "Yes";
}

?>
	</td>
  </tr>
<?}?>
</table>
</td>
</tr>
</table>
<?

}

function display_payment_info($row, $Errormsg = false, $ErrorArray = false) {

 global $Admin, $Country;

 print_error($Errormsg);

 ?>
  <input type="hidden" name="Update" value="1">
  <table width="620" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td width="50%" valign="top">
     <table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">
      <tr>
       <td width="100%" class="Border">
        <table width="100%" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
         <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="2"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] - <?= get_page_data("7") ?></td>
         </tr>
         <?if($row['CID'] == 1) {?>
	         <tr>
	          <td class="<?= bg1($row) ?>"" align="center" colspan="2">BPAY Details</td>
	         </tr>
	         <tr>
	          <td class="<?= bg($row) ?>" align="right" nowrap width="150">BPAY Biller Code:</td>
	          <td bgcolor="#FFFFFF" align="left">374215</td>
	         </tr>
	         <tr>
	          <td class="<?= bg($row) ?>" align="right" nowrap width="150">BPAY Customer Ref:</td>
	          <td bgcolor="#FFFFFF" align="left"><?= bpay_code($row['memid']) ?></td>
	         </tr>
         <?}?>
          <tr>
          <td class="<?= bg1($row) ?>"" align="center" colspan="2">Payment Details</td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("58") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            $query = dbRead("select tbl_admin_payment_types.* from tbl_admin_payment_types where ongoing = '1' order by tbl_admin_payment_types.Type");
            form_select('paymenttype',$query,'Type','FieldID',which_data($row,"paymenttype",$Errormsg),'None',check_disabled("2"));

           ?>
		  </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("62") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="accountname" size="30" maxlength="32" value="<?= which_data($row,"accountname",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("63") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><? if(checkmodule("EditMemberLevel2")) { ?><input type="text" name="accountno" size="20" maxlength="30" value="<?= which_data($row,"accountno",$Errormsg) ?>"<?= check_disabled("2"); ?>onKeyPress="return number2(event)"><? } else { print substr($row[accountno], strlen($row[accountno])-4, 4); } ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("64") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="expires" size="5" maxlength="5" value="<?= which_data($row,"expires",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number2(event)"></td>
         </tr>
         <?

         if($_SESSION['Country']['erewards'] == "Y") {

         ?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("90") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= erewards_text($row) ?></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("100") ?>:</td>
          <td bgcolor="#FFFFFF" align="left">
           <?

            $query = dbRead("select tbl_admin_supplier_abn.* from tbl_admin_supplier_abn order by tbl_admin_supplier_abn.Type");
            form_select('supply_statement',$query,'Type','FieldID',which_data($row,"supply_statement",$Errormsg),'',check_disabled("2"));

           ?>
		  </td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("99") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="reward_bsb" size="6" maxlength="6" value="<?= which_data($row,"reward_bsb",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("63") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="reward_accno" size="10" maxlength="9" value="<?= which_data($row,"reward_accno",$Errormsg) ?>"<?= check_disabled("2"); ?> onKeyPress="return number(event)"></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("62") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="reward_accname" size="30" maxlength="32" value="<?= which_data($row,"reward_accname",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_page_data("16") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><input type="text" name="reward_sponsorship" size="6" maxlength="6" value="<?= which_data($row,"reward_sponsorship",$Errormsg) ?>"<?= check_disabled("2"); ?>></td>
         </tr>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap width="150"><?= get_word("90") ?> <?= get_word("65") ?>:</td>
          <td bgcolor="#FFFFFF" align="left"><?= $row[referedby] ?></td>
         </tr>
         <?

         }

         ?>
         <tr>
          <td class="<?= bg($row) ?>" align="right" nowrap>&nbsp;</td>
          <td bgcolor="#FFFFFF" align="right"><input type="submit" name="main" value="<?= get_page_data("12") ?>"></td>
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

function select_statements($row, $Errormsg = false, $ErrorArray = false) {

?>
<input type="hidden" name="DisplayStatement" value="1">
<table border="0" cellpadding="1" cellspacing="1" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="<?= bg($row) ?>"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] - <?= get_page_data("8") ?></td>
	</tr>
	<tr>
		<td width="150" align="right" class="<?= bg($row) ?>"><b><?= get_word("38") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

            $query = dbRead("select tbl_admin_months.* from tbl_admin_months");
            form_select('currentmonth',$query,'Month','FieldID',date("m"));

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="<?= bg($row) ?>"><b><?= get_word("40") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_month_array();
            form_select('numbermonths',$query,'','','','None','','','','All');

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="<?= bg($row) ?>"><b><?= get_word("39") ?>:</b></td>
		<td width="450" bgcolor="#FFFFFF">
           <?

			$query = get_year_array();
            form_select('currentyear',$query,'','',date("Y"));

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" class="<?= bg($row) ?>">&nbsp;</td>
		<td width="450" bgcolor="#FFFFFF">
        <input type="submit" value="<?= get_page_data("21") ?>" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>
<?

}

function display_statements($row, $numbermonths = false) {

// Get current month dates in unix timestamp format.

 $currentmonth = (!$_REQUEST['currentmonth']) ? date("m") : str_pad($_REQUEST['currentmonth'], 2, "0", STR_PAD_LEFT);
 $currentyear = (!$_REQUEST['currentyear']) ? date("Y") : $_REQUEST['currentyear'];

 if($currentmonth == date("m") && $currentyear == date("Y") && $numbermonths == 0) {

  // Current Statement.

  add_kpi("6", $row['memid']);

 } else {

  // Past Statement.

  add_kpi("7", $row['memid']);

 }

 $startdate = mktime(0,0,0,$currentmonth-$numbermonths,1,$currentyear);
 $enddate = mktime(0,0,0,$currentmonth+1,1,$currentyear);

 if($startdate < 1162303200 && checkmodule("Log")) {
	//$startdate = 1162303200;
 }

// Get current cash fee balance out of the db before the start of this month.

 $dbgetcashbal = dbRead("select sum(dollarfees) as ccb from transactions where memid='$row[memid]' and date < $startdate");
 $cashrow = mysql_fetch_assoc($dbgetcashbal);
 $dollarfees_total = $cashrow[ccb];

// Get current trade balance out of the db before the start of this month.

 //if($startdate < 1162303200 && !checkmodule("EditMemberLevel2")) {

 $dbgetbal = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='$row[memid]' and date < $startdate");
 $traderow = mysql_fetch_assoc($dbgetbal);
 $tradebal = $traderow[cb];

 $dbgetbalu = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='$row[memid]' and checked = 0 and date < $startdate");
 $traderowu = mysql_fetch_assoc($dbgetbalu);
 $tradebalu = $traderowu[cb];
?>
<script LANGUAGE="JavaScript">
	<!--

		function reverseTrans(itemID) {

			bDelete = confirm("Are you sure you want to reverse transaction: " + itemID + "?");

			if (bDelete) {

				location.href="/general.php?currentmonth=<?=  $_REQUEST['currentmonth'] ?>&Client=<?=  $_REQUEST['Client'] ?>&numbermonths=<?=  $_REQUEST['numbermonths'] ?>&currentyear=<?=  $_REQUEST['currentyear'] ?>&pageno=1&tab=tab7&transactionReversal=" + itemID;

			} else {

			    return;

			}

		}

		function reverseTrust(itemID) {

			bDelete = confirm("Are you sure you want to reverse trust: " + itemID + "?");

			if (bDelete) {

				location.href="/general.php?transactionTrust=" + itemID;

			} else {

			    return;

			}

		}

	//-->
</script>

<table width="639" border="0" cellspacing="0" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td width="150" align="left"><a href="javascript:print()" class="nav"><img border="0" src="images/icon_printable.gif"></a></td>
    <td width="150" align="left"><b>&nbsp;</b></td>
    <td align="right"><?= get_word("7") ?>:  <?= get_all_added_characters($row[phonearea]) ?> <?= get_all_added_characters($row[phoneno]) ?></td>
  </tr>
  <tr>
    <td width="150" align="right">&nbsp;</td>
    <td width="150" align="left"><b>&nbsp;</b></td>
    <td align="right"><?= get_word("29") ?>: <?= get_all_added_characters($row[abn]) ?></td>
  </tr>
  <tr>
    <td width="150" align="right"><?= get_word("50") ?>:</td>
    <td width="150" align="left"><b><?= $row[memid] ?></b></td>
    <td align="right"><?= get_all_added_characters($row[companyname]) ?></td>
  </tr>
  <tr>
    <td width="150" align="right"><?= get_word("4") ?>:</td>
    <td width="150" align="left"><b><?= get_all_added_characters($row[accholder]) ?></b></td>
    <td align="right">&nbsp;<?= get_all_added_characters($row[streetno]) ?> <?= get_all_added_characters($row[streetname]) ?></td>
  </tr>
  <tr>
    <td width="150" align="right"><?= get_word("38") ?>/<?= get_word("39") ?>:</td>
    <td width="150" align="left"><b><?= $currentmonth ?>/<?= $currentyear ?></b></td>
    <td align="right"><?= get_all_added_characters($row[city]) ?>, <?= get_all_added_characters($row[state]) ?>, <?= get_all_added_characters($row[postcode]) ?></td>
  </tr>
</table>
<table width="639" cellpadding="2" cellspacing="0" border="1" bordercolor="#97A5BB" style="border-collapse: collapse">
  <tr>
    <td class="<?= bg($row) ?>" valign="bottom"><b><?= get_word("41") ?></b>&nbsp;</td>
    <td class="<?= bg($row) ?>" valign="bottom"><b><?= get_word("42") ?></b>&nbsp;</td>
    <td class="<?= bg($row) ?>" width="2" valign="bottom"><b>C</b></td>
    <td class="<?= bg($row) ?>" align="right" width="90" valign="bottom"><b><?= get_word("43") ?></b>&nbsp;</td>
    <td class="<?= bg($row) ?>" align="right" width="90" valign="bottom"><b><?= get_word("44") ?></b>&nbsp;</td>
    <td class="<?= bg($row) ?>" align="right" width="90" valign="bottom"><b><?= get_word("45") ?></b>&nbsp;</td>
    <td class="<?= bg($row) ?>" align="right" width="45" valign="bottom"><b><?= get_word("46") ?></b>&nbsp;</td>
    <td class="<?= bg($row) ?>" align="right" width="50" valign="bottom"><b><?= get_word("47") ?></b>&nbsp;</td>
  </tr>
  <tr>
    <td height="19" valign="top">&nbsp;</td>
    <td align="right" height="19" valign="top"><b><?= get_word("51") ?>:</b></td>
    <td align="right" height="2" valign="top"><b></b></td>
    <td align="right" width="90" valign="top" height="19">&nbsp;</td>
    <td align="right" width="90" valign="top" height="19">&nbsp;</td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($tradebal,2) ?></td>
    <td align="right" width="45" valign="top" height="19">&nbsp;</td>
    <td width="50" align="right" valign="top" height="19">&nbsp;<?= number_format($dollarfees_total,2) ?></td>
  </tr>

<?

// Get the transactions out.

$dbgettrans = dbRead("select transactions.date, transactions.to_memid, transactions.buy, transactions.sell, transactions.details, transactions.authno, transactions.dollarfees, transactions.type, transactions.id, transactions.chq_no, transactions.checked, tbl_members_companyinfo.Companyname as TOCompanyname from transactions left outer join tbl_members_companyinfo on (transactions.to_memid = tbl_members_companyinfo.memid AND transactions.dis_date BETWEEN tbl_members_companyinfo.datefrom AND tbl_members_companyinfo.dateto) where transactions.memid='$row[memid]' and transactions.date >= $startdate and transactions.date < $enddate order by transactions.dis_date, transactions.id ASC");

$foo = 0;

while($transrow = mysql_fetch_assoc($dbgettrans)) {

if(!$transrow[details]) {
 $dis_details = "&nbsp;";
} else {
 $dis_details = $details;
}

if($transrow[buy] == "0") {
 $tradebal += $transrow[sell];
 if(!$transrow[checked])  {
  $tradebalu += $transrow[sell];
 }
} else {
 $tradebal -= $transrow[buy];
 if(!$transrow[checked])  {
  $tradebalu -= $transrow[buy];
 }
}

if($transrow[checked] == "0")  {
 $cleared = "C";
} else {
 $cleared = "U";
}

$dis_date = date("d/m/y", $transrow[date]);

 $dbgetotherid = dbRead("select * from members where memid='$transrow[to_memid]'");
 $otherrow = mysql_fetch_assoc($dbgetotherid);

 if($transrow['TOCompanyname']) {

  $otherrow[$otherrow[displayname]] = $transrow['TOCompanyname'];

 }

$dollarfees_total += $transrow[dollarfees];

$cfgbgcolorone = "#CCCCCC";
$cfgbgcolortwo = "#EEEEEE";
$bgcolor = $cfgbgcolorone;
$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

?>

  <tr>
    <td valign="top" bgcolor="<?= $bgcolor ?>" height="19"><a href="javascript:open_win('body.php?page=trans_details&id=<?= $transrow[id] ?>');" class="nav"><?= $dis_date ?></a></td>
    <td valign="top"  bgcolor="<?= $bgcolor ?>" height="19"><? echo '<a href="body.php?page=member_edit&Client='.$transrow[to_memid].'&pagno=1&tab=tab5 " target=_blank class="nav">'; ?><?= $otherrow[$otherrow[displayname]] ?></a><? if($transrow[details]) { print'<br><font style="font-size: 7pt">'.$transrow[details].' '.$transrow[id].'</font>'; } ?><? if($transrow[chq_no] > 0) { print'<br><font style="font-size: 7pt">Cheque No: '.$transrow[chq_no].'</font>'; } ?>&nbsp;</td>    <td width="2" align="center" valign="top" bgcolor="<?= $bgcolor ?>" height="19"><? if(checkmodule("AuthEdit")) { echo '<a href="body.php?page=auth_edit&data='.$transrow[authno].'&search=true&redirectpage=body.php%3Fpage%3Dmember_edit%26Client%3D'.$_REQUEST['Client'].'%26DisplayStatement%3Dtrue%26currentmonth%3D'.$_REQUEST['currentmonth'].'%26currentyear%3D'.$_REQUEST['currentyear'].'%26numbermonths%3D'.$_REQUEST['numbermonths'].'%26tab%3D'.$_REQUEST['tab'].'" class="nav">'; } ?><?= $cleared ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<? if($transrow[buy] > 0 && checkmodule("Reversals")) { ?><a class="nav" href="javascript:reverseTrans('<?= $transrow[authno] ?>')"><? } ?><?= number_format($transrow[buy],2) ?><? if($transrow[buy] > 0 && checkmodule("Reversals")) { ?></a><? } ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<? if($transrow[sell] > 0 && checktrust($row[memid],$row[CID])) { ?><a class="nav" href="javascript:reverseTrust('<?= $transrow[authno] ?>')"><? } elseif($transrow[sell] > 0 && checkmodule("Reversals")) { ?><a class="nav" href="javascript:reverseTrans('<?= $transrow[authno] ?>')"><? } ?><?= number_format($transrow[sell],2) ?><? if($transrow[sell] > 0 && checkmodule("Reversals")) { ?></a><? } ?></td>
    <td width="90" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">&nbsp;<?= number_format($tradebal,2) ?></td>
    <td width="45" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">
    &nbsp;<?= number_format($transrow[dollarfees],2) ?></td>
    <td width="50" align="right" valign="top" bgcolor="<?= $bgcolor ?>" height="19">
    &nbsp;<?= number_format($dollarfees_total,2) ?></td>
  </tr>

<?
$statement_fees += $transrow[dollarfees];
$total_buy += $transrow[buy];
$total_sell += $transrow[sell];
$foo++;
}

$taf = $tradebal-$row[overdraft]-$row[reoverdraft];

if($tradebalu < 0) {
 $tradebalu = 0;
}
//number_format($statement_fees9,2)
?>

  <tr>
    <td height="19" valign="top">&nbsp;</td>
    <td align="right" valign="top" height="19" colspan="2"><b><?= get_word("52") ?>:</b></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($total_buy,2) ?></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($total_sell,2) ?></td>
    <td width="90" align="right" valign="top" height="19">&nbsp;<?= number_format($tradebal,2) ?></td>
    <td width="45" align="right" valign="top" height="19">&nbsp;</td>
    <td width="50" align="right" valign="top" height="19">&nbsp;<?= number_format($dollarfees_total,2) ?></td>
  </tr>
</table>
<table width="639" border="0" cellspacing="1" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111" height="96">
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("218") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19">T<?= $_SESSION['Country']['currency'] ?><?= number_format($tradebal,2) ?></td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("76") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19">T<?= $_SESSION['Country']['currency'] ?><?= number_format($tradebalu,2) ?></td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("53") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19">T<?= $_SESSION['Country']['currency'] ?><?= number_format($row[overdraft],2) ?></td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("54") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19">T<?= $_SESSION['Country']['currency'] ?><?= number_format($row[reoverdraft],2) ?></td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td align="right" height="19"><b><?= get_word("55") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19">T<?= $_SESSION['Country']['currency'] ?><?= number_format($taf,2) ?></td>
  </tr>
  <tr>
    <td height="19" colspan="2"><?= get_page_data("49") ?>&nbsp;</td>
    <td width="252" align="right" height="19">
    <button style="width: 120; height: 25; font-family: Verdana; font-weight: bold; font-size: 10px" type="submit">
    <?= get_page_data("22") ?>
    </button>
    </td>
  </tr>
</table>
<?

}

function edit_note() {

 $NoteSQL = dbRead("select * from notes where FieldID = " . $_REQUEST['ID']);
 $NoteRow = mysql_fetch_assoc($NoteSQL);
 $array = explode("-", $NoteRow['reminder']);
 $month = $array[1];
 $day = $array[2];
 $year = $array[0];

 ?>
 <input type="hidden" name="SaveNote" value="1">
 <input type="hidden" name="NoteID" value="<?= $_REQUEST['ID'] ?>">
 <table width="620" cellspacing="0" cellpadding="3" style="border: 1px solid #0E1B2A">
  <tr>
   <td style="background: #97A5BB; border-bottom: 1px solid #0E1B2A; color: #FFFFFF; font-weight: bold">Edit Note</td>
  </tr>
  <tr>
   <td><textarea rows="9" cols="75" name="note"><?= $NoteRow['note'] ?></textarea></td>
  </tr>
  <tr>
   <td><select size="1" name="type">
    <option <? if($NoteRow['type'] == "1") { echo "selected "; } ?> value="1"><?= get_word("170") ?></option>
    <option <? if($NoteRow['type'] == "2") { echo "selected "; } ?> value="2"><?= get_word("172") ?></option>
    <option <? if($NoteRow['type'] == "3") { echo "selected "; } ?> value="3"><?= get_word("169") ?></option>
	<?if($_SESSION['User']['Area'] == 1) {?>
    <option <? if($NoteRow['type'] == "4") { echo "selected "; } ?> value="4">HQ Note</option>
	<?}?>
    </select>&nbsp;&nbsp;<b><?= get_word("178") ?>:</b>&nbsp;&nbsp;<select name="day1">
        <option <? if($day == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if($day == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if($day == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if($day == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if($day == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if($day == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if($day == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if($day == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if($day == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if($day == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if($day == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if($day == "12") { echo "selected "; } ?>value="12">12</option>
        <option <? if($day == "13") { echo "selected "; } ?>value="13">13</option>
        <option <? if($day == "14") { echo "selected "; } ?>value="14">14</option>
        <option <? if($day == "15") { echo "selected "; } ?>value="15">15</option>
        <option <? if($day == "16") { echo "selected "; } ?>value="16">16</option>
        <option <? if($day == "17") { echo "selected "; } ?>value="17">17</option>
        <option <? if($day == "18") { echo "selected "; } ?>value="18">18</option>
        <option <? if($day == "19") { echo "selected "; } ?>value="19">19</option>
        <option <? if($day == "20") { echo "selected "; } ?>value="20">20</option>
        <option <? if($day == "21") { echo "selected "; } ?>value="21">21</option>
        <option <? if($day == "22") { echo "selected "; } ?>value="22">22</option>
        <option <? if($day == "23") { echo "selected "; } ?>value="23">23</option>
        <option <? if($day == "24") { echo "selected "; } ?>value="24">24</option>
        <option <? if($day == "25") { echo "selected "; } ?>value="25">25</option>
        <option <? if($day == "26") { echo "selected "; } ?>value="26">26</option>
        <option <? if($day == "27") { echo "selected "; } ?>value="27">27</option>
        <option <? if($day == "28") { echo "selected "; } ?>value="28">28</option>
        <option <? if($day == "29") { echo "selected "; } ?>value="29">29</option>
        <option <? if($day == "30") { echo "selected "; } ?>value="30">30</option>
        <option <? if($day == "31") { echo "selected "; } ?>value="31">31</option>
       </select>
       <select name="month1">
        <option <? if($month == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if($month == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if($month == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if($month == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if($month == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if($month == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if($month == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if($month == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if($month == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if($month == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if($month == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if($month == "12") { echo "selected "; } ?>value="12">12</option>
       </select>
		<?

		$query = get_year_array(1);
	    form_select('year1',$query,'','',$year);

	   	?>&nbsp;&nbsp;<input type="submit" value="UpdateNote"></td>
  </tr>
 </table>
 <?
}

function save_note() {

 if(!$_REQUEST['note']) {

  //delete note
  dbWrite("delete from notes where FieldID = " . $_REQUEST['NoteID']);

 } else {

  //update note
 $reminder = $_REQUEST['year1']."-".$_REQUEST['month1']."-".$_REQUEST['day1'];
 if($reminder < date("Y-m-d"))  {
  $reminder = "0000-00-00";
 }

  dbWrite("update notes set note = '".addslashes(encode_text2($_REQUEST['note']))."', reminder = '".$reminder."', type =  '".$_REQUEST['type']."' where FieldID = " . $_REQUEST['NoteID']);

 }

}

function display_notes($row, $Errormsg = false, $ErrorArray = false) {

 global $Admin;

 if($_REQUEST['adddeal']) {

	dbWrite("insert into deals (NoteID,MemID,UserID,Details,AuthNo,Amount) values ('".$_REQUEST['noteid2']."','".$_REQUEST['Client']."','".$_SESSION['User']['FieldID']."','".addslashes(encode_text2($_REQUEST['details']))."','".$_REQUEST['authno']."','".$_REQUEST['amount']."')","etradebanc", true);

 }

 $SQLCount = dbRead("SELECT Type , count(Type) as Count FROM notes WHERE memid = '".$_REQUEST['Client']."' and note != '' GROUP BY TYPE");
 while($SQLCountRow = mysql_fetch_assoc($SQLCount)) {
  $NoteCount[$SQLCountRow[Type]] = $SQLCountRow['Count'];
 }

 $SQL =  "select tbl_admin_users.*, notes.*, UNIX_TIMESTAMP(date) as unix_date from notes, tbl_admin_users where (notes.userid = tbl_admin_users.FieldID) and notes.memid = '".$row['memid']."' and note != ''  ";

 if($_REQUEST['ViewNote']) {

  $SQL .= "and notes.type IN (".$_REQUEST['ViewNote'].") ";

 } else {

  //$SQL .= "and notes.type IN (".$_SESSION['User']['NoteType'].") ";

 }

 if($_SESSION['User']['Area'] != 1) {

  $SQL .= "and notes.type not in (4) ";

 }

 $SQL .= "order by notes.date DESC";
 $p = new Pager;
 $limit = 20;

 $start = $p->findStart($limit);
 $rs = dbRead($SQL);
 $count = mysql_num_rows($rs);

 /* Find the number of pages based on $count and $limit */
 $pagenos = $p->findPages($count, $limit);

 /* Now we use the LIMIT clause to grab a range of rows */
 $rs = dbRead("$SQL LIMIT ".$start.", ".$limit);

 /* Now get the page list and echo it */
 $pagelist = $p->pageList($_REQUEST['pageno'], $pagenos);

 $SQLQuery = $rs;

?>
<table border="0" cellspacing="0" width="620" cellpadding="1">
<tr>
<td><?= $pagelist ?></td></tr></table>
<table border="0" cellspacing="0" width="620" cellpadding="1">
<tr>
<td>
<table border="0" cellspacing="0" width="100%" cellpadding="3">
  <tr>
    <td width="100%" align="center"><a href="#GoAdd" class="nav"><?= get_page_data("15") ?></a><?if($_SESSION['User']['Area'] == 1) {?>&nbsp;|&nbsp;<a href="/body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab9&ViewNote=4" class="<?= do_notes_bold("4"); ?>">HQ Notes
    (<?= number_format($NoteCount['4']) ?>)</a><?}?>&nbsp;|&nbsp;<a href="/body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab9&ViewNote=1" class="<?= do_notes_bold("1"); ?>"><?= get_word("170")  ?>
    (<?= number_format($NoteCount['1']) ?>)</a>&nbsp;|&nbsp;<a href="/body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab9&ViewNote=2" class="<?= do_notes_bold("2"); ?>">
    <?= get_word("172")  ?> (<?= number_format($NoteCount['2']) ?>)</a>&nbsp;|&nbsp;<a href="/body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab9&ViewNote=3" class="<?= do_notes_bold("3"); ?>"><?= get_word("169")  ?>
    (<?= number_format($NoteCount['3']) ?>)</a>&nbsp;|&nbsp;<a href="/body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab9&ViewNote=1,2,3" class="<?= do_notes_bold("1,2,3"); ?>"><?= get_word("171")  ?>
    (<?= number_format(@array_sum($NoteCount)) ?>)</a</td>
  </tr>
</table>
</td>
</tr>
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
  <tr>
    <td width="60" class="<?= bg1($row) ?>""><?= get_word("41") ?>:</td>
    <td width="100" class="<?= bg1($row) ?>""><?= get_word("56") ?>:</td>
    <td width="340" class="<?= bg1($row) ?>""><?= get_word("57") ?>:</td>
    <td width="75" class="<?= bg1($row) ?>"></td>
  </tr>
  </tr>
<?

$date = date("Y-m-d");

$foo=0;
while($notesrow = mysql_fetch_assoc($SQLQuery)) {

	if($notesrow['type'] == 1) {
	 $cfgbgcolorone="#F95858";
	 $cfgbgcolortwo="#FD9393";
	} else {
	 $cfgbgcolorone="#CCCCCC";
	 $cfgbgcolortwo="#EEEEEE";
	}

	//$cfgbgcolorone="#CCCCCC";
	//$cfgbgcolortwo="#EEEEEE";
	$bgcolor=$cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

	if($notesrow['type'] == 1) {
	  $bb="bordercolor=red";
	} else {
	  $bb="bordercolor=".$bgcolor;
	}

	$display_date = date("jS M y", $notesrow[unix_date]);

	if($notesrow['Name']) {

	 $display_name = $notesrow['Name'];

	} else {

	 $display_name = $notesrow['Username'];

	}

     $Dealquery = dbRead("select * from deals where NoteID = ".$notesrow['FieldID']."");
	 $Dealrow = mysql_fetch_assoc($Dealquery);

?>
  <tr <?= $bb ?> bgcolor="<?= $bgcolor ?>">
    <input type="hidden" name="noteid" value="<?= $notesrow['FieldID'] ?>">
    <td width="60"><?= $display_date ?></td>
    <td width="100"><?= $display_name ?></td>
    <?if ($_SESSION['User']['FieldID'] == $notesrow['userid'] && date("Y-m-d", strtotime($notesrow['date'])) == $date) {?><td width="300"><a href="/body.php?page=member_edit&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab9&EditNote=true&ID=<?= $notesrow['FieldID'] ?>"><?= $notesrow[note] ?></a></td><?} else {?><td width="300"><?= $notesrow[note] ?></td><?}?>
    <td><?if($_SESSION['User']['FieldID'] == $notesrow['userid'] && !$Dealrow) {?>
	<?}?></td>
  </tr>


	<?
     //$Dealquery = dbRead("select * from deals where NoteID = ".$notesrow['FieldID']."");
	 //$Dealrow = mysql_fetch_assoc($Dealquery);
     if($Dealrow) {
     ?>
  	  <tr <?= $bb ?> bgcolor="<?= $bgcolor ?>">
       <td width="100"><?= $Dealrow['AuthNo'] ?></td>
       <td width="100"><?= number_format($Dealrow['Amount'], 2) ?></td>
       <td colspan="2"><font color="#2400FF"><b>DEAL:</b></font> <?= get_all_added_characters($Dealrow['Details']) ?></td>
	  </tr>
 	<?
     }
	?>

<?

$foo++;

}


?>
</table>
</td>
</tr>
</table>
<br>
<?
if($_REQUEST['deal']) {
?>
<table border="1" cellspacing="0" width="620" cellpadding="3">
 <tr>
    <td class="<?= bg($row) ?>" nowrap align="right" width="50%" valign="top"><span lang="en-us">Details:</span></td>
    <td nowrap align="left" width="50%" bgcolor="#FFFFFF">
    <input type="hidden" name="noteid2" value="<?= $_REQUEST['noteid2'] ?>">
    <input type="hidden" name="memid" value="<?= $_REQUEST['memid'] ?>">
    <textarea rows="3" name="details" cols="30"></textarea></td>
    </tr>
     <tr>
      <td class="<?= bg($row) ?>" align="right" nowrap>Authno:</td>
      <td bgcolor="#FFFFFF" align="left"><input type="text" name="authno" size="10"  maxlength="10" value="" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td class="<?= bg($row) ?>" align="right" nowrap>Trade Amount:</td>
      <td bgcolor="#FFFFFF" align="left"><input type="text" name="amount" size="10"  maxlength="10" value="" onKeyPress="return number(event)"></td>
     </tr>
    <tr>
    <td bgcolor="#FFFFFF" nowrap align="center" width="100%" colspan="2">
    <button name="adddeal" style="width: 131; height: 25" type="submit"><b>
    <font face="Tahoma"><span lang="en-us">Add Deal</span></font></b>
    </button></td>

 </tr>
</table>

<?
} else {

if($_REQUEST['ID']) {

 $query1 = mysql_db_query($db, "select notes.*, UNIX_TIMESTAMP(date) as unix_date from notes where FieldID = '".$_REQUEST['ID']."' order by date DESC", $linkid);
 $row1 = mysql_fetch_array($query1);

}

?>
<a name="GoAdd"></a>
<input type="hidden" name="AddNote" value="1">
<input type="hidden" value="<?= $row1[FieldID] ?>" name="id">
<table border="0" cellpadding="1" cellspacing="0" width="620">
<tr>
<td class="Border">
<table border="0" cellspacing="0" width="100%" cellpadding="3" style="border-collapse: collapse" bordercolor="#111111">
  <tr>
    <td width="100%" class="<?= bg1($row) ?>""><?= get_page_data("15") ?></tr>
  <tr>
    <td bgcolor="#FFFFFF"><textarea rows="9" cols="75" name="note"><?= $row1[note] ?></textarea></tr>
  <tr>
    <td bgcolor="#FFFFFF"><b><?= get_word("86") ?>:</b>&nbsp;&nbsp;<select size="1" name="type">
    <option <? if($_SESSION['User']['NoteType'] == "1") { echo "selected "; } ?> value="1"><?= get_word("170") ?></option>
    <option <? if($_SESSION['User']['NoteType'] == "2") { echo "selected "; } ?> value="2"><?= get_word("172") ?></option>
    <option <? if($_SESSION['User']['NoteType'] == "3") { echo "selected "; } ?> value="3"><?= get_word("169") ?></option>
	<?if($_SESSION['User']['Area'] == 1) {?>
    <option <? if($_SESSION['User']['NoteType'] == "4") { echo "selected "; } ?> value="4">HQ Note</option>
	<?}?>
    </select>&nbsp;&nbsp;<b><?= get_word("178") ?>:</b>&nbsp;&nbsp;<select name="day1">
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "12") { echo "selected "; } ?>value="12">12</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "13") { echo "selected "; } ?>value="13">13</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "14") { echo "selected "; } ?>value="14">14</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "15") { echo "selected "; } ?>value="15">15</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "16") { echo "selected "; } ?>value="16">16</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "17") { echo "selected "; } ?>value="17">17</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "18") { echo "selected "; } ?>value="18">18</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "19") { echo "selected "; } ?>value="19">19</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "20") { echo "selected "; } ?>value="20">20</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "21") { echo "selected "; } ?>value="21">21</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "22") { echo "selected "; } ?>value="22">22</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "23") { echo "selected "; } ?>value="23">23</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "24") { echo "selected "; } ?>value="24">24</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "25") { echo "selected "; } ?>value="25">25</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "26") { echo "selected "; } ?>value="26">26</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "27") { echo "selected "; } ?>value="27">27</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "28") { echo "selected "; } ?>value="28">28</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "29") { echo "selected "; } ?>value="29">29</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "30") { echo "selected "; } ?>value="30">30</option>
        <option <? if((date("d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))) == "31") { echo "selected "; } ?>value="31">31</option>
       </select>
       <select name="month1">
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "01") { echo "selected "; } ?>value="01">1</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "02") { echo "selected "; } ?>value="02">2</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "03") { echo "selected "; } ?>value="03">3</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "04") { echo "selected "; } ?>value="04">4</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "05") { echo "selected "; } ?>value="05">5</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "06") { echo "selected "; } ?>value="06">6</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "07") { echo "selected "; } ?>value="07">7</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "08") { echo "selected "; } ?>value="08">8</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "09") { echo "selected "; } ?>value="09">9</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "10") { echo "selected "; } ?>value="10">10</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "11") { echo "selected "; } ?>value="11">11</option>
        <option <? if(date("m", mktime(0,0,0,date("m"),date("d")-1,date("Y"))) == "12") { echo "selected "; } ?>value="12">12</option>
       </select>
		<?

		$query = get_year_array(1);
	    form_select('year1',$query,'','',date("Y", mktime(0,0,0,date("m"),date("d")-1,date("Y"))));

	   	?>&nbsp;&nbsp;
	<button name="updatenote" style="width: 81; height: 20" type="submit">
    <b><font size="1" face="Verdana"><?= get_page_data("15") ?></font></b>
    </button>

  <a href="javascript:new_window6('body.php?page=deal&memid=<?= $row['memid']?>&noteid=<?= $row['FieldID']?>');" class="nav"></a>
  </tr>
  <?
  if(!$_REQUEST['ID'] && !$_REQUEST['deal']) {
  ?>
  <tr>
   <td bgcolor="#FFFFFF"><b>Send Note To: </b><select size="1" name="message_sendto"><option value="">Forward To</option>
    <?
     $query = dbRead("select Name, Position, FieldID from tbl_admin_users where Name != '' and Suspended !='1' and CID = ".$_SESSION['Country']['countryID']." order by Name");
     while($row = mysql_fetch_assoc($query)) {

      ?>
       <option value="<?= $row['FieldID'] ?>"><?= $row['Name'] ?> (<?= $row['Position'] ?>)</option>
      <?

     }
    ?>
    </select>  <?if(checkmodule("EditMemberLevel2")) {?><input type="checkbox" name="reply" value="1">Agent Response required<?}?> </td>
  </tr>
  <?}?>
  <tr>
   <td bgcolor="#FFFFFF"><b>Add Note to Additional Account: </b><input type="text" name="anote" size="10" maxlength="6" value="<?= which_data($row,"anote",$Errormsg) ?>"></td>
  </tr>
</table>
</td>
</tr>
</table>

<?
}
}

function display_log($row) {

 $SQL = "SELECT tbl_kpi_changes.Data , tbl_kpi.*, tbl_kpi_type.Type FROM tbl_kpi_type, tbl_kpi LEFT OUTER JOIN tbl_kpi_changes ON (tbl_kpi.FieldID = tbl_kpi_changes.KpiID) WHERE (tbl_kpi.Type = tbl_kpi_type.FieldID) and tbl_kpi.memid = '".$row[memid]."' Order by tbl_kpi.Date DESC";

 $p = new Pager;
 $limit = 20;

 $start = $p->findStart($limit);
 $rs = dbRead($SQL,"etxint_log");
 $count = mysql_num_rows($rs);

 /* Find the number of pages based on $count and $limit */
 $pagenos = $p->findPages($count, $limit);

 /* Now we use the LIMIT clause to grab a range of rows */
 $rs = dbRead("$SQL LIMIT ".$start.", ".$limit,"etxint_log");

 /* Now get the page list and echo it */
 $pagelist = $p->pageList($_REQUEST['pageno'], $pagenos);

 $SQLQuery = $rs;

?>
<?= $pagelist ?><br>
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" id="AutoNumber1" width="620">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2" width="100%">
      <tr>
        <td colspan="4" align="center" class="<?= bg1($row) ?>""><?= get_word("66") ?>: <?= get_all_added_characters($row[companyname]) ?> [<?= $row[memid] ?>] - <?= get_page_data("9") ?></td>
      </tr>
      <tr>
        <td width="65" valign="top" class="<?= bg($row) ?>"><?= get_word("41") ?>:</td>
        <td width="90" valign="top" class="<?= bg($row) ?>"><?= get_word("77") ?>:</td>
        <td width="130" valign="top" class="<?= bg($row) ?>"><?= get_word("86") ?>:</td>
        <td valign="top" class="<?= bg($row) ?>"><?= get_word("85") ?>:</td>
      </tr>
      <?

       $foo = 0;

       while($row = mysql_fetch_assoc($SQLQuery)) {

        $UserQuery = dbRead("select tbl_admin_users.Name from tbl_admin_users where FieldID = '".addslashes($row['UserID'])."'");
        $UserRow = mysql_fetch_assoc($UserQuery);

        $cfgbgcolorone="#CCCCCC";
        $cfgbgcolortwo="#EEEEEE";
        $bgcolor=$cfgbgcolorone;
        $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

        ?>
      <tr bgcolor="<?= $bgcolor ?>">
        <td width="65" valign="top"><?= date("jS M y", strtotime($row['Date'])); ?></td>
        <td width="90" valign="top"><?= get_all_added_characters($UserRow['Name']) ?></td>
        <td width="130" valign="top"><?= get_all_added_characters($row['Type']) ?></td>
        <td valign="top"><?= display_log_array($row['Data']) ?></td>
      </tr>
        <?



        $foo++;

       }
      ?>
    </table>
    </td>
  </tr>
</table>
<?

}

function display_log_array($Data) {

 $NewData = unserialize($Data);
 if(is_array($NewData)) {

  foreach($NewData as $Key => $Value) {

   $PrintData .= "<b>" . $Key . "</b><br>&nbsp;&nbsp;" . $Value[0] . " Changed to " . $Value[1] . "<br>";

  }

  return $PrintData;

 }

}

function display_taxinvoice($row) {

?>
<table width="620" border="0" cellpadding="1" cellspacing="0">
<tr>
<td class="border">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" align="center" class="<?= bg($row) ?>"><?= get_word("66") ?>: <?= $row[companyname] ?> [<?= $row[memid] ?>] -<?= get_word("177") ?></td>
	</tr>
	<tr>
		<td width="150" align="right" class="<?= bg($row) ?>"><b><?= get_word("38") ?>:</b></td>
		<td bgcolor="#FFFFFF">
           <?

            $query = dbRead("select tbl_admin_months.* from tbl_admin_months order by tbl_admin_months.Month");
            form_select('currentmonth',$query,'Month','FieldID',date("n"));

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" align="right" class="<?= bg($row) ?>"><b><?= get_word("39") ?>:</b></td>
		<td bgcolor="#FFFFFF">
           <?

			$query = get_year_array();
            form_select('currentyear',$query,'','',date("Y"));

           ?>
		</td>
	</tr>
	<tr>
		<td width="150" class="<?= bg($row) ?>">&nbsp;</td>
		<td bgcolor="#FFFFFF">
        <input type="submit" value="<?= get_word("183") ?>" name="view" style="size: 8pt">
        <input type="submit" value="<?= get_word("180") ?>" name="send" style="size: 8pt"></td>
	</tr>
</table>
</td>
</tr>
</table>
<?

}

function display_adverts($row) {

?>
<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" id="AutoNumber1" width="620">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2" width="100%">

  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b>Advert Details</b></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><b><?= get_all_added_characters($row[companyname]) ?></b><br></td>
  </tr>
  <?
          $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$_REQUEST['Client']."' and mem_categories.category > 0 order by mem_categories.FieldID");
          while($catrow = mysql_Fetch_assoc($query)) {

           ?>
            <tr>
             <td colspan="2" bgcolor="#FFFFFF" align="left"><?= $catrow[description] ?><?if($catrow['dir_state'] == 1) {?><b> - State Member</b><?}?><?if($catrow['dir_nation'] == 1) {?><b> - National Member</b><?}?></td>
            </tr>
           <?

          }
  ?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><br>Currently Accepting: <?= $row[trade_per] ?>% Empire Trade</td>
  </tr>
  <?
  if($row[fiftyclub] == 2) {
   //$cc = "100% Trade for Gold Club Members<br>50% Trade for 50% Club Members and ".$row[trade_per]."% non Club Members";
   $cc = "100% Trade for Gold Club Members<br>50% Trade for non Club Members";
  } elseif($row[fiftyclub] == 1) {
   $cc = "50% Trade for all Club Members and ".$row[trade_per]."% non Club Members";
  } else {
   $cc = "";
  }

  if($cc) {?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><?= $cc ?></td>
  </tr>
  <?}?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><br>Contact: <?= get_all_added_characters($row[contactname]) ?> on <?= get_all_added_characters($row[phonearea]) ?> <?= get_all_added_characters($row[phoneno]) ?></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">Address: <?= get_all_added_characters($row[streetno]) ?> <?= get_all_added_characters($row[streetname]) ?>, <?= get_all_added_characters($row[suburb]) ?> <?= get_all_added_characters($row[city]) ?> <?= get_all_added_characters($row[state]) ?> <?= get_all_added_characters($row[postcode]) ?></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">Email: <? if($row['emailAddress_1']) { ?><a href="mailto:<?= $row['emailAddress_1'] ?>" class="nav"><? } ?><?= $row['emailAddress_1'] ?><? if($row['emailAddress_1']) { ?></a><? } ?></td>
  </tr>
  <?if($row[webpageurl]) {?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">Website: <?= $row[webpageurl] ?></td>
  </tr>
  <?}?>

  <tr>
    <td align="center" valign="middle" colspan="2" class="<?= bg($row) ?>"><b>Previous Adverts</b></td>
  </tr>
  <?
	  $advquery = dbRead("select tbl_jobs_data.*, subject from tbl_jobs_data, tbl_jobs where (tbl_jobs_data.jobID = tbl_jobs.FieldID) and memid = ".$row['memid']." order by FieldID Desc","etxint_email_system");
	  while($advrow = mysql_Fetch_assoc($advquery)) {
  ?>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF" align="left"><a href="javascript:advert('body.php?page=advert&id=<?= $advrow['FieldID'] ?>')"><?= $advrow['subject'] ?></a>
	</td>
  </tr>
  <?  }?>
      </table>
    </td>
   </tr>
</table>

<?
}

function communicate($row) {

 $SQL = "select * from ebanc_letters.letters, etradebanc.tbl_admin_users where (letters.userid = tbl_admin_users.FieldID) and memid = '".$_REQUEST['Client']."'";

 $p = new Pager;
 $limit = 12;

 $start = $p->findStart($limit);
 $rs = dbRead($SQL);
 $count = mysql_num_rows($rs);

 /* Find the number of pages based on $count and $limit */
 $pagenos = $p->findPages($count, $limit);

 /* Now we use the LIMIT clause to grab a range of rows */
 $rs = dbRead("$SQL LIMIT ".$start.", ".$limit);

 /* Now get the page list and echo it */
 $pagelist = $p->pageList($_REQUEST['pageno'], $pagenos);

 $SQLQuery = $rs;

?>

<table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse" id="AutoNumber1" width="620">
  <tr>
    <td width="100%" class="Border">
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2" width="100%">
      <tr>
        <td colspan="4" align="center" class="<?= bg1($row) ?>""><?= get_word("66") ?>: <?= get_all_added_characters($row[companyname]) ?> [<?= $row[memid] ?>] - <?= get_page_data("11") ?></td>
      </tr>
      <tr>
         <td colspan="4" class="<?= bg1($row) ?>""><?= $pagelist ?></td>
      <tr>
        <td width="60" valign="top" class="<?= bg($row) ?>"><?= get_word("41") ?>:</td>
        <td width="80" valign="top" class="<?= bg($row) ?>"><?= get_word("77") ?>:</td>
        <td width="50" valign="top" class="<?= bg($row) ?>">Type:</td>
        <td width="200" valign="top" class="<?= bg($row) ?>"><?= get_page_data("25") ?>:</td>
      </tr>
       <?

       $foo = 0;
       $query = dbRead("select * from ebanc_letters.letters, etradebanc.tbl_admin_users where (letters.userid = tbl_admin_users.FieldID) and memid = '".$_REQUEST['Client']."'");

       while($row = mysql_fetch_assoc($query)) {

        $cfgbgcolorone="#CCCCCC";
        $cfgbgcolortwo="#EEEEEE";
        $bgcolor=$cfgbgcolorone;
        $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

        if($row['type'] == 1) {
          $type = "Posted";
	   } else {
          $type = "Emailed";
        }
        ?>
      <tr bgcolor="<?= $bgcolor ?>">
        <td width="60" valign="top"><?= date("jS M y", strtotime($row['date'])); ?></td>
        <td width="80" valign="top"><?= $row['Name'] ?></td>
        <td width="50" valign="top"><?= $type ?></td>
        <td width="130" valign="top"><a href="includes/lettersend.php?Action=true&id=<?= $row['letterid']?>&ChangeMargin=1" class="nav"><?= $row['subject'] ?></a></td>
      </tr>
      <?}?>
    </table>
    </td>
   </tr>
</table>

<?
}

function history($row)  {
?>

<table width="610" border="0" cellspacing="0" cellpadding="1">
<tr>
<td class="border">
<table width="610" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="left" class="<?= bg($row) ?>" width="100"><b><?= get_word("38") ?>/<?= get_word("39") ?></b></td>
    <td align="right" class="<?= bg($row) ?>"><b><?= get_word("43") ?></b></td>
    <td align="right" class="<?= bg($row) ?>"><b><?= get_word("44") ?></b></td>
    <td align="right" class="<?= bg($row) ?>"><b>Current Fees</b></td>
    <td align="right" class="<?= bg($row) ?>"><b>Overdue</b></td>
    <td align="right" class="<?= bg($row) ?>"><b>Paid</b></td>
  </tr>
<?
$total=0;
$total2=0;
$foo= 0;

//$dbquery = dbRead("select sum(transactions.buy) as TradeAmount, sum(transactions.sell) as STradeAmount, extract(year_month from transactions.dis_date) as date1, date_format(transactions.dis_date,'%Y-%m') as Date2 from transactions, members where (members.memid = transactions.memid) and transactions.type IN (1,2) and members.memid = '".$_REQUEST['Client']."' and transactions.to_memid not in (".get_non_included_accounts($row[CID]).")  group by date1 order by date1 desc");
$dbquery2 = dbRead("select * from invoice where memid = '".$_REQUEST['Client']."' order by date desc");
while($row2 = mysql_fetch_assoc($dbquery2)) {

	//$date = $row3['Date2'];
	//$date2 = date("m/Y", strtotime($row3['Date2'] . "-01"));

	$date = $row2['date'];

	$array = explode("-", $row2['date']);
	$month = $array[1];
	$day = $array[2];
	$year = $array[0];
	$date2 = date("m/Y", mktime(0,0,1,$month,$day,$year));
	$date3 = date("Y-m", mktime(0,0,1,$month,$day,$year));

	$cfgbgcolorone = "#CCCCCC";
	$cfgbgcolortwo = "#EEEEEE";
	$bgcolor = $cfgbgcolorone;
	$foo % 2  ? 0: $bgcolor = $cfgbgcolortwo;

     //$dbquery2 = dbRead("select * from invoice where memid = '".$_REQUEST['Client']."' and date like '".$date."-%'");
     //$row2 = mysql_fetch_assoc($dbquery2);

      $dbquery = dbRead("select sum(transactions.buy) as TradeAmount, sum(transactions.sell) as STradeAmount from transactions, members where (members.memid = transactions.memid) and transactions.type IN (1,2) and members.memid = '".$_REQUEST['Client']."' and dis_date like '".$date3."-%' and transactions.to_memid not in (".get_non_included_accounts($row[CID]).")");
      $row3 = mysql_fetch_assoc($dbquery);
?>
  <tr>
    <td align="left" bgcolor="<?= $bgcolor ?>"><?= $date2 ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row3['TradeAmount'],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row3['STradeAmount'],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row2['currentfees'],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row2['overduefees'],2) ?></td>
    <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($row2['currentpaid'],2) ?></td>
  </tr>
<?

$total += $row3['TradeAmount'];
$total2 += $row3['STradeAmount'];
$total3 += $row2['currentfees'];
$total4 += $row2['currentpaid'];

$foo++;

}


?>
  <tr>
    <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><b><?= get_word("52") ?>:    <?= number_format($total,2) ?></b></td>
    <td align="right" bgcolor="#FFFFFF"><b><?= number_format($total2,2) ?></b></td>
    <td align="right" bgcolor="#FFFFFF"><b><?= number_format($total3,2) ?></b></td>
    <td align="right" bgcolor="#FFFFFF"></td>
    <td align="right" bgcolor="#FFFFFF"><b><?= number_format($total4,2) ?></b></td>
  </tr>
</table>
<?
// Get current trade balance out of the db before the start of this month.

 $dbgetbal = dbRead("select (sum(sell)-sum(buy)) as cb from transactions where memid='".$_REQUEST['Client']."' ");
 $traderow = mysql_fetch_assoc($dbgetbal);
 $tradebal = $traderow[cb];

 $taf = $tradebal-$row[overdraft]-$row[reoverdraft];
?>
<table width="610" border="0" cellspacing="1" cellpadding="2" style="border-collapse: collapse" bordercolor="#111111" height="96">
  <tr>
    <td align="right" height="19"  bgcolor="#FFFFFF"><b><?= get_word("218") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19"><?= $_SESSION['Country']['currency'] ?><?= number_format($tradebal,2) ?></td>
  </tr>
  <tr>
    <td align="right" height="19"  bgcolor="#FFFFFF"><b><?= get_word("53") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19"><?= $_SESSION['Country']['currency'] ?><?= number_format($row[overdraft],2) ?></td>
  </tr>
  <tr>
    <td align="right" height="19"  bgcolor="#FFFFFF"><b><?= get_word("54") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19"><?= $_SESSION['Country']['currency'] ?><?= number_format($row[reoverdraft],2) ?></td>
  </tr>
  <tr>
    <td align="right" height="19"  bgcolor="#FFFFFF"><b><?= get_word("55") ?>:</b></td>
    <td width="252" align="right" bgcolor="#CCCCCC" height="19"><?= $_SESSION['Country']['currency'] ?><?= number_format($taf,2) ?></td>
  </tr>
</table>
</td>
</tr>
</table>
<br>
<br>

</body>

<?
}

function display_quick_notes($row) {

 $message = "";
 $adfee = "";
 $refee = "";

 if($row['rob'] == 1 && checkmodule("EditMemberLevel2")) {

  $message .= "<font size = '3' color = #FF0000><b>(_??_)<br></b></font>";

 }

 if($row['paymenttype'] == 0 && $row['accountno'] > 0) {

  $message .= "<font size = '3' color = #FF0000><b>Direct Debit Needs Updating<br>Expired/Dishonoured<br></b></font>";

 }

 $date31 = date("Y-m", mktime(0,0,1,date("m"),date("d"),date("Y")));
 $date32 = date("Y-m-d", mktime(0,0,1,date("m"),1-1,date("Y")));

 $getpaidbal = dbRead("select sum(dollarfees) as nf from transactions where dis_date like '".$date31."-%' and dollarfees < 0 AND (to_memid NOT IN (".get_non_included_accounts($_SESSION['Country']['countryID'],true,false,false,true).")) and memid='".$_REQUEST['Client']."' ");
 $paidrow = mysql_fetch_assoc($getpaidbal);
 $invbal = dbRead("select sum(currentfees+currentpaid+overduefees) as minv from invoice where date = '".$date32."' and memid='".$_REQUEST['Client']."' ");
 $invrow = mysql_fetch_assoc($invbal);

 $nowing = $invrow['minv']+$paidrow['nf'];
 if($nowing > 0) {

   if($row['paymenttype'] == 0) {

	$message .= "<font color = #FF0000><b>Fees Due for Payment $".number_format($nowing,2)."</b></font><br>";

   }

 }

 switch($row['status']) {

  case "1": $message .= "<font color = #FF0000><b>".get_page_data("30")."</b></font><br>"; break;
  case "2": $message .= get_page_data("31")."<br>"; break;
  case "3": $message .= get_page_data("32")."<br>"; break;
  case "4": $message .= get_page_data("33")."<br>"; break;
  case "5": $message .= "<font color = #FF0000>".get_page_data("34")."</font><br>"; break;
  case "6": $message .= "<font color = #FF0000><b>".get_page_data("35")."</b></font><br>"; break;

 }

 if($row['status'] != 1) {

  if($_SESSION['Country']['countryID'] == 1 && $gg) {

	if($row['fiftyclub'] == 1 && $row['paymenttype'] != 0 && $row['datejoined'] < '2007-09-10') {
		$adfee = " - Currently Discounted to $" .number_format($_SESSION['Country']['admin_fee']/3,2);
	} elseif($row['fiftyclub'] == 1 && $row['paymenttype'] != 0 && $row['datejoined'] > '2007-09-10') {
		$adfee = " - Currently Discounted to $" .number_format($_SESSION['Country']['admin_fee']/2,2);
	} elseif($row['paymenttype'] != 0 && $row['datejoined'] > '2007-09-10') {
		$adfee = " - Currently Discounted to $" .number_format(($_SESSION['Country']['admin_fee']/3)*2,2);
	} elseif($row['paymenttype'] != 0 && $row['datejoined'] < '2007-09-10') {
		$adfee = " - Currently Discounted to $" .number_format(($_SESSION['Country']['admin_fee']/2),2);
	} elseif($row['fiftyclub'] == 1 && $row['paymenttype'] == 0 && $row['datejoined'] < '2007-09-10') {
		$refee = " reducing Admin Fee from $".number_format(($_SESSION['Country']['admin_fee']/3*2),2)." to $".number_format(($_SESSION['Country']['admin_fee']/3),2);
	} elseif($row['fiftyclub'] == 1 && $row['paymenttype'] == 0 && $row['datejoined'] > '2007-09-10') {
		$refee = " reducing Admin Fee from $".number_format($_SESSION['Country']['admin_fee'],2)." to $".number_format($_SESSION['Country']['admin_fee']/2,2);
	} elseif($row['fiftyclub'] == 2) {
		$refee = "";
	} elseif($row['datejoined'] < '2007-09-10') {
		$refee = " reducing Admin Fee from $".number_format($_SESSION['Country']['admin_fee']/3*2,2)." to $".number_format(($_SESSION['Country']['admin_fee']/2),2);
	} else {
		$refee = " reducing Admin Fee from $".number_format($_SESSION['Country']['admin_fee'],2)." to $".number_format(($_SESSION['Country']['admin_fee']/3)*2,2);
	}

	if($row['paymenttype'] == 0 && $row['admin_exempt'] == 0 && $_SESSION['Country']['a_fee'] == 'Y' && $row['wagesacc'] == 0 && $row['status'] != 3 && $row['Status'] != 1 && $row['Status'] != 2 && $row['Status'] != 6) {

	  $message .= "DIRECT DEBIT NEEDED ".$refee." <br>";

	}

    if($adfee && $row['fiftyclub'] != 2 && $row['admin_exempt'] == 0 && $_SESSION['Country']['a_fee'] == 'Y' && $row['wagesacc'] == 0 && $row['status'] != 3 && $row['Status'] != 1 && $row['Status'] != 2 && $row['Status'] != 6) {

		if($row['datejoined'] > "2007-09-10") {

		  $message .= "Standard Admin Fee $".number_format($_SESSION['Country']['admin_fee'],2)." ".$adfee." <br>";

		} else {

		  $message .= "Standard Admin Fee $".number_format($_SESSION['Country']['admin_fee']/3*2,2)." ".$adfee." <br>";

		}

	}

  }

  if($row['paymenttype'] == 0 && $row['admin_exempt'] == 0 && $_SESSION['Country']['a_fee'] == 'Y' && $row['wagesacc'] == 0 && $row['status'] != 3 && $row['Status'] != 1 && $row['Status'] != 2 && $row['Status'] != 6) {

    $message .= "DIRECT DEBIT NEEDED ".$refee." <br>";

  }

  if($row['t_unlist'] == 1) {

   $message .= "<font color=#F1A003><b>This Member is Temporary Unlisted.</b></font><br>";

  }

  $sumSQL = dbRead("select sum(category) as catSum from mem_categories where memid = ".$_REQUEST[Client]." group by memid ");
  $sumRow = mysql_fetch_assoc($sumSQL);

  if($sumRow['catSum'] == 0 || $row['status'] == 6) {

   $message .= "<font color=#F1A003><b>THIS MEMBER IS UNLISTED.</b></font><br>";

  }

  if($row['bdriven'] == "Y") {

   $message .= "This Member is Broker Driven.<br>";

  }

  if(!$row['pin']) {

   $message .= "This Member doesn't have a pin.<br>";

  }

  switch($row['letters']) {

   case "9": $message .= "<font color=#FF4444><b>".get_page_data("36")."</b></font><br>"; break;
   case "1": $message .= "<font color=#33cc66><b>".get_page_data("37")."</b></font><br>"; break;
   case "2": $message .= "<font color=#0080ff><b>".get_page_data("38")."</b></font><br>"; break;
   case "3": $message .= "<font color=#cc00cc><b>".get_page_data("39")."</b></font><br>"; break;
   case "4": $message .= "<font color=#FF4444><b>".get_page_data("50")."</b></font><br>"; break;
   case "6": $message .= "<font color=#FF4444><b>Debt collection action stopped<br><font color=#0080ff>Contact needed to resolve and get trading</font></b></font><br>"; break;

  }

  if($row['opt'] == "N") {

   $message .= get_page_data("40")."<br>";

  }

  if($row['reopt'] == "N") {

   $message .= "This Member has opted out of Real Estate emails<br>";

  }

  //if($row['fiftyclub'] == 1 || $row['fiftyclub'] == 5) {

   //$message .= "<font color = #FF0000><b>50% Club Member.<br></b></font><br>";

  //}

  if($row['fiftyclub'] == 2) {

   $message .= "<font color = #FF0000><b>Gold Club Member.<br></b></font><br>";

  }

  if($row['uncon'] == 'Y') {

   $message .= "<font color = #FF0000><b>THIS MEMBER IS UNCONTACTABLE<br></b></font>";

  }
 }

 if($message != "") {

   $message = substr($message, 0, -4);

   ?>
   <table width="620" border="1" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
    <tr>
     <td bgcolor="#FFFFFF" align="center"><?= $message ?>&nbsp;</td>
    </tr>
   </table><br>
   <?

 }
}

function which_data($row,$field,$error = false) {

 if($error) {
  return $_REQUEST[$field];
 } else {
  //return preg_replace('/\\\\/', '', htmlspecialchars($row[$field], ENT_QUOTES, "UTF-8"));
  return get_all_added_characters($row[$field]);
 }

}

function change_colour($field,$ErrorArray) {

 if($ErrorArray[$field]) {
  return "#FF0000";
 } else {
  return "#FFFFFF";
 }

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

function log_changes($row,$type) {

 $NIPageArray = array(
       'phpbb2mysql_data' => 'phpbb2mysql_data',
       'page' => 'page',
       'Client' => 'Client',
       'pageno' => 'pageno',
       'tab' => 'tab',
       'Update' => 'Update',
       'main' => 'main',
       'changemember' => 'changemember',
       'PHPSESSID' => 'PHPSESSID'
 );

 $DatesArray = array(
       'DOBholder_Day' => 'DOBholder_Day',
       'DOBholder_Month' => 'DOBholder_Month',
       'DOBholder_Year' => 'DOBholder_Year',
       'DOBcontact_Day' => 'DOBcontact_Day',
       'DOBcontact_Month' => 'DOBcontact_Month',
       'DOBcontact_Year' => 'DOBcontact_Year',
       'banked_Day' => 'banked_Day',
       'banked_Month' => 'banked_Month',
       'banked_Year' => 'banked_Year',
       'datejoined_Day' => 'datejoined_Day',
       'datejoined_Month' => 'datejoined_Month',
       'datejoined_Year' => 'datejoined_Year',
       'datepacksent_Day' => 'datepacksent_Day',
       'datepacksent_Month' => 'datepacksent_Month',
       'datepacksent_Year' => 'datepacksent_Year',
       'accountno' => 'accountno'
 );

 foreach($_REQUEST as $key => $value) {
  if($_REQUEST[$key] != $row[$key]) {
   if($key != $NIPageArray[$key]) {
    if(in_array($key, $DatesArray)) {

     /**
      * This is a date key. check against appropriate field.
      */

     if(strstr($key, "Day")) {

      /**
       * Day.
       */

      $CheckField = explode("_", $key);

      $checkDataArray = explode("-", $row[$CheckField[0]]);

      if($value != $checkDataArray[2]) {
       $logdata[$key] = array($checkDataArray[2],$value);
      }

     } elseif(strstr($key, "Month")) {

      /**
       * Month.
       */

      $checkDataArray = explode("-", $row[$CheckField[0]]);

      if($value != $checkDataArray[1]) {
       $logdata[$key] = array($checkDataArray[1],$value);
      }

     } elseif(strstr($key, "Year")) {

      /**
       * Year.
       */

      $checkDataArray = explode("-", $row[$CheckField[0]]);

      if($value != $checkDataArray[0]) {
       $logdata[$key] = array($checkDataArray[0],$value);
      }

     } elseif(strstr($key, "accountno")) {

      /**
       * Year.
       */
	  $aa = "Account Number Updated";
      if($value != $row['accountno']) {
       $logdata[$key] = array('', $aa);
      }

     }

    } else {
     $logdata[$key] = array($row[$key],$value);
    }
   }
  }
 }

 add_kpi($type,$row['memid'],$logdata);

}

function check_disabled($level = false) {

 if($level == "1") {

	 if(!checkmodule("EditMemberLevel1")) {

	  return " disabled";

	 }

 } elseif($level == "2") {

	 if(!checkmodule("EditMemberLevel2")) {

	  return " disabled";

	 }

 } else {

	  return " disabled";

 }

}

function do_notes_bold($Note) {

 if($_REQUEST['ViewNote']) {

  $Result = ($_REQUEST['ViewNote'] == $Note) ? "nav2" : "nav";

 } else {

  //$Result = ($_SESSION['User']['NoteType'] == $Note) ? "nav2" : "nav";
  $Result = ('1,2,3' == $Note) ? "nav2" : "nav";

 }

 return $Result;

}

 function UpdateLastEdit() {

  dbWrite("update members set lastedit = " . $_SESSION['User']['FieldID'] . " where memid = " . $_REQUEST['Client']);

 }

?>