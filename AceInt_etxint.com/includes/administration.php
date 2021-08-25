<?

if(!checkmodule("Downloads")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>
<html>
<head>
<script language="JavaScript" type="text/javascript">

function ChangeCountry(list) {
 var url = 'https://admin.etxint.com/body.php?page=administration&tab=tab2&area=' + list.options[list.selectedIndex].value;
 if (url != "") {
  location.href=url;
 }
}

</script>
</head>
<body>
<!-- <form method="POST" action="body.php?page=administration&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>"> -->

<?

// Some Setup.

 $tabarray = array(get_page_data("26"),get_page_data("28"),get_page_data("29"),get_page_data("33"),get_page_data("34"),get_page_data("60"),"Sponsorship","Member Support");
 if($_SESSION[User][CID] == 8) { $tabarray[] = get_page_data("35"); }
 if($_SESSION[User][CID] == 8) { $tabarray[] = get_page_data("36"); }
// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  forms();

} elseif($_GET[tab] == "tab2") {

  stationery();

} elseif($_GET[tab] == "tab3") {

  application();

} elseif($_GET[tab] == "tab4") {

  services();

} elseif($_GET[tab] == "tab5") {

  hr();

} elseif($_GET[tab] == "tab6") {

  include("/virtual/preview/htdocs/snyper/MatOrder.php");

} elseif($_GET[tab] == "tab7") {

  spon();

} elseif($_GET[tab] == "tab8") {

  cs();

} elseif($_GET[tab] == "tab9") {

  lagreement();

} elseif($_GET[tab] == "tab10")  {

  howto();
}



?>

<!-- </form> -->
</body>
</html>
<?

function forms() {

 ?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("26") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	    <?if($_SESSION['Country']['countryID'] == 1) {?>
	     <li><a href="downloads/pdf/variation.pdf" class="nav">Variation Acknowledgement</a></li>
		<?}?>
	     <li><a href="downloads/pdf/get_publication.php?file=registration&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Membership Registration</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D026&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("6") ?> - D026</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D021&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("7") ?> - D021</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D035&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("8") ?> - D035</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D036&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("9") ?> - D036</a></li><br><br>

	     <li><a href="downloads/pdf/staff_account.pdf" class="nav">Staff Account Request</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D037&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("10") ?> - D037</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D059&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Joint Account Holder Request - D059</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D023&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("12") ?> - D023</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=close_account&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Close of account notification</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=sold_business&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Sale of Business notification</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D039&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("18") ?> - D039</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=change_account&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Change of Account Details</a></li><br><br>
	     <?if($_SESSION['Country']['countryID'] == 1) {?>
		 <li><a href="downloads/pdf/Supplier_Statement.pdf" class="nav"><?= get_page_data("11") ?></a></li><br>
	     <li><a href="downloads/pdf/get_publication.php?file=ad_submission&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Directory Advertising</a></li><br><br>
	     <?}?>
	     <li><a href="downloads/pdf/get_publication.php?file=D027a&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("13") ?> - D027a</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D027b&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("14") ?> - D027b</a></li><br><br>

	     <li><a href="downloads/pdf/get_publication.php?file=D024a&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("15") ?> - D024a</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D024b&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("16") ?> - D024b</a></li><br><br>

	     <li><a href="downloads/pdf/get_publication.php?file=D040&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("20") ?> - D040</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D016&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("65") ?> - D016</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=privacy&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Privacy Doc</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=referrals&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Referral Doc</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=referral_form&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Referral Bonus Form</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D029&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=doc" class="nav">Purchase Order - D029</a></li>
	      <? if($_SESSION[User][CID] == 14) { ?>
	     <li><a href="downloads/pdf/get_publication.php?file=new_members&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=doc" class="nav">Elso Kontakt Adatlap</a></li>
	      <?}?>
	      <? if($_SESSION[User][CID] == 12) { ?>
	     <li><a href="downloads/pdf/get_publication.php?file=fac_proc&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">VK SZABALYZAT- Kepviselet</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=bplan&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">VKUT</a></li>
	      <?}?>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

function stationery() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<?//if($_SESSION['User']['ReportsAllowed'] != 'all') {?>
	<tr>
	 <td align="center" class="Heading2"><?= get_word("25") ?>:
	  <select name="area" id="area" onChange="ChangeCountry(this);">
          <?
          if($_SESSION['User']['ReportsAllowed'] == 'all')  {
			  $areas = "";
          }  else  {
              $count=0;
              $newarray = explode(",", $_SESSION['User']['ReportsAllowed']);
   			  foreach($newarray as $cat_val) {
    			if($count == 0) {
    			 $andor="";
 				} else {
 				 $andor=",";
				}

 				$cat_array.="".$andor."".$cat_val."";

 				$count++;

 			  }
 			  $areas = " and (FieldID in ($cat_array))";
		   }

           $query2 = dbRead("select place,FieldID from area where CID='".$_SESSION['Country']['countryID']."'$areas group by place order by place");
           while($row2 = mysql_fetch_assoc($query2)) {

            $areatemp = ($_REQUEST['area']) ? $_REQUEST['area'] : $_SESSION['User']['Area'];

            ?>
            <option <? if ($row2[FieldID] == $areatemp) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[place] ?></option>
            <?
           }
          ?>
          </select>
        </td>
    </tr>
    <?//}
    if($_REQUEST['area'])  {
     $areacode = $_REQUEST['area'];
    }  else  {
     $areacode = $_SESSION['User']['Area'];
    }
    ?>
    <tr>
		<td align="center" class="Heading2"><?= get_page_data("28") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <?if(checkmodule("LicReports")) {?>
	     <form target="_blank" method="POST" action="https://admin.etxint.com/includes/emailtemplate.htm" name="emailform">
	     <input type="hidden" name="area" value="<?= $areacode ?>">
	     <li><a href="javascript:document.emailform.submit();" class="nav"><?= get_page_data("41") ?></a>
	      <select name="staff" id="staff">
	       <?
	       $query2 = dbRead("select Name, FieldID from tbl_admin_users where Area='".$areacode."' and EmailAddress != '' and Name != '' and (Suspended != '1' or SalesPerson = 1) order by Name");
           while($row2 = mysql_fetch_assoc($query2)) {
            ?>
            <option <? if ($row2[FieldID] == $_SESSION['User']['FieldID']) { echo "selected "; } ?>value="<?= $row2[FieldID] ?>"><?= $row2[Name] ?></option>
          <?}
          } else {
           ?>
             <li><a href="includes/emailtemplate.htm?area=<?= $areacode ?>" class="nav"><?= get_page_data("41") ?></a>
          <?
          }
          ?>
          </select></li></form>
	     <li><a href="downloads/stationery/lhead_col-<?= $areacode ?>.doc" class="nav"><?= get_page_data("38") ?> .doc</a></li>
	     <li><a href="downloads/stationery/lhead_col-<?= $areacode ?>.pdf" class="nav"><?= get_page_data("38") ?> .pdf</a></li>
	     <li><a href="downloads/stationery/fax-<?= $areacode ?>.doc" class="nav"><?= get_page_data("39") ?> </a></li><br><br>
	     <li><a href="downloads/stationery/comp-<?= $areacode ?>.doc" class="nav"><?= get_page_data("40") ?> .doc</a></li><br>
	     <li><a href="downloads/stationery/comp-<?= $areacode ?>.pdf" class="nav"><?= get_page_data("40") ?> .pdf</a></li><br>
		<?if($_SESSION['Country']['countryID'] == 15) {?>
	     <li><a href="downloads/templates/get_publication.php?file=cheque_sheets&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data(47) ?></a></li>
	     <li><a href="downloads/templates/get_publication.php?file=cheque_covers&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data(44) ?></a></li>
	     <li><a href="downloads/templates/get_publication.php?file=cheque_balance&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data(50) ?></a></li>
	     <li><a href="downloads/templates/get_publication.php?file=cheque_reminders&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data(49) ?></a></li>
		<?}?>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

function application() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("29") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="downloads/pdf/get_publication.php?file=D017&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("21") ?> - D017</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D032&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("53") ?> - D032</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=membership_guidelines&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Membership Application Guidelines</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=app_book_guidelines&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Guide to Completing Membership Application</a></li>
		 <br><br>
	     <li><a href="downloads/pdf/get_publication.php?file=application_50&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">50% Plus Club Application</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=application_gold&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Gold Club Application</a></li>
		 <br><br>
	     <li><a href="downloads/pdf/get_publication.php?file=Realestate_Agreement&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("51") ?></a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

function services() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("33") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="downloads/services/get_publication.php?file=D041&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("55") ?> - D041</a></li>
	     <li><a href="downloads/services/get_publication.php?file=D042&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("66") ?> - D042</a></li>
	     <li><a href="downloads/services/get_publication.php?file=mall&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("56") ?></a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

function hr() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("34") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <?if($_SESSION[User][AreasAllowed] == 'all') {?>
	    <ul>
	     <li><a href="downloads/hr/get_publication.php?file=D052&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Leave Request Form - D052</a></li>
	     <li><a href="downloads/hr/get_publication.php?file=D053&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Induction Evaluation - D053</a></li>
	     <li><a href="downloads/hr/get_publication.php?file=D054&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">3 Month Evaluation - D054</a></li>
	     <li><a href="downloads/hr/get_publication.php?file=D055&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">6 Month Evaluation - D055</a></li>
	     <li><a href="downloads/hr/get_publication.php?file=D050&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Employee Details Form - D050</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D040&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Intranet Access Request - D040</a></li>
	     <li><a href="downloads/pdf/get_publication.php?file=D016&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav"><?= get_page_data("65") ?> - D016</a></li>
	    </ul>
	    <?}?>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}
function spon() {
?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("33") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="downloads/sponsorship/get_publication.php?file=sp_guidelines&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Sponsorship Guidelines</a></li>
	     <li><a href="downloads/sponsorship/get_publication.php?file=D028&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Empire Trade Funding Application - D028</a></li>
	     <li><a href="downloads/sponsorship/get_publication.php?file=D046&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=doc" class="nav">Empire Trade Funding Agreement - D046</a></li>
	     <li><a href="downloads/sponsorship/get_publication.php?file=sp_checklist&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Sponsorship Checklist</a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>
<?
}

function training() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("35") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <ul>
	     <li><a href="downloads/pdf/result_may04.pdf" class="nav">May Result 04 </a></li>
	     <li><a href="downloads/pdf/comments-vn.pdf" class="nav"><?= get_page_data("5")?></a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

function lagreement() {
?>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("36") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <ul>
	     <li><a href="downloads/pdf/CoverLetter.pdf" class="nav"><?= get_page_data("1")?></a></li>
	     <li><a href="downloads/pdf/ThoaUocBaoMat.pdf" class="nav"><?= get_page_data("2")?></a></li>
	     <li><a href="downloads/pdf/HopDongDaiLy.pdf" class="nav"><?= get_page_data("3")?></a></li>
	     <li><a href="downloads/pdf/PhuLucDieuHanhHoatDongLicensee.pdf" class="nav"><?= get_page_data("4")?></a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?
}

function howto() {
?>
<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2">How to install desktop</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
To install this background on your computer:</FONT></B>
<P></P>
<OL style="MARGIN-TOP: 0cm" type=1>
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">&nbsp;Select the appropriate image by identifying the screen resolution&nbsp;set for your computer.&nbsp; To do this
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Minimise all windows until you can see your desktop
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Right click on your desktop. In the context menu that comes up, click �Properties�
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Then select the "Settings" tab.
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">On the lower right of the display box you will see "Screen area" and under that a slid scale with less and More on either side.&nbsp; Below this slide scale is your screen resolution.
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Going back to the intranet, select the file that matches your screen resolution and right click on it.&nbsp; Choose "Save Target as" then browse to the 'My Documents' folder and save the file. </LI></OL>
<DIV class=MsoNormal style="MARGIN: 0cm 0cm 0pt; mso-list: l0 level1 lfo1; tab-stops: list 36.0pt">Once you have determined your screen resolution, follow these steps:</DIV>
<OL style="MARGIN-TOP: 0cm" type=1>
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Right click on your desktop. In the&nbsp;&nbsp;display box that comes up, click �Properties�
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Select the "Background" tab at the top.
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Click the �Browse� button and go to your �My Documents folder�
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Select the image that you have downloaded and click �ok�
<LI class=MsoNormal style="MARGIN: 0cm 0cm 0pt">Click �Ok"</LI></OL>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>
<?}

function cs() {
?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2">Customer Support</td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
	     <li><a href="downloads/cs/get_publication.php?file=newmember&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">New Member Visits</a></li>
	     <li><a href="downloads/cs/get_publication.php?file=checklist&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">New Member Contact Check List</a></li>
	     <li><a href="downloads/cs/get_publication.php?file=busexp&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Business Expense Checklist</a></li>
	     <li><a href="downloads/cs/get_publication.php?file=analysis&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Expense Analysis</a></li>
	     <li><a href="downloads/cs/get_publication.php?file=code&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Intranet Coding Guide</a></li>
	     <li><a href="downloads/cs/get_publication.php?file=admin_fee&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Administration Fee Guidelines</a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>
<?
}
?>