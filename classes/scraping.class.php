<?php 

/**
* This is a set of utilities to scrape content from websites
*/

require_once(ABSPATH.'classes/simple_html_dom.php'); 

class Scraper
{
  protected $_url; // string
  protected $_html; //object
  protected $_article_container; // object

  function __construct($url)
  {
    $this->_url = $url;
    $this->_html = @file_get_html($this->_url);
   
    if (is_object($this->_html)) { // if we successfully extracted html     
      $this->_article_container = $this->getArticleBody(); // try to extract article from html
      if ($this->_article_container) { // If we succesfully extracted article
        # everything ok . Construction successful.
      }else{
        die('Article couldn\'t be extracted. improve this script by adding the article container for this kind of websites');
      }
    } else {
      die('Could not pull HTML');
    }
  }

  public function getImagesFromHtml($minimumWidth = 0, $returnFirstEligibleImage = false){

    $eligibleImages = array();
    foreach ($this->_article_container->find('img') as $e)
    {
      $img = $e->src;
      $img = self::cleanUpImageUrl($img);
      if ($minimumWidth > 0)
      { // if we have set a minimum Width required
        list($width, $height, $type, $attr) = getimagesize("$img");
        if ($width >= $minimumWidth)
        {
          array_push($eligibleImages, $img);
        }
      }else{
        // we have not set a minimum width
        array_push($eligibleImages, $img);
      }
      if ($returnFirstEligibleImage) 
      {
        return $eligibleImages;
      } // else keep looping then return the images
    }
    return $eligibleImages;
  }

  private function getArticleBody(){

    // add to these as you find more
    $knownContainers = array('.post', '.entry-content', '.post-content', '#content', '.content','.article' );
    
    foreach ($knownContainers as $key => $container) 
    {
      $test =  @$this->_html->find($container,0);
      if (is_object($test))
      {
        return $test;
      }
      // if all options are exhausted
        return null;
    }
  }

  public static function getYoutubeImageFromHtml($htmlContent){
    /*******************************************************************
    * Tries to get youtube content's preview
    *
    ********************************************************************/ 
    {
      preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $htmlContent, $matches);
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
  }

  public static function getVimeoImageFromHtml($htmlContent){
  /*******************************************************************
  * Tries to get Vimeo content's preview
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

  public static function cleanUpImageUrl($imgUrl){

    //clean up string to remove parameters like ?w=xxx&h=sss
    $image_parts = explode("?",$imgUrl);
    $imgUrl = $image_parts[0];

    // check if this is a facebook image from a facebook blog. Replace _s.jpg (small images) with _n.jpg (normal)

    if (strpos($imgUrl, 'fbcdn')>0){  // this is a facebook hosted image. 
      $imgUrl = preg_replace('/_s.jpg$/', "_n.jpg", $imgUrl); // replace image with larger one.
    }

    //remove automatic resizing applied by wordpress and go straight to original image
    // for example, a file that ends with image-150x250.jpg becomes image.jpg 

    $adjusted = preg_replace('/-[0-9]{3}x[0-9]{3}\.jpg$/', ".jpg", $imgUrl);
    $imgUrl = $adjusted;

    return $imgUrl;
  }

}

?>