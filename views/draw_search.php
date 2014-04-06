<?php 

$searchterm = trim($this->_searchTerm);

?>
<div id="searchresults" data-term ="<?php echo $searchterm; ?>">

	<h2>Search results for '<?php echo $searchterm ;?>'</h2>
	<hr>
	<div id="blognames">
		<h3 class ="status">Searching for Blogs with the phrase '<?php echo $searchterm  ?>'</h3>
		<img src="img/interface/ajax-loader.gif" alt="spinning wheel">
	</div>

	<div id="blogtitles">
		
	</div>

	<div id="blogcontents">
		
	</div>

	<div id="blogimages">
		
	</div>

</div>

<?php
?>