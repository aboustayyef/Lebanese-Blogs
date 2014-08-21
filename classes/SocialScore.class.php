<?php 

/**
* Social Score Class, inspired by: http://99webtools.com/script-to-get-shared-count.php
* Usage:
*   $score = new socialScore("http://the.url");
*   $twitterScore = $score->twitterScore();
*   $facebookScore = $score->facebookScore();
*/
class SocialScore
{
  protected $url;
  protected $timeout;

  protected $twitterScore;    // how many times url was retweeted / shared
  protected $facebookScore;   // how many times url was shared, liked and commented on facebook;
  
  function __construct($url,$timeout=10)
  {
    if (empty($url)) {
      die('url should be set in SocialScore class');
    }
    $this->url = $url;
    $this->timeout = $timeout;
  }

  public function getTwitterScore()
  {
    $this->twitterScore = $this->get_tweets();
    return $this->twitterScore;
  }

  public function getFacebookScore()
  {
    $this->facebookScore = $this->get_fb();
    return $this->facebookScore;
  }



  // Helper functions (the meat)

  private function get_tweets() 
  {
    try {
      $json_string = $this->file_get_contents_curl('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->url);
      $json = json_decode($json_string, true);
      return isset($json['count'])?intval($json['count']):0;
    } catch (Exception $e) {
      echo "Could not get twitter count of URL $this->url\n";
    }
  }

  private function get_fb() 
  {
    try {
      $json_string = $this->file_get_contents_curl('http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$this->url);
      $json = json_decode($json_string, true);
      return isset($json[0]['total_count'])?intval($json[0]['total_count']):0; 
    } catch (Exception $e) {
      echo "Could not get Facebook count of URL $this->url\n";
    }
  }

  private function file_get_contents_curl($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
    $cont = curl_exec($ch);
    if(curl_error($ch))
    {
      die(curl_error($ch));
    }
    return $cont;
  }

}

?>