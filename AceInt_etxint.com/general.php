<?php

 include("includes/global.php");
 include("includes/class.html.mime.mail.inc");
 include("includes/modules/class.phpmailer.php");
 include("includes/modules/class.ebancSuite.php");
 include("includes/modules/class.feepayments.php");

 $MerchantID = "ebt0022";

if($_REQUEST['addclassified']) {

	if(checkmodule("Log")) {
	 add_kpi("41", "0");
	}

	$percent = ($_REQUEST[price]/70)*30;
	$amount = $_REQUEST[tradeprice] - $percent;

	if($_REQUEST[amount] < 0)  {

	   xheader("Location: body.php?page=clas_add&id=$clasid&productname=$productname&err=1&error=2&Accept=1");

	}


	$name=addslashes($_REQUEST[name]);
	$shortdesc=addslashes($_REQUEST[shortdesc]);
	$phoneno=addslashes($_REQUEST[phoneno]);
	$emailaddress=addslashes($_REQUEST[emailaddress]);
	$suburb=addslashes($_REQUEST[suburb]);
	$postcode=addslashes($_REQUEST[postcode]);
	$productname=addslashes($_REQUEST[productname]);


	if(!$_REQUEST[emailaddress])  {

	  header("Location: body.php?page=clas_add&id=$clasid&productname=$productname&category=$category&price=$price&tradeprice=$tradeprice&type=$type&name=$name&phoneno=$phoneno&suburb=$suburb&area=$area&postcode=$postcode&picture=$picture&shortdesc=$shortdesc&err=1&error=1&Accept=1");

    } else  {

		$date=date("Y-m-d H:i:s");

		$name=addslashes($_REQUEST[name]);
		$shortdesc=addslashes($_REQUEST[shortdesc]);
		$phoneno=addslashes($_REQUEST[phoneno]);
		$emailaddress=addslashes($_REQUEST[emailaddress]);
		$suburb=addslashes($_REQUEST[suburb]);
		$postcode=addslashes($_REQUEST[postcode]);
		$productname=addslashes($_REQUEST[productname]);

		$adddet="insert into classifieds (id,date,name,shortdesc,detaildesc,phone,price,category,emailaddress,suburb,postcode,areaid,type,checked,int_check,productname,image,memid,tradeprice,CID,cid_origin) values ('','$date','".encode_text2($name)."','".encode_text2($shortdesc)."','".encode_text2($shortdesc)."','".$_REQUEST['phoneno']."','".$_REQUEST['price']."','$_REQUEST[category]','".$emailaddress."','".encode_text2($suburb)."','".$postcode."','".$_REQUEST[areaid]."','".$_REQUEST[type]."','0','1','".encode_text2($productname)."','".$image."','9845','".$_REQUEST[tradeprice]."',".$_SESSION['User']['CID'].",".$_SESSION['User']['CID'].")";

        $clasid=dbWrite($adddet,etradebanc,true);

		if($_FILES['picture']['tmp_name']) {

			$test = getimagesize($_FILES['picture']['tmp_name']);
			$ext = image_type_or_mime_type_to_extension($test['mime'], true);

			move_uploaded_file($_FILES['picture']['tmp_name'], "/home/etxint/public_html/clasimages/".$clasid."".$ext."");
			$source="/home/etxint/public_html/clasimages/".$clasid."".$ext."";
			$dest="/home/etxint/public_html/clasimages/thumb-".$clasid."".$ext."";
			$dest2="/home/etxint/public_html/clasimages/thumb2-".$clasid."".$ext."";
			copy($source, $dest);
			copy($source, $dest2);
			exec('convert -geometry 75 /home/etxint/public_html/clasimages/thumb-'.$clasid.''.$ext.' /home/etxint/public_html/clasimages/thumb-'.$clasid.''.$ext.'');
			exec('convert -geometry 150 /home/etxint/public_html/clasimages/thumb2-'.$clasid.''.$ext.' /home/etxint/public_html/clasimages/thumb2-'.$clasid.''.$ext.'');

  $maxwidth = "550";
  $imagehw = GetImageSize("/home/etxint/public_html/clasimages/".$clasid."".$ext."");
  $imagewidth = $imagehw[0];
  $imageheight = $imagehw[1];
  $imgorig = $imagewidth;

  if ($imagewidth > $maxwidth) {
    $imageprop=($maxwidth/$imagewidth);
    $imagevsize= ($imageheight*$imageprop);
    //$imageprop=($maxwidth*100)/$imagewidth;
    //$imagevsize= ($imageheight*$imageprop)/100 ;
    $imagewidth=$maxwidth;
    $imageheight=ceil($imagevsize);
    exec("convert -geometry ".$imagewidth."x".$imageheight." /home/etxint/public_html/clasimages/".$clasid."".$ext." /home/etxint/public_html/clasimages/".$clasid."".$ext);

  }
			dbWrite("update classifieds set image = '".$clasid."".$ext."' where  id = '".$clasid."'");

		} else {

			$clid="noimg.gif";
			dbWrite("update classifieds set image = '".$clid."' where  id = '".$clasid."'");

		}


		$areas = dbRead("select tbl_area_regional.FieldID, RegionalName from tbl_area_regional where tbl_area_regional.FieldID='".$_REQUEST[areaid]."'");
		$arearow = mysql_fetch_assoc($areas);
		//$message = "New Classified has been added. [$clasid]\r\n\r\n". get_word("120") .": $_REQUEST[name]\r\n". get_word("119") .":$_REQUEST[productname]\r\n". get_word("7") .":$_REQUEST[phoneno]\r\n". get_word("78") .":".$arearow['RegionalName']."\r\n". get_word("27") .":$_REQUEST[shortdesc]\r\n". get_word("121") .": $_REQUEST[price]\r\n". get_word("122") .": $_REQUEST[tradeprice]\r\n\r\nE Banc Members Section.";
		$message = "New Classified has been added. [ID: $clasid]<br><br>". get_word("120") .": $_REQUEST[name]<br>". get_word("119") .":$_REQUEST[productname]<br>". get_word("7") .":$_REQUEST[phoneno]<br>". get_word("78") .":".$arearow['RegionalName']."<br>". get_word("27") .":$_REQUEST[shortdesc]<br>". get_word("121") .": $_REQUEST[price]<br>". get_word("122") .": $_REQUEST[tradeprice]";

		//mail("classified@".$_SESSION['Country']['countrycode'].".ebanctrade.com","New Member Classified",$message);

	if($_SESSION['Country']['logo'] == 'etx') {
	  //$nn = "Empire";
	  //$ee = "empireXchange";
	} elseif($_SESSION['Country']['logo'] == 'ept') {
	  //$nn = "E Planet";
	  //$ee = "eplanettrade";
	} else {
	  //$nn = "E Banc";
	  //$ee = "ebanctrade";
	}

    // define the text.
    $text = get_html_template($_SESSION['Country']['countryID'], "Classifeds", $message);

	$clubMail = new PHPMailer();

	$clubMail->Priority = 3;
	$clubMail->CharSet = "utf-8";
	$clubMail->From = 'hq@'.$_SESSION['Country']['countrycode'].'.'.getWho($_SESSION['Country'][logo], 2);
	$clubMail->FromName = getWho($_SESSION['Country'][logo], 1)." - Web Site";
	$clubMail->Sender = 'hq@'.$_SESSION['Country']['countrycode'].'.'.getWho($_SESSION['Country'][logo], 2);
	$clubMail->Subject = 'New Member Classified';
	$clubMail->AddReplyTo('hq@'.$_SESSION['Country']['countrycode'].'.'.getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1));
	$clubMail->IsSendmail(true);
	$clubMail->Body = get_html_template($_SESSION['Country']['countryID'], "Classifeds", $message);
	$clubMail->IsHTML(true);

    $clubMail->AddAddress("classified@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1));

    //$clubMail->Send();

    unset($attachArray);
    unset($addressArray);

    $email = "classified@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION['Country']['logo'], 2);
	$addressArray[] = array(trim($email), getWho($_SESSION['Country']['logo'], 1));

	sendEmail("accounts@" . $_SESSION[Country][countrycode] .".". getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country']['logo'], 2), $subject . ' - ' . $row2[companyname], 'accounts@' . $_SESSION[Country][countrycode] .'.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray, $attachArray);

		if($ext) {
		  $im = $clasid."".$ext;
		} else {
		  $im = "noimg.gif";
		}

        $query2 = dbRead("select * from country where Display = 'Yes'");
        while ($row2 = mysql_fetch_assoc($query2)) {

			if($_REQUEST[$row2['countryID']])  {
		       dbWrite("insert into classifieds (id,date,name,shortdesc,detaildesc,phone,price,category,emailaddress,suburb,postcode,areaid,type,checked,productname,image,memid,tradeprice,CID,cid_origin,link_id) values ('','$date','".addslashes(encode_text2($name))."','".addslashes(encode_text2($shortdesc))."','".addslashes(encode_text2($shortdesc))."','phoneno','".$_REQUEST['$price']."','$_REQUEST[category]','$emailaddress','".addslashes(encode_text2($suburb))."','$postcode','$_REQUEST[area]','$_REQUEST[type]','0','".addslashes(encode_text2($productname))."','".$im."','9845','$_REQUEST[tradeprice]','$row2[countryID]','".$_SESSION['User']['CID']."','$clasid')","etradebanc","true");
			}

       }

		header("Location: body.php?page=clas_detail&id=$clasid");

	} }


if($_REQUEST[checktransactions]) {

$count = sizeof($_REQUEST[id2]);
$i = 0;
for ($i = 0; $i <= $count; $i++) {

    $q1 = dbRead("select * from transactions where id='".$_REQUEST[id2][$i]."'");
    $row1 = mysql_fetch_assoc($q1);

    $q2 = dbRead("select * from transactions where authno='".$row1['authno']."' and buy = '".$row1['sell']."'");
    $row2 = mysql_fetch_assoc($q2);

	$update="update transactions set checked='0', clear_date='".date("Y-m-d")."' where (id='$_REQUEST[id2][$i]' or id='".$row2['id']."')";
	mysql_db_query($db, $update, $linkid);
}

Header ("Location: body.php?page=auth_search&data=$_REQUEST[data]&search=true");

}

if($_REQUEST[updatestationery]) {

  $update="update members set monthlyfeecash ='0' where memid='".$_REQUEST['Client']."'";
  dbWrite($update);

  $data = $_REQUEST['Client'];
  Header ("Location: body.php?page=member_edit&Client=$data&tab=tab1&pageno=1");

}

 /**
  * Membership Payment Check.
  */
 if($_REQUEST[checkmembers]) {

  $genArray = $_REQUEST[id2];

  $count = sizeof($genArray);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {

	$update="update members set paid = 'Y' where memid='$genArray[$i]'";
	dbWrite($update);
  }

  Header ("Location: body.php?page=reports_comm&tab=tab5&data=$_REQUEST[data]&search=true");

 }

 /**
  * Member Membership Payment Check.
  */

 if($_REQUEST[checkmembers_mem]) {


 $date = date("dmy", mktime(0,0,0,date("m"),date("d"),date("Y")));
 $date2 = date("Y-m-d", mktime(0,0,0,date("m"),1-1,date("Y")));

 // define the text.
  $text = "Hi,\r\n\r\nAttached is members referral bonus payments to be Upload to Suncorp.";

 $ctotal=0;
 $net=0;
 $counter=0;

 $datee=str_pad($date, 46);

 $blah = "0                 01MET       EMPIRE TRADE AUSTRALIA    336668creditors   $datee\r\n";

 $disdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));

  $genArray2 = $_REQUEST[id2];

  $count = sizeof($genArray2);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {
 //while($row = mysql_fetch_assoc($query)) {
  $query = dbRead("select * from members where memid = '$genArray2[$i]'");
  $row = mysql_fetch_assoc($query);
  if($row['memid']) {
  $counter++;

  $bank1=explode(",", $row[refer_account]);
  $bank=chunk_split($bank1[0], 3, '-');
  $bank=rtrim($bank,"-");

  $banknumber=str_pad($bank1[1], 9, "0", STR_PAD_LEFT);

  $total=100;
  $total=$total*100;
  $totall=str_pad($total, 10, "0", STR_PAD_LEFT);

  $ctotal=$ctotal+$total;
  $name=substr($row[refer_name], 0, 32);
  $name=str_pad($name, 32);

  $memid=str_pad($row[Acc_No], 5);
  $det="ReferralBonus Pmt";

  $blah .= "1$bank$banknumber 50$totall$name$det 484-799027318880Empire Trade Aus00000000\r\n";
  }
 }

 $net=$ctotal;

 $counter=str_pad($counter, 6, "0", STR_PAD_LEFT);
 $ctotall=str_pad($ctotal, 10, "0", STR_PAD_LEFT);
 $dtotal="0000000000";
 $net=str_pad($ctotal, 10, "0", STR_PAD_LEFT);

 $blah .= "7999-999            $net$ctotall$dtotal                        $counter                                        ";

 // get the actual taxinvoice ready.
  $buffer = $blah;

 // define carriage returns for macs and pc's
  define('CRLF', "\r\n", TRUE);

 // create a new mail instance
  $mail = new html_mime_mail(array('X-Mailer: E Banc Trade'));

 // add the text in.
  $mail->add_text($text);

 // add the attachment on.
  $mail->add_attachment($buffer, 'referal_bonus_'.$date.'_'.$ctotal.'.txt', 'text/plain');
 // build the message.
  $mail->build_message();

 // send the message.
  $mail->send('Dave', $_SESSION['User']['EmailAddress'], 'Empire Accounts - Head Office', 'accounts@ebanctrade.com', 'Referral Bonus Payment Upload','Bcc: reports@ebanctrade.com');

  $genArray = $_REQUEST[id2];

  $count = sizeof($genArray);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {

	$update="update members set paid_mem = 'Y' where memid='$genArray[$i]'";
	dbWrite($update);
  }

  Header ("Location: body.php?page=reports_comm&tab=tab6&data=$_REQUEST[data]&search=true");

 }

 /**
  * Area Update.
  */

 if($_REQUEST[changearea])  {

   foreach($_REQUEST as $i => $value) {
    $TRANSFER_VARS[$i]=addslashes($value);
   }

  dbWrite("UPDATE area SET tradeq='".encode_text2($TRANSFER_VARS[tradeq])."', street='".encode_text2($TRANSFER_VARS[street])."', suburb='".encode_text2($TRANSFER_VARS[suburb])."', state='".encode_text2($TRANSFER_VARS[state])."', postcode='$TRANSFER_VARS[postcode]', phone='$TRANSFER_VARS[phone]', fax='$TRANSFER_VARS[fax]', mobile='$TRANSFER_VARS[mobile]' where FieldID='$TRANSFER_VARS[fieldid]'");

  header("Location: /body.php?page=contacts&tab=Area Update");

 }

 /**
  * Auth Check.
  */

 if($_REQUEST['checktransactions']) {

  $genArray = $_REQUEST['id2'];

  $count = sizeof($genArray);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {

	$update="update transactions set checked='0' where id='".$genArray[$i]."'";
	dbWrite($update);
  }

  Header ("Location: body.php?page=auth_search&data=".$_REQUEST['data']."&search=true");

 }

 /**
  * Delete New Members from tbl_newmem.
  */

 if($_REQUEST[deletenewmem]) {

  $genArray = $_REQUEST[delnewmem];

  $count = sizeof($genArray);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {
	dbWrite("delete from tbl_newmem where id='$genArray[$i]'");
  }

  Header ("Location: body.php?page=netmem");

 }

 /**
  * International Auth Search.
  */

 if($_REQUEST[checktransactions2]) {

  $transArray = $_REQUEST[id2];

  $count = sizeof($transArray);
  $i = 0;
  for ($i = 0; $i <= $count; $i++) {

	$update="update transactions set checked='0' where id='$transArray[$i]'";
	dbWrite($update);
  }

  Header ("Location: body.php?page=auth_inter&data=$_REQUEST[data]&search=true");

 }


 /**
  * Delete Classified.
  */

 if($_REQUEST['deleteclas']) {

  dbWrite("delete from classifieds where id='".$_REQUEST['clasno']."'");
  header("Location: /body.php?page=clas_edit");
  die;

 }

 /**
  * Change Classified.
  */

 if($_REQUEST[changeclas]) {

  if(checkmodule("Log")) {
   add_kpi("42", "0");
  }

  foreach($_REQUEST as $key => $value) {

   $TRANSFER_VARS[$key] = addslashes($_REQUEST[$key]);

  }

  dbWrite("update classifieds set name='".encode_text2($TRANSFER_VARS[name])."', shortdesc='".encode_text2($TRANSFER_VARS[shortdesc])."', detaildesc='".encode_text2($TRANSFER_VARS[detaildesc])."', phone='$TRANSFER_VARS[phone]', price='$TRANSFER_VARS[price]', category='$TRANSFER_VARS[catid5]', emailaddress='$TRANSFER_VARS[emailaddress]', suburb='".encode_text2($TRANSFER_VARS[suburb])."', postcode='$TRANSFER_VARS[postcode]', areaid='$TRANSFER_VARS[areaid]', type='$TRANSFER_VARS[type]', productname='".encode_text2($TRANSFER_VARS[productname])."', tradeprice='$TRANSFER_VARS[tradeprice]' where id='".$TRANSFER_VARS[clasno]."'");

  if($_REQUEST['all']) {
    $query2 = dbRead("select * from classifieds where link_id = '".$TRANSFER_VARS[clasno]."'");
    while($row2 = mysql_fetch_assoc($query2)) {
     dbWrite("update classifieds set name='".encode_text2($TRANSFER_VARS[name])."', shortdesc='".encode_text2($TRANSFER_VARS[shortdesc])."', detaildesc='".encode_text2($TRANSFER_VARS[detaildesc])."', phone='$TRANSFER_VARS[phone]', price='$TRANSFER_VARS[price]', category='$TRANSFER_VARS[catid5]', emailaddress='$TRANSFER_VARS[emailaddress]', suburb='".encode_text2($TRANSFER_VARS[suburb])."', postcode='$TRANSFER_VARS[postcode]', areaid='$TRANSFER_VARS[areaid]', type='$TRANSFER_VARS[type]', productname='".encode_text2($TRANSFER_VARS[productname])."', tradeprice='$TRANSFER_VARS[tradeprice]' where id='".$row2[id]."'");
    }
  }

  if($_REQUEST['check']) {
   header("Location: /body.php?page=clas_check");
  } else {
   header("Location: /body.php?page=clas_edit");
  }

  die;
 }

 /**
  * Add Realestate.
  */

 if($_REQUEST['addretodb']) {

  if(checkmodule("Log")) {
   add_kpi("36", "0");
  }

  $date=date("Y-m-d",mktime());
  $totalprice=$_REQUEST[totalprice];

  $desc = addslashes($_REQUEST[detaildesc]);

  $sendid = dbWrite("insert into realestate (agent,date,contactname,emailaddress,phone,area,price,pricetrade,shortdesc,totalprice,checked,CID,category,suburb,postcode,under) values ('".$_REQUEST[agent]."','$date','".encode_text2($_REQUEST[contactname])."','$_REQUEST[emailaddress]','".$_REQUEST[phone]."','".encode_text2($_REQUEST[area])."','".$_REQUEST[price]."','".$_REQUEST[pricetrade]."','$desc','".$_REQUEST[totalprice]."','1','".$_SESSION['User']['CID']."','".$_REQUEST[category]."','".encode_text2($_REQUEST[suburb])."','".encode_text2($_REQUEST[postcode])."','".$_REQUEST[under]."')",'etradebanc','1');

  header("Location: body.php?page=re_detail&id=$sendid");

 }

 /**
  * Delete Realeste.
  */

  if($_REQUEST['deletereclas']) {

  //$dc="delete from realestate where id='".$_REQUEST['reno']."' and agent='".$_SESSION['User']['AgentID']."'";
  dbWrite("delete from realestate where id = ".addslashes($_REQUEST['reno'])."");
  //mysql_db_query($db, $dc, $linkid);
  header("Location: body.php?page=re_edit");

  die;

 }

 /**
  * Change Realestate.
  */

 if($_REQUEST['changereclas']) {

  if(checkmodule("Log")) {
   add_kpi("37", $_REQUEST['memid']);
  }

  $dc="update realestate set contactname='".encode_text2(addslashes($_REQUEST['contactname']))."', emailaddress='".addslashes($_REQUEST['emailaddress'])."', area='".addslashes($_REQUEST['area'])."', price='".addslashes($_REQUEST['price'])."', category='".addslashes($_REQUEST['catid5'])."', pricetrade='".addslashes($_REQUEST['tradeprice'])."', shortdesc='".encode_text2(addslashes($_REQUEST['detaildesc']))."', suburb='".encode_text2(addslashes($_REQUEST['suburb']))."', postcode='".addslashes($_REQUEST['postcode'])."', phone='".addslashes($_REQUEST['phone'])."', totalprice='".addslashes($_REQUEST['totalprice'])."', under='".addslashes($_REQUEST['under'])."' where id='".addslashes($_REQUEST['reno'])."' ";
  mysql_db_query($db, $dc, $linkid);
  header("Location: body.php?page=re_edit");
  die;

 }

 /**
  * Update New Members and Send email out.
  */

 if($_REQUEST[updatenewmembersemail]) {
    $_REQUEST['new'] = 1;

    include("includes/newmemberPDF.php");

	$buffer = newMembersPDF("");

    $text = "Please check the New Members details and spelling";
    $text = get_html_template($_SESSION['User']['CID'], "Reception", $text);

   	$attachArray[] = array($buffer, 'newmem.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim("barry.g@au.empireXchange.com"), "Anna");

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), $subject, 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

  header("Location: body.php?page=newmembersdocs&sent=1");

 }

 /**
  * Update New Members and Send email out.
  */

 if($_REQUEST['updatenewmembers']) {

  add_kpi("56", "0");

  $newmemquery = dbRead("select * from area, members left outer join tbl_members_email on (members.memid = tbl_members_email.acc_no and tbl_members_email.type = 3) where (members.licensee = area.FieldID) and (datepacksent is null or datepacksent = '0000-00-00') and members.CID='".$_SESSION['User']['CID']."'");
  while($newmemrow = mysql_fetch_assoc($newmemquery)) {

  $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '".$_SESSION['User']['CID']."'"));

   if($newmemrow['email']) {

    // get category names out.
    $query = dbRead("select mem_categories.*, categories.*, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$newmemrow['memid']."' order by mem_categories.FieldID");
    $Counter = 1;
    $Categories = "";
    while($catrow = mysql_fetch_assoc($query)) {

      $Categories .= "".get_word("26")." $Counter: $catrow[category]<br>".get_word("27").": $catrow[description]<br>";

      $Counter++;

    }

    $text = "".$CountryDataRow['parta']."<br><br>".$CountryDataRow['partb']." ".$_SESSION['Country']['phone']." ".$CountryDataRow['partc']."<br><br>".get_word("50").": $newmemrow[memid]<br>".get_word("4").": $newmemrow[accholder]<br>".get_word("5").": $newmemrow[contactname]<br>".get_word("3").": $newmemrow[companyname]<br>".get_word("9").": $newmemrow[email]<br>".get_word("28").": $newmemrow[webpageurl]<br>".get_word("7").": $newmemrow[phonearea] $newmemrow[phoneno]<br>".get_word("8").": $newmemrow[faxarea] $newmemrow[faxno]<br>".get_word("11").": $newmemrow[homephonearea] $newmemrow[homephone] (".get_word("210").")<br>".get_word("10").": $newmemrow[mobile]<br>$Categories (".get_word("211").")<br>".get_word("129").": $newmemrow[streetno] $newmemrow[streetname] $newmemrow[suburb] $newmemrow[city] $newmemrow[state] $newmemrow[postcode]<br>".get_word("93").": $newmemrow[postalno] $newmemrow[postalname] $newmemrow[postalsuburb] $newmemrow[postalcity] $newmemrow[postalstate] $newmemrow[postalpostcode]<br><br><br>Accounts Department"; //mail($emailaddress, "New Membership Details", $text, "From: E Banc Accounts <accounts@ebanctrade.com>\r\nReply-To: New Accounts <newaccounts@".$_SESSION['Country']['countrycode'].".ebanctrade.com>\r\nErrors-To: New Accounts <newaccounts@".$_SESSION['Country']['countrycode'].".ebanctrade.com>\r\n");

    // define the text.
    $text = get_html_template($_SESSION['User']['CID'], $newmemrow['contactname'], $text);

    $subject = get_word("193");
if($ff) {
    $this->Mail = new PHPMailer();

    $this->Mail->Priority = 3;
    $this->Mail->CharSet = "utf-8";
    $this->Mail->From = "accounts@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2);
    $this->Mail->FromName = getWho($_SESSION['Country'][logo], 1)." - Accounts";
    $this->Mail->Sender = "accounts@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2);
    $this->Mail->Subject = $subject;
    $this->Mail->AddReplyTo("newaccounts@".$_SESSION['Country']['countrycode'].".".getWho($_SESSION['Country'][logo], 2), "NewMemberChanges");
	$this->Mail->IsSendmail(true);
    $this->Mail->Body = $text;
    $this->Mail->IsHTML(true);

    $this->Mail->AddAddress($newmemrow['email'], $newmemrow['accholder']);

    //$this->Mail->Send();
}
     unset($attachArray);
     unset($addressArray);
     unset($addressBCCArray);

	if(strstr($newmemrow['email'], ";")) {
		$emailArray = explode(";", $newmemrow['email']);
		foreach($emailArray as $key => $value) {
			$addressArray[] = array(trim($value), "Agent");
		}
	} else {
		$addressArray[] = array(trim($newmemrow['email']), "Agent");
	}

  	 sendEmail("newaccounts@".$_SESSION['Country'][countrycode]."." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'newaccounts@'.$_SESSION['Country'][countrycode].'.' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), "New Membership - ".$licrow['place'], 'newaccounts@' . $_SESSION[Country][countrycode] . '.' . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

   }

   $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
   $remdate = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+14,date("Y")));
   $re = "New Member Call Required (".$remdate.")";
   dbWrite("insert into notes (memid,date,userid,type,reminder,note) values ('".$newmemrow[memid]."','".$curdate."','".$newmemrow[user]."','1','".$remdate."','".$re."')");

  }

  include("includes/newmemberPDF.php");

  $licquery = dbRead("select licensee from members where (datepacksent is null or datepacksent = '0000-00-00') and CID='".$_SESSION['User']['CID']."' group by licensee");
  while($licrow = mysql_fetch_assoc($licquery)) {

     $licmemquery = dbRead("select * from area where FieldID = ".$licrow['licensee']."");
     $licmemrow = mysql_fetch_assoc($licmemquery);

     $_REQUEST['new'] = 1;
     $_REQUEST['newlic'] = $licrow['licensee'];

     $buffer = newMembersPDF("newlic");

     $text = "Attached is a list of new members that have been placed in your agency area. Please note these members may be located in a different physical area, so please check their account details online.<br><br>Members Accounts";
     $text = get_html_template($_SESSION['User']['CID'], "Agent", $text);
     define('CRLF', "\r\n", TRUE);

     unset($attachArray);
     unset($addressArray);

     $attachArray[] = array($buffer, 'newmem.pdf', 'base64', 'application/pdf');
	 $addressArray[] = array(trim($licmemrow['email']), "Agent");

  	 sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@'.$_SESSION['Country']['countrycode'].'.' . $_SESSION['Country']['countrycode'] . getWho($_SESSION['Country']['logo'], 2), "New Membership - ".$licrow['place'], 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray, $attachArray, $addressBCCArray);

  }

	$buffer2 = newMembersPDF("");

    $text = "New Members added today";
    $text = get_html_template($_SESSION['User']['CID'], "MS", $text);

   	$attachArray[] = array($buffer2, 'newmem.pdf', 'base64', 'application/pdf');
	$addressArray[] = array(trim("msteam@au.etxint.com"), "MSTeam");

	sendEmail("accounts@au." . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), $subject, 'accounts@' . $_SESSION[Country][countrycode] . getWho($_SESSION['Country'][logo], 2), getWho($_SESSION['Country'][logo], 1) .' Accounts', $text, $addressArray, $attachArray);

  $date=date("Y-m-d");
  dbWrite("update members set datepacksent='$date' where (datepacksent is null or datepacksent = '0000-00-00') and CID='".$_SESSION['User']['CID']."'");
  header("Location: body.php?page=mem_search");

 }

 /**
  * Approve New Erewards Members.
  */

 if($_REQUEST[approvenewmem]) {

  $date_now = date("Y-m-d");
  $amount = "214.50";
  $ccamount = "21450";
  //$Debug = 1;

  foreach($approvemem as $i) {

   if($i != '') {

    // We need to try and charge the card now.

    $ponum = add_temp_trans($row[memid],$row[feesowe]);

    $query = dbRead("select * from members where memid='$i'");
    $row = mysql_fetch_assoc($query);

    if($row[accountno] != "4321432143214321") {

     $cc1 = substr($row[accountno], 0, 4);
     $cc2 = substr($row[accountno], 4, 4);
     $cc3 = substr($row[accountno], 8, 4);
     $cc4 = substr($row[accountno], 12, 4);

     $exdate_temp = explode("/", $row[expires]);

     $exdate1 = $exdate_temp[0];
     $exdate2 = $exdate_temp[1];

     $SecureResponse = Process_Credit_Card($MerchantID,$ccamount,$ponum,$cc1,$cc2,$cc3,$cc4,$exdate1,$exdate2,$row[companyname]);

     // see if the credit card processed.

     if($Debug) {
      echo "<pre>";
      var_dump($SecureResponse);
      echo "</pre>";
     }

    } else {

     $SecureResponse[successfull] = "1";

    }

    // update type of transaction.
    dbWrite("update credit_transactions set type='3' where FieldID='$ponum'");

    if($SecureResponse[successfull] == 1) {

     // successfull.

     dbWrite("update credit_transactions set success='Yes', amount='$amount', response_code='$SecureResponse[response_code]', response_text='$SecureResponse[response_text]', sp_trans_id='$SecureResponse[txn_id]', card_type='$SecureResponse[card_type]', card_name='$SecureResponse[optional_info]' where FieldID='$SecureResponse[ponum]'");

     // now we need to check to see what number is in the rewards column.
     // if its 1 then the referer wants the erewards so leave him in the referer and run the rewards script.
     // if its 2 then we need to put $100 into lyns table and not run the rewards script. also set the referer back to 0 for this member.
     // both cases we need to set the members erewards to 9.

     if($row[erewards] == 1) {

      // run the rewards script.

      if($row[referedby] != 0) {
       add_referal($row[referedby],$i);
      }

      mysql_db_query($db, "update members set erewards='9', reward_datejoined = ".date("Y-m-d")." where memid='$i'", $linkid);

     } elseif($row[erewards] == 2) {

      // add $100 into lyns table and set members referer to 0 if there is a referer.

      if($row[referedby] != 0) {
       mysql_db_query($db, "insert into erewards_bank (type,memid,date,amount_cash) values ('3','$row[referedby]','$date_now','100.00')", $linkid);
      }

      mysql_db_query($db, "update members set referedby='0', erewards='9', reward_datejoined = ".date("Y-m-d")." where memid='$i'", $linkid);

     }

     $errormsg .= get_error($SecureResponse[response_code])." [$i]";
     $errormsg .= "<br>";

    } elseif($SecureResponse[successfull] == 2) {

     // unsuccessfull.

     $errormsg .= get_error($SecureResponse[response_code])." [$i]";
     $errormsg .= "<br>";
     dbWrite("update credit_transactions set success='No', amount='$amount', response_code='$SecureResponse[response_code]', response_text='$SecureResponse[response_text]', sp_trans_id='$SecureResponse[txn_id]', card_type='$SecureResponse[card_type]', card_name='$SecureResponse[optional_info]' where FieldID='$SecureResponse[ponum]'");

    }

   }

  }

  header("Location: body.php?page=erewardsapprovals&errormsg=$errormsg");

 }

 /**
  * Edit Transaction.
  */

 if($_REQUEST['edittrans']) {

  $dis_date = $_REQUEST[dis_date];
  $buy = $_REQUEST[buy];
  $sell = $_REQUEST[sell];
  $memid = $_REQUEST[memid];
  $to_memid = $_REQUEST[to_memid];
  $details = $_REQUEST[details];
  $dollarfees = $_REQUEST[dollarfees];
  $id = $_REQUEST[id];
  $cleared = $_REQUEST[cleared];
  $authno = $_REQUEST[authno];
  $redirectpage = $_REQUEST['redirectpage'];

  //if(!$memid[1]) {

  //} else {

    //if($memid[0] != $to_memid[1] || $memid[1] != $to_memid[0] || $buy[0] != $sell[1] || $buy[1] != $sell[0] || $dis_date[0] != $dis_date[1]) {
    if($dis_date[0] != $dis_date[1]) {

	  $error = "Date are not equal, Please correct.";
      Header("Location: body.php?page=auth_edit&data=$authno[0]&search=true&redirectpage=$redirectpage&error=$error");
      die;
    }

    //if((date("m", strtotime($dis_date[0])) != date("m") || date("Y", strtotime($dis_date[0])) != date("Y")) && $_REQUEST['dis_date']) {

	  //$error = "Date can not be in previous mouths, Please correct.";
      //Header("Location: body.php?page=auth_edit&data=$authno[0]&search=true&redirectpage=$redirectpage&error=$error");
      //die;
    //}

  //}

  $count = 0;

  foreach($id as $key) {

   $date_temp = explode("-", $dis_date[$count]);
   $epoch = mktime(1,1,1,$date_temp[1],$date_temp[2],$date_temp[0]);

   $newdetails = addslashes($details[$count]);

   //$query = "update transactions set date='$epoch', dis_date='$dis_date[$count]', buy='$buy[$count]', sell='$sell[$count]', memid='$memid[$count]', to_memid='$to_memid[$count]', dollarfees='$dollarfees[$count]', checked='$cleared[$count]', details='".encode_text2($newdetails)."' where id='$key' limit 1";
   $query = "update transactions set date='$epoch', dis_date='$dis_date[$count]', details='".encode_text2($newdetails)."' where id='$key' limit 1";
   dbWrite($query);

   $count++;

  }

  if($_POST[redirectpage]) {
   Header("Location: ".urldecode($_REQUEST['redirectpage'])."");
  } else {
   Header("Location: body.php?page=auth_edit&data=$_POST[data]&search=true");
  }

 }

 /**
  * Send Username/Password.
  */

 if($_REQUEST['SendLoginInfo']) {

  $query = dbRead("select * from members, tbl_members_email where (members.memid = tbl_members_email.acc_no) and members.memid='".$_REQUEST['memid']."' and tbl_members_email.type = 3");
  $row = mysql_fetch_assoc($query);

  $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '".$_SESSION['User']['CID']."'"));

  if($row['email']) {

   if(!(($row['status'] == 3) && (!checkmodule("SuperUser")))) {

    $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['memid']."','".$curdate."','".$_SESSION['User']['FieldID']."','1','Username and Password Emailed')");

    $subject = get_word("192");
    $text = "".$CountryDataRow['upparta']."<br><br>".$CountryDataRow['muser'].": $row[memusername]<br>".$CountryDataRow['mpass'].": $row[mempassword]<br><br>".$CountryDataRow['uppartb']."<br><br>Accounts Department";
    $text = get_html_template($row['CID'],$row['contactname'],$text);
    define('CRLF', "\r\n", TRUE);

    if(strstr($row['email'], ";")) {

    	$emailArray = explode(";", $row['email']);
    	foreach($emailArray as $key => $value) {

			 $addressArray[] = array(trim($value), $row['contactname']);

 		}

		//sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray);
		sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) ." Accounts", "accounts@" . $_SESSION['Country']['countrycode'] . "." . getWho($_SESSION['Country']['logo'], 2), $subject, "accounts@" . $_SESSION['Country']['countrycode'] . "." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) ." Accounts", $text, $addressArray);

 	} else {

		$addressArray[] = array(trim($row['email']), $row['contactname']);

		//sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray);
		sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) ." Accounts", "accounts@" . $_SESSION['Country']['countrycode'] . "." . getWho($_SESSION['Country']['logo'], 2), $subject, "accounts@" . $_SESSION['Country']['countrycode'] . "." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) ." Accounts", $text, $addressArray);

 	}

    header("Location: ".$_SERVER['HTTP_REFERER']."&emailed=true");
   }

  }

 }

 /**
  * Send Agent Info.
  */

 if($_REQUEST['SendAgent']) {

  $query = dbRead("select members.*, area.*, tbl_members_email.email as email2  from members, tbl_members_email, area where (members.memid = tbl_members_email.acc_no) and (members.licensee = area.FieldID) and members.memid='".$_REQUEST['memid']."' and tbl_members_email.type = 3");
  $row = mysql_fetch_assoc($query);

  $CountryDataRow = mysql_fetch_assoc(dbRead("select * from countrydata where CID = '".$_SESSION['User']['CID']."'"));

  if($row['email2']) {

   //if(!(($row['status'] == 3) && (!checkmodule("SuperUser")))) {

    $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['memid']."','".$curdate."','".$_SESSION['User']['FieldID']."','1','Agent Info Emailed')");

    $subject = get_word("25");
    $text = "Your Empire Trade regional agent details are as follow: <br><br><b>". get_word(25).":</b> $row[place]<br><b>". get_word(5).":</b> $row[tradeq]<br><b>". get_word(7).":</b> $row[phone]<br><b>". get_word(8).":</b> $row[fax]<br><b>". get_word(9).":</b> $row[email]<br><b>". get_word(129).":</b> $row[r_address]<br><b>". get_word(19).":</b> $row[p_address]<br><br>".$CountryDataRow['em_agent']."<br><br>Accounts Department";
    $text = get_html_template($row['CID'],$row['contactname'],$text);
    define('CRLF', "\r\n", TRUE);

    if(strstr($row['email2'], ";")) {

    	$emailArray = explode(";", $row['email2']);
    	foreach($emailArray as $key => $value) {

			 $addressArray[] = array(trim($value), $row['contactname']);

 		}

		sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray);

 	} else {

		$addressArray[] = array(trim($row['email2']), $row['contactname']);

		sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray);

 	}

    header("Location: ".$_SERVER['HTTP_REFERER']."&emailed=true");

   //}

  }

 }

 /**
  * Send Confirm Info.
  */

 if($_REQUEST['SendConfirm']) {

  $query = dbRead("select area.*, members.*, tbl_members_email.email as email2, tbl_area_physical.RegionalID from members, tbl_members_email, area, tbl_area_physical where (members.memid = tbl_members_email.acc_no) and (members.licensee = area.FieldID) and members.area = tbl_area_physical.FieldID and members.memid='".$_REQUEST['memid']."' and tbl_members_email.type = 3");
  $row = mysql_fetch_assoc($query);

  $Crow = mysql_fetch_assoc(dbRead("select country.*, countrydata.* from country, countrydata where (country.countryID = countrydata.CID) and Display = 'Yes' and countryID = '".$_SESSION['User']['CID']."' order by countryID"));

  if($row['email2']) {

   //if(!(($row['status'] == 3) && (!checkmodule("SuperUser")))) {

    $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
    dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['memid']."','".$curdate."','".$_SESSION['User']['FieldID']."','1','Confirm Details Emailed')");

    $query = dbRead("select mem_categories.*, categories.category as catname, mem_categories.FieldID from mem_categories, categories where (categories.catid = mem_categories.category) and mem_categories.memid = '".$row['memid']."' order by mem_categories.FieldID");
    $Counter = 1;
    $Categories = "";
    while($catrow = mysql_fetch_assoc($query)) {

      if($catrow['category'] != 0) {
       $Categories .= "".get_word("26")." $Counter: <b>$catrow[catname]</b><br>".get_word("27")." $Counter: <b>$catrow[description]</b><br><br>";
       $Counter++;
      }

    }

	if($Categories) {
	 if(is_numeric($row['trade_per']) && $row['trade_per']) {
	  $Categories = $Categories."Last known Trade %: <b>".$row['trade_per']."%</b><br>";
	 } else {
	  $Categories = $Categories."Last known Trade %: <b>Please indicate your accepted trade %.</b><br>";
	 }
	}

	if(!$Categories || $row['t_unlist']) {
	 $Categories = $Categories." You are currently not listed in the directory at your request however, if you want to be relist in the directory please contact head office.<br><br>";
	} elseif($Categories) {
	 $Categories = $Categories."(".get_word("211").")<br><br>";
	}

    $subject = "Confirm Details";
    // define the text.
    $text1 = "".$Crow['partb']." ".$Crow['phone']." ".$Crow['partc']."<br><br>".get_word("50").": <b>$row[memid]</b><br>".get_word("4").": <b>$row[accholder]</b><br>".get_word("5").": <b>$row[contactname]</b><br>".get_word("3").": <b>$row[companyname]</b><br>".get_word("9").": <b>$row[email2]</b><br>".get_word("28").": <b>$row[webpageurl]</b><br>".get_word("7").": <b>$row[phonearea] $row[phoneno]</b><br>".get_word("8").": <b>$row[faxarea] $row[faxno]</b><br>".get_word("11").": <b>$row[homephonearea] $row[homephone]</b> (".get_word("210").")<br>".get_word("10").": <b>$row[mobile]</b> (".get_word("210").")<br><br>$Categories ".get_word("129").": <b>$row[streetno] $row[streetname] $row[suburb] $row[city] $row[state] $row[postcode]</b><br>".get_word("93").": <b>$row[postalno] $row[postalname] $row[postalsuburb] $row[postalcity] $row[postalstate] $row[postalpostcode]</b>";
    $text1 = $text1.'<br><p><b>Member Directory</b><br>To ensure you are always up to date with your fellow exchange members, we are emailing you a link to your local area directory. Simply click the <b>DOWNLOAD</b> icon and your directory will be saved to your computer. You can then print it out and take it with you, or access it from your computer. While is it valuable to have your local area directory, remember we have many members who can trade nationally.</p><p></p><p align="center"><a href="http://www.ebanctrade.com/home/directory_download.php?disarea='.$row['RegionalID'].'"target="_blank"><img src="http://media.ebanctrade.com/uploads/Image/download.jpg" width="150" height="67" border="0" alt="Download Directory" align="middle"></a></p><p></p><b>Member Site</b><br>To access the full member directory along with the latest Classifieds, Bid and Buy Auction, Product Catalogue and your latest account information <a href="http://www.empireXchange.com/members">CLICK HERE</a> to log into the member section.<br><br><br>Regards<br>'.$_SESSION['User']['Name'].'';
    $text = get_html_template($row['CID'],$row['contactname'],$text1);
    define('CRLF', "\r\n", TRUE);

    if(strstr($row['email2'], ";")) {

    	$emailArray = explode(";", $row['email2']);
    	foreach($emailArray as $key => $value) {

			 $addressArray[] = array(trim($value), $row['contactname']);

 		}

		sendEmail($_SESSION['User']['EmailAddress'], getWho($_SESSION['Country']['logo'], 1).' - '.$_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], $subject, $_SESSION['User']['EmailAddress'], getWho($_SESSION['Country']['logo'], 1).' - '.$_SESSION['User']['Name'], $text, $addressArray);

 	} else {

		$addressArray[] = array(trim($row['email2']), $row['contactname']);

		sendEmail($_SESSION['User']['EmailAddress'], getWho($_SESSION['Country']['logo'], 1).' - '.$_SESSION['User']['Name'], $_SESSION['User']['EmailAddress'], $subject, $_SESSION['User']['EmailAddress'], getWho($_SESSION['Country']['logo'], 1).' - '.$_SESSION['User']['Name'], $text, $addressArray);

 	}

    header("Location: ".$_SERVER['HTTP_REFERER']."&emailed2=true");

   //}

  }

 }

 /**
  * Send Trans Rec.
  */

 if($_REQUEST['SendTranRec']) {

	#get buyer and seller information out.

	$query2=mysql_db_query($db, "select * from transactions where authno='".$_REQUEST['authno']."' and type='1' order by id asc limit 1", $linkid);
	$row2=mysql_fetch_array($query2);

	$query5=mysql_db_query($db, "select * from transactions where authno='".$_REQUEST['authno']."' and type='2' order by id desc limit 1", $linkid);
	$row5=mysql_fetch_array($query5);

	$query1=mysql_db_query($db, "select members.*, tbl_members_email.email as email2, country.*  from members, country, tbl_members_email where (members.CID = country.countryID) and (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and memid='$row2[memid]'", $linkid);
	$query2=mysql_db_query($db, "select members.*, tbl_members_email.email as email2, country.*  from members, country, tbl_members_email where (members.CID = country.countryID) and (members.memid = tbl_members_email.acc_no) and tbl_members_email.type = 2 and memid='$row5[memid]'", $linkid);
	$buyerrow=mysql_fetch_array($query1);
	$sellerrow=mysql_fetch_array($query2);

	$newdate=explode("-", $row2[dis_date]);
	$dis_date="$newdate[2]/$newdate[1]/$newdate[0]";

	$query5 = dbRead("select position, data from tbl_admin_data where langcode='".$_SESSION['User']['lang_code']."' and pageid = '29' order by position");
	while($row3 = mysql_fetch_array($query5)) {

	 $PageData3[$row3[position]] = $row3[data];

	}

	function get_page_data3($id)  {
	  global $PageData3;
	  return $PageData3[$id];
	}

   if($buyerrow['email2'] || $sellerrow['email2']) {

    $curdate = date("Y-m-d H:i:s", mktime()+ $_SESSION['Country']['timezone']);
    //dbWrite("insert into notes (memid,date,userid,type,note) values ('".$_REQUEST['memid']."','".$curdate."','".$_SESSION['User']['FieldID']."','1','Agent Info Emailed')");

    $subject = "Empire Trade - Transaction Confirmation";

	$text = '
		<table width="639" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse">
		<tr>
		<td bgcolor="#FFFFFF">
		    '. $_SESSION['Country']['abn'].'</td>
		<td bgcolor="#FFFFFF" align="right" valign="bottom">
		    <b>'. get_page_data3("1") .'<br>'. $otherdate .'</b></td>
		</tr>
		</table>
		<hr align="left" noshade color="#000000" width="639" size="1">
		<table border="0" cellpadding="4" cellspacing="0" style="border-collapse: collapse" width="639" id="AutoNumber1">
		  <tr>
		    <td width="100%" align="left" colspan="4"><b>'. get_page_data3("2") .'.<br>'. get_word("182") .': <font color="#FF0000">'. $row2['authno'] .'</font></b></td>
		    </tr>
		  <tr>
		    <td width="25%" class="Heading2" align="right"><span lang="en-au">'. get_word("3") .':</span></td>
		    <td width="25%" bgcolor="#FFFFFF"><b>'. $buyerrow[companyname] .'</b></td>
		    <td width="25%" class="Heading2" align="right"><span lang="en-au">'. get_word("3") .':</span></td>
		    <td width="25%" bgcolor="#FFFFFF"><b>'. $sellerrow[companyname] .'</b></td>
		  </tr>
		  <tr>
		    <td width="25%" class="Heading2" align="right">'. get_page_data3("4") .':</td>
		    <td width="25%" bgcolor="#FFFFFF">'. $row2[memid] .'</td>
		    <td width="25%" class="Heading2" align="right">'. get_page_data3("5") .':</td>
		    <td width="25%" bgcolor="#FFFFFF">'. $row2[to_memid] .'</td>
		  </tr>
		  <tr>
		    <td width="25%" class="Heading2" align="right" valign="top">'. get_word("80") .':</td>
		    <td width="75%" colspan="3" bgcolor="#FFFFFF">'. $row2[details] .'</td>
		  </tr>
		  <tr>
		    <td width="25%" align="right" class="Heading2">'. get_word("41") .':</td>
		    <td width="25%" bgcolor="#FFFFFF">'. $dis_date .'</td>
		    <td width="25%" align="right" class="Heading2">'. get_word("61") .':</td>
		    <td width="25%" bgcolor="#FFFFFF"><b>'. $sellerrow['currency'].''. number_format($row5[sell],2) .'</b></td>
		  </tr>
		  </table>
		<hr width="639" align="left" noshade color="#000000" size="1">
	';


    $text = get_html_template($_SESSION['Country']['countryID'],'Member',$text);
    define('CRLF', "\r\n", TRUE);

    if(strstr($buyerrow['email2'], ";")) {

    	$emailArray = explode(";", $buyerrow['email2']);
    	foreach($emailArray as $key => $value) {
			 $addressArray[] = array(trim($value), $buyerrow['contactname']);
 		}

 	} else {
		$addressArray[] = array(trim($buyerrow['email2']), $buyerrow['contactname']);
 	}
    if(strstr($sellerrow['email2'], ";")) {
      $emailArray = explode(";", $sellerrow['email2']);
      foreach($emailArray as $key => $value) {
        $addressArray[] = array(trim($value), $sellerrow['contactname']);
      }
    } else {
      $addressArray[] = array(trim($sellerrow['email2']), $sellerrow['contactname']);
    }
	  sendEmail("accounts@".$_SESSION['Country']['countrycode']."." . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), $subject, 'accounts@' . $_SESSION['Country']['countrycode'] . '.' . getWho($_SESSION['Country']['logo'], 2), getWho($_SESSION['Country']['logo'], 1) .' Accounts', $text, $addressArray);

  }

  header("Location: body.php?page=trans_receipt&authno=".$_REQUEST['authno']);

 }

  /**
   * Realestate Cash Fees Payment.
   */

 if($_REQUEST[feepayment2]) {

	 	$ebancAdmin = new ebancSuite();

	 	$otherMemberSQL = $ebancAdmin->dbRead("select * from members where memid = " . $_REQUEST['othermemid']);
	 	$otherMemberRow = mysql_fetch_assoc($otherMemberSQL);

	 	$feePay = new feePayment($_REQUEST['memberacc']);
		$feePay->payFees($_SESSION['feePayment']['memberRow'], $_REQUEST['amount'], 2, 6, '', $otherMemberRow, $_REQUEST['det']);

  if(checkmodule("Log")) {
   //add_kpi("28", $_REQUEST['memberacc'][0]);
  }

  Header("Location: body.php?page=feepayment3");

 }


 if($_REQUEST[rolloverpayment]) {

	 	$ebancAdmin = new ebancSuite();

	 	//$otherMemberSQL = $ebancAdmin->dbRead("select * from members where memid = " . $_REQUEST['othermemid']);
	 	//$otherMemberRow = mysql_fetch_assoc($otherMemberSQL);

	 	$feePay = new feePayment($_REQUEST['memberacc']);
		$feePay->payFees($_SESSION['feePayment']['memberRow'], $_REQUEST['amount'], 2, 6, '', $otherMemberRow, 'Real Estate Rollover Fee Payment', '', true);

  if(checkmodule("Log")) {
   //add_kpi("28", $_REQUEST['memberacc'][0]);
  }

  Header("Location: body.php?page=feepaymentrollover");

 }

 /**
  * Cash Fees Payment.
  */

 if($_REQUEST[feepayment1]) {

 	$ebancAdmin = new ebancSuite();

 	$feePay = new feePayment($_REQUEST['memberacc']);

 	if($_REQUEST['trade']) {

 		$feePay->payFees($_SESSION['feePayment']['memberRow'], $_REQUEST['amount'], 1, 7, true, $_REQUEST['det']);

 	} else {

 		$feePay->payFees($_SESSION['feePayment']['memberRow'], $_REQUEST['amount'], 1, 6, false, $_REQUEST['det']);

 	}



	if(checkmodule("Log")) {
		//add_kpi("27", $_REQUEST['membracc'][0]);
	}

	Header("Location: " . $_SERVER['HTTP_REFERER']);

 }


 /**
  * Directory Charge Version 1.
  */

	if($_REQUEST[directorycharge]) {

	    $genArray = $_REQUEST['amount'];
	    $memArray = $_REQUEST['memberacc'];
	    $detArray = $_REQUEST['det'];
	    $feeArray = $_REQUEST['d_fee'];
	    $tomArray = $_REQUEST['to_memid'];

	    $count = sizeof($genArray);
	    $i = 0;
	    for ($i = 0; $i <= $count; $i++) {


			if($genArray[$i]) {

				$authno=mt_rand(1000000,99999999);
				$t=mktime();
				$d=date("Y-m-d");
				#insert transaction

				if($_REQUEST['type'] == "1") {

					//add fees onto an account.

					//$memberSQL = dbRead("select members.* from members where memid = " . $memberacc[$i]);
					$memberSQL = dbRead("select members.* from members where memid = " . $memArray[$i]);
					$memberRow = mysql_fetch_assoc($memberSQL);

					$areaSQL = dbRead("select * from area where FieldID = " . $memberRow['licensee']);
					$areaRow = mysql_fetch_assoc($areaSQL);

					$chargePercent = $areaRow['feepercent'] / 2;

					$uq="insert into transactions values('".$memArray[$i]."','$t','".$_SESSION['Country'][reserveacc]."','0','0','0','".$genArray[$i]."','3','".addslashes(encode_text2($detArray[$i]))."','$authno','$d','','','0','','".$_SESSION['User']['FieldID']."')";
					$buyid = dbWrite($uq, "etradebanc", true);

					if($_REQUEST['deduct'] == "1")  {

						if($memberRow['over_payment']) {

							$newfee = $feeArray[$i]+$genArray[$i];

							/**

							if($memberRow['over_payment'] > $newfee) {

								dbWrite("update members set over_payment = (over_payment - " . $newfee . ") where memid = " . $memberRow['memid']);
								//insert into feespaid for the $newfee amount
								//dbWrite("insert into feespaid (memid,paymentdate,amountpaid,numfeesowed,deducted_fees,percent,area,type) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $newfee . "','" . $newfee . "','" . $newfee . "','" . $chargePercent . "','" . $areaRow['FieldID'] . "','6')");

							} else {

							**/

								// zero over_payment.
								// add onto fee_deductions amount - any over_payment
								//dbWrite("update members set over_payment = 0 where memid = " . $memberRow['memid']);
								dbWrite("update members set fee_deductions = (" . $newfee . " - " . $memberRow['over_payment'] . ") where memid = " . $memberRow['memid']);

								if($memberRow['over_payment'] > 0) {

									// insert into fees paid only if there was something in the over_payment. otherwise they never paid anything off.

									//dbWrite("insert into feespaid (memid,paymentdate,amountpaid,numfeesowed,deducted_fees,percent,area,type) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $memberRow['over_payment'] . "','" . $memberRow['over_payment'] . "','" . $memberRow['over_payment'] . "','" . $chargePercent . "','" . $areaRow['FieldID'] . "','6')");

								}

							/**

							}

							**/

						} else {

							$newfee = $feeArray[$i]+$genArray[$i];
							dbWrite("update members set fee_deductions = ".$newfee." where memid='".$memArray[$i]."'");

						}

					} else {

						//insert into fees incurred

						$oAreaSQL = dbRead("select area.* from area where FieldID = " . $_SESSION['Country']['DefaultArea']);
						$oAreaRow = mysql_fetch_assoc($oAreaSQL);

						$chargePercentTo = $oAreaRow['feepercent'] / 2;

						if($memberRow['over_payment'] > 0) {

							if($memberRow['over_payment'] > $genArray[$i]) {

								dbWrite("update members set over_payment = (over_payment - " . $genArray[$i] . ") where memid = " . $memberRow['memid']);
			// Should be adding inter fee to feespaid??
								dbWrite("insert into feespaid (memid, paymentdate, amountpaid, percent, area, type) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $genArray[$i] . "','" . $chargePercent . "','" . $_SESSION['Country']['DefaultArea'] . "','5')");
								$amountPaid = $genArray[$i];

							} else {

								dbWrite("update members set over_payment = 0 where memid = " . $memberRow['memid']);
								dbWrite("insert into feespaid (memid, paymentdate, amountpaid, percent, area, type) values ('" . $memberRow['memid'] . "','" . date("Y-m-d") . "','" . $memberRow['over_payment'] . "','" . $chargePercent . "','" . $_SESSION['Country']['DefaultArea'] . "','5')");
			// Should be adding inter fee to feespaid??
								$amountPaid = $memberRow['over_payment'];

							}

							$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,fee_paid,trans_id,percent,to_percent) values ('".$memArray[$i]."','".$areaRow['FieldID']."','".date("Y-m-d")."','".$memArray[$i]."','" . $_SESSION['Country']['DefaultArea'] . "','".$genArray[$i]."','" . $amountPaid . "','".$buyid."','" . $chargePercent . "','" . $chargePercentTo . "')";
							dbWrite($trans12);

						} else {

							$trans12 = "insert into feesincurred (memid,licensee,date,to_memid,to_licensee,fee_amount,trans_id,percent,to_percent) values ('".$memArray[$i]."','".$areaRow['FieldID']."','".date("Y-m-d")."','".$memArray[$i]."','" . $_SESSION['Country']['DefaultArea'] . "','".$genArray[$i]."','".$buyid."','" . $chargePercent . "','" . $chargePercentTo . "')";
							dbWrite($trans12);

						}

					}

				} elseif($_REQUEST['type'] == "4") {

					//un honour reversal

					//$uq="insert into transactions values('".$memArray[$i]."','$t','".$_SESSION['Country'][reserveacc]."','0','0','0','".$genArray[$i]."','10','".addslashes(encode_text2($detArray[$i]))."','$authno','$d','','0','','".$_SESSION['User']['FieldID']."')";

					//dbWrite("insert into memcards (memid,date,type,userid) values ('".$memArray[$i]."','$d','4','".$_SESSION['User']['FieldID']."')");

					//if($fees[$i]) {

						//dbWrite("update members set fee_deductions = fee_deductions + ".$fees[$i]." where memid='".$memArray[$i]."'");

					//}



				} elseif($_REQUEST['type'] == "5") {
					/*
					//trans fees from one account to another.

					$query3 = dbRead("select Sum(dollarfees) as FeeSum from transactions where memid = ".$memArray[$i]."");
					$row3 = mysql_fetch_assoc($query3);

					$uq="insert into transactions values('".$tomArray[$i]."','$t','".$_SESSION['Country'][reserveacc]."','0','0','0','".$row['FeeSum']."','3','".addslashes(encode_text2($detArray[$i]))."','$authno','$d','','0','','".$_SESSION['User']['FieldID']."')";
					$query4 = dbRead("select * from members where memid = '".$memArray[$i]."'");
					$row4 = mysql_fetch_assoc($query4);

					dbWrite("update members set fee_deductions = '".$row4['fee_deductions']."', over_payment = '".$row4['over_payment']."' where memid = '".$tomArray[$i]."'");

					if($row['FeeSum'] != 0) {

						$queryinc = dbRead("select * from feesincurred where memid=".$memArray[$i]." and fee_amount != fee_paid order by fieldid");

						while($row5 = mysql_fetch_assoc($queryinc)) {

							if(($row5['fee_amount']-$row5['fee_paid']) != 0) {

								dbWrite("update feesincurred set memid = '".$tomArray[$i]."' where fieldid = '".$row5['fieldid']."'");

							}

						}

						if($row['FeeSum'] < 0) {

							$amount = abs($row['FeeSum']);

						} elseif($row['FeeSum'] > 0) {

							$amount = -abs($row['FeeSum']);

						}

						$uq2="insert into transactions values('".$memArray[$i]."','$t','".$_SESSION['Country'][reserveacc]."','0','0','0','$amount','9','".addslashes(encode_text2($detArray[$i]))."','$authno','$d','','0','','".$_SESSION['User']['FieldID']."')";
						mysql_db_query($db, $uq2, $linkid);

					}
					*/

				 	$ebancAdmin = new ebancSuite();

				 	$feePay = new feePayment($memArray[$i]);

					$feePay->transferFees($memArray[$i], $tomArray[$i]);

				}

				if($_REQUEST['type'] != "1") {

					mysql_db_query($db, $uq, $linkid);

				}

			}

		}

		if ($_REQUEST[type] == 4) {

			Header("Location: ".$_SERVER['HTTP_REFERER']);

			if(checkmodule("Log")) {

				//add_kpi("32", $_REQUEST['memid']);

			}

		} else {

			if(checkmodule("Log")) {

				//add_kpi("31", $_REQUEST['memberacc'][0]);

			}

			Header("Location: ".$_SERVER['HTTP_REFERER']);

		}

	}

 /**
  * Fee Reversal.
  */

	if($_REQUEST[feereverse]) {

 		$amount = $_REQUEST['amount'];
 		$memberacc = $_REQUEST['memberacc'];

		if($amount) {

			$authno=mt_rand(1000000,99999999);
			$t=mktime();
			$d=date("Y-m-d");
			#insert transaction

			if ($_REQUEST['type'] == 2) {

				// real estate reversal

			 	$ebancAdmin = new ebancSuite();

			 	$feePay = new feePayment($memberacc);

				$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 2, $amount, '', $_REQUEST['det']);

				//$uq="insert into transactions values('$memberacc','$t','".$_SESSION['Country'][rereserve]."','0','0','0','-$amount','3','".addslashes(encode_text2($det))."','$authno','$d','','0','','".$_SESSION['User']['FieldID']."')";
				//mysql_db_query($db, $uq, $linkid);

			} else {

				// normal reversal.

			 	$ebancAdmin = new ebancSuite();

			 	$feePay = new feePayment($memberacc);

			 	if(!$_REQUEST['fees']) {

			 		//$cashFeesSQL = dbRead("select sum(dollarfees) as cashFees from transactions where memid = " . $memberacc);
			 		//$cashFeesRow = mysql_fetch_assoc($cashFeesSQL);

			 		$cashFeesSQL = dbRead("select sum(fee_amount-fee_paid) as cashFees from feesincurred where memid = " . $memberacc ." and fee_amount != 11 and percent != 50");
			 		$cashFeesRow = mysql_fetch_assoc($cashFeesSQL);

    			 	if($amount > $cashFeesRow['cashFees']) {

    			 		/**
    			 		 * Error
    			 		 */
    			 		Header("Location: body.php?page=cfo1&cfo=1&next=1&Action=true&memid=".$memberacc."&ChangeMargin=1&error=true&errorMsg=You CAN NOT reverse more Transaction Fees than is owing upto&errorAmount=".$cashFeesRow['cashFees']);
    			 		die;

    			 	} else {

    					$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $amount, '', $_REQUEST['det']);

    			 	}

			 	} elseif($_REQUEST['fees'] == 1) {

    			 	if($amount > $_SESSION['feePayment']['memberRow']['fee_deductions']) {

    			 		/**
    			 		 * Error
    			 		 */
    			 		Header("Location: body.php?page=cfo1&cfo=1&next=1&Action=true&memid=".$memberacc."&ChangeMargin=1&error=true&errorMsg=You CAN NOT reverse more Stationery Fees than is owning upto&errorAmount=".$_SESSION['feePayment']['memberRow']['fee_deductions']);
    			 		die;

    			 	} else {

    					$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $amount, '', $_REQUEST['det']);

    			 	}

			 	} elseif($_REQUEST['fees'] == 2) {

			 		//$cashFeesSQL = dbRead("select sum(fee_amount-fee_paid) as cashFees from feesincurred where memid = " . $memberacc ." and fee_amount = 11 and percent = 50");
			 		$cashFeesSQL = dbRead("select sum(fee_amount-fee_paid) as cashFees from feesincurred where memid = " . $memberacc ." and to_memid = ".$_SESSION['Country']['adminacc']." and percent = 50");
			 		$cashFeesRow = mysql_fetch_assoc($cashFeesSQL);

    			 	if($amount > $cashFeesRow['cashFees']) {

    			 		/**
    			 		 * Error
    			 		 */
    			 		Header("Location: body.php?page=cfo1&cfo=1&next=1&Action=true&memid=".$memberacc."&ChangeMargin=1&error=true&errorMsg=You CAN NOT reverse more Admin Fees than is owing upto&errorAmount=".$cashFeesRow['cashFees']);
    			 		die;

    			 	} else {

    					$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 1, $amount, '', $_REQUEST['det']);

    			 	}
				 }

				//$uq="insert into transactions values('$memberacc','$t','".$_SESSION['Country'][reserveacc]."','0','0','0','-$amount','9','".addslashes(encode_text2($det))."','$authno','$d','','0','','".$_SESSION['User']['FieldID']."')";
				//mysql_db_query($db, $uq, $linkid);

				//if($_REQUEST[fees])  {

					#check to see if there are any stationery fees owed
					//$dbcheckstationery=mysql_db_query($db, "select fee_deductions from members where memid='$memberacc'", $linkid);

					//list($fee_deductions)=mysql_fetch_row($dbcheckstationery);

					#if they do take the number off the feesowing table to

					//if($fee_deductions != "0") {

						//$final=$fee_deductions-$amount;

						//if($final <= 0) {

							//$final=0;

						//}

					//}

					//mysql_db_query($db, "update members set fee_deductions='$final' where memid='$memberacc'", $linkid);

				//}

			}

		}

		if($_REQUEST[type] == 2) {

			Header("Location: body.php?page=cfo2&ChangeMargin=".$_REQUEST['ChangeMargin']."&memid=".$memberacc."&done=1");

		} else {

			Header("Location: body.php?page=cfo1&ChangeMargin=".$_REQUEST['ChangeMargin']."&memid=".$memberacc."&done=1");

		}

	}

	/**
	 * Un Honour Fee Reversal.
	 */

	if($_REQUEST['unHonourReversal']) {

		/**
		 * Loop around the feespaid id's and see how many actually need to be reversed.
		 *
		 * If there is statinoery fees on any add that amount back onto the members table.
		 *
		 */

	 	$ebancAdmin = new ebancSuite();

	 	$feePay = new feePayment($_REQUEST['memberID']);

		$feePay->feeReversal($_SESSION['feePayment']['memberRow'], 3, '', $_REQUEST['feesPaidID'], $_REQUEST['transferDetails']);

		Header("Location: body.php?page=unhonoured");

	}

	/**
	 * Transaction Reversal.
	 */

	if($_REQUEST['transactionReversal']) {

		reverseTransaction($_REQUEST['transactionReversal']);
		header("Location: /body.php?page=member_edit&DisplayStatement=1&currentmonth=" . $_REQUEST['currentmonth'] . "&currentyear=" . $_REQUEST['currentyear'] . "&numbermonths=" . $_REQUEST['numbermonths'] . "&Client=" . $_REQUEST['Client'] . "&pageno=1&tab=tab7");

	}

	if($_REQUEST['transactionTrust']) {

  		$query = dbRead("select * from transactions where sell > 0 and authno = ".$_REQUEST['transactionTrust']." order by id desc limit 1");
  		$row = mysql_fetch_assoc($query);

		$dd = date("d F Y", mktime() + $_SESSION['Country']['timezone']);

		//header("Location: /body.php?page=TransferNew&Transfer=1&BuyerID=" . $row['memid'] . "&SellerID=99999999&TransDate=" . $dd . "&TransAmount=" . $row['sell'] . "&ConvertCurrency=AUD&FeesBuyer=0&FeesSeller=0&ChargeFeesBuyer=0&ChargeFeesSeller=0&TransDetails=" . addslashes(encode_text2($row['details'])) . " [".$row['id']."]");
		header("Location: /body.php?page=TransferNew&Transfer=1&BuyerID=" . $row['memid'] . "&SellerID=99999999&TransDate=" . $dd . "&TransAmount=" . $row['sell'] . "&ConvertCurrency=AUD&FeesBuyer=0&FeesSeller=0&ChargeFeesBuyer=0&ChargeFeesSeller=0&TransDetails=" . $row['details'] . " [".$row['id']."]");

	}


  ?>
