<?php
require_once("Config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}

  
      $sql = "UPDATE codecrusaders.ticket
  SET Quantity = Quantity+1 WHERE ticketID='7';";
      
      // Execute the query
      $result = $conn->query($sql);
      
      if ($result === FALSE) {
          die("UNABLE TO UPDATE THE RECORD");
      }
    echo "ADDED";
    //header("Location: cartPage.php");
    ?>