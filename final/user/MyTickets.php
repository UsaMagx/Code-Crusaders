<?php
require_once("../login/secure.php");
session_start();
//print_r($_SESSION);
$name=$_SESSION['username'];

require_once("config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}//$sql = "SELECT SUM(Quantity)
//FROM codecrusaders.ticket;";


// Execute the query
$sql="SELECT * FROM codecrusaders.ticket inner join
 codecrusaders.user on ticket.userID=USER.USERID
 INNER JOIN 
  codecrusaders.ACCOUNT  ON ACCOUNT.ACCOUNTID=USER.ACCOUNTID inner join
  codecrusaders.event on event.eventID=ticket.EventID
  INNER JOIN CODECRUSADERS.PHOTO
  ON  event.eventID=PHOTO.eventID WHERE USERNAME='$name' AND STATUS='purchased';";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("UNABLE TO UPDATE THE RECORD");
}
ECHO"
<table style='background-color: #636B2F; width:100%;'>
    <td>
      <nav class='navigation'>
        <a href='index.php' style='color: #bec5ad'>HOME</a>
        
         <a href='../index.php' style='color: #bec5ad'>Log out</a>
      </nav>
    </td>
  </table>
  <h2>MyTickets"."</h2>";
if ($result->num_rows>0){    // take rows from the output table in the database
 
  while ($row =$result->fetch_assoc()){   
    $id=$row['TicketID'];
    $picture=$row['Path'];
     echo "<div id='myDIV'>
   <div class='row'>
  <div class='column' >";
    echo"<h2>".$row['Title']."</h2>";
    echo"<img id='images' src='$picture' >
  </div>
  <div class='column' >
   <br><br><br><br>";
   echo "<p>"."<B>"."Quantity:"."<B>".$row["Quantity"]."</p><br><br>";
   echo "<p>"."<B>"."Time&Date:"."<B>".$row["DateTime"]."</p>";

   echo"
  </div>
</div>
</div>
<p id='demo'></p>";
  }
}
ELSE{
  echo" No new tickets added";
}
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="profile.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>


.row {
  display: flex;
}

/* Create two equal columns that sits next to each other */





#myDIV {
  width:50%;
  height:300px;
  background-color:#FFFFFF;
  border:5px ;
  border-style: OUTSET;
  margin: 0 auto; /* Center the container */
        
  padding: 20px; 
}

#images{
  width:50%;
  height:200px;
padding-top:10px;
padding-right:65%;
}
.column {
  float: left;
  width: 50%;
  height: 300px; 
}
#icon{
  padding-top:25%;
  padding-left:90%;
  cursor: pointer;
}
.btn{
  cursor: pointer;
 
 
}
</style>
</head>
<body>
<footer style="text-align:center;background-color: #636B2F;">
    <p style="color: #bec5ad;">&copy Author: Code Crusaders | <a
        href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</a></p>
  </footer>
  </table>

</body>
</html>



