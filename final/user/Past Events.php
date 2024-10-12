<?php
require_once("../login/secure.php");

//print_r($_SESSION);
$name=$_SESSION['username'];
$new=$_REQUEST['username'];
require_once("config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}

  
$sql = "UPDATE codecrusaders.ACCOUNT
SET username = '$new'
WHERE accountID IN (
    SELECT USER.ACCOUNTID
    FROM codecrusaders.USER
    WHERE username = '$name'
);";
      
      // Execute the query
      $result = $conn->query($sql);
      
      if ($result === FALSE) {
          die("UNABLE TO UPDATE THE RECORD");
      }
   
    header("Location: Profile.php");
    ?>