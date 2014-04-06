<?php 
 /**
  * This page loads, prepares and renders the browse page
  * @author  Mustapha Hamoui <mustapha.hamoui@gmail.com>
  */

class SearchPage 
{
  protected $_pageDetails = array();   // Contains metadata for the html page 
  protected $_postsData = array();  // Contains the initial posts data. Initial because more posts will be loaded with inifinite loading.
  protected $_footerDetails = array();  // contains footer parameters

  function __construct($s)
  {
    $this->_pageDetails['googleFonts']  = GOOGLE_FONTS;
    $this->_pageDetails['title'] = 'Search Results for "'.$s.'"';
    $this->_pageDetails['description'] = 'Search Results for "'.$s.'"';
    $this->_pageDetails['searchTerm'] = $s;
    $this->_pageDetails['leftColumn'] = "yes";
    $this->_pageDetails['cssFile'] = "lebaneseblogs.css";

    $this->_footerDetails['statcounter']  = STATCOUNTER_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['analytics']  = ANALYTICS_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['jquery'] = JQUERY_SOURCE ; // Don't touch. Change from config.php only
    
    // The code below decides what the initial state of the "left column" is
    $this->_footerDetails['lefColumnInitialState'] = 'open';
    

  }

  function render(){
    $_SESSION['pageWanted'] = 'search';
    $_SESSION['searchTerm'] = $this->_pageDetails['searchTerm'];
    $this->initializeCounter(); // will initialize the counter of the cards
    
    View::makeHeader($this->_pageDetails);
    View::makeSearchBody($this->_pageDetails['searchTerm']);
    View::makeFooter($this->_footerDetails); // think of the parameters of footer

    // Draw the header, pass along the _pageTitle and _pageDescription and 

  }


  function initializeCounter(){
    // initialize general counter of all posts
    $_SESSION['posts_displayed'] = 0; // initialize number of posts shown
    $_SESSION['items_displayed'] = 0; // initialize number of items shown (including other widgets)
  }
}
?>