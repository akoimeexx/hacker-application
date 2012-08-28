<?php
	/**
	 * 
	 * 
	 * 
	 */
	
	class Cache {
		/**
		 * Class constant declarations
		 */
		const CACHE_FILE_DIRECTORY = './cache/';
		const CACHE_FILE_EXTENSION = '.cache';
		const CACHE_FILE_MANIFEST = 'cache.manifest';
		const CACHE_FILE_MIMETYPE = 'binary/octet-stream';
		
		/**
		 * Class variable declarations
		 */
		private $cachelist = array();
		private $timeout = 3600; /* Seconds till cached item is expired. */
		
		/**
		 * Class debugging variable declarations
		 */
		
		
		function __construct() {
			
		}
		
		function __destruct() {
			
		}
		
		/**
		 * Universal get method for private property variables
		 */
		public function __get( $key ) {
			if(isset($this->$key)) {
				return $this->$key;
			} else {
				throw new Exception("Undefined property '$key'");
			}
		}
		
		/**
		 * Universal set method for private property variables, with 
		 * special attention to cachelist property
		 */
		public function __set( $key, $val ) {
			if(isset($this->$key)) {
				// Get property variable type
				$key_type = gettype($this->$key);
				if($key == 'cachelist') {
					/**
					 * cachelist array indices must be the 
					 * md5hash of their value
					 */
					$this->$key = array(md5($val)=>$val);
				} else {
					$this->$key = $val;
					// Retain old property variable type
					settype($this->$key, $key_type);
				}
			} else {
				throw new Exception("Undefined property '$key'");
			}
		}
		
		/**
		 * Specialized Get/Set method for cachelist property variable
		 */
		public function cachelist( $url=null ) {
			if($url === null) {
				return (array) $this->cachelist;
			}
			// Set cachelist array index to md5hash of their value
			$this->cachelist[md5($url)] = $url;
		}
		
		public function load( $url ) {
			if($this->is_cached( $url )) {
				if($this->is_expired($url)) {
					$this->set_cached_url($url);
				}
				return $this->get_cached_url($url);
			} else {
				$this->set_cached_url($url);
				return $this->get_cached_url($url);
			}
			throw new Exception("Unknown exception while loading '$url'!");
		}
		
		/**
		 * 
		 */
		private function get_cache_filename( $url ) {
			return Cache::CACHE_FILE_DIRECTORY . md5($url) . Cache::CACHE_FILE_EXTENSION;
		}

		private function is_cached( $url ) {
			return file_exists($this->get_cache_filename($url));
		}

		/**
		 * Check if a cache file has expired or not
		 */
		private function is_expired( $url ) {
			$timestamp = 0;
			if(file_exists($this->get_cache_filename($url))) {
				$timestamp = @filemtime($this->get_cache_filename($url));
				@clearstatcache();
				
				if(time() - $this->timeout < $timestamp) {
					return false;
				}
			} else {
				throw new Exception("File '$cache_file' does not exist!");
			}
			return true;
		}
		
		private function get_cached_url( $url ) {
			if($this->is_cached($url)) {
				return file_get_contents($this->get_cache_filename($url));
			} else {
				throw new Exception("Cache '$url' does not exist!");
			}
		}
		
		private function set_cached_url( $url ) {
			$buffer = @file_get_contents($url);
			$cache_file_handle = @fopen($this->get_cache_filename($url), 'w');
			@fwrite($cache_file_handle, $buffer);
			@fclose($cache_file_handle);
			
			$this->cachelist($url);
		}
	}

?>
