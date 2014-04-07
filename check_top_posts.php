<?php 

/*This script checks the top post, and if it's new, publishes it to twitter and facebook*/

require_once('init.php');
require ABSPATH.'classes/facebook-php-sdk/src/facebook.php';  // Include facebook SDK file
require ABSPATH.'classes/twitter/twitter.class.php';  // Include facebook SDK file

if (isset($argv[1]) && ($argv[1] == 'debug')) {
	$debugMode = true;
}else{
	$debugMode = false;
}

$connection = DB::getInstance();

/* Step 1: Find which posts is currently the top (we will use the 12 hour time frame) */

$topPosts = Posts::get_Top_Posts(12, 1);
$topPost = $topPosts[0];

if ($debugMode) {
	echo "TopPost:";
	print_r($topPost);
}

/* Step 2: Is the post already in the database?*/
$results = $connection->query('SELECT * FROM top_posts WHERE top_post_url = "'.$topPost->post_url.'"')->results();
echo count($results);
if ((count($results) > 0) && !$debugMode) { // post already exists and we're not debugging
	echo 'Post Already Exists';
}else{ // post is new
	if ($topPost->post_visits > 8 ) { // post need to be clicked at least 8 times to avoid a de-facto top 1 (like those late at night)
		// add post to database
		add_post_to_db($topPost->post_url);

		// add post to facebook
		add_post_to_facebook($topPost);

		// add post to twitter
	 	add_post_to_twitter($topPost);
	}else{
		# Do Nothing
	}
}


/* Functions below */

function add_post_to_db($url){
	global $debugMode;
	if ($debugMode) {
		return;
	}
	$connection = DB::getInstance();
	$connection->insert('top_posts', array(
		'top_post_url'				=>	$url,
		'top_post_timestamp_added'	=> time()
	));
}

function add_post_to_facebook($postObject){
	global $debugMode;
	/*
		Reference: http://www.pontikis.net/blog/auto_post_on_facebook_with_php
		postObject has the following attributes: post_image , post_timestamp , post_image_width , post_image_height , post_url , post_title , blog_name , blog_id , col_name, blog_author_twitter_username
	*/

	// initialize Facebook class using Facebook App credentials
	// see: https://developers.facebook.com/docs/php/gettingstarted/#install

	// page ID: 625974710801501

	$config = array();
	$config['appId'] = '1419973148218767';
	$config['secret'] = '16a49abb2d49c364d06b72eae7c79c1a';
	$config['fileUpload'] = false; // optional

	// to avoid monotony, we prepare several possible wordings for the facebook message
	if (!isset($postObject->blog_name)) { //it is a column, not a blog
		$site = $postObject->col_name;
	}else{
		$site = $postObject->blog_name;
	}
	$attribution = '"'.$postObject->post_title.'" by '.$site;
	$variety_of_messages = array(
		$attribution.' is now the most popular post on Lebanese Blogs',
		'The most popular blog post on Lebanese Blogs right now is '.$attribution, 
		'A new blog post is now the most popular on Lebanese Blogs: '.$attribution);

	$messageToShare = $variety_of_messages[rand(0,count($variety_of_messages)-1)];

	if ($debugMode) {
		echo "Message to Share on Facebook: \n";
		echo "$messageToShare \n";
		return;
	}


	$fb = new Facebook($config);

	// define your POST parameters (replace with your own values)
	$params = array(
	  "access_token" => "CAAULdUMXFY8BAC9P1GZBQs52EOmW7eO6s7ZB5WRZB2Wek4Vn1Yrud31VLGVb0zAGeZBcJ4hZA4mUaAhxdO1Gfro1FgkLOkouSC370ogkZA47P1kIGhoAczgxL98grJe3tekpHXA0nisLweWb5SKNnIapWLNaWbPBZBe3HjoxyRle5ZAntI3sZAsTu", // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
	  "message" => $messageToShare,
	  "link" => $postObject->post_url,
	  //"picture" => "http://i.imgur.com/lHkOsiH.png",
	  //"name" => "How to Auto Post on Facebook with PHP",
	  //"caption" => "www.pontikis.net",
	  //"description" => "Automatically post on Facebook with PHP using Facebook PHP SDK. How to create a Facebook app. Obtain and extend Facebook access tokens. Cron automation."
	);

	// post to Facebook
	// see: https://developers.facebook.com/docs/reference/php/facebook-api/
	try {
	  $ret = $fb->api('/625974710801501/feed', 'POST', $params);
	  echo 'Successfully posted to Facebook';
	} catch(Exception $e) {
	  echo $e->getMessage();
	}

}

function add_post_to_twitter($postObject){
	global $debugMode;
	/* Step 5: Add post to twitter 
		Reference: http://www.pontikis.net/blog/auto_post_on_twitter_with_php
	*/

	// get twitter handle
	if (!isset($postObject->blog_name)) { //it is a column, not a blog
		$twitter_author = $postObject->col_author_twitter_username;
	}else{
		$twitter_author = $postObject->blog_author_twitter_username;
	}

	$length_of_twitter_handle = strlen($twitter_author);
	$title_allowance = 59 - $length_of_twitter_handle; // twitter handle + title should be equal to 59 in length to accomodate rest of tweet.
	$title = substr($postObject->post_title, 0, $title_allowance);

	$status = 'New Top Post: '.$title.' by @'.$twitter_author.', '.$postObject->post_url.'. More at lebaneseblogs.com';
	
	if ($debugMode) {
		echo "Twitter Status: \n";
		echo "$status \n";
		return;
	}

	$twitter = new Twitter('JFJmBCbVrLfBFu5u0TDdzg', 'QI8jrDWQdXH6TFb8zSYZ8gzWDW5DpSakBlQ7qdHZYI', "1054796604-YlpZJiKXOrGvQAcU6fuzLvUljubIHToUfBRSUgV", "ydm1xxTU1OmA1Nsq3CStrr3CLcXJOAYpagdV7E1Aco1SJ");
	try {
	    $twitter->send($status);
	} catch (TwitterException $e) {
	    echo "\nTwitter Error: ", $e->getMessage();
	}
	echo $status;

}
?>