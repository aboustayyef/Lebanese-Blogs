<?php 

require_once('../init.php');
require_once('admin_functions.php');

if (!admin_logged_in()) {
	header('location:login.php');
} 

$connection = DB::getInstance();

$connection->update_advanced('posts', array('post_id', $_POST['post_id']), array(
	'post_title'		=>	$_POST['post_title'],
	'post_excerpt'		=>	$_POST['post_excerpt'],
	'post_timestamp'	=> 	$_POST['post_timestamp'],
	'post_image'		=> 	$_POST['post_image'],
	'post_image_width' 	=> 	$_POST['post_image_width'],
	'post_image_height' =>	$_POST['post_image_height'],
));

header('location:'.WEBPATH.'../');
?>