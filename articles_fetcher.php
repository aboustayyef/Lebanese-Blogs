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

				// when a reset is being done, use line 2. Else use line 1;
				$timeStampToUse = time(); // line 1
				// $timeStampToUse = $article['timestamp']


				DB::getInstance()->insert('posts', array(
					'post_url'	=>	$article['link'],
					'post_title'	=>	$article['title'],
					'post_image'	=>	$image_source,
					'post_excerpt'	=> 	$article['excerpt'],
					'blog_id'		=>	$columnist->col_shorthand,
					'post_timestamp'	=> $timeStampToUse,
					'post_content'	=> $article['content'],
					'post_image_height'	=> $image_height,
					'post_image_width'	=>	$image_width,
				));


				// cache images
				if ($image_width > 0) { // post has images
					$image = new Imagick($image_source);
					$image = $image->flattenImages();
					$image->setFormat('JPEG');
					$image->thumbnailImage(300,0);
					$outFile = ABSPATH.'img/cache/'.$timeStampToUse.'_'.$columnist->col_shorthand.'.jpg';//.Lb_functions::get_image_format($image_source);
					$image->writeImage($outFile);
				}

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
