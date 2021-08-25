<?
 $CONFIG2['db_name'] = "control2";
 //$CONFIG2['db_host'] = "66.228.219.105";
 $CONFIG2['db_host'] = "localhost";
 $CONFIG2['db_user'] = "stealth";
 $CONFIG2['db_pass'] = "";
 $CONFIG2['db_linkid'] = @mysql_pconnect($CONFIG2['db_host'], $CONFIG2['db_user'], $CONFIG2['db_pass']);

 $CONFIG2['auth_enabled'] = false;
 $CONFIG2['auth_realm'] = "E Banc Administration.";
 $CONFIG2['auth_failed'] = "/virt/web-01/errormsg/auth_1.htm";
 $CONFIG2['auth_suspend'] = "/virt/web-01/errormsg/auth_2.htm";
 $CONFIG2['auth_user'] = "";
 $CONFIG2['auth_pass'] = "";
 $CONFIG2['DEBUG'] = true;
 $CONFIG2[graphver] = "1.8";

 /**
  * User Management
  *
  * USerManagement.php
  * Version 0.02
  */

 /**
  * Module Matrix.
  */

 $ModuleMatrix = array(
	'Administration' => array(
		'SuperUser',
		'ChangeUserPass',
		'AddAdminUser'),
 	'Members' => array(
 		'NetMem',
 		'AddMember',
 		'EditMemberLevel1',
 		'EditMemberLevel2',
 		'EditEvents',
 		'Summary',
 		'MemberSearch',
 		'ViewMember',
 		'ViewStatement',
 		'Notes'),
 	'Misc' => array(
 		'Graphs',
 		'Downloads',
 		'MemOrder',
 		'OrderCards',
 		'PrintCheque',
 		'PrintVoucher',
 		'PrintLabels',
 		'SendTaxInv',
 		'StatsReports',
 		'LogReport',
 		'Log',
 		'Weather',
 		'SendXmas',
 		'WriteOff'),
 	'Transactions' => array(
 		'Scheduled',
 		'DDUpload',
 		'Transaction',
 		'Override',
 		'Suspense',
 		'Contractor',
 		'Deactivated',
 		'Staff',
 		'TransDetails',
 		'TransReceipt',
 		'IntAuthCheck',
 		'AuthCheck',
 		'AuthEdit'),
 	'Categories' => array(
 		'CatAdd',
 		'CatEdit',
 		'CatDel'),
 	'Sales People' => array(
 		'SalesAdd'),
 	'Fees' => array(
 		'FeePayment',
 		'REFeePayment',
 		'Reversals',
 		'REReversals',
 		'ChargeFees'),
 	'Auction' => array(
 		'AuctionEdit'),
 	'Clasifieds' => array(
 		'ClasAdd',
 		'ClasEdit',
 		'ClasSearch',
 		'ClasDetail',
 		'ClasPicture',
 		'ClasCheck'),
 	'Real Estate' => array(
 		'REAdd',
 		'REEdit',
 		'RESearch',
 		'REPicture',
 		'RECatAdd'),
 	'Conversion' => array(
 		'CountryUpdate',
 		'Conversion',
 		'Facility',
 		'REFacility'),
 	'E Rewards' => array(
 		'ErewardsStatement',
 		'ErewardsApproval',
 		'ErewardsSignup',
 		'ErewardsCheck',
 		'ErewardsReports',
 		'ErewardsChange'),
 	'Credit Cards' => array(
 		'CCFees',
 		'CCPayments',
 		'CCReport',
 		'CCDeclined',
 		'CCExpired'),
 	'Licensee and Management' => array(
 		'LynReports',
 		'Bpay',
 		'LicReports',
 		'LicAreaUpdate',
 		'LicEmail',
 		'HQEmail',
 		'HQSend',
 		'ManReports',
 		'Letters',
 		'PrintTaxInv',
 		'PrintStatements',
		'MyServices',
		'Clubs',
		'Pat',
 		'CorpUpdate',
 		'MemberUpdate',
 		'AdminUpdate',
 		'Newsletters',
		'MemReports')
	);

  $notearray = array('1' => 'Admin', '2' => 'C/Support', '3' => 'R/Estate');

 if(!checkmodule("AddAdminUser")) {
  ?>
  <table width="601" border="0" cellpadding="1" cellspacing="0">
   <tr>
    <td class="Border">
     <table width="100%" border="0" cellpadding="3" cellspacing="0">
      <tr>
       <td width="100%" align="center" class="Heading2">You are not allowed to use this function.</td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
  <?
  die;
 }

?>
<script language="javascript">
<!--
function DateRangeSelect(element,input) {
	form = document.UserManagement;
	if(element.checked) {
		form[input + '1'].disabled = false;
		form[input + '2'].disabled = false;
		form[input + '3'].disabled = false;
		form[input + '4'].disabled = false;
		form[input + '5'].disabled = false;
		form[input + '6'].disabled = false;
	} else {
		form[input + '1'].disabled = true;
		form[input + '2'].disabled = true;
		form[input + '3'].disabled = true;
		form[input + '4'].disabled = true;
		form[input + '5'].disabled = true;
		form[input + '6'].disabled = true;
	}
}
function SelectRow(input) {
	form = document.UserManagement;
	if(form[input].checked) {
		form[input].checked = false;
	} else {
		if(form[input].disabled == false) form[input].checked = true;
	}

}
function DeleteUser(id) {
	result = vbconfirmbox("Are you sure you wish to delete this user?",32 + 4 + 256,"Delete User");
	if (result==6){
		document.location.href="body.php?page=UserManagement&User=<?= $_REQUEST['User'] ?>&tab=<?= $_REQUEST['tab'] ?>&DeleteID=<?= $_REQUEST['User'] ?>";
	}
}

function ToggleLicensee() {

	form = document.UserManagement;
	form['EditMemberLevel1'].checked = true;
	form['Summary'].checked = true;
	form['MemberSearch'].checked = true;
	form['ViewMember'].checked = true;
	form['ViewStatement'].checked = true;
	form['Notes'].checked = true;
	form['Graphs'].checked = true;
	form['Downloads'].checked = true;
	form['MemOrder'].checked = true;
	form['Log'].checked = true;
	form['Contractor'].checked = true;
	form['Staff'].checked = true;
	form['ClasAdd'].checked = true;
	form['ClasEdit'].checked = true;
	form['ClasSearch'].checked = true;
	form['ClasDetail'].checked = true;
	form['ClasPicture'].checked = true;
	form['RESearch'].checked = true;
	form['ErewardsStatement'].checked = true;
	form['ErewardsCheck'].checked = true;
	form['LicReports'].checked = true;
	form['LicAreaUpdate'].checked = true;
	form['LicEmail'].checked = true;
	form['HQEmail'].checked = true;

}

function ToggleCustomerSupport() {

	form = document.UserManagement;
	form['EditMemberLevel1'].checked = true;
	form['Summary'].checked = true;
	form['MemberSearch'].checked = true;
	form['ViewMember'].checked = true;
	form['ViewStatement'].checked = true;
	form['Notes'].checked = true;
	form['Graphs'].checked = true;
	form['Downloads'].checked = true;
	form['MemOrder'].checked = true;
	form['SendTaxInv'].checked = true;
	form['Log'].checked = true;
	form['ClasAdd'].checked = true;
	form['ClasEdit'].checked = true;
	form['ClasSearch'].checked = true;
	form['ClasDetail'].checked = true;
	form['ClasPicture'].checked = true;
	form['RESearch'].checked = true;

}

function ChangeCountry(list) {
 var url = 'https://admin.ebanctrade.com/body.php?page=UserManagement&tab=Module Edit&countryid=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}
//-->
</script>
<script language="VBScript">
function vbconfirmbox(thismsg,thisstyle,thistitle)
    vbconfirmbox = MsgBox(thismsg,thisstyle,thistitle)
End function
</script>
<script type="text/javascript" src="includes/tipster.js"></script>
<script type="text/javascript" src="includes/newtips.js"></script>
<form method="POST" action="body.php?page=UserManagement&User=<?= $_REQUEST['User'] ?>&tab=<?= $_REQUEST['tab'] ?>" name="UserManagement">

<div id="docTipsLayer" style="position: absolute; z-index: 1000; visibility: hidden; left: 0px; top: 0px; width: 10px">&nbsp;</div>

<?

 /**
  * Some Setup.
  */

 $time_start = getmicrotime();

 if(checkmodule("SuperUser")) {
   $tabarray = array('Users','Add User','Module Edit');
 } else {
   $tabarray = array('Users','Add User');
 }
 /**
  * Do Tabs if we need to.
  */

 tabs($tabarray);

if($_REQUEST['tab'] == "Users") {

 if($_REQUEST['EditID']) {

  update_user();

 } else {

  if($_REQUEST['EditUser']) {

   edit_user($_REQUEST['User'],$ModuleMatrix);

  } else {

   if($_REQUEST['DeleteID']) {

    dbWrite("delete from tbl_admin_users where FieldID = '".addslashes($_REQUEST['DeleteID'])."'");

   }

   display_users();

  }

 }

} elseif($_REQUEST['tab'] == "Add User") {

 if($_REQUEST['EditID']) {

  add_user_todb();

 } else {

  add_user($ModuleMatrix);

 }

} elseif($_REQUEST['tab'] == "Module Edit") {

 edit_modules($ModuleMatrix);

}

?>

</form>

<?

 /**
  * Functions.
  */

 function edit_modules($ModuleMatrix) {

 if($_REQUEST[countryid]) {
  $GET_CID = $_REQUEST[countryid];
 } else {
  $GET_CID = $_SESSION['User']['CID'];
 }


 if($_REQUEST[emlic]) {
  $t1 = " emlic = '1'";
 } else {
  $t1 = "";
 }

 if($_REQUEST[emadm]) {
  if($t1)  {
   $t2 = " OR emadm = '1'";
  } else {
   $t2 = " emadm = '1'";
  }
 } else {
  $t2 = "";
 }

 if($_REQUEST[emcus]) {
  if($t1 || $t2)  {
   $t3 = " OR emcus = '1'";
  } else {
   $t3 = " emcus = '1'";
  }
 } else {
  $t3 = "";
 }

 if($_REQUEST[emsal]) {
  if($t1 || $t2 || $t3)  {
   $t4 = " OR emsal = '1'";
  } else {
   $t4 = " emsal = '1'";
  }
 } else {
  $t4 = "";
 }

 if($_REQUEST[emrea]) {
  if($t1 || $t2 || $t3 || $t4)  {
   $t5 = " OR emrea = '1'";
  } else {
   $t5 = " emrea = '1'";
  }
 } else {
  $t5 = "";
 }

if($_REQUEST[emlic] || $_REQUEST[emadm] || $_REQUEST[emcus] || $_REQUEST[emsal] || $_REQUEST[emrea]) {
 $t = " and ($t1$t2$t3$t4$t5)";
} else {
 $t = "";
}
  ?>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#000000" cellspacing="0">
   <tr>
    <td colspan="17" height="30" align="center" class="Heading2" ><b><?= get_word("79") ?>:</b>
    <select name="countryid" id="countryid" onChange="ChangeCountry(this);">
<?
		$dbgetarea=dbRead("select * from country where Display = 'Yes' order by name ASC");
		while($row = mysql_fetch_assoc($dbgetarea)) {
			?>
			<option <? if ($row[countryID] == $GET_CID) { echo "selected "; } ?>value="<?= $row[countryID] ?>"><?= $row[name] ?></option>
			<?
		}
?>
     </select>&nbsp;</td>
    </tr>
    <tr>
     <td colspan ="17">
      <table border="0" cellpadding="3" cellspacing="3" width="100%">
      <tr>
       <td align="right"><b>Lic:</b></td>
       <td width="2"><input type="checkbox" name="emlic" value="1" <? if($_REQUEST['emlic']){?>checked<?}?>></td>
       <td align="right"><b>Admin:</b></td>
       <td width="2"><input type="checkbox" name="emadm" value="1" <? if($_REQUEST['emadm']){?>checked<?}?>></td>
       <td align="right"><b>C/Support:</b></td>
       <td width="2"><input type="checkbox" name="emcus" value="1" <? if($_REQUEST['emcus']){?>checked<?}?>></td>
       <td align="right"><b>Sal:</b></td>
       <td width="2"><input type="checkbox" name="emsal" value="1" <? if($_REQUEST['emsal']){?>checked<?}?>></td>
       <td align="right"><b>R/E:</b></td>
       <td width="2"><input type="checkbox" name="emrea" value="1" <? if($_REQUEST['emrea']){?>checked<?}?>></td>
       <td width="50%" align="right"><input type="submit" value="Search" name="Search"></td>
      </tr>
     </table>
     </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <?
      echo $t;
      $SQLQuery = dbRead("select tbl_admin_users.*, country.name from tbl_admin_users left outer join country on (tbl_admin_users.CID = country.countryID) where CID = '".$GET_CID."' and (Suspended = 0 or SalesPerson = 1)$t order by CID, tbl_admin_users.Name ");
      while($Row = mysql_fetch_assoc($SQLQuery)) {
      ?>
        <td style="layout-flow: vertical-ideographic;"><b><?= $Row['Name'] ?></b>&nbsp;</td>
      <?

       $UserModules[$Row[FieldID]] = unserialize($Row['Modules']);

      }

      	//print_r($UserModules);

       foreach($ModuleMatrix as $Key => $Value) {

        $ModuleCount++;

        ?><tr><td><b><?= $Key ?></b>&nbsp;</td></tr><?

         $Foo = 0;

         foreach($Value as $Key2 => $Value2) {

          $ModuleCount++;

          $bgcolor = "#FFFFFF";
          $Foo % 2  ? 0: $bgcolor = "#e5e5e5";

 		   ?>
 		   <td bgcolor="<?= $bgcolor ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?= $Value2 ?></td>
 		   <?

           foreach($UserModules as $Key3 => $Value3) {
            ?>
            <td bgcolor="<?= $bgcolor ?>"><input type="checkbox" name="<?= $Value2 ?>[<?= $Key3 ?>]" value="Y" style="cursor: hand;" <? if($Value3[$Value2]) { print "checked"; } ?>></td>
            <?
           }
           ?>
           </tr>
           <?
          $Foo++;

         }

       }


      ?>
  </table>
  <?

 }

 function update_user() {

  global $ModuleMatrix, $logdata;

  /**
   * Check there is no other users with the same username
   */

  $SQLTest = dbRead("select count(*) as Test from tbl_admin_users where FieldID != '".addslashes($_REQUEST['User'])."' and Username = '".addslashes($_REQUEST['Username'])."'");
  @$SQLTestRow = mysql_fetch_assoc($SQLTest);
  if($SQLTestRow['Test'] > 0) {

   /**
    * User Exists Go back to Edit with Error.
    */

   edit_user($_REQUEST['User'],$ModuleMatrix,"That Username already exists on the system. Please Choose another one.");

  } else {

   /**
    * Safe to update Current User.
    */

  $query = dbRead("select * from tbl_admin_users where FieldID = '".$_REQUEST['User']."'");
  $row = mysql_fetch_assoc($query);

  foreach($row as $key => $value) {
   if($key != 'Modules') {
     $NIPageArray = array(
       'phpbb2mysql_data' => 'phpbb2mysql_data',
       'page' => 'page',
       'Client' => 'Client',
       'pageno' => 'pageno',
       'tab' => 'tab',
       'Update' => 'Update',
       'main' => 'main',
       'changemember' => 'changemember',
       'PHPSESSID' => 'PHPSESSID',
       'countryID' => 'countryID',
       'fieldid' => 'fieldid',
       'LocationID' => 'LocationID',
       'changearea' => 'changearea',
       'changecountry' => 'changecountry',
       'FieldID' => 'FieldID',
       'Password' => 'Password',
       'md5Password' => 'md5Password',
       'SalesmanID' => 'SalesmanID'
    );

    if(!encode_text2($_REQUEST[$key])) {
     $aa = 0;
    } else {
     $aa = encode_text2($_REQUEST[$key]);
    }

    //if(encode_text2($_REQUEST[$key]) != $row[$key]) {
    if($aa != $row[$key]) {
     if($key != $NIPageArray[$key]) {
      $logdata[$key] = array($row[$key],encode_text2($_REQUEST[$key]));
     }
    }
   }
  }

   dbWrite("update tbl_admin_users set emlic = '".$_REQUEST['emlic']."',emadm = '".$_REQUEST['emadm']."',emcus = '".$_REQUEST['emcus']."',emsal = '".$_REQUEST['emsal']."',emrea = '".$_REQUEST['emrea']."',PrintView = '".addslashes(encode_text2($_REQUEST['PrintView']))."',Position2 = '".addslashes(encode_text2($_REQUEST['Position2']))."',Position = '".addslashes(encode_text2($_REQUEST['Position']))."', Name = '".addslashes(encode_text2($_REQUEST['Name']))."', Username = '".addslashes(encode_text2($_REQUEST['Username']))."', EmailAddress = '".addslashes(encode_text2($_REQUEST['EmailAddress']))."', skype_id = '".addslashes(encode_text2($_REQUEST['skype_id']))."', Area = '".addslashes($_REQUEST['Area'])."', lang_code = '".addslashes($_REQUEST['lang_code'])."', CID = '".addslashes($_REQUEST['CID'])."', NoteType = '".addslashes($_REQUEST['NoteType'])."', Suspended = '".addslashes($_REQUEST['Suspended'])."', MaxTransfer = '".addslashes($_REQUEST['MaxTransfer'])."', SalesPerson = '".addslashes($_REQUEST['SalesPerson'])."', AreasAllowed = '".addslashes($_REQUEST['AreasAllowed'])."', ReportsAllowed = '".addslashes($_REQUEST['ReportsAllowed'])."', Address = '".addslashes($_REQUEST['Address'])."', PhoneNo = '".addslashes($_REQUEST['PhoneNo'])."', Mobile = '".addslashes($_REQUEST['Mobile'])."', salespercent = '".addslashes($_REQUEST['salespercent'])."', AgentID = '".addslashes($_REQUEST['AgentID'])."' where FieldID = '".$_REQUEST['User']."'");
   //dbWrite("update tbl_admin_users set Type = '".addslashes(encode_text2($_REQUEST['Type']))."',Position2 = '".addslashes(encode_text2($_REQUEST['Position2']))."',Position = '".addslashes(encode_text2($_REQUEST['Position']))."', Name = '".addslashes(encode_text2($_REQUEST['Name']))."', Username = '".addslashes(encode_text2($_REQUEST['Username']))."', EmailAddress = '".addslashes(encode_text2($_REQUEST['EmailAddress']))."', Area = '".addslashes($_REQUEST['Area'])."', CID = '".addslashes($_REQUEST['CID'])."', NoteType = '".addslashes($_REQUEST['NoteType'])."', Suspended = '".addslashes($_REQUEST['Suspended'])."', MaxTransfer = '".addslashes($_REQUEST['MaxTransfer'])."', SalesmanID = '".addslashes($_REQUEST['SalesManID'])."', AreasAllowed = '".addslashes($_REQUEST['AreasAllowed'])."', ReportsAllowed = '".addslashes($_REQUEST['ReportsAllowed'])."' where FieldID = '".$_REQUEST['User']."'");

   if($_REQUEST['Password']) {

    if($_REQUEST['Password'] != $_REQUEST['Password2']) {

     /**
      * Passwords do not match bomb out with error.
      */

     edit_user($_REQUEST['User'],$ModuleMatrix,"The Passwords do not match.");
     die;

    } else {

     /**
      * Safe to Update Password.
      */

     dbWrite("update tbl_admin_users set Password = password('".addslashes(encode_text2($_REQUEST['Password']))."'), OldPassword = 'ASDFASFDERFAWEFAWEF' where FieldID = '".addslashes($_REQUEST['User'])."'");

     $array = explode("@", $_REQUEST['EmailAddress']);
     $add1 = $array[0];
     $add2 = $array[1];
     $querych  = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".addslashes($add1)."' and AliasDomain = '".addslashes($add2)."'");
     $rowch = @mysql_fetch_assoc($querych);

     if($rowch['FieldID']) {

     	dbWrite2("update tbl_Mail set Password = '".addslashes(encode_text2($_REQUEST['Password']))."', StatusID = '4' where FieldID = ". $rowch['MailID'] ."");

     }

    }

   }

   update_modules($_REQUEST['User']);
   display_users();

  }

 }

 function add_user_todb() {

  global $ModuleMatrix;

  /**
   * Check there is no other users with the same username
   */

  $SQLTest = dbRead("select count(*) as Test from tbl_admin_users where FieldID != '".addslashes($_REQUEST['User'])."' and Username = '".addslashes($_REQUEST['Username'])."'");
  @$SQLTestRow = mysql_fetch_assoc($SQLTest);
  if($SQLTestRow['Test'] > 0) {

   /**
    * User Exists Go back to Add with Error.
    */

   add_user($ModuleMatrix,"That Username already exists on the system. Please Choose another one.");

  } else {

   /**
    * Safe to add Current User.
    */

   if($_REQUEST['Password'] != $_REQUEST['Password2']) {

    /**
     * Passwords do not match bomb out with error.
     */

    add_user($ModuleMatrix,"The Passwords do not match.");
    die;

   }
if($ff) {
  $EmailAddress = $_REQUEST[Email]."@".$_REQUEST[domain];

  if($_REQUEST[Email] && $_REQUEST[mailbox])  {
   $email = $EmailAddress;
   $user = $_REQUEST[Username];
   $password = $_REQUEST[Password];
   $name = "E Banc Trade - ".$_REQUEST[Name];

   $array = explode("@", $email);
   $add1 = $array[0];
   $add2 = $array[1];

   $querycheck  = dbRead2("select * from tbl_Mail where Username = '".$user."'","control2");
   //$querycheck  = dbRead2("select * from tbl_Mail where Username = '".$user."'");

   if(mysql_num_rows($querycheck) == 0) {

    //$querycheck2  = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".$add1."' and AliasDomain = '".$add2."'","control2");
    $querycheck2  = dbRead2("select * from tbl_Mail_Aliases where AliasUser = '".$add1."' and AliasDomain = '".$add2."'","control2");
    if(mysql_num_rows($querycheck2) == 0) {

     $MailID = dbWrite2("insert into tbl_Mail (AccountID,ServerID,StatusID,Username,Password,Name,Forward,AR) values ('44','2','0','".addslashes($user)."','".addslashes($password)."','".addslashes($name)."','N','N')","control2", true);
     //$MailID = dbWrite2("insert into tbl_Mail (AccountID,ServerID,StatusID,Username,Password,Name,Forward,AR) values ('44','2','0','".addslashes($user)."','".addslashes($password)."','".addslashes($name)."','N','N')",'', true);
     dbWrite2("insert into tbl_Mail_Aliases (MailID,AliasUser,AliasDomain) values ($MailID,'".addslashes($add1)."','".addslashes($add2)."')","control2");
     //dbWrite2("insert into tbl_Mail_Aliases (MailID,AliasUser,AliasDomain) values ($MailID,'".addslashes($add1)."','".addslashes($add2)."')");

    } else {
     add_user($ModuleMatrix,"Email Address already exsists.");
     die;
    }
   } else {
     add_user($ModuleMatrix,"Mail Box Name already exsists.");
     die;
   }
  }
 }
   $UserID = dbWrite("insert into tbl_admin_users (Username,Password,Name,EmailAddress,skype_id,Position,Position2,AgentID,MaxTransfer,AreasAllowed,ReportsAllowed,SalesPerson,Area,PrintView,NoteType,emlic,emadm,emcus,emsal,emrea,lang_code,CID,Suspended,Address,PhoneNo,Mobile,salespercent) values ('".addslashes(encode_text2($_REQUEST['Username']))."',password('".addslashes(encode_text2($_REQUEST['Password']))."'),'".addslashes(encode_text2($_REQUEST['Name']))."','".addslashes(encode_text2($EmailAddress))."','".addslashes(encode_text2($_REQUEST['skype_id']))."','".addslashes(encode_text2($_REQUEST['Position']))."','".addslashes(encode_text2($_REQUEST['Position2']))."','".addslashes(encode_text2($_REQUEST['AgentID']))."','".addslashes(encode_text2($_REQUEST['MaxTransfer']))."','".addslashes(encode_text2($_REQUEST['AreasAllowed']))."','".addslashes(encode_text2($_REQUEST['ReportsAllowed']))."','".addslashes(encode_text2($_REQUEST['SalesPerson']))."','".addslashes(encode_text2($_REQUEST['Area']))."','".addslashes(encode_text2($_REQUEST['PrintView']))."','".addslashes(encode_text2($_REQUEST['NoteType']))."','".$_REQUEST['emlic']."','".$_REQUEST['emadm']."','".$_REQUEST['emcus']."','".$_REQUEST['emsal']."','".$_REQUEST['emrea']."','".addslashes(encode_text2($_REQUEST['lang_code']))."','".addslashes(encode_text2($_REQUEST['CID']))."','".addslashes(encode_text2($_REQUEST['Suspended']))."','".addslashes(encode_text2($_REQUEST['Address']))."','".addslashes(encode_text2($_REQUEST['PhoneNo']))."','".addslashes(encode_text2($_REQUEST['Mobile']))."','".addslashes(encode_text2($_REQUEST['salespercent']))."')","etradebanc",true);
   //$UserID = dbWrite("insert into tbl_admin_users (Username,Password,Name,EmailAddress,Position,Position2,AgentID,MaxTransfer,AreasAllowed,ReportsAllowed,SalesmanID,Area,Type,NoteType,CID,Suspended) values ('".addslashes(encode_text2($_REQUEST['Username']))."',password('".addslashes(encode_text2($_REQUEST['Password']))."'),'".addslashes(encode_text2($_REQUEST['Name']))."','".addslashes(encode_text2($_REQUEST['EmailAddress']))."','".addslashes(encode_text2($_REQUEST['Position']))."','".addslashes(encode_text2($_REQUEST['Position2']))."','".addslashes(encode_text2($_REQUEST['AgentID']))."','".addslashes(encode_text2($_REQUEST['MaxTransfer']))."','".addslashes(encode_text2($_REQUEST['AreasAllowed']))."','".addslashes(encode_text2($_REQUEST['ReportsAllowed']))."','".addslashes(encode_text2($_REQUEST['SalesManID']))."','".addslashes(encode_text2($_REQUEST['Area']))."','".addslashes(encode_text2($_REQUEST['Type']))."','".addslashes(encode_text2($_REQUEST['NoteType']))."','".addslashes(encode_text2($_REQUEST['CID']))."','".addslashes(encode_text2($_REQUEST['Suspended']))."')","etradebanc",true);

   update_modules($UserID);
   display_users();

  }

 }

 function edit_user($User,$ModuleMatrix,$Error = false) {

  $notearray = array('1' => 'Admin', '2' => 'C/Support', '3' => 'R/Estate');

  $SQLQuery = dbRead("select tbl_admin_users.* from tbl_admin_users where FieldID = '".addslashes($_REQUEST['User'])."'");
  $UserRow = mysql_fetch_assoc($SQLQuery);
  $UserModules = unserialize($UserRow['Modules']);
  if($Error) {
   $UserRow = $_REQUEST;
  }

  if($Error) {

  ?>
  <table border="2" cellpadding="3" width="600" style="border-collapse: collapse" bordercolor="#FF0000" cellspacing="0">
   <tr>
    <td width="100%" bgcolor="#FFFFFF"><?= $Error ?>&nbsp;</td>
   </tr>
  </table>
  <br>
  <?

  }

  ?>

  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">User Information</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td align="right" nowrap><b>Name:</b></td>
       <td><input type="text" name="Name" size="20" value="<?= get_all_added_characters($UserRow['Name']) ?>" tabindex="1"></td>
       <td align="right"><b>Username:</b></td>
       <td><input type="text" name="Username" size="20" value="<?= get_all_added_characters($UserRow['Username']) ?>" tabindex="4"></td>
      </tr>
      <tr>
       <td align="right" nowrap><b>Position:</b></td>
       <td><input type="text" name="Position2" size="20" value="<?= get_all_added_characters($UserRow['Position2']) ?>" tabindex="2"></td>
       <td align="right"><b>Password:</b></td>
       <td><input type="password" name="Password" size="20" tabindex="5"></td>
      </tr>
      <tr>
       <td align="right" nowrap><b>Notes Position:</b></td>
       <td><input type="text" name="Position" size="20" value="<?= get_all_added_characters($UserRow['Position']) ?>" tabindex="2"></td>
       <td align="right"><b>Password Again:</b></td>
       <td><input type="password" name="Password2" size="20" tabindex="6"></td>
      </tr>
      <tr>
       <td align="right" colspan="2" nowrap><b>Skype ID: <input type="text" name="skype_id" size="20" value="<?= $UserRow['skype_id'] ?>"></b><b> E-mail:</b></td>
       <td colspan="2"><input type="text" name="EmailAddress" size="37" value="<?= get_all_added_characters($UserRow['EmailAddress']) ?>" tabindex="7"></td>
	  </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Personal Contact Details</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td align="center"><b>Address:</b></td>
       <td colspan="3"><input type="text" name="Address" size="40" value="<?= get_all_added_characters($UserRow['Address']) ?>" tabindex="9"></td>
       </td>
      </tr>
      <tr>
       <td align="right"><b>Phone:</b></td>
       <td><input type="text" name="PhoneNo" size="20" value="<?= get_all_added_characters($UserRow['PhoneNo']) ?>" tabindex="9"></td>
       </td>
       <td align="right"><b>Mobile:</b></td>
       <td><input type="text" name="Mobile" size="20" value="<?= get_all_added_characters($UserRow['Mobile']) ?>" tabindex="9"></td>
       </td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Country Specific Options</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td align="right" nowrap><b>Licensee:</b></td>
       <td><select name="Area" tabindex="8">
        <?
         if(!checkmodule("SuperUser")) {
           $extra = " where CID = ".$_SESSION['Country']['countryID']."";
		 } else {
		   $extra = "";
		 }

         $AQuery = dbRead("select area.* from area where CID = ".$UserRow['CID']." order by place ASC");
         while($ARow = mysql_fetch_assoc($AQuery)) {

          ?><option value="<?= $ARow['FieldID'] ?>"<? if($UserRow['Area'] == $ARow['FieldID']) { echo " selected"; } ?>><?= $ARow['place'] ?> (<?= $ARow['FieldID'] ?>)</option><?

         }

        ?>
       </select></td>
       <td align="right"><b>Country:</b></td>
       <td><select name="CID" tabindex="9">
        <?

      	 if(!checkmodule("SuperUser")) {
           $extra = " where countryID = ".$_SESSION['Country']['countryID']."";
		 } else {
		   $extra = "";
		 }

         $CQuery = dbRead("select country.* from country $extra order by Name ASC");
         while($CRow = mysql_fetch_assoc($CQuery)) {

          ?><option value="<?= $CRow['countryID'] ?>"<? if($UserRow['CID'] == $CRow['countryID']) { echo " selected"; } ?>><?= $CRow['name'] ?> (<?= $CRow['countryID'] ?>)</option><?

         }

        ?>
       </select></td>
      </tr>
      <tr>
       <td align="right"><b>Lang Code:</b></td>
       <td><select name="lang_code" tabindex="9">
        <?

         $LQuery = dbRead("select country.* from country group by Langcode order by Langcode ASC");
         while($LRow = mysql_fetch_assoc($LQuery)) {

          ?><option value="<?= $LRow['Langcode'] ?>"<? if($UserRow['lang_code'] == $LRow['Langcode']) { echo " selected"; }?>><?= $LRow['Langcode'] ?> (<?= $LRow['Langcode'] ?>)</option><?

         }

        ?>
       </select></td>
     </tr>
     <tr>
       <td align="centre"><b>R/E Agent Access:</b></td>
       <td colspan="3"><select name="AgentID" tabindex="9"><option selected value="">No R/E Access</option>
        <?

         $AQuery = dbRead("select * from agents where CID = ".$UserRow['CID']." order by name ASC");
         while($ARow = mysql_fetch_assoc($AQuery)) {

          ?><option value="<?= $ARow['agentid'] ?>"<? if($UserRow['AgentID'] == $ARow['agentid']) { echo " selected"; }?>><?= $ARow['name'] ?> (<?= $ARow['agentid'] ?>)</option><?

         }

        ?>
       </select></td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" colspan="2" class="Heading2">Options</td>
   </tr>
   <tr>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
    <table border="0" cellpadding="2">
      <tr>
        <td align="right"><b>Note Type:</b></td>
        <td bgcolor="#FFFFFF" align="left"><?= form_select('NoteType',$notearray,'','',$UserRow['NoteType']); ?></td>
      </tr>
      <tr>
        <td align="right"><b>Print View:</b></td>
        <td><input type="checkbox" name="PrintView" value="1"  style="cursor: hand;" <? if ($UserRow['PrintView']) { echo "checked"; } ?>></td>
      </tr>
      <tr>
       <td align="right"><b>Sales Person:</b></td>
       <td><input type="checkbox" name="SalesPerson" value="1"  style="cursor: hand;" <? if ($UserRow['SalesPerson']) { echo "checked"; } ?>></td>
       <td align="right"><b>Comm. %:</b></td>
       <td><input type="text" name="salespercent" size="8" value="<?= get_all_added_characters($UserRow['salespercent']) ?>" ></td>
      </tr>
      <tr>
        <td align="right"><b>Suspend User:</b></td>
        <td><input type="checkbox" name="Suspended" value="1"  style="cursor: hand;" <? if ($UserRow['Suspended']) { echo "checked"; } ?>></td>
        </TR>
      </table>
    </td>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF" valign="top" nowrap>
    <table border="0" cellspacing="1" width="100%">
      <tr>
        <td>&nbsp;</td>
        <td width="100%"><b>From</b></td>
      </tr>
      <tr>
        <td>
        <input type="checkbox" name="StartTimeEnabled" value="1"  style="cursor: hand;" onclick="DateRangeSelect(this,'from')"></td>
        <td width="100%" nowrap><select size="1" name="from1" disabled>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option selected value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select>
		    <select size="1" name="from2" disabled>
			<option value="1">Jan</option>
			<option value="2">Feb</option>
			<option value="3">Mar</option>
			<option value="4">Apr</option>
			<option value="5">May</option>
			<option value="6">Jun</option>
			<option value="7">Jul</option>
			<option value="8">Aug</option>
			<option selected value="9">Sep</option>
			<option value="10">Oct</option>
			<option value="11">Nov</option>
			<option value="12">Dec</option>
			</select> <select size="1" name="from3" disabled>
			<option selected value="2003">2003</option>
			<option value="2004">2004</option>
			<option value="2005">2005</option>
			</select>
	        <input type="text" name="from4" size="1" value="10" maxlength="2" disabled>:<input type="text" name="from5" size="1" value="19" maxlength="2" disabled>
	        <select size="1" name="from6" disabled>
			<option selected value="AM">AM</option>
			<option value="PM">PM</option>
			</select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="100%"><b>To</b></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="EndTimeEnabled" value="1"  style="cursor: hand;" onclick="DateRangeSelect(this,'to')"></td>
        <td width="100%" nowrap><select size="1" name="to1" disabled>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option selected value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select> <select size="1" name="to2" disabled>
			<option value="1">Jan</option>
			<option value="2">Feb</option>
			<option value="3">Mar</option>
			<option value="4">Apr</option>
			<option value="5">May</option>
			<option value="6">Jun</option>
			<option value="7">Jul</option>
			<option value="8">Aug</option>
			<option selected value="9">Sep</option>
			<option value="10">Oct</option>
			<option value="11">Nov</option>
			<option value="12">Dec</option>
			</select> <select size="1" name="to3" disabled>
			<option value="2003">2003</option>
			<option selected value="2004">2004</option>
			<option value="2005">2005</option>
			</select>
    	    <input type="text" name="to4" size="1" value="12" maxlength="2" disabled>:<input type="text" name="to5" size="1" value="00" maxlength="2" disabled>
	        <select size="1" name="to6" disabled>
			<option selected value="AM">AM</option>
			<option value="PM">PM</option>
			</select></td>
      </tr>
      </table>
    </td>
  </tr>
</table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Email Lists</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3" width="300">
      <tr>
       <td align="right"><b>Licensee:</b></td>
       <td width="2"><input type="checkbox" name="emlic" value="1" <? if($UserRow['emlic']){?>checked<?}?>></td>
       <td align="right"><b>Sales:</b></td>
       <td width="2"><input type="checkbox" name="emsal" value="1" <? if($UserRow['emsal']){?>checked<?}?>></td>
      </tr>
      <tr>
       <td align="right"><b>Admin:</b></td>
       <td width="2"><input type="checkbox" name="emadm" value="1" <? if($UserRow['emadm']){?>checked<?}?>></td>
       <td align="right"><b>Real Estate:</b></td>
       <td width="2"><input type="checkbox" name="emrea" value="1" <? if($UserRow['emrea']){?>checked<?}?>></td>
      </tr>
      <tr>
       <td align="right"><b>Customer Support:</b></td>
       <td width="2"><input type="checkbox" name="emcus" value="1" <? if($UserRow['emcus']){?>checked<?}?>></td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
    <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Limitations</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td colspan="2" align="right" nowrap><b>Max Transfer:</b></td>
       <td><input type="text" name="MaxTransfer" size="20" value="<?= get_all_added_characters($UserRow['MaxTransfer']) ?>" tabindex="13"></td>
      </tr>
      <tr>
       <td align="right" nowrap><b>Areas Allowed:</b></td>
       <td><input type="text" name="AreasAllowed" size="20" value="<?= get_all_added_characters($UserRow['AreasAllowed']) ?>" tabindex="14"></td>
       <td align="right"><b>Reports Allowed:</b></td>
       <td><input type="text" name="ReportsAllowed" size="20" tabindex="15" value="<?= get_all_added_characters($UserRow['ReportsAllowed']) ?>"></td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <?

  display_permissions($ModuleMatrix,$UserModules);

  ?>
  <table border="0" cellpadding="0" cellspacing="0" width="600">
   <tr>
    <td width="50%"><br></td>
    <td width="50%" align="right"><br>
    <input type="submit" value="Update" name="job"></td>
   </tr>
  </table>
  <input type="hidden" name="EditID" value="1">
  <input type="hidden" name="search" value="1">
  <input type="hidden" name="countryID" value="<?= $_REQUEST[countryID] ?>">
  </form>

  <?

 }

 function add_user($ModuleMatrix,$Error = false) {

  $notearray = array('1' => 'Admin', '2' => 'C/Support', '3' => 'R/Estate');

  if($Error) {
   $UserRow = $_REQUEST;
  }

  if($Error) {

  ?>
  <table border="2" cellpadding="3" width="600" style="border-collapse: collapse" bordercolor="#FF0000" cellspacing="0">
   <tr>
    <td width="100%" bgcolor="#FFFFFF"><?= $Error ?>&nbsp;</td>
   </tr>
  </table>
  <br>
  <?

  }

  ?>

  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">User Information</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td align="right" nowrap><b>Name:</b></td>
       <td><input type="text" name="Name" size="20" value="<?= get_all_added_characters($UserRow['Name']) ?>" tabindex="1"></td>
       <td align="right"><b>Username:</b></td>
       <td><input type="text" name="Username" size="20" value="<?= get_all_added_characters($UserRow['Username']) ?>" tabindex="4"></td>
      </tr>
      <tr>
       <td align="right" nowrap><b>Position:</b></td>
       <td><input type="text" name="Position2" size="20" value="<?= get_all_added_characters($UserRow['Position2']) ?>" tabindex="2"></td>
       <td align="right"><b>Password:</b></td>
       <td><input type="password" name="Password" size="20" tabindex="5"></td>
      </tr>
      <tr>
       <td align="right" nowrap><b>Notes Position:</b></td>
       <td><input type="text" name="Position" size="20" value="<?= get_all_added_characters($UserRow['Position']) ?>" tabindex="3"></td>
       <td align="right"><b>Password Again:</b></td>
       <td><input type="password" name="Password2" size="20" tabindex="6"></td>
      </tr>
      <tr>
       <td align="right" colspan="1" nowrap>Create Email <input type="checkbox" name="mailbox" value="1"><b> E-mail:</b></td>
       <td colspan="1"><input type="text" name="Email" size="20" value="<?= get_all_added_characters($UserRow['EmailAddress']) ?>" tabindex="7"></td>
       <?//if($ff) {?>
       <td colspan="2">@ <select name="domain" tabindex="9">
        <?

       	 if(!checkmodule("SuperUser")) {
           $extra = " and Domain = 'ebanctrade.com'";
		 } else {
		   $extra = "";
		 }

 		 //$querydomain  = dbRead2("select Domain,FieldID from tbl_Domains where AccountID = '44'$extra","control2");
 		 //$querydomain  = dbRead2("select Domain,FieldID from tbl_Domains where AccountID = '44'$extra");
         //while($RowDomain = mysql_fetch_assoc($querydomain)) {
         // ?>
         // <option value="<?= $RowDomain['Domain'] ?>" <?if($RowDomain['FieldID'] == 40) { echo " selected"; }?>><?= $RowDomain['Domain'] ?></option>
         // <?
         //}
        //?>
        <//%}?>
       </select>
       </td>
	  </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Personal Contact Details</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td align="center"><b>Address:</b></td>
       <td colspan="3"><input type="text" name="Address" size="40" value="<?= get_all_added_characters($UserRow['Address']) ?>" tabindex="9"></td>
       </td>
      </tr>
      <tr>
       <td align="right"><b>Phone:</b></td>
       <td><input type="text" name="PhoneNo" size="20" value="<?= get_all_added_characters($UserRow['PhoneNo']) ?>" tabindex="9"></td>
       </td>
       <td align="right"><b>Mobile:</b></td>
       <td><input type="text" name="Mobile" size="20" value="<?= get_all_added_characters($UserRow['Mobile']) ?>" tabindex="9"></td>
       </td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Country Specific Options</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>


      </tr>
      <tr>
       <td align="right" nowrap><b>Licensee:</b></td>
       <td><select name="Area" tabindex="8">
        <?
       	 if(!checkmodule("SuperUser")) {
           $extra = " where CID = ".$_SESSION['Country']['countryID']."";
		 } else {
		   $extra = "";
		 }

         $AQuery = dbRead("select area.* from area $extra order by place ASC");
         while($ARow = mysql_fetch_assoc($AQuery)) {

          ?><option value="<?= $ARow['FieldID'] ?>"<? if($UserRow['Area'] == $ARow['FieldID']) { echo " selected"; }?>><?= $ARow['place'] ?> (<?= $ARow['FieldID'] ?>)</option><?

         }

        ?>
       </select></td>
       <td align="right"><b>Country:</b></td>
       <td><select name="CID" tabindex="9">
        <?
      	 if(!checkmodule("SuperUser")) {
           $extra = " where countryID = ".$_SESSION['Country']['countryID']."";
		 } else {
		   $extra = "";
		 }

         $CQuery = dbRead("select country.* from country $extra order by Name ASC");
         while($CRow = mysql_fetch_assoc($CQuery)) {

          ?><option value="<?= $CRow['countryID'] ?>"<? if($UserRow['CID'] == $CRow['countryID']) { echo " selected"; }?>><?= $CRow['name'] ?> (<?= $CRow['countryID'] ?>)</option><?

         }

        ?>
       </select></td>
      </tr>
      <tr>
       <td align="centre"><b>Lang Code:</b></td>
       <td><select name="lang_code" tabindex="9">
        <?

         $LQuery = dbRead("select country.* from country group by Langcode order by Langcode ASC");
         while($LRow = mysql_fetch_assoc($LQuery)) {

          ?><option value="<?= $LRow['Langcode'] ?>"<? if($UserRow['lang_code'] == $LRow['Langcode']) { echo " selected"; }?>><?= $LRow['Langcode'] ?> (<?= $LRow['Langcode'] ?>)</option><?

         }

        ?>
       </select></td>
      </tr>
      <tr>
       <td align="centre"><b>R/E Agent Access:</b></td>
       <td colspan="3"><select name="AgentID" tabindex="9"><option selected value="">No R/E Access</option>
        <?

         $AQuery = dbRead("select * from agents order by name ASC");
         while($ARow = mysql_fetch_assoc($AQuery)) {

          ?><option value="<?= $ARow['agentid'] ?>"<? if($UserRow['AgentID'] == $ARow['agentid']) { echo " selected"; }?>><?= $ARow['name'] ?> (<?= $ARow['agentid'] ?>)</option><?

         }

        ?>
       </select></td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" colspan="2" class="Heading2">Options</td>
   </tr>
   <tr>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
    <table border="0" cellpadding="2">

      <tr>
        <td align="right"><b>Note Type:</b></td>
        <td bgcolor="#FFFFFF" align="left"><?= form_select('NoteType',$notearray,'','',$UserRow['NoteType']); ?></td>
      </tr>
      <tr>
        <td align="right"><b>Print View:</b></td>
        <td><input type="checkbox" name="PrintView" value="1"  style="cursor: hand;" <? if ($UserRow['PrintView']) { echo "checked"; } ?>></td>
      </tr>
      <tr>
       <td align="right"><b>Sales Person:</b></td>
       <td><input type="checkbox" name="SalesPerson" value="1"  style="cursor: hand;" <? if ($UserRow['SalesPerson']) { echo "checked"; } ?>></td>
       <td align="right"><b>Comm. %:</b></td>
       <td><input type="text" name="salespercent" size="8" value="<?= get_all_added_characters($UserRow['salespercent']) ?>" ></td>
      </tr>
      <TR>
        <td align="right"><b>Suspend User:</b></td>
        <td><input type="checkbox" name="Suspended" value="1"  style="cursor: hand;" <? if ($UserRow['Suspended']) { echo "checked"; } ?>></td>
        </TR>
    </table>
    </td>
    <td width="50%" bordercolor="#0E1B2A" bgcolor="#FFFFFF" valign="top" nowrap>
    <table border="0" cellspacing="1" width="100%">
      <tr>
        <td>&nbsp;</td>
        <td width="100%"><b>From</b></td>
      </tr>
      <tr>
        <td>
        <input type="checkbox" name="StartTimeEnabled" value="1"  style="cursor: hand;" onclick="DateRangeSelect(this,'from')"></td>
        <td width="100%" nowrap><select size="1" name="from1" disabled>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option selected value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select>
		    <select size="1" name="from2" disabled>
			<option value="1">Jan</option>
			<option value="2">Feb</option>
			<option value="3">Mar</option>
			<option value="4">Apr</option>
			<option value="5">May</option>
			<option value="6">Jun</option>
			<option value="7">Jul</option>
			<option value="8">Aug</option>
			<option selected value="9">Sep</option>
			<option value="10">Oct</option>
			<option value="11">Nov</option>
			<option value="12">Dec</option>
			</select> <select size="1" name="from3" disabled>
			<option selected value="2003">2003</option>
			<option value="2004">2004</option>
			<option value="2005">2005</option>
			</select>
	        <input type="text" name="from4" size="1" value="10" maxlength="2" disabled>:<input type="text" name="from5" size="1" value="19" maxlength="2" disabled>
	        <select size="1" name="from6" disabled>
			<option selected value="AM">AM</option>
			<option value="PM">PM</option>
			</select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="100%"><b>To</b></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="EndTimeEnabled" value="1"  style="cursor: hand;" onclick="DateRangeSelect(this,'to')"></td>
        <td width="100%" nowrap><select size="1" name="to1" disabled>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option selected value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select> <select size="1" name="to2" disabled>
			<option value="1">Jan</option>
			<option value="2">Feb</option>
			<option value="3">Mar</option>
			<option value="4">Apr</option>
			<option value="5">May</option>
			<option value="6">Jun</option>
			<option value="7">Jul</option>
			<option value="8">Aug</option>
			<option selected value="9">Sep</option>
			<option value="10">Oct</option>
			<option value="11">Nov</option>
			<option value="12">Dec</option>
			</select> <select size="1" name="to3" disabled>
			<option value="2003">2003</option>
			<option selected value="2004">2004</option>
			<option value="2005">2005</option>
			</select>
    	    <input type="text" name="to4" size="1" value="12" maxlength="2" disabled>:<input type="text" name="to5" size="1" value="00" maxlength="2" disabled>
	        <select size="1" name="to6" disabled>
			<option selected value="AM">AM</option>
			<option value="PM">PM</option>
			</select></td>
      </tr>
      </table>
    </td>
  </tr>
</table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
  <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Email Lists</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3" width="300">
      <tr>
       <td align="right"><b>Licensee:</b></td>
       <td width="2"><input type="checkbox" name="emlic" value="1" <? if($UserRow['emlic']){?>checked<?}?>></td>
       <td align="right"><b>Sales:</b></td>
       <td width="2"><input type="checkbox" name="emsal" value="1" <? if($UserRow['emsal']){?>checked<?}?>></td>
      </tr>
      <tr>
       <td align="right"><b>Admin:</b></td>
       <td width="2"><input type="checkbox" name="emadm" value="1" <? if($UserRow['emadm']){?>checked<?}?>></td>
       <td align="right"><b>Real Estate:</b></td>
       <td width="2"><input type="checkbox" name="emrea" value="1" <? if($UserRow['emrea']){?>checked<?}?>></td>
      </tr>
      <tr>
       <td align="right"><b>Customer Support:</b></td>
       <td width="2"><input type="checkbox" name="emcus" value="1" <? if($UserRow['emcus']){?>checked<?}?>></td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td width="100%">&nbsp; </td>
   </tr>
  </table>
    <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
   <tr>
    <td width="100%" class="Heading2">Limitations</td>
   </tr>
   <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF">
     <div align="center">
     <center>
     <table border="0" cellpadding="3" cellspacing="3">
      <tr>
       <td colspan = "2" align="right" nowrap><b>Max Transfer:</b></td>
       <td><input type="text" name="MaxTransfer" size="20" value="<?= get_all_added_characters($UserRow['MaxTransfer']) ?>" tabindex="13"></td>
      </tr>
      <tr>
       <td align="right" nowrap><b>Areas Allowed:</b></td>
       <td><input type="text" name="AreasAllowed" size="20" value="all" tabindex="14"></td>
       <td align="right"><b>Reports Allowed:</b></td>
       <td><input type="text" name="ReportsAllowed" size="20" tabindex="15" value="all"></td>
      </tr>
     </table>
     </center>
     </div>
    </td>
   </tr>
  </table>
  <?

  display_permissions($ModuleMatrix,$UserModules);

  ?>
  <table border="0" cellpadding="0" cellspacing="0" width="600">
   <tr>
    <td width="50%"><br></td>
    <td width="50%" align="right"><br>
    <input type="submit" value="Add" name="job"></td>
   </tr>
  </table>
  <input type="hidden" name="EditID" value="1">
  </form>

  <?

 }

 function display_permissions($ModuleMatrix,$UserModules) {

  if($_SESSION['Country']['countryID'] != 1) {
?>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="100%">&nbsp;
 <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
  <tr>
    <td width="100%" class="Heading2">Permissions</td>
  </tr>
  <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF" valign="top">
     <table border="0" cellpadding="2" bordercolor="#000000" style="border-collapse: collapse" cellspacing="0" width="100%">
     <tr bgcolor="<?= $bgcolor ?>">
       <td nowrap height="16" WIDTH="90%">&nbsp; - Licensee</a></td>
       <td width="10%" align="center" height="16"><input type="radio" name="cat" value="1" style="cursor: hand;"></td>
     </tr>
     <tr bgcolor="<?= $bgcolor ?>">
       <td nowrap height="16" WIDTH="90%">&nbsp; - Administration ie Diane</a></td>
       <td width="10%" align="center" height="16"><input type="radio" name="cat" value="2" style="cursor: hand;"></td>
     </tr>
     <tr bgcolor="<?= $bgcolor ?>">
       <td nowrap height="16" WIDTH="90%">&nbsp; - Customer Support ie Jane</a></td>
       <td width="10%" align="center" height="16"><input type="radio" name="cat" value="3" style="cursor: hand;"></td>
     </tr>
     <tr bgcolor="<?= $bgcolor ?>">
       <td nowrap height="16" WIDTH="90%">&nbsp; - Sales Person ie Lance</a></td>
       <td width="10%" align="center" height="16"><input type="radio" name="cat" value="4" style="cursor: hand;"></td>
     </tr>
     </table>
    </td>
  </tr>
 </table>
    </td>
  </tr>
 </table>


<?
  } else {
 ?>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="100%">&nbsp;
 <table border="1" cellpadding="2" width="600" style="border-collapse: collapse" bordercolor="#111111" cellspacing="0">
  <tr>
    <td width="100%" class="Heading2">Permissions&nbsp;&nbsp;[<a class="nav" href="javascript:ToggleLicensee();">Licensee</a>]&nbsp;[<a class="nav" href="javascript:ToggleCustomerSupport();">CustomerSupport</a>]</td>
  </tr>
  <tr>
    <td width="100%" bordercolor="#0E1B2A" bgcolor="#FFFFFF" valign="top">
     <table border="0" cellpadding="2" bordercolor="#000000" style="border-collapse: collapse" cellspacing="0" width="100%">

     <?

     foreach($ModuleMatrix as $Key => $Value) {

      ?>

      <tr bgcolor="#E5E5E5">
        <td nowrap class="item_head" height="14" WIDTH="90%"><b><?= $Key ?></b></td>
        <td width="10%" align="center" class="item_head" height="14"><B>Allow</B></td>
      </tr>

      <?

       $Foo = 0;

       foreach($Value as $Key2 => $Value2) {

        $bgcolor = "#FFFFFF";
        $Foo % 2  ? 0: $bgcolor = "#F5F5F5";

        ?>

         <tr bgcolor="<?= $bgcolor ?>">
           <td nowrap height="16" WIDTH="90%">&nbsp; - <a href="javascript:SelectRow('<?= $Value2 ?>');" class="nav"><?= $Value2 ?></a></td>
           <td width="10%" align="center" height="16"><input type="checkbox" name="<?= $Value2 ?>" value="Y" style="cursor: hand;" <? if($UserModules[$Value2]) { print "checked"; } ?>></td>
         </tr>

        <?

        $Foo++;

       }

      }

      ?>

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

 function display_users() {

  if(checkmodule("SuperUser")) {
   $ee = "";
  } else {
   $ee = " where countryID = ".$_SESSION['Country']['countryID']."";
   $ee2 = " and countryID = ".$_SESSION['Country']['countryID']."";
   $_REQUEST[countryID] = $_SESSION['Country']['countryID'];
   $_REQUEST[search] = 1;
  }

  ?>
 <body>
 <form method="post" action="body.php?page=UserManagement">
 <table width="600" border="0" cellpadding="1" cellspacing="0">
  <tr>
   <td class="Border">
   <table width="600" border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td colspan="2" align="center" class="Heading">Users Select</td>
    </tr>
    <tr>
      <td align="right" width="150" class="Heading2"><b>Country:</b></td>
      <td width="450" bgcolor="#FFFFFF" >
          <?
           $query1 = dbRead("select * from country $ee order by name");
           form_select('countryID',$query1,'name','countryID',$_REQUEST[countryID]);
          ?>
      </td>
    </tr>
    <tr>
      <td width="150" height="30" class="Heading2">&nbsp;</td>
      <td width="450" height="30" bgcolor="#FFFFFF"><input type="Submit" value="Search" name="search">&nbsp;</td>
    </tr>
   </table>
   </td>
  </tr>
 </table>

 <input type="hidden" name="search" value="1">
 </form>
 <?
 if($_REQUEST[search]) {

  if($_REQUEST[countryID])  {
    $searchCID = $_REQUEST[countryID];
  } else {
    $searchCID = "%";
  }

  //$SQLQuery = dbRead("select tbl_admin_users.*, area.*, country.name, country.countryID from tbl_admin_users, area left outer join country on (tbl_admin_users.CID = country.countryID) where (tbl_admin_users.Area = area.FieldID) and country.countryID like '$searchCID' order by CID, place, Suspended, Username");
  $SQLQuery = dbRead("select tbl_admin_users.*, country.name, country.countryID from tbl_admin_users left outer join country on (tbl_admin_users.CID = country.countryID) where country.countryID like '$searchCID'$ee2 order by CID, Area, Suspended, Username");

  ?>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="600" id="AutoNumber1">
   <tr>
    <td width="100%" class="Border">
     <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse" width="100%" id="AutoNumber2">
     <tr>
       <td colspan="5" class="Heading" align="center">USER EDIT</td>
      </tr>
      <tr>
       <td nowrap class="Heading2" width="60">USER ID</td>
       <td class="Heading2" width="90%">USERNAME</td>
       <td class="Heading2" align="left">CID</td>
       <td class="Heading2" align="left">ACTIVE</td>
       <td class="Heading2" align="left">EDIT</td>
      </tr>
      <?
       $CID = "";
       $Area = "";
       while($row = mysql_fetch_assoc($SQLQuery)) {

        if($row['CID'] != $CID) {

         ?>

         <tr>
          <td colspan="5" class="Heading2" align="center"><? if(!$row['name']) { print "None"; } else { print $row['name']; } ?></td>
         </tr>

         <?

        }

        if($row['Area'] != $Area) {
         $SQLQuery2 = dbRead("select * from area where FieldID = ".$row['Area']."");
         $row2 = mysql_fetch_assoc($SQLQuery2);

         ?>

         <tr>
          <td colspan="5" class="Heading2" align="center"><? if(!$row2['place']) { print "None"; } else { print get_all_added_characters($row2['place']); } ?></td>
         </tr>

         <?

        }

        $CID = $row['CID'];
        $Area = $row['Area'];
        ?>
        <tr bgcolor="#FFFFFF">
         <td nowrap width="60"><?= $row['FieldID'] ?></td>
         <td width="90%"><?= get_all_added_characters($row['Username']) ?>&nbsp;(<?= get_all_added_characters($row['Name']) ?>)</td>
         <td align="right"><?= $row['CID'] ?></td>
         <td align="right"><? if($row['Suspended']) { print "No"; } else { print "Yes"; } ?></td>
         <td align="left"><a href="body.php?page=UserManagement&EditUser=true&User=<?= $row['FieldID'] ?>&tab=Users&countryID=<?= $_REQUEST['countryID'] ?>" class="nav">EDIT</a></td>
        </tr>
        <?

       }

      ?>
     </table>
    </td>
   </tr>
  </table>
  <?

 }
}
 function update_modules($User) {

  global $logdata;

  $query = dbRead("select * from tbl_admin_users where FieldID = '".addslashes($User)."'");
  $row = mysql_fetch_assoc($query);

  $mod = unserialize($row['Modules']);

  if($mod) {
   //foreach($_REQUEST as $key => $value) {
   foreach($mod as $key => $value) {

     $NIPageArray = array(
       'phpbb2mysql_data' => 'phpbb2mysql_data',
       'page' => 'page',
       'Client' => 'Client',
       'pageno' => 'pageno',
       'tab' => 'tab',
       'Update' => 'Update',
       'main' => 'main',
       'changemember' => 'changemember',
       'PHPSESSID' => 'PHPSESSID',
       'countryID' => 'countryID',
       'fieldid' => 'fieldid',
       'LocationID' => 'LocationID',
       'changearea' => 'changearea',
       'changecountry' => 'changecountry'
    );

    if(encode_text2($_REQUEST[$key]) != $mod[$key]) {
     if($key != $NIPageArray[$key]) {
      //$logdata[$key] = array($mod[$key],encode_text2($value));
      if(!$mod[$key]) {
       $ss = "0";
      } else {
       $ss = $mod[$key];
      }
      if(!encode_text2($_REQUEST[$key])) {
       $tt = "0";
      } else {
       $tt = encode_text2($_REQUEST[$key]);
      }
      $logdata[$key] = array($ss,$tt);
     }
    }
   }
  }

  add_kpi2(7,addslashes($User),'0','0',$logdata);

  $ModulesArray['SuperUser'] =       	strstr($_REQUEST['SuperUser'], "Y") ? TRUE : FALSE;
  $ModulesArray['ChangeUserPass'] =    	strstr($_REQUEST['ChangeUserPass'], "Y") ? TRUE : FALSE;
  $ModulesArray['AddAdminUser'] =    	strstr($_REQUEST['AddAdminUser'], "Y") ? TRUE : FALSE;
  $ModulesArray['CustomerSupport'] = 	strstr($_REQUEST['CustomerSupport'], "Y") ? TRUE : FALSE;
  $ModulesArray['Override'] =        	strstr($_REQUEST['Override'], "Y") ? TRUE : FALSE;
  $ModulesArray['Suspense'] =        	strstr($_REQUEST['Suspense'], "Y") ? TRUE : FALSE;
  $ModulesArray['NetMem'] =           	strstr($_REQUEST['NetMem'], "Y") ? TRUE : FALSE;
  $ModulesArray['AddMember'] =        	strstr($_REQUEST['AddMember'], "Y") ? TRUE : FALSE;
  $ModulesArray['EditMemberLevel1'] = 	strstr($_REQUEST['EditMemberLevel1'], "Y") ? TRUE : FALSE;
  $ModulesArray['EditMemberLevel2'] = 	strstr($_REQUEST['EditMemberLevel2'], "Y") ? TRUE : FALSE;
  $ModulesArray['EditEvents'] =       	strstr($_REQUEST['EditEvents'], "Y") ? TRUE : FALSE;
  $ModulesArray['Summary'] =          	strstr($_REQUEST['Summary'], "Y") ? TRUE : FALSE;
  $ModulesArray['MemberSearch'] =     	strstr($_REQUEST['MemberSearch'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ViewMember'] =       	strstr($_REQUEST['ViewMember'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ViewStatement'] = 		strstr($_REQUEST['ViewStatement'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Notes'] = 				strstr($_REQUEST['Notes'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Graphs'] = 			strstr($_REQUEST['Graphs'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Downloads'] = 			strstr($_REQUEST['Downloads'] , "Y") ? TRUE : FALSE;
  $ModulesArray['MemOrder'] = 			strstr($_REQUEST['MemOrder'] , "Y") ? TRUE : FALSE;
  $ModulesArray['OrderCards'] = 		strstr($_REQUEST['OrderCards'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Scheduled'] = 			strstr($_REQUEST['Scheduled'] , "Y") ? TRUE : FALSE;
  $ModulesArray['DDUpload'] = 			strstr($_REQUEST['DDUpload'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Transaction'] = 		strstr($_REQUEST['Transaction'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Contractor'] = 		strstr($_REQUEST['Contractor'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Staff'] = 				strstr($_REQUEST['Staff'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Deactivated'] = 		strstr($_REQUEST['Deactivated'] , "Y") ? TRUE : FALSE;
  $ModulesArray['TransDetails'] = 		strstr($_REQUEST['TransDetails'] , "Y") ? TRUE : FALSE;
  $ModulesArray['TransReceipt'] = 		strstr($_REQUEST['TransReceipt'] , "Y") ? TRUE : FALSE;
  $ModulesArray['IntAuthCheck'] = 		strstr($_REQUEST['IntAuthCheck'] , "Y") ? TRUE : FALSE;
  $ModulesArray['AuthCheck'] = 			strstr($_REQUEST['AuthCheck'] , "Y") ? TRUE : FALSE;
  $ModulesArray['AuthEdit'] = 			strstr($_REQUEST['AuthEdit'] , "Y") ? TRUE : FALSE;
  $ModulesArray['SalesAdd'] = 			strstr($_REQUEST['SalesAdd'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CatAdd'] = 			strstr($_REQUEST['CatAdd'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CatEdit'] = 			strstr($_REQUEST['CatEdit'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CatDel'] = 			strstr($_REQUEST['CatDel'] , "Y") ? TRUE : FALSE;
  $ModulesArray['FeePayment'] = 		strstr($_REQUEST['FeePayment'] , "Y") ? TRUE : FALSE;
  $ModulesArray['REFeePayment'] = 		strstr($_REQUEST['REFeePayment'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Reversals'] = 			strstr($_REQUEST['Reversals'] , "Y") ? TRUE : FALSE;
  $ModulesArray['REReversals'] = 		strstr($_REQUEST['REReversals'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ChargeFees'] = 		strstr($_REQUEST['ChargeFees'] , "Y") ? TRUE : FALSE;
  $ModulesArray['AuctionEdit'] = 		strstr($_REQUEST['AuctionEdit'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ClasAdd'] = 			strstr($_REQUEST['ClasAdd'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ClasEdit'] = 			strstr($_REQUEST['ClasEdit'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ClasSearch'] = 		strstr($_REQUEST['ClasSearch'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ClasDetail'] = 		strstr($_REQUEST['ClasDetail'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ClasPicture'] = 		strstr($_REQUEST['ClasPicture'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ClasCheck'] = 			strstr($_REQUEST['ClasCheck'] , "Y") ? TRUE : FALSE;
  $ModulesArray['REAdd'] = 				strstr($_REQUEST['REAdd'] , "Y") ? TRUE : FALSE;
  $ModulesArray['REEdit'] = 			strstr($_REQUEST['REEdit'] , "Y") ? TRUE : FALSE;
  $ModulesArray['RESearch'] = 			strstr($_REQUEST['RESearch'] , "Y") ? TRUE : FALSE;
  $ModulesArray['REPicture'] = 			strstr($_REQUEST['REPicture'] , "Y") ? TRUE : FALSE;
  $ModulesArray['RECatAdd'] = 			strstr($_REQUEST['RECatAdd'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CountryUpdate'] = 		strstr($_REQUEST['CountryUpdate'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Conversion'] = 		strstr($_REQUEST['Conversion'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Facility'] = 			strstr($_REQUEST['Facility'] , "Y") ? TRUE : FALSE;
  $ModulesArray['REFacility'] = 		strstr($_REQUEST['REFacility'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ErewardsStatement'] = 	strstr($_REQUEST['ErewardsStatement'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ErewardsApproval'] = 	strstr($_REQUEST['ErewardsApproval'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ErewardsSignup'] = 	strstr($_REQUEST['ErewardsSignup'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ErewardsCheck'] = 		strstr($_REQUEST['ErewardsCheck'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ErewardsReports'] = 	strstr($_REQUEST['ErewardsReports'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ErewardsChange'] = 	strstr($_REQUEST['ErewardsChange'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CCFees'] = 			strstr($_REQUEST['CCFees'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CCPayments'] = 		strstr($_REQUEST['CCPayments'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CCReport'] = 			strstr($_REQUEST['CCReport'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CCDeclined'] = 		strstr($_REQUEST['CCDeclined'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CCExpired'] = 			strstr($_REQUEST['CCExpired'] , "Y") ? TRUE : FALSE;
  $ModulesArray['LogReport'] = 			strstr($_REQUEST['LogReport'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Log'] = 				strstr($_REQUEST['Log'] , "Y") ? TRUE : FALSE;
  $ModulesArray['PrintCheque'] = 		strstr($_REQUEST['PrintCheque'] , "Y") ? TRUE : FALSE;
  $ModulesArray['PrintLabels'] = 		strstr($_REQUEST['PrintLabels'] , "Y") ? TRUE : FALSE;
  $ModulesArray['PrintVoucher'] = 		strstr($_REQUEST['PrintVoucher'] , "Y") ? TRUE : FALSE;
  $ModulesArray['PrintTaxInv'] = 		strstr($_REQUEST['PrintTaxInv'] , "Y") ? TRUE : FALSE;
  $ModulesArray['PrintStatements'] = 	strstr($_REQUEST['PrintStatements'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Letters'] = 			strstr($_REQUEST['Letters'] , "Y") ? TRUE : FALSE;
  $ModulesArray['StatsReports'] = 		strstr($_REQUEST['StatsReports'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Pat'] = 				strstr($_REQUEST['Pat'] , "Y") ? TRUE : FALSE;
  $ModulesArray['SendXmas'] = 			strstr($_REQUEST['SendXmas'] , "Y") ? TRUE : FALSE;
  $ModulesArray['WriteOff'] = 			strstr($_REQUEST['WriteOff'] , "Y") ? TRUE : FALSE;
  $ModulesArray['LynReports'] = 		strstr($_REQUEST['LynReports'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Bpay'] = 				strstr($_REQUEST['Bpay'] , "Y") ? TRUE : FALSE;
  $ModulesArray['LicReports'] = 		strstr($_REQUEST['LicReports'] , "Y") ? TRUE : FALSE;
  $ModulesArray['LicAreaUpdate'] = 		strstr($_REQUEST['LicAreaUpdate'] , "Y") ? TRUE : FALSE;
  $ModulesArray['LicEmail'] = 			strstr($_REQUEST['LicEmail'] , "Y") ? TRUE : FALSE;
  $ModulesArray['HQEmail'] = 			strstr($_REQUEST['HQEmail'] , "Y") ? TRUE : FALSE;
  $ModulesArray['HQSend'] = 			strstr($_REQUEST['HQSend'] , "Y") ? TRUE : FALSE;
  $ModulesArray['ManReports'] = 		strstr($_REQUEST['ManReports'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Active'] = 			strstr($_REQUEST['Active'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Weather'] = 			strstr($_REQUEST['Weather'] , "Y") ? TRUE : FALSE;
  $ModulesArray['SendTaxInv'] = 		strstr($_REQUEST['SendTaxInv'] , "Y") ? TRUE : FALSE;
  $ModulesArray['CorpUpdate'] = 		strstr($_REQUEST['CorpUpdate'] , "Y") ? TRUE : FALSE;
  $ModulesArray['MemberUpdate'] = 		strstr($_REQUEST['MemberUpdate'] , "Y") ? TRUE : FALSE;
  $ModulesArray['AdminUpdate'] = 		strstr($_REQUEST['AdminUpdate'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Newsletters'] = 		strstr($_REQUEST['Newsletters'] , "Y") ? TRUE : FALSE;
  $ModulesArray['MemReports'] = 		strstr($_REQUEST['MemReports'] , "Y") ? TRUE : FALSE;
  $ModulesArray['MyServices'] = 		strstr($_REQUEST['MyServices'] , "Y") ? TRUE : FALSE;
  $ModulesArray['Clubs'] = 				strstr($_REQUEST['Clubs'] , "Y") ? TRUE : FALSE;

  $DBModules = serialize($ModulesArray);

  if($_REQUEST['cat'] == 1) {
    $DBModules = 'a:91:{s:9:"SuperUser";b:0;s:14:"ChangeUserPass";b:0;s:15:"CustomerSupport";b:0;s:8:"Override";b:0;s:8:"Suspense";b:0;s:6:"NetMem";b:0;s:9:"AddMember";b:0;s:16:"EditMemberLevel1";b:1;s:16:"EditMemberLevel2";b:0;s:10:"EditEvents";b:1;s:7:"Summary";b:1;s:12:"MemberSearch";b:1;s:10:"ViewMember";b:1;s:13:"ViewStatement";b:1;s:5:"Notes";b:1;s:6:"Graphs";b:1;s:9:"Downloads";b:1;s:8:"MemOrder";b:1;s:10:"OrderCards";b:1;s:9:"Scheduled";b:0;s:8:"DDUpload";b:0;s:11:"Transaction";b:0;s:10:"Contractor";b:1;s:5:"Staff";b:1;s:12:"TransDetails";b:1;s:12:"TransReceipt";b:1;s:12:"IntAuthCheck";b:0;s:9:"AuthCheck";b:0;s:8:"AuthEdit";b:0;s:8:"SalesAdd";b:0;s:6:"CatAdd";b:0;s:7:"CatEdit";b:0;s:6:"CatDel";b:0;s:10:"FeePayment";b:0;s:12:"REFeePayment";b:0;s:9:"Reversals";b:0;s:11:"REReversals";b:0;s:10:"ChargeFees";b:0;s:11:"AuctionEdit";b:0;s:7:"ClasAdd";b:1;s:8:"ClasEdit";b:1;s:10:"ClasSearch";b:1;s:10:"ClasDetail";b:1;s:11:"ClasPicture";b:1;s:9:"ClasCheck";b:0;s:5:"REAdd";b:0;s:6:"REEdit";b:0;s:8:"RESearch";b:1;s:9:"REPicture";b:0;s:8:"RECatAdd";b:0;s:13:"CountryUpdate";b:0;s:10:"Conversion";b:0;s:8:"Facility";b:0;s:10:"REFacility";b:0;s:17:"ErewardsStatement";b:0;s:16:"ErewardsApproval";b:0;s:14:"ErewardsSignup";b:0;s:13:"ErewardsCheck";b:0;s:15:"ErewardsReports";b:0;s:14:"ErewardsChange";b:0;s:6:"CCFees";b:0;s:10:"CCPayments";b:0;s:8:"CCReport";b:0;s:10:"CCDeclined";b:0;s:9:"CCExpired";b:0;s:9:"LogReport";b:0;s:3:"Log";b:1;s:11:"PrintCheque";b:0;s:11:"PrintLabels";b:0;s:12:"PrintVoucher";b:0;s:11:"PrintTaxInv";b:0;s:15:"PrintStatements";b:0;s:7:"Letters";b:0;s:12:"StatsReports";b:1;s:3:"Pat";b:0;s:8:"SendXmas";b:1;s:8:"WriteOff";b:0;s:10:"LynReports";b:0;s:10:"LicReports";b:1;s:13:"LicAreaUpdate";b:1;s:8:"LicEmail";b:1;s:7:"HQEmail";b:0;s:6:"HQSend";b:0;s:10:"ManReports";b:0;s:6:"Active";b:0;s:7:"Weather";b:0;s:10:"SendTaxInv";b:1;s:10:"CorpUpdate";b:0;s:11:"Newsletters";b:1;s:10:"MyServices";b:0;s:5:"Clubs";b:0;}';
  } elseif($_REQUEST['cat'] == 2) {
    $DBModules = 'a:91:{s:9:"SuperUser";b:0;s:14:"ChangeUserPass";b:0;s:15:"CustomerSupport";b:0;s:8:"Override";b:0;s:8:"Suspense";b:1;s:6:"NetMem";b:1;s:9:"AddMember";b:1;s:16:"EditMemberLevel1";b:1;s:16:"EditMemberLevel2";b:1;s:10:"EditEvents";b:0;s:7:"Summary";b:1;s:12:"MemberSearch";b:1;s:10:"ViewMember";b:1;s:13:"ViewStatement";b:1;s:5:"Notes";b:1;s:6:"Graphs";b:1;s:9:"Downloads";b:1;s:8:"MemOrder";b:1;s:10:"OrderCards";b:1;s:9:"Scheduled";b:1;s:8:"DDUpload";b:0;s:11:"Transaction";b:1;s:10:"Contractor";b:1;s:5:"Staff";b:1;s:12:"TransDetails";b:1;s:12:"TransReceipt";b:1;s:12:"IntAuthCheck";b:0;s:9:"AuthCheck";b:1;s:8:"AuthEdit";b:1;s:8:"SalesAdd";b:1;s:6:"CatAdd";b:1;s:7:"CatEdit";b:1;s:6:"CatDel";b:0;s:10:"FeePayment";b:1;s:12:"REFeePayment";b:1;s:9:"Reversals";b:1;s:11:"REReversals";b:1;s:10:"ChargeFees";b:1;s:11:"AuctionEdit";b:0;s:7:"ClasAdd";b:1;s:8:"ClasEdit";b:1;s:10:"ClasSearch";b:1;s:10:"ClasDetail";b:1;s:11:"ClasPicture";b:1;s:9:"ClasCheck";b:1;s:5:"REAdd";b:0;s:6:"REEdit";b:0;s:8:"RESearch";b:1;s:9:"REPicture";b:0;s:8:"RECatAdd";b:0;s:13:"CountryUpdate";b:0;s:10:"Conversion";b:1;s:8:"Facility";b:1;s:10:"REFacility";b:0;s:17:"ErewardsStatement";b:0;s:16:"ErewardsApproval";b:0;s:14:"ErewardsSignup";b:0;s:13:"ErewardsCheck";b:0;s:15:"ErewardsReports";b:0;s:14:"ErewardsChange";b:0;s:6:"CCFees";b:1;s:10:"CCPayments";b:1;s:8:"CCReport";b:1;s:10:"CCDeclined";b:1;s:9:"CCExpired";b:1;s:9:"LogReport";b:1;s:3:"Log";b:1;s:11:"PrintCheque";b:1;s:11:"PrintLabels";b:1;s:12:"PrintVoucher";b:0;s:11:"PrintTaxInv";b:1;s:15:"PrintStatements";b:1;s:7:"Letters";b:1;s:12:"StatsReports";b:1;s:3:"Pat";b:0;s:8:"SendXmas";b:1;s:8:"WriteOff";b:1;s:10:"LynReports";b:0;s:10:"LicReports";b:1;s:13:"LicAreaUpdate";b:1;s:8:"LicEmail";b:1;s:7:"HQEmail";b:1;s:6:"HQSend";b:1;s:10:"ManReports";b:0;s:6:"Active";b:0;s:7:"Weather";b:0;s:10:"SendTaxInv";b:1;s:10:"CorpUpdate";b:0;s:11:"Newsletters";b:1;s:10:"MyServices";b:0;s:5:"Clubs";b:0;}';
  } elseif($_REQUEST['cat'] == 3) {
    $DBModules = 'a:91:{s:9:"SuperUser";b:0;s:14:"ChangeUserPass";b:0;s:15:"CustomerSupport";b:0;s:8:"Override";b:0;s:8:"Suspense";b:0;s:6:"NetMem";b:0;s:9:"AddMember";b:0;s:16:"EditMemberLevel1";b:1;s:16:"EditMemberLevel2";b:0;s:10:"EditEvents";b:1;s:7:"Summary";b:1;s:12:"MemberSearch";b:1;s:10:"ViewMember";b:1;s:13:"ViewStatement";b:1;s:5:"Notes";b:1;s:6:"Graphs";b:1;s:9:"Downloads";b:1;s:8:"MemOrder";b:1;s:10:"OrderCards";b:1;s:9:"Scheduled";b:0;s:8:"DDUpload";b:0;s:11:"Transaction";b:0;s:10:"Contractor";b:0;s:5:"Staff";b:0;s:12:"TransDetails";b:0;s:12:"TransReceipt";b:0;s:12:"IntAuthCheck";b:0;s:9:"AuthCheck";b:0;s:8:"AuthEdit";b:0;s:8:"SalesAdd";b:0;s:6:"CatAdd";b:0;s:7:"CatEdit";b:0;s:6:"CatDel";b:0;s:10:"FeePayment";b:0;s:12:"REFeePayment";b:0;s:9:"Reversals";b:0;s:11:"REReversals";b:0;s:10:"ChargeFees";b:0;s:11:"AuctionEdit";b:0;s:7:"ClasAdd";b:1;s:8:"ClasEdit";b:1;s:10:"ClasSearch";b:1;s:10:"ClasDetail";b:1;s:11:"ClasPicture";b:1;s:9:"ClasCheck";b:0;s:5:"REAdd";b:0;s:6:"REEdit";b:0;s:8:"RESearch";b:1;s:9:"REPicture";b:0;s:8:"RECatAdd";b:0;s:13:"CountryUpdate";b:0;s:10:"Conversion";b:0;s:8:"Facility";b:0;s:10:"REFacility";b:0;s:17:"ErewardsStatement";b:0;s:16:"ErewardsApproval";b:0;s:14:"ErewardsSignup";b:0;s:13:"ErewardsCheck";b:0;s:15:"ErewardsReports";b:0;s:14:"ErewardsChange";b:0;s:6:"CCFees";b:0;s:10:"CCPayments";b:0;s:8:"CCReport";b:0;s:10:"CCDeclined";b:0;s:9:"CCExpired";b:0;s:9:"LogReport";b:0;s:3:"Log";b:1;s:11:"PrintCheque";b:0;s:11:"PrintLabels";b:0;s:12:"PrintVoucher";b:0;s:11:"PrintTaxInv";b:0;s:15:"PrintStatements";b:0;s:7:"Letters";b:0;s:12:"StatsReports";b:0;s:3:"Pat";b:0;s:8:"SendXmas";b:1;s:8:"WriteOff";b:0;s:10:"LynReports";b:0;s:10:"LicReports";b:0;s:13:"LicAreaUpdate";b:0;s:8:"LicEmail";b:0;s:7:"HQEmail";b:0;s:6:"HQSend";b:0;s:10:"ManReports";b:0;s:6:"Active";b:0;s:7:"Weather";b:0;s:10:"SendTaxInv";b:1;s:10:"CorpUpdate";b:0;s:11:"Newsletters";b:0;s:10:"MyServices";b:0;s:5:"Clubs";b:0;}';
  } elseif($_REQUEST['cat'] == 4) {
    $DBModules = 'a:91:{s:9:"SuperUser";b:0;s:14:"ChangeUserPass";b:0;s:15:"CustomerSupport";b:0;s:8:"Override";b:0;s:8:"Suspense";b:0;s:6:"NetMem";b:0;s:9:"AddMember";b:0;s:16:"EditMemberLevel1";b:0;s:16:"EditMemberLevel2";b:0;s:10:"EditEvents";b:0;s:7:"Summary";b:1;s:12:"MemberSearch";b:1;s:10:"ViewMember";b:1;s:13:"ViewStatement";b:1;s:5:"Notes";b:1;s:6:"Graphs";b:1;s:9:"Downloads";b:1;s:8:"MemOrder";b:0;s:10:"OrderCards";b:0;s:9:"Scheduled";b:0;s:8:"DDUpload";b:0;s:11:"Transaction";b:0;s:10:"Contractor";b:0;s:5:"Staff";b:0;s:12:"TransDetails";b:0;s:12:"TransReceipt";b:0;s:12:"IntAuthCheck";b:0;s:9:"AuthCheck";b:0;s:8:"AuthEdit";b:0;s:8:"SalesAdd";b:0;s:6:"CatAdd";b:0;s:7:"CatEdit";b:0;s:6:"CatDel";b:0;s:10:"FeePayment";b:0;s:12:"REFeePayment";b:0;s:9:"Reversals";b:0;s:11:"REReversals";b:0;s:10:"ChargeFees";b:0;s:11:"AuctionEdit";b:0;s:7:"ClasAdd";b:0;s:8:"ClasEdit";b:0;s:10:"ClasSearch";b:1;s:10:"ClasDetail";b:1;s:11:"ClasPicture";b:0;s:9:"ClasCheck";b:0;s:5:"REAdd";b:0;s:6:"REEdit";b:0;s:8:"RESearch";b:1;s:9:"REPicture";b:0;s:8:"RECatAdd";b:0;s:13:"CountryUpdate";b:0;s:10:"Conversion";b:0;s:8:"Facility";b:0;s:10:"REFacility";b:0;s:17:"ErewardsStatement";b:0;s:16:"ErewardsApproval";b:0;s:14:"ErewardsSignup";b:0;s:13:"ErewardsCheck";b:0;s:15:"ErewardsReports";b:0;s:14:"ErewardsChange";b:0;s:6:"CCFees";b:0;s:10:"CCPayments";b:0;s:8:"CCReport";b:0;s:10:"CCDeclined";b:0;s:9:"CCExpired";b:0;s:9:"LogReport";b:0;s:3:"Log";b:1;s:11:"PrintCheque";b:0;s:11:"PrintLabels";b:0;s:12:"PrintVoucher";b:0;s:11:"PrintTaxInv";b:0;s:15:"PrintStatements";b:0;s:7:"Letters";b:0;s:12:"StatsReports";b:0;s:3:"Pat";b:0;s:8:"SendXmas";b:1;s:8:"WriteOff";b:0;s:10:"LynReports";b:0;s:10:"LicReports";b:0;s:13:"LicAreaUpdate";b:0;s:8:"LicEmail";b:0;s:7:"HQEmail";b:0;s:6:"HQSend";b:0;s:10:"ManReports";b:0;s:6:"Active";b:0;s:7:"Weather";b:0;s:10:"SendTaxInv";b:0;s:10:"CorpUpdate";b:0;s:11:"Newsletters";b:0;s:10:"MyServices";b:0;s:5:"Clubs";b:0;}';
  }

  if($_SESSION['Country']['countryID'] != 1) {

  	if($_REQUEST['cat']) {

  		dbWrite("update tbl_admin_users set Modules = '$DBModules' where FieldID = '".addslashes($User)."'");

 	}

  } else {

  	dbWrite("update tbl_admin_users set Modules = '$DBModules' where FieldID = '".addslashes($User)."'");

  }

 }

function check_disabled() {

 if(!checkmodule("SuperUser")) {

  return " disabled";

 }

}

function which_data($row,$field,$error = false) {

 if($error) {
  return $_REQUEST[$field];
 } else {
  return get_all_added_characters($row['NoteType']);
 }

}

function dbRead2($SQLQuery,$database = false) {
	global $CONFIG2, $DB_Count;
	if($database == false) { $database = $CONFIG2['db_name']; }

	if ($CONFIG2['db_linkid'] == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }

	mysql_select_db($database);

	$rsid = mysql_query($SQLQuery, $CONFIG2['db_linkid']);
	if ($rsid == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }

	$DB_Count++;

	return($rsid);
}

function dbWrite2($SQLQuery,$database = false,$DBReturnID = False) {
	global $CONFIG2, $DB_Count;
	if($database == false) { $database = $CONFIG2['db_name']; }

	if ($CONFIG2['db_linkid'] == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }

	mysql_select_db($database);

	$rsid = mysql_query($SQLQuery, $CONFIG2['db_linkid']);
	if ($rsid == False) { dbReportError2(mysql_errno(),mysql_error(),$SQLQuery); }
	if ($DBReturnID == True) {
		$DBReturnID = mysql_insert_id($CONFIG2['db_linkid']);
	} else {
		$DBReturnID = True;
	}

	$DB_Count++;

	return($DBReturnID);
}


function dbReportError2($ErrorNumber,$ErrorMsg,$SQLQuery) {

	print "An error occured while connecting to the database<br>";
	print "<strong>$ErrorNumber</strong>";
	print $ErrorMsg;
	exit;
}
