<?

	/**
	 * Base class to serve other classes in the system.
	 *
	 * @package E Banc Administration Site
	 * @author Antony Puckey
	 * @copyright Copyright 2005, RDI Host Pty Ltd.
	 *
	 * Function definitions.
	 *
	 * authNo()											-	Generate and authorisation for transactions.
	 * sendToBrowser($data,$mimeType,$fileName,$type)	-	Send a file of particular type to the browser.
	 * getFileSize($file_size)							-	Return a human readable representation of a number.
	 * getPageData()
	 * getWord()
	 * displayTabs
	 *
	 *
	 */

	/**
	 * Database definitions.
	 */

	DEFINE(DB_NAME, "etxint_etradebanc");
	DEFINE(DB_HOST, "localhost");
	DEFINE(DB_USER, "etxint_admin");
	DEFINE(DB_PASS, "Ohc6icho6eimaid3");

	DEFINE(DB_TABLE_TRANSACTIONS, "transactions");
	DEFINE(DB_TABLE_FEESPAID, "feespaid");
	DEFINE(DB_TABLE_CATEGORIES, "categories");
	DEFINE(DB_TABLE_COUNTRY, "country");
	DEFINE(DB_TABLE_AREA, "area");
	DEFINE(DB_TABLE_CREDIT, "credit_transactions");
	DEFINE(DB_TABLE_USERS, "tbl_admin_user");
	DEFINE(DB_TABLE_ADMINDATA, "tbl_admin_data");
	DEFINE(DB_TABLE_ADMINPAGES, "tbl_admin_pages");
	DEFINE(DB_TABLE_INVOICE, "invoice");
	DEFINE(DB_TABLE_MEMBERS, "members");
	DEFINE(DB_TABLE_FEESINCURRED, "feesincurred");
	DEFINE(DB_TABLE_FEESOWING, "feesowing");
	DEFINE(DB_TABLE_KEYWORDS, "tbl_lang_keywords");
	DEFINE(DB_TABLE_COUNTRYPREF, "countrypref_members");

	/**
	 * Other definitions.
	 */

	DEFINE(DEBUG, false);

	/**
	 * Start base class here.
	 */

	class ebancSuite {

		var $authNo;
		var $dbLink;
		var $dbCount = 0;
		var $pageData = Array();
		var $transactionDate;

		function ebancSuite($siteName = "EBANCADMIN") {

			DEFINE(SITE_NAME, $siteName);

			$this->dbLink = @mysql_connect(DB_HOST, DB_USER, DB_PASS);

			$this->doStartup();

		}

		function doStartup() {

			if(!$_SESSION['Country']) {

				$countryQuery = $this->dbRead("select * from " . DB_TABLE_COUNTRY . " where countryID = '" . $_SESSION['User']['CID'] . "'", DB_NAME);

				$_SESSION['Country'] = @mysql_fetch_assoc($countryQuery);

				$this->wordData = $_SESSION['WordData'];

			} else {

				$this->wordData = $_SESSION['WordData'];

			}

			if(!$_SESSION['WordData']) {

				$langCode = ($_SESSION['User']['lang_code']) ? $_SESSION['User']['lang_code'] : "en";

				$wordQuery = $this->dbRead("select wordid, word from " . DB_TABLE_KEYWORDS . " where langcode = '" . $langCode . "' order by wordid", DB_NAME);

				while($wordRow = mysql_fetch_assoc($wordQuery)) {

					$_SESSION['WordData'][$wordRow[wordid]] = $wordRow['word'];

				}

				$this->wordData = $_SESSION['WordData'];

			} else {

				$this->wordData = $_SESSION['WordData'];

			}

			if(!$_SESSION['CountryPref_Members']) {

			  $countryPrefQuery = $this->dbRead("select * from " . DB_TABLE_COUNTRYPREF . " where CID = '" . $_SESSION['User']['CID'] . "'", DB_NAME);

			  $_SESSION['CountryPref_Members'] = @mysql_fetch_assoc($countryPrefQuery);

			}

			$ReqPage = ($_REQUEST['page']) ? $_REQUEST['page'] : "mem_search";

			if($_REQUEST['page'] == "TransferNew") $ReqPage = "transfer";

			if($_REQUEST['page'] == "nav2") {

				$userSQL = $this->dbRead("select tbl_admin_users.* from " . DB_TABLE_USERS . " where FieldID = ".$_REQUEST['UserID']." and md5Password = '".$_REQUEST['md5']."'", DB_NAME);
				$userRow = mysql_fetch_assoc($userSQL);

				$pageDataQuery = $this->dbRead("select position, data from " . DB_TABLE_ADMINDATA . ", " . DB_TABLE_ADMINPAGES . " where (" . DB_TABLE_ADMINDATA . ".pageid = " . DB_TABLE_ADMINPAGES . ".pageid) and langcode='".$UserRow['lang_code']."' and page = '".$ReqPage."' order by position", DB_NAME);

			} else {

				$pageDataQuery = $this->dbRead("select position, data from " . DB_TABLE_ADMINDATA . ", " . DB_TABLE_ADMINPAGES . " where (" . DB_TABLE_ADMINDATA . ".pageid = " . DB_TABLE_ADMINPAGES . ".pageid) and langcode='".$_SESSION['User']['lang_code']."' and page = '".$ReqPage."' order by position", DB_NAME);

			}

			while($row = mysql_fetch_assoc($pageDataQuery)) {

				$this->pageData[$row[position]] = $row[data];

			}

		}

		function authNo() {

			$Check = true;

			while($Check) {

				$this->authNo = (mktime()-951500000) - mt_rand(1000,9000);

				$authSQL = $this->dbRead("select count(id) as Count from " . DB_TABLE_TRANSACTIONS . " where authno = '" . $this->authNo . "'", DB_NAME);
				$authRow = (DEBUG) ? mysql_fetch_assoc($authSQL) : @mysql_fetch_assoc($authSQL);

				if($authRow['Count'] == 0) {

					$Check = false;

				}

			}

			return;

		}

		function dbtList($table, $database = false) {

			if($database == false) {

				$database = DB_NAME;

			}

			if ($this->dbLink == False) {

				$this->dbReportError(mysql_errno(),mysql_error(),$table);

			}

			$rsid = mysql_list_fields($database, $table, $this->dbLink);
			if ($rsid == False) {

				$this->dbReportError(mysql_errno(),mysql_error(),$table);

			}

			$columns = mysql_num_fields($rsid);

		    for ($i = 0; $i < $columns; $i++) {

		    	$returnArray[] = mysql_field_name($rsid, $i);

			}

			$this->dbCount++;

			return($returnArray);

		}

		function dbRead($SQLQuery,$database = false) {

			if($database == false) {

				$database = DB_NAME;

			}

			if ($this->dbLink == False) {

				$this->dbReportError(mysql_errno(),mysql_error(),$SQLQuery);

			}

			mysql_select_db($database);

			$rsid = mysql_query($SQLQuery, $this->dbLink);
			if ($rsid == False) {

				$this->dbReportError(mysql_errno(),mysql_error(),$SQLQuery);

			}

			$this->dbCount++;

			return($rsid);

		}

		function dbWrite($SQLQuery,$database = false,$DBReturnID = False) {

			if($database == false) { $database = DB_NAME; }

			if ($this->dbLink == False) { $this->dbReportError(mysql_errno(),mysql_error(),$SQLQuery); }

			mysql_select_db($database);

			$rsid = mysql_query($SQLQuery, $this->dbLink);
			if ($rsid == False) { $this->dbReportError(mysql_errno(),mysql_error(),$SQLQuery); }

			if ($DBReturnID == True) {

				$DBReturnID = mysql_insert_id($this->dbLink);

			} else {

				$DBReturnID = True;

			}

			$this->dbCount++;

			return($DBReturnID);
		}

		function dbReportError($ErrorNumber,$ErrorMsg,$SQLQuery) {

			print "An error occured while connecting to the database<br>";
			print "<strong>$ErrorNumber</strong>";
			print $ErrorMsg;
			exit;

		}

		function dbRecordTotal($rs) {

			return mysql_num_rows($rs);

		}

		function dbRecordAffected($rs) {

			return mysql_affected_rows($this->dbLink);

		}

		function dbFetchArray($rs) {

			return mysql_fetch_assoc($rs);

		}

		function dbString($string) {

			$string = addslashes(trim($string));
			return $string;

		}

		function getMicrotime() {

		   	list($msec, $sec) = explode(" ",microtime());
		   	return ((float)$sec + (float)$msec);

		}

		function displayTabs($tabArray, $currentTab, $currentPage, $currentPageno) {

			$count = sizeof($tabArray);

		  	?>
		    	<table border="0" cellpadding="0" cellspacing="0" width="620">
		    		<tr>
		      			<td><img border="0" src="images/layout_arrow_right.gif" width="6" height="11">&nbsp;</td>
		      			<td width="100%">

		      				<?

		       					$foo = 1;

		       					foreach($tabarray as $tabkey => $tabvalue) {

		        					if($_REQUEST[tab] == "tab".$foo) {

		         						?>

		         							&nbsp;<a class="nav" href="body.php?page=<?= $_REQUEST['page']?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab<?= $foo ?>"><b><?= $tabvalue ?>&nbsp;</b></a><? if($count != $foo) { ?> |<? } ?>

		         						<?

		        					} else {

		         						?>

		         							&nbsp;<a class="nav" href="body.php?page=<?= $_REQUEST['page']?>&Client=<?= $_REQUEST['Client'] ?>&pageno=<?= $_REQUEST['pageno'] ?>&tab=tab<?= $foo ?>"><?= $tabvalue ?>&nbsp;</a><? if($count != $foo) { ?> |<? } ?>
		         						<?

		        					}

			    					$foo++;

			   					}

		   	  				?>

		      			</td>
		    		</tr>
		   			<tr>
		      			<td width="100%" colspan="2"><img border="0" src="images/layout_line.gif" width="100%" height="13"><br>&nbsp;</td>
		    		</tr>
		 		</table>

		  	<?

		 	return;

		 }

		function getWord($id)  {

		  return $this->wordData[$id];

		}

		function getPageData($id)  {

  			return $this->pageData[$id];

		}

		function getFileSize($fileSize) {

			if(!$fileSize) { $fileSize = 0; }

			if ($fileSize >= 1099511627776) {

				$fileSize = round($fileSize/1099511627776, 2). "TB";

			} elseif ($fileSize >= 1073741824) {

				$fileSize = round($fileSize/1073741824, 2). "GB";

			} elseif ($fileSize >= 1048576) {

				$fileSize = round($fileSize/1048576, 2). "MB";

			} elseif ($fileSize >= 1024) {

				$fileSize = round($fileSize/1024, 2). "KB";

			} else {

				$fileSize = $fileSize. " bytes";

			}

			return $fileSize;

		}

		function sendToBrowser($data,$mimeType,$fileName,$type) {

			$now = gmdate("D, d M Y H:i:s") ." GMT";

			header("Content-type: " . $mimeType);
			header("Content-Length: " . strlen($data));
			header("Expires: ". $now);
			header("Content-Disposition: " . $type . "; filename=" . $fileName);
			header("Cache-Control: must-revalidate. post-check=0, pre-check=0");
			header("Pragma: public");

			print $data;
			exit;

		}

		function getYearArray() {

			$startYear = 2000;

			while(date("Y") >= $startYear) {

				$yearArray[$startYear] = $startYear;

				$startYear++;

			}

			return $yearArray;

		}

		function getMonthArray() {

			$numMonths = $this->periodDiff(strtotime("1st Feb 2000"),mktime())+1;

			$foo = 1;

			while($foo != $numMonths) {

				$monthArray[$foo] = $foo;

				$foo++;

			}

			return $monthArray;

		}

		function periodDiff($in_dateLow, $in_dateHigh) {

			// swap dates if they are backwards

			if ($in_dateLow > $in_dateHigh) {

				$tmp = $in_dateLow;
				$in_dateLow = $in_dateHigh;
				$in_dateHigh = $tmp;

			}

			$dateLow = $in_dateLow;
			$dateHigh = strftime('%m/%Y', $in_dateHigh);

			$periodDiff = 0;

			while (strftime('%m/%Y', $dateLow) != $dateHigh) {

				$periodDiff++;
				$dateLow = strtotime('+1 month', $dateLow);

			}

			return $periodDiff;

		}

		function addTransaction($fromMemid,$fromFeeAmount,$toMemid,$toFeeAmount,$date,$transactionAmount,$details,$transType,$clearFunds) {

			$this->transactionDate = strtotime($date);
			$this->authNo();
			$clearCheque = ($clearFunds) ? "0" : "1";
			$this->transactionDetails = $this->encodeText($details);

			$this->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('" . $fromMemid . "','" . $this->transactionDate . "','" . $toMemid . "','" . $transactionAmount . "','0','" . $fromFeeAmount . "','1','" . $this->transactionDetails . "','" . $this->authNo . "','" . date("Y-m-d", $this->transactionDate) . "','0','180')", DB_NAME);
			$this->dbWrite("insert into " . DB_TABLE_TRANSACTIONS . " (memid,date,to_memid,buy,sell,dollarfees,type,details,authno,dis_date,checked,userid) values ('" . $toMemid . "','" . $this->transactionDate . "','" . $fromMemid . "','0','" . $transactionAmount . "','" . $toFeeAmount . "','2','" . $this->transactionDetails . "','" . $this->authNo . "','" . date("Y-m-d", $this->transactionDate) . "','" . $clearCheque . "','180')", DB_NAME);

			return;

		}

		function encodeText($str) {

			return $this->unicodeToUtf8($this->utf8Tounicode($str));

		}

		function decodeText($str) {

			return $this->unicodeToUtf8($this->entitiesToUnicode($str));

		}

		function unicodeToUtf8($str) {

		    $utf8 = '';

		    foreach($str as $unicode) {

		        if ($unicode < 128) {

		            $utf8.= chr($unicode);

		        } elseif ($unicode < 2048) {

		            $utf8.= chr(192 +  (($unicode - ($unicode % 64)) / 64));
		            $utf8.= chr(128 + ($unicode % 64));

		        } else {

		            $utf8.= chr(224 + (($unicode - ($unicode % 4096)) / 4096));
		            $utf8.= chr(128 + ((($unicode % 4096) - ($unicode % 64)) / 64));
		            $utf8.= chr(128 + ($unicode % 64));

		        }

		    }

		    return $utf8;

		}

		function utf8Tounicode($str) {

		    $unicode = array();
		    $values = array();
		    $lookingFor = 1;

		    for ($i = 0; $i < strlen($str); $i++ ) {

		        $thisValue = ord($str[$i]);

		        if ($thisValue < 128) {

		        	$unicode[] = $thisValue;

		        } else {

		            if (count($values) == 0) $lookingFor = ($thisValue < 224) ? 2 : 3;

		            $values[] = $thisValue;

		            if (count($values) == $lookingFor) {

		                $number = ($lookingFor == 3) ?
		                    (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64):
		                	(($values[0] % 32) * 64) + ($values[1] % 64);
		                $unicode[] = $number;
		                $values = array();
		                $lookingFor = 1;

		            }

		        }

		    }

		    return $unicode;

		}

		function entitiesToUnicode($str) {

		    $unicode = array();
		    $inEntity = FALSE;
		    $entity = '';

		    for ($i = 0; $i < strlen($str); $i++) {

		        if ($inEntity) {

		            if ($str[$i] == ';') {

		                $unicode[] = (int)$entity;
		                $entity = '';
		                $inEntity = FALSE;

		            } elseif ($str[$i] != '#') {

		                $entity .= $str[$i];

		            }

		        } else {

		            if (($str[$i] == '&')&&($str[$i + 1] == '#')) {

		            	$inEntity = TRUE;

		            } else {

		            	$unicode[] = ord($str[$i]);

		            }

		        }
		    }

		   return $unicode;

		}

		 function getSessionSize() {

			$Size += @filesize("/tmp/sess_" . $PHPSESSID);

			return $this->getFileSize($Size);

		}

		function permError($errNo) {

			?>

				<table width="601" border="0" cellpadding="1" cellspacing="0">
					<tr>
						<td class="Border">
							<table width="100%" border="0" cellpadding="3" cellspacing="0">
								<tr>
									<td width="100%" align="center" class="Heading2"><?= $this->getWord($errNo) ?>.</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

			<?

			return;

		}

		function formField($fieldType, $fieldLabel, $fieldName, $fieldValue, $fieldSize, $errorMsg = false, $requiredField = false, $dataFormat = false, $onClick = false, $readOnly = false, $numberOnly = false) {

			/**
			 * fieldType:
			 *
			 * 	1: Input Text
			 * 	2: TextArea
			 *  8: Custom Data
			 *  9: No Field. Just Text.
			 *
			 */

			$fieldSizeArray = explode(",", $fieldSize);

			if($numberOnly == 1) {
				$check = "onKeyPress='return number2(event)'";
			} elseif($numberOnly == 2) {
				$check = "onKeyPress='return letternumber(event)'";
			} else {
				$check = "";
			}

			switch($fieldType) {

				case "1":

					$onClick = ($onClick) ? $onClick : "";
					$readOnly = ($readOnly) ? "readonly" : "";
					$dataCorner = "Straight";
					$dataDisplay = "<input ".$readOnly." " . $onClick . " class=\"inputBoxes\" type=\"text\" name=\"" . $fieldName . "\" value=\"" . $fieldValue . "\" size=\"" . $fieldSizeArray[0] . "\" " . $check .">";
					$dataColour = ($errorMsg) ? "red" : "blue";
					break;

				case "2":

					$dataCorner = "";
					$dataDisplay = "<textarea class=\"inputBoxes\" cols=\"" . $fieldSizeArray[0] . "\" rows=\"" . $fieldSizeArray[1] . "\" name=\"" . $fieldName . "\">" . $fieldValue . "</textarea>";
					$dataColour = ($errorMsg) ? "red" : "blue";
					break;

				case "8":

					$dataCorner = "Straight";
					$dataDisplay = $fieldValue;
					$dataColour = ($errorMsg) ? "red" : "blue";
					$dataCustomPadding = "customPadding";
					break;

				case "9":

					if($dataFormat) {

						/**
						 * Data Display Format for non textbox style.
						 *
						 * 1: format as currency.
						 *
						 */

						switch($dataFormat) {

							case "1":

								$dataDisplay = $_SESSION['Country']['currency'] . number_format($fieldValue, 2);
								break;

						}

					} else {

						$dataDisplay = $fieldValue;

					}

					$dataCorner = "Straight";
					$dataColour = "blue";
					$dataText = "Text";
					$dataClass = "greyBackground";
					break;

			}

			?>

				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="leftSide">
							<table class="formContainer alignRight" cellpadding="0" cellspacing="0">
								<tr>
									<td class="cornerTopLeft <?= $dataColour ?>Background"></td>
									<td class="middleTop <?= $dataColour ?>Background"></td>
								</tr>
								<tr>
									<td class="middleLeft <?= $dataColour ?>Background"></td>
									<td class="mainContentLeft <?= $dataColour ?>Background"><?= $requiredField ?> <?= $fieldLabel ?>:</td>
								</tr>
								<tr>
									<td class="cornerBottomLeft <?= $dataColour ?>Background"></td>
									<td class="middleBottom <?= $dataColour ?>Background"></td>
								</tr>
							</table>
						</td>
						<td class="rightSide">
							<table class="formContainer" cellpadding="0" cellspacing="0">
								<tr>
									<td class="cornerTopLeftStraight <?= $dataClass ?>"></td>
									<td class="middleTop <?= $dataClass ?>"></td>
									<td class="cornerTopRight <?= $dataClass ?>"></td>
								</tr>
								<tr>
									<td class="middleLeft <?= $dataClass ?>"></td>
									<td class="mainContentRight<?= $dataText ?> <?= $dataClass ?> <?= $dataCustomPadding ?>"><?= $dataDisplay ?></td>
									<td class="middleRight <?= $dataClass ?>"></td>
								</tr>
								<tr>
									<td class="cornerBottomLeft<?= $dataCorner ?> <?= $dataClass ?>"></td>
									<td class="middleBottom <?= $dataClass ?>"></td>
									<td class="cornerBottomRight <?= $dataClass ?>"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="/images/spacer.gif" height="2" width="1"></td>
					</tr>
				</table>

			<?

			return;

		}

function form_select($name,$query,$value,$key,$compare = false,$allowall = false,$custom = false,$size = 1) {

 $sql_query = $query;

 if($allowall) {

  $output .= "<option value=\"\">$allowall</option>\n";

 }

 if(is_array($query)) {

  foreach($query as $key2 => $value2) {

   if(strtolower($value2) == strtolower($compare)) {

    $output .= "<option selected value=\"$key2\">$value2</option>\n";

   } else {

    $output .= "<option value=\"$key2\">$value2</option>\n";

   }

  }

 } else {

  while($row = mysql_fetch_assoc($sql_query)) {

   if(strtolower($row[$key]) == strtolower($compare)) {

    $output .= "<option selected value=\"$row[$key]\">$row[$value]</option>\n";

   } else {

    $output .= "<option value=\"$row[$key]\">$row[$value]</option>\n";

   }

  }

$dataDisplay = "<select size=\"$size\" name=\"$name\"$custom>\n$output</select>";

?>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="leftSide">
			<table class="formContainer alignRight" cellpadding="0" cellspacing="0">
				<tr>
					<td class="cornerTopLeft blueBackground"></td>
					<td class="middleTop blueBackground"></td>
				</tr>
				<tr>
					<td class="middleLeftblueBackground"></td>
					<td class="mainContentLeft blueBackground"><?= $requiredField ?> Currency:</td>
				</tr>
				<tr>
					<td class="cornerBottomLeft blueBackground"></td>
					<td class="middleBottom blueBackground"></td>
				</tr>
			</table>
		</td>
		<td class="rightSide">
			<table class="formContainer" cellpadding="0" cellspacing="0">
				<tr>
					<td class="cornerTopLeftStraight <?= $dataClass ?>"></td>
					<td class="middleTop <?= $dataClass ?>"></td>
					<td class="cornerTopRight <?= $dataClass ?>"></td>
				</tr>
				<tr>
					<td class="middleLeft <?= $dataClass ?>"></td>
					<td class="mainContentRight<?= $dataText ?> <?= $dataClass ?> <?= $dataCustomPadding ?>"><?= $dataDisplay ?></td>
					<td class="middleRight <?= $dataClass ?>"></td>
				</tr>
				<tr>
					<td class="cornerBottomLeftStraight <?= $dataClass ?>"></td>
					<td class="middleBottom <?= $dataClass ?>"></td>
					<td class="cornerBottomRight <?= $dataClass ?>"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="/images/spacer.gif" height="2" width="1"></td>
	</tr>
</table>

<?



 }

 return;

}

		function formSubmit($buttonName, $formName) {

			?>

				<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="leftSide">
							&nbsp;
						</td>
						<td class="rightSide">
							<a onclick="javascript:document.<?= $formName ?>.submit()">
								<div class="cursorHand">
									<table class="formContainer" cellpadding="0" cellspacing="0">
										<tr>
											<td class="cornerTopLeft greenBackground"></td>
											<td class="middleTop greenBackground"></td>
											<td class="cornerTopRight greenBackground"></td>
										</tr>
										<tr>
											<td class="middleLeft greenBackground"></td>
											<td class="mainContentRight greenBackground submitButton"><?= strtoupper($buttonName) ?> >></td>
											<td class="middleRight greenBackground"></td>
										</tr>
										<tr>
											<td class="cornerBottomLeft greenBackground"></td>
											<td class="middleBottom greenBackground"></td>
											<td class="cornerBottomRight greenBackground"></td>
										</tr>
									</table>
								</div>
							</a>
						</td>
					</tr>
				</table>

			<?

		}

		function formSelect($name,$query,$value,$key,$compare = false,$allowall = false,$custom = false,$size = 1,$bracket = false) {

			$sql_query = $query;

			if($allowall) {

				$output .= "<option value=\"\">$allowall</option>\n";

			}

			if(is_array($query)) {

				foreach($query as $key2 => $value2) {

					$ibracket = ($bracket) ? " ($key2)" : "";

					if(strtolower($key2) == strtolower($compare)) {

						$output .= "<option selected value=\"$key2\">$value2$ibracket</option>\n";

					} else {

						$output .= "<option value=\"$key2\">$value2$ibracket</option>\n";

					}

				}

			} else {

				while($row = mysql_fetch_assoc($sql_query)) {

					$ibracket = ($bracket) ? " ($row[$key])" : "";

					if(strtolower($row[$key]) == strtolower($compare)) {

						$output .= "<option selected value=\"$row[$key]\">$row[$value]$ibracket</option>\n";

					} else {

						$output .= "<option value=\"$row[$key]\">$row[$value]$ibracket</option>\n";

					}

				}

			}

			return "<select size=\"$size\" name=\"$name\"$custom>\n$output</select>";

		}


	}

?>
