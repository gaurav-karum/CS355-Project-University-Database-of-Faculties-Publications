<?php

require_once 'core/init.php';

$user = new User();


if ($user->isLoggedIn()) { } else {
  Redirect::to('index.php');
}


$fac = Input::get('nametrend');
// echo $fac;
$data = DB::getInstance();
$data->query("SELECT  j_year AS 'Year', COUNT(j_year) AS 'Contributions' FROM faculty, fac_publication, journals WHERE faculty.f_name = '$fac' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id  GROUP BY journals.j_year ORDER BY journals.j_year LIMIT 10");


$dataPoints = array();
foreach ($data->results() as $row) {
  $point = array("label" => $row->Year, "y" => $row->Contributions);

  array_push($dataPoints, $point);
}

$dept = Input::get('deptrend');
// echo $fac;
$data2 = DB::getInstance();
$data2->query("SELECT  j_year AS 'Year', COUNT(j_year) AS 'Contributions' FROM faculty, fac_publication, journals WHERE faculty.f_dept = '$dept' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id  GROUP BY journals.j_year ORDER BY journals.j_year LIMIT 10");


$dataPoints1 = array();
foreach ($data2->results() as $row) {
  $point1 = array("label" => $row->Year, "y" => $row->Contributions);

  array_push($dataPoints1, $point1);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User Journals</title>
  <style>
  </style>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">

  <script>
    window.onload = function() {

      var chart = new CanvasJS.Chart("chartContainer", {
        title: {
          text: "Searched Faculty's Journal Publications"
        },
        axisY: {
          minimum: 0,
          maximum: 10,

        },
        data: [{
          type: "column",
          indexLabel: "{y}",
          dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
      });

      function updateChart() {
        var color, deltaY, yVal;
        var dps = chart.options.data[0].dataPoints;

        chart.options.data[0].dataPoints = dps;
        chart.render();
      };
      updateChart();

      var chart1 = new CanvasJS.Chart("deptContainer", {
        title: {
          text: "Searched Dept Journal Publications"
        },
        axisY: {
          minimum: 0,
          maximum: 40,

        },
        data: [{
          type: "column",
          indexLabel: "{y}",
          dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
        }]
      });

      function updateChart1() {
        var color, deltaY, yVal;
        var dps = chart.options.data[0].dataPoints1;

        chart1.options.data[0].dataPoints1 = dps;
        chart1.render();
      };
      updateChart1();
    }
  </script>
 

</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-2 fixed-top">
    <div class="container">
      <a href="landingpage.php" class="navbar-brand">DB</a>
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
            <a href="journals.php" class="nav-link active">Journals</a>
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

  <header id="main-header" class="py-5 bg-info text-white mt-5">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1><i class="fa fa-pencil px-2"></i> Journal Publications</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-6 ml-auto">
          <!-- <div class="input-group">
            <input type="text" class="form-control" placeholder="Search">
            <span class="input-group-btn">
              <button class="btn btn-primary">Search</button>
            </span>
          </div> -->
        </div>
      </div>
    </div>
  </section>

  <!-- POSTS -->
  <section id="posts">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4>Your Journal Publications</h4>
            </div>
            <div class="table-responsive">
              <?php
              $q = DB::getInstance();
              $user = new User();
              $web = $user->data()->l_webmail;

              $q->query("SELECT fac_publisher, fac_issn, j_title, j_year,j_volume,j_issue,j_pages,j_doi FROM faculty, fac_publication, journals WHERE faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id ORDER BY journals.j_year DESC");

              if ($q->count()) {
                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Publisher</th><th>ISSN</th><th>Title</th><th>Year</th><th>Volume</th><th>DOI</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->fac_publisher</td><td>$row->fac_issn</td><td>$row->j_title</td><td>$row->j_year</td><td>$row->j_volume $row->j_issue $row->j_pages</td><td>$row->j_doi</td></tr>\n";
                }
                echo "</tbody></table>";
              }
              ?>
            </div>


          </div><br>


          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-search mr-3"></i>Search Your Journals</h4>
            </div>
            <br>
            <form action="journals.php">
              <div class="input-group">
                <input type="text" required name="JnameS" class="form-control" placeholder="Search By Journal Name">
                <input type="submit" name="JnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JnameS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $jnames = DB::getInstance();
                $jname = Input::get('JnameS');

                $jnames->query("SELECT f_name,f_dept,fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_webmail='$web' AND  faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND j_title LIKE '%$jname%' ORDER BY journals.j_year DESC LIMIT 20");

                if ($jnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="journals.php">
              <div class="input-group">
                <input type="text" name="JpnameS" required class="form-control" placeholder="Search By Publisher Name">
                <input type="submit" name="JpnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JpnameS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $jpnames = DB::getInstance();
                $jpname = Input::get('JpnameS');


                $jpnames->query("SELECT f_name,f_dept,fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE  faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND fac_publisher LIKE '%$jpname%' ORDER BY journals.j_year DESC LIMIT 20");

                if ($jpnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jpnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="journals.php">
              <div class="input-group">
                <input type="text" name="JyearS" required class="form-control" placeholder="Search By Journals published in last X years">
                <input type="submit" name="JyearSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JyearS') && is_numeric(Input::get('JyearS'))) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $jyears = DB::getInstance();
                $jyear = Input::get('JyearS');

                $jyears->query("SELECT f_name,f_dept,fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE  faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND YEAR(CURRENT_DATE) - j_year <= $jyear ORDER BY journals.j_year DESC LIMIT 20");

                if ($jyears->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jyears->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>


            <br>
            <form action="journals.php">
              <div class="input-group">
                <input type="text" name="JfieldS" required class="form-control" placeholder="Search By Field of Publication">
                <input type="submit" name="JfieldSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JfieldS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $jfields = DB::getInstance();
                $jfield = Input::get('JfieldS');

                $jfields->query("SELECT f_name,fac_field,fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_webmail='$web' AND  faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND fac_publication.fac_field LIKE '%$jfield%' ORDER BY journals.j_year DESC LIMIT 20");

                if ($jfields->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Field</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jfields->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->fac_field</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>
            <br>
          </div><br>
        </div>
      </div>
    </div>
  </section>
  <br>

  <div class="container">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h4>Your Wiki</h4>
          </div>
          <div id="">
            <canvas id="mycanvas" class="chartjs-render-monitor">
            </canvas>
          </div>
          <div id="">
            <canvas id="mydeptcanvas" class="chartjs-render-monitor">
            </canvas>
          </div>
        </div>
      </div>
    </div>
  </div><br>
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h4>Check Other's Wiki</h4>
          </div><br>
          <form action="journals.php">
            <div class="input-group">
              <input type="text" required name="nametrend" class="form-control" placeholder="Enter Faculty name to get its Journal Trends">
              <input type="submit" name="Jnametrend" class="btn btn-secondary">
            </div>
          </form><br>
          <form action="journals.php">
            <div class="input-group">
              <input type="text" required name="deptrend" class="form-control" placeholder="Enter dept to get its journal trends">
              <input type="submit" name="Jdeptrend" class="btn btn-secondary">
            </div>
          </form><br>
          <div id="chartContainer" style="height: 300px; width: 100%;"></div>
          <br>
          <div id="deptContainer" style="height: 300px; width: 100%;"></div>
        </div>
      </div>
    </div>
  </div>

  <footer id="main-footer" class="bg-dark text-white mt-5 pt-3">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="text-muted text-center">1701CS21</p>
        </div>
      </div>
    </div>
  </footer>
  
  <script type="text/javascript" src="js/canvasjs.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>
  <script src="js/utils.js"></script>
  <script src="js/yourjournal.js"></script>
  <script src="js/yourdeptj.js"></script>
  <script src="js/jother.js"></script>
  <script src="js/jdept.js"></script>


  <script>
    CKEDITOR.replace('editor1');
  </script>

</body>

</html>