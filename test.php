<?php 
	/* testing */
require_once('init.php');
require_once('classes/scraping.class.php');

$object = new Scraper('http://shezshe.net/rain');


echo '<pre>';
print_r($object->getImagesFromHtml());
echo '</pre>';

?>