<?php 

/**
* This class is for the LB user. So far it's facebook specific.
*/

class LbUser
{
  /* The instance of the facebook object, to avoid having to initialize it again and again*/
  /* This is a singleton that uses the function FbInstance to be created */
  static $FacebookObject;


  /* Initialize Singleton facebook object*/
  static function FbInstance(){
    if (empty(self::$FacebookObject)) { // singleton is empty
      self::$FacebookObject = new Facebook(array( // initialize it as a new facebook object
        'appId'  => FACEBOOK_APP_ID,
        'secret' => FACEBOOK_APP_SECRET,
      ));
    }
    return self::$FacebookObject;
  }

  /* Is the user signed in to facebook? return true or false*/
  static function isSignedIn(){
    if (isset($_SESSION['facebook'])) { // user already signed in
      return TRUE;
    }else{ // user not signed in 
      $user = self::FbInstance()->getUser();
      if ($user) { 
        // successful first sign in. Initiate session data. 
        // Using session to limit calls to facebook api throughout code
        $details = self::FbInstance()->api('/me');
        $id = $details['id'];
        $_SESSION['facebook'] = array(
            'id'          =>    $id,
            'picture'     =>    "http://graph.facebook.com/$id/picture",
            'first_name'  =>    $details['first_name'],
        );
        // logged in user
        return TRUE;
      }else{
        return FALSE;
      }
    }
  }

  static function getFacebookID(){
    if (self::isSignedIn()) {
      return $_SESSION['facebook']['id']; 
    }else{
      return false;
    }
  }

  static function getFacebookProfilePic(){
    if (self::isSignedIn()) {
      return $_SESSION['facebook']['picture']; 
    }else{
      return false;
    }
  }
  
  static function getFacebookFirstName(){
    if (self::isSignedIn()) {
      return $_SESSION['facebook']['first_name']; 
    }else{
      return false;
    }
  }

  static function getCounterReadingList(){
    if (self::isSignedIn()) {
      return Posts::howManySaved($_SESSION['facebook']['id']); 
    }else{
      return false;
    }
  }

  static function showFacebookSigninButton($redirectTo=NULL){
    $loginConfig = array(
      'scope' => 'email', 
    );
    if (!empty($redirectTo)) {
      $loginConfig['next'] = $redirectTo;  
    }

    $loginUrl = self::FbInstance()->getLoginUrl($loginConfig);
    ?>
      <a href ="<?php echo $loginUrl ?>"><img src ="<?php echo WEBPATH.'img/interface/facebook_login_button_404x2.png'; ?>" width="202" height="auto"></a>
    <?php
  }

  static function showFacebookSignOutLink($text){
    $logoutUrl = self::FbInstance()->getLogoutUrl(array('next' => WEBPATH.'/facebooklogout.php'));
    ?>
      <a href ="<?php echo $logoutUrl; ?>" class ="signout"><?php echo $text ?></a>
    <?php
  }

  static function getUserDetails(){
    if (self::isSignedIn()) {
      return self::FbInstance()->api('/me');
    } else {
      return "No signed in users";
    }
  }



}

?>