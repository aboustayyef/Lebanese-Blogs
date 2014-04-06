<?php 
/* 
  This script finds the date of the latest post for each blogger and stores that in blogger's table
  This is a working script. Ideally it is only launched once after the database is altered or restet
  
  This script should be launched from the PHP-CLI not via web interface
*/

require_once('init.php');

// initialize db connection
$connection = DB::getInstance();


// Bloggers

// First, check if blogs table has the blog_last_post_timestamp field;
$check = $connection->query('SELECT blog_last_post_timestamp from blogs')->results();
if (count($check)<1) {
  die('Create field "blog_last_post_timestamp" then try again');
}

// get all Bloggers (including inactive ones)
$bloggers = $connection->query('SELECT blog_id from blogs')->results();

// loop through bloggers
foreach ($bloggers as $key => $blogger) {
  $blog_id = $blogger->blog_id;
  echo "The latest post by $blog_id was on ";
  // get blogger's latest post
  $latest_posts = $connection->query('SELECT post_timestamp FROM posts WHERE blog_id ="'.$blog_id.'" ORDER BY post_timestamp DESC LIMIT 1')->results();
  if (count($latest_posts) == 1) {
    $latest_post = $latest_posts[0];
    $latest_post_timestamp = $latest_post->post_timestamp;
    DB::getInstance()->query('UPDATE blogs SET blog_last_post_timestamp = '.$latest_post_timestamp.' WHERE `blog_id` = "'.$blog_id.'"');
    echo " $latest_post_timestamp<br>";
  } else {
    echo "(Blogger $blog_id Seems Inactive)<br>";
  }
}

// Columnists  

// First, check if Columnists table has the col_last_post_timestamp field;
$check = $connection->query('SELECT col_last_post_timestamp from columnists')->results();
if (count($check)<1) {
  die('Create field "col_last_post_timestamp" then try again');
}


// get all Columnists (including inactive ones)
$columnists = $connection->query('SELECT col_shorthand from columnists')->results();

// loop through columnists
foreach ($columnists as $key => $columnist) {
  $blog_id = $columnist->col_shorthand;
  echo "The latest post by $blog_id was on ";
  // get blogger's latest post
  $latest_posts = $connection->query('SELECT post_timestamp FROM posts WHERE blog_id ="'.$blog_id.'" ORDER BY post_timestamp DESC LIMIT 1')->results();
  if (count($latest_posts) == 1) {
    $latest_post = $latest_posts[0];
    $latest_post_timestamp = $latest_post->post_timestamp;
    DB::getInstance()->query('UPDATE columnists SET col_last_post_timestamp = '.$latest_post_timestamp.' WHERE `col_shorthand` = "'.$blog_id.'"');
    echo " $latest_post_timestamp<br>";
  } else {
    echo "(Columnist $blog_id Seems Inactive)<br>";
  }
}


/* IMPORTANT: DO THE SAME FOR COLUMNISTS */

?>