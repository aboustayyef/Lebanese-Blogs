<?php 
 /**
  * This page loads, prepares and renders the blogger page
  * @author  Mustapha Hamoui <mustapha.hamoui@gmail.com>
  */

class BloggerPage 
{
  protected $_pageDetails = array();   // Contains metadata for the html page 
  protected $_bloggerDetails = array();  // Contains blogger details and blooger posts
  protected $_footerDetails = array();  // contains footer parameters

  function __construct($bloggerid = NULL)
  {
    $this->_pageDetails['bloggerid']  = $bloggerid;
    $this->_bloggerDetails = $this->getBloggerDetails();
    $blog_name = $this->_bloggerDetails[0]->blog_name;
    $this->_pageDetails['googleFonts']  = GOOGLE_FONTS;
    $this->_pageDetails['title'] = "$blog_name at Lebanese Blogs";
    $this->_pageDetails['description'] = "blog $blog_name in Lebanese Blogs";
    $this->_pageDetails['leftColumn'] = "yes";
    $this->_pageDetails['cssFile'] = "lebaneseblogs.css";

    $this->_footerDetails['statcounter']  = STATCOUNTER_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['analytics']  = ANALYTICS_TRACKER; // Don't touch. Change from config.php only
    $this->_footerDetails['jquery'] = JQUERY_SOURCE ; // Don't touch. Change from config.php only

  }

  function render(){
    $_SESSION['pageWanted'] = 'blogger';
    $_SESSION['currentView'] = 'cards';
    $_SESSION['currentBlogger'] = $this->_pageDetails['bloggerid'];
    $this->getBloggerDetails(); // will populate $this->_postsData
    $this->initializeCounter(); // will initialize the counter of the cards

    View::makeHeader($this->_pageDetails);
    View::makeBloggerBody($this->_bloggerDetails);
    View::makeFooter($this->_footerDetails); // think of the parameters of footer

    // Draw the header, pass along the _pageTitle and _pageDescription and 

  }

  function initializeCounter(){
    // initialize general counter of all posts
    $_SESSION['posts_displayed'] = 0; // initialize number of posts shown
    $_SESSION['items_displayed'] = 0; // initialize number of items shown (including other widgets)
  }

  function getBloggerDetails(){
      // the compact mode gets more initial posts;
      return BloggerDetails::get_blogger_posts(20, $this->_pageDetails['bloggerid']);
  }

}
?>