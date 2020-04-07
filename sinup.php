<?php 
	
	# read the data

	$freg = $_POST["FacultyId"];
	$pass = $_POST["Password"];
	$mail = $_POST["Webmail"];
	$name = $_POST["Username"];

	# connect with mysql 
	$db = mysqli_connect('db', 'user', 'test', 'project');
	$errors = array();

	// # check connection
	// if ($conn->connect_error) {
  //   	die("Connection failed: " . $conn->connect_error);
	// } 
	// echo "Connected successfully";

	# msql query
	$sql = "SELECT *
	FROM faculty
	WHERE f_regid='$freg' AND f_webmail='$mail'";

	# get results
	$result = mysqli_query($db, $sql);
	$userexists = mysqli_fetch_assoc($result);

	
	if($userexists){

		# msql query
		$sqli = "INSERT INTO 
		login (l_webmail, l_username, l_password) 
		VALUES('$mail', '$name', '$pass')";	

		if(mysqli_query($db, $sqli)){ 
			ob_start();
			header("Location: signup.php");
			ob_end_flush();
	
		} else { 
    	echo "ERROR: Could not able to execute $sqli. " . mysqli_error($db); 
		}
	}
	else{
		echo "Incorrect ID/Webmail";
	}

	# close connection
	mysqli_close($db);

?>	