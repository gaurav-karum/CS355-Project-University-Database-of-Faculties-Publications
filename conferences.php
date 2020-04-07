<?php

require_once 'core/init.php';
$user = new User();

if ($user->isLoggedIn()) { } else {
  Redirect::to('index.php');
}

$fac = Input::get('cnametrend');
//  echo $fac;
$data = DB::getInstance();
$data->query("SELECT  c_year AS 'Year', COUNT(c_year) AS 'Contributions' FROM faculty, fac_publication,conferences WHERE faculty.f_name = '$fac' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id  GROUP BY conferences.c_year ORDER BY conferences.c_year LIMIT 10");


$dataPoints = array();
foreach ($data->results() as $row) {
  $point = array("label" => $row->Year, "y" => $row->Contributions);

  array_push($dataPoints, $point);
}
// print_r($dataPoints);

$dept = Input::get('cdeptrend');
// echo $fac;
$data2 = DB::getInstance();
$data2->query("SELECT  c_year AS 'Year', COUNT(c_year) AS 'Contributions' FROM faculty, fac_publication, conferences WHERE faculty.f_dept = '$dept' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id  GROUP BY conferences.c_year ORDER BY conferences.c_year LIMIT 10");


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
  <title>User Conferences</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <script>
    window.onload = function() {

      var chart = new CanvasJS.Chart("chartContainer", {
        title: {
          text: "Searched Faculty's Conference Publications"
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
          text: "Searched Dept Conferences Publications"
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
            <a href="conferences.php" class="nav-link active">Conferences</a>
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
        <div class="col-sm-12">
          <h1><i class="fa fa-folder px-2"></i> Conference Publications</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-6 ml-auto">
          <div class="input-group">
            <!-- <input type="text" class="form-control" placeholder="Search">
            <span class="input-group-btn">
              <button class="btn btn-warning">Search</button>
            </span> -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CONFERENCES -->
  <section id="posts">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4>Your Conference Publications</h4>
            </div>
            <div class="table-responsive">
              <?php
              $q = DB::getInstance();
              $user = new User();
              $web = $user->data()->l_webmail;

              $q->query("SELECT fac_publisher, c_title, c_year, c_city, c_doi FROM faculty, fac_publication, conferences WHERE faculty.f_webmail='$web' AND faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id ORDER BY conferences.c_year DESC");

              if ($q->count()) {
                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Organiser</th><th>Title</th><th>Year</th><th>City</th><th>DOI</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td><td>$row->c_city</td><td>$row->j_doi</td></tr>\n";
                }
                echo "</tbody></table>";
              }
              ?>
            </div>
          </div><br>

          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-search mr-3"></i>Search Your Conferences</h4>
            </div>

            <br>
            <form action="conferences.php">
              <div class="input-group">
                <input type="text" required name="CnameS" class="form-control" placeholder="Search By Conference Paper Title">
                <input type="submit" name="CnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CnameS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $cnames = DB::getInstance();
                $cname = Input::get('CnameS');
                $cnames->query("SELECT f_name,f_dept,fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND c_title LIKE '%$cname%' ORDER BY conferences.c_year DESC LIMIT 20");

                if ($cnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              } ?>
            </div>

            <br>
            <form action="conferences.php">
              <div class="input-group">
                <input type="text" required name="CcnameS" class="form-control" placeholder="Search By Conference Name / Publication Name ">
                <input type="submit" name="CcnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CcnameS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $ccnames = DB::getInstance();
                $ccname = Input::get('CcnameS');
                $ccnames->query("SELECT f_name,f_dept,fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND fac_publisher LIKE '%$ccname%' ORDER BY conferences.c_year DESC LIMIT 20");

                if ($ccnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($ccnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="conferences.php">
              <div class="input-group">
                <input type="text" required name="CyearS" class="form-control" placeholder="Search By Conference published in last X years ">
                <input type="submit" name="CyearSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CyearS')) > 0 && is_numeric(Input::get('CyearS'))) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $cyears = DB::getInstance();
                $cyear = Input::get('CyearS');
                $cyears->query("SELECT f_name,f_dept,fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND YEAR(CURRENT_DATE) - c_year <= $cyear ORDER BY conferences.c_year DESC LIMIT 20");

                if ($cyears->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cyears->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="conferences.php">
              <div class="input-group">
                <input type="text" required name="CfieldS" class="form-control" placeholder="Search By Field of Publication ">
                <input type="submit" name="CfieldSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CfieldS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $cfields = DB::getInstance();
                $cfield = Input::get('CfieldS');
                $cfields->query("SELECT f_name,fac_field,fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_webmail='$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND fac_publication.fac_field = '$cfield' ORDER BY conferences.c_year DESC LIMIT 20");

                if ($cfields->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cfields->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->fac_field</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div><br>
          </div><br>
        </div>
      </div>
    </div>
  </section> <br>

  <!-- WIKI -->
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h4>Wiki</h4>
          </div>
          <div>
            <canvas id="mycanvas" class="chartjs-render-monitor">
            </canvas>
          </div>
          <div>
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
          <form action="conferences.php">
            <div class="input-group">
              <input type="text" required name="cnametrend" class="form-control" placeholder="Enter Faculty name to get its Conference Trends">
              <input type="submit" name="Cnametrend" class="btn btn-secondary">
            </div>
          </form><br>
          <form action="conferences.php">
            <div class="input-group">
              <input type="text" required name="cdeptrend" class="form-control" placeholder="Enter dept to get its Conference trends">
              <input type="submit" name="Cdeptrend" class="btn btn-secondary">
            </div>
          </form><br>
          <div id="chartContainer" style="height: 300px; width: 100%;"></div>
          <br>
          <div id="deptContainer" style="height: 300px; width: 100%;"></div>
        </div>
      </div>
    </div>
  </div>

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

  <script type="text/javascript" src="js/canvasjs.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>
  <script src="js/yourconf.js"></script>
  <script src="js/yourdeptconf.js"></script>
  <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('editor1');
  </script>
</body>

</html>