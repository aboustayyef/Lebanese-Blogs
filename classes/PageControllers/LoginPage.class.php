<?php 
 /**
  * This page loads, prepares and renders the Login page
  * @author  Mustapha Hamoui <mustapha.hamoui@gmail.com>
  */

class LoginPage 
{
  protected $_pageDetails = array();   // Contains metadata for the html page 
  protected $_footerDetails = array();  // contains footer parameters
  protected $_loginDetails = array();


  function __construct($blogToFave, $urlToSave, $redirectUrl)
  {
    $this->_loginDetails['blogToFave']  = $blogToFave ;
    $this->_loginDetails['urlToSave'] = $urlToSave ;
    $this->_loginDetails['redirectUrl'] = $redirectUrl ;

    $this->_pageDetails['googleFonts']  = GOOGLE_FONTS;
    $this->_pageDetails['title'] = "Login to Lebanese Blogs";
    $this->_pageDetails['description'] = "Login to Lebanese Blogs to access cool features";
    $this->_pageDetails['leftColumn'] = "no";
    $this->_pageDetails['cssFile'] = "pages.css";
    $this->_pageDetails['showViewArea'] = "yes";

    $this->_footerDetails['statcounter']  = STATCOUNTER_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['analytics']  = ANALYTICS_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['jquery'] = JQUERY_SOURCE ; // Don't touch. Change from config.php only

  }

  function render(){
    $_SESSION['pageWanted'] = 'login';
    
    // if logged in to facebook, 
    if (LbUser::isSignedIn()) {
      //  --> perform action (save or fave) 
      /* Add blog to Favorite */
      if (!empty($this->_loginDetails['blogToFave'])) {
        $user = LbUser::getFacebookID();
        $blog = $this->_loginDetails['blogToFave'];
        Posts::toggleFavorite($user,$blog);
      }
      /* Save Post*/
      if (!empty($this->_loginDetails['urlToSave'])) {
        $user = LbUser::getFacebookID();
        $post = urldecode($this->_loginDetails['urlToSave']);
        Posts::toggleSaved($user, $post);
      }
      //  --> then redirects to requested page, defaulting to root if none given.
      $redirectTo = empty($this->_loginDetails['redirectUrl'])? WEBPATH : $this->_loginDetails['redirectUrl'];
      header("Location: ".urldecode($redirectTo));
    // else, go to login page;  
    } else {
      View::makeHeader($this->_pageDetails);
      View::makeLoginBody($this->_loginDetails);
      View::makeFooter($this->_footerDetails); 
    }
    // --> draw the page (which includes a login button with a url that carries the 3 variables)
  }

}
?>