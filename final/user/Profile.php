<?php
session_start();
//print_r($_SESSION);
require_once("../login/secure.php");
$name=$_SESSION['username'];


require_once("config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}//$sql = "SELECT SUM(Quantity)
//FROM codecrusaders.ticket;";
$sql = "SELECT * FROM codecrusaders.user
inner join codecrusaders.account
on user.accountID=account.AccountID where username='$name';";

// Execute the query
$result = $conn->query($sql);

if ($result === FALSE) {
    die("UNABLE TO UPDATE THE RECORD");
}
echo "<nav class='navigation'>
    <a href='index.php' style='color: #bec5ad'>HOME</a>
    
    <a href='../index.php' style='color: #bec5ad'>LOG OUT</a>
  </nav><Br><br><Br><br><Br><br><Br><br>";
echo "<div id='topDIV'>
   <div class='row'>
  <div class='column' >";
    echo"<h2 style='color:green'>Your Profile</h2>
    <p>View Details and Edit your Account</p>

  </div> 
</div>
</div>";

if ($result->num_rows>0){    // take rows from the output table in the database
 
  while ($row =$result->fetch_assoc()){   

     echo "<div id='myDIV'>
   <div class='row'>
  <div class='column' >";
    echo"<h2 style='color:green'>User Details</h2>
  </div>
  <div class='column' >";
   echo "<p>"."<b>"."First Name:"."</b>".$row["FirstName"]."</p>";
   echo "<p>"."<b>"."Last Name:"."</b>".$row["LastName"]."</p>";
   echo "<p>"."<b>"."Username:"."</b>".$row["Username"]."</p>";
   echo "<p>"."<b>"."Email Address:"."</b>".$row["Email"]."</p>";
  
  echo "</div>";
 echo " <button type='button' onclick='alert('Hello world!')''>Reset Email Address</button>";
  echo " <button type='button' onclick='myFunction()'> Change UserName</button>";
echo "</div>
</div><br><br>";

  }
}
echo"<footer style='text-align:center;background-color: #636B2F;'>
<p style='color: #bec5ad;'>&copy Author: Code Crusaders | <a
    href='mailto:codecrusaders@gmail.com'>codecrusaders@gmail.com</a></p>
</footer>";
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="profile.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>

/* Create two equal columns that sits next to each other */

#myDIV {
  width:50%;
  height:300px;
  background-color:#FFFFFF;
  border:5px ;
  border-style:outset;
  margin: 0 auto; /* Center the container */
  border-radius: 5px;   
  padding: 20px; 
}
#topDIV {
  width:50%;
  height:100px;
  background-color:#FFFFFF;
  border:5px ;
  border-radius: 5px;
  border-style: outset;
  margin: 0 auto; /* Center the container */      
  padding: 20px; 
}
footer {
  display: flex;
  justify-content: space-around;
  background-color: #636B2F;
  padding: 15px 0;

  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

footer a {
  color: #ff9f1a;
  transition: color 0.3s ease;
}

footer a:hover {
  color: #fff;
}





.btn{
  cursor: pointer;
 
 
}
</style>

<body>

<script>
function myFunction(){
    window.location.href = "updateUsername.php";
}
</script>

</body>

