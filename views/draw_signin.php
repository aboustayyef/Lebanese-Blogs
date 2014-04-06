
<?php 

$user = new Users;

if ($user->error()){
	echo 'Error';
} else {
	if (!$user->FacebookSignedIn()) {
		echo 'User is not signed in, please sign in <a href ="' . $user->FacebookLoginURL() . '">here</a>';
	} else {
		echo $user->getFacebookUserDetails()['first_name'];
		echo '<a href ="' . $user->FacebookLogoutURL() . '">Sign Out</a>';

		// Add Session Details
	    $_SESSION['LebaneseBlogs_Facebook_User_ID']= $user->getFacebookUserDetails()['id'];

	    // See if user exists in LB database
	    DB::getInstance()->get('users', array('user_facebook_id', '=', $user->getFacebookUserDetails()['id']));
	    
	    if (DB::getInstance()->count() > 0) { // user exists
	    	// if user exists, increase database count
	    	
	    } else {
	    	// create user
	    	DB::getInstance()->insert('users', array(
	    		'user_facebook_id'	=>	$user->getFacebookUserDetails()['id'],
	    		'user_first_name'	=> 	$user->getFacebookUserDetails()['first_name'],
	    		'user_last_name'	=> 	$user->getFacebookUserDetails()['last_name'],
	    		'user_email'		=> 	$user->getFacebookUserDetails()['email'],
	    		'user_visit_count'	=> 1
    		));
	    	$_SESSION['LebaneseBlogs_user_id'] = DB::getInstance()->get('users', array('user_facebook_id','=',$user->getFacebookUserDetails()['id']))->results()->user_id;
	    }

   		echo '<pre>',print_r($_SESSION),'</pre>';


	}		
}


?>
<div class="" id="posts">
	<h2>Yey! This is the signin page</h2>
</div>