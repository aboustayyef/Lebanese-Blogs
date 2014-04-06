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
    	<?php $results = $results[0] ;?>
	    <h1>Are you sure you want to delete the post: <h3>'<?php echo $results->post_title ;?>'?</h3><hr></h1>

    </div>

    <form action="do_delete.php" method="post">
      <div class="form-group ">
        <input type="hidden" name="post_id" value="<?php echo $results->post_id; ?>">
		<input id="submit" class ="btn btn-danger" type="submit" name="submit" value ="Erase the goddamn post">
		<a class= "btn btn-default" href="../">Cancel</a>		
        </div>
      </div>
    </form>
  </div>  
</body>

	<?php
}