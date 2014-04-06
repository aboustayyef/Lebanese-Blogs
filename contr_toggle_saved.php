<?php 
require_once('init.php');

if (LbUser::isSignedIn()) {
	$user_id = $_POST['user'];
	$post_url = $_POST['url'];
	Posts::toggleSaved($user_id, $post_url);
}
?>