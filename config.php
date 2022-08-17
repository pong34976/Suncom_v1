<?php


 
  function connectdb(){
  $servername = "localhost";
  $username = "root";
  $password = "1234";
  $dbname = "db_housing";
  
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $conn->set_charset("utf8");
  return $conn;
 
}

 
//  $sql = "SELECT * FROM tb_user";
//  $conn->set_charset("utf8");
//  $result = $conn->query($sql);

//  if ($result->num_rows > 0) {
//    // output data of each row
//    print_r( $result->fetch_all() );
// //    while($row = $result->fetch_assoc()) {
  
// //    }
//  } else {
//    echo "0 results";
//  }
//  $conn->close();
 ?>
 


 
 