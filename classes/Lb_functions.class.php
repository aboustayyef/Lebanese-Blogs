<?php 


/**
*   This File provides general static functions
*/ 


class Lb_functions {
	
	private static $tagrouter =  array(
		'fashion' 	=>	'fashion',
		'style'		=>	'fashion', 
		'food'		=>	'food',
		'health'	=>	'food',
		'society'	=>	'society',
		'politics'	=>	'politics',
		'tech'		=>	'tech',
		'business'	=>	'tech',
		'media'		=>	'media',
		'music'		=>	'media',
		'tv'		=>	'media',
		'film'		=>	'media',
		'advertising'	=>	'design',
		'design'	=>	'design'
		);


	function __construct()
	{
		# do nothing
	}

	// This function is loaded when users enter the website to count their visits. (perhaps more later)
	public static function registerEntry(){
		$expire=time()+60*60*24*30; // 1 month
		if (@isset($_COOKIE['lebaneseblogs_user_visits'])) {
		    // add a visit if cookie exists
		    $visits = $_COOKIE['lebaneseblogs_user_visits'];
		    setcookie('lebaneseblogs_user_visits', $visits+1, $expire);

		}else{
		    // create cookie and set it to 1
		     setcookie('lebaneseblogs_user_visits', '1', $expire);
		} 
	}

	static function tagtochannel($tag){
		if (null !== self::$tagrouter[$tag]) {
			return self::$tagrouter[$tag];
		}
		return 'TagError';
	}

	static function contains_arabic($string){	//returns true if string has arabic characters

		if (preg_match("/([ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz]).+/", $string)){
			return false;
		} else {
			return true;
		}
	}
	/**
	*   convert time from timestamp to
	*	a seconds, b minutes, c hours ..etc
	*	source: http://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
	*/ 

	static function hours_to_days($hours){
		$seconds = $hours * 3600;
		$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $seconds / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . $str . ($r > 1 ? 's' : '') ;
	        }
	    }

	}

	static function time_elapsed_string($ptime)
	{
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'min',
	                1                       =>  'sec'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
	        }
	    }
	}

	public static function limitWords($wordswanted, $phrase){
		$allWords = explode(" ", $phrase);
		$newPhrase = "";
		$suffix = "";
		foreach ($allWords as $key=>$word) {
			$newPhrase .= $word. " ";
			if ($key == $wordswanted-1) {
				$suffix = " ...";
				break;
			}
		}
		return trim($newPhrase).$suffix;
	}

	static function display_posts($data){
		switch ($_SESSION['viewtype']) {
			case 'cards':
				include_once(ABSPATH.'views/display_cards.php')	;
				break;
			
			case 'timeline':
				include_once(ABSPATH.'views/display_timeline.php');
				break;
			
			case 'compact':
				include_once(ABSPATH.'views/display_compact.php') ;
				break;
			
			default:
				# code...
				break;
		}
	}

static function get_image_format($imageUrl){
	$format = strtolower(substr($imageUrl, -3));
	if (($format == 'gif')||($format == 'png')) {
		return $format;
	} else {
		return "jpg";
	}
}

	static function map_keys($key){
		echo '<!-- MAP KEYS CALLED -->';
		if ($key == 0) { // show top posts
;?>
			<div class="card">
				<div class="card_header tip"></div>
				<div class="card_body">
					<p>Top Posts Now moved to the sidebar</p>
				</div>
			</div>
<?php
		} else if ($key == 7){
			;?>
			
			<div class="card">
				<div class="card_header tip"></div>
				<div class="card_body">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Incidunt, nobis, tenetur ad eos quia quae ut dolor voluptates et odio?</p>
				</div>
			</div>
			
			<?php
		} else if ($key % 15 == 0) {
			echo '<!--'.$key.' is a multiple of 15 -->';
			$_SESSION['items_displayed'] = $_SESSION['items_displayed'] + 1;
		} else if ($key == 4){
			;?>
						
			<div id="testcard" class="card">
				<div class="card_header redheader">
					<h3 class ="whitefont">Featured Bloggers</h3>
				</div>
				<div class="card_body elastic silverbody">
					<div class="list_type_b">
						<img src="img/thumbs/blogbaladi.jpg" alt="" class="thumb">
						<h3>Blog Baladi</h3>
						<div class ="button-wrapper"><a href="" class="btn btn-red btn-small">explore</a></div>
					</div>
					<div class="list_type_b">
						<img src="img/thumbs/beirutspring.jpg" alt="" class="thumb">
						<h3>Beirut Spring</h3>
						<div class ="button-wrapper"><a href="" class="btn btn-red btn-small">explore</a></div>
					</div>
					<div class="list_type_b">
						<img src="img/thumbs/beirutntsc.jpg" alt="" class="thumb">
						<h3>Beirut NTSC</h3>
						<div class ="button-wrapper"><a href="" class="btn btn-red btn-small">explore</a></div>
					</div>
				</div>
			</div>
						
			<?php			
		}
	}
}

?>
