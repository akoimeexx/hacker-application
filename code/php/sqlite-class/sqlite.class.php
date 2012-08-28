<?php
	/**
	 * SQLite3 wrapper class module
	 */
	class SQLiteDB {
		/* Define constant values */
		/* ---------------------- */
		
		/* Define private properties */
		protected $SQLiteDB_hwd = null;
		
		/* Define public properties */
		/* ------------------------ */
		
		
		public function __construct($filename) {
			$this->SQLiteDB_hwd = new SQLite3($filename);
			if($this->SQLiteDB_hwd === null) {
				unset($this);
			}
		}
		
		public function __destruct() {
			$this->SQLiteDB_hwd->close();
			unset($this->SQLiteDB_hwd);
			unset($this);
		}
		
		
		/**
		 * CRUD function block:
		 * 	create(string TableName, assoc_array Fieldnames=>Values)
		 * 	read(string TableName, assoc_array NeedleFieldnames=>Values, array FieldsToReturn)
		 * 	update
		 * 	delete
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
			$table = sqlite_escape_string($table);
			
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
					$where_strings[] = "$fieldname=\"" . sqlite_escape_string($value) . "\"";
				}
				$wheres .= implode(" AND ", $where_strings);
			}
			
			/**
			 * Build our query and check for results. If results 
			 * are found, return them as an array
			 */
			$sqlite_results = $this->SQLiteDB_hwd->query("SELECT $selects FROM $table$wheres;");
			if($sqlite_results == false) {
				return false;
			}
			$records = array();
			while($record = $sqlite_results->fetchArray(SQLITE3_ASSOC)) {
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
		
		
		
		
		private function is_assoc(array $array) {
			foreach(array_keys($array) as $key) {
				if(!is_int($key)) return true;
			}
			return false;
		}
		private function is_table($table) {
			$sqlite_results = @$this->SQLiteDB_hwd->query("SELECT $table FROM sqlite_master WHERE type='table' AND name='$table';");
			if(count($sqlite_results) < 1) {
				return false;
			}
			return true;
		}
	}
?>
