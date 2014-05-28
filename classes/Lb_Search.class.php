<?php 
/**
* 
*/
class Lb_Search
{
  public static function searchBlogNames($term)
  {
    $names = DB::getInstance();
    $term = addslashes($term);
    $sql = 'SELECT 
            blog_id, 
            blog_name, 
            blog_url, 
            blog_description 
            FROM blogs 
            WHERE MATCH(blog_name) 
            AGAINST ( \'"'.$term.'"\' IN BOOLEAN MODE) 
            UNION SELECT col_shorthand, col_name, col_home_page, col_description 
            FROM columnists 
            WHERE MATCH(col_name) AGAINST (\'"'.$term.'"\' IN BOOLEAN MODE)';        
    $names->query($sql);
    return $names->results();
  }

  public static function searchBlogTitles($term)
  {
    $names = DB::getInstance();
    $term = addslashes($term);
    $sql  = 'SELECT 
            posts.post_excerpt,
            blogs.blog_id,
            posts.post_title, 
            posts.post_url, 
            posts.post_timestamp, 
            blogs.blog_name, 
            columnists.col_name, 
            posts.post_image, 
            posts.post_image_height, 
            posts.post_image_width,
            columnists.col_shorthand,
            blogs.blog_author_twitter_username,
            columnists.col_author_twitter_username,
            posts.post_virality
            FROM posts 
            LEFT JOIN blogs ON posts.blog_id = blogs.blog_id 
            LEFT JOIN columnists ON posts.blog_id = columnists.col_shorthand
            WHERE MATCH(post_title) AGAINST (\'"'.$term.'"\' IN BOOLEAN MODE) 
            ORDER BY post_timestamp DESC LIMIT 100';
    $results = $names->query($sql)->results();
    //return $results;
    return self::NormalizeResults($results);
  }

  public static function searchBlogContents($term)
  {
    $names = DB::getInstance();
    $term = addslashes($term);
    $sql  = 'SELECT 
            posts.post_excerpt,
            blogs.blog_id,
            posts.post_title, 
            posts.post_url, 
            posts.post_timestamp, 
            blogs.blog_name, 
            columnists.col_name, 
            posts.post_image, 
            posts.post_image_height, 
            posts.post_image_width,
            columnists.col_shorthand,
            blogs.blog_author_twitter_username,
            columnists.col_author_twitter_username,
            posts.post_virality
            FROM posts 
            LEFT JOIN blogs ON posts.blog_id = blogs.blog_id 
            LEFT JOIN columnists ON posts.blog_id = columnists.col_shorthand
            WHERE MATCH(post_content) AGAINST (\'"'.$term.'"\' IN BOOLEAN MODE) 
            ORDER BY post_timestamp DESC LIMIT 100'; // limited to 100 to avoid crazy common words like 'lebanese'
    $results = $names->query($sql)->results();
    //return $results;
    return self::NormalizeResults($results);
  }

  Public Static function NormalizeResults($input = array())
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
}
?>