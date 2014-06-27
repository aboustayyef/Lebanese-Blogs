<?php 

/**
* This class is article getter. It extracts a list of articles from an author page on the web.
*/
//header('Content-Type: text/html; charset=utf-8');
require_once(ABSPATH.'classes/simple_html_dom.php');

class GetArticles {
	function __construct(){
		# Nothing
	}

	public static function getList($media_source, $author_page, $howmany){

		switch ($media_source['method']) {
			case '1':
				$result = self::getList_method1($media_source, $author_page, $howmany);
				break;
			case '2':
				$result = self::getList_method2($media_source, $author_page, $howmany);
				break;			
			default:
				die('a method needs to be specified');
				break;
		}
		return $result;


	}
	public static function getArticle($media_source, $author_page, $whichArticle){
		switch ($media_source['method']) {
			case '1':
				$result = self::getArticle_method1($media_source, $author_page, $whichArticle);
				break;
			case '2':
				$result = self::getArticle_method2($media_source, $author_page, $whichArticle);
				break;			
			default:
				die('a method needs to be specified');
				break;
		}
		return $result;
	}

	public static function getArticle_method1($media_source, $author_page, $whichArticle){
		$html = file_get_html($author_page);
		if (method_exists($html,"find")) {   // then check if the html element exists to avoid trying to parse non-html
		    if ($html->find('html')) {  // and only then start searching (and manipulating) the dom 
		     	$article = array();
				$allElements = $html->find($media_source['articles_wrapper']);
				if ($element = $allElements[$whichArticle]) { // if this assignment does not generate an error
					$article['title'] = trim(html_entity_decode($element->find($media_source['title'][0],$media_source['title'][1])->plaintext));			
					$article['link'] = $media_source['link_prefix'].trim(html_entity_decode($element->find($media_source['link'][0],$media_source['link'][1])->href));
					if (!empty($media_source['timestamp'][0])) {
						// shop off superflous part of timestamp string
						$date_description = html_entity_decode($element->find($media_source['timestamp'][0],$media_source['timestamp'][1])->plaintext);
						if (isset($media_source['timestamp'][2])) {
							$adjusted_date_description =substr($date_description,$media_source['timestamp'][2], $media_source['timestamp'][2]);
							$thedate = new DateTime(trim($adjusted_date_description));

						}else{
							$thedate = new DateTime(trim($date_description));
						}
						$article['timestamp'] = ($thedate->GetTimeStamp())+ 25200 + rand(60,3600); 
					}
					
					/*Since Timestamps are taken from dates, not exact times (ex: 12/November/2013), the resulting timestamp 
					will be at midnight, and columnists would be relegated to midnight posts. To remedy this we add 6 hours so that they're published at a good early time, 
					We also add a random amount between 1 minutes and 60 minutes so that columnists don't all appear at the exact same time */
					$article['excerpt'] = trim(html_entity_decode($element->find($media_source['excerpt'][0],$media_source['excerpt'][1])->plaintext));
					$article['image_details'] = self::getImageFromURL($article['link'], $media_source['article_body'], $media_source['img_prefix']);
					$article["content"] = self::getContentFromURL($article["link"], $media_source['content_body']);
					print_r($article);
					return $article;
				} else {
					// $whichArticle is probably larger than amount of articles available
					return false ;
				}	
		     } else {
		     	echo "\n [ERROR] Could not find html \n";
		     }
		} else {
			echo "\n [ERROR] Method could not be found. \n";
		}
		
	}

	public static function getList_method1($media_source, $author_page, $howmany=null){
		$counter = 0;
		$html = file_get_html($author_page);
		$articles = array();
		foreach ($html->find($media_source['articles_wrapper']) as $key => $element) {
			
			$articles[$key]['title'] = trim(html_entity_decode($element->find($media_source['title'][0],$media_source['title'][1])->plaintext));
			
			$articles[$key]['link'] = $media_source['link_prefix'].trim(html_entity_decode($element->find($media_source['link'][0],$media_source['link'][1])->href));

			$thedate = new DateTime(trim(html_entity_decode($element->find($media_source['timestamp'][0],$media_source['timestamp'][1])->plaintext)));
			
			/*Since Timestamps are taken from dates, not exact times (ex: 12/November/2013), the resulting timestamp 
			will be at midnight, and columnists would be relegated to midnight posts. To remedy this we add 6 hours so that they're published at a good early time, 
			We also add a random amount between 1 minutes and 60 minutes so that columnists don't all appear at the exact same time */
			$articles[$key]['timestamp'] = ($thedate->GetTimeStamp())+ 25200 + rand(60,3600); 

			$articles[$key]['excerpt'] = trim(html_entity_decode($element->find($media_source['excerpt'][0],$media_source['excerpt'][1])->plaintext));

			$articles[$key]['image'] = self::getImageFromURL($articles[$key]['link'], $media_source['article_body'], $media_source['img_prefix']);
			$articles[$key]["content"] = self::getContentFromURL($articles[$key]["link"], $media_source['content_body']);

			$counter++;
			if (isset($howmany)) {
				if ($counter > $howmany-1) {
					break;
				}
			}
		
		}

		return $articles;
	}

	public static function getArticle_method2($media_source, $author_page, $whichArticle){
		
		$html = file_get_html($author_page);
		$article = array();

		// container is the main envelop of article units. Extract the first item.
		$container = $html->find($media_source['articles_wrapper'],0);

		$allTitles = $container->find($media_source['title']);
		if ($article['title'] = trim(html_entity_decode($allTitles[$whichArticle]->plaintext))) { // produces result
			# nothing. Assignment already made
		} else {
			// nothing to return. $WhichArticle could be too big.
			return false;
		}

		$allLinks = $container->find($media_source['link']);
		$article['link'] = $media_source['link_prefix'].$allLinks[$whichArticle]->href;
		$article['image_details'] = self::getImageFromURL($article['link'], $media_source['article_body'], $media_source['img_prefix']);
		$article['content'] = self::getContentFromURL($article['link'], $media_source['content_body']);

		$allTimeStamps = $container->find($media_source['timestamp']);
		$thedate = new DateTime(trim(html_entity_decode($allTimeStamps[$whichArticle]->plaintext)));
		$article['timestamp']= $thedate->getTimestamp()+ 25200 + rand(60,3600);
		
		$allExcerpts = $container->find($media_source['excerpt']);
		$article['excerpt']= trim($allExcerpts[$whichArticle]->plaintext);
	
		return $article;
	}

	public static function getList_method2($media_source, $author_page, $howmany=null){
		
		$counter = 0;
		$html = file_get_html($author_page);
		$articles = array();

		// container is the main envelop of article units. Extract the first item.
		$container = $html->find($media_source['articles_wrapper'],0);

		$counter = 0;
		foreach ($container->find($media_source['title']) as $key => $element) {
			$articles[$key]["title"] = trim(html_entity_decode($element->plaintext));
			$counter++;
				if (isset($howmany)) {
					if ($counter > $howmany-1) {
						break;
					}
				}
		}

		$counter = 0;
		foreach ($container->find($media_source['link']) as $key => $element) {
				$articles[$key]["link"] = $media_source['link_prefix'].trim(html_entity_decode($element->href));
				$articles[$key]["image"] = self::getImageFromURL($articles[$key]["link"], $media_source['article_body'], $media_source['img_prefix']);
				$articles[$key]["content"] = self::getContentFromURL($articles[$key]["link"], $media_source['content_body']);
				$counter++;
				if (isset($howmany)) {
					if ($counter > $howmany-1) {
						break;
					}
				}
		}

		$counter = 0;
		foreach ($container->find($media_source['timestamp']) as $key => $element) {
			$thedate = new DateTime(trim(html_entity_decode($element->plaintext)));
			/*Since Timestamps are taken from dates, not exact times (ex: 12/November/2013), the resulting timestamp will be at 
			midnight, and columnists would be relegated to midnight posts. To remedy this we add 6 hours so that they're published at a good
			early time,	We also add a random amount between 1 minutes and 60 minutes so that columnists don't all appear at the exact same time */
			$articles[$key]['timestamp']= $thedate->getTimestamp()+ 25200 + rand(60,3600);
			$counter++;
			if (isset($howmany)) {
				if ($counter > $howmany-1) {
					break;
				}
			}
		}

		$counter = 0;
		foreach ($container->find($media_source['excerpt']) as $key => $element) {
			$articles[$key]['excerpt']= trim($element->plaintext);
			$counter++;
			if (isset($howmany)) {
				if ($counter > $howmany-1) {
					break;
				}
			}
		}
	
		return $articles;
	}



	private static function getImageFromURL($url, $article_path, $img_prefix){

	$html = file_get_html($url);
	
	// to avoid extracting logo, we only look in the article container, which is entered in the function's parameters
	if (method_exists($html,"find")) {
	     // then check if the html element exists to avoid trying to parse non-html
	     if ($html->find('html')) {
	            if ($article_container = $html->find($article_path,0)){
	            	if ($article_container->find('img')) {
						foreach ($article_container->find('img') as $key => $element) {
							list($width, $height, $type, $attr) = getimagesize($img_prefix.$element->src);
							if ($width>299){ //only return images 300 px large or wider
								$image_details = array(
									'source'	=>	$img_prefix.$element->src,
									'width'		=>	$width,
									'height'	=>	$height,
								);
								return $image_details;
							} 
						}
					}
				return null;
			}
	     } else {
	     	echo "\n [ERROR] Could not find html \n";
	     }
	} else {
		echo "\n [ERROR] Method could not be found. \n";
	}

	}

	private static function getContentFromURL($url, $article_path){
		$html = file_get_html($url);

		if ($html->find($article_path,0)) {
			$article_container = $html->find($article_path,0);
			$content = '';
			if ($article_container->find('p')) {
				foreach ($article_container->find('p') as $key => $element) {
					$content .= '<p>'.$element->plaintext.'</p>';
				}
				return $content;
			}
		}
	}
}

?>
