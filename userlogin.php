<?php 
require_once('init.php');

/*
For Debugging:
echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/

$returnTo = $_GET['redirect'];

if (isset($_SESSION['LebaneseBlogs_Facebook_User_ID'])){ // if user is signed in
	
	goback($returnTo); // redirect to page. Will be using details from existing session

} else {
	$user = new Users;

	if ($user->error()){
		echo 'Error';
	} else {
		if (!$user->FacebookSignedIn()) {

			// save state parameter from Facebook login url
			$loginURL = $user->FacebookLoginURL();
			preg_match("/&state=(\\w+)/u", $loginURL, $matches);
			$_SESSION['fb_state'] = $matches[1];

// Below is the website we see before the user signs in
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Login</title>
	<link rel="stylesheet" href="css/login.css">
</head>

<body>
	<div class="login-box">
		<!-- <img src="img/interface/lb-apple-icon-114x114.png" width = "57px" alt="lebaneseblogs logo" class="logo"> -->
	<?php if ($_GET['from'] == 'favorites') {
		?>
		<img src="img/interface/favorites-featured.jpg" alt="Favorites">
		<p>Favorite Blogs is a great way to keep track of the blogs you care about most. Only see posts by bloggers you have designated as favorites</p>
		<h2>Log in to Access your Favorite Bloggers </h2>

		<?php
	} else if ($_GET['from'] == 'saved'){
		?>
		<img src="img/interface/saved-featured.jpg" alt="Saved">
		<p class ="strong">Saved Posts is a great way to keep awesome posts for future reference</p>
		<p>Don't lose that excellent infographic. Always have that recipe at hand. Keep that look around to steal later, and never forget that restaurant's address after reading that glowing review.</p>

		<?php } ?>
		
		<a class = "facebook_button" href ="<?php echo  $user->FacebookLoginURL();?>"></a>
		<p class="privacy">
			Privacy: Your email address will be used to communicate with you if anything goes wrong with your account. We will not share it with anyone.
		</p>

	</div>
	<!-- Start of StatCounter Code for Default Guide -->
                <script type="text/javascript">
                var sc_project=8489889; 
                var sc_invisible=1; 
                var sc_security="6ec3dc93"; 
                var scJsHost = (("https:" == document.location.protocol) ?
                "https://secure." : "http://www.");
                document.write("<sc"+"ript type='text/javascript' src='" +
                scJsHost +
                "statcounter.com/counter/counter.js'></"+"script>");</script>
                <noscript><div class="statcounter"><a title="web counter"
                href="http://statcounter.com/" target="_blank"><img
                class="statcounter"
                src="https://c.statcounter.com/8489889/0/6ec3dc93/1/"
                alt="web counter"></a></div></noscript>
            <!-- End of StatCounter Code for Default Guide -->

            <!-- Start of Google Analytics Code --> 
                <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                    ga('create', 'UA-40418714-1', 'lebaneseblogs.com');
                    ga('send', 'pageview');
                </script>
</body>
</html>



<?php
			

		} else {
			
			//User has now signed in to facebook. We now store the information from Facebook then redirect

		    // See if user exists in LB database
		    DB::getInstance()->get('users', array('user_facebook_id', '=', $user->getFacebookUserDetails('id')));
		    
		    if (DB::getInstance()->count() > 0) { // user exists
		    	// if user exists, increase database count
		    	
		    } else {
		    	// user is not in database. create user
		    	DB::getInstance()->insert('users', array(
		    		'user_facebook_id'	=>	$user->getFacebookUserDetails('id'),
		    		'user_first_name'	=> 	$user->getFacebookUserDetails('first_name'),
		    		'user_last_name'	=> 	$user->getFacebookUserDetails('last_name'),
		    		'user_email'		=> 	$user->getFacebookUserDetails('email'),
		    		'user_gender'		=>	$user->getFacebookUserDetails('gender'),
	    		));
		    }
		
			// Add Session Details
		    $_SESSION['LebaneseBlogs_Facebook_User_ID']= $user->getFacebookUserDetails('id');
	    	$_SESSION['LebaneseBlogs_user_id'] = Users::getIdFromFacebookId($user->getFacebookUserDetails('id'));
	    	$_SESSION['LebaneseBlogs_Facebook_FirstName'] = $user->getFacebookUserDetails('first_name');
	    	$_SESSION['lebaneseblogs_Facebook_Profile_Pic'] = 'http://graph.facebook.com/'.$user->getFacebookUserDetails('id').'/picture';

	    	// redirect using parameters from facebook login
	    	$params = "&code={$_GET['code']}&state={$_SESSION['fb_state']}";
	    	goback($returnTo);	

		}		
	}
}

// redirect function

function goback($returnTo=null){

	/* DEBUG 
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";*/

	$user_id = $_SESSION['LebaneseBlogs_Facebook_User_ID'];
	if (isset($_GET['action'])){

		switch ($_GET['action']) {
			case 'favorite':
				$blog_id = $_GET['blog'];
				Posts::toggleFavorite($user_id, $blog_id);
				header("location: ".$returnTo);
				break;
			
			case 'save':
				$post_url = urldecode($_GET['url']);
				Posts::toggleSaved($user_id, $post_url);
				header("location: ".$returnTo);
				break;

			default:
				header("location: ".$returnTo);
				break;

		}
	}else{
		header("location: ".$returnTo);
	}
}
?>