<?php
// Import database configuration
require_once('../Config.php');

// Create database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("<p class='error'>Connection to database failed: " . $conn->connect_error . "</p>");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $conn -> real_escape_string($_POST['name']);
    $surname = $conn -> real_escape_string($_POST['surname']);
    $email = $conn -> real_escape_string($_POST['email']);
    $username = $conn -> real_escape_string($_POST['username']);
    $password = $conn -> real_escape_string($_POST['psw']);
    $role = $conn -> real_escape_string($_POST['role']);

    $hashed_password = password_hash($password,PASSWORD_BCRYPT);

    
    // Insert user into the account table (only username and password)
    $sqlAccount = "INSERT INTO account (Username, Password,Role) 
                   VALUES ('$username', '$hashed_password','$role')";

    $ResultAccount = $conn->query($sqlAccount);

    if ($ResultAccount === FALSE) {
        die("<p class='error'>Unable to create account!</p>");
    }

    // Get the inserted Account_ID
    $Account_Id = $conn->insert_id;

    // Insert into either the 'user' or 'creator' table based on the role
    if ($role == 'user') {
        $sqlUser = "INSERT INTO user (AccountID, FirstName, LastName, Email) 
                    VALUES ('$Account_Id', '$name', '$surname', '$email')";

        if ($conn->query($sqlUser) === FALSE) {
            die("<p class='error'>Unable to insert user info: " . $conn->error . "</p>");
        }
        header("Location: login.php"); // Redirect to user dashboard

    } elseif ($role == 'creator') {
        $sqlCreator = "INSERT INTO creator (AccountID, FirstName, LastName, Email) 
                       VALUES ('$Account_Id', '$name', '$surname', '$email')";

        if ($conn->query($sqlCreator) === FALSE) {
            die("<p class='error'>Unable to insert creator info: " . $conn->error . "</p>");
        }
        header("Location: Location: login.php"); // Redirect to creator dashboard
    }

    // Close the database connection
    $conn->close();
    exit(); // Ensure no more code is executed after redirection
}
?>
