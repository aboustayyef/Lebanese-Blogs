<?php 
require_once('../init.php');
require_once('admin_functions.php');

if (isset($_SESSION['admin'])) {
	 # continue
 } else {
	header('location: login.php');
 }

/*Make sure the postid Parameter exists*/
if (isset($_GET['postid']) && !empty($_GET['postid'])) {
	the_form();
} else {
	die('sorry, postid has to be set');
}

/* See if form is submitted , otherwise draw the form */
function the_form(){
	if (isset($_POST['submit'])) {
		# form is submitted. do modification logic here
	} else {
		draw_form($_GET['postid']);
	}
}

/*draw the form*/
function draw_form($postid){
	/*Check if post exists*/
	$connection = DB::getInstance();
	$results = $connection->get('posts', array('post_id','=',$postid))->results();
	if (count($results)>0) { // post exists
		form_html($results);
	}else{
		echo '<h2>Sorry, post does not exist</h2>';
	}
}


function form_html($results){
	?>
<html lang="en">
<?php draw_bootstrap_header('Edit Post'); ?>
<!-- We'll be editing: post_title, post_image, post_excerpt, post_timestamp, post_image_height, post_image_width -->
<body>
  <div class="container">
    <div class="col-sm-12">
      <h1>Adjust details then hit submit<hr></h1>
      <?php $results = $results[0] ;?>
    </div>

    <form action="submit_post_changes.php" method="post">
      <div class="form-group ">

        <input type="hidden" name="post_id" value="<?php echo $results->post_id; ?>">
        
        <label for="post_title" class="col-sm-2 control-label">Post Title:</label>
        <div class="col-sm-10">
            <input id="post_title" name ="post_title" class ="form-control" type="text" value = "<?php echo $results->post_title ;?>">
        </div>
        
        <label for="post_excerpt" class="col-sm-2 control-label">Post Excerpt (if any)</label>
        <div class="col-sm-10">
            <textarea id="post_excerpt" name ="post_excerpt" class ="form-control" rows="5"> 
            	<?php echo trim($results->post_excerpt," \t\n\r\0\x0B"); ?> 
            </textarea>
        </div>

        <label for="post_timestamp" class="col-sm-2 control-label">Post Timestamp:</label>
        <div class="col-sm-10">
            <input id="post_timestamp" name ="post_timestamp" class ="form-control" type="text" value="<?php echo $results->post_timestamp ; ?>">
        </div>

        <label for="post_image" class="col-sm-2 control-label">Post Image: (if any)</label>
        <div class="col-sm-10">
            <input id="post_image" name ="post_image" class ="form-control" type="text" value = "<?php echo $results->post_image; ?>">
        </div>

        <label for="post_image_height" class="col-sm-2 control-label">Post Image Height</label>
        <div class="col-sm-10">
            <input id="post_image_height" name ="post_image_height" class ="form-control" type="text" value = "<?php echo $results->post_image_height; ?>">
        </div>

        <label for="post_image_width" class="col-sm-2 control-label">Post Image Width</label>
        <div class="col-sm-10">
            <input id="post_image_width" name ="post_image_width" class ="form-control" type="text" value = "<?php echo $results->post_image_width; ?>">
        </div>

		<input id="submit" class ="btn btn-large" type="submit" name="submit" >

        </div>
      </div>
    </form>
  </div>  
</body>

	<?php
}