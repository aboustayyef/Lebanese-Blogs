<?php 

require_once('../init.php');
require_once('admin_functions.php');

if (!admin_logged_in()) {
	header('location:login.php');
} 

$connection = DB::getInstance();

$connection->delete('posts', array('post_id','=', $_POST['post_id']));

header('location:'.WEBPATH.'../');
?>