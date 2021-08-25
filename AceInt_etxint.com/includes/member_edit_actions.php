<head>
<SCRIPT language=JavaScript>
function open_win(url) {
	var tvcWindow=window.open(url,'','alwaysRaised=yes,toolbar=no,scrollbars=yes,status=yes,resizable=no,menubar=no,width=450,height=450');
}
</script>
</head>
<table width="610" border="1" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
 <tr>
  <td bgcolor="#FFFFFF" align="left">
<table width="610" border="0" bordercolor="#304C78" cellpadding="3" cellspacing="0" border-style="colapse" STYLE="border-collapse: collapse">
 <tr>
  <td bgcolor="#FFFFFF" align="left">
   <ul>
    <? //if(checkmodule("EditMemberLevel2") || checkmodule("ClasCheck") || $_SESSION['User']['AreasAllowed'] == 'all') { ?>
	<li><a href="body.php?page=member_edit_communicate&next=true&Action=true&Client=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("1") ?></a></li>
	<? //} ?>
    <? if(checkmodule("AddMember")) { ?><li><a target="main" href="body.php?page=member_add&next=true&Action=true&edit2=true&memid=<?= $_REQUEST['Client'] ?>" class="nav"><?= get_page_data("2") ?></a></li><? } ?>
    <li><a class="nav" href="javascript:open_win('body.php?page=complaints&memid=<?= $_REQUEST['Client'] ?>');" class="nav"><?= get_page_data("18") ?></a></li>
   <?
   $query = dbRead("select * from members where memid = ".$_REQUEST['Client']);
   $row = mysql_fetch_assoc($query);

   if($row['status'] != 1) {?>
    <? if(checkmodule("FeePayment")) { ?><li><a href="body.php?page=feepayment&next=true&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("3") ?></a></li><? } ?>
    <? if(checkmodule("CCFees")) { ?><li><a href="getPage.php?page=ccfees&next=1&Action=true&nextPage=1&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("4") ?></a></li><? } ?>
    <? if(checkmodule("Reversals")) { ?><li><a href="body.php?page=cfo1&cfo=1&next=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("5") ?></a></li><? } ?>
    <? if(checkmodule("REFeePayment")) { ?><li><a href="body.php?page=feepayment3&next=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("6") ?></a></li><? } ?>
    <? if(checkmodule("REReversals")) { ?><li><a href="body.php?page=cfo2&cfo=1&next=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("7") ?></a></li><? } ?>
    <? if(checkmodule("Reversals")) { ?><li><a href="body.php?page=feepaymentrollover&next=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Pay RE Rollover Fee</a></li><? } ?>
    <? if(checkmodule("ChargeFees")) { ?><li><a href="body.php?page=stationeryfeeadd&GO=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("8") ?></a></li><? } ?>
    <? if(checkmodule("ChargeFees")) { ?><li><a href="body.php?page=feetransfer&GO=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("19") ?></a></li><br><? } ?>
    <? if(checkmodule("ChargeFees")) { ?><li><a href="body.php?page=unhonoured&GO=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("9") ?></a></li><br><? } ?>
   </ul>
  </td>
 </tr>
 <tr>
  <td bgcolor="#FFFFFF" align="left">
   <ul>
    <? if(checkmodule("Facility")) { ?><li><a href="body.php?page=changefacility&changefacility=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("10") ?></a></li><? } ?>
    <? if(checkmodule("REFacility")) { ?><li><a href="body.php?page=changefacility2&changefacility=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("11") ?></a></li><? } ?>
    <? if(checkmodule("Conversion")) { ?><li><a href="body.php?page=conversion&conversion=1&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_word("187") ?></a></li><br><? } ?>
   <?}?>
   </ul>
  </td>
 </tr>
 <tr>
  <td bgcolor="#FFFFFF" align="left">
   <ul>
    <li><a href="body.php?page=taxinvoiceselect&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_word("177") ?></a></li>
    <li><a href="body.php?page=statementselect&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_word("207") ?></a></li>
    <? if(checkmodule("MemOrder") && $row['status'] != 1) { ?><li><a href="body.php?page=ordercheque&addmember=true&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("14") ?></a></li><? } ?>
    <? if(checkmodule("MemOrder") && $row['status'] != 1) { ?><li><a href="body.php?page=orderdirect&addmember=true&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("20") ?></a></li><? } ?>
    <? if(checkmodule("MemOrder") && $row['status'] != 1) { ?><li><a href="body.php?page=ordermemcards&addmember=true&Action=true&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("15") ?></a></li><? } ?>
    <? if(checkmodule("ClasAdd") && $row['status'] != 1) { ?><li><a href="body.php?page=clas_add&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_Word("181") ?></a></li><? } ?>
    <? if(checkmodule("ClasAdd") && $row['status'] != 1) { ?><li><a href="body.php?page=cat_add&tab=tab1&Client=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Add Product</a></li><? } ?>
    <? if(checkmodule("WriteOff") && $row['status'] != 1) { ?><li><a href="body.php?page=solutions_process&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Promissory Note</a></li><? } ?>
    <? if(checkmodule("WriteOff") && $row['status'] != 1) { ?><li><a href="body.php?page=writeoff&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav"><?= get_page_data("17") ?></a></li><? } ?>
    <? if(checkmodule("WriteOff") && $row['status'] == 1) { ?><li><a href="body.php?page=wreactive&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Reactive Account</a></li><? } ?>
    <? if(checkmodule("WriteOff")) { ?><li><a href="body.php?page=updateadmin2&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Annual Admin Update</a></li><? } ?>
   </ul>
  </td>
 </tr>
 <tr>
  <td bgcolor="#FFFFFF" align="left">
   <ul>
   <? if(checkmodule("WriteOff")) { ?><li><a href="body.php?page=logo_picupload&memid=<?= $_REQUEST['Client'] ?>&ChangeMargin=1" class="nav">Logo Upload</a></li><? } ?>
   </ul>
  </td>
 </tr>
</table>
  </td>
 </tr>
</table>