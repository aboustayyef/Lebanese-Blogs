<?php 

/**
* This class adds Extra cards (items?) to the deck. Things like tips, etc..
*/
class Extras
{

  function __construct()
  {
    //nothing
  }

  // this function is the controller of the entire extras system
  // it allocates slots to different cards

  public static function control($itemNumber){

    // advertisement

    /*$ad_positions = array(1,2);
    $rand = rand(0,1);
    $ad_position = $ad_positions[$rand];
    if (isset($_SESSION['ad_position'])) {
      # nothing
    }else{
      $_SESSION['ad_position'] = $ad_position;
    }*/

    switch ($itemNumber) {
      case 0: 
        if ($_SESSION['pageWanted'] == 'browse') {
          self::topFive(5, 12, $_SESSION['currentChannel']);
          $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
        }
      break;      

/*    case $_SESSION['ad_position']:
        if ($_SESSION['pageWanted'] == 'browse') {
          self::drawAd();
          $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
        }
      break;
*/
      case 3:
        self::facebookLikes();
        $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
      break;

      case 6: 
        if ($_SESSION['pageWanted'] == 'browse') {
          self::featuredBloggers();
          $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
        }
      break;

      case 9: 
        self::tip(0);
      break;

      case 12: 
        self::tip(1);
      break;

      case 15: 
        self::tip(2);
      break;
      
      case 18: 
        self::tip(3);
      break;

      default:
        # nothing for now
      break;
    }
  }

  public static function tip($whichtip){
    /* Uncomment if you want experts to stop seeing it
    if (isset($_COOKIE['lebaneseblogs_user_visits'])) {
      if ($_COOKIE['lebaneseblogs_user_visits'] > 3) {
        # user has seen website more than 3 times. 
        # user is an expert, no longer needs tips;
        return NULL;
      }
    }*/
    // show this widget only to the browse page
   if ($_SESSION['pageWanted'] != 'browse') {
      return;
   }

    $all_tips = array(
      array(
        'title'=>'<i class ="fa fa-clock-o"> Read Posts Later</i>',
        'body'=> 'You see some very interesting posts but you only have a few minutes? No problem, just mark them for reading later and you\'re set! <img src ="img/interface/stack-of-cards.png" width ="240" height ="172" class ="noborder">',
        ),
      array(
        'title'=>'Smart Tweeting',
        'body'=> 'When you share using the Lebanese Blogs "tweet" button, the blogger who wrote the post will be automatically mentioned on twitter <a href ="http://lebaneseblogs.com/blog/?p=44">learn more</a> <img src ="img/interface/smart-twitter-bird.png" width ="240" height ="107" class ="noborder">',
        ),
      array(
        'title'=>'<i class ="fa fa-star"></i> Don\'t Miss a Post',
        'body'=> 'Having a list of favorite blogs is a great way to keep up with blogs that don\'t post often. Whenever you see a blog you like, just click on the "Add Blog to Favorites" button to add it to your list<img src ="img/interface/add-blog-button.png" class ="noborder">',
        ),
      array(
        'title'=>'A Bottomless Pit',
        'body'=> 'Lebanese Blogs is an infinite scrolling website. This means that scrolling down never ends. It is like going back in time to see older posts. <img src ="img/interface/downward-arrows.png" width ="240" height = "80" class ="noborder">',
        ),
      );
      ?>
    <div class="card-container">
      <div class="card tip">
        <div class="card_header primaryfont tip">
          <h3><i class = "icon-lightbulb"></i> TIP</h3>
        </div>
        <div class="card_body tip">
          <h3 class ="primaryfont"><?php echo $all_tips[$whichtip]['title'] ; ?></h3>
          <p class ="secondaryfont"><?php echo $all_tips[$whichtip]['body'] ; ?></p>
        </div>
        <?php 
        if (!empty($all_tips[$whichtip]['image'])) {
          ;?> 
          <div class="card_footer tip nopadding background-white">
            <img src="<?php echo $all_tips[$whichtip]['image'] ; ?>">
          </div>
          <?php
        }
        ?>
      </div>
    </div>

    <?php
      // add the tip card to the count of items displayed
      $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
    }

    public static function featuredBloggers(){

      global $channel_descriptions;
      if (!empty($_SESSION['currentChannel'])) {
        $tempChannel = $_SESSION['currentChannel'];
      } else {
        $tempChannel = "all";
      }
      $bloggers = Posts::get_random_bloggers(1, $tempChannel);
      $bloggerID = $bloggers[0]->blog_id;
      Render::drawFeaturedBlogger($bloggerID, $featured='yes');

    }

    public static function topFive($howmany=5, $hours=12, $channel = null){
      $stats = Posts::get_Top_Posts($hours, $howmany, $channel);
      if (count($stats)<5) {
        $hours = 24;
        $stats = Posts::get_Top_Posts($hours, $howmany, $channel);
        if (count($stats)<5) {
          $hours = 48;
          $stats = Posts::get_Top_Posts($hours, $howmany, $channel);
        }
      }
      ;?>
      <div class="card-container">

        <div class="card nobackground">
          <div class="announcement">
            <h4>
              First Time? <a href="tour" id="startHere" class ="secondaryfont">Start Here</a>
            </h4>
          </div>    

        </div>
        
        <div class="card">
          <div class="card_header primaryfont noborder background-red">
            <h3 class ="whitefont">Top Posts <span class ="deemphasize">in the last </span></h3>
            <div id="timeSelector"><?php echo Lb_functions::hours_to_days($hours); ?><i class ="fa fa-chevron-down rightit"></i></div>
          </div>
            <ul id ="timeList" style="display:none">
              <li data-hours="12">12 Hours<i class ="fa fa-chevron-up"></i></li>
              <li data-hours="24">24 Hours</li>
              <li data-hours="72">3 Days</li>
              <li data-hours="168">7 days</li>
            </ul>
          <div id = "top" class ="card_body elastic">

            <?php

            foreach ($stats as $stat) {
              $img = $stat->post_image;

              // use image cache if exists.
              $image_cache = IMGCACHE_BASE.$stat->post_timestamp.'_'.$stat->blog_id.'.jpg';
              $image_file = ABSPATH.'img/cache/'.$stat->post_timestamp.'_'.$stat->blog_id.'.jpg';
              if (file_exists($image_file)) {
                $img = $image_cache;
              }
              $img_height =$stat->post_image_height ;
              $img_width =$stat->post_image_width ;
              $title = $stat->post_title;
              $url = $stat->post_url;
              $blogger_url = WEBPATH . $stat->blog_id;
              ;?>

              <div class="list_type_a">
                <?php
                if (empty($stat->post_image)) {
                  ;?>
                  <a href ="<?php echo $url ;?>" target="_blank">
                    <div class="thumb"><img class ="lazy" data-original="<?php echo "img/interface/no-image.jpg" ;?>" src="img/interface/grey.gif" height ="75px"></div>
                  </a>
                  <?php
                }else {
                  if ($img_width >= $img_height) {
                    ;?>       
                    <a href ="<?php echo $url ;?>" target="_blank">
                      <div class="thumb"><img class ="lazy" data-original="<?php echo $img ;?>" src="img/interface/grey.gif" height ="75px"></div>
                    </a>
                    <?php
                  } else {
                    ;?>
                    <a href ="<?php echo $url ;?>" target="_blank">
                      <div class="thumb"><img class ="lazy" data-original="<?php echo $img ;?>" src="img/interface/grey.gif" width = "75px"></div>
                    </a>
                    <?php
                  }
                }
                ?>
                <?php $blog_name = isset($stat->blog_name)? $stat->blog_name: $stat->col_name ;?>
                <h4><a href ="<?php echo $url ;?>" target="_blank"><?php echo $title ;?></a></h4><h5><a href ="<?php echo $blogger_url; ?>"><?php echo $blog_name ;?></a></h5>      
              </div>
              <?php
            } 
            ;?>
          </div> <!-- /cards_body -->
        </div>
      </div>
      <?php 
    }

    public static function facebookLikes(){
      ?>
      <?php 
        // show this widget only to the browse page
        if ($_SESSION['pageWanted'] == 'browse') {
      ?>
      <div id ="facebooklikes" class="card-container" >
        <div class="card" style ="opacity:0; height:150px">
          <div class="card_header background-greylightest">
            <h4><i class="fa fa-thumbs-o-up"></i> Stay updated with new top posts by Liking Lebanese Blogs on Facebook</h4>
          </div>
          <div class="card_body">
            <div class="fb-like" data-href="http://facebook.com/lebaneseblogs" data-width="268" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div>
          </div>
        </div>
<!--         <div class="card" style ="opacity:0; height:100px">
          <div class="card_header background-greylightest" style ="border-top: 1px solid silver">
            <h4>... and by following us on Twitter</h4>
          </div>
          <div class="card_body">
            <a href="https://twitter.com/lebaneseblogs" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @lebaneseblogs</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          </div>
        </div> -->
      </div>
      <?php
        }
    }

    public static function drawAd(){
      ?>

      <div class="card-container">
        <div class="card" style ="height:475px; opacity:0;">

          <!--card header-->

          <div class="card_header background-greylightest" style="background-color: #CF8CD7">
            <img class ="blog_thumb" src="<?php echo WEBPATH.'img/thumbs/bullhorn.jpg';?>" width ="50" height ="50">
            <div class="post_details">
              <div class="blog_name secondaryfont">Special Promotion<br><span class ="small understated" style ="opacity:.5;font-size:12px">Sponsored listing</span></div>
            </div>
          </div>

          <!--card body-->
          <div class ="card_body" id ="advertisement">
            <div class="post_title secondaryfont"><a href="http://go.anghami.com/lbblogsalfa">We're giving you music and free 3G starting at $1 !</a></div>
            <a href="http://go.anghami.com/lbblogsalfa">
              <img src="<?php echo WEBPATH ?>img/ads/alfa-anghami300x300.gif" width ="278" height ="278" alt="Anghami &amp; Alfa Special Offer">              
            </a>
            <p class ="small understated" style ="opacity:.5;font-size:11px">Thanks for helping make Lebanese Blogs possible</p>    
          </div>

        </div>
      </div>
      <?php
    }

    public static function facebookTest(){
      ;?>
      <div class="card-container">
        <div class="card">
          <div class ="card_body" id ="facebookTest">
          <?php 

          if (LbUser::isSignedIn()) {
            Echo "<p>User is signed in ";
            echo "with Facebook ID ".LbUser::getFacebookID();
            LbUser::showFacebookSignOutLink("Log Out");
          } else {
            LbUser::showFacebookSigninButton();
          }
          ?>
        </div>
      </div>
    </div>
    <?php  
    }

  }
  ?>
