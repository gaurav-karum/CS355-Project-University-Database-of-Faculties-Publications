<!DOCTYPE html>
<html>
<head>
  <title>changepass</title>
</head>
<body>

<?php 
	
	# read the data

	$id = $_POST["l_id"];
	$old = $_POST["l_pass"];
	$new = $_POST["l_newpass"];

	# connect with mysql 

	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "mysql";
	$dbname = "project";

	# create connection
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	# check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";

	# msql query
	$sql = "
	UPDATE login	
	SET l_password = '$new'	
	WHERE l_webmail = '$id' and l_password = '$old'";

	if(mysqli_query($conn, $sql)){ 
    	echo "Password was updated successfully."; 
	} else { 
    	echo "Wrong Credentials"  
                            . mysqli_error($conn); 
	}	 


	# close connection
	$conn->close();

?>

</body>
</html>