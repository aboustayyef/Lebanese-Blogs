<?php 
/* 	
	This controller is called by javascript (AJAX) to load more posts where infinite scrolling is needed, 
	it fetches extra posts using the posts class (model) then loads a view to display them	
*/

// 	Assets are included because controller is independently called from Javascript and does not carry forward data;
	include_once("init.php"); 

// 	data from $.post() 
	$start_from = $_SESSION['posts_displayed']; 
	$channel= $_SESSION['currentChannel'];
//	model
	
	if ($_SESSION['currentView'] =='compact') {
		$posts_per_refresh = 50;
	} else {
		$posts_per_refresh = 20;
	}

	$userID = LbUser::getFacebookID();
	$data = Posts::get_saved_bloggers_posts($userID, $_SESSION['posts_displayed'], $posts_per_refresh);
	switch ($_SESSION['currentView']) {
		case 'cards':
			Render::drawCards($data, 'normal');
			break;
		case 'timeline':
			Render::drawTimeline($data);
			break;
		case 'compact':
			Render::drawCompact($data);
			break;
	}
?>