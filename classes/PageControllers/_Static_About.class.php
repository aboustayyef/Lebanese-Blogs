<?php 
 /**
  * This page loads, prepares and renders the blogger page
  * @author  Mustapha Hamoui <mustapha.hamoui@gmail.com>
  */

class AboutPage extends StaticPage
{
  function __construct(){
  	parent::__construct();	
    $this->_pageDetails['title'] = "Lebanese Blogs | About";
    $this->_pageDetails['description'] = "Get to know why Lebanese Blogs is so awesome";
    $this->_pageDetails['whichPage'] = 'about.part.php';
  }
}
?>