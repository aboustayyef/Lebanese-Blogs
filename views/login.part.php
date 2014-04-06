<div class="loginPage">
  <?php 

  // This page is the static page of where the login button lives
  // it is passed along the $parameters array with keys 'urlToSave', 'blogToFave' & 'redirectUrl'

  // build the redirect part of the Facebook Login Button. It carries along the parameters so that after login is succesful
  // the instructions to favorite and save will be passed along

  // Prepare the Login button. Display later, through the body
  $loginRedirectUrl = WEBPATH.'?pagewanted=login';
  if (!empty($parameters['urlToSave'])) {
    $loginRedirectUrl .= '&urltosave='.urlencode($parameters['urlToSave']);
  }
  if (!empty($parameters['blogToFave'])) {
    $loginRedirectUrl .= '&blogtofave='.$parameters['blogToFave'];
  }
  if (!empty($parameters['redirectUrl'])) {
    $loginRedirectUrl .= '&redirecturl='.urlencode($parameters['redirectUrl']);
  }

?>
  <!-- The Page Itself -->
  <div class="modal_cover">
  </div>
  <div class ="modal_window_base">
  </div>
  <div class="modal_window">
    <a href ="<?php echo WEBPATH; ?>"><img src ="<?php echo WEBPATH.'/img/interface/lb-apple-icon-114x114.png' ?>" width="57px" height="auto"></a>
    <h3>Sign in to access extra features</h3>
    <hr class = "fancy-line">
    <div class="thebutton">
      <?php LbUser::showFacebookSigninButton($loginRedirectUrl); ?>
    </div>
    <ul class="feature_list">
      <li><i class ="fa fa-clock-o"></i> Read Posts Later</li>
      <li><i class ="fa fa-star"></i> Add Blogs to Favorites</li>
    </ul>
    <p class ="understated">We respect your privacy. We will not share your email address and we won't spam you. We only need it to get in touch with you if something is wrong with your account</p>
  </div>
