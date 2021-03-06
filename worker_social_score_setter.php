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

$posts = DB::getInstance()->query('SELECT post_title, post_url, post_visits FROM posts WHERE `post_timestamp` > '.$hoursAgo)->results();

foreach($posts as $key => $post){
  echo $post->post_url;
  $postVisits = $post->post_visits;
	$score = new SocialScore($post->post_url);
  $title = $post->post_title;
  $totalScore = $score->getFacebookScore() + $score->getTwitterScore();
  
  // make total shared more weighed by twitter because it's less easy to game and buy
  $totalScore = round((($score->getFacebookScore() + (2 * $score->getTwitterScore())) / 3 ) * 2 );
  
  // formula#1: $virality = 2+round(3 * sqrt($shares));
  // fromula#2: $virality = 2 + round(6 * (pow($totalScore, 1/3)));
  $virality = $totalScore > 1 ? round( 8 * log($totalScore) ) : 2 ;
    if ($virality > 50) {
      $virality = 50;
  }

  // The formula for social score 
  $socialScore = $postVisits + round($virality / 2);

  // Listicle Penalty (20%)
  if (isListicle($title)) {
    $socialScore = round($socialScore*0.8);
  }

  // logging
  echo "\ntotal shares: $totalScore, Virality: $virality\n";
  if (isListicle($title)) {
    echo "\n(Listicle penalty added)\n";
  }

  DB::getInstance()->query('UPDATE `posts` SET `post_facebookShares` = ' . $score->getFacebookScore() . ' WHERE `post_url` = "' . $post->post_url . '"');
  DB::getInstance()->query('UPDATE `posts` SET `post_twitterShares` = ' . $score->getTwitterScore() . ' WHERE `post_url` = "' . $post->post_url . '"');
  DB::getInstance()->query('UPDATE `posts` SET `post_totalShares` = ' . $totalScore . ' WHERE `post_url` = "' . $post->post_url . '"');
	DB::getInstance()->query('UPDATE `posts` SET `post_socialScore` = ' . $socialScore . ' WHERE `post_url` = "' . $post->post_url . '"');
  DB::getInstance()->query('UPDATE `posts` SET `post_virality` = ' . $virality . ' WHERE `post_url` = "' . $post->post_url . '"');
  echo "\n";
}

function isListicle($title){
  $title = strtolower($title); 
  $parts = explode(" ", $title);
  $firstWord = $parts[0];
  $secondWord = $parts[1];
  $listOfNumbers = array('3','4','5','6','7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', 'three','four','five','six','seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
  if ((in_array($firstWord, $listOfNumbers))||(in_array($secondWord, $listOfNumbers))) {
    return TRUE;
  }else{
    return FALSE;
  }
}

?>