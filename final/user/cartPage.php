<?php
require_once("../login/secure.php"); 


require_once("Config.php");  
$accountID = $_SESSION['acc_id'];

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}//$sql = "SELECT SUM(Quantity)
//FROM codecrusaders.ticket;";
$sql = "SELECT  sum(Price*Quantity) AS'TOTAL' FROM codecrusaders.ticket
INNER JOIN codecrusaders.user
ON  TICKET.USERID=USER.USERID
INNER JOIN 
codecrusaders.account 
on 
USER.AccountID=account.AccountID
INNER JOIN 
codecrusaders.EVENT
on 
TICKET.EVENTID=EVENT.EVENTID where ACCOUNT.accountID='$accountID' AND  status ='Add to cart'";

// Execute the query
$result = $conn->query($sql);

if ($result === FALSE) {
    die("UNABLE TO UPDATE THE RECORD");
}

echo "<nav class='navigation'>
        <a href='index.php' style='color: #bec5ad'>BACK</a>
        <a href='../login/logout.php' style='color: #bec5ad'>LOG OUT</a>
    </nav>";
while ($row =$result->fetch_assoc()){
  ECHO"<br><br><br><br><br><h2>Total amount:"."R".$row['TOTAL']."</h2>";
}
$sql1="SELECT * FROM codecrusaders.ticket
INNER JOIN codecrusaders.user
ON  TICKET.USERID=USER.USERID
INNER JOIN 
codecrusaders.account 
on 
USER.AccountID=account.AccountID
INNER JOIN 
codecrusaders.EVENT
on 
TICKET.EVENTID=EVENT.EVENTID
inner join codecrusaders.photo on
EVENT.EVENTID=photo.eventID WHERE ACCOUNT.ACCOUNTID= '$accountID' AND status ='Add to cart'";
$result1 = $conn->query($sql1);

if ($result1 === FALSE) {
    die("UNABLE TO UPDATE THE RECORD");
}

if ($result1->num_rows>0){    // take rows from the output table in the database
 
  while ($row =$result1->fetch_assoc()){   
    $id=$row['TicketID'];
    $p=$row['Path'];
    $picture=htmlspecialchars($row['Path']);

    $basePath = 'creator/'; 
    $imagePath = $basePath . 'events/' . $picture;

     echo "<div id='myDIV'>
   <div class='row'>
  <div class='column' >";
    echo"<h2>".$row['Title']."</h2>";
    echo"<img style='width:60%;'id='images' src='$p' >
  </div>
  <div class='column' >
   <br><br><br><br>";
   echo "<p>"."Quantity:".$row["Quantity"]."</p>";
   echo "<p>"."<P>Total:" .$row["Price"]."</P>";
   echo " <button  class='btn' id='decrement'>
   <i onclick='decrementFunction($id)' class='fas fa-minus'></i>
</button>
<button class='btn' id='increment'>
   <i onclick='incrementFunction($id)'class='fas fa-plus'></i>
</button>";
   echo"<a><i  onclick='myFunction($id)' id='icon' class='material-icons'>delete</i></a>
  </div>
</div>
</div>";

  }
  echo "<div class='Paybtn'>
  <button type='button' onclick=\"window.location.href='payment.html'\" id='pay' class='payNow'>Check out</button>
</div>";
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

.payNow {
    width: 48%;
    color: #bec5ad;
    background-color: #533b4d;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.payNow:hover {
    background-color: #636B2F;
    color: white;
}

.payNow{
    display: flex;
}

#images{

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
 
 
}footer {
  display: flex;
  justify-content: space-around;
  background-color: #636B2F;
  padding: 15px 0;
 /* Fix the navigation bar at the top */
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

</style>
</head>
<body>
  <br>
<footer style="text-align:center;background-color: #636B2F;">
<p style="color: #bec5ad;">&copy Author: Code Crusaders | <a href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</footer>

</body>
</html>


<script>
function myFunction(id) {
  var txt;
  if (confirm("Press a button!")) {
    window.location.href = "delete.php?userId=" + id;
  } 
  }
  

function decrementFunction(id){
  
  window.location.href = "decremnt.php?ticketId=" + id;
   
}
function incrementFunction(id){
  window.location.href = "increment.php?ticketId=" + id;
}
</script>
