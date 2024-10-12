<?php
$Id = $_GET['ticketId'];

require_once("Config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}
//$sql1 ="SELECT SUM(Quantity) AS TOTAL, CAPACITY
//FROM codecrusaders.ticket 
//INNER JOIN codecrusaders.EVENT 
//ON ticket.eventID = EVENT.EVENTID 
//WHERE EVENT.EVENTID = '$event'";
$sql = "UPDATE codecrusaders.ticket
            SET Quantity = Quantity+1 WHERE ticketID='$Id';";
          
$result = $conn->query($sql);
      
if ($result === FALSE) {
    die("UNABLE TO UPDATE THE RECORD");
}

    
       
         echo " There are no more tickets available";
    
  
      
      // Execute the query
     
    echo "ADDED";
    header("Location: cartPage.php");
    ?>