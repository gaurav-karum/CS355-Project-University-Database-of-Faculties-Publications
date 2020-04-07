<body>

<form action="" method="post">

  <input name="search" type="search" required autofocus><input type="submit" name="SearchBtn">

</form>

<table>
  <tr><td><b>First Name</td><td></td><td><b>Last Name</td></tr>

<?php

$con=mysqli_connect('db', 'user', 'test', 'project');

$errors = array();


if(isset($_POST['SearchBtn'])){    //trigger button click

  $search = mysqli_real_escape_string($con, $_POST['search']);

  $result=mysqli_query($con, "SELECT j_title, j_year FROM journals WHERE j_title LIKE '%$search%'  ");

if ($result) {

  

  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>".$row['j_title']."</td><td></td><td>".$row['j_year']."</td></tr>";
  }
}else{
    echo "No Journals Found<br><br>";

  }

}else{                          //while not in use of search  returns all the values
  $query=mysqli_query($con,"SELECT j_title, j_year FROM journals");

  while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr><td>".$row['j_title']."</td><td></td><td>".$row['j_year']."</td></tr>";
  }
}

mysqli_close($con);
?>