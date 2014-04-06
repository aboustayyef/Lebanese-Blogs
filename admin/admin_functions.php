<?php 

function admin_logged_in(){
	if (isset($_SESSION['admin'])) {
		return true;
	} else {
		return false;
	}
}

function draw_bootstrap_header($title = NULL){
	?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?></title>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
</head>
<?php }

?>