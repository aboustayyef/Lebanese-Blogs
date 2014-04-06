<?php

/**
* Configuration file for Lebanese Blogs
* @author Mustapha Hamoui<mustapha.hamoui@gmail.com>
*/

// Absolute directory path to main directory
define('ABSPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

// Home page web address
$url = findWebRoot(); // custom function, at bottom of this document
define('WEBPATH', $url);


// Facebook APP ID (), one of the two must be commented out.

/*Testing Server, "App for testing" facebook app*/
define('FACEBOOK_APP_ID', '269731313194548');
define('FACEBOOK_APP_SECRET', '1bac35b7253d2e0fcef1cf9f620d2de7');

/*Live Server, "Lebanese Blogs" facebook app*/
//define('FACEBOOK_APP_ID', '1419973148218767'); 
//define('FACEBOOK_APP_SECRET', '16a49abb2d49c364d06b72eae7c79c1a');  

// Database Connection
define('DB_USER', 'mustapha');
define('DB_PASS', 'mm000741');
define('DB_HOST', '127.0.0.1');
define('DB_DBASE', 'lebanese_blogs');
setlocale(LC_MONETARY, "en_US");

// Runtime settings
define('GOOGLE_FONTS','yes');
define('STATCOUNTER_TRACKER', 'no');
define('ANALYTICS_TRACKER', 'no');
define('JQUERY_SOURCE', 'local'); //options: 'local', 'cdn' or 'none'

// For CDN Purposes
define('THUMBS_BASE', 'http://localhost/lebanese_blogs/img/thumbs'.DIRECTORY_SEPARATOR);
define('IMGCACHE_BASE', 'http://localhost/lebanese_blogs/img/cache'.DIRECTORY_SEPARATOR);



// This utility function extracts the web root of the current document
function findWebRoot(){
  
  $host = 'http://'.$_SERVER['HTTP_HOST']; // example: http://localhost or http://lebaneseblogs.com
  $self = explode('/', $_SERVER['PHP_SELF']);
  array_pop($self); // removes last item from the array, which is config.php in this case

  // allow for a second level in directory hierarchy
  // We're making use of the fact that ABSPATH is related to the root 
  // folder and not to the folder of the file that includes it

  $abs_path = explode(DIRECTORY_SEPARATOR, ABSPATH);
  array_pop($abs_path);
  if (end($abs_path) !== end($self)) {
      array_pop($self); 
  }

  $root_directory = implode('/', $self); 
  $url = $host . $root_directory . DIRECTORY_SEPARATOR;
  return $url;
}

?>