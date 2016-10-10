<?php

 /*establish database connection with database information*/

 $db_username = "asikpo_cnyong";
 $db_name = "asikpo_attendance";
 $host = "localhost";
 $password = "tog1767";

 $connection = mysqli_connect($host, $db_username, $password, $db_name);


  if(mysqli_connect_errno()){
  	echo "Failed to connect: " . mysqli_connect_errno();
  }
  else{
  //	echo "Connected!";
  }

?>
