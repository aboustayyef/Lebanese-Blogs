<?php 

/**
*   All functions used by feeds_fetcher.php
*   will be here.
*/ 

require_once(ABSPATH.'classes/simple_html_dom.php'); 
require_once(ABSPATH.'classes/scraping.class.php');

function get_domain($theurl)
/*****************************************
*
*	Will extract the domain from the url
*	Example input: "http://www.xyz.com"
*	Example output: "xyz" (string)
*
*******************************************/
{
	$parse = parse_url($theurl);
	$host = $parse['host'];
	$explode = explode(".", $host);
	$domain = $explode[0];
	if ($domain == 'www' || $domain == 'blog') {
		$domain = $explode[1];
	} else {
		$domain = $domain ;
	}

	if ($domain == 'facebook') { // facebook domains should return their facebook ID instead
		$theurl =  htmlspecialchars_decode($theurl);
		$parse = parse_url($theurl, PHP_URL_QUERY);
		parse_str($parse, $params);
		$temp = explode('.',$params['set']);
		$domain = $temp[3];
	}

	return $domain;
}

function clean_up($original_string, $length)
/*****************************************
*
*	Will clean up and truncate a string
*	mostly used for post excerpts
*
*******************************************/
{
	$original_string = html_entity_decode($original_string, ENT_COMPAT, 'utf-8');
	$original_string = strip_tags($original_string);
	str_replace(array("\r", "\r\n", "\n"), '', $original_string);
	$original_string = trim($original_string);
	if (strlen($original_string) > $length ) {
		$new_string = mb_substr($original_string,0,$length)."...";
	} else {
		$new_string = $original_string;
	}
	
	return $new_string ;
}

function dig_suitable_image($content, $link = NULL) {


/*******************************************************************
*	This functions uses the PHP DOM Parser to extract images
*
********************************************************************/
$firstImage ="";
$html = str_get_html($content);
foreach ($html->find('img') as $img) 
	{
	    $image = $img->getAttribute('src');

		//clean up string to remove parameters like ?w=xxx&h=sss
		$image_parts = explode("?",$image);
		$image_without_parameters = $image_parts[0];
		$image = $image_without_parameters;


		// check if this is a facebook image from a facebook blog. Replace _s.jpg (small images) with _n.jpg (normal)

		if (strpos($image, 'fbcdn')>0){  // this is a facebook hosted image. 
			$image = preg_replace('/_s.jpg$/', "_n.jpg", $image); // replace image with larger one.
		}


		//remove automatic resizing applied by wordpress and go straight to original image
		// for example, a file that ends with image-150x250.jpg becomes image.jpg 

		$adjusted = preg_replace('/-[0-9]{3}x[0-9]{3}\.jpg$/', ".jpg", $image);
		$image = $adjusted;

    	list($width, $height, $type, $attr) = getimagesize("$image");
    	if ($width>299) //only return images 300 px large or wider
    	{ 
    		$firstImage = $image;
    		break;
    	}
    }
if ($firstImage) 
	{
		return $firstImage;
	}
elseif (get_image_from_post($link)) {
	return get_image_from_post($link);
}
elseif (get_youtube_thumb($content)) 
	{
		return get_youtube_thumb($content);
	} 
elseif (get_vimeo_thumb($content))
	{
		return get_vimeo_thumb($content);
	}
else 
	{
	return NULL;
	}
}

function get_vimeo_thumb($content){
/*******************************************************************
*	Tries to get Vimeo content's preview
*
********************************************************************/ 
	preg_match_all("#(?:https?://)?(?:\w+\.)?vimeo.com/(?:video/|moogaloop\.swf\?clip_id=)(\w+)#", $content, $results);
	if (isset($results[1][0])){
		$imgid = $results[1][0];
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
		return $hash[0]['thumbnail_large']; 
	} else {
		return false;
	};
}

function get_image_from_post($link){
	echo "\n ===== Trying to scrape out an image from link  $link ====\n";
	$imagesObject = new Scraper($link);
	if ($imagesObject->allOk()) 
	{
		if ($images = $imagesObject->getImagesFromLink(300,true)) {
			return $images[0];
		}else{
			return false;
		}
	}else{
		return false;
	}
}


function get_youtube_thumb($content)
/*******************************************************************
*	Tries to get youtube content's preview
*
********************************************************************/ 
{
	preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $content, $matches);
	if(isset($matches[2]) && $matches[2] != '')
	{
 		$YoutubeCode = $matches[2];
 		return 'http://img.youtube.com/vi/'.$YoutubeCode.'/0.jpg';
	} 
	else
	{
		return NULL;
	}
}

function get_blog_post_excerpt($content, $length) 
/*******************************************************************
*	Gets blog post excerpt with a length caracter
*
********************************************************************/ 
{

	$sample_paragraph = clean_up($content, $length);
	return $sample_paragraph ;

}

function has_canonical_url ($resource) { // will either return the canonical url or "false"
	$canonical = "no";
	for ($i=0; $i < 4 ; $i++) { 
		if (@$resource[$i]['attribs']['']['rel'] == "canonical") {
			$canonical = "yes";
			return $resource[$i]['attribs']['']['href'];
		}
	}
	if ($canonical ="no") {
		return false;
	}
}


?>