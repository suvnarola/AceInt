<?

 /**
  * Database Class
  *
  * db.php
  * version 0.02
  * 
  * Added `` to table names.
  *
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  */

 class dbCreateSQL {

  /*
	add_item(name,value); OR add_item(array);
	delete_item(name);
	
	add_where(value); OR add_where(array);
	add_table(name); OR add_table(array);
	delete_table(name);
	set_orderby(name);
	set_groupby(name);
	set_limit(name);
	
	// OUTPUT
	get_sql_insert(); returns SQL for INSERT
	get_sql_update(); returns SQL for UPDATE
	get_sql_delete(); returns SQL for DELETE

  */

	function dbCreateSQL() {
		$this->values = Array();
		$this->tables = Array();
		$this->orderby = "";
		$this->limit = "";
		$this->error = "";
		$this->where = Array();
		$this->groupby = "";
		$this->errors = "";
	
	}
	
	function add_item($key,$value = false) {
		if(!$key) return;
		if(is_array($key)) {
			foreach($key as $ikey => $ivalue) {
				$this->values[trim($ikey)] = trim($ivalue);
			}		
		} else {
			$this->values[trim($key)] = trim($value);	
		}
	}
	
	function delete_item($key) {
		if(!$key) return;
		$array = Array();
		foreach($this->values as $i => $value) {
			if($i != trim($key)) {
				$array[$i] = $value;
			}
		}
		$this->values = $array;
	}
	
	function add_where($value) {
		if(!$value) return;
		
		if(is_array($value)) {
			foreach($value as $ivalue) {
				$this->where[] = trim($ivalue);
			}
		} else {
			$this->where[] = trim($value);
		}
	}
	
	function add_table($value) {
		if(is_array($value)) {
			foreach($value as $ivalue) {
				$this->tables[] = trim($ivalue);
			}
		} else {
			$this->tables[] = trim($value);
		}
	}
	
	function delete_table($delete) {
		$array = Array();
		foreach($this->tables as $value) {
			if($value != trim($delete)) {
				$array[] = $value;
			}
		}
		$this->tables = $array;
	}
	
	function set_orderby($orderby) {
		$this->orderby = trim($orderby);	
	}
	
	function set_groupby($orderby) {
		$this->groupby = trim($orderby);	
	}
	
	function set_limit($limit) {
		$this->limit = trim($limit);	
	}
	
	function sql_tables() {
		if(count($this->tables) < 1) {
			$this->error[] = "No table names specified";
			return;
		}
		
		foreach($this->tables as $value) {
			$tables .= " $value,";		
		}
		
		return substr($tables, 0, strlen($tables)-1);
	}
	
	function sql_where() {
		if(count($this->where) < 1) {
			return;
		}
		
		foreach($this->where as $value) {
			$where .= " ($value) AND";
		}
		
		return " WHERE" . substr($where, 0, strlen($where)-4);
	}
	
	function sql_groupby() {
		if(!$this->groupby) {
			return;
		}
		
		return " GROUP BY " . $this->groupby;
	}
	
	function sql_orderby() {
		if(!$this->orderby) {
			return;
		}
		
		return " ORDER BY " . $this->groupby;
	}
	
	function sql_limit() {
		if(!$this->limit) {
			return;
		}
		
		return " LIMIT " . $this->limit;
	}
	
	function sql_values_insert() {
		if(count($this->values) < 1) {
			return;
		}
		foreach($this->values as $key => $value) {
			$sql_name .= "`".$key . "`,";
			$sql_value .= "'".addslashes($value) . "',";
		}
		
		$sql_name = substr($sql_name, 0, strlen($sql_name)-1);
		$sql_value = substr($sql_value, 0, strlen($sql_value)-1);
		
		return " ($sql_name) VALUES ($sql_value)";	
	}
	
	
	function sql_values_update() {
		if(count($this->values) < 1) {
			return;
		}
		foreach($this->values as $key => $value) {
			if($value == "NULL") {
			 $sql .= " `" . $key . "` = NULL,";
			} else {
			 $sql .= " `" . $key . "` = '".addslashes($value)."',";
			}
		}
		
	
		
		$sql = substr($sql, 0, strlen($sql)-1);
		
		return " SET $sql";	
	}
	
	function get_sql_insert() {
		$SQLQuery = "INSERT INTO" . $this->sql_tables() . $this->sql_values_insert() . $this->sql_where() . $this->sql_groupby() . $this->sql_orderby() . $this->sql_limit();
		if($this->error) {
			print processError($this->error);
			return "";
		}
		return $SQLQuery;	
	}
	
	function get_sql_update() {
		$SQLQuery = "UPDATE" . $this->sql_tables() . $this->sql_values_update() . $this->sql_where() . $this->sql_limit();
		if($this->error) {
			print processError($this->error);
			return "";
		}
		return $SQLQuery;
	}
	
	function get_sql_delete() {
		$SQLQuery = "DELETE FROM" . $this->sql_tables() . $this->sql_where() . $this->sql_limit();
		if($this->error) {
			print processError($this->error);
			return "";
		}
		return $SQLQuery;
	}
}

?>