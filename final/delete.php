<?php
 $userId = $_GET['userId'];

require_once("Config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}//$sql = "SELECT SUM(Quantity)
//FROM codecrusaders.ticket;";
$sql = "DELETE FROM codecrusaders.ticket WHERE TicketID='$userId';";

// Execute the query
$result = $conn->query($sql);

if ($result === FALSE) {
    die("UNABLE TO UPDATE THE RECORD");
}

header("Location: cartPage.php");

?>