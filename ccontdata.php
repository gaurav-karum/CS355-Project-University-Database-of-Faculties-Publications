<?php
require_once 'core/init.php';

$user = new User();
$web = $user->data()->l_webmail;
//echo $user->data()->l_webmail;

header('Content-Type: application/json');


$data = DB::getInstance();
$data->query("SELECT  c_year AS 'Year', COUNT(c_year) AS 'Contributions' FROM faculty, fac_publication, conferences WHERE faculty.f_webmail= '$web' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_cid = conferences.c_id  GROUP BY conferences.c_year ORDER BY conferences.c_year LIMIT 10");

$result = array();

foreach($data->results() as $row){
  //echo $row->Year. $row->Contributions;
  $result[] = $row;
}

print json_encode($result);

