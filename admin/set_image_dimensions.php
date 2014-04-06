<?php

// One-off script to set the width and height for all images in database
// connect to database

include_once("config.php");
include_once("connection.php");


global $db;

//error handling
if (!($db)) {
	echo "Error in connection";
	die();
}

// get feeds
$query = $db->query('SELECT `post_image`,`post_image_height`,`post_image_width` FROM `posts` WHERE `post_image` LIKE "%.%" AND  `post_image_height` = 0');

$feeds = $query->fetchAll();
foreach ($feeds as $feed) {
	echo "Image: ", $feed['post_image'];
	//echo "width ", $feed['post_image_width'],"<br/>";
	//echo "height ", $feed['post_image_height'],"<br/>";
	list($width,$height,$x,$y) = getimagesize($feed['post_image']);
	$image = $feed['post_image'];

	$stmt = $db->prepare ('UPDATE posts SET post_image_height=? ,post_image_width=? WHERE post_image=?');
	$stmt->execute(array($height, $width, $image));
	if ($stmt->rowCount()) {
		Echo '-ok- <br/>';
	}
	echo "<hr>";
}

// fetch all the records where image is not null and dimensions are null

//perform a loop
		//check dimensions of image
		//insert in database
//end loop
 ?>