<?php 
$startTime = microtime(true);
session_start();
//dependencies
include_once("config.php");
/*function my_autoloader($class) {
	include 'classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');*/

include ABSPATH.'/classes/GetArticles.php';
// Will manually include classes to control which classes I'm no longer using
include ABSPATH.'/classes/DB.class.php'; 
include ABSPATH.'/classes/Posts.class.php'; 
include ABSPATH.'/classes/View.class.php';
include ABSPATH.'/classes/Channels.class.php';
include ABSPATH.'/classes/ViewTypes.class.php';
include ABSPATH.'/classes/Render.class.php';
include ABSPATH.'/classes/Lb_functions.class.php';
include ABSPATH.'/classes/BloggerDetails.class.php';
include ABSPATH.'/classes/LbUser.class.php';
include ABSPATH.'/classes/Lb_Search.class.php';

include ABSPATH.'/classes/Extras.class.php';
include ABSPATH.'/classes/Image.class.php';

include ABSPATH.'/classes/PageControllers/BrowsePage.class.php';
include ABSPATH.'/classes/PageControllers/BloggerPage.class.php';
include ABSPATH.'/classes/PageControllers/StaticPage.class.php';
include ABSPATH.'/classes/PageControllers/LoginPage.class.php';
include ABSPATH.'/classes/PageControllers/FavoritesPage.class.php';
include ABSPATH.'/classes/PageControllers/SavedPage.class.php';
include ABSPATH.'/classes/PageControllers/SearchPage.class.php';
include ABSPATH.'/classes/PageControllers/_Static_About.class.php';


//facebook
include ABSPATH.'/classes/facebook-php-sdk/src/facebook.php';
//include ABSPATH.'/classes/PageControllers/SearchPage.class.php';
?>