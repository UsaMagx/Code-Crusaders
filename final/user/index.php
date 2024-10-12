<?php
require_once("../login/secure.php");

$accountID = $_SESSION['acc_id'];

require_once("../Config.php");

// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}

// Count the items in the cart for the logged-in user
$sql = "SELECT COUNT(*) AS total FROM codecrusaders.account
        INNER JOIN codecrusaders.user ON account.AccountID=user.accountID
        INNER JOIN codecrusaders.ticket ON ticket.userID=user.userID 
        WHERE status='Add to cart' AND account.accountID='$accountID';";

// Execute the query
$result = $conn->query($sql);
$totalItems = 0; // Default value for total items
if ($result && $row = $result->fetch_assoc()) {
    $totalItems = $row['total']; // Get the total count
} else {
    die("UNABLE TO UPDATE THE RECORD");
}

// Fetch events for display
$sqldisplay = "SELECT event.Title, event.Description, photo.Path, category.CategoryName, event.EventID 
               FROM event AS event
               INNER JOIN category AS category ON event.CategoryID = category.CategoryID
               INNER JOIN photo AS photo ON event.EventID = photo.EventID
               ORDER BY category.CategoryName;";
$resultdisplay = $conn->query($sqldisplay);

// Initialize an array to hold events by category
$eventsByCategory = [];
if ($resultdisplay && $resultdisplay->num_rows > 0) {
    while ($row = $resultdisplay->fetch_assoc()) {
        $eventsByCategory[$row['CategoryName']][] = [
            'title' => $row['Title'],
            'description' => $row['Description'],
            'image' => $row['Path'],
            'EventID' => $row['EventID']
        ];
    }
} else {
   
    echo "No events found!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="indexStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Home</title>
    <style>
 

    </style>
</head>

<body>
    <nav class="navigation">
        <a href="#" style="color: #bec5ad">HOME</a>
        <a href="../login/logout.php" style="color: #bec5ad">LOG OUT</a>
    </nav>

    <div class="sideAndMain">
        <div class="sideBar">
            <div class="search sideElement">
                <input type="text" id="myInput" placeholder="Search." title="Type in a name">
            </div>

            <div class="category sideElement">
                <button class="category">Categories</button>
                <div class="drop-content">
                    <a href="#sport">Sport</a>
                    <a href="#movies">Movies</a>
                    <a href="#Theatre">Theatre</a>
                    <a href="#music-festival">Music Festival</a>
                    <a href="#comedy">Comedy</a>
                    <a href="#art-exhibition">Art Exhibition</a>
                </div>
            </div>
            <a href="myReviews.php"><button class="my-reviews">My Reviews</button></a>

            <button onclick="window.location.href='cartPage.php'" class="normal">
                <i name="icons" class="fa fa-shopping-cart"> Cart</i>
                <span class="badge"><?php echo $totalItems; ?></span>
            </button>

            <button onclick="window.location.href='Profile.php'" class="normal">Profile</button>
            <button onclick="window.location.href='MyTickets.php'" class="normal">My Tickets</button>
        </div>

        <article style="background-color: #f5f1e3;">
            <header style="text-align:center;font-family:verdana">
                <h1>TicketHub</h1>
                <h2>Find the event you're looking for! Book your ticket.</h2>
                <p>TicketHub is a user-friendly website dedicated to helping visitors discover and book a wide range of events. The site caters to both registered and unregistered users, providing a comprehensive view of upcoming events with detailed descriptions.</p>
            </header>
            <br>
            

            <div class="slideshow-container">
                <div class="mySlides fade">
                    <div class="numbertext">1 / 3</div>
                    <img src="../images/events/tara-mae-miller-UnQ1JWNs8F4-unsplash.jpg" style="width:100%;">
                    <div class="text">Caption Text</div>
                </div>

                <div class="mySlides fade">
                    <div class="numbertext">2 / 3</div>
                    <img src="../images/events/anthony-delanoix-hzgs56Ze49s-unsplash.jpg" style="width:100%;">
                    <div class="text">Caption Two</div>
                </div>

                <div class="mySlides fade">
                    <div class="numbertext">3 / 3</div>
                    <img src="../images/events/kyle-head-p6rNTdAPbuk-unsplash.jpg" style="width:100%;">
                    <div class="text">Caption Three</div>
                </div>

                <a class="prev" onclick="plusSlides(-1)">❮</a>
                <a class="next" onclick="plusSlides(1)">❯</a>
            </div>
        </div>
        <br>

        <div style="text-align:center" class="dot-container">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
        <br>

        <h1 id="upcoming-events">Upcoming Events</h1>
        <br>

        <?php
        $categories = ['Comedy', 'Sport', 'Movies', 'Theatre', 'Music Festival', 'Art Exhibition'];

        foreach ($categories as $category) {
            $urlFriendlyId = strtolower(str_replace(' ', '-', $category));
            echo "<h2 id='{$urlFriendlyId}'>{$category}</h2>";

            if (isset($eventsByCategory[$category]) && count($eventsByCategory[$category]) > 0) {
                echo '<div class="events">';
                foreach ($eventsByCategory[$category] as $event) {
                    $dbImagePath = $event['image'];
                    $eventID = $event['EventID'];

                    // Define a base path for the display
                    $basePath = '../creator/'; // Adjust this if needed to reflect your directory structure

                    // Determine the correct image path to display
                    $imagePath = '';
                    if (strpos($dbImagePath, 'events/') === 0 || strpos($dbImagePath, 'images/') === 0) {
                        $imagePath = $basePath . $dbImagePath; // Prepend base path
                    } else {
                        $imagePath = $basePath . 'events/' . $dbImagePath; // Adjust based on your structure
                    }

                    echo '<figure class="event">
                            <form method="post" action="eventInfo.php">
                <input type="hidden" name="eventID" value="' . htmlspecialchars($eventID) . '">
                <button type="submit" class="event-button">
                    <img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($event['title']) . '">
                    <figcaption>
                        <h3>' . htmlspecialchars($event['title']) . '</h3>
                        <p>' . htmlspecialchars($event['description']) . '<br></p>
                    </figcaption>

                </button>
            </form>
                          </figure>';
                }
                echo '</div>';
            } else {
                echo "<p style=\"padding-left:22%;\">No events found in this category.</p>";
            }
        }
        ?>
        </article>
    </div>

    <footer style="text-align:center;background-color: #636B2F;">
        <p style="color: #bec5ad;">&copy Author: Code Crusaders | <a href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</footer>

        <script src="indexscript.js"></script>
</body>
</html>
