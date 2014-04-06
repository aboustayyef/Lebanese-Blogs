<?php 

// This is the header section, it requires the $pageDetails array 
if (empty($pageDetails)) {
  die('cannot draw header without the pageDetails array');
}

?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    
    <meta charset="utf-8">
    <title><?php echo $pageDetails['title'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $pageDetails['description']?>">
    <meta name="author" content="Mustapha Hamoui">
    <meta property="og:image" content="http://lebaneseblogs.com/img/interface/facebook-og-image.jpg">

    <!-- Main CSS -->
    <link href="<?php echo WEBPATH ;?>css/<?php echo $pageDetails['cssFile'].'?'.time(); ?>" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" type="text/css" href="<?php echo WEBPATH ;?>css/font-awesome-4.0.3/css/font-awesome.min.css">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo WEBPATH ;?>img/interface/lb-apple-icon-144x144.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo WEBPATH ;?>img/interface/lb-apple-icon-114x114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo WEBPATH ;?>img/interface/lb-apple-icon-72x72.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo WEBPATH ;?>img/interface/lb-apple-icon-57x57.png">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo WEBPATH ;?>img/interface/favicon.ico"> 
    
    <!-- Google Font -->
    <?php 
    if ($pageDetails['googleFonts'] == "yes") {
      ?>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Bitter' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/droidarabickufi.css">
      <?php
    }

    ?>

  </head>


<!-- About Menu -->
<body>

  <!-- Facebook Javascript sdk -->
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '<?php echo FACEBOOK_APP_ID ;?>', // CHANGE THIS TO REAL APP ONCE ONLINE
      status     : true,
      xfbml      : true
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<!-- End of Facebook Javascript SDK -->
    <?php include_once ABSPATH."views/modules/modal.inc"; ?>
    <div class = "menu" id="menu-about">
      <ul>
        <a href="<?php echo WEBPATH.'about' ?>"><li>About</li></a>
        <a href="<?php echo WEBPATH.'about#submit' ?>"><li>Submit Your Blog</li></a>
        <a href="<?php echo WEBPATH.'about#feedback' ?>"><li>Submit Feedback</li></a>
        <a href="<?php echo WEBPATH.'blog'; ?>"><li>Our Blog</li></a>
        <a href="<?php echo WEBPATH.'admin'; ?>"><li class ="last" >Admin</li></a>

      </ul>
    </div>

<!-- Search Menu -->
    <div class="menu" id ="menu-search">
      <form method="get" action="<?php echo WEBPATH ;?>">
        <input type="hidden" name = "pagewanted" value = "search">
        <input id="search-box" type="text" name="s">
        <input type="submit" value ="go" style ="display:none;">
      </form>
        <p>Search thousand of lebanese blog posts. <span class ="strong lbred"><br>Tip:</span> try searching for stores or brands.</p>
    </div>

    <!-- Left Nav -->
    <?php if ($pageDetails['leftColumn'] == "yes") {
        include_once(ABSPATH.'views/leftColumn.part.php');
    } ?>

    <!-- rest of page -->
    <div id ="pagewrapper">
      <!-- Navigation -->
      <div class="mainbar-wrapper">
        <div class="mainbar">
          <!-- logo -->
          <?php if ($pageDetails['leftColumn'] == "yes") {
            ;?>
              <div id="hamburger"><i class ="fa fa-bars"></i></div>
            <?php
          } ?>
          
            <div id ="logo">
                <a href="<?php echo WEBPATH.'?channel=all' ;?>">
                <img class='desktop-logo' src ="<?php echo WEBPATH;?>img/interface/logo-horiz-white.png" >
                <img src ="<?php echo WEBPATH;?>img/interface/logo-horiz-white-mobile.png" alt="" class="mobile-logo">
              </a>
            </div>
            <div id="slogan" class="secondaryfont">The best place to discover, read and organize Lebanon's top blogs</div>
            <div class="nav-wrapper">
                <ul id ="menu-icons">
                  <li data-menu="menu-about"><i id ="info" class ="fa fa-info-circle"></i></li>
                  <li data-menu="menu-search"><i id ="search" class ="fa fa-search"></i></li>
                </ul>
            </div><!-- nav-wrapper -->
        </div> <!-- /mainbar -->
      </div>
      <?php 
        if (@$pageDetails['showViewArea'] != 'yes') {
          echo '<img class ="loader" src="img/interface/lb-loader-animated-big-red.gif">';
        }
      ?>
    <div id="content_wrapper"> <!-- the middle section between the header and the footer -->
    <div id ="view-area" 
    <?php 
      if (@$pageDetails['showViewArea'] != 'yes') {
        echo 'style ="opacity:0">';
      }
    ?>
