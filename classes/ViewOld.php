<?php 
 /**
 *  This class is reponsible for rendering the web page
 *	It takes initial data of $posts and $meta ($depth, title, description)
 */ 

class ViewOld
{
	protected $_page; // internal reference to page ("home", "about"..etc)
	protected $_view; // which view kind
	protected $_title; // title of a website
	protected $_left_column; // "yes" or "no";
	protected $_description; // description of page
	protected $_posts; //initial set of posts to display
	protected $_blogger; // if we're in the blogger page
	protected $_searchTerm;

	function __construct($pagewanted=null, $view=null, $channel=null, $bloggerid=null, $s=null) {
		
		/* Visits cookie */

		$expire=time()+60*60*24*30;
		if (isset($_COOKIE['lebaneseblogs_user_visits'])) {
		    // add a visit if cookie exists
		    $visits = $_COOKIE['lebaneseblogs_user_visits'];
		    setcookie('lebaneseblogs_user_visits', $visits+1, $expire);

		}else{
		    // create cookie and set it to 1
		     setcookie('lebaneseblogs_user_visits', '1', $expire);
		} 
		
		// Which page is this? is it a browse page? an about page or a 'top' page?
		if ((isset($pagewanted))) {
			$this->_page = $pagewanted;
		} else {
			$this->_page = "browse"; //default
		}

		$_SESSION['pagewanted']= $this->_page;

		if (($this->_page == "favorites" || $this->_page == "saved") && !isset($_SESSION['LebaneseBlogs_user_id'])) { 
			// This is a backdoor to the favorites or saved page, 
			// possibly from returning from signout
			// Best course is to redirect to homepage
			header("location: ".WEBPATH);
		}

		// does page have a left column?
		if ($this->hasLeftColumn($this->_page)) {
			$this->_left_column = "yes";
		} else {
			$this->_left_column = "no";
		}

		// 	If we are browsing posts, which view type are we using? (cards, timeline or compact?)
		if (($this->_page == "browse") || ($this->_page == "favorites") || ($this->_page == "saved")) {

			//	Is the view type set in the URL parameters?
			if ((isset($view)) && ($view !== "")) { // url parameter
				$this->_view = $view;
				$_SESSION['viewtype'] = $view;

			// if not, is it set in the Session? 
			} else if (isset($_SESSION['viewtype'])) { // session
				$this->_view = $_SESSION['viewtype'];

			// if not, is it set in a cookie?
			} else if (isset($_COOKIE["lblogs_default_view"])) {//cookie
				$this->_view = $_COOKIE["lblogs_default_view"];
				$_SESSION['viewtype'] = $this->_view;

			// if not, "cards" is the default
			} else{
				$expire=time()+60*60*24*30; 
				setcookie("lblogs_default_view", "cards", $expire); // "cards is the default view"
				$_SESSION['viewtype'] = "cards";
				$this->_view = "cards";
			}

			;?>		
			
			<?php

		// Which channel is being requested? (if any)
			if ((isset($channel)) && ($channel !== "")) {
				$this->_channel = $channel;
				$_SESSION['channel'] = $channel;
			} else {
				$this->_channel = "all"; //default
				$_SESSION['channel'] = NULL;
			}
		}

		if ($this->_page == 'blogger') {
			if (isset($bloggerid) && $bloggerid !=="") {
				$this->_blogger = $bloggerid;
			}else{
				die('No bloggerid specified');
			}
			
		}

		if ($this->_page == 'search'){
			if (isset($s) && !empty($s)) {
				$this->_searchTerm = $s;
			}else{
				die('you need to specify a search term');
			}
		}

	}

	function DrawHeader(){
		$this->SetTitleAndDescription();
		// depending on constructor outcomes, get $this->_title and $this->_description
		include_once(ABSPATH.'views/draw_header.php');
	}

	function DrawContent(){

		global $db;
		switch ($this->_page) {
			
			case 'about':
				include_once(ABSPATH.'views/draw_pages.php');
				break;

			case 'blogger':
				include_once(ABSPATH.'views/draw_blogger.php');
				break;

			case 'browse':
				// initialize general counter of all posts
				$_SESSION['posts_displayed'] = 0; // initialize number of posts shown
				$_SESSION['items_displayed'] = 0; // initialize number of items shown (including other widgets)
				

				// the compact mode gets more initial posts;
				if ($this->_view == "compact") {
					$initial_posts_to_retreive = 50;
				}else{
					$initial_posts_to_retreive = 20;
				}
				$data = Posts::get_latest_posts($initial_posts_to_retreive, $_SESSION['channel']); 
				
				//envelope the posts;
				echo '<div id="posts">';
					$this->display_posts($data);
				echo '</div> <!-- /posts -->';
				break;

			case 'favorites':

				if (isset($_SESSION['LebaneseBlogs_Facebook_User_ID'])){ // if user is signed in
					$_SESSION['posts_displayed'] = 0; // initialize number of posts shown
					$_SESSION['items_displayed'] = 0; // initialize number of items shown (including other widgets)

					// the compact mode gets more initial posts;
					if ($this->_view == "compact") {
						$initial_posts_to_retreive = 50;
					}else{
						$initial_posts_to_retreive = 20;
					}

					$data = Posts::get_favorite_bloggers_posts($_SESSION['LebaneseBlogs_Facebook_User_ID'], 0, $initial_posts_to_retreive); 
					
					if (count($data) == 0) { // no favorites yet
						?>
						<div id = "message">
							<?php include_once(ABSPATH.'views/favorites-starter.php') ?>
						</div>
						<?php
					} else {
						?><div id = "message" class = "primaryfont"><h3><?php echo $_SESSION['LebaneseBlogs_Facebook_FirstName'] ?>'s Favorite Blogs</h3></div><?php
						
						//envelope the posts;
						echo '<div id="posts">';
						$this->display_posts($data);
					}			
					echo '</div> <!-- /posts -->';
				} else {
					?><div id = "message" class = "primaryfont"><h3>You Are Now Signed out</h3></div><?php
				}
				break;
				case 'saved':

				if (isset($_SESSION['LebaneseBlogs_Facebook_User_ID'])){ // if user is signed in
					$_SESSION['posts_displayed'] = 0; // initialize number of posts shown
					$_SESSION['items_displayed'] = 0; // initialize number of items shown (including other widgets)

					// the compact mode gets more initial posts;
					if ($this->_view == "compact") {
						$initial_posts_to_retreive = 50;
					}else{
						$initial_posts_to_retreive = 20;
					}

					$data = Posts::get_saved_bloggers_posts($_SESSION['LebaneseBlogs_Facebook_User_ID'], 0, $initial_posts_to_retreive); 
					
					if (count($data) == 0) { // no saved yet
						?>
						<div id = "message">
							<?php include_once(ABSPATH.'views/saved-starter.php') ?>
						</div>
						<?php
					} else {
						?><div id = "message" class = "primaryfont"><h3><?php echo $_SESSION['LebaneseBlogs_Facebook_FirstName'] ?>'s Saved Posts</h3></div><?php
						
						//envelope the posts;
						echo '<div id="posts">';
						$this->display_posts($data);
					}			
					echo '</div> <!-- /posts -->';
				} else {
					?><div id = "message" class = "primaryfont"><h3>You Are Now Signed out</h3></div><?php
				}
				break;
			case 'search':
				include_once(ABSPATH.'views/draw_search.php');
				break;

			default:
				# code...
				break;
		}
		// left column ?
		// Dynamic or static content?
		// Infinite scrolling or not?
	}

	function DrawFooter(){
		include_once(ABSPATH.'views/draw_footer.php')	;
	}


// Assisting Functions
/********************************************************************************************************************************************/

	function SetTitleAndDescription(){ 

		// this method comes up with the title and description of the page depending on the page wanted ()
		// sets the values of $this->_title and $this->_description
		switch ($this->_page) {

			case 'browse':
				if ($this->_channel == "all") {
					$this->_title = "Lebanese Blogs | Latest posts from the best Blogs";
					$this->_description ="The best place to discover, read and organize Lebanon's top blogs";
				} else {
					$this->_title = "Top {$this->_channel} blogs in Lebanon | Lebanese Blogs";
					$this->_description ="The best place to discover, read and organize Lebanon's top {$this->_channel} bloggers";
				}
				break;
			
			case 'about':
				$this->_title = "Lebanese Blogs | About";
				$this->_description = "Get to know why Lebanese Blogs is so awesome";
				break;
			
			case 'top':
				$this->_title = "Lebanese Blogs | Top Posts";
				$this->_description = "Top posts today at Lebanese Blogs";
				break;

			case 'blogger':
				$blogName = Posts::get_blog_name($this->_blogger);
				if (!$blogName) {
					Die('This blogger does not exist');
				}
				$this->_title = "$blogName at Lebanese Blogs";
				$this->_description = "Posts of blog $blogName on Lebanese Blogs";
				break;

			case 'search':
				$this->_title = "Search result for $this->_searchTerm";
				$this->_description = "Search result for $this->_searchTerm";
				break;

			case 'favorites':
				if (isset($_SESSION['LebaneseBlogs_Facebook_User_ID'])) { //signed in to facebook
					$this->_title = "{$_SESSION['LebaneseBlogs_Facebook_FirstName']}'s Favorite Bloggers";
					$this->_description = "Favorite Blogs";
				}else{
					$this->_title = "Choose your favorite blogs";
					$this->_description = "Choose your favorite blogs";
				}
				break;
			case 'saved':
				if (isset($_SESSION['LebaneseBlogs_Facebook_User_ID'])) { //signed in to facebook
					$this->_title = "{$_SESSION['LebaneseBlogs_Facebook_FirstName']}'s Saved Posts";
					$this->_description = "Saved Posts";
				}else{
					$this->_title = "Save Posts";
					$this->_description = "Save Posts at Lebanese Blogs";
				}
				break;
			default:
				die('pagewanted is not set correctly! '.$this->_page);
				break;
		}
	}

	function display_posts($data){
		switch ($this->_view) {
			case 'cards':
				Render::drawCards($data);
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

/**
*   Utility functions
*********************************************************************************************************************************************/
 

	function contains_arabic($string){	//returns true if string has arabic characters

		if (preg_match("/([ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz]).+/", $string)){
			return false;
		} else {
			return true;
		}
	}

	function infinitescroll(){ // adds script in the end for inifite scrolling
		;?>	
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script>
			$.post('contr_get_extra_posts.php', { start_from:15, channel: "fashion"}, function (data) {
		  		$('body').append(data);	
		  	});
		</script>
		<?php
	}

	function hasLeftColumn($whichPage){
		$LeftColumns = array (
			'browse' 	=> "yes",
			'top'		=> "yes",
			'about'		=> "no",
			'search'	=> 	"no",
			'blogger'	=>	"no",
			'search'	=>	"no",
			'favorites'	=> 	"yes",
			'saved'		=>	"yes"
		);
		if (array_key_exists($whichPage, $LeftColumns)) {
			if ($LeftColumns[$whichPage] == "yes") {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE; // default is FALSE if it's not in the array
		}
	}

}
?>