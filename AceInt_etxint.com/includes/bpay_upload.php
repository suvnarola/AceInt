<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */

 include("/home/etxint/admin.etxint.com/includes/modules/class.ebancSuite.php");
 include("/home/etxint/admin.etxint.com/includes/modules/class.feepayments.php");

 $ebancAdmin = new ebancSuite();

 $CONFIG['image_magick'] = "/virt/phpexec/";
 $CONFIG['dir_upload'] = "upload/bpay_files";
 $CONFIG['dir_root'] = "";
 $CONFIG['dir_path'] = "/home/etxint/public_html/";
 $CONFIG['dir_temp'] = $CONFIG['dir_path'] . "/" . $CONFIG['dir_upload'] . "/_temp";
 $CONFIG['dir_thumbnails'] = $CONFIG['dir_path'] . "/" . $CONFIG['dir_upload'] . "/_thumbnails";
 $CONFIG['MaxFileSize'] = 1500000;
 $CONFIG['upload_temp_dir'] = "/home/etxint/public_html/upload/temp";

 $_SESSION['FileUpload']['path'] = "upload/bpay_files/";

 ?><script language="javascript" src="includes/modules/file.js?cache=no"></script>

 <?
  if($_REQUEST['job'] == "Upload") {
	//$Errormsg = check_errors();
  	if(!$Errormsg) {
		$Errormsg = process_upload();
   		if(!$Errormsg) {
    		//add_kpi("63", "0");
    		//mail("publications@ebanctrade.com","Newsletter Management","A New Newsletter has been added by ".$_SESSION['User']['Name']." in CountryID: ".$_SESSION['User']['CID']);
			result_form();
   		}
  	} else {
  		upload_form($Errormsg['Messages'],$Errormsg['Highlight']);
  	}
  } elseif($_REQUEST['process']) {
		result_process();
  } else {
  	upload_form($Errormsg['Messages'],$Errormsg['Highlight']);
  }


  function result_process() {

    $dd = date("Y-m-d");
	$entry = "bpay_".$dd.".txt";
	$array1 = file("/home/etxint/public_html/upload/bpay_files/".$entry);

	$toid = explode("_", $entry);
	$tomemid = $toid[0];

	foreach($array1 as $key => $value) {

	    $col = explode(",", $value);
		$acc_no = substr($col[2], 2);
		$acc_no = substr($acc_no, 0, 5);
		$acc_no = ($acc_no < 10000) ? substr($acc_no, 1) : $acc_no;
		$am = $col[3]/100;

		$feePay = new feePayment($acc_no);
		$feePay->payFees($_SESSION['feePayment']['memberRow'], $am, 1, 5, '', '', "Cash Fees Payment - BPAY");
		//print $acc_no."-".$am."<br>";
	}

	$source="/home/etxint/public_html/upload/bpay_files/".$entry;
	$dest="/home/etxint/public_html/upload/bpay_files/processed_files/".$entry;
	copy($source, $dest);
	unlink("/home/etxint/public_html/upload/bpay_files/".$entry);

	print "Processing Complete";

  }


  //if($_REQUEST['job'] == "Upload" && !$Errormsg) {
  function result_form() {

	global $ebancAdmin;

	 $dd = date("Y-m-d");
	 $array1 = file("/home/etxint/public_html/upload/bpay_files/bpay_".$dd.".txt");

	 $foo = 0;
	 $total = 0;

	 ?>
	 <form method="POST" action="body.php?page=bpay_upload&process=1" name="process">
	 <table border="0" cellspacing="0" width="620" cellpadding="1">
	 <tr>
	 <td class="Border">
	  <table border="0" cellspacing="0" width="100%" cellpadding="3">
	   <tr>
	     <td width="100%" colspan="8" align="center" class="Heading">Transfers</td>
	   </tr>
	   <tr>
	     <td class="Heading2"><b>From Account:</b></td>
	     <td class="Heading2"><b>Auth Code:</b></td>
	     <td class="Heading2"><b>Fees Owing:</b></td>
	     <td align="right" class="Heading2"><b>Amount:</b></td>
	   </tr>
	   <?
		foreach($array1 as $key => $value) {

		  $col = explode(",", $value);
		  $acc_no = substr($col[2], 2);
		  $acc_no = substr($acc_no, 0, 5);
		  $acc_no = ($acc_no < 10000) ? substr($acc_no, 1) : $acc_no;
		  $am = $col[3]/100;

		  $dbtq = $ebancAdmin->dbRead("select memid, sum(dollarfees) as feesowe from transactions where memid = ".$acc_no." group by memid");
		  $row2 = mysql_fetch_assoc($dbtq);

		  $cfgbgcolorone = "#CCCCCC";
		  $cfgbgcolortwo = "#EEEEEE";
		  $bgcolor = $cfgbgcolorone;
		  $foo % 2  ? 0: $bgcolor=$cfgbgcolortwo;

    $dbgetdataout = dbRead("select * from members where memid = '$acc_no'");
	$rowR = mysql_fetch_assoc($dbgetdataout);

	if($rowR['letters'] == 1) {
	$bgcolor = "#33cc66";
	$foo % 2  ? 0: $bgcolor = "#009933";
	} elseif($rowR['letters'] == 2) {
	$bgcolor = "#0080ff";
	$foo % 2  ? 0: $bgcolor = "#0050ff";
	} elseif($rowR['letters'] == 3) {
	$bgcolor = "#cc00cc";
	$foo % 2  ? 0: $bgcolor = "#ee00ee";
	} elseif($rowR['letters'] == 9) {
	$bgcolor = "#FF4444";
	$foo % 2  ? 0: $bgcolor = "#FF6666";
	} elseif($rowR['letters'] == 4) {
	$bgcolor = "#fffc00";
	$foo % 2  ? 0: $bgcolor = "#f0d3oc";
	} else {
	$bgcolor = "#CCCCCC";
	$foo % 2  ? 0: $bgcolor = "#EEEEEE";
	}

		  ?>
		    <tr>
		      <td bgcolor="<?= $bgcolor ?>"><?= $acc_no ?></td>
		      <td bgcolor="<?= $bgcolor ?>"><?= $col[4] ?></td>
		      <td bgcolor="<?= $bgcolor ?>"><?= $row2['feesowe'] ?></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($am, 2) ?></td>
		    </tr>
		  <?

		  $foo++;
		  $total += $col[3];

		}

	$total = $total/100;
	 ?>
		 <tr>
		 	<tr>
		      <td bgcolor="<?= $bgcolor ?>"></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"></td>
		      <td align="right" bgcolor="<?= $bgcolor ?>"><?= number_format($total, 2) ?></td>
		    </tr>
		 </tr>
	    <tr>
	    <td colspan="4" align="right"><input type="submit" value="Process" name="prcesspayment"></td>
		</tr>
	     <tr>
	       <td bgcolor="#FFFFFF" colspan="8" align="center">Page Generation Time: <?
			    $time_end = getmicrotime();
			    $time = $time_end - $time_start;
				$time = number_format($time,2);
				echo $time;
			    ?> seconds</td>
	     </tr>
	    </table>
	   </td>
	  </tr>
	 </table>
	 </form>
	</body>
	</html>
<?
  }

 function upload_form($Errormsg = false, $ErrorArray = false) {

  print_error($Errormsg);

  ?>
 <form name="NewsLetterManagement" enctype="multipart/form-data" method="POST" onsubmit="return GetSelected()">
 <input type="hidden" name="SelectedFiles" value="">
  <table width="620" cellspacing="0" cellpadding="1" border="0">
   <tr>
    <td class="Border">
     <table width="100%" cellpadding="3" cellspacing="0" border="0">
      <tr>
       <td class="Heading2">Upload BPAY File.</td>
      </tr>
      <tr>
       <td bgcolor="#FFFFFF">
      <table border="0" style="border-collapse: collapse" bordercolor="#111111" cellspacing="1" id="table1" width="614">
        <tr>
          <td colspan="2"><b>File Upload ( .txt ONLY ) ( MAX 2MB )<br></b><input type="file" name="file" value="<?= $_FILES['file']['name'] ?>" size="20" style="width:498"></td>
        </tr>
        <tr>
          <td align="right">
          <input type="submit" value="Upload" name="job"></td>
        </tr>
      </table>
      	</td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
</form>
  <?

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

function check_errors() {

 $file = $_FILES['file'];
 $file['dest_ext'] = strtolower(substr(strrchr($file['name'], "."), 1));

 if($file['dest_ext'] != 'txt') {
  $Errormsg['Messages'] .= "The file type need to a txt file.<br>";
  $Errormsg['Highlight']['file'] = true;
 }

 $lines = file($file['tmp_name']);

 echo $hh;

 foreach ($lines as $line_num => $line) {
	//echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
	$split = explode(",", $line);

	foreach($split as $i => $data) {

		//echo "Line #<b>{$i}</b> : " . htmlspecialchars($data) . "<br />\n";
		if($i == 0) {
		  $query = dbRead("select * from members where memid = ".$data." and status != 1");
		  $row = @mysql_fetch_assoc($query);
		  if(!$row['memid']) {
		    $Errormsg['Messages'] .= "Invalid account number on line ".$line_num.".<br>";
		  }
		} elseif($i == 1) {

		} elseif($i == 2) {
		  if(!preg_match ("/^([0-9.]+)$/", $data))  {
		    $Errormsg['Messages'] .= "Invalid amount on line ".$line_num.".<br>";
		  }
		} elseif($i == 3) {

		}
	}

 }

 if(!$_FILES['file']) {
  $Errormsg['Messages'] .= "You Must Select a File to upload.<br>";
  $Errormsg['Highlight']['file'] = true;
 }

 return $Errormsg;

}

 function process_upload() {

	global $max_file_size, $upload_temp_dir, $CONFIG;

	$dd = date("Y-m-d");
	$file = $_FILES['file'];
	$file['org_name'] = $file['name'];
	$file['dest_ext'] = strtolower(substr(strrchr($file['name'], "."), 1));
	$file['name'] = "bpay_".$dd."." . $file['dest_ext'];
	$file['dest_path'] = $CONFIG['dir_path'] . $_SESSION['FileUpload']['path'];
	$file['dest_name'] = $file['dest_path'] . $file['name'];

	if($file['size'] > $max_file_size) {
		$errormsg[] = "The uploaded file size has exceeded the 1.5MB limit.";
		$haltUpload = true;
	}

	if (move_uploaded_file($file['tmp_name'], $file['dest_name'])) {
		$ParentID = process_file($file);

		if(!$ParentID) { return; }

	}

 }

 function process_file($file,$parentid = false) {

	global $CONFIG,$image_magic_filetypes;

	$path = pathinfo($file["dest_name"]);

	$file[dest_path] = str_replace($CONFIG[dir_path],"",$file[dest_path]);
	$isDeleteProtect = ($_REQUEST['file_delete']) ? 1 : 0;
	$name = (trim($_REQUEST[file_name])) ? trim($_REQUEST[file_name]) : $file["org_name"];

	$md5 = process_md5($file["dest_name"]);

    return 1;
 }

  function process_md5($file) {
    if(!file_exists($file)) {
        return false;
    }
    else {
        $filecontent = implode("", file($file));
        return md5($filecontent);
    }
 }
?>