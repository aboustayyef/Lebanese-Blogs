 <?php 
/**
* This class has a set of functions that extract details for particular bloggers
*/
class BloggerDetails
{
	public     	$blogger_id, 
				$blog_details, 
				$list_of_posts,
				$list_of_photos;

	function __construct($blogger_id)
	{
		$this->blogger_id = $blogger_id;
		$this->list_of_posts = self::get_blogger_posts(9, $this->blogger_id); 
		// both functions below require the one above as a prerequisit
		$this->blog_details = $this->getBloggerDetails();
		$this->list_of_photos = $this->getListOfPhotos();
	}


	public static function getLastPostByBlogger($blog_id){
		$result =Posts::get_blogger_posts(1, $blog_id);
		return $result[0];
	}

	public static function getTimeSinceLastPostByBlogger($blog_id, $options = "days"){
		$last_post = self::getLastPostByBlogger($blog_id);
		$now = time();
		$then = $last_post->post_timestamp;
		$deltaInSeconds = $now - $then;
		$deltaInDays = ceil($deltaInSeconds / (60*60*24));
		if ($options="days") {
			return $deltaInDays;
		}else{
			return $deltaInSeconds;
		}
	}

/*Low Level Functions. Will be used by higher ones above*/

	public function getBloggerDetails(){
        if (!empty($this->list_of_posts[0])) {
            $tempDetails = array();
            $tempDetails['blog_name'] =$this->list_of_posts[0]->blog_name ;
            $tempDetails['blog_description']=$this->list_of_posts[0]->blog_description;
            $tempDetails['blog_url']=$this->list_of_posts[0]->blog_url;
            $tempDetails['blog_author_twitter_username']=$this->list_of_posts[0]->blog_author_twitter_username;
            $tempDetails['blog_tags']=$this->list_of_posts[0]->blog_tags;
            return $tempDetails;
        }
	}

    public static function getTopBlogPhotos($blogger_ID, $number_of_photos=8){
        $query = 
        'SELECT
        post_visits,
        post_url, 
        post_timestamp, 
        post_title, 
        post_image,
        post_image_height, 
        post_image_width 
        FROM posts 
        WHERE post_image_height > 0 
        AND blog_id = "'.$blogger_ID.'"
        ORDER BY `post_socialScore` DESC LIMIT ' . $number_of_photos ;   

        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = DB::getInstance()->results();
            return $output;
        }
    }

	public function getListOfPhotos(){
		$tempPhotos = array();
		foreach ($this->list_of_posts as $key => $post) {
			if (!empty($post->post_image)) {

              /* find out if there's a cache image */
              $the_image = $post->post_image;
              $image_cache = IMGCACHE_BASE.$post->post_timestamp.'_'.$post->blog_id.'.'.Lb_functions::get_image_format($the_image);
              $image_file = ABSPATH.'img/cache/'.$post->post_timestamp.'_'.$post->blog_id.'.'.Lb_functions::get_image_format($the_image);
              if (file_exists($image_file)) {
                $the_image = $image_cache;
              } else {
              	$the_image = $post->post_image;
              }
              
				$tempPhotos[]=array(
					'img_url'		=>      $the_image,
					'post_title'	=>		$post->post_title
				);
			}
		}
		return $tempPhotos;
	}
              // use image cache if exists.

	public static function getTopPostsByBlogger($whichblogger, $number_of_posts=3, $timeInDays=60){
 	$targetMinimumTimeStamp = time() - ($timeInDays * 24 * 60 * 60);
 	$query = 
        'SELECT
        blogs.blog_id,
        columnists.col_shorthand,
        blogs.blog_name, 
        columnists.col_name,
        blogs.blog_url, 
        columnists.col_home_page,
        posts.post_visits,
        posts.post_url, 
        posts.post_timestamp, 
        posts.post_title, 
        posts.post_image,
        posts.post_image_height, 
        posts.post_image_width, 
        posts.post_excerpt, 
        blogs.blog_author_twitter_username, 
        columnists.col_author_twitter_username
        FROM `posts` 
        LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
        LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
        WHERE 
        ((blogs.blog_id = "'.trim($whichblogger).'") OR (columnists.col_shorthand = "'.trim($whichblogger).'"))
        AND 
        (posts.post_timestamp > '.$targetMinimumTimeStamp.')
        ORDER BY `post_socialScore` DESC LIMIT ' . $number_of_posts ;	
        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output;
        }
	}

    public static function get_blogger_posts($number_of_posts = 10, $whichblogger = NULL) { //default is 10 posts
        // get blog's details

        $query = 
        'SELECT
        blogs.blog_id, 
        columnists.col_shorthand, 
        blogs.blog_name, 
        columnists.col_name,
        blogs.blog_description, 
        columnists.col_description, 
        blogs.blog_tags, 
        columnists.col_tags, 
        blogs.blog_url, 
        columnists.col_home_page,
        posts.post_url, 
        posts.post_timestamp, 
        posts.post_title, 
        posts.post_image,
        posts.post_image_height, 
        posts.post_image_width, 
        posts.post_excerpt, 
        blogs.blog_author_twitter_username, 
        columnists.col_author_twitter_username,
        posts.post_totalShares,
        posts.post_socialScore
        FROM `posts` 
        LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
        LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
        WHERE (blogs.blog_id = "'.trim($whichblogger).'") OR (columnists.col_shorthand = "'.trim($whichblogger).'") 
        ORDER BY `post_timestamp` DESC LIMIT ' . $number_of_posts ;
        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output;
        }
    }


}
?>