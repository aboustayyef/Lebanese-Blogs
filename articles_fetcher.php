<?php 

require_once('init.php');

$columnists = DB::getInstance();
if (isset($argv[1])) {
	echo $argv[1];
	$columnists = $columnists->get('columnists', array('col_id','=',$argv[1]))->results();
} else {
	$columnists = $columnists->getAll('columnists')->results();
}

include_once('media_definitions.php');

// horizontal line
$line_length = 70;
$hr  = "\n".str_repeat('-', $line_length)."\n";
$dhr = "\n".str_repeat('=', $line_length)."\n";

// Go through authors
foreach ($columnists as $key => $columnist) {

	$author_media_definition = $columnist->col_media_source_shorthand;
	$author_media_definition = $$author_media_definition;
	echo $hr;
	echo 'Getting author '.$columnist->col_name.' at '.$columnist->col_media_source;
	echo $hr;
	// Go through articles
	$counter = 0;
	while ($counter >= 0) {
		// an assignment and a conditional. If succesful assignment.. etc
		if ($article = GetArticles::getArticle($author_media_definition, $columnist->col_home_page, $counter)) {
			
			$url = $article['link'];
			if (Posts::postExists($url)) {
				echo 'Post "'.$article['title'].'" already exists';
				echo "\n";

			} else {
				// new post!
				if ((isset($article['image_details']['source'])) && (!empty($article['image_details']['source'])) ) {
					$image_source = $article['image_details']['source'];
					$image_width = $article['image_details']['width'];
					$image_height = $article['image_details']['height'];
				} else {
					$image_source = NULL;
					$image_width = 0;
					$image_height = 0;
				}

				DB::getInstance()->insert('posts', array(
					'post_url'	=>	$article['link'],
					'post_title'	=>	$article['title'],
					'post_image'	=>	$image_source,
					'post_excerpt'	=> 	$article['excerpt'],
					'blog_id'		=>	$columnist->col_shorthand,

					// if we're doing a reset, uncomment line below 
					 'post_timestamp' => $article['timestamp'],
					
					// For update mode, uncomment line below to use the current time for timestamp.
					//'post_timestamp'	=> time(),
					'post_content'	=> $article['content'],
					'post_image_height'	=> $image_height,
					'post_image_width'	=>	$image_width,
				));

				echo 'added: "'.$article['title'].'"';
				echo "\n";

				// If you are debugging, uncomment the next line so that just one article of each columnist is inserted
				// break;
			}
			$counter++;
		} else {

			echo $hr.'All Articles Available are displayed'.$hr ;
			break;
		}
	}

}	

?>
