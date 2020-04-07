<?php
require_once 'core/init.php';

if (Input::exists()) {
  if (Token::check(Input::get('token'))) {

    $validate = new Validate();
    $validate1 = new Validate();

    $validation = $validate->check($_POST, array(
      'Webmail' => array('unique' => 'login'),
      'Username' => array('min' => 2,  'max' => 20, 'unique' => 'login'),
      'Password' => array( 'max'=>40, 'ofpattern' => "/^.*(?=.{7,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/"
      ),
      'RepeatPassword' => array('matches' => 'Password')
    ));

    if ($validation->passed()) {
      $validation1 = $validate1->checkfac(); //check if correct id webmail is given 
   
      if ($validation1->passed()) {
        
        $user = new User();
        try {
          $user->create('login', array(
            'l_webmail' => Input::get('Webmail'),
            'l_username' => Input::get('Username'),
            'l_password' => Input::get('Password')
          ));
          echo "<script type='text/javascript'>alert('User Registered');</script>";
          //Redirect::to('index.php');
        } catch (Exception $e) {
          die($e->getMessage());
        }
      } else {
        $err1 = $validation1->errors()[0].$validation->errors()[1];
        echo "<script type='text/javascript'>alert('$err1');</script>";
        // }
        //echo '<script>myFunction1()</script>';
      }
    } else {
      $err = $validation->errors()[0].$validation->errors()[1].$validation->errors()[2].$validation->errors()[3];

      echo "<script type='text/javascript'>alert('$err');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>CS355 Project</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">

</head>

<body>

  <header id="main-header" class="py-5 bg-info text-white text-center">
    <div class="container">
      <div>
        <div>
          <h1>University Database</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row justify-content-end">
        <a href="index.php" class="col-2 btn btn-outline-dark btn-block align-self-end justify-content-end mx-3">Login</a>
      </div>
    </div>
  </section>


  <!-- SIGNUP FORM -->
  <section id="login">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="card">
            <div class="header">
              <p class="text-muted my-3 p-2">You need to have Faculty ID, Webmail provided by University to Signup</p> </div>
          </div><br>
          <div class="card">
            <div class="card-header ">
              <h4><i class="fa fa-user text-center mx-auto"></i> Sign Up</h4>
            </div>
            <div class="card-body">
              <form action="signup.php" method="post">
                <div class="form-group">
                  <label>Faculty ID<span class="m-1 text-primary">*</span></label>
                  <input type="text" class="form-control" required name="FacultyId">
                </div>
                <div class="form-group">
                  <label>Webmail<span class="m-1 text-primary">*</span></label>
                  <input type="text" class="form-control" required placeholder="xyz@iitp.ac.in" name="Webmail">
                </div>
                <div class="form-group">
                  <label>Username<span class="m-1 text-primary">*</span></label>
                  <input type="text" class="form-control" required name="Username">
                </div>
                <div class="form-group">
                  <label>Password<span class="m-1 text-primary">*</span></label>
                  <input type="password" class="form-control" title="A good password contains at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required name="Password">
                </div>
                <div class="form-group">
                  <label>Repeat Password<span class="m-1 text-primary">*</span></label>
                  <input type="password" class="form-control" required name="RepeatPassword">
                </div>

                <input type="submit" class="btn btn-info btn-block" value="Sign Up">
                <br>
                <p class="text-dark text-muted">Already have an account? <a href="index.php">Login Here</a></p>
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              </form>
              <span><a href="I"></a></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <br><br><br>
  <br><br><br>
  <br><br><br>

  <!-- FOOTER -->
  <footer id="main-footer" class="bg-dark text-white mt-5 p-3">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="lead text-center text-muted">CS355 Database Project - 1701CS21</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('editor1');
  </script>
  <!-- <script>
    function myFunction() {
      alert("<?php foreach ($validation->errors() as $key => $item) {
                echo "Error => $item\n";
              }?>");
    }
    function myFunction1() {
      alert("<?php foreach ($validation1->errors() as $key => $item) {
                echo "Error => $item\n";
              }?>");
    }
  </script> -->
</body>

</html>