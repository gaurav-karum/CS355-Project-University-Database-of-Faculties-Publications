<?php

require_once 'core/init.php';

$user = new User();


if ($user->isLoggedIn()) { } else {
  Redirect::to('index.php');
}


// PHP FOR NEW PASSWORD
if (Input::exists() && isset($_POST['UpdateSubmit'])) {
  //if (Token::check(Input::get('token'))) {
  $validate = new Validate();
  $validate1 = new Validate();

  $validation = $validate->check($_POST, array(

    'l_pass' => array(), //
    'l_newpass' => array('max' => 40 , 'ofpattern' => "/^.*(?=.{7,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/"),
    'l_newpassrep' => array('matches' => 'l_newpass')
  ));

  if ($validation->passed()) {

    $validation1 = $validate1->checkfreg($user->data()->l_webmail);

    if (($user->data()->l_password) !== Input::get('l_pass')) {
      $validation1->addError("Wrong Password");
    }

    if ($validation1->passed()) {
      try {

        $update = DB::getInstance();
        $update->update(
          'login',
          'l_password',
          Input::get('l_pass'),
          array(
            'l_password' => Input::get('l_newpass')
          )
        );
        echo "<script type=\"text/javascript\">alert(\"Password Updated\");</script>";
      } catch (Exception $e) {
        die($e->getMessage());
      }
    } else {
      $err1 = $validation1->errors()[0] . '\n' . $validation1->errors()[1];
      echo "<script type='text/javascript'>alert('$err1');</script>";
    }
  } else {
    $err = $validation->errors()[0] . '\n' . $validation->errors()[1] . '\n' . $validation->errors()[2];
    //echo $err;
    echo "<script type='text/javascript'>alert('$err');</script>";
  }
  //}
}

// PHP FOR NEW USERNAME
if (Input::exists() && isset($_POST['UserSubmit'])) {
  //if (Token::check(Input::get('token'))) {
  $validate2 = new Validate();
  $validate3 = new Validate();

  $validation2 = $validate2->check($_POST, array(

    'l_pass' => array(),
    'Username' => array('unique' => 'login')
  ));

  if ($validation2->passed()) {

    $validation3 = $validate3->checkfreg($user->data()->l_webmail);

    if (($user->data()->l_password) !== Input::get('l_pass')) {
      $validation3->addError("Wrong Password");
    }

    if ($validation3->passed()) {
      try {

        $update = DB::getInstance();
        $update->update(
          'login',
          'l_password',
          Input::get('l_pass'),
          array(
            'l_username' => Input::get('Username')
          )
        );
        echo "<script type=\"text/javascript\">alert(\"Username Updated\");</script>";
      } catch (Exception $e) {
        die($e->getMessage());
      }
    } else {
      $err3 = $validation3->errors()[0] . '\n' . $validation3->errors()[1];
      echo "<script type='text/javascript'>alert('$err3');</script>";
    }
  } else {
    $err2 = $validation2->errors()[0] . '\n' . $validation2->errors()[1] . '\n' . $validation2->errors()[2];
    //echo $err;
    echo "<script type='text/javascript'>alert('$err2');</script>";
  }
  //}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User Profile</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-2 fixed-top">
    <div class="container">
      <a href="landingpage.php" class="navbar-brand px-2">DB</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item px-2">
            <a href="projects.php" class="nav-link">Projects</a>
          </li>
          <li class="nav-item px-2">
            <a href="conferences.php" class="nav-link">Conferences</a>
          </li>
          <li class="nav-item px-2">
            <a href="journals.php" class="nav-link">Journals</a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item px-2">
            <a href="profile.php" class="nav-link">
              <i class="fa fa-user"></i> <?php echo escape($user->data()->l_username); ?>
            </a>
          </li>

          <li class="nav-item px-2">
            <a href="logout.php" class="nav-link">
              <i class="fa fa-user-times"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header id="main-header" class="py-5 bg-info mt-5 text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1><i class="fa fa-user"></i> Settings</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light" style="min-height:648px">
    <div class="container">
      <div class="d-flex flex-column row-hl">
        <div class="p-4 item-hl">
          <a href="landingpage.php" class="btn btn-outline-dark btn-block">
            <i class="fa fa-arrow-left"></i> Back To Dashboard
          </a>
        </div>
        <div class="p-4 item-hl">
          <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#passwordModal">
            <i class="fa fa-lock"></i> Update Password
          </a>
        </div>
        <div class="p-4 item-hl">
          <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#usernameModal">
            <i class="fa fa-lock"></i> Update Username
          </a>
        </div>
      </div>
    </div>
  </section>

<!-- FOOTER -->
  <footer id="main-footer" class="bg-dark text-white mt-5 pt-3">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="text-muted text-center">1701CS21</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- PASSWORD MODAL -->
  <div class="modal fade" id="passwordModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Change Password</h5>
          <button class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <form action="profile.php" method="post">
            <div class="form-group">
              <label for="email">Faculty ID</label>
              <input type="text" id="email" class="form-control" required name="fregid">
            </div>
            <div class="form-group">
              <label for="oldpassword">Old Password</label>
              <input type="password" id="oldpassword" class="form-control" required name="l_pass">
            </div>
            <div class="form-group">
              <label for="newpassword">New Password</label>
              <input type="password" id="newpassword" class="form-control" required name="l_newpass" title="A good password contains at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
            </div>
            <div class="form-group">
              <label for="newpasswordrep">Repeat New Password</label>
              <input type="password" id="newpasswordrep" class="form-control" required name="l_newpassrep">
            </div>
            <input type="submit" class="btn btn-info btn-block" name="UpdateSubmit" value="Submit">
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        </div>
      </div>
    </div>
  </div>

  <!-- PASSWORD MODAL -->
  <div class="modal fade" id="usernameModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Change Username</h5>
          <button class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <form action="profile.php" method="post">
            <div class="form-group">
              <label for="email">Faculty ID</label>
              <input type="text" id="email" class="form-control" required name="fregid">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" class="form-control" required name="l_pass">
            </div>
            <div class="form-group">
              <label for="newusername">New Username</label>
              <input type="text" id="newusername" class="form-control" required name="Username">
            </div>
            <input type="submit" class="btn btn-info btn-block" name="UserSubmit" value="Submit">
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('editor1');
  </script>
</body>

</html>