<?

 /**
  * Currency Convert Script for Intranet.
  *
  * currency_convert.php
  * Version 0.1
  */

 ?>
 <body onload="javascript:setFocus('CurrencyConvert','Amount');">
 <form method="get" action="body.php" name="CurrencyConvert">
 <input type="hidden" name="page" value="currency_convert">
 <?

 if($_REQUEST['NextForm']) {

  add_kpi("59", "0");
  first_form();
  result_form();

 } else {

  first_form();

 }

 ?>
 </form>
 </body>
 <?


 /**
  * Functions.
  */

 function result_form() {

  $ConvertFromCountry = mysql_fetch_assoc(dbRead("select * from country where `convert` = '".$_REQUEST['ConvertFrom']."'"));
  $ConvertToCountry = mysql_fetch_assoc(dbRead("select * from country where `convert` = '".$_REQUEST['ConvertTo']."'"));
  $Rates = get_rates($_REQUEST['ConvertFrom'],$_REQUEST['ConvertTo'],$_REQUEST['Amount']);

  ?>
  <table border="0" cellpadding="1" cellspacing="1" width="610">
   <tr>
    <td class="Border">
     <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr>
	   <td colspan="2" align="center" class="Heading2"><?= get_page_data("2") ?>.</td>
	  </tr>
	  <tr>
	   <td class="Heading2" width="150"><?= get_page_data("3") ?>:</td>
	   <td bgcolor="#FFFFFF"><?= $ConvertFromCountry['currency'] ?>&nbsp;<?= number_format($_REQUEST['Amount'],2) ?>&nbsp;<?= $_REQUEST['ConvertFrom'] ?></td>
	  </tr>
	  <tr>
	   <td class="Heading2"><?= get_page_data("4") ?>:</td>
	   <td bgcolor="#FFFFFF"><?= $ConvertToCountry['currency'] ?>&nbsp;<?= number_format($Rates['Amount'],2) ?>&nbsp;<?= $_REQUEST['ConvertTo'] ?></td>
	  </tr>
	  <tr>
	   <td class="Heading2"><?= get_page_data("6") ?>:</td>
	   <td bgcolor="#FFFFFF">1 <?= $_REQUEST['ConvertFrom'] ?> == <?= number_format($Rates['Rate'],10) ?> <?= $_REQUEST['ConvertTo'] ?></td>
	  </tr>
	 </table>
	</td>
   </tr>
  </table>
  <?

 }

 function first_form() {

  ?>
  <table border="0" cellpadding="1" cellspacing="1" width="610">
   <tr>
    <td class="Border">
     <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr>
	   <td colspan="2" align="center" class="Heading2"><?= get_page_data("1") ?>.</td>
	  </tr>
	  <tr>
	   <td width="150" align="right" class="Heading2"><b><?= get_word("61") ?>:</b></td>
	   <td width="450" bgcolor="#FFFFFF"><input type="text" name="Amount" size="16" value="<?= $_REQUEST['Amount'] ?>" onKeyPress="return number(event)"></td>
	  </tr>
	  <tr>
	   <td width="150" align="right" class="Heading2"><b><?= get_page_data("3") ?>:</b></td>
	   <td width="450" bgcolor="#FFFFFF">
	   <?

        $sql_query = dbRead("select * from country order by name");
        form_select('ConvertFrom',$sql_query,'name','convert',$_REQUEST['ConvertFrom'],false,false,false,true);

	   ?>
	   </td>
	  </tr>
	  <tr>
	   <td width="150" align="right" class="Heading2"><b><?= get_page_data("4") ?>:</b></td>
	   <td width="450" bgcolor="#FFFFFF">
	   <?

        $sql_query = dbRead("select * from country order by name");
        form_select('ConvertTo',$sql_query,'name','convert',$_REQUEST['ConvertTo'],false,false,false,true);

	   ?>
	   </td>
	  </tr>
	  <tr>
	   <td class="Heading2">&nbsp;</td>
	   <td bgcolor="#FFFFFF"><input type="submit" value="<?= get_page_data("5") ?>"></td>
	  </tr>
	 </table>
	</td>
   </tr>
  </table>
  <input type="hidden" name="NextForm" value="1">
  <?

 }

?>