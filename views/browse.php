<?php 
// This is the router for the viewtypes
// initialize general counter of all posts
	echo "<!-- WE ARE NOW IN BROWSE.PHP -->";

	$_SESSION['posts_displayed'] = 0; //number of posts shown
	$_SESSION['items_displayed'] = 0; // number of items shown (including other widgets)
	
	// Get initial posts. Initiate model.
	$posts = new Posts($db);
	// the compact mode gets more initial posts;
	if ($this->_view == "compact") {
		$initial_posts_to_retreive = 50;
	}else{
		$initial_posts_to_retreive = 20;
	}
	$data = $posts->get_latest_posts($initial_posts_to_retreive, $_SESSION['channel']); 
	//envelope the posts;
	echo '<div id="posts">';
		Lb_functions::display_posts($data);
	echo '</div> <!-- /posts -->';
?>