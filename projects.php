<?php

require_once 'core/init.php';

$user = new User();


if ($user->isLoggedIn()) { } else {
  Redirect::to('index.php');
}

$fac = Input::get('pnametrend');
$data = DB::getInstance();
$data->query("SELECT p_status AS 'Status', COUNT(p_status) AS 'Contributions' FROM faculty, faculty_project, projects WHERE faculty.f_name='$fac' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id GROUP BY projects.p_status LIMIT 10");

$dataPoints = array();
foreach ($data->results() as $row) {
  $point = array("label" => $row->Status, "y" => $row->Contributions);

  array_push($dataPoints, $point);
}

$dept = Input::get('pdeptrend');
$data2 = DB::getInstance();
$data2->query("SELECT p_status AS 'Status', COUNT(p_status) AS 'Contributions' FROM faculty, faculty_project, projects WHERE faculty.f_dept='$dept' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id GROUP BY projects.p_status LIMIT 10");

$dataPoints1 = array();
foreach ($data2->results() as $row) {
  $point1 = array("label" => $row->Status, "y" => $row->Contributions);

  array_push($dataPoints1, $point1);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User Projects</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <script>
    window.onload = function() {

      var chart = new CanvasJS.Chart("chartContainer", {
        title: {
          text: "Searched Faculty's Projects Trends"
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
          text: "Searched Dept Projects Trends"
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
            <a href="projects.php" class="nav-link active">Projects</a>
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
          <h1><i class="fa fa-users px-2"></i> Projects</h1>
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

  <!-- PROJECTS -->
  <section id="posts">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h4>Your Projects</h4>
            </div>
            <div class="table-responsive">
              <?php
              $q = DB::getInstance();
              $user = new User();
              $web = $user->data()->l_webmail;

              $q->query("SELECT f_id, fp_fid, fp_pid, p_title, p_sponsor, p_status, p_budget, p_id, fp_position FROM faculty, faculty_project, projects WHERE faculty.f_webmail='$web' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id ORDER BY projects.p_id DESC");

              if ($q->count()) {
                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Sponsor</th><th>Title</th><th>Status</th><th>Budget</th><th>Position</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->p_sponsor</td><td>$row->p_title</td><td>$row->p_status</td><td>$row->p_budget</td><td>$row->fp_position</td></tr>\n";
                }
                echo "</tbody></table>";
              }
              ?>
            </div>


          </div><br>

          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-search mr-3"></i>Search Your Projects</h4>
            </div>
            <br>
            <form action="projects.php">
              <div class="input-group">
                <input type="text" required name="PnameS" class="form-control" placeholder="Search By Project Name">
                <input type="submit" name="PnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PnameS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $pnames = DB::getInstance();
                $pname = Input::get('PnameS');
                $pnames->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id, p_budget FROM faculty, faculty_project, projects WHERE faculty.f_webmail='$web' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND projects.p_title LIKE '%$pname%' ORDER BY projects.p_id DESC LIMIT 10");

                if ($pnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Title</th><th>Sponsor</th><th>Faculty</th><th>Dept</th><th>Position</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($pnames->results() as $row) {
                    echo "<tr><td>$row->p_title</td><td>$row->p_sponsor</td><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fp_position</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="projects.php">
              <div class="input-group">
                <input type="text" required name="PsnameS" class="form-control" placeholder="Search By Sponsor name ">
                <input type="submit" name="PsnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PsnameS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;
                $psnames = DB::getInstance();
                $psname = Input::get('PsnameS');
                $psnames->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id FROM faculty, faculty_project, projects WHERE faculty.f_webmail='$web' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND projects.p_sponsor LIKE '%$psname%' ORDER BY projects.p_id DESC LIMIT 10");

                if ($psnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Title</th><th>Sponsor</th><th>Faculty</th><th>Dept</th><th>Position</th></tr></thead>";
                  echo "<tbody>";


                  foreach ($psnames->results() as $row) {
                    echo "<tr><td>$row->p_title</td><td>$row->p_sponsor</td><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fp_position</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>


            <br>
            <form action="projects.php">
              <div class="input-group">
                <input type="text" required name="PbudgetS" class="form-control" placeholder="Search By Budget greater than = X">
                <input type="submit" name="PbudgetSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PbudgetS')) > 0) {
                $user = new User();
                $web = $user->data()->l_webmail;

                $pbudgets = DB::getInstance();
                $pbudget = Input::get('PbudgetS');
                $pbudgets->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id, p_budget FROM faculty, faculty_project, projects WHERE faculty.f_webmail='$web' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND projects.p_budget >= $pbudget ORDER BY projects.p_id DESC LIMIT 10");

                if ($pbudgets->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Title</th><th>Sponsor</th><th>Faculty</th><th>Dept</th><th>Position</th><th>Budget</th></tr></thead>";
                  echo "<tbody>";


                  foreach ($pbudgets->results() as $row) {
                    echo "<tr><td>$row->p_title</td><td>$row->p_sponsor</td><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fp_position</td><td>$row->p_budget</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>


          </div>
        </div>
      </div>
    </div>
  </section> <br>

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
          <form action="projects.php">
            <div class="input-group">
              <input type="text" required name="pnametrend" class="form-control" placeholder="Enter Faculty name to get its Projects Trends">
              <input type="submit" name="Pnametrend" class="btn btn-secondary">
            </div>
          </form><br>
          <form action="conferences.php">
            <div class="input-group">
              <input type="text" required name="pdeptrend" class="form-control" placeholder="Enter dept to get its Projects trends">
              <input type="submit" name="Pdeptrend" class="btn btn-secondary">
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
  <script src="js/yourpro.js"></script>
  <script src="js/yourdeptpro.js"></script>
  <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('editor1');
  </script>
</body>

</html>