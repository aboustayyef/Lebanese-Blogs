<?php 

$howManyPosts = 40 ; // checks the latest ($howManyPosts) to see if there are duplicates

// Note: We keep starting over every time a post is deleted because a post may have multiple duplicates

// begin:
// get the latest ($howManyPosts) posts;
// loop through all posts, starting from latest added (use id in reverese)
  // first, dedupe by url
    // check if present url exists in database
    // make sure to use cleanUpUrl();
      // if it exists
        // 1- add number of visits of the found post to that of the present one
        // 2- delete present one
        // 3- start over
  // next, dedupe by post title
    // check if present title exists (make sure we use current blogger's id too)         
      // if it exists
        // 1- add number of visits of the found post to that of the present one
        // 2- delete present one
        // 3- start over



/*
This function cleans up a url before finding out if it exists
mainly, it removes http:// or https:// from the beginning
*/
function cleanUpUrl($url){
  $url = urldecode($url);

  // remove https or http from beginning;
  $pieces = parse_url($url);
  array_shift($pieces);

  // add possible queries (?=etc) or fragments(#=etc)
  if(!empty($pieces['query'])){
  $pieces['query'] = '?'.$pieces['query'];
  }
  if(!empty($pieces['fragment'])){
  $pieces['fragment'] = '#'.$pieces['fragment'];
  }

  $reconstructed_url = implode('', $pieces);
  return $reconstructed_url;
}

?>