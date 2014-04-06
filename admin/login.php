<?php
require_once('../init.php');
require_once('admin_functions.php');
if (admin_logged_in())
{
  header("location:index.php");
} 

if ((isset($_POST['password'])) && (md5($_POST['password']) == 'be00623c0c1becdd97fc72facf745449')) 
{
  $_SESSION['admin']='be00623c0c1becdd97fc72facf745449';
    header("location:index.php");
} else {
    log_in_form();
}

?>

<?php 

function log_in_form(){
?>

<!doctype html>
<html lang="en">

<?php draw_bootstrap_header('Log in to Administration tools') ?>

<body>
  <div class="container">
    
    <div class="col-sm-12">
      <h1>Sign in To access Admin tools<hr></h1>
    </div>

    <form action="" method="post">
      <div class="form-group ">
        <label for="password" class="col-sm-1 control-label">PASSWORD</label>
        <div class="col-sm-11">
          <input id="password" name ="password" class ="form-control" type="password" placeholder="Enter your password">
        </div>
      </div>
    </form>
  </div>  
</body>

</html>

  
  <?php
}

?>