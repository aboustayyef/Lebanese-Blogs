<?php 
	/* testing */
require_once('init.php');

$results = Posts::blogExists('hghaddarnl');

echo '<pre>';
print_r($results);
echo '</pre>';

?>