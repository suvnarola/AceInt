<?

 /**
  * E Banc Trade Member Addition.
  *
  * member_add.php
  * Version 0.01
  */

 include("includes/modules/db.php");
 include("includes/class.html.mime.mail.inc");
 include("includes/modules/class.phpmailer.php");
?>
<html>
<head>
<title>E Banc Trade - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK REL="STYLESHEET" type="text/css" href="includes/styles.css">
<script language="javascript" type="text/javascript" src="includes/default.js?cache=no"></script>
<script LANGUAGE="JavaScript">
<!--

function ConfirmAdd() {
	bDelete = confirm("Are you sure you wish to add this member?");
	if (bDelete) {
		document.member_add.submit();
	} else {
	    return false;
	}
}

//-->
</script>
</head>
 <body onload="javascript:setFocus('member_add','abn');">
<form method="POST" action="body.php?page=member_add" name="member_add">
<?

 if($_REQUEST['newmem']) {
  $query = dbRead("select * from tbl_newmem where id='".addslashes($_GET['newmem'])."'");
  $memrow = mysql_fetch_assoc($query);
 } elseif($_REQUEST['edit2']) {

  $query = dbRead("select * from members where memid='".addslashes($_GET[memid])."'");
  $memrow = mysql_fetch_assoc($query);

  $memrow['signatories'] = "";
  $memrow['cat1'] = "0";
  $memrow['cat2'] = "0";
  $memrow['cat3'] = "0";
  $memrow['cat4'] = "0";
  $memrow['opt'] = "N";
  $memrow['reopt'] = "N";
  $memrow['datejoined'] = "";
  $memrow['datepacksent'] = "";
  $memrow['memusername'] = $row2[memusername]."2";
  $memrow['salesmanid'] = "9";
  $memrow['membershipfeepaid'] = "";
  $memrow['memshipfeepaytype'] = "";
  $memrow['overdraft'] = "0";
  $memrow['reoverdraft'] = "0";
  $memrow['lastedit'] = "";
  $memrow['wagesacc'] = "";
  $memrow['erewards'] = "0";
  $memrow['reward_bsb'] = "";
  $memrow['reward_accno'] = "";
  $memrow['reward_accname'] = "";
  $memrow['reward_sponsorship'] = "";
  $memrow['paymenttype'] = "";
  $memrow['accountname'] = "";
  $memrow['accountno'] = "";
  $memrow['expires'] = "";
  $memrow['refer_name'] = "";
  $memrow['refer_account'] = "";
  $memrow['transfeecash'] = "5.5";
  $memrow['referedby'] = "";
  $memrow['goldcard'] = "";
  $memrow['alltrades'] = "";
  $memrow['letters'] = "";
  $memrow['paid'] = "y";

 }


 if($_REQUEST['AddMember']) {

 $Errormsg = multi_check();

 if($Errormsg) {
  display_add($memrow, $Errormsg['Messages'], $Errormsg['Highlight']);
 } else {
  add_member();
 }

 } else {

  display_add($memrow);

 }

?>

  <script language="javascript">
   function samepostal() {

    if(document.member_add.SamePostal.checked == true) {

     document.member_add.postalno.value = document.member_add.streetno.value;
     document.member_add.postalname.value = document.member_add.streetname.value;
     document.member_add.postalsuburb.value = document.member_add.suburb.value;
     document.member_add.postalcity.value = document.member_add.city.value;
     document.member_add.postalstate.value = document.member_add.state.value;
     document.member_add.postalpostcode.value = document.member_add.postcode.value;

    } else {

     document.member_add.postalno.value = '<?= $memrow['postalno'] ?>';
     document.member_add.postalname.value = '<?= $memrow['postalname'] ?>';
     document.member_add.postalsuburb.value = '<?= $memrow['postalsuburb'] ?>';
     document.member_add.postalcity.value = '<?= $memrow['postalcity'] ?>';
     document.member_add.postalstate.value = '<?= $memrow['postalstate'] ?>';
     document.member_add.postalpostcode.value = '<?= $memrow['postalpostcode'] ?>';

    }

   }

   function samehome() {

    if(document.member_add.SameHome.checked == true) {

     document.member_add.homestreetno.value = document.member_add.streetno.value;
     document.member_add.homestreetname.value = document.member_add.streetname.value;
     document.member_add.homesuburb.value = document.member_add.suburb.value;
     document.member_add.homecity.value = document.member_add.city.value;
     document.member_add.homestate.value = document.member_add.state.value;
     document.member_add.homepostcode.value = document.member_add.postcode.value;

    } else {

     document.member_add.homestreetno.value = '<?= $memrow['homestreetno'] ?>';
     document.member_add.homestreetname.value = '<?= $memrow['homestreetname'] ?>';
     document.member_add.homesuburb.value = '<?= $memrow['homesuburb'] ?>';
     document.member_add.homecity.value = '<?= $memrow['homecity'] ?>';
     document.member_add.homestate.value = '<?= $memrow['homestate'] ?>';
     document.member_add.homepostcode.value = '<?= $memrow['homepostcode'] ?>';

    }

   }

  </script>
</form>

<?


 /**
  * Functions.
  */

 function multi_check() {

  if(strpos(strtolower(" ".$_REQUEST['webpageurl']), "http://")) {
   $Errormsg['Messages'] .= get_word("145")."<br>";
   $Errormsg['Highlight']['webpageurl'] = true;
  }

  if($_REQUEST['emailaddress'] && !validate_email($_REQUEST['emailaddress'])) {
   $Errormsg['Messages'] .= get_word("144")."<br>";
   $Errormsg['Highlight']['emailaddress'] = true;
  }

  if(!abs($_REQUEST['monthlyfeecash']) && !validate_email($_REQUEST['emailaddress'])) {
   $Errormsg['Messages'] .= get_word("146")."<br>";
   $Errormsg['Highlight']['emailaddress'] = true;
   $Errormsg['Highlight']['monthlyfeecash'] = true;
  }

  if(!$_REQUEST['regname']) {
   $Errormsg['Messages'] .= get_word("142")."<br>";
   $Errormsg['Highlight']['companyname'] = true;
  }

  if(!$_REQUEST['accholder']) {
   $Errormsg['Messages'] .= get_word("143")."<br>";
   $Errormsg['Highlight']['accholder'] = true;
  }

  if(!$_REQUEST['accholder_first']) {
   $Errormsg['Messages'] .= get_word("143")."<br>";
   $Errormsg['Highlight']['accholder_first'] = true;
  }

  if(!$_REQUEST['accholder_surname']) {
   $Errormsg['Messages'] .= get_word("143")."<br>";
   $Errormsg['Highlight']['accholder_surname'] = true;
  }

  if(!$_REQUEST['city']) {
   $Errormsg['Messages'] .=  get_word("141")."<br>";
   $Errormsg['Highlight']['city'] = true;
  }

  if(!$_REQUEST['postalcity']) {
   $Errormsg['Messages'] .= get_word("147")."<br>";
   $Errormsg['Highlight']['postalcity'] = true;
  }

  if(!$_REQUEST['salesmanid']) {
   $Errormsg['Messages'] .= get_word("212")."<br>";
   $Errormsg['Highlight']['salesmanid'] = true;
  }

  if($_SESSION['CountryPref_Members']['state_required'] == 1)  {
    if(!$_REQUEST['state']) {
     $Errormsg['Messages'] .= get_word("195")."<br>";
     $Errormsg['Highlight']['state'] = true;
    }
    if(!$_REQUEST['postalstate']) {
     $Errormsg['Messages'] .= get_word("196")."<br>";
     $Errormsg['Highlight']['postalstate'] = true;
    }
  }

  if($_SESSION['CountryPref_Members']['postcode_required'] == 1)  {
    if(!$_REQUEST['postcode']) {
     $Errormsg['Messages'] .= get_word("197")."<br>";
     $Errormsg['Highlight']['postcode'] = true;
    }
    if(!$_REQUEST['postalpostcode']) {
     $Errormsg['Messages'] .= get_word("198")."<br>";
     $Errormsg['Highlight']['postalcode'] = true;
    }
  }

  if($_REQUEST['wagesacc']) {
   $WageSQL = dbRead("select count(*) as Test from members where memid = '".$_REQUEST['wagesacc']."'");
   $WageRow = mysql_fetch_assoc($WageSQL);
   if($WageRow['Test'] < 1) {
    $Errormsg['Messages'] .= get_word("149")."<br>";
    $Errormsg['Highlight']['wagesacc'] = true;
   }
  }

  if($_REQUEST['status'] == 4) {
    if($_REQUEST['sponcat'] == 0) {
     $Errormsg['Messages'] .= get_word("203")."<br>";
     $Errormsg['Highlight']['sponcat'] = true;
    }
  }

  if($_REQUEST['datejoined_Day'] || $_REQUEST['datejoined_Month'] || $_REQUEST['datejoined_Year']) {
   $DateJoined = mktime(0,0,0,$_REQUEST['datejoined_Month'],$_REQUEST['datejoined_Day'],$_REQUEST['datejoined_Year']);
   if($DateJoined > (mktime()+$_SESSION['Country']['timezone'])) {
    $Errormsg['Messages'] .= get_word("150")."<br>";
    $Errormsg['Highlight']['datejoined'] = true;
   }
  }

  if($_REQUEST['referedby']) {
   $RefSQL = dbRead("select count(*) as Test from members where memid = '".$_REQUEST['referedby']."'");
   $RefRow = mysql_fetch_assoc($RefSQL);
   if($RefRow['Test'] < 1) {
    $Errormsg['Messages'] .= get_word("158")."<br>";
    $Errormsg['Highlight']['referedby'] = true;
   }
  }

  if($_REQUEST['paymenttype'] == 1 || $_REQUEST['paymenttype'] == 4 || $_REQUEST['paymenttype'] == 6 || $_REQUEST['paymenttype'] == 7) {

   $ExpireArray = explode("/", $_REQUEST['expires']);

   if($ExpireArray[0] < date("m") and $ExpireArray[1] <= date("y")) {
    $Errormsg['Messages'] .= get_word("151")."<br>";
    $Errormsg['Highlight']['expires'] = true;
   }

   if(!$_REQUEST['accountname']) {
    $Errormsg['Messages'] .= get_word("152")."<br>";
    $Errormsg['Highlight']['accountname'] = true;
   }

   if(!$_REQUEST['accountno']) {
    $Errormsg['Messages'] .= get_word("153")."<br>";
    $Errormsg['Highlight']['accountno'] = true;
   }

  }

  if($_REQUEST['refer_account'] || $_REQUEST['refer_name']) {

   if(!$_REQUEST['refer_name']) {
    $Errormsg['Messages'] .= get_word("154")."<br>";
    $Errormsg['Highlight']['refer_name'] = true;
   }

   if(!$_REQUEST['refer_account']) {
    $Errormsg['Messages'] .= get_word("153")."<br>";
    $Errormsg['Highlight']['refer_account'] = true;
   }

   if($_SESSION['Country']['countryID'] == 1) {

	   if(!strstr($_REQUEST['refer_account'], ",")) {
	    $Errormsg['Messages'] .= get_word("155")."<br>";
	    $Errormsg['Highlight']['refer_account'] = true;
	   }

	   $BSBArray = @explode(",", $_REQUEST['refer_account']);

	   if(strlen($BSBArray[0]) != 6) {
	    $Errormsg['Messages'] .= get_word("156")."<br>";
	    $Errormsg['Highlight']['refer_account'] = true;
	   }

   } elseif($_SESSION['Country']['countryID'] == 15) {

	   if(strlen($_REQUEST['refer_account']) != 12) {
	    $Errormsg['Messages'] .= get_word("156")."<br>";
	    $Errormsg['Highlight']['refer_account'] = true;
	   }

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

	   if(strlen($_REQUEST['accountno']) != 12) {
	    $Errormsg['Messages'] .= get_word("156")."<br>";
	    $Errormsg['Highlight']['accountno'] = true;
	   }

   }

  }

  if($_REQUEST['erewards']) {

   if($_REQUEST['reward_no'] || $_REQUEST['reward_bsb'] || $_REQUEST['reward_name']) {

    if(!$_REQUEST['reward_accname']) {
     $Errormsg['Messages'] .= get_word("157")."<br>";
     $Errormsg['Highlight']['reward_accname'] = true;
    }

    if(!$_REQUEST['reward_accno']) {
     $Errormsg['Messages'] .= get_word("153")."<br>";
     $Errormsg['Highlight']['reward_accno'] = true;
    }

    if(strlen($_REQUEST['reward_bsb']) != 6) {
     $Errormsg['Messages'] .= get_word("156")."<br>";
     $Errormsg['Highlight']['reward_no'] = true;
    }

   }

  }

  if(!$_REQUEST['description1']) {
    $Errormsg['Messages'] .= get_word("159")."<br>";
    $Errormsg['Highlight']['description1'] = true;
  }

  if($_REQUEST['description1'] && $_SESSION['Country']['english'] == 'N') {
   if(!$_REQUEST['engdesc1']) {
    $Errormsg['Messages'] .= get_word("159")."<br>";
    $Errormsg['Highlight']['engdesc1'] = true;
   }
  }

  if($_REQUEST['description2'] && $_SESSION['Country']['english'] == 'N') {
   if(!$_REQUEST['engdesc2']) {
    $Errormsg['Messages'] .= get_word("159")."<br>";
    $Errormsg['Highlight']['engdesc2'] = true;
   }
  }

  if($_REQUEST['description3'] && $_SESSION['Country']['english'] == 'N') {
   if(!$_REQUEST['engdesc3']) {
    $Errormsg['Messages'] .= get_word("159")."<br>";
    $Errormsg['Highlight']['engdesc3'] = true;
   }
  }

  if($_REQUEST['description4'] && $_SESSION['Country']['english'] == 'N') {
   if(!$_REQUEST['engdesc4']) {
    $Errormsg['Messages'] .= get_word("159")."<br>";
    $Errormsg['Highlight']['engdesc4'] = true;
   }
  }

  return $Errormsg;

 }

 function change_colour($field,$ErrorArray) {

  if($ErrorArray[$field]) {
   return "#FF0000";
  } else {
   return "#FFFFFF";
  }

 }

 function generate_username($memID) {

	$newUsername = $_SESSION['Country']['countryID'] . $memID . mt_rand(10,99);

  	return $newUsername;

 }

 function which_data($row,$field,$error = false) {

  if($error) {
   return $_REQUEST[$field];
  } else {
   //return $row[$field];   //return preg_replace('/\\\\/', '', htmlspecialchars($row[$field], ENT_QUOTES, "UTF-8"));   return get_all_added_characters($row[$field]);
  }

 }

 function which_date_data($row,$field,$error = false) {

  if($error) {
   return $_REQUEST[$field."_Year"]."-".$_REQUEST[$field."_Month"]."-".$_REQUEST[$field."_Day"];
  } else {
   return $row[$field];
  }

 }

 function print_error($Errormsg = false) {

  if($Errormsg) {

   ?>
   <table width="640" border="1" bordercolor="#FF0000" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
    <tr>
     <td bgcolor="#FFFFFF" align="center"><?= $Errormsg ?>&nbsp;</td>
    </tr>
   </table><br>
   <?

  }

 }

 function add_member() {

  $GoldCard = $_REQUEST['goldcard'] ? 1 : 0;
  $ReOPT = ($_REQUEST['reopt'] == "N") ? "N" : "Y";
  $OPT = ($_REQUEST['opt'] == "N") ? "N" : "Y";

  //if($_REQUEST['emailaddress'] && !$_REQUEST['email_accounts']) {
   //$_REQUEST['email_accounts'] = $_REQUEST['emailaddress'];
  //}

  $SQL = new dbCreateSQL();

  $SQL->add_table("members");

  $SQL->add_item("regname", encode_text2($_REQUEST['regname']));
  $SQL->add_item("displayname", encode_text2($_REQUEST['displayname']));
  $SQL->add_item("accholder", encode_text2($_REQUEST['accholder']));
  $SQL->add_item("accholder_first", encode_text2($_REQUEST['accholder_first']));
  $SQL->add_item("accholder_surname", encode_text2($_REQUEST['accholder_surname']));
  $SQL->add_item("signatories", encode_text2($_REQUEST['signatories']));
  $SQL->add_item("abn", encode_text2($_REQUEST['abn']));
  $SQL->add_item("streetno", encode_text2($_REQUEST['streetno']));
  $SQL->add_item("streetname", encode_text2($_REQUEST['streetname']));
  $SQL->add_item("state", encode_text2($_REQUEST['state']));
  $SQL->add_item("postcode", encode_text2($_REQUEST['postcode']));
  $SQL->add_item("suburb", encode_text2($_REQUEST['suburb']));
  $SQL->add_item("city", encode_text2($_REQUEST['city']));
  $SQL->add_item("homestreetno", encode_text2($_REQUEST['homestreetno']));
  $SQL->add_item("homestreetname", encode_text2($_REQUEST['homestreetname']));
  $SQL->add_item("homestate", encode_text2($_REQUEST['homestate']));
  $SQL->add_item("homepostcode", encode_text2($_REQUEST['homepostcode']));
  $SQL->add_item("homesuburb", encode_text2($_REQUEST['homesuburb']));
  $SQL->add_item("homecity", encode_text2($_REQUEST['homecity']));
  $SQL->add_item("postalno", encode_text2($_REQUEST['postalno']));
  $SQL->add_item("postalname", encode_text2($_REQUEST['postalname']));
  $SQL->add_item("postalstate", encode_text2($_REQUEST['postalstate']));
  $SQL->add_item("postalpostcode", encode_text2($_REQUEST['postalpostcode']));
  $SQL->add_item("postalcity", encode_text2($_REQUEST['postalcity']));
  $SQL->add_item("postalsuburb", encode_text2($_REQUEST['postalsuburb']));
  $SQL->add_item("postalstate", encode_text2($_REQUEST['postalstate']));
  $SQL->add_item("phonearea", encode_text2($_REQUEST['phonearea']));
  $SQL->add_item("phoneno", encode_text2($_REQUEST['phoneno']));
  $SQL->add_item("faxno", encode_text2($_REQUEST['faxno']));
  $SQL->add_item("faxarea", encode_text2($_REQUEST['faxarea']));
  $SQL->add_item("homephone", encode_text2($_REQUEST['homephone']));
  $SQL->add_item("homephonearea", encode_text2($_REQUEST['homephonearea']));
  $SQL->add_item("mobile", encode_text2($_REQUEST['mobile']));
  $SQL->add_item("sms", encode_text2($_REQUEST['sms']));
  $SQL->add_item("pin", encode_text2($_REQUEST['pin']));
  //$SQL->add_item("emailaddress", encode_text2($_REQUEST['emailaddress']));
  $SQL->add_item("webpageurl", encode_text2($_REQUEST['webpageurl']));
  $SQL->add_item("accountname", encode_text2($_REQUEST['accountname']));
  $SQL->add_item("reward_accname", encode_text2($_REQUEST['reward_accname']));

  $SQL->add_item("banked", $_REQUEST['banked_Year']."-".$_REQUEST['banked_Month']."-".$_REQUEST['banked_Day']);
  $SQL->add_item("DOBholder", $_REQUEST['DOBholder_Year']."-".$_REQUEST['DOBholder_Month']."-".$_REQUEST['DOBholder_Day']);
  $SQL->add_item("DOBcontact", $_REQUEST['DOBcontact_Year']."-".$_REQUEST['DOBcontact_Month']."-".$_REQUEST['DOBcontact_Day']);
  $SQL->add_item("mempassword", encode_text2("etb".mt_rand(10000,99999)));
  $SQL->add_item("goldcard", $GoldCard);
  $SQL->add_item("reopt", $ReOPT);
  $SQL->add_item("opt", $OPT);
  $SQL->add_item("abn", $_REQUEST['abn']);
  $SQL->add_item("status", $_REQUEST['status']);
  $SQL->add_item("gst", $_REQUEST['gst']);
  $SQL->add_item("CID", $_SESSION['User']['CID']);
  $SQL->add_item("bdriven", $_REQUEST['bdriven']);
  $SQL->add_item("paymenttype", $_REQUEST['paymenttype']);
  $SQL->add_item("erewards", $_REQUEST['erewards']);
  $SQL->add_item("reward_accno", $_REQUEST['reward_accno']);
  $SQL->add_item("reward_bsb", $_REQUEST['reward_bsb']);
  $SQL->add_item("reward_sponsorship", $_REQUEST['reward_sponsorship']);
  if($_REQUEST['erewards'] == "9" || $_REQUEST['erewards'] == "3") {
   $SQL->add_item("reward_datejoined", date("Y-m-d"));
  }
  $SQL->add_item("transfeecash", $_REQUEST['transfeecash']);
  $SQL->add_item("monthlyfeecash", $_REQUEST['monthlyfeecash']);
  $SQL->add_item("referedby", $_REQUEST['referedby']);
  $SQL->add_item("refer_name", $_REQUEST['refer_name']);
  $SQL->add_item("refer_account", $_REQUEST['refer_account']);
  $SQL->add_item("area", $_REQUEST['area']);
  $SQL->add_item("salesmanid", $_REQUEST['salesmanid']);
  $SQL->add_item("accountno", $_REQUEST['accountno']);
  $SQL->add_item("expires", $_REQUEST['expires']);
  $SQL->add_item("membershipfeepaid", $_REQUEST['membershipfeepaid']);
  $SQL->add_item("trade_membership", $_REQUEST['trade_membership']);
  $SQL->add_item("memshipfeepaytype", $_REQUEST['memshipfeepaytype']);
  $SQL->add_item("salesmanpaid", $_REQUEST['salesmanpaid']);
  $SQL->add_item("feescharge", "Sell");
  $SQL->add_item("licensee", $_REQUEST['licensee']);
  $SQL->add_item("lastedit", $_SESSION['User']['FieldID']);
  $SQL->add_item("wagesacc", $_REQUEST['wagesacc']);
  $SQL->add_item("sponcat", $_REQUEST['sponcat']);
  $SQL->add_item("accept", $_REQUEST['accept']);
  $SQL->add_item("admin_exempt", $_REQUEST['admin_exempt']);

  if($_REQUEST['abn']) {
   $SQL->add_item("supply_statement", 3);
  } else {
   $SQL->add_item("supply_statement", $_REQUEST['supply_statement']);
  }

  if(!$_REQUEST['contactname']) {
   $SQL->add_item("contactname", encode_text2($_REQUEST['accholder']));
  } else {
   $SQL->add_item("contactname", encode_text2($_REQUEST['contactname']));
  }

  if(!$_REQUEST['companyname']) {
   $SQL->add_item("companyname", encode_text2($_REQUEST['regname']));
  } else {
   $SQL->add_item("companyname", encode_text2($_REQUEST['companyname']));
  }

  if(!$_REQUEST['datejoined_Day'] || !$_REQUEST['datejoined_Month'] || !$_REQUEST['datejoined_Year']) {
   $SQL->add_item("datejoined", date("Y-m-d"));
  } else {
   $SQL->add_item("datejoined", $_REQUEST['datejoined_Year']."-".$_REQUEST['datejoined_Month']."-".$_REQUEST['datejoined_Day']);
  }

  $Memid = dbWrite($SQL->get_sql_insert(),"etradebanc",true);

  $memUsername = generate_username($Memid);

  dbWrite("update members set memusername = '" . $memUsername . "' where memid = " . $Memid);

  $memQuery = dbRead("select members.* from members where memid = " . $Memid);
  $creditRow = mysql_fetch_object($memQuery);

  //dbWrite("insert into PHPAUCTIONXL_users (id,nick,password,name,address,city,country,zip,phone,email) values ('" . addslashes($creditRow->memid) . "','" . addslashes($creditRow->memusername) . "','" . md5("lostit" . addslashes($creditRow->mempassword)) . "','" . addslashes($creditRow->contactname) . "','" . addslashes($creditRow->streetno) . " " . addslashes($creditRow->streetname) . "','" . addslashes($creditRow->city) . "','" . addslashes($creditRow->name) . "','" . addslashes($creditRow->postcode) . "','" . addslashes($creditRow->phonearea) . " " . addslashes($creditRow->phoneno) . "','" . addslashes($creditRow->email) . "')", "empireAuction");

  //if($_REQUEST['emailaddress']) {
   dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','1','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
   dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','2','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
   dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','3','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
   dbWrite("insert into tbl_members_email (acc_no,type,email) values ('$Memid','4','".addslashes(encode_text2($_REQUEST['emailaddress']))."')");
 //}

  if($_REQUEST['cat1']) {
   dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','".addslashes($_REQUEST['cat1'])."','".addslashes(encode_text2($_REQUEST['description1']))."','".addslashes(encode_text2($_REQUEST['engdesc1']))."')");
  } else {
   if($_REQUEST['description1']) {
    dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','0','".addslashes(encode_text2($_REQUEST['description1']))."','".addslashes(encode_text2($_REQUEST['engdesc1']))."')");
   }
  }

  if($_REQUEST['cat2']) {
   dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','".addslashes($_REQUEST['cat2'])."','".addslashes(encode_text2($_REQUEST['description2']))."','".addslashes(encode_text2($_REQUEST['engdesc2']))."')");
  } else {
   if($_REQUEST['description2']) {
    dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','0','".addslashes(encode_text2($_REQUEST['description2']))."','".addslashes(encode_text2($_REQUEST['engdesc2']))."')");
   }
  }

  if($_REQUEST['cat3']) {
   dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','".addslashes($_REQUEST['cat3'])."','".addslashes(encode_text2($_REQUEST['description3']))."','".addslashes(encode_text2($_REQUEST['engdesc3']))."')");
  } else {
   if($_REQUEST['description3']) {
    dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','0','".addslashes(encode_text2($_REQUEST['description3']))."','".addslashes(encode_text2($_REQUEST['engdesc3']))."')");
   }
  }

  if($_REQUEST['cat4']) {
   dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','".addslashes($_REQUEST['cat4'])."','".addslashes(encode_text2($_REQUEST['description4']))."','".addslashes(encode_text2($_REQUEST['engdesc4']))."')");
  } else {
   if($_REQUEST['description4']) {
    dbWrite("insert into mem_categories (memid,category,description,engdesc) values ('$Memid','0','".addslashes(encode_text2($_REQUEST['description4']))."','".addslashes(encode_text2($_REQUEST['engdesc4']))."')");
   }
  }

  if(checkmodule("Log")) {
   add_kpi("12",$Memid);
  }

  if($_REQUEST['erewards'] == 9 || $_REQUEST['erewards'] == 3) {
   if($_REQUEST['referedby'] != 0) {
    add_referal($_REQUEST['referedby'],$Memid);
   }
  }

  //dbWrite("insert into feesowing values ('$Memid','0')");

  if($_REQUEST['refer_account']) {
  	$addressArray[] = array('michelle.d@au.empirexchange.com', 'Michelle');
  	sendEmail("accounts@" . $_SESSION[Country][countrycode] ."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), "Members Membership Refer Bonus", 'accounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray, $attachArray);
  }

  display_receipt($Memid);

 }

 function display_receipt($Memid) {

  $MemRowSQL = dbRead("select members.* from members where memid = '".$Memid."'");
  $MemRow = mysql_fetch_assoc($MemRowSQL);

  $EmaRowSQL = dbRead("select * from tbl_members_email where acc_no = '".$Memid."' and type = 3");
  $EmaRow = mysql_fetch_assoc($EmaRowSQL);

  if($_REQUEST['goldcard']) {
   $gll = "Yes";
  } else {
   $gll = "No";
  }

  if($_REQUEST['opt'] == "N") {
   $optt = "No";
  } else {
   $optt = "Yes";
  }

  if($_REQUEST['reopt'] == "N") {
   $reoptt = "No";
  } else {
   $reoptt = "Yes";
  }

  if($_REQUEST['memshipfeepaytype']) {
   $query4 = dbRead("select * from tbl_admin_payment_types where FieldID = '".$_REQUEST['memshipfeepaytype']."'");
   $row4 = mysql_fetch_array($query4);
  }

  if($_REQUEST['paymenttype']) {
   $query5 = dbRead("select * from tbl_admin_payment_types where FieldID = '".$_REQUEST['paymenttype']."'");
   $row5 = mysql_fetch_array($query5);
  }

  if($_REQUEST['supply_statement'] == 1) {
   $supply = "Pending Supplier Statement";
  } elseif($_REQUEST['supply_statement'] == 2) {
   $supply = "Supplier Statement Received";
  } elseif($_REQUEST['supply_statement'] == 3) {
   $supply = "ABN Provided";
  }

  $query = dbRead("select * from status where FieldID = '".$_REQUEST['status']."'");
  $statusrow = mysql_fetch_array($query);

  ?>
    <table width="600" cellpadding="1" cellspacing="0" border="0">
    <tr>
    <td class="Border">
    <table border="0" width="100%" cellspacing="0" cellpadding="3">
      <tr>
        <td colspan="2" align="center" class="Heading"><b><?= get_page_data("3") ?></b></td>
      </tr>
      <tr>
        <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("91") ?></b></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2" width="30%"><b><?= get_word("50") ?>:</b></td>
        <td bgcolor="#FFFFFF"><b><?= $Memid ?></b></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("2") ?>:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['regname'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("3") ?>:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['companyname'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("67") ?>:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['displayname'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("4") ?>:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['accholder'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("4") ?> Firstname:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['accholder_first'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("4") ?> Surname:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['accholder_surname'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("5") ?>:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['contactname'] ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" class="Heading2"><b><?= get_word("6") ?>:</b></td>
        <td bgcolor="#FFFFFF"><?= $MemRow['signatories'] ?></td>
      </tr>
      <tr>
    	<td align="right" valign="middle" class="Heading2"><b><?= get_word("30") ?>:</b></td>
    	<td bgcolor="#FFFFFF"><?= $MemRow['abn'] ?></td>
	  </tr>
      <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("75") ?>:</b></td>
		<td bgcolor="#FFFFFF"><?= $MemRow['gst'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("69") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['datejoined'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("9") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $EmaRow['email'] ?></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("21") ?>:</b></td>
		<td bgcolor="#FFFFFF"><?= $optt ?></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("22") ?>:</b></td>
		<td bgcolor="#FFFFFF"><?= $reoptt ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("28") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['webpageurl'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("11") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['homephonearea'] ?>&nbsp;<?= $MemRow['homephone'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("7") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['phonearea'] ?>&nbsp;<?= $MemRow['phoneno'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("8") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['faxarea'] ?>&nbsp;<?= $MemRow['faxno'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("10") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['mobile'] ?></td>
	  </tr>
       <?if($_SESSION[Country][countryID] == 12)  {?>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("208") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['sms'] ?></td>
	  </tr>
	  <?}?>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("32") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><b><?= $MemRow['memusername'] ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("33") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><b><?= $MemRow['mempassword'] ?></b></td>
	  </tr>
	  <TR>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("24") ?>:</b></td>
	    <td bgcolor="#FFFFFF">
	    <?

		$query = dbRead("select AreaName from tbl_area_physical  where FieldID = '".$MemRow['area']."'");
		$row = mysql_fetch_assoc($query);

	    print $row['AreaName'];

	    ?>
	    </td>
	  </TR>
	  <TR>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("25") ?>:</b></td>
	    <td bgcolor="#FFFFFF">
	    <?

		$query = dbRead("select place from area where FieldID = '".$MemRow['licensee']."'");
		$row = mysql_fetch_assoc($query);

	    print $row['place'];

	    ?>
	    </td>
	  </TR>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("12") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $statusrow['Name'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("50") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['wagesacc'] ?></td>
	  </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("26") ?></b></td>
     </tr>
     <?
          $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$MemRow['memid']."' order by mem_categories.FieldID");
          while($catrow = mysql_Fetch_assoc($query)) {

           ?>
            <tr>
             <td class="Heading2" align="right" rowspan="2" valign="top"><?= $catrow[category] ?></td>
             <td bgcolor="#FFFFFF" align="left"><?= $catrow[description] ?></td>
            </tr>
            <tr>
             <td bgcolor="#FFFFFF" align="left"><? print $catrow[engdesc]; ?><br></td>
            </tr>
           <?

          }
     ?>
	     <tr>
  		<td align="right" valign="middle" class="Heading2"><b><?= get_word("23") ?>:</b></td>
  		<td bgcolor="#FFFFFF"><?= $MemRow['bdriven'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("92") ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("13") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['streetno'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("14") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['streetname'] ?></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("15") ?>:</b></td>
		<td bgcolor="#FFFFFF"><?= $MemRow['suburb'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("16") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['city'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("17") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['state'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("18") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['postcode'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b>Home Address</b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("228") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['homestreetno'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("229") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['homestreetname'] ?></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("230") ?>:</b></td>
		<td bgcolor="#FFFFFF"><?= $MemRow['homesuburb'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("232") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['homecity'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("233") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['homestate'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("234") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['homrpostcode'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("93") ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("19") ?> <?= get_word("13") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['postalno'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("19") ?> <?= get_word("14") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['postalname'] ?></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("19") ?> <?= get_word("15") ?>:</b></td>
		<td bgcolor="#FFFFFF"><?= $MemRow['postalsuburb'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("19") ?> <?= get_word("16") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['postalcity'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("19") ?> <?= get_word("17") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['postalstate'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("19") ?> <?= get_word("18") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['postalpostcode'] ?></td>
	  </tr>
	  <tr>
		<td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("95") ?></b></td>
	  </tr>
      <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("34") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?

	     //$SalesSQL = dbRead("select * from salespeople where salesmanid = ".$MemRow['salesmanid']."");
	     $SalesSQL = dbRead("select * from tbl_admin_users where FieldID = ".$MemRow['salesmanid']."");
	     $SalesRow = mysql_fetch_assoc($SalesSQL);

		 print $SalesRow['Name'];

		?></td>
      </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("60") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['membershipfeepaid'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b>Net Trade % in above <?= get_word("60") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['trade_membership'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("59") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $row4[Type] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("74") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['banked'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("96") ?></b></td>
	  </tr>
      <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("58") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $row5['Type'] ?></td>
	  </tr>
      <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("62") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['accountname'] ?></td>
	  </tr>
      <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("63") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['accountno'] ?></td>
	  </tr>
      <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("64") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['expires'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("90") ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("90") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= erewards_text($MemRow) ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("100") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?

	     $SupplySQL = dbRead("select * from tbl_admin_supplier_abn where FieldID = ".$MemRow['supply_statement']."");
	     $SupplyRow = mysql_fetch_assoc($SupplySQL);

		 print $SupplyRow['Type'];

		?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("99") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['reward_bsb'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("63") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['reward_accno'] ?></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("62") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['reward_accname'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("97") ?> %</b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b>%:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['transfeecash'] ?>&nbsp;</td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("98") ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("61") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['monthlyfeecash'] ?>&nbsp;</td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("49") ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("49") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['pin'] ?></td>
	  </tr>
	  <tr>
	    <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("65") ?></b></td>
	  </tr>
	  <tr>
	    <td align="right" valign="middle" class="Heading2"><b><?= get_word("50") ?>:</b></td>
	    <td bgcolor="#FFFFFF"><?= $MemRow['referedby'] ?></td>
	  </tr>
	  <tr>
		<td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("37") ?></b></td>
	  </tr>
	  <tr>
		<td align="right" valign="middle" class="Heading2"><b><?= get_word("37") ?>:</b></td>
		<td bgcolor="FFFFFF"><?= $gll ?></td>
	  </tr>
	</table>
	</td>
	</tr>
	</table>
  <?

 }

 function display_add($row = false, $ErrorMsg = false, $ErrorArray = false) {

  print_error($ErrorMsg);

  ?>
  <input type="hidden" name="AddMember" value="1">
  <table border="0" cellspacing="1" cellpadding="1">
   <tr>
    <td class="Border">
     <table border="0" width="600" cellspacing="0" cellpadding="3">
      <tr>
       <td colspan="2" align="center" class="Heading"><b><?= get_page_data("1") ?></b></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("91") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("29") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="abn" size="<?= $_SESSION['CountryPref_Members']['abn_limit']?>"  maxlength="<?= $_SESSION['CountryPref_Members']['abn_limit']?>" value="<?= which_data($row, "abn", $ErrorMsg) ?>" <? if ($_SESSION['CountryPref_Members']['abn_no']) {?>onKeyPress="return number(event)"<?}?>></td>
     </tr>
     <tr>
       <td align="right" valign="middle" class="Heading2" width="30%"><b><?= get_word("30") ?>:</b></td>
       <td bgcolor="#FFFFFF" width="70%"><select size="1" name="gst">
        <option <? if(which_data($row, "gst", $ErrorMsg) == "N") { echo "selected "; }?>value="N">
        No</option>
        <option <? if(which_data($row, "gst", $ErrorMsg) == "Y") { echo "selected "; }?>value="Y">
        Yes</option>
        </select>
       </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("2") ?>:</b></td>
      <td bgcolor="<?= change_colour("regname", $ErrorArray) ?>"><input type="text" name="regname" size="30" maxlength="80" value="<?= which_data($row, "regname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("3") ?>:</b></td>
      <td bgcolor="<?= change_colour("companyname", $ErrorArray) ?>"><input type="text" name="companyname" size="30" maxlength="80" value="<?= which_data($row, "companyname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
       <td align="right" valign="middle" class="Heading2" width="30%"><b><?= get_word("67") ?>:</b></td>
       <td bgcolor="#FFFFFF" width="70%"><select size="1" name="displayname">
        <option <? if(which_data($row, "companyname", $ErrorMsg) == "comapnyname") { echo "selected "; }?>value="companyname">
        Trading As</option>
        <option <? if(which_data($row, "regname", $ErrorMsg) == "regname") { echo "selected "; }?>value="regname">
        Reg Name</option>
        </select>
       </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("4") ?>:</b></td>
      <td bgcolor="<?= change_colour("accholder", $ErrorArray) ?>"><input type="text" name="accholder" size="30" maxlength="80" value="<?= which_data($row, "accholder", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("4") ?> Firstname:</b></td>
      <td bgcolor="<?= change_colour("accholder_first", $ErrorArray) ?>"><input type="text" name="accholder_first" size="30" maxlength="80" value="<?= which_data($row, "accholder_first", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("4") ?> Surname:</b></td>
      <td bgcolor="<?= change_colour("accholder_surname", $ErrorArray) ?>"><input type="text" name="accholder_surname" size="30" maxlength="80" value="<?= which_data($row, "accholder_surname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><B><?= get_word("4") ?> DOB:</B></td>
      <td bgcolor="FFFFFF"><?= do_date("DOBholder",which_date_data($row,"DOBholder",$ErrorMsg),true); ?></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("5") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="contactname" size="30" maxlength="80" value="<?= which_data($row, "contactname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><B><?= get_word("5") ?> DOB:</B></td>
      <td bgcolor="FFFFFF"><?= do_date("DOBcontact",which_date_data($row,"DOBcontact",$ErrorMsg),true); ?></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("6") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="signatories" size="20" maxlength="80" value="<?= which_data($row, "signatories", $ErrorMsg) ?>"></td>
     </tr>
     <TR>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("9") ?>:</b></td>
      <td bgcolor="<?= change_colour("emailaddress", $ErrorArray) ?>"><input type="text" name="emailaddress" size="30" maxlength="80" value="<?= which_data($row, "emailaddress", $ErrorMsg) ?>"></td>
     </TR>
     <tr>
      <td align="right" valign="middle" class="Heading2"><B><?= get_word("69") ?>:</B></td>
      <td bgcolor="<?= change_colour("datejoined", $ErrorArray) ?>"><?= do_date("datejoined",which_date_data($row,"datejoined",$ErrorMsg)); ?></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2" width="30%"><b><?= get_word("21") ?>:</b></td>
      <td bgcolor="#FFFFFF" width="70%"><select size="1" name="opt">
       <option <? if(which_data($row, "opt", $ErrorMsg) == "Y") { echo "selected "; }?>value="Y">
       Yes</option>
       <option <? if(which_data($row, "opt", $ErrorMsg) == "N") { echo "selected "; }?>value="N">
       No</option>
       </select>
      </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2" width="30%"><b><?= get_word("22") ?>:</b></td>
      <td bgcolor="#FFFFFF" width="70%"><select size="1" name="reopt">
       <option <? if(which_data($row, "reopt", $ErrorMsg) == "Y") { echo "selected "; }?>value="Y">
       Yes</option>
       <option <? if(which_data($row, "reopt", $ErrorMsg) == "N") { echo "selected "; }?>value="N">
       No</option>
       </select>
      </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("28") ?>:</b></td>
      <td bgcolor="<?= change_colour("webpageurl", $ErrorArray) ?>"><input type="text" name="webpageurl" size="30" maxlength="80" value="<?= which_data($row, "webpageurl", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("7") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="phonearea" size="5" maxlength="15" value="<?= which_data($row, "phonearea", $ErrorMsg) ?>" onKeyPress="return number(event)"><input type="text" name="phoneno" size="16" maxlength="15" value="<?= which_data($row, "phoneno", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("8") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="faxarea" size="5" maxlength="15" value="<?= which_data($row, "faxarea", $ErrorMsg) ?>" onKeyPress="return number(event)"><input type="text" name="faxno" size="16" maxlength="15" value="<?= which_data($row, "faxno", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("11") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="homephonearea" size="5" maxlength="15" value="<?= which_data($row, "homephonearea", $ErrorMsg) ?>" onKeyPress="return number(event)"><input type="text" name="homephone" size="16" maxlength="15" value="<?= which_data($row, "homephone", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("10") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="mobile" size="20" maxlength="15" value="<?= which_data($row, "mobile", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <?if($_SESSION[Country][countryID] == 12)  {?>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("208") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="sms" size="20" maxlength="50" value="<?= which_data($row, "sms", $ErrorMsg) ?>" ></td>
     </tr>
     <?}?>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("24") ?>:</b></td>
      <td bgcolor="FFFFFF"><select size="1" name="area">
       <?

		//$query1 = dbRead("select FieldID, place from area where CID like '".$_SESSION['User']['CID']."' order by place ASC");
          $query11 = dbRead("select tbl_area_physical.FieldID as FieldID, AreaName from tbl_area_physical, tbl_area_regional, tbl_area_states where (tbl_area_physical.RegionalID = tbl_area_regional.FieldID) and (tbl_area_regional.StateID = tbl_area_states.FieldID) and tbl_area_states.CID = '".$_SESSION['User']['CID']."' order by AreaName");
		while($row2 = mysql_fetch_assoc($query11)) {
		 ?>
		  <option value="<?= $row2['FieldID'] ?>"<? if(which_data($row, "area", $ErrorMsg) == $row2['FieldID']) { echo " selected"; } ?>><?= $row2['AreaName'] ?></option>
		 <?
		}

	   ?>
	   </select>
	  </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("25") ?>:</b></td>
      <td bgcolor="FFFFFF"><select size="1" name="licensee">
       <?

		$query1 = dbRead("select FieldID, place from area where CID like '".$_SESSION['User']['CID']."' and `drop` = 'Y' order by place ASC");
		while($row2 = mysql_fetch_assoc($query1)) {
		 ?>
		  <option value="<?= $row2['FieldID'] ?>"<? if(which_data($row, "licensee", $ErrorMsg) == $row2['FieldID']) { echo " selected"; } ?>><?= $row2['place'] ?></option>
		 <?
		}

	   ?>
	   </select>
	  </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("12") ?>:</b></td>
      <td bgcolor="FFFFFF"><select size="1" name="status">
       <?

        $query = dbRead("select * from status where FieldID != 1 order by Name");
        while($row2 = mysql_fetch_assoc($query)) {

        ?>
	     <option value="<?= $row2['FieldID'] ?>"><?= $row2['Name'] ?></option>
	    <?

         }

       ?>

	   </select>
	  </td>
	 </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("31") ?>:</b></td>
      <td bgcolor="<?= change_colour("wagesacc", $ErrorArray) ?>"><input type="text" name="wagesacc" size="20" value="<?= which_data($row, "wagesacc", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
        <td class="Heading2" align="right" nowrap><?= get_word("203") ?>:</td>
        <td bgcolor="#FFFFFF" align="left"><select size="1" name="sponcat"><option value='0'></option>
       <?

            $clasquery = dbRead("select * from spon_cats order by category");
            while($clasrow = mysql_fetch_assoc($clasquery)) {
		 ?>
		  <option value="<?= $clasrow['fieldid'] ?>"<? if(which_data($row, "sponcat", $ErrorMsg) == $clasrow['fieldid']) { echo " selected"; } ?>><?= $clasrow['category'] ?></option>
		 <?
		}

	   ?>
	   </select>
	  </td>
    </tr>

     <tr>
      <td align="right" valign="middle" class="Heading2" width="30%"><b><?= get_word("23") ?>:</b></td>
      <td bgcolor="#FFFFFF" width="70%"><select size="1" name="bdriven">
       <option <? if(which_data($row, "bdriven", $ErrorMsg) == "N") { echo "selected "; }?>value="N">
       No</option>
       <option <? if(which_data($row, "bdriven", $ErrorMsg) == "Y") { echo "selected "; }?>value="Y">
       Yes</option>
       </select>
      </td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("26") ?> 1</b></td>
     </tr>
     <tr>
      <td align="right" valign="top" class="Heading2"><b><?= get_word("26") ?>:<br><br>
      <?= get_word("27") ?>:<br><br><br>English:<br><? if($_SESSION['Country']['english'] == "Y") { print $English; } ?></td>
      <td bgcolor="<?= change_colour("description1", $ErrorArray) ?>"><select size="1" name="cat1"><option value='0'></option>
       <?

		$query1 = dbRead("select catid, category from categories where display_drop = 'Y' and CID like '".$_SESSION['User']['CID']."' or CID='0' order by category ASC");
		while($row2 = mysql_fetch_assoc($query1)) {
		 ?>
		  <option value="<?= $row2['catid'] ?>"<? if(which_data($row, "cat1", $ErrorMsg) == $row2['catid']) { echo " selected"; } ?>><?= $row2['category'] ?></option>
		 <?
		}

	   ?>
	   </select><br>
       <textarea class="desc" rows="4" name="description1" cols="80"><?= which_data($row, "description1", $ErrorMsg) ?></textarea><br><? if($_SESSION['Country']['english'] != "Y") { ?><textarea class="desc" rows="4" name="engdesc1" cols="80"><?= which_data($row, "engdesc1", $ErrorMsg) ?></textarea><? } ?>
      </td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("26") ?> 2</b></td>
     </tr>
     <tr>
      <td align="right" valign="top" class="Heading2"><b><?= get_word("26") ?>:<br><br>
      <?= get_word("27") ?>:<br><br><br>English:<br><? if($_SESSION['Country']['english'] == "Y") { print $English; } ?></td>
      <td bgcolor="FFFFFF"><select size="1" name="cat2"><option value="0"></option>
	   <?

	    $query1 = dbRead("select catid, category from categories where display_drop = 'Y' and CID like '".$_SESSION['User']['CID']."' or CID='0' order by category ASC");
	    while($row2 = mysql_fetch_assoc($query1)) {
 		 ?>
		  <option value="<?= $row2['catid'] ?>"<? if(which_data($row, "cat2", $ErrorMsg) == $row2['catid']) { echo " selected"; } ?>><?= $row2['category'] ?></option>
		 <?
	    }

	   ?>
       </select><br>
       <textarea class="desc" rows="4" name="description2" cols="80"><?= which_data($row, "description2", $ErrorMsg) ?></textarea><br>
       <? if($_SESSION['Country']['english'] != "Y") { ?><textarea class="desc" rows="4" name="engdesc2" cols="80" value=""><? } ?></textarea>
      </td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("26") ?> 3</b></td>
     </tr>
     <tr>
      <td align="right" valign="top" class="Heading2"><b><?= get_word("26") ?>:<br><br>
      <?= get_word("27") ?>:<br><br><br>English:<br><? if($_SESSION['Country']['english'] == "Y") { print $English; } ?></td>
      <td bgcolor="FFFFFF"><select size="1" name="cat3"><option value="0"></option>
	   <?

		$query1 = dbRead("select catid, category from categories where display_drop = 'Y' and CID like '".$_SESSION['User']['CID']."' or CID='0' order by category ASC");
		while($row2 = mysql_fetch_assoc($query1)) {
		 ?>
		  <option value="<?= $row2['catid'] ?>"<? if(which_data($row, "cat3", $ErrorMsg) == $row2['catid']) { echo " selected"; } ?>><?= $row2['category'] ?></option>
		 <?
		}

	   ?>
       </select><br>
       <textarea class="desc" rows="4" name="description3" cols="80"><?= which_data($row, "description3", $ErrorMsg) ?></textarea><br>
       <? if($_SESSION['Country']['english'] != "Y") { ?><textarea class="desc" rows="4" name="engdesc3" cols="80" value=""></textarea><? } ?>
      </td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("26") ?> 4</b></td>
     </tr>
     <tr>
      <td align="right" valign="top" class="Heading2"><b><?= get_word("26") ?>:<br><br>
      <?= get_word("27") ?>:<br><br><br>English:<br><? if($_SESSION['Country']['english'] == "Y") { print $English; } ?></td>
      <td bgcolor="FFFFFF"><select size="1" name="cat4"><option value="0"></option>
 	   <?

	    $query1 = dbRead("select catid, category from categories where display_drop = 'Y' and CID like '".$_SESSION['User']['CID']."' or CID='0' order by category ASC");
	    while($row2 = mysql_fetch_assoc($query1)) {
	 	 ?>
		  <option value="<?= $row2['catid'] ?>"<? if(which_data($row, "cat4", $ErrorMsg) == $row2['catid']) { echo " selected"; } ?>><?= $row2['category'] ?></option>
		 <?
	    }

	   ?>
       </select><br>
       <textarea class="desc" rows="4" name="description4" cols="80"><?= which_data($row, "description4", $ErrorMsg) ?></textarea><br>
       <? if($_SESSION['Country']['english'] != "Y") { ?><textarea class="desc" rows="4" name="engdesc4" cols="80" value=""></textarea><? } ?>
      </td>
     </tr>
      <?if($_SESSION['Country']['english'] == "N") {?>
      <?}?>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("92") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("13") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="streetno" size="10" maxlength="10" value="<?= which_data($row, "streetno", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("14") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="streetname" size="30" maxlength="40" value="<?= which_data($row, "streetname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("15") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="suburb" size="30" maxlength="40" value="<?= which_data($row, "suburb", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("16") ?>:</b></td>
      <td bgcolor="<?= change_colour("companyname", $ErrorArray) ?>"><input type="text" name="city" size="30" maxlength="40" value="<?= which_data($row, "city", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("17") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="state" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>" value="<?= which_data($row, "state", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("18") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="postcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row, "postcode", $ErrorMsg) ?>" <? if ($_SESSION['CountryPref_Members']['post_no']) {?>onKeyPress="return number(event)"<?}?>></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b>Home Address</b></td>
     </tr>
     <tr>
      <td class="Heading2" align="right" nowrap><?= get_word("20") ?>:</td>
      <td bgcolor="#FFFFFF" align="left">
      <input type="checkbox" name="SameHome" onclick="javascript:samehome();" value="ON"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("228") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="homestreetno" size="10" maxlength="10" value="<?= which_data($row, "homestreetno", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("229") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="homestreetname" size="30" maxlength="40" value="<?= which_data($row, "homestreetname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("230") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="homesuburb" size="30" maxlength="40" value="<?= which_data($row, "homesuburb", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("232") ?>:</b></td>
      <td bgcolor="<?= change_colour("companyname", $ErrorArray) ?>"><input type="text" name="homecity" size="30" maxlength="40" value="<?= which_data($row, "homecity", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("233") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="homestate" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>" value="<?= which_data($row, "homestate", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("234") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="homepostcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row, "homepostcode", $ErrorMsg) ?>" <? if ($_SESSION['CountryPref_Members']['post_no']) {?>onKeyPress="return number(event)"<?}?>></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("93") ?></b></td>
     </tr>
     <tr>
      <td class="Heading2" align="right" nowrap><?= get_word("20") ?>:</td>
      <td bgcolor="#FFFFFF" align="left">
      <input type="checkbox" name="SamePostal" onclick="javascript:samepostal();" value="ON"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("13") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="postalno" size="10" value="<?= which_data($row, "postalno", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("14") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="postalname" size="30" maxlength="40" value="<?= which_data($row, "postalname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("15") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="postalsuburb" size="30" maxlength="40" value="<?= which_data($row, "postalsuburb", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("16") ?>:</b></td>
      <td bgcolor="<?= change_colour("companyname", $ErrorArray) ?>"><input type="text" name="postalcity" size="30" maxlength="40" value="<?= which_data($row, "postalcity", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b> <?= get_word("17") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="postalstate" size="<?= $_SESSION['CountryPref_Members']['state_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['state_limit']?>" value="<?= which_data($row, "postalstate", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("18") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="postalpostcode" size="<?= $_SESSION['CountryPref_Members']['post_limit']?>" maxlength="<?= $_SESSION['CountryPref_Members']['post_limit']?>" value="<?= which_data($row, "postalpostcode", $ErrorMsg) ?>" <? if ($_SESSION['CountryPref_Members']['post_no']) {?>onKeyPress="return number(event)"<?}?>></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("95") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("34") ?>:</b></td>
      <td bgcolor="<?= change_colour("salesmanid", $ErrorArray) ?>"><select size="1" name="salesmanid">
       <option value = "" >Select One</option>
		<?

		 //$getsalesppl = dbRead("select salesmanid, name from salespeople where CID like '".$_SESSION['User']['CID']."' order by name ASC");
		 $getsalesppl = dbRead("select FieldID, Name from tbl_admin_users where CID like '".$_SESSION['User']['CID']."' and SalesPerson = '1' order by name ASC");
		 while($row2 = mysql_fetch_assoc($getsalesppl)) {

		  ?>
		   <option value="<?= $row2['FieldID'] ?>"<? if($row2['FieldID'] == which_data($row, "salesmanid", $ErrorMsg)) { print " selected"; } ?>><?= $row2['Name'] ?></option>
		  <?

	 	 }

	    ?>
	   </select>
	  </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("60") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="membershipfeepaid" size="10" maxlength="6" value="<?= which_data($row, "membershipfeepaid", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
    <?if($_SESSION['User']['CID'] == 12) {?>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b>Trade % in above <?= get_word("60") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="trade_membership" size="10" maxlength="6" value="<?= which_data($row, "trade_membership", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <?}?>
    <?if($row['promo'] == 'trade') {?>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b>Promotion:</b></td>
      <td bgcolor="FFFFFF"><font size = '2' color = #FF0000>Membership $99 ONLY</font></td>
     </tr>
     <?}?>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("59") ?>:</b></td>
      <td bgcolor="FFFFFF">
       <?

        $query = dbRead("select * from tbl_admin_payment_types where startup = '1' order by Type");
        form_select('memshipfeepaytype',$query,'Type','FieldID',which_data($row, "memshipfeepaytype", $ErrorMsg),'Select One');

       ?>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><B><?= get_word("74") ?>:</B></td>
      <td bgcolor="FFFFFF"><?= do_date("banked",which_date_data($row,"banked",$ErrorMsg)); ?></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("96") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("58") ?>:</b></td>
      <td bgcolor="FFFFFF">
       <?

        $query = dbRead("select * from tbl_admin_payment_types where ongoing = '1' order by Type");
        form_select('paymenttype',$query,'Type','FieldID',which_data($row, "paymenttype", $ErrorMsg),'Select One');

       ?>
      </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("62") ?>:</b></td>
      <td bgcolor="<?= change_colour("accountname", $ErrorArray) ?>"><input type="text" name="accountname" size="30" maxlength="32" value="<?= which_data($row, "accountname", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("63") ?>:</b></td>
      <td bgcolor="<?= change_colour("accountno", $ErrorArray) ?>"><input type="text" name="accountno" size="30" maxlength="30" value="<?= which_data($row, "accountno", $ErrorMsg) ?>" onKeyPress="return number2(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("64") ?>:</b></td>
      <td bgcolor="<?= change_colour("expires", $ErrorArray) ?>"><input type="text" name="expires" size="5" maxlength="5" value="<?= which_data($row, "expires", $ErrorMsg) ?>" onKeyPress="return number2(event)"></td>
     </tr>
     <?if($_SESSION['Country']['erewards'] == "Y")  {?>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("90") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("100") ?></b></td>
      <td bgcolor="#FFFFFF"><select name="supply_statement">
       <option <? if(which_data($row, "supply_statement", $ErrorMsg) == 1) { echo "selected "; } ?>value="1">
       Pending Supplier Statement</option>
       <option <? if(which_data($row, "supply_statement", $ErrorMsg) == 2) { echo "selected "; } ?>value="2">
       Supplier Statement Received</option>
       <option <? if(which_data($row, "supply_statement", $ErrorMsg) == 3) { echo "selected "; } ?>value="3">
       ABN Provided</option>
       </select>
      </td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("90") ?>:</b></td>
      <td bgcolor="FFFFFF">
      <input type="checkbox" <? if(which_data($row, "erewards", $ErrorMsg)) { echo "checked "; } ?>value="<? if(which_data($row, "erewards", $ErrorMsg)) { echo which_data($row, "erewards", $ErrorMsg); } else { echo "9"; } ?>" name="erewards" value="ON"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("99") ?>:</b></td>
      <td bgcolor="<?= change_colour("reward_bsb", $ErrorArray) ?>"><input type="text" name="reward_bsb" size="10" maxlength="6" value="<?= which_data($row, "reward_bsb", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("63") ?>:</b></td>
      <td bgcolor="<?= change_colour("reward_accno", $ErrorArray) ?>"><input type="text" name="reward_accno" size="30" maxlength="9" value="<?= which_data($row, "reward_accno", $ErrorMsg) ?>" onKeyPress="return number2(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("62") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="reward_accname" size="30" maxlength="32" value="<?= which_data($row, "reward_accname", $ErrorMsg) ?>"></td>
     </tr>
     <?}?>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("97") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b>%:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="transfeecash" size="20"  maxlength="6" value="<? if($ErrorMsg) { print $_REQUEST['transfeecash']; } else { if($row['transfeecash']) { print $row['transfeecash']; } else { print $_SESSION['Country']['feepercent']; } }?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("98") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("61") ?>:</b></td>
      <td bgcolor="<?= change_colour("monthlyfeecash", $ErrorArray) ?>"><input type="text" name="monthlyfeecash" size="20" maxlength="9" value="<? if($ErrorMsg) { print $_REQUEST['monthlyfeecash']; } else { if($row['monthlyfeecash']) { print $row['monthlyfeecash']; } else { print $_SESSION['Country']['feemonthly']; } }?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("49") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("49") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="text" name="pin" size="30" maxlength="30" value="<?= which_data($row, "pin", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("65") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("50") ?>:</b></td>
      <td bgcolor="<?= change_colour("referedby", $ErrorArray) ?>"><input type="text" name="referedby" size="20" maxlength="10" value="<?= which_data($row, "referedby", $ErrorMsg) ?>" onKeyPress="return number(event)"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b>Referrers <?= get_word("62") ?>:</b></td>
      <td bgcolor="<?= change_colour("refer_name", $ErrorArray) ?>"><input type="text" name="refer_name" size="30" maxlength="32" value="<?= which_data($row, "refer_name", $ErrorMsg) ?>"></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b>Referrers <?= get_word("63") ?>:</b></td>
      <td bgcolor="<?= change_colour("refer_account", $ErrorArray) ?>"><input type="text" name="refer_account" size="30" maxlength="30" value="<?= which_data($row, "refer_account", $ErrorMsg) ?>" onKeyPress="return number2(event)"></td>
     </tr>
     <?if($_SESSION['Country']['alltrades'] == "Y")  {?>
     <tr>
      <td align="center" valign="middle" colspan="2" class="Heading2"><b><?= get_word("37") ?></b></td>
     </tr>
     <tr>
      <td align="right" valign="middle" class="Heading2"><b><?= get_word("37") ?>:</b></td>
      <td bgcolor="FFFFFF"><input type="checkbox" name="goldcard" value="ON"<? if(which_data($row, "goldcard", $ErrorMsg)) { print " checked"; }?>></td>
     </tr>
     <?}?>
     <tr>
      <td class="Heading2" align="right" nowrap>Acceptance Letter Received:</td>
      <td bgcolor="#FFFFFF" align="left">
      <input type="checkbox" name="accept" value="2" <? if(which_data($row, "accept", $ErrorMsg)) {?>Checked<?}?>></td>
     </tr>
     <tr>
       <td align="right" valign="middle" class="Heading2" width="30%"><b>Admin Fee Exemption:</b></td>
       <td bgcolor="#FFFFFF" width="70%"><select size="1" name="admin_exempt">
        <option <? if(which_data($row, "admin_exempt", $ErrorMsg) == 0) { echo "selected "; }?>value="0">
        No</option>
        <option <? if(which_data($row, "admin_exempt", $ErrorMsg) == 1) { echo "selected "; }?>value="1">
        Yes</option>
        </select>
       </td>
     </tr>
     <tr>
      <td align="right" valign="top" class="Heading2">&nbsp;</td>
      <td bgcolor="FFFFFF" align="right"><br><input type="button" value="Add Member" name="add" onclick="ConfirmAdd();">&nbsp;&nbsp;<br><br></td>
     </tr>
    </table>
   </td>
  </tr>
  </table>
  <?

 }


?>