<?php 
	/* testing */
require_once('init.php');

$posts = Posts::get_latest_posts(250);

foreach ($posts as $key => $post) {
  $shares = $post->post_totalShares;
  $virality = 2+round(3 * sqrt($shares));
  if ($virality > 50) {
    $virality = 50;
  }
  $viralityColor = round((255/50)*$virality);

  echo '<span style ="color:rgba('.$viralityColor.',120,120,1)">';
  echo str_repeat('|', $virality);
  echo str_repeat('.', 50-$virality);
  echo '</span>';
  echo $post->post_title.' ';
  echo '<br>';
}

?>