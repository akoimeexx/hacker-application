<?php
	include('cache.class.php');
	
	
	
	$SiteCache = new Cache;
	echo "\r\n";
	echo "Getting the cachelist via __get() function:\r\n";
	var_dump($SiteCache->cachelist);
	
	echo "\r\n";
	echo "Getting the cachelist via cachelist() function:\r\n";
	var_dump($SiteCache->cachelist());
	
	echo "\r\n";
	echo "Setting the cachelist via cachelist('http://random-site.com/somepage.html') function:\r\n";
	$SiteCache->cachelist('http://random-site.com/somepage.html');
	$SiteCache->cachelist('http://some-site.com/somepage.html');
	var_dump($SiteCache->cachelist());
	
	echo "\r\n";
	echo "Setting the cachelist via __set() = (string) 'http://some-site.com/somepage.html' function:\r\n(overwrites any previous array)\r\n";
	$SiteCache->cachelist = (string) 'http://some-site.com/somepage.html';
	var_dump($SiteCache->cachelist);
	
	echo "\r\n";
	echo "Getting the timeout via __get() function:\r\n";
	var_dump($SiteCache->timeout);
	
	echo "\r\n";
	echo "Setting the timeout via __set() = (string) '60' function:\r\n";
	$SiteCache->timeout = (string) '60';
	var_dump($SiteCache->timeout);
	
		
	echo "\r\n\r\n";
	echo "Dump of entire \$SiteCache instance:\r\n";
	var_dump($SiteCache);

	
	unset($SiteCache);
	
	
	echo "\r\nAttempting a full on caching of http://akoimeexx.com/\r\n";
	$Cache = new Cache;
	
	$page_content = $Cache->load("http://akoimeexx.com/");
	echo '/******************************************************************************\\' . "\r\n";
	echo '|                     Contents of Akoimeexx.com index page                     |' . "\r\n";
	echo '\\******************************************************************************/' . "\r\n";
	echo $page_content;
	echo "\r\n";
	//echo "\r\n";
	//echo "Test is_expired() (string) 'cache.class.php' function:\r\n";
	//var_dump($SiteCache->is_expired('cache.class.php'));
	
	//echo "\r\n";
	//echo "Getting a non-existent property via __get function:\r\n";
	//echo $SiteCache->graylist . "\r\n";
	
	//echo "\r\n";
	//echo "Setting a non-existent property via __get function:\r\n";
	//$SiteCache->graylist = 'http://some-site.com/somepage.html';
?>
