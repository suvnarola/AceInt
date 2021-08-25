<?
        var $db_hostname = '104.192.31.75';
        var $db_username = 'empireDB';
        var $db_password = '1emPire82';
        var $db_database = 'etradebanc';
        var $db_table    = 'members';


        //function getSuggestions($prefix, &$arr)
        {
            $conn = @mysql_connect($this->db_hostname, $this->db_username, $this->db_password);
            if (!$conn)
                return;
            if (!@mysql_select_db($this->db_database, $conn)) {
                mysql_close($conn);
                return;
            }

            // firstly clean up the data
            $prefix = ltrim(preg_replace('/[^a-z0-9_. ]/', '', strtolower($prefix)));
            $prefix = preg_replace('/\s+/', ' ', $prefix);
            if (strlen($prefix) > 0) {
                $query = sprintf("select companyname, memid
                                    from %s
                                    where companyname like '%s%%'
                                    limit %d",
                                 $this->db_table,
                                 mysql_real_escape_string($prefix),
                                 $this->suggestion_limit);
                $result = mysql_query($query);
                while ($row = mysql_fetch_row($result)) {
                    if ($row[1] == 0)
                        $row[1] = '';
                    else
                        $row[1] = 'ID: ' . $row[1];
                    $arr[] = $row;
                }
            }
            mysql_close($conn);
        //}

        return $result;

?>
