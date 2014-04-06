<?php 

/**
*   This script draws the statistics page
*/

;?>
<div id="posts">
<?php 
	global $channel_descriptions;	
	global $db;
	$top5 = new Posts($db);
	$posts_12hours = $top5->get_Top_Posts($hours=12, $howmany = 5);
	$posts_7days = $top5->get_Top_Posts($hours=24*7, $howmany = 5);
	$posts_30days = $top5->get_Top_Posts($hours=24*7*30, $howmany = 5);
	$total_stats = array(
		"Most Popular Posts: last 12 hours"	=>	$posts_12hours,
		"Most Popular Posts: last 7 days"	=>	$posts_7days,
		"Most Popular Posts: last 30 days"	=>	$posts_30days
		);

	foreach ($total_stats as $description => $stats) {

?>

<div class ="card">
	<div class="card_header redheader">
		<h3 class ="whitefont"><?php echo $description; ?></h3>
	</div>
	<div class ="card_body elastic">
		
<?php

	foreach ($stats as $stat) {
		$img = $stat['post_image'];
		$img_height =$stat['post_image_height'] ;
		$img_width =$stat['post_image_width'] ;
		$title = $stat['post_title'];
		$url = $stat['post_url'];
		$blogger_url = WEBPATH . $stat['blog_id'];
		;?>
		
		<div class="list_type_a">
				<?php
				if (empty($stat['post_image'])) {
					;?>
						<a href ="<?php echo $url ;?>">
								<div class="thumb"><img src="<?php echo "img/interface/no-image.jpg" ;?>" height ="75px"></div>
						</a>
					<?php
				}else {
					if ($img_width >= $img_height) {
						;?>				
							<a href ="<?php echo $url ;?>">
									<div class="thumb"><img src="<?php echo $img ;?>" height ="75px"></div>
							</a>
						<?php
					} else {
						;?>
							<a href ="<?php echo $url ;?>">
									<div class="thumb"><img src="<?php echo $img ;?>" width = "75px"></div>
							</a>
						<?php
					}
				}
				?>
				<h4><a href ="<?php echo $url ;?>"><?php echo $title ;?></a></h4><h5><a href ="<?php echo $blogger_url; ?>"><?php echo $stat['blog_name'] ;?></a></h5>			
		</div>
		
		<?php
		//echo '<div class ="post_wrapper"><img src ="'.$img.'" width ="25"><div class ="info_wrapper"><a href ="'.$url.'"><h4>'.$title.'</h4></a></div>'.$post['blog_name'].'</div>';
	} echo "</div>";
	;?>
	
	<!-- <div class ="card_footer"></div> -->
	</div> 
<?php
}
?>
<div class="card">
	<div class="card_header tip"></div>
	<div class="card_body">
		<p>More Statistics coming soon, stay tuned</p>
	</div>
</div>
</div> <!-- posts -- >
