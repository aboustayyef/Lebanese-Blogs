<?php 

/************************************************************************************************
This script gets the social scores of all the posts in the last 7 days 
and includes them in the posts database 
************************************************************************************************/ 

include_once("init.php");
require_once("classes/SocialScore.class.php");
require_once("classes/simplepie.php");

// Define CLI Interface Elements
$line_length = 70;
$hr  = "\n".str_repeat('-', $line_length)."\n";
$dhr = "\n".str_repeat('=', $line_length)."\n";

$hours = 24 * 60;

// If an argument is supplied, use its value as the number of hours;
if ((!empty($argv[1])) && ($argv[1] > 0)) {
  $hours = $argv[1];
}


$hoursAgo = time() - ($hours * 60 * 60);

$posts = DB::getInstance()->query('SELECT post_url, post_visits FROM posts WHERE `post_timestamp` > '.$hoursAgo)->results();

foreach($posts as $key => $post){
  echo $post->post_url;
  $postVisits = $post->post_visits;
	$score = new SocialScore($post->post_url);
  $totalScore = $score->getFacebookScore() + $score->getTwitterScore();
  
  // The formula for social score scales downward the social sharing to avoid disproportional figures;
  $socialScore = $postVisits + ( 2 * round(sqrt($totalScore)));

  DB::getInstance()->query('UPDATE `posts` SET `post_facebookShares` = ' . $score->getFacebookScore() . ' WHERE `post_url` = "' . $post->post_url . '"');
  DB::getInstance()->query('UPDATE `posts` SET `post_twitterShares` = ' . $score->getTwitterScore() . ' WHERE `post_url` = "' . $post->post_url . '"');
  DB::getInstance()->query('UPDATE `posts` SET `post_totalShares` = ' . $totalScore . ' WHERE `post_url` = "' . $post->post_url . '"');
	DB::getInstance()->query('UPDATE `posts` SET `post_socialScore` = ' . $socialScore . ' WHERE `post_url` = "' . $post->post_url . '"');
  echo "\n";
}
// Note: $virality = round(2 + 1.5*(sqrt($totalScore)));

?>