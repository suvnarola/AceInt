<?
	//	connect to the database
	mysql_connect("localhost", "empireDB", "1emPire82") or die(mysql_error());
	mysql_select_db("etradebanc") or die(mysql_error());

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//	get our post variable, if its not set, exit
		if (isset($_POST["q"])) {
			$q = $_POST["q"];
		} else {
			exit;
		}

		$threshold = 1;	//	minimum length of the string

		//	make sure the length of the string isn't below the threshold
		if (strlen($q) >= $threshold) {
			$max = 5;	//	max number of results to show
			$found = 0;	//	placeholder so we know if we've found any matches or not

			//	shows results that start with the string first
			$sql = "SELECT * FROM members WHERE companyname LIKE '%".mysql_real_escape_string($q)."%' ORDER BY companyname ASC LIMIT ".$max;
			$query = mysql_query($sql) or die(mysql_error());
			$rows = mysql_num_rows($query);

			//	did we find any matches?
			if ($rows > 0) {
				$found = 1;	//	sure did!

				//	start the <ul> and loop through the matches
				echo "<ul>";
				while ($row = mysql_fetch_array($query)) {
					echo "<li title=\"".$row["companyname"]."\">".str_ireplace($q, "<span class=\"match\">".$q."</span>", $row["companyname"])."</li>";
				}
			}

			//	shows results that contains the string anywhere except the beginning if we haven't already found the max to display
			if ($rows < $max) {
				$sql = "SELECT * FROM members WHERE companyname LIKE '%".mysql_real_escape_string($q)."%' AND state NOT LIKE '".mysql_real_escape_string($q)."%' ORDER BY companyname ASC LIMIT ".($max - $rows);
				$query = mysql_query($sql) or die(mysql_error());
				$rows = mysql_num_rows($query);

				//	did we find any matches?
				if ($rows > 0) {
					if ($found == 0) {	//	have we already found matches before? if not, start the <ul>
						$found = 1;
						echo "<ul>";
					}

					//	loop through the matches
					while ($row = mysql_fetch_array($query)) {
						echo "<li title=\"".$row["companyname"]."\">".str_ireplace($q, "<span class=\"match\">".$q."</span>", $row["companyname"])."</li>";
					}
				}
			}

			//	if we found any matches, close our <ul>
			if ($found == 1) {
				echo "</ul>";
			}
		}
	}
?>