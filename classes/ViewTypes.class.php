<?php 
class ViewTypes
{

  public static function registerView($view){

    if (!isset($_SESSION['currentView'])) {
      $_SESSION['currentView'] = 'cards'; // default
    }

    // This simply validates $view and defines what view we're on
    switch ($view) {
      case 'compact':
        $_SESSION['currentView'] = "compact";
        break;
      case 'timeline':
        $_SESSION['currentView'] = "timeline";
        break;
      case 'cards':
        $_SESSION['currentView'] = "cards";
      break;
    }
    return $_SESSION['currentView'];   
  }
}
?>