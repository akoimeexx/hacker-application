<?php
	/**
	 * MySQL wrapper class module
	 */

	class MySqlDB {
		/* Define constant values */
		/* ---------------------- */
		
		/* Define private properties */
		protected $MySqlDB_hwd = null;
		
		/* Define public properties */
		/* ------------------------ */
		
		
		public function __construct($server, $database, $username, $password) {
			$this->MySqlDB_hwd = mysql_connect($server, $username, $password) or $this->MySqlDB_hwd = null;
			if($this->MySqlDB_hwd === null) {
				$this->__destruct();
			} else {
				mysql_select_db($database, $this->MySqlDB_hwd);
			}
		}
		
		public function __destruct() {
			mysql_close($this->MySqlDB_hwd);
			unset($this->MySqlDB_hwd);
			unset($this);
		}
		
		
		/**
		 * CRUD function block:
		 * 	create(string TableName, assoc_array Fieldnames=>Values)
		 * 	read(string TableName, assoc_array NeedleFieldnames=>Values, array FieldsToReturn)
		 * 	update
		 * 	destroy
		 *
		 */
		
		
		/**
		 * SQL "INSERT INTO" a new record into table
		 */
		public function create($table, $fields=null) {
			/**
			 * Check to make sure the table exists
			 */
			if(!$this->is_table($table)) { return false; }

			if($fields == null) {
				return false;
			}
			
			return true;
		}
		
		/**
		 * SQL "SELECT" records from a specified table
		 */
		public function read($table, $select_fields=null, $field_needles=null) {
			/**
			 * Check to make sure the table exists
			 */
			if(!$this->is_table($table)) { return false; }
			$table = mysql_real_escape_string($table);
			
			/**
			 * If SQL "SELECT" fields are passed in, build that 
			 * part of our query
			 */
			if($select_fields != null && is_array($select_fields)) {
				$selects = implode(', ', $select_fields);
			} else {
				$selects = "*";
			}
			
			/**
			 * If SQL "WHERE" fields are passed in, build the 
			 * fieldname/value pairs.
			 */
			$wheres = "";
			if(is_array($field_needles) && $this->is_assoc($field_needles)) { 
				$wheres=" WHERE ";
				foreach($field_needles as $fieldname=>$value) {
					$where_strings[] = "$fieldname=\"" . mysql_real_escape_string($value) . "\"";
				}
				$wheres .= implode(" AND ", $where_strings);
			}
			
			/**
			 * Build our query and check for results. If results 
			 * are found, return them as an array
			 */
			$query = "SELECT $selects FROM $table$wheres;";
			$sql_results_resource = $this->query($query);
			if($sql_results_resource == false) {
				return false;
			}
			$records = array();
			while($record = mysql_fetch_assoc($sql_results_resource)) {
				$records[] = $record;
			}
			return $records;
		}
		
		public function update($table, $field_needles=null, $fields=null) {
			/**
			 * Check to make sure the table exists
			 */
			if(!$this->is_table($table)) { return false; }

			if($fields == null) {
				return false;
			}
			
			return true;
		}
		public function delete($table, $field_needles) {
			/**
			 * Check to make sure the table exists
			 */
			if(!$this->is_table($table)) { return false; }

			if($field_needles == null) {
				return false;
			}
			
			return true;
		}
		
		
		
		private function query($raw_query) {
			return mysql_query($raw_query, $this->MySqlDB_hwd);
		}
		
		private function is_assoc(array $array) {
			foreach(array_keys($array) as $key) {
				if(!is_int($key)) return true;
			}
			return false;
		}
		private function is_table($table) {
			$query = "SHOW TABLES LIKE '" . mysql_real_escape_string($table) . "';";
			$sql_results_resource = $this->query($query);
			
			if(mysql_num_rows($sql_results_resource) != 1) {
				return false;
			}
			return true;
		}
	}
?>
