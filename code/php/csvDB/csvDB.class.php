<?php
	class csvDB {
		/**
		 * Class constant declarations
		 */
		const FILE_MIMETYPE = 'text/csv';
		const FILE_EXTENSION = 'csv';
		
		const READ = "r";
		const WRITE = "a";
		const READWRITE = "a+";
		
		/**
		 * Class variable declarations
		 */
		public $raw; // Array of raw CSV data
		public $db;
		
		/**
		 * Class debugging variable declarations
		 */
		static $console = array(); // console Array
		public $debug = false; // Debugging switch
		
		
		/**
		 * csvDB Constructor (Obvs)
		 */
		function __construct($csv_filename, $csv_mode=csvDB::READ, $line_headers=true) {
			if(file_exists($csv_filename)) {
				$this->load_db($csv_filename, $csv_mode, $line_headers);
			}
		}
		
		/**
		 * csvDB Destructor (Again, Obvs)
		 */
		function __destruct() {
			
			
		}
		
		public function load_db($csv_filename, $csv_mode, $line_headers=true, $delimiter=',', $enclosure='"', $escape = '\\') {
			/**
			 * Check to see if the file being passed in exists and
			 * if so, load it as an array and close the file.
			 */
			if(!file_exists($csv_filename)) {
				csvDB::$console[$this][] = "File not found: " . $csv_filename;
			} elseif ($this->raw = file($csv_filename)) {
				// If we are expecting line headers, pull that data
				if($line_headers) {
					$csv_header = str_getcsv($this->raw[0], $delimiter, $enclosure, $escape);
					foreach($csv_header as $key=>$value) {
						$this->db['HEADER'][] = $value;
					}
				}
				// Load our db records
				foreach($this->raw as $key=>$value) {
					if(($line_headers && $key != 0) || $line_headers != true) {
						$this->db[$key] = str_getcsv($value);
					}
				}
			} else {
				csvDB::$console[$this][] = "There was an unspecified error while trying to open " . $csv_filename . ".";
			}
		}

		public function search($needle, $haystack=null) {
			if(empty($haystack)) {
				$haystack = $this->db;
			}
			foreach($haystack as $record_key=> $record_array) {
				if($record_key != "HEADER") {
					foreach($record_array as $field_key=>$field_value) {
						if(trim(strtolower($needle)) === trim(strtolower($field_value)))
						{
							$needle_stack[] = $record_array;
							break;
						}
					}
				}
			}
			if(count($needle_stack) >= 1) {
				return $needle_stack;
			}
			return false;
		}
		
	 	/**
		 * Sanitize input data, return a comma-delimited string if input 
		 * is an array.
		 */
		public static function sanitize($input) {
			$cleaning = $input;
			
			switch ($cleaning) {
				case trim($cleaning) == "":
					$clean = false;
					break;
				case is_array($cleaning):
					foreach($cleaning as $key => $value) {
						$cleaning[] = sanitize($value);
					}
					$clean = implode(",", $cleaning);
					break;
				default:
					if(get_magic_quotes_gpc()) {
						$cleaning = stripslashes($cleaning);
					}
					$cleaning = strip_tags($cleaning);
					$clean = trim($cleaning);
			}
			
			return $clean;
		}
	}
?>
