<?php 
/**
* This class renders posts for certain views
*/
class Render
{
  public static function drawData($data, $kind='normal'){
    if ($_SESSION['currentView'] == 'cards') {
        echo '<div id="posts">';
          self::drawCards($data, 'normal');
        echo '</div> <!-- /posts -->';
    } else if ($_SESSION['currentView'] == 'timeline') {
      echo '<div id="posts">';
        self::drawTimeline($data);
      echo '</div> <!-- /posts -->';
    }else if ($_SESSION['currentView'] == 'compact'){
      echo '<div id="posts">';
        self::drawCompact($data);
      echo '</div> <!-- /posts -->';
    }else{
      die('View has to be "cards", "timeLine" or "compact"');
    }
  }

  public static function drawVirality($virality){
    $viralityColor = round((255/50)*$virality);
    echo '<span class ="virality">Virality: ';
    echo '<span class ="viralityBar" title ="Virality Score: ' . $virality . '/50" style ="color:rgba('.$viralityColor.',120,120,1)">';
    echo str_repeat('|', $virality);
    echo '<span style ="color:rgba('.$viralityColor.',120,120,0.2)">';
    echo str_repeat('|', 50-$virality);
    echo '</span></span></span>';
  }

  public static function drawCards($data, $kind='normal') // kinds supported: 'normal' & 'blogger'
  {
    // loops through the posts
    $signed_in = LbUser::isSignedIn() ? True: false;
    $f_id = LbUser::getFacebookID();

    foreach ($data as $key => $post) {
      // prepare URL of exit link
      $target_url = WEBPATH."r.php?r=".urlencode($post->post_url);
      
      // add extra cards (if any)
      if ($kind == 'normal') {
        Extras::control($_SESSION['posts_displayed']);
      }
      ;?>
      
      <!-- Depending on wheather it's a columnist or a blogger, some variables have to change -->
      <?php 
        $blog_id = $post->blog_id;
        $blog_name = $post->blog_name;
        $author_twitter = $post->blog_author_twitter_username;
      ?>
      <!-- Card wrapper -->

      <div class="card-container">
        <div class="card" 
            style ="opacity:0;"
            data-id="<?php echo $_SESSION['posts_displayed']; ?>"
            data-blogid="<?php echo $blog_id; ?>"
            data-blogname="<?php echo $blog_name; ?>"
            data-twitter="<?php echo $author_twitter; ?>"
            data-postTimestamp = "<?php echo $post->post_timestamp ; ?>"
            data-postTitle ="<?php echo $post->post_title; ?>"
            data-postUrl="<?php echo $target_url ; ?>"
            data-postExcerpt="<?php echo $post->post_excerpt; ?>"
            data-postImage="<?php echo $post->post_image ; ?>"
            data-postImageHeight="<?php echo $post->post_image_height ?>"
            data-postImageWidth="<?php echo $post->post_image_width ?>"
            <?php 
              if (Lb_functions::contains_arabic($post->post_title)) {
                ?>
                  data-postLanguage="arabic"
                <?php
              }else{
                ?>
                  data-postLanguage="latin"
                <?php
              }
            ?>

        >
      
        <!--card header-->
        <?php 

        if ($kind == 'normal') { //only show the header when we're not in the blogger page

        ?>
          <div class="card_header background-greylightest">
            <a href ="<?php echo WEBPATH. $blog_id ; ?>"><img class ="blog_thumb" src="<?php echo THUMBS_BASE.$blog_id.'.jpg';?>" width ="50" height ="50"></a>
            <div class="post_details">
              <div class="blog_name secondaryfont"><a href ="<?php echo WEBPATH. $blog_id ;?>"><?php echo $blog_name ;?></a></div>
              <div class="blog_tools">
                  <!-- <li><i class ="icon-exclamation-sign"></i> About Blog</li> -->
                  
                  <?php 
                    self::showFavoritesStar($f_id,$blog_id);
                  ?>
              </div>
            </div>
          </div>
        <?php 
        }  
        ?>


        <!--card body-->
        <div class ="card_body" id ="<?php echo 'content-post-' . $_SESSION['posts_displayed']; ?>">
          <div class="post_time">
            <?php 
              // time since posted
              echo Lb_functions::time_elapsed_string($post->post_timestamp);
              // show virality bar
                self::drawVirality($post->post_virality);             
            ?>

          </div>
          <?php 
            if (isset($_SESSION['admin']))
            {
              if ($kind == 'normal') 
              {
                ?>
                <div class="number-of-clicks"><?php echo $post->post_visits; ?></div>
                <?php
              }
            }
          ?>
          <div class="post_title secondaryfont <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";}else{echo " ltr";} ?>"><a  href="<?php echo $target_url ;?>" target="_blank"><?php echo $post->post_title; ?></a>
          <?php 
            // if admin is signed in, add edit link
            if (isset($_SESSION['admin'])) {
              ?>
              <a href="admin/edit.php?postid=<?php echo $post->post_id; ?>"> <small>edit</small></a>&nbsp;
              <a href="admin/delete.php?postid=<?php echo $post->post_id; ?>"> <small><i class ="icon-trash"></i></small></a>
              <?php
            }
          ?>
          </div>
          <?php 

            if (isset($post->post_image) && ($post->post_image_width > 0) && ($post->post_image_height>0)) {
              $image_width = 278;
              $image_height = intval(($image_width / $post->post_image_width)*$post->post_image_height);
              $the_image = $post->post_image;

              // use image cache if exists.
              $image_cache = IMGCACHE_BASE.$post->post_timestamp.'_'.$post->blog_id.'.'.Lb_functions::get_image_format($the_image);
              $image_file = ABSPATH.'img/cache/'.$post->post_timestamp.'_'.$post->blog_id.'.'.Lb_functions::get_image_format($the_image);
              if (file_exists($image_file)) {
                $the_image = $image_cache;
              }

              ;?>
              
              <a href="<?php echo $target_url ;?>" target="_blank"><img class="lazy" data-original="<?php echo $the_image ; ?>" src="img/interface/grey.gif" width="<?php echo $image_width ; ?>" height="<?php echo $image_height ; ?>"></a>
              
              <?php
            } else {
              ;?>
              
              <div class ="excerpt"><blockquote class ="secondaryfont <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";}else{echo " ltr";} ?>"><?php echo $post->post_excerpt; ?></blockquote></div>
              
              <?php
            }

          ?>
        </div>

        <!--card footer-->
        <div class="card_footer nopadding">
          <?php 
          $tweetcredit = ($author_twitter)?" by @{$author_twitter}":"";
          $url_to_incode = "{$post->post_title} {$post->post_url}".$tweetcredit." via lebaneseblogs.com";
          $twitterUrl = urlencode($url_to_incode);
          $post_url = $post->post_url;
          ?>
          <ul>
            <?php 
              if (LbUser::isSignedIn()) { // if user is signed in;
                if (Posts::isSaved($f_id, $post_url)) {
                  // user is signed in and post is saved
                  ?>
                  <li class="doSave save_toggle action" data-action="removeFromList" data-url ="<?php echo $post_url ?>" data-user="<?php echo $f_id ; ?>"><span class="message"><i class="fa fa-list-alt selected"></i> Listed</span></li>
                  <?php
                }else {
                  // user is signed in but post is not saved
                  ?>
                  <li class="doSave save_toggle action" data-action="addToList" data-url ="<?php echo $post_url ?>" data-user="<?php echo $f_id ; ?>"><span class="message"><i class="fa fa-clock-o"></i> Read Later</span></li>
                  <?php
                }
              } else {
                // user is not signed in. Will ask them to sign in;
                $here=urlencode($_SESSION['pageWanted']);
                $encoded_post_url = urlencode($post_url);
                ?>
                <li class="doSave"><a href="<?php echo WEBPATH.'?pagewanted=saved&urltosave='.$encoded_post_url.'&redirecturl='.WEBPATH.'?pagewanted='.$here ; ?>"><i class="fa fa-clock-o"></i> Read Later</a></li>
                <?php
              }
             ?> 
          
            <li> <a href="https://twitter.com/intent/tweet?text=<?php echo $twitterUrl; ?>" title="Click to send this post to Twitter!" target="_blank"><i class="fa fa-twitter"></i> Tweet</a> </li>
            <li> <a href="http://www.facebook.com/sharer.php?u=<?php echo $post_url ;?>"><i class="fa fa-facebook"></i> Share</a> </li>
            <?php 
              /*if (admin_logged_in()) {
                echo "<li>$post_visits</li>";
                echo '<a href ="'.$root_is_at.'/admin/edit.php?id='.$post_id.'"><i class ="icon-edit icon-large"></i></a>';
              }*/
              ?>
            </ul>


        </div>
      </div> <!-- /card-container -->
    </div> <!-- /content module -->
      
    <?php
    // update counters
    $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
    $_SESSION['posts_displayed'] = $_SESSION['posts_displayed'] + 1;
    }
  }

  public static function drawTimeline($data)
  {
    
    // loops through the posts
    foreach ($data as $key => $post) 
    {
      // prepare URL of exit link
      $target_url = WEBPATH."r.php?r=".urlencode($post->post_url);?>
    
      <!-- timeline post wrapper -->

      <div class="timeline timeline-post" data-id="<?php echo $_SESSION['posts_displayed']; ?>"  style ="opacity:0;">
    
      <!--blog thumb-->
      
      <div class="col col-1 timeline-blog-thumb">
        <a href ="<?php echo '/' . $post->post_id ?>"><img class ="blog_thumb lazy" data-original="<?php echo "img/thumbs/".$post->blog_id.".jpg";?>" src="img/interface/grey.gif" width ="50" height ="50"></a>
      </div>
        
      <!-- post details -->
      <div class="col col-2">
        <div class="col col-2a timeline-post-details">
          <!-- blog name -->
          <div class="blog_name"><a href ="<?php echo '/' . $post->blog_id ?>"><?php echo $post->blog_name ;?></a></div>
          
          <!-- post title -->
          <div class="post_title <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";};?>"><a href="<?php echo $target_url ;?>"><?php echo $post->post_title; ?></a><span class="post_time"><?php echo Lb_functions::time_elapsed_string($post->post_timestamp) ?></span></div>
          
          <!-- image or excerpt -->
          <?php 

          if (isset($post->post_image) && ($post->post_image_width > 0) && ($post->post_image_height>0)) 
          {
            $theimage = new Image();
            $theimage->setMax(500);
            $theimage->setWidth($post->post_image_width);
            $theimage->setHeight($post->post_image_height);
            $theimage->calculateDimensions();
            $image_height = $theimage->getDesiredHeight();
            $image_width = $theimage->getDesiredWidth();
            ?>
            
            <a href="<?php echo $target_url ;?>"><img class="lazy" data-original="<?php echo $post->post_image ; ?>" src="img/interface/grey.gif" width="<?php echo $image_width ; ?>" height="<?php echo $image_height ; ?>"></a>
            <?php
          } else {
            ;?>
            <div class ="excerpt <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";};?>">
              <blockquote><?php echo $post->post_excerpt; ?></blockquote> 
              <a href="<?php echo $target_url ;?>">Read more..</a>
            </div>  
            <?php
          }

          $tweetcredit = ($post->blog_author_twitter_username)?" by @{$post->blog_author_twitter_username}":"";
          $url_to_incode = "{$post->post_title} {$post->post_url}".$tweetcredit." via lebaneseblogs.com";
          $twitterUrl = urlencode($url_to_incode);
          ?>

        </div>
        <!-- <div class="col col-2b timeline-tools">
          <ul>
            <li><a href="<?php echo WEBPATH.$post->blog_id; ?>"><i class="icon icon-signin"></i> Go To Blog's Page</a></li>
            <li><div class="add2fav"><a href="userlogin.php?from=favorites"><i class="icon-star"></i> Add Blog to Favorites</a></div></li>
            <li><a href="#"><i class="icon-heart"></i> Save Post</a></li>
            <li> <a href="https://twitter.com/intent/tweet?text=<?php echo $twitterUrl; ?>" title="Click to send this post to Twitter!" target="_blank"><i class="icon-twitter icon-large"></i> Tweet</a> </li>
            <li> <a href="http://www.facebook.com/sharer.php?u='.$post_url.'"><i class="icon-facebook icon-large"></i> Share</a> </li>
          </ul>
        </div> -->
      </div>
    </div>
    <?php
    // update counters
    $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
    $_SESSION['posts_displayed'] = $_SESSION['posts_displayed'] + 1;
    }
  }


  public static function showFavoritesStar($user, $blog){
      $blog_id = $blog;
      $f_id = $user;
    if (LbUser::isSignedIn())
    { // if user is signed in;
      if (Posts::isFavorite($f_id, $blog_id))
      {
        // user is signed in and blog is a favorite
        ?>
        <div title ="Remove Blog from Favorites" class ="button action removeFromFav" data-action="removeFromFavorites" data-blog="<?php echo $blog_id ?>" data-user="<?php echo $f_id ; ?>"><i class="fa fa-star fav"></i></div>
        <?php
      }else {
        // user is signed in but blog is not a favorite
        ?>
        <div title ="Add Blog to Favorites" class ="button action addToFav " data-action="addToFavorites" data-blog="<?php echo $blog_id ?>" data-user="<?php echo $f_id ; ?>"><i class="fa fa-star fav"></i></div>
        <?php
      }
    } else {
      // user is not signed in. Will ask them to sign in;
      $here=urlencode($_SESSION['pageWanted']);
      ?>
      <div title ="Add Blog to Favorites (Requires Signin)" class ="addToFav" ><a href="<?php echo WEBPATH.'?pagewanted=login&blogtofave='.$blog_id.'&redirecturl='.WEBPATH.'?pagewanted='.$here ; ?>"><i class="fa fa-star fav"></i></a></div>
      <?php
    }
  }


  public static function drawCompact($data)
  {
    foreach ($data as $key => $post) {

      $target_url = WEBPATH."r.php?r=".urlencode($post->post_url);?>

      <!-- compact post wrapper -->

      <div class="compact" data-post-number="<?php echo $_SESSION['posts_displayed']; ?>" style ="opacity:0"> 
        
          <!-- The stuff that show are wrapped in a compact-preview class -->
          
          <div class="compact-preview">
            <!-- blog name -->
            <div class="blog_name"><?php echo $post->blog_name ;?></div>
            
            <!-- post title -->
            <div class="post_title <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";};?>"><?php echo $post->post_title; ?></div>

            <!-- post excerpt -->
            <div class ="excerpt-preview <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";};?>"><?php echo $post->post_excerpt; ?>  </div>  
            
            <!-- post time -->
            <div class="post_time"><?php echo Lb_functions::time_elapsed_string($post->post_timestamp) ?></div>
          </div>
          
          <!-- The other stuff are in the class compact-details -->
          <div class="compact-details"> <!-- (the hidden part) -->

            <h4><a href ="<?php echo '/' . $post->blog_id ?>"><?php echo $post->blog_name ;?></a></h4>
            <h2 class="<?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";};?>"><a href="<?php echo $target_url ;?>"><?php echo $post->post_title; ?></a></h2>
            <?php 
              if (isset($post->post_image) && ($post->post_image_width > 0) && ($post->post_image_height>0)) {
                $theimage = new Image(); // use this class to display a maximum side of 500 per image
                $theimage->setMax(500);
                $theimage->setWidth($post->post_image_width);
                $theimage->setHeight($post->post_image_height);
                $theimage->calculateDimensions();
                $image_height = $theimage->getDesiredHeight();
                $image_width = $theimage->getDesiredWidth();
                ?>
              
              <div class="compact-image">
                <a href="<?php echo $target_url ;?>">
                  <img class="lazy" data-original="<?php echo $post->post_image ; ?>" src="img/interface/grey.gif" width="<?php echo $image_width ; ?>" height="<?php echo $image_height ; ?>">
                </a>
              </div>
              <?php } ;?>
              <div class ="compact-excerpt <?php if (Lb_functions::contains_arabic($post->post_title)) {echo " rtl";};?>">
                <blockquote><?php echo $post->post_excerpt; ?></blockquote> 
                  <a href="<?php echo $target_url ;?>">Read more..</a>
              </div>  
              <?php?>
          </div> <!-- /compact-details (the hidden part) -->        
        </div> <!-- /compact -->
      <?php

      // update counters
      $_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
      $_SESSION['posts_displayed'] = $_SESSION['posts_displayed'] + 1;
    }
  }

  public static function drawFeaturedBlogger($BloggerID, $featured='no'){
    global $tags;
    // get blogger details (name, profile pic, home, twitter, link, etc)
    // get 8 images from top posts, with links to source posts and titles
    // get top 3 posts, titles and links.
    $blogger = new BloggerDetails($BloggerID);
    $bloggerID = $BloggerID;
    $blogTitle = $blogger->blog_details['blog_name'];
    $blogUrl = $blogger->blog_details['blog_url'];
    $blogDescription = $blogger->blog_details['blog_description'];
    $topPosts = BloggerDetails::getTopPostsByBlogger($bloggerID, $number_of_posts=3, 90);
    $blogPhotos = BloggerDetails::getTopBlogPhotos($bloggerID,8);
    $blogTags = $blogger->blog_details['blog_tags'];
    ?>
    <div class ="card-container bloggerCard">
      
      <!-- Start Card -->
      <div class="card" style ="opacity:0" data-blogid="<?php echo $bloggerID; ?>">
        <?php 
          if ($featured == "yes") 
          {
            ;?>
            <div class="card_header primaryfont background-bluegreen">
              <h3 class ="whitefont">
                <?php 
                if ((isset($_SESSION['currentChannel'])) && ($_SESSION['currentChannel'] != 'all')) 
                {
                  echo '<span class ="understated">'. Channels::describeTag($_SESSION['currentChannel']).'</span><br>';
                }
                  ?>
                  Featured Blog        
                </h3>
              </div>
            <?php
          }
        ?>
        

        <!-- Lose Items -->
        <div class="featuredBlogHeader">
          <?php
          /*Only show this section if we have 8 photos*/
          if (count($blogPhotos) == 8) 
          {
            foreach ($blogPhotos as $key => $photo) 
            {
              $url = $photo->post_url;
              $img = $photo->post_image;
              $img_height = $photo->post_image_height;
              $img_width = $photo->post_image_width;
              $title = $photo->post_title;
              if ($img_width >= $img_height) 
              {
                ;?>       
                <a href ="<?php echo $url ;?>" target="_blank">
                  <div class="CoverThumb"><img title ="<?php echo $title; ?>" class="lazy" data-original="<?php echo $img ; ?>" src="img/interface/grey.gif" height="75px"></div>
                </a>
                <?php
              } else {
                ;?>
                <a href ="<?php echo $url ;?>" target="_blank">
                  <div class="CoverThumb" ><img title ="<?php echo $title; ?>" class="lazy" data-original="<?php echo $img ; ?>" src="img/interface/grey.gif" width = "75px"></div>
                </a>
                <?php
              }
            }
            echo '<div class="avatar withheader">';
          }else{
            echo '<div class="avatar noheader">';
          }
          ?>         
              <a href ="<?php echo WEBPATH.$bloggerID ?>"><img src="<?php echo WEBPATH.'/img/thumbs/'.$bloggerID.'.jpg' ?>" alt="<?php echo $blogTitle; ?>"></a>
          </div>
        </div>
        <!-- End Lose Item -->
        
        <!-- Card Header -->
        <div class="card_header bloggerCard">
          <div class="blogTitle">
            <h2 class="primaryfont">
              <a href ="<?php echo WEBPATH.$bloggerID ?>"><?php echo $blogTitle ;?></a>
            </h2>
          </div>
        </div>
        <!-- End of header -->
        <!-- Lose Section -->
        <div class="tools">
          <ul>
              <?php 
              $blog_id = $bloggerID;
              if (LbUser::isSignedIn())
              { // if user is signed in;
                $f_id = LbUser::getFacebookID();
                if (Posts::isFavorite($f_id, $blog_id))
                {
                  // user is signed in and blog is a favorite
                  ?>
                  <li title ="Remove Blog From Favorites" class ="button action removeFromFav" data-action="removeFromFavorites" data-blog="<?php echo $blog_id ?>" data-user="<?php echo $f_id ; ?>"><i class="fa fa-star "></i></li>
                  <?php
                }else {
                  // user is signed in but blog is not a favorite
                  ?>
                  <li title ="Add Blog To Favorites" class ="button action addToFav " data-action="addToFavorites" data-blog="<?php echo $blog_id ?>" data-user="<?php echo $f_id ; ?>"><i class="fa fa-star "></i></li>
                  <?php
                }
              } else {
                // user is not signed in. Will ask them to sign in;
                $here=urlencode($_SESSION['pageWanted']);
                ?>
                <li title ="Add Blog To Favorites (requires Sign In)" class ="addToFav" ><a href="<?php echo WEBPATH.'?pagewanted=login&blogtofave='.$blog_id.'&redirecturl='.WEBPATH.'?pagewanted='.$here ; ?>"><i class="fa fa-star "></i></a></li>
                <?php
              }

              ?>
            <li><a title ="Go to author's twitter page" href="<?php echo 'https://twitter.com/'.$blogger->blog_details['blog_author_twitter_username']; ?>"><i class ="fa fa-twitter"></i></a></li>
            <li><a title ="Go to author's homepage" href="<?php echo $blogUrl  ?>"><i class ="fa fa-home"></i></a></li>
          </ul>
        </div>
        <!-- End of Lose Section -->

        <!-- Another header -->
        <div class="card_header description <?php
            if (count($blogPhotos) < 8) {
              echo " nophotos";
            }
           ?>">
          <div class="secondaryfont blogDescription ">
            <p><?php echo $blogDescription; ?></p>
            <?php 
              $tags = explode(',',$blogTags);
              echo '<div id ="tags">';
              foreach ($tags as $key => $tag) {
                $tag = trim($tag);
                $channel = Channels::resolveTag($tag); // because we have many tags but only a few channels
                echo '<a href="'.WEBPATH.'?channel='.$channel.'">#'.$tag.' </a>'; 
              }
              echo '</div>';
            ?>
          </div>
        </div>
        <!-- End of other header -->

<?php 
          if (count($blogPhotos) < 8){
?>
          <!-- This is a card body shows only if no images-->
        <div class="card_body">
          <div class="topPosts secondaryfont">
            <h3>Top Posts:</h3>
            <ol>
              <?php 
                foreach ($topPosts as $key => $post) {
                  echo '<li><a href="' . $post->post_url . '">' . $post->post_title . '</a></li>';
                }
              ?>
            </ol>
          </div>
        </div>
        <!-- End of card body -->
<?php
          } 
?>
        

      </div>
      <!-- End of Card -->
    </div>
  <?php }

}




?>