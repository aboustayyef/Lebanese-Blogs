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
	draw_page();
}


function draw_page(){
	
	?>
<html lang="en">
<?php draw_bootstrap_header('Admin Area') ?><!--  -->
<body>
  <div class="container">
	<div class="col-sm-12">
		<br>
		<a class="btn btn-default" href="../" role="button"> &larr; Go Home</a>
		<h1>Welcome Admin,<hr></h1>
	  	<ul>
		  <li><a href="add_blog.php">Add new blog</a></li>
		  <li><a href="#">Feature 2</a></li>
		  <li><a href="#">Feature 3</a></li>
	  	</ul>
	</div>
  </div>  
</body>

<?php
}
