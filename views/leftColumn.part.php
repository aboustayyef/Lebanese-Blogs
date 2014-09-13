<?php ; 

function areWeInChannel($s){
  if ($s == $_SESSION['currentChannel']) {
      echo " selected ";
    }
}

?>
<div id="left-col-wrapper">
  <div class="left-col-inner">
    <div class="leftNav-dismiss"><i class ="fa fa-times-circle"></i></div>

<!-- <h4 class="sectionheader">Statistics </h4>
  <div class="label level1"><a href="<?php echo WEBPATH . '?pagewanted=top'; ?>"><i class ="icon-bar-chart"></i>Top Posts &amp; Blogs</a></div> -->

  <div id ="channels">
    <h4 class="sectionheader">User Area</h4>
    <?php 
        if (LbUser::isSignedIn()){ // user logged in
          ?>
        <div class="userBox">
          <img src="<?php echo LbUser::getFacebookProfilePic(); ?>" alt="" width ="50" height = "50" class="profilePic">
          <h4 class="userName"><?php echo LbUser::getFacebookFirstName(); ?></h4>
          <?php LbUser::showFacebookSignOutLink("Log Out") ?>
        </div>
        <?php
        } 
        ?>
        <div class = "label level1 <?php if ( $_SESSION['pageWanted']=='favorites') { echo 'selected';} ?>">
          <a href="<?php echo WEBPATH.'?pagewanted=favorites';?>"><i class ="fa fa-star"></i>My Favorite Bloggers</a>
        </div>
        <div class = "label level1 <?php if ( $_SESSION['pageWanted']=='saved') { echo 'selected';} ?>">
          <a href="<?php echo WEBPATH.'?pagewanted=saved';?>"><i class ="fa fa-list-alt"></i>My Reading List</a>
          <?php 
            if (LbUser::isSignedIn()) {
              echo '<span id ="readingListCounter"> (<span class="theFigure">'.LbUser::getCounterReadingList().'</span>) </span>';
            }
          ?>
        </div>
        
        <h4 class="sectionheader">Posts to Show</h4>
        <a href="<?php echo WEBPATH . '?channel=all'; ?>">
          <div class = " <?php areWeInChannel('all'); ?> label folder level1">
            <i class ="fa fa-home"></i>
            Show All
          </div>
        </a>
        <a href ="<?php echo WEBPATH . '?channel=columnists'; ?>">
          <div class = " <?php areWeInChannel('columnists'); ?> label level1">
            <i class="fa fa-quote-right"></i>
            Columnists 
          </div>
        </a>
        <a href ="<?php echo WEBPATH . '?channel=fashion'; ?>">
          <div class = " <?php areWeInChannel('fashion'); ?> label level1">
            <i class="fa fa-umbrella"></i>
            Fashion &amp; Style
          </div>
        </a>
        <a href ="<?php echo WEBPATH . '?channel=food'; ?>">
          <div class = " <?php areWeInChannel('food'); ?> label level1">
            <i class="fa fa-coffee"></i>
            Food &amp; Health
          </div>
        </a>
        <a href ="<?php echo WEBPATH . '?channel=society'; ?>">
          <div class = " <?php areWeInChannel('society'); ?> label level1">
            <i class="fa fa-smile-o"></i>
            Society &amp; Fun News
          </div> 
        </a>
        <a href ="<?php echo WEBPATH . '?channel=politics'; ?>">
          <div class = " <?php areWeInChannel('politics'); ?> label level1">
            <i class="fa fa-globe"></i>
            Politics &amp; Current Affairs
          </div>     
        </a>
        <a href ="<?php echo WEBPATH . '?channel=tech'; ?>">
          <div 
          class = " <?php areWeInChannel('tech'); ?> label level1">
          <i class="fa fa-laptop"></i>
          Tech &amp; Business
        </div>
      </a>
      <a href ="<?php echo WEBPATH . '?channel=media'; ?>">
        <div class = " <?php areWeInChannel('media'); ?> label level1">
          <i class="fa fa-music"></i>
          Music, TV &amp; Film
        </div>
      </a>
      <a href ="<?php echo WEBPATH . '?channel=design'; ?>">
        <div class = " <?php areWeInChannel('design'); ?> label level1">
          <i class="fa fa-picture-o"></i>
          Advertising &amp; Design
        </div>     
      </a> 

    </div>
    <!-- <div id ="viewtype">
      <h4 class="sectionheader">Posts Layout </h4>
      <div id="icons">
        
        <a href="<?php 
              $tempurl = WEBPATH.'?view=cards';
              if (!empty($_SESSION['pageWanted'])) {
                $tempurl .= "&pagewanted=".$_SESSION['pageWanted'];
              }
              echo $tempurl;
            ?>">
          <img src="<?php echo WEBPATH . 'img/interface/view-icon-card';
          if ($_SESSION['currentView']=='cards') {
            echo '-selected';
          }
          echo '.png' ;?>" width ="25" height ="25" alt=""></a>
          
          <a href="<?php 
              $tempurl = WEBPATH.'?view=timeline';
              if (!empty($_SESSION['pageWanted'])) {
                $tempurl .= "&pagewanted=".$_SESSION['pageWanted'];
              }
              echo $tempurl;
            ?>">
            <img src="<?php echo WEBPATH . 'img/interface/view-icon-timeline';
            if ($_SESSION['currentView']=='timeline') {
              echo '-selected';
            }
            echo '.png' ;?>" width ="25" height ="25" alt=""></a>

            <a href="<?php 
              $tempurl = WEBPATH.'?view=compact';
              if (!empty($_SESSION['pageWanted'])) {
                $tempurl .= "&pagewanted=".$_SESSION['pageWanted'];
              }
              echo $tempurl;
            ?>">  
              <img src="<?php echo WEBPATH . 'img/interface/view-icon-compact';
              if ($_SESSION['currentView']=='compact') {
                echo '-selected';
              }
              echo '.png' ;?>" width ="25" height ="25" alt=""></a>
            </div>
          </div> -->
          <div class="credits">
            Designed and Built by <br> <a href ="http://twitter.com/beirutspring">Mustapha Hamoui</a>
          </div>
        </div> <!-- /left-col-inner -->
</div> <!-- /left-col-wrapper -->