<?

	/**
	 * Base class to serve other classes in the system.
	 *
	 * @package E Banc Administration Site
	 * @author Antony Puckey
	 * @copyright Copyright 2005, RDI Host Pty Ltd
	 *
	 * Function definitions.
	 *
	 * authNo()			-	Generate and authorisation for transactions.
	 *
	 *
	 */

	/**
	 * Database definitions.
	 */

	DEFINE(DB_NAME, "etradebanc");
	DEFINE(DB_HOST, "rdihost.com");
	DEFINE(DB_USER, "empireDB");
	DEFINE(DB_PASS, "1emPire82");

	DEFINE(DB_TABLE_TRANSACTIONS, "transactions");
	DEFINE(DB_TABLE_FEESPAID, "transactions");
	DEFINE(DB_TABLE_CATEGORIES, "categories");
	DEFINE(DB_TABLE_COUNTRY, "country");
	DEFINE(DB_TABLE_AREA, "area");
	DEFINE(DB_TABLE_USERS, "tbl_admin_user");
	DEFINE(DB_TABLE_INVOICE, "invoice");
	DEFINE(DB_TABLE_MEMBERS, "members");
	DEFINE(DB_TABLE_FEESINCURRED, "feesincurred");
	DEFINE(DB_TABLE_FEESOWING, "feesowing");

	/**
	 * Other definitions.
	 */

	DEFINE(DEBUG, false);
	DEFINE(SITE_NAME, "EBANCADMIN");

	/**
	 * Start base class here.
	 */

	class ebancAdmin {

		var $authNo;
		var $dbLink;
		var $dbCount = 0;

		function ebancAdmin() {

			$this->dbLink = @mysql_connect(DB_HOST, DB_USER, DB_PASS);

		}

		/**
		 *  Generate authorisation number
		 */

		function authNo() {

			$Check = true;

			while($Check) {

				$this->authNo = (mktime()-951500000) - mt_rand(1000,9000);

				$authSQL = dbRead("select count(id) as Count from " . DB_TABLE_TRANSACTIONS . " where authno = '" . $this->authNo . "'");
				$authRow = (DEBUG) ? mysql_fetch_assoc($authSQL) : @mysql_fetch_assoc($authSQL);

				if($authRow['Count'] == 0) {

					$Check = false;

				}

			}

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

		function getmicrotime() {

		   	list($msec, $sec) = explode(" ",microtime());
		   	return ((float)$sec + (float)$msec);

		}


	}

?>
