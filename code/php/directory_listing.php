<?php
	function ll($dir_path, $sorted = true, $include_navs = false) {
		$dir_handle = opendir($dir_path);
		while(($dir_item = readdir($dir_handle)) !== false) {
			if($sorted) {
				if(is_dir($dir_item)) {
					$dir_array[] = $dir_item . '/';
				} else {
					$file_array[] = $dir_item;
				}
			} else {
				$results[] = $dir_item;
			}
		}
		closedir($dir_handle);
		
		if($sorted) {
			sort($dir_array);
			if(!$include_navs) {
				unset($dir_array[array_search('./', $dir_array)]);
				unset($dir_array[array_search('../', $dir_array)]);
			}
			sort($file_array);
			
			$results = array_merge($dir_array, $file_array);
		}
		return $results;
	}
	
	$res = ll('./');
	print_r($res);
	$res = ll('./', false);
	print_r($res);
?>
