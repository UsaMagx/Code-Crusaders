<?php
require_once("../login/secure.php");
require_once("Config.php");

$accountID = 1; // i will find it later
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get name and surname
$name = "SELECT creator.FirstName, creator.LastName, creator.CreatorID 
         FROM creator 
         INNER JOIN account ON account.AccountID = creator.AccountID 
         WHERE creator.AccountID = ?";
$namestmt = $conn->prepare($name);
$namestmt->bind_param("i", $accountID);
$namestmt->execute();
$result = $namestmt->get_result();

$FirstName = $LastName = $creatorID = null;
while ($row = $result->fetch_assoc()) {
    $FirstName = $row['FirstName'];
    $LastName = $row['LastName'];
    $creatorID = $row['CreatorID'];
}
$namestmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myEventsStyle.css">
    <title>My Events</title>
</head>

<body>
    <div class="events">
        <?php
        // Fetch events created by the current user
        $event = "SELECT category.CategoryName, photo.Path, event.Description, event.Title, event.EventID 
                  FROM event 
                  INNER JOIN category ON category.CategoryID = event.CategoryID 
                  INNER JOIN photo ON photo.EventID = event.EventID 
                  WHERE event.CreatorID = ?";
        $eventstmt = $conn->prepare($event);
        $eventstmt->bind_param("i", $creatorID);
        $eventstmt->execute();
        $result = $eventstmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $category = $row['CategoryName'];
            $picture = $row['Path'];
            $description = $row['Description'];
            $title = $row['Title'];
            $eventID = $row['EventID'];
            
            echo "
            <figure class='event'>
                <a href='eventInfo.php?eventID=$eventID'>
                    <img src='$picture' alt='$title'>
                    <figcaption>
                        <h3>$title</h3>
                        <p>$description</p>
                    </figcaption>
                </a>
            </figure>";
        }
        $eventstmt->close();
        ?>
    </div>
</body>
<style>
    /* General Styles */
* {
  box-sizing: border-box; /* Ensure padding doesn't affect width */
}

body {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f5f1e3;
  color: #333;
  margin-top: 75px;
  height: 100%;
}

h1,
h2,
p {
  margin: 0;
  padding: 0;
}

a {
  text-decoration: none;
  color: #636B2F; /* Default link color */
  transition: color 0.3s ease;
}

a:hover {
  color: #533b4d; /* Hover color for links */
}

/* Navigation Styles */
.navigation {
  display: flex;
  justify-content: space-around;
  background-color: #636B2F;
  padding: 15px 0;
  position: fixed; /* Fix the navigation bar at the top */
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.navigation a {
  color: #bec5ad;
  font-weight: bold;
  padding: 10px 15px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.navigation a:hover {
  background-color: #533b4d;
  color: #fff;
  border-radius: 5px;
}

/* Layout Styles */


.event, .details, .reviews {
  flex: none; /* Allow each section to take its natural height */
  padding: 20px; /* Padding for spacing */
  border-radius: 5px; /* Rounded corners */
}

.event {
  flex: 1; /* Increase the space for the event section */
}

.details {
  flex: 1; /* Takes half the space of event */
  max-width: 500px; /* Optional: Set a max width */
}

/* Figure and Image Styles */
figure{
  display: block;
  margin: 10px auto; /* Add margin to give space around figure */
  width: 80%; /* Ensure the figure takes full width */
  text-align: center; /* Centers the caption inside the figure */
  max-width: 100%; /* Ensure the figure does not exceed its container */
  max-height: 80vh; /* Set a maximum height for the figure to avoid overflow */
  overflow: hidden; /* Hide overflow to prevent content spilling out */
}

img {
  width: 80%; /* Ensures the image doesn't overflow */
  height: auto; /* Maintain aspect ratio of the image */
  border: 3px solid #636B2F; /* Add a green border */
  border-radius: 5px; /* Optional: Rounded corners for images */
  max-height: 400px; /* Set a maximum height for the image */
  object-fit: cover; /* Ensures the image covers the entire area while preserving aspect ratio */
}

figcaption {
  font-size: 1.5em; /* Increase the font size */
  font-weight: bold; /* Make the text bold */
  color: #333; /* Change the color if desired */
  text-align: center; /* Center the text */
  margin-top: 10px; /* Space between the image and caption */
}

/* Form Group Styles */
.form-group {
  display: flex; /* Use flexbox to align labels and values side by side */
  margin-bottom: 15px; /* Space between each form group */
}

.label {
  flex: 1; /* Make label take equal space */
  padding: 10px; /* Padding inside the label */
  border-radius: 5px; /* Rounded corners for label */
}

.value {
  flex: 2; /* Make value take double space */
  padding: 10px; /* Padding inside the value */
  border: 1px solid #999; /* Border around the value */
  border-radius: 5px; /* Rounded corners for value */
  background-color: #fff; /* White background for the value */
}

/* Buy Button Styles */
.buy-button {
  display: block;
  width: 100%; /* Full width */
  padding: 10px; /* Padding for the button */
  background-color: #636B2F; /* Button background color */
  color: #fff; /* Button text color */
  font-size: 1.5em; /* Font size for button */
  font-weight: bold; /* Bold text for button */
  border: none; /* Remove border */
  border-radius: 5px; /* Rounded corners for button */
  text-align: center; /* Center text */
  text-decoration: none; /* No underline */
  margin-top: 20px; /* Space above button */
  transition: background-color 0.3s ease; /* Smooth transition */
}

.buy-button:hover {
  background-color: #533b4d; /* Darker color on hover */
}

/* Reviews Section Styles */
.review {
  display: flex; /* Use flexbox to align items */
  flex-direction: column; /* Stack items vertically */
  align-items: flex-start; /* Align items to the start */
  width: 90vw; /* Set the width to 90% of the viewport width */
  margin: 20px 0; /* Add vertical spacing */
  border: 1px solid #ccc; /* Optional: Add a border */
  padding: 20px; /* Optional: Add padding */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a shadow for better visibility */
  background-color: #fff; /* Optional: Background color */
  border: 2px solid #636B2F; /* Solid green border */
  border-radius: 10px;
  transition: transform 0.3s ease; /* Smooth transition for scaling */
}

  .review:hover {
      background-color: #f0f0f0; /* Light gray background on hover */
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Enhance shadow for emphasis */

}

header {
  text-align: center;
  margin-top: 10px;
}

.top {
  display: flex;
  width: 100%; /* Full width for top section */
}

.top-left {
  display: flex; /* Use flexbox for layout */
  align-items: center; /* Center align items vertically */
  flex: 1; /* Allow it to take available space */
}

.top-left img {
  width: 100px; /* Set a fixed width for images */
  height: auto; /* Maintain aspect ratio */
  margin-right: 20px; /* Space between image and title */
  border-radius: 100px;
}

.top-right {
  display: flex; /* Use flexbox for layout */
  justify-content: flex-end; /* Align items to the right */
  align-items: center; /* Center align items vertically */
  margin-left: 20px; 
}

.rating {
  display: flex; /* Use flexbox for the rating stars */
}

.rating__star {
  color: #ccc; /* Default color for unfilled stars */
  font-size: 24px; /* Adjust size as needed */
}

.rating__star.fas {
  color: #FFD700; /* Color for filled stars (gold) */
}

.comment {
  margin-top: 15px; /* Add spacing above the comment */
  font-size: 20px; /* Adjust font size as needed */
  color: #333; /* Change text color if desired */
  max-width: calc(100% - 200px); /* Ensures it doesn't overlap with stars */
  overflow-wrap: break-word; /* Ensure long words wrap */
}

footer {
  margin-top: auto; /* Push footer to the bottom */
  padding: 15px 0;
  background-color: #636B2F;
  color: #bec5ad;
  text-align: center;
  font-size: 14px;
}
.title{
  font-family: 32px;
  font-weight: bolder;
}
.delete-button {
  background-color: transparent; /* No background */
  border: none; /* Remove border */
  color: #636B2F; /* Red color for delete */
  cursor: pointer; /* Change cursor to pointer */
  font-size: 16px; /* Adjust font size */
  transition: color 0.3s ease; /* Smooth color transition */
}

.delete-button:hover {
  color:  #533b4d; /* Darker red on hover */
}
/* Footer Styles */
footer {
  text-align: center; /* Center align footer content */
  padding: 20px; /* Add padding to the footer */
  background-color: #636B2F; /* Footer background color */
  color: #bec5ad; /* Footer text color */
  position: relative; /* Ensures it behaves well within the layout */
  margin-top: 20px; /* Space above footer */
}

</style>

</html>

<?php
$conn->close();
?>
