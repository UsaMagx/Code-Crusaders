<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="indexStyle.css">
  <title>Document</title>
</head>

<body>
  <!-- Index page will be visible to registered and unregistered users. They will see what events are upcoming -->

  <nav class="navigation">
    <a href="index.html" style="color: #bec5ad">HOME</a>
    <a href="about.html" style="color: #bec5ad">ABOUT</a>
    <a href="contact.html" style="color: #bec5ad">CONTACT</a>
    <a href="login/signup.html" style="color: #bec5ad">SIGN IN</a>
    <a href="login/login.php" style="color: #bec5ad">LOG IN</a>
  </nav>

  <div class="sideAndMain">
    <div class="sideBar">
      <!-- this is a placeholder for now, will be able to operate properly with php -->
      <div class="search sideElement">
        <input type="text" id="myInput" placeholder="Search." title="Type in a name">
      </div>

      <div class="category sideElement">
        <button class="category">Categories</button>
        <div class="drop-content">
        <a href="#sport">Sport</a>
          <a href="#movies">Movies</a>
          <a href="#Theatre">Theatre</a>
          <a href="#music festival">Music Festival</a>
          <a href="#comedy">Comedy</a>
          <a href="#art exhibition">Art Exhibition</a>
        </div>
      </div>
     
   
    </div>
    
    <article style="background-color: #f5f1e3;">
      <header style="text-align:center;font-family:verdana">
        <h1>TicketHub</h1>
        <h2>Find the event you're looking for! Book your ticket.</h2>
        <p>TicketHub is a user-friendly website dedicated to helping visitors discover and book a wide range of events.
          The site caters to both registered and unregistered users, providing a comprehensive view of upcoming events
          with detailed descriptions.</p>
      </header>
      <br>


      <div class="slideshow-container">
        <div class="mySlides fade">
          <div class="numbertext">1 / 3</div>
          <img src="images/events/tara-mae-miller-UnQ1JWNs8F4-unsplash.jpg" style="width:100%;">
          <div class="text">Caption Text</div>
        </div>

        <div class="mySlides fade">
          <div class="numbertext">2 / 3</div>
          <img src="images/events/anthony-delanoix-hzgs56Ze49s-unsplash.jpg" style="width:100%;"> 
          <div class="text">Caption Two</div>
        </div>

        <div class="mySlides fade">
          <div class="numbertext">3 / 3</div>
          <img src="images/events/kyle-head-p6rNTdAPbuk-unsplash.jpg" style="width:100%;">
          <div class="text">Caption Three</div>
        </div>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
      </div>
      <br>

      <div style="text-align:center" class="dot-container">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
      </div>
      <br>

      <h1>Upcoming Events</h1>
      <hr>

      <?php
      session_start();

      require_once("Config.php");
      
      // take the new values from the form
      
      
      // Connect to the database
      $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
      
      if ($conn->connect_error) {
        die("Connection to database failed!"); // If failed to connect
      }
      $sqldisplay = "SELECT event.Title, event.Description, photo.Path, category.CategoryName 
                FROM event
                INNER JOIN category ON event.CategoryID = category.CategoryID
                INNER JOIN photo ON event.EventID = photo.EventID
                ORDER BY category.CategoryName;";

$resultdisplay = $conn->query($sqldisplay);

// Initialize an array to hold events by category
$eventsByCategory = [];

if ($resultdisplay && $resultdisplay->num_rows > 0) {
    while ($row = $resultdisplay->fetch_assoc()) {
        $eventsByCategory[$row['CategoryName']][] = [
            'title' => $row['Title'],
            'description' => $row['Description'],
            'image' => $row['Path']
        ];
    }
} else {
    echo "No events found in the database.";
}

            $categories = ['Comedy', 'Sport', 'Movies', 'Theatre', 'Music Festival', 'Art Exhibition'];

            foreach ($categories as $category) {
              $urlFriendlyId = strtolower(str_replace(' ', '-', $category));
    
              echo "<h2 id='{$urlFriendlyId}'>{$category}</h2>";
                if (isset($eventsByCategory[$category]) && count($eventsByCategory[$category]) > 0) {
                    echo '<div class="events">';
                    foreach ($eventsByCategory[$category] as $event) {
                      $dbImagePath = $event['image'];

                      // Define a base path for the display
                      $basePath = 'creator/'; // Adjust this if needed to reflect your directory structure
                  
                      // Determine the correct image path to display
                      $imagePath = '';
                  
                      // Check the path stored in the database
                      if (strpos($dbImagePath, 'events/') === 0) {
                          // If it starts with 'events/', use it directly
                          $imagePath = $basePath . $dbImagePath; // Prepend base path
                      } elseif (strpos($dbImagePath, 'images/') === 0) {
                          // If it starts with 'images/', use it directly
                          $imagePath = $basePath . $dbImagePath; // Prepend base path
                      } else {
                          // If it's a different path format, adjust accordingly
                          $imagePath = $basePath . 'events/' . $dbImagePath; // Adjust based on your structure
                      }
                      
                      echo '<figure class="event">
                      <a href="#">
                          <img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($event['title']) . '">
                          <figcaption>
                              <h3>' . htmlspecialchars($event['title']) . '</h3>
                              <p>' . htmlspecialchars($event['description']) . '<br></p>
                              
                          </figcaption>
                      </a>
                    </figure>';
                    }
                    echo '</div>';
                } else {
                    echo "<p style=\"padding-left:22%;\">No events found in this category.</p>";
                }
            }
            ?>

  <footer style="text-align:center;background-color: #636B2F;">
    <p style="color: #bec5ad;">&copy Author: Code Crusaders | <a
        href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</a></p>
  </footer>
  <script src="indexscript.js"></script>
</body>

</html>
