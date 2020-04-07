<?php
require_once 'core/init.php';
//echo $token;
$user = new User();

/*   token validification not working for multiple forms on same page 
  token validification working and verified for single form on a page
  - just uncomment the if Token::check Input::get if there is only one form on the page
 */
if ($user->isLoggedIn()) { } else {
  Redirect::to('index.php');
}

/*  PHP FOR JOURNAL SUBMIT - MULTIPLE AUTHORS PER JOURNALS verified*/
if (Input::exists() && isset($_POST['jsubmit'])) {
  //if (Token::check(Input::get('token'))) {
  $validate = new Validate();
  $validate1 = new Validate();

  $validation = $validate->check($_POST, array(
    'facpublisher' => array('max' => 250),
    'jyear' => array('range' => 1970),
    'facissn' => array('max' => 30),
    'facrank' => array('ofpattern' => "/^[1-9]$/"),
    'jtitle' => array('max' => 250),
    'jvolume' => array('max' => 10),
    'jissue' => array('max' => 15),
    'jpages' => array('max' => 15),
    'jdoi' => array('max' => 15),
  ));

  if ($validation->passed()) {
    $validation1 = $validate1->checkfreg($user->data()->l_webmail);

    if ($validation1->passed()) {
      try {
        $fid = $validate1->getfid();
        $jins = DB::getInstance();
        $jtitle = Input::get('jtitle');
        $jyear = Input::get('jyear');
        $volume = Input::get('jvolume');
        $jissue = Input::get('jissue');
        $jpages = Input::get('jpages');
        $jdoi = Input::get('jdoi');
        $jins->query("SELECT * FROM journals WHERE j_title LIKE '%$jtitle%' AND  j_year LIKE '%$jyear%' AND  j_volume LIKE '%$jvolume%' AND  j_issue LIKE '%$jissue%' AND  j_pages LIKE '%$jpages%' AND  j_doi LIKE '%$jdoi%' ");
        if ($jins->count()) {
          $jid = $jins->first()->j_id;
          $fpins = DB::getInstance();
          $fpins->insert(
            'fac_publication',
            array(
              'fac_fid' => $fid,
              'fac_jid' => $jid,
              'fac_issn' => Input::get('facissn'),
              'fac_field' => Input::get('facfield'),
              'fac_rank' => Input::get('facrank'),
              'fac_publisher' => Input::get('facpublisher')
            )
          );
          echo "<script type=\"text/javascript\">alert(\"Journal already present, Author added\");</script>";
        } else {
          $jinss = DB::getInstance();
          $jinss->insert(
            'journals',
            array(
              'j_title' => Input::get('jtitle'),
              'j_year' => Input::get('jyear'),
              'j_volume' => Input::get('jvolume'),
              'j_issue' => Input::get('jissue'),
              'j_pages' => Input::get('jpages'),
              'j_doi' => Input::get('jdoi')
            )
          );

          $jQ = $jinss->query("SELECT j_id FROM journals ORDER BY j_id DESC LIMIT 1");
          $jid = $jQ->first()->j_id;
          $fpins = DB::getInstance();
          $fpins->insert(
            'fac_publication',
            array(
              'fac_fid' => $fid,
              'fac_jid' => $jid,
              'fac_issn' => Input::get('facissn'),
              'fac_field' => Input::get('facfield'),
              'fac_rank' => Input::get('facrank'),
              'fac_publisher' => Input::get('facpublisher')
            )
          );
          echo "<script type=\"text/javascript\">alert(\"Journal added\");</script>";
        }
      } catch (Exception $e) {
        die($e->getMessage());
      }
    } else {
      $err1 = $validation1->errors()[0] . '\n' . $validation1->errors()[1];
      echo "<script type='text/javascript'>alert('$err1');</script>";
    }
  } else {
    $err = $validation->errors()[0] . '\n' . $validation->errors()[1] . '\n' . $validation->errors()[2] . '\n' . $validation->errors()[3] . '\n' . $validation->errors()[4] . '\n' . $validation->errors()[5] . '\n' . $validation->errors()[6] . '\n' . $validation->errors()[7] . '\n' . $validation->errors()[8];
    echo "<script type='text/javascript'>alert('$err');</script>";
  }
  //}
}

/*  PHP FOR CONFERENCES SUBMIT */
if (Input::exists() && isset($_POST['csubmit'])) {
  //if (Token::check(Input::get('token'))) {
  $validate2 = new Validate();
  $validate3 = new Validate();
  //echo "Here1";

  $validation2 = $validate2->check($_POST, array(
    'facpublisher' => array('max' => 250),
    'cyear' => array('range' => 1970),
    'facissn' => array('max' => 30),
    'facrank' => array('ofpattern' => "/^[1-9]$/"),
    'ctitle' => array('max' => 250),
    'cvolume' => array('max' => 10),
    'cissue' => array('max' => 15),
    'cpages' => array('max' => 15),
    'cdoi' => array('max' => 15),
    'ccountry' => array('max' => 20),
    'ccity' => array('max' => 20),
  ));
  //echo "Here2";

  if ($validation2->passed()) {
    $validation3 = $validate3->checkfreg($user->data()->l_webmail);
    if ($validation3->passed()) {
      try {
        //echo "Here4";
        // get current users fid
        $fid = $validate3->getfid();
        // echo $fid;
        // insert user into login table 
        $cins = DB::getInstance();
        $ctitle = Input::get('ctitle');
        $cyear = Input::get('cyear');
        $cvolume = Input::get('cvolume');
        $cissue = Input::get('cissue');
        $cpages = Input::get('cpages');
        $cdoi = Input::get('cdoi');
        $ccountry = Input::get('ccountry');
        $ccity = Input::get('ccity');
        $cins->query("SELECT * FROM conferences WHERE c_title LIKE '%$ctitle%' AND  c_year LIKE '%$cyear%' AND  c_volume LIKE '%$cvolume%' AND  c_issue LIKE '%$cissue%' AND  c_pages LIKE '%$cpages%' AND  c_doi LIKE '%$cdoi%' AND c_country LIKE '%$ccountry%' AND c_city LIKE '%$ccountry%'");
        if ($cins->count()) {


          $cid = $cins->first()->c_id;
          // echo $cid;
          $fpcins = DB::getInstance();
          $fpcins->insert(
            'fac_publication',
            array(
              'fac_fid' => $fid,
              'fac_cid' => $cid,
              'fac_issn' => Input::get('facissn'),
              'fac_field' => Input::get('facfield'),
              'fac_rank' => Input::get('facrank'),
              'fac_publisher' => Input::get('facpublisher')
            )
          );
          echo "<script type=\"text/javascript\">alert(\"Conference Already present, Author added\");</script>";
        } else {
          $cinns = DB::getInstance();
          $cinss->insert(
            'conferences',
            array(
              'c_title' => Input::get('ctitle'),
              'c_year' => Input::get('cyear'),
              'c_volume' => Input::get('cvolume'),
              'c_issue' => Input::get('cissue'),
              'c_pages' => Input::get('cpages'),
              'c_doi' => Input::get('cdoi'),
              'c_country' => Input::get('ccountry'),
              'c_city' => Input::get('ccity')
            )
          );
          // echo "Here5";
          $cQ = $cinss->query("SELECT c_id FROM conferences ORDER BY c_id DESC LIMIT 1");
          $cid = $cQ->first()->c_id;
          // echo $cid;
          $fpcins = DB::getInstance();
          $fpcins->insert(
            'fac_publication',
            array(
              'fac_fid' => $fid,
              'fac_cid' => $cid,
              'fac_issn' => Input::get('facissn'),
              'fac_field' => Input::get('facfield'),
              'fac_rank' => Input::get('facrank'),
              'fac_publisher' => Input::get('facpublisher')
            )
          );
          echo "<script type=\"text/javascript\">alert(\"Conference Added\");</script>";
        }
        // 
      } catch (Exception $e) {
        //echo "Here8";
        die($e->getMessage());
      }
    } else {
      $err3 = $validation3->errors()[0] . '\n' . $validation3->errors()[1];
      echo "<script type='text/javascript'>alert('$err3');</script>";
    }
  } else {
    $err2 = $validation2->errors()[0] . '\n' . $validation2->errors()[1] . '\n' . $validation2->errors()[2] . '\n' . $validation2->errors()[3] . '\n' . $validation2->errors()[4] . '\n' . $validation2->errors()[5] . '\n' . $validation2->errors()[6] . '\n' . $validation2->errors()[7] . '\n' . $validation2->errors()[8] . '\n' . $validation2->errors()[9] . '\n' . $validation2->errors()[10];

    echo "<script type='text/javascript'>alert('$err2');</script>";
  }
  // }
}

/*  PHP FOR PROJECT SUBMISSION */
if (Input::exists() && isset($_POST['psubmit'])) {
  //if (Token::check(Input::get('token'))) {
  $validate4 = new Validate();
  $validate5 = new Validate();
  //echo "Here1";

  $validation4 = $validate4->check($_POST, array(
    'pbudget' => array('max' => 10),
    'pduration' => array('max' => 10),
    'psponsor' => array('max' => 50),
    'ptitle' => array('max' => 100)
  ));
  //echo "Here2";

  if ($validation4->passed()) {
    $validation5 = $validate5->checkfreg($user->data()->l_webmail);
    if ($validation5->passed()) {
      try {
        //echo "Here4";
        $fid = $validate5->getfid();

        $pins = DB::getInstance();
        $ptitle = Input::get('ptitle');
        $psponsor = Input::get('psponsor');
        $prostat = Input::get('prostat');
        $pins->query("SELECT * from projects WHERE p_title LIKE '%$ptitle%' AND p_sponsor LIKE '%$psponsor%' AND p_status LIKE '%$prostat%'");

        if ($pins->count()) {
          $pid = $pins->first()->p_id;
          $fproins = DB::getInstance();
          $fproins->insert(
            'faculty_project',
            array(
              'fp_fid' => $fid,
              'fp_pid' => $pid,
              'fp_position' => Input::get('projectpos')
            )
          );
          //echo "Here7";
          echo "<script type=\"text/javascript\">alert(\"Project already present, Contributor added\");</script>";
        } else {
          // echo $fid;
          $pinss = DB::getInstance();
          $pinss->insert(
            'projects',
            array(
              'p_title' => Input::get('ptitle'),
              'p_budget' => Input::get('pbudget'),
              'p_duration' => Input::get('pduration'),
              'p_sponsor' => Input::get('psponsor'),
              'p_status' => Input::get('prostat'),
            )
          );
          // echo "Here5";
          $pQ = $pinss->query("SELECT p_id FROM projects ORDER BY p_id DESC LIMIT 1");
          $pid = $pQ->first()->p_id;
          // echo $cid;
          $fproins = DB::getInstance();
          $fproins->insert(
            'faculty_project',
            array(
              'fp_fid' => $fid,
              'fp_pid' => $pid,
              'fp_position' => Input::get('projectpos')
            )
          );
          //echo "Here7";
          echo "<script type=\"text/javascript\">alert(\"Project entered\");</script>";
        }



        // 
      } catch (Exception $e) {
        //echo "Here8";
        die($e->getMessage());
      }
    } else {
      $err5 = $validation5->errors()[0] . '\n' . $validation5->errors()[1];
      echo "<script type='text/javascript'>alert('$err5');</script>";
    }
  } else {
    $err4 = $validation4->errors()[0] . '\n' . $validation4->errors()[1] . '\n' . $validation4->errors()[2] . '\n' . $validation4->errors()[3];
    echo "<script type='text/javascript'>alert('$err4');</script>";
  }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User Homepage</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    #confselect:focus option:first-of-type {
      display: none;
    }

    #jourselect:focus option:first-of-type {
      display: none;
    }

    #proselect:focus option:first-of-type {
      display: none;
    }

    #prostatus:focus option:first-of-type {
      display: none;
    }
  </style>
</head>

<body>
  <!-- NAVBAR -->
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
            <a href="journals.php" class="nav-link">Journals</a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <!-- SHOW USERNAME - NOT FACULTY NAME -->
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

  <!-- HEADER -->
  <header id="main-header" class="py-5 bg-info text-white mt-5">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1><i class="fa fa-gear"></i> Dashboard</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <a href="#" class="btn btn-block btn-outline-dark" data-toggle="modal" data-target="#addProjectModal">
            <i class="fa fa-plus"></i> Add Project
          </a>
        </div>
        <div class="col-md-4">
          <a href="#" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addConferenceModal">
            <i class="fa fa-plus"></i> Add Conference
          </a>
        </div>
        <div class="col-md-4">
          <a href="#" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addJournalModal">
            <i class="fa fa-plus"></i> Add Journal
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN AND SIDEBAR -->
  <section id="posts">
    <div class="container">
      <div class="row">
        <!-- MAIN  -->
        <div class="col-md-9">
          <!-- JOURNALS -->
          <div class="card">
            <div class="card-header">
              <h4>Latest Journals</h4>
            </div>

            <div class="table-responsive">
              <?php
              $s = DB::getInstance();
              $s->query("SELECT f_name,f_dept,fac_rank,fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id ORDER BY journals.j_year DESC, fac_publication.fac_rank LIMIT 5");

              if ($s->count()) {
                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                echo "<tbody>";

                foreach ($s->results() as $row) {
                  echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                }
                echo "</tbody></table>";
              }
              ?>
            </div>
          </div>
          <br>
          <!-- SEARCH JOURNALS -->
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-search mr-3"></i>Search Journals</h4>
            </div>
            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="JnameS" class="form-control" placeholder="Search By Journal Name">
                <input type="submit" name="JnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JnameS')) > 0) {
                $jnames = DB::getInstance();
                $jname = Input::get('JnameS');

                $jnames->query("SELECT f_name,f_dept,fac_rank, fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND j_title LIKE '%$jname%' ORDER BY journals.j_year DESC, fac_publication.fac_rank ASC LIMIT 20");

                if ($jnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" name="JpnameS" required class="form-control" placeholder="Search By Publisher Name">
                <input type="submit" name="JpnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JpnameS')) > 0) {
                $jpnames = DB::getInstance();
                $jpname = Input::get('JpnameS');


                $jpnames->query("SELECT f_name,f_dept,fac_rank, fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND fac_publisher LIKE '%$jpname%' ORDER BY journals.j_year DESC, fac_publication.fac_rank ASC LIMIT 20");

                if ($jpnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jpnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" name="JyearS" required class="form-control" placeholder="Search By Journals published in last X years">
                <input type="submit" name="JyearSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JyearS') && is_numeric(Input::get('JyearS'))) > 0) {
                $jyears = DB::getInstance();
                $jyear = Input::get('JyearS');

                $jyears->query("SELECT f_name,f_dept,fac_rank, fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND YEAR(CURRENT_DATE) - j_year <= $jyear ORDER BY journals.j_year DESC, fac_publication.fac_rank ASC LIMIT 40");

                if ($jyears->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jyears->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" name="JfieldS" required class="form-control" placeholder="Search By Field of Publication">
                <input type="submit" name="JfieldSearch" class="btn btn-secondary">
              </div>
            </form>

            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JfieldS')) > 0) {
                $jfields = DB::getInstance();
                $jfield = Input::get('JfieldS');

                $jfields->query("SELECT f_name,f_dept, fac_field, fac_rank,fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND fac_publication.fac_field LIKE '%$jfield%' ORDER BY journals.j_year DESC, fac_publication.fac_rank ASC LIMIT 20");

                if ($jfields->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jfields->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" name="JfnameS" required class="form-control" placeholder="Search By Faculty Name">
                <input type="submit" name="JfnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('JfnameS')) > 0) {
                $jfnames = DB::getInstance();
                $jfname = Input::get('JfnameS');

                $jfnames->query("SELECT f_name,f_dept,fac_rank, fac_publisher,j_title, j_year FROM faculty, fac_publication, journals WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id AND faculty.f_name LIKE '%$jfname%' ORDER BY journals.j_year DESC, fac_publication.fac_rank LIMIT 20");

                if ($jfnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($jfnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->j_title</td><td>$row->j_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>
          </div>
          <br>
          <!-- CONFERENCES -->
          <div class="card">
            <div class="card-header">
              <h4>Latest Conferences</h4>
            </div>

            <div class="table-responsive">
              <?php
              $c = DB::getInstance();
              $c->query("SELECT f_name,f_dept,fac_rank, fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id ORDER BY conferences.c_year DESC, fac_publication.fac_rank LIMIT 5");

              if ($c->count()) {
                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                echo "<tbody>";

                foreach ($c->results() as $row) {
                  echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                }
                echo "</tbody></table>";
              }
              ?>
            </div>
          </div>
          <br>
          <!-- SEARCH CONFERENCES -->
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-search mr-3"></i>Search Conferences</h4>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="CnameS" class="form-control" placeholder="Search By Conference Paper Title">
                <input type="submit" name="CnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CnameS')) > 0) {
                $cnames = DB::getInstance();
                $cname = Input::get('CnameS');
                $cnames->query("SELECT f_name,f_dept,fac_rank, fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND c_title LIKE '%$cname%' ORDER BY conferences.c_year DESC, fac_publication.fac_rank LIMIT 20");

                if ($cnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              } ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="CcnameS" class="form-control" placeholder="Search By Conference Name / Publication Name ">
                <input type="submit" name="CcnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CcnameS')) > 0) {
                $ccnames = DB::getInstance();
                $ccname = Input::get('CcnameS');
                $ccnames->query("SELECT f_name,f_dept,fac_rank, fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND fac_publisher LIKE '%$ccname%' ORDER BY conferences.c_year DESC, fac_publication.fac_rank LIMIT 20");

                if ($ccnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($ccnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="CyearS" class="form-control" placeholder="Search By Conference published in last X years ">
                <input type="submit" name="CyearSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CyearS')) > 0 && is_numeric(Input::get('CyearS'))) {
                $cyears = DB::getInstance();
                $cyear = Input::get('CyearS');
                $cyears->query("SELECT f_name,f_dept,fac_rank,fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND YEAR(CURRENT_DATE) - c_year <= $cyear ORDER BY conferences.c_year DESC, fac_publication.fac_rank LIMIT 20");

                if ($cyears->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cyears->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="CfieldS" class="form-control" placeholder="Search By Field of Publication ">
                <input type="submit" name="CfieldSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CfieldS')) > 0) {
                $cfields = DB::getInstance();
                $cfield = Input::get('CfieldS');
                $cfields->query("SELECT f_name,f_dept, fac_rank,fac_field, fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND fac_publication.fac_field = '$cfield' ORDER BY conferences.c_year DESC, fac_publication.fac_rank LIMIT 20");

                if ($cfields->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cfields->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="CfnameS" class="form-control" placeholder="Search By Faculty name ">
                <input type="submit" name="CfnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('CfnameS')) > 0) {
                $cfnames = DB::getInstance();
                $cfname = Input::get('CfnameS');
                $cfnames->query("SELECT f_name,f_dept,fac_rank,fac_publisher,c_title, c_year FROM faculty, fac_publication, conferences WHERE faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id AND faculty.f_name LIKE '%$cfname%' ORDER BY conferences.c_year DESC, fac_publication.fac_rank LIMIT 20");

                if ($cfnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Name</th><th>Dept</th><th>Author Rank</th><th>Publisher</th><th>Title</th><th>Year</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($cfnames->results() as $row) {
                    echo "<tr><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fac_rank</td><td>$row->fac_publisher</td><td>$row->c_title</td><td>$row->c_year</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>
          </div>
          <!-- PROJECTS -->
          <br>
          <div class="card">
            <div class="card-header">
              <h4>Latest Projects</h4>
            </div>
            <div class="table-responsive">
              <?php
              $p = DB::getInstance();
              $p->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id FROM faculty, faculty_project, projects WHERE faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id ORDER BY projects.p_id DESC LIMIT 10");

              if ($p->count()) {
                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Title</th><th>Sponsor</th><th>Faculty</th><th>Dept</th><th>Position</th></tr></thead>";
                echo "<tbody>";

                foreach ($p->results() as $row) {
                  echo "<tr><td>$row->p_title</td><td>$row->p_sponsor</td><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fp_position</td></tr>\n";
                }
                echo "</tbody></table>";
              }
              ?>
            </div>
          </div>
          <!-- SEARCH PROJECTS -->
          <br>
          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-search mr-3"></i>Search Projects</h4>
            </div>
            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="PnameS" class="form-control" placeholder="Search By Project Name">
                <input type="submit" name="PnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PnameS')) > 0) {
                $pnames = DB::getInstance();
                $pname = Input::get('PnameS');
                $pnames->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id, p_budget FROM faculty, faculty_project, projects WHERE faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND projects.p_title LIKE '%$pname%' ORDER BY projects.p_id DESC LIMIT 10");

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
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="PsnameS" class="form-control" placeholder="Search By Sponsor name ">
                <input type="submit" name="PsnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PsnameS')) > 0) {
                $psnames = DB::getInstance();
                $psname = Input::get('PsnameS');
                $psnames->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id FROM faculty, faculty_project, projects WHERE faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND projects.p_sponsor LIKE '%$psname%' ORDER BY projects.p_id DESC LIMIT 10");

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
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="PfnameS" class="form-control" placeholder="Search By Faculty name ">
                <input type="submit" name="PfnameSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PfnameS')) > 0) {
                $pfnames = DB::getInstance();
                $pfname = Input::get('PfnameS');
                $pfnames->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id FROM faculty, faculty_project, projects WHERE faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND faculty.f_name LIKE '%$pfname%' ORDER BY projects.p_id DESC LIMIT 10");

                if ($pfnames->count()) {
                  echo "<table class=\"table table-striped table-hover\">";
                  echo "<thead class=\"thead-inverse\">";
                  echo "<tr><th>Title</th><th>Sponsor</th><th>Faculty</th><th>Dept</th><th>Position</th></tr></thead>";
                  echo "<tbody>";

                  foreach ($pfnames->results() as $row) {
                    echo "<tr><td>$row->p_title</td><td>$row->p_sponsor</td><td>$row->f_name</td><td>$row->f_dept</td><td>$row->fp_position</td></tr>\n";
                  }
                  echo "</tbody></table>";
                }
              }
              ?>
            </div>

            <br>
            <form action="landingpage.php">
              <div class="input-group">
                <input type="text" required name="PbudgetS" class="form-control" placeholder="Search By Budget greater than = X">
                <input type="submit" name="PbudgetSearch" class="btn btn-secondary">
              </div>
            </form>
            <div class="table-responsive">
              <?php
              if (strlen(Input::get('PbudgetS')) > 0) {
                $pbudgets = DB::getInstance();
                $pbudget = Input::get('PbudgetS');
                $pbudgets->query("SELECT p_title, p_sponsor, f_name,f_dept,fp_position, p_id, p_budget FROM faculty, faculty_project, projects WHERE faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id AND projects.p_budget >= $pbudget ORDER BY projects.p_id DESC LIMIT 10");

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
          </div><br>
        </div>

        <!-- SIDEBAR -->
        <div class="col-md-3">
          <div class="card text-center bg-light text-black mb-3">
            <div class="card-body">
              <h5>My</h5>
              <h5>Journals</h5>
              <h1 class="display-5">
                <i class="fa fa-pencil py-2"></i>
              </h1>
              <a href="journals.php" class="btn btn-outline-dark btn-sm">View</a>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body">
              <h5>My</h5>
              <!-- dont change this to conferences -->
              <h5>Conference</h5>
              <h1 class="display-5">
                <i class="fa fa-folder-open-o py-2"></i>
              </h1>
              <a href="conferences.php" class="btn btn-outline-dark btn-sm">View</a>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body">
              <h5>My</h5>
              <h5>Projects</h5>
              <h1 class="display-5">
                <i class="fa fa-users py-2"></i>
              </h1>
              <a href="projects.php" class="btn btn-outline-dark btn-sm">View</a>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body">
              <h5>Funds </h5>
              <h5>Received</h5>
              <h2 class="display-5">
                <i class="fa fa-inr"></i>
              </h2><br>
              <div class="table-responsive">
                <?php
                $q = DB::getInstance();

                $user = new User();
                $web = $user->data()->l_webmail;
                $getdept = DB::getInstance();
                $res = $getdept->get('faculty', 'f_webmail', '=', $web);
                $dept = $res->first()->f_dept;

                $q->query("SELECT f_dept AS 'Dept', SUM(p_budget) AS 'budget' FROM faculty, faculty_project, projects WHERE faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id GROUP BY faculty.f_dept");

                echo "<table class=\"table table-striped table-hover\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Dept</th><th>Budget</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->Dept</td><td>$row->budget</td></tr>\n";
                }
                echo "</tbody></table>";
                ?>
              </div>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body text-center">
              <h5>Most Active</h5>
              <h5>Faculties</h5>
              <h5>Conference</h5>
              <h1 class="display-5">
                <i class="fa fa-pencil"></i>
              </h1>
              <div class="table table-responsive">
                <?php
                $q = DB::getInstance();

                $q->query("SELECT f_name AS 'Name', Count(fac_cid) AS 'Conferences' FROM faculty, fac_publication WHERE faculty.f_id = fac_publication.fac_fid GROUP BY faculty.f_name ORDER BY COUNT(fac_cid)  DESC LIMIT 3");

                echo "<table class=\"table table-striped table-hover no-cellpadding\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Faculty</th><th>Conferences</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->Name</td><td>$row->Conferences</td></tr>\n";
                }
                echo "</tbody></table>";
                ?>
              </div>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body text-center">
              <h5>Most Active</h5>
              <h5>Faculties</h5>
              <h5>Journals</h5>
              <h1 class="display-5">
                <i class="fa fa-folder-open-o"></i>
              </h1>
              <div class="table-responsive">
                <?php
                $q = DB::getInstance();

                $q->query("SELECT f_name AS 'Name', Count(fac_jid) AS 'Journals' FROM faculty, fac_publication WHERE faculty.f_id = fac_publication.fac_fid GROUP BY faculty.f_name ORDER BY COUNT(fac_jid)  DESC LIMIT 3");

                echo "<table class=\"table table-striped table-hover no-cellpadding\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Faculty</th><th>Journals</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->Name</td><td>$row->Journals</td></tr>\n";
                }
                echo "</tbody></table>";
                ?>
              </div>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body text-center">
              <h5>Most Active</h5>
              <h5>Faculties</h5>
              <h5>Projects</h5>
              <h1 class="display-5">
                <i class="fa fa-folder-open-o"></i>
              </h1>
              <div class="table-responsive">
                <?php
                $q = DB::getInstance();

                $q->query("SELECT f_name AS 'Name', Count(fp_pid) AS 'Projects' FROM faculty, faculty_project WHERE faculty.f_id = faculty_project.fp_fid GROUP BY faculty.f_name ORDER BY COUNT(fp_fid)  DESC LIMIT 3");

                echo "<table class=\"table table-striped table-hover no-cellpadding\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Faculty</th><th>Projects</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->Name</td><td>$row->Projects</td></tr>\n";
                }
                echo "</tbody></table>";
                ?>
              </div>
            </div>
          </div>

          <div class="card text-center bg-light text-dark mb-3">
            <div class="card-body text-center">
              <h5>Most Active</h5>
              <h5>Department</h5>
              <h1 class="display-5">
                <i class="fa fa-folder-open-o"></i>
              </h1>
              <div class="table-responsive">
                <?php
                $q = DB::getInstance();

                $q->query("SELECT f_dept AS 'Dept', Count(f_dept) AS 'Publications' FROM faculty, fac_publication WHERE faculty.f_id = fac_publication.fac_fid GROUP BY faculty.f_dept ORDER BY COUNT(f_dept)  DESC LIMIT 3");

                echo "<table class=\"table table-striped table-hover no-cellpadding\">";
                echo "<thead class=\"thead-inverse\">";
                echo "<tr><th>Dept</th><th>Publications</th></tr></thead>";
                echo "<tbody>";

                foreach ($q->results() as $row) {
                  echo "<tr><td>$row->Dept</td><td>$row->Publications</td></tr>\n";
                }
                echo "</tbody></table>";
                ?>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <footer id="main-footer" class="bg-dark text-white mt-5 pt-3">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="text-muted text-center">1701CS21</p>
        </div>
      </div>
    </div>
  </footer>


  <!-- PROJECT MODAL -->
  <div class="modal fade" id="addProjectModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Add Project</h5>
          <button class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <form action="landingpage.php" method="post">

            <div class="form-group">
              <label>Faculty ID<span class="m-1 text-primary">*</span> </label>
              <input type="text" class="form-control" name="fregid" required>
            </div>
            <div class="form-group">
              <label>Position in project<span class="m-1 text-primary">*</span></label>
              <select name="projectpos" id="proselect" class="form-control" required>
                <option>Position</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Co-supervisor">Co-supervisor</option>
              </select>
            </div>
            <div class="form-group">
              <label>Project Title<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="ptitle" required>
            </div>
            <div class="form-group">
              <label>Project Duration</label>
              <input type="text" class="form-control" name="pduration" placeholder="2010-2014">
            </div>
            <div class="form-group">
              <label>Project Budget</label>
              <input type="text" class="form-control" name="pbudget">
            </div>
            <div class="form-group">
              <label>Project Sponsor<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="psponsor" required>
            </div>
            <div class="form-group">
              <label>Project Status<span class="m-1 text-primary">*</span></label>
              <select name="prostat" id="prostatus" class="form-control" required>
                <option>Select current status</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
              </select>
            </div>

            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-info" name="psubmit">

          </form>
        </div>

      </div>
    </div>
  </div>


  <!-- CONFERENCE MODAL -->
  <div class="modal fade" id="addConferenceModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Add Conference Publications</h5>
          <button class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <form action="landingpage.php" method="post">

            <div class="form-group">
              <label>Faculty ID<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="fregid" required>
            </div>
            <div class="form-group">
              <label>Conference Name<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="facpublisher" required>
            </div>
            <div class="form-group">
              <label>Conference Year<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="cyear" required>
            </div>
            <div class="form-group">
              <label>Author Rank<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="facrank" required>
            </div>
            <div class="form-group">
              <label>Field<span class="m-1 text-primary">*</span></label>
              <select name="facfield" id="confselect" class="form-control" required>
                <option>Choose a Field</option>
                <option value="CSE">CSE</option>
                <option value="EE">EE</option>
                <option value="ME">ME</option>
                <option value="HS">HS</option>
                <option value="MSE">MSE</option>
              </select>
            </div>
            <div class="form-group">
              <label>Conference Paper Title<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="ctitle" required>
            </div>
            <div class="form-group">
              <label>Country</label>
              <input type="text" class="form-control" name="ccountry">
            </div>
            <div class="form-group">
              <label>City</label>
              <input type="text" class="form-control" name="ccity">
            </div>
            <div class="form-group">
              <label>Conference Proceedings Volume</label>
              <input type="text" class="form-control" name="cvolume">
            </div>
            <div class="form-group">
              <label>Conference Proceedings Issue</label>
              <input type="text" class="form-control" name="cissue">
            </div>
            <div class="form-group">
              <label>Conference Proceedings Pages</label>
              <input type="text" class="form-control" name="cpages">
            </div>
            <div class="form-group">
              <label>Conference Paper DOI</label>
              <input type="text" class="form-control" name="cdoi">
            </div>
            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-info" name="csubmit">

          </form>
        </div>

      </div>
    </div>
  </div>
  <!--JOURNAL MODAL -->
  <div class="modal fade" id="addJournalModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Add Journal Publication</h5>
          <button class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <form action="landingpage.php" method="post">
            <div class="form-group">
              <label>Faculty ID<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="fregid" required>
            </div>
            <div class="form-group">
              <label>Publisher Name<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="facpublisher" required>
            </div>
            <div class="form-group">
              <label>Published Year<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="jyear" required>
            </div>
            <div class="form-group">
              <label>Publisher ISSN</label>
              <input type="text" class="form-control" name="facissn">
            </div>
            <div class="form-group">
              <label>Author Rank<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="facrank" required>
            </div>
            <div class="form-group">
              <label>Field<span class="m-1 text-primary">*</span></label>
              <select name="facfield" id="jourselect" class="form-control" required>
                <option>Choose a Field</option>
                <option value="CSE">CSE</option>
                <option value="EE">EE</option>
                <option value="ME">ME</option>
                <option value="HS">HS</option>
                <option value="MSE">MSE</option>
              </select>
            </div>
            <div class="form-group">
              <label>Journal Title<span class="m-1 text-primary">*</span></label>
              <input type="text" class="form-control" name="jtitle" required>
            </div>
            <div class="form-group">
              <label>Journal Volume</label>
              <input type="text" class="form-control" name="jvolume">
            </div>
            <div class="form-group">
              <label>Journal Issue</label>
              <input type="text" class="form-control" name="jissue">
            </div>
            <div class="form-group">
              <label>Journal Pages</label>
              <input type="text" class="form-control" name="jpages">
            </div>
            <div class="form-group">
              <label>Journal DOI</label>
              <input type="text" class="form-control" name="jdoi">
            </div>
            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-info" name="jsubmit">
            <!-- <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> -->
          </form>
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


<!-- JOURNAL ADD - BACKUP - 1 UNIQUE JOURNAL PER AUTHOR
// $jins->insert(
        //   'journals',
        //   array(
        //     'j_title' => Input::get('jtitle'),
        //     'j_year' => Input::get('jyear'),
        //     'j_volume' => Input::get('jvolume'),
        //     'j_issue' => Input::get('jissue'),
        //     'j_pages' => Input::get('jpages'),
        //     'j_doi' => Input::get('jdoi')
        //   )
        // );

        // $jQ = $jins->query("SELECT j_id FROM journals ORDER BY j_id DESC LIMIT 1");
        // $jid = $jQ->first()->j_id;
        // $fpins = DB::getInstance();
        // $fpins->insert(
        //   'fac_publication',
        //   array(
        //     'fac_fid' => $fid,
        //     'fac_jid' => $jid,
        //     'fac_issn' => Input::get('facissn'),
        //     'fac_field' => Input::get('facfield'),
        //     'fac_rank' => Input::get('facrank'),
        //     'fac_publisher' => Input::get('facpublisher')
        //   )
        // );
        // echo "<script type=\"text/javascript\">alert(\"Journal added\");</script>"; 
      
      $cins->insert(
        //   'conferences',
        //   array(
        //     'c_title' => Input::get('ctitle'),
        //     'c_year' => Input::get('cyear'),
        //     'c_volume' => Input::get('cvolume'),
        //     'c_issue' => Input::get('cissue'),
        //     'c_pages' => Input::get('cpages'),
        //     'c_doi' => Input::get('cdoi'),
        //     'c_country' => Input::get('ccountry'),
        //     'c_city' => Input::get('ccity')
        //   )
        // );
        // // echo "Here5";
        // $cQ = $cins->query("SELECT c_id FROM conferences ORDER BY c_id DESC LIMIT 1");
        // $cid = $cQ->first()->c_id;
        // // echo $cid;
        // $fpcins = DB::getInstance();
        // $fpcins->insert(
        //   'fac_publication',
        //   array(
        //     'fac_fid' => $fid,
        //     'fac_cid' => $cid,
        //     'fac_issn' => Input::get('facissn'),
        //     'fac_field' => Input::get('facfield'),
        //     'fac_rank' => Input::get('facrank'),
        //     'fac_publisher' => Input::get('facpublisher')
        //   )
        // );
        // echo "<script type=\"text/javascript\">alert(\"Conference Added\");</script>";
         
      // echo $fid;
        // $pins = DB::getInstance();
        // $pins->insert(
        //   'projects',
        //   array(
        //     'p_title' => Input::get('ptitle'),
        //     'p_budget' => Input::get('pbudget'),
        //     'p_duration' => Input::get('pduration'),
        //     'p_sponsor' => Input::get('psponsor'),
        //     'p_status' => Input::get('prostat'),
        //   )
        // );
        // // echo "Here5";
        // $pQ = $pins->query("SELECT p_id FROM projects ORDER BY p_id DESC LIMIT 1");
        // $pid = $pQ->first()->p_id;
        // // echo $cid;
        // $fproins = DB::getInstance();
        // $fproins->insert(
        //   'faculty_project',
        //   array(
        //     'fp_fid' => $fid,
        //     'fp_pid' => $pid,
        //     'fp_position' => Input::get('projectpos')
        //   )
        // );
        // //echo "Here7";
        // echo "<script type=\"text/javascript\">alert(\"Project entered\");</script>";
      -->