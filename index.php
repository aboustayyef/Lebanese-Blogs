<?php 

/**
*   Index.php is the main routing file. It uses url parameters to know which class to load.
*   The main parameter is 'pagewanted', which takes us to different kinds of pages.
*
*   The valid 'pagewanted' parameters so far are: 
*   "browse" for home page that browses all the posts (optional requirements: 'view' & 'channel') 
*   "blogger" for pages of particular blogs (requirement: 'bloggerid')
*   "about" for the about page.
*   "search" for search page
*   "favorites" for favorite pages
*   "saved" for saved pages
*
*   @author Mustapha Hamoui <mustapha.hamoui@gmail.com>
*/ 


// initialize settings, session, database and load classes
include_once('init.php'); 

// User entry registration (cookie)
Lb_functions::registerEntry();

// get the main pagewanted parameter
$pagewanted = isset($_GET['pagewanted'])? $_GET['pagewanted'] : NULL;

// the default (null) is "browse"
if (($pagewanted == "browse") || ($pagewanted == NULL)) {

  // get the "browse"-specific parameters, view and channel
  $view = isset($_GET['view']) ? $_GET['view'] : NULL;
  $channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;

  // Initialize the browse controller, BrowsePage
  $browsePage = new BrowsePage($view, $channel);
  // .. then load it
  $browsePage->render();
  return;
}


// If the page is blogger
  if ($pagewanted == 'blogger') 
  {
    $bloggerid = isset($_GET['bloggerid']) ? $_GET['bloggerid'] : NULL;
    if (!$bloggerid) 
    {
      if (!empty($_SESSION['currentBlogger'])) {
        $bloggerid = $_SESSION['currentBlogger'];
      }else{
        die('blogger ID cant be empty');
      }
    }
    if (Posts::blogExists($bloggerid)) 
    {
      $bloggerPage = new BloggerPage($bloggerid);
      $bloggerPage->render();
    }else{
      die('This Blogger Doesn\'t exists');
    }
  return;
  }

if ($pagewanted == 'about') {
  $aboutPage = new AboutPage();
  $aboutPage->render();
  return;
}

if ($pagewanted == 'login') {
  
  $blogToFave   = isset($_GET['blogtofave']) ? $_GET['blogtofave'] : NULL;    // use the blogid / optional
  $urlToSave    = isset($_GET['urltosave']) ? $_GET['urltosave'] : NULL;       // use an encoded url / optional
  $redirectUrl  = isset($_GET['redirecturl']) ? $_GET['redirecturl'] : NULL; // use an encoded url / optional
  
  $loginPage = new LoginPage($blogToFave, $urlToSave, $redirectUrl);
  $loginPage->render();
  return;
}

if ($pagewanted == 'search') {
  $s = isset($_GET['s']) ? $_GET['s'] : NULL;

  /*Only search if $s is not empty */
  if ($s) {
    $searchPage = new SearchPage($s);
    $searchPage->render();
    return;
  }else{
    /* reroute to home page*/
    header("location: ".WEBPATH);
  }

}

if ($pagewanted == 'saved') {
  if (LbUser::isSignedIn()) {
      $view = isset($_GET['view']) ? $_GET['view'] : NULL;
      $savedPage = new SavedPage($view);
      $savedPage->render();
      return;
  } else {
      // build url to login page with a redirect url parameter to return to the page 'saved'
      $url = WEBPATH.'?pagewanted=login&redirecturl='.urlencode(WEBPATH.'?pagewanted=saved');
      header('Location: '.$url);
  }
}


if ($pagewanted == 'favorites') {
  if (LbUser::isSignedIn()) {
      $view = isset($_GET['view']) ? $_GET['view'] : NULL;
      $favoritesPage = new FavoritesPage($view);
      $favoritesPage->render();
      return;
  } else {
      // build url to login page with a redirect url parameter to return to the page 'favorites'
      $url = WEBPATH.'?pagewanted=login&redirecturl='.urlencode(WEBPATH.'?pagewanted=favorites');
      header('Location: '.$url);
  }
}

die();

// If the page is search




// If the page is saved
  $savedPage = new SavedPage($s);
  $savedPage->render();
  return;

  die('Parameter not properly set: pagewanted');
?>