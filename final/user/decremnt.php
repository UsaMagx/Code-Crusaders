<?php
require_once("../login/secure.php");
$Id = $_GET['ticketId'];

require_once("Config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}

   $sql1="SELECT quantity FROM codecrusaders.ticket where TicketID='$Id';";
   $result1 = $conn->query($sql1);
      
      if ($result1 === FALSE) {
          die("UNABLE TO UPDATE THE RECORD");
      }
      while ($row =$result1->fetch_assoc()){
        if ($row['quantity'] === 0) {
            header("Location: delete.php");
        }
      }

      
     $sql = "UPDATE codecrusaders.ticket
  SET Quantity = Quantity - 1 WHERE ticketID='$Id';";
      
      // Execute the query
      $result = $conn->query($sql);
      
      if ($result === FALSE) {
          die("UNABLE TO UPDATE THE RECORD");
      }
   
    header("Location: cartPage.php");
    ?>