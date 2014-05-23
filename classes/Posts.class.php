<?php 

/**
* fetches posts to display 
*/

class Posts
{

    private static $user_bloggers = null;

    public function __construct()
    {
        //nothing
    }

    public static function get_blog_name($blog_id){
        $query = 'SELECT blogs.blog_name FROM blogs WHERE blogs.blog_id = "'.$blog_id.'" 
                  UNION
                SELECT columnists.col_name FROM columnists WHERE col_shorthand = "'.$blog_id.'"' ;
        DB::getInstance()->query($query);
        if (count(DB::getInstance()->results())>0) {
            $allResults = DB::getInstance()->results();
            return $allResults[0]->blog_name;
        } else {
            return null;
        }
    }

    public static function blogExists($blog_id){
        $query = 'SELECT blog_id FROM blogs WHERE blogs.blog_id = "'.$blog_id.'" 
                    UNION SELECT col_shorthand FROM columnists WHERE columnists.col_shorthand = "'.$blog_id.'"';
        $results = DB::getInstance()->query($query)->results();
        if (count($results)>0) {
            return true;
        }else{
            return false;
        }
    }


    public static function get_latest_posts($number_of_posts = 10, $channel = NULL) { //default is 10 posts
        // get blog's details
        if ($channel == 'all') {
            $channel = NULL ;
        }
        if (isset($channel) && !empty($channel)) {
            $query = 'SELECT 
            posts.post_url, 
            posts.post_title, 
            posts.post_id, 
            posts.post_visits, 
            blogs.blog_id, 
            columnists.col_shorthand, 
            blogs.blog_name, 
            columnists.col_name,  
            posts.post_excerpt, 
            posts.post_image,
            posts.post_image_height, 
            posts.post_image_width, 
            blogs.blog_author_twitter_username, 
            columnists.col_author_twitter_username, 
            posts.post_timestamp,
            posts.post_totalShares,
            posts.post_socialScore
            FROM `posts` 
            LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
            LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
            WHERE (blogs.blog_tags LIKE "%'.trim($channel).'%") OR (columnists.col_tags LIKE "%'.trim($channel).'%") 
            ORDER BY `post_timestamp` DESC LIMIT ' . $number_of_posts ;
        } else {
            $query = 'SELECT 
            posts.post_url, 
            posts.post_title, 
            posts.post_id, 
            posts.post_visits, 
            blogs.blog_id, 
            columnists.col_shorthand, 
            blogs.blog_name, 
            columnists.col_name,  
            posts.post_excerpt, 
            posts.post_image,
            posts.post_image_height, 
            posts.post_image_width, 
            blogs.blog_author_twitter_username, 
            columnists.col_author_twitter_username, 
            posts.post_timestamp,
            posts.post_totalShares,
            posts.post_socialScore
            FROM `posts` 
            LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
            LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
            ORDER BY `post_timestamp` DESC LIMIT ' . $number_of_posts ;
        }
        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output ;
        }
    }


    public static function get_interval_posts($from=0,$howmany=10, $channel = NULL){
        if ($channel == 'all') {
            $channel = NULL ;
        }
        if (isset($channel)) {
            $query = 'SELECT 
            posts.post_url, 
            posts.post_title, 
            posts.post_id,
            posts.post_visits, 
            blogs.blog_id, 
            columnists.col_shorthand, 
            blogs.blog_name, 
            columnists.col_name,  
            posts.post_excerpt, 
            posts.post_image,
            posts.post_image_height, 
            posts.post_image_width, 
            blogs.blog_author_twitter_username, 
            columnists.col_author_twitter_username, 
            posts.post_timestamp,
            posts.post_totalShares,
            posts.post_socialScore 
            FROM `posts` 
            LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
            LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
            WHERE (blogs.blog_tags LIKE "%'.trim($channel).'%") OR (columnists.col_tags LIKE "%'.trim($channel).'%") 
            ORDER BY `post_timestamp` DESC LIMIT '.$from.','.$howmany;
        } else {
            $query = "SELECT 
            posts.post_url, 
            posts.post_title, 
            posts.post_id, 
            posts.post_visits, 
            blogs.blog_id, 
            columnists.col_shorthand, 
            blogs.blog_name, 
            columnists.col_name,  
            posts.post_excerpt, 
            posts.post_image,
            posts.post_image_height, 
            posts.post_image_width, 
            blogs.blog_author_twitter_username, 
            columnists.col_author_twitter_username, 
            posts.post_timestamp,
            posts.post_totalShares,
            posts.post_socialScore 
            FROM `posts` 
            LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
            LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
            ORDER BY `post_timestamp` DESC LIMIT $from, $howmany";
        }

        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output;
        }

    }

    // public static function get_blogger_posts($number_of_posts = 10, $whichblogger = NULL) { //default is 10 posts
    //     // get blog's details

    //     $query = 
    //     'SELECT
    //     blogs.blog_id, 
    //     columnists.col_shorthand, 
    //     blogs.blog_name, 
    //     columnists.col_name,
    //     blogs.blog_description, 
    //     columnists.col_description, 
    //     blogs.blog_tags, 
    //     columnists.col_tags, 
    //     blogs.blog_url, 
    //     columnists.col_home_page,
    //     posts.post_url, 
    //     posts.post_timestamp, 
    //     posts.post_title, 
    //     posts.post_image,
    //     posts.post_image_height, 
    //     posts.post_image_width, 
    //     posts.post_excerpt, 
    //     blogs.blog_author_twitter_username, 
    //     columnists.col_author_twitter_username,
    //     posts.post_totalShares,
    //     posts.post_socialScore
    //     FROM `posts` 
    //     LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
    //     LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
    //     WHERE (blogs.blog_id = "'.trim($whichblogger).'") OR (columnists.col_shorthand = "'.trim($whichblogger).'") 
    //     ORDER BY `post_timestamp` DESC LIMIT ' . $number_of_posts ;

    //     DB::getInstance()->query($query);
    //     if (DB::getInstance()->error()) {
    //         echo "There's an error in the query";
    //     } else {
    //         $output = NormalizeResults(DB::getInstance()->results());
    //         return $output;
    //     }
    // }


    public static function get_top_posts($hours, $posts_to_show = 5, $channel = NULL){
        if ($channel == 'all') {
            $channel = NULL ;
        }
        // calculate the time cutoff 
        $lb_now = time();
        $lb_before = $lb_now-($hours*60*60);
        if ($channel){
            $query = 
            'SELECT 
            posts.post_image, 
            posts.post_timestamp, 
            posts.post_image_width, 
            posts.post_visits, 
            posts.post_image_height, 
            posts.post_url, 
            posts.post_title, 
            blogs.blog_name, 
            blogs.blog_author_twitter_username, 
            columnists.col_author_twitter_username ,
            columnists.col_name, 
            posts.blog_id,
            posts.post_totalShares,
            posts.post_socialScore 
            FROM posts LEFT JOIN blogs ON posts.blog_id = blogs.blog_id 
            LEFT JOIN columnists ON posts.blog_id = columnists.col_shorthand
            WHERE ((blogs.blog_tags LIKE "%'.$channel.'%") OR (columnists.col_tags LIKE "%'.$channel.'%")) AND (posts.post_timestamp > '.$lb_before.')
            ORDER BY post_socialScore DESC LIMIT '.$posts_to_show;
        } else {
            $query = 
            'SELECT 
            posts.post_image, 
            posts.post_timestamp, 
            posts.post_image_width, 
            posts.post_visits, 
            posts.post_image_height, 
            posts.post_url, 
            posts.post_title, 
            blogs.blog_name, 
            blogs.blog_author_twitter_username, 
            columnists.col_author_twitter_username ,
            columnists.col_name, 
            posts.blog_id,
            posts.post_totalShares,
            posts.post_socialScore 
            FROM posts LEFT JOIN blogs ON posts.blog_id = blogs.blog_id 
            LEFT JOIN columnists ON posts.blog_id = columnists.col_shorthand
            WHERE posts.post_timestamp > '.$lb_before.' ORDER BY post_socialScore DESC LIMIT '.$posts_to_show;
        }

        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output;
        }
    }

    public static function get_favorite_bloggers_posts($user_id, $from, $howmany){

        // get list of User's favorite blogs - don't make a singleton because the list can change
        $sql1 = 'SELECT `blog_id` FROM users_blogs WHERE `user_facebook_id` = "'.$user_id.'"';
        $blogs = DB::getInstance();
        $allblogs = $blogs->query($sql1)->results();
        $list = array();
        foreach ($allblogs as $key => $blog) {
            $list[] = $blog->blog_id;
        }
        $list = "'".join("', '", $list)."'";
        
        // get list of posts

        $sql2 = "SELECT * 
        FROM `posts` 
        LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
        LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand
        WHERE posts.blog_id IN ($list) OR columnists.col_shorthand IN ($list)
        ORDER BY posts.post_timestamp DESC LIMIT $from, $howmany ";

        DB::getInstance()->query($sql2);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output;
        }

    }

public static function get_saved_bloggers_posts($user_id, $from, $howmany){

        // get list of User's saved posts - don't make a singleton because the list can change
        $sql1 = 'SELECT `post_url` FROM users_posts WHERE `user_facebook_id` = "'.$user_id.'"';
        $posts = DB::getInstance();
        $allposts = $posts->query($sql1)->results();

        $list = array();
        foreach ($allposts as $key => $post) {
            $list[] = $post->post_url;
        }
        $list = "'".join("', '", $list)."'";
        // get list of posts

        $sql2 = "SELECT * 
        FROM `posts` 
        LEFT JOIN `blogs` ON posts.blog_id = blogs.blog_id 
        LEFT JOIN `columnists` ON posts.blog_id = columnists.col_shorthand 
        WHERE posts.post_url 
        IN ($list) 
        ORDER BY posts.post_timestamp 
        DESC LIMIT $from, $howmany ";

        DB::getInstance()->query($sql2);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            $output = NormalizeResults(DB::getInstance()->results());
            return $output;
        }

    }


    public static function get_random_bloggers($howmany, $channel=null, $freshness=45){ // freshness -> last post by that blogger is within x days;
        if ($channel=='all') {
            $channel = NULL;
        }
        $targetTimestamp = time() - ( $freshness * 24 * 60 * 60 );
        if (isset($channel)) {
        $query = 'SELECT `blog_id`, `blog_url`, `blog_name`, `blog_description` FROM blogs WHERE blogs.blog_tags LIKE "%'.$channel.'%" 
                    AND blogs.blog_last_post_timestamp > '.$targetTimestamp.'
                    UNION SELECT `col_shorthand`, `col_home_page`, `col_name`, `col_description` FROM columnists  WHERE columnists.col_tags LIKE "%'.$channel.'%"
                    AND columnists.col_last_post_timestamp > '.$targetTimestamp.'
                    ORDER BY RAND() LIMIT '.$howmany; 
        } else {
        $query = 'SELECT `blog_id`, `blog_url`, `blog_name`, `blog_description` FROM blogs  
                    WHERE blogs.blog_last_post_timestamp > '.$targetTimestamp.'
                    UNION SELECT `col_shorthand`, `col_home_page`, `col_name`, `col_description` FROM columnists 
                    WHERE columnists.col_last_post_timestamp > '.$targetTimestamp.'
                    ORDER BY RAND() LIMIT '.$howmany;            
        }

        DB::getInstance()->query($query);
        if (DB::getInstance()->error()) {
            echo "There's an error in the query";
        } else {
            return DB::getInstance()->results();
        }
    }

    public static function postExists($url){
        $sql = 'SELECT * from posts WHERE `post_url` = "' . $url . '"';
        if (DB::getInstance()->query($sql)->count() > 0) { // yes, this post exists
                return true;
            }
    }

    public static function isFavorite($user_id, $blog_id){
        $sql = 'SELECT * from users_blogs WHERE `user_facebook_id` = "' . $user_id . '" AND `blog_id` = "'  . $blog_id . '"';
        if (DB::getInstance()->query($sql)->count() > 0) { // yes, it's a favorite
            return true;
        }
        return false;
    }

    public static function isSaved($user_id, $post_url){
        $sql = 'SELECT * from users_posts WHERE `user_facebook_id` = "' . $user_id . '" AND `post_url` = "'  . $post_url . '"';
        if (DB::getInstance()->query($sql)->count() > 0) { // yes, it's saved
            return true;
        }
        return false;
    }

    public static function howManySaved($user_id){
        $sql = 'SELECT * from users_posts WHERE `user_facebook_id` = "' . $user_id . '" ';
        return DB::getInstance()->query($sql)->count();
    }

    public static function hasClicked($user_ip, $post_url){
        $sql = 'SELECT * from exit_log WHERE `ip_address` = "' . $user_ip . '" AND `exit_url` = "'  . $post_url . '"';
        if (DB::getInstance()->query($sql)->count() > 0) { // yes, that user clicked that post.
            return true;
        }
        return false;    
    }

    public static function toggleFavorite($user_id, $blog_id){
        if (self::isFavorite($user_id, $blog_id)) { // blog is a favorite, remove
            $sql = 'DELETE FROM users_blogs WHERE `user_facebook_id` = "' . $user_id . '" AND `blog_id` = "'  . $blog_id . '"';
            DB::getInstance()->query($sql);
        } else { // blog is not a favorite. Add new record
            DB::getInstance()->insert('users_blogs', array(
                    'user_facebook_id'  =>  $user_id , 
                    'blog_id'           =>  $blog_id 
                ));
        }
    }

    public static function toggleSaved($user_id, $post_url){
        if (self::isSaved($user_id, $post_url)) { // blog is a favorite, remove
            $sql = 'DELETE FROM users_posts WHERE `user_facebook_id` = "' . $user_id . '" AND `post_url` = "'  . $post_url . '"';
            DB::getInstance()->query($sql);
        } else { // blog is not a favorite. Add new record
            DB::getInstance()->insert('users_posts', array(
                    'user_facebook_id'  =>  $user_id , 
                    'post_url'          =>  $post_url 
                ));
        }
    }

}

/*This function serves to normalize the outputs from the queries 
*so that columnists get treated as bloggers*/
function NormalizeResults($input = array())
{
    $output = array();
    foreach ($input as $key => $post) 
    {
        if (empty($post->blog_id)) {
            $post->blog_id = $post->col_shorthand ;
            $post->blog_author_twitter_username = $post->col_author_twitter_username ;
            $post->blog_name = $post->col_name ;
            // optional
            if (!empty($post->col_description)) {
                $post->blog_description = $post->col_description;
            }
            if (!empty($post->col_tags)) {
                $post->blog_tags = $post->col_tags;
            }            
            if (!empty($post->col_home_page)) {
                $post->blog_url = $post->col_home_page;
            }        
        }
        array_push($output, $post);
    }
    return $output;
}