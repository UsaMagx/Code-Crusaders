<?php
require_once("../login/secure.php");

$acc_id = $_SESSION['acc_id'];
$eventID = 1; // Placeholder, you will need to pass this dynamically


// Retrieve rating and comment from form submission
$rating = isset($_REQUEST['rating-value']) ? intval($_REQUEST['rating-value']) : 0;
$comment = isset($_REQUEST['description']) ? trim($_REQUEST['description']) : "";

require_once("Config.php");

// Create a connection to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check for database connection error
if ($conn->connect_error) {
    die("<p class=\"error\">Connection to database failed: " . $conn->connect_error . "</p>");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// Check if rating and comment are valid
if ($rating > 0 && !empty($comment)) {
    // SQL query to insert review into the database
    $reviewSQL = "INSERT INTO rating (Description, RatingNumber, AccountID, EventID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($reviewSQL);
    
    // Bind the parameters
    $stmt->bind_param("siis", $comment, $rating, $acc_id, $eventID);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo "<p class='success'>Review submitted successfully!</p>";
    } else {
        echo "<p class='error'>Error submitting review. Please try again.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<p class='error'>Please provide a valid rating and comment.</p>";
}
header("Location: index.php");
?>
</body>
</html>
