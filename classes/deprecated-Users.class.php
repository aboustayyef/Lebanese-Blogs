<?php 

class Users{

	private $_facebook ; // facebook object
	private $_facebook_error ; 
	private $_user; // facebook user 
	private $_user_profile; // facebook user details array
	private $_loginUrl;
	private $_logoutUrl;

	public function __construct(){
		$this->FacebookInit();
	}

	//initialize facebook
	public function FacebookInit(){
		
		$this->_facebook = new Facebook(array(
		  'appId'  => '1419973148218767',
		  'secret' => '16a49abb2d49c364d06b72eae7c79c1a',
		//  'cookie' => true,    
		));

		$this->_user = $this->_facebook->getUser();
		if ($this->_user) {
		  try {
		    $this->_user_profile = $this->_facebook->api('/me');
		  } catch (FacebookApiException $e) {
		    $this->_facebook_error = $e->getResult();
		  	$this->_user = null;
		  }
		}
		if ($this->_user) {
		  $this->_logoutUrl = $this->_facebook->getLogoutUrl();
		} else {
		 $this->_loginUrl = $this->_facebook->getLoginUrl(
		 	array(
			    'scope' => 'email'
			));
		}
	}

	public function FacebookSignedIn(){
		if ($this->_user_profile) {
			return true;
		} else {
			return false;
		}
	}

	public function FacebookLoginURL(){
		return $this->_loginUrl ;
	}
	public function FacebookLogoutURL(){
		return 'facebooklogout.php' ;
	}
	public function error(){
		if ($this->_facebook_error) {
			return $this->_facebook_error;
		}
	}
	public function getFacebookUserDetails($item){
		return $this->_user_profile[$item];
	}

	public static function UserSignedIn(){
		if (isset($_SESSION['LebaneseBlogs_Facebook_User_ID'])) {
			return true;
		}else{
			return false;
		}
	}

	public static function getIdFromFacebookId($facebook_id){
		$users = DB::getInstance();
		$users->get('users', array('user_facebook_id','=',$facebook_id));
		$result = $users->results();
		return $result[0]->user_id;
	}

}

?>