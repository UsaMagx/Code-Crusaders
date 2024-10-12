<?php
require_once("Config.php");
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
  die("<p class=\"error\">Error connecting to the system!</p>");
}

// Get event ID from request
//$eventId = $_REQUEST['eventID'];
$eventId = $_REQUEST['eventID'];

//echo$eventId;
//print_r($_REQUEST);


// Prepare SQL query for event details
$eventSQL = "SELECT event.Title, event.Description, photo.Path AS picture, event.Venue, event.DateTime, 
                      category.CategoryName,event.Price
              FROM event
              JOIN photo ON photo.EventID = event.EventID
              JOIN category ON category.CategoryID = event.CategoryID
              WHERE event.EventID = ?;";

$eventstmt = $conn->prepare($eventSQL);
$eventstmt->bind_param("i", $eventId);
$eventstmt->execute();
$result = $eventstmt->get_result();
$title = $description = $picture = $venue = $dateTime = $price = null;

// Fetch data from the result set
if ($row = $result->fetch_assoc()) {
  $title = htmlspecialchars($row['Title']);
  $description = htmlspecialchars($row['Description']);
  $picture = htmlspecialchars($row['picture']);
  $venue = htmlspecialchars($row['Venue']);
  $dateTime = htmlspecialchars($row['DateTime']);
  $price = htmlspecialchars($row['Price']);

}

$date = new DateTime($dateTime);
$formattedDate = $date->format('d F Y');
$formattedTime = $date->format('H:i:s');
?>

<!DOCTYPE html>
<html lang='en'>

<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <link rel='stylesheet' href='EventInfo.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <title>Event Information</title>
  <style>
    * {
      box-sizing: border-box;
      /* Ensure padding doesn't affect width */
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
      color: #636B2F;
      /* Default link color */
      transition: color 0.3s ease;
    }

    a:hover {
      color: #533b4d;
      /* Hover color for links */
    }

    h1 {
      text-align: center;
    }

    /* Navigation Styles */
    .navigation {
      display: flex;
      justify-content: space-around;
      background-color: #636B2F;
      padding: 15px 0;
      position: fixed;
      /* Fix the navigation bar at the top */
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

    figure {
      display: block;
      margin: 10px auto;
      /* Add margin to give space around figure */
      width: 80%;
      /* Ensure the figure takes full width */
      text-align: center;
      /* Centers the caption inside the figure */
      max-width: 100%;
      /* Ensure the figure does not exceed its container */
      max-height: 80vh;
      /* Set a maximum height for the figure to avoid overflow */
      overflow: hidden;
      /* Hide overflow to prevent content spilling out */
    }

    img {
      width: 80%;
      /* Ensures the image doesn't overflow */
      height: auto;
      /* Maintain aspect ratio of the image */
      border: 3px solid #636B2F;
      /* Add a green border */
      border-radius: 5px;
      /* Optional: Rounded corners for images */
      max-height: 400px;
      /* Set a maximum height for the image */
      object-fit: cover;
      /* Ensures the image covers the entire area while preserving aspect ratio */
    }

    h1 {
      text-align: center;
    }

    .main {
      display: flex;
      height: calc(100vh - 75px - 350px);
      /* Full height minus navigation height and estimated footer height */
      overflow: auto;
      /* Allow scrolling if content exceeds height */
      margin-bottom: 10px;
      /* Adjust this value to control space below the main section */
    }

    .reviews {
      margin-top: 10px;
      /* Adjust this value to control space above the reviews section */
      padding: 20px;
      /* Optional: add padding for better inner spacing */
    }


    .event {
      flex: 1;
      /* Increase the space for the event section */
      padding-bottom: 20px;
      /* Optional: Add padding to the bottom for spacing */
    }

    .details {
      flex: 1;
      /* Takes half the space of event */
      padding: 20px;
      /* Padding around the content */
      border-radius: 5px;
      /* Rounded corners */
      max-width: 500px;
      /* Optional: Set a max width */
    }

    .event figure {
      width: 100%;
      /* Ensure figure takes full width of its container */
      margin: 0;
      /* Remove margin to utilize full space */
      padding: 10px;
      /* Optional: Add padding inside figure */
    }

    .details div {
      display: flex;
      margin-bottom: 10px;
      /* Space between rows */
    }

    /* Form Group Styles */
    .form-group {
      display: flex;
      /* Use flexbox to align labels and values side by side */
      margin-bottom: 15px;
      /* Space between each form group */
    }

    .label {
      flex: 1;
      /* Make label take equal space */
      padding: 10px;
      /* Padding inside the label */
      border-radius: 5px;
      /* Rounded corners for label */
    }

    .value {
      flex: 2;
      /* Make value take double space */
      padding: 10px;
      /* Padding inside the value */
      border: 1px solid #999;
      /* Border around the value */
      border-radius: 5px;
      /* Rounded corners for value */
      background-color: #fff;
      /* White background for the value */
    }

    figcaption {
      font-size: 1.5em;
      /* Increase the font size */
      font-weight: bold;
      /* Make the text bold */
      color: #333;
      /* Change the color if desired */
      text-align: center;
      /* Center the text */
      margin-top: 10px;
      /* Space between the image and caption */
    }

    .buy-button {
      display: block;
      width: 100%;
      /* Full width */
      padding: 10px;
      /* Padding for the button */
      background-color: #636B2F;
      /* Button background color */
      color: #fff;
      /* Button text color */
      font-size: 1.5em;
      /* Font size for button */
      font-weight: bold;
      /* Bold text for button */
      border: none;
      /* Remove border */
      border-radius: 5px;
      /* Rounded corners for button */
      text-align: center;
      /* Center text */
      text-decoration: none;
      /* No underline */
      margin-top: 20px;
      /* Space above button */
      transition: background-color 0.3s ease;
      /* Smooth transition */
    }

    .buy-button:hover {
      background-color: #533b4d;
      /* Darker color on hover */
    }

    /* Footer Styles */
    .footer {
      padding: 20px;
      /* Add padding to the footer */
      text-align: center;
      /* Center align footer content */
      background-color: #636B2F;
      /* Footer background color */
      color: #bec5ad;
      /* Footer text color */
      position: relative;
      /* Ensures it behaves well within the layout */
      margin-top: 20px;
      /* Space above footer */
    }

    footer {
      margin-top: 20px;
      /* Add space between the content and footer */
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .main {
        flex-direction: column;
        /* Stack elements vertically */
        height: auto;
        /* Allow height to auto-adjust */
      }

      .event {
        order: 1;
        /* Ensure event comes first */
      }

      .details {
        order: 2;
        /* Ensure details come after event */
      }
    }

    .buy-button {
      display: inline-block;
      /* Change to inline-block for better alignment */
      width: 100%;
      padding: 12px 20px;
      /* Increase padding for a larger clickable area */
      background-color: #636B2F;
      /* Button background color */
      color: #fff;
      /* Button text color */
      font-size: 1.5em;
      /* Font size for button */
      font-weight: bold;
      /* Bold text for button */
      border: none;
      /* Remove border */
      border-radius: 5px;
      /* Rounded corners for button */
      text-align: center;
      /* Center text */
      text-decoration: none;
      /* No underline */
      margin-top: 20px;
      /* Space above button */
      transition: background-color 0.3s ease, transform 0.3s ease;
      /* Smooth transition */
      cursor: pointer;
      /* Change cursor on hover */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      /* Subtle shadow for depth */
    }

    .buy-button:hover {
      background-color: #4C5A37;
      /* Darker color on hover */
      transform: translateY(-2px);
      /* Slight lift effect on hover */
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
      /* Enhance shadow on hover */
    }

    .buy-button:active {
      transform: translateY(0);
      /* Reset on click */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      /* Reset shadow on click */
    }

    .reviews {
      margin-top: 0px;
    }

    /* Footer Styles */
    footer {
      padding: 20px;
      text-align: center;
      color: #bec5ad;
    }

    footer a {
      color: #bec5ad;
      text-decoration: none;
    }

    footer a:hover {
      color: white;
    }

    @media (max-width: 600px) {
      footer {
        font-size: 14px;
      }
    }

    .review {

      display: flex;
      /* Use flexbox to align items */
      flex-direction: column;
      /* Stack items vertically */
      align-items: flex-start;
      /* Align items to the start */
      width: 95vw;
      /* Set the width to 90% of the viewport width */
      margin: 20px 0;
      /* Add vertical spacing */
      margin-left: 30px;
      border: 1px solid #ccc;
      /* Optional: Add a border */
      padding: 20px;
      /* Optional: Add padding */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      /* Optional: Add a shadow for better visibility */
      background-color: #fff;
      /* Optional: Background color */
      border: 2px solid #636B2F;
      /* Solid green border */
      border-radius: 10px;
      transition: transform 0.3s ease;
      /* Smooth transition for scaling */
    }

    .review:hover {
      background-color: #f0f0f0;
      /* Light gray background on hover */
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      /* Enhance shadow for emphasis */

    }

    .top {
      display: flex;
      width: 100%;
      /* Full width for top section */
    }

    .top-left {
      display: flex;
      /* Use flexbox for layout */
      align-items: center;
      /* Center align items vertically */
      flex: 1;
      /* Allow it to take available space */
    }

    .top-left img {
      width: 100px;
      /* Set a fixed width for images */
      height: auto;
      /* Maintain aspect ratio */
      margin-right: 20px;
      /* Space between image and title */
      border-radius: 100px;
    }

    .top-right {
      display: flex;
      /* Use flexbox for layout */
      justify-content: flex-end;
      /* Align items to the right */
      align-items: center;
      /* Center align items vertically */
      margin-left: 20px;
    }

    .rating {
      display: flex;
      /* Use flexbox for the rating stars */
    }

    .button-container {
      display: flex;
      /* Use flexbox for alignment */
      justify-content: center;
      /* Center content horizontally */
      margin-top: 20px;
      /* Space above the container */
    }

    .rating__star {
      color: #ccc;
      /* Default color for unfilled stars */
      font-size: 24px;
      /* Adjust size as needed */
    }

    .rating__star.fas {
      color: #FFD700;
      /* Color for filled stars (gold) */
    }

    .comment {
      margin-top: 15px;
      /* Add spacing above the comment */
      font-size: 20px;
      /* Adjust font size as needed */
      color: #333;
      /* Change text color if desired */
      max-width: calc(100% - 200px);
      /* Ensures it doesn't overlap with stars */
      overflow-wrap: break-word;
      /* Ensure long words wrap */
    }

    .event-button {
      background-color: #636B2F;
      /* Button color */
      color: white;
      /* Text color */
      padding: 10px 20px;
      /* Padding around the button */
      border: none;
      /* No borders */
      cursor: pointer;
      /* Pointer cursor on hover */
      font-size: 16px;
      /* Button font size */
      border-radius: 5px;
      /* Rounded corners */
      transition: background-color 0.3s ease;
      /* Smooth transition */
    }

    .event-button:hover {
      background-color: #bec5ad;
      /* Button color on hover */
    }
  </style>
</head>

<body>
  <nav class='navigation'>
    <a href='index.php'>HOME</a>
    
    <a href='../login/logout.php'>LOG OUT</a>
  </nav>
  <div class="main">
    <div class="event">
      <figure class="figure">
        <img src="<?php echo $picture ?>" alt="">
        <figcaption><?php echo $description ?> </figcaption>
      </figure>
    </div>
    <div class="details">
      <h2><?php echo $title ?></h2>
      <div class="form-group">
        <div class="label venue-details">
          <p class="venue">Venue:</p>
        </div>
        <div class="value">
          <p class="location"><?php echo $venue ?></p>
        </div>
      </div>
      <div class="form-group">
        <div class="label date-details">
          <p class="dateTitle">Date:</p>
        </div>
        <div class="value">
          <p class="date"><?php echo $formattedDate ?></p>
        </div>
      </div>
      <div class="form-group">
        <div class="label time-details">
          <p class="timeTitle">Time:</p>
        </div>
        <div class="value">
          <p class="time"><?php echo $formattedTime ?></p>
        </div>
      </div>
      <div class="form-group">
        <div class="label price-details">
          <p class="priceTitle">Price:</p>
        </div>
        <div class="value">
          <p class="price"><?php echo $price ?></p>
        </div>
      </div>
      <div class="button-container">
        <?php
        $currentDateTimeObj = new DateTime();
        if ($currentDateTimeObj < $date) {
         
          echo "
          <form action='AddToCart.php' method='POST'>
              <input type='hidden' name='eventID' value='" . $eventId . "'>
              <button type='submit' class='event-button'>Add To Cart</button>
          </form>
      ";
        } else {
          // Display the "Leave a Review" button
          echo "
          <form action='reviewPopUp.php' method='POST'>
              <input type='hidden' name='eventID' value='" . $eventId . "'>
              <button type='submit' class='event-button'>Leave a Review</button>
          </form>
      ";
        }
        ?>
      </div>
    </div>

  </div>


  <!-- Move reviews section here -->
  </div>
  <div class="reviews">
   
    <?php

// SQL query to get reviews
$getReviewsSQL = "SELECT rating.RatingNumber, rating.Description, account.Username, rating.RatingID 
                  FROM rating
                  JOIN event ON event.EventID = rating.EventID
                  JOIN account ON account.AccountID = rating.AccountID
                  WHERE rating.EventID = ?";

// Prepare the SQL statement
$getReviewsSQLstmt = $conn->prepare($getReviewsSQL);
$getReviewsSQLstmt->bind_param("i", $eventId);
$getReviewsSQLstmt->execute();
$getReviewsSQLstmt->bind_result($ratingNumber, $reviewDescription, $username, $ratingID);

// Store reviews in an array to count them
$reviews = [];
while ($getReviewsSQLstmt->fetch()) {
    $reviews[] = [
        'ratingNumber' => $ratingNumber,
        'reviewDescription' => $reviewDescription,
        'username' => $username,
        'ratingID' => $ratingID
    ];
}

// Check if there are any reviews
if (count($reviews) > 0) {
    // Loop through each review and display it
    echo"<H1>Reviews</H1>";
    foreach ($reviews as $review) {
        echo "<div class='review' id='review'>
                <div class='top' id='top'>
                    <div class='top-left' id='top-left'>
                        <p class='username' id='username'>Reviewed by: " . htmlspecialchars($review['username']) . "</p>
                    </div>
                    <div class='top-right' id='top-right'>
                        <div class='rating'>";

        // Loop to display stars
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $review['ratingNumber']) {
                echo "<i class='rating__star fas fa-star' data-value='$i'></i>"; // Filled star
            } else {
                echo "<i class='rating__star far fa-star' data-value='$i'></i>"; // Unfilled star
            }
        }

        echo "</div>
                    </div>
                </div>
                <div class='comment' id='comment'>
                    " . htmlspecialchars($review['reviewDescription']) . "
                </div> 
            </div>"; // Close the .review div
    }
} 

// Close the statement after fetching
$getReviewsSQLstmt->close();
?>
  </div>

  <footer style="text-align:center;background-color: #636B2F;">
    <p style="color: #bec5ad;">&copy Author: Code Crusaders | <a href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</a></p>
  </footer>

</body>


</html>