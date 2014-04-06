<?php 
include_once('init.php');

switch ($_GET['type']) {
	case 'top':
		// top requires the parameters hours, howmany and channel
		// defaults are 12 hours, 5 items and Null;
		$hours = empty($_GET['hours'])? 12:$_GET['hours'];
		$howmany = empty($_GET['howmany'])? 5:$_GET['howmany'];
/*		$result = Posts::get_top_posts($hours, $howmany, $channel);
		$JSONresult = json_encode($result);
		exit($JSONresult);*/
		Extras::topFive($howmany, $hours, $_SESSION['currentChannel']);
		break;
	
	default:
		# code...
		break;
}

?>