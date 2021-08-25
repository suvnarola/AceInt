<? require("includes/global.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
<!--
.Border {
	background-color: #304C78; }
.Heading     {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: bold;
	color: #000000;
	background-color: #97A5BB; }
a.nav:link {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	text-decoration: none; }
a.nav:visited {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	text-decoration: none; }
a.nav:hover {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	text-decoration: underline; }
a.nav2:link {
	font-family: Tahoma;
	font-size: 12pt;
	font-weight: strong;
	color: #000000;
	background-color: #FFFFFF;
	text-decoration: none; }
a.nav2:visited {
	font-family: Tahoma;
	font-size: 12pt;
	font-weight: strong;
	color: #000000;
	background-color: #FFFFFF;
	text-decoration: none; }
a.nav2:hover {
	font-family: Tahoma;
	font-size: 12pt;
	font-weight: strong;
	color: #000000;
	background-color: #FFFFFF;
	text-decoration: underline; }
td {
	font-family: Tahoma;
	font-size: 8pt;
	font-weight: normal;
	color: #000000;
	background-color: #FFFFFF; }
-->
</style>
<base target="main">
</head>

<body bgcolor="#FFFFFF" topmargin="2" leftmargin="2">

<!-- START NAVIGATION -->


<?
if(date("m", mktime(0,0,0,date("m"),1,date("Y"))) == 12)  {
 $con = "true";
} else {
 $con = "false";
}


 $AdminArray = array(
 	get_page_data("1")		=>	array(
 		"Check" =>	false,
 		"Label" =>	"",
 		"URL"	=>	"body.php?page=contacts&tab=tab1"),
 	get_page_data("2")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"Downloads",
 		"URL"	=>	"body.php?page=administration&tab=tab1"),
 	get_page_data("3")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Downloads",
 		"URL"	=>	"body.php?page=downloads"),
 	get_page_data("4")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"Downloadss",
 		"URL"	=>	"body.php?page=publications"),
 	get_page_data("88")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"Downloads",
 		"URL"	=>	"body.php?page=procs&tab=tab1"),
 	get_page_data("5")=>	array(
 		"Check" =>	true,
 		"Label" =>	"LicEmail,HQEmail,PrintLabels",
 		"URL"	=>	"body.php?page=listshq&tab=tab2"),
 	get_page_data("89")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"HQSend",
 		"URL"	=>	"body.php?page=email_system/defaultnew&tab=tab1&pageno=1"),
 	get_page_data("6")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"Notes",
 		"URL"	=>	"body.php?page=notescenter&tab=tab1"),
 	get_page_data("7")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"HQSendd",
 		"URL"	=>	"body.php?page=emailsellect_send"),
 	get_page_data("10")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"PrintCheque",
 		"URL"	=>	"body.php?page=printchequeview&tab=tab1"),
 	get_page_data("8")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"LicReports,Newsletters",
 		"URL"	=>	"body.php?page=newsletters_manage"),
 	"Help System"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"SuperUser",
 		"URL"	=>	"https://admin.etxint.com/adminup/helpsystem/admin/select-doc.php")
 );

 $TransactionsArray = array(
 	get_page_data("11")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Transaction",
 		"URL"	=>	"body.php?page=TransferNew"),
 	get_page_data("12")	=>	array(
 		"Check"	=>	false,
 		"Label"	=>	"",
 		"URL"	=>	"body.php?page=currency_convert"),
 	get_page_data("13")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"IntAuthCheck",
 		"URL"	=>	"body.php?page=auth_inter"),
 	get_page_data("14")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"AuthCheck",
 		"URL"	=>	"body.php?page=auth_search"),
 	get_page_data("15")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"AuthEdit",
 		"URL"	=>	"body.php?page=auth_edit"),
 	get_page_data("16")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Scheduled",
 		"URL"	=>	"body.php?page=trans_scheduled&tab=tab1"),
 	get_page_data("17")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"DDUpload",
 		"URL"	=>	"body.php?page=dd_file"),
 	get_page_data("18")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Facility",
 		"URL"	=>	"body.php?page=changefacility"),
 	get_page_data("19")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"REFacility",
 		"URL"	=>	"body.php?page=changefacility2"),
 	get_page_data("20")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Conversion",
 		"URL"	=>	"body.php?page=conversion"),
 );

 $MembersArray = array(
 	get_page_data("21")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"AddMember",
 		"URL"	=>	"body.php?page=member_add"),
 	get_page_data("22")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"NetMem",
 		"URL"	=>	"body.php?page=netmem"),
 	get_page_data("23")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"AddMember",
 		"URL"	=>	"body.php?page=newmembersdocs"),
 	"Send Promo"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Summary",
 		"URL"	=>	"body.php?page=promo_email"),
 	get_page_data("24")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Summary",
 		"URL"	=>	"body.php?page=summary"),
 	get_page_data("25")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Graphs",
 		"URL"	=>	"body.php?page=graphs"),
 	get_page_data("26")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"MemOrder",
 		"URL"	=>	"body.php?page=ordercheque"),
 	get_page_data("93")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"MemOrder",
 		"URL"	=>	"body.php?page=orderdirect"),
 	get_page_data("27")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"MemOrderee",
 		"URL"	=>	"body.php?page=ordermemcards"),
 	get_page_data("90")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"SendTaxInv",
 		"URL"	=>	"body.php?page=taxinvoiceselect"),
 	get_page_data("29")=>	array(
 		"Check" =>	true,
 		"Label" =>	"SendXmas",
 		"URL"	=>	"body.php?page=sendxmas"),
 	"Club Admin"=>	array(
 		"Check" =>	true,
 		"Label" =>	"Clubs",
 		"URL"	=>	"body.php?page=club_admin&tab=tab2"),
 	"Create Invoice"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Pat",
 		"URL"	=>	"body.php?page=invoicecreate&tab=tab1"),
 	"Invoice Report"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Pat",
 		"URL"	=>	"body.php?page=invoicereports&tab=tab1"),
 	"Retuned Emails"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LynReports",
 		"URL"	=>	"body.php?page=email_returned&tab=tab1"),
 );

 $AuctionArray = array(
 	get_page_data("30")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"AuctionEdit",
 		"URL"	=>	"/auction",
 		"Target"=>	"_blank"),
	"Catalogue"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"AuctionEdit",
 		"URL"	=>	"body.php?page=cat_add&tab=tab1",
 		"Target"=>	"_blank"),
 );

 $ERewards1Array = array(
 	get_page_data("31")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsCheck",
 		"URL"	=>	"body.php?page=erewards_check"),
 	get_page_data("32")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsStatement",
 		"URL"	=>	"body.php?page=erewardsstatements"),
 	get_page_data("33")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsReports",
 		"URL"	=>	"body.php?page=erewards_reports&tab=Total Paid"),
 	get_page_data("34")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsApproval",
 		"URL"	=>	"body.php?page=erewardsapprovals"),
 	get_page_data("35")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsSignupOLD",
 		"URL"	=>	"body.php?page=erewardssignup"),
 	get_page_data("36")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsChange",
 		"URL"	=>	"body.php?page=erewardscrap"),
 	get_page_data("37")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ErewardsChange",
 		"URL"	=>	"body.php?page=refererchange"),
 );

 $CreditCardArray = array(
 	get_page_data("38")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CCFees",
 		"URL"	=>	"getPage.php?page=ccfees"),
 	get_page_data("39")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CCPayments",
 		"URL"	=>	"body.php?page=ccpayments"),
 	get_page_data("40")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CCReport",
 		"URL"	=>	"body.php?page=ccreport"),
 	get_page_data("41")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CCDeclined",
 		"URL"	=>	"body.php?page=ccdeclined"),
 	get_page_data("42")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CCExpired",
 		"URL"	=>	"includes/ccexpired.php"),
 );

 $FeePaymentArray = array(
 	get_page_data("43")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"FeePayment",
 		"URL"	=>	"body.php?page=feepayment"),
 	get_page_data("44")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"REFeePayment",
 		"URL"	=>	"body.php?page=feepayment3"),
 	get_page_data("45")=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Reversals",
 		"URL"	=>	"body.php?page=cfo1"),
 	get_page_data("46")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"REReversals",
 		"URL"	=>	"body.php?page=cfo2"),
 	get_page_data("47")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ChargeFees",
 		"URL"	=>	"body.php?page=stationeryfeeadd"),
 	"Transfer Fees"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ChargeFees",
 		"URL"	=>	"body.php?page=feetransfer"),
 	get_page_data("48")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ChargeFees",
 		"URL"	=>	"body.php?page=unhonoured"),
 	"BPAY Upload"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Bpay",
 		"URL"	=>	"body.php?page=bpay_upload"),
 	"Trust Recon"=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Reversals",
 		"URL"	=>	"body.php?page=trustrecon"),
 );

 $ReportsArray = array(
 	get_page_data("49")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LynReports",
 		"URL"	=>	"body.php?page=reports_lyn&tab=tab1"),
 	get_page_data("50")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LicReports,LynReports",
 		"URL"	=>	"body.php?page=reports_comm&tab=tab1"),
 	get_page_data("53")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LicReports,StatsReports,MemReports",
 		"URL"	=>	"body.php?page=reports_members&tab=Listed"),
 	"Facility Renewals"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LynReports",
 		"URL"	=>	"body.php?page=reports_facility2&tab=Broker Driven"),
  	get_page_data("51")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LicReports",
 		"URL"	=>	"body.php?page=reports_daily&tab=tab1"),
 	get_page_data("55")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"SalesPerson",
 		"URL"	=>	"body.php?page=reports_sales"),
 	get_page_data("56")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"LogReport",
 		"URL"	=>	"body.php?page=reports_log&tab=User Log"),
 	get_page_data("57")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"SuperUser",
 		"URL"	=>	"body.php?page=reports_admin&tab=Active"),
 	get_page_data("58")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"ManReports",
 		"URL"	=>	"body.php?page=reports_man&tab=Monthly"),
 	"Feedback Forms"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"LogReport",
 		"URL"	=>	"body.php?page=feedback&tab=tab1"),
 	"Inter Reports"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"Bpay",
 		"URL"	=>	"body.php?page=reports_inter&tab=tab1"),
 );

 $MonthlyArray = array(
 	get_page_data("59")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"PrintTaxInv",
 		"URL"	=>	"body.php?page=taxinvselectmonthly"),
 	get_page_data("60")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"PrintStatements",
 		"URL"	=>	"body.php?page=statementselectmonthly"),
 	get_page_data("61")	=>	array(
 		"Check" =>	true,
 		"Label" =>	"Letters",
 		"URL"	=>	"body.php?page=letters&tab=tab1"),
 );

 $DirectArray = array(
 	"Null P/ment Type"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CCReport",
 		"URL"	=>	"body.php?page=dd"),
 );

 $SolutionsArray = array(
 	"Solutions Admin"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=solutions_admin&tab=tab1"),
 	"Solutions Statement"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=solutions_statement&tab=tab1"),
 	"CC Report"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=solutions_ccreport&tab=tab1"),
 	"Report"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=solutions_report&tab=tab1"),
 );

 $ServicesArray = array(
 	"Services Admin"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=services_admin&tab=tab1"),
 	"Services Statement"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=services_statement&tab=tab1"),
 	"CC Report"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=services_ccreport&tab=tab1"),
 	"Report"	=>	array(
 		"Check" =>	true,
 		"Label" =>	"MyServices",
 		"URL"	=>	"body.php?page=services_report&tab=tab1"),
 );

 $ClassifiedsArray = array(
 	get_page_data("62")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ClasAdd",
 		"URL"	=>	"body.php?page=clas_add"),
 	get_page_data("63")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ClasEdit",
 		"URL"	=>	"body.php?page=clas_edit"),
 	get_page_data("64")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ClasSearch",
 		"URL"	=>	"body.php?page=clas_search"),
 	get_page_data("65")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ClasPicture",
 		"URL"	=>	"body.php?page=clas_picupload"),
 	get_page_data("66")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"ClasCheck",
 		"URL"	=>	"body.php?page=clas_check"),
 );

 $RealEstateArray = array(
 	get_page_data("70")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"RESearch",
 		"URL"	=>	"body.php?page=re_search"),
 	"Real Estate Admin"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"REAdd",
 		"URL"	=>	"body.php?page=re&tab=tab1"),
 );

 $SalesPersonArray = array(
 	get_page_data("72")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"SalesAdd",
 		"URL"	=>	"body.php?page=salesadd"),
 );

 $CategoryArray = array(
 	get_page_data("73")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CatAdd",
 		"URL"	=>	"body.php?page=catadd"),
 	get_page_data("74")	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CatEdit",
 		"URL"	=>	"body.php?page=catedit"),
 	"Cat Providers"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CatDel",
 		"URL"	=>	"body.php?page=catproviders"),
 	"Cat Links"	=>	array(
 		"Check"	=>	true,
 		"Label"	=>	"CatDel",
 		"URL"	=>	"body.php?page=catlinks"),
 );

 $PrefArray = array(
 	get_page_data("92")	=>	array(
 		"Check"	=>	false,
 		"Label"	=>	"",
 		"URL"	=>	"body.php?page=ChangePassword"),
 );

 display_nav_element(get_page_data("75"),$AdminArray);
 display_nav_element(get_page_data("76"),$MembersArray);
 display_nav_element(get_page_data("77"),$TransactionsArray);
 display_nav_element(get_page_data("78"),$FeePaymentArray);
 display_nav_element(get_page_data("79"),$CreditCardArray);
 display_nav_element("Direct Debits",$DirectArray);
 display_nav_element(get_page_data("80"),$ERewards1Array);
 display_nav_element(get_page_data("81"),$ReportsArray);
 display_nav_element(get_page_data("82"),$MonthlyArray);
 display_nav_element("Solution",$SolutionsArray);
 display_nav_element("myServices",$ServicesArray);
 display_nav_element(get_page_data("83"),$AuctionArray);
 display_nav_element(get_page_data("84"),$ClassifiedsArray);
 display_nav_element(get_page_data("85"),$RealEstateArray);
 display_nav_element(get_page_data("86"),$SalesPersonArray);
 display_nav_element(get_page_data("87"),$CategoryArray);
 display_nav_element(get_page_data("91"),$PrefArray);

?>

<!-- END NAVIGATION -->

</body>

</html>

<?

 /**
  * Functions.
  */

function display_nav_element($ElementName,$Modules) {

 foreach($Modules as $ModKey => $ModValue) {

  if($ModValue['Check'] == false) {
   $DisplayNavElement = true;
   break;
  }

  if(checkmodule($ModValue['Label'])) {
   $DisplayNavElement = true;
   break;
  }

 }

 if($DisplayNavElement) {

 ?>

 <table border="0" width="119" cellspacing="0" cellpadding="0">
   <tr>
     <td width="119" class="Border">
       <table cellspacing="0" cellpadding="0" width="120">
         <tr>
           <td width="20">
           <img src="images/admin_site_3_09.gif" border="0" width="22" height="22"></td>
           <td background="images/admin_site_3_10.gif" class="Heading" width="115"><?= get_all_added_characters($ElementName) ?></td>
           <td width="14">
           <img src="images/admin_site_3_12.gif" border="0" width="13" height="22"></td>
         </tr>
       </table>
       <table cellpadding="0" cellspacing="0">
         <tr>
           <td><img src="images/spacer.gif" width="120" height="1"></td>
         </tr>
       </table>
       <table cellspacing="0" cellpadding="0" width="120">
         <tr>
           <td width="2">
           <img src="images/nav_01.gif" border="0" width="2" height="2"></td>
           <td width="119" background="images/nav_02.gif" height="2"></td>
           <td width="4">
           <img src="images/nav_03.gif" border="0" width="2" height="2"></td>
         </tr>
         <tr>
           <td width="2" background="images/nav_04.gif">
           <img src="images/spacer.gif" border="0" width="1" height="1"></td>
           <td width="119" background="images/nav_05.gif" style="padding: 3px;">
            <?

             $foo = 0;

             foreach($Modules as $ModKey => $ModValue) {

              if($ModValue['Check']) {
               if(checkmodule($ModValue['Label'])) {
                ?><? if($foo > 0) { print "<br>"; } ?>»&nbsp;<a class="nav" href="<?= $ModValue['URL'] ?>"><?= $ModKey ?></a><?
               }
              } else {
               ?><? if($foo > 0) { print "<br>"; } ?>»&nbsp;<a class="nav" href="<?= $ModValue['URL'] ?>"><?= $ModKey ?></a><?
              }

              $foo++;

             }

            ?>
           </td>
           <td width="1" border="0"  style="background: url('images/nav_07.gif'); no-repeat;&gt;
           &lt;img src="images/spacer.gif" border="0" width="1" height="1"></td>
         </tr>
         <tr>
           <td width="2">
           <img src="images/nav_09.gif" border="0" width="2" height="2"></td>
           <td width="120" background="images/nav_10.gif" height="2"></td>
           <td width="4">
           <img src="images/nav_11.gif" border="0" width="2" height="2"></td>
         </tr>
       </table>
     </td>
   </tr>
 </table>
 <table cellpadding="0" cellspacing="0" width="120">
   <tr>
     <td width="120"><img src="images/spacer.gif" width="120" height="5"></td>
   </tr>
 </table>

 <?

 }

}

?>
