<?php
session_start();

require_once("../Config.php");

// Create a connection to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check for database connection error
if ($conn->connect_error) {
    die("<p class=\"error\">Connection to database failed: " . $conn->connect_error . "</p>");
}

// Escape user inputs
$username = $conn->real_escape_string($_REQUEST['username']);
$password = $conn->real_escape_string($_REQUEST['psw']);  // Updated to match the form name


// Prepared statement to prevent SQL injection
$sql = "SELECT * FROM account WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);  // Bind username to the query

// Execute the prepared statement
$stmt->execute();
$result = $stmt->get_result(); // Get the result
//echo $result;

// Check if the query execution was successful
if ($result === false) {
    die("<p class=\"error\">Unable to retrieve data!</p>");
}

// Check if the user exists
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc(); // Fetch the result as an associative array
    
    // Verify the password 
    if ($row['Password'] && password_verify($password,$row['Password'])) { 
        // Store user data in the session
        $_SESSION['acc_id'] = $row['AccountID'];
        $_SESSION['access'] = "yes";
        $_SESSION['role'] = $row['Role'];
        $_SESSION['username'] = $username;
        $role = $row['Role'];
        // Redirect based on the user's role
        if ($role === 'creator') {
           header("Location: ../creator/creator_dash.php");
            exit(); // Ensure script execution stops after redirect
        } elseif ($role === "user") {
           header("Location: ../user/index.php");
            exit();
        } else {
            header("Location: login.php");
            exit();
        }
    } else {
        // Incorrect password
        header("Location: login.php?error=password");
        exit();
    }
} else {
    // Username not found
    header("Location: login.php?error=email");
    exit();
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
