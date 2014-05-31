<?php
// get the channel
$channel = @$_GET['channel'];

// set the head type of this document
header("Content-Type:text/xml");

// prerequisits
require_once('../init.php');

// RSS config
$numberOfFeedItems = 25;

// Get Channel's Titles and description
if (empty($channel)) {
  $channel = 'all';
  $feed_channel_title = 'Lebanese Blogs Feed';
  $feed_channel_description = htmlentities('This is an automated RSS feed of the lebanese blogs featured on lebaneseblogs.com');
} else {
  $channelName = htmlentities(Channels::resolveDescription($channel));
  if (empty($channelName)) {
    die('This channel does not exist');
  }
  $feed_channel_title = $channelName.' at Lebanese Blogs';
  $feed_channel_description = htmlentities('This is an automated RSS feed of lebanese '. $channelName .' blogs as featured on lebaneseblogs.com');
}

// get the latest posts
$data = Posts::get_latest_posts($numberOfFeedItems, $channel);

// Prepare some header variables
$now = new dateTime();
$pubDate = $now->format('D, d M Y H:i:s O');
$feedLocation = WEBPATH.'feed';

// Prepare header. Using RSS 2.0 with specs published at http://cyber.law.harvard.edu/rss/rss.html
$feed_header = <<<feedheader
<?xml version="1.0"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>$feed_channel_title</title>
    <link>http://lebaneseblogs.com/</link>
    <description>$feed_channel_description</description>
    <language>en-us</language>
    <lastBuildDate>$pubDate </lastBuildDate>
    <generator>Lebanese Blogs</generator>
    <atom:link href="$feedLocation" rel="self" type="application/rss+xml" />
feedheader;
$feed_footer = <<<feedfooter
  </channel>
</rss>
feedfooter;

// Prepare feeds
$feed_items = "";

foreach ($data as $key => $feed_item) {

  $item_url = WEBPATH."r.php?r=".urlencode($feed_item->post_url); // use the redirector to count for countdown clicks
  $post_content = ""; 
  /*
  Find out the image and if it's in cache
  */
  if (isset($feed_item->post_image) && ($feed_item->post_image_width > 0) && ($feed_item->post_image_height>0)) {
    $image_width = 278;
    $image_height = intval(($image_width / $feed_item->post_image_width)*$feed_item->post_image_height);
    $the_image = $feed_item->post_image;

    // use image cache if exists.
    $image_cache = IMGCACHE_BASE.$feed_item->post_timestamp.'_'.$feed_item->blog_id.'.'.Lb_functions::get_image_format($the_image);
    $image_file = ABSPATH.'img/cache/'.$feed_item->post_timestamp.'_'.$feed_item->blog_id.'.'.Lb_functions::get_image_format($the_image);
    if (file_exists($image_file)) {
      $the_image = $image_cache;
    }
    $post_content = '<p><a href ="'.$item_url.'"><img src ="' . $the_image . '" width ="278" height ="' . $image_height . '"></a></p>'."\n";
  }

  $post_content.= $feed_item->post_excerpt;
  $post_content.= '<p><a href ="' . $item_url . '">Go to the post &rarr;</a></p>';

  
  $lebaneseBlogsTools = "<h4>Lebanese Blogs Tools</h4>";
  $lebaneseBlogsTools .= "<ul>";
 // $lebaneseBlogsTools .= '<li>Go to this author\'s <a href ="' . $feed_item->blog_url . '">home</a></li>';
  if (!empty($feed_item->blog_author_twitter_username)) {
    $lebaneseBlogsTools .= '<li>Follow the author of this post on twitter: <a href ="http://twitter.com/' . $feed_item->blog_author_twitter_username . '">@' . $feed_item->blog_author_twitter_username . '</a></li>';
  }
  $lebaneseBlogsTools .= '<li>See the list of this author\'s <a href ="' . WEBPATH.$feed_item->blog_id . '">latest posts</a></li>';

  $lebaneseBlogsTools .= "</ul>"; 

  $post_content .= $lebaneseBlogsTools;

  $item_pub_date = new dateTime();
  $item_pub_date->setTimestamp($feed_item->post_timestamp);
  $item_pub_date = $item_pub_date->format('D, d M Y H:i:s O'); 
  $_feed_item_parts = "\n<item>";
  $_feed_item_parts .= "\n<title>".htmlspecialchars($feed_item->blog_name).": ".htmlspecialchars($feed_item->post_title)."</title>";
  $_feed_item_parts .= "\n<link>".$item_url."</link>";
  $_feed_item_parts .= "\n<description>".htmlspecialchars($post_content)."</description>";
  $_feed_item_parts .= "\n<pubDate>$item_pub_date </pubDate>";
  $_feed_item_parts .= "\n<guid>".$item_url."</guid>";
  $_feed_item_parts .="\n</item>";
  $feed_items .= $_feed_item_parts;
}
$complete_feed = $feed_header . $feed_items . $feed_footer;
echo $complete_feed;