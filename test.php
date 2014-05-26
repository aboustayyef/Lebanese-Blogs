<?php 
	/* testing */
require_once('init.php');
require_once("classes/SocialScore.class.php");

$score = new SocialScore('https://now.mmedia.me/lb/en/reportsfeatures/548863-the-presidential-equation');
echo $score->getTwitterScore();
echo "------";
echo $score->getFacebookScore();

?>