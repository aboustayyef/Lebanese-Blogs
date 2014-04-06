<?php 

/************************************************************************************************
*	This script handles the Cron Job for adding feeds for Lebanese Blogs into the database 		*
*																								*
************************************************************************************************/ 

include_once("init.php");
require_once("feeds_fetcher_functions.php");
require_once("classes/simplepie.php");
// horizontal line
$line_length = 70;
$hr  = "\n".str_repeat('-', $line_length)."\n";
$dhr = "\n".str_repeat('=', $line_length)."\n";

echo $dhr;
echo 'work began: '.date('d M Y , H:i:s');
$robot = shell_exec('whoami');
echo "\n Robot: $robot";

echo ABSPATH;

echo $dhr;


$maxitems = 0;

$connection = DB::GetInstance();

// get all feeds

if (isset($argv[1])) {
	echo $dhr;
	echo 'fetching script '.$argv[1];
	echo $dhr;
	$feeds = array();
	$feeds[0] = new stdClass();
	$feeds[0]->blog_active = 1;
	$feeds[0]->blog_rss_feed = $argv[1]; 
	$feeds[0]->blog_id = 'testing';
} else {
	$connection->query('SELECT `blog_id`, `blog_rss_feed` , `blog_active` FROM `blogs`');
	$feeds = $connection->results();
}

// loop through feeds
foreach ($feeds as $feed) 
{	
	if ($feed->blog_active == 1) { // ignore blog if it's marked as inactive
	$workingfeed = $feed->blog_rss_feed;
	echo "\n",$workingfeed;

	$sp_feed = new SimplePie(); // We'll process this feed with all of the default options.
	$sp_feed->enable_cache(false);
	$sp_feed->set_feed_url($workingfeed); // Set which feed to process.
	//$sp_feed->set_cache_location(ABSPATH.'cache');
	//$sp_feed->set_cache_duration(600); // Set cache to 10 mins
	$sp_feed->strip_htmltags(false);
	$sp_feed->init(); // Run SimplePie. 
	$sp_feed->handle_content_type(); // This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).

	// loop through feed items
	foreach($sp_feed->get_items(0, $maxitems) as $key => $item) 
		{
			// post link
			$blog_post_link = $item->get_permalink();

			//resolves feedburner proxies
			$canonical_resource = $item->get_item_tags("http://rssnamespace.org/feedburner/ext/1.0",'origLink');
			if (isset($canonical_resource[0]['data'])) { 
				$blog_post_link = $canonical_resource[0]['data'];
			}
			$blog_post_link = urldecode($blog_post_link);
			

			// get blogid , example: beirutspring.com -> beirutspring
			$domain = $feed->blog_id;

			// get timestamp
			$blog_post_timestamp =  strtotime($item->get_date()); // get post's timestamp;	

			// check if this post is in the database
			
			//first, get the path
			$post_path_parts = parse_url($blog_post_link);
			$post_path = $post_path_parts['path']; // result example: "/a-new-10000-lebanese-lira-bill/"
			if (@$post_path_parts['query']) { // has a query (example: ?pagewanted=.....)
				$post_path = $post_path.'?'.$post_path_parts['query'];
			}
			if (@$post_path_parts['fragment']) { // has a fragment (example: #utm=.....)
				$post_path = $post_path.'#'.$post_path_parts['fragment'];
			}
			//echo "\n",'The post path is: '.$post_path."\n";
			// now check if this particular post is in database. we use a combination of $domain and $post_path and timestamp
			$exists = $connection->query('SELECT `post_id` FROM `posts` WHERE `blog_id` = "' . $domain . '" AND `post_url` LIKE "%' . $post_path  . '" ');

			if ($exists->count() > 0) { // post exists in database
				if($key == 0){ // The first post in the feed. no need to enter it
					echo '  [x Nothing to add ] ';
				}else{
					echo "\n".$blog_post_link.' [ x ] post is already in the database',"\n";
				}	
				
				break;
			/*} else if ((time()-$blog_post_timestamp) > (3*30*24*60*60)) { //post is more than 3 month old ;
				Will ignore the time limit for now. See if sustainable.
				echo '<span style ="color:blue">post is older than three months</span><br>',"\n";
				continue;*/
			} else { // ok, new post, insert in database

				$blog_post_title = clean_up($item->get_title(), 120);
				$temp_content = $item->get_content();
				$blog_post_content = html_entity_decode($temp_content, ENT_COMPAT, 'utf-8'); // for arabic
				if ($blog_post_image = dig_suitable_image($blog_post_content, $blog_post_link)){
					//proceed
				}
				$blog_post_excerpt = get_blog_post_excerpt($blog_post_content, 120);

				// added image dimensions for lazy loading

				if ($blog_post_image) {
					list($width, $height, $type, $attr) = getimagesize($blog_post_image);
					$blog_post_image_width = $width;
					$blog_post_image_height = $height;
				}	else {
					$blog_post_image_width = 0;
					$blog_post_image_height = 0;
				}

				  $connection->insert('posts', array(
					'post_url'			=>	$blog_post_link,
					'post_title'		=>	$blog_post_title,
					'post_image'		=>	$blog_post_image,
					'post_excerpt'		=>	$blog_post_excerpt,
					'blog_id'			=>	$domain,
					'post_timestamp'	=>	$blog_post_timestamp,
					'post_content'		=>	$blog_post_content,
					'post_image_width'	=>	$blog_post_image_width,
					'post_image_height'	=>	$blog_post_image_height
					));
				
				// cache images
				if ($blog_post_image_width > 0) { // post has images
					$outFile = ABSPATH.'img/cache/'.$blog_post_timestamp.'_'.$domain.'.'.Lb_functions::get_image_format($blog_post_image);
					$image = new Imagick($blog_post_image);
					$image->thumbnailImage(300,0);
					$image->writeImage($outFile);
				}

				if ($connection->count() > 0) {
					if($key == 0){ // The first post in the feed. no need to enter it
						echo $hr.$blog_post_link; echo "   [ √ POST ADDED ] \n";
					}else{
						echo $blog_post_link; echo "   [ √ POST ADDED ] \n";
					}
				}
			}
		}
	}
}

echo $dhr.'Feeds Work Ended: '.date('d M Y , H:i:s').$dhr;

?>
