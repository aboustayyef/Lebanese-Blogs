<?php 
 /**
  * This class serves to includes various parts of the Lebanese Blogs Web page
  *
  * @author  Mustapha Hamoui <mustapha.hamoui@gmail.com>
  */

class View
{
  public static function makeHeader($pageDetails){
    include_once(ABSPATH.'/views/header.part.php');
  }

  public static function makeBrowseBody($data){
    if (isset($_SESSION['currentChannel'])) {
      self::categoryHead($_SESSION['currentChannel']);
    }
    Render::drawData($data, 'normal');
  }

  public static function makeSearchBody($data)
  {
    echo '<div id="posts">';
      echo '<div class ="searchingFor" id ="placeHolder_blognames"><h4><i class="fa fa-refresh fa-spin"></i> Searching for Blog Names</h4></div>';
      echo '<div class ="searchingFor" id ="placeHolder_blogtitles"><h4><i class="fa fa-refresh fa-spin"></i> Searching for Blog Titles</h4></div>';
      echo '<div class ="searchingFor" id ="placeHolder_blogcontents"><h4><i class="fa fa-refresh fa-spin"></i> Searching for Blog Content</h4></div>';      
    echo '</div>';
    ?>
    <?php
  }


  public static function makeSavedBody($data){
    if (count($data) == 0) { // No favorites yet
      include_once(ABSPATH.'/views/saved-starter.part.php');
    }else{
      self::makeMessage('Hello '.$_SESSION['facebook']['first_name'].'! Here are the posts you marked for reading later');
      Render::drawData($data, 'normal');
    }
  }

  public static function makeFavoritesBody($data){
    if (count($data) == 0) { // No favorites yet
      include_once(ABSPATH.'/views/favorites-starter.part.php');
    }else{
      self::makeMessage('Hello '.$_SESSION['facebook']['first_name'].'! Here are the posts by your favorite bloggers.');
      Render::drawData($data, 'normal');
    }
  }

  public static function makeBloggerBody($data){
    include_once(ABSPATH.'/views/blogger.part.php');
  }

  public static function makeStaticBody($pageDetails){
    include_once(ABSPATH.'/views/'.$pageDetails['whichPage']);
  }

  public static function makeFooter($pageFooter){
    include_once(ABSPATH.'/views/footer.part.php');
  }

  public static function makeLoginBody($parameters){
    include_once(ABSPATH.'/views/login.part.php');
  }

  public static function makeMessage($theMessage){
    /*The div below comes after the View Area div*/
    echo '<div class ="bodyMessage">'.$theMessage.'</div>';
  }

  public static function categoryHead($theCategory){
    //echo '<div class ="categoryHeader"><i class ="fa fa-coffee"></i>'.$theCategory.'</div>';
  }

}

?>