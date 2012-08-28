<?php
	/**
	 * Sanitize input data, return a comma-delimited string if input 
	 * is an array.
	 */
	function sanitize($input) {
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
				break;
		}
		
		return $clean;
	}
?>
