<?php
require_once 'core/init.php';

$user = new User();
$web = $user->data()->l_webmail;
//echo $user->data()->l_webmail;

header('Content-Type: application/json');

$getdept = DB::getInstance();
$res = $getdept->get('faculty', 'f_webmail', '=', $web);
$dept = $res->first()->f_dept;

$data = DB::getInstance();
$data->query("SELECT  j_year AS 'Year', COUNT(j_year) AS 'Contributions' FROM faculty, fac_publication, journals WHERE faculty.f_dept= '$dept' AND faculty.f_id = fac_publication.fac_fid AND fac_publication.fac_jid = journals.j_id  GROUP BY journals.j_year ORDER BY journals.j_year LIMIT 10");

$result = array();

foreach($data->results() as $row){
  //echo $row->Year. $row->Contributions;
  $result[] = $row;
}

print json_encode($result);

