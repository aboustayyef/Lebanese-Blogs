<?php 

require_once('../init.php');
require_once('admin_functions.php');

if (!admin_logged_in()) {
	header('location:login.php');
} 

$connection = DB::getInstance();

$connection->insert('blogs', array(
	'blog_id'			=>	$_POST['blogid'],
	'blog_name'			=>	$_POST['blogname'],
	'blog_description'	=> 	$_POST['blogdescription'],
	'blog_url'			=> 	$_POST['blogurl'],
	'blog_author' 	 	=> 	$_POST['blogauthor'],
	'blog_author_twitter_username' => $_POST['blogtwitter'],
	'blog_rss_feed'		=> 	$_POST['blogrss'],
	'blog_tags'			=> 	$_POST['blogtags'],
	'id'				=>	NULL,
	'blog_active'		=> 	'1'));

	echo '<h1>SUCCESS!</h1>';
	echo '<h2>The following details have been inserted into the database</h2>';

	$results = $connection->get('blogs', array('blog_id','=',$_POST['blogid']))->results();
	echo "<pre>";
	print_r($results);
	echo "</pre>";
?>