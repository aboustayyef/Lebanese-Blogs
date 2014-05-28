<?php 
header("Content-Type:text/xml");
require_once('../init.php');
$numberOfFeedItems = 25;
$channel = @$_GET['channel'];
$channel = empty($channel)? 'all': $channel;
$data = Posts::get_latest_posts($numberOfFeedItems, $channel);
$now = new dateTime();
$pubDate = $now->format('D, d M Y H:i:s O');
$feedLocation = WEBPATH.'feed';
// using RSS 2.0 using specs at http://cyber.law.harvard.edu/rss/rss.html
$feed_header = <<<feedheader
<?xml version="1.0"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Lebanese Blogs Feed</title>
    <link>http://lebaneseblogs.com/</link>
    <description>Lebanese Blogs Feed</description>
    <language>en-us</language>
    <lastBuildDate>$pubDate </lastBuildDate>
    <generator>Lebanese Blogs</generator>
    <atom:link href="$feedLocation" rel="self" type="application/rss+xml" />
feedheader;
$feed_footer = <<<feedfooter
  </channel>
</rss>
feedfooter;
$feed_items = "";

foreach ($data as $key => $feed_item) {

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
    $post_content = '<p><img src ="' . $the_image . '" width ="278" height ="' . $image_height . '"></p>'."\n";
  }

  $post_content.= $feed_item->post_excerpt;

  $item_pub_date = new dateTime();
  $item_pub_date->setTimestamp($feed_item->post_timestamp);
  $item_pub_date = $item_pub_date->format('D, d M Y H:i:s O'); 
  $_feed_item_parts = "\n<item>";
  $_feed_item_parts .= "\n<title>".htmlspecialchars($feed_item->blog_name).": ".htmlspecialchars($feed_item->post_title)."</title>";
  $_feed_item_parts .= "\n<link>".htmlspecialchars($feed_item->post_url.'?utm_source=LebaneseBlogs&utm_medium=RSS&utm_campaign=Lb_RSS')."</link>";
  $_feed_item_parts .= "\n<description>".htmlspecialchars($post_content)."</description>";
  $_feed_item_parts .= "\n<pubDate>$item_pub_date </pubDate>";
  $_feed_item_parts .= "\n<guid>".htmlspecialchars($feed_item->post_url)."_$feed_item->post_id</guid>";
  $_feed_item_parts .="\n</item>";
  $feed_items .= $_feed_item_parts;
}
$complete_feed = $feed_header . $feed_items . $feed_footer;
echo $complete_feed;