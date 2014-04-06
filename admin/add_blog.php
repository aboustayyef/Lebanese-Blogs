<?php 
require_once('../init.php');
require_once('admin_functions.php');

if (isset($_SESSION['admin'])) {
     # continue
 } else {
    header('location: login.php');
 }

if (isset($_POST['submit'])) {
    draw_full_form($_POST['blogurl']);
} else {
    draw_submit_blog_form();
}


function draw_submit_blog_form(){
    
    ?>
<html lang="en">
<?php draw_bootstrap_header('Submit Blog') ?><!--  -->
<body>
  <div class="container">
    <div class="col-sm-12">
      <h1>Submit blog<hr></h1>
    </div>

    <form action="" method="post">
      <div class="form-group ">
        <label for="blogurl" class="col-sm-2 control-label">Blog URL:</label>
        <div class="col-sm-10">
            <input id="blogurl" name ="blogurl" class ="form-control" type="text" placeholder="http://yourblog.com">
            <input id="submit" type="submit" name="submit" style ="display:none">
        </div>
      </div>
    </form>
  </div>  
</body>

<?php
}

function draw_full_form($url){

/*Try to extract some info from URL */
require_once('../classes/simple_html_dom.php');
$html = new simple_html_dom();
$html->load_file($url); 

//To get Meta Title
$meta_title = $html->find("title", 0)->plaintext;

//To get Meta Description
$meta_description = $html->find('meta[property="og:description"]', 0)->content;

//To get RSS Feed
$rss_feed = $html->find('link[type="application/rss+xml"]',0)->href;

draw_bootstrap_header('Fill the rest of this form');
;?>

<body>
  <div class="container">
    <div class="col-sm-12">
      <h1>Check the Details then submit blog<hr></h1>
    </div>

    <form action="submit.php" method="post">
      <div class="form-group ">
        
        <label for="blogid" class="col-sm-2 control-label">Choosse a Blog ID:</label>
        <div class="col-sm-10">
            <input id="blogid" name ="blogid" class ="form-control" type="text" placeholder = "oneWordID">
        </div>
        
        <label for="blogurl" class="col-sm-2 control-label">Blog URL:</label>
        <div class="col-sm-10">
            <input id="blogurl" name ="blogurl" class ="form-control" type="text" value = "<?php echo $url; ?>">
        </div>

        <label for="blogname" class="col-sm-2 control-label">Blog Name:</label>
        <div class="col-sm-10">
            <input id="blogname" name ="blogname" class ="form-control" type="text" value="<?php echo $meta_title; ?>">
        </div>

        <label for="blogdescription" class="col-sm-2 control-label">Blog Description:</label>
        <div class="col-sm-10">
            <input id="blogdescription" name ="blogdescription" class ="form-control" type="text" value="<?php echo $meta_description; ?>">
        </div>

        <label for="blogrss" class="col-sm-2 control-label">Blog RSS:</label>
        <div class="col-sm-10">
            <input id="blogrss" name ="blogrss" class ="form-control" type="text" value="<?php echo $rss_feed; ?>">
        </div>

        <label for="blogauthor" class="col-sm-2 control-label">Blog Author:</label>
        <div class="col-sm-10">
            <input id="blogauthor" name ="blogauthor" class ="form-control" type="text" placeholder="Author's name (optional) ">
        </div>

        <label for="blogtwitter" class="col-sm-2 control-label">Blog Twitter:</label>
        <div class="col-sm-10">
        <input id="blogtwitter" name ="blogtwitter" class ="form-control" type="text" placeholder="Author's Twitter address (without @) ">

        </div>
        <label for="blogtags" class="col-sm-2 control-label">Blog Tags:</label>
        <div class="col-sm-10">
            <input id="blogtags" name ="blogtags" class ="form-control" type="text" placeholder="politics, society (never trailing comas)">
            <br>        <input id="submit" class ="btn btn-large" type="submit" name="submit" >

        </div>
      </div>
    </form>
  </div>  
</body>

<?php

}

?>