<?php 

// query to get the latest posts by blogger $this->_blogger;

	$blog_id = $data[0]->blog_id;

;?>
<div class="blogger">
	

<div id ="posts">
<?php
Render::drawFeaturedBlogger($blog_id);
Render::drawCards($data, 'blogger');
?>

</div> <!-- /posts -->
</div> <!-- /blogger -->