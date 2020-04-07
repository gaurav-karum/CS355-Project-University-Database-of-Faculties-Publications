<?php 
	
	# read the data

	$acc = $_POST['lmail'];
	$pwd = $_POST['lpass'];

	# create connection
	
	$db = mysqli_connect('db', 'user', 'test', 'project');
$errors = array();

	# check connection
	// if ($conn->connect_error) {
  //   	die("Connection failed: " . $conn->connect_error);
	// } else{
	// 	echo "Connected successfully";
	// }
	# msql query
	$sql = "SELECT *
		FROM login 
		WHERE l_webmail = '$acc'";

	# get result of above mentioned query
	$result = mysqli_query($db, $sql);
	$userexists = mysqli_fetch_assoc($result);
	
	// echo $sql;
	// while($row = mysqli_fetch_assoc($result)){
  //   foreach($row as $cname => $cvalue){
  //       echo "$cname: $cvalue\t";
  //   }
  //   echo "\r\n";
  //}

	# show results if result is returned
	if($userexists){ 
		if($userexists['l_password'] === $pwd){
			ob_start();
			header("Location: landingpage.html");
			ob_end_flush();
		}
	}
	else{
		echo "<input type=\"submit\" class=\"btn btn-info btn-block\" value=\"wrong credentials mf\">
		<br>";
		// if(!empty($user))
    //   array_push($errors, "User does not exists");
    // foreach($errors as $key=>$item) {
    //   echo "Error => $item <br>";
    // }
	}
	


	# close connection
	mysqli_close($db);

?>