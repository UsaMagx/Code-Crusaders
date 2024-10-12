<?php
require_once('../login/secure.php');
require_once('Config.php');


// Start session to handle session variables


// Create database connection
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("<p class='error'>Connection to database failed: " . $conn->connect_error . "</p>");
}

if (!isset($_SESSION['acc_id'])) {
    die("<p class=\"error\">User not logged in!</p>");
}

$accID = $_SESSION['acc_id']; // Get the account ID

// This handles the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $eventName = $_POST['ename'];
    $category = $_POST['eventtypes'];
    $venue = $_POST['venue'];
    $dateTime = $_POST['eventdate'];
    $price = $_POST['price'];
    $eventDescription = $_POST['edescription'];
    $capacity = $_POST['capacity'];

    $categoryMap = [
        'music' => 5,       // Music Festival
        'theater' => 3,    // Theater
        'film' => 4,       // Film
        'sports' => 1,     // Sports Event
        'comedy' => 2,     // Comedy
        'art' => 6         // Art Exhibition
    ];


    if (array_key_exists($category, $categoryMap)) {
        $category_id = $categoryMap[$category];  // Get the category ID from the map
    } else {
        echo "Category not found.";
        exit;
    }

    // Check if CreatorID exists
    $sqlcreator= "SELECT CreatorID from creator where AccountID= $accID";
    $creatorresults = $conn->query($sqlcreator);

    if($creatorresults === false){
        die("<p class=\"error\">Cannot find creator ID: " . $conn->error . "</p>");
    }

    if ($creatorresults->num_rows > 0) {
        $creatorRow = $creatorresults->fetch_assoc();
        $creatorID = $creatorRow['CreatorID']; // Get the CreatorID
    } else {
        die("<p class=\"error\">No creator found for the given AccountID.</p>");
    }

    // Insert event into the event table
    $sql = "INSERT INTO event (Title, CategoryID, Venue, DateTime, Price, Description, CreatorID, Capacity) 
            VALUES ('$eventName', '$category_id', '$venue', '$dateTime', '$price', '$eventDescription','$creatorID', '$capacity')";

    $eventinsert = $conn->query($sql);

    if($eventinsert === false){
        die("<p class=\"error\">Cannot insert into event: " . $conn->error . "</p>");
    }

    $RecentIdQuery = "SELECT MAX(EventID) AS 'most_recent_id' FROM event;";
    $RecentIdResult = $conn->query($RecentIdQuery);

    if ($RecentIdResult === False) {
        die("<p class=\"error\">Cannot retrieve recent ID!</p>");
    }

    $rowresults = $RecentIdResult ->fetch_assoc();
    $mostRecentId = $rowresults['most_recent_id'];

    if(isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK){
        $picture = time() . "_" . basename($_FILES['picture']['name']);
        $location = "events/" . $picture;

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $location)) {
            // Insert into the database
            $fileEntry = "INSERT INTO photo (Path, EventID) VALUES ('$location', '$mostRecentId')";
            $fileEntryResult = $conn->query($fileEntry);
    
            if ($fileEntryResult === false) {
                die("<p class=\"error\">Cannot insert into photo table: " . $connection->error . "</p>");
            }
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="createeventStyle.css.css">
    <script src="event.js"></script>
    <title>Create Event</title>
</head>

<body>
    <table style="background-color: #636B2F; width:100%">
        <td>
            <nav class="navigation">
                <a href="creator_dash.php" style="color: #bec5ad;">Back</a>
                
            </nav>
        </td>
    </table>

    <form style="background-color: #f5f1e3;" method="post" enctype="multipart/form-data" action="createevent.php">
        <h1 style="font-family:verdana">Add New Event</h1>

        <div class="form-group">
            <label for="ename"><b>Name of Event:</b></label>
            <input type="text" placeholder="Enter Event Name" name="ename" required>
        </div>

        <div class="form-group">
            <label for="category"><b>Category:</b></label>
            <select id="eventtypes" name="eventtypes">
                <option value="music">Music Festival</option>
                <option value="theater">Theater</option>
                <option value="film">Film</option>
                <option value="sports">Sports Event</option>
                <option value="comedy">Comedy</option>
                <option value="art">Art Exhibition</option>
            </select>
        </div>

        <div class="form-group">
            <label for="venue"><b>Venue:</b></label>
            <input type="text" placeholder="Enter Venue" id="venue" name="venue" required>
        </div>

        <div class="form-group">
            <label for="date"><b>Date & Time:</b></label>
            <input type="datetime-local" id="eventdate" name="eventdate">
        </div>

        <div class="form-group">
            <label for="price"><b>Price:</b></label>
            <input type="text" placeholder="Enter price" id ="price" name="price" required>
        </div>

        <div class="form-group">
            <label for="capacity"><b>Capacity:</b></label>
            <input type="number"  id ="capacity" name="capacity" min="1" required>
        </div>

        <div class="form-group">
            <label for="picture"><b>Add Picture of Event:</b></label>
            <input type="file" id="picture" name="picture">
        </div>

        <div class="form-group">
            <label for="edescription"><b>Event Description:</b></label>
            <textarea id="edescription" name="edescription" rows="5" cols="30">Describe your Event</textarea>
        </div>

        <div class="formbtns">
            <button type="submit" class="signupbtn">Create</button>
        </div>

    </form>

    <footer style="text-align:center;background-color: #636B2F;">
        <p style="color: #bec5ad;">&copy; Author: Code Crusaders | <a href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</a></p>
    </footer>
</body>

</html>
