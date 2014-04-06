<?php 
 /**
  * This page loads, prepares and renders the blogger page
  * @author  Mustapha Hamoui <mustapha.hamoui@gmail.com>
  */

class StaticPage 
{
  protected $_pageDetails = array();   // Contains metadata for the html page 
  protected $_footerDetails = array();  // contains footer parameters

  function __construct()
  {
    $this->_pageDetails['googleFonts']  = GOOGLE_FONTS;
    $this->_pageDetails['leftColumn'] = "no";
    $this->_pageDetails['cssFile'] = "pages.css";
    $this->_footerDetails['statcounter']  = STATCOUNTER_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['analytics']  = ANALYTICS_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['jquery'] = JQUERY_SOURCE ; // Don't touch. Change from config.php only
  }

  function render(){
    $_SESSION['pageWanted'] = 'about';
    View::makeHeader($this->_pageDetails);
    View::makeStaticBody($this->_pageDetails);
    View::makeFooter($this->_footerDetails); // think of the parameters of footer

    // Draw the header, pass along the _pageTitle and _pageDescription and 

  }
}
?>