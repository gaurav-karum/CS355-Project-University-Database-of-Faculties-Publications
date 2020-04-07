<?php
require_once 'core/init.php';


//if (Input::get($_POST['nametrend'])) {
  $fac = Input::get($_POST['nametrend']);
  header('Content-Type: application/json');
  $data = DB::getInstance();
  $data->query("SELECT  j_year AS 'Year', COUNT(j_year) AS 'Contributions' FROM faculty, fac_publication, journals WHERE faculty.f_name = '$fac' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id  GROUP BY journals.j_year ORDER BY journals.j_year LIMIT 10");

  $result = array();

  foreach ($data->results() as $row) {
    //echo $row->Year. $row->Contributions;
    $result[] = $row;
  }
//}
print json_encode($result);

