<?php 

/************************************************************************************************
*	This script handles the Cron Job for adding feeds for Lebanese Blogs into the database    		*
************************************************************************************************/ 

include_once("init.php");
require_once("feeds_fetcher_functions.php");
require_once("classes/simplepie.php");


// Define CLI Interface Elements
$line_length = 70;
$hr  = "\n".str_repeat('-', $line_length)."\n";
$dhr = "\n".str_repeat('=', $line_length)."\n";

// Produce header
echo $dhr;
echo 'Work began: '.date('d M Y , H:i:s');
$robot = shell_exec('whoami');
echo "\nRobot: $robot";
echo "\nPHP in use: ", shell_exec('which php');
echo "\nAbsolute Path: ". ABSPATH;
echo $dhr;

// get all blogs
DB::GetInstance()->query('SELECT `blog_id`, `blog_rss_feed` , `blog_active` FROM `blogs`');
$blogs = DB::GetInstance()->results();

// loop through blogs
foreach ($blogs as $blog) 
{	
	if ($blog->blog_active == 1) // ignore blog if it's marked as inactive
	{ 
		$workingfeed = $blog->blog_rss_feed;
		echo "\n\nNow fetching posts from feed: ",$workingfeed;
		fetchIndividualFeed($blog, $workingfeed);
	}
}

echo $dhr.'Feeds Work Ended: '.date('d M Y , H:i:s').$dhr;


function fetchIndividualFeed($blog, $workingfeed)
{
	global $hr, $dhr;
	$maxitems = 0;

	$sp_feed = new SimplePie(); // We'll process this feed with all of the default options.
	$sp_feed->set_feed_url($workingfeed); // Set which feed to process.
	$sp_feed->set_useragent('Lebanese Blogs/3.0 (+http://www.lebaneseblogs.com)');
	$sp_feed->strip_htmltags(false);
	$sp_feed->enable_cache(false);
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
			
			// remove https or http from beginning of link to avoid duplicate posts;
			$pieces = parse_url($blog_post_link);
			array_shift($pieces);
			if(!empty($pieces['query'])){
				$pieces['query'] = '?'.$pieces['query'];
			}
			if(!empty($pieces['fragment'])){
					$pieces['fragment'] = '#'.$pieces['fragment'];
			}
			$reconstructed_url = implode('', $pieces);

			// get blogid , example: beirutspring.com -> beirutspring
			$domain = $blog->blog_id;

			// get timestamp
			$blog_post_timestamp =  strtotime($item->get_date()); // get post's timestamp;	

			// get title
			$blog_post_title = clean_up($item->get_title(), 120);

			// check if this post is in the database
			echo "\n---> checking: $reconstructed_url";
			$urlExists = DB::GetInstance()->query('SELECT post_id FROM posts WHERE post_url LIKE "%'.$reconstructed_url.'"')->results();
			$nameExists = DB::GetInstance()->query('SELECT post_id FROM posts WHERE post_title ="' . $blog_post_title . '" AND blog_id ="' . $domain . '"')->results();

			if ((count($urlExists) > 0) || (count($nameExists) > 0)) { // post exists in database
				echo '  [ x Post already in Database ] ';	
				continue;
			} else { // ok, new post, insert in database
				echo "\n-------------> New POST";
				echo "\n-------------> Title: $blog_post_title";
				$temp_content = $item->get_content();
				$blog_post_content = html_entity_decode($temp_content, ENT_COMPAT, 'utf-8'); // for arabic
				if ($blog_post_image = dig_suitable_image($blog_post_content, $blog_post_link)){
					echo "\n-------------> Image: $blog_post_image";
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

				  DB::GetInstance()->insert('posts', array(
					'post_url'			=>	$blog_post_link,
					'post_title'		=>	$blog_post_title,
					'post_image'		=>	$blog_post_image,
					'post_excerpt'		=>	$blog_post_excerpt,
					'blog_id'			=>	$domain,
					'post_timestamp'	=>	$blog_post_timestamp,
					'post_content'		=>	$blog_post_content,
					'post_image_width'	=>	$blog_post_image_width,
					'post_image_height'	=>	$blog_post_image_height,
					'post_visits'	=>	0
					));

				// cache images
				if ($blog_post_image_width > 0) { // post has images
					if ($image = new Imagick($blog_post_image)){
					$image = $image->flattenImages();
					$image->setFormat('JPEG');
					$image->thumbnailImage(300,0);
					$outFile = ABSPATH.'img/cache/'.$blog_post_timestamp.'_'.$domain.'.jpg';//.Lb_functions::get_image_format($blog_post_image);
					$image->writeImage($outFile);
					};	
				}

				if (DB::GetInstance()->count() > 0) 
				{
					echo " [ √ Post Added ]\n";
				} else {
					echo "\n [XXX] There was an error. Couldn't Add post.. \n";
				}
			}
		}
	}


/* Dumpster. Delete when all is ok

			//first, get the path
			$post_path_parts = parse_url($blog_post_link);
			$post_path = $post_path_parts['path']; // result example: "/a-new-10000-lebanese-lira-bill/"
			if (@$post_path_parts['query']) { // has a query (example: ?pagewanted=.....)
				$post_path = $post_path.'?'.$post_path_parts['query'];
			}
			if (@$post_path_parts['fragment']) { // has a fragment (example: #utm=.....)
				$post_path = $post_path.'#'.$post_path_parts['fragment'];
			}
 			$exists = DB::GetInstance()->query('SELECT `post_id` FROM `posts` WHERE `blog_id` = "' . $domain . '" AND `post_url` LIKE "%' . $post_path  . '" ');



*/

?>
