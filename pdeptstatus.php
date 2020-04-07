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
$data->query("SELECT p_status AS 'Status', COUNT(p_status) AS 'Contributions' FROM faculty, faculty_project, projects WHERE faculty.f_dept='$dept' AND faculty.f_id = faculty_project.fp_fid AND faculty_project.fp_pid = projects.p_id GROUP BY projects.p_status LIMIT 10");

$result = array();

foreach($data->results() as $row){
  //echo $row->Year. $row->Contributions;
  $result[] = $row;
}

print json_encode($result);