<?php
require_once("../login/secure.php");
?>


<STYLE>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f1e3;
    color: #333;
  }
  
  .form-container {
    background-color: #f9f9f9; /* Light background for contrast */
    padding: 50px; /* Padding around the form */
    border-style: OUTSET;
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

form {
    display: flex;
    flex-direction: column; /* Stack form elements vertically */
}
footer {
  display: flex;
  justify-content: space-around;
  background-color: #636B2F;
  padding: 20px ;
  margin-top: 80%; 
  left: 10;
  width: 150%; /* Full width */
height: 50px;
  z-index: 100;
}

footer a {
  color: #ff9f1a;
  transition: color 0.3s ease;
}

footer a:hover {
  color: #fff;
}
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
    margin: 0; /* Remove default margin */
}

</STYLE>

<!DOCTYPE html>
<html>
<body>


<div class="form-container">
<form id="form" action='username.php' >
  <label for="fname">Enter new Username:</label><br>
  <input type="text" id="username" name="username"  required><br>
  <input type="submit" value="Submit">
</form> 

</div>


</body>

</html>

